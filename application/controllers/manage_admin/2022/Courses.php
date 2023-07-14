<?php defined('BASEPATH') or exit('No direct script access allowed');

class Courses extends Admin_Controller
{

    /**
     * Holds logged in admin id
     * @var int $loggedInId
     */
    private $loggedInId;

    /**
     * Loads the constructor
     */
    function __construct()
    {
        // call the parent constructor
        parent::__construct();
        // load the ION auth library
        $this->load->library('ion_auth');
        // set the admin id
        $this->loggedInId =
            $this->ion_auth->user()->row()->id;
        // load the library
        $this->load->library('Api_auth');
        // call the event
        $this->api_auth->checkAndLogin(
            0,
            $this->loggedInId
        );
        //
        // call the company event
        if ($this->uri->segment(4)) {
            $this->api_auth->checkAndLogin(
                $this->uri->segment(4),
                $this->loggedInId
            );
        }
       
    }

    /**
     * Dashboard
     */
    public function index()
    {
        // check and set the security group
        $this->data['security_details'] = db_get_admin_access_level_details($this->loggedInId);
        // make sure the system is redirecting
        check_access_permissions($this->data['security_details'], 'manage_admin', 'blocked_app');
        // set the title
        $this->data['title'] = "LMS Courses :: " . STORE_NAME;
        $this->data['loadUp'] = true;
        $this->data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        // load CSS
        $this->data['PageCSS'] = [
            ['1.2.1', 'v1/plugins/ms_uploader/main'],
            ['1.2.1', 'v1/plugins/ms_modal/main'],
            ['1.0.1', '2022/css/main']
        ];
        // load JS
        $this->data['PageScripts'] = [
            ['1.0', 'js/app_helper'],
            ['1.2.0', 'v1/plugins/ms_uploader/main'],
            ['1.2.0', 'v1/plugins/ms_modal/main'],
            ['1.0', 'v1/plugins/ms_recorder/main'],
            ['1.0.0', 'v1/common'],
            ['1.0', 'v1/lms/add_question'],
            ['1.0', 'v1/lms/edit_question'],
            ['1.0.2', 'v1/lms/create_course'],
            ['1.0.2', 'v1/lms/edit_course'],
            ['1.0.1', 'v1/lms/main'],
        ];
        // get access token
        $this->data['apiAccessToken'] = getApiAccessToken(
            0,
            $this->loggedInId
        );
        // render the view
        $this->render('manage_admin/courses/dashboard', 'admin_master');
    }

    /**
     * Company Courses
     * @param int $companyId
     */
    public function companyCourses(int $companyId)
    {
        // check and set the security group
        $this->data['security_details'] = db_get_admin_access_level_details($this->loggedInId);
        // make sure the system is redirecting
        check_access_permissions($this->data['security_details'], 'manage_admin', 'blocked_app');
        // set the title
        $this->data['title'] = "LMS Company Courses :: " . STORE_NAME;
        $this->data['loadUp'] = true;
        $this->data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        $this->data['companyId'] = $companyId;
        // load CSS
        $this->data['PageCSS'] = [
            ['1.2.1', 'v1/plugins/ms_uploader/main'],
            ['1.2.1', 'v1/plugins/ms_modal/main'],
            ['1.0.1', '2022/css/main']
        ];
        // load JS
        $this->data['PageScripts'] = [
            ['1.0', 'js/app_helper'],
            ['1.2.0', 'v1/plugins/ms_uploader/main'],
            ['1.2.0', 'v1/plugins/ms_modal/main'],
            ['1.0', 'v1/plugins/ms_recorder/main'],
            ['1.0.0', 'v1/common'],
            ['1.0', 'v1/lms/add_question'],
            ['1.0', 'v1/lms/edit_question'],
            ['1.0.2', 'v1/lms/create_course'],
            ['1.0.2', 'v1/lms/edit_course'],
            ['1.0.1', 'v1/lms/main'],
            ['1.0', 'v1/lms/assign_company_courses']
        ];
        // get access token
        $this->data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $this->loggedInId
        );
        //
        $this->data['apiAccessTokenStore'] = getApiAccessToken(
            0,
            $this->loggedInId
        );
        // render the view
        $this->render('manage_admin/courses/company_dashboard', 'admin_master');
    }

}
