<?php

class Exclude_companies_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_ec_record($data){
        $this->db->insert('companies_excluded_from_reporting', $data);
    }

    function update_ec_record($sid, $data){
        $this->db->where('sid', $sid);
        $this->db->update('companies_excluded_from_reporting', $data);
    }

    function delete_ec_record($sid){
        $this->db->where('sid', $sid);
        $this->db->delete('companies_excluded_from_reporting');
    }

    function delete_all_ec_records(){
        $this->db->delete('companies_excluded_from_reporting');
    }

    function batch_insert_ec_records($data){
        $this->db->insert_batch('companies_excluded_from_reporting', $data);
    }

    function get_all_companies(){
        $this->db->select('sid,CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        return $this->db->get('users')->result_array();
    }
    
    function get_all_ahr_companies($limit = NULL, $start = NULL, $search = array())
    {
        $this->db->select('*');
        $this->db->from('users');
        if ($limit != NULL && $start != NULL) {
            $this->db->limit($limit, $start);
        }
        $this->db->where('parent_sid', 0);
        $this->db->where($search);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by("sid", "desc");
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    function get_excluded_companies(){
        $this->db->select('*');
        return $this->db->get('companies_excluded_from_reporting')->result_array();
    }
    
    function exclude_company($data, $company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $company_info = $this->db->get('companies_excluded_from_reporting')->result_array();
       
        if (!empty($company_info)) {
            $sid = $company_info[0]['sid'];
            $this->db->where('sid', $sid);
            $this->db->update('companies_excluded_from_reporting', $data);
        } else {
            $this->db->insert('companies_excluded_from_reporting', $data);
        }
    }
    
    function remove_excluded_company($sid) {
        $result = $this->db->delete('companies_excluded_from_reporting', array('sid' => $sid));
        return $result;
    }
}
