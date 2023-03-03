<?php
define('PJL', 'portal_job_listings');
define('PJA', 'portal_job_applications');
define('PAJL', 'portal_applicant_jobs_list');

class Invoice_new_model extends CI_Model
{
    //
    //
    function __construct()
    {
        //
        parent::__construct();
    }


    function Get_admin_invoice($invoice_sid, $get_items = false) {
        $this->db->select('*');
        $this->db->where('sid', $invoice_sid);
        
        $records_obj = $this->db->get('admin_invoices');
        $invoice = $records_obj->result_array();
        $records_obj->free_result();
        $invoice_items = array();

        if (!empty($invoice)) {
            $invoice = $invoice[0];

            if ($get_items == true) {
                $this->db->select('*');
                $this->db->where('invoice_sid', $invoice_sid);
                $records_obj = $this->db->get('admin_invoice_items');
                $invoice_items = $records_obj->result_array();
                $records_obj->free_result();
            }

            $invoice['items'] = $invoice_items;
        }
        return $invoice;
    }



    function get_all_company_cards($company_sid, $active_status = null) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($active_status !== null) {
            $this->db->where('active', $active_status);
        }

        $this->db->from('emp_cards');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    
}
