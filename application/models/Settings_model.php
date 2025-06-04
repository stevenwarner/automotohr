<?php
class Settings_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function Get_all_admin_invoices($page_number, $invoices_per_page, $company_sid = null, $invoice_status = 'active')
    {
        $offset = 0;

        if ($page_number > 1) {
            $offset = ($page_number - 1) * $invoices_per_page;
        }

        $this->db->select('*');
        $this->db->order_by('sid', 'DESC');
        $this->db->limit($invoices_per_page, $offset);

        if ($company_sid != null) {
            $this->db->where('company_sid', $company_sid);
        }

        $this->db->where('invoice_status', $invoice_status);

        $records_obj = $this->db->get('admin_invoices');
        $invoices = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $this->db->select('item_name');
                $this->db->where('invoice_sid', $invoice['sid']);

                $records_obj = $this->db->get('admin_invoice_items');
                $invoice_item_names = $records_obj->result_array();
                $records_obj->free_result();

                if (!empty($invoice_item_names)) {
                    $invoices[$key]['item_names'] = $invoice_item_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }

        return $invoices;
    }

    function Get_admin_invoice($invoice_sid, $get_items = false)
    {
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

    public function insert_company_timezone_modification_history($company_sid, $employer_sid, $timezone)
    {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['modified_by'] = $employer_sid;
        date_default_timezone_set('Canada/Saskatchewan');
        $data_to_insert['modified_date'] = date('Y-m-d H:i:s');
        $data_to_insert['timezone'] = $timezone;
        $this->db->insert('company_timezone_modification_history', $data_to_insert);
    }

    public function get_unpaid_admin_invoices($company_sid)
    {
        $this->db->select('*');
        $this->db->where('payment_status', 'unpaid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('invoice_status', 'active');
        $this->db->where('exclusion_status', 0);
        $this->db->order_by('created', 'DESC');
        $this->db->from('admin_invoices');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_admin_invoices($sids = array())
    {
        $this->db->select('*');
        $this->db->where_in('sid', $sids);
        $this->db->from('admin_invoices');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_job_listing_template_group_template_ids($template_group_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $template_group_sid);
        $record_obj = $this->db->get('portal_job_listing_template_groups');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $template_sids = $record_arr[0]['templates'];
            $template_sids = unserialize($template_sids);

            if ($template_sids == null) {
                $template_sids = array();
            }

            return $template_sids;
        } else {
            return array();
        }
    }

    public function get_job_listing_templates($template_sids)
    {
        $this->db->select('title, sid');

        if (!empty($template_sids) && is_array($template_sids)) {
            $this->db->where_in('sid', $template_sids);
        }

        $record_obj = $this->db->get('portal_job_listing_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function get_job_listing_template($template_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $template_sid);
        $record_obj = $this->db->get('portal_job_listing_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function job_fair_configuration($company_sid)
    {
        $this->db->select('status');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) {
            return 0;
        } else {
            return $record_arr[0]['status'];
        }
    }

    function get_job_fair_data($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function fetch_active_job_fair_title($company_sid)
    {
        $this->db->select('title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);

        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) {
            $this->db->select('title');
            $this->db->where('company_sid', $company_sid);
            $record_obj = $this->db->get('job_fairs_recruitment');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
        } else {
            if (($record_arr[0]['title'] == NULL || $record_arr[0]['title'] == '')) {
                $this->db->select('title');
                $this->db->where('company_sid', $company_sid);
                $record_obj = $this->db->get('job_fairs_recruitment');
                $record_arr = $record_obj->result_array();
                $record_obj->free_result();
            }
        }

        return $record_arr;
    }

    function check_reassign_candidate($sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $sid);
        $record_obj = $this->db->get('reassign_candidate_companies');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (sizeof($result) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function fetch_details($id)
    {
        $this->db->select('mykey, myvalue, mydomain, mytype, mysec');
        $this->db->where('myid', $id);
        $record_obj = $this->db->get('portal_themes_data');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($result)) {
            $data = $result[0];
            $sec = $data['mysec'];
            $type = $data['mytype'];
            $key = $data['mykey'];
            $value = $data['myvalue'];

            $enc = openssl_decrypt($type, "AES-128-ECB", $sec);
            $key = openssl_decrypt($key, $enc, $sec);
            $value = openssl_decrypt($value, $enc, $sec);

            return array('auth_user' => $key, 'auth_pass' => $value);
        } else {
            return array();
        }
    }


    //
    public function insert_cookie_log($data)
    {
        $this->db->insert('cookie_log_data', $data);
    }
}
