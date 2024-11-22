<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_email_model extends CI_Model
{

    private $companyId;
    private $companyEmployees;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Send course reminder emails
     */
    public function sendCourseReminderEmails()
    {
        // get the company ids
        $companyIds = $this->getLMSCompanyIds();
        // check
        if (!$companyIds) {
            exit("No companies found!");
        }
        $companyIds = [
            51,
            8578
        ];
        // iterate
        foreach ($companyIds as $companyId) {
            // send emails
            $this->sendCourseReminderEmailsByCompanyId($companyId);
        }
    }

    /**
     * Send course reminder emails by company wise
     */
    public function sendCourseReminderEmailsByCompanyId(
        int $companyId,
        array $types = [
            "due_soon",
            "expired",
            "inprogress",
            "ready_to_start",
        ],
        int $courseId = 0
    ) {
        // set the company Id
        $this->companyId = $companyId;
        // get the company employees by course
        $employeesWithCourseList = $this
            ->getCompanyEmployeesWithCourses(
                $types,
                $courseId
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
        int $courseId
    ) {
        // get company active courses
        $employeeList = $this->getCompanyEmployeesWithJobTiles();
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
                    $courseId
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

    /**
     * Send course reminder emails by company wise
     */
    private function getCompanyEmployeesWithJobTiles()
    {
        // get company active courses
        return $this
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
            ])
            ->get("users")
            ->result_array();
    }

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

    /**
     * Send course reminder emails by company wise
     */
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

    /**
     * get the LMS onboard company ids
     */
    private function getLMSCompanyIds()
    {
        $record = $this
            ->db
            ->select('sid')
            ->where('module_slug', 'lms')
            ->where('is_disabled', 0)
            ->limit(1)
            ->get('modules')
            ->row_array();

        if (!$record) {
            return [];
        }

        $records = $this
            ->db
            ->select('company_sid')
            ->where('module_sid', $record['sid'])
            ->where('is_active', 1)
            ->get("company_modules")
            ->result_array();

        return $records
            ? array_column($records, "company_sid")
            : [];
    }

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

    /**
     * get the LMS onboard company ids
     */
    private function extractEmployeeCourses(
        array $companyCoursesList,
        array $employeeStartedCourses,
        int $lmsJobTitleId,
        array $types,
        int $courseId
    ) {
        //
        $employeeCoursesList = [
            "expired" => [],
            "due_soon" => [],
            "ready_to_start" => [],
            "inprogress" => [],
        ];
        // set today date
        $todayDateTime = new DateTime(date("Y-m-d 00:00:00"));
        //
        foreach ($companyCoursesList as $v0) {
            // $v0["course_end_period"] = "2024-11-" . rand(1, 31) . " 00:00:00";
            // no need to sent notification email
            // for completed courses
            if ($employeeStartedCourses["completed"] && in_array($v0["sid"], $employeeStartedCourses["completed"])) {
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
            if ($courseId != 0 && $courseId != $v0["sid"]) {
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

    private function sendCourseEmailRemindersToEmployees()
    {
        // get the template
        $template = get_email_template(COURSE_UNCOMPLETED_REMINDER_EMAILS);
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
            if (!$this->allowedToSendEmail($employee["sid"])) {
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
            $replaceArray["{{course_listing_table}}"] = $this->generateCoursesTable(
                $employee["courses"]
            );

            // set keys
            $replaceKeys = array_keys($replaceArray);

            // replace
            $fromName = str_replace(
                $replaceKeys,
                $replaceArray,
                $templateFromName
            );
            $subject = str_replace(
                $replaceKeys,
                $replaceArray,
                $templateSubject
            );
            $body = str_replace(
                $replaceKeys,
                $replaceArray,
                $templateBody
            );

            log_and_sendEmail(
                $fromName,
                $employee["email"],
                $subject,
                $body,
                $replaceArray["{{contact_name}}"]
            );
        }
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
                if ($v0["expiring_in"] || $v0["expiring"] == 0) {
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
                if ($v0["expiring_in"] || $v0["expiring"] == 0) {
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
                if ($v0["expiring_in"] || $v0["expiring"] == 0) {
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
}
