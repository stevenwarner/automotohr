<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Referred_clients extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/referred_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
    }

    public function index(){ 
        $redirect_url                       = 'manage_admin/referred_clients';
        $function_name                      = 'index';
        $admin_id                           = $this->ion_auth->user()->row()->id;
        $security_details                   = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);

        $config                             = array();
        $config['base_url']                 = base_url() . "manage_admin/free_demo/";
        $page                               = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $config['per_page']                 = 50;
        $my_offset                          = 0;
        
        if ($page > 1) {
            $my_offset                      = ($page - 1) * $config["per_page"];
        }

        $messages                           = $this->referred_model->get_all_messages($config['per_page'], $my_offset);
        $total                              = $this->referred_model->get_all_messages_total();
        $config['total_rows']               = $total;

        if (isset($post['per_page']) && !empty($post['per_page'])) {
            $config['per_page']             = $post['per_page'];
        }

        $config['uri_segment']              = 3;
        $choice                             = $config['total_rows'] / $config['per_page'];
        $config['num_links']                = 5;
        $config['use_page_numbers']         = true;                
        $config['full_tag_open']            = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close']           = '</ul></nav><!--pagination-->';
        $config['first_link']               = '&laquo;';
        $config['first_tag_open']           = '<li class="prev page">';
        $config['first_tag_close']          = '</li>';
        $config['last_link']                = '&raquo;';
        $config['last_tag_open']            = '<li class="next page">';
        $config['last_tag_close']           = '</li>';
        $config['next_link']                = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open']            = '<li class="next page">';
        $config['next_tag_close']           = '</li>';
        $config['prev_link']                = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open']            = '<li class="prev page">';
        $config['prev_tag_close']           = '</li>';
        $config['cur_tag_open']             = '<li class="active"><a href="javascript:;">';
        $config['cur_tag_close']            = '</a></li>';
        $config['num_tag_open']             = '<li class="page">';
        $config['num_tag_close']            = '</li>';

        $this->pagination->initialize($config);
        $links                              = $this->pagination->create_links();
        $application_status                 = $this->referred_model->get_status();
        $status_name                        = $this->referred_model->get_all_status_name(); 
        $groups                             = $this->ion_auth->groups()->result();
        $total_unread                       = $this->referred_model->get_unread_enquires_count();
        
        $this->data['page']                 = 'inbox';
        $this->data['page_title']           = 'Free Demo Enquiries';
        $this->data['status']               = 'Not Contacted Yet';
        $this->data['per_page']             = $config['per_page'];
        $this->data['total_rows']           = $config['total_rows'];
        $this->data['offset']               = $my_offset;
        $this->data["links"]                = $links;
        $this->data['security_details']     = $security_details;
        $this->data['application_status']   = $application_status;      
        $this->data['status_name']          = $status_name;
        $this->data['groups']               = $groups;
        $this->data['total']                = $total;
        $this->data['total_unread']         = $total_unread;
        $this->data['messages']             = $messages;

        $this->render('manage_admin/referred/index');
    }

    public function view_details($sid = NULL){
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {

            $redirect_url                               = 'manage_admin/referred_clients';
            $function_name                              = 'view_details';
            $admin_id                                   = $this->ion_auth->user()->row()->id;
            $security_details                           = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name); 
            
            
            $this->referred_model->mark_read($sid);
            $uri_segment                                = $this->uri->segment(2);
            $referred                                   = $this->referred_model->get_referred_client($sid);

            $user_type = '';
            if ($referred[0]['is_reffered'] == 1) {
                $user_type = 'referred_client';
            } else {
                $user_type = 'demo_user';
            }

            $scheduled_tasks                            = $this->referred_model->get_schedule_records($sid, $user_type);
            $notes                                      = $this->referred_model->get_refer_request_notes($sid, $user_type);
            $referral_reply                             = $this->referred_model->get_referral_client_reply($sid, $user_type);
            $message_data                               = $this->referred_model->get_inbox_message_client_detail($sid);
            
            $this->data['page_title']                   = 'Referral Program';
            $this->data['security_details']             = $security_details;
            $this->data['scheduled_tasks']              = $scheduled_tasks;
            $this->data['referred']                     = $referred[0];
            $this->data['message']                      = $message_data[0];
            $this->data['referral_reply']               = $referral_reply;
            $this->data['notes']                        = $notes;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $is_read    = 1;
                $status_arr = array('is_read' => $is_read);
                $this->referred_model->change_status($sid,$status_arr);
                
                if(empty($referred)) {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
                    redirect('manage_admin/referred', 'refresh');
                }
                
                $this->data['status']                   = $referred[0]['contact_status'];
                $this->data['application_status']       = $this->referred_model->get_admin_status();
                $status_name                            = $this->referred_model->get_status_name($this->data['status']);
                
                if(!empty($status_name)) {
                    
                    $status_name = $status_name['0']['name'];
                } else {
                    
                    $status_name = 'No Status Found!';
                }
                
                $this->data['status_name']              = $status_name;
                $this->render('manage_admin/referred/view_details_new');
            } else {
                $perform_action = $this->input->post('perform_action');
                $redirect_url_segment = $this->uri->segment(2);

                switch ($perform_action) {
                    case 'add_new_note':
                        $note_text                      = $this->input->post('note_text');

                        $data_to_insert                 = array();
                        $data_to_insert['demo_sid']     = $sid;
                        $data_to_insert['user_type']    = $user_type;
                        $data_to_insert['created_by']   = $admin_id;
                        $data_to_insert['note_text']    = $note_text;

                        $this->referred_model->insert_referral_client_note($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Saved!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'edit_new_note':
                        $note_text                      = $this->input->post('note_text');
                        $note_sid              = $this->input->post('note_sid');
                        $data_to_insert                 = array();
                        $data_to_insert['note_text']    = $note_text;

                        $this->referred_model->update_referral_client_notes($note_sid, $data_to_insert);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Updated!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'delete_note':

                        $note_sid                       = $this->input->post('note_sid');

                        $this->referred_model->delete_referral_client_note($sid, $user_type, $note_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Deleted!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'schedule_a_task':
                        $post_data                      = $this->input->post();

                        if (isset($post_data['perform_action'])) {
                            unset($post_data['perform_action']);
                        }

                        if (isset($post_data['schedule_datetime'])) {
                            $schedule_date              = $post_data['schedule_datetime'];
                            $my_date                    = new DateTime($schedule_date);
                            $schedule_date              = $my_date->format('Y-m-d H:i:s');
                            $post_data['schedule_datetime'] = $schedule_date;
                        }
                        
                        $message = $message_data[0];
                        $post_data['user_sid']          = $sid;
                        $post_data['user_type']         = $user_type;
                        $post_data['fdr_first_name']    = $message["first_name"];
                        $post_data['fdr_last_name']     = $message["last_name"];
                        $post_data['fdr_job_role']      = $message["job_role"];
                        $post_data['fdr_email']         = $message["email"];
                        $post_data['fdr_phone_number']  = $message["phone_number"];
                        $post_data['fdr_company_name']  = $message["company_name"];
                        $post_data['created_by']        = $admin_id;
                        $post_data['reminder_email_triggered_date'] = NULL;

                        $this->referred_model->add_new_schedule_record($post_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Reminder Scheduled!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'delete_schedule_record':
                        $schedule_sid                   = $this->input->post('schedule_sid');
                        $this->referred_model->delete_schedule_record($schedule_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Deleted!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'complete_schedule_record':
                        $schedule_sid                   = $this->input->post('schedule_sid');
                        $this->referred_model->set_schedule_status($schedule_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Completed!');
                        redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'delete_demo_request':
                        $user_sid                       = $this->input->post('user_sid');
                        $this->referred_model->delete_demo_request($sid, $user_type);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Demo Request Deleted!');
                        redirect('manage_admin//referred_clients', 'location');
                        break;    
                }
            }
        }    
    }

    public function edit_details($sid = NULL){
        if($sid == NULL){
            $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
            redirect('manage_admin/referred', 'refresh');
        } else { 
            $redirect_url                           = 'manage_admin/referred_clients';
            $function_name                          = 'edit_details';
            $admin_id                               = $this->ion_auth->user()->row()->id;
            $security_details                       = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);
            

            $referred                               = $this->referred_model->get_referred_user_detail($sid);
            $data_countries                     = db_get_active_countries();
            $this->data['security_details']     = $security_details;

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]     = db_get_active_states($value['sid']);
            }

            $this->data['active_countries']     = $data_countries;
            $this->data['active_states']        = $data_states;
            $data_states_encode                 = htmlentities(json_encode($data_states));
            $this->data['states']               = $data_states_encode;
            
            if(empty($referred)) {
                $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
                redirect('manage_admin/referred', 'refresh');
            }
            
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('email', 'E-Mail', 'required|xss_clean|trim|valid_email');
            
            if ($this->form_validation->run() == false) {
                $this->data['page_title']           = 'Referral Program';
                $this->data['security_details']     = $security_details;
                $this->data['referred']             = $referred[0];
                $this->data['status']               = $referred[0]['contact_status'];

                $this->render('manage_admin/referred/edit_referred');
            } else {

                $formpost                           = $this->input->post(NULL, TRUE);
                $change_email                       = $this->referred_model->get_referral_user_email($sid);
                if ($change_email[0]['email'] == $formpost['email']) {
                    $this->referred_model->update_details($sid, $formpost);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Details updates!');
                    redirect('manage_admin/referred_clients/view_details/'.$sid, 'refresh');
                } else {
                    $already_referred = $this->referred_model->check_referral_user($formpost['email']);
                    
                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Referral Email!');
                        redirect('manage_admin/referred_clients/view_details/'.$sid, 'refresh');
                    }
                   
                    $this->referred_model->update_details($sid, $formpost);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Details updates!');
                    redirect('manage_admin/referred_clients/view_details/'.$sid, 'refresh');
                }
            }
        }
    }

    public function ajax_handler() {
        $data['session']                            = $this->session->userdata('logged_in');
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
            $status                                 = $_REQUEST['status'];
            $message_sid                            = $_REQUEST['message_sid'];
            $this->referred_model->update_contact_status($message_sid, $status);
            echo 'success';
            exit;
        }
    }

    public function send_reply($sid = NULL) {
        if ($sid != NULL) {
            $message_data                           = $this->referred_model->get_referred_detail($sid);
            $email_templates                        = $this->referred_model->get_email_templates();

            if (!empty($message_data)) {
                $data                               = $message_data[0];
                $enquirer_email                     = $data['email'];
                $enquirer_subject                   = 'RE: ' . $data['job_role'];
                $enquirer_name                      = $data['first_name'] . ' ' . $data['last_name'];

                $user_type = '';
                if ($data['is_reffered'] == 1) {
                    $user_type = 'referred_client';
                } else {
                    $user_type = 'demo_user';
                }

                $this->data['page_title']           = 'Referred Reply for '.$enquirer_name;
                $this->data['sid']                  = $sid;
                $this->data['enquirer_email']       = $enquirer_email;
                $this->data['enquirer_subject']     = $enquirer_subject;
                $this->data['email_templates']       = $email_templates;
                $this->load->library('form_validation');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required');

                if ($this->form_validation->run() === FALSE) {
                    $this->render('manage_admin/referred/send_mail');
                } else {
                    $formpost                       = $this->input->post(NULL, TRUE);
                    
                    $to                             = $formpost['email'];
                    $subject                        = $formpost['subject'];
                    $message                        = $formpost['message'];
                    $date                           = date('Y-m-d H:i:s');
                    $admin                          = 'admin';

                    if (empty($enquirer_name)) {
                        $enquirer_name              = $to;
                    }

                    $reply_data                     = $formpost;
                    $reply_data['reply_date']       = date('Y-m-d H:i:s');
                    $reply_data['demo_sid']         = $sid;
                    $reply_data['user_type']        = $user_type;

                    $from = FROM_EMAIL_STEVEN;
                    $body = EMAIL_HEADER
                            . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $enquirer_name . ',</h2>'
                            . '<br><br>' 
                            . $message
                            . '<br><br>'
                            . EMAIL_FOOTER;

                    sendMail($from, $to, $subject, $body, FROM_STORE_NAME);
                   
                    $formpost['message']            = $body;
                    $this->referred_model->save_email_log($formpost);
                    $this->referred_model->save_referral_reply($reply_data);
                    $this->session->set_flashdata('message', '<b>Success!</b> E-Mail is sent to Refer Person.');
                    redirect('manage_admin/referred_clients/view_details/' . $sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error!</b> Referral not found!');
                redirect('manage_admin/referred_clients', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error!</b> Referral not found!');
            redirect('manage_admin/referred_clients', 'refresh');
        }
    }

    public function view_reply($sid = NULL) {
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {
            $redirect_url                               = 'manage_admin/referred_clients';
            $function_name                              = 'view_reply';
            $admin_id                                   = $this->ion_auth->user()->row()->id;
            $security_details                           = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);

            $reply                                      = $this->referred_model->get_reply_by_id($sid);

            $this->data['security_details']             = $security_details;
            $this->data['reply']                        = $reply;
            $this->data['page']                         = 'View Referral Reply';

            $this->render('manage_admin/referred/view_send_mail');
        }
    }

    function get_template_body($template_sid) {
        $template_body = $this->referred_model->get_email_template_body($template_sid); 

        $return_data = array();
        if (!empty($template_body) ) {
            $return_data['email_body'] = $template_body[0]['message_body'];
            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    public function getnewstates($country_sid) {
        $states = db_get_active_states($country_sid);
        $html = '<option value="">Select State</option>';
        foreach ($states as $key => $state) {
            $name = $state['state_name'];
            $html .= '<option value="' . $name . '">' . $name . '</option>';
        }
      
        echo json_encode($html);
    }
}