<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Documents extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/documents_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($company_sid = null, $company_name = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'admin_forms_and_documents';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $company_name = $company_name == null ? 'all' : urldecode($company_name);
        if ($company_sid == null) {
            $companies_documents = $this->documents_model->get_all_companies_and_documents(0, $company_name);
        } else {
            $companies_documents = $this->documents_model->get_all_companies_and_documents($company_sid, $company_name);
            $this->data['company_sid'] = $company_sid;
            $companies_uploaded_documents = $this->documents_model->get_all_forms_documents_uploaded($company_sid);
            $this->data['companies_uploaded_documents'] = $companies_uploaded_documents;
        
        }
        // echo '<pre>'; print_r($companies_documents); exit;
        $this->form_validation->set_rules('company_sid', 'company sid', 'required');
        $this->data['flag'] = true;
        
        if ($this->form_validation->run() == false) {
            $this->data['companies_documents'] = $companies_documents;
            $this->render('manage_admin/documents/index', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            $company_sid = $this->input->post('company_sid');
            $company_name = $this->input->post('company_name');
            $form_name = $this->input->post('form_to_send');
            $company_admin_email = $this->input->post('company_admin_email');
            $company_admin_full_name = $this->input->post('company_admin_full_name');


            switch ($perform_action) {
                
                case 'generate_form':
                    if ($form_name == 'eula') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_document_record('end_user_license_agreement', $company_sid, $verification_key, 'generated');
                    } elseif ($form_name == 'credit_card_authorization') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_document_record('credit_card_authorization_form', $company_sid, $verification_key, 'generated');
                    } elseif ($form_name == 'company_contacts') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_document_record('company_contacts', $company_sid, $verification_key, 'generated');
                    }elseif ($form_name == 'fpa') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_document_record('payroll_agreement', $company_sid, $verification_key, 'generated');
                    } elseif ($form_name == 'payroll_cc_auth') {
                        $verification_key = random_key(80);
                        $this->documents_model->insert_document_record('payroll_credit_card_authorization_form', $company_sid, $verification_key, 'generated');
                    }

                    redirect('manage_admin/documents/' . $company_sid, 'refresh');
                    break;
                case 'send_form':
                    $verification_key = $this->input->post('verification_key');

                    if ($form_name == 'eula') { //Send Emails
                        $link = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" href="' . base_url('form_end_user_license_agreement' . '/' . $verification_key) . '" target="_blank">End User License Agreement</a>';
                        $replacement_array = array();
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['company_admin_email'] = ucwords($company_admin_email);
                        $replacement_array['form_link'] = $link;
                        log_and_send_templated_email(END_USER_LICENSE_AGREEMENT_NOTIFICATION_SUPER_ADMIN, TO_EMAIL_DEV, $replacement_array);
                        $replacement_array = array();
                        $replacement_array['company_admin'] = ucwords($company_admin_full_name);
                        $replacement_array['form_link'] = $link;
                        log_and_send_templated_email(END_USER_LICENSE_AGREEMENT_NOTIFICATION_COMPANY_ADMIN, $company_admin_email, $replacement_array);
                        $this->documents_model->update_document_status('end_user_license_agreement', $verification_key, 'sent');
                        $this->session->set_flashdata('message', '<strong>Success</strong> : End User License Agreement Sent!');
                        //redirect('manage_admin/documents/' . $company_sid , 'refresh');
                    } elseif ($form_name == 'credit_card_authorization') { //Send Emails
                        $link = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . base_url('form_credit_card_authorization' . '/' . $verification_key) . '" target="_blank">Credit Card Authorization</a>';
                        $replacement_array = array();
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['company_admin_email'] = ucwords($company_admin_email);
                        $replacement_array['form_link'] = $link;
                        log_and_send_templated_email(CREDIT_CARD_AUTHORIZATION_NOTIFICATION_SUPER_ADMIN, TO_EMAIL_DEV, $replacement_array);
                        $replacement_array = array();
                        $replacement_array['company_admin'] = ucwords($company_admin_full_name);
                        $replacement_array['form_link'] = $link;
                        log_and_send_templated_email(CREDIT_CARD_AUTHORIZATION_NOTIFICATION_COMPANY_ADMIN, $company_admin_email, $replacement_array);
                        $this->documents_model->update_document_status('credit_card_authorization', $verification_key, 'sent');
                        $this->session->set_flashdata('message', '<strong>Success</strong> : Credit Card Authorization Form Sent!');
                        redirect('manage_admin/documents/' . $company_sid, 'refresh');
                    }
                    
                    break;
                case 'upload_document':
                    $verification_key = random_key(80);
                    $company_sid = $this->input->post('company_sid');
                    $document_name = $this->input->post('document_name');
                    $document_short_desc = $this->input->post('document_short_description');
                    $aws_file_name = upload_file_to_aws('document', $company_sid, $document_name, 'by_admin_' . date('Ymd'));
                    $dataToInsert = array();
                    $dataToInsert['company_sid'] = $company_sid;
                    $dataToInsert['admin_upload_date'] = date('Y-m-d H:i:s');
                    $dataToInsert['admin_aws_filename'] = $aws_file_name;
                    $dataToInsert['document_name'] = $document_name;
                    $dataToInsert['document_short_description'] = $document_short_desc;
                    $this->documents_model->insert_document_record('uploaded_document', $company_sid, $verification_key, 'uploaded', $dataToInsert);
                    $this->session->set_flashdata('message', '<strong>Success</strong> : Document Uploaded successfully!');
                    redirect('manage_admin/documents/' . $company_sid, 'refresh');
                    break;
            }
        }
    }

    public function send($company_sid = null) {
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $companies_documents = $this->documents_model->get_all_companies_and_documents($company_sid);
            $this->data['company_sid'] = $company_sid;
            $this->form_validation->set_rules('perform_action', 'perform action', 'required|xss_clean|trim');
            $employees = $this->documents_model->get_company_employees($company_sid);

            if ($this->form_validation->run() == false) {
                $this->data['companies_documents'] = $companies_documents;
                $this->data['employees'] = $employees;
                $this->render('manage_admin/documents/send_documents', 'admin_master');
            } else {
                $perform_action = $this->input->post('perform_action');
                $cc_auth = $this->input->post('cc_auth');
                $eula = $this->input->post('eula');
                $payroll_cc_auth = $this->input->post('payroll_cc_auth');
                $contacts = $this->input->post('contacts');
                $uploaded_documents = $this->input->post('documents');
                $message = $this->input->post('message');

                $fpa = $this->input->post('fpa');


                $link_cc_auth = '';
                $link_eula = '';
                $link_payroll_cc_auth = '';
                $link_contacts = '';
                

               if (strlen($cc_auth) > 1) {
                    $link_cc_auth = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" target="_blank" href="' . base_url('form_credit_card_authorization/' . $cc_auth) . '">Credit Card Authorization Form</a>';
                    $cc_auth_sid = $this->documents_model->get_document_sid('credit_card_authorization', $cc_auth);
                    $this->documents_model->update_document_status('credit_card_authorization', $cc_auth, 'sent');
                }

                if (strlen($eula) > 1) {
                    $link_eula = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url('form_end_user_license_agreement/' . $eula) . '">End User License Agreement</a>';
                    $eula_sid = $this->documents_model->get_document_sid('end_user_license_agreement', $eula);
                    $this->documents_model->update_document_status('end_user_license_agreement', $eula, 'sent');
                }

                if (strlen($contacts) > 1) {
                    $link_contacts = '<a style="' . DEF_EMAIL_BTN_STYLE_SUCCESS . '" target="_blank" href="' . base_url('form_company_contacts/' . $contacts) . '">Company Contacts</a>';
                    $contacts_sid = $this->documents_model->get_document_sid('company_contacts', $contacts);
                    $this->documents_model->update_document_status('company_contacts', $contacts, 'sent');
                }


                if (strlen($fpa) > 1) {
                    $link_fpa = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url('form_payroll_agreement/' . $fpa) . '">Payroll Agreement</a>';
                    $fpa_sid = $this->documents_model->get_document_sid('form_payroll_agreement', $fpa);
                    $this->documents_model->update_document_status('form_payroll_agreement', $fpa, 'sent');
                }
                
                if (strlen($payroll_cc_auth) > 1) {
                    $link_fpa = '<a style="' . DEF_EMAIL_BTN_STYLE_DANGER . '" target="_blank" href="' . base_url('payroll_form_credit_card_authorization/' . $payroll_cc_auth) . '">Payroll Credit Card Authorization Form</a>';
                    $payroll_cc_auth_sid = $this->documents_model->get_document_sid('payroll_form_credit_card_authorization', $payroll_cc_auth);
                    $this->documents_model->update_document_status('payroll_form_credit_card_authorization', $payroll_cc_auth, 'sent');
                }


                $documents_links = '';
                $temp_link = '';

                if (!empty($uploaded_documents)) {
                    foreach ($uploaded_documents as $uploaded_document) {
                        if (strlen($uploaded_document) > 1) {
                            $document_detail = $this->documents_model->get_document_record('uploaded_document', $uploaded_document);
                            $temp_link = '<a target="_blank" href="' . base_url('form_company_agreements/' . $uploaded_document) . '">' . $document_detail['document_name'] . '</a>';
                            $this->documents_model->update_document_status('uploaded_document', $uploaded_document, 'sent');
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


                if ($link_fpa != '') {
                    $links .= '<br />';
                    $links .= $link_fpa;
                    $links .= '<br />';
                }


                if ($link_cc_auth != '') {
                    $links .= '<br />';
                    $links .= $link_cc_auth;
                    $links .= '<br />';
                }

                if ($link_contacts != '') {
                    $links .= '<br />';
                    $links .= $link_contacts;
                    $links .= '<br />';
                }

                if ($documents_links != '') {
                    $links .= $documents_links;
                }

                switch (strtolower($perform_action)) {
                    case 'send_to_single_email':
                        $email_address = $this->input->post('email');
                        $company_name = $this->input->post('company_name');
                        $replacement_array = array();
                        $replacement_array['company_name'] = $company_name;
                        $replacement_array['email_address'] = $email_address;
                        $replacement_array['links'] = $links;
                        $replacement_array['message'] = $message;
                        $system_notification_emails = get_system_notification_emails('documents_management_emails');

                        if (!empty($system_notification_emails)) {
                            foreach ($system_notification_emails as $system_notification_email) {
                                log_and_send_templated_email(FORMS_NOTIFICATION_TO_ADMIN, $system_notification_email['email'], $replacement_array);
                            }
                        }

                        log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $email_address, $replacement_array);

                        if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                            $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Manual Email', $email_address);
                        }

                        if (isset($eula_sid) && $eula_sid != 0) {
                            $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Manual Email', $email_address);
                        }


                        if (isset($fpa_sid) && $fpa_sid != 0) {
                            $this->documents_model->insert_document_email_history_record($company_sid, $fpa_sid, 'Manual Email', $email_address);
                        }
                        
                        if (isset($payroll_cc_auth_sid) && $payroll_cc_auth_sid != 0) {
                            $this->documents_model->insert_document_email_history_record($company_sid, $payroll_cc_auth_sid, 'Manual Email', $email_address);
                        }


                        if (isset($contacts_sid) && $contacts_sid != 0) {
                            $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Manual Email', $email_address);
                        }

                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfully Forwarded.');
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'send_to_company_admin':
                        $send_to = $this->input->post('send_to');
                        $primary_email = $this->input->post('admin_email_address');
                        $alternate_email = $this->input->post('admin_alt_email_address');
                        $company_name = $this->input->post('company_name');

                        if ($send_to == 'primary') {
                            $replacement_array = array();
                            $replacement_array['company_name'] = '<strong>' . ucwords($company_name) . '</strong>';
                            $replacement_array['links'] = $links;
                            $replacement_array['email_address'] = $primary_email;
                            $system_notification_emails = get_system_notification_emails('documents_management_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    log_and_send_templated_email(FORMS_NOTIFICATION_TO_ADMIN, $system_notification_email['email'], $replacement_array);
                                }
                            }

                            log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $primary_email, $replacement_array);

                            if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Company Admin', $primary_email);
                            }

                            if (isset($eula_sid) && $eula_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Company Admin', $primary_email);
                            }

                            if (isset($contacts_sid) && $contacts_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Company Admin', $primary_email);
                            }

                        } elseif ($send_to == 'alternate') {
                            $replacement_array = array();
                            $replacement_array['company_name'] = '<strong>' . ucwords($company_name) . '</strong>';
                            $replacement_array['links'] = $links;
                            $replacement_array['message'] = $message;
                            $replacement_array['email_address'] = $alternate_email;
                            $system_notification_emails = get_system_notification_emails('documents_management_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    log_and_send_templated_email(FORMS_NOTIFICATION_TO_ADMIN, $system_notification_email['email'], $replacement_array);
                                }
                            }

                            log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $alternate_email, $replacement_array);

                            if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Company Admin', $alternate_email);
                            }

                            if (isset($eula_sid) && $eula_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Company Admin', $alternate_email);
                            }

                            if (isset($contacts_sid) && $contacts_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Company Admin', $alternate_email);
                            }

                        } elseif ($send_to == 'both') {
                            $replacement_array = array();
                            $replacement_array['company_name'] = '<strong>' . ucwords($company_name) . '</strong>';
                            $replacement_array['links'] = $links;
                            $replacement_array['message'] = $message;
                            $replacement_array['email_address'] = $primary_email . ' and ' . $alternate_email;
                            $system_notification_emails = get_system_notification_emails('documents_management_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    log_and_send_templated_email(FORMS_NOTIFICATION_TO_ADMIN, $system_notification_email['email'], $replacement_array);
                                }
                            }

                            log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $primary_email, $replacement_array);

                            if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Company Admin', $primary_email);
                            }

                            if (isset($eula_sid) && $eula_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Company Admin', $primary_email);
                            }

                            if (isset($contacts_sid) && $contacts_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Company Admin', $primary_email);
                            }

                            log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $alternate_email, $replacement_array);

                            if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Company Admin', $alternate_email);
                            }

                            if (isset($eula_sid) && $eula_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Company Admin', $alternate_email);
                            }

                            if (isset($contacts_sid) && $contacts_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Company Admin', $alternate_email);
                            }
                        }

                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfully Forwarded.');
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                    case 'send_to_employees':
                        $company_name = $this->input->post('company_name');
                        $employee_emails = $this->input->post('employee_emails');

                        foreach ($employee_emails as $email) {
                            $replacement_array = array();
                            $replacement_array['company_name'] = $company_name;
                            $replacement_array['email_address'] = $email;
                            $replacement_array['links'] = $links;
                            $replacement_array['message'] = $message;
 
                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    log_and_send_templated_email(FORMS_NOTIFICATION_TO_ADMIN, $system_notification_email['email'], $replacement_array);
                                }
                            }

                            log_and_send_templated_email(FORMS_NOTIFICATION_TO_CLIENT, $email, $replacement_array);

                            if (isset($cc_auth_sid) && $cc_auth_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $cc_auth_sid, 'Employee', $email);
                            }

                            if (isset($eula_sid) && $eula_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $eula_sid, 'Employee', $email);
                            }

                            if (isset($contacts_sid) && $contacts_sid != 0) {
                                $this->documents_model->insert_document_email_history_record($company_sid, $contacts_sid, 'Employee', $email);
                            }
                        }

                        $this->session->set_flashdata('message', '<b>Success:</b> Documents Successfuly Forwarded.');
                        redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('manage_admin/documents');
        }
    }

    public function check_signed_forms() {
        $companies_documents = $this->documents_model->get_cc_auth();

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
                $this->documents_model->update_database($sid, $cc_type, $cc_holder_name, $cc_number, $cc_expiration_month, $cc_expiration_year);
            } else {
                echo 'Nothing to process';
            }
        }
    }

    function regenerate_credit_card_authorization ($verification_key = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'regenerate_credit_card_authorization';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);

        $credit_card_info = $this->documents_model->get_credit_card_information($verification_key);
        $record_sid = $credit_card_info['sid'];
        $company_sid = $credit_card_info['company_sid'];
        
        $data_to_insert = array();
        $data_to_insert = $credit_card_info;
        $data_to_insert['credit_card_document_sid'] = $record_sid;
        unset($data_to_insert['sid']);
        $this->documents_model->insert_credit_card_authorization_history($data_to_insert);

        $data_to_update = array();
        $data_to_update['status'] = 'generated';
        $data_to_update['status_date'] = date('Y-m-d H:i:s');
        $data_to_update['authorized_person_full_name'] = NULL;
        $data_to_update['recurring_amount'] = 0;
        $data_to_update['day_of_payment'] = 0;
        $data_to_update['authorization_on_behalf_of'] = NULL;
        $data_to_update['billing_address'] = NULL;
        $data_to_update['billing_phone_number'] = NULL;
        $data_to_update['billing_city'] = NULL;
        $data_to_update['billing_state'] = NULL;
        $data_to_update['billing_zip_code'] = NULL;
        $data_to_update['billing_email_address'] = NULL;
        $data_to_update['bank_account_type'] = NULL;
        $data_to_update['bank_name'] = NULL;
        $data_to_update['bank_account_title'] = NULL;
        $data_to_update['bank_account_number'] = NULL;
        $data_to_update['bank_routing_number'] = NULL;
        $data_to_update['bank_state'] = NULL;
        $data_to_update['bank_city'] = NULL;
        $data_to_update['cc_type'] = NULL;
        $data_to_update['cc_holder_name'] = NULL;
        $data_to_update['cc_number'] = NULL;
        $data_to_update['cc_expiration_month'] = NULL;
        $data_to_update['cc_expiration_year'] = NULL;
        $data_to_update['cc_front_image'] = NULL;
        $data_to_update['cc_back_image'] = NULL;
        $data_to_update['driving_license_front_image'] = NULL;
        $data_to_update['authorized_signature'] = NULL;
        $data_to_update['authorization_date'] = NULL;
        $data_to_update['additional_fee'] = 0;
        $data_to_update['acknowledgement'] = NULL;
        $data_to_update['processed'] = 0;
        $data_to_update['client_ip'] = NULL;
        $data_to_update['client_signature_timestamp'] = NULL;
        $this->documents_model->regenerate_credit_card_authorization($record_sid, $data_to_update);
        $this->session->set_flashdata('message', '<strong>Success</strong> : Credit Card Regenerate Successfully!');
        redirect('manage_admin/documents/' . $company_sid, 'refresh');
    }

    function regenerate_payroll_credit_card_authorization($verification_key = null)
    {
        $redirect_url = 'manage_admin/';
        $function_name = 'regenerate_payroll_credit_card_authorization';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);

        $credit_card_info = $this->documents_model->get_payroll_credit_card_information($verification_key);
        $record_sid = $credit_card_info['sid'];
        $company_sid = $credit_card_info['company_sid'];

        $data_to_insert = array();
        $data_to_insert = $credit_card_info;
        $data_to_insert['credit_card_document_sid'] = $record_sid;
        unset($data_to_insert['sid']);
        $this->documents_model->insert_payroll_credit_card_authorization_history($data_to_insert);

        $data_to_update = array();
        $data_to_update['status'] = 'generated';
        $data_to_update['status_date'] = date('Y-m-d H:i:s');
        $data_to_update['authorized_person_full_name'] = NULL;
        $data_to_update['recurring_amount'] = 0;
        $data_to_update['day_of_payment'] = 0;
        $data_to_update['authorization_on_behalf_of'] = NULL;
        $data_to_update['billing_address'] = NULL;
        $data_to_update['billing_phone_number'] = NULL;
        $data_to_update['billing_city'] = NULL;
        $data_to_update['billing_state'] = NULL;
        $data_to_update['billing_zip_code'] = NULL;
        $data_to_update['billing_email_address'] = NULL;
        $data_to_update['bank_account_type'] = NULL;
        $data_to_update['bank_name'] = NULL;
        $data_to_update['bank_account_title'] = NULL;
        $data_to_update['bank_account_number'] = NULL;
        $data_to_update['bank_routing_number'] = NULL;
        $data_to_update['bank_state'] = NULL;
        $data_to_update['bank_city'] = NULL;
        $data_to_update['cc_type'] = NULL;
        $data_to_update['cc_holder_name'] = NULL;
        $data_to_update['cc_number'] = NULL;
        $data_to_update['cc_expiration_month'] = NULL;
        $data_to_update['cc_expiration_year'] = NULL;
        $data_to_update['cc_front_image'] = NULL;
        $data_to_update['cc_back_image'] = NULL;
        $data_to_update['driving_license_front_image'] = NULL;
        $data_to_update['authorized_signature'] = NULL;
        $data_to_update['authorization_date'] = NULL;
        $data_to_update['additional_fee'] = 0;
        $data_to_update['acknowledgement'] = NULL;
        $data_to_update['processed'] = 0;
        $data_to_update['contract_type'] = null;
        $data_to_update['contract_length'] = 0;
        $data_to_update['client_ip'] = NULL;
        $data_to_update['client_signature_timestamp'] = NULL;
        $this->documents_model->regenerate_payroll_credit_card_authorization($record_sid, $data_to_update);
        $this->session->set_flashdata('message', '<strong>Success</strong> : Payroll Credit Card Regenerate Successfully!');
        redirect('manage_admin/documents/' . $company_sid, 'refresh');
    }

    function regenerate_enduser_license_agreement ($verification_key = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'regenerate_enduser_license_agreement';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        $eula_info = $this->documents_model->get_enduser_license_agreement_information($verification_key);
        $record_sid = $eula_info['sid'];
        $company_sid = $eula_info['company_sid'];
        
        $data_to_insert = array();
        $data_to_insert = $eula_info;
        $data_to_insert['document_eula_sid'] = $record_sid;
        unset($data_to_insert['sid']);
        $this->documents_model->insert_enduser_license_agreement_history($data_to_insert);

        $data_to_update = array();
        $data_to_update['status'] = 'generated';
        $data_to_update['status_date'] = date('Y-m-d H:i:s');
        $data_to_update['the_entity'] = NULL;
        $data_to_update['the_client'] = NULL;
        $data_to_update['development_fee'] = 0;
        $data_to_update['monthly_fee'] = 0;
        $data_to_update['company_by'] = NULL;
        $data_to_update['company_name'] = NULL;
        $data_to_update['company_title'] = NULL;
        $data_to_update['company_date'] = NULL;
        $data_to_update['client_by'] = NULL;
        $data_to_update['client_name'] = NULL;
        $data_to_update['client_title'] = NULL;
        $data_to_update['client_date'] = NULL;
        $data_to_update['company_signature'] = NULL;
        $data_to_update['client_signature'] = NULL;
        $data_to_update['acknowledgement'] = NULL;
        $data_to_update['is_trial_period'] = 0;
        $data_to_update['recurring_payment_day'] = NULL;
        $data_to_update['trial_limit'] = 0;
        $data_to_update['client_ip'] = NULL;
        $data_to_update['client_signature_timestamp'] = NULL;
        $this->documents_model->regenerate_enduser_license_agreement($record_sid, $data_to_update);
        $this->session->set_flashdata('message', '<strong>Success</strong> : End User License Agreement Regenerate Successfully!');
        redirect('manage_admin/documents/' . $company_sid, 'refresh');
    }

    function regenerate_company_contacts_document ($verification_key = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'regenerate_company_contacts_document';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        $cc_info = $this->documents_model->get_company_contacts_information($verification_key);
        $record_sid = $cc_info['sid'];
        $company_sid = $cc_info['company_sid'];

        $data_to_insert = array();
        $data_to_insert = $cc_info;
        $data_to_insert['cc_document_sid'] = $record_sid;
        unset($data_to_insert['sid']);
        $this->documents_model->insert_company_contacts_history($data_to_insert);

        $data_to_update = array();
        $data_to_update['status'] = 'generated';
        $data_to_update['status_date'] = date('Y-m-d H:i:s');
        $data_to_update['company_name'] = NULL;
        $data_to_update['platform_activation_date'] = NULL;
        $data_to_update['team_onboarding_date'] = NULL;
        $data_to_update['team_onboarding_time'] = NULL;
        $data_to_update['it_meeting_date'] = NULL;
        $data_to_update['it_meeting_time'] = NULL;
        $data_to_update['cp_meeting_date'] = NULL;
        $data_to_update['cp_meeting_time'] = NULL;
        $this->documents_model->regenerate_company_contacts_document($record_sid, $data_to_update);
        $cc_details_info = $this->documents_model->get_company_contacts_details_information($company_sid ,$record_sid);

        foreach ($cc_details_info as $info) {
            $detail_sid = $info['sid'];
            $employe_detail_to_insert = array();
            $employe_detail_to_insert = $info;
            $employe_detail_to_insert['cc_detail_sid'] = $detail_sid;
            unset($employe_detail_to_insert['sid']);
            $this->documents_model->insert_company_contacts_detail_history($employe_detail_to_insert);
            $this->documents_model->delete_company_contacts_detail($detail_sid);
        }

        $this->session->set_flashdata('message', '<strong>Success</strong> : Company Contacts Document Regenerate Successfully!');
        redirect('manage_admin/documents/' . $company_sid, 'refresh');
    }


    function regenerate_enduser_payroll_agreement ($verification_key = null) {
        $redirect_url = 'manage_admin/';
        $function_name = 'regenerate_enduser_license_agreement';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        $fpa_info = $this->documents_model->get_enduser_payroll_agreement_information($verification_key);
        $record_sid = $fpa_info['sid'];
        $company_sid = $fpa_info['company_sid'];
        
        $data_to_insert = array();
        $data_to_insert = $fpa_info;
        $data_to_insert['document_fpa_sid'] = $record_sid;
        unset($data_to_insert['sid']);
        $this->documents_model->insert_enduser_payroll_agreement_history($data_to_insert);

        $data_to_update = array();
        $data_to_update['status'] = 'generated';
        $data_to_update['status_date'] = date('Y-m-d H:i:s');
        $data_to_update['acknowledgement'] = NULL;
        $data_to_update['client_ip'] = NULL;
        $this->documents_model->regenerate_enduser_payroll_agreement($record_sid, $data_to_update);
        $this->session->set_flashdata('message', '<strong>Success</strong> : Payroll Agreement Regenerate Successfully!');
        redirect('manage_admin/documents/' . $company_sid, 'refresh');
    }


}