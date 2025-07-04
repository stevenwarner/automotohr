<?php

class Admin_invoices_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function Insert_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $quantity, $number_of_rooftops, $item_details = array(), $custom_flag = 0) {
        $dataToSave = array();
        $dataToSave['invoice_sid'] = $invoice_sid;
        $dataToSave['invoice_number'] = $invoice_number;
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['quantity'] = $quantity;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;

        if (!empty($item_details)) {
            $unitPrice = $item_details['unit_price'];

            if (!$custom_flag) {
                $rooftopsTotal = $number_of_rooftops * $unitPrice;
                $quantityTotal = $quantity * $rooftopsTotal;
            } else {
                $rooftopsTotal = $unitPrice;
                $quantityTotal = $rooftopsTotal;
            }

            $dataToSave['unit_price'] = $unitPrice;
            $dataToSave['rooftops_total'] = $rooftopsTotal;
            $dataToSave['quantity_total'] = $quantityTotal;
        }

        /**
         * item_sid,
         * item_name,
         * item_description,
         * unit_price,
         * number_of_employees,
         * includes_deluxe_theme,
         * includes_facebook_api,
         * number_of_days
         *
         * item_details array should contain all these fields.
         */
        $dataToSave = array_merge($dataToSave, $item_details);
        $this->db->insert('admin_invoice_items', $dataToSave);
    }

    function Insert_invoice($created_by, $company_sid, $company_name, $company_email) {
        $dataToSave = array();
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['company_name'] = $company_name;
        $dataToSave['company_email'] = $company_email;
        $this->db->insert('admin_invoices', $dataToSave);
        return $this->db->insert_id();
    }

    function Save_invoice($created_by, $company_sid, $item_ids = array(), $item_id_to_number_of_rooftops = array(), $item_id_to_quantity = array(), $invoice_type = 'manual', $custom_price = 0, $cost_price = 0) {
        $company_info = $this->Get_company_information($company_sid); //Get Company Info
        $admin_invoice_sid = 0;

        if (!empty($company_info)) {
            $company_info = $company_info[0];
            $company_admin = $this->Get_company_admin_information($company_sid);
            $company_name = $company_info['CompanyName'];
            $company_email = $company_info['email'];

            if (!empty($company_admin)) {
                $company_admin = $company_admin[0];
                $company_email = $company_admin['email'];
            }

            //Insert New Invoice Record to Get Invoice sid
            $invoice_sid = $this->Insert_invoice($created_by, $company_sid, $company_name, $company_email);
            $admin_invoice_sid = $invoice_sid;
            $invoice_number = $this->Update_invoice_number($invoice_sid); //Update Invoice Number
            $this->Update_invoice_type($invoice_sid, $invoice_type); //Update Invoice Type
            $products = $this->Get_products($item_ids); //Get Items

            /**
             * item_sid,
             * item_name,
             * item_description,
             * unit_price,
             * number_of_employees,
             * includes_deluxe_theme,
             * includes_facebook_api,
             * number_of_days
             */
            foreach ($products as $product) {
                $original_price = $product['price'];
                $original_cost_price = $product['cost_price'];
                $custom_flag = 0;

                if ($custom_price != 0 && $original_price != $custom_price) {
                    $original_price = $custom_price;
                    $original_cost_price = $cost_price;
                    $this->update_admin_invoice($invoice_sid, array('is_custom' => 1));
                    $custom_flag = 1;
                }

                $item_detail = array();
                $item_detail['item_sid'] = $product['sid'];
                $item_detail['item_name'] = $product['name'];
                $item_detail['item_description'] = $product['short_description'];
                $item_detail['unit_price'] = $original_price;
                $item_detail['cost_price'] = $original_cost_price;
                $item_detail['number_of_employees'] = $product['maximum_number_of_employees'];
                $item_detail['includes_deluxe_theme'] = $product['includes_deluxe_theme'];
                $item_detail['includes_facebook_api'] = $product['includes_facebook_api'];
                $item_detail['number_of_days'] = $product['expiry_days'];

                if (empty($item_id_to_quantity) && empty($item_id_to_number_of_rooftops)) {
                    $this->Insert_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, 1, 1, $item_detail, $custom_flag);
                } else {
                    $this->Insert_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], $item_detail, $custom_flag);
                }
            }

            $this->Calculate_and_update_invoice_value($invoice_sid, 0);

            if (strtolower($invoice_type) == 'automatic') {
                return $invoice_sid;
            }
        }

        return $admin_invoice_sid;
    }

    function Get_company_information($company_sid) {
        $this->db->select('*');
        $this->db->limit(1);
        $this->db->where('sid', $company_sid);
        
        $record_obj = $this->db->get('users');
        $admin = $record_obj->result_array();
        $record_obj->free_result();
        
        return $admin;
    }

    function Get_company_admin_information($company_sid) {
        $this->db->select('*');
        $this->db->where('is_primary_admin', 1);
        $this->db->where('parent_sid', $company_sid);
        $record_obj = $this->db->get('users');
        $admin = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($admin)) {
            $this->db->select('*');
            $this->db->limit(1);
            $this->db->where('sid', intval($company_sid) + 1);
            $this->db->where('parent_sid', $company_sid);
            
            $record_obj = $this->db->get('users');
            $admin = $record_obj->result_array();
            $record_obj->free_result();
        }

        return $admin;
    }

    function Generate_invoice_number($invoice_sid) {
        $padded_sid = str_pad($invoice_sid, 6, '0', STR_PAD_LEFT);
        return STORE_CODE . '-' . $padded_sid;
    }

    function Update_invoice_number($invoice_sid) {
        $invoice_number = $this->Generate_invoice_number($invoice_sid);
        $dataToSave = array();
        $dataToSave['invoice_number'] = $invoice_number;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
        return $invoice_number;
    }

    function Calculate_and_update_invoice_value($invoice_sid, $discount_value = 0) {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        
        $record_obj = $this->db->get('admin_invoice_items');
        $invoice_items = $record_obj->result_array();
        $record_obj->free_result();
        $invoice_total = 0;
        
        if (!empty($invoice_items)) {
            foreach ($invoice_items as $item) {
                $item_value = floatval($item['quantity_total']);
                $invoice_total += $item_value;
            }
        }

        $invoice_total = $invoice_total - $discount_value;
        $dataToSave = array();
        $dataToSave['value'] = $invoice_total;
        $dataToSave['total_after_discount'] = $invoice_total;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Get_products($product_ids = array()) {
        $cs_product_ids = implode(',', $product_ids);
        $this->db->select('*');

        if (!empty($product_ids)) {
            $this->db->where('sid IN ( ' . $cs_product_ids . ' )');
        }

        $record_obj = $this->db->get('products');
        $products = $record_obj->result_array();
        $record_obj->free_result();
        
        return $products;
    }

    function Get_all_admin_invoices($page_number, $invoices_per_page, $company_sid = null, $invoice_status = 'active', $from_date = null, $to_date = null, $payment_status = null, $payment_method = null) {
        $offset = 0;
        
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $invoices_per_page;
        }

        $this->db->select('*');
        $this->db->order_by('sid', 'DESC');
        $this->db->limit($invoices_per_page, $offset);

        if ($company_sid !== null) {
            $this->db->where('company_sid', $company_sid);
        }

        if ($invoice_status == 'active') {
            $this->db->where_in('invoice_status', array('active', 'due', 'overdue', 'baddebt'));
        }

        if ($from_date !== null && $to_date !== null) {
            $this->db->where('created BETWEEN \'' . $from_date . '\' AND \'' . $to_date . '\'');
        }

        if ($payment_status !== null) {
            $this->db->where('payment_status', $payment_status);
        }

        if ($payment_method !== null) {
            $this->db->where('payment_method', $payment_method);
        }

        $record_obj = $this->db->get('admin_invoices');
        $invoices = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $this->db->where('invoice_sid', $invoice['sid']);
                $query = $this->db->get('invoice_credit_notes');
                
                if ($query->num_rows() > 0) {
                    $invoices[$key]['payment_status'] = 'refunded';
                }

                $this->db->select('item_name');
                $this->db->where('invoice_sid', $invoice['sid']);
                
                $record_obj = $this->db->get('admin_invoice_items');
                $invoice_item_names = $record_obj->result_array();
                $record_obj->free_result();

                if (!empty($invoice_item_names)) {
                    $invoices[$key]['item_names'] = $invoice_item_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }

        return $invoices;
    }

    function Get_admin_invoice($invoice_sid, $get_items = false) {
        $this->db->select('*');
        $this->db->where('sid', $invoice_sid);
        $record_obj = $this->db->get('admin_invoices');
        $invoice = $record_obj->result_array();
        $record_obj->free_result();  
        $invoice_items = array();

        if (!empty($invoice)) {
            $invoice = $invoice[0];
            $this->db->where('invoice_sid', $invoice_sid);
            $query = $this->db->get('invoice_credit_notes');
            
            if ($query->num_rows() > 0) {
                $invoice['payment_status'] = 'refunded';
            }

            if ($get_items == true) {
                $this->db->select('*');
                $this->db->where('invoice_sid', $invoice_sid);
                $record_obj = $this->db->get('admin_invoice_items');
                $invoice_items = $record_obj->result_array();
                $record_obj->free_result(); 
            }

            $invoice['items'] = $invoice_items;
        }

        return $invoice;
    }

    function Get_commission_invoice($invoice_sid, $get_items = false) {
        $this->db->select('*');
        $this->db->where('sid', $invoice_sid);
        $record_obj = $this->db->get('commission_invoices');
        $invoice = $record_obj->result_array();
        $record_obj->free_result();  
        $invoice_items = array();

        if (!empty($invoice)) {
            $invoice = $invoice[0];

            if ($get_items == true) {
                $this->db->select('*');
                $this->db->where('invoice_sid', $invoice_sid);
                $record_obj = $this->db->get('commission_invoice_items');
                $invoice_items = $record_obj->result_array();
                $record_obj->free_result();
            }

            $invoice['items'] = $invoice_items;
        }

        return $invoice;
    }

    function Update_admin_invoice_discount($invoice_sid, $discount_percentage, $discount_amount, $total_after_discount) {
        $dataToSave = array();
        $dataToSave['discount_percentage'] = $discount_percentage;
        $dataToSave['discount_amount'] = $discount_amount;
        $dataToSave['total_after_discount'] = $total_after_discount;
        $dataToSave['is_discounted'] = 1;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_admin_invoice_status($invoice_sid, $status) {
        $dataToSave = array();
        $dataToSave['invoice_status'] = $status;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_admin_invoice_payment_status($invoice_sid, $status) {
        $dataToSave = array();
        $dataToSave['payment_status'] = $status;
        $dataToSave['payment_date'] = date('Y-m-d H:i:s');
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Get_admin_invoice_items($invoice_sid) {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        $record_obj = $this->db->get('admin_invoice_items');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function Update_admin_invoice_payment_description($invoice_sid, $payment_description) {
        $dataToSave = array();
        $dataToSave['payment_description'] = $payment_description;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_admin_invoice_payment_method($invoice_sid, $payment_method) {
        $dataToSave = array();
        $dataToSave['payment_method'] = $payment_method;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_payment_processed_by($invoice_sid, $admin_user_id) {
        $dataToSave = array();
        $dataToSave['payment_processed_by'] = $admin_user_id;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_development_fee_for_company($company_sid, $development_fee) {
        $this->db->where('sid', $company_sid);
        $dataToSave = array();
        $dataToSave['development_fee'] = $development_fee;
        $this->db->update('users', $dataToSave);
    }

    function Update_invoice_description($invoice_sid, $invoice_description) {
        $dataToSave = array();
        $dataToSave['invoice_description'] = $invoice_description;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Update_invoice_type($invoice_sid, $invoice_type) {
        $dataToSave = array();
        $dataToSave['invoice_type'] = $invoice_type;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function Mark_automatic_invoice_as_processed_on_manual_payment($invoice_sid, $payment_status, $payment_response_text) {
        $invoice = $this->Get_admin_invoice($invoice_sid);

        if ($invoice['invoice_type'] == 'automatic') {
            $dataToUpdate = array();
            $dataToUpdate['payment_processed'] = 1;
            $dataToUpdate['payment_date'] = date('Y-m-d H:i:s');
            $dataToUpdate['payment_status'] = $payment_status;
            $dataToUpdate['payment_response_text'] = $payment_response_text;
            $this->db->where('admin_invoice_sid', $invoice_sid);
            $this->db->update('recurring_payments_process_history', $dataToUpdate);
        }
    }

    function update_admin_invoice($invoice_sid, $data_to_update) {
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $data_to_update);
    }

    /*     * ***** Commission Invoices ****** */

    function calculate_commission($marketing_agency_sid, $product_sid, $commission = 'initial', $product_quantity = 1, $product_rooftops = 1, $applied = 'primary', $custom_price = 0, $custom_cost_price = 0) {
        $commission_amount = 0;
        $this->db->select('*');
        $this->db->where('sid', $product_sid);
        $record_obj = $this->db->get('products');
        $product_detail = $record_obj->result_array();
        $record_obj->free_result();
                
        if (!empty($product_detail)) {
            $product_detail = $product_detail[0];
            $cost_price = $product_detail['cost_price'];
            $sale_price = $product_detail['price'];

            if ($custom_price != 0 && $sale_price != $custom_price) {
                $sale_price = $custom_price;
                $cost_price = $custom_cost_price;
                $profit_amount = $sale_price - $cost_price;
            } else {
                $product_quantity = $product_quantity * $product_rooftops;
                $sale_price = $sale_price * $product_quantity;
                $cost_price = $cost_price * $product_quantity;
                $profit_amount = $sale_price - $cost_price;
            }

            if ($sale_price > $cost_price) {
                $this->db->select('*');
                $this->db->where('sid', $marketing_agency_sid);
                
                $record_obj = $this->db->get('marketing_agencies');
                $marketing_agency = $record_obj->result_array();
                $record_obj->free_result();

                if (!empty($marketing_agency)) {
                    $marketing_agency = $marketing_agency[0];

                    switch ($commission) {
                        case 'initial':
                            $commission_type = $applied == 'primary' ? $marketing_agency['initial_commission_type'] : $marketing_agency['secondary_initial_commission_type'];
                            $commission_value = $applied == 'primary' ? $marketing_agency['initial_commission_value'] : $marketing_agency['secondary_initial_commission_value'];
                            break;
                        case 'recurring':
                            $commission_type = $applied == 'primary' ? $marketing_agency['recurring_commission_type'] : $marketing_agency['secondary_recurring_commission_type'];
                            $commission_value = $applied == 'primary' ? $marketing_agency['recurring_commission_value'] : $marketing_agency['secondary_recurring_commission_value'];
                            break;
                    }

                    if ($commission_type == 'fixed') {
                        $commission_amount = $commission_value;
                    } else if ($commission_type == 'percentage') {
                        if ($commission_value > 0) {
                            $commission_amount = $profit_amount * ($commission_value / 100);
                        } else {
                            $commission_amount = 0;
                        }
                    }
                }
            }
        }

        return $commission_amount;
    }

    function Generate_commission_invoice_number($invoice_sid) {
        $padded_sid = str_pad($invoice_sid, 6, '0', STR_PAD_LEFT);
        return STORE_CODE . '-CM-' . $padded_sid;
    }

    function Calculate_and_update_commission_invoice_value($invoice_sid, $discount_value = 0, $commission_applied = 'primary') {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        $record_obj = $this->db->get('commission_invoice_items');
        $invoice_items = $record_obj->result_array();
        $record_obj->free_result();
        $total_commission = 0;
        $invoice_total = 0;
        
        if (!empty($invoice_items)) {
            foreach ($invoice_items as $item) {
                $item_value = floatval($item['quantity_total']);
                $item_commission_value = floatval($item['commission_amount']);
                $invoice_total += $item_value;
                $total_commission += $item_commission_value;
            }
        }

        $invoice_total = $invoice_total - $discount_value;
        $dataToSave = array();
        $dataToSave['value'] = $invoice_total;
        $dataToSave['commission_value'] = $total_commission;
        $dataToSave['total_after_discount'] = $invoice_total;
        $dataToSave['total_commission_after_discount'] = $total_commission;
        $dataToSave['commission_applied'] = $commission_applied;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('commission_invoices', $dataToSave);
    }

    function Update_commission_invoice_type($invoice_sid, $invoice_type) {
        $dataToSave = array();
        $dataToSave['invoice_type'] = $invoice_type;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('commission_invoices', $dataToSave);
    }

    function Update_commission_invoice_number($invoice_sid) {
        $invoice_number = $this->Generate_commission_invoice_number($invoice_sid);
        $dataToSave = array();
        $dataToSave['invoice_number'] = $invoice_number;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('commission_invoices', $dataToSave);
        return $invoice_number;
    }

    function Insert_commission_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $quantity, $number_of_rooftops, $item_details = array(), $profit_amount = 0, $commission_amount = 0, $rooftop_flag = true) {
        $dataToSave = array();
        $dataToSave['invoice_sid'] = $invoice_sid;
        $dataToSave['invoice_number'] = $invoice_number;
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['quantity'] = $quantity;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;
        //Commission Fields
        $dataToSave['profit_amount'] = $profit_amount;
        $dataToSave['commission_amount'] = $commission_amount;

        if (!empty($item_details)) {
            $unitPrice = $item_details['unit_price'];
            $rooftopsTotal = $rooftop_flag ? $number_of_rooftops * $unitPrice : $unitPrice;
            $quantityTotal = $rooftop_flag ? $quantity * $rooftopsTotal : $rooftopsTotal;
            $dataToSave['unit_price'] = $unitPrice;
            $dataToSave['rooftops_total'] = $rooftopsTotal;
            $dataToSave['quantity_total'] = $quantityTotal;
        }

        /**
         * item_sid,
         * item_name,
         * item_description,
         * unit_price,
         * number_of_employees,
         * includes_deluxe_theme,
         * includes_facebook_api,
         * number_of_days
         *
         * item_details array should contain all these fields.
         */
        $dataToSave = array_merge($dataToSave, $item_details);
        $this->db->insert('commission_invoice_items', $dataToSave);
    }

    function Insert_commission_invoice($created_by, $company_sid, $company_name, $company_email, $marketing_agency_sid, $invoice_origin = 'super_admin', $marketing_agency_parent_sid = 0) {
        $dataToSave = array();
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['company_name'] = $company_name;
        $dataToSave['company_email'] = $company_email;
        $dataToSave['marketing_agency_sid'] = $marketing_agency_sid;
        $dataToSave['invoice_origin'] = $invoice_origin;
        $dataToSave['secondary_commission_referrer_sid'] = $marketing_agency_parent_sid;
        $this->db->insert('commission_invoices', $dataToSave);
        return $this->db->insert_id();
    }

    function get_refer_by_sid($marketing_agency_sid) {
        $this->db->select('referred_by');
        $this->db->where('sid', $marketing_agency_sid);
        $record_obj = $this->db->get('marketing_agencies');
        $referred_by = $record_obj->result_array();
        $record_obj->free_result();
        return $referred_by[0]['referred_by'];
    }

    function Save_commission_invoice($created_by, $company_sid, $item_ids = array(), $item_id_to_number_of_rooftops = array(), $item_id_to_quantity = array(), $invoice_type = 'manual', $invoice_origin = 'super_admin', $custom_price = 0, $cost_price = 0) {
        $company_info = $this->Get_company_information($company_sid); //Get Company Info
        $commission_invoice_sid = array();

        if (!empty($company_info)) {
            $company_info = $company_info[0];
            $marketing_agency_sid = $company_info['marketing_agency_sid'];
            $secondary_marketing_agency_sid = $this->get_refer_by_sid($marketing_agency_sid);

            if ($marketing_agency_sid > 0) {
                $company_admin = $this->Get_company_admin_information($company_sid);
                $commission_invoices = $this->get_all_commission_invoices($company_sid, $marketing_agency_sid, 'primary');
                $company_name = $company_info['CompanyName'];
                $company_email = $company_info['email'];

                if (!empty($company_admin)) {
                    $company_admin = $company_admin[0];
                    $company_email = $company_admin['email'];
                }

                //Insert New Invoice Record to Get Invoice sid
                $invoice_sid = $this->Insert_commission_invoice($created_by, $company_sid, $company_name, $company_email, $marketing_agency_sid, $invoice_origin, 0);
                $commission_invoice_sid['primary'] = $invoice_sid;
                $invoice_number = $this->Update_commission_invoice_number($invoice_sid); //Update Invoice Number
                $this->Update_commission_invoice_type($invoice_sid, $invoice_type); //Update Invoice Type
                $products = $this->Get_products($item_ids); //Get Items

                /**
                 * item_sid,
                 * item_name,
                 * item_description,
                 * unit_price,
                 * number_of_employees,
                 * includes_deluxe_theme,
                 * includes_facebook_api,
                 * number_of_days
                 */
                foreach ($products as $product) {
                    $original_price = $product['price'];
                    $original_cost_price = $product['cost_price'];
                    $rooftop_flag = true;

                    if ($custom_price != 0 && $original_price != $custom_price) {
                        $original_price = $custom_price;
                        $original_cost_price = $cost_price;
                        $rooftop_flag = false;
                    }
                    
                    $item_detail = array();
                    $item_detail['item_sid'] = $product['sid'];
                    $item_detail['item_name'] = $product['name'];
                    $item_detail['item_description'] = $product['short_description'];
                    $item_detail['unit_price'] = $original_price;
                    $item_detail['cost_price'] = $original_cost_price;
                    $item_detail['number_of_employees'] = $product['maximum_number_of_employees'];
                    $item_detail['includes_deluxe_theme'] = $product['includes_deluxe_theme'];
                    $item_detail['includes_facebook_api'] = $product['includes_facebook_api'];
                    $item_detail['number_of_days'] = $product['expiry_days'];
                    $profit_amount = $original_price - $original_cost_price;
                    $commission_amount = 0;

                    if (!empty($commission_invoices)) {
                        $commission_amount = $this->calculate_commission($marketing_agency_sid, $product['sid'], 'recurring', $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], 'primary', $custom_price, $cost_price);
                    } else {
                        $commission_amount = $this->calculate_commission($marketing_agency_sid, $product['sid'], 'initial', $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], 'primary', $custom_price, $cost_price);
                    }

                    if (empty($item_id_to_quantity) && empty($item_id_to_number_of_rooftops)) {
                        $this->Insert_commission_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, 1, 1, $item_detail, $profit_amount, $commission_amount, $rooftop_flag);
                    } else {
                        $this->Insert_commission_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], $item_detail, $profit_amount, $commission_amount, $rooftop_flag);
                    }
                }

                $this->Calculate_and_update_commission_invoice_value($invoice_sid, 0, 'primary');
            }

            // Calculation for secondary commission
            if (!empty($secondary_marketing_agency_sid) && $secondary_marketing_agency_sid != NULL) {
                $company_admin = $this->Get_company_admin_information($company_sid);
                $commission_invoices = $this->get_all_commission_invoices($company_sid, $secondary_marketing_agency_sid, 'secondary');
                $company_name = $company_info['CompanyName'];
                $company_email = $company_info['email'];

                if (!empty($company_admin)) {
                    $company_admin = $company_admin[0];
                    $company_email = $company_admin['email'];
                }

                //Insert New Invoice Record to Get Invoice sid
                //Here $marketing_agency_sid is the agency through which secondary agency got commission
                $invoice_sid = $this->Insert_commission_invoice($created_by, $company_sid, $company_name, $company_email, $secondary_marketing_agency_sid, $invoice_origin, $marketing_agency_sid);
                $commission_invoice_sid['secondary'] = $invoice_sid;
                $invoice_number = $this->Update_commission_invoice_number($invoice_sid); //Update Invoice Number
                $this->Update_commission_invoice_type($invoice_sid, $invoice_type); //Update Invoice Type
                $products = $this->Get_products($item_ids); //Get Items
                        
                /**
                 * item_sid,
                 * item_name,
                 * item_description,
                 * unit_price,
                 * number_of_employees,
                 * includes_deluxe_theme,
                 * includes_facebook_api,
                 * number_of_days
                 */
                foreach ($products as $product) {
                    $original_price = $product['price'];
                    $original_cost_price = $product['cost_price'];
                    $rooftop_flag = true;

                    if ($custom_price != 0 && $original_price != $custom_price) {
                        $original_price = $custom_price;
                        $original_cost_price = $cost_price;
                        $rooftop_flag = false;
                    }
                    
                    $item_detail = array();
                    $item_detail['item_sid'] = $product['sid'];
                    $item_detail['item_name'] = $product['name'];
                    $item_detail['item_description'] = $product['short_description'];
                    $item_detail['unit_price'] = $original_price;
                    $item_detail['cost_price'] = $original_cost_price;
                    $item_detail['number_of_employees'] = $product['maximum_number_of_employees'];
                    $item_detail['includes_deluxe_theme'] = $product['includes_deluxe_theme'];
                    $item_detail['includes_facebook_api'] = $product['includes_facebook_api'];
                    $item_detail['number_of_days'] = $product['expiry_days'];
                    $profit_amount = $original_price - $original_cost_price;
                    $commission_amount = 0;

                    if (!empty($commission_invoices)) {
                        $commission_amount = $this->calculate_commission($secondary_marketing_agency_sid, $product['sid'], 'recurring', $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], 'secondary', $custom_price, $cost_price);
                    } else {
                        $commission_amount = $this->calculate_commission($secondary_marketing_agency_sid, $product['sid'], 'initial', $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], 'secondary', $custom_price, $cost_price);
                    }

                    if (empty($item_id_to_quantity) && empty($item_id_to_number_of_rooftops)) {
                        $this->Insert_commission_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, 1, 1, $item_detail, $profit_amount, $commission_amount, $rooftop_flag);
                    } else {
                        $this->Insert_commission_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], $item_detail, $profit_amount, $commission_amount, $rooftop_flag);
                    }
                }

                $this->Calculate_and_update_commission_invoice_value($invoice_sid, 0, 'secondary');
            }
        }

        return $commission_invoice_sid;
    }

    function get_all_commission_invoices($company_sid, $marketing_agency_sid, $commission_applied) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('commission_applied', $commission_applied);
        $this->db->where('marketing_agency_sid', $marketing_agency_sid);
        $record_obj = $this->db->get('commission_invoices');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function update_commission_invoice_sid($admin_invoice_sid, $commission_invoice_sid, $invoice_type = 'admin_invoice', $secondary_commission_invoice_sid = 0) {
        $this->db->where('sid', $admin_invoice_sid);
        $data_to_update = array();
        $data_to_update['commission_invoice_sid'] = $commission_invoice_sid;

        if ($secondary_commission_invoice_sid > 0) {
            $data_to_update['secondary_commission_invoice_sid'] = $secondary_commission_invoice_sid;
        }

        if ($invoice_type == 'admin_invoice') {
            $this->db->update('admin_invoices', $data_to_update);
        } else if ($invoice_type == 'market_place_invoice') {
            $this->db->update('invoices', $data_to_update);
        }
    }

    function update_invoice_sid_in_commission_invoice($commission_invoice_sid, $invoice_sid, $secondary_invoice_sid = 0) {
        $this->db->where('sid', $commission_invoice_sid);
        $data_to_update = array();
        $data_to_update['invoice_sid'] = $invoice_sid;
        $this->db->update('commission_invoices', $data_to_update);

        if ($secondary_invoice_sid > 0) {
            $this->db->where('sid', $secondary_invoice_sid);
            $this->db->update('commission_invoices', $data_to_update);
        }
    }

    function Update_commission_invoice_discount($invoice_sid, $discount_percentage, $discount_amount, $total_after_discount) {
        $dataToSave = array();
        $dataToSave['discount_percentage'] = $discount_percentage;
        $dataToSave['discount_amount'] = $discount_amount;
        $dataToSave['total_after_discount'] = $total_after_discount;
        $dataToSave['is_discounted'] = 1;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('commission_invoices', $dataToSave);
    }

    function apply_discount_on_commission($invoice_sid) {
        $this->db->select('*');
        $this->db->where('sid', $invoice_sid);
        $record_obj = $this->db->get('commission_invoices');
        $invoice_data = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($invoice_data)) {
            $invoice_data = $invoice_data[0];
            $total_after_discount = $invoice_data['total_after_discount'];

            if ($total_after_discount > 0) {
                $discount_percentage = $invoice_data['discount_percentage'];
                $commission_value = $invoice_data['commission_value'];
                $commission_value = $commission_value - ( $commission_value * $discount_percentage / 100);
                $this->db->where('sid', $invoice_sid);
                $data_to_update = array();
                $data_to_update['total_commission_after_discount'] = $commission_value;
                $this->db->update('commission_invoices', $data_to_update);
            }
        }
    }

    /*     * ***** Commission Invoices ****** */
    /*     * ***** Marketplace Invoices ****** */
    function Insert_marketplace_invoice($created_by, $company_sid, $company_name, $company_email) {
        $dataToSave = array();
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['company_name'] = $company_name;
        $dataToSave['company_email'] = $company_email;
        $this->db->insert('marketplace_invoices', $dataToSave);
        return $this->db->insert_id();
    }

    function Update_marketplace_invoice_number($invoice_sid) {
        $invoice_number = $this->Generate_marketplace_invoice_number($invoice_sid);
        $dataToSave = array();
        $dataToSave['invoice_number'] = $invoice_number;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('marketplace_invoices', $dataToSave);
        return $invoice_number;
    }

    function Generate_marketplace_invoice_number($invoice_sid) {
        $padded_sid = str_pad($invoice_sid, 6, '0', STR_PAD_LEFT);
        return STORE_CODE . '-MP-' . $padded_sid;
    }

    function Update_marketplace_invoice_type($invoice_sid, $invoice_type) {
        $dataToSave = array();
        $dataToSave['invoice_type'] = $invoice_type;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('marketplace_invoices', $dataToSave);
    }

    function Insert_marketplace_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $quantity, $number_of_rooftops, $item_details = array()) {
        $dataToSave = array();
        $dataToSave['invoice_sid'] = $invoice_sid;
        $dataToSave['invoice_number'] = $invoice_number;
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['quantity'] = $quantity;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;

        if (!empty($item_details)) {
            $unitPrice = $item_details['unit_price'];
            $rooftopsTotal = $number_of_rooftops * $unitPrice;
            $quantityTotal = $quantity * $rooftopsTotal;
            $dataToSave['unit_price'] = $unitPrice;
            $dataToSave['rooftops_total'] = $rooftopsTotal;
            $dataToSave['quantity_total'] = $quantityTotal;
        }

        /**
         * item_sid,
         * item_name,
         * item_description,
         * unit_price,
         * number_of_employees,
         * includes_deluxe_theme,
         * includes_facebook_api,
         * number_of_days
         *
         * item_details array should contain all these fields.
         */
        
        $dataToSave = array_merge($dataToSave, $item_details);
        $this->db->insert('marketplace_invoice_items', $dataToSave);
    }

    function Calculate_and_update_marketplace_invoice_value($invoice_sid, $discount_value = 0) {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        $record_obj = $this->db->get('marketplace_invoice_items');
        $invoice_items = $record_obj->result_array();
        $record_obj->free_result();
        $invoice_total = 0;

        if (!empty($invoice_items)) {
            foreach ($invoice_items as $item) {
                $item_value = floatval($item['quantity_total']);
                $invoice_total += $item_value;
            }
        }

        $invoice_total = $invoice_total - $discount_value;
        $dataToSave = array();
        $dataToSave['value'] = $invoice_total;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('marketplace_invoices', $dataToSave);
    }

    function Save_marketplace_invoice($created_by, $company_sid, $item_ids = array(), $item_id_to_number_of_rooftops = array(), $item_id_to_quantity = array(), $invoice_type = 'manual') {
        $company_info = $this->Get_company_information($company_sid); //Get Company Info
        $admin_invoice_sid = 0;

        if (!empty($company_info)) {
            $company_info = $company_info[0];
            $company_admin = $this->Get_company_admin_information($company_sid);
            $company_name = $company_info['CompanyName'];
            $company_email = $company_info['email'];

            if (!empty($company_admin)) {
                $company_admin = $company_admin[0];
                $company_email = $company_admin['email'];
            }

            //Insert New Invoice Record to Get Invoice sid
            $invoice_sid = $this->Insert_marketplace_invoice($created_by, $company_sid, $company_name, $company_email);
            $admin_invoice_sid = $invoice_sid;
            $invoice_number = $this->Update_marketplace_invoice_number($invoice_sid); //Update Invoice Number
            $this->Update_marketplace_invoice_type($invoice_sid, $invoice_type); //Update Invoice Type
            $products = $this->Get_products($item_ids); //Get Items

            /**
             * item_sid,
             * item_name,
             * item_description,
             * unit_price,
             * number_of_employees,
             * includes_deluxe_theme,
             * includes_facebook_api,
             * number_of_days
             */
            foreach ($products as $product) {
                $item_detail = array();
                $item_detail['item_sid'] = $product['sid'];
                $item_detail['item_name'] = $product['name'];
                $item_detail['item_description'] = $product['short_description'];
                $item_detail['unit_price'] = $product['price'];
                $item_detail['cost_price'] = $product['cost_price'];
                $item_detail['number_of_employees'] = $product['maximum_number_of_employees'];
                $item_detail['includes_deluxe_theme'] = $product['includes_deluxe_theme'];
                $item_detail['includes_facebook_api'] = $product['includes_facebook_api'];
                $item_detail['number_of_days'] = $product['expiry_days'];

                if (empty($item_id_to_quantity) && empty($item_id_to_number_of_rooftops)) {
                    $this->Insert_marketplace_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, 1, 1, $item_detail);
                } else {
                    $this->Insert_marketplace_invoice_item($invoice_sid, $invoice_number, $created_by, $company_sid, $item_id_to_quantity[$product['sid']], $item_id_to_number_of_rooftops[$product['sid']], $item_detail);
                }
            }

            $this->Calculate_and_update_marketplace_invoice_value($invoice_sid, 0);

            if (strtolower($invoice_type) == 'automatic') {
                return $invoice_sid;
            }
        }

        return $admin_invoice_sid;
    }

    /*     * ***** Marketplace Invoices ****** */

    function update_admin_invoice_payment_table($invoice_sid, $processed_by, $payment_status, $payment_method, $invoice_description, $check_number = NULL) {
        $dataToSave = array();
        $dataToSave['payment_processed_by'] = $processed_by;
        $dataToSave['payment_status'] = $payment_status;
        $dataToSave['payment_method'] = $payment_method;
        $dataToSave['payment_description'] = $invoice_description;
        $dataToSave['check_number'] = $check_number;
        $this->db->where('sid', $invoice_sid);
        $this->db->update('admin_invoices', $dataToSave);
    }

    function get_all_companies() {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function insert_invoice_credit_notes($data) {
        $this->db->insert('invoice_credit_notes', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    function get_invoice_notes($invoice_sid) {
        $this->db->select('invoice_type, credit_amount, notes, refund_date');
        $this->db->where('invoice_sid', $invoice_sid);
        $this->db->where('invoice_type', 'Admin');
        $record_obj = $this->db->get('invoice_credit_notes');
        $notes = $record_obj->result_array();
        $record_obj->free_result();
        return $notes;
    }

    function get_market_invoice_notes($invoice_id) {
        $this->db->select('invoice_type, credit_amount, notes, refund_date');
        $this->db->where('invoice_sid', $invoice_id);
        $this->db->where('invoice_type', 'Marketplace');
        
        $record_obj = $this->db->get('invoice_credit_notes');
        $notes = $record_obj->result_array();
        $record_obj->free_result();
        return $notes;
    }

    function get_payment_voucher_id($invoice_id) {
        $this->db->select('payment_voucher_sid');
        $this->db->where('sid', $invoice_id);
        $this->db->limit(1);
        $record_obj = $this->db->get('commission_invoices');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($result)) {
            return $result[0]['payment_voucher_sid'];
        } else {
            return null;
        }
    }

    function delete_payment_voucher($voucher_id) {
        $this->db->where('sid', $voucher_id);
        $this->db->delete('payment_voucher');
    }

    function delete_commission_invoice($invoice_id) {
        $this->db->where('sid', $invoice_id);
        $this->db->delete('commission_invoices');
    }

}
