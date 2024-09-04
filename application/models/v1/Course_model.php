<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Course_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    public function checkEmployeeCourse($companyId, $employeeId, $courseId)
    {
        //
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        //
        $this->db->from('lms_employee_course');
        //
        $count = $this->db->count_all_results();
        //
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkEmployeeCourseCompleted($companyId, $employeeId, $courseId)
    {
        $this->db->select('course_status');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        $this->db->where('lesson_status', 'completed');
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        if (!empty($result) && $result['course_status'] == 'passed') {
            return true;
        } else {
            return false;
        }
    }

    public function getCourseInfo($sid)
    {
        $this->db->select('course_title, course_content, course_type, course_questions, Imsmanifist_json');
        $this->db->where('sid', $sid);
        $result = $this->db->get('lms_default_courses')->row_array();
        return $result;
    }

    public function getCMIObject($courseId, $employeeId, $companyId)
    {
        $this->db->select('course_answer_json');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        if (!empty($result)) {
            return json_decode($result['course_answer_json'], true);
        } else {
            return array();
        }
    }

    public function getEmployeeCourseProgressInfo($courseId, $employeeId, $companyId)
    {
        //
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        //
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        return $result;
    }

    public function getStudentInfo($employeeId)
    {
        //
        $this->db->select('dob, ssn');
        $this->db->where('sid', $employeeId);
        //
        $result = $this->db->get('users')->row_array();
        //
        return $result;
    }


    public function moveCourseHistory($courseId, $employeeId, $companyId)
    {
        //
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('course_sid', $courseId);
        //
        $result = $this->db->get('lms_employee_course')->row_array();
        //
        if (!empty($result)) {
            if (!empty($result['course_status']) && $result['course_status'] == "passed") {
                //
                $rowId = $result['sid'];
                unset($result['sid']);
                //
                $this->db->insert('lms_employee_course_history', $result);
                //
                $dataToUpdate = array();
                $dataToUpdate['lesson_status'] = 'incomplete';
                $dataToUpdate['course_status'] = '';
                $dataToUpdate['course_answer_json'] = NULL;
                //
                $this->db->where('sid', $rowId)->update('lms_employee_course', $dataToUpdate);
            }
        }
        //
        return true;
    }

    /***
     * get employee pending course count
     *
     * @param int    $companyId
     * @param int    $employeeId
     * @return array
     */
    public function getEmployeePendingCourseCount(
        int $companyId,
        int $employeeId
    ): int {
        // get employee job title id
        $jobTitleId = $this->db
            ->select('portal_job_title_templates.sid')
            ->from('users')
            ->join('portal_job_title_templates', 'users.job_title = portal_job_title_templates.title', 'title')
            ->where('users.sid', $employeeId)
            ->get()
            ->row_array();
        // when no job title is assigned
        if (!$jobTitleId) {
            $userJobTitle = $this->db
                ->select('job_title')
                ->from('users')
                ->where('users.sid', $employeeId)
                ->get()
                ->row_array();
            //    
            if (!empty($userJobTitle['job_title'])) {
                $jobTitleId = -1;
            } else {  
                return 0;
            }
            
        }
        //
        $jobTitleId = $jobTitleId['sid'];
        // get the courses
        $assignedCourses = $this->db
            ->select('lms_default_courses.sid')
            ->from('lms_default_courses')
            ->join(
                'lms_default_courses_job_titles',
                'lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid',
                'inner'
            )
            ->where('lms_default_courses.company_sid', $companyId)
            ->where('lms_default_courses.is_active', 1)
            ->group_start()
            ->where('lms_default_courses_job_titles.job_title_id', -1)
            ->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId)
            ->group_end()
            ->get()
            ->result_array();
        // if no courses are found
        if (!$assignedCourses) {
            return 0;
        }
        //
        $returnCount = 0;
        // loop through courses
        foreach ($assignedCourses as $course) {
            // get course answer
            $courseStatus = $this->db
                ->select('course_status')
                ->from('lms_employee_course')
                ->where('lms_employee_course.course_sid', $course['sid'])
                ->where('lms_employee_course.employee_sid', $employeeId)
                ->get()
                ->row_array();
            //
            if (!$courseStatus) {
                $returnCount++;
            } else {
                //
                if ($courseStatus['course_status'] != 'passed') {
                    $returnCount++;
                }
                //
            }
        }

        return $returnCount;
    }

    /***
     * get employee pending course count
     *
     * @param int    $companyId
     * @return array
     */
    public function getCompanyCoursesInfo(
        int $companyId
    ): array {
        // 
        // get the company courses
        $companyCourses = $this->db
            ->select('sid, course_start_period, course_end_period')
            ->from('lms_default_courses')
            ->where('company_sid', $companyId)
            ->where('is_active', 1)
            ->get()
            ->result_array();
        //
        $result = [
            'total_course' => count($companyCourses),
            'expire_soon' => 0,
            'upcoming' => 0,
            'expired' => 0,
            'started' => 0
        ];
        // if no courses are found
        if (!$companyCourses) {
            return $result;
        }
        //
        $now = new DateTime(date("Y-m-d"));
        // loop through courses
        foreach ($companyCourses as $course) {
            //
            $start_period = new DateTime($course['course_start_period']);
            $end_period = new DateTime($course['course_end_period']);
            //
            $start_diff = $now->diff($start_period)->format("%r%a");
            $end_diff = $now->diff($end_period)->format("%r%a");
            //
            if ($start_diff > 0) {
                $result['upcoming']++;
            } else if ($start_diff < 0 && $end_diff > 0) {
                if ($end_diff < 15) {
                    $result['expire_soon']++;
                } 
                //
                $result['started']++;
            } else if ($start_diff < 0 && $end_diff < 0) {
                $result['expired']++;
            }
            
        }
        //
        return $result;
    }

    public function insertEmployeeSubordinate(
        int $companyId,
        int $employeeId,
        array $subordinateInfo
    )
    {
        $dataToInsert = array();
        $dataToInsert['unique_key'] = generateRandomString(32);
        $dataToInsert['department_team_json'] = json_encode($subordinateInfo);
        //
        if (
            $this->db
            ->where('company_sid', $companyId)
            ->where('employee_sid', $employeeId)
            ->count_all_results('lms_employee_subordinate')
        ) {
            //
            $dataToInsert['created_at'] = date('Y-m-d H:i:s');
            $dataToInsert['updated_at'] = date('Y-m-d H:i:s');
            //
            $this->db->where('company_sid', $companyId);
            $this->db->where('employee_sid', $employeeId);
            $this->db->update('lms_employee_subordinate', $dataToInsert);
        } else {
            //
            $dataToInsert['company_sid'] = $companyId;
            $dataToInsert['employee_sid'] = $employeeId;
            $dataToInsert['created_at'] = date('Y-m-d H:i:s');
            $dataToInsert['updated_at'] = date('Y-m-d H:i:s');
            //
            $this->db->insert('lms_employee_subordinate', $dataToInsert);
        }
        //
        return $dataToInsert['unique_key'];
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
            portal_job_title_templates.sid as job_title_sid
        ')
            ->join(
                "portal_job_title_templates",
                "portal_job_title_templates.title = users.job_title",
                "left"
            )
            ->where('users.parent_sid', $companyId)
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->order_by('users.first_name', 'ASC');
        //
        // $this->db->group_start();
        // $this->db->where('users.job_title <> ', NULL);
        // $this->db->where('users.job_title <> ', '');
        // $this->db->group_end();
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
            if ($result["completedCount"]> 0) {
                $result["percentage"] = round(($result["completedCount"] / $result["courseCount"]) * 100, 2);
            }
        }
        //
        return $result;
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

    function getEmployerDetail($id) {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    public function getCourseIdByTitleAndType ($title, $type, $companyId) {
        $this->db->select('sid');
        $this->db->where('course_title', $title);
        $this->db->where('course_type', $type);
        $this->db->where('company_sid', $companyId);
        $a = $this->db->get('lms_default_courses');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!empty($b)) {
            return $b['sid'];
        } else {
            return 0;
        }
    }

    public function insertEmployeeCourseInfo ($dataToInsert) {
        $this->db->insert('lms_employee_course', $dataToInsert);
    }
}
