<?php defined('BASEPATH') or exit('No direct script access allowed');

class Private_messages extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('message_model');
        $this->load->model('Users_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($company_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $executive_user_companies                                           = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], null);
            $deleted_companies                                                  = array();
            $company_sid_array                                                  = array();
            $company_name                                                       = '';

            if (!empty($executive_user_companies)) { // check for deleted companies
                foreach ($executive_user_companies as $key => $value) {
                    $company_details                                            = $this->Users_model->get_company_exists($value['company_sid']);

                    if (empty($company_details)) {
                        $deleted_companies[]                                     = $key;
                    } else {
                        $company_sid_array[]                                    = $value['company_sid'];

                        if ($company_id == $value['company_sid']) {
                            $company_name                                       = $value['company_name'];
                        }
                    }
                }
            }

            if (!empty($deleted_companies)) {
                foreach ($deleted_companies as $deleted_company) {
                    unset($executive_user_companies[$deleted_company]);
                }

                $executive_user_companies                                       = array_values($executive_user_companies);
            }

            $data['executive_user_companies']                                   = $executive_user_companies;
            $data['company_name']                                               = $company_name;

            if ($company_id > 0 && in_array($company_id, $company_sid_array)) {
                $data['title']                                                  = 'Private Messages';
                $data['company_id']                                             = $company_id;
                $username                                                       = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
                $employer_email                                                 = $data['executive_user']['email'];
                $glyphicon_class                                                = 'glyphicon-plus';
                $collapse_class                                                 = '';
                $name                                                           = '';
                $email                                                          = '';
                $subject                                                        = '';
                $start_date                                                     = '';
                $end_date                                                       = '';
                $search_activated                                               = false;

                if (isset($_GET['filter'])) {
                    $glyphicon_class                                            = 'glyphicon-minus';
                    $collapse_class                                             = 'in';
                    $name                                                       = trim($_GET['name']);
                    $email                                                      = trim($_GET['email']);
                    $subject                                                    = trim($_GET['subject']);
                    $start_date                                                 = trim($_GET['start_date']);
                    $end_date                                                   = trim($_GET['end_date']);
                    $search_activated                                           = true;
                }

                $data['glyphicon_class']                                        = $glyphicon_class;
                $data['collapse_class']                                         = $collapse_class;
                $data['name']                                                   = $name;
                $data['email']                                                  = $email;
                $data['subject']                                                = $subject;
                $data['start_date']                                             = $start_date;
                $data['end_date']                                               = $end_date;
                $between                                                        = '';

                if ($start_date != '' || $end_date != '') {
                    if ($start_date != '') {
                        $start_date                                             = explode('-', $start_date);
                        $start_date                                             = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                    } else {
                        $start_date                                             = '01-01-1970 00:00:00';
                    }

                    if ($end_date != '') {
                        $end_date                                               = explode('-', $end_date);
                        $end_date                                               = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                    } else {
                        $end_date                                               = date('Y-m-d H:i:s');
                    }

                    $between                                                    = "date between '" . $start_date . "' and '" . $end_date . "'";
                }

                $employer_id                                                    = $this->message_model->get_employer_id($username, $employer_email, $company_id);
                $data['messages']                                               = $this->message_model->get_employer_messages($employer_id, $employer_email, $between, $company_id);
                $this->load->helper('email');

                foreach ($data['messages'] as $myKey => $message) {
                    if (is_numeric($message['username'])) {
                        $result_data                                            = $this->message_model->get_name_by_id($message['username'], $message['users_type']);
                        $data['messages'][$myKey]['username']                   = $result_data['name'];
                        $data['messages'][$myKey]['email']                      = $result_data['email'];
                    } else {
                        if (valid_email(trim($message['username']))) {
                            $data['messages'][$myKey]['email']                  = $message['username'];
                            $data['messages'][$myKey]['username']               = $this->message_model->fetch_name($message['username'], $company_id);
                        }
                    }
                }

                if ($search_activated) {
                    $return_messages                                            = array();
                    $search_message                                             = $data['messages'];

                    foreach ($search_message as $key => $value) {
                        $db_name                                                = $value['username'];
                        $db_email                                               = $value['email'];
                        $db_subject                                             = $value['subject'];

                        if ($name != '') { // strcasecmp
                            $name_array                                         = explode(' ', $name);
                            $dbname_array                                       = explode(' ', $db_name);
                            $found                                              = false;

                            foreach ($name_array as $names) {
                                if (in_array($names, $dbname_array)) {
                                    $found                                      = true;
                                    continue;
                                }
                            }

                            if ($found) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }

                        if ($email != '') { // strcasecmp
                            if (strcasecmp($email, $db_email) == 0) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }

                        if ($subject != '') { // strcasecmp
                            $subject_array                                      = explode(' ', $subject);
                            $db_subject_array                                   = explode(' ', $db_subject);
                            $found                                              = false;

                            foreach ($subject_array as $subjects) {
                                if (in_array($subjects, $db_subject_array)) {
                                    $found                                      = true;
                                    continue;
                                }
                            }

                            if ($found) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }
                    }

                    $data['messages']                                           = $return_messages;
                }
                //                echo '<pre>'; print_r($return_messages); echo '</pre>';
                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, $employer_email, $between, $company_id);
                //                echo $this->db->last_query();
                //                $data['total']                                                  = $this->message_model->get_messages_total_inbox($employer_id, $employer_email, $between);
                $data['page']                                                   = 'inbox';

                $this->load->view('main/header', $data);
                $this->load->view('private_messages/my_messages_new');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Company Messages Not Found!');
                redirect(base_url(), 'refresh');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function outbox($company_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $executive_user_companies                                           = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], null);
            $deleted_companies                                                  = array();
            $company_sid_array                                                  = array();
            $company_name                                                       = '';

            if (!empty($executive_user_companies)) { // check for deleted companies
                foreach ($executive_user_companies as $key => $value) {
                    $company_details                                            = $this->Users_model->get_company_exists($value['company_sid']);

                    if (empty($company_details)) {
                        $deleted_companies[]                                     = $key;
                    } else {
                        $company_sid_array[]                                    = $value['company_sid'];

                        if ($company_id == $value['company_sid']) {
                            $company_name                                       = $value['company_name'];
                        }
                    }
                }
            }

            if (!empty($deleted_companies)) {
                foreach ($deleted_companies as $deleted_company) {
                    unset($executive_user_companies[$deleted_company]);
                }

                $executive_user_companies                                       = array_values($executive_user_companies);
            }

            $data['executive_user_companies']                                   = $executive_user_companies;
            $data['company_name']                                               = $company_name;

            if ($company_id > 0 && in_array($company_id, $company_sid_array)) {
                $data['title']                                                  = 'Private Messages';
                $data['company_id']                                             = $company_id;
                $username                                                       = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
                $employer_email                                                 = $data['executive_user']['email'];

                $glyphicon_class                                                = 'glyphicon-plus';
                $collapse_class                                                 = '';
                $name                                                           = '';
                $email                                                          = '';
                $subject                                                        = '';
                $start_date                                                     = '';
                $end_date                                                       = '';
                $search_activated                                               = false;

                if (isset($_GET['filter'])) {
                    $glyphicon_class                                            = 'glyphicon-minus';
                    $collapse_class                                             = 'in';
                    $name                                                       = trim($_GET['name']);
                    $email                                                      = trim($_GET['email']);
                    $subject                                                    = trim($_GET['subject']);
                    $start_date                                                 = trim($_GET['start_date']);
                    $end_date                                                   = trim($_GET['end_date']);
                    $search_activated                                           = true;
                }

                $data['glyphicon_class']                                        = $glyphicon_class;
                $data['collapse_class']                                         = $collapse_class;
                $data['name']                                                   = $name;
                $data['email']                                                  = $email;
                $data['subject']                                                = $subject;
                $data['start_date']                                             = $start_date;
                $data['end_date']                                               = $end_date;
                $between                                                        = '';

                if ($start_date != '' || $end_date != '') {
                    if ($start_date != '') {
                        $start_date                                             = explode('-', $start_date);
                        $start_date                                             = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                    } else {
                        $start_date                                             = '01-01-1970 00:00:00';
                    }

                    if ($end_date != '') {
                        $end_date                                               = explode('-', $end_date);
                        $end_date                                               = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                    } else {
                        $end_date                                               = date('Y-m-d H:i:s');
                    }

                    $between                                                    = "date between '" . $start_date . "' and '" . $end_date . "'";
                }

                $employer_email                                                 = $this->Users_model->get_employee_email($employer_id);
                $employer_id                                                    = $this->message_model->get_employer_id($username, $employer_email, $company_id);
                $data['messages']                                               = $this->message_model->get_employer_outbox_messages($employer_id, $between);
                //                $data['messages']                                               = $db_data->result_array();
                $this->load->helper('email');

                foreach ($data['messages'] as $myKey => $message) {
                    if (is_numeric($message['to_id'])) {
                        $result_data                                            = $this->message_model->get_name_by_id($message['to_id'], $message['users_type']);
                        $data['messages'][$myKey]['to_id']                      = $result_data['name'];
                        $data['messages'][$myKey]['email']                      = $result_data['email'];
                    } else {
                        if (valid_email(trim($message['to_id']))) {
                            $data['messages'][$myKey]['email']                  = $message['to_id'];
                            $data['messages'][$myKey]['to_id']                  = $this->message_model->fetch_name($message['to_id'], $company_id);
                        }
                    }
                }

                if ($search_activated) {
                    $return_messages                                            = array();
                    $search_message                                             = $data['messages'];

                    foreach ($search_message as $key => $value) {
                        $db_name                                                = $value['username'];
                        $db_email                                               = $value['email'];
                        $db_subject                                             = $value['subject'];

                        if ($name != '') { // strcasecmp
                            $name_array                                         = explode(' ', $name);
                            $dbname_array                                       = explode(' ', $db_name);
                            $found                                              = false;

                            foreach ($name_array as $names) {
                                if (in_array($names, $dbname_array)) {
                                    $found                                      = true;
                                    continue;
                                }
                            }

                            if ($found) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }

                        if ($email != '') { // strcasecmp
                            if (strcasecmp($email, $db_email) == 0) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }

                        if ($subject != '') { // strcasecmp
                            $subject_array                                      = explode(' ', $subject);
                            $db_subject_array                                   = explode(' ', $db_subject);
                            $found                                              = false;

                            foreach ($subject_array as $subjects) {
                                if (in_array($subjects, $db_subject_array)) {
                                    $found                                      = true;
                                    continue;
                                }
                            }

                            if ($found) {
                                $return_messages[]                              = $value;
                                continue;
                            }
                        }
                    }

                    $data['messages']                                           = $return_messages;
                }

                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, null, $between, $company_id);
                //                $data['total']                                                  = $this->message_model->get_messages_total_outbox($employer_id, $between);
                $data['page']                                                   = 'outbox';

                $this->load->view('main/header', $data);
                $this->load->view('private_messages/my_messages_new');
                $this->load->view('main/footer');
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> Company Messages Not Found!');
                redirect(base_url(), 'refresh');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function inbox_message_detail($company_id = NULL, $edit_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['company_id']                                                 = $company_id;
            $username                                                           = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
            $employer_email                                                     = $data['executive_user']['email'];
            $employer_id                                                        = $this->message_model->get_employer_id($username, $employer_email, $company_id);
            $company_name                                                       = '';

            if ($company_id = NULL || $edit_id == NULL) { //If parameter not exist
                redirect(base_url('private_messages'), 'refresh');
            } else {
                $data['title']                                                  = 'Message Detail';
                $data['page']                                                   = 'Inbox';
                $message_data                                                   = $this->message_model->get_inbox_message_detail($edit_id);
                $company_datails                                                = $this->message_model->get_user($data['company_id']);

                if (!empty($company_datails)) {
                    $company_name                                               = $company_datails[0]['CompanyName'];
                }

                $to_id                                                          = $message_data[0]['to_id'];  // it can be numeric too or email
                $msg_id                                                         = $message_data[0]['msg_id'];
                $from_id                                                        = $message_data[0]['username'];
                $from_type                                                      = $message_data[0]['from_type'];
                $to_type                                                        = $message_data[0]['to_type'];
                $this->load->helper('email');
                $contact_details                                                = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);
                //                echo '<pre>'; print_r($contact_details); echo '</pre>';
                if (valid_email(trim($from_id))) {
                    $name_only                                                  = $this->message_model->fetch_name($from_id, $company_id);
                    $contact_details['from_email']                              = $from_id;
                    $contact_details['from_name']                               = $name_only;
                }

                $data['contact_details']                                        = $contact_details;
                $data['message']                                                = $message_data[0];
                $this->message_model->mark_read($edit_id);
                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);
                $data['company_name']                                           = $company_name;

                $data['employer_id'] = $employer_id;

                $this->load->view('main/header', $data);
                $this->load->view('private_messages/message_detail_new');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function outbox_message_detail($company_id = NULL, $edit_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['company_id']                                                 = $company_id;
            $username                                                           = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
            $employer_email                                                     = $data['executive_user']['email'];
            $employer_id                                                        = $this->message_model->get_employer_id($username, $employer_email, $company_id);
            $company_name                                                       = '';

            if ($company_id = NULL || $edit_id == NULL) { //If parameter not exist 
                redirect(base_url('private_messages'), 'refresh');
            } else {
                $data['title']                                                  = 'Message Detail';
                $data['page']                                                   = 'outbox';
                $message_data                                                   = $this->message_model->get_outbox_message_detail($edit_id);
                $company_datails                                                = $this->message_model->get_user($data['company_id']);

                if (!empty($company_datails)) {
                    $company_name                                               = $company_datails[0]['CompanyName'];
                }

                $to_id                                                          = $message_data[0]['to_id'];  // it can be numeric too or email
                $msg_id                                                         = $message_data[0]['msg_id'];
                $from_id                                                        = $message_data[0]['username'];
                $from_type                                                      = $message_data[0]['from_type'];
                $to_type                                                        = $message_data[0]['to_type'];
                $this->load->helper('email');

                if (valid_email(trim($to_id))) {
                    $contact_details                                            = $this->message_model->get_name_from_email($to_id, $from_id, $company_id);
                } else {
                    $contact_details                                            = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);
                }

                $data['contact_details']                                        = $contact_details;
                $data['message']                                                = $message_data[0];
                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);
                $data['company_name']                                           = $company_name;
                $this->load->view('main/header', $data);
                $this->load->view('private_messages/message_detail_new');
                $this->load->view('main/footer');
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function compose_message($company_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['company_id']                                                 = $company_id;
            $data['title']                                                      = 'Private Messages';
            $username                                                           = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
            $employer_email                                                     = $data['executive_user']['email'];
            $employer_id                                                        = $this->message_model->get_employer_id($username, $employer_email, $company_id);
            $executive_user_companies                                           = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], null);
            $deleted_companies                                                  = array();
            $company_sid_array                                                  = array();
            $company_name                                                       = '';

            //
            $company_Location_Address = '';
            $company_PhoneNumber = '';
            $company_WebSite = '';

            if (!empty($executive_user_companies)) { // check for deleted companies
                foreach ($executive_user_companies as $key => $value) {
                    $company_details                                            = $this->Users_model->get_company_exists($value['company_sid']);

                    if (empty($company_details)) {
                        $deleted_companies[]                                     = $key;
                    } else {
                        $company_sid_array[]                                    = $value['company_sid'];

                        if ($company_id == $value['company_sid']) {
                            $company_name                                       = $value['company_name'];
                            $company_Location_Address = $value['Location_Address'];
                            $company_PhoneNumber = $value['PhoneNumber'];
                            $company_WebSite = $value['company_website'];
                        }
                    }
                }
            }

            if ($company_id == NULL || !in_array($company_id, $company_sid_array)) {
                $this->session->set_flashdata('message', 'Error: Company not found!');
                redirect(base_url('private_messages'), 'refresh');
            }



            //
            $portal_email_templates   = $this->message_model->get_portal_email_templates($company_id);

            foreach ($portal_email_templates as $key => $template) {
                $portal_email_templates[$key]['attachments']  = $this->message_model->get_all_email_template_attachments($template['sid']);
            }

            $data['portal_email_templates'] = $portal_email_templates;


            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim|required');
            //$this->form_validation->set_rules('contact_name', 'Contact Name', 'trim');
            /** load applicants and employees * */
            $data['applicants']                                                 = $this->message_model->get_users_list($company_id, 'applicant');
            $data['employees']                                                  = $this->message_model->get_users_list($company_id, 'employee');
            $data['employer_sid']                                               = $employer_id;
            $data['company_name']                                               = $company_name;

            if ($this->form_validation->run() === FALSE) {
                $data['page']                                                   = 'compose';
                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);
                $this->load->view('main/header', $data);
                $this->load->view('private_messages/compose_message_new');
                $this->load->view('main/footer');
            } else {
                $formpost                                                       = $this->input->post(NULL, TRUE);
                //                echo '<pre>'; print_r($formpost); exit;
                $this->load->helper('email');
                //
                $attach_body       = '';
                if (!empty($formpost['template'])) {
                    $attachments       = $this->message_model->get_all_email_template_attachments($formpost['template']);

                    if (sizeof($attachments) > 0) {
                        $attach_body .= '<br> Please Review The Following Attachments: <br>';

                        foreach ($attachments as $attachment) {
                            $attach_body .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a> <br>';
                        }
                    }
                }

                $fromArray = [
                    '{{company_name}}',
                    '{{date}}',
                    '{{first_name}}',
                    '{{last_name}}',
                    '{{job_title}}',
                    '{{applicant_name}}',
                    '{{email}}',
                    '{{company_address}}',
                    '{{company_phone}}',
                    '{{career_site_url}}'
                ];
                //
                $today           = new DateTime();
                $today           = reset_datetime(array('datetime' => $today->format('Y-m-d'), '_this' => $this, 'type' => 'company', 'with_timezone' => true));



                if ($formpost['send_invoice'] == 'to_admin') {
                    foreach ($formpost as $key => $value) {
                        if ($key != 'send_invoice' && $key != 'toemail' && $key != 'employees' && $key != 'applicants') { // exclude these values from array
                            if (is_array($value)) {
                                $value = implode(',', $value);
                            }
                            $message_data[$key]                                 = $value;
                        }
                    }

                    $message_data['to_id']                                      = '1';

                    //
                    unset($formpost['template']);
                    $message_data['to_id'] = '1';
                    $adminDetails = $this->message_model->get_admin_details($message_data['to_id']);
                    $toArray = array($company_name, $today, $adminDetails['first_name'], $adminDetails['last_name'], '',  ' ', $adminDetails['email'], $company_Location_Address, $company_PhoneNumber, $company_WebSite);

                    $to                                                         = $this->message_model->get_admin_email($message_data['to_id']);
                    //                    echo '<pre>'; print_r($to); exit;
                    $name                                                       = 'Admin';
                    $message_data['from_id']                                    = $employer_id;
                    //$message_data['contact_name'] = $formpost['contact_name'];
                    $message_data['from_type']                                  = 'employer';
                    $message_data['to_type']                                    = 'admin';
                    $message_date                                               = date('Y-m-d H:i:s');
                    $message_data['date']                                       = $message_date;
                    $from                                                       = REPLY_TO;
                    $message_data['identity_key']                               = generateRandomString(48);
                    $secret_key                                                 = $message_data['identity_key'] . "__";
                    $employerData                                               = $this->message_model->user_data_by_id($employer_id);
                    $employer_name                                              = $employerData['username'];
                    $message_hf                                                 = (message_header_footer($company_id, $company_name));
                    $subject                                                    = 'Private Message Notification'; //send email


                    $messageBody = $formpost['message'];
                    $messagesubject = $formpost["subject"];
                    replace_magic_quotes($messagesubject, $fromArray, $toArray);
                    replace_magic_quotes($messageBody, $fromArray, $toArray);
                    $messageBody .= $attach_body;


                    $body                                                       = $message_hf['header']
                        . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $name . ',</h2>'
                        . '<br><br>'
                        . $employer_name . '</b> has sent you a private message.'
                        . '<br><br><b>'
                        . 'Date:</b> '
                        . $message_date
                        . '<br><br><b>'
                        . 'Subject:</b> '
                        . $messagesubject
                        . '<br><hr>'
                        . $messageBody . '<br><br>'
                        . $message_hf['footer']
                        . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                        . $secret_key . '</div>';

                    if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                        $file                                                   = explode(".", $_FILES['message_attachment']['name']);
                        $file_name                                              = str_replace(" ", "-", $file[0]);
                        $messageFile                                            = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                        if ($_FILES['message_attachment']['size'] == 0) {
                            $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                            redirect('private_messages', 'refresh');
                        }

                        $aws                                                    = new AwsSdk();
                        $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                        $message_data['attachment']                             = $messageFile;
                        sendMailWithAttachment($from, $to, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                    } else {
                        sendMail($from, $to, $subject, $body, $company_name, REPLY_TO);
                    }

                    unset($message_data['template']);

                    $subject = $formpost["subject"];
                    $body    = $formpost["message"];

                    replace_magic_quotes($subject, $fromArray, $toArray);
                    replace_magic_quotes($body, $fromArray, $toArray);
                    $body .= $attach_body;

                    $message_data['message'] = $body;
                    $message_data['subject'] = $subject;

                    $this->message_model->save_message($message_data);
                    $this->session->set_flashdata('message', 'Success! Message(s) sent successfully!');
                    redirect(base_url('private_messages') . '/' . $company_id, 'refresh');
                } else if ($formpost['send_invoice'] == 'to_email') {
                    $employerData                                               = $this->message_model->user_data_by_id($employer_id);
                    $employer_name                                              = $employerData['username'];
                    $message_hf                                                 = (message_header_footer($company_id, $company_name));
                    $emails                                                     = explode(',', $formpost['toemail']);
                    $message_date                                               = date('Y-m-d H:i:s');
                    // echo '<pre>FORM POST'; print_r($formpost); echo '<hr>'; print_r($employerData);  
                    foreach ($emails as $email) {
                        if (valid_email(trim($email))) {
                            foreach ($formpost as $key => $value) {
                                if ($key != 'send_invoice' && $key != 'toemail' && $key != 'employees' && $key != 'applicants') { // exclude these values from array
                                    if (is_array($value)) {
                                        $value                                  = implode(',', $value);
                                    }
                                    $message_data[$key]                         = $value;
                                }
                            }
                            //
                            unset($formpost['template']);
                            $found                                              = 0;
                            $email_result                                       = $this->message_model->get_email_for_record($email, $company_id); //check if the email belongs to any applicant or employee in the company.

                            if (!empty($email_result['employee'])) {
                                $found                                          = 1;
                                $message_data['from_id']                        = $employer_id;
                                $message_data['from_type']                      = 'employer';
                                $message_data['date']                           = $message_date;
                                $message_data['identity_key']                   = generateRandomString(50);
                                $employee_data                                  = $email_result['employee'][0];
                                $message_data['to_id']                          = $employee_data['sid'];
                                $message_data['to_type']                        = 'employer';
                                $name                                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                                $message_data['contact_name']                   = $name;
                                $to_email                                       = $email;
                                $subject                                        = $formpost['subject'];
                                $from                                           = REPLY_TO;
                                $secret_key                                     = $message_data['identity_key'] . "__";

                                //
                                $toArray = array($company_name, $today, $employee_data['first_name'], $employee_data['last_name'], $employee_data['job_title'], '',  $employee_data["email"], $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                $messageBody = $formpost['message'];
                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($messageBody, $fromArray, $toArray);
                                $messageBody .= $attach_body;


                                $body                                           = $message_hf['header']
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
                                    . $messageBody . '<br><br>'
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file                                       = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name                                  = str_replace(" ", "-", $file[0]);
                                    $messageFile                                = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws                                        = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment']                 = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }


                                unset($message_data['template']);

                                $toArray = array($company_name, $today, $employee_data['first_name'], $employee_data['last_name'], $employee_data['job_title'], '',  $employee_data["email"], $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                //
                                $subject = $formpost["subject"];
                                $body    = $formpost["message"];

                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($body, $fromArray, $toArray);
                                $body .= $attach_body;

                                $message_data['message'] = $body;
                                $message_data['subject'] = $subject;

                                $this->message_model->save_message($message_data);
                            }

                            if (!empty($email_result['applicant'])) {
                                $found                                          = 1;
                                $message_data['from_id']                        = $employer_id;
                                $message_data['from_type']                      = 'employer';
                                $message_data['date']                           = $message_date;
                                $message_data['identity_key']                   = generateRandomString(50);
                                $applicant_data                                 = $email_result['applicant'][0];
                                $message_data['to_id']                          = $email;
                                $message_data['to_type']                        = 'applicant';
                                $name                                           = $applicant_data['first_name'] . ' ' . $applicant_data['last_name'];
                                $message_data['contact_name']                   = $name;
                                $message_data['job_id']                         = $applicant_data['sid'];
                                $to_email                                       = $email;
                                $subject                                        = $formpost['subject'];
                                $from                                           = REPLY_TO;
                                $secret_key                                     = $message_data['identity_key'] . "__";

                                $toArray = array($company_name, $today, $applicant_data['first_name'], $applicant_data['last_name'], $applicant_data['job_title'], '',  $applicant_data["email"], $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                $messageBody = $formpost['message'];
                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($messageBody, $fromArray, $toArray);
                                $messageBody .= $attach_body;

                                $body                                           = $message_hf['header']
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
                                    . $messageBody . '<br><br>'
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file                                       = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name                                  = str_replace(" ", "-", $file[0]);
                                    $messageFile                                = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws                                        = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment']                 = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }

                                unset($message_data['template']);

                                $toArray = array($company_name, $today, $applicant_data['first_name'], $applicant_data['last_name'], $applicant_data['job_title'], '', $applicant_data["email"], $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                //
                                $body    = $formpost["message"];

                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($body, $fromArray, $toArray);
                                $body .= $attach_body;

                                $message_data['message'] = $body;
                                $message_data['subject'] = $subject;

                                $this->message_model->save_message($message_data);
                            }

                            if ($found == 0) {
                                $message_data['from_id']                        = $employer_id;
                                $message_data['from_type']                      = 'employer';
                                $message_data['date']                           = $message_date;
                                $message_data['identity_key']                   = generateRandomString(50);
                                $message_data['to_id']                          = trim($email);
                                $message_data['to_type']                        = 'employer';
                                $name                                           = trim($email);
                                $to_email                                       = $email;
                                $subject                                        = $formpost['subject'];
                                $from                                           = REPLY_TO;
                                $secret_key                                     = $message_data['identity_key'] . "__";

                                $toArray = array($company_name, $today, $name, "", "", '',  $to_email, $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                $messageBody = $formpost['message'];
                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($messageBody, $fromArray, $toArray);
                                $messageBody .= $attach_body;

                                $body                                           = $message_hf['header']
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
                                    . $messageBody . '<br><br>'
                                    . $message_hf['footer']
                                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                    . $secret_key . '</div>';

                                if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                    $file                                       = explode(".", $_FILES['message_attachment']['name']);
                                    $file_name                                  = str_replace(" ", "-", $file[0]);
                                    $messageFile                                = $file_name . '-' . generateRandomString(5) . '.' . $file[1];

                                    if ($_FILES['message_attachment']['size'] == 0) {
                                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                        redirect('private_messages', 'refresh');
                                    }

                                    $aws                                        = new AwsSdk();
                                    $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                    $message_data['attachment']                 = $messageFile;
                                    sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                                } else {
                                    sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                                }

                                //
                                unset($message_data['template']);

                                $toArray = array($company_name, $today, $name, '', '',  ' ', '', $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                                //
                                $body    = $formpost["message"];

                                replace_magic_quotes($subject, $fromArray, $toArray);
                                replace_magic_quotes($body, $fromArray, $toArray);
                                $body .= $attach_body;

                                $message_data['message'] = $body;
                                $message_data['subject'] = $subject;

                                $this->message_model->save_message($message_data);
                            }
                        }
                    }

                    $this->session->set_flashdata('message', 'Success! Message(s) sent successfully!');
                    redirect(base_url('private_messages') . '/' . $company_id, 'refresh');
                } else {
                    if ($formpost['send_invoice'] == 'to_employees') {
                        $users                                                  = $formpost['employees'];
                        $type                                                   = 'employee';
                    } else {
                        $users                                                  = $formpost['applicants'];
                        $type                                                   = 'applicant';
                    }

                    $message_date                                               = date('Y-m-d H:i:s');

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

                            $message_data['from_id']                            = $employer_id;
                            $message_data['from_type']                          = 'employer';
                            $message_data['date']                               = $message_date;
                            $message_data['identity_key']                       = generateRandomString(49);

                            $first_name = '';
                            $last_name = '';

                            if ($type == 'employee') {
                                foreach ($data['employees'] as $employee) {
                                    if ($user == $employee['sid']) {
                                        $message_data['to_id']                  = $employee['sid'];
                                        $message_data['to_type']                = 'employer';
                                        $message_data['contact_name']           = $employee['first_name'] . ' ' . $employee['last_name'];
                                        $to_email                               = $employee['email'];
                                        $first_name = $employee['first_name'];
                                        $last_name = $employee['last_name'];
                                        $job_title = $employee['job_title'];
                                    }
                                }
                            } else {
                                foreach ($data['applicants'] as $applicant) {
                                    if ($user == $applicant['sid']) {
                                        $message_data['to_id']                  = $applicant['email'];
                                        $message_data['to_type']                = 'applicant';
                                        $message_data['contact_name']           = $applicant['first_name'] . ' ' . $applicant['last_name'];
                                        $to_email                               = $applicant['email'];
                                        $message_data['job_id']                 = $applicant['sid'];
                                        $first_name = $applicant['first_name'];
                                        $last_name = $applicant['last_name'];
                                        $job_title = $applicant['job_title'];
                                    }
                                }
                            }

                            $from                                               = REPLY_TO;
                            $secret_key                                         = $message_data['identity_key'] . "__";
                            $employerData                                       = $this->message_model->user_data_by_id($employer_id);
                            $employer_name                                      = $employerData['username'];
                            $message_hf                                         = (message_header_footer($company_id, $company_name));
                            $subject                                            = $formpost['subject'];
                            //
                            $toArray = array($company_name, $today, $first_name, $last_name, $job_title,  ' ', $to_email, $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                            $messageBody = $formpost['message'];
                            replace_magic_quotes($subject, $fromArray, $toArray);
                            replace_magic_quotes($messageBody, $fromArray, $toArray);
                            $messageBody .= $attach_body;

                            $body                                               = $message_hf['header']
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
                                . $messageBody . '<br><br>'
                                . $message_hf['footer']
                                . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                                . $secret_key . '</div>';

                            if (isset($_FILES['message_attachment']) && $_FILES['message_attachment']['name'] != '') {
                                $file                                       = explode(".", $_FILES['message_attachment']['name']);
                                $file_name                                  = str_replace(" ", "-", $file[0]);
                                $messageFile                                = $file_name . '-' . generateRandomString(6) . '.' . $file[1];

                                if ($_FILES['message_attachment']['size'] == 0) {
                                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                                    redirect('private_messages', 'refresh');
                                }

                                $aws = new AwsSdk();
                                $aws->putToBucket($messageFile, $_FILES['message_attachment']['tmp_name'], AWS_S3_BUCKET_NAME);
                                $message_data['attachment']                 = $messageFile;
                                sendMailWithAttachment($from, $to_email, $subject, $body, $company_name, $_FILES['message_attachment'], REPLY_TO);
                            } else {
                                sendMail($from, $to_email, $subject, $body, $company_name, REPLY_TO);
                            }


                            unset($message_data['template']);


                            $toArray = array($company_name, $today, $first_name, $last_name, $job_title,  ' ', $to_email, $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                            //
                            $body    = $formpost["message"];

                            replace_magic_quotes($subject, $fromArray, $toArray);
                            replace_magic_quotes($body, $fromArray, $toArray);
                            $body .= $attach_body;

                            $message_data['message'] = $body;
                            $message_data['subject'] = $subject;

                            $this->message_model->save_message($message_data);
                        }

                        $this->session->set_flashdata('message', 'Success! Message(s) sent successfully!');
                        redirect(base_url('private_messages') . '/' . $company_id, 'refresh');
                    }
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function reply_message($company_id = NULL, $edit_id = NULL)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['company_id']                                                 = $company_id;
            $username                                                           = $data['executive_user']['username'] . '_executive_admin_' . $company_id;
            $employer_email                                                     = $data['executive_user']['email'];
            $employer_id                                                        = $this->message_model->get_employer_id($username, $employer_email, $company_id);
            $executive_user_companies                                           = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], null);
            $deleted_companies                                                  = array();
            $company_sid_array                                                  = array();
            $company_name                                                       = '';

            if (!empty($executive_user_companies)) { // check for deleted companies
                foreach ($executive_user_companies as $key => $value) {
                    $company_details                                            = $this->Users_model->get_company_exists($value['company_sid']);

                    if (empty($company_details)) {
                        $deleted_companies[]                                     = $key;
                    } else {
                        $company_sid_array[]                                    = $value['company_sid'];

                        if ($company_id == $value['company_sid']) {
                            $company_name                                       = $value['company_name'];
                            $company_Location_Address = $value['Location_Address'];
                            $company_PhoneNumber = $value['PhoneNumber'];
                            $company_WebSite = $value['company_website'];
                        }
                    }
                }
            }

            if ($company_id == NULL || $edit_id == NULL || !in_array($company_id, $company_sid_array)) {
                $this->session->set_flashdata('message', 'Error: Message not found!');
                redirect(base_url('private_messages'), 'refresh');
            }

            $data['title']                                                      = 'Private Messages';
            $data['message_type']                                               = $user_id = $this->message_model->get_message_type($edit_id);
            $data['company_name']                                               = $company_name;
            //            echo $this->db->last_query().' - <br>'.$user_id.'<pre>'; print_r($data['message_type']); exit;
            if ($data['message_type'] != 1) {
                if (is_numeric($data['message_type'])) {
                    $employerData                                               = $this->message_model->user_data_by_id($data['message_type']);
                    $data['message_type']                                       = $employerData['email'];
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
                $data['page']                                                   = 'reply';
                $data['total_messages']                                         = $this->message_model->get_employer_messages_total($employer_id, null, null, $company_id);


                $portal_email_templates   = $this->message_model->get_portal_email_templates($company_id);

                foreach ($portal_email_templates as $key => $template) {
                    $portal_email_templates[$key]['attachments']  = $this->message_model->get_all_email_template_attachments($template['sid']);
                }

                $data['portal_email_templates'] = $portal_email_templates;


                //                echo '<pre>'; print_r($data); exit;
                $this->load->view('main/header', $data);
                $this->load->view('private_messages/compose_message_new');
                $this->load->view('main/footer');
            } else {
                $message                                                        = $this->message_model->get_message($edit_id);


                $formpost = $this->input->post(NULL, TRUE);

                $attach_body       = '';
                if (!empty($formpost['template'])) {
                    $attachments       = $this->message_model->get_all_email_template_attachments($formpost['template']);
                    if (sizeof($attachments) > 0) {
                        $attach_body .= '<br> Please Review The Following Attachments: <br>';

                        foreach ($attachments as $attachment) {
                            $attach_body .= '<a href="' . AWS_S3_BUCKET_URL . $attachment['attachment_aws_file'] . '">' . $attachment['original_file_name'] . '</a> <br>';
                        }
                    }
                }

                $fromArray = [
                    '{{company_name}}',
                    '{{date}}',
                    '{{first_name}}',
                    '{{last_name}}',
                    '{{job_title}}',
                    '{{applicant_name}}',
                    '{{email}}',
                    '{{company_address}}',
                    '{{company_phone}}',
                    '{{career_site_url}}'
                ];
                //
                $today   = new DateTime();
                $today   = reset_datetime(array('datetime' => $today->format('Y-m-d'), '_this' => $this, 'type' => 'company', 'with_timezone' => true));



                if (isset($message[0])) {
                    $user_type                                                  = $message[0]['users_type'];

                    if ($user_type == 'employee') { // employee
                        $from_id                                                = $message[0]['from_id'];
                        $user                                                   = $this->message_model->get_user($from_id);
                        $first_name                                             = $user[0]['first_name'];
                        $last_name                                              = $user[0]['last_name'];
                        $email = $user[0]['email'];
                        $job_title = $user[0]['job_title'];
                    } else {  // applicant
                        $job_id                                                 = $message[0]['job_id'];
                        $applicant                                              = $this->message_model->get_applicant($job_id);
                        $first_name                                             = $applicant[0]['first_name'];
                        $last_name                                              = $applicant[0]['last_name'];
                        $email = $applicant[0]['email'];
                        $job_title = $applicant[0]['desired_job_title'];
                    }
                } else {
                    $first_name                                                 = '';
                    $last_name                                                  = '';
                    $email = '';
                    $job_title = '';
                }

                $formpost                                                       = $this->input->post(NULL, TRUE);

                foreach ($formpost as $key => $value) {
                    if ($key != 'send_invoice' && $key != 'to-email') { // exclude these values from array
                        if (is_array($value)) {
                            $value                                              = implode(',', $value);
                        }

                        $message_data[$key]                                     = $value;
                    }
                }

                $message_data['contact_name']                                   = $first_name . ' ' . $last_name;
                $message_data['from_id']                                        = $employer_id;
                $message_data['from_type']                                      = 'employer';
                $message_data['to_type']                                        = 'admin';
                $message_data['date']                                           = date('Y-m-d H:i:s');

                if ($formpost['send_invoice'] == 'to_admin') {
                    $message_data['to_id']                                      = '1';
                    $to                                                         = $this->message_model->get_admin_email($message_data['to_id']);
                    $name                                                       = 'Admin';
                } elseif ($formpost['send_invoice'] == 'to_email') {
                    $message_data['to_id']                                      = $formpost['toemail'];
                    $to                                                         = $message_data['to_id'];
                    $name                                                       = $formpost['toemail'];
                    $message_data['to_type']                                    = 'employer';
                } elseif ($formpost['send_invoice'] == 'to_employer') {
                    $message_data['to_id']                                      = $user_id;

                    if (isset($formpost['toemail'])) {
                        $to                                                     = $formpost['toemail'];
                        $name                                                   = $formpost['toemail'];
                    }

                    $message_data['to_type']                                    = 'employer';
                }

                $from                                                           = REPLY_TO;
                $message_data['identity_key']                                   = generateRandomString(48);
                $secret_key                                                     = $message_data['identity_key'] . "__";
                //                $message_data['to_id']                                          = $message_data['toemail'];
                unset($message_data['toemail']);
             //   $this->message_model->save_message($message_data);
                $employerData                                                   = $this->message_model->user_data_by_id($employer_id);
                $employer_name                                                  = $employerData['username'];
                $from                                                           = $employerData['email'];
                //getting email header and footer
                $message_hf                                                     = (message_header_footer($company_id, $company_name));
                //send email
                $subject                                                        = 'Private Message Notification';

                unset($message_data['toemail']);

                $toArray = array($company_name, $today, $first_name, $last_name, $job_title,  ' ', $email, $company_Location_Address, $company_PhoneNumber, $company_WebSite);
                //

                $body    = $formpost["message"];
                $subject    = $formpost["subject"];

                replace_magic_quotes($subject, $fromArray, $toArray);
                replace_magic_quotes($body, $fromArray, $toArray);
                $body .= $attach_body;

                $message_data['subject'] = $subject;
                $message_data['message'] = $body;

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
                sendMail($from, $to, $subject, $body, $company_name, REPLY_TO);

                //
                unset($message_data['template']);
                $this->message_model->save_message($message_data);

                $this->session->set_flashdata('message', 'Success! Messsage sent successfully!');
                redirect(base_url('private_messages') . '/' . $company_id, 'refresh');
            }
        }
    }

    function message_task()
    {
        $action = $this->input->post("action");
        $message_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->message_model->delete_message($message_id);
        }
    }

    function trouble_shoot()
    {
        $message_data                                                   = $this->message_model->get_outbox_message_detail(193397);
        $to_id                                                          = $message_data[0]['to_id'];  // it can be numeric too or email
        $msg_id                                                         = $message_data[0]['msg_id'];
        $from_id                                                        = $message_data[0]['username'];
        $from_type                                                      = $message_data[0]['from_type'];
        $to_type                                                        = $message_data[0]['to_type'];
        $this->load->helper('email');
        echo '<br>TO: ' . $to_id;
        echo '<br>From: ' . $from_id;
        if (valid_email(trim($to_id))) {
            echo '<hr> IF';
            $contact_details                                            = $this->message_model->get_name_from_email($to_id, $from_id, 2112);
        } else {
            echo '<hr> Else';
            $contact_details                                            = $this->message_model->get_contact_name($msg_id, $to_id, $from_id, $from_type, $to_type);
        }

        echo $this->db->last_query() . '<pre>';
        print_r($contact_details);
        echo '</pre>';
    }
}
