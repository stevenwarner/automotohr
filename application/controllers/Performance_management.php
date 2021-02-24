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
    //
    private $tables = [];

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
        $this->tables = [
            'PM' => 'performance_management',
            'PMT' => 'performance_management_templates',
            'PMCT' => 'performance_management_company_templates',
            'USER' => 'users',
            'DM' => 'departments_management',
            'DTM' => 'departments_team_management',
            'DME' => 'departments_employee_2_team',
            'PMR' => 'performance_management_reviewees',
            'PMRV' => 'performance_management_reviewers',
            'PMQ' => 'performance_management_review_questions',
        ];
        //
        $this->pargs = [];
        // Load helper
        $this->load->helper($this->tables['PM']);
        // Load modal
        $this->load->model('performance_management_model', 'pmm', TRUE, $this->tables);
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
     * handler
     * 
     * @employee Mubashir Ahmed
     * @date     02/17/2021
     * 
     * @return Void
     */
    function handler(){
        if($this->input->method(false) == 'get') $this->get_handler();
        else if($this->input->method(false) == 'post') $this->post_handler();
        else{
            res(['Status' => false, 'Response' => 'Invalid request made.']);
        }
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

            // Get all company employees
            case "get_all_company_employees":
                $list = $this->pmm->getAllCompanyEmployees($pargs['companyId']);
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
     * Post handler
     * 
     * @employee Mubashir Ahmed
     * @date     02/10/2021
     * 
     * @return JSON
     */
    function post_handler(){
        $pargs = [];
        // Check session
        $isLogin = $this->checkLogin($pargs, true);
        //
        if(!$isLogin) res($this->resp);
        //
        $this->resp['Redirect'] = false;
        //
        $params = $this->input->post(NULL, TRUE);
        //
        switch($params['action']):
            // Video
            case "basetovideo":
                $video = str_replace('data:video/webm;base64,', '', $this->input->post('data'));
                //
                $pd = APPPATH.'../assets/performance_management/videos/'.$params['id'].'/';
                // _e($video, true, true);
                $filename = 'video_'.($params['questionId']).'.webm';
                //
                if(!is_dir($pd)) mkdir($pd, 0777, true);
                //
                $handler = fopen($pd.$filename, 'wb');
                fwrite($handler, base64_decode($video));
                fclose($handler);
            break;
            // Save review
            case "save_review":
                //
                $ins = [];
                $ins['company_sid'] = $pargs['companyId'];
                $ins['review_title'] = $params['title'];
                $ins['description'] = $params['description'];
                $ins['frequency'] = $params['schedule']['frequency'];
                $ins['review_start_date'] = !empty($params['schedule']['reviewStartDate']) ? $params['schedule']['reviewStartDate'] : NULL;
                $ins['review_end_date'] = !empty($params['schedule']['reviewEndDate']) ? $params['schedule']['reviewEndDate'] : NULL;
                $ins['repeat_after'] = $params['schedule']['repeatVal'];
                $ins['repeat_type'] = $params['schedule']['repeatType'];
                $ins['review_runs'] = json_encode($params['schedule']['customRuns']);
                $ins['repeat_review'] = $params['schedule']['continueReview'];
                $ins['review_due'] = $params['schedule']['reviewDue'];
                $ins['visibility_roles'] = implode(',', $params['visibility']['roles']);
                $ins['visibility_departments'] = implode(',', $params['visibility']['departments']);
                $ins['visibility_teams'] = implode(',', $params['visibility']['teams']);
                $ins['visibility_employees'] = implode(',', $params['visibility']['individuals']);
                //
                if($ins['frequency'] == 'custom'){
                    $ins['repeat_after'] = 0;
                    $ins['review_start_date'] = 
                    $ins['review_end_date'] = ''; 
                    $ins['repeat_type'] = 'day';
                } else{
                    $ins['review_due'] = 0;
                    $ins['review_runs'] = '[]';
                    $ins['repeat_review'] = 0;
                }
                //
                if($ins['frequency'] == 'onetime'){
                    $ins['repeat_after'] = 0;
                    $ins['repeat_type'] = 'day';
                }
                //
                if(!empty($ins['review_start_date'])){
                    $ins['review_start_date'] = formatDateToDB($ins['review_start_date']);
                    $ins['review_end_date'] = formatDateToDB($ins['review_end_date']);
                } else{
                    unset($ins['review_start_date'], $ins['review_end_date']);
                }
                $ins['last_updated_by'] = $pargs['employerId'];
                // Insert the review into the database
                $reviewId = $this->pmm->_insert($this->tables['PM'], $ins);
                // History array
                $ins = [];
                $ins['review_sid'] = $reviewId;
                $ins['employee_sid'] = $pargs['employerId'];
                $ins['action'] = 'review_created';
                $ins['action_fields'] = json_encode(['action' => 'created', 'msg' => '']);
                // Insert the review log
                $this->pmm->_insert('perfomance_management_log', $ins);
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                $this->resp['Data'] = $reviewId;
                //
                res($this->resp);
            break;
            // Update review
            case "update_review":
                // For Schedule
                if($params['step'] == 'schedule'){
                    //
                    $ins = [];
                    $ins['review_title'] = $params['title'];
                    $ins['description'] = $params['description'];
                    $ins['frequency'] = $params['schedule']['frequency'];
                    $ins['review_start_date'] = !empty($params['schedule']['reviewStartDate']) ? $params['schedule']['reviewStartDate'] : NULL;
                    $ins['review_end_date'] = !empty($params['schedule']['reviewEndDate']) ? $params['schedule']['reviewEndDate'] : NULL;
                    $ins['repeat_after'] = $params['schedule']['repeatVal'];
                    $ins['repeat_type'] = $params['schedule']['repeatType'];
                    $ins['review_runs'] = json_encode($params['schedule']['customRuns']);
                    $ins['repeat_review'] = $params['schedule']['continueReview'];
                    $ins['review_due'] = $params['schedule']['reviewDue'];
                    $ins['visibility_roles'] = implode(',', $params['visibility']['roles']);
                    $ins['visibility_departments'] = implode(',', $params['visibility']['departments']);
                    $ins['visibility_teams'] = implode(',', $params['visibility']['teams']);
                    $ins['visibility_employees'] = implode(',', $params['visibility']['individuals']);
                    //
                    if($ins['frequency'] == 'custom'){
                        $ins['repeat_after'] = 0;
                        $ins['review_start_date'] = 
                        $ins['review_end_date'] = ''; 
                        $ins['repeat_type'] = 'day';
                    } else{
                        $ins['review_due'] = 0;
                        $ins['review_runs'] = '[]';
                        $ins['repeat_review'] = 0;
                    }
                    //
                    if($ins['frequency'] == 'onetime'){
                        $ins['repeat_after'] = 0;
                        $ins['repeat_type'] = 'day';
                    }
                    //
                    if(!empty($ins['review_start_date'])){
                        $ins['review_start_date'] = formatDateToDB($ins['review_start_date']);
                        $ins['review_end_date'] = formatDateToDB($ins['review_end_date']);
                    } else{
                        unset($ins['review_start_date'], $ins['review_end_date']);
                    }
                    $ins['last_updated_by'] = $pargs['employerId'];
                }

                // For Reviewees
                if($params['step'] == 'reviewees'){
                    //
                    $upd = [];
                    $upd['included_employees'] = isset($params['reviewees']['included']) ? json_encode($params['reviewees']['included']) : '[]';
                    $upd['excluded_employees'] = isset($params['reviewees']['excluded']) ? json_encode($params['reviewees']['excluded']) : '[]';
                    //
                    $this->pmm->_update($this->tables['PM'], $upd, ['sid' => $params['id']]);
                }
                
                // For Reviewers
                if($params['step'] == 'reviewers'){
                    //
                    $upd = [];
                    $upd['reviewers'] = json_encode($params['reviewer']);
                    //
                    $this->pmm->_update($this->tables['PM'], $upd, ['sid' => $params['id']]);
                }
               
                // For Questions
                if($params['step'] == 'questions'){
                    //
                    $upd = [];
                    $upd['questions'] = json_encode($params['questions']);
                    //
                    $this->pmm->_update($this->tables['PM'], $upd, ['sid' => $params['id']]);
                }
                
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                //
                res($this->resp);
            break;
            // Save
            case "finish_save_review":
                //
                $upd = [];
                $upd['share_feedback'] = $params['feedback'];
                $upd['is_draft'] = 0;
                $upd['questions'] = '';
                $upd['reviewers'] = '';
                $upd['included_employees '] = '';
                // Get review
                $review = $this->pmm->getReviewByid($params['id'], ['included_employees', 'reviewers', 'questions', 'review_start_date', 'review_end_date']);
                //
                $is_started = 0;
                if($review['review_start_date'] >= date('Y-m-d', strtolower('now')) && $review['review_end_date'] < date('Y-m-d', strtolower('now'))) $is_started = 1;
                //
                $ins = [];
                // Lets set reviwees
                foreach(json_decode($review['included_employees'], true) as $reviewee){
                    $t = [];
                    $t['review_sid']  = $params['id'];
                    $t['reviewee_sid']  = $reviewee;
                    if(!empty($review['review_start_date'])){
                        $t['start_date']  = $review['review_start_date'];
                        $t['end_date']  = $review['review_end_date'];
                    }
                    $t['is_started']  = $is_started;
                    //
                    $ins[] = $t;
                }
                //
                $this->pmm->_insert($this->tables['PMR'], $ins);
                //
                $ins = [];
                // Lets set reviewers
                foreach(json_decode($review['reviewers'], true)['employees'] as $k => $reviewer){
                    //
                    $revieweeId = $k;
                    $reviewers = [];
                    $reviewers = !empty($reviewer['reporting_manager']) ? array_merge($reviewers, array_keys($reviewer['reporting_manager'])) : $reviewers;
                    $reviewers = !empty($reviewer['self_review']) ? array_merge($reviewers, array_keys($reviewer['self_review'])) : $reviewers;
                    $reviewers = !empty($reviewer['peer']) ? array_merge($reviewers, array_keys($reviewer['peer'])) : $reviewers;
                    $reviewers = !empty($reviewer['specific']) ? array_merge($reviewers, array_keys($reviewer['specific'])) : $reviewers;
                    if(!empty($reviewer['custom'])){
                        $reviewers = array_merge($reviewers, array_keys($reviewer['custom']));
                        //
                        if(!empty($reviewer['excluded'])){
                            $reviewers = array_diff($reviewers, array_keys($reviewer['excluded']));
                        }
                    }
                    //
                    $reviewers = array_unique($reviewers, SORT_STRING);
                    //
                    if(!empty($reviewers)){
                        foreach($reviewers as $em){
                            $t = [];
                            $t['review_sid']  = $params['id'];
                            $t['reviewee_sid']  = $revieweeId;
                            $t['reviewer_sid']  = $em;
                            $t['added_by']  = $pargs['employerId'];
                            //
                            $ins[] = $t;
                        }
                    }
                }
                //
                $this->pmm->_insert($this->tables['PMRV'], $ins);
                //
                $ins = [];
                // Lets add questions
                $questions = json_decode($review['questions'], true);
                //
                foreach($questions as $question){
                    $t = [];
                    $t['review_sid'] = $params['id'];
                    $t['question_type'] = $question['question_type'];
                    $t['question'] = json_encode($question);
                    //
                    $ins[] = $t;
                }
                //
                $this->pmm->_insert($this->tables['PMQ'], $ins);
                //
                $this->pmm->_update($this->tables['PM'], $upd, ['sid' => $params['id']]);
                //
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'Proceed.';
                //
                res($this->resp);
            break;

            // Review listing
            case "get_review_listing":
                $this->resp['Data'] = $this->pmm->getReviews($pargs['companyId'], $pargs['employerId'], $pargs['employerRole'], $pargs['level']);
                $this->resp['Status'] = TRUE;
                $this->resp['Response'] = 'Proceed.';
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
        $data['employerDetails'] = $data['session']['employer_detail'];
        $data['companyDetails'] = $data['session']['company_detail'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        $data['employerName'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        $data['isSuperAdmin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['level'] = $data['session']['employer_detail']['access_level_plus'] == 1 || $data['session']['employer_detail']['pay_plan_flag'] == 1 ? 1 : 0;
        $data['employerRole'] = $data['session']['employer_detail']['access_level'] ;
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