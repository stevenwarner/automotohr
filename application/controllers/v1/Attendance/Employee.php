<?php defined("BASEPATH") || exit("Access is denied.");
// load the base controller
loadUpController("v1/Attendance/Base");
/**
 * Employee
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Employee extends Base
{
    /**
     * main entry
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
    }

    /**
     * Employees dashboard
     */
    public function dashboard()
    {
        // add plugins
        $this->data["pageJs"] = [
            // Google maps
            "https://maps.googleapis.com/maps/api/js?key=" . getCreds("AHR")->GoogleAPIKey . "",
            getPlugin("google_map", "js"),
            // high charts
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/highcharts.min.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/data.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/exporting.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/accessibility.js?v=3.0"),
        ];
        // set js
        $this->setCommon("v1/attendance/js/my/dashboard", "js");
        $this->getCommon($this->data, "my_dashboard");
        $this->data["startDate"] = getSystemDate(DB_DATE, "-7 days");
        $this->data["endDate"] = getSystemDate(DB_DATE);

        $this->data["title"]  = "Overview";
        // get todays footprints
        $this->data["record"] = $this->clock_model
            ->getClockWithState(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                getSystemDate(DB_DATE),
                true
            );
        // make the blue portal popup
        $this->data["loadView"] = true;
        $this->renderView("v1/attendance/my_dashboard");
    }


    /**
     * Employees timesheet
     */
    public function timesheet()
    {
        // add plugins
        $this->data["pageCSS"] = [
            getPlugin("timepicker", "css"),
        ];
        $this->data["pageJs"] = [
            getPlugin("validator", "js"),
            getPlugin("timepicker", "js"),
        ];
        // set js
        $this->setCommon("v1/plugins/ms_modal/main", "css");
        $this->setCommon("v1/plugins/ms_modal/main", "js");
        $this->setCommon("v1/attendance/js/my/timesheet", "js");
        $this->getCommon($this->data, "my_timesheet");
        // get todays date
        $this->data["filter"] = [
            "year" => $this->input->get("year", true) ?? getSystemDate("Y"),
            "month" => $this->input->get("month", true) ?? getSystemDate("m"),
        ];
        $this->data["filter"]["startDate"] = $this->data["filter"]["year"] . "-" . $this->data["filter"]["month"] . "-01";
        $this->data["filter"]["endDate"] = getSystemDate($this->data["filter"]["year"] . "-" . $this->data["filter"]["month"] . "-t");
        // load schedule model
        $this->load->model("Timeoff_model", "timeoff_model");
        // get employee shifts
        $this->data["leaves"] = $this->timeoff_model
            ->getEmployeeTimeOffsInRange(
                $this->loggedInEmployee["sid"],
                $this->data["filter"]["startDate"],
                $this->data["filter"]["endDate"]
            );
        //
        $this->data["records"] = $this->clock_model
            ->getAttendanceWithinRange(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                $this->data["filter"]["startDate"],
                $this->data["filter"]["endDate"]
            );

        // make the blue portal popup
        $this->data["loadView"] = true;
        $this->renderView("v1/attendance/my_timesheet");
    }
}
