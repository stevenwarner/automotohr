<?php

class Cron_lms_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function getEmployeesWithPendingCourses(){
        //
        $companies = $this->getAllLMSEnableCompanies();
        //
        if(!empty($companies)){
            $companyIds = array_column($companies, "company_sid");
            return $this->getCompanyPendingCoursesEmployees($companyIds);
        } else {
            return array();
        }
    }

    function getEmployeesWithTodayAssignedCourses () {
        //
        $companies = $this->getAllLMSEnableCompanies();
        //
        if(!empty($companies)){
            $companyIds = array_column($companies, "company_sid");
            return $this->getCompanyTodayAssignedCoursesEmployees($companyIds);
        } else {
            return array();
        }
    }

    function getAllLMSEnableCompanies () {
        $this->db->select('sid');
        $this->db->where('module_slug', 'lms');
        $this->db->where('is_disabled', 0);
        //
        $record_obj = $this->db->get('modules');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if(!empty($record_arr)){
            //
            $this->db->select('company_sid');
            $this->db->where('module_sid', $record_arr['sid']);
            $this->db->where('is_active', 1);
            //
            $records_obj = $this->db->get('company_modules');
            $companyList = $records_obj->result_array();
            $records_obj->free_result();
            //
            if(!empty($companyList)){
                return $companyList;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function getCompanyPendingCoursesEmployees ($companyIds) {
        //
        $result = [];
        //
        foreach ($companyIds as $companyId) {
            //
            $companyCoursesList = $this->getCompanyActiveCourses($companyId);
            //
            $pendingCourses = [];
            $jobTitleStatus = "";
            $jobTitleIds = [];
            //
            if(!empty($companyCoursesList)){
                //
                $now = new DateTime(date("Y-m-d"));
                //
                foreach ($companyCoursesList as $course) {
                    //
                    if (!empty($course['course_end_period'])) {
                        //
                        $end_period = new DateTime($course['course_end_period']);
                        //
                        $end_diff = $now->diff($end_period)->format("%r%a");
                        //
                        if ($end_diff < 15 && $end_diff > 0) {
                            $pendingCourses[] = $course['sid'];
                            //
                            if ($jobTitleStatus != 'all') {
                                $jobTitleInfo = $this->getCoursesJobTitles($course['sid']);
                                //
                                $jobTitleStatus = $jobTitleInfo['status'];
                                $jobTitleIds = $jobTitleInfo['ids'];
                            }
                        } 
                    }
                }
            }
            //
            $companyemployees = $this->getCompanyEmployeesDetails ($companyId, $jobTitleIds, $jobTitleStatus, $pendingCourses);
            //
            if (!empty($companyemployees)) {
                $result[$companyId]["employees"] = $companyemployees;
            }
            //
        }
        //
        return $result;
    } 
    
    function getCompanyTodayAssignedCoursesEmployees ($companyIds) {
        //
        $result = [];
        //
        foreach ($companyIds as $ckey => $companyId) {
            //
            $companyCoursesList = $this->getCompanyActiveCourses($companyId);
            //
            $pendingCourses = [];
            $jobTitleStatus = "";
            $jobTitleIds = [];
            //
            if(!empty($companyCoursesList)){
                //
                $now = new DateTime(date("Y-m-d"));
                //
                foreach ($companyCoursesList as $course) {
                    //
                    $start_period = new DateTime($course['course_start_period']);
                    //
                    $start_diff = $now->diff($start_period)->format("%r%a");
                    //
                    if ($start_diff == 0) {
                        $pendingCourses[] = $course['sid'];
                        //
                        if ($jobTitleStatus != 'all') {
                            $jobTitleInfo = $this->getCoursesJobTitles($course['sid']);
                            //
                            $jobTitleStatus = $jobTitleInfo['status'];
                            $jobTitleIds = $jobTitleInfo['ids'];
                        }
                    }
                }
            }
            //
            $companyemployees = $this->getCompanyEmployeesDetails ($companyId, $jobTitleIds, $jobTitleStatus, $pendingCourses);
            //
            if (!empty($companyemployees)) {
                $result[$companyId]["employees"] = $companyemployees;
            }
            //
        }
        //
        return $result;
    } 

    function getCompanyActiveCourses ($companyId) {
        $this->db->select('sid, course_start_period, course_end_period');
        $this->db->where('company_sid', $companyId);
        $this->db->where('is_active', 1);
        //
        $records_obj = $this->db->get('lms_default_courses');
        $activeCourses = $records_obj->result_array();
        $records_obj->free_result();
        //
        if(!empty($activeCourses)){
            return $activeCourses;
        } else {
            return array();
        }
    }

    function getCoursesJobTitles ($courseIds) {
        $result = [
            'ids' => [],
            'status' => 'not_found'
        ];
        //
        $this->db->select('job_title_id');
        $this->db->where('lms_default_courses_sid', $courseIds);
        //
        $records_obj = $this->db->get('lms_default_courses_job_titles');
        $jobTitleList = $records_obj->result_array();
        $records_obj->free_result();
        //
        $jobTitleIds = array_column($jobTitleList, "job_title_id");
        //
        if (!empty($jobTitleIds)) {
            if (in_array(-1,$jobTitleIds)) {
                $result['status'] = "all";
            } else {
                $result['status'] = "specific";
                $result['ids'] = $jobTitleIds;
            }
        }
        //
        return $result;
    }   
    
    function getCompanyEmployeesDetails ($companyId, $jobTitleIds, $jobTitleStatus, $pendingCourses) {
        //
        $employees = [];
        //
        if ($jobTitleStatus == "all" || $jobTitleStatus == "specific") {
            if ($jobTitleStatus == "specific") {
                $employees = $this->getSpecificCompanyEmployees($companyId, $jobTitleIds);
            } else {
                $employees = $this->getAllCompanyEmployees($companyId);
            }
        } else {
            $employees = [];
        }
        //
        if (!empty($employees)) {
            foreach ($employees as $ekey => $employee) {
                $coursesStatistics = $this->checkEmployeeCoursesReport(
                    $companyId, 
                    $employee['sid'],
                    $pendingCourses
                );
                //
                if ($coursesStatistics['pendingCount'] == 0) {
                    unset($employees[$ekey]);
                } else {
                    unset($employees[$ekey]['sid']);
                    $employees[$ekey]["pendingCount"] = $coursesStatistics['pendingCount'];
                }
                //
            }
        }
        //
        return $employees;
    }

    function getAllCompanyEmployees ($companyId) {
        $this->db
            ->select('
            users.sid,
            users.first_name,
            users.last_name,
            users.email
        ');
        //
        $this->db->where('users.parent_sid', $companyId);
        $this->db->where('users.active', 1);
        $this->db->where('users.terminated_status', 0);
        $this->db->where('users.is_executive_admin', 0);
        //
        $this->db->group_start();
        $this->db->where('users.job_title <> ', NULL);
        $this->db->where('users.job_title <> ', '');
        $this->db->group_end();
        //
        $records_obj = $this->db->get('users');
        $employeesArray = $records_obj->result_array();
        $records_obj->free_result();
        //
        return $employeesArray;
    }

    function getSpecificCompanyEmployees ($companyId, $jobTitleIds) {
        //
        $this->db
            ->select('
            users.sid,
            users.first_name,
            users.last_name,
            users.email
        ');
        $this->db->join(
                "portal_job_title_templates",
                "portal_job_title_templates.title = users.job_title",
                "inner"
        );
        //
        $this->db->where_in('portal_job_title_templates.sid', $jobTitleIds);
        $this->db->where('users.parent_sid', $companyId);
        $this->db->where('users.active', 1);
        $this->db->where('users.terminated_status', 0);
        $this->db->where('users.is_executive_admin', 0);
        //
        $records_obj = $this->db->get('users');
        $employeesArray = $records_obj->result_array();
        $records_obj->free_result();
        //
        return $employeesArray;
    }

    function checkEmployeeCoursesReport ($companyId, $employeeId, $employeeAssignCoursesList) {
        //
        $result = [
            "completedCount" => 0,
            "pendingCount" => 0,
            "courseCount" => count($employeeAssignCoursesList),
            "percentage" => 0,
            "coursesInfo" => []
        ];
        //
        if (!empty($employeeAssignCoursesList)) {
            foreach ($employeeAssignCoursesList as $courseId) {

                $this->db->where('company_sid', $companyId);
                $this->db->where('employee_sid', $employeeId);
                $this->db->where('course_sid', $courseId);
                $this->db->where('course_status', "passed");
                //
                $this->db->from('lms_employee_course');
                //
                $count = $this->db->count_all_results();
                //
                if ($count > 0) {
                    $result["completedCount"]++;
                } else {
                    $result["pendingCount"]++;
                }  
                //
                $result["coursesInfo"][$courseId] = $count;   
            }
            //
        }
        //
        if (checkAnyManualCourseAssigned($employeeId)) {
            //
            $manualAssignedCourses = $this->db
                ->select('default_course_sid')
                ->from('lms_manual_assign_employee_course')
                ->where('employee_sid', $employeeId)
                ->where('company_sid', $companyId)
                ->get()
                ->result_array();
            //
            $result["courseCount"] = $result["courseCount"] + count($manualAssignedCourses);
            //
            foreach ($manualAssignedCourses as $manualCourse) {
                //
                $this->db->select("lesson_status");
                $this->db->where('company_sid', $companyId);
                $this->db->where('employee_sid', $employeeId);
                $this->db->where('course_sid', $manualCourse['default_course_sid']);
                //
                $a = $this->db->get('lms_employee_course');
                //
                $b = $a->row_array();
                $a = $a->free_result();
                //
                $status = 0;
                //
                if (empty($b)) {
                    $result["pendingCount"]++;
                    $result["readyToStart"]++;
                } else if ($b['lesson_status'] == 'completed') {
                    $status = 1;
                    $result["completedCount"]++;
                } else if ($b['lesson_status'] == 'incomplete') {
                    $result["pendingCount"]++;
                    $result["inProgressCount"]++;
                }
                //
                $result["coursesInfo"][$courseId] = $status;
            }
        }
        //
        if ($result["completedCount"] > 0) {
            $result["percentage"] = round(($result["completedCount"] / $result["courseCount"]) * 100, 2);
        }
        //
        return $result;
    }

    public function checkEmployeeManualCoursesReport($companyId, $employeeId)
    {
        //
        $result = [
            "completedCount" => 0,
            "inProgressCount" => 0,
            "pendingCount" => 0,
            "readyToStart" => 0,
            "courseCount" => 0,
            "percentage" => 0,
            "coursesInfo" => []
        ];
        //
        $manualAssignedCourses = $this->db
            ->select('default_course_sid')
            ->from('lms_manual_assign_employee_course')
            ->where('employee_sid', $employeeId)
            ->where('company_sid', $companyId)
            ->get()
            ->result_array();
        //
        $result["courseCount"] = count($manualAssignedCourses);
        //
        foreach ($manualAssignedCourses as $manualCourse) {
            //
            $this->db->select("lesson_status");
            $this->db->where('company_sid', $companyId);
            $this->db->where('employee_sid', $employeeId);
            $this->db->where('course_sid', $manualCourse['default_course_sid']);
            //
            $a = $this->db->get('lms_employee_course');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            $status = 0;
            //
            if (empty($b)) {
                $result["pendingCount"]++;
                $result["readyToStart"]++;
            } else if ($b['lesson_status'] == 'completed') {
                $status = 1;
                $result["completedCount"]++;
            } else if ($b['lesson_status'] == 'incomplete') {
                $result["pendingCount"]++;
                $result["inProgressCount"]++;
            }
            //
            $result["coursesInfo"][$courseId] = $status;
        }
        //
        if ($result["completedCount"] > 0) {
            $result["percentage"] = round(($result["completedCount"] / $result["courseCount"]) * 100, 2);
        }
        //
        return $result;
    }
}    