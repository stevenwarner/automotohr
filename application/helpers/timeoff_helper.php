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
 * @param int     $policyId
 * @param int     $employeeId
 * @param string      $employementStatus
 * @param string      $employeeJoiningDate
 * @param string      $durationInMinutes
 * @param array       $accruals
 * @param int      $balanceInMinutes
 * @param string      $asOfToday (Optional)
 * @param string      $slug (Optional)
 * @param array       $employeeDefaultAccrual
 *
 * @return array
 */
if (!function_exists('getEmployeeAccrualNew')) {
    function getEmployeeAccrualNew(
        $policyId,
        $employeeId,
        $employmentStatus,
        $employeeJoiningDate,
        $durationInMinutes,
        $accruals,
        $balanceInMinutes,
        $asOfToday,
        $slug,
        $categoryType,
        $employeeDefaultAccrual,
        $accrualRateInMinutes,
        $employeeAnniversaryDate
    ) {
        // get CI instance
        $CI = &get_instance();
        $accrualRate = $accruals['rate'];
        $applicableTime = $accruals['applicableTime'];
        $applicableType = $accruals['applicableTimeType'];
        // set default plan array
        $policyPlansDates = [
            'lastAnniversaryDate' => $employeeJoiningDate,
            'upcomingAnniversaryDate' => getSystemDate(DB_DATE),
            'breakdown' => []
        ];
        // check on probation
        if ($employmentStatus != 'probation') {
            //
            $accrualRateInMinutesWithoutAward = $accrualRateInMinutes;
            // extract plans
            $plans = $employeeDefaultAccrual['Plans'];
            // if plans found
            if ($plans) {
                // extract the plan dates
                $plansApplicableDates = array_column($plans, 'date');
                // sort the dates amending order
                usort($plansApplicableDates, function ($a, $b) {
                    return strtotime($a) - strtotime($b);
                });
                // load the first plan
                $policyPlansDates['upcomingAnniversaryDate'] =
                    date('Y-m-d', strtotime('-1 day', strtotime($plansApplicableDates[0])));
                // add to breakdown as well
                $policyPlansDates['breakdown'][] = [
                    'lastAnniversaryDate' => $policyPlansDates['lastAnniversaryDate'],
                    'upcomingAnniversaryDate' => $policyPlansDates['upcomingAnniversaryDate']
                ];
                // loop through the plans
                foreach ($plansApplicableDates as $index => $plan) {
                    // set next index
                    $nextIndex = $index + 1;
                    //
                    $newDate = getSystemDate(DB_DATE);
                    //
                    if (isset($plansApplicableDates[$nextIndex])) {
                        $newDate = date('Y-m-d', strtotime('-1 day', strtotime($plansApplicableDates[$nextIndex])));
                    } elseif ($newDate <= $plan) {
                        $newDate = $plan;
                    }
                    // add to breakdown
                    $policyPlansDates['breakdown'][] = [
                        'lastAnniversaryDate' => $plan,
                        'upcomingAnniversaryDate' => $newDate
                    ];
                    // check and set
                    if ($asOfToday >= $plan && $asOfToday <= $newDate) {
                        $policyPlansDates['lastAnniversaryDate'] = $plan;
                        $policyPlansDates['upcomingAnniversaryDate'] = $newDate;
                        //
                        $accrualRateInMinutes = $employeeDefaultAccrual['Plans'][$plan]['minutes'];
                    }
                }
                // check for last iteration
                $lastPolicyRecord = $policyPlansDates['breakdown'][count($policyPlansDates['breakdown']) - 1];
                // check and set to latest policy
                if ($asOfToday >= $lastPolicyRecord['upcomingAnniversaryDate']) {
                    $policyPlansDates['lastAnniversaryDate'] = $lastPolicyRecord['lastAnniversaryDate'];
                    $policyPlansDates['upcomingAnniversaryDate'] = $lastPolicyRecord['upcomingAnniversaryDate'];
                    //
                    $accrualRateInMinutes = $employeeDefaultAccrual['Plans'][$lastPolicyRecord['lastAnniversaryDate']]['minutes'];
                }
            } else {
                // add to breakdown as well
                $policyPlansDates['breakdown'][] = [
                    'lastAnniversaryDate' => $policyPlansDates['lastAnniversaryDate'],
                    'upcomingAnniversaryDate' => $policyPlansDates['upcomingAnniversaryDate']
                ];
            }
        }

        // get consumed time
        $consumedTimeInMinutes = $CI->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
            $policyId,
            $employeeId,
            $policyPlansDates['lastAnniversaryDate'],
            $policyPlansDates['upcomingAnniversaryDate']
        );

        //
        $compareDateOBJ = DateTime::createfromformat(DB_DATE, $policyPlansDates['upcomingAnniversaryDate']);
        //
        $difference = $compareDateOBJ->diff(DateTime::createFromFormat(DB_DATE, getSystemDate(DB_DATE)));
        //
        $monthsWorked = $difference->y * 12;
        $monthsWorked += $difference->m;
        // Only get pending time if carryover
        // is enabled
        // Set negative time
        if ($accruals['negativeBalanceCheck'] == 'yes' && $accruals['negativeBalanceCheck'] != 'off') {
            //
            if ($accruals['negativeBalanceType'] == 'days') {
                $negativeTimeInMinutes = $accruals['negativeBalanceVal'] * $durationInMinutes;
            } else {
                $negativeTimeInMinutes = $accruals['negativeBalanceVal'] * 60;
            }
        }
        // // Total allowed time
        if ($accrualRateInMinutes == 0) {
            $actualTime = 0;
            $allowedTime = 0;
        } else {
            $actualTime = $accrualRateInMinutes;
            $allowedTime = 0 + $accrualRateInMinutes;
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
        } elseif ($accruals['frequency'] == 'custom') {
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
        $carryOverTime = 0;
        $testArray = [];
        //
        if (!isset($accruals['carryOverCycle'])) {
            $accruals['carryOverCycle'] = 0;
        }

        if ($accruals['carryOverCheck'] == 'yes') {
            //
            if ($accruals['carryOverCycle'] == 0 || count($policyPlansDates['breakdown']) == 1) {
                //
                $totalService = getCycleTimeDifference($employeeAnniversaryDate['ad'], $employeeAnniversaryDate['upcomingAnniversaryDate']);
                //
                $totalAllowedTime = 0;
                $totalConsumedTime = 0;
                $totalManualBalanceTime = 0;
                //
                $joiningYear = date('Y', strtotime($employeeAnniversaryDate['ad']));
                //
                for ($i = 0; $i < $totalService; $i++) {
                    //
                    if ($i == 0) {
                        $yearStart = $employeeAnniversaryDate['ad'];
                        $yearEnd = preg_replace('/[0-9]{4}/', $joiningYear + ($i + 1), $employeeAnniversaryDate['ad']);
                    } else {
                        $yearStart = preg_replace('/[0-9]{4}/', $joiningYear + $i, $employeeAnniversaryDate['ad']);
                        $yearEnd = preg_replace('/[0-9]{4}/', $joiningYear + ($i + 1), $employeeAnniversaryDate['ad']);
                    }
                    //
                    $todayDate =  date('Y-m-d', strtotime($yearEnd));
                    //
                    $employeeStatus = getEmployementStatus(
                        $yearStart,
                        $accruals['newHireTime'],
                        $accruals['newHireTimeType'],
                        $durationInMinutes,
                        $todayDate
                    );
                    //
                    // See if policy implements
                    // When the policy starts from employee joining date
                    if (empty($accruals['applicableDate']) || $accruals['applicableDate'] == '0000-00-00') {
                        if ($employeeJoiningDate > $todayDate) {
                            continue;
                        }
                    } else {
                        //
                        // When the policy starts on a specific date
                        if (getFormatedDate($todayDate, '') < getFormatedDate($accruals['applicableDate'], '')) {
                            continue;
                        }
                    }
                    //
                    // Check if employee has worked for certain time
                    // Employee doesn't meet the minimum allowed time
                    if ($employeeStatus == 'permanent' && getTimeDifference($employeeJoiningDate, $applicableTime, $applicableType, $todayDate) == false) {
                        //
                        continue;
                    }
                    // Check if employee is on probation
                    if ($employeeStatus == 'probation') {
                        // Probation
                        $totalAllowedTime = $totalAllowedTime + $accruals['newHireRate'];
                    } else {
                        //
                        $cycleYear = date('Y-m-d', strtotime($yearStart));
                        //
                        foreach ($policyPlansDates['breakdown'] as $key => $cycle) {
                            //
                            $lastAnniversaryDate = date('Y-m-d', strtotime($cycle['lastAnniversaryDate']));
                            $upcomingAnniversaryDate = date('Y-m-d', strtotime($cycle['upcomingAnniversaryDate']));
                            //
                            if (($cycleYear >= $lastAnniversaryDate) && ($cycleYear <= $upcomingAnniversaryDate)) {
                                $allowedPolicyTime = 0;
                                if (isset($plans[$cycle['lastAnniversaryDate']])) {
                                    $allowedPolicyTime = $plans[$cycle['lastAnniversaryDate']]['minutes'];
                                } else {
                                    $allowedPolicyTime = ($accruals['rate'] * 60);
                                }
                                //
                                $testArray[$i]['allowedTime'] = $allowedPolicyTime;
                                $totalAllowedTime = $totalAllowedTime + $allowedPolicyTime;
                            }
                        }
                        //   
                    }
                    //
                    $consumedPolicyTime = $CI->timeoff_model->getEmployeeConsumedTimeByResetDate(
                        $policyId,
                        $employeeId,
                        $yearStart,
                        $yearEnd
                    );
                    //
                    $testArray[$i]['yearStart'] = $yearStart;
                    $testArray[$i]['yearEnd'] = $yearEnd;
                    $testArray[$i]['consumedTime'] = $consumedPolicyTime;
                    //
                    $totalConsumedTime = $totalConsumedTime + $consumedPolicyTime;
                    //
                    // check if date is for production
                    if ($todayDate > '2023-05-24') {
                        $cycleBalance = getEmployeeManualBalance(
                            $employeeId,
                            $policyId,
                            $yearStart,
                            $yearEnd
                        );
                        //
                        $totalManualBalanceTime = $totalManualBalanceTime + $cycleBalance;
                    }
                }
                //
                $totalConsumedTime = $totalConsumedTime - $consumedTimeInMinutes;
                $totalAllowedTime = $totalAllowedTime - ($allowedTime + $totalConsumedTime) + $totalManualBalanceTime;
                //
                if ($accruals['carryOverVal'] > 0) {
                    if ($accruals['carryOverVal'] < ($totalAllowedTime / 60)) {
                        $carryOverTime = $accruals['carryOverVal'] * 60;
                    } else if ($accruals['carryOverVal'] > $totalAllowedTime) {
                        $carryOverTime = $totalAllowedTime;
                    }
                } else {
                    $carryOverTime = $totalAllowedTime;
                }
            } else {
                //
                $currentCycleKey = 0;
                $currentDate = date('Y-m-d', strtotime('now'));
                //
                if (!empty($asOfToday)) {
                    $currentDate = date('Y-m-d', strtotime($asOfToday));
                }

                //
                foreach ($policyPlansDates['breakdown'] as $key => $cycle) {
                    $lastAnniversaryDate = date('Y-m-d', strtotime($cycle['lastAnniversaryDate']));
                    $upcomingAnniversaryDate = date('Y-m-d', strtotime($cycle['upcomingAnniversaryDate']));
                    //
                    if (($currentDate >= $lastAnniversaryDate) && ($currentDate <= $upcomingAnniversaryDate)) {
                        $currentCycleKey = $key;
                    } else if ($currentDate >= $lastAnniversaryDate) {
                        $currentCycleKey = $key;
                    }
                }
                //
                $totalAllowedTime = 0;
                $totalConsumedTime = 0;
                $totalManualBalanceTime = 0;
                //
                for ($i = 0; $i < $accruals['carryOverCycle']; $i++) {
                    $index = --$currentCycleKey;
                    //
                    $todayDate =  date('Y-m-d', strtotime($policyPlansDates['breakdown'][$index]['upcomingAnniversaryDate']));
                    //
                    $employeeStatus = getEmployementStatus(
                        $policyPlansDates['breakdown'][$index]['lastAnniversaryDate'],
                        $accruals['newHireTime'],
                        $accruals['newHireTimeType'],
                        $durationInMinutes,
                        $todayDate
                    );
                    //
                    // See if policy implements
                    // When the policy starts from employee joining date
                    if (empty($accruals['applicableDate']) || $accruals['applicableDate'] == '0000-00-00') {
                        if ($employeeJoiningDate > $todayDate) {
                            continue;
                        }
                    } else {
                        //
                        // When the policy starts on a specific date
                        if (getFormatedDate($todayDate, '') < getFormatedDate($accruals['applicableDate'], '')) {
                            continue;
                        }
                    }
                    //
                    // Check if employee has worked for certain time
                    // Employee doesn't meet the minimum allowed time
                    if ($employeeStatus == 'permanent' && getTimeDifference($employeeJoiningDate, $applicableTime, $applicableType, $todayDate) == false) {
                        //
                        continue;
                    }
                    //
                    $allowedPlanTime = 0;
                    //
                    if (isset($plans[$policyPlansDates['breakdown'][$index]['lastAnniversaryDate']])) {
                        $allowedPlanTime = $plans[$policyPlansDates['breakdown'][$index]['lastAnniversaryDate']]['minutes'];
                    } else {
                        $allowedPlanTime = ($accruals['rate'] * 60);
                    }
                    //
                    //
                    $consumedPlanTime = $CI->timeoff_model->getEmployeeConsumedTimeByResetDate(
                        $policyId,
                        $employeeId,
                        $policyPlansDates['breakdown'][$index]['lastAnniversaryDate'],
                        $policyPlansDates['breakdown'][$index]['upcomingAnniversaryDate']
                    );
                    //
                    // Check if employee is on probation
                    if ($employeeStatus == 'probation') {
                        // Probation
                        $totalAllowedTime = $totalAllowedTime + $accruals['newHireRate'];
                    } else {
                        //
                        $totalAllowedTime = $totalAllowedTime + $allowedPlanTime;
                    }
                    //
                    $totalConsumedTime = $totalConsumedTime + $consumedPlanTime;
                    //
                    $cycleYear = date('Y-m-d', strtotime($policyPlansDates['breakdown'][$index]['upcomingAnniversaryDate']));
                    // check if date is for production
                    if ($todayDate > '2023-05-24') {
                        $cycleBalance = getEmployeeManualBalance(
                            $employeeId,
                            $policyId,
                            $policyPlansDates['breakdown'][$index]['lastAnniversaryDate'],
                            $policyPlansDates['breakdown'][$index]['upcomingAnniversaryDate']
                        );
                        //
                        $totalManualBalanceTime = $totalManualBalanceTime + $cycleBalance;
                    }
                }
                //
                $totalAllowedTime = $totalAllowedTime - $totalConsumedTime + $totalManualBalanceTime;
                //
                if ($accruals['carryOverVal'] > 0) {
                    if ($accruals['carryOverVal'] < ($totalAllowedTime / 60)) {
                        $carryOverTime = $accruals['carryOverVal'] * 60;
                    } else if ($accruals['carryOverVal'] > $totalAllowedTime) {
                        $carryOverTime = $totalAllowedTime;
                    }
                } else {
                    $carryOverTime = $totalAllowedTime;
                }
            }
        }
        //
        // _e($allowedTime,true);
        // _e($consumedTimeInMinutes,true);
        // _e($carryOverTime,true);
        // _e($balanceInMinutes,true);
        // _e($plans,true);
        // _e($policyPlansDates,true,true);
        //accruals
        $employeeDefaultAccrual['AllowedTime'] = $allowedTime + $balanceInMinutes;
        $employeeDefaultAccrual['ConsumedTime'] = $consumedTimeInMinutes;
        $employeeDefaultAccrual['CarryOverTime'] = $carryOverTime;
        $employeeDefaultAccrual['RemainingTime'] = ($allowedTime - $consumedTimeInMinutes + $balanceInMinutes) + $carryOverTime;
        $employeeDefaultAccrual['MaxNegativeTime'] = $negativeTimeInMinutes;
        $employeeDefaultAccrual['Balance'] = $balanceInMinutes;
        $employeeDefaultAccrual['EmployementStatus'] = $employmentStatus;

        $employeeDefaultAccrual['lastAnniversaryDate'] =  $policyPlansDates['lastAnniversaryDate'];
        $employeeDefaultAccrual['upcomingAnniversaryDate'] = $policyPlansDates['upcomingAnniversaryDate'];

        //
        if ($accruals['frequency'] == 'none') {
            $employeeDefaultAccrual['RemainingTimeWithNegative'] = $employeeDefaultAccrual['RemainingTime'] + $negativeTimeInMinutes;
        } elseif ($accruals['frequency'] == 'monthly') {
            $employeeDefaultAccrual['RemainingTimeWithNegative'] = $employeeDefaultAccrual['RemainingTime'] + $negativeTimeInMinutes;
        } elseif ($accruals['frequency'] == 'yearly') {
            $employeeDefaultAccrual['RemainingTimeWithNegative'] = $employeeDefaultAccrual['RemainingTime'] + $negativeTimeInMinutes;
        } elseif ($accruals['frequency'] == 'custom') {
            $employeeDefaultAccrual['RemainingTimeWithNegative'] = $employeeDefaultAccrual['RemainingTime'] + $negativeTimeInMinutes;
        }
        //
        $employeeDefaultAccrual['IsUnlimited'] = $accrualRateInMinutes == 0 ? 1 : 0;

        // for unpaid
        if ($categoryType == '0') {
            $tmp = $employeeDefaultAccrual['UnpaidConsumedTime'];
            $employeeDefaultAccrual['UnpaidConsumedTime'] = $employeeDefaultAccrual['ConsumedTime'];
            $employeeDefaultAccrual['ConsumedTime'] = $tmp;
        }
        //
        $cp = date('Y-m-d', strtotime('now'));
        //
        if (!empty($asOfToday)) {
            $cp = date('Y-m-d', strtotime($asOfToday));
        }

        // for adding time off balance
        if (
            $cp >= $policyPlansDates['lastAnniversaryDate']
        ) {
            //
            if ($allowedTime != 0) {
                //
                $is_added = $CI->timeoff_model->checkAllowedBalanceAdded(
                    $employeeId,
                    $policyId,
                    1,
                    $policyPlansDates['lastAnniversaryDate'],
                    $allowedTime
                );
                //
                if ($is_added == 0) {
                    // This section add allowed balance of current year
                    $company_sid = $CI->timeoff_model->getEmployeeCompanySid($employeeId);
                    $policyName = $CI->timeoff_model->getPolicyNameById($policyId);
                    //
                    $added_by = getCompanyAdminSid($company_sid);
                    //
                    $balanceToAdd = array();
                    $balanceToAdd['user_sid'] = $employeeId;
                    $balanceToAdd['policy_sid'] = $policyId;
                    $balanceToAdd['added_by'] = $added_by;
                    $balanceToAdd['is_added'] = 1;
                    $balanceToAdd['added_time'] = $allowedTime;
                    $balanceToAdd['note'] = getAddPolicyBalanceNote(
                        $allowedTime,
                        $accruals['applicableTime'],
                        $accruals['applicableTimeType'],
                        $policyName,
                        $slug,
                        $durationInMinutes
                    );
                    $balanceToAdd['effective_at'] = $policyPlansDates['lastAnniversaryDate'];
                    //
                    $CI->timeoff_model->addEmployeeAllowedBalance($balanceToAdd);
                }
            }
        }

        return $employeeDefaultAccrual;
    }
}


