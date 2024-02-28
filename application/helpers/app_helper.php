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
        // get CI instance
        $CI = &get_instance();
        // update the user in "users" table
        $CI->db->where(['sid' => $employeeId])->update('users', $updateArray);
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
        return get_instance()->db
            ->select('access_token')
            ->where([
                'company_sid' => $companyId,
                'user_sid' => $employeeId
            ])
            ->get('api_credentials')
            ->row_array()['access_token'];
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
        $data['section1_last_name'] = $data['section1_last_name'] ?? $profileData['last_name'];
        $data['section1_first_name'] = $data['section1_first_name'] ?? $profileData['first_name'];
        $data['section1_middle_initial'] = $data['section1_middle_initial'] ?? $profileData['middle_name'];
        $data['section1_address'] = $data['section1_address'] ?? $address;
        $data['section1_city_town'] = $data['section1_city_town'] ?? $profileData['Location_City'];
        $data['section1_state'] = $data['section1_state'] ?? getStateColumnById($profileData['Location_State'] ?? 0);
        $data['section1_zip_code'] = $data['section1_zip_code'] ?? $profileData['Location_ZipCode'];
        $data['section1_date_of_birth'] = $data['section1_date_of_birth'] ?? $profileData['dob'];
        $data['section1_social_security_number'] = $data['section1_social_security_number'] ?? $profileData['ssn'];
        $data['section1_emp_email_address'] = $data['section1_emp_email_address'] ?? $profileData['email'];
        $data['section1_emp_telephone_number'] = $data['section1_emp_telephone_number'] ?? $profileData['PhoneNumber'];
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
        $CI = &get_instance();
        return $CI->db
            ->select($column)
            ->where($where)
            ->get('states')
            ->row_array()[$column];
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

//
if (!function_exists('employeePerformanceDocSectionsData')) {

    function employeePerformanceDocSectionsData(int $documentId)
    {

        $CI = &get_instance();
        $CI->db->select('performance_document_json');
        $CI->db->where('sid', $documentId);
        $record_row = $CI->db->get('documents_assigned')->row_array();

        $performance_document_json=[];

        if($record_row['performance_document_json']!=''){
           $performance_document_json=json_decode($record_row['performance_document_json'],true);
        }
        return $performance_document_json; 

    }
}
