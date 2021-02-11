<?php defined('BASEPATH') or exit('No direct script access allowed');

class Testing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    function testEmail(){
        sendResumeEmailToApplicant([
            'company_sid' => 57,
            'company_name' => 'Dev Team',
            'job_list_sid' => 29934,
            'user_sid' => 825425,
            'user_type' => 'applicant',
            'requested_job_sid' => 29934,
            'requested_job_type' => 'job'
        ]);
    }

}
