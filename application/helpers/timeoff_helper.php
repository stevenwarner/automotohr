<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Add messages to policies
 * 
 * @date 12/03/2020
 * 
 * @param Array   $p       Policy Reference
 * @param String  $type    Accrual Index
 * @param Array   $replace Contains magic codes and indexes to 
 *                         replaced.
 * 
 * @return Void
 */
if (!function_exists('setPolicyStatusFields')) {
    function setPolicyStatusFields(
        &$p,
        $type,
        $replace
    ) {
        // Set messages array
        $msg = [];
        $msg['{{minimum_probation_days}}'] = "The employee is on probation and doesn't meet the minimum workdays of {{new_hire_days}} days. The employee has worked for {{worked_days}} days.";
        $msg['{{accural_start_date_null}}'] = "The policy 'Accrual Start Date' is set Employee's joining date which is less than today's date. <br /> Joining Date: {{joined_at}} <br />  Todays Date: {{todays_date}}.";
        $msg['{{accural_start_date}}'] = "Employee's joining date is less than the policy start date. <br /> Joining Date: {{joined_at}} <br /> Policy Start Date: {{accrual_start_date}}.";
        $msg['{{minimum_applicable_hours}}'] = "The employee doesn't meet the minimum work {{minimum_applicable_type}} of {{minimum_applicable_hours}} {{minimum_applicable_type}}. The employee has worked for {{worked_days}} {{minimum_applicable_type}}.";
        // Check and replace message
        $p['PolicyStatus']['Implements'] = false;
        $p['PolicyStatus']['Reason'] = str_replace(array_keys($replace), $replace, (isset($msg[$type]) ? $msg[$type] : ''));
    }
}

/**
 * Get difefrence format from string
 * 
 * @param String  $s
 * 
 * @return String|Void
 */
if (!function_exists('getFormatFromString')) {
    function getFormatFromString(
        $s
    ) {
        if ($s == 'years') return "%y";
        if ($s == 'months') return "%m";
        if ($s == 'days') return "%d";
        if ($s == 'hours') return "%h";
    }
}

/**
 * Check if policy meets the accruals
 * 
 * @param Array       $accrual
 * @param String|Null $employeeJoiningDate
 * @param String|Null $employmentStatus
 * @param Reference   $policy
 * 
 * @return Bool
 */
if (!function_exists('policyAccrualCheck')) {
    function policyAccrualCheck(
        $accrual,
        $employeeJoiningDate,
        $employmentStatus,
        &$policy
    ) {
        // If accrual is empty that means the employee can avail the time
        if (empty($accrual)) return true;

        // Set policy accural checks
        // New hire days check
        if (strtolower($employmentStatus) != 'permanent' && date('Y-m-d', strtotime('now')) < date('Y-m-d', strtotime($employeeJoiningDate . '+' . ($accrual['new_hire_days']) . ' days'))) {
            setPolicyStatusFields(
                $policy,
                '{{minimum_probation_days}}',
                [
                    'new_hire_days' => $accrual['new_hire_days'],
                    'worked_days' => dateDifferenceInDays(
                        date('Y-m-d', strtotime('now')),
                        date('Y-m-d', strtotime($employeeJoiningDate . '+' . ($accrual['new_hire_days']) . ' days'))
                    )
                ]
            );
            //
            return false;
        }

        // Check for applicable date
        if ($accrual['accural_start_date'] == null || $accrual['accural_start_date'] == '0000-00-00') {
            if ($employeeJoiningDate > date('Y-m-d', strtotime('now'))) {
                setPolicyStatusFields(
                    $policy,
                    '{{accural_start_date_null}}',
                    [
                        'joinedAt' => formatDate($employeeJoiningDate),
                        'todays_date' => date('m/d/Y', strtotime('now'))
                    ]
                );
                //
                return false;
            }
        } else {
            //
            if ($accrual['accural_start_date'] > date('Y-m-d', strtotime('now'))) {
                setPolicyStatusFields(
                    $policy,
                    '{{accural_start_date}}',
                    [
                        'accural_start_date' => formatDate($accrual['accural_start_date']),
                        'todays_date' => date('m/d/Y', strtotime('now'))
                    ]
                );
                //
                return false;
            }
        }

        // minimum applicable check
        if (date('Y-m-d', strtotime('now')) < date('Y-m-d', strtotime($employeeJoiningDate . '+' . ($accrual['minimum_applicable_hours']) . ' ' . ($accrual['minimum_applicable_type']) . ''))) {
            setPolicyStatusFields(
                $policy,
                '{{minimum_applicable_hours}}',
                [
                    '{{minimum_applicable_hours}}' => $accrual['minimum_applicable_hours'],
                    '{{minimum_applicable_type}}' => $accrual['minimum_applicable_type'],
                    '{{worked_days}}' => dateDifferenceInDays(
                        date('Y-m-d', strtotime('now')),
                        date('Y-m-d', strtotime($employeeJoiningDate . '+' . ($accrual['minimum_applicable_hours']) . ' ' . ($accrual['minimum_applicable_type']) . '')),
                        getFormatFromString($accrual['minimum_applicable_type'])
                    )
                ]
            );
            //
            return false;
        }
        //
        return true;
    }
}

