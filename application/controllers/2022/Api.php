<?php defined('BASEPATH') or exit('No direct script access allowed');

class Api extends Public_Controller{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 
     */
    public function get_report ($company_sid = 0, $date = date('Y_m_d', strtotime('now')), $domain = "ahr", $user_sid = 0, $page = 1) {
        
    }
}
