<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_email_model extends CI_Model
{

    private $companyId;
    private $byPass;
    private $notifiers;
    private $companyEmployees;

    public function __construct()
    {
        parent::__construct();
        //
        $this->byPass = false;
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
            8578,
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
        array $courseIds = []
    ) {
        // set the company Id
        $this->companyId = $companyId;
        // get the company employees by course
        $employeesWithCourseList = $this
            ->getCompanyEmployeesWithCourses(
                $types,
                $courseIds
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
     * send first time course available emails
     */
    public function sendFirstTimeAvailableCourseEmailToEmployees()
    {
        // get the jobs from queue
        $jobs = $this->getCoursesByCompany();
        // extract courses ids
        $courseIds = array_column(
            $jobs,
            "course_sid"
        );
        // send emails forcefully
        $this->byPass = true;
        // get the company employees by course
        $employeesWithCourseList = $this
            ->getCompanyEmployeesWithCourses(
                [
                    "due_soon",
                    "expired",
                    "inprogress",
                    "ready_to_start",
                ],
                $courseIds
            );
        //
        if (!$employeesWithCourseList) {
            //
            $this
                ->db
                ->where_in("sid", array_column($jobs, "sid"))
                ->delete("lms_course_email_queue");
            return ["errors" => ["No employees found!"]];
        }
        //
        $this->companyEmployees = $employeesWithCourseList;
        //
        $this->sendCourseEmailRemindersToEmployees();
        //
        $this
            ->db
            ->where_in("sid", array_column($jobs, "sid"))
            ->delete("lms_course_email_queue");
    }

    /**
     * send first time course available emails
     */
    public function getCoursesByCompany()
    {
        // get the company id from queue
        $record =
            $this
            ->db
            ->select("distinct(company_sid)")
            ->where("is_processing", 0)
            ->limit(1)
            ->get("lms_course_email_queue")
            ->row_array();
        //
        if (!$record) {
            exit("Queue is empty.");
        }
        // set the company id
        $this->companyId = $record["company_sid"];
        // get the company courses
        $records =
            $this
            ->db
            ->select("course_sid, sid")
            ->where("company_sid", $this->companyId)
            ->where("is_processing", 0)
            ->get("lms_course_email_queue")
            ->result_array();
        // update the processing flag
        $this
            ->db
            ->where_in("sid", array_column($records, "sid"))
            ->update(
                "lms_course_email_queue",
                [
                    "is_processing" => 1
                ]
            );
        // return
        return $records;
    }

    public function sendCourseReportToManagers()
    {
        // get the company ids
        $companyIds = $this->getLMSCompanyIds();
        // check
        if (!$companyIds) {
            exit("No companies found!");
        }
        $companyIds = [
            // 51,
            8578,
        ];
        // iterate
        foreach ($companyIds as $companyId) {
            // send emails
            $this->generateAndSendReport($companyId);
        }
    }


    /**
     * Send course reminder emails by company wise
     */
    private function generateAndSendReport(
        int $companyId,
        array $types = [
            "due_soon",
            "expired",
            "inprogress",
            "ready_to_start",
        ],
        array $courseIds = []
    ) {
        // set the company Id
        $this->companyId = $companyId;
        // get notifiers
        $this->notifiers = get_notification_email_contacts(
            $this->companyId,
            "course_status"
        );
        //
        if (!$this->notifiers) {
            return ["errors" => ["No notifiers found"]];
        }
        // get the company employees by course
        $employeesWithCourseList = $this
            ->getCompanyEmployeesWithCourses(
                $types,
                $courseIds
            );
        //
        if (!$employeesWithCourseList) {
            return ["errors" => ["No employees found!"]];
        }
        //
        $this->companyEmployees = $employeesWithCourseList;
        //
        $report = $this->generateReport();
        //
        $this->sendCourseReportEmails($report);
        //
        return ["success"];
    }

    /**
     * generate courses report
     * 
     * @return array
     */
    private function generateReport(): array
    {
        //
        $data = [];
        // add header to the file
        $data[] = ["Report Date/Time", getSystemDate(DATE_WITH_TIME) . " PST"];
        $data[] = [];
        // Add first section
        $data[] = ["Report"];
        // add headers
        $data[] = ["Employee", "Courses", "Due Soon", "Expired", "In Progress", "Ready To Start"];
        //
        $totals = [
            "total" => 0,
            "due_soon" => 0,
            "expired" => 0,
            "inprogress" => 0,
            "ready_to_start" => 0,
        ];
        //
        foreach ($this->companyEmployees as $v0) {
            //
            $tmp = [
                remakeEmployeeName($v0, true, true),
                0,
                count($v0["courses"]["due_soon"]),
                count($v0["courses"]["expired"]),
                count($v0["courses"]["inprogress"]),
                count($v0["courses"]["ready_to_start"]),
            ];
            //
            $tmp[1] = $tmp[2]
                + $tmp[3]
                + $tmp[4]
                + $tmp[5];
            //
            $totals["total"] += $tmp[1];
            $totals["due_soon"] += $tmp[2];
            $totals["expired"] += $tmp[3];
            $totals["inprogress"] += $tmp[4];
            $totals["ready_to_start"] += $tmp[5];


            $data[] = $tmp;
        }

        //
        $data[] = [];
        $data[] = [
            "Summary",
            $totals["total"],
            $totals["due_soon"],
            $totals["expired"],
            $totals["inprogress"],
            $totals["ready_to_start"],
        ];

        return ["name" => "lms_report.csv", "data" => $data];
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

    /**
     * Send course reminder emails by company wise
     */
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
        array $courseIds
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

    private function sendCourseReportEmails(array $report)
    {
        // get the template
        $template = get_email_template(COURSE_REPORT_EMAILS);
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
        foreach ($this->notifiers as $employee) {
            // set replace array
            $replaceArray = $companyReplaceArray;
            //
            $replaceArray["{{baseurl}}"] = base_url();
            $replaceArray["{{full_name}}"] =
                $replaceArray["{{contact_name}}"]
                = $employee["contact_name"];

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

            log_and_send_email_with_attachment(
                FROM_EMAIL_NOTIFICATIONS,
                $employee["email"],
                $subject,
                $body,
                $fromName,
                $report,
                "sendMailWithAttachmentAsString"
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
