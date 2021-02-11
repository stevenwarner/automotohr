<?php
class Dependents_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL) {
        $this->db->where('applicant_job_sid', $app_id);

        if($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if($date != NULL){ // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $result = $this->db->get('portal_applicant_rating');

        $rows = $result->num_rows();

        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $data = $this->db->get('portal_applicant_rating')->result_array();
            return round($data[0]['rating'] / $rows, 2);
        }
    }

    function check_sent_video_questionnaires($applicant_sid, $company_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if(!empty($data)){
            return true;
        } else {
            return false;
        }
    }

    function check_answered_video_questionnaires($applicant_sid, $company_sid){
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $data = $this->db->get('video_interview_questions_sent')->num_rows();

        if($data > 0){
            return true;
        } else {
            return false;
        }
    }

    function get_dependant_info($type, $users_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $type);
        $this->db->where('users_sid', $users_sid);
        return $this->db->get('dependant_information')->result_array();
    }

    function get_company_detail($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_applicants_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('portal_job_applications')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    public function delete_dependant($users_type, $users_sid, $dependant_sid, $company_sid)
    {
        $this->db->where('users_type', $users_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $dependant_sid);
        $this->db->delete('dependant_information');
    }

    function dependant_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('dependant_information')->result_array();
        return $result;
    }

    function check_authenticity($company_sid, $con_id, $flag = NULL)
    {
        $this->db->select('users.parent_sid');
        if ($flag == 'dependants') {
            $this->db->where('dependant_information.sid', $con_id);
            $this->db->join('users', 'users.sid = dependant_information.users_sid', 'left');
            $parent_sid = $this->db->get('dependant_information')->result_array();
        } else {
            $this->db->where('emergency_contacts.sid', $con_id);
            $this->db->join('users', 'users.sid = emergency_contacts.users_sid', 'left');
            $parent_sid = $this->db->get('emergency_contacts')->result_array();
        }
        if ($company_sid != $parent_sid[0]['parent_sid']) {
            return true;
        } else {
            return false;
        }
    }

    function update_dependant_info($dependant_id, $dependantData)
    {
        $this->db->where('sid', $dependant_id)
            ->update('dependant_information', $dependantData);
    }

    function save_dependant_info($data)
    {
        $this->db->insert('dependant_information', $data);
    }

}