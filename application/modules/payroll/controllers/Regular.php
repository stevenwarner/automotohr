<?php defined('BASEPATH') || exit('No direct script access allowed');

class Regular extends Public_controller
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
        $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/regular/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/regular/';
        //
        $this->createMinifyFiles = true;
    }

    /**
     * Main page
     */
    public function index()
    {
        //
        $data = $this->getData();

        // let's check if there are any payroll blockers
        $payrollBlockers = $this->regular_payroll_model
            ->getRegularPayrollBlocker(
                $data['loggedInPersonCompanyId']
            );
        //
        if ($payrollBlockers['data']) {
            //
            $data['title'] = "Payroll blockers";
            $data['payrollBlockers'] = $payrollBlockers['data'];
            //
            return $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/regular/blockers')
                ->view('main/footer');
        }
        //
        $data['title'] = "Regular Payrolls";
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
                'v1/payroll/js/regular/main'
            ],
            $this->js,
            'main',
            $this->createMinifyFiles
        );
        // let's check and sync payrolls
        $this->regular_payroll_model
            ->syncRegularPayrolls(
                $data['loggedInPersonCompanyId'],
                $data['loggedInPersonId']
            );

        // get the payrolls
        $data['regularPayrolls'] = $this->regular_payroll_model
            ->getRegularPayrolls(
                $data['loggedInPersonCompanyId']
            );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/regular/manage')
            ->view('main/footer');
    }

    /**
     * Run regular payroll
     *
     * @param int $payrollId
     * @param string $step
     *               hours_and_earnings
     *               timeoff
     *               review
     *               confirmation
     */
    public function single(int $payrollId, string $step = 'hours_and_earnings')
    {
        //
        $data = $this->getData();
        // check and set for prepare payroll
        $payrollStatus = $this->regular_payroll_model
            ->checkAndGetPayrollStatus(
                $payrollId,
                $data['loggedInPersonCompanyId']
            );
        //
        $data['title'] = "Regular Payrolls";
        // mismatch
        if ($payrollStatus === 'not_found') {
            return $this->load->view(
                'main/header',
                $data
            )
                ->view('v1/payroll/regular/error')
                ->view('main/footer');
        }
        // if needs to be prepared
        if ($payrollStatus === 'prepare' && in_array($step, ['hours_and_earnings', 'timeoff'])) {
            // prepare data
            $this->regular_payroll_model
                ->preparePayrollForUpdate(
                    $payrollId
                );
        }
        // get data
        $data['regularPayroll'] = $this->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline'
                ]
            );

        return $this->$step($payrollId, $data);
    }

    /**
     * regular payroll hours and earnings
     *
     * @param int $payrollId
     * @param array $data
     */
    private function hours_and_earnings(int $payrollId, array $data)
    {
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'hours_and_earnings',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/regular/hours_and_earnings'
            ],
            $this->js,
            'hours_and_earnings',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view("v1/payroll/regular/hours_and_earnings")
            ->view('main/footer');
    }

    /**
     * regular payroll time off
     *
     * @param int $payrollId
     * @param array $data
     */
    private function timeoff(int $payrollId, array $data)
    {
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'timeoff',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/regular/timeoff'
            ],
            $this->js,
            'timeoff',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view("v1/payroll/regular/timeoff")
            ->view('main/footer');
    }

    /**
     * regular payroll review
     *
     * @param int $payrollId
     * @param array $data
     */
    private function review(int $payrollId, array $data)
    {
        // time to calculate payroll
        $gustoResponse = $this->regular_payroll_model->calculatePayrollById($payrollId);
        //
        if (!$gustoResponse['success']) {
            return redirect('payrolls/regular');
        }
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'review',
            $this->createMinifyFiles
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/regular/review'
            ],
            $this->js,
            'review',
            $this->createMinifyFiles
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view("v1/payroll/regular/review")
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
     * prepares the payroll for update
     *
     * @param int $payrollId
     * @return JSON
     */
    public function preparePayrollForUpdate(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get he post
        $post = $this->input->post(null, true);

        _e($post, true, true);
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
     * get the regular payroll view
     *
     * @param int $payrollId
     * @return JSON
     */
    public function getRegularPayrollStep1(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $passData = [
            'payrollEmployees' => []
        ];
        // get the single payroll
        $regularPayroll = $this->regular_payroll_model
            ->getRegularPayrollById(
                $session['company_detail']['sid'],
                $payrollId
            );
        if ($regularPayroll['employees']) {
            $payrollEmployees = $this->regular_payroll_model
                ->getPayrollEmployeesWithCompensation(
                    $session['company_detail']['sid'],
                    true
                );
            $passData['payrollEmployees'] = $payrollEmployees;
        }
        //
        $passData['regularPayroll'] = $regularPayroll;
        // send response
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/payroll/regular/partials/hours_and_earnings',
                    $passData,
                    true
                ),
                'employees' => $passData['payrollEmployees'],
                'payroll' => $passData['regularPayroll']
            ]
        );
    }

    /**
     * get the regular payroll view - review
     *
     * @param int $payrollId
     * @return JSON
     */
    public function getRegularPayrollStep3(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get payroll one more time
        $this->regular_payroll_model
            ->getPayrollById($payrollId);

        //
        $passData = [
            'payrollEmployees' => []
        ];
        // get the single payroll
        $regularPayroll = $this->regular_payroll_model
            ->getRegularPayrollById(
                $session['company_detail']['sid'],
                $payrollId
            );
        if ($regularPayroll['employees']) {
            $payrollEmployees = $this->regular_payroll_model
                ->getPayrollEmployeesWithCompensation(
                    $session['company_detail']['sid'],
                    true
                );
            $passData['payrollEmployees'] = $payrollEmployees;
        }
        //
        $passData['payroll'] = $regularPayroll;

        // send response
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/payroll/regular/partials/review',
                    $passData,
                    true
                )
            ]
        );
    }

    /**
     * submit regular payroll
     *
     * @param int $payrollId
     * @return JSON
     */
    public function submitPayroll(int $payrollId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get payroll one more time
        $gustoResponse = $this->regular_payroll_model
            ->submitPayroll($payrollId);

        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse['errors'] ?? ['msg' => "You have successfully submitted the payroll. The receipt of the payroll is shown on \"Payroll history\"."]
        );
    }

    /**
     * cancel regular payroll
     *
     * @param int $payrollId
     * @return JSON
     */
    public function cancelPayroll(int $payrollId)
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get payroll one more time
        $gustoResponse = $this->regular_payroll_model
            ->cancelPayroll($payrollId);

        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse['errors'] ?? ['msg' => "You have successfully cancelled the payroll."]
        );
    }

    /**
     * get the regular payroll view - time off
     *
     * @param int $payrollId
     * @return JSON
     */
    public function getRegularPayrollStep2(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $passData = [
            'payrollEmployees' => []
        ];
        // get the single payroll
        $regularPayroll = $this->regular_payroll_model
            ->getRegularPayrollById(
                $session['company_detail']['sid'],
                $payrollId
            );
        if ($regularPayroll['employees']) {
            $payrollEmployees = $this->regular_payroll_model
                ->getPayrollEmployeesWithCompensation(
                    $session['company_detail']['sid'],
                    true
                );
            $passData['payrollEmployees'] = $payrollEmployees;
        }
        //
        $passData['regularPayroll'] = $regularPayroll;
        // send response
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'v1/payroll/regular/partials/timeoff',
                    $passData,
                    true
                ),
                'payroll' => $passData['regularPayroll']
            ]
        );
    }

    /**
     * save the regular payroll hours and earnings
     *
     * @param int $payrollId
     * @return JSON
     */
    public function saveRegularPayrollStep1(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $post = $this->input->post(null, true);
        // set errors array
        $errorsArray = [];
        // validation
        if (!$post) {
            $errorsArray[] = '"Data" is required.';
        }
        //
        if (!$post['payrollId']) {
            $errorsArray[] = '"Payroll id" is required.';
        }
        //
        if (!$post['employees']) {
            $errorsArray[] = '"Payroll employees" are required.';
        }
        //
        if ($errorsArray) {
            return SendResponse(400, ['errors' => $errorsArray]);
        }
        // make request
        $payrollEmployees = $post['employees'];
        //
        foreach ($payrollEmployees as $key => $value) {
            //
            $payrollEmployees[$key]['fixed_compensations'] = array_values($value['fixed_compensations']);
            $payrollEmployees[$key]['hourly_compensations'] = array_values($value['hourly_compensations']);
            unset($payrollEmployees[$key]['v1']);
        }
        //
        $payrollEmployees = array_values($payrollEmployees);
        // update on Gusto
        $gustoResponse = $this->regular_payroll_model
            ->updatePayrollById(
                $payrollId,
                $payrollEmployees
            );
        //
        if ($gustoResponse['errors']) {
            return SendResponse(400, ['errors' => $gustoResponse['errors']]);
        }

        // save entries
        foreach ($post['employees'] as $key => $value) {
            //
            $this->db
                ->where('employee_sid', $key)
                ->where('regular_payroll_sid', $payrollId)
                ->update('payrolls.regular_payrolls_employees', [
                    'reimbursement_json' => json_encode($value['v1']['reimbursements'])
                ]);
        }
        //
        return SendResponse(200, ['success' => true, 'msg' => 'You have successfully saved the "' . ($post['action'] ?? 'Hours and earnings') . '".']);
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
     * get common data
     */
    private function getData()
    {
        //
        $data = [];
        // check and set user session
        $data['session'] = checkUserSession();
        //
        $data['isLoggedInView'] = 1;
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['loggedInPersonCompany'] = $data['session']['company_detail'];
        $data['loggedInPersonCompanyId'] = $data['session']['company_detail']['sid'];
        $data['loggedInPersonId'] = $data['session']['employer_detail']['sid'];
        // get the security details
        $data['security_details'] = db_get_access_level_details(
            $data['session']['employer_detail']['sid'],
            null,
            $data['session']
        );
        //
        return $data;
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
}
