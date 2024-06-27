<?php

defined('BASEPATH') or exit('No direct script access allowed');

class General_ledger extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('bulk_resume_model');
    }

    public function index($start_date = 'all', $end_date = 'all')
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $data['sanitizedView'] = true;
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'bulk_resume'); // Param2: Redirect URL, Param3: Function Name
        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['title'] = 'General Ledger Report';
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        //
        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
        } else {
            $start_date_applied = date('Y-m-d 00:00:00');
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
        } else {
            $end_date_applied = date('Y-m-d');
        }
        //
        $between = '';
        //
        if ($start_date_applied != NULL && $end_date_applied != NULL) {
            $between = "terminated_employees.termination_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
        }
        //
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/general_ledger"
        ], "public/v1/shifts/", "general_ledger", false);
        //
        $generalLedger = [];
        //
        $data['company_sid'] = $company_sid;
        $data['company_name'] = $company_name;
        $data['employer_sid'] = $employer_sid;
        $data['generalLedgerCount'] = count($generalLedger);
        $data['generalLedger'] = $generalLedger;
        //
        $this->load->view('main/header', $data);
        $this->load->view('general_ledger/index');
        $this->load->view('main/footer');
    }
}
