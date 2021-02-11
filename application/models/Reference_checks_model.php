<?php

class reference_checks_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function GetAllReferences($user_sid, $company_sid, $user_type){
        $this->db->where('users_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'active');
        $result = $this->db->get('reference_checks')->result_array();

        if(count($result) > 0){
            return $result;
        }else{
            return array();
        }
    }

    public function GetReference($sid, $user_sid, $company_sid, $reference_type){
        $this->db->where('sid', $sid);
        $this->db->where('users_type', $reference_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $result = $this->db->get('reference_checks')->result_array();

        if(count($result) > 0){
            return $result;
        }else{
            return array();
        }
    }
    public function GetReferenceById($sid){
        $this->db->where('sid', $sid);

        $result = $this->db->get('reference_checks')->result_array();

        if(count($result) > 0){
            return $result[0];
        }else{
            return array();
        }
    }

    public function Insert($sid, $user_sid, $company_sid, $users_type, $dataArray = array()){
        $data = array(
            'user_sid' => $user_sid,
            'company_sid' => $company_sid,
            'users_type' => $users_type,
            'verification_key' => generateRandomString(24)
        );

        $data = array_merge($data, $dataArray);

        $this->db->insert('reference_checks', $data);
    }

    public function Update($sid, $user_sid, $company_sid, $users_type, $dataArray = array()){
        $data = array(
            'user_sid' => $user_sid,
            'company_sid' => $company_sid,
            'users_type' => $users_type
        );

        $data = array_merge($data, $dataArray);

        $this->db->where('sid', $sid);
        $this->db->update('reference_checks', $data);
    }

    public function Save($sid, $user_sid, $company_sid, $users_type, $dataArray = array()){
        if(intval($sid) > 0){
            $this->Update($sid, $user_sid, $company_sid, $users_type, $dataArray);
        }else{
            $this->Insert($sid, $user_sid, $company_sid, $users_type, $dataArray);
        }
    }


    public function UpdateQuestionnairInformation($sid, $user_sid, $company_sid, $users_type, $questionnair_information, $conducted_by){
        $this->db->where('sid' , $sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('users_type', $users_type);

        $data = array(
            'questionnaire_information' => serialize($questionnair_information),
            'questionnaire_conducted_by' => $conducted_by,
            'verification_key' => null
        );

        $this->db->update('reference_checks', $data);
    }

    public function SetStatusAs($sid, $status){
        $this->db->where('sid', $sid);

        $data = array(
            'status' => $status
        );

        $this->db->update('reference_checks', $data);
    }

    public function GetUserDetails($sid){
        $this->db->where('sid', $sid);
        $return = $this->db->get('users')->result_array();

        if(!empty($return)){
            return $return[0];
        }else{
            return array();
        }
    }
    
    public function check_whether_table_exists($template_code, $company_sid){
        $this->db->select('*');
        $this->db->where('template_code', $template_code);
        $this->db->where('company_sid', $company_sid);
        $template = $this->db->get('portal_email_templates')->result_array();
        
        return $template;
    }

    public function GetApplicantDetails($sid){
        $this->db->where('sid', $sid);
        $return = $this->db->get('portal_job_applications')->result_array();

        if(!empty($return)){
            return $return[0];
        }else{
            return array();
        }
    }
    public function GetApplicantDetailsWithVerificationKey($verificationKey){
        $this->db->where('verification_key', $verificationKey);
        $return = $this->db->get('portal_job_applications')->result_array();

        if(!empty($return)){
            return $return[0];
        } else {
            return array();
        }
    }

    public function GetReferenceCheckDetails($verificationKey){
        $this->db->where('verification_key', $verificationKey);
        $return = $this->db->get('reference_checks')->result_array();

        if(!empty($return)){
            return $return[0];
        }else{
            return array();
        }

    }

    public function UpdateReferenceVerificationKey($sid, $NewVerificationKey){
        $this->db->where('sid', $sid);

        $data = array(
            'verification_key' => $NewVerificationKey
        );

        $this->db->update('reference_checks', $data);
    }
}
