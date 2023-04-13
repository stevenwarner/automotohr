<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Courses extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/2022/Courses_model', 'couses_model');
        //
        $this->res['Status'] = FALSE;
        $this->res['Redirect'] = TRUE;
        $this->res['Response'] = 'Invalid request';
        $this->res['Code'] = 'INVALIDREQUEST';
        //
        $this->scorm_versions = [
            "20043rd",
            "20044th",
            "12"
        ];
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['type'] = "Scorm"; 
        $courses[0]['version'] = "1.2"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies";
        $courses[1]['type'] = "Scorm"; 
        $courses[1]['version'] = "2004 4th Edition"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['type'] = "Manual";  
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function add() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'add';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //
        $this->load->library('form_validation');
        $this->form_validation->set_rules('title', 'Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('job_title', 'Job Title', 'required|trim|xss_clean');
        $this->form_validation->set_rules('type', 'Course Type', 'trim|xss_clean');

        if ($this->form_validation->run() === FALSE) {
            $job_titles = $this->couses_model->GetAllActiveTemplates();
            $this->data['PageCSS'] = [
                'mFileUploader/index'
            ];
            //
            $this->data['PageScripts'] = [
                '2022/js/courses/add_course',
                'mFileUploader/index'
            ];
            $this->data['page_title'] = 'Add Course';
            $this->data['job_titles'] = $job_titles;
            $this->render('manage_admin/courses/add');
        } else {

        }
    }

    public function edit($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Default Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function preview($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'courses_index';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Default Courses';
        $companies = $this->couses_model->get_all_companies();
        $this->data['standard_companies'] = $companies;
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $courses[2]['sid'] = 3; 
        $courses[2]['title'] = "Basic Work Enviourment"; 
        $courses[2]['status'] = 0;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/index');
    }

    public function manage($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'manage';  // change the function name to apply security to this module
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        
        $this->data['page_title'] = 'Manage Courses';
        //
        $courses = array();
        $courses[0]['sid'] = 1; 
        $courses[0]['title'] = "Work Place Eathic"; 
        $courses[0]['status'] = 1; 
        $courses[1]['sid'] = 2; 
        $courses[1]['title'] = "Time off Policies"; 
        $courses[1]['status'] = 1;
        $this->data['courses'] = $courses;
        //
        $this->render('manage_admin/courses/manage');
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
        ///
        $post = $this->input->post(NULL, TRUE);
        // Check post size and action
        if (!sizeof($post) || !isset($post['action'])) $this->resp();
        $post['public'] = 0;
        // For expired session
        if ($post['public'] == 0 && empty($this->ion_auth->user())) {
            $this->res['Redirect'] = true;
            $this->res['Response'] = 'Your login session has expired.';
            $this->res['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }
        //
        $this->res['Redirect'] = FALSE;
        //
        switch (strtolower($post['action'])) {
            // 
            case 'get_all_courses':
                //
                $type = "running";
                //
                if (isset($post['type']) && !empty($post['type'])) {
                    $type = $post['type'];
                }
                //
                $courses = $this->cm->getAllCourses($post['companyId'], $type);
                $draftCount = $this->cm->getCoursesCount($post['companyId'], 'draft');
                $completedCount = $this->cm->getCoursesCount($post['companyId'], 'completed');
                $assignedCount = $this->cm->getCoursesCount($post['companyId'], 'assigned');
                $runningCount = $this->cm->getCoursesCount($post['companyId'], 'running');
                //
                $this->res['Courses'] = $courses;
                $this->res['draftCount'] = $draftCount;
                $this->res['completedCount'] = $completedCount;
                $this->res['assignedCount'] = $assignedCount;
                $this->res['runningCount'] = $runningCount;
                $this->res['Response'] = 'Proceed.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                //
                break;

            case 'add_course':
                //
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $post['companyId'];
                $data_to_insert['creator_sid'] = $post['employeeId'];
                $data_to_insert['title'] = $post['title'];
                $data_to_insert['type'] = $post['course_type'];
                $data_to_insert['description'] = $post['description'];
                $data_to_insert['start_date'] = $post['start_date'];
                $data_to_insert['end_date'] = $post['end_date'];
                //
                $insert_id = $this->cm->addData('lms_courses', $data_to_insert);
                //
                $this->res['Id'] = $insert_id;
                $this->res['Type'] = $post['course_type'];
                $this->res['Response'] = 'You have successfully added a course with the title <b>"' . (stripcslashes($post['title'])) . '"</b>.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;

            case 'update_course':
                //
                $data_to_update = array();
                $data_to_update['start_date'] = $post['startDate'];
                $data_to_update['end_date'] = $post['endDate'];
                //
                $this->cm->updateCourse($data_to_update, $_POST['courseId']);
                //
                $this->res['Response'] = 'Update course period successfully';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;    

            case 'upload_zip':
                //
                $random = generateRandomString(5);
                $timestemp = date('Y_m_d');
                $target_file_name = basename($_FILES["upload_zip"]["name"]);
                $file_name = strtolower($random . '_' . $timestemp . '_' . $target_file_name);
                //
                $target_dir = 'assets/temp_files/scorm/' ;
                $target_file = $target_dir . $file_name;
                $file_info = pathinfo($_FILES["upload_zip"]["name"]);
                // 
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                //
                if (move_uploaded_file($_FILES["upload_zip"]["tmp_name"], $target_file)) {
                    $zip = new ZipArchive;
                    //
                    $x = $zip->open($target_file);
                    //
                    if ($x === true) {
                        $zip->extractTo($target_dir);
                        $zip->close();
                    }
                    //
                    $newFileName = $random . '_' .$timestemp. '_' .$file_info['filename'];
                    $oldName = $target_dir.str_replace('.zip', '', $target_file_name);
                    $newName = $target_dir.$newFileName;
                    unlink($target_file);
                    rename($oldName, $newName);
                    //
                    $unzipFile = $target_dir .$file_info['filename'];
                    $this->load->library('Scorm/Scorm_lib', '', 'slib');
                    //
                    $courseContent = $this->slib->LoadFile($newName."/imsmanifest.xml")->GetIndex();
                    //
                    if (
                        !empty($courseContent) && 
                        isset($courseContent["items"]) &&
                        !empty($courseContent["items"])
                    ) {
                        if (
                            in_array($courseContent['version'], $this->scorm_versions)
                        ) {
                            //
                            $this->res['Response'] = $newFileName;
                            $this->res['Code'] = "SUCCESS";
                            $this->res['Status'] = true;
                            $this->resp();
                        } else {
                            $this->res['Response'] = '<strong>Sorry</strong>, system not support this '. $courseContent['version'] .' version.';
                            $this->resp();
                        }
                    } else {
                        $this->res['Response'] = '<strong>Sorry</strong>, scorm file is invalide';
                        $this->resp();
                    }
                    
                } else {
                    $this->res['Response'] = '<strong>Sorry</strong>, there was an error uploading your file.';
                    $this->resp();
                }
                //
                
                break;  

            case 'upload_video';
                $random = generateRandomString(5);
                $company_id = $post['companyId'];
                $companyName = getCompanyNameBySid($company_id);
                $target_file_name = basename($_FILES["video"]["name"]);
                $file_name = strtolower($random . '_' . $target_file_name);
                //
                $target_dir = 'assets/temp_files/courses_videos/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . $_POST['courseId'] .'/';
                $target_file = $target_dir . $file_name;
                $file_info = pathinfo($_FILES["video"]["name"]);
                // 
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                //
                if (move_uploaded_file($_FILES["video"]["tmp_name"], $target_file)) {
                    //
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $post['companyId'];
                    $data_to_insert['creator_sid'] = $post['employeeId'];
                    $data_to_insert['course_sid'] = $_POST['courseId'];
                    $data_to_insert['chapter_video'] = $target_file;
                    $data_to_insert['chapter_title'] = $_POST['title'];
                    $data_to_insert['chapter_description'] = $_POST['description'];
                    //
                    $insert_id = $this->cm->addData('lms_manual_course', $data_to_insert);
                    //
                    $this->res['Response'] = '<strong>The video ' . basename($_FILES["video"]["name"]) . ' has been uploaded.';

                    $this->res['chapterId'] = $insert_id;
                    $this->res['Code'] = "SUCCESS";
                    $this->res['Status'] = true;
                    $this->resp();
                }
                //
                break; 

            case 'get_manual_course_detail':
                //
                $this->res['CourseInfo'] = $this->cm->getManualCourseInfo($post['courseId']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                //
                $this->resp();
                break;       

            case 'save_question':
                //
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $post['companyId'];
                $data_to_insert['creator_sid'] = $post['employeeId'];
                $data_to_insert['course_sid'] = $_POST['courseId'];
                $data_to_insert['chapter_sid'] = $_POST['chapterId'];
                $data_to_insert['question'] = $_POST['question'];
                $data_to_insert['answer'] = $_POST['answer'];
                $data_to_insert['type'] = $_POST['type'];
                $data_to_insert['sort_order'] = $_POST['sort_order'];
                //
                $insert_id = $this->cm->addData('lms_manual_course_question', $data_to_insert);
                $questionCount = $this->cm->countChapterQuestion($_POST['courseId'], $_POST['chapterId']);
                //
                $data_to_update = array();
                $data_to_update['count_question'] = $questionCount;
                //
                if ($questionCount > 0) {
                    $data_to_update['is_question'] = 1;
                } else if ($questionCount == 0) {
                    $data_to_update['is_question'] = 0;
                }
                //
                $this->cm->updateChapter($data_to_update, $_POST['chapterId']);
                //
                $this->res['QuestionCount'] = $questionCount + 1;
                $this->res['Response'] = 'Question save successfully';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break; 

            case 'update_question':
                //
                $data_to_update = array();
                $data_to_update['question'] = $_POST['question'];
                $data_to_update['answer'] = $_POST['answer'];
                $data_to_update['type'] = $_POST['type'];
                $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                //
                $this->cm->updateQuestion($data_to_update, $_POST['questionId']);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Question updated successfully';
                //
                $this->resp();
                //
                break;  

            case 'get_chapter_detail':
                $this->res['VideoURL']   = $this->cm->getChapterVideo($post['companyId'],$post['courseId'],$post['chapterId']);
                $this->res['Questions'] = $this->cm->getChapterQuestions($post['companyId'],$post['courseId'],$post['chapterId']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                //
                $this->resp();
                break;  

            case 'update_sort_order':
                //
                if (empty($_POST['sortOrder'])) {
                    $this->res['Response'] = '<strong>Sorry</strong>, Questions not sorted.';
                    $this->resp();
                }
                //
                foreach ($_POST['sortOrder'] as $key => $questionSid) {
                    $data_to_update = array();
                    $data_to_update['sort_order'] = $key;
                    $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                    //
                    $this->cm->updateQuestion($data_to_update, $questionSid);
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Sort order updated successfully';
                //
                $this->resp();
                //
                break; 

            case 'delete_question':
                $this->cm->deleteCourseQuestion($post['questionId']);
                $questionCount = $this->cm->countChapterQuestion($_POST['courseId'], $_POST['chapterId']);
                //
                $data_to_update = array();
                $data_to_update['count_question'] = $questionCount;
                //
                if ($questionCount > 0) {
                    $data_to_update['is_question'] = 1;
                } else if ($questionCount == 0) {
                    $data_to_update['is_question'] = 0;
                }
                //
                $this->cm->updateChapter($data_to_update, $_POST['chapterId']);
                //
                $this->res['QuestionCount'] = $questionCount + 1;
                $this->res['Status'] = true;
                $this->res['Response'] = 'Question delete successfully.';
                //
                $this->resp();
                break;  
                break;    

            case 'get_question':
                $this->res['QuestionInfo'] = $this->cm->getQuestionInfo($post['questionId']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                //
                $this->resp();
                break;       

            case "get_employees_list":
                $this->res['employees']   = $this->cm->getAllActiveEmployees($post['companyId']);
                $this->res['departments'] = $this->cm->getAllDepartments($post['companyId']);
                $this->res['jobTitles']   = $this->cm->getAllJobTitles($post['companyId']);
                $this->res['Status'] = true;
                $this->res['Response'] = 'Proceed.';
                //
                $this->resp();
                break; 

            case 'get_employees':  
                $this->res['Employees'] = $this->cm->getAllActiveEmployees(
                    $post['companyId'],
                    $post['department_sids'],
                    $post['included_sids'],
                    $post['excluded_sids'],
                    $post['employee_types'],
                    $post['job_titles']
                );
                $this->res['Status']    = true;
                $this->res['Response']  = 'Proceed.';
                //
                $this->resp();
                break;

            case 'get_departments': 
                $this->res['Departments'] = $this->cm->getAllDepartments($post['companyId']);
                $this->res['Status']    = true;
                $this->res['Response']  = 'Proceed.';
                //
                $this->resp();
                break;    

            case 'get_job_titles': 
                $this->res['JobTitles'] = $this->cm->getAllJobTitles($post['companyId']);
                $this->res['Status']    = true;
                $this->res['Response']  = 'Proceed.';
                //
                $this->resp();
                break;     

            case 'get_assigned_employees': 
                $this->res['AssignedEmployees'] = $this->cm->getAllAssignedEmployees($post['companyId'], $post['courseIds']);
                $this->res['Status']    = true;
                $this->res['Response']  = 'Proceed.';
                //
                $this->resp();
                break;     

            case 'save_assigned_employees':
                //
                $this->cm->deleteAssignedEmployees($post['companyId'], $post['courseId']);
                //
                foreach ($post['employees'] as $employeeID) {
                    //
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $post['companyId'];
                    $data_to_insert['creator_sid'] = $post['employeeId'];
                    $data_to_insert['employee_sid'] = $employeeID;
                    $data_to_insert['course_sid'] = $post['courseId'];
                    //
                    $this->cm->addData('lms_assigned_employees', $data_to_insert);
                }
                //
                $this->res['Status']    = true;
                $this->res['Response'] = 'Employees are added Successfully.<br>Do you want to complete this course?';
                $this->res['Code'] = "SUCCESS";
                //
                $this->resp();
                break;   

            case 'finish_course':
                //
                $data_to_update = array();
                $data_to_update['is_draft'] = 0;
                $data_to_update['is_completed'] = 1;
                //
                $this->cm->updateCourse($data_to_update, $_POST['courseId']);
                //
                $this->res['RedirectURL'] = base_url('lms_courses/overview');
                $this->res['Redirect'] = true;
                $this->res['Status']    = true;
                $this->res['Response'] = 'Course completed successfully.';
                $this->res['Code'] = "SUCCESS";
                //
                $this->resp();
                break;        

            case 'get_assigned_courses':
                //
                $courses = $this->cm->getAssignedCourses($post['companyId'], $post['employeeId'], $post['type']);
                $pendingCount = $this->cm->getMyAssignedPendingCourses($post['employeeId']);
                $completedCount = $this->cm->getMyAssignedCompletedCourses($post['employeeId']);
                //
                $this->res['Courses'] = $courses;
                $this->res['pendingCount'] = $pendingCount;
                $this->res['completedCount'] = $completedCount;
                $this->res['Response'] = 'Proceed.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;    

            case 'get_specific_course':
                $course = $this->cm->getAssignedSpecificCourse($post['courseId']);
                //
                if ($course['type'] == 'manual') {
                    $chaptersInfo = $this->cm->getManualCourseInfo($post['courseId']);
                    //
                    foreach ($chaptersInfo as $ckey => $chapter) {
                        $questions = $this->cm->getChapterQuestions($post['companyId'], $post['courseId'], $chapter['chapterID']);
                        $chaptersInfo[$ckey]['questions'] = $questions;
                    }
                    //
                    $this->res['Chapters'] = $chaptersInfo;
                } else if ($course['type'] == 'upload') {
                    $scormPath = $this->cm->getScromCourseInfo($post['courseId']);
                    //
                    $this->load->library('Scorm/Scorm_lib', '', 'slib');
                    //
                    $scormInfo = $this->slib->LoadFile($scormPath."/imsmanifest.xml")->GetIndex();
                    //
                    $this->res['Scrom'] = $scormInfo;
                }
                //
                $this->res['Course'] = $course;
                $this->res['Response'] = 'Proceed.';
                $this->res['Code'] = "SUCCESS";
                $this->res['Status'] = true;
                $this->resp();
                break;    
                break;    

            case 'video_completed';
                $data_to_update = array();
                $data_to_update['watched_video'] = 1;
                $data_to_update['watched_video_at'] = date('Y-m-d');
                //
                $this->cm->updateAssignedChapter($data_to_update, $_POST['employeeId'], $_POST['courseId'], $_POST['chapterId']);
                //
                $questions = $this->cm->getChapterQuestions($_POST['companyId'], $_POST['courseId'], $_POST['chapterId']);
                //
                if (empty($questions)) {
                    $data_to_update = array();
                    $data_to_update['chapter_completed'] = 1;
                    $data_to_update['chapter_completed_at'] = date('Y-m-d');
                    $data_to_update['quiz_status'] = 'pass';
                    $data_to_update['quiz_total_marks'] = 0;
                    $data_to_update['quiz_obtain_marks'] = 0;
                    $data_to_update['quiz_completed'] = 1;
                    $data_to_update['quiz_completed_at'] = date('Y-m-d');
                    //
                    $this->cm->updateAssignedChapter($data_to_update, $_POST['employeeId'], $_POST['courseId'], $_POST['chapterId']);
                    //
                    $checkChapter = $this->cm->checkRemainingChapter($_POST['employeeId'], $_POST['courseId']);
                    //
                    if ($checkChapter == 0) {
                        $this->cm->finishMyCourse($_POST['employeeId'], $_POST['courseId']);
                    }
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Chapter video mark as completed successfully';
                //
                $this->resp();
                //
                break;    

            case 'quiz_completed';
                //
                $quiz_data = json_decode($_POST['quiz'], true);
                $questions = $this->cm->getChapterQuestions($_POST['companyId'], $_POST['courseId'], $_POST['chapterId']);
                $totalMarks = count($questions);
                $obtainMarks = 0;
                $quizStatus = 'fail';
                //
                foreach ($questions as $question) {
                    $questionKey = getQuizKey($question['question']);
                    $answer = $question['answer'];
                    //
                    if ($quiz_data[$questionKey] == $answer) {
                        $obtainMarks++;
                    }
                }
                //
                if ($obtainMarks == $totalMarks) {
                    $quizStatus = 'pass';
                }
                //
                $data_to_update = array();
                $data_to_update['chapter_completed'] = 1;
                $data_to_update['chapter_completed_at'] = date('Y-m-d');
                $data_to_update['quiz'] = serialize($_POST['quiz']);
                $data_to_update['quiz_status'] = $quizStatus;
                $data_to_update['quiz_total_marks'] = $totalMarks;
                $data_to_update['quiz_obtain_marks'] = $obtainMarks;
                $data_to_update['quiz_completed'] = 1;
                $data_to_update['quiz_completed_at'] = date('Y-m-d');
                //
                $this->cm->updateAssignedChapter($data_to_update, $_POST['employeeId'], $_POST['courseId'], $_POST['chapterId']);
                //
                $checkChapter = $this->cm->checkRemainingChapter($_POST['employeeId'], $_POST['courseId']);
                //
                if ($checkChapter == 0) {
                    $this->cm->finishMyCourse($_POST['employeeId'], $_POST['courseId']);
                }
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Chapter quiz mark as completed successfully';
                //
                $this->resp();
                //
                break;    

            case 'save_scorm_progress':
                //
                $scorm_data = array();
                $scorm_new_data = json_decode($post['scorm'], true);
                //
                $previousInfo = $this->cm->getPreviousScormData($_POST['employeeId'], $_POST['courseId']);
                //
                if (!empty($previousInfo['scorm_data'])) {
                    $scorm_old_data = unserialize($previousInfo['scorm_data']);
                    
                    if ($scorm_old_data['version'] == '12') {
                        // _e($scorm_new_data,true);
                        // _e($scorm_old_data,true);
                        $scorm_data = $this->manage12ScromChapterData($scorm_new_data, $scorm_old_data);
                    } else {
                        $scorm_data = $this->manage2004ScromChapterData($scorm_new_data, $scorm_old_data);
                    }
                    
                    // _e($scorm_data,true,true);
                }
                //
                $data_to_update = array();
                $data_to_update['scorm_data'] = serialize($scorm_data);
                $data_to_update['updated_at'] = date('Y-m-d H:i:s');
                //
                if ($scorm_new_data['completion_status'] == 'completed' && $scorm_new_data['success_status'] != 'unknown') {
                    //
                    $data_to_update['completed_chapters'] = $post['chapter'];
                    //
                    if ($previousInfo['total_chapters'] == $post['chapter']) {
                        $data_to_update['chapter_completed_at'] = date('Y-m-d');
                        $this->cm->finishMyCourse($_POST['employeeId'], $_POST['courseId']);
                        //
                        $this->res['Chapter'] = 'completed';
                    }
                    
                } else if ($scorm_new_data['version'] == '12' && ($scorm_new_data['lesson_status'] == 'passed' || $scorm_new_data['lesson_status'] == 'completed')) {
                    $data_to_update['completed_chapters'] = $post['chapter'];
                    $data_to_update['chapter_completed_at'] = date('Y-m-d');
                }
                //
                $this->cm->updateEmployeeScormData($data_to_update, $_POST['employeeId'], $_POST['courseId']);
                //
                $this->res['Status'] = true;
                $this->res['Response'] = 'Task completed successfully';
                //
                $this->resp();
                break;    
        } 
        //
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
}
