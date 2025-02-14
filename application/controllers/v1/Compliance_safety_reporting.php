<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Indeed controller to handle all new
 * events
 *
 * @author  AutomotoHR Dev Team
 * @version 1.0
 */
class Compliance_safety_reporting extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('v1/compliance_report_model');
    }


    public function listing()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;

        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Compliance Safety Reporting';

        $types = $this->compliance_report_model->getAllReportTypes($company_sid);

        $data['types'] = $types;
        $load_view = check_blue_panel_status(false, 'self');
        $data['load_view'] = $load_view;
        $data['employee'] = $data['session']['employer_detail'];
        $this->load->view('main/header', $data);
        $this->load->view('compliance_safety_reporting/listings');
        $this->load->view('main/footer');
    }
}
