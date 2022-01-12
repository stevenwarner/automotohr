<?php defined('BASEPATH') || exit('No direct script access allowed');


class Cron_common extends CI_Controller{
    //
    private $verifyToken;

    function __construct(){
        parent::__construct();
        $this->load->model('common_model');
        //
        $this->verifyToken = getCreds('AHR')->VerifyToken;
    }

    function tos(){
        //
        $this->common_model->startR();
        $this->common_model->endR();
    }

    //
    function auto_email_reminder($verificationToken){
        //
        if($this->verifyToken != $verificationToken){
            echo "Failed";
            exit(0);
        }
        //
        $records = $this->common_model->get_all_licenses();
        //
        if(empty($records)){
            exit(0);
        }
        //
        $todaysDate = date('Y-m-d', strtotime('now'));
        //
        $this->load->model('common_ajax_model');
        //
        foreach($records as $record){
            //
            $expiryDate = !empty($record['license_details']['license_expiration_date']) ? trim($record['license_details']['license_expiration_date']) : '';
            // //
            if(empty($expiryDate)){
                continue;
            }
            //
            $expiryDate = str_replace('/', '-', $expiryDate);
            //
            $format = 'Y-m-d';
            // Re-format the date
            if(preg_match('/[0-9]{2}-[0-9]{2}-[0-9]{4}/', $expiryDate)){
                $format = 'm-d-Y';
            }
            //
            $expiryDate = DateTime::createfromformat($format, $expiryDate)->format('Y-m-d');
            //
            $difference = dateDifferenceInDays($expiryDate, $todaysDate);
            //
            $days = 0;
            //
            if($difference == 15){
                $days = 15;
            } else if($difference == 7){
                $days = 7;
            } else if($difference == 3){
                $days = 3;
            }
            //
            if($days != 0){
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
    private function send_email_reminder($type, $note, $user_detail, $company_detail, $email_hf){
        //
        $this->load->model('common_ajax_model');
        // Set link
        $link = '<a href="'.(base_url('general_info')).'" style="padding: 10px; color: #ffffff; background-color: #0000ff; border-radius: 5px;">Go To Document</a>';
        //
        $email_slug = $type.'-reminder-email';
        // Get email template
        $template = $this->common_ajax_model->get_email_template_by_code($email_slug);
        // Set replace array
        $replaceArray = [];
        $replaceArray['{{first_name}}'] = ucwords($user_detail['first_name']);
        $replaceArray['{{last_name}}'] = ucwords($user_detail['last_name']);
        $replaceArray['{{company_name}}'] = ucwords($company_detail['CompanyName']);
        $replaceArray['{{company_address}}'] = $company_detail['Location_Address'];
        $replaceArray['{{company_phone}}'] = $company_detail['PhoneNumber'];
        $replaceArray['{{career_site_url}}'] = 'https://'.$email_hf['sub_domain'];
        $replaceArray['{{note}}'] = "<strong>Note:</strong>".$note;
        $replaceArray['{{link}}'] = $link;
        //
        $indexes = array_keys($replaceArray);
        // Change subject
        $subject = str_replace($indexes, $replaceArray, $template['subject']);
        $body = $email_hf['header'].str_replace($indexes, $replaceArray, $template['text']).$email_hf['footer'];
        //
        $from_email = empty($template['from_email']) ? FROM_EMAIL_NOTIFICATIONS : $template['from_email'];
        $from_name = empty($template['from_name']) ? ucwords($company_detail['CompanyName']) : str_replace($indexes, $replaceArray, $template['from_name']);
        //
        log_and_sendEmail($from_email, $user_detail['email'], $subject, $body, $from_name);
    }


    /**
     * 
     */
    function PMMCronStartAndEndReplicate($verificationToken){
        //
        if($verificationToken != $this->verifyToken){
            echo "All done!";
            exit(0);
        }
        //
        $this->load->model('performance_management_model', 'pmm');
        //
        $records = $this->pmm->GetReviewsForCron();
        //
        if(empty($records)){
            exit(0);
        }
        //
        $now = date('Y-m-d', strtotime('now'));
        //
        foreach($records as $record){
            //
            $insertArray = [];
            //
            if(isset($reviewId)){ unset($reviewId); }
            //
            if(isset($activeRecord)){ unset($activeRecord); }
            // Case 3
            if($record['frequency'] == 'custom'){
                //
                $activeRecord = $record;
                //
                $customRuns = json_decode($activeRecord['review_runs'], true);
                //
                $dueIn = $activeRecord['review_due'];
                //
                $dueInType = $activeRecord['review_due_type'];
                //
                if($dueInType == 'days'){ $dueInType = 'D' ;}
                else if($dueInType == 'weeks'){ $dueInType = 'W' ;}
                else if($dueInType == 'months'){ $dueInType = 'M' ;}
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
                foreach($employeesList as $employee){
                    //
                    foreach($customRuns as $run){
                        //
                        if($run['type'] == 'days'){ $run['type'] = 'D' ;}
                        else if($run['type'] == 'weeks'){ $run['type'] = 'W' ;}
                        else if($run['type'] == 'months'){ $run['type'] = 'M' ;}
                        //
                        $latest_joining = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
                        //
                        $runDate = addTimeToDate($latest_joining, "{$run['value']}{$run['type']}", 'Y-m-d');
                        //
                        if($runDate == $now){
                            $selectedEmployees[] = $employee['sid'];
                            break;
                        }
                    }
                }

                //
                if(!empty($selectedEmployees)){
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
            if($record['frequency'] == 'recurring'){

                // Lets check for sub record
                if(!empty($record['Cycles'])){ // Sub record found
                    $activeRecord = $record['Cycles'][0];
                } else{ // Sub record not found
                    $activeRecord = $record;
                }
                //
                $repeatValue = $activeRecord['repeat_after'];
                //
                $repeatType = $activeRecord['repeat_type'];
                //
                if($repeatType == 'days'){ $repeatType = 'D' ;}
                else if($repeatType == 'weeks'){ $repeatType = 'W' ;}
                else if($repeatType == 'months'){ $repeatType = 'M' ;}
                //
                $compareDate = addTimeToDate($activeRecord['review_end_date'], "{$repeatValue}{$repeatType}", 'Y-m-d');
                //
                if(date('Y-m-d', strtotime('now')) == $compareDate){
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
            if(isset($reviewId)){
                // Get the review
                $review = $this->pmm->GetReviewRowById($reviewId, $activeRecord['company_sid']);

                // Set the questions
                $questions = json_decode($review['questions'], true);
                //
                $ins = [];
                //
                foreach($questions as $question){
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
                foreach($reviewees as $reviewee){
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
                foreach($reviewers['reviewees'] as $reviewee => $reviewer){
                    //
                    $newReviewers = array_diff($reviewer['included'], isset($reviewer['excluded']) ? $reviewer['excluded'] : []);
                    //
                    foreach($newReviewers as $newReviewer){
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
            if(!empty($record['Cycles'])){ // Sub record found
                $activeRecord = $record['Cycles'][0];
            } else{ // Sub record not found
                $activeRecord = $record;
            }

            // Start and end reviews
            // Review will start
            if($activeRecord['review_start_date'] == $now && $activeRecord['review_end_date'] > $now){
                $this->pmm->UpdateReview(['status' => "started"], $activeRecord['sid']);
                //
            }
            
            // Review will end
            if($activeRecord['review_end_date'] <= $now){
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
    function SendNotificationEmails($verificationToken){
        //
        if($verificationToken != $this->verifyToken){
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
        if(empty($toArray)){
            echo "All Done!";
            exit(0);
        }
        // Get template
        $template = get_email_template(REVIEW_EXPIRING);
        //
        foreach($toArray as $record){
            //
            $hf = message_header_footer($record['companyId'], $record['companyName']);
            //
            $indexes = array_keys($record['replace']);
            //
            $subject = str_replace($indexes, $record['replace'], $template['subject']);
            //
            $body = $hf['header'].str_replace($indexes, $record['replace'], $template['text']).$hf['footer'];
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
    private function getEmployeesForNotification($days, &$toArray){
        //
        $records = $this->pmm->GetEmployeesForNotificationEmailByDays($days);
        //
        if(!empty($records)){
            foreach($records as $record) {
                //
                $reviewPeriod = formatDateToDB($record['start_date'], 'Y-m-d', 'M d Y, D');
                $reviewPeriod .= ' - '.formatDateToDB($record['end_date'], 'Y-m-d', 'M d Y, D');
                //
                $link = base_url("performance-management/".($record['is_manager'] ? "feedback" : "review")."/".($record['sid'])."/".($record['reviewee_sid'])."/".($record['reviewer_sid'])."");
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
                $replaceArray['{{link}}'] = getButtonForEmail(['{{url}}' => $link, '{{text}}' => 'Complete The Review', '{{color}}'=> '#0000ff']);
                //
                $toArray[] = [
                    "companyId" => $record['company_sid'],
                    "companyName" => ucwords(strtolower(trim($record['company_name']))),
                    "email" => strtolower(trim($record['reviewer_email'])),
                    "name" => ucwords(strtolower(trim($record['reviewer_first_name'].' '.$record['reviewer_last_name']))),
                    "replace" => $replaceArray
                ];
            }
        }
    }

}