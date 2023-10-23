<?php defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        //
        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        //
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
    }

    public function register()
    {
        // Not Applicable  
    }

    public function Login()
    {
        $data = $this->session->userdata('affiliate_loggedin');

        if (!empty($data)) {
            $this->session->set_flashdata('message', '<b>Notice:</b> You are already Logged IN!');
            redirect(base_url('dashboard'), "refresh");
        }

        if (isset($_COOKIE['affiliate_admin']['username']) && isset($_COOKIE['affiliate_admin']['password'])) {
            $username = $this->decryptCookie($_COOKIE['affiliate_admin']['username']);
            $password = $this->decryptCookie($_COOKIE['affiliate_admin']['password']);
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('affiliate_loggedin', $result);
                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Your Account is De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                redirect(base_url('login'), "refresh");
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), "refresh");
            }
        }


        //
        $loginContent = getPageContent('affiliate_login');

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $loginContent['page']['meta']['title'];
        $data['meta']['description'] = $loginContent['page']['meta']['description'];
        $data['meta']['keywords'] = $loginContent['page']['meta']['keyword'];
        //
        $data['pageCSS'] = [
            'v1/app/plugins/bootstrap5/css/bootstrap.min',
            'v1/app/plugins/fontawesome/css/all',
            'v1/app/css/login',

        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/main',
            'v1/app/css/app',
        ], $this->css);
        //
        $data['appJs'] = bundleJs([
            'v1/app/js/jquery-1.11.3.min',
            'plugins/bootstrap5/js/bootstrap.bundle',
            'alertifyjs/alertify.min'
        ], $this->js);


        $this->form_validation->set_rules('identity', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        // $this->form_validation->set_rules('remember', 'remember', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');


        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Affiliate Login";

            $this->load->view($this->header, $data);
            $this->load->view('v1/app/affiliate_login');
            $this->load->view($this->footer);
        } else {
            $username = $this->input->post('identity');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('affiliate_loggedin', $result);

                if ($remember == 'on') {
                    setcookie("affiliate_admin[username]", encryptCookie($username), time() + 3600);
                    setcookie("affiliate_admin[password]", encryptCookie($password), time() + 3600);
                }

                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                redirect(base_url('login'), "refresh");
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), "refresh");
            }
        }
    }

    //
    public function LoginOld()
    {
        $data = $this->session->userdata('affiliate_loggedin');

        if (!empty($data)) {
            $this->session->set_flashdata('message', '<b>Notice:</b> You are already Logged IN!');
            redirect(base_url('dashboard'), "refresh");
        }

        if (isset($_COOKIE['affiliate_admin']['username']) && isset($_COOKIE['affiliate_admin']['password'])) {
            $username = $this->decryptCookie($_COOKIE['affiliate_admin']['username']);
            $password = $this->decryptCookie($_COOKIE['affiliate_admin']['password']);
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('affiliate_loggedin', $result);
                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Your Account is De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                redirect(base_url('login'), "refresh");
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), "refresh");
            }
        }

        $this->form_validation->set_rules('identity', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('remember', 'remember', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Affiliate Login";
            $this->load->view('main/header', $data);
            $this->load->view('users/login');
            $this->load->view('main/footer');
        } else {
            $username = $this->input->post('identity');
            $password = $this->input->post('password');
            $remember = $this->input->post('remember');
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('affiliate_loggedin', $result);

                if ($remember == 'on') {
                    setcookie("affiliate_admin[username]", encryptCookie($username), time() + 3600);
                    setcookie("affiliate_admin[password]", encryptCookie($password), time() + 3600);
                }

                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                redirect(base_url('login'), "refresh");
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), "refresh");
            }
        }
    }

    public function check_username($username)
    {
        $result = $this->users_model->check_user($username);

        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_username', 'Username already exist.');
            return false;
        }
    }

    public function check_email($email)
    {
        $result = $this->users_model->check_email($email);

        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_email', 'Email already in use.');
            return false;
        }
    }

    public function logout()
    {
        setcookie("affiliate_admin[username]", '', time() - 3600);
        setcookie("affiliate_admin[password]", '', time() - 3600);
        $this->session->unset_userdata('affiliate_loggedin');
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

    public function generate_password($key = NULL)
    {
        if ($key != NULL) {
            $check_exist = $this->users_model->verify_affiliate_key($key);

            if ($check_exist == 'not_found') {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Request!');
                redirect(base_url('login'), "refresh");
            }

            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

            if ($this->form_validation->run() == FALSE) {
                $data['page_title'] = "Affiliate Program - Generate Password";
                $data['username'] = $check_exist;
                $this->load->view('main/header', $data);
                $this->load->view('users/generate-password');
                $this->load->view('main/footer');
            } else {
                $password = $this->input->post('password');
                $this->users_model->updatePass($password, $key, $check_exist);
                $this->session->set_flashdata('message', 'You have successfully generated your password!');
                $result = $this->users_model->login($check_exist, $password);

                if ($result['status'] == 'active') {
                    $this->session->set_userdata('affiliate_loggedin', $result);
                    redirect(base_url('dashboard'), "refresh");
                } else if ($result['status'] == 'inactive') {
                    $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                    redirect(base_url('login'), "refresh");
                } else {
                    redirect(base_url('login'), "refresh");
                }
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_profile()
    {
        if ($this->session->userdata('affiliate_loggedin')) {
            $session = $this->session->userdata('affiliate_loggedin');
            $data['session'] = $session;
            $data['affiliate_user'] = $session;
            $affiliate_user_sid = $session['affiliate_users']['sid'];

            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|xss_clean');
            $this->form_validation->set_rules('phone_number', 'Phone Number', 'trim|xss_clean');
            $this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
            $this->form_validation->set_rules('email', 'E-Mail Address', 'trim|xss_clean|valid_email');
            $this->form_validation->set_rules('paypal_email', 'PayPal E-Mail Address', 'trim|xss_clean|valid_email');
            $this->form_validation->set_rules('company_name', 'Company Name', 'trim|xss_clean');
            $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|xss_clean');
            $this->form_validation->set_rules('mathod_of_promotion', 'Mathod Of Promotion', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $affiliate_user = $this->users_model->get_affiliate_user($affiliate_user_sid);
                $data['affiliate_user'] = $affiliate_user;
                $data['name'] = $affiliate_user['full_name'];
                $data['user_id'] = $affiliate_user_sid;
                $data['title'] = 'My Profile';
                $this->load->view('main/header', $data);
                $this->load->view('users/affiliate_user_profile');
                $this->load->view('main/footer');
            } else {
                $affiliate_record_sid = $this->input->post('referred_affiliate');
                $data_to_update = array();

                if (!empty($_FILES) && isset($_FILES['profile_picture']) && $_FILES['profile_picture']['size'] > 0) {
                    $is_profile_pic = $this->users_model->get_affiliate_user_profilr_picture($affiliate_user_sid);

                    if (!empty($is_profile_pic)) {
                        $profile_url = 'assets/uploaded_affiliate_profile_picture/' . $is_profile_pic['profile_picture'];
                        if (file_exists($profile_url)) {
                            unlink($profile_url);
                        }
                    }

                    $random = generateRandomString(5);
                    $target_file_name = basename($_FILES["profile_picture"]["name"]);
                    $file_name = strtolower($random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_affiliate_profile_picture/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                        $data_to_update['profile_picture'] = $file_name;
                    }
                }

                $data_to_update['full_name'] = $this->input->post('full_name');
                $data_to_update['email'] = $this->input->post('email');
                $data_to_update['contact_number'] = $this->input->post('phone_number');
                $data_to_update['address'] = $this->input->post('address');
                $data_to_update['paypal_email'] = $this->input->post('paypal_email');
                $data_to_update['company_name'] = $this->input->post('company_name');
                $data_to_update['zip_code'] = $this->input->post('zipcode');
                $data_to_update['method_of_promotion'] = $this->input->post('mathod_of_promotion');
                $data_to_update['list_of_emails'] = $this->input->post('list_of_emails');
                $data_to_update['notes'] = $this->input->post('notes');
                $this->users_model->update_Affiliate_user($affiliate_record_sid, $data_to_update);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Profile is updated successfully');
                redirect(base_url('view-profile'), 'location');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function login_password()
    {
        if ($this->session->userdata('affiliate_loggedin')) {
            $session = $this->session->userdata('affiliate_loggedin');
            $data['session'] = $session;
            $data['affiliate_user'] = $session;
            $affiliate_user_sid = $session['affiliate_users']['sid'];
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $this->form_validation->set_rules('username', 'User Name', 'required|trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $affiliate_user = $this->users_model->get_affiliate_user($affiliate_user_sid);
                $data['affiliate_user'] = $affiliate_user;
                $data['name'] = $affiliate_user['full_name'];
                $data['user_id'] = $affiliate_user_sid;
                $data['title'] = 'Login Credentials';
                $this->load->view('main/header', $data);
                $this->load->view('users/affiliate_user_login_password');
                $this->load->view('main/footer');
            } else {
                $sid = $this->input->post('user_sid');
                $password = $this->input->post('password');
                $username = $this->input->post('username');
                $already_assigned = $this->users_model->check_user_username_before_update_login_credential($sid, $username);

                if (sizeof($already_assigned) > 0) {
                    $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Provided Username!');
                    redirect(base_url('login-credentials'), 'location');
                }

                if (empty($password)) {
                    $data = array(
                        'username' => $username
                    );
                } else {
                    $data = array(
                        'username' => $username,
                        'password' => do_hash($this->input->post('password'), 'md5')
                    );
                }

                $this->users_model->update_user_login_credential($sid, $data);
                $this->session->set_flashdata('message', '<b>Success:</b> Your Login credentials updated successfully');
                redirect(base_url('login-credentials'), 'location');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
