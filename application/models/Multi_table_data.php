<?php

class Multi_table_data extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getData($insert_keys) {
        $str = $this->db->insert_string('users', $insert_keys);
        $this->db->query($str);
    }

    function getJobCategory() {
        $my_sesseion = $this->session->userdata('logged_in');
        $company_id = $my_sesseion["company_detail"]["sid"];
        $this->db->where('field_sid', 198);
        $this->db->where('company_sid',0);
        $this->db->or_where('company_sid', $company_id); 
        //$this->db->where('field_type', 'system');
        $this->db->order_by('value');
        $result_data_list = $this->db->get('listing_field_list');
        
        $data_list = array();
        foreach ($result_data_list->result_array() as $row_data_list) {
            $data_list[] = array("id" => $row_data_list['sid'], "value" => $row_data_list['value']);
        }
        return $data_list;
    }

    function getCustomJobCategory($company_sid) {
        $this->db->where('field_sid', 198);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('field_type', 'custom');

        $this->db->order_by('value');
        $result_data_list = $this->db->get('listing_field_list');
        $data_list = array();
        foreach ($result_data_list->result_array() as $row_data_list) {
            $data_list[] = array("id" => $row_data_list['sid'], "value" => $row_data_list['value']);
        }
        return $data_list;
    }

    function getScreeningQuestionnaires($empId) {
        $screening_questions = array();
        $this->db->where('employer_sid', $empId);
        $this->db->where('type', 'job');
        $result_data_list = $this->db->get('portal_screening_questionnaires');
        if ($result_data_list->num_rows() > 0) {
            foreach ($result_data_list->result_array() as $row) {
                $screening_questions[] = array("sid" => $row['sid'], "caption" => $row['name'], "employer_sid" => $row['employer_sid'], "passing_score" => $row['passing_score']);
            }
        }
        return $screening_questions;
    }

    function getJobCategoriesByIds($categories_ids) {
        $this->db->where('field_sid', 198);
        $this->db->where_in('sid',$categories_ids);
        $this->db->order_by('value');
        $result_data_list = $this->db->get('listing_field_list');
        
        $data_list = array();
        foreach ($result_data_list->result_array() as $row_data_list) {
            $data_list[] = array("sid" => $row_data_list['sid'], "name" => $row_data_list['value']);
        }
        return $data_list;
    }
}
