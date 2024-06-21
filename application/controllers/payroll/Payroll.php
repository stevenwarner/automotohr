<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll extends CI_Controller
{
    //
    private $userDetails;
    private $data;
    private $pages;
    //
    private $version;
    //
    private $models;
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("Payroll_model", "pm");
        // Call helper
        $this->load->helper("payroll_helper");
        //
        $this->userDetails = [
            'first_name' => 'Steven',
            'last_name' => 'Warner',
            'email' => FROM_EMAIL_STEVEN,
            'phone' => ''
        ];
        //
        $this->resp = [];
        $this->resp['Status'] = false;
        $this->resp['Error'] = 'Request not authorized.';
        //
        $this->data = [];
        //
        $this->models = [];
        $this->models['sem'] = 'single/Employee_model';
        //
        $this->pages['header'] = 'main/header';
        $this->pages['footer'] = 'main/footer';
        //
        $this->version = 'v=' . (MINIFIED ? '1.0' : time());
        //

        if (!isCompanyOnBoard()) {
            return redirect('/dashboard');
        }
        //
        if (!isCompanyTermsAccpeted() &&  $this->uri->segment(1) != 'payroll' && $this->uri->segment(2) != 'service-terms') {
            return redirect('/payroll/service-terms');
        }
    }

    /**
     * 
     */
    function Dashboard()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Dashboard';
        $this->data['load_view'] = 0;
        $this->data['PageScripts'] = [
            '1.0.2' => 'gusto/js/company_onboard'
        ];
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/dashboard')
            ->view('main/footer');
    }

    /**
     * 
     */
    function CompanyOnboard()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Company';
        $this->data['load_view'] = 0;
        //
        $this->data['PageScripts'] = [
            time() => 'payroll/js/companySync'
        ];
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        if (!checkTermsAccepted($company_sid)) {
            $reload_location = 'payroll/service-terms';
            return redirect($reload_location, 'refresh');
        }
        //
        $this->data['company_sid'] = $company_sid;

        $this->load->model('Payroll_model', 'pm');
        $this->data['company_info'] = $this->pm->GetGustoCompanyData($company_sid);
        $this->data['companyPayrollStatus'] = $this->pm->GetCompanyPayrollStatus($company_sid);
        //
        $company_status = array();
        $onboarding_link = "";
        //
        if (!empty($this->data['company_info']['access_token'])) {
            //
            $this->load->helper("payroll_helper");
            //
            $company_status = GetCompanyStatus($this->data['company_info']);
            //
            $flow_info = CreateCompanyFlowLink($this->data['company_info'], isLoggedInPersonIsSignatory());
            //
            $onboarding_link = isset($flow_info['url']) ? $flow_info['url'] : '';
        }
        //
        $this->data['company_status'] = $company_status;
        $this->data['onboarding_link'] = $onboarding_link;
        $this->data['PageScripts'][] =
            ['1.0.2', 'gusto/js/company_onboard'];
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/company')
            ->view('main/footer');
    }

    /**
     * 
     */
    function EmployeeList($type = 'normal')
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Employees Listing';
        $this->data['load_view'] = 0;
        //
        $this->data['SelectedTab'] = $type;
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        if (!checkTermsAccepted($company_sid)) {
            $reload_location = 'payroll/service-terms';
            redirect($reload_location, 'refresh');
        }
        //
        $this->data['company_sid'] = $company_sid;
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/employees_list')
            ->view('main/footer');
    }

    /**
     * 
     */
    function ManageAdmins()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Manage Admins';
        $this->data['load_view'] = 0;
        //
        $this->data['PageScripts'] = [
            'payroll/js/admin'
        ];
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        if (!checkTermsAccepted($company_sid)) {
            $reload_location = 'payroll/service-terms';
            redirect($reload_location, 'refresh');
        }
        //
        $this->data['company_sid'] = $company_sid;
        //
        $this->data['CompanyAdmins'] = $this->pm->GetPayrollColumns(
            'payroll_company_admin',
            [
                'company_sid' => $company_sid
            ],
            'sid, first_name, last_name, email_address, phone_number, created_at, updated_at'
        );
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/manage_admin')
            ->view('main/footer');
    }

    /**
     * 
     */
    function ServiceTerms()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Service Terms';
        $this->data['load_view'] = 0;
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        $termsAccepted = $this->pm->GetPayrollColumn(
            'payroll_companies',
            [
                "company_sid" => $company_sid
            ],
            'terms_accepted, ip_address, email_address, employee_sid, accepted_at',
            false
        );

        $this->data['canSign'] = $this->pm->GetPayrollColumn(
            'payroll_company_admin',
            [
                "company_sid" => $company_sid,
                "email_address" => $session['employer_detail']['email'],
            ],
            'sid',
            true
        );
        //
        $this->data['PageScripts'] = !$termsAccepted['terms_accepted'] ? ['payroll/js/service'] : [];
        $this->data['PageScripts'][] = ['1.0.2', 'gusto/js/company_onboard'];
        //
        $this->data['acceptedData'] = $termsAccepted;
        //
        $this->data['company_sid'] = $company_sid;
        //
        $this->data['CompanyAdmins'] = $this->pm->GetPayrollColumns(
            'payroll_company_admin',
            [
                'company_sid' => $company_sid
            ],
            'sid, first_name, last_name, email_address, phone_number, created_at, updated_at'
        );
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/service_terms')
            ->view('main/footer');
    }

    /**
     * 
     */
    function Settings()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Settings';
        $this->data['load_view'] = 0;
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        if (!checkTermsAccepted($company_sid)) {
            $reload_location = 'payroll/service-terms';
            redirect($reload_location, 'refresh');
        }
        //
        $this->data['PageScripts'] = [
            'payroll/js/settings'
        ];
        //
        $this->data['payroll_settings'] = $this->pm->GetPayrollColumn(
            'payroll_settings',
            [
                'company_sid' => $company_sid
            ],
            'sid, fast_payment_limit, payment_speed',
            false
        );
        //
        if (!$this->data['payroll_settings']) {
            //
            $this->GetAndSetPaymentConfig($company_sid);
            //
            $this->data['payroll_settings'] = $this->pm->GetPayrollColumn(
                'payroll_settings',
                [
                    'company_sid' => $company_sid
                ],
                'sid, fast_payment_limit, payment_speed',
                false
            );
        }
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/configs')
            ->view('main/footer');
    }

    /**
     * 
     */
    function MyPayStubs()
    {

        //
        $data['load_view'] = check_blue_panel_status(false, 'self');
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Pay Stubs';
        //
        $this->data['PageScripts'] = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        $this->data['company_sid'] = $company_sid;
        $this->data['session'] = $session;
        //
        $myId = $session['employer_detail']['sid'];
        //
        $this->CheckAndFetchPayStubs($company_sid, $myId);
        //
        // Get employee saved paystub
        $this->data['payStubs'] = $this->pm->GetPayrollColumns(
            'payroll_employees_pay_stubs',
            [
                'employee_sid' => $myId
            ],
            'sid, payroll_uuid, s3_file_name, check_date'
        );
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/pay_stubs')
            ->view('main/footer');
    }

    /**
     * 
     */
    function MyPayrollDocuments()
    {

        //
        $data['load_view'] = check_blue_panel_status(false, 'self');
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Documents';
        //
        $this->data['PageScripts'] = [];
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        $this->data['company_sid'] = $company_sid;
        $this->data['session'] = $session;
        //
        $myId = $session['employer_detail']['sid'];
        //
        $formInfo = $this->CheckAndFetchPayrollDocuments($myId, $company_sid);
        //
        if (!empty($formInfo['Forms'])) {
            foreach ($formInfo['Forms'] as $form) {
                if (!$this->pm->checkFormExist($form['uuid'], $myId)) {
                    //                    
                    $data_to_insert = [];
                    $data_to_insert['employee_sid'] = $myId;
                    $data_to_insert['form_uuid'] = $form['uuid'];
                    $data_to_insert['name'] = $form['name'];
                    $data_to_insert['title'] = $form['title'];
                    $data_to_insert['description'] = $form['description'];
                    $data_to_insert['requires_signing'] = $form['requires_signing'];
                    $data_to_insert['created_at'] = getSystemDate();
                    //
                    $this->pm->addEmployeeForm($data_to_insert);
                }
            }
        }
        //
        $this->data['formInfo'] = $this->pm->getEmployeeForm($myId);
        // _e($this->data['formInfo'],true,true);
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/my_payroll_documents')
            ->view('main/footer');
    }

    /**
     * 
     */
    function MyDocument($formId)
    {

        //
        $data['load_view'] = check_blue_panel_status(false, 'self');
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Documents';
        //
        $this->data['PageScripts'] = [];
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        //
        $this->data['company_sid'] = $company_sid;
        $this->data['session'] = $session;
        //
        $myId = $session['employer_detail']['sid'];
        //
        $formInfo = $this->pm->getEmployeeFormInfo($formId);
        $formData = $this->GetPayrollDocument($company_sid, $formInfo['form_uuid'], $myId);
        //
        $this->data['formId'] = $formId;
        $this->data['formData'] = $formData;
        $this->data['formInfo'] = $formInfo;
        //
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');


        if ($this->form_validation->run() == false) {
            $this->load
                ->view('main/header', $this->data)
                ->view('payroll/my_payroll_document')
                ->view('main/footer');
        } else {
            //
            $signFormData = [];
            $signFormData['signature_text'] = $_POST['signature'];
            $signFormData['agree'] = true;
            $signFormData['signed_by_ip_address'] = getUserIP();
            $signedData = $this->signPayrollDocument($company_sid, $formInfo['form_uuid'], $myId, $signFormData);
            //
            if ($signedData['Status'] == 1 && empty($signedData['Form']['requires_signing'])) {
                $data_to_update = [];
                $data_to_update['is_signed'] = 1;
                $data_to_update['signature_text'] = $signFormData['signature_text'];
                $data_to_update['signed_by_ip_address'] = $signFormData['signed_by_ip_address'];
                //
                $this->pm->updateEmployeeFormInfo($formId, $data_to_update);
            }
            //
            $reload_location = 'payroll/my_payroll_documents';
            redirect($reload_location, 'refresh');
        }
    }

    /**
     * 
     */
    function Accounts()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Bank Accounts';
        $this->data['load_view'] = 0;
        // Load Company Modal
        LoadModel('scm', $this);
        // Get Company Bank Accounts
        $this->data['BankAccounts'] = $this->scm->GetBankAccounts(
            $this->data['companyId'],
            [
                'company_bank_accounts.sid',
                'company_bank_accounts.account_title',
                'company_bank_accounts.account_number',
                'company_bank_accounts.account_type',
                'company_bank_accounts.routing_number',
                'company_bank_accounts.use_for_payroll',
                'company_bank_accounts.account_uid',
                'company_bank_accounts.updated_at',
                'company_bank_accounts.verification_status',
                'u.first_name',
                'u.last_name',
                'u.access_level',
                'u.access_level_plus',
                'u.pay_plan_flag',
                'u.is_executive_admin',
                'u.job_title'
            ]
        );
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/accounts')
            ->view('main/footer');
    }

    /**
     * 
     */
    function PayrollHistory()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Create';
        $this->data['load_view'] = 0;
        $this->data['hide_employer_section'] = 1;
        // Get processed payrolls

        //
        $this->data['payrollHistory'] = $this->pm->GetPayrollColumns(
            'payroll_company_processed_history',
            [
                'company_sid' => $this->data['companyId']
            ],
            'sid, "Regular" as type, payroll_json',
            [
                'start_date', 'DESC'
            ]
        );
        // Get Gusto Company Details
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/payroll_history')
            ->view('main/footer');
    }

    /**
     * 
     */
    function PayrollSingleHistory($id)
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Create';
        $this->data['load_view'] = 0;
        $this->data['hide_employer_section'] = 1;
        //
        $this->data['payrollHistory'] = $this->pm->GetPayrollColumn(
            'payroll_company_processed_history',
            [
                'company_sid' => $this->data['companyId'],
                'sid' => $id
            ],
            'payroll_json, payroll_id',
            false
        );

        $payroll = json_decode($this->data['payrollHistory']['payroll_json'], true);
        $payrollReceipt = $this->getProcessedPayrollsReceipt($this->data['companyId'], $this->data['payrollHistory']['payroll_id'])['Response'];
        $employee_compensations = array();
        //
        foreach ($payrollReceipt['employee_compensations'] as $ekey => $employee) {
            // $employee['employee_uuid'];

            foreach ($payroll['employee_compensations'] as $compensation) {
                if ($employee['employee_uuid'] == $compensation['employee_uuid']) {
                    $employee['hourly_compensations'] = $compensation['hourly_compensations'];
                }
            }
            //
            array_push($employee_compensations, $employee);
        }
        //
        $payrollReceipt['employee_compensations'] = $employee_compensations;
        //
        $this->data['Payroll'] = $payroll;
        $this->data['payrollReceipt'] = $payrollReceipt;
        //
        // Get Gusto Company Details
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/payroll_single_history')
            ->view('main/footer');
    }
    /**
     * 
     */
    function Run()
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Create';
        $this->data['load_view'] = 0;
        $this->data['hide_employer_section'] = 1;
        //
        if (!checkTermsAccepted($this->data['companyId'])) {
            $reload_location = 'payroll/service-terms';
            redirect($reload_location, 'refresh');
        }
        // check and get payroll blockers
        $payrollBlockers = $this->payRollBlockers($this->data['companyId']);
        $this->data['payrollBlockers'] = $payrollBlockers;
        //
        if ($payrollBlockers) {
            //
            $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
            //
            return $this->load
                ->view('main/header', $this->data)
                ->view('payroll/payroll_blocker')
                ->view('main/footer');
        }
        // Get processed payrolls
        // Get the company pay periods
        $response = $this->PayPeriods($this->data['companyId']);
        //
        $payPeriods = array_filter($response['Response'], function ($period) {
            return empty($period['payroll']['processed']) && $period['payroll']['payroll_type'] == 'regular' ? true : false;
        });
        //
        $payrollInfo = array();
        //
        if ($payPeriods) {
            foreach ($payPeriods as $period) {
                //
                $payRoll = $this->GetUnProcessedPayrolls(
                    $this->data['companyId'],
                    $period['start_date'],
                    $period['end_date']
                )['Response'][0];
                //
                if ($payRoll && $period['pay_schedule_id'] == $payRoll['pay_period']['pay_schedule_id']) {
                    $payrollInfo[] = [
                        'payroll_id' => $payRoll['payroll_id'],
                        'start_date' => $payRoll['pay_period']['start_date'],
                        'end_date' => $payRoll['pay_period']['end_date'],
                        'pay_schedule_id' => $payRoll['pay_period']['pay_schedule_id'],
                        'check_date' => $payRoll['check_date'],
                        'payroll_deadline' => $payRoll['payroll_deadline'],
                        'version' => $payRoll['version']
                    ];
                }
            }
        }
        $this->data['period'] = $payrollInfo;
        //
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/create')
            ->view('main/footer');
    }

    /**
     * Run the current payroll
     */
    function RunSingle($payrolId, $version = false)
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Run Regular Payroll';
        $this->data['load_view'] = 0;
        $this->data['hide_employer_section'] = 1;
        //
        $this->data['step'] = $this->input->get('step', true) ? $this->input->get('step', true) : '1';

        //
        if ($this->data['step'] == 4) {
            $this->data['Payroll'] = $this->pm->GetSinglePayroll($payrolId);
        } else {
            //
            $this->data['Payroll'] = $this->GetSinglePayroll($payrolId, $this->data['companyId'], $this->data['step'])['Response'];
        }
        //
        if ($this->data['step'] == 3 && empty($this->data['Payroll']['calculated_at'])) {
            // Calculate Payroll
            $this->CalculatePayroll($this->data['companyId'], $payrolId);
            //
            sleep(2);
            //
            return redirect(current_url() . '?step=3');
        }
        //
        if ($this->data['processed'] && $this->data['step'] <= 3) {
            return redirect(base_url('payroll/run'));
        }
        //
        $this->pm->CheckAndInsertPayroll(
            $this->data['companyId'],
            $this->data['employerId'],
            $payrolId,
            $this->data['Payroll']
        );
        //
        $this->data['PayrollEmployees'] = $this->GetCompanyEmployees($this->data['companyId'])['Response'];
        //
        if (!empty($this->data['Payroll'])) {
            foreach ($this->data['Payroll']['employee_compensations'] as $index => $payroll) {

                if ($payroll['excluded'] == 1 && $this->data['step'] >= 3) {
                    unset($this->data['Payroll']['employee_compensations'][$index]);
                    continue;
                }
                //
                $this->data['Payroll']['employee_compensations'][$index]['employee_id'] = number_format($payroll['employee_id'], 0, '', '');
                //
                $fixed_compensations = [];
                $hourly_compensations = [];
                $paid_time_off = [];
                //
                if (!isset($payroll['payment_method'])) {
                    $this->data['Payroll']['employee_compensations'][$index]['payment_method'] = 'Direct Deposit';
                }
                //
                if (!empty($payroll['fixed_compensations'])) {
                    foreach ($payroll['fixed_compensations'] as $v) {
                        //
                        if (stringToSlug($v['name']) == 'reimbursement') {
                            $fixed_compensations[stringToSlug($v['name'])][] = $v;
                        } else {
                            $fixed_compensations[stringToSlug($v['name'])] = $v;
                        }
                    }
                }
                //
                if (!empty($payroll['hourly_compensations'])) {
                    foreach ($payroll['hourly_compensations'] as $v) {
                        $hourly_compensations[stringToSlug($v['name'])] = $v;
                    }
                }
                //
                $this->data['Payroll']['employee_compensations'][$index]['fixed_compensations'] = $fixed_compensations;
                $this->data['Payroll']['employee_compensations'][$index]['hourly_compensations'] = $hourly_compensations;
                $this->data['Payroll']['employee_compensations'][$index]['job_id'] = $this->data['PayrollEmployees'][$payroll['employee_id']]['jobs'][0]['compensations'][0]['job_id'];
            }
        }
        //
        $this->data['payrollId'] = $payrolId;
        $this->data['payrollVersion'] = $this->data['Payroll']['version'];
        $this->data['PageScripts'] = [['1.0.2', 'gusto/js/company_onboard']];
        // Get Gusto Company Details
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/create_payroll')
            ->view('main/footer');
    }

    /**
     * 
     */
    function AddEmployee($employeeId)
    {
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Add Employee To Payroll';
        $this->data['load_view'] = 0;
        // Load employee model
        $this->load->model($this->models['sem'], 'sem');
        // Get employee details
        $employee = $this->sem->GetEmployeeDetails(
            $employeeId,
            [
                'sid',
                'parent_sid',
                'email',
                'on_payroll',
                'first_name',
                'last_name',
                '"" as middle_name',
                'ssn',
                'dob',
                'full_employment_application'
            ]
        );
        //
        if (!empty($employee['full_employment_application'])) {
            //
            $ef = unserialize($employee['full_employment_application']);
            //
            $employee['middle_name'] = isset($ef['TextBoxNameMiddle']) ? $ef['TextBoxNameMiddle'] : '';
            //
            if (empty($employee['ssn']) && isset($ef['TextBoxSSN'])) {
                $employee['ssn'] = $ef['TextBoxSSN'];
            }
            //
            if (empty($employee['dob']) && isset($ef['TextBoxDOB'])) {
                $employee['dob'] = DateTime::createfromformat('m-d-Y', $ef['TextBoxDOB'])->format('Y-m-d');
            }
            //
            unset($employee['full_employment_application']);
        }
        //
        $this->data['Employee'] = $employee;
        $this->data['Payroll'] = $this->pm->EmployeeAlreadyAddedToGusto($employeeId, [
            'gusto_employee_uid',
            'created_at',
            'updated_at'
        ]);
        //
        if (!empty($this->data['Payroll'])) {
            $this->data['Employee']['on_payroll'] = 1;
        }
        //
        $this->load
            ->view('main/header', $this->data)
            ->view('payroll/add_employee')
            ->view('main/footer');
    }

    /**
     * 
     */
    function GetAddBankAccount($companyId)
    {
        //
        $d = [];
        //
        $this->checkLogin($d);
        //
        echo $this->load->view('payroll/partials/add_company_bank_account', [
            'companyId' => $companyId,
            'employerId' => $d['employerId']
        ], true);
    }

    /**
     * 
     */
    function GetEditBankAccount(
        $accountId,
        $companyId
    ) {
        //
        $d = [];
        //
        $this->checkLogin($d);
        //
        LoadModel('scm', $this);
        //
        $bankAccount = $this->scm->GetSingleBankAccounts(
            $companyId,
            $accountId,
            [
                'bank_name',
                'account_title',
                'use_for_payroll',
                'account_number',
                'routing_number',
                'account_type'
            ]
        );
        //
        echo $this->load->view('payroll/partials/edit_company_bank_account', [
            'accountId' => $accountId,
            'companyId' => $companyId,
            'employerId' => $d['employerId'],
            'bank_account' => $bankAccount
        ], true);
    }

    // AJAX Calls
    /**
     * 
     */
    function RemoveCompanyBankAccounts()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Load Company Model
        LoadModel('scm', $this);
        // Update Data
        $this->scm->DeleteBankAccounts(
            $post['accountIds'],
            $post['employeeId']
        );
        //
        res([
            'Statua' => true,
            'Message' => 'You have successfully deleted the bank accounts.'
        ]);
    }

    /**
     * 
     */
    function GetEmployeeBankAccounts($employeeId)
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'get'
        ) {
            res($this->resp);
        }
        // Load Employee Model
        LoadModel('sem', $this);
        //
        $records = $this->sem->GetBankAccounts(
            $employeeId,
            [
                'sid as account_id',
                'account_title',
                'routing_transaction_number as routing_number',
                'account_number',
                'account_type',
                'financial_institution_name as bank_name'
            ]
        );
        //
        $bankAccount = $this->pm->EmployeeAlreadyAddedToGusto($employeeId, [
            'bank_uid',
            'bank_id'
        ]);
        //
        echo $this->load->view('payroll/partials/bank_account_details', [
            'employeeId' => $employeeId,
            'bank_accounts' => $records,
            'selected' => $bankAccount
        ], true);
    }

    /**
     * Create a partner company 
     * on Gusto with the API Key
     */
    function CreatePartnerCompany()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $companyId = $this->input->post('sid', TRUE);
        // Load Company Model
        $this->load->model('single/Company_model');
        // Get company
        $companyDetails = $this->Company_model->GetCompanyDetails(
            $companyId,
            [
                'ssn as EIN',
                'CompanyName'
            ]
        );
        //
        // Check if ENI is already used
        if ($this->db->where('gusto_company_uid', $companyDetails['EIN'])->count_all_results('payroll_companies')) {
            // return if EIN already in used
            return SendResponse(200, ['errors' => ['EIN already in used.']]);
        }
        // Request
        $request =  [];
        $request['user'] =  $this->userDetails;
        $request['company'] =  [];
        $request['company']['name'] = $companyDetails['CompanyName'];
        $request['company']['trade_name'] = $companyDetails['CompanyName'];
        $request['company']['ein'] = $companyDetails['EIN'];
        //
        $response = CreatePartnerCompany($request);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $date = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertArray = [];
            $insertArray['company_sid'] = $companyId;
            $insertArray['gusto_company_sid'] = $request['company']['ein'];
            $insertArray['gusto_company_uid'] = $response['company_uuid'];
            $insertArray['refresh_token'] = $response['refresh_token'];
            $insertArray['access_token'] = $response['access_token'];
            $insertArray['created_at'] = $date;
            $insertArray['updated_at'] = $date;
            //
            $insertId = $this->pm->AddCompany($insertArray);
            $this->pm->UpdateCompany($companyId, ['on_payroll' => 1]);
            //
            res([
                'Status' => true,
                'Message' => 'You have successfully created the company on Gusto.',
                'Id' => $insertId
            ]);
        }
    }

    /**
     * 
     */
    function AddEmployeeToPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $updArray = [];
        //
        $updArray['first_name'] = $post['first_name'];
        $updArray['last_name'] = $post['last_name'];
        $updArray['ssn'] = $post['ssn'];
        $updArray['dob'] = $post['dob'];
        // Load Employee Model
        $this->load->model($this->models['sem'], 'sem');
        // Update Data
        $this->sem->Update($updArray, ['sid' => $post['id']]);
        //
        $company = $this->pm->GetCompany($post['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $request =  [];
        $request['first_name'] = $post['first_name'];
        $request['middle_initial'] = $post['middle_name'];
        $request['last_name'] = $post['last_name'];
        $request['date_of_birth'] = $post['dob'];
        $request['email'] = $post['email'];
        $request['ssn'] = $post['ssn'];
        //
        $response = AddEmployeeToCompany($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $date = date('Y-m-d H:i:s', strtotime('now'));
            //
            $insertArray = [];
            $insertArray['company_sid'] = $post['companyId'];
            $insertArray['employee_sid'] = $post['id'];
            $insertArray['gusto_employee_id'] = $response['id'];
            $insertArray['gusto_employee_uid'] = $response['uuid'];
            $insertArray['created_at'] = $date;
            $insertArray['updated_at'] = $date;
            //
            $insertId = $this->pm->AddEmployeeCompany($insertArray);
            // Update Data
            $this->sem->Update(['on_payroll' => 1], ['sid' => $post['id']]);
            //
            res([
                'Status' => true,
                'Message' => 'You have successfully added the employee on Gusto.',
                'Id' => $insertId
            ]);
        }
    }

    /**
     * 
     */
    function AddBankAccountToPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $bankAccount = $this->pm->EmployeeAlreadyAddedToGusto($post['employeeId'], [
            'sid',
            'company_sid',
            'gusto_employee_uid',
            'bank_uid',
            'bank_id'
        ]);
        //
        if (empty($bankAccount)) {
            res([
                'Status' => false,
                'Errors' => 'Please add the employee on payroll first.'
            ]);
        }
        //
        $company = $this->pm->GetCompany($bankAccount['company_sid'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        if (!empty($bankAccount['bank_uid'])) {
            // Remove the old ones
            DeleteBankAccountToPayroll([
                'bank_account_id' => $bankAccount['bank_uid'],
                'employee_id' => $bankAccount['gusto_employee_uid']
            ], $company);
            // All okay to go
            $updateArray = [];
            $updateArray['bank_id'] = NULL;
            $updateArray['bank_uid'] = NULL;
            $updateArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            // Update Data
            $this->pm->UpdatePCE($updateArray, ['sid' => $bankAccount['sid']]);
        }
        //
        $request =  [];
        $request['name'] = $post['name'];
        $request['routing_number'] = $post['routing_number'];
        $request['account_number'] = $post['account_number'];
        $request['account_type'] = ucfirst($post['account_type']);
        //
        $company['employeeId'] = $bankAccount['gusto_employee_uid'];
        //
        $response = AddBankAccountToPayroll($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $updateArray = [];
            $updateArray['bank_id'] = $post['id'];
            $updateArray['bank_uid'] = $response['uuid'];
            $updateArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            // Update Data
            $this->pm->UpdatePCE($updateArray, ['sid' => $bankAccount['sid']]);
            //
            res([
                'Status' => true,
                'Message' => 'You have successfully updated the bank account for payroll.',
            ]);
        }
    }

    /**
     * 
     */
    function AddCompanyBankAccountToPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Load company model
        LoadModel('scm', $this);
        // Lets add company account to company
        $bankAccountId = $this->scm->AddBankAccount([
            'company_sid' => $post['companyId'],
            'account_title' => $post['account_title'],
            'bank_name' => $post['bank_name'],
            'account_number' => $post['account_number'],
            'routing_number' => $post['routing_number'],
            'account_type' => $post['account_type'],
            'use_for_payroll' => $post['use_for_payroll'] == 'true' ? 1 : 0,
            'created_by' => $post['employerId'],
            'last_updated_by' => $post['employerId'],
            'created_at' => date('Y-m-d H:i:s', strtotime('now')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('now'))
        ]);
        //
        if ($post['use_for_payroll'] == 'false') {
            res([
                'Status' => true,
                'Response' => 'You have successfully added a new bank account',
            ]);
        }
        //
        $company = $this->pm->GetCompany($post['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);

        //
        $request =  [];
        $request['routing_number'] = $post['routing_number'];
        $request['account_number'] = $post['account_number'];
        $request['account_type'] = ucfirst($post['account_type']);
        //
        $response = AddCompanyBankAccountToPayroll($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $updateArray = [];
            $updateArray['account_uid'] = $response['uuid'];
            $updateArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            // Update Data
            $this->scm->Update(['account_uid' => NULL, 'use_for_payroll' => 0], ['company_sid' => $post['companyId']]);
            $this->scm->Update($updateArray, ['sid' => $bankAccountId]);
            //
            res([
                'Status' => true,
                'Response' => 'You have successfully added a bank account for payroll.',
            ]);
        }
    }

    /**
     * 
     */
    function EditCompanyBankAccountToPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Load company model
        LoadModel('scm', $this);
        //
        $bankAccountId = $post['accountId'];
        // Lets add company account to company
        $this->scm->UpdateBankAccount([
            'account_title' => $post['account_title'],
            'bank_name' => $post['bank_name'],
            'account_number' => $post['account_number'],
            'routing_number' => $post['routing_number'],
            'account_type' => $post['account_type'],
            'use_for_payroll' => $post['use_for_payroll'] == 'true' ? 1 : 0,
            'last_updated_by' => $post['employerId'],
            'updated_at' => date('Y-m-d H:i:s', strtotime('now'))
        ], ['sid' => $bankAccountId]);
        //
        if ($post['use_for_payroll'] == 'false') {
            res([
                'Status' => true,
                'Response' => 'You have successfully updated a new bank account',
            ]);
        }
        //
        $company = $this->pm->GetCompany($post['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);

        //
        $request =  [];
        $request['routing_number'] = $post['routing_number'];
        $request['account_number'] = $post['account_number'];
        $request['account_type'] = ucfirst($post['account_type']);
        //
        $response = AddCompanyBankAccountToPayroll($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $updateArray = [];
            $updateArray['account_uid'] = $response['uuid'];
            $updateArray['verification_status'] = $response['verification_status'];
            $updateArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            // Update Data
            $this->scm->Update(['account_uid' => NULL, 'use_for_payroll' => 0], ['company_sid' => $post['companyId']]);
            $this->scm->Update($updateArray, ['sid' => $bankAccountId]);
            //
            res([
                'Status' => true,
                'Response' => 'You have successfully updated a bank account for payroll.',
            ]);
        }
    }

    /**
     * 
     */
    function UpdateCompanyBankAccount()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Load company model
        LoadModel('scm', $this);
        //
        $bankAccountId = $post['accountId'];
        //
        $bankAccount = $this->scm->GetSingleBankAccounts(
            $post['companyId'],
            $post['accountId'],
            [
                'account_number',
                'routing_number',
                'account_type'
            ]
        );
        //
        $company = $this->pm->GetCompany($post['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);

        //
        $request =  [];
        $request['routing_number'] = $bankAccount['routing_number'];
        $request['account_number'] = $bankAccount['account_number'];
        $request['account_type'] = ucfirst($bankAccount['account_type']);
        //
        $response = AddCompanyBankAccountToPayroll($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            // All okay to go
            $updateArray = [];
            $updateArray['account_uid'] = $response['uuid'];
            $updateArray['verification_status'] = $response['verification_status'];
            $updateArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            // Update Data
            $this->scm->Update(['account_uid' => NULL, 'use_for_payroll' => 0], ['company_sid' => $post['companyId']]);
            $this->scm->Update($updateArray, ['sid' => $bankAccountId]);
            //
            res([
                'Status' => true,
                'Response' => 'You have successfully added a bank account for payroll.',
            ]);
        }
    }

    /**
     * 
     */
    function UpdatePayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() &&
            $this->input->method() != 'post' &&
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $data = [];
        //
        $this->checkLogin($data);
        //
        $post = $this->input->post(NULL, TRUE);
        // Make request array
        $employeeArray = [];

        
        //
        foreach ($post['payroll'] as $payroll) {
            //
            unset($payroll['fixedCompensations']['reimbursement']);
            unset($payroll['paid_time_off_amount']);
            // Temporary Array
            $ta = [];
            $ta['employee_id'] = $payroll['employeeId'];
            $ta['excluded'] = $payroll['excluded'];
            if (isset($payroll['paymentMethod'])) {
                $ta['payment_method'] = $payroll['paymentMethod'];
            }
            $ta['fixed_compensations'] = array_values($payroll['fixedCompensations']);
            $ta['hourly_compensations'] = array_values($payroll['hourlyCompensations']);
            $ta['paid_time_off'] = isset($payroll['paidTimeOff']) ? array_values($payroll['paidTimeOff']) : [];
            //
            if (isset($payroll['reimbursements'])) {
                //
                $reimbursements = $payroll['reimbursements'];
                //
                foreach ($reimbursements as $reimbursement) {
                    $reimbursement['job_id'] = isset($ta['fixed_compensations'][0]['job_id']) ? $ta['fixed_compensations'][0]['job_id'] : $payroll['jobId'];
                    $reimbursement['name'] = 'Reimbursement';
                    $ta['fixed_compensations'][] = $reimbursement;
                }
            } else {
                $ta['fixed_compensations'][] = [
                    'job_id' => $payroll['jobId'],
                    'name' => 'Reimbursement',
                    'amount' => 0.00
                ];
            }
            //
            $employeeArray[] = $ta;
        }
        //
        $company = $this->pm->GetCompany($data['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $company['payroll_id'] = $post['payrollId'];
        //
        $request = [];
        $request['version'] = $post['payrollVersion'];
        $request['employee_compensations'] = $employeeArray;
        //
        $response = UpdatePayrollById($request, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else if (isset($response['message'])) {

            // Error took place
            res([
                'Status' => false,
                'Message' => $response['message']
            ]);
        } else {
            //
            res([
                'Status' => true,
                'Response' => $post['payroll']
            ]);
        }
    }

    /**
     * 
     */
    function CancelPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() &&
            $this->input->method() != 'post' &&
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $data = [];
        //
        $this->checkLogin($data);
        //
        $post = $this->input->post(NULL, TRUE);
        // Make request array
        //
        $company = $this->pm->GetCompany($data['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $company['payroll_id'] = $post['payrollId'];
        //
        $response = CancelPayrollById($company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            $this->pm->DeletePayroll('payrolls', ['payroll_id' => $company['payroll_id']]);
            //
            res([
                'Status' => true,
                'Response' => $response
            ]);
        }
    }

    /**
     * 
     */
    function UpdatePayrollForDemo($employees, $version, $startDate, $endDate)
    {
        //
        $data = [];
        //
        $this->checkLogin($data);
        // Make request array
        $company = $this->pm->GetCompany($data['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        UpdatePayrollForDemo($company, $employees, $version, $startDate, $endDate);
    }

    /**
     * 
     */
    function SubmitPayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() &&
            $this->input->method() != 'post' &&
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //
        $data = [];
        //
        $this->checkLogin($data);
        //
        $post = $this->input->post(NULL, TRUE);
        // Make request array
        //
        $company = $this->pm->GetCompany($data['companyId'], [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $company['payroll_id'] = $post['payrollId'];
        //
        $response = SubmitPayrollById($company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            res([
                'Status' => true,
                'Response' => $response
            ]);
        }
    }

    /**
     * 
     */
    private function GetUnProcessedPayrolls($companyId, $startDate, $endDate)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);

        //
        $query = '?processing_statuses=unprocessed&payroll_types=regular&start_date=' . ($startDate) . '&end_date=' . ($endDate) . '';
        //
        $response = GetUnProcessedPayrolls($query, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function GetProcessedPayrolls($companyId, $startDate = '')
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $query = '?processed=true&include=taxes,benefits,deductions&show_calculation=true';
        //
        if (!empty($startDate)) {
            $query .= '&start_date=' . $startDate;
        }
        //
        $response = GetUnProcessedPayrolls($query, $company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function GetSinglePayroll($payrollId, $companyId, $step)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $company['payroll_id'] = $payrollId;
        //
        $query = '';
        //
        $response = GetSinglePayroll($query, $company, $step);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function GetCompanyEmployees($companyId)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetCompanyEmployees($company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            if (!empty($response)) {
                //
                $emps = [];
                //
                foreach ($response as $emp) {
                    //
                    $id = SnToString($emp['id']);
                    //
                    $emps[$id] = $emp;
                    //
                    if (!empty($emps[$id]['jobs'])) {
                        foreach ($emps[$id]['jobs'] as $index => $value) {
                            //
                            $emps[$id]['jobs'][SnToString($value['id'])] = $value;
                        }
                    }
                }
                //
                $response = $emps;
            }
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function CalculatePayroll($companyId, $payrollId)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $company['payroll_id'] = $payrollId;
        //
        $response = CalculatePayroll($company);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response
            ];
        }
    }



    /**
     * 
     */
    function RefreshToken()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post())
        ) {
            res($this->resp);
        }
        //        
        $companyId = $this->input->post('sid', TRUE);
        //
        $company = $this->pm->GetCompany(
            $companyId,
            [
                'gusto_company_uid',
                'access_token',
                'refresh_token'
            ]
        );
        //
        $response = RefreshToken([
            'access_token' => $company['access_token'],
            'refresh_token' => $company['refresh_token']
        ]);
        //
        if (isset($response['access_token'])) {
            $this->pm->UpdatePC([
                'old_access_token' => $company['access_token'],
                'old_refresh_token' => $company['refresh_token'],
                'access_token' => $response['access_token'],
                'refresh_token' => $response['refresh_token']
            ], [
                'company_sid' => $companyId
            ]);
            //
        }
        //
        return $response;
    }


    // As of 12/09/2021
    //
    private function PayPeriods($companyId)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        $response = PayPeriods($company, date('Y-m-t', strtotime('-2 month')));
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            return ([
                'Status' => false,
                'Errors' => $errors
            ]);
            // res([
            //     'Status' => false,
            //     'Errors' => $errors
            // ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response
            ];
        }
    }


    //
    /**
     * Check user session and set data
     * 
     * @employee Mubashir Ahmed
     * @date     02/02/2021
     *
     * @param Reference $data
     * @param Bool      $return (Default is 'FALSE')
     * 
     * @return VOID
     */
    private function checkLogin(&$data, $return = FALSE)
    {
        //
        if (!$this->session->userdata('logged_in')) {
            if ($return) {
                return false;
            }
            redirect('login', 'refresh');
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        //
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['companyName'] = $data['session']['company_detail']['CompanyName'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['employerRole'] = $data['session']['employer_detail']['access_level'];
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        $data['employee'] = $data['session']['employer_detail'];
        //
        if ($return) {
            return true;
        } else {
            //
            $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
        }
    }

    //
    private function CheckAndFetchPayStubs($companyId, $employeeId)
    {
        // Get company
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_sid'
        ]);

        // Get employee UUID
        $employeeUid = $this->pm->GetPayrollColumn(
            'payroll_employees',
            [
                'employee_sid' => $employeeId
            ],
            'payroll_employee_uuid'
        );
        // Get employee saved paystubs
        $paystubs = $this->pm->GetPayrollColumns(
            'payroll_employees_pay_stubs',
            [
                'employee_sid' => $employeeId
            ],
            'payroll_uuid'
        );
        //
        if (!empty($paystubs)) {
            $paystubs = array_column($paystubs, 'payroll_uuid');
        }
        // Get all processed payrolls
        $query = '?processed=true';
        //
        $response = GetUnProcessedPayrolls($query, $company);
        //
        if (isset($response['errors'])) {
            return [];
        }
        //
        foreach ($response as $payroll) {
            //
            if (in_array($payroll['payroll_uuid'], $paystubs)) {
                continue;
            }
            // 
            foreach ($payroll['employee_compensations'] as $ec) {
                //
                //
                if ($employeeUid == $ec['employee_uuid']) {
                    $stubResponse = GetEmployeePayStubs($payroll['payroll_uuid'], $employeeUid, $company);
                    //
                    if (isset($stubResponse['s3_file_url'])) {
                        //
                        $this->db
                            ->insert('payroll_employees_pay_stubs', [
                                'employee_sid' => $employeeId,
                                'payroll_uuid' => $payroll['payroll_uuid'],
                                'processed_date' => $payroll['processed_date'],
                                'payroll_deadline' => $payroll['payroll_deadline'],
                                'check_date' => $payroll['check_date'],
                                'start_date' => $payroll['pay_period']['start_date'],
                                'end_date' => $payroll['pay_period']['end_date'],
                                'pay_schedule_uuid' => $payroll['pay_period']['pay_schedule_uuid'],
                                's3_file_name' => $stubResponse['s3_file_name'],
                                'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                                'updated_at' => date('Y-m-d H:i:s', strtotime('now')),
                                'status' => 1
                            ]);
                    }
                }
            }
        }
    }

    //
    public function PrivateFile($action, $id)
    {
        // Validate details
        $data = $this->pm->GetPayrollColumn(
            'payroll_employees_pay_stubs',
            [
                'sid' => $id,
                'employee_sid' => $this->session->userdata('logged_in')['employer_detail']['sid']
            ],
            's3_file_name, check_date',
            false
        );
        //
        if (empty($data)) {
            return redirect('dashboard');
        }
        $s3_file_name = $data['s3_file_name'];
        $check_date = str_replace('-', '_', $data['check_date']);
        //
        if ($action === 'download') {
            //
            $this->load->library('aws_lib');
            //
            $fileData = $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $s3_file_name);
            //
            header('Content-Description: File Transfer');
            header('Content-Disposition: attachment; filename=' . ($check_date) . '_paystub_.pdf');
            header('Content-Type: ' . $fileData['ContentType']);
            echo $fileData['Body'];
            return;
        }
        //
        return redirect('dashboard');
    }

    private function CheckAndGetProcessedPayrolls($companyId)
    {
        // Check for payroll history
        $history = $this->pm->GetPayrollColumns(
            'payrolls',
            [
                'company_sid' => $companyId
            ],
            'payroll_uid, start_date',
            [
                'start_date', 'DESC'
            ]
        );
        //
        $date = '';
        //
        if (!empty($history)) {
            $date = $history[0]['start_date'];
            $history = array_column($history, 'payroll_uid');
        }
        //
        $payrolls = $this->GetProcessedPayrolls($companyId, $date);
        //
        if (isset($payrolls['errors'])) {
            return [];
        }
        //
        foreach ($payrolls['Response'] as $payroll) {
            //
            if (in_array($payroll['payroll_uuid'], $history)) {
                continue;
            }
            //
            $insertArray = [];
            $insertArray['company_sid'] = $companyId;
            $insertArray['payroll_id'] = $payroll['payroll_id'];
            $insertArray['payroll_uid'] = $payroll['payroll_uuid'];
            $insertArray['version'] = $payroll['version'];
            $insertArray['start_date'] = $payroll['pay_period']['start_date'];
            $insertArray['end_date'] = $payroll['pay_period']['end_date'];
            $insertArray['check_date'] = $payroll['check_date'];
            $insertArray['deadline_date'] = $payroll['payroll_deadline'];
            $insertArray['payroll_json'] = json_encode($payroll);
            $insertArray['is_processed'] = 1;
            $insertArray['created_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];
            $insertArray['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
            $insertArray['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $this->db->insert('payrolls', $insertArray);
        }
    }

    /**
     * 
     */
    private function GetAndSetPaymentConfig($companyId)
    {
        // Get company
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetPaymentConfig($company);
        //
        if (isset($response['name'])) {
            return;
        }
        //
        $ai = [];
        $ai['company_sid'] = $companyId;
        $ai['last_updated_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];
        $ai['created_at'] = $ai['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $ai['fast_payment_limit'] = $response['fast_payment_limit'];
        $ai['payment_speed'] = $response['payment_speed'];
        $ai['partner_uid'] = $response['partner_uuid'];
        //
        $this->pm->InsertPayroll(
            'payroll_settings',
            $ai
        );
    }

    /**
     * 
     */
    private function getProcessedPayrollsReceipt($companyId, $payrollUid)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = getProcessedPayrollsReceipt($payrollUid, $company, [
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function getPayrollsEmployeesCompensations($companyId, $payrollUid)
    {
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = getPayrollsEmployeesCompensations($payrollUid, $company, [
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else {
            //
            return [
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function CheckAndFetchPayrollDocuments($employeeId, $companyId)
    {
        //
        $returnData =  [
            'Status' => false,
            'Message' => 'No Document Found',
            'Forms' => []
        ];
        //
        $checkPrerequisite = $this->pm->checkEmployeeDocumentPrerequisite($employeeId);
        //
        if ($checkPrerequisite['data_missing']) {
            return $returnData;
        } else {
            //
            $company = $this->pm->GetCompany($companyId, [
                'access_token',
                'refresh_token',
                'gusto_company_uid'
            ]);
            //
            $response = getEmployeePayrollsDocuments($company, $checkPrerequisite['payroll_employee_uuid'], [
                'X-Gusto-API-Version: 2024-03-01'
            ]);
            //
            if (isset($response['errors'])) {
                return $returnData;
            } else {
                //
                $returnData['Status'] = true;
                $returnData['Message'] = 'Document Found';
                $returnData['Forms'] = $response;
                //
                return $returnData;
            }
        }
    }

    /**
     * 
     */
    private function GetPayrollDocument($companyId, $formUUID, $employeeId)
    {
        //
        $returnData =  [
            'Status' => false,
            'Message' => 'No Form Found',
            'Form' => []
        ];
        //
        $employeeUUID = $this->pm->getEmployeeUUID($employeeId);
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = getEmployeeFormContent($company, $employeeUUID, $formUUID, [
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            return $returnData;
        } else {
            //
            $returnData['Status'] = true;
            $returnData['Message'] = 'Document Found';
            $returnData['Form'] = $response;
            //
            return $returnData;
        }
    }

    /**
     * 
     */
    private function signPayrollDocument($companyId, $formUUID, $employeeId, $formData)
    {
        //
        $returnData =  [
            'Status' => false,
            'Message' => 'No Form Found',
            'Form' => []
        ];
        //
        $employeeUUID = $this->pm->getEmployeeUUID($employeeId);
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = signPayrollEmployeeForm($company, $employeeUUID, $formUUID, $formData, [
            'X-Gusto-API-Version: 2024-03-01'
        ]);
        //
        if (isset($response['errors'])) {
            return $returnData;
        } else {
            //
            $returnData['Status'] = true;
            $returnData['Message'] = 'Document Found';
            $returnData['Form'] = $response;
            //
            return $returnData;
        }
    }

    /**
     * get the payroll blockers
     *
     * @param int $companyId
     * @return array
     */
    private function payRollBlockers(int $companyId)
    {
        // get company information
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        // get blockers
        $response = payRollBlockers($company);
        // check and set errors
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        return $response;
    }
}
