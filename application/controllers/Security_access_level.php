<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Security_access_level extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'security_access_level'); // Param2: Redirect URL, Param3: Function Name

            $company_id = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "Security Access Manager";
            $data["company_employees"] = db_get_company_users($company_id);
            $data["employer"] = $this->dashboard_model->get_company_detail($employer_id);
            //$data["access_level"] = db_get_enum_values('users', 'access_level');
            $data["access_level"] = $this->dashboard_model->get_security_access_levels();

            $this->load->view('main/header', $data);
            $this->load->view('security_access_level/access_level');
            $this->load->view('main/footer');

        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function access_level()
    {
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_record') {
            $sid = $_REQUEST['id'];
            $access_level = $_REQUEST['level'];
            $this->dashboard_model->update_access_level($access_level, $sid);
            echo "Access Level Updated!";
            exit;
        }
    }

    function details()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'my_settings', 'security_access_level'); // Param2: Redirect URL, Param3: Function Name
        $data['title'] = "Security Access Details";
        $data['security_details'] = db_get_security_access_details();

        $this->load->view('main/header', $data);
        $this->load->view('security_access_level/details');
        $this->load->view('main/footer');

    }
}