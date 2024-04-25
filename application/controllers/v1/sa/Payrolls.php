<?php defined('BASEPATH') or exit('No direct script access allowed');

class Payrolls extends Admin_Controller
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


    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('v1/Payroll_model', 'payroll_model');
        // set path to CSS file
        $this->css = 'public/v1/sa/css/payrolls/';
        // set path to JS file
        $this->js = 'public/v1/sa/js/payrolls/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * main page
     *
     * @param int $companyId
     */
    public function index(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        //
        $this->data["mode"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "Production" : "Demo";
        $this->data['primaryAdmin'] = $this->payroll_model->getCompanyPrimaryAdmin($companyId);
        //
        $this->data['companyOnboardingStatus'] = $this->payroll_model->getCompanyOnboardingStatus($companyId);
        //
        if ($this->data['companyOnboardingStatus'] != 'Not Connected') {
            //
            $this->data['companyPaySchedules'] = $this->payroll_model->getCompanyPaySchedules($companyId);
            $this->data['companyTermsCondition'] = $this->payroll_model->getCompanyTermConditionInfo($companyId);
            $this->data['companyPaymentConfiguration'] = $this->payroll_model->getCompanyPaymentConfiguration($companyId);
            $this->data['companySignatories'] = $this->payroll_model->getCompanySignatoriesInfo($companyId);
            $this->data['companyBankInfo'] = $this->payroll_model->getCompanyBankInfo($companyId);
            $this->data['companyFederalTaxInfo'] = $this->payroll_model->getCompanyFederalTaxInfo($companyId);
            $this->data['companyEarningTypes'] = $this->payroll_model->getCompanyEarningTypesForDashboard($companyId);
            $this->data['companyIndustry'] = $this->payroll_model->getCompanySelectedIndustry($companyId);
            $this->data['companyEmployees'] = $this->payroll_model->getCompanyOnboardEmployees($companyId);
            //
            $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls', 'is_ts_accepted']);
            //
            $this->data['payrollBlockers'] = gustoCall(
                'getPayrollBlockers',
                $companyGustoDetails
            );
            //

        }
        // _e($this->data,true,true);

        // set title
        $this->data['page_title'] = 'Payroll dashboard :: ' . (STORE_NAME) . ' [' . getCompanyNameBySid($companyId) . ']';
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "css/theme-2021"
        ], $this->css, "admins", $this->createMinifyFiles);
        // set JS
        $this->data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/sa/payrolls/dashboard'
        ], $this->js, 'dashboard', $this->createMinifyFiles);
        // render the page
        $this->render('v1/sa/payrolls/dashboard', 'admin_master');
    }

    /**
     * main page
     *
     * @param int $companyId
     */
    public function setupCompanyPayroll(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        // get gusto details
        $this->data['companyGustoDetails'] = $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls', 'is_ts_accepted']);
        //
        $this->data["mode"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "Production" : "Demo";
        //
        // load set up page
        if (!$this->data["companyGustoDetails"]) {
            return $this->createPartnerCompany();
        }
        // load agreement page
        if (!$this->data["companyGustoDetails"]["is_ts_accepted"]) {
            return $this->agreement();
        }
        //
        $this->data['companyStatus'] = $companyGustoDetails['status'];
        // get the company onboard flow
        $this->data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "
                    select_industry,
                    add_bank_info,
                    verify_bank_info,
                    payroll_schedule,
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
        $this->data['payrollBlockers'] = gustoCall(
            'getPayrollBlockers',
            $companyGustoDetails
        );
        // set title
        $this->data['page_title'] = 'Payroll dashboard :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "css/theme-2021"
        ], $this->css, "admins", $this->createMinifyFiles);
        //
        // set JS
        $this->data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/sa/payrolls/dashboard'
        ], $this->js, 'dashboard', $this->createMinifyFiles);
        // render the page
        $this->render('v1/sa/payrolls/setup_payroll', 'admin_master');
    }


    /**
     * main page
     *
     * @param int $companyId
     */
    public function setupClair(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        // get gusto details
        $this->data['companyGustoDetails'] = $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls', 'is_ts_accepted']);
        //
        $this->data["mode"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "Production" : "Demo";
        //
        // load set up page
        if (!$this->data["companyGustoDetails"]) {
            return $this->createPartnerCompany();
        }
        // load agreement page
        if (!$this->data["companyGustoDetails"]["is_ts_accepted"]) {
            return $this->agreement();
        }
        //
        $this->data['companyStatus'] = $companyGustoDetails['status'];
        // get the company onboard flow
        $this->data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "company_earned_wage_access_enrollment",
                "entity_type" => "Company",
                "entity_uuid" => $companyGustoDetails['gusto_uuid']
            ],
            "POST"
        )['url'];

        // set title
        $this->data['page_title'] = 'Payroll Clair :: ' . (STORE_NAME);
        // render the page
        $this->render('v1/sa/payrolls/clair', 'admin_master');
    }

    /**
     * main page
     *
     * @param int $companyId
     */
    public function setupHealthInsurance(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        // get gusto details
        $this->data['companyGustoDetails'] = $companyGustoDetails = $this->payroll_model->getCompanyDetailsForGusto($companyId, ['status', 'added_historical_payrolls', 'is_ts_accepted']);
        //
        $this->data["mode"] = $this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "Production" : "Demo";
        //
        // load set up page
        if (!$this->data["companyGustoDetails"]) {
            return $this->createPartnerCompany();
        }
        // load agreement page
        if (!$this->data["companyGustoDetails"]["is_ts_accepted"]) {
            return $this->agreement();
        }
        //
        $this->data['companyStatus'] = $companyGustoDetails['status'];
        // get the company onboard flow
        $this->data['flow'] = gustoCall(
            'getCompanyOnboardFlow',
            $companyGustoDetails,
            [
                'flow_type' => "company_health_insurance",
                "entity_type" => "Company",
                "entity_uuid" => $companyGustoDetails['gusto_uuid']
            ],
            "POST"
        )['url'];
        // set title
        $this->data['page_title'] = 'Payroll Health Insurance :: ' . (STORE_NAME);
        // render the page
        $this->render('v1/sa/payrolls/health_insurance', 'admin_master');
    }

    /**
     * Manage admins
     */
    public function manageAdmins(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        // set
        $this->data['title']
            = 'Payroll admins :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "v1/plugins/ms_modal/main",
            "css/theme-2021"
        ], $this->css, "admins", $this->createMinifyFiles);
        // get admins
        $this->data['admins'] = $this->db
            ->where([
                'company_sid' => $companyId,
                'is_store_admin' => 0
            ])
            ->order_by('sid', 'DESC')
            ->get('gusto_companies_admin')
            ->result_array();
        //
        $this->render('v1/sa/payrolls/manage_admins', 'admin_master');
    }

    /**
     * add admins
     */
    public function addAdmin(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // set the company id
        $this->data['loggedInCompanyId'] = $companyId;
        // set
        $this->data['title']
            = 'Payroll add admin :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "v1/plugins/ms_modal/main",
            "css/theme-2021"
        ], $this->css, "admins", $this->createMinifyFiles);
        // scripts
        $this->data['appJs'] = bundleJs([
            'js/app_helper',
            'v1/sa/payrolls/add_admin'
        ], $this->js, 'add-admin', $this->createMinifyFiles);
        //
        $this->render('v1/sa/payrolls/add_admins', 'admin_master');
    }

    /**
     * create partner company
     */
    private function createPartnerCompany()
    {
        // $this->payroll_model->loadPayrollHelper($companyId);
        // set title
        $this->data['page_title'] = 'Payroll set-up :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "v1/plugins/ms_modal/main",
            "css/theme-2021"
        ], $this->css, "company-onboard", $this->createMinifyFiles);
        // set JS
        $this->data['appJs'] = bundleJs([
            'v1/plugins/ms_modal/main',
            'js/app_helper',
            'v1/payroll/js/company_onboard'
        ], $this->js, 'company-onboard', $this->createMinifyFiles);
        // render the page
        $this->render('v1/sa/payrolls/create_partner_company', 'admin_master');
    }

    /**
     * sign the agreement
     */
    private function agreement()
    {
        // $this->payroll_model->loadPayrollHelper($companyId);
        // set title
        $this->data['page_title'] = 'Payroll agreement :: ' . (STORE_NAME);
        // set CSS
        $this->data['appCSS'] = bundleCSS([
            "v1/plugins/ms_modal/main",
            "css/theme-2021"
        ], $this->css, "agreement", $this->createMinifyFiles);
        // set JS
        $this->data['appJs'] = bundleJs([
            'v1/plugins/ms_modal/main',
            'js/app_helper',
            "v1/payroll/js/agreement"
        ], $this->js, 'agreement', $this->createMinifyFiles);
        // render the page
        $this->render('v1/sa/payrolls/agreement', 'admin_master');
    }


    // API routes
    /**
     * Sync company
     *
     * @param int $companyId
     * @return
     */
    public function syncCompanyWithGusto(int $companyId): array
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        //
        // return $this->payroll_model->syncCompanyWithGusto(
        //     $companyId
        // );
        //
        return $this->payroll_model->addCompanySyncRequestInQueue(
            $companyId
        );
    }

    /**
     * Verify bank account
     * Only available on demo mode
     *
     * @return
     */
    public function verifyCompanyBankAccount(int $companyId): array
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($companyId);
        // get the company bank details
        $companyBankAccounts = $this->db
            ->select('sid, gusto_uuid')
            ->where('company_sid', $companyId)
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
    public function verifyCompany(int $companyId): array
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        // get the company
        $companyDetails = $this->payroll_model
            ->getCompanyDetailsForGusto($companyId);
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
                ->where('company_sid', $companyId)
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
        $this->payroll_model->loadPayrollHelper($companyId);
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
     * update company payment configuration
     *
     * @param int $companyId
     * @return
     */
    public function updatePaymentConfiguration(int $companyId): array
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        //
        $post = $this->input->post(null, true);
        //
        $request = [];
        $request['fast_payment_limit'] = $post['fast_speed_limit'] ?? 0;
        $request['payment_speed'] = $post['payment_speed'];
        //
        $response = $this->payroll_model->updatePaymentConfig($companyId, $request);
        //
        if (isset($response['errors'])) {
            $message = $response['errors'][0];
        } else {
            $message = $response['msg'];
        }
        //
        return SendResponse(
            200,
            ['msg' => $message]
        );
    }

    /**
     * update company payment configuration
     *
     * @param int $companyId
     * @return
     */
    public function updateMode(int $companyId)
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        //
        $post = $this->input->post(null, true);
        //
        $this->payroll_model->updateMode($companyId, $post);
    }

    /**
     * update company payment configuration
     *
     * @param int $companyId
     * @return
     */
    public function updatePrimaryAdmin(int $companyId): array
    {
        $this->payroll_model->loadPayrollHelper($companyId);
        //
        $post = $this->input->post(null, true);
        //
        $response = $this->payroll_model->addOrUpdatePrimaryAdmin($companyId, $post);
        //
        return SendResponse(
            200,
            ['msg' => $response['msg']]
        );
    }
}