/**
 * Get awarded time
 * 
 * @param Object  $employeeJoiningDateOBJ
 * @param Inteher $employeeWorkedInMinutes
 * @param Array   $rateInMinutes
 * @param Integer $rateType
 * @param Array   $plans
 * 
 * @return Integer
 */
if (!function_exists('getAwardedRate')) {
    function getAwardedRate(
        $employeeJoiningDateOBJ,
        $employeeWorkedInMinutes,
        $rateInMinutes,
        $rateType,
        $plans
    ) {
        //
        if (empty($plans)) return $rateInMinutes;
        //
        $employeeWorkedTimeInTimestamp = new DateTime(date('Y-m-d', $employeeJoiningDateOBJ->format('U')));
        $employeeWorkedTimeInTimestamp->add(new DateInterval('PT' . ($employeeWorkedInMinutes) . 'M'));
        $employeeWorkedTimeInTimestamp = $employeeWorkedTimeInTimestamp->format('U');
        //
        $i = count($plans) - 1;
        $il = 0;
        $d2 = 'hours';
        // Inverse loop
        for ($i; $i >= $il; $i--) {
            //
            if (isset($plans[$i]['accrualType']) && $plans[$i]['accrualTypeM']) {
                //
                $dateToAdd = $plans[$i]['accrualType'];
                $d = $plans[$i]['accrualTypeM'];
                //
                $planTimeInTimestamp = new DateTime(date('Y-m-d', $employeeJoiningDateOBJ->format('U')));
                $planTimeInTimestamp->add(new DateInterval('P' . ($dateToAdd) . '' . ($d == 'years' ? 'Y' : 'M') . ''));
                $planTimeInTimestamp = $planTimeInTimestamp->format('U');
                //
                // Difference in minutes
                if ($employeeWorkedTimeInTimestamp >= $planTimeInTimestamp) {
                    //
                    $newAllowedTime = $plans[$i]['accrualRate'] * 60;
                    //
                    return $newAllowedTime + $rateInMinutes;
                }
            }
        }
        //
        return $rateInMinutes;
    }
}

/**
 * Manage Accruals
 * 
 * @param Integer     $policyId
 * @param Integer     $employeeId
 * @param String      $employementStatus
 * @param String      $employeeJoiningDate
 * @param String      $durationInMinutes
 * @param Array       $accruals
 * @param Intger      $balanceInMinutes
 * @param String      $asOfToday (Optional)
 * @param String      $slug (Optional)
 * 
 * @return Array
 */
