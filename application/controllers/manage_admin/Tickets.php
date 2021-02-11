<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/tickets_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {
        $redirect_url       = 'manage_admin';
        $function_name      = 'tickets';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Tickets';
        $this->data['tickets_count'] = $this->tickets_model->get_all_tickets_count();

        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $records_per_page;
        }

        $baseUrl = base_url('manage_admin/tickets');
        $uri_segment = 3;
        $config = array();
        $config['base_url'] = $baseUrl;
        $config['total_rows'] = $this->data['tickets_count'];
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
        $this->data['links'] = $this->pagination->create_links();
        $this->data['tickets'] = $this->tickets_model->get_all_tickets($records_per_page, $my_offset);
        $this->render('manage_admin/tickets/index');
    }

    public function view_ticket($ticket_id) {
        $admin_id = $this->session->userdata('user_id');
        
        if (!isset($admin_id) || $admin_id == 0 || $admin_id == NULL) {
            redirect(base_url('login'), "refresh");
        }

        if (!isset($ticket_id) || $ticket_id == '' || $ticket_id == NULL || $ticket_id == 0) {
            $this->session->set_flashdata('message', 'Ticket not found.');
            redirect('manage_admin/tickets');
        }

        $this->data['page_title'] = 'View Ticket';
        $this->tickets_model->update_ticket_status($ticket_id, array('admin_read' => 1));
        $this->data['ticket'] = $this->tickets_model->get_ticket_by_id($ticket_id);
        $messages = $this->tickets_model->get_messages_by_id($ticket_id);
        $this->data['messages_count'] = $messages->num_rows();
        $this->data['messages'] = $messages->result_array();
        $this->data['files'] = $this->tickets_model->get_ticket_files($ticket_id);
        $this->render('manage_admin/tickets/view_ticket');
    }

    public function add_new_ticket_message($ticket_id) { // check user login
        $admin_id = $this->session->userdata('user_id');
        
        if (!isset($admin_id) || $admin_id == 0 || $admin_id == NULL) {
            redirect(base_url('login'), 'refresh');
        }

        if (!isset($ticket_id) || $ticket_id == '' || $ticket_id == NULL || $ticket_id == 0) {
            $this->session->set_flashdata('message', 'Ticket not found.');
            redirect('manage_admin/tickets');
        }

        $this->form_validation->set_rules('message_body', 'Message Body', 'xss_clean|trim|required');
        $this->form_validation->set_rules('ticket_status', 'Ticket Status', 'xss_clean|trim');

        if ($this->form_validation->run() == false) {
            
        } else {
            $message_body = $this->input->post('message_body');
            $ticket_status = $this->input->post('ticket_status');
            $insert_array = array();
            $insert_array['ticket_sid'] = $ticket_id;
            $insert_array['employer_sid'] = $this->ion_auth->user()->row()->id;
            $insert_array['message_body'] = $message_body;
            $insert_array['date'] = date('Y-m-d H:i:s');
            $insert_array['employee_type'] = 'admin';
            $insert_array['employee_name'] = $this->ion_auth->user()->row()->first_name . ' ' . $this->ion_auth->user()->row()->last_name;
            $this->tickets_model->update_ticket_status($ticket_id, array('employee_read' => 0));
            $message_id = $this->tickets_model->add_ticket_message($insert_array, $ticket_status);
            $ticket = $this->tickets_model->get_ticket_by_id($ticket_id);

            // ************* file upload code start ************* //
            if (isset($_FILES['document']) && $_FILES['document']['name'] != '') {
                $file = explode(".", $_FILES['document']['name']);  // [0] = file_name, [1] = extension
                $document = $file[0] . '_' . generateRandomString(15);
                $document = clean($document);
                $document = $document . '.' . $file[1];
                
                if($_FILES['document']['size'] == 0){
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                    redirect("manage_admin/tickets/view_ticket/" . $ticket_id);
                }
                
                $aws = new AwsSdk();
                $aws->putToBucket($document, $_FILES['document']['tmp_name'], AWS_S3_BUCKET_NAME);
                $file_insert_array = array();
                $file_insert_array['ticket_sid'] = $ticket_id;
                $file_insert_array['ticket_message_sid'] = $message_id;
                $file_insert_array['company_sid'] = $ticket['company_sid'];
                $file_insert_array['uploaded_by_sid'] = $this->ion_auth->user()->row()->id;
                $file_insert_array['uploader_type'] = 'admin';
                $file_insert_array['uploaded_file_name'] = $document;
                $file_insert_array['saved_file_name'] = $file[0] . '.' . $file[1];
                $file_insert_array['date_added'] = date('Y-m-d H:i:s');
                $file_insert_array['saved_file_type'] = $file[1];
                $this->tickets_model->save_ticket_file($file_insert_array);
            }
            // ************* file upload code end *************** //

            /***************************** send email to employer(s) code start *************************************/
            $employer_sids_array = $this->tickets_model->get_ticket_participants($ticket_id);
            $employer_count = $employer_sids_array->num_rows();
            $employer_sids = $employer_sids_array->result_array();

            if ($employer_count > 0) {
                foreach ($employer_sids as $employer) {
                    $employer_data = $this->tickets_model->get_employee_data($employer['employer_sid']);
                    $body = '<p>Dear '. $employer_data['first_name'] . ' ' . $employer_data['last_name'] .',</p>';
                    $body .= '<p>'.STORE_NAME.' Supportee has responded to your support ticket.</p>';
                    $body .= '<p>Ticket Subject: ' . $ticket['subject'] . '</p>';
                    $body .= '<p>Please review the ticket from Employer Portal.</p>';
                    $body .= '<br><br>';
                    $body .= '<p>Thank You.</p>';
                    $body .= '<p>---------------------------------------------------------</p>';
                    $body .= '<p>Automated Email; Please Do Not reply!</p>';
                    $body .= '<p>---------------------------------------------------------</p>';

                    $identity_key = generateRandomString(48);
                    $message_hf = message_header_footer_domain($ticket['company_sid'], $ticket['company_name']);
                    $secret_key = $identity_key . "__";
                    $autoemailbody = $message_hf['header']
                            . $body
                            . $message_hf['footer']
                            . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                            . $secret_key . '</div>';

                    sendMail(REPLY_TO, $employer_data['email'], 'Ticket Updated', $autoemailbody, $ticket['company_name'], REPLY_TO);

                    $email_log_autoresponder = array();
                    $email_log_autoresponder['company_sid'] = $ticket['company_sid'];
                    $email_log_autoresponder['sender'] = REPLY_TO;
                    $email_log_autoresponder['receiver'] = $employer_data['email'];
                    $email_log_autoresponder['from_type'] = 'admin';
                    $email_log_autoresponder['to_type'] = 'employer';
                    $email_log_autoresponder['users_type'] = 'admin';
                    $email_log_autoresponder['sent_date'] = date('Y-m-d H:i:s');
                    $email_log_autoresponder['subject'] = 'Ticket Updated';
                    $email_log_autoresponder['message'] = $autoemailbody;
                    $email_log_autoresponder['job_or_employee_id'] = $employer['employer_sid'];
                    $save_email_log = save_email_log_autoresponder($email_log_autoresponder);
                }
            } 
            /***************************** send email to employer(s) code end ***************************************/

            $this->session->set_flashdata('message', '<b>Success:</b> New Ticket Message added successfully');
        }
        redirect("manage_admin/tickets/view_ticket/" . $ticket_id);
    }
}