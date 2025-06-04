<?php defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . 'libraries/aws/aws.php');
ini_set('memory_limit', '50M');

class Zip_recruiter_organic extends CI_Controller
{

    private $path;
    private $debug_email = TO_EMAIL_DEV;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('all_feed_model');
        $this->load->model('users_model');
    }
    /**
     * 
     */
    private function addLastRead($sid)
    {
        $this->db
            ->where('sid', $sid)
            ->set([
                'last_read' => date('Y-m-d H:i:s', strtotime('now')),
                'referral' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : ''
            ])->update('job_feeds_management');
        //
        $this->db
            ->insert('job_feeds_management_history', [
                'feed_id' => $sid,
                'referral' => !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
                'created_at' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
    }
    /**
     * 
     */
    private function addReport($source, $email, $type = 'add')
    {
        if ($type == 'add') {
            $this->db
                ->insert('daily_job_counter', [
                    'source' => $source,
                    'email' => $email,
                    'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'already_exists' => 0
                ]);
        } else {
            $this->db
                ->where('email', $email)
                ->where('created_at', date('Y-m-d H:i:s', strtotime('now')))
                ->update('daily_job_counter', [
                    'already_exists' => 1
                ]);
        }
    }

    public function index($generateXML = null)
    {
        $sid = $this->isActiveFeed();
        $this->addLastRead(4);
        $isOld = TRUE;
        // Added on: 05-08-2019
        // Load the XML file ifgenerate check is false
        //if($generateXML == 'dwwbtQzuoHI9d5TEIKBKDGWwuioGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk5HV7ZNCbu2ZfkjFVLHWINnM5uzMkUfIiINdZ19NJj' ) { $isOld = FALSE; $this->index_new(); $isOld = TRUE; }
        //  mail(TO_EMAIL_DEV, 'Feed XML - Zip: ' . date('Y-m-d H:i:s'), 'Pinged');
        //
        $featuredArray = array();
        // For old flow
        if ($isOld) {
            //
            $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
            $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);
            $rows = '';

            $i = 0;
            $featuredArray[$i] = "";

            foreach ($featuredJobs as $featuredId) {
                $featuredArray[$i] = $featuredId['jobId'];
                $i++;
            }

            $organicJobData = $this->all_feed_model->get_all_organic_jobs($featuredArray);
        }
        $featured = 0;
        $expiryDate = "";

        //
        $newArray = array();

        foreach ($organicJobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                if (!isset($companyPortal['sub_domain']))
                    continue;
                $companyDetail = $this->all_feed_model->get_company_detail($company_id);
                $companyName = $companyDetail['CompanyName'];
                $has_job_approval_rights = $companyDetail['has_job_approval_rights'];
                $companyLogo = $companyDetail['Logo'];
                $companyContactName = $companyDetail['ContactName'];
                $companyUserName = strtolower(str_replace(" ", "", $companyName));



                if ($has_job_approval_rights == 1) {
                    $approval_right_status = $job['approval_status'];

                    if ($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                $feed_data = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                if (!empty($feed_data)) {
                    $uid = $feed_data['uid'];
                    $publish_date = $feed_data['publish_date'];
                }

                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country = db_get_country_name($job['Location_Country']);
                } else {
                    $country['country_code'] = "US";
                }

                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state = db_get_state_name($job['Location_State']);
                } else {
                    $state['state_name'] = "";
                }

                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city = $job['Location_City'];
                } else {
                    $city = "";
                }

                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode = $job['Location_ZipCode'];
                } else {
                    $zipcode = "";
                }

                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                } else {
                    $salary = "";
                }

                if (isset($job['SalaryType']) && $job['SalaryType'] != NULL) {
                    if ($job['SalaryType'] == 'per_hour') {
                        $jobType = "Per Hour";
                    } elseif ($job['SalaryType'] == 'per_week') {
                        $jobType = "Per Week";
                    } elseif ($job['SalaryType'] == 'per_month') {
                        $jobType = "Per Month   ";
                    } elseif ($job['SalaryType'] == 'per_year') {
                        $jobType = "Per Year";
                    } else {
                        $jobType = "";
                    }
                } else {
                    $jobType = "";
                }

                $jobDescription = "Job Description:" . '<br /><br />' . str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDescription .= '<br /><br />' . " Job Requirement:" . str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                } else {
                    $jobRequirements = "";
                }
                //
                $newArray[] = array(
                    'title' => $job['Title'],
                    'dateo' => ($publish_date),
                    'date' => date_with_time($publish_date),
                    'referencenumber' => $uid,
                    'company' => $companyName,
                    'city' => $city,
                    'state' => $state['state_name'],
                    'country' => $country['country_code'],
                    'postalcode' => $zipcode,
                    'description' => $jobDescription
                );
            }
        }

        $rows = '';
        if (sizeof($newArray)) {
            $columns = array_column($newArray, 'dateo');
            array_multisort($columns, SORT_DESC, $newArray);
            //
            foreach ($newArray as $k0 => $v0) {
                $rows .= "<job>
                <title><![CDATA[" . $v0['title'] . "]]></title>
                <date><![CDATA[" . $v0['date'] . " PST]]></date>
                <referencenumber><![CDATA[" . $v0['referencenumber'] . "]]></referencenumber>
                <company><![CDATA[" . $v0['company'] . "]]></company>
                <city><![CDATA[" . $v0['city'] . "]]></city>
                <state><![CDATA[" . $v0['state'] . "]]></state>
                <country><![CDATA[" . $v0['country'] . "]]></country>
                " . ($v0['postalcode'] == '' ? '<postalcode />' : '<postalcode><![CDATA[' . $v0['postalcode'] . ']]></postalcode>') . "
                <description><![CDATA[" . $v0['description'] . "]]></description>
				<email />
				<url />
				<category />
                </job>";
            }
        }

        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        $row = '
    		<?xml version="1.0" encoding="utf-8"?>
    		<source>
    			<publisher>' . (STORE_NAME) . '</publisher>
    			<publisherurl><![CDATA[' . (STORE_FULL_URL_SSL) . ']]></publisherurl>
    			<lastBuildDate>' . (date('D, d M Y h:i:s')) . ' PST</lastBuildDate>
    			' . $rows . '
    		</source>
        ';
        //
        echo trim($row);
        // mail(TO_EMAIL_DEV, 'Feed XML - Zip Data: ' . date('Y-m-d H:i:s'), print_r($newArray, true));
        // @mail('mubashir.saleemi123@gmail.com', 'Ziprecruiter Feed - HIT on ' . date('Y-m-d H:i:s') . '', count($rows));

        //echo xml_template_indeed($rows);
        exit;
    }

    public function zipPostUrl()
    {
        $this->addLastRead(10);
        $this->output->set_content_type('application/json');
        $this->output->set_header('Accept: */*');
        //$dummy_email = 'j.taylor.title@gmail.com';
        //$headers = $this->input->request_headers();
        //mail($dummy_email, 'ZipRecruter Request Headers -' . date('Y-m-d H:i:s'), print_r($headers, true));
        $insert_data_primary = array();
        $insert_job_list = array();
        //  mail('mubashir.saleemi123@gmail.com', 'ZipRecruter Request -' . date('Y-m-d H:i:s'),' Pinged');
        //
        @mail(OFFSITE_DEV_EMAIL, 'ZipRecruter - Applicant Recieve - ' . date('Y-m-d H:i:s') . '', (file_get_contents('php://input')));
        //
        $folder = APPPATH . '../../applicant/zipRecruter';
        //
        if (!is_dir($folder))
            mkdir($folder, 0777, true);
        // 
        $categories_file = fopen($folder . '/ZipRecruter_Applicant_Recieve_' . date('Y_m_d_H_i_s') . '.json', 'w');
        //
        fwrite($categories_file, file_get_contents('php://input'));
        //
        fclose($categories_file);
        //
        if (file_get_contents('php://input')) {
            try {
                $jSonData = file_get_contents('php://input');
                $data = json_decode(trim($jSonData), true);
                // mail($this->debug_email, 'ZipRecruiter applicant Full Data - AWS Server: ' . date('Y-m-d H:i:s'), print_r($data, true));
                //  mail('mubashir.saleemi123@gmail.com', 'ZipRecruter Request Headers -' . date('Y-m-d H:i:s'), print_r($data, true));

                if (!empty($data)) {
                    $name = $data['name'];
                    $first_name = $data['first_name'];
                    $last_name = $data['last_name'];
                    $email = $data['email'];
                    $phone = $data['phone'];
                    $resume = $data['resume'];
                    $job_sid = $data['job_id'];
                    $original_job_title = '';
                    $date_applied = date('Y-m-d H:i:s');
                    /**
                     * Add report
                     */
                    $this->addReport('ZipRecruiter', $data['email']);

                    if (!is_numeric($job_sid)) {
                        $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
                    }

                    //$job_sid = 215;
                    $job_details = $this->all_feed_model->get_job_detail($job_sid);
                    $referer = 'https://www.ziprecruiter.com/';
                    $userAgent = '';

                    if (!empty($resume)) { //Generate Resume - Start
                        $file_name = 'resume_ziprecruiter_' . clean($first_name) . '_' . clean($last_name) . '_' . date('YmdHis') . '.pdf';
                        $file_location = sys_get_temp_dir();
                        $file_path = $file_location . '/' . $file_name;
                        file_put_contents($file_path, base64_decode($resume));
                        $aws = new AwsSdk();
                        $aws->putToBucket($file_name, $file_path, AWS_S3_BUCKET_NAME);  //uploading file to AWS
                        $insert_data_primary['resume'] = $file_name;
                        $resume = $file_name;
                        unlink($file_path);
                    } //Generate Resume - End

                    if (!empty($job_details)) {
                        $company_sid = $job_details['user_sid'];
                        $company_name = $this->all_feed_model->get_company_name_by_id($company_sid);
                        $all_status = $this->all_feed_model->get_default_status_sid_and_text($company_sid);
                        $status_sid = $all_status['status_sid'];
                        $status = $all_status['status_name'];
                        $portal_job_applications_sid = $this->all_feed_model->check_job_applicant('company_check', $email, $company_sid);
                        $original_job_title = $job_details['Title'];


                        if (checkForBlockedEmail($email) == 'blocked') {
                            exit(0);
                        }

                        if ($portal_job_applications_sid == 'no_record_found') {
                            $insert_data_primary['employer_sid'] = $company_sid;
                            $insert_data_primary['first_name'] = $first_name;
                            $insert_data_primary['last_name'] = $last_name;
                            $insert_data_primary['email'] = $email;
                            $insert_data_primary['phone_number'] = $phone;
                            $job_applications_sid = $this->all_feed_model->saveApplicant($insert_data_primary);
                            //
                            send_full_employment_application($company_sid, $job_applications_sid, "applicant");
                        } else {
                            $job_applications_sid = $portal_job_applications_sid;
                        }

                        $already_applied = $this->all_feed_model->check_job_applicant($job_sid, $email, $company_sid);

                        //Resume Url
                        $resume_url = '';
                        $resume_anchor = '';

                        if (!empty($resume)) {
                            $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                            $resume_anchor = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                        }

                        if ($already_applied <= 0) {
                            $insert_job_list['portal_job_applications_sid'] = $job_applications_sid;
                            $insert_job_list['job_sid'] = $job_sid;
                            $insert_job_list['company_sid'] = $company_sid;
                            $insert_job_list['date_applied'] = $date_applied;
                            $insert_job_list['status'] = $status;
                            $insert_job_list['status_sid'] = $status_sid;
                            $insert_job_list['applicant_source'] = $referer;
                            $insert_job_list['main_referral'] = 'ziprecruiter';
                            $insert_job_list['applicant_type'] = 'Applicant';
                            $insert_job_list['eeo_form'] = null;
                            $jobs_list_result = $this->all_feed_model->add_applicant_job_details($insert_job_list);
                            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                            $job_added_successfully = $jobs_list_result[1];

                            // Send applicant to the queue
                            storeApplicantInQueueToProcess([
                                "portal_job_applications_sid" => $job_applications_sid,
                                "portal_applicant_job_sid" => $portal_applicant_jobs_list_sid,
                                "job_sid" => $job_sid,
                                "company_sid" => $company_sid,
                            ]);

                            $my_debug_message = '<br><pre>' . print_r($insert_data_primary, true) . print_r($insert_job_list, true) . '</pre>';
                            //                            mail($this->debug_email, 'ZipRecruiter Direct Applicant Data AWS' . $date_applied, $my_debug_message);

                            $acknowledgement_email_data['company_name'] = $company_name;
                            $acknowledgement_email_data['sid'] = $job_applications_sid;
                            $acknowledgement_email_data['job_sid'] = $job_sid;
                            $acknowledgement_email_data['job_title'] = $job_details['Title'];
                            $acknowledgement_email_data['employer_sid'] = $company_sid;
                            $acknowledgement_email_data['first_name'] = $first_name;
                            $acknowledgement_email_data['last_name'] = $last_name;
                            $acknowledgement_email_data['email'] = $email;
                            $acknowledgement_email_data['phone_number'] = $phone;
                            $acknowledgement_email_data['date_applied'] = $date_applied;
                            common_indeed_acknowledgement_email($acknowledgement_email_data);
                            // send email to 'new applicant notification' users *** START *** ////////
                            $message_hf = message_header_footer_domain($company_sid, $company_name);
                            $replacement_array = array();
                            $replacement_array['site_url'] = base_url();
                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                            $replacement_array['job_title'] = $job_details['Title'];
                            $replacement_array['phone_number'] = $phone;
                            $replacement_array['original_job_title'] = $original_job_title;
                            $profile_anchor = '<a href="' . base_url('applicant_profile/' . $job_applications_sid . '/' . $portal_applicant_jobs_list_sid) . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                            if (isset($city_name)) {
                                $replacement_array['city'] = $city_name;
                            } else {
                                $replacement_array['city'] = '';
                            }

                            $replacement_array['company_name'] = $company_name;
                            $notifications_status = get_notifications_status($company_sid);
                            //$company_primary_admin_info = get_primary_administrator_information($company_sid);
                            $my_debug_message = json_encode($replacement_array);
                            $applicant_notifications_status = 0;

                            if (!empty($notifications_status)) {
                                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                            } else {
                                //mail($this->debug_email, STORE_NAME . ' Apply Now Debug - No Status Record Found', $my_debug_message);
                            }

                            $applicant_notification_contacts = array();

                            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                                $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $job_sid);

                                if (!empty($applicant_notification_contacts)) {
                                    foreach ($applicant_notification_contacts as $contact) {
                                        $replacement_array['firstname'] = $first_name;
                                        $replacement_array['lastname'] = $last_name;
                                        $replacement_array['email'] = $email;
                                        $replacement_array['company_name'] = $company_name;
                                        $replacement_array['resume_link'] = $resume_anchor;
                                        $replacement_array['applicant_profile_link'] = $profile_anchor;
                                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                    }
                                    // mail($this->debug_email, 'Zip Applicant Notification', print_r($replacement_array, true ));
                                } /* else {
                     if (!empty($company_primary_admin_info)) {
                         $admin_first_name = $company_primary_admin_info['first_name'];
                         $admin_last_name = $company_primary_admin_info['last_name'];
                         $admin_email = $company_primary_admin_info['email'];
                         $contact_name = ucwords($admin_first_name . ' ' . $admin_last_name);
                         $replacement_array['firstname'] = $first_name;
                         $replacement_array['lastname'] = $last_name;
                         $replacement_array['email'] = $email;
                         $replacement_array['original_job_title'] = $original_job_title;
                         $replacement_array['company_name'] = $company_name;
                         $replacement_array['resume_link'] = $resume_anchor;
                         log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $admin_email, $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                     }
                 } */
                            }

                            //check if screening Questionnaire is attached to the job - If Yes, Send screen questionnaire email to applicant ***  START ***
                            $questionnaire_sid = $job_details['questionnaire_sid'];

                            if ($questionnaire_sid > 0) {
                                $questionnaire_status = $this->all_feed_model->check_screening_questionnaires($questionnaire_sid);

                                if ($questionnaire_status == 'found') {
                                    $email_template_information = $this->all_feed_model->get_email_template_data(SCREENING_QUESTIONNAIRE_FOR_JOB);
                                    $screening_questionnaire_key = $this->all_feed_model->generate_questionnaire_key($portal_applicant_jobs_list_sid);

                                    if (empty($email_template_information)) {
                                        $email_template_information = array(
                                            'subject' => '{{company_name}} - Screening Questionnaire for {{job_title}}',
                                            'text' => '<p>Dear {{applicant_name}},</p>
                                                                            <p>You have successfully applied for the job: "{{job_title}}" and your job application is in our system. </p>
                                                                            <p><strong>Please complete the Job Screening Questionnaire by clicking on the link below. We are excited to learn more about you. </strong></p>
                                                                            <p>{{url}}</p>
                                                                            <p>Thank you, again, for your interest in {{company_name}}</p>',
                                            'from_name' => '{{company_name}}'
                                        );
                                    }

                                    $emailTemplateBody = $email_template_information['text'];
                                    $emailTemplateSubject = $email_template_information['subject'];
                                    $emailTemplateFromName = $email_template_information['from_name'];
                                    $replacement_array = array();
                                    $replacement_array['company_name'] = $company_name;

                                    if ($original_job_title != '') {
                                        $replacement_array['job_title'] = $original_job_title;
                                    } else {
                                        $replacement_array['job_title'] = $job_details['Title'];
                                    }

                                    $replacement_array['applicant_name'] = $first_name . '&nbsp;' . $last_name;
                                    $replacement_array['url'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                                    if (!empty($replacement_array)) {
                                        foreach ($replacement_array as $key => $value) {
                                            $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                                            $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                            $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                        }
                                    }

                                    $message_data = array();
                                    $message_data['to_id'] = $email;
                                    $message_data['from_type'] = 'employer';
                                    $message_data['to_type'] = 'admin';
                                    $message_data['job_id'] = $job_applications_sid;
                                    $message_data['users_type'] = 'applicant';
                                    $message_data['subject'] = $emailTemplateSubject;
                                    $message_data['message'] = $emailTemplateBody;
                                    $message_data['date'] = $date_applied;
                                    $message_data['from_id'] = REPLY_TO;
                                    $message_data['contact_name'] = $first_name . '&nbsp;' . $last_name;
                                    $message_data['identity_key'] = generateRandomString(48);
                                    $message_hf = message_header_footer_domain($company_sid, $company_name);
                                    $secret_key = $message_data['identity_key'] . "__";
                                    $autoemailbody = $message_hf['header']
                                        . $emailTemplateBody
                                        . $message_hf['footer']
                                        . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                        . $secret_key . '</div>';

                                    //sendMail(REPLY_TO, $email, $emailTemplateSubject, $autoemailbody, $company_name, REPLY_TO);
                                    sendMail(REPLY_TO, $this->debug_email, $emailTemplateSubject . ' ZiP', $autoemailbody, $company_name, REPLY_TO);
                                    $sent_to_pm = common_save_message($message_data, NULL);
                                    $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                                }
                            }
                            // *** END - Screening Questionnaire Email ***


                        } else {
                            $replacement_array = array();
                            $replacement_array['company_name'] = $company_name;
                            $replacement_array['job_title'] = $job_details['Title'];
                            $replacement_array['original_job_title'] = $original_job_title;
                            $replacement_array['applicant_name'] = $name;
                            $replacement_array['resume_link'] = $resume_anchor;
                            $replacement_array['applicant_profile_link'] = $profile_anchor;
                            log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $email, $replacement_array, message_header_footer($company_sid, $company_name), 0);
                        }
                    } else { // flag it that job not found
                        $this->addReport('ZipRecruiter', $data['email'], 'update');
                        $flagged_data = array();
                        $flagged_data['name'] = $name;
                        $flagged_data['first_name'] = $first_name;
                        $flagged_data['last_name'] = $last_name;
                        $flagged_data['email'] = $email;
                        $flagged_data['phone_number'] = $phone;
                        $flagged_data['job_sid'] = $job_sid;
                        $flagged_data['resume'] = $resume;
                        $flagged_data['feed_source'] = 'https://www.ziprecruiter.com';
                        mail(TO_EMAIL_DEV, 'ZipRecruter feed missed applicant -' . $date_applied, print_r($flagged_data, true) . ' missed applicant');
                        $this->all_feed_model->savetotable($flagged_data, 'job_feed_missed_applicant');
                    }
                }
            } catch (Exception $ex) {
                mail($this->debug_email, 'ZipRecruter Request Exception AWS -' . date('Y-m-d H:i:s'), print_r($ex, true));
            }
        }
    }

    /**
     * Loads the new
     *
     */
    function index_new()
    {
        // Check in database
        $jobs = $this->all_feed_model->getZipRecruiterXmlJobs();
        if (sizeof($jobs)) {
            header('Content-type: text/xml');
            header('Pragma: public');
            header('Cache-control: private');
            header('Expires: -1');
            $jobRows = '';
            foreach ($jobs as $job)
                $jobRows .= $job['job'];
            echo xml_template_indeed(($jobRows));
            exit(0);
        }
    }

    private function isActiveFeed()
    {
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('ziprecruiter_organic');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}