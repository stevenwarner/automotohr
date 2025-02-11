<?php

defined('BASEPATH') or exit('No direct script access allowed');

class LMS_company_report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // $this->load->library("pagination");
        $this->load->model('course_model');
    }


    public function index($companyId, $departments = "all", $courses = "all", $employees = "all")
    {
        if ($this->session->userdata('executive_loggedin')) {
            // Added on: 28-08-2023
            $data = $this->session->userdata('executive_loggedin');
            $executiveUserId =  $data['executive_user']['sid'];
            $data['companyName'] = getCompanyNameBySid($companyId);
            //
            $adminPlusData = get_executive_administrator_admin_plus_status($executiveUserId, $companyId);
            //
            $execAdminAccessLevelPlus = FALSE;
            //
            if (!empty($adminPlusData)) {
                $execAdminAccessLevelPlus =  $adminPlusData['access_level_plus'] ? TRUE : FALSE;
            }
            //                                              
            if (!$execAdminAccessLevelPlus || !checkIfAppIsEnabled(MODULE_LMS, $companyId)) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessible!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['title'] = "LMS Employees Report [ " . $data['companyName'] . " ]";
            $data['companyId'] = $companyId;
            $data['executiveUserId'] = $executiveUserId;
            $data['logged_in_view'] = true;
            //
            //
            $filters = [
                "departments" => urldecode($departments),
                "courses" => urldecode($courses),
                "employees" => urldecode($employees)
            ];
            //
            $companyEmployeesList = $this->course_model->getAllActiveEmployees($companyId, false);
            //
            $filterData = [];
            $filterData["employees"] = $companyEmployeesList;
            $filterData["courses"] = $this->course_model->getActiveCourseList($companyId, "all");
            $filterData["departments"] = $this->course_model->getCompanyActiveDepartment($companyId, "all");
            //
            $fetchDepartment = 'all';
            $fetchEmployees = 'all';
            //
            if ($filters["employees"] != "all") {
                $departmentIds = $this->course_model->getEmployeeDepartmentIds($companyId, $filters["employees"]);
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $fetchDepartment = $departmentIds;
                    $fetchEmployees = $filters["employees"];
                } else {
                    $fetchDepartment = $filters["departments"] . ',' . implode(",", $departmentIds);
                    $fetchDepartmentEmployees = $this->course_model->getAllDepartmentEmployees($companyId, $filters["departments"]);

                    $fetchEmployees = $filters["employees"] . ',' . implode(",", $fetchDepartmentEmployees);
                }
            }
            //
            $companyCoursesList = $this->course_model->getActiveCourseList($companyId, $filters["courses"]);
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
            $haveSubordinate = 'no';
            //
            if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                //
                $today = getSystemDate("Y-m-d");
                //
                $coursesList = [];
                $employeesList = [];
                $departments = [];
                $haveSubordinate = 'yes';
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
                $companyDepartments = $this->course_model->getCompanyActiveDepartment($companyId, $fetchDepartment);
                $jobTitleIds = array_filter(array_column($companyEmployeesList, "job_title_sid"));
                $jobTitleIds[] = -1;
                //
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $companyId);
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
                    if ($executiveUserId == $employee['sid']) {
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
                                    $companyId,
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
                                    $companyId,
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
            $data['haveSubordinate'] = $haveSubordinate;
            //
            if ($this->input->method() === 'post') {
                if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=' . $data['companyName'] . '_LMS_company_report_' . date('Y_m_d-H:i:s') . '.csv');
                    $output = fopen('php://output', 'w');
                    //
                    fputcsv($output, array('Company Name', $data['companyName'], '', '', '', ''));
                    fputcsv($output, array('Exported By', getUserNameBySID($executiveUserId), '', '', '', ''));
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
                    fputcsv($output, array('Total Completed Course(s)', $companyReport['courses_report']['total_completed_courses'], round(($companyReport['courses_report']['total_completed_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 2) . '%', '', '', ''));
                    fputcsv($output, array('Total Inprogress Course(s)', $companyReport['courses_report']['total_inprogress_courses'], round(($companyReport['courses_report']['total_inprogress_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 2) . '%', '', '', ''));
                    fputcsv($output, array('Total Ready to Start Course(s)', $companyReport['courses_report']['total_rts_courses'], round(($companyReport['courses_report']['total_rts_courses'] / $companyReport['courses_report']['total_assigned_courses']) * 100, 2) . '%', '', '', ''));
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
            $this->load->view('reports/lms_employees_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function simpleCompanyReport($companyId, $departments = "all", $courses = "all", $employees = "all")
    {
        if ($this->session->userdata('executive_loggedin')) {
            // Added on: 28-08-2023
            $data = $this->session->userdata('executive_loggedin');
            $executiveUserId =  $data['executive_user']['sid'];
            $data['companyName'] = getCompanyNameBySid($companyId);
            $data['companyLogo'] = getCompanyLogoBySid($companyId);
            //
            $adminPlusData = get_executive_administrator_admin_plus_status($executiveUserId, $companyId);
            //
            $execAdminAccessLevelPlus = FALSE;
            //
            if (!empty($adminPlusData)) {
                $execAdminAccessLevelPlus =  $adminPlusData['access_level_plus'] ? TRUE : FALSE;
            }
            //                                              
            if (!$execAdminAccessLevelPlus || !checkIfAppIsEnabled(MODULE_LMS, $companyId)) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessible!');
                redirect('dashboard', 'refresh');
            }
            //
            $data['title'] = "LMS Employees Report [ " . $data['companyName'] . " ]";
            $data['companyId'] = $companyId;
            $data['executiveUserId'] = $executiveUserId;
            $data['executiveUserName'] = $data['executive_user']['first_name'] . ' ' . $data['executive_user']['last_name'] . ' [Executive Admin] (Admin Plus)';
            $data['logged_in_view'] = true;
            //
            //
            $filters = [
                "departments" => urldecode($departments),
                "courses" => urldecode($courses),
                "employees" => urldecode($employees)
            ];

            error_reporting(E_ALL);
            ini_set('display_errors', 1);
            // _e("pakistan",true);
            // echo $companyId;
            $companyEmployeesList = $this->course_model->getAllActiveEmployees($companyId, false);
            // _e($companyEmployeesList,true,true);
            //
            $filterData = [];
            $filterData["employees"] = $companyEmployeesList;
            $filterData["courses"] = $this->course_model->getActiveCourseList($companyId, "all");
            $filterData["departments"] = $this->course_model->getCompanyActiveDepartment($companyId, "all");
            //
            $fetchDepartment = 'all';
            $fetchEmployees = 'all';
            //
            if ($filters["employees"] != "all") {
                $departmentIds = $this->course_model->getEmployeeDepartmentIds($companyId, $filters["employees"]);
                //
                if ($filters["departments"] == "all" || $filters["departments"] == '0') {
                    $fetchDepartment = $departmentIds;
                    $fetchEmployees = $filters["employees"];
                } else {
                    $fetchDepartment = $filters["departments"] . ',' . implode(",", $departmentIds);
                    $fetchDepartmentEmployees = $this->course_model->getAllDepartmentEmployees($companyId, $filters["departments"]);

                    $fetchEmployees = $filters["employees"] . ',' . implode(",", $fetchDepartmentEmployees);
                }
            }
            //
            $companyCoursesList = $this->course_model->getActiveCourseList($companyId, $filters["courses"]);
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
            $haveSubordinate = 'no';
            //
            if (!empty($companyEmployeesList) && !empty($companyCoursesList)) {
                //
                $today = getSystemDate("Y-m-d");
                //
                $coursesList = [];
                $employeesList = [];
                $departments = [];
                $haveSubordinate = 'yes';
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
                $companyDepartments = $this->course_model->getCompanyActiveDepartment($companyId, $fetchDepartment);
                $jobTitleIds = array_filter(array_column($companyEmployeesList, "job_title_sid"));
                $jobTitleIds[] = -1;
                //
                $jobRoleCourses = $this->course_model->fetchCourses($jobTitleIds, $companyId);
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
                    if ($executiveUserId == $employee['sid']) {
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
                                    $companyId,
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
            //
            //
            $this->load->view('reports/simple_company_report', $data);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    function getBase64Image()
    {
        //get url from input
        $url = $this->input->get('url');
        //make a curl call to fetch content
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
        //get mime type
        $mime_type = getMimeType($url);
        $str64 = base64_encode($data);

        print_r(json_encode(array('type' => $mime_type, 'string' => $str64)));
    }

    //
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

        $companySid = $post['companySid'];


        // extract employee id
        $employeeIds = array_column($post["employeeList"], "employee_sid");
        //
        if (!$employeeIds) {
            $res["Message"] = "No employees selected";
            return SendResponse(400, $res);
        }

        // send reminder emails
        $response = $this->course_model->sendCourseReminderEmailsToSpecificEmployees(
                $employeeIds,
                $companySid,
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


  
}
