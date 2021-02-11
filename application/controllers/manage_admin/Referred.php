<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Referred extends Admin_Controller {
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
        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
//        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Referral Program';
        $config['per_page'] = 50;
        $my_offset = 0;
        $this->data['referred'] = $this->referred_model->get_all_referred_affiliates($config['per_page'], $my_offset);
        $this->data['page'] = 'referred';
        $status_name = $this->referred_model->get_all_status_name(); 
        // die('adda') ;     
        $this->data['status_name'] = $status_name;
        $this->data['application_status'] = $this->referred_model->get_status();
        $this->render('manage_admin/referred/index');
    }

    public function view_details($sid = NULL){
        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';

        $uri_segment = $this->uri->segment(2);

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
        if ($this->form_validation->run() == false) {
            if ($sid !== NULL && $sid > 0) {
                $admin_id = $this->ion_auth->user()->row()->id;
                $security_details = db_get_admin_access_level_details($admin_id);
                $this->data['security_details'] = $security_details;
                $this->data['page_title'] = 'Referral Program';
                //check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
                $is_read = 1;
                $status_arr = array('is_read' => $is_read);
                $this->referred_model->change_status($sid,$status_arr);

                if ($uri_segment == 'referred_affiliates') {
                    $referred = $this->referred_model->get_referred_user($sid);
                    $message_data = $this->referred_model->get_inbox_message_user_detail($sid);
                    $referral_reply = $this->referred_model->get_referral_reply($sid);
                    $notes = $this->referred_model->get_demo_request_notes($sid);
                } elseif ($uri_segment == 'referred_clients') {
                    $referred = $this->referred_model->get_referred_client($sid);
                    $message_data = $this->referred_model->get_inbox_message_client_detail($sid);
                    $referral_reply = $this->referred_model->get_referral_client_reply($sid);
                    $notes = $this->referred_model->get_refer_request_notes($sid);
                }

                $this->data['referred'] = $referred[0];
                $this->data['message'] = $message_data[0];
                $this->data['referral_reply'] = $referral_reply;
                $this->data['notes'] = $notes;
                
                if(empty($referred)) {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
                    redirect('manage_admin/referred', 'refresh');
                }
                
                $this->data['status'] = $referred[0]['contact_status'];
                $this->data['application_status'] = $this->referred_model->get_admin_status();
                $status_name = $this->referred_model->get_status_name($this->data['status']);
                
                if(!empty($status_name)) {
                    
                    $status_name = $status_name['0']['name'];
                } else {
                    
                    $status_name = 'No Status Found!';
                }
                
                $this->data['status_name'] = $status_name;
                $this->render('manage_admin/referred/view_details_new');
            } else {
                $this->session->set_flashdata('message', '<b>Failed: </b>Sorry enquiry does not exists!');
                redirect('manage_admin/referred', 'refresh');
            }
        } else {
            $perform_action = $this->input->post('perform_action');
            $redirect_url_segment = $this->uri->segment(2);

            switch ($perform_action) {
                case 'add_new_note':
                    $admin_id = $this->ion_auth->user()->row()->id;
                    $note_text = $this->input->post('note_text');
                    $referral_user_sid = $sid;
                    $data_to_insert = array();
                    
                    $data_to_insert['created_by'] = $admin_id;
                    $data_to_insert['note_text'] = $note_text;

                    if ($uri_segment == 'referred_affiliates') {
                        $data_to_insert['affiliate_sid'] = $referral_user_sid;
                        $this->referred_model->insert_referral_note($data_to_insert);
                    } elseif ($uri_segment == 'referred_clients') {
                        $data_to_insert['referred_sid'] = $referral_user_sid;
                        $this->referred_model->insert_referral_client_note($data_to_insert);
                    }

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Note Saved!');
                    redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $referral_user_sid, 'location');
                    break;
                case 'edit_new_note':
                    $note_text = $this->input->post('note_text');
                    $referral_user_sid = $this->input->post('affiliate_sid');
                    $data_to_insert = array();
                    $data_to_insert['note_text'] = $note_text;

                    if ($uri_segment == 'referred_affiliates') {
                        $this->referred_model->update_referral_notes($referral_user_sid, $data_to_insert);
                    } elseif ($uri_segment == 'referred_clients') {
                        $this->referred_model->update_referral_client_notes($referral_user_sid, $data_to_insert);
                    }

                    $this->session->set_flashdata('message', '<strong>Success:</strong> Note Updated!');
                    redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $sid, 'location');
                    break;
                case 'delete_note':
                    $referral_user_sid = $sid;
                    $note_sid = $this->input->post('note_sid');

                    if ($uri_segment == 'referred_affiliates') {
                        $this->referred_model->delete_referral_note($referral_user_sid, $note_sid);
                    } elseif ($uri_segment == 'referred_clients') {
                        $this->referred_model->delete_referral_client_note($referral_user_sid, $note_sid);
                    }

                    
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Note Deleted!');
                    redirect('manage_admin/'.$redirect_url_segment.'/view_details/' . $referral_user_sid, 'location');
                    break;
            }
        }
    }

    public function edit_details($sid = NULL){

        $redirect_url       = 'manage_admin';
        $function_name      = 'incident_reporting';
 
        if($sid == NULL){
            $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
            redirect('manage_admin/referred', 'refresh');
        }
        
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Referral Program';
        $referred = $this->referred_model->get_referred_user_detail($sid);
        $this->data['countries'] = $this->referred_model->get_all_countries();
        
        if(empty($referred)) {
            $this->session->set_flashdata('message', '<strong>Error</strong> Referral Person not found!');
            redirect('manage_admin/referred', 'refresh');
        }
        
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'E-Mail', 'required|xss_clean|trim|valid_email');
        
        if ($this->form_validation->run() == false) {
            $this->data['referred'] = $referred[0];
            $this->data['status'] = $referred[0]['contact_status'];
            $this->render('manage_admin/referred/edit_referred');
        } else {

            $formpost = $this->input->post(NULL, TRUE);

            $change_email = $this->referred_model->get_referral_user_email($sid);
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

    public function accept_reject(){
        $admin_id = $this->ion_auth->user()->row()->id;
        $id = $this->input->post('id');
        $referred = $this->referred_model->get_referred_user_detail($id);
        $created_by = $admin_id;
        $created_date = date('Y-m-d H:i:s');
        $data_to_insert = array();
        $data_to_insert['full_name'] = ucwords($referred[0]['first_name'] . ' ' . $referred[0]['last_name']);
        $data_to_insert['contact_number'] = $referred[0]['contact_number'];
        $data_to_insert['address'] = $referred[0]['street'] . ', ' . $referred[0]['city'] . ', ' . $referred[0]['state'] . ', ' . $referred[0]['country'];
        $data_to_insert['email'] = $referred[0]['email'];
        $data_to_insert['initial_commission_value'] = 0;
        $data_to_insert['initial_commission_type'] = 'percentage';
        $data_to_insert['recurring_commission_value'] = 0;
        $data_to_insert['recurring_commission_type'] = 'percentage';
        $data_to_insert['status'] = 1;
        $data_to_insert['created_by'] = $created_by;
        $data_to_insert['created_date'] = $created_date;
        $data_to_insert['referred_sid'] = $id;
        $check_existence = $this->referred_model->check_marketing_agency($referred[0]['email']);
        
        if($check_existence>0){
            $status_arr = array('status' => 3, 'is_read' => 1);
            $this->referred_model->change_status($id,$status_arr);
            $this->session->set_flashdata('message', '<strong>Warning:</strong> Already Exist!');
            echo 'exist';
        } else{
            $status = $this->input->post('status');
            $is_read = 1;
            $status_arr = array('status' => $status, 'is_read' => $is_read);
            $this->referred_model->change_status($id,$status_arr);
            
            if($status == 1){
                $id = $this->referred_model->insert_marketing_agency($data_to_insert);
                echo $id;
            }
        }
    }

    public function ajax_handler() {
        $data['session'] = $this->session->userdata('logged_in');
        if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'ajax_update_status') {
//            $sid = $_REQUEST['id'];
            $status = $_REQUEST['status'];
            $message_sid = $_REQUEST['message_sid'];
            $this->referred_model->update_contact_status($message_sid, $status);
            echo 'success';
            exit;
        }
    }

    public function send_reply($sid = NULL) {
        if ($sid != NULL) {
            $message_data = $this->referred_model->get_referred_detail($sid);
            
            if (!empty($message_data)) {
                $data = $message_data[0];
                $enquirer_email = $data['email'];
                $enquirer_subject = 'RE: Referred message';
                $enquirer_name = $data['first_name'] . ' ' . $data['last_name'];
                $this->data['page_title'] = 'Referred Reply for '.$enquirer_name;
                $this->data['sid'] = $sid;
                $this->data['enquirer_email'] = $enquirer_email;
                $this->data['enquirer_subject'] = $enquirer_subject;
                $this->load->library('form_validation');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
                $this->form_validation->set_rules('message', 'Message', 'trim|required');

                if ($this->form_validation->run() === FALSE) {
                    $this->render('manage_admin/referred/send_mail');
                } else {
                    $formpost = $this->input->post(NULL, TRUE);
                    
                    $to = $formpost['email'];
                    $subject = $formpost['subject'];
                    $message = $formpost['message'];
                    $date = date('Y-m-d H:i:s');
                    $admin = 'admin';

                    if (empty($enquirer_name)) {
                        $enquirer_name = $to;
                    }

                    $reply_data = $formpost;
                    $reply_data['reply_date'] = date('Y-m-d H:i:s');
                    $reply_data['referred_sid'] = $sid;
                    $from = FROM_EMAIL_STEVEN;
                    $body = EMAIL_HEADER
                            . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $enquirer_name . ',</h2>'
                            . '<br><br>' 
                            . $message
                            . '<br><br>'
                            . EMAIL_FOOTER;

                    sendMail($from, $to, $subject, $body, FROM_STORE_NAME);
                   
                    $formpost['message'] = $body;
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
        $admin_id = $this->ion_auth->user()->row()->id;
        $this->data['reply'] = $this->referred_model->get_reply_by_id($sid);
        $this->data['page'] = 'View Referral Reply';
        $this->render('manage_admin/referred/referred_details_view');
    }


}