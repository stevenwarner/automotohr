<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Indeed_feed extends CI_Controller {

    private $debug_email = TO_EMAIL_DEV;

    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {
        $sid = $this->isActiveFeed();
        $jobData = $this->all_feed_model->get_all_company_jobs_indeed();
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);
        $rows = '';
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        $rows .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        // echo '<xml>';
        $rows .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";

        if (count($jobData) > 0) {
            // $productData = $this->all_feed_model->get_product_data($jobData[0]['product_sid']);
            foreach ($jobData as $job) {
                if (in_array($job['user_sid'], $activeCompaniesArray)) {
                    $company_id = $job['user_sid'];
                    $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                    $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
                    $companyName = $companydata['CompanyName'];
                    $has_job_approval_rights = $companydata['has_job_approval_rights'];

                    if($has_job_approval_rights ==  1) {
                        $approval_right_status = $job['approval_status'];

                        if($approval_right_status != 'approved') {
                            continue;
                        }
                    }

                    if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                        $jobDesc = strip_tags($job['JobDescription'], '<br>') . '<br><br>Job Requirements:<br>' . strip_tags($job['JobRequirements'], '<br>');
                    } else {
                        $jobDesc = strip_tags($job['JobDescription'], '<br>');
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

                    $JobCategorys = $job['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();

                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                            $job_category_array[] = $job_cat_name[0]['value'];
                        }

                        $job_category = implode(', ', $job_category_array);
                    }

                    $rows .=  "
                    <job>
                    <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], fasle) . "]]></title>
                    <sponsored><![CDATA[yes]]></sponsored>
                    <budget><![CDATA[" . $job['budget'] . "]]></budget>
                    <date><![CDATA[" . date_with_time($job['activation_date']) . " PST]]></date>
                    <referencenumber><![CDATA[" . $job['sid'] . "]]></referencenumber>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $job['sid'] . "]]></url>
                    <company><![CDATA[" . $companyName . "]]></company>
                    <city><![CDATA[" . $city . "]]></city>
                    <state><![CDATA[" . $state['state_name'] . "]]></state>
                    <country><![CDATA[" . $country['country_code'] . "]]></country>
                    <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                    <salary><![CDATA[" . $salary . "]]></salary>
                    <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                    <category><![CDATA[" . $job_category . "]]></category>
                    <description><![CDATA[" . $jobDesc . "]]></description>
                    <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $job['sid']) . "&indeed-apply-jobid=" . $job['sid'] . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "/indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required&indeed-apply-allow-apply-on-indeed=1]]></indeed-apply-data>
                    </job>";
                }
            }
        }
        $rows .=  '</source>
        ';
        echo trim($rows);
        exit;
    }

    public function indeedPostUrl() {
        // error_reporting(E_ALL);

        if (file_get_contents('php://input')) {
            $jSonData = file_get_contents('php://input');
            $data = json_decode($jSonData, true);
            $applicationData = array(); // Old Variable currently used
            $insert_data_primary = array(); // Data to be inserted in Primary table
            $applicant_resume = '';
            // mail($this->debug_email, 'Indeed applicant Full Data - AWS Server: ' . date('Y-m-d H:i:s'), print_r($data, true));
            sleep(rand(1, 3));


            $job_sid = $data['job']['jobId'];

            if (!is_numeric($job_sid)) {
                $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
            }

            $job_details = $this->all_feed_model->get_job_detail($job_sid);
            $companyId = $job_details['user_sid'];
            // if (in_array($companyId, array("7", "51"))) {
            if (!in_array($companyId, array("0"))) {

                if (isset($data['applicant']['resume']['file']['data'])) { //making Resume file to upload on AWS
                    $base64Data = $data['applicant']['resume']['file']['data']; //Decode pdf content
                    $pdf_decoded = base64_decode($base64Data);
                    $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                    $fileName = $data['applicant']['resume']['file']['fileName'];

                    if (isset($fileName)) {
                        if (!file_exists($filePath)) {
                            mkdir($filePath, 0777);
                        }

                        $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                        fwrite($pdf, $pdf_decoded);
                        fclose($pdf); //close output file

                        $resume = generateRandomString(6) . "_" . $fileName;
                        $aws = new AwsSdk();
                        $aws->putToBucket($resume, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS
                        $applicationData['resume'] = $resume;
                        $insert_data_primary['resume'] = $resume;
                        $applicant_resume = $resume;
                        unlink('/' . DOC_ROOT . 'assets/temp_files/' . $fileName);
                    }
                }

                $nameArray = explode(' ', $data['applicant']['fullName'], 2);
                $more_info = $data['applicant']['resume']['json'];

                if (!empty($more_info)) {
                    if (isset($more_info['location']['city'])) {
                        $city_details = $more_info['location']['city'];
                        $city_details = explode(',', $city_details);
                        $city_name = trim($city_details[0]);
                        $applicationData['city'] = $city_name;
                        $insert_data_primary['city'] = $city_name;
                        $state_code = trim($city_details[1]);
                        $state_country_id = common_get_location_by_statecode($state_code);

                        if (!empty($state_country_id)) {
                            $country_sid = $state_country_id['country_sid'];
                            $state_sid = $state_country_id['sid'];
                            $applicationData['country'] = $country_sid;
                            $applicationData['state'] = $state_sid;
                            $insert_data_primary['country'] = $country_sid;
                            $insert_data_primary['state'] = $state_sid;
                        }
                    }

                    if (isset($more_info['location']['postalcode'])) {
                        $postalcode = $more_info['location']['postalcode'];
                        $applicationData['zipcode'] = $postalcode;
                        $insert_data_primary['zipcode'] = $postalcode;
                    }
                }

                if (isset($data['applicant']['coverletter'])) {
                    $fileData = $data['applicant']['coverletter'];
                    $temp_cover = $nameArray[0] . "_cover_letter";
                    $filePath = FCPATH . "assets/temp_files/";

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777); //make dir
                    }

                    write_file("$filePath" . $temp_cover . ".txt", $fileData); //write file
                    $cover_letter = $temp_cover . '-' . generateRandomString(6) . '.txt';

                    $aws = new AwsSdk();
                    $aws->putToBucket($cover_letter, $filePath . $temp_cover . '.txt', AWS_S3_BUCKET_NAME);
                    $applicationData['cover_letter'] = $cover_letter;
                    $insert_data_primary['cover_letter'] = $cover_letter;
                    unlink('/' . DOC_ROOT . 'assets/temp_files/' . $temp_cover . ".txt");
                }

                $referer = 'https://www.indeed.com';
                $userAgent = '';

                if (isset($data['analytics']['referer'])) {
                    $referer = $data['analytics']['referer'];
                }

                if (isset($data['analytics']['userAgent'])) {
                    $userAgent = $data['analytics']['userAgent'];
                }

                // $job_sid = $data['job']['jobId'];

                // if (!is_numeric($job_sid)) {
                //     $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
                // }

                $original_job_title = '';
                // $job_details = $this->all_feed_model->get_job_detail($job_sid);
                $questionnaire_sid = 0;

                if(!empty($job_details)){
                    $original_job_title = $job_details['Title'];
                }

                $company_name = $data['job']['jobCompany'];
                $applicant_email = $data['applicant']['email'];
                $jobTitle = $data['job']['jobTitle'];
                $date_applied = date('Y-m-d H:i:s');
                //$companyId = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
                // $companyId = $job_details['user_sid'];
                $all_status = $this->all_feed_model->get_default_status_sid_and_text($companyId);
                $status_sid = $all_status['status_sid'];
                $status = $all_status['status_name'];
                $applicationData['job_sid'] = $job_sid;
                $applicationData['employer_sid'] = $companyId;
                $applicationData['first_name'] = $nameArray[0];
                $applicationData['last_name'] = $nameArray[1];
                $applicationData['email'] = $applicant_email;
                $applicationData['phone_number'] = $data['applicant']['phoneNumber'];
                $applicationData['status_sid'] = $status_sid;
                $applicationData['status'] = $status;
                $applicationData['date_applied'] = $date_applied;
                $applicationData['applicant_source'] = $referer;
                $applicationData['main_referral'] = 'indeed';
                $applicationData['ip_address'] = $data['analytics']['ip'];
                $applicationData['user_agent'] = $userAgent;

                // Check if user has already applied in this company for any other job
                $portal_job_applications_sid = $this->all_feed_model->check_job_applicant('company_check', $applicant_email, $companyId);

                if ($portal_job_applications_sid == 'no_record_found') {
                    //$insert_data_primary['job_sid'] = $job_sid; // this has to be removed
                    $insert_data_primary['employer_sid'] = $companyId;
                    $insert_data_primary['first_name'] = $nameArray[0];
                    $insert_data_primary['last_name'] = $nameArray[1];
                    $insert_data_primary['email'] = $applicant_email;
                    $insert_data_primary['phone_number'] = $data['applicant']['phoneNumber'];

                    $job_applications_sid = $this->all_feed_model->saveApplicant($insert_data_primary);
                } else {
                    //$old_s3_resume = $this->all_feed_model->get_old_resume($portal_job_applications_sid);
                    $job_applications_sid = $portal_job_applications_sid;
                    // $resume_to_update = array();
                    // $resume_to_update['resume'] = $applicant_resume;
                    // $this->all_feed_model->update_applicant_resume($portal_job_applications_sid, $resume_to_update);

                    // if (!empty($old_s3_resume)) {
                    //     $resume_log_data                            = array();
                    //     $resume_log_data['company_sid']             = $companyId;
                    //     $resume_log_data['user_type']               = 'Applicant';
                    //     $resume_log_data['user_sid']                = $portal_job_applications_sid;
                    //     $resume_log_data['user_email']              = $applicant_email;
                    //     $resume_log_data['requested_by']            = 0;
                    //     $resume_log_data['requested_subject']       = 'NULL';
                    //     $resume_log_data['requested_message']       = 'NULL';
                    //     $resume_log_data['requested_ip_address']    = getUserIP();
                    //     $resume_log_data['requested_user_agent']    = $_SERVER['HTTP_USER_AGENT'];
                    //     $resume_log_data['request_status']          = 3;
                    //     $resume_log_data['is_respond']              = 1;
                    //     $resume_log_data['resume_original_name']    = 'applicant_indeed_resume';
                    //     $resume_log_data['resume_s3_name']          = $applicant_resume;
                    //     $resume_log_data['resume_extension']        = '.pdf';
                    //     $resume_log_data['old_resume_s3_name']      = $old_s3_resume;
                    //     $resume_log_data['response_date']           = date('Y-m-d H:i:s');
                    //     $resume_log_data['requested_date']          = date('Y-m-d H:i:s');
                    //     $resume_log_data['job_sid']                 = $job_sid;
                    //    $resume_log_data['job_type']                = 'profile_update';
                    //     $this->all_feed_model->insert_resume_log($resume_log_data);
                    // }
                }

                if (!isset($resume) || empty($resume) || $resume == '') {
                    sendResumeEmailToApplicant([
                        'company_sid' => $companyId,
                        'company_name' => $company_name,
                        'job_list_sid' => $job_sid,
                        'user_sid' => $job_applications_sid,
                        'user_type' => 'applicant',
                        'requested_job_sid' => $job_sid,
                        'requested_job_type' => 'job'
                    ]);
                }


                //check if the user has already applied for this job
                $already_applied = $this->all_feed_model->check_job_applicant($job_sid, $applicant_email, $companyId);

                if ($already_applied <= 0) {
                    $insert_job_list = array(
                                            'portal_job_applications_sid' => $job_applications_sid,
                                            'job_sid' => $job_sid,
                                            'company_sid' => $companyId,
                                            'date_applied' => $date_applied,
                                            'status' => $status,
                                            'status_sid' => $status_sid,
                                            'applicant_source' => $referer,
                                            'main_referral' => 'indeed',
                                            'applicant_type' => 'Applicant',
                                            'eeo_form' => null,
                                            'ip_address' => $data['analytics']['ip'],
                                            'user_agent' => $userAgent,
                                            'resume' => $applicant_resume,
                                            'last_update' => date('Y-m-d')
                                        );
                    sleep(1);
                    $final_check = $this->all_feed_model->applicant_list_exists_check($job_applications_sid, $job_sid, $companyId);

                    if($final_check <=0) {

                        /*START START START*/
                        $jobs_list_result = $this->all_feed_model->add_applicant_job_details($insert_job_list);
                        $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                        $job_added_successfully = $jobs_list_result[1];
                        $acknowledgement_email_data['company_name'] = $company_name;
                        $acknowledgement_email_data['sid'] = $job_applications_sid;
                        $acknowledgement_email_data['job_sid'] = $job_sid;
                        $acknowledgement_email_data['job_title'] = $jobTitle;
                        $acknowledgement_email_data['employer_sid'] = $companyId;
                        $acknowledgement_email_data['first_name'] = $nameArray[0];
                        $acknowledgement_email_data['last_name'] = $nameArray[1];
                        $acknowledgement_email_data['email'] = $applicant_email;
                        $acknowledgement_email_data['phone_number'] = $data['applicant']['phoneNumber'];
                        $acknowledgement_email_data['date_applied'] = date('Y-m-d H:i:s');
                        common_indeed_acknowledgement_email($acknowledgement_email_data);
                        // send email to 'new applicant notification' users *** START *** ////////

                        $company_sid = $companyId;
                        $message_hf = message_header_footer_domain($company_sid, $company_name);
                        $replacement_array = array();
                        $replacement_array['site_url'] = base_url();
                        $replacement_array['date'] = month_date_year(date('Y-m-d'));
                        $replacement_array['job_title'] = $jobTitle;
                        $replacement_array['phone_number'] = $data['applicant']['phoneNumber'];

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
                        }

                        $resume_url = '';
                        $resume_anchor = '';
                        $profile_anchor = '<a href="'.base_url('applicant_profile/'.$job_applications_sid.'/'.$portal_applicant_jobs_list_sid).'" style="'.DEF_EMAIL_BTN_STYLE_DANGER.'"  download="resume" >View Profile</a>';

                        if (!empty($resume)) {
                            $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                            $resume_anchor = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                        }

                        $applicant_notification_contacts = array();

                        if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                            $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $job_sid);

                            if (!empty($applicant_notification_contacts)) {
                                    foreach($applicant_notification_contacts as $contact) {
                                            $replacement_array['firstname'] = $nameArray[0];
                                            $replacement_array['lastname'] = $nameArray[1];
                                            $replacement_array['email'] = $applicant_email;
                                            $replacement_array['company_name'] = $company_name;
											$replacement_array['phone_number'] = $data['applicant']['phoneNumber'];
                                            $replacement_array['resume_link'] = $resume_anchor;
                                            $replacement_array['applicant_profile_link'] = $profile_anchor;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                            //mail($this->debug_email, 'Company notification for: ' . $company_name, 'applicant_email: ' . $applicant_email . ' Send Mail to: ' . $contact['email']);
                                    }
                                    //mail($this->debug_email, 'Indeed Applicant Notification', print_r($replacement_array, true ));
                            }
                        } // send email to 'new applicant notification' users *** END *** ////////

                        //check if screening Questionnaire is attached to the job - If Yes, Send screen questionnaire email to applicant ***  START ***
                        if(!empty($job_details)) {
                            $original_job_title = $job_details['Title'];
                            $questionnaire_sid = $job_details['questionnaire_sid'];
                        }

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

                                if($original_job_title != '') {
                                    $replacement_array['job_title'] = $original_job_title;
                                } else {
                                    $replacement_array['job_title'] = $jobTitle;
                                }

                                $replacement_array['applicant_name'] = $nameArray[0].'&nbsp;'.$nameArray[1];
                                $replacement_array['url'] = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                                if (!empty($replacement_array)) {
                                    foreach ($replacement_array as $key => $value) {
                                        $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                                        $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                        $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                    }
                                }

                                if (!empty($emailTemplateBody)) {
                                                $emailTemplateBody     = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{firstname}}', $nameArray[0], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{lastname}}', $nameArray[1], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{applicant_name}}', $nameArray[0] . ' ' . $nameArray[1], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{job_title}}', $replacement_array['job_title'], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
                                }

                                $message_data = array();
                                $message_data['to_id'] = $applicant_email;
                                $message_data['from_type'] = 'employer';
                                $message_data['to_type'] = 'admin';
                                $message_data['job_id'] = $job_applications_sid;
                                $message_data['users_type'] = 'applicant';
                                $message_data['subject'] = $emailTemplateSubject;
                                $message_data['message'] = $emailTemplateBody;
                                $message_data['date'] = $date_applied;
                                $message_data['from_id'] = REPLY_TO;
                                $message_data['contact_name'] = $nameArray[0].'&nbsp;'.$nameArray[1];
                                $message_data['identity_key'] = generateRandomString(48);
                                $message_hf = message_header_footer_domain($company_sid, $company_name);
                                $secret_key = $message_data['identity_key'] . "__";
                                $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                                                . $emailTemplateBody
                                                . $message_hf['footer']
                                                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                . $secret_key . '</div>';

                                $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                                sendMail(FROM_EMAIL_INFO, $applicant_email, $emailTemplateSubject, $autoemailbody, $company_name, FROM_EMAIL_INFO);
                                //sendMail(REPLY_TO, $this->debug_email, $emailTemplateSubject, $autoemailbody, $company_name, REPLY_TO);
                                $sent_to_pm = common_save_message($message_data, NULL);
                                $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                            }
                        }
                    // *** END - Screening Questionnaire Email ***
                    /*END END END*/
                    }
                } else {
                    $applicant_email = $data['applicant']['email'];
                    $company_name = $data['job']['jobCompany'];
                    $company_sid = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
                    $replacement_array = array();
                    $replacement_array['company_name'] = $data['job']['jobCompany'];
                    $replacement_array['job_title'] = $data['job']['jobTitle'];
                    $replacement_array['applicant_name'] = $data['applicant']['fullName'];
                    $replacement_array['resume_link'] = '';
                    $replacement_array['applicant_profile_link'] = '';
                    log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $applicant_email, $replacement_array, message_header_footer($company_sid, $company_name), 0);
                    //log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $this->debug_email, $replacement_array, message_header_footer($company_sid, $company_name));
                    //mail($this->debug_email, STORE_NAME.' Apply Now Debug - Indeed Applicant already applied for this job.', print_r($data, true));
                }

            } else {
                if (isset($data['applicant']['resume']['file']['data'])) { //making Resume file to upload on AWS
                    $base64Data = $data['applicant']['resume']['file']['data']; //Decode pdf content
                    $pdf_decoded = base64_decode($base64Data);
                    $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                    $fileName = $data['applicant']['resume']['file']['fileName'];

                    if (isset($fileName)) {
                        if (!file_exists($filePath)) {
                            mkdir($filePath, 0777);
                        }

                        $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                        fwrite($pdf, $pdf_decoded);
                        fclose($pdf); //close output file

                        $resume = generateRandomString(6) . "_" . $fileName;
                        $aws = new AwsSdk();
                        $aws->putToBucket($resume, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS
                        $applicationData['resume'] = $resume;
                        $insert_data_primary['resume'] = $resume;
                        unlink('/' . DOC_ROOT . 'assets/temp_files/' . $fileName);
                    }
                }

                $nameArray = explode(' ', $data['applicant']['fullName'], 2);
                $more_info = $data['applicant']['resume']['json'];

                if (!empty($more_info)) {
                    if (isset($more_info['location']['city'])) {
                        $city_details = $more_info['location']['city'];
                        $city_details = explode(',', $city_details);
                        $city_name = trim($city_details[0]);
                        $applicationData['city'] = $city_name;
                        $insert_data_primary['city'] = $city_name;
                        $state_code = trim($city_details[1]);
                        $state_country_id = common_get_location_by_statecode($state_code);

                        if (!empty($state_country_id)) {
                            $country_sid = $state_country_id['country_sid'];
                            $state_sid = $state_country_id['sid'];
                            $applicationData['country'] = $country_sid;
                            $applicationData['state'] = $state_sid;
                            $insert_data_primary['country'] = $country_sid;
                            $insert_data_primary['state'] = $state_sid;
                        }
                    }

                    if (isset($more_info['location']['postalcode'])) {
                        $postalcode = $more_info['location']['postalcode'];
                        $applicationData['zipcode'] = $postalcode;
                        $insert_data_primary['zipcode'] = $postalcode;
                    }
                }

                if (isset($data['applicant']['coverletter'])) {
                    $fileData = $data['applicant']['coverletter'];
                    $temp_cover = $nameArray[0] . "_cover_letter";
                    $filePath = FCPATH . "assets/temp_files/";

                    if (!file_exists($filePath)) {
                        mkdir($filePath, 0777); //make dir
                    }

                    write_file("$filePath" . $temp_cover . ".txt", $fileData); //write file
                    $cover_letter = $temp_cover . '-' . generateRandomString(6) . '.txt';

                    $aws = new AwsSdk();
                    $aws->putToBucket($cover_letter, $filePath . $temp_cover . '.txt', AWS_S3_BUCKET_NAME);
                    $applicationData['cover_letter'] = $cover_letter;
                    $insert_data_primary['cover_letter'] = $cover_letter;
                    unlink('/' . DOC_ROOT . 'assets/temp_files/' . $temp_cover . ".txt");
                }

                $referer = 'https://www.indeed.com';
                $userAgent = '';

                if (isset($data['analytics']['referer'])) {
                    $referer = $data['analytics']['referer'];
                }

                if (isset($data['analytics']['userAgent'])) {
                    $userAgent = $data['analytics']['userAgent'];
                }

                $job_sid = $data['job']['jobId'];

                if (!is_numeric($job_sid)) {
                    $job_sid = $this->all_feed_model->fetch_job_id_from_random_key($job_sid);
                }

                $original_job_title = '';
                $job_details = $this->all_feed_model->get_job_detail($job_sid);
                $questionnaire_sid = 0;

                if(!empty($job_details)){
                    $original_job_title = $job_details['Title'];
                }

                $company_name = $data['job']['jobCompany'];
                $applicant_email = $data['applicant']['email'];
                $jobTitle = $data['job']['jobTitle'];
                $date_applied = date('Y-m-d H:i:s');
                //$companyId = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
                $companyId = $job_details['user_sid'];
                $all_status = $this->all_feed_model->get_default_status_sid_and_text($companyId);
                $status_sid = $all_status['status_sid'];
                $status = $all_status['status_name'];
                $applicationData['job_sid'] = $job_sid;
                $applicationData['employer_sid'] = $companyId;
                $applicationData['first_name'] = $nameArray[0];
                $applicationData['last_name'] = $nameArray[1];
                $applicationData['email'] = $applicant_email;
                $applicationData['phone_number'] = $data['applicant']['phoneNumber'];
                $applicationData['status_sid'] = $status_sid;
                $applicationData['status'] = $status;
                $applicationData['date_applied'] = $date_applied;
                $applicationData['applicant_source'] = $referer;
                $applicationData['main_referral'] = 'indeed';
                $applicationData['ip_address'] = $data['analytics']['ip'];
                $applicationData['user_agent'] = $userAgent;
                // Check if user has already applied in this company for any other job
                $portal_job_applications_sid = $this->all_feed_model->check_job_applicant('company_check', $applicant_email, $companyId);

                if ($portal_job_applications_sid == 'no_record_found') {
                    //$insert_data_primary['job_sid'] = $job_sid; // this has to be removed
                    $insert_data_primary['employer_sid'] = $companyId;
                    $insert_data_primary['first_name'] = $nameArray[0];
                    $insert_data_primary['last_name'] = $nameArray[1];
                    $insert_data_primary['email'] = $applicant_email;
                    $insert_data_primary['phone_number'] = $data['applicant']['phoneNumber'];
                    $job_applications_sid = $this->all_feed_model->saveApplicant($insert_data_primary);
                } else {
                    $job_applications_sid = $portal_job_applications_sid;
                }

                //check if the user has already applied for this job
                $already_applied = $this->all_feed_model->check_job_applicant($job_sid, $applicant_email, $companyId);

                if ($already_applied <= 0) {
                    $insert_job_list = array(
                                            'portal_job_applications_sid' => $job_applications_sid,
                                            'job_sid' => $job_sid,
                                            'company_sid' => $companyId,
                                            'date_applied' => $date_applied,
                                            'status' => $status,
                                            'status_sid' => $status_sid,
                                            'applicant_source' => $referer,
                                            'main_referral' => 'indeed',
                                            'applicant_type' => 'Applicant',
                                            'eeo_form' => null,
                                            'ip_address' => $data['analytics']['ip'],
                                            'user_agent' => $userAgent
                                        );
                    sleep(1);
                    $final_check = $this->all_feed_model->applicant_list_exists_check($job_applications_sid, $job_sid, $companyId);

                    if($final_check <=0) {
                        /*START START START*/
                        $jobs_list_result = $this->all_feed_model->add_applicant_job_details($insert_job_list);
                        $portal_applicant_jobs_list_sid = $jobs_list_result[0];
                        $job_added_successfully = $jobs_list_result[1];
                        $acknowledgement_email_data['company_name'] = $company_name;
                        $acknowledgement_email_data['sid'] = $job_applications_sid;
                        $acknowledgement_email_data['job_sid'] = $job_sid;
                        $acknowledgement_email_data['job_title'] = $jobTitle;
                        $acknowledgement_email_data['employer_sid'] = $companyId;
                        $acknowledgement_email_data['first_name'] = $nameArray[0];
                        $acknowledgement_email_data['last_name'] = $nameArray[1];
                        $acknowledgement_email_data['email'] = $applicant_email;
                        $acknowledgement_email_data['phone_number'] = $data['applicant']['phoneNumber'];
                        $acknowledgement_email_data['date_applied'] = date('Y-m-d H:i:s');
                        common_indeed_acknowledgement_email($acknowledgement_email_data);
                        // send email to 'new applicant notification' users *** START *** ////////

                        $company_sid = $companyId;
                        $message_hf = message_header_footer_domain($company_sid, $company_name);
                        $replacement_array = array();
                        $replacement_array['site_url'] = base_url();
                        $replacement_array['date'] = month_date_year(date('Y-m-d'));
                        $replacement_array['job_title'] = $jobTitle;
                        $replacement_array['phone_number'] = $data['applicant']['phoneNumber'];

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
                        }

                        $resume_url = '';
                        $resume_anchor = '';
                        $profile_anchor = '<a href="'.base_url('applicant_profile/'.$job_applications_sid.'/'.$portal_applicant_jobs_list_sid).'" style="'.DEF_EMAIL_BTN_STYLE_DANGER.'"  download="resume" >View Profile</a>';

                        if (!empty($resume)) {
                            $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                            $resume_anchor = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
                        }

                        $applicant_notification_contacts = array();

                        if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                            $applicant_notification_contacts = get_notification_email_contacts($company_sid, 'new_applicant', $job_sid);

                            if (!empty($applicant_notification_contacts)) {
                                    foreach($applicant_notification_contacts as $contact) {
                                            $replacement_array['firstname'] = $nameArray[0];
                                            $replacement_array['lastname'] = $nameArray[1];
                                            $replacement_array['email'] = $applicant_email;
                                            $replacement_array['company_name'] = $company_name;
                                            $replacement_array['resume_link'] = $resume_anchor;
                                            $replacement_array['applicant_profile_link'] = $profile_anchor;
                                            $replacement_array['original_job_title'] = $original_job_title;
                                            log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                                            //mail($this->debug_email, 'Company notification for: ' . $company_name, 'applicant_email: ' . $applicant_email . ' Send Mail to: ' . $contact['email']);
                                    }
                                    //mail($this->debug_email, 'Indeed Applicant Notification', print_r($replacement_array, true ));
                            }
                        } // send email to 'new applicant notification' users *** END *** ////////

                        //check if screening Questionnaire is attached to the job - If Yes, Send screen questionnaire email to applicant ***  START ***
                        if(!empty($job_details)) {
                            $original_job_title = $job_details['Title'];
                            $questionnaire_sid = $job_details['questionnaire_sid'];
                        }

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

                                if($original_job_title != '') {
                                    $replacement_array['job_title'] = $original_job_title;
                                } else {
                                    $replacement_array['job_title'] = $jobTitle;
                                }

                                $replacement_array['applicant_name'] = $nameArray[0].'&nbsp;'.$nameArray[1];
                                $replacement_array['url'] = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                                if (!empty($replacement_array)) {
                                    foreach ($replacement_array as $key => $value) {
                                        $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                                        $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                                        $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                                    }
                                }

                                if (!empty($emailTemplateBody)) {
                                                $emailTemplateBody     = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{firstname}}', $nameArray[0], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{lastname}}', $nameArray[1], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{applicant_name}}', $nameArray[0] . ' ' . $nameArray[1], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{job_title}}', $replacement_array['job_title'], $emailTemplateBody);
                                                $emailTemplateBody     = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
                                }

                                $message_data = array();
                                $message_data['to_id'] = $applicant_email;
                                $message_data['from_type'] = 'employer';
                                $message_data['to_type'] = 'admin';
                                $message_data['job_id'] = $job_applications_sid;
                                $message_data['users_type'] = 'applicant';
                                $message_data['subject'] = $emailTemplateSubject;
                                $message_data['message'] = $emailTemplateBody;
                                $message_data['date'] = $date_applied;
                                $message_data['from_id'] = REPLY_TO;
                                $message_data['contact_name'] = $nameArray[0].'&nbsp;'.$nameArray[1];
                                $message_data['identity_key'] = generateRandomString(48);
                                $message_hf = message_header_footer_domain($company_sid, $company_name);
                                $secret_key = $message_data['identity_key'] . "__";
                                $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                                                . $emailTemplateBody
                                                . $message_hf['footer']
                                                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                                . $secret_key . '</div>';

                                $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                                sendMail(FROM_EMAIL_INFO, $applicant_email, $emailTemplateSubject, $autoemailbody, $company_name, FROM_EMAIL_INFO);
                                $sent_to_pm = common_save_message($message_data, NULL);
                                $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                            }
                        }
                    // *** END - Screening Questionnaire Email ***
                    /*END END END*/
                    }
                } else {
                    $applicant_email = $data['applicant']['email'];
                    $company_name = $data['job']['jobCompany'];
                    $company_sid = $this->all_feed_model->companyIdByName(strtolower(urldecode($company_name)));
                    $replacement_array = array();
                    $replacement_array['company_name'] = $data['job']['jobCompany'];
                    $replacement_array['job_title'] = $data['job']['jobTitle'];
                    $replacement_array['applicant_name'] = $data['applicant']['fullName'];
                    $replacement_array['resume_link'] = '';
                    $replacement_array['applicant_profile_link'] = '';
                    log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $applicant_email, $replacement_array, message_header_footer($company_sid, $company_name), 0);
                    //log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $this->debug_email, $replacement_array, message_header_footer($company_sid, $company_name));
                }
            } //old_flow
        }
    }

    private function isActiveFeed(){
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('indeed_paid');
        if(!$validSlug){
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}
