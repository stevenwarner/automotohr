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

    //

    /**
     * Send course reminder emails to specific employees
     *
     * @param array $employeeIds
     * @param int $companyId
     * @return bool
     */
    public function sendCourseReminderEmailsToSpecificEmployees(
        array $employeeIds,
        int $companyId,
        bool $byPass = false,
        array $types = [
            "due_soon",
            "expired",
            "inprogress",
            "ready_to_start",
        ]
    ) {
        // set the company Id
        $this->companyId = $companyId;
        $this->byPass = $byPass;
        // get the company employees by course
        $employeesWithCourseList = $this
            ->getCompanyEmployeesWithCourses(
                $types,
                [],
                $employeeIds
            );

        //
        if (!$employeesWithCourseList) {
            return ["errors" => ["No employees found!"]];
        }
        //
        $this->companyEmployees = $employeesWithCourseList;
        //
        $this->sendCourseEmailRemindersToEmployees();

        return ["success"];
    }


    /**
     * Send course reminder emails by company wise
     */
    private function getCompanyEmployeesWithCourses(
        array $types,
        array $courseIds,
        array $employeeIds = []
    ) {
        // get company active courses
        $employeeList = $this->getCompanyEmployeesWithJobTiles(
            $employeeIds
        );
        //
        if (!$employeeList) {
            return [];
        }
        // get the company courses and
        // convert them to list
        $companyCoursesList = $this->getCompanyActiveCourses();
        //
        if (!$companyCoursesList) {
            return [];
        }
        //
        foreach ($employeeList as $k0 => $employee) {
            // get the employee courses
            $employeeStartedCourses = $this->getEmployeeCompletedCourses($employee["sid"]);
            // search for employee courses
            $employeeCourses = $this
                ->extractEmployeeCourses(
                    $companyCoursesList,
                    $employeeStartedCourses,
                    $employee["lms_job_title"],
                    $types,
                    $courseIds
                );
            // employee don't have courses
            if (!$employeeCourses) {
                unset($employeeList[$k0]);
                continue;
            }
            $employeeList[$k0]["courses"] = $employeeCourses;
        }
        return $employeeList;
    }


    private function getCompanyEmployeesWithJobTiles(
        array $employeeIds
    ) {
        // get company active courses
        $this
            ->db
            ->select([
                "users.sid",
                "users.first_name",
                "users.middle_name",
                "users.last_name",
                "users.email",
                "users.lms_job_title",
            ])
            ->join(
                "portal_job_title_templates",
                "portal_job_title_templates.sid = users.lms_job_title",
                "inner"
            )
            ->where([
                "users.lms_job_title is not null" => null,
                "users.parent_sid" => $this->companyId,
                "users.active" => 1,
                "users.terminated_status" => 0,
                "users.is_executive_admin" => 0,
                "portal_job_title_templates.status" => 1,
            ]);
        // check for employee ids
        if ($employeeIds) {
            $this
                ->db
                ->where_in(
                    "users.sid",
                    $employeeIds
                );
        }
        return $this
            ->db
            ->get("users")
            ->result_array();
    }


    //

    /**
     * Send course reminder emails by company wise
     */
    private function getCompanyActiveCourses(): array
    {
        // get company active courses
        $records =  $this
            ->db
            ->select([
                "lms_default_courses.sid",
                "lms_default_courses.course_title",
                "lms_default_courses.course_start_period",
                "lms_default_courses.course_end_period",
            ])
            ->where([
                "lms_default_courses.company_sid" => $this->companyId,
                "lms_default_courses.is_active" => 1,
            ])
            ->get("lms_default_courses")
            ->result_array();

        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $v0) {
            // get job titles as well
            $tmp[$v0["sid"]] = $v0;
            $tmp[$v0["sid"]]["job_titles"] =
                $this->getCourseJobTitles(
                    $v0["sid"]
                );
        }

        return $tmp;
    }

    //
    private function getCourseJobTitles(int $courseId): array
    {
        // get company active courses
        $records =  $this
            ->db
            ->select([
                "job_title_id"
            ])
            ->where([
                "lms_default_courses_sid" => $courseId,
            ])
            ->get("lms_default_courses_job_titles")
            ->result_array();

        return !$records
            ? []
            : array_column(
                $records,
                "job_title_id"
            );
    }

    //
    /**
     * get the LMS onboard company ids
     */
    private function getEmployeeCompletedCourses(int $employeeId)
    {
        //
        $returnArray = [
            "completed" => [],
            "inprogress" => [],
        ];
        //
        $records = $this
            ->db
            ->select('course_sid, lesson_status')
            ->where('company_sid', $this->companyId)
            ->where('employee_sid', $employeeId)
            ->get('lms_employee_course')
            ->result_array();
        //
        if (!$records) {
            return $returnArray;
        }

        //
        foreach ($records as $v0) {
            if ($v0["lesson_status"] === "completed") {
                $returnArray["completed"][] = $v0["course_sid"];
            } else {

                $returnArray["inprogress"][] = $v0["course_sid"];
            }
        }

        return $returnArray;
    }


    //
    /**
     * get the LMS onboard company ids
     */
    private function extractEmployeeCourses(
        array $companyCoursesList,
        array $employeeStartedCourses,
        int $lmsJobTitleId,
        array $types,
        array $courseIds
    ) {
        //
        $employeeCoursesList = [
            "expired" => [],
            "due_soon" => [],
            "ready_to_start" => [],
            "inprogress" => [],
            "passed" => [],
        ];
        // set today date
        $todayDateTime = new DateTime(date("Y-m-d 00:00:00"));
        //
        foreach ($companyCoursesList as $v0) {
            // $v0["course_end_period"] = "2024-11-" . rand(1, 31) . " 00:00:00";
            // no need to sent notification email
            // for completed courses
            if ($employeeStartedCourses["completed"] && in_array($v0["sid"], $employeeStartedCourses["completed"])) {
                if (in_array("passed", $types)) {
                    $employeeCoursesList["passed"][] = $v0;
                }
                continue;
            }
            // check if the course implements on employee
            if (
                !in_array($lmsJobTitleId, $v0["job_titles"])
                && !in_array(-1, $v0["job_titles"])
            ) {
                continue;
            }
            // check for courseId
            if ($courseIds && !in_array($v0["sid"], $courseIds)) {
                continue;
            }

            if ($v0["course_end_period"]) {
                $expireDateTime = new DateTime($v0["course_end_period"]);
                // not expired
                $dueIn = $expireDateTime
                    ->diff($todayDateTime)
                    ->days;
            }
            // check for expired types
            if ($v0["course_end_period"] && in_array("expired", $types)) {
                $expireDateTime = new DateTime($v0["course_end_period"]);
                // not expired
                if ($expireDateTime < $todayDateTime) {
                    //
                    $difference = $expireDateTime
                        ->diff($todayDateTime)
                        ->days;
                    $v0["expiring_in"] = $difference;
                    $employeeCoursesList["expired"][] = $v0;
                    continue;
                }
            }
            // check for due soon types
            if ($v0["course_end_period"] && in_array("due_soon", $types)) {
                $expireDateTime = new DateTime($v0["course_end_period"]);
                //
                $difference = $expireDateTime
                    ->diff($todayDateTime)
                    ->days;

                if ($difference <= COURSE_DUE_SOON_DAYS) {
                    $v0["expiring_in"] = $difference;
                    $employeeCoursesList["due_soon"][] = $v0;
                    continue;
                }
            }

            // check for due soon types
            if (in_array("ready_to_start", $types) && !in_array($v0["sid"], $employeeStartedCourses["inprogress"])) {
                $v0["expiring_in"] = $dueIn;
                $employeeCoursesList["ready_to_start"][] = $v0;
                continue;
            }

            // check for due soon types
            if (in_array("inprogress", $types) && in_array($v0["sid"], $employeeStartedCourses["inprogress"])) {
                $v0["expiring_in"] = $dueIn;
                $employeeCoursesList["inprogress"][] = $v0;
                continue;
            }
        }
        //
        return $employeeCoursesList;
    }
    //


    private function sendCourseEmailRemindersToEmployees(string $templateCode = COURSE_UNCOMPLETED_REMINDER_EMAILS)
    {
        // get the template

        $template = get_email_template($templateCode);


        // get company details
        $companyDetails = getCompanyInfo($this->companyId);
        // set common replace array
        $companyReplaceArray = [
            "{{company_name}}" => $companyDetails["company_name"],
            "{{company_address}}" => $companyDetails["company_address"],
            "{{company_phone}}" => $companyDetails["company_phone"],
            "{{career_site_url}}" => $companyDetails["career_site_url"],
        ];
        // get the required indexes
        $templateSubject = $template["subject"];
        $templateFromName = $template["from_name"];
        $templateBody = $template["text"];
        //
        foreach ($this->companyEmployees as $employee) {
            // check if two weeks are done
            if (!$this->byPass && !$this->allowedToSendEmail($employee["sid"])) {
                continue;
            }
            // set replace array
            $replaceArray = $companyReplaceArray;
            //
            $replaceArray["{{first_name}}"] = $employee["first_name"];
            $replaceArray["{{last_name}}"] = $employee["last_name"];
            $replaceArray["{{middle_name}}"] = $employee["middle_name"];
            $replaceArray["{{baseurl}}"] = base_url();
            $replaceArray["{{full_name}}"] =
                $replaceArray["{{contact_name}}"]
                = remakeEmployeeName(
                    $employee,
                    true,
                    true
                );
            //
            $replaceArray["{{course_listing_table}}"] = $this->generateCoursesTable(
                $employee["courses"]
            );
            //
            // set keys
            $replaceKeys = array_keys($replaceArray);
            //
            // replace
            $fromName = str_replace(
                $replaceKeys,
                $replaceArray,
                $templateFromName
            );
            //
            $subject = str_replace(
                $replaceKeys,
                $replaceArray,
                $templateSubject
            );
            //
            $email_hf = message_header_footer_domain($this->companyId, $companyDetails["company_name"]);
            //
            $body = $email_hf['header']
                . str_replace($replaceKeys, $replaceArray, $templateBody)
                . $email_hf['footer'];
            //        
            log_and_sendEmail(
                $fromName,
                $employee["email"],
                $subject,
                $body,
                $replaceArray["{{contact_name}}"]
            );
        }
    }



    //
    private function allowedToSendEmail(int $employeeId): bool
    {
        // check if record exists
        $record = $this
            ->db
            ->select("sid, last_email_sent_date")
            ->where("employee_sid", $employeeId)
            ->limit(1)
            ->get("lms_employee_email_log")
            ->row_array();

        if (!$record) {
            $this->db->insert(
                "lms_employee_email_log",
                [
                    "employee_sid" => $employeeId,
                    "last_email_sent_date" => getSystemDate("Y-m-d")
                ]
            );
            return true;
        }
        // check
        $now = new DateTime(date("Y-m-d 00:00:00"));
        $lastDate = new DateTime($record["last_email_sent_date"] . "00:00:00");
        // difference
        // Calculate the difference
        $interval = $lastDate->diff($now);
        // Get the total days and convert to weeks
        $totalDays = $interval->days;
        $totalWeeks = floor($totalDays / 7);
        //
        if ($totalWeeks >= 2) {
            $this->db
                ->where(
                    "employee_sid",
                    $employeeId,
                )->update(
                    "lms_employee_email_log",
                    [
                        "last_email_sent_date" => getSystemDate("Y-m-d")
                    ]
                );
            return true;
        }

        return false;
    }

        /**
     * generate course table
     *
     * @param array $courses
     * return string
     */
    private function generateCoursesTable(array $courses): string
    {
        $html = "";
        $html  .= '<table border="1">';
        $html  .= '<tbody>';
        // inprogress
        if ($courses["inprogress"]) {
            $html .= '<tr>';
            $html .= '  <td colspan="2">';
            $html .= '      <strong>Courses In Progress </strong>';
            $html .= '  </td>';
            $html .= '</tr>';

            foreach ($courses["inprogress"] as $v0) {
                $html .= '<tr>';
                $html .= '  <td>';
                $html .= $v0["course_title"];
                $html .= '  </td>';
                $html .= '  <td>';
                if (strlen($v0["expiring_in"]) >= 1) {
                    $html .= "Due in " . ($v0["expiring_in"]) . " days. ";
                } else {
                    $html .= "-";
                }
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        // ready_to_start
        if ($courses["ready_to_start"]) {
            $html .= '<tr>';
            $html .= '  <td colspan="2">';
            $html .= '      <strong>Ready To Start</strong>';
            $html .= '  </td>';
            $html .= '</tr>';

            foreach ($courses["ready_to_start"] as $v0) {
                $html .= '<tr>';
                $html .= '  <td>';
                $html .= $v0["course_title"];
                $html .= '  </td>';
                $html .= '  <td>';
                if (strlen($v0["expiring_in"]) >= 1) {
                    $html .= "Due in " . ($v0["expiring_in"]) . " days. ";
                } else {
                    $html .= "-";
                }
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        // expired
        if ($courses["expired"]) {
            $html .= '<tr>';
            $html .= '  <td colspan="2">';
            $html .= '       <strong>Past Due</strong>';
            $html .= '  </td>';
            $html .= '</tr>';

            foreach ($courses["expired"] as $v0) {
                $html .= '<tr>';
                $html .= '  <td>';
                $html .= $v0["course_title"];
                $html .= '  </td>';
                $html .= '  <td>';
                $html .= "Expired " . ($v0["expiring_in"]) . " days ago. ";
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        // due_soon
        if ($courses["due_soon"]) {
            $html .= '<tr>';
            $html .= '  <td colspan="2">';
            $html .= '      <strong>Due Soon</strong>';
            $html .= '  </td>';
            $html .= '</tr>';

            foreach ($courses["due_soon"] as $v0) {
                $html .= '<tr>';
                $html .= '  <td>';
                $html .= $v0["course_title"];
                $html .= '  </td>';
                $html .= '  <td>';
                if (strlen($v0["expiring_in"]) >= 1) {
                    $html .= "Due in " . ($v0["expiring_in"]) . " days. ";
                } else {
                    $html .= "-";
                }
                $html .= '  </td>';
                $html .= '</tr>';
            }
        }
        $html .= '</tbody>';
        $html .= '</table>';
        //
        return $html;
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

}
