<?php

class Varification_document_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_users_pending_w4 ($company_sid, $user_type, $count = FALSE) {
        $inactive_employee_sid = $this->getAllCompanyInactiveEmployee($company_sid);
        //
        $inactive_applicant_sid = $this->getAllCompanyInactiveApplicant($company_sid);
        //
        $this->db->select('user_type, employer_sid as user_sid, sent_date, signature_timestamp as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->group_start();
            $this->db->where_not_in('employer_sid', $inactive_employee_sid);
            $this->db->where_not_in('user_type', 'employee');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where_not_in('employer_sid', $inactive_applicant_sid);
            $this->db->where_not_in('user_type', 'applicant');
            $this->db->group_end();
        }

        $this->db->where('emp_identification_number', NULL);
        $this->db->where('emp_name', NULL);
        $this->db->where('emp_address', NULL);
        $this->db->where('first_date_of_employment', NULL);
        $this->db->where('first_date_of_employment', NULL);
        $this->db->where('user_consent', 1);
        $this->db->where('uploaded_file', NULL);
        //
        if($count){
            return $this->db->count_all_results('form_w4_original');
        }
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

    function get_all_users_pending_i9 ($company_sid, $user_type, $count = FALSE) {
        $inactive_employee_sid = $this->getAllCompanyInactiveEmployee($company_sid);
        //
        $inactive_applicant_sid = $this->getAllCompanyInactiveApplicant($company_sid);
        //
        $this->db->select('user_type, user_sid, sent_date, applicant_filled_date as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee') {
            $this->db->group_start();
            $this->db->where_not_in('user_sid', $inactive_employee_sid);
            $this->db->where_not_in('user_type', 'employee');
            $this->db->group_end();
        } else {
            $this->db->group_start();
            $this->db->where_not_in('user_sid', $inactive_applicant_sid);
            $this->db->where_not_in('user_type', 'applicant');
            $this->db->group_end();
        }
        
        $this->db->where('section2_sig_emp_auth_rep', NULL);
        $this->db->where('section3_emp_sign', NULL);
        $this->db->where('user_consent', 1);
        $this->db->where('s3_filename', NULL);
        //
        if($count){
            return $this->db->count_all_results('applicant_i9form');
        }
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

    function getAllCompanyInactiveEmployee($companySid) {
        $a = $this->db
        ->select('
            sid
        ')
        ->where('parent_sid', $companySid)
        ->group_start()
        ->where('active <>', 1)
        ->where('general_status <>', 'active')
        ->group_end()
        ->or_where('terminated_status <>', 0)
        ->or_where('general_status', 'suspended')
        ->order_by('concat(first_name,last_name)', 'ASC', false)
        ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    function getAllCompanyInactiveApplicant($companySid) {
        $a = $this->db
        ->select('
            portal_job_applications_sid as sid
        ')
        ->where('company_sid', $companySid)
        ->where('archived', 1)
        ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

}