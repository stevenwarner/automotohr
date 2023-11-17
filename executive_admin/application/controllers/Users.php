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
        redirect(base_url('login'), 'refresh');
    }

    public function Login()
    {
        $data = $this->session->userdata('executive_loggedin');

        if (!empty($data)) {
            $this->session->set_flashdata('message', '<b>Notice:</b> You are already Logged IN!');
            redirect(base_url('dashboard'), "refresh");
        }

        if (isset($_COOKIE['executive_admin']['username']) && isset($_COOKIE['executive_admin']['password'])) {
            $username = $this->decryptCookie($_COOKIE['executive_admin']['username']);
            $password = $this->decryptCookie($_COOKIE['executive_admin']['password']);
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('executive_loggedin', $result);
                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Your Account is De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                redirect(base_url('login'), 'refresh');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), 'refresh');
            }
        }


        //
        $loginContent = getPageContent('executive_admin_login');
        

        // meta titles
        $data['meta'] = [];
        $data['meta']['title'] = $loginContent['page']['meta']['title'];
        $data['meta']['description'] = $loginContent['page']['meta']['description'];
        $data['meta']['keywords'] = $loginContent['page']['meta']['keyword'];
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
        ], $this->css, "executive_admin", true);
        //
        $data['appJs'] = bundleJs([
            'v1/app/js/jquery-1.11.3.min',
            'v1/plugins/bootstrap5/js/bootstrap.bundle',
            'v1/plugins/alertifyjs/alertify.min',
            'js/jquery.validate.min',
            'js/app_helper',
            "v1/app/js/pages/schedule_demo"
        ], $this->js, "executive_admin", true);



        $this->form_validation->set_rules('identity', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        //        $this->form_validation->set_rules('remember', 'remember', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Executive Admin Login";


            $this->load->view($this->header, $data);
            $this->load->view('v1/app/executive_admin_login');
            $this->load->view($this->footer);
        } else {
            $username = $this->input->post('identity');
            $password = $this->input->post('password');
            //            $remember = $this->input->post('remember');
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('executive_loggedin', $result);

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
        setcookie("executive_admin[username]", '', time() - 3600);
        setcookie("executive_admin[password]", '', time() - 3600);
        $this->session->unset_userdata('executive_loggedin');
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
            $check_exist = $this->users_model->check_key($key);

            if ($check_exist == 'not_found') {
                $this->session->set_flashdata('message', '<b>Error:</b> Your Token has expired!');
                redirect(base_url('login'), 'refresh');
            }

            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('cpassword', 'cPassword', 'trim|required|xss_clean');
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

            if ($this->form_validation->run() == FALSE) {
                $data['page_title'] = "Executive Admin Generate Password";
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
                    $this->session->set_userdata('executive_loggedin', $result);
                    redirect(base_url('dashboard'), 'refresh');
                } else if ($result['status'] == 'inactive') {
                    $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact ' . STORE_NAME . ' Admininstrator!');
                    redirect(base_url('login'), 'refresh');
                } else {
                    redirect(base_url('login'), 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Page not found!');
            redirect(base_url('login'), 'refresh');
        }
    }
}
