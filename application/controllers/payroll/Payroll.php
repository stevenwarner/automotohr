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
    function Create(){
        //
        $this->checkLogin($this->data);
        //
        $this->data['title'] = 'Payroll | Create';
        $this->data['load_view'] = 0;
        // Get Gusto Company Details
        $company = $this->pm->GetCompany(
            $this->data['companyId'], [
                'gusto_company_uid',
                'access_token',
                'refresh_token'
            ]
        );
        // Start payroll on Gusto
        // $resp = PayPeriods($company);
        $resp = Payrolls($company);

        _e($resp, true, true);

        //
        $this->load
        ->view('main/header', $this->data)
        ->view('payroll/create')
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

    // AJAX Calls

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