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
        $this->disableCreationOfMinifyFiles = false;
        //
        $this->css = "public/v1/css/attendance/";
        $this->js = "public/v1/js/attendance/";
        // load the library
        $this->load->library('Api_auth');
        $this->commonFiles = ["css" => [], "js" => []];
        //
        $this->sidebarPath = "v1/attendance/sidebar";
        //
        // $this->api_auth->checkAndLogin(
        //     $this->loggedInEmployee["sid"],
        //     $this->loggedInCompany["sid"]
        // );
    }

    /**
     * logged in person time sheet
     */
    public function myDashboard()
    {
        $data["session"] = $this->session->userdata("logged_in");

        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $this->loggedInEmployee["sid"],
            $this->loggedInCompany["sid"]
        );
        //
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/app/css/loader", "css");
        $this->setCommon("v1/attendance/js/my/dashboard", "js");
        $data["pageJs"] = [
            // high charts
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/highcharts.min.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/data.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/exporting.js?v=3.0"),
            main_url("public/v1/plugins/Highcharts-Maps-11.2.0/code/modules/accessibility.js?v=3.0"),
        ];
        $this->getCommon($data, "my_dashboard");
        $data["sanitizedView"] = true;

        $this->load->view("main/header", $data);
        $this->load->view("v1/attendance/my_dashboard");
        $this->load->view("main/footer",);
    }

    /**
     * logged in person time sheet
     */
    public function myTimeSheet()
    {
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $this->loggedInEmployee["sid"],
            $this->loggedInCompany["sid"]
        );
        //
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/my_timesheet", "js");
        //
        $data["load_view"] = true;
        //
        $this->getCommon($data, "my_timesheet");

        $this->load->view("main/header", $data);
        $this->load->view("v1/attendance/my_timesheet");
        $this->load->view("main/footer",);
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

        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $this->loggedInEmployee["sid"],
            $this->loggedInCompany["sid"]
        );
        //
        $this->setCommon("v1/app/plugins/select2/select2.min", "css");
        $this->setCommon("v1/app/plugins/select2/select2.min", "js");
        //
        $this->setCommon("v1/app/css/system", "css");
        $this->setCommon("v1/attendance/js/dashboard", "js");
        //
        $data["load_view"] = false;
        $data['security_details'] = db_get_access_level_details($this->loggedInEmployee["sid"]);
        //
        $this->getCommon($data, "dashboard");
        //
        // $data["pageJs"] = ["https://code.highcharts.com/highcharts.js"];
        //
        $data["sidebarPath"] = $this->sidebarPath;
        $data["mainContentPath"] = "v1/attendance/dashboard";

        $this->load->view("main/header", $data);
        $this->load->view("v1/employer/main");
        $this->load->view("main/footer",);
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
