<?php

class widget_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_company_detail($employer_id) {
        $this->db->where('sid', $employer_id);
        $result = $this->db->get('users')->result_array();
        return $result[0];
    }

    function get_company_id_by_api_key($api_key) {
        $this->db->select('sid');
        $this->db->where('api_key', $api_key);
        return $this->db->get('users');
    }

    function get_jobs_by_company_id($attributes, $user_sid) {
        $qry = "SELECT $attributes  FROM `portal_job_listings` where user_sid = $user_sid";
        return $this->db->query($qry);
    }

}
