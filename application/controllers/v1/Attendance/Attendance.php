<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * Attendance
 * @author AutomotoHR Dev Team <www.automotohr.com>
 * @version 1.0.0
 * @package Attendance
 */
class Attendance extends Public_Controller
{
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
     * holds sidebar path
     * @var string
     */
    private $sidebarPath;
    /**
     * main entry point
     */
    public function __construct()
    {
        parent::__construct();
        //
        $this->loggedInEmployee = checkAndGetSession("employer_detail");
        $this->loggedInCompany = checkAndGetSession("company_detail");
        //
        $this->disableCreationOfMinifyFiles = true;
        //
        $this->css = "public/v1/css/attendance/";
        $this->js = "public/v1/js/attendance/";
        // load the library
        $this->load->library('Api_auth');
        $this->commonFiles = ["css" => [], "js" => []];
        //
        $this->sidebarPath = "v1/attendance/sidebar";
    }

    // Employer

    /**
     * logged in person time sheet
     */
    public function dashboard()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $data["employee"] = $this->loggedInEmployee;
        $data["session"] = checkAndGetSession("all");
        //
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/dashboard", "js");
        $this->getCommon($data, "overview");
        // //
        $data["filter"] = [
            "date" => $this->input->get("date", true) ?? getSystemDate(SITE_DATE)
        ];
        //
        $data["load_view"] = false;
        $data["sanitizedView"] = true;
        $data["title"] = "Overview";
        $data['security_details'] =
            $data['securityDetails'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/dashboard";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        // get the people
        $data["records"] = $this->clock_model
            ->getPeopleClocks(
                $this->loggedInCompany["sid"],
                formatDateToDB(
                    $data["filter"]["date"],
                    SITE_DATE,
                    DB_DATE
                )
            );
        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer");
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
        $data["employee"] = $this->loggedInEmployee;
        $data["session"] = checkAndGetSession("all");

        // add plugins
        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
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
        $this->getCommon($data, "timesheet");
        //
        $data["load_view"] = false;
        $data["sanitizedView"] = true;
        $data["title"] = "Overview";
        $data['security_details'] = $data['securityDetails'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/timesheet";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $defaultRange = getSystemDate(SITE_DATE) . ' - ' . getSystemDate(SITE_DATE);
        $dateRange = $this->input->get("date_range") ?? $defaultRange;
        //
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        $data["filter"] = [
            "employeeId" => $this->input->get("employees", true) ?? "",
            "startDate" => $startDate,
            "endDate" => $endDate,
            "dateRange" => $dateRange
        ];
        $data["records"] = [];
        //
        $startDate = formatDateToDB($startDate, SITE_DATE, DB_DATE);
        $endDate = formatDateToDB($endDate, SITE_DATE, DB_DATE);
        $data["filter"]['startDateDB'] = $data["startDate"] = $startDate;
        $data["filter"]['endDateDB'] = $data["endDate"] = $endDate;
        $data['user_sid'] = 0;
        if ($data["filter"]["employeeId"]) {
            $data['user_sid'] = $data["filter"]["employeeId"];
            //
            $data["records"] = $this->clock_model
                ->getAttendanceWithinRange(
                    $this->loggedInCompany["sid"],
                    $data["filter"]["employeeId"],
                    $startDate,
                    $endDate
                );
            // load schedule model
            $this->load->model("Timeoff_model", "timeoff_model");
            // get employee shifts
            $data["leaves"] = $this->timeoff_model
                ->getEmployeeTimeOffsInRange(
                    $data["filter"]["employeeId"],
                    $startDate,
                    $endDate
                );
            //
            // load the clock model
            $this->load->model("v1/Attendance/Clock_model", "clock_model");
            // get the employee worked shifts
            $data["clockArray"] = $this->clock_model->calculateTimeWithinRange(
                $data["filter"]["employeeId"],
                $startDate,
                $endDate
            ); 
            //
            if ($data["clockArray"]["periods"]) {
                foreach ($data["clockArray"]["periods"] as $pkey => $period) {
                    $data["clockArray"]["periods"][$period["date"]] = $period;
                    unset($data["clockArray"]["periods"][$pkey]);
                }
            }  
        }
        //
        $data["employees"] = $this->clock_model
            ->getEmployees(
                $this->loggedInCompany["sid"]
            );   
        //  
        // echo $this->db->last_query();
        // _e($data["employees"],true,true);
        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer");
    }

    /**
     * logged in person time sheet
     */
    public function timesheets()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $data["employee"] = $this->loggedInEmployee;
        $data["session"] = checkAndGetSession("all");

        // add plugins
        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        $data["pageJs"] = [
            getPlugin("timepicker", "js"),
            getPlugin("daterangepicker", "js"),
        ];

        $this->setCommon("v1/plugins/select2/select2.min", "css");
        $this->setCommon("v1/plugins/select2/select2.min", "js");
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/timesheets", "js");
        $this->getCommon($data, "timesheets");

        //
        $data["load_view"] = false;
        $data["sanitizedView"] = true;
        $data["title"] = "Overview";
        $data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/employees_timesheets";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $defaultRange = getSystemDate(SITE_DATE) . ' - ' . getSystemDate(SITE_DATE);
        $dateRange = $this->input->get("date_range") ?? $defaultRange;
        $tmp = explode("-", $dateRange);
        $startDate = trim($tmp[0]);
        $endDate = trim($tmp[1]);
        // get todays date
        //
        $data["filter"] = [
            "employees" => $this->input->get("employees", true) ?? ["all"],
            "departments" => $this->input->get("department", true) ?? "all",
            "teams" => $this->input->get("teams", true) ?? ["all"],
            "jobTitles" => $this->input->get("jobTitle", true) ?? ["all"],
        ];
        //
        $startDate = formatDateToDB($startDate, SITE_DATE, DB_DATE);
        $endDate = formatDateToDB($endDate, SITE_DATE, DB_DATE);
        //
        $data["filter"]['startDateDB'] = $data["startDate"] = $startDate;
        $data["filter"]['endDateDB'] = $data["endDate"] = $endDate;
        $data["filter"]["dateRange"] = $dateRange;
        //
        $data["filterEmployees"] = $this->clock_model
                ->getFilterEmployees(
                    $this->loggedInCompany["sid"],
                    $data["filter"]["employees"],
                    $data["filter"]["teams"],
                    $data["filter"]["departments"],
                    $data["filter"]["jobTitles"]
                );       
        //
        if ($data["filterEmployees"]) {
            foreach ($data["filterEmployees"] as $ekey => $employee) {
                // get the employee worked shifts
                $clockArray = $this->clock_model->calculateTimeWithinRange(
                    $employee['sid'],
                    $startDate,
                    $endDate
                ); 
                //
                $data["filterEmployees"][$ekey]['clockArray'] = $clockArray;
            }
        }                
        //
        $data["employees"] = $this->clock_model->getEmployees($this->loggedInCompany["sid"]);
        $data['departments'] = $this->clock_model->getDepartments($this->loggedInCompany["sid"]); 
        $data['teams'] = $this->clock_model->getTeams($this->loggedInCompany["sid"], $data['departments']);
        $data['jobTitles'] = $this->clock_model->getJobTitles();
        //   
        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer");
    }

