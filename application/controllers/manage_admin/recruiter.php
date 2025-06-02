<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Recruiter extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/settings_model');
    }

    public function configurations() {
        return 'there';
    }
}