<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_screening_questionnaire extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('job_screening_questionnaire_model');
    }

    public function index($verification_key = null, $pre_fill_flag = null) {
        $applicant_details = $this->job_screening_questionnaire_model->get_screening_questionnaire_by_key($verification_key);
        $list = array();
        $job_title = '';
        $job_type = '';
        
        if(!empty($applicant_details)){ // sid, portal_job_applications_sid, company_sid, job_sid, questionnaire, score, passing_score
            $questionnaire_sid = 0;
            $applicant_jobs_list_sid = $applicant_details[0]['sid'];
            $applicant_sid = $applicant_details[0]['portal_job_applications_sid'];
            $company_sid = $applicant_details[0]['company_sid'];
            $logo_info=$this->job_screening_questionnaire_model->get_company_logo($company_sid);
           
            $job_sid = $applicant_details[0]['job_sid'];
            $questionnaire_attended = $applicant_details[0]['questionnaire'];
            $questionnaire_manual_sent = $applicant_details[0]['questionnaire_manual_sent'];
            $manual_questionnaire_sid = $applicant_details[0]['manual_questionnaire_sid'];            
            $company_details = $this->job_screening_questionnaire_model->get_company_details($company_sid);
            if(!empty($questionnaire_attended)) { // applicant as already answered the questionnaire. show thank you message.
                $data['page_title'] = 'Job Screening Questionnaire';
                $data['status'] = 'answered';
                $data['applicant_details'] = array();
//                echo '<pre>';
//                print_r(unserialize($questionnaire_attended));
//                exit();
                $this->load->view('manage_employer/job_screening_questionnaire_view', $data);            
            } else {
                $questionnaire_sid_details = $this->job_screening_questionnaire_model->get_screening_questionnaire_id($job_sid);

                if(!empty($questionnaire_sid_details)) {
                    $questionnaire_sid = $questionnaire_sid_details[0]['questionnaire_sid'];
                    $job_title = $questionnaire_sid_details[0]['Title'];
                    $job_type = $questionnaire_sid_details[0]['JobType'];
                }
                
                if($questionnaire_manual_sent > 0 && $manual_questionnaire_sid > 0) {
                    $questionnaire_sid = $manual_questionnaire_sid;
                }

                if($questionnaire_sid != NULL && $questionnaire_sid > 0) {
                    $questionnaire_status = $this->job_screening_questionnaire_model->check_screening_questionnaires($questionnaire_sid);                   
                    $portal_screening_questionnaires = $this->job_screening_questionnaire_model->get_screening_questionnaire_by_id($questionnaire_sid);
                            
                    if(!empty($portal_screening_questionnaires) && $questionnaire_status == 'found') {
                        $questionnaire_name = $portal_screening_questionnaires[0]['name'];
                        $list['q_name'] = $portal_screening_questionnaires[0]['name'];
                        $list['q_passing'] = $portal_screening_questionnaires[0]['passing_score'];
                        $list['q_send_pass'] = $portal_screening_questionnaires[0]['auto_reply_pass'];
                        $list['q_send_fail'] = $portal_screening_questionnaires[0]['auto_reply_fail'];
                        $list['q_pass_text'] = '';//$portal_screening_questionnaires[0]['email_text_pass'];
                        $list['q_fail_text'] = '';//$portal_screening_questionnaires[0]['email_text_fail'];
                        $list['my_id'] = 'q_question_' . $questionnaire_sid;
                        $screening_questions_numrows = $this->job_screening_questionnaire_model->get_screenings_count_by_id($questionnaire_sid);
                        
                        if ($screening_questions_numrows > 0) {
                            $screening_questions = $this->job_screening_questionnaire_model->get_screening_questions_by_id($questionnaire_sid);
                            //echo '<pre>'; print_r($screening_questions); echo '</pre>';
                            foreach ($screening_questions as $qkey => $qvalue) {
                                $questions_sid = $qvalue['sid'];
                                $list['q_question_' . $questionnaire_sid][] = array('questions_sid' => $questions_sid, 'caption' => $qvalue['caption'], 'is_required' => $qvalue['is_required'], 'question_type' => $qvalue['question_type']);
                                $screening_answers_numrows = $this->job_screening_questionnaire_model->get_screening_answer_count_by_id($questions_sid);

                                if ($screening_answers_numrows) {
                                    $screening_answers = $this->job_screening_questionnaire_model->get_screening_answers_by_id($questions_sid);

                                    foreach ($screening_answers as $akey => $avalue) {
                                        $list['q_answer_' . $questions_sid][] = array('value' => $avalue['value'], 'score' => $avalue['sid']);
                                    }
                                }
                            }
                        }
                    
                        $this->form_validation->set_rules('action', 'action', 'required|xss_clean|trim');

                        if ($this->form_validation->run() == false) {
                            $data['page_title'] = 'Job Screening Questionnaire';
                            $data['status'] = 'Found';
                            $data['job_title'] = $job_title;
                            $data['job_type'] = $job_type;
                            $data['applicant_details'] = $applicant_details;
                            $data['job_details'] = $list;
                            $data['verification_key'] = $verification_key;
                            $data['questionnaire_sid'] = $questionnaire_sid;
                            $data['job_sid'] = $job_sid;
                            //$this->load->view('manage_employer/job_screening_questionnaire_view', $data);
                        } else {
                            $post_questionnaire_sid                             = $_POST['questionnaire_sid'];
                            $post_screening_questionnaires                      = $this->job_screening_questionnaire_model->get_screening_questionnaire_by_id($post_questionnaire_sid);
                            $questionnaire_serialize                            = '';
                            $total_score                                        = 0;
                            $total_questionnaire_score                          = 0;
                            $array_questionnaire                                = array();
                            $q_name                                             = $post_screening_questionnaires[0]['name'];
                            $q_send_pass                                        = $post_screening_questionnaires[0]['auto_reply_pass'];
                            $q_pass_text                                        = $post_screening_questionnaires[0]['email_text_pass'];
                            $q_send_fail                                        = $post_screening_questionnaires[0]['auto_reply_fail'];
                            $q_fail_text                                        = $post_screening_questionnaires[0]['email_text_fail'];
                            $overall_status                                     = 'Pass';
                            $is_string = 0;
                            $screening_questionnaire_results                    = array();
                            $all_questions_ids                                  = $_POST['all_questions_ids'];

                            foreach ($all_questions_ids as $key => $value) {
                                $q_passing = 0;
                                $post_questions_sid = $value;
                                $caption = 'caption' . $value;
                                $type = 'type' . $value;
                                $answer = $_POST[$type] . $value;
                                $questions_type = $_POST[$type];
                                $my_question = '';
                                $individual_score = 0;
                                $individual_passing_score = 0;
                                $individual_status = 'Pass';
                                $result_status = array();
                         
                                if (isset($_POST[$caption])) {
                                    $my_question = $_POST[$caption];
                                }

                                $my_answer = NULL;

                                if (isset($_POST[$answer])) {
                                    $my_answer = $_POST[$answer];
                                }
                                
                                
                                if($questions_type != 'string') { // get the question possible score
                                    $q_passing = $this->job_screening_questionnaire_model->get_possible_score_of_questions($post_questions_sid, $questions_type);
                                }

                                if($my_answer != NULL) { // It is required question           
                                    if (is_array($my_answer)) {
                                        $answered = array();
                                        $answered_result_status = array();
                                        $answered_question_score = array();
                                        $total_questionnaire_score += $q_passing;
                                        $is_string = 1;
                                        
                                        foreach ($my_answer as $answers) {
                                                $result = explode('@#$', $answers);
                                                $a = $result[0];
                                                $answered_question_sid = $result[1];
                                                $question_details = $this->job_screening_questionnaire_model->get_individual_question_details($answered_question_sid);
                                                
                                                if(!empty($question_details)) {
                                                    $questions_score = $question_details['score'];
                                                    $questions_result_status = $question_details['result_status'];
                                                    $questions_result_value = $question_details['value'];
                                                }
                                                
                                                $score = $questions_score;
                                                $total_score += $questions_score;
                                                $individual_score += $questions_score;
                                                $individual_passing_score = $q_passing;
                                                $answered[] = $a;
                                                $result_status[] = $questions_result_status;
                                                $answered_result_status[] = $questions_result_status;
                                                $answered_question_score[] = $questions_score;
                                        }
                                    } else {
                                        $result = explode('@#$', $my_answer);
                                        $total_questionnaire_score += $q_passing;
                                        $a = $result[0];
                                        $answered = $a;
                                        $answered_result_status = '';
                                        $answered_question_score = 0;

                                        if (isset($result[1])) {
                                            $answered_question_sid = $result[1];
                                            $question_details = $this->job_screening_questionnaire_model->get_individual_question_details($answered_question_sid);

                                            if(!empty($question_details)) {
                                                $questions_score = $question_details['score'];
                                                $questions_result_status = $question_details['result_status'];
                                                $questions_result_value = $question_details['value'];
                                            }
                                            
                                            $is_string = 1;
                                            $score = $questions_score;
                                            $total_score += $questions_score;
                                            $individual_score += $questions_score;
                                            $individual_passing_score = $q_passing;
                                            $result_status[] = $questions_result_status;
                                            $answered_result_status = $questions_result_status;
                                            $answered_question_score = $questions_score;
                                        }
                                    }
                                    
                                    if(!empty($result_status)) {
                                        if(in_array('Fail', $result_status)) {
                                            $individual_status = 'Fail';
                                            $overall_status = 'Fail';
                                        }
                                    }
                                } else { // it is optional question
                                    $answered = '';
                                    $individual_passing_score = $q_passing;
                                    $individual_score = 0;
                                    $individual_status = 'Candidate did not answer the question';
                                    $answered_result_status = '';
                                    $answered_question_score = 0;
                                }

                                $array_questionnaire[$my_question] = array( 'answer' => $answered,
                                                                            'passing_score' => $individual_passing_score,
                                                                            'score' => $individual_score,
                                                                            'status' => $individual_status,
                                                                            'answered_result_status' => $answered_result_status,
                                                                            'answered_question_score' => $answered_question_score);  
                            } // this is the end of foreach

//                            if ($q_passing <= $total_score) { // he passed it
//                                $questionnaire_result = 'pass';
//                            } else { // he failed it
//                                $questionnaire_result = 'fail';
//                            }
                            $questionnaire_result = $overall_status;
                            $datetime = date('Y-m-d H:i:s');
                            $remote_addr = getUserIP();
                            $user_agent = $_SERVER['HTTP_USER_AGENT'];
//                            if (!empty($array_questionnaire)) {
                                $questionnaire_data = array('applicant_sid' => $applicant_sid,
                                                            'applicant_jobs_list_sid' => $applicant_jobs_list_sid,
                                                            'job_sid' => $job_sid,
                                                            'job_title' => $job_title,
                                                            'job_type' => $job_type,
                                                            'company_sid' => $company_sid,
                                                            'questionnaire_name' => $questionnaire_name,
                                                            'questionnaire' => $array_questionnaire,
                                                            'questionnaire_result' => $questionnaire_result,
                                                            'attend_timestamp' => $datetime,
                                                            'questionnaire_ip_address' => $remote_addr,
                                                            'questionnaire_user_agent' => $user_agent);
                                $questionnaire_serialize = serialize($questionnaire_data);
                                $array_questionnaire_serialize = serialize($array_questionnaire);
//                            }
                                
                                $screening_questionnaire_results = array(   'applicant_sid' => $applicant_sid,
                                                                            'applicant_jobs_list_sid' => $applicant_jobs_list_sid,
                                                                            'job_sid' => $job_sid,
                                                                            'job_title' => $job_title,
                                                                            'job_type' => $job_type,
                                                                            'company_sid' => $company_sid,
                                                                            'questionnaire_name' => $questionnaire_name,
                                                                            'questionnaire' => $array_questionnaire_serialize,
                                                                            'questionnaire_result' => $questionnaire_result,
                                                                            'attend_timestamp' => $datetime,
                                                                            'questionnaire_ip_address' => $remote_addr,
                                                                            'questionnaire_user_agent' => $user_agent);
                            //print_r($array_questionnaire); exit;
                            $this->job_screening_questionnaire_model->update_questionnaire_result($applicant_jobs_list_sid, $questionnaire_serialize, $total_questionnaire_score, $total_score, $questionnaire_result);
                            $this->job_screening_questionnaire_model->insert_questionnaire_result($screening_questionnaire_results);
                            //redirect('Job_screening_questionnaire/'.$verification_key, 'refresh');

                            $send_mail = false; // send email 
                            $from = FROM_EMAIL_INFO;
                            $fromname = $company_details['CompanyName'];
                            $mail_body = '';

                            if ($questionnaire_result == 'Pass' && (isset($q_send_pass) && $q_send_pass == '1') && !empty($q_pass_text)) { // send pass email
                                $send_mail = true;
                                $mail_body = $q_pass_text;
                                //echo "<br> YOU PASSED IT: <br>".$mail_body;
                            }

                            if ($questionnaire_result == 'Fail' && (isset($q_send_fail) && $q_send_fail == '1') && !empty($q_fail_text)) { // send fail email
                                $send_mail = true;
                                $mail_body = $q_fail_text;
                                //echo "<br> YOU FAILED IT: <br>".$mail_body;
                            }

                            if ($send_mail) { // get Applicant email address
                                $to = $this->job_screening_questionnaire_model->get_applicant_email($applicant_sid, 'portal_job_applications');
                                $subject = $job_title;
                                $applicant_data = $this->job_screening_questionnaire_model->get_applicant_details($applicant_sid);
                                $applicant_fname = $applicant_data['first_name'];
                                $applicant_lname = $applicant_data['last_name'];

                                $mail_body = str_replace('{{company_name}}', ucwords($company_details['CompanyName']), $mail_body);
                                $mail_body = str_replace('{{applicant_name}}', ucwords($applicant_fname . ' ' . $applicant_lname), $mail_body);
                                $mail_body = str_replace('{{job_title}}', $job_title, $mail_body);
                                $mail_body = str_replace('{{first_name}}', ucwords($applicant_fname), $mail_body);
                                $mail_body = str_replace('{{last_name}}', ucwords($applicant_lname), $mail_body);

                                $mail_body = str_replace('{{company-name}}', ucwords($company_details['CompanyName']), $mail_body);
                                $mail_body = str_replace('{{applicant-name}}', ucwords($applicant_fname . ' ' . $applicant_lname), $mail_body);
                                $mail_body = str_replace('{{job-title}}', $job_title, $mail_body);
                                $mail_body = str_replace('{{first-name}}', ucwords($applicant_fname), $mail_body);
                                $mail_body = str_replace('{{last-name}}', ucwords($applicant_lname), $mail_body);

                                sendMail($from, $to, $subject, $mail_body, $fromname);
                                sendMail($from, 'ahassan.egenie@gmail.com', $subject, $mail_body, $fromname);
                            }
                            redirect(base_url('Job_screening_questionnaire').'/'.$verification_key, "refresh");                   
                        }
                    } else {
                        $data['status'] = 'not_found';
                    }// not found check - hererere
                } // questionnaire check herer
                $data['page_title'] = 'Job Screening Questionnaire';
                $data['job_title'] = $job_title;
                $data['job_type'] = $job_type;
                $data['logo_info']=$logo_info;
               
                $this->load->view('manage_employer/job_screening_questionnaire_view', $data); 
            }
        } else {
            $data['page_title'] = 'Job Screening Questionnaire';
            $data['status'] = 'Not Found';
            $data['applicant_details'] = array();
            $this->load->view('manage_employer/job_screening_questionnaire_view', $data);
            redirect(base_url(), "refresh");
        }
        
    }
    
    public function send_interview_questionnaires($applicant_id, $applicant_list_id, $job_sid, $questionnaire_sid) {
        if ($this->session->userdata('logged_in')) {
            $screening_questionnaire_key = $this->job_screening_questionnaire_model->generate_questionnaire_key($applicant_list_id);
            $session_data = $this->session->userdata('logged_in');
            $company_sid = $session_data['company_detail']['sid'];
            $employer_sid = $session_data['employer_detail']['sid'];
            $company_name = $session_data['company_detail']['CompanyName'];
            $company_email = $session_data['company_detail']['email'];
            $employer_email = $session_data['employer_detail']['email'];
            $applicant_details = $this->job_screening_questionnaire_model->get_applicant_details($applicant_id);
            $job_title = $this->job_screening_questionnaire_model->get_job_title($job_sid);
            $email_template_information = $this->job_screening_questionnaire_model->get_email_template_data(SCREENING_QUESTIONNAIRE_FOR_JOB);
            $date_time = date('Y-m-d H:i:s');
            
            if(empty($email_template_information)){
                $email_template_information = array('subject' =>'{{company_name}} - Screening Questionnaire for {{job_title}}',
                                                    'text' => '<p>Dear {{applicant_name}},</p>
                                                                <p>You have successfully applied to the following position "{{job_title}}" and your job application is in our system. </p>
                                                                <p><strong>Please complete the following Questionnaire by clicking on the link below to enhance your chances to be short listed. We are excited to learn more about you. </strong></p>
                                                                <p>{{url}}</p>
                                                                <p>Thank you, again, for your interest in {{company_name}}</p>',
                                                    'from_name' => '{{company_name}}'
                                                    );
            }
            
            $emailTemplateBody = $email_template_information['text'];
            $emailTemplateSubject = $email_template_information['subject'];
            $emailTemplateFromName = $email_template_information['from_name'];
            $applicant_email = $applicant_details['email'];
            $replacement_array = array();
            $replacement_array['company_name'] = $company_name;
            $replacement_array['company-name'] = $company_name;
            $replacement_array['job_title'] = $job_title;
            $replacement_array['applicant_name'] = $applicant_details['first_name'].'&nbsp;'.$applicant_details['last_name'];
            $replacement_array['url'] = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';
            
            if (!empty($replacement_array)) {
                foreach ($replacement_array as $key => $value) {
                    $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                    $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                    $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                }
            }
            
            $message_data = array();
            $message_data['to_id'] = $applicant_email;
            $message_data['from_type'] = 'employer';
            $message_data['to_type'] = 'admin';
            $message_data['job_id'] = $applicant_id;
            $message_data['users_type'] = 'applicant';
            $message_data['subject'] = $emailTemplateSubject;
            $message_data['message'] = $emailTemplateBody;
            $message_data['date'] = $date_time;
            $message_data['from_id'] = REPLY_TO;
            $message_data['contact_name'] = $applicant_details['first_name'].'&nbsp;'.$applicant_details['last_name'];
            $message_data['identity_key'] = generateRandomString(48);
            $message_hf = message_header_footer_domain($company_sid, $company_name);
            $secret_key = $message_data['identity_key'] . "__";
            
            $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                . '<p>Subject: ' . $emailTemplateSubject . '</p>'
                . $emailTemplateBody
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';

            $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

            sendMail(FROM_EMAIL_INFO, $applicant_email, $emailTemplateSubject, $autoemailbody, $company_name, FROM_EMAIL_INFO);
            //sendMail(REPLY_TO, 'mubashir.saleemi123@gmail.com', $subject, $autoemailbody, $from_name, REPLY_TO);

            $sent_to_pm = common_save_message($message_data, NULL);
            //log_and_send_templated_email('346', $applicant_email, $replacement_array, message_header_footer($company_sid, $company_name));            
            $this->job_screening_questionnaire_model->update_questionnaire_status($applicant_list_id);
            $this->session->set_flashdata('message', '<b>Success:</b> Screening Questionnaire sent successfully');
            
            $emailData = array(
                                'date' => $date_time,
                                'subject' => $emailTemplateSubject,
                                'email' => $applicant_email,
                                'message' => $autoemailbody,
                                'username' => $company_name,
                            );

            save_email_log_common($emailData);
            
            redirect('applicant_profile/'.$applicant_id.'/'.$applicant_list_id);
        }
    }
    
    function send_questionnaire_email($applicationData) {
        $template = get_portal_email_template($applicationData['employer_sid'], 'application_acknowledgement_letter');

        if (!empty($template) && $template[0]['enable_auto_responder'] == 1) {
            $title = $applicationData['job_title'];
            $body = $template[0]['message_body'];
            $body = str_replace('{{applicant_name}}', $applicationData['first_name'] . ' ' . $applicationData['last_name'], $body);
            $body = str_replace('{{job_title}}', $title, $body);
            $email = $template[0];
            $from = REPLY_TO;
            $subject = $email['subject'];
            $from_name = $email['from_name'];
            $message_data = array();
            $message_data['to_id'] = $applicationData['email'];
            $message_data['from_type'] = 'employer';
            $message_data['to_type'] = 'admin';
            $message_data['job_id'] = $applicationData['sid'];
            $message_data['users_type'] = 'applicant';
            $message_data['subject'] = 'Application Acknowledgement Letter';
            $message_data['message'] = $body;
            $message_data['date'] = date('Y-m-d H:i:s');
            $message_data['from_id'] = REPLY_TO;
            $message_data['contact_name'] = $applicationData['first_name'] . ' ' . $applicationData['last_name'];
            $message_data['identity_key'] = generateRandomString(48);
            $message_hf = message_header_footer_domain($applicationData['employer_sid'], $applicationData['company_name']);
            $secret_key = $message_data['identity_key'] . "__";

            $autoemailbody = $message_hf['header']
                . '<p>Subject: ' . $subject . '</p>'
                . $body
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';

            sendMail(REPLY_TO, $applicationData['email'], $subject, $autoemailbody, $from_name, REPLY_TO);
            //sendMail(REPLY_TO, 'mubashir.saleemi123@gmail.com', $subject, $autoemailbody, $from_name, REPLY_TO);

            $sent_to_pm = common_save_message($message_data, NULL);
            $email_log_autoresponder = array();
            $email_log_autoresponder['company_sid'] = $applicationData['employer_sid'];
            $email_log_autoresponder['sender'] = REPLY_TO;
            $email_log_autoresponder['receiver'] = $applicationData['email'];
            $email_log_autoresponder['from_type'] = 'employer';
            $email_log_autoresponder['to_type'] = 'admin';
            $email_log_autoresponder['users_type'] = 'applicant';
            $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
            $email_log_autoresponder['subject'] = $subject;
            $email_log_autoresponder['message'] = $autoemailbody;
            $email_log_autoresponder['job_or_employee_id'] = $applicationData['sid'];
            $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
        } /* else {
            mail('mubashir.saleemi123@gmail.com', 'Indeed acknowledgement - opt out', print_r($applicationData, true));
        } */
    }
    
    function log_and_send_templated_email($template_id, $to, $replacement_array = array(), $message_hf = array()) {
        $emailTemplateData = get_email_template($template_id);
        $emailTemplateBody = $emailTemplateData['text'];
        $emailTemplateSubject = $emailTemplateData['subject'];
        $emailTemplateFromName = $emailTemplateData['from_name'];

        if (!empty($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
                $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
            }
        }

        $from = $emailTemplateData['from_email'];
        $subject = $emailTemplateSubject;
        $from_name = $emailTemplateData['from_name'];

        if ($from_name == '{{company_name}}' && isset($replacement_array['company_name'])) {
            $from_name = $replacement_array['company_name'];
        }

        if (!empty($message_hf)) {
            $body = $message_hf['header']
                . $emailTemplateBody
                . $message_hf['footer'];
        } else {
            $body = EMAIL_HEADER
                . $emailTemplateBody
                . EMAIL_FOOTER;
        }

        log_and_sendEmail($from, $to, $subject, $body, $from_name);
    }
}