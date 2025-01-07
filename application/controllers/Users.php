<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
        require_once(APPPATH . 'libraries/xmlapi.php');

        //
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        //
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        $this->disableMinifiedFiles = true;
    }

    public function login()
    {
        //
        // $this->output->cache(WEB_PAGE_CACHE_TIME_IN_MINUTES);

        $data['session'] = $this->session->userdata('logged_in');
        if ($data['session']) {
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }
        $data['limited_menu'] = true;

        //
        $loginContent = getPageContent('login');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $loginContent['page']['meta']['title'];
        $data['meta']['description'] = $loginContent['page']['meta']['description'];
        $data['meta']['keywords'] = $loginContent['page']['meta']['keywords'];
        //
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'login', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min', // jquery
            'v1/plugins/bootstrap5/js/bootstrap.bundle', // bootstrap 5
            "v1/app/js/pages/home"
        ], $this->js, 'login', $this->disableMinifiedFiles);


        // if (isset($_COOKIE[STORE_NAME]['username']) && isset($_COOKIE[STORE_NAME]['password'])) {
        //     $username = $this->decryptCookie($_COOKIE[STORE_NAME]['username']);
        //     $password = $this->decryptCookie($_COOKIE[STORE_NAME]['password']);
        //     $result = $this->users_model->login($username, $password);

        //     if ($result) {
        //         // Check for timezone
        //         // Added on: 26-05-2019
        //         if ($result['employer']['timezone'] == '' || $result['employer']['timezone'] == NULL || !preg_match('/^[A-Z]/', $result['employer']['timezone'])) {
        //             if ($result['company']['timezone'] != '' && preg_match('/^[A-Z]/', $result['company']['timezone'])) $result['employer']['timezone'] = $result['company']['timezone'];
        //             else $result['employer']['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
        //         }
        //         $sess_array = array(
        //             'company_detail' => $result["company"],
        //             'employer_detail' => $result["employer"],
        //             'cart' => $result["cart"],
        //             'portal_detail' => $result["portal"],
        //             'clocked_status' => $result["clocked_status"],
        //             'is_super' => 0
        //         );

        //         //Set Default Timezone
        //         // $company_timezone = $result['portal']['company_timezone'];
        //         // date_default_timezone_set($company_timezone);


        //         $this->session->set_userdata('logged_in', $sess_array);
        //         $this->session->set_userdata('accurate_background', array());
        //         redirect("dashboard", "location");
        //     }
        // }

        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean|callback_check_database'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {

            $data['title'] = "Login";
            $data['loginContent'] = $loginContent;

            $this->load->view($this->header, $data);
            $this->load->view('v1/app/users/login');

            $snapshot = $this->session->userdata('snapshot');
        } else {

            $this->users_model->checkTerminated($data['session']['employer_detail']['parent_sid']); //Check if any terminated person is due
            $snapshot = $this->session->userdata('snapshot');

            if (!empty($snapshot)) {
                $redirect_url = $snapshot['snapshot']['url'];

                if ($redirect_url != 'login' && !preg_match('/(get_notifications)/', $redirect_url)) {
                    redirect($redirect_url, "location");
                } else {
                    redirect("dashboard", "location");
                }
            } else {
                redirect("dashboard", "location");
            }
        }
    }

    function check_database($password)
    { //Field validation succeeded.  Validate against database
        $username = $this->input->post('username');
        //        $keep = $this->input->post('keep');
        $this->load->config('config');
        $result = $this->users_model->login($username, $password);

        //        if ($keep == 'on') {
        //            setcookie(STORE_NAME . "[username]", encryptCookie($username), time() + 3600);
        //            setcookie(STORE_NAME . "[password]", encryptCookie($password), time() + 3600);
        //        }

        if ($result) {
            // Check for timezone
            // Added on: 26-05-2019
            if ($result['employer']['timezone'] == '' || $result['employer']['timezone'] == NULL || !preg_match('/^[A-Z]/', $result['employer']['timezone'])) {
                if ($result['company']['timezone'] != '' && preg_match('/^[A-Z]/', $result['company']['timezone'])) $result['employer']['timezone'] = $result['company']['timezone'];
                else $result['employer']['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
            }

            $sess_array = array(
                'company_detail' => $result["company"],
                'employer_detail' => $result["employer"],
                'cart' => $result["cart"],
                'portal_detail' => $result["portal"],
                'clocked_status' => $result["clocked_status"],
                'is_super' => 0
            );

            // track manual Login
            $activity_data = array();
            $activity_data['company_sid'] = $result["company"]['sid'];
            $activity_data['employer_sid'] = $result["employer"]['sid'];
            $activity_data['company_name'] = $result["company"]['CompanyName'];
            $activity_data['employer_name'] = $result["employer"]['first_name'] . ' ' . $result["employer"]['last_name'];
            $activity_data['employer_access_level'] = $result["employer"]['access_level'];
            $activity_data['module'] = 'users';
            $activity_data['action_performed'] = 'login';
            $activity_data['action_year'] = date('Y');
            $activity_data['action_week'] = date('W');
            $activity_data['action_timestamp'] = date('Y-m-d H:i:s');
            $activity_data['action_status'] = '';
            $activity_data['action_url'] = current_url();
            $activity_data['employer_ip'] = getUserIP();
            $activity_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $this->db->insert('logged_in_activitiy_tracker', $activity_data);
            $this->session->set_userdata('logged_in', $sess_array);
            return TRUE;
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
            $this->form_validation->set_message('check_database', 'Your Username and/or Password is not correct!');
            return false;
        }
    }

    function decryptCookie($value)
    {
        if (!$value) {
            return false;
        }

        $key = 'roltyFoamisTheDI';
        $ciphertext = $value;
        $version = phpversion();

        if ($version < 6) {
            $c = base64_decode($ciphertext);
            $ivlen = openssl_cipher_iv_length($cipher = "aes-128-cbc");
            //            $iv = substr($c, 0, $ivlen);
            $iv = hex2bin('34857d973953e44afb49ea9d61104d8c');
            $hmac = substr($c, $ivlen, $sha2len = 32);
            $ciphertext_raw = substr($c, $ivlen + $sha2len);
            $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
            $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);

            if (@hash_equals($hmac, $calcmac)) {
                return trim($original_plaintext);
            }
        } else {
            $cipher = "aes-128-cbc";

            if (in_array($cipher, openssl_get_cipher_methods())) {
                $ivlen = openssl_cipher_iv_length($cipher);
                $iv = openssl_random_pseudo_bytes($ivlen);
                $original_plaintext = openssl_decrypt($ciphertext, $cipher, $key, $options = 0, $iv, $tag);
                return trim($original_plaintext);
            }
        }
    }

    function check_username($username)
    {
        $result = $this->users_model->check_user($username);

        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_username', 'Username already exist.');
            return false;
        }
    }

    function check_email($email)
    {
        $result = $this->users_model->check_email($email);

        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_email', 'Email already in use.');
            return false;
        }
    }

    function logout()
    {
        $login_check = $this->session->userdata('logged_in');

        if ($login_check) { // User is already Logged IN // track User's activity Log
            $activity_data = array();
            $activity_data['company_sid'] = $login_check['company_detail']['sid'];
            $activity_data['employer_sid'] = $login_check['employer_detail']['sid'];
            $activity_data['company_name'] = $login_check['company_detail']['CompanyName'];
            $activity_data['employer_name'] = $login_check['employer_detail']['first_name'] . ' ' . $login_check['employer_detail']['last_name'];
            $activity_data['employer_access_level'] = $login_check['employer_detail']['access_level'];
            $activity_data['module'] = 'users';
            $activity_data['action_performed'] = 'logoff';
            $activity_data['action_year'] = date('Y');
            $activity_data['action_week'] = date('W');
            $activity_data['action_timestamp'] = date('Y-m-d H:i:s');
            $activity_data['action_status'] = '';
            $activity_data['action_url'] = current_url();
            $activity_data['employer_ip'] = getUserIP();
            $activity_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

            if (isset($_SESSION['logged_in']['is_super']) && $_SESSION['logged_in']['is_super'] == 1) {
                $this->db->insert('logged_in_activitiy_tracker_super', $activity_data);
            } else {
                $this->db->insert('logged_in_activitiy_tracker', $activity_data);
            }
        }
        setcookie(STORE_NAME . "[username]", time() - 3600);
        setcookie(STORE_NAME . "[password]", time() - 3600);
        $this->session->unset_userdata('logged_in');
        $this->session->unset_userdata('coupon_data');
        session_destroy();
        redirect(base_url('login'), "refresh");
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

    public function validate_youtube($str)
    {
        if ($str != "") {
            preg_match("#(?<=v=)[a-zA-Z0-9-]+(?=&)|(?<=v\/)[^&\n]+|(?<=v=)[^&\n]+|(?<=youtu.be/)[^&\n]+#", $str, $matches);
            if (!isset($matches[0])) { //if validation not passed
                $this->form_validation->set_message('validate_youtube', 'Invalid <b>youtube link</b>.');
                return FALSE;
            } else { //if validation passed
                return TRUE;
            }
        } else {
            return true;
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

        $forgotPasswordContent = getPageContent('forgot_password');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $forgotPasswordContent['page']['meta']['title'];
        $data['meta']['description'] = $forgotPasswordContent['page']['meta']['description'];
        $data['meta']['keywords'] = $forgotPasswordContent['page']['meta']['keywords'];
        $data['limited_menu'] = true;
        //
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'forgot', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min', // jquery
            'v1/plugins/bootstrap5/js/bootstrap.bundle', // bootstrap 5
            'js/jquery.validate.min', // validator
            "v1/app/js/pages/home",
            "v1/app/js/pages/forgot_password",
        ], $this->js, 'forgot', $this->disableMinifiedFiles);


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Forgot Password";
            $data['forgotPasswordContent'] = $forgotPasswordContent;


            $this->load->view($this->header, $data);
            $this->load->view('v1/app/users/forgot_password');
        } else {
            $email = $this->input->post('email');
            $result = $this->users_model->check_email($email);

            if ($result) {
                $this->session->set_flashdata('message', 'Sorry! No E-Mail record found!');
            } else {
                $random_string = generateRandomString(12);
                $this->users_model->varification_key($email, $random_string);
                $user_data = $this->users_model->email_user_data($email);
                //
                if (!$user_data) {
                    $this->session->set_flashdata('message', 'The provided email does not exist, or the account is inactive.');
                    return redirect('/forgot_password', 'refresh');
                }
                $this->session->set_flashdata('message', 'Check Your Email and follow link to Reset Your password.');
                //sending email to user
                $emailTemplateData = get_email_template(PASSWORD_RECOVERY);
                //
                $emailTemplateBody = convert_email_template($emailTemplateData['text'], $user_data);
                $from = $emailTemplateData['from_email'];
                $to = $email;
                $subject = $emailTemplateData['subject'];
                $from_name = $emailTemplateData['from_name'];
                $body = EMAIL_HEADER
                    . $emailTemplateBody
                    . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);
                //saving email to logs
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $body,
                    'username' => $user_data['sid'],
                );
                $this->users_model->save_email_logs($emailData);
            }
            redirect('/forgot-password', 'refresh');
        }
    }

    public function contact_us()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        $data['title'] = "Contact Us";

        $contactUsContent = getPageContent('contact_us');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $contactUsContent['page']['meta']['title'];
        $data['meta']['description'] = $contactUsContent['page']['meta']['description'];
        $data['meta']['keywords'] = $contactUsContent['page']['meta']['keywords'];
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
        ], $this->css);
        //
        $data['appJs'] = bundleJs([
            'plugins/bootstrap5/js/bootstrap.bundle',
            'alertifyjs/alertify.min'
        ], $this->js);


        $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean|valid_email');
        $this->form_validation->set_rules('message', 'Message', 'required|trim|xss_clean|min_length[50]|strip_tags');
        $this->form_validation->set_rules('g-recaptcha-response', 'Captcha', 'required|callback_recaptcha[' . $this->input->post('g-recaptcha-response') . ']');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {

            $data['contactUsContent'] = $contactUsContent;

            $this->load->view($this->header, $data);
            $this->load->view('v1/app/users/contact_us');
            $this->load->view($this->footer);
        } else {
            $contact_name = $this->input->post('name');
            $contact_email = $this->input->post('email');
            $contact_message = strip_tags($this->input->post('message'));
            $is_blocked_email = checkForBlockedEmail($contact_email);


            //
            if (preg_match('/.ru$/', $contact_email)) {
                $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your enquiry. We will get back to you!');

                redirect(base_url('contact_us'), "refresh");
            }

            if ($is_blocked_email == 'not-blocked') {
                $from = FROM_EMAIL_NOTIFICATIONS;
                $subject = "Contact Us enquiry - " . STORE_NAME;
                $fromName = $contact_name;
                $replyTo = $contact_email;

                $body = EMAIL_HEADER
                    . '<h2 style="width:100%; margin:0 0 20px 0;">New Contact Us Enquiry!</h2>'
                    . '<br><b>Sender Name: </b>' . $fromName
                    . '<br><b>Sender Email: </b>' . $contact_email
                    . '<p><b>Message: </b>' . $contact_message . '</p>'
                    . EMAIL_FOOTER;

                $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        if ($system_notification_email['email'] == 'steven@automotohr.com') {
                            continue;
                        }
                        sendMail($from, $system_notification_email['email'], $subject, $body, $fromName, $replyTo);
                    }
                }
            }

            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your enquiry. We will get back to you!');
            redirect(base_url('contact_us'), "refresh");
        }
    }

    function change_password($user = NULL, $key = NULL)
    {
        $data['verification'] = $this->users_model->varification_user_key($user, $key);
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

        $passwordRecoveryContent = getPageContent('password_recovery');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $passwordRecoveryContent['page']['meta']['title'];
        $data['meta']['description'] = $passwordRecoveryContent['page']['meta']['description'];
        $data['meta']['keywords'] = $passwordRecoveryContent['page']['meta']['keywords'];

        $data['limited_menu'] = true;
        //
        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'forgot', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min', // jquery
            'v1/plugins/bootstrap5/js/bootstrap.bundle', // bootstrap 5
            'js/jquery.validate.min', // validator
            "v1/app/js/pages/home",
            "v1/app/js/pages/forgot_password_recovery",
        ], $this->js, 'forgot', $this->disableMinifiedFiles);


        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $retrn = $this->users_model->varification_user_key($user, $key);
            $data['title'] = "Change Password";
            $data['passwordRecoveryContent'] = $passwordRecoveryContent;

            //
            $this->load->view($this->header, $data);
            $this->load->view('v1/app/users/password_recovery');
        } else {
            $password = $this->input->post('password');
            $re_password = $this->input->post('retypepassword');

            if ($password == $re_password) {
                $this->users_model->change_password($password, $user, $key);
                $this->users_model->reset_key($user);
                $user_data = $this->users_model->username_user_data($user);
                $emailTemplateData = get_email_template(PASSWORD_CHANGE);
                //
                $emailTemplateBody = convert_email_template($emailTemplateData['text'], $user_data);
                $from = $emailTemplateData['from_email'];
                $to = $user_data['email'];
                $subject = $emailTemplateData['subject'];
                $from_name = $emailTemplateData['from_name'];

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
                $this->users_model->save_email_logs($emailData);
            }

            $this->session->set_flashdata('message', 'Your Password has been changed Successfully!');
            redirect("login", "refresh");
        }
    }

    public function employee_login()
    {
        $data['session'] = $this->session->userdata('logged_in');
        if ($data['session']) {
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        if (isset($_COOKIE[STORE_NAME]['username']) && isset($_COOKIE[STORE_NAME]['password'])) {
            $username = $this->decryptCookie($_COOKIE[STORE_NAME]['username']);
            $password = $this->decryptCookie($_COOKIE[STORE_NAME]['password']);
            $result = $this->users_model->login($username, $password);

            if ($result) {
                $sess_array = array(
                    'company_detail' => $result["company"],
                    'employer_detail' => $result["employer"],
                    'cart' => $result["cart"],
                    'portal_detail' => $result["portal"],
                    'clocked_status' => $result["clocked_status"],
                    'is_super' => 0
                );

                $this->session->set_userdata('logged_in', $sess_array);
                redirect("dashboard", "location");
            }
        }

        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|xss_clean'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'trim|required|xss_clean|callback_check_database'
            )
        );

        $this->form_validation->set_rules($config);
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Employee / Team Members Login";
            $this->load->view('main/header', $data);
            $this->load->view('users/employee_login');
            $this->load->view('main/footer');
        } else {
            redirect("dashboard", "location");
        }
    }

    function employer_login_independant()
    {
        $this->load->model('manage_admin/company_model');
        $this->load->model('dashboard_model'); //activate user
        $action = $this->input->post("action");
        $employer_id = $this->input->post("sid");

        if ($action == 'login') {
            if ($this->input->post("system") != null) {
                $dataToUpdate = array('verification_key' => NULL); //removing verification key from user document 
                $this->company_model->updateUserDocument($employer_id, $dataToUpdate);
                $updatedData = array('active' => 1);
                $this->dashboard_model->update_user($employer_id, $updatedData);
            }

            $result = $this->dashboard_model->update_session_details(0, $employer_id);
            $empData = $result["employer"];
            $companyData = $result["company"];
            $portalData = $result["portal"];

            if ($empData) {
                $sess_array = array();
                $data['cart'] = db_get_cart_content($empData["parent_sid"]);

                $sess_array = array(
                    'company_detail' => $companyData,
                    'employer_detail' => $empData,
                    'cart' => $data["cart"],
                    'portal_detail' => $portalData,
                    'clocked_status' => $result["clocked_status"]
                );

                $this->session->set_userdata('logged_in', $sess_array);
                return 'true';
            } else
                return 'false';
        }
    }

    function check_clocked_status()
    {
        if ($this->session->userdata('logged_in')) {
            $info = $this->session->userdata('logged_in');
            $company_sid = $info["company_detail"]["sid"];
            $employer_sid = $info["employer_detail"]["sid"];
            $clocked_status = $this->users_model->check_clocked_in_activity($company_sid, $employer_sid);
            echo $this->db->last_query();
            echo "<br><pre>";
            print_r($clocked_status);
            exit;
        }
    }

    public function generate_password($key)
    {
        $data['session'] = $this->session->userdata('logged_in');

        if ($data['session']) {
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        if (isset($_COOKIE[STORE_NAME]['username']) && isset($_COOKIE[STORE_NAME]['password'])) {
            $username = $this->decryptCookie($_COOKIE[STORE_NAME]['username']);
            $password = $this->decryptCookie($_COOKIE[STORE_NAME]['password']);
            $result = $this->users_model->login($username, $password);

            if ($result) {
                $sess_array = array(
                    'company_detail' => $result["company"],
                    'employer_detail' => $result["employer"],
                    'cart' => $result["cart"],
                    'portal_detail' => $result["portal"],
                    'clocked_status' => $result["clocked_status"],
                    'is_super' => 0
                );

                //Set Default Timezone
                $company_timezone = $result['portal']['company_timezone'];
                date_default_timezone_set($company_timezone);

                $this->session->set_userdata('logged_in', $sess_array);
                $this->session->set_userdata('accurate_background', array());
                redirect("dashboard", "location");
            }
        }

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $check_exist = $this->users_model->check_key($key);

            if (!$check_exist) {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Request!');
                redirect(base_url('login'), "refresh");
            }

            $data['page_title'] = "Executive Admin Change Password";
            $this->load->view('main/header', $data);
            $this->load->view('users/generate-password');
            $this->load->view('main/footer');
        } else {
            $password = $this->input->post('password');
            $this->users_model->updatePass($password, $key);
            $this->session->set_flashdata('message', 'You have changed your password successfully!');
            redirect(base_url('login'), "refresh");
        }
    }


    public function why_us()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        $data['title'] = "Why Us";

        $whyUsContent = getPageContent('why_us');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $whyUsContent['page']['meta']['title'];
        $data['meta']['description'] = $whyUsContent['page']['meta']['description'];
        $data['meta']['keywords'] = $whyUsContent['page']['meta']['keywords'];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/css/why_us',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/main',
            'v1/app/css/app',
        ], $this->css);
        //
        $data['appJs'] = bundleJs([
            'plugins/bootstrap5/js/bootstrap.bundle',
            'alertifyjs/alertify.min'
        ], $this->js);

        $data['whyUsContent'] = $whyUsContent;
        $this->load->view($this->header, $data);
        $this->load->view('v1/app/why_us');
        $this->load->view($this->footer);
    }
}
