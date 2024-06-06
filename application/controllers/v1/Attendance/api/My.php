<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * API - Main
 * 
 * @author AutomotoHR Dev Team <www.automotohr.com>
 * @version 1.0
 * @package Shift & Clock
 */
class My extends Public_Controller
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
     * allow to create minified files
     * @var bool
     */
    private $disableCreationOfMinifyFiles;

    /**
     * CSS file creation path
     * @var string
     */
    private $css;

    /**
     * JS file creation path
     * @var string
     */
    private $js;

    /**
     * common files holder
     * @var array
     */
    private $commonFiles;

    /**
     * holds data
     * @var string
     */
    protected $data;

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
        //
        $this->css = "public/v1/css/attendance/";
        $this->js = "public/v1/js/attendance/";
        $this->commonFiles = ["css" => [], "js" => []];
        // load libraries
        $this->load->library(
            "Attendance_lib",
            "attendance_lib"
        );
        // load models
        $this->load->model(
            "v1/Attendance/My_clock_model",
            "my_clock_model"
        );
    }

    /**
     * get the clock state with job sites
     */
    public function getClockWithState()
    {
        $this->my_clock_model->getClockWithState(
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
        //
        $this->my_clock_model->markAttendance(
            $this->loggedInCompany["sid"],
            $this->loggedInEmployee["sid"],
            $post
        );
    }

    /**
     * Employees time sheet
     */
    public function timesheet()
    {
        $this->data = [
            "session" => $this->appSession,
            "loggedInEmployee" => $this->loggedInEmployee,
            "sanitizedView" => true,
            "securityDetails" => db_get_access_level_details(
                $this->loggedInEmployee["sid"]
            )
        ];
        //
        $this->data["security_details"] = $this->data["securityDetails"];
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
        $this->data["records"] = $this->my_clock_model
            ->getAttendanceWithinRange(
                $this->loggedInCompany["sid"],
                $this->loggedInEmployee["sid"],
                $startDate,
                $endDate
            );
        //
        // get company permissions
        $companyPermissions = unserialize(getUserColumnById($this->loggedInCompany["sid"], "extra_info"));
        $this->data["isEditAllowed"] = $companyPermissions["timesheet_enable_for_attendance"];
        // make the blue portal popup
        $this->data["loadView"] = true;
        //
        $this->renderView("v1/attendance/my_timesheet");
    }

    /**
     * mark attendance
     */
    public function getTimeSheetDetails(int $attendanceId, string $clockDate)
    {
        $data = [];

        if ($attendanceId != 0) {

            $data["logs"] = $this->my_clock_model
                ->getAttendanceLogsForSheet(
                    $attendanceId
                );
        }
        // _e($data,true,true);
        //
        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "v1/attendance/partials/my_timesheet",
                    $data,
                    true
                )
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
        //
        if ($attendanceId == 0) {
            $post["companyId"] = $this->loggedInCompany["sid"];
            $post["employeeId"] = isset($post["logs"][0]["employeeId"]) ? $post["logs"][0]["employeeId"] : $this->loggedInEmployee["sid"];
        }
        //
        $this->my_clock_model->processTimeSheetDetails($post);
        //
        return SendResponse(
            200,
            [
                "msg" => "You have successfully updated the clock."
            ]
        );
    }

    /**
     * Render the view in template
     *
     * @param string $path
     */
    protected function renderView(string $path)
    {
        $this->load
            ->view("main/header", $this->data)
            ->view($path)
            ->view("main/footer");
    }

    /**
     * set the common files
     *
     * @param string $filePath
     * @param string $type
     */
    protected function setCommon(string $filePath, string $type = "css"): void
    {
        $this->commonFiles[$type][] = $filePath;
    }

    /**
     * set the common files
     *
     * @param array $data passed by reference
     */
    protected function getCommon(&$data, string $page): void
    {
        // set common bundles
        // css
        $data["appCSS"] = bundleCSS(
            getCommonFiles("css"),
            $this->css,
            "common_css",
            true
        );
        // js
        $data["appJs"] = bundleJs(
            getCommonFiles("js"),
            $this->js,
            "common_js",
            true
        );
        // css bundle
        $data['appCSS'] .= bundleCSS(
            $this->commonFiles["css"],
            $this->css,
            $page,
            $this->disableCreationOfMinifyFiles
        );
        // js bundle
        $data['appJs'] .= bundleJs(
            $this->commonFiles["js"],
            $this->js,
            $page,
            $this->disableCreationOfMinifyFiles
        );
    }
}
