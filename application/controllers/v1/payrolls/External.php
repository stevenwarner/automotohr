<?php defined('BASEPATH') || exit('No direct script access allowed');
// add the controller
loadController(
    "modules/payroll/Payroll_base_controller"
);

class External extends Payroll_base_controller
{

    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct(true);
        //
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_date', '"{field}" is invalid.');
        //
        $this->load->model(
            "v1/Payroll/External_payroll_model",
            "external_payroll_model"
        );
        // load compulsory plugins
        // plugins
        $this->data['pageCSS'] = [
            base_url("public/v1/plugins/alertifyjs/css/alertify.min.css"),
            base_url('public/v1/plugins/daterangepicker/css/daterangepicker.min.css')
        ];
        $this->data['pageJs'] = [
            base_url("public/v1/plugins/alertifyjs/alertify.min.js"),
            base_url('public/v1/plugins/daterangepicker/daterangepicker.min.js')
        ];
    }

    /**
     * Main page
     */
    public function index()
    {
        // check the payroll blockers
        if ($this->checkForPayrollBlockers()) {
            return true;
        }
        //
        $this->data['title'] = "External Payrolls";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        // css
        $this->data['appCSS'] = $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'main'
        );
        //
        $this->data['appJs'] = $this->loadJsBundle(
            [
                'js/app_helper',
                'v1/payroll/js/external/main'
            ],
            'main'
        );
        // get all external payrolls
        $this->data['externalPayrolls'] =
            $this->external_payroll_model
            ->getAllCompanyExternalPayrolls(
                $this->data['loggedInPersonCompany']['sid']
            );

        // get all external payrolls
        $this->data['hasUnProcessedExternalPayroll'] =
            $this->external_payroll_model
            ->hasAnyUnprocessedExternalPayrolls(
                $this->data['loggedInPersonCompany']['sid']
            );

        $this->data['hasExternalPayroll'] =
            $this->external_payroll_model
            ->hasExternalPayroll(
                $this->data['loggedInPersonCompany']['sid']
            );

        // get the processed payrolls
        $this->data["processedPayrollCount"] = $this
            ->external_payroll_model
            ->getProcessedPayrollCount(
                $this->data["companyId"]
            );

        //
        $this->loadView('v1/payroll/external/manage');
    }

    /**
     * add page
     */
    public function create()
    {
        //
        //
        $this->data['title'] = "Create External Payroll";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        //
        $this->data['appCSS'] =  $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'add-external'
        );
        //
        $this->data['appJs'] =  $this->loadJsBundle(
            [
                'v1/payroll/js/external/add'
            ],
            'add-external'
        );
        //
        $this->loadView('v1/payroll/external/add');
    }

    /**
     * manage single external payroll
     *
     * @param int $externalPayrollId
     * @return void
     */
    public function manageSingle(int $externalPayrollId)
    {
        //
        $this->data['title'] = "Single External Payroll";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        // get all external payroll
        $this->data['externalPayroll'] =
            $this->external_payroll_model
            ->getExternalPayrollById(
                [
                    'sid' => $externalPayrollId,
                    'is_deleted' => 0,
                    'company_sid' => $this->data['loggedInPersonCompany']['sid']
                ],
                [
                    'is_processed',
                    'check_date',
                    'payment_period_start_date',
                    'payment_period_end_date'
                ]
            );
        //
        if (!$this->data['externalPayroll']) {
            return redirect(base_url('payrolls/external'));
        }

        $this->data['externalPayrollId'] = $externalPayrollId;
        // get all payroll employees
        $this->data['payrollEmployees'] =
            $this->external_payroll_model
            ->getPayrollOnboardEmployees(
                $this->data['loggedInPersonCompany']['sid']
            );
        //
        $this->data['linkedEmployeeIds'] = $this->external_payroll_model
            ->getLinkEmployeeIds(
                $externalPayrollId
            );
        //
        $this->loadView('v1/payroll/external/manage_single');
    }

    /**
     * manage single external payroll for a
     * specific employee
     *
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return void
     */
    public function manageSingleEmployee(
        int $externalPayrollId,
        int $employeeId
    ) {
        //
        $this->data['title'] = "Single Employee External Payroll";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        // check external payroll
        if (!$this
            ->external_payroll_model
            ->checkExternalPayrollById(
                [
                    'sid' => $externalPayrollId,
                    'is_deleted' => 0,
                    'company_sid' => $this->data['loggedInPersonCompany']['sid']
                ]
            )) {

            return redirect(base_url('payrolls/external'));
        }
        // check and push the employee to Gusto
        $this->external_payroll_model
            ->checkAndLinkEmployeeToExternalPayroll(
                $externalPayrollId,
                $employeeId
            );
        // add css
        $this->data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'employee',
            $this->createMinifyFiles
        );
        // add js
        $this->data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/employee'
            ],
            $this->js,
            'employee',
            $this->createMinifyFiles
        );

        // get actual payroll
        $externalPayrollDetails =
            $this
            ->external_payroll_model
            ->getExternalPayrollById(
                [
                    'sid' => $externalPayrollId,
                    'is_deleted' => 0,
                    'company_sid' => $this->data['loggedInPersonCompany']['sid']
                ],
                [
                    'is_processed',
                    'applicable_earnings',
                    'applicable_benefits',
                    'applicable_taxes',
                ]
            );
        // decode json
        $externalPayrollDetails['applicable_earnings'] = json_decode($externalPayrollDetails['applicable_earnings'], true);
        $externalPayrollDetails['applicable_benefits'] = json_decode($externalPayrollDetails['applicable_benefits'], true);
        $externalPayrollDetails['applicable_taxes'] = json_decode($externalPayrollDetails['applicable_taxes'], true);
        //
        $this->data['externalPayrollDetails'] = $externalPayrollDetails;
        $this->data['externalPayrollId'] = $externalPayrollId;
        // get single employee details
        $this->data['employeeDetails'] =
            $this->external_payroll_model
            ->getPayrollEmployeeById(
                $this->data['loggedInPersonCompany']['sid'],
                $employeeId
            );
        // get employee external payroll details
        $employeeExternalPayrollDetails =
            $this->external_payroll_model
            ->getEmployeeExternalPayroll(
                $externalPayrollId,
                $employeeId
            );
        //
        if ($employeeExternalPayrollDetails) {
            $this->data['externalPayrollDetails'] = $this->mergeEarnings(
                $this->data['externalPayrollDetails'],
                $employeeExternalPayrollDetails
            );
        }
        //
        $this->loadView('v1/payroll/external/employee');
    }

    /**
     * confirm tax liabilities
     *
     * @return void
     */
    public function taxLiabilities()
    {
        //
        $this->data['title'] = "Single Employee External Payroll";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        // sync tax liabilities
        $this->external_payroll_model
            ->syncTaxLiabilitiesForExternalPayroll(
                $this->data['loggedInPersonCompany']['sid'],
                $this->data['loggedInPerson']['sid']
            );
        $this->data['taxLiabilities'] = $this->external_payroll_model
            ->getExternalPayrollTaxLiabilities(
                $this->data['loggedInPersonCompany']['sid']
            );
        // add css
        $this->data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'tax-liabilities',
            $this->createMinifyFiles
        );
        // add js
        $this->data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/tax_liabilities'
            ],
            $this->js,
            'tax-liabilities',
            $this->createMinifyFiles
        );
        //
        $this->loadView('v1/payroll/external/tax_liabilities');
    }

    /** 
     * confirm tax liabilities
     *
     * @return void
     */
    public function confirmTaxLiabilities()
    {
        //
        $this->data['title'] = "Confirm Tax Liabilities External Payroll";
        //
        $this->data['isLoggedInView'] = 1;
        // set
        $this->data['loggedInPerson'] = $this->data['session']['employer_detail'];
        $this->data['loggedInPersonCompany'] = $this->data['session']['company_detail'];
        // get the security details
        $this->data['security_details'] = db_get_access_level_details(
            $this->data['session']['employer_detail']['sid'],
            null,
            $this->data['session']
        );
        $this->data['taxLiabilities'] = $this->external_payroll_model
            ->getExternalPayrollTaxLiabilities(
                $this->data['loggedInPersonCompany']['sid']
            );
        if (!$this->data['taxLiabilities']) {
            return redirect('payrolls/external');
        }
        // add css
        $this->data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'confirm-tax-liabilities',
            $this->createMinifyFiles
        );
        // add js
        $this->data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/confirm_tax_liabilities'
            ],
            $this->js,
            'confirm-tax-liabilities',
            $this->createMinifyFiles
        );
        //
        $this->loadView('v1/payroll/external/confirm_tax_liabilities');
    }


    //  API routes
    /**
     * handles external payroll creation
     *
     * @return JSON
     */
    public function createProcess(): array
    {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // validation
        $this->form_validation->set_rules(
            'check_date',
            'Check date',
            'xss_clean|trim|required|callback_valid_date'
        );
        $this->form_validation->set_rules(
            'payment_period_start_date',
            'Payment period start date',
            'xss_clean|trim|required|callback_valid_date'
        );
        $this->form_validation->set_rules(
            'payment_period_end_date',
            'Payment period end date',
            'xss_clean|trim|required|callback_valid_date'
        );
        // run the validator
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        // get he post
        $post = $this->input->post(null, true);
        //
        $response = $this
            ->external_payroll_model
            ->createExternalPayroll(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid'],
                $post
            );
        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * handles external payroll deletion
     *
     * @param int $externalPayrollId
     * @return JSON
     */
    public function deleteProcess(int $externalPayrollId): array
    {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->deleteExternalPayroll(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid'],
                $externalPayrollId
            );

        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * handles employee external payroll update
     *
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return json
     */
    public function processEmployeeExternalPayroll(
        int $externalPayrollId,
        int $employeeId
    ): array {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get the post
        $post = $this->input->post(null, true);
        //
        $errorsArray = [];
        //
        if (!$post['applicable_earnings']) {
            $errorsArray[] = '"Applicable earnings" are required.';
        }
        //
        if (!$post['applicable_taxes']) {
            $errorsArray[] = '"Applicable taxes" are required.';
        }
        // run the validator
        if ($errorsArray) {
            return SendResponse(
                400,
                ['errors' => $errorsArray]
            );
        }
        //
        $response = $this
            ->external_payroll_model
            ->updateEmployeeExternalPayroll(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid'],
                $externalPayrollId,
                $employeeId,
                $post
            );

        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * calculate employee external payroll taxes
     *
     * @param int $externalPayrollId
     * @param int $employeeId
     * @return json
     */
    public function calculateEmployeeExternalPayroll(
        int $externalPayrollId,
        int $employeeId
    ): array {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->calculateEmployeeExternalPayroll(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid'],
                $externalPayrollId,
                $employeeId
            );

        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * update employee external payroll tax liabilities
     *
     * @return json
     */
    public function updateTaxLiabilities(): array
    {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $post = $this->input->post('tax_liabilities', true);
        //
        $response = $this
            ->external_payroll_model
            ->updateTaxLiabilities(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid'],
                $post
            );

        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * confirm and finish employee external payroll tax liabilities
     *
     * @return json
     */
    public function finishTaxLiabilities(): array
    {
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->finishTaxLiabilities(
                $this->data['session']['company_detail']['sid'],
                $this->data['session']['employer_detail']['sid']
            );

        // send response
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * callback event for validation to check date format
     *
     * @param string $date
     * @return bool
     */
    public function valid_date(string $date): bool
    {
        return preg_match(
            '/\d{2}\/\d{2}\/\d{4}/',
            $date
        );
    }


    /**
     * merge the external and employee external payroll
     *
     * @param array $externalPayroll
     * @param array $employeeExternalPayroll
     * @return array
     */
    private function mergeEarnings(
        array $externalPayroll,
        array $employeeExternalPayroll
    ): array {
        // convert the array to associate
        if ($employeeExternalPayroll['earnings']) {
            //
            $tmp = [];
            //
            foreach ($employeeExternalPayroll['earnings'] as $value) {
                //
                if (!$tmp[$value['earning_id']]) {
                    $tmp[$value['earning_id']] = $value;
                }
                //
                $tmp[$value['earning_id']]['amount'] = $value['amount'] != 0.0 ? $value['amount'] : $tmp[$value['earning_id']]['amount'];
                $tmp[$value['earning_id']]['hours'] =
                    $value['hours'] != 0.0 ? $value['hours'] : $tmp[$value['earning_id']]['hours'];
            }
            //
            $employeeExternalPayroll['earnings'] = $tmp;
        }
        // convert the array to associate
        if ($employeeExternalPayroll['benefits']) {
            //
            $tmp = [];
            //
            foreach ($employeeExternalPayroll['benefits'] as $value) {
                $tmp[$value['id']] = $value;
            }
            //
            $employeeExternalPayroll['benefits'] = $tmp;
        }
        // convert the array to associate
        if ($employeeExternalPayroll['taxes']) {
            //
            $tmp = [];
            //
            foreach ($employeeExternalPayroll['taxes'] as $value) {
                $tmp[$value['tax_id']] = $value;
            }
            //
            $employeeExternalPayroll['taxes'] = $tmp;
        }
        // merge
        if ($externalPayroll['applicable_earnings']) {
            foreach ($externalPayroll['applicable_earnings'] as $key => $value) {
                $externalPayroll['applicable_earnings'][$key]['amount'] =
                    $employeeExternalPayroll['earnings'][$value['earning_id']]['amount'];
                $externalPayroll['applicable_earnings'][$key]['hours'] =
                    $employeeExternalPayroll['earnings'][$value['earning_id']]['hours'];
            }
        }

        if ($externalPayroll['applicable_benefits']) {
            foreach ($externalPayroll['applicable_benefits'] as $key => $value) {
                $externalPayroll['applicable_benefits'][$key]['amount'] = $employeeExternalPayroll['benefits'][$value['id']]['amount'];
                $externalPayroll['applicable_benefits'][$key]['hours'] = $employeeExternalPayroll['benefits'][$value['id']]['hours'];
            }
        }

        if ($externalPayroll['applicable_taxes']) {
            foreach ($externalPayroll['applicable_taxes'] as $key => $value) {
                $externalPayroll['applicable_taxes'][$key]['amount'] = $employeeExternalPayroll['taxes'][$value['id']]['amount'];
                $externalPayroll['applicable_taxes'][$key]['hours'] = $employeeExternalPayroll['taxes'][$value['id']]['hours'];
            }
        }
        //
        return $externalPayroll;
    }
}
