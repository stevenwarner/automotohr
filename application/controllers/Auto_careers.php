<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auto_careers extends CI_Controller
{

    private $debug_email = TO_EMAIL_DEV;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auto_careers_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    /**
     * 
     */
    private function addReport($source, $email, $type = 'add'){
        if($type == 'add'){
            $this->db
            ->insert('daily_job_counter', [
                'source' => $source,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                'already_exists' => 0
            ]);
        } else{
            $this->db
            ->where('email', $email)
            ->where('created_at', date('Y-m-d H:i:s', strtotime('now')))
            ->update('daily_job_counter', [
                'already_exists' => 1
            ]);
        }
    }

    public function index()
    {
        //
        $folder = APPPATH.'../../applicant/autocareers';
        //
        if(!is_dir($folder)){ mkdir($folder, 0777, true); }
        // Create json file for all filters job categories
        $categories_file = fopen($folder.'/AutoCareers_Applicant_Recieve_' . date('Y_m_d_H_i_s') . '.json', 'w');
        //
        $applicant_job_info         = file_get_contents('php://input');
        //
        fwrite($categories_file, $applicant_job_info);
        //
        fclose($categories_file);
        //
        if($applicant_job_info){
            //
            $this->add_applicant($applicant_job_info);
        } else{
            sendResponse(['error' => 'Invalid request']);
            exit(0);
        }
        
    }

    //
    private function add_applicant($applicant_job_info){
        //
            $video_type                 = '';
            $video_url                  = '';
            $address                    = '';
            $referred_by_name           = '';
            $referred_by_email          = '';
            $original_job_title         = '';
            $resume_aws_path            = '';
            $job_applications_sid       = '';
            $eeo_form                   = '';
            $job_type                   = '';
            $applicant_primary_picture  = '';
            $applicant_primary_resume   = '';
            $applicant_primary_CV       = '';
            $is_archive                 = 0;
            $for_notification                = 0;
            $applicant_primary_data     = array();
            $applicant_data             = json_decode($applicant_job_info, true);
            $auto_fill                  = $applicant_data['auto_fill'];

            if ($auto_fill == 1) {
                $applicant_sid              = $applicant_data['applicant_sid'];
                $applicant_old_data         = $this->auto_careers_model->fetch_applicant_info($applicant_sid);
                if (!empty($applicant_old_data)) {
                    $applicant_primary_picture  = $applicant_old_data['pictures'];
                    $applicant_primary_resume   = $applicant_old_data['resume'];
                    $applicant_primary_CV       = $applicant_old_data['cover_letter'];
                }
            }
            //
            $response = [];
            $response['error'] = 'Invalid request';
            /**
             * Add report
             */
            $this->addReport('AutoCareers', $applicant_data['email']);

            $applicant_data['email'] = fixEmailAddress($applicant_data['email'], 'gmail');
           
            //
            if(
                strpos($applicant_data['email'], '@devnull.facebook.com') !== false || 
                preg_match('/@(.*).ru/i', $applicant_data['email']) ||
                !isAllowedDomain($applicant_data['email'])
            ) {
                $response['error'] = "Domain Not Valid";
                sendResponse($response);
                exit(0);
            }
            // Check for blocked array
            if($this->db->where('LOWER(applicant_email)', strtolower($applicant_data['email']))->count_all_results('blocked_applicants')){
                $response['error'] = "Candidate is blocked";
                sendResponse($response);
                exit(0);
            }
            //
            if (isset($applicant_data['ip_address']) && !empty($applicant_data['ip_address'])) {
                $ip_address         = $applicant_data['ip_address'];
            } else {
                $ip_address         = '';
            }
            //
            $is_archive         = 0;
            $for_notification   = 0;
            // Lets check the city and IP
            if(!empty($ip_address) && empty($applicant_data['country'])){
                //
                $ip_details = getIpDetails($ip_address);
                //
                if(!empty($ip_details['country_name']) && !in_array(strtolower(trim($ip_details['country_name'])), ['united states', 'canada'])){
                    $is_archive         = 1;
                    $for_notification   = 1;
                }
            }
            
            //
            $referer_from           = 'https://auto.careers';
            $job_sid                = $applicant_data['job_sid'];
            $first_name             = $applicant_data['first_name'];
            $last_name              = $applicant_data['last_name'];
            $email                  = $applicant_data['email'];
            $phone_number           = $applicant_data['phone_number'];
            $city_name              = $applicant_data['city'];
            $state_sid              = $applicant_data['state'];
            $country_sid            = $applicant_data['country'];
            $eeo_form               = $applicant_data['EEO'];
            

            if (isset($applicant_data['user_agent']) && !empty($applicant_data['user_agent'])) {
                $user_agent         = $applicant_data['user_agent'];
            } else {
                $user_agent         = '';
            }

            // Check if applicant already applied to this job

            $date_applied   = date('Y-m-d H:i:s');

            if (isset($applicant_data['video_type']) && !empty($applicant_data['video_type'])) {
                $video_type = $applicant_data['video_type'];

                if ($video_type == 'upload') {
                    if ($_SERVER['HTTP_HOST']=='localhost') {
                        $source = 'https://www.automotohr.com/assets/uploaded_videos/57/sjg5h_shooting_stars.mp4';
                    } else {
                        $source = 'https://auto.careers/assets/uploaded_videos'.'/'.$applicant_data['video_url'];
                    } 
                    $video_directory = ROOTPATH.'assets/uploaded_videos/';   

                    $video_data = @file_get_contents($source);
                    @file_put_contents($video_directory.$applicant_data['video_url'], $video_data);
                }

                $video_url = $applicant_data['video_url'];
            }
            

            if (isset($applicant_data['YouTube_Video_Id']) && !empty($applicant_data['YouTube_Video_Id'])) {
                $YouTube_Video      = $applicant_data['YouTube_Video_Id'];
            }

            if (isset($applicant_data['address']) && !empty($applicant_data['address'])) {
                $address = $applicant_data['address'];
            }

            if (isset($applicant_data['referred_by_name']) && !empty($applicant_data['referred_by_name'])) {
                $referred_by_name = $applicant_data['referred_by_name'];
            }

            if (isset($applicant_data['referred_by_email']) && !empty($applicant_data['referred_by_email'])) {
                $referred_by_email = $applicant_data['referred_by_email'];
            }

            $job_details    = $this->auto_careers_model->get_job_detail($job_sid);
            //
            if(empty($job_details)){
                $response['error'] = "Job is inactive";
                sendResponse($response);
                exit(0);
            }

            if (!empty($job_details)) {
                $original_job_title = $job_details['Title'];
            }

            $job_type    = $job_details['JobType'];
            $company_sid = $job_details['user_sid'];
            
            if (check_company_status($company_sid) == 0) {
                $response['error'] = "Company not Found";
                sendResponse($response);
                exit(0);
            }

            if (isset($applicant_data['pictures']) && !empty($applicant_data['pictures'])) { //making Resume file to upload on AWS
                $base64Data = $applicant_data['pictures']; //Decode pdf content
                $picture_decoded = base64_decode($base64Data);
                $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                $fileName = 'auto_careers_' . generateRandomString(3) . '.' . $applicant_data['pictures_extension'];


                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777);
                }

                $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                fwrite($pdf, $picture_decoded);
                fclose($pdf); //close output file

                $profile_picture = generateRandomString(6) . "_" . $fileName;
                $aws = new AwsSdk();
                $aws->putToBucket($profile_picture, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS

                $applicant_primary_data['pictures'] = $profile_picture;
                unlink(FCPATH . 'assets/temp_files/' . $fileName);
            } else {
                if (!empty($applicant_primary_picture)) {
                    $applicant_primary_data['pictures'] = $applicant_primary_picture;
                }
            }

            if (isset($applicant_data['resume']) && !empty($applicant_data['resume'])) { //making Resume file to upload on AWS
                $base64Data = $applicant_data['resume']; //Decode pdf content
                $resume_decoded = base64_decode($base64Data);
                $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                $fileName = 'auto_careers_' . generateRandomString(3) . '.' . $applicant_data['resume_extension'];


                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777);
                }

                $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                fwrite($pdf, $resume_decoded);
                fclose($pdf); //close output file

                $resume = generateRandomString(6) . "_" . $fileName;
                $aws = new AwsSdk();
                $aws->putToBucket($resume, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS

                $applicant_primary_data['resume']   = $resume;
                $resume_aws_path                    = $resume;

                unlink(FCPATH . 'assets/temp_files/' . $fileName);
            } else {
                if (!empty($applicant_primary_resume)) {
                    $applicant_primary_data['resume']   = $applicant_primary_resume;
                    $resume_aws_path                    = $applicant_primary_resume;
                }
            }

            if (isset($applicant_data['cover_letter_base64']) && !empty($applicant_data['cover_letter_base64'])) {
                $base64Data = $applicant_data['cover_letter_base64']; //Decode pdf content
                $cover_letter_decoded = base64_decode($base64Data);
                $filePath = FCPATH . "assets/temp_files/"; //making Directory to store
                $fileName = 'auto_careers_' . generateRandomString(3) . '.' . $applicant_data['cover_letter_extension'];


                if (!file_exists($filePath)) {
                    mkdir($filePath, 0777);
                }

                $pdf = fopen($filePath . $fileName, 'w'); //Write data back to pdf file
                fwrite($pdf, $cover_letter_decoded);
                fclose($pdf); //close output file

                $cover_letter = generateRandomString(6) . "_" . $fileName;
                $aws = new AwsSdk();
                $aws->putToBucket($cover_letter, $filePath . $fileName, AWS_S3_BUCKET_NAME); //uploading file to AWS

                $applicant_primary_data['cover_letter'] = $cover_letter;
                unlink(FCPATH . 'assets/temp_files/' . $fileName);
            } else {
                if (!empty($applicant_primary_CV)) {
                    $applicant_primary_data['cover_letter']   = $applicant_primary_CV;
                }
            }

            $all_status = $this->auto_careers_model->get_default_status_sid_and_text($company_sid);
            $status_sid = $all_status['status_sid'];
            $status     = $all_status['status_name'];

            // Check if applicant has already applied in this company for any other job
            $portal_job_applications_sid = $this->auto_careers_model->check_job_applicant('company_check', $email, $company_sid);

            if ($portal_job_applications_sid == 'no_record_found') {

                $applicant_primary_data['job_sid']              = $job_sid;
                $applicant_primary_data['employer_sid']         = $company_sid;
                $applicant_primary_data['first_name']           = $first_name;
                $applicant_primary_data['last_name']            = $last_name;
                $applicant_primary_data['email']                = $email;
                $applicant_primary_data['phone_number']         = $phone_number;
                $applicant_primary_data['address']              = $address;
                $applicant_primary_data['country']              = $country_sid;
                $applicant_primary_data['state']                = $state_sid;
                $applicant_primary_data['city']                 = $city_name;
                $applicant_primary_data['video_type']           = $video_type;
                $applicant_primary_data['YouTube_Video']        = $video_url;
                $applicant_primary_data['referred_by_name']     = $referred_by_name;
                $applicant_primary_data['referred_by_email']    = $referred_by_email;

                $job_applications_sid = $this->auto_careers_model->save_applicant($applicant_primary_data);
                //
                send_full_employment_application($company_sid, $job_applications_sid, "applicant");
            } else {
                $job_applications_sid = $portal_job_applications_sid;
            }
            
            // Check if the user has already applied for this job
            $already_applied = $this->auto_careers_model->applicant_list_exists_check($job_applications_sid, $job_sid, $company_sid);
            //
            $company_name = $this->auto_careers_model->getCompanyName($company_sid);
            //
            mail('mubashir.saleemi123@gmail.com', 'Auto Careers - Applicant Recieve - ' . date('Y-m-d') . '', print_r([
                'CID' => $company_sid,
                'JID' => $job_sid,
                'APID' => $job_applications_sid,
                'AAPL' => $already_applied
            ], true));
            
            if ($already_applied <= 0) {
                //
                $insert_new_job = array();
                $insert_new_job['job_sid']                      = $job_sid;
                $insert_new_job['company_sid']                  = $company_sid;
                $insert_new_job['date_applied']                 = $date_applied;
                $insert_new_job['status']                       = $status;
                $insert_new_job['status_sid']                   = $status_sid;
                $insert_new_job['applicant_source']             = $referer_from;
                $insert_new_job['applicant_type']               = 'Applicant';
                $insert_new_job['eeo_form']                     = $eeo_form;
                $insert_new_job['ip_address']                   = $ip_address;
                $insert_new_job['user_agent']                   = $user_agent;
                $insert_new_job['resume']                       = $resume_aws_path;
                $insert_new_job['last_update']                  = date('Y-m-d');
                $insert_new_job['portal_job_applications_sid']  = $job_applications_sid;
                $insert_new_job['archived']                   = $is_archive;
                $insert_new_job['for_notification']             = $for_notification;
                
                $portal_applicant_jobs_list_sid = $this->auto_careers_model->add_applicant_job_details($insert_new_job);
                $response['applicant_sid'] = $job_applications_sid;
                $response['applicant_job_sid'] = $portal_applicant_jobs_list_sid;

                //
                //
                $profile_anchor = '<a href="' . STORE_FULL_URL_SSL . 'applicant_profile/' . $portal_job_applications_sid . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '"  download="resume" >View Profile</a>';

                //
                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume_aws_path);
                $resume_anchor = '<a  style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';

                

                // Send email
                $message_hf = message_header_footer_domain($company_sid, $company_name);
                $notifications_status = get_notifications_status($company_sid);

                // Set default status
                $applicant_notifications_status = 0;
                // Check notification exists
                if (!empty($notifications_status)) {
                    $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
                }
                // Applicant contacts array
                $applicant_notification_contacts        = array();
                // $resume_anchor = AWS_S3_BUCKET_URL.$resume;
                //
                if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                    $applicant_notification_contacts    = get_notification_email_contacts($company_sid, 'new_applicant', $job_sid);
                    $rp = [];
                    if (!empty($applicant_notification_contacts)) {
                        foreach ($applicant_notification_contacts as $contact) {
                            $replacement_array['city'] = $city_name;
                            $replacement_array['email'] = $email;
                            $replacement_array['lastname'] = $last_name;
                            $replacement_array['job_title'] = $job_details['Title'];
                            $replacement_array['firstname'] = $first_name;
                            $replacement_array['resume_link'] = $resume_anchor;
                            $replacement_array['phone_number'] = $phone_number;
                            $replacement_array['company_name'] = $company_name;
                            $replacement_array['original_job_title'] = $job_details['Title'];
                            $replacement_array['applicant_profile_link'] = $profile_anchor;
                            $rp = $replacement_array;
                            log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $company_sid, $job_sid, 'new_applicant_notification');
                        }
                        sendMail(REPLY_TO, 'mubashir.saleemi123@gmail.com', 'Auto.Careers Alert', print_r($rp, true), 'Accounts', REPLY_TO);
                    }
                }

                // Check if screening questionnaire is attached to email and send pass or fail email to applicant
                // Check if any questionnaire is attached with this job.
                if ($applicant_data['questionnaire_sid'] > 0) {
                    $questionnaire_serialize                    = '';
                    $total_score                                = 0;
                    $total_questionnaire_score                  = 0;
                    $q_passing                                  = 0;
                    $array_questionnaire                        = array();
                    $overall_status                             = 'Pass';
                    $screening_questionnaire_results            = array();

                    $post_questionnaire_sid             = $applicant_data['questionnaire_sid'];
                    $post_screening_questionnaires      = $this->auto_careers_model->get_screening_questionnaire_by_id($post_questionnaire_sid);
                    $array_questionnaire                = array();
                    $q_name                             = '';
                    $q_send_pass                        = '';
                    $q_pass_text                        = '';
                    $q_send_fail                        = '';
                    $q_fail_text                        = '';
                    if (sizeof($post_screening_questionnaires)) {
                        $q_name                             = $post_screening_questionnaires[0]['name'];
                        $q_send_pass                        = $post_screening_questionnaires[0]['auto_reply_pass'];
                        $q_pass_text                        = $post_screening_questionnaires[0]['email_text_pass'];
                        $q_send_fail                        = $post_screening_questionnaires[0]['auto_reply_fail'];
                        $q_fail_text                        = $post_screening_questionnaires[0]['email_text_fail'];
                    }
                    if (isset($applicant_data['all_questions_ids'])) {

                        $all_questions_ids                  = $applicant_data['all_questions_ids'];

                        foreach ($all_questions_ids as $key => $value) {
                            $q_passing                  = 0;
                            $post_questions_sid         = $value;
                            $caption                    = 'caption' . $value;
                            $type                       = 'type' . $value;
                            $answer                     = $applicant_data[$type] . $value;
                            $questions_type             = $applicant_data[$type];
                            $my_question                = '';
                            $individual_score           = 0;
                            $individual_passing_score   = 0;
                            $individual_status          = 'Pass';
                            $result_status              = array();

                            if (isset($applicant_data[$caption])) {
                                $my_question            = $applicant_data[$caption];
                            }

                            $my_answer                  = NULL;

                            if (isset($applicant_data[$answer])) {
                                $my_answer              = $applicant_data[$answer];
                            }

                            if ($questions_type != 'string') { // get the question possible score
                                $q_passing              = $this->auto_careers_model->get_possible_score_of_questions($post_questions_sid, $questions_type);
                            }

                            if ($my_answer != NULL) { // It is required question
                                if (is_array($my_answer)) {
                                    $answered                   = array();
                                    $answered_result_status     = array();
                                    $answered_question_score    = array();
                                    $total_questionnaire_score  += $q_passing;
                                    $is_string                  = 1;

                                    foreach ($my_answer as $answers) {
                                        $result                 = explode('@#$', $answers);
                                        $a                      = $result[0];
                                        $answered_question_sid  = $result[1];
                                        $question_details       = $this->auto_careers_model->get_individual_question_details($answered_question_sid);

                                        if (!empty($question_details)) {
                                            $questions_score            = $question_details['score'];
                                            $questions_result_status    = $question_details['result_status'];
                                            $questions_result_value     = $question_details['value'];
                                        }

                                        $score                      = $questions_score;
                                        $total_score                += $questions_score;
                                        $individual_score           += $questions_score;
                                        $individual_passing_score   = $q_passing;
                                        $answered[]                 = $a;
                                        $result_status[]            = $questions_result_status;
                                        $answered_result_status[]   = $questions_result_status;
                                        $answered_question_score[]  = $questions_score;
                                    }
                                } else { // hassan WORKING area
                                    $result                     = explode('@#$', $my_answer);
                                    $total_questionnaire_score  += $q_passing;
                                    $a                          = $result[0];
                                    $answered                   = $a;
                                    $answered_result_status     = '';
                                    $answered_question_score    = 0;

                                    if (isset($result[1])) {
                                        $answered_question_sid  = $result[1];
                                        $question_details       = $this->auto_careers_model->get_individual_question_details($answered_question_sid);

                                        if (!empty($question_details)) {
                                            $questions_score = $question_details['score'];
                                            $questions_result_status    = $question_details['result_status'];
                                            $questions_result_value     = $question_details['value'];
                                        }

                                        $is_string                  = 1;
                                        $score                      = $questions_score;
                                        $total_score                += $questions_score;
                                        $individual_score           += $questions_score;
                                        $individual_passing_score   = $q_passing;
                                        $result_status[]            = $questions_result_status;
                                        $answered_result_status     = $questions_result_status;
                                        $answered_question_score    = $questions_score;
                                    }
                                }

                                if (!empty($result_status)) {
                                    if (in_array('Fail', $result_status)) {
                                        $individual_status  = 'Fail';
                                        $overall_status     = 'Fail';
                                    }
                                }
                            } else { // it is optional question
                                $answered                   = '';
                                $individual_passing_score   = $q_passing;
                                $individual_score           = 0;
                                $individual_status          = 'Candidate did not answer the question';
                                $answered_result_status     = '';
                                $answered_question_score    = 0;
                            }

                            $array_questionnaire[$my_question] = array(
                                'answer'                    => $answered,
                                'passing_score'             => $individual_passing_score,
                                'score'                     => $individual_score,
                                'status'                    => $individual_status,
                                'answered_result_status'    => $answered_result_status,
                                'answered_question_score'   => $answered_question_score
                            );
                        }
                    }

                    $questionnaire_result               = $overall_status;
                    $datetime                           = date('Y-m-d H:i:s');
                    $remote_addr                        = getUserIP();
                    if (isset($_SERVER['HTTP_USER_AGENT'])){
                        $user_agent                         = $_SERVER['HTTP_USER_AGENT'];}
                    else if (isset($applicant_data['user_agent'])){
                        $user_agent                         = $applicant_data['user_agent'];}
                    $questionnaire_data = array(
                        'applicant_sid'             => $job_applications_sid,
                        'applicant_jobs_list_sid'   => $portal_applicant_jobs_list_sid,
                        'job_sid'                   => $job_sid,
                        'job_title'                 => $original_job_title,
                        'job_type'                  => $job_type,
                        'company_sid'               => $company_sid,
                        'questionnaire_name'        => $q_name,
                        'questionnaire'             => $array_questionnaire,
                        'questionnaire_result'      => $questionnaire_result,
                        'attend_timestamp'          => $datetime,
                        'questionnaire_ip_address'  => $remote_addr,
                        'questionnaire_user_agent'  => $user_agent
                    );

                    $questionnaire_serialize            = serialize($questionnaire_data);
                    $array_questionnaire_serialize      = serialize($array_questionnaire);

                    $screening_questionnaire_results = array(
                        'applicant_sid'                => $job_applications_sid,
                        'applicant_jobs_list_sid'       => $portal_applicant_jobs_list_sid,
                        'job_sid'                       => $job_sid,
                        'job_title'                     => $original_job_title,
                        'job_type'                      => $job_type,
                        'company_sid'                   => $company_sid,
                        'questionnaire_name'            => $q_name,
                        'questionnaire'                 => $array_questionnaire_serialize,
                        'questionnaire_result'          => $questionnaire_result,
                        'attend_timestamp'              => $datetime,
                        'questionnaire_ip_address'      => $remote_addr,
                        'questionnaire_user_agent'      => $user_agent
                    );


                    $this->auto_careers_model->update_questionnaire_result($portal_applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result, $user_agent);
                    $this->auto_careers_model->insert_questionnaire_result($screening_questionnaire_results);
                    $send_mail                          = false;
                    $mail_body                          = '';

                    if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                        $send_mail                      = true;
                        $mail_body                      = $q_pass_text;
                    }

                    if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                        $send_mail                      = true;
                        $mail_body                      = $q_fail_text;
                    }

                    if ($send_mail) {
                        $from                           = TO_EMAIL_INFO;
                        $fromname                       = $company_name;
                        $title                          = $data['job_details']['Title'];
                        $subject                        = 'Job Application Questionnaire Status for "' . $title . '"';
                        $to                             = $email;
                        $mail_body                      = str_replace('{{company_name}}', ucwords($company_name), $mail_body);
                        $mail_body                      = str_replace('{{applicant_name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                        $mail_body                      = str_replace('{{job_title}}', $title, $mail_body);
                        $mail_body                      = str_replace('{{first_name}}', ucwords($first_name), $mail_body);
                        $mail_body                      = str_replace('{{last_name}}', ucwords($last_name), $mail_body);
                        $mail_body                      = str_replace('{{company-name}}', ucwords($company_name), $mail_body);
                        $mail_body                      = str_replace('{{applicant-name}}', ucwords($first_name . ' ' . $last_name), $mail_body);
                        $mail_body                      = str_replace('{{job-title}}', $title, $mail_body);
                        $mail_body                      = str_replace('{{first-name}}', ucwords($first_name), $mail_body);
                        $mail_body                      = str_replace('{{last-name}}', ucwords($last_name), $mail_body);
                        sendMail($from, $to, $subject, $mail_body, $fromname);
                    }
                }

                //Getting data for EEO Form Starts
                if ($eeo_form == 'Yes') {
                    $eeo_data_to_insert                                     = array();
                    $eeo_data_to_insert['users_type']                       = "applicant";
                    $eeo_data_to_insert['application_sid']                  = $job_applications_sid;
                    $eeo_data_to_insert['portal_applicant_jobs_list_sid']   = $portal_applicant_jobs_list_sid;
                    $eeo_data_to_insert['us_citizen']                       = $applicant_data['us_citizen'];
                    $eeo_data_to_insert['visa_status']                      = $applicant_data['visa_status'];
                    $eeo_data_to_insert['group_status']                     = $applicant_data['group_status'];
                    $eeo_data_to_insert['veteran']                          = $applicant_data['veteran'];
                    $eeo_data_to_insert['disability']                       = $applicant_data['disability'];
                    $eeo_data_to_insert['gender']                           = $applicant_data['gender'];
                    $eeo_data_to_insert['is_expired']                       = 1;

                    $this->auto_careers_model->save_eeo_form($eeo_data_to_insert);

                    //
                    $dataToUpdate = array();
                    $dataToUpdate['gender'] = strtolower($applicant_data['gender']);
                    update_user_gender($job_applications_sid, 'applicant', $dataToUpdate);
                    //
                }
            } else {
                $response['error'] = 'Applied';
                /**
                * Add report
                */
                $this->addReport('AutoCareers', $applicant_data['email'], 'update');
                $resume_to_update = array();
                $resume_to_update['resume'] = $resume_aws_path;

                $this->auto_careers_model->update_job_related_resume($portal_job_applications_sid, $company_sid, $job_sid, $resume_to_update);
            }

            //
            sendResponse($response);
            exit(0);
        
    }

    //
    function mover(){
        //
        $dt = date('Y_m_d');
        //
        $path = APPPATH."../../applicant/autocareers/*_{$dt}_*.json";
        //
        $files = glob($path, GLOB_BRACE);
        //
        foreach ($files as $file) {
            $this->add_applicant(file_get_contents($file));
        }
        echo "All done";
        exit(0);
    }
}


//
function sendResponse($response){
    header('Content-Type: application/json');
    echo json_encode($response);
}
//
function getIpDetails($ip){
    //
    $key = getCreds()->AHR->IP->Key;
    //
    $curl = curl_init();
    //
    curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.ipstack.com/{$ip}?access_key={$key}&fields=country_name,%20city,%20latitude,%20longitude,%20region_name&output=json&language=en",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    //
    $response = json_decode(curl_exec($curl), true);
    //
    curl_close($curl);
    return $response;
}
//
function getCitiesList(){
    return json_decode(file_get_contents(APPPATH.'../cities.json'), true);
}

//            
function isAllowedDomain($email){
    $allowedDomains = [
        '.com',
        '.us',
        '.ca',
        '.edu',
        '.gov',
        '.org',
        '.net'
    ];
    $t = explode('.', trim(strtolower($email)));
return (int)in_array('.'.trim($t[count($t) -1]), $allowedDomains);
}