<?php defined('BASEPATH') or exit('No direct script access allowed');

class Lms_employees extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('v1/course_model');
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
        $page_title = 'LMS :: Employee Course';
        $companies = $this->lms_employees_model->get_all_companies();
        $this->data['page_title'] = $page_title;
        $this->data['companies'] = $companies;
        $this->render('manage_admin/company/employee_lms_course_list', 'admin_master');
    }

    public function get_corporate_companies($corporate_sid)
    {
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
        return SendResponse(
            200,
            $this
                ->lms_employees_model
                ->get_company_employee($company_sid)
        );
    }

    //
    public function employeeAllCourses($companyId, $employeeId)
    {
        return SendResponse(
            200,
            $this
                ->course_model
                ->getEmployeePendingCourseDetail(
                    $companyId,
                    $employeeId
                )
        );
    }

    public function manuallyCourseComplete()
    {
        // get the sanitized post
        $this
            ->course_model
            ->manuallyCourseComplete(
                $this->input->post("companyId", true),
                $this->input->post("employeeId", true),
                $this->input->post("courseId", true),
                $this->input->post("language", true),
                $this->input->post("completionDate", true),
            );
    }
}
