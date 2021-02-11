<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Free_demo extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('demo_model');
        $this->load->library("pagination");
        $this->load->model('manage_admin/settings_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() { 
        $redirect_url = 'manage_admin';
        $function_name = 'index';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);

        $config                             = array();
        if ($this->uri->segment(2) == 'free_demo') {
            $config['base_url']                 = base_url() . "manage_admin/free_demo/";
        } else if ($this->uri->segment(2) == 'referred_clients') {
            $config['base_url']                 = base_url() . "manage_admin/referred_clients/";
        }
        
        $page                               = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
        $config['per_page']                 = 50;
        $my_offset                          = 0;
        
        if ($page > 1) {
            $my_offset                      = ($page - 1) * $config["per_page"];
        }

        $messages                           = $this->demo_model->get_all_messages($config['per_page'], $my_offset);
        $total                              = $this->demo_model->get_all_messages_total();
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
        $application_status                 = $this->settings_model->get_admin_status();
        $status_name                        = $this->demo_model->get_all_status_name();
        $groups                             = $this->ion_auth->groups()->result();
        $total_unread                       = $this->demo_model->get_unread_enquires_count();
        
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
        $this->render('manage_admin/free_demo/listing_view');
    }

    public function enquiry_message_details($sid = NULL) {
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {
            $redirect_url                               = 'manage_admin/referred_clients';
            $function_name                              = 'enquiry_message_details';
            $admin_id                                   = $this->ion_auth->user()->row()->id;
            $security_details                           = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name); 
            $this->demo_model->mark_read($sid);
            $groups                                     = $this->ion_auth->groups()->result();
            $message_data                               = $this->demo_model->get_inbox_message_detail($sid);

            if (empty($message_data)) {
                $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
                if ($this->uri->segment(2) == 'referred_clients') {
                    redirect('/manage_admin/referred_clients/', 'refresh');
                } else {
                    redirect('/manage_admin/free_demo/', 'refresh');
                }
            } 
          
            $user_type = '';
            if ($message_data[0]['is_reffered'] == 1) {
                $user_type = 'referred_client';
            } else {
                $user_type = 'demo_user';
            }

            $notes                                      = $this->demo_model->get_demo_request_notes($sid, $user_type);
            $scheduled_tasks                            = $this->demo_model->get_schedule_records($sid, $user_type);
            $demo_reply                                 = $this->demo_model->get_demo_reply($sid, $user_type);
            $application_status                         = $this->settings_model->get_admin_status();

            $this->data['page']                         = 'inbox';
            $this->data['page_title']                   = 'Demo Enquiry Details';
            $this->data['groups']                       = $groups;
            $this->data['message']                      = $message_data[0];
            $this->data['notes']                        = $notes;
            $this->data['scheduled_tasks']              = $scheduled_tasks;
            $this->data['demo_reply']                   = $demo_reply;
            $this->data['application_status']           = $application_status;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
            
            if ($this->form_validation->run() == false) {
                $this->data['status']                   = $message_data[0]['contact_status'];
                $status_name                            = $this->demo_model->get_status_name($this->data['status']);

                if(!empty($status_name)) {
                    $status_name                        = $status_name['0']['name'];
                } else {
                    $status_name                        = 'No Status Found!';
                }
                
                $this->data['status_name']              = $status_name;
                $this->render('manage_admin/free_demo/enquiry_message_detail');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_new_note':
                        $note_text                      = $this->input->post('note_text');

                        $data_to_insert                 = array();
                        $data_to_insert['demo_sid']     = $sid;
                        $data_to_insert['user_type']    = $user_type;
                        $data_to_insert['created_by']   = $admin_id;
                        $data_to_insert['note_text']    = $note_text;

                        $this->demo_model->insert_demo_request_note($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Saved!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                    case 'edit_new_note':
                        $note_text                      = $this->input->post('note_text');
                        $note_sid                       = $this->input->post('note_sid');
                        $data_to_insert                 = array();
                        $data_to_insert['note_text']    = $note_text;
                        $this->demo_model->update_demo_request_notes($note_sid, $data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Updated!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                    case 'delete_note':
                        
                        $note_sid                       = $this->input->post('note_sid');
                        $this->demo_model->delete_demo_request_note($sid, $user_type, $note_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Deleted!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                    case 'delete_demo_request':
                        $user_sid                       = $this->input->post('user_sid');
                        $this->demo_model->delete_demo_request($user_sid, $user_type);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Demo Request Deleted!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/free_demo', 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients', 'location');
                        }
                        break;
                    case 'schedule_a_task':
                        $post_data                      = $this->input->post();
                        $demo_sid                       = $this->input->post('user_sid');

                        if (isset($post_data['perform_action'])) {
                            unset($post_data['perform_action']);
                        }

                        if (isset($post_data['schedule_datetime'])) {
                            $schedule_date              = $post_data['schedule_datetime'];
                            $my_date                    = new DateTime($schedule_date);
                            $schedule_date              = $my_date->format('Y-m-d H:i:s');
                            $post_data['schedule_datetime'] = $schedule_date;
                        }

                        $message                        = $message_data[0];
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
                        
                        $post_data['created_by']        = $admin_id;
                        $this->demo_model->add_new_schedule_record($post_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Reminder Scheduled!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                    case 'delete_schedule_record':
                        $schedule_sid                   = $this->input->post('schedule_sid');
                        $this->demo_model->delete_schedule_record($schedule_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Deleted!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                    case 'complete_schedule_record':
                        $schedule_sid                   = $this->input->post('schedule_sid');
                        $this->demo_model->set_schedule_status($schedule_sid, 'completed');
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Completed!');
                        if ($this->uri->segment(2) == 'enquiry_message_details') {
                            redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                        } else if ($this->uri->segment(2) == 'referred_clients') {
                            redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                        }
                        break;
                }
            }
        }
    }

    public function demo_admin_reply($sid = NULL) {
        $enquirer_email                     = '';
        $enquirer_subject                   = '';
        $enquirer_name                      = '';
        
        if ($sid != NULL) {
            $message_data                   = $this->demo_model->get_inbox_message_detail($sid);
            
            if (!empty($message_data)) {
                $data                       = $message_data[0];
                $enquirer_email             = $data['email'];
                $enquirer_subject           = 'RE: ' . $data['job_role'];
                $enquirer_name              = $data['first_name'] . ' ' . $data['last_name'];
            } else {
                if ($this->uri->segment(2) == 'referred_clients') {
                    redirect('/manage_admin/referred_clients/', 'refresh');
                } else {
                    redirect('/manage_admin/free_demo/', 'refresh');
                }
            }
        }

        $user_type = '';
        if ($data['is_reffered'] == 1) {
            $user_type = 'referred_client';
        } else {
            $user_type = 'demo_user';
        }
        
        $this->data['page_title']           = 'Demo Enquiry Admin Reply';
        $this->data['enquirer_email']       = $enquirer_email;
        $this->data['enquirer_subject']     = $enquirer_subject;
        $this->data['sid']                  = $sid;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->data['admin_templates'] = $this->demo_model->fetch_admin_templates();
            $this->render('manage_admin/free_demo/send_mail');
        } else {
            $demo_sid                       = $this->input->post('demo_sid');
            $formpost                       = $this->input->post(NULL, TRUE);
            
            if (isset($formpost['demo_sid'])) {
                unset($formpost['demo_sid']);
            }

            // Added on 30-04-2019
            replace_magic_quotes(
                $formpost['subject'],
                array(
                    '{{first_name}}' => $message_data[0]['first_name'],
                    '{{last_name}}' => $message_data[0]['last_name'],
                    '{{firstname}}' => $message_data[0]['first_name'],
                    '{{lastname}}' => $message_data[0]['last_name'],
                    '{{first-name}}' => $message_data[0]['first_name'],
                    '{{last-name}}' => $message_data[0]['last_name'],
                    '{{email}}' => $message_data[0]['email'],
                    '{{phone}}'  => $message_data[0]['phone_number'],
                    '{{company_name}}'  => $message_data[0]['company_name'],
                    '{{username}}'  => '',
                )
            );
            replace_magic_quotes(
                $formpost['message'],
                array(
                    '{{first_name}}' => $message_data[0]['first_name'],
                    '{{last_name}}' => $message_data[0]['last_name'],
                    '{{firstname}}' => $message_data[0]['first_name'],
                    '{{lastname}}' => $message_data[0]['last_name'],
                    '{{first-name}}' => $message_data[0]['first_name'],
                    '{{last-name}}' => $message_data[0]['last_name'],
                    '{{email}}' => $message_data[0]['email'],
                    '{{phone}}'  => $message_data[0]['phone_number'],
                    '{{company_name}}'  => $message_data[0]['company_name'],
                    '{{username}}'  => '',
                    '{{password}}'  => '',
                    '{{create_password_link}}'  => '',
                    '{{site_url}}'  => '',
                    '{{affiliate_name}}'  => $message_data[0]['first_name'] . ' ' . $message_data[0]['last_name'],
                )
            );

            $to                             = $formpost['email'];
            $subject                        = $formpost['subject'];
            $message                        = $formpost['message'];
            $date                           = date('Y-m-d H:i:s');
            $admin                          = 'admin';
            
            if (empty($enquirer_name)) {
                $enquirer_name              = $to;
            }
//            $formpost['message'] = str_replace(chr(194)," ",$formpost['message']);
//            $formpost['message'] = mb_convert_encoding ($formpost['message'],"UTF-8","UTF-8");
//            $formpost['message'] = mb_convert_encoding ($formpost['message'],"UTF-8","ISO-8859-1");
            $formpost['message'] = mb_convert_encoding ($formpost['message'],"ISO-8859-1","UTF-8");
            $formpost['message'] = utf8_encode($formpost['message']);
            $reply_data                     = $formpost;
            $reply_data['demo_sid']         = $sid;
            $reply_data['user_type']        = $user_type;
            $reply_data['reply_date']       = date('Y-m-d H:i:s');
            $from                           = FROM_EMAIL_STEVEN;
            $body                           = EMAIL_HEADER. $message. '<br><br>'. EMAIL_FOOTER;
            sendMail($from, $to, $subject, $body, FROM_STORE_NAME);
            $formpost['message']            = $body;
            $this->demo_model->save_email_log($formpost);
            $this->demo_model->save_demo_reply($reply_data);
            $this->demo_model->set_demo_request_reply_status($demo_sid, 1);
            $this->session->set_flashdata('message', '<b>Success!</b> E-Mail is sent to the company.');
            if ($this->uri->segment(2) == 'referred_clients') {
                redirect('manage_admin/referred_clients/enquiry_message_details/'.$sid, 'refresh');
            } else {
                redirect('manage_admin/enquiry_message_details/'.$sid, 'refresh');
            }    
        }
    }

    public function view_demo_email_reply($sid = NULL) {
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {
            $redirect_url                       = 'manage_admin';
            $function_name                      = 'free_demo_enquiries';
            $admin_id                           = $this->ion_auth->user()->row()->id;
            $security_details                   = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);
            $this->data['security_details']     = $security_details;
            $reply                              = $this->demo_model->get_reply_by_id($sid);
            if (sizeof($reply) <= 0) {
                $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
                redirect('manage_admin/referred_clients', 'refresh');
            }
            $this->data['reply']                = $reply;
            $this->data['page']                 = 'View Demo Reply';
            $this->render('manage_admin/free_demo/demo_reply_view');
        }    
    }

    public function add_demo_request($sid = NULL) {
//        if ($sid == NULL || $sid <= 0) {
//            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
//            redirect('manage_admin/referred_clients', 'refresh');
//        } else {
            $redirect_url                       = 'manage_admin/free_demo';
            $function_name                      = 'add_demo_record';
            $admin_id                           = $this->ion_auth->user()->row()->id;
            $security_details                   = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);

            $this->data['title']                = 'Add Potential Client';
            $this->data['submit_button']        = 'Submit';
            $data_countries                     = db_get_active_countries();
            $this->data['security_details']     = $security_details;

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]     = db_get_active_states($value['sid']);
            }

            if($sid != NULL){
                $message_data = $this->demo_model->get_inbox_message_detail($sid);
                if (empty($message_data)) {
                    $this->session->set_flashdata('message', '<b>Error: </b>Client Details Not Found!');
                    if ($this->uri->segment(2) == 'referred_clients') {
                        redirect('/manage_admin/referred_clients/', 'refresh');
                    } else {
                        redirect('/manage_admin/free_demo/', 'refresh');
                    }
                } 
                $this->data['message']              = $message_data[0];
                $this->data['title']                = 'Edit Details';
                $this->data['submit_button']        = 'Update';
                $this->data['pp_emails']            = $this->demo_model->get_additional_pp_email($sid);
                $this->data['pp_phone_numbers']     = $this->demo_model->get_additional_pp_phone_number($sid);
                $this->data['additional_contacts']  = $this->demo_model->get_additional_contacts($sid);
            }

            $this->data['active_countries']     = $data_countries;
            $this->data['active_states']        = $data_states;
            $data_states_encode                 = htmlentities(json_encode($data_states));
            $this->data['states']               = $data_states_encode;
            $this->data['page']                 = 'Add Potential Client';
            $this->form_validation->set_rules('first_name', 'Please provide first name', 'trim|xss_clean');
            $this->form_validation->set_rules('last_name', 'Please provide last name', 'trim|xss_clean');
            $this->form_validation->set_rules('email', 'Please provide valid email address', 'trim|valid_email|xss_clean');
            $this->form_validation->set_rules('phone_number', 'Please provide valid number', 'trim|xss_clean');
            $this->form_validation->set_rules('job_role', 'Please provide your Job Role', 'trim|xss_clean');
            $this->form_validation->set_rules('client_source', 'Please tell how did you hear about us?', 'trim|xss_clean');
            $this->form_validation->set_rules('client_message', 'Your Message?', 'trim|xss_clean');
            $this->form_validation->set_rules('company_size', 'Please provide your Company Size', 'trim|xss_clean');
            $this->form_validation->set_rules('newsletter_subscribe', 'Please select your choice', 'trim|xss_clean');

            if ($this->form_validation->run() === FALSE) {
                $this->render('manage_admin/free_demo/add_demo_request');
            } else {
                $first_name                     = $this->input->post('first_name');
                $last_name                      = $this->input->post('last_name');
                $email                          = $this->input->post('email');
                $phone_number                   = $this->input->post('txt_phonenumber') ? $this->input->post('txt_phonenumber') : $this->input->post('phone_number');
                $company_name                   = $this->input->post('company_name');
                $company_size                   = $this->input->post('company_size');
                $newsletter_subscribe           = $this->input->post('newsletter_subscribe');
                $state                          = $this->input->post('state');
                $country                        = $this->input->post('country');
                $job_role                       = $this->input->post('job_role');
                $client_source                  = $this->input->post('client_source');
                $client_message                 = $this->input->post('client_message');
                $city                           = $this->input->post('city');
                $street                         = $this->input->post('street');
                $zip_code                       = $this->input->post('zip_code');
                if ($zip_code == '' || empty($zip_code)) {
                    $zip_code = NULL;
                }
                $client_message                 = htmlentities($client_message);

                if ($country == 38) {
                    $country                    = 'Canada';
                }
                if ($country == 227) {
                    $country                    = 'United States';
                }

                // Added on: 25-06-2019
                $new_timezone = $this->input->post('timezone', true);
                if($new_timezone == '') $new_timezone = NULL;

                if($newsletter_subscribe == NULL){
                    $newsletter_subscribe = 0;
                }
                if($sid == NULL){
                    $this->demo_model->free_demo($first_name, $last_name, $email, $phone_number, $company_name, $company_size, $state, $country, $job_role, $client_source, $client_message, 1, 1, 1, $newsletter_subscribe, $city, $street, $zip_code, $new_timezone);
                    $this->session->set_flashdata('message', '<b>Success: </b>Demo request added Successfully!');
                } else{
                    $update_data                = array(
                                                    'first_name'                => $first_name,
                                                    'last_name'                 => $last_name,
                                                    'email'                     => $email,
                                                    'phone_number'              => $phone_number,
                                                    'company_name'              => $company_name,
                                                    'company_size'              => $company_size,
                                                    'state'                     => $state,
                                                    'country'                   => $country,
                                                    'job_role'                  => $job_role,
                                                    'client_source'             => $client_source,
                                                    'client_message'            => $client_message,
                                                    'newsletter_subscribe'      => $newsletter_subscribe,
                                                    'city'                      => $city,
                                                    'street'                    => $street,
                                                    'timezone'                  => $new_timezone,
                                                    'zip_code'                  => $zip_code

                                                );

                    $this->demo_model->update_demo_request($sid,$update_data);
                    $this->session->set_flashdata('message', '<b>Success: </b>Client Details Updated Successfully!');
                }
                if($sid == NULL){
                    if ($this->uri->segment(2) == 'free_demo') {
                        
                        redirect('/manage_admin/free_demo/', 'refresh');
                    } else if ($this->uri->segment(2) == 'referred_clients') { 
                        redirect('/manage_admin/referred_clients/', 'refresh');
                    } 
                } else if ($this->uri->segment(2) == 'referred_clients') {
                    redirect('manage_admin/referred_clients/enquiry_message_details/' . $sid, 'location');
                } else {    
                    redirect('manage_admin/enquiry_message_details/' . $sid, 'location');
                }
                
            }
