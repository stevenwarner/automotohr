<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set("memory_limit", "1024M");

class Applicant_interviews extends CI_Controller
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

    public function interviewCall($id)
    {
        $portal_job_list = $this->applicant_interview_model->get_applicant_data($id);
        $data['company'] = array(
            'name' => $portal_job_list['CompanyName'],
            'logo' => $portal_job_list['profile_picture'] ? $portal_job_list['profile_picture'] : $portal_job_list['Logo']
        );
        $data['portal_employeer'] = $this->applicant_interview_model->get_portal_employer($portal_job_list['userId']);

        $interview_logs = $this->application_tracking_system_model->get_interview_log($id);
        if(!empty($interview_logs['reports'])) {
            $this->load->view('applicant/interview-expired', $data);
            return;
        }
        
        $data['portal_job_list'] = $portal_job_list;
        $this->load->view('applicant/interview', $data);
    }
}