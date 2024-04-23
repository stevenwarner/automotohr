<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('hr_documents_management_model');
    }

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }

    /**
     * 
     */
    public function test()
    {
        $results = $this->db->select("sid")
            ->get("portal_job_title_templates")
            ->result_array();

        foreach ($results as $v) {
            $this->db->where("sid", $v["sid"])
                ->update("portal_job_title_templates", [
                    "color_code" => generateRandomColor()
                ]);
        }
    }

    /**
     * 
     */
    public function calculateWages()
    {
        // load payroll model
        $this->load->model("v1/Payroll/Wage_model", "wage_model");
        //
        $this->wage_model->calculateEmployeeWage(
            15753,
            "2024-01-01",
            "2024-01-05"
        );
    }

    public function w4ToGusto()
    {
        // load payroll model
        $this->load->model("v1/Payroll/W4_payroll_model", "w4_payroll_model");

        // $this->w4_payroll_model->syncW4s(21);
        // $this->w4_payroll_model->syncStateW4(21);
        // $this->w4_payroll_model->syncDDI(21);
    }

    public function syncEmployeesToStore()
    {
        // get the employees
        $employees = $this->db
            ->select("gusto_uuid")
            ->get("gusto_companies_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("no employees found");
        }
        // load payroll model
        $this->load->model("v1/Employee_payroll_model", "employee_payroll_model");
        foreach ($employees as $v0) {

            // handle employee sync
            $this
                ->employee_payroll_model
                ->syncEmployeeFromGustoToStore(
                    $v0["gusto_uuid"],
                    false
                );
        }
        exit(count($employees) . " employees are synced!");
    }

    public function syncEmployeeHomeAddress()
    {
        $employees = $this->db
            ->select("employee_sid, gusto_uuid")
            ->where('gusto_home_address_uuid', NULL)
            ->or_where('gusto_home_address_uuid', '')
            ->get("gusto_companies_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("no employees found");
        }
        //
        $this->load->model("v1/Payroll_model", "payroll_model");
        //
        foreach ($employees as $employee) {
            $employeeId = $employee['employee_sid'];
            $employeeInfo = $this->payroll_model->getEmployeeHomeAddress($employeeId);

            //
            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('hr_documents_management_model');
                // check and get state forms
                $companyStateForms = $this->hr_documents_management_model
                    ->getCompanyStateForms(
                        17100,
                        $employeeId,
                        "employee"
                    );

                //
                if ($companyStateForms['completed']) {
                    foreach ($companyStateForms['completed'] as $form) {
                        //
                        _e($form, true);
                        if ($form['title'] == "2020 W-4MN, Minnesota Employee Withholding Allowance/Exemption Certificate") {
                            if (!$employeeInfo['Location_Address']) {
                                $employeeInfo['Location_Address'] = $form['form_data']['street_1'];
                            }
                            //
                            if (!$employeeInfo['Location_City']) {
                                $employeeInfo['Location_City'] = $form['form_data']['city'];
                            }
                            //
                            if (!$employeeInfo['state_code']) {
                                $employeeInfo['state_code'] = getStateColumnById($form['form_data']['state']);
                            }
                            //
                            if (!$employeeInfo['Location_ZipCode']) {
                                $employeeInfo['Location_ZipCode'] = $form['form_data']['zip_code'];
                            }
                        }
                    }
                }
                //
                if ($companyStateForms['']) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $w4FormInfo['home_address'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $w4FormInfo['city'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        if ($w4FormInfo['state']) {
                            //
                            $stateCode = $this->db
                                ->select("state_code")
                                ->where('LOWER(state_name)', strtolower($w4FormInfo['state']))
                                ->get("states")
                                ->row_array()['state_code'];
                            //    
                            $employeeInfo['state_code'] = $stateCode;
                        }
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $w4FormInfo['zip'];
                    }
                }
            }

            //
            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('form_wi9_model');
                $w4FormInfo = $this->form_wi9_model->fetch_form("w4", "employee", $employeeId);
                //
                if ($w4FormInfo) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $w4FormInfo['home_address'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $w4FormInfo['city'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        if ($w4FormInfo['state']) {
                            //
                            $stateCode = $this->db
                                ->select("state_code")
                                ->where('LOWER(state_name)', strtolower($w4FormInfo['state']))
                                ->get("states")
                                ->row_array()['state_code'];
                            //    
                            $employeeInfo['state_code'] = $stateCode;
                        }
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $w4FormInfo['zip'];
                    }
                }
            }

            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('form_wi9_model');
                $i9FormInfo = $this->form_wi9_model->fetch_form("i9", "employee", $employeeId);
                //
                if ($i9FormInfo) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $i9FormInfo['section1_address'] . ' ' . $i9FormInfo['section1_apt_number'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $i9FormInfo['section1_city_town'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        $employeeInfo['state_code'] = $i9FormInfo['section1_state'];
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $i9FormInfo['section1_zip_code'];
                    }
                }
            }

            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $employeeFullInfo = $this->db
                    ->select("full_employment_application")
                    ->where("sid", $employeeId)
                    ->get("users")
                    ->row_array()['full_employment_application'];
                //
                if ($employeeFullInfo) {
                    $fullEmploymentApplication = unserialize($employeeFullInfo);
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $fullEmploymentApplication['TextBoxAddressStreetFormer1'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $fullEmploymentApplication['TextBoxAddressCityFormer1'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        $employeeInfo['state_code'] = $fullEmploymentApplication['DropDownListAddressStateFormer1'];
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $fullEmploymentApplication['TextBoxAddressZIPFormer1'];
                    }
                }
            }

            _e($employeeInfo, true);
            if (
                $employeeInfo['Location_Address'] &&
                $employeeInfo['Location_City'] &&
                $employeeInfo['state_code'] &&
                $employeeInfo['Location_ZipCode']
            ) {

                // sync employee address
                $this->payroll_model->createEmployeeHomeAddress($employeeId, [
                    'street_1' => $employeeInfo['Location_Address'],
                    'street_2' => $employeeInfo['Location_Address_2'],
                    'city' => $employeeInfo['Location_City'],
                    'state' => strtoupper($employeeInfo['state_code']),
                    'zip' => $employeeInfo['Location_ZipCode'],
                ]);
            }
        }
        _e("script process complete", true, true);
    }

    // Time off 
    public function timeOffHistoryMove($employeeSid)
    {

        $results = $this->db->select("*")
            ->where("new_employee_sid", $employeeSid)
            ->order_by('sid', 'Desc')
            ->limit(1)
            ->get("employees_transfer_log")
            ->result_array();

        if (empty($results)) {
            echo "employee transfer log not found";
        } else {

            //
            $adminId = getCompanyAdminSid($results[0]['to_company_sid']);
            $previousEmployeeSid = $results[0]['previous_employee_sid'];
            //
            $this->load->model('timeoff_model');

            $employeeRequests = $this->timeoff_model
                ->getEmployeeRequestsPrevious(
                    $previousEmployeeSid
                );

            //
            if ($employeeRequests) {
                foreach ($employeeRequests as $request) {
                    //Get Ploicy Title
                    $policyData = $this->timeoff_model
                        ->getPreviousPlicyTitle(
                            $request['company_sid'],
                            $request['timeoff_policy_sid']
                        );

                    // Get Policy Id
                    $newPolicyId = $this->timeoff_model
                        ->getPreviousPlicySid(
                            $results[0]['to_company_sid'],
                            $policyData['title']
                        );

                    if (empty($newPolicyId)) {
                        $policyDetails =
                            $this->timeoff_model->getPolicyDetailsById($request['timeoff_policy_sid']);
                        //
                        unset($policyDetails['sid']);
                        //
                        $policyDetails['company_sid'] = $results[0]['to_company_sid'];
                        $policyDetails['creator_sid'] = $adminId;
                        $policyDetails['type_sid'] = $this->timeoff_model
                            ->checkAndAddType(
                                $policyDetails['type_sid'],
                                $results[0]['to_company_sid']
                            );
                        $policyDetails['is_entitled_employee'] = 1;
                        $policyDetails['assigned_employees'] = $employeeSid;

                        // insert the policy
                        $this->db->insert('timeoff_policies', $policyDetails);
                        $newPolicyId['sid'] = $this->db->insert_id();
                    }

                    //
                    $requestId = $request['sid'];
                    $approvedBy = $request['approved_by'];
                    //
                    unset($request['sid']);
                    //
                    $request['company_sid'] = $results[0]['to_company_sid'];
                    $request['employee_sid'] = $results[0]['new_employee_sid'];
                    $request['timeoff_policy_sid'] = $newPolicyId['sid'];
                    $request['creator_sid'] = $request['creator_sid'] ==  $results[0]['new_employee_sid'] ? $results[0]['new_employee_sid'] : $adminId;
                    $request['approved_by'] = $request['approved_by'] ? $adminId : $request['approved_by'];
                    //
                    $whereArray = [
                        'employee_sid' => $results[0]['new_employee_sid'],
                        'timeoff_policy_sid' => $newPolicyId['sid'],
                        'request_from_date' => $request['request_from_date'],
                        'request_to_date' => $request['request_to_date'],
                        'status' => $request['status']
                    ];
                    //
                    if (!$this->db->where($whereArray)->count_all_results('timeoff_requests')) {
                        //
                        $this->db->insert(
                            'timeoff_requests',
                            $request
                        );
                        //
                        if ($approvedBy) {
                            //
                            $newRequestId = $this->db->insert_id();
                            //
                            $comment = $this->timeoff_model
                                ->getRequestApproverComment(
                                    $requestId,
                                    $approvedBy
                                );
                            // Insert the time off timeline
                            $insertTimeline = array();
                            $insertTimeline['request_sid'] = $newRequestId;
                            $insertTimeline['employee_sid'] = $adminId;
                            $insertTimeline['action'] = 'update';
                            $insertTimeline['note'] = json_encode([
                                'status' => $request['status'],
                                'canApprove' => 1,
                                'details' => [
                                    'startDate' => $request['request_from_date'],
                                    'endDate' => $request['request_to_date'],
                                    'time' => $request['requested_time'],
                                    'policyId' => $newPolicyId['sid'],
                                    'policyTitle' => $this->db
                                        ->select('title')
                                        ->where('sid', $newPolicyId['sid'])
                                        ->get('timeoff_policies')->row_array()['title'],
                                ],
                                'comment' => $comment
                            ]);
                            $insertTimeline['created_at'] = getSystemDate();
                            $insertTimeline['updated_at'] = getSystemDate();
                            $insertTimeline['is_moved'] = 0;
                            $insertTimeline['comment'] = $comment;
                            //
                            $this->db->insert('timeoff_request_timeline', $insertTimeline);
                        }
                    }
                }
            }

            echo "Done";
        }
    }


    public function eeoc($employeeID)
    {
        $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($employeeID, "employee");
        _e($eeo_form_info, true, true);
    }

    public function checkTimeoff($employeeId, $companyId)
    {
        $this->db->select('
            joined_at,
            registration_date,
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            is_executive_admin,
            employee_type,
            terminated_status,
            active
        ')
            ->order_by('first_name', 'ASC')
            ->where('sid', $employeeId);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();

        $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        //
        $todayDate = date('Y-m-d', strtotime('now'));
        //
        $employeeAnniversaryDate = getEmployeeAnniversary($JoinedDate, $todayDate);

        $this->load->model('timeoff_model');
        $policies = $this->timeoff_model->getCompanyPoliciesWithAccruals($companyId);
        //
        foreach ($policies as $policy) {
            _e($this->db->last_query(), true);
            $balanceInMinutes = $this->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
                $policy['sid'],
                $employeeId,
                $employeeAnniversaryDate['lastAnniversaryDate'],
                $employeeAnniversaryDate['upcomingAnniversaryDate']
            );
            //
            _e($balanceInMinutes, true);
        }
        //
        _e($employeeAnniversaryDate, true);
        _e($JoinedDate, true, true);
    }

    function fixEmployeeMerge()
    {
        $this->db->select('
            sid,
            primary_employee_sid,
            secondary_employee_sid,
            primary_employee_profile_data,
            secondary_employee_profile_data,
            merge_at
        ');
        //
        $this->db->group_start();
        $this->db->where('secondary_employee_profile_data <>', NULL);
        $this->db->or_where('secondary_employee_profile_data <>', '');
        $this->db->or_where('secondary_employee_profile_data <>', 'a:0:{}');
        $this->db->group_end();
        //
        $a = $this->db->get('employee_merge_history');
        $mergeEmployees = $a->result_array();
        $a->free_result();
        //
        $effectedEmployeeCount = 0;
        $employeeFound = [];
        $employeeNotFound = [];
        //
        if ($mergeEmployees) {
            foreach ($mergeEmployees as $md) {
                //
                if (!empty($md['secondary_employee_profile_data']) && $md['secondary_employee_profile_data'] != 'a:0:{}') {
                    $secondary_data = @unserialize($md['secondary_employee_profile_data']);
                    //
                    if ($secondary_data === false) {
                        //
                        $newData = '';
                        //

                        $this->db->select('*');
                        $this->db->where('sid', $md['secondary_employee_sid']);
                        //
                        $b = $this->db->get('deleted_users_by_merge');
                        $employeeData = $b->row_array();
                        $b->free_result();
                        //
                        if ($employeeData) {
                            $employeeFound[] = $md['secondary_employee_sid'];
                            $newData = serialize($employeeData);
                        } else {
                            $employeeNotFound[] = $md['secondary_employee_sid'];
                            $split = explode('s:9:"documents"', $md['secondary_employee_profile_data']);
                            //
                            $modifyData = @unserialize($split[0] . 's:9:"documents";a:0:{}}');
                            //
                            if ($modifyData !== false) {
                                $newData = $split[0] . 's:9:"documents";a:0:{}}';
                            } else {
                                $split = explode('s:11:"e_signature";a:17:', $md['secondary_employee_profile_data']);
                                $newData = $split[0] . 's:11:"e_signature";a:0:{}s:4:"eeoc";a:0:{}s:5:"group";a:0:{}s:9:"documents";a:0:{}}';
                            }
                            // 
                        }
                        //
                        $effectedEmployeeCount++;
                        $this->db->where("sid", $md["sid"])
                            ->update("employee_merge_history", [
                                "secondary_employee_profile_data" => $newData
                            ]);

                        //
                    }
                }
            }
        }
        //
        _e($effectedEmployeeCount, true);
        _e(json_encode($employeeFound), true);
        _e(json_encode($employeeNotFound), true, true);
    }

    public function getFileBase64()
    {
        $this->copyObject($this->input->post("fileName"));
        echo 'success';
    }

    /**
     * 
     */
    public function copyObject($fileName)
    {
        // load the AWS library
        $this->load->library(
            "Aws_lib",
            '',
            "aws_lib"
        );

        $meta = [
            "ContentType" => "application/pdf",
            "ContentDisposition" => "inline",
            "logicByM" => "1"
        ];

        $options = [
            'Bucket'     => AWS_S3_BUCKET_NAME,
            'CopySource' => urlencode(AWS_S3_BUCKET_NAME . '/' . $fileName), // Source object
            'Key'        => $fileName, // Destination object
            'Metadata' => $meta,
            "MetadataDirective" => "REPLACE",
            "ContentType" => "application/pdf",
            'ACL'        => 'public-read', // Optional: specify the ACL (access control list)
        ];
        //
        $this->aws_lib->copyObject($options);

        return $fileName;
    }

    public function addNewEmployeesIntoCompany ($companyId) {
        // Load the fake employee library
        $this->load->library('fake_employees/Fake_employees', null, 'fakeEmployees');
        //
        $employees = 
            $this
            ->fakeEmployees
            ->init(5);
        //
        foreach($employees as $employee){
            $employee['parent_sid'] = $companyId;
            $employee['active'] = 1;
            //
            $this->db->insert('users', $employee);
        }
        //
        _e("employee add successfully");
    } 

    public function checkCompanyCustomEarning ($company_sid) {
        $customEarnings = [
            "cash_spiffs" => [
                    "name" => "Cash Spiffs",
                    "fields_json" => '{"name":"Cash Spiffs","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "accrued_commission" => [
                    "name" => "Accrued Commission",
                    "fields_json" => '{"name":"Accrued Commission","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "accrued_payroll" => [
                    "name" => 'Accrued Payroll',
                    "fields_json" => '{"name":"Accrued Payroll","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "apprentice_pay" => [ 
                    "name" => 'Apprentice Pay',
                    "fields_json" => '{"name":"Apprentice Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "building_maintenance_pay" => [
                    "name" => 'Building Maintenance Pay',
                    "fields_json" => '{"name":"Building Maintenance Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "commercial_truck_reg_hours" => [
                    "name" => 'Commercial Truck Reg Hours',
                    "fields_json" => '{"name":"Commercial Truck Reg Hours","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "cost_of_medical_coverage" => [ 
                    "name" => 'Cost of Medical Coverage',
                    "fields_json" => '{"name":"Cost of Medical Coverage","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "demo_earnings" => [
                    "name" => 'Demo Earnings',
                    "fields_json" => '{"name":"Demo Earnings","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "employee_referral_bonus" => [
                    "name" => 'Employee Referral Bonus',
                    "fields_json" => '{"name":"Employee Referral Bonus","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "esst" => [
                    "name" => 'ESST',
                    "fields_json" => '{"name":"ESST","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"0","federal_income_tax":"0","federal_income_tax_additional":"0","federal_income_tax_fixed_rate":"0","social_security_company":"0","social_security_employee":"0","medicare_company":"0","medicare_employee":"0","federal_unemployment_insurance":"0","mn_income_tax":"0","mn_income_tax_additional":"0","mn_income_tax_fixed_rate":"0","mn_unemployment_insurance":"0","mn_workforce_dev_assessment":"0"}'
            ],
            "f&i_manager_commission" => [
                    "name" => 'F&I Manager Commission',
                    "fields_json" => '{"name":"F&I Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "general_manager_commission" => [
                    "name" => 'General Manager Commission',
                    "fields_json" => '{"name":"General Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "general_manager_salary" => [
                    "name" => 'General Manager Salary',
                    "fields_json" => '{"name":"General Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "holiday_pay" => [
                    "name" => 'Holiday Pay',
                    "fields_json" => '{"name":"Holiday Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_commission" => [
                    "name" => 'Manager Commission',
                    "fields_json" => '{"name":"Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_holiday_hours" => [
                    "name" => 'Manager Holiday Hours',
                    "fields_json" => '{"name":"Manager Holiday Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_salary" => [
                    "name" => 'Manager Salary',
                    "fields_json" => '{"name":"Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "manager_vacation_hours" => [
                    "name" => 'Manager Vacation Hours',
                    "fields_json" => '{"name":"Manager Vacation Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "miscellaneous_earnings" => [
                    "name" => 'Miscellaneous Earnings',
                    "fields_json" => '{"name":"Miscellaneous Earnings","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "officer_health_premiums" => [
                    "name" => 'Officer Health Premiums',
                    "fields_json" => '{"name":"Officer Health Premiums","rate_type":"Flat Rate","rate":"","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"Yes","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
            "other_pay" => [
                    "name" => 'Other Pay',
                    "fields_json" => '{"name":"Other Pay","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "overtime_hourly_wages" => [
                    "name" => 'Overtime Hourly Wages',
                    "fields_json" => '{"name":"Overtime Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"Yes","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "overtime_premium" => [
                    "name" => 'Overtime Premium',
                    "fields_json" => '{"name":"Overtime Premium","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "owner_salary" => [
                    "name" => 'Owner Salary',
                    "fields_json" => '{"name":"Owner Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "paid_time_off" => [
                    "name" => 'Paid Time Off',
                    "fields_json" => '{"name":"Paid Time Off","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"No","process_as_ot":"No","report_as_a_fringe_benefit":"No","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "piece_work_1" => [
                    "name" => 'Piece Work 1',
                    "fields_json" => '{"name":"Piece Work 1","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "piece_work_2" => [
                    "name" => 'Piece Work 2',
                    "fields_json" => '{"name":"Piece Work 2","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "pto_payout" => [
                    "name" => 'PTO Payout',
                    "fields_json" => '{"name":"PTO Payout","rate_type":"Hourly Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "regular_hourly_wages" => [
                    "name" => 'Regular Hourly Wages',
                    "fields_json" => '{"name":"Regular Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "salary" => [
                    "name" => 'Salary',
                    "fields_json" => '{"name":"Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "shuttle_driver_reg_hours" => [
                    "name" => 'Shuttle Driver Reg Hours',
                    "fields_json" => '{"name":"Shuttle Driver Reg Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "sign_on_bonus" => [
                    "name" => 'Sign On Bonus',
                    "fields_json" => '{"name":"Sign On Bonus","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "tech_unapplied_time" => [
                    "name" => 'Tech Unapplied Time',
                    "fields_json" => '{"name":"Tech Unapplied Time","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "technician_upsells" => [
                    "name" => 'Technician Upsells',
                    "fields_json" => '{"name":"Technician Upsells","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "training_hourly_pay" => [
                    "name" => 'Training Hourly Pay',
                    "fields_json" => '{"name":"Training Hourly Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "training_fixed_amount" => [
                    "name" => 'Training Fixed Amount',
                    "fields_json" => '{"name":"Training Fixed Amount","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "umt_manager_salary" => [
                    "name" => 'UMT Manager Salary',
                    "fields_json" => '{"name":"UMT Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "vacation_pay" => [
                    "name" => 'Vacation pay',
                    "fields_json" => '{"name":"Vacation pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ]
        
        ];
        //
        $customName = array_column($customEarnings, 'name');
        //
        $this->db->select('name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_default', 1);
        $result = $this->db->get("gusto_companies_earning_types")->result_array();

        if ($result) {
            foreach ($result as $key => $customEarning) {
                if (in_array($customEarning['name'], $customName)) {
                    $index = strtolower(str_replace(' ','_',$customEarning['name']));
                    unset($customEarnings[$index]);
                } 
            }
        } 
        //
        if (!empty($customEarnings)) {
            $this->load->model("v1/Payroll_model", "payroll_model");
            //
            foreach ($customEarnings as $earning) {
                $this->payroll_model
                        ->addCompanyDefaultEarningType(
                            $company_sid,
                            $earning
                        );
            }
        }
        //
        echo "The default earning type script end";
    }
    
    public function getCustomEarning () {
        $customEarning = Array
        (
            [
                "name" => "Paid Time Off",
                "fields_json" => '{}'
            ],
            "paid_time_off" => [
                    "name" => "Cash Spiffs",
                    "fields_json" => '{"name":"Cash Spiffs","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
            "paid_time_off" => [
                    "name" => "Accrued Commission",
                    "fields_json" => '{"name":"Accrued Commission","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Accrued Payroll',
                    "fields_json" => '{"name":"Accrued Payroll","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [ 
                    "name" => 'Apprentice Pay',
                    "fields_json" => '{"name":"Apprentice Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Building Maintenance Pay',
                    "fields_json" => '{"name":"Building Maintenance Pay","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Commercial Truck Reg Hours',
                    "fields_json" => '{"name":"Commercial Truck Reg Hours","rate_type":"Hourly Rate","rate":"0.0","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [ 
                    "name" => 'Cost of Medical Coverage',
                    "fields_json" => '{"name":"Cost of Medical Coverage","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
        
            [
                    "name" => 'Demo Earnings',
                    "fields_json" => '{"name":"Demo Earnings","rate_type":"Flat Rate","rate":"0.0","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"No","mn_income_tax":"No","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
        
            [
                    "name" => 'Employee Referral Bonus',
                    "fields_json" => '{"name":"Employee Referral Bonus","rate_type":"Flat Rate","rate":"0.0","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'ESST',
                    "fields_json" => '{"name":"ESST","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"0","federal_income_tax":"0","federal_income_tax_additional":"0","federal_income_tax_fixed_rate":"0","social_security_company":"0","social_security_employee":"0","medicare_company":"0","medicare_employee":"0","federal_unemployment_insurance":"0","mn_income_tax":"0","mn_income_tax_additional":"0","mn_income_tax_fixed_rate":"0","mn_unemployment_insurance":"0","mn_workforce_dev_assessment":"0"}'
            ],
        
            [
                    "name" => 'F&I Manager Commission',
                    "fields_json" => '{"name":"F&I Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'General Manager Commission',
                    "fields_json" => '{"name":"General Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'General Manager Salary',
                    "fields_json" => '{"name":"General Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Holiday Pay',
                    "fields_json" => '{"name":"Holiday Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Manager Commission',
                    "fields_json" => '{"name":"Manager Commission","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Manager Holiday Hours',
                    "fields_json" => '{"name":"Manager Holiday Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Manager Salary',
                    "fields_json" => '{"name":"Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Manager Vacation Hours',
                    "fields_json" => '{"name":"Manager Vacation Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Miscellaneous Earnings',
                    "fields_json" => '{"name":"Miscellaneous Earnings","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Officer Health Premiums',
                    "fields_json" => '{"name":"Officer Health Premiums","rate_type":"Flat Rate","rate":"","wage_type":"Imputed Income","count_toward_minimum_wage":"No","non_monetary_income":"Yes","process_as_ot":"0","report_as_a_fringe_benefit":"Yes","from_w-2_box_14":"Yes","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"No","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"No","medicare_company":"No","medicare_employee":"No","federal_unemployment_insurance":"No","mn_income_tax":"Yes","mn_income_tax_additional":"No","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"No","mn_workforce_dev_assessment":"No"}'
            ],
        
            [
                    "name" => 'Other Pay',
                    "fields_json" => '{"name":"Other Pay","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Overtime Hourly Wages',
                    "fields_json" => '{"name":"Overtime Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"Yes","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Overtime Premium',
                    "fields_json" => '{"name":"Overtime Premium","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Owner Salary',
                    "fields_json" => '{"name":"Owner Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Paid Time Off',
                    "fields_json" => '{"name":"Paid Time Off","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"No","process_as_ot":"No","report_as_a_fringe_benefit":"No","from_w-2_box_14":"0","update_balances_":"Yes","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Piece Work 1',
                    "fields_json" => '{"name":"Piece Work 1","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"No","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Piece Work 2',
                    "fields_json" => '{"name":"Piece Work 2","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'PTO Payout',
                    "fields_json" => '{"name":"PTO Payout","rate_type":"Hourly Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"Yes","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Regular Hourly Wages',
                    "fields_json" => '{"name":"Regular Hourly Wages","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Salary',
                    "fields_json" => '{"name":"Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"0","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Shuttle Driver Reg Hours',
                    "fields_json" => '{"name":"Shuttle Driver Reg Hours","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Sign On Bonus',
                    "fields_json" => '{"name":"Sign On Bonus","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"No","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"Yes","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"No","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"Yes","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
         
            [
                    "name" => 'Tech Unapplied Time',
                    "fields_json" => '{"name":"Tech Unapplied Time","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Technician Upsells',
                    "fields_json" => '{"name":"Technician Upsells","rate_type":"Flat Rate","rate":"","wage_type":"Supplemental Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Training Hourly Pay',
                    "fields_json" => '{"name":"Training Hourly Pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Training Fixed Amount',
                    "fields_json" => '{"name":"Training Fixed Amount","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'UMT Manager Salary',
                    "fields_json" => '{"name":"UMT Manager Salary","rate_type":"Flat Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ],
        
            [
                    "name" => 'Vacation pay',
                    "fields_json" => '{"name":"Vacation pay","rate_type":"Hourly Rate","rate":"","wage_type":"Regular Wages","count_toward_minimum_wage":"Yes","non_monetary_income":"0","process_as_ot":"0","report_as_a_fringe_benefit":"0","from_w-2_box_14":"0","update_balances_":"0","leave_plan":"0","federal_loan_assessment":"Yes","federal_income_tax":"Yes","federal_income_tax_additional":"Yes","federal_income_tax_fixed_rate":"No","social_security_company":"Yes","social_security_employee":"Yes","medicare_company":"Yes","medicare_employee":"Yes","federal_unemployment_insurance":"Yes","mn_income_tax":"Yes","mn_income_tax_additional":"Yes","mn_income_tax_fixed_rate":"No","mn_unemployment_insurance":"Yes","mn_workforce_dev_assessment":"Yes"}'
            ]
        
        );
        $json = '[{"sid":"105","name":"Paid Time Off","fields_json":null},{"sid":"114","name":"Cash Spiffs","fields_json":"{\"name\":\"Cash Spiffs\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"115","name":"Accrued Commission","fields_json":"{\"name\":\"Accrued Commission\",\"rate_type\":\"Flat Rate\",\"rate\":\"0.0\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"Yes\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"116","name":"Accrued Payroll","fields_json":"{\"name\":\"Accrued Payroll\",\"rate_type\":\"Flat Rate\",\"rate\":\"0.0\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"117","name":"Apprentice Pay","fields_json":"{\"name\":\"Apprentice Pay\",\"rate_type\":\"Hourly Rate\",\"rate\":\"0.0\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"0\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"118","name":"Building Maintenance Pay","fields_json":"{\"name\":\"Building Maintenance Pay\",\"rate_type\":\"Hourly Rate\",\"rate\":\"0.0\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"Yes\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"119","name":"Commercial Truck Reg Hours","fields_json":"{\"name\":\"Commercial Truck Reg Hours\",\"rate_type\":\"Hourly Rate\",\"rate\":\"0.0\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"120","name":"Cost of Medical Coverage","fields_json":"{\"name\":\"Cost of Medical Coverage\",\"rate_type\":\"Flat Rate\",\"rate\":\"0.0\",\"wage_type\":\"Imputed Income\",\"count_toward_minimum_wage\":\"No\",\"non_monetary_income\":\"Yes\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"Yes\",\"from_w-2_box_14\":\"Yes\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"No\",\"federal_income_tax_additional\":\"No\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"No\",\"social_security_employee\":\"No\",\"medicare_company\":\"No\",\"medicare_employee\":\"No\",\"federal_unemployment_insurance\":\"No\",\"mn_income_tax\":\"No\",\"mn_income_tax_additional\":\"No\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"No\",\"mn_workforce_dev_assessment\":\"No\"}"},{"sid":"121","name":"Demo Earnings","fields_json":"{\"name\":\"Demo Earnings\",\"rate_type\":\"Flat Rate\",\"rate\":\"0.0\",\"wage_type\":\"Imputed Income\",\"count_toward_minimum_wage\":\"No\",\"non_monetary_income\":\"Yes\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"Yes\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"No\",\"federal_income_tax_additional\":\"No\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"No\",\"mn_income_tax\":\"No\",\"mn_income_tax_additional\":\"No\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"No\",\"mn_workforce_dev_assessment\":\"No\"}"},{"sid":"122","name":"Employee Referral Bonus","fields_json":"{\"name\":\"Employee Referral Bonus\",\"rate_type\":\"Flat Rate\",\"rate\":\"0.0\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"No\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"Yes\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"No\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"Yes\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"123","name":"ESST","fields_json":"{\"name\":\"ESST\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"0\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"0\",\"federal_income_tax\":\"0\",\"federal_income_tax_additional\":\"0\",\"federal_income_tax_fixed_rate\":\"0\",\"social_security_company\":\"0\",\"social_security_employee\":\"0\",\"medicare_company\":\"0\",\"medicare_employee\":\"0\",\"federal_unemployment_insurance\":\"0\",\"mn_income_tax\":\"0\",\"mn_income_tax_additional\":\"0\",\"mn_income_tax_fixed_rate\":\"0\",\"mn_unemployment_insurance\":\"0\",\"mn_workforce_dev_assessment\":\"0\"}"},{"sid":"124","name":"F&I Manager Commission","fields_json":"{\"name\":\"F&I Manager Commission\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"125","name":"General Manager Commission","fields_json":"{\"name\":\"General Manager Commission\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"126","name":"General Manager Salary","fields_json":"{\"name\":\"General Manager Salary\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"127","name":"Holiday Pay","fields_json":"{\"name\":\"Holiday Pay\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"128","name":"Manager Commission","fields_json":"{\"name\":\"Manager Commission\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"129","name":"Manager Holiday Hours","fields_json":"{\"name\":\"Manager Holiday Hours\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"130","name":"Manager Salary","fields_json":"{\"name\":\"Manager Salary\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"131","name":"Manager Vacation Hours","fields_json":"{\"name\":\"Manager Vacation Hours\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"Yes\",\"leave_plan\":\"Yes\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"No\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"132","name":"Miscellaneous Earnings","fields_json":"{\"name\":\"Miscellaneous Earnings\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"133","name":"Officer Health Premiums","fields_json":"{\"name\":\"Officer Health Premiums\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Imputed Income\",\"count_toward_minimum_wage\":\"No\",\"non_monetary_income\":\"Yes\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"Yes\",\"from_w-2_box_14\":\"Yes\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"No\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"No\",\"social_security_employee\":\"No\",\"medicare_company\":\"No\",\"medicare_employee\":\"No\",\"federal_unemployment_insurance\":\"No\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"No\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"No\",\"mn_workforce_dev_assessment\":\"No\"}"},{"sid":"134","name":"Other Pay","fields_json":"{\"name\":\"Other Pay\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"135","name":"Overtime Hourly Wages","fields_json":"{\"name\":\"Overtime Hourly Wages\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"Yes\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"136","name":"Overtime Premium","fields_json":"{\"name\":\"Overtime Premium\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"137","name":"Owner Salary","fields_json":"{\"name\":\"Owner Salary\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"138","name":"Paid Time Off","fields_json":"{\"name\":\"Paid Time Off\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"No\",\"process_as_ot\":\"No\",\"report_as_a_fringe_benefit\":\"No\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"Yes\",\"leave_plan\":\"Yes\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"139","name":"Piece Work 1","fields_json":"{\"name\":\"Piece Work 1\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"No\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"140","name":"Piece Work 2","fields_json":"{\"name\":\"Piece Work 2\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"141","name":"PTO Payout","fields_json":"{\"name\":\"PTO Payout\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"Yes\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"No\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"Yes\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"No\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"Yes\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"142","name":"Regular Hourly Wages","fields_json":"{\"name\":\"Regular Hourly Wages\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"143","name":"Salary","fields_json":"{\"name\":\"Salary\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"0\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"144","name":"Shuttle Driver Reg Hours","fields_json":"{\"name\":\"Shuttle Driver Reg Hours\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"145","name":"Sign On Bonus","fields_json":"{\"name\":\"Sign On Bonus\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"No\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"Yes\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"No\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"Yes\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"146","name":"Tech Unapplied Time","fields_json":"{\"name\":\"Tech Unapplied Time\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"147","name":"Technician Upsells","fields_json":"{\"name\":\"Technician Upsells\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Supplemental Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"148","name":"Training Hourly Pay","fields_json":"{\"name\":\"Training Hourly Pay\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"149","name":"Training Fixed Amount","fields_json":"{\"name\":\"Training Fixed Amount\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"150","name":"UMT Manager Salary","fields_json":"{\"name\":\"UMT Manager Salary\",\"rate_type\":\"Flat Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"},{"sid":"151","name":"Vacation pay","fields_json":"{\"name\":\"Vacation pay\",\"rate_type\":\"Hourly Rate\",\"rate\":\"\",\"wage_type\":\"Regular Wages\",\"count_toward_minimum_wage\":\"Yes\",\"non_monetary_income\":\"0\",\"process_as_ot\":\"0\",\"report_as_a_fringe_benefit\":\"0\",\"from_w-2_box_14\":\"0\",\"update_balances_\":\"0\",\"leave_plan\":\"0\",\"federal_loan_assessment\":\"Yes\",\"federal_income_tax\":\"Yes\",\"federal_income_tax_additional\":\"Yes\",\"federal_income_tax_fixed_rate\":\"No\",\"social_security_company\":\"Yes\",\"social_security_employee\":\"Yes\",\"medicare_company\":\"Yes\",\"medicare_employee\":\"Yes\",\"federal_unemployment_insurance\":\"Yes\",\"mn_income_tax\":\"Yes\",\"mn_income_tax_additional\":\"Yes\",\"mn_income_tax_fixed_rate\":\"No\",\"mn_unemployment_insurance\":\"Yes\",\"mn_workforce_dev_assessment\":\"Yes\"}"}]';
        // _e(json_decode($json,true),true); 
        _e($customEarning,true,true);   
    }

    public function test2()
    {
        $this->load->model("v1/Fillable_documents_model", "fillable_documents_model");
        $this->fillable_documents_model->checkAndAddFillableDocuments(21);

        // $this->load->view("v1/documents/fillable/oral_employee_counselling_report_form");
    }
}
