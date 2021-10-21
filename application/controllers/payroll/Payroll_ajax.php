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
        if(!$this->input->is_ajax_request()){
            return SendResponse(401);
        }
        // Call the model
        $this->load->model("Payroll_model", "pm");
        $this->load->model('single/Employee_model', 'em');
        // Call helper
        $this->load->helper("payroll_helper");
        //
        $this->path = 'payroll/pages/';
    }

    /**
     * Get the page html
     */
    function GetPage($page, $companyId = 0){
        //
        $data = [];
        //
        $page = stringToSlug(strtolower($page));
        //
        if($page === 'status'){
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
            }
            //
            if(!isset($_SESSION['GUSTO_COMPANY'])){
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
        if($page === 'employees'){
            // Fetch employees
            $data['employees'] = $this->em->GetCompanyEmployees($companyId, [
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
        }
        //
        if($page === 'onboard'){
            //
            $data['hasPrimaryAdmin'] = $this->pm->HasPrimaryAdmin($companyId);
        }
        //
        if($page === 'admin-view'){
            //
            $data['primaryAdmin'] = $this->pm->GetPrimaryAdmin($companyId);
        }
        //
        if($page === 'company-address'){
            //
            LoadModel('scm', $this);
            // Get primary admin
            $primaryAdmin = $this->pm->GetPrimaryAdmin($companyId);
            // Get company details
            $companyDetails = $this->scm->GetCompanyDetails($companyId, 'CompanyName, ssn as ein, on_payroll');
            //
            if($companyDetails['on_payroll'] == '1'){
                return SendResponse(200, ['errors'=> ['Company already in use for payroll']]);
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
            if(isset($response['errors'])){
                //
                $errors = [];
                //
                foreach($response['errors'] as $error){
                    $errors[] = $error[0];
                }
                // Error took place
                return SendResponse(200, $errors);
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
                $this->pm->AddCompany($insertArray);
                $this->pm->UpdateCompany($companyId, ['on_payroll' => 1]);
                //
                return SendResponse(200, true);
            }
        }
        //
        if($page === 'add-company-location'){
            $data['location'] = array();
            $page = "company-details";
            $data['states'] = $this->pm->GetStates();
            
            //
            if($_GET["location_id"] > 0){
                $data['location'] = $this->pm->GetCompanyLocationById($companyId, $_GET["location_id"]);
            }
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'location_url' => getAPIUrl("locations"),
                'location_id' => $_GET["location_id"],
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }

        // After payroll enabled pages
        //
        if($page === 'company-details'){
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'location_type' => $location_type,
                'location_url' => getAPIUrl("locations"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'set-company-location'){
            //
            $post = $this->input->post(NULL, TRUE);
            // Get company details
            $details = $this->pm->GetPayrollCompany($companyId);
            //
            // Let's onboard the company
            // Make request array
            $request = [];
            $request['street_1'] = $post['street_1'];
            $request['street_2'] = $post['street_2'];
            $request['city'] = $post['city'];
            $request['zip'] = $post['zip'];
            $request['state'] = $post['state'];
            $request['phone_number'] = $post['phone_number'];
            $request['mailing_address'] = $post['mailing_address'] ? true: false;
            $request['filing_address'] = $post['filing_address'] ? true: false;
            // Check location
            // $locationExists = 
            // TODO check and update data
            //
            // $response = AddCompanyLocation($request, $details);
            $response = [
                'company_id' => '7756341741034032',
                'version' => 'e70c632b227b2276612d4fed2d5bf71a',
                'id' => '7757727717383309',
                'street_1' => '425 2nd street',
                'street_2' => '',
                'city' => 'San Francisco',
                'state' => 'CA',
                'zip' => '12345',
                'country' => 'USA',
                'active' => '1',
                'phone_number' => '1234567892',
                'filing_address' => false,
                'mailing_address' => true
            ];
            //
            _e($response, true);
            //
            if(isset($response['errors'])){
                //
                $errors = ['errors' => MakeErrorArray($response['errors'])];
                // Error took place
                return SendResponse(200, $errors);
            } else{
                // All okay to go
                $date = date('Y-m-d H:i:s', strtotime('now'));
                //
                $insertArray = [];
                $insertArray['company_sid'] = $companyId;
                $insertArray['gusto_location_id'] = $response['id'];
                $insertArray['state'] = $post['state'];
                $insertArray['city'] = $post['city'];
                $insertArray['street_1'] = $post['street_1'];
                $insertArray['street_2'] = $post['street_2'];
                $insertArray['zip'] = $post['zip'];
                $insertArray['phone_number'] = $post['phone_number'];
                $insertArray['mailing_address'] = $post['mailing_address'];
                $insertArray['filing_address'] = $post['filing_address'];
                $insertArray['last_updated_by'] = 0;
                $insertArray['version'] = $response['version'];
                $insertArray['created_at'] = $date;
                $insertArray['updated_at'] = $date;
                //
                $this->pm->AddCompanyLocation($insertArray);
                //
                return SendResponse(200, true);
            }

            _e($details, true, true);
            //
            $data['location'] = $this->pm->GetCompanyLocations($companyId);
        }
        //
        if($page === 'fedral-tax-detail'){
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'TAX_URL' => getAPIUrl("tax"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'edit-fedral-tax'){
            //
            $taxInfo = $this->pm->GetCompanyFedralTaxInfo($companyId);
            //
            $page = "federal-tax-info";
            $data['taxInfo'] = $taxInfo;
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'TAX_URL' => getAPIUrl("tax"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'company-industry'){
            //
            $industries = $this->pm->GetJobIndustries($companyId);
            //
            $data['industries'] = $industries;
            //
            return SendResponse(200,[
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'company-bank-info'){
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'BANK_URL' => getAPIUrl("bank_account"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'edit-bank-info'){
            //
            $bankInfo = $this->pm->GetCompanyBankAccount($companyId);
            //
            $data['bankInfo'] = $bankInfo;
            $page = "company-bank-info";   
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'BANK_URL' => getAPIUrl("bank_account"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'start-employee-onboarding'){  
            //
            return SendResponse(200,[
                'html' => $this->load->view($this->path.$page, $data, true)
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
            $companyEmployees = $this->em->GetCompanyEmployees($companyId, [
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
            return SendResponse(200,[
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'get-company-employee-profile'){
            //
            $data['locations'] = $this->pm->GetCompanyLocations($companyId);
            //
            $data['employee_info'] = $this->em->GetEmployeeDetails($_GET["employee_id"], [
                "users.sid",
                "users.first_name",
                "users.last_name",
                "users.middle_name",
                "users.email",
                "users.	dob",
                "users.ssn",
                "users.registration_date",
                "users.joined_at"
            ]);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if($page === 'get-company-employee-address'){
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['states'] = $this->pm->GetStates();
            //
            $data['employee_address_info'] = $this->em->GetEmployeeDetails($_GET["employee_id"], [
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }

        if ($page === "get-company-employee-compensation") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['employee_job_info'] = $this->pm->GetEmployeeJobDetails($_GET["employee_id"]);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-federal-tax") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['federal_tax_info'] = $this->pm->GetEmployeeFederalTaxDetails($_GET["employee_id"]);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-state-tax") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['state_tax_info'] = $this->pm->GetEmployeeStateTaxDetails($_GET["employee_id"]);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-payment-method") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $data['payment_method'] = $this->pm->GetEmployeePaymentMethod($_GET["employee_id"]);
            //
            $data['bank_account'] = $this->pm->GetEmployeeBankDetails($_GET["employee_id"]);
            //
            $data['payroll_bank_account'] = $this->pm->GetEmployeePayrollBankDetails($_GET["employee_id"]);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-employee-bank-detail") {
            //
            $data['employee_sid'] = $_GET["employee_id"];
            //
            $bank_detail = array();
            //
            if ($_GET["row_id"] != 0) {
                $bank_detail = $this->pm->GetEmployeeDirectDeposit($_GET["row_id"]);
            }
            //
            $data["bank_detail"] = $bank_detail;
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        SendResponse(200, $this->load->view($this->path.$page, $data, false), 'html');
    }

    /**
     * 
     */
    function SaveAdmin($companyId){
        //
        $post = $this->input->post(null, true);
        //
        if(!count($post)){
            return SendResponse(401);
        }
        // Let's double check if company payroll
        // admin doesn't exists
        if($this->pm->HasPrimaryAdmin($companyId)){
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
}