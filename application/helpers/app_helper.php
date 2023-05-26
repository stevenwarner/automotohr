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
