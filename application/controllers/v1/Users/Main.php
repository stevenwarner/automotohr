<?php
defined("BASEPATH") || exit("Access is denied.");
/**
 * Main
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
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
        //
        $this->appSession = checkAndGetSession("all");
        $this->loggedInEmployee = $this->appSession["employer_detail"];
        $this->loggedInCompany = $this->appSession["company_detail"];
        //
        $this->disableCreationOfMinifyFiles = false;
        //
        $this->css = "public/v1/css/users/";
        $this->js = "public/v1/js/users/";
        // load the library
        $this->load->library('Api_auth');
        $this->commonFiles = ["css" => [], "js" => []];
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
        $this->load->model("v1/Users/main_model", "main_model");
    }


    /**
     * User payroll dashboard
     *
     * @param int    $userId
     * @param string $userType
     */
    public function dashboard(int $userId, string $userType)
    {
        //
        $this->data["title"] = "Payroll dashboard";
        //
        $this->data["userId"] = $userId;
        $this->data["userType"] = $userType;
        $this->data["loadJsFiles"] = true;
        //
        $this->data["employer"] = $this->main_model->getEmployeeDetails($userId);
        //
        if ($userType === "employee") {
            $this->data['return_title_heading'] = "Employee Profile";
            $this->data['return_title_heading_link'] = base_url() . 'employee_profile/' . $userId;
        } else {
            $this->data['return_title_heading'] = "Applicant Profile";
            $this->data['return_title_heading_link'] = base_url() . 'applicant_profile/' . $userId;
        }
        //
        check_access_permissions($this->data["securityDetails"], 'employee_management', 'payroll_dashboard');
        //
        $this->data = employee_right_nav($userId, $this->data);
        $this->data["left_navigation"] = 'manage_employer/employee_management/profile_right_menu_employee_new';
        // add plugins
        $this->data["pageJs"] = [
            // high charts
            main_url("public/v1/plugins/ms_modal/main.min.js?v=3.0"),
            main_url("public/v1/plugins/select2/select2.min.js?v=3.0"),
            main_url("public/v1/plugins/daterangepicker/daterangepicker.min.js?v=3.0"),
        ];
        $this->data["pageCSS"] = [
            // high charts
            main_url("public/v1/plugins/ms_modal/main.min.css?v=3.0"),
            main_url("public/v1/plugins/select2/css/select2.min.css?v=3.0"),
            main_url("public/v1/plugins/daterangepicker/css/daterangepicker.min.css?v=3.0"),
        ];
        // set js
        $this->setCommon("v1/users/payroll/js/payroll_dashboard", "js");
        $this->getCommon($this->data, "payroll_dashboard");
        // get the dashboard details
        $this->data["paySchedule"] = $this->main_model
            ->getUserPayScheduleById(
                $userId,
                $userType,
                true
            );
        // make the blue portal popup
        $this->renderView("v1/users/payroll/dashboard");
    }

    /**
     * get the page by slug
     *
     * @method pagePaySchedule
     * @param int    $userId
     * @param string $userType
     * @param string $slug
     * @return array
     */
    public function getPageBySlug(
        int $userId,
        string $userType,
        string $slug
    ): array {
        // check and generate error for session
        checkAndGetSession();
        // convert the slug to function
        $func = "page" . preg_replace("/\s/i", "", ucwords(preg_replace("/[^a-z]/i", " ", $slug)));
        //
        if ($func == 'pageJobAndWage') {

        }
        // get the data
        $data = $this->main_model
            ->$func(
                $userId,
                $userType,
                $this->loggedInCompany["sid"]
            );
        // _e($data,true,true);        
        //
        return SendResponse(200, [
            "view" => $this->load->view("v1/users/payroll/partials/page_" . $slug, $data, true),
            "data" => $data["return"] ?? []
        ]);
    }

    /**
     * get the page by slug
     *
     * @method processPaySchedule
     */
    public function updatePage()
    {
        // check and generate error for session
        $session = checkAndGetSession();
        // set the sanitized post
        $post = $this->input->post(null, true);
        //
        $post["companyId"] = $session["company_detail"]["sid"];
        // convert the slug to function
        $func = "process" . preg_replace("/\s/i", "", ucwords(preg_replace("/[^a-z]/i", " ", $post["page"])));
        // call the function
        $this->main_model->$func(
            $post["userId"],
            $post["userType"],
            $post
        );
    }

    public function updateEmployeejobCompensation ($userId, $userType) {
        // check and generate error for session
        $session = checkAndGetSession();
        // set the sanitized post
        $post = $this->input->post(null, true);
        //
        $companyId = $session["company_detail"]["sid"];
        //
        // get the company details
        $companyGustoDetails =  getCompanyDetailsForGusto($companyId);
        //
        if ($companyGustoDetails) {
            //
            $gustoEmployeeInfo = $this->main_model->getEmployeeGustoInfo($userId);
            //
            if ($gustoEmployeeInfo) {
                $jobInfo = $this->main_model->getEmployeeJobInfo($userId);
                //
                $newHireDate = formatDateToDB($post['hireDate']);
                //
                //
                $this->load->model("v1/Payroll_model", "payroll_model");
                //
                if ($jobInfo && $jobInfo['hire_date'] != $newHireDate) {
                    $companyGustoDetails['other_uuid'] = $jobInfo['gusto_uuid'];
                    //
                    // $this->main_model->updateEmployeeJobOnGusto($companyId, $jobInfo, $newHireDate, $companyGustoDetails);
                    $this->payroll_model->updateEmployeeCompensation($userId, $post, false);
                } else {
                    //
                    $companyGustoDetails['other_uuid'] = $gustoEmployeeInfo['gusto_uuid'];
                    // $this->payroll_model->createEmployeeJob($userId, $gustoEmployeeInfo['gusto_uuid'], $companyId);
                    //
                    // jsEmployeeFlowJobTitle
                    $this->payroll_model->updateEmployeeCompensation($userId, $post, false);
                }
            } else {
                $this->main_model->processEmployeeJobData($userId, $post);
            }
        } else {
            $this->main_model->processEmployeeJobData($userId, $post);
        }
        _e($userType,true);
        _e($_POST,true,true);
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
            false
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
