<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll extends CI_Controller
{

    //
    private $userDetails;
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
    function AddEmployeeToCompany(){
        //
        if(
            !$this->input->is_ajax_request() ||
            $this->input->method() !== 'post' ||
            empty($this->input->post) 
        ){
            res($this->resp);
        }
        
        $employeeId = $this->input->post('sid', TRUE);
        $employeeId = 11712;
        // Check if employee was already added to Gusto
        if($this->pm->EmployeeAlreadyAddedToGusto($employeeId, ['sid'])){
            res([
                'Status' => false,
                'Message' => 'Employee already added.'
            ]);
        }
        // Load employee Model
        $this->load->model('single/Employee_model');
        // Get company
        $employeeDetails = $this->Employee_model->GetEmployeeDetails(
            $employeeId, [
                'first_name',
                'last_name',
                'email',
                'dob',
                'ssn',
                'parent_sid'
            ]
        );
        //
        $company = $this->pm->GetCompany(
            $employeeDetails['parent_sid'], [
                'gusto_company_uid',
                'access_token',
                'refresh_token'
            ]
        );
        // Mock Request
        $request =  [];
        $request['first_name'] = $employeeDetails['first_name'];
        $request['middle_initial'] = '';
        $request['last_name'] = $employeeDetails['last_name'];
        $request['date_of_birth'] = $employeeDetails['dob'];
        $request['email'] = $employeeDetails['email'];
        $request['ssn'] = $employeeDetails['ssn'];
        //
        $response = AddEmployeeToCompany($request, $company);
        _e($response, true, true);
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
            $insertArray['company_sid'] = $employeeDetails['parent_sid'];
            $insertArray['employee_sid'] = $employeeId;
            $insertArray['gusto_employee_id'] = $response['id'];
            $insertArray['gusto_employee_uid'] = $response['uuid'];
            $insertArray['created_at'] = $date;
            $insertArray['updated_at'] = $date;
            //
            $insertId = $this->pm->AddEmployeeCompany($insertArray);
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
}