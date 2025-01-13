<?php defined('BASEPATH') or exit('No direct script access allowed');

class Settings extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
        $this->load->model('settings_model');
        $this->load->model('application_tracking_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->library("pagination");
    }

    public function validate_sub_domain($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                if (isset($_POST['new_domain']) && $this->input->post("new_domain") != "") {
                    if ($_POST['new_domain_radio'] == 'addondomain') {
                        $new_domain = clean_domain($_POST['new_domain']);
                    } else {
                        $new_domain_api = clean($_POST['new_domain']);
                        $new_domain = $new_domain_api . '.' . STORE_DOMAIN;
                    }
                }

                $temp = $this->dashboard_model->get_portal_detail_by_domain_name($new_domain);

                if ($temp) {
                    return TRUE;
                } else {
                    $this->form_validation->set_message('validate_sub_domain', 'Sub Domain Alreay Exist.');
                    return FALSE;
                }
            }
        }
    }

    public function my_settings()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'my_settings'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "My Settings";
            $result = $this->dashboard_model->update_session_details($company_id, $employer_id);
            $data['portal'] = $result['portal'];
            $data['company'] = $result['company'];
            $data['employer'] = $result['employer'];
            $data['cart'] = db_get_cart_content($company_id);
            $previous_session = $this->session->userdata('logged_in');
            $sess_array = array();
            $sess_array['company_detail'] = $result['company'];
            $sess_array['employer_detail'] = $result['employer'];
            $sess_array['cart'] = $data['cart'];
            $sess_array['portal_detail'] = $result['portal'];
            $sess_array['clocked_status'] = $result['clocked_status'];

            if (isset($previous_session['is_super']) && intval($previous_session['is_super']) == 1) {
                $sess_array['is_super'] = 1;
            } else {
                $sess_array['is_super'] = 0;
            }

            $this->db->where('company_id', $sess_array['company_detail']['sid']);
            $config = $this->db->get('incident_type_configuration')->num_rows();
            $sess_array['incident_config'] = $config;
            $sess_array['resource_center'] = $sess_array['company_detail']['enable_resource_center'];
            $this->session->set_userdata('logged_in', $sess_array); // update session to load latest modifications made.
            $data['company_sid'] = $company_id;
            $data['employer_sid'] = $employer_id;
            $job_fair_configuration = $this->settings_model->job_fair_configuration($company_id);
            $data['job_fair_configuration'] = $job_fair_configuration;
            $data['reassign_flag'] = $this->settings_model->check_reassign_candidate($company_id);
            //Fetch Contact Info for logged in company
            $this->load->model('manage_admin/company_model');
            $data['company_info'] = $this->company_model->get_contact_info($company_id);
            // set CSS
            $data['PageCSS'] = [];
            $data['PageCSS'][] = 'v1/plugins/ms_modal/main';
            // set default js
            $data['PageScripts'] = [];
            $data['PageScripts'][] = 'v1/plugins/ms_modal/main';
            $data['PageScripts'][] = 'js/app_helper';
            // check and add payroll scripts
            if (checkIfAppIsEnabled('payroll')) {
                if (!hasAcceptedPayrollTerms($data['session']['company_detail']['sid'])) {
                    $data['PageScripts'][] = 'v1/payroll/js/agreement';
                }
                if (!isCompanyOnBoard($data['session']['company_detail']['sid'])) {
                    $data['PageScripts'][] = 'v1/payroll/js/company_onboard';
                }
            }
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/my_settings_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function my_profile()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'my_profile'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data = employee_right_nav($employer_id);
            $registration_date = $data['session']['employer_detail']['registration_date'];
            $data['title'] = "My Profile";
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            $data['access_level'] = db_get_enum_values('users', 'access_level');
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';
            //New my_profile view daata STARTS
            $data['applicant_message'] = array();
            $data['applicant_all_ratings'] = NULL;
            $data['applicant_ratings_count'] = NULL;
            $data['applicant_events'] = '';
            $this->load->model('application_tracking_model');
            $rating_result = $this->application_tracking_model->getApplicantAllRating($employer_id, 'employee', $registration_date); //getting all rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee'); // getting applicant ratings - getting average rating of applicant

            if ($rating_result != NULL) {
                $data['applicant_ratings_count'] = $rating_result->num_rows();
                $data['applicant_all_ratings'] = $rating_result->result_array();
            }

            $data['company_accounts'] = $this->application_tracking_model->getCompanyAccounts($company_id); //fetching list of all sub-accounts
            $data['applicant_events'] = $this->application_tracking_model->getApplicantEvents($company_id, $employer_id, 'employee', $registration_date); //Getting Events
            $applicant_email = $data['email'];
            $rawMessages = $this->application_tracking_model->get_sent_messages($applicant_email, ""); // getting private messages of the user

            if (!empty($rawMessages)) {
                $i = 0;

                foreach ($rawMessages as $message) {
                    $employerData = $this->application_tracking_model->getEmployerDetail($data["session"]["employer_detail"]["sid"]);
                    $message['profile_picture'] = $employerData['profile_picture'];
                    $message['first_name'] = $employerData['first_name'];
                    $message['last_name'] = $employerData['last_name'];
                    $message['username'] = $employerData['username'];
                    $allMessages[$i] = $message;
                    $i++;
                }

                $data['applicant_message'] = $allMessages;
            }

            $data['employer']['state_name'] = '';
            $data['employer']['country_name'] = '';
            $data['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee', $registration_date);

            if (!empty($data['employer']['Location_State'])) {
                $state_id = $data['employer']['Location_State'];
                $country_state_info = db_get_state_name($state_id);
                $data['employer']['state_name'] = $country_state_info['state_name'];
                $data['employer']['country_name'] = $country_state_info['country_name'];
            }

            if (empty($data['employer']['country_name']) && !empty($data['employer']['Location_Country'])) {
                $country_id = $data['employer']['Location_Country'];
                $country_info = db_get_country_name($country_id);
                $data['employer']['country_name'] = $country_info['country_name'];
            }

            $data['employee_notes'] = $this->employee_model->getEmployeeNotes($employer_id, $registration_date);
            //New my_profile view daata ENDS
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('PhoneNumber', 'Phone Number', 'trim|xss_clean');
            //$this->form_validation->set_rules('access_level', 'Access Level', 'trim|xss_clean');
            if ($this->form_validation->run() === FALSE) {
                $data['edit_form'] = false;

                if ($this->input->post()) {
                    $data['edit_form'] = true;
                }

                $data['notes_view'] = false; //checking if the form is submitted so i can open the notes form screen again

                if (isset($_SESSION['show_notes']) && $_SESSION['show_notes'] == 'true') {
                    $data['notes_view'] = true;
                    $_SESSION['show_notes'] = 'false';
                }

                $data['show_event'] = false; //checking if the form is submitted so i can open the notes form screen again

                if (isset($_SESSION['show_event']) && $_SESSION['show_event'] == 'true') {
                    $data['show_event'] = true;
                    $_SESSION['show_event'] = 'false';
                }

                $data['show_message'] = false; //checking if the form is submitted so i can open the Messages form screen again

                if (isset($_SESSION['show_message']) && $_SESSION['show_message'] == 'true') {
                    $data["show_message"] = true;
                    $_SESSION['show_message'] = 'false';
                }

                $data['extra_info'] = unserialize($data['employer']['extra_info']); //Send extra Information.
                $data['employee'] = $data['employer'];
                $zones = array();
                // $zones[] = ['name' => 'Hawaii-Aleutian Standard Time - HAST', 'value' => '-10:00|utc'];
                // $zones[] = ['name' => 'Alaska Standard Time - AKST', 'value' => '-9:00|utc'];
                // $zones[] = ['name' => 'Pacific Standard Time - PST', 'value' => '-8:00|utc'];
                // $zones[] = ['name' => 'Mountain Standard Time - MST', 'value' => '-7:00|utc'];
                // $zones[] = ['name' => 'Central Standard Time - CST', 'value' => '-6:00|utc'];
                // $zones[] = ['name' => 'Eastern Standard Time - EST', 'value' => '-5:00|utc'];
                // $zones[] = ['name' => 'Atlantic Standard Time - AST', 'value' => '-4:00|utc'];
                // $zones[] = ['name' => 'Newfoundland Standard Time - NST', 'value' => '-3:30|utc'];
                $data['timezones'] = $zones;
                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/my_profile_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $pictures = $this->input->post('old_profile_picture');
                $profile_picture = upload_file_to_aws('profile_picture', $company_id, 'profile_picture', $sid);

                if ($profile_picture != 'error') {
                    $pictures = $profile_picture;
                } else {
                    $pictures = '';
                }

                $extra_info_arr = array();
                $extra_info_arr['secondary_email'] = $this->input->post('secondary_email');
                $extra_info_arr['secondary_PhoneNumber'] = $this->input->post('secondary_PhoneNumber');
                $extra_info_arr['other_email'] = $this->input->post('other_email');
                $extra_info_arr['other_PhoneNumber'] = $this->input->post('other_PhoneNumber');
                $extra_info_arr['title'] = $this->input->post('title');
                $extra_info_arr['division'] = $this->input->post('division');
                $extra_info_arr['department'] = $this->input->post('department');
                $extra_info_arr['office_location'] = $this->input->post('office_location');
                $extra_info_arr['interests'] = $this->input->post('interests');
                $extra_info_arr['short_bio'] = $this->input->post('short_bio');
                $extra_info = serialize($extra_info_arr);
                $youtube_code = '';

                if ($this->input->post('YouTubeVideo') != "") {
                    $youtube_link = $this->input->post('YouTubeVideo');
                    $youtube_code = substr($youtube_link, strpos($youtube_link, '=') + 1, strlen($youtube_link));
                }

                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'Location_Country' => $this->input->post('Location_Country'),
                    'Location_State' => $this->input->post('Location_State'),
                    'Location_City' => $this->input->post('Location_City'),
                    'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                    'Location_Address' => $this->input->post('Location_Address'),
                    'PhoneNumber' => $this->input->post('PhoneNumber'),
                    //'profile_picture' => $pictures,
                    'YouTubeVideo' => $youtube_code,
                    'job_title' => $this->input->post('job_title'),
                    'extra_info' => $extra_info,
                    'linkedin_profile_url' => $this->input->post('linkedin_profile_url'),
                    'timezone' => $this->input->post('timezone')
                );

                if (!empty($pictures)) {
                    $data['profile_picture'] = $pictures;
                }

                $this->dashboard_model->update_user($sid, $data);
                $employer = $this->dashboard_model->get_company_detail($sid);
                $session = $this->session->userdata('logged_in');
                $session['employer_detail'] = $employer;
                $this->session->set_userdata('logged_in', $session);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Profile is updated successfully');
                redirect('my_profile', 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function company_profile()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'company_profile'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $onPayroll = $data['session']['company_detail']['on_payroll'];
            $data['title'] = 'Company Profile';
            $company = $this->dashboard_model->get_company_detail($company_id);


            if (empty($company['extra_info'])) {
                $data['onboarding_eeo_form_status'] = 1;
                $data['safety_sheet'] = 1;
                $data['eight_plus'] = 0;
                $data['affiliate'] = 0;
                $data['d_license'] = 0;
                $data['l_employment'] = 0;
                $company['mtin'] = "";
                $company['clock_enable_for_attendance'] = "";
                $company['timesheet_enable_for_attendance'] = "";
                $company['shift_reminder_email_for_next_day'] = 0;
                $company['week_off_days'] = [];
            } else {
                $serializedata = unserialize($company['extra_info']);
                $data['onboarding_eeo_form_status'] = $serializedata['EEO'];
                $data['safety_sheet'] = $serializedata['safety_sheet'];
                $data['eight_plus'] = isset($serializedata['18_plus']) ? $serializedata['18_plus'] : 0;
                $data['affiliate'] = isset($serializedata['affiliate']) ? $serializedata['affiliate'] : 0;
                $data['d_license'] = isset($serializedata['d_license']) ? $serializedata['d_license'] : 0;
                $data['l_employment'] = isset($serializedata['l_employment']) ? $serializedata['l_employment'] : 0;
                $company['mtin'] = isset($serializedata['mtin']) ? $serializedata['mtin'] : 0;
                $company['clock_enable_for_attendance'] = isset($serializedata['clock_enable_for_attendance']) ? $serializedata['clock_enable_for_attendance'] : 0;
                $company['timesheet_enable_for_attendance'] = isset($serializedata['timesheet_enable_for_attendance']) ? $serializedata['timesheet_enable_for_attendance'] : 0;
                $company['shift_reminder_email_for_next_day'] = isset($serializedata['shift_reminder_email_for_next_day']) ? $serializedata['shift_reminder_email_for_next_day'] : 0;
                $company['week_off_days'] = isset($serializedata['week_off_days']) ? $serializedata['week_off_days'] : [];
            }
            $serializedata = unserialize($company['extra_info']);
            $data['company'] = $company;
            $complynet_status  = $data['session']['company_detail']['complynet_status'];
            $access_level_plus = $data['session']['employer_detail']['access_level_plus'];
            $data['access_level_plus'] = $access_level_plus;
            $data['complynet_status'] = $complynet_status;
            $data['complynet_link'] = $data['session']['company_detail']['complynet_dashboard_link'];
            $data['portal'] = $this->dashboard_model->get_portal_detail($company_id);
            $this->load->model("v1/Payroll_model", "payroll_model");
            //
            $payroll_status = $this->payroll_model->GetCompanyPayrollStatus($company_id);
            $data['payroll_status'] = $payroll_status;
            //
            if ($data['company']['CompanyName'] != $this->input->post('CompanyName')) {
                $this->form_validation->set_message('is_unique', '%s is already registered.');
                $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean|is_unique[users.CompanyName]');
            } else {
                $this->form_validation->set_rules('CompanyName', 'Company Name', 'required|trim|xss_clean');
            }

            $video_source = $this->input->post('video_source');
            $this->form_validation->set_rules('ContactName', 'Contact Name', 'required|trim|xss_clean');

            if ($video_source == 'youtube') {
                $this->form_validation->set_rules('YouTubeVideo', 'YouTube Video', 'trim|xss_clean|callback_validate_youtube');
            } else if ($video_source == 'vimeo') {
                $this->form_validation->set_rules('VimeoVideo', 'Vimeo Video', 'trim|xss_clean|callback_validate_vimeo');
            }

            $this->form_validation->set_rules('email', 'Company E-Mail', 'trim|xss_clean');
            $this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');
            //
            if ($payroll_status == 1) {
                $this->form_validation->set_rules('ssn', 'ssn', 'trim|xss_clean|required');
            }
            //
            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/company_profile_new');
                $this->load->view('main/footer');
            } else {
                $sid = $company_id;
                $yoututbe_video_url = $this->input->post('YouTubeVideo');
                $vimeo_video_url = $this->input->post('VimeoVideo');
                $video_source = $this->input->post('video_source');
                $CompanyName = $this->input->post('CompanyName');
                $ContactName = $this->input->post('ContactName');
                $email = $this->input->post('email');
                $WebSite = $this->input->post('WebSite');
                $onboarding_eeo = $this->input->post('onboarding_eeo_form_status');
                $safety_sheet = $this->input->post('safety_sheet');
                $CompanyDescription = $this->input->post('CompanyDescription');
                $complynet_link = $access_level_plus ? $this->input->post('complynet_link') : '';
                //
                $post = $this->input->post(NULL, TRUE);
                //
                if (isset($onboarding_eeo) || isset($safety_sheet)) {
                    $company_extra_info = array(
                        'EEO' => (isset($onboarding_eeo) && !empty($onboarding_eeo)) ? $onboarding_eeo : 0,
                        'safety_sheet' => (isset($safety_sheet) && !empty($safety_sheet)) ? $safety_sheet : 0
                    );
                } else {
                    $company_extra_info = array(
                        'EEO' => 0,
                        'safety_sheet' => 0
                    );
                }

                if ($post["mtin"]) {
                    $company_extra_info["mtin"] = $post["mtin"];
                }

                if ($post["clock_enable_for_attendance"]) {
                    $company_extra_info["clock_enable_for_attendance"] = $post["clock_enable_for_attendance"];
                }
                if ($post["timesheet_enable_for_attendance"]) {
                    $company_extra_info["timesheet_enable_for_attendance"] = $post["timesheet_enable_for_attendance"];
                }
                if ($post["shift_reminder_email_for_next_day"]) {
                    $company_extra_info["shift_reminder_email_for_next_day"] = $post["shift_reminder_email_for_next_day"];
                }
                if ($post["week_off_days"]) {
                    $company_extra_info["week_off_days"] = $post["week_off_days"];
                }

                // Full employment form required
                if (isset($post['18_plus'])) {
                    $company_extra_info['18_plus'] = $post['18_plus'];
                }
                if (isset($post['affiliate'])) {
                    $company_extra_info['affiliate'] = $post['affiliate'];
                }
                if (isset($post['d_license'])) {
                    $company_extra_info['d_license'] = $post['d_license'];
                }
                if (isset($post['l_employment'])) {
                    $company_extra_info['l_employment'] = $post['l_employment'];
                }
                $extra_info = serialize($company_extra_info);
                $video_id = '';
                $remove_flag;

                if (!empty($_FILES) && isset($_FILES['UploadedVideo']) && $_FILES['UploadedVideo']['size'] > 0) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES['UploadedVideo']['name']);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = 'assets/uploaded_videos/';
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES['UploadedVideo']['tmp_name'], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES['UploadedVideo']['name']) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('company_profile', 'refresh');
                    }

                    $video_id = $file_name;
                    $remove_flag = true;
                } else {
                    $video_id = $this->input->post('video_id');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($yoututbe_video_url, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_id = $url_prams['v'];
                        }

                        $remove_flag = true;
                    } else if ($video_source == 'vimeo') {
                        $vimeo_url = $this->input->post('VimeoVideo');
                        $video_id = $this->vimeo_get_id($vimeo_url);
                        $remove_flag = true;
                    } else {
                        $remove_flag = false;
                    }
                }

                if ($remove_flag == true) {
                    $previous_video = $this->dashboard_model->get_company_video($sid);
                    $previous_source = $previous_video[0]['video_source'];

                    if ($previous_source == 'uploaded') {
                        $video_url = 'assets/uploaded_videos/' . $previous_video[0]['YouTubeVideo'];
                        unlink($video_url);
                    }
                }

                $data = array();
                $data['CompanyName'] = $CompanyName;
                $data['ContactName'] = $ContactName;
                $data['video_source'] = $video_source;

                if (!empty($video_id) && !is_null($video_id)) {
                    $data['YouTubeVideo'] = $video_id;
                }

                $data['email'] = $email;
                $data['WebSite'] = $WebSite;
                $data['CompanyDescription'] = $CompanyDescription;
                $data['extra_info'] = $extra_info;
                $Logo = upload_file_to_aws('Logo', $company_id, 'logo', '', AWS_S3_BUCKET_NAME);

                if (!empty($Logo) && $Logo != 'error') {
                    $data['Logo'] = $Logo;
                }

                if (IS_TIMEZONE_ACTIVE) {
                    // Added on: 25-06-2019
                    $new_company_timezone = $this->input->post('company_timezone', true);
                    if (!empty($new_company_timezone)) $data['timezone'] = $new_company_timezone;
                }

                if ($complynet_status && $access_level_plus) {
                    // Added on: 02-09-2019
                    $data['complynet_dashboard_link'] = $complynet_link;
                }

                // EEOC Questionnaire
                $portal_data['dl_citizen'] = $this->input->post('dl_citizen', true) == 'on' ? 1 : 0;
                $portal_data['dl_vet'] = $this->input->post('dl_vet', true) == 'on' ? 1 : 0;
                $portal_data['dl_vol'] = $this->input->post('dl_vol', true) == 'on' ? 1 : 0;
                $portal_data['dl_gen'] = $this->input->post('dl_gen', true) == 'on' ? 1 : 0;


                //
                $data['ssn'] = $this->input->post('ssn', true);
                $data['Location_State'] = $this->input->post('Location_State', true);
                $data['company_corp_name'] = $this->input->post('company_corp_name');


                $this->dashboard_model->update_user($sid, $data);
                $company_details = $data;
                $company_details['sid'] = $sid;
                $this->load->model('manage_admin/company_model');
                $this->company_model->sync_company_details_to_remarket($company_details);
                $contact_us_page = $this->input->post('contact_us_page');
                $enable_company_logo = $this->input->post('enable_company_logo');
                $ats_active_job_flag = $this->input->post('ats_active_job_flag');
                $job_title_location = $this->input->post('job_title_location');
                $resume_mandatory = $this->input->post('is_resume_mandatory');
                $eeo_form_status = $this->input->post('eeo_form_status');
                $eeo_form_profile_status = $this->input->post('eeo_form_profile_status');
                $employee_handbook = $this->input->post('employee_handbook');
                $full_employment_app_print = $this->input->post('full_employment_app_print');
                $job_title_special_chars = $this->input->post('job_title_special_chars');
                $onboarding_ssn_status = $this->input->post('onboarding_ssn_status');
                $FEA_sent_to_an_applicant = $this->input->post('FEA_sent_to_an_applicant');
                $onboarding_dob_status = $this->input->post('onboarding_dob_status');
                $company_timezone = $this->input->post('company_timezone');

                if (empty($contact_us_page)) {
                    $portal_data['contact_us_page'] = 0;
                } else {
                    $portal_data['contact_us_page'] = 1;
                }

                if (empty($enable_company_logo)) {
                    $portal_data['enable_company_logo'] = 0;
                } else {
                    $portal_data['enable_company_logo'] = 1;
                }

                if (empty($ats_active_job_flag)) {
                    $portal_data['ats_active_job_flag'] = 0;
                } else {
                    $portal_data['ats_active_job_flag'] = 1;
                }

                if (empty($job_title_location)) {
                    $portal_data['job_title_location'] = 0;
                } else {
                    $portal_data['job_title_location'] = 1;
                }

                if (empty($resume_mandatory)) {
                    $portal_data['is_resume_mandatory'] = 0;
                } else {
                    $portal_data['is_resume_mandatory'] = 1;
                }

                if (empty($eeo_form_status)) {
                    $portal_data['eeo_form_status'] = 0;
                } else {
                    $portal_data['eeo_form_status'] = 1;
                }

                if (empty($eeo_form_profile_status)) {
                    $portal_data['eeo_form_profile_status'] = 0;
                } else {
                    $portal_data['eeo_form_profile_status'] = 1;
                }

                if (empty($employee_handbook)) {
                    $portal_data['employee_handbook'] = 0;
                } else {
                    $portal_data['employee_handbook'] = 1;
                }


                if (empty($full_employment_app_print)) {
                    $portal_data['full_employment_app_print'] = 0;
                } else {
                    $portal_data['full_employment_app_print'] = 1;
                }

                if (empty($job_title_special_chars)) {
                    $portal_data['job_title_special_chars'] = 0;
                } else {
                    $portal_data['job_title_special_chars'] = 1;
                }

                if (empty($FEA_sent_to_an_applicant)) {
                    $portal_data['sent_to_an_applicant'] = 0;
                } else {
                    $portal_data['sent_to_an_applicant'] = 1;
                }

                if (empty($onboarding_ssn_status)) {
                    $portal_data['ssn_required'] = 0;
                } else {
                    $portal_data['ssn_required'] = 1;
                }

                if (empty($onboarding_dob_status)) {
                    $portal_data['dob_required'] = 0;
                } else {
                    $portal_data['dob_required'] = 1;
                }


                $post = $this->input->post(null, true);

                //
                $portal_data['eeo_on_applicant_document_center'] = empty($post['eeo_on_applicant_document_center']) ? 0 : 1;
                $portal_data['eeo_on_employee_document_center'] = empty($post['eeo_on_employee_document_center']) ? 0 : 1;
                $portal_data['eeo_on_document_center'] = empty($post['eeo_on_document_center']) ? 0 : 1;


                if (IS_TIMEZONE_ACTIVE) {
                    // Added on: 25-06-2019
                    $new_company_timezone = $this->input->post('company_timezone', true);
                    if (!empty($new_company_timezone)) $portal_data['company_timezone'] = $new_company_timezone;
                    if (isset($portal_data['company_timezone'])) {
                        // Check and set
                        $this->dashboard_model->set_new_timezone_in_old_calendar_events_by_company_id($company_id, $portal_data['company_timezone']);
                    }
                }
                // Check and update data on gusto
                if ($onPayroll == 1) {
                    // TO BE checked
                    // //
                    // $this->load->model('Payroll_model', 'pm');
                    // $this->load->helper('payroll');
                    // //
                    // $updateArray = [
                    //     'ein_number' => $this->input->post('ssn', true),
                    //     'legal_name' => $this->input->post('CompanyName', true)
                    // ];
                    // //
                    // $this->pm->UpdateCompanyTax($updateArray, [
                    //     'company_sid' => $company_id
                    // ]);
                    // Update data to Gusto
                    // GustoUpdateCompanyTax($updateArray, $company_id);
                }
                // General documents required
                $portal_data['man_d1'] = $this->input->post('man_d1', true) == 'on' ? 1 : 0;
                $portal_data['man_d2'] = $this->input->post('man_d2', true) == 'on' ? 1 : 0;
                $portal_data['man_d3'] = $this->input->post('man_d3', true) == 'on' ? 1 : 0;
                $portal_data['man_d4'] = $this->input->post('man_d4', true) == 'on' ? 1 : 0;
                $portal_data['man_d5'] = $this->input->post('man_d5', true) == 'on' ? 1 : 0;

                //
                $portal_data['primary_number_required'] = $this->input->post('primary_number_status', true) == '1' ? 1 : 0;

                //
                if (checkIfAppIsEnabled('documentlibrary')) :
                    $portal_data['dl_i9'] = $this->input->post('dl_i9', true) == 'on' ? 1 : 0;
                    $portal_data['dl_w9'] = $this->input->post('dl_w9', true) == 'on' ? 1 : 0;
                    $portal_data['dl_w4'] = $this->input->post('dl_w4', true) == 'on' ? 1 : 0;
                endif;
                //

                // set uniform sizes
                $portal_data["uniform_sizes"] = $this->input->post("uniform_sizes", true) === "on";

                //Emergency Contact
                $portal_data['emergency_contact_phone_number_status'] = $this->input->post('emergency_contact_phone_number_status')  ? 1 : 0;
                $portal_data['emergency_contact_email_status'] = $this->input->post('emergency_contact_email_status')  ? 1 : 0;
                
                $this->dashboard_model->update_portal($portal_data, $company_id);
                $this->session->set_flashdata('message', '<b>Success:</b> Company Profile is updated successfully');
                redirect("my_settings", "location");
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function company_address()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'company_address'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Company Contact Details';
            $data['company'] = $this->dashboard_model->get_company_detail($company_id);
            $data_countries = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']] = db_get_active_states($value['sid']);
            }

            $data_states_encode = htmlentities(json_encode($data_states));
            $data['active_countries'] = $data_countries;
            $data['active_states'] = $data_states;
            $data['states'] = $data_states_encode;
            //$data['access_level'] = array('Admin', 'Executive', 'Hiring Manager', 'Recruiter', 'Manager', 'Employee');
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City ', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('PhoneNumber', 'Primary Number', 'trim|xss_clean');
            //$this->form_validation->set_rules('CompanyDescription', 'Description', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/company_address_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $data = array(
                    'Location_Country' => $this->input->post('Location_Country'),
                    'Location_State' => $this->input->post('Location_State'),
                    'Location_City' => $this->input->post('Location_City'),
                    'Location_ZipCode' => $this->input->post('Location_ZipCode'),
                    'Location_Address' => $this->input->post('Location_Address'),
                    // 'PhoneNumber' => $this->input->post('PhoneNumber')
                    'PhoneNumber' => $this->input->post('txt_phonenumber', true) ? $this->input->post('txt_phonenumber', true) : $this->input->post('PhoneNumber', true)
                );

                $this->dashboard_model->update_user($sid, $data);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Profile is updated successfully');
                redirect('my_settings', 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function seo_tags()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'seo_tags'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Search Engine Optimization Meta tags";
            $data["company"] = $this->dashboard_model->get_portal_detail($company_id);
            $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|xss_clean');
            $this->form_validation->set_rules('meta_keywords', 'meta_keywords', 'trim|xss_clean');
            $this->form_validation->set_rules('meta_description', 'Meta Description', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/seo_tags_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $data = array(
                    'meta_title' => $this->input->post('meta_title'),
                    'meta_keywords' => $this->input->post('meta_keywords'),
                    'meta_description' => $this->input->post('meta_description')
                );
                $this->dashboard_model->update_portal($data, $sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Meta Tags updated successfully');
                redirect("my_settings", "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function embedded_code()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'embedded_code'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Embedded Code / Google Analytics";
            $data['company'] = $this->dashboard_model->get_portal_detail($company_id);
            $this->form_validation->set_rules('embedded_code', 'Embedded Code', 'trim|xss_clean|min_length[11]|max_length[14]');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/embedded_code_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $data = array('embedded_code' => $this->input->post('embedded_code'));
                $this->dashboard_model->update_portal($data, $sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Embedded code updated successfully');
                redirect("my_settings", "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function portal_widget()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'portal_widget'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data['session']['company_detail']['sid'];
            $employer_id = $data['session']['employer_detail']['sid'];
            $data['title'] = "Career Page Widget";
            $data['script_tag'] = '<script id="autom' . $company_id . '" class="automotosocial_webwidget" type="text/javascript" src="' . STORE_FULL_URL_SSL . 'job_widget/jobs.js"></script><div id="automoto-widget-container"></div>';
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/job_widget_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function web_services()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'web_services'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data["company"] = $this->dashboard_model->get_company_detail($company_id);
            $data['title'] = "Career Page XML WEBSERVICE";
            $data['api_link'] = base_url() . "widget/xml_api?api_key=" . $data["company"]["api_key"] . "&attributes=sid,user_sid,active,views,activation_date,Title,JobType,JobCategory,Location_Country,Location_State,Location_ZipCode,JobDescription,JobRequirements,SalaryType,Location_City,Salary,YouTube_Video";

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/web_services_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function domain_management()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'domain_management'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['company'] = $this->dashboard_model->get_portal_detail($company_id);
            $data['title'] = "Domain Name Management";
            $this->form_validation->set_rules('new_subdomain', 'Sub Domain', 'trim|xss_clean');
            $this->form_validation->set_rules('new_addondomain', 'Addon Domain', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/domain_management_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $domain_type = $this->input->post('domain_type');
                $new_subdomain = $this->input->post('new_subdomain');
                $new_addondomain = $this->input->post('new_addondomain');
                $old_domain = $this->input->post('old_domain');


                if (empty($domain_type)) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Nothing modified for domain management.');
                    redirect('my_settings', 'location');
                } else if ($old_domain == $new_addondomain || $old_domain == $new_subdomain) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> Nothing modified as the new domain name is similar to your current domain name.');
                    redirect('my_settings', 'location');
                } else {
                    if ($domain_type == 'addondomain') {
                        $new_domain_api = clean_domain($new_addondomain);
                        $new_domain = $new_domain_api;
                        $new_domain_api_username = clean($new_domain_api);
                    } else {
                        $new_domain_api = clean($new_subdomain);
                        $new_domain = $new_domain_api . '.' . STORE_DOMAIN;
                    }

                    $result = $this->dashboard_model->check_existing_domain($new_domain); //check if the domain name is avaialable or not

                    if ($result == 1) { // new domain does is not available
                        $this->session->set_flashdata('message', '<b>Error:</b> New domain name already exists, please try different name!');
                        redirect('domain_management', 'location');
                    } else { // all set, update domain name
                        $data = array(
                            'sub_domain' => $new_domain,
                            'domain_type' => $domain_type
                        );

                        $this->dashboard_model->update_existing_domain($data, $sid);
                        $auth_details = $this->settings_model->fetch_details(THEME_AUTH);
                        $auth_user = $auth_details['auth_user'];
                        $auth_pass = $auth_details['auth_pass'];
                        require_once(APPPATH . 'libraries/xmlapi.php');

                        if ($domain_type == 'addondomain') { //make addon domain at server
                            $server = STORE_DOMAIN;
                            $json_client = new xmlapi($server);
                            $json_client->set_output('json');
                            $json_client->set_port(2083);
                            $json_client->password_auth($auth_user, $auth_pass);
                            //
                            $AHR = getCreds("AHR");
                            $pass = $AHR->keys->DomainPass;
                            //
                            $args = array(
                                'dir' => 'public_html/manage_portal/',
                                'newdomain' => $new_domain,
                                'subdomain' => $new_domain_api_username,
                                'pass' => $pass
                            );

                            if ($_SERVER['SERVER_NAME'] != 'localhost') {
                                $result = $json_client->api2_query($auth_user, "AddonDomain", "addaddondomain", $args);
                                sendMail(FROM_EMAIL_NOTIFICATIONS, 'mubashar.ahmed@egenienext.com', 'New addon domain - Domain Management', $result);
                            }
                        } else { // make subdomain at server
                            $server = STORE_DOMAIN;
                            $json_client = new xmlapi($server);
                            $json_client->set_output('json');
                            $json_client->set_port(2083);
                            $json_client->password_auth($auth_user, $auth_pass);

                            $args = array(
                                'dir' => 'public_html/manage_portal/',
                                'rootdomain' => STORE_DOMAIN,
                                'domain' => $new_domain
                            );

                            if ($_SERVER['SERVER_NAME'] != 'localhost') {
                                $result = $json_client->api2_query($auth_user, 'SubDomain', 'addsubdomain', $args);
                                sendMail(FROM_EMAIL_NOTIFICATIONS, 'mubashar.ahmed@egenienext.com', 'New Api Result - Domain Management', $result);
                            }
                        }
                        $this->session->set_flashdata('message', '<b>Success:</b> Your domain is updated successfully!');
                        redirect("my_settings", "location");
                    }
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function login_password()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'login_password'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Login Credentials";
            $data['employer'] = $this->dashboard_model->get_company_detail($employer_id);
            // getting applicant ratings - getting average rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee');

            if ($data['employer']['is_executive_admin'] == '1') {
                redirect("my_settings", "refresh");
            }

            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_personal';

            if ($data["employer"]['username'] != $this->input->post('username')) {
                $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean|is_unique[users.username]');
            } else {
                $this->form_validation->set_rules('username', 'User Name', 'required|min_length[5]|trim|xss_clean');
            }

            if ($data['employer']['email'] != $this->input->post('email')) {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email|callback_if_user_exists_ci_validation');
            } else {
                $this->form_validation->set_rules('email', 'E-Mail Address', 'required|trim|xss_clean|valid_email');
            }

            $data['employee'] = $data["employer"];
            $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|matches[cpassword]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|xss_clean');
            $this->form_validation->set_message('is_unique', '%s is not available, Please try again!');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/login_password_new');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('id');
                $password = $this->input->post('password');

                /* if (empty($password)) {
                  $data = array(
                  'username' => $this->input->post('username'),
                  'email' => $this->input->post('email')
                  );
                  } else {
                  $data = array(  'username' => $this->input->post('username'),
                  'password' => do_hash($this->input->post('password'), 'md5'),
                  'email' => $this->input->post('email'));
                  } */

                $data = array('password' => do_hash($this->input->post('password'), 'md5'));
                $this->dashboard_model->update_user($sid, $data);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Login credentials updated successfully');
                redirect('my_profile', 'location');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function social_links()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'social_links'); // Param2: Redirect URL, Param3: Function Name
            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "SOCIAL LINKS FOR THE FOOTER OF YOUR COMPANY CAREER PAGE";
            $data["company"] = $this->dashboard_model->get_portal_detail($company_id);

            $this->form_validation->set_rules('facebook_footer', 'Facebook profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('twitter_footer', 'Twitter profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('google_footer', 'Google plus profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('linkedin_footer', 'LinkedIn profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('youtube_footer', 'LinkedIn profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('instagram_footer', 'LinkedIn profile link', 'trim|xss_clean');
            $this->form_validation->set_rules('glassdoor_footer', 'LinkedIn profile link', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/social_links_new');
                $this->load->view('main/footer');
            } else {
                $enable_facebook_footer = $this->input->post('enable_facebook_footer');
                $enable_twitter_footer = $this->input->post('enable_twitter_footer');
                $enable_google_footer = $this->input->post('enable_google_footer');
                $enable_linkedin_footer = $this->input->post('enable_linkedin_footer');
                $enable_youtube_footer = $this->input->post('enable_youtube_footer');
                $enable_instagram_footer = $this->input->post('enable_instagram_footer');
                $enable_glassdoor_footer = $this->input->post('enable_glassdoor_footer');
                $facebook_footer = $this->input->post('facebook_footer');
                $twitter_footer = $this->input->post('twitter_footer');
                $google_footer = $this->input->post('google_footer');
                $linkedin_footer = $this->input->post('linkedin_footer');
                $youtube_footer = $this->input->post('youtube_footer');
                $instagram_footer = $this->input->post('instagram_footer');
                $glassdoor_footer = $this->input->post('glassdoor_footer');

                if (empty($enable_facebook_footer)) {
                    $enable_facebook_footer = 0;
                }

                if (empty($enable_twitter_footer)) {
                    $enable_twitter_footer = 0;
                }

                if (empty($enable_google_footer)) {
                    $enable_google_footer = 0;
                }

                if (empty($enable_linkedin_footer)) {
                    $enable_linkedin_footer = 0;
                }

                if (empty($enable_youtube_footer)) {
                    $enable_youtube_footer = 0;
                }

                if (empty($enable_instagram_footer)) {
                    $enable_instagram_footer = 0;
                }

                if (empty($enable_glassdoor_footer)) {
                    $enable_glassdoor_footer = 0;
                }

                $social_data = array(
                    'enable_facebook_footer' => $enable_facebook_footer,
                    'enable_twitter_footer' => $enable_twitter_footer,
                    'enable_google_footer' => $enable_google_footer,
                    'enable_linkedin_footer' => $enable_linkedin_footer,
                    'enable_youtube_footer' => $enable_youtube_footer,
                    'enable_instagram_footer' => $enable_instagram_footer,
                    'enable_glassdoor_footer' => $enable_glassdoor_footer,
                    'facebook_footer' => $facebook_footer,
                    'twitter_footer' => $twitter_footer,
                    'linkedin_footer' => $linkedin_footer,
                    'google_footer' => $google_footer,
                    'youtube_footer' => $youtube_footer,
                    'instagram_footer' => $instagram_footer,
                    'glassdoor_footer' => $glassdoor_footer
                );

                $sid = $data['company']['user_sid'];
                $this->dashboard_model->update_portal($social_data, $sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Social links updated successfully');
                redirect("my_settings", "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function job_task()
    {
        if ($this->session->userdata('logged_in')) {
            $action = $this->input->post('action');
            $jobId = $this->input->post('sid');

            if ($action == 'delete') {
                $this->dashboard_model->delete($jobId);
            } elseif ($action == 'active') {
                $this->dashboard_model->active($jobId);
            } elseif ($action == 'deactive') {
                $this->dashboard_model->deactive($jobId);
            } elseif ($action == 'delete_img') {
                $this->dashboard_model->delete_img($jobId);
            }
        }
    }

    public function setting_task()
    {
        $action = $this->input->post("action");
        if ($action == 'remove_logo') {
            $company_id = $this->input->post("sid");
            $this->dashboard_model->delete_logo($company_id);
        }
    }

    public function validate_youtube($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_youtube', 'Invalid youtube video url.');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    public function validate_vimeo($str)
    {
        if ($this->session->userdata('logged_in')) {
            if ($str != "") {
                preg_match("/https?:\/\/(?:www\.)?vimeo\.com\/\d{6}/", $str, $matches);
                if (!isset($matches[0])) { //if validation not passed
                    $this->form_validation->set_message('validate_vimeo', 'Invalid vimeo video url.');
                    return FALSE;
                } else { //if validation passed
                    return TRUE;
                }
            } else {
                return true;
            }
        }
    }

    public function full_employment_application($sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data["session"]["company_detail"]["sid"];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_access_level = $data["session"]["employer_detail"]["access_level"];
            $full_employment_app_print = $data["session"]["portal_detail"]["full_employment_app_print"];
            $access_level = $data['session']['employer_detail']['access_level'];

            if ($sid == NULL) {
                $employer_id = $data["session"]["employer_detail"]["sid"];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = "Full Employment Application";
                $data['return_title_heading'] = "My Profile";
                $data['return_title_heading_link'] = base_url() . 'my_profile';
                $load_view = check_blue_panel_status(false, 'self');
            } else {
                check_access_permissions($security_details, 'employee_management', 'view_employee_full_employment_application'); // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = "Employee / Team Members Full Employment Application";
                $reload_location = 'full_employment_application/' . $sid;
                $data['return_title_heading'] = "Employee Profile";
                $data['return_title_heading_link'] = base_url() . 'employee_profile/' . $sid;
                $load_view = check_blue_panel_status(false, 'employee');
            }

            $data['employer_access_level'] = $employer_access_level;
            $full_access = false;
            $data['load_view'] = $load_view;
            $data['from_cntrl'] = 'emp';

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }


            $data['states'] = db_get_active_states(227);
            $data['starting_year_loop'] = 1930;
            $data['full_access'] = $full_access;
            $data['left_navigation'] = $left_navigation;
            $data["company"] = $this->dashboard_model->get_portal_detail($company_id);
            $data["employer"] = $this->dashboard_model->get_company_detail($employer_id);
            $data['above18'] = $data["employer"]['dob'] != NULL && $data["employer"]['dob'] != '0000-00-00' && !empty($data["employer"]['dob']) ? date('Y') - date('Y', strtotime($data["employer"]['dob'])) : 0;
            //
            // ***This below is old logic to get employee joining date*** //
            //
            // $date_of_emp = !empty($data["employer"]['registration_date']) && $data["employer"]['registration_date'] != NULL ? $data["employer"]['registration_date'] : '';
            // if (empty($date_of_emp)) {
            //     $date_of_emp = !empty($data["employer"]['joined_at']) && $data["employer"]['joined_at'] != NULL ? $data["employer"]['joined_at'] : '';
            // }
            //
            // ***This below is new logic to get employee joining date*** //
            //
            $date_of_emp = get_employee_latest_joined_date($data["employer"]['registration_date'], $data["employer"]['joined_at'], $data["employer"]['rehire_date']);
            //
            if (!empty($date_of_emp)) {
                $data['month'] = date('F', strtotime($date_of_emp));
                $data['year'] = date('Y', strtotime($date_of_emp));

                $data['cur_month'] = date('F');
                $data['cur_year'] = date('Y');
            }
            $data["company_name"] = $company_name;
            $data['full_employment_app_print'] = $full_employment_app_print;
            // getting applicant ratings - getting average rating of applicant
            $data['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee');
            $data['employee'] = $data['session']['employer_detail'];
            if (empty($data["employer"])) { // Employer does not exists - throw error
                $this->session->set_flashdata('message', '<b>Error:</b> No Employee found!');
                redirect('employee_management', 'refresh');
            }
            $drivers_license = $this->dashboard_model->get_license_details('employee', $employer_id, 'drivers');
            if (!empty($drivers_license)) {
                $drivers_license['license_details'] = unserialize($drivers_license['license_details']);
            }
            $data['drivers_license_details'] = isset($drivers_license['license_details']) ? $drivers_license['license_details'] : array();

            if (isset($_POST['action']) && $_POST['action'] == 'true') {
                $data["formpost"] = $_POST;
            } else {
                $data["formpost"] = unserialize($data["employer"]["full_employment_application"]);
            }
            $data["employer"]['extra_info'] = unserialize($data["employer"]['extra_info']);

            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim|xss_clean');
            //$this->form_validation->set_rules('TextBoxNameMiddle', 'Middle Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('suffix', 'Suffix', 'trim|xss_clean');

            if (isset($_POST['email']) && $_POST['email'] == $data["employer"]['email']) {
                $this->form_validation->set_rules('email', 'Email Address', 'required|trim|xss_clean');
            } else {
                $this->form_validation->set_rules('email', 'Email Address', 'required|trim|xss_clean|is_unique[users.email]');
            }

            $this->form_validation->set_rules('TextBoxAddressEmailConfirm', 'Confirm Email Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('Location_Address', 'Address', 'required|trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressLenghtCurrent', 'How Long', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_City', 'City', 'required|trim|xss_clean');
            $this->form_validation->set_rules('Location_Country', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_State', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('Location_ZipCode', 'Zipcode', 'required|trim|xss_clean');
            //$this->form_validation->set_rules('CheckBoxAddressInternationalCurrent', 'Non USA Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressStreetFormer1', 'Former Residence', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressLenghtFormer1', 'How Long?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressCityFormer1', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListAddressCountryFormer1', 'Former Country', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListAddressStateFormer1', 'Former State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressZIPFormer1', 'Zip Code', 'trim|xss_clean');
            //$this->form_validation->set_rules('CheckBoxAddressInternationalFormer1', 'Non USA Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressStreetFormer2', 'Former Residence', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressLenghtFormer2', 'How Long?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressCityFormer2', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListAddressStateFormer2', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressZIPFormer2', 'Zip Code', 'trim|xss_clean');
            $this->form_validation->set_rules('CheckBoxAddressInternationalFormer2', 'Non USA Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressStreetFormer3', 'Other Mailing Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressCityFormer3', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListAddressStateFormer3', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAddressZIPFormer3', 'Zip Code', 'trim|xss_clean');
            $this->form_validation->set_rules('PhoneNumber', 'Primary Telephone', 'required|trim|xss_clean');
            $this->form_validation->set_rules('TextBoxTelephoneMobile', 'Mobile Telephone', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxTelephoneOther', 'Other Telephone', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListPostionTime', 'Job position', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxPositionDesired', 'more position', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxWorkBeginDate', 'Begin Date', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxWorkCompensation', 'Expected Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListWorkTransportation', 'Have Transportation', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years or older?', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListAliases', 'Any other names', 'trim|xss_clean');
            $this->form_validation->set_rules('nickname_or_othername_details', 'other name explaination', 'trim|xss_clean');
            //$this->form_validation->set_rules('RadioButtonListCriminalWrongDoing', 'ever plead Guilty', 'trim|xss_clean');
            //$this->form_validation->set_rules('RadioButtonListCriminalBail', 'been arrested?', 'trim|xss_clean');
            //$this->form_validation->set_rules('arrested_pending_trail_details', 'been arrested details', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListDriversLicenseQuestion', 'Drivers License Question', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'Drivers License Number', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListDriversState', 'Drivers State', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListDriversCountry', 'Drivers Country', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'Expiration date', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'Drivers License Plead Guilty', 'trim|xss_clean');
            $this->form_validation->set_rules('license_guilty_details', 'license guilty details', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationHighSchoolName', 'Education High School Name', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEducationHighSchoolGraduated', 'High School Graduated', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationHighSchoolCity', 'Education City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolState', 'Education State', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolCountry', 'Education Country', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearBegin', 'Year', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationHighSchoolDateAttendedYearEnd', 'Year', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationCollegeName', 'College / University', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEducationCollegeGraduated', 'Did you graduate?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationCollegeCity', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeState', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeCountry', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthBegin', 'Month begin', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearBegin', 'Year', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationCollegeDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationCollegeMajor', 'Major', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationCollegeDegree', 'Degree Earned', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationOtherName', 'Other School', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEducationOtherGraduated', 'Did you graduate?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationOtherCity', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherState', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherCountry', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthBegin', 'Dates of Attendance', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearBegin', 'Strat Year', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedMonthEnd', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEducationOtherDateAttendedYearEnd', 'Year End', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationOtherMajor', 'Other Major', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationOtherDegree', 'Other Degree', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseName', 'Professional License Type', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseIssuingAgencyState', 'Issuing Agency/State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEducationProfessionalLicenseNumber', 'License Number', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position/Title', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerCountry1', 'Country', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Dates of Employment', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Year Begin', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Year End', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin1', 'Starting Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd1', 'Ending Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'May we contact this employer?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Employer Reason Leave', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerName2', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition2', 'Position/Title', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress2', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCity2', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerState2', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber2', 'Telephone', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin2', 'Dates of Employment', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin2', 'Year Begin', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd2', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd2', 'Year End', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin2', 'Starting Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd2', 'Ending Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor2', 'Supervisor', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact2_0', 'May we contact this employer?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave2', 'Employer Reason Leave', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerName3', 'Employment Current / Most Recent Employer', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition3', 'Position/Title', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress3', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCity3', 'City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerState3', 'State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber3', 'Telephone', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin3', 'Dates of Employment', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin3', 'Year Begin', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd3', 'Month End', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd3', 'Year End', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationBegin3', 'Starting Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerCompensationEnd3', 'Ending Compensation', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor3', 'Supervisor', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact3_0', 'May we contact this employer?', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave3', 'Employer Reason Leave', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEmploymentEverTerminated', 'Employment Ever Terminated', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEverTerminatedReason', 'Ever Terminated Reason', 'trim|xss_clean');
            $this->form_validation->set_rules('RadioButtonListEmploymentEverResign', 'Employment Ever Resign', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEverResignReason', 'Employment Resign Reason', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentGaps', 'Employer Gaps', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxEmploymentEmployerNoContact', 'Employer No Contact', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceName1', ' Reference Name', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAcquainted1', 'Reference Acquainted', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAddress1', 'Reference Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceCity1', 'Reference City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListReferenceState1', 'Reference State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber1', 'Telephone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceEmail1', 'Reference Email', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceName2', 'Reference Name', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAcquainted2', 'Reference Acquainted', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAddress2', 'Reference Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceCity2', 'Reference City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListReferenceState2', 'Reference State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber2', 'Telephone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceEmail2', 'Reference Email', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceName3', 'Reference Name', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAcquainted3', 'Reference Acquainted', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceAddress3', 'Reference Address', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceCity3', 'Reference City', 'trim|xss_clean');
            $this->form_validation->set_rules('DropDownListReferenceState3', 'Reference State', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceTelephoneNumber3', 'Telephone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxReferenceEmail3', 'Reference Email', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAdditionalInfoWorkExperience', 'Additional Work Experience Information', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAdditionalInfoWorkTraining', 'Additional Work Training Information', 'trim|xss_clean');
            $this->form_validation->set_rules('TextBoxAdditionalInfoWorkConsideration', 'Additional Work Consideration Information', 'trim|xss_clean');
            $this->form_validation->set_rules('CheckBoxAgreement1786', 'CheckBoxAgreement1786', 'required|trim|xss_clean');
            $this->form_validation->set_rules('CheckBoxAgree', 'Acknowledge Agree', 'required|trim|xss_clean');
            //
            $ei = unserialize($data['session']['company_detail']['extra_info']);
            //
            $data['eight_plus'] = 0;
            $data['affiliate'] = 0;
            $data['d_license'] = 0;
            $data['l_employment'] = 0;
            $data['ssn_required'] = $data['session']['portal_detail']['ssn_required'];
            $data['dob_required'] = $data['session']['portal_detail']['dob_required'];
            //
            if ($data['ssn_required'] == 1) {
                //
                $this->form_validation->set_rules('TextBoxSSN', 'TextBoxSSN', 'required|trim|xss_clean');
            }
            //
            if ($data['dob_required'] == 1) {
                //
                $this->form_validation->set_rules('TextBoxDOB', 'Date of Birth', 'required|trim|xss_clean');
            }

            if (isset($ei['affiliate'])) {
                $data['affiliate'] = $ei['affiliate'];
            }
            if (isset($ei['18_plus'])) {
                $data['eight_plus'] = $ei['18_plus'];
            }
            if (isset($ei['d_license'])) {
                $data['d_license'] = $ei['d_license'];
            }
            if (isset($ei['l_employment'])) {
                $data['l_employment'] = $ei['l_employment'];
            }
            //
            if ($data['d_license'] && $this->input->post('RadioButtonListDriversLicenseQuestion', true) != 'No') {
                $this->form_validation->set_rules('TextBoxDriversLicenseNumber', 'License Number', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxDriversLicenseExpiration', 'License Expiration Date', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListDriversCountry', 'License Country', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListDriversState', 'License State', 'required|trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListDriversLicenseTraffic', 'guilty', 'required|trim|xss_clean');

                if ($this->input->post('RadioButtonListDriversLicenseTraffic', true) != 'No') {
                    $this->form_validation->set_rules('license_guilty_details_violation', 'Violation', 'required|trim|xss_clean');
                }
            }

            //
            if ($data['l_employment']) {
                $this->form_validation->set_rules('TextBoxEmploymentEmployerName1', 'Employment Type', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPosition1', 'Position', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerAddress1', 'Address', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerCountry1', 'Country', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerState1', 'State', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerCity1', 'City', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerPhoneNumber1', 'Telephone', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthBegin1', 'Employment Start Month', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearBegin1', 'Employment Start Year', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentMonthEnd1', 'Employment End Month', 'required|trim|xss_clean');
                $this->form_validation->set_rules('DropDownListEmploymentEmployerDatesOfEmploymentYearEnd1', 'Employment End Year', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerSupervisor1', 'Supervisor', 'required|trim|xss_clean');
                $this->form_validation->set_rules('RadioButtonListEmploymentEmployerContact1_0', 'Contact', 'required|trim|xss_clean');
                $this->form_validation->set_rules('TextBoxEmploymentEmployerReasonLeave1', 'Reason', 'required|trim|xss_clean');
            }

            //
            if ($data['eight_plus']) {
                $this->form_validation->set_rules('RadioButtonListWorkOver18', '18 years', 'required|trim|xss_clean');
            }

            //
            if ($data['affiliate']) {
                $this->form_validation->set_rules('is_already_employed', 'Already Employed', 'required|trim|xss_clean');
            }
            $this->load->model('manage_admin/documents_model');

            //
            $data['_ssv'] = $_ssv = getSSV($data['session']['employer_detail']);



            if ($this->form_validation->run() === FALSE) {
                //
                if (!empty(validation_errors())) {
                    sendMail(
                        FROM_EMAIL_NOTIFICATIONS,
                        'mubashir.saleemi123@gmail.com',
                        'Form Full Application Validation Error',
                        @json_encode(validation_errors())
                    );
                }
                //
                $data_countries = db_get_active_countries(); //Get Countries and States - Start

                foreach ($data_countries as $value) {
                    $data_states[$value['sid']] = db_get_active_states($value['sid']);
                }

                $data['active_countries'] = $data_countries;
                $data['active_states'] = $data_states;
                $data_states_encode = htmlentities(json_encode($data_states));
                $data['states'] = $data_states_encode;
                //Get Countries and States - End
                $ip_track = $this->documents_model->get_document_ip_tracking_record($company_id, 'full_employment_application', $sid, 'employee');
                $data['ip_track'] = $ip_track;

                // Check and set the company sms module
                // phone number
                company_sms_phonenumber(
                    $data['session']['company_detail']['sms_module_status'],
                    $company_id,
                    $data,
                    $this
                );

                //
                // $fullEmploymentForm = unserialize($data['employer']['full_employment_application']);
                // //
                // $updateArray = $fullEmploymentForm;
                // // Check for DOB
                // if((empty($fullEmploymentForm['TextBoxDOB']) || !isset($fullEmploymentForm['TextBoxDOB'])) && !empty($data['employer']['dob'])){
                //     $data['formpost'] = $updateArray['TextBoxDOB'] = DateTime::createfromformat('Y-m-d',$fullEmploymentForm['TextBoxDOB'])->format('m-d-Y');
                // }
                // // Check for SSN
                // if((empty($fullEmploymentForm['TextBoxSSN']) || !isset($fullEmploymentForm['TextBoxSSN'])) && !empty($data['employer']['dob'])){
                //     $data['formpost'] = $updateArray['TextBoxSSN'] = $fullEmploymentForm['TextBoxSSN'];
                // }
                // //
                // if($updateArray){
                //     $this->db->where('sid', $data['employer']['sid'])->update('users', ['full_employment_application' => serialize($updateArray)]);
                // }

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/full_employment_application');
                $this->load->view('main/footer');
            } else {
                $company_sid = $data["session"]["company_detail"]["sid"];
                $employer_sid = $data["session"]["employer_detail"]["sid"];
                $reload_location = 'full_employment_application/' . $sid;
                $formpost = $this->input->post(NULL, TRUE);
                $full_employment_application = array();
                $driving_no = '';
                $driving_exp = '';

                foreach ($formpost as $key => $value) {
                    if ($key != 'action' && $key != 'first_name' && $key != 'last_name' && $key != 'email' && $key != 'Location_Address' && $key != 'Location_City' && $key != 'Location_State' && $key != 'Location_ZipCode' && $key != 'PhoneNumber') { // exclude these values from array
                        $full_employment_application[$key] = $value;
                        if ($key == 'TextBoxDriversLicenseNumber') {
                            $driving_no = $value;
                        } elseif ($key == 'TextBoxDriversLicenseExpiration') {
                            $driving_exp = $value;
                        }
                    }
                }

                $drive_data = $data['drivers_license_details'];
                if (!empty($driving_no)) {
                    $drive_data['license_number'] = $driving_no;
                }
                if (!empty($driving_exp)) {
                    $drive_data['license_expiration_date'] = $driving_exp;
                }
                if (!empty($driving_no) || !empty($driving_exp)) {
                    if (sizeof($drivers_license)) {
                        $drivers_license_serial_data['license_details'] = serialize($drive_data);
                        $this->dashboard_model->update_license_info($drivers_license['sid'], $drivers_license_serial_data);
                    } else {
                        $drivers_license = array();
                        $drivers_license['users_sid'] = $sid;
                        $drivers_license['users_type'] = 'employee';
                        $drivers_license['license_type'] = 'drivers';
                        $drivers_license['license_details'] = serialize($drive_data);
                        $this->dashboard_model->save_license_info($drivers_license);
                    }
                }

                $full_employment_application['client_ip'] = getUserIP();
                $full_employment_application['client_signature_timestamp'] = date('Y-m-d H:i:s');
                $this->documents_model->insert_document_ip_tracking_record($company_sid, $employer_sid, getUserIP(), 'full_employment_application', 'pre_filled', $_SERVER['HTTP_USER_AGENT'], $sid, 'employee');

                if (!$load_view) {
                    $id = $formpost['sid'];
                } else {
                    $id = $formpost['applicant_sid'];
                }
                $data["employer"]['extra_info']['other_email'] = $formpost['TextBoxAddressStreetFormer3'];
                $data["employer"]['extra_info']['other_PhoneNumber'] = $formpost['TextBoxTelephoneOther'];

                $data = array(
                    'first_name' => $formpost['first_name'],
                    'last_name' => $formpost['last_name'],
                    'email' => $formpost['email'],
                    'Location_Address' => $formpost['Location_Address'],
                    'Location_City' => $formpost['Location_City'],
                    'Location_State' => $formpost['Location_State'],
                    'Location_Country' => $formpost['Location_Country'],
                    'Location_ZipCode' => $formpost['Location_ZipCode'],
                    'PhoneNumber' => isset($formpost['txt_phonenumber']) ? $formpost['txt_phonenumber'] : $formpost['PhoneNumber'],
                    'extra_info' => serialize($data["employer"]['extra_info']),
                    'full_employment_application' => serialize($full_employment_application)
                );

                $record =
                    $this->db
                    ->select('full_employment_application')
                    ->where('sid', $id)
                    ->get('users')
                    ->row_array();
                //
                $fef = [];

                //
                if ($record) {
                    $fef = unserialize($record['full_employment_application']);
                }

                //
                if (isSecret($full_employment_application['TextBoxSSN'])) {
                    $full_employment_application['TextBoxSSN'] = $fef['TextBoxSSN'];
                }
                //
                if (isSecret($full_employment_application['TextBoxDOB'])) {
                    $full_employment_application['TextBoxDOB'] = $fef['TextBoxDOB'];
                }
                //
                $data['full_employment_application'] = serialize($full_employment_application);
                //
                if (isset($formpost['TextBoxDOB']) && !empty($formpost['TextBoxDOB']) && !isSecret($formpost['TextBoxDOB'])) {
                    $DOB = date('Y-m-d', strtotime(str_replace('-', '/', $formpost['TextBoxDOB'])));
                    $data['dob'] = $DOB;
                }
                //
                if (isset($formpost['TextBoxNameMiddle']) && !empty($formpost['TextBoxNameMiddle'])) {
                    $data['middle_name'] = $formpost['TextBoxNameMiddle'];
                }
                //
                if (isset($formpost['TextBoxSSN']) && !empty($formpost['TextBoxSSN']) && !isSecret($formpost['TextBoxSSN'])) {
                    $data['ssn'] = $formpost['TextBoxSSN'];
                }
                //
                $this->dashboard_model->update_user($id, $data);
                $this->session->set_flashdata('message', '<b>Success:</b> Full employment form updated successfully');
                redirect($reload_location, "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function alpha_dash_space($str)
    {
        if ($str != "") {
            if (!preg_match("/^([-0-9])+$/i", $str)) {
                $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain numeric characters and dashes.');
                return FALSE;
            } else {
                return TRUE;
            }
        } else
            return TRUE;
    }

    public function w4form($type = NULL, $sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            if ($type == 'employee') {
                $employer_id = $sid;
                $left_navigation = 'profile_left_menu_employee';
                $data_function['title'] = 'Employee / Team Members W4 form and Tax withholding';
                $reload_location = 'i9form/employee/' . $sid;
            }

            if ($type == 'applicant') {
                $employer_id = $sid;
                $left_navigation = 'profile_left_menu_applicant';
                $data_function['title'] = 'Applicant W4 form and Tax withholding';
                $reload_location = 'i9form/applicant/' . $sid;
            }

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'profile_left_menu_personal';
                $data_function['title'] = 'W4 form and Tax withholding';
                $reload_location = 'i9form';
                $type = 'employee';
            }

            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/w4form');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function equipment_info($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data_function['session'] = $this->session->userdata('logged_in');
            $security_sid = $data_function['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data_function['security_details'] = $security_details;
            $company_id = $data_function['session']['company_detail']['sid'];
            $employer_access_level = $data_function['session']['employer_detail']['access_level'];

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data_function['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data_function['title'] = 'Equipment Information';
                $reload_location = 'equipment_info';
                $type = 'employee';
                $data_function['employee'] = $data_function['session']['employer_detail'];
                $data_function["return_title_heading"] = "My Profile";
                $data_function["return_title_heading_link"] = base_url() . 'my_profile';
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee');
                $load_view = check_blue_panel_status(false, 'self');
            } elseif ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_equipment_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = employee_right_nav($sid);
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Members Equipment Information';
                $reload_location = 'equipment_info/employee/' . $sid;
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($sid, 'employee');
                $load_view = false;
            } elseif ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_equipment_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = applicant_right_nav($sid);
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Equipment Information';
                $reload_location = 'equipment_info/applicant/' . $sid . '/' . $jobs_listing;
                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data_function["cancel_url"] = 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $load_view = false;
            }
            $data_function['load_view'] = $load_view;
            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;
            $data_function['questions_sent'] = $this->application_tracking_model->check_sent_video_questionnaires($sid, $company_id);
            $data_function['questions_answered'] = $this->application_tracking_model->check_answered_video_questionnaires($sid, $company_id);
            $data_function['job_list_sid'] = $jobs_listing;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Equipment Information not found!');
                redirect('dashboard', 'refresh');
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            $equipment_info_data = $this->dashboard_model->get_equipment_info($type, $employer_id);
            //            echo '<pre>';
            //            print_r($equipment_info_data);
            //            die();
            //            if (!empty($equipment_info_data[0]['equipment_details'])) {
            //                $equipment_info_data = unserialize($equipment_info_data[0]['equipment_details']);
            //                $selected = explode('_details',$equipment_info_data['equipment_type'])[0] ;
            //                $selected = $selected == 'company_vehicles' ? 'vehicles' : $selected;
            //            }

            $data_function['equipment_info'] = $equipment_info_data;
            //            $data_function['selected'] = $selected;
            //            echo '<pre>';
            //            print_r(unserialize($equipment_info_data[0]['equipment_details']));
            //            die();

            if ($type == 'employee') {
                $data_function["employer"] = $this->dashboard_model->get_company_detail($employer_id);
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                //                $data_function['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data_function['company_background_check'] = checkCompanyAccurateCheck($data_function["session"]["company_detail"]["sid"]);
                //Outsourced HR Compliance and Onboarding check
                $data_function['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data_function["session"]["company_detail"]["sid"]);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data_function['employer'] = $data_employer;
            }

            $url = '';
            switch ($type) {
                case 'employee':
                    $parent_sid = getEmployeeUserParent_sid($employer_id);

                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                            $url = base_url('employee_management');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Does Not Exist In Db.
                        $url = base_url('employee_management');
                        redirect($url, 'refresh');
                    }
                    break;
                case 'applicant':
                    $parent_sid = getApplicantsEmployer_sid($employer_id);

                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.
                            $url = base_url('application_tracking_system/active/all/all/all/all');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Does Not Exist In Db.
                        $url = base_url('application_tracking_system/active/all/all/all/all');
                        redirect($url, 'refresh');
                    }
                    break;
            }
            $data_function['job_list_sid'] = $jobs_listing;

            $this->form_validation->set_rules('equipment_type', 'Equipment type', 'required');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/equipment_info');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(null, true);
                $action_flag = $formpost['action_flag'];
                $action_sid = $formpost['action_sid'];
                $equipmentData['users_sid'] = $employer_id;
                $equipmentData['users_type'] = $type;
                //                $equipmentData['equipment_details'] = serialize($formpost);
                $equipmentData['equipment_details'] = NULL;
                $equipmentCheck = $this->dashboard_model->check_user_equipment($employer_id, $type);

                //                if ($equipmentCheck->num_rows() > 0) {
                //                    $equipment_data = $equipmentCheck->result_array();
                //                    $equipment_id = $equipment_data[0]['sid'];
                //                    $this->dashboard_model->update_equipment_info($equipment_id, $equipmentData);
                //                } else {
                //                    $this->dashboard_model->save_equipment_info($equipmentData);
                //                }


                // New Flow
                $equipment_type = $formpost['equipment_type'];
                $insert_array = array();
                if ($equipment_type == 'cellphone_details') {
                    $insert_array['equipment_type'] = 'cellphone';
                    $insert_array['brand_name']     = $formpost['cellphone_brand_name'];
                    $insert_array['imei_no']        = $formpost['cellphone_imei_no'];
                    $insert_array['model']          = $formpost['cellphone_model'];
                    $insert_array['issue_date']     = !empty($formpost['cellphone_issue_date']) ? DateTime::createFromFormat('m-d-Y', $formpost['cellphone_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                    $insert_array['color']          = $formpost['cellphone_color'];
                    $insert_array['notes']          = $formpost['cellphone_notes'];
                    $insert_array['product_id']     = '';
                    $insert_array['vin_number']     = '';
                    $insert_array['transmission_type'] = '';
                    $insert_array['fuel_type']      = '';
                    $insert_array['serial_number']  = '';
                    $insert_array['specification']  = '';
                } else if ($equipment_type == 'laptops_details') {
                    $insert_array['equipment_type'] = 'laptop';
                    $insert_array['brand_name']     = $formpost['laptop_brand_name'];
                    $insert_array['product_id']     = $formpost['laptop_product_id'];
                    $insert_array['model']          = $formpost['laptop_model'];
                    $insert_array['issue_date']     = !empty($formpost['laptop_issue_date']) ? DateTime::createFromFormat('m-d-Y', $formpost['laptop_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                    $insert_array['color']          = $formpost['laptop_color'];
                    $insert_array['notes']          = $formpost['laptop_notes'];
                    $insert_array['vin_number']     = '';
                    $insert_array['transmission_type'] = '';
                    $insert_array['fuel_type']      = '';
                    $insert_array['serial_number']  = '';
                    $insert_array['specification']  = $formpost['laptop_specification'];
                    $insert_array['imei_no']        = '';
                } else if ($equipment_type == 'company_vehicles_details') {
                    $insert_array['equipment_type'] = 'vehicle';
                    $insert_array['brand_name']     = $formpost['vehicles_brand_name'];
                    $insert_array['vin_number']     = $formpost['vehicles_engine_no'];
                    $insert_array['model']          = $formpost['vehicles_model'];
                    $insert_array['issue_date']     = !empty($formpost['vehicles_issue_date']) ? DateTime::createFromFormat('m-d-Y', $formpost['vehicles_issue_date'])->format('Y-m-d') : date('Y-m-d H:i:s');
                    $insert_array['color']          = $formpost['vehicles_color'];
                    $insert_array['transmission_type']          = $formpost['vehicles_transmisssion_type'];
                    $insert_array['fuel_type']      = $formpost['vehicles_fuel_type'];
                    $insert_array['notes']          = $formpost['vehicles_notes'];
                    $insert_array['imei_no']        = '';
                    $insert_array['serial_number']  = '';
                    $insert_array['product_id']     = '';
                    $insert_array['specification']  = '';
                } else if ($equipment_type == 'tablets_details') {
                    $insert_array['equipment_type'] = 'tablet';
                    $insert_array['brand_name']     = $formpost['tablets_brand_name'];
                    $insert_array['serial_number']  = $formpost['tablets_serial_no'];
                    $insert_array['model']          = $formpost['tablets_model'];
                    $insert_array['issue_date']     = !empty($formpost['tablets_issue_date']) ? DateTime::createFromFormat('m-d-Y', $formpost['tablets_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                    $insert_array['color']          = $formpost['tablets_color'];
                    $insert_array['specification']  = $formpost['tablets_specification'];
                    $insert_array['notes']          = $formpost['tablets_notes'];
                    $insert_array['imei_no']        = '';
                    $insert_array['vin_number']     = '';
                    $insert_array['transmission_type'] = '';
                    $insert_array['fuel_type']      = '';
                    $insert_array['product_id']     = '';
                } else if ($equipment_type == 'other1_details') {
                    $insert_array['equipment_type'] = 'other';
                    $insert_array['brand_name']     = $formpost['other1_brand_name'];
                    $insert_array['serial_number']  = $formpost['other1_serial_no'];
                    $insert_array['model']          = $formpost['other1_model'];
                    $insert_array['issue_date']     = !empty($formpost['other1_issue_date']) ? DateTime::createFromFormat('m-d-Y', $formpost['other1_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                    $insert_array['color']          = $formpost['other1_color'];
                    $insert_array['specification']  = $formpost['other1_specification'];
                    $insert_array['notes']          = $formpost['other1_notes'];
                    $insert_array['vin_number']     = '';
                    $insert_array['imei_no']        = '';
                    $insert_array['transmission_type'] = '';
                    $insert_array['fuel_type']      = '';
                    $insert_array['product_id']     = '';
                }
                if ($action_flag == 'add') {
                    $insert_array['assigned_id']        = $employer_id;
                    $insert_array['assigned_by_ip']     = getUserIP();
                    $this->dashboard_model->save_equipment_info(array_merge($equipmentData, $insert_array));
                } else {
                    $this->dashboard_model->update_equipment_info($action_sid, $insert_array);
                }

                $this->load->model('direct_deposit_model');
                $this->load->model('hr_documents_management_model');
                $userData = $this->direct_deposit_model->getUserData($sid, $type);
                //
                $doSend = false;
                //
                if (array_key_exists('document_sent_on', $userData)) {
                    //
                    $doSend = false;
                    //
                    if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                        $doSend = true;
                        //
                        $this->hr_documents_management_model->update_employee($sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                    }
                }

                // Only send if dosend is true
                if ($doSend == true) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'equipment_info',
                        $company_id,
                        $data_function['session']['company_detail']['CompanyName'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $sid
                    );
                }

                $this->session->set_flashdata('message', '<b>Success:</b> Equipment(s) info saved successfully');
                redirect(base_url($reload_location));
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function delete_equipment()
    {
        $sid = $this->input->post('sid');
        $update_array = array(
            'delete_flag'     =>  1,
            'delete_by_ip'       =>  getUserIP(),
            'delete_by_id'       =>  $this->session->userdata('logged_in')['employer_detail']['sid'],
            'delete_datetime' => date('Y-m-d H:i:s')
        );
        $this->dashboard_model->update_equipment_info($sid, $update_array);
        $this->session->set_flashdata('message', '<strong>Message:</strong> Equipment(s) info deleted successfully');
        echo 'success';
    }

    public function equipment_script()
    {
        $equipment_info_data = $this->dashboard_model->get_serialized_equipment_info();

        foreach ($equipment_info_data as $info) {
            $serialize = unserialize($info['equipment_details']);

            if (!empty($serialize['cellphone_brand_name'])) {
                $insert_array['equipment_type'] = 'cellphone';
                $insert_array['brand_name']     = isset($serialize['cellphone_brand_name']) ? $serialize['cellphone_brand_name'] : '';
                $insert_array['imei_no']        = isset($serialize['cellphone_imei_no']) ? $serialize['cellphone_imei_no'] : '';
                $insert_array['model']          = isset($serialize['cellphone_model']) ? $serialize['cellphone_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['cellphone_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['cellphone_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['cellphone_color']) ? $serialize['cellphone_color'] : '';
                $insert_array['notes']          = isset($serialize['cellphone_notes']) ? $serialize['cellphone_notes'] : '';
                $insert_array['product_id']     = '';
                $insert_array['vin_number']     = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['serial_number']  = '';
                $insert_array['specification']  = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['laptop_brand_name'])) {
                //                echo '<pre>'; print_r($serialize); exit;
                $insert_array['equipment_type'] = 'laptops';
                $insert_array['brand_name']     = isset($serialize['laptop_brand_name']) ? $serialize['laptop_brand_name'] : '';
                $insert_array['product_id']     = isset($serialize['laptop_product_id']) ? $serialize['laptop_product_id'] : '';
                $insert_array['model']          = isset($serialize['laptop_model']) ? $serialize['laptop_model'] : '';
                //                $insert_array['issue_date']     = !empty($serialize['laptop_issue_date']) ? DateTime::createFromFormat('m-d-y', $serialize['laptop_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['laptop_color']) ? $serialize['laptop_color'] : '';
                $insert_array['notes']          = isset($serialize['laptop_notes']) ? $serialize['laptop_notes'] : '';
                $insert_array['vin_number']     = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['serial_number']  = '';
                $insert_array['specification']  = '';
                $insert_array['imei_no']        = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['vehicles_brand_name'])) {
                $insert_array['equipment_type'] = 'vehicles';
                $insert_array['brand_name']     = isset($serialize['vehicles_brand_name']) ? $serialize['vehicles_brand_name'] : '';
                $insert_array['vin_number']     = isset($serialize['vehicles_engine_no']) ? $serialize['vehicles_engine_no'] : '';
                $insert_array['model']          = isset($serialize['vehicles_model']) ? $serialize['vehicles_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['vehicles_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['vehicles_issue_date'])->format('Y-m-d') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['vehicles_color']) ? $serialize['vehicles_color'] : '';
                $insert_array['transmission_type']          = $serialize['vehicles_transmisssion_type'];
                $insert_array['fuel_type']      = isset($serialize['vehicles_fuel_type']) ? $serialize['vehicles_fuel_type'] : '';
                $insert_array['notes']          = isset($serialize['vehicles_notes']) ? $serialize['vehicles_notes'] : '';
                $insert_array['imei_no']        = '';
                $insert_array['serial_number']  = '';
                $insert_array['product_id']     = '';
                $insert_array['specification']  = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['tablets_brand_name'])) {
                $insert_array['equipment_type'] = 'tablets';
                $insert_array['brand_name']     = isset($serialize['tablets_brand_name']) ? $serialize['tablets_brand_name'] : '';
                $insert_array['serial_number']  = isset($serialize['tablets_serial_no']) ? $serialize['tablets_serial_no'] : '';
                $insert_array['model']          = isset($serialize['tablets_model']) ? $serialize['tablets_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['tablets_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['tablets_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['tablets_color']) ? $serialize['tablets_color'] : '';
                $insert_array['specification']  = isset($serialize['tablets_specification']) ? $serialize['tablets_specification'] : '';
                $insert_array['notes']          = isset($serialize['tablets_notes']) ? $serialize['tablets_notes'] : '';
                $insert_array['imei_no']        = '';
                $insert_array['vin_number']     = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['product_id']     = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['other1_brand_name'])) {
                $insert_array['equipment_type'] = 'others';
                $insert_array['brand_name']     = isset($serialize['other1_brand_name']) ? $serialize['other1_brand_name'] : '';
                $insert_array['serial_number']  = isset($serialize['other1_serial_no']) ? $serialize['other1_serial_no'] : '';
                $insert_array['model']          = isset($serialize['other1_model']) ? $serialize['other1_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['other1_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['other1_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                //                $insert_array['color']          = isset($serialize['other1_color']) ? $info['other1_color'] : '';
                $insert_array['specification']  = isset($serialize['other1_specification']) ? $serialize['other1_specification'] : '';
                $insert_array['notes']          = isset($serialize['other1_notes']) ? $serialize['other1_notes'] : '';
                $insert_array['vin_number']     = '';
                $insert_array['imei_no']        = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['product_id']     = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['other2_brand_name'])) {
                $insert_array['equipment_type'] = 'others';
                $insert_array['brand_name']     = isset($serialize['other2_brand_name']) ? $serialize['other2_brand_name'] : '';
                $insert_array['serial_number']  = isset($serialize['other2_serial_no']) ? $serialize['other2_serial_no'] : '';
                $insert_array['model']          = isset($serialize['other2_model']) ? $serialize['other2_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['other2_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['other2_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['other2_color']) ? $serialize['other2_color'] : '';
                $insert_array['specification']  = isset($serialize['other2_specification']) ? $serialize['other2_specification'] : '';
                $insert_array['notes']          = isset($serialize['other2_notes']) ? $serialize['other2_notes'] : '';
                $insert_array['vin_number']     = '';
                $insert_array['imei_no']        = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['product_id']     = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
            if (!empty($serialize['other3_brand_name'])) {
                $insert_array['equipment_type'] = 'others';
                $insert_array['brand_name']     = isset($serialize['other3_brand_name']) ? $serialize['other3_brand_name'] : '';
                $insert_array['serial_number']  = isset($serialize['other3_serial_no']) ? $serialize['other3_serial_no'] : '';
                $insert_array['model']          = isset($serialize['other3_model']) ? $serialize['other3_model'] : '';
                $insert_array['issue_date']     = !empty($serialize['other3_issue_date']) ? DateTime::createFromFormat('m-d-Y', $serialize['other3_issue_date'])->format('Y-m-d H:i:s') : date('Y-m-d H:i:s');
                $insert_array['color']          = isset($serialize['other3_color']) ? $serialize['other3_color'] : '';
                $insert_array['specification']  = isset($serialize['other3_specification']) ? $serialize['other3_specification'] : '';
                $insert_array['notes']          = isset($serialize['other3_notes']) ? $serialize['other3_notes'] : '';
                $insert_array['vin_number']     = '';
                $insert_array['imei_no']        = '';
                $insert_array['transmission_type'] = '';
                $insert_array['fuel_type']      = '';
                $insert_array['product_id']     = '';
                $insert_array['users_sid'] = $info['users_sid'];
                $insert_array['users_type'] = $info['users_type'];
                $insert_array['equipment_details'] = NULL;
                $eqipment_info_sid = $info['sid'];
                $data_to_update = array();
                $data_to_update['equipment_details'] = NULL;
                $this->dashboard_model->update_equipment_info($eqipment_info_sid, $data_to_update);
                $this->dashboard_model->save_equipment_info($insert_array);
            }
        }
    }

    public function drivers_license_info($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data_function['session'] = $this->session->userdata('logged_in');
            $security_sid = $data_function['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data_function['security_details'] = $security_details;
            $company_id = $data_function['session']['company_detail']['sid'];
            $employer_access_level = $data_function['session']['employer_detail']['access_level'];


            if ($sid == NULL && $type == NULL) {
                $employer_id = $data_function['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data_function['title'] = 'Drivers License Information';
                $reload_location = 'drivers_license_info';
                $type = 'employee';
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee');
                $load_view = check_blue_panel_status(false, 'self');
                $cancel_url = 'my_profile';
            } elseif ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_drivers_license_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = employee_right_nav($sid);
                $data_function['session'] = $this->session->userdata('logged_in');
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Members Drivers License Information';
                $reload_location = 'drivers_license_info/employee/' . $sid;
                $cancel_url = 'drivers_license_info/employee/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($sid, 'employee');
                $load_view = check_blue_panel_status(false, $type);
            } elseif ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_drivers_license_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = applicant_right_nav($sid);
                $data_function['session'] = $this->session->userdata('logged_in');
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Drivers License Information';
                $reload_location = 'drivers_license_info/applicant/' . $sid . '/' . $jobs_listing;
                $cancel_url = 'drivers_license_info/applicant/' . $sid . '/' . $jobs_listing;
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($sid, 'applicant');
                $load_view = check_blue_panel_status(false, $type);
            }

            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;
            $data_function['questions_sent'] = $this->application_tracking_model->check_sent_video_questionnaires($sid, $company_id);
            $data_function['questions_answered'] = $this->application_tracking_model->check_answered_video_questionnaires($sid, $company_id);

            if ($type == 'employee') {
                $data_function["employer"] = $this->dashboard_model->get_company_detail($employer_id);
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $full_emp_form = array();
                if (sizeof($data_function["employer"])) {
                    $full_emp_form = !empty($data_function["employer"]['full_employment_application']) && $data_function["employer"]['full_employment_application'] != NULL ? unserialize($data_function["employer"]['full_employment_application']) : array();
                }
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                $full_emp_form = array();
                if (sizeof($applicant_info)) {
                    $full_emp_form = !empty($applicant_info['full_employment_application']) && $applicant_info['full_employment_application'] != NULL ? unserialize($applicant_info['full_employment_application']) : array();
                }
                //                $data_function['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data_function['company_background_check'] = checkCompanyAccurateCheck($data_function["session"]["company_detail"]["sid"]);
                //Outsourced HR Compliance and Onboarding check
                $data_function['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data_function["session"]["company_detail"]["sid"]);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'dob'             => $applicant_info['dob'],
                    'user_type' => 'Applicant'
                );

                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data_function['employer'] = $data_employer;
                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
            }

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Drivers License Information not found!');
                redirect('dashboard', 'refresh');
            }

            $url = '';
            switch ($type) {
                case 'employee':
                    $parent_sid = getEmployeeUserParent_sid($employer_id);
                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                            $url = base_url('employee_management');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Does Not Exist In Db.
                        $url = base_url('employee_management');
                        redirect($url, 'refresh');
                    }
                    break;
                case 'applicant':
                    $parent_sid = getApplicantsEmployer_sid($employer_id);
                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.
                            $url = base_url('application_tracking_system/active/all/all/all/all');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Does Not Exist In Db.
                        $url = base_url('application_tracking_system/active/all/all/all/all');
                        redirect($url, 'refresh');
                    }
                    break;
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            $license_type = "drivers";
            $license_info_data = $this->dashboard_model->get_license_info($employer_id, $type, $license_type);

            if (!empty($license_info_data[0]['license_details'])) {
                $license_info_data = array_merge($license_info_data[0], unserialize($license_info_data[0]['license_details']));
            }

            $data_function['license_info'] = $license_info_data;
            $data_function['license_type'] = $license_type;
            $license_types = array();
            $license_types['Sales License'] = 'Sales License';
            $license_types['Commercial Driver’s License'] = 'Commercial Driver’s License';
            $license_types['Non-commercial Driver’s License'] = 'Non-commercial Driver’s License';
            $license_types['Restricted Driver’s License'] = 'Restricted Driver’s License';
            $license_types['Basic Driver’s License'] = 'Basic Driver’s License';
            $license_types['Identification Card'] = 'Identification Card';
            $license_types['College Diploma'] = 'College Diploma';
            $license_types['Training'] = 'Training';
            $license_types['Other'] = 'Other';
            $data_function['license_types'] = $license_types;
            $license_classes = array();
            $license_classes['Class A'] = 'Class A';
            $license_classes['Class B'] = 'Class B';
            $license_classes['Class C'] = 'Class C';
            $data_function['license_classes'] = $license_classes;
            $data_function['employee'] = $data_function['session']['employer_detail'];
            $data_function['security_details'] = $security_details;
            //form data valdation starts
            $this->form_validation->set_rules('license_type', 'License type', 'trim');
            //form data valdation ends
            $data_function['job_list_sid'] = $jobs_listing;
            $data_function['cancel_url'] = $cancel_url;
            $data_function['load_view'] = $load_view;
            //
            $data_function['_ssv'] = $_ssv = getSSV($data_function['session']['employer_detail']);
            //
            if ($this->form_validation->run() === FALSE) {
                $data_function['user_sid'] = $sid;
                $data_function['user_type'] = $type;
                $data_function['company_sid'] = $company_id;
                //                if ($load_view == 'old') {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/license_info');
                $this->load->view('main/footer');
                //                } else {
                //                    $this->load->view('onboarding/on_boarding_header', $data_function);
                //                    $this->load->view('onboarding/license_info');
                //                    $this->load->view('onboarding/on_boarding_footer');
                //                }
            } else {
                $formpost = $this->input->post(null, true);
                $licenseData['users_sid'] = $employer_id;
                $licenseData['users_type'] = $type;
                $licenseData['license_type'] = 'drivers';
                $licenseCheck = $this->dashboard_model->check_user_license($employer_id, $type, $license_type);
                //$formpost['license_file'] = "";

                $license_file = upload_file_to_aws('license_file', $company_id, 'license_file', $employer_id);

                if (!empty($license_file) && $license_file != 'error') {
                    $licenseData['license_file'] = $license_file;
                }

                if ($type == 'employee') {
                    //
                    $this->load->model('2022/User_model', 'em');
                    //
                    $this->em->handleGeneralDocumentChange(
                        'driversLicense',
                        $this->input->post(null, true),
                        $license_file,
                        $sid,
                        $this->session->userdata('logged_in')['employer_detail']['sid']
                    );
                }

                //uplaod file to AMS
                /*
                  if (isset($_FILES['license_file']) && $_FILES['license_file']['name'] != '') {
                  $file = explode(".", $_FILES["license_file"]["name"]);
                  $file_name = str_replace(" ", "-", $file[0]);
                  $pictures = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                  generate_image_compressed($_FILES['license_file']['tmp_name'], 'images/' . $pictures);
                  $aws = new AwsSdk();
                  $aws->putToBucket($pictures, 'images/' . $pictures, AWS_S3_BUCKET_NAME);
                  $formpost['license_file'] = $pictures;
                  } else {
                  $formpost['license_file'] = $license_info_data['license_file'];
                  }
                 */

                if (isset($formpost['perform_action'])) {
                    unset($formpost['perform_action']);
                }

                //
                if (preg_match(XSYM_PREG, $formpost['license_number'])) $formpost['license_number'] = $license_info_data['license_number'];
                if (preg_match(XSYM_PREG, $formpost['license_issue_date'])) $formpost['license_issue_date'] = $license_info_data['license_issue_date'];
                if (preg_match(XSYM_PREG, $formpost['license_expiration_date'])) $formpost['license_expiration_date'] = $license_info_data['license_expiration_date'];
                if (preg_match(XSYM_PREG, $formpost['dob'])) $formpost['dob'] = $license_info_data['dob'];

                $licenseData['license_details'] = serialize($formpost);
                $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;

                if (preg_match(XSYM_PREG, $this->input->post('dob'))) $dateOfBirth['dob'] = $license_info_data['dob'];
                if ($licenseCheck->num_rows() > 0) {
                    $license_data = $licenseCheck->result_array();
                    $license_id = $license_data[0]['sid'];
                    $this->dashboard_model->update_license_info($license_id, $licenseData, $dateOfBirth, $employer_id);
                } else {
                    $this->dashboard_model->save_license_info($licenseData, $dateOfBirth, $employer_id);
                }
                $full_emp_form['TextBoxDriversLicenseNumber'] = $this->input->post('license_number');;
                $full_emp_form['TextBoxDriversLicenseExpiration'] = $this->input->post('license_expiration_date');

                if (preg_match(XSYM_PREG, $full_emp_form['TextBoxDriversLicenseNumber'])) unset($full_emp_form['TextBoxDriversLicenseNumber']);
                if (preg_match(XSYM_PREG, $full_emp_form['TextBoxDriversLicenseExpiration'])) unset($full_emp_form['TextBoxDriversLicenseExpiration']);

                $serial_form = array();
                $serial_form['full_employment_application'] = serialize($full_emp_form);
                if ($type == 'employee') {
                    $this->dashboard_model->update_users_data($employer_id, $serial_form);
                } elseif ($type == 'applicant') {
                    $this->dashboard_model->update_applicant_data($employer_id, $serial_form);
                }

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $sid;
                $cpArray['user_type'] = $type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'drivers_license';
                //
                checkAndInsertCompletedDocument($cpArray);

                //
                checkAndUpdateDD($sid, $type, $company_id, 'drivers_license');

                $this->load->model('direct_deposit_model');
                $this->load->model('hr_documents_management_model');
                $userData = $this->direct_deposit_model->getUserData($sid, $type);
                //
                $doSend = false;
                //
                if (array_key_exists('document_sent_on', $userData)) {
                    //
                    $doSend = false;
                    //
                    if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                        $doSend = true;
                        //
                        $this->hr_documents_management_model->update_employee($sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                    }
                }

                // Only send if dosend is true
                if ($doSend == true) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'driver_license',
                        $company_id,
                        $data_function['session']['company_detail']['CompanyName'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $sid
                    );
                }

                $this->session->set_flashdata('message', '<b>Success:</b> License details saved successfully');
                redirect($reload_location, 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function occupational_license_info($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data_function['session'] = $this->session->userdata('logged_in');
            $security_sid = $data_function['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data_function['security_details'] = $security_details;
            $company_id = $data_function['session']['company_detail']['sid'];
            $employer_access_level = $data_function['session']['employer_detail']['access_level'];

            $user_sid = $sid;
            $user_type = $type;

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data_function['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data_function['title'] = 'Occupational License Information';
                $reload_location = 'occupational_license_info';
                $type = 'employee';
                $data_function["return_title_heading"] = "My Profile";
                $data_function["return_title_heading_link"] = base_url() . 'My Profile';
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($employer_id, 'employee');
                $data_function["cancel_url"] = 'my_profile/';
                $load_view = check_blue_panel_status(false, 'self');
            } elseif ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_occupational_license_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = employee_right_nav($sid);
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Members Occupational License Infomation';
                $reload_location = 'occupational_license_info/employee/' . $sid;
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $data_function["cancel_url"] = 'employee_profile/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($sid, 'employee');
                $load_view = check_blue_panel_status(false, $type);
            } elseif ($type == 'applicant') {
                check_access_permissions($security_details, 'application_tracking', 'applicant_occupational_license_info');  // Param2: Redirect URL, Param3: Function Name
                $data_function = applicant_right_nav($sid);
                $data_function['security_details'] = $security_details;
                $employer_id = $sid;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Occupational License Information';
                $reload_location = 'occupational_license_info/applicant/' . $sid . '/' . $jobs_listing;
                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data_function["cancel_url"] = 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $load_view = check_blue_panel_status(false, $type);
            }

            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> Occupational License Info not found!');
                redirect('dashboard', 'refresh');
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;

            if ($type == 'employee') {
                $data_function["employer"] = $this->dashboard_model->get_company_detail($employer_id);
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                //                $data_function['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data_function['company_background_check'] = checkCompanyAccurateCheck($company_id);

                //Outsourced HR Compliance and Onboarding check
                $data_function['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($company_id);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'dob' => $applicant_info['dob'],
                    'user_type' => 'Applicant'
                );

                $data_function['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data_function['employer'] = $data_employer;
            }

            $url = '';
            switch ($type) {
                case 'employee':
                    $parent_sid = getEmployeeUserParent_sid($employer_id);
                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Exists In Db But Not In Same Company
                            $url = base_url('employee_management');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Employee Not Found!'); // Employee Does Not Exist In Db.
                        $url = base_url('employee_management');
                        redirect($url, 'refresh');
                    }
                    break;
                case 'applicant':
                    $parent_sid = getApplicantsEmployer_sid($employer_id);
                    if ($parent_sid > 0) {
                        if ($company_id != $parent_sid) {
                            $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Exist In Db But Not in Same Company.
                            $url = base_url('application_tracking_system/active/all/all/all/all');
                            redirect($url, 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> Applicant Not Found!'); // Applicant Does Not Exist In Db.
                        $url = base_url('application_tracking_system/active/all/all/all/all');
                        redirect($url, 'refresh');
                    }
                    break;
            }

            $license_type = "occupational";
            $license_info_data = $this->dashboard_model->get_license_info($employer_id, $type, $license_type);

            if (!empty($license_info_data[0]['license_details'])) {
                $license_info_data = array_merge($license_info_data[0], unserialize($license_info_data[0]['license_details']));
            }

            $data_function['license_info'] = $license_info_data;
            $data_function['license_type'] = $license_type;
            $license_types = array();
            $license_types['Sales License'] = 'Sales License';
            $license_types['Commercial Driver\'s License'] = 'Commercial Driver\'s License';
            $license_types['Non-commercial Driver\'s License'] = 'Non-commercial Driver\'s License';
            $license_types['Restricted Driver\'s License'] = 'Restricted Driver\'s License';
            $license_types['Basic Driver\'s License'] = 'Basic Driver\'s License';
            $license_types['Identification Card'] = 'Identification Card';
            $license_types['College Diploma'] = 'College Diploma';
            $license_types['Training'] = 'Training';
            $license_types['Other'] = 'Other';
            $data_function['license_types'] = $license_types;
            $license_classes = array();
            $license_classes['Class A'] = 'Class A';
            $license_classes['Class B'] = 'Class B';
            $license_classes['Class C'] = 'Class C';
            $data_function['license_classes'] = $license_classes;
            $data_function['questions_sent'] = $this->application_tracking_model->check_sent_video_questionnaires($sid, $company_id);
            $data_function['questions_answered'] = $this->application_tracking_model->check_answered_video_questionnaires($sid, $company_id);
            $data_function['employee'] = $data_function['session']['employer_detail'];
            $data_function['job_list_sid'] = $jobs_listing;
            $data_function['load_view'] = $load_view;
            //form data valdation starts
            $this->form_validation->set_rules('license_type', 'License type', 'trim');
            //form data valdation ends
            if ($this->form_validation->run() === FALSE) {
                $data_function['company_sid'] = $company_id;
                $data_function['user_sid'] = $user_sid = $sid;
                $data_function['user_type'] = $user_type = $type;
                //                if ($load_view == 'old') {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/license_info_occupational');
                $this->load->view('main/footer');
                //                } else {
                //                    $this->load->view('onboarding/on_boarding_header', $data_function);
                //                    $this->load->view('onboarding/license_info');
                //                    $this->load->view('onboarding/on_boarding_footer');
                //                }
            } else {
                $formpost = $this->input->post(null, true);
                $licenseData['users_sid'] = $employer_id;
                $licenseData['users_type'] = $type;
                $licenseData['license_type'] = $license_type;
                $licenseCheck = $this->dashboard_model->check_user_license($employer_id, $type, $license_type);
                //$formpost['license_file'] = "";
                $license_file = upload_file_to_aws('license_file', $company_id, 'license_file', $employer_id);

                if ($type == 'employee') {
                    //
                    $this->load->model('2022/User_model', 'em');
                    //
                    $this->em->handleGeneralDocumentChange(
                        'occupationalLicense',
                        $this->input->post(null, true),
                        $license_file,
                        $sid,
                        $this->session->userdata('logged_in')['employer_detail']['sid']
                    );
                }

                if (!empty($license_file) && $license_file != 'error') {
                    $licenseData['license_file'] = $license_file;
                }

                if (isset($formpost['perform_action'])) {
                    unset($formpost['perform_action']);
                }

                $licenseData['license_details'] = serialize($formpost);
                $dateOfBirth['dob'] = (!empty($this->input->post('dob'))) ? date("Y-m-d", strtotime($this->input->post('dob'))) : null;
                if ($licenseCheck->num_rows() > 0) {
                    $license_data = $licenseCheck->result_array();
                    $license_id = $license_data[0]['sid'];
                    $this->dashboard_model->update_license_info($license_id, $licenseData, $dateOfBirth, $employer_id);
                } else {
                    $this->dashboard_model->save_license_info($licenseData, $dateOfBirth, $employer_id);
                }

                $this->load->model('direct_deposit_model');
                $this->load->model('hr_documents_management_model');
                $userData = $this->direct_deposit_model->getUserData($user_sid, $user_type);

                //
                $cpArray = [];
                $cpArray['company_sid'] = $company_id;
                $cpArray['user_sid'] = $user_sid;
                $cpArray['user_type'] = $user_type;
                $cpArray['document_sid'] = 0;
                $cpArray['document_type'] = 'occupational_license';
                //
                checkAndInsertCompletedDocument($cpArray);
                //
                checkAndUpdateDD($user_sid, $user_type, $company_id, 'occupational_license');
                //
                $doSend = false;
                //
                if (array_key_exists('document_sent_on', $userData)) {
                    //
                    $doSend = false;
                    //
                    if (empty($userData['document_sent_on']) || $userData['document_sent_on'] > date('Y-m-d 23:59:59', strtotime('now'))) {
                        $doSend = true;
                        //
                        $this->hr_documents_management_model->update_employee($user_sid, array('document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))));
                    }
                }

                // Only send if dosend is true
                if ($doSend == true) {
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_TEMPLATE,
                        'general_information_status',
                        'occupational_license',
                        $company_id,
                        $data_function['session']['company_detail']['CompanyName'],
                        $userData['first_name'],
                        $userData['last_name'],
                        $user_sid
                    );
                }

                $this->session->set_flashdata('message', '<b>Success:</b> License details saved successfully');
                redirect(base_url($reload_location), 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function kpa_onboarding()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'kpa_onboarding');  // Param2: Redirect URL, Param3: Function Name

            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];

            $data['title'] = "Outsourced HR Compliance and Onboarding";
            $data['kpaData'] = $this->dashboard_model->get_kpa_onboarding($company_id);

            // Add Admin Templates to client end *** Start ***
            $this->load->model('portal_email_templates_model');

            $hr_documents_notification = $this->portal_email_templates_model->check_admin_template_exists(ON_BOARDING_REQUEST, $company_id);

            if (empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
                $admin_template_data = $this->portal_email_templates_model->get_admin_template_by_id(ON_BOARDING_REQUEST);

                if (!empty($admin_template_data)) {
                    $template_code = str_replace(' ', '_', strtolower($admin_template_data['name']));
                    $template_code = str_replace('-', '_', $template_code);

                    $data_to_insert = array();
                    $data_to_insert['template_code'] = $template_code;
                    $data_to_insert['company_sid'] = $company_id;
                    $data_to_insert['created'] = date('Y-m-d H:i:s');
                    $data_to_insert['template_name'] = $admin_template_data['name'];
                    $data_to_insert['from_name'] = $data["session"]["company_detail"]["CompanyName"];
                    $data_to_insert['from_email'] = $admin_template_data['from_email'];
                    $data_to_insert['subject'] = $admin_template_data['subject'];
                    $data_to_insert['message_body'] = $admin_template_data['text'];
                    $data_to_insert['admin_template_sid'] = $admin_template_data['sid'];
                    $data_to_insert['enable_auto_responder'] = 0;
                    $this->db->insert("portal_email_templates", $data_to_insert);
                }
            }
            // Add Admin Templates to Client end ***  End  ***


            $onboarding_email_template = $this->portal_email_templates_model->get_portal_email_template_by_code('on_boarding_request', $company_id);
            $data['onboarding_email_template'] = $onboarding_email_template;
            $data['company_sid'] = $company_id;
            $data['template_code'] = 'on_boarding_request';

            $this->form_validation->set_rules('kpa_url', 'Outsourced HR Compliance and Onboarding Url', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/kpa_onboarding');
                $this->load->view('main/footer');
            } else {

                $kpa_url = $this->input->post('kpa_url');
                $status = $this->input->post('status');
                $subject = $this->input->post('subject');
                $message_body = $this->input->post('message_body');
                $company_sid = $this->input->post('company_sid');
                $template_sid = $this->input->post('template_sid');

                //Update Kpa record
                $kpa_data_to_update = array();
                $kpa_data_to_update['kpa_url'] = $kpa_url;
                $kpa_data_to_update['status'] = is_null($status) || empty($status) ? 0 : $status;
                $kpa_data_to_update['date'] = date('Y-m-d H:i:s');
                $kpa_data_to_update['company_sid'] = $company_sid;
                $this->dashboard_model->save_kpa_onboarding($kpa_data_to_update);

                //Update Template
                $template_data_to_update = array();
                $template_data_to_update['subject'] = $subject;
                $template_data_to_update['message_body'] = $message_body;
                $this->portal_email_templates_model->update_email_template($template_data_to_update, $template_sid);

                redirect("my_settings", "location");
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function if_user_exists_ci_validation($str)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data['session']['company_detail']['sid'];
        $this->db->where('email', $str);
        $this->db->where('parent_sid', $company_id);
        $userInfo = $this->db->get('users')->result_array();
        $return = FALSE;

        if (empty($userInfo)) {
            $return = TRUE;
        }

        $this->form_validation->set_message('if_user_exists_ci_validation', 'Provided email address is already in use!');
        return $return;
    }

    public function list_packages_addons_invoices()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'list_packages_addons_invoices');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $admin_invoices = $this->settings_model->Get_all_admin_invoices(1, 10000, $company_sid);
            $data['invoices'] = $admin_invoices;
            $data['title'] = 'Platform Packages and Admin Invoices';
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/list_admin_invoices');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function view_packages_addons_invoice($invoice_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'list_packages_addons_invoices'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['company_detail']['sid'];

            if ($invoice_sid > 0) {
                $invoice_data = $this->settings_model->Get_admin_invoice($invoice_sid);
                $invoice_html = '';

                if (!empty($invoice_data)) {
                    if ($invoice_data['company_sid'] == $company_sid) {
                        $invoice_html = generate_invoice_html($invoice_sid);
                    } else {
                        $this->session->set_flashdata('message', '<b>Error:</b> No Such Invoice Exists!');
                        redirect('settings/list_packages_addons_invoices', 'refresh');
                    }
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> No Invoice Found!');
                    redirect('settings/list_packages_addons_invoices', 'refresh');
                }

                $data['invoice'] = $invoice_html;
                $data['title'] = 'View Invoice # ' . $invoice_data['invoice_number'];
                $data['invoice_sid'] = $invoice_data['sid'];
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/view_admin_invoice');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong>No Invoice Found!');
                redirect('settings/list_packages_addons_invoices', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function print_packages_addons_invoice($invoice_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'list_packages_addons_invoices'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['company_detail']['sid'];
            $invoice_data = $this->settings_model->Get_admin_invoice($invoice_sid);
            $invoice_html = '';

            if (!empty($invoice_data)) {
                if ($invoice_data['company_sid'] == $company_sid) {
                    $invoice_html = generate_invoice_html($invoice_sid);
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> No Such Invoice Exists!');
                    redirect('settings/list_packages_addons_invoices', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No Such Invoice Exists!');
                redirect('settings/list_packages_addons_invoices', 'refresh');
            }

            $data['title'] = 'View Invoice # ' . $invoice_data['invoice_number'];
            $data['invoice'] = $invoice_html;
            $this->load->view('manage_employer/print_admin_invoice', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function talent_network_content_configuration()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'talent_network_content_configuration');
            $company_sid = $data['session']['company_detail']['sid'];
            $data['talent_data'] = $this->dashboard_model->get_talent_configuration($company_sid);
            $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
            $this->form_validation->set_rules('content', 'Content', 'required|trim|xss_clean');
            $this->form_validation->set_rules('picture_or_video', 'Show image or video', 'trim|xss_clean|required');

            if ($this->input->post('picture_or_video') == 'video') {
                $this->form_validation->set_rules('youtube_link', 'YouTube Link', 'trim|xss_clean|callback_validate_youtube|required');
            } else {
                $this->form_validation->set_rules('youtube_link', 'YouTube Link', 'trim|xss_clean|callback_validate_youtube');
            }

            if ($this->form_validation->run() === FALSE) {
                $this->load->model('job_fair_model');
                $data['employees'] = $this->job_fair_model->getAllEmployees($company_sid);
                $data['title'] = 'Talent Network Configuration';
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/talent_network_content_configuration');
                $this->load->view('main/footer');
            } else {
                $title = $this->input->post('title');
                $content = $this->input->post('content');
                $youtube_link = $this->input->post('youtube_link');
                $picture_or_video = $this->input->post('picture_or_video');

                if (isset($_FILES['pictures']) && $_FILES['pictures']['name'] != '') {
                    $result = put_file_on_aws('pictures');
                }

                if (isset($youtube_link) && $youtube_link != '' && $youtube_link != NULL) {
                    $youtube_link = substr($youtube_link, strpos($youtube_link, '=') + 1, strlen($youtube_link));
                }

                $insert_array = array();
                $insert_array['company_sid'] = $company_sid;
                $insert_array['title'] = $title;
                $insert_array['content'] = $content;
                $insert_array['youtube_link'] = $youtube_link;
                $insert_array['visibility_employees'] = !empty($this->input->post('visibility', TRUE)) ? implode(',', $this->input->post('visibility', TRUE)) : NULL;
                $insert_array['picture_or_video'] = $picture_or_video;

                if (isset($result) && $result != 'error' && $result != '') {
                    $insert_array['picture'] = $result;
                }

                $result = $this->dashboard_model->save_talent_configuration($insert_array);
                $this->session->set_flashdata('message', '<b>Success:</b> Talent Content Configuration is updated successfully');
                redirect('settings/talent_network_content_configuration');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function process_manual_payment()
    {
        $this->load->helper('payment');
        $payment = process_payment_using_credit_card('CARD-2DC802384M690860AK7NYZRA', 'USD', 1, 'Test Manual Payment');
        echo "<pre>";
        print_r($payment);
        echo "<pre>";
    }

    public function manage_career_page_maintenance_mode()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'manage_career_page_maintenance_mode');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $this->form_validation->set_rules('page_title', 'Title', 'required|trim');
            $this->form_validation->set_rules('page_content', 'Content', 'required|trim');
            $this->form_validation->set_rules('company_sid', 'Company', 'required|trim');
            $this->form_validation->set_rules('portal_sid', 'Portal', 'required|trim');
            $this->load->model('manage_admin/maintenance_mode_model');

            if ($this->form_validation->run() == false) {
            } else {
                //Get Data from post
                $page_title = $this->input->post('page_title');
                $page_content = $this->input->post('page_content');
                $maintenance_mode_status = $this->input->post('maintenance_mode_status');
                $company_sid = $this->input->post('company_sid');
                $portal_sid = $this->input->post('portal_sid');
                //Update Maintenance Mode Status
                $this->maintenance_mode_model->update_maintenance_mode_status($company_sid, $portal_sid, $maintenance_mode_status);
                //Update Maintenance Mode Page Content
                $page_content = htmlentities($page_content);
                $data_to_update = array();
                $data_to_update['page_title'] = $page_title;
                $data_to_update['page_content'] = $page_content;
                $this->maintenance_mode_model->update_maintenance_mode_page_content($company_sid, $portal_sid, $data_to_update);
                $this->session->set_flashdata('message', '<strong>Success:</strong> Maintenance Mode Successfully updated.');
                redirect('settings/manage_career_page_maintenance_mode', 'location');
            }

            $portal_info = $this->maintenance_mode_model->get_employer_portal_record($company_sid);
            $maintenance_mode_detail = array();
            $employer_portal_sid = $portal_info['sid'];
            //Get Maintenance Mode Record
            $maintenance_mode_detail = $this->maintenance_mode_model->get_maintenance_mode_record($company_sid, $employer_portal_sid);

            if (empty($maintenance_mode_detail)) {
                //If Not found create new record
                $this->maintenance_mode_model->insert_default_maintenance_mode_record($company_sid, $employer_portal_sid);
            }

            //Re Get Maintenance Mode Record
            $maintenance_mode_detail = $this->maintenance_mode_model->get_maintenance_mode_record($company_sid, $employer_portal_sid);

            if ($portal_info['maintenance_mode'] == 1) {
                $maintenance_mode_enabled_default = true;
                $maintenance_mode_disabled_default = false;
            } else {
                $maintenance_mode_enabled_default = false;
                $maintenance_mode_disabled_default = true;
            }

            $data['maintenance_mode_enabled_default'] = $maintenance_mode_enabled_default;
            $data['maintenance_mode_disabled_default'] = $maintenance_mode_disabled_default;
            $data['company_sid'] = $company_sid;
            $data['portal_sid'] = $employer_portal_sid;
            $data['maintenance_mode_detail'] = $maintenance_mode_detail;
            $data['title'] = 'Manage Career Page Maintenance Mode';
            $this->load->view('main/header', $data);
            $this->load->view('manage_career_page_maintenance_mode/manage');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function pending_invoices()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'pending_invoices');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Overdue Invoices';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            if ($this->form_validation->run() == false) {
                $invoices = $this->settings_model->get_unpaid_admin_invoices($company_sid);
                $grand_total = 0;

                foreach ($invoices as $invoice) {
                    if ($invoice['exclusion_status'] == 0) {
                        /*
                          if ($invoice['is_discounted'] == 1) {
                          $grand_total += $invoice['total_after_discount'];
                          } else {
                          $grand_total += $invoice['value'];
                          }
                         */

                        $grand_total += $invoice['total_after_discount'];
                    }
                }

                $data['grand_total'] = $grand_total;
                $data['invoices'] = $invoices;
                $this->load->model('ext_model');
                $credit_cards = $this->ext_model->get_all_company_cards($company_sid, 1);
                $data['user_cc'] = $credit_cards;
                $this->load->view('main/header', $data);
                $this->load->view('settings/pending_invoices');
                $this->load->view('main/footer');
            } else {
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function preview_job_listing_template($template_sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'preview_job_listing');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Preview Job Listing Template';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            //Get Templates
            $job_listing_template_group = $data['session']['company_detail']['job_listing_template_group']; // templates code start

            if (empty($job_listing_template_group)) {
                $job_listing_template_group = 1;
            }

            $template_sids = $this->settings_model->get_job_listing_template_group_template_ids($job_listing_template_group);
            $templates = array();

            if (!empty($template_sids)) {
                $templates = $this->settings_model->get_job_listing_templates($template_sids);
            }

            $data['templates'] = $templates;

            if ($template_sid != null && $template_sid > 0) {
                $template = $this->settings_model->get_job_listing_template($template_sid);
                $data['my_template'] = $template;
            } else {
                $data['my_template'] = array();
            }

            $this->load->view('main/header', $data);
            $this->load->view('settings/preview_job_listing_template');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
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

    public function export_time_off($app_id = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $page_number = 1)
    {
        if ($this->session->userdata('logged_in')) {
            $this->load->model('export_timeoff_csv_model');
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'export_applicant_csv'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');


            $data['title'] = 'Export Employee Timeoff CSV';
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;

            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            $allEmp = $this->export_timeoff_csv_model->get_all_employees($company_sid);
            $data['company_sid'] = $company_sid;
            $data['allEmp'] = $allEmp;
            //            echo '<pre>';
            //            print_r($allEmp);
            //            die();

            //            $applicant_types = array();
            //            $applicant_types[] = 'Applicant';
            //            $applicant_types[] = 'Talent Network';
            //            $applicant_types[] = 'Manual Candidate';
            //            $applicant_types[] = 'Job Fair';
            //            $applicant_types[] = 'Re-Assigned Candidates';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'export_timeoff':
                    $app_sid = $this->input->post('app_sid');
                    $archive = $this->input->post('archive');
                    $status = $this->input->post('status');
                    $start_date = $this->input->post('start_date_applied');
                    $end_date = $this->input->post('end_date_applied');
                    $company_sid = $this->input->post('company_sid');

                    if (!empty($start_date) && $start_date != 'all') {
                        $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
                    } else {
                        $start_date_applied = date('Y-m-d');
                    }

                    if (!empty($end_date) && $end_date != 'all') {
                        $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
                    } else {
                        $end_date_applied = date('Y-m-d');
                    }
                    $applicants = $this->export_timeoff_csv_model->get_csv_applicants($company_sid, $app_sid, $archive, $status, $start_date_applied, $end_date_applied);
                    //                    echo '<pre>';
                    //                    print_r($applicants);
                    //                    die();
                    $export_data = array();
                    $i = 0;
                    $rows = '';

                    //
                    $filename = 'timeoff_export_' . date('Y_m_d_H_i_s') . '.csv';
                    $f = fopen($filename, 'w');
                    fputcsv($f, ['Request Id', 'Employee Number', 'Employee Name', 'Policy Type', 'Policy', 'Start Date', 'End Date', 'Total Days', 'Days Breakdown', 'Reason', 'Approver Comment', 'Level', 'Status']);

                    foreach ($applicants as $key => $applicant) {
                        $r = $applicant['full_name'];
                        //
                        if (!empty($applicant['job_title']) && $applicant['job_title'] != null) $r .= ' (' . ($applicant['job_title']) . ')';
                        //
                        $r .= ' [';
                        //
                        if ($applicant['is_executive_admin'] !== null && $applicant['is_executive_admin'] != 0) $r .= 'Executive ';
                        //
                        if ($applicant['access_level_plus'] == 1 && $applicant['pay_plan_flag'] == 1)  $r .= $applicant['access_level'] . ' Plus / Payroll';
                        else if ($applicant['access_level_plus'] == 1) $r .= $applicant['access_level'] . ' Plus';
                        else if ($applicant['pay_plan_flag'] == 1) $r .= $applicant['access_level'] . ' Payroll';
                        else $r .= $applicant['access_level'];
                        $r .= ']';
                        $export_data[$i]['employee_number'] =  $applicant['employee_number'] == '' ? '-' : $applicant['employee_number'];
                        $export_data[$i]['name'] =  $r;
                        $export_data[$i]['policy'] =  $applicant['policy'];
                        $export_data[$i]['type'] =  $applicant['type'];
                        $export_data[$i]['dates'] =  DateTime::createFromFormat('Y-m-d', $applicant['from'])->format('m-d-Y') . ' to ' . DateTime::createFromFormat('Y-m-d', $applicant['to'])->format('m-d-Y');
                        $export_data[$i]['comment'] =  strip_tags($applicant['comment']);
                        $export_data[$i]['reason'] =  strip_tags($applicant['reason']);
                        $export_data[$i]['level'] =  ($applicant['level_at'] == 1 ? 'Team Lead' : ($applicant['level_at'] == 2 ? 'Supervisor' : 'Approver'));
                        $export_data[$i]['status'] =  ucfirst($applicant['status']);
                        $breakDown = '';
                        $timeoff_days = json_decode($applicant['timeoff_days']);
                        $export_data[$i]['days'] =  sizeof($timeoff_days);
                        $export_data[$i]['days'] = $export_data[$i]['days'] == 0 ? 1 : $export_data[$i]['days'];
                        foreach ($timeoff_days as $days) {
                            $h = floor($days->time / 60);
                            $m = floor($days->time % 60);
                            $breakDown .= 'Date: ' . $days->date . ' Type: ' . (isset($days->partial) ? ucwords($days->partial) : '') . ' Duration: ' . $h . ' Hours ' . $m . ' Minutes';
                            $breakDown .= "\n";
                        }
                        //$breakDown = rtrim($breakDown,'/ ');
                        $export_data[$i]['break'] = $breakDown;
                        //
                        fputcsv($f, [
                            $applicant['sid'],
                            $export_data[$i]['employee_number'],
                            $export_data[$i]['name'],
                            $export_data[$i]['type'],
                            $export_data[$i]['policy'],
                            DateTime::createFromFormat('Y-m-d', $applicant['from'])->format('m-d-Y'),
                            DateTime::createFromFormat('Y-m-d', $applicant['to'])->format('m-d-Y'),
                            $export_data[$i]['days'],
                            $export_data[$i]['break'],
                            $export_data[$i]['reason'],
                            $export_data[$i]['comment'],
                            $export_data[$i]['level'],
                            $export_data[$i]['status']
                        ]);

                        $rows .= $export_data[$i]['employee_number'] . ',' . $export_data[$i]['name'] . ',' . $export_data[$i]['policy'] . ',' . $export_data[$i]['type'] . ',' . $export_data[$i]['dates'] . ',' . ($export_data[$i]['days'] + 1) . ',' . $export_data[$i]['break'] . ',' . $export_data[$i]['comment'] . ',' . $export_data[$i]['reason'] . ',' . $export_data[$i]['level'] . ',' . $export_data[$i]['status'] . PHP_EOL;
                        $i++;
                    }

                    fclose($f);

                    $file_content = file_get_contents($filename);
                    $file_size = strlen($file_content);
                    //
                    unlink($filename);

                    header('Pragma: public');     // required
                    header('Expires: 0');         // no cache
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Cache-Control: private', false);
                    header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                    header('Content-Disposition: attachment; filename="' . ($filename) . '"');  // Add the file name
                    header('Content-Transfer-Encoding: binary');
                    header('Content-Length: ' . $file_size); // provide file size
                    header('Connection: close');
                    echo $file_content;
                    exit;
            }


            $this->load->view('main/header', $data);
            $this->load->view('export_timeoff_csv/index');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    /**
     * list company page schedules
     *
     * @param string $status Optional
     */
    public function schedules(string $status = "active")
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Company Pay Schedules | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        // get the schedules
        $data["schedules"] = $this->schedule_model
            ->getCompanySchedules(
                $loggedInCompany["sid"],
                $status === "active" ? 1 : 0
            );
        $data["status"] = $status;
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/index');
        $this->load->view('main/footer');
    }

    /**
     * add a pay schedule
     */
    public function addSchedule()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Add Company Pay Schedule | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("additionalMethods", "js"),
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/schedules/add"
        ], "public/v1/schedules/add/", "add_schedule", true);
        // load views
        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/add');
        $this->load->view('main/footer');
    }

    /**
     * edit a pay schedule
     *
     * @param int $scheduleId
     */
    public function editSchedule(int $scheduleId)
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        // get the schedule
        $data["schedule"] = $this->schedule_model
            ->getScheduleById(
                $scheduleId
            );
        if (!$data["schedule"]) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Schedule not found.");
            return redirect("schedules");
        }
        $data["title"] = "Edit Company Pay Schedule | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("additionalMethods", "js"),
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/schedules/edit"
        ], "public/v1/schedules/edit/", "edit_schedule", true);
        // load views
        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/edit');
        $this->load->view('main/footer');
    }

    /**
     * get schedule deadline date
     *
     * @param string $firstPayDate
     */
    public function getScheduleDeadlineDate(string $firstPayDate)
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Access denied!"
                    ]
                ]
            );
        }
        // get the logged in company
        $loggedInCompany = checkAndGetSession("company_detail");
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        // get the company holidays
        $holidaysDates = $this->schedule_model->getCompanyHolidays($loggedInCompany["sid"]);
        //
        if ($firstPayDate < getSystemDate("Y-m-d")) {
            // use next pay date
        }
        // get the deadline date
        $deadlineDate = getPastDate(
            $firstPayDate,
            $holidaysDates,
            2
        );
        // send back the response
        return SendResponse(
            200,
            [
                "deadlineDate" => $deadlineDate
            ]
        );
    }

    /**
     * process schedule
     */
    public function processSchedule()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Access denied!"
                    ]
                ]
            );
        }
        // get the sanitized post
        $post = $this->input->post(null, true);
        // add validation rules
        $this->form_validation->set_rules("name", "Name", "required|xss_clean|trim");
        $this->form_validation->set_rules("pay_frequency", "Pay frequency", "required|xss_clean|trim");
        $this->form_validation->set_rules("first_pay_date", "First pay date", "required|xss_clean|trim|callback_validDate");
        // for dealine
        if ($post["pay_frequency"] === "Every week" || $post["pay_frequency"] === "Every other week") {
            $this->form_validation->set_rules("deadline_to_run_payroll", "Deadline date", "required|xss_clean|trim|callback_validDate");
        }
        // for days 1 and 2
        if ($post["pay_frequency"] === "Twice a month: Custom") {
            $this->form_validation->set_rules("day_1", "Day 1", "required|xss_clean|trim");
            $this->form_validation->set_rules("day_2", "Day 2", "required|xss_clean|trim");
        } else {
            $post["day_1"] = null;
            $post["day_2"] = null;
        }
        // for pay day
        if ($post["pay_frequency"] === "Monthly") {
            $this->form_validation->set_rules("pay_day", "Pay day", "required|xss_clean|trim");
        } else {
            $post["pay_day"] = null;
        }

        $this->form_validation->set_rules("first_pay_period_end_date", "First pay period end date", "required|xss_clean|trim|callback_validDate");
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get the logged in company
        $loggedInCompany = checkAndGetSession("company_detail");
        //
        // prepare array
        $ins = [];
        $ins["custom_name"] = $post["name"];
        $ins['frequency'] = $post["pay_frequency"];
        $ins['anchor_pay_date'] = formatDateToDB($post["first_pay_date"], SITE_DATE);
        $ins['anchor_end_of_pay_period'] = formatDateToDB($post["first_pay_period_end_date"], SITE_DATE);
        //
        if ($post["pay_frequency"] === "Twice a month: Custom") {
            $ins['frequency'] = 'Twice per month';
            $ins['day_1'] = $post["day_1"];
            $ins['day_2'] = $post["day_2"];
        } else if ($post["pay_frequency"] === "Monthly") {
            $ins['day_1'] = $post["pay_day"];
        } else {
            $ins['day_1'] = null;
            $ins['day_2'] = null;
        }
        // get the company details
        $companyGustoDetails =  getCompanyDetailsForGusto($loggedInCompany["sid"]);
        //
        if ($companyGustoDetails) {
            //
            $this->load->helper('v1/payroll' . ($this->db->where([
                "company_sid" => $loggedInCompany["sid"],
                "stage" => "production"
            ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
            //
            $gustoResponse = gustoCall(
                'getPaySchedules',
                $companyGustoDetails,
                $ins,
                "POST"
            );
            //
            $errors = hasGustoErrors($gustoResponse);
            //
            if ($errors) {
                return SendResponse(400, ["msg" => $errors['errors'][0]]);
            }
            //
            $ins["gusto_uuid"] = $gustoResponse["uuid"];
            $ins["gusto_version"] = $gustoResponse["version"];
        }

        $ins["company_sid"] = $loggedInCompany["sid"];
        $ins["deadline_to_run_payroll"] = formatDateToDB($post["deadline_to_run_payroll"], SITE_DATE);
        $ins["active"] = 1;
        $ins["updated_at"] = $ins["created_at"] = getSystemDate();
        //
        $this->db->insert(
            "companies_pay_schedules",
            $ins
        );
        //
        return SendResponse(200, [
            "msg" => "You have successfully added a new schedule."
        ]);
    }


    /**
     * process edit schedule
     */
    public function processEditSchedule(int $scheduleId)
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Access denied!"
                    ]
                ]
            );
        }
        // get the sanitized post
        $post = $this->input->post(null, true);
        // add validation rules
        $this->form_validation->set_rules("name", "Name", "required|xss_clean|trim");
        $this->form_validation->set_rules("pay_frequency", "Pay frequency", "required|xss_clean|trim");
        $this->form_validation->set_rules("first_pay_date", "First pay date", "required|xss_clean|trim|callback_validDate");
        // for dealine
        if ($post["pay_frequency"] === "Every week" || $post["pay_frequency"] === "Every other week") {
            $post["day_1"] = null;
            $post["day_2"] = null;
            $this->form_validation->set_rules("deadline_to_run_payroll", "Deadline date", "required|xss_clean|trim|callback_validDate");
        }
        // for days 1 and 2
        if ($post["pay_frequency"] === "Twice a month: Custom") {
            $this->form_validation->set_rules("day_1", "Day 1", "required|xss_clean|trim");
            $this->form_validation->set_rules("day_2", "Day 2", "required|xss_clean|trim");
        } else {
            $post["day_1"] = null;
            $post["day_2"] = null;
        }
        // for pay day
        if ($post["pay_frequency"] === "Monthly") {
            $this->form_validation->set_rules("pay_day", "Pay day", "required|xss_clean|trim");
        } else {
            $post["pay_day"] = null;
        }
        //
        $this->form_validation->set_rules("first_pay_period_end_date", "First pay period end date", "required|xss_clean|trim|callback_validDate");
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get the logged in company
        $loggedInCompany = checkAndGetSession("company_detail");
        //
        // prepare array
        $upd = [];
        $upd["custom_name"] = $post["name"];
        $upd['frequency'] = $post["pay_frequency"];
        $upd['anchor_pay_date'] = formatDateToDB($post["first_pay_date"], SITE_DATE);
        $upd['anchor_end_of_pay_period'] = formatDateToDB($post["first_pay_period_end_date"], SITE_DATE);
        //
        if ($post["pay_frequency"] === "Twice a month: Custom") {
            $upd['frequency'] = 'Twice per month';
            $upd['day_1'] = $post["day_1"];
            $upd['day_2'] = $post["day_2"];
        } else if ($post["pay_frequency"] === "Monthly") {
            $upd['day_1'] = $post["pay_day"];
        } else {
            $upd['day_1'] = null;
            $upd['day_2'] = null;
        }
        // get the company details
        $companyGustoDetails = getCompanyDetailsForGusto($loggedInCompany["sid"]);
        //
        if ($companyGustoDetails) {
            $gustoInfo =
                $this->db
                ->select('gusto_uuid, gusto_version')
                ->where('sid', $scheduleId)
                ->get('companies_pay_schedules')
                ->row_array();
            //
            $this->load->helper('v1/payroll' . ($this->db->where([
                "company_sid" => $loggedInCompany["sid"],
                "stage" => "production"
            ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
            //
            if (!$gustoInfo["gusto_uuid"]) {
                // prepare array
                $ins = [];
                $ins["custom_name"] = $post["name"];
                $ins['frequency'] = $post["pay_frequency"];
                $ins['anchor_pay_date'] = formatDateToDB($post["first_pay_date"], SITE_DATE);
                $ins['anchor_end_of_pay_period'] = formatDateToDB($post["first_pay_period_end_date"], SITE_DATE);
                //
                if ($post["pay_frequency"] === "Twice a month: Custom") {
                    $ins['frequency'] = 'Twice per month';
                    $ins['day_1'] = $post["day_1"];
                    $ins['day_2'] = $post["day_2"];
                } else if ($post["pay_frequency"] === "Monthly") {
                    $ins['day_1'] = $post["pay_day"];
                } else {
                    $ins['day_1'] = null;
                    $ins['day_2'] = null;
                }
                //
                $gustoResponse = gustoCall(
                    'getPaySchedules',
                    $companyGustoDetails,
                    $ins,
                    "POST"
                );
                //
                $errors = hasGustoErrors($gustoResponse);
                //
                if ($errors) {
                    return SendResponse(400, ["msg" => $errors['errors'][0]]);
                }
                //
                $ins["gusto_uuid"] = $gustoResponse["uuid"];
                $ins["gusto_version"] = $gustoResponse["version"];
                //
                $this->db
                    ->where("sid", $scheduleId)
                    ->update(
                        "companies_pay_schedules",
                        [
                            "gusto_uuid" => $gustoResponse["uuid"],
                            "gusto_version" => $gustoResponse["version"]
                        ]
                    );
                //
                $gustoInfo["gusto_uuid"] = $gustoResponse["uuid"];
                $gustoInfo["gusto_version"] = $gustoResponse["version"];
            }
            $upd['version'] = $gustoInfo['gusto_version'];
            $companyGustoDetails['other_uuid'] = $gustoInfo['gusto_uuid'];
            //
            $gustoResponse = gustoCall(
                'updatePaySchedules',
                $companyGustoDetails,
                $upd,
                "PUT"
            );
            //
            $errors = hasGustoErrors($gustoResponse);
            //
            if ($errors) {
                return SendResponse(400, ["msg" => $errors['errors'][0]]);
            }
            //
            unset($upd["version"]);
            $upd["gusto_version"] = $gustoResponse["version"];
        }
        //
        $upd["deadline_to_run_payroll"] = formatDateToDB($post["deadline_to_run_payroll"], SITE_DATE);
        $upd["updated_at"] = getSystemDate();
        //
        $this->db
            ->where("sid", $scheduleId)
            ->update(
                "companies_pay_schedules",
                $upd
            );
        //
        return SendResponse(200, [
            "msg" => "You have successfully updated schedule."
        ]);
    }

    /**
     * validate site date
     *
     * @param string $siteDate
     * @return bool
     */
    public function validDate(string $siteDate): bool
    {
        $this->form_validation->set_message("validDate", "The date format is invalid for %s.");
        return preg_match("/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/", $siteDate);
    }


    /**
     * list company overtime rules
     *
     * @param string $status Optional
     */
    public function overTimeRules(string $status = "active")
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Company Overtime rules | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
        // get the schedules
        $data["overtimeRules"] = $this->overtime_rules_model
            ->getOvertimeRules(
                $loggedInCompany["sid"],
                $status === "active" ? 1 : 0
            );
        $data["status"] = $status;
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/overtimerules/main"
        ], "public/v1/overtimerules/", "main", true);
        // load views
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/overtime_rules/listing');
        $this->load->view('main/footer');
    }
    /**
     * list company minimum wages
     */
    public function minimumWages()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Company Minimum Wages | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Minimum_wages_model", "minimum_wages_model");
        // get ones from Gusto
        $this->minimum_wages_model->sync(
            $loggedInCompany["sid"]
        );
        // get the wages
        $data["wages"] = $this->minimum_wages_model
            ->get($loggedInCompany["sid"]);
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/minimum_wages/main"
        ], "public/v1/minimum_wages/", "main", true);
        // load views
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/minimum_wages/listing');
        $this->load->view('main/footer');
    }

    /**
     * Manage shifts
     */
    public function manageShifts()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // get all active employees

        $employees = $this->input->get("employees");
        $toggleFilter = false;

        $employeesArray = explode(',', $employees);
        $team = $this->input->get("team", true);
        $employeeFilter['employees'] = $employeesArray;
        $employeeFilter['team'] = $team;

        $jobTitle = $this->input->get("jobtitle", true);
        $employeeFilter['jobtitle'] = explode(',', $jobTitle);


        if ($employees != '' || $team != '') {
            $toggleFilter = true;
        }
        if ($employees == '') {
            $employees = 'all';
        }


        $data["employees"] = $this->shift_model->getCompanyEmployees(
            $loggedInCompany["sid"],
            $employeeFilter
        );

        $data["allemployees"] = $this->shift_model->getCompanyEmployeesOnly(
            $loggedInCompany["sid"]
        );

        //
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";

        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates['end_date'];
        } elseif ($data["filter"]["mode"] === "two_week") {
            // get the current week dates
            $weekDates = getWeekDates(true, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates["current_week"]['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates["next_week"]['end_date'];
        } else {
            $data["filter"]["month"] = $this->input->get("month", true) ?? getSystemDate("m");
            $data["filter"]["year"] = $this->input->get("year", true) ?? getSystemDate("Y");
            //
            $data["filter"]["start_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "01/m/Y");
            //
            $data["filter"]["end_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "t/m/Y");
        }

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $employeeIds = array_column($data["employees"], "userId");
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            $employeeIds
        );
        //
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] = $employeeIds ? $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                $employeeIds,
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            ) : [];

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["filter_team"] = $team;
        $data["filter_employees"] =
            explode(",", $employees);
        $data["filter_toggle"] = $toggleFilter;

        $data["filter_jobtitle"] =
            explode(",", $jobTitle);


        // get off and holidays
        $data["holidays"] = $this->shift_model->getCompanyHolidaysWithTitle(
            $loggedInCompany["sid"],
            $data["filter"]
        );

        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
            getPlugin("select2", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("select2", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/main"
        ], "public/v1/shifts/", "main", false);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/listing');
        $this->load->view('main/footer');
    }

    /**
     * Manage shift breaks
     */
    public function manageShiftBreaks()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Shifts Breaks | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the records
        $data["records"] = $this->shift_break_model
            ->get($loggedInCompany["sid"]);
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/break"
        ], "public/v1/shifts/", "break", true);
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/breaks');
        $this->load->view('main/footer');
    }

    /**
     * Manage shift templates
     */
    public function manageShiftTemplates()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Shifts Templates | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        // get the records
        $data["records"] = $this->shift_template_model
            ->get($loggedInCompany["sid"]);
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/templates"
        ], "public/v1/shifts/", "templates", true);
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/templates');
        $this->load->view('main/footer');
    }

    /**
     * Manage job sites
     */
    public function manageJobSites()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Job Sites | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["records"] = $this->job_sites_model
            ->get($loggedInCompany["sid"]);
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            "https://maps.googleapis.com/maps/api/js?key=" . getCreds("AHR")->GoogleAPIKey . "",
            getPlugin("google_map", "js"),
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/job_sites/main"
        ], "public/v1/job_sites/", "main", true);
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/job_sites/main');
        $this->load->view('main/footer');
    }

    /**
     * get the page by slug
     *
     * @method pageOvertimeRules
     * @param string $slug
     * @param int    $pageId
     * @return array
     */
    public function getPageBySlug(string $slug, int $pageId)
    {

        //
        if ($_GET['shiftsIds']) {
            $pageId = $_GET['shiftsIds'];
        }

        // check and generate error for session
        checkAndGetSession();
        // convert the slug to function
        $func = "page" . preg_replace("/\s/i", "", ucwords(preg_replace("/[^a-z]/i", " ", $slug)));

        // get the data
        $this->$func(
            $slug,
            $pageId
        );
    }

    /**
     * get the page by slug
     *
     * @return array
     */
    public function processOvertimeRules()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("rule_name", "Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("overtime_multiplier", "Overtime rate", "trim|xss_clean|required");
        $this->form_validation->set_rules("double_overtime_multiplier", "Double time rate", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
        // call the function
        $this->overtime_rules_model
            ->processOvertimeRules(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     * delete the overtime rule
     *
     * @param int $ruleId
     * @return array
     */
    public function processDeleteOvertimeRules(int $ruleId)
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
        // call the function
        $this->overtime_rules_model
            ->processDeleteOvertimeRules(
                $session["company_detail"]["sid"],
                $ruleId
            );
    }

    /**
     * process shift break
     *
     * @return array
     */
    public function processShiftBreak()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("break_name", "Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("break_duration", "Duration", "trim|xss_clean|required");
        $this->form_validation->set_rules("break_type", "Type", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // call the function
        $this->shift_break_model
            ->process(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     *  process delete shift break
     *
     * @param int $breakId
     * @return array
     */
    public function processDeleteShiftBreak(int $breakId)
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // call the function
        $this->shift_break_model
            ->delete(
                $session["company_detail"]["sid"],
                $breakId
            );
    }

    /**
     * process  shift templates
     *
     * @return array
     */
    public function processShiftTemplate()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("start_time", "Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("end_time", "Duration", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        // call the function
        $this->shift_template_model
            ->process(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     *  process delete shift template
     *
     * @param int $breakId
     * @return array
     */
    public function processDeleteShiftTemplate(int $breakId)
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        // call the function
        $this->shift_template_model
            ->delete(
                $session["company_detail"]["sid"],
                $breakId
            );
    }

    /**
     * process job site
     *
     * @return array
     */
    public function processJobSites()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("site_name", "Name", "trim|xss_clean|required");
        $this->form_validation->set_rules("street_1", "Street 1", "trim|xss_clean|required");
        $this->form_validation->set_rules("city", "City", "trim|xss_clean|required");
        $this->form_validation->set_rules("state", "State", "trim|xss_clean|required");
        $this->form_validation->set_rules("zip_code", "Zip code", "trim|xss_clean|required|numeric|exact_length[5]");
        $this->form_validation->set_rules("site_radius", "Radius", "trim|xss_clean|required");
        $this->form_validation->set_rules("lat", "Latitude", "trim|xss_clean|required");
        $this->form_validation->set_rules("lng", "Longitude", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);

        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // call the function
        $this->job_sites_model
            ->process(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     *  process delete job site
     *
     * @param int $breakId
     * @return array
     */
    public function processDeleteJobSite(int $breakId)
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // call the function
        $this->job_sites_model
            ->delete(
                $session["company_detail"]["sid"],
                $breakId
            );
    }

    /**
     * process apply template
     *
     * @return array
     */
    public function processApplyTemplateProcess()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("schedule_id", "Schedule", "trim|xss_clean|required");
        $this->form_validation->set_rules("start_date", "Start date", "trim|xss_clean|required");
        $this->form_validation->set_rules("end_date", "End date", "trim|xss_clean|required");
        $this->form_validation->set_rules("employees[]", "Employees", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->applyTemplate(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     * set page overtime rules
     *
     * @param string $pageSlug
     * @param string $pageId
     * @return array
     */
    private function pageOvertimeRules(string $pageSlug, int $pageId): array
    {
        // set default array
        $data = [];
        // check if page id i set
        if ($pageId) {
            // load schedule model
            $this->load->model("v1/Overtime_rules_model", "overtime_rules_model");
            // check and generate error for session
            $session = checkAndGetSession();
            //
            $data["overtimeRule"] = $this->overtime_rules_model
                ->getOvertimeRuleById(
                    $session["company_detail"]["sid"],
                    $pageId
                );
            //
            if (!$data["overtimeRule"]) {
                return SendResponse(
                    400,
                    [
                        "errors" => [
                            "System failed to verify the rule."
                        ]
                    ]
                );
            }
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/overtime_rules/partials/" . (!$pageId ? "add" : "edit"), $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * set shift break page
     *
     * @param string $pageSlug
     * @param string $pageId
     * @return array
     */
    private function pageShifts(string $pageSlug, int $pageId): array
    {
        // set default array
        $data = [];
        // check if page id i set
        if ($pageId) {
            // load schedule model
            $this->load->model("v1/Shift_break_model", "shift_break_model");
            // check and generate error for session
            $session = checkAndGetSession();
            //
            $data["record"] = $this->shift_break_model
                ->getSingle(
                    $session["company_detail"]["sid"],
                    $pageId
                );
            //
            if (!$data["record"]) {
                return SendResponse(
                    400,
                    [
                        "errors" => [
                            "System failed to verify the break."
                        ]
                    ]
                );
            }
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/" . (!$pageId ? "add" : "edit") . "_break", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * set shift break page
     *
     * @param string $pageSlug
     * @param string $pageId
     * @return array
     */
    private function pageShiftTemplate(string $pageSlug, int $pageId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set default array
        $data = [];
        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);

        // check if page id i set
        if ($pageId) {
            // load schedule model
            $this->load->model("v1/Shift_template_model", "shift_template_model");
            //
            $data["record"] = $this->shift_template_model
                ->getSingle(
                    $session["company_detail"]["sid"],
                    $pageId
                );
            //
            if (!$data["record"]) {
                return SendResponse(
                    400,
                    [
                        "errors" => [
                            "System failed to verify the shift template."
                        ]
                    ]
                );
            }
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/" . (!$pageId ? "add" : "edit") . "_template", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * set job site page
     *
     * @param string $pageSlug
     * @param string $pageId
     * @return array
     */
    private function pageJobSite(string $pageSlug, int $pageId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set default array
        $data = [];
        //
        $data["states"] = $this->db
            ->select("sid, state_name, state_code")
            ->where("active", 1)
            ->where("country_sid", 227)
            ->get("states")
            ->result_array();
        // check if page id i set
        if ($pageId) {
            // load job site model
            $this->load->model("v1/Job_sites_model", "job_sites_model");
            //
            $data["record"] = $this->job_sites_model
                ->getSingle(
                    $session["company_detail"]["sid"],
                    $pageId
                );

            //
            $data["return"] = [
                "lat" => (float)$data["record"]["lat"],
                "lng" => (float)$data["record"]["lng"],
            ];
            //
            if (!$data["record"]) {
                return SendResponse(
                    400,
                    [
                        "errors" => [
                            "System failed to verify the job site."
                        ]
                    ]
                );
            }
        }
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/job_sites/partials/" . (!$pageId ? "add" : "edit") . "_job_site", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * Apply shift template
     *
     * @param string $pageSlug
     * @param string $pageId
     * @return array
     */
    private function pageApplyShiftTemplates(string $pageSlug, int $pageId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data["templates"] = $this->shift_template_model->get($session["company_detail"]["sid"]);
        $data["employees"] = $this->shift_model->getCompanyEmployeesOnly($session["company_detail"]["sid"]);
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/apply_shift_templates", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * Create a single shift template
     *
     * @param string $pageSlug
     * @param string $employeeId
     * @return array
     */
    private function pageCreateSingleShift(string $pageSlug, int $employeeId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data["employees"] = $this->shift_model->getCompanySingleEmployee(
            $session["company_detail"]["sid"],
            $employeeId
        );
        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["jobSites"] = $this->job_sites_model
            ->get($session["company_detail"]["sid"]);
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/create_single_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * Create a single shift template
     *
     * @param string $pageSlug
     * @param string $shiftId
     * @return array
     */
    private function pageEditSingleShift(string $pageSlug, int $shiftId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");

        $data["shift"] = $this->shift_model->getSingle(
            $session["company_detail"]["sid"],
            $shiftId
        );

        // alert($shiftId);
        if (empty($data["shift"])) {
            return SendResponse(400, [
                'errors' => ["Shift Not Found"]
            ]);
        }

        $employeeId =  $data["shift"]["employee_sid"];
        // _e($employeeId,true,true);
        //
        $data["employees"] = $this->shift_model->getCompanySingleEmployee(
            $session["company_detail"]["sid"],
            $employeeId
        );

        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["jobSites"] = $this->job_sites_model
            ->get($session["company_detail"]["sid"]);

        //
        $data['shiftHistoryCount'] = $this->shift_model->getShiftRequestsHistory($shiftId, true);

        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/edit_single_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }


    //
    private function pageCreateMultiShift(string $pageSlug, int $employeeId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data["employees"] = $this->shift_model->getCompanyEmployeesOnly($session["company_detail"]["sid"]);

        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["jobSites"] = $this->job_sites_model
            ->get($session["company_detail"]["sid"]);
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/create_multi_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }


    //
    private function pageDeleteMultiShift(string $pageSlug, int $employeeId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data["employees"] = $this->shift_model->getCompanyEmployees($session["company_detail"]["sid"]);

        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["jobSites"] = $this->job_sites_model
            ->get($session["company_detail"]["sid"]);
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/delete_multi_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }


    //
    private function pageCopyShift(string $pageSlug, int $employeeId): array
    {

        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //

        $data["employees"] = $this->shift_model->getCompanyEmployeesOnly($session["company_detail"]["sid"]);

        // load break model
        $this->load->model("v1/Shift_break_model", "shift_break_model");
        // get the breaks
        $data["breaks"] = $this->shift_break_model
            ->get($session["company_detail"]["sid"]);
        // load schedule model
        $this->load->model("v1/Job_sites_model", "job_sites_model");
        // get the records
        $data["jobSites"] = $this->job_sites_model
            ->get($session["company_detail"]["sid"]);
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/copy_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * process  shift templates
     *
     * @return array
     */
    public function processCreateSingleShift()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("shift_employee", "Employee", "trim|xss_clean|required");
        $this->form_validation->set_rules("shift_date", "Shift date", "trim|xss_clean|required");
        $this->form_validation->set_rules("start_time", "Start time", "trim|xss_clean|required");
        $this->form_validation->set_rules("end_time", "End time", "trim|xss_clean|required");
        // run the validation

        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }
        // set the sanitized post
        $post = $this->input->post(null, true);

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function

        if ($post['create_and_send_shift']) {
            //echo "createAndSend";
            $this->shift_model
                ->processCreateSingleShift(
                    $session["company_detail"]["sid"],
                    $post,
                    true
                );
        } else {

            $this->shift_model
                ->processCreateSingleShift(
                    $session["company_detail"]["sid"],
                    $post
                );
        }
    }

    //
    public function processApplyMulitProcess()
    {

        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("shift_date_from", "From Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("shift_date_to", "To Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("employees[]", "Employees", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }

        // set the sanitized post
        $post = $this->input->post(null, true);

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->applyMultiShifts(
                $session["company_detail"]["sid"],
                $post
            );
    }

    //
    public function processCopyMulitProcess()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("last_shift_date_from", "From Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("last_shift_date_to", "To Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("shift_date_from", "From Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("shift_date_to", "To Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("employees[]", "Employees", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }

        // set the sanitized post
        $post = $this->input->post(null, true);

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->copyMultiShifts(
                $session["company_detail"]["sid"],
                $post
            );
    }

    //
    public function processDeleteMulitProcess()
    {

        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $this->form_validation->set_rules("shift_date_from", "From Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("shift_date_to", "To Date", "trim|xss_clean|required");
        $this->form_validation->set_rules("employees[]", "Employees", "trim|xss_clean|required");
        // run the validation
        if (!$this->form_validation->run()) {
            return SendResponse(400, getFormErrors());
        }

        // set the sanitized post
        $post = $this->input->post(null, true);

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->deleteMultiShifts(
                $session["company_detail"]["sid"],
                $post
            );
    }

    //
    public function processDeleteSingleProcess()
    {
        // check and generate error for session
        $session = checkAndGetSession();

        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->deleteSingleShift(
                $session["company_detail"]["sid"],
                $post
            );
    }

    /**
     * list employee page schedules
     */
    public function employeesSchedule()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Employees Pay Schedule | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        // get the schedules
        $data["schedules"] = $this->schedule_model
            ->getCompanySchedules(
                $loggedInCompany["sid"],
                1
            );
        // get the schedules
        $data["employee_schedule_ids"] = $this->schedule_model
            ->getCompanyEmployeeSchedulesIds(
                $loggedInCompany["sid"],
                true
            );
        // get all employees
        $data["employees"] = $this->db
            ->select(getUserFields())
            ->where([
                "parent_sid" => $loggedInCompany["sid"],
                "active" => 1,
                "terminated_status" => 0,
                "is_executive_admin" => 0
            ])
            ->get("users")
            ->result_array();
        //
        $this->load->view('main/header', $data);
        $this->load->view('v1/employee_schedules/index');
        $this->load->view('main/footer');
    }

    /**
     * list employee page schedules
     */
    public function employeesScheduleEdit()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Employees Pay Schedule Edit | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        // get the schedules
        $data["schedules"] = $this->schedule_model
            ->getCompanySchedules(
                $loggedInCompany["sid"],
                1
            );
        // get the schedules
        $data["employee_schedule_ids"] = $this->schedule_model
            ->getCompanyEmployeeSchedulesIds(
                $loggedInCompany["sid"]
            );
        // get all employees
        $data["employees"] = $this->db
            ->select(getUserFields())
            ->where([
                "parent_sid" => $loggedInCompany["sid"],
                "active" => 1,
                "terminated_status" => 0,
                "is_executive_admin" => 0
            ])
            ->get("users")
            ->result_array();
        //
        if ($data["employees"]) {
            // set common files bundle
            $data["pageCSS"] = [
                getPlugin("alertify", "css"),
            ];
            $data["pageJs"] = [
                getPlugin("alertify", "js"),
            ];
            // set bundle
            $data["appJs"] = bundleJs([
                "v1/schedules/employee/edit"
            ], "public/v1/schedules/employee/edit/", "edit_employees_schedule", true);
        }
        $this->load->view('main/header', $data);
        $this->load->view('v1/employee_schedules/edit');
        $this->load->view('main/footer');
    }

    /**
     * process employee schedule
     */
    public function processCmployeesScheduleEdit()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true)) {
            return SendResponse(
                400,
                [
                    "errors" => [
                        "Access denied!"
                    ]
                ]
            );
        }
        // get the sanitized post
        $post = $this->input->post(null, true);
        // add validation rules
        $this->form_validation->set_rules("data[]", "Data", "required|xss_clean|trim");
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get the logged in company
        $loggedInCompany = checkAndGetSession("company_detail");
        $post["companyId"] = $loggedInCompany["sid"];
        //
        if (checkIfAppIsEnabled(PAYROLL)) {
            $this->load->model("v1/Payroll_model", "payroll_model");
            $response = $this->payroll_model->updateEmployeePaySchedules(
                $post
            );

            if ($response["errors"]) {
                return SendResponse(
                    400,
                    $response
                );
            }
        } else {
            // load schedule model
            $this->load->model("v1/Schedule_model", "schedule_model");
            // add employees to AutomotoHR
            $this->schedule_model->linkEmployeesToPaySchedule($post);
        }
        //
        return SendResponse(200, [
            "msg" => "You have successfully updated the emloyees pay schedule."
        ]);
    }

    /**
     * Manage shifts
     */
    public function manageMyShifts()
    {
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }

        //
        $session = $this->session->userdata('logged_in');
        //
        $employeeId = $session['employer_detail']['sid'];

        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // get all active employees

        $employees = $this->input->get("employees");

        $filterStartDate = $this->input->get("start_date");
        $filterEndDate = $this->input->get("end_date");

        $toggleFilter = true;

        $employeesArray = explode(',', $employees);
        $team = $this->input->get("team", true);
        $employeeFilter['employees'] = $employeesArray;
        $employeeFilter['team'] = $team;

        $jobTitle = $this->input->get("jobtitle", true);
        $employeeFilter['jobtitle'] = explode(',', $jobTitle);


        if ($filterStartDate != '') {
            $toggleFilter = false;
        }

        if ($employees == '') {
            $employees = 'all';
        }

        $employeeFilter['employees'] = [$employeeId];
        $data["employees"] = $this->shift_model->getCompanyEmployees(
            $loggedInCompany["sid"],
            $employeeFilter
        );

        $data["allemployees"] = $this->shift_model->getCompanyEmployeesOnly(
            $loggedInCompany["sid"]
        );

        //
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";

        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates['end_date'];
        } elseif ($data["filter"]["mode"] === "two_week") {
            // get the current week dates
            $weekDates = getWeekDates(true, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates["current_week"]['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates["next_week"]['end_date'];
        } else {
            $data["filter"]["month"] = $this->input->get("month", true) ?? getSystemDate("m");
            $data["filter"]["year"] = $this->input->get("year", true) ?? getSystemDate("Y");
            //
            $data["filter"]["start_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "01/m/Y");
            //
            $data["filter"]["end_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "t/m/Y");
        }


        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $employeeIds = array_column($data["employees"], "userId");
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            $employeeIds
        );
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] = $employeeIds ? $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                $employeeIds,
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            ) : [];

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["filter_team"] = $team;
        $data["filter_employees"] =
            explode(",", $employees);
        $data["filter_toggle"] = $toggleFilter;

        $data["filter_jobtitle"] =
            explode(",", $jobTitle);

        $data["filterStartDate"] = $filterStartDate;
        $data["filterEndDate"] = $filterEndDate;

        // get off and holidays
        $data["holidays"] = $this->shift_model->getCompanyHolidaysWithTitle(
            $loggedInCompany["sid"],
            $data["filter"]
        );

        $data['title'] = "My Scheduling | " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = 0;
        $data['page'] = "my_courses";
        $data['viewMode'] = "my";
        $data['employee'] = $session['employer_detail'];
        $data['haveSubordinate'] = $haveSubordinate;
        $data['load_view'] = 1;
        $data['type'] = "self";

        $bundleCSS = bundleCSS(['v1/plugins/ms_modal/main'], 'public/v1/css/', 'dashboard', true);

        $data['appCSS'] = $bundleCSS;

        /*
        $bundleJS = bundleJs([
            'v1/plugins/ms_modal/main',
            'js/app_helper',
        ], 'public/v1/js/', 'dashboard', true);
      */

        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
            getPlugin("select2", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("select2", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/ems_main"
        ], "public/v1/shifts/", "ems_main", true);
        //

        $this->load->view('main/header_2022', $data);
        $this->load->view('v1/settings/shifts/my_listing');
        $this->load->view('main/footer');
    }

    //
    public function manageSubordinateShifts()
    {
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }

        //
        $session = $this->session->userdata('logged_in');
        //
        $employeeId = $session['employer_detail']['sid'];

        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "Manage Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // get all active employees

        $myDepartments = $this->shift_model->getMyTeams($employeeId);

        $myDepartmentList = $this->shift_model->getDepartments($myDepartments['departments']);
        $myTeamList = $this->shift_model->getTeams($myDepartments['teams']);

        $data['myDepartmentList'] = $myDepartmentList;
        $data['myTeamList'] = $myTeamList;

        $filterStartDate = $this->input->get("start_date");
        $filterEndDate = $this->input->get("end_date");
        $filterDepartments = $this->input->get("departments");
        $filterTeams = $this->input->get("teams");

        $data['filterDepartments'] = $filterDepartments;
        $data['filterTeams'] = $filterTeams;

        $filterDepartmentsArray = explode(',', $filterDepartments);
        $filterTeamsArray = explode(',', $filterTeams);

        if ($filterDepartmentsArray[0] == 'all' || $filterDepartmentsArray[0] == '') {
            $filterDepartmentsArray = array_column($data['myDepartmentList'], 'sid');
        }

        if ($filterTeamsArray[0] == 'all' || $filterTeamsArray[0] == '') {
            $filterTeamsArray  = array_column($data['myTeamList'], 'sid');
        }

        $employeeFilter['departments'] = $filterDepartmentsArray;
        $employeeFilter['teams'] = $filterTeamsArray;

        $toggleFilter = true;

        if ($filterStartDate != '' || $filterDepartments != '' || $filterEndDate != '') {
            $toggleFilter = false;
        }

        $data["employees"] = $this->shift_model->getSubordinatesEmployees(
            $loggedInCompany["sid"],
            $employeeFilter
        );

        $data["allemployees"] = $this->shift_model->getCompanyEmployeesOnly(
            $loggedInCompany["sid"]
        );

        //
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";

        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates['end_date'];
        } elseif ($data["filter"]["mode"] === "two_week") {
            // get the current week dates
            $weekDates = getWeekDates(true, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates["current_week"]['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates["next_week"]['end_date'];
        } else {
            $data["filter"]["month"] = $this->input->get("month", true) ?? getSystemDate("m");
            $data["filter"]["year"] = $this->input->get("year", true) ?? getSystemDate("Y");
            //
            $data["filter"]["start_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "01/m/Y");
            //
            $data["filter"]["end_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "t/m/Y");
        }


        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $employeeIds = array_column($data["employees"], "userId");
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            $employeeIds
        );
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] = $employeeIds ? $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                $employeeIds,
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            ) : [];

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["filter_team"] = $team;
        $data["filter_toggle"] = $toggleFilter;
        $data["filterStartDate"] = $filterStartDate;
        $data["filterEndDate"] = $filterEndDate;

        // get off and holidays
        $data["holidays"] = $this->shift_model->getCompanyHolidaysWithTitle(
            $loggedInCompany["sid"],
            $data["filter"]
        );

        //

        $data['title'] = "My Scheduling | " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = 0;
        $data['page'] = "my_courses";
        $data['viewMode'] = "my";
        $data['employee'] = $session['employer_detail'];
        $data['haveSubordinate'] = $haveSubordinate;
        $data['load_view'] = 1;
        $data['type'] = "self";

        $bundleCSS = bundleCSS(['v1/plugins/ms_modal/main'], 'public/v1/css/', 'dashboard', true);

        $data['appCSS'] = $bundleCSS;

        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
            getPlugin("select2", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("select2", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),
            "v1/plugins/ms_modal/main"
        ];


        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/ems_subordinate_main"
        ], "public/v1/shifts/", "ems_subordinate_main", false);
        //

        $this->load->view('main/header_2022', $data);
        $this->load->view('v1/settings/shifts/subordinate_listing');
        $this->load->view('main/footer');
    }

    //
    public function processSingleShiftPublishStatus()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set the sanitized post
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $loggedInCompany = checkAndGetSession("company_detail");
        //
        $this->shift_model
            ->SingleShiftPublishStatusChange(
                $session["company_detail"]["sid"],
                $post
            );
        //
        if ($post['sendEmail'] == 1) {
            $shiftids = explode(',', $post['shiftId']);
            // get the message header and footer
            $message_hf = message_header_footer(
                $loggedInCompany["sid"],
                $loggedInCompany['CompanyName']
            );
            // get the shifts
            $shiftsData = $this->shift_model
                ->getShiftsById(
                    $loggedInCompany["sid"],
                    $shiftids
                );

            $this->sendShiftPublishEmailNotification(
                $shiftsData,
                $message_hf,
                $loggedInCompany['CompanyName'],
                get_email_template(
                    SHIFTS_PUBLISH_CONFIRMATION
                )
            );
        }

        $msg = $post['publishStatus'] == 1 ? " Published " : " Unpublished ";

        return SendResponse(
            200,
            [
                "msg" => "You have successfully " . $msg . " a shift."
            ]
        );
    }

    //
    public function processMultiShiftPublishStatus()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set the sanitized post
        $post = $this->input->post(null, true);
        //
        $loggedInCompany = checkAndGetSession("company_detail");
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function
        $this->shift_model
            ->SingleMultiPublishStatusChange(
                $session["company_detail"]["sid"],
                $post
            );
        // Send  Notification Email
        if ($post['sendEmail'] == 1) {

            $loggedInEmployee = checkAndGetSession("employer_detail");

            if (isset($post['sendShiftsEmailOption']) && $post['sendShiftsEmailOption'] == 'all') {
                $shiftids = explode(',', $post['allShiftsId']);
            } else {
                $shiftids = explode(',', $post['shiftIds']);
            }


            $shiftPublishStatus = '';
            if ($post['publishStatus'] == 0) {
                $shiftPublishStatus = 'Unpublished';
            } else {
                $shiftPublishStatus = 'Published';
            }

            $message_hf = message_header_footer($loggedInCompany["sid"], $loggedInCompany['CompanyName']);

            $shiftsData = $this->shift_model->getShiftsById($loggedInCompany["sid"], $shiftids);

            $empdata = [];

            foreach ($shiftsData as $key => $row) {

                $empdata[$row['employee_sid']][] = $row;
            }
            //
            $template = get_email_template(
                SHIFTS_PUBLISH_CONFIRMATION
            );

            //
            foreach ($empdata as $empRow) {
                $this->sendShiftPublishEmailNotification(
                    $empRow,
                    $message_hf,
                    $loggedInCompany['CompanyName'],
                    $template
                );
            }
        }

        return SendResponse(
            200,
            [
                "msg" => "The shifts have been successfully published."
            ]
        );
    }

    /**
     * Send email notification publish
     */
    private function sendShiftPublishEmailNotification(
        array $shiftsData,
        array $message_hf,
        string $companyName,
        array $emailTemplateData
    ) {
        // set replace array
        $replaceArray = [
            "{{first_name}}" => $shiftsData[0]["first_name"],
            "{{last_name}}" => $shiftsData[0]["last_name"],
            "{{shift_details}}" => "",
            "{{company_name}}" => $companyName,
        ];
        //
        $shiftDetails = "";
        $shiftDetails = '<table>';
        $shiftDetails .= '<thead>';
        $shiftDetails .= '  <tr>';
        $shiftDetails .= '      <th scope="col">Shift Date</th>';
        $shiftDetails .= '      <th scope="col">Shift Time</th>';
        $shiftDetails .= '  </tr>';
        $shiftDetails .= '</thead>';
        $shiftDetails .= '<tbody>';

        // set the first and last name
        foreach ($shiftsData as $row) {
            $shiftDetails .= "<tr>";
            $shiftDetails .= "<td>" . formatDateToDB(
                $row['shift_date'],
                DB_DATE,
                DATE
            ) . "</td>";
            $shiftDetails .= "<td>";
            $shiftDetails .= formatDateToDB(
                $row["start_time"],
                "H:i:s",
                "h:i A"
            );
            $shiftDetails .= " - ";
            $shiftDetails .= formatDateToDB(
                $row["end_time"],
                "H:i:s",
                "h:i A"
            );
            $shiftDetails .= "</td>";
            $shiftDetails .= "</tr>";
        }
        //
        $shiftDetails .= '</tbody>';
        $shiftDetails .= "</table>";
        //
        $replaceArray["{{shift_details}}"] = $shiftDetails;

        $emailTemplateBody = $emailTemplateData['text'];
        $emailTemplateSubject = $emailTemplateData['subject'];
        //
        $emailTemplateSubject = str_replace(
            array_keys(
                $replaceArray
            ),
            $replaceArray,
            $emailTemplateSubject
        );
        //
        $emailTemplateBody = str_replace(
            array_keys(
                $replaceArray
            ),
            $replaceArray,
            $emailTemplateBody
        );

        $body = $message_hf['header']
            . $emailTemplateBody
            . $message_hf['footer'];


        log_and_sendEmail(
            $emailTemplateData['from_email'],
            $shiftsData[0]["email"],
            $emailTemplateSubject,
            $body,
            STORE_NAME
        );
    }

    //
    public function shiftsTrade()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data

        $data = [];
        $data["title"] = "Trade Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(getSystemDate(SITE_DATE), SITE_DATE);
        $weekEndDate = formatDateToDB(date('Y-m-d', strtotime($weekStartDate . ' + 1 month')), 'Y-m-d', SITE_DATE);
        $weekStartDate = formatDateToDB($weekStartDate, 'Y-m-d', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getEmployeeShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/trade_list');
        $this->load->view('main/footer');
    }

    //
    private function pageTradeShift(string $pageSlug, $shiftsIds): array
    {

        //
        $shiftsIdsArray = explode(',', $shiftsIds);
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");

        $shiftsData = $this->shift_model->getShiftsByShiftId($shiftsIdsArray);
        $shiftsDate = array_column($shiftsData, 'shift_date');

        $employeesOnLeave = $this->shift_model->getEmployeesOnLeave($shiftsDate, $session["company_detail"]["sid"]);
        $employeesWithShifts = $this->shift_model->getEmployeesWithShifts($shiftsDate, $session["company_detail"]["sid"]);

        $data["employeesOnLeave"] = array_unique(array_merge($employeesOnLeave, $employeesWithShifts));
        //
        $data["employees"] = $this->shift_model->getCompanyEmployeesForShiftSwap($session["company_detail"]["sid"], $session["employer_detail"]["sid"]);

        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/trade_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    //
    public function processTradeShifts()
    {
        $session = checkAndGetSession();
        $post = $this->input->post(null, true);
        $shiftIds = explode(',', $post['shiftids']);
        $toEmployeeSid = $post['employeeid'];

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");

        $data["shiftsData"] = $this->shift_model->getShiftsByShiftId($shiftIds);

        //
        foreach ($data["shiftsData"] as $rowShifts) {
            $data_insert_request = [];
            $data_insert_request['shift_sid'] = $rowShifts['sid'];
            $data_insert_request['to_employee_sid'] = $toEmployeeSid;
            $data_insert_request['from_employee_sid'] = $rowShifts['employee_sid'];
            $data_insert_request['created_at'] = getSystemDate();
            $data_insert_request['updated_at'] = getSystemDate();

            $this->shift_model->addShiftsTradeRequest($rowShifts['sid'], $data_insert_request);
        }


        // send mail
        $emailTemplateFromEmployee = get_email_template(SHIFTS_SWAP_AWAITING_CONFIRMATION);
        $emailTemplateToEmployee = $emailTemplateFromEmployee;
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds);
        //
        foreach ($requestsData as $requestRow) {
            //
            $fromName = checkAndGetSession("employer_detail")['first_name'] . ' ' . checkAndGetSession("employer_detail")['first_name'];
            //
            if ($requestRow['to_employee_sid'] != '') {
                //
                $emailTemplateToEmployee['text'] = str_replace('{{to_employee_name}}', $requestRow['to_employee'], $emailTemplateToEmployee['text']);
                $emailTemplateToEmployee['text'] = str_replace('{{from_employee_name}}', $requestRow['updated_by'], $emailTemplateToEmployee['text']);
                //
                $emailTemplateBodyToEmployee = $this->shiftSwapEmailTemplate($emailTemplateToEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['to_employee']);

                $from = $emailTemplateToEmployee['from_email'];
                $to = $requestRow['to_employee_email'];
                $subject = $emailTemplateToEmployee['subject'];
                $from_name = $emailTemplateToEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyToEmployee
                    . EMAIL_FOOTER;
                //
                if ($_SERVER['SERVER_NAME'] != 'localhost') {
                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }
        }


        return SendResponse(200, [
            "msg" => "You have successfully sent a request to swap your shift."
        ]);
    }


    //
    public function processTradeShiftsCancel()
    {
        $session = checkAndGetSession();
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $post = $this->input->post(null, true);
        $shiftIds = explode(',', $post['shiftids']);

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");

        //
        $data_update_request = [];
        $shiftIds = $shiftIds;
        $data_update_request['request_status'] = 'cancelled';
        $data_update_request['updated_at'] = getSystemDate();
        $data_update_request['to_employee_sid'] = $loggedInEmployee['sid'];
        $this->shift_model->cancelShiftsTradeRequest($shiftIds, $data_update_request);

        return SendResponse(200, [
            "msg" => "You have successfully cancelled Request."
        ]);
    }

    //
    public function MyshiftsTrade()
    {
        // check if plus or don't have access to the module
        if (!isPayrollOrPlus(true) || !checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data

        $data = [];
        $data["title"] = "Trade Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(date('m-01-Y'), 'm-d-Y', SITE_DATE);
        $weekEndDate = formatDateToDB(date('m-t-Y'), 'm-d-Y', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getMySwapShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/my_trade');
        $this->load->view('main/footer');
    }

    //
    public function processMyShiftsChangeStatus()
    {
        $session = checkAndGetSession();
        $loggedInEmployee = checkAndGetSession("employer_detail");

        $post = $this->input->post(null, true);
        $shiftIds = explode(',', $post['shiftids']);
        $shiftStatus = $post['shiftstatus'];
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data_update_request = [];
        $data_update_request['request_status'] = $shiftStatus;
        $data_update_request['updated_at'] = getSystemDate();
        $data_update_request['to_employee_sid'] = $loggedInEmployee['sid'];
        //
        $this->shift_model->cancelShiftsTradeRequest($shiftIds, $data_update_request);
        //
        // send mail
        //
        if ($shiftStatus == 'rejected') {
            $this->sendEmailToEmployee($shiftIds);
            
        } else if ($shiftStatus == 'confirmed') {
            
            $this->sendEmailToAdmin($shiftIds);
        }
        //
        return SendResponse(200, [
            "msg" => "You have successfully " . $shiftStatus . " Request."
        ]);
    }

    function sendEmailToEmployee ($shiftIds) {
        //
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds);
        //
        foreach ($requestsData as $requestRow) {
            //
            $emailTemplate = get_email_template(SHIFTS_SWAP_EMPLOYEE_REJECTION);
            //
            if ($requestRow['from_employee_sid'] != '') {
                //
                $emailTemplate['text'] = str_replace('{{to_employee}}', $requestRow['to_employee'], $emailTemplate['text']);
                $emailTemplate['text'] = str_replace('{{from_employee}}', $requestRow['from_employee'], $emailTemplate['text']);
                //
                $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplate['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee']);
                //
                $from = $emailTemplate['from_email'];
                $to = $requestRow['from_employee_email'];
                $subject = $emailTemplate['subject'];
                $from_name = $emailTemplate['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyFromEmployee
                    . EMAIL_FOOTER;
                //
                if ($_SERVER['SERVER_NAME'] != 'localhost') {
                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
                //
            }
        }
    }

    function sendEmailToAdmin ($shiftIds) {
        //
        $companyDetail = checkAndGetSession("company_detail");
        $adminList = getCompanyAdminPlusList($companyDetail['sid']);
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds);
        //
        if ($adminList) {
            foreach ($adminList as $admin) {
                $adminName = $admin['first_name'] . " " . $admin['last_name'];
                //
                foreach ($requestsData as $requestRow) {
                    //
                    $requestRow['admin_id'] = $admin['sid'];
                    //
                    $emailTemplate = get_email_template(SHIFTS_SWAP_ADMIN_APPROVAL);
                    //
                    if ($requestRow['from_employee_sid'] != '') {
                        //
                        $emailTemplate['text'] = str_replace('{{admin_name}}', $adminName, $emailTemplate['text']);
                        $emailTemplate['text'] = str_replace('{{to_employee}}', $requestRow['to_employee'], $emailTemplate['text']);
                        $emailTemplate['text'] = str_replace('{{from_employee}}', $requestRow['from_employee'], $emailTemplate['text']);
                        //
                        $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplate['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee'], true);
                        //
                        $from = $emailTemplate['from_email'];
                        $to = $admin['email'];
                        $subject = $emailTemplate['subject'];
                        $from_name = $emailTemplate['from_name'];
                        $body = EMAIL_HEADER
                            . $emailTemplateBodyFromEmployee
                            . EMAIL_FOOTER;
                        //
                        if ($_SERVER['SERVER_NAME'] != 'localhost') {
                            sendMail($from, $to, $subject, $body, $from_name);
                        }
                        //
                        $emailData = array(
                            'date' => date('Y-m-d H:i:s'),
                            'subject' => $subject,
                            'email' => $to,
                            'message' => $body,
                        );
                        save_email_log_common($emailData);
                        //
                    }
                }
            }
        }    
    }

    /**
     * AJAX request handler
     *
     * @accepts POST
     * 'action'
     *
     * @return JSON
     */
    function handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        ///
        $post = $this->input->post(NULL, TRUE);

        if (!sizeof($post) || !isset($post['action'])) $this->resp();
        //  if (!isset($post['companyId']) || $post['companyId'] == '') $this->resp();

        // For expired session
        if (empty($this->session->userdata('logged_in'))) {
            $this->res['Redirect'] = true;
            $this->res['Response'] = 'Your login session has expired.';
            $this->res['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }

        //
        $this->res['Redirect'] = FALSE;
        $this->load->model("v1/Shift_model", "shift_model");

        switch (strtolower($post['action'])) {
                // 
            case "get_requests":
                //
                $data = $this->shift_model->getSwapShiftsRequest($post);
                //
                $this->res['requestsType'] = $post['type'];
                //
                $post['type'] = 'all';
                //
                $allData = $this->shift_model->getSwapShiftsRequest($post);
                //
                $allRequests = 0;
                $pendingRequests = 0;
                $approvedRequests = 0;
                $rejectedRequests = 0;
                foreach ($allData as $row) {
                    if ($row['request_status'] == 'Confirmed') {
                        $pendingRequests++;
                    } else if ($row['request_status'] == 'Approved') {
                        $approvedRequests++;
                    } else if ($row['request_status'] == 'Admin Rejected') {
                        $rejectedRequests++;
                    }
                    $allRequests++;
                }
                //
                $this->res['allRequests'] = $allRequests;
                $this->res['pendingRequests'] = $pendingRequests;
                $this->res['rejectedRequests'] = $rejectedRequests;
                $this->res['approvedRequests'] = $approvedRequests;
                // 
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed...';
                $this->res['Data'] = $data;
                break;
        }
        //
        $this->resp();
    }

    /**
     * AJAX Responder
     */
    private function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }

    //
    public function processTradeShiftsApprove()
    {
        //
        $session = $this->session->userdata('logged_in');
        $employeeId = $session['employer_detail']['sid'];
        $companyId = $session["company_detail"]["sid"];
        //
        $post = $this->input->post(null, true);
        $shiftIds = explode(',', $post['shiftids']);
        $toEmployeeid = $post['toEmployeeId'];
        //
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data_update_request = [];
        $data_update_request['request_status'] = 'approved';
        $data_update_request['admin_sid'] = $employeeId;
        $data_update_request['updated_at'] = getSystemDate();
        //
        if ($toEmployeeid) {
            $this->shift_model->updateShiftsTradeRequest($post['shiftids'], $toEmployeeid, $data_update_request);
        } else {
            $this->shift_model->cancelShiftsTradeRequest($shiftIds, $data_update_request);
        }
        //
        // send mail
        $emailTemplateFromEmployee = get_email_template(SHIFTS_SWAP_ADMIN_APPROVED);
        $emailTemplateToEmployee = $emailTemplateFromEmployee;
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds, 'approved');
        //
        foreach ($requestsData as $requestRow) {
            //
            if ($requestRow['from_employee_sid'] != '') {
                //
                $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplateFromEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['from_employee']);
                //
                $from = $emailTemplateFromEmployee['from_email'];
                $to = $requestRow['from_employee_email'];
                $subject = $emailTemplateFromEmployee['subject'];
                $from_name = $emailTemplateFromEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyFromEmployee
                    . EMAIL_FOOTER;

                if ($_SERVER['SERVER_NAME'] != 'localhost') {

                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }
            //
            if ($requestRow['to_employee_sid'] != '') {

                $emailTemplateBodyToEmployee = $this->shiftSwapEmailTemplate($emailTemplateToEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['to_employee']);

                $from = $emailTemplateToEmployee['from_email'];
                $to = $requestRow['to_employee_email'];
                $subject = $emailTemplateToEmployee['subject'];
                $from_name = $emailTemplateToEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyToEmployee
                    . EMAIL_FOOTER;

                if ($_SERVER['SERVER_NAME'] != 'localhost') {

                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }
        }


        return SendResponse(200, [
            "msg" => "You have successfully Approved Request."
        ]);
    }

    //
    public function processTradeShiftsReject()
    {

        //
        $session = $this->session->userdata('logged_in');
        $employeeId = $session['employer_detail']['sid'];
        //
        $post = $this->input->post(null, true);
        $shiftIds = explode(',', $post['shiftids']);
        $toEmployeeid = $post['toEmployeeId'];
        //
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data_update_request = [];
        $data_update_request['request_status'] = 'admin rejected';
        $data_update_request['admin_sid'] = $employeeId;
        $data_update_request['updated_at'] = getSystemDate();
        //
        if ($toEmployeeid) {
            $this->shift_model->updateShiftsTradeRequest($post['shiftids'], $toEmployeeid, $data_update_request);
        } else {
            $this->shift_model->cancelShiftsTradeRequest($shiftIds, $data_update_request);
        }
        
        
        //
        // send mail
        $emailTemplateFromEmployee = get_email_template(SHIFTS_SWAP_ADMIN_REJECTED);
        $emailTemplateToEmployee = $emailTemplateFromEmployee;
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds, 'admin rejected');
        //
        foreach ($requestsData as $key => $requestRow) {

            if ($requestRow['from_employee_sid'] != '') {
                //
                $emailTemplateBodyFromEmployee = $this->shiftSwapEmailTemplate($emailTemplateFromEmployee['text'], $requestRow,  $requestRow['companyName'], $requestRow['from_employee']);
                //
                $from = $emailTemplateFromEmployee['from_email'];
                $to = $requestRow['from_employee_email'];
                $subject = $emailTemplateFromEmployee['subject'];
                $from_name = $emailTemplateFromEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyFromEmployee
                    . EMAIL_FOOTER;

                if ($_SERVER['SERVER_NAME'] != 'localhost') {

                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }

            if ($requestRow['to_employee_sid'] != '') {

                $emailTemplateBodyToEmployee = $this->shiftSwapEmailTemplate($emailTemplateToEmployee['text'], $requestRow,  $requestRow['companyName'], $requestRow['to_employee']);

                $from = $emailTemplateToEmployee['from_email'];
                $to = $requestRow['to_employee_email'];
                $subject = $emailTemplateToEmployee['subject'];
                $from_name = $emailTemplateToEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyToEmployee
                    . EMAIL_FOOTER;

                if ($_SERVER['SERVER_NAME'] != 'localhost') {

                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }
        }


        return SendResponse(200, [
            "msg" => "You have successfully rejected the swap shift request."
        ]);
    }

    //
    function shiftSwapEmailTemplate($emailTemplateBody, $replacementArray, $companyName, $employeeName, $isAdmin = false)
    {
        if (!$replacementArray) {
            return $emailTemplateBody;
        }
        //
        if ($isAdmin == true) {
            $reject_shift = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['admin_id'] . '/admin_reject' . '" target="_blank">Reject</a>';
            //
            $confirm_shift = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['admin_id'] . '/admin_approve' . '" target="_blank">Approve</a>';
        } else {
            $reject_shift = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['to_employee_sid'] . '/employee_reject' . '" target="_blank">Reject</a>';
            //
            $confirm_shift = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'swap_shift_confirm/' . $replacementArray['shift_sid'] . '/' . $replacementArray['to_employee_sid'] . '/employee_approve' . '" target="_blank">Approve</a>';
        }
        //
        $emailTemplateBody = str_replace('{{employee_name}}', $employeeName, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_date}}', $replacementArray['shift_date'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_time}}', $replacementArray['start_time'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_status}}', $replacementArray['request_status'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{company_name}}', $companyName, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{shift_status_by}}', $replacementArray['updated_by'], $emailTemplateBody);
        $emailTemplateBody = str_replace('{{reject_shift}}', $reject_shift, $emailTemplateBody);
        $emailTemplateBody = str_replace('{{approve_shift}}', $confirm_shift, $emailTemplateBody);

        return $emailTemplateBody;
    }

    //
    private function pageSendShift(string $pageSlug, int $employeeId): array
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");
        //
        $data["employees"] = $this->shift_model->getCompanyEmployeesOnly($session["company_detail"]["sid"]);

        // load break model

        $data['company_sid'] = $session["company_detail"]["sid"];

        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/send_shift", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    //
    public function processGetEmployeeList()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $post = $this->input->post(null, true);
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // call the function

        $employeeFilter = [];

        $employeeFilter['team'] = $post['departmentId'];
        $employeeFilter['jobtitle'] = $post['job_titles'];
        $employeeFilter['shift_date'] = $post['shift_date'];

        $employeesdata = $this->shift_model->getCompanyEmployees(
            $session["company_detail"]["sid"],
            $employeeFilter
        );


        if (!empty($employeesdata)) {
            foreach ($employeesdata as $key => $row) {
                $employeesdata[$key]['employee_name'] = getUserNameBySID($row['userId']);
            }
        }

        $data["employeesdata"] = $employeesdata;

        return SendResponse(
            200,
            [
                "list" => $data["employeesdata"]
            ]
        );
      
    }

    //
    public function processSendShift()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set up the rules
        $post = $this->input->post(null, true);

        $shiftIds = explode(',', $post['shift_id']);
        $employees = $post['employees'];

        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");

        $data["shiftsData"] = $this->shift_model->getShiftsByShiftId($shiftIds);
        //
        foreach ($data["shiftsData"] as $rowShifts) {

            foreach ($employees as $toEmployeeSid) {
                $data_insert_request = [];
                $data_insert_request['shift_sid'] = $rowShifts['sid'];
                $data_insert_request['to_employee_sid'] = $toEmployeeSid;
                $data_insert_request['from_employee_sid'] = $rowShifts['employee_sid'];
                $data_insert_request['created_at'] = getSystemDate();
                $data_insert_request['updated_at'] = getSystemDate();
                $data_insert_request['request_type'] = 'open';

                $this->shift_model->addShiftsTradeRequest($rowShifts['sid'], $data_insert_request);
            }
        }


        // send mail
        $emailTemplateFromEmployee = get_email_template(SHIFTS_SWAP_AWAITING_CONFIRMATION);
        $emailTemplateToEmployee = $emailTemplateFromEmployee;
        $requestsData = $this->shift_model->getSwapShiftsRequestById($shiftIds);

        $companyId = $session['company_detail']['sid'];
        $company_data = $session['company_detail'];

        $shiftsArray = [];
        foreach ($requestsData as $requestRow) {
            //
            if ($requestRow['to_employee_sid'] != '') {

                $emailTemplateFromEmployee = get_email_template(SHIFTS_SWAP_AWAITING_CONFIRMATION);
                $emailTemplateToEmployee = $emailTemplateFromEmployee;
                //
                $emailTemplateToEmployee['text'] = str_replace('{{to_employee_name}}', $requestRow['to_employee'], $emailTemplateToEmployee['text']);
                $emailTemplateToEmployee['text'] = str_replace('{{from_employee_name}}', $requestRow['from_employee'], $emailTemplateToEmployee['text']);
                //
                $emailTemplateBodyToEmployee = $this->shiftSwapEmailTemplate($emailTemplateToEmployee['text'], $requestRow, $requestRow['companyName'], $requestRow['to_employee']);
                //
                $from = $emailTemplateToEmployee['from_email'];
                $to = $requestRow['to_employee_email'];
                $subject = $emailTemplateToEmployee['subject'];
                $from_name = $emailTemplateToEmployee['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBodyToEmployee
                    . EMAIL_FOOTER;
                //
                if ($_SERVER['SERVER_NAME'] != 'localhost') {
                    sendMail($from, $to, $subject, $body, $from_name);
                }
                //
                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                );
                save_email_log_common($emailData);
            }
        }

        return SendResponse(200, [
            "msg" => "You have successfully sent shift request."
        ]);
    }

    //
    private function pageShiftHistory(string $pageSlug, int $shiftId): array
    {

        // check and generate error for session
        $session = checkAndGetSession();
        // load schedule model
        $this->load->model("v1/Shift_template_model", "shift_template_model");
        $this->load->model("v1/Shift_model", "shift_model");

        $data["shiftHistory"] = $this->shift_model->getShiftRequestsHistory($shiftId);

        return SendResponse(200, [
            "view" => $this->load->view("v1/settings/shifts/partials/shift_history", $data, true),
            "data" => $data["return"] ?? []
        ]);
    }    

}
