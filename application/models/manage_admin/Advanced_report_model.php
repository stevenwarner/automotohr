<?php

class Advanced_report_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all_companies()
    {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    function get_active_jobs()
    {
        $this->db->select('sid, Title');
        $this->db->where('active', 1);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_company_jobs($company_sid)
    {
        $this->db->select('sid, Title, active');
        //        $this->db->where('active', 1);
        $this->db->where('user_sid', $company_sid);
        return $this->db->get('portal_job_listings')->result_array();
    }

    function get_not_hired_applicants($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('hired_status', 0);
        $applicants = $this->db->get('portal_job_applications')->result_array();
        $applicant_sids = array();

        foreach ($applicants as $applicant) {
            $applicant_sids[] = $applicant['sid'];
        }

        return $applicant_sids;
    }

    //    function get_applicants($company_sid = NULL, $brand_sid = NULL, $search = '', $search2 = '') {
//        if ($company_sid == NULL) {
//            $companies = $this->get_brand_companies($brand_sid);
//            $company_sids = array();
//
//            foreach ($companies as $company) {
//                $company_sids[] = $company['company_sid'];
//            }
//        }
//
//        if ($brand_sid == NULL) {
//            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
//        } else {
//            if (!empty($company_sids)) {
//                $this->db->where_in('portal_applicant_jobs_list.company_sid', $company_sids);
//            } else {
//                return array();
//            }
//        }
//
//        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
//        $this->db->select('portal_applicant_jobs_list.date_applied');
//        $this->db->select('portal_applicant_jobs_list.status');
//        $this->db->select('portal_applicant_jobs_list.job_sid');
//        $this->db->select('portal_applicant_jobs_list.applicant_type');
//        $this->db->select('portal_applicant_jobs_list.questionnaire');
//        $this->db->select('portal_applicant_jobs_list.score');
//        $this->db->select('portal_applicant_jobs_list.passing_score');
//        $this->db->select('portal_applicant_jobs_list.desired_job_title');
//        $this->db->select('portal_job_applications.sid as applicant_sid');
//        $this->db->select('portal_applicant_jobs_list.company_sid');
//        $this->db->select('portal_job_applications.first_name');
//        $this->db->select('portal_job_applications.last_name');
//        $this->db->select('portal_job_applications.email');
//        $this->db->select('portal_job_applications.phone_number');
//
//        $this->db->where('portal_applicant_jobs_list.archived', 0);
//        $this->db->where('portal_job_applications.hired_status', 0);
//
//        if($search != ''){
//            $this->db->where($search);
//        }
//        if($search2 != ''){
//            $this->db->where($search2);
//        }
//
//        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
//        $this->db->order_by('date_applied', 'DESC');
//        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
//
//        foreach($applications as $key => $application){
//            $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
//
//            $review_count = $this->getApplicantReviewsCount($application['applicant_sid']);
//            $review_score = $this->getApplicantAverageRating($application['applicant_sid']);
//
//            if($review_score == '' || $review_score == NULL){
//                $review_score = 0;
//            }
//
//            $applications[$key]['review_count'] = $review_count;
//            $applications[$key]['review_score'] = $review_score;
//
//            $company_det = get_company_details($application['company_sid']);
//            if (isset($company_det['CompanyName'])) {
//                $company_name = $company_det['CompanyName'];
//            } else {
//                $company_name = '';
//            }
//            $applications[$key]['CompanyName'] = $company_name;
//
//            //**** get interview quesionnaire score ****//
//            $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
//            $this->db->select('users.first_name, users.last_name');
//            $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
//            $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
//            //$this->db->where('interview_questionnaire_score.company_sid', $company_sid);
//
//            if ($brand_sid == NULL) {
//                $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
//            } else {
//                if (!empty($company_sids)) {
//                    $this->db->where_in('interview_questionnaire_score.company_sid', $company_sids);
//                } else {
//                    return array();
//                }
//            }
//
//            $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
//            $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
//            //**** get interview quesionnaire score ****//
//        }
//
//        return $applications;
//    }

    function get_job_title_by_type($job_sid, $applicant_type, $desired_job_title)
    {
        $job_title = '';

        if ($applicant_type == 'Applicant') {
            $job_title = get_job_title($job_sid);
        } else if ($applicant_type == 'Talent Network' || $applicant_type == 'Imported Resume') {
            if ($desired_job_title != NULL && $desired_job_title != '') {
                $job_title = $desired_job_title;
            } else {
                $job_title = 'Job Not Applied';
            }
        } else if ($applicant_type == 'Manual Candidate') {
            if ($job_sid != 0) {
                $job_title = get_job_title($job_sid);
            } else {
                $job_title = 'Job Not Applied';
            }
        } else {
            $job_title = 'Job Not Applied';
        }

        if (empty($job_title)) {
            $job_title = 'Job Deleted / does not exist in system!';
        }

        return $job_title;
    }

    function get_applicants_count($search = '')
    {
        if ($search != '') {
            $this->db->where($search);
        }
        return $this->db->get('portal_job_applications')->num_rows();
    }

    function get_all_hired_jobs($company_sid = NULL, $brand_sid = NULL, $start_date = null, $end_date = null, $status = 0)
    {
        if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();

            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
        }

        if ($brand_sid == NULL) {
            $this->db->where('portal_job_applications.employer_sid', $company_sid);
        } else {
            if (!empty($company_sids)) {
                $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
            } else {
                return array();
            }
        }

        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');
        if ($status) {
            $this->db->where('portal_job_applications.hired_status', 1);
            $this->db->where('portal_job_applications.hired_sid >', 0);
        }
        //$this->db->where('portal_job_applications.employer_sid', $company_sid);

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_date)) . '"');
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $applications = $this->db->get('portal_job_applications')->result_array();
        $i = 0;

        foreach ($applications as $application) {
            //$applications[$i]['Title'] = get_job_title($application['job_sid']);

            $company_sid = $applications[$i]['employer_sid'];
            $company_det = get_company_details($company_sid);
            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }
            $applications[$i]['CompanyName'] = $company_name;

            $i++;
        }

        return $applications;
    }

    function GetAllUsers($company_sid = NULL, $brand_sid = NULL)
    {
        if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();

            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
        }

        if ($brand_sid == NULL) {
            $this->db->where('parent_sid', $company_sid);
        } else {
            if (!empty($company_sids)) {
                $this->db->where_in('parent_sid', $company_sids);
            } else {
                return array();
            }
        }

        $this->db->select('sid, parent_sid, first_name, last_name');
        $this->db->where('active', 1);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        return $this->db->get('users')->result_array();
    }

    function GetAllEventsByCompanyAndEmployer($company_sid, $employer_sid)
    {
        $this->db->select('portal_schedule_event.*');
        $this->db->select('portal_job_applications.first_name as applicant_first_name');
        $this->db->select('portal_job_applications.last_name as applicant_last_name');
        $this->db->select('portal_job_applications.employer_sid');

        $this->db->where('portal_schedule_event.companys_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('portal_schedule_event.employers_sid', $employer_sid);
        $this->db->or_where('find_in_set (' . $employer_sid . ', portal_schedule_event.interviewer)');
        $this->db->group_end();
        $this->db->where('portal_schedule_event.category', 'interview');

        $this->db->join('portal_job_applications', 'portal_schedule_event.applicant_job_sid = portal_job_applications.sid', 'right');
        $this->db->order_by('portal_schedule_event.date', 'DESC');
        $events = $this->db->get('portal_schedule_event')->result_array();
        $i = 0;

        foreach ($events as $event) { //Foreach Loop used as For Loop ( Please review )
            $company_sid = $events[$i]['employer_sid'];

            $company_det = get_company_details($company_sid);
            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }

            $events[$i]['CompanyName'] = $company_name;
            $i++;
        }

        return $events;
    }

    function GetAllApplicantsBetween($company_sid = NULL, $brand_sid = NULL, $start_date, $end_date, $keyword, $hired_status = null, $check_hired_date = false, $count, $all, $limit = NULL, $offset = NULL)
    {
        //        if ($company_sid != NULL || $brand_sid != NULL) {
//            if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
//                $companies = $this->get_brand_companies($brand_sid);
//                $company_sids = array();
//
//                foreach ($companies as $company) {
//                    $company_sids[] = $company['company_sid'];
//                }
//
////                $start_date = '2015-01-01 00:00:00';
//            }
//
//            if ($brand_sid == NULL) { // always keep this where at the top to avoid conflict error with other queries
//                $this->db->where('portal_job_applications.employer_sid', $company_sid);
//            } else {
//                if (!empty($company_sids)) {
//                    $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
//                } else {
//                    return array();
//                }
//            }
//        }
        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid != NULL)
                $company_sids[] = $company_sid;
            else {
                $oems = $this->get_brand_companies($brand_sid);
                if (sizeof($oems) >= 1) {
                    foreach ($oems as $oem) {
                        $company_sids[] = $oem['company_sid'];
                    }
                }
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('portal_applicant_jobs_list.company_sid', $company_sids);
            }
        }
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.company_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');

        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
        // $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('portal_job_applications.first_name', $keyword);
                $this->db->or_like('portal_job_applications.last_name', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', $keywrd);
                    $this->db->or_like('portal_job_applications.last_name', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        if ($hired_status != null) {
            $this->db->where('portal_job_applications.hired_status', $hired_status);
        }

        if ($check_hired_date == true) {
            //            if ($start_date != null && $end_date != null) {
//                $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
//            }
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date <=', $end_date);
            }
        } else {
            //            if ($start_date != null && $end_date != null) {
//                $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_date)) . '"');
//            }
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
            }
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('date_applied', 'DESC');

        if ($count) {
            $applications = $this->db->get('portal_applicant_jobs_list')->num_rows();
            if (sizeof($company_sids) < 1 && !$all)
                $applications = 0;
        } else {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
            foreach ($applications as $key => $application) {
                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);

                $company_det = get_company_details($application['company_sid']);
                if (isset($company_det['CompanyName'])) {
                    $company_name = $company_det['CompanyName'];
                } else {
                    $company_name = '';
                }
                $applications[$key]['CompanyName'] = $company_name;
            }
            if (sizeof($company_sids) < 1 && !$all)
                $applications = array();
        }

        return $applications;
    }

    function GetAllJobsCompanySpecific($company_sid = NULL, $brand_sid = NULL, $keywords = '', $count, $all, $limit = null, $offset = null)
    {

        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
                $companies = $this->get_brand_companies($brand_sid);

                foreach ($companies as $company) {
                    $company_sids[] = $company['company_sid'];
                }
            }

            if ($brand_sid == NULL) {
                $company_sids[] = $company_sid;
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('user_sid', $company_sids);
            }
        }
        $this->db->select('*');

        if (!empty($keywords)) {
            $this->db->like('Title', $keywords);
        }
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count) {
            $count = $this->db->get('portal_job_listings')->num_rows();
            if (sizeof($company_sids) < 1 && !$all) {
                $count = 0;
            }
            return $count;
        } else {
            $this->db->order_by('portal_job_listings.activation_date', 'DESC');
            $jobs = $this->db->get('portal_job_listings')->result_array();
            $i = 0;

            foreach ($jobs as $job) {
                $company_sid = $jobs[$i]['user_sid'];

                $company_det = get_company_details($company_sid);
                if (isset($company_det['CompanyName'])) {
                    $company_name = $company_det['CompanyName'];
                } else {
                    $company_name = '';
                }

                $jobs[$i]['CompanyName'] = $company_name;
                $i++;
            }

            if (sizeof($company_sids) < 1 && !$all) {
                $jobs = array();
            }
            return $jobs;
        }
    }

    function GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid = NULL, $brand_sid = NULL, $job_sid, $hired_status = null)
    {

        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
                $companies = $this->get_brand_companies($brand_sid);

                foreach ($companies as $company) {
                    $company_sids[] = $company['company_sid'];
                }
            }

            if ($brand_sid == NULL) {
                $company_sids[] = $company_sid;
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
            }
        }

        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_applications.sid as pja_sid');
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
        $this->db->select('portal_applicant_jobs_list.*');

        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');

        if ($hired_status != null) {
            $this->db->where('portal_job_applications.hired_status', $hired_status);
        }

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
        $i = 0;

        foreach ($applications as $application) {
            $company_sid = $applications[$i]['employer_sid'];
            $company_det = get_company_details($company_sid);
            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }
            $applications[$i]['CompanyName'] = $company_name;
            $i++;
        }

        return $applications;
    }

    function GetAllJobCategoriesWhereApplicantsAreHired($company_sid = NULL, $brand_sid = NULL, $all, $count, $limit = NULL, $offset = NULL)
    {

        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
                $companies = $this->get_brand_companies($brand_sid);

                foreach ($companies as $company) {
                    $company_sids[] = $company['company_sid'];
                }
            }

            if ($brand_sid == NULL) {
                $company_sids[] = $company_sid;
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
            }
        }

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
        $this->db->select('portal_job_listings.JobCategory');

        $this->db->where('portal_job_applications.hired_status', 1);

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }
        if (!$count) {
            $applicants = $this->db->get('portal_job_applications')->result_array();


            $csCategories = '';

            foreach ($applicants as $applicant) {
                $csCategories .= $applicant['JobCategory'] . ',';
            }

            $csCategories = substr($csCategories, 0, strlen($csCategories) - 1);
            $csCategories = explode(',', $csCategories);
            $categories = array();
            $categoriesHireCount = array();
            foreach ($csCategories as $category) {
                $this->db->select('*');
                $this->db->where('sid', $category);
                $category_info = $this->db->get('listing_field_list')->result_array();

                if (!empty($category_info)) {
                    $category_info = $category_info[0];

                    if (!in_array($category_info['value'], $categories)) {
                        $categories[] = $category_info['value'];
                        $categoriesHireCount[$category_info['value']] = 1;
                    } else {
                        $count = $categoriesHireCount[$category_info['value']];
                        $categoriesHireCount[$category_info['value']] = $count + 1;
                    }
                }
            }

            $myReturn = array();

            foreach ($categories as $category) {
                $myReturn[] = array('category' => $category, 'count' => $categoriesHireCount[$category]);
            }

            rsort($myReturn, 1);
            if (sizeof($company_sids) < 1 && !$all) {
                $myReturn = array();
            }
            return $myReturn;
        } else {
            $count = $this->db->get('portal_job_applications')->num_rows();
            if (sizeof($company_sids) < 1 && !$all) {
                $count = 0;
            }
            return $count;
        }

    }

    function GetAllApplicantsOnboarding($company_sid = NULL, $brand_sid = NULL, $start_date, $end_date, $keyword, $check_hired_date = false, $count, $all, $limit = NULL, $offset = NULL)
    {
        //        if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
//            $companies = $this->get_brand_companies($brand_sid);
//            $company_sids = array();
//
//            foreach ($companies as $company) {
//                $company_sids[] = $company['company_sid'];
//            }
//        }
//
//        if ($brand_sid == NULL) {
//            $this->db->where('users.parent_sid', $company_sid);
//            $this->db->where('portal_job_applications.employer_sid', $company_sid);
//        } else {
//            if (!empty($company_sids)) {
//                $this->db->where_in('users.parent_sid', $company_sids);
//                $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
//            } else {
//                return array();
//            }
//        }
        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid != NULL)
                $company_sids[] = $company_sid;
            else {
                $oems = $this->get_brand_companies($brand_sid);
                if (sizeof($oems) >= 1) {
                    foreach ($oems as $oem) {
                        $company_sids[] = $oem['company_sid'];
                    }
                }
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('portal_job_applications.employer_sid', $company_sids);
            }
        }

        $this->db->select('users.username');
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
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('users.CompanyName');

        $this->db->select('portal_job_listings.Title');

        $this->db->where('users.is_executive_admin', 0);
        $this->db->where('users.applicant_sid <>', NULL);

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('portal_job_applications.first_name', $keyword);
                $this->db->or_like('portal_job_applications.last_name', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', $keywrd);
                    $this->db->or_like('portal_job_applications.last_name', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        $this->db->group_start();
        $this->db->where('users.username', '');
        $this->db->or_where('users.username', NULL);
        $this->db->group_end();

        if ($check_hired_date == true) {
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.hired_date <=', $end_date);
            }
        } else {
            //            if ($start_date != null && $end_date != null) {
//                $this->db->where('portal_job_applications.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
//            }
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_job_applications.date_applied >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_applications.date_applied <=', $end_date);
            }
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'users.sid = portal_job_applications.hired_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');

        if ($count) {
            $applications = $this->db->get('users')->num_rows();
            if (sizeof($company_sids) < 1 && !$all)
                $applications = 0;
        } else {
            $this->db->order_by('hired_date', 'DESC');
            $applications = $this->db->get('users')->result_array();
            $i = 0;
            foreach ($applications as $application) {
                $applications[$i]['Title'] = get_job_title($application['job_sid']);
                $i++;
            }
            if (sizeof($company_sids) < 1 && !$all)
                $applications = array();
        }

        return $applications;
    }

    function get_all_jobs_views_applicants_count($company_sid = NULL, $brand_sid = NULL, $all, $count, $limit = null, $offset = null)
    {
        $company_sids = array();
        if ($company_sid != NULL || $brand_sid != NULL) {
            if ($company_sid != NULL)
                $company_sids[] = $company_sid;
            else {
                $oems = $this->get_brand_companies($brand_sid);
                if (sizeof($oems) >= 1) {
                    foreach ($oems as $oem) {
                        $company_sids[] = $oem['company_sid'];
                    }
                }
            }
            if (sizeof($company_sids) >= 1) {
                $this->db->where_in('user_sid', $company_sids);
            }
        }
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->select('*');
        // $this->db->select('portal_job_listings.Location_City');
        $this->db->where('active <> ', 2);
        $this->db->order_by('sid', 'DESC');
        if ($count) {
            $return_data = $this->db->get('portal_job_listings')->num_rows();
            if (sizeof($company_sids) < 1 && !$all)
                $return_data = 0;
        } else {
            $return_data = $this->db->get('portal_job_listings')->result_array();
            if (!empty($return_data)) {
                foreach ($return_data as $key => $row) {
                    $job_sid = $row['sid'];
                    $this->db->select('sid');
                    $this->db->where('job_sid', $job_sid);
                    $applicants = $this->db->get('portal_applicant_jobs_list')->num_rows();
                    $return_data[$key]['applicant_count'] = $applicants;
                    $company_sid = $return_data[$key]['user_sid'];
                    $company_det = get_company_details($company_sid);
                    if (isset($company_det['CompanyName'])) {
                        $company_name = $company_det['CompanyName'];
                    } else {
                        $company_name = '';
                    }
                    $return_data[$key]['company_title'] = $company_name;
                }
            }
            if (sizeof($company_sids) < 1 && !$all)
                $return_data = array();
        }
        return $return_data;
    }

    function get_all_oem_brands()
    {
        $this->db->select('sid, oem_brand_name');
        $this->db->where('brand_status', 'active');
        return $this->db->get('oem_brands')->result_array();
    }

    function get_brand_companies($brand_sid)
    {
        $this->db->select('distinct(company_sid)');
        $this->db->where('oem_brand_sid', $brand_sid);
        return $this->db->get('oem_brands_companies')->result_array();
    }

    function get_brand_companies_with_full_details($brand_sid)
    {
        $brand_company_sids = array();
        $brand_companies = $this->get_brand_companies($brand_sid);

        foreach ($brand_companies as $company) {
            $brand_company_sids[] = $company['company_sid'];
        }

        if (!empty($brand_company_sids)) {
            $this->db->select('*');
            $this->db->where('sid IN ( ' . implode(',', $brand_company_sids) . ' ) ');
            $this->db->where('career_page_type', 'standard_career_site');
            return $this->db->get('users')->result_array();
        } else {
            return array();
        }
    }

    //**** references function ****//
    function get_references($company_sid = NULL, $brand_sid = NULL)
    {
        if ($company_sid == NULL) { // create array beforehand in case of brand selection to avoid errors between where conditions
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();
            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
        }

        if ($brand_sid == NULL) {
            $this->db->where('company_sid', $company_sid);
        } else {
            if (!empty($company_sids)) {
                $this->db->where_in('company_sid', $company_sids);
            } else {
                return array();
            }
        }
        $this->db->select('*');
        $this->db->order_by('sid', 'DESC');

        $i = 0;
        $references = $this->db->get('reference_checks')->result_array();
        foreach ($references as $reference) {
            $company_det = get_company_details($reference['company_sid']);

            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }
            $references[$i]['CompanyName'] = $company_name;

            $sid = $reference['user_sid'];
            $type = $reference['users_type'];
            $this->db->select('first_name, last_name');
            $this->db->where('sid', $sid);

            if ($type == 'applicant') { // get applicant name
                $user = $this->db->get('portal_job_applications')->result_array();
            } else if ($type == 'employee') { // get employee name
                $user = $this->db->get('users')->result_array();
            }

            if (isset($user[0])) {
                $references[$i]['user_name'] = $user[0]['first_name'] . ' ' . $user[0]['last_name'];
            } else {
                $references[$i]['user_name'] = '';
            }

            $i++;
        }
        return $references;
    }

    //**** references function ****//

    function get_applicants_status($company_sid = NULL, $brand_sid = NULL, $start_date = NULL, $end_date = NULL)
    {
        if ($company_sid == NULL) {
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();

            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
        }

        $this->db->select('portal_job_applications.sid as pja_sid');
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


        $this->db->select('portal_applicant_jobs_list.*');

        if ($brand_sid == NULL) {
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        } else {
            if (!empty($company_sids)) {
                $this->db->where_in('portal_applicant_jobs_list.company_sid', $company_sids);
            } else {
                return array();
            }
        }

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');

        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();


        $have_status = $this->check_company_status($company_sid);

        foreach ($applications as $key => $application) {
            $applications[$key]['Title'] = get_job_title($application['job_sid']);

            $company_sid = $application['employer_sid'];
            $company_det = get_company_details($company_sid);
            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }
            $applications[$key]['CompanyName'] = $company_name;

            if ($have_status == true) {
                $status = $this->get_application_status($application['status_sid']);
                if (!empty($status)) {
                    $applications[$key]['status_name'] = $status[0]['name'];
                    $applications[$key]['status_css_class'] = $status[0]['css_class'];
                    $applications[$key]['status_text_css_class'] = $status[0]['text_css_class'];
                }
            }

        }

        return $applications;
    }

    function check_company_status($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $records = $this->db->get('application_status')->result_array();

        if (sizeof($records) <= 0) {
            return false; // records do not exist
        } else {
            return true; // records exist
        }
    }

    function get_application_status($status_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $status_sid);
        return $this->db->get('application_status')->result_array();
    }

    function get_candidate_offers($company_sid = NULL, $brand_sid = NULL, $start_date = NULL, $end_date = NULL, $all, $keyword = 'all')
    {
        if (!$all) {
            if ($company_sid == NULL) {
                $companies = $this->get_brand_companies($brand_sid);
                $company_sids = array();

                foreach ($companies as $company) {
                    $company_sids[] = $company['company_sid'];
                }
            }

            if ($brand_sid == NULL) {
                $this->db->where('parent_sid', $company_sid);
            } else {
                if (!empty($company_sids)) {
                    $this->db->where_in('parent_sid', $company_sids);
                } else {
                    return array();
                }
            }
        }

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('first_name', $keyword);
                $this->db->or_like('last_name', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('first_name', $keywrd);
                    $this->db->or_like('last_name', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        $this->db->select('first_name, last_name, registration_date, CompanyName, access_level, email, applicant_sid');

        $this->db->where('username', NULL);
        $this->db->where('password', NULL);
        $this->db->where('active', 0);
        $this->db->where('applicant_sid <>', NULL);

        if ($start_date != null && $end_date != null) {
            $this->db->where('registration_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $candidates = $this->db->get('users')->result_array();

        // get job title
        foreach ($candidates as $key => $candidate) {
            $applicant_sid = $candidate['applicant_sid'];

            $this->db->select('job_sid');
            $this->db->where('portal_job_applications_sid', $applicant_sid);
            $this->db->select('portal_job_listings.Location_State');
            $this->db->select('portal_job_listings.Location_City');
            $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
            $job_sid = $this->db->get('portal_applicant_jobs_list')->result_array();
            // $candidates[$key]['Location_State'] =$job_sid[0]['Location_City'];
            // $candidates[$key]['Location_City'] =$job_sid[0]['Location_State'];
            if (isset($job_sid[0]['job_sid'])) {
                $candidates[$key]['Location_State'] = $job_sid[0]['Location_State'];
                $candidates[$key]['Location_City'] = $job_sid[0]['Location_City'];
                $job_sid = $job_sid[0]['job_sid'];
                $candidates[$key]['job_title'] = get_job_title($job_sid);
            } else {
                $candidates[$key]['job_title'] = 'Job Deleted';
            }

        }
        return $candidates;
    }

    function getApplicantReviewsCount($applicant_sid)
    {
        $result = $this->db->get_where('portal_applicant_rating', array('applicant_job_sid' => $applicant_sid));
        return $result->num_rows();
    }

    function getApplicantAverageRating($app_id, $users_type = 'applicant')
    {
        $result = $this->db->get_where('portal_applicant_rating', array(
            'applicant_job_sid' => $app_id
        ));
        $rows = $result->num_rows();
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $data = $this->db->get('portal_applicant_rating')->result_array();
            return round($data[0]['rating'] / $rows, 2);
        }
    }

    function get_applicants_by_source($company_sid, $applicant_source, $start_date, $end_date)
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->select('portal_job_listings.Title');

        if ($company_sid > 0) {
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        }
        if ($applicant_source != 'all') {
            if ($applicant_source == 'career_website') {
                $this->db->group_start();
                $this->db->where('portal_applicant_jobs_list.applicant_source', $applicant_source);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', NULL);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', '');
                $this->db->group_end();

            } else {
                $this->db->where('portal_applicant_jobs_list.applicant_source', $applicant_source);
            }
        }

        if ($start_date != '' && $start_date != null && $end_date != '' && $end_date != null) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $applicants = $this->db->get('portal_applicant_jobs_list')->result_array();

        foreach ($applicants as $key => $applicant) {
            $applicants[$key]['job_title'] = $this->get_job_title_by_type($applicant['job_sid'], $applicant['applicant_type'], $applicant['desired_job_title']);
        }

        return $applicants;

    }

    function get_applicant_interview_scores($company_sid)
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
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

        $this->db->select('portal_job_listings.Title as job_title');

        $this->db->select('interview_questionnaire_score.star_rating');
        $this->db->select('interview_questionnaire_score.candidate_score');
        $this->db->select('interview_questionnaire_score.job_relevancy_score');
        $this->db->select('interview_questionnaire_score.candidate_overall_score');
        $this->db->select('interview_questionnaire_score.job_relevancy_overall_score');
        $this->db->select('interview_questionnaire_score.created_date');
        $this->db->select('interview_questionnaire_score.company_sid');
        $this->db->select('interview_questionnaire_score.employer_sid');
        $this->db->select('interview_questionnaire_score.candidate_sid');

        $this->db->select('portal_applicant_jobs_list.*');

        $this->db->select('users.first_name as conducted_by_first_name');
        $this->db->select('users.last_name as conducted_by_last_name');
        $this->db->select('users.job_title as conducted_by_job_title');

        $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
        $this->db->where('interview_questionnaire_score.candidate_type', 'applicant');

        $this->db->join('portal_job_applications', 'portal_job_applications.sid = interview_questionnaire_score.candidate_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = interview_questionnaire_score.job_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = interview_questionnaire_score.candidate_sid', 'left');
        $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid', 'left');

        $applicants = $this->db->get('interview_questionnaire_score')->result_array();

        foreach ($applicants as $key => $applicant) {
            $applicants[$key]['job_title'] = $this->get_job_title_by_type($applicant['job_sid'], $applicant['applicant_type'], $applicant['desired_job_title']);
        }

        return $applicants;
    }

    function get_applicant_source($company_sid = NULL, $brand_sid = NULL, $search = '', $search2 = '')
    {
        if ($company_sid == NULL) {
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();

            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
        }

        if ($brand_sid == NULL) {
            if ($company_sid != 'all') {
                $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
            }
        } else {
            if (!empty($company_sids)) {
                $this->db->where_in('portal_applicant_jobs_list.company_sid', $company_sids);
            } else {
                return array();
            }
        }

        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_applicant_jobs_list.company_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');

        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('users.is_paid', 1);

        if ($search != '') {
            $this->db->where($search);
        }
        if ($search2 != '') {
            $this->db->where($search2);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('users', 'portal_applicant_jobs_list.company_sid = users.sid');
        $this->db->order_by('date_applied', 'DESC');
        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

        foreach ($applications as $key => $application) {
            $company_det = get_company_details($application['company_sid']);
            if (isset($company_det['CompanyName'])) {
                $company_name = $company_det['CompanyName'];
            } else {
                $company_name = '';
            }
            $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
            $applications[$key]['CompanyName'] = $company_name;
        }

        return $applications;
    }

    function get_applicant_origins($company_sid, $source = '', $start_date = '', $end_date = '', $keyword = '', $count, $per_page = NULL, $offset = NULL)
    {
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.company_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.main_referral');
        $this->db->select('portal_applicant_jobs_list.ip_address');

        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');

        $this->db->select('users.CompanyName');

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('portal_job_applications.first_name', $keyword);
                $this->db->or_like('portal_job_applications.last_name', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', $keywrd);
                    $this->db->or_like('portal_job_applications.last_name', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        if ($source != 'all') {
            if ($source == 'career_website') {
                $this->db->group_start();
                $this->db->like('portal_applicant_jobs_list.applicant_source', $source);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', NULL);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', '');
                $this->db->group_end();
            } else {
                $this->db->like('portal_applicant_jobs_list.applicant_source', $source);
            }
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        if ($company_sid != 'all') {
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        }
        if ($per_page !== null && $offset !== null) {
            $this->db->limit($per_page, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('users', 'portal_applicant_jobs_list.company_sid = users.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid =portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        //        $applications = array();


        if ($count) {
            $this->db->from('portal_applicant_jobs_list');
            $applications = $this->db->count_all_results();
        } else {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            foreach ($applications as $key => $application) {
                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
                if (empty($application['applicant_source']) || is_null($application['applicant_source']) || $application['applicant_source'] == 'career_website') {
                    $applications[$key]['applicant_source'] = 'Career Website';
                }
            }
        }

        return $applications;
    }

    function get_stats_by_source($search = array())
    {
        if ($search['date_option'] == 'daily') {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 23:59:59');
        } else {
            $start = DateTime::createFromFormat('m-d-Y', $search['startdate'])->format('Y-m-d 00:00:00');
            $end = DateTime::createFromFormat('m-d-Y', $search['enddate'])->format('Y-m-d 23:59:59');
        }
        $date_string = "portal_applicant_jobs_list.date_applied between '" . $start . "' and '" . $end . "'";
        $this->db->select('portal_applicant_jobs_list.company_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->where($date_string);
        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_total_job_applications($start_date, $end_date, $city = NULL, $state = NULL)
    {
        if ($city != NULL) {
            $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
            $this->db->where('portal_job_applications.city', $city);
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
            $this->db->from('portal_applicant_jobs_list');
            return $this->db->count_all_results();
        } elseif ($state != NULL) {
            $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
            $this->db->where('portal_job_applications.state', $state);
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
            $this->db->from('portal_applicant_jobs_list');
            return $this->db->count_all_results();
        } else {
            $this->db->select('portal_applicant_jobs_list.sid');
            $this->db->select('portal_applicant_jobs_list.portal_job_applications_sid');
            $this->db->select('portal_applicant_jobs_list.company_sid');
            $this->db->select('portal_applicant_jobs_list.job_sid');
            $this->db->select('portal_applicant_jobs_list.date_applied');
            $this->db->select('portal_applicant_jobs_list.ip_address');
            $this->db->select('portal_applicant_jobs_list.applicant_source');
            $this->db->select('portal_applicant_jobs_list.applicant_type');
            $this->db->select('portal_applicant_jobs_list.main_referral');
            $this->db->select('portal_applicant_jobs_list.user_agent');
            $this->db->select('portal_applicant_jobs_list.desired_job_title');
            $this->db->select('portal_job_applications.first_name');
            $this->db->select('portal_job_applications.last_name');
            $this->db->select('portal_job_applications.email');
            $this->db->select('portal_job_applications.city');
            $this->db->select('portal_job_applications.state');
            $this->db->select('portal_job_applications.applicant_type');
            $this->db->select('states.state_name');
            $this->db->select('users.CompanyName');
            $this->db->select('portal_job_listings.Location_State');
            $this->db->select('portal_job_listings.Location_City');
            $this->db->where('portal_job_applications.email not regexp "@mail.ru"');

            $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
            $this->db->join('states', 'states.sid = portal_job_applications.state', 'left');
            $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
            $this->db->join('users', 'portal_applicant_jobs_list.company_sid = users.sid', 'left');


            $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            if (!empty($applications)) {
                foreach ($applications as $key => $application) {
                    $applications[$key]['job_title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
                }
            }

            return $applications;
        }
    }

    function get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date = null, $end_date = null, $oem, $count_only = false, $limit = null, $offset = null, $source = 'all')
    {
        $c_sids = array();
        if ($company_sid != 'all' || $oem != 'all') {
            if ($company_sid != 'all') {
                $c_sids[] = $company_sid;
            } else {
                $oems = $this->get_brand_companies($oem);
                if (sizeof($oems) >= 1) {
                    foreach ($oems as $oem) {
                        $c_sids[] = $oem['company_sid'];
                    }
                }
            }
            if (sizeof($c_sids) >= 1) {
                $this->db->where_in('portal_applicant_jobs_list.company_sid', $c_sids);
            }
        }

        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');

        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.main_referral');
        $this->db->select('portal_applicant_jobs_list.ip_address');


        $this->db->select('application_status.css_class');

        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);


        if ($source != 'all') {
            if ($source == 'career_website') {
                $this->db->group_start();
                $this->db->like('portal_applicant_jobs_list.applicant_source', $source);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', NULL);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', '');
                $this->db->group_end();
            } else if ($source == 'others') {
                $this->db->group_start();
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'indeed');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'automotosocial');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'jobs2careers');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'ziprecruiter');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'glassdoor');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'juju');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'career_website');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'intranet.dev');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'career_builder');

                $this->db->where('portal_applicant_jobs_list.applicant_source <>', NULL);
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', '');
                $this->db->group_end();
            } else {
                $this->db->like('portal_applicant_jobs_list.applicant_source', $source);
            }
        }

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('portal_job_applications.first_name', $keyword);
                $this->db->or_like('portal_job_applications.last_name', $keyword);
                $this->db->or_like('portal_job_applications.email', $keyword);
                $this->db->or_like('portal_job_applications.phone_number', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', $keywrd);
                    $this->db->or_like('portal_job_applications.last_name', $keywrd);
                    $this->db->or_like('portal_job_applications.email', $keywrd);
                    $this->db->or_like('portal_job_applications.phone_number', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $check_jobs_exists)) {
            if (is_array($check_jobs_exists)) {
                $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
            } else {
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
        }
        //        if (!empty($job_sid) && $job_sid != 'all') {
//            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
//        }

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        if (!empty($applicant_type) && $applicant_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $applicant_type);
        }


        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            // $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('application_status', 'portal_applicant_jobs_list.status_sid = application_status.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');

        if ($count_only == false) {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            foreach ($applications as $key => $application) {
                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);

                $review_count = $this->getApplicantReviewsCount($application['applicant_sid']);
                $review_score = $this->getApplicantAverageRating($application['applicant_sid']);

                if ($review_score == '' || $review_score == NULL) {
                    $review_score = 0;
                }

                $applications[$key]['review_count'] = $review_count;
                $applications[$key]['review_score'] = $review_score;

                //**** get Company Names from users table ****//
                $company_sid = $application['employer_sid'];
                $company_det = get_company_details($company_sid);
                if (isset($company_det['CompanyName'])) {
                    $company_name = $company_det['CompanyName'];
                } else {
                    $company_name = '';
                }
                $applications[$key]['CompanyName'] = $company_name;

                //**** get interview quesionnaire score ****//
                if (!empty($company_sid) && $company_sid != 'all')
                    $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
                else {
                    $companies = $this->get_all_companies();
                    $company_sids = array();
                    foreach ($companies as $com) {
                        $company_sids[] = $com['sid'];
                    }
                    $this->db->where_in('interview_questionnaire_score.company_sid', $company_sids);
                }
                $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
                $this->db->select('users.first_name, users.last_name');
                $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
                $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
                $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');

                $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
                //**** get interview quesionnaire score ****//
            }

            return $applications;
        } else {
            $count = $this->db->count_all_results('portal_applicant_jobs_list');
            return $count;
        }
    }

    function get_company_statuses($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'ASC');

        $records_obj = $this->db->get('application_status');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_all_advanced_jobs($company_sid, $start_date = null, $end_date = null, $priority, $count, $limit = null, $offset = null)
    {
        //        if ($brand_sid == NULL) {
//            $this->db->where('user_sid', $company_sid);
//        }
//        else {
//            $companies = $this->get_brand_companies($brand_sid);
//            $company_sids = array();
//
//            foreach ($companies as $company) {
//                $company_sids[] = $company['company_sid'];
//            }
//
//            if (!empty($company_sids)) {
//                $this->db->where_in('user_sid', $company_sids);
//            } else {
//                return array();
//            }
//        }
        $this->db->select('activation_date,deactivation_date,Title,active,views,user_sid,sid');
        $this->db->select('Location_State');
        $this->db->select('Location_City');
        if ($priority == 'active') {
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_listings.activation_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_job_listings.activation_date >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_listings.activation_date <=', $end_date);
            }
            $this->db->where('active <> ', 2);
            $this->db->order_by('activation_date', 'DESC');
        } else {
            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_listings.deactivation_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_job_listings.deactivation_date >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_job_listings.deactivation_date <=', $end_date);
            }
            $this->db->order_by('deactivation_date', 'DESC');
            $this->db->where('active', 0);
        }

        if ($company_sid != 'all') {
            $this->db->where('user_sid', $company_sid);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if (!$count) {
            $return_data = $this->db->get('portal_job_listings')->result_array();
            if (!empty($return_data)) {
                foreach ($return_data as $key => $row) {
                    $job_sid = $row['sid'];
                    $this->db->select('sid');
                    $this->db->where('job_sid', $job_sid);
                    $applicants = $this->db->get('portal_applicant_jobs_list')->num_rows();
                    $return_data[$key]['applicant_count'] = $applicants;

                    $company_sid = $return_data[$key]['user_sid'];
                    $company_det = get_company_details($company_sid);
                    if (isset($company_det['CompanyName'])) {
                        $company_name = $company_det['CompanyName'];
                    } else {
                        $company_name = '';
                    }
                    $return_data[$key]['company_title'] = $company_name;
                }
            } else {
                $return_data = array();
            }
        } else {
            $return_data = $this->db->get('portal_job_listings')->num_rows();
        }

        return $return_data;
    }


    /**
     * Fetch copied applicants report
     * Created on: 22-08-2019
     *
     * @param $startDate
     * @param $endDate
     * @param $page
     * @param $limit
     *
     * @return Array|Bool
     */
    function fetchCopyApplicantData($startDate, $endDate, $page, $limit = 100)
    {
        //
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $result = $this->db
            ->select('
            copy_applicant_tracking.token, 
            copy_applicant_tracking.job_title, 
            from_user.CompanyName as from_company_name,
            to_user.CompanyName as to_company_name,
            copy_applicant_tracking.created_at
        ')
            ->from('copy_applicant_tracking')
            ->where('DATE_FORMAT(copy_applicant_tracking.created_at, "%m-%d-%Y") BETWEEN "' . ($startDate) . '" AND "' . ($endDate) . '"', NULL)
            ->group_by('copy_applicant_tracking.token')
            ->order_by('copy_applicant_tracking.sid', 'DESC')
            ->limit($limit, $start)
            ->join('users as to_user', 'to_user.sid = copy_applicant_tracking.to_company_id', 'inner')
            ->join('users as from_user', 'from_user.sid = copy_applicant_tracking.from_company_id', 'inner')
            ->get();
        //
        $result_arr = $result->result_array();
        $result = $result->free_result();

        //
        if (!sizeof($result_arr))
            return false;
        //
        if ($page != 1)
            return $result_arr;
        //
        $totalRecords = $this->db
            ->from('copy_applicant_tracking')
            ->where('DATE_FORMAT(copy_applicant_tracking.created_at, "%m-%d-%Y") BETWEEN "' . ($startDate) . '" AND "' . ($endDate) . '"', NULL)
            ->group_by('copy_applicant_tracking.token')
            ->count_all_results();
        // _e($result_arr, true, true);
        //
        return array('Data' => $result_arr, 'Total' => $totalRecords);
    }

    /**
     * Fetch copied applicants details
     * Created on: 22-08-2019
     *
     * @param $token
     * @param $page
     * @param $limit
     *
     * @return Array|Bool
     */
    function fetchCopyApplicantDetail($token, $page = 1, $limit = 100)
    {
        //
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $result = $this->db
            ->select('
            copy_applicant_tracking.job_title,
            copy_applicant_tracking.copied_applicants,
            copy_applicant_tracking.failed_applicants,
            copy_applicant_tracking.existed_applicants
        ')
            ->from('copy_applicant_tracking')
            ->order_by('copy_applicant_tracking.sid', 'DESC')
            // ->limit($limit, $start)
            ->where('copy_applicant_tracking.token', $token)
            ->join('users as to_user', 'to_user.sid = copy_applicant_tracking.to_company_id', 'inner')
            ->join('users as from_user', 'from_user.sid = copy_applicant_tracking.from_company_id', 'inner')
            ->get();
        //
        $result_arr = $result->result_array();
        $result = $result->free_result();

        //
        if (!sizeof($result_arr))
            return false;
        //
        return $result_arr;
        // if($page != 1) return $result_arr;
        // //
        // $totalRecords = $this->db
        // ->from('copy_applicant_tracking')
        // ->where('copy_applicant_tracking.token', $token)
        // ->group_by('copy_applicant_tracking.token')
        // ->count_all_results();
        // // _e($result_arr, true, true);
        // //
        // return array( 'Data' => $result_arr, 'Total' => $totalRecords );
    }

    function get_applicant_ai_report($company_sid, $keyword = '', $start_date = '', $end_date = '', $status, $count, $per_page = NULL, $offset = NULL)
    {
        $this->db->select('portal_applicant_jobs_queue.sid');
        $this->db->select('portal_applicant_jobs_queue.created_at');
        $this->db->select('portal_applicant_jobs_queue.job_sid');
        $this->db->select('portal_applicant_jobs_queue.company_sid');
        $this->db->select('portal_applicant_jobs_queue.status');
        $this->db->select('portal_applicant_jobs_queue.attempts');
        $this->db->select('portal_applicant_jobs_queue.failed_message');
        $this->db->select('portal_applicant_jobs_queue.portal_applicant_job_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Title');

        $this->db->select('users.CompanyName');

        if (!empty($keyword) && $keyword != 'all') {
            $multiple_keywords = explode(' ', $keyword);

            if (count($multiple_keywords) == 1) {
                $this->db->group_start();
                $this->db->like('portal_job_applications.first_name', $keyword);
                $this->db->or_like('portal_job_applications.last_name', $keyword);
                $this->db->group_end();
            } else {
                foreach ($multiple_keywords as $keywrd) {
                    $this->db->group_start();
                    $this->db->like('portal_job_applications.first_name', $keywrd);
                    $this->db->or_like('portal_job_applications.last_name', $keywrd);
                    $this->db->group_end();
                }
            }
        }

        if ($status != 'all') {
            $this->db->where('portal_applicant_jobs_queue.status', $status);
        } else {
            // $this->db->where('portal_applicant_jobs_queue.status <>', "queued");
        }

        if ($start_date && $end_date) {

            if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_queue.created_at BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
            } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_queue.created_at >=', $start_date);
            } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
                $this->db->where('portal_applicant_jobs_queue.created_at <=', $end_date);
            }
        }

        if ($company_sid != 'all') {
            $this->db->where('portal_applicant_jobs_queue.company_sid', $company_sid);
        }
        if ($per_page !== null && $offset !== null) {
            $this->db->limit($per_page, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_queue.portal_job_applications_sid = portal_job_applications.sid', 'inner');
        $this->db->join('users', 'portal_applicant_jobs_queue.company_sid = users.sid', 'inner');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_queue.job_sid', 'inner');
        $this->db->order_by('portal_applicant_jobs_queue.sid', 'DESC');
        //        $applications = array();


        if ($count) {
            $this->db->from('portal_applicant_jobs_queue');
            $applications = $this->db->count_all_results();
        } else {
            $applications = $this->db->get('portal_applicant_jobs_queue')->result_array();

            foreach ($applications as $key => $application) {
                //
                if (empty($application['applicant_source']) || is_null($application['applicant_source']) || $application['applicant_source'] == 'career_website') {
                    $applications[$key]['applicant_source'] = 'Career Website';
                }
            }
        }

        return $applications;
    }
}