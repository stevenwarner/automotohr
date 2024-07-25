<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Create  partner company
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Create_partner_company extends CI_Controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // Call the model
        $this->load->model(
            "payroll/Create_partner_company_model",
            "partner_company_model"
        );
        //
        $this->load->model(
            "payroll/Company_payroll_model",
            "company_payroll_model"
        );
    }

     /**
     * get the create partner company step
     *
     * @param int $step
     * @param int $companyId
     * @return json
     */
    public function getCreatePartnerCompanyPage(
        int $step,
        int $companyId
    ): array {
        // welcome page
        if ($step === 1) :
            // check if company is already onboard
            $isOnboard = $this->partner_company_model->getCompanyOnboardLastStep($companyId);
            //
            if ($isOnboard !== 'onboard') {
                return SendResponse(200, ['success' => true, 'onboard' => $isOnboard]);
            }
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/welcome', [], true)
                ]
            );
        elseif ($step === 2) : // employee listing page
            // get all employees
            $employees = $this->partner_company_model->getEmployeesForPayroll(
                $companyId,
                [
                    'users.active' => 1,
                    'users.employee_type != ' => 'contractual',
                    'users.terminated_status' => 0
                ]
            );
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/employees', [
                        'employees' => $employees
                    ], true)
                ]
            );
        elseif ($step === 3) : // admin step
            // get the admin
            $admin = $this->partner_company_model->checkAdminForPayroll(
                $companyId
            );
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/onboard', [
                        'admin' => $admin
                    ], true)
                ]
            );
        elseif ($step === 4) : // set admin step
            // get system employees
            $employees = $this->partner_company_model->getActiveEmployees($companyId);
            //
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/admin', [
                        'employees' => $employees
                    ], true)
                ]
            );
        elseif ($step === 5) : // save admin step
            // get the sanitized post
            $post = $this->input->post(null, true);
            // set default errors
            $errors = [];
            // apply validation
            if (!$post['firstName']) {
                $errors[] = '"First name" is missing.';
            }
            if (!$post['lastName']) {
                $errors[] = '"Last name" is missing.';
            }
            if (!$post['email']) {
                $errors[] = '"Email" is missing.';
            }
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errors[] = '"Email" is invalid.';
            }
            // check for errors
            if ($errors) {
                return SendResponse(400, ['errors' => $errors]);
            }
            // save the admin to database
            $this->partner_company_model->checkAndSaveAdmin($post, $companyId);
            //
            return SendResponse(
                200,
                [
                    'success' => true
                ]
            );
        elseif ($step === 6) : // view admin step
            // get the admin
            $admin = $this->partner_company_model->getAdminForPayroll(
                $companyId
            );
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/admin_view', [
                        'admin' => $admin
                    ], true)
                ]
            );
        elseif ($step === 7) : // create partner company
            // call the executor
            $response = $this->company_payroll_model->startCreatePartnerCompany(
                $companyId,
                $this->input->post('employees', true)
            );
            //
            if (isset($response['errors'])) {
                return SendResponse(400, $response);
            }
            return SendResponse(200, [$response]);
        endif;
        // send default response
        return SendResponse(400, ['errors' => ['Invalid call.']]);
    }
}    