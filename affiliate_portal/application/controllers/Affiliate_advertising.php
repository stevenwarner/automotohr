<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Affiliate_advertising extends CI_Controller {

	public function index()
	{ 
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Affiliate Advertising';
            $name = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $name['affiliate_users']['full_name'];

    		$this->load->view('main/header', $data);
    		$this->load->view('affiliate_advertising/index');
    		$this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        } 
	}

	public function edit(){
        
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Affiliate Advertising: Edit';
            $name = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $name['affiliate_users']['full_name'];

            $this->load->view('main/header', $data);
            $this->load->view('affiliate_advertising/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
