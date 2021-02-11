<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Invoice_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function get_all_company_names($m_sid){
        $this->db->select('company_name');
        $this->db->where('payment_voucher.marketing_agency_sid', $m_sid);
        $this->db->order_by('payment_voucher.sid', 'DESC');
        $records_obj = $this->db->get('payment_voucher');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_all_invoices($m_sid, $company_name, $payment_type, $start_date_applied = NULL, $end_date_applied = NULL) {
        $this->db->select('payment_voucher.sid,voucher_number,payment_voucher.commission_invoice_no,payment_voucher.payment_date,payment_voucher.payment_status,payment_voucher.company_name,paid_amount,commission_invoices.commission_applied,commission_invoices.secondary_commission_referrer_sid,users.CompanyName');
        $this->db->where('payment_voucher.marketing_agency_sid', $m_sid);
        if(sizeof($company_name) > 0 && $company_name != 'all'){
            $this->db->where_in('commission_invoices.company_name', $company_name);
        }
        if(!empty($payment_type) && $payment_type != 'all'){
            $this->db->where('commission_invoices.payment_status', $payment_type);
        }
        if ($start_date_applied != NULL && $start_date_applied != 'all') {
            $this->db->where('commission_invoices.created >=', $start_date_applied);
        }
        if ($end_date_applied != NULL && $end_date_applied != 'all') {
            $this->db->where('commission_invoices.created <=', $end_date_applied);
        }
        $this->db->join('commission_invoices','commission_invoices.sid = payment_voucher.commission_invoice_sid','left');
        $this->db->join('users', 'payment_voucher.company_sid = users.sid');
        $this->db->order_by('payment_voucher.sid', 'DESC');
        $records_obj = $this->db->get('payment_voucher');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_array = array();
        foreach ($records_arr as $key => $voucher) {
            if ($voucher['commission_applied'] == 'secondary') {
                $secondary_agency = $this->get_secondary_agency($voucher['secondary_commission_referrer_sid']);
                $records_arr[$key]['secondary_agency'] = $secondary_agency['full_name'];
            }
        }

        foreach ($records_arr as $key => $ci) {
            $CompanyName = $ci['CompanyName'];
            $return_array[$CompanyName][] = $records_arr[$key];
        }

        return $return_array;
    }

    public function get_secondary_agency($m_sid) {
        $this->db->select('full_name');
        $this->db->where('sid', $m_sid);
        $records_obj = $this->db->get('marketing_agencies');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if(sizeof($records_arr)>0){
            $records_arr = $records_arr[0];
        } else{
            $records_arr = 'N/A';
        }
        return $records_arr;
    }

    function get_commission_invoices_group($marketing_agency_sid) {
        $this->db->select('commission_invoices.*, users.CompanyName');
        $this->db->where('commission_invoices.marketing_agency_sid', $marketing_agency_sid);
        $this->db->join('users', 'commission_invoices.company_sid = users.sid');
        $this->db->order_by('commission_invoices.sid', 'desc');
        $commission_invoices = $this->db->get('commission_invoices')->result_array();
        $return_array = array();

        foreach ($commission_invoices as $key => $ci) {
            $CompanyName = $ci['CompanyName'];
            $return_array[$CompanyName][] = $commission_invoices[$key];
        }

        return $return_array;
    }

    function get_voucher_detail($sid){
        $this->db->select('payment_voucher.sid,payment_voucher.voucher_number,payment_voucher.commission_invoice_no,payment_voucher.payment_date,payment_voucher.payment_status,payment_voucher.company_name,payment_voucher.paid_amount,payment_voucher.marketing_agency_name,payment_voucher.created,admin_invoices.invoice_number,admin_invoices.total_after_discount');
        $this->db->where('payment_voucher.sid', $sid);
        $this->db->join('commission_invoices','commission_invoices.sid = payment_voucher.commission_invoice_sid','left');
        $this->db->join('admin_invoices','admin_invoices.sid = commission_invoices.invoice_sid','left');
        $records_obj = $this->db->get('payment_voucher');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr[0];
    }

}