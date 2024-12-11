<?php

class Reports_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function get_job_products($company_sid, $product_sid, $job_sid, $start_date, $end_date, $count_only = false, $limit = null, $offset = null)
    {
        $this->db->select('jobs_to_feed.*');
        $this->db->where('jobs_to_feed.company_sid', $company_sid);

        if ($product_sid != null && $product_sid != 'all') {
            $this->db->where('jobs_to_feed.product_sid', $product_sid);
        }

        $check_jobs_exists = explode(',', $job_sid);

        if (!in_array('all', $check_jobs_exists)) {
            if (is_array($check_jobs_exists)) {
                $this->db->where_in('jobs_to_feed.job_sid', $check_jobs_exists);
            } else {
                $this->db->where('jobs_to_feed.job_sid', $job_sid);
            }
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('jobs_to_feed.purchased_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('jobs_to_feed.purchased_date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('jobs_to_feed.purchased_date <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if ($count_only == false) {
            $this->db->order_by("sid", "desc");
            $products = $this->db->get('jobs_to_feed')->result_array();
            $i = 0;

            foreach ($products as $product) {
                $job_title = get_job_title($product['job_sid']);
                $product_name = db_get_products_details($product['product_sid']);
                $products[$i]['job_title'] = $job_title;

                if (isset($product_name['name'])) {
                    $products[$i]['product_name'] = $product_name['name'];
                } else {
                    $products[$i]['product_name'] = '';
                }

                $i++;
            }

            return $products;
        } else {
            $this->db->from('jobs_to_feed');
            return $this->db->count_all_results();
        }
    }

    public function get_job_products_count($company_sid = NULL, $search = '', $between = '')
    {
        if ($search != '' && $search != NULL) {
            $this->db->where($search);
        }

        if ($between != '' && $between != NULL) {
            $this->db->where($between);
        }

        $this->db->where('company_sid > ', 0);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('jobs_to_feed')->num_rows();
    }

    public function get_active_products()
    {
        $this->db->select('sid, name');
        $this->db->where('active', 1);
        return $this->db->get('products')->result_array();
    }

    public function get_purchased_products_by_company($company_sid)
    {
        $orders = $this->db->get_where('invoices', array('company_sid' => $company_sid, 'status' => 'Paid'))->result_array();
        $product_ids = array();

        foreach ($orders as $order) {
            $items_array = $order['serialized_items_info'];
            $items = unserialize($items_array);
            $products = $items['products'];

            foreach ($products as $product) {
                $product_ids[] = $product;
            }
        }

        $products = array_unique($product_ids);

        if (sizeof($products) > 0) {
            $this->db->select('sid, name');
            $this->db->where_in('sid', $products);
        } else {
            return array();
        }

        return $this->db->get('products')->result_array();
    }

    public function get_active_jobs($company_sid = NULL)
    {
        $this->db->select('sid, Title');
        $this->db->where('active', 1);
        $this->db->where('user_sid', $company_sid);
        return $this->db->get('portal_job_listings')->result_array();
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

    function get_references($company_sid = NULL)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $references = $this->db->get('reference_checks')->result_array();
        $i = 0;

        foreach ($references as $reference) {
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

    function GetAllUsers($company_sid = NULL)
    {
        $this->db->select('*');
        $this->db->where('active', 1);
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);
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
        return $this->db->get('portal_schedule_event')->result_array();
    }

    function get_all_hired_jobs($company_sid = NULL, $start_date = null, $end_date = null, $status = null)
    {
        $this->db->select('portal_job_listings.Title');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->select('portal_applicant_jobs_list.job_sid');

        if ($status) {
            $this->db->where('portal_job_applications.hired_status', 1);
            $this->db->where('portal_job_applications.hired_sid >', 0);
        }

        $this->db->where('portal_job_applications.employer_sid', $company_sid);

        if ($start_date != null && $end_date != null) {
            $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:23:59', strtotime($end_date)) . '"');
        }

        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        return $this->db->get('portal_job_applications')->result_array();
    }

    function GetAllApplicantsBetween($company_sid = NULL, $keyword = 'all', $start_date, $end_date, $hired_status = null, $check_hired_date = false)
    {
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.desired_job_title');
        $this->db->select('portal_applicant_jobs_list.applicant_type');
        $this->db->select('portal_job_applications.first_name');
        $this->db->select('portal_job_applications.last_name');
        $this->db->select('portal_job_applications.hired_date');
        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');
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

        if ($hired_status != null) {
            $this->db->where('portal_job_applications.hired_status', $hired_status);
        }

        if ($check_hired_date == true) {
            if ($start_date != null && $end_date != null) {
                $this->db->where('portal_job_applications.hired_date BETWEEN "' . date('Y-m-d', strtotime($start_date)) . '" and "' . date('Y-m-d', strtotime($end_date)) . '"');
            }
        } else {
            if ($start_date != null && $end_date != null) {
                $this->db->where('portal_applicant_jobs_list.date_applied BETWEEN "' . date('Y-m-d 00:00:00', strtotime($start_date)) . '" and "' . date('Y-m-d 23:59:59', strtotime($end_date)) . '"');
            }
        }

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->order_by('date_applied', 'DESC');
        $applications = $this->db->get('portal_applicant_jobs_list')->result_array();

        foreach ($applications as $key => $application) {
            $applications[$key]['Title'] = $this->get_job_title_by_type($application['job_sid'], $application['applicant_type'], $application['desired_job_title']);
        }

        return $applications;
    }

    function GetAllJobsCompanySpecific($company_sid = NULL, $keywords = '')
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);

        if (!empty($keywords) && $keywords != 'all') {
            $this->db->like('Title', $keywords);
        }

        $this->db->order_by('portal_job_listings.activation_date', 'DESC');
        return $this->db->get('portal_job_listings')->result_array();
    }

    function GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid = NULL, $job_sid, $hired_status = null)
    {
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
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_applicant_jobs_list.applicant_type', 'Applicant');

        if ($hired_status != null) {
            $this->db->where('portal_job_applications.hired_status', $hired_status);
        }

        $this->db->order_by('portal_applicant_jobs_list.sid', 'DESC');
        return $this->db->get('portal_applicant_jobs_list')->result_array();
    }

    function get_company_jobs($company_sid)
    {
        $this->db->select('sid, Title, active');
        $this->db->where('user_sid', $company_sid);
        return $this->db->get('portal_job_listings')->result_array();
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

        $this->db->join('portal_job_applications', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('application_status', 'portal_applicant_jobs_list.status_sid = application_status.sid', 'left');
        $this->db->order_by('date_applied', 'DESC');

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

    function GetAllJobCategoriesWhereApplicantsAreHired($company_sid = NULL)
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

    function GetAllApplicantsOnboarding($company_sid = NULL, $keyword = 'all', $start_date, $end_date, $check_hired_date = false)
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
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_job_listings.Title');
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
                $this->db->where('portal_job_applications.hired_date BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
        } else {
            if ($start_date != null && $end_date != null) {
                $this->db->where('portal_job_applications.date_applied BETWEEN "' . $start_date . '" and "' . $end_date . '"');
            }
        }

        $this->db->join('portal_job_applications', 'users.sid = portal_job_applications.hired_sid', 'left');
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left');
        $this->db->order_by('hired_date', 'DESC');
        $applications = $this->db->get('users')->result_array();
        $i = 0;

        foreach ($applications as $application) {
            $applications[$i]['Title'] = get_job_title($application['job_sid']);
            $i++;
        }

        return $applications;
    }

    function get_all_jobs_views_applicants_count($company_sid = NULL)
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

        return $return_data;
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

    //*************** Activity Reports functions **********************//
    function get_all_companies()
    {
        $this->db->select('*');
        $this->db->where('parent_sid', 0);
        $this->db->order_by('sid', 'DESC');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('active', 1);
        return $this->db->get('users')->result_array();
    }

    function generate_activity_log_data_for_view($company_sid, $start_date, $end_date, $employer_sid = null)
    { //Handle Manual Employer Sid Feed
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
            $employer_logs = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
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

    function get_active_employers($company_sid, $start_date, $end_date)
    {
        $this->db->distinct();
        $this->db->select('employer_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        return $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
    }

    public function get_all_employers($company_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    public function get_activity_log($employer_sid, $year, $month, $day, $hour)
    {
        $this->db->select('*');
        $this->db->where('sid', $employer_sid);
        $employer_info = $this->db->get('users')->result_array();

        if (!empty($employer_info)) {
            $employer_info = $employer_info[0];
            $company_sid = $employer_info['parent_sid'];
            $date_start = $year . '-' . $month . '-' . $day . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $date_end = $year . '-' . $month . '-' . $day . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':59:59';
            $this->db->select('*');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where("action_timestamp BETWEEN '" . $date_start . "' AND '" . $date_end . "'");
            return $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $date_start))->result_array();
        }
    }

    public function get_all_inactive_employees($company_sid, $start_date, $end_date)
    {
        $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);
        $active_employers_sids = array();

        foreach ($active_employers as $active_employer) {
            $active_employers_sids[] = $active_employer['employer_sid'];
        }

        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);

        if (!empty($active_employers_sids)) {
            $this->db->where('sid NOT IN ( ' . implode(',', $active_employers_sids) . ' )');
        }

        $inactive_employers = $this->db->get('users')->result_array();
        return $inactive_employers;
    }

    public function get_company_activity_overview($company_sid, $start_date, $end_date, $get_details = false)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', 0);
        $this->db->where('sid', $company_sid);
        $this->db->where('active', 1);
        $companies = $this->db->get('users')->result_array();

        foreach ($companies as $key => $company) {
            $company_sid = $company['sid'];
            //Get Activity Information from Activity Tracker
            $this->db->distinct();
            $this->db->select('employer_sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $active_employers = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
            $active_employers_sids = array();

            foreach ($active_employers as $active_employer) {
                $active_employers_sids[] = $active_employer['employer_sid'];
            }

            //Get Inactive Employers
            $inactive_employers = array();
            $this->db->select('*');
            $this->db->where('parent_sid', $company_sid);
            $this->db->order_by('is_executive_admin', 'DESC');
            $this->db->order_by('access_level', 'ASC');

            if (!empty($active_employers_sids)) {
                $this->db->where('sid NOT IN ( ' . implode(',', $active_employers_sids) . ' )');
            }

            $inactive_employers = $this->db->get('users')->result_array();
            $active_employers = array(); //Get Active Employers

            if (!empty($active_employers_sids)) {
                $this->db->select('*');
                $this->db->where('parent_sid', $company_sid);
                $this->db->order_by('is_executive_admin', 'DESC');
                $this->db->order_by('access_level', 'ASC');
                $this->db->where('sid IN ( ' . implode(',', $active_employers_sids) . ' )');
                $active_employers = $this->db->get('users')->result_array();
            }

            if ($get_details == true) {
                if (!empty($active_employers)) {
                    foreach ($active_employers as $act_emp_key => $active_employer) {
                        $activity_details = $this->generate_activity_log_data_for_view($company_sid, $start_date, $end_date, $active_employer['sid']);
                        if (!empty($activity_details)) {
                            $active_employers[$act_emp_key]['details'] = $activity_details[0];
                        } else {
                            $active_employers[$act_emp_key]['details'] = array();
                        }
                    }
                }
            }

            $companies[$key]['active_employers'] = $active_employers;
            $companies[$key]['inactive_employers'] = $inactive_employers;
        }
        return $companies;
    }

    function get_candidate_offers($company_sid = NULL, $start_date = NULL, $end_date = NULL, $keyword = 'all')
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

        foreach ($candidates as $key => $candidate) { // get job title
            $applicant_sid = $candidate['applicant_sid'];
            $this->db->select('job_sid');
            $this->db->where('portal_job_applications_sid', $applicant_sid);
            $job_sid = $this->db->get('portal_applicant_jobs_list')->result_array();

            if (isset($job_sid[0]['job_sid'])) {
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

    function get_applicant_interview_scores($company_sid, $keyword = 'all')
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

    //
    function get_all_jobs_views_applicants_count_filter($company_sid, $jobStatus)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);

        if ($jobStatus != 'all') {
            $this->db->where('active', $jobStatus);
        }
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
                $return_data[$key]['applicant_count'] = $count;
            }
        }
        return $return_data;
    }


    //
    public function getAssignedDocumentForReport($assignedById, $companySid, $documentTitle,$startDate,$endDate)
    {
        //
        $this->db->select('
        documents_assigned.user_sid,
        documents_assigned.document_title,
        documents_assigned.sid,
        documents_assigned.user_consent,
        documents_assigned.confidential_employees,
        documents_assigned.document_description,
        documents_assigned.acknowledgment_required,
        documents_assigned.download_required,
        documents_assigned.signature_required,
        documents_assigned.acknowledged,
        documents_assigned.uploaded,
        documents_assigned.downloaded,
        documents_assigned.document_sid,
        documents_assigned.status,
        documents_assigned.assigned_date,
        documents_assigned.company_sid,
        users.companyName ,
        emp.first_name,
         emp.last_name           
    ');

        $this->db
            ->from('documents_assigned');

        $this->db->join('users', 'users.sid =  documents_assigned.company_sid', 'left');
        $this->db->join('users as emp', 'emp.sid =  documents_assigned.user_sid', 'right');


        if ($documentTitle != 'all') {
            $this->db->like('documents_assigned.document_title', urldecode($documentTitle));
        }

        if ($companySid != 'all') {
            $this->db->where('documents_assigned.company_sid', $companySid);
        }

        if($startDate!='all' && $endDate!='all' && $documentTitle == 'all' ){
            $this->db->where('documents_assigned.assigned_date >=', $startDate);
            $this->db->where('documents_assigned.assigned_date <=', $endDate);
        }

        $this->db->where_in('documents_assigned.assigned_by', $assignedById);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.archive', 0);

        //
        $result = $this->db->get();
        //
        $data = $result->result_array();
        //
        if (!empty($data)) {

            foreach ($data as $key => $val) {

                $data[$key]['completedStatus'] = '';

                if ($val['status'] != 1) {
                    continue;
                }
                // Column to check
                $is_magic_tag_exist = preg_match('/{{(.*?)}}/', $val['document_description']) ? true : false;
                //
                if (!$is_magic_tag_exist) $is_magic_tag_exist = preg_match('/<select(.*?)>/', $val['document_description']);
                //
                $is_document_completed = 0;
                //
                $is_magic_tag_exist = 0;
                //
                if (str_replace(EFFECT_MAGIC_CODE_LIST, '', $val['document_description']) != $val['document_description']) {
                    $is_magic_tag_exist = 1;
                }
                // Check for uploaded manual dcoument
                if ($val['document_sid'] == 0) {
                    // continue;
                    $data[$key]['completedStatus'] = 'No Action Required';
                } else {
                    //
                    if ($this->isDocumentArchived($val["document_sid"])) {
                        unset($data[$key]);
                        continue;
                    }
                    //
                    if ($val['acknowledgment_required'] || $val['download_required'] || $val['signature_required'] || $is_magic_tag_exist) {

                        if ($val['acknowledgment_required'] == 1 && $val['download_required'] == 1 && $val['signature_required'] == 1) {
                            if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['acknowledgment_required'] == 1 && $val['download_required'] == 1) {
                            if ($is_magic_tag_exist == 1) {
                                if ($val['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($val['acknowledged'] == 1 && $val['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['acknowledgment_required'] == 1 && $val['signature_required'] == 1) {
                            if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['download_required'] == 1 && $val['signature_required'] == 1) {
                            if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['acknowledgment_required'] == 1) {
                            if ($val['acknowledged'] == 1) {
                                $is_document_completed = 1;
                            } else if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['download_required'] == 1) {
                            if ($val['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($val['signature_required'] == 1) {
                            if ($val['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($is_magic_tag_exist == 1) {
                            if ($val['user_consent'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        }

                        if ($is_document_completed == 1) {
                            $data[$key]['completedStatus'] = 'Completed';
                        } else {
                            $data[$key]['completedStatus'] = 'Not Completed';
                        }
                    } else {
                        $data[$key]['completedStatus'] = 'No Action Required';
                    }
                }
            }
        }


        return $data ? array_values($data) : [];
    }

    //
    public function isDocumentArchived($documentId)
    {
        return $this->db
            ->where("sid", $documentId)
            ->where("archive", 1)
            ->count_all_results("documents_management");
    }
}
