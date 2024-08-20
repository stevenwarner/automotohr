<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    /**
     * for js
     */
    private $js;
    /**
     * for css
     */
    private $css;
    /**
     * wether to create minified files or not
     */
    private $createMinifyFiles;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // set default form messages
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_email', '"{field}" is invalid.');
        // Call the model
        $this->load->model("v1/Payroll_model", "payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/';
        //
        $this->createMinifyFiles = true;
    }

    public function dashboard()
    {
        // check for linked company
        $this->checkForLinkedCompany();
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        $data['title'] = "Payroll Dashboard";
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
        // load regular payroll model
        $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");
        // load external payroll model
        $this->load->model("v1/External_payroll_model", "external_payroll_model");
        // load history payroll model
        $this->load->model("v1/History_payroll_model", "history_payroll_model");
        // Call the model
        $this->load->model("v1/Company_benefits_model", "company_benefits_model");
        //
        $this->load->model("v1/Pay_stubs_model", "pay_stubs_model");
        // get the payrolls
        $data['regularPayrolls'] = $this->regular_payroll_model
            ->getRegularPayrolls(
                $companyId,
                5
            );
        // get the processed payrolls
        $data['payrolls'] = $this
            ->history_payroll_model
            ->getProcessedRegularPayrolls(
                $companyId,
                10
            );
        // get the processed off cycle payrolls
        $data['offCyclePayrolls'] = $this
            ->history_payroll_model
            ->getProcessedOffcyclePayrolls(
                $companyId,
                10
            );
        // get all external payrolls
        $data['externalPayrolls'] =
            $this->external_payroll_model
            ->getAllCompanyExternalPayrolls(
                $companyId,
                3
            );
        //
        $data['payrollEmployees'] = $this->payroll_model->getPayrollEmployees($companyId, false, 5);
        // get store benefits
        $data['benefits'] = $this->company_benefits_model
            ->getBenefits(
                $companyId,
                5
            );
        // get company bank account
        $data['bankAccount'] = $this->payroll_model->getCompanyBankAccount(
            $companyId
        );
        // get company pay stubs
        $data['payStubs'] = $this->pay_stubs_model->getCompanyPayStubs(
            $companyId,
            5
        );

        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/dashboard')
            ->view('main/footer');
    }


    public function setup()
    {
        // check for linked company
        $this->checkForLinkedCompany();
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        $data['title'] = "Payroll Set up";
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
        $data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/payroll/js/dashboard'
        ], $this->js, 'dashboard', $this->createMinifyFiles);
        //
        $data['companyGustoDetails'] = $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls']);
        //
        $data['companyStatus'] = $companyGustoDetails['status'];
        // get the company onboard flow
        $data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "
                    select_industry,
                    add_bank_info,
                    verify_bank_info,
                    federal_tax_setup,
                    state_setup,
                    sign_all_forms
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
        $this->payroll_model
            ->handleInitialEmployeeOnboard(
                $data['session']['company_detail']['sid']
            );
        $data["companyOnProduction"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode");
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/setup')
            ->view('main/footer');
    }


    /**
     * Manage admins
     */
    public function manageAdmins()
    {
        // check for linked company
        $this->checkForLinkedCompany();
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
        $data['title'] = "Payroll Admins";
        // get admins
        $data['admins'] = $this->db
            ->where([
                'company_sid' => $data['loggedInPersonCompany']['sid'],
                'is_store_admin' => 0
            ])
            ->order_by('sid', 'DESC')
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
        // check for linked company
        $this->checkForLinkedCompany();
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
        $data['title'] = "Add Payroll Admin";
        // scripts
        $data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/payroll/js/admin/add'
        ], $this->js, 'add-admin', $this->createMinifyFiles);
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
        // check for linked company
        $this->checkForLinkedCompany();
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
        $data['title'] = "Payroll Signatories";
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
        // check for linked company
        $this->checkForLinkedCompany();
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
        // get all active employees
        $data['employees'] = $this->payroll_model->getSystemEmployees(
            $data['loggedInPersonCompany']['sid']
        );
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        $data['title'] = "Add Payroll Signatory";
        // scripts
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'add-signatory',
            $this->createMinifyFiles
        );
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/signatories/create'
            ],
            $this->js,
            'add-signatory',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/signatories/create')
            ->view('main/footer');
    }

    /**
     * Manage earnings
     */
    public function earningTypes()
    {
        // check for linked company
        $this->checkForLinkedCompany();
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
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'earning-types'
        );
        // js
        $data['appJs'] =
            bundleJs([
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/earnings/manage'
            ], $this->js, 'earning-types', $this->createMinifyFiles);
        // get admins
        $data['earnings'] = $this->payroll_model
            ->getCompanyEarningTypes(
                $data['loggedInPersonCompany']['sid']
            );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/earnings/manage')
            ->view('main/footer');
    }

    /**
     * Manage employees
     */
    public function manageEmployees()
    {
        // check for linked company
        $this->checkForLinkedCompany();
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $companyId = $data['session']['company_detail']['sid'];
        $data['company_sid'] = $companyId;
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
        // styles
        $data['appCSS'] = bundleCSS([
            'v1/plugins/ms_modal/main',
            'v1/app/css/loader',
        ], $this->css, 'employee-onboard');
        // scripts
        $data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/plugins/ms_modal/main',
            'v1/payroll/js/employees/add',
            'v1/payroll/js/employees/manage',
            'v1/payroll/js/employees/garnishments',
        ], $this->js, 'employee-onboard', $this->createMinifyFiles);
        // get employees
        $data['payrollEmployees'] = $this->payroll_model->getPayrollEmployees($companyId);
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/employees/manage')
            ->view('main/footer');
    }

    /**
     * contractor
     */
    public function manageContractors()
    {
        // check for linked company
        $this->checkForLinkedCompany();
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $companyId = $data['session']['company_detail']['sid'];
        //
        $data['title'] = "Manage Payroll Contractors";
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
            'v1/payroll/js/contractors/manage'
        ];
        // get contractors
        $data['payrollContractors'] = $this->payroll_model->getPayrollContractors($companyId);
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/contractors/view')
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
        // check for linked company
        $this->checkForLinkedCompany(true);
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
        // check for linked company
        $this->checkForLinkedCompany(true);
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
        // check for linked company
        $this->checkForLinkedCompany(true);
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
        // check for linked company
        $this->checkForLinkedCompany(true);
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
     * Verify bank account
     * Only available on demo mode
     *
     * @return
     */
    public function verifyCompany(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($session['company_detail']['sid']);
        // verify call
        $gustoResponse = gustoCall(
            'verifyCompany',
            $companyDetails,
            [],
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($gustoResponse['company_status'] === 'Approved') {
            $this->db
                ->where('company_sid', $session['company_detail']['sid'])
                ->update('gusto_companies', ['status' => 'approved']);
        }
        //
        return SendResponse(
            $errors ? 400 : 200,
            $errors ?? ['msg' => 'Company is successfully verified']
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
            // get system employees
            $employees = $this->payroll_model->getActiveEmployees($companyId);
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
        // check for linked company
        // $this->checkForLinkedCompany(true);

        if (isCompanyOnBoard($companyId)) {
            // set
            $data = [];
            // check if the contract is signed
            $data['agreement'] = $this->db
                ->select('is_ts_accepted, ts_email, ts_ip')
                ->where('company_sid', $companyId)
                ->get('gusto_companies')
                ->row_array();
            // get company's dmins
            $data['admins'] = $this->db
                ->select('email_address, automotohr_reference')
                ->where('company_sid', $companyId)
                ->where('is_store_admin', 0)
                ->get('gusto_companies_admin')
                ->result_array();
            //
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/create_partner_company/agreement', $data, true)
                ]
            );
        }
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
        //
        if ($companyDetails['employee_ids']) {
            // get the employee list
            $ids = explode(',', $companyDetails['employee_ids']);
            //
            foreach ($ids as $employeeId) {
                // selected employees
                $this->payroll_model->onboardEmployee($employeeId, $companyId);
            }
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
            // get employee summary
            $data['summary'] = $this->payroll_model->getEmployeeSummary($employeeId);
            //
            if ($data['summary']['response']['onboarding_status'] == 'onboarding_completed') {
                //
                $this->db
                    ->where('employee_sid', $employeeId)
                    ->update(
                        'gusto_companies_employees',
                        [
                            'is_onboarded' => 1,
                            'personal_details' => 1,
                            'compensation_details' => 1,
                            'work_address' => 1,
                            'home_address' => 1,
                            'federal_tax' => 1,
                            'state_tax' => 1,
                            'updated_at' => getSystemDate()
                        ]
                    );
            }
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
            //
            if ($data['primaryJob']['compensation']['adjust_for_minimum_wage'] == 1 && !empty($data['primaryJob']['compensation']['minimum_wages'])) {
                $minimumWages = unserialize($data['primaryJob']['compensation']['minimum_wages']);
                $selectedWages = array_column($minimumWages, 'uuid');
                $data['selectedWages'] = $selectedWages;
            }
            //
            $data['minimumWages'] = $this->payroll_model
                ->getCompanyMinimumWages(
                    $employeeId
                );
            //
        } elseif ($step === 'home_address') {
            //
            $data['record'] = $this->payroll_model
                ->getEmployeeHomeAddress(
                    $employeeId
                );
        } elseif ($step === 'federal_tax') {
            // get federal tax record
            $data['record'] = $this->payroll_model
                ->getEmployeeFederalTax(
                    $employeeId
                );
        } elseif ($step === 'state_tax') {
            // get federal tax record
            $data['record'] = $this->payroll_model
                ->getEmployeeStateTax(
                    $employeeId
                );
        } elseif ($step === 'payment_method') {
            //
            $this->payroll_model->syncEmployeePaymentMethodFromGusto($employeeId);
            // get payment method
            $data['record'] = $this->payroll_model
                ->getEmployeePaymentMethod(
                    $employeeId
                );
            //
            $data['bankAccounts'] = $this->payroll_model
                ->getEmployeeBankAccountsById(
                    $employeeId,
                    false
                );
        } elseif ($step === 'documents') {
            //
            $this->payroll_model->syncEmployeeDocuments($employeeId);
            //
            $data['documents'] = $this->payroll_model->getEmployeeDocuments($employeeId);
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
                400,
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
                400,
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
                400,
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
                400,
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
                400,
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
        // _e($post,true,true);    
        //
        if ($response['errors']) {
            return SendResponse(
                400,
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
                400,
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
                400,
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
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated home address.'
            ]
        );
    }

    /**
     * employee onboard flow home address
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeeFederalTax(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        // validation
        if (!$post['filing_status']) {
            $errorsArray[] = '"Filing status" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
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
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        // get the job
        $gustoFederalTax = $this->db
            ->where('employee_sid', $employeeId)
            ->count_all_results('gusto_employees_federal_tax');
        //
        $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
        // let's update employee's home address
        $response = $this->payroll_model
            ->$method(
                $employeeId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated federal tax.'
            ]
        );
    }

    /**
     * employee onboard flow state tax
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeeStateTax(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        } else {
            //
            $record = $this->db
                ->select('questions_json, state_code')
                ->where('employee_sid', $employeeId)
                ->get('gusto_employees_state_tax')
                ->row_array();
            // get the state questions
            $questionsObj = json_decode($record['questions_json'], true);
            //
            $tmp = [];
            //
            foreach ($questionsObj as $question) {
                $tmp[$question['key']] = $question;
            }
            //
            $questionsObj = $tmp;
            unset($tmp);

            foreach ($post as $index => $value) {
                //
                if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && $value < 0) {
                    $errorsArray[] = '"' . ($questionsObj[$index]['label']) . '" can not be less than 0.';
                }
                //
                if ($questionsObj[$index]['input_question_format']['type'] !== 'Select' && !$value) {
                    $value = 0;
                } elseif ($questionsObj[$index]['input_question_format']['type'] === 'Select') {
                    $value = $value == 'yes' ? "true" : $value;
                    $value = $value == 'no' ? "false" : $value;
                }
                //
                if ($questionsObj[$index]['answers'][0]['value']) {
                    $questionsObj[$index]['answers'][0]['value'] = $value;
                } else {
                    $questionsObj[$index]['answers'] = [['value' => $value, 'valid_from' => '2010-01-01']];
                }
            }
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
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
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        // let's update employee's state tax
        $response = $this->payroll_model
            ->updateEmployeeStateTax(
                $employeeId,
                ['state' => $record['state_code'], 'questions' => array_values($questionsObj)]
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated state tax.'
            ]
        );
    }

    /**
     * employee onboard flow bank account
     *
     * @param int    $employeeId
     * @return json
     */
    public function addEmployeeBankAccount(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        if (!$post['accountTitle']) {
            $errorsArray[] = '"Account title" is missing.';
        }
        if (!$post['routingNumber']) {
            $errorsArray[] = '"Routing number" is missing.';
        }
        if (strlen($post['routingNumber']) != 9) {
            $errorsArray[] = '"Routing number" must be 9 digits long.';
        }
        if (!$post['accountNumber']) {
            $errorsArray[] = '"Account number" is missing.';
        }
        if (!$post['accountType']) {
            $errorsArray[] = '"Account type" is missing.';
        }
        if (!in_array($post['accountType'], ['Checking', 'Savings'])) {
            $errorsArray[] = '"Account type" can either be "Checking" or "Savings".';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        // get account id
        $post['sid']  = $this->payroll_model
            ->getEmployeeBankAccountId(
                $employeeId,
                $gustoEmployee['company_sid'],
                $post
            );
        //
        if ($post['sid'] == 0) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        'You can add a maximum of two bank accounts.'
                    ]
                ]
            );
        }
        // let's add employee's bank account
        $response = $this->payroll_model
            ->addEmployeeBankAccountToGusto(
                $employeeId,
                [
                    'account_title' => $post['accountTitle'],
                    'routing_transaction_number' => $post['routingNumber'],
                    'account_number' => $post['accountNumber'],
                    'account_type' => $post['accountType'],
                    'sid' => $post['sid'],
                ]
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully added a bank account.'
            ]
        );
    }

    /**
     * employee onboard flow payment method
     *
     * @param int    $employeeId
     * @return json
     */
    public function updateEmployeePaymentMethod(int $employeeId): array
    {
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        if (!$post['paymentType']) {
            $errorsArray[] = '"Payment method" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        //
        $accounts = [];
        //
        if ($post['paymentType'] == 'Direct Deposit') {
            // get account id
            $accounts = $this->payroll_model
                ->getEmployeeBankAccountsByIdWithGusto(
                    $employeeId
                );
            //
            if (!$accounts) {
                return SendResponse(
                    400,
                    [
                        'errors' => ['Please add a bank account before changing the payment method to "Direct deposit".']
                    ]
                );
            }
        }
        //
        $post['accounts'] = $accounts;
        // let's update employee's payment method
        $response = $this->payroll_model
            ->updateEmployeePaymentMethodToGusto(
                $employeeId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }
        //
        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated the payment method.'
            ]
        );
    }

    /**
     * employee onboard flow payment method
     *
     * @param int $employeeId
     * @param int $bankId
     * @return json
     */
    public function deleteBankAccount(int $employeeId, int $bankId): array
    {
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        // get employee bank accounts
        $bankAccount = $this->db
            ->select('
            gusto_uuid
        ')
            ->where([
                'sid' => $bankId
            ])
            ->get('bank_account_details')
            ->row_array();
        // let's update employee's payment method
        $response = $this->payroll_model
            ->deleteEmployeeBankAccountToGusto(
                $employeeId,
                [
                    'gusto_uuid' => $bankAccount['gusto_uuid']
                ]
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }
        //
        $this->db
            ->where([
                'sid' => $bankId
            ])
            ->update('bank_account_details', ['gusto_uuid' => null]);
        //
        return SendResponse(
            200,
            [
                'msg' => 'You have successfully deleted a bank account.'
            ]
        );
    }

    /**
     * employee onboard flow payment method
     *
     * @param int $employeeId
     * @param int $bankId
     * @return json
     */
    public function useBankAccount(int $employeeId, int $bankId): array
    {
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        // let's update employee's payment method
        $response = $this->payroll_model
            ->useEmployeeSingleBankAccount(
                $employeeId,
                $bankId
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }
        //
        return SendResponse(
            200,
            [
                'msg' => 'You have successfully linked the bank account.'
            ]
        );
    }

    /**
     * employee onboard flow payment method
     *
     * @param int $employeeId
     * @return json
     */
    public function finishOnboard(int $employeeId): array
    {
        // get gusto employee details
        $gustoEmployee = $this->payroll_model
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        if (!$gustoEmployee) {
            return SendResponse(
                400,
                ['errors' => 'The selected employee is not on payroll.']
            );
        }
        $summary = $this->payroll_model->getEmployeeSummary($employeeId);
        //
        if ($summary['response']['onboarding_status'] == 'onboarding_completed') {
            //
            $this->db
                ->where('employee_sid', $employeeId)
                ->update(
                    'gusto_companies_employees',
                    [
                        'is_onboarded' => 1,
                        'personal_details' => 1,
                        'compensation_details' => 1,
                        'work_address' => 1,
                        'home_address' => 1,
                        'federal_tax' => 1,
                        'state_tax' => 1,
                        'updated_at' => getSystemDate()
                    ]
                );
            return SendResponse(
                200,
                ['msg' => 'You have successfully onboard an employee for payroll.']
            );
        } else {
            return SendResponse(
                200,
                [
                    'view' => $this->load->view('v1/payroll/', $summary, true)
                ]
            );
        }
    }

    /**
     * add contractor view
     */
    public function addContractor()
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/contractors/add', [], true)
            ]
        );
    }
    /**
     * contractor onboard flow
     *
     * @param int    $contractorId
     * @param string $step
     * @param int    $formId Optional
     * @return json
     */
    public function contractorFlow(int $contractorId, string $step, int $formId = 0): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // set data
        $data = [];
        $data['step'] = $step;
        // check the status
        $this->payroll_model->syncContractor($contractorId);
        $data['onboard'] = $this->payroll_model->checkContractorOnboard($contractorId);
        // for summary page
        if ($step === 'personal_details') {
            $this->payroll_model->syncContractor($contractorId);
            // get the specific contractor
            $data['contractor'] = $this->payroll_model->getContractorById($contractorId);
        } elseif ($step === 'home_address') {
            // get the specific contractor
            $data['home_address'] = $this->payroll_model->getContractorById($contractorId, [
                'street_1',
                'street_2',
                'city',
                'state',
                'zip',
                'gusto_address_version',
            ]);
            //
            $this->payroll_model->syncContractorHomeAddress($contractorId);
        } elseif ($step === 'payment_method') {
            // get the specific contractor
            $data['payment_method'] = $this->payroll_model->getContractorById($contractorId, [
                'payment_method_type',
                'splits_by',
                'splits',
                'gusto_payment_method_version'
            ]);
            //
            $this->payroll_model->syncContractorPaymentMethod($contractorId);
            if ($data['payment_method']['payment_method_type'] === 'Direct Deposit') {
                $data['bank'] = $this->payroll_model->getContractorBankAccount($contractorId);
            }
        } elseif ($step === 'documents') {
            //
            $this->payroll_model->syncContractorDocuments($contractorId);
            // get the specific contractor
            $data['documents'] = $this->payroll_model->getContractorDocuments($contractorId);
        } elseif ($step === 'single_form') {
            // get the specific contractor
            $data['document'] = $this->payroll_model
                ->getContractorDocument(
                    $contractorId,
                    $formId
                );
        }
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/contractors/flow_edit', $data, true)
            ]
        );
    }

    /**
     * contractor creation
     *
     * @return json
     */
    public function processAddContractor(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        //
        if (!$post['type']) {
            $errorsArray[] = '"Type" is missing.';
        }
        //
        if (!$post['wageType']) {
            $errorsArray[] = '"Wage type" is missing.';
        }
        //
        if (!$post['startDate']) {
            $errorsArray[] = '"Start date" is missing.';
        }
        //
        if ($post['email'] && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errorsArray[] = '"Email" is invalid.';
        }
        //
        if ($post['type'] && $post['type'] === 'Individual') {
            //
            if (!$post['firstName']) {
                $errorsArray[] = '"First name" is missing.';
            }
            //
            if (!$post['lastName']) {
                $errorsArray[] = '"Last name" is missing.';
            }
            //
            if ($post['fileNewHireReport'] && !$post['workState']) {
                $errorsArray[] = '"Work state" is missing.';
            }
            //
            if (!$post['ssn'] || !preg_match('/\d{9}/', preg_replace('/\D/', '', $post['ssn']))) {
                $errorsArray[] = '"Social Security Number (SSN)" is missing / invalid.';
            }
        } elseif ($post['type'] && $post['type'] === 'Business') {
            //
            if (!$post['businessName']) {
                $errorsArray[] = '"Business name" is missing.';
            }
            //
            if (!$post['ein'] || strlen(preg_replace('/\D/', '', $post['ein'])) !== 9) {
                $errorsArray[] = '"EIN" is missing / invalid.';
            }
        }
        //
        if ($post['wageType'] === 'Hourly' && !$post['hourlyRate']) {
            $errorsArray[] = '"Hourly rate" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's add contractor
        $response = $this->payroll_model
            ->addContractor($session['company_detail']['sid'], $post);
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully added a contractor.'
            ]
        );
    }

    /**
     * contractor modification
     *
     * @param int $contractorId
     * @return json
     */
    public function updateContractorPersonalDetails(int $contractorId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        //
        if (!$post['type']) {
            $errorsArray[] = '"Type" is missing.';
        }
        //
        if (!$post['wageType']) {
            $errorsArray[] = '"Wage type" is missing.';
        }
        //
        if (!$post['startDate']) {
            $errorsArray[] = '"Start date" is missing.';
        }
        //
        if ($post['email'] && !filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
            $errorsArray[] = '"Email" is invalid.';
        }
        //
        if ($post['type'] && $post['type'] === 'Individual') {
            //
            if (!$post['firstName']) {
                $errorsArray[] = '"First name" is missing.';
            }
            //
            if (!$post['lastName']) {
                $errorsArray[] = '"Last name" is missing.';
            }
            //
            if ($post['fileNewHireReport'] && !$post['workState']) {
                $errorsArray[] = '"Work state" is missing.';
            }
            //
            if (
                !$post['ssn'] || (!preg_match(
                    '/\d{9}/',
                    preg_replace('/\D/', '', $post['ssn'])
                ) && !preg_match('/x/i', $post['ssn']))
            ) {
                $errorsArray[] = '"Social Security Number (SSN)" is missing / invalid.';
            }
        } elseif ($post['type'] && $post['type'] === 'Business') {
            //
            if (!$post['businessName']) {
                $errorsArray[] = '"Business name" is missing.';
            }
            //
            if (
                !$post['ein'] || (strlen(preg_replace('/\D/', '', $post['ein'])) !== 9 && !preg_match('/x/i', $post['ein']))

            ) {
                $errorsArray[] = '"EIN" is missing / invalid.';
            }
        }
        //
        if ($post['wageType'] === 'Hourly' && !$post['hourlyRate']) {
            $errorsArray[] = '"Hourly rate" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's modify contractor
        $response = $this->payroll_model
            ->updateContractor(
                $contractorId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        //
        $this->payroll_model->checkAndUpdateContractorOnboard($contractorId);

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated the contractor.'
            ]
        );
    }

    /**
     * contractor modification
     *
     * @param int $contractorId
     * @return json
     */
    public function updateContractorHomeAddress(int $contractorId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        //
        if (!$post['street_1']) {
            $errorsArray[] = '"Street 1" is missing.';
        }
        //
        if (!$post['city']) {
            $errorsArray[] = '"City" is missing.';
        }
        //
        if (!$post['state']) {
            $errorsArray[] = '"State" is missing.';
        }
        //
        if (!$post['zip']) {
            $errorsArray[] = '"Zip" is missing.';
        }
        //
        if (strlen($post['zip']) !== 5) {
            $errorsArray[] = '"Zip" must be of 9 digits long.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's modify contractor
        $response = $this->payroll_model
            ->updateContractorHomeAddress(
                $contractorId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }
        //
        $this->payroll_model->checkAndUpdateContractorOnboard($contractorId);

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated the contractors address.'
            ]
        );
    }

    /**
     * contractor modification
     *
     * @param int $contractorId
     * @return json
     */
    public function updateContractorPaymentMethod(int $contractorId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get post
        $post = $this->input->post(null, true);
        // set error array
        $errorsArray = [];
        //
        if (!$post) {
            $errorsArray[] = '"Data fields" are missing.';
        }
        //
        if (!$post['type']) {
            $errorsArray[] = '"Type" is missing.';
        }
        //
        if ($post['type'] === 'Direct Deposit') {
            //
            if (!$post['accountName']) {
                $errorsArray[] = '"Account name" is missing.';
            }
            //
            if (strlen($post['routingNumber']) != 9) {
                $errorsArray[] = '"Routing number" is missing.';
            }
            //
            if (strlen($post['accountNumber']) != 9) {
                $errorsArray[] = '"Account number" is missing.';
            }
            //
            if (!$post['accountType']) {
                $errorsArray[] = '"Account type" is missing.';
            }
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's modify contractor
        $response = $this->payroll_model
            ->updateContractorPaymentMethod(
                $contractorId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }
        //
        $this->payroll_model->checkAndUpdateContractorOnboard($contractorId);

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated the contractors payment method.'
            ]
        );
    }

    /**
     * Custom earning type
     *
     * @param int $earningId
     * @return json
     */
    public function deactivateCustomEarningType(int $earningId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        $errorsArray = [];
        // verify the earning type
        if (!$this->payroll_model->checkCompanyEarningType($earningId, $session['company_detail']['sid'])) {
            $errorsArray[] = '"Earning type" doesn\'t belong to this company.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's deactivate company
        $response = $this->payroll_model
            ->deactivateCompanyEarningType(
                $earningId
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully deactivated the earning type.'
            ]
        );
    }

    /**
     * Custom earning type
     *
     * @return json
     */
    public function addCustomEarningType(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);

        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/payroll/earnings/add',
                    [],
                    true
                )
            ]
        );
    }

    /**
     * Custom earning type
     *
     * @param int $earningId
     * @return json
     */
    public function editCustomEarningType(int $earningId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        $data = [];
        // get the earning type
        $data['earning'] = $this->payroll_model
            ->getSingleEarning(
                $earningId,
                $session['company_detail']['sid']
            );
        $data["earning"] = array_merge(
            $data["earning"],
            $data["earning"]["fields_json"] ?
                json_decode(
                    $data["earning"]["fields_json"],
                    true
                ) :
                []
        );
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/payroll/earnings/edit',
                    $data,
                    true
                )
            ]
        );
    }
    /**
     * Custom earning type
     *
     * @return json
     */
    public function processAddCustomEarningType(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        $post = $this->input->post(null, true);
        //
        $errorsArray = [];
        //
        if (!$post['name']) {
            $errorsArray[] = '"Name" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's deactivate company
        $response = $this->payroll_model
            ->addCompanyEarningType(
                $session['company_detail']['sid'],
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully add an earning type.'
            ]
        );
    }

    /**
     * Custom earning type
     *
     * @param int $earningId
     * @return json
     */
    public function processEditCustomEarningType(int $earningId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        $post = $this->input->post(null, true);
        //
        $errorsArray = [];
        //
        if (!$post['name']) {
            $errorsArray[] = '"Name" is missing.';
        }
        //
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        // let's update earning
        $response = $this->payroll_model
            ->editCompanyEarningType(
                $earningId,
                $post
            );
        //
        if ($response['errors']) {
            return SendResponse(
                400,
                $response
            );
        }

        return SendResponse(
            200,
            [
                'msg' => 'You have successfully updated earning type.'
            ]
        );
    }

    /**
     * Get employees for payroll
     *
     * @param int $companyId
     * @return json
     */
    public function getEmployeesForPayroll(int $companyId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // get all employees
        $employees = $this->payroll_model->getEmployeesForPayroll(
            $companyId
        );
        return SendResponse(
            200,
            [
                'view' => $this->load->view('v1/payroll/employees/employees_list', [
                    'employees' => $employees
                ], true)
            ]
        );
    }

    /**
     * Get employees for payroll
     *
     * @param int $employeeId
     * @return json
     */
    public function onboardEmployee(int $employeeId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // verify employee and get the employee companyId
        $response = $this->payroll_model->onboardEmployee($employeeId, $session['company_detail']['sid']);
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * Get employees for payroll
     *
     * @param int $employeeId
     * @return json
     */
    public function removeEmployee(int $employeeId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        // verify employee and get the employee companyId
        $response = $this->payroll_model->removeEmployee($employeeId, $session['company_detail']['sid']);
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * Get employees for payroll
     *
     * @return json
     */
    public function updateSettings(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check for session out
        $this->checkSessionStatus($session);
        //
        $paymentSpeed = $this->input->post('paymentSpeed', true);
        //
        $response = $this->payroll_model
            ->updatePaymentConfig(
                $session['company_detail']['sid'],
                [
                    'payment_speed' => $paymentSpeed,
                    'fast_payment_limit' => FAST_PAYMENT_LIMIT
                ]
            );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
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
                    'errors' => ['Access denied. Please login to access this route.']
                ]
            );
        }
    }

    /**
     * check if company is synced with Gusto
     */
    private function checkForLinkedCompany($isAJAX = false)
    {
        // check if module is active
        if (!isCompanyOnBoard($this->session->userdata('logged_in')['company_detail']['sid'])) {
            //
            if ($isAJAX) {
                return SendResponse(
                    400,
                    [
                        'errors' => ['Company is not set-up for payroll.']
                    ]
                );
            }
            // set message
            $this->session->set_flashdata('message', 'Access denied!');
            // redirect
            return redirect('dashboard');
        }
    }

    public function getSingleEmployee(int $employeeId): array
    {
        return SendResponse(
            200,
            $this->payroll_model->getEmployeeById($employeeId)
        );
    }

    public function refreshGustoOAuthToken(): array
    {
        //
        $URL = $this->payroll_model->regenerateAuthToken();
        //
        return SendResponse(
            200,
            [
                'msg' => 'Successfully send call to refresh token.',
                'url' => $URL,
            ]
        );
    }

    public function updateGustoOAuthToken()
    {
        //
        // Disable All previous demo code
        $this->db
            ->where('is_production', 0)
            ->update(
                'gusto_authorization',
                [
                    'status' => 0
                ]
            );
        //
        // Update current Authorization request
        $this->db
            ->where('state', $_GET['state'])
            ->update(
                'gusto_authorization',
                [
                    'status' => 1,
                    'code' => $_GET['code'],
                    'updated_at' => getSystemDate()
                ]
            );
    }

    //
    public function ledger($start_date = 'all', $end_date = 'all', $employee = 'all', $department = 'all', $jobtitles = 'all', $dateSelection = 'transaction', $page_number = 1)
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Employees Termination Reports';
            //

            $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");

            $data["allemployees"] = $this->regular_payroll_model->getCompanyEmployeesOnly(
                $company_sid
            );
            $data["company_sid"] = $company_sid;


            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            $filterEmployees = explode(',', urldecode($employee));
            $filterJobTitles = explode(',', urldecode($jobtitles));
            $filterDepartment = explode(',', urldecode($department));

            //
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
            } else {
                $end_date_applied = date('Y-m-d');
            }
            //
            $between = '';
            //
            if ($start_date_applied != NULL && $end_date_applied != NULL) {
                if ($dateSelection == 'transaction') {
                    $between = "payroll_ledger.transaction_date >= '" . $start_date_applied . "' and payroll_ledger.transaction_date <=  '" . $end_date_applied . "'";
                } else {
                    $between = "payroll_ledger.start_date = '" . $start_date_applied . "' and payroll_ledger.end_date = '" . $end_date_applied . "'";
                }
            }

            //
            $data["flag"] = true;
            $data['ledgerCount'] = sizeof($this->regular_payroll_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, null, null));
            /** pagination * */
            $this->load->library('pagination');
            $records_per_page =  PAGINATION_RECORDS_PER_PAGE;
            $my_offset = 0;
            //
            if ($page_number > 1) {
                $my_offset = ($page_number - 1) * $records_per_page;
            }
            //
            $baseUrl = base_url('payrolls/ledger') . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($employee) . '/' . urldecode($department) . '/' . urldecode($jobtitles) . '/' . $dateSelection;
            //
            $uri_segment = 9;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $data['ledgerCount'];
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = ceil($choice);
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $data['page_links'] = $this->pagination->create_links();
            $total_records = $data['terminatedEmployeesCount'];

            $data['current_page'] = $page_number;
            $data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
            $data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;


            $data['employeesLedger'] = $this->regular_payroll_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, $records_per_page, $my_offset);


            //
            if (sizeof($this->input->post(NULL, TRUE))) {

                $additionalHeader = [];
                if ($this->input->post('employee_sid')) {
                    $additionalHeader['employee_id'] = 'Employee ID';
                }
                if ($this->input->post('first_name')) {
                    $additionalHeader['first_name'] = "First Name";
                }
                if ($this->input->post('middle_name')) {
                    $additionalHeader['middle_name'] = "Middle Name";
                }
                if ($this->input->post('last_name')) {
                    $additionalHeader['last_name'] = "Last Name";
                }
                if ($this->input->post('job_title')) {
                    $additionalHeader['job_title'] = "Job Title";
                }
                if ($this->input->post('department')) {
                    $additionalHeader['department'] = "Department";
                }
                if ($this->input->post('team')) {
                    $additionalHeader['team'] = "Team";
                }

                $additionalHeader['debit_amount'] = 'Debit Amount';
                $additionalHeader['credit_amount'] = 'Credit Amount';
                $additionalHeader['gross_pay'] = 'Gross Pay';
                $additionalHeader['net_pay'] = 'Net Pay';
                $additionalHeader['taxes'] = 'Taxes';
                $additionalHeader['description'] = 'Description';
                $additionalHeader['transaction_date'] = 'Transaction Date';
                $additionalHeader['start_date'] = 'Stard Date';
                $additionalHeader['end_date'] = 'End Date';
                $additionalHeader['created_at'] = 'Imported At';

                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=employees_ledger_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
                $output = fopen('php://output', 'w');

                fputcsv($output, array($data['session']['company_detail']['CompanyName'], '', '', ''));

                fputcsv($output, array(
                    "Exported By",
                    $data['session']['employer_detail']['first_name'] . " " . $data['session']['employer_detail']['last_name']
                ));
                fputcsv($output, array(
                    "Export Date",
                    date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR
                ));
                fputcsv($output, array("",));

                fputcsv($output, $additionalHeader);

                if (!empty($data['employeesLedger'])) {
                    foreach ($data['employeesLedger'] as $ledgerRow) {

                        $input = array();

                        $teamDepartment = [];

                        if ($additionalHeader['employee_id']) {
                            $input['employee_sid'] = $ledgerRow['employee_sid'];
                        }
                        if ($additionalHeader['first_name']) {
                            $input['first_name'] = $ledgerRow['first_name'];
                        }
                        if ($additionalHeader['middle_name']) {
                            $input['middle_name'] = $ledgerRow['middle_name'];
                        }
                        if ($additionalHeader['last_name']) {
                            $input['last_name'] = $ledgerRow['last_name'];
                        }
                        if ($additionalHeader['job_title']) {
                            $input['job_title'] = $ledgerRow['job_title'];
                        }
                        if ($additionalHeader['department'] || $additionalHeader['team']) {

                            $teamDepartment = $ledgerRow['employee_sid'] != null ? getEmployeeDepartmentAndTeams($ledgerRow['employee_sid']) : '';
                        }
                        if ($additionalHeader['department']) {
                            $departments = !empty($teamDepartment['departments']) ? implode(',', array_column($teamDepartment['departments'], 'name')) : '';
                            $input['department'] = $departments;
                        }
                        if ($additionalHeader['team']) {
                            $teams = !empty($teamDepartment['teams']) ? implode(',', array_column($teamDepartment['teams'], 'name')) : '';
                            $input['team'] = $teams;
                        }

                        $input['debit_amount'] = $ledgerRow['debit_amount'] ? _a($ledgerRow['debit_amount']) : '-';
                        $input['credit_amount'] = $ledgerRow['credit_amount'] ? _a($ledgerRow['credit_amount']) : '-';
                        $input['gross_pay'] = $ledgerRow['gross_pay'] ? _a($ledgerRow['gross_pay']) : '-';
                        $input['net_pay'] = $ledgerRow['net_pay'] ? _a($ledgerRow['net_pay']) : '-';
                        $input['taxes'] = $ledgerRow['taxes'] ? _a($ledgerRow['taxes']) : '-';
                        $input['description'] = preg_replace('/[^A-Za-z0-9\-]/', '', $ledgerRow['description']);
                        $input['transaction_date'] = formatDateToDB($ledgerRow['transaction_date'], DB_DATE, DATE);
                        $input['start_date'] = formatDateToDB($ledgerRow['start_date'], DB_DATE, DATE);
                        $input['end_date'] = formatDateToDB($ledgerRow['end_date'], DB_DATE, DATE);
                        $input['imported_at'] = formatDateToDB($ledgerRow['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                        fputcsv($output, $input);
                    }
                }

                fclose($output);
                exit;
            }

            //
            $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/ledger')
                ->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }
}
