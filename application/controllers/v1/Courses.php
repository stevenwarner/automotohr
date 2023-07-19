<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 *
 */
class Courses extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
        // load the library
        $this->load->library('Api_auth');
        // call the company event
        $this->api_auth->checkAndLogin(
            $this->session->userdata('logged_in')['company_detail']['sid'],
            $this->session->userdata('logged_in')['employer_detail']['sid']
        );
        //
    }

    /**
     *
     */
    public function myCourses()
    {
        //
        $data = [];
        //
        //
        $company_sid = $data['session']['company_detail']['sid'];
        $employeeId = $data['session']['employer_detail']['sid'];
      
        //
        // $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "My Courses :: ".STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $data['session']['employer_detail'];
        //
        $this->load
        ->view('main/header_2022', $data)
        ->view('courses/my_dashboard')
        ->view('main/footer_2022');
    }
}
