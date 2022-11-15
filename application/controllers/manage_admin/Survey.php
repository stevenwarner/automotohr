<?php defined('BASEPATH') or exit('No direct script access allowed');

class Survey extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/time_off_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {

        $this->data['page_title'] = 'Templates';


        /*
        $api_url = 'http://localhost:3000/employee_survey/templates';
        $cSession = curl_init();
        curl_setopt ($cSession, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($cSession, CURLOPT_URL, $api_url);
        curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cSession, CURLOPT_HEADER, false);
        $response = curl_exec($cSession);
        curl_close($cSession);
        $response = json_decode($response, true);
        _e($response,true,true);
      */



        $response = '[{
            "title": "Pulse Check",
            "description": "This is a quick survey to measure the company’s engagement.",
            "frequency": "monthly",
            "questions_count": 5
        },
        {
            "title": "Employee Net Promoter Score (eNPS)",
            "description": "This survey is designed to capture the companys internal happiness and loyalty. The questions are inspired by the Net Promoter® Score template, an industry standard survey to gather customer satisfaction.",
            "frequency": "monthly",
            "questions_count": 2
        },{
            "title": "Engagement Survey",
            "description": "This survey’s purpose is to obtain holistic feedback on the company’s leadership, enablement, alignment and development.",
            "frequency": "Semiannually",
            "questions_count": 21
        },{
            "title": "Pre Open Enrollment Survey",
            "description": "This survey will measure if people are satisfied with the company’s current health benefit offerings.",
            "frequency": "annually",
            "questions_count": 8
        },{
            "title": "Post Open Enrollment Survey",
            "description": "This survey’s intent is to gather feedback on the company’s health benefit offerings in the most recent open enrollment.",
            "frequency": "annually",
            "questions_count": 10
        },{
            "title": "Work-Life Flexibility",
            "description": "This is a quick survey to assess the company’s work-life balance.",
            "frequency": "quarterly",
            "questions_count": 4
        }]';






       $response = json_decode($response, true);

       // print_r(json_decode($response, true));
        //die('sdf');
        $this->data['surveytemplates'] = $response;
        $this->render('manage_admin/survey/template_listing', 'admin_master');
    }
}
