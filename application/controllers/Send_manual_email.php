<?php defined('BASEPATH') or exit('No direct script access allowed');

class Send_manual_email extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in')) {
            require_once(APPPATH . 'libraries/aws/aws.php');
            $this->load->model('portal_email_templates_model');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            echo 'Error: Please login.';
            exit(0);
        }
        $data = $this->session->userdata('logged_in');
        $company_detail = $data['company_detail'];
        $company_sid = $company_detail['sid'];
        $company_name = $company_detail['CompanyName'];
        $employer_id = $data['employer_detail']['sid'];
        $sent_flag = false;
        $current_timestamp = date('Y-m-d H:i:s');
        $letter = $this->input->post('action');
        $applicant_ids = $this->input->post('ids');
        //
        $fromArray = array('{{applicant_name}}', '{{first_name}}', '{{last_name}}', '{{job_title}}', '{{email}}', '{{date}}');
        $today = reset_datetime(array('datetime' => date('Y-m-d', strtotime('now')), '_this' => $this, 'type' => 'company', 'with_timezone' => true));
        //
        foreach ($applicant_ids as $applicant_id) {
            $applicant_data = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
            $rejection_email_template = $this->portal_email_templates_model->get_portal_email_template_by_code($letter, $company_sid);

            if (empty($rejection_email_template)) { // portal email template not found - Email will not be sent to applicant(s)
                echo 'Error: Email not sent due to missing email template.';
                exit(0);
            }
            //
            if (!empty($applicant_data)) {
                $title = $applicant_data['job_title'];
                $email = $rejection_email_template;
                //
                $body = $email['message_body'];
                //
                $subject = $email['subject'];
                replace_magic_quotes($subject);
                $from_name = replace_magic_quotes($email['from_name']);
                $message_data = array();
                $message_data['to_id'] = $applicant_data['email'];
                $message_data['from_type'] = 'employer';
                $message_data['to_type']   = 'applicant';
                $message_data['job_id']    = $applicant_data['sid'];
                $message_data['users_type'] = 'employee';

                $message_data['subject'] = 'Rejection Letter';
                if ($letter == 'application_acknowledgement_letter')
                    $message_data['subject'] = 'Application Acknowledgement Letter';

                // Added on: 29-04-2019
                replace_magic_quotes($body, $fromArray, array(
                    $applicant_data['first_name'] . ' ' . $applicant_data['last_name'],
                    $applicant_data['first_name'],
                    $applicant_data['last_name'],
                    $title,
                    $applicant_data['email'],
                    $today
                ));


                // Aded on: 29-04-2019
                replace_magic_quotes($body);

                $message_data['message']                                = $body;
                $message_data['date']                                   = $current_timestamp;
                $message_data['from_id']                                = $employer_id;
                $message_data['contact_name']                           = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                $message_data['identity_key']                           = generateRandomString(49);
                $message_hf                                             = message_header_footer_domain($company_sid, $company_name);
                $secret_key                                             = $message_data['identity_key'] . "__";

                $autoemailbody                                          = $message_hf['header']
                    . '<p>Subject: ' . $subject . '</p>'
                    . $body
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                sendMail(REPLY_TO, $applicant_data['email'], $subject, $autoemailbody, $from_name, REPLY_TO);
                $this->portal_email_templates_model->save_message($message_data);
                $sent_flag = true;


                // $email_log_autoresponder = array();
                // $email_log_autoresponder['company_sid'] = $applicant_data['employer_sid'];
                // $email_log_autoresponder['sender'] = REPLY_TO;
                // $email_log_autoresponder['receiver'] = $applicant_data['email'];
                // $email_log_autoresponder['from_type'] = 'employer';
                // $email_log_autoresponder['to_type'] = 'admin';
                // $email_log_autoresponder['users_type'] = 'applicant';
                // $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                // $email_log_autoresponder['subject'] = $subject;
                // $email_log_autoresponder['message'] = $autoemailbody;
                // $email_log_autoresponder['job_or_employee_id'] = $applicant_data['sid'];
                // $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
            }
        }
        // Updated on: 29-04-2019
        if (!$sent_flag) {
            echo 'Error: Could not send email.';
            exit(0);
        }
        echo 'Success: Email sent to selected applicants.';
        exit(0);
    }

    // function to send bulk email to selected applicants
    function send_bulk_email()
    {
        $data              = $this->session->userdata('logged_in');
        $company_detail    = $data['company_detail'];
        $company_sid       = $company_detail['sid'];
        $company_name      = $company_detail['CompanyName'];
        $employer_id       = $data['employer_detail']['sid'];
        $sent_flag         = false;
        $current_timestamp = date('Y-m-d H:i:s');
        $action            = $this->input->post('action');
        $applicant_ids     = explode(',', $this->input->post('ids'));
        $post_subject      = $this->input->post('subject');
        $post_body         = $this->input->post('message');
        $temp_id           = $this->input->post('template_id');
        $job_titles        = json_decode($this->input->post('job_titles'));
        $attach_body       = '';
        $attachments       = $this->portal_email_templates_model->get_all_email_template_attachments($temp_id);

        if (sizeof($attachments) > 0) {
            $attach_body .= '<br> Please Review The Following Attachments: <br>';

            foreach ($attachments as $attachment) {
                $attach_body .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a> <br>';
            }
        }
        //
        $fromArray = array('{{company_name}}', '{{date}}', '{{first_name}}', '{{last_name}}', '{{job_title}}', '{{applicant_name}}', '{{email}}');
        //
        $today           = new DateTime();
        $today           = reset_datetime(array('datetime' => $today->format('Y-m-d'), '_this' => $this, 'type' => 'company', 'with_timezone' => true));

        foreach ($applicant_ids as $applicant_id) {
            $applicant_data  = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
            $job_title       = isset($job_titles[$applicant_id]) ? $job_titles[$applicant_id] : $applicant_data['job_title'];
            $applicant_fname = $applicant_data['first_name'];
            $applicant_lname = $applicant_data['last_name'];
            //
            $toArray = array($company_name, $today, $applicant_fname, $applicant_lname, $job_title, $applicant_fname . ' ' . $applicant_lname, $applicant_data['email']);
            $subject = $post_subject;
            $body    = $post_body;
            // Added on: 29-04-2019
            replace_magic_quotes($subject, $fromArray, $toArray);
            replace_magic_quotes($body, $fromArray, $toArray);
            $body .= $attach_body;

            // Aded on: 29-04-2019
            replace_magic_quotes($body);

            $from_name                    = $company_name;
            $message_data                 = array();
            $message_data['to_id']        = $applicant_data['email'];
            $message_data['from_type']    = 'employer';
            $message_data['to_type']      = 'applicant';
            $message_data['job_id']       = $applicant_data['sid'];
            $message_data['users_type']   = 'employee';
            $message_data['subject']      = $subject;
            $message_data['message']      = $body;
            $message_data['date']         = $current_timestamp;
            $message_data['from_id']      = $employer_id;
            $message_data['contact_name'] = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
            $message_data['identity_key'] = generateRandomString(48);
            $message_hf                   = message_header_footer_domain($company_sid, $company_name);
            $secret_key                   = $message_data['identity_key'] . "__";
            $autoemailbody                = $message_hf['header']
                . '<p>Subject: ' . $subject . '</p>'
                . $body
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';


            if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                $file        = explode(".", $_FILES['message_attachment']['name']);
                $file_name   = str_replace(" ", "-", $file[0]);
                $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                $aws         = new AwsSdk();
                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                $message_data['attachment'] = $messageFile;
                sendMailWithAttachment(REPLY_TO, $applicant_data['email'], $subject, $autoemailbody, $from_name, $_FILES['message_attachment'], REPLY_TO);
            } else {
                sendMail(REPLY_TO, $applicant_data['email'], $subject, $autoemailbody, $from_name, REPLY_TO);
            }

            $this->portal_email_templates_model->save_message($message_data);
            $sent_flag = true;

            // $email_log_autoresponder = array();
            // $email_log_autoresponder['company_sid'] = $applicant_data['employer_sid'];
            // $email_log_autoresponder['sender'] = REPLY_TO;
            // $email_log_autoresponder['receiver'] = $applicant_data['email'];
            // $email_log_autoresponder['from_type'] = 'employer';
            // $email_log_autoresponder['to_type'] = 'admin';
            // $email_log_autoresponder['users_type'] = 'applicant';
            // $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
            // $email_log_autoresponder['subject'] = $subject;
            // $email_log_autoresponder['message'] = $autoemailbody;
            // $email_log_autoresponder['job_or_employee_id'] = $applicant_data['sid'];
            // $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
        }
    }

    // Updated on: 29-04-2019
    function send_candidate_email()
    {
        $data = $this->session->userdata('logged_in');
        $company_detail = $data['company_detail'];
        $company_sid    = $company_detail['sid'];
        $company_name   = $company_detail['CompanyName'];
        $employer_id    = $data['employer_detail']['sid'];
        $sent_flag      = false;
        $current_timestamp = date('Y-m-d H:i:s');
        $action            = $this->input->post('action');
        $applicant_ids     = explode(',', $this->input->post('ids'));
        $list_ids          = explode(',', $this->input->post('list_ids'));
        $post_subject      = $this->input->post('subject');
        $post_body         = $this->input->post('message');
        $toemail           = $this->input->post('to_email');
        $employee          = $this->input->post('employee');
        // $template_from_email = $this->input->post('from_email');
        $template_from_email = REPLY_TO;
        $job_titles          = explode(',', $this->input->post('job_titles'));
        $final_body          = '';
        $start               = strpos($post_body, '{{block_start}}');
        $end                 = strpos($post_body, '{{block_end}}');
        $repeat              = substr($post_body, $start + 15, $end - $start - 15);
        $i                   = 0;

        replace_magic_quotes($post_subject);

        if (!isset($employee)) {
            $i = 0;

            foreach ($applicant_ids as $applicant_id) {
                $applicant_data  = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
                $job_title       = isset($job_titles[$i]) ? $job_titles[$i] : $applicant_data['job_title'];
                $applicant_fname = $applicant_data['first_name'];
                $applicant_lname = $applicant_data['last_name'];
                $body            = $repeat;
                //
                $resume_replace_with = '';

                if (!empty($applicant_data['resume']))
                    $resume_replace_with = 'Resume: <a href="' . AWS_S3_BUCKET_URL . $applicant_data['resume'] . '" style="background-color: #0000ff; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block"  download="resume" >View Resume</a>';

                // 
                replace_magic_quotes(
                    $body,
                    array(
                        '{{company_name}}', '{{applicant_name}}', '{{email}}',
                        '{{phone_number}}', '{{city}}', '{{job_title}}',
                        '{{download_resume}}', '{{link}}'
                    ),
                    array(
                        $company_name, $applicant_fname . " " . $applicant_lname,
                        $applicant_data['email'], $applicant_data['phone_number'],
                        $applicant_data['city'], $job_title, $resume_replace_with,
                        '<a href="' . base_url('applicant_profile/' . $applicant_id . '/' . $list_ids[$i++]) . '" style="background-color: #ff6600; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" >View Profile</a> '
                    )
                );
                $body .= '<hr>';
                $final_body .= $body;
            }

            $post_body = str_replace($repeat, $final_body, $post_body);
            $post_body = str_replace('{{block_start}}', '', $post_body);
            $post_body = str_replace('{{block_end}}', '', $post_body);
            $to_emails = explode(',', $toemail);

            foreach ($to_emails as $email) {
                $subject = str_replace('{{company_name}}', $company_name, $post_subject);
                $subject = str_replace('{{employee_name}}', $email, $subject);
                $post_body = str_replace('{{employee_name}}', $email, $post_body);
                $post_body = str_replace('{{sender_name}}', $data["employer_detail"]["first_name"] . ' ' . $data["employer_detail"]["last_name"], $post_body);
                $post_body = str_replace('{{sender_email}}', $data["employer_detail"]["email"], $post_body);
                $post_body = str_replace('{{company_name}}', $company_name, $post_body);

                replace_magic_quotes($post_body);
                $from_name                    = $company_name;
                $message_data                 = array();
                $message_data['to_id']        = $email;
                $message_data['from_type']    = 'employer';
                $message_data['to_type']      = 'employer';
                $message_data['job_id']       = $email;
                $message_data['users_type']   = 'employee';
                $message_data['subject']      = $subject;
                $message_data['message']      = $post_body;
                $message_data['date']         = $current_timestamp;
                $message_data['from_id']      = $employer_id;
                $message_data['contact_name'] = $email;
                $message_data['identity_key'] = generateRandomString(49);
                $message_hf                   = message_header_footer_domain($company_sid, $company_name);
                $secret_key                   = $message_data['identity_key'] . "__";
                $autoemailbody                = $message_hf['header']
                    . '<p>Subject: ' . $subject . '</p>'
                    . $post_body
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                // sendMail($template_from_email, $email, $subject, $autoemailbody, $from_name, REPLY_TO);
                $sent_to_pm  = common_save_message($message_data);
                log_and_sendEmail($template_from_email, $email, $subject, $autoemailbody, $from_name);
                // $email_log_autoresponder = array();
                // $email_log_autoresponder['company_sid'] = $company_sid;
                // $email_log_autoresponder['sender'] = $template_from_email;
                // $email_log_autoresponder['receiver'] = $email;
                // $email_log_autoresponder['from_type'] = 'admin';
                // $email_log_autoresponder['to_type'] = 'employee';
                // $email_log_autoresponder['users_type'] = 'employee';
                // $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                // $email_log_autoresponder['subject'] = $subject;
                // $email_log_autoresponder['message'] = $autoemailbody;
                // $email_log_autoresponder['job_or_employee_id'] = $email;
                // $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
            }

            exit(0);
        }

        // when employee is set
        $employee_ids = explode(',', $employee);

        foreach ($applicant_ids as $applicant_id) {
            $applicant_data  = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
            $job_title       = isset($job_titles[$i]) ? $job_titles[$i] : $applicant_data['job_title'];
            $applicant_fname = $applicant_data['first_name'];
            $applicant_lname = $applicant_data['last_name'];
            $body            = str_replace('{{company_name}}', $company_name, $repeat);
            $body            = str_replace('{{applicant_name}}', $applicant_fname . " " . $applicant_lname, $body);
            $body            = str_replace('{{email}}', $applicant_data['email'], $body);
            $body            = str_replace('{{phone_number}}', $applicant_data['phone_number'], $body);
            $body            = str_replace('{{city}}', $applicant_data['city'], $body);
            $body            = str_replace('{{job_title}}', $job_title, $body);

            if (!empty($applicant_data['resume'])) {
                $body                                                       = str_replace('{{download_resume}}', 'Resume: <a href="' . AWS_S3_BUCKET_URL . $applicant_data['resume'] . '" style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '"  download="resume" >View Resume</a>', $body);
            } else {
                $body                                                       = str_replace('{{download_resume}}', '', $body);
            }

            $body                                                           = str_replace('{{link}}', '<a href="' . base_url('applicant_profile/' . $applicant_id . '/' . $list_ids[$i++]) . '" style="' . DEF_EMAIL_BTN_STYLE_INFO . '" >View Profile</a> ', $body);
            $body                                                           .= '<hr>';
            $final_body                                                     .= $body;
        }

        $post_body                                                          = str_replace($repeat, $final_body, $post_body);
        $post_body                                                          = str_replace('{{block_start}}', '', $post_body);
        $post_body                                                          = str_replace('{{block_end}}', '', $post_body);

        $this->load->model('Hr_documents_management_model', 'HRDMM');
        foreach ($employee_ids as $employee_id) {
            //
            if (!$this->HRDMM->isActiveUser($employee_id)) {
                continue;
            }
            $applicant_data                                                 = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
            $applicant_fname                                                = $applicant_data['first_name'];
            $applicant_lname                                                = $applicant_data['last_name'];
            $subject                                                        = str_replace('{{company_name}}', $company_name, $post_subject);
            $subject                                                        = str_replace('{{employee_name}}', $applicant_fname . ' ' . $applicant_lname, $subject);
            $post_body                                                      = str_replace('{{employee_name}}', $applicant_fname . ' ' . $applicant_lname, $post_body);
            $post_body                                                      = str_replace('{{sender_name}}', $data["employer_detail"]["first_name"] . ' ' . $data["employer_detail"]["last_name"], $post_body);
            $post_body                                                      = str_replace('{{sender_email}}', $data["employer_detail"]["email"], $post_body);
            $post_body                                                      = str_replace('{{company_name}}', $company_name, $post_body);
            replace_magic_quotes($post_body);
            $from_name                                                      = $company_name;
            $message_data                                                   = array();
            $message_data['to_id']                                          = $employee_id;
            $message_data['from_type']                                      = 'employer';
            $message_data['to_type']                                        = 'employer';
            $message_data['job_id']                                         = null;
            $message_data['users_type']                                     = 'employee';
            $message_data['subject']                                        = $subject;
            $message_data['message']                                        = $post_body;
            $message_data['date']                                           = $current_timestamp;
            $message_data['from_id']                                        = $employer_id;
            $message_data['contact_name']                                   = $applicant_fname . ' ' . $applicant_lname;
            $message_data['identity_key']                                   = generateRandomString(49);
            $message_hf                                                     = message_header_footer_domain($company_sid, $company_name);
            $secret_key                                                     = $message_data['identity_key'] . "__";
            $autoemailbody                                                  = $message_hf['header']
                . '<p>Subject: ' . $subject . '</p>'
                . $post_body
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';

            $employee_email                                                 = $this->portal_email_templates_model->getemployeeemail($employee_id);

            if ($employee_email != 'not_found') {
                // sendMail($template_from_email, $employee_email, $subject, $autoemailbody, $from_name, REPLY_TO);
                // $sent_to_pm = common_save_message($message_data);
                $this->portal_email_templates_model->save_message($message_data);
            }

            log_and_sendEmail($template_from_email, $employee_email, $subject, $autoemailbody, $from_name);
            // $email_log_autoresponder = array();
            // $email_log_autoresponder['company_sid'] = $company_sid;
            // $email_log_autoresponder['sender'] = $template_from_email;
            // $email_log_autoresponder['receiver'] = $applicant_data['email'];
            // $email_log_autoresponder['from_type'] = 'admin';
            // $email_log_autoresponder['to_type'] = 'employee';
            // $email_log_autoresponder['users_type'] = 'employee';
            // $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
            // $email_log_autoresponder['subject'] = $subject;
            // $email_log_autoresponder['message'] = $autoemailbody;
            // $email_log_autoresponder['job_or_employee_id'] = $employee_id;
            // $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
        }
    }






    function send_bulk_email_employees()
    {

        $data              = $this->session->userdata('logged_in');
        $company_detail    = $data['company_detail'];
        $company_sid       = $company_detail['sid'];
        $company_name      = $company_detail['CompanyName'];
        $employer_id       = $data['employer_detail']['sid'];
        $sent_flag         = false;
        $current_timestamp = date('Y-m-d H:i:s');
        $employee_ids     = explode(',', $this->input->post('ids'));
        $post_subject      = $this->input->post('subject');
        $post_body         = $this->input->post('message');
        $temp_id           = $this->input->post('template_id');
        $attach_body       = '';
        $attachments       = $this->portal_email_templates_model->get_all_email_template_attachments($temp_id);


        if (sizeof($attachments) > 0) {
            $attach_body .= '<br> Please Review The Following Attachments: <br>';

            foreach ($attachments as $attachment) {
                $attach_body .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a> <br>';
            }
        }
        //
        $fromArray = array('{{company_name}}', '{{date}}', '{{first_name}}', '{{last_name}}', '{{job_title}}', '{{employee_name}}', '{{email}}');
        //
        $today           = new DateTime();
        $today           = reset_datetime(array('datetime' => $today->format('Y-m-d'), '_this' => $this, 'type' => 'company', 'with_timezone' => true));

        foreach ($employee_ids as $employee_id) {

            $employee_data  = $this->portal_email_templates_model->get_employee_data($employee_id, $company_sid);

            //
            $toArray = array($company_name, $today, $employee_data['first_name'], $employee_data['last_name'], $employee_data['job_title'], $employee_data['first_name'] . ' ' . $employee_data['last_name'], $employee_data['email']);
            $subject = $post_subject;
            $body    = $post_body;
            replace_magic_quotes($subject, $fromArray, $toArray);
            replace_magic_quotes($body, $fromArray, $toArray);
            $body .= $attach_body;

            replace_magic_quotes($body);

            $from_name                    = $company_name;
            $message_data                 = array();
            $message_data['to_id']        = $employee_data['email'];
            $message_data['from_type']    = 'employer';
            $message_data['to_type']      = 'employee';
            $message_data['users_type']   = 'employee';
            $message_data['subject']      = $subject;
            $message_data['message']      = $body;
            $message_data['date']         = $current_timestamp;
            $message_data['from_id']      = $employer_id;
            $message_data['contact_name'] = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
            $message_data['identity_key'] = generateRandomString(48);
            $message_hf                   = message_header_footer_domain($company_sid, $company_name);
            $secret_key                   = $message_data['identity_key'] . "__";
            $autoemailbody                = $message_hf['header']
                . '<p>Subject: ' . $subject . '</p>'
                . $body
                . $message_hf['footer']
                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                . $secret_key . '</div>';


            if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                $file        = explode(".", $_FILES['message_attachment']['name']);
                $file_name   = str_replace(" ", "-", $file[0]);
                $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];
                $aws         = new AwsSdk();
                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                $message_data['attachment'] = $messageFile;

                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $employee_data['email'],
                    'message' => $autoemailbody,
                    'username' => $from_name
                );
                save_email_log_common($emailData);

                sendMailWithAttachment(REPLY_TO, $employee_data['email'], $subject, $autoemailbody, $from_name, $_FILES['message_attachment'], $from_name);
            } else {

                log_and_sendEmail(REPLY_TO, $employee_data['email'], $subject, $autoemailbody, $from_name);
            }

            $sent_flag = true;
        }
    }


    ///
    function send_still_interested_email()
    {

        $data = $this->session->userdata('logged_in');
        $company_detail = $data['company_detail'];
        $company_sid    = $company_detail['sid'];
        $company_name   = $company_detail['CompanyName'];
        $applicant_ids     = explode(',', $this->input->post('ids'));
        $job_titles          = explode(',', $this->input->post('job_titles'));

        //
        $temp_id           = 7920;
        $fromArray = array('{{company_name}}', '{{first_name}}', '{{last_name}}', '{{job_title}}', '{{applicant_name}}', '{{email}}');
        $still_interested_email_template = $this->portal_email_templates_model->getSingleTemplateDetails($temp_id);

        $i = 0;

        foreach ($applicant_ids as $applicant_id) {
            $applicant_data  = $this->portal_email_templates_model->get_applicant_data($applicant_id, $company_sid);
            $job_title       = isset($job_titles[$i]) ? $job_titles[$i] : $applicant_data['job_title'];

            $applicant_fname = $applicant_data['first_name'];
            $applicant_lname = $applicant_data['last_name'];
            //
            $toArray = array($company_name, $applicant_fname, $applicant_lname, $job_title, $applicant_fname . ' ' . $applicant_lname, $applicant_data['email']);
            $subject = $still_interested_email_template['subject'];
            $body    = $still_interested_email_template['message_body'];

            replace_magic_quotes($subject, $fromArray, $toArray);
            replace_magic_quotes($body, $fromArray, $toArray);

            $from_name                    = $company_name;
            $message_hf                   = message_header_footer_domain($company_sid, $company_name);

            $body = $message_hf['header'] . $body . $message_hf['footer'];

            log_and_sendEmail(REPLY_TO, $applicant_data['email'], $subject, $body, $from_name);
            $i++;

        }

    }
  
}
