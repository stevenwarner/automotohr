<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Performance Management Module
 *
 * PHP version >= 5.6
 *
 * @category   Module
 * @package    Performance Managment
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Performance_management extends Public_Controller{
    // Set page path
    private $pp;
    // Set mobile path
    private $mp;
    //  Set argumenst array
    private $pargs;
    //
    private $resp = [];

    /**
     * Contructor
     *
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     */
    function __construct(){
        //
        parent::__construct();
        //
        $this->pargs = [];
        // Load helper
        $this->load->helper('performance_management');
        // Load modal
        $this->load->model('performance_management_model', 'pmm');
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->mp = $this->agent->is_mobile() ? '' : '';
        //
        $this->pp = 'Performance_management/theme1/';
        //
        $this->pargs['pp'] = $this->pp;
        //
        $this->resp = [
            'Status' => FALSE,
            'Redirect' => TRUE,
            'Response' => 'Invalid request'
        ];
    }

    /**
     * Dashboard
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function dashboard(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Dashboard';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}{$this->mp}dashboard", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    
    /**
     * Reviews
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function reviews(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Reviews';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}index", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    /**
     * Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/02/2021
     * 
     * @param Integer $reviewId
     * 
     * @return Void
     */
    function review($reviewId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Review';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}review", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    /**
     * Review - Feedback
     * 
     * @employee Mubashir Ahmed 
     * @date     02/03/2021
     * 
     * @param Integer $reviewId
     * @param Integer $employeeId
     * 
     * @return Void
     */
    function feedback($reviewId, $employeeId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}feedback", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }

    /**
     * Review - Reviewer Feedback
     * 
     * @employee Mubashir Ahmed 
     * @date     02/03/2021
     * 
     * @param Integer $reviewId
     * @param Integer $employeeId
     * 
     * @return Void
     */
    function reviewer_feedback($reviewId, $employeeId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        // $this->load->view("{$this->pp}reviews/{$this->mp}preview", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}reviewer_feedback", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    /**
     * Review - Create Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/04/2021
     * 
     * @return Void
     */
    function create_review(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Create a review';
        // Set templates
        $this->pargs['templates'] = [];
        // Get personal templates
        $this->pargs['templates']['personal'] = $this->pmm->getPersonalTemplates($this->pargs['companyId'], ['sid', 'name']);
        // Get company templates
        $this->pargs['templates']['company'] = $this->pmm->getCompanyTemplates(['sid', 'name']);
        // Get department & teams list
        $this->pargs['dnt'] = $this->pmm->getTeamsAndDepartments($this->pargs['companyId']);
        // Get job titles
        $this->pargs['jobTitles'] = $this->pmm->getCompanyJobTitles($this->pargs['companyId']);

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}create", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    
    /**
     * Goals - Create Goal
     * 
     * @employee Mubashir Ahmed 
     * @date     02/04/2021
     * 
     * @return Void
     */
    function create_goal(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Create a goal';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}goals/{$this->mp}create", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    /**
     * Goals - List Goal
     * 
     * @employee Mubashir Ahmed 
     * @date     02/08/2021
     * 
     * @return Void
     */
    function goals(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - List Goals';

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}goals/{$this->mp}view", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }


    /**
     * Get handler
     * 
     * @employee Mubashir Ahmed
     * @date     02/10/2021
     * 
     * @return JSON
     */
    function get_handler(){
        $pargs = [];
        // Check session
        $isLogin = $this->checkLogin($pargs, true);
        //
        if(!$isLogin) res($this->resp);
        //
        $this->resp['Redirect'] = false;
        //
        $params = $this->getParams();
        //
        switch($params[0]):
            // Get template questions
            case "template-questions-h":
                if($params[2] == 'personal'):
                    $questions = $this->pmm->getPersonalQuestionsById($params[1]);
                else:
                    $questions = $this->pmm->getCompanyQuestionsById($params[1]);
                endif;
                // Decode JSON to Array
                $questions = json_decode($questions, true);
                // Get question HTML
                $html = $this->load->view("{$this->pp}preview/questions", ['Questions' => $questions], true);
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['Data'] = $html;
                //
                res($this->resp);
            break;
            // Get template by id
            case "template":
                if($params[2] == 'personal'):
                    $template = $this->pmm->getPersonalTemplateById($params[1], ['name', 'questions']);
                else:
                    $template = $this->pmm->getCompanyTemplateById($params[1], ['name', 'questions']);
                endif;
                // Decode JSON to Array
                $template['questions'] = json_decode($template['questions'], true);
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['Data'] = $template;
                //
                res($this->resp);
            break;
            // Get employee list with dnt
            case "employeeListWithDnT":
                $list = $this->pmm->getEmployeeListWithDepartments($pargs['employerId'], $pargs['companyId'], getEmployerAccessLevel());
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['Data'] = $list;
                //
                res($this->resp);
            break;
        endswitch;
    }


    /**
     * Check user session and set data
     * 
     * @employee Mubashir Ahmed
     * @date     02/02/2021
     *
     * @param Reference $data
     * @param Bool      $return (Default is 'FALSE')
     * 
     * @return VOID
     */
    private function checkLogin(&$data, $return = FALSE){
        //
        if (!$this->session->userdata('logged_in')) {
            if ($return) return false;
            redirect('login', 'refresh');
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        //
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['companyName'] = $data['session']['company_detail']['CompanyName'];
        $data['companyDetails'] = $data['session']['company_detail'];
        $data['employerDetails'] = $data['session']['employer_detail'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        //
        if ($return) return true;
        else $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
    }

    /**
     * Get cleaned params
     * 
     * @employee Mubashir Ahmed
     * @date     02/10/2021
     * 
     * @return Array
     */
    private function getParams(){
        $r = [];
        $p = $this->uri->segment_array();
        unset($p[0], $p[1], $p[2], $p[3]);
        $r = array_values($p);
        $r = array_map(function($i){
            return strip_tags(trim($i));
        }, $r);
        return $r;
    }
}