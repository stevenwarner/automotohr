<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 *
 */
class Courses extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        //
        // load the library
        $this->load->library('Api_auth');
        //
        $this->load->model('v1/course_model');
        // call the company event
        $this->api_auth->checkAndLogin(
            $this->session->userdata('logged_in')['company_detail']['sid'],
            $this->session->userdata('logged_in')['employer_detail']['sid']
        );
        //
    }

    /**
     *
     */
    public function myCourses()
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $haveSubordinate = getMyDepartmentAndTeams($employeeId, "", "count_all_results");
        //
        $data['title'] = "My Trainings | " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = 0;
        $data['page'] = "my_courses";
        $data['viewMode'] = "my";
        $data['employee'] = $session['employer_detail'];
        $data['haveSubordinate'] = $haveSubordinate;
        $data['load_view'] = 1;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/assign_employee_courses',
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/my_dashboard')
            ->view('main/footer');
    }

    /**
     *
     */
    public function getCourse(int $sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        // Check if course is already taken then move it to history if needed
        // $this->course_model->moveCourseHistory($sid, $employeeId, $companyId);
        //
        $lessonStatus = '';
        //
        if (!$this->course_model->checkEmployeeCourse($companyId, $employeeId, $sid)) {
            $lessonStatus = 'not_started';
        } else {
            if ($this->course_model->checkEmployeeCourseCompleted($companyId, $employeeId, $sid)) {
                //
                $lessonStatus = 'not_started';
            } else {
                // 
                $lessonStatus = 'started';
            }
        }
        //
        $courseInfo = $this->course_model->getCourseInfo($sid);
        //
        $questions = $courseInfo['course_questions'];
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "my_course";
        $data['subordinate_sid'] = 0;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/preview_assign',
        ];
        //
        if ($courseInfo['course_type'] == "scorm") {
            $viewName = "scorm_course";
            $scormInfo = json_decode($courseInfo['Imsmanifist_json'], true);
            $data['PageScripts'][] = 'v1/plugins/ms_scorm/main';
            //
            if ($scormInfo["version"] == '1.2') {
                $data['PageScripts'][] = 'v1/plugins/ms_scorm/adapter_12';
            } elseif ($scormInfo["version"] == '2004_3') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_3'];
            } elseif ($scormInfo["version"] == '2004_4') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_4'];
            }
            //
            $data['version'] = $scormInfo["version"];
            //
            $data['CMIObject'] = $this->course_model->getCMIObject($sid, $employeeId, $companyId);
        } elseif ($courseInfo['course_type'] == "manual") {
            $viewName = "manual_course";
            //
            $data['questions'] = $questions;
            $data['viewMode'] = "attempt";
        }
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/' . $viewName)
            ->view('main/footer');
    }

    /**
     *
     */
    public function viewCertificate(int $sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $data['title'] = "Certificate :: " . STORE_NAME;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
        ];
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('courses/certificate', $data);
    }

    /**
     *
     */
    public function previewResult($sid)
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $courseInfo = $this->course_model->getCourseInfo($sid);
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['viewMode'] = "preview";
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/preview_assign'
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/manual_course_result_preview')
            ->view('main/footer');
    }

    /**
     *
     */
    public function report($departments = "all", $teams = "all", $employees = "all")
    {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $subordinateInfo = getMyDepartmentAndTeams($employeeId, "courses");
        //
        $uniqueKey = '';
        $haveSubordinate = 'no';
        //
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $haveSubordinate = 'yes';
            $uniqueKey = $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        } 
        //
        $filters = [
            "departments" => $departments,
            "teams" => $teams,
            "employees" => $employees
        ];
        //
        if($this->input->is_ajax_request()){
            if (isset($_GET['departments'])) {
                $filters["departments"] = $_GET['departments'];
            }
            //
            if (isset($_GET['teams'])) {
                $filters["teams"] = $_GET['teams'];
            }
            //
            if (isset($_GET['employees'])) {
                $filters["employees"] = $_GET['employees'];
            }
            //
            $selectedEmployeesList = [];
            $selectedEmployeesIds = [];
            //
            if ($filters["departments"][0] == 'all' || $filters["teams"][0] == 'all' || $filters["employees"][0] == 'all') {
                $selectedEmployeesList = $subordinateInfo['employees'];
            } else {
                foreach ($subordinateInfo['employees'] as $subordinateEmployee) {
                    if ($subordinateEmployee["job_title_sid"] > 0) {
                        //
                        $teamId = $subordinateEmployee['team_sid'];
                        $subordinateEmployee['department_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                        $subordinateEmployee['team_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
                        //
                        if (in_array($subordinateEmployee["employee_sid"], $filters["employees"])) {
                            $selectedEmployeesList[] = $subordinateEmployee;
                            array_push($selectedEmployeesIds, $subordinateEmployee["employee_sid"]);
                        }
                        //
                        if (in_array($subordinateEmployee["team_sid"], $filters["teams"])) {
                            if (!in_array($subordinateEmployee["employee_sid"], $selectedEmployeesIds)) {
                                $selectedEmployeesList[] = $subordinateEmployee;
                                array_push($selectedEmployeesIds, $subordinateEmployee["employee_sid"]);
                            }    
                        }
                        //
                        if (in_array($subordinateEmployee["department_sid"], $filters["departments"])) {
                            if (!in_array($subordinateEmployee["employee_sid"], $selectedEmployeesIds)) {
                                $selectedEmployeesList[] = $subordinateEmployee;
                                array_push($selectedEmployeesIds, $subordinateEmployee["employee_sid"]);
                            }    
                        }
                    }    
                }
            }    
            //
            header('Content-Type: application/json');
            echo json_encode($selectedEmployeesList); exit(0);
        }
        //
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['subordinateInfo'] = $subordinateInfo;
        $data['title'] = "Employee(s) Report";
        $data['filters'] = $filters;
        // load CSS
        //
        // $data['page_css'] = bundleCSS([
        //     '2022/css/main'
        // ]);
        //
        $data['Page_CSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/reporting'
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/report')
            ->view('main/footer');
    }

    public function subordinateCourses ($subordinateId) {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $subordinateInfo = getMyDepartmentAndTeams($employeeId, "courses");
        //
        $haveSubordinate = 'no';
        //
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $haveSubordinate = 'yes';
            $uniqueKey = $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        } 
        //
        $data['title'] = "My Trainings | " . STORE_NAME;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = $subordinateId;
        $data['viewMode'] = "subordinate";
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['page'] = "subordinate_courses";
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/employee_course_preview'
        ];
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/my_dashboard')
            ->view('main/footer');
    }

    public function subordinateCourse ($courseId, $subordinateId) {
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $subordinateInfo = getMyDepartmentAndTeams($employeeId, "courses");
        //
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        } 
        //
        $courseInfo = $this->course_model->getCourseInfo($courseId);
        //
        $questions = $courseInfo['course_questions'];
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $courseId;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "subordinate_course";
        $data['subordinate_sid'] = $subordinateId;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/employee_course_preview',
        ];
        //
        if ($courseInfo['course_type'] == "scorm") {
            $viewName = "scorm_course";
            $scormInfo = json_decode($courseInfo['Imsmanifist_json'], true);
            $data['PageScripts'][] = 'v1/plugins/ms_scorm/main';
            //
            if ($scormInfo["version"] == '1.2') {
                $data['PageScripts'][] = 'v1/plugins/ms_scorm/adapter_12';
            } elseif ($scormInfo["version"] == '2004_3') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_3'];
            } elseif ($scormInfo["version"] == '2004_4') {
                $data['PageScripts'][] = ['v1/plugins/ms_scorm/adapter_2004_4'];
            }
            //
            $data['version'] = $scormInfo["version"];
            //
            $data['CMIObject'] = $this->course_model->getCMIObject($sid, $employeeId, $companyId);
        } elseif ($courseInfo['course_type'] == "manual") {
            $viewName = "manual_course";
            //
            $data['questions'] = $questions;
            
        }
        //
        // get access token
        $data['apiAccessToken'] = getApiAccessToken(
            $companyId,
            $employeeId
        );
        //
        $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
        $data['viewMode'] = "preview_only";
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/' . $viewName)
            ->view('main/footer');
    }

    public function companyReport () {
        if ($this->session->userdata('logged_in')) { 
            // Added on: 28-08-2023
            $session = $this->session->userdata('logged_in');
            //
            if (!$session['employer_detail']['access_level_plus']) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessible!');
                redirect('dashboard', 'refresh');
            }
            //
            $security_sid = $session['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            //
            check_access_permissions($security_details, 'dashboard', 'companyReport'); // Param2: Redirect URL, Param3: Function Name
            //
            $data['session'] = $session;
            $data['security_details'] = $security_details;
            $data['title'] = "LMS Company Report";
            $data['employer_sid'] = $security_sid;
            $data['company_sid'] = $session['company_detail']['sid'];
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            $data['employer_detail'] = $data['session']['employer_detail'];
            $data['company_detail'] = $data['session']['company_detail'];
            //
            $employeesList = $this->course_model->getAllActiveEmployees($data['company_sid'], false);
            //
            if (!empty($employeesList)) {
                //
                $jobTitleIds = array_filter(array_column($employeesList, "job_title_sid"));
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $data['company_sid']);
                //
                foreach ($employeesList as $ekey => $employee) {
                    //
                    if (!empty($employee['job_title_sid'])) {
                        $employeesList[$ekey]["courses_sid"]  = $jobRoleCourses[$employee['job_title_sid']];
                        //
                        $this->course_model->checkEmployeeCoursesReport(
                            $data['company_sid'], 
                            $employee['sid'],
                            $jobRoleCourses[$employee['job_title_sid']]
                        );
                    } else {
                        $employeesList[$ekey]["courses_sid"]  = 0;
                    }
                    //
                    $departmentAndTeams = $this->course_model->fetchDepartmentTeams($employee['sid']);
                    $employeesList[$ekey]["departments"]  = $departmentAndTeams['departmentIds'];
                    $employeesList[$ekey]["teams"]  = $departmentAndTeams['teamIds'];
                }
            }    
            //
            _e($employeesList,true,true);
            //
            $this->load->view('main/header', $data);
            $this->load->view('courses/company_report');
            $this->load->view('main/footer');
            
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
