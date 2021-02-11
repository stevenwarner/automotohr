<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Marketing_agency_documents extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/marketing_agency_documents_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($marketing_agency_sid = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'admin_forms_and_documents';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $companies_documents = $this->marketing_agency_documents_model->get_all_companies_and_documents($marketing_agency_sid);
        $this->data['company_sid'] = $marketing_agency_sid;
        $companies_uploaded_documents = $this->marketing_agency_documents_model->get_all_forms_documents_uploaded($marketing_agency_sid);
        foreach($companies_uploaded_documents as $key => $document){
            if(empty($document['verification_key']) || $document['verification_key'] == NULL){
                $verification_key = random_key(80);
                $update_data = array('verification_key' => $verification_key);
                $this->marketing_agency_documents_model->active_deactive_status($document['sid'],$update_data);
                $companies_uploaded_documents[$key]['verification_key'] = $verification_key;
            }
        }
        $form_w9_status = $this->marketing_agency_documents_model->get_form_w9_status($marketing_agency_sid);
        
        if (empty($form_w9_status)) {
            $this->data['form_w9_status'] = 'not sent';
        } else {
            $this->data['form_w9_status'] = $form_w9_status;
            $form_w9_data = $this->marketing_agency_documents_model->get_form_w9_data($marketing_agency_sid);
            $this->data['pre_form'] = $form_w9_data;
        }

        $this->data['companies_uploaded_documents'] = $companies_uploaded_documents;
        $this->data['marketing_agency_sid'] = $marketing_agency_sid;
        $this->form_validation->set_rules('marketing_agency_sid', 'marketing agency sid', 'required');

        $this->data['flag'] = true;
        if ($this->form_validation->run() == false) {
            $this->data['company_documents'] = $companies_documents;
            $this->render('manage_admin/marketing_agency_documents/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            $marketing_agency_sid = $this->input->post('marketing_agency_sid');
            $form_name = $this->input->post('form_to_send');

            switch ($perform_action) {
                case 'generate_form':
                    if ($form_name == 'eula') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_affiliate_document_record($marketing_agency_sid, $verification_key, 'generated');
                    }

                    redirect('manage_admin/marketing_agency_documents/' . $marketing_agency_sid, 'refresh');
                    break;
                case 'send_form':
                    $verification_key = $this->input->post('verification_key');

                    if ($form_name == 'eula') { //Send Emails
                        $link = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" href="' . base_url('form_affiliate_end_user_license_agreement' . '/' . $verification_key) . '" target="_blank">Affiliate End User License Agreement</a>';

                        $marketing_agency_name = $companies_documents[0]['full_name'];
                        $marketing_agency_email = $companies_documents[0]['email'];
                        $replacement_array = array();
                        $replacement_array['affiliate_name'] = $marketing_agency_name;
                        $replacement_array['links'] = $link;

                        log_and_send_templated_email(AFFILIATE_END_USER_LICENSE_NOTIFICATION, $marketing_agency_email, $replacement_array);
                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfully Forwarded.');
                        redirect('manage_admin/marketing_agency_documents/' . $marketing_agency_sid, 'refresh');
                    }
                    break;
                case 'upload_document':
                    $verification_key = random_key(80);
                    $document_name = $_FILES['documents_and_forms']['name'];
                    $aws_document_name = upload_file_to_aws('documents_and_forms', $marketing_agency_sid, $companies_documents[0]['full_name'], 'by_admin_' . date('Ymd'));
//                    $aws_document_name = 'aws_document_name';
                    $document_insert_data = array();
                    $document_insert_data['marketing_agency_sid'] = $marketing_agency_sid;
                    $document_insert_data['document_name'] = $document_name;
                    $document_insert_data['aws_document_name'] = $aws_document_name;
                    $document_insert_data['insert_date'] = date('Y-m-d H:i:s');
                    $document_insert_data['verification_key'] = $verification_key;
                    $this->marketing_agency_documents_model->insert_marketing_document($document_insert_data);

                    $this->session->set_flashdata('message', '<strong>Success</strong> : Document Uploaded Successfully!');
                    redirect('manage_admin/marketing_agency_documents/' . $marketing_agency_sid, 'refresh');
                    break;
            }
        }
    }

    public function send($marketing_agency_sid = null) {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($marketing_agency_sid != null) {
            $companies_documents = $this->marketing_agency_documents_model->get_all_companies_and_documents($marketing_agency_sid);
            $this->data['marketing_agency_sid'] = $marketing_agency_sid;
            $this->form_validation->set_rules('perform_action', 'perform action', 'required|xss_clean|trim');

            if ($this->form_validation->run() == false) {
                $this->data['marketing_agency_documents'] = $companies_documents;
                $this->render('manage_admin/marketing_agency_documents/send_documents', 'admin_master');
            } else {
                $perform_action = $this->input->post('perform_action');
                $eula = $this->input->post('eula');
                $uploaded_documents = $this->input->post('documents');
                $link_eula = '';

                if (strlen($eula) > 1) {
                    $link_eula = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url('form_affiliate_end_user_license_agreement/' . $eula) . '">Affiliate End User License Agreement</a>';

                    $this->marketing_agency_documents_model->update_document_record('form_affiliate_end_user_license_agreement', $eula, array(), 'sent');

                    $this->marketing_agency_documents_model->insert_document_ip_tracking_record($marketing_agency_sid, 0, getUserIP(), 'form_affiliate_end_user_license_agreement', 'sent', $_SERVER['HTTP_USER_AGENT']);

                }

                $documents_links = '';

                if (!empty($uploaded_documents)) {
                    foreach ($uploaded_documents as $uploaded_document) {
                        if (strlen($uploaded_document) > 1) {
                            $document_detail = $this->marketing_agency_documents_model->get_document_record('uploaded_document', $uploaded_document);
                            $temp_link = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" target="_blank" href="' . base_url('form_company_agreements/market_agency_documents/' . $uploaded_document) . '">' . $document_detail['document_name'] . '</a>';
                            $this->marketing_agency_documents_model->update_document_status('uploaded_document', $uploaded_document, 'sent');
                            $documents_links .= '<br />';
                            $documents_links .= $temp_link;
                            $documents_links .= '<br />';
                        }
                    }
                }

                $links = '';

                if ($link_eula != '') {
                    $links .= '<br />';
                    $links .= $link_eula;
                    $links .= '<br />';
                }

                if ($documents_links != '') {
                    $links .= $documents_links;
                }

                switch (strtolower($perform_action)) {
                    case 'send_to_single_email':
                        $marketing_agency_email = $this->input->post('marketing_agency_email');
                        $marketing_agency_name = $this->input->post('marketing_agency_name');
                        $replacement_array = array();
                        $replacement_array['affiliate_name'] = $marketing_agency_name;
                        $replacement_array['links'] = $links;
                        log_and_send_templated_email(AFFILIATE_END_USER_LICENSE_NOTIFICATION, $marketing_agency_email, $replacement_array);
                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfully Forwarded.');
                        redirect('manage_admin/marketing_agencies/edit_marketing_agency/' . $marketing_agency_sid, 'refresh');
                        break;
                    case 'send_to_market_agency':
                        $marketing_agency_name = $this->input->post('marketing_agency_name');
                        $marketing_agency_email = $this->input->post('marketing_agency_email');
                        $replacement_array = array();
                        $replacement_array['affiliate_name'] = $marketing_agency_name;
                        $replacement_array['links'] = $links;
                        log_and_send_templated_email(AFFILIATE_END_USER_LICENSE_NOTIFICATION, $marketing_agency_email, $replacement_array);
                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfully Forwarded.');
                        redirect('manage_admin/marketing_agencies/edit_marketing_agency/' . $marketing_agency_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('manage_admin/marketing_agencies/edit_marketing_agency/'.$marketing_agency_sid);
        }
    }

    public function check_signed_forms() {
        $companies_documents = $this->marketing_agency_documents_model->get_cc_auth();

        foreach ($companies_documents as $key => $value) {
            $sid = $value['sid'];
            $status = $value['status'];
            $processed = $value['processed'];

            if ($processed == 0 && $status = 'signed') {
                echo '<br>SID: ' . $sid;
                $cc_type = encode_string($value['cc_type']);
                $cc_holder_name = encode_string($value['cc_holder_name']);
                $cc_number = encode_string($value['cc_number']);
                $cc_expiration_month = encode_string($value['cc_expiration_month']);
                $cc_expiration_year = encode_string($value['cc_expiration_year']);
                $this->marketing_agency_documents_model->update_database($sid, $cc_type, $cc_holder_name, $cc_number, $cc_expiration_month, $cc_expiration_year);
            } else {
                echo 'Nothing to process';
            }
        }
    }

    public function ajax_responder(){
        $sid = $this->input->post('sid');
        $status = $this->input->post('status');
        $update_data = array('active_status' => $status);
        $this->marketing_agency_documents_model->active_deactive_status($sid,$update_data);
        echo 'updated';
    }

    public function assign_w9_form ($marketing_agency_sid = null) {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($marketing_agency_sid != null) {
            $marketing_agency_data = $this->marketing_agency_documents_model->get_marketing_agency_data($marketing_agency_sid);
            $parent_sid = $marketing_agency_data['parent_sid'];
            $verification_key = random_key(80);
            
            $already_assigned_w9 = $this->marketing_agency_documents_model->check_w9_form_exist($marketing_agency_sid);

            if (empty($already_assigned_w9)) {
                $w9_data_to_insert = array();
                $w9_data_to_insert['affiliate_sid'] = $marketing_agency_sid;
                $w9_data_to_insert['parent_sid'] = $parent_sid;
                $w9_data_to_insert['w9_form_status'] = 'sent';
                $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                $this->marketing_agency_documents_model->insert_w9_form_record($w9_data_to_insert);
                
                $emailTemplateBody = 'Dear ' . $marketing_agency_data["full_name"] . ', <br>';
                $emailTemplateBody = $emailTemplateBody . 'AutomotoHR has assigned the following document to you:' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<b>Form W9 Fillable</b>' . '<br>';
                if (empty($marketing_agency_data["username"])) {
                    $this->marketing_agency_documents_model->set_verification_key($marketing_agency_sid, $verification_key);
                    $url = base_url() . 'affiliate_portal/documents/form_w9/' . $verification_key;
                    $emailTemplateBody = $emailTemplateBody . 'You can fill your W9 Form by following the link below : ' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '<a href="'.$url.'">Fill W9 Form</a><br><br>';
                } else {
                    $emailTemplateBody = $emailTemplateBody . 'Please login to your Affiliate Account and fill up the assigned document' . '<br>';
                    $emailTemplateBody = $emailTemplateBody . '<a href="https://www.automotohr.com/affiliate_portal/login">https://www.automotohr.com/affiliate_portal/login</a><br><br>';
                }
                
                $from = TO_EMAIL_DEV; 
                $to = $marketing_agency_data["email"];
                $subject = 'Form W9 Fillable';
                $from_name = ucwords(STORE_DOMAIN);
                
                $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;
                sendMail($from, $to, $subject, $body, $from_name);

                $emailData = array(
                                    'date' => date('Y-m-d H:i:s'),
                                    'subject' => $subject,
                                    'email' => $to,
                                    'message' => $body,
                                    'username' => $marketing_agency_data['sid'],
                                );
                $this->marketing_agency_documents_model->save_email_logs($emailData);
                $this->session->set_flashdata('message', '<b>Success:</b> Form W9 Successfully Assigned.');  

            } else {
                $this->session->set_flashdata('message', '<b>Warning:</b> Form W9 Already Assigned.'); 
            }
            
            redirect('manage_admin/marketing_agency_documents/'.$marketing_agency_sid);
        } else {
            redirect('manage_admin/marketing_agencies');
        }
    }

    public function print_download_w9_form ($document_sid = null, $action) {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($document_sid != null) {
            $form_w9 = $this->marketing_agency_documents_model->get_w9_form_data($document_sid);
            $data['pre_form'] = $form_w9;
            $data['action'] = $action;
            $this->load->view('manage_admin/marketing_agency_documents/w9_pdf_view',$data);
        } else {
            redirect('manage_admin/marketing_agencies');
        }
    }


    function download($filename){
        downloadAWSFileToBrowser($filename);
    }
}