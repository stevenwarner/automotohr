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
        $this->pp = 'Performance_management/theme2/';
        //
        $this->pargs['pp'] = $this->pp;
        //
        $this->resp = [
            'Status' => FALSE,
            'Redirect' => TRUE,
            'Response' => 'Invalid request'
        ];
        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
        // test
        // $this->pargs = [];
        
        // $this->checkLogin($this->pargs);
        
        // $employees = $this->pmm->getAllEmployees($this->pargs['session']['company_detail']['sid']);

        //
        $this->header = 'main/header';
        $this->footer = 'main/footer';
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
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}dashboard");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
        // Get department & teams list
        // $employees = $this->pmm->getAllCompanyEmployees($this->pargs['companyId']);
        // //
        // if(!empty($employees)){
        //     foreach($employees as $employee){
        //         $this->pargs['employees'][$employee['Id']] = [
        //             'name' => ucwords($employee['FirstName'].' '.$employee['LastName']),
        //             'role' => $employee['FullRole'],
        //             'img' => getImageURL($employee['Image'])
        //         ];
        //     }
        // }
        // Get goals 
        // $this->pargs['goals'] = $this->pmm->getGoals($this->pargs['employerId']);
        // // Get Assigned Reviews 
        // $this->pargs['assignedReviews'] = $this->pmm->getReviewsByType($this->pargs['employerId'], 'assigned');
        // $this->pargs['feedbackReviews'] = $this->pmm->getReviewsByType($this->pargs['employerId'], 'feedback');
        // // Get employer role
        // $this->pargs['permission'] = $this->pmm->getEmployeePermission($this->pargs['employerId'], $this->pargs['level']);
        // // Get department & teams list
        // My goals

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}{$this->mp}dashboard_new", $this->pargs);
        $this->load->view("{$this->pp}footer", $this->pargs);
        $this->load->view("main/footer");
    }
    
    
    /**
     * Dashboard
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function feedback(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}feedback");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
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
        $this->pargs['title'] = 'Performance Management - Feedback';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}reviews");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }
    
    /**
     * Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function review(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}review");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    /**
     * Create Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function create_review(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Dashboard';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->GetMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        // Set company department and teams
        $this->pargs['company_dt'] = $this->pmm->GetCompanyDepartmentAndTeams($this->pargs['companyId']);
        // Set system provided templates
        $this->pargs['system_templates'] = $this->pmm->GetCompanyTemplates();
        // Set company generated templates
        $this->pargs['company_templates'] = $this->pmm->GetPersonalTemplates($this->pargs['companyId']);
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}create");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    
    /**
     * Review - Create Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/04/2021
     * 
     * @return Void
     */
    function create_review($id = 0){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Create a review';
        // Set templates
        $this->pargs['templates'] = [];
        // Get personal templates
        $this->pargs['templates']['personal'] = $this->pmm->getPersonalTemplates($this->pargs['companyId'], ['sid', 'name', 'questions']);
        // Get company templates
        $this->pargs['templates']['company'] = $this->pmm->getCompanyTemplates(['sid', 'name', 'questions']);
        // Get employer role
        $this->pargs['permission'] = $this->pmm->getEmployeePermission($this->pargs['employerId'], $this->pargs['level']);
        // Get department & teams list
        $this->pargs['dnt'] = $this->pmm->getTeamsAndDepartments($this->pargs['companyId']);
        // Get job titles
        $this->pargs['jobTitles'] = $this->pmm->getCompanyJobTitles($this->pargs['companyId']);
        //
        if($id != 0){
            $this->pargs['review'] = $this->pmm->getReviewById($id, '*', 0, ['is_draft' => 1]);
        }

        $this->load->view("main/header", $this->pargs);
        $this->load->view("{$this->pp}header", $this->pargs);
        $this->load->view("{$this->pp}reviews/{$this->mp}create_new", $this->pargs);
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
    function download(
        $type,
        $reviewId = 0,
        $revieweeId = 0,
        $reviewerId = 0
    ){
        // 
        $this->checkLogin($this->pargs);
        // Get department & teams list
        $employees = $this->pmm->getAllCompanyEmployees($this->pargs['companyId']);
        //
        if(!empty($employees)){
            foreach($employees as $employee){
                $this->pargs['employees'][$employee['Id']] = [
                    'name' => ucwords($employee['FirstName'].' '.$employee['LastName']),
                    'role' => $employee['FullRole'],
                    'img' => getImageURL($employee['Image']),
                    'joined' => formatDate($employee['JoinedAt'], 'Y-m-d', 'M d D, Y')
                ];
            }
        }
        //
        $this->pargs['review'] = $this->pmm->getReviewWithQuestions($reviewId, $revieweeId, $reviewerId);
        $this->load->view("{$this->pp}reviews/download_q", $this->pargs);
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
        // Get employer role
        $this->pargs['permission'] = $this->pmm->getEmployeePermission($this->pargs['employerId'], $this->pargs['level']);
        // Get department & teams list
        $this->pargs['dnt'] = $this->pmm->getTeamsAndDepartments($this->pargs['companyId']);

    /**
     * Report
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function report(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Dashboard';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->GetMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        // Set company department and teams
        $this->pargs['company_dt'] = $this->pmm->GetCompanyDepartmentAndTeams($this->pargs['companyId']);
        // Set system provided templates
        $this->pargs['system_templates'] = $this->pmm->GetCompanyTemplates();
        // Set company generated templates
        $this->pargs['company_templates'] = $this->pmm->GetPersonalTemplates($this->pargs['companyId']);
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}report/index");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    // AJAX REQUESTS

    /**
     * 
     */
    function template_questions($id, $type){
        //
        if( !$this->input->is_ajax_request() ){
            // $this->res([], true);
        }
        //
        if($type == 'company'){
            // Set system provided templates
            $template = $this->pmm->GetSingleCompanyTemplates($id, 'questions');
            // Set company generated templates
        } else if($type == 'personal'){
            $template = $this->pmm->GetSinglePersonalTemplates($id, 'questions');
            
        } else{
            $this->res([], true);
        }

        //
        $this->load->view($this->pp.'template_questions_view', ['questions' => json_decode($template['questions'])]);

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}dashboard");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
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
            if ($return) {
                return false;
            }
            redirect('login', 'refresh');
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        //
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['companyName'] = $data['session']['company_detail']['CompanyName'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        // $data['load_view'] = 0;
        $data['hide_employer_section'] = 1;
        //
        if ($return) {
            return true;
        }
        else {
            $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
        }
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

    /**
     * 
     */
    private function res($resp = [], $isError = false){
        header("Content-Type: application/json");
        echo json_encode($isError ? ["Error" => "Invalid request"] : $resp);
        exit(0);
    }
}