<?php

class Financial_reports_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_total_sales($start_date, $end_date, $sale_origin = 'all', $invoice_type = null)
    {
        $test_companies = get_company_sids_excluded_from_reporting();

        $this->db->select('*');
        //Exclude Test Companies
        if (!empty($test_companies)) {
            $this->db->where_not_in('company_sid', $test_companies);
        }

        if ($sale_origin != 'all') {
            $this->db->where('processed_from', $sale_origin);
        }

        if($invoice_type != null){
            $this->db->where('invoice_type', $invoice_type);
        }

        $this->db->order_by('sid', 'DESC');

        $this->db->where("receipt_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $receipts = $this->db->get('receipts')->result_array();

        $total_sales = 0;

        if (!empty($receipts)) {
            foreach ($receipts as $receipt) {
                $total_sales = $total_sales + $receipt['amount'];
            }
        }

        return $total_sales;
    }

    function get_receipts($start_date, $end_date, $sale_origin = 'all', $invoice_type = null)
    {
        $test_companies = get_company_sids_excluded_from_reporting();

        $this->db->select('receipts.*');
        $this->db->select('users.CompanyName');

        //Exclude Test Companies
        if (!empty($test_companies)) {
            $this->db->where_not_in('receipts.company_sid', $test_companies);
        }

        if($invoice_type != null){
            $this->db->where('invoice_type', $invoice_type);
        }

        if ($sale_origin != 'all') {
            $this->db->where('receipts.processed_from', $sale_origin);
        }

        $this->db->where("receipts.receipt_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

        $this->db->join('users', 'users.sid = receipts.company_sid', 'left');

        $this->db->order_by('receipts.sid', 'DESC');

        $receipts = $this->db->get('receipts')->result_array();

        return $receipts;
    }

    function get_invoice_item_sids($invoice_sid, $origin)
    {
        $item_sids = array();

        switch ($origin) {
            case 'super_admin':
                $this->db->select('item_sid');
                $this->db->where('invoice_sid', $invoice_sid);

                $items = $this->db->get('admin_invoice_items')->result_array();

                if (!empty($items)) {
                    foreach ($items as $item) {
                        $item_sids[] = $item['item_sid'];
                    }
                }

                break;
            case 'employer_portal':
                $this->db->select('product_sid');
                $this->db->where('sid', $invoice_sid);

                $items = $this->db->get('invoices')->result_array();

                if (!empty($items)) {
                    $item_sids = explode(',', $items[0]['product_sid']);
                }
                break;
        }

        return $item_sids;
    }

    function get_invoice_detail($invoice_sid, $origin)
    {
        $my_return = array();
        switch ($origin) {
            case 'super_admin':
                $this->db->select('*');
                $this->db->where('sid', $invoice_sid);
                $invoice = $this->db->get('admin_invoices')->result_array();

                if (!empty($invoice)) {
                    $invoice = $invoice[0];

                    $this->db->select('*');
                    $this->db->where('invoice_sid', $invoice_sid);
                    $items = $this->db->get('admin_invoice_items')->result_array();

                    $invoice['invoice_items'] = $items;
                }

                $my_return = $invoice;

                break;
            case 'employer_portal':
                $this->db->select('invoices.*');
                $this->db->select('users.CompanyName as company_name');
                $this->db->where('invoices.sid', $invoice_sid);

                $this->db->join('users', 'users.sid = invoices.company_sid', 'left');
                $invoice = $this->db->get('invoices')->result_array();

                if (!empty($invoice)) {
                    $invoice = $invoice[0];

                    $organized_items = array();
                    $items = unserialize($invoice['serialized_items_info']);

                    $item_array = array();
                    for ($count = 0; $count < count($items['products']); $count++) {
                        $product_info = $this->get_product_details($items['products'][$count]);

                        $item_array['custom_text'] = $items['custom_text'][$count];
                        $item_array['item_qty'] = $items['item_qty'][$count];
                        $item_array['item_price'] = $items['item_price'][$count];
                        $item_array['product_sid'] = $items['products'][$count];
                        $item_array['item_remaining_qty'] = $items['item_remaining_qty'][$count];
                        $item_array['flag'] = $items['flag'][$count];

                        $item_array['total_price'] = $items['item_price'][$count];

                        if (isset($items['total_cost'])) {
                            $item_array['total_cost'] = $items['total_cost'][$count];
                        } else {
                            //$item_array['total_cost'] = $items['item_price'][$count];
                            $item_array['total_cost'] = 0;
                        }


                        $item_array['total_profit'] = $item_array['total_price'] - $item_array['total_cost'];

                        $organized_items[] = $item_array;
                    }
                    $invoice['invoice_items'] = $organized_items;

                    $my_return = $invoice;

                }
                break;
        }

        return $my_return;
    }


    function calculate_invoice_profit($invoice_sid, $origin)
    {
        $invoice = $this->get_invoice_detail($invoice_sid, $origin);

        if (!empty($invoice)) {
            $invoice_value = 0;
            $invoice_discount = 0;
            $invoice_discount_percentage = 0;
            $invoice_value_after_discount = 0;

            switch ($origin) {
                case 'super_admin':
                    $invoice_value = $invoice['value'];
                    $invoice_discount = $invoice['discount_amount'];
                    $invoice_value_after_discount = $invoice['total_after_discount'];
                    break;
                case 'employer_portal':
                    $invoice_value = $invoice['sub_total'];
                    $invoice_discount = $invoice['total_discount'];
                    $invoice_value_after_discount = $invoice['total'];
                    break;
            }

            if ($invoice_discount > 0) {
                $invoice_discount_percentage = (($invoice_discount / $invoice_value) * 100);
            } else {
                $invoice_discount_percentage = 0;
            }

            $invoice_items = $invoice['invoice_items'];


            $total_price = 0;
            $total_cost = 0;
            $total_profit = 0;

            foreach ($invoice_items as $item) {
                switch ($origin) {
                    case 'super_admin':
                        /*
                        if ($item['cost_price'] > 0) {
                            $cost = $item['quantity'] * $item['cost_price'];
                        } else {
                            $cost = $item['quantity'] * $item['unit_price'];
                        }
                        */

                        $cost = $item['quantity'] * $item['cost_price'];


                        $price = $item['quantity_total'];

                        $profit = $price - $cost;

                        $total_price = $total_price + $price;
                        $total_cost = $total_cost + $cost;
                        $total_profit = $total_profit + $profit;


                        break;
                    case 'employer_portal':
                        $price = $item['total_price'];
                        $cost = $item['total_cost'];
                        $profit = $item['total_profit'];


                        $total_price = $total_price + $price;
                        $total_cost = $total_cost + $cost;
                        $total_profit = $total_profit + $profit;
                        break;
                }
            }

            $invoice_amount = 0;
            switch ($origin) {
                case 'super_admin':
                    /*
                    if($invoice['is_discounted'] == 1){
                        $invoice_amount = $invoice['total_after_discount'];
                    } else {
                        $invoice_amount = $invoice['value'];
                    }
                    */

                    $invoice_amount = $invoice['total_after_discount'];
                    break;
                case 'employer_portal':
                    $invoice_amount = $invoice['total'];
                    break;
            }


            $invoice['total_price'] = $total_price;
            $invoice['total_cost'] = $total_cost;
            $invoice['total_profit'] = $total_profit;
            $invoice['total_projected_profit'] = $total_profit - ($total_profit * ($invoice_discount_percentage / 100));
            $invoice['total_actual_profit'] = $invoice_amount - $total_cost;
            $invoice['total_discount_percentage'] = $invoice_discount_percentage;
        }

        return $invoice;

    }

    function get_invoices($origin, $from_date, $to_date, $payment_status = 'paid') {
        switch ($origin) {
            case 'super_admin':
                $this->db->select('sid');
                $this->db->order_by('sid', 'DESC');

                if($payment_status == 'paid') {
                    $this->db->where('payment_status', 'paid');
                } elseif($payment_status == 'unpaid'){
                    $this->db->where('payment_status', 'unpaid');
                    $this->db->where('invoice_status !=', 'deleted');
                }

                $this->db->where("created BETWEEN '" . $from_date . "' AND '" . $to_date . "'");
                $invoice_sids = $this->db->get('admin_invoices')->result_array();
                break;
            case 'employer_portal':
                $this->db->select('sid');
                $this->db->order_by('sid', 'DESC');

                if($payment_status == 'paid') {
                    $this->db->where('status', 'Paid');
                } elseif($payment_status == 'unpaid'){
                    $this->db->where('status', 'Unpaid');
                }

                $this->db->where('payment_method !=', 'background_check_refund');
                $this->db->where("date BETWEEN '" . $from_date . "' AND '" . $to_date . "'");
                $invoice_sids = $this->db->get('invoices')->result_array();
                break;
        }

        $all_invoices = array();
        if (!empty($invoice_sids)) {
            foreach ($invoice_sids as $invoice_sid) {
                $invoice_data = $this->calculate_invoice_profit($invoice_sid['sid'], $origin);
                $all_invoices[] = $invoice_data;
            }
        }

        return $all_invoices;
    }

    function get_product_details($product_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $product_sid);
        $product = $this->db->get('products')->result_array();

        if (!empty($product)) {
            return $product[0];
        } else {
            return array();
        }
    }

    function get_invoices_by_products_sid($product_sid, $from_date, $to_date, $show_all_items = false)
    {
        $this->db->select('invoices.*');
        $this->db->select('users.CompanyName');

        $this->db->order_by('invoices.date', 'DESC');

        $this->db->where("invoices.date BETWEEN '" . $from_date . "' AND '" . $to_date . "'");

        $this->db->where('FIND_IN_SET(' . $product_sid . ', product_sid )');

        $this->db->join('users', 'users.sid = invoices.company_sid', 'left');

        $invoices = $this->db->get('invoices')->result_array();

        $excluded_companies = get_company_sids_excluded_from_reporting();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                if(in_array($invoice['company_sid'], $excluded_companies)){
                    unset($invoices[$key]);
                } else {
                    $organized_items = array();
                    $items = unserialize($invoice['serialized_items_info']);

                    $item_array = array();
                    for ($count = 0; $count < count($items['products']); $count++) {

                        $product_info = $this->get_product_details($items['products'][$count]);

                        $item_array['custom_text'] = $items['custom_text'][$count];

                        if($items['item_price'][$count] % $product_info['price'] == 0){
                            $quantity = $items['item_price'][$count] / $product_info['price'];

                            $item_array['item_qty'] = $quantity;

                            $item_array['total_price'] = $quantity * $product_info['price'];
                            $item_array['total_cost'] = $quantity * $product_info['cost_price'];

                        } else {
                            $item_array['item_qty'] = $items['item_qty'][$count];

                            $item_array['total_price'] = $items['item_qty'][$count] * $product_info['price'];
                            $item_array['total_cost'] = $items['item_qty'][$count] * $product_info['cost_price'];
                        }




                        $item_array['item_price'] = $items['item_price'][$count];
                        //$item_array['product_sid'] = $items['products'][$count];
                        $item_array['item_remaining_qty'] = $items['item_remaining_qty'][$count];
                        $item_array['flag'] = $items['flag'][$count];


                        if ($product_info['cost_price'] <= 0 || $product_info['cost_price'] == null) {
                            $product_info['cost_price'] = $product_info['price'];
                        }


                        $item_array['total_profit'] = $item_array['total_price'] - $item_array['total_cost'];


                        if ($show_all_items == false) {
                            if ($product_sid == $product_info['sid']) {
                                $organized_items[] = array_merge($item_array, $product_info);
                            }
                        } else {
                            $organized_items[] = array_merge($item_array, $product_info);
                        }
                    }


                    $invoices[$key]['invoice_items'] = $organized_items;
                }
            }
        }

        return $invoices;
    }

    function get_job_board_product_usage($product_sid, $start_date, $end_date)
    {
        $this->db->select('jobs_to_feed.*');
        $this->db->select('jobs_to_feed.purchased_date as usage_date');
        $this->db->select('users.CompanyName as company_name');
        $this->db->select('products.name as product_name');
        $this->db->select('products.price as product_price');
        $this->db->select('products.cost_price as product_cost_price');
        $this->db->select('products.expiry_days');

        $this->db->order_by('jobs_to_feed.sid', 'DESC');

        $this->db->where('jobs_to_feed.product_sid', $product_sid);
        $this->db->where("jobs_to_feed.purchased_date BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

        $this->db->join('users', 'users.sid = jobs_to_feed.company_sid', 'left');
        $this->db->join('products', 'products.sid = jobs_to_feed.product_sid', 'left');

        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_accurate_background_product_usage($product_sid, $start_date, $end_date)
    {
        $this->db->select('background_check_orders.*');
        $this->db->select('background_check_orders.date_applied as usage_date');
        $this->db->select('users.CompanyName as company_name');
        $this->db->select('products.name as product_name');
        $this->db->select('products.price as product_price');
        $this->db->select('products.cost_price as product_cost_price');
        $this->db->select('products.expiry_days');

        $this->db->order_by('background_check_orders.sid', 'DESC');

        $this->db->where('background_check_orders.product_sid', $product_sid);
        $this->db->where('background_check_orders.order_refunded', 0);
        $this->db->where("background_check_orders.date_applied BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

        $this->db->join('users', 'users.sid = background_check_orders.company_sid', 'left');
        $this->db->join('products', 'products.sid = background_check_orders.product_sid', 'left');

        return $this->db->get('background_check_orders')->result_array();
    }

    function get_sms_data ($company_sid, $start_date, $end_date) {
        $this->db->select('*');

        //Exclude Test Companies
        if ($company_sid != 'all') {
            $this->db->where('company_id', $company_sid);
        }

        $this->db->where('sender_user_id <>', 1);

        $this->db->where("created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

        $this->db->order_by('sid', 'ASC');

        $sms_data = $this->db->get('portal_sms')->result_array();


        if (!empty($sms_data)) {
            return $sms_data;
        } else {
            return array();
        }

    }

    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }


}