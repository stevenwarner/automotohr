<?php

class Bulk_email_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // FUNCTIONS related to bulk email
    
    function get_all_companies() {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        // $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }
    
    function get_company_employees($company_sid) {
        $this->db->select('sid, first_name, last_name, email');
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $this->db->from('users');
        // $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid > ', 0);
        // $this->db->where('is_executive_admin <', 1);
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        return $this->db->get()->result_array();
    }
    
    function get_employee_data($employee_id) {
        $this->db->select('sid, email, first_name, last_name, PhoneNumber as phone_number, active, terminated_status');
        $this->db->where('sid', $employee_id);
        $employee = $this->db->get('users')->result_array();
        
        if(!empty($employee)){
            return $employee[0];
        } else {
            return array();
        }
    }

    /**
     * Fetch Admin templates from db
     *
     * @return Array|Bool
     */
    function fetch_admin_templates(){
        $result = $this->db
        ->select('
            sid AS id, name as templateName, subject, text as body
        ')
        ->from('email_templates')
        ->where('status', 1)
        ->where('group', 'super_admin_templates')
        ->order_by('name', 'ASC')
        ->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    /**
     * Fetch Company details by employee id
     * Created on: 30-04-2019
     *
     * @return Array|Bool
     */
    function fetch_company_details_by_employee_id($employee_id){
        $result = $this->db
        ->select(' 
            company.CompanyName as company_name, 
            company.PhoneNumber as company_phone, 
            company.Location_Address as company_address,
            company.email  as company_email,
            company.WebSite  as career_site_url
        ')
        ->from('users')
        ->where('users.sid', $employee_id)
        ->join('users as company', 'company.sid = users.parent_sid', 'left')
        ->get();
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return $result_arr;
    }
}
