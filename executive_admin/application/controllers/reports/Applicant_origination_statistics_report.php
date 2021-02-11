<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_origination_statistics_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');

            $data['company_sid'] = $company_sid;
            $data['title'] = "Generate Applicant Origination Statistics";
            $data['flag'] = true;
            $search = $this->input->get(NULL, TRUE);
            if (!isset($search['date_option'])) {
                $search['date_option'] = 'daily';
            }
            $data['search'] = $search;

            // grouping data
            $indeed_array = array();
            $automotosocial_array = array();
            $glassdoor_array = array();
            $juju_array = array();
            $automotohr_array = array();
            $other_array = array();
            $zip_recruiter_array = array();
            $jobs_2_career_array = array();

            $applicants = $this->Reports_model->get_stats_by_source($search,$company_sid);

            foreach ($applicants as $key => $value) {
                $source = $value['applicant_source'];
                if (strpos($source, 'indeed') !== FALSE) {
                    $indeed_array[] = $value;
                } else if (strpos($source, 'automotosocial.com') !== FALSE) {
                    $automotosocial_array[] = $value;
                } else if (strpos($source, 'glassdoor.com') !== FALSE) {
                    $glassdoor_array[] = $value;
                } else if (strpos($source, 'juju.com') !== FALSE) {
                    $juju_array[] = $value;
                } else if (strpos($source, 'automotohr.com') !== FALSE || strpos($source, 'automotohr') !== FALSE || strpos($source, 'Career Website') !== FALSE || strpos($source, 'career_website') !== FALSE) {
                    $automotohr_array[] = $value;
                } else if (strpos($source, 'ziprecruiter') !== FALSE) {
                    $zip_recruiter_array[] = $value;
                } else if (strpos($source, 'jobs2career') !== FALSE) {
                    $jobs_2_career_array[] = $value;
                } else {
                    $other_array[] = $value;
                }
            }

            $data['indeed'] = $indeed_array;
            $data['indeed_count'] = count($indeed_array);

            $data['automoto_social'] = $automotosocial_array;
            $data['automoto_social_count'] = count($automotosocial_array);

            $data['glassdoor'] = $glassdoor_array;
            $data['glassdoor_count'] = count($glassdoor_array);

            $data['juju'] = $juju_array;
            $data['juju_count'] = count($juju_array);

            $data['automotohr'] = $automotohr_array;
            $data['automotohr_count'] = count($automotohr_array);

            $data['other'] = $other_array;
            $data['other_count'] = count($other_array);

            $data['zip_recruiter'] = $zip_recruiter_array;
            $data['zip_recruiter_count'] = count($zip_recruiter_array);

            $data['jobs_2_career'] = $jobs_2_career_array;
            $data['jobs_2_career_count'] = count($jobs_2_career_array);


            $career_sites_array = array();
            $other_sites_array = array();

//            foreach ($automotohr_array as $key => $value) {
//                $career_sites_array[$value['applicant_source']][] = $automotohr_array[$key];
//            }

            foreach ($other_array as $key => $value) {
                if (!empty($value['applicant_source'])) {
                    $other_sites_array[$value['applicant_source']][] = $other_array[$key];
                } else {
//                $career_sites_array['Career Website'][] = $other_array[$key];
                    $automotohr_array[] = $other_array[$key];
                }
            }

//            $data['career_sites_array'] = $career_sites_array;
            $data['other_sites_array'] = $other_sites_array;
            $data['other_sites_array_count'] = count($other_sites_array);

            //**** working code ****//
            $this->load->view('main/header', $data);
            $this->load->view('reports/applicant_origination_statistics_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }



}
