<?php defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('checkAndGetSession')) {
    /**
     * Check and get session
     * 
     * Make sure the session is in place, and base of options
     * it either send json response or redirect or return bool
     * 
     * @param string $index
     * @param bool   $doReturn
     */
    function checkAndGetSession(string $index = 'all', bool $doReturn = false)
    {
        // get CI instance
        $CI = &get_instance();
        // get the session
        $session = $CI->session->userdata('logged_in');
        // check if session is set or not
        if (!$session) {
            // when we need to return check
            if ($doReturn) {
                return false;
            }
            // check if it is an AJAX call then send back response
            if ($CI->input->is_ajax_request()) {
                return SendResponse(401, ['errors' => ['Session is expired. Please log in again.']]);
            }
            //
            return redirect('/login');
        }
        // get the employee detail session
        if ($index == 'employee') {
            $index = 'employer_detail';
        }
        // get the company detail session
        if ($index == 'company') {
            $index = 'company_detail';
        }
        // get the portal detail session
        if ($index == 'portal') {
            $index = 'portal_detail';
        }
        // when session is set
        return isset($session[$index]) ? $session[$index] : $session;
    }
}

if (!function_exists('getPolicyDifference')) {
    /**
     * Compare two policies data
     * 
     * @param array $oldData
     * @param array $newData
     * @return array
     */
    function getPolicyDifference(array $oldData, array $newData)
    {
        // decode the JSON
        $newDataArray = json_decode($newData['note'], true);
        $newDataAccrualArray = json_decode($newDataArray['accruals'], true);
        $oldDataArray = json_decode($oldData['note'], true);
        $oldDataAccrualArray = json_decode($oldDataArray['accruals'], true);
        //
        if ($oldDataArray['transferred']) {
            //
            $differenceArray =  [
                'policy_title' => [
                    'old_value' => $oldDataArray['title'],
                    'new_value' => $newDataArray['title']
                ],
                'transferred' => [
                    'old_value' => '',
                    'new_value' => 'The time off requests of the following employee have been successfully transferred from the "<strong>' . $oldDataArray['title'] . '</strong>" policy.'
                ]
            ];
            // get CI instance
            $CI = &get_instance();
            // tag in for employees
            if ($oldDataArray['employee_sid']) {
                // get the employee details
                $user = $CI->db->select(getUserFields())
                    ->where('sid', $oldDataArray['employee_sid'])
                    ->get('users')
                    ->row_array();
                $differenceArray['transferred']['new_value'] .= '<br/><br/><p><strong>' . (remakeEmployeeName($user)) . '</strong></p>';
            }

            return $differenceArray;
        }
        //
        if (!isset($oldDataArray['title']) && isset($oldDataArray['is_archived'])) {
            // only when there is a difference
            if ($$oldDataArray['is_archived'] != $newDataArray['is_archived']) {
                return [
                    'deactivate_this_policy' => [
                        'old_value' => $oldDataArray['is_archived'] == 1 ? 'YES' : 'NO',
                        'new_value' => $newDataArray['is_archived'] == 1 ? 'YES' : 'NO'
                    ]
                ];
            }
            //
            return [];
        }
        $differenceArray = [];
        // set proper data array
        $newDataDifferenceArray = [];
        $newDataDifferenceArray['policy_title'] = $newDataArray['title'];
        $newDataDifferenceArray['is_employees_entitled_for_policy'] = $newDataArray['is_entitled_employee'] == '0' ? 'NO' : 'YES';
        $newDataDifferenceArray['employees'] = $newDataArray['assigned_employees'];
        $newDataDifferenceArray['employee_type'] = ucwords(implode(',', $newDataAccrualArray['employeeTypes']));
        $newDataDifferenceArray['week_off_days'] = ucwords(implode(',', $newDataArray['off_days']));
        $newDataDifferenceArray['make_this_visible_to_the_following_approvers'] = $newDataArray['for_admin'] == '0' ? 'NO' : 'YES';
        $newDataDifferenceArray['allowed_approver'] = $newDataArray['allowed_approvers'];
        $newDataDifferenceArray['deactivate_this_policy'] = $newDataArray['is_archived'] == '0' ? 'NO' : 'YES';
        $newDataDifferenceArray['include_this_policy_time_in_balance'] = $newDataArray['is_included'] == '0' ? 'NO' : 'YES';
        // Accruals
        $newDataDifferenceArray['minimum_applicable_time_for_this_policy_to_take_affect'] = $newDataAccrualArray['applicableTimeType'];
        $newDataDifferenceArray['minimum_applicable_time_for_this_policy_to_take_affect_time'] = $newDataAccrualArray['applicableTime'];
        $newDataDifferenceArray['accrual_frequency'] = $newDataAccrualArray['frequency'];
        $newDataDifferenceArray['accrual_rate'] = $newDataAccrualArray['rate'];
        $newDataDifferenceArray['accrual_rate_type'] = ucwords(str_replace('_', ' ', $newDataAccrualArray['rateType']));
        $newDataDifferenceArray['accrual_plans'] = $newDataAccrualArray['plans'];
        // carryover
        $newDataDifferenceArray['allow_this_policy_to_annually_carryover'] = $newDataAccrualArray['carryOverCheck'];
        $newDataDifferenceArray['allow_this_policy_to_annually_carryover_type'] = ucwords(str_replace('_', ' ', $newDataAccrualArray['carryOverType']));
        $newDataDifferenceArray['allow_this_policy_to_annually_carryover_val'] = $newDataAccrualArray['carryOverVal'];
        // carryover
        $newDataDifferenceArray['allow_negative_balance'] = $newDataAccrualArray['negativeBalanceCheck'];
        $newDataDifferenceArray['allow_negative_balance_type'] = ucwords(str_replace('_', ' ', $newDataAccrualArray['negativeBalanceType']));
        $newDataDifferenceArray['allow_negative_balance_val'] = $newDataAccrualArray['negativeBalanceVal'];
        // applicable date
        $newDataDifferenceArray['policy_applicable_date'] = $newDataAccrualArray['applicableDateType'] == 'hireDate' ? 'Employee Hire Date' : 'Pick A Date';
        $newDataDifferenceArray['policy_applicable_date_val'] = $newDataAccrualArray['applicableDate'] == '0' ? '0' : $newDataAccrualArray['applicableDate'];
        // reset date
        $newDataDifferenceArray['policy_reset_date'] = $newDataAccrualArray['resetDateType'] == 'policyDate' ? 'Policy Applicable Date' : 'Pick A Date';
        $newDataDifferenceArray['policy_reset_date_val'] = $newDataAccrualArray['resetDate'] == '0' ? '0' : $newDataAccrualArray['resetDate'];
        // reset date
        $newDataDifferenceArray['probation_period'] = $newDataAccrualArray['newHireTime'];
        $newDataDifferenceArray['probation_period_type'] = $newDataAccrualArray['newHireTimeType'];
        $newDataDifferenceArray['probation_period_val'] = $newDataAccrualArray['newHireRate'];

        // set proper data array
        $oldDataDifferenceArray = [];
        $oldDataDifferenceArray['policy_title'] = $oldDataArray['title'];
        $oldDataDifferenceArray['is_employees_entitled_for_policy'] = $oldDataArray['is_entitled_employee'] == '0' ? 'NO' : 'YES';
        $oldDataDifferenceArray['employees'] = $oldDataArray['assigned_employees'];
        $oldDataDifferenceArray['employee_type'] = ucwords(implode(',', $oldDataAccrualArray['employeeTypes']));
        $oldDataDifferenceArray['week_off_days'] = ucwords(implode(',', $oldDataArray['off_days']));
        $oldDataDifferenceArray['make_this_visible_to_the_following_approvers'] = $oldDataArray['for_admin'] == '0' ? 'NO' : 'YES';
        $oldDataDifferenceArray['allowed_approver'] = $oldDataArray['allowed_approvers'];
        $oldDataDifferenceArray['deactivate_this_policy'] = $oldDataArray['is_archived'] == '0' ? 'NO' : 'YES';
        $oldDataDifferenceArray['include_this_policy_time_in_balance'] = $oldDataArray['is_included'] == '0' ? 'NO' : 'YES';
        // Accruals
        $oldDataDifferenceArray['minimum_applicable_time_for_this_policy_to_take_affect'] = $oldDataAccrualArray['applicableTimeType'];
        $oldDataDifferenceArray['minimum_applicable_time_for_this_policy_to_take_affect_time'] = $oldDataAccrualArray['applicableTime'];
        $oldDataDifferenceArray['accrual_frequency'] = $oldDataAccrualArray['frequency'];
        $oldDataDifferenceArray['accrual_rate'] = $oldDataAccrualArray['rate'];
        $oldDataDifferenceArray['accrual_rate_type'] = ucwords(str_replace('_', ' ', $oldDataAccrualArray['rateType']));
        $oldDataDifferenceArray['accrual_plans'] = $oldDataAccrualArray['plans'];
        // carryover
        $oldDataDifferenceArray['allow_this_policy_to_annually_carryover'] = $oldDataAccrualArray['carryOverCheck'];
        $oldDataDifferenceArray['allow_this_policy_to_annually_carryover_type'] = ucwords(str_replace('_', ' ', $oldDataAccrualArray['carryOverType']));
        $oldDataDifferenceArray['allow_this_policy_to_annually_carryover_val'] = $oldDataAccrualArray['carryOverVal'];
        // carryover
        $oldDataDifferenceArray['allow_negative_balance'] = $oldDataAccrualArray['negativeBalanceCheck'];
        $oldDataDifferenceArray['allow_negative_balance_type'] = ucwords(str_replace('_', ' ', $oldDataAccrualArray['negativeBalanceType']));
        $oldDataDifferenceArray['allow_negative_balance_val'] = $oldDataAccrualArray['negativeBalanceVal'];
        // applicable date
        $oldDataDifferenceArray['policy_applicable_date'] = $oldDataAccrualArray['applicableDateType'] == 'hireDate' ? 'Employee Hire Date' : 'Pick A Date';
        $oldDataDifferenceArray['policy_applicable_date_val'] = $oldDataAccrualArray['applicableDate'] == '0' ? '0' : $oldDataAccrualArray['applicableDate'];
        // reset date
        $oldDataDifferenceArray['policy_reset_date'] = $oldDataAccrualArray['resetDateType'] == 'policyDate' ? 'Policy Applicable Date' : 'Pick A Date';
        $oldDataDifferenceArray['policy_reset_date_val'] = $oldDataAccrualArray['resetDate'] == '0' ? '0' : $oldDataAccrualArray['resetDate'];
        // reset date
        $oldDataDifferenceArray['probation_period'] = $oldDataAccrualArray['newHireTime'];
        $oldDataDifferenceArray['probation_period_type'] = $oldDataAccrualArray['newHireTimeType'];
        $oldDataDifferenceArray['probation_period_val'] = $oldDataAccrualArray['newHireRate'];
        // set default difference array
        $differenceArray = [];
        // loop through the data
        foreach ($oldDataDifferenceArray as $index => $value) {
            //
            $newValue = $newDataDifferenceArray[$index];
            //
            if ($value != $newValue) {
                // for 0
                if ($value == '0') {
                    $value = '';
                }
                // for 0
                if ($newValue == '0') {
                    $newValue = '';
                }
                // check for date
                if (preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $newValue)) {
                    $newValue = formatDateToDB($newValue, 'm-d-Y', DATE);
                }
                $differenceArray[$index] = [
                    'old_value' => $value,
                    'new_value' => $newValue
                ];
            }
        }
        // get CI instance
        $CI = &get_instance();
        // tag in for employees
        if (isset($differenceArray['employees'])) {
            //
            if ($differenceArray['employees']['old_value']) {
                //
                $oldEmployeesList = explode(',', $differenceArray['employees']['old_value']);
                // get the employee details
                $users = $CI->db->select(getUserFields())
                    ->where_in('sid', $oldEmployeesList)
                    ->get('users')
                    ->result_array();
                //
                $differenceArray['employees']['old_value'] = [];
                //
                foreach ($users as $user) {
                    $differenceArray['employees']['old_value'][] = remakeEmployeeName($user);
                }
            }
            //
            if ($differenceArray['employees']['new_value']) {
                //
                $newEmployeeList = explode(',', $differenceArray['employees']['new_value']);
                // get the employee details
                $users = $CI->db->select(getUserFields())
                    ->where_in('sid', $newEmployeeList)
                    ->get('users')
                    ->result_array();
                //
                $differenceArray['employees']['new_value'] = [];
                //
                foreach ($users as $user) {
                    $differenceArray['employees']['new_value'][] = remakeEmployeeName($user);
                }
            }
        }
        if (isset($differenceArray['allowed_approver'])) {
            //
            if ($differenceArray['allowed_approver']['old_value']) {
                //
                $oldEmployeesList = explode(',', $differenceArray['allowed_approver']['old_value']);
                // get the employee details
                $users = $CI->db->select(getUserFields())
                    ->where_in('sid', $oldEmployeesList)
                    ->get('users')
                    ->result_array();
                //
                $differenceArray['allowed_approver']['old_value'] = [];
                //
                foreach ($users as $user) {
                    $differenceArray['allowed_approver']['old_value'][] = remakeEmployeeName($user);
                }
            }
            //
            if ($differenceArray['allowed_approver']['new_value']) {
                //
                $newEmployeeList = explode(',', $differenceArray['allowed_approver']['new_value']);
                // get the employee details
                $users = $CI->db->select(getUserFields())
                    ->where_in('sid', $newEmployeeList)
                    ->get('users')
                    ->result_array();
                //
                $differenceArray['allowed_approver']['new_value'] = [];
                //
                foreach ($users as $user) {
                    $differenceArray['allowed_approver']['new_value'][] = remakeEmployeeName($user);
                }
            }
        }
        // return the difference array
        return $differenceArray;
    }
}

