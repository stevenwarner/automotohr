<?php

class Complynet_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function invoice_item_usage($company_sid = null, $start_date = null, $end_date = null,  $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('invoice_items_track.*');

        $this->db->select('users_a.first_name as bought_by_first_name');
        $this->db->select('users_a.last_name as bought_by_last_name');

        $this->db->select('users_b.CompanyName as company_name');

        $this->db->select('users_c.first_name as used_by_first_name');
        $this->db->select('users_c.last_name as used_by_last_name');

        $this->db->select('jobs.Title as job_title');

        $this->db->where('invoice_items_track.used_by_employer_sid >', 0);

        if (!empty($company_sid) && !is_null($company_sid) && $company_sid != 'all') {
            $this->db->where('invoice_items_track.company_sid', $company_sid);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('invoice_items_track.date_purchased BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('invoice_items_track.date_purchased >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('invoice_items_track.date_purchased <=', $end_date);
        }

        $this->db->join('users as users_a', 'invoice_items_track.employer_sid = users_a.sid', 'left');
        $this->db->join('users as users_b', 'invoice_items_track.company_sid = users_b.sid', 'left');
        $this->db->join('users as users_c', 'invoice_items_track.used_by_employer_sid = users_c.sid', 'left');
        $this->db->join('portal_job_listings as jobs', 'invoice_items_track.used_against_job_sid = jobs.sid', 'left');

        $this->db->from('invoice_items_track');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            return $records_arr;
        }
    }


    public function get_complynet_companies($company_sid = null,  $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('sid,CompanyName');
        $this->db->from('users');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('complynet_status', 1);
        if (!empty($company_sid) && !is_null($company_sid) && $company_sid != 'all') {
            $this->db->where('sid', $company_sid);
        }

        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {//echo $offset.'s';
                $this->db->limit($limit, $offset);
            }

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            return $records_arr;
        }
    }

    //
    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('complynet_status', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }


//

function get_active_employees_detail($parent_sid) {
    $this->db->select('*');
    $this->db->where('parent_sid', $parent_sid);
    $this->db->where('active', '1');
    $this->db->where('terminated_status', 0);
  //  $this->db->where('archived', $archive);
    $this->db->where('is_executive_admin', 0);
    $this->db->order_by('sid', 'DESC');
    $all_employees = $this->db->get('users')->result_array();
 //   $all_employees = $this->verify_executive_admin_status($all_employees);
    return $all_employees;
}




}