if (!function_exists('getEmployeeAccrual')) {
    function getEmployeeAccrual(
        $policyId,
        $employeeId,
        $employementStatus,
        $employeeJoiningDate,
        $durationInMinutes,
        $accruals,
        $balanceInMinutes,
        $asOfToday = '',
        $slug = '',
        $categoryType = '1'
    ) {
        // Get instance of CI
        $_this = &get_instance();
        // Load time off modal
        $_this->load->model('timeoff_model');
        // Get employee balance
        // Set default array
        $r = [
            'AllowedTime' => $balanceInMinutes,
            'ConsumedTime' => 0,
            'RemainingTime' => $balanceInMinutes,
            'CarryOverTime' => 0,
            'UnpaidConsumedTime' => 0,
            'IsUnlimited' => 0,
            'MaxNegativeTime' => $balanceInMinutes,
            'Balance' => $balanceInMinutes,
            'Plans' => [],
            'EmployementStatus' => 'probation',
            'EmployeeJoinedAt' => $employeeJoiningDate,
            'RemainingTimeWithNegative' => $balanceInMinutes,
            'Reason' => ''
        ];
        //
        $todayDate = !empty($asOfToday) ? $asOfToday : date('Y-m-d', strtotime('now'));
        $todayDate = getFormatedDate($todayDate);
        //
        $cdd = date('d', strtotime($todayDate));
        $cdm = date('m', strtotime($todayDate));
        //
        $consumedTimeInMinutes = 0;
        $pendingTimeInMinutes = 0;
        $negativeTimeInMinutes = 0;
        $carryOverTimeInMinutes = 0;
        $originalAloowedTime = 0;
        $accrualRateInMinutesWithoutAward = 0;
        $getConsumedTime = 1;
        //
        $employeeJoiningDate = explode(' ', $employeeJoiningDate)[0];
        //
        if (empty($employeeJoiningDate)) $employeeJoiningDate = $todayDate;
        //
        $todayDateOBJ = DateTime::createfromformat('Y-m-d', $todayDate);
        $employeeJoiningDateOBJ = DateTime::createfromformat('Y-m-d', $employeeJoiningDate);
        //
        $diff = $todayDateOBJ->diff($employeeJoiningDateOBJ);
        //
        $employeeWorkedInMinutes =  ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
        //
        // $planTimeInTimestamp = new DateTime(date('Y-m-d', $employeeJoiningDateOBJ->format('U')));
        // $planTimeInTimestamp->add(new DateInterval('P'.($plan['accrualType']).''.( $plan['accrualTypeM'] == 'years' ? 'Y' : 'M' ).''));
        // $planTimeInTimestamp = $planTimeInTimestamp->format('Y-m-d');
        // //
        // $r['Plans'][] = 
        // [
        //     'time' => get_array_from_minutes( ($accruals['rate']+$plan['accrualRate']) * 60, $durationInMinutes / 60, $slug)['text'],
        //     'date' => $planTimeInTimestamp
        // ];
        //
        if (!empty($accruals['plans'])) {
            foreach ($accruals['plans'] as $plan) {
                //
                $planTimeInTimestamp = new DateTime(date('Y-m-d', $employeeJoiningDateOBJ->format('U')));
                $planTimeInTimestamp->add(new DateInterval('P' . ($plan['accrualType']) . '' . ($plan['accrualTypeM'] == 'years' ? 'Y' : 'M') . ''));
                $planTimeInTimestamp = $planTimeInTimestamp->format('Y-m-d');
                //
                $r['Plans'][] =
                    [
                        'time' => get_array_from_minutes(($accruals['rate'] + $plan['accrualRate']) * 60, $durationInMinutes / 60, $slug)['text'],
                        'date' => $planTimeInTimestamp
                    ];
            }
        }
        // Permanent
        $accrualRate = $accruals['rate'];
        $applicableTime = $accruals['applicableTime'];
        $applicableType = $accruals['applicableTimeType'];
        //
        $employementStatus = getEmployementStatus(
            $employeeJoiningDate,
            $accruals['newHireTime'],
            $accruals['newHireTimeType'],
            $durationInMinutes,
            $todayDate
        );
        // Check if employee is on probabtion
        if ($employementStatus == 'probation') {
            // Probation
            $accrualRate = $accruals['newHireRate'];
            //
            if ($accrualRate == 0) {
                // return $r;
            }
        }
        // See if policy implements
        // When the policy starts from employee joining date
        if (empty($accruals['applicableDate']) || $accruals['applicableDate'] == '0000-00-00') {
            //
            $effectedDate = $employeeJoiningDate;
            //
            $compareDate = getFormatedDate($employeeJoiningDate, 'd-m-Y');
            //
            if ($employeeJoiningDate > $todayDate) {
                $r['Reason'] = "The employee doesn't meet the policy 'Applicable Date'. The joining date is less than today's date.";
                return $r;
            }
        } else {
            //
            $effectedDate = $accruals['applicableDate'];
            //
            $compareDate = getFormatedDate($accruals['applicableDate'], 'd-m-Y');
            // When the policy starts on a specific date
            if (getFormatedDate($todayDate, '') < getFormatedDate($accruals['applicableDate'], '')) {
                $r['Reason'] = "The employee doesn't meet the policy 'Applicable Date'.";
                return $r;
            }
        }
        //
        // Check if employee has worked for certain time
        // Employee doesn't meet the minimum allowed time
        if ($employementStatus == 'permanent' && getTimeDifference($employeeJoiningDate, $applicableTime, $applicableType, $todayDate) == false) {
            //
            $r['Reason'] = "The employee doesn't meet the minimum work-time of $applicableTime $applicableType.";
            return $r;
        }
        // Check the period
        if ($accruals['frequency'] == 'none') {
            if ($accruals['time'] == 'start_of_period') {
                if ($cdm > 6) {
                    $r['Reason'] = "The employee doesn't meet the policy 'Accrual Time'. The employee can take time-off from Jan to June.";
                    return $r;
                }
            } else if ($accruals['time'] == 'end_of_period') {
                if ($cdm < 6) {
                    $r['Reason'] = "The employee doesn't meet the policy 'Accrual Time'. The employee can take time-off from July to Dec.";
                    return $r;
                }
            }
        } else {
            if ($accruals['time'] == 'start_of_period') {
                if ($cdd > 15) {
                    $r['Reason'] = "The employee doesn't meet the policy 'Accrual Time'. The employee can take time-off from 1st to 15th.";
                    return $r;
                }
            } else if ($accruals['time'] == 'end_of_period') {
                if ($cdd < 15) {
                    $r['Reason'] = "The employee doesn't meet the policy 'Accrual Time'. The employee can take time-off from 16th to 30th.";
                    return $r;
                }
            }
        }
        // Check if time off needs to be reset
        // Check if reset date is not empty
        if (!empty($accruals['resetDate']) && $accruals['resetDate'] != '0000-00-00' && $accruals['resetDate'] != '0') {
            //
            if (strtotime(getSystemDate(DB_DATE)) >= strtotime($accruals['resetDate'])) {
                $effectedDate = $accruals['resetDate'];
            }
        }
        //
        // Convert rate into minutes
        // For days
        if ($accruals['rateType'] == 'days') $originalAloowedTime = $accrualRateInMinutes = $accrualRate * $durationInMinutes;
        else $originalAloowedTime = $accrualRateInMinutes = $accrualRate * 60; // For hours
        // Get award time for permanent employee
        if ($employementStatus != 'probation') {
            // Get awarded rate
            $accrualRateInMinutesWithoutAward = $accrualRateInMinutes;
            //
            $originalAloowedTime = $accrualRateInMinutes = getAwardedRate(
                $employeeJoiningDateOBJ,
                $employeeWorkedInMinutes,
                $accrualRateInMinutes,
                $accruals['rateType'],
                $accruals['plans']
            );
        }
        // 
        $currentDate = getSystemDate('Y-m-d');
        $effectedDate2 = checkDateFormate($effectedDate) ? formatDateToDB($effectedDate, 'm-d-Y', DB_DATE) : $effectedDate;
        //
        $consumeDate = '';
        //
        if (strtotime($currentDate) >= strtotime($effectedDate)) {
            $consumeDate = $effectedDate2;
            $consumeDate = preg_replace('/[0-9]{4}/', date('Y', strtotime('now')), $consumeDate);
            $consumedTimeInMinutes = $_this->timeoff_model->getEmployeeConsumedTimeByResetDate(
                $policyId,
                $employeeId,
                $consumeDate
            );
        } else {
            $consumedTimeInMinutes = $_this->timeoff_model->getEmployeeConsumedTime(
                $policyId,
                $employeeId,
                $accruals['method'],
                $accruals['frequency'],
                $todayDate
            );
        }
        $monthsWorked = 1;
        $hasCarryOver = 0;
        //
        $compareDateOBJ = DateTime::createfromformat('d-m-Y', $compareDate);
        //
        $difference = $compareDateOBJ->diff($todayDateOBJ);
        //
        $monthsWorked = $difference->y * 12;
        $monthsWorked += $difference->m;
        // Only get pending time if carryover
        // is enabled
        if ($accruals['carryOverCheck'] == 'yes' && $accruals['carryOverCheck'] != 'on') {
            // Difference in minutes
            $carryOverInMinutes = $accruals['carryOverVal'] * 60;
            //
            if ($carryOverInMinutes == 0) $carryOverInMinutes = $accrualRateInMinutes;
            //
            $hasCarryOver = 1;
            // Get Pending time
            $carryOverTimeInMinutes = $_this->timeoff_model->getEmployeeCarryOverTime(
                $policyId,
                $employeeId,
                $accruals['method'],
                $accruals['frequency'],
                $accruals['rateType'],
                $accrualRateInMinutes + $balanceInMinutes,
                $carryOverInMinutes,
                $todayDate,
                $monthsWorked,
                $accrualRateInMinutesWithoutAward
            );
        }
        // Set negative time
        if ($accruals['negativeBalanceCheck'] == 'yes' && $accruals['negativeBalanceCheck'] != 'off') {
            //
            if ($accruals['negativeBalanceType'] == 'days') {
                $negativeTimeInMinutes = $accruals['negativeBalanceVal'] * $durationInMinutes;
            } else {
                $negativeTimeInMinutes = $accruals['negativeBalanceVal'] * 60;
            }
        }
        // Total allowed time
        if ($accrualRateInMinutes == 0) {
            $actualTime = 0;
            $allowedTime = 0;
        } else {
            $actualTime = $accrualRateInMinutes;
            $allowedTime = $carryOverTimeInMinutes + $accrualRateInMinutes;
        }
        // Set the frequency
        //
        if ($accruals['frequency'] == 'none') $allowedTime = $allowedTime;
        else if ($accruals['frequency'] == 'yearly') {
            //
            $currentYearMonthsWorked = $difference->m + 1;
            $currentYearMonthsWorked = $currentYearMonthsWorked > 12 ? 12 : $currentYearMonthsWorked;
            //
            if ($accruals['rateType'] == 'total_hours') {
                $allowedTime = ($allowedTime / 12) * $currentYearMonthsWorked;
            } else {
                $allowedTime = ($allowedTime * 12)  * $currentYearMonthsWorked;
            }
        } else if ($accruals['frequency'] == 'custom') {
            // Get slots
            $slots = 12 / $accruals['frequencyVal'];
            //
            $workedSlots = ceil(($difference->m) / $slots);
            //
            $workedSlots = $workedSlots == 0 ? 1 : $workedSlots;
            $newAllowedTime = ($allowedTime / $slots) * $workedSlots;
            //
            $allowedTime = $newAllowedTime > $allowedTime ? $allowedTime : $newAllowedTime;
        }
        //
        $r['AllowedTime'] = $allowedTime + $balanceInMinutes;
        $r['ConsumedTime'] = $consumedTimeInMinutes;
        $r['CarryOverTime'] = $carryOverTimeInMinutes;
        $r['RemainingTime'] = $allowedTime - $consumedTimeInMinutes + $balanceInMinutes;
        $r['MaxNegativeTime'] = $negativeTimeInMinutes;
        $r['Balance'] = $balanceInMinutes;
        $r['EmployementStatus'] = $employementStatus;
        //
        if ($accruals['frequency'] == 'none') {
            $r['RemainingTimeWithNegative'] = $r['RemainingTime'] + $negativeTimeInMinutes;
        } else if ($accruals['frequency'] == 'monthly') {
            $r['RemainingTimeWithNegative'] = $r['RemainingTime'] + $negativeTimeInMinutes;
        } else if ($accruals['frequency'] == 'yearly') {
            $r['RemainingTimeWithNegative'] = $r['RemainingTime'] + $negativeTimeInMinutes;
        } else if ($accruals['frequency'] == 'custom') {
            $r['RemainingTimeWithNegative'] = $r['RemainingTime'] + $negativeTimeInMinutes;
        }
        //
        $r['IsUnlimited'] = $accrualRateInMinutes == 0 ? 1 : 0;

        // for unpaid
        if ($categoryType == '0') {
            $tmp = $r['UnpaidConsumedTime'];
            $r['UnpaidConsumedTime'] = $r['ConsumedTime'];
            $r['ConsumedTime'] = $tmp;
        }

        $currentDate = getSystemDate('Y-m-d');
        $effectedDate = checkDateFormate($effectedDate) ? formatDateToDB($effectedDate, 'm-d-Y', DB_DATE) : $effectedDate;
        //
        if (strtotime($currentDate) >= strtotime($effectedDate)) {

            //
            if ($allowedTime != 0) {
                // This section add allowed balance of current year
                $company_sid = $_this->timeoff_model->getEmployeeCompanySid($employeeId);
                $policyName = $_this->timeoff_model->getPolicyNameById($policyId);
                //
                $added_by = getCompanyAdminSid($company_sid);
                $effectedDate = preg_replace('/[0-9]{4}/', date('Y', strtotime('now')), $effectedDate);
                //
                $is_added = $_this->timeoff_model->checkAllowedBalanceAdded(
                    $employeeId,
                    $policyId,
                    1,
                    $effectedDate,
                    $allowedTime
                );
                //
                if ($is_added == 0) {
                    $balanceToAdd = array();
                    $balanceToAdd['user_sid'] = $employeeId;
                    $balanceToAdd['policy_sid'] = $policyId;
                    $balanceToAdd['added_by'] = $added_by;
                    $balanceToAdd['is_added'] = 1;
                    $balanceToAdd['added_time'] = $allowedTime;
                    $balanceToAdd['note'] = 'On <b>' . date('M d, Y,', strtotime('now')) . ' at ' . date('g:i A,', strtotime('now')) . '</b> a balance of ' . $accruals['applicableTime'] . ' hours was added in accordance with the <b>"' . $policyName . '"</b> policy.';
                    $balanceToAdd['effective_at'] = $effectedDate;
                    //
                    $_this->timeoff_model->addEmployeeAllowedBalance($balanceToAdd);
                }
            }
        }
        //
        return $r;
    }
}

