<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class i9form extends Public_Controller
{
    private $security_details;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('multi_table_data');
        $this->load->model('dashboard_model');
        $this->load->model('settings_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
        require_once(APPPATH . 'libraries/common_functions.php');
        $this->load->library("pagination");
        $this->common_functions = new common_functions();
        $session_details = $this->session->userdata('logged_in');
        $sid = $session_details['employer_detail']['sid'];
        $this->security_details = db_get_access_level_details($sid);
    }

    public function index($type = NULL, $sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $security_details = $this->security_details;
            $data['security_details'] = $security_details;

            $data['session'] = $this->session->userdata('logged_in');
            $company_id = $data['session']['company_detail']['sid'];
            $employer_access_level = $data['session']['employer_detail']['access_level'];

            if ($type == 'employee') {
                redirect('employee_profile/' . $sid); // 19/10/16 disabled i9form for employee on request of client. trello card ref: https://trello.com/c/tFPecPvy/597-i-9-verification-needs-to-be-disabled-on-profiles
                $data_function = employee_right_nav($sid);

                if (in_array('full_access', $security_details) || in_array('employee_i9form', $security_details)) {
                    // It has access to the controller
                } else {
                    $this->session->set_flashdata('message', SECURITY_PERMISSIONS_ERROR);
                    redirect("employee_management", "location");
                }
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee / Team Members Emergency Form I-9 Employment Verification';
                $reload_location = 'i9form/employee/' . $sid;
                $employee_details = $this->dashboard_model->get_company_detail($employer_id);
                $full_employment_application = $employee_details['full_employment_application'];
                $data_function["employer"] = $employee_details;

                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
            }

            if ($type == 'applicant') {
                redirect('applicant_profile/' . $sid);  // 19/10/16 disabled i9form for applicant on request of client. trello card ref: https://trello.com/c/tFPecPvy/597-i-9-verification-needs-to-be-disabled-on-profiles
                $data_function = applicant_right_nav($sid);

                if (in_array('full_access', $security_details) || in_array('applicant_i9form', $security_details)) {
                    // It has access to the controller
                } else {
                    $this->session->set_flashdata('message', SECURITY_PERMISSIONS_ERROR);
                    redirect("application_tracking_system/active/all/all/all/all", "location");
                }
                $employer_id = $sid;
                $left_navigation = 'manage_employer/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Form I-9 Employment Verification';
                $reload_location = 'i9form/applicant/' . $sid;
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
//                $data_function['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data_function['company_background_check'] = checkCompanyAccurateCheck($data["session"]["company_detail"]["sid"]);

                //Outsourced HR Compliance and Onboarding check
                $data_function['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($data["session"]["company_detail"]["sid"]);
                $full_employment_application = $applicant_info['full_employment_application'];
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->application_tracking_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant

                $data_function["employer"] = $data_employer;
                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid;
            }

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data_function['title'] = 'Form I-9 Employment Verification';
                $reload_location = 'i9form';
                $type = 'employee';
                $data_function["employer"] = $this->dashboard_model->get_company_detail($employer_id);
                $full_employment_application = $data_function["employer"]['full_employment_application'];
            }

            if (isset($_POST['action']) && $_POST['action'] == 'true') {
                $data_function["formpost"] = $_POST;
            } else {
                $data_function["formpost"] = unserialize($full_employment_application);
            }

            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            $data_function['states'] = db_get_active_states(227);
            $data_function['starting_year_loop'] = 1930;
            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            $data_function["company"] = $this->dashboard_model->get_portal_detail($company_id);
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data_function);
                $this->load->view('manage_employer/i9form');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $full_employment_application = array();

                foreach ($formpost as $key => $value) {
                    if ($key != 'action' && $key != 'first_name' && $key != 'last_name' && $key != 'sid') { // exclude these values from array
                        $full_employment_application[$key] = $value;
                    }
                }

                $id = $formpost['sid'];
                $data = array(
                    'first_name' => $formpost['first_name'],
                    'last_name' => $formpost['last_name'],
                    'full_employment_application' => serialize($full_employment_application)
                );

                $this->dashboard_model->update_user($id, $data);
                $this->session->set_flashdata('message', '<b>Success:</b> Full employment form updated successfully');
                redirect($reload_location, "location");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}