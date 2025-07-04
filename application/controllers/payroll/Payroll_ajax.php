<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * 
 */
class Payroll_ajax extends CI_Controller
{
    //
    private $path;
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
        $this->path = '2022/gusto/pages/';
        //
        $this->session = $this->session->userdata('logged_in');
    }

    /**
     * Get the page html
     */
    function GetPage($page, $companyId = 0)
    {
        //
        $data = [];
        //
        $page = stringToSlug(strtolower($page));
        //
        if ($page === 'status') {
            //
            LoadModel('scm', $this);
            // Get company details
            $companyDetails = $this->scm->GetCompanyDetails($companyId, 'on_payroll, CompanyName');
            //
            // Get company details
            $details = $this->pm->GetPayrollCompany($companyId);
            //
            $status = $details['onboarding_status'] == 0 ? "incomplete" : "complete";
            //
            $level = '';
            $level_id = '';
            //
            switch ($details['onbording_level']) {
                case 0:
                    $level = "company_address";
                    $level_id = 0;
                    break;

                case 1:
                    $level = "federal_tax";
                    $level_id = 1;
                    break;

                case 2:
                    $level = "bank_info";
                    $level_id = 2;
                    break;

                case 3:
                    $level = "employee";
                    $level_id = 3;
                    break;

                case 4:
                    $level = "payroll";
                    $level_id = 4;
                    break;

                case 5:
                    $level = "tax_details";
                    $level_id = 5;
                    break;

                case 6:
                    $level = "bank_verification";
                    $level_id = 6;
                    break;
            }
            //
            if (!isset($_SESSION['GUSTO_COMPANY'])) {
                // Get company details
                $details = $this->pm->GetPayrollCompany($companyId);
                //
                $_SESSION['GUSTO_COMPANY'] = GetCompany($details);
            }
            //
            return SendResponse(200, [
                'payroll_enabled' => $companyDetails['on_payroll'],
                'name' => $companyDetails['CompanyName'],
                'onboarding_status' => $status,
                'onbording_level' => $level,
                'onbording_level_id' => $level_id,

            ]);
        }
        //
        if ($page === 'change-level') {
            $updateArray = [];
            $updateArray['onbording_level'] = $_GET['next_level'];
            $this->pm->UpdateOndordingLevel($companyId, $updateArray);
            return SendResponse(200, true);
        }
        //
        if ($page === 'employees') {
            // Fetch employees
            $employees = $this->sem->GetCompanyEmployees($companyId, [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.access_level",
                "users.access_level_plus",
                "users.is_executive_admin",
                "users.job_title",
                "users.ssn",
                "users.dob",
                "users.email",
                "users.middle_name",
                "users.pay_plan_flag",
                "users.on_payroll",
                'users.PhoneNumber',
                'payroll_employees.payroll_employee_uuid',
            ], [
                'users.active' => 1,
                'users.is_executive_admin' => 0,
                'users.terminated_status' => 0
            ]);
            //
            if ($employees) {
                //
                $tmp = [];
                //
                foreach ($employees as $employee) {
                    //
                    if ($employee['payroll_employee_uuid']) {
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
                        'sid' => $employee['sid'],
                        'full_name_with_role' => remakeEmployeeName($employee),
                        'first_name' => $employee['first_name'],
                        'last_name' => $employee['last_name'],
                        'email' => $employee['email'],
                        'phone_number' => $employee['PhoneNumber'],
                        'missing_fields' => $missing_fields
                    ];
                }
                //
                $employees = $tmp;
            }
            //
            $data['employees'] = $employees;
        }
        //
        if ($page === 'onboard') {
            //
            $data['hasPrimaryAdmin'] = $this->pm->HasPrimaryAdmin($companyId);
        }
        //
        if ($page === 'admin-view') {
            //
            $data['primaryAdmin'] = $this->pm->GetPrimaryAdmin($companyId);
        }
        // ADD Compamy to Gusto Platform
        if ($page === 'company-address') {
            //
            LoadModel('scm', $this);
            // Get primary admin
            $primaryAdmin = $this->pm->GetPrimaryAdmin($companyId);
            // Get company details
            $companyDetails = $this->scm->GetCompanyDetails($companyId, 'CompanyName, ssn as ein, on_payroll');
            //
            if ($companyDetails['on_payroll'] == '1') {
                return SendResponse(200, ['errors' => ['Company already in use for payroll']]);
            }
            //
            // Check if ENI is already used
            if ($this->db->where('gusto_company_uid', $companyDetails['ein'])->count_all_results('payroll_companies')) {
                // return if EIN already in used
                return SendResponse(200, ['errors' => ['EIN already in used.']]);
            }
            // Let's onboard the company
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
            $employees_list = array();
            //
            if (isset($_POST["employees"]) && !empty($_POST["employees"])) {
                foreach ($_POST["employees"] as $employeeId) {
                    $employees_list[$employeeId] = getUserNameBySID($employeeId);
                }
            }
            //
            if (isset($response['errors'])) {
                //
                $errors = [];
                //
                foreach ($response['errors'] as $error) {
                    $errors[] = $error[0];
                }
                //
                $addressInfo = $this->pm->GetCompanyAddressInfo($companyId);
                // Error took place
                return SendResponse(200, [
                    'errors' => $errors
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
                $this->pm->AddCompany($insertArray);
                $this->pm->UpdateCompany($companyId, ['on_payroll' => 1]);
                //
                $addressInfo = $this->pm->GetCompanyAddressInfo($companyId);
                //
                return SendResponse(200, [
                    'status' => true,
                    'address_info' => $addressInfo,
                    'employees_list' => $employees_list,
                    'Location_URL' => base_url("get_payroll_page/set_company_location") . "/" . $companyId
                    // 'Location_URL' => getAPIUrl("locations")
                ]);
            }
        }
        //
        if ($page === 'add-company-location') {
            $data['location'] = array();
            $page = "company-details";
            $data['states'] = $this->pm->GetStates();

            //
            if ($_GET["location_id"] > 0) {
                $data['location'] = $this->pm->GetCompanyLocationById($companyId, $_GET["location_id"]);
            }
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'location_url' => getAPIUrl("locations"),
                'location_id' => $_GET["location_id"],
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }

        // After payroll enabled pages
        //
        if ($page === 'company-details') {
            //
            $data['states'] = $this->pm->GetStates();
            $locations = $this->pm->GetCompanyLocations($companyId);
            $location_type = 'new';
            //
            if (!empty($locations)) {
                $page = "company-address-listing";
                $data['locations'] = $locations;
                $location_type = 'listing';
            }
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'location_type' => $location_type,
                'location_url' => getAPIUrl("locations"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'set-company-location') {
            //
            $post = $this->input->post(NULL, TRUE);
            // Get company details
            $details = $this->pm->GetPayrollCompany($companyId);
            //
            // Let's onboard the company
            // Make request array
            $request = [];
            $request['street_1'] = $post['Street1'];
            $request['street_2'] = $post['Street2'];
            $request['country'] = db_get_country_name($post['Country'])["country_code"];
            $request['city'] = $post['City'];
            $request['zip'] = $post['Zipcode'];
            $request['state'] = db_get_state_code_only($post['State']);
            $request['phone_number'] = $post['PhoneNumber'];
            $request['mailing_address'] = $post['MailingAddress'] ? true : false;
            $request['filing_address'] = $post['FillingAddress'] ? true : false;
            //
            $response = AddCompanyLocation($request, $details);
            // $response = [
            //     'company_id' => '7756341741034032',
            //     'version' => 'e70c632b227b2276612d4fed2d5bf71a',
            //     'id' => '7757727717540047',
            //     'street_1' => '425 2nd street',
            //     'street_2' => '',
            //     'city' => 'San Francisco',
            //     'state' => 'CA',
            //     'zip' => '12345',
            //     'country' => 'USA',
            //     'active' => '1',
            //     'phone_number' => '1234567892',
            //     'filing_address' => false,
            //     'mailing_address' => true
            // ];
            //
            if (isset($response['errors'])) {
                //
                $errors = ['errors' => MakeErrorArray($response['errors'])];
                // Error took place
                return SendResponse(200, $errors);
            } else {
                // All okay to go
                $date = date('Y-m-d H:i:s', strtotime('now'));
                //
                $insertArray = [];
                $insertArray['company_sid'] = $companyId;
                $insertArray['gusto_location_id'] = $response['id'];
                $insertArray['country'] = db_get_country_name($post['Country'])["country_code"];
                $insertArray['state'] = db_get_state_code_only($post['State']);
                $insertArray['city'] = $post['City'];
                $insertArray['street_1'] = $post['Street1'];
                $insertArray['street_2'] = $post['Street2'];
                $insertArray['zip'] = $post['Zipcode'];
                $insertArray['phone_number'] = $post['PhoneNumber'];
                $insertArray['mailing_address'] = $post['MailingAddress'];
                $insertArray['filing_address'] = $post['FillingAddress'];
                $insertArray['last_updated_by'] = 0;
                $insertArray['version'] = $response['version'];
                $insertArray['created_at'] = $date;
                $insertArray['updated_at'] = $date;
                //
                $this->pm->AddCompanyLocation($insertArray);
                //
                return SendResponse(200, [
                    'status' => true,
                    'locationID' => $response['id']
                ]);
            }
        }
        //
        if ($page === 'gusto-company-location-id') {
            //
            $locationID = $this->pm->GetCompanyGustoLocationID($companyId);
            //
            return SendResponse(200, [
                'status' => true,
                'locationID' => $locationID["gusto_location_id"]
            ]);
        }
        //
        if ($page === 'company-fedral-tax-info') {
            //
            $taxInfo = $this->pm->GetCompanyFedralTaxInfo($companyId);
            //
            return SendResponse(200, [
                'status' => true,
                'API_KEY' => getAPIKey(),
                'TAX_URL' => getAPIUrl("tax"),
                'taxInfo' => $taxInfo
            ]);
        }
        //
        if ($page === 'get-company-bank-info') {
            //
            $bankInfo = $this->pm->GetCompanyBankAccountDetail($companyId);
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'BANK_URL' => getAPIUrl("bank_account"),
                'status' => true,
                'bankinfo' => $bankInfo
            ]);
        }
        //
        if ($page === 'set-company-employee') {
            //
            $data['employee_sid'] = $_POST["employee_id"];
            //
            $data['states'] = $this->pm->GetStates();
            //
            $employee_info = $this->sem->GetEmployeeDetails($_POST["employee_id"], [
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
                "users.Location_Address_2"
            ]);
            //
            $request = [];
            $request['first_name'] = $employee_info['first_name'];
            $request['middle_initial'] = !empty($employee_info['middle_name']) ? substr($employee_info['middle_name'], 0, 1) : substr($employee_info['first_name'], 0, 1);
            $request['last_name'] = $employee_info['last_name'];
            $request['date_of_birth'] = $employee_info['dob'];
            $request['email'] = $employee_info['email'];
            $request['ssn'] = $employee_info['ssn'];
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
            } else {
                //
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
                } else {
                    $this->pm->UpdateEmployee($_POST["employee_id"], ['on_payroll' => 1]);
                    //
                    return SendResponse(200, [
                        'status' => true,
                        'employee_info' => $response
                    ]);
                }
            }
        }
        //
        if ($page === 'delete-employee-from-gusto') {
            //
            echo $_POST["employee_id"];
            // Get company details
            $company_details = $this->pm->GetPayrollCompany($companyId);
            //
            $response = DeleteCompanyEmployee($_POST["employee_id"], $company_details);
            //
            return SendResponse(200, [
                'status' => true,
                'employee_info' => $response
            ]);
        }
        //
        if ($page === 'get-company-all-employees') {
            $companyEmployees = $this->sem->GetCompanyEmployees($companyId, [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.ssn",
                "users.dob",
                "users.email",
            ]);
            //
            $page = "company-all-employees";
            //
            // Get company details
            $company_details = $this->pm->GetPayrollCompany($companyId);
            //
            $payrollEmployees = GetCompanyEmployees($company_details);
            //
            $employees_onboard = array();
            $payrollEmployeesList = array();
            //
            foreach ($payrollEmployees as $pekey => $pe) {
                $employees_onboard[$pe['email']] = $pe['id'];
                $payrollEmployeesList[$pekey] = $pe['email'];
            }
            //
            foreach ($companyEmployees as $cekey => $employee) {
                //
                if (in_array(strtolower($employee['email']), $payrollEmployeesList)) {
                    $companyEmployees[$cekey]["onboard"] = "yes";
                    $companyEmployees[$cekey]["onboard_id"] = $employees_onboard[strtolower($employee['email'])];
                } else {
                    $companyEmployees[$cekey]["onboard"] = "no";
                }
            }
            //
            $data['companyEmployees'] = $companyEmployees;
            //
            $company_name = getCompanyNameBySid($companyId);
            //
            return SendResponse(200, [
                "company_name" => $company_name,
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
            //
        }
        //
        if ($page === 'get-company-bonboarding-status') {
            $details = $this->pm->GetPayrollCompany($companyId);
            //
            $company_status = GetCompanyStatus($details);
            //
            return SendResponse(200, [
                'status' => true,
                'company_status' => $company_status
            ]);
        }
        //
        if ($page === 'fedral-tax-detail') {
            //
            $taxInfo = $this->pm->GetCompanyFedralTaxInfo($companyId);
            //
            $page_type = '';
            //
            if (!empty($taxInfo)) {
                $page = "federal-tax-detail";
                $data['taxInfo'] = $taxInfo;
                $page_type = "tax_detail";
            } else {
                $page = "federal-tax-info";
                $data['taxInfo'] = $taxInfo;
                $page_type = "tax_form";
            }
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'TAX_URL' => getAPIUrl("tax"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'edit-fedral-tax') {
            //
            $taxInfo = $this->pm->GetCompanyFedralTaxInfo($companyId);
            //
            $page = "federal-tax-info";
            $data['taxInfo'] = $taxInfo;
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'TAX_URL' => getAPIUrl("tax"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'company-industry') {
            //
            $industries = $this->pm->GetJobIndustries($companyId);
            //
            $data['industries'] = $industries;
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'company-bank-info') {
            //
            $bankInfo = $this->pm->GetCompanyBankAccount($companyId);
            //
            $page_type = "new";
            //
            if (!empty($bankInfo)) {
                $data['bankInfo'] = $bankInfo;
                $page = "company-bank-detail";
                $page_type = "detail";
            }
            //

            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'BANK_URL' => getAPIUrl("bank_account"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'edit-bank-info') {
            //
            $bankInfo = $this->pm->GetCompanyBankAccount($companyId);
            //
            $data['bankInfo'] = $bankInfo;
            $page = "company-bank-info";
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'BANK_URL' => getAPIUrl("bank_account"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'start-employee-onboarding') {
            //
            $payrollEmployees = $this->pm->GetCompanyPayrollEmployees($companyId);
            //
            $payrollEmployeesList = array_column($payrollEmployees, "employee_sid");
            //
            $companyEmployees = $this->sem->GetCompanyEmployees($companyId, [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.access_level",
                "users.access_level_plus",
                "users.is_executive_admin",
                "users.job_title",
                "users.pay_plan_flag",
                "users.on_payroll"
            ]);
            //
            $employees_onboard = array();
            //
            foreach ($payrollEmployees as $pe) {
                $employees_onboard[$pe['employee_sid']] = $pe['onboard_level'];
            }
            //
            foreach ($companyEmployees as $key => $employee) {
                $employee_level = 0;
                if (in_array($employee['sid'], $payrollEmployeesList)) {
                    $employee_level = $employees_onboard[$employee['sid']];
                }

                $companyEmployees[$key]['onboarding_level'] = $employee_level;

                if ($employee_level < 6) {
                    unset($companyEmployees[$key]);
                }
            }
            //
            $data['companyEmployees'] = $companyEmployees;
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
            //
        }
        //
        if ($page === 'show-company-employee-list') {
            //
            $payrollEmployees = $this->pm->GetCompanyPayrollEmployees($companyId);
            //
            $payrollEmployeesList = array_column($payrollEmployees, "employee_sid");
            //
            $companyEmployees = $this->sem->GetCompanyEmployees($companyId, [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.access_level",
                "users.access_level_plus",
                "users.is_executive_admin",
                "users.job_title",
                "users.pay_plan_flag",
                "users.on_payroll"
            ]);
            //
            $employees_onboard = array();
            //
            foreach ($payrollEmployees as $pe) {
                $employees_onboard[$pe['employee_sid']] = $pe['onboard_level'];
            }
            //
            foreach ($companyEmployees as $key => $employee) {
                $employee_level = 0;
                if (in_array($employee['sid'], $payrollEmployeesList)) {
                    $employee_level = $employees_onboard[$employee['sid']];
                }
                $companyEmployees[$key]['onboarding_level'] = $employee_level;
            }
            //
            $data['companyEmployees'] = $companyEmployees;
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'get-company-employee-profile') {
            //
            $data['locations'] = $this->pm->GetCompanyLocations($companyId);
            //
            $data['workAddressId'] = $this->pm->GetWorkAddressId($_GET["employee_id"]);
            //
            $data['employee_info'] = $this->sem->GetEmployeeDetails($_GET["employee_id"], [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.middle_name",
                "users.email",
                "users.	dob",
                "users.ssn",
                "users.registration_date",
                "users.joined_at",
                "users.rehire_date",
            ]);
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'get-company-employee-address') {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['states'] = $this->pm->GetStates();
            //
            $data['employee_address_info'] = $this->sem->GetEmployeeDetails($_GET["employee_id"], [
                "users.sid",
                "users.Location_Country",
                "users.Location_State",
                "users.Location_City",
                "users.Location_Address",
                "users.	PhoneNumber",
                "users.Location_ZipCode",
                "users.Location_Address_2"
            ]);
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-compensation") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['employee_job_info'] = $this->pm->GetEmployeeJobDetails($_GET["employee_id"]);
            //
            if (!$data['employee_job_info']) {
                $data['employee_job_info'] = $this->db->select('
                    "Exempt" as flsa_status,
                    job_title as title,
                    hourly_rate as rate,
                    "Hour" as payment_unit
                ')->where('sid', $_GET["employee_id"])->get('users')->row_array();
            }
            //
            return SendResponse(200, [
                'JOB_ID' => $data['employee_job_info']['sid'],
                'JOB_HIRE_DATE' => $data['employee_job_info']['hire_date'],
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-federal-tax") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['federal_tax_info'] = $this->pm->GetEmployeeFederalTaxDetails($_GET["employee_id"]);
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-state-tax") {
            $statePrerequisite = $this->pm->checkStatePrerequisite($_GET["employee_id"]);
            //
            if ($statePrerequisite['status'] == 'data_missing') {
                $page = 'state_prerequisite';
                $data['work_address'] = $statePrerequisite['work_address'];
                $data['federal_tax'] = $statePrerequisite['federal_tax'];
                //
                return SendResponse(200, [
                    'API_KEY' => getAPIKey(),
                    'EMPLOYEE_URL' => getAPIUrl("employees"),
                    'status' => 'data_missing',
                    'html' => $this->load->view($this->path . $page, $data, true)
                ]);
            } else {
                //
                $data['employee_sid'] = $_GET["employee_id"];
                //
                $data['state_tax_info'] = $this->pm->GetEmployeeStateTaxDetails($_GET["employee_id"]);
                //
                return SendResponse(200, [
                    'API_KEY' => getAPIKey(),
                    'EMPLOYEE_URL' => getAPIUrl("employees"),
                    'status' => 'data_completed',
                    'html' => $this->load->view($this->path . $page, $data, true)
                ]);
            }
        }
        //
        if ($page === "get-company-employee-payment-method") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            // load model
            $this->load->model('gusto/Gusto_payroll_model', 'gusto_payroll_model');
            // sync employees with gusto
            $this->gusto_payroll_model->syncEmployeeBankAccountsWithGusto(
                $companyId,
                $_GET['employee_id']
            );
            //
            $data['payment_method'] = $this->pm->GetEmployeePaymentMethod($_GET["employee_id"]);
            //
            if (empty($data['payment_method'])) {
                //
                $data['payment_method'] = $this->GetEmployeePaymentMethod(
                    $companyId,
                    $data['employee_sid']
                );
            }
            //
            $splits = json_decode($data['payment_method']['splits'], true);
            //
            if ($splits) {
                foreach ($splits as $k => $v) {
                    //
                    $splits[$k] = array_merge($splits[$k], $this->db
                    ->where('payroll_bank_uuid', $v['uuid'])
                    ->get('payroll_employee_bank_accounts')
                    ->row_array());
                }
                //
                $data['payment_method']['splits'] = json_encode($splits);
            }
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-bank-detail") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            $data['type'] = $_GET["type"];
            //
            $bank_detail = array();
            //
            if ($_GET["row_id"] != 0) {
                $bank_detail = $this->pm->GetEmployeeDirectDeposit($_GET["row_id"]);
            }
            //
            $data["bank_detail"] = $bank_detail;
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //get_company_payroll_setting
        if ($page === "get-company-payroll-setting") {
            //
            $payrollInfo = $this->pm->GetCompanyPayrollSetting($companyId);
            //
            $page_type = '';
            //
            if (!empty($payrollInfo)) {
                $page = "payroll_info_detail";
                $data['payrollInfo'] = $payrollInfo;
                $page_type = "payroll_detail";
            } else {
                $page = "add_edit_payroll_info";
                $data['payrollInfo'] = $payrollInfo;
                $page_type = "payroll_form";
            }
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'COMPANIES_URL' => getAPIUrl("company"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //get_company_payroll_setting
        if ($page === "update-company-payroll-setting") {
            //
            $payrollInfo = $this->pm->GetCompanyPayrollSetting($companyId);
            //
            $page = "add_edit_payroll_info";
            $data['payrollInfo'] = $payrollInfo;
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'COMPANIES_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-date-list") {
            $week =  date("W");
            $year = date("Y");
            $day = $_GET['day'];
            //
            $dateTime = new DateTime();
            $dateTime->setISODate($year, $week);
            $dateTime->format('Y-m-d');
            //
            $row = '<option value="0">Please select pay date</option>';
            //
            for ($x = 0; $x <= 25; $x++) {

                if ($day == 1 && $x == 0) {
                    $weekdate = $dateTime->modify('+7 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }

                if ($day == 2 && $x == 0) {
                    $weekdate = $dateTime->modify('+8 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }

                if ($day == 3 && $x == 0) {
                    $weekdate = $dateTime->modify('+9 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }

                if ($day == 4 && $x == 0) {
                    $weekdate = $dateTime->modify('+10 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }

                if ($day == 5 && $x == 0) {
                    $weekdate = $dateTime->modify('+11 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }

                if ($x > 0) {
                    $weekdate = $dateTime->modify('+7 days')->format('Y-m-d');
                    $row .= '<option value="' . $weekdate . '">' . date("m/d/Y", strtotime($weekdate)) . '</option>';
                }
                //8
            }

            return SendResponse(200, [
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-twice-month-dates") {

            $row = '<option value="0">Please select pay date</option>';
            //
            $currentDate =  date("d");
            //
            if ($currentDate < 11) {
                $midDate = date("Y") . '-' . date("m") . '-15';
                $row .= '<option value="' . $midDate . '">' . date("m/d/Y", strtotime($midDate)) . '</option>';
            }
            if ($currentDate < 25) {
                $lastDate =  date('Y-m-t');
                $row .= '<option value="' . $lastDate . '">' . date("m/d/Y", strtotime($lastDate)) . '</option>';
            }
            //
            for ($x = 1; $x <= 7; $x++) {
                $month = date('m', strtotime('first day of +' . $x . ' month'));
                $year =  date('Y', strtotime('first day of +' . $x . ' month'));
                //
                $midDate = $year . '-' . $month . '-15';
                $lastDate =  date('Y-m-t', strtotime('first day of +' . $x . ' month'));
                //
                $row .= '<option value="' . $midDate . '">' . date("m/d/Y", strtotime($midDate)) . '</option>';
                $row .= '<option value="' . $lastDate . '">' . date("m/d/Y", strtotime($lastDate)) . '</option>';
            }
            //

            return SendResponse(200, [
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-other-semimonthly-dates") {

            $row = '<option value="0">Please select pay date</option>';
            //
            $currentDate =  date("d");
            //
            $dayOne = $_GET["dayOne"];
            $dayTwo = $_GET["dayTwo"];
            //
            for ($x = 1; $x <= 7; $x++) {
                $month = date('m', strtotime('first day of +' . $x . ' month'));
                $year =  date('Y', strtotime('first day of +' . $x . ' month'));
                //
                $firstDate = $year . '-' . $month . '-' . $dayOne;
                $SecondDate = $year . '-' . $month . '-' . $dayTwo;
                //
                $row .= '<option value="' . $firstDate . '">' . date("m/d/Y", strtotime($firstDate)) . '</option>';
                $row .= '<option value="' . $SecondDate . '">' . date("m/d/Y", strtotime($SecondDate)) . '</option>';
            }
            //

            return SendResponse(200, [
                'row' =>  $row
            ]);
        }
        //
        if ($page === "get-next-month-dates") {
            //
            $row = '<option value="0">Please select pay date</option>';
            //
            for ($x = 1; $x <= 7; $x++) {
                $day = $_GET["day"];
                if ($day == "last_day_of_month") {
                    $day = date('t', strtotime('first day of +' . $x . ' month'));
                }
                $month = date('m', strtotime('first day of +' . $x . ' month'));
                $year =  date('Y', strtotime('first day of +' . $x . ' month'));
                //
                $nextDate = $year . '-' . $month . '-' . $day;
                //
                $row .= '<option value="' . $nextDate . '">' . date("m/d/Y", strtotime($nextDate)) . '</option>';
            }
            //
            return SendResponse(200, [
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-upcoming-months") {
            //
            $row = '<option value="0">Please select a month</option>';
            $row .= '<option value="' . date("m,Y") . '">' . date("F Y") . '</option>';
            //
            for ($x = 1; $x <= 7; $x++) {

                $displayMonth = date('F', strtotime('first day of +' . $x . ' month'));
                $month = date('m', strtotime('first day of +' . $x . ' month'));
                $year =  date('Y', strtotime('first day of +' . $x . ' month'));
                //
                $row .= '<option value="' . $month . ',' . $year . '">' . $displayMonth . ' ' . $year . '</option>';
            }
            //
            return SendResponse(200, [
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-selected-month-dates") {
            //
            $values = explode(",", $_GET["value"]);
            //
            $currentMonth = date('m');
            $currentYear = date('Y');
            //
            $start = 1;
            $end = 30;
            //
            if ($currentMonth == $values[0] && $currentYear == $values[1]) {
                $start = date('d');
                $end = date('t');
            } else {
                $midDate = '15-' . $values[0] . '-' . $values[1];
                $end = date("t", strtotime($midDate));
            }
            //
            $row = '<option value="0">Please select a month</option>';
            //
            for ($x = $start; $x <= $end; $x++) {

                $monthDate = $values[1] . '-' . $values[0] . '-' . $x;
                //
                $row .= '<option value="' . $monthDate . '">' . date("m/d/Y", strtotime($monthDate)) . '</option>';
            }
            //
            return SendResponse(200, [
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-payroll-deadline") {
            //
            $applyDate = $_GET["date"];
            $type = $_GET["type"];
            //
            $deadline = '';
            $row = '';
            //
            if ($type == "Every week") {
                //
                $deadlineSafe1 =  date("m/d", strtotime('-7 days', strtotime($applyDate)));
                $deadlineSafe2 =  date("m/d", strtotime('-6 days', strtotime($deadlineSafe1)));
                //
                $row .= '<option value="' . date("Y-m-d", strtotime($deadlineSafe1)) . '">' . $deadlineSafe2 . ' - ' . $deadlineSafe1 . '</option>';
                $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">' . date("m/d", strtotime($deadline)) . ' - ' . date("m/d", strtotime($applyDate)) . '</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Every other week") {
                //
                $deadlineSafe1 =  date("m/d", strtotime('-7 days', strtotime($applyDate)));
                $deadlineSafe2 =  date("m/d", strtotime('-13 days', strtotime($deadlineSafe1)));
                $deadlineCurrent =  date("m/d", strtotime('-13 days', strtotime($applyDate)));
                //
                $row .= '<option value="' . date("Y-m-d", strtotime($deadlineSafe1)) . '">' . $deadlineSafe2 . ' - ' . $deadlineSafe1 . '</option>';
                $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">' . $deadlineCurrent . ' - ' . date("m/d", strtotime($applyDate)) . '</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Twice per month") {
                //
                $deadlineSafe1 =  date("m/d", strtotime('-7 days', strtotime($applyDate)));
                $deadlineSafe2 =  date("m/d", strtotime('-13 days', strtotime($deadlineSafe1)));
                $deadlineCurrent =  date("m/d", strtotime('-14 days', strtotime($applyDate)));
                //
                $row .= '<option value="' . date("Y-m-d", strtotime($deadlineSafe1)) . '">' . $deadlineSafe2 . ' - ' . $deadlineSafe1 . '</option>';
                $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">' . $deadlineCurrent . ' - ' . date("m/d", strtotime($applyDate)) . '</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Monthly") {
                //
                $firstMonthdate = date('m/1', strtotime('-30 days', strtotime($applyDate)));
                $lastMonthdate =  date('m/t', strtotime('-30 days', strtotime($applyDate)));
                $deadlineCurrentStart =  date("m/d", strtotime('-30 days', strtotime($applyDate)));
                $deadlineCurrentEnd =  date("m/d", strtotime($applyDate));
                //
                $row .= '<option value="' . date("Y-m-d", strtotime($lastMonthdate)) . '">' . $firstMonthdate . ' - ' . $lastMonthdate . '</option>';
                $row .= '<option value="' . date("Y-m-d", strtotime($deadlineCurrentEnd)) . '">' . $deadlineCurrentStart . ' - ' . $deadlineCurrentEnd . '</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Quarterly") {
                //
                $month =  date("m", strtotime($applyDate));
                //
                if (in_array($month, [1, 2, 3])) {
                    $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">January - March</option>';
                } else if (in_array($month, [4, 5, 6])) {
                    $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">April - June</option>';
                } else if (in_array($month, [7, 8, 9])) {
                    $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">July - September</option>';
                } else if (in_array($month, [10, 11, 12])) {
                    $row .= '<option value="' . date("Y-m-d", strtotime($applyDate)) . '">October - December</option>';
                }
                //';
            }
            //
            return SendResponse(200, [
                'deadline' =>  date("m/d/Y", strtotime('-6 days', strtotime($applyDate))),
                'row' => $row
            ]);
        }
        //
        if ($page === "get-other-payperiod-dates") {
            //
            $applyDate = $_GET["date"];
            $type = $_GET["frequency"];
            //
            $row = '';
            $start = 1;
            $end = 17;
            //
            if ($type == "Every week") {
                $end = 10;
            } else if ($type == "Monthly") {
                $end = 30;
            }
            //
            $row .= '<option value="' . $applyDate . '">' . date("m/d/Y", strtotime($applyDate)) . '</option>';
            //
            for ($x = $start; $x <= $end; $x++) {
                $newdate = date("Y-m-d", strtotime('-' . $x . ' days', strtotime($applyDate)));
                $row .= '<option value="' . $newdate . '">' . date("m/d/Y", strtotime($newdate)) . '</option>';
            }
            //
            return SendResponse(200, [
                'row' =>  $row
            ]);
        }
        //
        if ($page === "get-company-signatory-page") {
            //
            $data['states'] = $this->pm->GetStates();
            $data['signatory_info'] = $this->pm->GetSignatoryInfo($companyId);
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'SIGN_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-sign-document-page") {
            //
            return SendResponse(200, [
                'IP_ADDRESS' => $_SERVER['REMOTE_ADDR'],
                'API_KEY' => getAPIKey(),
                'SIGN_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-tax-detail-link-page") {
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-bank-verification-page") {
            //
            return SendResponse(200, [
                'API_KEY' => getAPIKey(),
                'COMPANY_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === "get-prosess-complete-page") {
            //
            return SendResponse(200, [
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
        }
        //
        if ($page === 'get-payroll-admin') {
            //
            $data['primaryAdmin'] = $this->pm->GetPrimaryAdmin($companyId);
            //
            $company_name = getCompanyNameBySid($companyId);
            //
            return SendResponse(200, [
                "company_name" => $company_name,
                'html' => $this->load->view($this->path . $page, $data, true)
            ]);
            //
        }

        //
        if ($page === 'get-api-creds') {
            //
            $data = [];
            $data['API_KEY'] = getAPIKey();
            $data['EMPLOYEE_URL'] = getAPIUrl('employees');
            //
            return SendResponse(200, $data);
        }
        header("content-type: text/html");
        echo $this->load->view($this->path . $page, $data, true);
        exit(0);
        //
        // SendResponse(200, $this->load->view($this->path.$page, $data, true), 'html');
    }

    /**
     * 
     */
    function SaveAdmin($companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        if (!count($post)) {
            return SendResponse(401);
        }
        // Let's double check if company payroll
        // admin doesn't exists
        if ($this->pm->HasPrimaryAdmin($companyId)) {
            return SendResponse(200, true);
        }
        //
        $post['company_sid'] = $companyId;
        $post['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $post['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->pm->InsertAdmin($post);
        //
        return SendResponse(200, true);
    }

    /**
     * 
     */
    function GetEmployees()
    {
        //
        $onPayroll = (int)$this->input->get('on_payroll', true);
        //
        $data = $this->sem->GetCompanyEmployees(
            $this->session['company_detail']['sid'],
            true,
            [
                'users.active' => 1,
                'users.terminated_status' => 0
            ]
        );
        //
        $responseArray = [
            'list' => [],
            'payroll_employees_count' => 0,
            'normal_employees_count' => 0
        ];
        //
        if (!empty($data)) {
            // 
            foreach ($data as $employee) {
                //
                $responseArray[$onPayroll && $employee['on_payroll'] ? 'payroll_employees_count' : 'normal_employees_count']++;
                //
                if ($onPayroll && $employee['on_payroll']) {
                    $responseArray['list'][] = $employee;
                }
                if (!$onPayroll && !$employee['on_payroll']) {
                    $responseArray['list'][] = $employee;
                }
            }
        }
        //
        SendResponse(200, $responseArray);
    }

    /**
     * 
     */
    private function GetEmployeePaymentMethod($companyId, $employeeId)
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
        //
        $response['sid'] = $sid;
        // Add entry to the payroll job
        $ia = [];
        $ia['employee_sid'] = $employeeId;
        $ia['company_sid'] = $companyId;
        $ia['payment_method'] = $response['type'];
        $ia['split_method'] = $response['split_by'];
        $ia['version'] = $response['version'];
        $ia['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
        $ia['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $ia['sid'] = $this->pm->InsertPayroll('payroll_employee_payment_method', $ia);
        //
        return $ia;
    }
}
