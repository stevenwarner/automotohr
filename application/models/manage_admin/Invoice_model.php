<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class invoice_model extends CI_Model
{

    public function get_invoices($search)
    {
        $this->db->select('invoices.*,invoices.sid as invoice_number');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.username');

        $this->db->join('users', 'users.sid = invoices.user_sid');
        $this->db->where($search);
        $this->db->order_by("invoices.sid", "desc");
        $data = $this->db->get('invoices');
        return $data->result_array();
    }

    public function get_email_invoices($search)
    {
        $this->db->select('invoices.*,invoices.sid as invoice_number');

        $this->db->where('user_sid', NULL);
        $this->db->order_by("sid", "DESC");
        $this->db->where($search);
        $data = $this->db->get('invoices');
        return $data->result_array();
    }

    public function get_email_invoices_date($name = 'all', $inv_num = 'all', $status = 'all', $method = 'all', $between)
    {
        $this->db->select('invoices.*,invoices.sid as invoice_number');

        $this->db->where('user_sid', NULL);
        $this->db->order_by("sid", "DESC");
        if (!empty($between)) {
            $this->db->where($between);
        }
        if (!empty($inv_num) && $inv_num != 'all') {
            $this->db->where('invoices.sid', $inv_num);
        }
        if (!empty($status) && $status != 'all') {
            $this->db->where('invoices.status', $status);
        }
        if (!empty($method) && $method != 'all') {
            $this->db->where('invoices.payment_method', $method);
        }
        $data = $this->db->get('invoices');
        return $data->result_array();
    }

    //    public function get_email_invoices_date($search, $between) {
    //        $this->db->select('invoices.*,invoices.sid as invoice_number');
    //
    //        $this->db->where('user_sid', NULL);
    //        $this->db->order_by("sid", "DESC");
    //        $this->db->where($search);
    //        $this->db->where($between);
    //        $data = $this->db->get('invoices');
    //        return $data->result_array();
    //    }

    public function get_invoices_date($name = 'all', $inv_num = 'all', $status = 'all', $method = 'all', $between = '', $company = 'all', $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('invoices.*,invoices.sid as invoice_number,users.first_name,users.last_name,users.username,users.parent_sid,user.CompanyName');
        $this->db->join('users', 'users.sid = invoices.user_sid');
        $this->db->join('users as user', 'user.sid = invoices.company_sid');
        // $this->db->join('users user', 'user.sid = users.parent_sid');

        if (!empty($between)) {
            $this->db->where($between);
        }


        if (!empty($inv_num) && $inv_num != 'all') {
            $this->db->where('invoices.sid', $inv_num);
        }
        if (!empty($name) && $name != 'all') {
            $this->db->like('users.username', $name);
        }
        if (!empty($status) && $status != 'all') {
            $this->db->where('invoices.status', $status);
        }
        if (!empty($method) && $method != 'all') {
            $this->db->where('invoices.payment_method', $method);
        }

        if (!empty($company) && $company != 'all') {
            $this->db->where('invoices.company_sid', $company);
        }

        $this->db->order_by("invoices.sid", "DESC");

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == false) {
            $data = $this->db->get('invoices');
            return $data->result_array();
        } else {
            return $this->db->count_all_results('invoices');
        }
    }

    //    public function get_invoices_date($search, $between) {
    //        $this->db->select('invoices.*,invoices.sid as invoice_number');
    //        $this->db->select('users.first_name');
    //        $this->db->select('users.last_name');
    //        $this->db->select('users.username');
    //
    //        $this->db->join('users', 'users.sid = invoices.user_sid');
    //        $this->db->where($search);
    //        $this->db->where($between);
    //        $this->db->order_by("invoices.sid", "DESC");
    //        $data = $this->db->get('invoices');
    //        return $data->result_array();
    //    }

    public function delete_product($del_id)
    {
        $this->db->where('sid', $del_id);
        $this->db->delete('invoices');
    }

    public function mark_paid($invoice_id)
    {
        $data = array('status' => 'Paid');
        $this->db->where('sid', $invoice_id);
        $this->db->update('invoices', $data);
    }

    public function mark_unpaid($invoice_id)
    {
        $data = array('status' => 'Unpaid');
        $this->db->where('sid', $invoice_id);
        $this->db->update('invoices', $data);
    }

    public function save_invoice($invoice_data)
    {
        //        echo "<pre>";
        //        print_r($invoice_data);
        //        exit;       
        $this->db->insert('invoices', $invoice_data);
        ($this->db->affected_rows() != 1) ? $this->session->set_flashdata('message', 'Invoice failed to save, Please try Again!') : $this->session->set_flashdata('message', 'Invoice is added successfully');
        return $this->db->insert_id();
    }

    public function update_invoice($invoice_id, $invoice_data)
    {
        //        echo "<pre>invoice_id: ".$invoice_id;
        //        print_r($invoice_data);
        //        exit;
        $this->db->where('sid', $invoice_id);
        $this->db->update('invoices', $invoice_data);
        $this->session->set_flashdata('message', 'Invoice updated successfully');
    }

    public function get_employer_email($emp_id)
    {
        $this->db->select('username,email,first_name,last_name');
        $this->db->where('sid', $emp_id);
        $data = $this->db->get('users')->result_array();
        return $data[0];
    }

    public function get_invoice_detail($invoice_id, $company_sid = NULL)
    {
        $this->db->where('sid', $invoice_id);
        if ($company_sid != NULL) {
            $this->db->where('company_sid', $company_sid);
        }
        $result = $this->db->get('invoices')->result_array();
        if (!empty($result)) {
            return $result[0];
        } else {
            return $result;
        }
    }

    public function get_all_invoices($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $invoices = $this->db->get('invoices')->result_array();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $items_ids = $invoice['product_sid'];

                $this->db->select('name');
                $this->db->where('sid IN ( ' . $items_ids . ' ) ');
                $items_names = $this->db->get('products')->result_array();

                if (!empty($items_names)) {
                    $invoices[$key]['item_names'] = $items_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }

        return $invoices;
    }


    public function get_companies_with_unpaid_admin_invoices()
    {
        $this->db->distinct();
        $this->db->select('admin_invoices.company_sid');
        $this->db->where('admin_invoices.payment_status', 'unpaid');
        $this->db->where('admin_invoices.invoice_status !=', 'deleted');
        $this->db->where('admin_invoices.invoice_status !=', 'archived');
        $this->db->where('admin_invoices.invoice_status !=', 'cancelled');
        $this->db->where('users.is_paid', 1);
        $this->db->select('users.CompanyName');
        $this->db->join('users', 'admin_invoices.company_sid = users.sid', 'left');

        $records_obj = $this->db->get('admin_invoices');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $record) {
                $this->db->select('sid');
                $this->db->where('payment_status', 'unpaid');
                $this->db->where('invoice_status !=', 'deleted');
                $this->db->where('invoice_status !=', 'archived');
                $this->db->where('invoice_status !=', 'cancelled');
                $this->db->where('company_sid', $record['company_sid']);
                $this->db->from('admin_invoices');
                $records_arr[$key]['invoice_count'] = $this->db->count_all_results();
            }
        }

        return $records_arr;
    }

    public function get_all_unpaid_commissions()
    {
        $this->db->distinct();
        $this->db->select('commission_invoices.invoice_number, commission_invoices.created, marketing_agencies.full_name, commission_invoices.sid, commission_invoices.is_read, commission_invoices.marketing_agency_sid, commission_invoices.company_name, commission_invoices.discount_amount, commission_invoices.total_commission_after_discount, commission_invoices.commission_value');
        $this->db->where('commission_invoices.payment_status', 'unpaid');
        $this->db->where('invoice_status !=', 'deleted');
        $this->db->where('invoice_status !=', 'archived');
        $this->db->where('invoice_status !=', 'cancelled');
        $this->db->order_by('commission_invoices.created', 'DESC');
        $this->db->join('marketing_agencies', 'commission_invoices.marketing_agency_sid = marketing_agencies.sid', 'left');

        $records_obj = $this->db->get('commission_invoices');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_unpaid_invoices($company_sid, $exclusion_status = null)
    {
        $this->db->select('*');
        $this->db->where('payment_status', 'unpaid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('invoice_status !=', 'deleted');
        $this->db->where('invoice_status !=', 'archived');
        $this->db->where('invoice_status !=', 'cancelled');

        if ($exclusion_status !== null) {
            $this->db->where('exclusion_status', $exclusion_status);
        }

        $this->db->order_by('created', 'DESC');
        $this->db->from('admin_invoices');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function get_unpaid_commissions($commission_sid, $exclusion_status = null)
    {
        $this->db->select('commission_invoices.*,marketing_agencies.full_name');
        $this->db->where('payment_status', 'unpaid');
        $this->db->where('commission_invoices.sid', $commission_sid);
        $this->db->where('invoice_status !=', 'deleted');
        $this->db->where('invoice_status !=', 'archived');
        $this->db->where('invoice_status !=', 'cancelled');

        //        if($exclusion_status !== null) {
        //            $this->db->where('exclusion_status', $exclusion_status);
        //        }
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = commission_invoices.marketing_agency_sid', 'left');

        $this->db->order_by('created', 'DESC');
        $this->db->from('commission_invoices');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function set_exclusion_status($company_sid, $invoice_sid, $status)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $invoice_sid);

        $data_to_update = array();
        $data_to_update['exclusion_status'] = $status;

        $this->db->update('admin_invoices', $data_to_update);
    }

    public function set_invoice_status($company_sid, $invoice_sid, $status)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $invoice_sid);

        $data_to_update = array();
        $data_to_update['invoice_status'] = $status;

        $this->db->update('admin_invoices', $data_to_update);
    }

    public function get_invoice_credit_notes($invoice_sid, $invoice_type = 'Marketplace', $count_only = false)
    {
        $this->db->select('*');
        $this->db->where('invoice_sid', $invoice_sid);
        $this->db->where('invoice_type', $invoice_type);
        $this->db->from('invoice_credit_notes');

        if ($count_only == true) {
            $count = $this->db->count_all_results();

            return $count;
        } else {
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            return $records_arr;
        }
    }

    public function get_company_employerd($company_sid)
    {
        $this->db->select('sid, username, email, parent_sid');
        $this->db->from('users');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        return $this->db->get()->result_array();
    }

    function Get_company_information($company_sid)
    {
        $this->db->select('*');
        $this->db->limit(1);
        $this->db->where('sid', $company_sid);
        return $this->db->get('users')->result_array();
    }

    function update_view_pending_commission_status($sid)
    {
        $data = array('is_read' => 1);
        $this->db->where('sid', $sid);
        $this->db->update('commission_invoices', $data);
    }

    public function check_marketing_agency($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('marketing_agencies')->result_array();
        if (!empty($result)) {
            return true;
        } else {
            return false;
        }
    }

    //
    function getAllCompanies($active = 1)
    {
        $result = $this->db
            ->select('sid, CompanyName')
            ->where('parent_sid', 0)
            ->where('active', $active)
            ->where('is_paid', 1)
            ->where('career_page_type', 'standard_career_site')
            ->order_by('CompanyName', 'ASC')
            ->get('users');
        //
        $companies = $result->result_array();
        $result = $result->free_result();
        //
        return $companies;
    }
}
