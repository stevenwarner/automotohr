<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        //
        parent::__construct();
        //
        // if (!$this->session->userdata('logged_in') && !$this->session->userdata('user_id')) {
        //     return SendResponse(401, ['errors' => ['Access denied, you are not authorized to make this call.']]);
        // }
        // Call the model
        $this->load->model("v1/Payroll_model", "payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function checkCompanyRequirements(int $companyId): array
    {
        //
        $returnArray = $this->payroll_model->checkCompanyRequirements($companyId);
        //
        if (!$returnArray) {
            return SendResponse(200, ['success' => true]);
        }
        //
        return SendResponse(400, ['errors' => $returnArray]);
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
    ) {
        // welcome page
        if ($step === 1) :
            // check if company is already onboard
            $isOnboard = $this->payroll_model->getCompanyOnboardLastStep($companyId);
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
            $employees = $this->payroll_model->getEmployeesForPayroll(
                $companyId
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
            $admin = $this->payroll_model->checkAdminForPayroll(
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
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/admin', [], true)
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
            $this->payroll_model->checkAndSaveAdmin($post, $companyId);
            //
            return SendResponse(
                200,
                [
                    'success' => true
                ]
            );
        elseif ($step === 6) : // view admin step
            // get the admin
            $admin = $this->payroll_model->getAdminForPayroll(
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
            $response = $this->payroll_model->startCreatePartnerCompany(
                $companyId,
                $this->input->post('employees', true)
            );
            //
            if (isset($response['errors'])) {
                return SendResponse(400, $response);
            }
            return SendResponse(200, [$response]);
        elseif ($step === 8) : // sync admins
            // call the executor
            $this->payroll_model->syncPayrollAdmins(
                $companyId
            );
            //
            return SendResponse(200, ['success' => true]);
        elseif ($step === 9) : // get and show Gusto terms
            // get the company
            $companyTerms = $this->payroll_model->getCompanyDetailsForGusto($companyId, [
                'is_ts_accepted',
                'ts_email',
                'ts_ip',
                'ts_user_sid'
            ], false);
            //
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/service_agreement', [
                        'companyTerms' => $companyTerms
                    ], true)
                ]
            );
        elseif ($step === 10) : // accept Gusto terms
            // get sanitized post
            $post = $this->input->post(null, true);
            // set error array
            $errorArray = [];
            // validation
            if (!$post['email']) {
                $errorArray[] = '"Email" is required.';
            }
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                $errorArray[] = '"Email" is invalid.';
            }
            if (!$post['userCode']) {
                $errorArray[] = '"System User Reference" is required.';
            }
            // send errors
            if ($errorArray) {
                return SendResponse(400, ['errors' => $errorArray]);
            }
            $response = $this->payroll_model->agreeToGustoTerms($post, $companyId);
            //
            if ($response['errors']) {
                return SendResponse(400, $response);
            }
            //
            return SendResponse(
                200,
                [
                    'success' => true
                ]
            );
        elseif ($step === 11) : // push company location to Gusto
            //
            $response = $this->payroll_model->checkAndPushCompanyLocationToGusto($companyId);
            //
            if ($response['errors']) {
                return SendResponse(400, $response);
            }
            //
            return SendResponse(
                200,
                [
                    'success' => true
                ]
            );
        elseif ($step === 12) : // push selected employees to Gusto
            //
            $employees = $this->input->post('employees', true);
            //
            if (!$employees) {
                return SendResponse(400, ['errors' => 'No employees selected']);
            }
            // set
            $errorArray = [];
            // loop through the data
            foreach ($employees as $employeeId) {
                // start employee onboard
                $response = $this->payroll_model->onboardEmployee($employeeId, $companyId);
                //
                if ($response['errors']) {
                    $errorArray[$employeeId] = $response['errors'];
                }
            }
            //
            if ($errorArray) {
                return SendResponse(400, ['errors' => $errorArray]);
            }

            //
            return SendResponse(
                200,
                [
                    'success' => true
                ]
            );
        endif;
        // send default response
        return SendResponse(400, ['errors' => ['Invalid call.']]);
    }
}
