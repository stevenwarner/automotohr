<?php defined('BASEPATH') || exit('No direct script access allowed');
// add the controller
loadController(
    "modules/payroll/Payroll_base_controller"
);
class Regular extends Payroll_base_controller
{

    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct(true);
        //
        $this->load->model(
            "v1/Payroll/Regular_payroll_model",
            "regular_payroll_model"
        );
        // load compulsory plugins
        // plugins
        $this->data['pageCSS'] = [
            base_url("public/v1/plugins/alertifyjs/css/alertify.min.css")
        ];
        $this->data['pageJs'] = [
            base_url("public/v1/plugins/alertifyjs/alertify.min.js"),
            base_url("public/v1/plugins/jquery/jquery-ui.js")
        ];
        // load library
        $this
            ->load
            ->library(
                "Lb_gusto",
                ["companyId" => $this->data["companyId"]],
                "lb_gusto"
            );
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
        // set the title
        $this->data['title'] = "Regular Payrolls";
        // set the CSS files
        $this->data['appCSS'] =
            $this->loadCssBundle([
                'v1/app/css/loader'
            ], "main");
        // load JS bundle
        $this->data['appJs'] = $this->loadJsBundle([
            'v1/payroll/js/regular/main'
        ], "main");

        // get the payrolls
        $this->data['regularPayrolls'] = $this
            ->regular_payroll_model
            ->getRegularPayrolls(
                $this->companyId
            );
        // load the view
        $this
            ->loadView(
                'modules/payroll/regular/listing'
            );
    }

    /**
     * Run regular payroll
     *
     * @method hoursAndEarnings
     * @param int $payrollId
     * @param string $step
     *               hours_and_earnings
     *               timeoff
     *               review
     *               confirmation
     */
    public function single(int $payrollId, string $step = 'hours_and_earnings')
    {
        // check the payroll blockers
        if ($this->checkForPayrollBlockers()) {
            return true;
        }
        // check if employee compensations are
        // intact or not
        $this
            ->regular_payroll_model
            ->checkAndPreparePayroll(
                $payrollId,
                $this->data["companyId"]
            );

        // get this->
        $this->data['regularPayroll'] = $this
            ->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline',
                    "status"
                ]
            );

        if ($this->data['regularPayroll']["status"] == "processed") {
            return redirect("payrolls/history");
        }

        if ($this->data['regularPayroll']["status"] == "calculated") {
            return redirect("payrolls/regular/$payrollId/review");
        }

        // check the state
        if ($this->data['regularPayroll']["status"] !== "pending") {
            return $this->handlePayrollStatusPage();
        }
        //
        $this->data['title'] = "Regular Payroll | ";
        $this->data['title'] .= formatDateToDB(
            $this->data["regularPayroll"]["start_date"],
            DB_DATE,
            DATE
        );
        $this->data['title'] .= " - ";
        $this->data['title'] .= formatDateToDB(
            $this->data["regularPayroll"]["end_date"],
            DB_DATE,
            DATE
        );
        //
        $loadFunc = stringToFunc($step);
        // load the step
        return $this->$loadFunc();
    }

    /**
     * Run regular payroll
     *
     * @method hoursAndEarnings
     * @param int $payrollId
     */
    public function review(int $payrollId)
    {
        // check the payroll blockers
        if ($this->checkForPayrollBlockers()) {
            return true;
        }
        //
        $this->data['regularPayroll'] = $this
            ->regular_payroll_model
            ->getRegularPayrollByIdColumns(
                $payrollId,
                [
                    'start_date',
                    'end_date',
                    'check_date',
                    'payroll_deadline',
                    'calculated_at',
                    "status"
                ]
            );

        if ($this->data['regularPayroll']["status"] == "processed") {
            return redirect("payrolls/history");
        }

        if ($this->data['regularPayroll']["status"] == "submitted") {
            return $this->handlePayrollStatusPage();
        }
        //
        $this->data['title'] = "Regular Payroll Review | ";
        $this->data['title'] .= formatDateToDB(
            $this->data["regularPayroll"]["start_date"],
            DB_DATE,
            DATE
        );
        $this->data['title'] .= " - ";
        $this->data['title'] .= formatDateToDB(
            $this->data["regularPayroll"]["end_date"],
            DB_DATE,
            DATE
        );
        //
        // time to calculate payroll
        $this->data['appCSS'] = $this->loadCssBundle(
            [
                'v1/app/css/loader'
            ],
            'regular_payroll_review'
        );
        //
        $this->data['appJs'] = $this->loadJsBundle(
            [
                'v1/payroll/js/regular/review'
            ],
            'regular_payroll_review'
        );
        //
        $this->loadView("modules/payroll/regular/partials/review");
    }

    /**
     * Revert the regular payroll changes
     *
     * @param int $regularPayrollId
     */
    public function discardPayrollChanges(int $regularPayrollId)
    {
        // set the title
        $this->data['title'] = "Regular Payrolls";
        //
        $response = $this
            ->regular_payroll_model
            ->discardPayrollChanges(
                $regularPayrollId
            );
        // get the payrolls
        return SendResponse(
            $response["errors"] ? 400 : 200,
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

    //--------------------------------------------------------------------------
    //-Private events
    //--------------------------------------------------------------------------

    /**
     * regular payroll hours and earnings
     *
     * @param int $payrollId
     */
    private function hoursAndEarnings()
    {
        // plugins
        $this->data['pageCSS'] = [
            base_url("public/v1/plugins/alertifyjs/css/alertify.min.css")
        ];
        $this->data['pageJs'] = [
            base_url("public/v1/plugins/alertifyjs/alertify.min.js")
        ];
        // css
        $this->data['appCSS'] = $this->loadCssBundle(
            [
                'v1/plugins/ms_modal/main',
                'v1/app/css/loader'
            ],
            'hours_and_earnings'
        );
        // js
        $this->data['appJs'] = $this->loadJsBundle(
            [
                'v1/plugins/ms_modal/main',
                'v1/payroll/js/regular/hours_and_earnings'
            ],
            'hours_and_earnings'
        );
        return $this
            ->loadView(
                "modules/payroll/regular/partials/hours_and_earnings"
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
        // check session and generate proper error
        $this->checkSessionStatus();
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
        // iterate
        foreach ($payrollEmployees as $value) {
            // removed unused fixed compensations
            foreach ($value['fixed_compensations'] as $k0 => $v0) {
                if ($v0["amount"] == 0) {
                    unset($value['fixed_compensations'][$k0]);
                }
            }
            // update the employee in database
            $this
                ->regular_payroll_model
                ->updateEmployeeCompensation(
                    $payrollId,
                    $value
                );
        }
        // update on Gusto
        $response = $this
            ->regular_payroll_model
            ->updatePayrollById(
                $payrollId
            );
        //
        if (!$response["errors"]) {
            // calculate the payroll
            $this
                ->regular_payroll_model
                ->calculatePayrollById(
                    $payrollId
                );
        }
        //
        return SendResponse(
            $response["errors"] ? 400 : 200,
            $response
        );
    }

    /**
     * get the regular payroll stage
     *
     * @param int $payrollId
     * @param string $stage
     */
    public function getPayrollStage(
        int $payrollId,
        string $stage
    ) {
        // check session and generate proper error
        $this->checkSessionStatus();
        // check if company is on payroll
        $this->checkForLinkedCompany(true);
        //
        $response = $this
            ->regular_payroll_model
            ->verifyPayrollStage(
                $payrollId,
                $stage
            );
        //
        if ($response) {
            $response = ["success" => true];
        } else {
            $response = [];
        }
        ///
        return SendResponse(
            200,
            $response
        );
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
     * get the regular payroll view - review
     *
     * @param int $payrollId
     * @return JSON
     */
    public function getRegularPayrollStep3(int $payrollId)
    {
        //
        $passData = [
            'payrollEmployees' => []
        ];
        // get the single payroll
        $regularPayroll = $this
            ->regular_payroll_model
            ->getRegularPayrollById(
                $this->data["companyId"],
                $payrollId
            );
        //
        $payrollEmployees = $this
            ->regular_payroll_model
            ->getPayrollEmployeesWithCompensation(
                $this->data["companyId"]
            );
        $passData['payrollEmployees'] = $payrollEmployees;
        //
        $passData['payroll'] = $regularPayroll;

        // send response
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'modules/payroll/regular/partials/review_view',
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
        // get payroll one more time
        $gustoResponse = $this
            ->regular_payroll_model
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
        // get payroll one more time
        $gustoResponse = $this
            ->regular_payroll_model
            ->setCompanyDetails(
                $this->data["companyId"]
            )
            ->cancelPayroll($payrollId);

        return SendResponse(
            $gustoResponse['errors'] ? 400 : 200,
            $gustoResponse['errors'] ?? ['msg' => "You have successfully cancelled the payroll."]
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
     * handles the stage of payroll
     */
    private function handlePayrollStatusPage()
    {
        $this->data['title'] = "Regular Payroll | ";
        // for calculating
        if ($this->data['regularPayroll']["status"] === "calculating") {
            $page = "calculatingStage";
            $this->data['title'] .= "Calculating";
            // js
            $this->data['appJs'] = $this->loadJsBundle(
                [
                    'v1/payroll/js/regular/calculating_payroll'
                ],
                'calculating_payroll'
            );
        }
        // for submitted
        if ($this->data['regularPayroll']["status"] === "submitted") {
            $page = "submittedStage";
            $this->data['title'] .= "Submitted";
            // js
            $this->data['appJs'] = $this->loadJsBundle(
                [
                    'v1/payroll/js/regular/submitted_payroll'
                ],
                'submitted_payroll'
            );
        }
        return $this->loadView("modules/payroll/regular/partials/" . $page);
    }
}