if (!function_exists('updateUserById')) {
    /**
     * Update user
     * 
     * Central point to update user in system, the system will user information to
     * all the other places, like profile, w4, I9, full employment etc.
     * 
     * @param array $updateArray
     * @param int   $employeeId
     */
    function updateUserById(array $updateArray, int $employeeId)
    {
        // get the CI instance
        $CI = &get_instance();
        // get the difference
        $differenceArray = checkAndSaveTheDifference(
            $updateArray,
            $employeeId
        );
        // update the user in "users" table
        $CI->db
            ->where("sid", $employeeId)
            ->update('users', $updateArray);

        // only process if there is a different
        if ($differenceArray) {
            checkAndUpdateEmailToNotifications(
                $updateArray,
                $employeeId,
                $differenceArray
            );
        }
    }
}

if (!function_exists('checkAndUpdateEmailToNotifications')) {
    /**
     * Check and update employee details to notifications
     * 
     * @param array $updateArray
     * @param int   $employeeId
     */
    function checkAndUpdateEmailToNotifications(array $updateArray, int $employeeId, array $differenceArray = [])
    {
        // get the CI instance
        $CI = &get_instance();
        //
        $upd = [];
        // get the difference
        $differenceArray = $differenceArray ? $differenceArray :
            checkAndSaveTheDifference(
                $updateArray,
                $employeeId,
                true
            );
        //
        if ($differenceArray["email"]) {
            $upd["email"] = $differenceArray["email"]["new"];
        }
        //
        if ($differenceArray["PhoneNumber"]) {
            $upd["contact_no"] = $differenceArray["PhoneNumber"]["new"];
        }
        //
        if ($differenceArray["first_name"] || $differenceArray["last_name"]) {
            $upd["contact_name"] = $updateArray["first_name"] . ' ' . $updateArray["last_name"];
        }
        //
        if ($upd) {
            // update the user in "notifications_emails_management" table
            $CI->db
                ->where("employer_sid", $employeeId)
                ->update('notifications_emails_management', $upd);
        }
    }
}

if (!function_exists('checkAndSaveTheDifference')) {
    /**
     * Update user
     * 
     * Central point to update user in system, the system will user information to
     * all the other places, like profile, w4, I9, full employment etc.
     * 
     * @param array $newData
     * @param int $employeeId
     * @param bool $doReturn Optional -> false
     * @return array
     */
    function checkAndSaveTheDifference(array $newData, int $employeeId, bool $doReturn = false): array
    {
        // get CI instance
        $CI = &get_instance();
        // get the old column data
        $oldData = $CI->db
            ->select(array_keys($newData))
            ->where("sid", $employeeId)
            ->get("users")
            ->row_array();
        // set difference array
        $diffArray = [];
        //
        foreach ($oldData as $index => $value) {
            if ($newData[$index] != $value) {
                $diffArray[$index] = [
                    "old" => $value,
                    "new" => $newData[$index]
                ];
            }
        }
        // only add if something is changed
        if (!$doReturn && $diffArray) {
            // insert the history
            $CI->db
                ->insert('profile_history', [
                    "user_sid" => $employeeId,
                    "employer_sid" => $CI->session->userdata("logged_in")["employer_detail"]["sid"] ?? 0,
                    "profile_data" => json_encode($diffArray),
                    "created_at" => getSystemDate(),
                ]);
        }
        //
        return $diffArray;
    }
}

