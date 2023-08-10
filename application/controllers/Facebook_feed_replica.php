<?php defined('BASEPATH') OR exit('No direct script access allowed');

error_reporting(E_ALL);
ini_set('display_errors', 1);
class Facebook_feed_replica extends CI_Controller {

    private $clientId = '2211285445561045';
    private $clientSecret = '8ee3e946d117a7a6c2c0cd5527335c82';
    private $hiringManagerId = "1923839514417430";
    private $accessToken;
    private $accessTokenType;
    private $feedId;

    public function __construct() {
        parent::__construct();
        $this->load->model('facebook_feed_model');
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');

        //
        $this->accessToken = '2211285445561045|CDxZYxcSQcx6mJFHiH1RRHbtyOk';
        $this->accessTokenType = 'bearer';
        //
    }

    public function jobXmlCallback(){
        $o = APPPATH.'../assets/fba/';
        $n = APPPATH.'../assets/fba/new/';
        //
        // mkdir($n, 0777);
        //
        $scan = scandir(APPPATH.'../assets/fba');
        //
        foreach ($scan as $key => $value) {
            if($value == '.') continue;
            if($value == '..') continue;
            

            $dt = file_get_contents(APPPATH.'../assets/fba/'.$value);
            // $dt = file_get_contents('php://input');
            $dt = json_decode($dt, true, 2, JSON_BIGINT_AS_STRING);
            //
            $this->jobApplicationId = $dt['job_application_id'];
            $jobId = $dt['job_external_id'];
            // $jobId = 24859;
            // Lets save the incoming request
            $facebookApplicantId = 
            $this->facebook_feed_model->saveIncomingApplicant([
                'job_sid' => $jobId,
                'job_application_id' => $dt['job_application_id'],
                'job_type' => $dt['type'],
                'original_resume_url' => isset($dt['resume_url']) ? $dt['resume_url'] : ''
            ]);

            // Check if job exists
            $jobDetails = $this->facebook_feed_model->getJobDetails($jobId);
            //
            if(!sizeof($jobDetails)){
                _e( 'Job not found!' );
                exit(0);
            }
            //
            $companyId = $jobDetails['user_sid'];   
            $companyName = $jobDetails['CompanyName'];   
            $jobTitle = $jobDetails['Title'];   
            // //
            $this->makeCall(
                'https://graph.facebook.com/v7.0/{{jobApplicationId}}?fields=name,email,city_name,phone_number&access_token={{accessToken}}',
                array(
                    CURLOPT_CUSTOMREQUEST => "GET"
                )
            ); 
            // Lets update the resume field
            $this->facebook_feed_model->updateIncomingApplicant([
                    'extra_info' => json_encode($this->curl),
                    'original_application_id' => $this->curl['id']
                ],
                $facebookApplicantId
            );
            //
            $applicantInfo = $this->curl;
            $t = explode(' ', $applicantInfo['name']);
            $firstName = trim($t[0]);
            $lastName = trim($t[1]);
            //
            unset($t);
            // Check if applicant exists in company
            //  - If No then add it
            //  - else get applicant id
            // Check if applicant already applied for job
            //  - If Yes then send already applied notification
            //  - else apply proccess
            // Check if applicant already applied
            $isApplied = $this->facebook_feed_model->alreadyApplied(
                $companyId,
                $jobId,
                trim($applicantInfo['email']),
                $firstName,
                $lastName,
                $applicantInfo['phone_number']
            );
            // If the candiatehas already applied
            if($isApplied['hasApplied'] == 1){
                $replacement_array = array();
                $replacement_array['company_name'] = $companyName;
                $replacement_array['job_title'] = $jobTitle;
                $replacement_array['original_job_title'] = $jobTitle;
                $replacement_array['applicant_name'] = $applicantInfo['name'];
                // $replacement_array['resume_link'] = $resume_anchor;
                $replacement_array['applicant_profile_link'] = base_url('applicant_profile/'.$isApplied['portalJobApplicationsId']);
                log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, DEV_EMAIL_2, $replacement_array, message_header_footer($companyId, $companyName), 0);
                // log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $applicantInfo['email'], $replacement_array, message_header_footer($companyId, $companyName), 0);
            } else{
                // Excute when applicant hasn't applied
                if(isset($dt['resume_url']) && !empty($dt['resume_url'])){
                    // Lets upload resume
                    $resume = $dt['resume_url'];
                    $file_name = 'resume_facebook_job_' . clean($firstName) . '_' . clean($lastName) . '_' . date('YmdHis') . '.pdf';
                    $file_location = sys_get_temp_dir();
                    $file_path = $file_location . '/' . $file_name;
                    @file_put_contents($file_path,  getFileData($resume));
                    // uncomment on production
                    $aws = new AwsSdk();
                    $aws->putToBucket($file_name, $file_path, AWS_S3_BUCKET_NAME);  //uploading file to AWS
                    $resume = $file_name;
                    unlink($file_path);
                } else $resume = '';

                // Get status
                $status = $this->facebook_feed_model->get_default_status_sid_and_text($companyId);

                // Create insert array
                $in = [];
                $in['portal_job_applications_sid'] = $isApplied['portalJobApplicationsId'];
                $in['job_sid'] = $jobId;
                $in['company_sid'] = $companyId;
                $in['date_applied'] = date('Y-m-d H:i:s', strtotime('now'));
                $in['status'] = $status['status_name'];
                $in['status_sid'] = $status['status_sid'];
                $in['applicant_source'] = 'https://www.facebook.com/jobs';
                $in['main_referral'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'https://www.facebook.com/jobs';
                $in['applicant_type'] = 'Applicant';
                $in['eeo_form'] = null;
                $in['resume'] = $resume;
                $in['last_update'] = date('Y-m-d', strtotime('now'));
                //
                $jobs_list_result = $this->facebook_feed_model->add_applicant_job_details($in);

                _e($jobs_list_result, true);
                //
                $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                $job_added_successfully = $jobs_list_result[1];

                // Email sent to applicant
                $acknowledgement_email_data['company_name'] = $companyName;
                $acknowledgement_email_data['sid'] = $isApplied['portalJobApplicationsId'];
                $acknowledgement_email_data['job_sid'] = $jobId;
                $acknowledgement_email_data['job_title'] = $jobTitle;
                $acknowledgement_email_data['employer_sid'] = $companyId;
                $acknowledgement_email_data['first_name'] = $firstName;
                $acknowledgement_email_data['last_name'] = $lastName;
                $acknowledgement_email_data['email'] = $applicantInfo['email'];
                $acknowledgement_email_data['phone_number'] = $applicantInfo['phone_number'];
                $acknowledgement_email_data['date_applied'] = $in['date_applied'];
                common_indeed_acknowledgement_email($acknowledgement_email_data);


                //
                // send email to 'new applicant notification' users *** START *** ////////
                $message_hf = message_header_footer_domain($companyId, $companyName);
                //
                $replacement_array = array();
                $replacement_array['site_url'] = base_url();
                $replacement_array['date'] = month_date_year(date('Y-m-d'));
                $replacement_array['job_title'] = $jobTitle;
                $replacement_array['phone_number'] = $applicantInfo['phone_number'];
                $replacement_array['original_job_title'] = $jobTitle;
                $replacement_array['company_name'] = $companyName;
                $replacement_array['city'] = isset($applicantInfo['city_name']) ? $applicantInfo['city_name'] : '';
                $profile_anchor = '<a href="'.base_url('applicant_profile/'.$isApplied['portalJobApplicationsId'].'/'.$portal_applicant_jobs_list_sid).'" style="'.DEF_EMAIL_BTN_STYLE_DANGER.'">View Profile</a>';
                // Get employers for nootification
                $notifications_status = get_notifications_status($companyId);
                //$company_primary_admin_info = get_primary_administrator_information($company_sid);
                $applicant_notifications_status = 0;

                if (!empty($notifications_status)) {
                    $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                } else {
                    //mail($this->debug_email, STORE_NAME . ' Apply Now Debug - No Status Record Found', $my_debug_message);
                }

                $applicant_notification_contacts = array();

                // Send notification to employers
                if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                    $applicant_notification_contacts = get_notification_email_contacts($companyId, 'new_applicant', $jobId);

                    if (!empty($applicant_notification_contacts)) {
                        foreach ($applicant_notification_contacts as $contact) {
                            $replacement_array['firstname'] = $firstName;
                            $replacement_array['lastname'] = $lastName;
                            $replacement_array['email'] = $applicantInfo['email'];
                            $replacement_array['company_name'] = $companyName;
                            // $replacement_array['resume_link'] = $resume_anchor;
                            $replacement_array['applicant_profile_link']   = $profile_anchor;
                            log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $companyId, $jobId, 'new_applicant_notification');
                        }
                    }
                }



                // Screening questionaire
                $questionnaire_sid = $jobDetails['questionnaire_sid'];

                if($questionnaire_sid > 0) {
                    $questionnaire_status = $this->all_feed_model->check_screening_questionnaires($questionnaire_sid);

                    if($questionnaire_status == 'found') {
                        $email_template_information = $this->all_feed_model->get_email_template_data(SCREENING_QUESTIONNAIRE_FOR_JOB);
                        $screening_questionnaire_key = $this->all_feed_model->generate_questionnaire_key($portal_applicant_jobs_list_sid);

                        if(empty($email_template_information)) {
                            $email_template_information = array('subject' =>'{{company_name}} - Screening Questionnaire for {{job_title}}',
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

                        if($jobTitle != '') {
                            $replacement_array['job_title'] = $jobTitle;
                        } else {
                            $replacement_array['job_title'] = $jobTitle;
                        }

                        $replacement_array['applicant_name'] = $firstName.'&nbsp;'.$lastName;
                        $replacement_array['url'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                        if (!empty($replacement_array)) {
                            foreach ($replacement_array as $key => $value) {
                                $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                            }
                        }

                        $message_data = array();
                        $message_data['to_id'] = $applicantInfo['email'];
                        $message_data['from_type'] = 'employer';
                        $message_data['to_type'] = 'admin';
                        $message_data['job_id'] = $isApplied['portalJobApplicationsId'];
                        $message_data['users_type'] = 'applicant';
                        $message_data['subject'] = $emailTemplateSubject;
                        $message_data['message'] = $emailTemplateBody;
                        $message_data['date'] = date('Y-m-d H:i:s', strtotime('now'));
                        $message_data['from_id'] = REPLY_TO;
                        $message_data['contact_name'] = $firstName.'&nbsp;'.$lastName;
                        $message_data['identity_key'] = generateRandomString(48);
                        $secret_key = $message_data['identity_key'] . "__";
                        $autoemailbody = $message_hf['header']
                                        . $emailTemplateBody
                                        . $message_hf['footer']
                                        . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                        . $secret_key . '</div>';

                        //sendMail(REPLY_TO, $email, $emailTemplateSubject, $autoemailbody, $company_name, REPLY_TO);
                        // sendMail(REPLY_TO, $this->debug_email, $emailTemplateSubject.' ZiP', $autoemailbody, $companyName, REPLY_TO);
                        $sent_to_pm = common_save_message($message_data, NULL);
                        $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                    }
                }
            }

            sleep(2);
            copy(
                $o.$value,
                $n.$value 
            );
            unlink($o.$value);
            _e('Worked: '.$this->jobApplicationId, true);
        }
    }



    //
    private function makeCall(
        $url,
        $headers = array()
    ){
        // $this->curl = json_decode(file_get_contents(APPPATH.'../assets/fba/tt.json'), true);
        // return;
        //2
        $this->parseURL($url);
        //
        _e($url, true);
        //
        $curl = curl_init();
        //
        $options = $headers;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_ENCODING] = "";
        $options[CURLOPT_MAXREDIRS] = 10;
        $options[CURLOPT_TIMEOUT] = 0;
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        //
        curl_setopt_array(
            $curl, 
            $options
        );
        //
        $this->curl = json_decode(curl_exec($curl), true);
        //
        curl_close($curl);
    }


    //
    private function parseURL(
        &$url
    ){
        $url = str_replace(
            [
                '{{clientId}}',
                '{{clientSecret}}',
                '{{accessToken}}',
                '{{hiringManagerId}}',
                '{{feedId}}',
                '{{jobApplicationId}}'
            ], 
            [
                $this->clientId,
                $this->clientSecret,
                $this->accessToken,
                $this->hiringManagerId,
                $this->feedId,
                $this->jobApplicationId
            ], 
            $url
        );
    }
}