<?php defined('BASEPATH') || exit('No direct script access allowed');

class Off_cycle extends Public_controller
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
    private $blockMinifyFilesCreation;
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
        $this->load->model("v1/Off_cycle_payroll_model", "off_cycle_payroll_model");
        $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/off_cycle/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/off_cycle/';
        //
        $this->blockMinifyFilesCreation = true;
    }

    /**
     * main page
     *
     * @param string $reason
     */
    public function index(string $reason)
    {
        // load the regular payroll model
        $this->load->model("v1/Regular_payroll_model", "regular_payroll_model");
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
            $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/regular/blockers')
                ->view('main/footer');
        }
        //
        $parsedReason = getReason($reason);
        // check if off cycle already exists
        $payrollId = $this->off_cycle_payroll_model->getPayrollId($parsedReason, $data['loggedInPersonCompanyId']);
        //
        if ($payrollId) {
            // redirect to payroll
            return redirect("/payrolls/{$reason}/{$payrollId}/hours_and_earnings");
        }
        //
        $this->basic($reason);
    }

    /**
     * Basic details
     * 
     * @param string $reason
     */
    public function basic(string $reason)
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Basic details";
        // get payroll employees
        $data['payrollEmployees'] = $this->off_cycle_payroll_model
            ->getFilteredPayrollEmployees(
                $data['loggedInPersonCompanyId']
            );
        $data['schedule'] = $this->off_cycle_payroll_model
            ->getCompanySchedule(
                $data['loggedInPersonCompanyId']
            );
        //
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'basic',
            $this->blockMinifyFilesCreation
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/off_cycle/basic'
            ],
            $this->js,
            'basic',
            $this->blockMinifyFilesCreation
        );
        //
        $data['reason'] = $reason;
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/off_cycle/basic')
            ->view('main/footer');
    }

    /**
     * hours and earnings
     *
     * @param string $reason
     * @param string $payrollId
     */
    public function hoursAndEarnings(string $reason, int $payrollId)
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Hours and earnings details";
        // get data
        $data['payroll'] = $this->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline'
                ]
            );
        //
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'hours_and_earnings',
            $this->blockMinifyFilesCreation
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/off_cycle/clear_data',
                'v1/payroll/js/off_cycle/hours_and_earnings'
            ],
            $this->js,
            'hours_and_earnings',
            $this->blockMinifyFilesCreation
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/off_cycle/hours_and_earnings')
            ->view('main/footer');
    }

    /**
     * hours and earnings
     *
     * @param string $reason
     * @param string $payrollId
     */
    public function timeOff(string $reason, int $payrollId)
    {
        //
        $data = $this->getData();
        //
        $data['title'] = "Time off details";
        // get data
        $data['payroll'] = $this->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline'
                ]
            );
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'timeoff',
            $this->blockMinifyFilesCreation
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/off_cycle/clear_data',
                'v1/payroll/js/off_cycle/timeoff'
            ],
            $this->js,
            'timeoff',
            $this->blockMinifyFilesCreation
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/off_cycle/timeoff')
            ->view('main/footer');
    }

    /**
     * review
     *
     * @param string $reason
     * @param string $payrollId
     */
    public function review(string $reason, int $payrollId)
    {
        // time to calculate payroll
        $gustoResponse = $this->off_cycle_payroll_model->calculatePayrollById($payrollId);
        //
        if (!$gustoResponse['success']) {
            return redirect("payrolls/{$reason}");
        }
        //
        $data = $this->getData();
        //
        $data['title'] = "Time off details";
        // get data
        $data['payroll'] = $this->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline'
                ]
            );
        // css
        $data['appCSS'] = bundleCSS(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            $this->css,
            'review',
            $this->blockMinifyFilesCreation
        );
        //
        $data['appJs'] = bundleJs(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/off_cycle/clear_data',
                'v1/payroll/js/off_cycle/review'
            ],
            $this->js,
            'review',
            $this->blockMinifyFilesCreation
        );
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/off_cycle/review')
            ->view('main/footer');
    }




    // API calls

    /**
     * process basic
     *
     * @return json
     */
    public function processBasics(): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // get sanitized post
        $post = $this->input->post(null, true);
        // set errors array
        $errorsArray = [];
        // validation
        if (!$post) {
            $errorsArray[] = '"Data" is missing.';
        }
        if (!$post['off_cycle_reason']) {
            $errorsArray[] = '"off cycle reason" is missing.';
        }
        if (!$post['start_date']) {
            $errorsArray[] = '"Start date" is missing.';
        } elseif (!$this->valid_date($post['start_date'])) {
            $errorsArray[] = '"Start date" is invalid.';
        }
        if (!$post['end_date']) {
            $errorsArray[] = '"End date" is missing.';
        } elseif (!$this->valid_date($post['end_date'])) {
            $errorsArray[] = '"End date" is invalid.';
        }
        if (!$post['check_date']) {
            $errorsArray[] = '"Check date" is missing.';
        } elseif (!$this->valid_date($post['check_date'])) {
            $errorsArray[] = '"Check date" is invalid.';
        }
        if (!$post['skip_regular_deductions']) {
            $errorsArray[] = '"Deductions and contributions" is missing.';
        }
        if (!$post['withholding_pay_period']) {
            $errorsArray[] = '"Withholding pay period" is missing.';
        }
        if (!$post['fixed_withholding_rate']) {
            $errorsArray[] = '"Withholding pay period" is missing.';
        }
        if (!$post['employees']) {
            $errorsArray[] = 'Please select at least one employee.';
        }
        //
        if ($errorsArray) {
            return SendResponse(400, ['errors' => $errorsArray]);
        }
        // check if payroll already exists
        if ($this->off_cycle_payroll_model->getPayrollId($post['off_cycle_reason'], $session['company_detail']['sid'])) {
            return SendResponse(400, ['errors' => ['"' . ($post['off_cycle_reason']) . '" already in progress.']]);
        }
        // 
        $response = $this->off_cycle_payroll_model->processOffCyclePayroll(
            $session['company_detail']['sid'],
            $post
        );
        //
        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * Clear draft data
     *
     * @param int $payrollId
     * @return json
     */
    public function clearDraftData(int $payrollId): array
    {
        // get the session
        $session = checkUserSession(false);
        // check session and generate proper error
        $this->checkSessionStatus($session);
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // 
        $response = $this->off_cycle_payroll_model
            ->clearDraftData(
                $session['company_detail']['sid'],
                $payrollId
            );
        //
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
