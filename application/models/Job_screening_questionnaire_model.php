<?php 
class Job_screening_questionnaire_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_screening_questionnaire_by_key($key=NULL) {
        if($key != NULL){
            $this->db->select('sid, portal_job_applications_sid, company_sid, job_sid, questionnaire, score, passing_score, questionnaire_manual_sent, manual_questionnaire_sid');
            $this->db->where('screening_questionnaire_key', $key);
            $records_obj = $this->db->get('portal_applicant_jobs_list');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            
            return $records_arr; 
        }
        return array();
    }
    
    function get_screening_questionnaire_id($sid){
        $this->db->select('Title, JobType, questionnaire_sid');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function get_screening_questionnaire_by_id($sid) {
        $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_screening_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function get_screenings_count_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->get()->num_rows();
    }
    
    function get_screening_questions_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->get()->result_array();
    }
    
    function get_screening_answer_count_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->get()->num_rows();
    }
    
    function get_screening_answers_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->get()->result_array();
    }
    
    function get_company_details($sid){
        $this->db->select('email, CompanyName, ContactName, extra_info, alternative_email, active');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }
    
    function update_questionnaire_result($sid, $questionnaire, $q_passing, $total_score, $questionnaire_result = NULL) {
        $update_data = array(   'questionnaire' => $questionnaire,
                                'score' => $total_score,
                                'passing_score' => $q_passing,
                                'questionnaire_ip_address' => getUserIP(),
                                'questionnaire_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                'questionnaire_result' => $questionnaire_result
                            );
        
        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
    }
    
    function get_applicant_email($sid, $table_name = 'users') {
        $this->db->select('email');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get($table_name);
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0]['email'];
        } else {
            return array();
        }
    }
    
    function generate_questionnaire_key($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            $questionnaire = $records_arr[0]['questionnaire'];
            $screening_questionnaire_key = $records_arr[0]['screening_questionnaire_key'];
            $questionnaire_manual_sent = $records_arr[0]['questionnaire_manual_sent'];
            
            if(($questionnaire == NULL || $questionnaire =='') && $questionnaire_manual_sent == 0) { // It can be manually sent to the applicant
                if($screening_questionnaire_key == NULL || $screening_questionnaire_key == '') { // Generare questionnaire key
                    $screening_questionnaire_key = random_key(72);
                    $update_data = array('screening_questionnaire_key' => $screening_questionnaire_key);
                    $this->db->where('sid', $sid);
                    $this->db->update('portal_applicant_jobs_list', $update_data);
                } 
            }
        }
        
        return $screening_questionnaire_key;
    }
    
    function get_applicant_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
    
    function get_job_title($sid) {
        $this->db->select('Title');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            return $records_arr[0]['Title'];
        } else {
            return array();
        }
    }
    
    function update_questionnaire_status($sid) {
        $update_data = array(   'questionnaire_manual_sent' => 1,
                                'questionnaire_sent_date' => date('Y-m-d H:i:s'));
        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
    }
    
    function get_email_template_data($sid) {
        $this->db->select('subject, text, from_name');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('email_templates');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }
    
    function check_screening_questionnaires($sid) {
        $this->db->select('sid');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_screening_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            $this->db->select('sid');
            $this->db->where('questionnaire_sid', $sid);
            $records_obj = $this->db->get('portal_questions');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            if(!empty($records_arr)) {
                return 'found';
            } else {
                return 'not_found';
            }
        } else {
            return 'not_found';
        }
    }
    
    function insert_questionnaire_result($data) {
        $this->db->insert('screening_questionnaire_results', $data);
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

    function get_company_logo($company_id)
    {
      $this->db->select('logo');
      $this->db->select('CompanyName');
      $this->db->where('sid',$company_id);
      $logo=$this->db->get('users');
      $result=$logo->result_array();
     
      return $result;
    }
}