function getTimeDifference(
    $employeeJoiningDate,
    $applicableTime,
    $applicableType,
    $todayDate

) {
    //
    if ($applicableTime == 0) return true;
    //
    $d1 = DateTime::createfromformat('Y-m-d', $employeeJoiningDate);
    $d2 = DateTime::createfromformat('Y-m-d', $todayDate);
    //
    $diff = $d2->diff($d1);
    //
    $minutes = $diff->days * 24 * 60;
    $minutes += $diff->h * 60;
    $minutes += $diff->i;
    //
    switch ($applicableType) {
        case "years":
            $applicableTimeToMinutes = $applicableTime * 365 * 24 * 60;
            break;
        case "months":
            $applicableTimeToMinutes = $applicableTime * 30 * 24 * 60;
            break;
        case "days":
            $applicableTimeToMinutes = $applicableTime * 24 * 60;
            break;
        case "hours":
            $applicableTimeToMinutes = $applicableTime * 24;
            break;
        case "minutes":
            $applicableTimeToMinutes = $applicableTime;
            break;
    }
    //
    if ($minutes >= $applicableTimeToMinutes) return true;
    //
    return false;
}

function getEmployementStatus(
    $employeeJoiningDate,
    $applicableTime,
    $applicableType,
    $durationInMinutes,
    $todayDate

) {
    //
    if ($applicableTime == 0) return 'permanent';
    //
    $d1 = DateTime::createfromformat('Y-m-d', $employeeJoiningDate);
    $d2 = DateTime::createfromformat('Y-m-d', $todayDate);
    //
    $diff = $d2->diff($d1);
    //
    $minutes = $diff->y * 365;
    $minutes += $diff->m * 12;
    $minutes += $diff->days * 24 * 60;
    $minutes += $diff->h * 60;
    $minutes += $diff->i;
    //
    switch ($applicableType) {
        case "years":
            $applicableTimeToMinutes = $applicableTime * 365 * 24 * 60;
            break;
        case "months":
            $applicableTimeToMinutes = $applicableTime * 30 * 24 * 60;
            break;
        case "days":
            $applicableTimeToMinutes = $applicableTime * 24 * 60;
            break;
        case "hours":
            $applicableTimeToMinutes = $applicableTime * 24;
            break;
        case "minutes":
            $applicableTimeToMinutes = $applicableTime;
            break;
        case "per_week":
        case "per_month":
            //
            $durationInHours = $durationInMinutes / 60;
            $workedTime = $durationInHours * ($applicableType == 'per_week' ? 7 : 30);
            //
            if ($workedTime >= $applicableTime) return 'permanent';
            return 'probation';
            break;
    }
    //
    if ($minutes >= $applicableTimeToMinutes) return 'permanent';
    //
    return 'probation';
}