    /**
     * logged in employees locations
     */
    public function locations()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $data["employee"] = $this->loggedInEmployee;
        $data["session"] = checkAndGetSession("all");

        // add plugins
        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        //
        $data["pageJs"] = [
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
        $this->getCommon($data, "employee_locations");

        //
        $data["load_view"] = false;
        $data["sanitizedView"] = true;
        $data["title"] = "Overview";
        $data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/employees_locations";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $defaultDate = getSystemDate(SITE_DATE);
        $selectedDate = $this->input->get("clocked_in_date") ?? $defaultDate;
        //
        $clockedInDate = formatDateToDB($selectedDate, SITE_DATE, DB_DATE);
        //
        $data["filter"] = [
            "employees" => $this->input->get("employees", true) ?? ["all"]
        ];
        $data["filter"]['clockedInDate'] = $clockedInDate;
        $data["filter"]['selectedDate'] = $selectedDate;
        //
        $data["markers"] = $this->clock_model
                ->getClockedInEmployees(
                    $this->loggedInCompany["sid"],
                    $clockedInDate,
                    $data["filter"]["employees"]
                ); 
        //        
        // _e($data["markers"],true);   
        $data["employees"] = $this->clock_model->getEmployees($this->loggedInCompany["sid"]);        
        //           
        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer");
    }

    public function location_detail()
    {
        //
        onlyPlusAndPayPlanCanAccess();
        //
        $data["employee"] = $this->loggedInEmployee;
        $data["session"] = checkAndGetSession("all");

        // add plugins
        $data["pageCSS"] = [
            getPlugin("timepicker", "css"),
            getPlugin("daterangepicker", "css"),
        ];
        //
        $data["pageJs"] = [
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
        $this->getCommon($data, "location_detail");

        //
        $data["load_view"] = false;
        $data["sanitizedView"] = true;
        $data["title"] = "Overview";
        $data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/location_detail";
        $this->load->model("v1/Attendance/Clock_model", "clock_model");
        //
        $selectedDate = $this->input->get("date");
        $employeeId = $this->input->get("sid");
        //
        $clockedInDate = formatDateToDB($selectedDate, SITE_DATE, DB_DATE);
        $data['clockedInDate'] = $clockedInDate;
        $data['employeeInfo'] = get_employee_profile_info($employeeId);
        //
        $data["history"] = $this->clock_model
                ->getEmployeeLoginHistory(
                    $employeeId,
                    $clockedInDate,
                ); 
        //
        // get the employee worked shifts
        $data["clockArray"] = $this->clock_model->calculateTimeWithinRange(
            $employeeId,
            $clockedInDate,
            $clockedInDate
        ); 
        //        
        // _e($data["history"],true);           
        //           
        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer");
    }


    /**
     * set the common files
     *
     * @param string $filePath
     * @param string $type
     */
    private function setCommon(string $filePath, string $type = "css"): void
    {
        $this->commonFiles[$type][] = $filePath;
    }

    /**
     * set the common files
     *
     * @param array $data passed by reference
     */
    private function getCommon(&$data, string $page): void
    {
        // set common bundles
        // css
        $data["appCSS"] = bundleCSS(
            getCommonFiles("css"),
            $this->css,
            "common",
            true
        );
        // js
        $data["appJs"] = bundleJs(
            getCommonFiles("js"),
            $this->js,
            "common",
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
