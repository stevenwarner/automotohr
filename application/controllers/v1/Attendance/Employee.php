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
        $this->data["startDate"] = getSystemDateInLoggedInPersonTZ(DB_DATE, "-7 days");
        $this->data["endDate"] = getSystemDateInLoggedInPersonTZ(DB_DATE);

        $this->data["title"]  = "My Attendance Dashboard :: " . (STORE_NAME);
        // get todays footprints
        $this->data["record"] = $this->clock_model
            ->getClockWithState(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                getSystemDateInLoggedInPersonTZ(DB_DATE_WITH_TIME),
                true
            );
        // make the blue portal popup
        $this->data["loadView"] = true;
        $this->renderView("v1/attendance/my_dashboard");
    }


    /**
     * Employees time sheet
     */
    public function timesheet()
    {
        $this->data["title"]  = "My time sheet :: " . (STORE_NAME);
        // add plugins
        $this->data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $this->data["pageJs"] = [
            getPlugin("validator", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];
        // set js
        $this->setCommon("v1/plugins/ms_modal/main", "css");
        $this->setCommon("v1/plugins/ms_modal/main", "js");
        $this->setCommon("v1/attendance/js/my/timesheet", "js");
        $this->getCommon($this->data, "my_timesheet");
        //
        $defaultRange = getSystemDateInLoggedInPersonTZ(SITE_DATE) . ' - ' . getSystemDateInLoggedInPersonTZ(SITE_DATE);
        $dateRange = $this->input->get("date_range") ?? $defaultRange;
        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $this->data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange,
        ];
        //
        $startDate = formatDateToDB($startDate, SITE_DATE, DB_DATE);
        $endDate = formatDateToDB($endDate, SITE_DATE, DB_DATE);
        $this->data["filter"]['startDateDB'] = $startDate;
        $this->data["filter"]['endDateDB'] = $endDate;
        // load schedule model
        $this->load->model("Timeoff_model", "timeoff_model");
        // get employee shifts
        $this->data["leaves"] = $this->timeoff_model
            ->getEmployeeTimeOffsInRange(
                $this->loggedInEmployee["sid"],
                $startDate,
                $endDate
            );
        //
        $this->data["records"] = $this->clock_model
            ->getAttendanceWithinRange(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                $startDate,
                $endDate
            );
        //
        // load attendance settings model
        $this->load->model(
            "v1/Attendance/Clock_setting_model",
            "clock_setting_model"
        );
        // get company permissions
        $this->data["isEditAllowed"] =
            $this->clock_setting_model
                ->getColumn()["controls"]["employee_can_manipulate_time_sheet"] == "1";
        // make the blue portal popup
        $this->data["loadView"] = true;
        $this->renderView("v1/attendance/my_timesheet");
    }
}
