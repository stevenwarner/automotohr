<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_email_model extends CI_Model
{

    private $companyId;
    private $byPass;
    private $notifiers;
    private $companyEmployees;
    private $testingCompanyIds;

    private $executiveAdminsList = [];

    public function __construct()
    {
        parent::__construct();
        //
        $this->byPass = false;
        $this->testingCompanyIds = [
            51,
            8578,
            21, // testing company from local
        ];
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
            "due_soon", // due soon
            "expired", // past due
            "inprogress", // in progress
            "ready_to_start", // ready to start
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
            // ->where_in('company_sid', $this->testingCompanyIds)
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
        // iterate
        $this->executiveAdminsList = [];

        foreach ($companyIds as $companyId) {
            // send emails
            $this->generateAndSendReport($companyId);
        }

        if (!empty($this->executiveAdminsList)) {

            foreach ($this->executiveAdminsList as $exRow) {

                $companyReplaceArray = [
                    "{{company_name}}" => '',
                    "{{company_address}}" => '',
                    "{{company_phone}}" => '',
                    "{{career_site_url}}" => '',
                ];

                $template = get_email_template(COURSE_REPORT_EMAILS);
                //
                $templateSubject = $template["subject"];
                $templateFromName = $template["from_name"];
                $templateBody = $template["text"];
                // set replace array
                //

                //_e($exRow,true);

                $replaceArray = $companyReplaceArray;
                //
                $replaceArray["{{baseurl}}"] = base_url();
                $replaceArray["{{full_name}}"] =
                    $replaceArray["{{contact_name}}"]
                    = $exRow['data']['contact_name'];


                $viewReport = '';
                foreach ($exRow['data']['buttons'] as $buttonRow) {
                    $viewReport .= "<br><br>" . $buttonRow;
                }

                $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
                $templateBody = str_replace('{{download_report}}', '', $templateBody);

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

                // _e($exRow['email'],true);
                log_and_send_email_with_attachment(
                    FROM_EMAIL_NOTIFICATIONS,
                    $exRow['data']['email'],
                    $subject,
                    $body,
                    $fromName,
                    '',
                    "sendMailWithAttachmentAsString"
                );
            }
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
            "passed"
        ],
        array $courseIds = []
    ) {
        echo "\nCompany Id: {$companyId} \n";
        // set the company Id
        $this->companyId = $companyId;
        // get notifiers
        $this->notifiers = get_notification_email_contacts(
            $this->companyId,
            "course_status"
        );
        //
        if (!$this->notifiers) {
            echo "No notifiers found! \n";
            return ["errors" => ["No notifiers found"]];
        }
        // loop through the 


        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );

        foreach ($this->notifiers as $v0) {
            // skip in case of non-employee contact
            // as we don't have team members
            if ($v0["employer_sid"] === 0) {
                echo "Employer Id 0 \n";
                continue;
            }

            if (!$v0["employer_sid"]) {
                echo "External employees not allowed \n";
                continue;
            }
            // check the department first
            $response = getMyDepartmentAndTeams($v0["employer_sid"], "courses", "get", $this->companyId);
            //
            $teamEmployeeIds = $response
                ? array_column(
                    $response["employees"],
                    "employee_sid"
                )
                : [];
            //
            if (!$teamEmployeeIds) {
                echo "No teams found \n";
                continue;
            }

            // check is executive admin
            if ($v0["is_executive_admin"] == 1) {
                echo "Entered in executive admin {$v0["email"]}'\n";
                //
                if (!array_key_exists($v0["email"], $this->executiveAdminsList)) {
                    $this->executiveAdminsList[$v0['email']] = [
                        "data" => [
                            "buttons" => [],
                            "contact_name" => $v0["name"],
                            "email" => $v0["email"],
                        ]
                    ];
                }
                //
                $viewCode = str_replace(
                    ['/', '+'],
                    ['$$ab$$', '$$ba$$'],
                    $this->encryption->encrypt($v0["employer_sid"] . '/' . 'view')
                );
                //
                $downloadCode = str_replace(
                    ['/', '+'],
                    ['$$ab$$', '$$ba$$'],
                    $this->encryption->encrypt($v0["employer_sid"] . '/' . 'download')
                );

                //
                $viewLink = base_url("lms/manager_report") . '/' . $viewCode;
                $downloadLink = base_url("lms/manager_report") . '/' . $downloadCode;

                $companyDetails = getCompanyInfo($v0["company_sid"]);

                $buttons = '';
                $forCompany = "Company: " . $companyDetails["company_name"] . "<br>";
                $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
                $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';
                $buttons .= $forCompany . $viewReport . '&nbsp;&nbsp;&nbsp;' . $downloadReport;

                $this->executiveAdminsList[$v0['email']]['data']['buttons'][] = $buttons;
                $this->executiveAdminsList[$v0['email']]['data']['contact_name'] = $v0['contact_name'];
                $this->executiveAdminsList[$v0['email']]['data']['email'] = $v0['email'];
            } else {
                echo "Entered in employee {$v0["email"]}'\n";
                // get the company employees by course
                $employeesWithCourseList = $this
                    ->getCompanyEmployeesWithCourses(
                        $types,
                        $courseIds,
                        $teamEmployeeIds
                    );
                // no team members found
                if (!$employeesWithCourseList) {
                    echo "No employees with courses found \n";
                    continue;
                }
                //
                $this->companyEmployees = $employeesWithCourseList;
                //
                $report = $this->generateReport();
                echo "Report generated \n";
                //
                $this->sendCourseReportEmails($report, $v0);
                echo "Report Sent \n\n";
            }

            //      

        }

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
        $data[] = ["Employee", "Courses", "Due Soon", "Expired", "In Progress", "Ready To Start", "Completed"];
        //
        $totals = [
            "total" => 0,
            "due_soon" => 0,
            "expired" => 0,
            "inprogress" => 0,
            "ready_to_start" => 0,
            "passed" => 0,
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
                count($v0["courses"]["passed"]),
            ];
            //
            $tmp[1] = $tmp[2]
                + $tmp[3]
                + $tmp[4]
                + $tmp[5]
                + $tmp[6];
            //
            $totals["total"] += $tmp[1];
            $totals["due_soon"] += $tmp[2];
            $totals["expired"] += $tmp[3];
            $totals["inprogress"] += $tmp[4];
            $totals["ready_to_start"] += $tmp[5];
            $totals["passed"] += $tmp[6];

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
            $totals["passed"],
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
            // employee don't have courses or employee don't have courses in "ready to start" or "inprogress"
            if (!$employeeCourses) {
                unset($employeeList[$k0]);
                continue;
            }
            //
            $hasAllowedType = false;
            //
            foreach ($types as $type) {
                if ($employeeCourses[$type]) {
                    $hasAllowedType = true;
                }
            }
            //
            if (!$hasAllowedType) {
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
        $records = $this
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
        $records = $this
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
            // ->where_in('company_sid', $this->testingCompanyIds)
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

    private function sendCourseReportEmails(array $report, array $employee)
    {
        // _e($report,true,true);
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
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        // foreach ($this->notifiers as $employee) {
        $templateBody = $template["text"];
        // set replace array
        $replaceArray = $companyReplaceArray;
        //
        $replaceArray["{{baseurl}}"] = base_url();
        $replaceArray["{{full_name}}"] =
            $replaceArray["{{contact_name}}"]
            = $employee["contact_name"];
        //
        $viewCode = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($employee['employer_sid'] . '/' . 'view')
        );
        //
        $downloadCode = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($employee['employer_sid'] . '/' . 'download')
        );
        //
        $viewLink = base_url("lms/manager_report") . '/' . $viewCode;
        $downloadLink = base_url("lms/manager_report") . '/' . $downloadCode;
        //
        $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
        $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';
        //
        $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
        $templateBody = str_replace('{{download_report}}', $downloadReport, $templateBody);
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
        echo "Sending Email \n\n";
        //
        log_and_send_email_with_attachment(
            FROM_EMAIL_NOTIFICATIONS,
            $employee["email"],
            $subject,
            $body,
            $fromName,
            $report,
            "sendMailWithAttachmentAsString"
        );
        // }
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
        $html .= '<table border="1">';
        $html .= '<tbody>';
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

    public function sendDocumentReportToManagers()
    {
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $this->load->model('reports_model');
        $this->load->model('manage_admin/copy_employees_model');
        //
        $companies = $this->copy_employees_model->get_all_companies();
        //
        if (!$companies) {
            exit("No companies found!");
        }

        $executiveAdminsList = [];

        //
        $scheduleArray = [
            "schedule" => [],
            "current" => [
                "dayOfWeek" => getSystemDate("0N"),
                "day" => getSystemDate("d"),
                "month" => getSystemDate("m"),
                "time" => getSystemDate("h:i A"),
            ],
        ];

        foreach ($companies as $companyRow) {
            // check if we have sent the report today
            // otherwise make an entry
            if (
                $this
                    ->db
                    ->where([
                        "company_sid" => $companyRow["sid"],
                        "created_at" => getSystemDate("Y-m-d")
                    ])->count_all_results("schedule_document_sent_email_log")
            ) {
                continue;
            }

            // get the schedule
            $schedule = $this
                ->db
                ->select([
                    "schedule_type",
                    "schedule_day",
                    "schedule_time",
                    "schedule_date",
                ])
                ->where([
                    "company_sid" => $companyRow["sid"],
                    "schedule_type <> " => "none"
                ])
                ->limit(1)
                ->get("schedule_document")
                ->row_array();
            // skip when there is no schedule
            if (!$schedule) {
                continue;
            } else {
                //
                $scheduleArray["schedule"] = [
                    "type" => $schedule["schedule_type"],
                    "dayOfWeek" => $schedule["schedule_day"],
                    "date" => $schedule["schedule_date"],
                    "time" => $schedule["schedule_time"],
                ];
            }
            // check if the time to send emails
            if (!doSendScheduleDocument($scheduleArray)) {
                continue;
            }
            // Add record
            $this->db
                ->insert(
                    "schedule_document_sent_email_log",
                    [
                        "company_sid" => $companyRow["sid"],
                        "created_at" => getSystemDate("Y-m-d")
                    ]
                );

            //
            $post['companySid'] = $companyRow['sid'];
            $post['employeeSid'] = ['all'];
            $post['employeeStatus'] = [];
            $post['documentSid'] = ['all'];
            $post['documentAction'] = 'all';
            //
            $employeeDocument = $this->reports_model->getEmployeeAssignedDocumentForReport($post);
            //
            if (sizeof($employeeDocument['Data'])) {
                //
                $report = $this->generateDocumentReport($employeeDocument);
                //
                // get notifiers
                $managersList = get_notification_email_contacts(
                    $companyRow['sid'],
                    "document_report"
                );
                //
                if (!empty($managersList)) {
                    //
                    $companyDetails = getCompanyInfo($companyRow['sid']);
                    // set common replace array
                    $companyReplaceArray = [
                        "{{company_name}}" => $companyDetails["company_name"],
                        "{{company_address}}" => $companyDetails["company_address"],
                        "{{company_phone}}" => $companyDetails["company_phone"],
                        "{{career_site_url}}" => $companyDetails["career_site_url"],
                    ];

                    //
                    foreach ($managersList as $key => $empRow) {
                        //

                        if ($empRow['is_executive_admin'] == 1) {

                            //
                            $viewCode = str_replace(
                                ['/', '+'],
                                ['$$ab$$', '$$ba$$'],
                                $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'view')
                            );
                            //
                            $downloadCode = str_replace(
                                ['/', '+'],
                                ['$$ab$$', '$$ba$$'],
                                $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'download')
                            );

                            //
                            $viewLink = base_url("hr_documents_management/manager_report") . '/' . $viewCode;
                            $downloadLink = base_url("hr_documents_management/manager_report") . '/' . $downloadCode;


                            $buttons = '';
                            $forCompany = "From: " . $companyDetails["company_name"] . "<br>";
                            $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
                            $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';
                            $buttons .= $forCompany . $viewReport . '&nbsp;&nbsp;&nbsp;' . $downloadReport;

                            $executiveAdminsList[$empRow['email']]['data']['buttons'][] = $buttons;
                            $executiveAdminsList[$empRow['email']]['data']['contact_name'] = $empRow['contact_name'];
                            $executiveAdminsList[$empRow['email']]['data']['email'] = $empRow['email'];
                        } else {

                            $template = get_email_template(DOCUMENT_REPORT_EMAILS);
                            //
                            $templateSubject = $template["subject"];
                            $templateFromName = $template["from_name"];
                            $templateBody = $template["text"];

                            // set replace array
                            //
                            $replaceArray = $companyReplaceArray;
                            //
                            $replaceArray["{{baseurl}}"] = base_url();
                            $replaceArray["{{full_name}}"] =
                                $replaceArray["{{contact_name}}"]
                                = $empRow['contact_name'];
                            //
                            $viewCode = str_replace(
                                ['/', '+'],
                                ['$$ab$$', '$$ba$$'],
                                $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'view')
                            );
                            //
                            $downloadCode = str_replace(
                                ['/', '+'],
                                ['$$ab$$', '$$ba$$'],
                                $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'download')
                            );
                            //
                            $viewLink = base_url("hr_documents_management/manager_report") . '/' . $viewCode;
                            $downloadLink = base_url("hr_documents_management/manager_report") . '/' . $downloadCode;
                            //
                            $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
                            $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';

                            //
                            $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
                            $templateBody = str_replace('{{download_report}}', $downloadReport, $templateBody);


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
                            //
                            log_and_send_email_with_attachment(
                                FROM_EMAIL_NOTIFICATIONS,
                                $empRow['email'],
                                $subject,
                                $body,
                                $fromName,
                                $report,
                                "sendMailWithAttachmentAsString"
                            );
                        }
                    }
                }
            }
        }
        //
        if (!empty($executiveAdminsList)) {
            foreach ($executiveAdminsList as $exRow) {

                $companyReplaceArray = [
                    "{{company_name}}" => '',
                    "{{company_address}}" => '',
                    "{{company_phone}}" => '',
                    "{{career_site_url}}" => '',
                ];

                $template = get_email_template(DOCUMENT_REPORT_EMAILS);
                //
                $templateSubject = $template["subject"];
                $templateFromName = $template["from_name"];
                $templateBody = $template["text"];
                // set replace array
                //
                $replaceArray = $companyReplaceArray;
                //
                $replaceArray["{{baseurl}}"] = base_url();
                $replaceArray["{{full_name}}"] =
                    $replaceArray["{{contact_name}}"]
                    = $exRow['data']['contact_name'];


                $viewReport = '';
                foreach ($exRow['data']['buttons'] as $buttonRow) {
                    $viewReport .= "<br><br>" . $buttonRow;
                }

                $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
                $templateBody = str_replace('{{download_report}}', '', $templateBody);

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
                    $exRow["data"]['email'],
                    $subject,
                    $body,
                    $fromName,
                    $report,
                    "sendMailWithAttachmentAsString"
                );
            }
        }

        echo "done";
    }

    /**
     * generate courses report
     * 
     * @return array
     */
    private function generateDocumentReport($employeesDocument): array
    {
        //
        $data = [];
        // add header to the file
        $data[] = ["Report Date/Time", getSystemDate(DATE_WITH_TIME) . " PST"];
        $data[] = [];
        // Add first section
        $data[] = ["Report"];
        // add headers
        $data[] = ["Employee", "Documents", "Not Completed", "Completed", "No Action Required"];
        //
        $totals = [
            "total" => 0,
            "not_completed" => 0,
            "completed" => 0,
            "on_action_required" => 0,
        ];
        //
        foreach ($employeesDocument['Data'] as $row) {
            //
            $totalAssignedDocs = count($row['assigneddocuments']);
            $totalAssignedGeneralDocs = count($row['assignedgeneraldocuments']);
            $totalDocs = $totalAssignedDocs + $totalAssignedGeneralDocs;
            //
            $totalDocsNotCompleted = 0;
            $totalDocsCompleted = 0;
            $totalDocsNoAction = 0;
            //
            if (!empty($row['assignedi9document'])) {
                $totalDocs = $totalDocs + 1;
                if ($row['assignedi9document'][0]['user_consent'] == 1) {
                    $totalDocsCompleted = $totalDocsCompleted + 1;
                } else {
                    $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                }
            }
            //
            if (!empty($row['assignedw9document'])) {
                $totalDocs = $totalDocs + 1;
                if ($row['assignedw9document'][0]['user_consent'] == 1) {
                    $totalDocsCompleted = $totalDocsCompleted + 1;
                } else {
                    $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                }
            }
            //
            if (!empty($row['assignedw4document'])) {
                $totalDocs = $totalDocs + 1;
                if ($row['assignedw4document'][0]['user_consent'] == 1) {
                    $totalDocsCompleted = $totalDocsCompleted + 1;
                } else {
                    $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                }
            }
            //
            if (!empty($row['assignedeeocdocument'])) {
                $totalDocs = $totalDocs + 1;
                if ($row['assignedeeocdocument'][0]['last_completed_on'] != '' && $row['assignedeeocdocument'][0]['last_completed_on'] != null) {
                    $totalDocsCompleted = $totalDocsCompleted + 1;
                } else {
                    $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                }
            }
            //
            if (count($row['assignedgeneraldocuments']) > 0) {
                foreach ($row['assignedgeneraldocuments'] as $rowGeneral) {

                    if ($rowGeneral['is_completed'] == 1) {
                        $totalDocsCompleted = $totalDocsCompleted + 1;
                    } else {
                        $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                    }
                }
            }
            //
            if (count($row['assigneddocuments']) > 0) {
                foreach ($row['assigneddocuments'] as $assigned_row) {

                    if ($assigned_row['completedStatus'] == 'Not Completed') {
                        $totalDocsNotCompleted = $totalDocsNotCompleted + 1;
                    }
                    if ($assigned_row['completedStatus'] == 'Completed') {
                        $totalDocsCompleted = $totalDocsCompleted + 1;
                    }

                    if ($assigned_row['completedStatus'] == 'No Action Required') {
                        $totalDocsNoAction = $totalDocsNoAction + 1;
                    }

                    if ($assigned_row['confidential_employees'] != null) {
                        $confidentialEmployees = explode(',', $assigned_row['confidential_employees']);

                        if (in_array($data['employerSid'], $confidentialEmployees)) {
                            //
                        } else {
                            $totalDocs = $totalDocs - 1;
                        }
                    }
                }
            }
            //
            $tmp = [
                getEmployeeOnlyNameBySID($row['sid'], true, true),
                $totalDocs,
                $totalDocsNotCompleted,
                $totalDocsCompleted,
                $totalDocsNoAction,
            ];
            //
            $totals["total"] += $tmp[1];
            $totals["not_completed"] += $tmp[2];
            $totals["completed"] += $tmp[3];
            $totals["on_action_required"] += $tmp[4];

            $data[] = $tmp;
        }

        //
        $data[] = [];
        $data[] = [
            "Summary",
            $totals["total"],
            $totals["not_completed"],
            $totals["completed"],
            $totals["on_action_required"],
        ];

        return ["name" => "document_report.csv", "data" => $data];
    }


    private function checkExecutiveAdmin($employeeSid)
    {

        $record = $this
            ->db
            ->select("is_executive_admin")
            ->where("sid", $employeeSid)
            ->limit(1)
            ->get("users")
            ->row_array();

        if ($record['is_executive_admin'] == 1) {
            return 1;
        } else {
            return 0;
        }
    }

    //
    public function sendScheduledDocumentReportToManagers()
    {
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $this->load->model('reports_model');
        $this->load->model('manage_admin/copy_employees_model');
        //
        $companies = $this->copy_employees_model->get_all_companies();
        //

        $executiveAdminsList = [];

        foreach ($companies as $companyRow) {
            //
            $post['companySid'] = $companyRow['sid'];
            $post['employeeSid'] = ['all'];
            $post['employeeStatus'] = [];
            $post['documentSid'] = ['all'];
            $post['documentAction'] = 'all';

            //
            $scheduleData = $this->reports_model->getsDocumentSchedule($companyRow['sid']);

            $sendEmail = 0;
            $curentData = '';
            $scheduleData['schedule_time'];
            $curentData = getSystemDate();
            $date = new DateTime($curentData);
            $systemTime = $date->format('H:i A');

            $scheduleTime = new DateTime($scheduleData['schedule_time']);
            $newScheduleTime = $scheduleTime->format('H:i A');

            $systemDay = $date->format('N');
            $systemTodayDateMonth = $date->format('m/d');

            $scheduleDatalog = $this->reports_model->getDocumentScheduleEmailLog($companyRow['sid'], $date->format('Y:m:d'));

            if (empty($scheduleDatalog)) {

                switch ($scheduleData['schedule_type']) {
                    case 'weekly':
                        if ($scheduleData['schedule_day'] == $systemDay) {
                            if ($newScheduleTime < $systemTime) {
                                $sendEmail = 1;
                            } elseif ($newScheduleTime > $systemTime) {
                                $sendEmail = 0;
                            } else {
                                $sendEmail = 1;
                            }
                        }

                        break;
                    case 'monthly':
                        $systemTodayDate = $date->format('d');
                        if ($scheduleData['schedule_date'] == $systemTodayDate) {
                            if ($newScheduleTime < $systemTime) {
                                $sendEmail = 1;
                            } elseif ($newScheduleTime > $systemTime) {
                                $sendEmail = 0;
                            } else {
                                $sendEmail = 1;
                            }
                        }

                        //                          
                        break;
                    case 'yearly':
                        if ($scheduleData['schedule_date'] == $systemTodayDateMonth) {
                            if ($newScheduleTime < $systemTime) {
                                $sendEmail = 1;
                            } elseif ($newScheduleTime > $systemTime) {
                                $sendEmail = 0;
                            } else {
                                $sendEmail = 1;
                            }
                        }

                        break;
                    case 'custom':
                        if ($scheduleData['schedule_date'] == $systemTodayDateMonth) {

                            if ($newScheduleTime < $systemTime) {
                                $sendEmail = 1;
                            } elseif ($newScheduleTime > $systemTime) {
                                $sendEmail = 0;
                            } else {
                                $sendEmail = 1;
                            }
                        }
                        break;
                    case "daily":

                        if ($newScheduleTime < $systemTime) {
                            $sendEmail = 1;
                        } elseif ($newScheduleTime > $systemTime) {
                            $sendEmail = 0;
                        } else {
                            $sendEmail = 1;
                        }

                        break;
                }

                if (!empty($scheduleData) && $sendEmail == 1) {
                    //
                    $employeeDocument = $this->reports_model->getEmployeeAssignedDocumentForReport($post);
                    //
                    if (sizeof($employeeDocument['Data'])) {
                        //
                        $report = $this->generateDocumentReport($employeeDocument);
                        //
                        // get notifiers
                        $managersList = get_notification_email_contacts(
                            $companyRow['sid'],
                            "scheduled_documents_status"
                        );

                        //
                        if (!empty($managersList)) {
                            //
                            $companyDetails = getCompanyInfo($companyRow['sid']);
                            // set common replace array
                            $companyReplaceArray = [
                                "{{company_name}}" => $companyDetails["company_name"],
                                "{{company_address}}" => $companyDetails["company_address"],
                                "{{company_phone}}" => $companyDetails["company_phone"],
                                "{{career_site_url}}" => $companyDetails["career_site_url"],
                            ];

                            //
                            foreach ($managersList as $key => $empRow) {
                                //
                                if ($empRow['is_executive_admin'] == 1) {
                                    //
                                    $viewCode = str_replace(
                                        ['/', '+'],
                                        ['$$ab$$', '$$ba$$'],
                                        $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'view')
                                    );
                                    //
                                    $downloadCode = str_replace(
                                        ['/', '+'],
                                        ['$$ab$$', '$$ba$$'],
                                        $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'download')
                                    );

                                    //
                                    $viewLink = base_url("hr_documents_management/manager_report") . '/' . $viewCode;
                                    $downloadLink = base_url("hr_documents_management/manager_report") . '/' . $downloadCode;

                                    $buttons = '';
                                    $forCompany = "From: " . $companyDetails["company_name"] . "<br>";
                                    $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
                                    $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';
                                    $buttons .= $forCompany . $viewReport . '&nbsp;&nbsp;&nbsp;' . $downloadReport;

                                    $executiveAdminsList[$empRow['email']]['data']['buttons'][] = $buttons;
                                    $executiveAdminsList[$empRow['email']]['data']['contact_name'] = $empRow['contact_name'];
                                    $executiveAdminsList[$empRow['email']]['data']['email'] = $empRow['email'];
                                } else {

                                    $template = get_email_template(DOCUMENT_REPORT_EMAILS);
                                    //
                                    $templateSubject = $template["subject"];
                                    $templateFromName = $template["from_name"];
                                    $templateBody = $template["text"];

                                    // set replace array
                                    //
                                    $replaceArray = $companyReplaceArray;
                                    //
                                    $replaceArray["{{baseurl}}"] = base_url();
                                    $replaceArray["{{full_name}}"] =
                                        $replaceArray["{{contact_name}}"]
                                        = $empRow['contact_name'];
                                    //
                                    $viewCode = str_replace(
                                        ['/', '+'],
                                        ['$$ab$$', '$$ba$$'],
                                        $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'view')
                                    );
                                    //
                                    $downloadCode = str_replace(
                                        ['/', '+'],
                                        ['$$ab$$', '$$ba$$'],
                                        $this->encryption->encrypt($companyRow['sid'] . '/' . $empRow['employer_sid'] . '/' . 'download')
                                    );
                                    //
                                    $viewLink = base_url("hr_documents_management/manager_report") . '/' . $viewCode;
                                    $downloadLink = base_url("hr_documents_management/manager_report") . '/' . $downloadCode;
                                    //
                                    $viewReport = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $viewLink . '" target="_blank">View Report</a>';
                                    $downloadReport = '<a style="background-color: #fd7a2a; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . $downloadLink . '" target="_blank">Download Report</a>';

                                    //
                                    $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
                                    $templateBody = str_replace('{{download_report}}', $downloadReport, $templateBody);

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
                                    //

                                    log_and_send_email_with_attachment(
                                        FROM_EMAIL_NOTIFICATIONS,
                                        $empRow['email'],
                                        $subject,
                                        $body,
                                        $fromName,
                                        $report,
                                        "sendMailWithAttachmentAsString"
                                    );
                                }
                            }
                        }
                    }
                }
            }
            //
            $this->reports_model->saveDocumentScheduleEmailLog($companyRow['sid'], $date->format('Y:m:d'));
        }

        //
        if (!empty($executiveAdminsList)) {
            foreach ($executiveAdminsList as $exRow) {
                $companyReplaceArray = [
                    "{{company_name}}" => '',
                    "{{company_address}}" => '',
                    "{{company_phone}}" => '',
                    "{{career_site_url}}" => '',
                ];

                $template = get_email_template(DOCUMENT_REPORT_EMAILS);
                //
                $templateSubject = $template["subject"];
                $templateFromName = $template["from_name"];
                $templateBody = $template["text"];
                // set replace array
                $replaceArray = $companyReplaceArray;
                //
                $replaceArray["{{baseurl}}"] = base_url();
                $replaceArray["{{full_name}}"] =
                    $replaceArray["{{contact_name}}"]
                    = $exRow['data']['contact_name'];

                $viewReport = '';
                foreach ($exRow['data']['buttons'] as $buttonRow) {
                    $viewReport .= "<br><br>" . $buttonRow;
                }

                $templateBody = str_replace('{{view_report}}', $viewReport, $templateBody);
                $templateBody = str_replace('{{download_report}}', '', $templateBody);

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
                    $exRow["data"]['email'],
                    $subject,
                    $body,
                    $fromName,
                    $report,
                    "sendMailWithAttachmentAsString"
                );
            }
        }

        echo "done";
    }
}