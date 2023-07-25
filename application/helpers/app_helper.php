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
            ->count_all_results('payroll_employees');
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
     * @return
     */
    function isCompanyOnBoard()
    {
        // Get instance
        $CI = &get_instance();
        // Get the session
        $ses = $CI->session->userdata('logged_in');
        //
        $has = $CI->db
            ->where([
                'company_sid' => $ses['company_detail']['sid']
            ])
            ->count_all_results('payroll_companies');
        //
        if ($has) {
            return true;
        }
        // Don't created yet
        return false;
    }
}

if (!function_exists('isCompanyTermsAccpeted')) {
    /**
     * Check company already onboard
     *
     * @return
     */
    function isCompanyTermsAccpeted()
    {
        // Get instance
        $CI = &get_instance();
        // Get the session
        $ses = $CI->session->userdata('logged_in');
        //
        $has = $CI->db
            ->where([
                'company_sid' => $ses['company_detail']['sid'],
                'terms_accepted' => 1

            ])
            ->count_all_results('payroll_companies');
        //
        if ($has) {
            return true;
        }
        // Don't created yet
        return false;
    }
}

if (!function_exists('isLoggedInPersonIsSignatory')) {
    /**
     * check if logged in person is signatory
     *
     * @return bool
     */
    function isLoggedInPersonIsSignatory()
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
            ->count_all_results('payroll_signatories');
    }
}

