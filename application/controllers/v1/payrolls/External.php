<?php defined('BASEPATH') || exit('No direct script access allowed');

class External extends Public_controller
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
        //
        $this->form_validation->set_message('required', '"{field}" is required.');
        $this->form_validation->set_message('valid_date', '"{field}" is invalid.');
        // Call the model
        $this->load->model("v1/Payroll/External_payroll_model", "external_payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/external/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/external/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * Main page
     */
    public function index()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "External Payrolls";
        //
        $data['isLoggedInView'] = 1;
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
                'v1/app/css/loader'
            ],
            $this->css,
            'main',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/main'
            ],
            $this->js,
            'main',
            $this->createMinifyFiles
        );
        // get all external payrolls
        $data['externalPayrolls'] =
            $this->external_payroll_model
            ->getAllCompanyExternalPayrolls(
                $data['loggedInPersonCompany']['sid']
            );

        // get all external payrolls
        $data['hasUnProcessedExternalPayroll'] =
            $this->external_payroll_model
            ->hasAnyUnprocessedExternalPayrolls(
                $data['loggedInPersonCompany']['sid']
            );

        $data['hasExternalPayroll'] =
            $this->external_payroll_model
            ->hasExternalPayroll(
                $data['loggedInPersonCompany']['sid']
            );

        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/manage')
            ->view('main/footer');
    }

    /**
     * add page
     */
    public function create()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "Create External Payroll";
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        //
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'add-external',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/add'
            ],
            $this->js,
            'add-external',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/add')
            ->view('main/footer');
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
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "Single External Payroll";
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // get all external payroll
        $data['externalPayroll'] =
            $this->external_payroll_model
            ->getExternalPayrollById(
                [
                    'sid' => $externalPayrollId,
                    'is_deleted' => 0,
                    'company_sid' => $data['loggedInPersonCompany']['sid']
                ],
                [
                    'is_processed',
                    'check_date',
                    'payment_period_start_date',
                    'payment_period_end_date'
                ]
            );
        //
        if (!$data['externalPayroll']) {
            return redirect(base_url('payrolls/external'));
        }

        $data['externalPayrollId'] = $externalPayrollId;
        // get all payroll employees
        $data['payrollEmployees'] =
            $this->external_payroll_model
            ->getPayrollOnboardEmployees(
                $data['loggedInPersonCompany']['sid']
            );
        //
        $data['linkedEmployeeIds'] = $this->external_payroll_model
            ->getLinkEmployeeIds(
                $externalPayrollId
            );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/manage_single')
            ->view('main/footer');
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
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "Single Employee External Payroll";
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // check external payroll
        if (!$this
            ->external_payroll_model
            ->checkExternalPayrollById(
                [
                    'sid' => $externalPayrollId,
                    'is_deleted' => 0,
                    'company_sid' => $data['loggedInPersonCompany']['sid']
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
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'employee',
            $this->createMinifyFiles
        );
        // add js
        $data['appJs'] = bundleJs(
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
                    'company_sid' => $data['loggedInPersonCompany']['sid']
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
        $data['externalPayrollDetails'] = $externalPayrollDetails;
        $data['externalPayrollId'] = $externalPayrollId;
        // get single employee details
        $data['employeeDetails'] =
            $this->external_payroll_model
            ->getPayrollEmployeeById(
                $data['loggedInPersonCompany']['sid'],
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
            $data['externalPayrollDetails'] = $this->mergeEarnings(
                $data['externalPayrollDetails'],
                $employeeExternalPayrollDetails
            );
        }
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/employee')
            ->view('main/footer');
    }

    /**
     * confirm tax liabilities
     *
     * @return void
     */
    public function taxLiabilities()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "Single Employee External Payroll";
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        // sync tax liabilities
        $this->external_payroll_model
            ->syncTaxLiabilitiesForExternalPayroll(
                $data['loggedInPersonCompany']['sid'],
                $data['loggedInPerson']['sid']
            );
        $data['taxLiabilities'] = $this->external_payroll_model
            ->getExternalPayrollTaxLiabilities(
                $data['loggedInPersonCompany']['sid']
            );
        // add css
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'tax-liabilities',
            $this->createMinifyFiles
        );
        // add js
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/tax_liabilities'
            ],
            $this->js,
            'tax-liabilities',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/tax_liabilities')
            ->view('main/footer');
    }

    /** 
     * confirm tax liabilities
     *
     * @return void
     */
    public function confirmTaxLiabilities()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['title'] = "Confirm Tax Liabilities External Payroll";
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        $data['taxLiabilities'] = $this->external_payroll_model
            ->getExternalPayrollTaxLiabilities(
                $data['loggedInPersonCompany']['sid']
            );
        if (!$data['taxLiabilities']) {
            return redirect('payrolls/external');
        }
        // add css
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'confirm-tax-liabilities',
            $this->createMinifyFiles
        );
        // add js
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/confirm_tax_liabilities'
            ],
            $this->js,
            'confirm-tax-liabilities',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/confirm_tax_liabilities')
            ->view('main/footer');
    }


    //  API routes
    /**
     * handles external payroll creation
     *
     * @return JSON
     */
    public function createProcess(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
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
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
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
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->deleteExternalPayroll(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
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
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
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
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
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
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->calculateEmployeeExternalPayroll(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
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
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $post = $this->input->post('tax_liabilities', true);
        //
        $response = $this
            ->external_payroll_model
            ->updateTaxLiabilities(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid'],
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
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->external_payroll_model
            ->finishTaxLiabilities(
                $session['company_detail']['sid'],
                $session['employer_detail']['sid']
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
     * generate error based on session
     *
     * @param mixed $session
     * @return json
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
        if (!isCompanyLinkedWithGusto($this->session->userdata('logged_in')['company_detail']['sid'])) {
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
