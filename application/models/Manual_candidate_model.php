<?php

class manual_candidate_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function add_manual_candidate($data, $company_sid) {
        $this->db->insert('portal_job_applications', $data);
        
        if ($this->db->affected_rows() != 1) {
            return $result[1] = 0; 
        } else {
            $result[0] = $this->db->insert_id();
            $result[1] = 1; 
            return $result;
        }
    }

    function get_candidate($id) {
        $this->db->where('sid', $id);
        $result = $this->db->get('portal_manual_candidates')->result_array();
        return $result[0];
    }

    function update_candidate($id, $data) {
        $this->db->where('sid', $id);
        $this->db->update('portal_manual_candidates', $data);
    }

    function get_applicant($id) {
        $this->db->where('sid', $id);
        $result = $this->db->get('portal_job_applications')->result_array();
        return $result[0];
    }

    function update_applicant($id, $data) {
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function check_manual_candidate($email, $company_sid) {
        $this->db->select('sid');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->order_by('sid', 'desc');
        $this->db->limit(1);
        $this->db->from('portal_job_applications');
        $result = $this->db->get()->result_array();

        if (sizeof($result) > 0) {
            $output = $result[0]['sid'];
        } else {
            $output = 'no_record_found';
        }

        return $output;
    }
    
    function add_applicant_job_details($applicant_info){
        $this->db->insert('portal_applicant_jobs_list', $applicant_info);
        if ($this->db->affected_rows() != 1) {
            $result[0] = 0;
            $result[1] = 0;
            return $result;
        } else {
            $result[0] = $this->db->insert_id();
            $result[1] = 1;
            return $result; 
        }
    }
    
    function update_applicant_applied_date($sid, $update_array){
        $this->db->where('sid', $sid);
        $this->db->update('portal_job_applications', $update_array);
    }
    
    public function GetModuleStatus($company_sid, $module = 'jobs'){

        if($module == 'jobs') {
            $this->db->select('has_job_approval_rights');
        }else{
            $this->db->select('has_applicant_approval_rights');
        }

        $this->db->from('users');
        $this->db->where('sid', $company_sid);
        $this->db->limit(1);
        $myData = $this->db->get()->result_array();

        if(!empty($myData)){
            if($module == 'jobs') {
                return $myData[0]['has_job_approval_rights'];
            }elseif($module == 'applicants'){
                return $myData[0]['has_applicant_approval_rights'];
            }
        }else {
            return 0;
        }
    }
}
