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

    public function subordinateCourses ($type, $subordinateId) {
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
        // $subordinateInfo = getMyDepartmentAndTeams($employeeId, "courses");
        $subordinateInfo = [];
        //
        $haveSubordinate = 'no';
        //
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $haveSubordinate = 'yes';
            $uniqueKey = $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        } else if ($session['employer_detail']['access_level_plus']) {
            $haveSubordinate = 'yes';
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
        $data['type'] = $type;
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

    public function companyReport ($departments = "all", $courses = "all", $employees = "all") {
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
            //
            $filters = [
                "departments" => urldecode($departments),
                "courses" => urldecode($courses),
                "employees" => urldecode($employees)
            ];
            //
            $companyEmployeesList = $this->course_model->getAllActiveEmployees($data['company_sid'], false);
            //
            $filterData = [];
            $filterData["employees"] = $companyEmployeesList;
            $filterData["courses"] = $this->course_model->getActiveCourseList($data['company_sid'], "all");
            $filterData["departments"] = $this->course_model->getCompanyActiveDepartment($data['company_sid'], "all");
            //
            $fetchDepartment = 'all';
            $fetchEmployees = 'all';
            //
            if ($filters["employees"] != "all") {
                $departmentIds = $this->course_model->getEmployeeDepartmentIds($data['company_sid'], $filters["employees"]);
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $fetchDepartment = $departmentIds;
                    $fetchEmployees = $filters["employees"];
                } else {
                    $fetchDepartment = $filters["departments"].','.implode(",", $departmentIds);
                    $fetchDepartmentEmployees = $this->course_model->getAllDepartmentEmployees($data['company_sid'], $filters["departments"]);

                    $fetchEmployees = $filters["employees"].','.implode(",", $fetchDepartmentEmployees);
                }
            }
            //
            $companyCoursesList = $this->course_model->getActiveCourseList($data['company_sid'], $filters["courses"]);
            //
            $companyReport = [
                "employee_have_courses" => 0,
                "employee_not_have_courses" => 0,
                "total_employees" => 0, 
                "departments_report" => [],
                "courses_report" => [
                    "expired" => 0,
                    "started" => 0,
                    "coming" => 0,
                ],
                "EmployeeList" => [],
                "CoursesList" => []
            ];
            //
            if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                //
                $today = getSystemDate("Y-m-d");
                //
                $coursesList = [];
                $employeesList = [];
                $departments = [];
                //
                foreach ($companyCoursesList as $course) {
                    //
                    $coursesList[$course["sid"]] = $course;
                    //
                    // Todo: convert caparison with date instead of date.
                    $today_time = strtotime($today);
                    $start_time = strtotime($course['course_start_period']);
                    $end_time = strtotime($course['course_end_period']);

                    if ($end_time < $today_time) {
                        $coursesList[$course["sid"]]['status'] = "Expired";
                        $companyReport["courses_report"]["expired"]++;
                    } else if ($start_time > $today_time) {
                        $coursesList[$course["sid"]]['status'] = "Coming";
                        $companyReport["courses_report"]["coming"]++;
                    } else if ($start_time < $today_time && $end_time > $today_time) {
                        $coursesList[$course["sid"]]['status'] = "Started";
                        $companyReport["courses_report"]["started"]++;
                    }
                    //
                    $coursesList[$course["sid"]]['assign_employee_count'] = 0;
                    $coursesList[$course["sid"]]['assign_employee_pending_count'] = 0;
                    $coursesList[$course["sid"]]['assign_employee_completed_count'] = 0;
                }
                //
                $companyDepartments = $this->course_model->getCompanyActiveDepartment($data['company_sid'], $fetchDepartment);
                $jobTitleIds = array_filter(array_column($companyEmployeesList, "job_title_sid"));
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $data['company_sid']);
                //
                
                //
                foreach ($companyDepartments as $department) {
                    $departments[$department['sid']] = $department;
                    $departments[$department['sid']]['employee_have_courses'] = 0;
                    $departments[$department['sid']]['employee_not_have_courses'] = 0;
                    $departments[$department['sid']]['total_employees'] = 0;
                    $departments[$department['sid']]['pending_courses'] = 0;
                    $departments[$department['sid']]['completed_courses'] = 0;
                    $departments[$department['sid']]['total_courses'] = 0;
                }
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $departments[0]['sid'] = 0;
                    $departments[0]['name'] = "Other";
                    $departments[0]['employee_have_courses'] = 0;
                    $departments[0]['employee_not_have_courses'] = 0;
                    $departments[0]['total_employees'] = 0;
                    $departments[0]['pending_courses'] = 0;
                    $departments[0]['completed_courses'] = 0;
                    $departments[0]['total_courses'] = 0;
                }    
                //
                $companyReport["total_employees"] = count($companyEmployeesList);
                //
                
                foreach ($companyEmployeesList as $ekey => $employee) {
                    
                    if ($fetchEmployees == "all" || in_array($employee['sid'], explode("," ,$fetchEmployees))) {
                        // _e($employee['sid'],true);
                        
                        $employeesList[$employee['sid']]["sid"]  = $employee['sid'];
                        //
                        $employeeName = remakeEmployeeName([
                            'first_name' => $employee['first_name'],
                            'last_name' => $employee['last_name'],
                            'access_level' => $employee['access_level'],
                            'timezone' => isset($employee['timezone']) ? $employee['timezone'] : '',
                            'access_level_plus' => $employee['access_level_plus'],
                            'is_executive_admin' => $employee['is_executive_admin'],
                            'pay_plan_flag' => $employee['pay_plan_flag'],
                            'job_title' => $employee['job_title'],
                        ]);
                        //
                        $employeesList[$employee['sid']]["full_name"]  = $employeeName;
                        //
                        if (!empty($employee['job_title_sid'])) {
                            $employeesList[$employee['sid']]["courses_sid"]  = $jobRoleCourses[$employee['job_title_sid']];
                            //
                            $employeesList[$employee['sid']]["courses_statistics"] = $this->course_model->checkEmployeeCoursesReport(
                                $data['company_sid'], 
                                $employee['sid'],
                                $jobRoleCourses[$employee['job_title_sid']]
                            );
                            //
                            $companyReport["employee_have_courses"]++;
                            //
                            foreach ($employeesList[$employee['sid']]["courses_statistics"]["coursesInfo"] as $cikey => $coursesStatus) {
                                //
                                if (isset($coursesList[$cikey])) {
                                    $coursesList[$cikey]['assign_employee_count']++;
                                    //
                                    if ($coursesStatus == 0) {
                                        $coursesList[$cikey]['assign_employee_pending_count']++;
                                    } else {
                                        $coursesList[$cikey]['assign_employee_completed_count']++;
                                    }
                                }
                            }
                        } else {
                            $employeesList[$employee['sid']]['job_title_sid'] = 0;
                            $employeesList[$employee['sid']]["courses_sid"]  = 0;
                            $employeesList[$employee['sid']]["courses_statistics"] = [
                                "completedCount" => 0,
                                "pendingCount" => 0,
                                "courseCount" => 0,
                                "percentage" => 0
                            ];
                            //
                            $companyReport["employee_not_have_courses"]++;
                        }
                        //
                        $departmentAndTeams = $this->course_model->fetchDepartmentTeams($employee['sid']);
                        $employeesList[$employee['sid']]["departments"]  = $departmentAndTeams['departmentIds'];
                        $employeesList[$employee['sid']]["teams"]  = $departmentAndTeams['teamIds'];
                        //
                        $employeeDepartments = explode(",", $departmentAndTeams['departmentIds']);
                        //
                        foreach ($employeeDepartments as $employeeDepartment) {
                            //
                            if (isset($departments[$employeeDepartment])) {
                                if ($employeesList[$employee['sid']]["courses_sid"] == 0) {
                                    $departments[$employeeDepartment]['employee_not_have_courses']++;
                                } else {
                                    $departments[$employeeDepartment]['employee_have_courses']++;
                                    //
                                    $departments[$employeeDepartment]['pending_courses'] = $departments[$employeeDepartment]['pending_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['pendingCount'];
                                    $departments[$employeeDepartment]['completed_courses'] = $departments[$employeeDepartment]['completed_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['completedCount'];
                                    $departments[$employeeDepartment]['total_courses'] = $departments[$employeeDepartment]['total_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['courseCount'];
                                    //
                                }
                                //
                                $departments[$employeeDepartment]['employees'][] = $employee['sid'];
                            }
                            
                        }
                        //
                    }
                }
                //
                $companyReport["departments_report"] = $departments;
                $companyReport["EmployeeList"] = $employeesList;
                $companyReport["CoursesList"] = $coursesList;
            } 
            // _e($filters,true,true);   
            // _e($companyReport,true,true);
            //
            $data["companyReport"] = $companyReport;
            $data["filters"] = $filters;
            $data["filterData"] = $filterData;
            $companyReport["departments_report"] = $departments;
            //
            if ($this->input->method() === 'post') {
                if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    
                    $myColumns = array(
                        'sid',
                        'Title',
                        'first_name',
                        'last_name',
                        'email',
                        'phone_number',
                        'date_applied',
                        'applicant_type',
                        'questionnaire',
                        'score',
                        'passing_score',
                        'status'
                    );
                    $cols = array();

                    // foreach ($myColumns as $col) {
                    //     if ($col != 'questionnaire' && $col != 'score' && $col != 'passing_score' && $col != 'sid') {
                    //         if ($col == 'Title') {
                    //             $cols[] = 'Job Title';
                    //         } else {
                    //             $cols[] = ucwords(str_replace('_', ' ', $col));
                    //         }
                    //     }
                    // }
                    $cols[] = 'Questionnaire Score';
                    $cols[] = 'Reviews Score';
                    $cols[] = 'Interview Scores';

                    fputcsv($output, array($companyinfo['company_name']));

                    fputcsv($output, $cols);

                    foreach ($myRecords as $applicant) {
                        $input = array();

                        foreach ($myColumns as $myColumn) {
                            if ($myColumn != 'questionnaire' && $myColumn != 'score' && $myColumn != 'passing_score' && $myColumn != 'sid') {
                                if ($myColumn != 'Title' && $myColumn != 'applicant_type') {
                                    $columnDetail = $column_info[$myColumn];
                                    $columnType = $columnDetail['type'];
                                    if ($columnType == 'datetime') {
                                        $input[$myColumn] = reset_datetime(array('datetime' => $applicant[$myColumn], '_this' => $this));
                                    } else {
                                        $input[$myColumn] = $applicant[$myColumn];
                                    }
                                } else {
                                    $city = '';
                                    $state = '';
                                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                                        $city = ' - ' . ucfirst($applicant['Location_City']);
                                    }
                                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                                        $state = ', ' . db_get_state_name($applicant['Location_State'])['state_name'];
                                    }
                                    $input[$myColumn] = ($applicant[$myColumn] == '' ? 'Job Removed From System' : $applicant[$myColumn] . $city . $state);
                                }
                            }
                        }

                        if ($applicant['questionnaire'] == '' || $applicant['questionnaire'] == NULL) {
                            $input['questionnaire_score'] = 'N/A';
                        } else {
                            $result = $applicant['score'];
                            if ($applicant['score'] >= $applicant['passing_score']) {
                                $result .= ' (Pass)';
                            } else {
                                $result .= ' (Fail)';
                            }
                            $input['questionnaire_score'] = $result;
                        }

                        $input['reviews_score'] = $applicant['review_score'] . ' with ' . $applicant['review_count'] . ' Review(s)';

                        if (sizeof($applicant['scores']) > 0) {
                            $score_text = '';
                            foreach ($applicant['scores'] as $score) {
                                $score_text .= 'Employer : ' . ucwords($score['first_name'] . ' ' . $score['last_name']) . ' ';
                                $score_text .= 'Candidate Score : ' . $score['candidate_score'] . ' out of 100 ';
                                $score_text .= 'Job Relevancy Score : ' . $score['job_relevancy_score'] . ' out of 100; ';
                            }
                        } else {
                            $score_text = 'No interview scores';
                        }

                        $input['scores'] = $score_text;
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                }    
            }
            //
            $this->load->view('main/header', $data);
            $this->load->view('courses/company_report');
            $this->load->view('main/footer');
            
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