//
function getFormatedDate(
    $d1,
    $toFormat = 'Y-m-d'
) {
    //
    $fromFormat = 'Y-m-d';
    //
    if (preg_match('/[0-9]{4}\/[0-9]{2}\/[0-9]{2}/', $d1)) $fromFormat = 'Y/m/d';
    else if (preg_match('/[0-9]{2}\/[0-9]{2}\/[0-9]{4}/', $d1)) $fromFormat = 'd/m/Y';
    else if (preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $d1)) $fromFormat = 'm-d-Y';
    //
    //
    $d2 = DateTime::createfromformat(
        $fromFormat,
        $d1
    );
    //
    if (empty($toFormat)) return $d2->format('U');
    return $d2->format($toFormat);
}


/**
 * Make an encrypted link
 * 
 * @employee Mubashir Ahmed
 * @date     02/07/2021
 * 
 * @param Array @args
 * 
 * @return String
 */
if (!function_exists('timeoffGetEncryptedLink')) {
    function timeoffGetEncryptedLink($args)
    {
        // Get CI instance
        $_this = &get_instance();
        // Load encryption library
        $_this->load->library('encryption');
        //
        $params = '';
        //
        foreach ($args as $k => $v) $params .= "{$k}={$v}/";
        //
        return base_url('timeoff/public/' . (str_replace(['-', '/', '='], ['$2B', '$3B', '$4B'], $_this->encryption->encrypt(rtrim($params, '/')))) . '');
    }
}

