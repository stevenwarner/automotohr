<?php
class Remarket_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    function get_remarket_company_settings($company_sid) {
        $this->db->select('status');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('remarket_company_settings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if(isset($record_arr[0])){
            return $record_arr[0];
        }else{
            $record_arr['status'] = 0;
            return $record_arr;
        }
    }
    function update_remarket_company_settings($company_sid, $dataToUpdate) {
        $this->db->where('company_sid',$company_sid);
        $q = $this->db->get('remarket_company_settings');
        if ( $q->num_rows() > 0 ) 
        {
            $this->db->where('company_sid',$company_sid);
            $this->db->update('remarket_company_settings',$dataToUpdate);
        } else {
            $this->db->set('company_sid', $company_sid);
            $this->db->insert('remarket_company_settings',$dataToUpdate);
        }
    }
    function get_remarket_settings() {
        $this->db->select('jobs,duration,email_template_sid');
        $record_obj = $this->db->get('remarket_settings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if(isset($record_arr[0])){
            return $record_arr[0];
        }else{
            $record_arr['jobs'] = 0;
            $record_arr['duration'] = 0;
            $record_arr['email_template_sid'] = 0;
            return $record_arr;
        }
    }
    function update_remarket_settings($dataToUpdate) {
        $q = $this->db->get('remarket_settings');
        if ( $q->num_rows() > 0 ) 
        {
            $this->db->update('remarket_settings',$dataToUpdate);
        } else {
            $this->db->insert('remarket_settings',$dataToUpdate);
        }
    }
    function get_remarket_templates() {
        $this->db->distinct();
        $this->db->select('*');
        $this->db->where('group','remarket_email_templates');
        $this->db->from('email_templates');
        return $this->db->get()->result_array();
    }
    function filters_of_active_jobs() {
        $this->db->select('portal_job_listings.user_sid,portal_job_listings.Location_Country, portal_job_listings.Location_State, portal_job_listings.JobCategory, users.has_job_approval_rights');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('portal_job_listings.published_on_career_page', 1);
                        
        $this->db->where('users.active', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        $this->db->from('portal_job_listings');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        return $record_arr;
    }
    function get_statenames($sids,$country_sid) {
        $this->db->select('sid,state_name');
        $this->db->where('country_sid', $country_sid);
        $this->db->where_in('sid', $sids);
//            $this->db->where('active', '1');
        $this->db->from('states');
        return $this->db->get()->result_array();
    }
    function get_job_categories($sids) {
        $this->db->select('sid,value');
        $this->db->where('field_sid', '198');
        $this->db->where_in('sid', $sids);
        $this->db->from('listing_field_list');
        return $this->db->get()->result_array();
    }
    function get_all_companies($company_sids)
    {
        $this->db->select('sid, CompanyName');
        $this->db->where_in('sid',$company_sids);
        $result = $this->db->get('users')->result_array();
        return $result;
    }
    function fetchJobsByFilters($filter_data, $limit = 100){
        $page = 1;
        if(!empty($filter_data['page'])){
            $page = $filter_data['page'];
        }
        //
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        //
        $this->db
        ->select('
            portal_job_listings.sid, 
            portal_job_listings.active as job_status, 
            portal_job_listings.Title as job_title,
            count(portal_applicant_jobs_list.portal_job_applications_sid) as applicants
        ')
        ->from('portal_job_listings')
        ->join('portal_applicant_jobs_list','portal_applicant_jobs_list.job_sid = portal_job_listings.sid','left')
        ->order_by('portal_job_listings.Title', 'ASC')
        ->group_by('portal_job_listings.sid');
        if(!empty($filter_data['sKeyword'])){
            $this->db->like('portal_job_listings.Title', $filter_data['sKeyword'], 'both');
        }
        if(!empty($filter_data['sCategory'])){
            $where = "FIND_IN_SET('".$filter_data['sCategory']."', JobCategory) > 0"; 
            $this->db->where($where);
        }
        if(!empty($filter_data['sCompany'])){
            $this->db->where('portal_job_listings.user_sid', $filter_data['sCompany']);
        }
        if(!empty($filter_data['sCountry'])){
            $this->db->where('portal_job_listings.Location_Country', $filter_data['sCountry']);
        }
        if(!empty($filter_data['sState'])){
            $this->db->where('portal_job_listings.Location_State', $filter_data['sState']);
        }
        if(!empty($filter_data['sCity'])){
            $this->db->where('portal_job_listings.Location_City', $filter_data['sCity']);
        }
        if(!empty($filter_data['sJobStaus'])){
            $this->db->where('portal_job_listings.active', $filter_data['sJobStaus']);
        }
        $tempdb = clone $this->db;
        //
        //$all_jobs_query = $this->db->get_compiled_select();
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        //
        $jobs = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($jobs)) return false;
        
        //
        if($page != 1) return $jobs;
       
        $jobCount = $tempdb->count_all_results();
        //
        return array( 'Jobs' => $jobs, 'JobCount' => $jobCount );
    }
    function fetchApplicantsByFilters($filter_data, $limit = 100){
        $page = 1;
        if(!empty($filter_data['page'])){
            $page = $filter_data['page'];
        }
        //
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        //
        $this->db
        ->distinct('portal_job_applications.sid')
        ->select('
            portal_job_applications.sid, 
            CONCAT(portal_job_applications.first_name," ",portal_job_applications.last_name) as name,
            portal_job_applications.email,
            count(portal_applicant_jobs_list.job_sid) as applied_on_jobs
        ')
        ->from('portal_job_applications')
        ->join('portal_applicant_jobs_list','portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid','left')
        ->join('portal_job_listings','portal_job_listings.sid = portal_applicant_jobs_list.job_sid','left')
        ->order_by('portal_job_listings.Title', 'ASC')
        ->group_by('portal_job_applications.sid');
        if(!empty($filter_data['applicant_ids'])){
            $this->db->where_in('portal_job_applications.sid', json_decode($filter_data['applicant_ids']));
        }
        if(!empty($filter_data['sKeyword'])){
            $this->db->like('portal_job_listings.Title', $filter_data['sKeyword'], 'both');
        }
        if(!empty($filter_data['sCategory'])){
            $where = "FIND_IN_SET('".$filter_data['sCategory']."', JobCategory) > 0"; 
            $this->db->where($where);
        }
        if(!empty($filter_data['sCompany'])){
            $this->db->where('portal_job_listings.user_sid', $filter_data['sCompany']);
        }
        if(!empty($filter_data['sCountry'])){
            $this->db->where('portal_job_applications.country', $filter_data['sCountry']);
        }
        if(!empty($filter_data['sState'])){
            $this->db->where('portal_job_applications.state', $filter_data['sState']);
        }
        if(!empty($filter_data['sCity'])){
            $this->db->where('portal_job_applications.city', $filter_data['sCity']);
        }
        if(isset($sJobStaus) && $filter_data['sJobStaus'] !== "all"){
            $this->db->where('portal_job_listings.active', $filter_data['sJobStaus']);
        }
        $tempdb = clone $this->db;
        //
        //$all_jobs_query = $this->db->get_compiled_select();
        $result = $this->db
        ->limit($limit, $start)
        ->get();
        //
        $applicants = $result->result_array();
        $result = $result->free_result();
        //
        if(!sizeof($applicants)) return false;
        
        //
        if($page != 1) return $applicants;
       
        $applicantCount = $tempdb->count_all_results();
        //
        return array( 'Applicants' => $applicants, 'ApplicantCount' => $applicantCount );
    }
    function getJobDetails($jobIds){
        //
        $query = $this->db
        ->select('
            portal_job_listings.sid, 
            portal_job_listings.active as job_status, 
            portal_job_listings.Title as job_title,
            portal_job_listings.JobCategory,
            portal_job_listings.Location_State,
            count(portal_applicant_jobs_list.portal_job_applications_sid) as applicants
        ')
        ->from('portal_job_listings')
        ->join('portal_applicant_jobs_list','portal_applicant_jobs_list.job_sid = portal_job_listings.sid','left')
        ->order_by('portal_job_listings.Title', 'ASC')
        ->group_by('portal_job_listings.sid')
        ->where_in('portal_job_listings.sid',$jobIds)
        ->get();
        //
        $jobs = $query->result_array();
        $result = $query->free_result();
        foreach($jobs as $key => $job){
            $where = "FIND_IN_SET('".$job['JobCategory']."', JobCategory) > 0"; 
                    
            $jobs[$key]['related_jobs'] = $this->db
                    ->select("sid")
                    ->from('portal_job_listings')
                    ->where('Location_State',$job['Location_State'])
                    ->where('active', 1)
                    ->where('published_on_career_page', 1)
                    ->where('approval_status', "approved")
                    ->where($where)
                    ->count_all_results();
        }
        return $jobs;
    }
    
} 