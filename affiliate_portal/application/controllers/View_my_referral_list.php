<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class View_my_referral_list extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('view_my_referral_list_model');
    }

	public function index()
	{ 
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'View My Referral List';

    		$login_user_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $login_user_data['affiliate_users']['full_name'];
            $user_id = $login_user_data['affiliate_users']['sid'];

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

	public function edit(){
        $view_data = array();
        $view_data['title'] = 'Refer Potential Clients: Edit';

        $this->load->view('main/header');
        $this->load->view('view_my_referral_list/edit', $view_data);
        $this->load->view('main/footer');
    }
}
