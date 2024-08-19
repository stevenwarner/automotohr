<?php defined('BASEPATH') || exit('No direct script access allowed');
// add the controller
loadController(
    "modules/payroll/Payroll_base_controller"
);

class Off_cycle_payroll extends Payroll_base_controller
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
        // Call the model
        $this->load->model(
            "v1/Payroll/Off_cycle_payroll_model",
            "off_cycle_payroll_model"
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
     * main page
     *
     * @param string $reason
     */
    public function index(string $reason)
    {
        // check the payroll blockers
        if ($this->checkForPayrollBlockers()) {
            return true;
        }
        //
        $parsedReason = getReason($reason);
        // check if off cycle already exists
        $payrollId = $this
            ->off_cycle_payroll_model
            ->getPayrollId(
                $parsedReason,
                $this->data["companyId"]
            );
        //
        if ($payrollId) {
            // redirect to payroll
            return redirect(
                "/payrolls/{$reason}/{$payrollId}/hours_and_earnings"
            );
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
        $this->data['title'] = "Basic details";
        // get payroll employees
        $this->data['payrollEmployees'] = $this
            ->off_cycle_payroll_model
            ->getFilteredPayrollEmployees(
                $this->data["companyId"]
            );
        $this->data['schedule'] = $this
            ->off_cycle_payroll_model
            ->getCompanySchedule(
                $this->data["companyId"]
            );
        //
        $this->data['appCSS'] =  $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'off_cycle_basic'
        );
        //
        $this->data['appJs'] =  $this->loadJsBundle(
            [
                'v1/payroll/js/off_cycle/basic'
            ],
            'off_cycle_basic'
        );
        //
        $this->data['reason'] = $reason;
        //
        $this->loadView('v1/payroll/off_cycle/basic');
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
        $this->data['title'] = "{$reason} Payroll | Hours and earnings";
        // get data
        $this->data['payroll'] = $this
            ->off_cycle_payroll_model
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
        $this->data['appCSS'] =  $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'off_cycle_basic_hours_and_earnings'
        );
        //
        $this->data['appJs'] =  $this->loadJsBundle(
            [
                'v1/payroll/js/off_cycle/clear_data',
                'v1/payroll/js/off_cycle/hours_and_earnings'
            ],
            'off_cycle_basic_hours_and_earnings'
        );
        //
        $this->loadView('v1/payroll/off_cycle/hours_and_earnings');
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
        //
        $this->data['title'] = "Time off details";
        // get data
        $this->data['payroll'] = $this->regular_payroll_model
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
        $this->data['appCSS'] =  $this->loadCssBundle(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            'review'
        );
        //
        $this->data['appJs'] =  $this->loadJsBundle(
            [
                'v1/plugins/ms_modal/main',
                'js/app_helper',
                'v1/payroll/js/off_cycle/clear_data',
                'v1/payroll/js/off_cycle/review'
            ],
            'review'
        );
        //
        $this->loadView('v1/payroll/off_cycle/review');
    }


    // API calls

    /**
     * process basic
     *
     * @return json
     */
    public function processBasics(): array
    {
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
        if ($this
            ->off_cycle_payroll_model
            ->getPayrollId(
                $post['off_cycle_reason'],
                $this->data['companyId']
            )
        ) {
            return SendResponse(
                400,
                [
                    'errors' => [
                        '"' . ($post['off_cycle_reason']) . '" already in progress.'
                    ]
                ]
            );
        }
        // 
        $response = $this
            ->off_cycle_payroll_model
            ->processOffCyclePayroll(
                $this->data['companyId'],
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
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        // 
        $response = $this->off_cycle_payroll_model
            ->clearDraftData(
                $this->data['session']['company_detail']['sid'],
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
        $this->checkSessionStatus();
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $passData = [
            'payrollEmployees' => []
        ];
        // get the single payroll
        $regularPayroll = $this
            ->regular_payroll_model
            ->getRegularPayrollById(
                $session['company_detail']['sid'],
                $payrollId,
                [
                    "sid",
                    "gusto_uuid",
                    "start_date",
                    "end_date",
                    "check_date",
                    "payroll_deadline",
                    "fixed_compensations_json"
                ]
            );
        //
        if ($regularPayroll['employees']) {
            $payrollEmployees = $this
                ->regular_payroll_model
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
                    'modules/payroll/regular/partials/hours_and_earnings_view',
                    $passData,
                    true
                ),
                'employees' => $passData['payrollEmployees'],
                'payroll' => $passData['regularPayroll']
            ]
        );
    }
}
