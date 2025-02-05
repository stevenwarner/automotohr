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
        $this->db->select('first_name, last_name, middle_name, dob, ssn');
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
                $dataToUpdate['lesson_status'] = 'incomplete';
                $dataToUpdate['course_status'] = '';
                $dataToUpdate['course_banner'] = NULL;
                $dataToUpdate['course_version'] = NULL;
                $dataToUpdate['course_file_name'] = NULL;
                $dataToUpdate['Imsmanifist_json'] = NULL;
                $dataToUpdate['course_answer_json'] = NULL;
                $dataToUpdate['course_taken_count'] = $result['course_taken_count'] + 1;
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
            ->join('portal_job_title_templates', 'users.lms_job_title = portal_job_title_templates.sid', 'inner')
            ->where('users.sid', $employeeId)
            ->get()
            ->row_array();
        // when no job title is assigned
        if (!$jobTitleId) {
            return 0;
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
     * get company active courses list
     *
     * @param int    $companyId
     * @return array
     */
    public function getActiveCompanyCourses(
        int $companyId
    ): array {
        // 
        // get the active company courses
        return $this->db
            ->select('sid, course_title')
            ->from('lms_default_courses')
            ->where('company_sid', $companyId)
            ->where('is_active', 1)
            ->get()
            ->result_array();
    }

    /***
     * get employee pending course count
     *
     * @param int $companyId
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
    ) {
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
            users.lms_job_title,
            users.profile_picture,
            portal_job_title_templates.sid as job_title_sid
        ')
            //     ->join(
            //         "portal_job_title_templates",
            //         "portal_job_title_templates.title = users.job_title",
            //         "left"
            //     )
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

    public function getActiveCourseList($companyId, $filter)
    {
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

    public function getCompanyActiveDepartment($companyId, $filter)
    {
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
                $result['departmentIds'] = implode(',', $departmentIds);
                $result['teamIds'] = implode(',', $teamIds);
            } else {
                $result['departmentIds'] = $records[0]['department_sid'];
                $result['teamIds'] = $records[0]['team_sid'];
            }
        }
        //
        return $result;
    }

    public function fetchCourses($jobTitlesIds, $companyId)
    {
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
                $result[$jobTitleId] = implode(',', array_column($companyCourses, "sid"));
            }
        }
        //
        return $result;
    }

    public function checkEmployeeCoursesReport($companyId, $employeeId, $employeeAssignCourses)
    {
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
            if ($result["completedCount"] > 0) {
                $result["percentage"] = round(($result["completedCount"] / $result["courseCount"]) * 100, 2);
            }
        }
        //
        return $result;
    }

    public function getemployeeDepartmentIds($courseId, $employeeIds)
    {
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

    public function getAllDepartmentEmployees($courseId, $departmentId)
    {
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

    public function getCourseLanguageInfo($courseId, $language)
    {
        $courseInfo = $this->db
            ->select('course_file_name, Imsmanifist_json')
            ->from('lms_scorm_courses')
            ->join('lms_assign_course_log', 'lms_scorm_courses.course_sid = lms_assign_course_log.default_course_sid')
            ->where('lms_assign_course_log.assigned_course_sid', $courseId)
            ->where('lms_scorm_courses.course_file_language', $language)
            ->get()
            ->row_array();
        //
        return $courseInfo;
    }

    public function deletePreviousAllLanguagesById($courseId)
    {
        $this->db->where('course_sid', $courseId);
        $this->db->delete('lms_scorm_courses');
    }

    public function deletePreviousAllLanguagesByIdAndLanguage($courseId, $language)
    {
        $this->db->where('course_sid', $courseId);
        $this->db->where('course_file_language', $language);
        $this->db->delete('lms_scorm_courses');
    }

    function getEmployerDetail($id)
    {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    public function getCourseIdByTitleAndType($title, $type, $companyId)
    {
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

    public function insertEmployeeCourseInfo($dataToInsert)
    {
        $this->db->insert('lms_employee_course', $dataToInsert);
    }

    public function getEmployeeCoursesHistory($companyId, $employeeId)
    {
        $this->db->select('sid, course_title, course_start_period, course_end_period');
        $this->db->where('company_sid', $companyId);
        $a = $this->db->get('lms_default_courses');
        //
        $c = $a->result_array();
        $a = $a->free_result();
        //
        if (!empty($c)) {
            foreach ($c as $key => $course) {
                //
                $this->db->select('sid, lesson_status, course_status, course_banner, course_language, created_at, updated_at');
                $this->db->where('company_sid', $companyId);
                $this->db->where('employee_sid', $employeeId);
                $this->db->where('course_sid', $course['sid']);
                $a = $this->db->get('lms_employee_course_history');
                //
                $h = $a->result_array();
                $a = $a->free_result();
                //
                if (!empty($h)) {
                    $c[$key]['history'] = $h;
                } else {
                    unset($c[$key]);
                }
            }
            //
            return $c;
        } else {
            return 0;
        }
    }

    public function getCourseHistoryInfo($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $a = $this->db->get('lms_employee_course_history');
        //
        $h = $a->row_array();
        $a = $a->free_result();
        //
        if (!empty($h)) {
            return $h;
        } else {
            return [];
        }
    }

    public function getCoursesHistoryTitle($courseId)
    {
        $this->db->select('course_title');
        $this->db->where('sid', $courseId);
        $a = $this->db->get('lms_default_courses');
        //
        $t = $a->row_array();
        $a = $a->free_result();
        //
        if (!empty($t)) {
            //
            return $t['course_title'];
        } else {
            return '';
        }
    }

    public function getCourseCertificateInfo($sid)
    {
        $this->db->select('course_title, secondary_logo');
        $this->db->where('sid', $sid);
        $result = $this->db->get('lms_default_courses')->row_array();
        return $result;
    }

    public function moveCourseIntoHistoryAndUpdate()
    {
        //
        $this->db->select('*');
        $this->db->where('lesson_status', "completed");
        //
        $completedCourses = $this->db->get('lms_employee_course')->result_array();
        //
        if ($completedCourses) {
            foreach ($completedCourses as $course) {
                $rowId = $course['sid'];
                unset($course['sid']);
                //
                $this->db->insert('lms_employee_course_history', $course);
                //
                $dataToUpdate = array();
                $dataToUpdate['lesson_status'] = 'incomplete';
                $dataToUpdate['course_status'] = '';
                $dataToUpdate['course_banner'] = NULL;
                $dataToUpdate['course_version'] = NULL;
                $dataToUpdate['course_file_name'] = NULL;
                $dataToUpdate['Imsmanifist_json'] = NULL;
                $dataToUpdate['course_answer_json'] = NULL;
                $dataToUpdate['course_taken_count'] = $course['course_taken_count'] + 1;
                //
                $this->db->where('sid', $rowId)->update('lms_employee_course', $dataToUpdate);
            }
        }
        //
        return true;
    }

    public function getSelectedCourses($coursesIds, $companyId)
    {
        //
        $this->db->select('sid, course_title, course_type');
        //
        $this->db->where('is_active', 1);
        $this->db->where('company_sid', $companyId);
        //
        if (!in_array(0, $coursesIds)) {
            $this->db->where_in('sid', $coursesIds);
        }
        //
        $result = $this->db->get('lms_default_courses')->result_array();
        return $result;
    }

    // Fetch all company active employees
    function getCompanyActiveEmployeesWithCourses(
        $companyId
    ) {
        //
        $this->db
            ->select('
            users.sid,
            users.email,
            users.ssn,
            users.PhoneNumber,
            users.job_title,
            users.first_name,
            users.last_name,
            users.access_level,
            users.employee_number,
            users.lms_job_title,
        ')
            ->join(
                "portal_job_title_templates",
                "portal_job_title_templates.sid = users.lms_job_title",
                "inner"
            )
            ->where([
                "users.lms_job_title is not null" => null,
                "users.parent_sid" => $companyId,
                "users.active" => 1,
                "users.terminated_status" => 0,
                "users.is_executive_admin" => 0,
                "portal_job_title_templates.status" => 1,
            ]);
        //

        $a = $this->db->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function getEmployeeCourseStatus(
        int $courseId,
        int $employeeId
    ): array {
        // get the employee assigned course
        $courseInfo = $this->db
            ->select('lesson_status, course_status, course_taken_count, created_at, completed_at')
            ->from('lms_employee_course')
            ->where('course_sid', $courseId)
            ->where('employee_sid', $employeeId)
            ->get()
            ->row_array();
        //
        // if course not started yet
        if (!$courseInfo) {
            $courseInfo['lesson_status'] = 'not_started';
            $courseInfo['course_status'] = '';
            $courseInfo['course_taken_count'] = 0;
            $courseInfo['created_at'] = '';
            $courseInfo['completed_at'] = '';
        }
        //
        return $courseInfo;
    }

    public function getEmployeeCourseHistory(
        int $courseId,
        int $employeeId
    ): array {
        // get the employee assigned course
        $courseInfo = $this->db
            ->select('lesson_status, course_status, course_taken_count, created_at, completed_at')
            ->from('lms_employee_course_history')
            ->where('course_sid', $courseId)
            ->where('employee_sid', $employeeId)
            ->get()
            ->result_array();
        //
        return $courseInfo;
    }

    public function getCompanyStats(int $companyId)
    {
        // set return array
        $returnArray = [
            "totalCourses" => 0,
            "totals" => [
                "completed" => 0,
                "started" => 0,
                "pending" => 0,
            ],
            "percentage" => [
                "completed" => 0,
                "started" => 0,
                "pending" => 0,
            ],
            "employee" => []
        ];
        // get the company courses with job titles
        $results = $this
            ->db
            ->select("sid")
            ->where("company_sid", $companyId)
            ->where("is_active", 1)
            ->get("lms_default_courses")
            ->result_array();
        //
        if (!$results) {
            return $returnArray;
        }
        //
        foreach ($results as $v0) {
            // get the job title
            $results1 = $this
                ->db
                ->select("job_title_id")
                ->where("lms_default_courses_sid", $v0["sid"])
                ->get("lms_default_courses_job_titles")
                ->result_array();
            //
            if (!$results1) {
                continue;
            }
            //
            $jobTitlesList = array_column($results1, "job_title_id");
            // all employees with lms job title
            $this
                ->db
                ->select("sid")
                ->where([
                    "active" => 1,
                    "terminated_status" => 0,
                    "is_executive_admin" => 0,
                    "parent_sid" => $companyId,
                ]);
            if (in_array("-1", $jobTitlesList)) {
                $this->db->where("lms_job_title is not null", null);
            } else {
                // get specific employees with lms job title
                $this->db->where_in("lms_job_title", $jobTitlesList);
            }
            //
            $courseEmployees = $this
                ->db
                ->get("users")
                ->result_array();
            //
            if (!$courseEmployees) {
                continue;
            }
            // extract course ids
            $courseEmployeeIds = array_column(
                $courseEmployees,
                "sid"
            );
            //
            // get passed course count
            $completedCoursesCount = $this
                ->db
                ->where([
                    "course_sid" => $v0["sid"],
                    "course_status" => "passed"
                ])
                ->count_all_results("lms_employee_course");

            // get passed course count
            $completedPendingCount = $this
                ->db
                ->where([
                    "course_sid" => $v0["sid"],
                    "course_status <> " => "passed"
                ])
                ->count_all_results("lms_employee_course");

            // increment total count
            $returnArray["totalCourses"] += count($courseEmployeeIds);
            $returnArray["totals"]["completed"] += $completedCoursesCount;
            $returnArray["totals"]["started"] += $completedPendingCount;
            $returnArray["totals"]["pending"] += count($courseEmployeeIds)
                - ($completedPendingCount + $completedCoursesCount);

            //
            $returnArray["courses"][$v0["sid"]] = [
                "total" => count($courseEmployeeIds),
                "completed" => $completedCoursesCount,
                "started" => $completedPendingCount,
                "pending" => (count($courseEmployeeIds)
                    - ($completedPendingCount + $completedCoursesCount)),
            ];
        }

        //
        if ($returnArray["totalCourses"] > 0) {
            $returnArray["percentage"]["completed"] =
                number_format(
                    (
                        ($returnArray["totals"]["completed"] * 100) / $returnArray["totalCourses"]
                    ),
                    2,
                    ".",
                    ""
                );
            $returnArray["percentage"]["started"] =
                number_format(
                    (
                        ($returnArray["totals"]["started"] * 100) / $returnArray["totalCourses"]
                    ),
                    2,
                    ".",
                    ""
                );
            $returnArray["percentage"]["pending"] =
                number_format(
                    (
                        ($returnArray["totals"]["pending"] * 100) / $returnArray["totalCourses"]
                    ),
                    2,
                    ".",
                    ""
                );
        }

        return $returnArray;
    }

    //
    public function getEmployeePendingCourseDetail(
        int $companyId,
        int $employeeId
    ): array {
        // get employee job title id
        $jobTitleId = $this->db
            ->select('portal_job_title_templates.sid')
            ->from('users')
            ->join('portal_job_title_templates', 'users.lms_job_title = portal_job_title_templates.sid', 'inner')
            ->where('users.sid', $employeeId)
            ->get()
            ->row_array();
        // when no job title is assigned
        if (!$jobTitleId) {
            return [];
        }
        //
        $jobTitleId = $jobTitleId['sid'];
        // get the courses
        $assignedCourses = $this->db
            ->select('
                lms_default_courses.sid,
                lms_default_courses.course_title
            ')
            ->from('lms_default_courses')
            ->join(
                'lms_default_courses_job_titles',
                'lms_default_courses_job_titles.lms_default_courses_sid = lms_default_courses.sid',
                'inner'
            )
            ->where('lms_default_courses.company_sid', $companyId)
            ->where('lms_default_courses.is_active', 1)
            ->where('lms_default_courses.course_type', "scorm")
            ->group_start()
            ->where('lms_default_courses_job_titles.job_title_id', -1)
            ->or_where('lms_default_courses_job_titles.job_title_id', $jobTitleId)
            ->group_end()
            ->get()
            ->result_array();
        // if no courses are found
        if (empty($assignedCourses)) {
            return [];
        }
        //
        $existingCourseIds = [];
        //
        if (!empty($assignedCourses)) {
            foreach ($assignedCourses as $key => $val) {
                if ($existingCourseIds[$val["sid"]]) {
                    unset($assignedCourses[$key]);
                    continue;
                }
                //
                $existingCourseIds[$val["sid"]] = true;
                // get course languages
                $courseLanguages = $this->getCourseLanguagesWithMain(
                    $companyId,
                    $val["sid"]
                );
                //
                $assignedCourses[$key]["languages"] = [];
                $assignedCourses[$key]["course_language"] = "english";

                if ($courseLanguages) {
                    $languages = array_column(
                        $courseLanguages,
                        "course_file_language"
                    );
                    $assignedCourses[$key]["languages"] = $languages;
                }

                $courseData = $this->db
                    ->select('lesson_status, course_status, course_language, course_type')
                    ->from('lms_employee_course')
                    ->where('lms_employee_course.course_sid', $val['sid'])
                    ->where('lms_employee_course.employee_sid', $employeeId)
                    ->get()
                    ->row_array();

                if ($courseData) {
                    if ($courseData["course_status"] === "passed") {
                        unset($assignedCourses[$key]);
                    } else {
                        $assignedCourses[$key]['lesson_status'] =
                            $courseData['lesson_status'];
                        $assignedCourses[$key]['course_language'] = $courseData["course_language"];
                        $assignedCourses[$key]['course_type'] = $courseData["course_type"];
                    }
                } else {
                    $assignedCourses[$key]['lesson_status'] = 'ready_to_start';
                    $assignedCourses[$key]['course_language'] = $courseData["course_language"];
                    $assignedCourses[$key]['course_type'] = $courseData["course_type"];
                }
            }

            return $assignedCourses;
        } else {
            return [];
        }
    }


    //
    public function manuallyCourseComplete(
        $companyId,
        $employeeId,
        $courseId,
        $language,
        $completionDate
    ) {
        //
        $todayDateTime = getSystemDate();
        // set where array
        $whereArray = [
            "lms_employee_course.company_sid" => $companyId,
            "lms_employee_course.employee_sid" => $employeeId,
            "lms_employee_course.course_sid" => $courseId,
        ];
        // get the required course data
        $courseData = $this->getCourseLanguagesWithMain(
            $companyId,
            $courseId,
            $language
        );

        if (!$courseData) {
            return sendResponse(
                400,
                [
                    "errors" => [
                        "Failed to verify course language."
                    ]
                ]
            );
        }
        //
        $courseData = $courseData[0];
        // get course banner and version
        $record = $this
            ->db
            ->select([
                "course_version",
                "course_banner",
            ])
            ->where("sid", $courseId)
            ->limit(1)
            ->get("lms_default_courses")
            ->row_array();

        if (!$record || !$record["course_banner"]) {
            return sendResponse(
                400,
                [
                    "errors" => [
                        "Failed to verify course banner."
                    ]
                ]
            );
        }
        // check if course is in progress
        if ($this->db->where($whereArray)->count_all_results("lms_employee_course")) {
            // course exists
            // needs to convert to completed
            $updateArray = [
                "lesson_status" => "completed",
                "course_status" => "passed",
                "course_banner" => $record["course_banner"],
                "course_version" => $record["course_version"],
                "Imsmanifist_json" => $courseData["Imsmanifist_json"],
                "course_file_name" => $courseData["course_file_name"],
                "updated_at" => $todayDateTime,
                "completed_at" => formatDateToDB($completionDate, 'm-d-Y', DB_DATE).' 00:00:00',
                "is_manual_completed" => 1,
            ];
            //
            $this
                ->db
                ->where($whereArray)
                ->update(
                    'lms_employee_course',
                    $updateArray
                );
        } else {

            $dataToInsert = [
                "course_sid" => $courseId,
                "company_sid" => $companyId,
                "employee_sid" => $employeeId,
                "lesson_status" => "completed",
                "course_status" => "passed",
                "course_type" => "scorm",
                "course_banner" => $record["course_banner"],
                "course_version" => $record["course_version"],
                "course_language" => $language,
                "course_file_name" => $courseData["course_file_name"],
                "Imsmanifist_json" => $courseData["Imsmanifist_json"],
                "course_taken_count" => 1,
                "created_at" => $todayDateTime,
                "updated_at" => $todayDateTime,
                "completed_at" => formatDateToDB($completionDate, 'm-d-Y', DB_DATE).' 00:00:00',
                "is_manual_completed" => 1,
            ];
            //
            $this
                ->db
                ->insert('lms_employee_course', $dataToInsert);
        }

        return sendResponse(
            200,
            [
                "message" => "You have successfully marked the course as completed."
            ]
        );


        if (!empty($coursedata)) {
            //
            $dataToUpdate['lesson_status'] = 'completed';
            $dataToUpdate['course_status'] = 'completed';
            $dataToUpdate['completed_at'] == getSystemDate();
            $dataToUpdate['updated_at'] == getSystemDate();
            $dataToUpdate['is_manual_completed'] = 1;

            //
            $this->db->where('course_sid', $courseSid);
            $this->db->where('employee_sid', $employeeSid);
            $this->db->where('company_sid', $companySid);
            $this->db->update('lms_employee_course', $dataToUpdate);
        } else {

            //
            $courseInfo = $this->getCourseInfo($courseSid);

            $dataToInsert['lesson_status'] = 'completed';
            $dataToInsert['course_status'] = 'completed';
            $dataToInsert['completed_at'] = getSystemDate();
            $dataToInsert['updated_at'] = getSystemDate();
            $dataToInsert['is_manual_completed'] = 1;
            $dataToInsert['course_sid'] = $courseSid;
            $dataToInsert['company_sid'] = $companySid;
            $dataToInsert['employee_sid'] = $employeeSid;

            $dataToInsert['course_type'] = $courseInfo['course_type'];
            $dataToInsert['Imsmanifist_json'] = $courseInfo['Imsmanifist_json'];
            $dataToInsert['course_taken_count'] = 1;
            //
            $this->db->insert('lms_employee_course', $dataToInsert);
        }
    }


    public function getCourseLanguagesWithMain(
        int $companyId,
        int $courseId,
        string $language = ""
    ) {
        // get the original course id
        $record = $this
            ->db
            ->select("default_course_sid")
            ->where([
                "assigned_course_sid" => $courseId,
                "assign_company_sid" => $companyId,
            ])
            ->limit(1)
            ->get("lms_assign_course_log")
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        // set where
        $where = [
            "course_sid" => $record["default_course_sid"]
        ];
        if ($language) {
            $where["course_file_language"] = $language;
        }
        // fetch the languages
        return $this
            ->db
            ->select("
                course_file_language,
                Imsmanifist_json,
                course_file_name,
            ")
            ->order_by("course_file_language", "ASC")
            ->where($where)
            ->get("lms_scorm_courses")
            ->result_array();
    }
}
