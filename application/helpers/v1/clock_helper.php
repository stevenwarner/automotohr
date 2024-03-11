<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Attendance helpers
 *
 * @author AutomotoHR Dev Team
 * @version 1.0
 */

if (!function_exists("getClockHoursForSettings")) {
    /**
     * Creates the select options for daily limit
     * in clock Settings
     *
     * @param string $selectedOption
     * @return array
     */
    function getClockHoursForSettings(string $selectedOption = ""): string
    {
        // set the start point
        $start = 2;
        // set the end point
        $ends = 23.5;
        // set the current point
        $current = $start;
        // set the options string
        $options = "";
        //
        while ($current <= $ends) {
            // create an option
            $options .= '<option ' . ($current == $selectedOption ? "selected" : "") . ' value="' . ($current) . '">' . (strpos($current, ".") !== false ? str_replace(".5", "", $current) . ":30" : $current) . ' Hours</option>';
            // increment by .5
            $current += .5;
        }
        // return the data
        return $options;
    }
}
