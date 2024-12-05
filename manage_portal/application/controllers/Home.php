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
        $this->load->model('common/testimonials_model');
        $this->load->model('common/themes_pages_model');
        $this->load->library('google_auth');
        $this->load->library('encryption');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $server_name = clean_domain($_SERVER['SERVER_NAME']);


        // $_SERVER['HTTP_REFERER'] = 'https://www.google.com';

        // _e($this->session->userdata('last_referral'), true);
        // _e($this->session->userdata('referral_details'), true);

        if (isset($_SERVER['HTTP_REFERER'])) { // Check that the referer is external or internal
            $referral = $_SERVER['HTTP_REFERER'];
            $clean_referral = clean_domain($referral);

            if (!$this->session->userdata('last_referral')) $this->session->set_userdata('last_referral', $referral);

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
        $company_sid = $data['company_details']['sid'];
        $data['customize_career_site'] = $this->themes_pages_model->getCustomizeCareerSiteData($company_sid);
        $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();
        company_phone_regex_module_check($company_sid, $data, $this);

        //
        $data['sms_module_status'] = $data['company_details']['sms_module_status'];

        if (!$this->session->userdata('portal_info')) {
            $this->session->set_userdata('portal_info', $data);
        }

        $theme_name = $data['theme_name'];
        $job_approval_module_status = 0;

        if (isset($data['company_details']['has_job_approval_rights'])) {
            $job_approval_module_status = $data['company_details']['has_job_approval_rights'];
        }

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            $isPaid = $data['is_paid'];
            $country = '';
            $state = '';
            $city = '';
            $categoryId = '';
            $keyword = '';
            $pageName = '';
            $segment1 = $this->uri->segment(1);
            $list = array();
            $categories_in_active_jobs = array();
            $data['pages'] = array();
            $countries_array = array();
            $states_array = array();
            $country_states_array = array();

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

            if (!empty($segment6)) {
                $keyword = urldecode($segment6);
                $data['search_params']['country'] = $country;
                $data['search_params']['state'] = $state;
                $data['search_params']['city'] = $city;
                $data['search_params']['category'] = $categoryId;
                $data['search_params']['keyword'] = $keyword;
            }

            if ($theme_name == 'theme-4') {
                $pageName = $this->uri->segment(1);
                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'jobs', 'jobs_page_title');
                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
                $data['jobs_page_title'] = $jobs_page_title;
                $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));

                if ($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) {
                    $pageName = 'jobs';
                }

                if ($pageName == '') {
                    $pageName = 'home';
                }
                if ($data['customize_career_site']['status'] == 1 && in_array($pageName, $data['customize_career_site']['inactive_pages'])) {
                    redirect(base_url('jobs'));
                }
            }
            $paid_jobs = [];
            $career_site_company_sid = [];
            $per_page                                                           = PAGINATION_RECORDS_PER_PAGE;
            $offset                                                             = 0;
            $featured_jobs_count                                    = 0;
            $list_count                                             = 0;
            $segment7                                                           = $this->uri->segment(7);
            $ajax_flag                                                          = $this->uri->segment(8);

            $companyIds = [$company_sid];
            if ($data['customize_career_site']['status'] == 1) {

                if (!empty($segment7) && $segment7 > 1) {
                    $offset = ($segment7 - 1) * $per_page;
                }
                $career_site_only_companies                             = $this->job_details->get_career_site_only_companies(); // get the career site status for companies
                $career_site_company_sid                                = array();

                if (!empty($career_site_only_companies)) {
                    foreach ($career_site_only_companies as $csoc) {
                        $career_site_company_sid[]                      = $csoc['sid'];
                    }
                }

                $all_paid_jobs                                          = $this->job_details->get_all_paid_jobs($career_site_company_sid);
                $paid_jobs                                              = array();
                $featured_jobs                                          = array();


                if (!empty($all_paid_jobs)) {
                    //
                    $paid_jobs = array_column($all_paid_jobs, 'jobId');
                }
            }

            $d3 = $this->db
                ->select('sid')
                ->from('automotive_groups')
                ->where('corporate_company_sid', $company_sid)
                ->limit(1)
                ->get();

            $automotive_group_sid = $d3->row_array();

            // Check for automotive group companies
            if ($automotive_group_sid) {
                $automotive_group_sid = $automotive_group_sid['sid'];
                $d3 = $this->db
                    ->select('
                    automotive_group_companies.company_sid
                ')
                    ->from('automotive_group_companies')
                    ->join('users', 'users.sid = automotive_group_companies.company_sid', 'left')
                    ->where('automotive_group_companies.automotive_group_sid', $automotive_group_sid)
                    ->where('automotive_group_companies.company_sid <> 0', null)
                    ->get();
                $automotive_group_companies = $d3->result_array();
                if ($automotive_group_companies) {
                    $companyIds = array_column($automotive_group_companies, 'company_sid');
                }
            }

            // Get company active jobs 
            $jb = $this->job_details->GetActiveJobsCatCSC($companyIds);
            // Get all categories
            $all_categories = $this->job_details->GetAllCategories($jb['categoryIds']);
            // Get all states
            $GetStatesWithCountries = $this->job_details->GetStatesWithCountries($jb['stateIds']);
            //
            $all_states = $GetStatesWithCountries['States'];
            // Get all countries
            $all_countries = [];
            //
            if (in_array(38, $jb['countryIds'])) {
                $all_countries[38] = ['sid' => 38, 'country_code' => 'CA', 'country_name' => 'Canada'];
            }
            //
            if (in_array(227, $jb['countryIds'])) {
                $all_countries[227] = ['sid' => 227, 'country_code' => 'US', 'country_name' => 'United States'];
            }

            if ($theme_name == 'theme-4' && strtoupper($pageName) == 'JOBS' && !empty($segment6)) { // if search is applied
                if ($data['customize_career_site']['status'] == 1) {
                    $list                                               = $this->job_details->get_all_company_jobs_ams($paid_jobs, $country, $state, $city, $categoryId, $keyword, $career_site_company_sid, $per_page, $offset);
                    $list_count                                         = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset, true);
                } else {
                    $list = $this->job_details->fetch_company_jobs_new($data['employer_id'], null, true, null, array(
                        'country' => $country,
                        'state' => $state,
                        'city' => $city,
                        'categoryId' => $categoryId,
                        'keyword' => $keyword
                    ));
                }


                // $list = $this->job_details->fetch_all_active_jobs_filtered($data['employer_id'], $country, $state, $city, $categoryId, $keyword);
            } else if ($theme_name == 'theme-4' && strtoupper($pageName) == 'JOBS') {
                if ($data['customize_career_site']['status'] == 1) {
                    $list                                               = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset);
                    $list_count                                         = $this->job_details->get_all_company_jobs_ams($paid_jobs, NULL, NULL, NULL, NULL, NULL, $career_site_company_sid, $per_page, $offset, true);
                } else {
                    $list = $this->job_details->fetch_company_jobs_new($data['employer_id']);
                }
            } else if ($theme_name != 'theme-4') {
                $list = $this->job_details->fetch_company_jobs_new($data['employer_id']);
            }
            //            
            if (($theme_name == 'theme-4' && strtoupper($pageName) == 'JOBS') || $theme_name == 'theme-3' || $theme_name == 'theme-2' || $theme_name == 'theme-1') {
                if ($data['customize_career_site']['status'] == 1) {
                    $all_active_jobs = $this->job_details->filters_of_active_jobs_of_companies($career_site_company_sid);
                } else {
                    $all_active_jobs = $this->job_details->filters_of_active_jobs($data['employer_id'], $job_approval_module_status);
                }


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
                                $country_states_array[$country_id][] = array('sid' => $state_id, 'state_name' => $state_name[0]['state_name']);
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
            }

            //
            $states_array = $all_states;
            $categories_in_active_jobs = $all_categories;
            $countries_array = $all_countries;
            $country_states_array = $GetStatesWithCountries['CountryWithStates'];

            if (!empty($list)) {
                //
                $storeIds = array_unique(array_column($list, 'user_sid'));
                // Get thier subdomains
                $data['storeData'] = $storeData = $this->job_details->getStoreData($storeIds);
                //
                $screeningQuestionaires = array_unique(
                    array_column($list, 'questionnaire_sid')
                );
                //
                $data['screeningQuestionaires'] = $screeningQuestionaires = $this->job_details->getScreeningQuestionares($screeningQuestionaires);
                //
                foreach ($list as $key => $value) {
                    $country_id = $value['Location_Country'];

                    if (!empty($country_id)) { // get country name

                        if ($country_id == 38) {
                            $list[$key]['Location_Country'] = 'Canada';
                        }

                        if ($country_id == 227) {
                            $list[$key]['Location_Country'] = 'United States';
                        }
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
                        //
                        if (isset($screeningQuestionaires[$questionnaire_sid])) {
                            $list[$key]['q_name'] = $screeningQuestionaires[$questionnaire_sid][0]['name'];
                            $list[$key]['q_passing'] = $screeningQuestionaires[$questionnaire_sid][0]['passing_score'];
                            $list[$key]['q_send_pass'] = $screeningQuestionaires[$questionnaire_sid][0]['auto_reply_pass'];
                            $list[$key]['q_send_fail'] = $screeningQuestionaires[$questionnaire_sid][0]['auto_reply_fail'];
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

                        if (isset($screeningQuestionaires[$questionnaire_sid]) && $screeningQuestionaires[$questionnaire_sid]['questions_count'] > 0) {
                            //
                            $screening_questions = $screeningQuestionaires[$questionnaire_sid]['questions'];
                            $screeningAnswers = [];
                            //
                            if ($screening_questions) {
                                $screeningQuestionIds = array_keys($screening_questions);
                                $screeningAnswers = $this->job_details->getScreeningAnswers($screeningQuestionIds);
                            }

                            foreach ($screening_questions as $qkey => $qvalue) {
                                $questions_sid = $qvalue['sid'];
                                $list[$key]['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);

                                //
                                if (isset($screeningAnswers[$questions_sid])) {

                                    foreach ($screeningAnswers[$questions_sid] as $akey => $avalue) {
                                        $list[$key]['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                    }
                                }
                            }
                        }
                    }

                    $company_id = $value['user_sid']; //Making job title start
                    $list[$key]['TitleOnly'] = $list[$key]['Title'];
                    $list[$key]['url'] = base_url(job_title_uri($list[$key]));
                    if ($data['customize_career_site']['status'] == 1) {
                        $list[$key]['Title'] = prepare_job_title($list[$key]['Title'], $list[$key]['Location_City'], $list[$key]['Location_State'], $list[$key]['Location_Country']);
                    } else {
                        //
                        $list[$key]['Title'] = $storeData[$company_id]['job_title_location'] == 1 ? $list[$key]['Title'] . ' - ' . $list[$key]['Location_City'] . ', ' . $list[$key]['Location_State'] . ', ' . $list[$key]['Location_Country'] : $list[$key]['Title'];
                    }
                    //Generate Share Links - start
                    $company_subdomain_url = STORE_PROTOCOL_SSL . $storeData[$company_id]['sub_domain'];
                    $portal_job_url = $company_subdomain_url . '/job_details/' . $list[$key]['sid'];
                    $fb_google_share_url = str_replace(':', '%3A', $portal_job_url);
                    $btn_facebook = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                    $btn_twitter = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list[$key]['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                    // $btn_google = '<a target="_blank" href="https://plusone.google.com/_/+1/confirm?hl=en&amp;url=' . $fb_google_share_url . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                    $btn_linkedin = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&source=AutomtoHR"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-4.png"></a>';
                    $btn_job_link = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list[$key]['Title']) . '</a>';
                    $btn_tell_a_friend = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';

                    $links = '';
                    $links .= '<ul>';
                    // $links .= '<li>' . $btn_google . '</li>';
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

            function array_sort_state_name($a, $b)
            {
                return strnatcmp($a['state_name'], $b['state_name']);
            }

            if (isset($country_states_array['227'])) {
                $us_states = $country_states_array['227'];
                usort($us_states, 'array_sort_state_name'); // sort alphabetically by name
                $country_states_array['227'] = $us_states;
            }

            if (isset($country_states_array['38'])) {
                $ca_states = $country_states_array['38'];
                usort($ca_states, 'array_sort_state_name'); // sort alphabetically by name
                $country_states_array['38'] = $ca_states;
            }

            $data_states_encode = htmlentities(json_encode($country_states_array));
            $data['active_countries'] = $countries_array;
            $data['active_states'] = $country_states_array;
            $data['states'] = $data_states_encode;
            $data['job_listings'] = $list;
            $data['total_calls'] = ceil($list_count) / $per_page;
            if (!empty($ajax_flag) && $ajax_flag) {
                // print_r(json_encode($data['featured_jobs']));
                print_r(json_encode($data['job_listings']));
                die();
            }
            $data['formpost'] = array();
            $data['dealership_website'] = '';
            $website = $data['company_details']['WebSite'];

            if (!empty($website)) {
                $data['dealership_website'] = $website;
            }

            $company_id = $data['company_details']['sid'];
            $data['isPaid'] = $isPaid;
            $counter = 0;

            if ($theme_name == 'theme-4') {
                $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($data['company_details']['sid']);
                $data['pages'] = $pages;

                if (!empty($data['pages'])) {
                    foreach ($data['pages'] as $page) {
                        $data['pages'][$counter]['page_title'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $data['pages'][$counter]['page_title']);
                        $counter++;
                    }
                }

                $pageName = $this->uri->segment(1);
                $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
                $data['jobs_page_title'] = $jobs_page_title;
                $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));

                if ($pageName == strtolower(str_replace(' ', '_', $jobs_page_title))) {
                    $pageName = 'jobs';
                }

                if ($pageName == '') {
                    $pageName = 'home';
                }

                $data['pageName'] = $pageName;
                $data['site_settings'] = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'site_settings', 'site_settings');

                switch (strtoupper($pageName)) {
                    case 'HOME':
                        $section_01_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_01');
                        $section_02_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_02');
                        $section_03_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_03');
                        $section_04_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_04');
                        $section_05_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_05');
                        $section_06_meta                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'section_06');
                        $additional_sections                                    = $this->theme_meta_model->getAdditionalSections($company_id);
                        $partners                                               = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, $pageName, 'partners');
                        $testimonials                                           = $this->testimonials_model->GetAllActive($company_id);
                        $footer_content                                         = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                        $footer_content['title']                                = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['title']);
                        $footer_content['content']                              = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['content']);
                        $data['partners']                                       = $partners;
                        $data['testimonials']                                   = $testimonials;
                        $data['footer_content']                                 = $footer_content;
                        // replacing {{company_name}} with company name from session array for 6 sections
                        $section_01_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_01_meta['title']);
                        $section_01_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_01_meta['content']);
                        $section_02_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_02_meta['title']);
                        $section_02_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_02_meta['content']);
                        $section_03_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_03_meta['title']);
                        $section_03_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_03_meta['content']);
                        $section_04_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_04_meta['title']);
                        $section_04_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_04_meta['content']);
                        $section_05_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_05_meta['title']);
                        $section_05_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_05_meta['content']);
                        $section_06_meta['title']                               = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_06_meta['title']);
                        $section_06_meta['content']                             = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $section_06_meta['content']);
                        $counter                                                = 0;

                        //Handle column_type for theme 4 home page section 2 and 3
                        if (!isset($section_02_meta['column_type'])) {
                            $section_02_meta['column_type']                     = 'right_left';
                        }

                        if (!isset($section_03_meta['column_type'])) {
                            $section_03_meta['column_type']                     = 'left_right';
                        }

                        $data['section_01_meta']                                = $section_01_meta;
                        $data['section_02_meta']                                = $section_02_meta;
                        $data['section_03_meta']                                = $section_03_meta;
                        $data['section_04_meta']                                = $section_04_meta;
                        $data['section_05_meta']                                = $section_05_meta;
                        $data['section_06_meta']                                = $section_06_meta;
                        $data['additional_sections']                            = $additional_sections;

                        foreach ($data['testimonials'] as $testimonial) {
                            $data['testimonials'][$counter]['author_name']      = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $data['testimonials'][$counter]['author_name']);
                            $data['testimonials'][$counter]['short_description'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $data['testimonials'][$counter]['short_description']);
                            $data['testimonials'][$counter]['full_description'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $data['testimonials'][$counter]['full_description']);
                            $counter++;
                        }
                        $this->load->view($theme_name . '/_parts/header_view', $data);
                        $this->load->view($theme_name . '/_parts/page_banner');
                        $this->load->view($theme_name . '/index_view');
                        $this->load->view($theme_name . '/_parts/footer_view');
                        break;
                    case 'JOBS':
                        asort($categories_in_active_jobs);
                        $data['job_categories']                                 = array();
                        $data['categories_in_active_jobs']                      = $categories_in_active_jobs;
                        $jobsPageBannerImage                                    = $this->theme_meta_model->fGetThemeMetaData($company_id, 'theme-4', 'jobs', 'jobs_page_banner');
                        $data['jobs_page_banner']                               = $jobsPageBannerImage;
                        $jobs_page_title                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                        $jobs_page_title                                        = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
                        $data['jobs_page_title']                                = strtolower(str_replace(' ', '_', $jobs_page_title));
                        $footer_content                                         = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                        $footer_content['title']                                = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['title']);
                        $footer_content['content']                              = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['content']);
                        $data['footer_content']                                 = $footer_content;
                        $this->load->view($theme_name . '/_parts/header_view', $data);
                        $this->load->view($theme_name . '/_parts/page_banner');
                        $this->load->view($theme_name . '/_parts/jobs_list_view');
                        $this->load->view($theme_name . '/_parts/footer_view');
                        break;
                    case 'TESTIMONIAL':
                        $testimonialId = $this->uri->segment(2);
                        $nextTestimonialId = $this->testimonials_model->GetNextTestimonialId($company_id, $testimonialId);
                        $previousTestimonialId = $this->testimonials_model->GetPreviousTestimonialId($company_id, $testimonialId);
                        $testimonialData = $this->testimonials_model->GetTestimonial($testimonialId);
                        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
                        $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));;
                        $testimonialData['author_name'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $testimonialData['author_name']);
                        $testimonialData['short_description'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $testimonialData['short_description']);
                        $testimonialData['full_description'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $testimonialData['full_description']);
                        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                        $footer_content['title'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['title']);
                        $footer_content['content'] = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['content']);
                        $data['footer_content'] = $footer_content;
                        //$data['pageName'] = 'testimonial';
                        $data['testimonial'] = $testimonialData;
                        $data['next_testimonial'] = $nextTestimonialId;
                        $data['prev_testimonial'] = $previousTestimonialId;
                        $this->load->view($theme_name . '/_parts/header_view', $data);
                        //$this->load->view($theme_name . '/_parts/page_banner');
                        $this->load->view($theme_name . '/_parts/testimonial');
                        $this->load->view($theme_name . '/_parts/footer_view');
                        break;
                    default:
                        $pageData = $this->themes_pages_model->GetPage($company_id, $pageName);

                        if (!empty($pageData)) {
                            if ($pageData['page_status'] == 1) {
                                $pageData['page_title']                         = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $pageData['page_title']);
                                $pageData['page_content']                       = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $pageData['page_content']);
                                $footer_content                                 = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                                $footer_content['title']                        = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['title']);
                                $footer_content['content']                      = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['content']);
                                $data['footer_content']                         = $footer_content;
                                $jobs_page_title                                = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
                                $jobs_page_title                                = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
                                $data['jobs_page_title']                        = strtolower(str_replace(' ', '_', $jobs_page_title));;
                                $data['pageData']                               = $pageData;

                                if ($pageData['job_fair'] == 1) {
                                    if ($pageData['job_fair_page_url'] == '' || $pageData['job_fair_page_url'] == NULL) {
                                        $job_fair_custom_forms = $this->contact_model->fetch_job_fair_forms($company_id);

                                        if (!empty($job_fair_custom_forms)) {
                                            $themes_pages_id = $pageData['sid'];
                                            $page_url = $job_fair_custom_forms[0]['page_url'];
                                            $pageData['job_fair_page_url'] = $page_url;
                                            $this->contact_model->update_fair_page_url($themes_pages_id, $page_url);
                                            $data['pageData'] = $pageData;
                                        } else {
                                            $pageData['job_fair'] = 0;
                                        }
                                    }
                                }

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

    public function contact_us()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $company_sid = $data['company_details']['sid'];
        $data['customize_career_site'] = $this->themes_pages_model->getCustomizeCareerSiteData($company_sid);
        company_phone_regex_module_check($company_sid, $data, $this);


        $theme_name = $data['theme_name'];
        $data['site_settings'] = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'site_settings', 'site_settings');
        $data['dealership_website'] = '';
        $data['pageName'] = 'contact_us';
        $data['isPaid'] = $data['is_paid'];
        $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($data['company_details']['sid']); //Pages Information
        $data['pages'] = $pages;
        $about_us_text = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'home', 'about-us'); //About Us Information
        $data['about_us'] = $about_us_text;
        $footer_content = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'home', 'footer_content');
        $data['footer_content'] = $footer_content;
        $website = $data['company_details']['WebSite'];
        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
        $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));

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
                    $data['company_details'] = $this->job_details->get_company_details($data['company_details']['sid']);
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

    public function join_our_talent_network()
    {
        $server_name = clean_domain($_SERVER['SERVER_NAME']);
        $data = $this->check_domain->check_portal_status($server_name);
        $company_sid = $data['company_details']['sid'];
        $data['customize_career_site'] = $this->themes_pages_model->getCustomizeCareerSiteData($company_sid);
        company_phone_regex_module_check($company_sid, $data, $this);
        $theme_name = $data['theme_name'];
        $data['site_settings'] = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'site_settings', 'site_settings');
        $data['dealership_website'] = '';
        $website = $data['company_details']['WebSite'];
        $company_id = $data['company_details']['sid'];
        $company_name = $data['company_details']['CompanyName'];
        $footer_content = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
        $data['footer_content'] = $footer_content;
        $company_email_templates = $data['company_email_templates'];
        $talent_acknowledgement_letter = array();
        $enable_auto_responder_email = 0;
        $talent_data = $this->contact_model->get_talent_config($company_id);
        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
        $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));

        if (isset($talent_data[0])) {
            $data['talent_data'] = $talent_data[0];
        }

        if (!empty($company_email_templates)) {
            foreach ($company_email_templates as $key => $email_templates) {
                if ($email_templates['template_code'] == 'talent_acknowledgement_letter') {
                    $talent_acknowledgement_letter = $email_templates;
                    $enable_auto_responder_email = $email_templates['enable_auto_responder'];
                }
            }
        }

        $data['talent_acknowledgement_letter'] = $talent_acknowledgement_letter;
        $data['enable_auto_responder_email'] = $enable_auto_responder_email;

        if (!empty($website)) {
            $data['dealership_website'] = $website;
        }
        $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();
        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) { //Paid Theme Related Info
            $data['pageName'] = 'join_our_talent_network';
            $data['isPaid'] = $data['is_paid'];

            if ($data['isPaid'] == 1) { //Get Pages If Theme is Paid.
                $pages = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id);
                $data['pages'] = $pages;
                $about_us_text = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'about-us');
                $data['about_us'] = $about_us_text;
            }

            $data['heading_title'] = 'Why Join Our Talent Network?';
            $data['talent_details'] = '<div class="text-column">
                                            <div class="text-block">
                                                <h2>Why Join Our Talent Network? </h2>
                                                <p>Joining our Talent Network will enhance your job search and application process.  Whether you choose to apply or just leave your information, we look forward to staying connected with you. </p>
                                                <ul>
                                                    <li>Receive alerts with new job opportunities that match your interests</li>
                                                    <li>Receive relevant communications and updates from our organization</li>
                                                    <li>Share job opportunities with family and friends through Social Media or email</li>
                                                </ul>
                                            </div>
                                        </div>';

            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|required');
            $this->form_validation->set_rules('state', 'State', 'trim|required');
            $this->form_validation->set_rules('city', 'City', 'trim|required');
            $this->form_validation->set_rules('desired_job_title', 'Desire Job', 'trim|required');
            $this->form_validation->set_rules('interest_level', 'Interest Level', 'required');
            $this->form_validation->set_rules('job_interest_text', 'Job Intrest', 'required');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim');
            $data_countries = $this->check_domain->get_active_countries(); //get all active `countries`

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = $this->check_domain->get_active_states($value['sid']); //get all active `states`
            }

            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = htmlentities(json_encode($data_states));
            $data['formpost'] = $_POST;

            if ($this->form_validation->run() === FALSE) {
                $data['meta_title'] = $data['meta_title'];
                $data['meta_description'] = $data['meta_description'];
                $data['meta_keywords'] = $data['meta_keywords'];
                $data['embedded_code'] = $data['embedded_code'];
                // die($theme_name);
                $this->load->view($theme_name . '/_parts/header_view', $data);
                $this->load->view($theme_name . '/join_network_view');
                $this->load->view($theme_name . '/_parts/footer_view');
            } else {
                $applied_by = '';
                $formpost = $this->input->post(NULL, TRUE);
                //
                if (!isset($formpost['g-recaptcha-response']) || empty($formpost['g-recaptcha-response'])) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                    if ($this->input->post('dr', true)) {
                        echo "Google captcha not set";
                        exit();
                    }
                    return redirect('join_our_talent_network', 'refresh');
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
                    return redirect('join_our_talent_network', 'refresh');
                }


                $status = $this->job_details->update_applicant_status_sid($data['company_details']['sid']); // get the statuses first for current company
                $email = $this->input->post('email');
                $first_name = $this->input->post('first_name');
                $last_name = $this->input->post('last_name');
                $country = $this->input->post('country');
                $state = $this->input->post('state');
                $city = $this->input->post('city');
                $phone_number = $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $this->input->post('phone_number', true);
                $desired_job_title = $this->input->post('desired_job_title');
                $interest_level = $this->input->post('interest_level');
                $job_interest_text = $this->input->post('job_interest_text');
                $resume = '';
                $fileData = 'Interest Level' . PHP_EOL;
                $fileData .= $interest_level . PHP_EOL;
                $fileData .= PHP_EOL . PHP_EOL . PHP_EOL . "Jobs You Are Interested In?" . PHP_EOL . $job_interest_text;

                $talent_and_fair_data['Desired Job Title']                      = $desired_job_title;
                $talent_and_fair_data['Interest Level']                         = $interest_level;
                $talent_and_fair_data['Jobs You Are Interested In?']            = $job_interest_text;

                $talent_data_to_store                                           = array(
                    'title' => 'Join Our Talent Network',
                    'questions' => $talent_and_fair_data
                );

                $serialize_talent_and_fair_data                                 = serialize($talent_data_to_store);
                $cover_letter                                                   = '';

                if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading resume to AWS
                    $resume_file = explode(".", $_FILES["resume"]["name"]);
                    $resume_name = 'applicant-' . $first_name . '-' . $last_name;
                    $resume = $resume_name . '-' . generateRandomString(3) . '.' . $resume_file[1];
                    $aws = new AwsSdk();
                    $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                }
                //
                if (check_company_status($data['company_details']['sid']) == 0) {
                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                    if ($this->input->post('dr', true)) {
                        echo "Job application success";
                        exit();
                    }
                    redirect('/', 'refresh');
                }
                // check if email is blocked
                if (checkForBlockedEmail($email) == 'blocked') {
                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your interest in our Talent Network, we will contact you soon.');
                    return redirect('/join_our_talent_network', 'refresh');
                }


                $talent_network_sid = $this->job_details->check_job_applicant('company_check', $email, $data['company_details']['sid']);
                $job_added_successfully = 0;
                $date_applied = date('Y-m-d H:i:s');
                // echo $talent_network_sid. "  = ".$resume; exit;
                $applied_by = "";
                if ($talent_network_sid == 'no_record_found') { // new entry in job applications table
                    $output = $this->contact_model->talent_network_applicant($email, $first_name, $last_name, $country, $city, $phone_number, $desired_job_title, $resume, $data, $cover_letter, $state, $date_applied);

                    if ($output[1] == 1) { // data inserted successfully
                        //
                        send_full_employment_application($data['company_details']['sid'], $output[0], "applicant");
                        //
                        $applicant_network_sid = $output[0];
                        $insert_data = array();
                        $insert_data['portal_job_applications_sid'] = $applicant_network_sid;
                        $insert_data['company_sid'] = $data['company_details']['sid'];
                        $insert_data['job_sid'] = 0;
                        $insert_data['date_applied'] = $date_applied;
                        $insert_data['status'] = $status['status_name'];
                        $insert_data['status_sid'] = $status['status_sid'];
                        $insert_data['ip_address'] = getUserIP();
                        $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['applicant_type'] = 'Talent Network';
                        $insert_data['applicant_source'] = $_SERVER['HTTP_HOST'];
                        $insert_data['main_referral'] = $this->session->userdata('last_referral') ? $this->session->userdata('last_referral') : $_SERVER['HTTP_HOST'];
                        $insert_data['desired_job_title'] = $this->input->post('desired_job_title');
                        $insert_data['talent_and_fair_data'] = $serialize_talent_and_fair_data;
                        $insert_data['resume']               = $resume ? $resume : NULL;
                        $output = $this->job_details->add_applicant_job_details($insert_data);
                        //
                        $job_added_successfully = $output[1];
                        $applied_by = "?applied_by=" . $output[0];
                    } else {
                        $this->session->set_flashdata('message', '<b>Failed:</b>Could not send your Enquiry, Please try Again!');
                    }
                } else { // applicant has already applied somewhere else
                    $insert_data = array();
                    $insert_data['portal_job_applications_sid'] = $talent_network_sid;
                    $insert_data['company_sid'] = $data['company_details']['sid'];
                    $insert_data['job_sid'] = 0;
                    $insert_data['date_applied'] = $date_applied;
                    $insert_data['status'] = $status['status_name'];
                    $insert_data['status_sid'] = $status['status_sid'];
                    $insert_data['ip_address'] = getUserIP();
                    $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $insert_data['applicant_type'] = 'Talent Network';
                    $insert_data['applicant_source'] = $_SERVER['HTTP_HOST'];
                    $insert_data['desired_job_title'] = $this->input->post('desired_job_title');
                    $insert_data['talent_and_fair_data'] = $serialize_talent_and_fair_data;
                    $insert_data['resume']               = $resume ? $resume : NULL;
                    $output = $this->job_details->add_applicant_job_details($insert_data);
                    $job_added_successfully = $output[1];
                    $applied_by = "?applied_by=" . $output[0];
                }

                if ($job_added_successfully == 1) {
                    if ($enable_auto_responder_email) { // generate email data
                        $talent_email_body = $talent_acknowledgement_letter['message_body'];

                        if (!empty($talent_email_body)) {
                            $talent_email_body = str_replace('{{site_url}}', base_url(), $talent_email_body);
                            $talent_email_body = str_replace('{{date}}', month_date_year(date('Y-m-d')), $talent_email_body);
                            $talent_email_body = str_replace('{{firstname}}', $first_name, $talent_email_body);
                            $talent_email_body = str_replace('{{lastname}}', $last_name, $talent_email_body);
                            $talent_email_body = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $talent_email_body);
                            $talent_email_body = str_replace('{{job_title}}', $desired_job_title, $talent_email_body);
                            $talent_email_body = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $talent_email_body);
                            $talent_email_body = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $talent_email_body);
                        }

                        $from = REPLY_TO;
                        $subject = str_replace('{{company_name}}', $company_name, $talent_acknowledgement_letter['subject']);
                        $from_name = str_replace('{{company_name}}', $company_name, $talent_acknowledgement_letter['from_name']);
                        $body = $talent_email_body;
                        $message_data = array();
                        $message_data['to_id'] = $email;
                        $message_data['from_type'] = 'employer';
                        $message_data['to_type'] = 'admin';
                        $message_data['job_id'] = $output[0];
                        $message_data['contact_name'] = $first_name . ' ' . $last_name;
                        $message_data['users_type'] = 'applicant';
                        $message_data['subject'] = 'Talent Network Acknowledgement Letter';
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
                        $email_log_autoresponder['users_type'] = 'Talent Network';
                        $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                        $email_log_autoresponder['subject'] = $subject;
                        $email_log_autoresponder['message'] = $autoemailbody;
                        $email_log_autoresponder['job_or_employee_id'] = $output[0];
                        $save_email_log = $this->contact_model->save_email_log_autoresponder($email_log_autoresponder);
                    }

                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your interest in our Talent Network, we will contact you soon.');
                }
                //}
                if ($this->input->post('dr', true)) {
                    echo "Talent network redirect";
                    exit();
                }
                redirect('/join_our_talent_network' . $applied_by, 'refresh');
            }
        } else {
            redirect('/', 'refresh');
        }
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
        //
        $data['sms_module_status'] = $data['company_details']['sms_module_status'];
        $theme_name = $data['theme_name'];

        $company_sid = $data['company_details']['sid'];
        $data['site_settings'] = $this->theme_meta_model->fGetThemeMetaData($company_sid, $theme_name, 'site_settings', 'site_settings');
        $company_name = $data['company_details']['CompanyName'];
        $data['customize_career_site'] = $this->themes_pages_model->getCustomizeCareerSiteData($company_sid);
        $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();
        company_phone_regex_module_check($company_sid, $data, $this);
        // set it so it can used
        // later on
        $originalId = $sid;
        // check if not numeric
        // then get the original id from
        // feed table
        if (!is_numeric($sid)) {
            $sid = $this->job_details->fetch_job_id_from_random_key($sid);
        }

        // check and get the Indeed apply button
        $data["indeedApplyButtonDetails"] = $this
            ->job_details
            ->getIndeedApplyButtonDetails(
                $originalId,
                $data['company_details']['sid']
            );

        $jobs_page_title = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'jobs', 'jobs_page_title');
        $jobs_detail_page_title = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'jobs_detail', 'jobs_detail_page_banner');
        $jobs_page_title = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
        $data['jobs_page_title'] = strtolower(str_replace(' ', '_', $jobs_page_title));
        $data['jobs_detail_page_banner_data'] = $jobs_detail_page_title;

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {
            if ($sid != null && intval($sid) > 0) {
                $company_sid = $data['company_details']['sid'];
                $has_job_approval_rights = $data['company_details']['has_job_approval_rights'];
                if ($data['customize_career_site']['status'] == 1) {
                    $list                                                       = $this->job_details->fetch_company_jobs_details($sid, NULL);
                    $data['jobs_detail_page_banner_data'] = $this->theme_meta_model->fGetThemeMetaData($list['user_sid'], $theme_name, 'jobs_detail', 'jobs_detail_page_banner');
                } else {
                    if ($data['career_type'] == 'corporate_career_site') {
                        $list                                                       = $this->job_details->fetch_company_jobs_new(NULL, $sid, TRUE, 0, array(), $has_job_approval_rights);
                    } else {

                        $list                                                       = $this->job_details->fetch_company_jobs_new($company_sid, $sid, TRUE, 0, array(), $has_job_approval_rights);
                    }
                }

                if (empty($list)) {
                    $search_job = explode('-', $this->uri->segment(2));
                    $job_title = isset($search_job[0]) ?  $search_job[0] : '';
                    $job_city = isset($search_job[3]) ?  $search_job[3] : '';
                    //
                    $get_alt_job = $this->job_details->get_alternate_job_from_company($company_sid, $job_title, $job_city);
                    //
                    if (!empty($get_alt_job)) {
                        redirect(base_url(job_title_uri($get_alt_job)), 'refresh');
                    }
                    //

                }

                if (!empty($list)) {
                    $company_sid                                                = $list['user_sid'];
                    $company_email_templates                                    = $data['company_email_templates'];
                    $application_acknowledgement_letter                         = array();
                    $enable_auto_responder_email                                = 0;

                    if (!empty($company_email_templates)) {
                        foreach ($company_email_templates as $key => $email_templates) {
                            if ($email_templates['template_code'] == 'application_acknowledgement_letter') {
                                $application_acknowledgement_letter             = $email_templates;
                                $enable_auto_responder_email                    = $email_templates['enable_auto_responder'];
                            }
                        }
                    }

                    $data['application_acknowledgement_letter']                 = $application_acknowledgement_letter;
                    $data['enable_auto_responder_email']                        = $enable_auto_responder_email;
                    $data['pageName']                                           = 'job_details';
                    $data['isPaid']                                             = $data['is_paid'];
                    $data['dealership_website']                                 = '';
                    $pages                                                      = $this->themes_pages_model->GetAllPageNamesAndTitles($data['company_details']['sid']); //Pages Information
                    $data['pages']                                              = $pages;
                    $about_us_text                                              = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'home', 'about-us'); //About Us Information
                    $data['about_us']                                           = $about_us_text;
                    $footer_content                                             = $this->theme_meta_model->fGetThemeMetaData($data['company_details']['sid'], $theme_name, 'home', 'footer_content');
                    $footer_content['title']                                    = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['title']);
                    $footer_content['content']                                  = str_replace("{{company_name}}", $data['company_details']['CompanyName'], $footer_content['content']);
                    $data['footer_content']                                     = $footer_content;
                    $website                                                    = $data['company_details']['WebSite'];

                    if (!empty($website)) {
                        $data['dealership_website']                             = $website;
                    }

                    $data['job_details']                                        = array();
                    $user_ip                                                    = getUserIP(); // get user Ip and Increment the job based on his IP
                    $job_session                                                = 'job_' . $user_ip . '_' . $sid;
                    $job_increment_check                                        = array($job_session => 'true');

                    if (!$this->session->userdata($job_session)) {
                        $this->job_details->increment_job_views($sid); // increment job views and create session
                        $this->session->set_userdata($job_increment_check);
                    }

                    $country_id                                                 = $list['Location_Country'];

                    if (!empty($country_id)) {
                        $country_name                                           = $this->job_details->get_countryname_by_id($country_id); // get country name
                        $list['Location_Country']                               = $country_name[0]['country_name'];
                    }

                    $state_id                                                   = $list['Location_State'];

                    if (!empty($state_id) && $state_id != 'undefined') {
                        $state_name                                             = $this->job_details->get_statename_by_id($state_id); // get state name
                        $list['Location_State']                                 = $state_name[0]['state_name'];
                        $list['Location_Code']                                  = $state_name[0]['state_code'];
                    }

                    $JobCategorys                                               = $list['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id                                                 = explode(',', $JobCategorys);
                        $job_category_array                                     = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name                                       = $this->job_details->get_job_category_name_by_id($id);

                            if (!empty($job_cat_name)) {
                                $job_category_array[]                           = $job_cat_name[0]['value'];
                            }
                        }

                        $job_category                                           = implode(',', $job_category_array);
                        $list['JobCategory']                                    = $job_category;
                    }

                    $date                                                       = substr($list['activation_date'], 0, 10); // change date format at front-end
                    $date_array                                                 = explode('-', $date);
                    $list['activation_date']                                    = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
                    $questionnaire_sid                                          = $list['questionnaire_sid'];

                    if ($questionnaire_sid > 0) {
                        $portal_screening_questionnaires                        = $this->job_details->get_screening_questionnaire_by_id($questionnaire_sid);
                        $questionnaire_name                                     = $portal_screening_questionnaires[0]['name'];
                        $list['q_name']                                         = $portal_screening_questionnaires[0]['name'];
                        $list['q_passing']                                      = $portal_screening_questionnaires[0]['passing_score'];
                        $list['q_send_pass']                                    = $portal_screening_questionnaires[0]['auto_reply_pass'];
                        $list['q_send_fail']                                    = $portal_screening_questionnaires[0]['auto_reply_fail'];
                        $list['q_pass_text']                                    = ''; //$portal_screening_questionnaires[0]['email_text_pass'];
                        $list['q_fail_text']                                    = ''; //$portal_screening_questionnaires[0]['email_text_fail'];
                        $list['my_id']                                          = 'q_question_' . $questionnaire_sid;
                        $screening_questions_numrows                            = $this->job_details->get_screenings_count_by_id($questionnaire_sid);

                        if ($screening_questions_numrows > 0) {
                            $screening_questions                                = $this->job_details->get_screening_questions_by_id($questionnaire_sid);

                            foreach ($screening_questions as $qkey => $qvalue) {
                                $questions_sid                                  = $qvalue['sid'];
                                $list['q_question_' . $questionnaire_sid][]     = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                $screening_answers_numrows                      = $this->job_details->get_screening_answer_count_by_id($questions_sid);

                                if ($screening_answers_numrows) {
                                    $screening_answers                          = $this->job_details->get_screening_answers_by_id($questions_sid);

                                    foreach ($screening_answers as $akey => $avalue) {
                                        $list['q_answer_' . $questions_sid][]   = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                    }
                                }
                            }
                        }
                    }
                    $list['TitleOnly'] = $list['Title'];
                    $list['Title']                                              = db_get_job_title($company_sid, $list['Title'], $list['Location_City'], $list['Location_State'], $list['Location_Country']);
                    $data_countries                                             = $this->check_domain->get_active_countries(); //get all active `countries`

                    foreach ($data_countries as $value) {
                        $data_states[$value['sid']]                             = $this->check_domain->get_active_states($value['sid']); //get all active `states`
                    }

                    $data['active_countries']                                   = $data_countries;
                    $data['active_states']                                      = $data_states;
                    $data['formpost']                                           = array();
                    $data['states']                                             = htmlentities(json_encode($data_states));
                    $data['company_details']                                    = $this->job_details->get_company_details($list['user_sid']);
                    $data['next_job']                                           = '';
                    $data['prev_job']                                           = '';
                    $next_job_anchor                                            = '';
                    $prev_job_anchor                                            = '';
                    $next_job_id                                                = $this->job_details->next_job($list['sid'], $list['user_sid']);
                    $prev_job_id                                                = $this->job_details->previous_job($list['sid'], $list['user_sid']);

                    if (!empty($next_job_id)) {
                        $next_id                                                = $next_job_id['sid'];
                        $data['next_job']                                       = "job_details/$next_id";
                    }

                    if (!empty($prev_job_id)) {
                        $prev_id                                                = $prev_job_id['sid'];
                        $data['prev_job']                                       = "job_details/$prev_id";
                    } //next and previous job link ENDS

                    $company_subdomain_url                                      = STORE_PROTOCOL_SSL . db_get_sub_domain($company_sid); //Generate Share Links - start
                    $portal_job_url                                             = $company_subdomain_url . '/job_details/' . $list['sid'];
                    $fb_google_share_url                                        = str_replace(':', '%3A', $portal_job_url);
                    $btn_facebook                                               = '<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=' . $fb_google_share_url . '" target="_blank"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-2.png"></a>';
                    $btn_twitter                                                = '<a target="_blank" href="https://twitter.com/intent/tweet?text=' . urlencode($list['Title']) . '&amp;url=' . urlencode($portal_job_url) . '"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-3.png"></a>';
                    $btn_linkedin                                               = '<a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=' . urlencode($portal_job_url) . '&source=AutomtoHR"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-4.png"></a>';
                    $btn_job_link                                               = '<a target="_blank" href="' . $portal_job_url . '">' . ucwords($list['Title']) . '</a>';
                    $btn_tell_a_friend                                          = '<a class="tellafrien-popup" href="javascript:;" data-toggle="modal" data-target="#tellAFriendModal"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                    $links                                                      = '';
                    $links                                                      .= '<ul>';
                    $links                                                      .= '<li>' . $btn_facebook . '</li>';
                    $links                                                      .= '<li>' . $btn_linkedin . '</li>';
                    $links                                                      .= '<li>' . $btn_twitter . '</li>';

                    if ($theme_name == 'theme-4') {
                        $links                                                  .= '<li>' . $btn_tell_a_friend . '</li>';
                    }

                    $links                                                      .= '</ul>';
                    $list['share_links']                                        = $links; //Generate Share Links - end
                    $data['job_details']                                        = $list;

                    if ($list["salary"]) {


                        //
                        $salaryBreakDown = breakSalary(
                            $data["job_details"]["Salary"],
                            $data["job_details"]["SalaryType"],
                            true
                        );

                        //
                        if (strpos($salaryBreakDown["min"], "$") === false) {
                            $data["job_details"]["Salary"] = "$" . $salaryBreakDown["min"];
                            if ($salaryBreakDown["max"]) {
                                $data["job_details"]["Salary"] .= " - $" . $salaryBreakDown["max"];
                            }
                        }
                    }
                    if (empty($data['job_details']['pictures'])) {
                        $data['image']                                          = base_url('assets/theme-1/images/new_logo.JPG');
                    } else {
                        $data['image']                                          = AWS_S3_BUCKET_URL . $data['job_details']['pictures'];
                    }

                    $action                                                     = '';

                    if (isset($_POST['action'])) {
                        $action                                                 = $this->input->post('action');
                    } else if (isset($_POST['perform_action'])) {
                        $action                                                 = $this->input->post('perform_action');
                    }

                    switch ($action) { //Setting Validation Rules for Different Post Requests
                        case 'job_applicant':
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

                    $job_company_sid                                            = $data['company_details']['sid'];
                    $more_career_oppurtunatity                                  = db_get_sub_domain($job_company_sid);
                    $job_company_career_title                                   = $this->theme_meta_model->fGetThemeMetaData($job_company_sid, $theme_name, 'jobs', 'jobs_page_title');

                    if (empty($job_company_career_title)) {
                        $job_company_career_title                               = 'jobs';
                    }

                    $data['more_career_oppurtunatity']                          = 'https://' . $more_career_oppurtunatity . '/' . $job_company_career_title;
                    $data['is_preview']                                         = 'no';

                    if ($data['indeedApplyButtonDetails']) {
                        $data['job_details']['Title'] = $list['TitleOnly'];
                    }

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


                        // Reset phone number
                        $txt_phone_number = $this->input->post('phone_number', true);
                        if ($this->input->post('txt_phonenumber', true))
                            $txt_phone_number = $this->input->post('txt_phonenumber', true);
                        switch ($action) {
                            case 'job_applicant':
                                // $recaptcha_response = $this->input->post('g-recaptcha-response');
                                // $google_secret = $this->config->item('google_secret');

                                // $recaptcha = verifyCaptcha($google_secret, $recaptcha_response);

                                // if ($recaptcha == false) {
                                //     $this->session->set_flashdata('message', '<b>Error: </b>Sorry Google Recaptcha Failed.');
                                //     $applied_from                               = $this->input->post('applied_from');

                                //     if ($applied_from == 'job') {
                                //         redirect('/job_details/' . $sid, 'refresh');
                                //     } else if ($applied_from == 'jobs_list_view') {
                                //         redirect('/jobs/');
                                //     } else {
                                //         redirect('/', 'refresh');
                                //     }

                                //     break;
                                // } 



                                $redirecturl = "";
                                $applied_from  = $this->input->post('applied_from');

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

                                $this->checkUserAppliedForJob($company_sid);
                                $job_sid                                        = $this->input->post('job_sid');
                                $first_name                                     = $this->input->post('first_name');
                                $last_name                                      = $this->input->post('last_name');
                                $YouTube_Video                                  = $this->input->post('YouTube_Video');
                                $email                                          = $this->input->post('email');
                                $is_blocked_email                               = checkForBlockedEmail($email);

                                if ($is_blocked_email == 'blocked') {
                                    $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                    $applied_from                               = $this->input->post('applied_from');
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

                                $phone_number                                   = $txt_phone_number;
                                $address                                        = $this->input->post('address');
                                $city                                           = $this->input->post('city');
                                $state                                          = $this->input->post('state');
                                $country                                        = $this->input->post('country');
                                $referred_by_name                               = $this->input->post('referred_by_name');
                                $referred_by_email                              = $this->input->post('referred_by_email');
                                $YouTube_code                                   = '';
                                $vType                                          = 'no_video';
                                $resume                                         = '';
                                $pictures                                       = '';
                                $cover_letter                                   = '';
                                $eeo_form                                       = 'No';
                                $job_details                                    = $this->job_details->fetch_jobs_details($job_sid);
                                $original_job_title                             = $job_details['Title'];

                                if ($this->input->post('EEO') != NULL) {
                                    $eeo_form                                   = $this->input->post('EEO');
                                }
                                //
                                if (check_company_status($company_sid) == 0) {
                                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                    if ($this->input->post('dr', true)) {
                                        echo "Job application success";
                                        exit();
                                    }
                                    redirect('/', 'refresh');
                                }
                                //
                                $already_applied                                = $this->job_details->check_job_applicant($job_sid, $email, $company_sid); //check if the user has already applied for this job
                                // if (in_array($company_sid, array("7", "51"))) {
                                if (!in_array($company_sid, array("0"))) {
                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from                               = $this->input->post('applied_from');
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
                                    } else { // fetch data and insert into database //echo 'New Applicant';

                                        if (!empty($YouTube_Video)) {
                                            $YouTube_code                               = substr($YouTube_Video, strpos($YouTube_Video, '=') + 1, strlen($YouTube_Video));
                                            $vType          = 'youtube';
                                        } elseif (!empty($_FILES) && isset($_FILES['uploaded_file']) && !empty($_FILES['uploaded_file']['name'])) {
                                            $document = $_FILES['uploaded_file']['name'];
                                            $ext = strtolower(pathinfo($document, PATHINFO_EXTENSION));

                                            if ($_FILES['uploaded_file']['size'] > 0) {
                                                if ($ext == "mp4" || $ext == "m4a" || $ext == "m4v" || $ext == "f4v" || $ext == "f4a" || $ext == "m4b" || $ext == "m4r" || $ext == "f4b" || $ext == "mov" || $ext == 'mp3') {
                                                    //error_reporting(E_ALL);
                                                    //ini_set('display_errors', '1');
                                                    $random = generateRandomString(5);
                                                    // $company_id = $company_id;
                                                    $target_file_name = basename($_FILES["uploaded_file"]["name"]);
                                                    $file_name = strtolower($company_sid . DIRECTORY_SEPARATOR . $random . '_' . $target_file_name);
                                                    $e = dirname(__FILE__) . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "uploaded_videos" . DIRECTORY_SEPARATOR;
                                                    $e2 = str_replace("manage_portal" . DIRECTORY_SEPARATOR . "application" . DIRECTORY_SEPARATOR . "controllers" . DIRECTORY_SEPARATOR, '', $e);
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
                                        $questionnaire_serialize                    = '';
                                        $total_score                                = 0;
                                        $total_questionnaire_score                  = 0;
                                        $q_passing                                  = 0;
                                        $array_questionnaire                        = array();
                                        $overall_status                             = 'Pass';
                                        $is_string                                  = 0;
                                        $screening_questionnaire_results            = array();
                                        $job_type                                   = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey                              = $_POST['unique_key'];
                                            $myUploadData                           = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume                             = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file                               = explode(".", $_FILES["resume"]["name"]);
                                                $resume_extension                   = $file[1];
                                                $resume_original_name               = $file[0];
                                                $file_name                          = str_replace(" ", "-", $file[0]);
                                                $resume                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws                                = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file                                   = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name                              = str_replace(" ", "-", $file[0]);
                                            $pictures                               = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws                                    = new AwsSdk();
                                            $aws->putToBucket($pictures, $_FILES['pictures']['tmp_name'], AWS_S3_BUCKET_NAME);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file                                   = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name                              = str_replace(" ", "-", $file[0]);
                                            $cover_letter                           = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws                                    = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid                               = $data['job_details']['user_sid'];
                                        $status_array                               = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Defult Status
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                            if ($this->input->post('dr', true)) {
                                                echo "Job application success";
                                                exit();
                                            }
                                            redirect('/', 'refresh');
                                        }
                                        //
                                        //
                                        $portal_job_applications_sid                = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully                     = 0;
                                        $date_applied                               = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid'          => $employer_sid,
                                                'first_name'            => $first_name,
                                                'last_name'             => $last_name,
                                                'YouTube_Video'         => $YouTube_code,
                                                'video_type'            => $vType,
                                                'email'                 => $email,
                                                'phone_number'          => $phone_number,
                                                'address'               => $address,
                                                'city'                  => $city,
                                                'state'                 => $state,
                                                'resume'                => $resume,
                                                'pictures'              => $pictures,
                                                'cover_letter'          => $cover_letter,
                                                'country'               => $country,
                                                'referred_by_name'      => $referred_by_name,
                                                'referred_by_email'     => $referred_by_email,
                                                'notified_by'                       => $this->input->post('contactPreference', true)
                                            );
                                            // echo "<pre>"; print_r($insert_data_primary); exit;
                                            $output                                 = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid               = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid'   => $job_applications_sid,
                                                    'company_sid'                   => $employer_sid,
                                                    'job_sid'                       => $job_sid,
                                                    'date_applied'                  => $date_applied,
                                                    'status'                        => $status_array['status_name'],
                                                    'status_sid'                    => $status_array['status_sid'],
                                                    'questionnaire'                 => $questionnaire_serialize,
                                                    'score'                         => $total_score,
                                                    'passing_score'                 => $q_passing,
                                                    'applicant_source'              => $this->session->userdata('referral_details'),
                                                    'ip_address'                    => getUserIP(),
                                                    'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form'                      => $eeo_form,
                                                    'resume'                        => $resume,
                                                    'last_update'                   => date('Y-m-d')
                                                );

                                                $jobs_list_result                   = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid     = $jobs_list_result[0];
                                                $job_added_successfully             = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid                   = $portal_job_applications_sid;
                                            //
                                            $update_data_primary = array(
                                                'first_name'            => $first_name,
                                                'last_name'             => $last_name,
                                                'phone_number'          => $phone_number,
                                                'address'               => $address,
                                                'city'                  => $city,
                                                'state'                 => $state,
                                                'country'               => $country,
                                                'referred_by_name'      => $referred_by_name,
                                                'referred_by_email'     => $referred_by_email,
                                                'notified_by'                       => $this->input->post('contactPreference', true)
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube        = array('YouTube_Video' => $YouTube_code, 'video_type' => $vType);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume         = array('resume' => $resume);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures       = array('pictures' => $pictures);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter   = array('cover_letter' => $cover_letter);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }

                                            $job_detail = $this->job_details->get_applicant_detail($job_applications_sid);

                                            if (isset($job_detail) && !empty($job_detail['resume']) && !empty($update_data_primary['resume'])) {
                                                $resume_log_data                            = array();
                                                $resume_log_data['company_sid']             = $job_detail['employer_sid'];
                                                $resume_log_data['user_type']               = $job_detail['applicant_type'];
                                                $resume_log_data['user_sid']                = $job_detail['sid'];
                                                $resume_log_data['user_email']              = $job_detail['email'];
                                                $resume_log_data['requested_by']            = 0;
                                                $resume_log_data['requested_subject']       = 'NULL';
                                                $resume_log_data['requested_message']       = 'NULL';
                                                $resume_log_data['requested_ip_address']    =  getUserIP();
                                                $resume_log_data['requested_user_agent']    = $_SERVER['HTTP_USER_AGENT'];
                                                $resume_log_data['request_status']          = 3;
                                                $resume_log_data['is_respond']              = 1;
                                                $resume_log_data['resume_original_name']    = $resume_original_name ? $resume_original_name : NULL;
                                                $resume_log_data['resume_s3_name']          = $update_data_primary['resume'];
                                                $resume_log_data['resume_extension']        =  $resume_extension ? $resume_extension : NULL;
                                                $resume_log_data['old_resume_s3_name']      = $job_detail['resume'];
                                                $resume_log_data['response_date']           = date('Y-m-d H:i:s');
                                                $resume_log_data['requested_date']          = date('Y-m-d H:i:s');
                                                $resume_log_data['job_sid']                 = $job_detail['job_sid'];
                                                $resume_log_data['job_type']                = "job";

                                                $this->job_details->insert_resume_request_log($resume_log_data); // insert resume log data in resume_request_log table
                                            }

                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            $insert_job_list = array(
                                                'portal_job_applications_sid'   => $job_applications_sid,
                                                'company_sid'                   => $employer_sid,
                                                'job_sid'                       => $job_sid,
                                                'date_applied'                  => $date_applied,
                                                'status'                        => $status_array['status_name'],
                                                'status_sid'                    => $status_array['status_sid'],
                                                'questionnaire'                 => $questionnaire_serialize,
                                                'score'                         => $total_score,
                                                'passing_score'                 => $q_passing,
                                                'applicant_source'              => $this->session->userdata('referral_details'),
                                                'ip_address'                    => getUserIP(),
                                                'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form'                      => $eeo_form,
                                                'resume'                        => $update_data_primary['resume'],
                                                'last_update'                   => date('Y-m-d')
                                            );

                                            // echo "<pre>"; print_r($insert_job_list); exit;
                                            $jobs_list_result                       = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid         = $jobs_list_result[0];
                                            $job_added_successfully                 = $jobs_list_result[1];
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
                                            $company_id                             = $list['user_sid'];
                                            $company_name                           = $data['company_details']['CompanyName'];
                                            $company_email                          = FROM_EMAIL_INFO;
                                            $resume_url                             = '';
                                            $resume_anchor                          = '';
                                            $profile_anchor                         = '<a href="' . STORE_FULL_URL_SSL . 'applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) {
                                                $resume_url                         = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor                      = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title                                  = $data['job_details']['Title'];
                                            $replacement_array                      = array();
                                            $replacement_array['site_url']          = base_url();
                                            $replacement_array['date']              = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title']         = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number']      = $phone_number;
                                            $replacement_array['city']              = $city;
                                            $replacement_array['company_name']      = $company_name;
                                            $message_hf                             = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status                   = get_notifications_status($company_sid);
                                            $my_debug_message                       = json_encode($replacement_array);
                                            $applicant_notifications_status         = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status     = $notifications_status['new_applicant_notifications'];
                                            }

                                            $applicant_notification_contacts        = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts    = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname']     = $first_name;
                                                        $replacement_array['lastname']      = $last_name;
                                                        $replacement_array['email']         = $email;
                                                        $replacement_array['company_name']  = $company_name;
                                                        $replacement_array['resume_link']   = $resume_anchor;
                                                        $replacement_array['applicant_profile_link']   = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }
                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body         = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body     = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                // 56357
                                                $from                               = REPLY_TO;
                                                $subject                            = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name                          = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body                               = $acknowledgement_email_body;
                                                $message_data                       = array();
                                                $message_data['contact_name']       = $first_name . ' ' . $last_name;
                                                $message_data['to_id']              = $email;
                                                $message_data['from_type']          = 'employer';
                                                $message_data['to_type']            = 'admin';
                                                $message_data['job_id']             = $job_applications_sid;
                                                $message_data['users_type']         = 'applicant';
                                                $message_data['subject']            = 'Application Acknowledgement Letter';
                                                $message_data['message']            = $body;
                                                $message_data['date']               = date('Y-m-d H:i:s');
                                                $message_data['from_id']            = REPLY_TO;
                                                $message_data['identity_key']       = generateRandomString(48);
                                                $message_hf                         = message_header_footer_domain($company_id, $company_name);
                                                $secret_key                         = $message_data['identity_key'] . "__";
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
                                            if ($_POST['questionnaire_sid'] > 0) { // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid             = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires      = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire                = array();
                                                $q_name                             = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass                        = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text                        = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail                        = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text                        = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids                  = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing                  = 0;
                                                    $post_questions_sid         = $value;
                                                    $caption                    = 'caption' . $value;
                                                    $type                       = 'type' . $value;
                                                    $answer                     = $_POST[$type] . $value;
                                                    $questions_type             = $_POST[$type];
                                                    $my_question                = '';
                                                    $individual_score           = 0;
                                                    $individual_passing_score   = 0;
                                                    $individual_status          = 'Pass';
                                                    $result_status              = array();

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
                                                        } else {
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
                                                }

                                                $questionnaire_result               = $overall_status;
                                                $datetime                           = date('Y-m-d H:i:s');
                                                $remote_addr                        = getUserIP();
                                                $user_agent                         = $_SERVER['HTTP_USER_AGENT'];

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
                                                    $from                           = $company_email;
                                                    $fromname                       = $company_name;
                                                    $title                          = $data['job_details']['Title'];
                                                    $subject                        = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to                             = $email;

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

                                            if ($eeo_form == "Yes") {
                                                $eeo_data = array(
                                                    'application_sid'                   => $job_applications_sid,
                                                    'users_type'                        => "applicant",
                                                    'portal_applicant_jobs_list_sid'    => $portal_applicant_jobs_list_sid,
                                                    'us_citizen'                        => $this->input->post('us_citizen'),
                                                    'visa_status '                      => $this->input->post('visa_status'),
                                                    'group_status'                      => $this->input->post('group_status'),
                                                    'veteran'                           => $this->input->post('veteran'),
                                                    'disability'                        => $this->input->post('disability'),
                                                    'gender'                            => $this->input->post('gender'),
                                                    'is_expired'                            => 1
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
                                } else {
                                    if ($already_applied > 0) { // appliant has already applied for the job. He can't apply again.
                                        $this->session->set_flashdata('message', "<b>Error!</b> You have already applied for this Job '" . $data['job_details']['Title'] . "'");
                                        $applied_from                               = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Applied job form";
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
                                        $questionnaire_serialize                    = '';
                                        $total_score                                = 0;
                                        $total_questionnaire_score                  = 0;
                                        $q_passing                                  = 0;
                                        $array_questionnaire                        = array();
                                        $overall_status                             = 'Pass';
                                        $is_string                                  = 0;
                                        $screening_questionnaire_results            = array();
                                        $job_type                                   = '';

                                        if (isset($_POST['resume_from_google_drive']) && $_POST['resume_from_google_drive'] != '0' && $_POST['resume_from_google_drive'] != '') {
                                            $uniqueKey                              = $_POST['unique_key'];
                                            $myUploadData                           = $this->check_domain->GetSingleGoogleUploadByKey($uniqueKey);

                                            if (!empty($myUploadData)) {
                                                $resume                             = $myUploadData['aws_file_name'];
                                            }
                                        } else {
                                            if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') {
                                                $file                               = explode(".", $_FILES["resume"]["name"]);
                                                $resume_extension                   = $file[1];
                                                $resume_original_name               = $file[0];
                                                $file_name                          = str_replace(" ", "-", $file[0]);
                                                $resume                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                                $aws                                = new AwsSdk();
                                                $aws->putToBucket($resume, $_FILES["resume"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                            }
                                        }

                                        if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                                            $file                                   = explode(".", $_FILES["pictures"]["name"]);
                                            $file_name                              = str_replace(" ", "-", $file[0]);
                                            $pictures                               = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws                                    = new AwsSdk();
                                            $aws->putToBucket($pictures, $_FILES['pictures']['tmp_name'], AWS_S3_BUCKET_NAME);
                                        }

                                        if (isset($_FILES['cover_letter']) && $_FILES['cover_letter']['name'] != '') {
                                            $file                                   = explode(".", $_FILES["cover_letter"]["name"]);
                                            $file_name                              = str_replace(" ", "-", $file[0]);
                                            $cover_letter                           = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                                            $aws                                    = new AwsSdk();
                                            $aws->putToBucket($cover_letter, $_FILES["cover_letter"]["tmp_name"], AWS_S3_BUCKET_NAME);
                                        }

                                        $employer_sid                               = $data['job_details']['user_sid'];
                                        $status_array                               = $this->job_details->update_applicant_status_sid($employer_sid); // Get Applicant Defult Status
                                        //
                                        if (check_company_status($employer_sid) == 0) {
                                            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                                            if ($this->input->post('dr', true)) {
                                                echo "Job application sucess";
                                                exit();
                                            }
                                            redirect('/', 'refresh');
                                        }
                                        //
                                        $portal_job_applications_sid                = $this->job_details->check_job_applicant('company_check', $email, $employer_sid);
                                        $job_added_successfully                     = 0;
                                        $date_applied                               = date('Y-m-d H:i:s');

                                        if ($portal_job_applications_sid == 'no_record_found') { // Applicant has never applied for any job - Add new Entry
                                            $insert_data_primary = array(
                                                'employer_sid'          => $employer_sid,
                                                'first_name'            => $first_name,
                                                'last_name'             => $last_name,
                                                'YouTube_Video'         => $YouTube_code,
                                                'video_type'            => $vType,
                                                'email'                 => $email,
                                                'phone_number'          => $phone_number,
                                                'address'               => $address,
                                                'city'                  => $city,
                                                'state'                 => $state,
                                                'resume'                => $resume,
                                                'pictures'              => $pictures,
                                                'cover_letter'          => $cover_letter,
                                                'country'               => $country,
                                                'referred_by_name'      => $referred_by_name,
                                                'referred_by_email'     => $referred_by_email
                                            );
                                            // echo "<pre>"; print_r($insert_data_primary); exit;
                                            $output                                 = $this->job_details->apply_for_job($insert_data_primary);

                                            if ($output[1] == 1) { // data inserted successfully. Add job details to portal_applicant_jobs_list
                                                $job_applications_sid               = $output[0];
                                                //
                                                send_full_employment_application($employer_sid, $job_applications_sid, "applicant");
                                                //
                                                $insert_job_list = array(
                                                    'portal_job_applications_sid'   => $job_applications_sid,
                                                    'company_sid'                   => $employer_sid,
                                                    'job_sid'                       => $job_sid,
                                                    'date_applied'                  => $date_applied,
                                                    'status'                        => $status_array['status_name'],
                                                    'status_sid'                    => $status_array['status_sid'],
                                                    'questionnaire'                 => $questionnaire_serialize,
                                                    'score'                         => $total_score,
                                                    'passing_score'                 => $q_passing,
                                                    'applicant_source'              => $this->session->userdata('referral_details'),
                                                    'ip_address'                    => getUserIP(),
                                                    'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
                                                    'eeo_form'                      => $eeo_form
                                                );

                                                $jobs_list_result                   = $this->job_details->add_applicant_job_details($insert_job_list);
                                                $portal_applicant_jobs_list_sid     = $jobs_list_result[0];
                                                $job_added_successfully             = $jobs_list_result[1];
                                            }
                                        } else { // Applicant already applied in the company. Add this job against his profile
                                            $job_applications_sid                   = $portal_job_applications_sid;

                                            $update_data_primary = array(
                                                'first_name'            => $first_name,
                                                'last_name'             => $last_name,
                                                'phone_number'          => $phone_number,
                                                'address'               => $address,
                                                'city'                  => $city,
                                                'state'                 => $state,
                                                'country'               => $country,
                                                'referred_by_name'      => $referred_by_name,
                                                'referred_by_email'     => $referred_by_email
                                            );

                                            if ($YouTube_code != '') { // check if youtube link is updated
                                                $update_data_primary_youtube        = array('YouTube_Video' => $YouTube_code);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_youtube);
                                            }

                                            if ($resume != '') { // check if resume is updated
                                                $update_data_primary_resume         = array('resume' => $resume);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_resume);
                                            }

                                            if ($pictures != '') { // check if profile picture is updated
                                                $update_data_primary_pictures       = array('pictures' => $pictures);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_pictures);
                                            }

                                            if ($cover_letter != '') { // check if cover letter is updated
                                                $update_data_primary_cover_letter   = array('cover_letter' => $cover_letter);
                                                $update_data_primary                = array_merge($update_data_primary, $update_data_primary_cover_letter);
                                            }
                                            $job_detail = $this->job_details->get_applicant_detail($job_applications_sid);
                                            if (isset($job_detail) && !empty($job_detail['resume']) && !empty($update_data_primary['resume'])) {
                                                $resume_log_data                            = array();
                                                $resume_log_data['company_sid']             = $job_detail['employer_sid'];
                                                $resume_log_data['user_type']               = $job_detail['applicant_type'];
                                                $resume_log_data['user_sid']                = $job_detail['sid'];
                                                $resume_log_data['user_email']              = $job_detail['email'];
                                                $resume_log_data['requested_by']            = '';
                                                $resume_log_data['requested_subject']       = '';
                                                $resume_log_data['requested_message']       = '';
                                                $resume_log_data['requested_ip_address']    =  getUserIP();
                                                $resume_log_data['requested_user_agent']    = $_SERVER['HTTP_USER_AGENT'];
                                                $resume_log_data['request_status']          = 3;
                                                $resume_log_data['is_respond']              = 1;
                                                $resume_log_data['resume_original_name']    = $resume_original_name ? $resume_original_name : '';
                                                $resume_log_data['resume_s3_name']          = $update_data_primary['resume'];
                                                $resume_log_data['resume_extension']        =  $resume_extension ? $resume_extension : '';
                                                $resume_log_data['old_resume_s3_name']      = $job_detail['resume'];
                                                $resume_log_data['response_date']
                                                    = '';
                                                $this->job_details->insert_resume_request_log($resume_log_data); // insert resume log data in resume_request_log table
                                            }
                                            $this->job_details->update_applicant_applied_date($job_applications_sid, $update_data_primary); //update applicant primary data

                                            $insert_job_list = array(
                                                'portal_job_applications_sid'   => $job_applications_sid,
                                                'company_sid'                   => $employer_sid,
                                                'job_sid'                       => $job_sid,
                                                'date_applied'                  => $date_applied,
                                                'status'                        => $status_array['status_name'],
                                                'status_sid'                    => $status_array['status_sid'],
                                                'questionnaire'                 => $questionnaire_serialize,
                                                'score'                         => $total_score,
                                                'passing_score'                 => $q_passing,
                                                'applicant_source'              => $this->session->userdata('referral_details'),
                                                'ip_address'                    => getUserIP(),
                                                'user_agent'                    => $_SERVER['HTTP_USER_AGENT'],
                                                'eeo_form'                      => $eeo_form
                                            );

                                            $jobs_list_result                       = $this->job_details->add_applicant_job_details($insert_job_list);
                                            $portal_applicant_jobs_list_sid         = $jobs_list_result[0];
                                            $job_added_successfully                 = $jobs_list_result[1];
                                        }

                                        if ($job_added_successfully == 1) { // send confirmation emails to Primary admin
                                            $company_id                             = $list['user_sid'];
                                            $company_name                           = $data['company_details']['CompanyName'];
                                            $company_email                          = FROM_EMAIL_INFO;
                                            $resume_url                             = '';
                                            $resume_anchor                          = '';
                                            $profile_anchor                         = '<a href="' . STORE_FULL_URL_SSL . 'applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                                            if (!empty($resume)) {
                                                $resume_url                         = AWS_S3_BUCKET_URL . urlencode($resume);
                                                $resume_anchor                      = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                                            }

                                            $title                                  = $data['job_details']['Title'];
                                            $replacement_array                      = array();
                                            $replacement_array['site_url']          = base_url();
                                            $replacement_array['date']              = month_date_year(date('Y-m-d'));
                                            $replacement_array['job_title']         = $title;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            $replacement_array['phone_number']      = $phone_number;
                                            $replacement_array['city']              = $city;
                                            $replacement_array['company_name']      = $company_name;
                                            $message_hf                             = message_header_footer_domain($company_id, $company_name);
                                            $notifications_status                   = get_notifications_status($company_sid);
                                            $my_debug_message                       = json_encode($replacement_array);
                                            $applicant_notifications_status         = 0;

                                            if (!empty($notifications_status)) {
                                                $applicant_notifications_status     = $notifications_status['new_applicant_notifications'];
                                            }

                                            $applicant_notification_contacts        = array();

                                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                                $applicant_notification_contacts    = get_notification_email_contacts($company_sid, 'new_applicant', $sid);

                                                if (!empty($applicant_notification_contacts)) {
                                                    foreach ($applicant_notification_contacts as $contact) {
                                                        $replacement_array['firstname']     = $first_name;
                                                        $replacement_array['lastname']      = $last_name;
                                                        $replacement_array['email']         = $email;
                                                        $replacement_array['company_name']  = $company_name;
                                                        $replacement_array['resume_link']   = $resume_anchor;
                                                        $replacement_array['applicant_profile_link']   = $profile_anchor;
                                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                                    }
                                                }
                                            }
                                            // send email to applicant from portal email templates
                                            if ($enable_auto_responder_email) { // generate email data - Auto Responder acknowledgement email to applicant
                                                $acknowledgement_email_body         = $application_acknowledgement_letter['message_body'];

                                                if (!empty($acknowledgement_email_body)) {
                                                    $acknowledgement_email_body     = str_replace('{{site_url}}', base_url(), $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{date}}', month_date_year(date('Y-m-d')), $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{firstname}}', $first_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{lastname}}', $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{applicant_name}}', $first_name . ' ' . $last_name, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{job_title}}', $title, $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{phone_number}}', $data['company_details']['PhoneNumber'], $acknowledgement_email_body);
                                                    $acknowledgement_email_body     = str_replace('{{company_name}}', $data['company_details']['CompanyName'], $acknowledgement_email_body);
                                                }
                                                // 56357
                                                $from                               = REPLY_TO;
                                                $subject                            = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['subject']);
                                                $from_name                          = str_replace('{{company_name}}', $company_name, $application_acknowledgement_letter['from_name']);
                                                $body                               = $acknowledgement_email_body;
                                                $message_data                       = array();
                                                $message_data['contact_name']       = $first_name . ' ' . $last_name;
                                                $message_data['to_id']              = $email;
                                                $message_data['from_type']          = 'employer';
                                                $message_data['to_type']            = 'admin';
                                                $message_data['job_id']             = $job_applications_sid;
                                                $message_data['users_type']         = 'applicant';
                                                $message_data['subject']            = 'Application Acknowledgement Letter';
                                                $message_data['message']            = $body;
                                                $message_data['date']               = date('Y-m-d H:i:s');
                                                $message_data['from_id']            = REPLY_TO;
                                                $message_data['identity_key']       = generateRandomString(48);
                                                $message_hf                         = message_header_footer_domain($company_id, $company_name);
                                                $secret_key                         = $message_data['identity_key'] . "__";
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
                                            if ($_POST['questionnaire_sid'] > 0) { // Check if any questionnaire is attached with this job.
                                                $post_questionnaire_sid             = $_POST['questionnaire_sid'];
                                                $post_screening_questionnaires      = $this->job_details->get_screening_questionnaire_by_id($post_questionnaire_sid);
                                                $array_questionnaire                = array();
                                                $q_name                             = $post_screening_questionnaires[0]['name'];
                                                $q_send_pass                        = $post_screening_questionnaires[0]['auto_reply_pass'];
                                                $q_pass_text                        = $post_screening_questionnaires[0]['email_text_pass'];
                                                $q_send_fail                        = $post_screening_questionnaires[0]['auto_reply_fail'];
                                                $q_fail_text                        = $post_screening_questionnaires[0]['email_text_fail'];
                                                $all_questions_ids                  = $_POST['all_questions_ids'];

                                                foreach ($all_questions_ids as $key => $value) {
                                                    $q_passing                  = 0;
                                                    $post_questions_sid         = $value;
                                                    $caption                    = 'caption' . $value;
                                                    $type                       = 'type' . $value;
                                                    $answer                     = $_POST[$type] . $value;
                                                    $questions_type             = $_POST[$type];
                                                    $my_question                = '';
                                                    $individual_score           = 0;
                                                    $individual_passing_score   = 0;
                                                    $individual_status          = 'Pass';
                                                    $result_status              = array();

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
                                                        } else {
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
                                                }

                                                $questionnaire_result               = $overall_status;
                                                $datetime                           = date('Y-m-d H:i:s');
                                                $remote_addr                        = getUserIP();
                                                $user_agent                         = $_SERVER['HTTP_USER_AGENT'];

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
                                                    $from                           = $company_email;
                                                    $fromname                       = $company_name;
                                                    $title                          = $data['job_details']['Title'];
                                                    $subject                        = 'Job Application Questionnaire Status for "' . $title . '"';
                                                    $to                             = $email;

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

                                            if ($eeo_form == "Yes") {
                                                $eeo_data = array(
                                                    'application_sid'                   => $job_applications_sid,
                                                    'users_type'                        => "applicant",
                                                    'portal_applicant_jobs_list_sid'    => $portal_applicant_jobs_list_sid,
                                                    'us_citizen'                        => $this->input->post('us_citizen'),
                                                    'visa_status '                      => $this->input->post('visa_status'),
                                                    'group_status'                      => $this->input->post('group_status'),
                                                    'veteran'                           => $this->input->post('veteran'),
                                                    'disability'                        => $this->input->post('disability'),
                                                    'gender'                            => $this->input->post('gender'),
                                                    'is_expired'                            => 1
                                                );

                                                $this->job_details->save_eeo_form($eeo_data);
                                            } //Getting data for EEO Form Ends

                                            $this->session->set_flashdata('message', '<b>Success: </b>Job application added successfully.');
                                        }

                                        $applied_from = $this->input->post('applied_from');
                                        if ($this->input->post('dr', true)) {
                                            echo "Job applied form";
                                            exit();
                                        }

                                        if ($applied_from == 'job') {

                                            redirect('/job_details/' . $sid, 'refresh');
                                        } else if ($applied_from == 'jobs_list_view') {
                                            redirect('/jobs/', 'refresh');
                                        } else {
                                            redirect('/', 'refresh');
                                        }
                                    }
                                }

                                break;
                            case 'friendShare':
                                $sender_name                                    = $this->input->post('sender_name');
                                $receiver_name                                  = $this->input->post('receiver_name');
                                $receiver_email                                 = $this->input->post('receiver_email');
                                $sender_email                                   = $this->input->post('sender_email');
                                $comment                                        = $this->input->post('comment');
                                $is_sender_blocked                              = checkForBlockedEmail($sender_email);
                                $is_receiver_blocked                            = checkForBlockedEmail($receiver_email);

                                if ($is_sender_blocked == 'blocked' || $is_receiver_blocked == "blocked") {
                                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you.');

                                    redirect('/job_details/' . $sid, 'refresh');
                                    break;
                                }

                                $check_already_request                          = $this->job_details->check_if_applied_already($sid);

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

                                redirect('/job_details/' . $sid, 'refresh');
                                break;
                            case 'send_tell_a_friend_email':
                                sendMail('info@automotohr.com', 'dev@automotohr.com', 'send_tell_a_friend_email!', print_r($_POST, true) . print_r($_SERVER, true));
                                //  $senderName = $_POST['sender_name'];
                                //  $receiverName = $_POST['receiver_name'];
                                //   $receiverEmail = $_POST['receiver_email'];
                                //    $message = $_POST['message'];
                                //   $subject = ucwords($senderName) . ' Recommends ' . $list['Title'];
                                //   $message_body = '';
                                //   $message_body .= '<h1>' . 'Hi ' . $receiverName . '</h1>';
                                //   $message_body .= '<h3>' . $senderName . '</h3>';
                                //  $message_body .= '<p>' . 'Has Recommended The Following Job on our Website to You:' . '</p>';
                                //   $message_body .= '<p>' . '</p>';
                                //  $message_body .= '<p>' . '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" target="_blank" href="' . base_url('job_details') . '/' . $sid . '">' . $list['Title'] . '</a>' . '</p>';
                                //  $message_body .= '<p>' . '</p>';
                                //                                $message_body .= '<p>' . '</p>';
                                //                                $message_body .= '<p>' . '<strong>' . 'Attached Personal Message' . '</strong>' . '</p>';
                                //                                $message_body .= '<p>' . $message . '</p>';
                                //                                $message_body .= '<p>' . '</p>';
                                //
                                //                                $message_body .= FROM_INFO_EMAIL_DISCLAIMER_MSG;
                                //
                                //                                if (base_url() == 'http://ahr.example/') { //echo 'Local Working';
                                //                                    save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                //                                } else { //echo 'LIVE';
                                //                                    save_email_log_common($senderName, $receiverEmail, $subject, $message_body);
                                //                                    sendMail(FROM_EMAIL_INFO, $receiverEmail, $subject, $message_body);
                                //                                }
                                break;
                        }
                    }
                } else { //Job Id Is not 0 But Job Not Found
                    $this->session->set_flashdata('message', 'No Active job found!');
                    redirect('/', 'refresh');
                }
            } else { //Job Id Is 0 or Null
                $this->session->set_flashdata('message', 'No Active job found!');
                redirect('/', 'location');
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

    public function job_fair($key = NULL)
    {
        $server_name                                                            = clean_domain($_SERVER['SERVER_NAME']);
        $data                                                                   = $this->check_domain->check_portal_status($server_name);

        $job_fair                                                               = $data['job_fairs'];
        $company_sid                                                            = $data['company_details']['sid'];
        $theme_name                                                             = $data['theme_name'];

        company_phone_regex_module_check($company_sid, $data, $this);

        if (empty($job_fair)) {
            $this->session->set_flashdata('message', '<b>Error: </b>Page not found! Please contact AutomotoHR Technical support, if you think otherwise.');
            redirect('/', 'refresh');
        }
        // echo '<pre>'; print_r($data); die();
        if ($theme_name != 'theme-4' && ($data['job_fair_homepage_page_url'] != NULL || $data['job_fair_homepage_page_url'] != '')) {
            $key = $data['job_fair_homepage_page_url'];

            if ($data['theme4_enable_job_fair_homepage'] == 0) {
                $this->session->set_flashdata('message', '<b>Error: </b>Page not found!');
                redirect('/', 'refresh');
            }
        }

        $data['remarket_company_settings'] = $this->themes_pages_model->get_remarket_company_settings();

        if ($key == NULL && $theme_name == 'theme-4') {
            $job_fair_data                                                      = $this->contact_model->fetch_job_fair_forms($company_sid);

            if (empty($job_fair_data)) { // record not found in multiple forms table, check for the data in primary table
                $job_fair_data                                                  = $this->check_domain->get_job_fair_data($company_sid);
                $page_url_key                                                   = $job_fair_data['page_url'];
                $default_sid                                                    = $job_fair_data['sid'];

                if ($page_url_key == '' || $page_url_key == NULL) {
                    $key                                                        = md5('default_' . $default_sid);
                } else {
                    $key                                                        = $page_url_key;
                }
            } else { // record found
                $page_url_key                                                   = $job_fair_data[0]['page_url'];
                $custom_sid                                                     = $job_fair_data[0]['sid'];

                if ($page_url_key == '' || $page_url_key == NULL) {
                    $key                                                        = md5('custom_' . $custom_sid);
                } else {
                    $key                                                        = $page_url_key;
                }
            }

            if (empty($job_fair_data)) { // job is not enabled for the company.
                $this->session->set_flashdata('message', '<b>Error: </b>Page not found! Please contact AutomotoHR Technical support, if you think otherwise.');
                redirect('/', 'refresh');
            }
        }

        $active_fair_form                                                       = array();

        if (isset($job_fair[$key])) {
            $active_fair_form                                                   = $job_fair[$key];
        }

        if (empty($active_fair_form)) { // record not found.
            $this->session->set_flashdata('message', '<b>Error: </b>Page not found! Please contact AutomotoHR Technical support, if you think otherwise.');
            redirect('/', 'refresh');
        }

        $form_type                                                              = $active_fair_form['form_type'];
        $fair_details                                                           = $job_fair[$key];
        $fair_full_details                                                      = $this->contact_model->fetch_form_details_by_id($fair_details['sid'], $form_type);
        $redirect_page_url                                                      = $key;

        if (empty($fair_full_details)) { // record not found.
            $this->session->set_flashdata('message', '<b>Error: </b>Page not found! Please contact AutomotoHR Technical support, if you think otherwise.');
            redirect('/', 'refresh');
        } else {
            $fair_full_details = $fair_full_details[0];
        }

        $data['dealership_website']                                             = '';
        $website                                                                = $data['company_details']['WebSite'];
        $company_id                                                             = $data['company_details']['sid'];
        $company_name                                                           = $data['company_details']['CompanyName'];
        $footer_content                                                         = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
        $data['footer_content']                                                 = $footer_content;
        $company_email_templates                                                = $data['company_email_templates'];
        $jobs_page_title                                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title                                                        = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
        $data['jobs_page_title']                                                = strtolower(str_replace(' ', '_', $jobs_page_title));

        if (!empty($website)) {
            $data['dealership_website']                                         = $website;
        }

        if ($data['status'] == 1 && $data['maintenance_mode'] == 0) {           //Paid Theme Related Info
            $data['pageName']                                                   = $fair_full_details['title'];
            $data['isPaid']                                                     = $data['is_paid'];

            if ($data['isPaid'] == 1) {                                         //Get Pages If Theme is Paid.
                $pages                                                          = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id);
                $data['pages']                                                  = $pages;
                $about_us_text                                                  = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'about-us');
                $data['about_us']                                               = $about_us_text;
            }

            $data_countries                                                     = $this->check_domain->get_active_countries(); //get all active `countries`

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]                                     = $this->check_domain->get_active_states($value['sid']); //get all active `states`
            }

            $data['active_countries']                                           = $data_countries;
            $data['active_states']                                              = $data_states;
            $data['states']                                                     = htmlentities(json_encode($data_states));
            $data['formpost']                                                   = $_POST;
            $fair_default_fields                                                = array();
            $fair_custom_fields                                                 = array();
            $fair_fields_key_pairs                                              = array();
            $template_sid                                                       = 0;
            $template_status                                                    = 0;

            if ($form_type == 'default') {
                $job_fairs_forms_sid                                            = 0;
                $job_fair_default_questions                                     = array();
                $job_fair_custom_questions                                      = array();
                $job_fair_question_options                                      = array();
            } else {
                $job_fairs_forms_sid                                            = $active_fair_form['sid'];
                $job_fair_default_questions                                     = $this->contact_model->fetch_job_fair_forms_questions($job_fairs_forms_sid, 'default');
                $job_fair_custom_questions                                      = $this->contact_model->fetch_job_fair_forms_questions($job_fairs_forms_sid, 'custom');
                $job_fair_question_options                                      = $this->contact_model->fetch_job_fair_forms_questions_option($job_fairs_forms_sid);

                foreach ($job_fair_default_questions as $key => $jfdq) {
                    $fair_default_fields[]                                      = $jfdq['field_id'];
                    $fair_fields_key_pairs[$jfdq['field_id']]                   = $jfdq['field_name'];
                    // Aded on: 11-07-2019
                    $job_fair_default_questions[$key]['is_phone_field']        = 0;
                    // $job_fair_default_questions[$key]['is_phone_field']        = strtolower(trim($jfdq['question_type'])) === 'string' && preg_match('/cell|phone|contact|mobile/i', $jfdq['field_name']) ? 1 : 0;
                }

                foreach ($job_fair_custom_questions as $key => $jfcq) {
                    $fair_custom_fields[]                                       = $jfcq['field_id'];
                    $fair_fields_key_pairs[$jfcq['field_id']]                   = $jfcq['field_name'];
                    $job_fair_custom_questions[$key]['is_phone_field']          = 0;
                    // $job_fair_custom_questions[$key]['is_phone_field']          = strtolower(trim($jfcq['question_type'])) === 'string' && preg_match('/cell|phone|contact|mobile/i', $jfcq['field_name']) ? 1 : 0;
                }

                $template_sid                                                   = $active_fair_form['template_sid'];
                $template_status                                                = $active_fair_form['template_status'];
            }

            // _e($fair_fields_key_pairs, true);
            // _e($job_fair_default_questions, true);
            // _e($job_fair_custom_questions, true, true);

            $data['heading_title']                                              = $fair_full_details['title'];
            $data['content']                                                    = $fair_full_details['content'];
            $data['picture_or_video']                                           = $fair_full_details['picture_or_video'];
            $data['video_type']                                                 = $fair_full_details['video_type'];
            $data['video_id']                                                   = $fair_full_details['video_id'];
            $data['banner_image']                                               = $fair_full_details['banner_image'];
            $data['form_type']                                                  = $form_type;
            $data['job_fair_custom_forms']                                      = $fair_full_details;
            $data['job_fairs_forms_sid']                                        = $fair_full_details['sid'];
            $data['job_fair_default_questions']                                 = $job_fair_default_questions;
            $data['job_fair_custom_questions']                                  = $job_fair_custom_questions;
            $data['job_fair_question_options']                                  = $job_fair_question_options;
            $resume_is_required                                                 = false;
            $profile_picture_is_required                                        = false;

            $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

            if (!empty($job_fair_default_questions)) {
                foreach ($job_fair_default_questions as $default_data_keys => $default_data_fields) {
                    $field_is_required                                          = 'trim';
                    $field_id                                                   = $default_data_fields['field_id'];

                    if ($default_data_fields['is_required'] == 1) {
                        $field_is_required                                      = 'trim|required';
                    }

                    $field_name                                                 = $default_data_fields['field_name'];

                    if ($default_data_fields['field_priority'] == 'optional') {
                        if ($field_id == 'resume') {
                            if ($default_data_fields['is_required'] == 1) {
                                $resume_is_required                             = true;
                            }
                        } else if ($field_id == 'profile_picture') {
                            if ($default_data_fields['is_required'] == 1) {
                                $profile_picture_is_required                      = true;
                            }
                        } else {
                            if ($field_id != 'desired_job_title' && $field_id != 'video_resume') {
                                $this->form_validation->set_rules($field_id, $field_name, $field_is_required);
                            }
                        }
                    }
                }
            }

            if (!empty($job_fair_custom_questions)) {
                foreach ($job_fair_custom_questions as $custom_data_keys => $custom_data_fields) {
                    $field_id                                                   = $custom_data_fields['field_id'];
                    $field_is_required                                          = 'trim';

                    if ($custom_data_fields['is_required'] == 1) {
                        $field_is_required                                      = 'trim|required';
                    }

                    $field_name                                                 = $custom_data_fields['field_name'];

                    if ($custom_data_fields['field_priority'] == 'optional') {
                        if ($custom_data_fields['question_type'] == 'multilist') {
                            $field_id                                           = $field_id . '[]';
                        }

                        $this->form_validation->set_rules($field_id, $field_name, $field_is_required);
                    }
                }
            }

            if (isset($_POST['action']) && $_POST['action'] == 'Submit') {
                if ($resume_is_required) {
                    if (empty($_FILES['resume']['name'])) {
                        $this->form_validation->set_rules('resume', 'Resume', 'trim|required');
                    }
                }
            }

            if (isset($_POST['action']) && $_POST['action'] == 'Submit') {
                if ($profile_picture_is_required) {
                    if (empty($_FILES['profile_picture']['name'])) {
                        $this->form_validation->set_rules('profile_picture', 'Profile Picture', 'trim|required');
                    }
                }
            }

            if ($this->form_validation->run() === FALSE) {
                $data['meta_title']                                             = $data['meta_title'];
                $data['meta_description']                                       = $data['meta_description'];
                $data['meta_keywords']                                          = $data['meta_keywords'];
                $data['embedded_code']                                          = $data['embedded_code'];

                if ($data['is_paid']) {
                    $this->load->view($theme_name . '/_parts/header_view', $data);
                    $this->load->view($theme_name . '/jobs_fair');
                    $this->load->view($theme_name . '/_parts/footer_view');
                } else {
                    $this->load->view($theme_name . '/_parts/header_view', $data);
                    $this->load->view('common/jobs_fair');
                    $this->load->view($theme_name . '/_parts/footer_view');
                }
            } else {
                $hack = false;
                // $recaptcha_response = $this->input->post('g-recaptcha-response');
                // $google_secret = $this->config->item('google_secret');

                // $recaptcha = verifyCaptcha($google_secret, $recaptcha_response);

                // if ($recaptcha == false) {
                //     $this->session->set_flashdata('message', '<b>Error: </b>Sorry Google Recaptcha Failed.');
                //     redirect('/job_fair', 'refresh');
                // }


                $formpost = $this->input->post(NULL, TRUE);
                //
                if (!isset($formpost['g-recaptcha-response']) || empty($formpost['g-recaptcha-response'])) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong>Failed to verify captcha.');
                    if ($this->input->post('dr', true)) {
                        echo "Google captcha not set";
                        exit();
                    }
                    return redirect('/job_fair' . '/' . $redirect_page_url . "?applied_by=" . rand(1, 99), 'refresh');
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
                    return redirect('/job_fair' . '/' . $redirect_page_url . "?applied_by=" . rand(1, 99), 'refresh');
                }

                $status                                                         = $this->job_details->update_applicant_status_sid($data['company_details']['sid']); // get the statuses first for current company
                $email                                                          = $this->input->post('email');
                $first_name                                                     = $this->input->post('first_name');
                $last_name                                                      = $this->input->post('last_name');
                $resume                                                         = '';
                $profile_picture                                                = '';
                //
                if (check_company_status($data['company_details']['sid']) == 0) {
                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                    if ($this->input->post('dr', true)) {
                        echo "Job application success";
                        exit();
                    }
                    redirect('/', 'refresh');
                }

                if ($email) {
                    // check if email is blocked
                    if (checkForBlockedEmail($email) == 'blocked') {
                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                        redirect('/job_fair' . '/' . $redirect_page_url . "?applied_by=" . rand(1, 99), 'refresh');
                    }
                }
                //
                $fair_job_sid                                                   = $this->job_details->check_job_applicant('company_check', $email, $data['company_details']['sid']);
                $job_added_successfully                                         = 0;
                $date_applied                                                   = date('Y-m-d H:i:s');
                $talent_and_fair_data                                           = array();
                $talent_data_to_store                                           = array();

                $portal_applicant_jobs_list_sid = 0;
                if ($form_type == 'default') {
                    $country                                                    = $this->input->post('country');
                    $state                                                      = $this->input->post('state');
                    $city                                                       = $this->input->post('city');
                    $phone_number                                               = $this->input->post('phone_number', true);
                    $phone_number                                               = '+1' . (preg_replace('/[^0-9]/', '', $phone_number));
                    $desired_job_title                                          = $fair_full_details['title'];
                    $job_fair_key                                               = $redirect_page_url;
                    $college_university_name                                    = $this->input->post('college_university_name');
                    $job_interest_text                                          = $this->input->post('job_interest_text');
                    $video_source                                               = $this->input->post('video_source');
                    $fileData                                                   = 'College University Name' . PHP_EOL;
                    $fileData                                                   .= $college_university_name . PHP_EOL;
                    $fileData                                                   .= PHP_EOL . PHP_EOL . PHP_EOL . "Jobs You Are Interested In?" . PHP_EOL . $job_interest_text;
                    //$cover_letter                                               = $this->custom_file_to_AWS($first_name . '-cover-letter', $fileData);
                    $cover_letter                                               = '';
                    $talent_and_fair_data['College University Name']            = $college_university_name;
                    $talent_and_fair_data['Jobs You Are Interested In?']        = $job_interest_text;
                    $talent_data_to_store                                       = array('title' => $data['heading_title'], 'questions' => $talent_and_fair_data);
                    $serialize_talent_and_fair_data                             = serialize($talent_data_to_store);
                    // check for hack attemnpts
                    if (in_array(preg_replace('/[^0-9]/', '', $phone_number), ['12134251453'])) {
                        $hack = true;
                    }

                    //
                    if ($hack) {
                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                        if ($this->input->post('dr', true)) {
                            echo "Job application success";
                            exit();
                        }
                        redirect('/job_fair' . '/' . $redirect_page_url . "?applied_by=" . rand(1, 99), 'refresh');
                    }

                    if ($fair_job_sid == 'no_record_found') { // new entry in job applications table
                        if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["resume"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $resume                                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($resume, $_FILES['resume']['tmp_name'], AWS_S3_BUCKET_NAME);
                        }

                        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["profile_picture"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $profile_picture                                    = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($profile_picture, $_FILES['profile_picture']['tmp_name'], AWS_S3_BUCKET_NAME);
                        }

                        $video_id                                               = '';

                        if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                            $random                                             = generateRandomString(5);
                            $company_sid                                        = $data['company_details']['sid'];
                            $target_file_name                                   = basename($_FILES['video_upload']['name']);
                            $file_name                                          = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                            $target_dir                                         = "../assets/uploaded_videos/";
                            $target_file                                        = $target_dir . $file_name;
                            $folder_name                                        = $target_dir . $company_id;

                            if (!file_exists($folder_name)) {
                                mkdir($folder_name);
                            }

                            if (move_uploaded_file($_FILES['video_upload']['tmp_name'], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your video file.');
                                if ($this->input->post('dr', true)) {
                                    echo "Job fair application error";
                                    exit();
                                }
                                redirect('/job_fair' . '/' . $redirect_page_url, 'refresh');
                            }

                            $video_id                                           = $file_name;
                        } else {
                            $video_id                                           = $this->input->post('yt_vm_video_url');

                            if (!empty($video_id)) {
                                if ($video_source == 'youtube') {
                                    $url_prams                                  = array();
                                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_id                               = $url_prams['v'];
                                    } else {
                                        $video_id                               = '';
                                    }
                                } else {
                                    $video_id                                    = $this->vimeo_get_id($video_id);
                                }
                            }
                        }

                        $output = $this->contact_model->talent_network_applicant($email, $first_name, $last_name, $country, $city, $phone_number, $desired_job_title, $resume, $data, $cover_letter, $state, $date_applied, $video_source, $video_id);

                        if ($output[1] == 1) {                                  // data inserted successfully
                            $applicant_network_sid                              = $output[0];
                            $insert_data                                        = array();
                            $insert_data['portal_job_applications_sid']         = $applicant_network_sid;
                            $insert_data['company_sid']                         = $data['company_details']['sid'];
                            $insert_data['job_sid']                             = 0;
                            $insert_data['date_applied']                        = $date_applied;
                            $insert_data['status']                              = $status['status_name'];
                            $insert_data['status_sid']                          = $status['status_sid'];
                            $insert_data['ip_address']                          = getUserIP();
                            $insert_data['user_agent']                          = $_SERVER['HTTP_USER_AGENT'];
                            $insert_data['applicant_type']                      = 'Job Fair';
                            $insert_data['applicant_source']                    = $_SERVER['HTTP_HOST'];
                            $insert_data['desired_job_title']                   = $fair_full_details['title'];
                            $insert_data['job_fair_key']                        = $redirect_page_url;
                            $insert_data['talent_and_fair_data']                = $serialize_talent_and_fair_data;
                            $output                                             = $this->job_details->add_applicant_job_details($insert_data);
                            $job_added_successfully                             = $output[1];
                        } else {
                            $this->session->set_flashdata('message', '<b>Failed:</b>Could not send your Enquiry, Please try Again!');
                        }
                    } else { // applicant has already applied somewhere else
                        //$this->job_details->update_applicant_applied_date($talent_network_sid, $update_applicant_jobs);
                        if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["resume"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $resume                                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($resume, $_FILES['resume']['tmp_name'], AWS_S3_BUCKET_NAME);
                            $data_for_resume                                    = array('resume' => $resume);
                            $this->job_details->update_applicant_applied_date($fair_job_sid, $data_for_resume);
                        }

                        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["profile_picture"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $profile_picture                                    = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($profile_picture, $_FILES['profile_picture']['tmp_name'], AWS_S3_BUCKET_NAME);
                            $data_for_profile_picture                           = array('pictures' => $profile_picture);
                            $this->job_details->update_applicant_applied_date($fair_job_sid, $data_for_profile_picture);
                        }

                        $video_source                                           = $this->input->post('video_source');
                        $video_id                                               = '';

                        if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                            $random                                             = generateRandomString(5);
                            $company_sid                                        = $data['company_details']['sid'];
                            $target_file_name                                   = basename($_FILES['video_upload']['name']);
                            $file_name                                          = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                            // $target_dir = PARENT_ASSETS_PATH."/uploaded_videos/";
                            $target_dir                                         = "../assets/uploaded_videos/";
                            $target_file                                        = $target_dir . $file_name;
                            $folder_name                                        = $target_dir . $company_id;

                            if (!file_exists($folder_name)) {
                                mkdir($folder_name, 0777);
                            }

                            if (move_uploaded_file($_FILES['video_upload']['tmp_name'], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES['video_upload']['name']) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your video file.');
                                if ($this->input->post('dr', true)) {
                                    echo "Error file uploading";
                                    exit();
                                }
                                redirect('/job_fair' . '/' . $redirect_page_url, 'refresh');
                            }

                            $video_id                                           = $file_name;
                        } else {
                            $video_id                                           = $this->input->post('yt_vm_video_url');

                            if (!empty($video_id)) {
                                if ($video_source == 'youtube') {
                                    $url_prams                                  = array();
                                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_id                               = $url_prams['v'];
                                    } else {
                                        $video_id                               = '';
                                    }
                                } else {
                                    $video_id                                    = $this->vimeo_get_id($video_id);
                                }
                            }
                        }

                        $data_for_video                                         = array('video_type' => $video_source, 'YouTube_Video' => $video_id);
                        $this->job_details->update_applicant_applied_date($fair_job_sid, $data_for_video);

                        $insert_data                                            = array();
                        $insert_data['portal_job_applications_sid']             = $fair_job_sid;
                        $insert_data['company_sid']                             = $data['company_details']['sid'];
                        $insert_data['job_sid']                                 = 0;
                        $insert_data['date_applied']                            = $date_applied;
                        $insert_data['status']                                  = $status['status_name'];
                        $insert_data['status_sid']                              = $status['status_sid'];
                        $insert_data['ip_address']                              = getUserIP();
                        $insert_data['user_agent']                              = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['applicant_type']                          = 'Job Fair';
                        $insert_data['applicant_source']                        = $_SERVER['HTTP_HOST'];
                        $insert_data['desired_job_title']                       = $fair_full_details['title'];
                        $insert_data['job_fair_key']                            = $redirect_page_url;
                        $insert_data['talent_and_fair_data']                    = $serialize_talent_and_fair_data;
                        $output                                                 = $this->job_details->add_applicant_job_details($insert_data);
                        $portal_applicant_jobs_list_sid                                 = $output[0];
                        $job_added_successfully                                 = $output[1];
                    }

                    if ($job_added_successfully == 1) {
                        $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                    } // end of default form
                } else { // start of custom Form 
                    $formpost                                                   = $this->input->post(NULL, TRUE);
                    $default_insert_data                                        = array();
                    $custom_insert_data                                         = array();
                    $insert_data_primary                                        = array();
                    $fileData                                                   = '';
                    $data_for_primary_optional                                  = array();
                    $question_data                                              = '';
                    $resume                                                     = '';
                    $profile_picture                                            = '';
                    $video_source                                               = 'no_video';


                    if (isset($_POST['video_source'])) {
                        $video_source                                           = $this->input->post('video_source');
                    }


                    foreach ($formpost as $key => $value) {
                        // Added on: 11-07-2019
                        // Replace phone number format
                        // if(isset($formpost['txt_'.($key).''])){
                        //     $value = $formpost['txt_'.($key).''];
                        //     $formpost[$key] = $formpost['txt_'.($key).''];
                        // }
                        $answered                                               = array();

                        if (in_array($key, $fair_default_fields)) {
                            $default_insert_data[$key]                          = $value;
                        }

                        if (in_array($key, $fair_custom_fields)) {
                            $custom_insert_data[$key]                           = $value;
                        }

                        if ($key != 'first_name' && $key != 'last_name' && $key != 'email' && $key != 'country' && $key != 'state' && $key != 'city' && $key != 'phone_number' && $key != 'desired_job_title' && $key != 'video_source' && $key != 'action') {
                            $fileData                                           .= PHP_EOL . PHP_EOL . PHP_EOL . $key . ':' . PHP_EOL;
                            $question_data                                      = $fair_fields_key_pairs[$key];

                            if (is_array($value)) {
                                foreach ($value as $value_value) {
                                    $fileData                                   .= $value_value . PHP_EOL;
                                    $answered[]                                 = $value_value;
                                }
                            } else {
                                $fileData                                        .= $value . PHP_EOL;
                                $answered[]                                      = $value;
                            }

                            $talent_and_fair_data[$question_data]               = $answered;
                        }

                        if ($key == 'country' || trim($key) == 'state' || trim($key) == 'city' || $key == 'phone_number') {
                            $data_for_primary_optional[$key]                    = $value;
                        }
                    }

                    $talent_data_to_store                                       = array('title' => $data['heading_title'], 'questions' => $talent_and_fair_data);
                    $serialize_talent_and_fair_data                             = serialize($talent_data_to_store);

                    if ($fair_job_sid == 'no_record_found') { // new entry in job applications table
                        if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES['resume']['name']);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $resume                                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($resume, $_FILES['resume']['tmp_name'], AWS_S3_BUCKET_NAME);
                        }

                        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["profile_picture"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $profile_picture                                    = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($profile_picture, $_FILES['profile_picture']['tmp_name'], AWS_S3_BUCKET_NAME);
                        }

                        $data_for_primary                                       = array(
                            'employer_sid'  => $data['company_details']['sid'],
                            'email'         => $email,
                            'first_name'    => $first_name,
                            'last_name'     => $last_name
                        );

                        if ($resume != '') {
                            $data_for_resume                                    = array('resume' => $resume);
                            $data_for_primary                                   = array_merge($data_for_primary, $data_for_resume);
                        }

                        if ($profile_picture != '') {
                            $data_for_profile_picture                           = array('pictures' => $profile_picture);
                            $data_for_primary                                   = array_merge($data_for_primary, $data_for_profile_picture);
                        }

                        $video_id                                               = '';

                        if (!empty($_FILES) && isset($_FILES['video_upload']) && $_FILES['video_upload']['size'] > 0) {
                            $random                                             = generateRandomString(5);
                            $company_sid                                        = $data['company_details']['sid'];
                            $target_file_name                                   = basename($_FILES['video_upload']['name']);
                            $file_name                                          = strtolower($company_sid . '/' . $random . '_' . $target_file_name);
                            $target_dir                                         = "../assets/uploaded_videos/";
                            $target_file                                        = $target_dir . $file_name;
                            $folder_name                                        = $target_dir . $company_id;

                            if (!file_exists($folder_name)) {
                                mkdir($folder_name);
                            }

                            if (move_uploaded_file($_FILES['video_upload']['tmp_name'], $target_file)) {
                                $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES['video_upload']['name']) . ' has been uploaded.');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your video file.');
                                if ($this->input->post('dr', true)) {
                                    echo "Error file upload";
                                    exit();
                                }
                                redirect('/job_fair' . '/' . $redirect_page_url, 'refresh');
                            }

                            $video_id                                           = $file_name;
                        } else {
                            $video_id                                           = $this->input->post('yt_vm_video_url');

                            if (!empty($video_id)) {
                                if ($video_source == 'youtube') {
                                    $url_prams                                  = array();
                                    parse_str(parse_url($video_id, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_id                               = $url_prams['v'];
                                    } else {
                                        $video_id                               = '';
                                    }
                                } else {
                                    $video_id                                    = $this->vimeo_get_id($video_id);
                                }
                            }
                        }

                        if ($video_id != '') {
                            $data_for_video_resume                              = array('video_type' => $video_source, 'YouTube_Video' => $video_id);
                            $data_for_primary                                   = array_merge($data_for_primary, $data_for_video_resume);
                        }

                        $insert_data_primary                                    = array_merge($data_for_primary, $data_for_primary_optional);
                        $output                                                 = $this->contact_model->custom_job_fair_entry_to_ats($insert_data_primary);

                        if ($output[1] == 1) { // data inserted successfully
                            $applicant_network_sid                              = $output[0];
                            $insert_data                                        = array();
                            $insert_data['portal_job_applications_sid']         = $applicant_network_sid;
                            $insert_data['company_sid']                         = $data['company_details']['sid'];
                            $insert_data['job_sid']                             = 0;
                            $insert_data['date_applied']                        = $date_applied;
                            $insert_data['status']                              = $status['status_name'];
                            $insert_data['status_sid']                          = $status['status_sid'];
                            $insert_data['ip_address']                          = getUserIP();
                            $insert_data['user_agent']                          = $_SERVER['HTTP_USER_AGENT'];
                            $insert_data['applicant_type']                      = 'Job Fair';
                            $insert_data['applicant_source']                    = $_SERVER['HTTP_HOST'];
                            $insert_data['talent_and_fair_data']                = $serialize_talent_and_fair_data;
                            $insert_data['desired_job_title']                   = $fair_full_details['title'];
                            $insert_data['job_fair_key']                        = $redirect_page_url;
                            $output                                             = $this->job_details->add_applicant_job_details($insert_data);
                            $portal_applicant_jobs_list_sid                                 = $output[0];
                            $job_added_successfully                             = $output[1];
                        } else {
                            $this->session->set_flashdata('message', '<b>Failed:</b>Could not send your Enquiry, Please try Again!');
                        }
                    } else { // applicant has already applied somewhere else //$this->job_details->update_applicant_applied_date($talent_network_sid, $update_applicant_jobs);
                        if (isset($_FILES['resume']) && $_FILES['resume']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES['resume']['name']);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $resume                                             = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($resume, $_FILES['resume']['tmp_name'], AWS_S3_BUCKET_NAME);
                            $data_for_resume                                    = array('resume' => $resume);
                            $this->job_details->update_applicant_applied_date($fair_job_sid, $data_for_resume);
                        }

                        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['name'] != '') { //uploading resume to AWS
                            $file                                               = explode(".", $_FILES["profile_picture"]["name"]);
                            $file_name                                          = str_replace(" ", "-", $file[0]);
                            $profile_picture                                    = $file_name . '-' . generateRandomString(6) . '.' . $file[1];
                            $aws                                                = new AwsSdk();
                            $aws->putToBucket($profile_picture, $_FILES['profile_picture']['tmp_name'], AWS_S3_BUCKET_NAME);
                            $data_for_profile_picture                           = array('pictures' => $profile_picture);
                            $this->job_details->update_applicant_applied_date($fair_job_sid, $data_for_profile_picture);
                        }

                        $insert_data                                            = array();
                        $insert_data['portal_job_applications_sid']             = $fair_job_sid;
                        $insert_data['company_sid']                             = $data['company_details']['sid'];
                        $insert_data['job_sid']                                 = 0;
                        $insert_data['date_applied']                            = $date_applied;
                        $insert_data['status']                                  = $status['status_name'];
                        $insert_data['status_sid']                              = $status['status_sid'];
                        $insert_data['ip_address']                              = getUserIP();
                        $insert_data['user_agent']                              = $_SERVER['HTTP_USER_AGENT'];
                        $insert_data['applicant_type']                          = 'Job Fair';
                        $insert_data['applicant_source']                        = $_SERVER['HTTP_HOST'];
                        $insert_data['talent_and_fair_data']                    = $serialize_talent_and_fair_data;
                        $insert_data['desired_job_title']                       = $fair_full_details['title'];
                        $insert_data['job_fair_key']                            = $redirect_page_url;
                        $output                                                 = $this->job_details->add_applicant_job_details($insert_data);
                        $portal_applicant_jobs_list_sid                                 = $output[0];
                        $job_added_successfully                                 = $output[1];
                    }

                    //******************************Send success templated email*************************
                    if ($template_sid > 0 && $template_status > 0) {
                        $attach_body                                            = '';
                        $attachments                                            = $this->check_domain->get_all_email_template_attachments($template_sid);

                        if (sizeof($attachments) > 0) {
                            $attach_body                                        .= '<br> Please Review The Following Attachments: <br>';

                            foreach ($attachments as $attachment) {
                                $attach_body                                    .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a> <br>';
                            }
                        }

                        $template_data                                          = $this->check_domain->fetch_template_by_sid($template_sid);
                        $today                                                  = new DateTime();
                        $today                                                  = $today->format('m-d-Y');
                        $applicant_fname                                        = $formpost['first_name'];
                        $applicant_lname                                        = $formpost['last_name'];
                        $subject                                                = str_replace('{{company_name}}', $company_name, $template_data[0]['subject']);
                        $subject                                                = str_replace('{{date}}', $today, $subject);
                        $subject                                                = str_replace('{{first_name}}', $applicant_fname, $subject);
                        $subject                                                = str_replace('{{last_name}}', $applicant_lname, $subject);
                        $subject                                                = str_replace('{{applicant_name}}', $applicant_fname . ' ' . $applicant_lname, $subject);
                        $body                                                   = str_replace('{{company_name}}', $company_name, $template_data[0]['message_body']);
                        $body                                                   = str_replace('{{date}}', $today, $body);
                        $body                                                   = str_replace('{{first_name}}', $applicant_fname, $body);
                        $body                                                   = str_replace('{{last_name}}', $applicant_lname, $body);
                        $body                                                   = str_replace('{{applicant_name}}', $applicant_fname . ' ' . $applicant_lname, $body);
                        $body                                                   .= $attach_body;

                        $from_name                                              = $company_name;
                        $message_data                                           = array();
                        $message_data['to_id']                                  = $formpost['email'];
                        $message_data['from_type']                              = 'employer';
                        $message_data['to_type']                                = 'applicant';
                        $message_data['users_type']                             = 'employee';
                        $message_data['subject']                                = $subject;
                        $message_data['message']                                = $body;
                        $message_data['date']                                   = $today;
                        $message_data['contact_name']                           = $applicant_fname . ' ' . $applicant_lname;
                        $message_data['identity_key']                           = generateRandomString(48);
                        $message_hf                                             = message_header_footer_domain($company_id, $company_name);
                        $secret_key                                             = $message_data['identity_key'] . "__";
                        $autoemailbody                                          = $message_hf['header']
                            . '<p>Subject: ' . $subject . '</p>'
                            . $body
                            . $message_hf['footer']
                            . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                            . $secret_key . '</div>';

                        sendMail(REPLY_TO, $formpost['email'], $subject, $autoemailbody, $from_name, REPLY_TO);
                    }

                    $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your application, we will contact you soon.');
                }
                if ($this->input->post('dr', true)) {
                    echo "Job fir redirect";
                    exit();
                }
                redirect('/job_fair' . '/' . $redirect_page_url . "?applied_by=" . $portal_applicant_jobs_list_sid, 'refresh');
            }
        } else {
            redirect('/', 'refresh');
        }
    }

    public function preview_job($sid = null)
    {
        $sid                                                                    = $this->input->post('sid') ? $this->input->post('sid') : $sid;
        $server_name                                                            = clean_domain($_SERVER['SERVER_NAME']);
        $data                                                                   = $this->check_domain->check_portal_status($server_name);
        $data['is_paid']                                                        = 1;
        $company_id                                                             = $data['company_details']['sid'];
        $company_name                                                           = $data['company_details']['CompanyName'];
        $theme_name                                                             = $data['theme_name'];

        company_phone_regex_module_check($company_id, $data, $this);

        if (!is_numeric($sid)) {
            $sid                                                                = $this->job_details->fetch_job_id_from_random_key($sid);
        }

        $jobs_page_title                                                        = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'jobs', 'jobs_page_title');
        $jobs_page_title                                                        = !empty($jobs_page_title) ? $jobs_page_title : 'jobs';
        $data['jobs_page_title']                                                = strtolower(str_replace(' ', '_', $jobs_page_title));

        if ($sid != null && intval($sid) > 0) {
            $list                                                           = $this->job_details->fetch_company_jobs_new(NULL, $sid, TRUE, 1);
            // $list                                                           = $this->job_details->fetch_company_jobs_details($sid, NULL, 1);
            $user_sid                                                       = $list['user_sid'];
            $jobs_detail_page_title                                         = $this->theme_meta_model->fGetThemeMetaData($user_sid, $theme_name, 'jobs_detail', 'jobs_detail_page_banner');
            $data['jobs_detail_page_banner_data']                           = $jobs_detail_page_title;

            if (!empty($list)) {
                $company_sid                                                = $list['user_sid'];
                $data['application_acknowledgement_letter']                 = '';
                $data['enable_auto_responder_email']                        = '';
                $data['pageName']                                           = 'job_details';
                $data['isPaid']                                             = $data['is_paid'];
                $data['dealership_website']                                 = '';
                $pages                                                      = $this->themes_pages_model->GetAllPageNamesAndTitles($company_id); //Pages Information
                $data['pages']                                              = $pages;
                $about_us_text                                              = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'about-us'); //About Us Information
                $data['about_us']                                           = $about_us_text;
                $footer_content                                             = $this->theme_meta_model->fGetThemeMetaData($company_id, $theme_name, 'home', 'footer_content');
                $footer_content['title']                                    = str_replace("{{company_name}}", $company_name, $footer_content['title']);
                $footer_content['content']                                  = str_replace("{{company_name}}", $company_name, $footer_content['content']);
                $data['footer_content']                                     = $footer_content;
                $website                                                    = $data['company_details']['WebSite'];

                if (!empty($website)) {
                    $data['dealership_website']                             = $website;
                }

                $data['job_details']                                        = array();
                $country_id                                                 = $list['Location_Country'];

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
                    $list['Location_Country']                               = $country_name;
                }

                $state_id                                                   = $list['Location_State'];

                if (!empty($state_id) && $state_id != 'undefined') {
                    $state_name                                             = $this->job_details->get_statename_by_id($state_id); // get state name
                    $list['Location_State']                                 = $state_name[0]['state_name'];
                }

                $JobCategorys                                               = $list['JobCategory'];

                if ($JobCategorys != null) {
                    $cat_id                                                 = explode(',', $JobCategorys);
                    $job_category_array                                     = array();

                    foreach ($cat_id as $id) {
                        $job_cat_name                                       = $this->job_details->get_job_category_name_by_id($id);

                        if (!empty($job_cat_name)) {
                            $job_category_array[]                           = $job_cat_name[0]['value'];
                        }
                    }

                    $job_category                                           = implode(',', $job_category_array);
                    $list['JobCategory']                                    = $job_category;
                }

                $date                                                       = substr($list['activation_date'], 0, 10); // change date format at front-end
                $date_array                                                 = explode('-', $date);
                $list['activation_date']                                    = $date_array[1] . '-' . $date_array[2] . '-' . $date_array[0];
                $questionnaire_sid                                          = 0; //$list['questionnaire_sid'];
                $list['Title']                                              = db_get_job_title($company_sid, $list['Title'], $list['Location_City'], $list['Location_State'], $list['Location_Country']);
                $data['active_countries']                                   = array();
                $data['active_states']                                      = array();
                $data['formpost']                                           = array();
                $data['states']                                             = htmlentities(json_encode(array()));
                $data['company_details']                                    = $this->job_details->get_company_details($list['user_sid']);
                $data['next_job']                                           = ''; //getting next and previous jobs link STARTS
                $data['prev_job']                                           = '';
                $next_job_anchor                                            = '';
                $prev_job_anchor                                            = '';
                $data['next_job']                                           = 'javascript:;';
                $data['prev_job']                                           = 'javascript:;';
                $company_subdomain_url                                      = STORE_PROTOCOL_SSL . db_get_sub_domain($company_sid); //Generate Share Links - start
                $portal_job_url                                             = $company_subdomain_url . '/job_details/' . $list['sid'];
                $fb_google_share_url                                        = str_replace(':', '%3A', $portal_job_url);
                $btn_facebook                                               = '<a target="_blank" href="javascript:;"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-2.png"></a>';
                $btn_twitter                                                = '<a target="_blank" href="javascript:;"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-3.png"></a>';
                //                      $btn_google                                                 = '<a target="_blank" href="javascript:;"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/' . $theme_name . '/images/social-1.png"></a>';
                $btn_linkedin                                               = '<a target="_blank" href="javascript:;"><img alt="" src="' . STORE_PROTOCOL_SSL . $server_name . '/assets/theme-1/images/social-4.png"></a>';
                $btn_job_link                                               = '<a target="_blank" href="javascript:;">' . ucwords($list['Title']) . '</a>';
                $btn_tell_a_friend                                          = '<a class="tellafrien-popup"><span><i class="fa fa-hand-o-right"></i></span>Tell A Friend</a>';
                $links                                                      = '';
                $links                                                      .= '<ul>';
                //                    $links                                                      .= '<li>' . $btn_google . '</li>';
                $links                                                      .= '<li>' . $btn_facebook . '</li>';
                $links                                                      .= '<li>' . $btn_linkedin . '</li>';
                $links                                                      .= '<li>' . $btn_twitter . '</li>';

                if ($theme_name == 'theme-4') {
                    $links                                                  .= '<li>' . $btn_tell_a_friend . '</li>';
                }

                $links                                                      .= '</ul>';
                $list['share_links']                                        = $links; //Generate Share Links - end
                $data['job_details']                                        = $list;

                if (empty($data['job_details']['pictures'])) {
                    $data['image']                                          = base_url('assets/theme-1/images/new_logo.JPG');
                } else {
                    $data['image']                                          = AWS_S3_BUCKET_URL . $data['job_details']['pictures'];
                }

                $action                                                     = '';
                $job_company_sid                                            = $data['company_details']['sid'];
                $job_company_career_title                                   = $this->theme_meta_model->fGetThemeMetaData($job_company_sid, $theme_name, 'jobs', 'jobs_page_title');

                if (empty($job_company_career_title)) {
                    $job_company_career_title                               = 'jobs';
                }

                $data['more_career_oppurtunatity']                          = 'javascript:;';
                $data['is_preview']                                         = 'yes';

                if ($theme_name == 'theme-4') {
                    $this->load->view($theme_name . '/_parts/header_view', $data);
                    $this->load->view($theme_name . '/_parts/page_banner');
                    $this->load->view($theme_name . '/job_detail_view');
                    $this->load->view($theme_name . '/_parts/footer_view');
                } else {
                    $this->load->view($theme_name . '/_parts/header_view', $data);
                    $this->load->view($theme_name . '/job_detail_view');
                    $this->load->view($theme_name . '/_parts/footer_view');
                }
            } else { //Job Id Is not 0 But Job Not Found
                echo 'No Preview Available';
            }
        } else { //Job Id Is 0 or Null
            echo 'No Preview Available';
        }
    }

    function validate_vimeo()
    {
        $str = $this->input->post('url');
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        echo TRUE;
                    } else {
                        $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                        $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                        echo FALSE;
                    }
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    echo TRUE;
                } else {
                    $this->form_validation->set_message('validate_vimeo', 'In-valid Vimeo video URL');
                    $this->session->set_flashdata('message', '<b>Error:</b> In-valid Vimeo video URL');
                    echo FALSE;
                }
            }
        } else {
            echo FALSE;
        }
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
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
    function checkUserAppliedForJob($company_sid)
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
        if ((int)$sid === 0) {
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
            if ($applied_from == 'job') redirect('/job_details/' . $job_sid, 'refresh');
            else if ($applied_from == 'jobs_list_view') redirect('/jobs/');
            else redirect('/', 'refresh');
        }
    }

    //
    function sitemap()
    {

        $domain = $_SERVER['SERVER_NAME'];
        //
        $domain = preg_replace('/-/', '_', str_replace(['.local', '.automotohr.com'], '', $domain));

        $path = APPPATH . '../../sitemaps/sitemap_' . $domain . '.xml';
        //
        if (!file_exists($path)) {
            die;
        }

        header("content-type: xml");

        echo file_get_contents($path);
    }
}
