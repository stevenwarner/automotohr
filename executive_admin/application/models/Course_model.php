<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // Fetch all active employees
    function getAllActiveEmployees(
        $companyId,
        $withExec = true
    ) {
        $this->db
            ->select('
            users.sid,
            users.job_title,
            users.first_name,
            users.last_name,
            users.access_level,
            users.timezone,
            users.access_level_plus,
            users.is_executive_admin,
            users.pay_plan_flag,
            users.job_title,
            users.lms_job_title,
            users.profile_picture,
            portal_job_title_templates.sid as job_title_sid
        ')
            ->join(
                "portal_job_title_templates",
                "portal_job_title_templates.sid = users.lms_job_title",
                "left"
            )
            ->where('users.parent_sid', $companyId)
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->order_by('users.first_name', 'ASC');
        //
        if (!$withExec) {
            $this->db->where('users.is_executive_admin', 0);
        }
        $a = $this->db->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    } 

    public function getActiveCourseList ($companyId, $filter) {
        $this->db->select('
            sid,
            course_title,
            course_start_period,
            course_end_period
        ');
        $this->db->where('company_sid', $companyId);
        $this->db->where('is_active', 1);
        //
        if ($filter != "all" && $filter != "0") {
            $this->db->where_in('sid', explode(",", $filter));
        }
        //
        //
        $a = $this->db->get('lms_default_courses');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    public function getCompanyActiveDepartment ($companyId, $filter) {
        $this->db->select('
            sid,
            name
        ');
        $this->db->where('company_sid', $companyId);
        $this->db->where('is_deleted', 0);
        //
        if ($filter != "all" && $filter != "0") {
            $this->db->where_in('sid', explode(",", $filter));
        }
        //
        $a = $this->db->get('departments_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    public function getemployeeDepartmentIds ($courseId, $employeeIds) {
        $this->db->select('department_sid');
        $this->db->where_in('employee_sid', explode(",", $employeeIds));
        $a = $this->db->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!empty($b)) {
            return array_column($b, "department_sid");
        } else {
            return [];
        }
    }

    public function getAllDepartmentEmployees ($courseId, $departmentId) {
        $this->db->select('employee_sid');
        $this->db->where('department_sid', $departmentId);
        $a = $this->db->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!empty($b)) {
            return array_column($b, "employee_sid");
        } else {
            return [];
        }
    }

    public function fetchCourses ($jobTitlesIds, $companyId) {
        //
        $result = [];
        $today = date('Y-m-d');
        //
        if (!empty($jobTitlesIds)) {
            foreach ($jobTitlesIds as $jkey => $jobTitleId) {
                
                $companyCourses = $this->db->select("
                    lms_default_courses.sid
                ")
                ->join(
                    "lms_default_courses_job_titles",
                    "lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid",
                    "right"
                )
                ->where('lms_default_courses.company_sid', $companyId)
                ->where('lms_default_courses.is_active', 1)
                ->where('course_start_period <=', $today)
                ->group_start()
                ->where('lms_default_courses_job_titles.job_title_id', -1)
                ->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId)
                ->group_end()
                ->get('lms_default_courses')
                ->result_array();
                //
                $result[$jobTitleId] = implode(',',array_column($companyCourses, "sid"));
            }
        }
        //
        return $result;
    }

    public function checkEmployeeCoursesReport ($companyId, $employeeId, $employeeAssignCourses) {
        $employeeAssignCoursesList = explode(",", $employeeAssignCourses);
        //
        $result = [
            "completedCount" => 0,
            "inProgressCount" => 0,
            "pendingCount" => 0,
            "readyToStart" => 0,
            "courseCount" => count($employeeAssignCoursesList),
            "percentage" => 0,
            "coursesInfo" => []
        ];
        //
        if (!empty($employeeAssignCoursesList)) {
            foreach ($employeeAssignCoursesList as $courseId) {
                //
                $this->db->select("lesson_status");
                $this->db->where('company_sid', $companyId);
                $this->db->where('employee_sid', $employeeId);
                $this->db->where('course_sid', $courseId);
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
            if ($result["completedCount"]> 0) {
                $result["percentage"] = round(($result["completedCount"] / $result["courseCount"]) * 100, 2);
            }
        }
        //
        return $result;
    }

    public function fetchDepartmentTeams($employeeId)
    {
        //
        $a = $this->db
            ->select('department_sid, team_sid')
            ->where('employee_sid', $employeeId)
            ->get('departments_employee_2_team');
        //
        $records = $a->result_array();
        $a->free_result();
        //
        $result = [
          "departmentIds" => 0,
          "teamIds" => 0  
        ];
        if ($records) {
            //
            if (count($records) > 1) {
                $departmentIds = [];
                $teamIds = [];
                foreach ($records as $record) {
                    if (!in_array($record['department_sid'], $departmentIds)) {
                        array_push($departmentIds, $record['department_sid']);
                        array_push($teamIds, $record['team_sid']);
                    }
                }
                //
                $result['departmentIds'] = implode(',',$departmentIds);
                $result['teamIds'] = implode(',', $teamIds);
            } else {
                $result['departmentIds'] = $records[0]['department_sid'];
                $result['teamIds'] = $records[0]['team_sid'];
            }
            
        } 
        //
        return $result;
    }

}    