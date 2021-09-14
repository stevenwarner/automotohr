<?php defined('BASEPATH') || exit('No direct script access allowed');

class CompanyAjax extends CI_Controller
{
    //
    private $userDetails;
    private $data;
    //
    private $models;
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("Payroll_model", "pm");
        // Call helper
        $this->load->helper("payroll_helper");
        //
        $this->userDetails = [
            'first_name'=> 'Steven',
            'last_name'=> 'Warner',
            'email'=> FROM_EMAIL_STEVEN,
            'phone' => ''
        ];
        //
        $this->resp = [];
        $this->resp['Status'] = false;
        $this->resp['Error'] = 'Request not authorized.';
        //
        $this->data = [];
        //
        $this->models = [];
        $this->models['sem'] = 'single/Employee_model';
    }

    /**
     * 
     */
    function Dashboard(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Dashboard';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/dashboard')
        ->view('main/footer');
    }

}