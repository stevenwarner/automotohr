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
        $this->load->model("v1/External_payroll_model", "external_payroll_model");
        // set the logged in user id
        $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'] ?? 0;
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/external/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/external/';
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
            $this->css
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/main'
            ],
            $this->js
        );
        // get all external payrolls
        $data['externalPayrolls'] =
            $this->external_payroll_model
            ->getAllCompanyExternalPayrolls(
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
            'add-external'
        );
        //
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/add'
            ],
            $this->js,
            'add-external'
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
        // check  external payroll
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
        // add css
        $data['appCSS'] = bundleCSS(
            [
                'v1/app/css/loader'
            ],
            $this->css,
            'employee'
        );
        // add js
        $data['appJs'] = bundleJs(
            [
                'js/app_helper',
                'v1/payroll/js/external/employee'
            ],
            $this->js,
            'employee'
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
                    'external_payroll_items',
                    'applicable_earnings',
                    'applicable_benefits',
                    'applicable_taxes',
                ]
            );
        // decode json
        $externalPayrollDetails['external_payroll_items'] = json_decode($externalPayrollDetails['external_payroll_items'], true);
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
        //
        $this->load
            ->view('main/header', $data)
            ->view('v1/payroll/external/employee')
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
}
