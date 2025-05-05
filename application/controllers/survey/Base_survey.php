<?php defined("BASEPATH") || exit("Access is denied.");
/**
 * Base
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Survey
 */
class Base_survey extends CI_Controller
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
    public function __construct(bool $isSecureUrl = true, bool $isEmployeeView = false)
    {
        parent::__construct();
        //
        $this->data = [];
        $this->isSecureUrl = $isSecureUrl;
        //
        // set the default data
        $this->data = [
            "sanitizedView" => true,
            "loadView" => $isEmployeeView,
            "loadJsFiles" => true,
            "pageCSS" => [
                "2022/css/main",
                "css/select2",
                "css/jquery.datetimepicker",
                "v1/plugins/ms_uploader/main.min",
                "alertifyjs/css/alertify.min",
            ],
            "pageJs" => [
                "js/select2",
                "js/jquery.validate.min",
                "js/jquery.datetimepicker",
                "v1/plugins/ms_uploader/main.min",
                "ckeditor/ckeditor",
                "alertifyjs/alertify.min",
            ],
        ];
        // set site modal plugin
        $this->setPlugin("plugins/ms_site_modal/main", "css");
        $this->setPlugin("plugins/ms_site_modal/main");
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
            // load the library
            $this->load->library('Api_auth');
            // call the company event
            $response = $this
                ->api_auth
                ->checkAndLogin(
                    $this->loggedInCompany['sid'],
                    $this->loggedInEmployee['sid']
                );
            // get access token
            $this->data['apiAccessToken'] = $response["access_token"]
                ? $response["access_token"]
                : getApiAccessToken(
                    $this->loggedInCompany['sid'],
                    $this->loggedInEmployee['sid']
                );
            // set the API URL
            $this->data["apiURL"] = getCreds('AHR')->API_BROWSER_URL;

            $this->logActivity();
        }
    }

    /**
     * Render the view in template
     *
     * @param string $path
     */
    protected function renderView(string $path)
    {
        $headerPath = "main/header_sanitized";
        $footerPath = "main/footer_sanitized";
        if (!$this->isSecureUrl) {
            $headerPath = "main/public/header_public";
            $footerPath = "main/public/footer_public";
        }
        $this->load
            ->view($headerPath, $this->data)
            ->view($path, $this->data)
            ->view($footerPath, $this->data);
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

    /**
     * Summary of setJs
     * @param string $jsFile
     * @return void
     */
    protected function setJs(string $jsFile)
    {
        $this->data["pageJs"][] = "survey/{$jsFile}";
    }

    /**
     * Summary of setPlugin
     * @param string $pluginFile
     * @param mixed $type
     * @return void
     */
    protected function setPlugin(string $pluginFile, $type = "js")
    {
        $this->data[$type == "js" ? "pageJs" : "pageCSS"][] = "{$pluginFile}";
    }

    /**
     * Summary of logActivity
     * @return void
     */
    private function logActivity()
    {
        $data = array();
        $login_check = $this->session->userdata('logged_in');
        if ($login_check) { // User is already Logged IN // track User's activity Log
            $activity_data = array();
            $activity_data['company_sid'] = $login_check['company_detail']['sid'];
            $activity_data['employer_sid'] = $login_check['employer_detail']['sid'];
            $activity_data['company_name'] = $login_check['company_detail']['CompanyName'];
            $activity_data['employer_name'] = $login_check['employer_detail']['first_name'] . ' ' . $login_check['employer_detail']['last_name'];
            $activity_data['employer_access_level'] = $login_check['employer_detail']['access_level'];
            $activity_data['module'] = $this->router->fetch_class();
            $activity_data['action_performed'] = $this->router->fetch_method();
            $activity_data['action_year'] = date('Y');
            $activity_data['action_week'] = date('W');
            $activity_data['action_timestamp'] = date('Y-m-d H:i:s');
            $activity_data['action_status'] = '';
            $activity_data['action_url'] = current_url();
            $activity_data['employer_ip'] = getUserIp();
            $activity_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

            if (isset($_SESSION['logged_in']['is_super']) && $_SESSION['logged_in']['is_super'] == 1) {
                $this->db->insert('logged_in_activitiy_tracker_super', $activity_data);
            } else {
                $this->db->insert('logged_in_activitiy_tracker', $activity_data);
            }
        } else { // User is not Logged IN // make snapshot for users URL, after login send him to his actual URL
            $data['snapshot']['class'] = $this->router->fetch_class();
            $data['snapshot']['method'] = $this->router->fetch_method();
            $data['snapshot']['url'] = current_url();
        }


        $this->session->set_userdata('snapshot', $data);
    }
}
