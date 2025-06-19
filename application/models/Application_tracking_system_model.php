<?php

use Aws\DynamoDb\Enum\Type;

class Application_tracking_system_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function rearrange_applicant_data($company_sid, $applications = array())
    {
        $have_status = $this->have_status_records($company_sid);

        if ($have_status == true) {
            $company_statuses = $this->get_company_statuses_specific($company_sid);
        }

        $result_array = array();
        $applicants_array = array();

        //foreach ($applications as $key => $application) {
        for ($i = 0; $i < count($applications); $i++) {
            $application = $applications[$i];
            $applicant_sid = $application['applicant_sid'];
            $result = array();
            $result = $application;

            if ($result['job_title'] == NULL && $result['job_title'] == '') {
                if ($result['desired_job_title'] != NULL && $result['desired_job_title'] != '') {
                    $result['job_title'] = $result['desired_job_title'];
                } else {
                    $result['job_title'] = 'Job Not Applied';
                }
            }

            if (!isset($applicants_array[$applicant_sid])) {
                $applicants_array[$applicant_sid] = $this->get_applicant_multiple_job_count($applicant_sid, $company_sid);
            }

            $result['multiple_jobs'] = $applicants_array[$applicant_sid];

            if ($have_status == true) {
                $status_sid = $application['status_sid'];

                if (!empty($company_statuses) && $status_sid > 1 && isset($company_statuses[$status_sid])) {
                    $result['status_name'] = $company_statuses[$status_sid]['name'];
                    $result['status_css_class'] = $company_statuses[$status_sid]['css_class'];
                    $result['status_text_css_class'] = $company_statuses[$status_sid]['text_css_class'];
                    $result['status_type'] = $company_statuses[$status_sid]['status_type'];
                    $result['bar_bgcolor'] = $company_statuses[$status_sid]['bar_bgcolor'];
                } else {
                    $result['status_name'] = 'Not Contacted Yet';
                    $result['status_css_class'] = 'not_contacted';
                    $result['status_text_css_class'] = 'not_contacted_text';
                    $result['status_type'] = '';
                    $result['bar_bgcolor'] = '';
                }
            }

            $reviews_count = $this->get_applicant_reviews_count($applicant_sid);
            $applicant_average_rating = '';
            $result['reviews_count'] = $reviews_count;

            if ($reviews_count > 0) {
                $applicant_average_rating = $this->getApplicantAverageRating($applicant_sid, 'applicant');
            }

            $result['applicant_average_rating'] = $applicant_average_rating;
            $resume_download_link = '';
            $cover_letter_download_link = '';
            $resume_direct_link = '';
            $cover_letter_direct_link = '';

            if (empty($application['resume'])) {
                $resume_direct_link = '';
                $resume_download_link = '';
            } else {
                $resume_direct_link = AWS_S3_BUCKET_URL . $application['resume'];
                //$resume_download_link = base_url('applicant_profile/downloadFile') . '/' . $application['resume'];
                $resume_download_link = AWS_S3_BUCKET_URL . $application['resume'];
            }

            if (empty($application['cover_letter'])) {
                $cover_letter_direct_link = '';
                $cover_letter_download_link = '';
            } else {
                $cover_letter_direct_link = AWS_S3_BUCKET_URL . $application['cover_letter'];
                //$cover_letter_download_link = base_url('applicant_profile/downloadFile') . '/' . $application['cover_letter']; //
                $cover_letter_download_link = AWS_S3_BUCKET_URL . $application['cover_letter'];
            }

            $result['resume_direct_link'] = $resume_direct_link;
            $result['resume_download_link'] = $resume_download_link;
            $result['cover_letter_direct_link'] = $cover_letter_direct_link;
            $result['cover_letter_download_link'] = $cover_letter_download_link;
            $questionnaire_manual_sent = $application['questionnaire_manual_sent']; //get resent applicants history // get the questionnaire results.

            if ($questionnaire_manual_sent == 1) {
                $manual_applicant_sid = $application['sid'];
                $manual_applicant_jobs_list_sid = $application['applicant_sid'];
                $result['manual_questionnaire_history'] = $this->screening_questionnaire_manual_sent_tracking($manual_applicant_sid, $manual_applicant_jobs_list_sid);
            } else {
                $result['manual_questionnaire_history'] = array();
            }

            /*             * ************************************************************************* */
            $average_interview_score = $this->get_questionnaire_average_score($company_sid, $applicant_sid, 'applicant', 0);
            $result['interview_score'] = $average_interview_score;
            $video_interview_scores = $this->get_applicant_video_interview_rating($company_sid, $applicant_sid);
            $average_rating = 0;
            $total_rating = 0;

            if (!empty($video_interview_scores)) {
                foreach ($video_interview_scores as $score) {
                    $total_rating = $total_rating + $score['rating'];
                }
            }

            if (count($video_interview_scores) > 0) {
                $average_rating = $total_rating / count($video_interview_scores);
            }

            $result['video_interview_score'] = round($average_rating, 1);
            $result['notes_count'] = $this->get_applicant_notes_count($applicant_sid);
            /*             * ************************************************************************ */

            $result_array[] = $result;
        }

        return $result_array;
    }

    function get_applicant_notes_count($applicant_sid = NULL)
    {
        $this->db->where('applicant_job_sid', $applicant_sid);
        $this->db->from('portal_misc_notes');
        return $this->db->count_all_results();
    }

    function get_admin_jobs_and_applicants($company_sid, $archived = 0, $limit = 0, $start = 1, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
        $this->db->select('portal_job_applications.cover_letter');
        $this->db->select('portal_job_applications.job_fit_category_sid');
        $this->db->select('portal_job_applications.is_onboarding');
        $this->db->select('portal_applicant_jobs_list.sid, portal_applicant_jobs_list.portal_job_applications_sid, portal_applicant_jobs_list.job_sid, portal_applicant_jobs_list.date_applied, portal_applicant_jobs_list.status, portal_applicant_jobs_list.status_sid, portal_applicant_jobs_list.questionnaire, portal_applicant_jobs_list.score, portal_applicant_jobs_list.passing_score, portal_applicant_jobs_list.applicant_type, portal_applicant_jobs_list.desired_job_title, portal_applicant_jobs_list.questionnaire_manual_sent');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('IF ((portal_applicant_jobs_list.resume IS NULL) , (portal_job_applications.resume) , (portal_applicant_jobs_list.resume)) AS resume');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }

        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if ($type != null && $type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        if (!empty($applicant_filters)) {
            foreach ($applicant_filters as $key => $value) {
                //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                $replaced_value = str_replace(' ', '-', $value);
                $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
            }
        }

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        //$applications = $this->db->get('portal_applicant_jobs_list')->result_array();

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $applications = $records_obj->result_array();
        $records_obj->free_result();

        //echo '<br>'.$this->db->last_query(); //exit;
        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $applications = array();
            }
        }

        $applications = $this->rearrange_applicant_data($company_sid, $applications);
        return $applications;
    }

    function get_admin_jobs_and_applicants_count($company_sid, $archived = 0, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }

        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        if (!empty($applicant_filters)) {
            foreach ($applicant_filters as $key => $value) {
                //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                $replaced_value = str_replace(' ', '-', $value);
                $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
            }
        }

        if ($type != null && $type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $records_arr = array();
            }
        }


        $result = $this->generate_applicants_counts($records_arr);
        return $result;
    }

    /*     * * employee ** */

    function get_employee_jobs_and_applicants($company_sid, $employer_sid, $archived = 0, $limit = 0, $start = 1, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        //
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
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
        $this->db->select('portal_job_applications.is_onboarding');
        $this->db->select('portal_applicant_jobs_list.*'); //hassanbokhary here here
        $this->db->select('IF ((portal_applicant_jobs_list.resume IS NULL) , (portal_job_applications.resume) , (portal_applicant_jobs_list.resume)) AS resume');
        $this->db->select('portal_job_listings_visibility.job_sid as visibility_job_sid');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }

        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        // $this->db->where_in('portal_job_listings_visibility.employer_sid', [$employer_sid, 0]);
        $this->db->where_in('portal_job_listings_visibility.employer_sid', [$employer_sid]);
        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if (!empty($applicant_filters)) {
            foreach ($applicant_filters as $key => $value) {
                //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                $replaced_value = str_replace(' ', '-', $value);
                $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
            }
        }

        if ($type != null && $type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        if (!empty($applicant_sid_list)) {
            $this->db->or_where_in('portal_applicant_jobs_list.sid', $applicant_sid_list);
        }

        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }

        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $records_obj = $this->db->get('portal_job_listings_visibility');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $records_arr = array();
            }
        }

        $applications = $this->rearrange_applicant_data($company_sid, $records_arr);

        $get = array();
        //
        if ($type == 'Job Fair' || $type == 'all') {
            $applicant_sid_list = array_column($applications, "sid");
            $get = getEmployeeJobfairApplicant($company_sid, $employer_sid, $applicant_sid_list, '', '', '', 'no', $fair_type, $applicant_filters);
        }
        //

        if (!empty($get)) {
            $job_fair_list = array_column($get, "sid");
            $job_fair_applications = $this->get_job_fair_applicant($job_fair_list, $company_sid, $applicant_status);
            $applicants = array_merge($applications, $job_fair_applications);

            //
            usort($applicants, 'sort_by_date');

            return $applicants;
        } else {
            return $applications;
        }
    }

    function get_job_fair_applicant($applicant_sids, $company_sid, $applicant_status)
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
        $this->db->select('portal_job_applications.employer_sid');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.pictures');
        $this->db->select('portal_job_applications.email');
        $this->db->select('portal_job_applications.phone_number');
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
        $this->db->select('portal_job_applications.is_onboarding');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->select('IF ((portal_applicant_jobs_list.resume IS NULL) , (portal_job_applications.resume) , (portal_applicant_jobs_list.resume)) AS resume');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        $this->db->where_in('portal_applicant_jobs_list.sid', $applicant_sids);
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $records_arr = array();
            }
        }

        $applications = $this->rearrange_jobfair_data($company_sid, $records_arr);

        // return $records_arr;
        return $applications;
    }

    function rearrange_jobfair_data($company_sid, $applications = array())
    {
        $have_status = $this->have_status_records($company_sid);

        if ($have_status == true) {
            $company_statuses = $this->get_company_statuses_specific($company_sid);
        }

        $result_array = array();
        $applicants_array = array();

        //foreach ($applications as $key => $application) {
        for ($i = 0; $i < count($applications); $i++) {
            $application = $applications[$i];
            $applicant_sid = $application['applicant_sid'];
            $result = array();
            $result = $application;

            $result['job_title'] = 'Job Not Applied';

            if (!isset($applicants_array[$applicant_sid])) {
                $applicants_array[$applicant_sid] = $this->get_applicant_multiple_job_count($applicant_sid, $company_sid);
            }

            $result['multiple_jobs'] = $applicants_array[$applicant_sid];

            if ($have_status == true) {
                $status_sid = $application['status_sid'];

                if (!empty($company_statuses) && $status_sid > 1 && isset($company_statuses[$status_sid])) {
                    $result['status_name'] = $company_statuses[$status_sid]['name'];
                    $result['status_css_class'] = $company_statuses[$status_sid]['css_class'];
                    $result['status_text_css_class'] = $company_statuses[$status_sid]['text_css_class'];
                    $result['status_type'] = $company_statuses[$status_sid]['status_type'];
                    $result['bar_bgcolor'] = $company_statuses[$status_sid]['bar_bgcolor'];
                } else {
                    $result['status_name'] = 'Not Contacted Yet';
                    $result['status_css_class'] = 'not_contacted';
                    $result['status_text_css_class'] = 'not_contacted_text';
                    $result['status_type'] = '';
                    $result['bar_bgcolor'] = '';
                }
            }

            $reviews_count = $this->get_applicant_reviews_count($applicant_sid);
            $applicant_average_rating = '';
            $result['reviews_count'] = $reviews_count;

            if ($reviews_count > 0) {
                $applicant_average_rating = $this->getApplicantAverageRating($applicant_sid, 'applicant');
            }

            $result['applicant_average_rating'] = $applicant_average_rating;
            $resume_download_link = '';
            $cover_letter_download_link = '';
            $resume_direct_link = '';
            $cover_letter_direct_link = '';

            if (empty($application['resume'])) {
                $resume_direct_link = '';
                $resume_download_link = '';
            } else {
                $resume_direct_link = AWS_S3_BUCKET_URL . $application['resume'];
                //$resume_download_link = base_url('applicant_profile/downloadFile') . '/' . $application['resume'];
                $resume_download_link = AWS_S3_BUCKET_URL . $application['resume'];
            }

            if (empty($application['cover_letter'])) {
                $cover_letter_direct_link = '';
                $cover_letter_download_link = '';
            } else {
                $cover_letter_direct_link = AWS_S3_BUCKET_URL . $application['cover_letter'];
                //$cover_letter_download_link = base_url('applicant_profile/downloadFile') . '/' . $application['cover_letter']; //
                $cover_letter_download_link = AWS_S3_BUCKET_URL . $application['cover_letter'];
            }

            $result['resume_direct_link'] = $resume_direct_link;
            $result['resume_download_link'] = $resume_download_link;
            $result['cover_letter_direct_link'] = $cover_letter_direct_link;
            $result['cover_letter_download_link'] = $cover_letter_download_link;
            $questionnaire_manual_sent = $application['questionnaire_manual_sent']; //get resent applicants history // get the questionnaire results.

            if ($questionnaire_manual_sent == 1) {
                $manual_applicant_sid = $application['sid'];
                $manual_applicant_jobs_list_sid = $application['applicant_sid'];
                $result['manual_questionnaire_history'] = $this->screening_questionnaire_manual_sent_tracking($manual_applicant_sid, $manual_applicant_jobs_list_sid);
            } else {
                $result['manual_questionnaire_history'] = array();
            }

            /*             * ************************************************************************* */
            $average_interview_score = $this->get_questionnaire_average_score($company_sid, $applicant_sid, 'applicant', 0);
            $result['interview_score'] = $average_interview_score;
            $video_interview_scores = $this->get_applicant_video_interview_rating($company_sid, $applicant_sid);
            $average_rating = 0;
            $total_rating = 0;

            if (!empty($video_interview_scores)) {
                foreach ($video_interview_scores as $score) {
                    $total_rating = $total_rating + $score['rating'];
                }
            }

            if (count($video_interview_scores) > 0) {
                $average_rating = $total_rating / count($video_interview_scores);
            }

            $result['video_interview_score'] = round($average_rating, 1);
            $result['notes_count'] = $this->get_applicant_notes_count($applicant_sid);
            /*             * ************************************************************************ */

            $result_array[] = $result;
        }

        return $result_array;
    }

    function get_employee_jobs_and_applicants_count($company_sid, $employer_sid, $archived = 0, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        $this->db->select('portal_job_listings_visibility.sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }

        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if (!empty($applicant_filters)) {
            foreach ($applicant_filters as $key => $value) {
                //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                $replaced_value = str_replace(' ', '-', $value);
                $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
            }
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }
        //$result = $this->db->get('portal_job_listings_visibility')->num_rows();
        $records_obj = $this->db->get('portal_job_listings_visibility');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $records_arr = array();
            }
        }

        $filtered_visible_applicants = $records_arr;
        //Get Talent Network, Manual Candidates and Job Fair Applicants
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.archived', $archived);
        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if (!empty($applicant_filters)) {
            foreach ($applicant_filters as $key => $value) {
                //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                $replaced_value = str_replace(' ', '-', $value);
                $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
            }
        }

        if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->where_in('portal_applicant_jobs_list.applicant_type', ['Manual Candidate', 'Job Fair', 'Talent Network']);
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
        $this->db->join('portal_job_listings_visibility', 'portal_job_listings_visibility.job_sid = portal_applicant_jobs_list.job_sid');
        //        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid', 'left');

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $all_manual_talent_and_job_fair_applicants = $records_arr;
        $applicants = array_merge($filtered_visible_applicants, $all_manual_talent_and_job_fair_applicants);

        $get = array();
        //
        if ($type == 'Job Fair' || $type == 'all') {
            $applicant_sid_list = array_column($applicants, "sid");
            $get = getEmployeeJobfairApplicant($company_sid, $employer_sid, $applicant_sid_list, '', '', '', 'no', $fair_type, $applicant_filters);
        }
        //

        if (!empty($get)) {
            $job_fair_list = array_column($get, "sid");
            $job_fair_applications = $this->get_job_fair_applicant($job_fair_list, $company_sid, $applicant_status);

            $applicants = array_merge($applicants, $job_fair_applications);
            //
            usort($applicants, 'sort_by_date');
        }

        $result = $this->generate_applicants_counts($applicants);

        return $result;
    }

    function get_job_specific_applicants($company_sid, $employer_sid, $job_sid, $archived, $limit = 0, $start = 1, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        $this->db->select('has_job_approval_rights');
        $this->db->where('sid', $company_sid);
        $records = $this->db->get('users')->result_array();
        $job_visibility_status = 0;

        if (!empty($records)) {
            $job_visibility_status = $records[0]['has_job_approval_rights'];
        }
        $tempIdsArray = array();
        //
        $t = explode(',', $job_sid);
        //
        if (sizeof($t)) {
            foreach ($t as $k => $v) {
                if (preg_match('/[a-z]/', $v)) {
                    $tempIdsArray[] = ltrim($v, 'd');
                    unset($t[$k]);
                }
            }
            //
            $job_sid = implode(',', $t);
        }

        // Get distinct desired title
        $tempIdsArray = $this->getSiblingIds($tempIdsArray);

        if ($is_admin || $job_visibility_status == 0) {

            $check_jobs_exists = explode(',', $job_sid);
        } else {
            $this->db->select('job_sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $results = $this->db->get('portal_job_listings_visibility')->result_array();
            $tempArray = array();

            if (!empty($results)) {
                foreach ($results as $result) {
                    $tempArray[] = $result['job_sid'];
                }
            }

            $job_sid_array = explode(',', $job_sid);
            $check_jobs_exists = array_intersect($job_sid_array, $tempArray);
        }


        //if (!empty($check_jobs_exists) && is_admin($employer_sid)) {
        if (!empty($check_jobs_exists)) {
            //$this->db->select('portal_applicant_jobs_list.sid, portal_applicant_jobs_list.portal_job_applications_sid, portal_applicant_jobs_list.job_sid, portal_applicant_jobs_list.date_applied, portal_applicant_jobs_list.status, portal_applicant_jobs_list.status_sid, portal_applicant_jobs_list.questionnaire, portal_applicant_jobs_list.score, portal_applicant_jobs_list.passing_score, portal_applicant_jobs_list.applicant_type, portal_applicant_jobs_list.desired_job_title, portal_job_applications.hired_status, portal_job_applications.first_name, portal_job_applications.last_name, portal_job_applications.phone_number, portal_job_applications.resume, portal_job_applications.cover_letter, portal_job_applications.pictures, portal_job_applications.sid as applicant_sid');
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
            $this->db->select('portal_job_applications.is_onboarding');
            $this->db->select('portal_job_listings.Title as job_title');
            $this->db->select('portal_job_listings.Location_State');
            $this->db->select('portal_job_listings.Location_Country');
            $this->db->select('portal_job_listings.Location_ZipCode');
            $this->db->select('portal_job_listings.Location_City');
            $this->db->select('portal_applicant_jobs_list.*');
            $this->db->select('IF ((portal_applicant_jobs_list.resume IS NULL) , (portal_job_applications.resume) , (portal_applicant_jobs_list.resume)) AS resume');
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

            if ($applicant_status != 'onboarding') {
                $this->db->where('portal_applicant_jobs_list.archived', $archived);
            } else {
                $this->db->where('portal_job_applications.is_onboarding', 1);
            }


            if (sizeof($check_jobs_exists) || $tempIdsArray != '') {
                $this->db->group_start();
                if ((sizeof($check_jobs_exists) && !empty($check_jobs_exists[0]))) $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
                if ($tempIdsArray != '' && $tempIdsArray != null) $this->db->or_where_in('portal_applicant_jobs_list.desired_job_title', $tempIdsArray, false);
                $this->db->group_end();
            }
            $this->db->where('portal_job_applications.hired_status', 0);

            if ($assigned_applicants_sids != null) {
                $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
            }

            if ($type != null && $type != 'all') {
                $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
            }

            if ($type == 'Job Fair' && $fair_type != 'all') {
                $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
            }

            if ($ques_status == 'qs') {
                $this->db->group_start();
                $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
                $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
                $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
                $this->db->group_end();
            }

            if ($ques_status == 'qc') {
                $this->db->group_start();
                $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
                $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
                $this->db->group_end();
            }

            $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');
            $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');

            if ($emp_app_status == 'eas') {
                $this->db->where('form_full_employment_application.user_type', 'applicant');
                $this->db->where('form_full_employment_application.status', 'sent');
                $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
            }

            if ($emp_app_status == 'eans') {
                $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
            }

            if ($emp_app_status == 'eac') {
                $this->db->where('form_full_employment_application.user_type', 'applicant');
                $this->db->where('form_full_employment_application.status', 'signed');
                $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
            }

            if (!empty($applicant_filters)) {
                foreach ($applicant_filters as $key => $value) {
                    //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                    $replaced_value = str_replace(' ', '-', $value);
                    $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                    $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
                }
            }

            if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
                $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
            }

            if ($limit > 0) {
                $this->db->limit($limit, $start);
            }
            $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
            $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

            if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
                if (empty($assigned_applicants_sids)) {
                    $applications = array();
                }
            }

            $applications = $this->rearrange_applicant_data($company_sid, $applications);

            return $applications;
        } else {
            return array();
        }
    }

    private function getSiblingIds($t)
    {
        if (!sizeof($t)) return array();
        //
        $a = $this->db
            ->select('distinct(portal_applicant_jobs_list.desired_job_title)')
            ->where_in('portal_applicant_jobs_list.sid', $t)
            ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!sizeof($b)) return array();

        //
        $slugs = '';
        foreach ($b as $k => $v) $slugs .= "'" . $v['desired_job_title'] . "',";
        return rtrim($slugs, ',');
        //
        $a = $this->db
            ->select('distinct(portal_applicant_jobs_list.sid)')
            ->where_in('portal_applicant_jobs_list.desired_job_title', $slugs, false)
            ->get('portal_applicant_jobs_list');

        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = [];
        //
        if (sizeof($b)) foreach ($b as $v) $r[] = $v['sid'];
        //
        return $r;
    }

    function get_job_specific_applicants_count($company_sid, $employer_sid, $job_sid, $archived, $applicant_filters, $job_fit_category_sid, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        $this->db->select('has_job_approval_rights');
        $this->db->where('sid', $company_sid);
        $records = $this->db->get('users')->result_array();
        $job_visibility_status = 0;


        if (!empty($records)) {
            $job_visibility_status = $records[0]['has_job_approval_rights'];
        }

        $tempIdsArray = array();
        //
        $t = explode(',', $job_sid);
        //
        if (sizeof($t)) {
            foreach ($t as $k => $v) {
                if (preg_match('/[a-z]/', $v)) {
                    $tempIdsArray[] = ltrim($v, 'd');
                    unset($t[$k]);
                }
            }
            //
            $job_sid = implode(',', $t);
        }

        // Get distinct desired title
        $tempIdsArray = $this->getSiblingIds($tempIdsArray);

        if ($is_admin || $job_visibility_status == 0) {
            $check_jobs_exists = explode(',', $job_sid);
        } else {
            $this->db->select('job_sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $results = $this->db->get('portal_job_listings_visibility')->result_array();
            $tempArray = array();

            if (!empty($results)) {
                foreach ($results as $result) {
                    $tempArray[] = $result['job_sid'];
                }
            }

            $job_sid_array = explode(',', $job_sid);
            $check_jobs_exists = array_intersect($job_sid_array, $tempArray);
        }

        if (!empty($check_jobs_exists)) {
            $this->db->select('portal_applicant_jobs_list.sid');
            $this->db->select('portal_applicant_jobs_list.applicant_type');
            $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

            if ($applicant_status != 'onboarding') {
                $this->db->where('portal_applicant_jobs_list.archived', $archived);
            } else {
                $this->db->where('portal_job_applications.is_onboarding', 1);
            }

            if (sizeof($check_jobs_exists) || $tempIdsArray != '') {
                $this->db->group_start();
                if ((sizeof($check_jobs_exists) && !empty($check_jobs_exists[0]))) $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
                if ($tempIdsArray != '' && $tempIdsArray != null) $this->db->or_where_in('portal_applicant_jobs_list.desired_job_title', $tempIdsArray, false);
                $this->db->group_end();
            }

            // $this->db->where_in('portal_applicant_jobs_list.job_sid', $check_jobs_exists);
            $this->db->where('portal_job_applications.hired_status', 0);

            if (!empty($applicant_filters)) {
                foreach ($applicant_filters as $key => $value) {
                    //$this->db->where('portal_applicant_jobs_list.' . $key, $value);
                    $replaced_value = str_replace(' ', '-', $value);
                    $replaced_key = '`portal_applicant_jobs_list`.' . '`' . $key . '`';
                    $this->db->where("REPLACE($replaced_key, ' ', '-') = '$replaced_value'");
                }
            }

            if ($job_fit_category_sid != 0 && $job_fit_category_sid != 'all') {
                $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
            }

            if ($assigned_applicants_sids != null) {
                $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
            }

            if ($type != null && $type != 'all') {
                $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
            }

            if ($type == 'Job Fair' && $fair_type != 'all') {
                $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
            }

            if ($ques_status == 'qs') {
                $this->db->group_start();
                $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
                $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
                $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
                $this->db->group_end();
            }

            if ($ques_status == 'qc') {
                $this->db->group_start();
                $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
                $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
                $this->db->group_end();
            }

            $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

            if ($emp_app_status == 'eas') {
                $this->db->where('form_full_employment_application.user_type', 'applicant');
                $this->db->where('form_full_employment_application.status', 'sent');
                $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
            }

            if ($emp_app_status == 'eans') {
                $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
            }

            if ($emp_app_status == 'eac') {
                $this->db->where('form_full_employment_application.user_type', 'applicant');
                $this->db->where('form_full_employment_application.status', 'signed');
                $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
            }
            // _e($this->db->get_compiled_select('portal_applicant_jobs_list'), true);
            $records_obj = $this->db->get('portal_applicant_jobs_list');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
                if (empty($assigned_applicants_sids)) {
                    $records_arr = array();
                }
            }

            $result = $this->generate_applicants_counts($records_arr);
            return $result;
        } else {
            return array();
        }
    }

    /*     * * job specific ** */

    function get_applicant_status($status_sid)
    {
        $this->db->where('sid', $status_sid);
        return $this->db->get('application_status')->result_array();
    }

    function have_status_records($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $status = $this->db->get('application_status')->result_array();

        if (sizeof($status) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_company_statuses($company_sid)
    {
        $this->db->select('sid, name, css_class, status_order, status_type, bar_bgcolor');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('application_status')->result_array();
    }

    function get_company_statuses_specific($company_sid)
    {
        $this->db->select('sid, name, css_class, text_css_class, status_order, status_type, bar_bgcolor');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'asc');
        $status_ids = $this->db->get('application_status')->result_array();
        $retun_array = array();

        for ($i = 0; $i < count($status_ids); $i++) {
            $sid = $status_ids[$i]['sid'];
            $name = $status_ids[$i]['name'];
            $css_class = $status_ids[$i]['css_class'];
            $text_css_class = $status_ids[$i]['text_css_class'];
            $status_order = $status_ids[$i]['status_order'];
            $status_type = $status_ids[$i]['status_type'];
            $bar_bgcolor = $status_ids[$i]['bar_bgcolor'];

            $retun_array[$sid] = array(
                'sid' => $sid,
                'name' => $name,
                'css_class' => $css_class,
                'text_css_class' => $text_css_class,
                'company_sid' => $company_sid,
                'status_order' => $status_order,
                'status_type' => $status_type,
                'bar_bgcolor' => $bar_bgcolor
            );
        }

        return $retun_array;
    }

    function get_applicant_reviews_count($applicant_sid)
    {
        $this->db->select('sid');
        $this->db->where('applicant_job_sid', $applicant_sid);
        $this->db->from('portal_applicant_rating');
        return $this->db->count_all_results();
    }

    function get_all_jobs_company_specific($company_sid, $status = null)
    {
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('sid, Title, approval_status, active, activation_date, deactivation_date');

        if ($status === null) {
            $this->db->where('active <', 2);
        } else {
            $this->db->where('active', intval($status));
        }

        $this->db->where('user_sid', $company_sid);
        $this->db->order_by('portal_job_listings.active', 'DESC');
        $this->db->order_by('portal_job_listings.sid', 'DESC');

        $records_obj = $this->db->get('portal_job_listings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        //
        $records_arr = array_merge($records_arr, $this->getDesiredJobs($company_sid, $status));

        return $records_arr;
    }

    //
    private function getDesiredJobs(
        $company_sid,
        $status
    ) {
        $this->db
            ->select("desired_job_title, sid")
            ->where('company_sid', $company_sid);
        //
        $a = $this->db->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = [];
        if (sizeof($b)) foreach ($b as $k => $v) if (!isset($r[$v['desired_job_title']])) $r[$v['desired_job_title']] = $v;

        // _e($b, true, true);
        //
        return array_values($r);
    }

    function get_all_jobs_company_and_employer_specific($company_sid, $employer_sid, $status = null)
    {
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.sid');
        $this->db->select('portal_job_listings.approval_status');
        $this->db->select('portal_job_listings.active');
        $this->db->select('portal_job_listings.activation_date');
        $this->db->select('portal_job_listings.deactivation_date');

        if ($status === null) {
            $this->db->where('portal_job_listings.active <', 2);
        } else {
            $this->db->where('portal_job_listings.active', intval($status));
        }

        $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
        $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
        $this->db->order_by('portal_job_listings.active', 'DESC');
        $this->db->order_by('portal_job_listings.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_job_listings_visibility.job_sid', 'left');

        $records_obj = $this->db->get('portal_job_listings_visibility');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function filtration($keywords)
    {
        if (!empty($keywords)) {
            $position = strpos($keywords, '@');

            if ($position === false) { // not an email
                //
                $testKeywords = preg_replace('/[^0-9]/i', '', $keywords);
                $phoneKeywords = preg_replace('/[^0-9]/i', '', $keywords);
                //
                // if (!empty($testKeywords) && is_numeric($testKeywords)){
                //     $keywords = $testKeywords;
                // }
                // _e($keywords, true);
                //
                $this->db->group_start();
                $this->db->like('REPLACE(CONCAT(portal_job_applications.first_name,"", portal_job_applications.last_name), "" ,"")', str_replace(' ', '', $keywords));
                $this->db->or_where('portal_job_applications.extra_info REGEXP "' . $keywords . '" ', null);
                //
                if ($phoneKeywords) {
                    $this->db->or_where('REGEXP_REPLACE(portal_job_applications.phone_number,"[^0-9]","")', $phoneKeywords, false);
                    $this->db->or_where('REGEXP_REPLACE(portal_job_applications.phone_number,"[^0-9]","")', '1' . $phoneKeywords, false);
                    $this->db->or_where('REGEXP_REPLACE(portal_job_applications.phone_number,"[^0-9]","")', '+1' . $phoneKeywords, false);
                }
                $this->db->group_end();
            } else {   // this is an email
                $this->db->group_start();
                $this->db->like('portal_job_applications.email', trim($keywords));
                $this->db->or_where('portal_job_applications.extra_info REGEXP "' . $keywords . '" ', null);
                $this->db->group_end();
            }
        }
    }

    function get_applicants_by_search($company_sid, $employer_sid, $archived, $keywords, $limit = 0, $start = 1, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        if (!($is_admin)) {
            $this->db->select('portal_job_listings_visibility.job_sid as visibility_job_sid');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->join('portal_job_listings_visibility', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        }
        //$this->db->select('portal_applicant_jobs_list.sid, portal_applicant_jobs_list.portal_job_applications_sid, portal_applicant_jobs_list.job_sid, portal_applicant_jobs_list.date_applied, portal_applicant_jobs_list.status, portal_applicant_jobs_list.status_sid, portal_applicant_jobs_list.questionnaire, portal_applicant_jobs_list.score, portal_applicant_jobs_list.passing_score, portal_applicant_jobs_list.applicant_type, portal_applicant_jobs_list.desired_job_title, portal_job_applications.hired_status, portal_job_applications.first_name, portal_job_applications.last_name, portal_job_applications.phone_number, portal_job_applications.resume, portal_job_applications.cover_letter, portal_job_applications.pictures, portal_job_applications.sid as applicant_sid');
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
        $this->db->select('portal_job_applications.is_onboarding');
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->select('IF ((portal_applicant_jobs_list.resume IS NULL) , (portal_job_applications.resume) , (portal_applicant_jobs_list.resume)) AS resume');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }

        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if ($type != null && $type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }
        $this->filtration($keywords);

        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $applications = array();
            }
        }
        $applications = $this->rearrange_applicant_data($company_sid, $applications);
        return $applications;
    }

    function get_applicants_by_search_count($company_sid, $employer_sid, $archived, $keywords, $assigned_applicants_sids = null, $applicant_status = 'active', $type = 'all', $is_admin, $fair_type = 'all', $ques_status = 'all', $emp_app_status = 'all')
    {
        if (!($is_admin)) {
            $this->db->select('portal_job_listings_visibility.job_sid as visibility_job_sid');
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->join('portal_job_listings_visibility', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        }

        $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        if ($applicant_status != 'onboarding') {
            $this->db->where('portal_applicant_jobs_list.archived', $archived);
        } else {
            $this->db->where('portal_job_applications.is_onboarding', 1);
        }
        $this->db->where('portal_job_applications.hired_status', 0);

        if ($assigned_applicants_sids != null) {
            $this->db->where_in('portal_job_applications.sid', $assigned_applicants_sids);
        }

        if ($type != null && $type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $type);
        }

        if ($type == 'Job Fair' && $fair_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_fair_key', $fair_type);
        }

        if ($ques_status == 'qs') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire <> ', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire <> ', '');
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_manual_sent', 1);
            $this->db->group_end();
        }

        if ($ques_status == 'qc') {
            $this->db->group_start();
            $this->db->where('portal_applicant_jobs_list.questionnaire_result <>', NULL);
            $this->db->or_where('portal_applicant_jobs_list.questionnaire_result <>', '');
            $this->db->group_end();
        }

        $this->filtration($keywords);
        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($emp_app_status == 'eas') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'sent');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        if ($emp_app_status == 'eans') {
            $this->db->where('portal_job_applications.sid NOT IN (select form_full_employment_application.user_sid from form_full_employment_application inner join portal_job_applications on form_full_employment_application.user_sid = portal_job_applications.sid WHERE form_full_employment_application.company_sid = ' . $company_sid . ')', NULL, FALSE);
        }

        if ($emp_app_status == 'eac') {
            $this->db->where('form_full_employment_application.user_type', 'applicant');
            $this->db->where('form_full_employment_application.status', 'signed');
            $this->db->join('form_full_employment_application', 'form_full_employment_application.user_sid = portal_job_applications.sid', 'left');
        }

        $records_obj = $this->db->get('portal_applicant_jobs_list');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!in_array($applicant_status, array('active', 'archive', 'onboarding'))) {
            if (empty($assigned_applicants_sids)) {
                $records_arr = array();
            }
        }

        $result = $this->generate_applicants_counts($records_arr);
        return $result;
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

    function get_applicant_count_by_type($employer_id, $archived, $type)
    {
        $applicants_not_hired_sids = $this->get_not_hired_applicants($employer_id);
        $this->db->where('applicant_type', $type);
        $this->db->where('company_sid', $employer_id);

        if (sizeof($applicants_not_hired_sids) > 0) {
            $this->db->where_in('portal_job_applications_sid', $applicants_not_hired_sids);
        }

        $this->db->where('archived', $archived);
        $result = $this->db->get('portal_applicant_jobs_list');
        return $result->num_rows();
    }

    function getApplicantCountByMonth($applicant_type, $company_id)
    {
        $result = $this->db->query('SELECT MONTH(date_applied) as month, COUNT(*) as count FROM `portal_applicant_jobs_list` where applicant_type = "' . $applicant_type . '" and company_sid = ' . $company_id . ' and YEAR(date_applied) = ' . date('Y') . ' GROUP BY MONTH(date_applied)');
        return $result->result_array();
    }

    function active_single_applicant($id)
    {
        $data = array(
            'archived' => 0
        );

        $this->db->where('sid', $id);
        $this->db->update('portal_applicant_jobs_list', $data);
    }

    function get_application_status($status_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $status_sid);
        return $this->db->get('application_status')->result_array();
    }

    function getEmployerID($job_id)
    {
        $result = $this->db
            ->get_where(
                'portal_job_listings',
                array(
                    'sid' => $job_id
                )
            );

        if ($result->num_rows() > 0) {
            $res = $result->result_array();
            return $res[0];
        }
    }

    function getApplicantNotes($app_id)
    {
        $this->db->select('*');
        $this->db->select("DATE_FORMAT(insert_date, '%b %d %Y %H:%i %a') AS insert_date");
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('portal_misc_notes')->result_array();
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL)
    {
        $this->db->where('applicant_job_sid', $app_id);

        if ($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $this->db->from('portal_applicant_rating');
        $rows = $this->db->count_all_results();

        //        echo $this->db->last_query(); exit;
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $data = $this->db->get('portal_applicant_rating')->result_array();
            return round($data[0]['rating'] / $rows, 2);
        }
    }

    function getApplicantAllRating($app_id, $users_type = NULL, $date = NULL)
    {
        $this->db->select('portal_applicant_rating.*, users.username, users.first_name, users.last_name');
        $this->db->select("DATE_FORMAT(date_added, '%b %d %Y %H:%i %a') AS date_added");
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->where('users_type', $users_type);

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $this->db->join('users', 'portal_applicant_rating.employer_sid=users.sid');
        $result = $this->db->get('portal_applicant_rating');

        if ($result->num_rows() > 0) {
            return $result;
        } else
            return NULL;
    }

    function get_sent_messages($to_id, $job_id)
    {
        $result = $this->db->query('SELECT * FROM `private_message`  WHERE `to_id` = "' . $to_id . '" AND `job_id` = "' . $job_id . '" AND `outbox` = 1 UNION SELECT * FROM `private_message` WHERE `from_id` = "' . $to_id . '"  AND `job_id` = "' . $job_id . '" AND `outbox` = 0 ORDER BY id DESC')->result_array();
        return $result;
    }

    function getSentMessagesForEmployees($to_id)
    {
        $result = $this->db->query('SELECT * FROM `private_message`  WHERE `to_id` = "' . $to_id . '" AND (`job_id` is null OR `job_id`="" ) AND `outbox` = 1 UNION SELECT * FROM `private_message` WHERE `from_id` = "' . $to_id . '"  AND (`job_id` is null OR `job_id`="" ) AND `outbox` = 0 ORDER BY id DESC')->result_array();
        return $result;
    }

    function getEmployerDetail($employer_id)
    {
        $this->db->where('sid', $employer_id);
        $result = $this->db->get('users')->result_array();
        return $result[0];
    }

    function getCompanyAccounts($company_id)
    {
        $args = array('parent_sid' => $company_id, 'active' => 1, 'career_page_type' => 'standard_career_site');
        $this->db->select('sid,username,email,first_name,last_name,access_level,is_executive_admin,timezone');
        //$this->db->where('is_executive_admin', 0);
        $res = $this->db->get_where('users', $args);
        $ret = $res->result_array();
        return $ret;
    }

    function getApplicantEvents($employer_id, $applicant_id, $users_type, $date = NULL)
    {
        $this->db->select('*');
        $this->db->where('companys_sid', $employer_id);
        $this->db->where('applicant_job_sid', $applicant_id);
        $this->db->where('users_type', $users_type);

        if ($date != null) {
            $this->db->where('created_on >', $date);
        }

        $this->db->order_by('created_on', 'DESC');

        $records_obj = $this->db->get('portal_schedule_event');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $record) {
                if (!empty($record['interviewer'])) {
                    $this->db->select('first_name');
                    $this->db->select('last_name');
                    $this->db->select('access_level');
                    $this->db->select('is_executive_admin');
                    $this->db->where_in('sid', explode(',', $record['interviewer']));

                    $users_obj = $this->db->get('users');
                    $users_arr = $users_obj->result_array();
                    $users_obj->free_result();

                    $records_arr[$key]['participants'] = $users_arr;
                } else {
                    $records_arr[$key]['participants'] = array();
                }
            }
        }
        return $records_arr;
    }

    function getCountryName($country_id)
    {
        $res = $this->db->get_where('countries', array('sid' => $country_id));
        if ($res->num_rows() > 0) {
            $ret = $res->result_array();
            return $ret[0]["country_name"];
        } else
            return "";
    }

    function getStateName($state_id)
    {
        $res = $this->db->get_where('states', array(
            'sid' => $state_id
        ));

        if ($res->num_rows() > 0) {
            $ret = $res->result_array();
            return $ret[0]["state_name"];
        } else
            return "";
    }

    function get_all_jobs($employer_id)
    {
        $this->db->select('sid,Title');
        $this->db->where('user_sid', $employer_id);
        return $this->db->get('portal_job_listings');
    }

    function check_assign_offer_letter($company_sid, $user_type, $user_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('status', 1);
        $this->db->from('documents_assigned');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_kpa_email_sent_count($company_sid, $applicant_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        return $this->db->get("outsource_onboarding_emails")->num_rows();
    }

    function check_applicant_email_exist($app_id, $company_sid, $email)
    {
        return check_is_employee_exist_or_transfer($company_sid, $app_id, $email);
    }

    function update_private_message_to_id($job_id, $email, $data)
    {
        $this->db->where('to_id', $email)
            ->where('job_id', $job_id)
            ->update('private_message', $data);
    }

    function update_private_message_from_id($job_id, $email, $data)
    {
        $this->db->where('from_id', $email)
            ->where('job_id', $job_id)
            ->update('private_message', $data);
    }

    function update_applicant($id, $data)
    {
        $this->db->where('sid', $id);
        $this->db->update('portal_job_applications', $data);
    }

    function upload_extra_attachments($user_data)
    {
        $this->db->insert('portal_applicant_attachments', $user_data);
    }

    function insertNote($employers_sid, $applicant_job_sid, $applicant_email, $notes, $attachment, $attachment_extension, $employee_sid = 0)
    {
        $now = date('Y-m-d H:i:s');
        $args = array('employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now, 'attachment' => $attachment, 'attachment_extension' => $attachment_extension, 'insert_sid' => $employee_sid);
        $this->db->insert('portal_misc_notes', $args);
    }

    function updateNote($sid, $employers_sid, $applicant_job_sid, $applicant_email, $notes, $attachment, $attachment_extension)
    {
        $now = date('Y-m-d H:i:s');
        $args = array('employers_sid' => $employers_sid, 'applicant_job_sid' => $applicant_job_sid, 'applicant_email' => $applicant_email, 'notes' => $notes, 'insert_date' => $now, 'attachment' => $attachment, 'attachment_extension' => $attachment_extension);
        $this->db->where(array('sid' => $sid))->update('portal_misc_notes', $args);
    }

    function updateRightNotes($sid, $update_array)
    {
        $this->db->where(array('sid' => $sid))->update('portal_misc_notes', $update_array);
    }

    function user_data_by_id($sid)
    {
        $this->db->from('users');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $query_result = $this->db->get();

        if ($query_result->num_rows() > 0) {
            $row = $query_result->row_array();
            return $row;
        }
    }

    function getApplicantData($app_id)
    {
        //
        $args = array('portal_job_applications.sid' => $app_id);
        //
        $res = $this->db
            ->get_where('portal_job_applications', $args);
        $ret = $res->row_array();

        //
        if ($ret) {
            // get last job
            $lastJob = $this->db
                ->select('
                portal_applicant_jobs_list.desired_job_title,
                portal_job_listings.Title
            ')
                ->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left')
                ->where('portal_applicant_jobs_list.portal_job_applications_sid', $ret['sid'])
                ->order_by('portal_applicant_jobs_list.sid', 'desc')
                ->get('portal_applicant_jobs_list')
                ->row_array();
            //
            $ret['job_title'] = $lastJob && $lastJob['Title'] ? $lastJob['Title'] : $lastJob['desired_job_title'];
        }

        return $ret;
    }

    function getApplicantRating($app_id, $employer_id, $users_type = NULL, $date = NULL)
    {
        $this->db->where('applicant_job_sid', $app_id);
        $this->db->where('employer_sid', $employer_id);

        if ($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $result = $this->db->get('portal_applicant_rating');

        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data[0];
        }
    }

    function save_message($data)
    {
        $data['outbox'] = 1;
        $this->db->insert('private_message', $data);
        $data['outbox'] = 0;
        $this->db->insert('private_message', $data);
    }

    function update_Event($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_schedule_event', $data);
    }

    function saveEvent($data)
    {
        $data['created_on'] = date('Y-m-d H:i:s');
        $this->db->insert('portal_schedule_event', $data);
    }

    function get_portal_detail($employer_id)
    {
        $this->db->where('user_sid', $employer_id);
        $result = $this->db->get('portal_employer')->result_array();
        return $result[0];
    }

    function save_email_logs($data)
    {
        $this->db->insert('email_log', $data);
    }

    function deleteEvent($sid)
    {
        $this->db->delete('portal_schedule_event', array(
            'sid' => $sid
        ));
    }

    function updateVerificationKey($applicant_sid, $verification_key)
    {
        $this->db->where('sid', $applicant_sid);
        $data = array(
            'verification_key' => $verification_key
        );

        $this->db->update('portal_job_applications', $data);
    }

    public function check_whether_table_exists($template_code, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('template_code', $template_code);
        $this->db->where('company_sid', $company_sid);
        $template = $this->db->get('portal_email_templates')->result_array();
        return $template;
    }

    function deleteMessage($sid)
    {
        $this->db->delete('private_message', array(
            'id' => $sid
        ));
    }

    function getMessageDetail($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->get('private_message')->result_array();
        return $result[0];
    }

    function delete_note($id)
    {
        $this->db->where('sid', $id);
        $this->db->delete('portal_misc_notes');
    }

    function set_applicant_approval_status($company_sid, $applicant_sid, $status, $status_by, $reason = null, $status_type = null, $reason_response = null, $job_sid = 0)
    {
        $data = array();
        $data['approval_status'] = $status;
        $data['approval_by'] = $status_by;
        $data['approval_date'] = date('Y-m-d H:i:s');

        if ($reason != null) {
            $data['approval_status_reason'] = $reason;
        }

        if ($status_type != null) {
            $data['approval_status_type'] = $status_type;
        }

        if ($reason_response == null || $reason_response == '') {
            $data['approval_status_reason_response'] = '';
        } else {
            $data['approval_status_reason_response'] = $reason_response;
        }

        if ($job_sid > 0) {
            $this->db->where('job_sid', $job_sid);
        }

        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->update('portal_applicant_jobs_list', $data);
    }

    function insert_applicant_approval_history_record($company_sid, $employer_sid, $applicant_sid, $approval_status, $approval_status_type, $approval_status_date, $approval_status_text)
    {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = $employer_sid;
        $data_to_insert['applicant_sid'] = $applicant_sid;
        $data_to_insert['approval_status'] = $approval_status;
        $data_to_insert['approval_status_type'] = $approval_status_type;
        $data_to_insert['approval_status_date'] = $approval_status_date;
        $data_to_insert['approval_status_text'] = $approval_status_text;
        $this->db->insert('applicants_approval_status_history', $data_to_insert);
    }

    function get_all_users_with_approval_rights($company_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('has_job_approval_rights', 1);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->from('users');
        return $this->db->get()->result_array();
    }

    function get_single_applicant($applicant_id, $job_sid)
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
        $this->db->select('portal_job_listings.Title as job_title');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_id);
        $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $result = $this->db->get('portal_applicant_jobs_list')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_kpa_onboarding($company_sid)
    {
        return $this->db->get_where('kpa_onboarding', array('company_sid' => $company_sid))->row_array();
    }

    function save_onboarding_email_record($data)
    {
        $this->db->insert('outsource_onboarding_emails', $data);
    }

    function delete_file($id, $type)
    {
        if ($type == 'file') {
            $this->db->where('sid', $id);
            $this->db->update('portal_applicant_attachments', array('status' => 'deleted'));
        } else {
            $this->db->where('sid', $id);
            $this->db->update('portal_job_applications', array($type => NULL));
        }
    }

    function save_rating($data)
    {
        $applicant_job_sid = $data['applicant_job_sid'];
        $employer_sid = $data['employer_sid'];
        $users_type = $data['users_type'];
        $this->db->where('applicant_job_sid', $applicant_job_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('users_type', $users_type);
        $this->db->from('portal_applicant_rating');
        $count = $this->db->count_all_results();
        if ($data['source_type'] == '') $data['source_type'] = 'no_video';

        if ($count > 0) {
            unset($data['applicant_job_sid']);
            unset($data['employer_sid']);
            unset($data['users_type']);

            $this->db->where('applicant_job_sid', $applicant_job_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where('users_type', $users_type);

            $data['date_added'] = date('Y-m-d H:i:s');
            $this->db->update('portal_applicant_rating', $data);
        } else {
            $data['date_added'] = date('Y-m-d H:i:s');
            $this->db->insert('portal_applicant_rating', $data);
        }
    }

    function update_applicant_status($company_sid, $applicant_job_list_sid, $status_sid, $status_name)
    {
        $data_to_update = array();
        $data_to_update['status'] = $status_name;
        $data_to_update['status_sid'] = $status_sid;
        $data_to_update['status_change_date'] = date('Y-m-d H:i:s');

        //
        $dataSession['session'] = $this->session->userdata('logged_in');
        $employers_details  = $dataSession['session']['employer_detail'];
        $employer_sid       = $employers_details['sid'];
        $data_to_update['status_change_by'] = $employer_sid;


        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $applicant_job_list_sid);
        $this->db->update('portal_applicant_jobs_list', $data_to_update);
    }

    function change_current_status($id, $status, $company_sid, $table)
    {
        $have_status = $this->have_status_records($company_sid);

        $data = array(
            'status' => $status
        );

        if ($have_status == true) {
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            //$this->db->where('LOWER(name)', strtolower($status));
            $this->db->where('name', $status);
            $status_sid = $this->db->get('application_status')->result_array();

            if (!empty($status_sid)) {
                $status_sid = $status_sid[0]['sid'];
            } else {
                $status_sid = 0;
            }

            $data['status_sid'] = $status_sid;
        }

        if ($table == 'portal_applicant_jobs_list') {
            $data['status_change_date'] = date('Y-m-d H:i:s');

            $dataSession['session'] = $this->session->userdata('logged_in');
            $employers_details  = $dataSession['session']['employer_detail'];
            $employer_sid       = $employers_details['sid'];
            $data['status_change_by'] = $employer_sid;
        }

        $this->db->where('sid', $id);
        $this->db->update($table, $data);
    }

    function arch_single_applicant($id)
    {
        $data = array(
            'archived' => 1
        );
        $this->db->where('sid', $id);
        $this->db->update('portal_applicant_jobs_list', $data);
    }

    function delete_single_applicant($id)
    {
        $this->db->where('sid', $id);
        //$this->db->where('hired_status', 0);
        $this->db->delete('portal_applicant_jobs_list');
    }

    function get_job_details($job_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $job_sid);
        $result = $this->db->get('portal_job_listings')->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function get_single_applicant_all_jobs($applicant_sid, $company_sid)
    {
        //$this->db->select('sid, job_sid, desired_job_title, applicant_type, date_applied, score, passing_score, status, questionnaire, status_sid');
        $this->db->select('portal_applicant_jobs_list.*');
        $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_sid);
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->select('portal_job_listings.interview_questionnaire_sid');
        $this->db->select('portal_job_listings.questionnaire_sid');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_Country');
        $this->db->select('portal_job_listings.Location_ZipCode');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        $applicants = $this->db->get('portal_applicant_jobs_list')->result_array();
        $have_status = $this->have_status_records($company_sid);

        foreach ($applicants as $key => $applicant) {
            switch ($applicant['applicant_type']) {
                case 'Applicant':
                    $job_title = !empty($applicant['Title']) ? $applicant['Title'] : 'Job Not Applied';
                    $applicants[$key]['job_title'] = $job_title;
                    break;
                case 'Manual Candidate':
                case 'Imported Resume':
                    if ($applicant['job_sid'] > 0) {
                        $job_title = !empty($applicant['Title']) ? $applicant['Title'] : 'Job Not Applied';
                        $applicants[$key]['job_title'] = $job_title;
                    } else if (!empty($applicant['desired_job_title'])) {
                        $applicants[$key]['job_title'] = $applicant['desired_job_title'];
                    } else {
                        $applicants[$key]['job_title'] = 'Job Not Applied';
                    }
                    break;
                case 'Talent Network':
                case 'Job Fair':
                    if (!empty($applicant['desired_job_title'])) {
                        $applicants[$key]['job_title'] = $applicant['desired_job_title'];
                    } else {
                        $applicants[$key]['job_title'] = 'Job Not Applied';
                    }
                    break;
            }

            if ($have_status == true) {
                $status_sid = 1;
                if ($applicant['status_sid'] != 0) {
                    $status_sid = $applicant['status_sid'];
                }
                $applicant_status = $this->get_applicant_status($status_sid);

                if (isset($applicant_status[0])) {
                    $applicants[$key]['status_name'] = $applicant_status[0]['name'];
                    $applicants[$key]['status_css_class'] = $applicant_status[0]['css_class'];
                    $applicants[$key]['status_text_css_class'] = $applicant_status[0]['text_css_class'];
                    $applicants[$key]['status_type'] = $applicant_status[0]['status_type'];
                    $applicants[$key]['bar_bgcolor'] = $applicant_status[0]['bar_bgcolor'];
                }
            }

            $questionnaire_manual_sent = $applicant['questionnaire_manual_sent']; //get resent applicants history

            if ($questionnaire_manual_sent == 1) {
                $manual_applicant_sid = $applicant['sid'];
                $manual_applicant_jobs_list_sid = $applicant['portal_job_applications_sid'];
                $applicants[$key]['manual_questionnaire_history'] = $this->screening_questionnaire_manual_sent_tracking($manual_applicant_sid, $manual_applicant_jobs_list_sid);
            } else {
                $applicants[$key]['manual_questionnaire_history'] = array();
            }
        }

        return $applicants;
    }

    function deleteTalentUser($id)
    {
        $this->db->delete('portal_join_network', array('sid' => $id));
    }

    function mc_decline_applicant($id, $table)
    {
        $data = array('status' => 'Client Declined');
        $this->db->where('sid', $id);
        $this->db->update($table, $data);
    }

    function mc_hire_applicant($id, $table)
    {
        $data = array('status' => 'Placed/Hired');
        $this->db->where('sid', $id);
        $this->db->update($table, $data);
    }

    function mc_delete_applicant($id)
    {
        $this->db->where('sid', $id);
        $this->db->delete('portal_manual_candidates');
    }

    function delete_applicant($id)
    {
        $this->db->where('sid', $id);
        //$this->db->where('hired_status', 0);
        $this->db->delete('portal_applicant_jobs_list');
    }

    function get_job_title_by_type($job_sid, $applicant_type, $desired_job_title)
    {
        $job_title = '';

        if ($applicant_type == 'Applicant') {
            $job_title = get_job_title($job_sid);
        } else if ($applicant_type == 'Talent Network' || $applicant_type == 'Imported Resume' || $applicant_type == 'Job Fair') {
            if ($desired_job_title != NULL && $desired_job_title != '') {
                $job_title = $desired_job_title;
            } else {
                $job_title = 'Job Not Applied';
            }
        } else if ($applicant_type == 'Manual Candidate') {
            if ($job_sid != 0) {
                $job_title = get_job_title($job_sid);
            } else {
                if ($desired_job_title != NULL && $desired_job_title != '') {
                    $job_title = $desired_job_title;
                } else {
                    $job_title = 'Job Not Applied';
                }
            }
        }

        return $job_title;
    }

    function get_applicant_multiple_job_count($applicant_sid, $company_sid)
    {
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->from('portal_applicant_jobs_list');
        $jobs = $this->db->count_all_results();

        if ($jobs > 1) {
            return 'Yes';
        } else {
            return 'No';
        }
    }

    function check_applicant_access($applicant_sid, $company_sid, $employer_sid)
    {
        $this->db->select('*'); // first check if applicant is assigned to me
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('employer_sid', $employer_sid);
        $this->db->or_where('assigned_by_sid', $employer_sid);
        $this->db->group_end();
        $applicant_found = $this->db->get('assignment_management')->num_rows();

        if ($applicant_found > 0) {
            return true;
        } // it is not assigned by me or to me therefore please continue to function

        $is_admin = is_admin($employer_sid);
        $this->db->select('portal_job_applications.sid as sid');
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($is_admin == FALSE) {
            $this->db->where('portal_job_listings_visibility.company_sid', $company_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employer_sid);
            $this->db->join('portal_job_listings_visibility', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        }

        $allowed_applicants = $this->db->get('portal_job_applications')->result_array();

        foreach ($allowed_applicants as $key => $value) {
            if ($applicant_sid == $value['sid']) {
                return true;
            }
        }

        return false;
    }

    function check_applicant_exists($applicant_sid, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->from('portal_applicant_jobs_list');
        $applicant_found = $this->db->count_all_results();

        if ($applicant_found > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_applicants_order($employer_sid, $ats_params = '')
    {
        $param_array = explode('/', $ats_params);

        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            $is_archive = isset($param_array[3]) ? $param_array[3] : 'all';
            $keywords = isset($param_array[4]) ? urldecode($param_array[4]) : 'all';
            $job_sid = isset($param_array[5]) ? $param_array[5] : 'all';
            $status = isset($param_array[6]) ? urldecode($param_array[6]) : 'all';
            $status = str_replace("_", " ", $status);
            $status = ucwords($status);
            $job_fit_category_sid = isset($param_array[7]) ? $param_array[7] : 'all';
        } else {
            $is_archive = isset($param_array[2]) ? $param_array[2] : 'all';
            $keywords = isset($param_array[3]) ? urldecode($param_array[3]) : 'all';
            $job_sid = isset($param_array[4]) ? $param_array[4] : 'all';
            $status = isset($param_array[5]) ? urldecode($param_array[5]) : 'all';
            $status = str_replace("_", " ", $status);
            $status = ucwords($status);
            $job_fit_category_sid = isset($param_array[6]) ? $param_array[6] : 'all';
        }

        $data['session'] = $this->session->userdata('logged_in');
        $employee_access_level = $data['session']['employer_detail']['access_level'];
        $employee_sid = $data['session']['employer_detail']['sid'];
        $this->db->select('portal_job_applications.sid as sid');
        $this->db->select('portal_applicant_jobs_list.sid as job_list_sid');
        $this->db->where('portal_job_applications.employer_sid', $employer_sid);

        if ($is_archive == 'archive') {
            $this->db->where('portal_applicant_jobs_list.archived', 1);
        } else {
            $this->db->where('portal_applicant_jobs_list.archived', 0);
        }

        $this->db->where('portal_job_applications.hired_status', 0);

        if (!empty($keywords) && $keywords != 'all') {
            $this->filtration($keywords);
        }

        if (!empty($job_sid) && $job_sid != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
        }

        if (!empty($status) && $status != 'all' && $status != 'All') {
            $this->db->where('portal_applicant_jobs_list.status', $status);
        }

        if (!empty($job_fit_category_sid) && $job_fit_category_sid != 'all') {
            $this->db->where('FIND_IN_SET(' . $job_fit_category_sid . ', `portal_job_applications`.`job_fit_category_sid`)');
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid');

        if ($employee_access_level != 'Admin') {
            $this->db->where('portal_job_listings_visibility.company_sid', $employer_sid);
            $this->db->where('portal_job_listings_visibility.employer_sid', $employee_sid);
            $this->db->join('portal_job_listings_visibility', 'portal_applicant_jobs_list.job_sid = portal_job_listings_visibility.job_sid');
        }

        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function next_applicant($application_id, $employer_sid, $is_archived = false)
    {
        if ($is_archived == true) {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid > $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 1  ORDER BY `sid` ASC LIMIT 1");
        } else {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid > $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 ORDER BY `sid` ASC LIMIT 1");
        }

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function previous_applicant($application_id, $employer_sid, $is_archived = false)
    {
        if ($is_archived == true) {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid < $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 and `archived` = 1 ORDER BY `sid` DESC LIMIT 1");
        } else {
            $data = $this->db->query("SELECT `sid` FROM `portal_job_applications` WHERE sid < $application_id and `employer_sid` = $employer_sid and `hired_status` = 0 ORDER BY `sid` DESC LIMIT 1");
        }

        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0];
        }
    }

    function getApplicantExtraAttachments($app_id, $employer_id, $users_type = NULL)
    {
        $result = $this->db->get_where('portal_applicant_attachments', array(
            'applicant_job_sid' => $app_id,
            /* 'employer_sid' => $employer_id, */  //This  brings only Employer Specific Files
            'users_type' => $users_type
        ));
        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data;
        }
    }

    function get_job_categories($category_sid)
    {
        if ($category_sid != '') {
            $this->db->select('value');
            $this->db->where('field_sid', 198);
            $this->db->where('sid IN ( ' . $category_sid . ' )');
            $category_info = $this->db->get('listing_field_list')->result_array();
            return $category_info;
        } else {
            return array();
        }
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

    function get_status_by_company($company_sid)
    {
        $this->db->select('sid, name, css_class, status_order');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('application_status')->result_array();
    }

    function get_min_applicant_id($company_name)
    {
        $data = $this->db->query("SELECT MIN(sid) as sid FROM `portal_job_applications` where `employer_sid` = $company_name and `hired_status` = 0 ");
        $data = $data->result_array();
        return $data[0]['sid'];
    }

    function get_max_applicant_id($company_name)
    {
        $data = $this->db->query("SELECT MAX(sid) as sid FROM `portal_job_applications` where `employer_sid` = $company_name and `hired_status` = 0 ");
        $data = $data->result_array();
        return $data[0]['sid'];
    }

    function get_all_applicants_by_approval_status($company_sid, $status, $applicant_id = null)
    {
        $this->db->select('portal_applicant_jobs_list.*');
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
        $this->db->select('users.first_name as approver_fname');
        $this->db->select('users.last_name as approver_lname');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->where('portal_applicant_jobs_list.approval_status', $status);

        if ($applicant_id != null) {
            $this->db->where('portal_applicant_jobs_list.portal_job_applications_sid', $applicant_id);
        }

        $this->db->group_by('portal_job_applications.sid');
        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $this->db->join('users', 'users.sid = portal_applicant_jobs_list.approval_by', 'left');
        return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_employer_details($employer_sid)
    {
        $this->db->select('first_name,last_name,email');
        $this->db->where('sid', $employer_sid);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $data_from_db = $this->db->get('users')->result_array();

        if (!empty($data_from_db)) {
            return $data_from_db[0];
        } else {
            return array();
        }
    }

    function get_portal_applicant_jobs_list_record($applicant_sid, $company_sid, $job_sid)
    {
        $this->db->select('*');
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('job_sid', $job_sid);
        $return_data = $this->db->get('portal_applicant_jobs_list')->result_array();

        if (!empty($return_data)) {
            $return_data = $return_data[0];
            return $return_data;
        } else {
            return array();
        }
    }

    function reset_applicant_for_approval($company_sid, $employer_sid, $applicant_sid, $approval_status_reason_response, $job_sid)
    {
        $dataToUpdate = array();
        $dataToUpdate['approval_status_reason_response'] = $approval_status_reason_response;
        $dataToUpdate['approval_status'] = 'pending';
        $dataToUpdate['approval_status_type'] = 're_request';
        $dataToUpdate['approval_by'] = $employer_sid;
        $dataToUpdate['approval_date'] = date('Y-m-d H:i:s');

        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $this->db->where('job_sid', $job_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->update('portal_applicant_jobs_list', $dataToUpdate);
    }

    function check_sent_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->limit(1);
        $data = $this->db->get('video_interview_questions_sent')->result_array();

        if (!empty($data)) {
            return true;
        } else {
            return false;
        }
    }

    function check_answered_video_questionnaires($applicant_sid, $company_sid)
    {
        $this->db->where('video_interview_questions_sent.applicant_sid', $applicant_sid);
        $this->db->where('video_interview_questions_sent.company_sid', $company_sid);
        $this->db->where('video_interview_questions_sent.status', 'answered');
        $this->db->join('video_interview_questions', 'video_interview_questions_sent.question_sid = video_interview_questions.sid');
        $data = $this->db->get('video_interview_questions_sent')->num_rows();

        if ($data > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_applicant_video_interview_rating($company_sid, $applicant_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('rating_type', 'rating');
        $this->db->order_by('date_added', 'DESC');
        $this->db->group_by('employer_sid');
        $ratings_obj = $this->db->get('video_interview_questions_rating');
        $ratings_arr = $ratings_obj->result_array();
        $ratings_obj->free_result();
        return $ratings_arr;
    }

    function generate_applicants_counts($records_arr)
    {
        $result = array();
        $applicant = 0;
        $manual = 0;
        $talent = 0;
        $job_fair = 0;
        //         echo "<pre>";
        //         print_r($records_arr);
        //         exit;
        if (!empty($records_arr)) {
            for ($i = 0; $i < count($records_arr); $i++) {
                $applicant_type = $records_arr[$i]['applicant_type'];
                switch ($applicant_type) {
                    case 'Applicant':
                        $applicant++;
                        break;
                    case 'Manual Candidate':
                        $manual++;
                        break;
                    case 'Talent Network':
                        $talent++;
                        break;
                    case 'Job Fair':
                        $job_fair++;
                        break;
                }
            }

            $result = array(
                'all_job_applicants' => $applicant,
                'all_manual_applicants' => $manual,
                'all_talent_applicants' => $talent,
                'all_job_fair_applicants' => $job_fair
            );
        }
        return $result;
    }

    function get_all_applicants_assigned_to_me($company_sid, $employer_sid)
    {
        $this->db->select('applicant_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $applicants_obj = $this->db->get('assignment_management');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();
        $return_data = array();

        foreach ($applicants_arr as $applicant) {
            $return_data[] = $applicant['applicant_sid'];
        }

        return $return_data;
    }

    function get_all_applicants_assigned_by_me($company_sid, $employer_sid)
    {
        $this->db->select('applicant_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('assigned_by_sid', $employer_sid);
        $applicants_obj = $this->db->get('assignment_management');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();
        $return_data = array();

        foreach ($applicants_arr as $applicant) {
            $return_data[] = $applicant['applicant_sid'];
        }

        return $return_data;
    }

    function check_assignment_management($applicant_sid, $company_sid, $employer_sid)
    {
        $this->db->select('sid');
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('employer_sid', $employer_sid);
        $this->db->or_where('assigned_by_sid', $employer_sid);
        $this->db->group_end();
        $applicants_obj = $this->db->get('assignment_management');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();

        if (empty($applicants_arr)) {
            return 'not_assigned';
        } else {
            return 'assigned';
        }
    }

    function get_interview_questionnaires($company_sid)
    {
        $this->db->select('sid');
        $this->db->select('title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'active');

        $records_obj = $this->db->get('interview_questionnaires');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_interview_questionnaires_scores($applicant_sid)
    {
        $this->db->select('interview_questionnaires.title');
        $this->db->select('interview_questionnaire_score.questionnaire_sid');
        $this->db->distinct('interview_questionnaire_score.questionnaire_sid');
        $this->db->where('interview_questionnaire_score.questionnaire_sid >', 0);
        $this->db->where('interview_questionnaire_score.candidate_sid', $applicant_sid);
        $this->db->join('interview_questionnaires', 'interview_questionnaires.sid = interview_questionnaire_score.questionnaire_sid', 'left');

        $records_obj = $this->db->get('interview_questionnaire_score');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function is_onboarding_configured($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('onboarding_getting_started_sections');
        $getting_started_count = $this->db->count_all_results();

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('onboarding_office_locations');
        $office_locations_count = $this->db->count_all_results();

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('onboarding_office_timings');
        $office_timings_count = $this->db->count_all_results();

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('onboarding_people_to_meet');
        $people_count = $this->db->count_all_results();

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('onboarding_useful_links');
        $links_count = $this->db->count_all_results();


        if (
            $getting_started_count > 0 ||
            $office_locations_count > 0 ||
            $office_timings_count > 0 ||
            $people_count > 0 ||
            $links_count > 0
        ) {
            return true;
        } else {
            return false;
        }
    }

    function get_onboarding_status($company_sid, $applicant_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->from('onboarding_applicants');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_portal_email_templates($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_custom', 1);
        $records_obj = $this->db->get('portal_email_templates');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function screening_questionnaire_manual_sent_tracking($applicant_sid, $applicant_jobs_list_sid)
    {
        $this->db->select('*');
        $this->db->where('applicant_sid', $applicant_sid);
        $this->db->where('applicant_jobs_list_sid', $applicant_jobs_list_sid);
        $this->db->order_by('sid', 'DESC');
        $records_obj = $this->db->get('screening_questionnaire_manual_sent_tracking');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    // deprecated
    // at: 26-03-2019
    function get_applicant_events($applicant_sid, $events_date = null)
    {
        $this->db->select('*');
        $this->db->select('eventstarttime as event_start_time');
        $this->db->select('date as event_date');
        $this->db->select('eventendtime as event_end_time');
        $this->db->where('applicant_job_sid', $applicant_sid);
        $this->db->where('users_type', 'applicant');
        $today = date('Y-m-d');

        if ($events_date == 'upcoming') {
            $this->db->where('date >=', $today);
            $this->db->order_by('date', 'ASC');
        } else if ($events_date == 'past') {
            $this->db->where('date <', $today);
            $this->db->order_by('date', 'DESC');
        } else if ($events_date !== null) {
            $this->db->where('date', $today);
        }

        $records_obj = $this->db->get('portal_schedule_event');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach ($records_arr as $key => $record) {
            $external_participants = $this->get_event_external_participants($record['sid']);
            $record['external_participants'] = $external_participants;

            $records_arr[$key] = $record;
            reset_event_datetime($records_arr[$key], $this);
        }

        return $records_arr;
    }

    public function get_company_addresses($company_sid)
    {
        return $this->fetch_company_addresses($company_sid);
        $this->db->select('address');
        $this->db->group_by('address');
        $this->db->where('companys_sid', $company_sid);

        $records_obj = $this->db->get('portal_schedule_event');
        $records_events_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('Location_Address');
        $this->db->where('sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_users_arr = $records_obj->result_array();
        $records_obj->free_result();

        $this->db->select('address');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('company_addresses_locations');
        $records_addresses_arr = $records_obj->result_array();
        $records_obj->free_result();

        $addresses = array();

        /*
          foreach($records_events_arr as $key => $address){
          if(!empty($address['address'])){
          if(!in_array($address['address'], $addresses)) {
          $addresses[] = $address['address'];
          }
          }
          }
         */

        foreach ($records_users_arr as $key => $address) {
            if (!empty($address['Location_Address'])) {
                if (!in_array($address['Location_Address'], $addresses)) {
                    $addresses[] = $address['Location_Address'];
                }
            }
        }

        foreach ($records_addresses_arr as $key => $address) {
            if (!empty($address['address'])) {
                if (!in_array($address['address'], $addresses)) {
                    $addresses[] = $address['address'];
                }
            }
        }


        return $addresses;
    }

    public function get_event_external_participants($event_sid)
    {
        $this->db->where('event_sid', $event_sid);
        $this->db->from('portal_schedule_event_external_participants');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function get_questionnaire_average_score($company_sid, $user_sid, $user_type, $job_sid)
    {
        // Please note: This function is also added in Interview Questionnaires Model
        // therefore if you modify anything in it then please apply same changes at other model too
        $my_return = array();
        $candidate_scores = $this->get_questionnaire_score_data_candidate_specific($company_sid, $user_sid, $user_type, $job_sid, 0);
        $candidate_total_score = 0;
        $job_relevancy_total_score = 0;
        $overall_score = 0;
        $total_star_rating = 0;
        $candidate_average_score = 0;
        $job_relevancy_average_score = 0;
        $overall_average_score = 0;
        $average_star_rating = 0;

        if (!empty($candidate_scores)) {
            foreach ($candidate_scores as $key => $score) {
                $candidate_total_score += $score['candidate_score'];
                $job_relevancy_total_score += $score['job_relevancy_score'];
                $overall_score += $score['candidate_overall_score'] + $score['job_relevancy_overall_score'];
                $total_star_rating += $score['star_rating'];
            }
        }

        $score_count = count($candidate_scores);

        if ($score_count > 0) {
            $candidate_average_score = $candidate_total_score / $score_count;
            $job_relevancy_average_score = $job_relevancy_total_score / $score_count;
            $overall_average_score = (($overall_score * 10) / 2) / $score_count;
            $average_star_rating = $total_star_rating / $score_count;
        }

        $my_return['candidate_score'] = $candidate_average_score;
        $my_return['job_relevancy_score'] = $job_relevancy_average_score;
        $my_return['overall_score'] = $overall_average_score;
        $my_return['star_rating'] = $average_star_rating;

        return $my_return;
    }

    public function get_questionnaire_score_data_candidate_specific($company_sid, $candidate_sid, $candidate_type, $job_sid, $questionnaire_sid)
    {
        // Please note: This function is also added in Interview Questionnaires Model
        // therefore if you modify anything in it then please apply same changes at other model too
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('candidate_sid', $candidate_sid);
        $this->db->where('candidate_type', $candidate_type);

        if ($questionnaire_sid > 0) {
            $this->db->where('questionnaire_sid', $questionnaire_sid);
        }

        if ($job_sid > 0) {
            $this->db->where('job_sid', $job_sid);
        }

        $questionnaire_score_data = $this->db->get('interview_questionnaire_score')->result_array();

        if (!empty($questionnaire_score_data)) {
            foreach ($questionnaire_score_data as $key => $score) {
                $this->db->select('first_name, last_name, job_title');
                $this->db->where('sid', $score['employer_sid']);
                $employer_data = $this->db->get('users')->result_array();

                if (!empty($employer_data)) {
                    $questionnaire_score_data[$key]['scored_by'] = $employer_data[0];
                } else {
                    $questionnaire_score_data[$key]['scored_by'] = array();
                }
            }
        }

        return $questionnaire_score_data;
    }

    function job_fair_configuration($company_sid)
    {
        $this->db->select('status');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) {
            return 0;
        } else {
            return $record_arr[0]['status'];
        }
    }

    function get_status_sid($company_sid, $status)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('LOWER(name)', strtolower($status));
        $record_obj = $this->db->get('application_status');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) {
            return 0;
        } else {
            return $record_arr[0]['sid'];
        }
    }

    function get_applicant_unique_sid($company_sid, $applicant_sid)
    {
        $this->db->select('unique_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('applicant_sid', $applicant_sid);

        $record_obj = $this->db->get('onboarding_applicants');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $unique_sid = $record_arr[0]['unique_sid'];
            return $unique_sid;
        } else {
            return 0;
        }
    }

    function get_company_detail($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_all_questionnaires_by_employer($employer_sid, $type = '')
    {
        $this->db->select('portal_screening_questionnaires.sid,portal_screening_questionnaires.name,count(portal_questions.sid) as que_count');
        $this->db->where('employer_sid', $employer_sid);
        if ($type != '') {
            $this->db->where('type', $type);
        }

        $this->db->order_by("sid", "desc");
        $this->db->join('portal_questions', 'portal_questions.questionnaire_sid = portal_screening_questionnaires.sid', 'left')->group_by('portal_questions.questionnaire_sid');
        return $this->db->get('portal_screening_questionnaires')->result_array();
    }

    /**
     * get applicant events
     * for calendars
     *
     * @param $applicant_sid Integer
     * @param $event_date String Optional
     *
     * @return Array|Bool
     */
    function fetch_applicant_events($applicant_sid, $events_date = null)
    {
        // fetch all events
        $this->db
            ->select('sid')
            ->select('applicant_email')
            ->select('users_phone')
            ->select('title')
            ->select('category')
            ->select('description')
            ->select('eventstarttime')
            ->select('eventendtime')
            ->from('portal_schedule_event')
            ->where('applicant_job_sid', $applicant_sid)
            ->where('users_type', 'applicant');

        $today = date('Y-m-d');

        if ($events_date == 'upcoming') {
            $this->db->where('date >=', $today);
            $this->db->order_by('date', 'ASC');
        } else if ($events_date == 'past') {
            $this->db->where('date <', $today);
            $this->db->order_by('date', 'DESC');
        } else if ($events_date !== null) {
            $this->db->where('date', $today);
        }

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj = $records_obj->free_result();

        if (!sizeof($records_arr)) return false;

        foreach ($records_arr as $key => $record) {
            $external_participants = $this->get_event_external_participants($record['sid']);
            $record['external_participants'] = $external_participants;

            $records_arr[$key] = $record;
        }

        return $records_arr;
    }


    /**
     * get company accounts
     * for calendars
     *
     * @param $company_sid Integer
     *
     * @return Array|Bool
     */
    function get_company_accounts($company_sid)
    {
        // $args = array('parent_sid' => $company_id, 'active' => 1, 'career_page_type' => 'standard_career_site');
        $result = $this->db
            ->select('sid as employer_id')
            ->select('email as email_address')
            ->select('concat(first_name," ",last_name) as full_name')
            ->select('case when is_executive_admin = 1 then "Executive Admin" else access_level end as employee_type', false)
            ->where('parent_sid', $company_sid)
            ->where('active', 1)
            ->where('career_page_type', 'standard_career_site')
            ->from('users')
            ->order_by('full_name', 'ASC')
            ->get();
        // fetch result
        $result_arr = $result->result_array();
        // free result from memory
        // and flush variable data
        $result = $result->free_result();
        // return output
        return $result_arr;
    }

    /**
     * get company accounts
     * for calendars
     *
     * @param $company_sid Integer
     *
     * @return Array|Bool
     */
    function fetch_company_addresses($company_sid)
    {
        //
        $SQL2 = $this->db
            ->select('distinct(Location_Address) as address')
            ->where('sid', $company_sid)
            ->from('users')
            ->get_compiled_select();
        //
        $SQL3 = $this->db
            ->select('distinct(address)')
            ->from('company_addresses_locations')
            ->where('company_sid', $company_sid)
            ->where('status', 1)
            ->get_compiled_select();

        //
        $result = $this->db->query("$SQL2 UNION $SQL3");
        // event addresses are not included
        $result_arr = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($result_arr)) return false;
        //
        $return_array = array();
        foreach ($result_arr as $k0 => $v0) {
            $return_array[] = $v0['address'];
        }
        return $return_array;
    }

    function job_fair_forms($company_sid)
    {
        $this->db->select('sid, title, page_url');
        $this->db->where('company_sid', $company_sid);
        $record_obj_main = $this->db->get('job_fairs_recruitment');
        $result_main = $record_obj_main->result_array();
        $record_obj_main = $record_obj_main->free_result();
        $return_array = array();

        if (!empty($result_main)) {
            $main_sid = $result_main[0]['sid'];
            $main_title = $result_main[0]['title'];
            $main_page_url = $result_main[0]['page_url'];

            if ($main_page_url == '' || $main_page_url == NULL) {
                $main_page_url = md5('default_' . $main_sid);
                $this->db->set('page_url', $main_page_url); //update page url at database
                $this->db->where('sid', $main_sid);
                $this->db->update('job_fairs_recruitment');
                $return_array[] = array('sid' => $main_sid, 'title' => $main_title, 'page_url' => $main_page_url, 'form_type' => 'default');
            } else {
                $default_form = $result_main[0];
                $default_form['form_type'] = 'default';
                $return_array[] = $default_form;
            }
        }

        $this->db->select('sid, title, page_url, form_type');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('form_type', 'custom');
        $record_obj_custom = $this->db->get('job_fairs_forms');
        $result_custom = $record_obj_custom->result_array();
        $record_obj_custom = $record_obj_custom->free_result();

        if (!empty($result_custom)) {
            foreach ($result_custom as $rc) {
                $custom_sid = $rc['sid'];
                $custom_title = $rc['title'];
                $custom_page_url = $rc['page_url'];

                if ($custom_page_url == '' || $custom_page_url == NULL) {
                    $custom_page_url = md5('custom_' . $custom_sid);
                    $this->db->set('page_url', $custom_page_url); //update page url at database
                    $this->db->where('sid', $custom_sid);
                    $this->db->update('job_fairs_forms');
                    $return_array[] = array('sid' => $custom_sid, 'title' => $custom_title, 'page_url' => $custom_page_url);
                } else {
                    $return_array[] = $rc;
                }
            }
        }

        return $return_array;
    }

    /**
     * Saves data into db
     * Created on: 18-07-2019
     *
     * @param $insert_array Array
     *
     * @return Integer|Bool
     */
    function save_sent_message($insert_array)
    {
        $insert = $this->db->insert('portal_sms', $insert_array);
        return $insert ? $this->db->insert_id() : false;
    }


    /**
     * Get the sms
     * Created on: 18-07-2019
     *
     * @param $user_type String
     * @param $user_id Integer
     * @param $company_id Integer
     * @param $lastId Integer
     * @param $module String Optional
     *
     * @return Array|Bool
     */
    function fetch_sms($user_type, $user_id, $company_id, $lastId, $module = '')
    {
        $this
            ->db
            ->select('
            sid,
            message_body,
            sender_user_id,
            sender_user_type,
            IF(is_sent = "1", "sent", "received") as message_type,
            created_at
        ')
            ->from('portal_sms')
            ->group_start()
            ->where('receiver_user_id', $user_id)
            ->or_where('sender_user_id', $user_id)
            ->group_end()
            ->group_start()
            ->where('receiver_user_type', $user_type)
            ->or_where('sender_user_type', $user_type)
            ->group_end()
            ->where('company_id', $company_id)
            ->limit(100)
            ->order_by('sid', 'DESC');

        if ($lastId != 0) $this->db->where('sid <', $lastId);
        if ($module != '') $this->db->where('module_slug', $module);
        //
        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        //
        if (!sizeof($result_arr)) return false;

        // $result_arr = array_reverse($result_arr);
        //
        // $lastFetchedId = $result_arr[0]['sid'];
        foreach ($result_arr as $k0 => $v0) {
            // Fetch user name
            $this->db
                ->from($v0['sender_user_type'] != 'applicant' ? 'users' : 'portal_job_applications');
            if ($v0['sender_user_type'] == 'applicant') {
                $this->db->select('CONCAT(portal_job_applications.first_name," ",portal_job_applications.last_name) as full_name');
                $this->db->where('portal_job_applications.sid', $v0['sender_user_id']);
            } else {
                $this->db->select('CONCAT(first_name," ",last_name) as full_name');
                $this->db->where('sid', $v0['sender_user_id']);
            }
            $result = $this->db->get();
            //
            $name = $result->row_array();
            $result = $result->free_result();
            $result_arr[$k0]['full_name'] = $name['full_name'];
            // Convert datetime to current timezone
            $result_arr[$k0]['created_at'] = reset_datetime(array(
                'datetime' => $v0['created_at'],
                '_this' => $this
            ));
            //
            unset(
                $result_arr[$k0]['sid'],
                $result_arr[$k0]['sender_user_id'],
                $result_arr[$k0]['sender_user_type']
            );
            $lastFetchedId = $v0['sid'];
        }

        return array('Records' => $result_arr, 'LastId' => $lastFetchedId);
    }


    /**
     * Update applicant phone number
     * Created on: 22-07-2019
     *
     * @param $phone_number String E164
     * @param $applicant_sid Integer
     *
     * @return VOID
     */
    function applicant_phone_number($phone_number, $applicant_sid)
    {
        $this->db->where('sid', $applicant_sid)->update('portal_job_applications', array('phone_number' => $phone_number));
    }

    function insert_resume_log($resume_log_data)
    {
        $this->db->insert('resume_request_logs', $resume_log_data);
    }

    function get_single_job_detail($portal_job_applications_sid, $company_sid, $job_sid, $job_type = '')
    {
        $this->db->select('resume');

        if ($job_type == 'job') {
            $this->db->where('job_sid', $job_sid);
        } else if ($job_type == 'desired_job') {
            $this->db->where('sid', $job_sid);
        } else if ($job_type == 'job_not_applied') {
            $this->db->where('job_sid', $job_sid);
            $this->db->where('desired_job_title', NULL);
        }
        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['resume'];
        } else {
            return array();
        }
    }

    function get_single_job_detail_old($portal_job_applications_sid, $company_sid, $job_sid, $job_type = '')
    {
        if ($job_type == 'desired_job') {
            $this->db->select('sid,resume');
            $this->db->where('sid', $job_sid);
        } else {
            $this->db->select('resume');
            $this->db->where('job_sid', $job_sid);
        }
        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('portal_applicant_jobs_list');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_resume_applicant_job_list($portal_job_applications_sid, $company_sid, $job_sid, $resume, $job_type = '')
    {
        if ($job_type == 'job') {
            $this->db->where('job_sid', $job_sid);
        } else if ($job_type == 'desired_job') {
            $this->db->where('sid', $job_sid);
        } else if ($job_type == 'job_not_applied') {
            $this->db->where('job_sid', $job_sid);
            $this->db->where('desired_job_title', NULL);
        }

        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $this->db->update('portal_applicant_jobs_list', array('resume' => $resume, 'last_update' => date('Y-m-d')));
    }

    function update_resume_applicant_job_list_old($portal_job_applications_sid, $company_sid, $job_sid, $resume, $job_type = '')
    {
        if ($job_type == 'desired_job') {
            $this->db->where('sid', $job_sid);
        } else {
            $this->db->where('job_sid', $job_sid);
        }
        $this->db->where('portal_job_applications_sid', $portal_job_applications_sid);
        $this->db->where('company_sid', $company_sid);

        $this->db->update('portal_applicant_jobs_list', array('resume' => $resume));
    }

    function getEmploymentStatuses()
    {
        return [
            'permanent' => 'Permanent',
            'probation' => 'Probation',
            // 'contractual' => 'Contractual',
            //  'trainee' => 'Trainee',
            //  'other' => 'Other',
        ];
    }
    function getEmploymentTypes()
    {
        return [
            'fulltime' => 'Full-time',
            'parttime' => 'Part-time',
            'contractual' => 'Contractual',
            //'casual' => 'Casual',
            //            'fixedterm' => 'Fixed term',
            //            'apprentices-and-trainees' => 'Apprentices and trainees',
            //            'commission-and-piece-rate-employees' => 'Commission and piece rate employees',
        ];
    }

    function mantain_incorrect_email_log($email_data)
    {
        $this->db->insert('fix_email_address_log', $email_data);
    }

    function update_applicant_job_title($sid, $job_title)
    {
        $data = array(
            'desired_job_title' => $job_title
        );

        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $data);
    }

    //
    function get_applicant_obboarding_status_log($sid)
    {

        $this->db->select('*');
        $this->db->where('portal_applicant_jobs_list_sid', $sid);
        $this->db->from('applicant_onboarding_status_log');
        return $this->db->order_by('sid', 'desc')->get()->result_array();
    }

    function get_applicant_job_queue($applicant_id) {
        $this->db->select('*');
        $this->db->where('portal_applicant_job_sid', $applicant_id);
        $this->db->from('portal_applicant_jobs_queue');
        return $this->db->order_by('sid', 'desc')->get()->row_array();
    }

    function get_submitted_resume_data($applicant_id) {
        $this->db->select('*');
        $this->db->where('portal_applicant_job_sid', $applicant_id);
        $this->db->from('portal_applicant_resume_analysis');
        return $this->db->order_by('sid', 'desc')->get()->row_array();
    }

    function update_submitted_resume($resume_id, $data) {
        $this->db->where('sid', $resume_id);
        $this->db->update('portal_applicant_resume_analysis', $data);
    }

    function get_interview_log($applicant_id) {
        $this->db->select('*');
        $this->db->where('portal_applicant_job_sid', $applicant_id);
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.sid = portal_applicant_interview_logs.portal_applicant_job_sid');
        $this->db->from('portal_applicant_interview_logs');
        return $this->db->order_by('portal_applicant_interview_logs.sid', 'desc')->get()->row_array();
    }

    function getOthersSubmittedResumeData ($applicantId, $queueId) {
        $this->db->select('sid');
        $this->db->where('portal_job_applications_sid', $applicantId);
        $this->db->where('sid <>', $queueId);
        $this->db->from('portal_applicant_jobs_queue');
        $queueIds = $this->db->get()->result_array();
        //
        $resumeIds = array_column($queueIds, 'sid');
        //
        $this->db->select('*');
        $this->db->where_in('portal_applicant_jobs_queue_sid', $resumeIds);
        $this->db->from('portal_applicant_resume_analysis');
        return $this->db->order_by('sid', 'desc')->get()->result_array();
    }
}

//
function sort_by_date($a, $b)
{
    return $a['date_applied'] < $b['date_applied'];
}
