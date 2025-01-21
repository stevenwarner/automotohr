<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lms_employees extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/lms_employees_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    /**
     * Handles copy applicants
     * Created on: 23-10-2019
     *
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     *
     * @return VOID
     */

    public function index()
    {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $page_title = 'Mark  Employee LMS  course as completed manually ';
        $companies = $this->lms_employees_model->get_all_companies();
        $this->data['page_title'] = $page_title;
        $this->data['companies'] = $companies;
        $this->render('manage_admin/company/employee_lms_course_list', 'admin_master');
    }

    public function get_corporate_companies($corporate_sid)
    {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        $corporate_companies = $this->copy_employees_model->get_corporate_companies_by_id($corporate_sid);

        if (!empty($corporate_companies)) {
            foreach ($corporate_companies as $key => $corporate_company) {
                $company_sid = $corporate_company['company_sid'];
                $company_name = $this->copy_employees_model->get_company_name_by_id($company_sid);
                if (!empty($company_name)) {
                    $corporate_companies[$key]['company_name'] = $company_name;
                }
            }
        }

        echo json_encode($corporate_companies);
    }

    public function get_companies_employees($company_sid)
    {

        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        if (!$this->input->is_ajax_request() || $this->input->method(true) !== 'GET') {
            exit(0);
        }
        //      
        $company_employees = $this->lms_employees_model->get_company_employee($company_sid);
        echo json_encode($company_employees);
    }
}
