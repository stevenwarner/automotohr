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
     * JS file creation path
     * @var string
     */
    private $commonFiles;
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
        $data["employee"] = $this->loggedInEmployee;

        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $this->loggedInEmployee["sid"],
            $this->loggedInCompany["sid"]
        );
        $data['appJS'] = bundleJs([
            "v1/plugins/moment/moment-timezone.min",
            "js/app_helper",
            "js/common",
            // "v1/attendance/js/test"
        ], $this->js, "my_dashboard", $this->disableCreationOfMinifyFiles);
        //
        $data["load_view"] = true;

        $this->load->view("main/header", $data);
        $this->load->view("v1/attendance/my_dashboard");
        $this->load->view("main/footer",);
    }

    /**
     * logged in person time sheet
     */
    public function myTimeSheet()
    {
        $data["employee"] = $this->loggedInEmployee;

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


    /**
     * set the common files
     *
     * @param string $filePath
     * @param string $type
     */
    private function setCommon(string $filePath, string $type = "css")
    {
        $this->commonFiles[$type][] = $filePath;
    }

    /**
     * set the common files
     *
     * @param array $data passed by reference
     */
    private function getCommon(&$data, string $page)
    {
        // css bundle
        $data['appCSS'] = bundleCSS(array_merge(getCommonFiles("css"), $this->commonFiles["css"]), $this->css, $page, $this->disableCreationOfMinifyFiles);
        // js bundle
        $data['appJs'] = bundleJs(array_merge(getCommonFiles("js"), $this->commonFiles["js"]), $this->js, $page, $this->disableCreationOfMinifyFiles);
    }
}