//        }
    }
    
    public function ajax_handler(){
        $data['session'] = $this->session->userdata('logged_in');

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
            $status                         = $_REQUEST['status'];
            $message_sid                    = $_REQUEST['message_sid'];
            $this->demo_model->update_contact_status($message_sid, $status);
            echo 'success';
            exit;
        }
    }

    function add_additional_contact (){
        $form_post = $this->input->post();
        if(isset($form_post['txt_phonenumber'])){ $form_post['phone_number'] = $form_post['txt_phonenumber']; unset($form_post['txt_phonenumber']); }
        else{ $form_post['phone_number'] = ''; }
        $this->demo_model->add_new_additional_contact($form_post);
        echo 'success';
        exit;
    }

    function get_additional_contact ($sid) {
        $contact = $this->demo_model->get_additional_user_contact($sid); 

        $return_data = array();
        if (!empty($contact) ) {
            $return_data['sid'] = $contact[0]['sid'];
            $return_data['full_name'] = $contact[0]['full_name'];
            $return_data['email'] = $contact[0]['email'];
            $return_data['phone_number'] = $contact[0]['phone_number'];
            if ($contact[0]['primary_person'] == 1) {
                $return_data['contact_type'] = $contact[0]['contact_type'];
            }

            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    function edit_additional_contact (){
        $form_post = $this->input->post();

        if(isset($form_post['txt_phonenumber'])){ $form_post['phone_number'] = $form_post['txt_phonenumber']; unset($form_post['txt_phonenumber']); }
        else{ $form_post['phone_number'] = ''; }

        $sid = $form_post['sid'];
        $full_name = $form_post['full_name'];
        $email = $form_post['email'];
        $phone_number = $form_post['phone_number'];


        $data_to_update = array();
        $data_to_update['full_name'] = $full_name;
        $data_to_update['email'] = $email;
        $data_to_update['phone_number'] = $phone_number;

        $this->demo_model->edit_additional_user_contact($sid, $data_to_update);
        echo 'success';
        exit;
    }

    function delete_additional_contact ($sid) {
        $this->demo_model->delete_additional_user_contact($sid); 
        echo 'success';
        exit();
    }

    function add_additional_pp_contact () {
        $form_post = $this->input->post();
        if ($form_post['contact_type'] == 'phone') {
            $form_post['email'] = 'NULL';
        } else if ($form_post['contact_type'] == 'email') {
            $form_post['phone_number'] = 'NULL';
        }

        if(isset($form_post['txt_phonenumber'])){ $form_post['phone_number'] = $form_post['txt_phonenumber']; unset($form_post['txt_phonenumber']); }
        $form_post['primary_person'] = 1;
        $this->demo_model->add_new_additional_contact($form_post);
        echo 'success';
        exit;
    }

    function edit_pp_additional_contact (){
        $form_post = $this->input->post();

        $sid = $form_post['pp_contact_sid'];
        $contact_type = $form_post['contact_type'];

        $data_to_update = array();
        if ($contact_type == 'phone') {
            if(isset($form_post['txt_phonenumber'])){ $form_post['phone_number'] = $form_post['txt_phonenumber']; unset($form_post['txt_phonenumber']); }
            $phone_number = $form_post['phone_number'];
            $data_to_update['phone_number'] = $phone_number;
        } else if ($contact_type == 'email') {
            $email = $form_post['email'];
            $data_to_update['email'] = $email;
        }

        $this->demo_model->edit_additional_user_contact($sid, $data_to_update);
        echo 'success';
        exit;
    }
}