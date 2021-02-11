<?php

class Reference_network_model extends CI_Model{
    function __construct(){
        parent::__construct();
    }

    function Insert($sid, $company_sid, $users_sid, $job_sid, $referred_to, $reference_email, $referred_date, $personal_message = null){
        $data = array();
        $data['company_sid'] = $company_sid;
        $data['users_sid'] = $users_sid;
        $data['job_sid'] = $job_sid;
        $data['referred_to'] = $referred_to;
        $data['reference_email'] = $reference_email;
        $data['referred_date'] = $referred_date;
        $data['personal_message'] = $personal_message;
        $this->db->insert('reference_network', $data);
    }

    function Update($sid, $company_sid, $users_sid, $job_sid, $referred_to, $reference_email, $referred_date, $personal_message = null){
        $data = array();
        $data['company_sid'] = $company_sid;
        $data['users_sid'] = $users_sid;
        $data['job_sid'] = $job_sid;
        $data['referred_to'] = $referred_to;
        $data['reference_email'] = $reference_email;
        $data['referred_date'] = $referred_date;
        $data['personal_message'] = $personal_message;
        $this->db->where('sid', $sid);
        $this->db->update('reference_network', $data);
    }

    function Save($sid, $company_sid, $users_sid, $job_sid, $referred_to, $reference_email, $referred_date, $personal_message = null){
        if ($sid == null) {
            $this->Insert($sid, $company_sid, $users_sid, $job_sid, $referred_to, $reference_email, $referred_date, $personal_message);
        } else {
            $this->Update($sid, $company_sid, $users_sid, $job_sid, $referred_to, $reference_email, $referred_date, $personal_message);
        }
    }

    function Get($sid){
        $this->db->where('sid', $sid);
        $this->db->from('reference_network');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
//        return $this->db->get('reference_network')->result_array();
    }

    function GetAll($company_sid, $users_sid, $limit = null, $start = null){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('users_sid', $users_sid);
        
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        
        $this->db->order_by('sid', 'desc');
        $this->db->from('reference_network');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
        
//        return $this->db->get('reference_network')->result_array();
    }
    
    function GetAllCount($company_sid, $users_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('users_sid', $users_sid);
        $this->db->from('reference_network');
        return $this->db->count_all_results();
    }

    function GetAllForCompany($company_sid, $limit = null, $start = null){
        $this->db->where('company_sid', $company_sid);
        
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        
        $this->db->order_by('sid', 'desc');
        $this->db->from('reference_network');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
//        return $this->db->get('reference_network')->result_array();
    }
    
    function GetAllForCompanyCount($company_sid) {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('reference_network')->num_rows();
    }

    function GetAllActiveJobs($company_sid){
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'DESC');
        $this->db->from('portal_job_listings');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetJobDetails($sid) {
        $this->db->select('Title');
        $this->db->where('sid', $sid);
        $this->db->from('portal_job_listings');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetUserDetails($user_sid){
        $this->db->select('first_name, last_name');
        $this->db->where('sid', $user_sid);
        $this->db->from('users');
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetShareJobReferrals($company_id,$type,$limit = null, $start = null) {
        $this->db->select('date_time,referral_name,referral_email,Title');
        $this->db->where('company_sid',$company_id);
        $this->db->where('type',$type);

        if($type=='coworker'){
            $this->db->select('first_name,last_name,email');
            $this->db->join('users','users.sid = coworker_referrals.coworker_sid','left');
        } else {
            $this->db->select('share_email,share_name');
        }

        if($limit != null){
            $this->db->limit($limit, $start);
        }
        
        $this->db->order_by("coworker_referrals.sid", "desc");
        $this->db->join('portal_job_listings','portal_job_listings.sid = coworker_referrals.job_sid','left');
        $this->db->from('coworker_referrals');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetShareJobReferralsCount($company_id,$type){
        $this->db->select('date_time,referral_name,referral_email,Title');
        $this->db->where('company_sid',$company_id);
        $this->db->where('type',$type);

        if($type=='coworker'){
            $this->db->select('first_name,last_name,email');
            $this->db->join('users','users.sid = coworker_referrals.coworker_sid','left');
        } else {
            $this->db->select('share_email,share_name');
        }

        $this->db->join('portal_job_listings','portal_job_listings.sid = coworker_referrals.job_sid','left');
        return $this->db->get('coworker_referrals')->num_rows();
    }

    function GetApplicantProvidedReferrals($company_sid, $limit = null, $start = null){
        $this->db->select('portal_job_applications.email,portal_job_applications.first_name,portal_job_applications.last_name,referred_by_name,referred_by_email,portal_job_listings.Title,portal_job_applications.desired_job_title,portal_job_applications.ip_address');
        $this->db->where('employer_sid',$company_sid);
        $this->db->where('referred_by_name <> ""');

        if($limit != null){
            $this->db->limit($limit, $start);
        }

        $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->join('portal_applicant_jobs_list','portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid','left');
        $this->db->join('portal_job_listings','portal_job_listings.sid = portal_applicant_jobs_list.job_sid','left');
        $this->db->order_by('portal_job_applications.sid','DESC');
        $this->db->from('portal_job_applications');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetApplicantProvidedReferralsCount($company_sid){
        $this->db->select('portal_job_applications.email,portal_job_applications.first_name,portal_job_applications.last_name,referred_by_name,referred_by_email,Title');
        $this->db->where('employer_sid',$company_sid);
        $this->db->where('referred_by_name <> ""');
        $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->join('portal_applicant_jobs_list','portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid','left');
        $this->db->join('portal_job_listings','portal_job_listings.sid = portal_applicant_jobs_list.job_sid','left');
        return $this->db->get('portal_job_applications')->num_rows();
    }
}