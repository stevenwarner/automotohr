<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post_on_jobs_to_career extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('jobs_to_career');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $data['title'] = "Post Jobs on Jobs2Career";

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking'); // First Param: security array, 2nd param: redirect url, 3rd param: function name

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];







            $this->load->view('main/header', $data);
            $this->load->view('post_on_jobs_to_career/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
