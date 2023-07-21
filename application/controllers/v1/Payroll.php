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

    public function dashboard()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        //
        $companyId = $data['session']['company_detail']['sid'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [];
        $data['PageScripts'] = [];
        //
        $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId);
        // get the company onboard flow
        $data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "federal_tax_setup,select_industry,add_bank_info,verify_bank_info,state_setup,payroll_schedule,sign_all_forms",
                "entity_type" => "Company",
                "entity_uuid" => $companyGustoDetails['gusto_uuid']
            ],
            "POST"
        )['url'];
        // get the payroll blockers
        $data['payrollBlockers'] = gustoCall(
            'getPayrollBlockers',
            $companyGustoDetails
        );
        // check if payroll can be run
        $data['canRunPayroll'] = $data['payrollBlockers'] ? false : true;
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/dashboard')
            ->view('main/footer');
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
        endif;
        // send default response
        return SendResponse(400, ['errors' => ['Invalid call.']]);
    }

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function getCompanyAgreement(int $companyId)
    {
        // set
        $data = [];
        // check if the contract is signed
        $data['agreement'] = $this->db
            ->select('is_ts_accepted, ts_email, ts_ip')
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/create_partner_company/agreement', $data, true)
            ]
        );
    }

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function signCompanyAgreement(int $companyId)
    {
        // set the sanitized post
        $post = $this->input->post(null, true);
        //
        $errors = [];
        // validation
        if (!$post['email']) {
            $errors[] = '"Email" is required.';
        }
        if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = '"Email" is invalid.';
        }
        if (!$post['userReference']) {
            $errors[] = '"System User Reference" is required.';
        }
        //
        if ($errors) {
            return SendResponse(400, ['errors' => $errors]);
        }
        //
        $companyDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['employee_ids']);
        //
        $request = [];
        $request['ip_address'] = getUserIP();
        $request['external_user_id'] = $post['userReference'];
        $request['email'] = $post['email'];
        //
        $gustoResponse = agreeToServiceAgreementFromGusto($request, $companyDetails);
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return SendResponse(400, $errors);
        }
        //
        $this->db->where('company_sid', $companyId)
            ->update('gusto_companies', [
                'is_ts_accepted' => 1,
                'ts_email' => $request['email'],
                'ts_ip' => $request['ip_address'],
                'ts_user_sid' => $request['external_user_id'],
            ]);
        // let's push the saved data
        // location
        $this->payroll_model->checkAndPushCompanyLocationToGusto($companyId);
        // get the employee list
        $ids = explode(',', $companyDetails['employee_ids']);
        //
        foreach ($ids as $employeeId) {
            // selected employees
            $this->payroll_model->onboardEmployee($employeeId, $companyId);
        }
        //
        return SendResponse(200, ['success' => true]);
    }
}
