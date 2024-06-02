<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * API - Main
 * 
 * @author AutomotoHR Dev Team <www.automotohr.com>
 * @version 1.0
 * @package Shift & Clock
 */
class Main extends Public_Controller
{
    /**
     * set logged in session
     * @var array
     */
    protected $appSession;

    /**
     * set logged in person details
     * @var array
     */
    protected $loggedInEmployee;

    /**
     * set logged in company details
     * @var array
     */
    protected $loggedInCompany;

    /**
     * main entry point
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
        // check wether the use is logged in or not
        $this->appSession = checkAndGetSession("all");
        $this->loggedInEmployee = $this->appSession["employer_detail"];
        $this->loggedInCompany = $this->appSession["company_detail"];
        // load libraries
        $this->load->library(
            "Attendance_lib",
            "attendance_lib"
        );
        // load models
        $this->load->model(
            "v1/Attendance/Clock_model",
            "clock_model"
        );
    }

    /**
     * get the clock state with job sites
     */
    public function getClockWithState()
    {
        $this->clock_model->getClockWithState(
            $this->loggedInCompany["sid"],
            $this->loggedInEmployee["sid"],
        );
    }

    /**
     * mark attendance
     */
    public function markAttendance()
    {
        // convert to post
        $_POST = json_decode(file_get_contents("php://input"), true);
        // set rules
        $this->form_validation->set_rules("type", "Mark type", "xss_clean|required");
        $this->form_validation->set_rules("latitude", "Latitude", "xss_clean|required");
        $this->form_validation->set_rules("longitude", "Longitude", "xss_clean|required");
        // run validation
        if (!$this->form_validation->run()) {
            return SendResponse(
                400,
                getFormErrors()
            );
        }
        //
        $post = $this->input->post(null, true);
        $latLon = $this->attendance_lib->getRandomLatLon();
        //
        if (isDevServer()) {
            // set lat long for demo purposes
            $post["latitude"] = $latLon["lat"];
            $post["longitude"] = $latLon["lng"];
        }
        _e($post,true,true);
        //
        $this->clock_model->markAttendance(
            $this->loggedInCompany["sid"],
            $this->loggedInEmployee["sid"],
            $post
        );
    }

    /**
     * mark attendance
     */
    public function getWorkedHoursForGraph()
    {
        $get = $this->input->get(null, true);
        // set start date
        $startDate = $get["start_date"] ?? getSystemDateInLoggedInPersonTZ(SITE_DATE, "-7 days");
        $endDate = $get["end_date"] ?? getSystemDateInLoggedInPersonTZ(SITE_DATE);
        //
        $this->clock_model
            ->getWorkedHoursForGraph(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                $startDate,
                $endDate
            );
    }

    /**
     * mark attendance
     */
    public function getMyTodaysFootprints()
    {
        // get todays footprints
        $data["record"] = $this->clock_model
            ->getClockWithState(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                getSystemDateInLoggedInPersonTZ(DB_DATE_WITH_TIME),
                true
            );
        if ($data["record"]["state"]) {
            // get the logs array
            $data["logs"] = $this->clock_model
                ->getAttendanceFootprints(
                    $data["record"]["reference"],
                    $data["record"]["allowed_breaks"]
                );
        }

        return SendResponse(200, [
            "logs" => $data["logs"]
        ]);
    }

    /**
     * mark attendance
     */
    public function getTimeSheetDetails(int $attendanceId, string $clockDate)
    {
        $data = [];

        if ($attendanceId != 0) {

            $data["logs"] = $this->clock_model
                ->getAttendanceLogsForSheet(
                    $attendanceId
                );
        }

        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "v1/attendance/partials/single_timesheet",
                    $data,
                    true
                )
            ]
        );
    }

    /**
     * mark attendance
     */
    public function deleteTimeSheetLogById(int $logId)
    {
        $this->clock_model->deleteAttendanceLogById($logId);

        return SendResponse(
            200,
            [
                "msg" => "Deleted."
            ]
        );
    }

    /**
     * mark attendance
     */
    public function processTimeSheetDetails(int $attendanceId, string $clockDate)
    {
        $post = $this->input->post(null, true);
        $post["sid"] = $attendanceId;
        $post["clockDate"] = $clockDate;

        if ($attendanceId == 0) {
            $post["companyId"] = $this->loggedInCompany["sid"];
            $post["employeeId"] = isset($post["logs"][0]["employeeId"]) ? $post["logs"][0]["employeeId"] : $this->loggedInEmployee["sid"];
        }
        $this->clock_model->processTimeSheetDetails($post);

        return SendResponse(
            200,
            [
                "msg" => "You have successfully updated the clock."
            ]
        );
    }

    /**
     * mark attendance
     */
    public function processTimeSheetStatus()
    {
        $post = $this->input->post(null, true);

        $this->clock_model->processTimeSheetStatus($post);

        return SendResponse(
            200,
            [
                "msg" => "You have successfully " . ($post["status"] == 1 ? "Approved" : "UnApproved") . " the selected time slots."
            ]
        );
    }

    /**
     * mark attendance
     */
    public function getTimeSheetHistory(int $id)
    {

        $data = [];

        $logs =  $this->clock_model->getTimeSheetHistory($id);
        $data["history"] = $logs["logs"];


        if ($data["history"]) {
            $tmp = [];
            foreach ($data["history"] as $v0) {
                if ($v0['jobSite']) {
                    if (!$tmp[$v0["jobSite"]["site_name"]]) {
                        $tmp[$v0["jobSite"]["site_name"]] = [];
                    }

                    //
                    $tmp[$v0["jobSite"]["site_name"]][] = $v0;
                } else {
                    $tmp["other"][] = $v0;
                }
            }

            $data["history"] = $tmp;
        }
        //
        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "v1/attendance/partials/timesheet_history",
                    $data,
                    true
                ),
                "locations" => $logs["pair_locations"]
            ]
        );
    }
}
