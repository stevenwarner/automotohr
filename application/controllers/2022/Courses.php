<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee Survey Module
 *
 * PHP version = 7.4.25
 *
 * @category   Module
 * @package    LMS Courses
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Mubashir Ahmed
 * @version    1.0
 * @link       https://www.automotohr.com
 */

class Courses extends Public_Controller
{
    // Set page path
    private $pp;
    // Set mobile path
    private $mp;
    /**
     * Holds the pages
     */
    private $pages;
    //
    private $res = array();
    //
    private $scorm_versions;

    /**
     * 
     */
    public function __construct()
    {
        // Inherit parent properties and methods
        parent::__construct();
        // Load user agent
        $this->load->library('user_agent');
        //
        $this->pages = [
            'header' => 'main/header_2022',
            'footer' => 'main/footer_2022',
        ];
        //
        $this->mp = $this->agent->is_mobile() ? 'mobile/' : '';
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
        //    
        $this->load->model("2022/Course_model", "cm");
    }


    /**
     *
     */
    public function overview()
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];

            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['load_view'] = 1;
            $data['page'] = 'courses_overview';
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $data['PageScripts'] = [
                '2022/js/courses/overview'
            ];
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/overview")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    /**
     *
     */
    public function courses($id = 0)
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];

            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['load_view'] = 1;
            $data['page'] = 'courses_list';
            $data['course_sid'] = $id;
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $data['PageScripts'] = [
                '2022/js/courses/courses'
            ];
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/courses")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    /**
     *
     */
    public function create()
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];
            //
            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['load_view'] = 1;
            $data['page'] = 'create_courses';
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $data['PageCSS'] = [
                'mFileUploader/index',
                'css\theme-2021'
            ];
            //
            $data['PageScripts'] = [
                '2022/js/courses/create',
                'mFileUploader/index'
            ];
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/create")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    /**
     *
     */
    public function my_courses_list()
    {
        //
        if ($this->session->userdata('logged_in')) {
            $data = [];
            $data['session'] = $this->session->userdata('logged_in');
            $employee_detail = $data['session']['employer_detail'];
            $company_detail  = $data['session']['company_detail'];
            $employee_sid  = $employee_detail['sid'];
            $ems_status = $company_detail['ems_status'];
            //
            if (!$ems_status) {
                $this->session->set_flashdata('message', '<strong>Warning</strong> Not Allowed!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['load_view'] = 1;
            $data['page'] = 'assign_courses';
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = db_get_access_level_details($employee_sid);
            $data['employee'] = $employee_detail;
            //
            $data['PageScripts'] = [
                '2022/js/courses/create',
            ];
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/my_courses_list")
                ->view($this->pages['footer']);
        } else {
            redirect(base_url('login'), 'refresh');
        }
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
        if (!isset($post['companyId']) || $post['companyId'] == '') $this->resp();
        if (!isset($post['employeeId']) || $post['employeeId'] == '') $this->resp();
        $post['public'] = 0;
        // For expired session
        if ($post['public'] == 0 && empty($this->session->userdata('logged_in'))) {
            $this->res['Redirect'] = true;
            $this->res['Response'] = 'Your login session has expired.';
            $this->res['Code'] = 'SESSIONEXPIRED';
            $this->resp();
        }
        //
        $this->res['Redirect'] = FALSE;
        //
        switch (strtolower($post['action'])) {
            // Fetch company
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
                $company_id = $post['companyId'];
                $companyName = getCompanyNameBySid($company_id);
                $target_file_name = basename($_FILES["upload_zip"]["name"]);
                $file_name = strtolower($random . '_' . $target_file_name);
                //
                $target_dir = 'assets/temp_files/scorm/' . strtolower(preg_replace('/\s+/', '_', $companyName)) . '/' . $_POST['courseId'] .'/';
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
                        $newName = $random . '_' .$file_info['filename'];
                        $zip->renameName($_FILES["upload_zip"]["name"],$newName);
                        $zip->extractTo($target_dir);
                        $zip->close();
                    }
                    //
                    $unzipFile = $target_dir .$file_info['filename'];
                    $this->load->library('Scorm/Scorm_lib', '', 'slib');
                    //
                    $courseContent = $this->slib->LoadFile($unzipFile."/imsmanifest.xml")->GetIndex();
                    if (
                        !empty($courseContent) && 
                        isset($courseContent["items"]) &&
                        !empty($courseContent["items"])
                    ) {
                        if (
                            in_array($courseContent['version'], $this->scorm_versions)
                        ) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $post['companyId'];
                            $data_to_insert['creator_sid'] = $post['employeeId'];
                            $data_to_insert['course_sid'] = $_POST['courseId'];
                            $data_to_insert['upload_scorm_file'] = $unzipFile;
                            $data_to_insert['version'] = $courseContent['version'];
                            //
                            $insert_id = $this->cm->addData('lms_scorm_courses', $data_to_insert);
                            //
                            $this->res['Response'] = '<strong>The file ' . basename($_FILES["upload_zip"]["name"]) . ' has been uploaded.';
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
