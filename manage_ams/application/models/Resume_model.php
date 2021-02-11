<?php

class Resume_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function deactivate_old_resume_request($company_sid, $user_type, $user_sid, $job_sid, $job_type) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('job_type', $job_type);
        $this->db->where('request_status', 1);
        $this->db->set('request_status', 0);
        $this->db->update('resume_request_logs');
    }

    function get_send_resume_template ($company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('template_code', 'send-resume-request');
        $this->db->where('is_custom', 0);

        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_applicant_information($applicant_sid) {
        $this->db->select('*');
        $this->db->where('sid', $applicant_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $record_arr = $record_arr[0];

            if (isset($record_arr['extra_info']) && !empty($record_arr['extra_info'])) {
                $extra_info = unserialize($record_arr['extra_info']);
                $record_arr = array_merge($record_arr, $extra_info);
            }

            return $record_arr;
        } else {
            return array();
        }
    }

    function set_offer_letter_verification_key ($sid, $verification_key, $type = 'applicant') {
        $this->db->where('sid', $sid);

        if($type == 'applicant'){
            $dataToUpdate = array();
            $dataToUpdate['verification_key'] = $verification_key;
            $this->db->update('portal_job_applications', $dataToUpdate);
        }else{

            $dataToUpdate = array();
            $dataToUpdate['emp_offer_letter_key'] = $verification_key;
            $this->db->update('users', $dataToUpdate);
        }
    }

    function insert_resume_request ($data_to_insert) {
        $this->db->insert('resume_request_logs', $data_to_insert);
    }

}