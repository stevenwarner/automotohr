<?php

class Recurring_payments_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_recurring_payment_records($status = 'all',$company_name = 'all'){
        $this->db->select('recurring_payments.*, recurring_payments.sid as record_sid');
        $this->db->select('users.CompanyName');

        if(strtolower($status) != 'all') {
            $this->db->where('status', $status);
        }
        if(!empty($company_name) && $company_name != 'all'){
            $this->db->like('users.CompanyName', $company_name);
        }

        $this->db->join('users', 'recurring_payments.company_sid = users.sid', 'left');
        $this->db->order_by('recurring_payments.sid', 'DESC');
        $result = $this->db->get('recurring_payments')->result_array();
        return $result;
    }

    function insert_recurring_payment_record($company_sid, $number_of_rooftops, $payment_day, $discount_amount, $created_by, $ip_address, $total_after_discount, $status = 'active', $items = array()){
        $dataToSave = array();
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;
        $dataToSave['payment_day'] = $payment_day;
        $dataToSave['discount_amount'] = $discount_amount;
        $dataToSave['cs_item_ids'] = implode(', ', $items);
        $dataToSave['created_by'] = $created_by;
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['status'] = $status;
        $dataToSave['ip_address'] = $ip_address;
        $dataToSave['total_after_discount'] = $total_after_discount;

        $this->db->insert('recurring_payments', $dataToSave);
    }

    function update_recurring_payment_record($sid, $company_sid, $number_of_rooftops, $payment_day, $discount_amount, $created_by, $ip_address, $total_after_discount, $status = 'active', $items = array()){
        $this->db->where('sid', $sid);

        $dataToSave = array();
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;
        $dataToSave['payment_day'] = $payment_day;
        $dataToSave['discount_amount'] = $discount_amount;
        $dataToSave['cs_item_ids'] = implode(', ', $items);
        $dataToSave['created_by'] = $created_by;
        $dataToSave['created'] = date('Y-m-d H:i:s');
        $dataToSave['status'] = $status;
        $dataToSave['ip_address'] = $ip_address;
        $dataToSave['total_after_discount'] = $total_after_discount;

        $this->db->update('recurring_payments', $dataToSave);
    }

    function save_recurring_payment_record($sid, $company_sid, $number_of_rooftops, $payment_day, $discount_amount, $created_by, $ip_address, $total_after_discount, $status = 'active', $items = array()){
        if($sid == null){
            $this->insert_recurring_payment_record($company_sid, $number_of_rooftops, $payment_day, $discount_amount, $created_by, $ip_address, $total_after_discount, $status, $items);

            $rp_sid = $this->db->insert_id();

            $this->insert_rp_modification_record($rp_sid, 'created', date('Y-m-d H:i:s'), $created_by, $number_of_rooftops, $payment_day, $items);
        }else{
            $this->update_recurring_payment_record($sid, $company_sid, $number_of_rooftops, $payment_day, $discount_amount, $created_by, $ip_address, $total_after_discount, $status, $items);

            $this->insert_rp_modification_record($sid, 'modified', date('Y-m-d H:i:s'), $created_by, $number_of_rooftops, $payment_day, $items);
        }
    }

    function insert_rp_modification_record($rp_sid, $operation, $operation_date, $performed_by, $number_of_rooftops, $payment_day, $items = array()){
        $dataToSave = array();

        $dataToSave['recurring_payment_sid'] = $rp_sid;
        $dataToSave['operation'] = $operation;
        $dataToSave['operation_date'] = $operation_date;
        $dataToSave['performed_by'] = $performed_by;
        $dataToSave['number_of_rooftops'] = $number_of_rooftops;
        $dataToSave['payment_day'] = $payment_day;
        $dataToSave['cs_item_ids'] = implode(',', $items);

        $this->db->insert('recurring_payments_modification_history', $dataToSave);
    }

    function get_all_companies_excluding_companies_in_rp(){
        $this->db->select('*');
        $this->db->where('status', 'active');
        $rps = $this->db->get('recurring_payments')->result_array();

        $companies = array();
        foreach($rps as $rp){
            if(!in_array($rp['company_sid'], $companies)) {
                $companies[] = $rp['company_sid'];
            }
        }

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('parent_sid', 0);
        $this->db->order_by('sid', 'DESC');

        $this->db->where('career_page_type', 'standard_career_site');

        if(!empty($companies)) {
            $this->db->where('sid NOT IN ( ' . implode(',', $companies) . ' )');
        }

        return $this->db->get()->result_array();
    }

    function get_company_detail($company_sid){
        $this->db->where('sid', $company_sid);
        return $this->db->get('users')->result_array();
    }

    function get_all_products($package_type = 'account-package'){
        $this->db->select('*');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->where('product_type', $package_type);
        return $this->db->get('products')->result_array();
    }

    function get_recurring_payment_record($sid){
        $this->db->where('sid', $sid);
        return $this->db->get('recurring_payments')->result_array();
    }

    function insert_recurring_payment_process_record($recurring_payment_sid, $admin_invoice_sid){
        $dataToSave = array();
        $dataToSave['recurring_payment_sid'] = $recurring_payment_sid;
        $dataToSave['admin_invoice_sid'] = $admin_invoice_sid;

        $this->db->insert('recurring_payments_process_history', $dataToSave);
    }

    function delete_recurring_payment_record($sid){
        $this->db->where('sid', $sid);
        $this->db->delete('recurring_payments');
    }
}
