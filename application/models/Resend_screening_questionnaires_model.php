<?php

class Resend_screening_questionnaires_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function get_all_questionnaires_by_employer($employer_sid) {
        $this->db->select('portal_screening_questionnaires.sid,portal_screening_questionnaires.name,count(portal_questions.sid) as que_count');
        $this->db->where('employer_sid', $employer_sid);
        $this->db->order_by("sid", "desc");
        $this->db->join('portal_questions','portal_questions.questionnaire_sid = portal_screening_questionnaires.sid','left')->group_by('portal_questions.questionnaire_sid');
        return $this->db->get('portal_screening_questionnaires')->result_array();
    }

    function previous_questionnaire_id($job_sid){
        $this->db->select('questionnaire_sid');
        $this->db->where('sid',$job_sid);
        return $this->db->get('portal_job_listings')->result_array()[0];
    }

    function job_title($job_sid){
        $this->db->select('Title');
        $this->db->where('sid',$job_sid);
        return $this->db->get('portal_job_listings')->result_array()[0]['Title'];
    }

    function copy_applicant_job_list($app_job_list_sid,$company_sid,$employer_sid){
        $this->db->where('sid',$app_job_list_sid);
        $result = $this->db->get('portal_applicant_jobs_list')->result_array()[0];

        $insert_array = array();
        $insert_array['applicant_sid'] = $result['sid'];
        $insert_array['applicant_jobs_list_sid'] = $result['portal_job_applications_sid'];
        $insert_array['company_sid'] = $company_sid;
        $insert_array['employer_sid'] = $employer_sid;
        $insert_array['job_sid'] = $result['job_sid'];
        $insert_array['questionnaire'] = $result['questionnaire'];
        $insert_array['score'] = $result['score'];
        $insert_array['passing_score'] = $result['passing_score'];
        $insert_array['questionnaire_result'] = $result['questionnaire_result'];
        $insert_array['questionnaire_ip_address'] = $result['questionnaire_ip_address'];
        $insert_array['questionnaire_user_agent'] = $result['questionnaire_user_agent'];
        $insert_array['questionnaire_manual_sent'] = $result['questionnaire_manual_sent'];
        $insert_array['questionnaire_sent_date'] = $result['questionnaire_sent_date'];
        $insert_array['manual_questionnaire_sid'] = $result['manual_questionnaire_sid'];

        $this->db->insert('screening_questionnaire_manual_sent_tracking',$insert_array);

        return $result['screening_questionnaire_key'];
    }

    function update_applicant_job_list($app_job_list_sid,$new_que_id,$questionnaire_key){
        $update_array = array();

        $update_array['questionnaire'] = NULL;
        $update_array['score'] = '';
        $update_array['passing_score'] = '';
        $update_array['questionnaire_result'] = NULL;
        $update_array['questionnaire_ip_address'] = NULL;
        $update_array['questionnaire_user_agent'] = NULL;
        $update_array['questionnaire_manual_sent'] = 1;
        $update_array['questionnaire_sent_date'] = date('Y-m-d H:i:s');
        $update_array['manual_questionnaire_sid'] = $new_que_id;

        if($questionnaire_key==NULL){
            $update_array['screening_questionnaire_key'] = random_key(72);
            $questionnaire_key = $update_array['screening_questionnaire_key'];
        }

        $this->db->where('sid',$app_job_list_sid);
        $this->db->update('portal_applicant_jobs_list',$update_array);
        return $questionnaire_key;
    }

    function get_applicant_data($applicant_sid){
        $this->db->select('first_name, last_name, employer_sid, email, applicant_type, desired_job_title');
        $this->db->where('sid', $applicant_sid);
        $data = $this->db->get('portal_job_applications')->result_array();

        if(!empty($data)){
            return $data[0];
        } else {
            return 0;
        }
    }

}
