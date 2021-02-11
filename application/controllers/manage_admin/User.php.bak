<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
    }

    public function login()
    {
        if ($this->ion_auth->logged_in()) {

            $current_user = $this->ion_auth->user()->row();

            if ($current_user->active == 1) {
                redirect('manage_admin', 'refresh');
            }
        }
        $this->data['page_title'] = 'Login';
        if ($this->input->post()) {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('identity', 'Identity', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            $this->form_validation->set_rules('remember', 'Remember me', 'integer');
            $remember = (bool)$this->input->post('remember');

            if ($this->form_validation->run() === TRUE) {
                /*if (isset($_COOKIE['admin'] ['username']) && isset($_COOKIE['admin'] ['password'])) {
                    $username = $this->decryptCookie($_COOKIE['admin'] ['username']);
                    $password = $this->decryptCookie($_COOKIE['admin'] ['password']);

                    if ($this->ion_auth->login($username, $password, $remember)) {
                        redirect('manage_admin', 'refresh');
                    } else {
                        $this->session->set_flashdata('message', $this->ion_auth->errors());
                        redirect('manage_admin/user/login', 'refresh');
                    }
                }*/

                if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                    if ($remember) {
                        setcookie("admin[username]", $this->encryptCookie($this->input->post('identity')), time() + 3600);
                        setcookie("admin[password]", $this->encryptCookie($this->input->post('password')), time() + 3600);
                    }
                    redirect('manage_admin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('manage_admin/user/login', 'refresh');
                }
            }
        }

        $this->load->helper('form');
        $this->render('manage_admin/login_view', 'admin_master');
    }

    public function logout()
    {
        setcookie("admin[username]", time() - 3600);
        setcookie("admin[password]", time() - 3600);
        $this->ion_auth->logout();
        redirect('manage_admin/user/login', 'refresh');
    }

    function encryptCookie($value)
    {
        if (!$value) {
            return false;
        }
        $key = 'roltyFoamisTheDI';
        $text = $value;
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
        return trim(base64_encode($crypttext)); //encode for cookie
    }

    function decryptCookie($value)
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
}