if (!function_exists("getAddPolicyBalanceNote")) {
    function getAddPolicyBalanceNote(
        $allowedTime,
        $applicableTime,
        $applicableTimeType,
        $policyName,
        $slug,
        $durationInMinutes
    ) {
        $date = getSystemDate('M d, Y');
        $time = getSystemDate('g:i A');
        //
        $allowedTimeText = get_array_from_minutes(
            $allowedTime,
            $durationInMinutes,
            $slug
        )["text"];

        return "On {$date}, at {$time}, a balance of {$allowedTimeText} was added in accordance with policy \"{$policyName}\" after meeting the minimum applicable time of {$applicableTime} {$applicableTimeType}.";
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
            'Reason' => '',
            'lastAnniversaryDate' => '',
            'upcomingAnniversaryDate' => ''
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
        if (!empty($accruals['plans'])) {
            foreach ($accruals['plans'] as $plan) {
                //
                $planTimeInTimestamp = new DateTime(date('Y-m-d', $employeeJoiningDateOBJ->format('U')));
                $planTimeInTimestamp->add(new DateInterval('P' . ($plan['accrualType']) . '' . ($plan['accrualTypeM'] == 'years' ? 'Y' : 'M') . ''));
                $planTimeInTimestamp = $planTimeInTimestamp->format('Y-m-d');
                //
                $planTimeArray = get_array_from_minutes(($accruals['rate'] + $plan['accrualRate']) * 60, $durationInMinutes / 60, $slug);
                $r['Plans'][$planTimeInTimestamp] =
                    [
                        'time' => $planTimeArray['text'],
                        'minutes' => $planTimeArray['M']['minutes'],
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
        //
        if (checkPolicyESST($policyId) == 1) {
            //
            return processESSTPolicy(
                $policyId,
                $employeeId,
                $employementStatus,
                $employeeJoiningDate,
                $durationInMinutes,
                $accruals,
                $balanceInMinutes,
                $asOfToday,
                $slug,
                $categoryType,
                $r
            );
        }
        // for the new ESTA policy
        if (
            checkPolicyESTA($policyId) == 1 &&
            isAllowedESTAPolicy($asOfToday, $employeeJoiningDate, $accruals['employeeTypes']) == 1
        ) {
            //
            return processESTAPolicy(
                $policyId,
                $employeeId,
                $employementStatus,
                $employeeJoiningDate,
                $durationInMinutes,
                $accruals,
                $balanceInMinutes,
                $asOfToday,
                $slug,
                $categoryType,
                $r
            );
        }

        // Check if employee is on probation
        if ($employementStatus == 'probation') {
            // Probation
            $accrualRate = $accruals['newHireRate'];
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
            $r = hasAllowedTimeBeforePolicyImplements(
                $employeeId,
                $policyId,
                $employeeJoiningDate,
                $applicableTime,
                $applicableType,
                $accruals['newHireRate'],
                $categoryType,
                $employementStatus,
                $r
            );

            if ($r["allowed"]) {
                unset($r["allowed"]);
                return $r;
            }

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
        //
        //
        // make sure date is in right format i.e. Y-m-d
        $effectedDate = checkDateFormate($effectedDate) ?
            formatDateToDB($effectedDate, 'm-d-Y', DB_DATE) :
            $effectedDate;
        //
        $employeeAnniversaryDate = getEmployeeAnniversary($effectedDate, $todayDate);
        //
        if (isset($accruals['defaultFlow']) && $accruals['defaultFlow'] == 0) {
            return getEmployeeAccrualNew(
                $policyId,
                $employeeId,
                $employementStatus,
                $employeeJoiningDate,
                $durationInMinutes,
                $accruals,
                $balanceInMinutes,
                $asOfToday,
                $slug,
                $categoryType,
                $r,
                $accrualRateInMinutes,
                $employeeAnniversaryDate
            );
        }
        //
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
        // today's date
        $currentDate = getSystemDate('Y-m-d');
        //
        // get employee manual balance for current cycle
        // against policy id
        $balanceInMinutes = getEmployeeManualBalance(
            $employeeId,
            $policyId,
            $employeeAnniversaryDate['lastAnniversaryDate'],
            $employeeAnniversaryDate['upcomingAnniversaryDate'],
            $balanceInMinutes
        );
        // get consumed time in current cycle
        //
        if (
            $currentDate >= $employeeAnniversaryDate['lastAnniversaryDate'] ||
            $currentDate <= $todayDate
        ) {
            //
            $consumedTimeInMinutes = $_this->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
                $policyId,
                $employeeId,
                $employeeAnniversaryDate['lastAnniversaryDate'],
                $employeeAnniversaryDate['upcomingAnniversaryDate']
            );
            // $consumedTimeInMinutes = $_this->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
            //     $policyId,
            //     $employeeId,
            //     $employeeAnniversaryDate['lastAnniversaryDate'],
            //     $employeeAnniversaryDate['upcomingAnniversaryDate']
            // );
        } else {
            $consumedTimeInMinutes = $_this->timeoff_model->getEmployeeConsumedTime(
                $policyId,
                $employeeId,
                $accruals['method'],
                $accruals['frequency'],
                $todayDate
            );
        }
        //
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
            //
            $yearlyRates[0] = $accruals['rate'];
            foreach ($accruals['plans'] as $key => $planInfo) {
                //
                $time = $planInfo['accrualType'];
                //
                if ($planInfo['accrualTypeM'] == 'months') {
                    $time = $time / 12;
                }
                //
                $yearlyRates[$time] = $planInfo['accrualRate'] + $accruals['rate'];
            }
            //
            $totalAllowed = 0;
            $totalConsumed = 0;
            $totalManualBalanceTime = 0;
            //
            if (!isset($accruals['carryOverCycle'])) {
                $accruals['carryOverCycle'] = 0;
            }
            //
            if ($accruals['carryOverCycle'] == 0) {
                $totalService = getCycleTimeDifference($employeeAnniversaryDate['ad'], $employeeAnniversaryDate['upcomingAnniversaryDate']) - 1;
                $joiningYear = date('Y', strtotime($employeeAnniversaryDate['ad']));
                //
                for ($i = 0; $i < $totalService; $i++) {
                    //
                    if ($i == 0) {
                        $yearStart = $employeeAnniversaryDate['ad'];
                        $yearEnd = preg_replace('/[0-9]{4}/', $joiningYear + ($i + 1), $employeeAnniversaryDate['ad']);
                    } else {
                        $yearStart = preg_replace('/[0-9]{4}/', $joiningYear + $i, $employeeAnniversaryDate['ad']);
                        $yearEnd = preg_replace('/[0-9]{4}/', $joiningYear + ($i + 1), $employeeAnniversaryDate['ad']);
                    }
                    //
                    $todayDate =  date('Y-m-d', strtotime($yearEnd));
                    //
                    $employeeStatus = getEmployementStatus(
                        $yearStart,
                        $accruals['newHireTime'],
                        $accruals['newHireTimeType'],
                        $durationInMinutes,
                        $todayDate
                    );
                    //
                    // See if policy implements
                    // When the policy starts from employee joining date
                    if (empty($accruals['applicableDate']) || $accruals['applicableDate'] == '0000-00-00') {
                        //
                        $effectedDate = $employeeJoiningDate;
                        //
                        $compareDate = getFormatedDate($employeeJoiningDate, 'd-m-Y');
                        //
                        if ($employeeJoiningDate > $todayDate) {
                            continue;
                        }
                    } else {
                        //
                        $effectedDate = $accruals['applicableDate'];
                        //
                        $compareDate = getFormatedDate($accruals['applicableDate'], 'd-m-Y');
                        // When the policy starts on a specific date
                        if (getFormatedDate($todayDate, '') < getFormatedDate($accruals['applicableDate'], '')) {
                            continue;
                        }
                    }
                    //
                    // Check if employee has worked for certain time
                    // Employee doesn't meet the minimum allowed time
                    if ($employeeStatus == 'permanent' && getTimeDifference($employeeJoiningDate, $applicableTime, $applicableType, $todayDate) == false) {
                        //
                        continue;
                    }
                    // Check if employee is on probation
                    if ($employeeStatus == 'probation') {
                        // Probation
                        $totalAllowed = $totalAllowed + $accruals['newHireRate'];
                    } else {
                        $previousRate = 0;
                        $serviceYears = getCycleTimeDifference($employeeAnniversaryDate['ad'], $yearStart);
                        //
                        foreach ($yearlyRates as $rkey => $rate) {
                            if ($serviceYears < $rkey) {
                                $timeOffCycle[$i]['allowedTime'] = $previousRate;
                                $totalAllowed = $totalAllowed + $previousRate;
                                break;
                            }
                            $previousRate = $rate;
                        }
                    }
                    //
                    $consumedPolicyTime = $_this->timeoff_model->getEmployeeConsumedTimeByResetDate(
                        $policyId,
                        $employeeId,
                        $yearStart,
                        $yearEnd
                    );
                    //
                    $timeOffCycle[$i]['yearStart'] = $yearStart;
                    $timeOffCycle[$i]['yearEnd'] = $yearEnd;
                    $timeOffCycle[$i]['consumedTime'] = $consumedPolicyTime;
                    $totalConsumed = $totalConsumed + $consumedPolicyTime;
                    // check if date is for production
                    if ($todayDate > '2023-05-24') {
                        $cycleBalance = getEmployeeManualBalance(
                            $employeeId,
                            $policyId,
                            $yearStart,
                            $yearEnd
                        );
                        //
                        $totalManualBalanceTime = $totalManualBalanceTime + $cycleBalance;
                    }
                    //
                }
                //
                $totalAllowed = ($totalAllowed * 60) - $totalConsumed + $totalManualBalanceTime;
            } else {
                //
                $joiningYear = date('Y', strtotime($employeeAnniversaryDate['upcomingAnniversaryDate']));
                //
                for ($i = 0; $i < $accruals['carryOverCycle']; $i++) {
                    //
                    $yearEnd = preg_replace('/[0-9]{4}/', $joiningYear - ($i + 1), $employeeAnniversaryDate['ad']);
                    $yearStart = preg_replace('/[0-9]{4}/', $joiningYear - ($i + 2), $employeeAnniversaryDate['ad']);
                    //
                    $todayDate =  date('Y-m-d', strtotime($yearEnd));
                    //
                    $employeeStatus = getEmployementStatus(
                        $yearStart,
                        $accruals['newHireTime'],
                        $accruals['newHireTimeType'],
                        $durationInMinutes,
                        $todayDate
                    );
                    //
                    // See if policy implements
                    // When the policy starts from employee joining date
                    if (empty($accruals['applicableDate']) || $accruals['applicableDate'] == '0000-00-00') {
                        //
                        $effectedDate = $employeeJoiningDate;
                        //
                        $compareDate = getFormatedDate($employeeJoiningDate, 'd-m-Y');
                        //
                        if ($employeeJoiningDate > $todayDate) {
                            continue;
                        }
                    } else {
                        //
                        $effectedDate = $accruals['applicableDate'];
                        //
                        $compareDate = getFormatedDate($accruals['applicableDate'], 'd-m-Y');
                        // When the policy starts on a specific date
                        if (getFormatedDate($todayDate, '') < getFormatedDate($accruals['applicableDate'], '')) {
                            continue;
                        }
                    }
                    //
                    // Check if employee has worked for certain time
                    // Employee doesn't meet the minimum allowed time
                    if ($employeeStatus == 'permanent' && getTimeDifference($employeeJoiningDate, $applicableTime, $applicableType, $todayDate) == false) {
                        //
                        continue;
                    }
                    // Check if employee is on probation
                    if ($employeeStatus == 'probation') {
                        // Probation
                        $totalAllowed = $totalAllowed + $accruals['newHireRate'];
                    } else {
                        $previousRate = 0;
                        $serviceYears = getCycleTimeDifference($employeeAnniversaryDate['ad'], $yearStart);
                        //
                        foreach ($yearlyRates as $rkey => $rate) {
                            if ($serviceYears < $rkey) {
                                $timeOffCycle[$i]['allowedTime'] = $previousRate;
                                $totalAllowed = $totalAllowed + $previousRate;
                                break;
                            }
                            $previousRate = $rate;
                        }
                        //
                    }
                    //
                    $consumedPolicyTime = $_this->timeoff_model->getEmployeeConsumedTimeByResetDate(
                        $policyId,
                        $employeeId,
                        $yearStart,
                        $yearEnd
                    );
                    //
                    // check if date is for production
                    if ($todayDate > '2023-05-24') {
                        $cycleBalance = getEmployeeManualBalance(
                            $employeeId,
                            $policyId,
                            $yearStart,
                            $yearEnd
                        );
                        //
                        $totalManualBalanceTime = $totalManualBalanceTime + $cycleBalance;
                        $timeOffCycle[$i]['balance'] = $cycleBalance;
                    }
                    //
                    $timeOffCycle[$i]['yearStart'] = $yearStart;
                    $timeOffCycle[$i]['yearEnd'] = $yearEnd;
                    $timeOffCycle[$i]['consumedTime'] = $consumedPolicyTime;
                    $totalConsumed = $totalConsumed + $consumedPolicyTime;
                    //

                }
                $totalAllowed = ($totalAllowed * 60) - $totalConsumed + $totalConsumed + $totalManualBalanceTime;
            }
            //
            if ($accruals['carryOverVal'] > 0) {
                if ($accruals['carryOverVal'] < ($totalAllowed / 60)) {
                    $carryOverTime = $accruals['carryOverVal'] * 60;
                } else if ($accruals['carryOverVal'] > $totalAllowed) {
                    $carryOverTime = $totalAllowed;
                }
            } else {
                $carryOverTime = $totalAllowed;
            }
        }
        // _e($carryOverTime,true);
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
            // $allowedTime = $carryOverTimeInMinutes + $accrualRateInMinutes;
            $allowedTime = $carryOverTime + $accrualRateInMinutes;
        }
        // _e($timeOffCycle,true,true);
        // _e($allowedTime,true);
        // _e($consumedTimeInMinutes,true);
        // _e($accrualRateInMinutes,true,true);
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
            //https://www.youtube.com/watch?v=sZt108pefpI
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

        $r['lastAnniversaryDate'] =  $employeeAnniversaryDate['lastAnniversaryDate'];
        $r['upcomingAnniversaryDate'] = $employeeAnniversaryDate['upcomingAnniversaryDate'];

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

        // for adding time off balance
        if (
            $currentDate >= $employeeAnniversaryDate['lastAnniversaryDate']
        ) {
            //
            if ($allowedTime != 0) {
                //
                $is_added = $_this->timeoff_model->checkAllowedBalanceAdded(
                    $employeeId,
                    $policyId,
                    1,
                    $employeeAnniversaryDate['lastAnniversaryDate'],
                    $allowedTime
                );
                //
                if ($is_added == 0) {
                    // This section add allowed balance of current year
                    $company_sid = $_this->timeoff_model->getEmployeeCompanySid($employeeId);
                    $policyName = $_this->timeoff_model->getPolicyNameById($policyId);
                    //
                    $added_by = getCompanyAdminSid($company_sid);
                    //
                    $balanceToAdd = array();
                    $balanceToAdd['user_sid'] = $employeeId;
                    $balanceToAdd['policy_sid'] = $policyId;
                    $balanceToAdd['added_by'] = $added_by;
                    $balanceToAdd['is_added'] = 1;
                    $balanceToAdd['added_time'] = $allowedTime;
                    $balanceToAdd['note'] = getAddPolicyBalanceNote(
                        $allowedTime,
                        $accruals['applicableTime'],
                        $accruals['applicableTimeType'],
                        $policyName,
                        $slug,
                        $durationInMinutes
                    );
                    $balanceToAdd['effective_at'] = $employeeAnniversaryDate['lastAnniversaryDate'];
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

function getEffectiveDateFromApplicableTimeAdType(
    $employeeJoiningDate,
    $applicableTime,
    $applicableType
) {
    //
    if ($applicableTime == 0) {
        return $employeeJoiningDate;
    }
    //
    $initialDateTime = new DateTime($employeeJoiningDate);
    //
    $initialDateTime->modify("+{$applicableTime} {$applicableType}");
    //
    return $initialDateTime->format(DB_DATE);
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
        //
        if ($request['employeeStatus'] == 'Active') {
            $html .= '<td><strong class="text-success">Active</strong></td>';
        } else if ($request['employeeStatus'] == 'Terminated') {
            $html .= '<td><strong class="text-danger">Terminated</strong></td>';
        } else {
            $html .= '<td><strong class="text-warning">' . $request['employeeStatus'] . '</strong></td>';
        }
        //
        $html .= '  <td>' . ($request['title']) . '</td>';
        $html .= '  <td>' . ($request['timeoff_category']) . '</td>';
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
        //
        $html .= '  <td>' . ($request['allowed_time']) . '</td>';
        $html .= '  <td>' . ($request['anniversary_date']) . '</td>';
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
     * @param int    $policyId
     * @param string $policyImplementDate
     * @param string $policyNextResetDate
     * @param int    $balance Optional
     * 
     * @return int
     */
    function getEmployeeManualBalance(
        int $employeeId,
        int $policyId,
        string $policyImplementDate,
        string $policyNextResetDate,
        int $balance = 0
    ) {
        // set default balance to incoming balance
        $balanceToReturn = $balance;
        // get current date
        $currentDate = getSystemDate('Y-m-d');
        // check if date is for production
        if ($currentDate < '2023-05-24') {
            return $balanceToReturn;
        }
        // reset the value to 0
        $balanceToReturn = 0;
        // get CI instance
        $CI = &get_instance();
        // Get Company id
        $companyId = getUserColumnByWhere(['sid' => $employeeId], ['parent_sid'])['parent_sid'];
        //
        $CI->db
            ->select('
    timeoff_balances.is_added,
    effective_at,
    timeoff_balances.added_time
');
        $CI->db->join('timeoff_policies', 'timeoff_policies.sid = timeoff_balances.policy_sid', 'inner');
        $CI->db->where('timeoff_policies.company_sid', $companyId);
        $CI->db->where('timeoff_balances.user_sid', $employeeId);
        $CI->db->where('timeoff_policies.sid', $policyId);
        $CI->db->where('timeoff_policies.is_archived', 0);
        $CI->db->where('timeoff_balances.effective_at >=', $policyImplementDate);
        $CI->db->where('timeoff_balances.effective_at <=', $policyNextResetDate);
        $CI->db->where('timeoff_balances.effective_at <=', $currentDate); // current date
        $balances =  $CI->db->get('timeoff_balances')->result_array();

        // return 0 when no balance is found
        if (empty($balances)) {
            return $balanceToReturn;
        }
        // loop through the balances
        foreach ($balances as $rowBalance) {
            if ($rowBalance['is_added'] == '1') $balanceToReturn += $rowBalance['added_time']; // on add
            else $balanceToReturn -= $rowBalance['added_time']; // on subtract
        }
        // return the # of minutes
        return $balanceToReturn;
    }
}

if (!function_exists('getCycleTimeDifference')) {
    /**
     * Get time difference
     * 
     * Get employee manual balance for current cycle
     * 
     * @param string $startDate
     * @param string $endDate
     * @param string $type
     * 
     * @return int
     */
    function getCycleTimeDifference(
        string $startDate,
        string $endDate,
        string $type = 'y'
    ) {
        $start = new DateTime($startDate);
        $end = new DateTime(date('Y-m-d', strtotime('+1 day', strtotime($endDate))));
        $difference = $start->diff($end);

        return $difference->$type;
    }
}

if (!function_exists('checkPolicyESST')) {
    /**
     * Check is policy is ESST
     * 
     * @param int $policyId
     * 
     * @return int
     */
    function checkPolicyESST(
        int $policyId
    ) {
        // get CI instance
        $CI = &get_instance();
        //
        $CI->db->select('is_esst');
        $CI->db->where('sid', $policyId);
        $result = $CI->db->get('timeoff_policies')->row_array();
        //
        return $result['is_esst'];
    }
}

if (!function_exists('checkPolicyESTA')) {
    /**
     * Check is policy is ESTA
     * 
     * @param int $policyId
     * 
     * @return int
     */
    function checkPolicyESTA(
        int $policyId
    ) {
        // get CI instance
        $CI = &get_instance();
        //
        $CI->db->select('is_esta');
        $CI->db->where('sid', $policyId);
        $result = $CI->db->get('timeoff_policies')->row_array();
        //
        return $result['is_esta'];
    }
}

if (!function_exists('processESSTPolicy')) {
    /**
     * In Minnesota, employers are required to provide employees with paid sick and safe time (ESST). 
     * For every 30 hours worked, employees must earn at least one hour of paid ESST, 
     * with a maximum of 48 hours of accrued ESST per year. 
     * An employee is defined as anyone who is expected to work at least 80 hours in a year for an employer in Minnesota,
     * and this does not include independent contractors.
     */
    function processESSTPolicy(
        $policyId,
        $employeeId,
        $employementStatus,
        $employeeJoiningDate,
        $durationInMinutes,
        $accruals,
        $balanceInMinutes,
        $asOfToday,
        $slug,
        $categoryType,
        $r
    ) {
        // get CI instance
        $CI = &get_instance();
        //
        $CI->db->select('policy_start_date');
        $CI->db->where('sid', $policyId);
        $result = $CI->db->get('timeoff_policies')->row_array();
        //
        $todayDate = !empty($asOfToday) ? $asOfToday : date('Y-m-d', strtotime('now'));
        $todayDate = getFormatedDate($todayDate);
        //
        // $employeeJoiningDate = '2024-06-24';
        // $todayDate = '2024-09-24';
        //
        if ($employeeJoiningDate < $result['policy_start_date']) {
            $employeeAnniversaryDate = getEmployeeAnniversary($result['policy_start_date'], $todayDate);
        } else {
            $employeeAnniversaryDate = getEmployeeAnniversary($employeeJoiningDate, $todayDate);
        }
        //
        $joinningDifference = dateDifferenceInDays($employeeJoiningDate, $todayDate);
        //
        // check if employee work for 80 hours 8 * 10 = 80 hours so we check employee worked more the 10 days
        if ($joinningDifference < 10) {
            $r['Reason'] = 'Employee do not meet accrual of 80 hours for this policy';
            return $r;
        }
        //
        $currentYearDifference = dateDifferenceInDays($employeeAnniversaryDate['lastAnniversaryDate'], $todayDate);
        //
        $totalHoursWork = $currentYearDifference * 8;
        $earnHours = round($totalHoursWork / 30);
        //
        if ($earnHours > 48) {
            $earnHours = 48;
        }
        //
        $allowedTimeInMinutes = $earnHours * 60;
        //
        $consumedTimeInMinutes = $CI->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
            $policyId,
            $employeeId,
            $employeeAnniversaryDate['lastAnniversaryDate'],
            $employeeAnniversaryDate['upcomingAnniversaryDate']
        );
        //
        // comment out old logic which is based on employee shifts
        // $totalShiftMinutes = 0;
        // //
        // if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
        //     // todo
        // } else {
        //     $CI->db->select('start_time,end_time');
        //     $CI->db->where('shift_date >=', $employeeAnniversaryDate['lastAnniversaryDate']);
        //     $CI->db->where('shift_date <=', $todayDate);
        //     $CI->db->where('employee_sid', $employeeId);
        //     $result = $CI->db->get('cl_shifts')->result_array();
        //     //
        //     if (!$result) {
        //         $r['Reason'] = 'Employee do not have a shift between ' . $employeeAnniversaryDate['lastAnniversaryDate'] . ' and ' . $todayDate;
        //         return $r;
        //     }
        //     //
        //     foreach ($result as $row) {
        //         $startTime = DateTime::createFromFormat("H:i:s", $row['start_time']);
        //         $endTime = DateTime::createFromFormat("H:i:s", $row['end_time']);
        //         $difference = $endTime->diff($startTime);
        //         //
        //         $totalShiftMinutes += ($difference->h * 60) + $difference->i;
        //         //
        //     }
        //     //
        //     if ($totalShiftMinutes < 4800) {
        //         $r['Reason'] = 'Employee do not meet accrual of 80 hours for this policy';
        //         return $r;
        //     }
        // }
        //
        $r['AllowedTime'] = $allowedTimeInMinutes;
        $r['ConsumedTime'] = $consumedTimeInMinutes;
        $r['RemainingTime'] = $allowedTimeInMinutes - $consumedTimeInMinutes;
        $r['MaxNegativeTime'] = $r['RemainingTime'];
        $r['RemainingTimeWithNegative'] = $r['RemainingTime'];
        $r['EmployementStatus'] = $employementStatus;
        $r['lastAnniversaryDate'] =  $employeeAnniversaryDate['lastAnniversaryDate'];
        $r['upcomingAnniversaryDate'] = $employeeAnniversaryDate['upcomingAnniversaryDate'];
        //
        return $r;
    }
}

if (!function_exists('processESTAPolicy')) {
    /**
     * In Minnesota, employers are required to provide employees with paid sick and safe time (ESTA). 
     * For every 91 days worked, employees must earn at least one hour after each week of paid ESTA, 
     * with a maximum of 72 hours of accrued ESTA per year.
     */
    function processESTAPolicy(
        $policyId,
        $employeeId,
        $employementStatus,
        $employeeJoiningDate,
        $durationInMinutes,
        $accruals,
        $balanceInMinutes,
        $asOfToday,
        $slug,
        $categoryType,
        $r
    ) {
        // get CI instance
        $CI = &get_instance();
        //
        $todayDate = !empty($asOfToday) ? $asOfToday : date('Y-m-d', strtotime('now'));
        $todayDate = getFormatedDate($todayDate);

        $todayDateObj = new DateTime($todayDate);
        $employeeJoiningDateObj = new DateTime($employeeJoiningDate);
        //
        if ($todayDateObj < $employeeJoiningDateObj) {
            $r['Reason'] = "The employee doesn't meet the policy 'Effective Date'.";
            return $r;
        }
        //
        $difference = dateDifferenceInDays($employeeJoiningDate, $todayDate);
        //


        if (in_array('fulltime', $accruals['employeeType'])) {
            if ($difference >= 730) {
                $r['Reason'] = 'Employee has worked for more than 2 year.';
                return $r;
            }
        } else {

            if ($difference >= 365) {
                $r['Reason'] = 'Employee has worked for more than 1 year.';
                return $r;
            }
        }
        //
        $allowedHours = 0;
        //

        $dayscheck = 90;
        if (in_array('parttime', $accruals['employeeType'])) {

            $dayscheck = 120;
        }

        if ($difference < $dayscheck) {
            $r['Reason'] = 'Employee do not meet accrual of 90 days for this policy';
            return $r;
        } else {
            //
            $allowedHours = 40;
            $difference = $difference - $dayscheck; // this needs to be fixed
            //
            if (in_array('parttime', $accruals['employeeType'])) {
                $earningHoursStartDate = date('Y-m-d', strtotime($employeeJoiningDate . ' +121 days'));
            } else {
                $earningHoursStartDate = date('Y-m-d', strtotime($employeeJoiningDate . ' +91 days'));
            }
            //
            $startTimestamp = strtotime($earningHoursStartDate);
            $endTimestamp = strtotime($todayDate);
            //
            for ($currentDate = $startTimestamp; $currentDate <= $endTimestamp; $currentDate = strtotime("+1 day", $currentDate)) {
                if (date('w', $currentDate) == 0) { // 0 means Sunday
                    $allowedHours  = $allowedHours + 1;
                }
            }
        }
        //
        if ($allowedHours > 72) {
            $allowedHours = 72;
        }
        //
        $employeeAnniversaryDate = getEmployeeAnniversary($employeeJoiningDate, $todayDate);
        //
        $consumedTimeInMinutes = $CI->timeoff_model->getEmployeeConsumedTimeByResetDateNew(
            $policyId,
            $employeeId,
            $employeeAnniversaryDate['lastAnniversaryDate'],
            $employeeAnniversaryDate['upcomingAnniversaryDate']
        );
        //
        $allowedTimeInMinutes = $allowedHours * 60;
        //
        $r['AllowedTime'] = $allowedTimeInMinutes;
        $r['ConsumedTime'] = $consumedTimeInMinutes;
        $r['RemainingTime'] = $allowedTimeInMinutes - $consumedTimeInMinutes;
        $r['MaxNegativeTime'] = $r['RemainingTime'];
        $r['RemainingTimeWithNegative'] = $r['RemainingTime'];
        $r['EmployementStatus'] = $employementStatus;
        $r['lastAnniversaryDate'] =  $employeeAnniversaryDate['lastAnniversaryDate'];
        $r['upcomingAnniversaryDate'] = $employeeAnniversaryDate['upcomingAnniversaryDate'];
        //
        return $r;
    }
}


function hasAllowedTimeBeforePolicyImplements(
    $employeeId,
    $policyId,
    $employeeJoiningDate,
    $applicableTime,
    $applicableType,
    $accrualRate,
    $categoryType,
    $employementStatus,
    $r
) {
    // Get instance of CI
    $_this = &get_instance();
    // Load time off modal
    $_this->load->model('timeoff_model');
    //
    $effectiveDate = getEffectiveDateFromApplicableTimeAdType(
        $employeeJoiningDate,
        $applicableTime,
        $applicableType
    );
    // get consumed time between the period
    $manualBalanceProbation = getEmployeeManualBalance(
        $employeeId,
        $policyId,
        $employeeJoiningDate,
        $effectiveDate,
        0
    );

    $consumedTimeInMinutes = $_this
        ->timeoff_model
        ->getEmployeeConsumedTimeByResetDateNew(
            $policyId,
            $employeeId,
            $employeeJoiningDate,
            $effectiveDate
        );

    $accrualRate = $accrualRate + $manualBalanceProbation;

    if ($accrualRate - $consumedTimeInMinutes == 0) {
        return $r;
    }

    $r['AllowedTime'] = $accrualRate;
    $r['ConsumedTime'] = $consumedTimeInMinutes;
    $r['CarryOverTime'] = 0;
    $r['RemainingTime'] = $accrualRate - $consumedTimeInMinutes;
    $r['MaxNegativeTime'] = 0;
    $r['Balance'] = $manualBalanceProbation;
    $r['EmployementStatus'] = $employementStatus;

    $r['lastAnniversaryDate'] =  $employeeJoiningDate;
    $r['upcomingAnniversaryDate'] = $effectiveDate;
    //
    $r['IsUnlimited'] = 0;

    // for unpaid
    if ($categoryType == '0') {
        $tmp = $r['UnpaidConsumedTime'];
        $r['UnpaidConsumedTime'] = $r['ConsumedTime'];
        $r['ConsumedTime'] = $tmp;
    }

    $r["allowed"] = 1;

    return $r;
}


if (!function_exists('isAllowedESTAPolicy')) {
    function isAllowedESTAPolicy($asOfToday, $employeeJoiningDate, $employeeType)
    {
        //
        if (in_array("parttime", $employeeType)) {
            return 1;
        }
        if (!in_array("fulltime", $employeeType)) {
            return 0;
        }
        //
        $todayDate = !empty($asOfToday) ? $asOfToday : date('Y-m-d', strtotime('now'));
        $todayDate = getFormatedDate($todayDate);
        //
        $difference = dateDifferenceInDays($employeeJoiningDate, $todayDate);
        //
        return $difference >= 730 ? 0 : 1;
    }
}
