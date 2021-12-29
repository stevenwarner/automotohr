<?php

class Setup_default_config_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_companies() {

        $this->db->select('sid, CompanyName');
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('ems_status', 1);
        $this->db->where('parent_sid', 0);
        $this->db->order_by('CompanyName', 'ASC');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $result = array();
        if (!empty($records_arr)) {
            $result = $records_arr;
        }
        return $result;
    }

    function get_company_all_groups($company_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('documents_group_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_company_all_categories($company_sid) {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $records_obj = $this->db->get('documents_category_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_group_record($data_to_insert) {
        $this->db->insert('documents_group_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function insert_category_record($data_to_insert) {
        $this->db->insert('documents_category_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function check_if_default_document_already_added($company_sid){
        $this->db->select('default_group_categories_assigned');
        $this->db->where('sid',$company_sid);
        $reuslt = $this->db->get('users')->result_array();
        return $reuslt[0]['default_group_categories_assigned'];
    }

    function update_company_default_config($sid, $data){
        $this->db->where('sid',$sid);
        $this->db->update('users',$data);
    }
}