if (!function_exists('getStaticFileVersion')) {
    /**
     * set and get the version of the minified file
     *
     * @param string $file The URI of the asset
     * @returns string
     */
    function getStaticFileVersion(
        string $file
    ) {
        // set files
        $files = [];
        // plugins
        $files['v1/plugins/ms_uploader/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        $files['v1/plugins/ms_modal/main'] = ['css' => '2.0.0', 'js' => '2.0.0'];
        // set the main CSS file
        $files['2022/css/main'] = ['css' => '2.0.0'];
        // check and return data
        return $files[$file] ?? [];
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
if (!function_exists('getUserDataById')) {

    function getUserDataById($fieldsName = '*', $employeeId)
    {
        // get CI instance
        $CI = &get_instance();
        $users = $CI->db->select($fieldsName)
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        return $users;
    }
}

//
if (!function_exists('updateW4DataById')) {
    function updateW4DataById($employeeId, $w4sid, $data)
    {
        $CI = &get_instance();
        $CI->db
            ->where('employer_sid', $employeeId)
            ->where('sid', $w4sid)
            ->where('users_type', 'employee')
            ->update('form_w4_original', $data);
    }
}

//
if (!function_exists('syncW4Data')) {

    function syncW4Data($employeeId, $formData)
    {

        //
        if ($formData['user_type'] == 'employee') {
            $tableName = 'users';
            $fields = "first_name,last_name,middle_name,ssn,Location_Address,Location_City,Location_ZipCode,Location_state,marital_status,";
        }
        //
        if ($formData['user_type'] == 'applicant') {
            $fields = "first_name,last_name,middle_name,ssn,address,city,zipcode,state,marital_status,";
            $tableName = 'portal_job_applications';
        }

        // get CI instance
        $CI = &get_instance();
        $userData = $CI->db->select($fields)
            ->where('sid', $employeeId)
            ->get($tableName)
            ->row_array();

        if ($formData['user_type'] == 'applicant') {
            $userData['Location_Address'] = $userData['address'];
            $userData['Location_City'] = $userData['city'];
            $userData['Location_ZipCode'] = $userData['zipcode'];
            $userData['Location_state'] = $userData['state'];
        }

        //
        if ($formData['first_name'] == null || $formData['first_name'] == '') {
            $formData['first_name'] = $userData['first_name'];
        }
        if ($formData['last_name'] == null || $formData['last_name'] == '') {
            $formData['last_name'] = $userData['last_name'];
        }

        if ($formData['middle_name'] == null || $formData['middle_name'] == '') {
            $formData['middle_name'] = $userData['middle_name'];
        }
        if ($formData['ss_number'] == null || $formData['ss_number'] == '') {
            $formData['ss_number'] = $userData['ssn'];
        }
        if ($formData['home_address'] == null || $formData['home_address'] == '') {
            $pre_form['home_address'] = $userData['Location_Address'];
        }
        if ($formData['zip'] == null || $formData['zip'] == '') {
            $formData['zip'] = $userData['Location_ZipCode'];
        }
        if ($formData['city'] == null || $formData['city'] == '') {
            $formData['city'] = $userData['Location_City'];
        }
        /*
        if ($formData['marriage_status'] == null || $formData['marriage_status'] == '') {
            if ($userData['marital_status'] == 'Single') {
                $formData['marriage_status'] = 'separately';
            }
        }
    
            if ($formData['marriage_status'] == null || $formData['marriage_status'] == '') {
                if ($userData['marital_status'] == 'Married') {
                    $formData['marriage_status'] = 'jointly';
                }
            }
      */
        if ($formData['state'] == null || $formData['state'] == '') {
            if (!empty($userData['Location_state'])) {
                $formData['state'] = db_get_state_name_only($userData['Location_state']);
            };
        }

        return $formData;
    }
}


//
if (!function_exists('syncW4DataChanges')) {

    function syncW4DataChanges(
        $employeeId,
        $dataToInsert,
        $userType
    ) {

        //
        $fields = "first_name,last_name,middle_name,ssn,Location_Address,Location_City,Location_ZipCode,Location_state,marital_status,";


        if ($userType == 'employee') {
            $tableName = 'users';
            $fields = "first_name,last_name,middle_name,ssn,Location_Address,Location_City,Location_ZipCode,Location_state,marital_status,";
        }
        //
        if ($userType == 'applicant') {
            $fields = "first_name,last_name,middle_name,ssn,address,city,zipcode,state,marital_status,";
            $tableName = 'portal_job_applications';
        }

        // get CI instance
        $CI = &get_instance();
        $employeeDetail = $CI->db->select($fields)
            ->where('sid', $employeeId)
            ->get($tableName)
            ->row_array();
        //

        $newCompareData = [];
        $newCompareData['first_name'] = $dataToInsert['first_name'];
        $newCompareData['middle_name'] = $dataToInsert['middle_name'];
        $newCompareData['last_name'] = $dataToInsert['last_name'];
        $newCompareData['Location_Address'] = $dataToInsert['Location_Address'];
        $newCompareData['Location_City'] = $dataToInsert['Location_City'];
        $newCompareData['Location_ZipCode'] = $dataToInsert['Location_ZipCode'];
        $newCompareData['Location_State'] = $dataToInsert['Location_State'];
        $newCompareData['ssn'] = $dataToInsert['ssn'];
        $newCompareData['marital_status'] = $dataToInsert['marital_status'];

        // Old Data
        $oldCompareData = [];
        if ($userType == 'employee') {
            $oldCompareData['first_name'] = $employeeDetail['first_name'];
            $oldCompareData['middle_name'] = $employeeDetail['middle_name'];
            $oldCompareData['last_name'] = $employeeDetail['last_name'];
            $oldCompareData['Location_Address'] = $employeeDetail['Location_Address'];
            $oldCompareData['Location_City'] = $employeeDetail['Location_City'];
            $oldCompareData['Location_ZipCode'] = $employeeDetail['Location_ZipCode'];
            $oldCompareData['Location_State'] = $employeeDetail['Location_State'];
            $oldCompareData['ssn'] = $employeeDetail['ssn'];
        }

        //
        if ($userType == 'applicant') {
            $oldCompareData['first_name'] = $employeeDetail['first_name'];
            $oldCompareData['middle_name'] = $employeeDetail['middle_name'];
            $oldCompareData['last_name'] = $employeeDetail['last_name'];
            $oldCompareData['Location_Address'] = $employeeDetail['address'];
            $oldCompareData['Location_City'] = $employeeDetail['city'];
            $oldCompareData['Location_ZipCode'] = $employeeDetail['zipcode'];
            $oldCompareData['Location_State'] = $employeeDetail['state'];
            $oldCompareData['ssn'] = $employeeDetail['ssn'];
        }

        $difference = findDifferenceData($oldCompareData, $newCompareData);

        //
        if ($difference['profile_changed'] == 0) {
            return false;
        }
        //
        $data_array = [];
        foreach ($difference['data'] as $key => $val) {
            $data_array[$key] = $val['new'];
        }

        //Update to user
        if ($userType == 'employee') {
            $CI->db->where('sid', $employeeId);
            $CI->db->update('users', $data_array);

            //Insert To Log 
            $employerId = $CI->session->userdata('logged_in')['employer_detail']['sid'];

            $CI->db->insert('profile_history', [
                'user_sid' => $employeeId,
                'employer_sid' => $employerId,
                'profile_data' => json_encode($difference['data']),
                'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                'change_from' => 'w4'
            ]);
        }

        //Update to Applicant
        if ($userType == 'applicant') {
            $CI->db->where('sid', $employeeId);
            $CI->db->update('portal_job_applications', $data_array);
        }
    }
}


//
if (!function_exists('findDifferenceData')) {
    function findDifferenceData($previous_data, $form_data)
    {
        // 
        $profile_changed = 0;
        //
        $dt = [];
        //
        if (!empty($previous_data)) {
            foreach ($previous_data as $key => $data) {
                //
                if (!isset($form_data[$key])) {
                    continue;
                }
                //   
                if ((isset($form_data[$key])) && strip_tags($data) != strip_tags($form_data[$key])) {
                    //
                    $dt[$key] = [
                        'old' => $data,
                        'new' => $form_data[$key]
                    ];
                    //
                    $profile_changed = 1;
                }
            }
        }
        //
        return ['profile_changed' => $profile_changed, 'data' => $dt];
    }
}


//
if (!function_exists('syncW9Data')) {

    function syncW9Data($employeeId, $formData)
    {

        //
        if ($formData['user_type'] == 'employee') {
            $tableName = 'users';
            $fields = "first_name,last_name,ssn,Location_Address,Location_City,Location_ZipCode,Location_state";
        }
        //
        if ($formData['user_type'] == 'applicant') {
            $fields = "first_name,last_name,middle_name,ssn,address,city,zipcode,state";
            $tableName = 'portal_job_applications';
        }

        // get CI instance
        $CI = &get_instance();
        $userData = $CI->db->select($fields)
            ->where('sid', $employeeId)
            ->get($tableName)
            ->row_array();


        $userData['w9_social_security_number'] = $userData['ssn'];

        if ($formData['user_type'] == 'employee') {
            $userData['w9_address'] = $userData['Location_Address'];
            $userData['w9_city_state_zip'] = $userData['Location_City'] . ',' . $userData['Location_state'] . ',' . $userData['Location_ZipCode'];
        }

        if ($formData['user_type'] == 'applicant') {
            $userData['w9_address'] = $userData['address'];
            $userData['w9_city_state_zip'] = $userData['city'] . ',' . $userData['state'] . ',' . $userData['zipcode'];
        }

        //
        if ($formData['w9_social_security_number'] == null || $formData['w9_social_security_number'] == '') {
            $formData['w9_social_security_number'] = $userData['ssn'];
        }
        if ($formData['w9_address'] == null || $formData['w9_address'] == '') {
            $formData['w9_address'] = $userData['w9_address'];
        }

        if ($formData['w9_name'] == null || $formData['w9_name'] == '') {
            $formData['w9_name'] = $userData['first_name'] . ' ' . $userData['last_name'];
        }

        if ($formData['w9_city_state_zip'] == null || $formData['w9_city_state_zip'] == '') {
            $formData['w9_city_state_zip'] = $userData['w9_city_state_zip'];
        }

        return $formData;
    }
}
