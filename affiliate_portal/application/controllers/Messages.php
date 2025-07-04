<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Messages extends CI_Controller {
     public function __construct() {
        parent::__construct();
        $this->load->model('message_model');
    }

	public function index() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data                                                               = array();
            $affiliate_data                                                     = $this->session->userdata('affiliate_loggedin');
            $data['session']                                                    = $affiliate_data;
            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $from_username = '';
            $email = '';
            $subject = '';
            $start_date = '';
            $end_date = '';
            $search_activated = false;

            if ($this->uri->segment(2) != '') {
                $from_username = trim($this->uri->segment(2));
                
                if ($from_username == 'all') {
                    $from_username = '';
                } else {
                    $from_username = urldecode($from_username);
                }
                
                $email = trim($this->uri->segment(3));
                
                 if ($email == 'all') {
                    $email = '';
                } else {
                    $email = urldecode($email);
                }
                
                $subject = trim($this->uri->segment(4));
                
                 if ($subject == 'all') {
                    $subject = '';
                } else {
                    $subject = urldecode($subject);
                }
                
                $start_date = trim($this->uri->segment(5));
                
                if ($start_date == 'all') {
                    $start_date = '';
                } else {
                    $start_date = urldecode($start_date);
                }
                
                $end_date = trim($this->uri->segment(6));
                
                if ($end_date == 'all') {
                    $end_date = '';
                } else {
                    $end_date = urldecode($end_date);
                }
                
                $search_activated = true;
            }

            $data['from_username'] = $from_username;
            $data['email'] = $email;
            $data['subject'] = $subject;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $between = '';

            if ($start_date != '' || $end_date != '') {
                if ($start_date != '') {
                    $start_date = explode('-', $start_date);
                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                } else {
                    $start_date = '2015-01-01 00:00:00';
                }

                if ($end_date != '') {
                    $end_date = explode('-', $end_date);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "message_date between '" . $start_date . "' and '" . $end_date . "'";
            }

            $messages = $this->message_model->get_all_inbox_messages($between,$affiliate_detail['sid'], $affiliate_detail['email']);

            foreach ($messages as $myKey => $message) {
                $sid = $message['to_id'];
                if (filter_var($sid, FILTER_VALIDATE_EMAIL)) {
                    $messages[$myKey]['full_name'] = $sid;
                    $messages[$myKey]['email'] =  $sid;
                } else if ($sid == 'administrator') {
                    $CI = &get_instance();
                    $CI->db->where('id', 1);
                    $CI->db->where('active', 1);
                    $CI->db->from('administrator_users');

                    $record_obj = $CI->db->get();
                    $record_arr = $record_obj->result_array();
                    $record_obj->free_result();

                    if (sizeof($record_arr) > 0) {
                        $messages[$myKey]['full_name'] = $record_arr[0]['first_name'].' '.$record_arr[0]['first_name'];
                        $messages[$myKey]['email'] = $record_arr[0]['email'];
                    } else {
                        $messages[$myKey]['full_name'] = "Record Not Found";
                        $messages[$myKey]['email'] = "Email Not Found";
                    }
                } else {
                    $CI = &get_instance();

                    $CI->db->select('full_name, email');
                    $CI->db->where('sid', $sid);
                    $CI->db->from('marketing_agencies');

                    $record_obj = $CI->db->get();
                    $record_arr = $record_obj->result_array();
                    $record_obj->free_result();

                    if (sizeof($record_arr) > 0) {
                        $messages[$myKey]['full_name'] = $record_arr[0]['full_name'];
                        $messages[$myKey]['email'] = $record_arr[0]['email'];
                    } else {
                        $messages[$myKey]['full_name'] = "Record Not Found";
                        $messages[$myKey]['email'] = "Email Not Found";
                    }
                }
            }

            if ($search_activated) {
                $return_messages = array();
                $search_message = $messages;

                foreach ($search_message as $key => $value) {
                    $db_name = $value['username'];
                    $db_email = $value['email'];
                    $db_subject = $value['subject'];

                    if ($from_username != '') { 
                        $name_array = explode(' ', $from_username);
                        $dbname_array = explode(' ', $db_name);
                        $found = false;

                        foreach ($name_array as $names) {
                            if (in_array($names, $dbname_array)) {
                                $found = true;
                                continue;
                            }
                        }

                        if ($found) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($email != '') { 
                        if (strcasecmp($email, $db_email) == 0) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($subject != '') { 
                        $subject_array = explode(' ', $subject);
                        $db_subject_array = explode(' ', $db_subject);
                        $found = false;

                        foreach ($subject_array as $subjects) {
                            if (in_array($subjects, $db_subject_array)) {
                                $found = true;
                                continue;
                            }
                        }

                        if ($found) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if($value['message_date'] >= $start_date && $value['message_date'] <= $end_date){
                        $return_messages[] = $value;
                        continue;
                    }
                }

                $data['messages'] = $return_messages;
            } else { 
                $data['messages'] = $messages;
            }

            $data['title']                                                      = 'Private Messages';
            $data['name']                                                       = $affiliate_data['affiliate_users']['full_name'];
            $data['security_details']                                           = $security_details;
            $data['page']                                                       = 'Inbox Messages';
            $data['view_type']                                                  = 'inbox';
            
            $this->load->view('main/header', $data);
            $this->load->view('private_messages/inbox');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        } 
	}

    public function outbox() { 
        if ($this->session->userdata('affiliate_loggedin')) {
            $data                                                               = array();
            $affiliate_data                                                     = $this->session->userdata('affiliate_loggedin');
            $data['session']                                                    = $affiliate_data;
            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);

            $from_username = '';
            $email = '';
            $subject = '';
            $start_date = '';
            $end_date = '';
            $search_activated = false;

            if ($this->uri->segment(2) != '') {
                $from_username = trim($this->uri->segment(2));
                
                if ($from_username == 'all') {
                    $from_username = '';
                } else {
                    $from_username = urldecode($from_username);
                }
                
                $email = trim($this->uri->segment(3));
                
                 if ($email == 'all') {
                    $email = '';
                } else {
                    $email = urldecode($email);
                }
                
                $subject = trim($this->uri->segment(4));
                
                 if ($subject == 'all') {
                    $subject = '';
                } else {
                    $subject = urldecode($subject);
                }
                
                $start_date = trim($this->uri->segment(5));
                
                if ($start_date == 'all') {
                    $start_date = '';
                } else {
                    $start_date = urldecode($start_date);
                }
                
                $end_date = trim($this->uri->segment(6));
                
                if ($end_date == 'all') {
                    $end_date = '';
                } else {
                    $end_date = urldecode($end_date);
                }
                
                $search_activated = true;
            }

            $data['from_username'] = $from_username;
            $data['email'] = $email;
            $data['subject'] = $subject;
            $data['start_date'] = $start_date;
            $data['end_date'] = $end_date;
            $between = '';

            if ($start_date != '' || $end_date != '') {
                if ($start_date != '') {
                    $start_date = explode('-', $start_date);
                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                } else {
                    $start_date = '01-01-1970 00:00:00';
                }

                if ($end_date != '') {
                    $end_date = explode('-', $end_date);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "message_date between '" . $start_date . "' and '" . $end_date . "'";
            }

            $messages = $this->message_model->get_all_outbox_messages($between,$affiliate_detail['sid'], 1, 'outbox');

            foreach ($messages as $myKey => $message) {
//                if (is_numeric($message['to_id'])) {
//                    $result_data = $this->message_model->get_name_by_id($message['to_id'], $message['users_type']);
//                    $messages[$myKey]['to_id'] = $result_data['name'];
//                    $messages[$myKey]['email'] = $result_data['email'];
//                } else {
//                    if($messages[$myKey]['to_type'] == 'admin'){
//                        $messages[$myKey]['email'] = 'dev@automotohr.com';
//                        $messages[$myKey]['to_id'] = 'Admin';
//                    }
////                    if (filter_var(trim($message['to_id']))) {
//                    else{
//                        $messages[$myKey]['email'] = $message['to_id'];
//                        $messages[$myKey]['to_id'] = $this->message_model->fetch_name($message['to_id']);
//                    }
//                }
                $sid = $message['to_id'];
                if (filter_var($sid, FILTER_VALIDATE_EMAIL)) {
                    $messages[$myKey]['full_name'] = $sid;
                    $messages[$myKey]['email'] =  $sid;
                } else if ($sid == 'administrator') {
                    $CI = &get_instance();
                    $CI->db->where('id', 1);
                    $CI->db->where('active', 1);
                    $CI->db->from('administrator_users');

                    $record_obj = $CI->db->get();
                    $record_arr = $record_obj->result_array();
                    $record_obj->free_result();

                    if (sizeof($record_arr) > 0) {
                        $messages[$myKey]['full_name'] = $record_arr[0]['first_name'].' '.$record_arr[0]['first_name'];
                        $messages[$myKey]['email'] = $record_arr[0]['email'];
                    } else {
                        $messages[$myKey]['full_name'] = "Record Not Found";
                        $messages[$myKey]['email'] = "Email Not Found";
                    }
                } else {
                    $CI = &get_instance();

                    $CI->db->select('full_name, email');
                    $CI->db->where('sid', $sid);
                    $CI->db->from('marketing_agencies');

                    $record_obj = $CI->db->get();
                    $record_arr = $record_obj->result_array();
                    $record_obj->free_result();

                    if (sizeof($record_arr) > 0) {
                        $messages[$myKey]['full_name'] = $record_arr[0]['full_name'];
                        $messages[$myKey]['email'] = $record_arr[0]['email'];
                    } else {
                        $messages[$myKey]['full_name'] = "Record Not Found";
                        $messages[$myKey]['email'] = "Email Not Found";
                    }
                }
            }
//            echo '<pre>';
//            print_r($messages);
//            die();


            if ($search_activated) {
                $return_messages = array();
                $search_message = $messages;

                foreach ($search_message as $key => $value) {
                    $db_name = strtolower($value['full_name']);
                    $db_email = $value['email'];
                    $db_subject = $value['subject'];

                    if ($from_username != '') { 
                        $name_array = explode(' ', $from_username);
                        $dbname_array = explode(' ', $db_name);
                        $found = false;

//                        echo '<pre>';
//                        echo $from_username;
//                        print_r($name_array);
//                        print_r($dbname_array);
                        foreach ($name_array as $names) {
                            if (in_array(strtolower($names), $dbname_array)) {
                                $found = true;
                                continue;
                            }
                        }

                        if ($found) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($email != '') {
                        if (strcasecmp($email, $db_email) == 0) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($subject != '') { 
                        $subject_array = explode(' ', $subject);
                        $db_subject_array = explode(' ', $db_subject);
                        $found = false;

                        foreach ($subject_array as $subjects) {
                            if (in_array($subjects, $db_subject_array)) {
                                $found = true;
                                continue;
                            }
                        }

                        if ($found) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if($value['message_date'] >= $start_date && $value['message_date'] <= $end_date){
                        $return_messages[] = $value;
                        continue;
                    }
                }

                $data['messages'] = $return_messages;
            } else { 
                $data['messages'] = $messages;
            }

            $data['title']                                                      = 'Private Messages';
            $data['name']                                                       = $affiliate_data['affiliate_users']['full_name'];
            $data['security_details']                                           = $security_details;
            $data['page']                                                       = 'Outbox Messages';
            $data['view_type']                                                  = 'outbox';

            $this->load->view('main/header', $data);
            $this->load->view('private_messages/outbox');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        } 
    }

    public function view_message ($type, $sid) { 
        if ($this->session->userdata('affiliate_loggedin')) {
            $data                                                               = array();
            $affiliate_data                                                     = $this->session->userdata('affiliate_loggedin');
            $data['session']                                                    = $affiliate_data;
            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);

            if ($type == 'inbox') {
                $message = $this->message_model->get_inbox_message($sid);
                $data['message_detail'] = $message;
            } else if ($type == 'outbox') {
                $message = $this->message_model->get_outbox_message($sid);
                $data['message_detail'] = $message;
            }
            $attachments = $this->message_model->get_employer_messages_attachments($sid);
            if(!empty($message_data[0]['attachment']) && $message_data[0]['attachment'] != NULL){
                $attachments[]['attachment'] = $message_data[0]['attachment'];
            }

            $data['message_type']                                               = $type;
            $data['message_attachments']                                        = $attachments;
            $data['title']                                                      = 'Private Messages';
            $data['name']                                                       = $affiliate_data['affiliate_users']['full_name'];
            $data['security_details']                                           = $security_details;
            $data['page']                                                       = 'View Messages';

            $this->load->view('main/header', $data);
            $this->load->view('private_messages/view_email');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function message_task()
    {
        $action = $this->input->post('action');
        $message_id = $this->input->post("sid");
        if ($action == 'delete') {
            $this->message_model->delete_message($message_id);
        }
    }

    public function compose_message($sid = NULL) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data                                                               = array();
            $affiliate_data                                                     = $this->session->userdata('affiliate_loggedin');
            $data['session']                                                    = $affiliate_data;
            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $affiliate_sid                                                      = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($affiliate_sid);
            $subject = array(   'field' => 'subject',
                                'label' => 'Subject',
                                'rules' => 'xss_clean|trim|required');
            
            $message = array(   'field' => 'message',
                                'label' => 'Message',
                                'rules' => 'xss_clean|trim|required');
            check_access_permissions($security_details, 'dashboard', 'private_messages');
            
            $config[] = $subject;
            $config[] = $message;
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $data['title']                                                      = 'Private Messages';
            $data['name']                                                       = $affiliate_data['affiliate_users']['full_name'];
            $data['security_details']                                           = $security_details;
            $affiliates_list                                                    = $this->message_model->get_all_affiliates($affiliate_sid);
            $data['affiliates']                                                 = $affiliates_list;
            $data['page']                                                       = 'Compose Messages';

            if ($this->form_validation->run() === FALSE) {
                if ($sid != NULL){
                    $user_data = get_affiliate_name_and_email($sid);
                    $to_email = $user_data['email'];
                    $data['to_email']                                           = $to_email;
                }

                $this->load->view('main/header', $data);
                $this->load->view('private_messages/compose_message');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                require_once(APPPATH . 'libraries/aws/aws.php');
                $this->load->helper('email');
                $message_hf = message_header_footer($affiliate_sid);

                if ($formpost['send_type'] == 'to_admin') {

                    foreach ($formpost as $key => $value) {
                        if ($key != 'send_type' && $key != 'toemail') { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }

                            $message_data[$key] = $value;
                        }
                    }
                    
                    $message_data['to_id'] = 'administrator';
                    $to = $this->message_model->get_admin_email(1);
                    $name = 'Admin';
                    $message_data['from_id'] = $affiliate_sid;
                    $message_data['from_type'] = 'affiliate';
                    $message_data['to_type'] = 'admin';
                    $message_date = date('Y-m-d H:i:s');
                    $from = AFFILIATE_NOTIFICATION;
                    $message_data['identity_key'] = generateRandomString(48);
                    $secret_key = $message_data['identity_key'] . "__";
                    $affiliate_user_name = $affiliate_detail['username'];
                    $subject = 'Private Message Notification'; //send email
                    $body = $message_hf['header']
                        . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                        . '<br><br>'
                        . $affiliate_user_name . '</b> has sent you a private message.'
                        . '<br><br><b>'
                        . 'Date:</b> '
                        . $message_date
                        . '<br><br><b>'
                        . 'Subject:</b> '
                        . $formpost["subject"]
                        . '<br><hr>'
                        . $formpost["message"] . '<br><br>'
                        . $message_hf['footer']
                        . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                        . $secret_key . '</div>';

                    $attachments = array();
                    foreach($_FILES['message_attachment']['name'] as $key => $fileName){
                        if (empty($_FILES['message_attachment']['name'][$key]) || $_FILES['message_attachment']['size'][$key] == 0) {
                            continue;
                        }

                        $file = explode(".", $_FILES['message_attachment']['name'][$key]);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];


                        $aws = new AwsSdk();
                        $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'][$key], AWS_S3_BUCKET_NAME);
                        $attachments[] = $messageFile;
                    }

                    $insert_ids = $this->message_model->save_message($message_data);
                    if(sizeof($attachments) > 0){
                        sendMailWithAttachment($from, $to, $subject, $body, $message_hf['company_name'], $_FILES['message_attachment'], REPLY_TO, true);
                        $file_data = array();
                        foreach($attachments as $attachment){
                            $file_data['private_message_sid	'] = $insert_ids[0];
                            $file_data['attachment'] = $attachment;
                            $file_data['date'] = date('Y-m-d H:i:s');
                            $this->message_model->insert_private_messages_files($file_data);
                            $file_data['private_message_sid	'] = $insert_ids[1];
                            $file_data['attachment'] = $attachment;
                            $file_data['date'] = date('Y-m-d H:i:s');
                            $this->message_model->insert_private_messages_files($file_data);
                        }
                    }else{
                        sendMail($from, $to, $subject, $body, $message_hf['company_name'], REPLY_TO);
                    }

                    $this->session->set_flashdata('message', 'Success! Message sent successfully!');
                    redirect('outbox', 'refresh');
                } else if ($formpost['send_type'] == 'to_email') {
                    $affiliate_user_name = $affiliate_detail['username'];
                    $parent_sid = $affiliate_detail['parent_sid'];
                    $emails = explode(',', $formpost['toemail']);
                    $message_date = date('Y-m-d H:i:s');
                    
                    foreach ($emails as $email) {
                        if (valid_email(trim($email))) {
                            foreach ($formpost as $key => $value) {
                                if ($key != 'send_type' && $key != 'toemail') { // exclude these values from array
                                    if (is_array($value)) {
                                        $value = implode(',', $value);
                                    }
                                    $message_data[$key] = $value;
                                }
                            }

                            $found = 0;
                            $email_result = $this->message_model->get_email_for_record($email, $parent_sid, $affiliate_sid);
                             //check if the email belongs to any applicant or employee in the company.

                            if (!empty($email_result)) {
                                $found = 1;
                                $message_data['from_id'] = $affiliate_sid;
                                $message_data['from_type'] = 'affiliate';
                                $message_data['identity_key'] = generateRandomString(50);
                                $affiliate_user_data = $email_result;
                                $message_data['to_id'] = $affiliate_user_data['sid'];
                                $message_data['to_type'] = 'affiliate';
                                $name = $affiliate_user_data['full_name'];
                                $to_email = $email;
                                $subject = $formpost['subject'];
                                $from = REPLY_TO;
                                $secret_key = $message_data['identity_key'] . "__";
                                $body = $message_hf['header']
                                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                                    . '<br><br>'
                                    . $affiliate_user_name . '</b> has sent you a private message.'
                                    . '<br><br><b>'
                                    . 'Date:</b> '
                                    . $message_date
                                    . '<br><br><b>'
                                    . 'Subject:</b> '
                                    . $subject
                                    . '<br><hr>'
                                    . $formpost['message'] . '<br><br>'
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                $attachments = array();
                                foreach($_FILES['message_attachment']['name'] as $key => $fileName){
                                    if (empty($_FILES['message_attachment']['name'][$key]) || $_FILES['message_attachment']['size'][$key] == 0) {
                                        continue;
                                    }

                                    $file = explode(".", $_FILES['message_attachment']['name'][$key]);
                                    $file_name = str_replace(" ", "-", $file[0]);
                                    $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];


                                    $aws = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'][$key], AWS_S3_BUCKET_NAME);
                                    $attachments[] = $messageFile;
                                }

                                $insert_ids = $this->message_model->save_message($message_data);
                                if(sizeof($attachments) > 0){
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $message_hf['company_name'], $_FILES['message_attachment'], REPLY_TO, true);
                                    $file_data = array();
                                    foreach($attachments as $attachment){
                                        $file_data['private_message_sid	'] = $insert_ids[0];
                                        $file_data['attachment'] = $attachment;
                                        $file_data['date'] = date('Y-m-d H:i:s');
                                        $this->message_model->insert_private_messages_files($file_data);
                                        $file_data['private_message_sid	'] = $insert_ids[1];
                                        $file_data['attachment'] = $attachment;
                                        $file_data['date'] = date('Y-m-d H:i:s');
                                        $this->message_model->insert_private_messages_files($file_data);
                                    }
                                }else{
                                    sendMail($from, $to_email, $subject, $body, $message_hf['company_name'], REPLY_TO);
                                }

                            }

                            if ($found == 0) {
                                $message_data['from_id'] = $affiliate_sid;
                                $message_data['from_type'] = 'affiliate';
                                $message_data['identity_key'] = generateRandomString(50);
                                $message_data['to_id'] = trim($email);
                                $message_data['to_type'] = 'email';
                                $name = trim($email);
                                $to_email = $email;
                                $subject = $formpost['subject'];
                                $from = REPLY_TO;
                                $secret_key = $message_data['identity_key'] . "__";
                                $body = $message_hf['header']
                                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                                    . '<br><br>'
                                    . $affiliate_user_name . '</b> has sent you a private message.'
                                    . '<br><br><b>'
                                    . 'Date:</b> '
                                    . $message_date
                                    . '<br><br><b>'
                                    . 'Subject:</b> '
                                    . $subject
                                    . '<br><hr>'
                                    . $formpost['message'] . '<br><br>'
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                $attachments = array();
                                foreach($_FILES['message_attachment']['name'] as $key => $fileName){
                                    if (empty($_FILES['message_attachment']['name'][$key]) || $_FILES['message_attachment']['size'][$key] == 0) {
                                        continue;
                                    }

                                    $file = explode(".", $_FILES['message_attachment']['name'][$key]);
                                    $file_name = str_replace(" ", "-", $file[0]);
                                    $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];


                                    $aws = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'][$key], AWS_S3_BUCKET_NAME);
                                    $attachments[] = $messageFile;
                                }

                                $insert_ids = $this->message_model->save_message($message_data);
                                if(sizeof($attachments) > 0){
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $message_hf['company_name'], $_FILES['message_attachment'], REPLY_TO, true);
                                    $file_data = array();
                                    foreach($attachments as $attachment){
                                        $file_data['private_message_sid	'] = $insert_ids[0];
                                        $file_data['attachment'] = $attachment;
                                        $file_data['date'] = date('Y-m-d H:i:s');
                                        $this->message_model->insert_private_messages_files($file_data);
                                        $file_data['private_message_sid	'] = $insert_ids[1];
                                        $file_data['attachment'] = $attachment;
                                        $file_data['date'] = date('Y-m-d H:i:s');
                                        $this->message_model->insert_private_messages_files($file_data);
                                    }
                                }else{
                                    sendMail($from, $to_email, $subject, $body, $message_hf['company_name'], REPLY_TO);
                                }
                            }
                        }
                    }

                    $this->session->set_flashdata('message', 'Success! Message sent successfully!');
                    redirect('outbox', 'refresh');
                } else {
                    $users = $formpost['affiliates'];
                    $type = 'affiliate';
                    $message_date = date('Y-m-d H:i:s');
                    if (sizeof($users) > 0) {
                        foreach ($users as $user) {
                            foreach ($formpost as $key => $value) {
                                if ($key != 'send_type' && $key != 'toemail' && $key != 'affiliates') { // exclude these values from array
                                    if (is_array($value)) {
                                        $value = implode(',', $value);
                                    }

                                    $message_data[$key] = $value;
                                }
                            }

                            $message_data['from_id'] = $affiliate_detail['sid'];
                            $message_data['to_id'] = $user;
                            $message_data['from_type'] = 'affiliate';
                            $message_data['to_type'] = 'affiliate';
                            $message_data['identity_key'] = generateRandomString(49);
                            $to_email = '';
                            $contact_name = '';

                            foreach ($affiliates_list as $key => $affiliate_user) {
                                if ($user == $affiliate_user['sid']) {
                                    $to_email = $affiliate_user['email'];
                                    $contact_name = $affiliate_user['full_name'];
                                    break;
                                }
                            }

                            $from = REPLY_TO;
                            $secret_key = $message_data['identity_key'] . "__";
                            $affiliate_name = $affiliate_detail['full_name'];
                            $subject = $formpost['subject'];
                            $body = $message_hf['header']
                                . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $contact_name . ',</h2>'
                                . '<br><br>'
                                . $affiliate_name . '</b> has sent you a private message.'
                                . '<br><br><b>'
                                . 'Date:</b> '
                                . $message_date
                                . '<br><br><b>'
                                . 'Subject:</b> '
                                . $subject
                                . '<br><hr>'
                                . $formpost['message'] . '<br><br>'
                                . $message_hf['footer']
                                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                . $secret_key . '</div>';

                            $attachments = array();
                            foreach($_FILES['message_attachment']['name'] as $key => $fileName){
                                if (empty($_FILES['message_attachment']['name'][$key]) || $_FILES['message_attachment']['size'][$key] == 0) {
                                    continue;
                                }

                                $file = explode(".", $_FILES['message_attachment']['name'][$key]);
                                $file_name = str_replace(" ", "-", $file[0]);
                                $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];


                                $aws = new AwsSdk();
                                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'][$key], AWS_S3_BUCKET_NAME);
                                $attachments[] = $messageFile;
                            }

                            $insert_ids = $this->message_model->save_message($message_data);
                            if(sizeof($attachments) > 0){
                                sendMailWithAttachment($from, $to_email, $subject, $body, $message_hf['company_name'], $_FILES['message_attachment'], REPLY_TO, true);
                                $file_data = array();
                                foreach($attachments as $attachment){
                                    $file_data['private_message_sid	'] = $insert_ids[0];
                                    $file_data['attachment'] = $attachment;
                                    $file_data['date'] = date('Y-m-d H:i:s');
                                    $this->message_model->insert_private_messages_files($file_data);
                                    $file_data['private_message_sid	'] = $insert_ids[1];
                                    $file_data['attachment'] = $attachment;
                                    $file_data['date'] = date('Y-m-d H:i:s');
                                    $this->message_model->insert_private_messages_files($file_data);
                                }
                            }else{
                                sendMail($from, $to_email, $subject, $body, $message_hf['company_name'], REPLY_TO);
                            }

                        }

                        $this->session->set_flashdata('message', 'Success! Message(s) sent successfully!');
                        redirect('outbox', 'refresh');
                    }
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function get_url() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $message_sid = $this->input->post('message_sid');
            $url = get_print_document_url($message_sid);
            echo json_encode($url);
        }
    }

    public function download_attachment($document_path) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data                                                               = array();
            $affiliate_data                                                     = $this->session->userdata('affiliate_loggedin');
            $data['session']                                                    = $affiliate_data;
            $affiliate_detail                                                   = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);

            
            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
            $file_name = $document_path;
            $temp_file_path = $temp_path . $file_name;

            if (file_exists($temp_file_path)) {
                unlink($temp_file_path);
            }

            
            $this->load->library('aws_lib');
            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document_path, $temp_file_path);

            if (file_exists($temp_file_path)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($temp_file_path));
                $handle = fopen($temp_file_path, 'rb');
                $buffer = '';

                while (!feof($handle)) {
                    $buffer = fread($handle, 4096);
                    echo $buffer;
                    ob_flush();
                    flush();
                }
                
                fclose($handle);
                unlink($temp_file_path);
            }
                
        } else {
            redirect('login', 'refresh');
        }
    }
}
