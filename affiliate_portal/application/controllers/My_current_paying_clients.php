<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My_current_paying_clients extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('my_current_paying_clients_model');
    }

    public function index() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Affiliate Paying Clients';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $user_id = $affiliate_data['affiliate_users']['sid'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $paying_clients = $this->my_current_paying_clients_model->get_all_paying_clients($user_id);
            $data['paying_clients'] = $paying_clients;
            $data['primary_count'] = count($paying_clients);
            $data['secondary_agency'] = array();
            $data['secondary_count'] = 0;
            $secondary_agency_id = $this->my_current_paying_clients_model->get_secondary_agency($user_id);
            
            if (sizeof($secondary_agency_id) > 0) {
                $secondary_agency = $this->my_current_paying_clients_model->get_all_paying_clients($secondary_agency_id[0]['sid'], 1);
                $data['secondary_agency'] = $secondary_agency;
                $data['secondary_count'] = count($secondary_agency);
            }

            $this->load->view('main/header', $data);
            $this->load->view('my_current_paying_clients/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function edit() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Edit Paying Client Details';
            $name = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $name['affiliate_users']['full_name'];
            $this->load->view('main/header', $data);
            $this->load->view('my_current_paying_clients/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }
}