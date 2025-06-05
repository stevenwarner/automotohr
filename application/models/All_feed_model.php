<?php

class all_feed_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_company_jobs_indeed() {
        $product_sid = array(1, 21);
        $this->db->where_in('product_sid', $product_sid);
        $this->db->where('active', 1);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_career_builder() {
        $product_sid = array(38);
        $this->db->where_in('product_sid', $product_sid);
        //$this->db->where('active', 1);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_zipRec() {
        $this->db->where('product_sid', 2);
        $this->db->where('active', 1);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_ams_zipRec() {
        $product_ids = array(3, 4);
        $this->db->where('active', 1);
        $this->db->where_in('product_sid', $product_ids);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs_ams() {
        $this->db->select('portal_job_listings.sid as jobId, expiry_date');
        $product_ids = array(3, 4);
        $this->db->where('active', 1);
        $this->db->where_in('product_sid', $product_ids);
        $this->db->where('expiry_date > "' . date('Y-m-d H:i:s') . '"');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed.	job_sid');
        return $this->db->get('jobs_to_feed')->result_array();
    }

    function get_all_company_jobs() {
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_company_jobs_trato() {
        $this->db->where('active', 1);
        $this->db->where('user_sid', 57);
        $this->db->limit(1);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_portal_detail($company_id) {
        $this->db->where('user_sid', $company_id);
        $result = $this->db->get('portal_employer')->result_array();

        if(!empty($result)){
            return $result[0];
        } else {
            return array();
        }
    }

    function get_job_category_name_by_id($sid) {
        $this->db->select('value');
        $this->db->where('sid', $sid);
        $this->db->where('field_sid', '198');
        $this->db->from('listing_field_list');
        return $this->db->get()->result_array();
    }

    function get_company_name_by_id($sid) {
        $this->db->select('CompanyName');
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();
        if (!empty($result)) {
            return $result[0]['CompanyName'];
        }
    }

    function get_company_name_and_job_approval($sid) {
        $this->db->select('CompanyName, has_job_approval_rights');
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function get_company_detail($sid) {
//        $this->db->select('Logo');
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();
        if (!empty($result)) {
            return $result[0];
        }
    }

    function saveData($incomingData) {
        $data['post_template'] = $incomingData;
        $this->db->insert("twitter_feed", $incomingData);
    }

    function saveApplicant($applicationData) {
        $this->db->insert("portal_job_applications", $applicationData);
        return $this->db->insert_id();
    }

    function savetotable($data, $tablename) {
        $this->db->insert("portal_job_applications", $applicationData);
        return $this->db->insert_id();
    }

    function companyIdByName($companyName) {
        $this->db->where('LOWER(CompanyName)', $companyName);
        $this->db->where('parent_sid', 0);
        $result = $this->db->get('users')->result_array();
        return $result[0]['sid'];
    }

    function get_all_organic_jobs($featuredArray) {
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->where_not_in('sid', $featuredArray);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_company_jobs_indeed_organic($featuredArray) {
        $this->db->where('active', 1);
        $this->db->where('organic_feed', 1);
        $this->db->where_not_in('sid', $featuredArray);
        $this->db->order_by('sid', 'desc');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_all_active_companies($feedSid) {
        $result = $this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `is_paid`='1' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            $data = array();
            foreach ($result as $r) {
                // Check if feed is allowed
                if($this->db
                    ->where('company_sid', $r['sid'])
                    ->where('feed_sid', $feedSid)
                    ->where('status', 0)
                    ->count_all_results('feed_restriction')
                ){
                    continue;
                }
                $data[] = $r['sid'];
            }
            return $data;
        } else {
            return 0;
        }
    }

    function get_product_data($product_id) {
        $res = $this->db->get_where('products', array('sid' => $product_id))->result_array();
        return $res[0];
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

    function add_applicant_job_details($applicant_info){
        $this->db->insert('portal_applicant_jobs_list', $applicant_info);
        if ($this->db->affected_rows() != 1) {
            $result[0] = 0;
            $result[1] = 0;
            return $result;
        } else {
            $result[0] = $this->db->insert_id();
            $result[1] = 1;
            $applicant_info['sid'] = $result[0];
            $this->get_remarket_company_settings($applicant_info);
            return $result; 
        }
    }

    function get_job_detail($job_sid){
        $this->db->select('*');
        $this->db->where('sid', $job_sid);
        $record_obj = $this->db->get('portal_job_listings');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function fetch_job_id_from_random_key($uid){
        $this->db->select('job_sid');
        $this->db->where('uid', $uid);
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['job_sid'];
        } else {
            return null;
        }
    }

    public function getQuestionType ($questionId) {
        //
        $this->db->select('question_type');
        $this->db->where('sid', $questionId);
        //
        $record_obj = $this->db->get('portal_questions');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr['question_type'];
        } else {
            return '';
        }

    }

    public function getJobQuestionnaireInfo ($questionId) {
        //
        $this->db->select('questionnaire_sid');
        $this->db->where('sid', $questionId);
        $this->db->limit(1);
        //
        $record_obj = $this->db->get('portal_questions');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            //
            $this->db->select('sid, name');
            $this->db->where('sid', $record_arr['questionnaire_sid']);
            //
            $record_obj = $this->db->get('portal_screening_questionnaires');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($record_arr)) {
                return $record_arr;
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function getQuestionAnswerId ($questionId, $value) {
        $this->db->select('sid');
        $this->db->where('questions_sid', $questionId);
        $this->db->where('score', $value);

        $record_obj = $this->db->get('portal_question_option');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['sid'];
        } else {
            return 0;
        }
    }

    function fetch_uid_from_job_sid($job_sid){
        $this->db->select('uid, publish_date');
        $this->db->where('job_sid', $job_sid);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        $this->db->limit(1);

        $record_obj = $this->db->get('portal_job_listings_feeds_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return '';
        }
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

    function generate_questionnaire_key($sid) {
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

    function update_questionnaire_status($sid) {
        $update_data = array(   'questionnaire_manual_sent' => 1,
                                'questionnaire_sent_date' => date('Y-m-d H:i:s'));
        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $update_data);
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

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
    }

    function applicant_list_exists_check($job_applications_sid, $job_sid, $companyId) {
        $this->db->select('*');
        $this->db->where('portal_job_applications_sid', $job_applications_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('company_sid', $companyId);
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->get()->num_rows();
    }

    function get_applicant_job($job_applications_sid, $job_sid, $companyId) {
        $this->db->select('*');
        $this->db->where('portal_job_applications_sid', $job_applications_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('company_sid', $companyId);
        $this->db->from('portal_applicant_jobs_list');
        return $this->db->get()->row();
    }

    function get_all_active_company_jobs() {
        $this->db->select('pjl.sid, pjl.user_sid, pjl.active, pjl.status, pjl.featured, pjl.activation_date, pjl.deactivation_date, pjl.access_type, pjl.company_name');
        $this->db->select('pjl.Title, pjl.JobType, pjl.JobCategory, pjl.Location_Country, pjl.Location_State, pjl.Location_ZipCode, pjl.YouTube_Video');
        $this->db->select('pjl.JobDescription, pjl.JobRequirements, pjl.DesiredSalaryType, pjl.SalaryType, pjl.Location_City, pjl.Salary, pjl.approval_status');
        $this->db->select('users.sid, users.parent_sid, users.active as active_company, users.expiry_date');
        $this->db->select('users.CompanyName, users.has_job_approval_rights, users.Logo, users.ContactName, users.YouTubeVideo');
        $this->db->select('portal_employer.sub_domain, portal_employer.job_title_location');
        $this->db->where('pjl.active', 1);
        $this->db->where('users.active', 1);
        $this->db->group_start();
        $this->db->where('users.expiry_date > ', '2016-04-20 13:26:27');
        $this->db->or_where('users.expiry_date', null);
        $this->db->group_end();
        $this->db->join('users', 'pjl.user_sid = users.sid', 'left');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid');
        //$this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        //$this->db->limit(5);
        $record_obj = $this->db->get('portal_job_listings as pjl');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_active_company_organic_jobs($featuredArray) {
        $this->db->select('pjl.sid, pjl.user_sid, pjl.active, pjl.status, pjl.featured, pjl.activation_date, pjl.deactivation_date, pjl.access_type, pjl.company_name');
        $this->db->select('pjl.Title, pjl.JobType, pjl.JobCategory, pjl.Location_Country, pjl.Location_State, pjl.Location_ZipCode, pjl.YouTube_Video');
        $this->db->select('pjl.JobDescription, pjl.JobRequirements, pjl.DesiredSalaryType, pjl.SalaryType, pjl.Location_City, pjl.Salary, pjl.approval_status');
        $this->db->select('users.sid, users.parent_sid, users.active as active_company, users.expiry_date');
        $this->db->select('users.CompanyName, users.has_job_approval_rights, users.Logo, users.ContactName, users.YouTubeVideo');
        $this->db->select('portal_employer.sub_domain, portal_employer.job_title_location');
        $this->db->where('pjl.active', 1);
        $this->db->where('pjl.organic_feed', 1);
        $this->db->where_not_in('pjl.sid', $featuredArray);
        $this->db->where('users.active', 1);
        $this->db->group_start();
        $this->db->where('users.expiry_date > ', '2016-04-20 13:26:27');
        $this->db->or_where('users.expiry_date', null);
        $this->db->group_end();
        $this->db->join('users', 'pjl.user_sid = users.sid', 'left');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid');
        //$this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        //$this->db->limit(5);
        $record_obj = $this->db->get('portal_job_listings as pjl');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_job_details($sid) {
        $this->db->select('pjl.sid, pjl.user_sid, pjl.active, pjl.status, pjl.featured, pjl.activation_date, pjl.deactivation_date, pjl.access_type, pjl.company_name');
        $this->db->select('pjl.Title, pjl.JobType, pjl.JobCategory, pjl.Location_Country, pjl.Location_State, pjl.Location_ZipCode, pjl.YouTube_Video');
        $this->db->select('pjl.JobDescription, pjl.JobRequirements, pjl.DesiredSalaryType, pjl.SalaryType, pjl.Location_City, pjl.Salary, pjl.approval_status');
        $this->db->select('users.sid, users.parent_sid, users.active as active_company, users.expiry_date');
        $this->db->select('users.CompanyName, users.has_job_approval_rights, users.Logo, users.ContactName, users.YouTubeVideo');
        $this->db->select('portal_employer.sub_domain, portal_employer.job_title_location');
        $this->db->where('pjl.active', 1);
        $this->db->where('pjl.sid', $sid);
        $this->db->where('users.active', 1);
        $this->db->group_start();
        $this->db->where('users.expiry_date > ', '2016-04-20 13:26:27');
        $this->db->or_where('users.expiry_date', null);
        $this->db->group_end();
        $this->db->join('users', 'pjl.user_sid = users.sid', 'left');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid');
        //$this->db->query("SELECT `sid` FROM `users` WHERE `parent_sid` = '0' AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        //$this->db->limit(5);
        $record_obj = $this->db->get('portal_job_listings as pjl');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)) {
            $record_arr = $record_arr[0];
        }
        return $record_arr;
    }

    /**
    * Fetch Indeed Xml jobs
    * Created on: 07-08-2019
    *
    * @return Array|Bool
    */
   function getIndeedXmlJobs(){
       $result = $this->db
       ->select('job_content as job')
       ->from('xml_jobs')
       ->where('is_indeed_job', 1)
       ->order_by('updated_at', 'DESC')
       ->get();
       //
       $result_arr = $result->result_array();
       $result     = $result->free_result();
       // if(!sizeof($result_arr)) return false;
       return $result_arr;
   }
   /**
    * Fetch ZipRecruiter Xml jobs
    * Created on: 07-08-2019
    *
    * @return Array|Bool
    */
   function getZipRecruiterXmlJobs(){
       $result = $this->db
       ->select('job_content as job')
       ->from('xml_jobs')
       ->where('is_ziprecruiter_job', 1)
       ->order_by('updated_at', 'DESC')
       ->get();
       //
       $result_arr = $result->result_array();
       $result     = $result->free_result();
       // if(!sizeof($result_arr)) return false;
       return $result_arr;
   }
   /**
    * Insert/Update Xml job
    * Created on: 07-08-2019
    *
    * @return Integer
    */
   function setXmlJob($dataArray, $jobSid, $type = 'indeed'){
       // Check if job exists
       $xmlJobId = 0;
       $result = $this->db
       ->select('sid')
       ->from('xml_jobs')
       ->where('job_sid', $jobSid)
       ->get();
       $result_arr = $result->row_array();
       $result     = $result->free_result();
       // Insert process
       if(!sizeof($result_arr)){
           $this->db->insert('xml_jobs', $dataArray);
           return $this->db->insert_id();
       }
       // This will make sure we don't update
       // the jobs for comparision reason
       // tobe removed after testing
       // $dataArray = array();
       // $dataArray[$type == 'indeed' ? 'is_indeed_job' : 'is_ziprecruiter_job'] = 1;
       // $dataArray[$type == 'indeed' ? 'is_ziprecruiter_job' : 'is_indeed_job'] = 0;
       // Update process
       $xmlJobId = $result_arr['sid'];
       $this->db
       ->update('xml_jobs', $dataArray, array(
           'sid' => $xmlJobId
       ));
       return $xmlJobId;
   }
   /**
    * Delete all xml jobs of a specific company
    * Created on: 08-08-2019
    *
    * @param $companyId Integer
    *
    * @return VOID
    */
   function flushXmlJobsByCompanyId($companyId){
       $this->db->delete('xml_jobs', array( 'company_sid' => $companyId ));
   }
   /**
    * Check for main company
    * Created on: 08-08-2019
    *
    * @param $companyId   Integer
    * @param $status      Integer Optional
    * Default is 'TRUE'
    *
    * @return Array|Bool
    */
   function checkCompanyStatusByCompanyId($companyId, $status = TRUE){
       $result = $this->db
       ->select('
           users.sid,
           users.CompanyName,
           portal_employer.job_title_location,
           users.has_job_approval_rights,
           portal_employer.sub_domain,
       ')
       ->from('users')
       ->join('portal_employer', 'portal_employer.user_sid = users.sid', 'inner')
       ->where('users.parent_sid', 0)
       ->where('users.career_site_listings_only', 0)
       ->where('users.per_job_listing_charge', $status)
       ->where('users.is_paid', 1)
       ->where('users.active', 1)
       ->group_start()
       ->where('users.expiry_date > ', '2016-04-20 13:26:27')
       ->or_where('users.expiry_date IS NULL', NULL)
       ->group_end()
       ->get();
       //
       $result_arr = $result->row_array();
       $result     = $result->free_result();
       //
       return sizeof($result_arr) && isset($result_arr['sid']) ? $result_arr : FALSE;
   }
   /**
    * Delete all xml jobs of a specific company
    * Created on: 08-08-2019
    *
    * @param $companyId Integer
    * @param $is_organic Bool Optional
    * Default is 'TRUE'
    *
    * @return Array|Bool
    */
   function jobsByCompanyId($companyId, $is_organic = TRUE) {
       $product_sid = array(1, 21);
       $result = $this->db
       ->select('GROUP_CONCAT(jobs_to_feed.sid) as sid')
       ->from('jobs_to_feed')
       ->where_in('product_sid', $product_sid)
       ->where('active', 1)
       ->where('expiry_date > "' . date('Y-m-d H:i:s') . '"')
       ->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed. job_sid')
       ->limit(1)
       ->get();
       //
       $featuredArray = $result->row_array()['sid'];
       $result        = $result->free_result();
       //
       $this->db
       ->select('
           portal_job_listings.sid,
           portal_job_listings.user_sid,
           portal_job_listings.expiration_date,
           portal_job_listings.Title,
           portal_job_listings.JobType,
           portal_job_listings.JobCategory,
           portal_job_listings.JobDescription,
           portal_job_listings.Location_Country,
           portal_job_listings.Location_State,
           portal_job_listings.Location_ZipCode,
           portal_job_listings.JobRequirements,
           portal_job_listings.Location_City,
           portal_job_listings.Salary,
           portal_job_listings.SalaryType,
           portal_job_listings.organic_feed,
           portal_job_listings.approval_status,
           portal_job_listings.activation_date
       ')
       ->from('portal_job_listings')
       ->where('portal_job_listings.active', 1)
       ->where('portal_job_listings.user_sid', $companyId)
       ->where_not_in('portal_job_listings.sid', $featuredArray);
       // For PPJ jobs
       if(!$is_organic){
           $this->db
           ->select('
               users.CompanyName,
               portal_job_listings.ppj_expiry_days,
               portal_job_listings.ppj_activation_date
           ')
           ->join('users','users.sid = portal_job_listings.user_sid', 'inner')
           ->where('portal_job_listings.approval_status','approved')
           ->where('portal_job_listings.ppj_product_id >',0)
           ->where('users.parent_sid',0)
           ->where('users.active',1)
           ->where('users.per_job_listing_charge',1)
           ->where("expiry_date > '2016-04-20 13:26:27' OR expiry_date IS NULL");
       }else{
           $this->db->where('organic_feed', 1);
       }
       //
       $result = $this->db->get();
       $jobs   = $result->result_array();
       $result = $result->free_result();
       return $jobs;
   }
   /**
    * Get all organic jobs for Indeed
    * Created on: 16-08-2019
    *
    * @param $approved Integer
    * Default is '0'
    *
    * @return Array
    */
   function getAllCompanyJobsIndeedOrganic($approved = 0){
       $product_sid = array(1, 21);
       $result = $this->db
       ->select('GROUP_CONCAT(jobs_to_feed.sid) as sid')
       ->from('jobs_to_feed')
       ->where_in('product_sid', $product_sid)
       ->where('active', 1)
       ->where('expiry_date > "' . date('Y-m-d H:i:s') . '"')
       ->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed. job_sid')
       ->limit(1)
       ->get();
       //
       $featuredArray = $result->row_array()['sid'];
       $result        = $result->free_result();
       $this->db
       ->select('portal_job_listings.*')
       ->from('portal_job_listings')
       ->where('portal_job_listings.active', 1)
       ->where_not_in('portal_job_listings.sid', $featuredArray);
       if($approved){
           $this->db
           ->select('users.CompanyName')
           ->where('portal_job_listings.approval_status','approved')
           ->where('portal_job_listings.ppj_product_id >',0)
           ->where('users.parent_sid',0)
           ->where('users.active',1)
           ->where('users.per_job_listing_charge',1)
           ->where("expiry_date > '2016-04-20 13:26:27' OR expiry_date IS NULL")
           ->join('users','users.sid = portal_job_listings.user_sid', 'inner');
       }
       $result = $this->db->get();
       $jobs   = $result->result_array();
       $result = $result->free_result();
       return $jobs;
   }
   function update_applicant_resume ($sid, $data_to_update) {
       $this->db->where('sid', $sid);
       $this->db->update('portal_job_applications', $data_to_update);
   }


    function get_old_resume ($sid) {
        $this->db->select('resume');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return array();
        }
    }

    function insert_resume_log($resume_log_data){
        $this->db->insert('resume_request_logs', $resume_log_data);
    }
    
    private function get_remarket_company_settings($job_list) {
        if($job_list['job_sid'] > 0){
            $this->db->select('status');
            $this->db->where('company_sid', $job_list['company_sid']);
            $record_obj = $this->db->get('remarket_company_settings');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if(isset($record_arr[0])){
                $data = $record_arr[0];
            }else{
                $record_arr['status'] = 1;
                $data = $record_arr;
            }
            $data['questionnaire'] = '';
            if(isset($job_list['questionnaire'])){
                $data['questionnaire'] = $job_list['questionnaire'];
                unset($job_list['questionnaire']);
            }
            $data['talent_and_fair_data'] = '';
            if(isset($job_list['talent_and_fair_data'])){
                $data['talent_and_fair_data'] = $job_list['talent_and_fair_data'];
                unset($job_list['talent_and_fair_data']);
            }
            
            $data['portal_applicant_jobs_list'] = json_encode($job_list);
            $this->db->select('*');
            $this->db->where('sid', $job_list['portal_job_applications_sid']);
            $record_obj = $this->db->get('portal_job_applications');
            $data['portal_job_applications'] = json_encode($record_obj->row_array());
            $record_obj->free_result();
            
            send_settings_to_remarket(REMARKET_PORTAL_SAVE_APPLICANT_URL,$data);
        }
        
    }

    public function GetCompanyAddresses($companyIdArray){
        //
        $query = 
        $this->db
        ->select('
            users.sid,
            users.Location_Address,
            users.Location_City,
            states.state_name as Location_State,
            countries.country_name as Location_Country
        ')
        ->join('states', 'states.sid = users.Location_State', 'inner')
        ->join('countries', 'countries.sid = users.Location_State', 'inner')
        ->where_in('users.sid', $companyIdArray)
        ->get('users');
        //
        $records = $query->result_array();
        //
        $notFound = [];
        //
        $returnArray = [];
        //
        foreach($records as $record){
            //
            $returnArray[$record['sid']] = '';
            //
            if(empty($record['Location_Address'])){
                $notFound[] = $record['sid'];
            } else{
                $returnArray[$record['sid']] = ucwords($record['Location_Address'].', '.
                $record['Location_City'].', '.
                $record['Location_State'].', '.
                $record['Location_Country']);
            }
        }

        //
        if(!empty($notFound)){
            //
            $query = 
            $this->db
            ->select('company_sid, address')
            ->group_by('company_sid')
            ->where_in('company_sid', $notFound)
            ->where('status', 1)
            ->order_by('sid', 'DESC')
            ->get('company_addresses_locations');
            //
            $records = $query->result_array();
            //
            $query->free_result();
            //
            if(!empty($records)){
                foreach($records as $record){
                    //
                    $returnArray[$record['company_sid']] = ucwords($record['address']);
                }
            }
        }
        //
        return $returnArray;
    }

    function get_screening_questionnaire_by_id($sid) {
        $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_screening_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
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

    function insert_questionnaire_result($data) {
        $this->db->insert('screening_questionnaire_results', $data);
    }

    public function processJobScreeningQuestionnaire (
        $extraInfo,
        $jobQuestions
    ) {
        //
        $post_questionnaire_sid                             = $jobQuestions['questionnaire_sid'];
        $post_screening_questionnaires                      = $this->get_screening_questionnaire_by_id($post_questionnaire_sid);
        $questionnaire_serialize                            = '';
        $total_score                                        = 0;
        $total_questionnaire_score                          = 0;
        $array_questionnaire                                = array();
        $q_name                                             = $post_screening_questionnaires[0]['name'];
        $q_send_pass                                        = $post_screening_questionnaires[0]['auto_reply_pass'];
        $q_pass_text                                        = $post_screening_questionnaires[0]['email_text_pass'];
        $q_send_fail                                        = $post_screening_questionnaires[0]['auto_reply_fail'];
        $q_fail_text                                        = $post_screening_questionnaires[0]['email_text_fail'];
        $overall_status                                     = 'Pass';
        $is_string                                          = 0;
        $screening_questionnaire_results                    = array();
        $all_questions_ids                                  = $jobQuestions['all_questions_ids'];

        foreach ($all_questions_ids as $value) {
            $q_passing = 0;
            $post_questions_sid = $value;
            $caption = 'caption' . $value;
            $type = 'type' . $value;
            $answer = $jobQuestions[$type] . $value;
            $questions_type = $jobQuestions[$type];
            $my_question = '';
            $individual_score = 0;
            $individual_passing_score = 0;
            $individual_status = 'Pass';
            $result_status = array();
        
            if (isset($jobQuestions[$caption])) {
                $my_question = $jobQuestions[$caption];
            }

            $my_answer = NULL;

            if (isset($jobQuestions[$answer])) {
                $my_answer = $jobQuestions[$answer];
            }
            
            if($questions_type != 'string') { // get the question possible score
                $q_passing = $this->get_possible_score_of_questions($post_questions_sid, $questions_type);
            }

            if($my_answer != NULL) { // It is required question           
                if (is_array($my_answer)) {
                    $answered = array();
                    $answered_result_status = array();
                    $answered_question_score = array();
                    $total_questionnaire_score += $q_passing;
                    $is_string = 1;
                    
                    foreach ($my_answer as $answers) {
                            $result = explode('@#$', $answers);
                            $a = $result[0];
                            $answered_question_sid = $result[1];
                            $question_details = $this->get_individual_question_details($answered_question_sid);
                            
                            if(!empty($question_details)) {
                                $questions_score = $question_details['score'];
                                $questions_result_status = $question_details['result_status'];
                                $questions_result_value = $question_details['value'];
                            }
                            
                            $score = $questions_score;
                            $total_score += $questions_score;
                            $individual_score += $questions_score;
                            $individual_passing_score = $q_passing;
                            $answered[] = $a;
                            $result_status[] = $questions_result_status;
                            $answered_result_status[] = $questions_result_status;
                            $answered_question_score[] = $questions_score;
                    }
                } else {
                    $result = explode('@#$', $my_answer);
                    $total_questionnaire_score += $q_passing;
                    $a = $result[0];
                    $answered = $a;
                    $answered_result_status = '';
                    $answered_question_score = 0;

                    if (isset($result[1])) {
                        $answered_question_sid = $result[1];
                        $question_details = $this->get_individual_question_details($answered_question_sid);

                        if(!empty($question_details)) {
                            $questions_score = $question_details['score'];
                            $questions_result_status = $question_details['result_status'];
                            $questions_result_value = $question_details['value'];
                        }
                        
                        $is_string = 1;
                        $score = $questions_score;
                        $total_score += $questions_score;
                        $individual_score += $questions_score;
                        $individual_passing_score = $q_passing;
                        $result_status[] = $questions_result_status;
                        $answered_result_status = $questions_result_status;
                        $answered_question_score = $questions_score;
                    }
                }
                
                if(!empty($result_status)) {
                    if(in_array('Fail', $result_status)) {
                        $individual_status = 'Fail';
                        $overall_status = 'Fail';
                    }
                }
            } else { // it is optional question
                $answered = '';
                $individual_passing_score = $q_passing;
                $individual_score = 0;
                $individual_status = 'Candidate did not answer the question';
                $answered_result_status = '';
                $answered_question_score = 0;
            }

            $array_questionnaire[$my_question] = array( 'answer' => $answered,
                                                        'passing_score' => $individual_passing_score,
                                                        'score' => $individual_score,
                                                        'status' => $individual_status,
                                                        'answered_result_status' => $answered_result_status,
                                                        'answered_question_score' => $answered_question_score);  
        } // this is the end of foreach


        $questionnaire_result = $overall_status;
        $datetime = date('Y-m-d H:i:s');
        $remote_addr = getUserIP();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $questionnaire_data = array('applicant_sid' => $extraInfo['applicantId'],
                                    'applicant_jobs_list_sid' => $extraInfo['applicantJobsListId'],
                                    'job_sid' => $extraInfo['jobId'],
                                    'job_title' => $extraInfo['jobTitle'],
                                    'job_type' => $extraInfo['jobType'],
                                    'company_sid' => $extraInfo['companyId'],
                                    'questionnaire_name' => $jobQuestions['q_name'],
                                    'questionnaire' => $array_questionnaire,
                                    'questionnaire_result' => $questionnaire_result,
                                    'attend_timestamp' => $datetime,
                                    'questionnaire_ip_address' => $remote_addr,
                                    'questionnaire_user_agent' => $user_agent
                                );
        $questionnaire_serialize = serialize($questionnaire_data);
        $array_questionnaire_serialize = serialize($array_questionnaire);

        
        $screening_questionnaire_results = array(   'applicant_sid' => $extraInfo['applicantId'],
                                                    'applicant_jobs_list_sid' => $extraInfo['applicantJobsListId'],
                                                    'job_sid' => $extraInfo['jobId'],
                                                    'job_title' => $extraInfo['jobTitle'],
                                                    'job_type' => $extraInfo['jobType'],
                                                    'company_sid' => $extraInfo['companyId'],
                                                    'questionnaire_name' => $jobQuestions['q_name'],
                                                    'questionnaire' => $array_questionnaire_serialize,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );                                  
                          
        $this->update_questionnaire_result($extraInfo['applicantJobsListId'], $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
        $this->insert_questionnaire_result($screening_questionnaire_results);;

        $send_mail = false; // send email 
        $from = FROM_EMAIL_INFO;
        $fromName = $extraInfo['companyName'];
        $mail_body = '';

        if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
            $send_mail = true;
            $mail_body = $q_pass_text;
        }

        if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
            $send_mail = true;
            $mail_body = $q_fail_text;
        }

        if ($send_mail) { // get Applicant email address
            $to = $this->job_screening_questionnaire_model->get_applicant_email($extraInfo['applicantId'], 'portal_job_applications');
            $subject = $extraInfo['jobTitle'];
            $applicant_data = $this->job_screening_questionnaire_model->get_applicant_details($extraInfo['applicantId']);
            $applicant_fname = $applicant_data['first_name'];
            $applicant_lname = $applicant_data['last_name'];

            $mail_body = str_replace('{{company_name}}', ucwords($extraInfo['companyName']), $mail_body);
            $mail_body = str_replace('{{applicant_name}}', ucwords($applicant_fname . ' ' . $applicant_lname), $mail_body);
            $mail_body = str_replace('{{job_title}}', $extraInfo['jobTitle'], $mail_body);
            $mail_body = str_replace('{{first_name}}', ucwords($applicant_fname), $mail_body);
            $mail_body = str_replace('{{last_name}}', ucwords($applicant_lname), $mail_body);

            $mail_body = str_replace('{{company-name}}', ucwords($extraInfo['companyName']), $mail_body);
            $mail_body = str_replace('{{applicant-name}}', ucwords($applicant_fname . ' ' . $applicant_lname), $mail_body);
            $mail_body = str_replace('{{job-title}}', $extraInfo['jobTitle'], $mail_body);
            $mail_body = str_replace('{{first-name}}', ucwords($applicant_fname), $mail_body);
            $mail_body = str_replace('{{last-name}}', ucwords($applicant_lname), $mail_body);

            sendMail($from, $to, $subject, $mail_body, $fromName);
            sendMail($from, 'mubashar@automotohr.com', $subject, $mail_body, $fromName);
        }  
          
    }

    function save_eeo_form($data) {
        $this->db->insert('portal_eeo_form', $data);
    }

    function insert_applicant_job_queue($data) {
        $this->db->insert('portal_applicant_jobs_queue', $data);
    }
}