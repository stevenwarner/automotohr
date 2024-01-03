<?php
defined("BASEPATH") || exit("Access is denied.");
/**
 * Base
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Base extends Public_Controller
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
     * holds sidebar path
     * @var string
     */
    protected $sidebarPath;

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
        parent::__construct();
        $this->load->library("attendance_lib");
        //
        $this->appSession = checkAndGetSession("all");
        $this->loggedInEmployee = $this->appSession["employer_detail"];
        $this->loggedInCompany = $this->appSession["company_detail"];
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
        // set the default data
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
        //
        $this->load->model("v1/Attendance/Base_model", "base_model");
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
