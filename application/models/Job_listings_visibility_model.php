<?php

class Job_listings_visibility_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function InsertNewVisibilityGroup($company_sid, $job_sid, $visible_to_employer_sids = array()) {
        foreach ($visible_to_employer_sids as $employer_sid) {
            $data = array();
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['job_sid'] = $job_sid;

            $this->db->insert('portal_job_listings_visibility', $data);
        }
    }

    function UpdateExistingVisibilityGroup($company_sid, $job_sid, $visible_to_employer_sids = array()) {
        $this->DeleteExistingVisibilityGroup($job_sid);
        $this->InsertNewVisibilityGroup($company_sid, $job_sid, $visible_to_employer_sids);
    }

    function DeleteExistingVisibilityGroup($job_sid) {
        if ($this->IfJobIdAlreadyExists($job_sid)) {
            $this->db->where('job_sid', $job_sid);
            $this->db->delete('portal_job_listings_visibility');
        }
    }

    function IfJobIdAlreadyExists($job_sid) {
        $this->db->where('job_sid', $job_sid);
        $records = $this->db->get('portal_job_listings_visibility')->num_rows();
        if (intval($records) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function GetEmployerIds($job_sid) {
        $this->db->where('job_sid', $job_sid);
        $query = $this->db->get('portal_job_listings_visibility')->result_array();

        $return_array = array();
        if (!empty($query)) {
            foreach ($query as $row) {
                $return_array[] = $row['employer_sid'];
            }
        }
        return $return_array;
    }

    //Get Jobs
    function GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, $limit = 0, $start = 1, $status = null) {
        $this->db->select('portal_job_listings.*, portal_job_listings_visibility.job_sid');
        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        if($status === null) {
            $this->db->where('portal_job_listings.active <', 2);
        } else {
            $this->db->where('portal_job_listings.active', intval($status));
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        return $this->db->get('portal_job_listings_visibility')->result_array();
    }

    function GetAllJobsCompanySpecific($company_sid, $keywords, $limit = 0, $start = 1, $status = null) {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);

        if($status === null){
            $this->db->where('active <', 2);
        }else {
            $this->db->where('active', intval($status));
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function GetAllJobsCountCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords, $status = null) {
        $this->db->select('portal_job_listings.*, portal_job_listings_visibility.job_sid');
        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        if($status === null){
            $this->db->where('active <', 2);
        }else {
            $this->db->where('active', intval($status));
        }



        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        return $this->db->get('portal_job_listings_visibility')->num_rows();
    }

    function GetAllJobsCountCompanySpecific($company_sid, $keywords, $status = null) {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }

        if($status === null){
            $this->db->where('active <', 2);
        }else {
            $this->db->where('active', intval($status));
        }

        $this->db->order_by('portal_job_listings.sid', 'DESC');
        return $this->db->get('portal_job_listings')->num_rows();
    }

    function GetAllJobsTitlesCompanySpecific($company_sid, $status = null) {
        $this->db->select('sid, Title, approval_status');

        if($status === null){
            $this->db->where('active <', 2);
        }else {
            $this->db->where('active', intval($status));
        }

        $this->db->where('user_sid', $company_sid);
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function GetAllJobsTitlesCompanyAndEmployerSpecific($company_sid, $employer_sid, $status = null) {
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.sid');
        $this->db->select('portal_job_listings.approval_status');


        if($status === null){
            $this->db->where('portal_job_listings.active <', 2);
        }else {
            $this->db->where('portal_job_listings.active', intval($status));
        }


        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        return $this->db->get('portal_job_listings_visibility')->result_array();
    }

    //Get Applicants
    function GetAllApplicantsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords = '', $limit = 0, $start = 1, $archived = 0, $applicant_filters = array()) {
        $this->db->select('portal_job_listings_visibility.job_sid');
        $this->db->select('portal_job_listings.Title');

        //$this->db->select('portal_job_applications.*');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');


        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where('portal_job_applications.archived', $archived);
        $this->db->where('portal_job_applications.hired_status', 0);
        
        if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

//        if (!empty($keywords)) {
//            $this->db->group_start();
//            $this->db->like('portal_job_applications.first_name', $keywords);
//            $this->db->or_like('portal_job_applications.last_name', $keywords);
//            $this->db->or_like('portal_job_applications.email', $keywords);
//            $this->db->group_end();
//        }
        $this->filtration($keywords);

        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_job_applications.job_sid = portal_job_listings_visibility.job_sid', 'left');
        $jobApplicants = $this->db->get('portal_job_listings_visibility')->result_array();
        $talentAndManual = $this->GetTalentNetworkAndManualCandidates($company_sid);
        return array_merge($jobApplicants, $talentAndManual);
    }

    function GetAllApplicantsCountCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords = '', $limit = 0, $start = 1, $archived = 0, $applicant_filters = array(), $today = false) {
        $this->db->select('portal_job_listings_visibility.job_sid');
        $this->db->select('portal_job_listings.Title');
        //$this->db->select('portal_job_applications.*');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');

        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where('portal_applicant_jobs_list.archived', $archived);
        $this->db->where('portal_job_applications.hired_status', 0);
        
        if($today == true){
            $this->db->where("portal_applicant_jobs_list.date_applied >= ", date('Y-m-d 00:00:00'));
            $this->db->where("portal_applicant_jobs_list.date_applied <= ", date('Y-m-d 23:59:59'));
        }
        
        if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

//        if (!empty($keywords)) {
//            // 1) Check keyword whether normal or email
//            // 2) If email then no need to check first name / last name only compare email
//            // 3) keywords word count. element zero at first name comparison and element 1 at last name
//            $this->db->group_start();
//            $this->db->like('portal_job_applications.first_name', $keywords);
//            $this->db->or_like('portal_job_applications.last_name', $keywords);
//            $this->db->or_like('portal_job_applications.email', $keywords);
//            $this->db->group_end();
//        }
        $this->filtration($keywords);

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid');
        //$this->db->join('portal_job_applications', 'portal_job_applications.job_sid = portal_job_listings_visibility.job_sid', 'left');
        $jobApplicantCount = $this->db->get('portal_job_listings_visibility')->num_rows();
        $talentAndManualCount = $this->GetCountTalentNetworkAndManualCandidates($company_sid);
        return intval($jobApplicantCount) + intval($talentAndManualCount);
    }

    function GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $employer_sid, $job_sid, $applicant_filters = array()) {
        $this->db->select('job_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $results = $this->db->get('portal_job_listings_visibility')->result_array();
        $tempArray = array();

        foreach ($results as $result) {
            $tempArray[] = $result['job_sid'];
        }

        if (in_array($job_sid, $tempArray) || is_admin($employer_sid)) {
            $this->db->select('portal_job_listings.Title');
            //$this->db->select('portal_job_applications.*');
            $this->db->select('portal_job_applications.sid');
            $this->db->select('portal_job_applications.employer_sid');
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
            $this->db->select('portal_job_applications.pictures');
            $this->db->select('portal_job_applications.email');
            $this->db->select('portal_job_applications.phone_number');
            $this->db->select('portal_job_applications.address');
            $this->db->select('portal_job_applications.country');
            $this->db->select('portal_job_applications.city');
            $this->db->select('portal_job_applications.state');
            $this->db->select('portal_job_applications.zipcode');
            $this->db->select('portal_job_applications.resume');
            $this->db->select('portal_job_applications.cover_letter');
            $this->db->select('portal_job_applications.YouTube_Video');
            $this->db->select('portal_job_applications.full_employment_application');
            $this->db->select('portal_job_applications.hired_sid');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->select('portal_job_applications.hired_date');
            $this->db->select('portal_job_applications.verification_key');
            $this->db->select('portal_job_applications.linkedin_profile_url');
            $this->db->select('portal_job_applications.extra_info');
            $this->db->select('portal_job_applications.referred_by_name');
            $this->db->select('portal_job_applications.referred_by_email');
            $this->db->select('portal_job_applications.job_fit_category_sid');

            $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left');
            $this->db->where('portal_job_applications.job_sid', $job_sid);
            $this->db->where('portal_job_applications.hired_status', 0);
            
            if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }
            
            $this->db->order_by('portal_job_applications.sid', 'DESC');
            return $this->db->get('portal_job_applications')->result_array();
        } else {
            return array();
        }
    }

    function GetAllApplicantsCountCompanyEmployerAndJobSpecific($company_sid, $employer_sid, $job_sid, $applicant_filters = array()) {
        $this->db->select('job_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $results = $this->db->get('portal_job_listings_visibility')->result_array();
        $tempArray = array();

        foreach ($results as $result) {
            $tempArray[] = $result['job_sid'];
        }

        if (in_array($job_sid, $tempArray) || is_admin($employer_sid)) {
            $this->db->select('portal_job_listings.Title');
            //$this->db->select('portal_job_applications.*');
            $this->db->select('portal_job_applications.sid');
            $this->db->select('portal_job_applications.employer_sid');
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
            $this->db->select('portal_job_applications.pictures');
            $this->db->select('portal_job_applications.email');
            $this->db->select('portal_job_applications.phone_number');
            $this->db->select('portal_job_applications.address');
            $this->db->select('portal_job_applications.country');
            $this->db->select('portal_job_applications.city');
            $this->db->select('portal_job_applications.state');
            $this->db->select('portal_job_applications.zipcode');
            $this->db->select('portal_job_applications.resume');
            $this->db->select('portal_job_applications.cover_letter');
            $this->db->select('portal_job_applications.YouTube_Video');
            $this->db->select('portal_job_applications.full_employment_application');
            $this->db->select('portal_job_applications.hired_sid');
            $this->db->select('portal_job_applications.hired_status');
            $this->db->select('portal_job_applications.hired_date');
            $this->db->select('portal_job_applications.verification_key');
            $this->db->select('portal_job_applications.linkedin_profile_url');
            $this->db->select('portal_job_applications.extra_info');
            $this->db->select('portal_job_applications.referred_by_name');
            $this->db->select('portal_job_applications.referred_by_email');
            $this->db->select('portal_job_applications.job_fit_category_sid');

            $this->db->where('portal_job_applications.hired_status', 0);
            
            $this->db->where('portal_job_applications.job_sid', $job_sid); // added later
            
            if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }
            
            $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left');
            $this->db->order_by('portal_job_applications.sid', 'DESC');
            return $this->db->get('portal_job_applications')->num_rows();
        } else {
            return 0;
        }
    }

    function GetAllApplicantsCompanySpecific($company_sid, $keywords = '', $limit = 0, $start = 1, $archived = 0, $applicant_filters = array()) {

        //$this->db->select('portal_job_applications.*');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');


        $this->db->select('portal_job_listings.Title');
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        $this->db->where('portal_job_applications.archived', $archived);
        $this->db->where('portal_job_applications.hired_status', 0);
        
        if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->filtration($keywords);

        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function GetAllApplicantsCountCompanySpecific($company_sid, $keywords = '', $limit = 0, $start = 1, $archived = 0, $applicant_filters = array()) {
        //$this->db->select('portal_job_applications.*');
        $this->db->select('portal_job_applications.sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.address');
        $this->db->select('portal_job_applications.country');
        $this->db->select('portal_job_applications.city');
        $this->db->select('portal_job_applications.state');
        $this->db->select('portal_job_applications.zipcode');
        $this->db->select('portal_job_applications.resume');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.YouTube_Video');
        $this->db->select('portal_job_applications.full_employment_application');
        $this->db->select('portal_job_applications.hired_sid');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.verification_key');
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.extra_info');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_job_applications.job_fit_category_sid');

        $this->db->select('portal_job_listings.Title');
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        $this->db->where('portal_job_applications.archived', $archived);
        $this->db->where('portal_job_applications.hired_status', 0);
        
        if(!empty($applicant_filters))
            {
                foreach($applicant_filters as $key => $value)
                {
                    $this->db->where('portal_job_applications.'.$key, $value);
                }
            }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

//        if (!empty($keywords)) {
//            $this->db->group_start();
//            $this->db->like('portal_job_applications.first_name', $keywords);
//            $this->db->or_like('portal_job_applications.last_name', $keywords);
//            $this->db->or_like('portal_job_applications.email', $keywords);
//            $this->db->group_end();
//        }
        $this->filtration($keywords);

        $this->db->order_by('portal_job_applications.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_applications.job_sid', 'left');
        return $this->db->get('portal_job_applications')->num_rows();
    }

    function GetTalentNetworkAndManualCandidates($company_sid) {
        $this->db->select('*');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('job_sid', 0);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->order_by('portal_job_applications.sid', 'DESC');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function GetCountTalentNetworkAndManualCandidates($company_sid) {
        $this->db->select('*');
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        //$this->db->where('job_sid', 0);
        
        $this->db->group_start();
        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Talent Network');
        $this->db->or_where('portal_applicant_jobs_list.applicant_type', 'Manual Candidate');
        $this->db->group_end();

        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
        $this->db->order_by('portal_job_applications.sid', 'DESC');
        return $this->db->get('portal_job_applications')->num_rows();
    }

    function filtration($keywords) {
        if (!empty($keywords)) {
            $position = strpos($keywords, '@');
          
            if ($position === false) { // not an email
                $exp = explode(' ', $keywords);

                if (sizeof($exp) > 1) { // there are more than 1 keywords
                    $firstname = $exp[0];
                    $lastname = '';
                    
                    for($i = 1; $i < sizeof($exp); $i++)
                    {
                        $lastname = $lastname . $exp[$i] . ' ';
                    }
                    
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', trim($firstname));
                    $this->db->like('portal_job_applications.last_name', trim($lastname));
                    $this->db->group_end();
                    
                } else { // there is only one keyword
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', trim($keywords));
                    $this->db->or_like('portal_job_applications.last_name', trim($keywords));
                    $this->db->group_end();
                }
            } else {   // this is an email
                $this->db->group_start();
                $this->db->like('portal_job_applications.email', trim($keywords));
                $this->db->group_end();
            }
        }
    }

    /**
     * Check if company is main or not
     * by job_sid
     * Created on: 05-08-2019 
     *
     * @param $job_sid Integer
     *
     * @return Bool
     */
    function isMainCompany($job_sid){
        $result = $this->db
        ->from('portal_job_listings')
        ->join('users', 'users.sid = portal_job_listings.user_sid', 'inner')
        ->where('portal_job_listings.sid', $job_sid)
        // ->where('portal_job_listings.ppj_product_id > ', 0)
        ->where('users.parent_sid', 0)
        ->count_all_results();

        return $result == 0 ? false : true;
    }

    /**
     * Get company id by job id
     * Created on: 05-08-2019 
     *
     * @param $job_sid Integer
     *
     * @return Array|String
     */
    function getCompanyIdByJobSid($job_sid){
        $result = $this->db
        ->select('users.sid')
        ->from('portal_job_listings')
        ->join('users', 'users.sid = portal_job_listings.user_sid', 'inner')
        ->where('portal_job_listings.sid', $job_sid)
        ->where('users.parent_sid', 0)
        ->get();

        $result_arr = $result->row_array();
        $result     = $result->free_result();

        return sizeof($result_arr) ? $result_arr['sid'] : array();
    }

    /**
     * Get job uid 
     * Created on: 05-08-2019 
     *
     * @param $companyId   Integer
     * @param $jobSid      Integer
     *
     * @return Integer|String
     */
    function getUidOfJob($companyId, $jobSid){
        $result = $this->db
        ->select('uid, publish_date')
        ->from('portal_job_listings_feeds_data')
        ->where('company_sid', $companyId)
        ->where('job_sid', $jobSid)
        ->where('active', 1)
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return sizeof($result_arr) ? $result_arr : 0;
    }

     /**
     * Get portal and company name
     * Created on: 06-08-2019 
     *
     * @param $companyId   Integer
     *
     * @return Array|Bool
     */
    function getPortalDetails($companyId){
        $result = $this->db
        ->select('
            portal_employer.sub_domain,
            portal_employer.job_title_location,
            users.CompanyName
        ')
        ->from('portal_employer')
        ->join('users', 'users.sid = portal_employer.user_sid')
        ->where('portal_employer.user_sid', $companyId)
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        
        return sizeof($result_arr) ? $result_arr : false;        
    }


    /**
     * Get portal and company name
     * Created on: 06-08-2019 
     *
     * @param $sid   Integer
     *
     * @return Array|String
     */
     function getJobCategoryNameById($sid) {
        $result = $this->db
        ->select('GROUP_CONCAT(value) as categoryName')
        ->where_in('sid', $sid)
        ->where('field_sid', 198)
        ->from('listing_field_list')
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return sizeof($result_arr) && isset($result_arr['categoryName']) ? $result_arr['categoryName'] : '';
    }

    /**
     * Get XML job ID
     * Created on: 07-08-2019
     *
     * @param   $jobSid  Integer
     * @return  Integer
     */
    function getXmlJobId($jobSid){
        $result = $this->db
        ->select('sid')
        ->from('xml_jobs')
        ->where('job_sid', $jobSid)
        ->limit(1)
        ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return isset($result_arr['sid']) ? $result_arr['sid'] : 0;
    }


    /**
     * Insert XML job ID
     * Created on: 07-08-2019
     *
     * @param   $dataArray Array
     * @return  Bool|Integer
     */
    function insertXmlJob($dataArray){
        $this->db->insert('xml_jobs', $dataArray);
        return $this->db->insert_id();
    }

    /**
     * Delete XML job ID
     * Created on: 07-08-2019
     *
     * @param   $jobSid Integer
     * @return  VOID
     */
    function deleteXMlJobById($jobSid){
        $this->db->delete('xml_jobs', array( 'job_sid' => $jobSid ));
    }

    /**
     * Check if product against job
     * Created on: 07-08-2019
     *
     * @param   $jobSid      Integer
     * @param   $productIds  Array
     * @return  Integer
     */
    function isPurchasedJob($jobSid, $productIds){
        return $this->db
        ->from('jobs_to_feed')
        ->where_in('product_sid', $productIds)
        ->where('portal_job_listings.active', 1)
        ->where('jobs_to_feed.job_sid', $jobSid)
        ->group_start()
        ->where('jobs_to_feed.expiry_date > "' . date('Y-m-d H:i:s') . '"')
        ->or_where('jobs_to_feed.expiry_date IS NULL ', null)
        ->group_end()
        ->join('portal_job_listings', 'portal_job_listings.sid = jobs_to_feed. job_sid')
        ->count_all_results();
    }

    /**
     * Update xml job
     * Created on: 07-08-2019
     *
     * @param   $updateArray Array
     * @param   $whereArray  Array
     * @return  VOID
     */
    function updateXmlJob($updateArray, $whereArray){
        $this->db->update('xml_jobs', $updateArray, $whereArray);
    }

    /**
     * Check approval management check
     * Created on: 07-08-2019
     *
     * @param   $companySid Integer
     * @return  Integer
     */
    function isApprovalManagementActive($companySid){
        return $this->db
        ->from('users')
        ->where('has_job_approval_rights', 1)
        ->where('sid', $companySid)
        ->count_all_results();
    }

    /**
     * Check job approval
     * Created on: 07-08-2019
     *
     * @param   $jobSid Integer
     * @return  Integer
     */
    function isJobApproved($jobSid){
        return $this->db
        ->from('portal_job_listings')
        ->where('approval_status', 'approved')
        ->where('sid', $jobSid)
        ->count_all_results();
    }


    /**
     * Get colmn from jobs
     * Created on: 07-08-2019
     *
     * @param   $jobSid Integer
     * @param   $column Integer
     * @return  Array|String
     */
    function getJobColumnById($jobSid, $column = '*'){
        $result =  $this->db
        ->select($column)
        ->from('portal_job_listings')
        ->where('sid', $jobSid)
        ->get();
        $result_arr = $result->row_array();
        $result     = $result->free_result();

        return sizeof($result_arr) && isset($result_arr[$column]) ? $result_arr[$column] : $result_arr;
    }


//
    function getApplicantsByJobId($job_sid,$company_sid){
        $result = $this->db
        ->from('portal_applicant_jobs_list')
        ->where('job_sid', $job_sid)
        ->where('company_sid', $company_sid)
        ->count_all_results();
        return $result ;
    }


}