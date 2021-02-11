<?php defined('BASEPATH') or exit('No direct script access allowed');

class Performance_review extends Public_Controller
{

    private $res = array();
    private $limit = 50;
    //
    private $ratingText = [
        'Strongly Disagree',
        'Disagree',
        'Neutral',
        'Agree',
        'Strongly Agree'
    ];

    private $dt = [];

    function __construct()
    {
        parent::__construct();
        $this->load->model('performance_review_model', 'prm');
        $this->res['Status'] = FALSE;
        $this->res['Redirect'] = TRUE;
        $this->res['Response'] = 'Invalid request';
        $this->check_login($this->dt);
        //
        if(!isset($_SESSION['PRD'])){
            $this->prm->checkAndAddTemplates($this->dt['company_sid'], $this->dt['employer_sid']);
        }
    }

    /**
     * Template handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function template($page = 'view', $id = NULL)
    {
        $data = array();
        $this->check_login($data);
        //
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Performance Review - ' . (ucfirst($page));
        //
        $data['RatingText'] = $this->ratingText;
        //
        if ($page != 'add' && $id !== null) {
            //
            $data['template'] = $this->prm->getTemplateById($id);
            $data['id'] = $id;
        }
        //
        $this->load->view('main/header', $data);
        if($page == 'view' && $id !== null){
            $this->load->view('manage_employer/PerformanceReview/template/single_' . ($page) . '');
        } else{
            $this->load->view('manage_employer/PerformanceReview/template/' . ($page) . '');
        }
        $this->load->view('main/footer');
    }


    /**
     * Review handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function review($page = 'view', $id = NULL, $cId = null, $eId = null)
    {
        $data = array();
        $this->check_login($data);
        //
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Performance Review - ' . (ucfirst($page));
        //
        $data['RatingText'] = $this->ratingText;
        //
        if ($page != 'view') {
            $data['templates'] = $this->prm->getTemplates($data['company_sid']);
            $data['employees'] = $this->prm->getReviewees($data['company_sid'], $data['employer_sid']);
        }
        if ($page != 'create' && $id !== null) {
            //
            $data['review'] = $this->prm->getReviewById($id);
            $data['id'] = $id;
            $data['cId'] = $cId;
            $data['eId'] = $eId;
            //
            if($page == 'edit'){
                //
                $oldReviewees = $data['review']['reviewees'];
                //
                $startDate = new DateTime ($data['review']['main']['start_date']);
                $endDate = new DateTime ($data['review']['main']['end_date']);
                //
                $data['review']['main']['startDate'] = DateTime::createFromFormat('Y-m-d H:i:s', $data['review']['main']['start_date'])->format('m/d/Y');
                $data['review']['main']['dueDays'] = $endDate->diff($startDate)->format('%a');
                //
                if(count($oldReviewees)){
                    $data['review']['reviewees'] = [];
                    $data['review']['reviewees']['department'] = $data['review']['department'];
                    $data['review']['reviewees']['revieweeType'] = '4';
                    //
                    if($data['review']['main']['review_type'] == 'specific_employees') $data['review']['reviewees']['revieweeType'] = '3';
                    if($data['review']['main']['review_type'] == 'sub_cordinates') $data['review']['reviewees']['revieweeType'] = '2';
                    if($data['review']['main']['review_type'] == 'entire_company') $data['review']['reviewees']['revieweeType'] = '1';
                    //
                    $data['review']['reviewees']['selectedEmployees'] = array_values(array_unique(array_column($oldReviewees, 'employee_sid'), SORT_STRING));

                    //
                    $isSelf = false;
                    $conductors = [];
                    //
                    foreach($oldReviewees as $k => $v){
                        //
                        if(!isset($conductors[$v['employee_sid']])) $conductors[$v['employee_sid']] = [];
                        //
                        $conductors[$v['employee_sid']][] = $v['conductor_sid'];
                    }
                    //
                    $data['review']['reviewers'] = $conductors;
                }
            }
        }
        //
        if($cId != null && $eId != null){
            //
            $data['answers'] = $this->prm->getAnswersId($id,$cId,$eId);
            //
            if(count($data['answers'])){

                foreach($data['review']['questions'] as $k => $question){
                    $data['review']['questions'][$k]['answer'] = 
                    $data['answers'][array_search($question['sid'], array_column($data['answers'], 'portal_review_question_sid'))];
                }
            }
        }
        //
        $this->load->view('main/header', $data);
        //
        if ($page == 'view' && $id != null) {
            $this->load->view('manage_employer/PerformanceReview/review/employee_' . ($page) . '');
        } else {
            $this->load->view('manage_employer/PerformanceReview/review/' . ($page) . '');
        }
        $this->load->view('main/footer');
    }

    /**
     * Assigned handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function assigned($page = 'view', $id = NULL)
    {
        $data = array();
        $this->check_login($data);
        //
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Performance Review - ' . (ucfirst($page));
        //
        $data['RatingText'] = $this->ratingText;
        //
        $data['review'] =
            $this->prm->getEmployeeReviewQuestions($id, $data['employer_sid']);
            //
        if($data['companyData']['ems_status'] == 1){
            $data['load_view'] = 'old';
            $data['employee'] = $data['session']['employer_detail'];
            $this->load->view('main/header', $data);
            //
            if ($id == null) {
                $this->load->view('manage_employer/PerformanceReview/assigned/ems/' . ($page) . '');
            } else {
                $this->load->view('manage_employer/PerformanceReview/assigned/ems/single_' . ($page) . '');
            }
            $this->load->view('main/footer');
        } else{
            $this->load->view('main/header', $data);
            //
            if ($id == null) {
                $this->load->view('manage_employer/PerformanceReview/assigned/' . ($page) . '');
            } else {
                $this->load->view('manage_employer/PerformanceReview/assigned/single_' . ($page) . '');
            }
            $this->load->view('main/footer');
        }
    }
    
    
    /**
     * Feedback handler
     *
     * @param $page Optional
     * Default is 'view'
     * @param $id   Optional
     * Default is 'NULL'
     *
     */
    function feedback($page = 'view', $id = NULL)
    {
        $data = array();
        $this->check_login($data);
        //
        // Reset page
        $page = preg_replace('/[^a-z]/', '', strtolower($page));
        //
        $data['page'] = $page;
        $data['title'] = 'Performance Review - ' . (ucfirst($page));
        //
        $data['employees'] = $this->prm->getReviewees($data['company_sid'], $data['employer_sid']);
        $data['RatingText'] = $this->ratingText;
        //
        if($id != null) $data['review'] = $this->prm->getReviewById($id);
        //
        $this->load->view('main/header', $data);
        //
        if ($id == null) {
            $this->load->view('manage_employer/PerformanceReview/Feedback/' . ($page) . '');
        } else {
            $this->load->view('manage_employer/PerformanceReview/Feedback/single_view');
        }
        $this->load->view('main/footer');
    }
    
    
    /**
     * Report handler
     *
     */
    function report()
    {
        $data = array();
        $this->check_login($data);
        //
        $data['title'] = 'Performance Review - Report';
        //
        $data['report'] = $this->prm->getReportAll($data['company_sid']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/PerformanceReview/report/index');
        $this->load->view('main/footer');
    }

    /**
     * Review print download handler
     *
     * @param $action
     * @param $reviewSid
     * @param $conductorSid   Optional
     * Default is 'NULL'
     * @param $employeeSid   Optional
     * Default is 'NULL'
     *
     */
    function pd($action, $reviewSid, $conductorSid = NULL, $employeeSid = NULL)
    {
        $data = array();
        $this->check_login($data);
        //
        $data['action'] = $action;
        $data['reviewSid'] = $reviewSid;
        $data['conductorSid'] = $conductorSid;
        $data['employeeSid'] = $employeeSid;
        //
        $data['title'] = 'Performance Review - Print / Download';
        //
        $data['review'] = $this->prm->getReviewById($reviewSid);
            //
        if($conductorSid != null && $employeeSid != null){
            //
            $data['answers'] = $this->prm->getAnswersId($reviewSid,$conductorSid,$employeeSid);
            //
            if(count($data['answers'])){

                foreach($data['review']['questions'] as $k => $question){
                    $data['review']['questions'][$k]['answer'] = 
                    $data['answers'][array_search($question['sid'], array_column($data['answers'], 'portal_review_question_sid'))];
                }
            }
        }
        //
        $this->load->view('manage_employer/PerformanceReview/pd', $data);
    }



    /**
     * AJAX request handler
     *
     * @accepts POST
     * 'action'
     *
     * @return JSON
     */
    function handler()
    {
        // Check for ajax request
        if (!$this->input->is_ajax_request()) $this->resp();
        $formpost = $this->input->post(NULL, TRUE);
        // $formpost = @json_decode(@file_get_contents("php://input"), true);
        // Check post size and action
        if (!sizeof($formpost) || !isset($formpost['Action'])) $this->resp();
        //
        $data = array();
        $this->res['Redirect'] = $this->check_login($data, true);
        // For expired session
        if ($this->res['Redirect'] == false) {
            $this->res['Response'] = 'Your login session has expired.';
            $this->resp();
        }
        $this->res['Redirect'] = FALSE;
        //
        switch (strtolower($formpost['Action'])) {
                // Get Review templates 
            case 'fetch_templates':
                // Fetch templates by company id
                $templates = $this->prm->templatesByCompanyId(
                    $data['company_sid'],
                    $this->limit,
                    $formpost
                );
                // No template are found
                if (!count($templates)) {
                    $this->res['Response'] = 'No templates found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $templates;
                $this->res['Status'] = TRUE;
                $this->res['Limit'] = $this->limit;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
                // SAVE TEMPLATE
            case "save_template":
                // Set insert data
                $ins = [];
                $ins['title'] = $formpost['Data']['main']['title'];
                $ins['status'] = $formpost['Data']['main']['status'];
                $ins['is_custom'] = 1;
                $ins['is_default'] = 0;
                $ins['company_sid'] = $data['company_sid'];
                $ins['creator_sid'] = $data['employer_sid'];
                $ins['description'] = $formpost['Data']['main']['description'];
                // Check the template title
                $doExists = $this->prm->doTitleExists(
                    $formpost['Data']['main'],
                    $data['company_sid']
                );
                //
                if ($doExists) {
                    $this->res['Response'] = 'Template title already in use.';
                    $this->resp();
                }
                //
                $insertId = $this->prm->insertTemplate($ins);
                //
                if (!$insertId) {
                    $this->res['Response'] = 'Failed to add template. Please, try again in a few seconds';
                    $this->resp();
                }
                //
                foreach ($formpost['Data']['questions'] as $question) {
                    //
                    $ins = [];
                    $ins['portal_review_questionnaire_type_sid'] = $insertId;
                    $ins['title'] = $question['question'];
                    $ins['description'] = $question['description'];
                    $ins['question_type'] = $question['type'];
                    $ins['sort_order'] = $question['sortOrder'];
                    $ins['text'] = '';
                    $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                    $ins['scale'] = $question['ratingScale'];
                    $ins['label_question'] = json_encode([]);
                    //
                    if ($question['useLabels'] == 'true') {
                        $ins['label_question'] = json_encode($question['ratingLabels']);
                    }
                    //
                    $insertQuestion = $this->prm->insertTemplateQuestion($ins);
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Template created.';
                $this->resp();
                break;
                // Update template
            case "update_template":
                $formpost['Data']['main']['sid'] = $formpost['Id'];
                // Set update data
                $ins = [];
                $ins['title'] = $formpost['Data']['main']['title'];
                $ins['status'] = $formpost['Data']['main']['status'];
                $ins['description'] = $formpost['Data']['main']['description'];
                // Check the template title
                $doExists = $this->prm->doTitleExists(
                    $formpost['Data']['main'],
                    $data['company_sid']
                );
                //
                if ($doExists) {
                    $this->res['Response'] = 'Template title already in use.';
                    $this->resp();
                }
                //
                $this->prm->updateTemplate($ins, $formpost['Data']['main']['sid']);
                $insertId = $formpost['Data']['main']['sid'];
                //
                if (!$insertId) {
                    $this->res['Response'] = 'Failed to update template. Please, try again in a few seconds';
                    $this->resp();
                }
                // Drop all the questions
                $this->prm->dropTemplateQuestions($insertId);
                //
                foreach ($formpost['Data']['questions'] as $question) {
                    //
                    $ins = [];
                    $ins['portal_review_questionnaire_type_sid'] = $insertId;
                    $ins['title'] = $question['question'];
                    $ins['description'] = $question['description'];
                    $ins['question_type'] = $question['type'];
                    $ins['sort_order'] = isset($question['sortOrder']) ? $question['sortOrder'] : 1;
                    $ins['text'] = '';
                    $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                    $ins['scale'] = $question['ratingScale'];
                    $ins['label_question'] = json_encode([]);
                    //
                    if ($question['useLabels'] == 'true') {
                        $ins['label_question'] = json_encode($question['ratingLabels']);
                    }
                    $this->prm->insertTemplateQuestion($ins);
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Template updated.';
                $this->resp();
                break;




                // Get Review templates 

            case 'fetch_reviews':
                // Fetch templates by company id
                $reviews = $this->prm->reviewsByCompanyId(
                    $data['company_sid'],
                    $this->limit,
                    $formpost,
                    $data['employer_sid'],
                    $data['is_super_admin']
                );
                // No template are found
                if (!count($reviews)) {
                    $this->res['Response'] = 'No reviews found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $reviews;
                $this->res['Status'] = TRUE;
                $this->res['Limit'] = $this->limit;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
            case 'fetch_single_template':
                // Fetch templates by company id
                $template =
                    $this->prm->getTemplateById($formpost['Id']);
                // No template are found
                if (!count($template)) {
                    $this->res['Response'] = 'No template found.';
                    $this->resp();
                }
                //
                $this->res['Data'] = $template;
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
            case 'add_review':
                    // Check if review name already exists in system
                $templateExists = $this->prm->doTitleExists(
                    $formpost['Data']['main'],
                    $data['company_sid']
                );
                // Add as template if not exists
                if (!$templateExists) {
                    // Set insert data
                    $ins = [];
                    $ins['title'] = $formpost['Data']['main']['title'];
                    $ins['status'] = $formpost['Data']['main']['status'];
                    $ins['is_custom'] = 1;
                    $ins['is_default'] = 0;
                    $ins['company_sid'] = $data['company_sid'];
                    $ins['creator_sid'] = $data['employer_sid'];
                    $ins['description'] = $formpost['Data']['main']['description'];
                    //
                    $insertId = $this->prm->insertTemplate($ins);
                    //
                    if (!$insertId) {
                        $this->res['Response'] = 'Failed to add template. Please, try again in a few seconds';
                        $this->resp();
                    }
                    //
                    foreach ($formpost['Data']['questions'] as $question) {
                        //
                        $ins = [];
                        $ins['portal_review_questionnaire_type_sid'] = $insertId;
                        $ins['title'] = $question['question'];
                        $ins['description'] = $question['description'];
                        $ins['question_type'] = $question['type'];
                        $ins['sort_order'] = !isset($question['sortOrder']) ? 1 : $question['sortOrder'];
                        $ins['text'] = '';
                        $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                        $ins['scale'] = $question['ratingScale'];
                        $ins['label_question'] = json_encode([]);
                        //
                        if ($question['useLabels'] == 'true') {
                            $ins['label_question'] = json_encode($question['ratingLabels']);
                        }
                        //
                        $insertQuestion = $this->prm->insertTemplateQuestion($ins);
                    }
                }
                // Set the insert array for review
                $ins = [];
                $ins['creator_sid'] = $data['employer_sid'];
                $ins['company_sid'] = $data['company_sid'];
                $ins['title'] = $formpost['Data']['main']['title'];
                $ins['description'] = $formpost['Data']['main']['description'];
                $ins['questionnaire_type'] = '';
                $ins['review_type'] = getReviewType($formpost['Data']['Reviewees']['revieweeType'], 'text');
                $ins['start_date'] = DateTime::createFromFormat('m/d/Y', $formpost['Data']['main']['startDate'])->format('Y-m-d 00:00:00');
                $ins['end_date'] = date('Y-m-d 00:00:00', strtotime($ins['start_date'] . '+' . ($formpost['Data']['main']['dueDays']) . ' days'));
                $ins['status'] = 'pending';
                $ins['is_archive'] = $formpost['Data']['main']['status'];
                // Insert main review
                $reviewId = $this->prm->insertReview($ins);
                //
                if (!$reviewId) {
                    $this->res['Response'] = 'Failed to add review.';
                    $this->resp();
                }
                //
                $managerIds = [];
                // Add Reporting Managers
                foreach($formpost['Data']['ReportingManagers'] as $manager){
                    $managerIds[] = $this->prm->insertReportingManager([
                        'portal_review_sid' => $reviewId,
                        'employee_sid' => $manager
                    ]);
                }
                // Add Questions now
                foreach ($formpost['Data']['questions'] as $question) {
                    //
                    $ins = [];
                    $ins['portal_review_sid'] = $reviewId;
                    $ins['title'] = $question['question'];
                    $ins['description'] = addslashes($question['description']);
                    $ins['question_type'] = $question['type'];
                    $ins['sort_order'] = isset($question['sortOrder']) ? $question['sortOrder'] : 1;
                    $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                    $ins['scale'] = $question['ratingScale'];
                    $ins['label_question'] = json_encode([]);
                    //
                    if ($question['useLabels'] == 'true') {
                        $ins['label_question'] = json_encode($question['ratingLabels']);
                    }
                    //
                    $this->prm->insertReviewQuestion($ins);
                }
                // Add Reviewees
                $reviewee = $formpost['Data']['Reviewees'];
                //
                if ($reviewee['department'] != 'false') {
                    $departments[$reviewee['department']] = true;
                    $this->prm->insertReviewDepartment([
                        'portal_review_sid' => $reviewId,
                        'department_sid' => $reviewee['department'],
                    ]);
                }
                foreach ($reviewee['selectedEmployees'] as $employee) {
                    //
                    if(count($managerIds)){
                        foreach($managerIds as $managerV){
                            // Lets add employee to manager
                            $this->prm->addEmployeeManagerForFeedback([
                                'portal_review_reporting_manager_sid' => $managerV,
                                'employee_sid' => $employee
                            ]);
                        }
                    }
                    $ins = [];
                    $ins['portal_review_sid'] = $reviewId;
                    $ins['employee_sid'] = $employee;
                    //
                    $revieweeId = $this->prm->insertReviewReviewees($ins);
                    //
                    if ($revieweeId) {
                        //
                        foreach ($formpost['Data']['Reviewers'][$employee] as $conductor) {
                            $this->prm->insertReviewConductor([
                                'portal_review_employee_sid' => $revieweeId,
                                'conductor_sid' => $conductor,
                                'is_completed' => 0,
                                'status' => 1,
                            ]);
                        }
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review added successfully.';
                $this->resp();
                break;
            //
            case 'edit_review':
                // Check if review name already exists in system
                $templateExists = $this->prm->doTitleExists(
                    $formpost['Data']['main'],
                    $data['company_sid']
                );
                // Add as template if not exists
                if (!$templateExists) {
                    // Set insert data
                    $ins = [];
                    $ins['title'] = $formpost['Data']['main']['title'];
                    $ins['status'] = $formpost['Data']['main']['status'];
                    $ins['is_custom'] = 1;
                    $ins['is_default'] = 0;
                    $ins['company_sid'] = $data['company_sid'];
                    $ins['creator_sid'] = $data['employer_sid'];
                    $ins['description'] = $formpost['Data']['main']['description'];
                    //
                    $insertId = $this->prm->insertTemplate($ins);
                    //
                    if (!$insertId) {
                        $this->res['Response'] = 'Failed to add template. Please, try again in a few seconds';
                        $this->resp();
                    }
                    //
                    foreach ($formpost['Data']['questions'] as $question) {
                        //
                        $ins = [];
                        $ins['portal_review_questionnaire_type_sid'] = $insertId;
                        $ins['title'] = $question['question'];
                        $ins['description'] = $question['description'];
                        $ins['question_type'] = $question['type'];
                        $ins['sort_order'] = !isset($question['sortOrder']) ? 1 : $question['sortOrder'];
                        $ins['text'] = '';
                        $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                        $ins['scale'] = $question['ratingScale'];
                        $ins['label_question'] = json_encode([]);
                        //
                        if ($question['useLabels'] == 'true') {
                            $ins['label_question'] = json_encode($question['ratingLabels']);
                        }
                        //
                        $insertQuestion = $this->prm->insertTemplateQuestion($ins);
                    }
                }
                // Set the insert array for review
                $ins = [];
                $ins['title'] = $formpost['Data']['main']['title'];
                $ins['description'] = $formpost['Data']['main']['description'];
                $ins['questionnaire_type'] = '';
                $ins['review_type'] = getReviewType($formpost['Data']['Reviewees']['revieweeType'], 'text');
                $ins['start_date'] = DateTime::createFromFormat('m/d/Y', $formpost['Data']['main']['startDate'])->format('Y-m-d 00:00:00');
                $ins['end_date'] = date('Y-m-d 00:00:00', strtotime($ins['start_date'] . '+' . ($formpost['Data']['main']['dueDays']) . ' days'));
                $ins['status'] = 'pending';
                $ins['is_archive'] = $formpost['Data']['main']['status'];
                // Insert main review
                $reviewId = $formpost['Data']['main']['sid'];
                //
                $this->prm->updateReview($ins, $reviewId);
                //
                $managerIds = [];
                // Add Reporting Managers
                foreach($formpost['Data']['ReportingManagers'] as $manager){
                    $managerIds[] = $this->prm->checkAndInsertReportingManager([
                        'portal_review_sid' => $reviewId,
                        'employee_sid' => $manager
                    ]);
                }
                // Add Questions now
                foreach ($formpost['Data']['questions'] as $question) {
                    //
                    $ins = [];
                    $ins['portal_review_sid'] = $reviewId;
                    $ins['title'] = $question['question'];
                    $ins['description'] = addslashes($question['description']);
                    $ins['question_type'] = $question['type'];
                    $ins['sort_order'] = isset($question['sortOrder']) ? $question['sortOrder'] : 1;
                    $ins['not_applicable'] = $question['includeNA'] == 'false' ? 0 : 1;
                    $ins['scale'] = $question['ratingScale'];
                    $ins['label_question'] = json_encode([]);
                    //
                    if ($question['useLabels'] == 'true') {
                        $ins['label_question'] = json_encode($question['ratingLabels']);
                    }
                    //
                    if(isset($question['sid'])){
                        unset($ins['portal_review_sid']);
                        //
                        $this->prm->updateReviewQuestion($ins, $question['sid']);
                    } else{
                        //
                        $this->prm->insertReviewQuestion($ins);
                    }
                }
                // Add Reviewees
                $reviewee = $formpost['Data']['Reviewees'];
                //
                if ($reviewee['department'] != 'false') {
                    $departments[$reviewee['department']] = true;
                    $this->prm->checkAndInsertReviewDepartment([
                        'portal_review_sid' => $reviewId,
                        'department_sid' => $reviewee['department'],
                    ]);
                }
                foreach ($reviewee['selectedEmployees'] as $employee) {
                     //
                    if(count($managerIds)){
                        foreach($managerIds as $managerV){
                            // Lets add employee to manager
                            $this->prm->checkAndAddEmployeeManagerForFeedback([
                                'portal_review_reporting_manager_sid' => $managerV,
                                'employee_sid' => $employee
                            ]);
                        }
                    }
                    $ins = [];
                    $ins['portal_review_sid'] = $reviewId;
                    $ins['employee_sid'] = $employee;
                    //
                    $revieweeId = $this->prm->checkAndInsertReviewReviewees($ins);
                    //
                    if ($revieweeId) {
                        foreach ($formpost['Data']['Reviewers'][$employee] as $conductor) {
                            $this->prm->checkAndInsertReviewConductor([
                                'portal_review_employee_sid' => $revieweeId,
                                'conductor_sid' => $conductor,
                                'is_completed' => 0,
                                'status' => 1,
                            ]);
                        }
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review updated successfully.';
                $this->resp();
            break;

            // Starts the review
        
                // Starts the review
            case "start_review":
                $this->prm->startReview($formpost['Id']);
                //
                $this->emailHandler($formpost['Id'], 'started', 'main', $data);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review started successfully.';
                $this->resp();
            break;

            // Ends the review
            case "end_review":
                $this->prm->endReview($formpost['Id']);
                //
                $this->emailHandler($formpost['Id'], 'ended', 'main', $data);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review ended successfully.';
                $this->resp();
            break;
                // Ends the review
            case "end_employee_review":
                $this->prm->endEmployeeReview($formpost['Id']);
                //
                // $this->emailHandler($formpost['Id'], 'ended', 'employee');
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review ended successfully.';
                $this->resp();
                break;
                // Ends the review
            case "start_employee_review":
                $this->prm->startEmployeeReview($formpost['Id']);
                //
                //
                // $this->emailHandler($formpost['Id'], 'started', 'employee');
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Review started successfully.';
                $this->resp();
                break;
                // Assigned reviews
            case "fetch_assigned_reviews":
                $reviews = $this->prm->getAssignedReviews($data['employer_sid'], $this->limit, $formpost);
                //
                $this->res['Data'] = $reviews;
                $this->res['Status'] = TRUE;
                $this->res['Limit'] = $this->limit;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
                break;
            case "save_answers":
                //
                foreach($formpost['Data'] as $k => $v){
                    $ins = [];
                    $ins['portal_review_question_sid'] = $k;
                    $ins['conductor_tbl_sid'] = $data['employer_sid'];
                    $ins['employee_tbl_sid'] = $formpost['EId'];
                    $ins['review_sid'] = $formpost['Id'];
                    $ins['edit_flag'] = $v['edit'];
                    if(isset($v['text'])) $ins['text_answer'] = $v['text'];
                    if (isset($v['rating'])) $ins['rating_answer'] = $v['rating'];
                    //
                    if(isset($v['sid']) && $v['sid'] != 0){
                        $this->prm->updateAnswer($ins, $v['sid']);
                    } else{
                        $this->prm->insertAnswer($ins);
                    }
                }
                // Mark as completed
                $this->prm->markAsCompleted([
                    'review_sid' => $formpost['Id'],
                    'employee_sid' => $formpost['EId'],
                    'condictor_sid' => $data['employer_sid']
                ]);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Answers saved successfully.';
                $this->resp();
            break;

            // Get reviewees for managers for feedback
            case 'fetch_manager_reviews':

                //
                $reviews = $this->prm->getReviewsForManagersForFeedback(
                    $data['company_sid'], 
                    $data['employer_sid'],
                    $formpost
                );
                //
                if(!$reviews || !count($reviews)){
                    $this->res['Response'] = 'No reviews found for feedback.';
                    $this->resp();    
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Data'] = $reviews;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
            break;
            
            // Save manager review
            case 'save_feedback':
                //
                $this->prm->saveFeedback(
                    $formpost
                );
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Your feedback has successfully saved.';
                $this->resp();
            break;

            case "get_answers":
                $id = $formpost['data']['reviewSid'];
                $cId = $formpost['data']['conductorSid'];
                $eId = $formpost['data']['employeeSid'];
                //
                $review = $this->prm->getReviewById($id);
                //
                $answers = $this->prm->getAnswersId($id,$cId,$eId);
                //
                if(count($answers)){
                    foreach($review['questions'] as $k => $question){
                        $review['questions'][$k]['answer'] = 
                        $answers[array_search($question['sid'], array_column($answers, 'portal_review_question_sid'))];
                    }
                }

                //
                $rows = '';
                $d = [];
                //
                foreach($review['questions'] as $k => $question) {
                    $rows .= '<div class="form-group">';
                    $rows .= '<h4>Q'.($k + 1).': '.($question['question']).'</h4>';
                    $rows .= '<p>'.($question['description']).'</p>';
                    $rows .= getQuestion($question, $d);
                    $rows .= '</div>';
                }

                //
                $this->res['Status'] = TRUE;
                $this->res['Data'] = $rows;
                $this->res['Response'] = 'Proceed.';
                $this->resp();
            break;

            // Delete Reviewee
            case "delete_reviewee":
                //
                $this->prm->deleteReviewee($formpost['reviewSid'], $formpost['employeeSid']);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Reviewee successfully deleted.';
                $this->resp();
            break;
        }
        //
        $this->resp();
    }

    /**
     * AJAX Responder
     */
    private function resp()
    {
        header('Content-type: application/json');
        echo json_encode($this->res);
        exit(0);
    }

    /**
     * Check  user sessiona nd set data
     * Created on: 23-08-2019
     *
     * @param $data     Reference
     * @param $return   Bool
     * Default is 'FALSE'
     *
     * @return VOID
     */
    private function check_login(&$data, $return = FALSE)
    {
        //
        if ($this->input->post('fromPublic', true) && $this->input->post('fromPublic', true) == 1 && !$this->session->userdata('logged_in')) {
            $this->load->config('config');
            $result = $this->timeoff_model->login($this->input->post('employerSid'));

            if ($result) {
                if ($result['employer']['timezone'] == '' || $result['employer']['timezone'] == NULL || !preg_match('/^[A-Z]/', $result['employer']['timezone'])) {
                    if ($result['company']['timezone'] != '' && preg_match('/^[A-Z]/', $result['company']['timezone'])) $result['employer']['timezone'] = $result['company']['timezone'];
                    else $result['employer']['timezone'] = STORE_DEFAULT_TIMEZONE_ABBR;
                }
                $data['session'] = array(
                    'company_detail' => $result["company"],
                    'employer_detail' => $result["employer"]
                );
            }
        }
        //
        if (!isset($data['session'])) {
            if (!$this->session->userdata('logged_in')) {
                if ($return) return false;
                redirect('login', 'refresh');
            }
            $data['session'] = $this->session->userdata('logged_in');
        }
        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['companyData'] = $data['session']['company_detail'];
        $data['employerData'] = $data['session']['employer_detail'];
        $data['company_name'] = $data['session']['company_detail']['CompanyName'];
        $data['timeoff_format_sid'] = $data['session']['company_detail']['pto_format_sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['is_super_admin'] = $data['session']['employer_detail']['access_level_plus'];
        $data['employee_full_name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
        if (!$return)
            $data['security_details'] = db_get_access_level_details($data['employer_sid'], NULL, $data['session']);
        if ($return) return true;
    }


    // CRON
    function cron_review(){
        // Set for current date time
        $date = date('Y-m-d 23:59:59', strtotime('now'));
        //
        $reviews = $this->prm->getReviewForCron($date);
        //
        if(!count($reviews)) exit(0);
        //
        foreach($reviews as $review){
            //
            if($date > $review['end_date'] ){
                // Review time is over, end all reviews
                $this->prm->endReviews($review['sid']);
            } else {
                $this->prm->startReviews($review['sid']);
            }
        }
        echo 'Done';
        //
        exit(0);
    }


    //
     function emailHandler(
        $id, 
        $action,
        $type = 'main',
        $d = array()
    ){
        // $this->check_login($d);
        //
        $data = [];
        // Get review data
        if($type == 'main'){
            $dd = $this->prm->getReviewById($id, [
                'detail' => true, 
                'reviewees' => true
            ]);
            //
            foreach($dd['reviewees'] as $r){
                $data[] = [
                    'title'=> $dd['main']['title'],
                    'first_name' => $r['first_name'],
                    'last_name' => $r['last_name'],
                    'email_address' => $r['email']
                ];
            }
        }
        //
        $em = $this->getEmailTemplate($d['company_sid'], $d['session']['company_detail']['CompanyName'], $action);
        //
        $link = base_url('performance/assigned/view');
        $link = '<a href="'.($link).'">Click Here</a>';
        // Send email
        foreach($data as $v){
            log_and_sendEmail(
                $em['FromEmail'],
                $v['email_address'],
                $em['Subject'],
                str_replace(
                    ['{{first_name}}', '{{last_name}}', '{{review_title}}', '{{review_link}}'],
                    [
                        $v['first_name'],
                        $v['last_name'],
                        $v['title'],
                        $link
                    ],
                    $em['Body']
                ),
                'AutomotoHR'
            );
        }
    }


    //
    private function getEmailTemplate($cId, $cName, $action){
        //
        $subject = "Performance Review assigned.";
        $template = '
        <p>Hi <strong>{{first_name}} {{last_name}}</strong>,</p>
        <p>You have assigned a new performance review with a title&nbsp;<strong>{{review_title}}</strong>.</p>
        
        <p>You can start the review by clicking the following link.</p>

        <p>{{review_link}}</p>
        ';
        //
        if($action == 'edit'){
            $subject = "Performance Review updated.";
            $template = '
                <p>Hi <strong>{{first_name}} {{last_name}}</strong>,</p>
                <p>A performance review with a title&nbsp;<strong>{{review_title}}</strong> has been updated.</p>

                <p>Please review the change by clicking the following link.</p>

                <p>{{review_link}}</p>
            ';
        }

        $h = message_header_footer($cId, $cName);
        
        return [
            'FromEmail' => 'notifications@automotohr.com',
            'Subject' => $subject,
            'Body' => $h['header'] . $template. $h['footer'],
        ];
    }

    public function employee_performance_review ($sid) {
        if ($this->session->userdata('logged_in')) {
            //
            $data = employee_right_nav($sid);
            //
            $security_sid = $data['session']['employer_detail']['sid'];
            //
            $company_sid = $data['session']['company_detail']['sid'];
            //
            $access_level_plus = $data["session"]["employer_detail"]["access_level_plus"];
            //
            $security_details = db_get_access_level_details($security_sid);
            //
            check_access_permissions($security_details, 'employee_management', 'employee_profile');
            //
            $data['title'] = "Employee / Performance Review";
            //
            $data['top_bar_link_title'] = 'Employee Profile';
            //
            $data['access_level_plus'] = $access_level_plus;
            //
            $data['security_details'] = $security_details;
            //
            $data['employer'] = $this->prm->get_employee_detail($sid);
            //
            $data['top_bar_link_url'] = base_url('employee_profile/'.$sid);
            //
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
            //
            $employee_all_reviews = $this->prm->get_employee_all_reviews($sid);
            //
            $active_employees = $this->prm->get_active_employees_detail($company_sid, $security_sid);
            //
            $data['active_employees'] = $active_employees;
            //
            $reviewIds = array_column($employee_all_reviews, 'portal_review_sid');
            //
            $data['report'] = $this->prm->getReport($reviewIds, $sid);
            //
            if (!empty($employee_all_reviews)) {
                foreach ($employee_all_reviews as $key => $employee_all_review) {
                    
                    $review_sid = $employee_all_review['portal_review_sid'];
                    //
                    $review_employee_sid = $employee_all_review['sid'];
                    //
                    $review_conductors_count = $this->prm->get_all_reviewers_count($review_employee_sid);
                    //
                    $employee_all_reviews[$key]['reviewer_count'] = $review_conductors_count;
                    //
                    $review_info = $this->prm->get_review_info($review_sid);
                    //
                    if (!empty($review_info)) {
                        //
                        $employee_all_reviews[$key]['title'] = $review_info['title'];
                        //
                        $employee_all_reviews[$key]['status'] = $review_info['status'];
                        //
                        $employee_all_reviews[$key]['start_date'] = $review_info['start_date'];
                    }
                }
            }
            //
            $data['employee_reviews'] = $employee_all_reviews;
            //
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/performance_review/employee_performance_review_listing');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
            
        }
    }

    public function review_detail ($review_sid, $employee_review_sid, $employee_sid) {
        if ($this->session->userdata('logged_in')) {
            //
            $data = employee_right_nav($employee_sid);
            //
            $security_sid = $data['session']['employer_detail']['sid'];
            //
            $company_sid = $data['session']['company_detail']['sid'];
            //
            $access_level_plus = $data["session"]["employer_detail"]["access_level_plus"];
            //
            $security_details = db_get_access_level_details($security_sid);
            //
            check_access_permissions($security_details, 'employee_management', 'employee_profile');
            //
            $data['title'] = "Employee / Performance Review Detail";
            //
            $data['top_bar_link_title'] = 'Review Listing';
            //
            $data['access_level_plus'] = $access_level_plus;
            //
            $data['security_details'] = $security_details;
            //
            $data['employer'] = $this->prm->get_employee_detail($employee_sid);
            //
            $data['top_bar_link_url'] = base_url('performance_review/'.$employee_sid);
            //
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
            //
            $review_info = $this->prm->get_review_info($review_sid);
            //
            $review_title = $review_info['title'];
            //
            $data['review_title'] = $review_title;
            //
            $data['review_sid'] = $review_sid;
            //
            $data['employee_review_sid'] = $employee_review_sid;
            //
            $active_employees = $this->prm->get_active_employees_detail($company_sid, $security_sid);
            //
            $data['active_employees'] = $active_employees;
            //
            $employee_review_detail = $this->prm->get_all_performance_reviewers($employee_review_sid);
            //
            $data['report'] = $this->prm->getReport($review_sid, $employee_sid);
            //
            $conductorSids = [];
            //
            if (!empty($employee_review_detail)) {
                foreach ($employee_review_detail as $key => $employee_review) {
                    $conductorSids[] = $conductor_sid = $employee_review['conductor_sid'];
                    $conductor_info = $this->prm->get_employee_detail($conductor_sid);
                    $conductor_name = $conductor_info['first_name'].' '.$conductor_info['last_name'];
                    $review_status = 'Pending';
                    //
                    if ($employee_review['is_completed'] == 1) {
                        $review_status = 'Completed';
                    }
                    //
                    $employee_review_detail[$key]['reviewer_name'] = $conductor_name;
                    $employee_review_detail[$key]['review_status'] = $review_status;
                }
                //
                $data['reviewed']['total'] = count($conductorSids);
                $data['reviewed']['completed'] = $this->prm->getAnswerCount($review_sid, $employee_sid, $conductorSids);
                //
                $data['feedback'] = $this->prm->getFeedbackCount($review_sid, $employee_sid);
            }
            //
            $data['employee_review_detail'] = $employee_review_detail;
            //
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/performance_review/employee_performance_review_detail');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
            
        }
    }

    public function review_question ($review_sid, $employee_review_sid, $employee_sid, $conductor_sid) {
        if ($this->session->userdata('logged_in')) {
            $data = employee_right_nav($employee_sid);
            //
            $security_sid = $data['session']['employer_detail']['sid'];
            //
            $access_level_plus = $data["session"]["employer_detail"]["access_level_plus"];
            //
            $security_details = db_get_access_level_details($security_sid);
            //
            check_access_permissions($security_details, 'employee_management', 'employee_profile');
            //
            $data['title'] = "Employee / Question Detail";
            //
            $data['top_bar_link_title'] = 'Review Detail';
            //
            $data['access_level_plus'] = $access_level_plus;
            //
            $data['security_details'] = $security_details;
            //
            $data['employer'] = $this->prm->get_employee_detail($employee_sid);
            //
            $data['top_bar_link_url'] = base_url('performance_review/review_detail/'.$review_sid.'/'.$employee_review_sid.'/'.$employee_sid);
            //
            $data['left_navigation'] = 'manage_employer/employee_management/profile_right_menu_employee_new';
            //
            $review_info = $this->prm->get_review_info($review_sid);
            //
            $conductor_info = $this->prm->get_employee_detail($conductor_sid); 
            //
            $data['review'] = $this->prm->getReviewById($review_sid);
            //
            $conductor_name = remakeEmployeeName($conductor_info);
            //
            $review_title = $review_info['title'];
            //
            $data['review_title'] = $review_title;
            //
            $data['review_sid'] = $review_sid;
            //
            $data['conductor_name'] = $conductor_name;
            //
            $data['employee_name'] = remakeEmployeeName($data['employer']);
            //
            $data['employee_sid'] = $employee_sid;
            //
            $data['conductor_sid'] = $conductor_sid;
            //
            $data['answers'] = $this->prm->getAnswersId($review_sid, $conductor_sid, $employee_sid);
            // 
            if(count($data['answers'])){
                //
                foreach($data['review']['questions'] as $key => $question){
                    $data['review']['questions'][$key]['answer'] = 
                    $data['answers'][array_search($question['sid'], array_column($data['answers'], 'portal_review_question_sid'))];
                }
            }
            //
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/performance_review/employee_performance_review_question_detail');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
            
        }
    }

    public function ajax_handler () {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $employee_sid       = $session["employer_detail"]["sid"];
            //
            $title              = $_POST['title'];
            //
            $conductor_sid      = $_POST['conductor_sid'];
            //
            $employee_sid       = $_POST['employee_sid'];
            //
            $filter_reviews = $this->prm->get_all_filter_reviews($title, $conductor_sid, $employee_sid);
            //
            if (!empty($filter_reviews)) {
                //
                foreach ($filter_reviews as $key => $filter_review) {
                    $employee_sid = $filter_review['employee_sid'];
                    //
                    $employee_info = $this->prm->get_employee_detail($employee_sid);
                    //
                    $filter_reviews[$key]['employee_name'] = remakeEmployeeName([
                                                                    'first_name' => $employee_info['first_name'],
                                                                    'last_name' => $employee_info['last_name'],
                                                                    'pay_plan_flag' => $employee_info['pay_plan_flag'],
                                                                    'access_level' => $employee_info['access_level'],
                                                                    'access_level_plus' => $employee_info['access_level_plus'],
                                                                    'job_title' => $employee_info['job_title'],
                                                                ], true);
                    //
                    $review_sid = $filter_review['sid'];
                    //
                    $review_conductors_count = $this->prm->get_all_reviewers_count($review_sid);
                    //
                    $filter_reviews[$key]['reviewer_count'] = $review_conductors_count;
                    //
                    $review_conductors_names = $this->prm->get_all_reviewers_names($review_sid);
                    //
                    $filter_reviews[$key]['review_conductors_names'] = $review_conductors_names;
                }
                //
            }
            //
            if (!empty($filter_reviews)) {
                echo json_encode($filter_reviews);
            } else {
                echo 'not_found';
            }
            
            //
        } else {
            echo 'error';
        }
    }

    public function edit_review_info ($review_sid = null, $page = 'view') {
        if ($this->session->userdata('logged_in')) {
            $data = array();
            //
            $this->check_login($data);
            // Reset page
            $page = preg_replace('/[^a-z]/', '', strtolower($page));
            //
            $data['page'] = $page;
            //
            $company_sid = $data['session']['company_detail']['sid'];
            //
            $employee_sid = $data['session']['employer_detail']['sid'];
            //
            $data['title'] = 'Performance Review - ' . (ucfirst($page));
            //
            $active_employees = $this->prm->get_active_employees_detail($company_sid, $employee_sid);
            //
            if ($review_sid != null) {
                //
                $review_title = $this->prm->get_review_title_by_sid($review_sid);
                //
                $data['review_title'] = $review_title['title'];
            }
            //
            $data['active_employees'] = $active_employees;
            //
            $data['company_sid'] = $company_sid;
            //
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/performance_review/performance_review_edit');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
            
        }
    }

    public function get_conductors_detail () {
        if ($this->session->userdata('logged_in')) {
            //
            $review_employees_id    = $_POST['review_employees_id'];
            //
            $reviewer_detail = $this->prm->get_all_performance_reviewers($review_employees_id);
            //
            if (!empty($reviewer_detail)) {
                //
                foreach ($reviewer_detail as $key => $detail) {
                    $conductor_sid = $detail['conductor_sid'];
                    //
                    $conductor_info = $this->prm->get_employee_detail($conductor_sid);
                    //
                    $reviewer_detail[$key]['conductor_name'] = remakeEmployeeName([
                                                                    'first_name' => $conductor_info['first_name'],
                                                                    'last_name' => $conductor_info['last_name'],
                                                                    'pay_plan_flag' => $conductor_info['pay_plan_flag'],
                                                                    'access_level' => $conductor_info['access_level'],
                                                                    'access_level_plus' => $conductor_info['access_level_plus'],
                                                                    'job_title' => $conductor_info['job_title'],
                                                                ], true);
                    //
                }
                //
            }
            //
            if (!empty($reviewer_detail)) {
                echo json_encode($reviewer_detail);
            } else {
                echo 'not_found';
            }
            //
        } else {
            echo 'error';
        }
    }

    public function update_conductors_detail () {
        if ($this->session->userdata('logged_in')) {
            //
            $review_employees_id    = $_POST['review_employees_id'];
            //
            if (isset($_POST['insert']) && !empty($_POST['insert'])) {
                //
                $insert_conductors    = explode(',', $_POST['insert']);
                //
                foreach ($insert_conductors as $key => $insert_id) {
                    $data_to_insert = array();
                    $data_to_insert['portal_review_employee_sid'] = $review_employees_id;
                    $data_to_insert['conductor_sid'] = $insert_id;
                    $data_to_insert['is_deleted'] = 0;
                    $data_to_insert['is_completed'] = 0;
                    $data_to_insert['status'] = 1;
                    $this->prm->insert_conductor($data_to_insert);
                }
            }

            if (isset($_POST['delete']) && !empty($_POST['delete'])) {
                //
                $delete_conductors    = explode(',', $_POST['delete']);
                //
                foreach ($delete_conductors as $key => $delete_id) {
                    $this->prm->delete_conductor($review_employees_id, $delete_id);
                }
            }
            //
            echo 'success';
            //
        } else {
            echo 'error';
        }
    }   

    public function get_all_review_title () {
        if ($this->session->userdata('logged_in')) {
            //
            $company_sid = $_POST['company_sid'];
            //
            $reviewe_titles = $this->prm->get_all_reviews_titles($company_sid);
            //
            if (!empty($reviewe_titles)) {
                echo json_encode($reviewe_titles);
            } else {
                echo 'not_found';
            }
            //
        } else {
            echo 'error';
        }
    }

    public function get_all_reviewee () {
        if ($this->session->userdata('logged_in')) {
            //
            $review_sid = $_POST['review_sid'];
            //
            $reviewess = $this->prm->get_all_reviewees($review_sid);
            //
            $reviewee_list = array();
            if(!empty($reviewess)) {
                foreach ($reviewess as $key => $reviewee) {
                    array_push($reviewee_list, $reviewee['employee_sid']);
                }
            }
            //
            $html = '';
            //
            if (!empty($reviewee_list)) {
                $data = array();
                //
                $this->check_login($data);
                //
                $company_sid = $data['session']['company_detail']['sid'];
                //
                $employee_sid = $data['session']['employer_detail']['sid'];
                //
                $active_employees = $this->prm->get_active_employees_detail($company_sid, $employee_sid);
                //
                $html .= '<select id="js-add-reviewee" multiple="multiple">'; 
                //
                foreach ($active_employees as $key => $active_employee) {
                    $empoloyee_name = remakeEmployeeName([
                            'first_name' => $active_employee['first_name'],
                            'last_name' => $active_employee['last_name'],
                            'pay_plan_flag' => $active_employee['pay_plan_flag'],
                            'access_level' => $active_employee['access_level'],
                            'access_level_plus' => $active_employee['access_level_plus'],
                            'job_title' => $active_employee['job_title'],
                        ], true);

                    if (in_array($active_employee['sid'], $reviewee_list)) {
                        $html .= '<option disabled value="'.$active_employee['sid'].'">'.$empoloyee_name.'</option>';
                    } else {
                        $html .= '<option value="'.$active_employee['sid'].'">'.$empoloyee_name.'</option>'; 
                    }

                }
                //
                $html .= '</select>'; 
            }
            //
            if (!empty($html)) {
                echo $html;
            } else {
                echo 'not_found';
            }
            //
        } else {
            echo 'error';
        }
    }

    public function add_new_review_detail () {
        if ($this->session->userdata('logged_in')) {
            //
            $review_sid    = $_POST['review_sid'];
            //
            $reviewee_sid    = $_POST['reviewee_sid'];
            //
            $review_employees_id = $this->prm->insert_reviewee($review_sid, $reviewee_sid);
            //
            $conductors_sids    = explode(',', $_POST['conductors_sids']);
            //
            $managers = $this->prm->getReportingManagersByReview($review_sid);
            //
            foreach($managers as $manager){
                // Lets add employee to manager
                $this->prm->checkAndAddEmployeeManagerForFeedback([
                    'portal_review_reporting_manager_sid' => $manager,
                    'employee_sid' => $reviewee_sid
                ]);
            }
            //
            foreach ($conductors_sids as $key => $insert_id) {
                $data_to_insert = array();
                $data_to_insert['portal_review_employee_sid'] = $review_employees_id;
                $data_to_insert['conductor_sid'] = $insert_id;
                $data_to_insert['is_deleted'] = 0;
                $data_to_insert['is_completed'] = 0;
                $data_to_insert['status'] = 1;
                $this->prm->insert_conductor($data_to_insert);
            }
            //
            echo 'success';
            //
        } else {
            echo 'error';
        }
    }

    public function reviews_statics ($page = 'view') {
        if ($this->session->userdata('logged_in')) {
            $data = array();
            $this->check_login($data);
            //
            // Reset page
            $page = preg_replace('/[^a-z]/', '', strtolower($page));
            //
            $data['page'] = $page;
            //
            $company_sid = $data['session']['company_detail']['sid'];
            //
            $employee_sid = $data['session']['employer_detail']['sid'];
            //
            $data['title'] = 'Performance Review - Statics';
            //
            $active_employees = $this->prm->get_active_employees_detail($company_sid, $employee_sid);
            //
            $data['active_employees'] = $active_employees;
            //
            $data['company_sid'] = $company_sid;
            //
            $reviewe_titles = $this->prm->get_all_reviews_titles($company_sid);
            //
            $newChart = array();
            //
            $newChart[0] = array("Review Type", "Count");
            //
            if (!empty($reviewe_titles)) {
                foreach ($reviewe_titles as $key => $reviewe_title) {
                    $reviewee_count = $this->prm->get_all_related_reviewees($reviewe_title['sid']);
                    $newChart[$key+1] = array($reviewe_title['title'], intval($reviewee_count));
                }
            }
            //
            $data['chart'] = json_encode($newChart);
            //
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/performance_review/performance_review_statics');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
            
        }
    }

    public function change_employee_review_status () {
        if ($this->session->userdata('logged_in')) {
            //
            $update_row    = $_POST['update_row'];
            //
            $reviewee_status    = $_POST['reviewee_status'];
            //
            $data_to_update = array();
            //
            $data_to_update['is_started'] = $reviewee_status;
            //
            $review_employees_id = $this->prm->update_employee_review_status($update_row, $data_to_update);
            //
            echo 'success';
            //
        } else {
            echo 'error';
        }
    }
}
