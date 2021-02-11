<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_login_text extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('dashboard_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'emp_login_text'); // Param2: Redirect URL, Param3: Function Name

            $company_id  = $data["session"]["company_detail"]["sid"];
            $employer_id = $data["session"]["employer_detail"]["sid"];
            $data['title'] = "FOOTER LOGIN TEXT CHANGES";
            $data["company"] = $this->dashboard_model->get_portal_detail($company_id);

            $this->form_validation->set_rules('employee_login_text', 'Employee Login Text', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/employee_login_text');
                $this->load->view('main/footer');
            } else {
                $employee_login_text_status = $this->input->post('employee_login_text_status');
                $employee_login_text = $this->input->post('employee_login_text');

                if (empty($employee_login_text)) {
                    $employee_login_text_status = 0;
                }
                $footer_login_data = array(
                    'employee_login_text_status' => $employee_login_text_status,
                    'employee_login_text' => $employee_login_text,
                );
                $sid = $data['company']['user_sid'];
                $this->dashboard_model->update_portal($footer_login_data, $sid);
                $this->session->set_flashdata('message', '<b>Success:</b> Footer Login Text updated successfully');
                redirect("my_settings", "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
