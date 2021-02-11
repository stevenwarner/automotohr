<?php

class xml_export_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    function get_jobs_by_company_id($user_sid) {
        $this->db->where('user_sid', $user_sid);
        return $this->db->get('portal_job_listings');
    }
    function run_query($qry){
        return $this->db->query($qry);
    }
    
    function add_company_feed($company_sid, $employer_sid){
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $result = $this->db->get('xml_job_feeds')->result_array();
        
        if(sizeof($result) <= 0){
            $insert_array = array();
            $insert_array['company_sid'] = $company_sid;
            $insert_array['employer_sid'] = $employer_sid;
            $insert_array['company_key'] = random_key() . $company_sid . $employer_sid;
            
            $result = $this->db->insert("xml_job_feeds", $insert_array);
            return $result;
        } else {
            return;
        }
    }
    
    function check_company_feed($security_key){
        $this->db->select('company_sid');
        $this->db->where('company_key', $security_key);
        $result = $this->db->get('xml_job_feeds')->result_array();
        
        if((sizeof($result) > 0) && isset($result[0]['company_sid'])){
            return $result[0]['company_sid'];
        } else {
            return false;
        }
    }
    
    function get_company_feed_url($company_sid){
        $this->db->select('company_key');
        $this->db->where('company_sid', $company_sid);
        $result = $this->db->get('xml_job_feeds')->result_array();
        
        if((sizeof($result) > 0) && isset($result[0]['company_key'])){
            return base_url() . 'xml_export/xml_jobs_feed/' . $result[0]['company_key'];
        } else {
            return '';
        }
    }
}
?>