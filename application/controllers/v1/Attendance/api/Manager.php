<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * API - Main
 * 
 * @author AutomotoHR Dev Team <www.automotohr.com>
 * @version 1.0
 * @package Shift & Clock
 */
class Manager extends Public_Controller
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
        //
        // load libraries
        $this->load->library(
            "Attendance_lib",
            "attendance_lib"
        );
        // load models
        $this->load->model(
            "v1/Attendance/Manager_clock_model",
            "manager_clock_model"
        );
    }

    /**
     * logged in person time sheet
     * checked
     */
    public function timesheet()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $this->data["employee"] = $this->loggedInEmployee;
        $this->data["session"] = checkAndGetSession("all");

        // add plugins
        $this->data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $this->data["pageJs"] = [
            // Google maps
            "https://maps.googleapis.com/maps/api/js?key=" . getCreds("AHR")->GoogleAPIKey . "",
            getPlugin("google_map", "js"),
            getPlugin("validator", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        // set js
        $this->setCommon("v1/plugins/ms_modal/main", "css");
        $this->setCommon("v1/plugins/ms_modal/main", "js");
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/timesheet", "js");
        $this->getCommon($this->data, "timesheet");
        //
        $this->data["load_view"] = false;
        $this->data["sanitizedView"] = true;
        $this->data["title"] = "Overview";
        $this->data['security_details'] = $this->data['securityDetails'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $this->data["sidebarPath"] = $this->sidebarPath;
        $this->data["mainContentPath"] = "v1/attendance/timesheet";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $defaultRange = getSystemDate(SITE_DATE) . ' - ' . getSystemDate(SITE_DATE);
        $dateRange = $this->input->get("date_range") ?? $defaultRange;
        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $this->data["filter"] = [
            "employeeId" => $this->input->get("employees", true) ?? "",
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];
        $this->data["records"] = [];
        //
        $startDate = formatDateToDB($startDate, SITE_DATE, DB_DATE);
        $endDate = formatDateToDB($endDate, SITE_DATE, DB_DATE);
        $this->data["filter"]['startDateDB'] = $this->data["startDate"] = $startDate;
        $this->data["filter"]['endDateDB'] = $this->data["endDate"] = $endDate;
        $this->data['user_sid'] = 0;
        if ($this->data["filter"]["employeeId"]) {
            $this->data['user_sid'] = $this->data["filter"]["employeeId"];
            //
            $this->data["records"] = $this->manager_clock_model
                ->getAttendanceWithinRange(
                    $this->loggedInCompany["sid"],
                    $this->data["filter"]["employeeId"],
                    $startDate,
                    $endDate
                );
            // load schedule model
            $this->load->model("Timeoff_model", "timeoff_model");
            // get employee shifts
            $this->data["leaves"] = $this->timeoff_model
                ->getEmployeeTimeOffsInRange(
                    $this->data["filter"]["employeeId"],
                    $startDate,
                    $endDate
                );
            //
            // get the employee worked shifts
            $this->data["clockArray"] = $this->manager_clock_model->calculateTimeWithinRange(
                $this->data["filter"]["employeeId"],
                $startDate,
                $endDate
            );
            //
            if ($this->data["clockArray"]["periods"]) {
                foreach ($this->data["clockArray"]["periods"] as $pkey => $period) {
                    $this->data["clockArray"]["periods"][$period["date"]] = $period;
                    unset($this->data["clockArray"]["periods"][$pkey]);
                }
            }
        }
        //
        $this->data["employees"] = $this->manager_clock_model
            ->getEmployees(
                $this->loggedInCompany["sid"]
            );
        //  
        $this->renderView("v1/employer/main");
    }

    /**
     * mark attendance
     */
    public function getTimeSheetDetails(int $attendanceId, string $clockDate)
    {
        if ($attendanceId != 0) {

            $this->data["logs"] = $this->manager_clock_model
                ->getAttendanceLogsForSheet(
                    $attendanceId
                );
        }
        //
        return SendResponse(
            200,
            [
                "view" =>
                $this->load->view(
                    "v1/attendance/partials/single_timesheet",
                    $this->data,
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
        $this->manager_clock_model->processTimeSheetDetails($post);
        //
        return SendResponse(
            200,
            [
                "msg" => "You have successfully updated the clock."
            ]
        );
    }

    /**
     * logged in employees locations
     */
    public function locations()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $this->data["employee"] = $this->loggedInEmployee;
        $this->data["session"] = checkAndGetSession("all");

        // add plugins
        $this->data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        //
        $this->data["pageJs"] = [
            // Google maps
            "https://maps.googleapis.com/maps/api/js?key=" . getCreds("AHR")->GoogleAPIKey . "",
            getPlugin("google_map", "js"),
            getPlugin("validator", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        $this->setCommon("v1/plugins/select2/select2.min", "css");
        $this->setCommon("v1/plugins/select2/select2.min", "js");
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/locations", "js");
        $this->getCommon($this->data, "employee_locations");

        //
        $this->data["load_view"] = false;
        $this->data["sanitizedView"] = true;
        $this->data["title"] = "Overview";
        $this->data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $this->data["sidebarPath"] = $this->sidebarPath;
        $this->data["mainContentPath"] = "v1/attendance/employees_locations";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $defaultDate = getSystemDate(SITE_DATE);
        $selectedDate = $this->input->get("clocked_in_date") ?? $defaultDate;
        //
        $clockedInDate = formatDateToDB($selectedDate, SITE_DATE, DB_DATE);
        //
        $this->data["filter"] = [
            "employees" => $this->input->get("employees", true) ?? ["all"]
        ];
        $this->data["filter"]['clockedInDate'] = $clockedInDate;
        $this->data["filter"]['selectedDate'] = $selectedDate;
        //
        $this->data["markers"] = $this->manager_clock_model
                ->getClockedInEmployees(
                    $this->loggedInCompany["sid"],
                    $clockedInDate,
                    $this->data["filter"]["employees"]
                ); 
        //          
        $this->data["employees"] = $this->manager_clock_model->getEmployees($this->loggedInCompany["sid"]);        
        //           
        $this->renderView("v1/employer/main");
    }

    public function location_detail()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $this->data["employee"] = $this->loggedInEmployee;
        $this->data["session"] = checkAndGetSession("all");

        // add plugins
        $this->data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        //
        $this->data["pageJs"] = [
            // Google maps
            "https://maps.googleapis.com/maps/api/js?key=" . getCreds("AHR")->GoogleAPIKey . "",
            getPlugin("google_map", "js"),
            getPlugin("validator", "js"),
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        $this->setCommon("v1/plugins/ms_modal/main", "css");
        $this->setCommon("v1/plugins/ms_modal/main", "js");
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/location_detail_1", "js");
        $this->getCommon($this->data, "location_detail");

        //
        $this->data["load_view"] = false;
        $this->data["sanitizedView"] = true;
        $this->data["title"] = "Overview";
        $this->data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $this->data["sidebarPath"] = $this->sidebarPath;
        $this->data["mainContentPath"] = "v1/attendance/location_detail";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $selectedDate = $this->input->get("date");
        $employeeId = $this->input->get("sid");
        //
        $clockedInDate = formatDateToDB($selectedDate, SITE_DATE, DB_DATE);
        $this->data['clockedInDate'] = $clockedInDate;
        $this->data['employeeInfo'] = get_employee_profile_info($employeeId);
        //
        $this->data["history"] = $this->manager_clock_model
                ->getEmployeeLoginHistory(
                    $employeeId,
                    $clockedInDate,
                ); 
        //
        // get the employee worked shifts
        $this->data["clockArray"] = $this->manager_clock_model->calculateTimeWithinRange(
            $employeeId,
            $clockedInDate,
            $clockedInDate
        ); 
        // //        
        // _e($this->data["history"],true);           
        //
        $this->renderView("v1/employer/main");
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
