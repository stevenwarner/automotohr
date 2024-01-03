<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Date helper
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package AutomotoHR
 * 
 * @method getDatesBetween get the date difference between two dates
 * @method getPastDate     get the past date
 */

if (!function_exists("getDatesBetween")) {
    /**
     * get the date difference between two dates
     *
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    function getDatesBetween(string $startDate, string $endDate): array
    {
        $dates = [];

        $currentDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }
}

if (!function_exists("getPastDate")) {
    /**
     * get the past date
     *
     * @param string $startDate
     * @param array  $holidayDates
     * @param int    $numberOfDays
     * @return string
     */
    function getPastDate(string $startDate, array $holidayDates, int $numberOfDays)
    {
        // create object
        $startDateObj = new DateTime($startDate);
        $startDateObj->modify("-$numberOfDays days");
        // check if the date is sunday or saturday
        if (
            in_array($startDateObj->format("D"), ["Sun", "Sat"])
            || in_array($startDateObj->format("Y-m-d"), $holidayDates)
        ) {
            // re-call the event
            return getPastDate(
                $startDateObj->format("Y-m-d"),
                $holidayDates,
                1
            );
        }
        //
        return $startDateObj->format("Y-m-d");
    }
}
