<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function register() {
      redirect(base_url('login'), 'refresh');
    }

    public function Login() {    
        $data = $this->session->userdata('executive_loggedin');
        
        if(!empty($data)) {
            $this->session->set_flashdata('message', '<b>Notice:</b> You are already Logged IN!');
            redirect(base_url('dashboard'), "refresh");
        }

        if(isset($_COOKIE['executive_admin']['username']) && isset($_COOKIE['executive_admin']['password'])) {
            $username = $this->decryptCookie($_COOKIE['executive_admin']['username']);
            $password = $this->decryptCookie($_COOKIE['executive_admin']['password']);
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('executive_loggedin', $result);
                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                $this->session->set_flashdata('message', '<b>Error:</b> Your Account is De-activated, Please contact '.STORE_NAME.' Admininstrator!');
                redirect(base_url('login'), 'refresh');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), 'refresh');
            }
        }

        $this->form_validation->set_rules('identity', 'Username', 'trim|required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('remember', 'remember', 'trim|xss_clean');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i> ', '</p>');

        if ($this->form_validation->run() == FALSE) {
            $data['page_title'] = "Executive Admin Login";
            $this->load->view('main/header', $data);
            $this->load->view('users/login');
            $this->load->view('main/footer');
        } else {
            $username = $this->input->post('identity');
            $password = $this->input->post('password');
//            $remember = $this->input->post('remember');
            $result = $this->users_model->login($username, $password);

            if ($result['status'] == 'active') {
                $this->session->set_userdata('executive_loggedin', $result);

//                if ($remember == 'on') {
//                    setcookie("executive_admin[username]", $this->encryptCookie($username), time() + 3600);
//                    setcookie("executive_admin[password]", $this->encryptCookie($password), time() + 3600);
//                }

                redirect(base_url('dashboard'), "refresh");
            } else if ($result['status'] == 'inactive') {
                authfaillog('Executive_admin',$this->input->post('identity'),$this->input->post('password'));
                $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact '.STORE_NAME.' Admininstrator!');
                redirect(base_url('login'), "refresh");
            } else {
                authfaillog('Executive_admin',$this->input->post('identity'),$this->input->post('password'));
                $this->session->set_flashdata('message', '<b>Error:</b> Invalid Login Credentials!');
                redirect(base_url('login'), "refresh");
            }
        }
    }

    function encryptCookie($value) {
        if (!$value) {
            return false;
        }
        
        $key = 'rolTyF0amisTheCI';
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
        return trim(base64_encode($crypttext)); //encode for cookie
    }

    function decryptCookie($value) {
        if (!$value) {
            return false;
        }
        
        $key = 'rolTyF0amisTheCI';
        $crypttext = base64_decode($value); //decode cookie
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
        return trim($decrypttext);
    }

    function check_username($username) {
        $result = $this->users_model->check_user($username);
        
        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_username', 'Username already exist.');
            return false;
        }
    }

    function check_email($email) {
        $result = $this->users_model->check_email($email);
        
        if ($result) {
            return TRUE;
        } else {
            $this->form_validation->set_message('check_email', 'Email already in use.');
            return false;
        }
    }

    function logout() {
        setcookie("executive_admin[username]", '', time() - 3600);
        setcookie("executive_admin[password]", '', time() - 3600);
        $this->session->unset_userdata('executive_loggedin');
        redirect(base_url('login'), "refresh");
    }

    public function recaptcha($str) {
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

    public function generate_password($key = NULL) {
        if($key != NULL) {
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
                    $this->session->set_flashdata('message', '<b>Error:</b> Account De-activated, Please contact '.STORE_NAME.' Admininstrator!');
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