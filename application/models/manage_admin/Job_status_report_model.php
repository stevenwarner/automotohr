<?php

class Job_status_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    function get_active_organic_jobs_old($start,$end){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.activation_date,portal_job_listings.sid');
        $this->db->where('portal_job_listings.activation_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end)) . '"');

        $this->db->where('portal_job_listings.active',1);
        $this->db->where('portal_job_listings.organic_feed',1);

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        $jobs = $this->db->get('portal_job_listings')->result_array();

        return $jobs;
    }

    function get_active_jobs_old($start,$end){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.activation_date,portal_job_listings.sid');
        $this->db->where('portal_job_listings.activation_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end)) . '"');

        $this->db->where('portal_job_listings.active',1);

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        $jobs = $this->db->get('portal_job_listings')->result_array();

        return $jobs;
    }

    function get_inactive_jobs_old($start,$end){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.deactivation_date,portal_job_listings.sid');
        $this->db->where('portal_job_listings.activation_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end)) . '"');

        $this->db->where('portal_job_listings.active',0);

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        $jobs = $this->db->get('portal_job_listings')->result_array();

        return $jobs;
    }

    function get_active_organic_jobs($count=0, $company_sid = 'all',$limit = null, $start = null){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.activation_date,portal_job_listings.sid');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('portal_job_listings.active',1);
        $this->db->where('portal_job_listings.organic_feed',1);

        if($company_sid!='all'){
            $this->db->where('portal_job_listings.user_sid',$company_sid);
        }

        if($limit != null){
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        if($count){
            $jobs = $this->db->get('portal_job_listings')->num_rows();
        } else{
            $jobs = $this->db->get('portal_job_listings')->result_array();
        }


        return $jobs;
    }

    function get_active_jobs($count=0, $company_sid = 'all',$limit = null, $start = null){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.activation_date,portal_job_listings.sid');
         $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('portal_job_listings.active',1);
        $this->db->where('portal_job_listings.organic_feed',0);

        if($limit != null){
            $this->db->limit($limit, $start);
        }

        if($company_sid!='all'){
            $this->db->where('portal_job_listings.user_sid',$company_sid);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        

        if($count){
            $jobs = $this->db->get('portal_job_listings')->num_rows();
        } else{
            $jobs = $this->db->get('portal_job_listings')->result_array();
        }

        return $jobs;
    }

    function get_inactive_jobs($count=0, $company_sid = 'all',$limit = null, $start = null){
        $this->db->select('users.CompanyName,portal_job_listings.Title,portal_job_listings.deactivation_date,portal_job_listings.sid');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('portal_job_listings.active',0);

        if($limit != null){
            $this->db->limit($limit, $start);
        }

        if($company_sid!='all'){
            $this->db->where('portal_job_listings.user_sid',$company_sid);
        }
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('users','users.sid=portal_job_listings.user_sid','left');
        if($count){
            $jobs = $this->db->get('portal_job_listings')->num_rows();
        } else{
            $jobs = $this->db->get('portal_job_listings')->result_array();
        }

        return $jobs;
    }

}
