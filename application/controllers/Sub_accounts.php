<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class sub_accounts extends Public_Controller
{

    public $common_functions;
    private $security_details;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        if ($this->session->userdata('logged_in')) {
            $this->load->model('subaccount_model');
        } else {
            redirect(base_url('login'), "refresh");
        }
        $session_details = $this->session->userdata('logged_in');
        $sid = $session_details['employer_detail']['sid'];
        $this->security_details = db_get_access_level_details($sid);
    }

    public function index()
    {
        $security_details = $this->security_details;
        $data['security_details'] = $security_details;
        $data['session'] = $this->session->userdata('logged_in');
        $company_id = $data["session"]["company_detail"]["sid"];


        $config = array(
            array(
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'trim|required|xss_clean|is_unique[users.username]|min_length[5]'
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|xss_clean|min_length[6]'
            ),
            array(
                'field' => 'first_name',
                'label' => 'First name',
                'rules' => 'required'
            ),
            array(
                'field' => 'last_name',
                'label' => 'Last name',
                'rules' => 'required'
            ),
            array(
                'field' => 'passconf',
                'label' => 'Confirm Password',
                'rules' => 'required|matches[password]|xss_clean|min_length[6]'
            ),
            array(
                'field' => 'email',
                'label' => 'Email',
                'rules' => 'required|valid_email|is_unique[users.email]'
            )
        );
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            //Rest of the page starts
            $data['title'] = "Add Sub Accounts";

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/subaccount');
            $this->load->view('main/footer');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            //Arranging company detial
            foreach ($formpost as $key => $value) {
                if ($key != 'passconf' && $key != 'password') { // exclude these values from array
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $subaccount_data[$key] = $value;
                }
            }

            $password = $formpost['password'];
            $password = do_hash($password, 'md5');
            $subaccount_data['password'] = $password;
            $subaccount_data['active'] = 1;
            $subaccount_data['registration_date'] = date('Y-m-d H:i:s');
            $subaccount_data['parent_sid'] = $company_id;

            $this->subaccount_model->add_subaccount($subaccount_data);
            $this->session->set_flashdata('message', '<b>Success:</b> Sub account added successfully');

            //sending email to user
            $from = FROM_EMAIL_DEV;
            $to = $subaccount_data["email"];
            $subject = "Welcome to " . ucwords(STORE_DOMAIN);


            $body = EMAIL_HEADER
                . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $formpost['first_name'] . ' ' . $formpost['last_name'] . ',</h2>'
                . '<br>You have be Registered on ' . STORE_FULL_URL
                . '<br><br>Use the following access details to <a href="' . STORE_FULL_URL . 'login">sign</a> in to the site:'
                . '<br><b>Username:</b>'
                . $formpost['username']
                . '<br><b>Password:</b>'
                . $formpost['password']
                . '<br><br><p style="margin:2px 0;">We are glad you have chosen to be a part of ' . ucwords(STORE_DOMAIN) . '. Please visit us often. ' . ucwords(STORE_DOMAIN) . ' is a dynamic environment, with many changes and updates happening every day.</p>'
                . '<p>We are here to help you Succeed!!</p><p>Please Email or Call me any time with questions or comments.</p> <p>We would love to hear from you.</p> <p>Thank You</p>'
                . EMAIL_FOOTER;


            sendMail($from, $to, $subject, $body, STORE_NAME);


            redirect("dashboard", "location");
        }
    }

}
