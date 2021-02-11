<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_feed_iframe extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
    }

    /**
     * Generates iframe link
     * @return VOID
     */
    function index($page = 'send'){
        $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, 'login', 'index'); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        $this->data['JobFeedURL'] = $_SERVER['HTTP_HOST']=='localhost' ? 'http://ams.example/job-feed/' : 'https://www.automotosocial.com/job-feed/';
        $this->render('manage_admin/job_feed_iframe/index');
    }


}
