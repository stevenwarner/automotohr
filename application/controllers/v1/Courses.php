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
        if ($this->session->userdata('logged_in')) {
            $this->api_auth->checkAndLogin(
                $this->session->userdata('logged_in')['company_detail']['sid'],
                $this->session->userdata('logged_in')['employer_detail']['sid']
            );
        }
        //
    }

    /**
     *
     */
    public function dashboard()
    {
        //
        if (isLoggedInPersonAnExecutiveAdmin()) {
            $this->session->set_flashdata("error", "Permission denied!");
            return redirect("/dashboard");
        }
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];

        //
        $data['security_details'] = db_get_access_level_details($employeeId);
        //
        $myDepartmentAndTeams = getMyDepartmentAndTeams($employeeId, "", "count_all_results");
        //
        if (!empty($myDepartmentAndTeams)) {
            $haveSubordinate = 'yes';
        } else {
            $haveSubordinate = 'no';
        }

        if (isPayrollOrPlus()) {
            $haveSubordinate = "yes";
        }
        //
        $data['title'] = "My Course(s) | " . STORE_NAME;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = 0;
        $data['page'] = "my_dashboard";
        $data['viewMode'] = "my";
        $data['employee'] = $session['employer_detail'];
        $data['haveSubordinate'] = $haveSubordinate;
        $data['load_view'] = 1;
        $data['type'] = "self";
        $data['level'] = 0;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'https://code.highcharts.com/highcharts.js',
            'https://code.highcharts.com/modules/exporting.js',
            'https://code.highcharts.com/modules/accessibility.js',
            'js/app_helper',
            'v1/common',
            'v1/lms/employee_courses_dashboard',
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
    public function myCourses()
    {
        return redirect("lms/courses/my_lms_dashboard");
        //
        redirect('lms/courses/my_lms_dashboard', 'refresh');
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
        $myDepartmentAndTeams = getMyDepartmentAndTeams($employeeId, "", "count_all_results");
        //
        if (!empty($myDepartmentAndTeams)) {
            $haveSubordinate = 'yes';
        } else {
            $haveSubordinate = 'no';
        }
        //
        $data['search'] = '';
        //
        if (isset($_GET) && $_GET['type']) {
            $data['search'] = $_GET['type'];
        }
        //
        $data['title'] = "My Course(s) | " . STORE_NAME;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['session'] = $session;
        $data['subordinate_sid'] = 0;
        $data['page'] = "my_courses";
        $data['viewMode'] = "my";
        $data['employee'] = $session['employer_detail'];
        $data['haveSubordinate'] = $haveSubordinate;
        $data['load_view'] = 1;
        $data['type'] = "self";
        $data['level'] = 0;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main',
            'v1/app/css/globals'
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
            ->view('courses/my_courses')
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
                $lessonStatus = 'completed';
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
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "my_course";
        $data['subordinate_sid'] = 0;
        $data['level'] = 0;
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
    public function getCourseByLanguage(int $sid, string $language)
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
                $lessonStatus = 'completed';
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
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "my_course";
        $data['subordinate_sid'] = 0;
        $data['language'] = $language;
        $data['level'] = 0;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/plugins/ms_uploader/main',
            'v1/plugins/ms_modal/main',
            'v1/plugins/ms_recorder/main',
            'v1/lms/preview_assign',
        ];
        //
        if ($courseInfo['course_type'] == "scorm") {
            $viewName = "scorm_course";
            $isManualAssign = $this->course_model->checkCourseIsManualAssigned($sid, $employeeId);
            //
            if ($isManualAssign) {
                $courseLanguageInfo = $this->course_model->getManualAssignCourseLanguageInfo($sid, $language);
            } else {
                $courseLanguageInfo = $this->course_model->getCourseLanguageInfo($sid, $language);
            }
            //
            $scormInfo = json_decode($courseLanguageInfo['Imsmanifist_json'], true);
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
            // add the employee id and name
            if (!$data["CMIObject"] || array_key_exists("cmi.core.student_id", $data['CMIObject'])) {
                $data['CMIObject']["cmi.core.student_id"] = $data['CMIObject']["cmi.core.student_id"] ? $data['CMIObject']["cmi.core.student_id"] : $employeeId;
                $data['CMIObject']["cmi.core.student_name"] = $data['CMIObject']["cmi.core.student_name"] ? $data['CMIObject']["cmi.core.student_name"] : getEmployeeOnlyNameBySID($employeeId);
            }
        } elseif ($courseInfo['course_type'] == "manual") {
            $viewName = "manual_course";
            //
            $data['questions'] = $questions;
            if ($lessonStatus = 'completed') {
                $data['viewMode'] = "preview";
            } else {
                $data['viewMode'] = "attempt";
            }
            //
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
    public function viewCertificate(int $courseId, int $studentId, string $type)
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
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['student_sid'] = $studentId;
        $data['type'] = $type;
        $data['employee'] = $session['employer_detail'];
        $data['employeeName'] = getEmployeeOnlyNameBySID($studentId);
        $data['company_info'] = $session['company_detail'];
        $data['companyName'] = getCompanyNameBySid($companyId);
        $data['AHRLogo'] = base_url('assets/images/lms_certificate_logo.png');
        $data['AHRStudentID'] = 'AHR-' . $studentId;
        $data['load_view'] = 1;
        $data['level'] = 0;
        $data['courseInfo'] = $this->course_model->getCourseCertificateInfo($courseId);
        $EmployeeCourseProgress = $this->course_model->getEmployeeCourseProgressInfo($courseId, $studentId, $companyId);
        $studentInfo = $this->course_model->getStudentInfo($studentId);
        //
        $data['completedOn'] = convertDateTimeToTimeZone(
            $EmployeeCourseProgress['updated_at'],
            DB_DATE_WITH_TIME,
            DATE
        );
        //
        $data['studentSSN'] = substr($studentInfo['ssn'], -4);
        //
        $data['studentDOB'] = $studentInfo['dob'] ?
            convertDateTimeToTimeZone(
                $studentInfo['dob'],
                DB_DATE,
                DATE
            ) : "";
        $data["studentInfo"] = $studentInfo;
        $data["EmployeeCourseProgress"] = $EmployeeCourseProgress;
        //
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
            ->view('main/header_2022', $data)
            ->view('courses/certificate')
            ->view('main/footer');
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
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['viewMode'] = "preview";
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['level'] = 0;
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
    public function subordinatesReport($departments = "all", $teams = "all", $employees = "all", $courses = "all")
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
        $subordinateInfo['courses'] = $this->course_model->getActiveCompanyCourses($companyId);
        //
        $uniqueKey = '';
        $haveSubordinate = 'no';
        //
        if (!empty($subordinateInfo['employees'])) {
            //
            $subordinateInfo['total_course'] = 0;
            $subordinateInfo['expire_soon'] = 0;
            $subordinateInfo['expired'] = 0;
            $subordinateInfo['started'] = 0;
            $subordinateInfo['completed'] = 0;
            $subordinateInfo['ready_to_start'] = 0;
            //
            unset($subordinateInfo['employees'][$employeeId]);
            //
            foreach ($subordinateInfo['employees'] as $key => $subordinateEmployee) {
                //
                $teamId = $subordinateEmployee['team_sid'];
                $subordinateInfo['employees'][$key]['department_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                $subordinateInfo['employees'][$key]['team_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
                //
                if (isset($subordinateEmployee['coursesInfo'])) {
                    //
                    if (isset($_GET['courses']) && $_GET['courses'][0] != "all") {
                        $filterCourses = getCoursesInfo(implode(',', $_GET['courses']), $key);
                        //
                        $subordinateInfo['employees'][$key]['coursesInfo']['total_course'] = $filterCourses['total_course'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['expire_soon'] = $filterCourses['expire_soon'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['expired'] = $filterCourses['expired'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['started'] = $filterCourses['started'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['completed'] = $filterCourses['completed'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['ready_to_start'] = $filterCourses['ready_to_start'];
                    }
                    //
                    $subordinateInfo['total_course'] = $subordinateInfo['total_course'] + $subordinateEmployee['coursesInfo']['total_course'];
                    $subordinateInfo['expire_soon'] = $subordinateInfo['expire_soon'] + $subordinateEmployee['coursesInfo']['expire_soon'];
                    $subordinateInfo['expired'] = $subordinateInfo['expired'] + $subordinateEmployee['coursesInfo']['expired'];
                    $subordinateInfo['started'] = $subordinateInfo['started'] + $subordinateEmployee['coursesInfo']['started'];
                    $subordinateInfo['completed'] = $subordinateInfo['completed'] + $subordinateEmployee['coursesInfo']['completed'];
                    $subordinateInfo['ready_to_start'] = $subordinateInfo['ready_to_start'] + $subordinateEmployee['coursesInfo']['ready_to_start'];
                }
            }
            //
            // Enter subordinate json into DB
            $haveSubordinate = 'yes';
            $uniqueKey = $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        }
        //
        $filters = [
            "departments" => $departments,
            "teams" => $teams,
            "employees" => $employees,
            "courses" => $courses
        ];
        //
        if ($this->input->is_ajax_request()) {
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
            if (isset($_GET['courses'])) {
                $filters["courses"] = $_GET['courses'];
            }
            //
            $selectedEmployeesList = [];
            $selectedEmployeesIds = [];
            //
            if ($filters["departments"][0] == 'all' && $filters["teams"][0] == 'all' && $filters["employees"][0] == 'all') {
                $selectedEmployeesList = $subordinateInfo['employees'];
            } else {
                foreach ($subordinateInfo['employees'] as $subordinateEmployee) {
                    //
                    if ($subordinateEmployee["job_title_sid"] > 0) {
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
                //
                // $subordinateInfo['total_course'];
                // $subordinateInfo['expire_soon'];
                // $subordinateInfo['expired'];
                // $subordinateInfo['started'];
                // $subordinateInfo['completed'];
                // $subordinateInfo['ready_to_start'];
            }
            //
            header('Content-Type: application/json');
            echo json_encode([
                "employees" => $selectedEmployeesList
            ]);
            exit(0);
        }
        //
        $data['title'] = "My Courses :: " . STORE_NAME;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data["companyName"] = $session['company_detail']['CompanyName'];
        $data["companyLogo"] = 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo'];
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['subordinateInfo'] = $subordinateInfo;
        $data['title'] = "Employee(s) Report";
        $data['filters'] = $filters;
        $data['level'] = 0;
        // load CSS
        //
        $data['Page_CSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/subordinate_reporting'
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
            ->view('courses/subordinate_report')
            ->view('main/footer');
    }

    public function subordinateDashboard($type, $subordinateId)
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
        if ($type == "plus") {
            $data['title'] = "Employee Courses | " . STORE_NAME;
        } else {
            $data['title'] = "Subordinate Courses | " . STORE_NAME;
        }
        //
        $data['companyId'] = $companyId;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = $subordinateId;
        $data["companyName"] = $session['company_detail']['CompanyName'];
        $data["companyLogo"] = 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo'];
        $data['subordinateName'] = getUserNameBySID($subordinateId);
        $data['subordinateInfo'] = get_employee_profile_info($subordinateId);
        $data['viewMode'] = "subordinate";
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['page'] = "subordinate_courses";
        $data['type'] = $type;
        $data['level'] = 0;
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/subordinate_employee_dashboard'
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
            ->view('courses/subordinate_dashboard')
            ->view('main/footer');
    }

    public function subordinateCourses($type, $subordinateId)
    {
        return redirect("lms/subordinate/dashboard/{$subordinateId}");
        //
        if ($type == "plus") {
            redirect('lms/subordinate/dashboard/' . $subordinateId, 'refresh');
        } else {
            redirect('lms/employee/courses/dashboard/' . $subordinateId, 'refresh');
        }
        //
        $data = [];
        //
        $session = $this->session->userdata('logged_in');
        //
        $companyId = $session['company_detail']['sid'];
        $employeeId = $session['employer_detail']['sid'];
        //
        $data['search'] = '';
        //
        if (isset($_GET) && $_GET['type']) {
            $data['search'] = $_GET['type'];
        }
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
        } else if ($session['employer_detail']['access_level_plus']) {
            $haveSubordinate = 'yes';
        }
        //
        if ($type == "plus") {
            $data['title'] = "Employee Courses | " . STORE_NAME;
        } else {
            $data['title'] = "Subordinate Courses | " . STORE_NAME;
        }
        //
        $data['companyId'] = $companyId;
        $data['session'] = $session;
        $data['employer_sid'] = $employeeId;
        $data['subordinate_sid'] = $subordinateId;
        $data['subordinateName'] = getUserNameBySID($subordinateId);
        $data['viewMode'] = "subordinate";
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['page'] = "subordinate_courses";
        $data['type'] = $type;
        $data['level'] = 0;
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
            ->view('courses/my_courses')
            ->view('main/footer');
    }

    public function previewSubordinateCourse($courseId, $subordinateId, $reviewAs)
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
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        }
        //
        $courseInfo = $this->course_model->getCourseInfo($courseId);
        //
        $questions = $courseInfo['course_questions'];
        //
        if ($reviewAs == "plus") {
            $data['title'] = "Employee Course Preview | " . STORE_NAME;
        } else {
            $data['title'] = "Subordinate Course Preview | " . STORE_NAME;
        }
        //
        $data["reviewAs"] = $reviewAs;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $courseId;
        $data['courseInfo'] = $courseInfo;
        // $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "subordinate_course";
        $data['subordinate_sid'] = $subordinateId;
        $data['level'] = 0;
        $data['search'] = "";
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

    public function previewSubordinateCourseByLanguage($courseId, $subordinateId, $reviewAs, $language)
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
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        }
        //
        $courseInfo = $this->course_model->getCourseInfo($courseId);
        //
        $questions = $courseInfo['course_questions'];
        //
        if ($reviewAs == "plus") {
            $data['title'] = "Employee Course Preview | " . STORE_NAME;
        } else {
            $data['title'] = "Subordinate Course Preview | " . STORE_NAME;
        }
        //
        $data["reviewAs"] = $reviewAs;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $courseId;
        $data['courseInfo'] = $courseInfo;
        // $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "subordinate_course";
        $data['subordinate_sid'] = $subordinateId;
        $data['language'] = $language;
        $data['level'] = 0;
        $data['search'] = "";
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
            //
            $isManualAssign = $this->course_model->checkCourseIsManualAssigned($courseId, $subordinateId);
            //
            if ($isManualAssign) {
                $courseLanguageInfo = $this->course_model->getManualAssignCourseLanguageInfo($courseId, $language);
            } else {
                $courseLanguageInfo = $this->course_model->getCourseLanguageInfo($courseId, $language);
            }
            $scormInfo = json_decode($courseLanguageInfo['Imsmanifist_json'], true);
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

    public function companyReport($departments = "all", $courses = "all", $employees = "all")
    {
        if ($this->session->userdata('logged_in')) {
            // Added on: 28-08-2023
            $session = $this->session->userdata('logged_in');
            $companyId = $session['company_detail']['sid'];
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
            $data['companyId'] = $companyId;
            $data['employer_sid'] = $security_sid;
            $data['company_sid'] = $session['company_detail']['sid'];
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            $data['employer_detail'] = $data['session']['employer_detail'];
            $data['company_detail'] = $data['session']['company_detail'];
            $data['level'] = 0;
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
                    $fetchDepartment = $filters["departments"] . ',' . implode(",", $departmentIds);
                    $fetchDepartmentEmployees = $this->course_model->getAllDepartmentEmployees($data['company_sid'], $filters["departments"]);

                    $fetchEmployees = $filters["employees"] . ',' . implode(",", $fetchDepartmentEmployees);
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
                    "total_assigned_courses" => 0,
                    "total_completed_courses" => 0,
                    "total_inprogress_courses" => 0,
                    "total_rts_courses" => 0,
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
                $jobTitleIds[] = -1;
                //
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $data['company_sid']);
                //
                foreach ($companyDepartments as $department) {
                    $departments[$department['sid']] = $department;
                    $departments[$department['sid']]['employee_have_courses'] = 0;
                    $departments[$department['sid']]['employee_not_have_courses'] = 0;
                    $departments[$department['sid']]['total_employees'] = 0;
                    $departments[$department['sid']]['pending_courses'] = 0;
                    $departments[$department['sid']]['completed_courses'] = 0;
                    $departments[$department['sid']]['total_courses'] = 0;
                    $departments[$department['sid']]['readyToStart_courses'] = 0;
                    $departments[$department['sid']]['inProgress_courses'] = 0;
                }
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $departments[0]['sid'] = 0;
                    $departments[0]['name'] = "Other (Employees who are not assigned to any department)";
                    $departments[0]['employee_have_courses'] = 0;
                    $departments[0]['employee_not_have_courses'] = 0;
                    $departments[0]['total_employees'] = 0;
                    $departments[0]['pending_courses'] = 0;
                    $departments[0]['completed_courses'] = 0;
                    $departments[0]['total_courses'] = 0;
                    $departments[0]['readyToStart_courses'] = 0;
                    $departments[0]['inProgress_courses'] = 0;
                }
                //
                $companyReport["total_employees"] = count($companyEmployeesList);
                //
                foreach ($companyEmployeesList as $ekey => $employee) {
                    //
                    if ($session['employer_detail']['sid'] == $employee['sid']) {
                        unset($companyEmployeesList[$ekey]);
                    } else {
                        if ($fetchEmployees == "all" || in_array($employee['sid'], explode(",", $fetchEmployees))) {

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
                            if (!empty($employee['lms_job_title'])) {
                                $employeesList[$employee['sid']]["courses_sid"]  = $jobRoleCourses[$employee['job_title_sid']];
                                //
                                $job_title_sid = !empty($employee['job_title_sid']) ? $employee['job_title_sid'] : -1;
                                //
                                $employeesList[$employee['sid']]["courses_statistics"] = $this->course_model->checkEmployeeCoursesReport(
                                    $data['company_sid'],
                                    $employee['sid'],
                                    $jobRoleCourses[$job_title_sid]
                                );
                                //
                                $companyReport["employee_have_courses"]++;
                                //
                                if ($employeesList[$employee['sid']]["courses_statistics"]) {
                                    //
                                    $companyReport["courses_report"]['total_assigned_courses'] = $companyReport["courses_report"]['total_assigned_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['courseCount'];
                                    $companyReport["courses_report"]['total_completed_courses'] = $companyReport["courses_report"]['total_completed_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['completedCount'];
                                    $companyReport["courses_report"]['total_inprogress_courses'] = $companyReport["courses_report"]['total_inprogress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                    $companyReport["courses_report"]['total_rts_courses'] = $companyReport["courses_report"]['total_rts_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
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
                                }
                                //
                            } else if (checkAnyManualCourseAssigned($employee['sid'])) {
                                $employeesList[$employee['sid']]["courses_statistics"] = $this->course_model->checkEmployeeManualCoursesReport(
                                    $data['company_sid'],
                                    $employee['sid'],
                                );
                                //
                                $companyReport["employee_have_courses"]++;
                                //
                                if ($employeesList[$employee['sid']]["courses_statistics"]) {
                                    //
                                    $companyReport["courses_report"]['total_assigned_courses'] = $companyReport["courses_report"]['total_assigned_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['courseCount'];
                                    $companyReport["courses_report"]['total_completed_courses'] = $companyReport["courses_report"]['total_completed_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['completedCount'];
                                    $companyReport["courses_report"]['total_inprogress_courses'] = $companyReport["courses_report"]['total_inprogress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                    $companyReport["courses_report"]['total_rts_courses'] = $companyReport["courses_report"]['total_rts_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
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
                                }
                                //
                            } else {
                                $employeesList[$employee['sid']]['job_title_sid'] = 0;
                                $employeesList[$employee['sid']]["courses_sid"]  = 0;
                                $employeesList[$employee['sid']]["courses_statistics"] = [
                                    "completedCount" => 0,
                                    "inProgressCount" => 0,
                                    "pendingCount" => 0,
                                    "readyToStart" => 0,
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
                                        $departments[$employeeDepartment]['readyToStart_courses'] = $departments[$employeeDepartment]['readyToStart_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
                                        $departments[$employeeDepartment]['inProgress_courses'] = $departments[$employeeDepartment]['inProgress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                        //
                                    }
                                    //
                                    $departments[$employeeDepartment]['employees'][] = $employee['sid'];
                                }
                            }
                            //
                        }
                    }
                }
                //
                $companyReport["departments_report"] = $departments;
                $companyReport["EmployeeList"] = $employeesList;
                $companyReport["CoursesList"] = $coursesList;
            }
            //
            $data["companyReport"] = $companyReport;
            $data["filters"] = $filters;
            $data["filterData"] = $filterData;
            $companyReport["departments_report"] = $departments;
            //
            if ($this->input->method() === 'post') {
                if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $session['company_detail']['CompanyName'] . '_LMS_company_report_' . date('Y_m_d-H:i:s') . '.csv');
                    $output = fopen('php://output', 'w');
                    //
                    fputcsv($output, array('Company Name', $session['company_detail']['CompanyName'], '', '', '', ''));
                    fputcsv($output, array('Exported By', getUserNameBySID($security_sid), '', '', '', ''));
                    fputcsv($output, array('Export Date', date(DATE_WITH_TIME, strtotime('now')), '', '', '', ''));
                    //
                    fputcsv($output, array('', '', '', '', '', ''));
                    fputcsv($output, array('', '', '', '', '', ''));
                    //
                    fputcsv($output, array('Number of Employees with Assigned Courses', $companyReport['employee_have_courses'], '', '', '', ''));
                    fputcsv($output, array('Number of Employees Without Assigned Courses', $companyReport['employee_not_have_courses'], '', '', '', ''));
                    //
                    // fputcsv($output, array('Expired courses', $companyReport['courses_report']['expired'], '', '', '', ''));
                    // fputcsv($output, array('Employees who have Started but not Completed a Course', $companyReport['courses_report']['started'], '', '', '', ''));
                    // fputcsv($output, array('Coming courses', $companyReport['courses_report']['coming'], '', '', '', ''));
                    //
                    fputcsv($output, array('', '', '', '', '', ''));
                    fputcsv($output, array('', '', '', '', '', ''));
                    //
                    fputcsv($output, array('Total Assigned Course(s)', $companyReport['courses_report']['total_assigned_courses'], 'Percentage', '', '', ''));
                    fputcsv($output, array('Total Completed Course(s)', $companyReport['courses_report']['total_completed_courses'], round(($companyReport['courses_report']['total_completed_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 0) . '%', '', '', ''));
                    fputcsv($output, array('Total Inprogress Course(s)', $companyReport['courses_report']['total_inprogress_courses'], round(($companyReport['courses_report']['total_inprogress_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 0) . '%', '', '', ''));
                    fputcsv($output, array('Total Ready to Start Course(s)', $companyReport['courses_report']['total_rts_courses'], round(($companyReport['courses_report']['total_rts_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 0) . '%', '', '', ''));
                    //
                    fputcsv($output, array('', '', '', '', '', ''));
                    fputcsv($output, array('', '', '', '', '', ''));
                    //
                    $cols = array();
                    $cols[] = 'Employee Name';
                    $cols[] = 'Department';
                    $cols[] = 'Assign Course(s)';
                    $cols[] = 'Inprogress Course(s)';
                    $cols[] = 'Ready To Start Course(s)';
                    $cols[] = 'Completed Course(s)';
                    $cols[] = 'Completion Percentage';
                    //
                    fputcsv($output, $cols);
                    //
                    foreach ($companyReport['departments_report'] as $department) {
                        if (!empty($department["employees"])) {
                            foreach ($department['employees'] as $employee) {
                                $assignCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['courseCount'];
                                $inProgressCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['inProgressCount'];
                                $readyTOStartCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['readyToStart'];
                                $completedCourses = $companyReport["EmployeeList"][$employee]["courses_statistics"]['completedCount'];
                                $completedCoursesPercentage = $companyReport["EmployeeList"][$employee]["courses_statistics"]['percentage'];
                                //
                                fputcsv($output, array(
                                    $companyReport["EmployeeList"][$employee]["full_name"],
                                    $department["name"],
                                    $assignCourses,
                                    $inProgressCourses,
                                    $readyTOStartCourses,
                                    $completedCourses,
                                    $completedCoursesPercentage . " %"
                                ));
                            }
                        }
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

    public function companyCourses()
    {
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
            $data['title'] = "LMS Company Courses";
            $data['employer_sid'] = $security_sid;
            $data['company_sid'] = $session['company_detail']['sid'];
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            $data['employer_detail'] = $data['session']['employer_detail'];
            $data['company_detail'] = $data['session']['company_detail'];
            $data['level'] = 0;
            //
            // load JS
            $data['PageScripts'] = [
                'js/app_helper',
                'v1/plugins/ms_uploader/main',
                'v1/plugins/ms_modal/main',
                'v1/plugins/ms_recorder/main',
                'v1/common',
                'v1/lms/company_courses'
            ];
            $data['apiURL'] = getCreds('AHR')->API_BROWSER_URL;
            // get access token
            $data['apiAccessToken'] = getApiAccessToken(
                $data['company_sid'],
                $data['employer_sid']
            );
            //
            $this->load->view('main/header', $data);
            $this->load->view('courses/company_courses');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function emailReminder($type)
    {
        //
        $res = [
            'Status' => false,
            'Message' => 'Invalid Request.'
        ];
        //
        if (
            !$this->input->is_ajax_request() ||
            !$this->input->post(null, true) ||
            $this->input->method() != 'post'
        ) {
            return SendResponse(400, $res);
        }
        //
        $post = $this->input->post(null, true);

        // extract employee id
        $employeeIds = array_column($post["employeeList"], "employee_sid");
        //
        if (!$employeeIds) {
            $res["Message"] = "No employees selected";
            return SendResponse(400, $res);
        }
        // load model
        $this->load->model("cron_email_model");
        // send reminder emails
        $response = $this
            ->cron_email_model
            ->sendCourseReminderEmailsToSpecificEmployees(
                $employeeIds,
                $this->session->userdata('logged_in')["company_detail"]["sid"],
                true
            );

        if ($response["errors"]) {
            $res["Message"] = "Something went wrong!";
            return SendResponse(400, $res);
        }

        //
        $res['Status'] = true;
        $res['Message'] = 'You have successfully sent an email reminder to selected employees.';
        //
        res($res);
    }

    public function deletePreviousAllLanguages($courseId)
    {
        //
        $this->course_model->deletePreviousAllLanguagesById($courseId);
        //
        return SendResponse(
            200,
            [
                "msg" => "Deleted."
            ]
        );
    }

    public function deletePreviousLanguages($courseId, $language)
    {
        //
        $this->course_model->deletePreviousAllLanguagesByIdAndLanguage($courseId, $language);
        //
        return SendResponse(
            200,
            [
                "msg" => "Deleted."
            ]
        );
    }

    public function importCourseCSV()
    {
        if ($this->session->userdata('logged_in')) {
            //
            $data = [];
            // check and set user session
            $data['session'] = checkUserSession();
            $data['title'] = "Import Courses CSV File";
            // set
            $data['loggedInPerson'] = $data['session']['employer_detail'];
            $data['companyId'] = $data['session']['company_detail']['sid'];
            $data['employerId'] = $data['session']['employer_detail']['sid'];
            $data['level'] = 0;
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            // get the security details
            $data['security_details'] = db_get_access_level_details(
                $data['session']['employer_detail']['sid'],
                null,
                $data['session']
            );
            //
            $data['employerData'] = $this->course_model->getEmployerDetail($data['employerId']);
            //
            $data['PageCSS'] = [
                'v1/plugins/ms_modal/main.min',
                'v1/plugins/alertifyjs/css/alertify.min',
                'mFileUploader/index'
            ];
            // load JS
            $data['PageScripts'] = [
                'v1/plugins/ms_modal/main.min',
                'lodash/loadash.min',
                'v1/plugins/alertifyjs/alertify.min',
                'mFileUploader/index',
                'v1/lms/import_courses_csv'
            ];

            $this->load->view('main/header', $data);
            $this->load->view('courses/import_courses_csv');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function getMyHistory()
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
        $history = $this->course_model->getEmployeeCoursesHistory($companyId, $employeeId);
        //
        $historyOverView = [];
        //
        if ($history) {
            foreach ($history as $item) {
                $historyOverView[] = [
                    'name' => $item['course_title'],
                    'value' => count($item['history'])
                ];
            }
            //
            $data['categories'] = array_column($historyOverView, 'name');
            $data['categoriesValues'] = array_column($historyOverView, 'value');
        }
        //
        $data['title'] = "My Courses History :: " . STORE_NAME;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['viewMode'] = "subordinate";
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['page'] = "my_courses_history";
        $data['page_title'] = "My Courses History";
        $data['level'] = 0;
        $data['history'] = $history;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/courses_history'
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
            ->view('courses/courses_history')
            ->view('main/footer');
    }

    /**
     *
     */
    public function previewCourseHistory(int $sid)
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
        $courseInfo = $this->course_model->getCourseHistoryInfo($sid);
        //
        $lessonStatus = '';
        //
        if ($courseInfo['lesson_status'] == "completed") {
            //
            $lessonStatus = 'completed';
        } else {
            // 
            $lessonStatus = 'started';
        }
        //
        $courseInfo['course_title'] = $this->course_model->getCoursesHistoryTitle($courseInfo['course_sid']);
        $questions = $courseInfo['course_questions'];
        //
        $data['title'] = "My Course History :: " . STORE_NAME;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "my_course";
        $data['subordinate_sid'] = 0;
        $data['level'] = 0;
        $data['viewMode'] = "preview_my_history";
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/courses_history',
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
            $data['CMIObject'] = json_decode($courseInfo['course_answer_json'], true);
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
        //
        $this->load
            ->view('main/header_2022', $data)
            ->view('courses/' . $viewName)
            ->view('main/footer');
    }

    public function getEmployeeHistory($reviewAs, $subordinateId)
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
        $history = $this->course_model->getEmployeeCoursesHistory($companyId, $subordinateId);
        //
        $historyOverView = [];
        //
        if ($history) {
            foreach ($history as $item) {
                $historyOverView[] = [
                    'name' => $item['course_title'],
                    'value' => count($item['history'])
                ];
            }
            //
            $data['categories'] = array_column($historyOverView, 'name');
            $data['categoriesValues'] = array_column($historyOverView, 'value');
        }
        //
        $data['title'] = "Subordinate Courses History :: " . STORE_NAME;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data["companyName"] = $session['company_detail']['CompanyName'];
        $data["companyLogo"] = 'https://automotohrattachments.s3.amazonaws.com/' . $session['company_detail']['Logo'];
        $data['employer_sid'] = $employeeId;
        $data['viewMode'] = "subordinate";
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['uniqueKey'] = $uniqueKey;
        $data['haveSubordinate'] = $haveSubordinate;
        $data['subordinateId'] = $subordinateId;
        $data['subordinateInfo'] = get_employee_profile_info($subordinateId);
        $data['page'] = "employee_courses_history";
        $data['page_title'] = $reviewAs == "non_plus" ? "Team Course History" : "Employee Course History";

        $data['level'] = 0;
        $data['reviewAs'] = $reviewAs;
        $data['history'] = $history;
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/courses_history'
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
            ->view('courses/courses_history')
            ->view('main/footer');
    }

    public function previewSubordinateCourseHistory($reviewAs, $sid, $subordinateId)
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
        if (!empty($subordinateInfo['employees'])) {
            // Enter subordinate json into DB
            $this->course_model->insertEmployeeSubordinate($companyId, $employeeId, $subordinateInfo);
        }
        //
        $courseInfo = $this->course_model->getCourseHistoryInfo($sid);
        //
        $questions = $courseInfo['course_questions'];
        //
        if ($reviewAs == "plus") {
            $data['title'] = "Employee Course Preview | " . STORE_NAME;
        } else {
            $data['title'] = "Subordinate Course Preview | " . STORE_NAME;
        }
        //
        $courseInfo['course_title'] = $this->course_model->getCoursesHistoryTitle($courseInfo['course_sid']);
        //
        $data["reviewAs"] = $reviewAs;
        $data['session'] = $session;
        $data['companyId'] = $companyId;
        $data['employer_sid'] = $employeeId;
        $data['employee'] = $session['employer_detail'];
        $data['load_view'] = 1;
        $data['course_sid'] = $sid;
        $data['courseInfo'] = $courseInfo;
        // $data['lessonStatus'] = $lessonStatus;
        $data['page'] = "subordinate_course";
        $data['subordinate_sid'] = $subordinateId;
        $data['level'] = 0;
        $data['search'] = "";
        $data['viewMode'] = "preview_subordinate_history";
        //
        // load CSS
        $data['PageCSS'] = [
            '2022/css/main'
        ];
        // load JS
        $data['PageScripts'] = [
            'js/app_helper',
            'v1/common',
            'v1/lms/courses_history',
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
            $data['CMIObject'] = json_decode($courseInfo['course_answer_json'], true);
        } elseif ($courseInfo['course_type'] == "manual") {
            $viewName = "manual_course";
            //
            $data['questions'] = $questions;
            //
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
     * Handle all ajax requests
     * Created on: 16-08-2019
     *
     * @accepts POST
     *
     * @uses resp
     *
     * @return JSON
     */
    function handler()
    {
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        // Set default response array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request made.';
        // Check for a valid AJAX request
        if (!$this->input->is_ajax_request()) exit(0);
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        switch ($formpost['action']) {
            case 'add_courses':
                set_time_limit(0);
                // Default array

                $failCount = $insertCount = $existCount = 0;
                $updatedRows = [];
                $failRows = [];
                //
                if ($formpost['courses']) {
                    foreach ($formpost['courses'] as $key => $course) {

                        if (!$course["course_sid"] || $course["course_sid"] == 0) {
                            $failCount++;
                            $failRows[] = $key;
                            continue;
                        }
                        //
                        $employeeId = checkEmployeeExistInCompany($course, $data['companyId']);
                        //
                        if ($employeeId == 0) {
                            $failCount++;
                            $failRows[] = $key;
                        } else {
                            //
                            if ($employeeId != 0) {
                                //
                                $courseId = $course["course_sid"];
                                //
                                if ($courseId > 0) {
                                    if (
                                        !$this->db
                                            ->where([
                                                'course_sid' => $courseId,
                                                'company_sid' => $data['companyId'],
                                                'employee_sid' => $employeeId
                                            ])
                                            ->count_all_results('lms_employee_course')
                                    ) {
                                        //
                                        $dataToInsert = [];
                                        $dataToInsert['course_sid'] = $courseId;
                                        $dataToInsert['company_sid'] = $data['companyId'];
                                        $dataToInsert['employee_sid'] = $employeeId;
                                        $dataToInsert['lesson_status'] = $course['progress'];
                                        $dataToInsert['course_status'] = $course['status'];
                                        $dataToInsert['course_type'] = $course['type'];
                                        $dataToInsert['course_taken_count'] = $course['count'];
                                        $dataToInsert['created_at'] = formatDateToDB($course['start_date'], SITE_DATE, DB_DATE);
                                        $dataToInsert['updated_at'] = formatDateToDB($course['end_date'], SITE_DATE, DB_DATE);
                                        //
                                        $this->course_model->insertEmployeeCourseInfo($dataToInsert);
                                        //
                                        $insertCount++;
                                    } else {
                                        $updatedRows[] = $key;
                                        $existCount++;
                                    }
                                } else {
                                    $failCount++;
                                    $failRows[] = $key;
                                }
                            }
                        }
                    }
                }
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Inserted'] = $insertCount;
                $resp['Existed'] = $existCount;
                $resp['Failed'] = $failCount;
                $resp['duplicateRows'] = $updatedRows;
                $resp['FailedRows'] = $failRows;
                //
                $this->resp($resp);
                break;
        }
        //
        $this->resp($resp);
    }



    /**
     * Send JSON response
     *
     * @param $responseArray Array
     *
     * @return JSON
     */
    function resp($responseArray)
    {
        header('Content-type: application/json');
        echo json_encode($responseArray);
        exit(0);
    }

    public function companyCoursesList()
    {
        return SendResponse(
            200,
            [
                "company_courses" => $this
                    ->course_model
                    ->getActiveCourseList(
                        checkUserSession()["company_detail"]["sid"],
                        "0"
                    )
            ]
        );
    }

    public function moveRecursiveCoursesIntoHistory()
    {
        //
        $this->course_model->moveCourseIntoHistoryAndUpdate();
        //
        _e("Process completed", true);
    }

    public function exportCourseCSV()
    {
        if ($this->session->userdata('logged_in')) {
            //
            $data = [];
            // check and set user session
            $data['session'] = checkUserSession();
            $data['title'] = "Export Courses CSV";
            // set
            $data['loggedInPerson'] = $data['session']['employer_detail'];
            $data['companyId'] = $data['session']['company_detail']['sid'];
            $data['employerId'] = $data['session']['employer_detail']['sid'];
            $data['level'] = 0;
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            // get the security details
            $data['security_details'] = db_get_access_level_details(
                $data['session']['employer_detail']['sid'],
                null,
                $data['session']
            );
            //sudo
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $data['employerData'] = $this->course_model->getEmployerDetail($data['employerId']);
                $data['companyCourses'] = $this->course_model->getActiveCompanyCourses($data['companyId']);
                //
                $this->load->view('main/header', $data);
                $this->load->view('courses/export_courses_csv');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'export_courses':
                        $exportRows = '';
                        $checked_boxes = explode(',', $_POST['columns']);
                        //
                        $selectedCourses = $this->course_model->getSelectedCourses($_POST['courses'], $data['companyId']);
                        $companyEmployeesList = $this->course_model->getCompanyActiveEmployeesWithCourses($data['companyId']);
                        //
                        if ($companyEmployeesList && $selectedCourses) {
                            //
                            foreach ($companyEmployeesList as $employee) {
                                //
                                foreach ($selectedCourses as $course) {
                                    //
                                    $courseStatus = $this->course_model->getEmployeeCourseStatus($course['sid'], $employee['sid']);
                                    //
                                    $exportRow = '';
                                    //
                                    foreach ($checked_boxes as $key => $item) {
                                        if ($item == "employee_id") {
                                            $exportRow .= $employee['sid'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "employee_no") {
                                            $exportRow .= $employee['employee_number'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "employee_ssn") {
                                            $exportRow .= $employee['ssn'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "employee_email") {
                                            $exportRow .= $employee['email'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "employee_PhoneNumber") {
                                            $exportRow .= $employee['PhoneNumber'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "first_name") {
                                            $exportRow .= $employee['first_name'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "last_name") {
                                            $exportRow .= $employee['last_name'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "access_level") {
                                            $exportRow .= $employee['access_level'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "job_title") {
                                            $exportRow .= $employee['job_title'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_title") {
                                            $exportRow .= $course['course_title'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "lesson_status") {
                                            $exportRow .= $courseStatus['lesson_status'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_status") {
                                            $exportRow .= $courseStatus['course_status'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_type") {
                                            $exportRow .= $course['course_type'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_taken_count") {
                                            $exportRow .= $courseStatus['course_taken_count'] . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_started_date") {
                                            $startDate = $courseStatus['created_at'] ? formatDateToDB($courseStatus['created_at'], DB_DATE_WITH_TIME, SITE_DATE) : "";
                                            $exportRow .= $startDate . ',';
                                            substr($exportRow, 0, -1);
                                        } else if ($item == "course_completion_date") {
                                            $completionDate = $courseStatus['completed_at'] ? formatDateToDB($courseStatus['completed_at'], DB_DATE_WITH_TIME, SITE_DATE) : "";
                                            $exportRow .= $completionDate . ',';
                                            substr($exportRow, 0, -1);
                                        }
                                    }
                                    //
                                    $exportRow .= 'current,';
                                    substr($exportRow, 0, -1);
                                    //
                                    $exportRows .= $exportRow . PHP_EOL;
                                    //
                                    // check course history
                                    $courseHistory = $this->course_model->getEmployeeCourseHistory($course['sid'], $employee['sid']);
                                    // 
                                    if ($courseHistory) {
                                        foreach ($courseHistory as $history) {
                                            //
                                            $exportHistoryRow = '';
                                            //
                                            foreach ($checked_boxes as $key => $item) {
                                                if ($item == "employee_id") {
                                                    $exportHistoryRow .= $employee['sid'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "employee_no") {
                                                    $exportHistoryRow .= $employee['employee_number'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "employee_ssn") {
                                                    $exportHistoryRow .= $employee['ssn'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "employee_email") {
                                                    $exportHistoryRow .= $employee['email'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "employee_PhoneNumber") {
                                                    $exportHistoryRow .= $employee['PhoneNumber'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "first_name") {
                                                    $exportHistoryRow .= $employee['first_name'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "last_name") {
                                                    $exportHistoryRow .= $employee['last_name'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "access_level") {
                                                    $exportHistoryRow .= $employee['access_level'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "job_title") {
                                                    $exportHistoryRow .= $employee['job_title'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_title") {
                                                    $exportHistoryRow .= $course['course_title'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "lesson_status") {
                                                    $exportHistoryRow .= $history['lesson_status'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_status") {
                                                    $exportHistoryRow .= $history['course_status'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_type") {
                                                    $exportHistoryRow .= $course['course_type'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_taken_count") {
                                                    $exportHistoryRow .= $history['course_taken_count'] . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_started_date") {
                                                    $startDate = $history['created_at'] ? formatDateToDB($history['created_at'], DB_DATE_WITH_TIME, SITE_DATE) : "";
                                                    $exportHistoryRow .= $startDate . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                } else if ($item == "course_completion_date") {
                                                    $completionDate = $history['completed_at'] ? formatDateToDB($history['completed_at'], DB_DATE_WITH_TIME, SITE_DATE) : "";
                                                    $exportHistoryRow .= $completionDate . ',';
                                                    substr($exportHistoryRow, 0, -1);
                                                }
                                            }
                                            //
                                            $exportHistoryRow .= 'history,';
                                            substr($exportHistoryRow, 0, -1);
                                            //
                                            $exportRows .= $exportHistoryRow . PHP_EOL;
                                        }
                                    }
                                }
                            }
                        }
                        //
                        if (!empty($exportRows)) {
                            //
                            $header = [];
                            //
                            if (count($checked_boxes) > 0) {
                                foreach ($checked_boxes as $key => $item) {
                                    if ($item == "employee_id") {
                                        $header[$key] = "Employee ID";
                                    } else if ($item == "employee_no") {
                                        $header[$key] = "Employee Number";
                                    } else if ($item == "employee_ssn") {
                                        $header[$key] = "Employee SSN";
                                    } else if ($item == "employee_email") {
                                        $header[$key] = "Employee Email";
                                    } else if ($item == "employee_PhoneNumber") {
                                        $header[$key] = "Employee Phone Number";
                                    } else if ($item == "first_name") {
                                        $header[$key] = "First Name";
                                    } else if ($item == "last_name") {
                                        $header[$key] = "Last Name";
                                    } else if ($item == "access_level") {
                                        $header[$key] = "Access Level";
                                    } else if ($item == "job_title") {
                                        $header[$key] = "Job Title";
                                    } else if ($item == "course_title") {
                                        $header[$key] = "Course Title";
                                    } else if ($item == "lesson_status") {
                                        $header[$key] = "Lesson Status";
                                    } else if ($item == "course_status") {
                                        $header[$key] = "Course Status";
                                    } else if ($item == "course_type") {
                                        $header[$key] = "Course Type";
                                    } else if ($item == "course_taken_count") {
                                        $header[$key] = "Course Taken Count";
                                    } else if ($item == "course_started_date") {
                                        $header[$key] = "Course Start Date";
                                    } else if ($item == "course_completion_date") {
                                        $header[$key] = "Course Completion Date";
                                    }
                                }
                                //
                            }
                            //
                            $companyHeader = '';
                            if (!empty($data['session']['company_detail']['CompanyName'])) {
                                $companyHeader = ',,,,' . $data['session']['company_detail']['CompanyName'];
                            }
                            //
                            $header_row = implode(',', $header) . ",Record Type";
                            //
                            $file_content = '';
                            $file_content .= $header_row . PHP_EOL;
                            $file_content .= $exportRows;
                            $file_size = 0;
                            //
                            if (function_exists('mb_strlen')) {
                                $file_size = mb_strlen($file_content, '8bit');
                            } else {
                                $file_size = strlen($file_content);
                            }

                            header('Pragma: public');     // required
                            header('Expires: 0');         // no cache
                            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                            header('Cache-Control: private', false);
                            header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                            header('Content-Disposition: attachment; filename="learning_management_system_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
                            header('Content-Transfer-Encoding: binary');
                            header('Content-Length: ' . $file_size); // provide file size
                            header('Connection: close');
                            echo $companyHeader . PHP_EOL . PHP_EOL;
                            echo $header_row . PHP_EOL;
                            echo $exportRows;
                        } else {
                            $this->session->set_flashdata('message', '<b>Error:</b> Record(s) Not Found!');
                            redirect('lms/courses/export_course_csv');
                        }
                        //
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function simpleCompanyReport($departments = "all", $courses = "all", $employees = "all")
    {
        //
        if ($this->session->userdata('logged_in')) {
            // Added on: 28-08-2023
            $session = $this->session->userdata('logged_in');
            $companyId = $session['company_detail']['sid'];
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
            $data['companyId'] = $companyId;
            $data['employer_sid'] = $security_sid;
            $data['company_sid'] = $session['company_detail']['sid'];
            $data['logged_in_view'] = true;
            $data['left_navigation'] = 'courses/partials/profile_left_menu';
            $data['employer_detail'] = $data['session']['employer_detail'];
            $data['company_detail'] = $data['session']['company_detail'];
            $data['level'] = 0;
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
                    $fetchDepartment = $filters["departments"] . ',' . implode(",", $departmentIds);
                    $fetchDepartmentEmployees = $this->course_model->getAllDepartmentEmployees($data['company_sid'], $filters["departments"]);

                    $fetchEmployees = $filters["employees"] . ',' . implode(",", $fetchDepartmentEmployees);
                }
            }
            //
            $companyCoursesList = $this->course_model->getActiveCourseList($data['company_sid'], $filters["courses"]);
            //
            $companyReport = [
                "employee_have_courses" => 0,
                "employee_not_have_courses" => 0,
                "employees_with_completed_courses" => 0,
                "employees_with_started_courses" => 0,
                "employees_with_not_started_courses" => 0,
                "total_employees" => 0,
                "departments_report" => [],
                "courses_report" => [
                    "total_assigned_courses" => 0,
                    "total_completed_courses" => 0,
                    "total_inprogress_courses" => 0,
                    "total_rts_courses" => 0,
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
                $jobTitleIds[] = -1;
                //
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $data['company_sid']);
                //
                foreach ($companyDepartments as $department) {
                    $departments[$department['sid']] = $department;
                    $departments[$department['sid']]['employee_have_courses'] = 0;
                    $departments[$department['sid']]['employee_not_have_courses'] = 0;
                    $departments[$department['sid']]['total_employees'] = 0;
                    $departments[$department['sid']]['pending_courses'] = 0;
                    $departments[$department['sid']]['completed_courses'] = 0;
                    $departments[$department['sid']]['total_courses'] = 0;
                    $departments[$department['sid']]['readyToStart_courses'] = 0;
                    $departments[$department['sid']]['inProgress_courses'] = 0;
                }
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $departments[0]['sid'] = 0;
                    $departments[0]['name'] = "Other (Employees who are not assigned to any department)";
                    $departments[0]['employee_have_courses'] = 0;
                    $departments[0]['employee_not_have_courses'] = 0;
                    $departments[0]['total_employees'] = 0;
                    $departments[0]['pending_courses'] = 0;
                    $departments[0]['completed_courses'] = 0;
                    $departments[0]['total_courses'] = 0;
                    $departments[0]['readyToStart_courses'] = 0;
                    $departments[0]['inProgress_courses'] = 0;
                }
                //
                $companyReport["total_employees"] = count($companyEmployeesList);
                //
                foreach ($companyEmployeesList as $ekey => $employee) {
                    //
                    if ($session['employer_detail']['sid'] == $employee['sid']) {
                        unset($companyEmployeesList[$ekey]);
                    } else {
                        if ($fetchEmployees == "all" || in_array($employee['sid'], explode(",", $fetchEmployees))) {

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
                            $employeesList[$employee['sid']]["profile_picture"]  = getImageURL($employee["profile_picture"]);
                            //
                            if (!empty($employee['lms_job_title'])) {
                                $employeesList[$employee['sid']]["courses_sid"]  = $jobRoleCourses[$employee['job_title_sid']];
                                //
                                $job_title_sid = !empty($employee['job_title_sid']) ? $employee['job_title_sid'] : -1;
                                //
                                $employeesList[$employee['sid']]["courses_statistics"] = $this->course_model->checkEmployeeCoursesReport(
                                    $data['company_sid'],
                                    $employee['sid'],
                                    $jobRoleCourses[$job_title_sid]
                                );
                                //
                                $companyReport["employee_have_courses"]++;
                                //
                                if ($employeesList[$employee['sid']]["courses_statistics"]) {
                                    //
                                    $companyReport["courses_report"]['total_assigned_courses'] = $companyReport["courses_report"]['total_assigned_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['courseCount'];
                                    $companyReport["courses_report"]['total_completed_courses'] = $companyReport["courses_report"]['total_completed_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['completedCount'];
                                    $companyReport["courses_report"]['total_inprogress_courses'] = $companyReport["courses_report"]['total_inprogress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                    $companyReport["courses_report"]['total_rts_courses'] = $companyReport["courses_report"]['total_rts_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
                                    //
                                    if ($employeesList[$employee['sid']]["courses_statistics"]['courseCount'] == $employeesList[$employee['sid']]["courses_statistics"]['completedCount']) {
                                        $companyReport["employees_with_completed_courses"]++;
                                    } else if ($employeesList[$employee['sid']]["courses_statistics"]['completedCount'] > 0) {
                                        $companyReport["employees_with_started_courses"]++;
                                    } else if ($employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'] == 0) {
                                        $companyReport["employees_with_not_started_courses"]++;
                                    }
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
                                }
                                //
                            } else if (checkAnyManualCourseAssigned($employee['sid'])) {
                                $employeesList[$employee['sid']]["courses_statistics"] = $this->course_model->checkEmployeeManualCoursesReport(
                                    $data['company_sid'],
                                    $employee['sid'],
                                );
                                //
                                $companyReport["employee_have_courses"]++;
                                //
                                if ($employeesList[$employee['sid']]["courses_statistics"]) {
                                    //
                                    $companyReport["courses_report"]['total_assigned_courses'] = $companyReport["courses_report"]['total_assigned_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['courseCount'];
                                    $companyReport["courses_report"]['total_completed_courses'] = $companyReport["courses_report"]['total_completed_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['completedCount'];
                                    $companyReport["courses_report"]['total_inprogress_courses'] = $companyReport["courses_report"]['total_inprogress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                    $companyReport["courses_report"]['total_rts_courses'] = $companyReport["courses_report"]['total_rts_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
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
                                }
                                //
                            } else {
                                $employeesList[$employee['sid']]['job_title_sid'] = 0;
                                $employeesList[$employee['sid']]["courses_sid"]  = 0;
                                $employeesList[$employee['sid']]["courses_statistics"] = [
                                    "completedCount" => 0,
                                    "inProgressCount" => 0,
                                    "pendingCount" => 0,
                                    "readyToStart" => 0,
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
                                        $departments[$employeeDepartment]['readyToStart_courses'] = $departments[$employeeDepartment]['readyToStart_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['readyToStart'];
                                        $departments[$employeeDepartment]['inProgress_courses'] = $departments[$employeeDepartment]['inProgress_courses'] + $employeesList[$employee['sid']]["courses_statistics"]['inProgressCount'];
                                        //
                                    }
                                    //
                                    $departments[$employeeDepartment]['employees'][] = $employee['sid'];
                                }
                            }
                            //
                        }
                    }
                }
                //
                $companyReport["departments_report"] = $departments;
                $companyReport["EmployeeList"] = $employeesList;
                $companyReport["CoursesList"] = $coursesList;
            }
            //
            $data["companyReport"] = $companyReport;
            $data["filters"] = $filters;
            $data["filterData"] = $filterData;

            $companyReport["departments_report"] = $departments;
            $data["companyReport"] = $companyReport;
            //
            $this->load->view('courses/simple_company_report', $data);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    /**
     * public function for Manager Report
     * 
     * @version 1.0
     * @date    12/23/2042
     * 
     * 
     * @param string $token
     */
    function managerCoursesReport(
        $token
    ) {
        // 
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $decrypt_token = $this->encryption->decrypt(str_replace(['$$ab$$', '$$ba$$'], ['/', '+'], $token));
        //
        if (!empty($decrypt_token)) {
            $decrypt_keys = explode("/", $decrypt_token);
            //
            $employeeId = $decrypt_keys[0];
            $action = $decrypt_keys[1];
            $companyId = getEmployeeUserParent_sid($employeeId);
            //
            $subordinateInfo = getLMSManagerDepartmentAndTeams($employeeId, $companyId);
            $subordinateInfo['courses'] = $this->course_model->getActiveCompanyCourses($companyId);
            //
            if (!empty($subordinateInfo['employees'])) {
                //
                $subordinateInfo['total_assigned_courses'] = 0;
                $subordinateInfo['total_inprogress_courses'] = 0;
                $subordinateInfo['total_completed_courses'] = 0;
                $subordinateInfo['total_rts_courses'] = 0;
                $subordinateInfo['employee_have_courses'] = 0;
                $subordinateInfo['employee_not_have_courses'] = 0;
                $subordinateInfo['employees_with_completed_courses'] = 0;
                $subordinateInfo['employees_with_started_courses'] = 0;
                $subordinateInfo['employees_with_not_started_courses'] = 0;
                //
                unset($subordinateInfo['employees'][$employeeId]);
                //
                foreach ($subordinateInfo['employees'] as $key => $subordinateEmployee) {
                    //
                    $teamId = $subordinateEmployee['team_sid'];
                    $subordinateInfo['employees'][$key]['department_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
                    $subordinateInfo['employees'][$key]['team_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
                    //
                    if (isset($subordinateEmployee['coursesInfo'])) {
                        //
                        $subordinateInfo["employee_have_courses"]++;
                        $subordinateInfo['total_assigned_courses'] = $subordinateInfo['total_assigned_courses'] + $subordinateEmployee['coursesInfo']['total_course'];
                        $subordinateInfo['total_inprogress_courses'] = $subordinateInfo['total_inprogress_courses'] + $subordinateEmployee['coursesInfo']['started'];
                        $subordinateInfo['total_completed_courses'] = $subordinateInfo['total_completed_courses'] + $subordinateEmployee['coursesInfo']['completed'];
                        $subordinateInfo['total_rts_courses'] = $subordinateInfo['total_rts_courses'] + $subordinateEmployee['coursesInfo']['ready_to_start'];
                        $subordinateInfo['employees'][$key]['coursesInfo']['percentage'] =  ($subordinateEmployee['coursesInfo']['completed'] / $subordinateEmployee['coursesInfo']['total_course']) * 100;
                        //
                        if ($subordinateEmployee['coursesInfo']['total_course'] == $subordinateEmployee['coursesInfo']['completed']) {
                            $subordinateInfo["employees_with_completed_courses"]++;
                        } else if ($subordinateEmployee['coursesInfo']['completed'] > 0) {
                            $subordinateInfo["employees_with_started_courses"]++;
                        } else if ($subordinateEmployee['coursesInfo']['started'] == 0) {
                            $subordinateInfo["employees_with_not_started_courses"]++;
                        }
                    } else {
                        $subordinateInfo["employee_not_have_courses"]++;
                    }
                }
            }
            //
            $data["employeeId"] = $employeeId;
            $data["companyId"] = $companyId;
            $data["companyName"] = getCompanyNameBySid($companyId);
            $data["companyLogo"] = 'https://automotohrattachments.s3.amazonaws.com/' . getCompanyLogoBySid($companyId);
            $data["subordinateReport"] = $subordinateInfo;
            $data["action"] = $action;
            //
            $this->load->view('courses/manager_report', $data);
            ///
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    // function managerCoursesReportNew(
    //     $employeeId
    // ) {
    //     $action = "download";
    //     $companyId = getEmployeeUserParent_sid($employeeId);
    //     //
    //     $subordinateInfo = getLMSManagerDepartmentAndTeams($employeeId, $companyId);
    //     // _e($subordinateInfo,true,true);
    //     $subordinateInfo['courses'] = $this->course_model->getActiveCompanyCourses($companyId);
    //     //
    //     if (!empty($subordinateInfo['employees'])) {
    //         //
    //         $subordinateInfo['total_assigned_courses'] = 0;
    //         $subordinateInfo['total_inprogress_courses'] = 0;
    //         $subordinateInfo['total_completed_courses'] = 0;
    //         $subordinateInfo['total_rts_courses'] = 0;
    //         $subordinateInfo['employee_have_courses'] = 0;
    //         $subordinateInfo['employee_not_have_courses'] = 0;
    //         $subordinateInfo['employees_with_completed_courses'] = 0;
    //         $subordinateInfo['employees_with_started_courses'] = 0;
    //         $subordinateInfo['employees_with_not_started_courses'] = 0;
    //         //
    //         unset($subordinateInfo['employees'][$employeeId]);
    //         //
    //         foreach ($subordinateInfo['employees'] as $key => $subordinateEmployee) {
    //             //
    //             $teamId = $subordinateEmployee['team_sid'];
    //             $subordinateInfo['employees'][$key]['department_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["department_name"] : "N/A";
    //             $subordinateInfo['employees'][$key]['team_name'] =  isset($subordinateInfo['teams'][$teamId]) ? $subordinateInfo['teams'][$teamId]["name"] : "N/A";
    //             //
    //             if (isset($subordinateEmployee['coursesInfo'])) {
    //                 //
    //                 $subordinateInfo["employee_have_courses"]++;
    //                 $subordinateInfo['total_assigned_courses'] = $subordinateInfo['total_assigned_courses'] + $subordinateEmployee['coursesInfo']['total_course'];
    //                 $subordinateInfo['total_inprogress_courses'] = $subordinateInfo['total_inprogress_courses'] + $subordinateEmployee['coursesInfo']['started'];
    //                 $subordinateInfo['total_completed_courses'] = $subordinateInfo['total_completed_courses'] + $subordinateEmployee['coursesInfo']['completed'];
    //                 $subordinateInfo['total_rts_courses'] = $subordinateInfo['total_rts_courses'] + $subordinateEmployee['coursesInfo']['ready_to_start'];
    //                 $subordinateInfo['employees'][$key]['coursesInfo']['percentage'] =  ($subordinateEmployee['coursesInfo']['completed'] / $subordinateEmployee['coursesInfo']['total_course']) * 100;
    //                 //
    //                 if ($subordinateEmployee['coursesInfo']['total_course'] == $subordinateEmployee['coursesInfo']['completed']) {
    //                     $subordinateInfo["employees_with_completed_courses"]++;
    //                 } else if ($subordinateEmployee['coursesInfo']['completed'] > 0) {
    //                     $subordinateInfo["employees_with_started_courses"]++;
    //                 } else if ($subordinateEmployee['coursesInfo']['started'] == 0) {
    //                     $subordinateInfo["employees_with_not_started_courses"]++;
    //                 }
    //             } else {
    //                 $subordinateInfo["employee_not_have_courses"]++;
    //             }
    //         }
    //     }
    //     //
    //     $data["employeeId"] = $employeeId;
    //     $data["companyId"] = $companyId;
    //     $data["companyName"] = getCompanyNameBySid($companyId);
    //     $data["companyLogo"] = 'https://automotohrattachments.s3.amazonaws.com/' . getCompanyLogoBySid($companyId);
    //     $data["subordinateReport"] = $subordinateInfo;
    //     $data["action"] = $action;
    //     //
    //     $this->load->view('courses/manager_report', $data);
    //     ///
    // }
}
