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

                if ($employee_level < 6) {
                    unset($companyEmployees[$key]);
                }
                
            }
            //
            $data['companyEmployees'] = $companyEmployees;
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'EMPLOYEE_URL' => getAPIUrl("employees"),
                'html' => $this->load->view($this->path.$page, $data, true)
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'COMPANIES_URL' => getAPIUrl("company"),
                'page_type' => $page_type,
                'html' => $this->load->view($this->path.$page, $data, true)
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
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'COMPANIES_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path.$page, $data, true)
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
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }

                if ($day == 2 && $x == 0) {
                    $weekdate = $dateTime->modify('+8 days')->format('Y-m-d');
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }

                if ($day == 3 && $x == 0) {
                    $weekdate = $dateTime->modify('+9 days')->format('Y-m-d');
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }

                if ($day == 4 && $x == 0) {
                    $weekdate = $dateTime->modify('+10 days')->format('Y-m-d');
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }

                if ($day == 5 && $x == 0) {
                    $weekdate = $dateTime->modify('+11 days')->format('Y-m-d');
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }

                if ($x > 0) {
                    $weekdate = $dateTime->modify('+7 days')->format('Y-m-d');
                    $row .= '<option value="'.$weekdate.'">'.date("m/d/Y", strtotime($weekdate)).'</option>';
                }
                //8
            }
            
            return SendResponse(200,[
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
                $midDate = date("Y").'-'.date("m").'-15';
                $row .= '<option value="'.$midDate.'">'.date("m/d/Y", strtotime($midDate)).'</option>';
            }
            if ($currentDate < 25) {
                $lastDate =  date('Y-m-t');
                $row .= '<option value="'.$lastDate.'">'.date("m/d/Y", strtotime($lastDate)).'</option>';
            }
            //
            for ($x = 1; $x <= 7; $x++) {
                $month = date('m',strtotime('first day of +'.$x.' month'));
                $year =  date('Y',strtotime('first day of +'.$x.' month'));
                //
                $midDate = $year.'-'.$month.'-15';
                $lastDate =  date('Y-m-t',strtotime('first day of +'.$x.' month'));
                //
                $row .= '<option value="'.$midDate.'">'.date("m/d/Y", strtotime($midDate)).'</option>';
                $row .= '<option value="'.$lastDate.'">'.date("m/d/Y", strtotime($lastDate)).'</option>';

            }
            //
            
            return SendResponse(200,[
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-other-semimonthly-dates") {
            
            $row = '<option value="0">Please select pay date</option>';
            //
            $currentDate =  date("d");
            //
            $dayOne =$_GET["dayOne"];
            $dayTwo =$_GET["dayTwo"];
            //
            for ($x = 1; $x <= 7; $x++) {
                $month = date('m',strtotime('first day of +'.$x.' month'));
                $year =  date('Y',strtotime('first day of +'.$x.' month'));
                //
                $firstDate = $year.'-'.$month.'-'.$dayOne;
                $SecondDate = $year.'-'.$month.'-'.$dayTwo;
                //
                $row .= '<option value="'.$firstDate.'">'.date("m/d/Y", strtotime($firstDate)).'</option>';
                $row .= '<option value="'.$SecondDate.'">'.date("m/d/Y", strtotime($SecondDate)).'</option>';

            }
            //
            
            return SendResponse(200,[
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
                    $day = date('t',strtotime('first day of +'.$x.' month'));
                }
                $month = date('m',strtotime('first day of +'.$x.' month'));
                $year =  date('Y',strtotime('first day of +'.$x.' month'));
                //
                $nextDate = $year.'-'.$month.'-'.$day;
                //
                $row .= '<option value="'.$nextDate.'">'.date("m/d/Y", strtotime($nextDate)).'</option>';

            }
            //
            return SendResponse(200,[
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-upcoming-months") {
            //
            $row = '<option value="0">Please select a month</option>';
            $row .= '<option value="'.date("m,Y").'">'.date("F Y").'</option>';
            //
            for ($x = 1; $x <= 7; $x++) {
                
                $displayMonth = date('F',strtotime('first day of +'.$x.' month'));
                $month = date('m',strtotime('first day of +'.$x.' month'));
                $year =  date('Y',strtotime('first day of +'.$x.' month'));
                //
                $row .= '<option value="'.$month.','.$year.'">'.$displayMonth.' '.$year.'</option>';

            }
            //
            return SendResponse(200,[
                'rows' =>  $row
            ]);
        }
        //
        if ($page === "get-selected-month-dates") {
            //
            $values = explode(",",$_GET["value"]); 
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
                $midDate = '15-'.$values[0].'-'.$values[1];
                $end = date("t", strtotime($midDate));
            }
            //
            $row = '<option value="0">Please select a month</option>';
            //
            for ($x = $start; $x <= $end; $x++) {
                
                $monthDate = $values[1].'-'.$values[0].'-'.$x;
                //
                $row .= '<option value="'.$monthDate.'">'.date("m/d/Y", strtotime($monthDate)).'</option>';

            }
            //
            return SendResponse(200,[
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
                $row .= '<option value="'.date("Y-m-d", strtotime($deadlineSafe1)).'">'.$deadlineSafe2.' - '.$deadlineSafe1.'</option>';
                $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">'.date("m/d", strtotime($deadline)).' - '.date("m/d", strtotime($applyDate)).'</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Every other week") {
                //
                $deadlineSafe1 =  date("m/d", strtotime('-7 days', strtotime($applyDate)));
                $deadlineSafe2 =  date("m/d", strtotime('-13 days', strtotime($deadlineSafe1)));
                $deadlineCurrent =  date("m/d", strtotime('-13 days', strtotime($applyDate)));
                //
                $row .= '<option value="'.date("Y-m-d", strtotime($deadlineSafe1)).'">'.$deadlineSafe2.' - '.$deadlineSafe1.'</option>';
                $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">'.$deadlineCurrent.' - '.date("m/d", strtotime($applyDate)).'</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Twice per month") {
                //
                $deadlineSafe1 =  date("m/d", strtotime('-7 days', strtotime($applyDate)));
                $deadlineSafe2 =  date("m/d", strtotime('-13 days', strtotime($deadlineSafe1)));
                $deadlineCurrent =  date("m/d", strtotime('-14 days', strtotime($applyDate)));
                //
                $row .= '<option value="'.date("Y-m-d", strtotime($deadlineSafe1)).'">'.$deadlineSafe2.' - '.$deadlineSafe1.'</option>';
                $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">'.$deadlineCurrent.' - '.date("m/d", strtotime($applyDate)).'</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Monthly") {
                //
                $firstMonthdate = date('m/1',strtotime('-30 days', strtotime($applyDate)));
                $lastMonthdate =  date('m/t',strtotime('-30 days', strtotime($applyDate)));
                $deadlineCurrentStart =  date("m/d", strtotime('-30 days', strtotime($applyDate)));
                $deadlineCurrentEnd =  date("m/d", strtotime($applyDate));
                //
                $row .= '<option value="'.date("Y-m-d", strtotime($lastMonthdate)).'">'.$firstMonthdate.' - '.$lastMonthdate.'</option>';
                $row .= '<option value="'.date("Y-m-d", strtotime($deadlineCurrentEnd)).'">'.$deadlineCurrentStart.' - '.$deadlineCurrentEnd.'</option>';
                $row .= '<option value="other">Other</option>';
            } else if ($type == "Quarterly") {
                //
                $month =  date("m", strtotime($applyDate));
                //
                if (in_array($month, [1, 2, 3])) {
                    $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">January - March</option>';
                } else if (in_array($month, [4, 5, 6])) {
                    $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">April - June</option>';
                } else if (in_array($month, [7, 8, 9])) {
                    $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">July - September</option>';
                } else if (in_array($month, [10, 11, 12])) {
                    $row .= '<option value="'.date("Y-m-d", strtotime($applyDate)).'">October - December</option>';
                }    
                //';
            }
            //
            return SendResponse(200,[
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
            $row .= '<option value="'.$applyDate.'">'.date("m/d/Y", strtotime($applyDate)).'</option>';
            //
            for ($x = $start; $x <= $end; $x++) {
                $newdate = date("Y-m-d", strtotime('-'.$x.' days', strtotime($applyDate)));
                $row .= '<option value="'.$newdate.'">'.date("m/d/Y", strtotime($newdate)).'</option>';
            }
            //
            return SendResponse(200,[
                'row' =>  $row
            ]);
        }
        //
        if ($page === "get-company-signatory-page") {
            //
            $data['states'] = $this->pm->GetStates();
            $data['signatory_info'] = $this->pm->GetSignatoryInfo($companyId);
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'SIGN_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-sign-document-page") {
            //
            return SendResponse(200,[
                'IP_ADDRESS' => $_SERVER['REMOTE_ADDR'],
                'API_KEY' => getAPIKey(),
                'SIGN_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-tax-detail-link-page") {
            //
            return SendResponse(200,[
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-company-bank-verification-page") {
            //
            return SendResponse(200,[
                'API_KEY' => getAPIKey(),
                'COMPANY_URL' => getAPIUrl("company"),
                'html' => $this->load->view($this->path.$page, $data, true)
            ]);
        }
        //
        if ($page === "get-prosess-complete-page") {
            //
            return SendResponse(200,[
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