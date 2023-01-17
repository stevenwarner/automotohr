<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_common extends CI_Controller
{
    //
    private $verifyToken;

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        //
        $this->verifyToken = getCreds('AHR')->VerifyToken;
    }

    function tos()
    {
        //
        $this->common_model->startR();
        $this->common_model->endR();
    }

    function auto_email_reminder($verificationToken)
    {
        //
        if ($this->verifyToken != $verificationToken) {
            echo "Failed";
            exit(0);
        }
        //
        $records = $this->common_model->get_all_licenses();
        //
        if (empty($records)) {
            exit(0);
        }
        //
        $todaysDate = date('Y-m-d', strtotime('now'));
        //
        $this->load->model('common_ajax_model');
        //
        foreach ($records as $record) {
            //
            $expiryDate = !empty($record['license_details']['license_expiration_date']) ? trim($record['license_details']['license_expiration_date']) : '';
            // //
            if (empty($expiryDate)) {
                continue;
            }
            //
            $expiryDate = str_replace('/', '-', $expiryDate);
            //
            $format = 'Y-m-d';
            // Re-format the date
            if (preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $expiryDate)) {
                $format = 'm-d-Y';
            }
            //
            $expiryDate = DateTime::createfromformat($format, $expiryDate)->format('Y-m-d');
            //
            $difference = dateDifferenceInDays($expiryDate, $todaysDate);
            //
            $days = 0;
            //
            if ($difference == 15) {
                $days = 15;
            } else if ($difference == 7) {
                $days = 7;
            } else if ($difference == 3) {
                $days = 3;
            }
            //
            if ($days != 0) {
                //
                $type = $record['license_type'] == 'drivers' ? 'drivers-license' : 'occupational-license';
                //
                $note = "Your {$record['license_type']} license is expiring in {$days} days.";
                //
                $email_hf = message_header_footer($record['CompanyId'], ucwords($record['CompanyName']));
                // Let's record the action
                $this->common_ajax_model->send_reminder_email_record([
                    'userId' => $record['sid'],
                    'userType' => 'employee',
                    'moduleType' => $type,
                    'note' => $note,
                    'lastSenderSid' => 0
                ]);
                // Email Send
                $this->send_email_reminder($type, $note, [
                    'first_name' => $record['first_name'],
                    'last_name' => $record['last_name'],
                    'email' => $record['email']
                ], [
                    'CompanyName' => $record['CompanyName'],
                    'Location_Address' => $record['Location_Address'],
                    'PhoneNumber' => $record['PhoneNumber'],
                ], $email_hf);
                // Last Send Date Update
                $this->common_model->update_license_last_sent_date($record['licenseId']);
            }
        }
        echo "Executed \n";
        //
        exit(0);
    }

    //
    private function send_email_reminder($type, $note, $user_detail, $company_detail, $email_hf)
    {
        //
        $this->load->model('common_ajax_model');
        // Set link
        $link = '<a href="' . (base_url('general_info')) . '" style="padding: 10px; color: #ffffff; background-color: #0000ff; border-radius: 5px;">Go To Document</a>';
        //
        $email_slug = $type . '-reminder-email';
        // Get email template
        $template = $this->common_ajax_model->get_email_template_by_code($email_slug);
        // Set replace array
        $replaceArray = [];
        $replaceArray['{{first_name}}'] = ucwords($user_detail['first_name']);
        $replaceArray['{{last_name}}'] = ucwords($user_detail['last_name']);
        $replaceArray['{{company_name}}'] = ucwords($company_detail['CompanyName']);
        $replaceArray['{{company_address}}'] = $company_detail['Location_Address'];
        $replaceArray['{{company_phone}}'] = $company_detail['PhoneNumber'];
        $replaceArray['{{career_site_url}}'] = 'https://' . $email_hf['sub_domain'];
        $replaceArray['{{note}}'] = "<strong>Note:</strong>" . $note;
        $replaceArray['{{link}}'] = $link;
        //
        $indexes = array_keys($replaceArray);
        // Change subject
        $subject = str_replace($indexes, $replaceArray, $template['subject']);
        $body = $email_hf['header'] . str_replace($indexes, $replaceArray, $template['text']) . $email_hf['footer'];
        //
        $from_email = empty($template['from_email']) ? FROM_EMAIL_NOTIFICATIONS : $template['from_email'];
        $from_name = empty($template['from_name']) ? ucwords($company_detail['CompanyName']) : str_replace($indexes, $replaceArray, $template['from_name']);
        //
        log_and_sendEmail($from_email, $user_detail['email'], $subject, $body, $from_name);
    }


    /**
     * 
     */
    function PMMCronStartAndEndReplicate($verificationToken)
    {
        //
        if ($verificationToken != $this->verifyToken) {
            echo "All done!";
            exit(0);
        }
        //
        $this->load->model('performance_management_model', 'pmm');
        //
        $records = $this->pmm->GetReviewsForCron();
        //
        if (empty($records)) {
            exit(0);
        }
        //
        $now = date('Y-m-d', strtotime('now'));
        //
        foreach ($records as $record) {
            //
            $insertArray = [];
            //
            if (isset($reviewId)) {
                unset($reviewId);
            }
            //
            if (isset($activeRecord)) {
                unset($activeRecord);
            }
            // Case 3
            if ($record['frequency'] == 'custom') {
                //
                $activeRecord = $record;
                //
                $customRuns = json_decode($activeRecord['review_runs'], true);
                //
                $dueIn = $activeRecord['review_due'];
                //
                $dueInType = $activeRecord['review_due_type'];
                //
                if ($dueInType == 'days') {
                    $dueInType = 'D';
                } else if ($dueInType == 'weeks') {
                    $dueInType = 'W';
                } else if ($dueInType == 'months') {
                    $dueInType = 'M';
                }
                //
                $repeat = $activeRecord['repeat_review']; // todo
                //
                $employeesList = array_diff(explode(',', $activeRecord['included_employees']), explode(',', $activeRecord['excluded_employees']));
                //
                $employeesList = $this->pmm->GetEmployeeColumns($employeesList, ['sid', 'joined_at', 'registration_date', 'rehire_date']);
                //
                $selectedEmployees = [];
                //
                $i = 0;
                //
                foreach ($employeesList as $employee) {
                    //
                    foreach ($customRuns as $run) {
                        //
                        if ($run['type'] == 'days') {
                            $run['type'] = 'D';
                        } else if ($run['type'] == 'weeks') {
                            $run['type'] = 'W';
                        } else if ($run['type'] == 'months') {
                            $run['type'] = 'M';
                        }
                        //
                        $latest_joining = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
                        //
                        $runDate = addTimeToDate($latest_joining, "{$run['value']}{$run['type']}", 'Y-m-d');
                        //
                        if ($runDate == $now) {
                            $selectedEmployees[] = $employee['sid'];
                            break;
                        }
                    }
                }

                //
                if (!empty($selectedEmployees)) {
                    // Insert new record here
                    $insertArray = $activeRecord;
                    $insertArray['parent_review_sid'] = $record['sid'];
                    $insertArray['review_start_date'] = $now;
                    $insertArray['review_end_date'] = addTimeToDate($insertArray['review_start_date'], "{$dueIn}{$dueInType}", 'Y-m-d');
                    $insertArray['included_employees'] = implode(',', $selectedEmployees);
                    $insertArray['excluded_employees'] = '';
                    //
                    unset($insertArray['Cycles']);
                    unset($insertArray['sid']);
                    //
                    $reviewId = $this->pmm->InsertReview($insertArray);
                }
            }
            // Case 2
            if ($record['frequency'] == 'recurring') {

                // Lets check for sub record
                if (!empty($record['Cycles'])) { // Sub record found
                    $activeRecord = $record['Cycles'][0];
                } else { // Sub record not found
                    $activeRecord = $record;
                }
                //
                $repeatValue = $activeRecord['repeat_after'];
                //
                $repeatType = $activeRecord['repeat_type'];
                //
                if ($repeatType == 'days') {
                    $repeatType = 'D';
                } else if ($repeatType == 'weeks') {
                    $repeatType = 'W';
                } else if ($repeatType == 'months') {
                    $repeatType = 'M';
                }
                //
                $compareDate = addTimeToDate($activeRecord['review_end_date'], "{$repeatValue}{$repeatType}", 'Y-m-d');
                //
                if (date('Y-m-d', strtotime('now')) == $compareDate) {
                    // Insert new record here
                    $insertArray = $activeRecord;
                    $insertArray['parent_review_sid'] = $record['sid'];
                    $insertArray['review_start_date'] = date('Y-m-d', strtotime('now'));
                    $insertArray['review_end_date'] = addTimeToDate($insertArray['review_start_date'], "{$repeatValue}{$repeatType}", 'Y-m-d');
                    //
                    unset($insertArray['Cycles']);
                    unset($insertArray['sid']);
                    //
                    $reviewId = $this->pmm->InsertReview($insertArray);
                }
            }

            // Add reviewers and reviewees
            if (isset($reviewId)) {
                // Get the review
                $review = $this->pmm->GetReviewRowById($reviewId, $activeRecord['company_sid']);

                // Set the questions
                $questions = json_decode($review['questions'], true);
                //
                $ins = [];
                //
                foreach ($questions as $question) {
                    //
                    $ins[] = [
                        'review_sid' => $review['reviewId'],
                        'question_type' => $question['question_type'],
                        'question' => json_encode($question),
                        'created_at' => $now
                    ];
                }
                //
                $this->pmm->insertReviewQuestions($ins);

                // Set reviwees
                //
                $ins = [];
                //
                $reviewees = explode(',', $review['included']);
                //
                foreach ($reviewees as $reviewee) {
                    //
                    $ins[] = [
                        'review_sid' => $review['reviewId'],
                        'reviewee_sid' => $reviewee,
                        'start_date' => $review['start_date'],
                        'end_date' => $review['end_date'],
                        'created_at' => $now,
                        'updated_at' => $now,
                        'is_started' => 0
                    ];
                }
                //
                $this->pmm->insertReviewReviewees($ins);


                // Set reviwers
                //
                $ins = [];
                //
                $reviewers = json_decode($review['reviewers'], true);
                //
                foreach ($reviewers['reviewees'] as $reviewee => $reviewer) {
                    //
                    $newReviewers = array_diff($reviewer['included'], isset($reviewer['excluded']) ? $reviewer['excluded'] : []);
                    //
                    foreach ($newReviewers as $newReviewer) {
                        //
                        $ins[] = [
                            'review_sid' => $review['reviewId'],
                            'reviewee_sid' => $reviewee,
                            'reviewer_sid' => $newReviewer,
                            'added_by' => 0,
                            'created_at' => $now,
                            'is_manager' => $this->pmm->isManager($reviewee, $newReviewer, $activeRecord['company_sid']),
                            'is_completed' => 0
                        ];
                    }
                }
                //
                $this->pmm->insertReviewReviewers($ins);
            }

            // Lets check for sub record
            if (!empty($record['Cycles'])) { // Sub record found
                $activeRecord = $record['Cycles'][0];
            } else { // Sub record not found
                $activeRecord = $record;
            }

            // Start and end reviews
            // Review will start
            if ($activeRecord['review_start_date'] == $now && $activeRecord['review_end_date'] > $now) {
                $this->pmm->UpdateReview(['status' => "started"], $activeRecord['sid']);
                //
            }

            // Review will end
            if ($activeRecord['review_end_date'] <= $now) {
                $this->pmm->UpdateReview(['status' => "ended"], $activeRecord['sid']);
            }
            // Check and start/end reviews 
            $this->pmm->CheckAndStartEndReviewees($now, $activeRecord['sid']);
        }

        //
        echo "All done";
        exit(0);
    }


    /**
     * 
     */
    function SendNotificationEmails($verificationToken)
    {
        //
        if ($verificationToken != $this->verifyToken) {
            echo "All done!";
            exit(0);
        }
        //
        $this->load->model('performance_management_model', 'pmm');
        //
        $toArray = [];
        //
        $this->getEmployeesForNotification(7, $toArray);
        $this->getEmployeesForNotification(3, $toArray);
        $this->getEmployeesForNotification(1, $toArray);
        //
        if (empty($toArray)) {
            echo "All Done!";
            exit(0);
        }
        // Get template
        $template = get_email_template(REVIEW_EXPIRING);
        //
        $this->load->model('Hr_documents_management_model', 'HRDMM');
        foreach ($toArray as $record) {
            //
            if (!$this->HRDMM->isActiveUser($record['userId'])) {
                continue;
            }
            //
            $hf = message_header_footer($record['companyId'], $record['companyName']);
            //
            $indexes = array_keys($record['replace']);
            //
            $subject = str_replace($indexes, $record['replace'], $template['subject']);
            //
            $body = $hf['header'] . str_replace($indexes, $record['replace'], $template['text']) . $hf['footer'];
            //
            log_and_sendEmail(
                $template['from_email'],
                $record['email'],
                $subject,
                $body,
                $record['name']
            );
            //
            usleep(100);
        }
        //
        echo "All Done!";
        exit(0);
    }

    /**
     * 
     */
    private function getEmployeesForNotification($days, &$toArray)
    {
        //
        $records = $this->pmm->GetEmployeesForNotificationEmailByDays($days);
        //
        if (!empty($records)) {
            foreach ($records as $record) {
                //
                $reviewPeriod = formatDateToDB($record['start_date'], 'Y-m-d', 'M d Y, D');
                $reviewPeriod .= ' - ' . formatDateToDB($record['end_date'], 'Y-m-d', 'M d Y, D');
                //
                $link = base_url("performance-management/" . ($record['is_manager'] ? "feedback" : "review") . "/" . ($record['sid']) . "/" . ($record['reviewee_sid']) . "/" . ($record['reviewer_sid']) . "");
                //
                // Set replace array 
                $replaceArray = [];
                $replaceArray['{{first_name}}'] = ucwords($record['first_name']);
                $replaceArray['{{last_name}}'] = ucwords($record['last_name']);
                $replaceArray['{{expiring_days}}'] = $days;
                $replaceArray['{{review_title}}'] = ucwords($record['review_title']);
                $replaceArray['{{review_period}}'] = $reviewPeriod;
                $replaceArray['{{reviewee_first_name}}'] = ucwords($record['reviewer_first_name']);
                $replaceArray['{{reviewee_last_name}}'] = ucwords($record['reviewer_last_name']);
                $replaceArray['{{link}}'] = getButtonForEmail(['{{url}}' => $link, '{{text}}' => 'Complete The Review', '{{color}}' => '#0000ff']);
                //
                $toArray[] = [
                    "companyId" => $record['company_sid'],
                    "companyName" => ucwords(strtolower(trim($record['company_name']))),
                    "email" => strtolower(trim($record['reviewer_email'])),
                    "name" => ucwords(strtolower(trim($record['reviewer_first_name'] . ' ' . $record['reviewer_last_name']))),
                    "replace" => $replaceArray
                ];
            }
        }
    }

    /**
     * Employee rehire status fixer
     */
    public function employeeStatusFixer()
    {
        // $this->employeeStatusRehiredFixer();
        $this->employeeStatusTerminatedFixer();
    }

    /**
     * Employee rehire status fixer
     */
    public function employeeStatusRehiredFixer()
    {
        //
        $employees = $this->db
            ->select('sid')
            ->where([
                'general_status' => 'rehired',
                'active' => 0
            ])
            ->get('users')
            ->result_array();
        //
        if (empty($employees)) {
            return;
        }
        //
        $ids = array_column($employees, 'sid');
        //
        $rows = [];
        //
        $lastStatuses =
            $this->db
            ->select('employee_status, employee_sid, termination_date')
            ->where_in('employee_sid', $ids)
            ->order_by('sid', 'desc')
            ->get('terminated_employees')
            ->result_array();
        // Save the last record of each employee
        foreach ($lastStatuses as $status) {
            //
            if (!isset($rows[$status['employee_sid']])) {
                $rows[$status['employee_sid']] = $status;
            }
        }
        //
        $handler = fopen(ROOTPATH . '../app_logs/' . time() . '-rehired.json', 'w');
        fwrite($handler, json_encode(['ids' => $ids, 'status' => $rows]));
        fclose($handler);
        //
        $found = 0;
        $notFound = 0;
        // Loop through the original ids
        foreach ($ids as $id) {
            //
            $upd = [];
            // Check if record found in terminated
            if (isset($rows[$id])) {
                // Check the last status
                // for rehired
                if (in_array($rows[$id]['employee_status'], [8])) {
                    $upd['rehire_date'] = $rows[$id]['termination_date'];
                    $upd['active'] = 1;
                }
                // for active
                if (in_array($rows[$id]['employee_status'], [5])) {
                    $upd['general_status'] = 'active';
                    $upd['active'] = 1;
                }

                $found++;
            } else {
                //
                $upd['active'] = 1;
                $upd['terminated_status'] = 0;
                //
                $notFound++;
            }
            //
            $this->db
                ->reset_query()
                ->set($upd)
                ->where('sid', $id)
                ->update('users');
        }
        //
        _e($found, true);
        _e($notFound, true, true);
    }

    /**
     * Employee terminated status fixer
     */
    public function employeeStatusTerminatedFixer()
    {
        //
        $employees = $this->db
            ->select('sid, general_status')
            ->where([
                'general_status <> ' => 'terminated',
                'active' => 1,
                'terminated_status' => 1
            ])
            ->get('users')
            ->result_array();
        //
        if (empty($employees)) {
            return;
        }
        _E($employees, true, true);

        //
        $ids = array_column($employees, 'sid');
        //
        $rows = [];
        //
        $lastStatuses =
            $this->db
            ->select('employee_status, employee_sid, termination_date')
            ->where_in('employee_sid', $ids)
            ->order_by('sid', 'desc')
            ->get('terminated_employees')
            ->result_array();
        // Save the last record of each employee
        foreach ($lastStatuses as $status) {
            //
            if (!isset($rows[$status['employee_sid']])) {
                $rows[$status['employee_sid']] = $status;
            }
        }

        //
        $handler = fopen(ROOTPATH . '../app_logs/' . time() . '-terminated.json', 'w');
        fwrite($handler, json_encode(['ids' => $ids, 'status' => $rows]));
        fclose($handler);
        //
        $found = 0;
        $notFound = 0;
        // Loop through the original ids
        foreach ($ids as $id) {
            //
            $upd = [];
            // Check if record found in terminated
            if (isset($rows[$id])) {
                // Transform
                $employeeLastStatus = strtolower(GetEmployeeStatusText($rows[$id]['employee_status']));
                //
                // Check the last status
                // for rehired
                if (
                    $employeeLastStatus == 'rehired'
                    || $employeeLastStatus == 'active'
                ) {
                    $upd['terminated_status'] = 0;
                }

                $found++;
            } else {
                //
                $notFound++;
            }
            if (!empty($upd)) {
                //
                $this->db
                    ->reset_query()
                    ->set($upd)
                    ->where('sid', $id)
                    ->update('users');
            }
        }
        //
        _e($found, true);
        _e($notFound, true, true);
    }

    /**
     * Get and set Google holidays
     */
    public function holidayShifter($companyId = 0)
    {
        // Get public holidays
        $publicHolidays = getCurrentYearHolidaysFromGoogle();
        //
        $ph = [];
        //
        if ($publicHolidays) {
            foreach ($publicHolidays as $holiday) {
                $ph[preg_replace('/[^a-zA-Z]/', '', strtolower(trim($holiday['holiday_title'])))] = $holiday;
            }
        }
        //
        $where = [
            'holiday_year' => date('Y', strtotime('-1 year'))
        ];
        //
        if ($companyId != 0) {
            $where['company_sid'] = $companyId;
        }
        // Get all companies
        $holidays = $this->db
            ->where($where)
            ->get('timeoff_holidays')
            ->result_array();
        //
        $nf = [
            'found' => 0,
            'notFound' => 0
        ];
        //
        if (empty($holidays)) {
            // No holiday of companies are found
            exit(0);
        } else {
            //
            $year = date('Y', strtotime('now'));
            $lastYear = date('Y', strtotime('-1 year'));
            //
            foreach ($holidays as $holiday) {
                //
                $title = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($holiday['holiday_title'])));
                //
                $doExists = $this->db
                    ->where([
                        'holiday_title' => $holiday['holiday_title'],
                        'company_sid' => $holiday['company_sid'],
                        'holiday_year' => $year
                    ])
                    ->count_all_results('timeoff_holidays');
                //
                if (
                    $doExists
                ) {
                    $nf['found']++;
                    continue;
                }
                //
                $ins = $holiday;
                //
                unset($ins['sid'], $ins['created_at'], $ins['updated_at']);
                //
                $ins['holiday_year'] = $year;
                $ins['created_at'] = $ins['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
                //
                if (isset($ph[$title])) {
                    $ins['from_date'] = $ph[$title]['from_date'];
                    $ins['to_date'] = $ph[$title]['to_date'];
                } else {
                    $ins['from_date'] = preg_replace("/$lastYear/", $year, $ins['from_date']);
                    $ins['to_date'] = preg_replace("/$lastYear/", $year, $ins['to_date']);
                }
                //
                $this->db->insert('timeoff_holidays', $ins);
                //
                $nf['notFound']++;
            }
        }
        //
        _e($nf, true);
        exit(0);
    }


    /**
     * Sends the information
     */
    public function issueNotificationGenerator()
    {
        $path = '/tmp';
        $result = shell_exec("df -H --output=pcent $path");
        $tmp = explode("\n", $result);
        $values = preg_replace('/[^0-9]/', '', $tmp[1]);
        //
        $filename = ROOTPATH . '../app_logs/tmp.txt';
        //
        $mode = 'a';
        //
        if (!is_file($filename)) {
            $mode = 'w';
        }
        //
        $handler = fopen($filename, $mode);
        //
        fwrite($handler, "[" . (date('c')) . "] $path $values\n");
        //
        fclose($handler);
        //
        if ($values >= 50) {
            @mail('mkhurram@egenienext.com', "/tmp memory exceeded to $values", $result);
            mail('mubashar@automotohr.com', "/tmp memory exceeded to $values", $result);
        }
    }
}