if (!function_exists('getUserStartDate')) {
    /**
     * Get user joining date
     * 
     * Get the user start date; registration date, joined dated
     * and rehire date
     * 
     * @param int  $employeeId
     * @param bool $includeRehireDate Optional Default is "false"
     * @return array
     */
    function getUserStartDate(int $employeeId, bool $includeRehireDate)
    {
        // get CI instance
        $CI = &get_instance();
        // get user dates
        $employeeDetails = $CI->db
            ->select('
            registration_date,
            joined_at,
            rehire_date
        ')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        // check the details
        if (!$employeeDetails) {
            return '';
        }
        // get the latest date
        return get_employee_latest_joined_date(
            $employeeDetails['registration_date'],
            $employeeDetails['joined_at'],
            $includeRehireDate ? $employeeDetails['rehire_date'] : '',
            false
        );
    }
}

if (!function_exists('isEmployeeOnPayroll')) {
    /**
     * Check employee on payroll
     * 
     * @param int $employeeId
     * @return int
     */
    function isEmployeeOnPayroll(int $employeeId)
    {
        // get CI instance
        $CI = &get_instance();
        // check
        return $CI->db
            ->where([
                'employee_sid' => $employeeId
            ])
            ->count_all_results('gusto_companies_employees');
    }
}

if (!function_exists('isCompanyApprovedForPayroll')) {
    /**
     * Check employee on payroll
     * 
     * @return bool
     */
    function isCompanyApprovedForPayroll(): bool
    {
        // get CI instance
        $CI = &get_instance();
        // check
        return (bool) $CI->db
            ->where([
                'company_sid' => $CI->session->userdata('logged_in')['company_detail']['sid'],
                'status' => 'approved'
            ])
            ->count_all_results('gusto_companies');
    }
}

if (!function_exists('hasPayrollDocuments')) {
    /**
     * Check employee on payroll
     * 
     * @param int $employeeId
     * @return int
     */
    function hasPayrollDocuments(int $employeeId)
    {
        // get CI instance
        $CI = &get_instance();
        // check
        return $CI->db
            ->where([
                'employee_sid' => $employeeId
            ])
            ->count_all_results('payroll_employees_forms');
    }
}

if (!function_exists('isPayrollAuthorizePerson')) {
    /**
     * Check employee on payroll
     * 
     * @param string $email
     * @return int
     */
    function isPayrollAuthorizePerson(string $email)
    {
        // get CI instance
        $CI = &get_instance();
        // check
        $has = $CI->db
            ->where([
                'email' => $email
            ])
            ->count_all_results('payroll_signatories');
        if ($has) {
            return $has;
        }
        // check
        return $CI->db
            ->where([
                'email_address' => $email
            ])
            ->count_all_results('payroll_company_admin');
    }
}

if (!function_exists('getUserColumnByWhere')) {
    /**
     * Get user data from where
     * 
     * @param array $whereArray
     * @param array $columns Optional Default is '[*]'
     * @return array
     */
    function getUserColumnByWhere(
        array $whereArray,
        array $columns = ['*']
    ) {
        // get CI instance
        $CI = &get_instance();
        // prepare query
        $CI->db->select($columns);
        $CI->db->where($whereArray);
        $CI->db->limit(1);
        // execute the query
        $result = $CI->db->get('users')->row_array();
        // send back result
        return $result;
    }
}

if (!function_exists('getEmployeeAnniversary')) {
    /**
     * Get Employee Joining data
     *
     * @param string $effectiveDate Y-m-d
     * @return array
     */
    function getEmployeeAnniversary(
        string $effectiveDate,
        string $currentDate
    ) {
        $currentYear = date('Y', strtotime($currentDate));
        // set default array
        $returnArray = [];
        // effective date
        $returnArray['ad'] = $effectiveDate;
        $returnArray['year'] = $currentYear;
        // get current date

        // get effective date for current year
        $joiningDateWithCurrentYear = preg_replace('/[0-9]{4}/', $currentYear, $effectiveDate);
        // check if month and day is in future
        if ($currentDate < $joiningDateWithCurrentYear) {
            // 
            $returnArray['lastAnniversaryDate'] = preg_replace('/[0-9]{4}/', $currentYear - 1, $effectiveDate);
            $returnArray['upcomingAnniversaryDate'] = preg_replace('/[0-9]{4}/', $currentYear, $effectiveDate);
        } else {
            //
            $returnArray['lastAnniversaryDate'] = preg_replace('/[0-9]{4}/', $currentYear, $effectiveDate);
            $returnArray['upcomingAnniversaryDate'] = preg_replace('/[0-9]{4}/', $currentYear + 1, $effectiveDate);
        }
        $newDateObj = new DateTime($returnArray["upcomingAnniversaryDate"]);
        $newDateObj->modify("-1 day");
        $returnArray['upcomingAnniversaryDate'] = $newDateObj->format("Y-m-d");
        //
        return $returnArray;
    }
}

if (!function_exists('getApiAccessToken')) {
    /**
     * Retrieve API access token
     * 
     * @param int $companyId
     * @param int $employeeId
     * 
     * @return string
     */
    function getApiAccessToken(int $companyId, int $employeeId)
    {
        // return the data
        $CI = &get_instance();
        // load the library
        $CI->load->library('Api_auth');
        // call the event
        $CI->api_auth->checkAndLogin(
            $companyId,
            $employeeId
        );
        //
        $token = $CI->db
            ->select('access_token')
            ->where([
                'company_sid' => $companyId,
                'user_sid' => $employeeId
            ])
            ->get('api_credentials')
            ->row_array();
        return $token['access_token'];
    }
}

if (!function_exists('sendI9EmailToDevs')) {
    /**
     * Send email to devs regarding I9 issue
     * 
     * @param string $subject
     * @param string $body
     */
    function sendI9EmailToDevs(string $subject, array $body)
    {
        // send email
        sendMail(FROM_STORE_NAME, DEV_TO_EMAIL, $subject, json_encode($body), 'AutomotoHR');
    }
}

if (!function_exists('portalFormI9Tracker')) {
    /**
     * Saves the I9 updates
     * 
     * @param int $userId
     * @param string $userType
     * @param array $body
     */
    function portalFormI9Tracker(int $userSid, string $userType, array $body)
    {
        //
        $dataToSave = array();
        $dataToSave['user_sid'] = $userSid;
        $dataToSave['user_type'] = $userType;
        $dataToSave['created_at'] = date('Y-m-d H:i:s');
        $dataToSave['body'] = json_encode($body);
        //
        get_instance()->db->insert('portal_form_i9_tracker', $dataToSave);
    }
}

if (!function_exists('isCompanyOnBoard')) {
    /**
     * Check company already onboard
     *
     * @param int $companyId
     * @return bool
     */
    function isCompanyOnBoard(int $companyId): bool
    {
        //
        return (bool)get_instance()->db
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('gusto_companies');
    }
}

if (!function_exists('hasAcceptedPayrollTerms')) {
    /**
     * Check company already onboard
     *
     * @param int $companyId
     * @return bool
     */
    function hasAcceptedPayrollTerms(int $companyId): bool
    {
        //
        return (bool) get_instance()->db
            ->where('is_ts_accepted is not null', null, null)
            ->where('is_ts_accepted', 1)
            ->where([
                'company_sid' => $companyId
            ])
            ->count_all_results('gusto_companies');
    }
}

if (!function_exists('isLoggedInPersonIsSignatory')) {
    /**
     * check if logged in person is signatory
     *
     * @return bool
     */
    function isLoggedInPersonIsSignatory(): bool
    {
        // Get instance
        $CI = &get_instance();
        // Get the session
        $ses = $CI->session->userdata('logged_in');
        //
        return (bool) $CI->db
            ->where([
                'company_sid' => $ses['company_detail']['sid'],
                'email' => $ses['employer_detail']['email']

            ])
            ->count_all_results('gusto_companies_signatories');
    }
}

if (!function_exists('getStaticFileVersion')) {
    /**
     * set and get the version of the minified file
     *
     * @param string $file The URI of the asset
     * @param string   $newFlow
     * @returns string
     */
    function getStaticFileVersion(
        string $file,
        string $newFlow = ''
    ) {
        // set main file path
        $filePath = ROOTPATH . "../protected_files/versions.json";
        // get the file data
        $handler = fopen($filePath, "r");
        $fileData = fread($handler, filesize($filePath));
        fclose($handler);
        $fileDataArray = json_decode($fileData, true);
        $checkFile = $file . ".min." . $newFlow;
        //
        if ($fileDataArray[$newFlow][$checkFile]) {
            return $fileDataArray[$newFlow][$checkFile];
        }
        // set files
        $files = [];
        // plugins
        $files['v1/plugins/ms_uploader/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        $files['v1/plugins/ms_modal/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        //
        $files['js/app_helper'] = ['js' => '1.0.0'];
        // Gusto
        $files['v1/payroll/js/company_onboard'] = ['js' => '1.0.0'];
        // set the main CSS file
        $files['2022/css/main'] = ['css' => '2.1.1'];
        // set the course files
        $files['js/app_helper'] = ['js' => '3.1.0'];
        $files['v1/common'] = ['js' => '3.0.0'];
        $files['v1/lms/add_question'] = ['js' => '3.0.0'];
        $files['v1/lms/edit_question'] = ['js' => '3.0.0'];
        $files['v1/lms/create_course'] = ['js' => '3.0.0'];
        $files['v1/lms/edit_course'] = ['js' => '3.0.0'];
        $files['v1/lms/main'] = ['js' => '3.0.0'];
        $files['v1/lms/assign_company_courses'] = ['js' => '3.0.0'];
        $files['v1/lms/preview_assign'] = ['js' => '3.0.0'];
        //
        $files['v1/lms/assign_employee_courses'] = ['js' => '1.0.0'];
        $files['v1/plugins/ms_scorm/main'] = ['js' => '1.0.0'];
        $files['v1/plugins/ms_scorm/adapter_12'] = ['js' => '1.0.0'];
        $files['v1/plugins/ms_scorm/adapter_2004_3'] = ['js' => '1.0.0'];
        $files['v1/plugins/ms_scorm/adapter_2004_4'] = ['js' => '1.0.0'];
        $files['v1/lms/subordinate_reporting'] = ['js' => '1.0.0'];
        $files['v1/lms/employee_course_preview'] = ['js' => '1.0.0'];
        $files['v1/lms/company_courses'] = ['js' => '1.0.0'];
        //
        // payroll files
        // dashboard
        $files['v1/payroll/js/dashboard'] = ['js' => '1.0.0'];
        // admins
        $files['v1/payroll/js/admin/add'] = ['js' => '1.0.0'];
        // signatory
        $files['v1/payroll/js/signatories/create'] = ['js' => '1.0.0'];
        // employee onboard
        $files['v1/payroll/js/employees/manage'] = ['js' => '1.0.0'];
        // contractor onboard
        $files['v1/payroll/js/contractors'] = ['js' => '1.0.0'];
        // Earning types
        $files['v1/payroll/js/earnings/manage'] = ['js' => '1.0.0'];

        // Payroll
        // signatory
        $files['public/v1/js/payroll/add-signatory'] = ['js' => '1.0.1'];
        $files['public/v1/css/payroll/add-signatory'] = ['css' => '1.0.1'];
        // regular
        $files['public/v1/js/payroll/regular/hours_and_earnings'] = ['js' => '1.0.1'];

        // new theme
        // home
        $files['public/v1/css/app/home'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/home'] = ["js" => "2.0.1"];
        // solution
        $files['public/v1/css/app/pages/products'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/products'] = ["js" => "2.0.1"];
        // why us
        $files['public/v1/css/app/pages/why-us'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/why-us'] = ["js" => "2.0.1"];
        // about us
        $files['public/v1/css/app/pages/about-us'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/about-us'] = ["js" => "2.0.1"];
        // contact us
        $files['public/v1/css/app/pages/contact-us'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/contact-us'] = ["js" => "2.0.1"];
        // get your free account
        $files['public/v1/css/app/pages/get-your-account'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/get-your-account'] = ["js" => "2.0.1"];
        // login
        $files['public/v1/css/app/login'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/login'] = ["js" => "2.0.1"];
        // forgot password
        $files['public/v1/css/app/forgot'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/forgot'] = ["js" => "2.0.1"];
        // Affiliate program
        $files['public/v1/css/app/pages/affiliate-program'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/affiliate-program'] = ["js" => "2.0.1"];
        // Privacy policy
        $files['public/v1/css/app/pages/privacy-policy'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/privacy-policy'] = ["js" => "2.0.1"];
        // Resources 
        $files['public/v1/css/app/resources'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/resources'] = ["js" => "2.0.1"];
        // Terms of service
        $files['public/v1/css/app/pages/terms-of-service'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/terms-of-service'] = ["js" => "2.0.1"];
        // Site map
        $files['public/v1/css/app/pages/sitemap'] = ["css" => "2.0.0"];
        $files['public/v1/js/app/pages/sitemap'] = ["js" => "2.0.1"];
        // check and return data
        return $newFlow ? ($files[$file][$newFlow] ?? '1.0.0') : ($files[$file] ?? []);
    }
}

if (!function_exists('copyAWSFile')) {
    /**
     * download file from AWS to local
     *
     * @param string $key  The name of the file
     * @param string $path The path of the file
     * @return string
     */
    function copyAWSFile(string $key, string $path = ROOTPATH . 'uploads/')
    {
        if (!file_exists($path)) {
            //
            mkdir($path, 0777, true);
        }
        // check the path
        if (!file_exists($path . $key)) {
            // get CI instance
            $CI = &get_instance();
            // load AWS library
            $CI->load->library('aws_lib');
            // get the object
            $CI->aws_lib->get_object(AWS_S3_BUCKET_NAME, $key, $path . $key);
        }
        //
        return $path . $key;
    }
}

if (!function_exists('getEmergencyContactsOptionsStatus')) {
    /**
     * fetch company checks for phone and email on emergency contacts
     *
     * @param int $companyId
     * @return array
     */
    function getEmergencyContactsOptionsStatus(int $companyId): array
    {
        return get_instance()->db
            ->select('emergency_contact_phone_number_status,emergency_contact_email_status')
            ->where('user_sid', $companyId)
            ->get('portal_employer')
            ->row_array();
    }
}

if (!function_exists('getDataFromTable')) {
    /**
     * fetch data from tables
     *
     * @param string $table
     * @param array  $where
     * @param array  $columns Optional
     * @param array  $method Optional
     * row_array, result_array
     * @return array
     */
    function getDataFromTable(string $table, array $where, array $columns = ['*'], string $method = 'row_array'): array
    {
        return get_instance()
            ->db
            ->select($columns)
            ->where($where)
            ->get($table)
            ->$method();
    }
}


if (!function_exists('isTranferredEmployee')) {
    /**
     * fetch data from tables
     *
     * @param int $userId
     * @return int
     */
    function isTranferredEmployee(int $userId): int
    {
        return get_instance()
            ->db
            ->where("new_employee_sid = $userId OR previous_employee_sid = $userId")
            ->count_all_results('employees_transfer_log');
    }
}

//
if (!function_exists('isDontHaveDependens')) {

    function isDontHaveDependens($companySid, $usersSid, $usersType)
    {
        // Get instance
        $CI = &get_instance();
        //
        $result = $CI->db
            ->where('company_sid', $companySid)
            ->where('users_sid', $usersSid)
            ->where('users_type', $usersType)
            ->where('have_dependents', '0')
            ->count_all_results('dependant_information');

        return $result;
    }
}

//
if (!function_exists('isDontHaveDependensDelete')) {

    function isDontHaveDependensDelete($companySid, $usersSid, $usersType)
    {
        // Get instance
        $CI = &get_instance();
        //
        $result = $CI->db
            ->where('company_sid', $companySid)
            ->where('users_sid', $usersSid)
            ->where('users_type', $usersType)
            ->where('have_dependents', '0')
            ->delete('dependant_information');

        return $result;
    }
}

//
if (!function_exists('haveDependensDelete')) {

    function haveDependensDelete($companySid, $usersSid, $usersType)
    {
        // Get instance
        return false;

        $CI = &get_instance();
        //
        $result = $CI->db
            ->where('company_sid', $companySid)
            ->where('users_sid', $usersSid)
            ->where('users_type', $usersType)
            ->where('have_dependents', '1')
            ->update('dependant_information', ['have_dependents' => 0]);

        return $result;
    }
}



//
if (!function_exists('saveApplicantOnboardingStatusLog')) {

    function saveApplicantOnboardingStatusLog($portalApplicantJobsListSid, $createdBySid, $newStatus, $oldStatus)
    {

        //
        $status = '[{"OldStatus":"' . $oldStatus . '","NewStatus":"' . $newStatus . '"}]';
        //
        $data['created_at'] = getSystemDate();
        $data['portal_applicant_jobs_list_sid'] = $portalApplicantJobsListSid;
        $data['created_by'] = $createdBySid;
        $data['status'] = $status;

        $CI = &get_instance();

        $result = $CI->db
            ->insert('applicant_onboarding_status_log', $data);

        return $result;
    }
}

if (!function_exists('getApplicantOnboardingPreviousStatus')) {

    function getApplicantOnboardingPreviousStatus($portalApplicantJobsListSid)
    {

        $CI = &get_instance();
        $oldStatus = get_instance()->db
            ->select('status')
            ->where('sid', $portalApplicantJobsListSid)
            ->get('portal_applicant_jobs_list')
            ->row_array();
        if (!empty($oldStatus)) {
            return $oldStatus['status'];
        } else {
            return '';
        }
    }
}


if (!function_exists('checkI9RecordWithProfile')) {
    /**
     * check user profile with I9
     *
     * @param int    $userId
     * @param string $userType
     * @param array  $data
     * @return array
     */
    function checkI9RecordWithProfile(int $userId, string $userType, array $data): array
    {
        //
        $CI = &get_instance();
        //
        $table = $userType === 'employee' ? 'users' : 'portal_job_applications';
        //
        $columns = [
            'first_name',
            'last_name',
            'middle_name',
            'ssn',
            'dob',
            'PhoneNumber',
            'email',
            'Location_Address',
            'Location_Address_2',
            'Location_City',
            'Location_ZipCode',
            'Location_State',
        ];
        //
        if ($userType === 'applicant') {
            //
            $columns = [
                'first_name',
                'last_name',
                'middle_name',
                'ssn',
                'dob',
                'phone_number',
                'email',
                'address',
                'city',
                'zipcode',
                'state',
            ];
        }
        //
        $profileData = $CI->db
            ->select($columns)
            ->where('sid', $userId)
            ->get($table)
            ->row_array();
        //
        if ($userType === 'applicant') {
            $profileData['PhoneNumber'] = $profileData['phone_number'];
            $profileData['Location_City'] = $profileData['city'];
            $profileData['Location_ZipCode'] = $profileData['zipcode'];
            $profileData['Location_state'] = $profileData['state'];
            $profileData['Location_Address'] = $profileData['address'];
            $profileData['Location_Address_2'] = '';
        }
        //
        $address = trim($profileData['Location_Address'] . ' ' . $profileData['Location_Address_2']);
        //
        $data['section1_last_name'] = $data['section1_last_name'] ? $data['section1_last_name'] : $profileData['last_name'];
        $data['section1_first_name'] = $data['section1_first_name'] ? $data['section1_first_name'] : $profileData['first_name'];
        $data['section1_middle_initial'] = $data['section1_middle_initial'] ? $data['section1_middle_initial'] : $profileData['middle_name'];
        $data['section1_address'] = $data['section1_address'] ? $data['section1_address'] :  $address;
        $data['section1_city_town'] = $data['section1_city_town'] ? $data['section1_city_town'] : $profileData['Location_City'];
        $data['section1_state'] = $data['section1_state'] ?? getStateColumnById($profileData['Location_State'] ?? 0);
        $data['section1_zip_code'] = $data['section1_zip_code'] ? $data['section1_zip_code'] : $profileData['Location_ZipCode'];
        $data['section1_date_of_birth'] = $data['section1_date_of_birth'] ? $data['section1_date_of_birth'] : $profileData['dob'];
        $data['section1_social_security_number'] = $data['section1_social_security_number'] ? $data['section1_social_security_number'] :  $profileData['ssn'];
        $data['section1_emp_email_address'] = $data['section1_emp_email_address'] ? $data['section1_emp_email_address'] : $profileData['email'];
        $data['section1_emp_telephone_number'] = $data['section1_emp_telephone_number'] ? $data['section1_emp_telephone_number'] : $profileData['PhoneNumber'];
        $data['section1_today_date'] = $data['section1_today_date'] ?? getSystemDate(DB_DATE);
        //
        return $data;
    }
}

if (!function_exists('getStateColumnById')) {
    /**
     * get state details
     *
     * @param int    $stateId
     * @param string $column Optional
     * @return array
     */
    function getStateColumnById(int $stateId = 0, string $column = 'state_code'): string
    {
        //
        if (!$stateId) {
            return '';
        }
        //
        $CI = &get_instance();
        //
        return $CI->db
            ->select($column)
            ->where('sid', $stateId)
            ->get('states')
            ->row_array()[$column];
    }
}

if (!function_exists('covertArrayToObject')) {
    /**
     * convert array to associate array
     *
     * @param array $data
     * @param string $index
     * @return array
     */
    function covertArrayToObject(array $data, string $index): array
    {
        // check fro empty
        if (!$data) {
            return $data;
        }
        // set temporary array
        $tmp = [];
        // loop through data
        foreach ($data as $k => $v) {
            $tmp[$v[$index]] = $v;
        }
        // return converted data
        return $tmp;
    }
}

/**
 * check the user session
 *
 * @param bool $redirect
 * @return
 */
if (!function_exists('checkUserSession')) {
    function checkUserSession(bool $redirect = true)
    {
        // get instance
        $CI = &get_instance();
        // check the session
        if (!$CI->session->userdata('logged_in')) {
            //
            if ($redirect) {
                return redirect('login', 'refresh');
            }
            //
            return false;
        }
        //
        return $CI->session->userdata('logged_in');
    }
}


if (!function_exists('makeLocation')) {
    /**
     * converts location array to string
     *
     * @param array $location
     */
    function makeLocation(array $location): string
    {
        //
        $str = '';
        //
        $str .= $location['Location_Address'];
        $str .= $location['Location_Address_2'] ? ', ' . $location['Location_Address_2'] : '';
        $str .= $location['Location_City'] ? ', ' . $location['Location_City'] : '';
        $str .= $location['state_code'] ? ', ' . $location['state_code'] : '';
        $str .= $location['Location_ZipCode'] ? ', ' . $location['Location_ZipCode'] : '';
        //
        return trim($str);
    }
}

if (!function_exists('getStateByCol')) {

    function getStateColumn(array $where, string $column): string
    {
        $result  = &get_instance()->db
            ->select($column)
            ->where($where)
            ->get('states')
            ->row_array();

        return $result && $result[$column] ?  $result[$column] : "";
    }
}

if (!function_exists('copyPrepareI9Json')) {
    /**
     * copy I9 Prepare json
     *
     * @param array  $form
     * @return array
     */
    function copyPrepareI9Json(array $form): string
    {
        $details = [];
        for ($i = 1; $i <= 4; $i++) {
            if ($i == 1) {
                $createDate = new DateTime($form['section1_preparer_today_date']);
                $today_date = $createDate->format('Y-m-d');

                $details[$i] = [
                    'signature' => $form['section1_preparer_signature'],
                    'initial' => $form['section1_preparer_signature_init'],
                    'user_agent' => $form['section1_preparer_signature_user_agent'],
                    'ip_address' => $form['section1_preparer_signature_ip_address'],
                    'last_name' => $form['section1_preparer_last_name'],
                    'first_name' => $form['section1_preparer_first_name'],
                    'middle_initial' => $form['user_agent'],
                    'address' => $form['section1_preparer_address'],
                    'city' => $form['section1_preparer_city_town'],
                    'state' => $form['section1_preparer_state'],
                    'zip_code' => $form['section1_preparer_zip_code'],
                    'today_date' => $today_date,
                ];
            } else {
                $details[$i] = [
                    'signature' => '',
                    'initial' => '',
                    'user_agent' => '',
                    'ip_address' => '',
                    'last_name' => '',
                    'first_name' => '',
                    'middle_initial' => '',
                    'address' => '',
                    'city' => '',
                    'state' => '',
                    'zip_code' => '',
                    'today_date' => '',
                ];
            }
        }
        //
        $updateArray = [];
        $updateArray['section1_preparer_json'] = json_encode($details);
        $updateArray['section1_preparer_or_translator'] = "used";

        //
        // get CI instance
        $CI = &get_instance();
        // update the user in "users" table
        $CI->db->where(['sid' => $form['sid']])->update('applicant_i9form', $updateArray);
        //
        return json_encode($details);
    }
}

if (!function_exists('copyAuthorizedI9Json')) {
    /**
     * copy I9 Authorized json
     *
     * @param array  $form
     * @return array
     */
    function copyAuthorizedI9Json(array $form): string
    {
        $details = [];
        //
        for ($i = 1; $i <= 3; $i++) {
            if ($i == 1) {
                $createDate = new DateTime($form['section3_today_date']);
                $today_date = $createDate->format('Y-m-d');
                //
                $details[$i] = [
                    //
                    'section3_rehire_date' => $form['section3_rehire_date'],
                    'section3_last_name' => $form['section3_last_name'],
                    'section3_first_name' => $form['section3_first_name'],
                    'section3_middle_initial' => $form['section3_middle_initial'],
                    'section3_document_title' => $form['section3_document_title'],
                    'section3_document_number' => $form['section3_document_number'],
                    'section3_expiration_date' => $form['section1_preparer_signature'],
                    'section3_name_of_emp' => $form['section3_expiration_date'],
                    'signature' => $form['section3_emp_sign'],
                    'section3_signature_date' => $today_date,
                    'section3_additional_information' => '',
                    'section3_alternative_procedure' => 0,
                ];
                //
            } else {
                $details[$i] = [
                    'section3_rehire_date' => '',
                    'section3_last_name' => '',
                    'section3_first_name' => '',
                    'section3_middle_initial' => '',
                    'section3_document_title' => '',
                    'section3_document_number' => '',
                    'section3_expiration_date' => '',
                    'section3_name_of_emp' => '',
                    'signature' => '',
                    'section3_signature_date' => '',
                    'section3_additional_information' => '',
                    'section3_alternative_procedure' => 0,
                ];
            }
        }
        //
        //
        $updateArray = [];
        $updateArray['section3_authorized_json'] = json_encode($details);

        //
        // get CI instance
        $CI = &get_instance();
        // update the user in "users" table
        $CI->db->where(['sid' => $form['sid']])->update('applicant_i9form', $updateArray);
        //
        return json_encode($details);
    }
}


if (!function_exists('isValidJson')) {
    /**
     * checked the valid json
     *
     * @param string  $string
     * @return bool
     */
    function isValidJson(string  $string): bool
    {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }
}

if (!function_exists('isSerializeString')) {
    /**
     * checked the string is Serialize
     *
     * @param string  $string
     * @return bool
     */
    function isSerializeString($string): bool
    {
        if (!$string) {
            return false;
        }
        //
        $data = @unserialize($string);
        //
        return is_array($data) ? true : false;
    }
}

if (!function_exists('isCompanyClosed')) {
    function isCompanyClosed(): bool
    {
        // get CI instance
        $CI = &get_instance();
        // get the session
        $session = $CI->session->userdata('logged_in')['company_detail'];
        // get session
        return (bool) $CI
            ->db
            ->where('sid', $session['sid'])
            ->where('company_status', 0)
            ->count_all_results('users');
    }
}

if (!function_exists('getEmployeeDepartmentAndTeams')) {
    /**
     * get employee teams and departments
     *
     * @param int $employeeId
     */
    function getEmployeeDepartmentAndTeams(int $employeeId): array
    {
        // set default
        $r = [
            'departments' => [],
            'teams' => []
        ];
        // get the data
        $records = get_instance()->db
            ->select("
                departments_management.sid as department_sid,
                departments_management.name as department_name,
                departments_team_management.sid,
                departments_team_management.name
            ")
            ->join(
                "departments_team_management",
                "departments_team_management.sid = departments_employee_2_team.team_sid",
                "inner"
            )
            ->join(
                "departments_management",
                "departments_management.sid = departments_team_management.department_sid",
                "inner"
            )
            ->where("departments_management.is_deleted", 0)
            ->where("departments_team_management.is_deleted", 0)
            ->where("departments_employee_2_team.employee_sid", $employeeId)
            ->get("departments_employee_2_team")
            ->result_array();
        //
        if (!$records) {
            return $r;
        }
        //
        foreach ($records as $record) {
            // set department
            $r['departments'][$record['department_sid']] = [
                'sid' => $record['department_sid'],
                'name' => $record['department_name'],
            ];
            // set teams
            $r['teams'][$record['sid']] = [
                'department_sid' => $record['department_sid'],
                'department_name' => $record['department_name'],
                'sid' => $record['sid'],
                'name' => $record['name'],
            ];
        }
        //
        $r['teams'] = array_values($r['teams']);
        //
        return $r;
    }
}

if (!function_exists('getMyDepartmentAndTeams')) {
    /**
     * get employee teams and departments
     *
     * @param int $employeeId
     * @param string $flag
     * @param string $method
     */
    function getMyDepartmentAndTeams(int $employeeId, string $flag = "", string $method = "get"): array
    {
        // set default
        $r = [
            'departments' => [],
            'teams' => [],
            'employees' => []
        ];
        //
        $CI = &get_instance();
        //
        $CI->db->select("
            departments_team_management.sid as team_sid, 
            departments_team_management.name as team_name,
            departments_management.sid,
            departments_management.name,
            departments_management.supervisor
        ")
            ->join(
                "departments_management",
                "departments_management.sid = departments_team_management.department_sid",
                "inner"
            )
            ->where("departments_management.is_deleted", 0)
            ->where("departments_team_management.is_deleted", 0)
            ->group_start()
            ->where("FIND_IN_SET({$employeeId}, departments_team_management.team_lead) > 0", null, null)
            ->or_where("FIND_IN_SET({$employeeId}, departments_management.supervisor) > 0", null, null)
            ->group_end();
        //
        if ($method == "count_all_results") {
            $CI->db->limit(1);
        }
        //
        $departmentAndTeams = $CI->db->$method('departments_team_management');
        //
        if ($method == "count_all_results") {
            return $departmentAndTeams  ? [1] : [];
        }
        //
        $departmentAndTeams = $departmentAndTeams->result_array();
        //
        if (!empty($departmentAndTeams)) {
            //
            foreach ($departmentAndTeams as $team) {
                $r['teams'][$team["team_sid"]] = array(
                    "department_sid" => $team["sid"],
                    "department_name" => $team["name"],
                    "sid" => $team["team_sid"],
                    "name" => $team["team_name"],
                    "employees_ids" => []
                );
                //
                if (in_array($employeeId, explode(",", $team["supervisor"]))) {
                    $r['departments'][$team["sid"]] = array(
                        "sid" => $team["sid"],
                        "name" =>  $team["name"],
                        "employees_ids" => []
                    );
                }
            }
            //
            $teamSids = array_column($departmentAndTeams, "team_sid");
            //
            $TeamEmployees = $CI->db->select("
                            department_sid, 
                            team_sid, 
                            employee_sid
                        ")
                ->where_in('team_sid', $teamSids)
                ->get('departments_employee_2_team')
                ->result_array();
            //
            $alreadyExist = [];
            $employees = [];
            //
            foreach ($TeamEmployees as $employee) {
                //
                if (!in_array($employee['employee_sid'], $alreadyExist)) {
                    array_push($alreadyExist, $employee['employee_sid']);
                    $jobTitleInfo = $CI->db->select("
                        users.job_title,
                        users.first_name,
                        users.last_name,
                        users.access_level,
                        users.timezone,
                        users.access_level_plus,
                        users.is_executive_admin,
                        users.pay_plan_flag,
                        portal_job_title_templates.sid as job_title_sid
                    ")
                        ->join(
                            "portal_job_title_templates",
                            "portal_job_title_templates.title = users.job_title",
                            "left"
                        )
                        ->where('users.sid', $employee['employee_sid'])
                        ->get('users')
                        ->row_array();
                    //
                    $jobTitleId = !empty($jobTitleInfo['job_title_sid']) ? $jobTitleInfo['job_title_sid'] : 0;
                    //
                    if ($jobTitleId == 0) {
                        if (!empty($jobTitleInfo["job_title"])) {
                            $jobTitleId = -1;
                        }
                    }
                    //  
                    $employeeName = remakeEmployeeName([
                        'first_name' => $jobTitleInfo['first_name'],
                        'last_name' => $jobTitleInfo['last_name'],
                        'access_level' => $jobTitleInfo['access_level'],
                        'timezone' => isset($jobTitleInfo['timezone']) ? $jobTitleInfo['timezone'] : '',
                        'access_level_plus' => $jobTitleInfo['access_level_plus'],
                        'is_executive_admin' => $jobTitleInfo['is_executive_admin'],
                        'pay_plan_flag' => $jobTitleInfo['pay_plan_flag'],
                        'job_title' => $jobTitleInfo['job_title'],
                    ]);
                    //
                    $employees[$employee['employee_sid']]["job_title_sid"] =  !empty($jobTitleInfo['job_title_sid']) ? $jobTitleInfo['job_title_sid'] : 0;
                    $employees[$employee['employee_sid']]["full_name"] = $employeeName;
                    $employees[$employee['employee_sid']]["employee_sid"] = $employee['employee_sid'];
                    $employees[$employee['employee_sid']]["department_sid"] = $employee['department_sid'];
                    $employees[$employee['employee_sid']]["team_sid"] = $employee['team_sid'];
                    //
                    $employeeData = [];
                    $employeeData["employee_sid"] = $employee['employee_sid'];
                    $employeeData["job_title_sid"] =  $jobTitleId;
                    $employeeData["employee_name"] =  $employeeName;
                    //
                    if ($flag == 'courses') {
                        if ($jobTitleId != 0) {
                            $today = date('Y-m-d');
                            //
                            $companyId = getEmployeeUserParent_sid($employee['employee_sid']);
                            //
                            $companyCourses = $CI->db->select("
                                    lms_default_courses.sid
                                ")
                                ->join(
                                    "lms_default_courses_job_titles",
                                    "lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid",
                                    "right"
                                )
                                ->where('lms_default_courses.company_sid', $companyId)
                                ->where('lms_default_courses.is_active', 1)
                                ->where('course_start_period <=', $today)
                                ->group_start()
                                ->where('lms_default_courses_job_titles.job_title_id', -1)
                                ->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId)
                                ->group_end()
                                ->get('lms_default_courses')
                                ->result_array();
                            //
                            $assignCourses = !empty($companyCourses) ? implode(',', array_column($companyCourses, "sid")) : "";
                            //
                            $employees[$employee['employee_sid']]["assign_courses"] = $assignCourses;
                            $employeeData["assign_courses"] =  $assignCourses;
                        } else {
                            $employees[$employee['employee_sid']]["assign_courses"] = "";
                            $employeeData["assign_courses"] =  "";
                        }
                    }

                    //
                    if (isset($r['departments'][$employee['department_sid']])) {
                        $r['departments'][$employee['department_sid']]['employees_ids'][] = $employeeData;
                    }
                    //
                    if (isset($r['teams'][$employee['team_sid']])) {
                        $r['teams'][$employee['team_sid']]['employees_ids'][] = $employeeData;
                    }
                    //
                }
            }
            //
            $r['employees'] = $employees;
        }
        //
        return $r;
    }
}

if (!function_exists('prefillFormData')) {
    /**
     * Prefill he form data
     * 
     * @param int $userId,
     * @param string $userType,
     * @param string $formType,
     * @param array $form
     * @return array
     */
    function prefillFormData(
        int $userId,
        string $userType,
        string $formType,
        array $form
    ): array {
        // set table
        $table = $userType === 'applicant' ?  'portal_job_applications' : 'users';
        // set columns
        $columns = [
            'first_name',
            'last_name',
            'middle_name',
            'ssn',
            'Location_Address',
            'Location_Address_2',
            'Location_City',
            'Location_State',
            'Location_ZipCode'
        ];
        //
        if ($userType === 'applicant') {
            // set columns
            $columns = [
                'first_name',
                'last_name',
                'middle_name',
                'ssn',
                'address',
                'city',
                'state',
                'zipcode'
            ];
        }
        // get the user details
        $userInfo = get_instance()
            ->db
            ->select($columns)
            ->where('sid', $userId)
            ->get($table)
            ->row_array();
        //
        if (!$userInfo) {
            return $form;
        }
        //
        if ($userType === 'applicant') {
            $userInfo['Location_Address'] = $userInfo['address'];
            $userInfo['Location_Address_2'] = '';
            $userInfo['Location_City'] = $userInfo['city'];
            $userInfo['Location_State'] = $userInfo['state'];
            $userInfo['Location_ZipCode'] = $userInfo['zipcode'];
        } else {
            $userInfo['Location_State'] = $userInfo['Location_State'] ? getStateColumnById(
                $userInfo['Location_State'],
                'state_name'
            ) : '';
        }
        //
        $method = 'prefill' . (ucwords($formType)) . 'Form';
        //
        return $method($userInfo, $form);
    }
}

if (!function_exists('prefillW4Form')) {
    /**
     * Prefill W4 form data
     * 
     * @param array $userInfo
     * @param array $form
     * @return array
     */
    function prefillW4Form(
        array $userInfo,
        array $form
    ): array {

        //
        if (!$form['first_name']) {
            $form['first_name'] = $userInfo['first_name'];
        }
        //
        if (!$form['middle_name']) {
            $form['middle_name'] = $userInfo['middle_name'];
        }
        //
        if (!$form['last_name']) {
            $form['last_name'] = $userInfo['last_name'];
        }
        //
        if (!$form['ss_number']) {
            $form['ss_number'] = $userInfo['ssn'];
        }
        //
        if (!$form['home_address']) {
            $form['home_address'] = trim($userInfo['Location_Address'] . ' ' . $userInfo['Location_Address_2']);
        }
        //
        if (!$form['city']) {
            $form['city'] = $userInfo['Location_City'];
        }
        //
        if (!$form['state']) {
            $form['state'] = $userInfo['Location_State'];
        }
        //
        if (!$form['zip']) {
            $form['zip'] = $userInfo['Location_ZipCode'];
        }
        //
        return $form;
    }
}

if (!function_exists('getFormErrors')) {
    /**
     * get the form errors
     *
     * @method validate_errors
     * @return array
     */
    function getFormErrors(): array
    {
        //
        $errors = explode("\n", validation_errors(' ', ' '));
        //
        unset($errors[count($errors) - 1]);
        //
        return [
            'errors' => array_map(
                function ($error) {
                    return trim($error);
                },
                $errors
            )
        ];
    }
}

if (!function_exists('loadUpModel')) {
    /**
     * loads up a model
     *
     * @method get_instance
     * @param string $modelPath
     * @param string $name
     * @return object
     */
    function loadUpModel(string $modelPath, string $name): object
    {
        // load the model
        return get_instance()->load->model($modelPath, $name);
    }
}

if (!function_exists('getDueDate')) {
    /**
     * get the due date from a date
     *
     * @param string $date
     * @return string
     */
    function getDueDate(string $date): string
    {
        //
        $dateTimeObj = new DateTime($date);
        $currentDateObj = new DateTime();
        //
        $diff = $currentDateObj->diff($dateTimeObj);

        //
        return $diff->format(($diff->invert ? '-' : '') . "%d days");
    }
}

if (!function_exists('_a')) {
    /**
     * amount formatter
     *
     * @param int $amount
     * @param string $symbol Optional
     * @return string
     */
    function _a(int $amount, string $symbol = '$'): string
    {
        return $symbol . number_format($amount, 2);
    }
}

if (!function_exists('getRatePerHour')) {
    /**
     * get employee rate per hour
     *
     * @param int $rate
     * @param string $paymentUnit
     * @returns
     */
    function getRatePerHour(int $rate, string $paymentUnit)
    {
        //
        $newRate = $rate;
        //
        $paymentUnit = strtolower($paymentUnit);
        //
        if ($paymentUnit == "year") {
            $newRate = $rate / 52 / 40;
        } elseif ($paymentUnit == "month") {
            $newRate = ($rate * 12) / 52 / 40;
        } elseif ($paymentUnit == "week") {
            $newRate = $rate / 40;
        }
        //
        return $newRate;
    }
}

if (!function_exists('splitPathAndFileName')) {
    /**
     * splits file name and path
     *
     * @param string $file
     * @return array
     */
    function splitPathAndFileName(string $file): array
    {
        //
        $returnArray = [
            'path' => '',
            'name' => '',
            'orig_name' => $file,
            'ext' => '',
            'mime' => ''
        ];
        //
        $splits = explode('/', $file);
        //
        $index = count($splits) - 1;
        //
        $returnArray['name'] = $splits[$index];
        //
        unset($splits[$index]);
        // for extension
        $returnArray['path'] = implode('/', $splits);
        //
        $splits = explode('.', $returnArray['name']);
        //
        $index = count($splits) - 1;
        //
        $returnArray['ext'] = $splits[$index];
        $returnArray['mime'] = getMimeType($returnArray['ext']);
        //
        return $returnArray;
    }
}

if (!function_exists('getAWSSecureFile')) {
    /**
     * get the secure AWS path
     *
     * @param string $file
     * @return object
     */
    function getAWSSecureFile(string $file): object
    {
        // get CI instance
        $CI = &get_instance();
        //
        $bucket = AWS_S3_BUCKET_NAME;
        //
        if (in_array($_SERVER['HTTP_HOST'], ['localhost', 'automotohr.local'])) {
            $bucket = str_replace('https', 'http', $bucket);
        }
        $parsedFile = splitPathAndFileName($file);
        // prepaere config
        $config = [];
        $config['Bucket'] = $bucket;
        $config['Key'] = $file;
        // secure params
        $config['ResponseContentLanguage'] = 'en-US';
        $config['ResponseContentType'] = $parsedFile['mime'];
        $config['ResponseContentDisposition'] = 'attachment; filename="' . ($parsedFile['name']) . '"';
        $config['ResponseCacheControl'] = 'No-cache';
        $config['ResponseExpires'] = gmdate(DATE_RFC2822, time() + 3600); // 1 hour
        // Load AWS library
        $CI->load->library('aws_lib');
        return $CI->aws_lib->get_secure_object($config);
    }
}

if (!function_exists("getPortalData")) {
    /**
     * get portal data
     *
     * @param int $companyId
     * @param array $fields Optional
     * @return array
     */
    function getPortalData(int $companyId, array $fields = ["*"]): array
    {
        return get_instance()
            ->db->select($fields)
            ->where("user_sid", $companyId)
            ->get("portal_employer")
            ->row_array();
    }
}

if (!function_exists('findCompanyUser')) {
    function findCompanyUser($email, $company_sid)
    {
        $result = [
            'userType' => '',
            'profilePath' => '',
            'userName' => ''
        ];
        //
        $CI = &get_instance();
        $CI->db->select('sid, first_name, last_name');
        $CI->db->where('parent_sid', $company_sid);
        $CI->db->where('email', $email);
        $record_row = $CI->db->get('users')->row_array();

        if (!empty($record_row)) {
            $result['profilePath'] = base_url('employee_profile') . '/' . $record_row['sid'];
            $result['userType'] = "employee";
            $result['userName'] = $record_row['first_name'] . ' ' . $record_row['last_name'];
        } else {
            $CI->db->select('sid, first_name, last_name, email');
            $CI->db->where('email', $email);
            $CI->db->where('employer_sid', $company_sid);
            $record_obj = $CI->db->get('portal_job_applications');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $result['userName'] = $record_arr['first_name'] . ' ' . $record_arr['last_name'];
                $portal_job_applications_sid = $record_arr['sid'];

                $CI->db->select('sid');
                $CI->db->order_by('sid', 'desc');
                $CI->db->limit(1);
                $CI->db->where('portal_job_applications_sid', $portal_job_applications_sid);
                $obj = $CI->db->get('portal_applicant_jobs_list');
                $result_arr = $obj->row_array();
                $obj->free_result();

                if (!empty($result_arr)) {
                    $result['profilePath'] = base_url('applicant_profile') . '/' . $portal_job_applications_sid . '/' . $result_arr['sid'];
                    $result['userType'] = 'applicant';
                }
            }
        }

        return $result;
    }
}

//

if (!function_exists('acceptGustoAgreement')) {

    function acceptGustoAgreement($name)
    {
        if ($name != '' && $name != null) {
        }

        return false;
    }
}

if (!function_exists('getPageContent')) {

    function getPageContent($page, $slug = false)
    {
        //
        $CI = &get_instance();
        $CI->db
            ->select('content');
        if ($slug == true) {
            $CI->db->where('slug', $page);
        } else {
            $CI->db->where('page', $page);
        }
        $CI->db->where('status', 1);
        $pageContent =   $CI->db->get('cms_pages_new')->row_array();
        return json_decode($pageContent['content'], true);
    }
}

if (!function_exists('getPageNameBySlug')) {

    function getPageNameBySlug($slug)
    {
        //
        $CI = &get_instance();
        $page =  $CI->db->select('page')
            ->where('slug', $slug)
            ->get('cms_pages_new')
            ->row_array();
        return $page['page'];
    }
}


if (!function_exists("getCommonFiles")) {
    /**
     * check wether image has an error or not
     *
     * @param string $type
     * @return array
     */
    function getCommonFiles(string $type = "css"): array
    {
        // set css defaults
        $arr["css"] = [
            "v1/plugins/daterangepicker/css/daterangepicker.min",
            "v1/plugins/alertifyjs/css/alertify.min",
        ];
        // set js defaults
        $arr["js"] = [
            "v1/plugins/daterangepicker/daterangepicker.min",
            "v1/plugins/alertifyjs/alertify.min",
        ];
        //
        return $arr[$type];
    }
}

if (!function_exists("validateCaptcha")) {
    /**
     * validate google captcha
     */
    function validateCaptcha(string $str)
    {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = getCreds("AHR")->GOOGLE_CAPTCHA_API_SECRET_V2;
        $url = $google_url . "?secret=" . $secret . "&response=" . $str;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        return $res['success'];
    }
}


if (!function_exists("convertToStrip")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertToStrip(string $str): string
    {
        return preg_replace("/##(.*?)##/i", '<strong class="text-yellow">$1</strong>', $str);
    }
}



if (!function_exists("convertToHilited")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertToHilited(string $str): string
    {
        return preg_replace("/##(.*?)##/i", '<span class="highlighted-light-blue-div">$1</span>', $str);
    }
}

if (!function_exists("makeResourceView")) {
    function makeResourceView($file)
    {
        // get the file extension
        $extension =
            pathinfo($file, PATHINFO_EXTENSION);
        // for video
        if (in_array($extension, ['mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov'])) {
            return ' <video style="width: 100%"  src="' . (AWS_S3_BUCKET_URL . $file) . '" controls="true" class="resources-video-detail" alt="smiling girl"> </video>';
        } elseif (in_array($extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
            return '<img src="' . (AWS_S3_BUCKET_URL . $file) . '" class="resources-card-images-adjustment-detail" alt="tablet with tea">';
        } else {
            return '<iframe src="' . (AWS_S3_BUCKET_URL . $file) . '" width="100%" height="500px"></iframe> ';
        }
    }
}


if (!function_exists("load404")) {
    function load404()
    {
        get_instance()->load
            ->view("v1/app/header", [
                "meta_title" => "404 | AutomotoHr.com"
            ])
            ->view("v1/app/footer");
    }
}


if (!function_exists("convertEnterToSpan")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertEnterToSpan(string $str): string
    {

        return preg_replace("/[\n\r]/", '<span class="d-md-block">$1</span>', $str);
    }
}


if (!function_exists("onlyPlusAndPayPlanCanAccess")) {
    function onlyPlusAndPayPlanCanAccess()
    {
        if (!isPayrollOrPlus()) {
            get_instance()->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
    }
}


if (!function_exists('updateEmployeeDepartmentToComplyNet')) {

    function updateEmployeeDepartmentToComplyNet(int $employeeId, int $companyId)
    {
        //
        $CI = &get_instance();
        //
        $CI->load->library('Complynet/Complynet_lib', '', 'clib');
        $CI->load->model('2022/complynet_model');
        // fetch the old department
        $employeeOldDepartmentId = $CI->complynet_model->getEmployeeOldComplyNetDepartment($employeeId);
        //
        if ($employeeOldDepartmentId == '') {
            return false;
        }

        // get name aur id
        $employeeNewDepartment = $CI->complynet_model->getDepartmentName($employeeId);
        //
        if (!$employeeNewDepartment) {
            return false;
        }

        // get new department id
        $employeeNewDepartmentId = $CI->complynet_model->getEmployeeDepartmentId($employeeId);

        // when both ids are equal
        if ($employeeNewDepartmentId == $employeeOldDepartmentId) {
            return false;
        }

        // Create New Department On Comply Net
        if (empty($employeeNewDepartmentId) || $employeeNewDepartmentId === 0) {
            // $employeeNewDepartment['name']='New Department Test';
            $employeeNewDepartmentId = $CI->complynet_model->checkAndCreateDepartmentOnComplyNet(
                $employeeNewDepartment['sid'],
                $employeeNewDepartment['name'],
                $companyId
            );
        }

        //
        if ($employeeNewDepartmentId == '') {
            return false;
        }
        //
        $employee = $CI->complynet_model->getemployeeComplyNetJobTitle($employeeId);
        //
        $complyJobRoleId = $CI->complynet_model->getAndSetJobRoleId(
            $employeeNewDepartmentId,
            $employee['complynet_job_title']
        );
        //
        if ($complyJobRoleId === 0) {
            return false;
        }
        //
        if (empty($complyJobRoleId)) {
            return false;
        }

        //
        return $CI->complynet_model->updateEmployeeJobTitleOnComplyNet(
            $employeeId,
            $employeeNewDepartmentId,
            $complyJobRoleId
        );
    }
}

//
if (!function_exists('updateEmployeeJobRoleToComplyNet')) {

    function updateEmployeeJobRoleToComplyNet(int $employeeId, int $companyId)
    {
        //
        $CI = &get_instance();
        //
        $CI->load->library('Complynet/Complynet_lib', '', 'clib');
        $CI->load->model('2022/complynet_model');

        //
        $employeeDepartmentId = $CI->complynet_model->getEmployeeOldComplyNetDepartment($employeeId);
        //
        if ($employeeDepartmentId == '') {
            return false;
        }
        // Get company job roles
        $employeeOldJobRoleId = $CI->complynet_model->getEmployeeOldComplyNetJobRole($employeeId);
        //
        if ($employeeOldJobRoleId == '') {
            return false;
        }

        //
        $employeeComplyNetJobTitle = $CI->complynet_model->getComplyNetJobTitle($employeeId);

        if ($employeeComplyNetJobTitle['complynet_job_title'] == '') {
            return false;
        }

        $employeeNewJobRoleId = $CI->complynet_model->getEmployeeNewComplyNetJobRole($employeeDepartmentId, $employeeComplyNetJobTitle['complynet_job_title']);

        //
        if ($employeeNewJobRoleId == $employeeOldJobRoleId) {
            return false;
        }

        if ($employeeNewJobRoleId != '') {
            // Just Bind
            $updateData['complynet_department_sid'] = $employeeDepartmentId;
            $updateData['complynet_job_role_sid'] = $employeeNewJobRoleId;
            $CI->complynet_model->updateComplyNetEmployeeJobTitle(
                $employeeId,
                $companyId,
                $updateData
            );
            //
            return false;
        }

        // Create New Job Role on Comply Net
        $complyJobRoleId = $CI->complynet_model->getAndSetComplyNetJobRoleId(
            $employeeDepartmentId,
            $employeeComplyNetJobTitle['complynet_job_title']
        );

        //
        if ($complyJobRoleId === 0) {
            return false;
        }
        //
        if (empty($complyJobRoleId)) {
            return false;
        }

        //
        return $CI->complynet_model->updateEmployeeJobTitleOnComplyNet(
            $employeeId,
            $employeeDepartmentId,
            $complyJobRoleId
        );
    }
}

if (!function_exists('image_url')) {
    function image_url($path)
    {
        $imagePath = base_url('assets/images/' . $path);

        return $imagePath;
    }
}

if (!function_exists("getFile")) {
    /**
     * get the plugin
     *
     * @param string $index
     * @param string $type
     * @return string
     */
    function getPlugin(string $index, string $type): string
    {
        // set plugins array
        $plugins = [];
        // set alertify plugin
        $plugins["alertify"] = [
            "css" =>
            main_url("public/v1/plugins/alertifyjs/css/alertify.min.css?v=3.0"),
            "js" =>   main_url("public/v1/plugins/alertifyjs/alertify.min.js?v=3.0")
        ];
        // set alertify plugin
        $plugins["validator"] = [
            "js" =>  main_url("public/v1/plugins/validator/jquery.validate.min.js?v=3.0")
        ];
        $plugins["additionalMethods"] = [
            "js" =>  main_url("public/v1/plugins/validator/additional-methods.min.js?v=3.0")
        ];

        // set date range picker plugin
        $plugins["daterangepicker"] = [
            "css" => main_url("public/v1/plugins/daterangepicker/css/daterangepicker.min.css?v=3.0"),
            "js" =>  main_url("public/v1/plugins/daterangepicker/daterangepicker.min.js?v=3.0")
        ];

        // set time picker
        $plugins["timepicker"] = [
            "css" => main_url("public/v1/plugins/timepicker/css/jquery.timepicker.min.css?v=3.0"),
            "js" =>  main_url("public/v1/plugins/timepicker/jquery.timepicker.min.js?v=3.0")
        ];
        // set google map
        $plugins["google_map"] = [
            "js" =>  main_url("public/v1/plugins/google_map/main.min.js?v=1.0")
        ];

        // set select2
        $plugins["select2"] = [
            "css" =>
            main_url("public/v1/plugins/select2/css/select2.min.css?v=3.0"),
            "js" =>   main_url("public/v1/plugins/select2/select2.min.js?v=3.0")
        ];
        //
        return $plugins[$index][$type] ?? "";
    }
}


if (!function_exists("makeAddress")) {
    /**
     * makes address
     *
     * @param array $address
     * @return string
     */
    function makeAddress(array $address): string
    {
        //
        $str = $address["street_1"];
        if ($address["street_2"]) {
            $str .= ", " . $address["street_2"];
        }
        $str .= ", " . $address["city"];
        $str .= ", " . $address["state_code"];
        $str .= ", " . $address["zip_code"];
        //
        return $str;
    }
}


if (!function_exists("getMonthDatesByYearAndMonth")) {
    /**
     * get the dates array of a year and month
     * @param int $year
     * @param int $month
     * @param string $format
     * @return array
     */
    function getMonthDatesByYearAndMonth(int $year, int $month, string $format = "D d"): array
    {
        // Create a DateTime object for the first day of the month
        $firstDay = new DateTime("$year-$month-01");
        // Get the number of days in the month
        $lastDay = new DateTime($firstDay->format('Y-m-t'));
        // Initialize an array to store weeks and their corresponding dates
        $dates = [];
        // Loop through the days of the month
        while ($firstDay <= $lastDay) {
            $dates[] = $firstDay->format($format);
            // Move to the next day
            $firstDay->modify('+1 day');
        }
        // return dates array
        return $dates;
    }
}


if (!function_exists("getWeekDates")) {
    /**
     * get current week or two week dates
     *
     * @param bool $nextTwoWeeks Optional
     * @param bool $format Optional
     * @return
     */
    function getWeekDates(bool $nextTwoWeeks = false, string $format = DB_DATE): array
    {
        // Get the current date
        $today = new DateTime();

        // Set the time to the beginning of the day
        $today->setTime(0, 0, 0);

        // Get the current day of the week (0 = Sunday, 1 = Monday, ..., 6 = Saturday)
        $currentDayOfWeek = $today->format('w');

        // Calculate the difference between the current day of the week and Monday (1)
        $daysUntilMonday = ($currentDayOfWeek + 6) % 7;

        // Calculate the start date of the current week (Monday)
        $startDate = clone $today;
        $startDate->sub(new DateInterval('P' . $daysUntilMonday . 'D'));

        // Calculate the end date of the current week (Sunday)
        $endDate = clone $startDate;
        $endDate->add(new DateInterval('P6D'));

        // Calculate the start date of the next week (Monday)
        $nextWeekStartDate = clone $startDate;
        $nextWeekStartDate->add(new DateInterval('P7D'));

        // Calculate the end date of the next week (Sunday)
        $nextWeekEndDate = clone $nextWeekStartDate;
        $nextWeekEndDate->add(new DateInterval('P6D'));

        if ($nextTwoWeeks) {
            return [
                'current_week' => [
                    'start_date' => $startDate->format($format),
                    'end_date' => $endDate->format($format),
                ],
                'next_week' => [
                    'start_date' => $nextWeekStartDate->format($format),
                    'end_date' => $nextWeekEndDate->format($format),
                ],
            ];
        } else {
            return [
                'start_date' => $startDate->format($format),
                'end_date' => $endDate->format($format),
            ];
        }
    }
}


if (!function_exists("getDatesInRange")) {
    /**
     * get dates range
     *
     * @param string $startDate
     * @param string $endDate
     * @param string $format Optional
     * @return array
     */
    function getDatesInRange(string $startDate, string $endDate, string $format = DB_DATE): array
    {
        $dates = [];
        $currentDate = new DateTime($startDate);

        while ($currentDate <= new DateTime($endDate)) {
            $dates[] = $currentDate->format($format);
            $currentDate->add(new DateInterval('P1D'));
        }

        return $dates;
    }
}


if (!function_exists("getTimeBetweenTwoDates")) {
    function getTimeBetweenTwoDates(string $date1, string $date2): string
    {
        //
        $date1 = new DateTime($date1);
        $date2 = new DateTime($date2);

        // Calculate the time difference
        return $date2->getTimestamp() - $date1->getTimestamp();
    }
}

if (!function_exists("convertSecondsToTime")) {
    function convertSecondsToTime(string $differenceInSeconds): string
    {
        // Convert seconds to hours and minutes
        $hours = floor($differenceInSeconds / 3600);
        $minutes = floor(($differenceInSeconds % 3600) / 60);

        if ($hours <= 0 && $minutes <= 0) {
            return "0h";
        }

        return ($hours > 0 ? $hours . "h" : "") . ($minutes > 0 ? " " . $minutes . 'm' : "");
    }
}

if (!function_exists("getSundaysAndSaturdays")) {
    function getSundaysAndSaturdays($startDate, $endDate)
    {
        $sundaysSaturdays = [];

        // Create DateTime objects from the input strings
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        // Iterate through the days
        $currentDate = $startDateTime;
        while ($currentDate <= $endDateTime) {
            // Check if the current day is Sunday or Saturday
            $dayOfWeek = $currentDate->format('N');
            if ($dayOfWeek == 7 /* Sunday */ || $dayOfWeek == 6 /* Saturday */) {
                $sundaysSaturdays[] = $currentDate->format('Y-m-d');
            }

            // Move to the next day
            $currentDate->modify('+1 day');
        }

        return $sundaysSaturdays;
    }
}

if (!function_exists("getCompanyOffDaysDatesWithinRange")) {
    function getCompanyOffDaysDatesWithinRange($startDate, $endDate, $offDays)
    {
        $sundaysSaturdays = [];

        // Create DateTime objects from the input strings
        $startDateTime = new DateTime($startDate);
        $endDateTime = new DateTime($endDate);

        // Iterate through the days
        $currentDate = $startDateTime;
        while ($currentDate <= $endDateTime) {
            // Check if the current day is Sunday or Saturday
            $dayOfWeek = $currentDate->format('N');
            if (in_array($dayOfWeek, $offDays)) {
                $sundaysSaturdays[] = $currentDate->format('Y-m-d');
            }

            // Move to the next day
            $currentDate->modify('+1 day');
        }

        return $sundaysSaturdays;
    }
}

if (!function_exists("haversine")) {
    function haversine($lat1, $lon1, $lat2, $lon2)
    {
        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Radius of the Earth in kilometers (you can change this value as needed)
        $earth_radius = 6371;

        // Calculate the distance
        $distance = $earth_radius * $c;

        return $distance;
    }
}

if (!function_exists("haversine")) {

    function isWithinRadius($lat1, $lon1, $lat2, $lon2, $radius)
    {
        $distance = haversine($lat1, $lon1, $lat2, $lon2);

        // Check if the distance is within the specified radius
        return $distance <= $radius;
    }
}

if (!function_exists("convertPayScheduleToText")) {
    /**
     * convert pay schedule to text
     *
     * @param array $schedule
     * @return string
     */
    function convertPayScheduleToText(array $schedule): string
    {
        $str = "";

        // add the name if any
        if ($schedule["custom_name"]) {
            $str .= $schedule["custom_name"] . " - ";
        }
        // add frequency
        $str .= $schedule["frequency"] . " - ";
        //
        if ($schedule["day_1"] && $schedule["day_2"]) {
            $str .= "On every " . ($schedule["day_1"]) . " and " . ($schedule["day_2"]) . " of the month.";
        } elseif ($schedule["day_1"]) {
            $str .= "On every " . ($schedule["day_1"]) . " of the month.";
        }

        $str = rtrim($str, "- ");

        return $str;
    }
}

if (!function_exists("getCompanyDetailsForGusto")) {
    /**
     * Get gusto company details for gusto
     *
     * @param int   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */

    function getCompanyDetailsForGusto(int $companyId, array $extra = [], bool $include = true): array
    {
        // get CI instance
        $CI = &get_instance();
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'refresh_token',
            'access_token'
        ], $extra) : $extra;
        //
        return $CI->db
            ->select($columns)
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
    }
}

//
if (!function_exists("getDataForEmployerPrefill")) {
    function getDataForEmployerPrefill($companySid, $userId = 0, $userType = "employee")
    {
        $CI = &get_instance();
        $companyData = $CI->db
            ->select('
                users.CompanyName,
                users.Location_Address,
                users.Location_City,
                users.Location_ZipCode,
                users.Location_State,
                users.ssn,
                states.state_code,
                users.Location_Address_2,
                users.extra_info
            ')
            ->join(
                "states",
                "states.sid = users.Location_State",
                "left"
            )
            ->where('users.sid', $companySid)
            ->where('users.parent_sid', 0)
            ->get('users')
            ->row_array();

        $address = "";
        // add street 1
        $address .= $companyData["Location_Address"] ? $companyData["Location_Address"] . ', ' : '';
        $address .= $companyData["Location_Address_2"] ? $companyData["Location_Address_2"] . ', ' : '';
        $address .= $companyData["Location_City"] ? $companyData["Location_City"] . ', ' : '';
        $address .= $companyData["state_code"] ? $companyData["state_code"] . ', ' : '';
        $address .= $companyData["Location_ZipCode"] ? $companyData["Location_ZipCode"] . ', ' : '';

        $companyData["companyAddress"] = rtrim(
            $address,
            ", "
        );
        $extraInfo = unserialize(
            $companyData["extra_info"]
        );
        $companyData["mtin"] = $extraInfo["mtin"] ?? "";
        //
        if ($userId != 0) {
            //
            if ($userType == "employee") {
                $employeeData = $CI->db->select('registration_date,joined_at,rehire_date')
                    ->where('sid', $userId)
                    ->where('parent_sid', $companySid)
                    ->get('users')
                    ->row_array();
                $joiningDate = get_employee_latest_joined_date($employeeData["registration_date"], $employeeData["joined_at"], $employeeData["rehire_date"], false);
                if ($joiningDate) {
                    $companyData['first_day_of_employment'] = formatDateToDB(
                        $joiningDate,
                        DB_DATE,
                        "m-d-Y"
                    );
                }
            } else {
                $companyData['first_day_of_employment'] = '';
            }
            //
        } else {
            $companyData['first_day_of_employment'] = '';
        }

        return $companyData;
    }
}

if (!function_exists("hasFileErrors")) {
    /**
     * check wether image has an error or not
     *
     * @param array $file
     * @param string $fileName
     * @param string $type
     * @param int $size  Optional
     * @return array
     */
    function hasFileErrors(array $file, string $fileName, string $type, int $size = 2): array
    {
        //
        $types = explode("|", $type);
        //
        $typeArray = [];
        //
        foreach ($types as $value) {
            $typeArray = array_merge($typeArray, getMimeByType($value));
        }
        //
        $errors = [];
        //
        if (!$file[$fileName]) {
            $errors[] = "File is empty";
        } elseif ($file[$fileName]['error']) {
            $errors[] = "File is corrupted.";
        } elseif ($file[$fileName]['size'] > ($size * 1000000)) {
            $errors[] = "File size exceeded.";
        } elseif (!in_array($file[$fileName]['type'], $typeArray)) {
            $errors[] = "File format is invalid.";
        }
        //
        return $errors;
    }
}


if (!file_exists("getSourceByType")) {
    function getSourceByType(string $type, string $path, string $props = '', $fullWidth = true): string
    {
        if ($type === "upload") {
            if (isImage($path)) {
                return '<img src="' . AWS_S3_BUCKET_URL . $path . '" style="' . ($fullWidth ? "width: 100%;" : "") . '" ' . ($props) . ' alt="' . (splitPathAndFileName($path)["name"]) . '" />';
            } else {
                return '<video src="' . AWS_S3_BUCKET_URL . $path . '" style="' . ($fullWidth ? "width: 100%;" : "") . '" controls ' . ($props) . '></video>';
            }
        } else {
            return '<iframe src="' . $path . '" title="AutomotoHR video" style="' . ($fullWidth ? "width: 100%;" : "") . ' min-height: 450px" ' . ($props) . '></iframe>';
        }
    }
}


if (!file_exists("generateLink")) {
    function generateLink(string $link): string
    {
        return strpos($link, "http") === false ? base_url($link) : $link;
    }
}

if (!function_exists("validateCaptcha")) {
    /**
     * validate google captcha
     */
    function validateCaptcha(string $str)
    {
        $google_url = "https://www.google.com/recaptcha/api/siteverify";
        $secret = getCreds("AHR")->GOOGLE_CAPTCHA_API_SECRET_V2;
        $url = $google_url . "?secret=" . $secret . "&response=" . $str;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.16) Gecko/20110319 Firefox/3.6.16");
        $res = curl_exec($curl);
        curl_close($curl);
        $res = json_decode($res, true);
        return $res['success'];
    }
}


if (!function_exists("convertToStrip")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertToStrip(string $str): string
    {
        return preg_replace("/##(.*?)##/i", '<strong class="text-yellow">$1</strong>', $str);
    }
}


if (!function_exists("convertToHilited")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertToHilited(string $str): string
    {
        return preg_replace("/##(.*?)##/i", '<span class="highlighted-light-blue-div">$1</span>', $str);
    }
}

if (!function_exists("makeResourceView")) {
    function makeResourceView($file)
    {
        // get the file extension
        $extension =
            pathinfo($file, PATHINFO_EXTENSION);
        // for video
        if (in_array($extension, ['mp4', 'm4a', 'm4v', 'f4v', 'f4a', 'm4b', 'm4r', 'f4b', 'mov'])) {
            return ' <video style="width: 100%" src="' . (AWS_S3_BUCKET_URL . $file) . '" controls="true" class="resources-video-detail" alt="smiling girl"> </video>';
        } elseif (in_array($extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) {
            return '<img src="' . (AWS_S3_BUCKET_URL . $file) . '" class="resources-card-images-adjustment-detail" alt="tablet with tea">';
        } else {
            return '<iframe src="' . (AWS_S3_BUCKET_URL . $file) . '" width="100%" height="500px"></iframe> ';
        }
    }
}


if (!function_exists("load404")) {
    function load404()
    {
        get_instance()->load
            ->view("v1/app/header", [
                "meta_title" => "404 | AutomotoHr.com"
            ])
            ->view("v1/app/footer");
    }
}


if (!function_exists("convertEnterToSpan")) {
    /**
     * converts
     *
     * @param string $str
     * @return string
     */
    function convertEnterToSpan(string $str): string
    {

        return preg_replace("/[\n\r]/", '<span class="d-md-block">$1</span>', $str);
    }
}

if (!function_exists("getMimeByType")) {
    /**
     * check wether image has an error or not
     *
     * @param string $type
     * @return array
     */
    function getMimeByType(string $type): array
    {
        $mime_types = [
            "image" => [
                'image/png',
                'image/jpeg',
                'image/webp',
                'image/gif',
                'image/bmp',
                'image/vnd.microsoft.icon',
                'image/tiff',
                'image/tiff',
                'image/svg+xml',
                'image/svg+xml',
            ],
            "video" => [
                'video/mp4',
                'video/mov',
            ]
        ];
        //
        return $mime_types[$type] ?? [];
    }
}


if (!function_exists("isW4EmployerSectionCompleted")) {
    /**
     * check wether the W4 employer section is completed or not
     *
     * @param array $w4
     * @return bool
     */
    function isW4EmployerSectionCompleted(array $w4): bool
    {
        return $w4["emp_name"] &&
            $w4["emp_address"] &&
            $w4["first_date_of_employment"] &&
            $w4["first_date_of_employment"] != "0000-00-00" &&
            $w4["emp_identification_number"];
    }
}

if (!function_exists('get_jobTitle_dropdown_for_search')) {
    /**
     * get the employees job titles in use
     */
    function get_jobTitle_dropdown_for_search(int $companyId, string $id = '')
    {
        //
        $jobTitles = [];
        $options = '';
        $select = '<select name="' . ($id) . '" id="' . ($id) . '" class="js-filter-jobtitle" style="width: 100%;" multiple="multiple">';
        $select .= '<option value="all">All</option>';
        $select .= '{{options}}';
        $select .= '</select>';
        //
        $CI = &get_instance();
        // Get Company Job titles 
        $userJobTitle = $CI->db->select('job_title')
            ->where('parent_sid', $companyId)
            ->distinct('job_title')
            ->get('users')
            ->result_array();

        $jobTitles =
            array_unique(
                array_column($userJobTitle, 'job_title')
            );

        //
        if ($jobTitles) {
            foreach ($jobTitles as $row) {
                $options .= '<option value="' . $row . '">' . $row . '</option>';
            }
        }

        $select = str_replace('{{options}}', $options, $select);
        return $select;
    }
}


if (!function_exists("getDateFromYearAndMonth")) {
    /**
     * 
     */
    function getDateFromYearAndMonth(string $year, string $month, string $format): string
    {
        // set the object
        $dateObj = new DateTime("{$year}-{$month}-01");
        return $dateObj->format($format);
    }
}


if (!function_exists("getLoggedInPersonTimeZone")) {
    function getLoggedInPersonTimeZone(): string
    {
        // get CI instance
        $ci = get_instance();
        // 
        $tz = $ci->session->userdata("logged_in")["employer_detail"]["timezone"];
        if (!$tz) {
            $tz = $ci->session->userdata("logged_in")["company_detail"]["timezone"];
        }
        if (!$tz) {
            $tz = STORE_DEFAULT_TIMEZONE_ABBR;
        }
        return $tz;
    }
}


if (!function_exists("convertTimeZone")) {
    /**
     * convert the time zone
     * 
     * @param string $date
     * @param string $fromFormat
     * @param string $fromTimeZone
     * @param string $toTimeZone
     * @param bool $doFormat
     * @param string $toFormat
     * @return string
     */
    function convertTimeZone(
        string $date,
        string $fromFormat,
        string $fromTimeZone,
        string $toTimeZone,
        bool $doFormat = false,
        string $toFormat = DB_DATE_WITH_TIME
    ): string {
        //
        $returnedValue = reset_datetime([
            "datetime" => $date,
            "from_format" => $fromFormat,
            "format" => $fromFormat,
            "_this" => get_instance(),
            "new_zone" => $toTimeZone,
            "from_timezone" => $fromTimeZone
        ]);
        //
        return !$doFormat ? $returnedValue : formatDateToDB(
            $returnedValue,
            $fromFormat,
            $toFormat
        );
    }
}

if (!function_exists("haversineDistance")) {
    function haversineDistance($lat1, $lon1, $lat2, $lon2)
    {
        // Radius of the Earth in meters
        $R = 6371000.0;

        // Convert latitude and longitude from degrees to radians
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        // Calculate the differences in coordinates
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        // Haversine formula
        $a = sin($dlat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dlon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Calculate the distance
        return $R * $c;
    }
}

function is_within_radius($centerLatLng, $latLng, $radius)
{
    $distance = haversineDistance($centerLatLng[0], $centerLatLng[1], $latLng[0], $latLng[1]);
    return [
        "within_range" => ($distance <= $radius),
        "distance" => $distance
    ];
}


if (!function_exists("convertToList")) {
    /**
     * converts array to list
     *
     * @param array  $dataArray
     * @param string $index
     * @param bool   $convertToSlug
     * @return array
     */
    function convertToList(array $dataArray, string $index, bool $convertToSlug = true): array
    {
        // when empty array provided
        if (!$dataArray) {
            return $dataArray;
        }
        // set the holder
        $tmp = [];
        //
        foreach ($dataArray as $v0) {
            $newIndex = $v0[$index];
            // convert the index to slug
            if ($convertToSlug) {
                $newIndex = stringToSlug($newIndex, "_");
            }
            $tmp[$newIndex] = $v0;
        }
        //
        return $tmp;
    }
}

if (!function_exists("getEmployeeProfileLink")) {
    /**
     * provide employee profile link
     *
     * @param int  $employeeId
     * @return string
     */
    function getEmployeeProfileLink(int $employeeId): string
    {
        $link = 'javascript:;';
        //
        if (isPayrollOrPlus()) {
            $link = base_url("employee_profile/" . $employeeId);
        }
        //
        return $link;
    }
}

if (!function_exists("getWageFromTime")) {
    function getWageFromTime($time = 0, $rate = 0): string
    {
        $wage = 0;
        //
        if ($rate > 0 && $time > 0) {
            $wage = (($time / (60 * 60)) * $rate);
        }
        //
        return _a($wage);
    }
}

if (!function_exists("getTotalWageFromTime")) {
    function getTotalWageFromTime($record, $type): string
    {
        $total = 0;
        //
        if ($record['regular_time'] > 0 && $record['normal_rate'] > 0) {
            $total += ($record['regular_time'] / (60 * 60)) * $record['normal_rate'];
        }
        //
        if ($record['overtime'] > 0 && $record['over_time_rate'] > 0) {
            $total += ($record['overtime'] / (60 * 60)) * $record['over_time_rate'];
        }
        //
        if ($record['double_overtime'] > 0 && $record['double_over_time_rate'] > 0) {
            $total += ($record['double_overtime'] / (60 * 60)) * $record['double_over_time_rate'];
        }
        //
        // if ($record['paid_break_time'] > 0 && $record['normal_rate'] > 0) {
        //     $total += ($record['paid_break_time'] / (60 * 60)) * $record['normal_rate'];
        // }
        //
        if ($type == "all" && $record['paid_time_off'] > 0 && $record['normal_rate'] > 0) {
            $total += ($record['paid_time_off']['total_hours']) * $record['normal_rate'];
        }
        //
        return _a($total);
    }
}

if (!function_exists("getTotalWorkTime")) {
    function getTotalWorkTime($record): string
    {
        $total = 0;
        //
        if ($record['regular_time'] > 0) {
            $total += $record['regular_time'] / (60 * 60);
        }
        //
        if ($record['overtime'] > 0) {
            $total += $record['overtime'] / (60 * 60);
        }
        //
        if ($record['double_overtime'] > 0) {
            $total += $record['double_overtime'] / (60 * 60);
        }
        //
        return $total . 'h';
    }
}

if (!function_exists("getLoggedInPersonId")) {
    /**
     * get the logged in person id
     */
    function getLoggedInPersonId()
    {
        // get CI instance
        return get_instance()
            ->session
            ->userdata("logged_in")["employer_detail"]["sid"] ?? null;
    }
}


if (!function_exists("generateRandomColor")) {
    function generateRandomColor()
    {
        // Generate random RGB values with higher intensity
        $red = mt_rand(150, 255);
        $green = mt_rand(150, 255);
        $blue = mt_rand(150, 255);

        // Convert RGB values to hexadecimal
        return sprintf("#%02x%02x%02x", $red, $green, $blue);
    }
}

if (!function_exists("saveHistoryToProfile")) {
    /**
     * saves the history of profile
     *
     * @param int $employeeId
     * @param array $diffArray
     */
    function saveHistoryToProfile(int $employeeId, array $diffArray)
    {
        // get CI instance
        $CI = get_instance();
        // insert the history
        $CI->db
            ->insert('profile_history', [
                "user_sid" => $employeeId,
                "employer_sid" => $CI->session->userdata("logged_in")["employer_detail"]["sid"] ?? 0,
                "profile_data" => json_encode($diffArray),
                "created_at" => getSystemDate(),
            ]);
    }
}

if (!function_exists("getLocationAddress")) {
    function getLocationAddress($lat, $lng)
    {
        $geoCodeJson = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false&key=AIzaSyBZFbi_PJLj0Sl42it9ThtQybsfu4MGY6w');
        $output = json_decode($geoCodeJson);
        $status = $output->status;
        //
        if ($status == "OK") {
            return $output->results[0]->formatted_address;
        } else {
            return "Unknown Location";
        }
    }
}

if (!function_exists("showEmployeeStatusSelect")) {
    /**
     * get the employee status dropdown
     *
     * @param array $selectedOptions
     * @param string $props
     * @return string
     */
    function showEmployeeStatusSelect(array $selectedOptions = [], string $props = ""): string
    {
        // set option array
        $options = [];
        // add options
        $options[0] = "[Select An Employee]";
        $options[8] = "Active Employee";
        $options[7] = "Active Employee On Leave";
        $options[4] = "Active Employee Suspended";
        $options[6] = "In-Active Employee";
        $options[1] = "Terminated";
        $options[2] = "Retired Employee";
        $options[3] = "Deceased Employee";
        //
        $html = '';
        $html = "<select {$props}>";
        foreach ($options as $index => $option) {
            $html .= '<option value="' . ($index) . '" ';
            $html .=  $selectedOptions && in_array($index, $selectedOptions) ? "selected" : "";
            $html .= ">";
            $html .= $option;
            $html .= '</option>';
        }
        $html .= "</select>";
        //
        return $html;
    }
}


if (!function_exists("getTheWhereFromEmployeeStatus")) {
    /**
     * get the where from employee status id
     *
     * @param string $statusCode
     * @return array
     */
    function getTheWhereFromEmployeeStatus(string $statusCode): array
    {
        // set where array
        $whereArray = [];
        if ($statusCode == 6) {
            // for inactive employees
            $whereArray = [
                "users.active" => 0
            ];
        } elseif ($statusCode == 1) {
            // for terminated employees
            $whereArray = [
                "users.active" => 0,
                "users.terminated_status" => 1
            ];
        } elseif ($statusCode == 2) {
            // for retired employees
            $whereArray = [
                "users.general_status" => "retired"
            ];
        } else {
            // when all is selected
            $whereArray = [
                "users.active" => 1,
                "users.terminated_status" => 0
            ];
        }
        //
        return $whereArray;
    }
}


if (!function_exists("getIndeedMappedQuestionType")) {
    /**
     * get the Indeed mapped question type
     *
     * @param string $questionType
     * @return string
     */
    function getIndeedMappedQuestionType(string $questionType): string
    {
        //
        $questionType = strtolower($questionType);
        // set the array
        $indeedTypeArray = [
            "string" => "text",
            "boolean" => "select",
            "multilist" => "multiselect",
            "list" => "select",
        ];
        // return it
        return $indeedTypeArray[$questionType];
    }
}

if (!function_exists("getEEOCFormQuestions")) {
    /**
     * get EEOC form questions
     *
     * @return array
     */
    function getEEOCFormQuestions(array $fields): array
    {
        // set the questions
        $questionsArray = [];
        // set the question
        $questionsArray[0] = [
            "id" => "citizen",
            "type" => "select",
            "question" => "I am a U.S. citizen or permanent resident",
            "options" => [
                [
                    "label" => "Yes",
                    "value" => "Yes",
                ],
                [
                    "label" => "No",
                    "value" => "No",
                ],
            ],
        ];
        // set the question
        $questionsArray[1] = [
            "id" => "group",
            "type" => "select",
            "question" => "1. GROUP STATUS (PLEASE CHECK ONE)",
            "options" => [
                [
                    "label" => "Hispanic or Latino - A person of Cuban, Mexican, Puerto Rican, South or Central American, or other Spanish culture or origin regardless of race.",
                    "value" => "Hispanic or Latino",
                ],
                [
                    "label" => "White (Not Hispanic or Latino) - A person having origins in any of the original peoples of Europe, the Middle East or North Africa.",
                    "value" => "White",
                ],
                [
                    "label" => "Black or African American (Not Hispanic or Latino) - A person having origins in any of the black racial groups of Africa.",
                    "value" => "Black or African American",
                ],
                [
                    "label" => "Native Hawaiian or Other Pacific Islander (Not Hispanic or Latino) - A person having origins in any of the peoples of Hawaii, Guam, Samoa or other Pacific Islands.",
                    "value" => "Native Hawaiian or Other Pacific Islander",
                ],
                [
                    "label" => "Asian (Not Hispanic or Latino) - A person having origins in any of the original peoples of the Far East, Southeast Asia or the Indian Subcontinent, including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand and Vietnam.",
                    "value" => "Asian",
                ],
                [
                    "label" => "American Indian or Alaska Native (Not Hispanic or Latino) - A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.",
                    "value" => "American Indian or Alaska Native",
                ],
                [
                    "label" => "Two or More Races (Not Hispanic or Latino) - All persons who identify with more than one of the above five races.",
                    "value" => "Two or More Races",
                ],
            ],
            "required" => true
        ];
        // set the question
        $questionsArray[2] = [
            "id" => "veteran",
            "type" => "select",
            "question" => "2. VETERAN",
            "options" => [
                [
                    "label" => "Disabled Veteran: A veteran of the U.S. military, ground, naval or air service who is entitled to compensation (or who but for the receipt of military retired pay would be entitled to compensation) under laws administered by the Secretary of Veterans Affairs; or a person who was discharged or released from active duty because of a service-connected disability.",
                    "value" => "Disabled Veteran",
                ],
                [
                    "label" => "Recently Separated Veteran: A \"recently separated veteran\" means any veteran during the three-year period beginning on the date of such veteran's discharge or release from active duty in the U.S. military, ground, naval, or air service.",
                    "value" => "Recently Separated Veteran",
                ],
                [
                    "label" => "Active Duty Wartime or Campaign Badge Veteran: An \"active duty wartime or campaign badge veteran\" means a veteran who served on active duty in the U.S. military, ground, naval or air service during a war, or in a campaign or expedition for which a campaign badge has been authorized under the laws administered by the Department of Defense.",
                    "value" => "Active Duty Wartime or Campaign Badge Veteran",
                ],
                [
                    "label" => "Armed Forces Service Medal Veteran: An \"Armed forces service medal veteran\" means a veteran who, while serving on active duty in the U.S. military, ground, naval or air service, participated in a United States military operation for which an Armed Forces service medal was awarded pursuant to Executive Order 12985.",
                    "value" => "Armed Forces Service Medal Veteran",
                ],
                [
                    "label" => "I Am Not a Protected Veteran",
                    "value" => "I Am Not a Protected Veteran",
                ],
            ],
        ];
        // set the question
        $questionsArray[3] = [
            "id" => "disability",
            "type" => "select",
            "question" => "3. VOLUNTARY SELF-IDENTIFICATION OF DISABILITY",
            "options" => [
                [
                    "label" => "YES, I HAVE A DISABILITY (or previously had a disability)",
                    "value" => "YES, I HAVE A DISABILITY",
                ],
                [
                    "label" => "NO, I DON'T HAVE A DISABILITY",
                    "value" => "NO, I DON'T HAVE A DISABILITY",
                ],
                [
                    "label" => "I DON'T WISH TO ANSWER",
                    "value" => "I DON'T WISH TO ANSWER",
                ],
            ],
        ];
        // set the question
        $questionsArray[4] = [
            "id" => "gender",
            "type" => "select",
            "question" => "4. GENDER (PLEASE CHECK ONE)",
            "options" => [
                [
                    "label" => "Male",
                    "value" => "Male",
                ],
                [
                    "label" => "Female",
                    "value" => "Female",
                ],
                [
                    "label" => "Other",
                    "value" => "Other",
                ],
            ],
        ];
        // set the required
        if ($fields["dl_citizen"]) {
            $questionsArray[0]["required"] = true;
        }
        if ($fields["dl_vet"]) {
            $questionsArray[2]["required"] = true;
        }
        if ($fields["dl_vol"]) {
            $questionsArray[3]["required"] = true;
        }
        if ($fields["dl_gen"]) {
            $questionsArray[4]["required"] = true;
        }

        //
        return $questionsArray;
    }
}
