<?php 
class Test_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_all_companies_form_list () {
        $this->db->select('sid, company_sid');
        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        return $record_arr;
    }

    function insert_profile_field ($data_to_insert) {
        $this->db->insert('job_fairs_forms_questions', $data_to_insert);
    }

    function get_all_specific_document () {
        $this->db->select('sid, company_sid, is_specific');
        $this->db->where('is_specific_type', NULL);
        $this->db->where('is_specific <>', 0);
        $record_obj = $this->db->get('documents_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        return $record_arr;
    }

    function find_user_type_in_company ($company_sid, $user_sid) {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_sid);
        $this->db->from('users');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            return 'employee';
        } else {
            return 'applicant';
        }
    }

    function update_user_type ($sid, $user_type) {
        $data_array = array();
        $data_array['is_specific_type'] = $user_type;
        //
        $this->db->where('sid', $sid);
        $this->db->update('documents_management', $data_array);
    }

}