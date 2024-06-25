<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee shifts
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Shifts
 */
class Employee_shifts extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        // check if the person is logged in
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }
    }

    /**
     * Manage shifts
     */
    public function my()
    {
        // check if plus or don't have access to the module
        if (!checkIfAppIsEnabled(SCHEDULE_MODULE)) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Access denied.");
            return redirect("dashboard");
        }
        // check and get the sessions
        $loggedInEmployee = checkAndGetSession("employer_detail");
        $loggedInCompany = checkAndGetSession("company_detail");
        // set default data
        $data = [];
        $data["title"] = "My Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        $data['employee'] = $loggedInEmployee;
        //
        $employeeId = $loggedInEmployee["sid"];
        // load schedule model
        $this->load->model("v1/Shift_model", "shift_model");
        // get all active employees
        $filterStartDate = $this->input->get("start_date", true);
        $filterEndDate = $this->input->get("end_date", true);
        //
        $toggleFilter = $filterStartDate != '';
        //
        $data["filter"] = [];
        // set the mode
        $data["filter"]["mode"] = $this->input->get("mode", true) ?? "month";

        if ($data["filter"]["mode"] === "week") {
            // get the current week dates
            $weekDates = getWeekDates(false, SITE_DATE);
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
        // get the shifts
        $data["shifts"] = $this->shift_model->getShifts(
            $data["filter"],
            [$employeeId],
            true
        );
        // load time off model
        $this->load->model("timeoff_model", "timeoff_model");
        // get the leaves
        $data["leaves"] =  $this->timeoff_model
            ->getEmployeesTimeOffsInRange(
                [$employeeId],
                formatDateToDB($data["filter"]["start_date"], SITE_DATE, DB_DATE),
                formatDateToDB($data["filter"]["end_date"], SITE_DATE, DB_DATE)
            );

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["filter_toggle"] = $toggleFilter;
        $data["filterStartDate"] = $filterStartDate;
        $data["filterEndDate"] = $filterEndDate;
        $data["loadView"] = true;
        $data["sanitizedView"] = true;
        // get off and holidays
        $data["holidays"] = $this->shift_model->getCompanyHolidaysWithTitle(
            $loggedInCompany["sid"],
            $data["filter"]
        );
        // set common files bundle
        $data["pageCSS"] = [
            getPlugin("alertify", "css"),
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/ems_main"
        ], "public/v1/shifts/", "my_ems_shifts", true);

        $this->load->view('main/header', $data);
        $this->load->view('v1/schedules/my/listing');
        $this->load->view('main/footer');
    }
}
