<?php

use Twilio\TwiML\Voice\Record;

defined('BASEPATH') or exit('No direct script access allowed');

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
    }

    /**
     * Manage shifts
     */
    public function my()
    {

        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }


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


    //
    public function shiftsTrade()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }
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
        $data["title"] = "Swap Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");
        $data['employee'] = $loggedInEmployee;
        //

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["loadView"] = true;
        $data["sanitizedView"] = true;

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(getSystemDate(SITE_DATE), SITE_DATE);
        $weekEndDate = formatDateToDB(date('Y-m-d', strtotime($weekStartDate . ' + 7 days')), 'Y-m-d', SITE_DATE);
        $weekStartDate = formatDateToDB($weekStartDate, 'Y-m-d', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getEmployeeShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/trade');
        $this->load->view('main/footer');
    }


    //
    public function MyshiftsTrade()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata("message", "<strong>Error!</strong> Session expired, please re-login.");
            return redirect("login");
        }

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
        $data["title"] = "Trade Shifts | " . (STORE_NAME);
        $data["sanitizedView"] = true;
        $data["loggedInEmployee"] = $loggedInEmployee;
        $data["security_details"] = $data["securityDetails"] = db_get_access_level_details($loggedInCompany["sid"]);
        $data["session"] = $this->session->userdata("logged_in");

        $data["company_sid"] =  $loggedInCompany["sid"];
        $data["loadView"] = true;
        $data["sanitizedView"] = true;

        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),

            getPlugin("alertify", "css"),
            "v1/plugins/ms_modal/main"
        ];
        $data["pageJs"] = [
            getPlugin("alertify", "js"),
            getPlugin("validator", "js"),
            getPlugin("additionalMethods", "js"),

            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),

            "v1/plugins/ms_modal/main"
        ];
        // set bundle
        $data["appJs"] = bundleJs([
            "v1/settings/shifts/trade"
        ], "public/v1/shifts/", "trade", false);
        //

        $weekStartDate = formatDateToDB(date('m-01-Y'), 'm-d-Y', SITE_DATE);
        $weekEndDate = formatDateToDB(date('m-t-Y'), 'm-d-Y', SITE_DATE);

        $defaultRange = $weekStartDate . ' - ' . $weekEndDate;
        $dateRange = $this->input->get("date_range") ?? $defaultRange;

        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];

        //  
        $this->load->model("v1/Shift_model", "shift_model");

        $data["employeeShifts"] = $this->shift_model->getMySwapShifts($data["filter"], $loggedInEmployee['sid']);

        $this->load->view('main/header', $data);
        $this->load->view('v1/settings/shifts/my_trade');
        $this->load->view('main/footer');
    }


    //
    function swapShiftConfirm($shiftId, $toEmployeeId, $action)
    {
        $this->load->model("v1/Shift_model", "shift_model");

        $data = [];
        $data['updated_by'] = $toEmployeeId;
        $data['updated_at'] = getSystemDate();

        if ($action == 'confirm') {
            //  $shiftRecord = $this->shift_model->getShiftsRequestById($shiftId, 'awaiting confirmation');
            $shiftRecord = $this->shift_model->getShiftsRequestById($shiftId, ['confirmed', 'admin rejected', 'approved']);

            if (!empty($shiftRecord)) {
                $this->session->set_flashdata('message', '<b>Error:</b> Shift Request is not available !');
            } else {
                // Confirm Shift               

                $data['request_status'] = 'confirmed';
                $this->shift_model->updateShiftsTradeRequest($shiftId, $toEmployeeId, $data);
                $this->session->set_flashdata('message', 'Shift Request is confirmed Successfully!');

           }
        }


        if ($action == 'reject') {
            $shiftRecord = $this->shift_model->getShiftsRequestById($shiftId, 'approved');
            if (empty($shiftRecord)) {
                // Confirm Shift
                $data['request_status'] = 'rejected';
                $this->shift_model->updateShiftsTradeRequest($shiftId, $toEmployeeId, $data);
                $this->session->set_flashdata('message', 'Shift Request is rejected Successfully!');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Shift Request is not available !');
            }
        }


        $this->header = "v1/app/header";
        $this->footer = "v1/app/footer";
        //
        $this->css = "public/v1/css/app/";
        $this->js = "public/v1/js/app/";
        $this->disableMinifiedFiles = true;

        $data['pageCSS'] = [
            'v1/plugins/bootstrap5/css/bootstrap.min',
            'v1/plugins/fontawesome/css/all',
        ];
        //
        $data['appCSS'] = bundleCSS([
            'v1/app/css/theme',
            'v1/app/css/pages',
        ], $this->css, 'forgot', $this->disableMinifiedFiles);
        //
        $data['appJs'] = bundleJs([
            'v1/plugins/jquery/jquery-3.7.min', // jquery
            'v1/plugins/bootstrap5/js/bootstrap.bundle', // bootstrap 5
            'js/jquery.validate.min', // validator
            "v1/app/js/pages/home",
            "v1/app/js/pages/forgot_password_recovery",
        ], $this->js, 'forgot', $this->disableMinifiedFiles);

        $this->load->view($this->header, $data);
        $this->load->view('v1/settings/shifts/my_trade_message');
    }
}
