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
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_email', '"{field}" is invalid.');
        //
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
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['level'] = 0;
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
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/payroll/js/dashboard'
        ];
        //
        $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId);
        // get the company onboard flow
        $data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "
                    select_industry,
                    add_bank_info,
                    verify_bank_info,
                    payroll_schedule,
                    federal_tax_setup,
                    add_employees,
                    state_setup,
                    " . (isLoggedInPersonIsSignatory() ? 'sign_all_forms' : '') . "
                ",
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
        //
        $this->payroll_model->handleInitialEmployeeOnboard($data['session']['company_detail']['sid']);
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/dashboard')
            ->view('main/footer');
    }

    /**
     * Manage admins
     */
    public function manageAdmins()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [];
        $data['PageScripts'] = [];
        // get admins
        $data['admins'] = $this->db
            ->where([
                'company_sid' => $data['loggedInPersonCompany']['sid'],
                'is_store_admin' => 0
            ])
            ->get('gusto_companies_admin')
            ->result_array();
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/admins/manage')
            ->view('main/footer');
    }

    /**
     * add admins
     */
    public function manageAddAdmin()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [];
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/payroll/js/admin/add'
        ];
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/admins/add')
            ->view('main/footer');
    }

    /**
     * manage signatories
     */
    public function manageSignatories()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [];
        $data['PageScripts'] = [];
        // get admins
        $data['signatories'] = $this->db
            ->where([
                'company_sid' => $data['loggedInPersonCompany']['sid'],
            ])
            ->get('gusto_companies_signatories')
            ->row_array();
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/signatories/manage')
            ->view('main/footer');
    }

    /**
     * create signatory
     */
    public function createSignatoriesPage()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        //
        if ($this->db
            ->where([
                'company_sid' => $data['loggedInPersonCompany']['sid'],
            ])
            ->count_all_results('gusto_companies_signatories')
        ) {
            return redirect('payrolls/signatories');
        }
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [];
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/payroll/js/signatories/create'
        ];
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/signatories/create')
            ->view('main/footer');
    }

    /**
     * Manage employees
     */
    public function manageEmployees()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $companyId = $data['session']['company_detail']['sid'];
        //
        $data['title'] = "Manage Payroll Employees";
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // scripts
        $data['PageCSS'] = [
            'v1/plugins/ms_modal/main',
        ];
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/plugins/ms_modal/main',
            'v1/payroll/js/employees/manage'
        ];
        // get employees
        $data['payrollEmployees'] = $this->payroll_model->getPayrollEmployees($companyId);
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/employees/manage')
            ->view('main/footer');
    }


    // API routes
    /**
     * Create admin
     *
     * @return
     */
    public function createAdmin(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // validation
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_email', '"{field}" is invalid.');
        $this->form_validation->set_rules(
            'first_name',
            'First Name',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'last_name',
            'Last Name',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'email_address',
            'Email Address',
            'trim|xss_clean|required|valid_email'
        );
        // validate
        if ($this->form_validation->run() === false) {
            //
            $errors = explode("\n", validation_errors(' ', ' '));
            //
            unset($errors[count($errors) - 1]);
            //
            return SendResponse(
                400,
                [
                    'errors' => $errors
                ]
            );
        }
        // get post
        $post = $this->input->post(null, true);
        // check if admin all ready created
        if ($this->db->where([
            'email_address' => $post['email_address'],
            'company_sid' =>  $session['company_detail']['sid']
        ])->count_all_results('gusto_companies_admin')) {
            //
            return SendResponse(
                400,
                [
                    'errors' => ['"' . ($post['email_address']) . '" already exists.']
                ]
            );
        }
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($session['company_detail']['sid']);
        // set request
        $request = [];
        $request['first_name'] = $post['first_name'];
        $request['last_name'] = $post['last_name'];
        $request['email'] = $post['email_address'];
        // make call
        $gustoResponse = createAdminOnGusto($request, $companyDetails);
        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            // block the iteration
            return SendResponse(400, $errors);
        }
        // add the admin to store
        $insertArray = [];
        $insertArray['company_sid'] = $session['company_detail']['sid'];
        $insertArray['gusto_uuid'] = $gustoResponse['uuid'];
        $insertArray['first_name'] = $post['first_name'];
        $insertArray['last_name'] = $post['last_name'];
        $insertArray['email_address'] = $post['email_address'];
        $insertArray['is_store_admin'] = 0;
        $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
        // add the admin
        $this->db->insert(
            'gusto_companies_admin',
            $insertArray
        );
        //
        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * Create admin
     *
     * @return
     */
    public function createSignatory(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // validation

        $this->form_validation->set_rules(
            'first_name',
            'First Name',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'last_name',
            'Last Name',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'ssn',
            'Social Security Number',
            'trim|xss_clean|required|exact_length[9]'
        );
        $this->form_validation->set_rules(
            'email',
            'Email',
            'trim|xss_clean|required|valid_email'
        );
        $this->form_validation->set_rules(
            'title',
            'Title',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'birthday',
            'Birthday',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'street1',
            'Street 1',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'city',
            'City',
            'trim|xss_clean|required'
        );
        $this->form_validation->set_rules(
            'state',
            'State',
            'trim|xss_clean|required|min_length[2]|max_length[3]'
        );
        $this->form_validation->set_rules(
            'zip',
            'Zip',
            'trim|xss_clean|required|min_length[5]'
        );
        // validate
        if ($this->form_validation->run() === false) {
            //
            $errors = explode("\n", validation_errors(' ', ' '));
            //
            unset($errors[count($errors) - 1]);
            //
            return SendResponse(
                400,
                [
                    'errors' => $errors
                ]
            );
        }
        // get post
        $post = $this->input->post(null, true);
        // check if admin all ready created
        if ($this->db->where([
            'company_sid' =>  $session['company_detail']['sid']
        ])->count_all_results('gusto_companies_signatories')) {
            //
            return SendResponse(
                400,
                [
                    'errors' => ['"Signatory" already exists.']
                ]
            );
        }
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($session['company_detail']['sid']);
        // set request
        $request = [];
        $request['first_name'] = $post['first_name'];
        $request['last_name'] = $post['last_name'];
        $request['middle_initial'] = $post['middle_initial'][0];
        $request['ssn'] = $post['ssn'];
        $request['email'] = $post['email'];
        $request['title'] = $post['title'];
        $request['phone'] = $post['phone'];
        $request['birthday'] = formatDateToDB($post['birthday'], SITE_DATE, DB_DATE);
        $request['home_address']['street_1'] = $post['street1'];
        $request['home_address']['street_2'] = $post['street2'];
        $request['home_address']['city'] = $post['city'];
        $request['home_address']['state'] = $post['state'];
        $request['home_address']['zip'] = $post['zip'];
        // make call
        $gustoResponse = gustoCall(
            'createSignatory',
            $companyDetails,
            $request,
            "POST"
        );

        // check for errors
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            // block the iteration
            return SendResponse(400, $errors);
        }
        // add the admin to store
        $insertArray = $request;
        $insertArray['street_1'] = $request['home_address']['street_1'];
        $insertArray['street_2'] = $request['home_address']['street_2'];
        $insertArray['city'] = $request['home_address']['city'];
        $insertArray['state'] = $request['home_address']['state'];
        $insertArray['zip'] = $request['home_address']['zip'];
        $insertArray['company_sid'] = $session['company_detail']['sid'];
        $insertArray['gusto_uuid'] = $gustoResponse['uuid'];
        $insertArray['gusto_version'] = $gustoResponse['version'];
        $insertArray['identity_verification_status'] = $gustoResponse['identity_verification_status'];
        $insertArray['created_at'] = $insertArray['updated_at'] = getSystemDate();
        //
        unset($insertArray['home_address']);
        // add the admin
        $this->db->insert(
            'gusto_companies_signatories',
            $insertArray
        );
        //
        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * Sync company
     * TODO
     *
     * @return
     */
    public function syncCompanyWithGusto(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        return $this->payroll_model->syncCompanyWithGusto(
            $session['company_detail']['sid']
        );
    }

    /**
     * Verify bank account
     * Only available on demo mode
     *
     * @return
     */
    public function verifyCompanyBankAccount(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($session['company_detail']['sid']);
        // get the company bank details
        $companyBankAccounts = $this->db
            ->select('sid, gusto_uuid')
            ->where('company_sid', $session['company_detail']['sid'])
            ->get('companies_bank_accounts')
            ->result_array();
        //
        if (!$companyBankAccounts) {
            return SendResponse(
                400,
                [
                    'errors' => 'Company doesn\'t registered a bank account. Please sync the company first.'
                ]
            );
        }
        //
        $errorsArray = [];
        //
        foreach ($companyBankAccounts as $bankAccount) {
            //
            $companyDetails['other_uuid'] = $bankAccount['gusto_uuid'];
            //
            $gustoResponse = gustoCall(
                'sendDeposits',
                $companyDetails,
                [
                    'deposit_1' => '0.02',
                    'deposit_2' => '0.42',
                ],
                "POST"
            );
            //
            $errors = hasGustoErrors($gustoResponse);
            //
            if ($errors) {
                $errorsArray = array_merge($errorsArray, $errors['errors']);
                continue;
            }
            // verify call
            $gustoResponse = gustoCall(
                'verifyBankAccount',
                $companyDetails,
                $gustoResponse,
                "PUT"
            );
            //
            $errors = hasGustoErrors($gustoResponse);
            //
            if ($errors) {
                $errorsArray = array_merge($errorsArray, $errors['errors']);
            }
            //
            $this->db
                ->where('sid', $bankAccount['sid'])
                ->update(
                    'companies_bank_accounts',
                    [
                        'verification_status' => $gustoResponse['verification_status'],
                        'verification_type' => $gustoResponse['verification_type'],
                        'plaid_status' => $gustoResponse['plaid_status'],
                        'last_cached_balance' => $gustoResponse['last_cached_balance'],
                        'balance_fetched_date' => $gustoResponse['balance_fetched_date'],
                    ]
                );
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                [
                    'errors' => $errorsArray
                ]
            );
        }
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
    ): array {
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
    public function getCompanyAgreement(int $companyId): array
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
    public function signCompanyAgreement(int $companyId): array
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

    /**
     * employee onboard flow
     *
     * @param int    $employeeId
     * @param string $step
     * @return json
     */
    public function employeeOnboardFlow(int $employeeId, string $step): array
    {
        // get employee details
        $gustoEmployee = $this->payroll_model->getEmployeeDetailsForGusto(
            $employeeId,
            [
                'company_sid',
                'personal_details',
                'compensation_details',
                'work_address',
                'home_address',
                'federal_tax',
                'state_tax',
                'new_hire_report',
            ]
        );
        // set data
        $data = [];
        $data['step'] = $step;
        // for summary page
        if ($step === 'summary') {
            $data['summary'] = [
                ['Personal details', $gustoEmployee['personal_details']],
                ['Enter compensation details', $gustoEmployee['compensation_details']],
                ['Add work address', $gustoEmployee['work_address']],
                ['Add home address', $gustoEmployee['home_address']],
                ['Enter federal tax withholdings', $gustoEmployee['federal_tax']],
                ['Enter state tax information', $gustoEmployee['state_tax']],
                ['File new hire report', $gustoEmployee['new_hire_report']],
            ];
        } elseif ($step === 'personal_details') {
            //
            $data['locations'] = $this->payroll_model->getCompanyLocations(
                $gustoEmployee['company_sid']
            );
            //
            $data['personalDetails'] = $this->payroll_model->getEmployeePersonalDetailsForGusto(
                $employeeId
            );
        } elseif ($step === 'compensation_details') {
            //
            $data['primaryJob'] = $this->payroll_model
                ->getEmployeePrimaryJob(
                    $employeeId
                );
        } elseif ($step === 'home_address') {
            //
            $data['record'] = $this->payroll_model
                ->getEmployeeHomeAddress(
                    $employeeId
                );
        } elseif ($step === 'federal_tax') {
            //
            $data['record'] = $this->payroll_model
                ->getEmployeeFederalTax(
                    $employeeId
                );
        }
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/employees/flow', $data, true)
            ]
        );
    }

    /**
     * employee onboard flow personal details
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeePersonalDetails(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // get employee details
        $employeeDetails = $this->db
            ->select('ssn, dob')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        $post['ssn'] = strpos($post['ssn'], '#') === false ? $post['ssn'] : $employeeDetails['ssn'];
        $post['date_of_birth'] = strpos($post['date_of_birth'], '#') === false
            ? formatDateToDB($post['date_of_birth'], SITE_DATE, DB_DATE)
            : $employeeDetails['dob'];
        //
        $post['start_date'] = formatDateToDB($post['start_date'], SITE_DATE, DB_DATE);
        // set error array
        $errorsArray = [];
        // validation
        if (!$post['first_name']) {
            $errorsArray[] = '"First name" is missing.';
        }
        if (!$post['last_name']) {
            $errorsArray[] = '"Last name" is missing.';
        }
        if (!$post['location_uuid']) {
            $errorsArray[] = '"Work address" is missing.';
        }
        if (!$post['start_date']) {
            $errorsArray[] = '"Start date" is missing.';
        }
        if (!$post['email']) {
            $errorsArray[] = '"Email" is missing.';
        }
        if (!$post['ssn']) {
            $errorsArray[] = '"Social Security Number (SSN)" is missing.';
        }
        if (!$post['date_of_birth']) {
            $errorsArray[] = '"Date of birth" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                404,
                ['errors' => $errorsArray]
            );
        }
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                404,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }

        // let's update employee's profile
        $response = $this->payroll_model
            ->updateEmployeePersonalDetails(
                $employeeId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                404,
                $response
            );
        }

        // let's update employee's work address
        $response = $this->payroll_model
            ->updateEmployeeJob(
                $employeeId,
                [
                    'location_uuid' => $post['location_uuid'],
                    'start_date' => $post['start_date'],
                ]
            );

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated employees personal details' . ($response['errors'] ? '' : ' and work location.')
            ]
        );
    }

    /**
     * employee onboard flow compensation
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeeCompensation(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        // validation
        if (!$post['title']) {
            $errorsArray[] = '"Job title" is missing.';
        }
        if (!$post['classification']) {
            $errorsArray[] = '"Employee classification" is missing.';
        }
        if (!$post['amount']) {
            $errorsArray[] = '"Amount" is missing.';
        }
        if (!$post['per']) {
            $errorsArray[] = '"Per" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                404,
                ['errors' => $errorsArray]
            );
        }
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                404,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }

        // let's update employee's profile
        $response = $this->payroll_model
            ->updateEmployeeCompensation(
                $employeeId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                404,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated compensation.'
            ]
        );
    }

    /**
     * employee onboard flow home address
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeeHomeAddress(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        // validation
        if (!$post['street_1']) {
            $errorsArray[] = '"Street 1" is missing.';
        }
        if (!$post['city']) {
            $errorsArray[] = '"City" is missing.';
        }
        if (!$post['state']) {
            $errorsArray[] = '"State" is missing.';
        }
        if (!$post['zip']) {
            $errorsArray[] = '"Zip" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                404,
                ['errors' => $errorsArray]
            );
        }
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                404,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }

        // get the job
        $gustoHomeAddress = $this->db
            ->where('employee_sid', $employeeId)
            ->where('gusto_home_address_uuid is not null', null)
            ->count_all_results('gusto_companies_employees');
        //
        $method = !$gustoHomeAddress ? 'createEmployeeHomeAddress' : 'updateEmployeeHomeAddress';
        // let's update employee's home address
        $response = $this->payroll_model
            ->$method(
                $employeeId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                404,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated compensation.'
            ]
        );
    }

    /**
     * generate error based on session
     *
     * @param mixed $session
     * @return
     */
    private function checkSessionStatus($session)
    {
        if (!$session) {
            return SendResponse(
                401,
                [
                    'errors' => ['Access denied. Please login to access this route']
                ]
            );
        }
    }
}
