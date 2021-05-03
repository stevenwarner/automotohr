<?php

class Varification_document_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_users_pending_w4 ($company_sid, $user_type) {

        $this->db->select('user_type, employer_sid as user_sid, sent_date, signature_timestamp as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('emp_identification_number', NULL);
        $this->db->where('emp_name', NULL);
        $this->db->where('emp_address', NULL);
        $this->db->where('first_date_of_employment', NULL);
        $this->db->where('first_date_of_employment', NULL);
        $this->db->where('user_consent', 1);
        $records_obj = $this->db->get('form_w4_original');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            foreach ($records_arr as $i_key => $value) {
                $records_arr[$i_key]['document_name'] = 'W4 Fillable';
            }
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_all_users_pending_i9 ($company_sid, $user_type) {

        $this->db->select('user_type, user_sid, sent_date, applicant_filled_date as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('section2_sig_emp_auth_rep', NULL);
        $this->db->where('section3_emp_sign', NULL);
        $this->db->where('user_consent', 1);
        $records_obj = $this->db->get('applicant_i9form');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            foreach ($records_arr as $i_key => $value) {
                $records_arr[$i_key]['document_name'] = 'I9 Fillable';
            }
            $return_data = $records_arr;
        }

        return $return_data;
    }

}