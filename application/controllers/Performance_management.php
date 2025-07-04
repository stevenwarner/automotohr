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

       // $this->header = 'main/header';
        $this->header = 'main/header_2022';
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
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $this->pargs['company_employees_index'] = [];
        //
        foreach($this->pargs['company_employees'] as $emp){
            $this->pargs['company_employees_index'][$emp['Id']] = $emp;
        }
        // // Get Assigned Reviews 
        $this->pargs['AssignedReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 0, 6);
        $this->pargs['FeedbackReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 1, 6);
        //
        $this->pargs['MyGoals'] = $this->filterGoals($this->pargs['Goals'], $this->pargs['employerId']);
        //
               
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}dashboard");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    /**
     * Dashboard
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function goals(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Goals';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $ne = [];
        //
        foreach($this->pargs['company_employees'] as $imp){
            $ne[$imp['Id']] = $imp;
        }
        //
        $this->pargs['ne'] = $ne;
        //
        $this->pargs['type'] = $type = $this->input->get('type', true) ? $this->input->get('type', true): 'my';
        //
        if($type == 'my'){
            //
            $this->pargs['MyGoals'] = $this->filterGoals($this->pargs['Goals'], $this->pargs['employerId']);
        } else if($type == 'company'){
            //
            $this->pargs['MyGoals'] = $this->filterGoals($this->pargs['Goals'], 0);
        } else if($type == 'department'){
            //
            $this->pargs['MyGoals'] = $this->filterGoals($this->pargs['Goals'], $this->pmm->GetEmployeesByDeparmentIds($this->pargs['employee']['department_sid']));
        } else if($type == 'team'){
            //
            $this->pargs['MyGoals'] = $this->filterGoals($this->pargs['Goals'], $this->pmm->GetEmployeesByDeparmentIds($this->pargs['employee']['team_sid']));
        }else {
            //
            if(!$this->pargs['employee']['access_level_plus']){
                //
                $this->pargs['MyGoals'] = $this->pargs['Goals'];
            } else{
                $this->pargs['MyGoals'] = array_filter($this->pargs['Goals'], function($goal){
                    //
                    if($goal['employee_sid'] == $this->pargs['employee']['sid']){
                        return 1;
                    }
                });
            }
            $this->pargs['MyGoals'] = $this->pargs['Goals'];
        } 
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}goals/dashboard");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    /**
     * Dashboard
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function pd_goal($id){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Goal';
        //
        $this->pargs['Goal'] = $this->pmm->GetSingleGoalById($id);
        
        $this->load->view("{$this->pp}goals/pd");
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
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $type = $this->input->get('type', true) ? $this->input->get('type', true) : 'active';
        //
        $this->pargs['type'] = $type;
        //
        $this->pargs['ReviewCount'] = $this->pmm->GetReviewCount($this->pargs['companyId']);
        //
        $this->pargs['reviews'] = $this->pmm->GetAllReviews(
            $this->pargs['employerId'], 
            $this->pargs['employerRole'], 
            $this->pargs['level'], 
            $this->pargs['companyId'],
            null,
            $type
        );

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}reviews");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }
   
   
    /**
     * templates
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function templates(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Reviews';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        //
        $this->pargs['templates'] = $this->pmm->GetAllTemplates(
            $this->pargs['companyId']
        );

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}templates");
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
    function MyReviews(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Reviews';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $this->pargs['reviews'] = $this->pmm->GetAllMyReviews(
            $this->pargs['employerId']
        );
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}my_reviews/reviews");
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
    function SingleReview($id){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Reviews';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $type = $this->input->get('type', true) ? $this->input->get('type', true) : 'active';
        //
        $this->pargs['type'] = $type;
        //
        $this->pargs['review'] = $this->pmm->GetReviewById(
            $id
        );

        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}single_review/single_review");
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
    function review($reviewId, $revieweeId, $reviewerId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        //
        $this->pargs['review'] = $this->pmm->GetReviewByReviewer($reviewId, $revieweeId, $reviewerId);
        //
        $this->pargs['reviewId'] = $reviewId;
        $this->pargs['revieweeId'] = $revieweeId;
        $this->pargs['reviewerId'] = $reviewerId;
        //
        $this->pargs['selectedPage'] = $this->input->get('page', true) ? $this->input->get('page', true) : 1;
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}feedback/review");
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
    function feedback($reviewId, $revieweeId, $reviewerId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Feedback';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        //
        $this->pargs['review'] = $this->pmm->GetReviewByReviewer($reviewId, $revieweeId, $reviewerId, true);
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $this->pargs['company_employees_index'] = [];
        //
        foreach($this->pargs['company_employees'] as $emp){
            $this->pargs['company_employees_index'][$emp['Id']] = $emp;
        }
        //
        $this->pargs['reviewId'] = $reviewId;
        $this->pargs['revieweeId'] = $revieweeId;
        $this->pargs['reviewerId'] = $reviewerId;
        //
        $this->pargs['selectedPage'] = $this->input->get('page', true) ? $this->input->get('page', true) : 1;
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}feedback/feedback");
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
    function create_review($id = 0, $section = false){
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
        //
        $this->pargs['section'] = $section;
        //
        if($id !== 0 &&$this->pargs['review']['is_draft'] == 0){
            redirect('performance-management/reviews','refresh');
            return;
        }
        if($id !== 0){
            if(empty($section)){
                //
                if(!empty($this->pargs['review']['reviewers'])){
                    redirect('performance-management/review/create/'.($id).'/reviewers','refresh');
                    return;
                }
                //
                if(!empty($this->pargs['review']['reviewees'])){
                    redirect('performance-management/review/create/'.($id).'/reviewees','refresh');
                    return;
                }
                //
                if(!empty($this->pargs['review']['questions'])){
                    redirect('performance-management/review/create/'.($id).'/questions','refresh');
                    return;
                }
            }
        }
        
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

    /**
     * Create Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function create_template($id = 0){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Dashboard';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->GetMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        //
        $this->pargs['template'] = $this->pmm->GetTemplateById($id);
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}create_template/create");
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
    function pd($action, $reviewId, $revieweeId, $reviewerId){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Review';
        //
        $this->pargs['action'] = $action;
        //
        $this->pargs['isManager'] = $this->pmm->isManager($revieweeId, $reviewerId);
        //
        $this->pargs['review'] = $this->pmm->GetReviewDetailsForPD($reviewId, $revieweeId, $reviewerId, $this->pargs['isManager']);
        //
        $this->load->view("{$this->pp}pd",  $this->pargs);
    }

    /**
     * Create Review
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function settings(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - Settings';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        // Set company department and teams
        $this->pargs['company_dt'] = $this->pmm->GetCompanyDepartmentAndTeams($this->pargs['companyId']);
        // Get Settings
        $this->pargs['settings'] = $this->pmm->GetSettings($this->pargs['companyId']);
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}settings");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }
    
    
    /**
     * All Assigned Reviews
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function all_reviews(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - All Reviews';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $this->pargs['company_employees_index'] = [];
        //
        foreach($this->pargs['company_employees'] as $emp){
            $this->pargs['company_employees_index'][$emp['Id']] = $emp;
        }
        // // Get Assigned Reviews 
        $this->pargs['AssignedReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 0);

        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}all_reviews");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }


    /**
     * All Feedback Reviews
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function all_feedbacks(){
        // 
        $this->checkLogin($this->pargs);
        // Set title
        $this->pargs['title'] = 'Performance Management - All Feedbacks';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        //
        $this->pargs['company_employees_index'] = [];
        //
        foreach($this->pargs['company_employees'] as $emp){
            $this->pargs['company_employees_index'][$emp['Id']] = $emp;
        }
        // // Get Assigned Feedbacks 
        $this->pargs['AssignedReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 1);

        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}all_feedbacks");
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
    function review_details($id){
        // 
        $this->checkLogin($this->pargs);
        //
        $this->pargs['company_employees_index'] = [];
        //
        foreach($this->pmm->GetAllEmployees($this->pargs['companyId']) as $emp){
            $this->pargs['company_employees_index'][$emp['Id']] = $emp;
        }
        //
        $this->pargs['Review'] = $this->pmm->GetReviewById(
            $id
        )[0];

        echo $this->load->view("{$this->pp}review_detail", $this->pargs, true);
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
        $this->load->view($this->pp.'create_review/template_questions_view', ['id' => $id, 'questions' => json_decode($template['questions'])]);
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
    function SaveFeedbackAnswer(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(null, true);
        //
        $questionId = $this->pmm->CheckAndSaveAnswer(
            $post['reviewId'],
            $post['revieweeId'],
            $post['reviewerId'],
            $post['questionId'],
            $post
        );
        //
        $this->res(['Status' => true, "Id" => $questionId]);
    }
    
    
    /**
     * 
     */
    function GetReviewReviewers($reviewId, $revieweeId){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $reviewers = $this->pmm->GetReviewReviewers($reviewId, $revieweeId);
        //
        $this->res(['Status' => true, "Data" => $reviewers]);
    }
   
    /**
     * 
     */
    function UpdateRevieweeReviewers(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->CheckAndInsertReviewee($post['reviewId'], $post['revieweeId']);
        //
        $insertArray = [];
        //
        $ids = [];
        //
        foreach($post['reviewerIds'] as $reviewer){
            //
            $insertArray[] = [
                'review_sid' => $post['reviewId'],
                'reviewee_sid' => $post['revieweeId'],
                'reviewer_sid' => $reviewer,
                'created_at' => date("Y-m-d H:i:s", strtotime("now")),
                'is_manager' => 0,
                'is_completed' => 0
            ];
            //
            $ids[] = $reviewer;
        }
        //
        $this->pmm->UpdateRevieweeReviewers($insertArray);
        $sent = 0;
        // Check if the review is started
        if(
            $this->pmm->IsReviewStarted(
                $post['reviewId'],
                $post['revieweeId']
                )
        ){
            $sent = 1;
            //
            $this->sendEmailNotifications(
                $post['reviewId'],
                $post['revieweeId'],
                $ids
            );
        }
        //
        $this->res([
            'Status' => true, 
            'Message' => 'You have successfully added new reviewers. '.(
                $sent ? 'An email notification has been sent to the selected reviewers.' : 'The selected reviewers are not notified because the review is not started yet.'
            ).''
        ]);
    }
    
    /**
     * 
     */
    function ArchiveReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->MarkReviewAsArchived($post['reviewId']);
       
        //
        $this->res(['Status' => true]);
    }
    
    
    /**
     * 
     */
    function ActivateReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->MarkReviewAsActive($post['reviewId']);
       
        //
        $this->res(['Status' => true]);
    }
    
    /**
     * 
     */
    function StopReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->StopReview($post['reviewId']);
       
        //
        $this->res(['Status' => true]);
    }
    
    /**
     * 
     */
    function StartReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->StartReview($post['reviewId']);
        //
        $this->sendEmailNotifications($post['reviewId']);
        
        //
        $this->res(['Status' => true]);
    }
    
    
    /**
     * 
     */
    function StopReviweeReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->StopReviweeReview($post['reviewId'], $post['revieweeId']);
        
        //
        $this->res(['Status' => true]);
    }
    
    /**
     * 
     */
    function StartReviweeReview(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->StartReviweeReview($post['reviewId'], $post['revieweeId']);
        $this->sendEmailNotifications($post['reviewId'], $post['revieweeId']);
       
        //
        $this->res(['Status' => true]);
    }
    
    /**
     * 
     */
    function UpdateReviewee(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $currentReviewers = $this->pmm->GetReviewReviewers($post['reviewId'], $post['revieweeId']);
        //
        $newReviewers = array_diff($post['reviwers'], $currentReviewers);
        $deletedReviewers = array_diff($currentReviewers, $post['reviwers']);
        //
        if(!empty($deletedReviewers)){
            $this->pmm->DeleteRevieweeReviewers($post['reviewId'], $post['revieweeId'],$deletedReviewers);
        }
        //
        if(!empty($newReviewers)){
            $this->pmm->AddRevieweeReviewers($post['reviewId'], $post['revieweeId'],$newReviewers);
        }
        //
        $this->pmm->UpdateRevieweeDates($post['reviewId'], $post['revieweeId'], $post);
        //
        $sent = 0;
        // Check if the review is started
        if(
            $this->pmm->IsReviewStarted(
                $post['reviewId'],
                $post['revieweeId']
            )
        ){
            $sent = 1;
            //
            $this->sendEmailNotifications(
                $post['reviewId'],
                $post['revieweeId'],
                array_values($newReviewers)
            );
        }
        //
        $this->res([
            'Status' => true, 
            'Message' => 'You have successfully added new reviewers. '.(
                $sent ? 'An email notification has been sent to the selected reviewers.' : 'The selected reviewers are not notified because the review is not started yet.'
            ).''
        ]);
    }
    
    
    /**
     * 
     */
    function GetReviewVisibility($id){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $bs = [];
        //
        $this->checkLogin($bs);
        //
        $data['visibility'] = $this->pmm->GetReviewVisibility($id);
        // Set company department and teams
        $data['company_dt'] = $this->pmm->GetCompanyDepartmentAndTeams($bs['companyId']);
        // Set company employees
        $data['company_employees'] = $this->pmm->GetAllEmployees($bs['companyId']);
        // Set company department and teams
        $data['company_roles'] = getRoles();

        echo $this->load->view("{$this->pp}visibility", $data, true);
    }
    
    /**
     * 
     */
    function UpdateVisibility(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $ins = [];
        //
        $ins['visibility_roles'] = !isset($post['roles']) ? '': implode(',', $post['roles']);
        $ins['visibility_departments'] = !isset($post['departments']) ? '': implode(',', $post['departments']);
        $ins['visibility_teams'] = !isset($post['teams']) ? '': implode(',', $post['teams']);
        $ins['visibility_employees'] = !isset($post['employees']) ? '': implode(',', $post['employees']);
        //
        $this->pmm->UpdateVisibility($ins, $post['reviewId']);
        //
        $this->res(['Status' => true]);
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
                
                if(isset($post['data']['questions'])){
                    //
                    $questions = [];
                    //
                    foreach($post['data']['questions'] as $question){
                        //
                        if(!isset($question['id'])){
                            $questions[] = array_merge($question, ['id' => generateRandomString(10)]);
                        } else{
                            $questions[] = $question;
                        }
                    }
                    $data_array['questions'] = json_encode($questions);
                }
                //
                if(!isset($post['id']) || $post['id'] == 0){
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
            case "ReviewStep2":
                // Set data array
                $data_array = [];
                //
                if(empty($post['data']['included'])){
                    //
                    $resp['Msg'] = "Please select at least one reviewee.";
                    $this->res($resp);
                }
                //
                $data_array['included_employees'] = implode(',', $post['data']['included']);
                //
                if(isset($post['data']['excluded'])){
                    $data_array['excluded_employees'] = implode(',', $post['data']['excluded']);
                } else{
                    $data_array['excluded_employees'] = '';
                }
                $data_array['reviewers'] = '';
                
                $reviewId = $this->pmm->UpdateReview($data_array, $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Reviewee added.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
            case "ReviewStep3":
                // Set data array
                $data_array = [];
                //
                if(empty($post['data']['reviewer_type'])){
                    //
                    $resp['Msg'] = "Please select the reviewer type.";
                    $this->res($resp);
                }
                //
                if(empty($post['data']['reviewees'])){
                    //
                    $resp['Msg'] = "Please add reviewers to reviewees.";
                    $this->res($resp);
                }

                $data_array['reviewers'] = json_encode($post['data']);
                
                $reviewId = $this->pmm->UpdateReview($data_array, $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Reviewer added.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
            case "SaveQuestion":
                // Set data array
                $data_array = [];
                // Get old 
                $questions = $this->pmm->GetReviewRowById($post['id'], $pargs['companyId'], ['questions'])['questions'];
                //
                if(!empty($questions) && $questions != null && $questions != 'null'){
                    $questions = json_decode($questions, true);
                    $questions = array_merge($questions, [$post['data']]);
                } else{
                    $questions[] = $post['data'];
                }
                //
                $data_array['questions'] = json_encode($questions);
                //
                $reviewId = $this->pmm->UpdateReview($data_array, $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Questions added.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
            case "SaveVideo":
                //
                $path = APPPATH.'../assets/performance_management/videos/'.$post['reviewId'].'/';
                //
                if(!is_dir($path)){
                    mkdir($path, DIR_WRITE_MODE, true);
                }
                //
                $idd = time().generateRandomString(7);
                //
                if($post['type'] == 'record'){
                    //
                    $newName = $path.$idd.'.webm';
                    //
                    file_put_contents($newName, base64_decode(str_replace('data:video/webm;base64,', '',$this->input->post('file', false))));
                    //
                    $resp['Msg'] = "Recorded video is uploaded.";
                    $resp['Id'] = $idd.'.webm';
                    $resp['Status'] = true;
                    $this->res($resp);
                }
                //
                if(empty($_FILES)){
                    //
                    $resp['Msg'] = "Please record/upload a video.";
                    $this->res($resp);
                }
                $newName = $idd.'.'.(explode('.', $_FILES['file']['name'])[1]);
                //
                if(!move_uploaded_file($_FILES['file']['tmp_name'], $path.$newName)){
                    //
                    $resp['Msg'] = "Failed to save video.";
                    $this->res($resp);
                } else{
                    $resp['Msg'] = "Video is uploaded";
                    $resp['Id'] = $newName;
                    $resp['Status'] = true;
                    $this->res($resp);
                }
            break;
            case "RemoveQuestion":
                // Get the question
                $questions = json_decode($this->pmm->GetReviewRowById($post['id'], $pargs['companyId'], ['questions'])['questions'], true);
                //
                $returningIndex = 0;
                //
                foreach($questions as $index => $question){
                    if($question['id'] == $post['question_id']){
                        //
                        $returningIndex = $index;
                        //
                        unset($questions[$index]);
                    }
                }
                //
                $reviewId = $this->pmm->UpdateReview(['questions' => json_encode(array_values($questions))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question deleted.';
                $resp['Id'] = $reviewId;
                $resp['Index'] = $returningIndex;
                //
                $this->res($resp);
            break;
            case "ReviewStep4":
                //
                $reviewId = $this->pmm->UpdateReview(['questions' => json_encode(array_values($post['questions']))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question added.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
            case "UpdateQuestion":
                // Get the question
                $questions = json_decode($this->pmm->GetReviewRowById($post['id'], $pargs['companyId'], ['questions'])['questions'], true);
                //
                foreach($questions as $index => $question){
                    if($question['id'] == $post['data']['id']){
                        //
                        $questions[$index] = $post['data'];
                    }
                }
                //
                $reviewId = $this->pmm->UpdateReview(['questions' => json_encode(array_values($questions))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question updated.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
            case "ReviewStep5":
                //
                $now = date('Y-m-d H:i:s', strtotime('now'));
                // Get the review
                $review = $this->pmm->GetReviewRowById($post['id'], $pargs['companyId']);

                // Set the questions
                $questions = json_decode($review['questions'], true);
                //
                $ins = [];
                //
                foreach($questions as $question){
                    //
                    $ins[] = [
                        'review_sid' => $review['reviewId'],
                        'question_type' => $question['question_type'],
                        'question' => json_encode($question),
                        'created_at' => $now
                    ];
                }
                //
                $this->pmm->insertReviewQuestions($ins);

                // Set reviwees
                //
                $ins = [];
                //
                $reviewees = explode(',', $review['included']);
                //
                if(!empty($review['excluded'])){
                    $reviewees = array_diff($reviewees, explode(',', $review['excluded']));
                }
                //
                foreach($reviewees as $reviewee){
                    //
                    $ins[] = [
                        'review_sid' => $review['reviewId'],
                        'reviewee_sid' => $reviewee,
                        'start_date' => $review['start_date'],
                        'end_date' => $review['end_date'],
                        'created_at' => $now,
                        'updated_at' => $now,
                        'is_started' => 0
                    ];
                }
                //
                $this->pmm->insertReviewReviewees($ins);


                // Set reviwers
                //
                $ins = [];
                //
                $reviewers = json_decode($review['reviewers'], true);
                //
                foreach($reviewers['reviewees'] as $reviewee => $reviewer){
                    //
                    $newReviewers = array_diff($reviewer['included'], isset($reviewer['excluded']) ? $reviewer['excluded'] : []);
                    //
                    foreach($newReviewers as $newReviewer){
                        //
                        $ins[] = [
                            'review_sid' => $review['reviewId'],
                            'reviewee_sid' => $reviewee,
                            'reviewer_sid' => $newReviewer,
                            'added_by' => $pargs['employerId'],
                            'created_at' => $now,
                            'is_manager' => $this->pmm->isManager($reviewee, $newReviewer, $pargs['companyId']),
                            'is_completed' => 0
                        ];
                    }
                }
                //
                $this->pmm->insertReviewReviewers($ins);
                // 
                $reviewId = $this->pmm->UpdateReview([
                    'share_feedback' => $post['feedback'], 
                    'is_draft' => 0
                ], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Review updated.';
                $resp['Id'] = $reviewId;
                //
                $this->res($resp);
            break;
        endswitch;
    }
    
    /**
     * 
     */
    function SaveTemplateStep(){
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
            case "InsertQuestion":
                // Set data array
                $data_array = [];
                // Get old 
                $questions = $this->pmm->GetTemplateById($post['id'])['questions'];
                //
                if(!empty($questions) && $questions != null && $questions != 'null'){
                    $questions = json_decode($questions, true);
                    $questions = array_merge($questions, [$post['data']]);
                } else{
                    $questions[] = $post['data'];
                }
                //
                $data_array['questions'] = json_encode($questions);
                //
                $this->pmm->UpdateTemplate($data_array, $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Questions added.';
                $resp['Id'] = $post['id'];
                //
                $this->res($resp);
            break;
            case "SaveVideo":
                //
                $path = APPPATH.'../assets/performance_management/videos/templates/'.$post['reviewId'].'/';
                //
                if(!is_dir($path)){
                    mkdir($path, DIR_WRITE_MODE, true);
                }
                //
                $idd = time().generateRandomString(7);
                //
                if($post['type'] == 'record'){
                    //
                    $newName = $path.$idd.'.webm';
                    //
                    file_put_contents($newName, base64_decode(str_replace('data:video/webm;base64,', '',$this->input->post('file', false))));
                    //
                    $resp['Msg'] = "Recorded video is uploaded.";
                    $resp['Id'] = $idd.'.webm';
                    $resp['Status'] = true;
                    $this->res($resp);
                }
                //
                if(empty($_FILES)){
                    //
                    $resp['Msg'] = "Please record/upload a video.";
                    $this->res($resp);
                }
                $newName = $idd.'.'.(explode('.', $_FILES['file']['name'])[1]);
                //
                if(!move_uploaded_file($_FILES['file']['tmp_name'], $path.$newName)){
                    //
                    $resp['Msg'] = "Failed to save video.";
                    $this->res($resp);
                } else{
                    $resp['Msg'] = "Video is uploaded";
                    $resp['Id'] = $newName;
                    $resp['Status'] = true;
                    $this->res($resp);
                }
            break;
            case "RemoveQuestion":
                // Get the question
                $questions = json_decode($this->pmm->GetTemplateById($post['id'], $pargs['companyId'], ['questions'])['questions'], true);
                //
                $returningIndex = 0;
                //
                foreach($questions as $index => $question){
                    if($question['id'] == $post['question_id']){
                        //
                        $returningIndex = $index;
                        //
                        unset($questions[$index]);
                    }
                }
                //
                $this->pmm->UpdateTemplate(['questions' => json_encode(array_values($questions))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question deleted.';
                $resp['Id'] = $post['id'];
                $resp['Index'] = $returningIndex;
                //
                $this->res($resp);
            break;
            case "UpdateQuestion":

                // Get the question
                $questions = json_decode($this->pmm->GetTemplateById($post['id'])['questions'], true);
                //
                foreach($questions as $index => $question){
                    if($question['id'] == $post['data']['id']){
                        //
                        $questions[$index] = $post['data'];
                    }
                }
                //
                $this->pmm->UpdateTemplate(['questions' => json_encode(array_values($questions))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question updated.';
                $resp['Id'] = $post['id'];
                //
                $this->res($resp);
            break;
            case "ReviewStep4":

                // Get the question
                //
                $this->pmm->UpdateTemplate(['questions' => json_encode(array_values($post['questions']))], $post['id']);
                //
                $resp['Status'] = true;
                $resp['Msg'] = 'Question updated.';
                $resp['Id'] = $post['id'];
                //
                $this->res($resp);
            break;
            
        endswitch;
    }

    /**
     * 
     */
    function UploadQuestionAttachment(){
        $filename =  upload_file_to_aws('file', 1, $_FILES['file']['name']);
        echo $filename;
    }

    /**
     * Reviews
     * 
     * @employee Mubashir Ahmed 
     * @date     02/01/2021
     * 
     * @return Void
     */
    function report($reviewId = 'all', $employeeIds = 'all', $startDate = 'all', $endDate = 'all'){
        // 
        $this->checkLogin($this->pargs);
        // Set titleub
        $this->pargs['title'] = 'Performance Management - Report';
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        //
        $this->pargs['graph1'] = $this->pmm->GetCompletedReviews($this->pargs['companyId']);
        $this->pargs['graph2'] = $this->pmm->GetReviewCountByStatus($this->pargs['companyId']);        
        //
        $this->load->view($this->header, $this->pargs);
        $this->load->view("{$this->pp}header");
        $this->load->view("{$this->pp}report/index");
        $this->load->view("{$this->pp}footer");
        $this->load->view($this->footer);
    }

    //
    function GetGoalBody(){
        //
        $this->checkLogin($this->pargs);
        // Set company employees
        $this->pargs['company_employees'] = $this->pmm->GetAllEmployees($this->pargs['companyId']);
        // Set company department and teams
        $this->pargs['company_dt'] = $this->pmm->GetCompanyDepartmentAndTeams($this->pargs['companyId']);
        //
        echo $this->load->view("{$this->pp}goals/create", $this->pargs, true);
    }
    
    //
    function SaveGoal(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->checkLogin($this->pargs);
        //
        $insertArray = [];
        $insertArray['created_by'] =  $this->pargs['employerId'];
        $insertArray['employee_sid'] = 0;
        $insertArray['company_sid'] = $this->pargs['companyId'];
        $insertArray['title'] = $post['title'];
        $insertArray['description'] = $post['description'];
        $insertArray['goal_type'] = $post['type'];
        $insertArray['status'] = 1;
        $insertArray['measure_type'] = $post['measureUnit'];
        $insertArray['custom_measure_type'] = $post['customUnit'];
        $insertArray['target'] = $post['target'];
        $insertArray['start_date'] = formatDateToDB($post['startDate']);
        $insertArray['end_date'] = formatDateToDB($post['endDate']);
        $insertArray['created_at'] = date("Y-m-d H:i:s", strtotime('now'));
        $insertArray['updated_at'] = date("Y-m-d H:i:s", strtotime('now'));
        $insertArray['roles'] = isset($post['roles']) ? json_encode($post['roles']) : '{}';
        $insertArray['teams'] = isset($post['teams']) ? json_encode($post['teams']) : '{}';
        $insertArray['departments'] = isset($post['departments']) ? json_encode($post['departments']) : '{}';
        $insertArray['employees'] = isset($post['employees']) ? json_encode($post['employees']) : '{}';
        //
        $ids = [];
        //
        if($insertArray['goal_type'] == 1){
            $this->pmm->InsertGoal($insertArray);
        } else if($insertArray['goal_type'] == 2){
            //
            $ids = $this->pmm->GetEmployeesByDeparmentIds($post['departmentIds']);
        } else if($insertArray['goal_type'] == 3){
            $ids = $this->pmm->GetEmployeesByTeamIds($post['teamIds']);
        } else if($insertArray['goal_type'] == 4){
            $ids = $post['employeeIds'];
        }

        if(!empty($ids)){
            //
            foreach($ids as $id){
                $insertArray['employee_sid'] = $id;
                $this->pmm->InsertGoal($insertArray);
            }
        }
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    
    //
    function CloseGoal(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $args = [];
        //
        $this->checkLogin($args);
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $insertArray = [];
        $insertArray['goal_sid'] = $post['goalId'];
        $insertArray['employee_sid'] = $args['employerId'];
        $insertArray['action'] = 'closed';
        $insertArray['note'] = json_encode([]);
        $insertArray['created_at'] = date("Y-m-d H:i:s", strtotime('now'));
        //
        $this->pmm->InsertGoalHistory($insertArray);
        //
        $updateArray = [];
        $updateArray['status'] =  0;
        //
        $this->pmm->UpdateGoal($updateArray, $post['goalId']);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    //
    function OpenGoal(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $args = [];
        //
        $this->checkLogin($args);
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $insertArray = [];
        $insertArray['goal_sid'] = $post['goalId'];
        $insertArray['employee_sid'] = $args['employerId'];
        $insertArray['action'] = 'opened';
        $insertArray['note'] = json_encode([]);
        $insertArray['created_at'] = date("Y-m-d H:i:s", strtotime('now'));
        //
        $this->pmm->InsertGoalHistory($insertArray);
        //
        $updateArray = [];
        $updateArray['status'] =  1;
        //
        $this->pmm->UpdateGoal($updateArray, $post['goalId']);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    //
    function UpdateGoal(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $args = [];
        //
        $this->checkLogin($args);
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $insertArray = [];
        $insertArray['goal_sid'] = $post['goalId'];
        $insertArray['employee_sid'] = $args['employerId'];
        $insertArray['action'] = 'updated';
        $insertArray['note'] = json_encode($post);
        $insertArray['created_at'] = date("Y-m-d H:i:s", strtotime('now'));
        //
        $this->pmm->InsertGoalHistory($insertArray);
        //
        $updateArray = [];
        $updateArray['on_track'] = $post['on_track'];
        $updateArray['completed_target'] =  $post['completed'];
        $updateArray['target'] =  $post['target'];
        //
        $this->pmm->UpdateGoal($updateArray, $post['goalId']);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    //
    function UpdateSettings(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $this->pmm->CheckAndInsertData($post);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'You have successfully updated the settings.';
        //
        $this->res($resp);
    }
    
    //
    function AddComment(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() || empty($this->input->post(NULL, TRUE)) ){
            $this->res($resp, true);
        }
        //
        $args = [];
        //
        $this->checkLogin($args);
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $insertArray = [];
        $insertArray['goal_sid'] = $post['goalId'];
        $insertArray['sender_sid'] = $args['employerId'];
        $insertArray['message'] = $post['msg'];
        $insertArray['created_at'] = date("Y-m-d H:i:s", strtotime('now'));
        $insertArray['updated_at'] = date("Y-m-d H:i:s", strtotime('now'));
        //
        $this->pmm->InsertComment($insertArray);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    //
    function GetGoalComments($id){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() ){
            $this->res($resp, true);
        }
        $resp['Data'] = $this->pmm->GetGoalComments($id);
        //
        $resp['Status'] = true;
        $resp['Msg'] = 'Procees';
        //
        $this->res($resp);
    }
    
    //
    function SaveTemplate(){
        //
        $resp = ['Status' => false, 'Msg' => "Invalid request"];
        //
        if( !$this->input->is_ajax_request() ){
            $this->res($resp, true);
        }
        //
        $session = $this->session->userdata('logged_in');
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if($post['Id'] != 0){
            $Id = $post['Id'];
            $this->pmm->UpdateTemplate(
                [
                    'name' => $post['name'],
                    'updated_at' => date('Y-m-d H:i:s', strtotime('now')),
                ],
                $post['Id']
            );
        } else{
            $Id = 
            $this->pmm->InsertTemplate(
                [
                    'name' => $post['name'],
                    'company_sid' => $session['company_detail']['sid'],
                    'employee_sid' => $session['employer_detail']['sid'],
                    'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('now'))
                ]
            );
        }
        
        $resp['Status'] = true;
        $resp['Id'] = $Id;
        $resp['Msg'] = 'Proceed';
        //
        $this->res($resp);
    }


    //
    function filterGoals(
        $goals,
        $employerId
    ){
        //
        if(empty($goals)){
            return $goals;
        }
        //
        $myGoals = [];
        //
        foreach($goals as $goal){
            if($goal['employee_sid'] == $employerId){
                $myGoals[] = $goal;
            }
        }
        //
        return $myGoals;
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
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 ? 1 : 0;
        $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;
        $data['load_view'] = $data['session']['company_detail']['ems_status'];
        // $data['load_view'] = 1;
        // $data['hide_employer_section'] = 1;
        //
        if ($return) {
            return true;
        }
        else {
            //
            $data['security_details'] = db_get_access_level_details($data['employerId'], NULL, $data['session']);
            // Get Goals
            $data['Goals'] = $this->pmm->GetAllGoals($data['session']['company_detail']['sid']);
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

    //
    private function sendEmailNotifications($id, $revieweeId = 0, $ids = []){
        //
        $record = $this->pmm->GetReviewByIdByReviewers($id, $revieweeId)[0];
        //
        $hf = message_header_footer($record['company_sid'], $record['CompanyName']);
        //
        if(empty($record['Reviewees'])){
            return;
        }
        //
        $template = get_email_template(REVIEW_ADDED);

        $this->load->model('Hr_documents_management_model', 'HRDMM');
        foreach($record['Reviewees'] as $row){
            //
            if(!empty($ids)){
                if(!in_array($row[0]['reviewer_sid'], $ids)){
                    continue;
                }
            }
            //
            if(!$this->HRDMM->isActiveUser($row[0]['reviewer_sid'])){
                continue;
            }
            //
            $replaceArray = [];
            $replaceArray['{{first_name}}'] = ucwords($row[0]['reviewer_first_name']);
            $replaceArray['{{last_name}}'] = ucwords($row[0]['reviewer_last_name']);
            $replaceArray['{{review_title}}'] = $record['review_title'];
            
            $replaceArray['{{table}}'] = $this->load->view('table', ['records' => $row, 'id' => $record['sid']], true);
            //
            $body = $hf['header'].str_replace(array_keys($replaceArray), $replaceArray, $template['text']).$hf['footer'];

            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $row[0]['reviewer_email'],
                $template['subject'],
                $body,
                $record['CompanyName']
            );
        }
    }
}