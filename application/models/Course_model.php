<?php
class Course_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_employees($company_sid) {
        $this->db->select('sid, first_name, last_name, email, access_level, access_level_plus, pay_plan_flag, is_executive_admin, job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->from('users');
        $this->db->order_by('concat(first_name,last_name)', 'ASC', false);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getActiveDepartments($company_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('is_deleted', 0);
        $this->db->from('departments_management');
        $this->db->order_by('name', 'asc');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getTeams( $companySid, $departments ){
        //
        if(!$departments || !count($departments)) return [];
        //
        $a = $this->db
        ->select('sid, name')
        ->where('company_sid', $companySid)
        ->where('status', 1)
        ->where('is_deleted', 0)
        ->where_in('department_sid', array_column($departments, 'sid'))
        ->order_by('sort_order', 'ASC')
        ->get('departments_team_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function insert_attached_document($data_to_insert) {
        $this->db->insert('learning_center_attachment', $data_to_insert);
        return $this->db->insert_id();
    }

}
