<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_origination_statistics extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/advanced_report_model');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_origination_statistics';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Applicant Origination Statistics';
        $this->data['flag'] = true;
        $search = $this->input->get(NULL, TRUE);
        if (!isset($search['date_option'])) {
            $search['date_option'] = 'daily';
        }
        $this->data['search'] = $search;

        // grouping data
        $indeed_array = array();
        $automotosocial_array = array();
        $glassdoor_array = array();
        $juju_array = array();
        $automotohr_array = array();
        $other_array = array();
        $zip_recruiter_array = array();
        $jobs_2_career_array = array();
        $career_builder_array = array();

        $applicants = $this->advanced_report_model->get_stats_by_source($search);

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
            } else if (strpos($source, 'careerbuilder') !== FALSE) {
                $career_builder_array[] = $value;
            } else {
                $other_array[] = $value;
            }
        }

        $this->data['indeed'] = $indeed_array;
        $this->data['indeed_count'] = count($indeed_array);

        $this->data['automoto_social'] = $automotosocial_array;
        $this->data['automoto_social_count'] = count($automotosocial_array);

        $this->data['glassdoor'] = $glassdoor_array;
        $this->data['glassdoor_count'] = count($glassdoor_array);

        $this->data['juju'] = $juju_array;
        $this->data['juju_count'] = count($juju_array);

        $this->data['automotohr'] = $automotohr_array;
        $this->data['automotohr_count'] = count($automotohr_array);

        $this->data['other'] = $other_array;
        $this->data['other_count'] = count($other_array);

        $this->data['zip_recruiter'] = $zip_recruiter_array;
        $this->data['zip_recruiter_count'] = count($zip_recruiter_array);

        $this->data['jobs_2_career'] = $jobs_2_career_array;
        $this->data['jobs_2_career_count'] = count($jobs_2_career_array);

        $this->data['career_builder'] = $career_builder_array;
        $this->data['career_builder_count'] = count($career_builder_array);


//        $career_sites_array = array();
        $other_sites_array = array();

//        foreach ($automotohr_array as $key => $value) {
//            $career_sites_array[$value['applicant_source']][] = $automotohr_array[$key];
//        }

        foreach ($other_array as $key => $value) {
            if (!empty($value['applicant_source'])) {
                $other_sites_array[$value['applicant_source']][] = $other_array[$key];
            } else {
//                $career_sites_array['Career Website'][] = $other_array[$key];
                $automotohr_array[] = $other_array[$key];
            }
        }

//        $this->data['career_sites_array'] = $career_sites_array;
        $this->data['other_sites_array'] = $other_sites_array;
        $this->data['other_sites_array_count'] = count($other_sites_array);

        $this->render('manage_admin/reports/applicant_origination_statistics');
    }
}
