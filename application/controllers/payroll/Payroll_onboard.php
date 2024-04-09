<?php
defined('BASEPATH') || exit('No direct script access allowed');

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
        $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
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
        $response = $this->gusto_payroll_model->onboardEmployeeOnGusto($employeeId);
        //
        if (is_array($response)) {
            return sendResponse(200, ['status' => false, 'errors' => $response]);
        }
        //
        return sendResponse(200, ['status' => true]);
    }

    /**
     * Add admin to payroll
     * 
     * @param number $companyId
     * @return
     */
    public function AddAdmin($companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $eid = $this->CreateAdminOnGusto($companyId, $post);
        //
        return sendResponse(200, ['status' => true]);
    }

    /**
     * Accept terms
     * 
     * @param number $companyId
     * @return
     */
    public function AcceptServiceTerms($companyId)
    {
        //
        $termsAccepted = $this->pm->GetPayrollColumn(
            'payroll_companies',
            [
                "company_sid" => $companyId
            ],
            'terms_accepted',
            true
        );
        //
        if ($termsAccepted) {
            return sendResponse(200, ['status' => false, 'response' => "Already accepted"]);
        }
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        //
        if (
            !$this->pm->GetPayrollColumn(
                'payroll_company_admin',
                [
                    "company_sid" => $companyId,
                    "email_address" => $ses['email']
                ],
                'sid',
                true
            )
        ) {
            return sendResponse(200, ['status' => false, 'response' => 'You are not allowed to accept the terms. Only signatories and admins are allowed to perform this action.']);
        }
        //
        $request = [];
        $request['email'] = $ses['email'];
        $request['ip_address'] = getUserIP();
        $request['external_user_id'] = $ses['sid'];
        //
        $this->AcceptServiceTermsOnGusto($companyId, $request);
        //
        return sendResponse(200, ['status' => true]);
    }

    /**
     * Payment configs
     * 
     * @param number $companyId
     * @return
     */
    public function Settings($companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $request = [];
        $request['fast_payment_limit'] = $post['fast_speed_limit'] ?? 0;
        $request['payment_speed'] = $post['payment_speed'];
        
        //
        $response = $this->UpdatePaymentConfig($companyId, $request);
        if ($response['errors']) {
            return sendResponse(200, ['errors' => $response['errors']]);
        }
        //
        return sendResponse(200, ['status' => true]);
    }

    /**
     * Get the page data / data
     * 
     * @param string $type
     * @return
     */
    public function Get($companyId, $type)
    {
        //
        $type = strtolower($type);
        // For payroll employees
        if ($type == 'payroll_employees') {
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
                'payroll_employees.onboard_completed',
                'payroll_employees.employee_form_signing',
                'payroll_employees.payroll_employee_id',
                'payroll_employees.payroll_employee_uuid'
            ]);
            //
            if ($employees) {
                //
                $tmp = [];
                //
                foreach ($employees as $employee) {
                    //
                    if (!$employee['payroll_employee_uuid']) {
                        continue;
                    }
                    //
                    $tmp[] = [
                        'user_id' => $employee['userId'],
                        'payroll_onboard_status' => EmployeePayrollOnboardStatus($employee),
                        'payroll_employee_id' => $employee['payroll_employee_uuid'],
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
        if ($type == 'employees') {
            //
            $employees = $this->sem->GetPayrollEmployees($companyId, [
                rtrim(getUserFields(), ','),
                'users.timezone',
                'users.dob',
                'users.ssn',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.PhoneNumber',
                'payroll_employees.payroll_employee_id'
            ]);
            //
            if ($employees) {
                //
                $tmp = [];
                //
                foreach ($employees as $employee) {
                    //
                    if ($employee['payroll_employee_id']) {
                        continue;
                    }
                    //
                    $missing_fields = [];
                    //
                    if (!$employee['first_name']) {
                        $missing_fields[] = 'First Name';
                    }
                    //
                    if (!$employee['last_name']) {
                        $missing_fields[] = 'Last Name';
                    }
                    //
                    $employee['ssn'] = preg_replace('/[^0-9]/', '', $employee['ssn']);
                    //
                    if (!preg_match('/[0-9]{9}/', $employee['ssn'])) {
                        $missing_fields[] = 'Social Security Number';
                    }
                    //
                    if (!$employee['dob']) {
                        $missing_fields[] = 'Date Of Birth';
                    }
                    //
                    if (!$employee['email']) {
                        $missing_fields[] = 'Email';
                    }
                    //
                    $tmp[] = [
                        'user_id' => $employee['userId'],
                        'full_name_with_role' => remakeEmployeeName($employee),
                        'first_name' => $employee['first_name'],
                        'last_name' => $employee['last_name'],
                        'email' => $employee['email'],
                        'phone_number' => $employee['phone_number'],
                        'missing_fields' => $missing_fields
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
     * @param number $employeeId
     * @return
     */
    public function DeleteEmployeeFromPayroll($companyId, $employeeId)
    {
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

    /** 
     * Add/Update employee section for payroll
     * 
     * @param string $section
     * @param number $companyId
     * @return
     */
    public function EmployeeOnboardPiece($section, $companyId)
    {
        //
        $section = strtolower($section);
        //
        $post = $this->input->post(NULL, TRUE);
        //
        switch ($section) {
            case "profile":
                $this->AddEditEmployeeProfile($companyId, $post);
                break;
            case "compensation":
                $this->EditEmployeeJobAndCompensation($companyId, $post);
                break;
            case "home_address":
                $this->EditEmployeeHomeAddress($companyId, $post);
                break;
            case "federal_tax":
                $this->EditEmployeeFederalTax($companyId, $post);
                break;
            case "state_tax":
                $this->EditEmployeeStateTax($companyId, $post);
                break;
            case "bank_account":
                $this->AddEditEmployeeBankAccount($companyId, $post);
                break;
            case "onboard":
                $this->CompleteOnboardStep($companyId, $post);
                break;
            default:
                return SendResponse(401, 'Invalid call made.');
        }
    }

    /** 
     * Get employee section for payroll
     * 
     * @param string $section
     * @param number $companyId
     * @param number $employeeId
     * @return
     */
    public function GetEmployeeOnboardSection($section, $companyId, $employeeId)
    {
        //
        $section = strtolower($section);
        //
        switch ($section) {
            case "state_tax":
                $this->GetEmployeeStateTax($companyId, $employeeId);
                break;
            default:
                return SendResponse(401, 'Invalid call made.');
        }
    }

    /** 
     * Delete employee section for payroll
     * 
     * @param string $section
     * @param number $companyId
     * @param number $employeeId
     * @param number $bankId
     * @return
     */
    public function DeleteEmployeeOnboardSection($section, $companyId, $employeeId, $bankId)
    {
        //
        $section = strtolower($section);
        //
        $post = [
            'employeeId' => $employeeId,
            'id' => $bankId
        ];
        //
        if ($section == 'bank_account') {
            $this->DeleteEmployeeBankAccount($companyId, $post);
        }

        return SendResponse(401, 'Invalid call made.');
    }

    /** 
     * Get employee's onboard status
     * 
     * @param number $companyId
     * @param number $employeeId
     * @return
     */
    public function OnboardStatus($companyId, $employeeId)
    {
        //
        $employeePayroll = $this->pm->GetPayrollColumn(
            'payroll_employees',
            [
                "employee_sid" => $employeeId
            ],
            'onboard_completed, personal_profile, compensation, home_address, federal_tax, state_tax, payment_method',
            false
        );
        //
        $resp = EmployeePayrollOnboardStatus($employeePayroll);
        //
        return SendResponse(200, ['status' => true, 'response' => $resp]);
    }


    // -------------------------------------------------------------------------------------------------------------------------------------


    /**
     * Create partner company
     * 
     * @param number $companyId
     * @return
     */
    private function CreatePartnerCompany($companyId)
    {
        //
        LoadModel('scm', $this);
        // Get primary admin
        $primaryAdmin = $this->pm->GetPrimaryAdmin($companyId);
        // Get company details
        $companyDetails = $this->scm->GetCompanyDetails($companyId, 'CompanyName, ssn as ein, on_payroll');
        //
        // Check if ENI is already used
        if ($this->db->where('gusto_company_uid', $companyDetails['ein'])->count_all_results('payroll_companies')) {
            // return if EIN already in used
            return SendResponse(200, ['errors' => ['EIN already in used.']]);
        }

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
     * Add company location
     * 
     * @param number $companyId
     * @param array  $cd
     * @return
     */
    private function AddCompanyLocation($companyId, $cd)
    {
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
    private function CreateEmployeeOnGusto($companyId, $employeeId, $ei)
    {
        //
        $request = [];
        $request['first_name'] = $ei['first_name'];
        $request['middle_initial'] = !empty($ei['middle_name']) ? substr($ei['middle_name'], 0, 1) : '';
        $request['last_name'] = $ei['last_name'];
        $request['date_of_birth'] = $ei['dob'];
        $request['email'] = $ei['email'];
        $request['ssn'] = $ei['ssn'];
        // $request['ssn'] = rand(1, 999999999);
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
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $datetime = date('Y-m-d H:i:s', strtotime('now'));
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
        // Get employee home address from gusto
        $gustoEmployeeAddress = getEmployeeHomeAddressFromGusto($ed['payroll_employee_uuid'],  $company_details);
        //
        if (!isset($gustoEmployeeAddress['errors'])) {
            // Update employee employee home address for payroll
            $ua = [];
            $ua['street_1'] = $gustoEmployeeAddress['street_1'];
            $ua['street_2'] = $gustoEmployeeAddress['street_2'];
            $ua['city'] = $gustoEmployeeAddress['city'];
            $ua['state'] = $gustoEmployeeAddress['state'];
            $ua['zip'] = $gustoEmployeeAddress['zip'];
            $ua['version'] = $gustoEmployeeAddress['version'];
            $ua['active'] = $gustoEmployeeAddress['active'];
            $ua['country'] = $gustoEmployeeAddress['country'];
            $ua['updated_at'] = $datetime;
            //
            $this->pm->UpdatePayroll('payroll_employee_address', $ua, ['sid' => $ei['sid']]);
            //
            $ei['version'] = $gustoEmployeeAddress['version'];
        }
        //
        $request = [];
        $request['street_1'] = $ei['Location_Address'];
        $request['street_2'] = $ei['Location_Address_2'];
        $request['city'] = $ei['Location_City'];
        $request['state'] = $this->pm->GetStateCodeById($ei['Location_State']);
        $request['zip'] = $ei['Location_ZipCode'];
        $request['version'] = $ei['version'];
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
    private function CreateEmployeeJobOnGusto($companyId, $employeeId)
    {
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
        // $request['location_id'] = $ed['work_address_sid'];  // The location_uuid parameter is deprecated.
        $request['hire_date'] = $hire_date;
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = CreateEmployeeJob($request, $ed['payroll_employee_uuid'],  $company_details);
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
        foreach ($response['compensations'] as $compensation) {
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

    /**
     * Create employee or update on Gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function AddEditEmployeeProfile($companyId, $post)
    {
        // Errors array
        $er = [];
        // Validations
        if (empty($post['FirstName'])) {
            $er[] = 'First name is missing.';
        }
        if (empty($post['LastName'])) {
            $er[] = 'Last name is missing.';
        }
        if (empty($post['DOB'])) {
            $er[] = 'Date Of Birth is missing.';
        }
        if (empty($post['Email'])) {
            $er[] = 'Email is missing.';
        }
        if (empty($post['SSN'])) {
            $er[] = 'Social Security Number is missing.';
        }

        if ($er) {
            return SendResponse(200, [
                'status' => false,
                'errors' => $er
            ]);
        }
        // Check if employee is already on payroll
        //
        $ei = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.payroll_employee_uuid",
            "payroll_employees.version",
            "payroll_employees.work_address_sid"
        ]);
        // Either employee is on payroll or not
        if (!$ei['payroll_employee_uuid']) {
            $this->CreateEmployeeOnGusto($companyId, $post['employeeId'], $ei);
        } else {
            // Let's update the employee data
            $this->UpdateEmployeeOnGusto($companyId, $post, $ei['payroll_employee_uuid'], $ei['version']);
            // Check the jobs view
            if (!$post['EWA'] || $post['EWA'] == 0) {
                //
                return SendResponse(200, ['status' => true, 'response' => 'Employee\'s personal details is added/updated. The system is unable to update job due to missing work address and start date. Please, complete these two in order to proceed. ', 'move' => false]);
            }
            // Lets update the job
            $response = $this->UpdateEmployeeJob($companyId, $post);
            //
            if (is_array($response)) {
                return SendResponse(200, ['status' => false, 'errors' => $response]);
            }
            //
            return SendResponse(200, ['status' => true, 'response' => 'Employee\'s personal details is added/updated. ', 'move' => true]);
        }
    }

    /**
     * Create employee or update on Gusto
     * 
     * @param number $companyId
     * @param array  $post
     * @param string $employee_uuid
     * @param string $version
     * 
     * @return
     */
    private function UpdateEmployeeOnGusto($companyId, $post, $employee_uuid, $version)
    {
        // Make update array
        $upa = [];
        $upa['first_name'] = $post['FirstName'];
        $upa['middle_initial'] = !$post['MiddleInitial'] ? '' : $post['MiddleInitial'][0];
        $upa['last_name'] = $post['LastName'];
        $upa['date_of_birth'] = formatDateToDB($post['DOB']);
        $upa['email'] = $post['Email'];
        $upa['ssn'] = $post['SSN'];
        $upa['version'] = $version;
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $company_details['employee_uuid'] = $employee_uuid;
        //
        $response = UpdateEmployeeOnGusto($upa, $company_details);
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
        // Change the payroll status
        $this->pm->UpdateEmployee($post['employeeId'], [
            'on_payroll' => 1,
            'first_name' => $upa['first_name'],
            'middle_name' => $post['MiddleInitial'],
            'last_name' => $upa['last_name'],
            'email' => $upa['email'],
            'ssn' => $upa['ssn'],
            'dob' => $upa['date_of_birth']
        ]);
        // Update entry to the main payroll table
        $ia = [];
        $ia['last_updated_by'] = $this->userId;
        $ia['version'] = $response['version'];
        $ia['updated_at'] = $this->datetime;
        $ia['personal_profile'] = 1;
        //
        $this->pm->UpdatePayroll('payroll_employees', $ia, ['employee_sid' => $post['employeeId']]);
        //
        $ia = [];
        $ia['payroll_employee_sid'] = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $post['employeeId']], 'sid');
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_versions', $ia);
    }

    /**
     * Update job on Gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function UpdateEmployeeJob($companyId, $post)
    {
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "users.job_title",
            "users.joined_at",
            "users.registration_date",
            "users.rehire_date"
        ]);
        //
        $ej = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employee_jobs.payroll_job_id",
            "payroll_employee_jobs.version"
        ], 'payroll_employee_jobs');
        //
        if (!$ej['payroll_job_id']) {
            return $this->CreateEmployeeJobOnGusto($companyId, $post['employeeId']);
        }
        //
        $hire_date = GetHireDate($ed['registration_date'], $ed['joined_at'], $ed['rehire_date']);
        //
        $request = [];
        $request['title'] = $ed['job_title'] ? $ed['job_title'] : 'Automotive';
        if (isset($post['job_title'])) {
            $request['title'] = $post['job_title'];
        }
        $request['location_id'] = $post['EWA'];
        $request['hire_date'] = $hire_date;
        $request['version'] = $ej['version'];
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = UpdateEmployeeJob($request, $ej['payroll_job_id'],  $company_details);
        //
        if (isset($response['errors'])) {
            //
            $errors = [];
            //
            foreach ($response['errors'] as $error) {
                $errors[] = $error[0]['message'];
            }
            // Error took place
            return $errors;
        }
        //
        if (isset($response['name'])) {
            return [$response['message']];
        }
        // Add entry to the payroll job
        $ia = [];
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
        $ia['updated_at'] = $this->datetime;
        // Get job sid
        $sid = $this->pm->GetJobColumn(['employee_sid' => $post['employeeId']]);
        //
        $this->pm->UpdatePayroll('payroll_employee_jobs', $ia, ['sid' => $sid]);
        //
        $ia = [];
        $ia['payroll_employee_job_sid'] = $sid;
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_job_versions', $ia);
        // //
        foreach ($response['compensations'] as $compensation) {
            // Get compensation sid
            $sid = $this->pm->GetCompensationColumn($compensation['id']);
            // Add entry to the payroll job compensation
            $ia = [];
            $ia['rate'] = $compensation['rate'];
            $ia['payment_unit'] = $compensation['payment_unit'];
            $ia['flsa_status'] = $compensation['flsa_status'];
            $ia['effective_date'] = $compensation['effective_date'];
            $ia['payroll_id'] = $compensation['id'];
            $ia['version'] = $compensation['version'];
            $ia['last_updated_by'] = $this->userId;
            $ia['updated_at'] = $this->datetime;
            //
            $this->pm->UpdatePayroll('payroll_employee_job_compensations', $ia, ['sid' => $sid]);
            //
            $ia = [];
            $ia['payroll_employee_job_compensations_sid'] = $sid;
            $ia['payroll_json'] = json_encode($compensation);
            $ia['last_updated_by'] = $this->userId;
            $ia['created_at'] = $this->datetime;
            //
            $this->pm->InsertPayroll('payroll_employee_job_compensations_versions', $ia);
        }
        // Change the payroll status
        $this->pm->UpdatePayroll('payroll_employees', ['compensation' => 1, 'work_address_sid' => $response['location_id']], ['employee_sid' => $post['employeeId']]);
        $this->pm->UpdatePayroll('users', ['job_title' => $request['title']], ['sid' => $post['employeeId']]);
        //
        return $sid;
    }

    /**
     * Update job and compensation on Gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function EditEmployeeJobAndCompensation($companyId, $post)
    {
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.work_address_sid"
        ]);
        _e($ed, true, true);
        // Update job
        $response = $this->UpdateEmployeeJob($companyId, ['job_title' => $post['Title'], 'EWA' => $ed['work_address_sid'], 'employeeId' => $post['employeeId']]);
        // Check if there was an error
        if (is_array($response)) {
            return sendResponse(200, ['status' => false, 'errors' => $response]);
        }
        //
        $sid = $this->pm->GetPayrollColumn('payroll_employee_job_compensations', ['payroll_job_sid' => $response], 'sid');
        $version = $this->pm->GetPayrollColumn('payroll_employee_job_compensations', ['sid' => $sid], 'version');
        $payrollId = $this->pm->GetPayrollColumn('payroll_employee_job_compensations', ['sid' => $sid], 'payroll_id');
        // Let's update the compensations
        $request = [];
        $request['rate'] = $post['Rate'];
        $request['payment_unit'] = $post['PaymentUnit'];
        $request['flsa_status'] = $post['FlsaStatus'];
        $request['version'] = $version;
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $compensation = UpdateJobCompensation($request, $payrollId,  $company_details);
        //
        if (isset($compensation['errors'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($compensation['errors'])]);
        }
        // Add entry to the payroll job compensation
        $ia = [];
        $ia['rate'] = $compensation['rate'];
        $ia['payment_unit'] = $compensation['payment_unit'];
        $ia['flsa_status'] = $compensation['flsa_status'];
        $ia['effective_date'] = $compensation['effective_date'];
        $ia['payroll_id'] = $compensation['id'];
        $ia['version'] = $compensation['version'];
        $ia['last_updated_by'] = $this->userId;
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_job_compensations', $ia, ['sid' => $sid]);
        //
        $ia = [];
        $ia['payroll_employee_job_compensations_sid'] = $sid;
        $ia['payroll_json'] = json_encode($compensation);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_job_compensations_versions', $ia);
        $this->pm->UpdatePayroll('payroll_employees', ['compensation' => 1], ['employee_sid' => $post['employeeId']]);
        //
        $this->GetAndUpdateJob($companyId, $response);
        //
        return SendResponse(200, ['status' => true, 'response' => 'Employee\'s job and compensation is successfully updated.']);
    }

    /**
     * Get latest job id from gusto
     * 
     * @param number $companyId
     * @param number $jobId
     * 
     * @return
     */
    private function GetAndUpdateJob($companyId, $jobId)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employee_jobs', ['sid' => $jobId], 'payroll_job_id');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = GetJob($payrollId,  $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        // Add entry to the payroll job
        $ia = [];
        $ia['version'] = $response['version'];
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_jobs', $ia, ['sid' => $jobId]);
        //
        $ia = [];
        $ia['payroll_employee_job_sid'] = $jobId;
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_job_versions', $ia);
        //
        return true;
    }

    /**
     * Update job and compensation on Gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function EditEmployeeHomeAddress($companyId, $post)
    {
        //
        // Get employee UUID
        $employeeUUID = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.payroll_employee_id"
        ])['payroll_employee_id'];
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employee_address.sid",
            "payroll_employee_address.version"
        ], 'payroll_employee_address');
        //
        $gustoEmployeeAddress = getEmployeeHomeAddressFromGusto($employeeUUID,  $company_details);
        //
        if (!isset($gustoEmployeeAddress['errors'])) {
            // Update employee employee home address for payroll
            $ua = [];
            $ua['street_1'] = $gustoEmployeeAddress['street_1'];
            $ua['street_2'] = $gustoEmployeeAddress['street_2'];
            $ua['city'] = $gustoEmployeeAddress['city'];
            $ua['state'] = $gustoEmployeeAddress['state'];
            $ua['zip'] = $gustoEmployeeAddress['zip'];
            $ua['version'] = $gustoEmployeeAddress['version'];
            $ua['active'] = $gustoEmployeeAddress['active'];
            $ua['country'] = $gustoEmployeeAddress['country'];
            $ua['updated_at'] = $this->datetime;
            //
            $this->pm->UpdatePayroll('payroll_employee_address', $ua, ['sid' => $ed['sid']]);
            //
            $ed['version'] = $gustoEmployeeAddress['version'];
        }
        // Let's update the compensations
        $request = [];
        $request['street_1'] = $post['Street1'];
        $request['street_2'] = $post['Street2'];
        $request['city'] = $post['City'];
        $request['state'] = $post['State'];
        $request['zip'] = $post['Zipcode'];
        $request['version'] = $ed['version'];
        //
        $response = UpdateEmployeeAddress($request, $employeeUUID,  $company_details);
        //
        if (isset($response['errors'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($response['errors'])]);
        }
        // Add entry to the payroll job response
        $ia = [];
        $ia['street_1'] = $response['street_1'];
        $ia['street_2'] = $response['street_2'];
        $ia['city'] = $response['city'];
        $ia['state'] = $response['state'];
        $ia['zip'] = $response['zip'];
        $ia['version'] = $response['version'];
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_address', $ia, ['sid' => $ed['sid']]);
        //
        $ia = [];
        $ia['payroll_employee_address_sid'] = $ed['sid'];
        $ia['payroll_json'] = json_encode($response);
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_address_versions', $ia);
        //
        $this->pm->UpdatePayroll('payroll_employees', ['home_address' => 1], ['employee_sid' => $post['employeeId']]);
        //
        $this->pm->UpdatePayroll('users', [
            'Location_Address' => $response['street_1'],
            'Location_Address_2' => $response['street_2'],
            'Location_State' => $this->pm->GetStateId($response['state']),
            'Location_ZipCode' => $response['zip'],
            'Location_City' => $response['city']
        ], ['sid' => $post['employeeId']]);
        //
        if (!empty($post['PhoneNumber'])) {
            $this->pm->UpdatePayroll('users', ['PhoneNumber' => $post['PhoneNumber']], ['sid' => $post['employeeId']]);
        }
        //
        return SendResponse(200, ['status' => true, 'response' => 'Employee\'s home address is successfully updated.']);
    }

    /**
     * Update federal tax
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function EditEmployeeFederalTax($companyId, $post)
    {
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employee_federal_tax.sid",
            "payroll_employee_federal_tax.version"
        ], 'payroll_employee_federal_tax');
        //
        if (empty($ed['sid'])) {
            $ed = $this->GetEmployeeFederalTax($companyId, $post['employeeId']);
        }
        // Let's update the compensations
        $request = [];
        $request['filing_status'] = $post['FederalFilingStatus'];
        $request['multiple_jobs'] = $post['MultipleJobs'];
        $request['dependent'] = $post['DependentTotal'];
        $request['other_income'] = $post['OtherIncome'];
        $request['deductions'] = $post['Deductions'];
        $request['extra_withholding'] = $post['ExtraWithholding'];
        $request['w4_data_type'] = 'rev_2020_w4';
        $request['version'] = $ed['version'];
        //
        $employeeUUID = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.payroll_employee_uuid"
        ])['payroll_employee_uuid'];
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = UpdateEmployeeFederalTax($request, $employeeUUID,  $company_details);
        //
        if (isset($response['errors'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($response['errors'])]);
        }
        if (isset($response['message'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => [$response['message']]]);
        }
        $ia = [];
        $ia['filing_status'] = $response['filing_status'];
        $ia['multiple_jobs'] = $response['two_jobs'];
        $ia['dependent'] = $response['dependents_amount'];
        $ia['other_income'] = $response['other_income'];
        $ia['deductions'] = $response['deductions'];
        $ia['extra_withholding'] = $response['extra_withholding'];
        $ia['w4_data_type'] = $response['w4_data_type'];
        $ia['version'] = $response['version'];
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_federal_tax', $ia, ['sid' => $ed['sid']]);
        //
        $ia = [];
        $ia['payroll_employee_federal_tax_sid'] = $ed['sid'];
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_federal_tax_versions', $ia);
        //
        $this->pm->UpdatePayroll('payroll_employees', ['federal_tax' => 1], ['employee_sid' => $post['employeeId']]);
        //
        return SendResponse(200, ['status' => true, 'response' => 'Employee\'s federal taxes are successfully updated.']);
    }

    /**
     * Update state tax
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function EditEmployeeStateTax($companyId, $post)
    {
        //
        $ed = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employee_state_tax.sid",
        ], 'payroll_employee_state_tax');
        //
        if (empty($ed['sid'])) {
            $ed = $this->GetEmployeeStateTax($companyId, $post['employeeId']);
        }
        //
        $employeeUUID = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.payroll_employee_uuid"
        ])['payroll_employee_uuid'];
        //
        $tpost = $post;
        // Let's update the compensations
        $request = [];
        $request['employee_id'] = $employeeUUID;
        $request['states'] = [];
        $request['states'][0]['state'] = strtoupper($this->pm->GetPayrollColumn('states', ['state_name' => $post['state_name']], 'state_code'));
        $request['states'][0]['questions'] = [];
        //
        unset($tpost['state_name'], $tpost['employeeId']);
        //
        foreach ($tpost as $k => $v) {
            $request['states'][0]['questions'][] = [
                'key' => $k,
                'answers' => [[
                    'value' => $v,
                    'valid_from' => "2010-01-01"
                ]]
            ];
        }


        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = UpdateEmployeeStateTax($request, $employeeUUID,  $company_details);
        //
        if (isset($response['errors'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($response['errors'])]);
        }
        if (isset($response['message'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => [$response['message']]]);
        }
        //
        $ia = [];
        $ia['employee_sid'] = $post['employeeId'];
        $ia['company_sid'] = $companyId;
        $ia['state'] = $response[0]['state'];
        $ia['state_json'] = json_encode($response[0]);
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->UpdatePayroll('payroll_employee_state_tax', $ia, ['sid' => $ed['sid']]);
        //
        $this->pm->UpdatePayroll('payroll_employees', ['state_tax' => 1], ['employee_sid' => $post['employeeId']]);
        //
        return SendResponse(200, ['status' => true, 'response' => 'Employee\'s state taxes are successfully updated.']);
    }

    /**
     * Add/Update bank account
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function AddEditEmployeeBankAccount($companyId, $post)
    {
        //
        if ($post['bankId'] == 0) {
            $this->AddEmployeeBankDetails($companyId, $post);
        }
        // Get the latest payment method
        $this->UpdateEmployeePaymentMethod($companyId, $post['employeeId']);
        //
        return SendResponse(200, ['status' => true, 'response' => 'Employee\'s bank account is successfully added/updated.']);
    }

    /**
     * Add bank account
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function AddEmployeeBankDetails($companyId, $post)
    {
        //
        $employeeUUID = $this->sem->GetEmployeeDetailWithPayroll($post['employeeId'], [
            "payroll_employees.payroll_employee_uuid"
        ])['payroll_employee_uuid'];
        // Let's update the compensations
        $request = [];
        $request['name'] = $post['BankName'];
        $request['routing_number'] = $post['RoutingNumber'];
        $request['account_number'] = $post['AccountNumber'];
        $request['account_type'] = ucwords($post['AccountType']);
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = AddEmployeeBandAccount($request, $employeeUUID,  $company_details);
        //
        if (isset($response['errors'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($response['errors'])]);
        }
        if (isset($response['message'])) {
            // Error took place
            return SendResponse(200, ['status' => false, 'errors' => [$response['message']]]);
        }
        //
        $ia = [];
        $ia['employee_sid'] = $post['employeeId'];
        $ia['payroll_bank_uuid'] = $response['uuid'];
        $ia['account_type'] = $response['account_type'];
        $ia['name'] = $response['name'];
        $ia['routing_number'] = $response['routing_number'];
        $ia['account_number'] = $request['account_number'];
        $ia['account_percentage'] = 0;
        $ia['direct_deposit_id'] = 0;
        $ia['is_deleted'] = 0;
        $ia['created_at'] = $this->datetime;
        $ia['updated_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_bank_accounts', $ia);
        //
        return $ia;
    }

    /**
     * Get latest job id from gusto
     * 
     * @param number $companyId
     * @param number $employeeId
     * 
     * @return
     */
    private function GetEmployeeFederalTax($companyId, $employeeId)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $employeeId], 'payroll_employee_uuid');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = GetEmployeeFederalTax($payrollId,  $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        // Add entry to the payroll job
        $ia = [];
        $ia['employee_sid'] = $employeeId;
        $ia['company_sid'] = $companyId;
        $ia['filing_status'] = $response['filing_status'];
        $ia['multiple_jobs'] = $response['two_jobs'];
        $ia['dependent'] = $response['dependents_amount'];
        $ia['other_income'] = $response['other_income'];
        $ia['deductions'] = $response['deductions'];
        $ia['extra_withholding'] = $response['extra_withholding'];
        $ia['w4_data_type'] = $response['w4_data_type'];
        $ia['version'] = $response['version'];
        $ia['updated_at'] = $this->datetime;
        $ia['created_at'] = $this->datetime;
        //
        $id = $this->pm->InsertPayroll('payroll_employee_federal_tax', $ia);
        //
        $ia = [];
        $ia['payroll_employee_federal_tax_sid'] = $id;
        $ia['payroll_json'] = json_encode($response);
        $ia['last_updated_by'] = $this->userId;
        $ia['created_at'] = $this->datetime;
        //
        $this->pm->InsertPayroll('payroll_employee_federal_tax_versions', $ia);
        //
        $response['sid'] = $id;
        //
        return $response;
    }

    /**
     * Get state tax from gusto
     * 
     * @param number $companyId
     * @param number $employeeId
     * 
     * @return
     */
    private function GetEmployeeStateTax($companyId, $employeeId)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $employeeId], 'payroll_employee_uuid');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = GetEmployeeStateTax($payrollId,  $company_details);

        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        //
        $sid = $this->pm->GetPayrollColumn('payroll_employee_state_tax', ['employee_sid' => $employeeId], 'sid');
        //
        $response['sid'] = $sid;
        // Add entry to the payroll job
        $ia = [];
        $ia['employee_sid'] = $employeeId;
        $ia['company_sid'] = $companyId;
        $ia['state'] = $response[0]['state'];
        $ia['state_json'] = json_encode($response[0]);
        $ia['updated_at'] = $this->datetime;
        //
        if (!$sid) {
            $ia['created_at'] = $this->datetime;
            $response['sid'] = $this->pm->InsertPayroll('payroll_employee_state_tax', $ia);
        } else {
            $this->pm->UpdatePayroll('payroll_employee_state_tax', $ia, ['sid' => $response['sid']]);
        }
        //
        $ia['sid'] = $response['sid'];
        //
        $ia['state_json'] = json_decode($ia['state_json'], true);
        //
        $ia['state_name'] = $this->pm->GetPayrollColumn('states', ['state_code' => $ia['state']], 'state_name');
        //
        return SendResponse(200, ['status' => true, 'response' => $ia]);
    }

    /**
     * 
     */
    private function UpdateEmployeePaymentMethod($companyId, $employeeId)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $employeeId], 'payroll_employee_uuid');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = GetEmployeePaymentMethod($payrollId, $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        //
        $sid = $this->pm->GetPayrollColumn('payroll_employee_payment_method', ['employee_sid' => $employeeId], 'sid');
        // Add entry to the payroll job
        $ia = [];
        $ia['payment_method'] = $response['type'];
        $ia['split_method'] = $response['split_by'];
        $ia['splits'] = json_encode($response['splits']);
        $ia['version'] = $response['version'];
        $ia['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->pm->UpdatePayroll('payroll_employee_payment_method', $ia, ['sid' => $sid]);
        //
        return $ia;
    }

    /**
     * 
     */
    private function DeleteEmployeeBankAccount($companyId, $post)
    {
        //
        $bankUid = $post['id'];
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $post['employeeId']], 'payroll_employee_uuid');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = DeleteEmployeeBankAccount($payrollId, $bankUid, $company_details);
        //
        if (isset($response['errors'])) {
            return SendResponse(200, ['status' => false, 'errors' => MakeErrorArray($response['errors'])]);
        }
        //
        $this->pm->DeletePayroll('payroll_employee_bank_accounts', ['payroll_bank_uuid' => $bankUid]);
        //
        $this->UpdateEmployeePaymentMethod($companyId, $post['employeeId']);
        //
        return SendResponse(200, ['status' => true, 'response' => "Employee's bank account is deleted."]);
    }

    /**
     * Complete onboard check
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function CompleteOnboardStep($companyId, $post, $return = true)
    {
        //
        $onboardCompleted = $this->pm->GetPayrollColumn('payroll_employees', [
            'employee_sid' => $post['employeeId'],
            'personal_profile' => 1,
            'compensation' => 1,
            'home_address' => 1,
            'federal_tax' => 1,
            'state_tax' => 1
        ], 'sid');
        //
        if ($return) {
            $this->UpdateEmployeePaymentMethodToGusto($companyId, $post['employeeId']);
        }
        //
        if (!$onboardCompleted) {
            return SendResponse(200, ['status' => true, 'response' => 'You have successfully onboard the employee.']);
        }
        //
        $payrollUid = $this->pm->GetPayrollColumn('payroll_employees', [
            'employee_sid' => $post['employeeId']
        ], 'payroll_employee_uuid');

        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = MarkEmployeeAsOnboarded($payrollUid, $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        // Mark this step as completed
        $this->pm->UpdatePayroll('payroll_employees', ['onboard_completed' => 1, 'payment_method' => 1], ['employee_sid' => $post['employeeId']]);
        //
        return $return ? SendResponse(200, ['status' => true, 'response' => 'Employee\'s payment method has been updated.']) : $response;
    }

    /**
     * 
     */
    private function UpdateEmployeePaymentMethodToGusto($companyId, $employeeId)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $employeeId], 'payroll_employee_uuid');
        //
        $payment = $this->pm->GetPayrollColumn('payroll_employee_payment_method', ['employee_sid' => $employeeId], '*', false);
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $request = [];
        $request['version'] = $payment['version'];
        $request['type'] = trim($this->input->post('PaymentMethod', true));
        $request['split_by'] = 'Amount';
        //
        if ($request['type'] == 'Direct Deposit') {
            //
            $request['splits'] = json_decode($payment['splits']);
            //
            if (count($request['splits']) == 1) {
                $request['split_by'] = 'Percentage';
            }
        }
        //
        $response = UpdateEmployeePaymentMethod($request, $payrollId, $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        //
        $this->UpdateEmployeePaymentMethod($companyId, $employeeId);
    }

    /**
     * Get bank details from gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function GetEmployeeBankDetails($companyId, $post)
    {
        //
        $payrollId = $this->pm->GetPayrollColumn('payroll_employees', ['employee_sid' => $post['employeeId']], 'payroll_employee_uuid');
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = GetEmployeeBankDetails($payrollId,  $company_details);
        //
        if (isset($response['errors'])) {
            return MakeErrorArray($response['errors']);
        }
        //
        if (!$response) {
            //
            $result = $this->pm->GetPayrollColumn('bank_account_details', ['users_sid' => $post['employeeId'], 'users_type' => 'employee'], 'sid, account_title, routing_transaction_number, account_number, account_type', false);
            // Add entry to the payroll job
            $ia = [];
            $ia['employee_sid'] = $post['employeeId'];
            $ia['company_sid'] = $companyId;
            $ia['payroll_bank_uuid'] = 0;
            $ia['account_type'] = $result ? $result['account_type'] : null;
            $ia['routing_number'] = $result ? $result['routing_transaction_number'] : null;
            $ia['name'] = $result ? $result['account_title'] : 'Default';
            $ia['account_number'] = $result ? $result['account_number'] : null;
            $ia['account_percentage'] = 0;
            $ia['direct_deposit_id'] = $result ? $result['sid'] : 0;
            $ia['is_deleted'] = 0;
            $ia['created_at'] = $this->datetime;
            $ia['updated_at'] = $this->datetime;
            //
            $ia['sid'] = $this->pm->InsertPayroll('payroll_employee_bank_accounts', $ia);
            //
            return $ia;
        }
    }

    /**
     * Create admin on gusto
     * 
     * @param number $companyId
     * @param array  $post
     * 
     * @return
     */
    private function CreateAdminOnGusto($companyId, $post)
    {
        //
        $request = $post;
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = CreateAdmin($request, $company_details);
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
        $ia['uuid'] = $response['uuid'];
        $ia['company_sid'] = $companyId;
        $ia['first_name'] = $post['first_name'];
        $ia['last_name'] = $post['last_name'];
        $ia['email_address'] = $post['email'];
        $ia['phone_number'] = NULL;
        $ia['created_at'] = $ia['updated_at'] = $datetime;
        //
        $id = $this->pm->InsertPayroll('payroll_company_admin', $ia);
        //
        return $id;
    }

    /**
     * Create admin on gusto
     * 
     * @param number $companyId
     * @param array  $request
     * 
     * @return
     */
    private function AcceptServiceTermsOnGusto($companyId, $request)
    {
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //
        $response = AcceptServiceTerms($request, $company_details);
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
        $ia = [];
        $ia['terms_accepted'] = (int)$response['latest_terms_accepted'];
        $ia['employee_sid'] = $request['external_user_id'];
        $ia['email_address'] = $request['email'];
        $ia['ip_address'] = $request['ip_address'];
        $ia['accepted_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->pm->UpdatePayroll('payroll_companies', $ia, ['company_sid' => $companyId]);
        //
        return true;
    }

    /**
     * Create admin on gusto
     * 
     * @param number $companyId
     * @param array  $request
     * 
     * @return
     */
    private function UpdatePaymentConfig($companyId, $request)
    {
        // Get company details
        $company_details = $this->pm->GetPayrollCompany($companyId);
        //

         $response = UpdatePaymentConfig($request, $company_details);
        //
        if (isset($response['errors'])) {
            // Error took place
            return hasGustoErrors($response);
        }
        //
        $ia = [];
        $ia['fast_payment_limit'] = $response['fast_payment_limit'];
        $ia['payment_speed'] = $request['payment_speed'];
        $ia['partner_uid'] = $request['partner_uuid'];
        $ia['updated_at'] = getSystemDate();
        
        $this->pm->UpdatePayroll('payroll_settings', $ia, ['company_sid' => $companyId]);
        //
        return true;
    }
}
