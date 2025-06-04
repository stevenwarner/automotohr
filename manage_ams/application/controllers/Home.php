<?php defined('BASEPATH') or exit('No direct script access allowed');
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('common/check_domain');
        $this->load->model('common/contact_model');
        $this->load->model('common/job_details');
        $this->load->model('common/theme_meta_model');
        $this->load->model('common/themes_pages_model');
        $this->load->library('google_auth');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $server_name = clean_domain($_SERVER['SERVER_NAME']);


        if (isset($_SERVER['HTTP_REFERER'])) { // Check that the referer is external or internal
            $referral = $_SERVER['HTTP_REFERER'];
            $clean_referral = clean_domain($referral);

            if (!$this->session->userdata('last_referral')) {
                $this->session->set_userdata('last_referral', $referral);
            }

            if ($clean_referral != $server_name) {
                $this->session->set_userdata('referral_details', $referral);
            }
        } else {
            $this->session->set_userdata('referral_details', $_SERVER['HTTP_HOST']);
        }
    }

    public function index()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);

        if (!$this->session->userdata('portal_info')) {
            $this->session->set_userdata('portal_info', $data);
        }

        $company_sid = $data['company_details']['sid'];
        company_phone_regex_module_check($company_sid, $data, $this);
        $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();
        //
        $data['is_paid'] = 1;
        $theme_name = !empty($data['theme_name']) ? $data['theme_name'] : 'theme-4';
        //
        if (empty($data['theme_name'])) {
            $data['theme_name'] = 'theme-4';
        }
        //
        $countries_array = array();
        $states_array = array();
        $counntry_states_array = array();

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $isPaid = $data['is_paid'];
            $country = '';
            $state = '';
            $city = '';
            $categoryId = '';
            $keyword = '';
            $pageName = '';
            $segment1 = $this->uri->segment(1);
            //
            $filter = [];
            $filter['key'] = '';
            $filter['value'] = '';

            if (!empty($segment1)) {
                if (preg_match('/jobs-at/', $segment1)) {
                    //
                    $pageName = 'JOBS';
                    $filter['key'] = 'users.CompanyName';
                    $filter['value'] = strtolower(
                        trim(
                            preg_replace(
                                '/-/',
                                ' ',
                                preg_replace('/jobs-at-/', '', $segment1)
                            )
                        )
                    );
                } else {
                    $pageName = urldecode($segment1);
                }
            }

            $segment2 = $this->uri->segment(2);

            if (!empty($segment2)) {
                $country = urldecode($segment2);
            }

            $segment3 = $this->uri->segment(3);

            if (!empty($segment3)) {
                $state = urldecode($segment3);
            }

            $segment4 = $this->uri->segment(4);

            if (!empty($segment4)) {
                $city = urldecode($segment4);
            }

            $segment5 = $this->uri->segment(5);

            if (!empty($segment5)) {
                $categoryId = $segment5;
            }

            $segment6 = $this->uri->segment(6);

            $segment7 = $this->uri->segment(7);
            $ajax_flag = $this->uri->segment(8);
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;

            if (!empty($segment7) && $segment7 > 1) {
                $offset = ($segment7 - 1) * $per_page;
            }

            if (!empty($segment6)) {
                $keyword = urldecode($segment6);
                $data['search_params']['country'] = $country;
                $data['search_params']['state'] = $state;
                $data['search_params']['city'] = $city;
                $data['search_params']['category'] = $categoryId;
                $data['search_params']['keyword'] = $keyword;
            }

            $data['formpost'] = array();
            $data['dealership_website'] = '';
            $company_id = $data['company_details']['sid'];
            $company_name = $data['company_details']['CompanyName'];
            $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id);
            $data['pages'] = $pages;
            $data['isPaid'] = $isPaid;
            $counter = 0;

            foreach ($data['pages'] as $page) {
                $data['pages'][$counter]['page_title'] = str_replace("{{company_name}}", $company_name, $data['pages'][$counter]['page_title']);
                $counter++;
            }

            if ($isPaid) {
                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
                $data['jobs_page_title'] = $jobs_page_title;

                if ($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) {
                    $pageName = 'jobs';
                }

                if ($pageName == '') {
                    $pageName = 'home';
                }

                $data['pageName'] = $pageName;

                switch (strtoupper($pageName)) {
                    case 'HOME':
                        $section_01_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_01');
                        $section_02_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_02');
                        $section_03_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_03');
                        $section_04_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_04');
                        $section_05_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_05');
                        $section_06_meta = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_06');
                        $additional_sections = $this->theme_meta_model->getAdditionalSections($company_id);
                        $partners = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, $pageName, 'partners');
                        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                        $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                        $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                        $data['partners'] = $partners;
                        $data['testimonials'] = '';
                        $data['footer_content'] = $footer_content;
                        $section_01_meta['title'] = str_replace("{{company_name}}", $company_name, $section_01_meta['title']);
                        $section_01_meta['content'] = str_replace("{{company_name}}", $company_name, $section_01_meta['content']);
                        $section_02_meta['title'] = str_replace("{{company_name}}", $company_name, $section_02_meta['title']);
                        $section_02_meta['content'] = str_replace("{{company_name}}", $company_name, $section_02_meta['content']);
                        $section_03_meta['title'] = str_replace("{{company_name}}", $company_name, $section_03_meta['title']);
                        $section_03_meta['content'] = str_replace("{{company_name}}", $company_name, $section_03_meta['content']);
                        $section_04_meta['title'] = str_replace("{{company_name}}", $company_name, $section_04_meta['title']);
                        $section_04_meta['content'] = str_replace("{{company_name}}", $company_name, $section_04_meta['content']);
                        $section_05_meta['title'] = str_replace("{{company_name}}", $company_name, $section_05_meta['title']);
                        $section_05_meta['content'] = str_replace("{{company_name}}", $company_name, $section_05_meta['content']);
                        $section_06_meta['title'] = str_replace("{{company_name}}", $company_name, $section_06_meta['title']);
                        $section_06_meta['content'] = str_replace("{{company_name}}", $company_name, $section_06_meta['content']);
                        $counter = 0;

                        if (!isset($section_02_meta['column_type'])) {
                            $section_02_meta['column_type'] = 'right_left';
                        }

                        if (!isset($section_03_meta['column_type'])) {
                            $section_03_meta['column_type'] = 'left_right';
                        }

                        $data['section_01_meta'] = $section_01_meta;
                        $data['section_02_meta'] = $section_02_meta;
                        $data['section_03_meta'] = $section_03_meta;
                        $data['section_04_meta'] = $section_04_meta;
                        $data['section_05_meta'] = $section_05_meta;
                        $data['section_06_meta'] = $section_06_meta;
                        $data['additional_sections'] = $additional_sections;

                        $this->load->view($theme_name . '/_parts/header_view', $data);
                        $this->load->view($theme_name . '/_parts/page_banner');
                        $this->load->view($theme_name . '/index_view');
                        $this->load->view($theme_name . '/_parts/footer_view');
                        break;
                    case 'JOBS':
                        $career_site_only_companies = $this->job_details->get_career_site_only_companies(); // get the career site status for companies
                        $career_site_company_sid = array();

                        if (!empty($career_site_only_companies)) {
                            foreach ($career_site_only_companies as $csoc) {
                                $career_site_company_sid[] = $csoc['sid'];
                            }
                        }

                        $all_paid_jobs = $this->job_details->get_all_paid_jobs($career_site_company_sid);
                        $paid_jobs = array();
                        $featured_jobs = array();
                        $featured_jobs_count = 0;
                        $list_count = 0;

                        if (!empty($all_paid_jobs)) {
                            foreach ($all_paid_jobs as $apj) {
                                $paid_jobs[] = $apj['jobId'];
                            }
                        }

                        if (!empty($segment6)) { // if search is applied
                            $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, $per_page, $offset, false, $filter);
                            $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, $per_page, $offset, true, $filter);

                            if (!empty($paid_jobs)) {
                                $featured_jobs = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, $per_page, $offset, false, $filter);
                                $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, $per_page, $offset, true, $filter);
                            }
                        } else {
                            $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset, false, $filter);
                            $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset, true, $filter);

                            if (!empty($paid_jobs)) {
                                $featured_jobs = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, $per_page, $offset, false, $filter);
                                $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, $per_page, $offset, true, $filter);
                            }
                        }

                        $all_active_jobs = $this->job_details->filters_of_active_jobs($career_site_company_sid, $filter);

                        if (!empty($all_active_jobs)) { // we need it for search filters as we only need to show filters as per active jobs only
                            for ($i = 0; $i < count($all_active_jobs); $i++) {
                                $country_id = $all_active_jobs[$i]['Location_Country'];

                                if ($country_id == 38) {
                                    $countries_array[38] = array('sid' => 38, 'country_code' => 'CA', 'country_name' => 'Canada');
                                }

                                if ($country_id == 227) {
                                    $countries_array[227] = array('sid' => 227, 'country_code' => 'US', 'country_name' => 'United States');
                                }

                                $state_id = $all_active_jobs[$i]['Location_State'];

                                if (!empty($state_id) && $state_id != 'undefined') {
                                    if (!array_key_exists($state_id, $states_array)) {
                                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                                        $states_array[$state_id] = $state_name[0]['state_name'];
                                        $counntry_states_array[$country_id][] = array('sid' => $state_id, 'state_name' => $state_name[0]['state_name']);
                                    }
                                }

                                $JobCategorys = $all_active_jobs[$i]['JobCategory'];

                                if ($JobCategorys != null) {
                                    $cat_id = explode(',', $JobCategorys);

                                    foreach ($cat_id as $id) {
                                        $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                                        if (sizeof($job_cat_name))
                                            $categories_in_active_jobs[$id] = $job_cat_name[0]['value'];
                                    }
                                }
                            }
                        }

                        if (!empty($list)) {
                            foreach ($list as $key => $value) {
                                $list[$key]['TitleOnly'] = $list[$key]['Title'];
                                $has_job_approval_rights = $value['has_job_approval_rights'];

                                if ($has_job_approval_rights == 1) {
                                    $approval_right_status = $value['approval_status'];

                                    if ($approval_right_status != 'approved') {
                                        unset($list[$key]);
                                        continue;
                                    }
                                }

                                $country_id = $value['Location_Country'];

                                if (!empty($country_id)) { // get country name
                                    switch ($country_id) {
                                        case 227:
                                            $country_name = 'United States';
                                            break;
                                        case 38:
                                            $country_name = 'Canada';
                                            break;
                                        default:
                                            $country_name = '';
                                            break;
                                    }

                                    $list[$key]['Location_Country'] = $country_name;
                                }

                                $state_id = $value['Location_State'];

                                if (!empty($state_id) && $state_id != 'undefined') {
                                    $list[$key]['Location_State'] = $states_array[$state_id];
                                }

                                $JobCategorys = $value['JobCategory'];

                                if ($JobCategorys != null) {
                                    $cat_id = explode(',', $JobCategorys);
                                    $job_category_array = array();

                                    foreach ($cat_id as $id) {
                                        $job_category_array[] = $categories_in_active_jobs[$id];
                                    }

                                    $job_category = implode(', ', $job_category_array);
                                    $list[$key]['JobCategory'] = $job_category;
                                }

                                $questionnaire_sid = $value['questionnaire_sid'];

                                if ($questionnaire_sid > 0) {
                                    $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);

                                    if (!empty($portal_screening_questionnaires)) {
                                        $list[$key]['q_name'] = $portal_screening_questionnaires[0]['name'];
                                        $list[$key]['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                                        $list[$key]['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                                        $list[$key]['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                                        $list[$key]['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                                        $list[$key]['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                                        $list[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                                    } else {
                                        $list[$key]['q_name'] = 'Not Found';
                                        $list[$key]['q_passing'] = 0;
                                        $list[$key]['q_send_pass'] = '';
                                        $list[$key]['q_send_fail'] = '';
                                        $list[$key]['q_pass_text'] = '';
                                        $list[$key]['q_fail_text'] = '';
                                        $list[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                                    }

                                    $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                                    if ($screening_questions_numrows > 0) {
                                        $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                        foreach ($screening_questions as $qkey => $qvalue) {
                                            $questions_sid = $qvalue['sid'];
                                            $list[$key]['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                            $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                            if ($screening_answers_numrows) {
                                                $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                                foreach ($screening_answers as $akey => $avalue) {
                                                    $list[$key]['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                                }
                                            }
                                        }
                                    }
                                }

                                $company_sid = $value['user_sid']; //Making job title start
                                $job_title_location = $value['job_title_location'];
                                $sub_domain = $value['sub_domain'];

                                if ($job_title_location == 1) {
                                    $list[$key]['Title'] = $list[$key]['Title'] . '  - ' . ucfirst($list[$key]['Location_City']) . ', ' . $list[$key]['Location_State'] . ', ' . $list[$key]['Location_Country'];
                                }

                                $company_subdomain_url = STORE_PROTOCOL_SSL . $sub_domain;
                                $portal_job_url = $company_subdomain_url . '/job_details/' . $list[$key]['sid'];
                                $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                                $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                                $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list[$key]['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                                // $btn_google                                     = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                                $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($list[$key]['Title']) . '&amp;summary=' . urlencode($list[$key]['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-4.png"></a>';
                                $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list[$key]['Title']) . '</a>';
                                $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                                $links = '';
                                $links .= '<ul>';
                                // $links                                       .= '<li>' . $btn_google . '</li>';
                                $links .= '<li>' . $btn_facebook . '</li>';
                                $links .= '<li>' . $btn_linkedin . '</li>';
                                $links .= '<li>' . $btn_twitter . '</li>';

                                if ($theme_name == 'theme-4') {
                                    $links .= '<li>' . $btn_tell_a_friend . '</li>';
                                }

                                $links .= '</ul>';
                                $list[$key]['share_links'] = $links; //Generate Share Links - end
                            }
                        }

                        if (!empty($featured_jobs)) {
                            foreach ($featured_jobs as $key => $value) {
                                $featured_jobs[$key]['TitleOnly'] = $featured_jobs[$key]['Title'];
                                $has_job_approval_rights = $value['has_job_approval_rights'];

                                if ($has_job_approval_rights == 1) {
                                    $approval_right_status = $value['approval_status'];

                                    if ($approval_right_status != 'approved') {
                                        continue;
                                    }
                                }

                                $country_id = $value['Location_Country'];

                                if (!empty($country_id)) { // get country name
                                    switch ($country_id) {
                                        case 227:
                                            $country_name = 'United States';
                                            break;
                                        case 38:
                                            $country_name = 'Canada';
                                            break;
                                        default:
                                            $country_name = '';
                                            break;
                                    }

                                    $featured_jobs[$key]['Location_Country'] = $country_name;
                                }

                                $state_id = $value['Location_State'];

                                if (!empty($state_id) && $state_id != 'undefined') {
                                    $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                                    $featured_jobs[$key]['Location_State'] = $state_name[0]['state_name'];
                                }

                                $JobCategorys = $value['JobCategory'];

                                if ($JobCategorys != null) {
                                    $cat_id = explode(',', $JobCategorys);
                                    $job_category_array = array();

                                    foreach ($cat_id as $id) {
                                        $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                                        if (!empty($job_cat_name)) {
                                            $job_category_array[] = $job_cat_name[0]['value'];
                                        }
                                    }

                                    $job_category = implode(', ', $job_category_array);
                                    $featured_jobs[$key]['JobCategory'] = $job_category;
                                }

                                $questionnaire_sid = $value['questionnaire_sid'];

                                if ($questionnaire_sid > 0) {
                                    $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);

                                    if (!empty($portal_screening_questionnaires)) {
                                        $featured_jobs[$key]['q_name'] = $portal_screening_questionnaires[0]['name'];
                                        $featured_jobs[$key]['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                                        $featured_jobs[$key]['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                                        $featured_jobs[$key]['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                                        $featured_jobs[$key]['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                                        $featured_jobs[$key]['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                                        $featured_jobs[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                                    } else {
                                        $featured_jobs[$key]['q_name'] = 'Not Found';
                                        $featured_jobs[$key]['q_passing'] = 0;
                                        $featured_jobs[$key]['q_send_pass'] = '';
                                        $featured_jobs[$key]['q_send_fail'] = '';
                                        $featured_jobs[$key]['q_pass_text'] = '';
                                        $featured_jobs[$key]['q_fail_text'] = '';
                                        $featured_jobs[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                                    }

                                    $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                                    if ($screening_questions_numrows > 0) {
                                        $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                        foreach ($screening_questions as $qkey => $qvalue) {
                                            $questions_sid = $qvalue['sid'];
                                            $featured_jobs[$key]['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                            $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                            if ($screening_answers_numrows) {
                                                $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                                foreach ($screening_answers as $akey => $avalue) {
                                                    $featured_jobs[$key]['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                                }
                                            }
                                        }
                                    }
                                }

                                $company_sid = $value['user_sid']; //Making job title start
                                $job_title_location = $value['job_title_location'];
                                $sub_domain = $value['sub_domain'];

                                if ($job_title_location == 1) {
                                    $featured_jobs[$key]['Title'] = $featured_jobs[$key]['Title'] . '  - ' . ucfirst($featured_jobs[$key]['Location_City']) . ', ' . $featured_jobs[$key]['Location_State'] . ', ' . $featured_jobs[$key]['Location_Country'];
                                }

                                //Generate Share Links - start
                                $company_subdomain_url = STORE_PROTOCOL_SSL . $sub_domain;
                                $portal_job_url = $company_subdomain_url . '/job_details/' . $featured_jobs[$key]['sid'];
                                $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                                $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                                $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($featured_jobs[$key]['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                                // $btn_google = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                                $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($featured_jobs[$key]['Title']) . '&amp;summary=' . urlencode($featured_jobs[$key]['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-4.png"></a>';
                                $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($featured_jobs[$key]['Title']) . '</a>';
                                $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                                $links = '';
                                $links .= '<ul>';
                                // $links                                       .= '<li>' . $btn_google . '</li>';
                                $links .= '<li>' . $btn_facebook . '</li>';
                                $links .= '<li>' . $btn_linkedin . '</li>';
                                $links .= '<li>' . $btn_twitter . '</li>';

                                if ($theme_name == 'theme-4') {
                                    $links .= '<li>' . $btn_tell_a_friend . '</li>';
                                }

                                $links .= '</ul>';
                                $featured_jobs[$key]['share_links'] = $links; //Generate Share Links - end
                            }
                        }

                        $data['featured_jobs'] = $featured_jobs;
                        $data['job_listings'] = $list;
                        $data['featured_jobs_count'] = $featured_jobs_count;
                        $data['job_listings_count'] = $list_count;
                        $data['total_calls'] = ceil($list_count + $featured_jobs_count) / $per_page;
                        if (!empty($ajax_flag) && $ajax_flag) {
                            print_r(json_encode($data['job_listings']));
                            die();
                        }
                        function array_sort_state_name($a, $b)
                        {
                            return strnatcmp($a['state_name'], $b['state_name']);
                        }

                        if (isset($counntry_states_array['227'])) {
                            $us_states = $counntry_states_array['227'];
                            usort($us_states, 'array_sort_state_name'); // sort alphabetically by name
                            $counntry_states_array['227'] = $us_states;
                        }

                        if (isset($counntry_states_array['38'])) {
                            $ca_states = $counntry_states_array['38'];
                            usort($ca_states, 'array_sort_state_name'); // sort alphabetically by name
                            $counntry_states_array['38'] = $ca_states;
                        }
                        $data['job_categories'] = array();
                        if (!empty($categories_in_active_jobs)) {
                            asort($categories_in_active_jobs);
                        }
                        $data['categories_in_active_jobs'] = $categories_in_active_jobs;
                        $data_states_encode = htmlentities(json_encode($counntry_states_array));
                        $data['active_countries'] = $countries_array;
                        $data['active_states'] = $counntry_states_array;
                        $data['states'] = $data_states_encode;
                        $jobsPageBannerImage = $this->theme_meta_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs', 'jobs_page_banner');
                        $data['jobs_page_banner'] = $jobsPageBannerImage;
                        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
                        $data['jobs_page_title'] = $jobs_page_title;
                        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                        $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                        $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                        $data['footer_content'] = $footer_content;
                        //
                        if ($filter['key'] == 'users.CompanyName') {
                            $data['meta_title'] = 'Jobs at ' . ucwords($filter['value']) . ', Automotosocial';
                        } else {
                            $data['meta_title'] = 'Jobs in USA, Canada at Automotosocial';
                        }

                        $this->load->view($theme_name . '/_parts/header_view', $data);
                        $this->load->view($theme_name . '/_parts/page_banner');
                        $this->load->view($theme_name . '/_parts/jobs_list_view');
                        $this->load->view($theme_name . '/_parts/footer_view');
                        break;
                    default:
                        $pageData = $this->themes_pages_model->GetPage($company_id, $pageName);

                        if (!empty($pageData)) {
                            if ($pageData['page_status'] == 1) {
                                $pageData['page_title'] = str_replace("{{company_name}}", $company_name, $pageData['page_title']);
                                $pageData['page_content'] = str_replace("{{company_name}}", $company_name, $pageData['page_content']);
                                $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                                $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                                $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                                $data['footer_content'] = $footer_content;
                                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
                                $data['jobs_page_title'] = $jobs_page_title;
                                $data['pageData'] = $pageData;

                                $this->load->view($theme_name . '/_parts/header_view', $data);
                                $this->load->view($theme_name . '/_parts/page_banner');
                                $this->load->view($theme_name . '/_parts/page_content');
                                $this->load->view($theme_name . '/_parts/footer_view');
                            } else {
                                redirect('/', 'refresh');
                            }
                        } else {
                            $this->load->view($theme_name . '/_parts/header_view', $data);
                            $this->load->view($theme_name . '/_parts/404');
                            $this->load->view($theme_name . '/_parts/footer_view');
                        }
                        break;
                }
            } else {
                $this->load->view($theme_name . '/_parts/header_view', $data);
                $this->load->view($theme_name . '/index_view');
                $this->load->view($theme_name . '/_parts/footer_view');
            }
        } else {
            if (isset($data['maintenance_mode']) && $data['maintenance_mode'] == 0) {
                $this->load->view($theme_name . '/_parts/header_view', $data);
                $this->load->view($theme_name . '/index_view');
                $this->load->view($theme_name . '/_parts/footer_view');
            } else {
                $maintenance_mode_page_content = $this->check_domain->get_maintenance_mode_page_content($data['employer_id'], $data['sid']);
                $data['maintenance_mode_page_content'] = $maintenance_mode_page_content;
                $this->load->view('common/maintenance_mode', $data);
            }
        }
    }

    public function get_jobs($page_no = 1)
    {
    }


    public function contact_us()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $theme_name = !empty($data['theme_name']) ? $data['theme_name'] : 'theme-4';
        //
        if (empty($data['theme_name'])) {
            $data['theme_name'] = 'theme-4';
        }
        //
        $company_sid = $data['company_details']['sid'];
        $data['dealership_website'] = '';
        $data['pageName'] = 'contact_us';
        $data['isPaid'] = $data['is_paid'];
        $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_sid); //Pages Information
        $data['pages'] = $pages;
        $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'about-us'); //About Us Information
        $data['about_us'] = $about_us_text;
        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'footer_content');
        $data['footer_content'] = $footer_content;
        $website = $data['company_details']['WebSite'];
        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
        $data['jobs_page_title'] = $jobs_page_title;

        company_phone_regex_module_check($company_sid, $data, $this);

        if (!empty($website)) {
            $data['dealership_website'] = $website;
        }

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $contact_us_page = $data['contact_us_page'];

            if ($contact_us_page) {
                $data['heading_title'] = 'Contact';
                $data['page_inner_title'] = 'contact us';
                $this->load->library('form_validation');
                $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
                $this->form_validation->set_rules('message', 'Message', 'required|trim|xss_clean|min_length[50]');

                if ($this->form_validation->run() === FALSE) {
                    $data['company_details'] = $this->job_details->get_company_details($company_sid);
                    $data['meta_title'] = $data['meta_title'];
                    $data['meta_description'] = $data['meta_description'];
                    $data['meta_keywords'] = $data['meta_keywords'];
                    $data['embedded_code'] = $data['embedded_code'];
                    $this->load->view($theme_name . '/_parts/header_view', $data);
                    $this->load->view($theme_name . '/contact_view');
                    $this->load->view($theme_name . '/_parts/footer_view');
                } else {
                    $contact_name = $this->input->post('name');
                    $contact_email = $this->input->post('email');
                    $is_blocked = checkForBlockedEmail($contact_email);

                    if ($is_blocked) {
                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your enquiry. We will get back to you!');
                        redirect('/contact_us', 'refresh');
                    } else {
                        $contact_message = $this->input->post('message');
                        $employer_id = $data['employer_id'];
                        $this->contact_model->send_enquiry($employer_id, $contact_name, $contact_email, $contact_message, $data);
                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your enquiry. We will get back to you!');
                        redirect('/contact_us', 'refresh');
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error: </b>Contact Us Page not found!'); // contact us not allowed redirect to home page
                redirect('/', 'refresh');
            }
        } else { // end of portal status check
            redirect('/', 'refresh');
        }
    }

    function custom_file_to_AWS($fileName, $fileData)
    {
        $filePath = FCPATH . "assets/temp_files/";

        if (!file_exists($filePath)) { //make dir
            mkdir($filePath, 0777);
        }

        write_file("$filePath/" . $fileName . ".txt", $fileData); //write file
        $cover_letter = $fileName . '-' . generateRandomString(6) . '.txt';
        $aws = new AwsSdk();
        $result = $aws->putToBucket($cover_letter, $filePath . $fileName . '.txt', AWS_S3_BUCKET_NAME); //upload file to AWS
        return $cover_letter;
    }

    public function print_ad()
    {
        $sid = $this->uri->segment(2);
        $list = array();

        if ($sid != null && $sid > 0) {
            if (!is_numeric($sid)) {
                $sid = $this->job_details->fetch_job_id_from_random_key($sid);
            }
        }

        if ($sid != null && $sid > 0) {
            $list = $this->job_details->fetch_jobs_details($sid);
        }

        if (!empty($list)) { // check if the job exists
            $user_ip = getUserIP(); // get user Ip and Increment the job based on his IP
            $job_session = 'job_' . $user_ip . '_' . $sid;
            $job_increment_check = array($job_session => 'true');

            if (!$this->session->userdata($job_session)) { // increment job views and create session
                $this->job_details->increment_job_views($sid);
                $this->session->set_userdata($job_increment_check);
            }

            $country_id = $list['Location_Country'];
            $country_name = $this->job_details->get_countryname_by_id($country_id); // get country name
            $list['Location_Country'] = $country_name[0]['country_name'];
            $state_id = $list['Location_State'];
            $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
            $list['Location_State'] = $state_name[0]['state_name'];
            $JobCategorys = $list['JobCategory'];

            if ($JobCategorys != null) {
                $cat_id = explode(',', $JobCategorys);
                $job_category_array = array();

                foreach ($cat_id as $id) {
                    $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                    $job_category_array[] = $job_cat_name[0]['value'];
                }

                $job_category = implode(',', $job_category_array);
                $list['JobCategory'] = $job_category;
            }

            $date = substr($list['activation_date'], 0, 10); // change date format at front-end
            $date_array = explode('-', $date);
            $list['activation_date'] = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
            $data['listing'] = $list;
            $data['company_details'] = $this->job_details->get_company_details($list['user_sid']); //getting compnay details
            $data['company_details']['server_name'] = clean_domain($_SERVER['SERVER_NAME']);
            $this->load->view('common/print_job', $data);
        }
    }

    public function job_details($sid = null)
    {
        $sid = $this->input->post('sid') ? $this->input->post('sid') : $sid;
        if (strpos($sid, "-") !== FALSE) {
            $sid = @end((explode('-', $sid)));
        }
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $theme_name = !empty($data['theme_name']) ? $data['theme_name'] : 'theme-4';
        //
        if (empty($data['theme_name'])) {
            $data['theme_name'] = 'theme-4';
        }
        //
        $data['is_paid'] = 1;
        $company_id = $data['company_details']['sid'];
        $company_name = $data['company_details']['CompanyName'];
        $theme_name = $data['theme_name'];

        $company_sid = $data['company_details']['sid'];
        company_phone_regex_module_check($company_sid, $data, $this);

        if (!is_numeric($sid)) {
            $sid = $this->job_details->fetch_job_id_from_random_key($sid);
        }

        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
        $data['jobs_page_title'] = $jobs_page_title;

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            if ($sid != null && intval($sid) > 0) {
                $list = $this->job_details->fetch_company_jobs_details($sid);
                $user_sid = $list['user_sid'];
                $jobs_detail_page_title = $this->theme_meta_model->fGetThemeMetaData($user_sid, $theme_name, 'jobs_detail', 'jobs_detail_page_banner');
                $data['jobs_detail_page_banner_data'] = $jobs_detail_page_title;

                if (!empty($list)) {
                    $company_sid = $list['user_sid'];
                    $data['site_settings'] = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'site_settings', 'site_settings');
                    $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();
                    $company_email_templates = $this->check_domain->portal_email_templates($company_sid);
                    $application_acknowledgement_letter = array();
                    $enable_auto_responder_email = 0;

                    if (!empty($company_email_templates)) {
                        foreach ($company_email_templates as $key => $email_templates) {
                            if ($email_templates['template_code'] == 'application_acknowledgement_letter') {
                                $application_acknowledgement_letter = $email_templates;
                                $enable_auto_responder_email = $email_templates['enable_auto_responder'];
                            }
                        }
                    }

                    $data['application_acknowledgement_letter'] = $application_acknowledgement_letter;
                    $data['enable_auto_responder_email'] = $enable_auto_responder_email;
                    $data['pageName'] = 'job_details';
                    $data['isPaid'] = $data['is_paid'];
                    $data['dealership_website'] = '';
                    $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id); //Pages Information
                    $data['pages'] = $pages;
                    $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'about-us'); //About Us Information
                    $data['about_us'] = $about_us_text;
                    $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                    $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                    $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                    $data['footer_content'] = $footer_content;
                    $website = $data['company_details']['WebSite'];

                    if (!empty($website)) {
                        $data['dealership_website'] = $website;
                    }

                    $data['job_details'] = array();
                    $user_ip = getUserIP(); // get user Ip and Increment the job based on his IP
                    $job_session = 'job_' . $user_ip . '_' . $sid;
                    $job_increment_check = array($job_session => 'true');

                    if (!$this->session->userdata($job_session)) {
                        $this->job_details->increment_job_views($sid); // increment job views and create session
                        $this->session->set_userdata($job_increment_check);
                    }

                    $country_id = $list['Location_Country'];

                    if (!empty($country_id)) {
                        switch ($country_id) {
                            case 227:
                                $country_name = 'United States';
                                break;
                            case 38:
                                $country_name = 'Canada';
                                break;
                            default:
                                $country_name = '';
                                break;
                        }
                        $list['Location_Country'] = $country_name;
                    }

                    $state_id = $list['Location_State'];

                    if (!empty($state_id) && $state_id != 'undefined') {
                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                        $list['Location_State'] = $state_name[0]['state_name'];
                        $list['Location_Code'] = $state_name[0]['state_code'];
                    }

                    $JobCategorys = $list['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->job_details->get_job_category_name_by_id($id);

                            if (!empty($job_cat_name)) {
                                $job_category_array[] = $job_cat_name[0]['value'];
                            }
                        }

                        $job_category = implode(',', $job_category_array);
                        $list['JobCategory'] = $job_category;
                    }

                    $date = substr($list['activation_date'], 0, 10); // change date format at front-end
                    $date_array = explode('-', $date);
                    $list['activation_date'] = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
                    $questionnaire_sid = $list['questionnaire_sid'];

                    if ($questionnaire_sid > 0) {
                        $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);
                        if (!empty($portal_screening_questionnaires)) {
                            $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                            $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                            $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                            $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                            $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                            $list['q_pass_text'] = '';
                            $list['q_fail_text'] = '';
                            $list['my_id'] = 'q_question_' . $questionnaire_sid;
                            $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                            if ($screening_questions_numrows > 0) {
                                $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                foreach ($screening_questions as $qkey => $qvalue) {
                                    $questions_sid = $qvalue['sid'];
                                    $list['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                    $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                    if ($screening_answers_numrows) {
                                        $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                        foreach ($screening_answers as $akey => $avalue) {
                                            $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $list['TitleOnly'] = $list['Title'];
                    $list['Title'] = db_get_job_title($company_sid, $list['Title'], $list['Location_City'], $list['Location_State'], $list['Location_Country']);
                    $data_countries = $this->check_domain->get_active_countries(); //get all active `countries`

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']] = $this->check_domain->get_active_states($value['sid']); //get all active `states`
                    }

                    $data['active_countries'] = $data_countries;
                    $data['active_states'] = $data_states;
                    $data['formpost'] = array();
                    $data['states'] = htmlentities(json_encode($data_states));
                    $data['company_details'] = $this->job_details->get_company_details($list['user_sid']);
                    $data['next_job'] = ''; //getting next and previous jobs link STARTS
                    $data['prev_job'] = '';
                    $next_job_id = $this->job_details->next_job($list['sid'], $list['user_sid']);
                    $prev_job_id = $this->job_details->previous_job($list['sid'], $list['user_sid']);

                    if (!empty($next_job_id)) {
                        $next_id = $next_job_id['sid'];
                        $data['next_job'] = "job_details/$next_id";
                    }

                    if (!empty($prev_job_id)) {
                        $prev_id = $prev_job_id['sid'];
                        $data['prev_job'] = "job_details/$prev_id";
                    } //next and previous job link ENDS

                    $company_subdomain_url = STORE_PROTOCOL_SSL . db_get_sub_domain($company_sid); //Generate Share Links - start
                    $portal_job_url = $company_subdomain_url . '/job_details/' . $list['sid'];
                    $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                    $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                    $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                    $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($list['Title']) . '&amp;summary=' . urlencode($list['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-4.png"></a>';
                    $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list['Title']) . '</a>';
                    $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                    $links = '';
                    $links .= '<ul>';
                    $links .= '<li>' . $btn_facebook . '</li>';
                    $links .= '<li>' . $btn_linkedin . '</li>';
                    $links .= '<li>' . $btn_twitter . '</li>';

                    if ($theme_name == 'theme-4') {
                        $links .= '<li>' . $btn_tell_a_friend . '</li>';
                    }

                    $links .= '</ul>';
                    $list['share_links'] = $links; //Generate Share Links - end
                    $data['job_details'] = $list;
                    if (empty($data['job_details']['pictures'])) {
                        $data['image'] = base_url('assets/theme-1/images/new_logo.JPG');
                    } else {
                        $data['image'] = AWS_S3_BUCKET_URL . $data['job_details']['pictures'];
                    }

                    $action = '';

                    if (isset($_POST['action'])) {
                        $action = $this->input->post('action');
                    } else if (isset($_POST['perform_action'])) {
                        $action = $this->input->post('perform_action');
                    }

                    $job_company_sid = $data['company_details']['sid'];


                    switch ($action) { //Setting Validation Rules for Different Post Requests
                        case 'job_applicant':
                            $this->checkUserAppliedForJob($job_company_sid);
                            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
                            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
                            $this->form_validation->set_rules('pictures', 'Pictures', 'trim');
                            $this->form_validation->set_rules('YouTube_Video', 'YouTube Video', 'trim');
                            $this->form_validation->set_rules('email', 'E-mail', 'trim|required');
                            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
                            $this->form_validation->set_rules('address', 'Address', 'trim');
                            $this->form_validation->set_rules('city', 'City', 'trim');
                            $this->form_validation->set_rules('state', 'State', 'trim');
                            $this->form_validation->set_rules('country', 'Country', 'trim');
                            $this->form_validation->set_rules('resume', 'Resume', 'trim');
                            $this->form_validation->set_rules('cover_letter', 'Cover Letter', 'trim');
                            $this->form_validation->set_rules('referred_by_name', 'Referred By Name', 'trim');
                            $this->form_validation->set_rules('referred_by_email', 'Referred By Email', 'trim');
                            break;
                        case 'friendShare':
                            $this->form_validation->set_rules('sender_name', 'Your Name', 'trim|required');
                            $this->form_validation->set_rules('receiver_name', "Your Friend's Name", 'trim|required');
                            $this->form_validation->set_rules('receiver_email', "Your Friend's Email Address", 'trim|required');
                            $this->form_validation->set_rules('comment', 'Comments', 'trim|required');
                            break;
                        case 'send_tell_a_friend_email':
                            $this->form_validation->set_rules('perform_action', 'perform_action', 'trim');
                            break;
                    }

                    $more_career_oppurtunatity = db_get_sub_domain($job_company_sid);
                    $job_company_career_title = $this->theme_meta_model->fGetThemeMetaData($job_company_sid, $theme_name, 'jobs', 'jobs_page_title');

                    if (empty($job_company_career_title)) {
                        $job_company_career_title = 'jobs';
                    }

                    $data['more_career_oppurtunatity'] = 'https://' . $more_career_oppurtunatity . '/' . $job_company_career_title;

                    if ($this->form_validation->run() == false) {
                        if ($data['is_paid']) {
                            $this->load->view($theme_name . '/_parts/header_view', $data);
                            $this->load->view($theme_name . '/_parts/page_banner');
                            $this->load->view($theme_name . '/job_detail_view');
                            $this->load->view($theme_name . '/_parts/footer_view');
                        } else {
                            $this->load->view($theme_name . '/_parts/header_view', $data);
                            $this->load->view($theme_name . '/job_detail_view');
                            $this->load->view($theme_name . '/_parts/footer_view');
                        }
                    } else { //Handle Post
                        $action = '';
                        if (isset($_POST['action'])) {
                            $action = $this->input->post('action');
                        } else if (isset($_POST['perform_action'])) {
                            $action = $this->input->post('perform_action');
                        }

                        // Added on: 11-07-2019
                        // Reset phone numbers
                        $txt_phone_number = $this->input->post('phone_number', true);
                        if ($this->input->post('txt_phonenumber', true))
                            $txt_phone_number = $this->input->post('txt_phonenumber', true);

                        switch ($action) {
                            case 'job_applicant':

                                $redirecturl = "";
                                $applied_from = $this->input->post('applied_from');

                                if ($this->input->post('dr', true)) {
                                    echo "Applied job form";
                                    exit();
                                }

                                if ($applied_from == 'job') {
                                    $redirecturl = '/job_details/' . $sid;
                                } else if ($applied_from == 'jobs_list_view') {
                                    $redirecturl = '/jobs/';
                                } else {
                                    $redirecturl = '/';
                                }

                                $formpost = $this->input->post(NULL, TRUE);
                                //
                                if (!isset($formpost['g-recaptcha-response']) || empty($formpost['g-recaptcha-response'])) {
                                    $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                                    if ($this->input->post('dr', true)) {
                                        echo "Google captcha not set";
                                        exit();
                                    }
                                    return redirect($redirecturl, 'refresh');
                                }
                                //
                                $gr = verifyCaptcha($formpost['g-recaptcha-response']);
                                //
                                if (!$gr['success']) {
                                    $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                                    if ($this->input->post('dr', true)) {
                                        echo "Google captcha not set";
                                        exit();
                                    }
                                    return redirect($redirecturl, 'refresh');
                                }

                                $my_ip = getUserIP();

                                // if (in_array($company_sid, array("7", "51")) || $my_ip == '72.255.38.246') {
                                if (!in_array($job_company_sid, array("0"))) {

                                    $job_sid = $this->input->post('job_sid');
                                    $first_name = $this->input->post('first_name');
                                    $last_name = $this->input->post('last_name');
                                    $YouTube_Video = $this->input->post('YouTube_Video');
                                    $email = $this->input->post('email');
                                    $is_blocked_email = checkForBlockedEmail($email);

                                    if ($is_blocked_email == 'blocked') {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Blocked email";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }

                                    $phone_number = $txt_phone_number;
                                    $address = $this->input->post('address');
                                    $city = $this->input->post('city');
                                    $state = $this->input->post('state');
                                    $country = $this->input->post('country');
                                    $referred_by_name = $this->input->post('referred_by_name');
                                    $referred_by_email = $this->input->post('referred_by_email');
                                    $YouTube_code = '';
                                    $vType = 'no_video';
                                    $resume = '';
                                    $pictures = '';
                                    $cover_letter = '';
                                    $eeo_form = 'No';
                                    $job_details = $this->job_details->fetch_jobs_details($job_sid);
                                    $original_job_title = $job_details['Title'];

                                    if ($this->input->post('EEO') != NULL) {
                                        $eeo_form = $this->input->post('EEO');
                                    }

                                    if (check_company_status($job_company_sid) == 0) {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                        if ($this->input->post('dr', true)) {
                                            echo "Applied job";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                    //
                                    $already_applied = $this->job_details->check_job_applicant($job_sid, $email, $job_company_sid); //check if the user has already applied for this job

                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Already applied job";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    } else {  // fetch data and insert into database //echo 'New Applicant';

                                        if (!empty($YouTube_Video)) {
                                            $YouTube_code = substr($YouTube_Video, strpos($YouTube_Video, '=') + 1, strlen($YouTube_Video));
                                            $vType = 'youtube';
                                        } elseif (!empty($_FILES) && isset($_FILES['uploaded_file']) && !empty($_FILES['uploaded_file']['name'])) {
                                            $document = $_FILES['uploaded_file']['name'];
                                            $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));

                                            if ($_FILES['uploaded_file']['size'] > 0) {
                                                if ($ext == "mp4" || $ext == "m4a" || $ext == "m4v" || $ext == "f4v" || $ext == "f4a" || $ext == "m4b" || $ext == "m4r" || $ext == "f4b" || $ext == "mov" || $ext == 'mp3') {
                                                    error_reporting(E_ALL);
                                                    ini_set('display_errors', '1');
                                                    $random = generateRandomString(5);
                                                    // $company_id = $company_id;
                                                    $target_file_name = basename($_FILES["uploaded_file"]["name"]);
                                                    $file_name = strtolower($company_sid . DIRECTORY_SEPARATOR . $random . '_' . $target_file_name);
                                                    $e = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'uploaded_videos' . DIRECTORY_SEPARATOR;
                                                    $e2 = str_replace('manage_ams' . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR, '', $e);
                                                    $target_file = $e2 . $file_name;
                                                    $filename = $e2 . $company_sid;
                                                    if (!file_exists($e2)) {
                                                        mkdir($e2);
                                                    }
                                                    if (!file_exists($filename)) {
                                                        mkdir($filename);
                                                    }
                                                    if (!copy($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
                                                        $file_name = '';
                                                    }
                                                    $YouTube_code = $file_name;
                                                    $vType = 'uploaded';
                                                }
                                            }
                                        }

                                        $questionnaire_serialize = '';
                                        $total_score = 0;
                                        $total_questionnaire_score = 0;
                                        $q_passing = 0;
                                        $array_questionnaire = array();
                                        $overall_status = 'Pass';
                                        $is_string = 0;
                                        $screening_questionnaire_results = array();
                                        $job_type = '';
                                        $log_resume_name = '';
                                        $log_resume_extension = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey = $_POST['unique_key'];
                                            $myUploadData = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file = explode(".", $_FILES["resume"]["name"]);
                                                $file_name = str_replace(" ", "-", $file[0]);
                                                $resume = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                                $log_resume_name = $_FILES['resume']['name'];
                                                $log_resume_extension = $file[1];
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $pictures = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                                            $aws = new AwsSdk();
                                            // $aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                                            unlink('images/' . $pictures);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $cover_letter = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid = $data['job_details']['user_sid'];
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                            if ($this->input->post('dr', true)) {
                                                echo "Application for job";
                                                exit();
                                            }
                                            if ($applied_from == 'job') {
                                                redirect('/job-feed-details/' . $sid, 'refresh');
                                            } else if ($applied_from == 'jobs_list_view') {
                                                redirect('/job-feed/');
                                            } else {
                                                redirect('/', 'refresh');
                                            }

                                            break;
                                        }
                                        //
                                        $status_array = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Default Status
                                        // Check if user has already applied in this company for any other job
                                        $portal_job_applications_sid = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully = 0;
                                        $date_applied = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid' => $employer_sid,
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'YouTube_Video' => $YouTube_code,
                                                'video_type' => $vType,
                                                'email' => $email,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'resume' => $resume,
                                                'pictures' => $pictures,
                                                'cover_letter' => $cover_letter,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'notified_by' => $this->input->post('contactPreference', true),
                                                'referred_by_email' => $referred_by_email
                                            );

                                            $output = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid' => $job_applications_sid,
                                                    'company_sid' => $employer_sid,
                                                    'job_sid' => $job_sid,
                                                    'date_applied' => $date_applied,
                                                    'status' => $status_array['status_name'],
                                                    'status_sid' => $status_array['status_sid'],
                                                    'questionnaire' => $questionnaire_serialize,
                                                    'score' => $total_score,
                                                    'passing_score' => $q_passing,
                                                    'applicant_source' => STORE_FULL_URL_SSL,
                                                    'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                    'ip_address' => getUserIP(),
                                                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form' => $eeo_form,
                                                    'resume' => $resume ? $resume : '',
                                                    'last_update' => date('Y-m-d')
                                                );

                                                $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                                $job_added_successfully = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid = $portal_job_applications_sid;

                                            $update_data_primary = array(
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'notified_by' => $this->input->post('contactPreference', true),
                                                'referred_by_email' => $referred_by_email
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube = array('YouTube_Video' => $YouTube_code, 'video_type' => $vType);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume = array('resume' => $resume);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures = array('pictures' => $pictures);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter = array('cover_letter' => $cover_letter);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }

                                            $old_s3_resume = $this->job_details->get_old_resume($job_applications_sid);
                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            if (!empty($old_s3_resume)) {
                                                $resume_log_data = array();
                                                $resume_log_data['company_sid'] = $employer_sid;
                                                $resume_log_data['user_type'] = 'Applicant';
                                                $resume_log_data['user_sid'] = $job_applications_sid;
                                                $resume_log_data['user_email'] = $email;
                                                $resume_log_data['requested_by'] = 0;
                                                $resume_log_data['requested_subject'] = 'NULL';
                                                $resume_log_data['requested_message'] = 'NULL';
                                                $resume_log_data['requested_ip_address'] = getUserIP();
                                                $resume_log_data['requested_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                                                $resume_log_data['request_status'] = 3;
                                                $resume_log_data['is_respond'] = 1;
                                                $resume_log_data['resume_original_name'] = $log_resume_name;
                                                $resume_log_data['resume_s3_name'] = $resume;
                                                $resume_log_data['resume_extension'] = $log_resume_extension;
                                                $resume_log_data['old_resume_s3_name'] = $old_s3_resume;
                                                $resume_log_data['response_date'] = date('Y-m-d H:i:s');
                                                $resume_log_data['requested_date'] = date('Y-m-d H:i:s');
                                                $resume_log_data['job_sid'] = $job_sid;
                                                $resume_log_data['job_type'] = "job";

                                                $this->job_details->insert_resume_log($resume_log_data);
                                            }

                                            $insert_job_list = array(
                                                'portal_job_applications_sid' => $job_applications_sid,
                                                'company_sid' => $employer_sid,
                                                'job_sid' => $job_sid,
                                                'date_applied' => $date_applied,
                                                'status' => $status_array['status_name'],
                                                'status_sid' => $status_array['status_sid'],
                                                'questionnaire' => $questionnaire_serialize,
                                                'score' => $total_score,
                                                'passing_score' => $q_passing,
                                                'applicant_source' => STORE_FULL_URL_SSL,
                                                'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                'ip_address' => getUserIP(),
                                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form' => $eeo_form,
                                                'resume' => $resume ? $resume : '',
                                                'last_update' => date('Y-m-d')
                                            );

                                            $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                            $job_added_successfully = $jobs_list_result[1];
                                        }

                                        // Add applicant to Queue
                                        storeApplicantInQueueToProcess([
                                            "portal_job_applications_sid" => $job_applications_sid,
                                            "portal_applicant_job_sid" => $portal_applicant_jobs_list_sid,
                                            "job_sid" => $job_sid,
                                            "company_sid" => $employer_sid,
                                        ]);

                                        if (!isset($resume) || empty($resume) || $resume == '') {
                                            sendResumeEmailToApplicant([
                                                'company_sid' => $company_sid,
                                                'company_name' => $company_name,
                                                'job_list_sid' => $job_sid,
                                                'user_sid' => $job_applications_sid,
                                                'user_type' => 'applicant',
                                                'requested_job_sid' => $job_sid,
                                                'requested_job_type' => 'job'
                                            ], false);
                                        }

                                        if ($job_added_successfully == 1) { // send confirmation emails to Primary admin
                                            $company_name = $data['company_details']['CompanyName'];
                                            $company_email = TO_EMAIL_INFO; //$data['company_details']['email'];
                                            $resume_url = '';
                                            $resume_anchor = '';
                                            $profile_anchor = '<a href="https://www.automotohr.com/applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) { // resume check here - change to button
                                                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title = $data['job_details']['Title'];
                                            $replacement_array = array();
                                            $replacement_array['site_url'] = base_url();
                                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title'] = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number'] = $phone_number;
                                            $replacement_array['city'] = $city;
                                            $replacement_array['company_name'] = $company_name;
                                            $message_hf = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status = get_notifications_status($company_sid);
                                            $my_debug_message = json_encode($replacement_array);
                                            $applicant_notifications_status = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                                            } /*else {
                                            // mail(TO_EMAIL_DEV, STORE_NAME.' Apply Now Debug - No Status Record Found', $my_debug_message);
                                        } */

                                            $applicant_notification_contacts = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname'] = $first_name;
                                                        $replacement_array['lastname'] = $last_name;
                                                        $replacement_array['email'] = $email;
                                                        $replacement_array['company_name'] = $company_name;
                                                        $replacement_array['resume_link'] = $resume_anchor;
                                                        $replacement_array['applicant_profile_link'] = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }

                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                //56357
                                                $from = REPLY_TO;
                                                $subject = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body = $acknowledgement_email_body;
                                                $message_data = array();
                                                $message_data['contact_name'] = $first_name . ' ' . $last_name;
                                                $message_data['to_id'] = $email;
                                                $message_data['from_type'] = 'employer';
                                                $message_data['to_type'] = 'admin';
                                                $message_data['job_id'] = $job_applications_sid;
                                                $message_data['users_type'] = 'applicant';
                                                $message_data['subject'] = 'Application Acknowledgement Letter';
                                                $message_data['message'] = $body;
                                                $message_data['date'] = date('Y-m-d H:i:s');
                                                $message_data['from_id'] = REPLY_TO;
                                                $message_data['identity_key'] = generateRandomString(48);
                                                $message_hf = message_header_footer_domain($company_id, $company_name);
                                                $secret_key = $message_data['identity_key'] . "__";
                                                $autoemailbody = $message_hf['header']
                                                    . '<p>Subject: ' . $subject . '</p>'
                                                    . $body
                                                    . $message_hf['footer']
                                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                    . $secret_key . '</div>';

                                                sendMail(REPLY_TO, $email, $subject, $autoemailbody, $from_name, REPLY_TO);
                                                $sent_to_pm = $this->contact_model->save_message($message_data);
                                                $email_log_autoresponder = array();
                                                $email_log_autoresponder['company_sid'] = $company_id;
                                                $email_log_autoresponder['sender'] = REPLY_TO;
                                                $email_log_autoresponder['receiver'] = $email;
                                                $email_log_autoresponder['from_type'] = 'employer';
                                                $email_log_autoresponder['to_type'] = 'admin';
                                                $email_log_autoresponder['users_type'] = 'applicant';
                                                $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                                                $email_log_autoresponder['subject'] = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $email_log_autoresponder['message'] = $autoemailbody;
                                                $email_log_autoresponder['job_or_employee_id'] = $job_applications_sid;
                                                $save_email_log = $this->contact_model->save_email_log_autoresponder($email_log_autoresponder);
                                            }

                                            // check if screening questionnaire is attached to email and send pass or fail email to applicant
                                            if ($_POST['questionnaire_sid'] > 0) {  // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire = array();
                                                $q_name = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing = 0;
                                                    $post_questions_sid = $value;
                                                    $caption = 'caption' . $value;
                                                    $type = 'type' . $value;
                                                    $answer = $_POST[$type] . $value;
                                                    $questions_type = $_POST[$type];
                                                    $my_question = '';
                                                    $individual_score = 0;
                                                    $individual_passing_score = 0;
                                                    $individual_status = 'Pass';
                                                    $result_status = array();

                                                    if (isset($_POST[$caption])) {
                                                        $my_question = $_POST[$caption];
                                                    }

                                                    $my_answer = NULL;

                                                    if (isset($_POST[$answer])) {
                                                        $my_answer = $_POST[$answer];
                                                    }

                                                    if ($questions_type != 'string') { // get the question possible score
                                                        $q_passing = $this->job_details->get_possible_score_of_questions($post_questions_sid, $questions_type);
                                                    }

                                                    if ($my_answer != NULL) { // It is required question
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
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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
                                                        } else { // hassan WORKING area
                                                            $result = explode('@#$', $my_answer);
                                                            $total_questionnaire_score += $q_passing;
                                                            $a = $result[0];
                                                            $answered = $a;
                                                            $answered_result_status = '';
                                                            $answered_question_score = 0;

                                                            if (isset($result[1])) {
                                                                $answered_question_sid = $result[1];
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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

                                                        if (!empty($result_status)) {
                                                            if (in_array('Fail', $result_status)) {
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

                                                    $array_questionnaire[$my_question] = array(
                                                        'answer' => $answered,
                                                        'passing_score' => $individual_passing_score,
                                                        'score' => $individual_score,
                                                        'status' => $individual_status,
                                                        'answered_result_status' => $answered_result_status,
                                                        'answered_question_score' => $answered_question_score
                                                    );
                                                    //echo '<pre>'; print_r($array_questionnaire); echo '</pre><hr>';
                                                } // here

                                                $questionnaire_result = $overall_status;
                                                $datetime = date('Y-m-d H:i:s');
                                                $remote_addr = getUserIP();
                                                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                                                $questionnaire_data = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $questionnaire_serialize = serialize($questionnaire_data);
                                                $array_questionnaire_serialize = serialize($array_questionnaire);
                                                //echo '<pre>'; print_r($questionnaire_data); echo '</pre><hr>';
                                                $screening_questionnaire_results = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire_serialize,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $this->job_details->update_questionnaire_result($portal_applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
                                                $this->job_details->insert_questionnaire_result($screening_questionnaire_results);
                                                $send_mail = false;
                                                $mail_body = '';

                                                if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                                                    $send_mail = true;
                                                    $mail_body = $q_pass_text;
                                                }

                                                if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                                                    $send_mail = true;
                                                    $mail_body = $q_fail_text;
                                                }

                                                if ($send_mail) {
                                                    $from = TO_EMAIL_INFO;
                                                    $fromname = $company_name;
                                                    $title = $data['job_details']['Title'];
                                                    $subject = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to = $email;
                                                    $mail_body = str_replace('{{company_name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant_name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job_title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first_name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last_name}}', ucwords($last_name), $mail_body);
                                                    $mail_body = str_replace('{{company-name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant-name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job-title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first-name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last-name}}', ucwords($last_name), $mail_body);
                                                    sendMail($from, $to, $subject, $mail_body, $fromname);
                                                }
                                            }

                                            if ($eeo_form == 'Yes') { //Getting data for EEO Form Starts
                                                $eeo_data = array(
                                                    'application_sid' => $job_applications_sid,
                                                    'users_type' => "applicant",
                                                    'portal_applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'us_citizen' => $this->input->post('us_citizen'),
                                                    'visa_status ' => $this->input->post('visa_status'),
                                                    'group_status' => $this->input->post('group_status'),
                                                    'veteran' => $this->input->post('veteran'),
                                                    'disability' => $this->input->post('disability'),
                                                    'gender' => $this->input->post('gender'),
                                                    'is_expired' => 1
                                                );

                                                $this->job_details->save_eeo_form($eeo_data);
                                            } //Getting data for EEO Form Ends

                                            $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        }

                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Job Applied Form";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid . "?applied_by=" . $portal_applicant_jobs_list_sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/' . "?applied_by=" . $portal_applicant_jobs_list_sid, 'refresh');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                } else {
                                    $job_sid = $this->input->post('job_sid');
                                    $first_name = $this->input->post('first_name');
                                    $last_name = $this->input->post('last_name');
                                    $YouTube_Video = $this->input->post('YouTube_Video');
                                    $email = $this->input->post('email');
                                    $is_blocked_email = checkForBlockedEmail($email);

                                    if ($is_blocked_email == 'blocked') {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Job application success";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }

                                    $phone_number = $txt_phone_number;
                                    $address = $this->input->post('address');
                                    $city = $this->input->post('city');
                                    $state = $this->input->post('state');
                                    $country = $this->input->post('country');
                                    $referred_by_name = $this->input->post('referred_by_name');
                                    $referred_by_email = $this->input->post('referred_by_email');
                                    $YouTube_code = '';
                                    $vType = 'no_video';
                                    $resume = '';
                                    $pictures = '';
                                    $cover_letter = '';
                                    $eeo_form = 'No';
                                    $job_details = $this->job_details->fetch_jobs_details($job_sid);
                                    $original_job_title = $job_details['Title'];

                                    if ($this->input->post('EEO') != NULL) {
                                        $eeo_form = $this->input->post('EEO');
                                    }
                                    if (!empty($YouTube_Video)) {
                                        $YouTube_code = substr($YouTube_Video, strpos($YouTube_Video, '=') + 1, strlen($YouTube_Video));
                                        $vType = 'youtube';
                                    } elseif (!empty($_FILES) && isset($_FILES['uploaded_file']) && !empty($_FILES['name'])) {

                                        $document = $_FILES['name'];
                                        $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));

                                        if ($_FILES['uploaded_file']['size'] > 0) {
                                            if ($ext == "mp4" || $ext == "m4a" || $ext == "m4v" || $ext == "f4v" || $ext == "f4a" || $ext == "m4b" || $ext == "m4r" || $ext == "f4b" || $ext == "mov" || $ext == 'mp3') {
                                                error_reporting(E_ALL);
                                                ini_set('display_errors', '1');
                                                $random = generateRandomString(5);
                                                // $company_id = $company_id;
                                                $target_file_name = basename($_FILES["uploaded_file"]["name"]);
                                                $file_name = strtolower($company_sid . "\\" . $random . '_' . $target_file_name);
                                                $e = dirname(__FILE__) . '\\assets\uploaded_videos\\';
                                                $e2 = str_replace('manage_ams\application\controllers\\', '', $e);
                                                $target_file = $e2 . $file_name;
                                                $filename = $e2 . $company_sid;
                                                if (!file_exists($e2)) {
                                                    mkdir($e2);
                                                }
                                                if (!file_exists($filename)) {
                                                    mkdir($filename);
                                                }
                                                if (!copy($_FILES["uploaded_file"]["tmp_name"], $target_file)) {
                                                    $file_name = '';
                                                }
                                                $YouTube_code = $file_name;
                                                $vType = 'uploaded';
                                            }
                                        }
                                    }

                                    //
                                    if (check_company_status($company_sid) == 0) {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                        if ($this->input->post('dr', true)) {
                                            echo "Job application success";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                    //
                                    $already_applied = $this->job_details->check_job_applicant($job_sid, $email, $company_sid); //check if the user has already applied for this job

                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Already applied for job";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    } else { // fetch data and insert into database //echo 'New Applicant';
                                        $questionnaire_serialize = '';
                                        $total_score = 0;
                                        $total_questionnaire_score = 0;
                                        $q_passing = 0;
                                        $array_questionnaire = array();
                                        $overall_status = 'Pass';
                                        $is_string = 0;
                                        $screening_questionnaire_results = array();
                                        $job_type = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey = $_POST['unique_key'];
                                            $myUploadData = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file = explode(".", $_FILES["resume"]["name"]);
                                                $file_name = str_replace(" ", "-", $file[0]);
                                                $resume = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $pictures = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                                            $aws = new AwsSdk();
                                            // $aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                                            unlink('images/' . $pictures);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $cover_letter = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid = $data['job_details']['user_sid'];
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                            if ($this->input->post('dr', true)) {
                                                echo "Job application success";
                                                exit();
                                            }
                                            if ($applied_from == 'job') {
                                                redirect('/job_details/' . $sid, 'refresh');
                                            } else if ($applied_from == 'jobs_list_view') {
                                                redirect('/jobs/');
                                            } else {
                                                redirect('/', 'refresh');
                                            }
                                        }
                                        //
                                        $status_array = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Defult Status
                                        // Check if user has already applied in this company for any other job
                                        $portal_job_applications_sid = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully = 0;
                                        $date_applied = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid' => $employer_sid,
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'YouTube_Video' => $YouTube_code,
                                                'video_type' => $vType,
                                                'email' => $email,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'resume' => $resume,
                                                'pictures' => $pictures,
                                                'cover_letter' => $cover_letter,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            $output = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid' => $job_applications_sid,
                                                    'company_sid' => $employer_sid,
                                                    'job_sid' => $job_sid,
                                                    'date_applied' => $date_applied,
                                                    'status' => $status_array['status_name'],
                                                    'status_sid' => $status_array['status_sid'],
                                                    'questionnaire' => $questionnaire_serialize,
                                                    'score' => $total_score,
                                                    'passing_score' => $q_passing,
                                                    'applicant_source' => STORE_FULL_URL_SSL,
                                                    'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                    'ip_address' => getUserIP(),
                                                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form' => $eeo_form
                                                );

                                                $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                                $job_added_successfully = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid = $portal_job_applications_sid;

                                            $update_data_primary = array(
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube = array('YouTube_Video' => $YouTube_code, 'video_type' => $vType);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume = array('resume' => $resume);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures = array('pictures' => $pictures);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter = array('cover_letter' => $cover_letter);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }

                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            $insert_job_list = array(
                                                'portal_job_applications_sid' => $job_applications_sid,
                                                'company_sid' => $employer_sid,
                                                'job_sid' => $job_sid,
                                                'date_applied' => $date_applied,
                                                'status' => $status_array['status_name'],
                                                'status_sid' => $status_array['status_sid'],
                                                'questionnaire' => $questionnaire_serialize,
                                                'score' => $total_score,
                                                'passing_score' => $q_passing,
                                                'applicant_source' => STORE_FULL_URL_SSL,
                                                'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                'ip_address' => getUserIP(),
                                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form' => $eeo_form
                                            );

                                            $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                            $job_added_successfully = $jobs_list_result[1];
                                        }

                                        if ($job_added_successfully == 1) { // send confirmation emails to Primary admin
                                            $company_name = $data['company_details']['CompanyName'];
                                            $company_email = TO_EMAIL_INFO; //$data['company_details']['email'];
                                            $resume_url = '';
                                            $resume_anchor = '';
                                            $profile_anchor = '<a href="https://www.automotohr.com/applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) { // resume check here - change to button
                                                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title = $data['job_details']['Title'];
                                            $replacement_array = array();
                                            $replacement_array['site_url'] = base_url();
                                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title'] = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number'] = $phone_number;
                                            $replacement_array['city'] = $city;
                                            $replacement_array['company_name'] = $company_name;
                                            $message_hf = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status = get_notifications_status($company_sid);
                                            $my_debug_message = json_encode($replacement_array);
                                            $applicant_notifications_status = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                                            } /*else {
                                            // mail(TO_EMAIL_DEV, STORE_NAME.' Apply Now Debug - No Status Record Found', $my_debug_message);
                                        } */

                                            $applicant_notification_contacts = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname'] = $first_name;
                                                        $replacement_array['lastname'] = $last_name;
                                                        $replacement_array['email'] = $email;
                                                        $replacement_array['company_name'] = $company_name;
                                                        $replacement_array['resume_link'] = $resume_anchor;
                                                        $replacement_array['applicant_profile_link'] = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }

                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                //56357
                                                $from = REPLY_TO;
                                                $subject = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body = $acknowledgement_email_body;
                                                $message_data = array();
                                                $message_data['contact_name'] = $first_name . ' ' . $last_name;
                                                $message_data['to_id'] = $email;
                                                $message_data['from_type'] = 'employer';
                                                $message_data['to_type'] = 'admin';
                                                $message_data['job_id'] = $job_applications_sid;
                                                $message_data['users_type'] = 'applicant';
                                                $message_data['subject'] = 'Application Acknowledgement Letter';
                                                $message_data['message'] = $body;
                                                $message_data['date'] = date('Y-m-d H:i:s');
                                                $message_data['from_id'] = REPLY_TO;
                                                $message_data['identity_key'] = generateRandomString(48);
                                                $message_hf = message_header_footer_domain($company_id, $company_name);
                                                $secret_key = $message_data['identity_key'] . "__";
                                                $autoemailbody = $message_hf['header']
                                                    . '<p>Subject: ' . $subject . '</p>'
                                                    . $body
                                                    . $message_hf['footer']
                                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                    . $secret_key . '</div>';

                                                sendMail(REPLY_TO, $email, $subject, $autoemailbody, $from_name, REPLY_TO);
                                                $sent_to_pm = $this->contact_model->save_message($message_data);
                                                $email_log_autoresponder = array();
                                                $email_log_autoresponder['company_sid'] = $company_id;
                                                $email_log_autoresponder['sender'] = REPLY_TO;
                                                $email_log_autoresponder['receiver'] = $email;
                                                $email_log_autoresponder['from_type'] = 'employer';
                                                $email_log_autoresponder['to_type'] = 'admin';
                                                $email_log_autoresponder['users_type'] = 'applicant';
                                                $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                                                $email_log_autoresponder['subject'] = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $email_log_autoresponder['message'] = $autoemailbody;
                                                $email_log_autoresponder['job_or_employee_id'] = $job_applications_sid;
                                                $save_email_log = $this->contact_model->save_email_log_autoresponder($email_log_autoresponder);
                                            }

                                            // check if screening questionnaire is attached to email and send pass or fail email to applicant
                                            if ($_POST['questionnaire_sid'] > 0) {  // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire = array();
                                                $q_name = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing = 0;
                                                    $post_questions_sid = $value;
                                                    $caption = 'caption' . $value;
                                                    $type = 'type' . $value;
                                                    $answer = $_POST[$type] . $value;
                                                    $questions_type = $_POST[$type];
                                                    $my_question = '';
                                                    $individual_score = 0;
                                                    $individual_passing_score = 0;
                                                    $individual_status = 'Pass';
                                                    $result_status = array();

                                                    if (isset($_POST[$caption])) {
                                                        $my_question = $_POST[$caption];
                                                    }

                                                    $my_answer = NULL;

                                                    if (isset($_POST[$answer])) {
                                                        $my_answer = $_POST[$answer];
                                                    }

                                                    if ($questions_type != 'string') { // get the question possible score
                                                        $q_passing = $this->job_details->get_possible_score_of_questions($post_questions_sid, $questions_type);
                                                    }

                                                    if ($my_answer != NULL) { // It is required question
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
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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
                                                        } else { // hassan WORKING area
                                                            $result = explode('@#$', $my_answer);
                                                            $total_questionnaire_score += $q_passing;
                                                            $a = $result[0];
                                                            $answered = $a;
                                                            $answered_result_status = '';
                                                            $answered_question_score = 0;

                                                            if (isset($result[1])) {
                                                                $answered_question_sid = $result[1];
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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

                                                        if (!empty($result_status)) {
                                                            if (in_array('Fail', $result_status)) {
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

                                                    $array_questionnaire[$my_question] = array(
                                                        'answer' => $answered,
                                                        'passing_score' => $individual_passing_score,
                                                        'score' => $individual_score,
                                                        'status' => $individual_status,
                                                        'answered_result_status' => $answered_result_status,
                                                        'answered_question_score' => $answered_question_score
                                                    );
                                                    //echo '<pre>'; print_r($array_questionnaire); echo '</pre><hr>';
                                                } // here

                                                $questionnaire_result = $overall_status;
                                                $datetime = date('Y-m-d H:i:s');
                                                $remote_addr = getUserIP();
                                                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                                                $questionnaire_data = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $questionnaire_serialize = serialize($questionnaire_data);
                                                $array_questionnaire_serialize = serialize($array_questionnaire);
                                                //echo '<pre>'; print_r($questionnaire_data); echo '</pre><hr>';
                                                $screening_questionnaire_results = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire_serialize,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $this->job_details->update_questionnaire_result($portal_applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
                                                $this->job_details->insert_questionnaire_result($screening_questionnaire_results);
                                                $send_mail = false;
                                                $mail_body = '';

                                                if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                                                    $send_mail = true;
                                                    $mail_body = $q_pass_text;
                                                }

                                                if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                                                    $send_mail = true;
                                                    $mail_body = $q_fail_text;
                                                }

                                                if ($send_mail) {
                                                    $from = TO_EMAIL_INFO;
                                                    $fromname = $company_name;
                                                    $title = $data['job_details']['Title'];
                                                    $subject = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to = $email;
                                                    $mail_body = str_replace('{{company_name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant_name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job_title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first_name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last_name}}', ucwords($last_name), $mail_body);
                                                    $mail_body = str_replace('{{company-name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant-name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job-title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first-name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last-name}}', ucwords($last_name), $mail_body);
                                                    sendMail($from, $to, $subject, $mail_body, $fromname);
                                                }
                                            }

                                            if ($eeo_form == 'Yes') { //Getting data for EEO Form Starts
                                                $eeo_data = array(
                                                    'application_sid' => $job_applications_sid,
                                                    'users_type' => "applicant",
                                                    'portal_applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'us_citizen' => $this->input->post('us_citizen'),
                                                    'visa_status ' => $this->input->post('visa_status'),
                                                    'group_status' => $this->input->post('group_status'),
                                                    'veteran' => $this->input->post('veteran'),
                                                    'disability' => $this->input->post('disability'),
                                                    'gender' => $this->input->post('gender'),
                                                    'is_expired' => 1
                                                );

                                                $this->job_details->save_eeo_form($eeo_data);
                                            } //Getting data for EEO Form Ends

                                            $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        }

                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Applied job form";
                                            exit();
                                        }
                                        if ($applied_from == 'job') {
                                            redirect('/job_details/' . $sid . "?applied_by=" . $portal_applicant_jobs_list_sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/' . "?applied_by=" . $portal_applicant_jobs_list_sid, 'refresh');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                }

                                break;
                            case 'friendShare':
                                $sender_name = $this->input->post('sender_name');
                                $receiver_name = $this->input->post('receiver_name');
                                $receiver_email = $this->input->post('receiver_email');
                                $sender_email = $this->input->post('sender_email');
                                $comment = $this->input->post('comment');
                                $is_sender_blocked = checkForBlockedEmail($sender_email);
                                $is_receiver_blocked = checkForBlockedEmail($receiver_email);

                                if ($is_sender_blocked == 'blocked' || $is_receiver_blocked == "blocked") {
                                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you.');
                                    redirect('/job_details/' . $sid, 'refresh');
                                    break;
                                }

                                $check_already_request = $this->job_details->check_if_applied_already($sid);

                                if (isset($_SERVER['HTTP_COOKIE']) && $check_already_request < 3) {
                                    // if(isset($_POST['g-recaptcha-response']) && isset($_SERVER['HTTP_COOKIE']) && !empty($this->input->post('g-recaptcha-response'))  && $check_already_request < 3){
                                    $this->job_details->save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, 'sent');
                                    $this->job_details->friend_share_job($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $data);
                                    if ($check_already_request != 0) {
                                        //Send Email to Ali Bhai
                                        sendMail('info@automotohr.com', 'dev@automotohr.com', 'Spam Alert!', print_r($_POST, true) . print_r($_SERVER, true));
                                    }
                                } else {
                                    $this->job_details->save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, 'not-sent');
                                }
                                $post_data = $_POST;
                                $server_data = $_SERVER;
                                // mail('mubashar.ahmed@egenienext.com', 'Friend Share Spam', print_r($post_data, true ));
                                // mail('mubashar.ahmed@egenienext.com', 'FS Spam Server', print_r($server_data, true ));
                                redirect('/job_details/' . $sid, 'refresh');
                                break;
                            case 'send_tell_a_friend_email':
                                sendMail('info@automotohr.com', 'dev@automotohr.com', 'send_tell_a_friend_email_ams!', print_r($_POST, true) . print_r($_SERVER, true));
                                // $senderName                                     = $_POST['sender_name'];
                                // $receiverName                                   = $_POST['receiver_name'];
                                // $receiverEmail                                  = $_POST['receiver_email'];
                                // $message                                        = $_POST['message'];
                                // $subject                                        = ucwords($senderName) . ' Recommends ' . $list['Title'];
                                // $message_body                                   = '';
                                // $message_body                                   .= '<h1>' . 'Hi ' . $receiverName . '</h1>';
                                // $message_body                                   .= '<h3>' . $senderName . '</h3>';
                                // $message_body                                   .= '<p>' . 'Has Recommended The Following Job on our Website to You:' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" target="_blank" href="' . base_url('job_details') . '/' . $sid . '">' . $list['Title'] . '</a>' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '<strong>' . 'Attached Personal Message' . '</strong>' . '</p>';
                                // $message_body                                   .= '<p>' . $message . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= FROM_INFO_EMAIL_DISCLAIMER_MSG;
                                //
                                // if (base_url() == 'http://ams.example/') { //echo 'Local Working';
                                //     save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                // } else { //echo 'LIVE';
                                //     save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                //     sendMail(FROM_EMAIL_INFO, $receiverEmail, $subject, $message_body);
                                // }
                                break;
                        }
                    }
                } else { //Job Id Is not 0 But Job Not Found
                    $this->session->set_flashdata('message', 'No Active job found!');
                    redirect('/', 'refresh');
                }
            } else { //Job Id Is 0 or Null
                $this->session->set_flashdata('message', 'No Active job found!');
                redirect('/', 'refresh');
            }
        } else { // Portal Deactivated or Maintenance Mode
            redirect('/', 'refresh');
        }
    }

    public function recaptcha($str)
    {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = '6Les2Q0TAAAAAPpmnngcC7RdzvAq1CuAVLqic_ei';
        $url = $google_url . "?secret=" . $secret . "&response=" . $str;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);

        if ($res['success']) {
            return TRUE;
        } else {
            $this->form_validation->set_message('recaptcha', 'The reCAPTCHA field is telling me that you are a robot. Shall we give it another try?');
            return $str;
        }
    }

    public function ajax_responder()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);

        if (!$this->session->userdata('portal_info')) {
            $this->session->set_userdata('portal_info', $data);
        }

        $theme_name = $data['theme_name'];

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            if (array_key_exists('perform_action', $_POST)) {
                $perform_action = strtoupper($_POST['perform_action']);
                switch ($perform_action) {
                    case 'GETFILECONTENT':
                        $myToken = $_POST['token'];
                        $downloadUrl = $_POST['url'];
                        $fileId = $_POST['document'];
                        $token = array(
                            'access_token' => $myToken,
                            'refresh_token' => $myToken
                        );

                        $json_token = json_encode($token);
                        $myClient = $this->google_auth->Authorize($json_token);
                        $myService = new Google_Service_Drive($myClient);
                        //$fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
                        $file = $myService->files->get($fileId);
                        $fileType = $file->getMimeType();
                        $fileName = $file->name;

                        if ($fileType == 'application/vnd.google-apps.document') {
                            $fileType = 'application/rtf';
                        }

                        $fileExtension = explode('/', $fileType);
                        $fileExtension = $fileExtension[1];
                        $fileContent = $myService->files->export($fileId, $fileType, array('alt' => 'media'));

                        $filePath = FCPATH . "assets/temp_files/"; //making Directory to store

                        if (!file_exists($filePath)) {
                            mkdir($filePath, 0777);
                        }

                        $fileNameWithExt = clean($fileName) . '.' . $fileExtension;
                        $tempFile = fopen($filePath . $fileNameWithExt, 'w'); //Write Temporary File on Server
                        fwrite($tempFile, $fileContent);
                        fclose($tempFile);
                        $resume = generateRandomString(6) . "_" . $fileNameWithExt;
                        $aws = new AwsSdk();
                        $aws->putToBucket($resume, $filePath . $fileNameWithExt, AWS_S3_BUCKET_NAME); //Upload To Aws
                        echo $resume;
                        break;
                    default:
                        break;
                }
            }
        }
    }

    public function listing_feeds()
    {
        if (isset($_REQUEST['feedId']) && $_REQUEST['feedId'] == 6) {
            $sid = $this->isActiveFeed();
            $list = $this->job_details->get_all_company_jobs_ams(
                array(),
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                array()
            );
            $this->addLastRead(1);
            // Filter jobs
            $activeCompanies = $this->job_details->get_all_active_companies($sid);

            header('Content-type: text/xml');
            header('Pragma: public');
            header('Cache-control: private');
            header('Expires: -1');
            echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

            echo '<xml>';
            echo "<source>
            <publisher>Automoto Social</publisher>
            <publisherurl><![CDATA[https://www.automotosocial.com/]]></publisherurl>
            <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";

            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    //
                    if (!in_array($value['user_sid'], $activeCompanies))
                        continue;
                    $has_job_approval_rights = $value['has_job_approval_rights'];

                    if ($has_job_approval_rights == 1) {
                        $approval_right_status = $value['approval_status'];

                        if ($approval_right_status != 'approved') {
                            continue;
                        }
                    }

                    $expiryDate = '';
                    $country_id = $value['Location_Country'];

                    if (!empty($country_id)) { // get country name
                        switch ($country_id) {
                            case 227:
                                $country_name = 'United States';
                                break;
                            case 38:
                                $country_name = 'Canada';
                                break;
                            default:
                                $country_name = '';
                                break;
                        }
                    }

                    $state_id = $value['Location_State'];

                    if (!empty($state_id) && $state_id != 'undefined') {
                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                        $state = $state_name[0]['state_name'];
                    }

                    $JobCategorys = $value['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                            $job_category_array[] = $job_cat_name[0]['value'];
                        }

                        $job_category = implode(', ', $job_category_array);
                    }

                    $company_sid = $value['user_sid']; //Making job title start
                    $job_title_location = $value['job_title_location'];
                    $sub_domain = $value['sub_domain'];
                    $companyName = $value['CompanyName'];
                    $companyLogo = $value['Logo'];
                    $companyContactName = $value['ContactName'];
                    $companyYoutube = $value['YouTubeVideo'];
                    $companyUserName = strtolower(str_replace(" ", "", $companyName));
                    $uid = $value['sid'];
                    $publish_date = $value['activation_date'];
                    $feed_data = $this->job_details->fetch_uid_from_job_sid($uid);
                    $jobDescription = str_replace('"', "'", strip_tags($value['JobDescription'], '<br>'));

                    if (isset($value['JobRequirements']) && $value['JobRequirements'] != NULL) {
                        $jobDescription .= '<br><br>Job Requirements:<br>' . strip_tags($value['JobRequirements'], '<br>');
                    }

                    if (!empty($feed_data)) {
                        $uid = $feed_data['uid'];
                        $publish_date = $feed_data['publish_date'];
                    }

                    if ($job_title_location == 1) {
                        $title = $list[$key]['Title'];
                    } else {
                        $title = $list[$key]['Title'];
                    }

                    $companyYoutube = '';
                    $YouTube_Video = '';

                    if (!empty($value['YouTubeVideo'])) {
                        $companyYoutube = "https://www.youtube.com/watch?v=" . $value['YouTubeVideo'];
                    }

                    if (!empty($value['YouTube_Video'])) {
                        $YouTube_Video = "https://www.youtube.com/watch?v=" . $value['YouTube_Video'];
                    }

                    $company_subdomain_url = STORE_FULL_URL_SSL;
                    $portal_job_url = $company_subdomain_url . 'display-job/' . $uid;

                    if (isset($value['SalaryType']) && $value['SalaryType'] != NULL) {
                        if ($value['SalaryType'] == 'per_hour') {
                            $jobType = 'Per Hour';
                        } elseif ($value['SalaryType'] == 'per_week') {
                            $jobType = 'Per Week';
                        } elseif ($value['SalaryType'] == 'per_month') {
                            $jobType = 'Per Month';
                        } elseif ($value['SalaryType'] == 'per_year') {
                            $jobType = 'Per Year';
                        } else {
                            $jobType = '';
                        }
                    } else {
                        $jobType = '';
                    }

                    //
                    $value['Salary'] = remakeSalary($value['Salary'], $value['SalaryType']);

                    echo "<job>
                        <title><![CDATA[" . $title . "]]></title>
                        <date><![CDATA[" . $this->date_with_time($publish_date) . " PST]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <url><![CDATA[" . $portal_job_url . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <city><![CDATA[" . $value['Location_City'] . "]]></city>
                        <state><![CDATA[" . $state . "]]></state>
                        <country><![CDATA[" . $country_name . "]]></country>
                        <postalcode><![CDATA[" . $value['Location_ZipCode'] . "]]></postalcode>
                        <salary><![CDATA[" . $value['Salary'] . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <description><![CDATA[" . $jobDescription . "]]></description>
                        </job>";
                }
            }

            echo '</source>
            </xml>';
            exit;
        }
    }

    public function listing_feeds_sjb()
    {
        if (isset($_REQUEST['feedId']) && $_REQUEST['feedId'] == 6) {
            $list = $this->job_details->get_all_company_jobs_ams(array(), NULL, NULL, NULL, NULL, NULL, $career_site_company_sid = array());
            $sid = $this->isActiveFeed();
            $this->addLastRead(1);
            // Filter jobs
            $activeCompanies = $this->job_details->get_all_active_companies($sid);
            header('Content-type: text/xml');
            header('Pragma: public');
            header('Cache-control: private');
            header('Expires: -1');
            echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

            echo "<source>
            <publisher>AutomotoSocial</publisher>
            <publisherurl><![CDATA[https://www.automotosocial.com/]]></publisherurl>
            <lastBuildDate>" . date('D, d M Y h:i:s') . "</lastBuildDate>";

            if (!empty($list)) {
                foreach ($list as $key => $value) {
                    if (!in_array($value['user_sid'], $activeCompanies))
                        continue;
                    $has_job_approval_rights = $value['has_job_approval_rights'];

                    if ($has_job_approval_rights == 1) {
                        $approval_right_status = $value['approval_status'];

                        if ($approval_right_status != 'approved') {
                            continue;
                        }
                    }

                    $expiryDate = '';
                    $country_id = $value['Location_Country'];
                    $country_name = '';
                    $country_full_name = '';

                    if (!empty($country_id)) { // get country name
                        switch ($country_id) {
                            case 227:
                                $country_name = 'US';
                                $country_full_name = 'United States';
                                break;
                            case 38:
                                $country_name = 'CA';
                                $country_full_name = 'Canada';
                                break;
                            default:
                                $country_name = '';
                                $country_full_name = '';
                                break;
                        }
                    }

                    $state_id = $value['Location_State'];

                    if (!empty($state_id) && $state_id != 'undefined') {
                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                        $state = $state_name[0]['state_name'];
                    }

                    $JobCategorys = $value['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                            $job_category_array[] = $job_cat_name[0]['value'];
                        }

                        $job_category = implode(', ', $job_category_array);
                    }

                    $company_sid = $value['user_sid']; //Making job title start
                    $job_title_location = 1; //$value['job_title_location']; Forcefully enabled
                    $sub_domain = $value['sub_domain'];
                    $companyName = $value['CompanyName'];
                    $companyLogo = $value['Logo'];
                    $companyContactName = $value['ContactName'];
                    $companyYoutube = $value['YouTubeVideo'];
                    $companyUserName = strtolower(str_replace(" ", "", $companyName));
                    $uid = $value['sid'];
                    $publish_date = $value['activation_date'];
                    // $feed_data                                                  = $this->job_details->fetch_uid_from_job_sid($uid);
                    $jobDescription = str_replace('"', "'", strip_tags($value['JobDescription'], '<br>'));

                    if (isset($value['JobRequirements']) && $value['JobRequirements'] != NULL) {
                        $jobDescription .= '<br><br>Job Requirements:<br>' . strip_tags($value['JobRequirements'], '<br>');
                    }

                    if ($job_title_location == 1) {
                        $title = $list[$key]['Title'];
                    } else {
                        $title = $list[$key]['Title'];
                    }

                    $companyYoutube = '';
                    $YouTube_Video = '';

                    if (!empty($value['YouTubeVideo'])) {
                        $companyYoutube = "https://www.youtube.com/watch?v=" . $value['YouTubeVideo'];
                    }

                    if (!empty($value['YouTube_Video'])) {
                        $YouTube_Video = "https://www.youtube.com/watch?v=" . $value['YouTube_Video'];
                    }

                    $company_subdomain_url = STORE_FULL_URL_SSL;
                    $portal_job_url = $company_subdomain_url . 'display-job/' . $uid . '/';

                    if (isset($value['SalaryType']) && $value['SalaryType'] != NULL) {
                        if ($value['SalaryType'] == 'per_hour') {
                            $jobType = 'Per Hour';
                        } elseif ($value['SalaryType'] == 'per_week') {
                            $jobType = 'Per Week';
                        } elseif ($value['SalaryType'] == 'per_month') {
                            $jobType = 'Per Month';
                        } elseif ($value['SalaryType'] == 'per_year') {
                            $jobType = 'Per Year';
                        } else {
                            $jobType = '';
                        }
                    } else {
                        $jobType = '';
                    }

                    //
                    $value['Salary'] = remakeSalary($value['Salary'], $value['SalaryType']);

                    echo "<job>
                        <title><![CDATA[" . $title . "]]></title>
                        <date><![CDATA[" . $publish_date . "]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <url><![CDATA[" . $portal_job_url . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <city><![CDATA[" . $value['Location_City'] . "]]></city>
                        <state><![CDATA[" . $state . "]]></state>
                        <country><![CDATA[" . $country_full_name . "]]></country>
                        <postalcode><![CDATA[" . $value['Location_ZipCode'] . "]]></postalcode>
                        <description><![CDATA[" . $jobDescription . "]]></description>
                        <salary><![CDATA[" . $value['Salary'] . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        </job>";
                }
            }

            echo '</source>';
            exit;
        }
    }

    /**
     * 
     */
    private function addLastRead($sid)
    {
        $this->db
            ->where('sid', $sid)
            ->set([
                'last_read' => date('Y-m-d H:i:s', strtotime('now')),
                'referral' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''
            ])->update('job_feeds_management');
        //
        $this->db
            ->insert('job_feeds_management_history', [
                'feed_id' => $sid,
                'referral' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                'created_at' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
    }

    function date_with_time($date)
    {
        $with_time = date('M d Y, D H:i:s', strtotime($date));

        if (strpos($with_time, '00:00:00')) {
            $with_time = date('M d Y, D', strtotime($date));
        }
        return $with_time;
    }

    public function terms_of_use()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $theme_name = !empty($data['theme_name']) ? $data['theme_name'] : "theme-4";
        //
        if (empty($data['theme_name'])) {
            $data['theme_name'] = "theme-4";
        }
        //
        $company_sid = $data['company_details']['sid'];
        $data['title'] = 'Terms Of Use';
        $data['dealership_website'] = '';
        $data['pageName'] = 'terms_of_use';
        $data['isPaid'] = $data['is_paid'];
        $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_sid); //Pages Information
        $data['pages'] = $pages;
        $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'about-us'); //About Us Information
        $data['about_us'] = $about_us_text;
        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'footer_content');
        $data['footer_content'] = $footer_content;
        $website = $data['company_details']['WebSite'];
        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
        $data['jobs_page_title'] = $jobs_page_title;

        if (!empty($website)) {
            $data['dealership_website'] = $website;
        }

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $data['heading_title'] = 'Automoto Social Jobs: Terms Of Use / Privacy Poilicy';
            $data['page_inner_title'] = 'Terms Of Use';
            $data['company_details'] = $this->job_details->get_company_details($company_sid);
            $data['meta_title'] = $data['meta_title'];
            $data['meta_description'] = $data['meta_description'];
            $data['meta_keywords'] = $data['meta_keywords'];
            $data['embedded_code'] = $data['embedded_code'];
            //
            $this->load->view($theme_name . '/_parts/header_view', $data);
            $this->load->view($theme_name . '/terms_of_use');
            $this->load->view($theme_name . '/_parts/footer_view');
        } else { // end of portal status check
            redirect('/', 'refresh');
        }
    }

    public function sitemap()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $theme_name = !empty($data['theme_name']) ? $data['theme_name'] : "theme-4";
        //
        if (empty($data['theme_name'])) {
            $data['theme_name'] = "theme-4";
        }
        //
        $company_sid = $data['company_details']['sid'];
        $data['title'] = 'Site Map';
        $data['dealership_website'] = '';
        $data['pageName'] = 'terms_of_use';
        $data['isPaid'] = $data['is_paid'];
        $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_sid); //Pages Information
        $data['pages'] = $pages;
        $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'about-us'); //About Us Information
        $data['about_us'] = $about_us_text;
        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'home', 'footer_content');
        $data['footer_content'] = $footer_content;
        $website = $data['company_details']['WebSite'];
        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
        $data['jobs_page_title'] = $jobs_page_title;

        if (!empty($website)) {
            $data['dealership_website'] = $website;
        }
        // Get all active companies
        $data['companyJobs'] = $this->job_details->GetAllActiveCompaniesWithActiveJobs();
        //
        unset($activeCompanies, $companyJobs);

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $data['heading_title'] = 'Automoto Social Jobs: Site Map';
            $data['page_inner_title'] = 'Site Map';
            $data['company_details'] = $this->job_details->get_company_details($company_sid);
            $data['meta_title'] = 'Automoto Social Jobs: Site Map';
            $data['meta_description'] = 'Automoto Social helps Automotive Employers and Jobseekers to connect';
            $data['meta_keywords'] = 'automotive jobs,job search,auto sales,car jobs,job search,auto';
            $data['embedded_code'] = $data['embedded_code'];
            $this->load->view($theme_name . '/_parts/header_view', $data);
            $this->load->view($theme_name . '/sitemap');
            $this->load->view($theme_name . '/_parts/footer_view');
        } else { // end of portal status check
            redirect('/', 'refresh');
        }
    }

    function ams_google_feed()
    {
        $all_paid_jobs = $this->job_details->get_all_paid_jobs();
        $paid_jobs = array();
        $featured_jobs = array();
        $google_xml = '';

        if (!empty($all_paid_jobs)) {
            foreach ($all_paid_jobs as $apj) {
                $paid_jobs[] = $apj['jobId'];
                $google_xml .= '
                                <url>
                                    <loc>https://www.automotosocial.com/display-job/' . $apj['jobId'] . '</loc>
                                    <changefreq>monthly</changefreq>
                                    <priority>0.80</priority>
                                </url>';
            }
        }

        $list = $this->job_details->get_all_company_jobs_sids($paid_jobs);

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $has_job_approval_rights = $value['has_job_approval_rights'];

                if ($has_job_approval_rights == 1) {
                    $approval_right_status = $value['approval_status'];

                    if ($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $google_xml .= '
                                <url>
                                    <loc>https://www.automotosocial.com/display-job/' . $value['sid'] . '</loc>
                                    <changefreq>monthly</changefreq>
                                    <priority>0.80</priority>
                                </url>';
            }
        }
        echo $google_xml;
        exit;
    }

    function organic_feed_test()
    {
        $paid_jobs = array();
        $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid);

        echo $this->db->last_query() . '<br><pre>';
        print_r($list);
        exit;
        foreach ($list as $key => $value) {
            $company_sid = $value['user_sid'];
            $has_job_approval_rights = $value['has_job_approval_rights'];

            if ($company_sid == 2948) {
                echo '<pre>';
                print_r($value);
                echo '</pre>';
            }
        }
    }


    /**
     * Check if user already applied for the job
     * from the same IP address
     * Created on: 05-08-2019
     *
     * @param $company_sid
     *
     * @return VOID
     */
    function checkUserAppliedForJob($company_sid, $isIframe = false)
    {
        $ip = getUserIP();
        $job_sid = $this->input->post('job_sid', true);
        $email_address = $this->input->post('email', true);
        $applied_from = $this->input->post('applied_from', true); // jobs_list_view
        //
        $this->load->library('user_agent');
        $this->load->helper('url');
        //
        $sid = $this->job_details->checkUserAppliedForJobByIP($ip, $job_sid, $company_sid);
        if ((int) $sid === 0) {
            // Add row
            $this->job_details->addJobRestrictionRow(array(
                'ip_address' => $ip,
                'job_sid' => $job_sid,
                'email_address' => $email_address,
                'page_uri' => current_url(),
                'user_agent' => $this->agent->agent_string(),
                'company_sid' => $company_sid,
                'expire_at' => date('Y-m-d H:i:s', strtotime('+2 hours'))
            ));
        } else {
            $this->job_details->updateJobRestrictionStatus($sid);
            $this->session->set_flashdata('message', '<b>Error: </b>You have already applied for this job.');
            if ($applied_from == 'job')
                redirect('/' . ($isIframe ? 'job-feed-details' : 'job_details') . '/' . $job_sid, 'refresh');
            else if ($applied_from == 'jobs_list_view')
                redirect('/' . ($isIframe ? 'job-feed' : 'jobs') . '/');
            else
                redirect('/', 'refresh');
        }
    }


    // Temporary
    function cron($vf = NULL)
    {
        if ($vf != 'ljfgdkuhgksadgfowetroyu2t352g5jhwegrwjkfgewkjgrk23gfhsdgfgdkjgfkjfg2373t4kwgfkhdsgfhd')
            return;

        $this->authorise();
        if (!$this->access_token)
            exit(0);

        $career_site_only_companies = $this->job_details->get_career_site_only_companies(); // get the career site status for companies
        $career_site_company_sid = array();

        if (!empty($career_site_only_companies)) {
            foreach ($career_site_only_companies as $csoc) {
                $career_site_company_sid[] = $csoc['sid'];
            }
        }

        $all_paid_jobs = $this->job_details->get_all_paid_jobs($career_site_company_sid);
        $paid_jobs = array();
        $featured_jobs = array();
        $featured_jobs_count = 0;
        $list_count = 0;

        if (!empty($all_paid_jobs)) {
            foreach ($all_paid_jobs as $apj) {
                $paid_jobs[] = $apj['jobId'];
            }
        }

        if (!empty($segment6)) { // if search is applied
            $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, 0, 0);
            $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, 0, 0, true);

            if (!empty($paid_jobs)) {
                $featured_jobs = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, 0, 0);
                $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, 0, 0, true);
            }
        } else {
            $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, 0, 0);
            $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, 0, 0, true);

            if (!empty($paid_jobs)) {
                $featured_jobs = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, 0, 0);
                $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, 0, 0, true);
            }
        }

        $all_active_jobs = $this->job_details->filters_of_active_jobs($career_site_company_sid);
        $states_array = array();
        if (!empty($all_active_jobs)) { // we need it for search filters as we only need to show filters as per active jobs only
            for ($i = 0; $i < count($all_active_jobs); $i++) {
                $country_id = $all_active_jobs[$i]['Location_Country'];

                if ($country_id == 38) {
                    $countries_array[38] = array('sid' => 38, 'country_code' => 'CA', 'country_name' => 'Canada');
                }

                if ($country_id == 227) {
                    $countries_array[227] = array('sid' => 227, 'country_code' => 'US', 'country_name' => 'United States');
                }

                $state_id = $all_active_jobs[$i]['Location_State'];

                if (!empty($state_id) && $state_id != 'undefined') {
                    if (!array_key_exists($state_id, $states_array)) {
                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                        $states_array[$state_id] = $state_name[0]['state_name'];
                        $counntry_states_array[$country_id][] = array('sid' => $state_id, 'state_name' => $state_name[0]['state_name']);
                    }
                }

                $JobCategorys = $all_active_jobs[$i]['JobCategory'];

                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);

                    foreach ($cat_id as $id) {
                        $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                        $categories_in_active_jobs[$id] = $job_cat_name[0]['value'];
                    }
                }
            }
        }

        // Get pre-existed jobs
        $ids = $this->job_details->getLastProcessedIds(1);

        //
        $jobs = [];
        $cur_job = 0;
        $max_job = 200;

        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $has_job_approval_rights = $value['has_job_approval_rights'];

                if ($has_job_approval_rights == 1) {
                    $approval_right_status = $value['approval_status'];

                    if ($approval_right_status != 'approved') {
                        unset($list[$key]);
                        continue;
                    }
                }

                $country_id = $value['Location_Country'];

                if (!empty($country_id)) { // get country name
                    switch ($country_id) {
                        case 227:
                            $country_name = 'United States';
                            break;
                        case 38:
                            $country_name = 'Canada';
                            break;
                        default:
                            $country_name = '';
                            break;
                    }

                    $list[$key]['Location_Country'] = $country_name;
                }

                $state_id = $value['Location_State'];

                if (!empty($state_id) && $state_id != 'undefined') {
                    $list[$key]['Location_State'] = $states_array[$state_id];
                }

                $JobCategorys = $value['JobCategory'];

                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    $job_category_array = array();

                    foreach ($cat_id as $id) {
                        $job_category_array[] = $categories_in_active_jobs[$id];
                    }

                    $job_category = implode(', ', $job_category_array);
                    $list[$key]['JobCategory'] = $job_category;
                }

                $company_sid = $value['user_sid']; //Making job title start
                $job_title_location = $value['job_title_location'];
                $sub_domain = $value['sub_domain'];
                $list[$key]['TitleOnly'] = $list[$key]['Title'];
                if ($job_title_location == 1) {
                    $list[$key]['Title'] = $list[$key]['Title'] . '  - ' . ucfirst($list[$key]['Location_City']) . ', ' . $list[$key]['Location_State'] . ', ' . $list[$key]['Location_Country'];
                }

                if (in_array($value['sid'], $ids))
                    continue;
                if ($cur_job >= $max_job)
                    break;
                //
                $cur_job++;

                $jobs[] = ['sid' => $value['sid'], 'title' => $list[$key]['Title'], 'uri' => job_title_uri($list[$key])];
            }
        }

        // TODO feature jobs
        mail(TO_EMAIL_DEV, 'Google Job API - Automotosocial- Triggered: ' . date('Y-m-d H:i:s'), print_r($jobs, true));
        // _e($jobs, true, true);
        //
        $this->google_hire_api($jobs, 'add');
    }

    //create sitemap for goole
    function create_sitemap($vf)
    {
        if ($vf != 'ljfgdkuhgksadgfowetroyu2t352g5jhwegrwjkfgewkjgrk23gfhsdgfgdkjgfkjfg2373t4kwgfkhdsgfhd')
            return;

        $newXml = '';
        $list = $this->job_details->get_all_company_jobs_ams_cron_2();
        //
        foreach ($list as $key => $value) {
            $list[$key]['TitleOnly'] = $list[$key]['Title'];
            $has_job_approval_rights = $value['has_job_approval_rights'];

            if ($has_job_approval_rights == 1) {
                $approval_right_status = $value['approval_status'];

                if ($approval_right_status != 'approved') {
                    unset($list[$key]);
                    continue;
                }
            }

            $country_id = $value['Location_Country'];

            if (!empty($country_id)) { // get country name
                switch ($country_id) {
                    case 227:
                        $country_name = 'United States';
                        break;
                    case 38:
                        $country_name = 'Canada';
                        break;
                    default:
                        $country_name = '';
                        break;
                }

                $list[$key]['Location_Country'] = $country_name;
            }

            $state_id = $value['Location_State'];

            if (!empty($state_id) && $state_id != 'undefined') {
                $list[$key]['Location_State'] = $this->job_details->get_statename_by_id($state_id)[0]['state_name']; // get state name
                ;
            }

            $JobCategorys = $value['JobCategory'];

            if ($JobCategorys != null) {
                $cat_id = explode(',', $JobCategorys);
                $job_category_array = array();

                foreach ($cat_id as $id) {
                    $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                    $job_category_array[] = $job_cat_name[0]['value'];
                }
                $job_category = implode(', ', $job_category_array);
                $list[$key]['JobCategory'] = $job_category;
            }
            //
            $job_url = 'https://www.automotosocial.com' . job_title_uri($list[$key]);
            $newXml .= "<url>";
            $newXml .= '    <loc>' . $job_url . "</loc>\n";
            $newXml .= "</url>";
        }
        //
        $xmlString = '<?xml version="1.0" encoding="utf-8"?>';
        $xmlString .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        // Companies jobs
        $activeCompaniesWithJobs = $this->job_details->GetAllActiveCompaniesWithActiveJobs();
        //
        if (!empty($activeCompaniesWithJobs)) {
            foreach ($activeCompaniesWithJobs as $comp) {
                $xmlString .= "<url>";
                $xmlString .= '     <loc>https://www.automotosocial.com/' . ($comp['slug']) . "</loc>";
                $xmlString .= "</url>";
            }
        }
        //
        if (!empty($newXml)) {
            $xmlString .= $newXml;
        }
        //
        $xmlString .= '</urlset>';
        echo " Sitemap Updated";
        file_put_contents('sitemap.xml', $xmlString);
        $submit_to_google = getFileData("https://www.google.com/ping?sitemap=https://www.automotosocial.com/sitemap.xml");
        echo $submit_to_google;
        mail(TO_EMAIL_DEV, 'Google Hire Cron Job at ' . date('Y-m-d H:i:s') . '', print_r($list, true));
    }

    private $is_test = 1;
    /**
     * Set hashing method
     */
    private $hash_type = 'RS256';
    /**
     * Set auth url
     */
    private $auth_url = 'https://www.googleapis.com/oauth2/v4/token';
    /**
     * Holds access token
     */
    private $access_token = '';


    /**
     * Get access token
     *
     * @return Resource
     */
    private function authorise()
    {
        // Set credenstials file name
        $cred_filename = 'google_hire_automotosocial.json';

        // Loads configutaion file
        $auth = @json_decode(@file_get_contents(APPPATH . '../../../protected_files/' . $cred_filename));

        // Generate JWT token
        $this->load->library('Mjwt');
        // $jwt = new MJWT();
        $mjwt = $this->mjwt;
        //
        $assertion = $mjwt::encode(
            array(
                "iss" => $auth->client_email,
                "scope" => "https://www.googleapis.com/auth/indexing",
                "aud" => $this->auth_url,
                "exp" => strtotime("+30 minutes"),
                "iat" => strtotime("now")
            ),
            $auth->private_key,
            $this->hash_type,
            $auth->private_key_id
        );
        //
        $grant_type = urlencode('urn:ietf:params:oauth:grant-type:jwt-bearer');
        $post = "grant_type=$grant_type&assertion=$assertion";
        //
        $curl_auth = curl_init($this->auth_url);
        curl_setopt_array(
            $curl_auth,
            array(
                CURLOPT_POST => strlen($post),
                CURLOPT_POSTFIELDS => urldecode(utf8_encode($post)),
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                )
            )
        );

        $result = curl_exec($curl_auth);
        curl_close($curl_auth);

        $result = @json_decode($result, true);

        // _e($result, true);
        if (!isset($result['access_token']))
            exit(0);
        $this->access_token = $result['access_token'];

        // $curl2 = curl_init("https://www.googleapis.com/oauth2/v1/tokeninfo?access_token={$this->access_token}");
        // _e(curl_exec($curl2), true, true);
        // curl_close($curl2);

        return $this;
    }


    /**
     * hit google index api
     *
     * @param $jobs Array
     * @param $type String Optional
     * @param $is_test Bool Optional
     *
     * @return Array
     */
    private function google_hire_api($jobs, $type = 'add')
    {
        if (!sizeof($jobs))
            return false;
        $max = 1;
        foreach ($jobs as $k0 => $v0) {
            // if($k0 > $max) break;
            // if($v0['sub_domain'] == '') continue;
            // TODO
            // dns record verification
            // confirm subdomain links
            $response = $this->make_request(
                "https://indexing.googleapis.com/v3/urlNotifications:" . ($type == 'add' ? 'publish' : 'unpublish') . "",
                array("url" => 'https://www.automotosocial.com' . $v0['uri'], "type" => ($type == 'add' ? 'URL_UPDATED' : 'URL_DELETED'))
            );
            $this->job_details->addProccessedId($v0['sid']);
            sleep(2);
            // _e($response, true);
        }
    }


    /**
     * Make a curl request
     *
     * @param $url String
     * @param $post Array
     *
     * @return Array
     */
    private function make_request($url, $post)
    {
        $post = @json_encode($post);
        //
        $curl = curl_init();

        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => $url,
                CURLOPT_POST => 1,
                // CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                // CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POSTFIELDS => $post,
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                // CURLOPT_HEADER => 1,
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/json",
                    "content-length: " . strlen($post) . "",
                    "Authorization: Bearer {$this->access_token}",
                    "Host: indexing.googleapis.com"
                )
            )
        );

        $result = curl_exec($curl);

        return $result;

        return @json_decode($result_array, true);
    }


    // Iframe job feed
    public function job_feed()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);

        if (!$this->session->userdata('portal_info')) {
            $this->session->set_userdata('portal_info', $data);
        }


        $company_sid = $data['company_details']['sid'];
        company_phone_regex_module_check($company_sid, $data, $this);

        $data['theme_name'] = 'theme-4';
        $data['is_paid'] = 1;
        $theme_name = $data['theme_name'];
        $countries_array = array();
        $states_array = array();
        $counntry_states_array = array();

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $isPaid = $data['is_paid'];
            $data['meta_title'] = $data['meta_title']; //#region MetaTags Information
            $data['meta_description'] = $data['meta_description'];
            $data['meta_keywords'] = $data['meta_keywords'];
            $data['embedded_code'] = $data['embedded_code'];
            // $data_countries                                                     = db_get_active_countries();
            //
            // foreach ($data_countries as $value) {
            //    $data_states[$value['sid']]                                     = db_get_active_states($value['sid']);
            // }

            // $data_states_encode                                                 = htmlentities(json_encode($data_states));
            // $data['active_countries']                                           = $data_countries;
            // $data['active_states']                                              = $data_states;
            // $data['states']                                                     = $data_states_encode;
            $country = '';
            $state = '';
            $city = '';
            $categoryId = '';
            $keyword = '';
            $pageName = 'JOBS';
            $segment1 = $this->uri->segment(1);

            if (!empty($segment1)) {
                $pageName = urldecode($segment1);
            }

            $segment2 = $this->uri->segment(2);

            if (!empty($segment2)) {
                $country = urldecode($segment2);
            }

            $segment3 = $this->uri->segment(3);

            if (!empty($segment3)) {
                $state = urldecode($segment3);
            }

            $segment4 = $this->uri->segment(4);

            if (!empty($segment4)) {
                $city = urldecode($segment4);
            }

            $segment5 = $this->uri->segment(5);

            if (!empty($segment5)) {
                $categoryId = $segment5;
            }

            $segment6 = $this->uri->segment(6);

            $segment7 = $this->uri->segment(7);
            $ajax_flag = $this->uri->segment(8);
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;

            if (!empty($segment7) && $segment7 > 1) {
                $offset = ($segment7 - 1) * $per_page;
            }

            if (!empty($segment6)) {
                $keyword = urldecode($segment6);
                $data['search_params']['country'] = $country;
                $data['search_params']['state'] = $state;
                $data['search_params']['city'] = $city;
                $data['search_params']['category'] = $categoryId;
                $data['search_params']['keyword'] = $keyword;
            }

            $data['formpost'] = array();
            $data['dealership_website'] = '';
            $company_id = $data['company_details']['sid'];
            $company_name = $data['company_details']['CompanyName'];
            $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id);
            $data['pages'] = $pages;
            $data['isPaid'] = $isPaid;
            $counter = 0;

            foreach ($data['pages'] as $page) {
                $data['pages'][$counter]['page_title'] = str_replace("{{company_name}}", $company_name, $data['pages'][$counter]['page_title']);
                $counter++;
            }

            if ($isPaid) {
                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
                $data['jobs_page_title'] = $jobs_page_title;

                if ($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) {
                    $pageName = 'jobs';
                }

                if ($pageName == '') {
                    $pageName = 'jobs';
                }

                $data['pageName'] = $pageName;


                $career_site_only_companies = $this->job_details->get_career_site_only_companies(); // get the career site status for companies
                $career_site_company_sid = array();

                if (!empty($career_site_only_companies)) {
                    foreach ($career_site_only_companies as $csoc) {
                        $career_site_company_sid[] = $csoc['sid'];
                    }
                }

                $all_paid_jobs = $this->job_details->get_all_paid_jobs($career_site_company_sid);
                $paid_jobs = array();
                $featured_jobs = array();
                $featured_jobs_count = 0;
                $list_count = 0;

                if (!empty($all_paid_jobs)) {
                    foreach ($all_paid_jobs as $apj) {
                        $paid_jobs[] = $apj['jobId'];
                    }
                }

                if (!empty($segment6)) { // if search is applied
                    $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, $per_page, $offset);
                    $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, $per_page, $offset, true);

                    if (!empty($paid_jobs)) {
                        $featured_jobs = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, $per_page, $offset);
                        $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, $country, $state, $city, $categoryId, $keyword, $per_page, $offset, true);
                    }
                } else {
                    $list = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset);
                    $list_count = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset, true);

                    if (!empty($paid_jobs)) {
                        $featured_jobs = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, $per_page, $offset);
                        $featured_jobs_count = $this->job_details->paid_job_details($paid_jobs, NULL, NULL, NULL, NULL, NULL, $per_page, $offset, true);
                    }
                }

                // TOBE deleted after testing
                if (getUserIP() == '72.255.38.246' || getUserIP() == '::1' || getUserIP() == '127.0.0.1') {
                    // Fetch
                    $testList = $this->job_details->getTestingCompanyJobs();
                    // _e(sizeof($list), true);
                    $list = array_merge_recursive($list, $testList);
                    // _e(sizeof($list), true, true);
                }

                $all_active_jobs = $this->job_details->filters_of_active_jobs($career_site_company_sid);

                if (!empty($all_active_jobs)) { // we need it for search filters as we only need to show filters as per active jobs only
                    for ($i = 0; $i < count($all_active_jobs); $i++) {
                        $country_id = $all_active_jobs[$i]['Location_Country'];

                        if ($country_id == 38) {
                            $countries_array[38] = array('sid' => 38, 'country_code' => 'CA', 'country_name' => 'Canada');
                        }

                        if ($country_id == 227) {
                            $countries_array[227] = array('sid' => 227, 'country_code' => 'US', 'country_name' => 'United States');
                        }

                        $state_id = $all_active_jobs[$i]['Location_State'];

                        if (!empty($state_id) && $state_id != 'undefined') {
                            if (!array_key_exists($state_id, $states_array)) {
                                $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                                $states_array[$state_id] = $state_name[0]['state_name'];
                                $counntry_states_array[$country_id][] = array('sid' => $state_id, 'state_name' => $state_name[0]['state_name']);
                            }
                        }

                        $JobCategorys = $all_active_jobs[$i]['JobCategory'];

                        if ($JobCategorys != null) {
                            $cat_id = explode(',', $JobCategorys);

                            foreach ($cat_id as $id) {
                                $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                                $categories_in_active_jobs[$id] = $job_cat_name[0]['value'];
                            }
                        }
                    }
                }

                if (!empty($list)) {
                    foreach ($list as $key => $value) {
                        $list[$key]['TitleOnly'] = $list[$key]['Title'];
                        $has_job_approval_rights = $value['has_job_approval_rights'];

                        if ($has_job_approval_rights == 1) {
                            $approval_right_status = $value['approval_status'];

                            if ($approval_right_status != 'approved') {
                                unset($list[$key]);
                                continue;
                            }
                        }

                        $country_id = $value['Location_Country'];

                        if (!empty($country_id)) { // get country name
                            switch ($country_id) {
                                case 227:
                                    $country_name = 'United States';
                                    break;
                                case 38:
                                    $country_name = 'Canada';
                                    break;
                                default:
                                    $country_name = '';
                                    break;
                            }

                            $list[$key]['Location_Country'] = $country_name;
                        }

                        $state_id = $value['Location_State'];

                        if (!empty($state_id) && $state_id != 'undefined') {
                            $list[$key]['Location_State'] = $states_array[$state_id];
                        }

                        $JobCategorys = $value['JobCategory'];

                        if ($JobCategorys != null) {
                            $cat_id = explode(',', $JobCategorys);
                            $job_category_array = array();

                            foreach ($cat_id as $id) {
                                $job_category_array[] = $categories_in_active_jobs[$id];
                            }

                            $job_category = implode(', ', $job_category_array);
                            $list[$key]['JobCategory'] = $job_category;
                        }

                        $questionnaire_sid = $value['questionnaire_sid'];

                        if ($questionnaire_sid > 0) {
                            $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);

                            if (!empty($portal_screening_questionnaires)) {
                                $list[$key]['q_name'] = $portal_screening_questionnaires[0]['name'];
                                $list[$key]['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                                $list[$key]['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                                $list[$key]['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                                $list[$key]['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                                $list[$key]['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                                $list[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                            } else {
                                $list[$key]['q_name'] = 'Not Found';
                                $list[$key]['q_passing'] = 0;
                                $list[$key]['q_send_pass'] = '';
                                $list[$key]['q_send_fail'] = '';
                                $list[$key]['q_pass_text'] = '';
                                $list[$key]['q_fail_text'] = '';
                                $list[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                            }

                            $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                            if ($screening_questions_numrows > 0) {
                                $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                foreach ($screening_questions as $qkey => $qvalue) {
                                    $questions_sid = $qvalue['sid'];
                                    $list[$key]['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                    $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                    if ($screening_answers_numrows) {
                                        $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                        foreach ($screening_answers as $akey => $avalue) {
                                            $list[$key]['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                        }
                                    }
                                }
                            }
                        }

                        $company_sid = $value['user_sid']; //Making job title start
                        $job_title_location = $value['job_title_location'];
                        $sub_domain = $value['sub_domain'];

                        if ($job_title_location == 1) {
                            $list[$key]['Title'] = $list[$key]['Title'] . '  - ' . ucfirst($list[$key]['Location_City']) . ', ' . $list[$key]['Location_State'] . ', ' . $list[$key]['Location_Country'];
                        }

                        $company_subdomain_url = STORE_PROTOCOL_SSL . $sub_domain;
                        $portal_job_url = $company_subdomain_url . '/job_details/' . $list[$key]['sid'];
                        $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                        $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                        $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list[$key]['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                        // $btn_google                                     = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                        $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($list[$key]['Title']) . '&amp;summary=' . urlencode($list[$key]['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-4.png"></a>';
                        $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list[$key]['Title']) . '</a>';
                        $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                        $links = '';
                        $links .= '<ul>';
                        // $links                                       .= '<li>' . $btn_google . '</li>';
                        $links .= '<li>' . $btn_facebook . '</li>';
                        $links .= '<li>' . $btn_linkedin . '</li>';
                        $links .= '<li>' . $btn_twitter . '</li>';

                        if ($theme_name == 'theme-4') {
                            $links .= '<li>' . $btn_tell_a_friend . '</li>';
                        }

                        $links .= '</ul>';
                        $list[$key]['share_links'] = $links; //Generate Share Links - end
                    }
                }

                if (!empty($featured_jobs)) {
                    foreach ($featured_jobs as $key => $value) {
                        $featured_jobs[$key]['TitleOnly'] = $featured_jobs[$key]['Title'];
                        $has_job_approval_rights = $value['has_job_approval_rights'];

                        if ($has_job_approval_rights == 1) {
                            $approval_right_status = $value['approval_status'];

                            if ($approval_right_status != 'approved') {
                                continue;
                            }
                        }

                        $country_id = $value['Location_Country'];

                        if (!empty($country_id)) { // get country name
                            switch ($country_id) {
                                case 227:
                                    $country_name = 'United States';
                                    break;
                                case 38:
                                    $country_name = 'Canada';
                                    break;
                                default:
                                    $country_name = '';
                                    break;
                            }

                            $featured_jobs[$key]['Location_Country'] = $country_name;
                        }

                        $state_id = $value['Location_State'];

                        if (!empty($state_id) && $state_id != 'undefined') {
                            $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                            $featured_jobs[$key]['Location_State'] = $state_name[0]['state_name'];
                        }

                        $JobCategorys = $value['JobCategory'];

                        if ($JobCategorys != null) {
                            $cat_id = explode(',', $JobCategorys);
                            $job_category_array = array();

                            foreach ($cat_id as $id) {
                                $job_cat_name = $this->job_details->get_job_category_name_by_id($id);
                                if (!empty($job_cat_name)) {
                                    $job_category_array[] = $job_cat_name[0]['value'];
                                }
                            }

                            $job_category = implode(', ', $job_category_array);
                            $featured_jobs[$key]['JobCategory'] = $job_category;
                        }

                        $questionnaire_sid = $value['questionnaire_sid'];

                        if ($questionnaire_sid > 0) {
                            $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);

                            if (!empty($portal_screening_questionnaires)) {
                                $featured_jobs[$key]['q_name'] = $portal_screening_questionnaires[0]['name'];
                                $featured_jobs[$key]['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                                $featured_jobs[$key]['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                                $featured_jobs[$key]['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                                $featured_jobs[$key]['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                                $featured_jobs[$key]['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                                $featured_jobs[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                            } else {
                                $featured_jobs[$key]['q_name'] = 'Not Found';
                                $featured_jobs[$key]['q_passing'] = 0;
                                $featured_jobs[$key]['q_send_pass'] = '';
                                $featured_jobs[$key]['q_send_fail'] = '';
                                $featured_jobs[$key]['q_pass_text'] = '';
                                $featured_jobs[$key]['q_fail_text'] = '';
                                $featured_jobs[$key]['my_id'] = 'q_question_' . $questionnaire_sid;
                            }

                            $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                            if ($screening_questions_numrows > 0) {
                                $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                foreach ($screening_questions as $qkey => $qvalue) {
                                    $questions_sid = $qvalue['sid'];
                                    $featured_jobs[$key]['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                    $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                    if ($screening_answers_numrows) {
                                        $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                        foreach ($screening_answers as $akey => $avalue) {
                                            $featured_jobs[$key]['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                        }
                                    }
                                }
                            }
                        }

                        $company_sid = $value['user_sid']; //Making job title start
                        $job_title_location = $value['job_title_location'];
                        $sub_domain = $value['sub_domain'];

                        if ($job_title_location == 1) {
                            $featured_jobs[$key]['Title'] = $featured_jobs[$key]['Title'] . '  - ' . ucfirst($featured_jobs[$key]['Location_City']) . ', ' . $featured_jobs[$key]['Location_State'] . ', ' . $featured_jobs[$key]['Location_Country'];
                        }

                        //Generate Share Links - start
                        $company_subdomain_url = STORE_PROTOCOL_SSL . $sub_domain;
                        $portal_job_url = $company_subdomain_url . '/job_details/' . $featured_jobs[$key]['sid'];
                        $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                        $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                        $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($featured_jobs[$key]['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                        // $btn_google = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                        $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($featured_jobs[$key]['Title']) . '&amp;summary=' . urlencode($featured_jobs[$key]['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-4.png"></a>';
                        $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($featured_jobs[$key]['Title']) . '</a>';
                        $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                        $links = '';
                        $links .= '<ul>';
                        // $links                                       .= '<li>' . $btn_google . '</li>';
                        $links .= '<li>' . $btn_facebook . '</li>';
                        $links .= '<li>' . $btn_linkedin . '</li>';
                        $links .= '<li>' . $btn_twitter . '</li>';

                        if ($theme_name == 'theme-4') {
                            $links .= '<li>' . $btn_tell_a_friend . '</li>';
                        }

                        $links .= '</ul>';
                        $featured_jobs[$key]['share_links'] = $links; //Generate Share Links - end
                    }
                }

                $data['featured_jobs'] = $featured_jobs;
                $data['job_listings'] = $list;
                $data['featured_jobs_count'] = $featured_jobs_count;
                $data['job_listings_count'] = $list_count;
                $data['total_calls'] = ceil($list_count + $featured_jobs_count) / $per_page;
                if (!empty($ajax_flag) && $ajax_flag) {
                    // print_r(json_encode($data['featured_jobs']));
                    print_r(json_encode($data['job_listings']));
                    die();
                }
                // $jobCategories                                          = db_get_job_category($company_id);
                // $data['job_categories']                                 = $jobCategories;
                function array_sort_state_name($a, $b)
                {
                    return strnatcmp($a['state_name'], $b['state_name']);
                }

                if (isset($counntry_states_array['227'])) {
                    $us_states = $counntry_states_array['227'];
                    usort($us_states, 'array_sort_state_name'); // sort alphabetically by name
                    $counntry_states_array['227'] = $us_states;
                }

                if (isset($counntry_states_array['38'])) {
                    $ca_states = $counntry_states_array['38'];
                    usort($ca_states, 'array_sort_state_name'); // sort alphabetically by name
                    $counntry_states_array['38'] = $ca_states;
                }

                $data['job_categories'] = array();
                asort($categories_in_active_jobs);
                $data['categories_in_active_jobs'] = $categories_in_active_jobs;
                $data_states_encode = htmlentities(json_encode($counntry_states_array));
                $data['active_countries'] = $countries_array;
                $data['active_states'] = $counntry_states_array;
                $data['states'] = $data_states_encode;
                $jobsPageBannerImage = $this->theme_meta_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs', 'jobs_page_banner');
                $data['jobs_page_banner'] = $jobsPageBannerImage;
                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
                $data['jobs_page_title'] = $jobs_page_title;
                // echo $company_id.'<br>'.$jobs_page_title; exit;
                $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                $data['footer_content'] = $footer_content;

                $this->load->view($theme_name . '/_parts/header_view_iframe', $data);
                $this->load->view($theme_name . '/_parts/jobs_list_view_iframe');
                $this->load->view($theme_name . '/_parts/footer_view_iframe');
            } else {
                $this->load->view($theme_name . '/_parts/header_view_iframe', $data);
                $this->load->view($theme_name . '/index_view_iframe');
                $this->load->view($theme_name . '/_parts/footer_view_iframe');
            }
        } else {
            if (isset($data['maintenance_mode']) && $data['maintenance_mode'] == 0) {
                $this->load->view($theme_name . '/_parts/header_view_iframe', $data);
                $this->load->view($theme_name . '/index_view');
                $this->load->view($theme_name . '/_parts/footer_view_iframe');
            } else {
                $maintenance_mode_page_content = $this->check_domain->get_maintenance_mode_page_content($data['employer_id'], $data['sid']);
                $data['maintenance_mode_page_content'] = $maintenance_mode_page_content;
                $this->load->view('common/maintenance_mode', $data);
            }
        }
    }

    public function job_feed_details($sid = null)
    {

        $sid = $this->input->post('sid') ? $this->input->post('sid') : $sid;
        if (strpos($sid, "-") !== FALSE) {
            $sid = @end((explode('-', $sid)));
        }
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $data['theme_name'] = 'theme-4';
        $data['is_paid'] = 1;
        $company_id = $data['company_details']['sid'];
        $company_name = $data['company_details']['CompanyName'];
        $theme_name = $data['theme_name'];

        $company_sid = $data['company_details']['sid'];
        company_phone_regex_module_check($company_sid, $data, $this);

        if (!is_numeric($sid)) {
            $sid = $this->job_details->fetch_job_id_from_random_key($sid);
        }

        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'Jobs';
        $data['jobs_page_title'] = $jobs_page_title;


        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            if ($sid != null && intval($sid) > 0) {
                $list = $this->job_details->fetch_company_jobs_details($sid);
                $user_sid = $list['user_sid'];
                $jobs_detail_page_title = $this->theme_meta_model->fGetThemeMetaData($user_sid, $theme_name, 'jobs_detail', 'jobs_detail_page_banner');
                $data['jobs_detail_page_banner_data'] = $jobs_detail_page_title;

                if (!empty($list)) {
                    $company_sid = $list['user_sid'];
                    $company_email_templates = $this->check_domain->portal_email_templates($company_sid);
                    $application_acknowledgement_letter = array();
                    $enable_auto_responder_email = 0;

                    if (!empty($company_email_templates)) {
                        foreach ($company_email_templates as $key => $email_templates) {
                            if ($email_templates['template_code'] == 'application_acknowledgement_letter') {
                                $application_acknowledgement_letter = $email_templates;
                                $enable_auto_responder_email = $email_templates['enable_auto_responder'];
                            }
                        }
                    }

                    $data['application_acknowledgement_letter'] = $application_acknowledgement_letter;
                    $data['enable_auto_responder_email'] = $enable_auto_responder_email;
                    $data['pageName'] = 'job_details';
                    $data['isPaid'] = $data['is_paid'];
                    $data['dealership_website'] = '';
                    $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id); //Pages Information
                    $data['pages'] = $pages;
                    $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'about-us'); //About Us Information
                    $data['about_us'] = $about_us_text;
                    $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                    $footer_content['title'] = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                    $footer_content['content'] = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                    $data['footer_content'] = $footer_content;
                    $website = $data['company_details']['WebSite'];

                    if (!empty($website)) {
                        $data['dealership_website'] = $website;
                    }

                    $data['job_details'] = array();
                    $user_ip = getUserIP(); // get user Ip and Increment the job based on his IP
                    $job_session = 'job_' . $user_ip . '_' . $sid;
                    $job_increment_check = array($job_session => 'true');

                    if (!$this->session->userdata($job_session)) {
                        $this->job_details->increment_job_views($sid); // increment job views and create session
                        $this->session->set_userdata($job_increment_check);
                    }

                    $country_id = $list['Location_Country'];

                    if (!empty($country_id)) {
                        switch ($country_id) {
                            case 227:
                                $country_name = 'United States';
                                break;
                            case 38:
                                $country_name = 'Canada';
                                break;
                            default:
                                $country_name = '';
                                break;
                        }
                        $list['Location_Country'] = $country_name;
                    }

                    $state_id = $list['Location_State'];

                    if (!empty($state_id) && $state_id != 'undefined') {
                        $state_name = $this->job_details->get_statename_by_id($state_id); // get state name
                        $list['Location_State'] = $state_name[0]['state_name'];
                    }

                    $JobCategorys = $list['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->job_details->get_job_category_name_by_id($id);

                            if (!empty($job_cat_name)) {
                                $job_category_array[] = $job_cat_name[0]['value'];
                            }
                        }

                        $job_category = implode(',', $job_category_array);
                        $list['JobCategory'] = $job_category;
                    }

                    $date = substr($list['activation_date'], 0, 10); // change date format at front-end
                    $date_array = explode('-', $date);
                    $list['activation_date'] = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
                    $questionnaire_sid = $list['questionnaire_sid'];

                    if ($questionnaire_sid > 0) {
                        $portal_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);
                        if (!empty($portal_screening_questionnaires)) {
                            $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                            $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                            $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                            $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                            $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                            $list['q_pass_text'] = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                            $list['q_fail_text'] = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                            $list['my_id'] = 'q_question_' . $questionnaire_sid;
                            $screening_questions_numrows = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                            if ($screening_questions_numrows > 0) {
                                $screening_questions = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                                foreach ($screening_questions as $qkey => $qvalue) {
                                    $questions_sid = $qvalue['sid'];
                                    $list['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                    $screening_answers_numrows = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                    if ($screening_answers_numrows) {
                                        $screening_answers = $this->job_details->get_screening_answers_by_id($questions_sid);

                                        foreach ($screening_answers as $akey => $avalue) {
                                            $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $list['TitleOnly'] = $list['Title'];
                    $list['Title'] = db_get_job_title($company_sid, $list['Title'], $list['Location_City'], $list['Location_State'], $list['Location_Country']);
                    $data_countries = $this->check_domain->get_active_countries(); //get all active `countries`

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']] = $this->check_domain->get_active_states($value['sid']); //get all active `states`
                    }

                    $data['active_countries'] = $data_countries;
                    $data['active_states'] = $data_states;
                    $data['formpost'] = array();
                    $data['states'] = htmlentities(json_encode($data_states));
                    $data['company_details'] = $this->job_details->get_company_details($list['user_sid']);
                    $data['next_job'] = ''; //getting next and previous jobs link STARTS
                    $data['prev_job'] = '';
                    $next_job_anchor = '';
                    $prev_job_anchor = '';
                    $next_job_id = $this->job_details->next_job($list['sid'], $list['user_sid']);
                    $prev_job_id = $this->job_details->previous_job($list['sid'], $list['user_sid']);

                    if (!empty($next_job_id)) {
                        $next_id = $next_job_id['sid'];
                        $data['next_job'] = "job_details/$next_id";
                    }

                    if (!empty($prev_job_id)) {
                        $prev_id = $prev_job_id['sid'];
                        $data['prev_job'] = "job_details/$prev_id";
                    } //next and previous job link ENDS

                    $company_subdomain_url = STORE_PROTOCOL_SSL . db_get_sub_domain($company_sid); //Generate Share Links - start
                    $portal_job_url = $company_subdomain_url . '/job_details/' . $list['sid'];
                    $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                    $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                    $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                    // $btn_google                                                 = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                    $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&amp;title=' . urlencode($list['Title']) . '&amp;summary=' . urlencode($list['Title']) . '&amp;source=' . $portal_job_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-4.png"></a>';
                    $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list['Title']) . '</a>';
                    $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                    $links = '';
                    $links .= '<ul>';
                    // $links                                                      .= '<li>' . $btn_google . '</li>';
                    $links .= '<li>' . $btn_facebook . '</li>';
                    $links .= '<li>' . $btn_linkedin . '</li>';
                    $links .= '<li>' . $btn_twitter . '</li>';

                    if ($theme_name == 'theme-4') {
                        $links .= '<li>' . $btn_tell_a_friend . '</li>';
                    }

                    $links .= '</ul>';
                    $list['share_links'] = $links; //Generate Share Links - end
                    $data['job_details'] = $list;

                    if (empty($data['job_details']['pictures'])) {
                        $data['image'] = base_url('assets/theme-1/images/new_logo.JPG');
                    } else {
                        $data['image'] = AWS_S3_BUCKET_URL . $data['job_details']['pictures'];
                    }

                    $action = '';

                    if (isset($_POST['action'])) {
                        $action = $this->input->post('action');
                    } else if (isset($_POST['perform_action'])) {
                        $action = $this->input->post('perform_action');
                    }
                    $job_company_sid = $data['company_details']['sid'];

                    switch ($action) { //Setting Validation Rules for Different Post Requests
                        case 'job_applicant':
                            $this->checkUserAppliedForJob($job_company_sid, true);
                            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
                            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
                            $this->form_validation->set_rules('pictures', 'Pictures', 'trim');
                            $this->form_validation->set_rules('YouTube_Video', 'YouTube Video', 'trim');
                            $this->form_validation->set_rules('email', 'E-mail', 'trim|required');
                            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
                            $this->form_validation->set_rules('address', 'Address', 'trim');
                            $this->form_validation->set_rules('city', 'City', 'trim');
                            $this->form_validation->set_rules('state', 'State', 'trim');
                            $this->form_validation->set_rules('country', 'Country', 'trim');
                            $this->form_validation->set_rules('resume', 'Resume', 'trim');
                            $this->form_validation->set_rules('cover_letter', 'Cover Letter', 'trim');
                            $this->form_validation->set_rules('referred_by_name', 'Referred By Name', 'trim');
                            $this->form_validation->set_rules('referred_by_email', 'Referred By Email', 'trim');
                            break;
                        case 'friendShare':
                            $this->form_validation->set_rules('sender_name', 'Your Name', 'trim|required');
                            $this->form_validation->set_rules('receiver_name', "Your Friend's Name", 'trim|required');
                            $this->form_validation->set_rules('receiver_email', "Your Friend's Email Address", 'trim|required');
                            $this->form_validation->set_rules('comment', 'Comments', 'trim|required');
                            break;
                        case 'send_tell_a_friend_email':
                            $this->form_validation->set_rules('perform_action', 'perform_action', 'trim');
                            break;
                    }

                    $more_career_oppurtunatity = db_get_sub_domain($job_company_sid);
                    $job_company_career_title = $this->theme_meta_model->fGetThemeMetaData($job_company_sid, $theme_name, 'jobs', 'jobs_page_title');

                    if (empty($job_company_career_title)) {
                        $job_company_career_title = 'jobs';
                    }

                    $data['more_career_oppurtunatity'] = 'https://' . $more_career_oppurtunatity . '/' . $job_company_career_title;

                    if ($this->form_validation->run() == false) {
                        if ($data['is_paid']) {
                            $this->load->view($theme_name . '/_parts/header_view_iframe', $data);
                            $this->load->view($theme_name . '/_parts/page_banner_iframe');
                            $this->load->view($theme_name . '/job_detail_view_iframe');
                            $this->load->view($theme_name . '/_parts/footer_view_iframe');
                        } else {
                            $this->load->view($theme_name . '/_parts/header_view_iframe', $data);
                            $this->load->view($theme_name . '/job_detail_view_iframe');
                            $this->load->view($theme_name . '/_parts/footer_view_iframe');
                        }
                    } else { //Handle Post

                        $action = '';
                        if (isset($_POST['action'])) {
                            $action = $this->input->post('action');
                        } else if (isset($_POST['perform_action'])) {
                            $action = $this->input->post('perform_action');
                        }

                        // Added on: 11-07-2019
                        // Reset phone numbers
                        $txt_phone_number = $this->input->post('phone_number', true);
                        if ($this->input->post('txt_phonenumber', true))
                            $txt_phone_number = $this->input->post('txt_phonenumber', true);

                        switch ($action) {
                            case 'job_applicant':
                                $my_ip = getUserIP();

                                // if (in_array($company_sid, array("7", "51")) || $my_ip == '72.255.38.246') {
                                if (!in_array($company_sid, array("0"))) {

                                    $job_sid = $this->input->post('job_sid');
                                    $first_name = $this->input->post('first_name');
                                    $last_name = $this->input->post('last_name');
                                    $YouTube_Video = $this->input->post('YouTube_Video');
                                    $email = $this->input->post('email');
                                    $is_blocked_email = checkForBlockedEmail($email);

                                    if ($is_blocked_email == 'blocked') {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }

                                    $phone_number = $txt_phone_number;
                                    $address = $this->input->post('address');
                                    $city = $this->input->post('city');
                                    $state = $this->input->post('state');
                                    $country = $this->input->post('country');
                                    $referred_by_name = $this->input->post('referred_by_name');
                                    $referred_by_email = $this->input->post('referred_by_email');
                                    $YouTube_code = '';
                                    $resume = '';
                                    $pictures = '';
                                    $cover_letter = '';
                                    $eeo_form = 'No';
                                    $job_details = $this->job_details->fetch_jobs_details($job_sid);
                                    $original_job_title = $job_details['Title'];

                                    if ($this->input->post('EEO') != NULL) {
                                        $eeo_form = $this->input->post('EEO');
                                    }

                                    if (!empty($YouTube_Video)) {
                                        $YouTube_code = substr($YouTube_Video, strpos($YouTube_Video, '=') + 1, strlen($YouTube_Video));
                                    }
                                    //
                                    if (check_company_status($company_sid) == 0) {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }
                                    //
                                    $already_applied = $this->job_details->check_job_applicant($job_sid, $email, $company_sid); //check if the user has already applied for this job

                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    } else {  // fetch data and insert into database //echo 'New Applicant';
                                        $questionnaire_serialize = '';
                                        $total_score = 0;
                                        $total_questionnaire_score = 0;
                                        $q_passing = 0;
                                        $array_questionnaire = array();
                                        $overall_status = 'Pass';
                                        $is_string = 0;
                                        $screening_questionnaire_results = array();
                                        $job_type = '';
                                        $log_resume_name = '';
                                        $log_resume_extension = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey = $_POST['unique_key'];
                                            $myUploadData = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file = explode(".", $_FILES["resume"]["name"]);
                                                $file_name = str_replace(" ", "-", $file[0]);
                                                $resume = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                                $log_resume_name = $_FILES['resume']['name'];
                                                $log_resume_extension = $file[1];
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $pictures = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                                            $aws = new AwsSdk();
                                            // $aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                                            unlink('images/' . $pictures);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $cover_letter = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid = $data['job_details']['user_sid'];
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');

                                            if ($applied_from == 'job') {
                                                redirect('/job-feed-details/' . $sid, 'refresh');
                                            } else if ($applied_from == 'jobs_list_view') {
                                                redirect('/job-feed/');
                                            } else {
                                                redirect('/', 'refresh');
                                            }

                                            break;
                                        }
                                        //
                                        $status_array = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Defult Status
                                        // Check if user has already applied in this company for any other job
                                        $portal_job_applications_sid = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully = 0;
                                        $date_applied = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid' => $employer_sid,
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'YouTube_Video' => $YouTube_code,
                                                'email' => $email,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'resume' => $resume,
                                                'pictures' => $pictures,
                                                'cover_letter' => $cover_letter,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            $output = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid' => $job_applications_sid,
                                                    'company_sid' => $employer_sid,
                                                    'job_sid' => $job_sid,
                                                    'date_applied' => $date_applied,
                                                    'status' => $status_array['status_name'],
                                                    'status_sid' => $status_array['status_sid'],
                                                    'questionnaire' => $questionnaire_serialize,
                                                    'score' => $total_score,
                                                    'passing_score' => $q_passing,
                                                    'applicant_source' => STORE_FULL_URL_SSL,
                                                    'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                    'ip_address' => getUserIP(),
                                                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form' => $eeo_form,
                                                    'resume' => $resume ? $resume : NULL,
                                                    'last_update' => date('Y-m-d')
                                                );

                                                $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                                $job_added_successfully = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid = $portal_job_applications_sid;

                                            $update_data_primary = array(
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube = array('YouTube_Video' => $YouTube_code);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume = array('resume' => $resume);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures = array('pictures' => $pictures);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter = array('cover_letter' => $cover_letter);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }

                                            $old_s3_resume = $this->job_details->get_old_resume($job_applications_sid);
                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            if (!empty($old_s3_resume)) {
                                                $resume_log_data = array();
                                                $resume_log_data['company_sid'] = $employer_sid;
                                                $resume_log_data['user_type'] = 'Applicant';
                                                $resume_log_data['user_sid'] = $job_applications_sid;
                                                $resume_log_data['user_email'] = $email;
                                                $resume_log_data['requested_by'] = 0;
                                                $resume_log_data['requested_subject'] = 'NULL';
                                                $resume_log_data['requested_message'] = 'NULL';
                                                $resume_log_data['requested_ip_address'] = getUserIP();
                                                $resume_log_data['requested_user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                                                $resume_log_data['request_status'] = 3;
                                                $resume_log_data['is_respond'] = 1;
                                                $resume_log_data['resume_original_name'] = $log_resume_name;
                                                $resume_log_data['resume_s3_name'] = $resume;
                                                $resume_log_data['resume_extension'] = $log_resume_extension;
                                                $resume_log_data['old_resume_s3_name'] = $old_s3_resume;
                                                $resume_log_data['response_date'] = date('Y-m-d H:i:s');
                                                $resume_log_data['requested_date'] = date('Y-m-d H:i:s');
                                                $resume_log_data['job_sid'] = $job_sid;
                                                $resume_log_data['job_type'] = 'job';

                                                $this->job_details->insert_resume_log($resume_log_data);
                                            }

                                            $insert_job_list = array(
                                                'portal_job_applications_sid' => $job_applications_sid,
                                                'company_sid' => $employer_sid,
                                                'job_sid' => $job_sid,
                                                'date_applied' => $date_applied,
                                                'status' => $status_array['status_name'],
                                                'status_sid' => $status_array['status_sid'],
                                                'questionnaire' => $questionnaire_serialize,
                                                'score' => $total_score,
                                                'passing_score' => $q_passing,
                                                'applicant_source' => STORE_FULL_URL_SSL,
                                                'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                'ip_address' => getUserIP(),
                                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form' => $eeo_form,
                                                'resume' => $resume ? $resume : NULL,
                                                'last_update' => date('Y-m-d')
                                            );

                                            $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                            $job_added_successfully = $jobs_list_result[1];
                                        }

                                        if (!isset($resume) || empty($resume) || $resume == '') {
                                            sendResumeEmailToApplicant([
                                                'company_sid' => $company_sid,
                                                'company_name' => $company_name,
                                                'job_list_sid' => $job_sid,
                                                'user_sid' => $job_applications_sid,
                                                'user_type' => 'applicant',
                                                'requested_job_sid' => $job_sid,
                                                'requested_job_type' => 'job'
                                            ], false);
                                        }

                                        if ($job_added_successfully == 1) { // send confirmation emails to Primary admin
                                            $company_name = $data['company_details']['CompanyName'];
                                            $company_email = TO_EMAIL_INFO; //$data['company_details']['email'];
                                            $resume_url = '';
                                            $resume_anchor = '';
                                            $profile_anchor = '<a href="https://www.automotohr.com/applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) { // resume check here - change to button
                                                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title = $data['job_details']['Title'];
                                            $replacement_array = array();
                                            $replacement_array['site_url'] = base_url();
                                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title'] = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number'] = $phone_number;
                                            $replacement_array['city'] = $city;
                                            $replacement_array['company_name'] = $company_name;
                                            $message_hf = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status = get_notifications_status($company_sid);
                                            $my_debug_message = json_encode($replacement_array);
                                            $applicant_notifications_status = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                                            } /*else {
                                            // mail(TO_EMAIL_DEV, STORE_NAME.' Apply Now Debug - No Status Record Found', $my_debug_message);
                                        } */

                                            $applicant_notification_contacts = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname'] = $first_name;
                                                        $replacement_array['lastname'] = $last_name;
                                                        $replacement_array['email'] = $email;
                                                        $replacement_array['company_name'] = $company_name;
                                                        $replacement_array['resume_link'] = $resume_anchor;
                                                        $replacement_array['applicant_profile_link'] = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }

                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                //56357
                                                $from = REPLY_TO;
                                                $subject = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body = $acknowledgement_email_body;
                                                $message_data = array();
                                                $message_data['contact_name'] = $first_name . ' ' . $last_name;
                                                $message_data['to_id'] = $email;
                                                $message_data['from_type'] = 'employer';
                                                $message_data['to_type'] = 'admin';
                                                $message_data['job_id'] = $job_applications_sid;
                                                $message_data['users_type'] = 'applicant';
                                                $message_data['subject'] = 'Application Acknowledgement Letter';
                                                $message_data['message'] = $body;
                                                $message_data['date'] = date('Y-m-d H:i:s');
                                                $message_data['from_id'] = REPLY_TO;
                                                $message_data['identity_key'] = generateRandomString(48);
                                                $message_hf = message_header_footer_domain($company_id, $company_name);
                                                $secret_key = $message_data['identity_key'] . "__";
                                                $autoemailbody = $message_hf['header']
                                                    . '<p>Subject: ' . $subject . '</p>'
                                                    . $body
                                                    . $message_hf['footer']
                                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                    . $secret_key . '</div>';

                                                sendMail(REPLY_TO, $email, $subject, $autoemailbody, $from_name, REPLY_TO);
                                                $sent_to_pm = $this->contact_model->save_message($message_data);
                                                $email_log_autoresponder = array();
                                                $email_log_autoresponder['company_sid'] = $company_id;
                                                $email_log_autoresponder['sender'] = REPLY_TO;
                                                $email_log_autoresponder['receiver'] = $email;
                                                $email_log_autoresponder['from_type'] = 'employer';
                                                $email_log_autoresponder['to_type'] = 'admin';
                                                $email_log_autoresponder['users_type'] = 'applicant';
                                                $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                                                $email_log_autoresponder['subject'] = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $email_log_autoresponder['message'] = $autoemailbody;
                                                $email_log_autoresponder['job_or_employee_id'] = $job_applications_sid;
                                                $save_email_log = $this->contact_model->save_email_log_autoresponder($email_log_autoresponder);
                                            }

                                            // check if screening questionnaire is attached to email and send pass or fail email to applicant
                                            if ($_POST['questionnaire_sid'] > 0) {  // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire = array();
                                                $q_name = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing = 0;
                                                    $post_questions_sid = $value;
                                                    $caption = 'caption' . $value;
                                                    $type = 'type' . $value;
                                                    $answer = $_POST[$type] . $value;
                                                    $questions_type = $_POST[$type];
                                                    $my_question = '';
                                                    $individual_score = 0;
                                                    $individual_passing_score = 0;
                                                    $individual_status = 'Pass';
                                                    $result_status = array();

                                                    if (isset($_POST[$caption])) {
                                                        $my_question = $_POST[$caption];
                                                    }

                                                    $my_answer = NULL;

                                                    if (isset($_POST[$answer])) {
                                                        $my_answer = $_POST[$answer];
                                                    }

                                                    if ($questions_type != 'string') { // get the question possible score
                                                        $q_passing = $this->job_details->get_possible_score_of_questions($post_questions_sid, $questions_type);
                                                    }

                                                    if ($my_answer != NULL) { // It is required question
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
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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
                                                        } else { // hassan WORKING area
                                                            $result = explode('@#$', $my_answer);
                                                            $total_questionnaire_score += $q_passing;
                                                            $a = $result[0];
                                                            $answered = $a;
                                                            $answered_result_status = '';
                                                            $answered_question_score = 0;

                                                            if (isset($result[1])) {
                                                                $answered_question_sid = $result[1];
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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

                                                        if (!empty($result_status)) {
                                                            if (in_array('Fail', $result_status)) {
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

                                                    $array_questionnaire[$my_question] = array(
                                                        'answer' => $answered,
                                                        'passing_score' => $individual_passing_score,
                                                        'score' => $individual_score,
                                                        'status' => $individual_status,
                                                        'answered_result_status' => $answered_result_status,
                                                        'answered_question_score' => $answered_question_score
                                                    );
                                                    //echo '<pre>'; print_r($array_questionnaire); echo '</pre><hr>';
                                                } // here

                                                $questionnaire_result = $overall_status;
                                                $datetime = date('Y-m-d H:i:s');
                                                $remote_addr = getUserIP();
                                                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                                                $questionnaire_data = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $questionnaire_serialize = serialize($questionnaire_data);
                                                $array_questionnaire_serialize = serialize($array_questionnaire);
                                                //echo '<pre>'; print_r($questionnaire_data); echo '</pre><hr>';
                                                $screening_questionnaire_results = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire_serialize,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $this->job_details->update_questionnaire_result($portal_applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
                                                $this->job_details->insert_questionnaire_result($screening_questionnaire_results);
                                                $send_mail = false;
                                                $mail_body = '';

                                                if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                                                    $send_mail = true;
                                                    $mail_body = $q_pass_text;
                                                }

                                                if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                                                    $send_mail = true;
                                                    $mail_body = $q_fail_text;
                                                }

                                                if ($send_mail) {
                                                    $from = TO_EMAIL_INFO;
                                                    $fromname = $company_name;
                                                    $title = $data['job_details']['Title'];
                                                    $subject = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to = $email;
                                                    $mail_body = str_replace('{{company_name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant_name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job_title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first_name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last_name}}', ucwords($last_name), $mail_body);
                                                    $mail_body = str_replace('{{company-name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant-name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job-title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first-name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last-name}}', ucwords($last_name), $mail_body);
                                                    sendMail($from, $to, $subject, $mail_body, $fromname);
                                                }
                                            }

                                            if ($eeo_form == 'Yes') { //Getting data for EEO Form Starts
                                                $eeo_data = array(
                                                    'application_sid' => $job_applications_sid,
                                                    'users_type' => "applicant",
                                                    'portal_applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'us_citizen' => $this->input->post('us_citizen'),
                                                    'visa_status ' => $this->input->post('visa_status'),
                                                    'group_status' => $this->input->post('group_status'),
                                                    'veteran' => $this->input->post('veteran'),
                                                    'disability' => $this->input->post('disability'),
                                                    'gender' => $this->input->post('gender'),
                                                    'is_expired' => 1
                                                );

                                                $this->job_details->save_eeo_form($eeo_data);
                                            } //Getting data for EEO Form Ends

                                            $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        }

                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/', 'refresh');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                } else {
                                    $job_sid = $this->input->post('job_sid');
                                    $first_name = $this->input->post('first_name');
                                    $last_name = $this->input->post('last_name');
                                    $YouTube_Video = $this->input->post('YouTube_Video');
                                    $email = $this->input->post('email');
                                    $is_blocked_email = checkForBlockedEmail($email);

                                    if ($is_blocked_email == 'blocked') {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }

                                    $phone_number = $txt_phone_number;
                                    $address = $this->input->post('address');
                                    $city = $this->input->post('city');
                                    $state = $this->input->post('state');
                                    $country = $this->input->post('country');
                                    $referred_by_name = $this->input->post('referred_by_name');
                                    $referred_by_email = $this->input->post('referred_by_email');
                                    $YouTube_code = '';
                                    $resume = '';
                                    $pictures = '';
                                    $cover_letter = '';
                                    $eeo_form = 'No';
                                    $job_details = $this->job_details->fetch_jobs_details($job_sid);
                                    $original_job_title = $job_details['Title'];

                                    if ($this->input->post('EEO') != NULL) {
                                        $eeo_form = $this->input->post('EEO');
                                    }

                                    if (!empty($YouTube_Video)) {
                                        $YouTube_code = substr($YouTube_Video, strpos($YouTube_Video, '=') + 1, strlen($YouTube_Video));
                                    }
                                    //
                                    if (check_company_status($company_sid) == 0) {
                                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }

                                        break;
                                    }
                                    //

                                    $already_applied = $this->job_details->check_job_applicant($job_sid, $email, $company_sid); //check if the user has already applied for this job

                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    } else { // fetch data and insert into database //echo 'New Applicant';
                                        $questionnaire_serialize = '';
                                        $total_score = 0;
                                        $total_questionnaire_score = 0;
                                        $q_passing = 0;
                                        $array_questionnaire = array();
                                        $overall_status = 'Pass';
                                        $is_string = 0;
                                        $screening_questionnaire_results = array();
                                        $job_type = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey = $_POST['unique_key'];
                                            $myUploadData = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file = explode(".", $_FILES["resume"]["name"]);
                                                $file_name = str_replace(" ", "-", $file[0]);
                                                $resume = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $pictures = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            generate_image_compressed($_FILES['pictures']['tmp_name'], 'images/' . $pictures);
                                            $aws = new AwsSdk();
                                            // $aws->putToBucket($pictures, $_FILES["pictures"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                                            unlink('images/' . $pictures);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name = str_replace(" ", "-", $file[0]);
                                            $cover_letter = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid = $data['job_details']['user_sid'];
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');

                                            if ($applied_from == 'job') {
                                                redirect('/job-feed-details/' . $sid, 'refresh');
                                            } else if ($applied_from == 'jobs_list_view') {
                                                redirect('/job-feed/');
                                            } else {
                                                redirect('/', 'refresh');
                                            }

                                            break;
                                        }
                                        //
                                        $status_array = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Defult Status
                                        // Check if user has already applied in this company for any other job
                                        $portal_job_applications_sid = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully = 0;
                                        $date_applied = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid' => $employer_sid,
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'YouTube_Video' => $YouTube_code,
                                                'email' => $email,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'resume' => $resume,
                                                'pictures' => $pictures,
                                                'cover_letter' => $cover_letter,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            $output = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid' => $job_applications_sid,
                                                    'company_sid' => $employer_sid,
                                                    'job_sid' => $job_sid,
                                                    'date_applied' => $date_applied,
                                                    'status' => $status_array['status_name'],
                                                    'status_sid' => $status_array['status_sid'],
                                                    'questionnaire' => $questionnaire_serialize,
                                                    'score' => $total_score,
                                                    'passing_score' => $q_passing,
                                                    'applicant_source' => STORE_FULL_URL_SSL,
                                                    'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                    'ip_address' => getUserIP(),
                                                    'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form' => $eeo_form
                                                );

                                                $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                                $job_added_successfully = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid = $portal_job_applications_sid;

                                            $update_data_primary = array(
                                                'first_name' => $first_name,
                                                'last_name' => $last_name,
                                                'phone_number' => $phone_number,
                                                'address' => $address,
                                                'city' => $city,
                                                'state' => $state,
                                                'country' => $country,
                                                'referred_by_name' => $referred_by_name,
                                                'referred_by_email' => $referred_by_email
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube = array('YouTube_Video' => $YouTube_code);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume = array('resume' => $resume);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures = array('pictures' => $pictures);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter = array('cover_letter' => $cover_letter);
                                                $update_data_primary = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }

                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            $insert_job_list = array(
                                                'portal_job_applications_sid' => $job_applications_sid,
                                                'company_sid' => $employer_sid,
                                                'job_sid' => $job_sid,
                                                'date_applied' => $date_applied,
                                                'status' => $status_array['status_name'],
                                                'status_sid' => $status_array['status_sid'],
                                                'questionnaire' => $questionnaire_serialize,
                                                'score' => $total_score,
                                                'passing_score' => $q_passing,
                                                'applicant_source' => STORE_FULL_URL_SSL,
                                                'main_referral' => $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : STORE_FULL_URL_SSL,
                                                'ip_address' => getUserIP(),
                                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form' => $eeo_form
                                            );

                                            $jobs_list_result = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                                            $job_added_successfully = $jobs_list_result[1];
                                        }

                                        if ($job_added_successfully == 1) { // send confirmation emails to Primary admin
                                            $company_name = $data['company_details']['CompanyName'];
                                            $company_email = TO_EMAIL_INFO; //$data['company_details']['email'];
                                            $resume_url = '';
                                            $resume_anchor = '';
                                            $profile_anchor = '<a href="https://www.automotohr.com/applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) { // resume check here - change to button
                                                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title = $data['job_details']['Title'];
                                            $replacement_array = array();
                                            $replacement_array['site_url'] = base_url();
                                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title'] = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number'] = $phone_number;
                                            $replacement_array['city'] = $city;
                                            $replacement_array['company_name'] = $company_name;
                                            $message_hf = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status = get_notifications_status($company_sid);
                                            $my_debug_message = json_encode($replacement_array);
                                            $applicant_notifications_status = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                                            } /*else {
                                            // mail(TO_EMAIL_DEV, STORE_NAME.' Apply Now Debug - No Status Record Found', $my_debug_message);
                                        } */

                                            $applicant_notification_contacts = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname'] = $first_name;
                                                        $replacement_array['lastname'] = $last_name;
                                                        $replacement_array['email'] = $email;
                                                        $replacement_array['company_name'] = $company_name;
                                                        $replacement_array['resume_link'] = $resume_anchor;
                                                        $replacement_array['applicant_profile_link'] = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }

                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                //56357
                                                $from = REPLY_TO;
                                                $subject = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body = $acknowledgement_email_body;
                                                $message_data = array();
                                                $message_data['contact_name'] = $first_name . ' ' . $last_name;
                                                $message_data['to_id'] = $email;
                                                $message_data['from_type'] = 'employer';
                                                $message_data['to_type'] = 'admin';
                                                $message_data['job_id'] = $job_applications_sid;
                                                $message_data['users_type'] = 'applicant';
                                                $message_data['subject'] = 'Application Acknowledgement Letter';
                                                $message_data['message'] = $body;
                                                $message_data['date'] = date('Y-m-d H:i:s');
                                                $message_data['from_id'] = REPLY_TO;
                                                $message_data['identity_key'] = generateRandomString(48);
                                                $message_hf = message_header_footer_domain($company_id, $company_name);
                                                $secret_key = $message_data['identity_key'] . "__";
                                                $autoemailbody = $message_hf['header']
                                                    . '<p>Subject: ' . $subject . '</p>'
                                                    . $body
                                                    . $message_hf['footer']
                                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                    . $secret_key . '</div>';

                                                sendMail(REPLY_TO, $email, $subject, $autoemailbody, $from_name, REPLY_TO);
                                                $sent_to_pm = $this->contact_model->save_message($message_data);
                                                $email_log_autoresponder = array();
                                                $email_log_autoresponder['company_sid'] = $company_id;
                                                $email_log_autoresponder['sender'] = REPLY_TO;
                                                $email_log_autoresponder['receiver'] = $email;
                                                $email_log_autoresponder['from_type'] = 'employer';
                                                $email_log_autoresponder['to_type'] = 'admin';
                                                $email_log_autoresponder['users_type'] = 'applicant';
                                                $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                                                $email_log_autoresponder['subject'] = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $email_log_autoresponder['message'] = $autoemailbody;
                                                $email_log_autoresponder['job_or_employee_id'] = $job_applications_sid;
                                                $save_email_log = $this->contact_model->save_email_log_autoresponder($email_log_autoresponder);
                                            }

                                            // check if screening questionnaire is attached to email and send pass or fail email to applicant
                                            if ($_POST['questionnaire_sid'] > 0) {  // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire = array();
                                                $q_name = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing = 0;
                                                    $post_questions_sid = $value;
                                                    $caption = 'caption' . $value;
                                                    $type = 'type' . $value;
                                                    $answer = $_POST[$type] . $value;
                                                    $questions_type = $_POST[$type];
                                                    $my_question = '';
                                                    $individual_score = 0;
                                                    $individual_passing_score = 0;
                                                    $individual_status = 'Pass';
                                                    $result_status = array();

                                                    if (isset($_POST[$caption])) {
                                                        $my_question = $_POST[$caption];
                                                    }

                                                    $my_answer = NULL;

                                                    if (isset($_POST[$answer])) {
                                                        $my_answer = $_POST[$answer];
                                                    }

                                                    if ($questions_type != 'string') { // get the question possible score
                                                        $q_passing = $this->job_details->get_possible_score_of_questions($post_questions_sid, $questions_type);
                                                    }

                                                    if ($my_answer != NULL) { // It is required question
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
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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
                                                        } else { // hassan WORKING area
                                                            $result = explode('@#$', $my_answer);
                                                            $total_questionnaire_score += $q_passing;
                                                            $a = $result[0];
                                                            $answered = $a;
                                                            $answered_result_status = '';
                                                            $answered_question_score = 0;

                                                            if (isset($result[1])) {
                                                                $answered_question_sid = $result[1];
                                                                $question_details = $this->job_details->get_individual_question_details($answered_question_sid);

                                                                if (!empty($question_details)) {
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

                                                        if (!empty($result_status)) {
                                                            if (in_array('Fail', $result_status)) {
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

                                                    $array_questionnaire[$my_question] = array(
                                                        'answer' => $answered,
                                                        'passing_score' => $individual_passing_score,
                                                        'score' => $individual_score,
                                                        'status' => $individual_status,
                                                        'answered_result_status' => $answered_result_status,
                                                        'answered_question_score' => $answered_question_score
                                                    );
                                                    //echo '<pre>'; print_r($array_questionnaire); echo '</pre><hr>';
                                                } // here

                                                $questionnaire_result = $overall_status;
                                                $datetime = date('Y-m-d H:i:s');
                                                $remote_addr = getUserIP();
                                                $user_agent = $_SERVER['HTTP_USER_AGENT'];

                                                $questionnaire_data = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $questionnaire_serialize = serialize($questionnaire_data);
                                                $array_questionnaire_serialize = serialize($array_questionnaire);
                                                //echo '<pre>'; print_r($questionnaire_data); echo '</pre><hr>';
                                                $screening_questionnaire_results = array(
                                                    'applicant_sid' => $job_applications_sid,
                                                    'applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'job_sid' => $job_sid,
                                                    'job_title' => $title,
                                                    'job_type' => $job_type,
                                                    'company_sid' => $company_sid,
                                                    'questionnaire_name' => $questionnaire_name,
                                                    'questionnaire' => $array_questionnaire_serialize,
                                                    'questionnaire_result' => $questionnaire_result,
                                                    'attend_timestamp' => $datetime,
                                                    'questionnaire_ip_address' => $remote_addr,
                                                    'questionnaire_user_agent' => $user_agent
                                                );

                                                $this->job_details->update_questionnaire_result($portal_applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
                                                $this->job_details->insert_questionnaire_result($screening_questionnaire_results);
                                                $send_mail = false;
                                                $mail_body = '';

                                                if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                                                    $send_mail = true;
                                                    $mail_body = $q_pass_text;
                                                }

                                                if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                                                    $send_mail = true;
                                                    $mail_body = $q_fail_text;
                                                }

                                                if ($send_mail) {
                                                    $from = TO_EMAIL_INFO;
                                                    $fromname = $company_name;
                                                    $title = $data['job_details']['Title'];
                                                    $subject = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to = $email;
                                                    $mail_body = str_replace('{{company_name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant_name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job_title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first_name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last_name}}', ucwords($last_name), $mail_body);
                                                    $mail_body = str_replace('{{company-name}}', ucwords($company_name), $mail_body);
                                                    $mail_body = str_replace('{{applicant-name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                                                    $mail_body = str_replace('{{job-title}}', $title, $mail_body);
                                                    $mail_body = str_replace('{{first-name}}', ucwords($first_name), $mail_body);
                                                    $mail_body = str_replace('{{last-name}}', ucwords($last_name), $mail_body);
                                                    sendMail($from, $to, $subject, $mail_body, $fromname);
                                                }
                                            }

                                            if ($eeo_form == 'Yes') { //Getting data for EEO Form Starts
                                                $eeo_data = array(
                                                    'application_sid' => $job_applications_sid,
                                                    'users_type' => "applicant",
                                                    'portal_applicant_jobs_list_sid' => $portal_applicant_jobs_list_sid,
                                                    'us_citizen' => $this->input->post('us_citizen'),
                                                    'visa_status ' => $this->input->post('visa_status'),
                                                    'group_status' => $this->input->post('group_status'),
                                                    'veteran' => $this->input->post('veteran'),
                                                    'disability' => $this->input->post('disability'),
                                                    'gender' => $this->input->post('gender'),
                                                    'is_expired' => 1
                                                );

                                                $this->job_details->save_eeo_form($eeo_data);
                                            } //Getting data for EEO Form Ends

                                            $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        }

                                        $applied_from = $this->input->post('applied_from');

                                        if ($applied_from == 'job') {
                                            redirect('/job-feed-details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/job-feed/', 'refresh');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                }

                                break;
                            case 'friendShare':
                                $sender_name = $this->input->post('sender_name');
                                $receiver_name = $this->input->post('receiver_name');
                                $receiver_email = $this->input->post('receiver_email');
                                $sender_email = $this->input->post('sender_email');
                                $comment = $this->input->post('comment');
                                $is_sender_blocked = checkForBlockedEmail($sender_email);
                                $is_receiver_blocked = checkForBlockedEmail($receiver_email);

                                if ($is_sender_blocked == 'blocked' || $is_receiver_blocked) {
                                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you.');
                                    redirect('/job-feed-details/' . $sid, 'refresh');
                                    break;
                                }

                                $check_already_request = $this->job_details->check_if_applied_already($sid);

                                if (isset($_POST['g-recaptcha-response']) && isset($_SERVER['HTTP_COOKIE']) && !empty($this->input->post('g-recaptcha-response')) && $check_already_request < 3) {
                                    $this->job_details->save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, 'sent');
                                    $this->job_details->friend_share_job($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $data);
                                    if ($check_already_request != 0) {
                                        //Send Email to Ali Bhai
                                        sendMail('info@automotohr.com', 'dev@automotohr.com', 'Spam Alert!', print_r($_POST, true) . print_r($_SERVER, true));
                                    }
                                } else {
                                    $this->job_details->save_friend_share_job_history($sender_name, $sender_email, $receiver_name, $receiver_email, $comment, $sid, 'not-sent');
                                }
                                $post_data = $_POST;
                                $server_data = $_SERVER;
                                mail('mubashar.ahmed@egenienext.com', 'Friend Share Spam', print_r($post_data, true));
                                mail('mubashar.ahmed@egenienext.com', 'FS Spam Server', print_r($server_data, true));
                                redirect('/job_details/' . $sid, 'refresh');
                                break;
                            case 'send_tell_a_friend_email':
                                sendMail('info@automotohr.com', 'dev@automotohr.com', 'send_tell_a_friend_email_ams!', print_r($_POST, true) . print_r($_SERVER, true));
                                // $senderName                                     = $_POST['sender_name'];
                                // $receiverName                                   = $_POST['receiver_name'];
                                // $receiverEmail                                  = $_POST['receiver_email'];
                                // $message                                        = $_POST['message'];
                                // $subject                                        = ucwords($senderName) . ' Recommends ' . $list['Title'];
                                // $message_body                                   = '';
                                // $message_body                                   .= '<h1>' . 'Hi ' . $receiverName . '</h1>';
                                // $message_body                                   .= '<h3>' . $senderName . '</h3>';
                                // $message_body                                   .= '<p>' . 'Has Recommended The Following Job on our Website to You:' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" target="_blank" href="' . base_url('job_details') . '/' . $sid . '">' . $list['Title'] . '</a>' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= '<p>' . '<strong>' . 'Attached Personal Message' . '</strong>' . '</p>';
                                // $message_body                                   .= '<p>' . $message . '</p>';
                                // $message_body                                   .= '<p>' . '</p>';
                                // $message_body                                   .= FROM_INFO_EMAIL_DISCLAIMER_MSG;
                                //
                                // if (base_url() == 'http://ams.example/') { //echo 'Local Working';
                                //     save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                // } else { //echo 'LIVE';
                                //     save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                //     sendMail(FROM_EMAIL_INFO, $receiverEmail, $subject, $message_body);
                                // }
                                break;
                        }
                    }
                } else { //Job Id Is not 0 But Job Not Found
                    $this->session->set_flashdata('message', 'No Active job found!');
                    redirect('/', 'refresh');
                }
            } else { //Job Id Is 0 or Null
                $this->session->set_flashdata('message', 'No Active job found!');
                redirect('/', 'refresh');
            }
        } else { // Portal Deactivated or Maintenance Mode
            redirect('/', 'refresh');
        }
    }

    private function isActiveFeed()
    {
        $validSlug = $this->job_details->check_for_slug('automotosocial');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}

if (!function_exists('remakeSalary')) {
    function remakeSalary($salary, $jobType)
    {
        $salary = trim(str_replace([',', 'k', 'K'], ['', '000', '000'], $salary));
        $jobType = strtolower($jobType);
        //
        if (preg_match('/year|yearly/', $jobType))
            $jobType = 'per year';
        else if (preg_match('/month|monthly/', $jobType))
            $jobType = 'per month';
        else if (preg_match('/week|weekly/', $jobType))
            $jobType = 'per week';
        else if (preg_match('/hour|hourly/', $jobType))
            $jobType = 'per hour';
        else
            $jobType = 'per year';
        //
        if ($salary == '')
            return $salary;
        //
        if (strpos($salary, '$') === FALSE)
            $salary = preg_replace('/(?<![^ ])(?=[^ ])(?![^0-9])/', '$', $salary);
        //
        $salary = $salary . ' ' . $jobType;
        //
        return $salary;
    }
}
