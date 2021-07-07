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
    function create_review($id = 0){
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
        // Get Review
        $this->pargs['review'] = $this->pmm->GetReviewRowById($id, $this->pargs['companyId']);
        // Set Job titles
        $this->pargs['job_titles'] = array_filter(array_unique(array_column($this->pargs['company_employees'], 'JobTitle')), function($job){
            if(!empty($job)) {
                return 1;
            }
        });
        //
        sort($this->pargs['job_titles']);
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}create_review/create");
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
            $this->res([], true);
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
        $this->load->view($this->pp.'create_review/template_questions_view', ['questions' => json_decode($template['questions'])]);
    }

    /**
     * 
     */
    function single_template($id, $type){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        if($type == 'company'){
            // Set system provided templates
            $template = $this->pmm->GetSingleCompanyTemplates($id, ['name', 'questions']);
            // Set company generated templates
        } else if($type == 'personal'){
            $template = $this->pmm->GetSinglePersonalTemplates($id, ['name', 'questions']);
        } else{
            $this->res([], true);
        }
        //
        $template['questions'] = json_decode($template['questions'], true);

        //
        $this->res(['data' => $template]);
    }
    
    /**
     * 
     */
    function SaveReviewStep(){
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        $pargs = [];
        //
        $this->checkLogin($pargs);
        //
        switch($post['step']):
            case "ReviewStep1":
                    // Set data array
                    $data_array = [];
                    //
                    if(empty($post['data']['title'])){
                        //
                        $resp['Msg'] = "The review title is missing.";
                        $this->res($resp);
                    }
                    //
                    if(empty($post['data']['frequency_type'])){
                        //
                        $resp['Msg'] = "The frequency is missing.";
                        $this->res($resp);
                    }
                    //
                    if(
                        ($post['data']['frequency_type'] == 'onetime' || $post['data']['frequency_type'] == 'recurring') &&
                        (empty($post['data']['start_date']) || empty($post['data']['end_date']))
                    ){
                        //
                        $resp['Msg'] = "The review start and end dates are missing.";
                        $this->res($resp);
                    }
                    //
                    if(
                        $post['data']['frequency_type'] == 'recurring' &&
                        (empty($post['data']['recur_value']) || $post['data']['recur_value'] == 0)
                    ){
                        //
                        $resp['Msg'] = "The recur value is missing.";
                        $this->res($resp);
                    }
                    //
                    if(
                        $post['data']['frequency_type'] == 'custom' &&
                        (empty($post['data']['review_due_value']) || $post['data']['review_due_value'] == 0)
                    ){
                        //
                        $resp['Msg'] = "The review due value is missing.";
                        $this->res($resp);
                    }
                    //
                    if(
                        $post['data']['frequency_type'] == 'custom' &&
                        empty($post['data']['custom_runs']) 
                    ){
                        //
                        $resp['Msg'] = "Please add at least one custom run.";
                        $this->res($resp);
                    }
                    //
                    if($post['data']['frequency_type'] == 'onetime' || $post['data']['frequency_type'] == 'recurring'){
                        $data_array['review_start_date'] = formatDateToDB($post['data']['start_date']);
                        $data_array['review_end_date'] = formatDateToDB($post['data']['end_date']);
                    }
                    //
                    if($post['data']['frequency_type'] == 'recurring'){
                        $data_array['repeat_after'] = $post['data']['recur_value'];
                        $data_array['repeat_type'] = $post['data']['recur_type'];
                    }
                    //
                    if($post['data']['frequency_type'] == 'custom'){
                        $data_array['review_due_type'] = $post['data']['review_due_type'];
                        $data_array['review_due'] = $post['data']['review_due_value'];
                        $data_array['repeat_review'] = $post['data']['repeat_review'];
                        $data_array['review_runs'] = json_encode($post['data']['custom_runs']);
                    }
                    //
                    $data_array['review_title'] = $post['data']['title'];
                    $data_array['description'] = $post['data']['description'];
                    $data_array['frequency'] = $post['data']['frequency_type'];
                    //
                    if(isset($post['data']['roles'])){
                        $data_array['visibility_roles'] = implode(',', $post['data']['roles']);
                    }
                    if(isset($post['data']['departments'])){
                        $data_array['visibility_departments'] = implode(',', $post['data']['departments']);
                    }
                    if(isset($post['data']['teams'])){
                        $data_array['visibility_teams'] = implode(',', $post['data']['teams']);
                    }
                    if(isset($post['data']['employees'])){
                        $data_array['visibility_employees'] = implode(',', $post['data']['employees']);
                    }
                    //
                    if(!isset($post['id'])){
                        $data_array['company_sid']  = $pargs['companyId'];
                        $data_array['is_draft'] = 1;
                        $data_array['status'] = 'pending';
                        $data_array['created_at'] = date("Y-m-d H:i:s", strtotime("now"));

                        //
                        $reviewId = $this->pmm->InsertReview($data_array);
                    } else{
                        $reviewId = $this->pmm->UpdateReview($data_array, $post['id']);
                    }
                    //
                    $resp['Status'] = true;
                    $resp['Msg'] = 'Review added.';
                    $resp['Id'] = $reviewId;
                    //
                    $this->res($resp);
                break;
        endswitch;
        //
        _e($post, true, true);

        //
        $this->res(['data' => $template]);
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
        // $data['load_view'] = 1;
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