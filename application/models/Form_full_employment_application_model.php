<?php

class Form_full_employment_application_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }


    function get_user_information($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');

        $data_row = array();

        switch (strtolower($user_type)) {
            case 'applicant':
                $this->db->where('employer_sid', $company_sid);
                $this->db->where('hired_status', 0);
                //$this->db->where('archived', 0);
                $this->db->where('sid', $user_sid);
                $data_row = $this->db->get('portal_job_applications')->result_array();
                break;
            case 'employee':
                $this->db->where('parent_sid', $company_sid);
                $this->db->where('sid', $user_sid);
                $data_row = $this->db->get('users')->result_array();
                break;
        }

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
        }
    }

    function create_form_request($company_sid, $user_type, $user_sid, $verification_key, $status){
        $request_info = $this->get_form_request_details($company_sid, $user_type, $user_sid);

        if(empty($request_info)) {

            $dataToSave = array();
            $dataToSave['company_sid'] = $company_sid;
            $dataToSave['user_type'] = $user_type;
            $dataToSave['user_sid'] = $user_sid;
            $dataToSave['verification_key'] = $verification_key;
            $dataToSave['status'] = $status;
            $dataToSave['status_date'] = date('Y-m-d H:i:s');

            $this->db->insert('form_full_employment_application', $dataToSave);
        }
    }

    function get_form_request_details($company_sid, $user_type, $user_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);


        $data_row = $this->db->get('form_full_employment_application')->result_array();

        if(!empty($data_row)){
            return $data_row[0];
        }else{
            return array();
        }
    }

    function get_form_request($verification_key){
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
		$this->db->order_by('sid', 'DESC');

        $data_row = $this->db->get('form_full_employment_application')->result_array();

        if(!empty($data_row)){
            return $data_row[0];
        }else{
            return array();
        }
    }

    function update_form_details($company_sid, $user_sid, $user_type, $dataToUpdate){

        switch(strtolower($user_type)){
            case 'applicant':
                $this->db->where('sid', $user_sid);
                $this->db->where('employer_sid', $company_sid);
                $this->db->update('portal_job_applications', $dataToUpdate);
                break;
            case 'employee':
                $this->db->where('sid', $user_sid);
                $this->db->where('parent_sid', $company_sid);
                $this->db->update('users', $dataToUpdate);
                break;
        }
    }

    function update_form_status($verification_key, $status){
        $dataToUpdate = array();
        $dataToUpdate['status'] = $status;
        $dataToUpdate['status_date'] = date('Y-m-d H:i:s');

        $this->db->where('verification_key', $verification_key);
        $this->db->update('form_full_employment_application', $dataToUpdate);
    }
    
    function get_company_details($company_sid){
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        $data_row = $this->db->get('users')->result_array();
        
        if(!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
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

    function get_applicants_details($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('portal_job_applications')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_portal_detail($employer_id)
    {
        $this->db->where('user_sid', $employer_id);
        $result = $this->db->get('portal_employer')->result_array();
        return $result[0];
    }

    function update_applicant($sid, $data, $type = NULL)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->update('portal_job_applications', $data);
        (!$result) ? 'false' : 'true';
    }

    function update_applicant_form_status($sid, $company_sid, $app_type = 'applicant', $status = 'sent')
    {
        $this->db->from('form_full_employment_application');
        $this->db->where('user_type', $app_type);
        $this->db->where('user_sid', $sid);

        $data_row = $this->db->count_all_results();

        if($data_row > 0){
            $data = array('status' => $status, 'status_date' => date('Y-m-d H:i:s'));
            $this->db->where('user_sid', $sid);
            $this->db->where('user_type', $app_type);
            $this->db->update('form_full_employment_application', $data);
        } else{
            $verification_key = random_key(80);
            $dataToSave = array();
            $dataToSave['company_sid'] = $company_sid;
            $dataToSave['user_type'] = $app_type;
            $dataToSave['user_sid'] = $sid;
            $dataToSave['verification_key'] = $verification_key;
            $dataToSave['status'] = $status;
            $dataToSave['status_date'] = date('Y-m-d H:i:s');
            $this->db->insert('form_full_employment_application', $dataToSave);
        }
    }

    function update_form($verification_key, $dataToUpdate){

        $this->db->where('verification_key', $verification_key);
        $this->db->update('form_full_employment_application', $dataToUpdate);
    }

    function get_license_details($user_type, $user_sid, $license_type) {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->limit(1);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    function save_license_info($data)
    {
        $this->db->insert('license_information', $data);
    }
    function update_license_info($license_id, $licenseData)
    {
        $this->db->where('sid', $license_id)->update('license_information', $licenseData);
    }

    function get_user_email_address ($user_sid, $user_type){
        $table = "";
        //
        if ($user_type == "employee"){
            $table = "users";
        } else {
            $table = "portal_job_applications";
        }
        //
        $this->db->select('email');
        $this->db->where('sid', $user_sid);
        $this->db->from($table);
        //
        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr["email"];
        } else {
            return "";
        }
    }

}