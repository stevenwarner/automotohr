<?php

class Employees_termination_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getAllActiveCompanies(){
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }
    
    public function getTerminatedEmployees($company_sids = NULL, $between = '', $limit = null, $start = null){
        //
        $this->db->select('terminated_employees.termination_reason');
        $this->db->select('terminated_employees.termination_date');
        $this->db->select('users.sid');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('users.access_level');
        $this->db->select('users.timezone');
        $this->db->select('users.is_executive_admin');
        $this->db->select('users.pay_plan_flag');
        $this->db->select('users.job_title');
        $this->db->select('users.joined_at');
        $this->db->select('users.rehire_date');
        $this->db->select('users.department_sid');
        $this->db->select('users.parent_sid');
        //
        $this->db->join('users', 'users.sid = terminated_employees.employee_sid', 'left');
        $this->db->where('terminated_employees.employee_status', 1);
        //
        if($between != '' && $between != NULL){
            $this->db->where($between);
        }
        //
        if (!empty($company_sids)) {
            $this->db->where('users.parent_sid', $company_sids);
        } 
        //
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        //  
        $this->db->order_by("terminated_employees.sid", "desc");
        $terminatedEmployees = $this->db->get('terminated_employees')->result_array();
        //
        return $terminatedEmployees;
    }
}
