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
        // // Get Assigned Reviews 
        $this->pargs['AssignedReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 0);
        $this->pargs['FeedbackReviews'] = $this->pmm->GetReviewsByTypeForDashboard($this->pargs['employerId'], 1);
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
        $this->pargs['title'] = 'Performance Management - Reviews';
        // Set logged in employee departments and teams
        $this->pargs['employee_dt'] = $this->pmm->getMyDepartmentAndTeams($this->pargs['companyId'], $this->pargs['employerId']);
        // Set employee information for the blue screen
        $this->pargs['employee'] = $this->pargs['session']['employer_detail'];
        //
        $this->pargs['reviews'] = $this->pmm->GetAllReviews($this->pargs['employerId'], $this->pargs['employerRole'], $this->pargs['level'], $this->pargs['companyId']);
        // Check and assign
        $this->pmm->CheckAndAssign($this->pargs['companyId']);

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
        //
        if($this->pargs['review']['is_draft'] == 0){
            redirect('performance-management/reviews','refresh');
            return;
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
    function SaveFeedbackAnswer(){
        //
        if( !$this->input->is_ajax_request() ){
            $this->res([], true);
        }
        //
        $post = $this->input->post(null, true);
        //
        $this->pmm->CheckAndSaveAnswer(
            $post['reviewId'],
            $post['revieweeId'],
            $post['reviewerId']
        );
       

        //
        $this->res(); // TODO
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
                        }
                    }
                    $data_array['questions'] = json_encode($questions);
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
                }
                
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