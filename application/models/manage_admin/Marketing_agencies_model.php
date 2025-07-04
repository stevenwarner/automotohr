<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Marketing_agencies_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_affiliate_record($marekting_sid = null){
        $this->db->select('form_affiliate_end_user_license_agreement.*');
        $this->db->limit(1);
        $this->db->where('form_affiliate_end_user_license_agreement.marketing_agency_sid', $marekting_sid);
        $document_data = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();
        return $document_data;
    }

    function get_all_companies($limit = NULL, $start = NULL, $search = array(), $status = 'all') {
        $this->db->select('*');
        $this->db->from('users');
        if ($limit != NULL && $start != NULL) {
            $this->db->limit($limit, $start);
        }
        if(strtolower($status) != 'all') {
            $this->db->where('active', $status);
        }

        $this->db->where('parent_sid', 0);
        $this->db->where($search);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by("sid", "desc");
        $result = $this->db->get()->result_array();
        return $result;
    }

    function get_single_marketing_agency($marketing_agency_sid) {
        $this->db->select('*');
        $this->db->where('sid', $marketing_agency_sid);
        $marketing_agency = $this->db->get('marketing_agencies');
        $marketing_agency = $marketing_agency->result_array();

        if (!empty($marketing_agency)) {
            return $marketing_agency[0];
        } else {
            return array();
        }
    }

    function get_marketing_agency_documents($marketing_agency_sid = 0, $status = 1) {
        if ($marketing_agency_sid > 0) {
            $this->db->select('*');
            $this->db->where('marketing_agency_sid', $marketing_agency_sid);
            $this->db->where('status', $status);
            $record_obj = $this->db->get('marketing_agency_documents');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_marketing_agencies() {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('is_deleted', 0);
        $this->db->where('parent_sid', 0);
        $this->db->order_by('sid', 'desc');
        $marketing_agencies = $this->db->get()->result_array();

        foreach($marketing_agencies as $key => $agency){
            $this->db->distinct();
            $this->db->select('commission_invoices.invoice_number,commission_invoices.created,marketing_agencies.full_name,commission_invoices.sid');
            $this->db->where('commission_invoices.payment_status', 'unpaid');
            $this->db->where('invoice_status !=', 'deleted');
            $this->db->where('invoice_status !=', 'archived');
            $this->db->where('invoice_status !=', 'cancelled');
            $this->db->where('marketing_agency_sid ', $agency['sid']);
            $this->db->join('marketing_agencies', 'commission_invoices.marketing_agency_sid = marketing_agencies.sid', 'left');

            $records_obj = $this->db->get('commission_invoices');
            $records_arr = $records_obj->num_rows();
            $marketing_agencies[$key]['pending_commissions'] = $records_arr;
        }
        
        return $marketing_agencies;
    }

    function get_all_marketing_agencies_except_own($marketing_agency_sid) {
        $this->db->select('*');
        $this->db->where('sid <>',$marketing_agency_sid);
        $this->db->where('is_deleted', 0);
        $this->db->from('marketing_agencies');
        $this->db->order_by('sid', 'desc');
        $marketing_agencies = $this->db->get();
        return $marketing_agencies->result_array();
    }

    function insert_marketing_document($data_to_insert) {
        $this->db->insert('marketing_agency_documents', $data_to_insert);
    }

    function insert_marketing_agency($data_to_insert) {
        $this->db->insert('marketing_agencies', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_marketing_agency($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('marketing_agencies', $data_to_update);
    }

    function delete_marketing_agency($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete('marketing_agencies');
    }

    function set_status_of_marketing_agency($sid, $status = 1) {
        $data_to_update = array();
        $data_to_update['status'] = $status;
        $this->db->where('sid', $sid);
        $this->db->update('marketing_agencies', $data_to_update);
    }

    function get_marketing_agency_companies($sid) {
        $this->db->where('marketing_agency_sid', $sid);
        $this->db->where('parent_sid', 0);
        return $this->db->get('users')->result_array();
    }

    function get_marketing_agency_commission_invoices($sid) {
        $marketing_agency_companies = $this->get_marketing_agency_companies($sid);

        if (!empty($marketing_agency_companies)) {
            foreach ($marketing_agency_companies as $key => $company) {
                $commission_invoices = $this->get_commission_invoices($company['sid']);
                $marketing_agency_companies[$key]['commission_invoices'] = $commission_invoices;
            }
        }

        return $marketing_agency_companies;
    }

    function get_commission_invoices($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'desc');
        return $this->db->get('commission_invoices')->result_array();
    }

    function get_commission_invoices_group($marketing_agency_sid) {
        $this->db->select('commission_invoices.*, users.CompanyName');
        $this->db->where('commission_invoices.marketing_agency_sid', $marketing_agency_sid);
        $this->db->join('users', 'commission_invoices.company_sid = users.sid');
        $this->db->order_by('commission_invoices.sid', 'desc');
        $commission_invoices = $this->db->get('commission_invoices')->result_array();
        $return_array = array();

        foreach ($commission_invoices as $key => $ci) {
            $company_sid = $ci['company_sid'];
            $CompanyName = $ci['CompanyName'];
            $return_array[$CompanyName][] = $commission_invoices[$key];
        }

        return $return_array;
    }

    function csv_commission_invoices($marketing_agency_sid, $company_names = NULL, $payment_status = NULL, $start = NULL, $end = NULL) {

        $this->db->select('commission_invoices.*, users.CompanyName');
        $this->db->where('commission_invoices.marketing_agency_sid', $marketing_agency_sid);

        if ($company_names != NULL) {
            $this->db->where_in('users.CompanyName', $company_names);
        }

        if ($payment_status == 'paid') {
            $this->db->where('commission_invoices.payment_status', $payment_status);
        } elseif ($payment_status == 'unpaid') {
            $this->db->where('commission_invoices.payment_status', $payment_status);
        }

        if ($start != NULL) {
            $this->db->where('commission_invoices.created >=', $start);
        }

        if ($end != NULL) {
            $this->db->where('commission_invoices.created <=', $end);
        }

        $this->db->join('users', 'commission_invoices.company_sid = users.sid');
        $this->db->order_by('commission_invoices.sid', 'desc');
        $commission_invoices = $this->db->get('commission_invoices')->result_array();
        $return_array = array();

        foreach ($commission_invoices as $key => $ci) {
            $company_sid = $ci['company_sid'];
            $CompanyName = $ci['CompanyName'];
            $return_array[$CompanyName][] = $commission_invoices[$key];
        }

        return $return_array;
    }

    public function get_commissions_invoices($marketing_agency_sid, $company_names = 'all', $method = 'all', $between = '') {
        $this->db->select('commission_invoices.*, users.CompanyName');
        $this->db->where('commission_invoices.marketing_agency_sid', $marketing_agency_sid);
        if(!empty($between)){
            $this->db->where($between);
        }
        if(!empty($company_names) && $company_names != 'all'){
            $this->db->where_in('users.CompanyName', $company_names);
        }
        if(!empty($method) && $method != 'all'){
            $this->db->where('commission_invoices.payment_status', $method);
        }
        

        $this->db->join('users', 'commission_invoices.company_sid = users.sid');
        $this->db->order_by('commission_invoices.sid', 'desc');
        $commission_invoices = $this->db->get('commission_invoices')->result_array();
        $return_array = array();

        foreach ($commission_invoices as $key => $ci) {
            $company_sid = $ci['company_sid'];
            $CompanyName = $ci['CompanyName'];
            $return_array[$CompanyName][] = $commission_invoices[$key];
        }

        return $return_array;
    }

    function get_commission_invoice($commission_invoice_sid) {
        $this->db->where('sid', $commission_invoice_sid);
        $this->db->limit(1);
        $invoice = $this->db->get('commission_invoices')->result_array();

        if (!empty($invoice)) {
            return $invoice[0];
        } else {
            return array();
        }
    }

    function mark_commission_invoice_as_commission_paid($commission_invoice_sid) {
        $data_to_update = array();
        $payment_date = date('Y-m-d H:i:s');
        $data_to_update['payment_status'] = 'paid';
        $data_to_update['payment_date'] = $payment_date;
        $this->db->where('sid', $commission_invoice_sid);
        $this->db->update('commission_invoices', $data_to_update);
        $this->db->select('sid');
        $this->db->where('commission_invoice_sid', $commission_invoice_sid);
        $record_obj = $this->db->get('payment_voucher');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) { // Payment voucher details not found in database, add it
            $commission_invoice_details = $this->get_commission_invoice($commission_invoice_sid);

            if (!empty($commission_invoice_details)) {
                $insert_voucher_array = array();
                $insert_voucher_array['created'] = date('Y-m-d H:i:s');
                $insert_voucher_array['commission_invoice_sid'] = $commission_invoice_details['sid'];
                $insert_voucher_array['commission_invoice_no'] = $commission_invoice_details['invoice_number'];
                $insert_voucher_array['company_sid'] = $commission_invoice_details['company_sid'];
                $insert_voucher_array['marketing_agency_sid'] = $commission_invoice_details['marketing_agency_sid'];
                $insert_voucher_array['payment_status'] = $commission_invoice_details['payment_status'];
                $insert_voucher_array['payment_date'] = $commission_invoice_details['payment_date'];
                $insert_voucher_array['company_name'] = $commission_invoice_details['company_name'];
                $insert_voucher_array['paid_amount'] = $commission_invoice_details['total_commission_after_discount'];
                $this->db->insert('payment_voucher', $insert_voucher_array);
                $voucher_sid = $this->db->insert_id();
                $padded_sid = str_pad($voucher_sid, 6, '0', STR_PAD_LEFT);
                $voucher_number = STORE_CODE . '-PV-' . $padded_sid;
                $marketing_agency_details = $this->get_single_marketing_agency($commission_invoice_details['marketing_agency_sid']);
                $marketing_agency_name = $marketing_agency_details['full_name'];
                $update_voucher_array = array();
                $update_voucher_array['voucher_number'] = $voucher_number;
                $update_voucher_array['marketing_agency_name'] = $marketing_agency_name;
                $this->db->where('sid', $voucher_sid);
                $this->db->update('payment_voucher', $update_voucher_array);
            }
        } else { // payment voucher already exists. Update payment date
            $payment_voucher_sid = $record_arr[0]['sid'];
            $this->db->where('sid', $payment_voucher_sid);
            $this->db->update('payment_voucher', $data_to_update);
        }
    }

    function payment_voucher_is_paid($sid, $commission_invoice_sid, $paid_status = 'paid') {
        $payment_date = date('Y-m-d H:i:s');
        
        if($paid_status == 'paid') {
            $data_to_update['payment_status'] = 'paid';
            $data_to_update['payment_date'] = $payment_date;
        } else {
            $data_to_update['payment_status'] = 'unpaid';
            $data_to_update['payment_date'] = NULL;
        }
        
        $this->db->where('sid', $sid);
        $this->db->update('payment_voucher', $data_to_update);
        // check if invoice status is paid in commission invoice table
        /*$this->db->select('payment_status');
        $this->db->where('sid', $commission_invoice_sid);
        $record_obj = $this->db->get('commission_invoices');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $commission_payment_status = $record_arr[0]['payment_status']; */

        //if ($commission_payment_status != 'paid') {
            $this->db->where('sid', $commission_invoice_sid);
            $this->db->update('commission_invoices', $data_to_update);
        //}
    }

    function get_payment_voucher($commission_sid) {
        $this->db->select('*');
        $this->db->where('commission_invoice_sid', $commission_sid);
        $record_obj = $this->db->get('payment_voucher');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) { // Payment voucher details not found in database, add it
            $commission_invoice_details = $this->get_commission_invoice($commission_sid);

            if (empty($commission_invoice_details)) { // invoice not found, generate error
                return array();
            } else { // process the invoice and generate payment voucher.
                $insert_voucher_array = array();
                $insert_voucher_array['created'] = date('Y-m-d H:i:s');
                $insert_voucher_array['commission_invoice_sid'] = $commission_invoice_details['sid'];
                $insert_voucher_array['commission_invoice_no'] = $commission_invoice_details['invoice_number'];
                $insert_voucher_array['company_sid'] = $commission_invoice_details['company_sid'];
                $insert_voucher_array['marketing_agency_sid'] = $commission_invoice_details['marketing_agency_sid'];
                $insert_voucher_array['payment_status'] = $commission_invoice_details['payment_status'];
                $insert_voucher_array['payment_date'] = $commission_invoice_details['payment_date'];
                $insert_voucher_array['company_name'] = $commission_invoice_details['company_name'];
                $insert_voucher_array['paid_amount'] = $commission_invoice_details['total_commission_after_discount'];
                $this->db->insert('payment_voucher', $insert_voucher_array);
                $voucher_sid = $this->db->insert_id();
                
                $this->db->where('sid', $commission_sid);
                $this->db->set('payment_voucher_sid', $voucher_sid);
                $this->db->update('commission_invoices');
                
                $padded_sid = str_pad($voucher_sid, 6, '0', STR_PAD_LEFT);
                $voucher_number = STORE_CODE . '-PV-' . $padded_sid;
                $marketing_agency_details = $this->get_single_marketing_agency($commission_invoice_details['marketing_agency_sid']);
                $marketing_agency_name = $marketing_agency_details['full_name'];
                $update_voucher_array = array();
                $update_voucher_array['voucher_number'] = $voucher_number;
                $update_voucher_array['marketing_agency_name'] = $marketing_agency_name;
                $this->db->where('sid', $voucher_sid);
                $this->db->update('payment_voucher', $update_voucher_array);

                /*
                  $insert_voucher_array['voucher_number'] = $commission_invoice_details[];
                  $insert_voucher_array['payment_method'] = $commission_invoice_details[''];
                  $insert_voucher_array['payment_reference'] = $commission_invoice_details[''];
                  $insert_voucher_array['payment_description'] = $commission_invoice_details[''];
                  $insert_voucher_array['sent_to_name'] = $commission_invoice_details[''];
                  $insert_voucher_array['sent_to_email'] = $commission_invoice_details[''];
                  $insert_voucher_array['sent_to_address'] = $commission_invoice_details[''];
                 *                  */
                $this->db->select('*');
                $this->db->where('sid', $voucher_sid);
                $record_obj = $this->db->get('payment_voucher');
                $record_arr = $record_obj->result_array();
                $record_obj->free_result();
                return $record_arr;
            }
        } else {
            return $record_arr;
        }
    }

    function payment_voucher_by_sid($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('payment_voucher');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function update_payment_voucher($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('payment_voucher', $data);
    }

    function record_payment_voucher_emails($to_name, $to_email, $payment_voucher) {
        $data_to_insert = array();
        $data_to_insert['payment_voucher_sid'] = $payment_voucher['sid'];
        $data_to_insert['to_name'] = $to_name;
        $data_to_insert['to_email'] = $to_email;
        $data_to_insert['commission_invoice_sid'] = $payment_voucher['commission_invoice_sid'];
        $data_to_insert['commission_invoice_no'] = $payment_voucher['commission_invoice_no'];
        $data_to_insert['company_sid'] = $payment_voucher['company_sid'];
        $data_to_insert['marketing_agency_sid'] = $payment_voucher['marketing_agency_sid'];
        $data_to_insert['company_name'] = $payment_voucher['company_name'];
        $data_to_insert['sent_date'] = date('Y-m-d H:i:s');
        $this->db->insert('payment_voucher_emails_log', $data_to_insert);
    }

    function get_email_log($commission_sid) {
        $this->db->select('*');
        $this->db->where('commission_invoice_sid', $commission_sid);
        $this->db->order_by('sid', 'desc');
        $record_obj = $this->db->get('payment_voucher_emails_log');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function update_commission_amount_in_commission_invoice($invoice_sid, $amount) {
        $this->db->where('sid', $invoice_sid);
        $this->db->set('commission_value', $amount);
        $this->db->set('total_commission_after_discount', $amount);
        $this->db->update('commission_invoices');
    }

    function recalculate_commission($commission_invoice_sid) {
        $invoice = $this->get_commission_invoice($commission_invoice_sid);

        if(!empty($invoice)) {
            $company_sid = $invoice['company_sid'];
            $marketing_agency_sid = $invoice['marketing_agency_sid'];
            $agency = $this->get_single_marketing_agency($marketing_agency_sid);
            $commission_applied = $invoice['commission_applied'];
            $initial_commission_type = $agency['initial_commission_type'];
            $initial_commission_value = $agency['initial_commission_value'];
            $recurring_commission_type = $agency['recurring_commission_type'];
            $recurring_commission_value = $agency['recurring_commission_value'];
            $secondary_initial_commission_type = $agency['secondary_initial_commission_type'];
            $secondary_initial_commission_value = $agency['secondary_initial_commission_value'];
            $secondary_recurring_commission_type = $agency['secondary_recurring_commission_type'];
            $secondary_recurring_commission_value = $agency['secondary_recurring_commission_value'];
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('marketing_agency_sid', $marketing_agency_sid);
            $this->db->where('commission_applied', $commission_applied);
            $this->db->where('sid <>', $commission_invoice_sid);
            $this->db->from('commission_invoices');
            $count = $this->db->count_all_results();
            $invoice_value = $invoice['value'];
            $is_discounted = $invoice['is_discounted'];
            $discount_amount = $invoice['discount_amount'];
            $discount_percentage = ceil(($discount_amount / $invoice_value) * 100);
            //echo $discount_percentage;

            if ($is_discounted == 1) {
                $invoice_value = $invoice['total_after_discount'];
            }

            switch($commission_applied){
                case 'primary':
                    if ($count <= 0) { //First Commission Invoice for Company
                        if ($initial_commission_type == 'fixed') {
                            $new_commission = $initial_commission_value;
                        } else {
                            $new_commission = ceil($invoice_value * ($initial_commission_value / 100));
                        }
                    } else { //Recurring Commission Invoice
                        if ($recurring_commission_type == 'fixed') {
                            $new_commission = $recurring_commission_value;
                        } else {
                            $new_commission = ceil($invoice_value * ($recurring_commission_value / 100));
                        }
                    }
                    break;
                case 'secondary':
                    if ($count <= 0) { //First Commission Invoice for Company
                        if ($secondary_initial_commission_type == 'fixed') {
                            $new_commission = $secondary_initial_commission_value;
                        } else {
                            $new_commission = ceil($invoice_value * ($secondary_initial_commission_value / 100));
                        }
                    } else { //Recurring Commission Invoice
                        if ($secondary_recurring_commission_type == 'fixed') {
                            $new_commission = $secondary_recurring_commission_value;
                        } else {
                            $new_commission = ceil($invoice_value * ($secondary_recurring_commission_value / 100));
                        }
                    }
                    break;
            }

            $this->db->where('sid', $commission_invoice_sid);
            $this->db->set('commission_value', $new_commission);
            $this->db->set('total_commission_after_discount', $new_commission);
            $this->db->update('commission_invoices');
            $this->db->where('commission_invoice_sid', $commission_invoice_sid);
            $this->db->set('paid_amount', $new_commission);
            $this->db->update('payment_voucher');
        }
    }

    function get_payment_voucher_details($voucher_sid) {
        $this->db->select('*');
        $this->db->where('sid', $voucher_sid);
        $voucher_obj = $this->db->get('payment_voucher');
        $voucher_arr = $voucher_obj->result_array();
        $voucher_obj->free_result();

        if (!empty($voucher_arr)) {
            $voucher_arr = $voucher_arr[0];
            $marketing_agency_sid = $voucher_arr['marketing_agency_sid'];
            $this->db->where('sid', $marketing_agency_sid);
            $agency_obj = $this->db->get('marketing_agencies');
            $agency_arr = $agency_obj->result_array();
            $agency_obj->free_result();

            if (!empty($agency_arr)) {
                $voucher_arr['agency'] = $agency_arr[0];
            } else {
                $voucher_arr['agency'] = array();
            }

            $company_sid = $voucher_arr['company_sid'];
            $this->db->where('sid', $company_sid);
            $company_obj = $this->db->get('users');
            $company_arr = $company_obj->result_array();
            $company_obj->free_result();

            if (!empty($company_arr)) {
                $voucher_arr['company'] = $company_arr[0];
            } else {
                $voucher_arr['company'] = array();
            }

            $actual_invoice_sid = 0;
            $actual_invoice_origin = '';
            $commission_invoice_sid = $voucher_arr['commission_invoice_sid'];
            $this->db->select('*');
            $this->db->where('sid', $commission_invoice_sid);
            $commission_inv_obj = $this->db->get('commission_invoices');
            $commission_inv_arr = $commission_inv_obj->result_array();
            $commission_inv_obj->free_result();

            if (!empty($commission_inv_arr)) {
                $voucher_arr['commission_invoice'] = $commission_inv_arr[0];
                $actual_invoice_sid = $commission_inv_arr[0]['invoice_sid'];
                $actual_invoice_origin = $commission_inv_arr[0]['invoice_origin'];
            } else {
                $voucher_arr['commission_invoice'] = array();
            }


            if (!empty($actual_invoice_sid) && !empty($actual_invoice_origin)) {
                if ($actual_invoice_origin == 'super_admin') {
                    $this->db->select('*');
                    $this->db->where('sid', $actual_invoice_sid);
                    $invoice_obj = $this->db->get('admin_invoices');
                    $invoice_arr = $invoice_obj->result_array();
                    $invoice_obj->free_result();
                } else {
                    $this->db->select('*');
                    $this->db->where('sid', $actual_invoice_sid);
                    $invoice_obj = $this->db->get('invoices');
                    $invoice_arr = $invoice_obj->result_array();
                    $invoice_obj->free_result();
                }

                if (!empty($invoice_arr)) {
                    $voucher_arr['invoice'] = $invoice_arr[0];
                    $voucher_arr['invoice_origin'] = $actual_invoice_origin;
                } else {
                    $voucher_arr['invoice'] = array();
                    $voucher_arr['invoice_origin'] = $actual_invoice_origin;
                }
            }

            return $voucher_arr;
        }
    }

    function fetch_voucher_data($company_sid){
        $this->db->select('sid,invoice_number,value,invoice_type');
        $this->db->where('company_sid' , $company_sid);
        $this->db->where('invoice_status' , 'active');
        $result = $this->db->get('admin_invoices')->result_array();
        return $result;
    }

    function Update_commission_invoice_number($invoice_sid){
        $invoice_number = $this->Generate_commission_invoice_number($invoice_sid);
        $dataToSave = array();
        $dataToSave['invoice_number'] = $invoice_number;

        $this->db->where('sid', $invoice_sid);
        $this->db->update('commission_invoices', $dataToSave);
        return $invoice_number;
    }

    function Generate_commission_invoice_number($invoice_sid){
        $padded_sid = str_pad($invoice_sid,6,'0',STR_PAD_LEFT);
        return  STORE_CODE . '-CM-' . $padded_sid;
    }

    function Insert_commission_invoice($created_by, $company_sid, $company_name, $company_email, $marketing_agency_sid, $invoice_origin = 'super_admin', $marketing_agency_parent_sid = 0,$invoice_type,$invoice_sid,$value,$date){
        $dataToSave = array();
        $dataToSave['created'] = $date;
        $dataToSave['created_by'] = $created_by;
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['company_name'] = $company_name;
        $dataToSave['company_email'] = $company_email;
        $dataToSave['marketing_agency_sid'] = $marketing_agency_sid;
        $dataToSave['invoice_origin'] = $invoice_origin;
        $dataToSave['invoice_type'] = $invoice_type;
        $dataToSave['invoice_sid'] = $invoice_sid;
        $dataToSave['value'] = $value;
        $dataToSave['commission_value'] = 0;
        $dataToSave['commission_applied'] = 'primary';
        $dataToSave['secondary_commission_referrer_sid'] = $marketing_agency_parent_sid;
        $this->db->insert('commission_invoices', $dataToSave);
        return $this->db->insert_id();
    }

    function delete_invoice_and_voucher($sid) {
        $this->db->where('sid', $sid);
        $this->db->delete('commission_invoices');
        $this->db->where('invoice_sid', $sid);
        $this->db->delete('commission_invoice_items');
        $this->db->where('commission_invoice_sid', $sid);
        $this->db->delete('payment_voucher');
        $this->db->where('commission_invoice_sid', $sid);
        $this->db->delete('payment_voucher_emails_log');
    }
    
    function delete_voucher_data($sid) {
        $this->db->where('commission_invoice_sid', $sid);
        $this->db->delete('payment_voucher');
        $this->db->where('commission_invoice_sid', $sid);
        $this->db->delete('payment_voucher_emails_log');
        $this->db->where('sid', $sid);
        $this->db->set('payment_voucher_sid', 0);
        $this->db->set('payment_status', 'unpaid');
        $this->db->update('commission_invoices');
    }

    function update_payment_voucher_no() {
        $this->db->select('sid, payment_voucher_sid');
        $invoice_obj = $this->db->get('commission_invoices');
        $invoices_no = $invoice_obj->result_array();
        $invoice_obj->free_result();
        
        if(!empty($invoices_no)) {
            foreach($invoices_no as $ino) {
                $sid = $ino['sid'];
                $payment_voucher_sid = $ino['payment_voucher_sid'];
                
                //if($payment_voucher_sid == 0) {
                    $this->db->select('sid');
                    $this->db->where('commission_invoice_sid', $sid);
                    $invoice_obj = $this->db->get('payment_voucher');
                    $payment_voucher = $invoice_obj->result_array();
                    $invoice_obj->free_result();
                    
                    if(!empty($payment_voucher)) {
                        $voucher_sid =  $payment_voucher[0]['sid'];
                        $this->db->where('sid', $sid);
                        $this->db->set('payment_voucher_sid', $voucher_sid);
                        $this->db->update('commission_invoices');
                        echo '<br>Found: '.$this->db->last_query();
                    } else {
                        $this->db->where('sid', $sid);
                        $this->db->set('payment_voucher_sid', 0);
                        $this->db->update('commission_invoices');
                        echo '<br>Not FOund: '.$this->db->last_query();
                    }
                //} 
            }
        }        
    }

    function check_username($username){
        $this->db->select('sid');
        $this->db->where('username',$username);
        $result = $this->db->get('marketing_agencies')->result_array();

        return $result;
    }

    function set_username($username,$id){
        $this->db->where('sid',$id);
        $data_to_update = array('username' => $username);
        $this->db->update('marketing_agencies', $data_to_update);
    }
    
     function affiliate_login($sid) {
        $this->db->select('*');
        $this->db->from('marketing_agencies');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $affiliate_query = $this->db->get();
        //echo $this->db->last_query();
        if ($affiliate_query->num_rows() == 1) {
            $affiliate_users = $affiliate_query->result_array();
            $status = $affiliate_users[0]['status']; // check the status whether the affiliate is active or inactive

            if ($status) {
                $data['status'] = 'active';
                $data['affiliate_users'] = $affiliate_users[0];
            } else {
                $data['status'] = 'inactive';
            }
        } else {
            $data['status'] = 'not_found';
        }
        return $data;
    }

    function get_documents_status($marketing_agency_sid) {
        $this->db->select('status as eula_status');
        $this->db->where('marketing_agency_sid', $marketing_agency_sid);
        $data = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }
    
    function get_marketing_agency_users($parent_sid) {
        $this->db->select('*');
        $this->db->where('parent_sid', $parent_sid);
        
        $record_obj = $this->db->get('marketing_agencies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        return $record_arr;
    }

    function get_affiliate_groups(){
        $this->db->where('status', 1);
        $record_obj = $this->db->get('affiliate_user_groups');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }
}