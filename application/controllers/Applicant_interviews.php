<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set("memory_limit", "1024M");


class Applicant_interviews extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        // require_once(APPPATH . 'libraries/aws/aws.php');
        $this->load->model('application_tracking_system_model');
        $this->load->model('applicant_interview_model');
        // check and add status
        $this->load->model('Application_status_model', 'application_status_model');
        // $this->application_status_model
        //     ->replaceStatusCheck(
        //         $this->session->userdata('logged_in')['company_detail']['sid'] ?? 0
        //     );
    }

    public function interviewCall($id) {
        $data['portal_job_list'] = $this->applicant_interview_model->get_applicant_data($id);
        $this->load->view('applicant/interview', $data);
    }
}