<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee shifts
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Shifts
 */
class Subordinate_shifts extends Public_Controller
{

    private $loggedInEmployee;
    private $loggedInCompany;
    private $appSession;
    public function __construct()
    {
        parent::__construct();
        // check if the person is logged in
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }

        // check and get the sessions
        $this->appSession = checkAndGetSession();
        $this->loggedInEmployee = $this->appSession["employer_detail"];
        $this->loggedInCompany = $this->appSession["company_detail"];
    }

    //
    public function loggedInPersonSubOrdinateShifts()
    {
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // set default data
        $data = [];
        $data['title'] = "My Subordinates Shifts | " . STORE_NAME;
        $data['employee'] = $this->loggedInEmployee;
        $data['loggedInEmployee'] = $this->loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($this->loggedInCompany["sid"]);
        $data["session"] = $this->appSession;
        $data["loadView"] = true;
        $data["sanitizedView"] = true;
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";
        $data["filter"]["departments"] = $this->input->get("departments", true) ?? [];
        $data["filter"]["teams"] = $this->input->get("teams", true) ?? [];
        $data["filter"]["employees"] = $this->input->get("employees", true) ?? [];

        //
        $data["filter"]["departments"] = in_array("all", $data["filter"]["departments"]) ? [] : $data["filter"]["departments"];
        $data["filter"]["teams"] = in_array("all", $data["filter"]["teams"]) ? [] : $data["filter"]["teams"];
        $data["filter"]["employees"] = in_array("all", $data["filter"]["employees"]) ? [] : $data["filter"]["employees"];
        //
        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
            // _e($weekDates, true, true);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates['end_date'];
        } elseif ($data["filter"]["mode"] === "two_week") {
            // get the current week dates
            $weekDates = getWeekDates(true, SITE_DATE);
            // set start date
            $data["filter"]["start_date"] = $this->input->get("start_date", true) ??
                $weekDates["current_week"]['start_date'];
            // set the end date
            $data["filter"]["end_date"] = $this->input->get("end_date", true) ??
                $weekDates["next_week"]['end_date'];
        } else {
            $data["filter"]["month"] = $this->input->get("month", true) ?? getSystemDate("m");
            $data["filter"]["year"] = $this->input->get("year", true) ?? getSystemDate("Y");
            //
            $data["filter"]["start_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "01/m/Y");
            //
            $data["filter"]["end_date"] = getDateFromYearAndMonth($data["filter"]["year"], $data["filter"]["month"], "t/m/Y");
        }
        // get the subordinates
        $mySubOrdinatesWithDepartmentAndTeams = $this->shift_model
            ->getEmployeeSubOrdinatesWithDepartmentAndTeams(
                $this->loggedInEmployee["sid"],
                $data["filter"]["departments"],
                $data["filter"]["teams"],
                $data["filter"]["employees"]
            );
        // get the subordinates
        $mySubOrdinatesWithDepartmentAndTeamsWithoutFilter = $this->shift_model
            ->getEmployeeSubOrdinatesWithDepartmentAndTeams(
                $this->loggedInEmployee["sid"],
                [],
                [],
                []
            );

        // set the departments
        $data["departments"] = $mySubOrdinatesWithDepartmentAndTeamsWithoutFilter["departments"];
        $data["teams"] = $mySubOrdinatesWithDepartmentAndTeamsWithoutFilter["teams"];
        $data["employees"] = $mySubOrdinatesWithDepartmentAndTeamsWithoutFilter["employees"];

        // get the employee details
        $data["subordinateEmployees"] = $this->shift_model->getEmployeeDetailsByIds(
            $data["employees"],
            $this->loggedInCompany["sid"]
        );

        $employeeIds = $mySubOrdinatesWithDepartmentAndTeams["employees"];
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            $employeeIds,
            true
        );
        //
        // get the employees unavailability
        $data["unavailability"] = $this->shift_model->getUnavailability(
            $data["filter"],
            $employeeIds
        );
        //
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] = $employeeIds ? $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                $employeeIds,
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            ) : [];
        // get off and holidays
        $data["holidays"] = $this->shift_model
            ->getCompanyHolidaysWithTitle(
                $this->loggedInCompany["sid"],
                $data["filter"]
            );
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
            getPlugin("select2", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
            getPlugin("select2", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/ems_subordinate_main"
        ], "public/v1/shifts/", "ems_subordinate_main", true);
        //

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/subordinate_listing');
        $this->load->view('main/footer');
    }
}
