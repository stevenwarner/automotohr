<?php defined("BASEPATH") || exit("Invalid access.");

/**
 * Company payroll
 * @version 1.0
 */

class Company extends Payroll_base_controller
{
    /**
     * main entry point
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct(true);
        // Call the model
        $this->load->model(
            "Company_payroll_model",
            "company_payroll_model"
        );
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
        $response = $this
            ->company_payroll_model
            ->checkCompanyRequirements(
                $companyId
            );
        //
        return SendResponse(
            !$response
                ? 200
                : 400,
            !$response
                ? ["success" => true]
                : ["errors" => $response]
        );
    }


    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function getWelcomePage(int $companyId): array
    {
        // check if company is already onboard
        $isOnboard = $this
            ->company_payroll_model
            ->getCompanyOnboardLastStep(
                $companyId
            );
        //
        if ($isOnboard !== 'onboard') {
            return SendResponse(
                200,
                [
                    'success' => true,
                    'onboard' => $isOnboard
                ]
            );
        }
        return SendResponse(
            200,
            [
                'view' =>
                $this->load->view(
                    'payroll/create_partner_company/welcome',
                    [],
                    true
                )
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function getEmployees(int $companyId): array
    {
        // get all employees
        $employees = $this
            ->company_payroll_model
            ->getEmployeesForInitialSelection(
                $companyId
            );
        //
        return SendResponse(
            200,
            [
                'view' =>
                $this
                    ->load
                    ->view(
                        'payroll/create_partner_company/employees',
                        [
                            'employees' => $employees
                        ],
                        true
                    )
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function getAdmin(int $companyId): array
    {
        // get the admin
        $admin = $this
            ->company_payroll_model
            ->checkAdminForPayroll(
                $companyId
            );
        return SendResponse(
            200,
            [
                'view' => $this
                    ->load
                    ->view(
                        'payroll/create_partner_company/admin',
                        [
                            'admin' => $admin
                        ],
                        true
                    )
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function addAdmin(int $companyId): array
    {
        // get system employees
        $employees = $this
            ->company_payroll_model
            ->getActiveEmployees(
                $companyId
            );
        //
        return SendResponse(
            200,
            [
                'view' => $this
                    ->load
                    ->view('payroll/create_partner_company/admin_add', [
                        'employees' => $employees
                    ], true)
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function saveAdmin(int $companyId): array
    {
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
        $this
            ->company_payroll_model
            ->checkAndSaveAdmin($post, $companyId);
        //
        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function viewAdmin(int $companyId): array
    {
        $admin = $this
            ->company_payroll_model
            ->getAdminForPayroll(
                $companyId
            );
        return SendResponse(
            200,
            [
                'view' => $this
                    ->load
                    ->view(
                        'payroll/create_partner_company/admin_view',
                        [
                            'admin' => $admin
                        ],
                        true
                    )
            ]
        );
    }

    /**
     * check company requirements
     *
     * @param int $companyId
     * @return array
     */
    public function createPartnerCompany(int $companyId): array
    {
        // call the executor
        $response = $this
            ->company_payroll_model
            ->startCreatePartnerCompany(
                $companyId,
                $this->input->post("employees", true)
            );
        //
        if (isset($response['errors'])) {
            return SendResponse(400, $response);
        }
        return SendResponse(200, [$response]);
    }


    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function getAgreement(int $companyId)
    {
        //
        if (!isCompanyLinkedWithGusto($companyId)) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        "Company is not set up for payroll."
                    ]
                ]
            );
        }
        // set
        $data = [];
        // check if the contract is signed
        $data['agreement'] = $this
            ->db
            ->select('is_ts_accepted, ts_email, ts_ip')
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
        // get company's admins
        $data['admins'] = $this->db
            ->select('
                email_address, 
                automotohr_reference
            ')
            ->where('company_sid', $companyId)
            ->where('is_store_admin', 0)
            ->get('gusto_companies_admin')
            ->result_array();
        //
        return SendResponse(
            200,
            [
                'view' =>
                $this->load->view(
                    'payroll/agreement/agreement',
                    $data,
                    true
                )
            ]
        );
    }

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function signAgreement(int $companyId): array
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
        $response = $this
            ->company_payroll_model
            ->signCompanyAgreement(
                $companyId,
                $post
            );
        //
        if ($response["errors"]) {
            return SendResponse(400, $errors);
        }
        // add the job to the queue
        $this
            ->company_payroll_model
            ->addSyncJobToQueue(
                $companyId
            );
        //
        return SendResponse(200, $response);
    }
}
