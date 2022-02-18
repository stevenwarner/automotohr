<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 
 */
class Payroll_onboard extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        //
        if (!$this->input->is_ajax_request()) {
            return SendResponse(401);
        }
        //
        if ($this->input->method(false) === 'post' && empty($this->input->post())) {
            return SendResponse(400);
        }
        // Call the model
        $this->load->model("Payroll_model", "pm");
        $this->load->model('single/Employee_model', 'sem');
        // Call helper
        $this->load->helper("payroll_helper");
        //
        $this->userId = 0;
        //
        if ($this->session->userdata('logged_in')) {
            $this->userId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        //
        $this->datetime = date('Y-m-d H:i:s', strtotime('now'));
    }


    /**
     * Start the initial company onboard
     * 
     * @param number $companyId
     * @return
     */
    public function OnboardCompany($companyId)
    {
        // Check if company is already onboard
        $cd = $this->pm->GetCompanyOnboardDetails($companyId);
        //
        if (!$cd['on_payroll']) {
            $this->CreatePartnerCompany($companyId);
        }
        // Company address
        $this->AddCompanyLocation($companyId, $cd);
        //
        return SendResponse(200, ['status' => true]);
    }

    /**
     * Start the initial employee onboard
     * 
     * @param number $companyId
     * @return
     */
    public function OnboardEmployee($companyId)
    {
        //
        $employeeId = $this->input->post('employee_id', true);
        //
        $ei = $this->sem->GetEmployeeDetailWithPayroll($employeeId, [
            "users.sid",
            "users.first_name",
            "users.middle_name",
            "users.last_name",
            "users.registration_date",
            "users.joined_at",
            "users.ssn",
            "users.dob",
            "users.email",
            "users.Location_Country",
            "users.Location_State",
            "users.Location_City",
            "users.Location_Address",
            "users.PhoneNumber",
            "users.Location_ZipCode",
            "users.Location_Address_2",
            "users.on_payroll",
            "payroll_employees.payroll_employee_uuid"
        ]);
        // Either employee is on payroll or not
        if(!$ei['payroll_employee_uuid']){
            $this->CreateEmployeeOnGusto($companyId, $employeeId, $ei);
        }
        // Let's update employee's address
        $this->UpdateEmployeeAddressOnGusto($companyId, $employeeId);
        // Let's create a job
        $this->CreateEmployeeJobOnGusto($companyId, $employeeId);
        //
        if($this->input->post('need_response', true)){
            return sendResponse(200, ['status' => true]);
        }

    }

    /**
     * Get the page data / data
     * 
     * @param string $type
     * @return
     */
    public function Get($companyId, $type){
        //
        $type = strtolower($type);
        // For payroll employees
        if($type == 'payroll_employees'){
            //
            $employees = $this->sem->GetPayrollEmployees($companyId, [
                rtrim(getUserFields(), ','),
                'users.timezone',
                'payroll_employees.personal_profile',
                'payroll_employees.compensation',
                'payroll_employees.home_address',
                'payroll_employees.federal_tax',
                'payroll_employees.state_tax',
                'payroll_employees.payment_method',
                'payroll_employees.payroll_employee_id'
            ]);
            //
            if($employees){
                //
                $tmp = [];
                //
                foreach($employees as $employee){
                    //
                    if(!$employee['payroll_employee_id']) { continue; }
                    //
                    $tmp[] = [
                        'user_id' => $employee['userId'],
                        'payroll_onboard_status' => EmployeePayrollOnboardStatus($employee),
                        'payroll_employee_id' => $employee['payroll_employee_id'],
                        'full_name_with_role' => remakeEmployeeName($employee)
                    ];
                }
                //
                $employees = $tmp;
            }
            //
            return SendResponse(200, $employees);
        }
        // To onboard payroll employees
        if($type == 'employees'){
            //
            $employees = $this->sem->GetPayrollEmployees($companyId, [
                rtrim(getUserFields(), ','),
                'users.timezone',
                'payroll_employees.payroll_employee_id'
            ]);
            //
            if($employees){
                //
                $tmp = [];
                //
                foreach($employees as $employee){
                    //
                    if($employee['payroll_employee_id']) { continue; }
                    //
                    $tmp[] = [
                        'user_id' => $employee['userId'],
                        'full_name_with_role' => remakeEmployeeName($employee)
                    ];
                }
                //
                $employees = $tmp;
            }
            //
            return SendResponse(200, $employees);
        }
    }

    /** 
     * Deleted employee from payroll
     * 
     * @param number $companyId
     * @return
     */
    public function DeleteEmployeeFromPayroll($companyId, $employeeId){
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($employeeId, [
            "payroll_employees.payroll_employee_uuid"
        ]);
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = DeleteOnboardingEmployee($ed['payroll_employee_uuid'], $company_details);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0]['message'];
            }
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => $errors]);
        }
        //
        $this->sem->DeletePayrollEmployee($employeeId);
        //
        return SendResponse(200, ['status' => true]);
    }

    // -------------------------------------------------------------------------------------------------------------------------------------


    /**
     * Create partner company
     * 
     * @param number $companyId
     * @return
     */
    private function CreatePartnerCompany($companyId){
        //
        LoadModel('scm', $this);
        // Get primary admin
        $primaryAdmin = $this->pm->GetPrimaryAdmin($companyId);
        // Get company details
        $companyDetails = $this->scm->GetCompanyDetails($companyId, 'CompanyName, ssn as ein, on_payroll');
        // Make request array
        $request = [
            'user' => [],
            'company' => []
        ];
        //
        $request['user']['first_name'] = $primaryAdmin['first_name'];
        $request['user']['last_name'] = $primaryAdmin['last_name'];
        $request['user']['email'] = $primaryAdmin['email_address'];
        $request['user']['phone'] = $primaryAdmin['phone_number'];
        $request['company']['name'] = $companyDetails['CompanyName'];
        $request['company']['ein'] = $companyDetails['ein'];
        //
        $response = CreatePartnerCompany($request);
        //
        if (isset($response['errors'])) {
            //
            $errors = MakeErrorArray($response['errors']);
            // Error took place
            return SendResponse(200, [
                'errors' => $errors
            ]);
        }
        //
        $insertArray = [];
        $insertArray['company_sid'] = $companyId;
        $insertArray['gusto_company_sid'] = $request['company']['ein'];
        $insertArray['gusto_company_uid'] = $response['company_uuid'];
        $insertArray['refresh_token'] = $response['refresh_token'];
        $insertArray['access_token'] = $response['access_token'];
        $insertArray['created_at'] = $this->datetime;
        $insertArray['updated_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_companies', $insertArray);
        $this->pm->UpdateCompany($companyId, ['on_payroll' => 1]);
        //
        return $response['company_uuid'];
    }

    /**
     * Add company address
     * 
     * @param number $companyId
     * @param array  $cd
     * @return
     */
    private function AddCompanyLocation($companyId, $cd){
        //
        LoadModel('scm', $this);
        // Get company details
        $details = $this->pm->GetPayrollCompany($companyId);
        //
        $request = [];
        $request['street_1'] = $cd['Location_Address'];
        $request['street_2'] = $cd['Location_Address_2'];
        $request['country'] = db_get_country_name($cd['Location_Country'])["country_code"];
        $request['city'] = $cd['Location_City'];
        $request['zip'] = $cd['Location_ZipCode'];
        $request['state'] = db_get_state_code_only($cd['Location_State']);
        $request['phone_number'] = $cd['PhoneNumber'];
        $request['mailing_address'] = false;
        $request['filing_address'] = false;
        //
        $response = AddCompanyLocation($request, $details);
        //
        if (isset($response['errors'])) {
            return false;
        }
        //
        $insertArray = [];
        $insertArray['company_sid'] = $companyId;
        $insertArray['gusto_location_id'] = $response['id'];
        $insertArray['country'] = $response['country'];
        $insertArray['state'] = $response['state'];
        $insertArray['city'] = $response['city'];
        $insertArray['street_1'] = $response['street_1'];
        $insertArray['street_2'] = $response['street_2'];
        $insertArray['zip'] = $response['zip'];
        $insertArray['phone_number'] = $response['phone_number'];
        $insertArray['mailing_address'] = $response['mailing_address'];
        $insertArray['filing_address'] = $response['filing_address'];
        $insertArray['last_updated_by'] = $this->userId;
        $insertArray['created_at'] = $this->datetime;
        $insertArray['updated_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_company_locations', $insertArray);
        //
        $insertArray = [];
        $insertArray['payroll_company_location_sid'] = $companyId;
        $insertArray['payroll_json'] = json_encode($response);
        $insertArray['last_updated_by'] = $this->userId;
        $insertArray['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_company_locations_versions', $insertArray);
        //
        return true;
    }


    /**
     * Creates employee on Gusto
     * 
     * @param number $companyId
     * @param number $employeeId
     * @param number $ei
     * 
     * @return
     */
    private function CreateEmployeeOnGusto($companyId, $employeeId, $ei){
        //
        $request = [];
        $request['first_name'] = $ei['first_name'];
        $request['middle_initial'] = !empty($ei['middle_name']) ? substr($ei['middle_name'], 0, 1) : '';
        $request['last_name'] = $ei['last_name'];
        $request['date_of_birth'] = $ei['dob'];
        $request['email'] = $ei['email'];
        // $request['ssn'] = $ei['ssn'];
        $request['ssn'] = rand(1, 999999999);
        //
        $error_flag = 0;
        $missing_info = [];
        //
        // Validation
        if (empty($request['first_name'])) {
            $error_flag = 1;
            array_push($missing_info, "First name is missing");
        }
        if (empty($request['last_name'])) {
            $error_flag = 1;
            array_push($missing_info, "Last name is missing");
        }
        if (empty($request['email'])) {
            $error_flag = 1;
            array_push($missing_info, "Email is missing");
        }
        if (empty($request['date_of_birth'])) {
            $error_flag = 1;
            array_push($missing_info, "Date of birth is missing");
        }
        //
        if ($error_flag == 1) {
            //
            return SendResponse(200, [
                'status' => false,
                'errors' => $missing_info
            ]);
        }
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = AddEmployeeToCompany($request, $company_details);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0]['message'];
            }
            // Error took place
            return SendResponse(200, [
                'status' => false,
                'errors' => $errors
            ]);
        }
        //
        $datetime = date('Y-m-d H:i:s', strtotime('now'));
        // Change the payroll status
        $this->pm->UpdateEmployee($employeeId, ['on_payroll' => 1]);
        // Add entry to the main payroll table
        //
        $ia = [];
        $ia['company_sid'] = $companyId;
        $ia['employee_sid'] = $employeeId;
        $ia['work_address_sid'] = $this->pm->GetCompanyWorkAddress($companyId);
        $ia['payroll_employee_id'] = $response['id'];
        $ia['payroll_employee_uuid'] = $response['uuid'];
        $ia['onboard_completed'] = 0;
        $ia['last_updated_by'] = 0;
        $ia['version'] = $response['version'];
        $ia['created_at'] = $datetime;
        $ia['updated_at'] = $datetime;
        $ia['personal_profile'] = 1;
        $ia['compensation'] = 0;
        $ia['home_address'] = 0;
        $ia['federal_tax'] = 0;
        $ia['state_tax'] = 0;
        $ia['payment_method'] = 0;
        //
        $id = $this->pm->InsertPayroll('payroll_employees', $ia);
        //
        $ia = [];
        $ia['payroll_employee_sid'] = $id;
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = 0;
        $ia['created_at'] = $datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_versions', $ia);

        // Add entry to the payroll address
        //
        $ia = [];
        $ia['employee_sid'] = $employeeId;
        $ia['street_1'] = $response['home_address']['street_1'];
        $ia['street_2'] = $response['home_address']['street_2'];
        $ia['city'] = $response['home_address']['city'];
        $ia['state'] = $response['home_address']['state'];
        $ia['zip'] = $response['home_address']['zip'];
        $ia['version'] = $response['home_address']['version'];
        $ia['active'] = $response['home_address']['active'];
        $ia['country'] = $response['home_address']['country'];
        $ia['created_at'] = $datetime;
        $ia['updated_at'] = $datetime;
        //
        $id = $this->pm->InsertPayroll('payroll_employee_address', $ia);
        //
        $ia = [];
        $ia['payroll_employee_address_sid'] = $id;
        $ia['payroll_json'] = json_encode($response['home_address']);
        $ia['created_at'] = $datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_address_versions', $ia);

        //
        return $response['uuid'];
    }

    /**
     * Update employee home address on Gusto
     * 
     * @param number $companyId
     * @param number $employeeId
     * 
     * @return
     */
    private function UpdateEmployeeAddressOnGusto($companyId, $employeeId)
    {
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($employeeId, [
            "payroll_employees.payroll_employee_uuid"
        ]);
        //
        $ei = $this->sem->GetEmployeeDetailWithPayroll($employeeId, [
            "users.Location_Country",
            "users.Location_State",
            "users.Location_City",
            "users.Location_Address",
            "users.Location_ZipCode",
            "users.Location_Address_2",
            "payroll_employee_address.sid",
            "payroll_employee_address.version"
        ], 'payroll_employee_address');
        //
        $request = [];
        $request['street_1'] = $ei['Location_Address'];
        $request['street_2'] = $ei['Location_Address_2'];
        $request['city'] = $ei['Location_City'];
        $request['state'] = $this->pm->GetStateCodeById($ei['Location_State']);
        $request['zip'] = $ei['Location_ZipCode'];
        $request['version'] = $ei['version'];
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = UpdateEmployeeAddress($request, $ed['payroll_employee_uuid'],  $company_details);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0]['message'];
            }
            // Error took place
            return false;
        }
        //
        $datetime = date('Y-m-d H:i:s', strtotime('now'));
        // Add entry to the payroll address
        $ia = [];
        $ia['street_1'] = $response['street_1'];
        $ia['street_2'] = $response['street_2'];
        $ia['city'] = $response['city'];
        $ia['state'] = $response['state'];
        $ia['zip'] = $response['zip'];
        $ia['version'] = $response['version'];
        $ia['active'] = $response['active'];
        $ia['country'] = $response['country'];
        $ia['updated_at'] = $datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_address', $ia, ['sid' => $ei['sid']]);
        //
        $ia = [];
        $ia['payroll_employee_address_sid'] = $ei['sid'];
        $ia['payroll_json'] = json_encode($response);
        $ia['created_at'] = $datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_address_versions', $ia);
        // Change the payroll status
        $this->pm->UpdatePayroll('payroll_employees', ['home_address' => 1], ['employee_sid' => $employeeId]);
        //
        return true;
    }
    
    /**
     * Create employee job on Gusto
     * 
     * @param number $companyId
     * @param number $employeeId
     * 
     * @return
     */
    private function CreateEmployeeJobOnGusto($companyId, $employeeId){
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($employeeId, [
            "users.job_title",
            "users.joined_at",
            "users.registration_date",
            "users.rehire_date",
            "payroll_employees.work_address_sid",
            "payroll_employees.payroll_employee_uuid"
        ]);
        //
        $hire_date = GetHireDate($ed['registration_date'], $ed['joined_at'], $ed['rehire_date']);
        //
        $request = [];
        $request['title'] = $ed['job_title'] ? $ed['job_title'] : 'Automotive';
        $request['location_id'] = $ed['work_address_sid'];
        $request['hire_date'] = $hire_date;
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = CreateEmployeeAddress($request, $ed['payroll_employee_uuid'],  $company_details);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0]['message'];
            }
            // Error took place
            return false;
        }
        //
        $datetime = date('Y-m-d H:i:s', strtotime('now'));
        // Add entry to the payroll job
        $ia = [];
        $ia['employee_sid'] = $employeeId;
        $ia['payroll_job_id'] = $response['id'];
        $ia['payroll_uid'] = $response['uuid'];
        $ia['version'] = $response['version'];
        $ia['payroll_location_id'] = $response['location_id'];
        $ia['hire_date'] = $response['hire_date'];
        $ia['title'] = $response['title'];
        $ia['is_primary'] = (int)$response['primary'];
        $ia['rate'] = $response['rate'];
        $ia['payment_unit'] = $response['payment_unit'];
        $ia['current_compensation_id'] = $response['current_compensation_id'];
        $ia['last_modified_by'] = $this->userId;
        $ia['is_deleted'] = 0;
        $ia['created_at'] = $ia['updated_at'] = $datetime;
        //
        $id = $this->pm->InsertPayroll('payroll_employee_jobs', $ia);
        //
        $ia = [];
        $ia['payroll_employee_job_sid'] = $id;
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_job_versions', $ia);
        // //
        foreach($response['compensations'] as $compensation){
            // Add entry to the payroll job compensation
            $ia = [];
            $ia['payroll_job_sid'] = $id;
            $ia['rate'] = $compensation['rate'];
            $ia['payment_unit'] = $compensation['payment_unit'];
            $ia['flsa_status'] = $compensation['flsa_status'];
            $ia['effective_date'] = $compensation['effective_date'];
            $ia['payroll_id'] = $compensation['id'];
            $ia['version'] = $compensation['version'];
            $ia['last_updated_by'] = $this->userId;
            $ia['created_at'] = $ia['updated_at'] = $datetime;
            //
            $cid = $this->pm->InsertPayroll('payroll_employee_job_compensations', $ia);
            //
            $ia = [];
            $ia['payroll_employee_job_compensations_sid'] = $cid;
            $ia['payroll_json'] = json_encode($compensation);
            $ia['last_updated_by'] = $this->userId;
            $ia['created_at'] = $datetime;
            //
            $this->pm->InsertPayroll('payroll_employee_job_compensations_versions', $ia);
        }
        // Change the payroll status
        $this->pm->UpdatePayroll('payroll_employees', ['compensation' => 1], ['employee_sid' => $employeeId]);
        //
        return $id;
    }
}
