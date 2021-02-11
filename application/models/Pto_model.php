<?php

class Pto_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function fetch_all_company_employees($company_sid){
        $this->db->select('sid,first_name, last_name, email');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }
    
    function get_all_configuration_groups($company_sid, $sid = NULL) {
        $this->db->select('sid, name, description, status, sort_order');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('is_deleted', 0);
        
        if($sid !=NULL) {
            $this->db->where('sid', $sid);
        }
        $this->db->order_by('sort_order', 'asc');
        
        $record_obj = $this->db->get('pto_group_configruations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
        
    }

    function get_configuration_group($company_sid, $sid = NULL) {
        $this->db->select('sid, name, description, status, sort_order');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        
        if($sid !=NULL) {
            $this->db->where('sid', $sid);
        }
        
        $record_obj = $this->db->get('pto_group_configruations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
        
    }
            
    function add_group($data_to_insert) {
        $this->db->insert('pto_group_configruations', $data_to_insert);
    }

    function update_group($group_sid, $data_to_update) {
        $this->db->where('sid', $group_sid);
        $this->db->update('pto_group_configruations', $data_to_update);
    }

    function get_all_employees_to_group($group_sid) {
        $this->db->select('employee_sid');
        $this->db->where('group_sid', $group_sid);
        $record_obj = $this->db->get('pto_employee_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_group_name ($sid) {
        $this->db->select('name');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('pto_group_configruations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return array();
        }
    }

    function delete_employees_from_group($group_sid) {
        $this->db->where('group_sid', $group_sid);
        $this->db->delete('pto_employee_2_group');
    }

    function assign_employee_to_group ($data_to_insert) {
        $this->db->insert('pto_employee_2_group', $data_to_insert);
    }

    function get_all_admin_to_group($group_sid) {
        $this->db->select('admin_sid');
        $this->db->where('group_sid', $group_sid);
        $record_obj = $this->db->get('pto_admin_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function delete_admin_2_group($group_sid) {
        $this->db->where('group_sid', $group_sid);
        $this->db->delete('pto_admin_2_group');
    }

    function assign_admin_2_group ($data_to_insert) {
        $this->db->insert('pto_admin_2_group', $data_to_insert);
    }
}