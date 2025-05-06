<?php defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    private $css;
    private $js;
    private $header;
    private $footer;
    private $disableMinifiedFiles;

    public function __construct()
    {
        parent::__construct();
        $data['title'] = "Home";
        $this->load->model('home_model');

        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        //
        $this->disableMinifiedFiles = true;
    }

    public function index()
    {
        //
        $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);
        ///
        if ($this->session->userdata('logged_in')) {
            $session_details = $this->session->userdata('logged_in');
            $sid = $session_details['employer_detail']['sid'];
            $security_details = db_get_access_level_details($sid);
            $data['security_details'] = $security_details;
            $data['session'] = $this->session->userdata('logged_in');
        }

        //
        $homeContent = getPageContent('home', true)["page"];

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $homeContent['meta']['title'];
        $data['meta']['description'] = $homeContent['meta']['description'];
        $data['meta']['keywords'] = $homeContent['meta']['keyword'];

        if (isset($_COOKIE[STORE_NAME]['username']) && isset($_COOKIE[STORE_NAME]['password'])) {
            $this->load->model('users_model');
            $username = $this->decryptCookie($_COOKIE[STORE_NAME]['username']);
            $password = $this->decryptCookie($_COOKIE[STORE_NAME]['password']);
            $result = $this->users_model->login($username, $password);

            if ($result) {
                $sess_array = array(
                    'company_detail' => $result['company'],
                    'employer_detail' => $result['employer'],
                    'cart' => $result['cart'],
                    'portal_detail' => $result['portal'],
                    'clocked_status' => $result['clocked_status'],
                    'is_super' => 0
                );

                $this->session->set_userdata('logged_in', $sess_array);
            }
        }
        // css
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        // js
        $data['pageJs'] = [
            "https://www.google.com/recaptcha/api.js",
            "https://code.jquery.com/jquery-3.5.1.min.js",
            'https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js',
        ];
        // css bundle
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/home',
        ], $this->css, 'home', $this->disableMinifiedFiles);
        // js bundle
        $data['appJs'] = bundleJs([
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'v1/plugins/alertifyjs/alertify.min',
            'js/jquery.validate.min',
            'v1/app/js/pages/home',
            'js/app_helper',
            'v1/app/js/pages/schedule_demo',
        ], $this->js, 'home', $this->disableMinifiedFiles);

        $data['headerFixed'] = true;

        $data['pageContent'] = $homeContent;

        //_e($this->footer,true,true);

        $this->load->view($this->header, $data)
            ->view('v1/app/homepage')
            ->view($this->footer);
    }

    public function decryptCookie($value)
    {
        if (!$value) {
            return false;
        }

        $key = 'roltyFoamisTheDI';
        $crypttext = base64_decode($value); //decode cookie
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    public function services($pageName)
    {
        if ($this->session->userdata('logged_in')) {
            $session_details = $this->session->userdata('logged_in');
            $sid = $session_details['employer_detail']['sid'];
            $security_details = db_get_access_level_details($sid);
            $data['security_details'] = $security_details;
        }

        if ($pageName == 'candidate-assessment') { // disabling candidate assessment for now
            redirect(base_url());
        }


        $data['home_page'] = $this->home_model->get_home_page_data(); //Getting customize home page Data Starts
        $data['title'] = ucfirst(str_replace("-", " ", $pageName));

        $privacyPolicyContent = getPageContent('privacy_policy');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $privacyPolicyContent['page']['meta']['title'];
        $data['meta']['description'] = $privacyPolicyContent['page']['meta']['description'];
        $data['meta']['keywords'] = $privacyPolicyContent['page']['meta']['keywords'];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/css/contact_us',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/main',
            'v1/app/css/app',
            'v1/app/css/services',

        ], $this->css);
        //
        $data['appJs'] = bundleJs([
            'plugins/bootstrap5/js/bootstrap.bundle',
            'alertifyjs/alertify.min'
        ], $this->js);


        $data['privacyPolicyContent'] = $privacyPolicyContent;

        $this->load->view($this->header, $data);
        $this->load->view('v1/app/services/' . $pageName);
        $this->load->view($this->footer);
    }

    function remove_cart_item()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'remove_cart_item') {
            $sid = $_REQUEST['sid'];
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            db_delete_cart_product($sid);
            echo 'Success';

            $cart_data = db_get_cart_content($company_sid);
            $session = $this->session->userdata('logged_in');
            $session['cart'] = $cart_data;
            $this->session->set_userdata('logged_in', $session);

            if (empty($cart_data)) {
                $empty_array = array();
                $this->session->set_userdata('coupon_data', $empty_array);
            }

            exit;
        }
    }

    function apply_coupon_code()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'apply_coupon_code') {
            $coupon_code = $_REQUEST['coupon_code'];
            $coupon_data = db_get_coupon_content($coupon_code);

            if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                $arr = array();
                $arr[0] = 'error';
                echo json_encode($arr);
                exit();
            } else {
                $coupon_data = $coupon_data[0];
                $discount = $coupon_data['discount'];
                $type = $coupon_data['type'];
                $maximum_uses = $coupon_data['maximum_uses'];
                $start_date = $coupon_data['start_date'];
                $end_date = $coupon_data['end_date'];

                if ($start_date != null) { // check whether coupon is started
                    $current_date_time = date('Y-m-d H:i:s');

                    if ($start_date > $current_date_time) {
                        $arr = array();
                        $arr[0] = 'error';
                        echo json_encode($arr);
                        exit();
                    }
                }

                if ($end_date != null) { // check whether coupon has expired
                    $current_date_time = date('Y-m-d H:i:s');

                    if ($current_date_time > $end_date) {
                        $arr = array();
                        $arr[0] = "error";
                        echo json_encode($arr);
                        exit();
                    }
                }

                if ($maximum_uses == null || $maximum_uses == 0) {
                    //it is umlimited, no need to perform any checks
                }

                // All well than assign the coupon and apply discount
                $coupon_array = array(
                    'coupon_code' => $coupon_code,
                    'coupon_discount' => $discount,
                    'coupon_type' => $type
                );

                if (!isset($_REQUEST['minicart'])) {
                    //$this->session->set_userdata('coupon_data', $coupon_array);
                }

                $arr = array();
                $arr[0] = "success";
                $arr[1] = $coupon_code;
                $arr[2] = $discount;
                $arr[3] = $type;
                echo json_encode($arr);
                exit;
            }
        }
    }

    public function resource_page()
    {
        $this->load->model('resource_center_model');
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $main_menu_cat_tree                                                 = $this->generate_menu_tree();
            $data['cat_tree']                                                   = $main_menu_cat_tree;
            $data['main_menu']                                                  = $main_menu_cat_tree['main'];
            $data['sub_menu']                                                   = $main_menu_cat_tree['sub'];
            $main_menu_url                                                      = $main_menu_cat_tree['main_menu_url'];
            $sub_menu_url                                                       = $main_menu_cat_tree['sub_menu_url'];
            $data['main_menu_url']                                              = $main_menu_url;
            $data['sub_menu_url']                                               = $sub_menu_url;
            $sub_menu_chain                                                     = $main_menu_cat_tree['sub_menu_chain'];
            $main_menu_segment                                                  = '';
            $sub_menu_segment                                                   = '';
            $generated_doc_segment                                              = '';
            $segment1                                                           = $this->uri->segment(2);
            $segment2                                                           = $this->uri->segment(3);
            $segment3                                                           = $this->uri->segment(4);
            $left_navigation                                                    = 'parent';
            $left_menu                                                          = array();
            $left_menu_parent                                                   = '';
            $active_link                                                        = 'resource_page';
            $intro_main_fa_icon                                                 = 'fa-align-left';
            $main_menu_id                                                       = 0;
            $sub_menu_id                                                        = 0;
            $gen_menu_id                                                        = 0;
            $page_type                                                          = '';
            $page_content                                                       = array();
            $page_sub_menus                                                     = array();
            $data['security_details'] = $security_details;
            $data['left_navigation']                                            = $left_navigation;
            $data['left_menu']                                                  = $left_menu;
            $data['left_menu_parent']                                           = $left_menu_parent;
            $data['active_link']                                                = $active_link;
            $data['segment1']                                                   = $segment1;
            $data['page_content']                                               = $page_content;
            $data['page_sub_menus']                                             = $page_sub_menus;
            $data['intro_main_fa_icon']                                         = $intro_main_fa_icon;
            $data['main_menu_segment']                                          = $main_menu_segment;
            $data['sub_menu_segment']                                           = $sub_menu_segment;
            $data['generated_doc_segment']                                      = $generated_doc_segment;
            $data['page_type']                                                  = $page_type;

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Resources Page';
                $page_data = $this->home_model->get_resource_page_data();
                $data['page_data'] = $page_data;

                if ($page_data['page_status'] == 1) {
                    $this->load->view('main/header', $data);
                    $this->load->view('home/resource_page');
                    $this->load->view('main/footer');
                } else {
                    redirect('home', 'refresh');
                }
            } else {
                // Handle Post
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function generate_menu_tree()
    {
        $parent_menu                                                            = $this->resource_center_model->get_parent_menu_tree();
        $main_menu                                                              = array();
        $sub_menu                                                               = array();
        $main_menu_url                                                          = array();
        $sub_menu_url                                                           = array();
        $sub_menu_chain                                                         = array();

        for ($i = 0; $i < count($parent_menu); $i++) {
            $main_sid                                                           = $parent_menu[$i]['main_sid'];
            $url_code                                                           = $parent_menu[$i]['url_code'];
            $fa_icon                                                            = $parent_menu[$i]['fa_icon'];

            $main_menu[$main_sid] = array(
                'main_sid'                          => $parent_menu[$i]['main_sid'],
                'name'                              => $parent_menu[$i]['name'],
                'code'                              => $url_code,
                'fa_icon'                           => $fa_icon,
                'parent_sort_order'                 => $parent_menu[$i]['parent_sort_order'],
                'description'                       => $parent_menu[$i]['parent_description']
            );
            //            }
            $main_menu_url[$url_code] = array(
                'sid'                             => $parent_menu[$i]['main_sid'],
                'name'                            => $parent_menu[$i]['name'],
                'code'                            => $url_code,
                'fa_icon'                         => $fa_icon,
                'link'                            => base_url('resource_center') . '/' . $url_code
            );

            $sid                                                                = $parent_menu[$i]['sid'];
            $status                                                             = $parent_menu[$i]['status'];

            if ($sid > 0) {
                if ($status > 0) {
                    $sub_url_code                                               = $parent_menu[$i]['sub_url_code'];
                    $title                                                      = $parent_menu[$i]['title'];
                    $anchor_type                                                = $parent_menu[$i]['type'];
                    $anchor_href                                                = $parent_menu[$i]['anchor_href'];

                    if ($anchor_type == 'content') {
                        $link                                                   = base_url('resource_center') . '/' . $url_code . '/' . $sub_url_code;
                    } else {
                        $parsed                                                 = parse_url($anchor_href);

                        if (empty($parsed['scheme'])) {
                            $anchor_href                                        = 'http://' . ltrim($anchor_href, '/');
                        }

                        $link                                                   = $anchor_href;
                    }

                    if ($sub_url_code == NULL) {
                        $sub_url_code                                           = strtolower(clean($title));
                        $this->resource_center_model->update_url_code($sub_url_code, $sid, 'document_library_sub_menu');
                    }

                    $sub_menu[$main_sid][] = array(
                        'sid'                       => $sid,
                        'name'                      => $parent_menu[$i]['title'],
                        'url_code'                  => $sub_url_code,
                        'description'               => $parent_menu[$i]['description'],
                        'type'                      => $parent_menu[$i]['type'],
                        'parent_type'               => $parent_menu[$i]['parent_type'],
                        'video'                     => $parent_menu[$i]['video'],
                        'video_status'              => $parent_menu[$i]['video_status'],
                        'video_type'                => $parent_menu[$i]['video_type'],
                        'banner_status'             => $parent_menu[$i]['banner_status'],
                        'banner_url'                => $parent_menu[$i]['banner_url'],
                        'anchor_href'               => $parent_menu[$i]['anchor_href'],
                        'link'                      => $link
                    );

                    $sub_menu_chain[$url_code][] = array(
                        'name'              => $parent_menu[$i]['title'],
                        'url_code'          => $sub_url_code,
                        'link'              => $link
                    );

                    $sub_menu_url[$sub_url_code] = array(
                        'sid'                  => $sid,
                        'name'                 => $parent_menu[$i]['title'],
                        'type'                 => $parent_menu[$i]['type'],
                        'parent_sid'           => $parent_menu[$i]['main_sid'],
                        'parent_name'          => $parent_menu[$i]['name'],
                        'parent_code'          => $url_code,
                        'link'                 => $link
                    );
                }
            } else {
                $sub_menu[$main_sid]                                            = array();
                $sub_menu_chain[$url_code]                                      = array();
            }
        }

        $return_array = array(
            'main'                                            => $main_menu,
            'sub'                                             => $sub_menu,
            'main_menu_url'                                   => $main_menu_url,
            'sub_menu_url'                                    => $sub_menu_url,
            'sub_menu_chain'                                  => $sub_menu_chain
        );
        //        echo '<pre>'; print_r($return_array); exit;
        return $return_array;
    }

    /**
     * Handles event status
     *
     * @param $token String
     *
     * @return Void
     */
    function event($token = null)
    {
        if ($token == null) redirect('login', "refresh");
        // Clean $token
        $token = str_replace('$eb$eb$1', '/', trim(strip_tags(str_replace('%24', '$', $token))));
        // Load encryption library
        $this->load->library('encrypt');
        // Decode token
        $dec_token = $this->encrypt->decode($token);
        if (empty($dec_token)) show_404();
        // Parse decoded string
        $detail_array = explode(':', $dec_token);
        // 
        if (!sizeof($detail_array)) show_404();
        //
        $event_array = array();
        foreach ($detail_array as $k0 => $v0) {
            $tmp = explode('=', $v0);
            $event_array[$tmp[0]] = $tmp[1];
        }
        // Double check indexes
        if (!isset(
            $event_array['id'],
            $event_array['eid'],
            $event_array['etype'],
            $event_array['name'],
            $event_array['email'],
            $event_array['type']
        )) {
            show_404();
        }
        //

        // Loads calendar modal
        $this->load->model('calendar_model', 'cm');
        // Check if not set to false
        if (MAX_EVENT_HISTORY_ENTRIES) {
            $entries = $this->cm->check_event_history_limit(
                $event_array['eid'],
                $event_array['id']
            );
            if ($entries >= MAX_EVENT_HISTORY_ENTRIES) $data['error_msg'] = 'Error! You have reached maximum entries for this event.';
        }
        // Check for event expire
        $event_date = $this->cm->get_event_column_by_event_id($event_array['eid'], 'date', true);
        // 
        if (strtotime('now') > $event_date) {
            $data['error_msg'] = 'Error! The event is expired.';
        }
        // Get 
        $data['event_type'] = $event_type = $this->cm->get_event_column_by_event_id($event_array['eid'], 'category');

        //
        if ($event_type == 'training-session') {
            // Fetch participant status
            $participant_data = $this->cm->get_lc_particpant_status($event_array['id'], $event_array['eid']);
            $participant_status = $participant_data['attend_status'];
            $event_array['training_session_sid'] = $participant_data['training_session_sid'];
            // check if already changed the status
            if ($participant_status == 'attended')
                $data['error_msg'] = "You have already 'Attended' the event.";
            else if (in_array($participant_status, array('cancelled', 'confirmed')))
                $event_array['type'] = 'attended';

            if (!isset($data['error_msg']) && in_array($event_array['type'], array('attended', 'confirmed'))) {
                $data_array =  array(
                    'attended' => 1,
                    'attend_status' => $event_array['type'],
                    'attend_status_date' => date('Y-m-d H:i:s'),
                    'date_attended' => date('Y-m-d H:i:s')
                );
                //
                if ($event_array['type'] == 'confirmed') {
                    $data_array['attend_status'] = 'will_attend';
                    unset($data_array['attended']);
                }
                // Change user status
                $this->cm->update_lc_user_status(
                    $participant_data['training_session_sid'],
                    $event_array['id'],
                    $data_array
                );
            }
        }

        // Change status on confirm
        if (!isset($data['error_msg']) && $event_array['type'] == 'confirmed' && $event_type != 'training-session') {
            // Add record
            $this->cm->add_event_history(
                array(
                    'event_sid' => $event_array['eid'],
                    'user_sid'  => $event_array['id'],
                    'user_name' => $event_array['name'],
                    'user_email' => $event_array['email'],
                    'user_type' => strtolower($event_array['etype']),
                    'event_type' => 'confirmed',
                    'created_at' => date('Y-m-d H:i:s')
                )
            );
        }
        $data['event_array'] = $event_array;
        //
        $event_array['event_type'] = $event_type;
        // Added on: 02-05-2019
        // Updated on: 09-05-2019
        if (!isset($data['error_msg']) && ($event_array['type'] == 'confirmed' || $event_array['type'] == 'attended')) $this->send_email_to_event_creator($event_array);
        // Old session checking
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        // get event details
        $data['event_details'] = $this->cm->get_event_detail_for_frontend($event_array['eid']);

        // Set user timezone

        switch ($event_array['etype']) {
            case 'interviewer':
            case 'employee':
            case 'personal':
                $data['timezone'] = $this->cm->get_employee_detail($event_array['id'])['timezone'];
                break;
        }
        // $this->_e($data['event_details'], true);
        $data['company_timezone'] = $this->cm->get_timezone('company', $data['event_details']['companys_sid']);
        if (empty($data['timezone']))
            $data['timezone'] = $data['company_timezone'];

        if (($event_array['etype'] == "applicant" || $event_array['etype'] == "extrainterviewer" || $event_array['etype'] == "non-employee interviewer") && isset($data['event_details']['event_timezone']))
            $data['timezone'] = $data['event_details']['event_timezone'];
        $company_id = $this->cm->get_event_column_by_event_id($event_array['eid'], 'companys_sid');
        $company_name = $this->cm->get_event_column_by_event_id($event_array['eid'], 'CompanyName');
        $data['company_template_header_footer'] = message_header_footer($company_id, ucwords($company_name));
        error_reporting(0);
        //
        $data['title'] = 'Event';
        $this->load
            ->view('calendar/header', $data)
            ->view('calendar/event-page')
            ->view('calendar/footer');
    }


    /**
     * Calendar event handler
     *  
     * Accepts POST 
     *  
     * @return JSON 
     *  
     */
    function event_handler()
    {
        // Check if direct access made
        if ($this->input->server('REQUEST_METHOD') == 'GET') redirect('calendar/my_events', 'refresh');
        // Load calendar modal
        $this->load->model('calendar_model', 'cm');
        $type = $this->input->post('type');
        $message = '';
        // Check if not set to false
        if (MAX_EVENT_HISTORY_ENTRIES) {
            $entries = $this->cm->check_event_history_limit(
                $this->input->post('event_id'),
                $this->input->post('user_id')
            );
            if ($entries >= MAX_EVENT_HISTORY_ENTRIES) $this->response(array('Status' => FALSE, 'Erase' => TRUE, 'Response' => 'You have reaached maximum entries for this event.'));
        }
        // Check for event expire
        $event_date = $this->cm->get_event_column_by_event_id($this->input->post('event_id'), 'date', true);
        // 
        if (strtotime('now') > $event_date)
            $this->response(array('Status' => FALSE, 'Erase' => TRUE, 'Response' => 'Error! The event has been expired.'));

        $insert_array =
            array(
                'event_sid' => $this->input->post('event_id'),
                'user_sid'  => $this->input->post('user_id'),
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'user_type' => $this->input->post('user_type'),
                'event_type' => 'cannotattend',
                'created_at' => date('Y-m-d H:i:s')
            );
        //
        $event_data = array(
            'eid' => $insert_array['event_sid'],
            'name' => $insert_array['user_name'],
            'type' => 'Can Not Attend',
            'event_type' => $this->input->post('cat')
        );

        if ($this->input->post('cat') == 'training-session' && $this->input->post('type') == 'cannotattend') {
            // Change user status
            $this->cm->update_lc_user_status(
                $this->input->post('lcid'),
                $this->input->post('user_id'),
                array(
                    'attend_status' => 'unable_to_attend',
                    'txt_reason' => $this->input->post('event_reason') ? $this->input->post('event_reason') : NULL
                )
            );
            //
            $event_data['type'] = 'cancelled';
            $message = 'Your have cancelled the event.';
            $resp = true;
        } else {
            //
            if ($this->input->post('event_reason')) $event_data['reason'] = $insert_array['reason'] = $this->input->post('event_reason');
            $message = 'Your Interview cancellation request has been received.';
            //
            if ($type == 'reschedule') {
                $message = 'Your Interview reschedule request has been received.';
                $insert_array['event_type'] = 'reschedule';
                $event_data['type'] = 'Reschedule';
                //
                if ($this->input->post('event_date')) $event_data['event_new_date'] = $insert_array['event_date'] = DateTime::createFromFormat('m-d-Y', $this->input->post('event_date'))->format('Y-m-d');
                if ($this->input->post('event_start_time')) $event_data['event_new_start_time'] = $insert_array['event_start_time'] = $this->input->post('event_start_time');
                if ($this->input->post('event_end_time')) $event_data['event_new_end_time'] = $insert_array['event_end_time'] = $this->input->post('event_end_time');

                // Reset 
                if (isset($insert_array['event_date'])) {
                    $event_data['event_new_date'] = $insert_array['event_date'] = reset_datetime(array(
                        'datetime' => $insert_array['event_date'],
                        'from_format' => 'Y-m-d',
                        'format' => 'Y-m-d',
                        '_this' => $this,
                        'from_timezone' => $this->input->post('timezone'),
                        'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                    ));
                }


                if (isset($insert_array['event_start_time'])) {
                    $event_data['event_new_start_time'] = $insert_array['event_start_time'] = reset_datetime(array(
                        'datetime' => $insert_array['event_start_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:iA',
                        '_this' => $this,
                        'from_timezone' => $this->input->post('timezone'),
                        'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                    ));
                }

                if (isset($insert_array['event_end_time'])) {
                    $event_data['event_new_end_time'] = $insert_array['event_end_time'] = reset_datetime(array(
                        'datetime' => $insert_array['event_end_time'],
                        'from_format' => 'h:iA',
                        'format' => 'h:iA',
                        '_this' => $this,
                        'from_timezone' => $this->input->post('timezone'),
                        'new_zone' => STORE_DEFAULT_TIMEZONE_ABBR
                    ));
                }
            }
            // Added on: 02-05-2019
            // $this->send_email_to_event_creator($event_data);
            // Add record
            $resp = $this->cm->add_event_history($insert_array);
        }
        // Added on: 02-05-2019
        $this->send_email_to_event_creator($event_data);

        //cancel the actual event in case of event creator
        $diff_array_json = [];
        if ($this->input->post('type') == 'cannotattend' || $this->input->post('type') == 'reschedule') {
            $action = '';
            $event_sid = $this->input->post('event_id');
            $old_event_details = $this->cm->get_event_details($event_sid);
            if (sizeof($old_event_details)) {
                $old_event_status =  $old_event_details['event_status'];
            }
            $creator_sid = $this->cm->get_event_creator($event_sid, $old_event_details['employers_sid']);
            if ($this->input->post('user_type') == 'interviewer' && $creator_sid == $this->input->post('user_id')) {
                if ($this->input->post('type') == 'cannotattend') {
                    $diff_array_json['old_event_status'] = $old_event_status;
                    $diff_array_json['new_event_status'] = 'cancelled';

                    $action = 'cancel_event';
                    $this->cm->cancel_event($event_sid);
                    $event_category = $this->cm->get_event_column_by_event_id($event_sid, 'category');
                    // Check for training session
                    if ($event_category == 'training-session') {
                        $learning_center_training_sessions = $this->cm->get_event_column_by_event_id($event_sid, 'learning_center_training_sessions');
                        $this->cm->cancel_training_session(
                            $learning_center_training_sessions
                        );
                    }
                    $resp_array['Response'] = 'Event cancelled successfully';
                }
                if ($this->input->post('type') == 'reschedule') {
                    $diff_array_json['old_event_status'] = $old_event_status;
                    $diff_array_json['new_event_status'] = 'rescheduled';
                    $action = 'reschedule_event';
                    if ($this->calendar_model->update_event($event_sid, [
                        'date' => $event_data['event_new_date'],
                        'eventstarttime' => $event_data['event_new_start_time'],
                        'eventendtime' => $event_data['event_new_end_time'],
                        'event_status' => 'pending',
                        'employers_sid' => $creator_sid
                    ])) {
                        $event_date = DateTime::createFromFormat('Y-m-d', $event_data['event_new_date'])->format('F j, Y');
                        $resp_array['Response'] = 'Event updated successfully, it is scheduled on ' . $event_date;
                    }
                }
                // Handle event changes
                $data_array = array(
                    'event_sid' => $event_sid,
                    'user_id' => $creator_sid,
                    'company_sid' => $old_event_details['companys_sid'],
                    'ip_address' => $this->input->ip_address(),
                    'details' => @json_encode($diff_array_json)
                );
                // Insert data
                $this->calendar_model->_insert('portal_schedule_event_history', $data_array);

                ics_files($event_sid, $old_event_details['companys_sid'], get_company_details($old_event_details['companys_sid']), $action, array(), false, $diff_array_json);
            }
        }


        $resp_array = array('Status' => FALSE, 'Erase' => FALSE, 'Response' => $message);
        if ($resp) $resp_array['Status'] = TRUE;
        $this->response($resp_array);
    }

    /**
     * Set reponse 
     *  
     * @param $array Array 
     *  
     */
    private function response($array)
    {
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }

    /**
     * Send email to event creator
     * Created on: 02-05-2019
     *
     * @param $event_array Array
     *
     * @return Void
     **/
    private function send_email_to_event_creator($event_array)
    {
        $columns = "companys_sid,
                    title as event_title, 
                    employers_sid, 
                    users_name,
                    date_format(date, '%m-%d-%Y') as event_date, 
                    eventstarttime as event_start_time, 
                    eventendtime as event_end_time, 
                    event_status, 
                    CONCAT(UCASE(SUBSTRING(category, 1, 1)),SUBSTRING(category, 2)) as category_uc
               ";
        $columns = "companys_sid, 
                    event_status, 
                    employers_sid, 
                    date, 
                    eventstarttime as event_start_time, 
                    eventendtime as event_end_time";
        $event_detail_array = array(
            'columns' => $columns,
            'event_id' => $event_array['eid']
        );
        // Fetch event and 
        $event_data = $this->cm->get_event_detail($event_detail_array, false);
        //
        $to_timezone = $event_data['creator_employee']['timezone'];
        if (empty($to_timezone)) {
            $to_timezone = $this->cm->get_timezone('company', $event_data['companys_sid']);
        }
        //
        // $template = get_email_template(EVENT_STATUS_EMAIL_NOTIFICATION_FOR_CREATOR);
        //
        // $subject = $template['subject'];
        // $body    = $template['text'];

        // Added on: 09-05-2019
        // if($event_array['event_type'] == 'training-session')
        // $body = str_replace(' has requested to change the event status ', ' has changed the invitation status ', $body);

        // $template_hf = message_header_footer(
        //     $event_data['companys_sid'], 
        //     ucwords($event_data["company_name"])
        // );

        // $from_name = $template['from_name'];

        //
        if (!sizeof($event_data['creator_employee'])) {
            sendMail(
                FROM_EMAIL_NOTIFICATIONS,
                'mubashar.ahmed@egenienext.com',
                'Creator email failed',
                @json_encode($event_data)
            );
        }

        $replace_array = array(
            '{{first_name}}' => $event_data['creator_employee']['first_name'],
            '{{last_name}}' => $event_data['creator_employee']['last_name'],
            '{{applicant_name}}' => $event_array['name'],
            '{{event_status}}' => ucwords($event_array['type']),
            // '{{event_category}}' => $event_data['category_uc'],
            // '{{event_title}}' => '<strong>Title:</strong> '.$event_data['event_title'],
            // '{{event_date}}' => '<strong>Date:</strong> '.$event_data['event_date'],
            // '{{event_start_time}}' => '<strong>Start Time:</strong> '.$event_data['event_start_time'],
            // '{{event_end_time}}' => '<strong>End Time:</strong> '.$event_data['event_end_time'],
            // '{{company_name}}' => $event_data['company_name'],
            // '{{reason}}' => isset($event_array['reason']) ? '<strong>Reason:</strong> '.$event_array['reason'] : '',
            // '{{event_new_date}}' => isset($event_array['event_new_date']) ? '<strong>Requested Date:</strong> '.$event_array['event_new_date'] : '',
            // '{{event_new_start_time}}' => isset($event_array['event_new_start_time']) ? '<strong>Requested Start Time:</strong> '.$event_array['event_new_start_time'] : '',
            // '{{event_new_end_time}}' => isset($event_array['event_new_end_time']) ? '<strong>Requested End Time:</strong> '.$event_array['event_new_end_time'] : '',
            '{{event_link}}' => '',
            '{{WITH_INFO_BOX}}' => '',
            // '{{event_link}}' => base_url('calendar/my_events/'.$event_array['eid']),
            // '{{template_footer}}' => $template_hf['footer'],
            // '{{template_header}}' => $template_hf['header'],
            '{{EVENT_DATE}}' => reset_datetime(array(
                'datetime' => $event_data['date'] . $event_data['event_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'M d Y, D',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            )),
            '{{EVENT_START_TIME}}' => reset_datetime(array(
                'datetime' => $event_data['date'] . $event_data['event_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            )),
            '{{EVENT_END_TIME}}' => reset_datetime(array(
                'datetime' => $event_data['date'] . $event_data['event_end_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            )),
            '{{NEW_EVENT_DETAILS}}' => '',
            '{{NEW_DATE_HEADING}}' => '',
            '{{NEW_TIME_HEADING}}' => '',
            '{{NEW_EVENT_DATE}}' => '',
            '{{NEW_EVENT_TIME}}' => '',
            '{{EVENT_HEADING}}' => 'Confirmation Request!',
            '{{EVENT_TIMEZONE}}' => $to_timezone
        );

        if ($event_array['type'] == 'Reschedule') {
            $event_new_date = reset_datetime(array(
                'datetime' => $event_array['event_new_date'] . $event_array['event_new_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'M d Y, D',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            ));
            $event_new_start_time = reset_datetime(array(
                'datetime' => $event_array['event_new_date'] . $event_array['event_new_start_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            ));
            $event_new_end_time = reset_datetime(array(
                'datetime' => $event_array['event_new_date'] . $event_array['event_new_end_time'],
                'from_format' => 'Y-m-dh:iA',
                'format' => 'h:i A',
                'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'new_zone' => $to_timezone,
                '_this' => $this
            ));
            $new_event_details  = '';
            $new_event_details .= '<tr><td><p><strong>Reschedule Reason: </strong><br />' . $event_array['reason'] . '</p></td></tr>';
            $replace_array['{{NEW_EVENT_DETAILS}}']  = $new_event_details;
            $replace_array['{{NEW_DATE_HEADING}}'] = '<td><strong>Reschedule Request Date</strong></td>';
            $replace_array['{{NEW_TIME_HEADING}}'] = '<td><strong>Reschedule Request Time</strong></td>';
            $replace_array['{{NEW_EVENT_DATE}}'] = '<td><p>' . (date_with_time($event_new_date)) . '</p></td>';
            $replace_array['{{NEW_EVENT_TIME}}'] = '<td><p>' . $event_new_start_time . ' - ' . $event_new_end_time . ' <b>(' . $to_timezone . ')</b></p></td>';

            $replace_array['{{EVENT_HEADING}}'] = 'Reschedule Request!';
        }

        if ($event_array['type'] == 'Can Not Attend' && isset($event_array['reason'])) {
            $new_event_details = '<tr><td><p><strong>Cancellation Reason: </strong><br />' . $event_array['reason'] . '</p></td></tr>';
            $replace_array['{{NEW_EVENT_DETAILS}}']  = $new_event_details;
            $replace_array['{{EVENT_HEADING}}'] = 'Cancellation Request!';
        }


        $template = ics_files(
            $event_array['eid'],
            $event_data['companys_sid'],
            array('CompanyName' => $event_data['company_name'], 'requested_event_status' => $event_array['type']),
            'confirm',
            array(),
            true
        );


        $replace_array['{{WITH_INFO_BOX}}'] = $template['with_info_box'];

        $from_name = $template['FromEmail'];
        $subject = $template['Subject'];
        $body    = $template['Body'];

        //
        foreach ($replace_array as $k0 => $v0) {
            $from_name = str_replace($k0, $v0, $from_name);
            $subject = str_replace($k0, $v0, $subject);
            $body    = str_replace($k0, $v0, $body);
        }

        $uname = $event_data['creator_employee']['first_name'];
        $uemail = $event_data['creator_employee']['first_name'];
        $uid = $event_data['creator_employee']['sid'];
        $download_url_vcs = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $this->encrypt->encode('type=vcs&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=employee&uid=' . ($uid) . '&eid=' . $event_array['eid'])));
        $download_url_ics = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $this->encrypt->encode('type=ics&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=employee&uid=' . ($uid) . '&eid=' . $event_array['eid'])));
        $download_url_gc  = base_url('download-event') . '/' . (str_replace('/', '$eb$eb$1', $this->encrypt->encode('type=gc&uname=' . ($uname) . '&uemail=' . ($uemail) . '&utype=employee&uid=' . ($uid) . '&eid=' . $event_array['eid'])));
        $calendar_rows  = '<tr>';
        $calendar_rows .= '     <td><br /><strong>Calendar event</strong><br /><br /></td>';
        $calendar_rows .= '</tr>';
        $calendar_rows .= '<tr>';
        $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/outlook.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_vcs) . '">Add to Outlook Calendar</a></td>';
        $calendar_rows .= '</tr>';
        $calendar_rows .= '<tr>';
        $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/google.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_gc) . '">Add to Google Calendar</a></td>';
        $calendar_rows .= '</tr>';
        $calendar_rows .= '<tr>';
        $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/apple.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Add to Apple Calendar</a></td>';
        $calendar_rows .= '</tr>';
        $calendar_rows .= '<tr>';
        $calendar_rows .= '     <td><img src="' . (base_url('assets/calendar_icons/calendar.png')) . '" width="20"/>&nbsp;&nbsp;<a href="' . ($download_url_ics) . '">Mobile & Other Calendar</a></td>';
        $calendar_rows .= '</tr>';
        $calendar_rows .= '<style>.dash-inner-block td, .dash-inner-block th{ border-bottom: none;}</style>';
        // Replace calendar rows
        $body = str_replace('{{CALENDAR_ROWS}}', $calendar_rows, $body);
        // _e($body, true, true);
        $this->log_and_send_email(
            FROM_EMAIL_NOTIFICATIONS,
            $event_data['creator_employee']['email_address'],
            $subject,
            $body,
            $from_name
        );
        // _e($body, true, true);
    }

    /**
     * Send and save email
     * Created on: 02-05-2019
     *
     * @param $from String
     * @param $to String
     * @param $subject String
     * @param $body String
     * @param $senderName String
     *
     *
     * @return Void
     */
    private function log_and_send_email($from, $to, $subject, $body, $senderName)
    {
        $emailData = array(
            'date' => date('Y-m-d H:i:s'),
            'subject' => $subject,
            'email' => $to,
            'message' => $body,
            'username' => $senderName
        );
        //
        save_email_log_common($emailData);
        //
        if (base_url() != STAGING_SERVER_URL) sendMail($from, $to, $subject, $body, $senderName);
    }

    /**
     * Download calendar page
     * Created on: 27-05-2019
     * 
     * @param $token String 
     * Accepts base64 encoded string
     *
     * @return VOID
     */
    function download_event($token = null)
    {
        if ($token == null) show_404();
        // Clean $token
        $token = str_replace('$eb$eb$1', '/', trim(strip_tags(str_replace('%24', '$', $token))));
        // Load encryption library
        $this->load->library('encrypt');
        // Decode token
        $dec_token = $this->encrypt->decode($token);
        if (empty($dec_token)) show_404();

        // Parse decoded string
        $detail_array = explode('&', $dec_token);
        // 
        if (!sizeof($detail_array)) show_404();
        //
        $event_array = array();
        foreach ($detail_array as $k0 => $v0) {
            $tmp = explode('=', $v0);
            $event_array[$tmp[0]] = $tmp[1];
        }
        // Double check indexes
        if (!isset(
            $event_array['eid'],
            $event_array['type']
        )) {
            show_404();
        }
        // Loads calendar modal
        $this->load->model('calendar_model', 'cm');

        // Get user timezone
        $user_details = array();
        if (isset($event_array['utype']) && $event_array['utype'] == 'applicant') {
            $user_details = $this->cm->get_applicant_detail($event_array['uid']);
        } else if (isset($event_array['utype']) && ($event_array['utype'] == 'employee' || $event_array['utype'] == 'interviewer' || $event_array['utype'] == 'personal')) {
            $user_details = $this->cm->get_employee_detail($event_array['uid']);
        } else {
            $user_details = $this->cm->get_employee_detail($this->cm->get_event_column_by_event_id($event_array['eid'], 'companys_sid'));
        }

        if (empty($user_details['timezone'])) {
            $user_details['timezone'] = $this->cm->get_employee_detail($this->cm->get_event_column_by_event_id($event_array['eid'], 'companys_sid'))['timezone'];
            if (empty($user_details['timezone'])) $user_details['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
        }

        $data['utype'] = $event_array['utype'];
        $data['uname'] = $event_array['uname'];
        $data['uemail'] = $event_array['uemail'];
        $data['uid'] = $event_array['uid'];
        $data['type'] = $event_array['type'];
        $data['event_sid'] = $event_array['eid'];
        //        
        switch ($event_array['type']) {
            case 'gc':
                $event_details = $this->cm->get_event_detail_for_frontend($event_array['eid']);
                if (!empty($event_details['event_timezone']) && $data['utype'] != 'employee' && $data['utype'] != 'interviewer') {
                    $user_details['timezone'] = $event_details['event_timezone'];
                }
                // if ($event_details['users_type'] != 'personal'){
                //     $detail_type = $event_details['users_type'] == 'applicant' ? 'get_applicant_detail' : 'get_employee_detail';
                //     $user_info = $this->cm->$detail_type($event_details['applicant_job_sid']);
                // }

                $event_category = reset_category(strtolower($event_details['category_uc']));

                $event_title = "{$event_category} : " . $event_details['title'];

                $new_start_time = reset_datetime(array(
                    'datetime' => $event_details['event_date_ac'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'His',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_details['timezone'],
                    '_this' => $this
                ));


                $new_end_time = reset_datetime(array(
                    'datetime' => $event_details['event_date_ac'] . $event_details['event_end_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'His',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_details['timezone'],
                    '_this' => $this
                ));
                $event_details['event_date_gc'] = reset_datetime(array(
                    'datetime' => $event_details['event_date_ac'] . $event_details['event_start_time'],
                    'from_format' => 'Y-m-dh:iA',
                    'format' => 'Ymd',
                    'from_timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'new_zone' => $user_details['timezone'],
                    '_this' => $this
                ));
                // _e($event_details['event_start_time'], true);
                // _e($new_start_time, true);
                // _e($user_details, true);
                // _e($event_array, true, true);
                //print_r($event_details);exit;
                $event_end_date_gc = $event_details['event_date_gc'];

                $eStartDateTimeFull = date('Ymd H:i:s', strtotime($event_details['event_date_gc'] . " " . $new_start_time));
                $eEndDateTimeFull = date('Ymd H:i:s', strtotime($event_details['event_date_gc'] . " " . $new_end_time));
                if ($eStartDateTimeFull > $eEndDateTimeFull)
                    $event_end_date_gc = date('Ymd', strtotime($event_end_date_gc . ' +1 day'));
                // Set dates
                $dates = "" . ($event_details['event_date_gc']) . "T{$new_start_time}/" . ($event_end_date_gc) . "T{$new_end_time}";

                $ss = "\n";
                $ds = "\n\n";
                // Set details
                $details = '';
                $details .= "Event Type:{$ss}";
                $details .= "{$event_category}{$ss}";
                $details .= $ds;

                $user_sid = $event_details['applicant_job_sid'];
                $event_type = $event_details['users_type'];
                $event_sid  = $event_array['eid'];
                $user_name  = $user_details['first_name'] . " " . $user_details['last_name'];
                $user_email = $user_details['email'];
                //
                $base_url = base_url() . 'event/';
                // Set event code string
                $string_conf = 'id=' . $event_array['uid'] . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=confirmed:name=' . $event_array['uname'] . ':email=' . $event_array['uemail'];
                $string_notconf = 'id=' . $event_array['uid'] . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=notconfirmed:name=' . $event_array['uname'] . ':email=' . $event_array['uemail'];
                $string_reschedule = 'id=' . $event_array['uid'] . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=reschedule:name=' . $event_array['uname'] . ':email=' . $event_array['uemail'];
                //
                if (strtolower($event_details['category_uc']) == 'training-session')
                    $string_attended = 'id=' . $event_array['uid'] . ':eid=' . $event_sid . ':etype=' . $event_type . ':type=attended:name=' . $event_array['uname'] . ':email=' . $event_array['uemail'];
                // Set encoded string
                $enc_string_conf = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_conf));
                //
                if (strtolower($event_details['category_uc']) == 'training-session')
                    $enc_string_attended = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_attended));
                //
                $enc_string_notconf  = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_notconf));
                $enc_string_reschedule = $base_url . str_replace('/', '$eb$eb$1', $this->encrypt->encode($string_reschedule));

                $details .= "Event Links:{$ss}";

                if (strtolower($event_details['category_uc']) == 'training-session') {
                    $details .= "Attended: {$enc_string_attended}{$ss}";
                }

                $details .= "Confirm: {$enc_string_conf}{$ss}";
                $details .= "Reschedule: {$enc_string_reschedule}{$ss}";
                $details .= "Cannot Attend: {$enc_string_notconf}{$ss}";

                if (sizeof($event_details['interviewers'])) {
                    $details .= $ds;
                    $details .= ($event_details['users_type'] == 'applicant' ? 'Interviewer(s)' : 'Participant(s)') . ":{$ss}";
                    foreach ($event_details['interviewers'] as $k0 => $v0) {
                        $details .= "&#8277;&nbsp;" . $v0['value'] . "" . ($v0['show_email'] == 1 ? '(' . ($v0['email_address']) . ')' : '') . "{$ss}";
                    }
                }

                $details .= $ds;
                $details .= "Company:{$ss}";
                $details .= "{$event_details['company_name']}{$ss}";

                $url = "https://calendar.google.com/calendar/r/eventedit?";
                $url .= "text=" . (urlencode($event_title)) . "&";
                $url .= "dates={$dates}&";
                $url .= "location=" . urlencode($event_details['address']) . "&";
                $url .= "details=" . (urlencode($details)) . "&";
                $url .= "trp=true&";
                $url .= "sf=true";

                redirect($url, 'location', 301);
                break;
            default:
                $data['type'] = $event_array['type'];
                break;
        }

        $company_id = $this->cm->get_event_column_by_event_id($event_array['eid'], 'companys_sid');
        $company_name = $this->cm->get_event_column_by_event_id($event_array['eid'], 'CompanyName');
        $data['company_template_header_footer'] = message_header_footer($company_id, ucwords($company_name));
        // $data['company_template_header_footer'] = array('header' => '', 'footer' => '');
        $data['title'] = 'Download event calendar';
        $this->load
            ->view('calendar/header', $data)
            ->view('calendar/download-page')
            ->view('calendar/footer');
    }

    /**
     * Download file
     * Created on: 27-05-2019
     *
     * @param $type String (ics|vcs)
     * @param $event_sid Integer
     * @param $user_id Integer
     * @param $user_type String
     * @param $user_name String
     * @param $user_email String
     *
     * @return VOID
     */
    function download($type, $event_sid, $user_id = 0, $user_type = 'extrainterviewer', $user_name = '', $user_email = '')
    {
        $user_name = urldecode($user_name);
        $user_email = urldecode($user_email);
        $destination = APPPATH . '../assets/ics_files/';
        if ($type == 'ics')
            $download_file = generate_ics_file_for_event($destination, $event_sid, false, $user_id, $user_type, $user_name, $user_email);
        else
            $download_file = generate_vcs_file_for_event($destination, $event_sid, $user_id, $user_type, $user_name, $user_email);

        if (file_exists($download_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($download_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($download_file));
            readfile($download_file);
        }

        exit(0);
    }

    /**
     * Handles event status requests
     * Created on: 24-06-2019
     *
     * @param $token String
     *
     * @return Void
     */
    function event_detail($token = null)
    {
        if ($token == null) redirect('login', "refresh");
        // Clean $token
        $token = str_replace('$eb$eb$1', '/', trim(strip_tags(str_replace('%24', '$', $token))));
        // Load encryption library
        $this->load->library('encrypt');
        // Decode token
        $dec_token = $this->encrypt->decode($token);
        if (empty($dec_token)) show_404();
        // Parse decoded string
        $detail_array = explode(':', $dec_token);
        // 
        if (!sizeof($detail_array)) show_404();
        //
        $event_array = array();
        foreach ($detail_array as $k0 => $v0) {
            $tmp = explode('=', $v0);
            $event_array[$tmp[0]] = $tmp[1];
        }

        // Double check indexes
        if (!isset(
            $event_array['id'],
            $event_array['eid'],
            $event_array['etype'],
            $event_array['name'],
            $event_array['email'],
            $event_array['type']
        )) {
            show_404();
        }
        // Tmp check
        $event_array['type'] = $event_array['type'] == 'external_user' ? 'external user' : $event_array['type'];
        //
        // Loads calendar modal
        $this->load->model('manage_admin/dashboard_model', 'cm');
        $event_exists = $this->cm->get_event_column_by_event_id($event_array['eid'], 'sid', false);
        if (empty($event_exists)) {
            $this->load->view('errors/html/error_event_not_found');
            return;
        }
        // Check if not set to false
        if (MAX_EVENT_HISTORY_ENTRIES) {
            $entries = $this->cm->check_event_history_limit(
                $event_array['eid'],
                $event_array['id']
            );
            if ($entries >= MAX_EVENT_HISTORY_ENTRIES) $data['error_msg'] = 'Error! You have reached maximum entries for this event.';
        }
        // Check for event expire
        $event_date = $this->cm->get_event_column_by_event_id($event_array['eid'], 'event_date', true);
        // 
        if (strtotime('now') > $event_date) {
            $data['error_msg'] = 'Error! The event is expired.';
        }
        // Get 
        $data['event_type'] = $event_type = $this->cm->get_event_column_by_event_id($event_array['eid'], 'event_category');
        //

        // Change status on confirm
        if (!isset($data['error_msg']) && $event_array['status'] == 'confirmed') {
            // Add record
            $this->cm->_q(
                'admin_event_history',
                array(
                    'event_sid' => $event_array['eid'],
                    'user_sid'  => $event_array['id'],
                    'user_name' => $event_array['name'],
                    'user_email' => $event_array['email'],
                    'user_type' => strtolower($event_array['type']),
                    'event_status' => 'confirmed',
                    'created_at' => date('Y-m-d H:i:s')
                )
            );
        }
        $data['event_array'] = $event_array;
        //
        $event_array['event_type'] = $event_type;
        // Added on: 02-05-2019
        // Updated on: 09-05-2019
        if (!isset($data['error_msg']) && $event_array['status'] == 'confirmed') $this->send_email_to_admin_event_creator($event_array, true);

        // get event details
        $data['event_details'] = $this->cm->event_detail($event_array['eid']);

        $data['company_template_header_footer'] = array(
            'header' => EMAIL_HEADER,
            'footer' => EMAIL_FOOTER
        );
        error_reporting(0);
        //
        $data['title'] = 'Event';
        $this->load
            ->view('manage_admin/calendar/header', $data)
            ->view('manage_admin/calendar/event-page')
            ->view('manage_admin/calendar/footer');
    }

    /**
     * Calendar event handler
     *  
     * Accepts POST 
     *  
     * @return JSON 
     *  
     */
    function admin_event_handler()
    {
        // Check if direct access made
        if ($this->input->server('REQUEST_METHOD') == 'GET') redirect('calendar/my_events', 'refresh');
        // Load calendar modal
        $this->load->model('manage_admin/dashboard_model', 'cm');
        $type = $this->input->post('type');
        $message = '';
        // Check if not set to false
        if (MAX_EVENT_HISTORY_ENTRIES) {
            $entries = $this->cm->check_event_history_limit(
                $this->input->post('event_id'),
                $this->input->post('user_id')
            );
            if ($entries >= MAX_EVENT_HISTORY_ENTRIES) $this->response(array('Status' => FALSE, 'Erase' => TRUE, 'Response' => 'You have reaached maximum entries for this event.'));
        }
        // Check for event expire
        $event_date = $this->cm->get_event_column_by_event_id($this->input->post('event_id'), 'event_date', true);
        // 
        if (strtotime('now') > $event_date)
            $this->response(array('Status' => FALSE, 'Erase' => TRUE, 'Response' => 'Error! The event has been expired.'));

        $insert_array =
            array(
                'event_sid' => $this->input->post('event_id'),
                'user_sid'  => $this->input->post('user_id'),
                'user_name' => $this->input->post('user_name'),
                'user_email' => $this->input->post('user_email'),
                'user_type' => $this->input->post('user_type'),
                'event_status' => 'cannotattend',
                'created_at' => date('Y-m-d H:i:s')
            );
        // _e($this->input->post('cat'), true, true);
        //
        $event_data = array(
            'eid' => $insert_array['event_sid'],
            'name' => $insert_array['user_name'],
            'type' => 'Can Not Attend',
            'event_type' => $this->input->post('cat')
        );

        if ($this->input->post('cat') == 'training-session' && $this->input->post('type') == 'cannotattend') {
            // Change user status
            // $this->cm->update_lc_user_status(
            //     $this->input->post('lcid'),
            //     $this->input->post('user_id'),
            //     array( 
            //         'attend_status'=> 'unable_to_attend', 
            //         'txt_reason' => $this->input->post('event_reason') ? $this->input->post('event_reason') : NULL 
            //     )
            // );
            //
            $event_data['type'] = 'cancelled';
            $message = 'Your have cancelled the event.';
            $this->cm->_q('admin_event_history', $insert_array);
        } else {
            //
            if ($this->input->post('event_reason')) $event_data['reason'] = $insert_array['reason'] = $this->input->post('event_reason');
            $message = 'Your Interview cancellation request has been received.';
            //
            if ($type == 'reschedule') {
                $message = 'Your Interview reschedule request has been received.';
                $insert_array['event_status'] = 'reschedule';
                $event_data['type'] = 'Reschedule';
                //
                if ($this->input->post('event_date')) $event_data['event_new_date'] = $insert_array['event_date'] = DateTime::createFromFormat('m-d-Y', $this->input->post('event_date'))->format('Y-m-d');
                if ($this->input->post('event_start_time')) $event_data['event_new_start_time'] = $insert_array['event_start_time'] = DateTime::createFromFormat('h:iA', $this->input->post('event_start_time'))->format('h:i A');
                if ($this->input->post('event_end_time')) $event_data['event_new_end_time'] = $insert_array['event_end_time'] = DateTime::createFromFormat('h:iA', $this->input->post('event_end_time'))->format('h:i A');
            }
            // Added on: 02-05-2019
            // $this->send_email_to_event_creator($event_data);
            // Add record
            $this->cm->_q('admin_event_history', $insert_array);
        }
        // Added on: 02-05-2019
        $this->send_email_to_admin_event_creator($event_data);
        $resp_array = array('Status' => FALSE, 'Erase' => FALSE, 'Response' => $message);
        $resp_array['Status'] = TRUE;
        $this->response($resp_array);
    }

    /**
     * Send email to event creator
     * Created on: 24-06-2019
     *
     * @param $event_array Array
     *
     * @return Void
     **/
    private function send_email_to_admin_event_creator($event_array, $type = false)
    {
        $this->load->model('manage_admin/dashboard_model', 'cm');
        // Fetch event and 
        $event = $this->cm->event_detail($event_array['eid']);

        $event['url_data'] = $event_array;

        if ($type) {
            $event['url_data']['type'] = 'confirmed';
        }

        //
        $replace_array = array();
        $replace_array['{{CALENDAR_ROWS}}'] = '';
        $replace_array['{{COMPANY_NAME}}'] = '&nbsp;';
        $replace_array['{{TO_USER_NAME}}'] = ucwords($event['creator_first_name'] . ' ' . $event['creator_last_name']);
        $replace_array['{{REQUESTOR_NAME}}'] = ucwords($event['url_data']['name']);
        $replace_array['{{REQUESTED_EVENT_STATUS}}'] = strtolower($event['url_data']['type']) == 'can not attend' ? 'Cancelled' : $event['url_data']['type'];
        $replace_array['{{REQUESTED_EVENT_DATE}}'] = $replace_array['{{NEW_DATE_HEADING}}'] =
            $replace_array['{{NEW_TIME_HEADING}}'] = $replace_array['{{REQUESTED_EVENT_TIME}}']
            = $replace_array['{{REQUEST_REASON}}'] = '';
        $replace_array['{{COMMENT_BOX}}'] = '';
        $replace_array['{{EVENT_HEADING}}'] = sft('<h3 style="text-align: center;">Event Confirmed!</h3>');

        $subject = reset_category($event['event_category']) . ' - Confirmed';

        $replace_array['{{REQUESTOR_SEPARATOR}}'] = 'has ';

        if ($event_array['type'] == 'Reschedule') {
            $new_event_details  = '';
            $new_event_details .= '<tr><td><p><strong>Reschedule Reason: </strong><br />' . $event_array['reason'] . '</p></td></tr>';
            $replace_array['{{NEW_EVENT_DETAILS}}']  = $new_event_details;
            $replace_array['{{NEW_DATE_HEADING}}'] = '<strong>Reschedule Request Date</strong>';
            $replace_array['{{NEW_TIME_HEADING}}'] = '<strong>Reschedule Request Time</strong>';
            $replace_array['{{REQUESTED_EVENT_DATE}}'] = '<p>' . (date_with_time($event_array['event_new_date'])) . '</p>';
            $replace_array['{{REQUESTED_EVENT_TIME}}'] = '<p>' . $event_array['event_new_start_time'] . ' - ' . $event_array['event_new_end_time'] . '</p>';

            $replace_array['{{EVENT_HEADING}}'] = sft('<h3 style="text-align: center;">Reschedule Request!</h3>');
            $replace_array['{{REQUEST_REASON}}'] = sft('<strong>Reschedule Reason.</strong>') . sft($event_array['reason']);
            $subject = reset_category($event['event_category']) . ' - Reschedule Requested!';
        }
        if ($event_array['type'] == 'Can Not Attend' && isset($event_array['reason'])) {
            $new_event_details = '<tr><td><p><strong>Cancellation Reason: </strong><br />' . $event_array['reason'] . '</p></td></tr>';
            $replace_array['{{NEW_EVENT_DETAILS}}']  = $new_event_details;
            $replace_array['{{EVENT_HEADING}}'] = sft('<h3 style="text-align: center;">Cancellation Request!</h3>');
            $replace_array['{{REQUEST_REASON}}'] = sft('<strong>Cancellation Reason.</strong>') . sft($event_array['reason']);
            $replace_array['{{REQUESTOR_SEPARATOR}}'] = ', ';
            //
            $subject = reset_category($event['event_category']) . ' - Cancellation Requested!';
        }


        $template = send_admin_calendar_email_template($event, 'confirm');
        //
        foreach ($replace_array as $k0 => $v0) {
            $template = str_replace($k0, $v0, $template);
        }

        $this->log_and_send_email(
            FROM_EMAIL_NOTIFICATIONS,
            $event['creator_email_address'],
            $subject,
            $template,
            FROM_EMAIL_NOTIFICATIONS
        );
        // _e($body, true, true);
    }

    /**
     * Download calendar page
     * Created on: 24-056-2019
     * 
     * @param $token String 
     * Accepts base64 encoded string
     *
     * @return VOID
     */
    function download_event_file($token = null)
    {
        if ($token == null) show_404();
        // Clean $token
        $token = str_replace('$eb$eb$1', '/', trim(strip_tags(str_replace('%24', '$', $token))));
        // Load encryption library
        $this->load->library('encrypt');
        // Decode token
        $dec_token = $this->encrypt->decode($token);
        if (empty($dec_token)) show_404();

        // Parse decoded string
        $detail_array = explode('&', $dec_token);
        // 
        if (!sizeof($detail_array)) show_404();
        //
        $event_array = array();
        foreach ($detail_array as $k0 => $v0) {
            $tmp = explode('=', $v0);
            $event_array[$tmp[0]] = $tmp[1];
        }
        // Double check indexes
        if (!isset(
            $event_array['eid'],
            $event_array['type']
        )) {
            show_404();
        }

        $data['type'] = $event_array['type'];
        $data['event_sid'] = $event_array['eid'];
        // Loads calendar modal
        $this->load->model('manage_admin/dashboard_model', 'cm');
        //
        switch ($event_array['type']) {
            case 'gc':
                $event = $this->cm->event_detail($event_array['eid']);
                $event_category = reset_category($event['event_category']);

                $event_title = "{$event_category} : " . $event['event_title'];

                $new_start_time = DateTime::createFromFormat(
                    'h:i A',
                    $event['event_start_time']
                )->format('His');

                $new_end_time = DateTime::createFromFormat(
                    'h:i A',
                    $event['event_end_time']
                )->format('His');
                $event['event_date_gc'] = DateTime::createFromFormat(
                    'Y-m-d',
                    $event['event_date']
                )->format('Ymd');
                // Set dates

                $dates = "" . ($event['event_date_gc']) . "T{$new_start_time}/" . ($event['event_date_gc']) . "T{$new_end_time}";

                $ss = "\n";
                $ds = "\n\n";
                // Set details
                $details = '';
                $details .= "Event Type:{$ss}";
                $details .= "{$event_category}{$ss}";
                $details .= $ds;

                // $user_sid   = $event['user_id'];
                // $event_type = $event['users_type'];
                // $event_sid  = $event_array['eid'];
                // $user_name  = $user['first_name']." ".$user['last_name'];
                // $user_email = $user['email'];
                // //
                // $base_url = base_url().'event-detail/';
                // // Set event code string
                // $string_conf = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=confirmed:name='.$user_name.':email='.$user_email;
                // $string_notconf = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=notconfirmed:name='.$user_name.':email='.$user_email;
                // $string_reschedule = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=reschedule:name='.$user_name.':email='.$user_email;
                // //
                // if(strtolower($event['category_uc']) == 'training-session')
                //     $string_attended = 'id='.$user_sid.':eid='.$event_sid.':etype='.$event_type.':type=attended:name='.$user_name.':email='.$user_email;
                // // Set encoded string
                // $enc_string_conf = $base_url.str_replace( '/', '$eb$eb$1', $this->encrypt->encode($string_conf));
                // //
                // if(strtolower($event['category_uc']) == 'training-session')
                //     $enc_string_attended = $base_url.str_replace( '/', '$eb$eb$1', $this->encrypt->encode($string_attended));
                // //
                // $enc_string_notconf  = $base_url.str_replace( '/', '$eb$eb$1', $this->encrypt->encode($string_notconf));
                // $enc_string_reschedule = $base_url.str_replace( '/', '$eb$eb$1', $this->encrypt->encode($string_reschedule));

                // $details .= "Event Links:{$ss}";

                // if(strtolower($event['category_uc']) == 'training-session'){
                //     $details .= "Attended: {$enc_string_attended}{$ss}";
                // }

                // $details .= "Confirm: {$enc_string_conf}{$ss}";
                // $details .= "Reschedule: {$enc_string_reschedule}{$ss}";
                // $details .= "Cannot Attend: {$enc_string_notconf}{$ss}";

                if (sizeof($event['participants']) && isset($event['participants'][0]['first_name'])) {
                    $details .= $ds;
                    $details .= $event['event_type'] == 'training-session' ? 'Attendee(s)' : 'Participant(s)';
                    $details .= $ds;
                    foreach ($event['participants'] as $k0 => $v0) {
                        $details .= "&#8277;&nbsp;" . (ucwords($v0['first_name'] . ' ' . $v0['last_name'])) . "" . ($v0['show_email'] == 1 ? '(' . ($v0['email_address']) . ')' : '') . "{$ss}";
                    }
                    if (sizeof($event['external_participants'])) {
                        foreach ($event['external_participants'] as $k0 => $v0) {
                            $details .= "&#8277;&nbsp;" . ($v0['external_participant_name']) . "" . ($v0['show_email'] == 1 ? '(' . ($v0['external_participant_email']) . ')' : '') . "{$ss}";
                        }
                    }
                }

                $url = "https://calendar.google.com/calendar/r/eventedit?";
                $url .= "text=" . (urlencode($event_title)) . "&";
                $url .= "dates={$dates}&";
                $url .= "location=" . urlencode($event['event_address']) . "&";
                $url .= "details=" . (urlencode($details)) . "&";
                $url .= "trp=true&";
                $url .= "sf=true";


                redirect($url, 'location');
                break;
            default:
                $data['type'] = $event_array['type'];
                break;
        }



        $data['company_template_header_footer'] = array(
            'header' => EMAIL_HEADER,
            'footer' => EMAIL_FOOTER
        );
        $data['title'] = 'Download event calendar';
        $this->load
            ->view('manage_admin/calendar/header', $data)
            ->view('manage_admin/calendar/download-page')
            ->view('manage_admin/calendar/footer');
    }

    /**
     * Download file
     * Created on: 27-05-2019
     *
     * @param $type String (ics|vcs)
     * @param $event_sid Integer
     *
     * @return VOID
     */
    function download_file($type, $event_sid)
    {
        $destination = APPPATH . '../assets/ics_files/';
        //
        $this->load->model('manage_admin/dashboard_model');
        //
        $event = $this->dashboard_model->event_detail($event_sid);
        if ($type == 'ics')
            $download_file = generate_admin_ics_file($event, false);
        else
            $download_file = generate_admin_vcs_file_for_event($event_sid);

        if (file_exists($download_file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($download_file) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($download_file));
            readfile($download_file);
        }

        exit(0);
    }

    /**
     *
     *
     */
    public function event_reminder_cron($vf_key = null)
    {
        if ($vf_key != 'dwwbtQzuoHI9d5TEIKBKDGWwgoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk5HV7ZNCbu2ZfkjFVLHWINnM5uzMkUfIiINdZ19NJj') return false;

        $this->load->model('manage_admin/dashboard_model');
        $today_events = $this->dashboard_model->fetch_all_today_events();
        if (!$today_events) return false;

        foreach ($today_events as $event) {
            //
            $cur_time_duration = date('H:i', strtotime('+' . $event['reminder_duration'] . ' minutes', strtotime(date('H:i'))));
            $event_start_time = date('H:i', strtotime($event['event_start_time']));

            // Check the time
            if (strtotime($cur_time_duration) == strtotime($event_start_time)) {
                $sent_to = array();
                //
                $event = $this->dashboard_model->event_detail($event['sid']);
                // Updated on: 25-06-2019
                // Fetch interviews, non-employee interviewers, applicant || employee
                // data
                $email_list = array();

                if ($event['event_type'] != 'personal') {
                    // For Participants
                    if (sizeof($event['participants'])) {
                        foreach ($event['participants'] as $k0 => $v0) {
                            $sent_to[] = $v0['email_address'];
                            $email_list[] = array(
                                'id' => $v0['id'],
                                'type' => 'interviewer',
                                'value' => ucwords($v0['first_name'] . ' ' . $v0['last_name']),
                                'email_address' => $v0['email_address']
                            );
                        }
                    }

                    // For External Participants
                    if (sizeof($event['external_participants'])) {
                        foreach ($event['external_participants'] as $k0 => $v0) {
                            $sent_to[] = $v0['external_participant_email'];
                            $email_list[] = array(
                                'id' => 0,
                                'type' => 'non-employee interviewer',
                                'value' => ucwords($v0['external_participant_name']),
                                'email_address' => $v0['external_participant_email']
                            );
                        }
                    }

                    //
                    if (isset($event['email_address'])) {
                        $sent_to[] = $event['email_address'];
                        $email_list[] = array(
                            'id' => $event['user_id'],
                            'type' => $event['user_type'],
                            'value' => ucwords($event['first_name'] . ' ' . $event['last_name']),
                            'email_address' => $event['email_address']
                        );
                    }
                } else {
                    $sent_to[] = $event['creator_email_address'];
                    $email_list[] = array(
                        'id' => $event['user_id'],
                        'type' => $event['user_type'],
                        'value' => ucwords($event['creator_first_name'] . ' ' . $event['creator_last_name']),
                        'email_address' => $event['creator_email_address']
                    );
                }

                //
                if (!sizeof($email_list)) continue;

                // Generate ICS file
                $event['ics_file'] = generate_admin_ics_file($event, false);
                //
                $return = send_admin_calendar_email_template(
                    $event,
                    'send_cron_reminder_emails',
                    $email_list
                );
                sendMail(
                    'notifications@automotohr.com',
                    'dev@automotohr.com',
                    'Auto Calendar Event Reminder executed',
                    'it is auto executed at ' . date('Y-m-d H:i:s') . '/n<br>Reminder sent to: ' . implode(',', $sent_to),
                    'AutomotoHR',
                    'dev@automotohr.com'
                );

                // Update 
                $this->dashboard_model->_q(
                    'admin_events',
                    array('reminder_sent_flag' => 1),
                    array('sid' => $event['event_sid']),
                    'update'
                );
                echo 'Done';
            }
        }
    }

    /**
     * Receives
     * Created on: 17-07-2019
     * 
     * @return JSON
     */
    function receive_request()
    {
        if (!sizeof($this->input->post())) exit(0);
        // Load twilio
        $this->load->library('twilio/twilioApp', null, 'twilio');
        $this->load->model('manage_admin/sms_model');
        //
        $resp = $this->twilio->receiveRequest();
        // Error handling
        if (!is_array($resp)) {
            _e('Throw an error', true, true);
        }
        if (isset($resp['Error'])) {
            _e('Throw an error', true, true);
        }

        // Get last row and insert data
        // Get the last record matching query from db
        $result_arr = $this->sms_model->get_last_sms_row(
            $resp['DataArray']['sender_phone_number'],
            $resp['URL']['cid'],
            'MG359e34ef1e42c763d3afc96c5ff28eaf'
        );
        //
        if (!$result_arr || !sizeof($result_arr)) exit(0);

        // Set insert array
        $insert_array = $resp['DataArray'];
        //
        if ($resp['URL']['cid'] != 0) {
            $insert_array['company_id'] = $resp['URL']['cid'];
            $insert_array['sender_user_id'] = $result_arr['receiver_user_id'];
            $insert_array['sender_user_type'] = $result_arr['receiver_user_type'];
        } else {
            $insert_array['sender_user_id'] = NULL;
            $insert_array['sender_user_type'] = NULL;
        }
        $insert_array['message_body'] = $resp['DataArray']['message_body'];
        $insert_array['module_slug']  = $resp['URL']['module'];
        $insert_array['message_mode']  = $result_arr['message_mode'];
        $insert_array['message_sid']  = $resp['DataArray']['message_sid'];
        $insert_array['receiver_user_id'] = $result_arr['sender_user_id'];
        $insert_array['receiver_user_type'] = $result_arr['sender_user_type'];
        // Insert data into database
        $insert_id = $this->sms_model->_insert('portal_sms', $insert_array);
        //
        _e($insert_id, true, true);
    }

    /**
     * SMS cron
     * TOBE delted after testing
     *
     */
    function sms_cron($module = 'admin')
    {
        // Fetch all SMS
        $this->load->model('manage_admin/sms_model');
        $this->load->library('twilio/Twilioapp', null, 'twilio');
        $sms = $this->sms_model->fetch_last_sent_sms($module);

        // _e($sms, true, true);
        //
        if (!$sms && !sizeof($sms)) exit(0);
        $this
            ->twilio
            ->setMode('production');
        //
        foreach ($sms as $k0 => $v0) {
            $resp = $this
                ->twilio
                ->fetchMessagesList(
                    array(
                        "dateSentAfter" => new DateTime($v0['created_at']),
                        "to" => $v0['sender_phone_number'],
                        "from" => $v0['receiver_phone_number']
                    ),
                    40
                );

            if (isset($resp['Error'])) continue;
            // Loop through data
            foreach ($resp as $k1 => $v1) {
                _e($resp, true);
                continue;
                // Check in db
                if ($this->sms_model->check_message_sid($v1['Sid']) != 0) continue;
                $insert_array = array();
                $insert_array['replied_at'] = $v1['SentAt'];
                $insert_array['message_sid'] = $v1['Sid'];
                $insert_array['message_body'] = $v1['Body'];
                $insert_array['message_service_sid'] = $v1['MessageServiceSid'];
                $insert_array['sender_phone_number'] = $v1['From'];
                $insert_array['receiver_phone_number'] = $v1['To'];

                $insert_array['message_mode'] = 'production';
                $insert_array['module_slug'] = $module;
                $insert_array['receiver_user_id'] = $v0['sender_user_id'];
                $insert_array['receiver_user_type'] = $v0['sender_user_type'];
                //
                if ($module == 'admin') $insert_array['sender_user_type'] = 'admin';
                else {
                    $insert_array['sender_user_ide'] = $v0['receiver_user_id'];
                    $insert_array['sender_user_type'] = $v0['receiver_user_type'];
                }
                $insert_array['is_sent'] = 0;

                $insert_id = $this->sms_model->_insert('portal_sms', $insert_array);
            }
        }
        exit(0);
    }

    /**
     * Update phone number and authorize
     * Created on: 01-08-2019
     * 
     * @param $token String 
     * Accepts base64 encoded string
     *
     * @return VOID
     */
    function update_phonenumber($token = null)
    {
        if ($token == null) show_404();
        // Clean $token
        $token = str_replace('$eb$eb$1', '/', trim(strip_tags(str_replace('%24', '$', $token))));
        // Load encryption library
        $this->load->library('encrypt');
        // Decode token
        $dec_token = $this->encrypt->decode($token);
        if (empty($dec_token)) show_404();
        // Parse decoded string
        $detail_array = explode(':', $dec_token);
        // 
        if (!sizeof($detail_array)) show_404();
        //
        $event_array = array();
        foreach ($detail_array as $k0 => $v0) {
            $tmp = explode('=', $v0);
            $event_array[$tmp[0]] = $tmp[1];
        }
        // Double check indexes
        if (!isset(
            $event_array['id'],
            $event_array['type'],
            $event_array['cid'],
            $event_array['cname']
        )) {
            show_404();
        }

        if (!$this->home_model->verify($event_array['id'], $event_array['type'])) show_404();
        //
        $hf = message_header_footer($event_array['cid'], ucwords($event_array["cname"]));
        //
        $data['company_template_header_footer'] = array(
            'header' => $hf['header'],
            'footer' => $hf['footer']
        );
        $data['title'] = 'Update and verify phone number';
        $data['event_array'] = $event_array;
        $this->load
            ->view('calendar/header', $data)
            ->view('manage_employer/modify_authorize_phonenumber')
            ->view('calendar/footer');
    }


    /**
     * Handle phone number AJAX
     * Created on: 02-08-2019
     *
     * @accepts POST
     *
     * @return JSON
     */
    function update_phonenumber_handler()
    {
        // Check if direct access made
        if ($this->input->server('REQUEST_METHOD') == 'GET') show_404();
        //
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request.';
        //
        $form_data = $this->input->post(NULL, TRUE);
        //
        if (!sizeof($form_data)) $this->response($resp);
        //
        switch ($form_data['action']) {
            case 'update_phonenumber':
                $verification_code = generateRandomString(4);
                //
                $this->home_model->updatePhoneNumber(
                    $form_data['phone_number_e164'],
                    $form_data['id'],
                    $form_data['type'],
                    $verification_code
                );
                // Fetch copany message id
                if (IS_SANDBOX) $messageId = SANDBOX_SERVICE;
                else $messageId = $this->home_model->getCompanyMessageId($form_data['companyId']);
                // TODO
                // Send the verification code
                // Load twilio library
                $this->load->library('twilio/Twilioapp', null, 'twilio');
                //
                $result = $this
                    ->twilio
                    ->setMode(IS_SANDBOX ? 'sandox' : 'production')
                    ->setReceiverPhone($form_data['phone_number_e164'])
                    ->setMessageServiceSID($messageId)
                    ->setMessage('Your verification code is ' . ($verification_code) . '.')
                    ->sendMessage();
                //
                if (isset($resp['Error'])) {
                    $resp['Response'] = $result['Error'];
                    $this->response($resp);
                }
                //
                $resp['Status'] = TRUE;
                // Only for testing
                // $resp['VerificationCode'] = $verification_code;
                $resp['Response'] = 'Proceed.';
                $this->response($resp);
                break;

            case 'validate_code':
                //
                $is_found = $this->home_model->verifyAndUpdateCode(
                    $form_data['code'],
                    $form_data['id'],
                    $form_data['type']
                );
                //
                if (!$is_found) {
                    $resp['Response'] = 'Invalid verification code provided.';
                    $this->response($resp);
                }
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $this->response($resp);
                break;
        }
        $this->response($resp);
    }

    //
    function eeoc_form($token)
    {
        //
        $this->load->library('encryption');
        //
        $id = $this->encryption->decrypt(str_replace(['$$ab$$', '$$ba$$'], ['/', '+'], $token));
        //
        if (empty($id)) {
            exit(0);
        }
        //
        $this->load->model('hr_documents_management_model');
        //
        $document = $this->hr_documents_management_model->getEEOC($id);
        //
        $data = [];
        if (empty($document)) {
            redirect('404');
            exit(0);
        }
        //
        $user_sid = $document['application_sid'];
        $user_type = $document['users_type'];
        //
        if (empty($document['gender'])) {
            $gender = get_user_gender($user_sid, $user_type);
            $document['gender'] = $gender;
        }
        //
        $company_sid = $this->hr_documents_management_model->getCompanysid($user_sid, $user_type);
        //
        $data['session']['company_detail'] = $this->hr_documents_management_model->getCompanyInfo($company_sid);
        $f1 = $this->hr_documents_management_model->hasEEOCPermission($company_sid, 'eeo_on_applicant_document_center');
        $data['eeo_form_status'] = $f1;
        //
        $data['company_sid']        = $data['session']['company_detail']['sid'];
        $data['company_name']       = $data['session']['company_detail']['CompanyName'];
        $data['id']                 = $id;
        $data['user_sid']           = $user_sid;
        $data['user_type']          = $user_type;
        $data['first_name']         = $document['user']['first_name'];
        $data['last_name']          = $document['user']['last_name'];
        $data['email']              = $document['user']['email'];
        $data['eeo_form_info']      = $document;
        $data['location']           = "Public Link";
        $data['dl_citizen']         = getEEOCCitizenShipFlag($data['company_sid']);
        //
        $this->load->view('onboarding/applicant_boarding_header_public', $data);
        $this->load->view('eeo/eeoc_view_public');
        $this->load->view('onboarding/on_boarding_footer');
    }


    //
    function eeoc_form_submit()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if (empty($post)) {
            exit(0);
        }
        //
        $this->load->model('hr_documents_management_model');
        //
        $document = $this->hr_documents_management_model->getEEOC($post['id']);
        // hijack the control and set the EEO
        $eeoFormId = checkAndSetEEOCForUser($document['application_sid'], $document['users_type']);
        //
        if ($eeoFormId != 0) {
            // fetch the new form details
            $document = $this->hr_documents_management_model->getEEOC($eeoFormId);
        } else {
            $eeoFormId = $post['id'];
        }
        //
        $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        //
        $upd = [
            'us_citizen' => $post['citizen'],
            'group_status' => $post['group'],
            'veteran' => $post['veteran'],
            'disability' => $post['disability'],
            'gender' => $post['gender']
        ];
        //
        $action = 'completed';
        $employee_sid = $document['application_sid'];
        //
        if ($employeeId && $employeeId != $document['application_sid']) {
            $eeocAction = $post['eeoc_action'];
            //
            if ($eeocAction == "consent") {
                $upd['last_completed_on'] = date('Y-m-d H:i:s', strtotime('now'));
                $upd['is_expired'] = 1;
            } else if ($eeocAction == "update") {
                $action = 'updated';
            }
            $employee_sid = $employeeId;
        } else {
            $upd['last_completed_on'] = date('Y-m-d H:i:s', strtotime('now'));
            $upd['is_expired'] = 1;
        }
        // update against all versions
        $this->db->where([
            'application_sid' => $document['application_sid'],
            'users_type' => $document['users_type']
        ])->update('portal_eeo_form', $upd);
        //
        $dataToUpdate = array();
        $dataToUpdate['gender'] = strtolower($post['gender']);
        update_user_gender($document['application_sid'], 'employee', $dataToUpdate);
        //
        keepTrackVerificationDocument($employee_sid, 'employee', $action, $eeoFormId, 'eeoc', $post['location']);
        //
        echo 'success';
        exit(0);
    }

    /**
     * 
     */
    public function payInvoice($invoiceId)
    {
        //
        $this->load->model('settings_model');
        // verify invoice
        $invoiceDetails = $this->settings_model->Get_admin_invoice($invoiceId);
        //
        $hf = message_header_footer_domain($invoiceDetails['company_sid'], $invoiceDetails['company_name']);
        //
        $this->load->view('public_invoice', [
            'invoiceDetails' => $invoiceDetails,
            'hf' => $hf
        ]);
    }

    /**
     * why us route
     */
    public function checkPage()
    {
        // set slug
        $slug = $this->uri->uri_string();
        // check if page is dynamic
        $pageContent = getPageContent($slug, true);
        // if not throw error
        if (!$pageContent) {
            return show_404();
        }
        //
        $data['pageContent'] = $pageContent;
        // set meta
        $data["meta"] = $pageContent["meta"];
        // css
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        // js
        $data['pageJs'] = [
            "https://www.google.com/recaptcha/api.js",
            "https://code.jquery.com/jquery-3.5.1.min.js",
        ];
        // css bundle
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'd_page', true);
        // js bundle
        $data['appJs'] = bundleJs([
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'v1/plugins/alertifyjs/alertify.min',
            'js/jquery.validate.min',
            'js/app_helper',
            'v1/app/js/pages/schedule_demo',
        ], $this->js, 'd_page', $this->disableMinifiedFiles);

        $this->load->view($this->header, $data);
        $this->load->view('v1/app/dynamic_pages');
        $this->load->view($this->footer);
    }
}
