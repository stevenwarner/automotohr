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
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "My Courses :: ".STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        // load CSS
        $data['PageCSS'] = [
            ['1.0.1', '2022/css/main']
        ];
         // load JS
         $this->data['PageScripts'] = [
            ['1.0', 'js/app_helper'],
            ['1.0.0', 'v1/common'],
            ['1.0.1', 'v1/lms/main'],
            ['1.0', 'v1/lms/assign_employee_courses'],
        ];
        //
        $this->load
        ->view('main/header_2022', $data)
        ->view('courses/my_dashboard')
        ->view('main/footer');
    }
}
