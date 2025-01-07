<?php

class Export_csv_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function get_all_employees($company_sid, $access_level, $status)
    {
        //        $this->db->select('email');
        //        $this->db->select('Location_Country');
        //        $this->db->select('Location_State');
        //        $this->db->select('Location_City');
        //        $this->db->select('Location_Address');
        //        $this->db->select('Location_ZipCode');
        //        $this->db->select('PhoneNumber');
        //        $this->db->select('profile_picture');
        //        $this->db->select('first_name');
        //        $this->db->select('last_name');
        //        $this->db->select('access_level');
        //        $this->db->select('job_title');
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);

        if ($access_level != 'all' && $access_level != 'executive_admin') {
            $this->db->where('access_level', $access_level);
        }

        if ($access_level == 'executive_admin') {
            $this->db->where('is_executive_admin', 1);
        }

        if ($status == 'active') {
            $this->db->where('active', 1);
        }

        if ($status == 'active') {
            $this->db->where('active', 1);
        }

        if ($status == 'archived') {
            $this->db->where('active', 0);
        }

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_all_employees_from_DB($company_sid, $access_level, $status, $start, $end)
    {
        //
        if (!$status) {
            $status= ["all"];
        }

        /*
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);

        if ($access_level != 'all' && $access_level != 'executive_admin' && $access_level != null) {
            $this->db->where('access_level', $access_level);
        }

        if ($access_level == 'executive_admin') {
            $this->db->where('is_executive_admin', 1);
        }
*/


        /*
        if ($status == 'active') {
            $this->db->where('active', 1);
            $this->db->where('terminated_status', 0);
        }

        if ($status == 'terminated') {
            $this->db->where('terminated_status', 1);
        }


        if ($status != 'all' && $status != 'active' && $status != 'terminated') {
            $this->db->where('LCASE(general_status) ', $status);
        }
        */


        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);

        if (is_array($access_level)) {
            if (!in_array("all", $access_level)) {
                $this->db->group_start();
                $this->db->where_in('access_level', $access_level);
                //
                if (in_array("executive_admin", $access_level)) {
                    $this->db->or_where('is_executive_admin', 1);
                } else {
                    $this->db->where('is_executive_admin', 0);
                }
                $this->db->group_end();
            }
        } else {
            if ($access_level != 'all' && $access_level != 'executive_admin' && $access_level != null) {
                $this->db->where('access_level', $access_level);
            }
        }

        if ($access_level == 'executive_admin') {
            $this->db->where('is_executive_admin', 1);
        }

        //
        if (is_array($status)) {
            //
            if ($status[0] != 'all') {
                $this->db->group_start();
                foreach ($status as $statusVal) {

                    if ($statusVal == 'terminated') {
                        $this->db->or_where('terminated_status', 1);
                    } else if ($statusVal == 'active') {
                        $this->db->group_start();
                        $this->db->or_where('active', 1);
                        $this->db->where('terminated_status', 0);
                        $this->db->group_end();
                    } else {
                        $this->db->or_where('LCASE(general_status) ', $statusVal);
                    }
                }
                $this->db->group_end();
            }
        } else {
            if ($status == 'active') {
                $this->db->where('active', 1);
                $this->db->where('terminated_status', 0);
            }

            if ($status == 'terminated') {
                $this->db->where('terminated_status', 1);
            }

            if ($status != 'all' && $status != 'active' && $status != 'terminated') {
                $this->db->where('LCASE(general_status) ', $status);
            }
        }


        if (!empty($start) && !empty($end)) {

            $startDate = str_replace(' 23:59:59', '', $end);
            $endDate = str_replace(' 00:00:00', '', $start);

            //
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where('joined_at >= ', $startDate);
            $this->db->where('joined_at <= ', $endDate);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('rehire_date>=', $startDate);
            $this->db->where('rehire_date<=', $endDate);
            $this->db->group_end();

            $this->db->or_group_start();
            $this->db->where('registration_date>=', $end);
            $this->db->where('registration_date<=', $start);
            $this->db->group_end();
            $this->db->group_end();
        }


        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    // Fetch all active employees
    function getAllActiveEmployees(
        $companySid,
        $withExec = true
    ) {
        $this->db
            ->select('
            sid, 
            first_name, 
            last_name, 
            is_executive_admin, 
            access_level, 
            access_level_plus,
            pay_plan_flag,
            job_title
        ')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->order_by('first_name', 'ASC');
        //
        if (!$withExec) {
            $this->db->where('is_executive_admin', 0);
        }
        $a = $this->db->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function get_all_applicants($company_sid, $applicant_type)
    {
        $this->db->select('portal_job_applications.sid as applicant_sid');
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
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_applications.referred_by_name');
        $this->db->select('portal_job_applications.referred_by_email');
        $this->db->select('portal_applicant_jobs_list.archived');
        $this->db->where('portal_job_applications.employer_sid', $company_sid);

        switch ($applicant_type) {
            case 'active':
                $this->db->where('portal_applicant_jobs_list.archived', 0);
                break;
            case 'archived':
                $this->db->where('portal_applicant_jobs_list.archived', 1);
                break;
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $applicants_obj = $this->db->get('portal_job_applications');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();
        return $applicants_arr;
    }

    function get_csv_applicants($company_sid, $applicant_type, $keyword, $job_sid, $applicant_status, $start_date, $end_date)
    {
        // $this->db->select('portal_applicant_jobs_list.sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.archived');
        $this->db->select('portal_applicant_jobs_list.portal_job_applications_sid');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.status as applicant_status');
        $this->db->select('portal_job_applications.sid as main_sid');
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
        $this->db->select('portal_job_applications.linkedin_profile_url');
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_applications.union_member');
        $this->db->select('portal_job_applications.union_name');
        $this->db->select('portal_job_applications.employee_number');

        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);

        //        switch ($applicant_type){
        //            case 'active':
        //                $this->db->where('portal_applicant_jobs_list.archived', 0);
        //                break;
        //            case 'archived':
        //                $this->db->where('portal_applicant_jobs_list.archived', 1);
        //                break;
        //        }

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

        //        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $job_sid)) {
            if (is_array($job_sid)) {
                $this->db->where_in('portal_applicant_jobs_list.job_sid', $job_sid);
            } else {
                $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
            }
        }

        if (!empty($applicant_status) && $applicant_status != 'all') {
            $this->db->like('portal_applicant_jobs_list.status', $applicant_status);
        }

        if (!empty($applicant_type) && $applicant_type == 'Archived') {
            $this->db->where('portal_applicant_jobs_list.archived', 1);
        } else if (!empty($applicant_type) && $applicant_type != 'all') {
            $this->db->where('portal_applicant_jobs_list.applicant_type', $applicant_type);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_applicant_jobs_list.date_applied <=', $end_date);
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'INNER');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        $this->db->order_by('portal_applicant_jobs_list.date_applied', 'DESC'); // check it over 
        $applicants_obj = $this->db->get('portal_applicant_jobs_list');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();
        //echo $this->db->last_query(); exit;
        return $applicants_arr;
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

    function get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date = null, $end_date = null, $count_only = false, $limit = null, $offset = null)
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

        if (!empty($job_sid) && $job_sid != 'all') {
            $this->db->where('portal_applicant_jobs_list.job_sid', $job_sid);
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
        } else {
            return $this->db->count_all_results('portal_applicant_jobs_list');
        }
    }

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

        return $job_title;
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

    function get_department_name($department_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $department_sid);
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    function get_team_name($team_sid)
    {
        $this->db->select('name');
        $this->db->where('sid', $team_sid);
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['name'];
        } else {
            return '';
        }
    }

    function get_applicant_notes($applicant_sid)
    {
        $this->db->select('notes');
        $this->db->where('applicant_job_sid', $applicant_sid);
        $record_obj = $this->db->get('portal_misc_notes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_status_info($employee_sid, $status)
    {
        $this->db->select('termination_reason , termination_date');
        $this->db->where('employee_status', $status);
        $this->db->where('employee_sid ', $employee_sid);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_employee_last_status_info($employee_sid)
    {
        $this->db->select('employee_status');
        $this->db->where('employee_sid ', $employee_sid);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $employee_status = "Archived Employee";
            //
            if ($record_arr['employee_status'] == 1) {
                $employee_status = 'Terminated';
            } else if ($record_arr['employee_status'] == 2) {
                $employee_status = 'Retired';
            } else if ($record_arr['employee_status'] == 3) {
                $employee_status = 'Deceased';
            } else if ($record_arr['employee_status'] == 4) {
                $employee_status = 'Suspended';
            } else if ($record_arr['employee_status'] == 5) {
                $employee_status = 'Active';
            } else if ($record_arr['employee_status'] == 6) {
                $employee_status = 'Inactive';
            } else if ($record_arr['employee_status'] == 7) {
                $employee_status = 'Leave';
            } else if ($record_arr['employee_status'] == 8) {
                $employee_status = 'Rehired';
            } else if ($record_arr['employee_status'] == 9) {
                $employee_status = 'Transferred';
            }
            //
            return $employee_status;
        } else {
            return 'Archived Employee';
        }
    }


    function save_employee_csv_report_settings($data_to_insert)
    {
        $this->db->insert('employee_csv_report_settings', $data_to_insert);
    }


    function get_employee_csv_report_settings()
    {

        $records_obj = $this->db->get('employee_csv_report_settings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_employee_csv_report_settings_bycompany($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->order_by('sid', 'Desc');
        $records_obj = $this->db->get('employee_csv_report_settings');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }


    function csv_report_settings_delete($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('employee_csv_report_settings', $data);
    }
}
