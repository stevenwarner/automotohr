<?php defined('BASEPATH') OR exit('No direct script access allowed');

class View_referral_clients extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('view_my_referral_list_model');
    }

    public function index() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Referred Client Management';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $user_id = $affiliate_data['affiliate_users']['sid'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            $referred = $this->view_my_referral_list_model->get_all_referel($user_id);
            $data['referred'] = $referred;
            $data['count'] = count($referred);

            $this->load->view('main/header', $data);
            $this->load->view('view_my_referral_list/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit() {
        $view_data = array();
        $view_data['title'] = 'Edit Potential Clients';
        $affiliate_data = $this->session->userdata('affiliate_loggedin');
        $data['session'] = $affiliate_data;
        $affiliate_detail                                                    = $data['session']['affiliate_users'];
        $security_sid                                                       = $affiliate_detail['sid'];
        $security_details                                                   = db_get_access_level_details($security_sid);
        $data['security_details']                                           = $security_details;
        $this->load->view('main/header');
        $this->load->view('view_my_referral_list/edit', $view_data);
        $this->load->view('main/footer');
    }

}