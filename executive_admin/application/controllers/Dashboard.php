<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    private $resp = array();
    private $limit = 100;
    private $listSize = 5;

    public function __construct()
    {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('Users_model');

        $this->resp['Status'] = false;
        $this->resp['Response'] = 'Invalid Request!';

        //
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        //
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
    }

    public function index($keyword = null)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['page_title']                                                 = 'Dashboard';
            $data['exadminId'] = $data['executive_user']['sid'];
            $keyword = $keyword == null ? '' : urldecode($keyword);
            $executive_user_companies                                           = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], $keyword);
            $deleted_companies                                                  = array();
            $this->load->model('message_model');
            $this->load->model('e_signature_model');

            $e_signature_data = $this->e_signature_model->get_e_signature($data['exadminId']);

            if (!empty($e_signature_data)) {
                $data['applysignature'] = 1;
            } else {
                $data['applysignature'] = 0;
            }


            if (!empty($executive_user_companies)) { // check for deleted companies
                foreach ($executive_user_companies as $key => $value) {
                    $company_details                                             = $this->Users_model->get_company_details($value['company_sid']);

                    if (empty($company_details)) {
                        $deleted_companies[]                                     = $key;
                    } else {
                        $company_id = $company_details[0]['sid'];
                        $employer_id = $value['logged_in_sid'];
                        $employer_email = $this->Users_model->get_employee_email($employer_id);

                        $message_total = $this->message_model->get_employer_messages_total($employer_id, $employer_email, NULL, $company_id);
                        //                        echo $company_id.' = '.$message_total.'<br>';
                        $executive_user_companies[$key]['message_total']        = $message_total;

                        //
                        $executive_user_companies[$key]['incidentCount'] = $this->Users_model->assigned_incidents_count($employer_id, $company_id);
                    }
                }
            }
            //            exit;
            if (!empty($deleted_companies)) {
                foreach ($deleted_companies as $deleted_company) {
                    unset($executive_user_companies[$deleted_company]);
                }

                $executive_user_companies                                       = array_values($executive_user_companies);
            }

            $data['executive_user_companies']                                   = $executive_user_companies;

            $this->load->view('main/header', $data);
            $this->load->view('dashboard/dashboard_view');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function reports($company_sid)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');

            if ($company_sid == 0 || $company_sid == NULL) {
                $this->session->set_flashdata('message', 'Company not found');
                redirect(base_url('dashboard'), "refresh");
            }

            $exists = $this->Users_model->check_company($data['executive_user']['sid'], $company_sid);

            if ($exists) {
                $data['company_sid'] = $company_sid;
                $data['title'] = 'Reports';
                $data['page_title'] = 'Executive Admin Reports';
                $this->load->view('main/header', $data);
                $this->load->view('dashboard/reports');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', 'Company not found');
                redirect(base_url('dashboard'), "refresh");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function manage_admin_companies($company_id)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['executive_user_companies'] = $this->Users_model->get_executive_users_companies($data['executive_user']['sid']);

            if ($company_id == 0 || $company_id == NULL) {
                $this->session->set_flashdata('message', 'Company not found');
                redirect(base_url('dashboard'), "refresh");
            }

            $exists = $this->Users_model->check_company($data['executive_user']['sid'], $company_id);

            if ($exists) {
                $data['admin_invoices'] = $this->Users_model->get_admin_invoices($company_id);
                $data['marketplace_invoices'] = $this->Users_model->get_admin_marketplace_invoices($company_id);
                $employee_details = $this->Users_model->get_company_employees($company_id);

                // print_r($employee_details);
                //die();

                $company_details = $this->Users_model->get_company_details($company_id);
                if (empty($company_details)) {
                    $this->session->set_flashdata('message', 'Company not found');
                    redirect(base_url('dashboard'), "refresh");
                } else {
                    $company_details = $company_details[0];
                }

                $data['location_info'] = db_get_state_name($company_details['Location_State']);
                $data['career_website'] = STORE_PROTOCOL . db_get_sub_domain($company_id);
                $data['company'] = $company_details;
                $data['employees'] = $employee_details;

                $this->load->view('main/header', $data);
                $this->load->view('dashboard/manage_company');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', 'Company not found');
                redirect(base_url('dashboard'), "refresh");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function company_login()
    {
        $company_sid = $this->input->post("company_sid");
        $action = $this->input->post("action");
        $logged_in_sid = $this->input->post('logged_in_sid');
        //$action = 'login'; $company_sid = 57; $logged_in_sid = 58;
        if ($action == 'login') {
            $data['employer_detail'] = $this->Users_model->get_company_details($logged_in_sid); // logged in
            $data['company_detail'] = $this->Users_model->get_company_details($company_sid);
            $data['portal'] = $this->Users_model->get_portal_details($company_sid);
            $data['cart'] = db_get_cart_content($company_sid);
            $sess_array = array();
            $sess_array = array(
                'employer_detail' => $data['employer_detail'][0],
                'company_detail' => $data['company_detail'][0],
                'cart' => $data['cart'],
                'portal_detail' => $data['portal'],
                'is_executive_admin_login' => 1
            );
            //            echo '<pre>'; print_r($sess_array); exit;
            $this->session->set_userdata('logged_in', $sess_array);
            echo $sess_array['employer_detail']['active'];
        }
    }

    public function forgot_password()
    {
        $config = array(
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'trim|required|valid_email|xss_clean'
            )
        );

        //
        $passwordRecoveryContent = getPageContent('executive_admin_password_recovery', false);
        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $passwordRecoveryContent['page']['meta']['title'];
        $data['meta']['description'] = $passwordRecoveryContent['page']['meta']['description'];
        $data['meta']['keywords'] = $passwordRecoveryContent['page']['meta']['keyword'];
        $data['limited_menu'] = true;
        // js
        $data['pageJs'] = [
            "https://www.google.com/recaptcha/api.js"
        ];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/css/login',
        ];
        //
        $data['appCSS'] = bundleCSS([
            "v1/plugins/alertifyjs/css/alertify.min",
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, "executive_admin_forgot", true);
        //
        $data['appJs'] = bundleJs([
            'v1/app/js/jquery-1.11.3.min',
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'v1/plugins/alertifyjs/alertify.min',
            'js/jquery.validate.min',
            'js/app_helper',
            "v1/app/js/pages/schedule_demo"
        ], $this->js, "executive_admin_forgot", true);


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Forgot Password";

            $data['passwordRecoveryContent'] = $passwordRecoveryContent;

            $this->load->view($this->header, $data);
            $this->load->view('v1/app/exe_admin_password_recovery');
        } else {
            $email = $this->input->post('email');
            $result = $this->Users_model->check_email($email);

            if ($result) {
                $this->session->set_flashdata('message', 'Sorry! No E-Mail record found!');
            } else {
                $random_string = generateRandomString(12);
                $this->Users_model->varification_key($email, $random_string); // update activation code for current user record in table                
                $user_data = $this->Users_model->email_user_data($email);
                $this->session->set_flashdata('message', 'Check Your Email and follow link to Reset Your password.');
                $url = base_url() . 'dashboard/change_password/' . $user_data["username"] . '/' . $user_data["activation_code"];
                $emailTemplateBody = 'Dear ' . $user_data["username"] . ', <br>';
                $emailTemplateBody = $emailTemplateBody . 'You can change your password by following the link below : ' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Your username is : ' . $user_data["username"] . '</strong><br><br>';
                $emailTemplateBody = $emailTemplateBody . '<a href="' . $url . '" style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block">Change Your Password</a><br><br>';
                $from = FROM_EMAIL_NOTIFICATIONS; //$emailTemplateData['from_email'];
                $to = $email;
                $subject = 'Password Recovery'; //$emailTemplateData['subject'];
                $from_name = ucwords(STORE_DOMAIN); //$emailTemplateData['from_name'];

                $body = EMAIL_HEADER
                    . $emailTemplateBody
                    . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);

                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => 0
                );
                $this->Users_model->save_email_logs($emailData);
            }
            redirect('dashboard/forgot_password');
        }
    }

    function change_password($user = NULL, $key = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard', 'refresh');
        }

        $data['verification'] = $this->Users_model->varification_user_key($user, $key);

        if ($data['verification'] == false) {
            redirect('login');
        }

        $config = array(
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'md5|trim|required|xss_clean'
            ),
            array(
                'field' => 'retypepassword',
                'label' => 'Re Enter Passsword',
                'rules' => 'md5|trim|required|xss_clean'
            )
        );

        $passwordRecoveryContent = getPageContent('executive_admin_password_recovery', false);

        // meta titles
        $data['limited_menu'] = true;
        // js
        $data['pageJs'] = [
            "https://www.google.com/recaptcha/api.js"
        ];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/css/login',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, "executive_admin_forgot_password", true);
        //
        $data['appJs'] = bundleJs([
            'v1/app/js/jquery-1.11.3.min',
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'js/jquery.validate.min',
            "js/additional-methods.min",
        ], $this->js, "executive_admin_forgot_password_common", true);

        $data['appJs'] .= bundleJs([
            'v1/app/js/pages/executive_forgot_password',
        ], $this->js, "executive_admin_forgot_password", true);


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $retrn = $this->Users_model->varification_user_key($user, $key);
            $data['meta'] = $passwordRecoveryContent["page"]["meta"];
            $data['passwordRecoveryContent'] = $passwordRecoveryContent;

            $data["onlyJS"] = true;

            $this->load->view($this->header, $data);
            $this->load->view('v1/app/change_password');
            $this->load->view($this->footer);
        } else {
            $password = $this->input->post('password');
            $re_password = $this->input->post('retypepassword');

            if ($password == $re_password) {
                $this->Users_model->change_password($password, $user, $key);
                $this->Users_model->reset_key($user);
                $user_data = $this->Users_model->username_user_data($user);

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $user_data['email'];
                $subject = 'Password Changed Successfully';
                $from_name = ucwords(STORE_DOMAIN);

                $emailTemplateBody = 'Your Password has been successfully Updated.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please login using this : <a href="' . STORE_FULL_URL_SSL . '/login">Link</a><br><br>';
                $emailTemplateBody = $emailTemplateBody . 'We are glad you have chosen to be a part of ' . ucwords(STORE_DOMAIN) . '.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please visit us often.<br>';
                $emailTemplateBody = $emailTemplateBody . ucwords(STORE_DOMAIN) . ' is a dynamic environment, with many changes and updates happening every day.<br><br>';
                $emailTemplateBody = $emailTemplateBody . 'We are here to help you Succeed!!.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please Email or Call us any time with questions or comments.<br>';
                $emailTemplateBody = $emailTemplateBody . 'We would love to hear from you.<br>';
                $emailTemplateBody = $emailTemplateBody . 'Thank You<br>';

                $body = EMAIL_HEADER
                    . $emailTemplateBody
                    . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);

                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => $user_data['sid'],
                );
                $this->Users_model->save_email_logs($emailData);
            }
            $this->session->set_flashdata('message', 'Your Password has been changed Successfully!');
            redirect('login');
        }
    }

    function my_profile()
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['page_title'] = 'My Profile';
            $data['back_url'] = base_url('dashboard');
            $complynet_status = $data['executive_user']['complynet_status'];
            $data['complynet_status'] = $complynet_status;
            //$this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|callback_unique_email['.$this->input->post('id').']');
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
            $this->form_validation->set_rules('alternative_email', 'Alternative Email', 'trim|xss_clean|valid_email');
            $this->form_validation->set_rules('job_title ', 'Job Title', 'trim|xss_clean');
            $this->form_validation->set_rules('direct_business_number', 'Direct Business Number', 'trim|xss_clean');
            $this->form_validation->set_rules('cell_number', 'Cell Number', 'trim|xss_clean');
            $this->form_validation->set_rules('video', 'Video URL', 'trim|xss_clean');

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('dashboard/my_profile');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $profile_picture = $this->upload_file_to_aws('profile_picture', $data['executive_user']['sid'], 'profile_picture');

                if ($profile_picture != 'error') {
                    $pictures = $profile_picture;
                } else {
                    $pictures = NULL;
                }

                $executive_admin_data = array(
                    'first_name'             => $formpost['first_name'],
                    'last_name'              => $formpost['last_name'],
                    'email'                  => $formpost['email'],
                    'alternative_email'      => $formpost['alternative_email'],
                    'job_title'              => $formpost['job_title'],
                    'direct_business_number' => $formpost['direct_business_number'],
                    'cell_number'            => $formpost['cell_number'],
                    'gender'            => $formpost['gender'],
                    'video'                  => $formpost['video']
                );
                $executive_admin_data['ip_address'] = getUserIP();


                if (IS_PTO_ENABLED == 1) {
                    $executive_admin_data['user_shift_hours'] = $formpost['shift_hours'];
                    $executive_admin_data['user_shift_minutes'] = $formpost['shift_mins'];
                }

                if (IS_NOTIFICATION_ENABLED == 1) {
                    if (!sizeof($formpost['notified_by'])) $executive_admin_data['notified_by'] = 'email';
                    else $executive_admin_data['notified_by'] = implode(',', $formpost['notified_by']);
                }

                if ($pictures != NULL) {
                    $executive_admin_data['profile_picture'] = $pictures;
                } else {
                    $executive_admin_data['profile_picture'] = $data['executive_user']['profile_picture'];
                }

                if (IS_TIMEZONE_ACTIVE) {
                    // Added on: 8-07-2019
                    $new_timezone = $this->input->post('timezone', true);
                    if ($new_timezone != '') $executive_admin_data['timezone'] = $new_timezone;
                }
                if ($complynet_status) {
                    $executive_admin_data['complynet_dashboard_link'] = $formpost['complynet_link'];
                }

                $result = $this->Users_model->update_executive_admin_profile($data['executive_user']['sid'], $executive_admin_data);

                if ($result == 'success') {
                    $this->session->set_flashdata('message', 'Success: Profile updated successfully');
                } else {
                    $this->session->set_flashdata('message', 'Error: Could not update profile, Please try again!');
                }

                redirect(base_url('dashboard'), "refresh");
            }
        } else {
            $this->session->set_flashdata('message', 'Error: You are not logged in!');
            redirect(base_url('login'), "refresh");
        }
    }

    public function unique_email($str)
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

    function upload_file_to_aws($file_input_id, $company_sid, $document_name, $suffix = '', $bucket_name = AWS_S3_BUCKET_NAME)
    {
        require_once(APPPATH . 'libraries/aws/aws.php');

        if (isset($_FILES[$file_input_id]) && $_FILES[$file_input_id]['name'] != '') {
            $last_index_of_dot = strrpos($_FILES[$file_input_id]["name"], '.') + 1;
            $file_ext = substr($_FILES[$file_input_id]["name"], $last_index_of_dot, strlen($_FILES[$file_input_id]["name"]) - $last_index_of_dot);
            $file_name = trim($document_name . '-' . $suffix);
            $file_name = str_replace(" ", "_", $file_name);
            $file_name = strtolower($file_name);
            $prefix = str_pad($company_sid, 4, '0', STR_PAD_LEFT);
            $new_file_name = $prefix . '-' . $file_name . '-' . generateRandomString(3) . '.' . $file_ext;
            $aws = new AwsSdk();
            $aws->putToBucket($new_file_name, $_FILES[$file_input_id]["tmp_name"], $bucket_name);
            return $new_file_name;
        } else {
            return 'error';
        }
    }

    function login_credentials()
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['page_title'] = 'Update Password';
            $data['back_url'] = base_url('dashboard');


            $this->form_validation->set_rules('username', 'User Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');


            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('dashboard/login_password_new');
                $this->load->view('main/footer');
            } else {
                $form_post = $this->input->post(NULL, TRUE);
                $pass = md5($form_post['password']);
                $this->Users_model->change_login_cred($pass, $data['executive_user']['sid']);
                $this->session->set_flashdata('message', 'Success: Credentials updated successfully');
                redirect(base_url('dashboard'), "refresh");
            }
        } else {
            $this->session->set_flashdata('message', 'Error: You are not logged in!');
            redirect(base_url('login'), "refresh");
        }
    }


    /**
     * Search applicants and employees against a company
     *
     */
    function search($query = '', $page = 1)
    {
        error_reporting(E_ALL);
        ini_set('max_execution_time', 0);
        if (!$this->session->userdata('executive_loggedin')) $this->response();
        if (!$this->input->is_ajax_request()) $this->response();
        if ($query == '') $this->response();
        //
        $data = $this->session->userdata('executive_loggedin');
        //
        $executiveCompanyIds = $this->Users_model->getExecutiveCompanies($data['executive_user']['sid']);
        //
        if (!$executiveCompanyIds) {
            $this->resp['Response'] = 'You don\'t have any companies to manage.';
            $this->response();
        }
        //
        $query = str_replace('--', ' ', $query);
        // 
        $inset = 0;
        $offset = $this->limit;
        if ($page > 1) {
            $inset = ($page - 1) * $this->limit;
            $offset = $inset * $page;
        }
        //
        $usersCount = $this->Users_model->getSearchedUsersCount($data['executive_user']['sid'], $executiveCompanyIds, $query);
        //
        $users = $this->Users_model->getSearchedUsers(
            $data['executive_user']['sid'],
            $executiveCompanyIds,
            $query,
            $inset,
            $offset,
            $this->limit
        );
        //
        foreach ($users as $ukey => $user) {
            if (isset($user["is_executive_admin"]) && $user["is_executive_admin"] == 1) {
                $is_executive = $this->Users_model->checkExecutiveAdmin($user["user_email"]);
                //
                if ($is_executive == 'no') {
                    unset($users[$ukey]);
                }
            } 
        }
        //
        if (!sizeof($users)) {
            $this->resp['Response'] = 'No records found.';
            $this->response();
        }

        foreach ($users as $index => $value) {
            $users[$index]['newstatus'] = GetEmployeeStatus($value['last_status_text'], $value['is_active']);
        }

        //
        $this->resp['Status'] = true;
        $this->resp['Response'] = 'Proceed';
        $this->resp['Limit']  = $this->limit;
        $this->resp['ListSize'] = $this->listSize;
        $this->resp['TotalRecords'] = $usersCount;
        $this->resp['TotalPages'] = ceil($this->resp['TotalRecords'] / $this->limit);
        $this->resp['Data'] = $users;
        $this->response();
    }

    /**
     * Send JSON back to client
     */
    private function response()
    {
        header('Content-Type: application/json');
        echo json_encode($this->resp);
        exit(0);
    }
}
