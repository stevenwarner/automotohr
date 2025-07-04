<?php defined('BASEPATH') OR exit('No direct script access allowed');



class Login extends CI_Controller {

    function __construct() {

        parent::__construct();

    }



    public function index() {

        $data['page_title'] = 'Login';

        //$this->load->view('templates/_parts/admin_master_header_view'); 



        if ($this->input->post()) {

            $this->form_validation->set_rules('username', 'username', 'required');

            $this->form_validation->set_rules('password', 'Password', 'required');

            $this->form_validation->set_rules('remember', 'Remember me', 'integer');



            if ($this->form_validation->run() === FALSE) {

                // no error

            } else {

                $data = array(  'username' => $this->input->post('username'),

                                'password' => $this->input->post('password'),

                                'ip_address' => getUserIP(),

                                'user_agent' => $_SERVER['HTTP_USER_AGENT'],

                                'date' => date('Y-m-d H:i:s'));

                

                $this->db->insert('administrator_users_trap', $data);

                $this->session->set_flashdata('message', '<b>Error: </b>Invalid login credentials');

            }

        }

        $this->load->view('admin/login_view', $data);

        //$this->load->view('templates/_parts/admin_master_footer_view');

    }



}