/**
 * Make an encrypted link
 * 
 * @employee Aleem Shaukat
 * @date     14/06/2021
 * 
 * @param Array @args
 * 
 * @return String
 */
if (!function_exists('timeoffGetApproverEncryptedLink')) {
    function timeoffGetApproverEncryptedLink($args)
    {
        // Get CI instance
        $_this = &get_instance();
        // Load encryption library
        $_this->load->library('encryption');
        //
        $params = '';
        //
        foreach ($args as $k => $v) $params .= "{$k}={$v}/";
        //
        return base_url('timeoff/approver/public/' . (str_replace(['-', '/', '='], ['$2B', '$3B', '$4B'], $_this->encryption->encrypt(rtrim($params, '/')))) . '');
    }
}

/**
 * Decrypt a link
 * 
 * @employee Mubashir Ahmed
 * @date     02/07/2021
 * 
 * @param String @url
 * 
 * @return Array
 */
if (!function_exists('timeoffDecryptLink')) {
    function timeoffDecryptLink($url)
    {
        // Get CI instance
        $_this = &get_instance();
        // Load encryption library
        $_this->load->library('encryption');
        //
        $url = explode('/', $_this->encryption->decrypt(str_replace(['$2B', '$3B', '$4B'], ['-', '/', '='], $url)));
        //
        $params = [];
        //
        foreach ($url as $urla) {
            $t = explode('=', $urla);
            $params[$t[0]] = $t[1];
        }
        //
        return $params;
    }
}


