<?php

class Reports_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function GetColumnNames($table, $exclude = array())
    {
        $columnNames = $this->db->list_fields($table);
        $columnNamesToReturn = array();

        if (!empty($exclude)) {
            foreach ($columnNames as $columnName) {
                if (!in_array($columnName, $exclude)) {
                    $columnNamesToReturn[] = $columnName;
                }
            }
        } else {
            $columnNamesToReturn = $columnNames;
        }

        return $columnNamesToReturn;
    }

    function GetColumnsInformation($table)
    {
        $columnDetails = $this->db->field_data($table);
        $myReturn = array();

        foreach ($columnDetails as $columnDetail) {
            $myReturn[$columnDetail->name] = (array) $columnDetail;
        }

        return $myReturn;
    }

    function BuildWhereClauseFromArray($where, $table)
    {
        $table_cols = $this->db->list_fields($table);
        $table_cols_detail = $this->GetColumnsInformation($table);

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                if ($value != '') {
                    $columnName = explode(' ', $key)[0];

                    if (count(explode(' ', $key)) > 1) {
                        $columnOperator = explode(' ', $key)[1];
                    }

                    $columnType = '';

                    if ($columnName != 'Title') {
                        $columnType = $table_cols_detail[$columnName]['type'];

                        switch ($columnType) {
                            case 'date':
                                if (in_array($columnName, $table_cols)) {
                                    $this->db->where('DATE(portal_job_applications.' . $columnName . ')' . ' ' . $columnOperator, $value);
                                }
                                break;
                            default:
                                if (in_array($columnName, $table_cols)) {
                                    $this->db->where('portal_job_applications.' . $key, $value);
                                } elseif ($columnName == 'Title') {
                                    if (intval($value) != 0) {
                                        $this->db->where('portal_job_applications.job_sid', $value);
                                    }
                                }
                                break;
                        }
                    } elseif ($columnName == 'Title') {
                        if (intval($value) != 0) {
                            $this->db->where('portal_job_applications.job_sid', $value);
                        }
                    }
                }
            }
        }
    }

    function GetAllApplicants($company_sid, $employer_sid, $columns = array(), $search = '', $search2 = '', $limit = 0, $start = 1)
    {
        $applicants_not_hired_sids = $this->get_not_hired_applicants($company_sid);
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        //$this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_listings_visibility.job_sid');
        $this->db->select('portal_job_listings.Title');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where_in('portal_applicant_jobs_list.portal_job_applications_sid', $applicants_not_hired_sids);

        if ($search != '') {
            $this->db->where($search);
        }
        if ($search2 != '') {
            $this->db->where($search2);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        //$this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $applications = $this->db->get('portal_job_listings_visibility')->result_array();

        foreach ($applications as $key => $application) {
            $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
            $review_count = $this->getApplicantReviewsCount($application['applicant_sid']);
            $review_score = $this->getApplicantAverageRating($application['applicant_sid']);

            if ($review_score == '' || $review_score == NULL) {
                $review_score = 0;
            }

            $applications[$key]['review_count'] = $review_count;
            $applications[$key]['review_score'] = $review_score;
            //**** get interview quesionnaire score ****//
            $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
            $this->db->select('users.first_name, users.last_name');
            $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
            $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
            $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
            $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
            $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
            //**** get interview quesionnaire score ****//
        }

        return $applications;
    }

    function GetAllApplicantsCompanySpecific($company_sid, $columns = array(), $search = '', $search2 = '', $limit = 0, $start = 1)
    {
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);

        if ($search != '') {
            $this->db->where($search);
        }
        if ($search2 != '') {
            $this->db->where($search2);
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('date_applied', 'DESC');
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
            //**** get interview quesionnaire score ****//
            $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
            $this->db->select('users.first_name, users.last_name');
            $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
            $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
            $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
            $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
            $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
            //**** get interview quesionnaire score ****//
        }

        return $applications;
    }

    function GetAllJobsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords = '', $limit = 0, $start = 1)
    {
        $this->db->select('portal_job_listings.*, portal_job_listings_visibility.job_sid');
        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords) && $keywords != 'all') {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        return $this->db->get('portal_job_listings_visibility')->result_array();
    }

    function GetAllJobsCompanySpecific($company_sid, $keywords = '', $limit = 0, $start = 1)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords) && $keywords != 'all') {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function GetAllHiredJobs($company_sid, $start_date = null, $end_date = null, $status = null)
    {
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_applicant_jobs_list.job_sid');

        if ($status) {
            $this->db->where('portal_job_applications.hired_status', 1);
            $this->db->where('portal_job_applications.hired_sid >', 0);
        }

        $this->db->where('portal_job_applications.employer_sid', $company_sid);

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_date)) . '"');
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function GetAllEventsByCompanyAndEmployer($company_sid, $employer_sid)
    {
        $this->db->select('portal_schedule_event.*');
        //$this->db->select('users.first_name as creator_first_name');
        //$this->db->select('users.last_name as creator_last_name');
        $this->db->select('portal_job_applications.first_name as applicant_first_name');
        $this->db->select('portal_job_applications.last_name as applicant_last_name');
        $this->db->where('portal_schedule_event.companys_sid', $company_sid);
        //        $this->db->where('portal_schedule_event.employers_sid', $employer_sid);
        $this->db->group_start();
        $this->db->where('portal_schedule_event.employers_sid', $employer_sid);
        $this->db->or_where('find_in_set (' . $employer_sid . ', portal_schedule_event.interviewer)');
        $this->db->group_end();
        $this->db->where('portal_schedule_event.category', 'interview');
        //$this->db->join('users', 'portal_schedule_event.employers_sid = users.sid', 'left');
        $this->db->join('portal_job_applications', 'portal_schedule_event.applicant_job_sid = portal_job_applications.sid', 'right');
        $this->db->order_by('portal_schedule_event.date', 'DESC');
        return $this->db->get('portal_schedule_event')->result_array();
    }

    function GetAllUsers($company_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('is_executive_admin', 0);
        return $this->db->get('users')->result_array();
    }

    function GetAllApplicantsBetween($company_sid, $start_date, $end_date, $keyword = 'all', $hired_status = null, $job_sid = 'all', $applicant_type, $applicant_status, $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.sid');
        //        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
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

        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $check_jobs_exists)) {
            if (is_array($check_jobs_exists)) {
                $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
            } else {
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
        }

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        if (!empty($applicant_type) && $applicant_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $applicant_type);
        }

        if ($hired_status != null) {
            $this->db->where('portal_job_applications.hired_status', $hired_status);
        }

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $this->db->group_by('portal_applicant_jobs_list.portal_job_applications_sid');

        if ($count_only == false) {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            foreach ($applications as $key => $application) {
                // Get City and State
                $this->db->select('portal_job_listings.Location_City');
                $this->db->select('portal_job_listings.Location_State');
                $this->db->where('sid', $application['job_sid']);

                $applications[$key] = array_merge(
                    $applications[$key],
                    $this->db->get('portal_job_listings')->row_array()
                );
                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
            }
            return $applications;
        } else {
            return $this->db->get('portal_applicant_jobs_list')->num_rows();
        }
    }

    function GetAllApplicantsBetweenPeriod($company_sid, $start_date, $end_date, $keyword = 'all', $hired_status = 0, $job_sid = 'all', $applicant_type, $applicant_status, $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_job_applications.sid');
        //        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
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

        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $check_jobs_exists)) {
            if (is_array($check_jobs_exists)) {
                $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
            } else {
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
        }

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        if (!empty($applicant_type) && $applicant_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $applicant_type);
        }

        $this->db->where('portal_job_applications.hired_status', $hired_status);

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');

        if ($count_only == false) {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            foreach ($applications as $key => $application) {
                // Get City and State
                $this->db->select('portal_job_listings.Location_City');
                $this->db->select('portal_job_listings.Location_State');
                $this->db->where('sid', $application['job_sid']);

                $applications[$key] = array_merge(
                    $applications[$key],
                    $this->db->get('portal_job_listings')->row_array()
                );

                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
            }
            return $applications;
        } else {
            return $this->db->count_all_results('portal_applicant_jobs_list');
        }
    }

    function GetAllApplicantsCompanyAndEmployerSpecific($company_sid, $employer_sid, $keywords = '', $limit = 0, $start = 1, $archived = 0)
    {
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

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        if (!empty($keywords)) {
            $this->db->like('portal_job_applications.first_name', $keywords);
            $this->db->or_like('portal_job_applications.last_name', $keywords);
            $this->db->or_like('portal_job_applications.email', $keywords);
        }

        $this->db->order_by('portal_job_applications.date_applied', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_job_applications.job_sid = portal_job_listings_visibility.job_sid', 'left');
        $jobApplicants = $this->db->get('portal_job_listings_visibility')->result_array();
        $talentAndManual = $this->GetTalentNetworkAndManualCandidates($company_sid);
        return array_merge($jobApplicants, $talentAndManual);
    }

    function GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $employer_sid, $job_sid, $hired_status = null)
    {
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
            return $this->db->get('portal_applicant_jobs_list')->result_array();
        } else {
            return array();
        }
    }

    function GetAllJobCategoriesWhereApplicantsAreHired($company_sid)
    {
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
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
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
        return $myReturn;
    }

    function GetAllApplicantsOnboarding($company_sid, $start_date, $end_date, $keyword = '', $check_hired_date = false)
    {
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
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('users.is_executive_admin', 0);
        $this->db->where('users.applicant_sid <>', NULL);
        $this->db->where('users.parent_sid', $company_sid);
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('users.username', '');
        $this->db->or_where('users.username', NULL);
        $this->db->group_end();

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

        if ($check_hired_date == true) {
            if ($start_date != null && $end_date != null) {
                $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
            }
        } else {
            if ($start_date != null && $end_date != null) {
                $this->db->where('portal_job_applications.date_applied BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
            }
        }

        $this->db->join('portal_job_applications', 'users.sid = portal_job_applications.hired_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        $this->db->order_by('hired_date', 'DESC');
        return $this->db->get('users')->result_array();
    }

    function get_all_jobs_views_applicants_count($company_sid)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active <> ', 2);
        $this->db->order_by('sid', 'DESC');
        $return_data = $this->db->get('portal_job_listings')->result_array();

        if (!empty($return_data)) {
            foreach ($return_data as $key => $row) {
                $job_sid = $row['sid'];
                $this->db->select('sid');
                $this->db->where('job_sid', $job_sid);
                $this->db->from('portal_applicant_jobs_list');
                $count = $this->db->count_all_results();
                //                $applicants = $this->db->get('portal_applicant_jobs_list')->num_rows();
                $return_data[$key]['applicant_count'] = $count;
            }
        }
        return $return_data;
    }

    //**** references function ****//
    function get_references($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $references = $this->db->get('reference_checks')->result_array();
        $i = 0;

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

    function get_applicants_status($company_sid = NULL, $start_date = NULL, $end_date = NULL)
    {
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
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();
        $have_status = $this->check_company_status($company_sid);

        foreach ($applications as $key => $application) {

            // Get City and State
            $this->db->select('portal_job_listings.Location_City');
            $this->db->select('portal_job_listings.Location_State');
            $this->db->where('sid', $application['job_sid']);

            $applications[$key] = array_merge(
                $applications[$key],
                $this->db->get('portal_job_listings')->row_array()
            );
            $applications[$key]['Title'] = get_job_title($application['job_sid']);

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

    function get_application_status($status_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $status_sid);
        return $this->db->get('application_status')->result_array();
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

    function get_candidate_offers($company_sid = NULL, $start_date = NULL, $end_date = NULL, $keyword = '')
    {
        $this->db->select('first_name, last_name, registration_date, CompanyName, access_level, email, applicant_sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username', NULL);
        $this->db->where('password', NULL);
        $this->db->where('active', 0);
        $this->db->where('applicant_sid <>', NULL);

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

        if ($start_date != null && $end_date != null) {
            $this->db->where('registration_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $candidates = $this->db->get('users')->result_array();

        foreach ($candidates as $key => $candidate) {
            $applicant_sid = $candidate['applicant_sid'];
            $this->db->select('job_sid');
            $this->db->where('portal_job_applications_sid', $applicant_sid);
            $job_sid = $this->db->get('portal_applicant_jobs_list')->result_array();


            if (isset($job_sid[0]['job_sid'])) {

                $job_sid = $job_sid[0]['job_sid'];
                // Get City and State
                $this->db->select('portal_job_listings.Location_City');
                $this->db->select('portal_job_listings.Location_State');
                $this->db->where('sid', $job_sid);
                $data = $this->db->get('portal_job_listings')->row_array();
                if (sizeof($data)) {
                    $candidates[$key] = array_merge(
                        $candidates[$key],
                        $data
                    );
                }
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

    function get_job_title_by_type($job_sid, $applicant_type, $desired_job_title)
    {
        $job_title = '';

        if ($applicant_type == 'Applicant') {
            $job_title = get_job_title($job_sid);
        } else if ($applicant_type == 'Talent Network' || $applicant_type == 'Imported Resume') {
            if ($desired_job_title != NULL && $desired_job_title != '') {
                $job_title = $desired_job_title . ' <br> (Provided from Talent Network)';
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

        return $job_title;
    }

    function get_applicants_by_source($company_sid, $applicant_source, $start_date, $end_date, $keyword = 'all', $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->select('portal_job_listings.Title');

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

        if ($company_sid > 0) {
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        }

        if ($applicant_source != 'all') {
            if ($applicant_source == 'career_website') {
                $this->db->group_start();
                $this->db->like('portal_applicant_jobs_list.applicant_source', $applicant_source);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', NULL);
                $this->db->or_where('portal_applicant_jobs_list.applicant_source', '');
                $this->db->group_end();
            } else if ($applicant_source == 'others') {
                $this->db->group_start();
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'indeed');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'automotosocial');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'jobs2careers');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'ziprecruiter');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'glassdoor');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'juju');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'career_website');
                $this->db->not_like('portal_applicant_jobs_list.applicant_source', 'intranet.dev');
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', NULL);
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', '');
                $this->db->group_end();
            } else {
                $this->db->like('portal_applicant_jobs_list.applicant_source', $applicant_source);
            }
        }

        if ($start_date != '' && $start_date != null && $end_date != '' && $end_date != null) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
        }

        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == false) {
            $applicants = $this->db->get('portal_applicant_jobs_list')->result_array();

            foreach ($applicants as $key => $applicant) {
                $applicants[$key]['job_title'] = $this->get_job_title_by_type($applicant['job_sid'], $applicant['applicant_type'], $applicant['desired_job_title']);
            }

            return $applicants;
        } else {
            $this->db->from('portal_applicant_jobs_list');
            return $this->db->count_all_results();
        }
    }

    function get_applicant_interview_scores($company_sid, $keyword = 'all', $start_date = NULL, $end_date = NULL, $count_only = false, $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $limit = null, $offset = null)
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
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');
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
            $this->db->limit($limit, $offset);
        }

        $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
        $this->db->where('interview_questionnaire_score.candidate_type', 'applicant');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = interview_questionnaire_score.candidate_sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = interview_questionnaire_score.job_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = interview_questionnaire_score.candidate_sid', 'left');
        $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid', 'left');

        if ($count_only == false) {
            $applicants = $this->db->get('interview_questionnaire_score')->result_array();

            foreach ($applicants as $key => $applicant) {
                $applicants[$key]['job_title'] = $this->get_job_title_by_type($applicant['job_sid'], $applicant['applicant_type'], $applicant['desired_job_title']);
            }
            return $applicants;
        } else {
            return $this->db->count_all_results('interview_questionnaire_score');
        }
    }

    function get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date = null, $end_date = null, $count_only = false, $limit = null, $offset = null, $source = 'all')
    {
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('application_status.css_class');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);


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

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        if (!empty($applicant_type) && $applicant_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $applicant_type);
        }

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
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', NULL);
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', '');
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

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid=portal_applicant_jobs_list.job_sid', 'left');
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
                $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
                $this->db->select('users.first_name, users.last_name');
                $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
                $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
                $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
                $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
                $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
            }

            return $applications;
        } else {
            return $this->db->count_all_results('portal_applicant_jobs_list');
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

    function GetSourceReportAllApplicants($company_sid, $employer_sid, $keyword, $job_sid, $applicant_type, $start_date = null, $end_date = null, $limit = 0, $start = 1)
    {
        $applicants_not_hired_sids = $this->get_not_hired_applicants($company_sid);
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        //$this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_listings_visibility.job_sid');
        $this->db->select('portal_job_listings.Title');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where_in('portal_applicant_jobs_list.portal_job_applications_sid', $applicants_not_hired_sids);

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

        if (!empty($job_sid) && $job_sid != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
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

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $applications = $this->db->get('portal_job_listings_visibility')->result_array();

        foreach ($applications as $key => $application) {
            $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
            $review_count = $this->getApplicantReviewsCount($application['applicant_sid']);
            $review_score = $this->getApplicantAverageRating($application['applicant_sid']);

            if ($review_score == '' || $review_score == NULL) {
                $review_score = 0;
            }

            $applications[$key]['review_count'] = $review_count;
            $applications[$key]['review_score'] = $review_score;
            $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
            $this->db->select('users.first_name, users.last_name');
            $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
            $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
            $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
            $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
            $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
        }

        return $applications;
    }

    function GetSourceReportAllApplicantsCompanySpecific($company_sid, $keyword, $job_sid, $applicant_type, $start_date = null, $end_date = null, $limit = null, $offset = null, $get_reviews = true, $count_only = false, $source = 'all', $applicant_status = 'all')
    {
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.questionnaire');
        $this->db->select('portal_applicant_jobs_list.score');
        $this->db->select('portal_applicant_jobs_list.passing_score');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', 0);
        $this->db->where('portal_job_applications.hired_status', 0);

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
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', NULL);
                $this->db->where('portal_applicant_jobs_list.applicant_source <>', '');
                $this->db->group_end();
            } else {
                $this->db->like('portal_applicant_jobs_list.applicant_source', $source);
            }
        }

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $check_jobs_exists)) {
            if (is_array($check_jobs_exists)) {
                $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
            } else {
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
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
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');

        if ($count_only == false) {
            $this->db->from('portal_applicant_jobs_list');
            $records_obj = $this->db->get();
            $applications = $records_obj->result_array();
            $records_obj->free_result();

            foreach ($applications as $key => $application) {
                $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);

                if ($get_reviews == true) {
                    $review_count = $this->getApplicantReviewsCount($application['applicant_sid']);
                    $review_score = $this->getApplicantAverageRating($application['applicant_sid']);

                    if ($review_score == '' || $review_score == NULL) {
                        $review_score = 0;
                    }

                    $applications[$key]['review_count'] = $review_count;
                    $applications[$key]['review_score'] = $review_score;

                    //**** get interview quesionnaire score ****//
                    $this->db->select('interview_questionnaire_score.candidate_score, interview_questionnaire_score.job_relevancy_score, interview_questionnaire_score.employer_sid');
                    $this->db->select('users.first_name, users.last_name');
                    $this->db->where('interview_questionnaire_score.job_sid', $application['job_sid']);
                    $this->db->where('interview_questionnaire_score.candidate_sid', $application['applicant_sid']);
                    $this->db->where('interview_questionnaire_score.company_sid', $company_sid);
                    $this->db->join('users', 'users.sid = interview_questionnaire_score.employer_sid');
                    $applications[$key]['scores'] = $this->db->get('interview_questionnaire_score')->result_array();
                    //**** get interview quesionnaire score ****//
                }

                if ($application['applicant_source'] == 'career_website') {
                    $applications[$key]['applicant_source'] = 'Career Website';
                }
            }
            return $applications;
        } else {
            $this->db->from('portal_applicant_jobs_list');
            return $this->db->count_all_results();
        }
    }

    function get_stats_by_source($search = array(), $company_sid)
    {
        if ($search['date_option'] == 'daily') {
            $start = date('Y-m-d 00:00:00');
            $end = date('Y-m-d 23:59:59');
        } else {
            $start = DateTime::createFromFormat('m-d-Y', $search['startdate'])->format('Y-m-d 00:00:00');
            $end = DateTime::createFromFormat('m-d-Y', $search['enddate'])->format('Y-m-d 23:59:59');
        }

        $date_string = "portal_applicant_jobs_list.date_applied between '" . $start . "' and '" . $end . "'";
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.applicant_source');
        $this->db->select('portal_applicant_jobs_list.ip_address');
        $this->db->select('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where($date_string);
        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_job_fairs($company_sid, $keyword = 'all', $start_date = null, $end_date = null, $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('portal_applicant_jobs_list.sid as application_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.status');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.talent_and_fair_data');
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.city as Location_City');
        $this->db->select('portal_job_applications.state as Location_State');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Job Fair');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

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

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        if ($count_only == false) {
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            return $applications;
        } else {
            return $this->db->count_all_results('portal_applicant_jobs_list');
        }
    }

    public function get_active_employers($company_sid, $start_date, $end_date)
    {
        $this->db->distinct();
        $this->db->select('employer_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        return $this->db->get('logged_in_activitiy_tracker')->result_array();
    }

    public function generate_activity_log_data_for_view($company_sid, $start_date, $end_date, $employer_sid = null)
    {
        //Handle Manual Employer Sid Feed
        if ($employer_sid == null) {
            $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);
        } else {
            $active_employers = array();
            $active_employers[] = array('employer_sid' => $employer_sid);
        }


        foreach ($active_employers as $key => $active_employer) {
            $this->db->select('*');
            $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $active_employer['employer_sid']);
            $this->db->order_by('action_timestamp', 'ASC');
            $this->db->order_by('employer_ip', 'ASC');
            $employer_logs = $this->db->get('logged_in_activitiy_tracker')->result_array();
            $logs_to_return = array();

            if (!empty($employer_logs)) {
                $first_log = $employer_logs[0];

                foreach ($employer_logs as $employer_log) {
                    $first_ip = $first_log['employer_ip'];
                    $first_ip_array_key = str_replace('.', '_', $first_ip);
                    $first_ip_array_key = str_replace('::1', '000_000_000_000', $first_ip_array_key);
                    $current_ip = $employer_log['employer_ip'];
                    $current_ip_array_key = str_replace('.', '_', $current_ip);
                    $current_ip_array_key = str_replace('::1', '000_000_000_000', $current_ip_array_key);

                    if ($first_ip_array_key == $current_ip_array_key) {
                        $logs_to_return[$first_ip_array_key][] = $employer_log;
                    } else {
                        $logs_to_return[$current_ip_array_key][] = $employer_log;
                        $first_log = $employer_log;
                    }
                }

                $active_employers[$key]['activity_logs'] = $logs_to_return;
            }
        }


        foreach ($active_employers as $emp_key => $employer) {
            $activity_logs = $employer['activity_logs'];
            $time_spent = 10;

            foreach ($activity_logs as $ip_log_key => $ip_logs) {
                $time_spent = 10;
                $first_activity = $ip_logs[0];

                foreach ($ip_logs as $act_key => $activity_log) {
                    $first_activity_datetime = new DateTime($first_activity['action_timestamp']);
                    $current_activity_datetime = new DateTime($activity_log['action_timestamp']);
                    $first_activity_ten_min_window = $first_activity_datetime->add(date_interval_create_from_date_string('10 min'));
                    $first_activity_ten_min_window_unix = $first_activity_ten_min_window->getTimestamp();
                    $current_activity_datetime_unix = $current_activity_datetime->getTimestamp();
                    $first_ip = $first_activity['employer_ip'];
                    $first_ip_array_key = str_replace('.', '_', $first_ip);
                    $first_ip_array_key = str_replace('::1', '000_000_000_000', $first_ip_array_key);
                    $current_ip = $activity_log['employer_ip'];
                    $current_ip_array_key = str_replace('.', '_', $current_ip);
                    $current_ip_array_key = str_replace('::1', '000_000_000_000', $current_ip_array_key);

                    if ($current_activity_datetime_unix < $first_activity_ten_min_window_unix) {
                    } else {
                        if ($first_ip_array_key == $current_ip_array_key) {
                            $time_spent = $time_spent + 10;
                        } else {
                            $time_spent = 10;
                        }

                        $first_activity = $activity_log;
                    }

                    $employer_activity_records['activities'][$ip_log_key]['act_details'] = $activity_log;
                    $employer_activity_records['activities'][$ip_log_key]['time_spent'] = $time_spent;
                    $employer_activity_records['activities'][$ip_log_key]['log_count'] = $act_key + 1;
                    $active_employers[$emp_key]['employer_name'] = $activity_log['employer_name'];
                }

                $time_spent = 10;
            }

            $active_employers[$emp_key]['activity_logs'] = $employer_activity_records['activities'];
        }


        foreach ($active_employers as $employer_key => $employer_logs) {
            $total_time_spent = 0;
            $total_logs = 0;

            foreach ($employer_logs['activity_logs'] as $ip_logs) {
                $total_time_spent += intval($ip_logs['time_spent']);
                $active_employers[$employer_key]['total_time_spent'] = $total_time_spent;
                $total_logs += intval($ip_logs['log_count']);
                $active_employers[$employer_key]['total_logs'] = $total_logs;
            }
        }

        return $active_employers;
    }

    function have_status_records($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $status = $this->db->count_all_results('application_status');

        if ($status > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_company_events_in_date_range($company_sid, $start_date, $end_date)
    {
        $this->db->select('sid, employers_sid, title, date, interviewer, applicant_jobs_list, applicant_job_sid, users_type');
        $this->db->where('companys_sid', $company_sid);
        $this->db->where('date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_date)) . '"');
        $this->db->where('event_status !=', 'cancelled'); //do not bring cancelled events
        $this->db->where('category', 'interview');
        $this->db->order_by('date', 'DESC');
        $records_obj = $this->db->get('portal_schedule_event');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_employers_name($sid)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('sid', $sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $name = 'Employee not found!';

        if (!empty($records_arr)) {
            $name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
        }
        return $name;
    }

    function get_applicants_name($sid)
    {
        $this->db->select('first_name, last_name');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $name = 'Applicant not found!';

        if (!empty($records_arr)) {
            $name = $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
        }
        return $name;
    }


    //
    function getEmployeesByCompanyId($companySid)
    {
        $a = $this->db
            ->select("
            sid as employeeId,
            CONCAT(first_name,' ', last_name) as full_name,
            " . (getUserFields()) . "
        ")
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->where('general_status', 'active')
            ->order_by('full_name', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getDriverLicenses($post)
    {
        $offSet = $post['limit'];
        $inSet =  $post['page'] == 1 ? 0 : (($post['page'] - 1) * $post['limit']);
        $r = array(
            'Count' => 0,
            'Data' => array()
        );
        $this->db
            ->select('license_information.license_details, CONCAT(users.first_name," ", users.last_name) as full_name, users.job_title, ' . (getUserFields()) . '')
            ->join('users', 'users.sid = license_information.users_sid', 'inner')
            ->where('users.parent_sid', $post['companySid'])
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->where('license_information.users_type', 'employee')
            ->limit($offSet, $inSet)
            ->order_by('full_name', 'ASC');
        // Filter
        if ($post['employeeSid'] != 'all') $this->db->where('license_information.users_sid', $post['employeeSid']);
        //
        $a = $this->db->get('license_information');
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) return $r;
        //
        $t = array();
        //
        foreach ($b as $k => $v) {
            $o = $v;
            $e = $v['full_name'];
            $j = $v['job_title'] == null ? '' : $v['job_title'];
            $v = @unserialize($v['license_details']);
            // Make sure all fields exists
            if (!isset($v['dob'])) $v['dob'] = '';
            if (!isset($v['license_indefinite'])) $v['license_indefinite'] = '';
            if (!isset($v['license_type'])) $v['license_type'] = '';
            if (!isset($v['license_class'])) $v['license_class'] = '';
            if (!isset($v['license_issue_date'])) $v['license_issue_date'] = '';
            if (!isset($v['license_expiration_date'])) $v['license_expiration_date'] = '';
            if (!isset($v['license_indefinite'])) $v['license_indefinite'] = '';
            if (!isset($v['license_authority'])) $v['license_authority'] = '';
            if (!isset($v['license_number'])) $v['license_number'] = '';
            // Reset fields
            $v['license_type'] = strtolower(trim($v['license_type']));
            $v['license_class'] = strtolower(trim($v['license_class']));
            $v['license_number'] = strtolower(trim($v['license_number']));
            $v['license_authority'] = strtolower(trim($v['license_authority']));
            $v['license_issue_date'] = trim($v['license_issue_date']);
            $v['license_indefinite'] = trim($v['license_indefinite']);
            $v['license_expiration_date'] = trim($v['license_expiration_date']);
            // Add Filter
            if (($post['licenseType'] != $v['license_type']) && $post['licenseType'] != 'all') continue;
            if (($post['licenseClass'] != $v['license_class']) && $post['licenseClass'] != 'all') continue;
            if (($post['licenseNumber'] != $v['license_number']) && $post['licenseNumber'] != 'all') continue;
            if (($post['issueDate'] != $v['license_issue_date']) && $post['issueDate'] != 'all') continue;
            if (($post['expirationDate'] != $v['license_expiration_date']) && $post['expirationDate'] != 'all') continue;
            //
            $v['full_name'] = $e;
            $v['job_title'] = $j;
            $v['first_name'] = $o['first_name'];
            $v['last_name'] = $o['last_name'];
            $v['access_level'] = $o['access_level'];
            $v['access_level_plus'] = $o['access_level_plus'];
            $v['pay_plan_flag'] = $o['pay_plan_flag'];
            $v['is_executive_admin'] = $o['is_executive_admin'];
            $t[] = $v;
        }
        //
        $r['Data'] = $t;
        if ($post['page'] != 1) return $r;

        $this->db
            ->select('license_information.sid')
            ->join('users', 'users.sid = license_information.users_sid', 'inner')
            ->where('users.parent_sid', $post['companySid'])
            ->where('license_information.users_type', 'employee');
        // Filter
        if ($post['employeeSid'] != 'all') $this->db->where('license_information.users_sid', $post['employeeSid']);
        //
        $a = $this->db->get('license_information');
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) return $r;
        //
        $t = 0;
        //
        foreach ($b as $k => $v) {
            $v = @unserialize($v['license_details']);
            // Make sure all fields exists
            if (!isset($v['license_type'])) $v['license_type'] = '';
            if (!isset($v['license_class'])) $v['license_class'] = '';
            if (!isset($v['license_issue_date'])) $v['license_issue_date'] = '';
            if (!isset($v['license_expiration_date'])) $v['license_expiration_date'] = '';
            if (!isset($v['license_indefinite'])) $v['license_indefinite'] = '';
            if (!isset($v['license_authority'])) $v['license_authority'] = '';
            if (!isset($v['license_number'])) $v['license_number'] = '';
            // Reset fields
            $v['license_type'] = strtolower(trim($v['license_type']));
            $v['license_class'] = strtolower(trim($v['license_class']));
            $v['license_number'] = strtolower(trim($v['license_number']));
            $v['license_authority'] = strtolower(trim($v['license_authority']));
            $v['license_issue_date'] = trim($v['license_issue_date']);
            $v['license_indefinite'] = trim($v['license_indefinite']);
            $v['license_expiration_date'] = trim($v['license_expiration_date']);
            // Add Filter
            if (($post['licenseType'] != $v['license_type']) && $post['licenseType'] != 'all') continue;
            if (($post['licenseClass'] != $v['license_class']) && $post['licenseClass'] != 'all') continue;
            if (($post['licenseNumber'] != $v['license_number']) && $post['licenseNumber'] != 'all') continue;
            if (($post['issueDate'] != $v['license_issue_date']) && $post['issueDate'] != 'all') continue;
            if (($post['expirationDate'] != $v['license_expiration_date']) && $post['expirationDate'] != 'all') continue;
            //
            $t++;
        }
        $r['Count'] = $t;

        return $r;
    }



    //
    function getDriverLicensesForExport($post)
    {
        $this->db
            ->select('license_information.license_details, CONCAT(users.first_name," ", users.last_name) as full_name, users.job_title')
            ->join('users', 'users.sid = license_information.users_sid', 'inner')
            ->where('license_information.users_type', 'employee')
            ->where('users.parent_sid', $post['companySid'])
            ->order_by('full_name', 'ASC');
        // Filter
        if ($post['dd-employee'] != 'all') $this->db->where('license_information.users_sid', $post['dd-employee']);
        //
        $a = $this->db->get('license_information');
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) return $b;
        //
        $t = array();
        //
        foreach ($b as $k => $v) {
            $e = $v['full_name'];
            $j = $v['job_title'] == null ? '' : $v['job_title'];
            $v = @unserialize($v['license_details']);
            // Make sure all fields exists
            if (!isset($v['dob'])) $v['dob'] = '';
            if (!isset($v['license_indefinite'])) $v['license_indefinite'] = '';
            if (!isset($v['license_type'])) $v['license_type'] = '';
            if (!isset($v['license_class'])) $v['license_class'] = '';
            if (!isset($v['license_issue_date'])) $v['license_issue_date'] = '';
            if (!isset($v['license_expiration_date'])) $v['license_expiration_date'] = '';
            if (!isset($v['license_indefinite'])) $v['license_indefinite'] = '';
            if (!isset($v['license_authority'])) $v['license_authority'] = '';
            if (!isset($v['license_number'])) $v['license_number'] = '';
            // Reset fields
            $v['license_type'] = strtolower(trim($v['license_type']));
            $v['license_class'] = strtolower(trim($v['license_class']));
            $v['license_number'] = strtolower(trim($v['license_number']));
            $v['license_authority'] = strtolower(trim($v['license_authority']));
            $v['license_issue_date'] = trim($v['license_issue_date']);
            $v['license_indefinite'] = trim($v['license_indefinite']);
            $v['license_expiration_date'] = trim($v['license_expiration_date']);
            // Add Filter
            if (($post['dd-license-type'] != $v['license_type']) && $post['dd-license-type'] != 'all') continue;
            if (($post['dd-license-class'] != $v['license_class']) && $post['dd-license-class'] != 'all') continue;
            if (($post['txt-license-number'] != $v['license_number']) && $post['txt-license-number'] != 'all') continue;
            if (($post['txt-issue-date'] != $v['license_issue_date']) && $post['txt-issue-date'] != 'all') continue;
            if (($post['txt-expiration-date'] != $v['license_expiration_date']) && $post['txt-expiration-date'] != 'all') continue;
            //
            $v['full_name'] = $e;
            $v['job_title'] = $j;
            $t[] = $v;
        }

        return $t;
    }

    function get_sms_data($company_sid, $start_date, $end_date)
    {
        $this->db->select('*');

        $this->db->where('company_id', $company_sid);
        $this->db->where('sender_user_id <>', 1);

        $this->db->where("created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $this->db->where("created_at BETWEEN '" . $start_date . "' AND '" . $end_date . "'");

        $this->db->order_by('sid', 'DESC');

        $sms_data = $this->db->get('portal_sms')->result_array();


        if (!empty($sms_data)) {
            return $sms_data;
        } else {
            return array();
        }
    }


    //
    function getEmployeesByCompanyIdAll($companySid)
    {
        $a = $this->db
            ->select("
        sid as employeeId,
        CONCAT(first_name,' ', last_name) as full_name,
        " . (getUserFields()) . "
    ")
            ->where('parent_sid', $companySid)
            ->order_by('full_name', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }




    function getEmployeeDocument($post, $csv = false)
    {
        $offSet = $post['limit'];
        $inSet =  $post['page'] == 1 ? 0 : (($post['page'] - 1) * $post['limit']);
        $r = array(
            'Count' => 0,
            'Data' => array()
        );

        //
        $employees = $post['employeeSid'];
        $companyid = $post['companySid'];
        //
        $status = !is_array($post['employeeStatus']) || $post['employeeStatus'][0] == 'all' ? [1, 2, 3, 4, 5, 6, 7] : $post['employeeStatus'];

        //
        if ($post['page'] == 1) {
            //
            $this->db->select('users.sid as employee_sid, users.general_status, users.first_name, users.last_name, users.job_title,users.timezone, users.is_executive_admin, users.access_level_plus, users.access_level, documents_assigned.is_confidential, documents_assigned.confidential_employees');
            $this->db->join('documents_assigned', 'documents_assigned.user_sid = users.sid');
            $this->db->from('users');
            $this->db->where('users.parent_sid', $companyid);
            // for status
            $this->db->group_start();
            foreach ($status as $vs) {
                $this->db->or_where('users.general_status', strtolower(GetEmployeeStatusText($vs)));
            }
            $this->db->group_end();
            // for employees
            if (is_array($employees) && $employees[0] != 'all') {
                $this->db->where_in('users.sid', $employees);
            }
            $r['Count'] = $this->db->count_all_results();
        }
        //
        $this->db->select('users.sid as employee_sid, users.general_status, users.first_name, users.last_name, users.job_title,users.timezone, users.is_executive_admin, users.access_level_plus, users.access_level,documents_assigned.is_confidential, documents_assigned.confidential_employees, documents_assigned.document_title, documents_assigned.managersList,documents_assigned.visible_to_payroll, documents_assigned.allowed_roles, documents_assigned.allowed_employees, documents_assigned.allowed_departments, documents_assigned.allowed_teams,documents_assigned.is_available_for_na');
        $this->db->join('documents_assigned', 'documents_assigned.user_sid = users.sid');
        $this->db->from('users');
        $this->db->where('users.parent_sid', $companyid);
        // for status
        $this->db->group_start();
        foreach ($status as $vs) {
            $this->db->or_where('users.general_status', strtolower(GetEmployeeStatusText($vs)));
        }
        $this->db->group_end();
        // for employees
        if (is_array($employees) && $employees[0] != 'all') {
            $this->db->where_in('users.sid', $employees);
        }
        // pagination
        if (!$csv) {
            $this->db->limit($offSet, $inSet);
        }
        $this->db->order_by('first_name', 'ASC');
        //
        $holderArray = $this->db->get()->result_array();
        //
        if (!$holderArray) {
            return $r;
        }
        //

        foreach ($holderArray as $k => $v) {
            // confidential employees
            if ($holderArray[$k]['confidential_employees'] != 'NULL') {
                $confidential_employees = explode(',', $holderArray[$k]['confidential_employees']);
                $this->db->select('sid as employee_sid, general_status, first_name, last_name, job_title,timezone, is_executive_admin, access_level_plus, access_level');

                if (!empty($confidential_employees) && $holderArray[$k]['confidential_employees'] != '-1') {
                    $this->db->where_in('sid', $confidential_employees);
                }

                $this->db->where('parent_sid', $companyid);
                $confidential_employees_rows =  $this->db->get('users')->result_array();
            }

            if ($confidential_employees_rows) {
                $confidential_employee_name  = ' ';
                foreach ($confidential_employees_rows as $confidential_row) {
                    $confidential_employee_name  =  $confidential_employee_name . remakeEmployeeName($confidential_row) . "<br><br>";
                }
                $holderArray[$k]['confidentialemployees'] = $confidential_employee_name;
            } else {
                $holderArray[$k]['confidentialemployees'] = '';
            }

            // Authorized Management Signers
            if ($holderArray[$k]['managersList'] != 'NULL') {
                $managerslist_employees = explode(',', $holderArray[$k]['managersList']);
                $this->db->select('sid as employee_sid, general_status, first_name, last_name, job_title,timezone, is_executive_admin, access_level_plus, access_level');
                $this->db->where_in('sid', $managerslist_employees);
                $managerslist_employees_rows =  $this->db->get('users')->result_array();
            }

            if ($managerslist_employees_rows) {
                $manager_name  = ' ';
                foreach ($managerslist_employees_rows as $manager_row) {
                    $manager_name  =  $manager_name . remakeEmployeeName($manager_row) . "<br><br>";
                }
                $holderArray[$k]['authorized_manager_name'] = $manager_name;
            } else {
                $holderArray[$k]['authorized_manager_name'] = '';
            }

            // Visible To Payroll
            if ($holderArray[$k]['allowed_employees'] != 'NULL') {
                $allowed_employees = explode(',', $holderArray[$k]['allowed_employees']);
                $this->db->select('sid as employee_sid, general_status, first_name, last_name, job_title,timezone, is_executive_admin, access_level_plus, access_level');
                $this->db->where_in('sid', $allowed_employees);
                $allowed_employees_rows =  $this->db->get('users')->result_array();
            }

            if ($allowed_employees_rows) {
                $allowed_employees_name  = 'Employees: <br>';
                foreach ($allowed_employees_rows as $allowed_employees_rows_row) {
                    $allowed_employees_name  =  $allowed_employees_name . remakeEmployeeName($allowed_employees_rows_row) . "<br><br>";
                }
                $holderArray[$k]['allowed_employees_name'] = $allowed_employees_name;
            } else {
                $holderArray[$k]['allowed_employees_name'] = '';
            }

            // Allowed Departments

            if ($holderArray[$k]['allowed_departments'] != 'NULL') {
                $allowed_departments = explode(',', $holderArray[$k]['allowed_departments']);
                $this->db->select('name as department_name');
                $this->db->where_in('sid', $allowed_departments);
                $allowed_departments_rows =  $this->db->get('departments_management')->result_array();
            }

            if ($allowed_departments_rows) {
                $allowed_departments_name  = 'Departments:  <br>';
                foreach ($allowed_departments_rows as $allowed_departments_row) {
                    $allowed_departments_name  =  $allowed_departments_name . $allowed_departments_row['department_name'] . "<br><br>";
                }
                $holderArray[$k]['allowed_departments_name'] = $allowed_departments_name;
            } else {
                $holderArray[$k]['allowed_departments_name'] = '';
            }

            // Allowed Teams

            if ($holderArray[$k]['allowed_teams'] != 'NULL') {
                $allowed_teams = explode(',', $holderArray[$k]['allowed_teams']);
                $this->db->select('name as team_name');
                $this->db->where_in('sid', $allowed_teams);
                $allowed_teams_rows =  $this->db->get('departments_team_management')->result_array();
            }

            if ($allowed_teams_rows) {
                $allowed_teams_name  = 'Teams: <br>';
                foreach ($allowed_teams_rows as $allowed_teams_row) {
                    $allowed_teams_name  =  $allowed_teams_name . $allowed_teams_row['team_name'] . "<br><br>";
                }
                $holderArray[$k]['allowed_teams_name'] = $allowed_teams_name;
            } else {
                $holderArray[$k]['allowed_teams_name'] = '';
            }

            if (!empty($holderArray[$k]['is_available_for_na'])) {
                $holderArray[$k]['is_available_for_na'] = "Roles: <br>" . $holderArray[$k]['is_available_for_na'] . "<br><br>";
            } else {
                $holderArray[$k]['is_available_for_na'] = '';
            }
        }
        //
        $r['Data'] = array_values($holderArray);
        //
        return $r;
    }
}
