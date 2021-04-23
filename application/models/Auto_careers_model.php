<?php

class auto_careers_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_job_detail($job_sid){
        $this->db->select('*');
        $this->db->where('sid', $job_sid);
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->group_start();
        $this->db->where('expiration_date IS NULL', NULL, NULL);
        $this->db->or_where('expiration_date >= ', date('Y-m-d', strtotime('now')));
        $this->db->group_end();
        $record_obj = $this->db->get('portal_job_listings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function fetch_applicant_info ($applicant_sid) {
        $this->db->select('*');
        $this->db->where('sid', $applicant_sid);
        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_default_status_sid_and_text($company_sid){
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('css_class', 'not_contacted');
        $status = $this->db->get('application_status')->result_array();
        $data = array();

        if((sizeof($status) > 0) && isset($status[0]['sid'])){
            $data['status_sid'] = $status[0]['sid'];
            $data['status_name'] = $status[0]['name'];
        } else {
            $data['status_sid'] = 1;
            $data['status_name'] = 'Not Contacted Yet';
        }

        return $data;
    }

    function save_applicant ($applicationData) {
        $this->db->insert("portal_job_applications", $applicationData);
        return $this->db->insert_id();
    }

    function get_old_resume ($portal_job_applications_sid, $company_sid, $job_sid) {
        $this->db->select('resume');
        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('job_sid', $job_sid);

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return array();
        }
    }

    function update_applicant_resume ($sid, $data_to_update) {
        if(!empty($data_to_update['resume'])){
            $this->db->where('sid', $sid);
            $this->db->update('portal_job_applications', $data_to_update);
        }
    }

    function insert_resume_log($resume_log_data){
        $this->db->insert('resume_request_logs', $resume_log_data);
        _e($this->db->insert_id(), true);
    }

    function check_job_applicant($job_sid, $email, $company_sid = NULL) {
        //return 'no_record_found'; // once the new modifications are LIVE un-comment the code
        if($job_sid=='company_check'){ // It checks whether this applicant has applied for any job in this company
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->order_by('sid', 'desc');
            $this->db->limit(1);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if(sizeof($result)>0){
                $output = $result[0]['sid'];
            } else {
                $output = 'no_record_found';
            }

            return $output;
        } else { // It checks whether this applicant has applied for this particular job
            $this->db->select('sid');
            $this->db->where('employer_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->from('portal_job_applications');
            $result = $this->db->get()->result_array();

            if(empty($result)){
                return 0;
            } else {
                $applicant_sid = $result[0]['sid'];
                $this->db->select('sid');
                $this->db->where('job_sid', $job_sid);
                $this->db->where('portal_job_applications_sid', $applicant_sid);
                $this->db->from('portal_applicant_jobs_list');
                return $this->db->get()->num_rows();
            }
        }
    }

    function applicant_list_exists_check($job_applications_sid, $job_sid, $companyId) {
        $this->db->select('*');
        $this->db->where('portal_job_applications_sid', $job_applications_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('company_sid', $companyId);
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->get()->num_rows();
    }

    function add_applicant_job_details($applicant_info){
        $this->db->insert('portal_applicant_jobs_list', $applicant_info);
        return $this->db->insert_id();
    }

    function update_job_related_resume ($portal_job_applications_sid, $company_sid, $job_sid, $resume_to_update) {
       $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
       $this->db->where('company_sid', $company_sid);
       $this->db->where('job_sid', $job_sid);
       $this->db->update('portal_applicant_jobs_list', $resume_to_update);
    }

    function get_screening_questionnaire_by_id($sid) {
        $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
        $this->db->where('sid', $sid);
        $this->db->from('portal_screening_questionnaires');
        return $this->db->get()->result_array();
    }

    function get_possible_score_of_questions($sid, $type) {
        $result = 0;

        if($type == 'multilist') {
            $this->db->select_sum('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        } else {
            $this->db->select_max('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        }

        return $result;
    }

    function get_individual_question_details($sid) {
        $this->db->select('value, score, result_status');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if(!empty($records_arr)){
            $result = $records_arr[0];
        }

        return $result;
    }

    function insert_questionnaire_result($data) {
        $this->db->insert('screening_questionnaire_results', $data);
    }

    function update_questionnaire_result($sid, $questionnaire, $q_passing, $total_score, $questionnaire_result = NULL,$user_agent) {
        $update_data = array(   'questionnaire' => $questionnaire,
                                'score' => $total_score,
                                'passing_score' => $q_passing,
                                'questionnaire_ip_address' => getUserIP(),
                                'questionnaire_user_agent' => $user_agent,
                                'questionnaire_result' => $questionnaire_result
                            );

        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
    }


    function save_eeo_form($data) {
        $this->db->insert('portal_eeo_form', $data);
    }

     // save message to system private meassges table for keeping it track
    public function save_message($product_data) {
                        $product_data['outbox']                                 = 1;
                        $this->db->insert('private_message', $product_data);
                        //$product_data['outbox'] = 0;
                        //$this->db->insert('private_message', $product_data);
                        return "Message Saved Successfully";
    }
    // save message in auto responder email log
    public function save_email_log_autoresponder($email_log) {
        return $this->db->insert('email_log_autoresponder', $email_log);
                        
    }
    
    function getCompanyName($sid){
        $a = $this->db->select('CompanyName')
        ->where('sid', $sid)
        ->get('users');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b['CompanyName'];
    }

    /**
     * Check if applicant already applied for the job
     * 
     * @employee Mubashir Ahmeed
     * @date     02/09/2021
     * 
     * @param Integer $jobId
     * @param String  $email
     * 
     * @return Integer
     */
    function hasAlreadyApplied(
        $jobId,
        $email
    ){
        return
        $this->db
        ->from('portal_applicant_jobs_list')
        ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'inner')
        ->where('portal_applicant_jobs_list.job_sid', $jobId)
        ->where('portal_job_applications.email', $email)
        ->count_all_results();
    }
}
