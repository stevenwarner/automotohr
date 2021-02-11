<?php

class Cron_google_hire_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    //
    function get_all_company_jobs_indeed() {
        $product_sid = array(1, 21);
        $this->db->where_in('product_sid', $product_sid);
        $this->db->where('active', 1);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed. job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_indeed_organic($featuredArray) {
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->where_not_in('sid', $featuredArray);
        $this->db->order_by('sid', 'desc');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_active_companies() {
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            $data = array();
            foreach ($result as $r) {
                $data[] = $r['sid'];
            }
            return $data;
        } else {
            return 0;
        }
    }

    function get_portal_detail($company_id) {
        $this->db->where('user_sid', $company_id);
        $result = $this->db->get('portal_employer')->result_array();
        
        if(!empty($result)){
            return $result[0];
        } else {
            return array();
        }
    }

    function get_company_name_and_job_approval($sid) {
        $this->db->select('users.CompanyName, users.has_job_approval_rights, portal_employer.sub_domain');
        $this->db->where('users.sid', $sid);
        $this->db->join('portal_employer', 'portal_employer.user_sid = users.sid', 'left');
        $result = $this->db->get('users')->result_array();
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function fetch_uid_from_job_sid($job_sid){
        $this->db->select('uid, publish_date');
        $this->db->where('job_sid', $job_sid);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->limit(1);
        
        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return '';
        }
    }

    function get_job_category_name_by_id($sid) {
        $this->db->select('value');
        $this->db->where('sid', $sid);
        $this->db->where('field_sid', '198');
        $this->db->from('listing_field_list');
        return $this->db->get()->result_array();
    }


    /**
     * Get last IDs sent to Goole Job API
     * Created on: 10-24-2019
     *
     * @param Int $type
     * '0' AutomotoHR
     * '1' AutomotoSocial
     * @return Array
     **/
    function getLastProcessedIds($type = 0){
        $result = $this->db
        ->select('job_sid')
        ->from('cron_google_hire_jobs')
        ->where('site', 0)
        ->where('status', 1)
        ->order_by('created_at', 'DESC')
        ->get();
        //
        $ids = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($ids)) return array();
        $returnArray = array();
        foreach ($ids as $k0 => $v0) $returnArray[] = $v0['job_sid'];
        //
        return $returnArray;
    }


    /**
     * Insert last ID sent to Goole Job API
     * Created on: 10-24-2019
     *
     * @param String $id
     * @return Integer
     **/
    function addProccessedId($id){
        $this->db->insert('cron_google_hire_jobs', array(
            'job_sid' => $id,
            'site' => 0,
            'status' => 1
        ));
        return $this->db->insert_id(); 
    }
}