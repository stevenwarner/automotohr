<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Thank_you extends CI_Controller {

    public function __construct() {
        parent::__construct();

    }

    public function index() {

    	$data['page_title'] = 'Thank You';
        //$this->load->view('main/header');
        $this->load->view('main/thank_you', $data);
        //$this->load->view('main/footer');
    }
}
