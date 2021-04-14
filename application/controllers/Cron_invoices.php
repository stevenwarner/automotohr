<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Pmg_cron extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/recurring_payments_model');
    }

    public function index($verification_key = null) {
        //
        $today = new DateTime();
        //
        if(
            $verification_key != 'dwwbtPzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk4HV7ZNCbu2ZfkjFVLHWINnY5uzMkUfIiINdZ19NJj'
        ){ exit(0); }
        //
        
    }
}