/**
 * Replace magic Quotes
 * 
 * @employee Mubashir Ahmed
 * @date     02/07/2021
 * 
 * @param Array $body
 * @param Array $replaceArray
 * 
 * @return Array
 */
if (!function_exists('timeoffMagicQuotesReplace')) {
    function timeoffMagicQuotesReplace($body, $replaceArray)
    {
        // Get indexes
        $indexes = array_keys($replaceArray);
        // Replace Subject
        $body['Subject'] = str_replace($indexes, $replaceArray, $body['Subject']);
        $body['Subject'] = preg_replace('/{{(.*)}}/', '', $body['Subject']);

        // Replace Body
        $body['Body'] = str_replace($indexes, $replaceArray, $body['Body']);
        $body['Body'] = preg_replace('/{{(.*)}}/', '', $body['Body']);

        // Replace FromName
        $body['FromName'] = str_replace($indexes, $replaceArray, $body['FromName']);
        $body['FromName'] = preg_replace('/{{(.*)}}/', '', $body['FromName']);

        //
        return $body;
    }
}


/**
 * Get time off button
 * 
 * @employee Mubashir Ahmed
 * @date     02/07/2021
 * 
 * @param Array  $replaceArray
 * 
 * @return String
 */
if (!function_exists('getButton')) {
    function getButton($replaceArray)
    {
        return
            str_replace(array_keys($replaceArray), $replaceArray, '<a href="{{url}}" target="_blank" style="padding: 8px 12px; border: 1px solid {{color}};background-color:{{color}};border-radius: 2px;font-size: 14px; color: #ffffff;text-decoration: none;font-weight:bold;display: inline-block; margin-right: 10px;">
        {{text}}             
        </a>');
    }
}


/**
 * Get time off request status
 * 
 * @employee Aleem Shaukat
 * @date     11/05/2023
 * 
 * @param Array  $requestDate
 * 
 * @return String
 */
if (!function_exists('getTimeoffRequestStatus')) {
    function getTimeoffRequestStatus($requestDate)
    {
        $date_now = date("Y-m-d");
        $requestDate = date('Y-m-d', strtotime($requestDate));
        //
        if ($date_now < $requestDate) {
            return '<strong class="text-warning">PENDING</strong>';
        } else {
            return '<strong class="text-success">CONSUMED</strong>';
        }
    }
}

/**
 * Split time off request
 * 
 * @employee Aleem Shaukat
 * @date     11/05/2023
 * 
 * @param Array  $request
 * 
 * @return Array
 */
if (!function_exists('splitTimeoffRequest')) {
    function splitTimeoffRequest($request)
    {
        //
        $response = [
            'type' => "single",
            'requestData' => $request
        ];
        //
        $fromDate = date_create($request['request_from_date']);
        $toDate = date_create($request['request_to_date']);
        //
        $requestInterval = $fromDate->diff($toDate);
        $requestDays = $requestInterval->format('%d') + 1;
        //
        if ($requestDays > 1) {
            $requestData = [];
            $response['type'] = 'multiple';
            $requestedTimePerDay = $request['requested_time'] / $requestDays;
            //
            for ($i = 0; $i < $requestDays; $i++) {
                $requestDate = date("Y-m-d", strtotime($i . 'days', strtotime($request['request_from_date'])));
                //
                $split = $request;
                $split['requested_time'] = $requestedTimePerDay;
                $split['request_from_date'] = $requestDate;
                $split['request_to_date'] = $requestDate;
                $split['request_status'] = getTimeoffRequestStatus($requestDate);
                //
                $requestData[] = $split;
            }

            $response['requestData'] = $requestData;
        } else {
            $response['requestData']['request_status'] = getTimeoffRequestStatus($request['request_from_date']);
        }
        //
        return $response;
    }
}

/**
 * Split time off request
 * 
 * @employee Aleem Shaukat
 * @date     11/05/2023
 * 
 * @param Array  $request
 * @param Array  $string
 * 
 * @return string
 */
if (!function_exists('generateTimeoffRequestSlot')) {
    function generateTimeoffRequestSlot($request, $type)
    {
        $consumed_time = $request['consumed_time'];
        //
        if ($type == 'multiple') {
            $hours = floor($request['requested_time'] / 60);

            if ($hours > 1) {
                $consumed_time = $hours . ' Hours';
            } else {
                $consumed_time = $hours . ' Hour';
            }
        }
        //
        $rowColor =  str_replace('CONSUMED', '', $request['request_status']) != $request['request_status'] ? 'background-color:  #f2dede !important;' : '';
        //
        $html =  '';
        $html .= '<tr style="' . $rowColor . '">';
        $html .= '  <td>' . (ucwords($request['first_name'] . ' ' . $request['last_name'])) . ' <br /> ' . (remakeEmployeeName($request, false)) . ' <br /> ' . (!empty($request['employee_number']) ? $request['employee_number'] : $request['employeeId']) . '</td>';
        $html .= '  <td>' . ($request['title']) . '</td>';
        $html .= '  <td>' . ($consumed_time) . '</td>';
        $html .= '  <td>' . (DateTime::createfromformat('Y-m-d', $request['request_from_date'])->format('m/d/Y')) . '</td>';
        $html .= '  <td>' . (DateTime::createfromformat('Y-m-d', $request['request_to_date'])->format('m/d/Y')) . '</td>';
        //
        $status = $request['status'];
        //
        if ($status == 'approved') {
            $html .= '<td><strong class="text-success">APPROVED</strong> (' . $request['request_status'] . ')</td>';
        } else if ($status == 'rejected') {
            $html .= '<td><strong class="text-danger">REJECTED</strong> (<strong class="text-warning">PENDING</strong>)</td>';
        } else if ($status == 'pending') {
            $html .= '<td><strong class="text-warning">PENDING</strong> (<strong class="text-warning">PENDING</strong>)</td>';
        }
        $html .= '</tr>';
        //
        return $html;
    }
}

if (!function_exists('getEmployeeManualBalance')) {
    /**
     * Get employee balance
     * 
     * Get employee manual balance for current cycle
     * 
     * @param int    $employeeId
     * @param string $policyImplementDate
     * @param string $policyNextResetDate
     * @param int    $balance Optional
     * 
     * @return int
     */
    function getEmployeeManualBalance(
        int $employeeId,
        string $policyImplementDate,
        string $policyNextResetDate,
        int $balance = 0
    ) {
        // set default 
        $balanceToReturn = $balance;
        //
        if ($policyImplementDate < '2023-05-20') {
            return $balanceToReturn;
        }
        // get CI instance
        $CI = &get_instance();
        //
        $balances =
            $this->db
            ->select('
            timeoff_balances.policy_sid,
            timeoff_balances.user_sid,
            timeoff_balances.is_added,
            timeoff_balances.added_time
        ')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_balances.policy_sid', 'inner')
            ->where('timeoff_policies.company_sid', $employeeId)
            ->where('timeoff_policies.is_archived', 0)
            ->where('timeoff_balances.effective_at >=', $policyImplementDate, FALSE)
            ->where('timeoff_balances.effective_at <=', $policyNextResetDate, FALSE)
            ->get('timeoff_balances')
            ->result_array();
        //
        if (empty($balances)) {
            return 0;
        }
        //
        $t = [];
        //
        foreach ($balances as $balance) {
            //
            $bb = $balance['user_sid'] . '-' . $balance['policy_sid'];
            //
            if (!isset($t[$bb])) $t[$bb] = 0;
            //
            if ($balance['is_added'] == '1') $t[$bb] += $balance['added_time'];
            else $t[$bb] -= $balance['added_time'];
        }
        //
        return $t;
        //
        return $balanceToReturn;
    }
}
