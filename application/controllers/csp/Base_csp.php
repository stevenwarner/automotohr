<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * Base
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Attendance
 */
class Base_csp extends CI_Controller
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
     * holds data
     * @var string
     */
    protected $data;

    /**
     * holds data
     * @var string
     */
    private $isSecureUrl;

    /**
     * main entry point
     */
    public function __construct(bool $isSecureUrl = true)
    {
        parent::__construct();
        //
        $this->data = [];
        $this->isSecureUrl = $isSecureUrl;
        //
        // set the default data
        $this->data = [
            "sanitizedView" => true,
            "loadView" => true,
            "pageCSS" => [
                "2022/css/main",
                "css/select2",
                "css/jquery.datetimepicker",
                "v1/plugins/ms_uploader/main.min",
                "v1/plugins/ms_modal/main.min",
                "alertifyjs/css/alertify.min",
            ],
            "pageJs" => [
                "js/select2",
                "js/jquery.validate.min",
                "js/jquery.datetimepicker",
                "v1/plugins/ms_uploader/main.min",
                "v1/plugins/ms_modal/main.min",
                "js/ckeditor",
                "alertifyjs/alertify.min",
            ],
        ];
        //
        if ($this->isSecureUrl) {
            //
            $this->appSession = checkAndGetSession("all");
            $this->loggedInEmployee = $this->appSession["employer_detail"];
            $this->loggedInCompany = $this->appSession["company_detail"];
            // set the default data
            $this->data["session"] = $this->appSession;
            $this->data["loggedInEmployee"] = $this->loggedInEmployee;
            $this->data["employee"] = $this->loggedInEmployee;
            $this->data["securityDetails"] = db_get_access_level_details(
                $this->loggedInEmployee["sid"]
            );
            //
            $this->data["security_details"] = $this->data["securityDetails"];
        }
        // load model
        $this->load->model('v1/compliance_report_model');
    }

    /**
     * Render the view in template
     *
     * @param string $path
     */
    protected function renderView(string $path)
    {
        if ($this->isSecureUrl) {
            $this->load
                ->view("main/header_sanitized", $this->data)
                ->view($path)
                ->view("main/footer_sanitized");
        } else {
            $this->load
                ->view("main/public/header_public", $this->data)
                ->view($path)
                ->view("main/public/footer_public");
        }
    }

    /**
     * Get the logged in employee
     */
    protected function getLoggedInEmployee(string $column = "sid")
    {
        return $this->loggedInEmployee[$column] ?? null;
    }

    /**
     * Get the logged in company
     */
    protected function getLoggedInCompany(string $column = "sid")
    {
        return $this->loggedInCompany[$column] ?? null;
    }
}
