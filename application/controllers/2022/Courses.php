<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee Survey Module
 *
 * PHP version = 7.4.25
 *
 * @category   Module
 * @package    LMS Courses
 * @author     AutomotoHR <www.automotohr.com>
 * @author     Aleem Shaukat
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
                '2022/js/courses/assign',
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
     *
     */
    public function my_course($course_sid)
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
            $data['course_sid'] = $course_sid;
            //
            $page = "";
            //
            $this->cm->startMyCourse($employee_sid, $course_sid);
            //
            $course = $this->cm->getAssignedSpecificCourse($course_sid);
            $data['course'] = $course;
            //
            if ($course['type'] == 'manual') {
                $chaptersInfo = $this->cm->getManualCourseInfo($course_sid);
                $chapterFlag = 0;

                $currentChapter = array(
                    'chapter_sid' => 0,
                    'status' => 'chapter_completed',
                    'videoURL' => null,
                    'quiz' => null,
                    'title' => '',
                    'description' => '',
                    'chaptersCount' => count($chaptersInfo),
                    'completedCount' => 0
                );
                //
                foreach ($chaptersInfo as $ckey => $chapter) {
                    //
                    if ($chapterFlag == 0) {
                        $checkChapter = $this->cm->checkChapterCompleted($employee_sid, $course_sid, $chapter['chapterID']);
                        //
                        if ($checkChapter == 'insert_record') {
                            $data_to_insert = array();
                            $data_to_insert['employee_sid'] = $employee_sid;
                            $data_to_insert['course_sid'] = $course_sid;
                            $data_to_insert['chapter_sid'] = $chapter['chapterID'];
                            //
                            $insert_id = $this->cm->addData('lms_manual_employee_course', $data_to_insert);
                        }
                        //
                        if ($checkChapter != 'chapter_completed') {
                            //
                            $chapterFlag = 1;
                            $currentChapter['chapter_sid'] = $chapter['chapterID'];
                            $currentChapter['title'] = $chapter['title'];
                            $currentChapter['description'] = $chapter['description'];
                            //
                            if ($checkChapter == 'insert_record' || $checkChapter == 'video_pending') {
                                $currentChapter['status'] = 'video_pending';
                                $currentChapter['videoURL'] = $chapter['videoURL'];
                            } else if ($checkChapter == 'quiz_pending') {
                                $questions = $this->cm->getChapterQuestions($company_detail['sid'], $course_sid, $chapter['chapterID']);
                                //
                                $currentChapter['status'] = 'quiz_pending';
                                $currentChapter['quiz'] = $questions;
                            }
                        } else if ($checkChapter == 'chapter_completed') {
                            $currentChapter['completedCount']++;

                        }
                    }
                }
                //
                if ($currentChapter['chaptersCount'] == $currentChapter['completedCount']) {
                    $currentChapter['title'] = 'Result Card';
                    $data['courseInfo'] = $this->cm->getChapterCompletedInfo($employee_sid, $course_sid);
                }
                //
                $data['chapter'] = $currentChapter;
                $page = 'manual';
                //
                $data['PageScripts'] = [
                    '2022/js/courses/assign'
                ];
            } else if ($course['type'] == 'upload') {
                //
                $scormData = '';
                $checkMyScorm = $this->cm->checkEployeeeScromCourse($course_sid, $employee_sid);
                //
                if ($checkMyScorm['status'] == 'insert_record') {
                    //
                    $scormPath = $this->cm->getScromCourseInfo($course_sid);
                    //
                    $this->load->library('Scorm/Scorm_lib', '', 'slib');
                    //
                    $scormInfo = $this->slib->LoadFile($scormPath."/imsmanifest.xml")->GetIndex();
                    $scormInfo['path'] = base_url().$scormPath."/shared/launchpage.html?content=";
                    //
                    $data_to_insert = array();
                    $data_to_insert['employee_sid'] = $employee_sid;
                    $data_to_insert['course_sid'] = $course_sid;
                    $data_to_insert['total_chapters'] = count($scormInfo['items']);
                    $data_to_insert['scorm_data'] = serialize($scormInfo);
                    //
                    $this->cm->addData('lms_scorm_employee_course', $data_to_insert);
                    $scormData = $scormInfo;
                    //
                    if ($scormData['storage'] > 0) {
                        foreach ($scormData['items'] as $ikey => $item) {
                            if (isset($item['chapter_note'])) {
                                $scormData['chapters_notes'][$ikey] = $item['chapter_note']; 
                            } else {
                                $scormData['chapters_notes'][$ikey] = ""; 
                            }
                        }
                    }
                }
                //
                if ($checkMyScorm['status'] == 'insert_record' || $checkMyScorm['status'] == 'course_pending') {
                    $data['courseStatus'] = 'course_pending';
                    //
                    if ($checkMyScorm['status'] == 'course_pending') {
                        $scormData = $checkMyScorm['scorm_data'];
                        //
                        if ($scormData['storage'] > 0) {
                            foreach ($scormData['items'] as $ikey => $item) {
                                if (isset($item['chapter_note'])) {
                                    $scormData['chapters_notes'][$ikey] = $item['chapter_note']; 
                                } else {
                                    $scormData['chapters_notes'][$ikey] = ""; 
                                }
                            }
                        }

                    }
                    //
                } else if ($checkMyScorm['status'] == 'course_completed') {
                    $scormData = $this->cm->getPreviousScormData($employee_sid, $course_sid);
                    //
                    if (!empty($scormData['scorm_data'])) {
                        $scormData = unserialize($scormData['scorm_data']);
                        $data['scormData'] = $scormData['items']; 
                        // _e($scormData,true,true);
                    }
                    $data['courseStatus'] = 'course_completed';
                }
                //
                $data['scorm'] = $scormData;
                // _e($checkMyScorm,true);
                // _e($scormData,true,true);
                //
                $page = 'scorm';
                //
                if (preg_match('/2004/', $scormData["version"])) {
                    $versionScript ="2022/js/courses/scorm/scorm-2004";  
                } else {
                    $versionScript ="2022/js/courses/scorm/scorm-1.2";
                }     
                //
                $data['PageScripts'] = [
                    '2022/js/courses/scorm/scorm',
                    $versionScript,
                ];
                //
                $data['PageCSS'] = [
                    '2022/css/courses/scorm/style'
                ];
            }
            // _e($data['chapter'],true,true);
            //
            $this->load
                ->view($this->pages['header'], $data)
                ->view("{$this->mp}courses/{$page}")
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
                    //
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
                    $scorm_data = $this->manageScromChapterData($scorm_new_data, $scorm_old_data);
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
     * 
     */
    private function manageScromChapterData($newData, $oldData)
    {
        $totalSeconds = 0;
        //
        $oldData['items'][$newData['level']]['type'] = $newData['type'];
        //
        if (isset($oldData['items'][$newData['level']]['spent_seconds'])) {
            $totalSeconds = $oldData['items'][$newData['level']]['spent_seconds'] + $newData['spent_seconds'];
        } else {
            $totalSeconds = $newData['spent_seconds'];
        }
        //
        if ($newData['action'] == 'result') {
            $oldData['items'][$newData['level']]['completion_status'] = $newData['completion_status'];
            $oldData['items'][$newData['level']]['success_status'] = $newData['success_status'];
            $oldData['items'][$newData['level']]['session_time'] = $newData['session_time'];
            $oldData['items'][$newData['level']]['spent_seconds'] = $totalSeconds;
            $oldData['items'][$newData['level']]['progress_measure'] = $newData['progress_measure'];
            $oldData['items'][$newData['level']]['attempted_date'] = $newData['date'];
            //
            if ($newData['progress_measure'] == 1 && $newData['success_status']== 'unknown') {
                $oldData['items'][$newData['level']]['success_status'] = 'passed';
            }else if ($newData['progress_measure'] == 0 && $newData['success_status']== 'unknown') {
                $oldData['items'][$newData['level']]['success_status'] = 'failed';
            }
            //
            if ($newData['type'] == 'quiz') {
                $oldData['items'][$newData['level']]['score_max'] = $newData['score_max'];
                $oldData['items'][$newData['level']]['score_min'] = $newData['score_min'];
                $oldData['items'][$newData['level']]['score_raw'] = $newData['score_raw'];
                $oldData['items'][$newData['level']]['score_scaled'] = $newData['score_scaled'];
                //
                $nextChapter = $newData['level'] + 1;
                $chapterCount = count($oldData['items']);
                //
                if ($chapterCount > $nextChapter) {
                    $oldData['lastChapter'] = $nextChapter;
                }
                $oldData['lastLocation'] = 0;
            }
        }
        //
        if ($newData['action'] == 'note') {
            if ($oldData['storage'] > 0) {
                $oldData['items'][$newData['level']]['chapter_note'] = $newData['chapter_note'];
            } 
        }
        //
        if ($newData['action'] == 'location') {
            if ($oldData['items'][$newData['level']]['location'] <= $newData['location']) {
                //
                $oldData['items'][$newData['level']]['location'] = $newData['location'];
                //
                if ($oldData['items'][$newData['level']]['slides'] == ($newData['location'] + 1)) {
                    $oldData['lastLocation'] = 0;
                    //
                    $nextChapter = $newData['level'] + 1;
                    $chapterCount = count($oldData['items']);
                    //
                    if ($chapterCount > $nextChapter) {
                        $oldData['lastChapter'] = $nextChapter;
                    }
                    //
                } else {
                    $oldData['lastLocation'] = $newData['location'];
                }
            }
        }
        //
        return $oldData;
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
