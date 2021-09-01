<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll extends CI_Controller
{

    //
    private $userDetails;
    private $data;
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
            'first_name'=> 'Steven',
            'last_name'=> 'Warner',
            'email'=> FROM_EMAIL_STEVEN,
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
    }

    /**
     * 
     */
    function Dashboard(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Dashboard';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/dashboard')
        ->view('main/footer');
    }

    /**
     * 
     */
    function EmployeeList(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Employees Listing';
        $this->data['load_view'] = 0;
        // Load Employee Model
        $this->load->model($this->models['sem'], 'sem');
        //
        $this->data['Employees'] = $this->sem->GetCompanyEmployees(
            $this->data['companyId'],
            true
        );
        //
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/employees_list')
        ->view('main/footer');
    }
    
    /**
     * 
     */
    function Accounts(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Bank Accounts';
        $this->data['load_view'] = 0;
        // Load Company Modal
        LoadModel('scm', $this);
        // Get Company Bank Accounts
        $this->data['BankAccounts'] = $this->scm->GetBankAccounts(
            $this->data['companyId'], [
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
    function Settings(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Settings';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/settings')
        ->view('main/footer');
    }
    
    /**
     * 
     */
    function Create($payrolId = false){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Create';
        $this->data['load_view'] = 1;
        $this->data['hide_employer_section'] = 1;
        //
        $this->data['step'] = $this->input->get('step', true) ? $this->input->get('step', true) : '1';
        //
        if(!$payrolId){
            //
            $this->data['UnProcessedPayrolls'] = [];
            //
            $response = $this->GetUnProcessedPayrolls($this->data['companyId']);
            //
            if($response['Status']){
                //
                $this->data['UnProcessedPayrolls'] = $response['Response'];
                //
                $payrolls = [];
                //
                if(!empty($this->data['UnProcessedPayrolls'])){
                    foreach($this->data['UnProcessedPayrolls'] as $payroll){
                        if(!empty($payroll['payroll_uuid'])){
                            $payrolls[$payroll['payroll_uuid']] = $payroll;
                            //
                            unset(
                                $payrolls[$payroll['payroll_uuid']]['employee_compensations']
                            );
                        }
                    }
                }
                //
                $this->data['UnProcessedPayrolls'] = $payrolls;
            }
        }else{
            //
            $this->data['PayrollEmployees'] = $this->GetCompanyEmployees($this->data['companyId'])['Response'];
            $this->data['Payroll'] = $this->GetSinglePayroll($payrolId, $this->data['companyId'])['Response'];
            //
            if(!empty($this->data['Payroll'])){
                foreach($this->data['Payroll']['employee_compensations'] as $index => $payroll){
                    //
                    $this->data['Payroll']['employee_compensations'][$index]['employee_id'] = number_format($payroll['employee_id'], 0, '', '');
                    //
                    $fixed_compensations = [];
                    $hourly_compensations = [];
                    $paid_time_off = [];
                    //
                    if(!empty($payroll['fixed_compensations'])){
                        foreach($payroll['fixed_compensations'] as $v){
                            //
                            if(stringToSlug($v['name']) == 'reimbursement'){
                                $fixed_compensations[stringToSlug($v['name'])][] = $v;
                            } else{
                                $fixed_compensations[stringToSlug($v['name'])] = $v;
                            }
                        }
                    }
                    //
                    if(!empty($payroll['hourly_compensations'])){
                        foreach($payroll['hourly_compensations'] as $v){
                            $hourly_compensations[stringToSlug($v['name'])] = $v;
                        }
                    }
                    //
                    if(!empty($payroll['paid_time_off'])){
                        foreach($payroll['paid_time_off'] as $v){
                            $paid_time_off[stringToSlug($v['name'])] = $v;
                        }
                    }
                    //
                    $this->data['Payroll']['employee_compensations'][$index]['fixed_compensations'] = $fixed_compensations;
                    $this->data['Payroll']['employee_compensations'][$index]['hourly_compensations'] = $hourly_compensations;
                    $this->data['Payroll']['employee_compensations'][$index]['paid_time_off'] = $paid_time_off;
                }
            }
        }
        //
        $this->data['payrollId'] = $payrolId;
        $this->data['payrollVersion'] = $this->data['Payroll']['version'];
        // Get Gusto Company Details
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/'.($payrolId ? 'create_payroll' : 'create').'')
        ->view('main/footer');
    }
    
    
    /**
     * 
     */
    function AddEmployee($employeeId){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Add Employee To Payroll';
        $this->data['load_view'] = 0;
        // Load employee model
        $this->load->model($this->models['sem'], 'sem');
        // Get employee details
        $employee = $this->sem->GetEmployeeDetails(
            $employeeId, [
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
        if(!empty($employee['full_employment_application'])){
            //
            $ef = unserialize($employee['full_employment_application']);
            //
            $employee['middle_name'] = isset($ef['TextBoxNameMiddle']) ? $ef['TextBoxNameMiddle'] : '';
            //
            if(empty($employee['ssn']) && isset($ef['TextBoxSSN'])){
                $employee['ssn'] = $ef['TextBoxSSN'];
            }
            //
            if(empty($employee['dob']) && isset($ef['TextBoxDOB'])){
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
        if(!empty($this->data['Payroll'])){
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
    function GetAddBankAccount($companyId){
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
    ){
        //
        $d = [];
        //
        $this->checkLogin($d);
        //
        LoadModel('scm', $this);
        //
        $bankAccount= $this->scm->GetSingleBankAccounts(
            $companyId,
            $accountId, [
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
    function RemoveCompanyBankAccounts(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
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
    function GetEmployeeBankAccounts($employeeId){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'get'
        ){
            res($this->resp);
        }
        // Load Employee Model
        LoadModel('sem', $this);
        //
        $records = $this->sem->GetBankAccounts(
            $employeeId, [
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
    function CreatePartnerCompany(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
            res($this->resp);
        }
        //
        $companyId = $this->input->post('sid', TRUE);
        // Load Company Model
        $this->load->model('single/Company_model');
        // Get company
        $companyDetails = $this->Company_model->GetCompanyDetails(
            $companyId, [
                'ssn as EIN',
                'CompanyName'
            ]
        );
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    function AddEmployeeToPayroll(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    function AddBankAccountToPayroll(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
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
        if(empty($bankAccount)){
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
        if(!empty($bankAccount['bank_uid'])){
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    function AddCompanyBankAccountToPayroll(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
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
        if($post['use_for_payroll'] == 'false'){
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    function EditCompanyBankAccountToPayroll(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
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
        if($post['use_for_payroll'] == 'false'){
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    function UpdateCompanyBankAccount(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Load company model
        LoadModel('scm', $this);
        //
        $bankAccountId = $post['accountId'];
        //
        $bankAccount= $this->scm->GetSingleBankAccounts(
            $post['companyId'],
            $post['accountId'], [
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
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
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
    private function GetUnProcessedPayrolls($companyId){
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        
        //
        $query = '?processed=false';
        //
        $response = GetUnProcessedPayrolls($query, $company);
        //
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
            //
            return[
                'Status' => true,
                'Response' => $response,
            ];
        }
    }

    /**
     * 
     */
    private function GetSinglePayroll($payrollId, $companyId){
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
        $response = GetSinglePayroll($query, $company);
        //
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
            //
            return[
                'Status' => true,
                'Response' => $response,
            ];
        }
    }
    
    /**
     * 
     */
    private function GetCompanyEmployees($companyId){
        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetCompanyEmployees($company);
        //
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
            //
            if(!empty($response)){
                //
                $emps = [];
                //
                foreach($response as $emp){
                    //
                    $id = SnToString($emp['id']);
                    //
                    $emps[$id] = $emp;
                    //
                    if(!empty($emps[$id]['jobs'])){
                        foreach($emps[$id]['jobs'] as $index => $value){
                            //
                            $emps[$id]['jobs'][SnToString($value['id'])] = $value;
                        }
                    }
                }
                //
                $response = $emps;
            }
            //
            return[
                'Status' => true,
                'Response' => $response,
            ];
        }
    }
    
    /**
     * 
     */
    function UpdatePayroll(){
        //
        if(
            !$this->input->is_ajax_request() &&
            $this->input->method() != 'post' &&
            empty($this->input->post())
        ){
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // Make request array
        $employeeArray = [];
        //
        foreach($post['payroll'] as $payroll){
            // Temporary Array
            $ta = [];
            $ta['employee_id'] = $payroll['employeeId'];
            $ta['fixed_compensations'] = [];
            // $ta['fixed_compensations'][] = [
            //     'name' => $payroll[''],
            //     'amount' => $payroll[''],
            // ];
        }
        //
        _e($post, true, true);
        die;

        //
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetCompanyEmployees($company);
        //
        if(isset($response['errors'])){
            //
            $errors = [];
            //
            foreach($response['errors'] as $error){
                $errors[] = $error[0];
            }
            // Error took place
            res([
                'Status' => false,
                'Errors' => $errors
            ]);
        } else{
            //
            if(!empty($response)){
                //
                $emps = [];
                //
                foreach($response as $emp){
                    //
                    $id = SnToString($emp['id']);
                    //
                    $emps[$id] = $emp;
                    //
                    if(!empty($emps[$id]['jobs'])){
                        foreach($emps[$id]['jobs'] as $index => $value){
                            //
                            $emps[$id]['jobs'][SnToString($value['id'])] = $value;
                        }
                    }
                }
                //
                $response = $emps;
            }
            //
            return[
                'Status' => true,
                'Response' => $response,
            ];
        }
    }


    /**
     * 
     */
    function RefreshToken(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post()) 
        ){
            res($this->resp);
        }
        //        
        $companyId = $this->input->post('sid', TRUE);
        //
        $company = $this->pm->GetCompany(
            $companyId, [
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
        if(isset($response['access_token'])){
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
    private function checkLogin(&$data, $return = FALSE){
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
        $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        $data['employee'] = $data['session']['employer_detail'];
        //
        if ($return) {
            return true;
        }
        else {
            //
            $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
        }
    }
}