<?php defined('BASEPATH') or exit('No direct script access allowed');

class Private_messages extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'private_messages');
            $data['title'] = 'Private Messages';
            $glyphicon_class = 'glyphicon-plus';
            $collapse_class = '';
            $name = '';
            $email = '';
            $subject = '';
            $start_date = '';
            $end_date = '';
            $search_activated = false;

            if (isset($_GET['filter'])) {
                $glyphicon_class = 'glyphicon-minus';
                $collapse_class = 'in';
                $name = trim($_GET['name']);
                $email = trim($_GET['email']);
                $subject = trim($_GET['subject']);
                $start_date = trim($_GET['start_date']);
                $end_date = trim($_GET['end_date']);
                $search_activated = true;
            }

            $data['glyphicon_class'] = $glyphicon_class;
            $data['collapse_class'] = $collapse_class;
            $data['name'] = $name;
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
                    $start_date = '1970-01-01 00:00:00';
                }

                if ($end_date != '') {
                    $end_date = explode('-', $end_date);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "date between '" . $start_date . "' and '" . $end_date . "'";
            }

            $employer_id = $employer_detail['sid'];
            $company_id = $company_detail['sid'];
            $employer_email = $employer_detail['email'];
            $access_level = $employer_detail['access_level'];
            $data['messages'] = $this->message_model->get_employer_messages($employer_id, $employer_email, $between, $company_id);
            //
            $data['employee'] = $employer_detail;

            $this->load->helper('email');
            // 
            foreach ($data['messages'] as $myKey => $message) {
                if (is_numeric($message['username'])) {
                    $result_data = $this->message_model->get_name_by_id($message['username'], $message['users_type']);
                    $data['messages'][$myKey]['username'] = $result_data['name'];
                    $data['messages'][$myKey]['email'] = $result_data['email'];
                } else {
                    if (valid_email(trim($message['username']))) {
                        //if(filter_var(trim($message['username']), FILTER_SANITIZE_EMAIL)) {
                        $data['messages'][$myKey]['email'] = $message['username'];
                        $data['messages'][$myKey]['username'] = $this->message_model->fetch_name($message['username'], $company_id);
                    }
                }
            }

            if ($search_activated) {
                $return_messages = array();
                $search_message = $data['messages'];

                foreach ($search_message as $key => $value) {
                    $db_name = $value['username'];
                    $db_email = $value['email'];
                    $db_subject = $value['subject'];

                    if ($name != '') { // strcasecmp
                        $name_array = explode(' ', $name);
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

                    if ($email != '') { // strcasecmp
                        if (strcasecmp($email, $db_email) == 0) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($subject != '') { // strcasecmp
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

                    if ($value['date'] >= $start_date && $value['date'] <= $end_date) {
                        $return_messages[] = $value;
                        continue;
                    }
                }

                $data['messages'] = $return_messages;
            }

            //
            $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, $employer_email, $between, $company_id);
            $data['total'] = $this->message_model->get_messages_total_inbox($employer_id, $employer_email, $between, NULL);
            $data['page'] = 'inbox';

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;
     
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/my_messages_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function outbox()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $data['title'] = 'Private Messages';
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'private_messages');

            $glyphicon_class = 'glyphicon-plus';
            $collapse_class = '';
            $name = '';
            $email = '';
            $subject = '';
            $start_date = '';
            $end_date = '';
            $search_activated = false;

            if (isset($_GET['filter'])) {
                $glyphicon_class = 'glyphicon-minus';
                $collapse_class = 'in';
                $name = trim($_GET['name']);
                $email = trim($_GET['email']);
                $subject = trim($_GET['subject']);
                $start_date = trim($_GET['start_date']);
                $end_date = trim($_GET['end_date']);
                $search_activated = true;
            }

            $data['glyphicon_class'] = $glyphicon_class;
            $data['collapse_class'] = $collapse_class;
            $data['name'] = $name;
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
                    $start_date = '1970-01-01 00:00:00';
                }

                if ($end_date != '') {
                    $end_date = explode('-', $end_date);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                } else {
                    $end_date = date('Y-m-d H:i:s');
                }

                $between = "date between '" . $start_date . "' and '" . $end_date . "'";
            }

            $employer_id = $employer_detail['sid'];
            $company_id = $company_detail['sid'];
            $data['messages'] = $this->message_model->get_employer_outbox_messages($employer_id, $between);
            //            $data['messages']                                                   = $db_data->result_array();
            $data['employee'] = $employer_detail;
            $this->load->helper('email');
            //            echo '<pre>'; print_r($data['messages']); echo '</pre>';
            foreach ($data['messages'] as $myKey => $message) {
                if (is_numeric($message['to_id'])) {
                    $result_data = $this->message_model->get_name_by_id($message['to_id'], $message['users_type']);
                    $data['messages'][$myKey]['to_id'] = $result_data['name'];
                    $data['messages'][$myKey]['email'] = $result_data['email'];
                } else {
                    if (valid_email(trim($message['to_id']))) {
                        $data['messages'][$myKey]['email'] = $message['to_id'];
                        $data['messages'][$myKey]['to_id'] = $this->message_model->fetch_name($message['to_id'], $company_id);
                    }
                }
            }
            if ($search_activated) {
                $return_messages = array();
                $search_message = $data['messages'];

                foreach ($search_message as $key => $value) {
                    $db_name = $value['username'];
                    $db_email = $value['email'];
                    $db_subject = $value['subject'];

                    if ($name != '') { // strcasecmp
                        $name_array = explode(' ', $name);
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

                    if ($email != '') { // strcasecmp
                        if (strcasecmp($email, $db_email) == 0) {
                            $return_messages[] = $value;
                            continue;
                        }
                    }

                    if ($subject != '') { // strcasecmp
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

                    if ($value['date'] >= $start_date && $value['date'] <= $end_date) {
                        $return_messages[] = $value;
                        continue;
                    }
                }

                $data['messages'] = $return_messages;
            }

            $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, null, $between, $company_id);
            $data['total'] = $this->message_model->get_messages_total_outbox($employer_id, $between);
            $data['page'] = 'outbox';
            $access_level = $employer_detail['access_level'];

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/my_messages_new');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function inbox_message_detail($edit_id = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $employer_id = $employer_detail['sid'];
            $security_sid = $employer_detail['sid'];
            $company_id = $company_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $access_level = $employer_detail['access_level'];
            $data['employee'] = $employer_detail;
            $data['backbtn'] = base_url('private_messages');
            check_access_permissions($security_details, 'dashboard', 'private_messages');

            if ($edit_id == NULL) { //If parameter not exist 
                redirect('private_messages', 'refresh');
            } else {
                $data['title'] = 'Message Detail';
                $data['page'] = 'Inbox';
                $message_data = $this->message_model->get_inbox_message_detail($edit_id);
                $to_id = $message_data[0]['to_id'];  // it can be numeric too or email
                $msg_id = $message_data[0]['msg_id'];
                $from_id = $message_data[0]['username'];
                $from_type = $message_data[0]['from_type'];
                $to_type = $message_data[0]['to_type'];
                $this->load->helper('email');
                $contact_details = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);
                //                echo '<pre>'; print_r($contact_details); echo '</pre>';
                if (valid_email(trim($from_id))) {
                    $name_only = $this->message_model->fetch_name($from_id, $company_id);
                    $contact_details['from_email'] = $from_id;
                    $contact_details['from_name'] = $name_only;
                }

                $data['contact_details'] = $contact_details;
                $data['message'] = $message_data[0];
                $this->message_model->mark_read($edit_id);
                $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);

                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/message_detail_new');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function outbox_message_detail($edit_id = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $employer_id = $employer_detail['sid'];
            $security_sid = $employer_detail['sid'];
            $company_id = $company_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $employer_detail;
            $data['backbtn'] = base_url('outbox');
            $access_level = $employer_detail['access_level'];
            check_access_permissions($security_details, 'dashboard', 'private_messages');

            if ($edit_id == NULL) { //If parameter not exist 
                redirect('private_messages', 'refresh');
            } else {
                $data['title'] = 'Message Detail';
                $data['page'] = 'outbox';
                $message_data = $this->message_model->get_outbox_message_detail($edit_id);
                $to_id = $message_data[0]['to_id'];  // it can be numeric too or email
                $msg_id = $message_data[0]['msg_id'];
                $from_id = $message_data[0]['username'];
                $from_type = $message_data[0]['from_type'];
                $to_type = $message_data[0]['to_type'];
                $this->load->helper('email');
                $contact_details = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);

                if (valid_email(trim($to_id))) {
                    $name_only = $this->message_model->fetch_name($from_id, $company_id);
                    $contact_details['from_email'] = $from_id;
                    $contact_details['from_name'] = $name_only;
                }

                //              echo $this->db->last_query().'<pre>'; print_r($contact_details); echo '</pre>';
                $data['contact_details'] = $contact_details;
                $data['message'] = $message_data[0];
                $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);

                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/message_detail_new');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function compose_message()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $employer_id = $employer_detail['sid'];
            $data['title'] = 'Compose Messages';
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $employer_detail;

            check_access_permissions($security_details, 'dashboard', 'private_messages');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            //$this->form_validation->set_rules('contact_name', 'Contact Name', 'trim');
            /** load applicants and employees * */
            $company_id = $company_detail['sid'];
            $company_name = $company_detail['CompanyName'];
            $data['applicants'] = get_users_list($company_id, 'applicant');
            $data['employees'] = get_users_list($company_id, 'employee');
            $data['employer_sid'] = $employer_id;

            if ($this->form_validation->run() === FALSE) {
                $data['page'] = 'compose';
                $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);
                $access_level = $employer_detail['access_level'];

                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['access_level'] = $access_level;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/compose_message_new');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $this->load->helper('email');

                if ($formpost['send_invoice'] == 'to_admin') {
                    foreach ($formpost as $key => $value) {
                        if ($key != 'send_invoice' && $key != 'toemail' && $key != 'employees' && $key != 'applicants') { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }

                            $message_data[$key] = $value;
                        }
                    }

                    $message_data['to_id'] = '1';
                    $to = $this->message_model->get_admin_email($message_data['to_id']);
                    $name = 'Admin';
                    $message_data['from_id'] = $employer_id;
                    $message_data['from_type'] = 'employer';
                    $message_data['to_type'] = 'admin';
                    $message_date = date('Y-m-d H:i:s');
                    $message_data['date'] = $message_date;
                    $message_data['company_sid'] = $company_id;
                    $from = REPLY_TO;
                    $message_data['identity_key'] = generateRandomString(48);
                    $secret_key = $message_data['identity_key'] . "__";
                    $employerData = $this->message_model->user_data_by_id($employer_id);
                    $employer_name = $employerData['username'];
                    $message_hf = (message_header_footer($data['session']['company_detail']['sid'], $data['session']['company_detail']['CompanyName']));
                    $subject = 'Private Message Notification'; //send email
                    $body = $message_hf['header']
                        . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                        . '<br><br>'
                        . $employer_name . '</b> has sent you a private message.'
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

                    if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                        $file = explode(".", $_FILES['message_attachment']['name']);
                        $file_name = str_replace(" ", "-", $file[0]);
                        $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                        if ($_FILES['message_attachment']['size'] == 0) {
                            $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                            redirect('private_messages', 'refresh');
                        }

                        $aws = new AwsSdk();
                        $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                        $message_data['attachment'] = $messageFile;
                        sendMailWithAttachment($from, $to, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                    } else {
                        sendMail($from, $to, $subject, $body, $company_name, REPLY_TO);
                    }

                    if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                        $this->send_email_notification($company_id, $company_name, $name, $employer_name, $to);
                    }

                    $this->message_model->save_message($message_data);
                    $this->session->set_flashdata('message', 'Success! Message sent successfully!');
                    redirect('private_messages', 'refresh');
                } else if ($formpost['send_invoice'] == 'to_email') {
                    $employerData = $this->message_model->user_data_by_id($employer_id);
                    $employer_name = $employerData['username'];
                    $message_hf = (message_header_footer($company_id, $company_name));
                    $emails = explode(',', $formpost['toemail']);
                    $message_date = date('Y-m-d H:i:s');

                    foreach ($emails as $email) {
                        if (valid_email(trim($email))) {
                            foreach ($formpost as $key => $value) {
                                if ($key != 'send_invoice' && $key != 'toemail' && $key != 'employees' && $key != 'applicants') { // exclude these values from array
                                    if (is_array($value)) {
                                        $value = implode(',', $value);
                                    }
                                    $message_data[$key] = $value;
                                }
                            }

                            $found = 0;
                            $email_result = $this->message_model->get_email_for_record($email, $company_id); //check if the email belongs to any applicant or employee in the company.

                            if (!empty($email_result['employee'])) {
                                $found = 1;
                                $message_data['from_id'] = $employer_id;
                                $message_data['from_type'] = 'employer';
                                $message_data['date'] = $message_date;
                                $message_data['identity_key'] = generateRandomString(50);
                                $message_data['company_sid'] = $company_id;
                                $employee_data = $email_result['employee'][0];
                                $message_data['to_id'] = $employee_data['sid'];
                                $message_data['to_type'] = 'employer';
                                $name = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                                $message_data['contact_name'] = $name;
                                $to_email = $email;
                                $subject = $formpost['subject'];
                                $from = REPLY_TO;
                                $secret_key = $message_data['identity_key'] . "__";
                                $body = $message_hf['header']
                                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                                    . '<br><br>'
                                    . $employer_name . '</b> has sent you a private message.'
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

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name = str_replace(" ", "-", $file[0]);
                                    $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment'] = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }

                                if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                                    $this->send_email_notification($company_id, $company_name, $name, $employer_name, $to_email);
                                }

                                $this->message_model->save_message($message_data);
                            }

                            if (!empty($email_result['applicant'])) {
                                $found = 1;
                                $message_data['from_id'] = $employer_id;
                                $message_data['from_type'] = 'employer';
                                $message_data['date'] = $message_date;
                                $message_data['identity_key'] = generateRandomString(50);
                                $message_data['company_sid'] = $company_id;
                                $applicant_data = $email_result['applicant'][0];
                                $message_data['to_id'] = $email;
                                $message_data['to_type'] = 'applicant';
                                $name = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                                $message_data['contact_name'] = $name;
                                $message_data['job_id'] = $applicant_data['sid'];
                                $to_email = $email;
                                $subject = $formpost['subject'];
                                $from = REPLY_TO;
                                $secret_key = $message_data['identity_key'] . "__";
                                $body = $message_hf['header']
                                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                                    . '<br><br>'
                                    . $employer_name . '</b> has sent you a private message.'
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

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name = str_replace(" ", "-", $file[0]);
                                    $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment'] = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }

                                if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                                    $this->send_email_notification($company_id, $company_name, $name, $employer_name, $to_email);
                                }

                                $this->message_model->save_message($message_data);
                            }

                            if ($found == 0) {
                                $message_data['from_id'] = $employer_id;
                                $message_data['from_type'] = 'employer';
                                $message_data['date'] = $message_date;
                                $message_data['identity_key'] = generateRandomString(50);
                                $message_data['to_id'] = trim($email);
                                $message_data['to_type'] = 'employer';
                                $message_data['company_sid'] = $company_id;
                                //
                                $name = trim($email);
                                $to_email = $email;
                                $subject = $formpost['subject'];
                                $from = REPLY_TO;
                                $secret_key = $message_data['identity_key'] . "__";
                                $body = $message_hf['header']
                                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                                    . '<br><br>'
                                    . $employer_name . '</b> has sent you a private message.'
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

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name = str_replace(" ", "-", $file[0]);
                                    $messageFile = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment'] = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }
                                //
                                if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                                    $this->send_email_notification($company_id, $company_name, $name, $employer_name, $to_email);
                                }
                                //
                                $this->message_model->save_message($message_data);
                            }
                        }
                    }

                    $this->session->set_flashdata('message', 'Success! Message sent successfully!');
                    redirect('private_messages', 'refresh');
                } else {
                    if ($formpost['send_invoice'] == 'to_employees') {
                        $users = $formpost['employees'];
                        $type = 'employee';
                    } else {
                        $users = $formpost['applicants'];
                        $type = 'applicant';
                    }

                    $message_date = date('Y-m-d H:i:s');

                    if (sizeof($users) > 0) {
                        foreach ($users as $user) {
                            foreach ($formpost as $key => $value) {
                                if ($key != 'send_invoice' && $key != 'toemail' && $key != 'employees' && $key != 'applicants' && $key != 'contact_name') { // exclude these values from array
                                    if (is_array($value)) {
                                        $value = implode(',', $value);
                                    }

                                    $message_data[$key] = $value;
                                }
                            }

                            $message_data['from_id'] = $employer_id;
                            $message_data['from_type'] = 'employer';
                            $message_data['date'] = $message_date;
                            $message_data['identity_key'] = generateRandomString(49);
                            $message_data['company_sid'] = $company_id;

                            if ($type == 'employee') {
                                foreach ($data['employees'] as $employee) {
                                    if ($user == $employee['sid']) {
                                        $message_data['to_id'] = $employee['sid'];
                                        $message_data['to_type'] = 'employer';
                                        $message_data['contact_name'] = $employee['first_name'] . ' ' . $employee['last_name'];
                                        $to_email = $employee['email'];
                                    }
                                }
                            } else {
                                foreach ($data['applicants'] as $applicant) {
                                    if ($user == $applicant['sid']) {
                                        $message_data['to_id'] = $applicant['email'];
                                        $message_data['to_type'] = 'applicant';
                                        $message_data['contact_name'] = $applicant['first_name'] . ' ' . $applicant['last_name'];
                                        $to_email = $applicant['email'];
                                        $message_data['job_id'] = $applicant['sid'];
                                    }
                                }
                            }

                            $from = REPLY_TO;
                            $secret_key = $message_data['identity_key'] . "__";
                            $employerData = $this->message_model->user_data_by_id($employer_id);
                            $employer_name = $employerData['username'];
                            $message_hf = (message_header_footer($company_id, $data['session']['company_detail']['CompanyName']));
                            $subject = $formpost['subject'];
                            $body = $message_hf['header']
                                . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $message_data['contact_name'] . ',</h2>'
                                . '<br><br>'
                                . $employer_name . '</b> has sent you a private message.'
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

                            if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                $file = explode(".", $_FILES['message_attachment']['name']);
                                $file_name = str_replace(" ", "-", $file[0]);
                                $messageFile = $file_name . '-' . generateRandomString(6) . '.' . $file[1];

                                if ($_FILES['message_attachment']['size'] == 0) {
                                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                    redirect('private_messages', 'refresh');
                                }

                                $aws = new AwsSdk();
                                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                $message_data['attachment'] = $messageFile;
                                sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                            } else {
                                sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                            }

                            if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                                $this->send_email_notification($company_id, $company_name, $message_data['contact_name'], $employer_name, $to_email);
                            }

                            $this->message_model->save_message($message_data);
                        }

                        $this->session->set_flashdata('message', 'Success! Message(s) sent successfully!');
                        redirect('private_messages', 'refresh');
                    }
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function reply_message($edit_id = NULL)
    {
        if ($edit_id == NULL) { //If parameter not exist 
            redirect('manage_admin/products', 'refresh');
        } else {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $company_id = $company_detail['sid'];
            $employer_id = $employer_detail['sid'];
            $security_sid = $employer_detail['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $employer_detail;
            check_access_permissions($security_details, 'dashboard', 'private_messages');
            $data['title'] = 'Reply to Messages';
            $data['message_type'] = $user_id = $this->message_model->get_message_type($edit_id);
            //            echo $this->db->last_query().'<br>'.$data['message_type']; 
            $data['backbtn'] = base_url('private_messages');

            if ($data['message_type'] != 1) {
                if (is_numeric($data['message_type'])) {
                    $this->load->model('dashboard_model');
                    $employerData = $this->dashboard_model->getEmployerDetail($data['message_type']);
                    $data['message_type'] = $employerData['email'];
                    //                    echo '<br>'.$data['message_type']; exit;
                    $data['messgae_type_flag'] = 'employer';
                } else {
                    $data['messgae_type_flag'] = 'email';
                }
            } else {
                $data['messgae_type_flag'] = 'admin';
            }

            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                $data['page'] = 'reply';
                $data['total_messages'] = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);
                $access_level = $employer_detail['access_level'];

                $load_view = check_blue_panel_status(false, 'self');
                $data['load_view'] = $load_view;
                $data['access_level'] = $access_level;

                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/compose_message_new');
                $this->load->view('main/footer');
            } else {
                $message = $this->message_model->get_message($edit_id);

                if (isset($message[0])) {
                    $user_type = $message[0]['users_type'];

                    if ($user_type == 'employee') { // employee
                        $from_id = $message[0]['from_id'];
                        $user = $this->message_model->get_user($from_id);
                        $first_name = $user[0]['first_name'];
                        $last_name = $user[0]['last_name'];
                    } else {  // applicant
                        $job_id = $message[0]['job_id'];
                        $applicant = $this->message_model->get_applicant($job_id);
                        $first_name = $applicant[0]['first_name'];
                        $last_name = $applicant[0]['last_name'];
                    }
                } else {
                    $first_name = '';
                    $last_name = '';
                }

                $formpost = $this->input->post(NULL, TRUE);

                foreach ($formpost as $key => $value) {
                    if ($key != 'send_invoice' && $key != 'to-email') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $message_data[$key] = $value;
                    }
                }

                $message_data['contact_name'] = $first_name . ' ' . $last_name;
                $message_data['from_id'] = $employer_id;
                $message_data['from_type'] = 'employer';
                $message_data['to_type'] = 'admin';
                $message_data['date'] = date('Y-m-d H:i:s');
                $message_data['company_sid'] = $company_id;

                if ($formpost['send_invoice'] == 'to_admin') {
                    $message_data['to_id'] = '1';
                    $to = $this->message_model->get_admin_email($message_data['to_id']);
                    $name = 'Admin';
                } elseif ($formpost['send_invoice'] == 'to_email') {
                    $message_data['to_id'] = $formpost['toemail'];
                    $to = $message_data['to_id'];
                    $name = $formpost['toemail'];
                    $message_data['to_type'] = 'employer';
                } elseif ($formpost['send_invoice'] == 'to_employer') {
                    $message_data['to_id'] = $user_id;

                    if (isset($formpost['toemail'])) {
                        $to = $formpost['toemail'];
                        $name = $formpost['toemail'];
                    }

                    $message_data['to_type'] = 'employer';
                }

                $from = REPLY_TO;
                $message_data['identity_key'] = generateRandomString(48);
                $secret_key = $message_data['identity_key'] . "__";
                //                $message_data['to_id']                                        = $message_data['toemail'];
                unset($message_data['toemail']);
                $this->message_model->save_message($message_data);
                $employerData = $this->message_model->user_data_by_id($employer_id);
                $employer_name = $employerData['username'];
                // $from = $employerData['email'];
                //getting email header and footer
                $message_hf = (message_header_footer($company_detail['sid'], $company_detail['CompanyName']));
                //send email
                $subject = 'Private Message Notification';
                $body = $message_hf['header']
                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                    . '<br><br>'
                    . $employer_name . '</b> has sent you a private message.'
                    . '<br><br><b>'
                    . 'Date:</b> '
                    . date('m-d-Y H:i:s')
                    . '<br><br><b>'
                    . 'Subject:</b> '
                    . $formpost["subject"]
                    . '<br><hr>'
                    . $formpost["message"] . '<br><br>'
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';
                sendMail($from, $to, $subject, $body, $company_detail['CompanyName'], REPLY_TO);
                //
                if (getnotifications_emails_configuration($company_id, 'private_message') > 0) {
                    if (!empty($message_data['contact_name'])) {
                        $name = $message_data['contact_name'];
                    }
                    //
                    $this->send_email_notification($company_id, $company_detail['CompanyName'], $name, $employer_name, $to);
                }   

                $this->session->set_flashdata('message', 'Success! Messsage sent successfully!');
                redirect('private_messages', 'refresh');
            }
        }
    }

    function message_task()
    {
        $action = $this->input->post('action');
        $message_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->message_model->delete_message($message_id);
        }
    }

    function trouble_shoot()
    {
        $message_data = $this->message_model->get_outbox_message_detail(193397);
        $to_id = $message_data[0]['to_id'];  // it can be numeric too or email
        $msg_id = $message_data[0]['msg_id'];
        $from_id = $message_data[0]['username'];
        $from_type = $message_data[0]['from_type'];
        $to_type = $message_data[0]['to_type'];
        $this->load->helper('email');
        echo '<br>TO: ' . $to_id;
        echo '<br>From: ' . $from_id;
        if (valid_email(trim($to_id))) {
            echo '<hr> IF';
            $contact_details = $this->message_model->get_name_from_email($to_id, $from_id, 2112);
        } else {
            echo '<hr> Else';
            $contact_details = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);
        }

        echo $this->db->last_query() . '<pre>';
        print_r($contact_details);
        echo '</pre>';
    }

    function send_email_notification ($company_sid, $company_name, $to_name, $from_name, $to_email) {
        $message_hf = message_header_footer($company_sid, $company_name);
        //
        $notification_email = $message_hf['header']
            . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $to_name . ',</h2>'
            . '</br> You have a message in your AutomotoHR inbox from <strong>'.$from_name .'</strong>'
            . $message_hf['footer'];
        //
        log_and_sendEmail(
            FROM_EMAIL_NOTIFICATIONS,
            $to_email,
            'Private Message Notification',
            $notification_email,
            $company_name
        );
        //    
        // $emailData = array(
        //     'date' => date('Y-m-d H:i:s'),
        //     'subject' => 'Private Message Notification',
        //     'email' => $to_email,
        //     'message' => $notification_email,
        //     'username' => $from_name,
        //     'temp_id' => 'nil'
        // );
        // //
        // save_email_log_common($emailData);

    }
}
