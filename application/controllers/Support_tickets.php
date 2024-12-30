<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Support_tickets extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('tickets_model');
        $this->load->library("pagination");
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {

            if (!checkIfAppIsEnabled('supporttickets')) {
                $this->session->set_flashdata('message', '<b>Error:</b> Access denied');
                redirect(base_url('dashboard'), "refresh");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'support_tickets');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data["session"]["company_detail"]["sid"];
            $data['title'] = "Technical Support Tickets";
            $data['tickets_count'] = $this->tickets_model->get_all_tickets_count($company_id);
            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('support_tickets');
            $uri_segment = 2;
            $config = array();
            $config['base_url'] = $baseUrl;
            $config['total_rows'] = $data['tickets_count'];
            $config['per_page'] = $records_per_page;
            $config['uri_segment'] = $uri_segment;
            $choice = $config['total_rows'] / $config['per_page'];
            $config['num_links'] = ceil($choice);
            $config['use_page_numbers'] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';
            $this->pagination->initialize($config);
            $data['links'] = $this->pagination->create_links();
            $data['tickets'] = $this->tickets_model->get_all_tickets($company_id, $records_per_page, $my_offset);

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            $data['employee'] = $data['session']['employer_detail'];

            $this->load->view('main/header', $data);
            $this->load->view('tickets/tickets_list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function process_ticket() {
        if ($this->session->userdata('logged_in')) {
            $action = $this->input->post('action');
            $ticketId = $this->input->post('sid');

            foreach ($ticketId as $id) {
                if ($action == 'delete') {
                    $this->session->set_flashdata('message', '<b>Success:</b> Ticket(s) deleted successfully.');
                    $this->tickets_model->delete_ticket($id);
                } elseif ($action == 'active') {
                    $this->session->set_flashdata('message', '<b>Success:</b> Ticket(s) activated successfully.');
                    $this->tickets_model->change_status($id, 'active');
                } elseif ($action == 'deactive') {
                    $this->session->set_flashdata('message', '<b>Success:</b> Ticket(s) deactivated successfully.');
                    $this->tickets_model->change_status($id, 'deactive');
                }
            }
        }
    }

    public function view($ticket_id) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            if (!isset($ticket_id) || $ticket_id == '' || $ticket_id == NULL || $ticket_id == 0) {
                $this->session->set_flashdata('message', 'Ticket not found.');
                redirect("support_tickets");
            }

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'view');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'View Support Ticket';
            $this->tickets_model->update_ticket_status($ticket_id, array('employee_read' => 1));
            $data['ticket'] = $this->tickets_model->get_ticket_by_id($ticket_id);
            $messages = $this->tickets_model->get_messages_by_id($ticket_id);
            $data['messages_count'] = $messages->num_rows();
            $data['messages'] = $messages->result_array();
            $data['files'] = $this->tickets_model->get_ticket_files($ticket_id);

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            $data['employee'] = $data['session']['employer_detail'];

            $this->load->view('main/header', $data);
            $this->load->view('tickets/view_ticket');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_new_ticket_message($ticket_id) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            if (!isset($ticket_id) || $ticket_id == '' || $ticket_id == NULL || $ticket_id == 0) {
                $this->session->set_flashdata('message', 'Ticket not found.');
                redirect("support_tickets");
            }

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'support_tickets');

            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $CompanyName = $data['session']['company_detail']['CompanyName'];

            $config = array(
                array(
                    'field' => 'message_body',
                    'label' => 'Message Body',
                    'rules' => 'xss_clean|trim|required'
                )
            );

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            if ($this->form_validation->run() == FALSE) {
                // do nothing
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $insert_array = array();
                $insert_array['message_body'] = $formpost['message_body'];
                $insert_array['ticket_sid'] = $ticket_id;
                $insert_array['employer_sid'] = $employer_id;
                $insert_array['date'] = date('Y-m-d H:i:s');
                $insert_array['employee_type'] = 'employee';
                $insert_array['employee_name'] = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                $this->tickets_model->update_ticket_status($ticket_id, array('admin_read' => 0));
                $message_id = $this->tickets_model->add_ticket_message($insert_array);

                if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                    $file = explode(".", $_FILES['document']['name']);  // [0] = file_name, [1] = extension
                    $document = $file[0] . '_' . generateRandomString(15);
                    $document = clean($document);
                    $document = $document . '.' . $file[1];

                    if($_FILES['document']['size'] == 0){
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect('support_tickets/view/' . $ticket_id);
                    }
                    
                    $aws = new AwsSdk();
                    $aws->putToBucket($document, $_FILES['document']['tmp_name'], AWS_S3_BUCKET_NAME);
                    $file_insert_array = array();
                    $file_insert_array['ticket_sid'] = $ticket_id;
                    $file_insert_array['ticket_message_sid'] = $message_id;
                    $file_insert_array['company_sid'] = $company_id;
                    $file_insert_array['uploaded_by_sid'] = $employer_id;
                    $file_insert_array['uploader_type'] = 'employee';
                    $file_insert_array['uploaded_file_name'] = $document;
                    $file_insert_array['saved_file_name'] = $file[0] . '.' . $file[1];
                    $file_insert_array['date_added'] = date('Y-m-d H:i:s');
                    $file_insert_array['saved_file_type'] = $file[1];
                    $this->tickets_model->save_ticket_file($file_insert_array);
                }

                $messages = $this->tickets_model->get_messages_by_id($ticket_id, 'DESC');
                $messages_count = $messages->num_rows();
                $message_history = $messages->result_array();
                $ticket = $this->tickets_model->get_ticket_by_id($ticket_id);
                
                $body = '<p>Dear Admin,</p>';
                $body .= '<p>The support ticket has been updated, a new message has been added to it.</p>';
                $body .= '<p>Ticket Subject: ' . $ticket['subject'] . '</p>';
                $body .= '<p>Please review the ticket from Admin Panel.</p>';
                $body .= '<br><p>Automated Email: Please Do Not Reply!</p>';

                if ($messages_count > 0) {
                    $body .= '<p>Ticket History</p>';
                    $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;">';
                    
                    foreach ($message_history as $message) {
                        $body .= '<tr>';
                        $body .= '<td>';
                        $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;border:1px solid #ddd; text-align:left;">';
                        $body .= '<tr>';
                        $body .= '<th bgcolor="#f5f5f5" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 15px;font-weight: 600;text-transform: capitalize;"><i>';
                        
                        if ($message['employee_type'] == 'admin') {
                            $body .= STORE_NAME . ' Support Team';
                        } else {
                            $body .= $message['employee_name'];
                        }
                        
                        $body .= '<small style="color:#333;font-weight:normal;padding-left:15px;">' . date_with_time($message['date']) . '</small></i></th>';
                        $body .= '</tr>';
                        $body .= '<tr>';
                        $body .= '<td bgcolor="#fff" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 14px;">';
                        $body .= $message['message_body'];
                        $body .= '</td>';
                        $body .= '</tr>';
                        $body .= '</table>';
                        $body .= '</td>';
                        $body .= '</tr>';
                        $body .= '<tr><td height="20"></td></tr>';
                    }
                    $body .= '</table>';
                }

                $identity_key = generateRandomString(48);
                $message_hf = message_header_footer_domain($company_id, $CompanyName);
                $secret_key = $identity_key . "__";
                $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                    . $body
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                //sendMail(FROM_EMAIL_INFO, TO_EMAIL_STEVEN, 'Support Ticket Updated', $autoemailbody, $CompanyName, FROM_EMAIL_INFO);
                sendMail(FROM_EMAIL_INFO, TO_EMAIL_DEV, 'Support Ticket Updated', $autoemailbody, $CompanyName, FROM_EMAIL_INFO);
                //Send Emails Through System Notifications Email - Start
                $system_notification_emails = get_system_notification_emails('support_ticket_emails');

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        sendMail(FROM_EMAIL_INFO, $system_notification_email['email'], 'Support Ticket Updated', $autoemailbody, $CompanyName, FROM_EMAIL_INFO);
                    }
                }
                //Send Emails Through System Notifications Email - End
                $employer_sids_array = $this->tickets_model->get_ticket_participants($ticket_id, $employer_id);
                $employer_count = $employer_sids_array->num_rows();
                $employer_sids = $employer_sids_array->result_array();

                if ($employer_count > 0) {
                    foreach ($employer_sids as $employer) {
                        $employer_data = $this->tickets_model->get_employee_data($employer['employer_sid']);
                        $body = '<p>Dear ' . $employer_data['first_name'] . ' ' . $employer_data['last_name'] . ',</p>';
                        $body .= '<p>The support ticket has been updated, a new message has been added to it by ' . $insert_array['employee_name'] . '.</p>';
                        $body .= '<p>Ticket Subject: ' . $ticket['subject'] . '</p>';
                        $body .= '<p>Please review the ticket from Employer Portal.</p>';
                        $body .= '<br><p>Automated Email: Please Do Not Reply!</p>';

                        if ($messages_count > 0) {
                            $body .= '<p>Ticket History</p>';
                            $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;">';
                            
                            foreach ($message_history as $message) {
                                $body .= '<tr>';
                                $body .= '<td>';
                                $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;border:1px solid #ddd; text-align:left;">';
                                $body .= '<tr>';
                                $body .= '<th bgcolor="#f5f5f5" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 15px;font-weight: 600;text-transform: capitalize;"><i>';
                                
                                if ($message['employee_type'] == 'admin') {
                                    $body .= STORE_NAME . ' Support Team';
                                } else {
                                    $body .= $message['employee_name'];
                                }
                                
                                $body .= '<small style="color:#333;font-weight:normal;padding-left:15px;">' . date_with_time($message['date']) . '</small></i></th>';
                                $body .= '</tr>';
                                $body .= '<tr>';
                                $body .= '<td bgcolor="#fff" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 14px;">';
                                $body .= $message['message_body'];
                                $body .= '</td>';
                                $body .= '</tr>';
                                $body .= '</table>';
                                $body .= '</td>';
                                $body .= '</tr>';
                                $body .= '<tr><td height="20"></td></tr>';
                            }
                            $body .= '</table>';
                        }

                        $identity_key = generateRandomString(48);
                        $message_hf = message_header_footer_domain($company_id, $CompanyName);
                        $secret_key = $identity_key . "__";
                        $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                            . $body
                            . $message_hf['footer']
                            . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                            . $secret_key . '</div>';

                        $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                        sendMail(FROM_EMAIL_INFO, $employer_data['email'], 'Support Ticket Updated', $autoemailbody, $CompanyName, FROM_EMAIL_INFO);
                        sendMail(FROM_EMAIL_INFO, TO_EMAIL_DEV, 'Support Ticket Updated', $autoemailbody, $CompanyName, FROM_EMAIL_INFO);
                    }
                }
                $this->session->set_flashdata('message', '<b>Success:</b> New Support Ticket Message added successfully');
            }
            redirect('support_tickets/view/' . $ticket_id);
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'add');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $CompanyName = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'Add New Support Ticket';

            $config = array(
                array(
                    'field' => 'subject',
                    'label' => 'Subject',
                    'rules' => 'xss_clean|trim|required'
                ),
                array(
                    'field' => 'message_body',
                    'label' => 'Message Body',
                    'rules' => 'xss_clean|trim|required'
                )
            );

            $ticket_category = $this->input->post('ticket_category');

            if (isset($ticket_category) && $ticket_category == 'Other') {
                $config[] = array(  'field' => 'custom_category',
                                    'label' => 'Custom Category',
                                    'rules' => 'xss_clean|trim|required');
            }

            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);

            $load_view = check_blue_panel_status(false, 'self');
            $data['load_view'] = $load_view;

            $data['employee'] = $data['session']['employer_detail'];

            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('tickets/add_ticket');
                $this->load->view('main/footer');
            } else {

                $formpost = $this->input->post(NULL, TRUE);
                $insert_array = array(); // for insertion into tickets table
                $insert_array2 = array(); // for insertion into ticket_messages table
                $insert_array['subject'] = $formpost['subject'];
                $insert_array['company_sid'] = $company_id;
                $insert_array['creation_date'] = date('Y-m-d H:i:s');
                $insert_array['updated_date'] = date('Y-m-d H:i:s');
                $insert_array['status'] = 'Awaiting Response';
                $insert_array['employee_read'] = 1;
                $insert_array['company_name'] = $CompanyName;
                
                if (isset($formpost['ticket_category']) && $formpost['ticket_category'] == 'Other') {
                    $insert_array['ticket_category'] = $formpost['custom_category'];
                } else {
                    $insert_array['ticket_category'] = $formpost['ticket_category'];
                }

                $insert_array2['message_body'] = $formpost['message_body'];
                $insert_array2['date'] = date('Y-m-d H:i:s');
                $insert_array2['employee_type'] = 'employee';
                $insert_array2['employer_sid'] = $employer_id;
                $insert_array2['employee_name'] = $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'];
                $file_insert_array = array();

                if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                    $file = explode(".", $_FILES['document']['name']);  // [0] = file_name, [1] = extension
                    $document = $file[0] . '_' . generateRandomString(15);
                    $document = clean($document);
                    $document = $document . '.' . $file[1];
                    
                    if($_FILES['document']['size'] == 0){
                        $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                        redirect('support_tickets');
                    }
                    
                    $aws = new AwsSdk();
                    $aws->putToBucket($document, $_FILES['document']['tmp_name'], AWS_S3_BUCKET_NAME);
                    $file_insert_array['company_sid'] = $company_id;
                    $file_insert_array['uploaded_by_sid'] = $employer_id;
                    $file_insert_array['uploader_type'] = 'employee';
                    $file_insert_array['uploaded_file_name'] = $document;
                    $file_insert_array['saved_file_name'] = $file[0] . '.' . $file[1];
                    $file_insert_array['date_added'] = date('Y-m-d H:i:s');
                    $file_insert_array['saved_file_type'] = $file[1];
                }

                $this->tickets_model->add_ticket($insert_array, $insert_array2, $file_insert_array);
                $body = '<p>Dear Admin,</p>';
                $body .= '<p>A new ticket has been generated by ' . $insert_array['company_name'] . '.</p>';
                $body .= '<p>Ticket Subject: ' . $insert_array['subject'] . '</p>';
                $body .= '<p>Please review the ticket from Admin Panel.</p>';
                $body .= '<br><p>Automated Email: Please Do Not Reply!</p>';
                $body .= '<p>Ticket Details</p>';
                $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;">';
                $body .= '<tr>';
                $body .= '<td>';
                $body .= '<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#fff" style="font-size:14px; color:#666; font-family:Open Sans, sans-serif, Trebuchet MS, Arial;border:1px solid #ddd; text-align:left;">';
                $body .= '<tr>';
                $body .= '<th bgcolor="#f5f5f5" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 15px;font-weight: 600;text-transform: capitalize;"><i>';
                $body .= $insert_array2['employee_name'];
                $body .= '<small style="color:#333;font-weight:normal;padding-left:15px;">' . date_with_time($insert_array2['date']) . '</small></i></th>';
                $body .= '</tr>';
                $body .= '<tr>';
                $body .= '<td bgcolor="#fff" style="border-bottom:1px solid #ddd;padding: 10px 15px; color: #000;font-size: 14px;">';
                $body .= $insert_array2['message_body'];
                $body .= '</td>';
                $body .= '</tr>';
                $body .= '</table>';
                $body .= '</td>';
                $body .= '</tr>';
                $body .= '<tr><td height="20"></td></tr>';
                $body .= '</table>';

                $identity_key = generateRandomString(48);
                $message_hf = message_header_footer_domain($company_id, $insert_array['company_name']);
                $secret_key = $identity_key . "__";
                $autoemailbody = FROM_INFO_EMAIL_DISCLAIMER_MSG . $message_hf['header']
                    . $body
                    . $message_hf['footer']
                    . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                    . $secret_key . '</div>';

                $autoemailbody .= FROM_INFO_EMAIL_DISCLAIMER_MSG;

                sendMail(FROM_EMAIL_INFO, TO_EMAIL_DEV, 'New Support Ticket Generated', $autoemailbody, $insert_array['company_name'], FROM_EMAIL_INFO);
                //sendMail(FROM_EMAIL_INFO, TO_EMAIL_STEVEN, 'New Support Ticket Generated', $autoemailbody, $insert_array['company_name'], FROM_EMAIL_INFO);
                //Send Emails Through System Notifications Email - Start
                $system_notification_emails = get_system_notification_emails('support_ticket_emails');

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        sendMail(FROM_EMAIL_INFO, $system_notification_email['email'], 'New Support Ticket Generated', $autoemailbody, $insert_array['company_name'], FROM_EMAIL_INFO);
                    }
                }
                //Send Emails Through System Notifications Email - End
                $this->session->set_flashdata('message', '<b>Success:</b> New Support Ticket added successfully');
                redirect("support_tickets");
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}