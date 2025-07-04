<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Affiliates extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/affiliation_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $admin_id                           = $this->ion_auth->user()->row()->id;
        $security_details                   = db_get_admin_access_level_details($admin_id);
        $this->data['security_details']     = $security_details;
    }

    public function index(){
        $redirect_url                       = 'manage_admin';
        $function_name                      = 'affiliate_request';
        $admin_id                           = $this->ion_auth->user()->row()->id;
        $security_details                   = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);

        $config['per_page'] = 50;
        $my_offset = 0;

        $uri_segment                        = $this->uri->segment(2);
        $application_status                 = $this->affiliation_model->get_admin_status();
        $status_name                        = $this->affiliation_model->get_all_status_name();       
        $affiliations                       = $this->affiliation_model->get_all_affiliations($config['per_page'], $my_offset);
        
        $this->data['page']                 = 'affiliate';
        $this->data['page_title']           = 'Affiliate Program';
        $this->data['security_details']     = $security_details;
        $this->data['uri_segment']          = $uri_segment;
        $this->data['application_status']   = $application_status;
        $this->data['status_name']          = $status_name;
        $this->data['affiliations']         = $affiliations;

        $this->render('manage_admin/affiliates/index');
    }

    public function view_details($sid = NULL) {
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {
            $redirect_url                           = 'manage_admin';
            $function_name                          = array('affiliate_request_view','referred_affiliate_view');
            $admin_id                               = $this->ion_auth->user()->row()->id;
            $security_details                       = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);

            $uri_segment                            = $this->uri->segment(2);
            $affiliate_reply                        = $this->affiliation_model->get_affiliate_reply($sid);
            $message_data                           = $this->affiliation_model->get_inbox_message_detail($sid);
            $notes                                  = $this->affiliation_model->get_demo_request_notes($sid);
            $scheduled_tasks                        = $this->affiliation_model->get_schedule_records($sid);

            $this->data['security_details']         = $security_details;
            $this->data['affiliate_reply']          = $affiliate_reply;
            $this->data['message']                  = $message_data[0];
            $this->data['notes']                    = $notes;
            $this->data['scheduled_tasks']          = $scheduled_tasks;
            $this->data['uri_segment']              = $uri_segment;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
            
            if ($this->form_validation->run() == false) {
                $is_read                            = 1;
                $status_arr                         = array('is_read' => $is_read);
                $this->affiliation_model->change_status($sid,$status_arr);
                $affiliation                        = $this->affiliation_model->get_affiliation($sid);

                if(empty($affiliation)) {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Application not found!');
                    redirect('manage_admin/'.$uri_segment, 'refresh');
                }
                
                
                $this->data['status']               = $affiliation[0]['contact_status'];
                $this->data['application_status']   = $this->affiliation_model->get_admin_status();
                $status_name                        = $this->affiliation_model->get_status_name($this->data['status']);
                
                if(!empty($status_name)) {
                    $status_name                    = $status_name['0']['name'];
                } else {
                    $status_name                    = 'No Status Found!';
                }   

                if ($affiliation[0]['is_reffered'] == 1) {
                    $referred                       = $this->affiliation_model->get_referred_user($sid);
                    $this->data['affiliation']      = $referred[0];
                    $this->data['page_title']       = 'Affiliate-Referral Detail';
                } else {
                    $this->data['affiliation']      = $affiliation[0];
                    $this->data['page_title']       = 'Affiliate Detail';
                }
                
                $this->data['status_name']          = $status_name;

                $this->render('manage_admin/affiliates/view_details_new');  
            } else {
                $perform_action                     = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_new_note':
                        $note_text                          = $this->input->post('note_text');
                        $affiliate_sid                      = $sid;
                        $data_to_insert                     = array();
                        $data_to_insert['affiliate_sid']    = $affiliate_sid;
                        $data_to_insert['created_by']       = $admin_id;
                        $data_to_insert['note_text']        = $note_text;
                        $this->affiliation_model->insert_affilate_note($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Saved!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $affiliate_sid, 'location');
                        break;
                    case 'edit_new_note':
                        $note_text                          = $this->input->post('note_text');
                        $affiliate_sid                      = $this->input->post('affiliate_sid');
                        $data_to_insert                     = array();
                        $data_to_insert['note_text'] = $note_text;
                        $this->affiliation_model->update_affilate_notes($affiliate_sid, $data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Updated!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $sid, 'location');
                        break;
                    case 'delete_note':
                        $affiliate_sid                      = $sid;
                        $note_sid                           = $this->input->post('note_sid');
                        $this->affiliation_model->delete_affilate_note($affiliate_sid, $note_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Note Deleted!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $affiliate_sid, 'location');
                        break;
                    case 'delete_demo_request':
                        $affiliate_sid                      = $sid;
                        $this->affiliation_model->delete_affilate_request($affiliate_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Demo Request Deleted!');
                        redirect('manage_admin/'.$uri_segment, 'location');
                        break;
                    case 'schedule_a_task':
                        $post_data                          = $this->input->post();
                        $affiliate_sid                      = $this->input->post('user_sid');

                        if (isset($post_data['perform_action'])) {
                            unset($post_data['perform_action']);
                        }

                        if (isset($post_data['schedule_datetime'])) {
                            $schedule_date                  = $post_data['schedule_datetime'];
                            $my_date                        = new DateTime($schedule_date);
                            $schedule_date                  = $my_date->format('Y-m-d H:i:s');
                            $post_data['schedule_datetime'] = $schedule_date;
                        }

                        $message = $message_data[0];
                        $post_data['user_type']         = 'affiliate_user';
                        $post_data['fdr_first_name']    = $message["first_name"];
                        $post_data['fdr_last_name']     = $message["last_name"];
                        $post_data['fdr_email']         = $message["email"];
                        $post_data['fdr_phone_number']  = $message["contact_number"];
                        $post_data['fdr_company_name']  = $message["company"];
                        $post_data['created_by']        = $admin_id;
                         $post_data['reminder_email_triggered_date'] = NULL;
                        
                        $this->affiliation_model->add_new_schedule_record($post_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Reminder Scheduled!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $affiliate_sid, 'location');
                        break;
                    case 'delete_schedule_record':
                        $schedule_sid                       = $this->input->post('schedule_sid');
                        $affiliate_sid                      = $this->input->post('user_sid');
                        $this->affiliation_model->delete_schedule_record($schedule_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Deleted!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $affiliate_sid, 'location');
                        break;
                    case 'complete_schedule_record':
                        $schedule_sid                       = $this->input->post('schedule_sid');
                        $affiliate_sid                      = $this->input->post('user_sid');
                        $this->affiliation_model->set_schedule_status($schedule_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Scheduled Task Completed!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/' . $affiliate_sid, 'location');
                        break;
                }
            }
        }    
    }
    
    public function edit_details($sid = NULL){
        if($sid == NULL){
            $this->session->set_flashdata('message', '<strong>Error</strong> Application not found!');
            redirect('manage_admin/affiliates', 'refresh');
        } else {

            $redirect_url                                       = 'manage_admin';
            $function_name                                      = array('affiliate_request_edit','referred_affiliate_edit');
            $uri_segment                                        = $this->uri->segment(2);
            $admin_id                                           = $this->ion_auth->user()->row()->id;
            $security_details                                   = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);
            
            $this->data['security_details']                     = $security_details;
            $this->data['uri_segment']                          = $uri_segment;
            $affiliation                                        = $this->affiliation_model->get_affiliation($sid);
            
            if(empty($affiliation)) {
                $this->session->set_flashdata('message', '<strong>Error</strong> Application not found!');
                redirect('manage_admin/affiliates', 'refresh');
            }
            
            $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
            $this->form_validation->set_rules('email', 'E-Mail', 'required|xss_clean|trim|valid_email');
            
            if ($this->form_validation->run() == false) {
                
                if ($affiliation[0]['is_reffered'] == 1) {
                    $this->data['page_title']                   = 'Referral Program';
                    $this->data['page_sub_title']               = 'Modify Referral Details';
                    $referred                                   = $this->affiliation_model->get_referred_user($sid);
                    $this->data['affiliation']                  = $referred[0];
                    $this->data['status']                       = $referred[0]['contact_status'];
                } else {
                    $this->data['page_title']                   = 'Affiliate Program';
                    $this->data['page_sub_title']               = 'Modify Details';
                    $this->data['affiliation']                  = $affiliation[0];
                    $this->data['status']                       = $affiliation[0]['contact_status'];
                }
                $this->render('manage_admin/affiliates/view_details');

            } else {
                $formpost                                       = $this->input->post(NULL, TRUE);
                // 
                if(isset($formpost['txt_phonenumber'])) { $formpost['contact_number'] = $formpost['txt_phonenumber']; unset($formpost['txt_phonenumber']); }
                else $formpost['contact_number'] = '';
                //
                $change_email                                   = $this->affiliation_model->get_referral_user_email($sid);

                if ($change_email[0]['email'] == $formpost['email']) {
                    $this->affiliation_model->update_details($sid, $formpost);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Details updates!');
                    redirect('manage_admin/'.$uri_segment.'/view_details/'.$sid, 'refresh');
                } else {
                    $already_referred                           = $this->affiliation_model->check_referral_user($formpost['email']);
                    
                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Email Before!');
                        redirect('manage_admin/'.$uri_segment.'/view_details/'.$sid, 'refresh');
                    }

                    // Added on: 25-06-2019
                    $new_timezone = $this->input->post('timezone', true);
                    if($new_timezone != '') $formpost['timezone'] = $new_timezone;

                    $this->affiliation_model->update_details($sid, $formpost);
                    $this->session->set_flashdata('message', '<strong>Success</strong> Details updates!');
                    redirect('manage_admin/'.$uri_segment.'/view_details/'.$sid, 'refresh');
                    
                }
                
            }
        }    
    }

    public function accept_reject(){ 
        $admin_id                                                   = $this->ion_auth->user()->row()->id;
        $id                                                         = $this->input->post('id');
        $affiliation                                                = $this->affiliation_model->get_affiliation($id);
        $created_by                                                 = $admin_id;
        $created_date                                               = date('Y-m-d H:i:s');
        $data_to_insert                                             = array();
        $data_to_insert['contact_number']                           = $affiliation[0]['contact_number'];
        $data_to_insert['email']                                    = $affiliation[0]['email'];
        $data_to_insert['initial_commission_value']                 = 0;
        $data_to_insert['initial_commission_type']                  = 'percentage';
        $data_to_insert['recurring_commission_value']               = 0;
        $data_to_insert['recurring_commission_type']                = 'percentage';
        $data_to_insert['status']                                   = 1;
        $data_to_insert['created_by']                               = $created_by;
        $data_to_insert['created_date']                             = $created_date;
        $data_to_insert['affiliation_sid']                          = $id;
        $data_to_insert['paypal_email']                             = $affiliation[0]['paypal_email'];
        $data_to_insert['company_name']                             = $affiliation[0]['company'];
        $data_to_insert['zip_code']                                 = $affiliation[0]['zip_code'];
        $data_to_insert['method_of_promotion']                      = $affiliation[0]['method_of_promotion'];
        $data_to_insert['website']                                  = $affiliation[0]['website'];
        $data_to_insert['list_of_emails']                           = $affiliation[0]['email_list'];
        $data_to_insert['notes']                                    = $affiliation[0]['special_notes'];
        $data_to_insert['full_name']                                = ucwords($affiliation[0]['first_name'] . ' ' . $affiliation[0]['last_name']);
        $data_to_insert['address']                                  = $affiliation[0]['street'] . ', ' . $affiliation[0]['city'] . ', ' . $affiliation[0]['state'] . ', ' . $affiliation[0]['country'];
        $check_existence                                            = $this->affiliation_model->check_marketing_agency($affiliation[0]['email']);
        
        if($check_existence>0){
            $status_arr                                             = array('status' => 3, 'is_read' => 1);
            $this->affiliation_model->change_status($id,$status_arr);
            $this->session->set_flashdata('message', '<strong>Warning:</strong> Already Exist!');
            echo 'exist';
        } else{
            $status                                                 = $this->input->post('status');
            $is_read                                                = 1;
            $status_arr                                             = array('status' => $status, 'is_read' => $is_read);
            $this->affiliation_model->change_status($id,$status_arr);
            
            if($status == 1){
                $id                                                 = $this->affiliation_model->insert_marketing_agency($data_to_insert);
                if($affiliation[0]['w9_form'] != NULL && !empty($affiliation[0]['w9_form'])){
                    $aws_document_name                              = $affiliation[0]['w9_form'];
                    $document_insert_data                           = array();
                    $document_insert_data['marketing_agency_sid']   = $id;
                    $document_insert_data['document_name']          = 'W9 Form';
                    $document_insert_data['aws_document_name']      = $aws_document_name;
                    $this->load->model('manage_admin/marketing_agencies_model');
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
                }
                if($affiliation[0]['w8_form'] != NULL && !empty($affiliation[0]['w8_form'])){
                    $aws_document_name = $affiliation[0]['w8_form'];
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid']   = $id;
                    $document_insert_data['document_name']          = 'W8 Form';
                    $document_insert_data['aws_document_name']      = $aws_document_name;
                    $this->load->model('manage_admin/marketing_agencies_model');
                    $this->marketing_agencies_model->insert_marketing_document($document_insert_data);
                }
                echo $id;
            }
        }
    }
    
    public function ajax_handler() {
        $data['session'] = $this->session->userdata('logged_in');

        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
            $status                                                 = $_REQUEST['status'];
            $message_sid                                            = $_REQUEST['message_sid'];
            $this->affiliation_model->update_contact_status($message_sid, $status);
            echo 'success';
            exit;
        }
    }

    public function send_reply($sid = NULL) {
        if ($sid != NULL) {
            $uri_segment                                            = $this->uri->segment(2);
            $message_data                                           = $this->affiliation_model->get_affiliate_details($sid);
            $redirect_url                                       = 'manage_admin';
            $function_name                                      = array('affiliate_request_send_reply','referred_affiliate_send_reply');
            $admin_id                                           = $this->ion_auth->user()->row()->id;
            $security_details                                   = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);
            if (!empty($message_data)) {
                $data                                               = $message_data[0];
                $enquirer_email                                     = $data['email'];
                $enquirer_name                                      = $data['first_name'] . ' ' . $data['last_name'];
                
                $this->data['sid']                                  = $sid;
                $this->data['enquirer_email']                       = $enquirer_email;
                $this->load->library('form_validation');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required');

                $affiliation  = $this->affiliation_model->get_affiliation($sid);
                if ($this->form_validation->run() === FALSE) {
                    
                    if ($affiliation[0]['is_reffered'] == 1) {
                        $enquirer_subject                           = 'RE: Referred message';
                        $this->data['page_title']                   = 'Referral Reply for '.$enquirer_name;
                        $this->data['enquirer_subject']             = $enquirer_subject;
                    } else {
                        $enquirer_subject                           = 'RE: Affiliate message';
                        $this->data['page_title']                   = 'Affiliate Reply for '.$enquirer_name;
                        $this->data['enquirer_subject']             = $enquirer_subject;
                    }
                    $this->data['uri_segment']                      = $uri_segment;
                    $this->data['admin_templates'] = $this->affiliation_model->fetch_admin_templates();

                    $this->render('manage_admin/affiliates/send_mail');
                } else {
                    $formpost = $this->input->post(NULL, TRUE);
                    // Added on 30-04-2019
                    replace_magic_quotes(
                        $formpost['subject'],
                        array(
                            '{{first_name}}' => $affiliation[0]['first_name'],
                            '{{firstname}}' => $affiliation[0]['first_name'],
                            '{{first-name}}' => $affiliation[0]['first_name'],
                            '{{last-name}}' => $affiliation[0]['last_name'],
                            '{{lasname}}' => $affiliation[0]['last_name'],
                            '{{last_name}}' => $affiliation[0]['last_name'],
                            '{{email}}' => $affiliation[0]['email'],
                            '{{phone}}'  => $affiliation[0]['contact_number'],
                            '{{company_name}}'  => $affiliation[0]['company'],
                            '{{username}}'  => ''
                        )
                    );
                    replace_magic_quotes(
                        $formpost['message'],
                        array(
                            '{{first_name}}' => $affiliation[0]['first_name'],
                            '{{firstname}}' => $affiliation[0]['first_name'],
                            '{{first-name}}' => $affiliation[0]['first_name'],
                            '{{last-name}}' => $affiliation[0]['last_name'],
                            '{{las-name}}' => $affiliation[0]['last_name'],
                            '{{last_name}}' => $affiliation[0]['last_name'],
                            '{{email}}' => $affiliation[0]['email'],
                            '{{company_name}}'  => $affiliation[0]['company'],
                            '{{phone}}'  => $affiliation[0]['contact_number'],
                            '{{username}}'  => '',
                            '{{password}}'  => '',
                            '{{create_password_link}}'  => '',
                            '{{site_url}}'  => '',
                            '{{affiliate_name}}'  => $affiliation[0]['first_name'] . ' ' . $affiliation[0]['last_name'],
                        )
                    );

                    $to                                             = $formpost['email'];
                    $subject                                        = $formpost['subject'];
                    $message                                        = $formpost['message'];
                    $date                                           = date('Y-m-d H:i:s');
                    $admin                                          = 'admin';

                    if (empty($enquirer_name)) {
                        $enquirer_name                              = $to;
                    }


                    $reply_data                                     = $formpost;
                    $reply_data['reply_date']                       = date('Y-m-d H:i:s');
                    $reply_data['affiliation_sid']                  = $sid;
                    $from                                           = FROM_EMAIL_STEVEN;
                    $body                                           = EMAIL_HEADER
                            . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $enquirer_name . ',</h2>'
                            . '<br><br>' 
                            . $message
                            . '<br><br>'
                            . EMAIL_FOOTER;

                    sendMail($from, $to, $subject, $body, FROM_STORE_NAME);


                    $formpost['message']                            = $body;
                    $this->affiliation_model->save_email_log($formpost);
                    $this->affiliation_model->save_affiliates_reply($reply_data);

                    if ($affiliation[0]['is_reffered'] == 1) {
                        $this->session->set_flashdata('message', '<b>Success!</b> E-Mail is sent to referred.');
                    } else {
                        $this->session->set_flashdata('message', '<b>Success!</b> E-Mail is sent to affiliate.');;
                    }
                    
                    redirect('manage_admin/'.$uri_segment.'/view_details/' . $sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error!</b> Affiliate not found!');
                redirect('manage_admin/'.$uri_segment, 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error!</b> Affiliate not found!');
            redirect('manage_admin/'.$uri_segment, 'refresh');
        }
    }

    public function view_reply($sid = NULL) {
        if ($sid == NULL || $sid <= 0) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
            redirect('manage_admin/referred_clients', 'refresh');
        } else {
            $redirect_url                      = 'manage_admin/referred_affiliates';
            $function_name                     = 'view_reply';
            $admin_id                          = $this->ion_auth->user()->row()->id;
            $security_details                  = db_get_admin_access_level_details($admin_id);
            check_access_permissions($security_details, $redirect_url, $function_name);
          
            $uri_segment                       = $this->uri->segment(2);
            $email_reply                       = $this->affiliation_model->get_reply_by_id($sid);
            $employeId                         = $email_reply[0]['affiliation_sid'];
            $affiliation                       = $this->affiliation_model->get_affiliation($employeId);

            $this->data['reply']               = $email_reply;
            $this->data['uri_segment']         = $uri_segment;

            if ($affiliation[0]['is_reffered'] == 1) {
                $this->data['page']            = 'View Referral Reply';
            } else {
                $this->data['page']            = 'View Affiliate Reply';
            }


            $this->render('manage_admin/affiliates/affiliates_details_view');
        }    
        
    }

    /*************Referred*************/ 

    public function referred(){
        
        $redirect_url                       = 'manage_admin';
        $function_name                      = 'referred_affiliate';
        $admin_id                           = $this->ion_auth->user()->row()->id;
        $security_details                   = db_get_admin_access_level_details($admin_id);
        check_access_permissions($security_details, $redirect_url, $function_name);
        
        $config['per_page']                 = 50;
        $my_offset                          = 0;

        $uri_segment                        = $this->uri->segment(2);
        $status_name                        = $this->affiliation_model->get_all_status_name(); 
        $application_status                 = $this->affiliation_model->get_admin_status();
        $affiliations                       = $this->affiliation_model->get_all_referred_affiliates($config['per_page'], $my_offset);

        $this->data['page']                 = 'referred';
        $this->data['page_title']           = 'Affiliate-Referral Program';
        $this->data['security_details']     = $security_details;
        $this->data['uri_segment']          = $uri_segment;
        $this->data['status_name']          = $status_name;
        $this->data['application_status']   = $application_status;
        $this->data['affiliations']         = $affiliations;

        $this->render('manage_admin/affiliates/index');
    }
   // Delet Affiliate
    public function delete_affiliate(){ 
        $id  = $this->input->post('id');
       $this->affiliation_model->delete_affiliate($id);
    }


}