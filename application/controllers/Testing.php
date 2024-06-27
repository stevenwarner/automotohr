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

        return urlencode($fileName);
    }

    public function addNewEmployeesIntoCompany($companyId)
    {
        // Load the fake employee library
        $this->load->library('fake_employees/Fake_employees', null, 'fakeEmployees');
        //
        $employees =
            $this
            ->fakeEmployees
            ->init(5);
        //
        foreach ($employees as $employee) {
            $employee['parent_sid'] = $companyId;
            $employee['active'] = 1;
            //
            $this->db->insert('users', $employee);
        }
        //
        _e("employee add successfully");
    }

    function payroll()
    {
        $payrollInfo = [
            [
                "payroll_deadline" => "2024-02-18T22:00:00Z",
                "check_date" => "2024-02-22",
                "off_cycle" => false,
                "external" => false,
                "processed" => true,
                "processed_date" => "2024-02-18",
                "calculated_at" => "2024-02-18T12:00:00Z",
                "payroll_uuid" => "b50e611d-8f3d-4f24-b001-46675f7b5777",
                "company_uuid" => "6bf7807c-a5a0-4f4d-b2e7-3fbb4b2299fb",
                "created_at" => "2024-02-01T22:00:00Z",
                "pay_period" => [
                    "start_date" => "2024-02-01",
                    "end_date" => "2024-02-15",
                    "pay_schedule_uuid" => "00ebc4a4-ec88-4435-8f45-c505bb63e501"
                ],
                "totals" => [
                    "company_debit" => "121747.71",
                    "net_pay_debit" => "79283.80",
                    "tax_debit" => "42463.91",
                    "reimbursement_debit" => "0.00",
                    "child_support_debit" => "0.00",
                    "reimbursements" => "0.00",
                    "net_pay" => "81752.94",
                    "gross_pay" => "130635.89",
                    "employee_bonuses" => "0.00",
                    "employee_commissions" => "18536.37",
                    "employee_cash_tips" => "0.00",
                    "employee_paycheck_tips" => "0.00",
                    "additional_earnings" => "0.00",
                    "owners_draw" => "0.00",
                    "check_amount" => "2469.14",
                    "employer_taxes" => "6917.19",
                    "employee_taxes" => "35546.72",
                    "benefits" => "0.00",
                    "employee_benefits_deductions" => "13336.23",
                    "deferred_payroll_taxes" => "0.00",
                    "other_deductions" => "240.00"
                ]
            ],
            [
                "payroll_deadline" => "2024-02-28T12:00:00Z",
                "check_date" => "2024-03-01",
                "off_cycle" => false,
                "external" => false,
                "processed" => true,
                "processed_date" => "2024-02-28T12:00:00Z",
                "calculated_at" => "2024-02-28",
                "payroll_uuid" => "b50e611d-8f3d-4f24-b001-46675f7b5777",
                "company_uuid" => "6bf7807c-a5a0-4f4d-b2e7-3fbb4b2299fb",
                "created_at" => "2022-02-01T22:00:00Z",
                "pay_period" => [
                    "start_date" => "2024-02-16",
                    "end_date" => "2024-03-01",
                    "pay_schedule_uuid" => "00ebc4a4-ec88-4435-8f45-c505bb63e501"
                ],
                "totals" => [
                    "company_debit" => "121747.71",
                    "net_pay_debit" => "79283.80",
                    "tax_debit" => "42463.91",
                    "reimbursement_debit" => "0.00",
                    "child_support_debit" => "0.00",
                    "reimbursements" => "0.00",
                    "net_pay" => "81752.94",
                    "gross_pay" => "130635.89",
                    "employee_bonuses" => "0.00",
                    "employee_commissions" => "18536.37",
                    "employee_cash_tips" => "0.00",
                    "employee_paycheck_tips" => "0.00",
                    "additional_earnings" => "0.00",
                    "owners_draw" => "0.00",
                    "check_amount" => "2469.14",
                    "employer_taxes" => "6917.19",
                    "employee_taxes" => "35546.72",
                    "benefits" => "0.00",
                    "employee_benefits_deductions" => "13336.23",
                    "deferred_payroll_taxes" => "0.00",
                    "other_deductions" => "240.00"
                ]
            ],
            [
                "payroll_deadline" => "2024-04-28T22:00:00Z",
                "check_date" => "2024-05-01",
                "off_cycle" => false,
                "external" => false,
                "processed" => true,
                "processed_date" => "2024-04-28T22:00:00Z",
                "calculated_at" => "2024-04-28",
                "payroll_uuid" => "b50e611d-8f3d-4f24-b001-46675f7b5777",
                "company_uuid" => "6bf7807c-a5a0-4f4d-b2e7-3fbb4b2299fb",
                "created_at" => "2022-04-01T22:00:00Z",
                "pay_period" => [
                    "start_date" => "2024-04-16",
                    "end_date" => "2024-05-01",
                    "pay_schedule_uuid" => "00ebc4a4-ec88-4435-8f45-c505bb63e501"
                ],
                "totals" => [
                    "company_debit" => "121747.71",
                    "net_pay_debit" => "79283.80",
                    "tax_debit" => "42463.91",
                    "reimbursement_debit" => "0.00",
                    "child_support_debit" => "0.00",
                    "reimbursements" => "0.00",
                    "net_pay" => "81752.94",
                    "gross_pay" => "130635.89",
                    "employee_bonuses" => "0.00",
                    "employee_commissions" => "18536.37",
                    "employee_cash_tips" => "0.00",
                    "employee_paycheck_tips" => "0.00",
                    "additional_earnings" => "0.00",
                    "owners_draw" => "0.00",
                    "check_amount" => "2469.14",
                    "employer_taxes" => "6917.19",
                    "employee_taxes" => "35546.72",
                    "benefits" => "0.00",
                    "employee_benefits_deductions" => "13336.23",
                    "deferred_payroll_taxes" => "0.00",
                    "other_deductions" => "240.00"
                ]
            ]
        ];

        foreach ($payrollInfo as $payroll) {
            $ins = [];
            $ins['check_date'] = $payroll['check_date'];
            $ins['payroll_deadline'] = $payroll['payroll_deadline'];
            $ins['processed'] = $payroll['processed'];
            $ins['processed_date'] = $payroll['processed_date'];
            $ins['calculated_at'] = $payroll['calculated_at'];
            $ins['last_changed_by'] = 15717;
            $ins['is_late_payroll'] =  0;
            $ins['company_sid'] = 15708;
            $ins['gusto_uuid'] = $payroll['payroll_uuid'];
            $ins['start_date'] = $payroll['pay_period']['start_date'];
            $ins['end_date'] = $payroll['pay_period']['end_date'];
            $ins['gusto_pay_schedule_uuid'] = $payroll['pay_period']['pay_schedule_uuid'];
            $ins['calculated_at'] = $payroll['calculated_at'];
            $ins['totals'] = json_encode($payroll['totals']);
            $ins['updated_at'] = getSystemDate();
            $ins['created_at'] = getSystemDate();
            // insert it
            // $this->db->insert('payrolls.regular_payrolls', $ins);
        }

        $this->db
            ->select('
                sid,
                start_date,
                end_date,
                check_date,
                totals
            ');
        //
        $records = $this->db
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //    
        _e($records, true, true);
    }
}
