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

    public function getManagers () {
        $managers = get_notification_email_contacts(
            64187,
            "course_status"
        );
        _e($managers,true,true);
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

    public function getEmploymentData($sid = '', $changeFrom = '', $employerSid = 0)
    {
        //
        $this->db->select("sid,registration_date,joined_at,rehire_date,employment_date");
        //
        if ($sid != '') {
            $this->db->where('sid', $sid);
        }
        //
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where_in('employee_type', ['fulltime','full-time']);
        $this->db->where('parent_sid <>', 0);
        $this->db->where('employment_date', null);
        $this->db->where('is_executive_admin', 0);
        $employees = $this->db->get("users")->result_array();
        //
        if (!empty($employees)) {
            foreach ($employees as $employeeRow) {

                $latestDate = get_employee_latest_joined_date(
                    $employeeRow['registration_date'],
                    $employeeRow['joined_at'],
                    $employeeRow['rehire_date'],
                    false
                );

                // Update User Employment Date
                $this->db->where("sid", $employeeRow["sid"])
                    ->update("users", [
                        "employment_date" => $latestDate
                    ]);


                //Save Histroy
                $historyArray['employment_date'] = array('old' => $employeeRow['employment_date'], 'new' => $latestDate);

                $insertHistory['user_sid'] = $employeeRow['sid'];
                $insertHistory['employer_sid'] = $employerSid;
                $insertHistory['history_type'] = 'profile';
                $insertHistory['created_at'] = getSystemDate();
                $insertHistory['change_from'] = $changeFrom;
                $insertHistory['profile_data'] = json_encode($historyArray);

                $this->db->insert('profile_history', $insertHistory);
            }
        }
        //
        echo "All Done";
    }

     /**
     * 
     */
    private function addLastRead($sid)
    {
        $this->db
            ->where('sid', $sid)
            ->set([
                'last_read' => date('Y-m-d H:i:s', strtotime('now')),
                'referral' => !empty($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : ''
            ])->update('job_feeds_management');
        //
        $this->db
            ->insert('job_feeds_management_history', [
                'feed_id' => $sid,
                'referral' => !empty($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : '',
                'created_at' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
    }

    public function countJob()
    {
        $this->load->model('all_feed_model');
        $this->load->model('indeed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
        $sid = $this->isActiveFeed();
        $this->addLastRead(7);
        //
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
        // Get Indeed Paid Job Ids
        $indeedPaidJobIds = $this->indeed_model->getIndeedPaidJobIds();
        $indeedPaidJobs = [];
        if (sizeof($indeedPaidJobIds['Ids'])) {
            // Get Indeed Paid Jobs
            $jobIds = $indeedPaidJobIds['Ids'];
            $budget = $indeedPaidJobIds['Budget'];
            $indeedPaidJobs = $this->indeed_model->getIndeedPaidJobs();
        } else $budget = $jobIds = array();
        // Get Indeed Organic Jobs
        $indeedOrganicJobs = $this->indeed_model->getIndeedOrganicJobs($featuredJobs);
        // Get Active companies
        $activeCompanies = $this->indeed_model->getAllActiveCompanies($sid);
        //
        $rows = '';
        //
        $infoArray = array();
        $infoArray['Skipped']['Paid'] = array();
        $infoArray['Skipped']['Organic'] = array();
        $infoArray['Listed']['Paid'] = array();
        $infoArray['Listed']['Organic'] = array();
        //
        $totalJobsForFeed = 0;

        // Loop through Organic Jobs
        if (sizeof($indeedPaidJobs)) {
            foreach ($indeedPaidJobs as $job) {
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                //
                $contactName = $companyData['full_name'];
                $contactPhone = $companyData['phone_number'];
                $contactEmail = $companyData['email'];
                // Check for company indeed details
                $indeedDetails = $this->indeed_model->GetCompanyIndeedDetails($job['user_sid'], $job['sid']);
                //
                if (!empty($indeedDetails['Name'])) {
                    $contactName = $indeedDetails['Name'];
                }
                if (!empty($indeedDetails['Phone'])) {
                    $contactPhone = $indeedDetails['Phone'];
                }
                if (!empty($indeedDetails['Email'])) {
                    $contactEmail = $indeedDetails['Email'];
                }

                //
                $uid = $job['sid'];
                $publishDate = $job['activation_date'];
                $feedData = $this->indeed_model->fetchUidFromJobSid($uid);
                //
                if (sizeof($feedData)) {
                    $uid = $feedData['uid'];
                    $publishDate = $feedData['publish_date'];
                }
                //
                $jobDesc = StripFeedTags($job['JobDescription']);
                $country['country_code'] = "US";
                $state['state_name'] = "";
                $city = "";
                $zipcode = "";
                $salary = "";
                $jobType = "";
                //
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc .= '<br><br>Job Requirements:<br>' . StripFeedTags($job['JobRequirements']);
                }
                //
                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country = db_get_country_name($job['Location_Country']);
                }
                //
                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state = db_get_state_name($job['Location_State']);
                }
                //
                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city = $job['Location_City'];
                }
                //
                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode = $job['Location_ZipCode'];
                }
                //
                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                }
                //


                if (isset($job['JobType']) && $job['JobType'] != NULL) {
                    $job['JobType'] = trim($job['JobType']);
                    if ($job['JobType'] == 'Full Time') {
                        $jobType = "Full Time";
                    } elseif ($job['JobType'] == 'Part Time') {
                        $jobType = "Part Time";
                    } elseif ($job['JobType'] == 'Seasonal') {
                        $jobType = "Seasonal";
                    }
                }


                //
                $JobCategorys = $job['JobCategory'];
                //
                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    $job_category_array = array();
                    //
                    foreach ($cat_id as $id) {
                        $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                        $job_category_array[] = $job_cat_name[0]['value'];
                    }

                    $job_category = implode(', ', $job_category_array);
                }
                //
                $infoArray['Listed']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid']);
                //
                $salary = remakeSalary($salary, $job['SalaryType']);
                //
                $isSponsored = in_array($job['sid'], $jobIds) ? "yes" : "no";
                $hasBudget = in_array($job['sid'], $jobIds) ? $budget[$job['sid']] : "0";
                //
                $jobQuestionnaireUrl = "";
                //
                if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                    //
                    $this->indeed_model->saveQuestionIntoFile($job['sid'], $job['user_sid'], true);
                    //
                    $jobQuestionnaireUrl = "&indeed-apply-questions=";
                    $jobQuestionnaireUrl .= urlencode(
                        STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                    );
                }
                //
                $rows .= "
                    <job>
                        <title><![CDATA[" . $job['Title'] . "]]></title>
                        <sponsored><![CDATA[" . ($isSponsored) . "]]></sponsored>
                        <budget><![CDATA[" . ($hasBudget) . "]]></budget>
                        <date><![CDATA[" . (DateTime::createFromFormat('Y-m-d H:i:s', $publishDate)->format('D, d M Y H:i:s')) . " PST]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <requisitionid><![CDATA[" . $job['sid'] . "]]></requisitionid>
                        <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <sourcename><![CDATA[" . $companyName . "]]></sourcename>
                        <city><![CDATA[" . $city . "]]></city>
                        <state><![CDATA[" . $state['state_name'] . "]]></state>
                        <country><![CDATA[" . $country['country_code'] . "]]></country>
                        <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                        <salary><![CDATA[" . $salary . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <description><![CDATA[" . $jobDesc . "]]></description>
                        <metadata><![CDATA[]]></metadata>
                        <email><![CDATA[" . $contactEmail . "]]></email>
                        <phonenumber><![CDATA[" . $contactPhone . "]]></phonenumber>
                        <contact><![CDATA[" . $contactName . "]]></contact>
                        <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($companySid, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";

                $totalJobsForFeed++;
            }
        }

        // Loop through Organic Jobs
        if (sizeof($indeedOrganicJobs)) {
            foreach ($indeedOrganicJobs as $job) {
                if (in_array($job['sid'], $jobIds)) {
                    continue;
                }
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                //
                $contactName = $companyData['full_name'];
                $contactPhone = $companyData['phone_number'];
                $contactEmail = $companyData['email'];
                // Check for company indeed details
                $indeedDetails = $this->indeed_model->GetCompanyIndeedDetails($job['user_sid'], $job['sid']);
                //
                if (!empty($indeedDetails['Name'])) {
                    $contactName = $indeedDetails['Name'];
                }
                if (!empty($indeedDetails['Phone'])) {
                    $contactPhone = $indeedDetails['Phone'];
                }
                if (!empty($indeedDetails['Email'])) {
                    $contactEmail = $indeedDetails['Email'];
                }
                //
                $uid = $job['sid'];
                $publishDate = $job['activation_date'];
                $feedData = $this->indeed_model->fetchUidFromJobSid($uid);
                //
                if (sizeof($feedData)) {
                    $uid = $feedData['uid'];
                    $publishDate = $feedData['publish_date'];
                }
                //
                $jobDesc = StripFeedTags($job['JobDescription']);
                $country['country_code'] = "US";
                $state['state_name'] = "";
                $city = "";
                $zipcode = "";
                $salary = "";
                $jobType = "";


                //
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc .= '<br><br>Job Requirements:<br>' . StripFeedTags($job['JobRequirements']);
                }
                //
                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country = db_get_country_name($job['Location_Country']);
                }
                //
                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state = db_get_state_name($job['Location_State']);
                }
                //
                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city = $job['Location_City'];
                }
                //
                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode = $job['Location_ZipCode'];
                }
                //
                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                }

                //
                if (isset($job['JobType']) && $job['JobType'] != NULL) {
                    $job['JobType'] = trim($job['JobType']);
                    if ($job['JobType'] == 'Full Time') {
                        $jobType = "Full Time";
                    } elseif ($job['JobType'] == 'Part Time') {
                        $jobType = "Part Time";
                    } elseif ($job['JobType'] == 'Seasonal') {
                        $jobType = "Seasonal";
                    }
                }


                //
                $JobCategorys = $job['JobCategory'];
                //
                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    $job_category_array = array();
                    //
                    foreach ($cat_id as $id) {
                        $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                        $job_category_array[] = $job_cat_name[0]['value'];
                    }

                    $job_category = implode(', ', $job_category_array);
                }
                //
                $infoArray['Listed']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid']);
                //
                $salary = remakeSalary($salary, $job['SalaryType']);
                //
                $isSponsored = in_array($job['sid'], $jobIds) ? "yes" : "no";
                $hasBudget = in_array($job['sid'], $jobIds) ? $budget[$job['sid']] : "0";
                //
                $jobQuestionnaireUrl = "";
                //
                if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                    //
                    if ($this->indeed_model->saveQuestionIntoFile($job['sid'], $job['user_sid'], true)) {
                        $jobQuestionnaireUrl = "&indeed-apply-questions=";
                        $jobQuestionnaireUrl .= urlencode(
                            STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                        );
                    }
                }
                //
                $rows .= "
                    <job>
                        <title><![CDATA[" . $job['Title'] . "]]></title>
                        <sponsored><![CDATA[" . ($isSponsored) . "]]></sponsored>
                        <budget><![CDATA[" . ($hasBudget) . "]]></budget>
                        <date><![CDATA[" . (DateTime::createFromFormat('Y-m-d H:i:s', $publishDate)->format('D, d M Y H:i:s')) . " PST]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <requisitionid><![CDATA[" . $job['sid'] . "]]></requisitionid>
                        <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <sourcename><![CDATA[" . $companyName . "]]></sourcename>
                        <city><![CDATA[" . $city . "]]></city>
                        <state><![CDATA[" . $state['state_name'] . "]]></state>
                        <country><![CDATA[" . $country['country_code'] . "]]></country>
                        <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                        <salary><![CDATA[" . $salary . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <description><![CDATA[" . $jobDesc . "]]></description>
                        <metadata><![CDATA[]]></metadata>
                        <email><![CDATA[" . $contactEmail . "]]></email>
                        <phonenumber><![CDATA[" . $contactPhone . "]]></phonenumber>
                        <contact><![CDATA[" . $contactName . "]]></contact>
                        <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($companySid, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";

                $totalJobsForFeed++;
            }
        }
        _e($totalJobsForFeed,true,true);

        // Post data to browser
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');

        $det = '';
        $det .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

        // echo '<xml>';
        $det .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";
        $det .=  trim($rows);
        $det .=  '</source>';
        echo trim($det);
        mail(TO_EMAIL_DEV, 'New Indeed hit XML: ' . date('Y-m-d H:i:s'), print_r($infoArray, true));
        exit;
    }

    private function isActiveFeed()
    {
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('indeed_new');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }

        return $validSlug;
    }
   

    function addScormCourses () {
        //
        $results = $this->db->select("sid, course_file_name, Imsmanifist_json")
            ->where("company_sid", 0)
            ->where("course_type", 'scorm')
            ->where("course_file_name <>", null)
            ->where("course_file_name <>", '')
            ->get("lms_default_courses")
            ->result_array();

        foreach ($results as $v) {
            $insert_data = array();
            $insert_data['course_sid'] = $v['sid'];
            $insert_data['course_file_name'] = $v['course_file_name'];
            $insert_data['course_file_language'] = 'english';
            $insert_data['Imsmanifist_json'] = $v['Imsmanifist_json'];
            $insert_data['created_at'] = getSystemDate();
            $insert_data['updated_at'] = getSystemDate();
            //
            $this->db->insert('lms_scorm_courses', $insert_data);
        }
    }
}




if (!function_exists('remakeSalary')) {
    function remakeSalary($salary, $jobType)
    {
        $salary = trim(str_replace([',', 'k', 'K'], ['', '000', '000'], $salary));
        $jobType = strtolower($jobType);
        //
        if (preg_match('/year|yearly/', $jobType)) $jobType = 'per year';
        else if (preg_match('/month|monthly/', $jobType)) $jobType = 'per month';
        else if (preg_match('/week|weekly/', $jobType)) $jobType = 'per week';
        else if (preg_match('/hour|hourly/', $jobType)) $jobType = 'per hour';
        else $jobType = 'per year';
        //
        if ($salary == '') return $salary;
        //
        if (strpos($salary, '$') === FALSE)
            $salary = preg_replace('/(?<![^ ])(?=[^ ])(?![^0-9])/', '$', $salary);
        //
        $salary = $salary . ' ' . $jobType;
        //
        return $salary;
    }
}


