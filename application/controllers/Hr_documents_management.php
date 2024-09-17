<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_documents_management extends Public_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('hr_documents_management_model');
        $this->load->model('onboarding_model');
        $this->load->model('varification_document_model');
        $this->load->library('pagination');
        // error_reporting(E_ALL);
        // ini_set('display_errors', 1);
    }

    public function index()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_email = $data['session']['employer_detail']['email'];
            $employer_first_name = $data['session']['employer_detail']['first_name'];
            $employer_last_name = $data['session']['employer_detail']['last_name'];
            $active_groups = array();
            $in_active_groups = array();
            $group_ids = array();
            $group_docs = array();
            $document_ids = array();

            $data['title'] = 'Document Management';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;
            $data['employer_email'] = $employer_email;
            $data['employer_first_name'] = $employer_first_name;
            $data['employer_last_name'] = $employer_last_name;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);

            foreach ($groups as $group) {
                $group_ids[] = $group['sid'];
            }

            if (!empty($group_ids)) {
                $group_docs = $this->hr_documents_management_model->get_distinct_group_docs($group_ids);
            }

            if (!empty($group_docs)) { // document are assigned to any group.
                foreach ($group_docs as $group_doc) {
                    $document_ids[] = $group_doc['document_sid'];
                }
            }

            if ($this->form_validation->run() == false) {
                if (!empty($groups)) {

                    //  _e($groups,true,true);
                    foreach ($groups as $key => $group) {
                        $group_status = $group['status'];
                        $group_sid = $group['sid'];
                        $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 0);


                        $otherDocuments = getGroupOtherDocuments($group);
                        $otherDocumentCount = count($otherDocuments);


                        if ($group_status) {
                            $active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'documents_count' => count($group_documents) + $otherDocumentCount,
                                'documents' => $group_documents,
                                'other_documents' => $otherDocuments
                            );
                        } else {
                            $in_active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'documents_count' => count($group_documents) + $otherDocumentCount,
                                'documents' => $group_documents,
                                'other_documents' => $otherDocuments
                            );
                        }
                    }
                }

                $all_documents = $this->hr_documents_management_model->get_all_company_documents($company_sid);

                $uncategorized_documents = $this->hr_documents_management_model->get_uncategorized_docs($company_sid, $document_ids, 0);

                $data['uncategorized_documents'] = $uncategorized_documents;
                $data['active_groups'] = $active_groups;
                $data['all_documents'] = $all_documents;
                $data['in_active_groups'] = $in_active_groups;
                $offer_letters = $this->hr_documents_management_model->get_all_offer_letters($company_sid, 0);
                $data['offer_letters'] = $offer_letters;
                $sections = $this->hr_documents_management_model->get_hr_documents_section_records(1); //Get Editors Data
                $data['sections'] = $sections;

                $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(
                    $data['company_sid'],
                    $data['employer_sid']
                );
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                $post = $this->input->post(NULL, TRUE);

                switch ($perform_action) {
                    case 'archive_uploaded_document':
                        $document_sid = $this->input->post('document_sid');
                        $document_type = $this->input->post('document_type');

                        $old_document = array();
                        $old_document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                        $old_document['documents_management_sid'] = $document_sid;
                        $old_document['update_by_sid'] = $employer_sid;
                        unset($old_document['sid']);
                        $this->hr_documents_management_model->insert_document_management_history($old_document);

                        $this->hr_documents_management_model->update_documents($document_sid, array('archive' => 1), 'documents_management');
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Archived!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                    case 'archive_offer_letter':
                        $offer_letter_sid = $this->input->post('offer_letter_sid');

                        $old_offer_letter = array();
                        $old_offer_letter = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $offer_letter_sid);
                        $old_offer_letter['offer_letter_sid'] = $offer_letter_sid;
                        $old_offer_letter['update_by_sid'] = $employer_sid;
                        unset($old_offer_letter['sid']);
                        $this->hr_documents_management_model->insert_offer_letter_history($old_offer_letter);
                        $this->hr_documents_management_model->set_offer_letter_archive_status($offer_letter_sid, 1);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Archived!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                    case 'assign_document':
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $select_employees = $this->input->post('employees');
                        $user_type = 'employee';

                        $authorized_signature_required = $this->input->post('auth_sign_sid');

                        if ($authorized_signature_required > 0) {
                            $update_authorized_signature = array();
                            $update_authorized_signature['document_sid'] = $document_sid;
                            $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
                        }
                        // 
                        if ($post['assign_type'] == 'department') {
                            // Fetch all employees belong to selected department
                            $select_employees = $this->hr_documents_management_model->getEmployeesFromDepartment(
                                $post['departments'],
                                $company_sid
                            );
                        } else if ($post['assign_type'] == 'team') {
                            // Fetch all employees belong to selected department
                            $select_employees = $this->hr_documents_management_model->getEmployeesFromTeams(
                                $post['teams'],
                                $company_sid
                            );
                        } else {
                            $select_employees = $this->hr_documents_management_model->getEmployees(
                                $post['employees'],
                                $company_sid
                            );
                        }

                        //
                        $documentDescription =
                            html_entity_decode(checkAndGetDocumentDescription(
                                $document_sid,
                                $this->input->post('document_description') ?? '',
                                true
                            ));                      

                        $doSendEmails = !$this->input->post('notification_email', true)
                            ? 'yes'
                            :  $this->input->post('notification_email', true);
                        //
                        $hf = message_header_footer(
                            $company_sid,
                            $company_name
                        );
                        foreach ($select_employees as $emp) {
                            $check_exist = $this->hr_documents_management_model->check_assigned_document($document_sid, $emp, $user_type);
                            if (!empty($check_exist)) {
                                $assignment_sid = $check_exist[0]['sid'];
                                $assigned_document = $this->hr_documents_management_model->get_assigned_document_details($company_sid, $assignment_sid);
                                unset($assigned_document['sid']);
                                $assigned_document['doc_sid'] = $assignment_sid;
                                $this->hr_documents_management_model->insert_documents_assignment_record_history($assigned_document);

                                $data_to_update = array();
                                $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                                $data_to_update['company_sid'] = $company_sid;
                                $data_to_update['assigned_date'] = date('Y-m-d H:i:s');
                                $data_to_update['assigned_by'] = $employer_sid;
                                $data_to_update['user_type'] = $user_type;
                                $data_to_update['user_sid'] = $emp;
                                $data_to_update['document_type'] = $document_type;
                                $data_to_update['document_sid'] = $document_sid;
                                $data_to_update['status'] = 1;
                                $data_to_update['document_original_name'] = $document['uploaded_document_original_name'];
                                $data_to_update['document_extension'] = $document['uploaded_document_extension'];
                                $data_to_update['document_s3_name'] = $document['uploaded_document_s3_name'];
                                $data_to_update['document_title'] = $document['document_title'];
                                $data_to_update['document_description'] = $documentDescription;
                                $data_to_update['acknowledged'] = NULL;
                                $data_to_update['acknowledged_date'] = NULL;
                                $data_to_update['downloaded'] = NULL;
                                $data_to_update['downloaded_date'] = NULL;
                                $data_to_update['uploaded'] = NULL;
                                $data_to_update['uploaded_date'] = NULL;
                                $data_to_update['uploaded_file'] = NULL;
                                $data_to_update['signature_timestamp'] = NULL;
                                $data_to_update['signature'] = NULL;
                                $data_to_update['signature_email'] = NULL;
                                $data_to_update['signature_ip'] = NULL;
                                $data_to_update['user_consent'] = 0;
                                $data_to_update['submitted_description'] = NULL;
                                $data_to_update['signature_base64'] = NULL;
                                $data_to_update['signature_initial'] = NULL;
                                $data_to_update['download_required'] = $document['download_required'];
                                $data_to_update['signature_required'] = $document['signature_required'];
                                $data_to_update['acknowledgment_required'] = $document['acknowledgment_required'];
                                $data_to_update['is_required'] = $document['is_required'];
                                $data_to_update['fillable_document_slug'] = $document['fillable_document_slug'];
                                //
                                addColumnsForDocumentAssigned($data_to_update, $document);
                                $this->hr_documents_management_model->update_documents($assignment_sid, $data_to_update, 'documents_assigned');
                            } else {
                                $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                                $data_to_insert = array();
                                $data_to_insert['company_sid'] = $company_sid;
                                $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                                $data_to_insert['assigned_by'] = $employer_sid;
                                $data_to_insert['user_type'] = $user_type;
                                $data_to_insert['user_sid'] = $emp;
                                $data_to_insert['document_type'] = $document_type;
                                $data_to_insert['document_sid'] = $document_sid;
                                $data_to_insert['status'] = 1;
                                $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                                $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                                $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                                $data_to_insert['document_title'] = $document['document_title'];
                                $data_to_insert['document_description'] = $documentDescription;
                                $data_to_insert['download_required'] = $document['download_required'];
                                $data_to_insert['signature_required'] = $document['signature_required'];
                                $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                                $data_to_insert['is_required'] = $document['is_required'];
                                $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];

                                //
                                $data_to_insert['isdoctohandbook'] = $document['isdoctohandbook'];

                                //
                                addColumnsForDocumentAssigned($data_to_insert, $document);

                                $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            }

                            //
                            if ($doSendEmails == 'no') continue;

                            $user_info = array();
                            switch ($user_type) {
                                case 'employee':
                                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $emp);
                                    //Send Email and SMS
                                    $replacement_array = array();
                                    $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                    $replacement_array['company_name'] = ucwords($company_name);
                                    $replacement_array['username'] = $replacement_array['contact-name'];
                                    $replacement_array['firstname'] = $user_info['first_name'];
                                    $replacement_array['lastname'] = $user_info['last_name'];
                                    $replacement_array['username'] = $replacement_array['contact-name'];
                                    $replacement_array['first_name'] = $user_info['first_name'];
                                    $replacement_array['last_name'] = $user_info['last_name'];
                                    $replacement_array['baseurl'] = base_url();
                                    $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                    //SMS Start
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);

                                    if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {

                                        $is_manual = get_document_type($assignment_sid);
                                        //
                                        if ($is_manual == 'no') {
                                            if ($company_sms_notification_status) {
                                                $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                                $sms_notify = 0;
                                                if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                    $contact_no = $notify_by['PhoneNumber'];
                                                    $sms_notify = 1;
                                                }
                                                if ($sms_notify) {
                                                    $this->load->library('Twilioapp');
                                                    // Send SMS
                                                    $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                                    $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                                    sendSMS(
                                                        $contact_no,
                                                        $sms_body,
                                                        trim(ucwords(strtolower($replacement_array['company_name']))),
                                                        $user_info['email'],
                                                        $this,
                                                        $sms_notify,
                                                        $company_sid
                                                    );
                                                }
                                            }
                                            //
                                            $user_extra_info = array();
                                            $user_extra_info['user_sid'] = $emp;
                                            $user_extra_info['user_type'] = "employee";
                                            //
                                            $this->load->model('Hr_documents_management_model', 'HRDMM');
                                            if ($this->HRDMM->isActiveUser($emp)) {
                                                //
                                                if ($this->hr_documents_management_model->doSendEmail($emp, "employee", "HREMS2")) {
                                                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                                }
                                            }
                                            //
                                            $this->hr_documents_management_model->update_employee($emp, array('document_sent_on' => date('Y-m-d H:i:s')));
                                        }
                                    }
                                    break;
                                case 'applicant':
                                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $emp);
                                    break;
                            }
                        }

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Bulk Assigned!');
                        redirect('hr_documents_management/', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function archived_documents()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'view_archive_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Archived Document Management';
            $active_groups = array();
            $in_active_groups = array();
            $group_ids = array();
            $group_docs = array();
            $document_ids = array();
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid);

            foreach ($groups as $group) {
                $group_ids[] = $group['sid'];
            }

            if (!empty($group_ids)) {
                $group_docs = $this->hr_documents_management_model->get_distinct_group_docs($group_ids);
            }

            if (!empty($group_docs)) { // document are assigned to any group.
                foreach ($group_docs as $group_doc) {
                    $document_ids[] = $group_doc['document_sid'];
                }
            }

            $data['all_documents'] = array();

            if ($this->form_validation->run() == false) {
                if (!empty($groups)) {
                    foreach ($groups as $key => $group) {
                        $group_status = $group['status'];
                        $group_sid = $group['sid'];
                        $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 1);

                        $data['all_documents'] = array_merge($data['all_documents'], $group_documents);
                        if ($group_status) {
                            $active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents
                            );
                        } else {
                            $in_active_groups[] = array(
                                'sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'eeoc' => $group['eeoc'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents
                            );
                        }
                    }
                }

                $uncategorized_documents = $this->hr_documents_management_model->get_uncategorized_docs($company_sid, $document_ids, 1);
                $data['uncategorized_documents'] = $uncategorized_documents;
                $data['active_groups'] = $active_groups;
                $data['in_active_groups'] = $in_active_groups;
                $data['all_documents'] = array_merge($data['all_documents'], $uncategorized_documents);
                $data['all_documents'] = array_merge($data['all_documents'], $uncategorized_documents);
                //
                $data['all_documents'] = array_unique($data['all_documents'], SORT_REGULAR);

                $offer_letters = $this->hr_documents_management_model->get_all_offer_letters($company_sid, 1);
                $data['offer_letters'] = $offer_letters;

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/archived_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'activate_uploaded_document':
                        $document_sid = $this->input->post('document_sid');
                        $document_type = $this->input->post('document_type');

                        $old_document = array();
                        $old_document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                        $old_document['documents_management_sid'] = $document_sid;
                        $old_document['update_by_sid'] = $employer_sid;
                        unset($old_document['sid']);
                        $this->hr_documents_management_model->insert_document_management_history($old_document);
                        $this->hr_documents_management_model->update_documents($document_sid, array('archive' => 0), 'documents_management');

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Activated!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                    case 'activate_offer_letter':
                        $offer_letter_sid = $this->input->post('offer_letter_sid');

                        $old_offer_letter = array();
                        $old_offer_letter = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $offer_letter_sid);
                        $old_offer_letter['offer_letter_sid'] = $offer_letter_sid;
                        $old_offer_letter['update_by_sid'] = $employer_sid;
                        unset($old_offer_letter['sid']);
                        $this->hr_documents_management_model->insert_offer_letter_history($old_offer_letter);
                        $this->hr_documents_management_model->set_offer_letter_archive_status($offer_letter_sid, 0);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Activated!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function upload_new_document()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_upload_generate_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Upload a New Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);
            $data['document_groups'] = $groups;
            $pre_assigned_groups = array();
            $data['pre_assigned_groups'] = $pre_assigned_groups;
            $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($company_sid, 1);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(
                $data['company_sid'],
                $data['employer_sid']
            );

            if ($this->form_validation->run() == false) {
                // Get departments & teams
                $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
                $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
                //
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/upload_new_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'upload_document':
                        $document_title = $this->input->post('document_title');
                        $document_description = $this->input->post('document_description');
                        $video_required = $this->input->post('video_source');
                        $document_description = htmlentities($document_description);
                        if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            // $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                        } else {
                            // $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);
                        }

                        if (!empty($this->input->post('document_url'))) {
                            $uploaded_document_s3_name = $this->input->post('document_url');
                            $uploaded_document_original_name = $this->input->post('document_name');
                            $uploaded_document_original_name = $document_title;
                        }


                        // if (isset($_FILES['document']['name'])) {
                        // $uploaded_document_original_name = $_FILES['document']['name'];
                        // }


                        // $file_info = pathinfo($uploaded_document_original_name);
                        $data_to_insert = array();
                        $new_history_data = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['document_title'] = $document_title;
                        $data_to_insert['document_description'] = $document_description;
                        $data_to_insert['document_type'] = 'uploaded';
                        $data_to_insert['unique_key'] = generateRandomString(32);
                        $data_to_insert['onboarding'] = $this->input->post('onboarding');
                        $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $data_to_insert['download_required'] = $this->input->post('download_required');
                        $data_to_insert['signature_required'] = $this->input->post('signature_required');
                        $data_to_insert['isdoctolibrary'] = $this->input->post('isdoctolibrary') ? $this->input->post('isdoctolibrary') : 0;
                        $data_to_insert['visible_to_document_center'] = 0;
                        $data_to_insert['isdoctohandbook'] = $this->input->post('isdoctohandbook') ? $this->input->post('isdoctohandbook') : 0;

                        $data_to_insert['is_required'] = $this->input->post('isRequired') ? $this->input->post('isRequired') : 0;



                        $data_to_insert['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
                        if ($data_to_insert == 'days') {
                            $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
                        } else {
                            $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
                        }
                        if (!empty($this->input->post('sort_order')))
                            $data_to_insert['sort_order'] = $this->input->post('sort_order');
                        else
                            $data_to_insert['sort_order'] = 1;

                        // if (isset($file_info['extension'])) {
                        //     $data_to_insert['uploaded_document_extension'] = $file_info['extension'];
                        //     $new_history_data['uploaded_document_extension'] = $uploaded_document_s3_name;
                        // }

                        if (!empty($this->input->post('document_extension'))) {
                            $data_to_insert['uploaded_document_extension'] = $this->input->post('document_extension');
                            $new_history_data['uploaded_document_extension'] = $uploaded_document_s3_name;

                            if ($uploaded_document_s3_name != 'error') {
                                $data_to_insert['uploaded_document_original_name'] = $uploaded_document_original_name;
                                $data_to_insert['uploaded_document_s3_name'] = $uploaded_document_s3_name;

                                $new_history_data['uploaded_document_original_name'] = $uploaded_document_original_name;
                                $new_history_data['uploaded_document_s3_name'] = $uploaded_document_s3_name;
                            }
                        }

                        if ($video_required != 'not_required') {
                            $video_source = $this->input->post('video_source');

                            if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                                $random = generateRandomString(5);
                                $company_id = $data['session']['company_detail']['sid'];
                                $target_file_name = basename($_FILES["video_upload"]["name"]);
                                $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                                $target_dir = "assets/uploaded_videos/";
                                $target_file = $target_dir . $file_name;
                                $filename = $target_dir . $company_id;

                                if (!file_exists($filename)) {
                                    mkdir($filename);
                                }

                                if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                    redirect('hr_documents_management/upload_new_document', 'refresh');
                                }

                                $video_url = $file_name;
                            } else {
                                $video_url = $this->input->post('yt_vm_video_url');

                                if ($video_source == 'youtube') {
                                    $url_prams = array();
                                    parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_url = $url_prams['v'];
                                    } else {
                                        $video_url = '';
                                    }
                                } else {
                                    $video_url = $this->vimeo_get_id($video_url);
                                }
                            }

                            $data_to_insert['video_source'] = $video_source;
                            $data_to_insert['video_url'] = $video_url;
                            $data_to_insert['video_required'] = 1;
                            $new_history_data['video_required'] = 1;
                            $new_history_data['video_source'] = $video_source;
                            $new_history_data['video_url'] = $video_url;
                        } else {
                            $data_to_insert['video_required'] = 0;
                            $new_history_data['video_required'] = 0;
                        }

                        if (!isset($_POST['categories']) && isset($_POST['visible_to_payroll'])) {
                            $data_to_insert['visible_to_payroll'] = 1;
                        } else {
                            $data_to_insert['visible_to_payroll'] = 0;
                        }
                        $post = $this->input->post(NULL, true);
                        //
                        $data_to_insert['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
                        $data_to_insert['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
                        $data_to_insert['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
                        $data_to_insert['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;

                        // Assign & Send document
                        // Set
                        $aType = $this->input->post('assignAndSendDocument', true);
                        $aDate = $this->input->post('assignAndSendCustomDate', true);
                        $aDay = $this->input->post('assignAndSendCustomDay', true);
                        $aTime = $this->input->post('assignAndSendCustomTime', true);
                        $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
                        //
                        $data_to_insert['assign_type'] = $aType;
                        $data_to_insert['assign_date'] = $aDate;
                        $data_to_insert['assign_time'] = $aTime;
                        //
                        if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_insert['assign_type'] = 'none';
                        //
                        if (empty($aDate) && empty($aDay)) $data_to_insert['assign_date'] = null;
                        if (empty($aTime)) $data_to_insert['assign_time'] = null;
                        //
                        if ($aType == 'weekly' && !empty($aDay)) $data_to_insert['assign_date'] = $aDay;
                        //
                        if ($aEmployees && count($aEmployees)) {
                            //
                            if (in_array('-1', $aEmployees)) $data_to_insert['assigned_employee_list'] = 'all';
                            else $data_to_insert['assigned_employee_list'] = json_encode($aEmployees);
                        }
                        // Approver Flow
                        $data_to_insert['has_approval_flow'] = 0;
                        $data_to_insert['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
                        //
                        if ($post['has_approval_flow'] == 'on') {
                            $data_to_insert['has_approval_flow'] = 1;
                            $data_to_insert['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $data_to_insert['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }
                        // Document Settings - Confidential
                        $data_to_insert['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                        //
                        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                        //
                        $data_to_insert['confidential_employees'] = NULL;
                        //
                        if ($post['confidentialSelectedEmployees']) {
                            $data_to_insert['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                        }

                        $insert_id = $this->hr_documents_management_model->insert_document_record($data_to_insert);



                        if (isset($_POST['document_group_assignment'])) {
                            $document_group_assignment = $this->input->post('document_group_assignment');

                            if (!empty($document_group_assignment)) {
                                foreach ($document_group_assignment as $key => $group_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['group_sid'] = $group_sid;
                                    $data_to_insert['document_sid'] = $insert_id;
                                    $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                                }
                            }
                        }

                        if (isset($_POST['categories'])) {
                            $document_category_assignment = $this->input->post('categories');

                            if (!empty($document_category_assignment)) {
                                foreach ($document_category_assignment as $key => $category_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['category_sid'] = $category_sid;
                                    $data_to_insert['document_sid'] = $insert_id;
                                    $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                                }
                            }
                        }

                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data['documents_management_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['employer_sid'] = $employer_sid;
                        $new_history_data['document_title'] = $document_title;
                        $new_history_data['document_description'] = $document_description;
                        $new_history_data['document_type'] = 'uploaded';
                        $new_history_data['date_created'] = date('Y-m-d H:i:s');
                        $new_history_data['update_by_sid'] = $employer_sid;
                        $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $new_history_data['download_required'] = $this->input->post('download_required');
                        $new_history_data['signature_required'] = $this->input->post('signature_required');
                        $new_history_data['automatic_assign_in'] = !empty($this->input->post('assign-in')) ? $this->input->post('assign-in') : 0;
                        // Assigner handling
                        $new_history_data['has_approval_flow'] = 0;
                        $new_history_data['document_approval_note'] = $new_history_data['document_approval_employees'] = '';
                        // Assigner handling
                        if ($post['has_approval_flow'] == 'on') {
                            $new_history_data['has_approval_flow'] = 1;
                            $new_history_data['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $new_history_data['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }
                        //
                        $this->hr_documents_management_model->insert_document_management_history($new_history_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Upload Successful!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function generate_new_document()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_upload_generate_document'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_email = $data['session']['employer_detail']['email'];
            $employer_first_name = $data['session']['employer_detail']['first_name'];
            $employer_last_name = $data['session']['employer_detail']['last_name'];
            $data['title'] = 'Generate A New Document';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;
            $data['employer_email'] = $employer_email;
            $data['employer_first_name'] = $employer_first_name;
            $data['employer_last_name'] = $employer_last_name;
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);
            $data['document_groups'] = $groups;
            $pre_assigned_groups = array();
            $data['pre_assigned_groups'] = $pre_assigned_groups;
            $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($company_sid, 1);

            $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(
                $data['company_sid'],
                $data['employer_sid']
            );

            $data['pre_assigned_employees'] = array();

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                // Get departments & teams
                $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
                $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
                //
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/generate_new_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'generate_new_document':
                        $document_title = $this->input->post('document_title');
                        $document_description = $this->input->post('document_description');
                        $document_description = htmlentities($document_description);
                        $data_to_insert = array();
                        $new_history_data = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['document_title'] = $document_title;
                        $data_to_insert['document_description'] = $document_description;
                        $data_to_insert['isdoctolibrary'] = $this->input->post('isdoctolibrary') ? $this->input->post('isdoctolibrary') : 0;
                        $data_to_insert['visible_to_document_center'] = 0;
                        $data_to_insert['document_type'] = 'generated';
                        if (!empty($this->input->post('sort_order')))
                            $data_to_insert['sort_order'] = $this->input->post('sort_order');
                        else
                            $data_to_insert['sort_order'] = 1;
                        $data_to_insert['unique_key'] = generateRandomString(32);
                        $data_to_insert['onboarding'] = $this->input->post('onboarding');
                        $data_to_insert['download_required'] = $this->input->post('download_required');
                        $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $data_to_insert['signature_required'] = $this->input->post('signature_required');
                        $data_to_insert['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
                        if ($data_to_insert == 'days') {
                            $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
                        } else {
                            $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
                        }
                        $video_required = $this->input->post('video_source');

                        //
                        $data_to_insert['isdoctohandbook'] = $this->input->post('isdoctohandbook') ? $this->input->post('isdoctohandbook') : 0;
                        $data_to_insert['is_required'] = $this->input->post('isRequired') ? $this->input->post('isRequired') : 0;


                        if ($video_required != 'not_required') {
                            $video_source = $this->input->post('video_source');

                            if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                                $random = generateRandomString(5);
                                $company_id = $data['session']['company_detail']['sid'];
                                $target_file_name = basename($_FILES["video_upload"]["name"]);
                                $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                                $target_dir = "assets/uploaded_videos/";
                                $target_file = $target_dir . $file_name;
                                $filename = $target_dir . $company_id;

                                if (!file_exists($filename)) {
                                    mkdir($filename);
                                }

                                if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                    redirect('hr_documents_management/generate_new_document', 'refresh');
                                }

                                $video_url = $file_name;
                            } else {
                                $video_url = $this->input->post('yt_vm_video_url');

                                if ($video_source == 'youtube') {
                                    $url_prams = array();
                                    parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_url = $url_prams['v'];
                                    } else {
                                        $video_url = '';
                                    }
                                } else {
                                    $video_url = $this->vimeo_get_id($video_url);
                                }
                            }

                            $data_to_insert['video_source'] = $video_source;
                            $data_to_insert['video_url'] = $video_url;
                            $data_to_insert['video_required'] = 1;
                            $new_history_data['video_required'] = 1;
                            $new_history_data['video_source'] = $video_source;
                            $new_history_data['video_url'] = $video_url;
                        } else {
                            $data_to_insert['video_required'] = 0;
                            $new_history_data['video_required'] = 0;
                        }

                        if (!isset($_POST['categories']) && isset($_POST['visible_to_payroll'])) {
                            $data_to_insert['visible_to_payroll'] = 1;
                        } else {
                            $data_to_insert['visible_to_payroll'] = 0;
                        }

                        $post = $this->input->post(NULL, true);
                        //
                        $data_to_insert['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
                        $data_to_insert['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
                        $data_to_insert['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
                        $data_to_insert['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;
                        // Assign & Send document
                        // Set
                        $aType = $this->input->post('assignAndSendDocument', true);
                        $aDate = $this->input->post('assignAndSendCustomDate', true);
                        $aDay = $this->input->post('assignAndSendCustomDay', true);
                        $aTime = $this->input->post('assignAndSendCustomTime', true);
                        $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
                        //
                        $data_to_insert['assign_type'] = $aType;
                        $data_to_insert['assign_date'] = $aDate;
                        $data_to_insert['assign_time'] = $aTime;
                        //
                        if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_insert['assign_type'] = 'none';
                        //
                        if (empty($aDate) && empty($aDay)) $data_to_insert['assign_date'] = null;
                        if (empty($aTime)) $data_to_insert['assign_time'] = null;
                        //
                        if ($aType == 'weekly' && !empty($aDay)) $data_to_insert['assign_date'] = $aDay;
                        //
                        if ($aEmployees && count($aEmployees)) {
                            //
                            if (in_array('-1', $aEmployees)) $data_to_insert['assigned_employee_list'] = 'all';
                            else $data_to_insert['assigned_employee_list'] = json_encode($aEmployees);
                        }

                        //
                        $managersList = $this->input->post('managersList', true);
                        if ($managersList && sizeof($managersList)) {
                            $data_to_insert['managers_list'] = implode(',', $managersList);
                        }
                        // Approver Flow
                        $data_to_insert['has_approval_flow'] = 0;
                        $data_to_insert['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
                        //
                        if ($post['has_approval_flow'] == 'on') {
                            $data_to_insert['has_approval_flow'] = 1;
                            $data_to_insert['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $data_to_insert['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }
                        // Document Settings - Confidential
                        $data_to_insert['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                        //
                        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                        //
                        $data_to_insert['confidential_employees'] = NULL;
                        //
                        if ($post['confidentialSelectedEmployees']) {
                            $data_to_insert['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                        }

                        $insert_id = $this->hr_documents_management_model->insert_document_record($data_to_insert);

                        if (isset($_POST['document_group_assignment'])) {
                            $document_group_assignment = $this->input->post('document_group_assignment');

                            if (!empty($document_group_assignment)) {
                                foreach ($document_group_assignment as $key => $group_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['group_sid'] = $group_sid;
                                    $data_to_insert['document_sid'] = $insert_id;
                                    $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                                }
                            }
                        }
                        if (isset($_POST['categories'])) {
                            $document_category_assignment = $this->input->post('categories');

                            if (!empty($document_category_assignment)) {
                                foreach ($document_category_assignment as $key => $category_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['category_sid'] = $category_sid;
                                    $data_to_insert['document_sid'] = $insert_id;
                                    $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                                }
                            }
                        }

                        $authorized_signature_required = $this->input->post('auth_sign_sid');

                        if ($authorized_signature_required > 0) {
                            $update_authorized_signature = array();
                            $update_authorized_signature['document_sid'] = $insert_id;
                            $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
                        }
                        // Assigner handling
                        $new_history_data['has_approval_flow'] = 0;
                        $new_history_data['document_approval_note'] = $new_history_data['document_approval_employees'] = '';
                        // Assigner handling
                        if ($post['has_approval_flow'] == 'on') {
                            $new_history_data['has_approval_flow'] = 1;
                            $new_history_data['document_approval_employees'] = isset($post['assigner']) && $post['assigner'] ? implode(',', $post['assigner']) : '';
                            $new_history_data['document_approval_note'] = $post['assigner_note'];
                        }

                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data['documents_management_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['employer_sid'] = $employer_sid;
                        $new_history_data['document_title'] = $document_title;
                        $new_history_data['document_description'] = $document_description;
                        $new_history_data['document_type'] = 'generated';
                        $new_history_data['date_created'] = date('Y-m-d H:i:s');
                        $new_history_data['update_by_sid'] = $employer_sid;
                        $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $new_history_data['download_required'] = $this->input->post('download_required');
                        $new_history_data['signature_required'] = $this->input->post('signature_required');
                        $new_history_data['automatic_assign_in'] = !empty($this->input->post('assign-in')) ? $this->input->post('assign-in') : 0;
                        $this->hr_documents_management_model->insert_document_management_history($new_history_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Generated Successfully!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    // The below function is not working properly
    // working not completed
    public function upload_new_offer_letter()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_upload_generate_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Upload a Offer letter / Pay Plans';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);
            $data['document_groups'] = $groups;
            $pre_assigned_groups = array();
            $data['pre_assigned_groups'] = $pre_assigned_groups;
            $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($company_sid, 1);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/upload_new_offer_letter');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'upload_offer_letter':

                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $acknowledgment_required = $this->input->post('acknowledgment_required');
                        $download_required = $this->input->post('download_required');
                        $signature_required = $this->input->post('signature_required');

                        if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                        } else {
                            $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);
                        }

                        if (isset($_FILES['document']['name'])) {
                            $uploaded_document_original_name = $_FILES['document']['name'];
                        }

                        $file_info = pathinfo($uploaded_document_original_name);

                        $offer_letter_data = array();
                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['employer_sid'] = $employer_sid;
                        $offer_letter_data['letter_type'] = 'uploaded';
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        $offer_letter_data['uploaded_document_s3_name'] = $uploaded_document_s3_name;
                        $offer_letter_data['uploaded_document_original_name'] = $uploaded_document_original_name;
                        $offer_letter_data['archive'] = 0;
                        $offer_letter_data['acknowledgment_required'] = $acknowledgment_required;
                        $offer_letter_data['download_required'] = $download_required;
                        $offer_letter_data['signature_required'] = $signature_required;
                        if (!empty($this->input->post('sort_order')))
                            $offer_letter_data['sort_order'] = $this->input->post('sort_order');
                        else
                            $offer_letter_data['sort_order'] = 1;
                        $offer_letter_data['created_date'] = date('Y-m-d H:i:s');
                        $insert_id = $this->hr_documents_management_model->add_new_offer_letter($offer_letter_data);

                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data = array();
                        $new_history_data['offer_letter_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['employer_sid'] = $employer_sid;
                        $new_history_data['letter_name'] = $letter_name;
                        $new_history_data['letter_body'] = htmlentities($letter_body);
                        $new_history_data['created_date'] = date('Y-m-d H:i:s');
                        $new_history_data['update_by_sid'] = $employer_sid;
                        $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $new_history_data['download_required'] = $this->input->post('download_required');
                        $new_history_data['signature_required'] = $this->input->post('signature_required');
                        $this->hr_documents_management_model->insert_offer_letter_history($new_history_data);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully Created!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function generate_new_offer_letter()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_offer_letter'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'New Offer Letter / Pay Plan Template';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            //
            $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
            $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
            //
            $employeesList = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            $data['employeesList'] = $employeesList;
            //
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/generate_new_offer_letter');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                $new_history_data = array();

                switch ($perform_action) {
                    case 'generate_new_offer_letter':
                        $assign_manager = '';
                        $assign_department = '';
                        $assign_teams = '';
                        $assign_employees = '';
                        $assign_roles = '';

                        if (isset($_POST['managers']) && !empty($_POST['managers'])) {
                            $assign_manager = implode(',', $_POST['managers']);
                        }

                        if (isset($_POST['roles']) && !empty($_POST['roles'])) {
                            $assign_roles = implode(',', $_POST['roles']);
                        }

                        if (isset($_POST['departments']) && !empty($_POST['departments'])) {
                            $assign_department = implode(',', $_POST['departments']);
                        }

                        if (isset($_POST['teams']) && !empty($_POST['teams'])) {
                            $assign_teams = implode(',', $_POST['teams']);
                        }

                        if (isset($_POST['employees']) && !empty($_POST['employees'])) {
                            $assign_employees = implode(',', $_POST['employees']);
                        }

                        $visible_to_payroll = isset($_POST['visible_to_payroll']) && $_POST['visible_to_payroll'] == 'yes' ? 1 : 0;
                        //
                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $signature_required = $this->input->post('signature_required');

                        $offer_letter_data = array();
                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['employer_sid'] = $employer_sid;
                        $offer_letter_data['letter_type'] = 'generated';
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        // $offer_letter_data['upload_letter_path'] = htmlentities($letter_body);
                        $offer_letter_data['archive'] = 0;
                        $offer_letter_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $offer_letter_data['download_required'] = $this->input->post('download_required');
                        $offer_letter_data['signature_required'] = $this->input->post('signature_required');
                        if (!empty($this->input->post('sort_order')))
                            $offer_letter_data['sort_order'] = $this->input->post('sort_order');
                        else
                            $offer_letter_data['sort_order'] = 1;
                        $offer_letter_data['created_date'] = date('Y-m-d H:i:s');

                        //Automatically assign after Days
                        $offer_letter_data['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
                        if ($offer_letter_data == 'days') {
                            $offer_letter_data['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
                        } else {
                            $offer_letter_data['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
                        }
                        //
                        $offer_letter_data['signers'] = $assign_manager;
                        $offer_letter_data['allowed_teams'] = $assign_teams;
                        $offer_letter_data['allowed_departments'] = $assign_department;
                        $offer_letter_data['allowed_employees'] = $assign_employees;
                        $offer_letter_data['is_available_for_na'] = $assign_roles;
                        $offer_letter_data['visible_to_payroll'] = $visible_to_payroll;
                        //
                        // Approval Flow
                        $offer_letter_data['has_approval_flow'] = 0;
                        $offer_letter_data['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
                        //
                        if ($_POST['has_approval_flow'] == 'on') {
                            $offer_letter_data['has_approval_flow'] = 1;
                            $offer_letter_data['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $offer_letter_data['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }

                        $post = $this->input->post(NULL, true);

                        // Document Settings - Confidential
                        $offer_letter_data['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                        //
                        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                        //
                        $offer_letter_data['confidential_employees'] = NULL;
                        //
                        if ($post['confidentialSelectedEmployees']) {
                            $offer_letter_data['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                        }
                        //
                        $insert_id = $this->hr_documents_management_model->add_new_offer_letter($offer_letter_data);
                        //
                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data['offer_letter_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['employer_sid'] = $employer_sid;
                        $new_history_data['letter_name'] = $letter_name;
                        $new_history_data['is_confidential'] = $offer_letter_data['is_confidential'];
                        $new_history_data['confidential_employees'] = $offer_letter_data['confidential_employees'];
                        $new_history_data['letter_body'] = htmlentities($letter_body);
                        $new_history_data['created_date'] = date('Y-m-d H:i:s');
                        $new_history_data['update_by_sid'] = $employer_sid;
                        $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $new_history_data['download_required'] = $this->input->post('download_required');
                        $new_history_data['signature_required'] = $this->input->post('signature_required');
                        $new_history_data['automatic_assign_type'] = $offer_letter_data['automatic_assign_type'];
                        $new_history_data['automatic_assign_in'] = $offer_letter_data['automatic_assign_in'];

                        //
                        $this->hr_documents_management_model->insert_offer_letter_history($new_history_data);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully Created!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_hr_document($sid = NULL, $redirect = 'index')
    {
        if ($this->session->userdata('logged_in') && $sid != NULL) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_upload_generate_document'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_email = $data['session']['employer_detail']['email'];
            $employer_first_name = $data['session']['employer_detail']['first_name'];
            $employer_last_name = $data['session']['employer_detail']['last_name'];

            $data['title'] = 'Generate A New Document';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;
            $data['employer_email'] = $employer_email;
            $data['employer_first_name'] = $employer_first_name;
            $data['employer_last_name'] = $employer_last_name;
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);
            $data['document_groups'] = $groups;
            $pre_assigned_groups = array();
            $pre_assigned_group_data = $this->hr_documents_management_model->get_all_group_2_document($sid);

            if (!empty($pre_assigned_group_data)) {
                foreach ($pre_assigned_group_data as $pagd) {
                    $pre_assigned_groups[] = $pagd['group_sid'];
                }
            }
            $pre_assigned_categories = array();
            $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($company_sid, 1);
            $pre_assigned_category_data = $this->hr_documents_management_model->get_all_category_2_document($sid);

            if (!empty($pre_assigned_category_data)) {
                foreach ($pre_assigned_category_data as $pagd) {
                    $pre_assigned_categories[] = $pagd['category_sid'];
                }
            }
            $data['assigned_categories'] = $pre_assigned_categories;

            $data['pre_assigned_groups'] = $pre_assigned_groups;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $document_info = $this->hr_documents_management_model->get_hr_document_details($company_sid, $sid);
            if ($this->form_validation->run() == false) {
                // print_r($document_info);
                //
                if (!empty($document_info)) {
                    $data['document_info'] = $document_info;
                    $document_type = $document_info['document_type'];
                    $data['title'] = $document_type == 'uploaded' ? 'Modify Uploaded HR Document' : 'Modify Generated HR Document';

                    if ($document_type == 'generated') {
                        $authorized_signature_exist = $this->hr_documents_management_model->is_authorized_signature_exist($sid, $company_sid);
                        $data['authorized_signature'] = $authorized_signature_exist;
                    }

                    $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(
                        $data['company_sid'],
                        $data['employer_sid']
                    );
                    // Get departments & teams
                    $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
                    $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
                    //
                    $data['pre_assigned_employees'] = isset($document_info['managers_list']) && $document_info['managers_list'] != null ? explode(',', $document_info['managers_list']) : array();
                    //
                    $this->load->view('main/header', $data);

                    if ($document_type == 'hybrid_document') {
                        $this->load->view('hr_documents_management/hybrid/edit');
                    } else if ($document_type == 'uploaded') {
                        $this->load->view('hr_documents_management/upload_new_document');
                    } else {
                        $this->load->view('hr_documents_management/generate_new_document');
                    }

                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> HR Document not found!');
                    redirect('hr_documents_management', 'refresh');
                }
            } else {
                $perform_action = $this->input->post('perform_action');
                $type = $this->input->post('type');

                switch ($perform_action) {
                    case 'update_document':

                        $document_name = $this->input->post('document_title');
                        $document_description = $this->input->post('document_description');

                        $document_description = $document_description ? magicCodeCorrection($document_description) : $document_description;

                        $video_required = $this->input->post('video_source');
                        $document_description = htmlentities($document_description);
                        // $action_required = $this->input->post('action_required');

                        if ($document_info["fillable_document_slug"]) {
                            $document_name = $document_info["document_title"];
                            $document_description = $document_info["document_description"];
                        }


                        $data_to_update = array();
                        $data_to_update['isdoctolibrary'] = $this->input->post('isdoctolibrary') ? $this->input->post('isdoctolibrary') : 0;
                        $data_to_update['visible_to_document_center'] = 0;
                        if (isset($_FILES['document']['name']) && !empty($_FILES['document']['name'])) {
                            $s3_file_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);
                            $original_name = $_FILES['document']['name'];

                            if ($s3_file_name != 'error') {
                                $data_to_update['uploaded_document_original_name'] = $original_name;
                                $data_to_update['uploaded_document_s3_name'] = $s3_file_name;
                            }


                            $file_info = pathinfo($original_name);

                            if (isset($file_info['extension'])) {
                                $data_to_update['uploaded_document_extension'] = $file_info['extension'];
                            }
                        }
                        //
                        $data_to_update['isdoctohandbook'] = $this->input->post('isdoctohandbook') ? $this->input->post('isdoctohandbook') : 0;

                        $data_to_update['is_required'] = $this->input->post('isRequired') ? $this->input->post('isRequired') : 0;



                        if ($video_required != 'not_required') {
                            $video_source = $this->input->post('video_source');

                            if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                                $random = generateRandomString(5);
                                $company_id = $data['session']['company_detail']['sid'];
                                $target_file_name = basename($_FILES["video_upload"]["name"]);
                                $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                                $target_dir = "assets/uploaded_videos/";
                                $target_file = $target_dir . $file_name;
                                $filename = $target_dir . $company_id;

                                if (!file_exists($filename)) {
                                    mkdir($filename);
                                }

                                if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                                    $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                                } else {

                                    $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                                    redirect('hr_documents_management/edit_hr_document', 'refresh');
                                }

                                $video_url = $file_name;
                            } else {
                                $video_url = $this->input->post('yt_vm_video_url');

                                if ($video_source == 'youtube') {
                                    $url_prams = array();
                                    parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                                    if (isset($url_prams['v'])) {
                                        $video_url = $url_prams['v'];
                                    } else {
                                        $video_url = '';
                                    }
                                } else {
                                    $video_url = $this->vimeo_get_id($video_url);
                                }
                            }
                            if (!empty($video_url)) {
                                $data_to_update['video_source'] = $video_source;
                                $data_to_update['video_url'] = $video_url;
                                $data_to_update['video_required'] = 1;
                            }
                        } else {
                            $data_to_update['video_required'] = 0;
                        }

                        if (isset($_POST['visible_to_payroll'])) {
                            $data_to_update['visible_to_payroll'] = 1;
                        } else {
                            $data_to_update['visible_to_payroll'] = 0;
                        }


                        $post = $this->input->post(NULL, true);
                        //
                        $data_to_update['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
                        $data_to_update['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
                        $data_to_update['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
                        $data_to_update['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;

                        // Assign & Send document
                        // Set
                        $aType = $this->input->post('assignAndSendDocument', true);
                        $aDate = $this->input->post('assignAndSendCustomDate', true);
                        $aDay = $this->input->post('assignAndSendCustomDay', true);
                        $aTime = $this->input->post('assignAndSendCustomTime', true);
                        $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
                        //
                        $data_to_update['assign_type'] = $aType;
                        $data_to_update['assign_date'] = $aDate;
                        $data_to_update['assign_time'] = $aTime;
                        //
                        if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_update['assign_type'] = 'none';
                        //
                        if (empty($aDate) && empty($aDay)) $data_to_update['assign_date'] = null;
                        if (empty($aTime)) $data_to_update['assign_time'] = null;
                        //
                        if ($aType == 'weekly' && !empty($aDay)) $data_to_update['assign_date'] = $aDay;
                        if ($aType == 'weekly' && empty($aDay)) $data_to_update['assign_date'] = null;
                        //
                        if ($aEmployees && count($aEmployees)) {
                            //
                            if (in_array('-1', $aEmployees)) $data_to_update['assigned_employee_list'] = 'all';
                            else $data_to_update['assigned_employee_list'] = json_encode($aEmployees);
                        }

                        $authorized_signature_required = $this->input->post('auth_sign_sid');

                        if ($authorized_signature_required > 0) {
                            $update_authorized_signature = array();
                            $update_authorized_signature['document_sid'] = $sid;
                            $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
                        }

                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data = array();
                        $new_history_data = $this->hr_documents_management_model->get_hr_document_details($company_sid, $sid);
                        $new_history_data['documents_management_sid'] = $sid;
                        $new_history_data['update_by_sid'] = $employer_sid;
                        unset($new_history_data['sid']);
                        $this->hr_documents_management_model->insert_document_management_history($new_history_data);

                        $data_to_update['document_title'] = $document_name;
                        $data_to_update['document_description'] = $document_description;
                        $data_to_update['onboarding'] = $this->input->post('onboarding');
                        $data_to_update['download_required'] = $this->input->post('download_required');
                        $data_to_update['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $data_to_update['signature_required'] = $this->input->post('signature_required');
                        $data_to_update['sort_order'] = $this->input->post('sort_order');
                        // $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in')) ? $this->input->post('assign-in') : 0;
                        $data_to_update['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
                        if ($data_to_update['automatic_assign_type'] == 'days') {
                            $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
                        } else {
                            $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
                        }

                        //
                        $managersList = $this->input->post('managersList', true);
                        if ($managersList && sizeof($managersList)) {
                            $data_to_update['managers_list'] = implode(',', $managersList);
                        }

                        if (!empty($this->input->post('document_url'))) {
                            $data_to_update['uploaded_document_original_name'] = $this->input->post('document_name');
                            $data_to_update['uploaded_document_s3_name'] = $this->input->post('document_url');
                        }
                        // Approver Flow
                        $data_to_update['has_approval_flow'] = 0;
                        $data_to_update['document_approval_note'] = $data_to_update['document_approval_employees'] = '';
                        // 
                        if ($post['has_approval_flow'] == 'on') {
                            $data_to_update['has_approval_flow'] = 1;
                            $data_to_update['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $data_to_update['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }
                        //
                        // Document Settings - Confidential
                        $data_to_update['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                        //
                        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                        //
                        $data_to_update['confidential_employees'] = NULL;
                        //
                        if ($post['confidentialSelectedEmployees']) {
                            $data_to_update['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                        }

                        $this->hr_documents_management_model->update_documents($sid, $data_to_update, 'documents_management');
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Info Successfully Updated!');
                        $this->hr_documents_management_model->delete_group_2_document($sid);

                        if (isset($_POST['document_group_assignment'])) {
                            $document_group_assignment = $this->input->post('document_group_assignment');

                            if (!empty($document_group_assignment)) {
                                foreach ($document_group_assignment as $key => $group_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['group_sid'] = $group_sid;
                                    $data_to_insert['document_sid'] = $sid;
                                    $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                                }
                            }
                        }
                        $this->hr_documents_management_model->delete_category_2_document($sid);
                        if (isset($_POST['categories'])) {
                            $document_category_assignment = $this->input->post('categories');

                            if (!empty($document_category_assignment)) {
                                foreach ($document_category_assignment as $key => $category_sid) {
                                    $data_to_insert = array();
                                    $data_to_insert['category_sid'] = $category_sid;
                                    $data_to_insert['document_sid'] = $sid;
                                    $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                                }
                            }
                        }

                        // Check for pay plan
                        if ($this->input->post('to_pay_plan') == 'yes') {
                            $this->convertDocumentToPayPlan();
                            exit(0);
                        }

                        if ($redirect == 'index') {
                            redirect('hr_documents_management', 'refresh');
                        } else {
                            redirect('hr_documents_management/archived_documents', 'refresh');
                        }

                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_offer_letter($sid = NULL)
    {
        if ($this->session->userdata('logged_in') && $sid != NULL) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_offer_letter'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            //
            $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
            $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
            //
            $employeesList = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            $data['employeesList'] = $employeesList;
            //
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $data['title'] = 'Modify an Offer Letter / Pay Plan';

            if ($this->form_validation->run() == false) {
                $document_info = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $sid);

                if (!empty($document_info)) {
                    $data['document_info'] = $document_info;
                    $this->load->view('main/header', $data);
                    $this->load->view('hr_documents_management/generate_new_offer_letter');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Offer letter not found!');
                    redirect('hr_documents_management', 'refresh');
                }
            } else {
                $perform_action = $this->input->post('perform_action');
                //
                switch ($perform_action) {
                    case 'update_offer_letter':
                        $assign_manager = '';
                        $assign_department = '';
                        $assign_teams = '';
                        $assign_employees = '';
                        $assign_roles = '';

                        if (isset($_POST['managers']) && !empty($_POST['managers'])) {
                            $assign_manager = implode(',', $_POST['managers']);
                        }

                        if (isset($_POST['roles']) && !empty($_POST['roles'])) {
                            $assign_roles = implode(',', $_POST['roles']);
                        }

                        if (isset($_POST['departments']) && !empty($_POST['departments'])) {
                            $assign_department = implode(',', $_POST['departments']);
                        }

                        if (isset($_POST['teams']) && !empty($_POST['teams'])) {
                            $assign_teams = implode(',', $_POST['teams']);
                        }

                        if (isset($_POST['employees']) && !empty($_POST['employees'])) {
                            $assign_employees = implode(',', $_POST['employees']);
                        }

                        $visible_to_payroll = isset($_POST['visible_to_payroll']) && $_POST['visible_to_payroll'] == 'yes' ? 1 : 0;


                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $signature_required = $this->input->post('signature_required');

                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data = array();
                        $new_history_data = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $sid);
                        $new_history_data['offer_letter_sid'] = $sid;
                        $new_history_data['update_by_sid'] = $employer_sid;
                        unset($new_history_data['sid']);
                        $this->hr_documents_management_model->insert_offer_letter_history($new_history_data);

                        $offer_letter_data = array();
                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['employer_sid'] = $employer_sid;
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        $offer_letter_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $offer_letter_data['download_required'] = $this->input->post('download_required');
                        $offer_letter_data['signature_required'] = $signature_required;
                        $offer_letter_data['sort_order'] = $this->input->post('sort_order');

                        //Automatically assign after Days
                        $offer_letter_data['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
                        if ($offer_letter_data == 'days') {
                            $offer_letter_data['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
                        } else {
                            $offer_letter_data['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
                        }

                        // Approval Flow
                        $offer_letter_data['has_approval_flow'] = 0;
                        $offer_letter_data['document_approval_note'] = $offer_letter_data['document_approval_employees'] = '';
                        // Approval Flow
                        if ($_POST['has_approval_flow'] == 'on') {
                            $offer_letter_data['has_approval_flow'] = 1;
                            $offer_letter_data['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                            $offer_letter_data['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
                        }


                        $post = $this->input->post(NULL, true);

                        // Document Settings - Confidential
                        $offer_letter_data['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                        //
                        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                        //
                        $offer_letter_data['confidential_employees'] = NULL;
                        //
                        if ($post['confidentialSelectedEmployees']) {
                            $offer_letter_data['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                        }

                        //
                        $offer_letter_data['signers'] = $assign_manager;
                        $offer_letter_data['allowed_teams'] = $assign_teams;
                        $offer_letter_data['allowed_departments'] = $assign_department;
                        $offer_letter_data['allowed_employees'] = $assign_employees;
                        $offer_letter_data['is_available_for_na'] = $assign_roles;
                        $offer_letter_data['visible_to_payroll'] = $visible_to_payroll;
                        //
                        $this->hr_documents_management_model->update_documents($sid, $offer_letter_data, 'offer_letter');

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully Updated!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_uploaded_offer_letter($sid = NULL)
    {
        if ($this->session->userdata('logged_in') && $sid != NULL) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'manage_ems', 'add_edit_offer_letter'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $data['title'] = 'Modify an Offer Letter / Pay Plan';

            if ($this->form_validation->run() == false) {
                $document_info = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $sid);

                if (!empty($document_info)) {
                    $data['document_info'] = $document_info;
                    $this->load->view('main/header', $data);
                    $this->load->view('hr_documents_management/upload_new_offer_letter');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Offer letter not found!');
                    redirect('hr_documents_management', 'refresh');
                }
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'update_offer_letter':

                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $signature_required = $this->input->post('signature_required');


                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data = array();
                        $new_history_data = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $sid);
                        $new_history_data['offer_letter_sid'] = $sid;
                        $new_history_data['update_by_sid'] = $employer_sid;
                        unset($new_history_data['sid']);
                        $this->hr_documents_management_model->insert_offer_letter_history($new_history_data);

                        $offer_letter_data = array();
                        if (isset($_FILES['document']['name']) && $_FILES['document']['name'] != '') {
                            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            } else {
                                $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $letter_name), $employer_sid, AWS_S3_BUCKET_NAME);
                            }
                            $uploaded_document_original_name = $_FILES['document']['name'];
                            //
                            $offer_letter_data['uploaded_document_s3_name'] = $uploaded_document_s3_name;
                            $offer_letter_data['uploaded_document_original_name'] = $uploaded_document_original_name;
                            $file_info = pathinfo($uploaded_document_original_name);
                        }

                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['employer_sid'] = $employer_sid;
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        $offer_letter_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $offer_letter_data['download_required'] = $this->input->post('download_required');
                        $offer_letter_data['signature_required'] = $signature_required;
                        $offer_letter_data['sort_order'] = $this->input->post('sort_order');
                        $this->hr_documents_management_model->update_documents($sid, $offer_letter_data, 'offer_letter');

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully Updated!');
                        redirect('hr_documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            if ($this->form_validation->run() == false) {
                //Handle Get
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'get_generated_document_preview':
                        $document_sid = $this->input->post('document_sid');
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $source = $this->input->post('source');
                        $fetch_data = $this->input->post('fetch_data');
                        $user_type = empty($user_type) ? null : $user_type;
                        $user_sid = empty($user_sid) ? null : $user_sid;

                        if ($source == 'generated') {
                            if ($fetch_data == 'original') {
                                $document_info = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                            } else if ($fetch_data == 'modified') {
                                $document_info = $this->hr_documents_management_model->get_assigned_document_record($user_type, $user_sid, $document_sid);
                            } else {
                                $history_sid = $this->input->post('history_sid');
                                $document_info = $this->hr_documents_management_model->get_assigned_document_history_record($user_type, $user_sid, $document_sid, $history_sid);
                            }
                        }

                        if ($source == 'uploaded') {
                            if ($fetch_data == 'original') {
                                $document_info = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                            } else if ($fetch_data == 'modified') {
                                $document_info = $this->hr_documents_management_model->get_assigned_document_record($user_type, $user_sid, $document_sid);
                            } else {
                                $history_sid = $this->input->post('history_sid');
                                $document_info = $this->hr_documents_management_model->get_assigned_document_history_record($user_type, $user_sid, $document_sid, $history_sid);
                            }

                            //
                            echo $this->load->view(
                                'hr_documents_management/uploaded_document_preview_partial',
                                $document_info,
                                true
                            );
                            return;
                        }

                        if ($source == 'hybrid_document') {
                            if ($fetch_data == 'original') {
                                $document_info = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                            } else if ($fetch_data == 'modified') {
                                $document_info = $this->hr_documents_management_model->get_assigned_document_record($user_type, $user_sid, $document_sid);
                            } else {
                                $history_sid = $this->input->post('history_sid');
                                $document_info = $this->hr_documents_management_model->get_assigned_document_history_record($user_type, $user_sid, $document_sid, $history_sid);
                            }
                            //
                            if (!empty($document_info)) {
                                $document_content = $document_info['document_description'];
                                $document_info['document_body'] = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_content, $document_sid);
                            }
                            //
                            echo $this->load->view(
                                'hr_documents_management/hybird_document_preview_partial',
                                $document_info,
                                true
                            );
                            return;
                        }

                        if ($source == 'offer') {
                            $document_info = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $document_sid);
                        }
                        if (!empty($document_info)) {
                            $document_content = $source == 'offer' ? $document_info['letter_body'] : $document_info['document_description'];
                            $document = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_content, $document_sid);
                            $view_data = array();
                            $view_data['document_title'] = $source == 'offer' ? $document_info['letter_name'] : $document_info['document_title'];
                            $view_data['document_body'] = $document;
                            echo $this->load->view('hr_documents_management/generated_document_preview_partial', $view_data, true);
                        }

                        break;

                    case 'download_hybird_document':
                        $pdf_content = $this->input->post('base64');
                        $file_name = $this->input->post('file_name');
                        $s3_path = $this->input->post('s3_path');
                        //
                        // $pdf_decoded = base64_decode ($pdf_content);
                        $path = ROOTPATH . '/temp_files/hybird_document/' . time() . '/';
                        $zipath = ROOTPATH . '/temp_files/hybird_document/';
                        //
                        if (!file_exists($path)) {
                            //
                            mkdir($path, 0777, true);
                        }
                        //
                        $handler = fopen($path . 'section_2.pdf', 'w');
                        fwrite($handler, base64_decode(str_replace('data:application/pdf;base64,', '', $pdf_content), true));
                        fclose($handler);
                        //
                        downloadFileFromAWS(
                            getFileName(
                                $path . 'section_1',
                                AWS_S3_BUCKET_URL . $s3_path
                            ),
                            AWS_S3_BUCKET_URL . $s3_path
                        );
                        //
                        $zipFileName = time() . '.zip';
                        $this->load->library('zip');
                        $this->zip->read_dir($path, FALSE);
                        $this->zip->archive($zipath . $zipFileName);
                        echo base_url('hr_documents_management/downloadHybridDocument/') . $zipFileName;
                        return;
                        break;
                }
            }
        }
    }

    public function documents_assignment($user_type = NULL, $user_sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {

            $data['session'] = $this->session->userdata('logged_in');
            // loadCachedFile('documents_assignment_'.($user_type).'_'.($user_sid).'', $data['session']);
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            //
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); 
            // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $eeid = $employer_sid = $data['session']['employer_detail']['sid'];
            $pp_flag = $data['session']['employer_detail']['pay_plan_flag'] && !$data['session']['employer_detail']['access_level_plus'] ? true : false;

            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            //
            $emp_sid = $employer_sid;
            $user_info = array();
            $active_groups = array();
            $in_active_groups = array();
            $group_ids = array();
            $group_docs = array();
            $document_ids = array();
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_email = $data['session']['employer_detail']['email'];
            $employer_first_name = $data['session']['employer_detail']['first_name'];
            $employer_last_name = $data['session']['employer_detail']['last_name'];
            $data['company_name'] = $company_name;

            // Check and assign GID
            $this->hr_documents_management_model->setGID(
                $user_type,
                $user_sid,
                $company_sid,
                $employer_sid
            );

            $approval_documents = $this->hr_documents_management_model->get_user_approval_pending_documents($user_type, $user_sid);
            $data['approval_documents'] = array_column($approval_documents, "document_sid");
            $approval_offer_letters = $this->hr_documents_management_model->get_user_approval_pending_offer_letters($user_type, $user_sid);
            $data['approval_offer_letters'] = array_column($approval_offer_letters, "document_sid");

            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                        redirect('employee_management', 'refresh');
                    }

                    // Commented on 09/07/2020
                    // Redirects non Plus and PPF
                    // if (!$data['session']['employer_detail']['access_level_plus'] && !$data['session']['employer_detail']['pay_plan_flag']) {
                    //     $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                    //     redirect('employee_management', 'refresh');
                    // }

                    $data = employee_right_nav($user_sid, $data);
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                    $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);

                    $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $user_sid, $user_type, 'single_download');
                    
                    break;
                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $data = applicant_right_nav($user_sid, $jobs_listing);
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($user_type)
                    );

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $applicant_info['sid'];

                    $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $user_sid, $user_type, 'single_download');
                    break;
            }
            $data['EmployeeSid'] = $user_sid;
            $data['Type'] = $user_type;

            // Check for post
            if (isset($_POST) && sizeof($_POST)) {
                $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
                if ($this->form_validation->run() != false) {
                    $perform_action = $this->input->post('perform_action');
                    switch ($perform_action) {
                        case 'activate_uploaded_document':
                            $document_sid = $this->input->post('document_sid');
                            $document_type = $this->input->post('document_type');
                            $data_to_update = array();
                            $data_to_update['archive'] = 0;
                            $data_to_update['status'] = 1;
                            $this->hr_documents_management_model->update_documents($document_sid, $data_to_update, 'documents_assigned');
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Activated!');
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'archive_uploaded_document':
                            $document_sid = $this->input->post('document_sid');
                            $document_type = $this->input->post('document_type');
                            $this->hr_documents_management_model->update_documents($document_sid, array('archive' => 1), 'documents_assigned');
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Archived!');
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'assign_document':
                            $document_type = $this->input->post('document_type');
                            $document_sid = $this->input->post('document_sid');
                            $check_exist = $this->hr_documents_management_model->check_assigned_document($document_sid, $user_sid, $user_type);
                            $authorized_signature_required = $this->input->post('auth_sign_sid');

                            if ($authorized_signature_required > 0) {
                                $update_authorized_signature = array();
                                $update_authorized_signature['document_sid'] = $document_sid;
                                $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
                            }

                            if (!empty($check_exist)) {
                                $assignment_sid = $check_exist[0]['sid'];
                                $assigned_document = $this->hr_documents_management_model->get_assigned_document_details($company_sid, $assignment_sid);
                                unset($assigned_document['sid']);
                                unset($assigned_document['is_pending']);
                                $assigned_document['doc_sid'] = $assignment_sid;
                                $this->hr_documents_management_model->insert_documents_assignment_record_history($assigned_document);

                                $data_to_update = array();
                                $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                                $data_to_update['company_sid'] = $company_sid;
                                $data_to_update['assigned_date'] = date('Y-m-d H:i:s');
                                $data_to_update['assigned_by'] = $employer_sid;
                                $data_to_update['user_type'] = $user_type;
                                $data_to_update['user_sid'] = $user_sid;
                                $data_to_update['document_type'] = $document_type;
                                $data_to_update['document_sid'] = $document_sid;
                                $data_to_update['status'] = 1;
                                $data_to_update['document_original_name'] = $document['uploaded_document_original_name'];
                                $data_to_update['document_extension'] = $document['uploaded_document_extension'];
                                $data_to_update['document_s3_name'] = $document['uploaded_document_s3_name'];
                                $data_to_update['document_title'] = $document['document_title'];
                                $data_to_update['document_description'] = htmlentities($this->input->post('document_description'));
                                $data_to_update['acknowledged'] = NULL;
                                $data_to_update['acknowledged_date'] = NULL;
                                $data_to_update['downloaded'] = NULL;
                                $data_to_update['downloaded_date'] = NULL;
                                $data_to_update['uploaded'] = NULL;
                                $data_to_update['uploaded_date'] = NULL;
                                $data_to_update['uploaded_file'] = NULL;
                                $data_to_update['signature_timestamp'] = NULL;
                                $data_to_update['signature'] = NULL;
                                $data_to_update['signature_email'] = NULL;
                                $data_to_update['signature_ip'] = NULL;
                                $data_to_update['user_consent'] = 0;
                                $data_to_update['archive'] = 0;
                                $data_to_update['submitted_description'] = NULL;
                                $data_to_update['signature_base64'] = NULL;
                                $data_to_update['signature_initial'] = NULL;
                                $data_to_update['authorized_signature'] = NULL;
                                $data_to_update['authorized_signature_by'] = NULL;
                                $data_to_update['authorized_signature_date'] = NULL;
                                $data_to_update['download_required'] = $document['download_required'];
                                $data_to_update['signature_required'] = $document['signature_required'];
                                $data_to_update['acknowledgment_required'] = $document['acknowledgment_required'];
                                $data_to_update['is_required'] = $document['is_required'];
                                $data_to_update['fillable_document_slug'] = $document['fillable_document_slug'];
                                // $data_to_update['is_pending'] = 1;

                                $this->hr_documents_management_model->update_documents($assignment_sid, $data_to_update, 'documents_assigned');
                                // $this->hr_documents_management_model->deactivate_assign_authorized_documents($company_sid, $assignment_sid);

                            } else {
                                $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                                $data_to_insert = array();
                                $data_to_insert['company_sid'] = $company_sid;
                                $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                                $data_to_insert['assigned_by'] = $employer_sid;
                                $data_to_insert['user_type'] = $user_type;
                                $data_to_insert['user_sid'] = $user_sid;
                                $data_to_insert['document_type'] = $document_type;
                                $data_to_insert['document_sid'] = $document_sid;
                                $data_to_insert['status'] = 1;
                                $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                                $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                                $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                                $data_to_insert['document_title'] = $document['document_title'];
                                $data_to_insert['document_description'] = htmlentities($this->input->post('document_description'));
                                $data_to_insert['download_required'] = $document['download_required'];
                                $data_to_insert['signature_required'] = $document['signature_required'];
                                $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                                $data_to_insert['is_required'] = $document['is_required'];
                                $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];

                                $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            }

                            // Managers handling
                            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                $document['managers_list'],
                                $assignment_sid,
                                $company_sid,
                                $employer_sid
                            );
                            //
                            if ($user_type == 'employee') {
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['username'] = $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                //SMS Start
                                if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if ($company_sms_notification_status) {
                                        $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                        $sms_notify = 0;
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                        if ($sms_notify) {
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                            sendSMS(
                                                $contact_no,
                                                $sms_body,
                                                trim(ucwords(strtolower($replacement_array['company_name']))),
                                                $user_info['email'],
                                                $this,
                                                $sms_notify,
                                                $company_sid
                                            );
                                        }
                                    }
                                    $is_manual = get_document_type($assignment_sid);
                                    //
                                    if ($is_manual == 'no') {
                                        //
                                        $user_extra_info = array();
                                        $user_extra_info['user_sid'] = $user_sid;
                                        $user_extra_info['user_type'] = $user_type;
                                        //
                                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                                        if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                            //
                                            if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS3")) {
                                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                                            }
                                        }

                                        $this->hr_documents_management_model->update_employee($user_sid, array('document_sent_on' => date('Y-m-d H:i:s')));
                                    }
                                }
                            }
                            //
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');
                            redirect('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'remove_document':
                            $document_type = $this->input->post('document_type');
                            $document_sid = $this->input->post('document_sid');
                            //
                            $assigned = $this->hr_documents_management_model->getAssignedDocumentByIdAndEmployeeId(
                                $document_sid,
                                $user_sid
                            );
                            //
                            $assignInsertId = $assigned['sid'];
                            //
                            unset($assigned['sid']);
                            unset($assigned['is_pending']);
                            //
                            $h = $assigned;
                            $h['doc_sid'] = $assignInsertId;
                            //
                            $this->hr_documents_management_model->insert_documents_assignment_record_history($h);
                            //
                            $data = array();
                            $data['status'] = 0;
                            // $data['is_pending'] = 1;
                            $this->hr_documents_management_model->assign_revoke_assigned_documents($document_sid, $document_type, $user_sid, $user_type, $data);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'assign_w4': //W4 Form Active
                            $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
                            //
                            if (empty($w4_form_history)) {
                                $w4_data_to_insert = array();
                                $w4_data_to_insert['employer_sid'] = $user_sid;
                                $w4_data_to_insert['company_sid'] = $company_sid;
                                $w4_data_to_insert['user_type'] = $user_type;
                                $w4_data_to_insert['sent_status'] = 1;
                                $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $w4_data_to_insert['status'] = 1;
                                $w4_data_to_insert['last_assign_by'] = $eeid;

                                $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                            } else {
                                $w4_data_to_update                                          = array();
                                $w4_data_to_update['sent_date']                             = date('Y-m-d H:i:s');
                                $w4_data_to_update['status']                                = 1;
                                $w4_data_to_update['signature_timestamp']                   = NULL;
                                $w4_data_to_update['signature_email_address']               = NULL;
                                $w4_data_to_update['signature_bas64_image']                 = NULL;
                                $w4_data_to_update['init_signature_bas64_image']            = NULL;
                                $w4_data_to_update['ip_address']                            = NULL;
                                $w4_data_to_update['user_agent']                            = NULL;
                                $w4_data_to_update['uploaded_file']                         = NULL;
                                $w4_data_to_update['uploaded_by_sid']                       = 0;
                                $w4_data_to_update['user_consent']                          = 0;
                                $w4_data_to_update['last_assign_by']                          = $eeid;

                                $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_update);
                            }
                            //
                            if ($user_type == 'employee') {
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                //SMS Start
                                if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if ($company_sms_notification_status) {
                                        $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                        $sms_notify = 0;
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                        if ($sms_notify) {
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                            sendSMS(
                                                $contact_no,
                                                $sms_body,
                                                trim(ucwords(strtolower($replacement_array['company_name']))),
                                                $user_info['email'],
                                                $this,
                                                $sms_notify,
                                                $company_sid
                                            );
                                        }
                                    }
                                    //
                                    $user_extra_info = array();
                                    $user_extra_info['user_sid'] = $user_sid;
                                    $user_extra_info['user_type'] = $user_type;
                                    //
                                    $this->load->model('Hr_documents_management_model', 'HRDMM');
                                    if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                        //
                                        if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS4")) {
                                            log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                                        }
                                    }
                                }
                            }
                            //
                            $w4_sid = getVerificationDocumentSid($user_sid, $user_type, 'w4');
                            keepTrackVerificationDocument($security_sid, "employee", 'assign', $w4_sid, 'w4', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_w4': //W4 Form Deactive
                            $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
                            //
                            $w4_form_history['form_w4_ref_sid'] = $w4_form_history['sid'];
                            unset($w4_form_history['sid']);
                            $this->hr_documents_management_model->w4_forms_history($w4_form_history);
                            //
                            $this->hr_documents_management_model->deactivate_w4_forms($user_type, $user_sid);
                            //
                            $w4_sid = getVerificationDocumentSid($user_sid, $user_type, 'w4');
                            keepTrackVerificationDocument($security_sid, "employee", 'revoke', $w4_sid, 'w4', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'update_w4_employer_section': //W4 Form Deactive
                            $emp_name                   = $this->input->post('emp_name');
                            $user_sid                   = $this->input->post('user_sid');
                            $user_type                  = $this->input->post('user_type');
                            $emp_address                = $this->input->post('emp_address');
                            $company_sid                = $this->input->post('company_sid');
                            $emp_identity_num           = $this->input->post('EIN');
                            $first_date_of_employment   = $this->input->post('first_date_of_employment');

                            if (!empty($first_date_of_employment)) {
                                $first_date_of_employment = DateTime::createFromFormat('m-d-Y', $first_date_of_employment)->format('Y-m-d');
                            }

                            $update_w4_employer                                 = array();
                            $update_w4_employer['emp_name']                     = $emp_name;
                            $update_w4_employer['emp_address']                  = $emp_address;
                            $update_w4_employer['emp_identification_number']    = $emp_identity_num;
                            $update_w4_employer['first_date_of_employment']     = $first_date_of_employment;

                            $this->hr_documents_management_model->update_w4_employer_info($user_type, $user_sid, $company_sid, $update_w4_employer);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> W4 Employer Section Successfully Updated!');

                            break;
                        case 'assign_w9': //W4 Form Active
                            $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);

                            if (empty($already_assigned_w9)) {
                                $w9_data_to_insert = array();
                                $w9_data_to_insert['user_sid'] = $user_sid;
                                $w9_data_to_insert['company_sid'] = $company_sid;
                                $w9_data_to_insert['user_type'] = $user_type;
                                $w9_data_to_insert['sent_status'] = 1;
                                $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $w9_data_to_insert['status'] = 1;
                                $w9_data_to_insert['last_assign_by'] = $eeid;

                                $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                            } else {
                                //
                                $already_assigned_w9 = array();
                                $already_assigned_w9['ip_address'] = NULL;
                                $already_assigned_w9['user_agent'] = NULL;
                                $already_assigned_w9['active_signature'] = NULL;
                                $already_assigned_w9['signature'] = NULL;
                                $already_assigned_w9['user_consent'] = NULL;
                                $already_assigned_w9['signature_timestamp'] = NULL;
                                $already_assigned_w9['signature_email_address'] = NULL;
                                $already_assigned_w9['signature_bas64_image'] = NULL;
                                $already_assigned_w9['init_signature_bas64_image'] = NULL;
                                $already_assigned_w9['signature_ip_address'] = NULL;
                                $already_assigned_w9['signature_user_agent'] = NULL;
                                $already_assigned_w9['sent_date'] = date('Y-m-d H:i:s');
                                $already_assigned_w9['status'] = 1;
                                $already_assigned_w9['uploaded_file'] = NULL;
                                $already_assigned_w9['uploaded_by_sid'] = 0;
                                $already_assigned_w9['last_assign_by'] = $eeid;

                                //
                                $this->hr_documents_management_model->activate_w9_forms($user_type, $user_sid, $already_assigned_w9);
                            }

                            //Send Email and SMS
                            $replacement_array = array();
                            $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                            $replacement_array['company_name'] = ucwords($company_name);
                            $replacement_array['firstname'] = $user_info['first_name'];
                            $replacement_array['lastname'] = $user_info['last_name'];
                            $replacement_array['first_name'] = $user_info['first_name'];
                            $replacement_array['last_name'] = $user_info['last_name'];
                            $replacement_array['baseurl'] = base_url();
                            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                            if ($user_type == 'employee') {
                                //SMS Start
                                if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if ($company_sms_notification_status) {
                                        $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                        $sms_notify = 0;
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                        if ($sms_notify) {
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                            sendSMS(
                                                $contact_no,
                                                $sms_body,
                                                trim(ucwords(strtolower($replacement_array['company_name']))),
                                                $user_info['email'],
                                                $this,
                                                $sms_notify,
                                                $company_sid
                                            );
                                        }
                                    }
                                    //
                                    $user_extra_info = array();
                                    $user_extra_info['user_sid'] = $user_sid;
                                    $user_extra_info['user_type'] = $user_type;
                                    //
                                    $this->load->model('Hr_documents_management_model', 'HRDMM');
                                    if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                        //
                                        if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS5")) {
                                            log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                                        }
                                    }
                                }
                            }
                            //
                            $w9_sid = getVerificationDocumentSid($user_sid, $user_type, 'w9');
                            keepTrackVerificationDocument($security_sid, "employee", 'assign', $w9_sid, 'w9', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_w9': //W9 Form Deactive
                            $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);
                            //
                            $already_assigned_w9['w9form_ref_sid'] = $already_assigned_w9['sid'];
                            unset($already_assigned_w9['sid']);
                            $this->hr_documents_management_model->w9_forms_history($already_assigned_w9);
                            //
                            $this->hr_documents_management_model->deactivate_w9_forms($user_type, $user_sid);
                            //
                            $w9_sid = getVerificationDocumentSid($user_sid, $user_type, 'w9');
                            keepTrackVerificationDocument($security_sid, "employee", 'revoke', $w9_sid, 'w9', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'assign_i9': //I9 Form Active

                            $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);

                            if (empty($already_assigned_i9)) {
                                $i9_data_to_insert = array();
                                $i9_data_to_insert['user_sid'] = $user_sid;
                                $i9_data_to_insert['user_type'] = $user_type;
                                $i9_data_to_insert['company_sid'] = $company_sid;
                                $i9_data_to_insert['sent_status'] = 1;
                                $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['status'] = 1;
                                $i9_data_to_insert['version'] = getSystemDate('Y');
                                $i9_data_to_insert['last_assign_by'] = $eeid;

                                $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                            } else {
                                //
                                $data_to_update = array();
                                $data_to_update["status"] = 1;
                                $data_to_update["sent_status"] = 1;
                                $data_to_update["sent_date"] = date('Y-m-d H:i:s');
                                $data_to_update["section1_emp_signature"] = NULL;
                                $data_to_update["section1_emp_signature_init"] = NULL;
                                $data_to_update["section1_emp_signature_ip_address"] = NULL;
                                $data_to_update["section1_emp_signature_user_agent"] = NULL;
                                $data_to_update["section1_preparer_signature"] = NULL;
                                $data_to_update["section1_preparer_signature_init"] = NULL;
                                $data_to_update["section1_preparer_signature_ip_address"] = NULL;
                                $data_to_update["section1_preparer_signature_user_agent"] = NULL;
                                $data_to_update["section2_sig_emp_auth_rep"] = NULL;
                                $data_to_update["section3_emp_sign"] = NULL;
                                $data_to_update["employer_flag"] = NULL;
                                $data_to_update["user_consent"] = NULL;
                                $data_to_update["s3_filename"] = NULL;
                                $data_to_update["version"] = getSystemDate('Y');
                                $data_to_update["section1_preparer_json"] = NULL;
                                $data_to_update["section3_authorized_json"] = NULL;
                                $data_to_update['last_assign_by'] = $eeid;

                                //
                                $this->hr_documents_management_model->reassign_i9_forms($user_type, $user_sid, $data_to_update);
                            }
                            //
                            $i9_sid = getVerificationDocumentSid($user_sid, $user_type, 'i9');
                            keepTrackVerificationDocument($security_sid, "employee", 'assign', $i9_sid, 'i9', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_i9': //I9 Form Deactive
                            $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);
                            //
                            $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                            unset($already_assigned_i9['sid']);
                            $this->hr_documents_management_model->i9_forms_history($already_assigned_i9);
                            //
                            $this->hr_documents_management_model->deactivate_i9_forms($user_type, $user_sid);
                            //
                            $i9_sid = getVerificationDocumentSid($user_sid, $user_type, 'i9');
                            keepTrackVerificationDocument($security_sid, "employee", 'revoke', $i9_sid, 'i9', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            // Need to be removed
                            // Why we will send email on removing I9
                            if ($user_type == 'employee') {
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                //SMS Start
                                if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if ($company_sms_notification_status) {
                                        $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                        $sms_notify = 0;
                                        if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                        if ($sms_notify) {
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                            $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                            sendSMS(
                                                $contact_no,
                                                $sms_body,
                                                trim(ucwords(strtolower($replacement_array['company_name']))),
                                                $user_info['email'],
                                                $this,
                                                $sms_notify,
                                                $company_sid
                                            );
                                        }
                                    }
                                    //
                                    $user_extra_info = array();
                                    $user_extra_info['user_sid'] = $user_sid;
                                    $user_extra_info['user_type'] = $user_type;

                                    //
                                    $this->load->model('Hr_documents_management_model', 'HRDMM');
                                    if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                        //
                                        if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS6")) {
                                            log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                                        }
                                    }
                                }


                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_EEOC': //EEOC Form Deactive
                            //
                            $this->hr_documents_management_model->deactivate_EEOC_forms($user_type, $user_sid);
                            //
                            $eeoc_sid = getVerificationDocumentSid($user_sid, $user_type, 'eeoc');
                            keepTrackVerificationDocument($security_sid, "employee", 'revoke', $eeoc_sid, 'eeoc', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');
                            //
                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'assign_EEOC': //EEOC Form Active
                            // $this->hr_documents_management_model->activate_EEOC_forms($user_type, $user_sid);
                            $this->hr_documents_management_model->getEEOCId($user_sid, $user_type, $jobs_listing, 'Document Center');
                            //
                            $eeoc_sid = getVerificationDocumentSid($user_sid, $user_type, 'eeoc');
                            keepTrackVerificationDocument($security_sid, "employee", 'assign', $eeoc_sid, 'eeoc', 'Document Center');
                            //
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');
                            //
                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'reupload_assign_specific':

                            $data_to_update = array();
                            $data_to_update['document_title'] =  $_POST['document_title'];

                            if (isset($_POST['accessable'])) { //check if document is uploaded for access level plus
                                $data_to_update['document_type'] = 'confidential';
                            } else {
                                $data_to_update['document_type'] = 'uploaded';
                            }

                            if (isset($_POST['is_offer_letter'])) { //check if document is offer letter
                                $user_info = '';

                                if ($user_type == 'applicant') {
                                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);
                                } else if ($user_type == 'employee') {
                                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
                                }

                                // $offer_letter_name = 'Offer Letter for ' . $user_info['first_name'] . ' ' . $user_info['last_name']; 
                                // $data_to_insert['document_title'] = $offer_letter_name;

                                $data_to_update['document_type'] = 'offer_letter';
                                $data_to_update['offer_letter_type'] = 'uploaded';
                                $data_to_update['user_consent'] = 1;

                                $already_assigned = $this->hr_documents_management_model->check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, 'offer_letter');

                                if (!empty($already_assigned)) {
                                    foreach ($already_assigned as $key => $previous_offer_letter) {
                                        $previous_assigned_sid = $previous_offer_letter['sid'];
                                        $already_moved = $this->hr_documents_management_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                                        if ($already_moved == 'no') {
                                            $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                                            unset($previous_offer_letter['sid']);
                                            $this->hr_documents_management_model->insert_documents_assignment_record_history($previous_offer_letter);
                                        }
                                    }
                                }

                                $this->hr_documents_management_model->disable_all_previous_letter($company_sid, $user_type, $user_sid, 'offer_letter');
                            } else {

                                if (!isset($_POST['categories'])) {
                                    if (isset($_POST['update_manual_doc_to_payroll'])) {
                                        $data_to_update['visible_to_payroll'] = 1;
                                    } else {
                                        $data_to_update['visible_to_payroll'] = 0;
                                    }
                                } else if (!in_array(27, $_POST['categories'])) {
                                    if (isset($_POST['update_manual_doc_to_payroll'])) {
                                        $data_to_update['visible_to_payroll'] = 1;
                                    } else {
                                        $data_to_update['visible_to_payroll'] = 0;
                                    }
                                }
                            }

                            if (isset($_POST['doc_assign_date']) && !empty($_POST['doc_assign_date'])) { //check if document has assign date
                                $data_to_update['assigned_date'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_assign_date'])->format('Y-m-d');
                            }

                            if (isset($_POST['doc_sign_date']) && !empty($_POST['doc_sign_date'])) { //check if document has sign date
                                $data_to_update['signature_timestamp'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_sign_date'])->format('Y-m-d');
                            }

                            $upload_document_url = $_POST['document_url'];

                            if (!empty($upload_document_url)) {
                                $upload_document_extension = $_POST['document_extension'];
                                $upload_document_name = $_POST['document_name'];
                                //
                                $data_to_update['document_original_name']   = $upload_document_name;
                                $data_to_update['document_s3_name']         = $upload_document_url;
                                $data_to_update['uploaded_file']            = $upload_document_url;
                                $data_to_update['document_extension']       = $upload_document_extension;
                                $data_to_update['uploaded']                 = 1;
                                $data_to_update['uploaded_date']            = date('Y-m-d H:i:s');
                            }

                            // if(!empty($_FILES['document']['name'])){
                            //     $uploaded_document_original_name = '';

                            //     if (isset($_FILES['document']['name'])) {
                            //         $uploaded_document_original_name = $_FILES['document']['name'];
                            //     }

                            //     // if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            //         // $uploaded_document_s3_name = '0003-d_6-1542874444-39O.jpg';
                            //         // $uploaded_document_s3_name = '0057-testing_uploaded_doc-58-AAH.docx';
                            //         // $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            //     // } else {
                            //         $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $uploaded_document_original_name), $employer_sid, AWS_S3_BUCKET_NAME);
                            //     // }

                            //     $file_info = pathinfo($uploaded_document_original_name);

                            //     $data_to_update['company_sid'] = $company_sid;
                            //     if (isset($file_info['extension'])) {
                            //         $data_to_update['document_extension'] = $file_info['extension'];
                            //     }

                            //     if ($uploaded_document_s3_name != 'error') {
                            //         $data_to_update['document_original_name'] = $uploaded_document_original_name;
                            //         $data_to_update['document_s3_name'] = $uploaded_document_s3_name;
                            //         $data_to_update['uploaded_file'] = $uploaded_document_s3_name;
                            //         $data_to_update['uploaded'] = 1;
                            //         $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                            //     } else {
                            //         $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                            //         $this->redirectHandler('hr_documents_management/documents_assignment/'.$user_type.'/'.$user_sid, 'refresh');
                            //     }
                            // }

                            $this->hr_documents_management_model->update_documents_assignment_record($this->input->post('documents_assigned_sid'), $data_to_update);
                            $this->hr_documents_management_model->add_update_categories_2_documents($this->input->post('documents_assigned_sid'), $this->input->post('categories'), "documents_assigned");
                            $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Reupload Successful!');
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'assign_specific':

                            $document_title = $this->input->post('document_title');
                            $document_description = $this->input->post('document_description');
                            $document_description = htmlentities($document_description);
                            $uploaded_document_s3_name = $this->input->post('document_url');
                            $uploaded_document_original_name = $this->input->post('document_name');
                            $uploaded_document_extension = $this->input->post('document_extension');
                            // if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            //     $uploaded_document_s3_name = '0003-d_6-1542874444-39O.jpg';
                            // $uploaded_document_s3_name = '0057-testing_uploaded_doc-58-AAH.docx';
                            // $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            // } else {
                            // $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);
                            // }
                            $uploaded_document_original_name = $document_title;

                            // if (isset($_FILES['document']['name'])) {
                            //     $uploaded_document_original_name = $_FILES['document']['name'];
                            // }


                            // $file_info = pathinfo($uploaded_document_original_name);
                            $data_to_insert = array();

                            if (isset($_POST['accessable'])) { //check if document is uploaded for access level plus
                                $data_to_insert['document_type'] = 'confidential';
                            } else {
                                $data_to_insert['document_type'] = 'uploaded';
                            }

                            if (isset($_POST['doc_assign_date']) && $_POST['doc_assign_date'] != '') { //check if document has assign date
                                $data_to_insert['assigned_date'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_assign_date'])->format('Y-m-d');
                            } else {
                                $data_to_insert['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
                            }

                            if (isset($_POST['doc_sign_date']) && $_POST['doc_sign_date'] != '') { //check if document has sign date
                                $data_to_insert['signature_timestamp'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_sign_date'])->format('Y-m-d');
                            }

                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_by'] = $employer_sid;
                            $data_to_insert['user_type'] = $user_type;
                            $data_to_insert['user_sid'] = $user_sid;
                            $data_to_insert['document_title'] = $document_title;
                            $data_to_insert['document_description'] = $document_description;
                            $data_to_insert['document_sid'] = 0;
                            $data_to_insert['status'] = 1;
                            $data_to_insert['user_consent'] = 1;
                            // $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                            // $data_to_insert['download_required'] = $this->input->post('download_required');
                            // $data_to_insert['signature_required'] = $this->input->post('signature_required');

                            // if (isset($file_info['extension'])) {
                            //     $data_to_insert['document_extension'] = $file_info['extension'];
                            // }

                            if ($uploaded_document_s3_name != 'error') {
                                $data_to_insert['document_original_name']   = $uploaded_document_original_name;
                                $data_to_insert['document_extension']       = $uploaded_document_extension;
                                $data_to_insert['document_s3_name']         = $uploaded_document_s3_name;
                                $data_to_insert['uploaded_file']            = $uploaded_document_s3_name;
                                $data_to_insert['uploaded']                 = 1;
                                $data_to_insert['uploaded_date']            = date('Y-m-d H:i:s');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                                $this->redirectHandler('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid, 'refresh');
                            }

                            if (isset($_POST['is_offer_letter'])) { //check if document is offer letter
                                $user_info = '';

                                if ($user_type == 'applicant') {
                                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);
                                } else if ($user_type == 'employee') {
                                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
                                }

                                // $offer_letter_name = 'Offer Letter for ' . $user_info['first_name'] . ' ' . $user_info['last_name']; 
                                // $data_to_insert['document_title'] = $offer_letter_name;

                                $data_to_insert['document_type'] = 'offer_letter';
                                $data_to_insert['offer_letter_type'] = 'uploaded';

                                $already_assigned = $this->hr_documents_management_model->check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, 'offer_letter');

                                if (!empty($already_assigned)) {
                                    foreach ($already_assigned as $key => $previous_offer_letter) {
                                        $previous_assigned_sid = $previous_offer_letter['sid'];
                                        $already_moved = $this->hr_documents_management_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                                        if ($already_moved == 'no') {
                                            $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                                            unset($previous_offer_letter['sid']);
                                            $this->hr_documents_management_model->insert_documents_assignment_record_history($previous_offer_letter);
                                        }
                                    }
                                }

                                $this->hr_documents_management_model->disable_all_previous_letter($company_sid, $user_type, $user_sid, 'offer_letter');
                            } else {

                                if (!isset($_POST['categories'])) {
                                    if (isset($_POST['visible_manual_doc_to_payroll'])) {
                                        $data_to_insert['visible_to_payroll'] = 1;
                                    } else {
                                        $data_to_insert['visible_to_payroll'] = 0;
                                    }
                                } else if (!in_array(27, $_POST['categories'])) {
                                    if (isset($_POST['visible_manual_doc_to_payroll'])) {
                                        $data_to_insert['visible_to_payroll'] = 1;
                                    } else {
                                        $data_to_insert['visible_to_payroll'] = 0;
                                    }
                                }
                            }
                            // Document Settings - Confidential
                            $data_to_insert['is_confidential'] = $this->input->post('setting_is_confidential', true) && $this->input->post('setting_is_confidential', true) == 'on' ? 1 : 0;
                            //
                            $confidentialSelectedEmployees = $this->input->post('confidentialSelectedEmployees', true);
                            //
                            $data_to_insert['confidential_employees'] = NULL;
                            //
                            if ($confidentialSelectedEmployees) {
                                $data_to_insert['confidential_employees'] = in_array("-1", $confidentialSelectedEmployees) ? "-1" : implode(",", $confidentialSelectedEmployees);
                            }
                            //
                            $insert_id = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            //
                            $this->hr_documents_management_model->add_update_categories_2_documents($insert_id, $this->input->post('categories'), "documents_assigned");
                            //
                            if (!isset($_POST['accessable']) && $user_type == 'employee') {
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {
                                    //SMS Start
                                    $is_manual = get_document_type($insert_id);
                                    //
                                    if ($is_manual == 'no') {

                                        $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                        if ($company_sms_notification_status) {
                                            $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                            $sms_notify = 0;
                                            if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                                $contact_no = $notify_by['PhoneNumber'];
                                                $sms_notify = 1;
                                            }
                                            if ($sms_notify) {
                                                $this->load->library('Twilioapp');
                                                // Send SMS
                                                $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                                $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                                sendSMS(
                                                    $contact_no,
                                                    $sms_body,
                                                    trim(ucwords(strtolower($replacement_array['company_name']))),
                                                    $user_info['email'],
                                                    $this,
                                                    $sms_notify,
                                                    $company_sid
                                                );
                                            }
                                        }
                                        //
                                        $user_extra_info = array();
                                        $user_extra_info['user_sid'] = $user_sid;
                                        $user_extra_info['user_type'] = $user_type;
                                        //
                                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                                        if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                            //
                                            if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS7")) {
                                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                                            }
                                        }
                                    }
                                }
                            }
                            $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Upload Successful!');
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'revoke_eeoc':
                            $eeoc_form_history = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                            $eeoc_form_sid = $eeoc_form_history['sid'];
                            $eeoc_form_history['eeoc_form_ref_sid'] = $eeoc_form_sid;
                            unset($eeoc_form_history['sid']);
                            $this->hr_documents_management_model->eeoc_forms_history($eeoc_form_history);
                            $this->hr_documents_management_model->change_eeoc_forms_status($user_sid);
                            $this->hr_documents_management_model->delete_eeoc_forms_info($eeoc_form_sid);

                            break;
                        case 'upload_eev_document':
                            $uploaded_document_original_name = $_FILES['document']['name'];
                            $document_type = $this->input->post('document_type');
                            $document_name = 'eev-' . $document_type . '-document';

                            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            } else {
                                $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);
                            }

                            $file_info = pathinfo($uploaded_document_original_name);
                            $data_to_insert = array();

                            if ($uploaded_document_s3_name != 'error') {
                                if ($document_type == 'i9') {
                                    //  $data_to_insert['company_sid'] = $company_sid;
                                    //  $data_to_insert['employee_sid'] = $user_sid;
                                    //  $data_to_insert['document_name'] = $uploaded_document_original_name;
                                    //  $data_to_insert['date_uploaded'] = date('Y-m-d H:i:s');
                                    //  $data_to_insert['uploaded_by_sid'] = $employer_sid;
                                    //  $data_to_insert['document_type'] = $this->input->post('document_type');
                                    //  $data_to_insert['sid'] = $this->input->post('sid');
                                    //  $data_to_insert['s3_filename'] = $uploaded_document_s3_name;

                                    $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist('employee', $user_sid); //Here type will always be employee

                                    if (empty($already_assigned_i9)) {
                                        $i9_data_to_insert = array();
                                        $i9_data_to_insert['user_sid'] = $user_sid;
                                        $i9_data_to_insert['user_type'] = $user_type;
                                        $i9_data_to_insert['company_sid'] = $company_sid;
                                        $i9_data_to_insert['sent_status'] = 1;
                                        $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['status'] = 1;
                                        $i9_data_to_insert['s3_filename'] = $uploaded_document_s3_name;
                                        $i9_data_to_insert['emp_app_sid'] = $employer_sid;
                                        $i9_data_to_insert['employer_flag'] = 1;
                                        $i9_data_to_insert['applicant_flag'] = 1;
                                        $i9_data_to_insert['applicant_filled_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['employer_filled_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['user_consent'] = 1;
                                        $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                                    } else {
                                        $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                                        unset($already_assigned_i9['sid']);
                                        $this->hr_documents_management_model->i9_forms_history($already_assigned_i9);
                                        $this->hr_documents_management_model->delete_i9_form($already_assigned_i9['i9form_ref_sid']);
                                        $i9_data_to_insert = array();
                                        $i9_data_to_insert['s3_filename'] = $uploaded_document_s3_name;
                                        $i9_data_to_insert['sid'] = $already_assigned_i9['i9form_ref_sid'];
                                        $i9_data_to_insert['user_sid'] = $user_sid;
                                        $i9_data_to_insert['user_type'] = $user_type;
                                        $i9_data_to_insert['company_sid'] = $company_sid;
                                        $i9_data_to_insert['sent_status'] = 1;
                                        $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['status'] = 1;
                                        $i9_data_to_insert['emp_app_sid'] = $employer_sid;
                                        $i9_data_to_insert['employer_flag'] = 1;
                                        $i9_data_to_insert['applicant_flag'] = 1;
                                        $i9_data_to_insert['applicant_filled_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['employer_filled_date'] = date('Y-m-d H:i:s');
                                        $i9_data_to_insert['user_consent'] = 1;
                                        $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                                    }
                                } else if ($document_type == 'w9') {
                                    $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist('employee', $user_sid);
                                    if (empty($already_assigned_w9)) {
                                        $w9_data_to_insert = array();
                                        $w9_data_to_insert['user_sid'] = $user_sid;
                                        $w9_data_to_insert['company_sid'] = $company_sid;
                                        $w9_data_to_insert['user_type'] = $user_type;
                                        $w9_data_to_insert['sent_status'] = 1;
                                        $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                        $w9_data_to_insert['status'] = 1;
                                        $w9_data_to_insert['user_consent'] = 1;
                                        $w9_data_to_insert['uploaded_file'] = $uploaded_document_s3_name;
                                        $w9_data_to_insert['uploaded_by_sid'] = $employer_sid;
                                        $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                                    } else {
                                        $already_assigned_w9['w9form_ref_sid'] = $already_assigned_w9['sid'];
                                        unset($already_assigned_w9['sid']);
                                        $this->hr_documents_management_model->w9_forms_history($already_assigned_w9);
                                        $already_assigned_w9 = array();
                                        $already_assigned_w9['w9_name'] = NULL;
                                        $already_assigned_w9['w9_business_name'] = NULL;
                                        $already_assigned_w9['w9_federaltax_classification'] = NULL;
                                        $already_assigned_w9['w9_federaltax_description'] = NULL;
                                        $already_assigned_w9['w9_exemption_payee_code'] = NULL;
                                        $already_assigned_w9['w9_exemption_reporting_code'] = NULL;
                                        $already_assigned_w9['w9_address'] = NULL;
                                        $already_assigned_w9['w9_city_state_zip'] = NULL;
                                        $already_assigned_w9['w9_requester_name_address'] = NULL;
                                        $already_assigned_w9['w9_account_no'] = NULL;
                                        $already_assigned_w9['w9_social_security_number'] = NULL;
                                        $already_assigned_w9['w9_employer_identification_number'] = NULL;
                                        $already_assigned_w9['ip_address'] = NULL;
                                        $already_assigned_w9['user_agent'] = NULL;
                                        $already_assigned_w9['first_name'] = NULL;
                                        $already_assigned_w9['last_name'] = NULL;
                                        $already_assigned_w9['email_address'] = NULL;
                                        $already_assigned_w9['active_signature'] = NULL;
                                        $already_assigned_w9['signature'] = NULL;
                                        $already_assigned_w9['user_consent'] = NULL;
                                        $already_assigned_w9['signature_timestamp'] = NULL;
                                        $already_assigned_w9['signature_email_address'] = NULL;
                                        $already_assigned_w9['signature_bas64_image'] = NULL;
                                        $already_assigned_w9['init_signature_bas64_image'] = NULL;
                                        $already_assigned_w9['signature_ip_address'] = NULL;
                                        $already_assigned_w9['signature_user_agent'] = NULL;
                                        $already_assigned_w9['sent_date'] = date('Y-m-d H:i:s');
                                        $already_assigned_w9['status'] = 1;
                                        $already_assigned_w9['user_consent'] = 1;
                                        $already_assigned_w9['uploaded_file'] = $uploaded_document_s3_name;
                                        $already_assigned_w9['uploaded_by_sid'] = $employer_sid;
                                        $this->hr_documents_management_model->activate_w9_forms($user_type, $user_sid, $already_assigned_w9);
                                    }
                                } else if ($document_type == 'w4') {
                                    $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist('employee', $user_sid);

                                    if (empty($w4_form_history)) {
                                        $w4_data_to_insert = array();
                                        $w4_data_to_insert['employer_sid'] = $user_sid;
                                        $w4_data_to_insert['company_sid'] = $company_sid;
                                        $w4_data_to_insert['user_type'] = $user_type;
                                        $w4_data_to_insert['sent_status'] = 1;
                                        $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                        $w4_data_to_insert['status'] = 1;
                                        $w4_data_to_insert['user_consent'] = 1;
                                        $w4_data_to_insert['uploaded_file'] = $uploaded_document_s3_name;
                                        $w4_data_to_insert['uploaded_by_sid'] = $employer_sid;
                                        $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                                    } else {
                                        if ($w4_form_history['user_consent'] == 1) {
                                            $w4_form_history['form_w4_ref_sid'] = $w4_form_history['sid'];
                                            unset($w4_form_history['sid']);
                                            $this->hr_documents_management_model->w4_forms_history($w4_form_history);
                                        }

                                        $w4_data_to_insert                                          = array();
                                        $w4_data_to_insert['sent_date']                             = date('Y-m-d H:i:s');
                                        $w4_data_to_insert['status']                                = 1;
                                        $w4_data_to_insert['first_name']                            = NULL;
                                        $w4_data_to_insert['middle_name']                           = NULL;
                                        $w4_data_to_insert['last_name']                             = NULL;
                                        $w4_data_to_insert['ss_number']                             = NULL;
                                        $w4_data_to_insert['home_address']                          = NULL;
                                        $w4_data_to_insert['city']                                  = NULL;
                                        $w4_data_to_insert['state']                                 = NULL;
                                        $w4_data_to_insert['zip']                                   = NULL;
                                        $w4_data_to_insert['marriage_status']                       = NULL;
                                        $w4_data_to_insert['different_last_name']                   = NULL;
                                        $w4_data_to_insert['number_of_allowance']                   = NULL;
                                        $w4_data_to_insert['additional_amount']                     = NULL;
                                        $w4_data_to_insert['claim_exempt']                          = NULL;
                                        $w4_data_to_insert['signature_timestamp']                   = NULL;
                                        $w4_data_to_insert['signature_email_address']               = NULL;
                                        $w4_data_to_insert['signature_bas64_image']                 = NULL;
                                        $w4_data_to_insert['init_signature_bas64_image']            = NULL;
                                        $w4_data_to_insert['ip_address']                            = NULL;
                                        $w4_data_to_insert['user_agent']                            = NULL;
                                        $w4_data_to_insert['emp_name']                              = NULL;
                                        $w4_data_to_insert['emp_address']                           = NULL;
                                        $w4_data_to_insert['first_date_of_employment']              = NULL;
                                        $w4_data_to_insert['emp_identification_number']             = NULL;
                                        $w4_data_to_insert['paw_yourself']                          = NULL;
                                        $w4_data_to_insert['paw_married']                           = NULL;
                                        $w4_data_to_insert['paw_head']                              = NULL;
                                        $w4_data_to_insert['paw_single_wages']                      = NULL;
                                        $w4_data_to_insert['paw_child_tax']                         = NULL;
                                        $w4_data_to_insert['paw_dependents']                        = NULL;
                                        $w4_data_to_insert['paw_other_credit']                      = NULL;
                                        $w4_data_to_insert['paw_accuracy']                          = NULL;
                                        $w4_data_to_insert['daaiw_estimate']                        = NULL;
                                        $w4_data_to_insert['daaiw_enter_status']                    = NULL;
                                        $w4_data_to_insert['daaiw_subtract_line_2']                 = NULL;
                                        $w4_data_to_insert['daaiw_estimate_of_adjustment']          = NULL;
                                        $w4_data_to_insert['daaiw_add_line_3_4']                    = NULL;
                                        $w4_data_to_insert['daaiw_estimate__of_nonwage']            = NULL;
                                        $w4_data_to_insert['daaiw_subtract_line_6']                 = NULL;
                                        $w4_data_to_insert['daaiw_divide_line_7']                   = NULL;
                                        $w4_data_to_insert['daaiw_enter_number_personal_allowance'] = NULL;
                                        $w4_data_to_insert['daaiw_add_line_8_9']                    = NULL;
                                        $w4_data_to_insert['temjw_personal_allowance']              = NULL;
                                        $w4_data_to_insert['temjw_num_in_table_1']                  = NULL;
                                        $w4_data_to_insert['temjw_more_line2']                      = NULL;
                                        $w4_data_to_insert['temjw_num_from_line2']                  = NULL;
                                        $w4_data_to_insert['temjw_num_from_line1']                  = NULL;
                                        $w4_data_to_insert['temjw_subtract_5_from_4']               = NULL;
                                        $w4_data_to_insert['temjw_amount_in_table_2']               = NULL;
                                        $w4_data_to_insert['temjw_multiply_7_by_6']                 = NULL;
                                        $w4_data_to_insert['temjw_divide_8_by_period']              = NULL;
                                        $w4_data_to_insert['user_consent']                          = 1;
                                        $w4_data_to_insert['uploaded_file']                         = $uploaded_document_s3_name;
                                        $w4_data_to_insert['uploaded_by_sid']                       = $employer_sid;

                                        //fore 2020 new fields
                                        $w4_data_to_insert['mjsw_status']                           = NULL;
                                        $w4_data_to_insert['dependents_children']                   = NULL;
                                        $w4_data_to_insert['other_dependents']                      = NULL;
                                        $w4_data_to_insert['claim_total_amount']                    = NULL;
                                        $w4_data_to_insert['other_income']                          = NULL;
                                        $w4_data_to_insert['other_deductions']                      = NULL;
                                        $w4_data_to_insert['additional_tax']                        = NULL;
                                        $w4_data_to_insert['mjw_two_jobs']                          = NULL;
                                        $w4_data_to_insert['mjw_three_jobs_a']                      = NULL;
                                        $w4_data_to_insert['mjw_three_jobs_b']                      = NULL;
                                        $w4_data_to_insert['mjw_three_jobs_c']                      = NULL;
                                        $w4_data_to_insert['mjw_pp_py']                             = NULL;
                                        $w4_data_to_insert['mjw_divide']                            = NULL;
                                        $w4_data_to_insert['dw_input_1']                            = NULL;
                                        $w4_data_to_insert['dw_input_2']                            = NULL;
                                        $w4_data_to_insert['dw_input_3']                            = NULL;
                                        $w4_data_to_insert['dw_input_4']                            = NULL;
                                        $w4_data_to_insert['dw_input_5']                            = NULL;

                                        $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_insert);
                                    }
                                }

                                // $insert_id = $this->hr_documents_management_model->insert_eev_document($data_to_insert);
                                $this->session->set_flashdata('message', '<strong>Success:</strong> Signed Employment Eligibility Verification Document Uploaded Successful!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                            }
                            if (!empty($this->input->post('redirect_link')))
                                $this->redirectHandler($this->input->post('redirect_link'));
                            $this->redirectHandler('hr_documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                    }
                }
            }

            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid, 1);

            if (!empty($groups)) {
                foreach ($groups as $key => $group) {
                    $document_status = $this->hr_documents_management_model->is_document_assign_2_group($group['sid']);
                    $groups[$key]['document_status'] = $document_status;
                    $group_status = $group['status'];
                    $group_sid = $group['sid'];
                    $group_ids[] = $group_sid;
                    $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 0, $pp_flag);
                    $otherDocuments = getGroupOtherDocuments($group);
                    $otherDocumentCount = count($otherDocuments);

                    if ($group_status) {
                        $active_groups[] = array(
                            'sid' => $group_sid,
                            'name' => $group['name'],
                            'sort_order' => $group['sort_order'],
                            'description' => $group['description'],
                            'created_date' => $group['created_date'],
                            'w4' => $group['w4'],
                            'w9' => $group['w9'],
                            'i9' => $group['i9'],
                            'eeoc' => $group['eeoc'],
                            'direct_deposit' => $group['direct_deposit'],
                            'drivers_license' => $group['drivers_license'],
                            'occupational_license' => $group['occupational_license'],
                            'emergency_contacts' => $group['emergency_contacts'],
                            'dependents' => $group['dependents'],
                            'documents_count' => count($group_documents) + $otherDocumentCount,
                            'documents' => $group_documents,
                            'other_documents' => $otherDocuments
                        );
                    } else {
                        $in_active_groups[] = array(
                            'sid' => $group_sid,
                            'name' => $group['name'],
                            'sort_order' => $group['sort_order'],
                            'description' => $group['description'],
                            'created_date' => $group['created_date'],
                            'w4' => $group['w4'],
                            'w9' => $group['w9'],
                            'i9' => $group['i9'],
                            'eeoc' => $group['eeoc'],
                            'direct_deposit' => $group['direct_deposit'],
                            'drivers_license' => $group['drivers_license'],
                            'occupational_license' => $group['occupational_license'],
                            'emergency_contacts' => $group['emergency_contacts'],
                            'dependents' => $group['dependents'],
                            'documents_count' => count($group_documents) + $otherDocumentCount,
                            'documents' => $group_documents,
                            'other_documents' => $otherDocuments
                        );
                    }
                }
            }

            $categories = $this->hr_documents_management_model->get_all_documents_category($company_sid);
            $active_categories = [];

            if (!empty($categories)) {
                foreach ($categories as $key => $category) {
                    $document_status = $this->hr_documents_management_model->is_document_assign_2_category($category['sid']);
                    $categories[$key]['document_status'] = $document_status;
                    $category_status = $category['status'];
                    $category_sid = $category['sid'];
                    $category_ids[] = $category_sid;
                    $category_documents = $this->hr_documents_management_model->get_all_documents_in_category($category_sid, 0);

                    if ($category_status) {
                        $active_categories[] = array(
                            'sid' => $category_sid,
                            'name' => $category['name'],
                            'sort_order' => $category['sort_order'],
                            'description' => $category['description'],
                            'created_date' => $category['created_date'],
                            'documents_count' => count($category_documents),
                            'documents' => $category_documents
                        );
                    } else {
                        $in_active_categories[] = array(
                            'sid' => $category_sid,
                            'name' => $category['name'],
                            'sort_order' => $category['sort_order'],
                            'description' => $category['description'],
                            'created_date' => $category['created_date'],
                            'documents_count' => count($category_documents),
                            'documents' => $category_documents
                        );
                    }
                }
            }

            if (!empty($group_ids)) {
                $group_docs = $this->hr_documents_management_model->get_distinct_group_docs($group_ids);
            }

            if (!empty($group_docs)) { // document are assigned to any group.
                foreach ($group_docs as $group_doc) {
                    $document_ids[] = $group_doc['document_sid'];
                }
            }

            $uncategorized_documents = $this->hr_documents_management_model->get_uncategorized_docs($company_sid, $document_ids, 0, $pp_flag);
            $access_level_manual_doc = $this->hr_documents_management_model->get_access_level_manual_doc($company_sid, $user_sid, $user_type, $pp_flag);
            $data['uncategorized_documents'] = $uncategorized_documents;
            $data['access_level_manual_doc'] = $access_level_manual_doc;
            $data['active_groups'] = $active_groups;
            $data['active_categories'] = $active_categories;
            $data['in_active_groups'] = $in_active_groups;
            $data['groups'] = $groups;

            $data['left_navigation'] = $left_navigation;
            $i9_form = $this->hr_documents_management_model->fetch_form('i9', $user_type, $user_sid);
            $w9_form = $this->hr_documents_management_model->fetch_form('w9', $user_type, $user_sid);
            $w4_form = $this->hr_documents_management_model->fetch_form('w4', $user_type, $user_sid);

            if (!empty($w4_form)) {
                $assign_on = date("Y-m-d", strtotime($w4_form['sent_date']));
                $compare_date = date("Y-m-d", strtotime('2020-01-06'));
                //
                $this->checkAndSetEmployerSection(
                    $w4_form,
                    $user_type,
                    $user_sid
                );


                $data['popup_emp_name'] = $w4_form['emp_name'];
                $data['popup_emp_address'] = $w4_form['emp_address'];

                if (isset($w4_form) && !empty($w4_form['first_date_of_employment']) && $w4_form['first_date_of_employment'] != '0000-00-00 00:00:00' && $w4_form['first_date_of_employment'] != '' && $w4_form['first_date_of_employment'] != null) {
                    if (preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $w4_form['first_date_of_employment'])) {
                        $sign_date = $w4_form['first_date_of_employment'];
                    } else {
                        $sign_date = date("m-d-Y", strtotime($w4_form['first_date_of_employment']));
                    }
                }

                $data['popup_first_date_of_employment']     = $sign_date;
                $data['popup_emp_identification_number']    = $w4_form['emp_identification_number'];
                $data['w4_employer_section']                    = 1;

                //  if ($assign_on >= $compare_date && $w4_form['user_consent'] == 1) {
                if ($w4_form['user_consent'] == 1 && $w4_form['uploaded_by_sid'] <= 0) {
                    $sign_date                                      = '';
                }
            }

            $data['i9_form'] = $i9_form;
            $data['w9_form'] = $w9_form;
            $data['w4_form'] = $w4_form;

            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['first_name'] = $data['session']['employer_detail']['first_name'];
            $data['last_name'] = $data['session']['employer_detail']['last_name'];

            $data['users_type'] = 'employee';
            // $data['this'] = $this;
            $data['users_sid'] = $emp_sid;
            $data['jobs_listing'] = $jobs_listing;

            $assigned_sids                          = array();
            $no_action_required_sids                = array();
            $completed_sids                         = array();
            $revoked_sids                           = array();
            $completed_documents                    = array();
            $signed_documents                       = array();
            $signed_document_sids                   = array();
            $completed_document_sids                = array();
            $no_action_required_documents           = array();
            $no_action_required_payroll_documents   = array();
            $payroll_documents_sids                 = array();
            $uncompleted_offer_letter               = array();
            $completed_offer_letter                 = array();
            $uncompleted_payroll_documents          = array();
            $completed_payroll_documents            = array();
            $user_completed_payroll_documents       = array();

            $sendGroupEmail = 0;
            $assign_group_documents = $this->hr_documents_management_model->get_assign_group_documents($company_sid, $user_type, $user_sid);

            if (!empty($assign_group_documents)) {
                foreach ($assign_group_documents as $key => $assign_group_document) {
                    $is_document_assign = $this->hr_documents_management_model->check_document_already_assigned($company_sid, $user_type, $user_sid, $assign_group_document['document_sid']);

                    if ($is_document_assign == 0 && $assign_group_document['document_sid'] > 0) {
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $assign_group_document['document_sid']);

                        if (!empty($document)) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $assign_group_document['assigned_by_sid'];
                            $data_to_insert['user_type'] = $user_type;
                            $data_to_insert['user_sid'] = $user_sid;
                            $data_to_insert['document_type'] = $document['document_type'];
                            $data_to_insert['document_sid'] = $assign_group_document['document_sid'];
                            $data_to_insert['status'] = 1;
                            $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_insert['document_title'] = $document['document_title'];
                            $data_to_insert['document_description'] = $document['document_description'];
                            $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                            $data_to_insert['signature_required'] = $document['signature_required'];
                            $data_to_insert['download_required'] = $document['download_required'];
                            $data_to_insert['is_confidential'] = $document['is_confidential'];
                            $data_to_insert['confidential_employees'] = $document['confidential_employees'];
                            $data_to_insert['is_required'] = $document['is_required'];
                            $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];

                            //
                            $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            //
                            if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                                $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $document['document_description']);
                                //
                                if ($isAuthorized == 1) {
                                    // Managers handling
                                    $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                        $document['managers_list'],
                                        $assignment_sid,
                                        $company_sid,
                                        $assign_group_document['assigned_by_sid']
                                    );
                                }
                            }
                            //
                            if ($document['has_approval_flow'] == 1) {
                                $this->HandleApprovalFlow(
                                    $assignment_sid,
                                    $document['document_approval_note'],
                                    $document["document_approval_employees"],
                                    0,
                                    $document['managers_list']
                                );
                            } else {
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }
            }

            $groups_assign = $this->hr_documents_management_model->get_all_documents_group_assigned($company_sid, $user_type, $user_sid);
            $assigned_groups = array();

            if (!empty($groups_assign)) {
                foreach ($groups_assign as $value) {
                    array_push($assigned_groups, $value['group_sid']);
                    $system_document = $this->hr_documents_management_model->get_document_group($value['group_sid']);

                    // General Documents
                    foreach ($system_document as $gk => $gv) {
                        //
                        if (!in_array($gk, [
                            'direct_deposit',
                            'drivers_license',
                            'occupational_license',
                            'emergency_contacts',
                            'dependents'
                        ])) continue;
                        //
                        if ($gv == 1) {
                            if ($this->hr_documents_management_model->checkAndAssignGeneralDocument(
                                $user_sid,
                                $user_type,
                                $company_sid,
                                $gk,
                                $eeid
                            )) {
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }

                    if (!empty($system_document['w4']) && $system_document['w4'] == 1) {
                        $is_w4_assign = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);

                        if (empty($is_w4_assign)) {
                            $w4_data_to_insert = array();
                            $w4_data_to_insert['employer_sid'] = $user_sid;
                            $w4_data_to_insert['company_sid'] = $company_sid;
                            $w4_data_to_insert['user_type'] = $user_type;
                            $w4_data_to_insert['sent_status'] = 1;
                            $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w4_data_to_insert['status'] = 1;
                            $w4_data_to_insert['last_assign_by'] = $assign_group_document['assigned_by_sid'];

                            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if (!empty($system_document['w9']) && $system_document['w9'] == 1) {
                        $is_w9_assign = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);

                        if (empty($is_w9_assign)) {
                            $w9_data_to_insert = array();
                            $w9_data_to_insert['user_sid'] = $user_sid;
                            $w9_data_to_insert['company_sid'] = $company_sid;
                            $w9_data_to_insert['user_type'] = $user_type;
                            $w9_data_to_insert['sent_status'] = 1;
                            $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w9_data_to_insert['status'] = 1;
                            $w9_data_to_insert['last_assign_by'] = $assign_group_document['assigned_by_sid'];
                            $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if (!empty($system_document['i9']) && $system_document['i9'] == 1) {
                        $is_i9_assign = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);

                        if (empty($is_i9_assign)) {
                            $i9_data_to_insert = array();
                            $i9_data_to_insert['user_sid'] = $user_sid;
                            $i9_data_to_insert['user_type'] = $user_type;
                            $i9_data_to_insert['company_sid'] = $company_sid;
                            $i9_data_to_insert['sent_status'] = 1;
                            $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $i9_data_to_insert['status'] = 1;
                            $i9_data_to_insert['last_assign_by'] = $assign_group_document['assigned_by_sid'];

                            $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if ($this->session->userdata('logged_in')['portal_detail'][$user_type == 'applicant' ? 'eeo_on_applicant_document_center' : 'eeo_on_employee_document_center']) {
                        if (!empty($system_document['eeoc']) && $system_document['eeoc'] == 1) {
                            $is_eeoc_assign = $this->hr_documents_management_model->check_eeoc_exist($user_sid, $user_type);

                            if (empty($is_eeoc_assign)) {
                                $eeoc_data_to_insert = array();
                                $eeoc_data_to_insert['application_sid'] = $user_sid;
                                $eeoc_data_to_insert['users_type'] = $user_type;
                                $eeoc_data_to_insert['status'] = 1;
                                $eeoc_data_to_insert['is_expired'] = 0;
                                $eeoc_data_to_insert['portal_applicant_jobs_list_sid'] = $jobs_listing;
                                $eeoc_data_to_insert['last_sent_at'] = getSystemDate();
                                $eeoc_data_to_insert['assigned_at'] = getSystemDate();
                                $eeoc_data_to_insert['last_assigned_by'] = 0;
                                //
                                $this->hr_documents_management_model->insert_eeoc_form_record($eeoc_data_to_insert);
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }
            }

            // state forms from group
            $this->hr_documents_management_model
                ->assignGroupDocumentsToUser(
                    $user_sid,
                    $user_type,
                    $sendGroupEmail
                );

            if ($sendGroupEmail == 1 && $user_type == 'employee') {
                //
                $hf = message_header_footer(
                    $company_sid,
                    ucwords($data['session']['company_detail']['CompanyName'])
                );
                //
                $replacement_array = array();
                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                $replacement_array['baseurl'] = base_url();
                //
                $extra_user_info = array();
                $extra_user_info["user_sid"] = $user_sid;
                $extra_user_info["user_type"] = $user_type;
                //
                $this->load->model('Hr_documents_management_model', 'HRDMM');
                if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                    //
                    if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS8")) {
                        //
                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $extra_user_info);
                    }
                }
            }

            if ($user_type == "applicant") {
                $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid, $user_type);
                $data['eeo_form_status'] = $eeo_form_status;
            }

            $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
            $data['eeo_form_info'] = $eeo_form_info;
            //
            // if (empty($data['eeo_form_info'])) {
            //     $data['eeo_form_info']['status'] = 0;
            // }

            $data['assigned_groups'] = $assigned_groups;

            $active_documents = $this->hr_documents_management_model->get_all_documents($company_sid, 0);
            $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, $user_type, $user_sid, 0, 1, 0, $pp_flag);


            $company_offer_letters = $this->hr_documents_management_model->get_all_company_offers_letters($company_sid, 0);
            $assigned_offer_letters = $this->hr_documents_management_model->get_assigned_offers($company_sid, $user_type, $user_sid);
            $assigned_offer_letter_history = $this->hr_documents_management_model->get_assigned_offer_letter_history($company_sid, $user_type, $user_sid, 0);

            $archived_assign_document = $this->hr_documents_management_model->get_archive_assigned_documents($company_sid, $user_type, $user_sid, $pp_flag);
            $user_assigned_manual_documents = $this->hr_documents_management_model->get_all_user_assigned_manual_documents($company_sid, $user_type, $user_sid, $pp_flag);
            //
            $history_doc_sids = array();
            //
            $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
            $documents_management_sids = $payroll_sids['documents_management_sids'];
            $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

            //        
            foreach ($assigned_documents as $key => $assigned_document) {
                //
                if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else {
                    $assigned_document['pay_roll_catgory'] = 0;
                }
                //
                if ($assigned_document['document_sid'] == 0) {
                    if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                        if ($assigned_document['pay_roll_catgory'] == 0) {
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                            $no_action_required_payroll_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        }
                    }
                } else {
                    //
                    $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                    //
                    if ($assigned_document['user_consent'] == 1) {
                        $assigned_document['archive'] = 0;
                    }
                    //
                    if ($assigned_document['archive'] == 0) {
                        //
                        //check is this approver document
                        $is_approval_document = $this->hr_documents_management_model->check_if_approval_document($user_type, $user_sid, $assigned_document['document_sid']);
                        //
                        if (!empty($is_approval_document)) {
                            $assigned_documents[$key]["approver_document"] = 1;
                            $assigned_documents[$key]["approver_managers"] = implode(",", array_column($is_approval_document, "assigner_sid"));
                        } else {
                            $assigned_documents[$key]["approver_document"] = 0;
                        }
                        //
                        //check Document Previous History
                        $previous_history = $this->hr_documents_management_model->check_if_document_has_history($user_type, $user_sid, $assigned_document['sid']);
                        //
                        if (!empty($previous_history)) {
                            array_push($history_doc_sids, $assigned_document['sid']);
                        }
                        //
                        $is_magic_tag_exist = 0;
                        $is_document_completed = 0;
                        $is_document_authorized = 0;
                        $is_document_authorized_date = 0;
                        $authorized_sign_status = 0;
                        $authorized_date_status = 0;


                        if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                            $document_body = $assigned_document['document_description'];
                            //$magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                            $magic_codes = array('{{signature}}', '{{inital}}');



                            //
                            $documentBodyOld = $document_body;
                            $document_body = $document_body ? magicCodeCorrection($document_body) : $document_body;


                            if ($documentBodyOld != $document_body) {

                                updateDocumentCorrectionDesc($document_body, $assigned_document['sid'], $assigned_document['document_sid']);
                            }

                            if (str_replace($magic_codes, '', $document_body) != $document_body) {

                                $is_magic_tag_exist = 1;
                            }
                            //
                            $assign_on = date("Y-m-d", strtotime($assigned_document['assigned_date']));
                            $compare_date = date("Y-m-d", strtotime('2020-03-04'));
                            //
                            if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {
                                //
                                // if (!empty($assigned_document['form_input_data'] || $assign_on >= $compare_date )) {
                                if ($assign_on >= $compare_date || !empty($assigned_document['form_input_data'])) {
                                    $is_document_authorized = 1;
                                }

                                // if ($assigned_document['user_consent'] == 1 && !empty($assigned_document['authorized_signature'])) {
                                if (!empty($assigned_document['authorized_signature'])) {
                                    $authorized_sign_status = 1;
                                } else {
                                    $authorized_sign_status = 0;
                                }

                                $assign_managers = $this->hr_documents_management_model->get_document_authorized_managers($company_sid, $assigned_document["sid"]);
                                $assigned_documents[$key]["assign_managers"] = implode(",", array_column($assign_managers, "assigned_to_sid"));
                            } else if (str_replace('{{authorized_signature}}', '', $document_body) == $document_body && str_replace('{{authorized_signature_date}}', '', $document_body) != $document_body)  {
                                //
                                if ($assign_on >= $compare_date || !empty($assigned_document['form_input_data'])) {
                                    $is_document_authorized_date = 1;
                                    $is_document_authorized = 1;
                                }
                                // 
                                if (!empty($assigned_document['authorized_signature_date'])) {
                                    $authorized_date_status = 1;
                                    $authorized_sign_status = 1;
                                } else {
                                    $authorized_date_status = 0;
                                    $authorized_sign_status = 0;
                                }
                                //
                                // $assign_managers = $this->hr_documents_management_model->get_document_authorized_managers($company_sid, $assigned_document["sid"]);
                                // $assigned_documents[$key]["assign_managers"] = implode(",", array_column($assign_managers, "assigned_to_sid"));
                                // $assigned_documents[$key]['is_document_authorized_date'] = $assigned_document['is_document_authorized_date'] = $is_document_authorized_date;
                                // $assigned_documents[$key]['authorized_date_status'] = $assigned_document['authorized_date_status'] = $authorized_date_status;
                            }
                        }
                        //
                        $assigned_documents[$key]['is_document_authorized'] = $assigned_document['is_document_authorized'] = $is_document_authorized;
                        $assigned_documents[$key]['authorized_sign_status'] = $assigned_document['authorized_sign_status'] = $authorized_sign_status;
                        //
                        if ($assigned_document['document_type'] != 'offer_letter') {
                            if ($assigned_document['document_type'] == 'uploaded') {
                                if (strpos($assigned_document['document_s3_name'], '&') !== false) {
                                    $assigned_documents[$key]['document_s3_name'] = modify_AWS_file_name($assigned_document['sid'], $assigned_document['document_s3_name'], "document_s3_name");
                                }

                                if (strpos($assigned_document['uploaded_file'], '&') !== false) {
                                    $assigned_documents[$key]['uploaded_file'] = modify_AWS_file_name($assigned_document['sid'], $assigned_document['uploaded_file'], "uploaded_file");
                                }
                            }
                            //
                            if ($assigned_document['status'] == 1) {
                                if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                                    // if ($is_document_authorized) {
                                    //     if ($assigned_document['user_consent'] == 1 && !empty($assigned_document['authorized_signature'])) {
                                    //         $is_document_completed = 1;
                                    //     } else {
                                    //         $is_document_completed = 0;
                                    //     }
                                    // } else 

                                    if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                        if ($is_magic_tag_exist == 1) {
                                            if ($assigned_document['uploaded'] == 1) {
                                                $is_document_completed = 1;
                                            } else {
                                                $is_document_completed = 0;
                                            }
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1) {
                                        if ($assigned_document['acknowledged'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['download_required'] == 1) {
                                        if ($assigned_document['downloaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($is_magic_tag_exist == 1) {
                                        if ($assigned_document['user_consent'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    }

                                    if ($is_document_completed > 0) {
                                        if ($assigned_document['pay_roll_catgory'] == 0) {

                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $signed_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $completed_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    } else {
                                        if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $uncompleted_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }

                                        $assigned_sids[] = $assigned_document['document_sid'];
                                    }
                                } else {
                                    if ($is_document_authorized == 1) {
                                        //
                                        if ($authorized_sign_status == 1) {
                                            if ($assigned_document['pay_roll_catgory'] == 0) {
                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $signed_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $completed_payroll_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            }
                                        } else {
                                            if ($assigned_document['pay_roll_catgory'] == 1) {
                                                $uncompleted_payroll_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            }
                                        }
                                        //
                                        $assigned_sids[] = $assigned_document['document_sid'];
                                        //
                                    } else if ($assigned_document['pay_roll_catgory'] == 0) {
                                        if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                                            $assigned_sids[] = $assigned_document['document_sid'];
                                            $no_action_required_sids[] = $assigned_document['document_sid'];
                                            $no_action_required_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                        if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                                            $no_action_required_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    }
                                }
                            } else {
                                $revoked_sids[] = $assigned_document['document_sid'];
                            }
                        }
                    } else if ($assigned_document['archive'] == 1) {
                        unset($assigned_documents[$key]);
                    }
                }
            }
            //
            $data['history_doc_sids'] = $history_doc_sids;
            //
            $current_assigned_offer_letter = $this->hr_documents_management_model->get_current_assigned_offer_letter($company_sid, $user_type, $user_sid);
            //
            if (!empty($current_assigned_offer_letter)) {
                if ($current_assigned_offer_letter[0]['user_consent'] == 1) {
                    $completed_offer_letter = $current_assigned_offer_letter;
                } else {
                    $uncompleted_offer_letter = $current_assigned_offer_letter;
                }
            }

            // Check for authorize tag
            if (sizeof($completed_offer_letter)) {
                //
                $completed_offer_letter[0]['is_document_authorized'] = 0;
                $completed_offer_letter[0]['authorized_sign_status'] = 0;
                //
                if (str_replace('{{authorized_signature}}', '', $completed_offer_letter[0]['document_description']) != $completed_offer_letter[0]['document_description']) {
                    $assign_on = date("Y-m-d", strtotime($completed_offer_letter[0]['assigned_date']));
                    $compare_date = date("Y-m-d", strtotime('2020-03-04'));

                    if ($assign_on >= $compare_date || !empty($completed_offer_letter[0]['form_input_data'])) {
                        $completed_offer_letter[0]['is_document_authorized'] = 1;
                    }

                    if (!empty($completed_offer_letter[0]['authorized_signature'])) {
                        $completed_offer_letter[0]['authorized_sign_status'] = 1;
                    }
                }
            }

            $data['w4_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w4');
            $data['w9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w9');
            // $data['i9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'i9');

            $assigned_documents_history = $this->hr_documents_management_model->get_assigned_documents_history(0, $user_type, $user_sid, $pp_flag);

            foreach ($active_documents as $key => $doc) {
                if ($doc['document_type'] == 'generated') {
                    $document = $this->hr_documents_management_model->get_assigned_document_record($user_type, $user_sid, $doc['sid']);
                    $active_documents[$key]['document_title'] = $doc['document_title'];
                    $active_documents[$key]['document_description'] = sizeof($document) > 0 && $document['document_description'] != NULL && !empty($document['document_description']) ? $document['document_description'] : $doc['document_description'];
                }
            }


            foreach ($signed_documents as $cd_key => $signed_document) {
                $signed_documents[$cd_key]["is_history"] = 0;
                $signed_documents[$cd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history($user_type, $user_sid, $signed_document['sid']);

                if (($key = array_search($signed_document['sid'], $history_doc_sids)) !== false) {
                    unset($history_doc_sids[$key]);
                }
            }

            foreach ($completed_payroll_documents as $prd_key => $payroll_document) {
                $completed_payroll_documents[$prd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history($user_type, $user_sid, $payroll_document['sid']);

                if (($key = array_search($payroll_document['sid'], $history_doc_sids)) !== false) {
                    unset($history_doc_sids[$key]);
                }
            }

            if (!empty($history_doc_sids)) {
                foreach ($history_doc_sids as $key => $doc_id) {
                    $his_docs = $this->hr_documents_management_model->check_if_document_has_history($user_type, $user_sid, $doc_id);
                    foreach ($his_docs as $key => $his_doc) {
                        $his_doc["is_history"] = 1;
                        $his_doc["history"] = array();
                        array_push($signed_documents, $his_doc);
                    }
                }
            }

            $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, $signed_documents, $no_action_required_documents, $data['session']['employer_detail']['access_level_plus']);

            // Get current employee departments and teams
            $data['employeeDepartments'] =
                $employeeDepartments = $this->hr_documents_management_model->getEmployeeDepartmentsAndTeams(
                    $company_sid,
                    $employer_sid
                );

            //
            cleanDocumentsByPermission(
                $categorized_docs,
                $data['session']['employer_detail'],
                true,
                $employeeDepartments
            );

            $data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
            $data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];
            $data['no_action_document_categories'] =  $categorized_docs['no_action_document_categories'];

            $archived_manual_documents = $this->hr_documents_management_model->get_archived_manual_documents($company_sid, $user_type, $user_sid, 1, $pp_flag);
            $archived_categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, null, $archived_manual_documents, $data['session']['employer_detail']['access_level_plus']);

            $data['archived_manual_documents'] = $archived_manual_documents;
            $data['user_assigned_manual_documents'] = $user_assigned_manual_documents;
            $data['archived_no_action_document_categories'] =  $archived_categorized_docs['no_action_document_categories'];

            // single flag will not resolve the issue
            // echo 'Incomplete Documents<pre>'; print_r($assigned_documents); echo '<hr>';
            // echo 'Completed ID<pre>'; print_r($completed_document_sids); echo '<hr>';
            // echo 'Signed ID<pre>'; print_r($signed_document_sids); echo '<hr>';
            // echo 'No Action Required<pre>'; print_r($no_action_required_sids);
            // echo 'Revoked Documents<pre>'; print_r($revoked_sids);   exit;
            // echo 'Assigned Documents<pre>'; print_r($assigned_documents);
            // exit;


            $data['title']                                  = 'Document(s) Management';
            $data['company_offer_letters']                  = $company_offer_letters;
            $data['active_documents']                       = $active_documents;
            $data['completed_sids']                         = $completed_sids; // completed Documemts Ids
            $data['signed_document_sids']                   = $signed_document_sids; // signed Documemts Ids
            $data['signed_documents']                       = $signed_documents; // signed Documemts
            $data['completed_document_sids']                = $completed_document_sids; // completed Documemts Ids
            $data['completed_documents']                    = $completed_documents; // completed Documemts
            $data['no_action_required_documents']           = $no_action_required_documents; // no action required documents
            $data['no_action_required_payroll_documents']   = $no_action_required_payroll_documents;
            $data['assigned_sids']                          = $assigned_sids;
            $data['revoked_sids']                           = $revoked_sids;
            $data['assigned_documents_history']             = $assigned_documents_history;
            $data['assigned_offer_letter_history']          = $assigned_offer_letter_history;
            $data['archived_assign_document']               = $archived_assign_document;
            $data['user_info']                              = $user_info;
            $data['user_type']                              = $user_type;
            $data['user_sid']                               = $user_sid;
            $data['job_list_sid']                           = $jobs_listing;
            $data['all_documents']                          = $this->hr_documents_management_model->get_total_documents($company_sid, $pp_flag, 1);
            $data['company_name']                           = $company_name;
            $data['employer_email']                         = $employer_email;
            $data['employer_first_name']                    = $employer_first_name;
            $data['employer_last_name']                     = $employer_last_name;

            if (!empty($assigned_offer_letters)) {
                $data['assigned_offer_letter_sid'] = $assigned_offer_letters[0]['document_sid'];
                $data['assigned_offer_letter_status'] = $assigned_offer_letters[0]['status'];
                $data['assigned_offer_letter_archive'] = $assigned_offer_letters[0]['archive'];
            } else {
                $data['assigned_offer_letter_sid'] = '';
                $data['assigned_offer_letter_status'] = '';
                $data['assigned_offer_letter_archive'] = '';
            }

            $data['assigned_documents']             = $assigned_documents; // not completed Documemts


            $data['uncompleted_payroll_documents']  = $uncompleted_payroll_documents;
            $data['completed_payroll_documents']    = $completed_payroll_documents;
            $data['payroll_documents_sids']         = $payroll_documents_sids;
            // Filter assigned documents
            // 01/08/2021

            $data['assigned_documents'] =
                cleanAssignedDocumentsByPermission(
                    $data['assigned_documents'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );

            $data['uncompleted_payroll_documents'] =
                cleanAssignedDocumentsByPermission(
                    $data['uncompleted_payroll_documents'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );

            $data['payroll_documents_sids'] =
                cleanAssignedDocumentsByPermission(
                    $data['payroll_documents_sids'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //
            $data['categories_documents_completed'] =
                cleanAssignedDocumentsByPermission(
                    $data['categories_documents_completed'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //
            $data['completed_offer_letter'] =
                cleanAssignedDocumentsByPermission(
                    $data['completed_offer_letter'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //
            $data['completed_payroll_documents'] =
                cleanAssignedDocumentsByPermission(
                    $data['completed_payroll_documents'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //
            $data['no_action_required_documents'] =
                cleanAssignedDocumentsByPermission(
                    $data['no_action_required_documents'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //    
            $data['no_action_required_payroll_documents'] =
                cleanAssignedDocumentsByPermission(
                    $data['no_action_required_payroll_documents'],
                    $data['session']['employer_detail'],
                    $employeeDepartments
                );
            //

            $confidential_sids = array();
            //
            $confidential_sids =  array_merge($confidential_sids, is_array($data['no_action_required_payroll_documents']) ? array_column($data['no_action_required_payroll_documents'], 'document_sid') : []);
            $confidential_sids =  array_merge($confidential_sids, is_array($data['no_action_required_documents']) ? array_column($data['no_action_required_documents'], 'document_sid') : []);
            $confidential_sids =  array_merge($confidential_sids, is_array($data['assigned_documents']) ? array_column($data['assigned_documents'], 'document_sid') : []);
            $confidential_sids =  array_merge($confidential_sids, is_array($data['completed_offer_letter']) ? array_column($data['completed_offer_letter'], 'document_sid') : []);
            $confidential_sids =  array_merge($confidential_sids, is_array($data['completed_payroll_documents']) ? array_column($data['completed_payroll_documents'], 'document_sid') : []);
            $confidential_sids =  array_merge($confidential_sids, is_array($data['categories_documents_completed']) ? array_column($data['categories_documents_completed'], 'document_sid') : []);
            //
            $confidential_sids = array_flip($confidential_sids);
            $data['confidential_sids'] = $confidential_sids;


            // Set completed/not completes/ no action required 
            // documents
            $data['AllNoActionRequiredDocuments']  = $categorized_docs['no_action_documents'];
            //
            $data['AllCompletedDocuments']  = $categorized_docs['completed_documents'];
            // 
            $data['AllNotCompletedDocuments']  = $assigned_documents;
            //
            if (sizeof($data['AllNotCompletedDocuments'])) {
                $this->hr_documents_management_model->getManagersList($data['AllNotCompletedDocuments']);
            }
            if (sizeof($data['AllCompletedDocuments'])) {
                $this->hr_documents_management_model->getManagersList($data['AllCompletedDocuments']);
            }
            if (sizeof($data['AllNoActionRequiredDocuments'])) {
                $this->hr_documents_management_model->getManagersList($data['AllNoActionRequiredDocuments']);
            }

            // Fetch All Company Managers
            $managers_list = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            $data['managers_list'] = $managers_list;

            $data['offer_letters'] = $this->hr_documents_management_model->get_all_offer_letters($company_sid, 0);
            $data['current_user_signature'] = $this->hr_documents_management_model->get_current_user_signature($company_sid, 'employee', $employer_sid);
            //
            cleanDocumentsByPermission(
                $data,
                $data['session']['employer_detail'],
                false,
                $employeeDepartments
            );
            //
            cleanDocumentsByPermission(
                $data['active_groups'],
                $data['session']['employer_detail'],
                true,
                $employeeDepartments
            );

            //
            cleanDocumentsByPermission(
                $data['in_active_groups'],
                $data['session']['employer_detail'],
                true,
                $employeeDepartments
            );
            // Get departments & teams
            $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
            $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
            //
            $completed_i9 = array();
            //
            if (empty($data['i9_form'])) {
                $data['i9_SD'] =  0;
            } else {
                $data['i9_SD'] = $this->hr_documents_management_model->isSupportingDocumentExist($data['i9_form']['sid'], $user_sid, "i9_assigned");
                //
                if ($data['i9_form']['user_consent'] == 1) {
                    $data['i9_form']["form_status"] = "Current";
                    array_push($completed_i9, $data['i9_form']);
                }
                //
                $i9_history = $this->hr_documents_management_model->is_I9_history_exist($data['i9_form']['sid'], $user_type, $user_sid);
                //
                if (!empty($i9_history)) {
                    foreach ($i9_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_i9, $history);
                    }
                }
            }
            //
            $completed_w9 = array();
            //
            if (empty($data['w9_form'])) {
                $data['w9_SD'] =  0;
            } else {
                $data['w9_SD'] = $this->hr_documents_management_model->isSupportingDocumentExist($data['w9_form']['sid'], $user_sid, "w9_assigned");
                //
                if ($data['w9_form']['user_consent'] == 1) {
                    $data['w9_form']["form_status"] = "Current";
                    array_push($completed_w9, $data['w9_form']);
                }
                //
                $w9_history = $this->hr_documents_management_model->is_W9_history_exist($data['w9_form']['sid'], $user_type, $user_sid);
                //
                if (!empty($w9_history)) {
                    foreach ($w9_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_w9, $history);
                    }
                }
            }
            //
            $completed_w4 = array();
            //
            if (empty($data['w4_form'])) {
                $data['w4_SD'] =  0;
            } else {
                $data['w4_SD'] = $this->hr_documents_management_model->isSupportingDocumentExist($data['w4_form']['sid'], $user_sid, "w4_assigned");
                //
                if ($data['w4_form']['user_consent'] == 1) {
                    $data['w4_form']["form_status"] = "Current";
                    array_push($completed_w4, $data['w4_form']);
                }
                //
                $w4_history = $this->hr_documents_management_model->is_W4_history_exist($data['w4_form']['sid'], $user_type, $user_sid);
                //
                if (!empty($w4_history)) {
                    foreach ($w4_history as $history) {
                        $history["form_status"] = "Previous";
                        array_push($completed_w4, $history);
                    }
                }
            }
            //
            $data['completed_w4'] = $completed_w4;
            $data['completed_w9'] = $completed_w9;
            $data['completed_i9'] = $completed_i9;
            //
            $data['i9_SD'] = empty($data['i9_form']) ? 0 : $this->hr_documents_management_model->isSupportingDocumentExist($data['i9_form']['sid'], $user_sid, "i9_assigned");
            $data['w9_SD'] = empty($data['w9_form']) ? 0 : $this->hr_documents_management_model->isSupportingDocumentExist($data['w9_form']['sid'], $user_sid, "w9_assigned");
            $data['w4_SD'] = empty($data['w4_form']) ? 0 : $this->hr_documents_management_model->isSupportingDocumentExist($data['w4_form']['sid'], $user_sid, "w4_assigned");

            ini_set('memory_limit', -1);
            // Set eeoc form status
            $data['EeocFormStatus'] = $data['session']['portal_detail']['eeo_form_profile_status'];

            $data['pp_flag'] = $pp_flag;

            //
            $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, '');
            //
            // Get all the flow document ids
            // $data['flowDocumentIds'] = $this->hr_documents_management_model->GetFlowDocumentIds(
            //     $user_sid,
            //     $user_type
            // );
            //
            $data['completed_offer_letter']         = $completed_offer_letter;
            $data['uncompleted_offer_letter']       = $uncompleted_offer_letter;

            // check and get state forms
            $companyStateForms = $this->hr_documents_management_model
                ->getCompanyStateForms(
                    $company_sid,
                    $user_sid,
                    $user_type
                );
            //
            $data["companyStateForms"] = $companyStateForms["all"];
            $data["userNotCompletedStateForms"] = $companyStateForms["not_completed"];
            $data["userCompletedStateForms"] = $companyStateForms["completed"];
            //
            if (checkIfAppIsEnabled('performanceevaluation')) {
                $this
                    ->load
                    ->model(
                        "v1/Employee_performance_evaluation_model",
                        "employee_performance_evaluation_model"
                    );
                //
                $data['assignPerformanceDocument'] = $this->employee_performance_evaluation_model->checkEmployeeAssignPerformanceDocument(
                    $user_sid
                );
                //
                if ($data['assignPerformanceDocument']) {
                    $data['pendingPerformanceSection'] = $this->employee_performance_evaluation_model->checkEmployeeUncompletedDocument(
                        $user_sid
                    );
                    //
                    $data['performanceDocumentInfo'] = $this->employee_performance_evaluation_model->getEmployeePerformanceDocumentInfo(
                        $user_sid
                    );
                }
            }
            //
            // _e($data['uncompleted_payroll_documents'],true);
            // _e($data['completed_payroll_documents'],true,true);
            //
            $this->load->view('main/header', $data);
            $this->load->view('hr_documents_management/documents_assignment');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    public function manage_document($user_type, $document_sid, $user_sid, $job_list_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $manager_email = $data['session']['employer_detail']['email'];

            // check whether this document is valid and is assigned - $user_type, $user_sid, $document_sid, $doc = NULL
            if ($document_sid > 0) {
                $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                if (empty($document)) {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                }
            } else { // document not found!
                $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            }

            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                        redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                    }

                    $data = employee_right_nav($user_sid, $data);
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                    $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                    break;
                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $data = applicant_right_nav($user_sid, $job_list_sid);
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($user_type)
                    );

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    break;
            }

            $data['document'] = $document;
            $document_type = $document['document_type'];
            $data['document_type'] = $document_type;
            $data['left_navigation'] = $left_navigation;
            $data['jobs_listing'] = $job_list_sid;
            $data['user_type'] = $user_type;
            $data['title'] = 'Manage Assigned Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');

            if ($this->form_validation->run() == false) {
                $signed_flag = false;

                if ($document['user_consent'] == 1) {
                    $signed_flag = true;
                }

                $data['signed_flag'] = $signed_flag;
                $data['save_post_url'] = current_url();
                $data['employee'] = $data['session']['employer_detail'];
                $data['company_sid'] = $data['session']['company_detail']['sid'];
                $data['users_type'] = $user_type;
                $data['users_sid'] = $user_sid;
                $data['back_url'] = base_url('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid);
                $data['unique_sid'] = '';

                if ($document['acknowledged'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successfully Acknowledged this document';
                    $acknowledgement_button_txt = 'Acknowledged';
                    $acknowledgement_button_css = 'btn-warning';
                    $acknowledgement_button_action = 'javascript:;';
                } else {
                    $acknowledgement_status = '<strong class="text-danger">Document Status:</strong> ' . ucwords($user_type) . ' has not yet acknowledged this document';
                    $acknowledgement_button_txt = 'Acknowledge Receipt for ' . ucwords($user_type);
                    $acknowledgement_button_css = 'blue-button';
                    $acknowledgement_button_action = 'func_acknowledge_document();';
                }

                $acknowledgment_action_title = 'Document Action: <b>Acknowledgement Required!</b>';
                $acknowledgment_action_desc = '<b>Acknowledge the receipt of this document</b>';

                if ($document_type == 'uploaded') {
                    $download_action_title = 'Document Action: <b>Download Acknowledgement Required!</b>';
                    $download_action_desc = '<b>' . ucwords($user_type) . ' has not yet downloaded this document.</b>';

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successfully downloaded this document';
                        $download_button_txt = 'Downloaded';
                        $download_button_css = 'btn-warning';
                        $download_button_action = 'javascript:;';
                        $download_button_type = 'text';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> ' . ucwords($user_type) . ' has not yet downloaded this document';
                        $download_button_txt = 'Acknowledge Download Receipt for ' . ucwords($user_type);
                        $download_button_css = 'blue-button';
                        $download_button_action = 'func_acknowledge_document_download();';
                        $download_button_type = 'button';
                    }
                } else { // generated document
                    $download_action_title = 'Document Action: <b>Download Acknowledgement Required!</b>';
                    $download_action_desc = '<b>' . ucwords($user_type) . ' has not yet downloaded the document.</b>';
                    $download_button_action = 'javascript:;';

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successfully printed this document';
                        $download_button_txt = 'Downloaded';
                        $download_button_css = 'btn-warning';
                        $download_button_type = 'text';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> ' . ucwords($user_type) . ' has not yet printed this document';
                        $download_button_txt = 'Acknowledge Download Receipt for ' . ucwords($user_type);
                        $download_button_css = 'blue-button';
                        $download_button_action = 'func_acknowledge_document_download();';
                        $download_button_type = 'button';
                    }
                }

                if ($document['uploaded'] == 1) {
                    $uploaded_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has already uploaded a Signed and Completed copy of this document. <br><br><b class="text-danger">As an Admin you can replace the uploaded the document for ' . ucwords($user_type) . '.</b><br><br>';
                    $uploaded_button_txt = 'Re-Upload Document';
                    $uploaded_button_css = 'btn-warning';
                } else {
                    $uploaded_status = '<strong class="text-danger">Document Status:</strong> Upload the Signed and Completed Document, ' . ucwords($user_type) . ' has not uploaded this document';
                    $uploaded_button_txt = 'Upload Document';
                    $uploaded_button_css = 'blue-button';
                }

                $uploaded_action_title = 'Document Action: <b>Upload Signed and Completed Copy!</b>';
                $uploaded_action_desc = '<b>Please sign this document and upload the Signed and Completed copy.</b>';

                $data['download_action_title'] = $download_action_title;
                $data['download_action_desc'] = $download_action_desc;
                $data['download_button_txt'] = $download_button_txt;
                $data['download_button_action'] = $download_button_action;
                $data['download_status'] = $download_status;
                $data['download_button_css'] = $download_button_css;
                $data['download_button_type'] = $download_button_type;

                $data['original_download_url'] = base_url('hr_documents_management/download_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original');

                $data['original_print_url'] = base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original');

                $data['submitted_download_url'] = base_url('hr_documents_management/download_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/submitted');
                $data['submitted_print_url'] = base_url('hr_documents_management/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/submitted');
                $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                $data['acknowledgement_status'] = $acknowledgement_status;
                $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                $data['acknowledgement_button_action'] = $acknowledgement_button_action;
                $data['uploaded_action_title'] = $uploaded_action_title;
                $data['uploaded_action_desc'] = $uploaded_action_desc;
                $data['uploaded_button_txt'] = $uploaded_button_txt;
                $data['uploaded_status'] = $uploaded_status;
                $data['uploaded_button_css'] = $uploaded_button_css;
                $data['pp_flag'] = $data['session']['company_detail']['pay_plan_flag'];
                //
                $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, '');
                //
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/manage_hr_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'acknowledge_document':
                        $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document['sid']);

                        $action_track = array(
                            'company_sid' => $company_sid,
                            'user_type' => $user_type,
                            'user_sid' => $user_sid,
                            'manager_sid' => $employer_sid,
                            'manager_email' => $manager_email,
                            'document_type' => $document['document_type'],
                            'document_title' => $document['document_title'],
                            'document_description' => $document['document_description'],
                            'document_original_name' => $document['document_original_name'],
                            'document_extension' => $document['document_extension'],
                            'document_s3_name' => $document['document_s3_name'],
                            'document_sid' => $document['document_sid'],
                            'documents_assigned_sid' => $document['sid'],
                            'acknowledged' => 1,
                            'acknowledged_date' => date('Y-m-d H:i:s'),
                            'ip' => getUserIP(),
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']
                        );

                        $this->hr_documents_management_model->manager_document_activity_track($action_track);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                        redirect('hr_documents_management/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                        break;
                    case 'upload_document':
                        if ($_SERVER['HTTP_HOST'] == 'localhost') {
                            $aws_file_name = '0003-d_6-1542874444-39O.jpg';
                        } else {
                            $aws_file_name = upload_file_to_aws('upload_file', $company_sid, $document['document_title'] . '_' . $document_sid, time());
                        }

                        $uploaded_file = '';

                        if ($aws_file_name != 'error') {
                            $uploaded_file = $aws_file_name;
                        }

                        if (!empty($uploaded_file)) {
                            $this->hr_documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);

                            $action_track = array(
                                'company_sid' => $company_sid,
                                'user_type' => $user_type,
                                'user_sid' => $user_sid,
                                'manager_sid' => $employer_sid,
                                'manager_email' => $manager_email,
                                'document_type' => $document['document_type'],
                                'document_title' => $document['document_title'],
                                'document_description' => $document['document_description'],
                                'document_original_name' => $document['document_original_name'],
                                'document_extension' => $document['document_extension'],
                                'document_s3_name' => $document['document_s3_name'],
                                'document_sid' => $document['document_sid'],
                                'documents_assigned_sid' => $document['sid'],
                                'uploaded' => 1,
                                'uploaded_date' => date('Y-m-d H:i:s'),
                                'uploaded_file' => $uploaded_file,
                                'ip' => getUserIP(),
                                'user_agent' => $_SERVER['HTTP_USER_AGENT']
                            );

                            $this->hr_documents_management_model->manager_document_activity_track($action_track);
                            $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                        }

                        redirect('hr_documents_management/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                        break;
                    case 'acknowledge_document_download':
                        $this->hr_documents_management_model->update_download_status($user_type, $user_sid, $document['sid']);
                        $action_track = array(
                            'company_sid' => $company_sid,
                            'user_type' => $user_type,
                            'user_sid' => $user_sid,
                            'manager_sid' => $employer_sid,
                            'manager_email' => $manager_email,
                            'document_type' => $document['document_type'],
                            'document_title' => $document['document_title'],
                            'document_description' => $document['document_description'],
                            'document_original_name' => $document['document_original_name'],
                            'document_extension' => $document['document_extension'],
                            'document_s3_name' => $document['document_s3_name'],
                            'document_sid' => $document['document_sid'],
                            'documents_assigned_sid' => $document['sid'],
                            'downloaded' => 1,
                            'downloaded_date' => date('Y-m-d H:i:s'),
                            'ip' => getUserIP(),
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']
                        );

                        $this->hr_documents_management_model->manager_document_activity_track($action_track);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Download Acknowledged!');
                        redirect('hr_documents_management/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function sign_authorized_signature_document($user_type, $document_sid, $user_sid, $job_list_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid            = $data['session']['company_detail']['sid'];
            $company_name           = $data['session']['company_detail']['CompanyName'];
            $employer_sid           = $data['session']['employer_detail']['sid'];
            $employer_first_name    = $data['session']['employer_detail']['first_name'];
            $employer_last_name     = $data['session']['employer_detail']['last_name'];
            $employer_email         = $data['session']['employer_detail']['email'];

            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                        redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                    }

                    $data = employee_right_nav($user_sid, $data);
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                    $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                    break;
                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $data = applicant_right_nav($user_sid, $job_list_sid);
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($user_type)
                    );

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    break;
            }

            // check whether this document is valid and is assigned - $user_type, $user_sid, $document_sid, $doc = NULL
            if ($document_sid > 0) {
                $document = $this->hr_documents_management_model->get_authorized_signature_document($user_type, $user_sid, $document_sid);

                if (!empty($document)) {
                    //
                    if (!empty($document['form_input_data'])) {
                        $form_input_data = unserialize($document['form_input_data']);
                        $data['form_input_data'] = json_encode(json_decode($form_input_data, true));
                    }
                    //
                    if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) {

                        if (!empty($document['authorized_signature'])) {
                            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        }

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
                    } else if (!empty($document['authorized_signature'])) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }
                    }

                    $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid'], 1);
                    $document['document_description'] = $document_content;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                }
            } else { // document not found!
                $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            }

            // Fetch All Company Managers
            $managers_list = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            $data['managers_list'] = $managers_list;

            $data['current_user_signature'] = $this->hr_documents_management_model->get_current_user_signature($company_sid, 'employee', $employer_sid);

            $data['document'] = $document;
            $document_type = $document['document_type'];
            $data['document_type'] = $document_type;
            $data['left_navigation'] = $left_navigation;
            $data['jobs_listing'] = $job_list_sid;
            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['title'] = 'Manage Assigned Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');

            if ($this->form_validation->run() == false) {
                $data['company_name']           = $company_name;
                $data['employer_sid']           = $employer_sid;
                $data['employer_first_name']    = $employer_first_name;
                $data['employer_last_name']     = $employer_last_name;
                $data['employer_email']         = $employer_email;


                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/document_authorized_signature');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                $data_to_update = array();
                $data_to_update['authorized_signature'] = $this->input->post('authorized_signature');
                $data_to_update['authorized_signature_by'] = $this->input->post('employer_sid');
                $data_to_update['authorized_signature_date'] = date('Y-m-d');

                $this->hr_documents_management_model->update_documents($document_sid, $data_to_update, 'documents_assigned');

                $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Activated!');
                redirect('hr_documents_management/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function assign_authorized_document()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $assigned_by_sid = $session['employer_detail']['sid'];
            $company_sid = $session['company_detail']['sid'];

            $form_post = $this->input->post();
            $document_sid = $form_post['document_sid'];
            $assign_to = $form_post['assign_to'];

            $previous_assign = $this->hr_documents_management_model->get_authorized_document_assign_manager($company_sid, $document_sid);
            //
            $new_assign_manger = explode(',', $assign_to);
            //
            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                $assign_to,
                $document_sid,
                $company_sid,
                $assigned_by_sid
            );
            //
            $data_to_update = array();
            $data_to_update['authorized_signature'] = NULL;
            $data_to_update['authorized_signature_by'] = 0;
            $data_to_update['authorized_signature_date'] = NULL;
            //
            $this->hr_documents_management_model->update_documents($document_sid, $data_to_update, 'documents_assigned');
            //
            echo 'success';
        }
    }

    public function get_authorized_document_assigned_user($company_sid, $assign_document_sid)
    {
        $assigned_user = $this->hr_documents_management_model->fetch_authorized_doc_assign_user($company_sid, $assign_document_sid);

        $return_data = array();
        if (!empty($assigned_user['row'])) {
            $assigned_to_sid    = $assigned_user['row']['assigned_to_sid'];
            $assigned_by_date   = $assigned_user['row']['assigned_by_date'];

            $employee_info  = db_get_employee_profile($assigned_to_sid);
            $user_name      = remakeEmployeeName($employee_info[0]);
            $assign_date    = date_with_time($assigned_by_date);

            //
            $ids = '';
            if (sizeof($assigned_user['ids'])) {
                foreach ($assigned_user['ids'] as $k => $v) {
                    $employee_info  = db_get_employee_profile($v);
                    $ids .= '<p>' . (remakeEmployeeName($employee_info[0])) . ',</p>';
                }
                //
                $ids = rtrim($ids, ',</p>');
            }

            $return_data['assign_sid'] = $assigned_to_sid;
            $return_data['assign_sids'] = $assigned_user['ids'];
            $return_data['assign_to_name'] = $ids;
            $return_data['assign_to'] = '<strong>' . $user_name . '</strong>';
            $return_data['assign_date'] = '<strong>' . $assign_date . '</strong>';
            echo json_encode($return_data);
        } else {

            //
            $ids = '';
            if ($assigned_user && sizeof($assigned_user['ids'])) {
                foreach ($assigned_user['ids'] as $k => $v) {
                    $employee_info  = db_get_employee_profile($v);
                    $ids .= '<p>' . (remakeEmployeeName($employee_info[0])) . ',</p>';
                }
                //
                $ids = rtrim($ids, ',</p>');
            }
            $return_data['assign_sid'] = 0;
            $return_data['assign_sids'] = $assigned_user['ids'];
            $return_data['assign_to_name'] = $ids;
            $return_data['assign_to'] = '<strong>--</strong>';
            $return_data['assign_date'] = '<strong>--</strong>';
            echo json_encode($return_data);
        }
    }

    public function authorized_document_listing()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {

            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $company_email                                                      = $data['session']['company_detail']['email'];
            $company_name                                                       = $data['session']['company_detail']['CompanyName'];
            $employers_details                                                  = $data['session']['employer_detail'];
            $employer_sid                                                       = $employers_details['sid'];
            $access_level                                                       = $employers_details['access_level'];
            $ats_active_job_flag                                                = null; // get both active and inactive jobs
            $security_details                                                   = db_get_access_level_details($employer_sid);
            $data['security_details']                                           = $security_details;
            // Get inactive employee and applicants
            getCompanyEmsStatusBySid($company_sid, true);

            $inactiveEmployees = $this->hr_documents_management_model->getAllCompanyInactiveEmployee($company_sid);
            $inactiveApplicants = $this->hr_documents_management_model->getAllCompanyInactiveApplicant($company_sid);
            $total_documents                                                    = $this->hr_documents_management_model->get_all_assigned_auth_documents(
                $company_sid,
                $employer_sid,
                $inactiveEmployees,
                $inactiveApplicants
            );
            $documents_count                                                    = count($total_documents);

            $records_per_page                                                   = PAGINATION_RECORDS_PER_PAGE;
            $page                                                               = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
            $my_offset                                                          = 0;
            $choice                                                             = $documents_count / $records_per_page;

            if ($page > 1) {
                $my_offset                                                      = ($page - 1) * $records_per_page;
            }

            $baseUrl                                                            = base_url('authorized_document');
            $uri_segment                                                        = 2;
            $config                                                             = array();
            $config["base_url"]                                                 = $baseUrl;
            $config["total_rows"]                                               = $documents_count;
            $config["per_page"]                                                 = $records_per_page;
            $config['uri_segment']                                              = $uri_segment;
            $config['num_links']                                                = ceil($choice);
            $config['use_page_numbers']                                         = true;
            $config['full_tag_open']                                            = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close']                                           = '</ul></nav><!--pagination-->';
            $config['first_link']                                               = '&laquo; First';
            $config['first_tag_open']                                           = '<li class="prev page">';
            $config['first_tag_close']                                          = '</li>';
            $config['last_link']                                                = 'Last &raquo;';
            $config['last_tag_open']                                            = '<li class="next page">';
            $config['last_tag_close']                                           = '</li>';
            $config['next_link']                                                = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open']                                            = '<li class="next page">';
            $config['next_tag_close']                                           = '</li>';
            $config['prev_link']                                                = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open']                                            = '<li class="prev page">';
            $config['prev_tag_close']                                           = '</li>';
            $config['cur_tag_open']                                             = '<li class="active"><a href="">';
            $config['cur_tag_close']                                            = '</a></li>';
            $config['num_tag_open']                                             = '<li class="page">';
            $config['num_tag_close']                                            = '</li>';

            $this->pagination->initialize($config);
            $links                                                              = $this->pagination->create_links();

            $documents_list = $this->hr_documents_management_model->get_all_paginate_auth_documents(
                $company_sid,
                $employer_sid,
                $records_per_page,
                $my_offset,
                $inactiveEmployees,
                $inactiveApplicants
            );

            $data['title']          = 'Authorized Documents';
            $data['employer_sid']   = $employer_sid;
            $data['employer']       = $employer_sid;
            $data['employer']       = $employers_details;
            $data['documents_list'] = $documents_list;
            $data['links']          = $links;
            //
            $data['employee'] = $data['session']['employer_detail'];
            //
            $data['load_view'] = 'old';
            //
            $this->load->view('main/header', $data);
            if (!$data['load_view']) {
                $this->load->view('hr_documents_management/authorized_document_assigned_listing');
            } else {
                $this->load->view('hr_documents_management/authorized_document_assigned_listing_ems');
            }
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function view_assigned_authorized_document($doc_type, $assign_document_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $company_sid            = $data['session']['company_detail']['sid'];
            $company_name           = $data['session']['company_detail']['CompanyName'];
            $employer_sid           = $data['session']['employer_detail']['sid'];
            $employer_first_name    = $data['session']['employer_detail']['first_name'];
            $employer_last_name     = $data['session']['employer_detail']['last_name'];
            $employer_email         = $data['session']['employer_detail']['email'];

            // check whether this document is valid and is assigned - $user_type, $user_sid, $document_sid, $doc = NULL
            if ($assign_document_sid > 0) {
                //
                $data['assignedDocuments'] = $this->hr_documents_management_model->getManagersByAssignedDocument(
                    $assign_document_sid
                );

                //

                if ($doc_type == 'o') {
                    $document = $this->hr_documents_management_model->get_assign_authorized_offer_letter($company_sid, $assign_document_sid);
                } else {

                    $document = $this->hr_documents_management_model->get_assign_authorized_document($company_sid, $assign_document_sid);
                }
                if (!empty($document)) {
                    //
                    if (!empty($document['form_input_data'])) {
                        $form_input_data = unserialize($document['form_input_data']);
                        $data['form_input_data'] = json_encode(json_decode($form_input_data, true));
                    }
                    //
                    if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) {

                        if (!empty($document['authorized_signature'])) {
                            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        }

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 1) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 0) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }
                    }

                    $document_content = replace_tags_for_document($company_sid, $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
                    $document['document_description'] = $document_content;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('hr_documents_management/authorized_document', 'refresh');
                }
            } else { // document not found!
                $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                redirect('hr_documents_management/authorized_document', 'refresh');
            }



            // Fetch All Company Managers
            $managers_list = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
            $data['managers_list'] = $managers_list;

            $data['current_user_signature'] = $this->hr_documents_management_model->get_current_user_signature($company_sid, 'employee', $employer_sid);

            $user_type = '';
            $user_name = '';

            if ($document['user_type'] == 'applicant') {
                $user_type = 'Applicant';
                $user_name = get_applicant_name($document['user_sid']);
            } else {
                $user_type = 'Employee';
                $employee_info = db_get_employee_profile($document['user_sid']);
                $employee_name = remakeEmployeeName([
                    'first_name' => $employee_info[0]['first_name'],
                    'last_name' => $employee_info[0]['last_name'],
                    'access_level' => $employee_info[0]['access_level'],
                    'access_level_plus' => $employee_info[0]['access_level_plus'],
                    'is_executive_admin' => $employee_info[0]['is_executive_admin'],
                    'pay_plan_flag' => $employee_info[0]['pay_plan_flag'],
                    'job_title' => $employee_info[0]['job_title'],
                ]);

                $user_name = $employee_name;
            }

            $data['document']               = $document;
            $data['title']                  = 'Sign Authorized Documents';
            $data['company_sid']            = $company_sid;
            $data['company_name']           = $company_name;
            $data['employer_sid']           = $employer_sid;
            $data['employer_first_name']    = $employer_first_name;
            $data['employer_last_name']     = $employer_last_name;
            $data['employer_email']         = $employer_email;
            $data['pop_up_flag']            = 0;
            $data['assign_doc_user_type']   = $user_type;
            $data['assign_doc_user_name']   = $user_name;

            //
            $data['employee'] = $data['session']['employer_detail'];
            //
            $data['load_view'] = 'old';
            //
            $this->load->view('main/header', $data);
            if (!$data['load_view']) {
                $this->load->view('hr_documents_management/assigned_authorized_document');
            } else {
                $this->load->view('hr_documents_management/assigned_authorized_document_ems');
            }
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }

    public function save_authorized_e_signature()
    {
        if ($this->session->has_userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $company_sid = $session['company_detail']['sid'];

            $form_post = $this->input->post();
            $authorized_signature_by = $form_post['user_sid'];
            $document_sid = $form_post['document_sid'];
            $authorized_signature = $form_post['authorized_signature'];


            $data_to_update = array();
            $data_to_update['authorized_signature'] = $authorized_signature;
            $data_to_update['authorized_signature_by'] = $authorized_signature_by;
            $data_to_update['authorized_signature_date'] = date('Y-m-d H:i:s');

            $this->hr_documents_management_model->update_documents($document_sid, $data_to_update, 'documents_assigned');

            $is_assigned_to_me = $this->hr_documents_management_model->is_assigned_authorized_document_to_me($company_sid, $document_sid, $authorized_signature_by);
            if ($is_assigned_to_me == 'yes') {
                $update_sign_document = array();
                $update_sign_document['assigned_to_signature'] = $authorized_signature;
                $update_sign_document['signature_date'] = date('Y-m-d H:i:s');

                $this->hr_documents_management_model->update_assigned_authorized_document($document_sid, $authorized_signature_by, $update_sign_document);
            } else {
                $insert_sign_document = array();
                $insert_sign_document['company_sid'] = $company_sid;
                $insert_sign_document['document_assigned_sid'] = $document_sid;
                $insert_sign_document['assigned_by_sid'] = $authorized_signature_by;
                $insert_sign_document['assigned_by_date'] = date('Y-m-d H:i:s');
                $insert_sign_document['assigned_to_sid'] = $authorized_signature_by;
                $insert_sign_document['assigned_to_signature'] = $authorized_signature;
                $insert_sign_document['signature_date'] = date('Y-m-d H:i:s');
                $insert_sign_document['assigned_status'] = 1;

                $this->hr_documents_management_model->deactivate_assign_authorized_documents($company_sid, $document_sid);
                $this->hr_documents_management_model->assign_authorized_document_to_user($insert_sign_document);
            }
        }
    }

    public function assign_offer_letter()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $user_info = '';
            $company_sid    = $session['company_detail']['sid'];
            $company_name   = $session['company_detail']['CompanyName'];
            $employer_sid   = $session['employer_detail']['sid'];

            $perform_action = $this->input->post('perform_action');
            $user_sid = $this->input->post('user_sid');
            $user_type = $this->input->post('user_type');
            $offer_letter_sid = $this->input->post('offer_letter_sid');
            $offer_letter_type = $this->input->post('offer_letter_type');
            $letter_body = $this->input->post('letter_body');

            if ($user_type == 'applicant') {
                $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);
            } else if ($user_type == 'employee') {
                $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
            }


            $offer_letter_title = $this->hr_documents_management_model->get_assigned_offer_letter_title($offer_letter_sid);

            $letter_name = $offer_letter_title;
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
            $data_to_insert['assigned_by'] = $employer_sid;
            $data_to_insert['user_type'] = $user_type;
            $data_to_insert['user_sid'] = $user_sid;
            $data_to_insert['document_type'] = 'offer_letter';
            $data_to_insert['document_sid'] = $offer_letter_sid;
            $data_to_insert['document_title'] = $letter_name;
            $data_to_insert['document_description'] = $letter_body;
            $data_to_insert['offer_letter_type'] = $offer_letter_type;

            // Get offer letter type and file
            $lets = $this->hr_documents_management_model->getOfferLetterBySId(
                $offer_letter_sid,
                [
                    'letter_type',
                    'uploaded_document_s3_name'
                ]
            );

            if (sizeof($lets)) {
                //
                if ($lets['letter_type'] == 'hybrid_document') {
                    //
                    $data_to_insert['document_s3_name'] = $lets['uploaded_document_s3_name'];
                }
            }

            if ($perform_action == "assign_uploaded_offer_letter") {

                $document_original_name   = $this->input->post('document_original_name');
                $offer_letter_file_info   = explode(".", $document_original_name);
                $offer_letter_name        = $offer_letter_file_info[0];
                $offer_letter_extension   = $offer_letter_file_info[1];

                $data_to_insert['document_original_name'] = $document_original_name;
                $data_to_insert['document_extension'] = $offer_letter_extension;
                $data_to_insert['document_s3_name'] = $this->input->post('document_s3_name');
            }

            $already_assigned = $this->hr_documents_management_model->check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, 'offer_letter');

            if (!empty($already_assigned)) {
                foreach ($already_assigned as $key => $previous_offer_letter) {
                    $previous_assigned_sid = $previous_offer_letter['sid'];
                    $already_moved = $this->hr_documents_management_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                    if ($already_moved == 'no') {
                        $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                        unset($previous_offer_letter['sid']);
                        $this->hr_documents_management_model->insert_documents_assignment_record_history($previous_offer_letter);
                    }
                }
            }

            $this->hr_documents_management_model->disable_all_previous_letter($company_sid, $user_type, $user_sid, 'offer_letter');

            $data_to_insert['status'] = 1;
            $verification_key = random_key(80);
            $assignOfferLetterId = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
            $this->hr_documents_management_model->set_offer_letter_verification_key($user_sid, $verification_key, $user_type);

            // Managers handling
            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                $this->input->post('gen_offer_letter_signers'),
                $assignOfferLetterId,
                $company_sid,
                $employer_sid
            );

            $this->session->set_flashdata('message', '<strong>Success: </strong> Offer letter / Pay plan assigned successfully!');

            if ($user_type == 'applicant') {

                $applicant_sid = $user_info['sid'];
                $applicant_email = $user_info['email'];
                $applicant_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

                $url = base_url() . 'onboarding/my_offer_letter/' . $verification_key;

                $emailTemplateBody = 'Dear ' . $applicant_name . ', <br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Congratulations and Welcome to ' . $company_name . '</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . 'We have attached an offer letter with this email for you.' . '<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please complete this offer letter by clicking on the link below.' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Offer Letter</a>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<em>If you have any questions at all, please feel free to send us a note at any time and we will get back to you as quickly as we can.</em>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>The HR Team at ' . $company_name . '</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email; Please Do Not reply!</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $applicant_email;
                $subject = 'Offer Letter';
                $from_name = ucwords(STORE_DOMAIN);
                $email_hf = message_header_footer_domain($company_sid, $company_name);
                $body = $email_hf['header']
                    . $emailTemplateBody
                    . $email_hf['footer'];
                // sendMail($from, $to, $subject, $body, $from_name); Don't send email here as steven said
                $this->session->set_flashdata('message', '<strong>Success: </strong> Offer letter / Pay plan assigned successfully!');
                $job_list_sid = $this->input->post('job_list_sid');
                redirect(base_url('hr_documents_management/documents_assignment') . '/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            } else {
                redirect(base_url('hr_documents_management/documents_assignment') . '/' . $user_type . '/' . $user_sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function revoke_offer_letter()
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $user_info = '';
            $company_sid    = $session['company_detail']['sid'];
            $company_name   = $session['company_detail']['CompanyName'];
            $employer_sid   = $session['employer_detail']['sid'];

            $perform_action = $this->input->post('perform_action');
            $offer_letter_sid = $this->input->post('offer_letter_sid');
            $user_sid = $this->input->post('user_sid');
            $user_type = $this->input->post('user_type');

            $this->hr_documents_management_model->revoke_assigned_offer_letter($user_type, $user_sid, $offer_letter_sid);

            $this->session->set_flashdata('message', '<strong>Success: </strong> Offer Letter Revoked Successfully!');

            if ($user_type == 'applicant') {
                $job_list_sid = $this->input->post('job_list_sid');
                redirect(base_url('hr_documents_management/documents_assignment') . '/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            } else {
                redirect(base_url('hr_documents_management/documents_assignment') . '/' . $user_type . '/' . $user_sid, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function my_documents()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assignment Documents';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $assigned_sids                          = array();
            $no_action_required_sids                = array();
            $completed_sids                         = array();
            $revoked_sids                           = array();
            $completed_documents                    = array();
            $no_action_required_documents           = array();
            $payroll_documents_sids                 = array();
            $uncompleted_payroll_documents          = array();
            $completed_payroll_documents            = array();
            $uncompleted_offer_letter               = array();
            $completed_offer_letter                 = array();
            $signed_documents                       = array();
            $no_action_required_payroll_documents   = array();

            $sendGroupEmail = 0;
            $groups_assign = $this->hr_documents_management_model->get_all_documents_group_assigned($company_sid, 'employee', $employer_sid);

            if (!empty($groups_assign)) {
                foreach ($groups_assign as $value) {
                    $system_document = $this->hr_documents_management_model->get_document_group($value['group_sid']);

                    // General Documents
                    foreach ($system_document as $gk => $gv) {
                        //
                        if (!in_array($gk, [
                            'direct_deposit',
                            'drivers_license',
                            'occupational_license',
                            'emergency_contacts',
                            'dependents'
                        ])) continue;
                        //
                        if ($gv == 1) {
                            if ($this->hr_documents_management_model->checkAndAssignGeneralDocument(
                                $employer_sid,
                                'employee',
                                $company_sid,
                                $gk,
                                $employer_sid
                            )) {
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }

                    if (isset($system_document['w4']) && $system_document['w4'] == 1) {
                        $is_w4_assign = $this->hr_documents_management_model->check_w4_form_exist('employee', $employer_sid);
                        if (empty($is_w4_assign)) {
                            $w4_data_to_insert = array();
                            $w4_data_to_insert['employer_sid'] = $employer_sid;
                            $w4_data_to_insert['company_sid'] = $company_sid;
                            $w4_data_to_insert['user_type'] = 'employee';
                            $w4_data_to_insert['sent_status'] = 1;
                            $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w4_data_to_insert['status'] = 1;
                            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if (isset($system_document['w9']) && $system_document['w9'] == 1) {
                        $is_w9_assign = $this->hr_documents_management_model->check_w9_form_exist('employee', $employer_sid);

                        if (empty($is_w9_assign)) {
                            $w9_data_to_insert = array();
                            $w9_data_to_insert['user_sid'] = $employer_sid;
                            $w9_data_to_insert['company_sid'] = $company_sid;
                            $w9_data_to_insert['user_type'] = 'employee';
                            $w9_data_to_insert['sent_status'] = 1;
                            $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $w9_data_to_insert['status'] = 1;
                            $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if (isset($system_document['i9']) && $system_document['i9'] == 1) {
                        $is_i9_assign = $this->hr_documents_management_model->check_i9_exist('employee', $employer_sid);

                        if (empty($is_i9_assign)) {
                            $i9_data_to_insert = array();
                            $i9_data_to_insert['user_sid'] = $employer_sid;
                            $i9_data_to_insert['user_type'] = 'employee';
                            $i9_data_to_insert['company_sid'] = $company_sid;
                            $i9_data_to_insert['sent_status'] = 1;
                            $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                            $i9_data_to_insert['status'] = 1;
                            $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }

                    if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_employee_document_center']) {
                        if (!empty($system_document['eeoc']) && $system_document['eeoc'] == 1) {
                            $is_eeoc_assign = $this->hr_documents_management_model->check_eeoc_exist($employer_sid, 'employee');

                            if (empty($is_eeoc_assign)) {
                                $eeoc_data_to_insert = array();
                                $eeoc_data_to_insert['application_sid'] = $employer_sid;
                                $eeoc_data_to_insert['users_type'] = 'employee';
                                $eeoc_data_to_insert['status'] = 1;
                                $eeoc_data_to_insert['is_expired'] = 0;
                                $eeoc_data_to_insert['portal_applicant_jobs_list_sid'] = $jobs_listing;
                                $eeoc_data_to_insert['last_sent_at'] = getSystemDate();
                                $eeoc_data_to_insert['assigned_at'] = getSystemDate();
                                $eeoc_data_to_insert['last_assigned_by'] = 0;
                                //
                                $this->hr_documents_management_model->insert_eeoc_form_record($eeoc_data_to_insert);
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }
            }

            $assign_group_documents = $this->hr_documents_management_model->get_assign_group_documents($company_sid, 'employee', $employer_sid);

            if (!empty($assign_group_documents)) {
                foreach ($assign_group_documents as $key => $assign_group_document) {
                    $is_document_assign = $this->hr_documents_management_model->check_document_already_assigned($company_sid, 'employee', $employer_sid, $assign_group_document['document_sid']);
                    if ($is_document_assign == 0 && $assign_group_document['document_sid'] > 0) {
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $assign_group_document['document_sid']);

                        if (!empty($document)) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                            $data_to_insert['assigned_by'] = $assign_group_document['assigned_by_sid'];
                            $data_to_insert['user_type'] = 'employee';
                            $data_to_insert['user_sid'] = $employer_sid;
                            $data_to_insert['document_type'] = $document['document_type'];
                            $data_to_insert['document_sid'] = $assign_group_document['document_sid'];
                            $data_to_insert['status'] = 1;
                            $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                            $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                            $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                            $data_to_insert['document_title'] = $document['document_title'];
                            $data_to_insert['document_description'] = $document['document_description'];
                            $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                            $data_to_insert['signature_required'] = $document['signature_required'];
                            $data_to_insert['download_required'] = $document['download_required'];
                            $data_to_insert['is_confidential'] = $document['is_confidential'];
                            $data_to_insert['is_required'] = $document['is_required'];
                            $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];
                            //
                            $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            //
                            if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                                $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $document['document_description']);
                                //
                                if ($isAuthorized == 1) {
                                    // Managers handling
                                    $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                        $document['managers_list'],
                                        $assignment_sid,
                                        $company_sid,
                                        $assign_group_document['assigned_by_sid']
                                    );
                                }
                            }
                            //
                            if ($document['has_approval_flow'] == 1) {
                                $this->HandleApprovalFlow(
                                    $assignment_sid,
                                    $document['document_approval_note'],
                                    $document["document_approval_employees"],
                                    0,
                                    $document['managers_list']
                                );
                            } else {
                                //
                                $sendGroupEmail = 1;
                            }
                        }
                    }
                }
            }

            // state forms from group
            $this->hr_documents_management_model
                ->assignGroupDocumentsToUser(
                    $employer_sid,
                    "employee",
                    $sendGroupEmail
                );

            if ($sendGroupEmail == 1) {
                //
                $hf = message_header_footer(
                    $company_sid,
                    ucwords($data['session']['company_detail']['CompanyName'])
                );
                //
                $replacement_array = array();
                $replacement_array['contact-name'] = ucwords($data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name']);
                $replacement_array['baseurl'] = base_url();
                //
                $extra_user_info = array();
                $extra_user_info["user_type"] = 'employee';
                $extra_user_info["user_sid"] = $employer_sid;
                //
                $this->load->model('Hr_documents_management_model', 'HRDMM');
                if ($this->HRDMM->isActiveUser($employer_sid)) {
                    //
                    if ($this->hr_documents_management_model->doSendEmail($employer_sid, 'employee', "HREMS9")) {
                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $data['session']['employer_detail']['email'], $replacement_array, $hf, 1, $extra_user_info);
                    }
                }
            }

            $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employer_sid, 0, 1, 0, 0, 1, 1);
            //
            $history_doc_sids = array();
            //
            $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
            $documents_management_sids = $payroll_sids['documents_management_sids'];
            $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];
            //
            foreach ($assigned_documents as $key => $assigned_document) {
                //
                if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                    $assigned_document['pay_roll_catgory'] = 1;
                } else {
                    $assigned_document['pay_roll_catgory'] = 0;
                }
                //
                if ($assigned_document['document_sid'] == 0) {
                    if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                        if ($assigned_document['pay_roll_catgory'] == 0) {
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                            if ($assigned_document['user_consent'] == 1 && $assigned_document['document_sid'] == 0) {
                                $no_action_required_payroll_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            }
                        }
                    }
                } else {
                    //
                    $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                    //
                    if ($assigned_document['user_consent'] == 1) {
                        $assigned_document['archive'] = 0;
                    }
                    //
                    if ($assigned_document['archive'] == 0) {
                        $is_magic_tag_exist = 0;
                        $is_document_completed = 0;
                        //
                        //check Document Previous History
                        $previous_history = $this->hr_documents_management_model->check_if_document_has_history('employee', $employer_sid, $assigned_document['sid']);
                        //
                        if (!empty($previous_history)) {
                            array_push($history_doc_sids, $assigned_document['sid']);
                        }
                        //
                        if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                            $document_body = $assigned_document['document_description'];
                            $magic_codes = array('{{signature}}', '{{inital}}');

                            //
                            $documentBodyOld = $document_body;
                            $document_body = $document_body ? magicCodeCorrection($document_body) : $document_body;

                            if ($documentBodyOld != $document_body) {
                                updateDocumentCorrectionDesc($document_body, $assigned_document['sid'], $assigned_document['document_sid']);
                            }



                            if (str_replace($magic_codes, '', $document_body) != $document_body) {
                                $is_magic_tag_exist = 1;
                            }
                        }
                        //
                        if ($assigned_document['document_type'] != 'offer_letter') {
                            if ($assigned_document['status'] == 1) {
                                if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {
                                    if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                        if ($is_magic_tag_exist == 1) {
                                            if ($assigned_document['uploaded'] == 1) {
                                                $is_document_completed = 1;
                                            } else {
                                                $is_document_completed = 0;
                                            }
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['acknowledgment_required'] == 1) {
                                        if ($assigned_document['acknowledged'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['download_required'] == 1) {
                                        if ($assigned_document['downloaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['signature_required'] == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($is_magic_tag_exist == 1) {
                                        if ($assigned_document['user_consent'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    }

                                    if ($is_document_completed > 0) {

                                        if ($assigned_document['is_confidential'] == 0) {
                                            if ($assigned_document['pay_roll_catgory'] == 0) {

                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $signed_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $completed_payroll_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            }
                                        } else {
                                            unset($assigned_documents[$key]);
                                        }
                                    } else {
                                        if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $uncompleted_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }

                                        $assigned_sids[] = $assigned_document['document_sid'];
                                    }
                                } else {
                                    if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {
                                        //
                                        if (!empty($assigned_document['authorized_signature'])) {
                                            if ($assigned_document['pay_roll_catgory'] == 0) {
                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $signed_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                                $signed_document_sids[] = $assigned_document['document_sid'];
                                                $completed_payroll_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            }
                                        } else {
                                            if ($assigned_document['pay_roll_catgory'] == 1) {
                                                $uncompleted_payroll_documents[] = $assigned_document;
                                                unset($assigned_documents[$key]);
                                            }
                                        }
                                        //
                                        $assigned_sids[] = $assigned_document['document_sid'];
                                    } else if ($assigned_document['pay_roll_catgory'] == 0) {
                                        $assigned_sids[] = $assigned_document['document_sid'];
                                        $no_action_required_sids[] = $assigned_document['document_sid'];
                                        $no_action_required_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                        if ($assigned_document['user_consent'] == 1 && $assigned_document['document_sid'] == 0) {
                                            $no_action_required_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    }
                                }
                            } else {
                                $revoked_sids[] = $assigned_document['document_sid'];
                            }
                        }
                    } else if ($assigned_document['archive'] == 1) {
                        unset($assigned_documents[$key]);
                    }
                }
            }
            //
            $data['history_doc_sids'] = $history_doc_sids;
            //
            foreach ($signed_documents as $cd_key => $signed_document) {
                $signed_documents[$cd_key]["is_history"] = 0;
                $signed_documents[$cd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history('employee', $employer_sid, $signed_document['sid']);

                if (($key = array_search($signed_document['sid'], $history_doc_sids)) !== false) {
                    unset($history_doc_sids[$key]);
                }
            }

            foreach ($completed_payroll_documents as $prd_key => $payroll_document) {
                $completed_payroll_documents[$prd_key]["history"] = $this->hr_documents_management_model->check_if_document_has_history('employee', $employer_sid, $payroll_document['sid']);

                if (($key = array_search($payroll_document['sid'], $history_doc_sids)) !== false) {
                    unset($history_doc_sids[$key]);
                }
            }

            if (!empty($history_doc_sids)) {
                foreach ($history_doc_sids as $key => $doc_id) {
                    $his_docs = $this->hr_documents_management_model->check_if_document_has_history('employee', $employer_sid, $doc_id);
                    foreach ($his_docs as $key => $his_doc) {
                        $his_doc["is_history"] = 1;
                        $his_doc["history"] = array();
                        array_push($signed_documents, $his_doc);
                    }
                }
            }
            //
            $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, $signed_documents, $no_action_required_documents, 0);
            $data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
            $data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];
            $documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employer_sid);
            $data['documents'] = $documents;

            $current_assigned_offer_letter = $this->hr_documents_management_model->get_current_assigned_offer_letter($company_sid, 'employee', $employer_sid);

            if (!empty($current_assigned_offer_letter)) {
                if ($current_assigned_offer_letter[0]['user_consent'] == 1) {
                    $completed_offer_letter = $current_assigned_offer_letter[0];
                } else {
                    $uncompleted_offer_letter = $current_assigned_offer_letter[0];
                }
            }

            $eev_w4 = $this->hr_documents_management_model->is_exist_in_eev_document('w4', $company_sid, $employer_sid);
            if (!empty($eev_w4)) {
                $data['w4_form'] = $data['eev_w4'] = $eev_w4;
            } else {
                $w4_form = $this->hr_documents_management_model->fetch_form_for_front_end('w4', 'employee', $employer_sid);
                $data['w4_form'] = $w4_form;
            }
            //
            $completed_w4 = array();
            //
            if (!empty($data['w4_form']) && $data['w4_form']['user_consent'] == 1) {
                $data['w4_form']["form_status"] = "Current";
                array_push($completed_w4, $data['w4_form']);
            }
            //
            $w4_history = $this->hr_documents_management_model->is_W4_history_exist($data['w4_form']['sid'], 'employee', $employer_sid);
            //
            if (!empty($w4_history)) {
                foreach ($w4_history as $history) {
                    $history["form_status"] = "Previous";
                    array_push($completed_w4, $history);
                }
            }
            //
            $eev_w9 = $this->hr_documents_management_model->is_exist_in_eev_document('w9', $company_sid, $employer_sid);
            if (!empty($eev_w9)) {
                $data['w9_form'] = $data['eev_w9'] = $eev_w9;
            } else {
                $w9_form = $this->hr_documents_management_model->fetch_form_for_front_end('w9', 'employee', $employer_sid);
                $data['w9_form'] = $w9_form;
            }
            //
            $completed_w9 = array();
            //
            if (!empty($data['w9_form']) && $data['w9_form']['user_consent'] == 1) {
                $data['w9_form']["form_status"] = "Current";
                array_push($completed_w9, $data['w9_form']);
            }
            //
            $w9_history = $this->hr_documents_management_model->is_W9_history_exist($data['w9_form']['sid'], 'employee', $employer_sid);
            //
            if (!empty($w9_history)) {
                foreach ($w9_history as $history) {
                    $history["form_status"] = "Previous";
                    array_push($completed_w9, $history);
                }
            }
            //
            $eev_i9 = $this->hr_documents_management_model->is_exist_in_eev_document('i9', $company_sid, $employer_sid);
            if (!empty($eev_i9)) {
                $data['i9_form'] = $data['eev_i9'] = $eev_i9;
            } else {
                $i9_form = $this->hr_documents_management_model->fetch_form_for_front_end('i9', 'employee', $employer_sid);
                $data['i9_form'] = $i9_form;
            }
            //
            $completed_i9 = array();
            //
            if (!empty($data['i9_form']) && $data['i9_form']['user_consent'] == 1) {
                $data['i9_form']["form_status"] = "Current";
                array_push($completed_i9, $data['i9_form']);
            }
            //
            $i9_history = $this->hr_documents_management_model->is_I9_history_exist($data['i9_form']['sid'], 'employee', $employer_sid);
            //
            if (!empty($i9_history)) {
                foreach ($i9_history as $history) {
                    $history["form_status"] = "Previous";
                    array_push($completed_i9, $history);
                }
            }
            //
            if ($this->hr_documents_management_model->hasEEOCPermission($company_sid, 'eeo_on_employee_document_center')) {
                $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($employer_sid, 'employee');
                //
                if (!empty($eeo_form_info) && $eeo_form_info['status'] == 1) {
                    $eeoc_form = $this->hr_documents_management_model->get_eeo_form_info($employer_sid, 'employee');
                    if (!empty($eeoc_form)) {
                        $data['eeoc_form'] = $eeoc_form;
                    }
                }
            }

            $data['completed_i9']                           = $completed_i9;
            $data['completed_w9']                           = $completed_w9;
            $data['completed_w4']                           = $completed_w4;
            $data['assigned_documents']                     = $assigned_documents;
            $data['completed_offer_letter']                 = $completed_offer_letter;
            $data['uncompleted_offer_letter']               = $uncompleted_offer_letter;
            $data['uncompleted_payroll_documents']          = $uncompleted_payroll_documents;
            $data['completed_payroll_documents']            = $completed_payroll_documents;
            $data['payroll_documents_sids']                 = $payroll_documents_sids;
            $data['completed_sids']                         = $completed_sids; // completed Documemts Ids
            $data['completed_documents']                    = $completed_documents;
            $data['no_action_required_documents']           = $no_action_required_documents;
            $data['assigned_sids']                          = $assigned_sids;
            $data['revoked_sids']                           = $revoked_sids;
            $data['user_type']                              = 'employee';
            $data['user_sid']                               = $employer_sid;
            $data['assigned_offer_letter']                  = $current_assigned_offer_letter;
            $data['no_action_required_payroll_documents']   = $no_action_required_payroll_documents;

            // General Documents
            $data['NotCompletedGeneralDocuments'] = $this->hr_documents_management_model->getGeneralDocuments(
                $data['session']['employer_detail']['sid'],
                'employee',
                $data['session']['company_detail']['sid']
            );
            $data['CompletedGeneralDocuments'] = $this->hr_documents_management_model->getGeneralDocuments(
                $data['session']['employer_detail']['sid'],
                'employee',
                $data['session']['company_detail']['sid'],
                'completed'
            );
            //
            if (checkIfAppIsEnabled('performanceevaluation')) {
                $this
                    ->load
                    ->model(
                        "v1/Employee_performance_evaluation_model",
                        "employee_performance_evaluation_model"
                    );

                $data['assignPerformanceDocument'] = $this->employee_performance_evaluation_model->checkEmployeeAssignPerformanceDocument(
                    $data['session']['employer_detail']['sid']
                );

                if ($data['assignPerformanceDocument']) {
                    $data['pendingPerformanceSection'] = $this->employee_performance_evaluation_model->checkEmployeeUncompletedDocument(
                        $data['session']['employer_detail']['sid']
                    );
                    //
                    $data['pendingPerformanceSectionName'] = $this->employee_performance_evaluation_model->getEmployeePendingSectionName(
                        $data['session']['employer_detail']['sid']
                    );
                }
            }
            //
            $data['load_view'] = check_blue_panel_status(false, 'self');
            $data['employee'] = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                // check and get state forms
                $companyStateForms = $this->hr_documents_management_model
                    ->getCompanyStateForms(
                        $company_sid,
                        $employer_sid,
                        "employee"
                    );
                //
                $data["companyStateForms"] = $companyStateForms["all"];
                $data["userNotCompletedStateForms"] = $companyStateForms["not_completed"];
                $data["userCompletedStateForms"] = $companyStateForms["completed"];
                //
                $this->load->view('main/header', $data);
                $this->load->view('onboarding/documents_new');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'sign_document':
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function sign_hr_document($doc = NULL, $document_sid)
    {
        if ($this->session->userdata('logged_in')) {

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['employer_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assigned Documents';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['doc'] = $doc;

            $document = $this->hr_documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, $doc);
            $doc_status = check_document_completed($document);
            //
            if ($doc_status == "Completed" && $document['is_confidential'] == 1) {
                redirect('/library_document');
            }
            //
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');

            $is_authorized_document = 'no';

            if ($this->form_validation->run() == false) {

                if ($document['document_type'] == 'offer_letter') {
                    $data['attached_video'] = array();
                } else {
                    if ($document['document_type'] == 'uploaded') {
                        if (strpos($document['document_s3_name'], '&') !== false) {
                            $document['document_s3_name'] = modify_AWS_file_name($document['sid'], $document['document_s3_name'], "document_s3_name");
                        }

                        if (strpos($document['uploaded_file'], '&') !== false) {
                            $document['uploaded_file'] = modify_AWS_file_name($document['sid'], $document['uploaded_file'], "uploaded_file");
                        }
                    }
                    //
                    $attached_video = $this->hr_documents_management_model->get_document_attached_video($document['document_sid']);
                    $data['attached_video'] = $attached_video;
                }

                if (!empty($document['document_description'])) {
                    $document_body = $document['document_description'];

                   $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                    $magic_signature_codes = array('{{signature}}', '{{inital}}');
                    $magic_authorized_codes = array('{{authorized_signature}}', '{{authorized_signature_date}}');

                    if (str_replace($magic_signature_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'consent_only';
                    } else if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'save_only';
                    }
                }

                $data['save_offer_letter_type'] = $save_offer_letter_type;

                if (!empty($document)) {
                    //
                    //
                    if (!empty($document['form_input_data'])) {
                        $form_input_data = unserialize($document['form_input_data']);
                        $data['form_input_data'] = json_encode(json_decode($form_input_data, true));
                    }
                    //
                    if ($document['user_consent'] == 1 && !empty($document['form_input_data'])) {

                        if (!empty($document['authorized_signature'])) {
                            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        }

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }

                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 1) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
                        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
                        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
                        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }
                    } else if (!empty($document['authorized_signature']) && $document['user_consent'] == 0) {
                        $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
                        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);

                        if (!empty($document['authorized_signature_date'])) {
                            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
                            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
                        }
                    }

                    $document_content = replace_tags_for_document($company_sid, $employer_sid, 'employee', $document['document_description'], $document['document_sid']);
                    $document['document_description'] = html_entity_decode($document_content);
                    $requested_content = preg_replace('#(<br */?>\s*)+#i', '<br />', $requested_content);
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('hr_documents_management/my_documents', 'refresh');
                }

                $data['document'] = $document;
                $document_type = $document['document_type'];
                $data['document_type'] = isset($document['letter_type']) ?  $document['letter_type'] : $document['document_type'];
                $signed_flag = false;

                if ($document['user_consent'] == 1) {
                    $signed_flag = true;
                }

                $data['signed_flag'] = $signed_flag;
                $data['save_post_url'] = current_url();
                $data['first_name'] = $data['session']['employer_detail']['first_name'];
                $data['last_name'] = $data['session']['employer_detail']['last_name'];
                $data['email'] = $data['session']['employer_detail']['email'];
                $data['employee'] = $data['session']['employer_detail'];
                $data['company_sid'] = $data['session']['company_detail']['sid'];
                $data['users_type'] = 'employee';
                $data['users_sid'] = $employer_sid;
                // Hanlded back url
                $data['back_url'] = base_url($this->input->get('document_backurl') ? 'library_document' : 'hr_documents_management/my_documents');

                $data['download_url'] = base_url('hr_documents_management/download_hr_document/' . $document['sid']);
                $data['unique_sid'] = ''; //No Need for Unique Sid for Employee

                if ($document['acknowledged'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Document Status:</strong> You have successfully Acknowledged this document';
                    $acknowledgement_button_txt = 'Acknowledged';
                    $acknowledgement_button_css = 'btn-warning';
                    $acknowledgement_button_action = 'javascript:;';
                } else {
                    $acknowledgement_status = '<strong class="text-danger">Document Status:</strong> You have not yet acknowledged this document';
                    $acknowledgement_button_txt = 'I Acknowledge';
                    $acknowledgement_button_css = 'blue-button';
                    $acknowledgement_button_action = 'func_acknowledge_document();';
                }

                $acknowledgment_action_title = 'Document Action: <b>Acknowledgement Required!</b>';
                $acknowledgment_action_desc = '<b>Acknowledge the receipt of this document</b>';

                if ($document_type == 'uploaded') {
                    $download_action_title = 'Document Action: <b>Save / Download</b>';
                    $download_action_desc = '<b>Please download this document to Sign / Fill. </b>';
                    $download_button_action = base_url('hr_documents_management/download_hr_document/' . $document['sid']);

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successfully downloaded this document';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> You have not yet download this document';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                    }

                    $document_filename = $document['document_s3_name'];
                    $name = explode(".", $document_filename);
                    $url_segment_submitted = $name[0];
                    $extension = $name[1];

                    if (strtolower($extension) == 'pdf') {
                        $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.pdf';
                    } else if (strtolower($extension) == 'doc') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edoc&wdAccPdf=0';
                    } else if (strtolower($extension) == 'docx') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edocx&wdAccPdf=0';
                    } else if (strtolower($extension) == 'xls') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Exls';
                    } else if (strtolower($extension) == 'xlsx') {
                        $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Exlsx';
                    } else if (strtolower($extension) == 'csv') {
                        $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.csv';
                    } else {
                        $print_button_action = base_url('onboarding/print_upload_img/' . $document['document_s3_name']);
                    }
                } else { // generated document
                    $download_action_title = 'Document Action: <b>Download / Print</b>';
                    $download_action_desc = '<b>You can Download / Print this document for your reference</b>';
                    $download_button_action = '';

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successfully printed this document';
                        $download_button_txt = 'Re-Download';
                        $download_button_css = 'btn-warning';
                    } else {
                        $download_status = '<strong class="text-danger">Document Status:</strong> You have not yet printed this document';
                        $download_button_txt = 'Save / Download';
                        $download_button_css = 'blue-button';
                    }

                    if (!empty($document['uploaded_file'])) {
                        $document_filename = $document['uploaded_file'];
                        $name = explode(".", $document_filename);
                        $url_segment_submitted = $name[0];
                        $extension = $name[1];

                        if (strtolower($extension) == 'pdf') {
                            $print_button_action = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $url_segment_submitted . '.pdf';
                        } else if (strtolower($extension) == 'doc') {
                            $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edoc&wdAccPdf=0';
                        } else if (strtolower($extension) == 'docx') {
                            $print_button_action = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $url_segment_submitted . '%2Edocx&wdAccPdf=0';
                        } else {
                            $print_button_action = base_url('hr_documents_management/print_upload_img/' . $document['uploaded_file']);
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1) {
                        if ($document['acknowledged'] == 1 && $document['downloaded'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if ($document['acknowledgment_required'] == 1 && $document['download_required'] == 0) {
                        if ($document['acknowledged'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if ($document['acknowledgment_required'] == 0 && $document['download_required'] == 1) {
                        if ($document['downloaded'] == 1) {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                        } else {
                            $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                            $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                        }
                    } else if (empty($document['submitted_description'])) {
                        $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/print';
                        $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/assigned/assigned_document/download';
                    } else {
                        $print_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/print';
                        $download_button_action = base_url('hr_documents_management/perform_action_on_document_content') . '/' . $document['sid'] . '/submitted/assigned_document/download';
                    }
                }

                if ($document['uploaded'] == 1) {
                    $uploaded_status = '<strong class="text-success">Document Status:</strong> You have successfully uploaded a signed copy of this document. In case you have uploaded the wrong document, you can replace it with the correct version by re uploading it.';
                    $uploaded_button_txt = 'Re-Upload Document';
                    $uploaded_button_css = 'btn-warning';
                } else {
                    $uploaded_status = '<strong class="text-danger">Document Status:</strong> Upload the Signed Document, You have not yet uploaded this document';
                    $uploaded_button_txt = 'Upload Document';
                    $uploaded_button_css = 'blue-button';
                }

                $uploaded_action_title = 'Document Action: <b>Upload Signed Copy!</b>';
                $uploaded_action_desc = '<b>Please sign this document and upload the signed copy.</b>';




                $data['download_action_title'] = $download_action_title;
                $data['download_action_desc'] = $download_action_desc;
                $data['download_button_txt'] = $download_button_txt;
                $data['download_button_action'] = $download_button_action;
                $data['download_status'] = $download_status;
                $data['download_button_css'] = $download_button_css;
                $data['acknowledgment_action_title'] = $acknowledgment_action_title;
                $data['acknowledgment_action_desc'] = $acknowledgment_action_desc;
                $data['acknowledgement_button_txt'] = $acknowledgement_button_txt;
                $data['acknowledgement_status'] = $acknowledgement_status;
                $data['acknowledgement_button_css'] = $acknowledgement_button_css;
                $data['acknowledgement_button_action'] = $acknowledgement_button_action;
                $data['uploaded_action_title'] = $uploaded_action_title;
                $data['uploaded_action_desc'] = $uploaded_action_desc;
                $data['uploaded_button_txt'] = $uploaded_button_txt;
                $data['uploaded_status'] = $uploaded_status;
                $data['uploaded_button_css'] = $uploaded_button_css;
                $data['print_button_action'] = $print_button_action;
                $this->hr_documents_management_model->update_document_pending_status($document_sid);

                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('hr_documents_management/sign_hr_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else {

                $document = $this->hr_documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, $doc);

                $is_authorized_document = 'no';

                if (!empty($document['document_description'])) {
                    $magic_authorized_codes = array('{{authorized_signature}}', '{{authorized_signature_date}}');
                    $document_body = $document['document_description'];

                    if (str_replace($magic_authorized_codes, '', $document_body) != $document_body) {
                        $is_authorized_document = 'yes';
                    }
                }

                $perform_action = $this->input->post('perform_action');
                //
                $isCompleted = true;
                //
                // if($perform_action == 'sign_document') $isCompleted = true;
                // else if($perform_action == 'upload_document') $isCompleted = true;
                // else if($perform_action == 'acknowledge_document') $isCompleted = true;
                //
                switch ($perform_action) {
                    case 'acknowledge_document':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        // $document_sid = $this->input->post('document_sid');

                        if ($doc == 'o') {
                            $document_info = $this->hr_documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, $doc);

                            if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1 && $document_info['download_required'] == 1)) {
                                if ($document_info['downloaded'] == 1) {
                                    $data_to_update = array();
                                    $data_to_update['acknowledged'] = 1;
                                    $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                    if ($document_info['signature_required'] == 0 && $document_info['user_consent'] == 0) {
                                        $data_to_update['user_consent'] = 1;
                                        $data_to_update['form_input_data'] = 's:2:"{}";';
                                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                                    }

                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                } else {
                                    $data_to_update = array();
                                    $data_to_update['acknowledged'] = 1;
                                    $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                                }
                            } else if (!empty($document_info) && ($document_info['acknowledgment_required'] == 1)) {
                                $data_to_update = array();
                                $data_to_update['acknowledged'] = 1;
                                $data_to_update['acknowledged_date'] = date('Y-m-d H:i:s');

                                if ($document_info['signature_required'] == 0 && $document_info['user_consent'] == 0) {
                                    $data_to_update['user_consent'] = 1;
                                    $data_to_update['signature_timestamp'] = date('Y-m-d');
                                    $data_to_update['form_input_data'] = 's:2:"{}";';
                                }

                                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                            }
                        } else {
                            $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_sid);
                        }

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $user_sid;
                        $cpArray['user_type'] = $user_type;
                        $cpArray['document_sid'] = $document_sid;
                        $cpArray['document_type'] = 'assigned';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        if ($isCompleted) {
                            $this->check_complete_document_send_email($company_sid, $employer_sid);

                            if ($is_authorized_document == 'yes') {
                                $assign_managers = $this->hr_documents_management_model->getAllAuthorizedAssignManagers($company_sid, $document_sid);

                                $employee_name = getUserNameBySID($employer_sid);

                                $email_template_id = $this->hr_documents_management_model->getAuthorizedManagerTemplate('Authorized Manager Notification');

                                $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('view_assigned_authorized_document/' . $document_sid) . '">Assign Authorized Document</a>';

                                if (!empty($assign_managers)) {
                                    foreach ($assign_managers as $manager) {
                                        $replacement_array['first_name'] = $manager['first_name'];
                                        $replacement_array['last_name'] = $manager['last_name'];
                                        $replacement_array['employee_name'] = $employee_name;
                                        $replacement_array['link'] = $link_html;
                                        $to_email = $manager['email'];

                                        $message_header_footer = message_header_footer($company_sid, ucwords($company_name));
                                        //
                                        $user_extra_info = array();
                                        $user_extra_info['user_sid'] = $user_sid;
                                        $user_extra_info['user_type'] = $user_type;
                                        //
                                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                                        if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                            //
                                            log_and_send_templated_email($email_template_id, $to_email, $replacement_array, $message_header_footer, 1, $user_extra_info);
                                        }
                                    }
                                }
                            }
                        }

                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                        redirect('hr_documents_management/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
                        break;
                    case 'upload_document':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        // $document_sid = $this->input->post('document_sid');
                        $document_type = $this->input->post('document_type');
                        $company_sid = $this->input->post('company_sid');
                        $aws_file_name = upload_file_to_aws('upload_file', $company_sid, $doc . '_' . $document_sid, time());
                        // $aws_file_name = '0003-d_6-1542874444-39O.jpg'; 
                        $uploaded_file = '';

                        if ($aws_file_name != 'error') {
                            $uploaded_file = $aws_file_name;
                        }

                        if (!empty($uploaded_file)) {
                            if ($doc == 'o') {
                                $data_to_update = array();
                                $data_to_update['uploaded'] = 1;
                                $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                $data_to_update['signature_timestamp'] = date('Y-m-d');
                                $data_to_update['user_consent'] = 1;
                                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                            } else {
                                $this->hr_documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                            }

                            $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                        }

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $user_sid;
                        $cpArray['user_type'] = $user_type;
                        $cpArray['document_sid'] = $document_sid;
                        $cpArray['document_type'] = 'assigned';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        if ($isCompleted) {
                            $this->check_complete_document_send_email($company_sid, $employer_sid);

                            if ($is_authorized_document == 'yes') {
                                $assign_managers = $this->hr_documents_management_model->getAllAuthorizedAssignManagers($company_sid, $document_sid);

                                $employee_name = getUserNameBySID($employer_sid);

                                $email_template_id = $this->hr_documents_management_model->getAuthorizedManagerTemplate('Authorized Manager Notification');

                                $link_html = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" target="_blank" href="' . base_url('view_assigned_authorized_document/' . $document_sid) . '">Assign Authorized Document</a>';

                                if (!empty($assign_managers)) {
                                    foreach ($assign_managers as $manager) {
                                        $replacement_array['first_name'] = $manager['first_name'];
                                        $replacement_array['last_name'] = $manager['last_name'];
                                        $replacement_array['employee_name'] = $employee_name;
                                        $replacement_array['link'] = $link_html;
                                        $to_email = $manager['email'];

                                        $message_header_footer = message_header_footer($company_sid, ucwords($company_name));
                                        //
                                        $user_extra_info = array();
                                        $user_extra_info['user_sid'] = $user_sid;
                                        $user_extra_info['user_type'] = $user_type;
                                        //
                                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                                        if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                                            //
                                            log_and_send_templated_email($email_template_id, $to_email, $replacement_array, $message_header_footer, 1, $user_extra_info);
                                        }
                                    }
                                }
                            }
                        }

                        redirect('hr_documents_management/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
                        break;
                    case 'sign_document':
                        $save_input_values = array();
                        $user_type = 'employee';
                        $user_sid = $employer_sid;
                        $save_signature = $this->input->post('save_signature');
                        $save_initial = $this->input->post('save_signature_initial');
                        $save_date = $this->input->post('save_signature_date');
                        $user_consent = $this->input->post('user_consent');
                        $base64_pdf = $this->input->post('save_PDF');

                        if (isset($_POST['save_input_values']) && !empty($_POST['save_input_values'])) {
                            $save_input_values = $_POST['save_input_values'];
                        }
                        $save_input_values = serialize($save_input_values);

                        $data_to_update = array();

                        if ($save_signature == 'yes' || $save_initial == 'yes' || $save_date == 'yes') {
                            $company_sid = $data['session']['company_detail']['sid'];
                            $signature = get_e_signature($company_sid, $user_sid, $user_type);

                            if ($save_signature == 'yes') {
                                $data_to_update['signature_base64'] = $signature['signature_bas64_image'];
                            }

                            if ($save_initial == 'yes') {
                                $data_to_update['signature_initial'] = $signature['init_signature_bas64_image'];
                            }

                            if ($save_date == 'yes') {
                                $data_to_update['signature_timestamp'] = date('Y-m-d');
                            }
                        }

                        $data_to_update['signature_email'] = $data['session']['employer_detail']['email'];
                        $data_to_update['signature_ip'] = getUserIP();
                        $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                        $data_to_update['submitted_description'] = $base64_pdf;
                        $data_to_update['uploaded'] = 1;
                        $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                        $data_to_update['form_input_data'] = $save_input_values;
                        $this->hr_documents_management_model->update_generated_documents($document_sid, $user_sid, $user_type, $data_to_update);
                        $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');

                        //
                        $cpArray = [];
                        $cpArray['company_sid'] = $company_sid;
                        $cpArray['user_sid'] = $user_sid;
                        $cpArray['user_type'] = $user_type;
                        $cpArray['document_sid'] = $document_sid;
                        $cpArray['document_type'] = 'assigned';
                        //
                        checkAndInsertCompletedDocument($cpArray);

                        if ($isCompleted) {
                            $this->check_complete_document_send_email($company_sid, $employer_sid);
                            if ($is_authorized_document == 'yes') {

                                $assign_managers = $this->hr_documents_management_model->getAllAuthorizedAssignManagers($company_sid, $document_sid);

                                if (!empty($assign_managers)) {
                                    //
                                    $managerIds = array_column($assign_managers, 'sid');
                                    //
                                    foreach ($managerIds as $managerId) {
                                        $this->hr_documents_management_model->sendEmailToAuthorizedManagers($managerId, $document_sid);
                                    }
                                }
                            }
                        }

                        if ($user_type == 'employee') {
                            redirect('hr_documents_management/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
                        } else {
                            redirect('onboarding/sign_hr_document/' . $document_sid);
                        }

                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function download_hr_document($document_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Documents Assignment';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid);
                $data['document'] = $document;
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document['document_original_name'];
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['document_s3_name'], $temp_file_path);

                if (file_exists($temp_file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $file_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($temp_file_path));
                    $handle = fopen($temp_file_path, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                    unlink($temp_file_path);
                }

                $this->hr_documents_management_model->update_download_status('employee', $employer_sid, $document_sid);
            } else {
                //nothing
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function copy_old_hr_documents_to_new_documents()
    {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $documents = $this->hr_documents_management_model->get_all_hr_documents();

            foreach ($documents as $key => $v) {
                $data = array();
                $data['sid'] = $v['sid'];
                $data['company_sid'] = $v['company_sid'];
                $data['employer_sid'] = $v['employer_sid'];
                $data['document_name'] = $v['document_original_name'];
                $data['document_original_name'] = $v['document_original_name'] . '.' . $v['document_type'];
                $data['document_type'] = $v['document_type'];
                $data['document_description'] = $v['document_description'];
                $data['onboarding'] = $v['onboarding'];
                $data['action_required'] = $v['action_required'];
                $data['to_all_employees'] = $v['to_all_employees'];
                $data['unique_key'] = generateRandomString(32);

                if ($v['archive'] == 1) {
                    $data['archive'] = 0;
                } else {
                    $data['archive'] = 1;
                }

                $data['s3_file_name'] = $v['document_name'];
                $this->db->insert('documents_uploads', $data);
                echo $this->db->insert_id() . ': ' . $this->db->last_query() . '<br>';
            }
        }
    }

    public function people_with_pending_documents_old(
        $employees = 'all',
        $documents = 'all',
        $type = FALSE
    ) {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'pending_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Employees With Pending Documents';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $data["session"]["company_detail"]["CompanyName"];
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();

            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);
            // Get documents list
            $data['documentsList'] = $this->hr_documents_management_model->getAllActiveDocuments($company_sid);


            //
            $data['selectedEmployeeList'] = explode(':', $employees);
            $data['selectedDocumentList'] = explode(':', $documents);

            // Only get documents for active and non executive employees
            if ($employees == 'all') {
                $employees = implode(':', array_column($data['employeesList'], 'sid'));
            }

            //
            $data['selectedEmployeeList'] = array_flip($data['selectedEmployeeList']);
            //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {

                $result = $this->hr_documents_management_model->getEmployeesWithPendingDoc_old(
                    $company_sid,
                    $employees,
                    $documents
                );
                //
                $emp_ids = array_keys($result);

                if ($employees != 'all') {
                    $emp_ids = explode(':', $employees);
                }

                if (!empty($emp_ids)) {
                    $data['employees'] = $this->hr_documents_management_model->getEmployeesDetails($emp_ids);
                } else {
                    $data['employees'] = array();
                }

                if (sizeof($data['employees'])) {
                    foreach ($data['employees'] as $k => $v) {
                        $data['employees'][$k]['Documents'] = $result[$v['sid']]['Documents'];
                    }
                }
                //
                if ($type == 'export') {
                    ob_start();
                    $h = array('Empoloyee Name', 'Email', 'Document Count', 'Document(s)', 'Status');
                    //
                    $filename = date('m_d_Y_H_i_s', strtotime('now')) . "_employee_with_pending_documents.csv";
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, $h);
                    //
                    foreach ($data['employees'] as $k => $v) {
                        $iText = '';
                        if (sizeof($v['Documents'])) {
                            foreach ($v['Documents'] as $k1 => $v1) {
                                $iText .= ($v1['Title']) . ' (' . ($v1['Type']) . ')' . "\n";
                            }
                        }
                        $d = array(remakeEmployeeName($v), $v['email'], sizeof($v['Documents']), $iText, 'Pending');
                        fputcsv($fp, $d);
                    }
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    ob_flush();
                    exit;
                } else if ($type == 'print') {
                    $this->load->view('hr_documents_management/print_new_people_with_pending_documents', $data);
                    return;
                } else if ($type == 'return') {
                    header('Content-Type: application/json');
                    echo json_encode($data['employees']);
                    exit(0);
                }

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/new_people_with_pending_documents');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function people_with_pending_documents(
        $employees = 'all',
        $documents = 'all',
        $type = FALSE
    ) {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'pending_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Employees With Pending Documents';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $data["session"]["company_detail"]["CompanyName"];
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();

            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);
            // Get documents list
            $data['documentsList'] = $this->hr_documents_management_model->getAllActiveDocuments($company_sid);


            //
            $data['selectedEmployeeList'] = explode(':', $employees);
            $data['selectedDocumentList'] = explode(':', $documents);

            // Only get documents for active and non executive employees
            if ($employees == 'all') {
                $employees = implode(':', array_column($data['employeesList'], 'sid'));
            }

            //
            $data['selectedEmployeeList'] = array_flip($data['selectedEmployeeList']);
            //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                //
                $data['employees'] = $this->hr_documents_management_model->getEmployeesWithPendingDoc(
                    $company_sid,
                    $employees,
                    $documents
                );
                //
                // _e($data['employees'],true,true);
                //
                if ($type == 'export') {
                    ob_start();
                    $h = array('Empoloyee Name', 'Email', 'Document Count', 'Document(s)', 'Status');
                    //
                    $filename = date('m_d_Y_H_i_s', strtotime('now')) . "_employee_with_pending_documents.csv";
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, $h);
                    //
                    foreach ($data['employees'] as $k => $v) {
                        $iText = '';
                        if (sizeof($v['Documents'])) {
                            foreach ($v['Documents'] as $k1 => $v1) {
                                $iText .= ($v1['Title']) . ' (' . ($v1['Type']) . ')' . "\n";
                            }
                        }
                        $d = array(remakeEmployeeName($v), $v['email'], sizeof($v['Documents']), $iText, 'Pending');
                        fputcsv($fp, $d);
                    }
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    ob_flush();
                    exit;
                } else if ($type == 'print') {
                    $this->load->view('hr_documents_management/print_new_people_with_pending_documents', $data);
                    return;
                } else if ($type == 'return') {
                    header('Content-Type: application/json');
                    echo json_encode($data['employees']);
                    exit(0);
                }

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/new_people_with_pending_documents');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function people_with_pending_federal_fillable_old(
        $employees = 'all',
        $documents = 'all',
        $type = FALSE
    ) {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'pending_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Employees With Pending Federal Fillable Documents';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $data["session"]["company_detail"]["CompanyName"];
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();

            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);
            //
            $data['selectedEmployeeList'] = explode(':', $employees);
            $data['selectedDocumentList'] = explode(':', $documents);

            // Only get documents for active and non executive employees
            if ($employees == 'all') {
                $employees = implode(':', array_column($data['employeesList'], 'sid'));
            }

            //
            $data['selectedEmployeeList'] = array_flip($data['selectedEmployeeList']);
            //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {

                $result = $this->hr_documents_management_model->getEmployeesWithPendingFederalFillable(
                    $company_sid,
                    $employees,
                    $documents
                );
                //
                $emp_ids = array_keys($result);
                // foreach ($result as $id) {
                //     $emp_ids[] = $id['user_sid'];
                // }

                if (!empty($emp_ids)) {
                    $data['employees'] = $this->hr_documents_management_model->getEmployeesDetails($emp_ids);
                } else {
                    $data['employees'] = array();
                }

                if (sizeof($data['employees'])) {
                    foreach ($data['employees'] as $k => $v) {
                        $data['employees'][$k]['Documents'] = $result[$v['sid']]['Documents'];
                    }
                }

                //
                if ($type == 'export') {
                    ob_start();

                    $h = array('Employee Name');

                    if (in_array('w4', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                        array_push($h, "W4 Status");
                    }


                    if (in_array('i9', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                        array_push($h, "I9 Status");
                    }

                    //
                    $filename = date('m_d_Y_H_i_s', strtotime('now')) . "_employee_with_pending_documents.csv";
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, $h);
                    //
                    foreach ($data['employees'] as $employee) {
                        $iText = '';

                        if (sizeof($employee['Documents'])) {

                            usort($employee['Documents'], 'dateSorter');

                            $w4_status = 'Not Assigned';
                            $i9_status = 'Not Assigned';


                            foreach ($employee['Documents'] as $ke => $v) {

                                //
                                if ($v['Title'] == "W4 Fillable") {
                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                        if ($v['Status'] == "pending") {
                                            $w4_status = 'Pending';
                                        } else {
                                            $w4_status = 'Completed';
                                        }
                                    }
                                }

                                //
                                if ($v['Title'] == "I9 Fillable") {
                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                        if ($v['Status'] == "pending") {
                                            $i9_status = 'Pending';;
                                        } else {
                                            $i9_status = 'Completed';
                                        }
                                    }
                                }
                            }
                        }

                        $d = array(remakeEmployeeName($employee));

                        if (in_array('w4', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                            array_push($d, $w4_status);
                        }

                        if (in_array('i9', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                            array_push($d, $i9_status);
                        }

                        fputcsv($fp, $d);
                    }
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    ob_flush();
                    exit;
                } else if ($type == 'print') {
                    $this->load->view('hr_documents_management/print_people_with_pending_federal_fillable', $data);
                    return;
                } else if ($type == 'return') {
                    header('Content-Type: application/json');
                    echo json_encode($data['employees']);
                    exit(0);
                }

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/people_with_pending_federal_fillable');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function people_with_pending_federal_fillable(
        $employees = 'all',
        $documents = 'all',
        $type = FALSE
    ) {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'pending_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Employees With Pending Federal Fillable Documents';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $data["session"]["company_detail"]["CompanyName"];
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();

            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);
            //
            $data['selectedEmployeeList'] = explode(':', $employees);
            $data['selectedDocumentList'] = explode(':', $documents);

            // Only get documents for active and non executive employees
            if ($employees == 'all') {
                $employees = implode(':', array_column($data['employeesList'], 'sid'));
            }

            //
            $data['selectedEmployeeList'] = array_flip($data['selectedEmployeeList']);
            //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                //
                $data['employees'] = [];
                //
                if ($employees) {
                    $data['employees'] = $this->hr_documents_management_model->getEmployeesWithPendingFederalFillable(
                        $company_sid,
                        $employees,
                        $documents
                    );
                }
                //
                if ($type == 'export') {
                    ob_start();

                    $h = array('Employee Name');

                    if (in_array('w4', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                        array_push($h, "W4 Status");
                    }


                    if (in_array('i9', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                        array_push($h, "I9 Status");
                    }

                    //
                    $filename = date('m_d_Y_H_i_s', strtotime('now')) . "_employee_with_pending_documents.csv";
                    $fp = fopen('php://output', 'w');
                    fputcsv($fp, $h);
                    //
                    foreach ($data['employees'] as $employee) {
                        $iText = '';

                        if (sizeof($employee['Documents'])) {

                            usort($employee['Documents'], 'dateSorter');

                            $w4_status = 'Not Assigned';
                            $i9_status = 'Not Assigned';


                            foreach ($employee['Documents'] as $ke => $v) {

                                //
                                if ($v['Title'] == "W4 Fillable") {
                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                        if ($v['Status'] == "pending") {
                                            $w4_status = 'Pending';
                                        } else {
                                            $w4_status = 'Completed';
                                        }
                                    }
                                }


                                //
                                if ($v['Title'] == "I9 Fillable") {
                                    if ($v['Status'] == "pending" || $v['Status'] == "completed") {
                                        if ($v['Status'] == "pending") {
                                            $i9_status = 'Pending';
                                        } else {
                                            $i9_status = 'Completed';
                                        }
                                    }
                                }
                            }
                        }

                        $d = array(remakeEmployeeName($employee));

                        if (in_array('w4', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                            array_push($d, $w4_status);
                        }

                        if (in_array('i9', $data['selectedDocumentList']) || in_array('all', $data['selectedDocumentList'])) {
                            array_push($d, $i9_status);
                        }

                        fputcsv($fp, $d);
                    }
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename=' . $filename);
                    ob_flush();
                    exit;
                } else if ($type == 'print') {
                    $this->load->view('hr_documents_management/print_people_with_pending_federal_fillable', $data);
                    return;
                } else if ($type == 'return') {
                    header('Content-Type: application/json');
                    echo json_encode($data['employees']);
                    exit(0);
                }

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/people_with_pending_federal_fillable');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function employee_document($employee_id = NULL)
    {
        if ($employee_id != NULL) {
            if ($this->session->has_userdata('logged_in')) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                $data['user_type'] = 'employee';
                $data['user_sid'] = $employee_id;
                check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
                $data['title'] = 'Document Management';
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employee_id, 0, 0);

                foreach ($assigned_documents as $key => $assigned_document) {
                    //
                    $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                    //
                    if ($assigned_document['archive'] == 0) {
                        $is_magic_tag_exist = 0;
                        $is_document_completed = 0;

                        if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                            $document_body = $assigned_document['document_description'];
                            // $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                            $magic_codes = array('{{signature}}', '{{inital}}');

                            //
                            $documentBodyOld = $document_body;

                            $document_body = $document_body ? magicCodeCorrection($document_body) : $document_body;

                            if ($documentBodyOld != $document_body) {
                                updateDocumentCorrectionDesc($document_body, $assigned_document['sid'], $assigned_document['document_sid']);
                            }


                            if (str_replace($magic_codes, '', $document_body) != $document_body) {
                                $is_magic_tag_exist = 1;
                            }
                        }

                        // if ($assigned_document['approval_process'] == 0) {
                        if ($assigned_document['document_type'] != 'offer_letter') {
                            if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0 && $assigned_document['status'] == 1) {
                                if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                    if ($is_magic_tag_exist == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1) {
                                    if ($assigned_document['acknowledged'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1) {
                                    if ($assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($is_magic_tag_exist == 1) {
                                    if ($assigned_document['user_consent'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                }

                                if ($is_document_completed > 0) {
                                    unset($assigned_documents[$key]);
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                }
                            } else {
                                unset($assigned_documents[$key]);
                            }

                            /*
                                if ($is_document_completed > 0) {
                                    unset($assigned_documents[$key]);
                                } else {
                                    unset($assigned_documents[$key]);
                                }
                                */
                        } else {
                            unset($assigned_documents[$key]);
                        }
                        //    } else {
                        //       unset($assigned_documents[$key]);
                        //   }
                    } else {
                        unset($assigned_documents[$key]);
                    }
                }

                $offer_letter = $this->hr_documents_management_model->getEmployeeOfferLetter($employee_id, 'employee');
                $isCompletedOL = $this->hr_documents_management_model->checkDocumentCompletionStatus($offer_letter);
                //
                if ($isCompletedOL == 0) {
                    $assigned_documents[] = $offer_letter;
                }
                //
                $w4_form = $this->hr_documents_management_model->is_w4_form_assign('employee', $employee_id);
                $w9_form = $this->hr_documents_management_model->is_w9_form_assign('employee', $employee_id);
                $i9_form = $this->hr_documents_management_model->is_i9_form_assign('employee', $employee_id);
                if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_employee_document_center']) {
                    $eeoc_form = $this->hr_documents_management_model->is_eeoc_document_assign('employee', $employee_id);
                } else {
                    $eeoc_form = array();
                }
                $data['w4_form'] = $w4_form;
                $data['w9_form'] = $w9_form;
                $data['i9_form'] = $i9_form;

                $data['eeoc_form'] = $eeoc_form;
                $data['documents'] = $assigned_documents;
                $data['userDetail'] = $this->hr_documents_management_model->getEmployerDetail($employee_id);
                // General Documents
                $data['NotCompletedGeneralDocuments'] = $this->hr_documents_management_model->getGeneralDocuments(
                    $employee_id,
                    'employee',
                    $company_sid,
                    'not_completed'
                );
                $stateForms = $this->hr_documents_management_model
                    ->getCompanyStateForms(
                        $company_sid,
                        $employee_id,
                        "employee"
                    );
                //
                if (checkIfAppIsEnabled('performanceevaluation')) {
                    $this
                        ->load
                        ->model(
                            "v1/Employee_performance_evaluation_model",
                            "employee_performance_evaluation_model"
                        );
                    //
                    $assignPerformanceDocument = $this->employee_performance_evaluation_model->checkEmployeeAssignPerformanceDocument(
                        $employee_id
                    );
                    //
                    if ($assignPerformanceDocument) {
                        //
                        $pendingPerformanceSection = $this->employee_performance_evaluation_model->checkEmployeeUncompletedDocument(
                            $employee_id
                        );
                        //`                                                                 
                        if ($pendingPerformanceSection) {
                            $data['performanceDocumentInfo'] = $this->employee_performance_evaluation_model->getEmployeePerformanceDocumentInfo(
                                $employee_id
                            );
                            //

                        }
                    }
                }
                //
                $data["userNotCompletedStateForms"] = $stateForms["not_completed"];
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/pending-hr-document');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('login'), "refresh");
            } //else end for session check fail
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please select an Employee to review documents');
            redirect(base_url('hr_documents'));
        } //else end for session check fail
    }

    public function send_document_reminder()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name

        if ($this->input->post()) {
            $userDocumentSid = $this->input->post('user_document_sid');
            $userData = $this->hr_documents_management_model->getUserDocument($userDocumentSid);
            $userData = $userData[0];
            $this->load->model('dashboard_model');
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $company_data = $this->dashboard_model->get_company_detail($company_sid);
            $companyname = $company_data['CompanyName'];
            $message_hf = message_header_footer($company_sid, $companyname);
            $emailTemplateData = $this->hr_documents_management_model->getEmailTemplate(HR_DOCUMENTS_NOTIFICATION_EMS, $company_sid);
            if (!sizeof($emailTemplateData)) {
                echo 'Email template not found!';
                exit(0);
            }
            $emailTemplateBody = $emailTemplateData['body'];

            $emailTemplateBody = str_replace('{{first_name}}', ucwords($userData['first_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{last_name}}', ucwords($userData['last_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{user_sid}}', $userDocumentSid, $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);
            $emailTemplateBody = str_replace('{{email}}', $userData['email'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{job_title}}', '', $emailTemplateBody);
            $emailTemplateBody = str_replace('{{date}}', '', $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_address}}', $company_data['Location_Address'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_phone}}', $company_data['PhoneNumber'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{career_site_url}}', $message_hf['sub_domain'], $emailTemplateBody);

            $emailTemplateBody = $message_hf['header'] . $emailTemplateBody . $message_hf['footer'];

            $from = $emailTemplateData['from_email'];
            $to = $userData['email'];
            $subject = $emailTemplateData['subject'];
            $from_name = ucwords(str_replace('{{company_name}}', $company_name, $emailTemplateData['from_name']));

            if ($this->hr_documents_management_model->doSendEmail($userDocumentSid, 'employee', "HREMS10")) {
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $emailTemplateBody,
                );
                save_email_log_common($emailData);
                sendMail($from, $to, $subject, $emailTemplateBody, $from_name);
            }


            echo 'success';
        } else {
            echo 'error';
            // $this->session->set_flashdata('message', '<b>Error:</b> Please try again!');
        }
    }

    public function downloaded_generated_doc($user_sid, $company_sid, $document_sid, $user_type)
    {
        $document = $this->onboarding_model->get_required_document_info($company_sid, $user_sid, $user_type, $document_sid);
        //
        $cpArray = [];
        $cpArray['company_sid'] = $company_sid;
        $cpArray['user_sid'] = $user_sid;
        $cpArray['user_type'] = $user_type;
        $cpArray['document_sid'] = $document_sid;
        $cpArray['document_type'] = 'assigned';
        //
        checkAndInsertCompletedDocument($cpArray);

        if ($document['document_type'] == 'offer_letter') {
            // $document_info = $this->onboarding_model->get_assign_offer_letter_info($document['document_sid']);

            if (!empty($document) && ($document['acknowledgment_required'] == 1 && $document['download_required'] == 1)) {
                if ($document['acknowledged'] == 1) {
                    $data_to_update = array();
                    $data_to_update['downloaded'] = 1;
                    $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');

                    if ($document['signature_required'] == 0 && $document['user_consent'] == 0) {
                        $data_to_update['user_consent'] = 1;
                        $data_to_update['form_input_data'] = 's:2:"{}";';
                        $data_to_update['signature_timestamp'] = date('Y-m-d');
                    }

                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                } else {
                    $data_to_update = array();
                    $data_to_update['downloaded'] = 1;
                    $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');
                    $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
                }
            } else if (!empty($document) && ($document['download_required'] == 1)) {
                $data_to_update = array();
                $data_to_update['downloaded'] = 1;
                $data_to_update['downloaded_date'] = date('Y-m-d H:i:s');

                if ($document['signature_required'] == 0 && $document['user_consent'] == 0) {
                    $data_to_update['user_consent'] = 1;
                    $data_to_update['form_input_data'] = 's:2:"{}";';
                    $data_to_update['signature_timestamp'] = date('Y-m-d');
                }

                $this->hr_documents_management_model->update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update);
            }
        } else {
            $this->hr_documents_management_model->downloaded_generated_doc_on($company_sid, $user_sid, $document_sid, $user_type);
        }
    }

    public function download_assign_document($user_type, $user_sid, $document_sid, $print_type)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $security_sid = $session['employer_detail']['sid'];
            $company_sid = $session['company_detail']['sid'];
            $employer_sid = $session['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            $company_name = $session['company_detail']['CompanyName'];
            $employee_name = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'];

            $data['company_name']   = $company_name;
            $data['employee_name']  = $employee_name;
            $data['action_date']    = 'Download Date';
            $data['action_by']      = 'Download By';
            $data['title']          = 'Document Center';
            $data['session']        = $session;

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);
                $data['document_title']     = $document['document_title'];

                if ($document['document_type'] == 'generated') {
                    $data['document'] = $document;
                    $data['load_view'] = $load_view;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['security_details'] = $security_details;
                    $data['employee'] = $session['employer_detail'];
                    $data['print'] = $print_type;
                    if ($print_type == 'original') {
                        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                        $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                        $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

                        //E_signature process
                        $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
                        $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
                        $signature_timestamp = '<a class="btn blue-button btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

                        $value = ' ';
                        $document_content = str_replace($signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($signature_timestamp, $value, $document_content);

                        $data['original_document_description'] = $document_content;
                    } else {
                        if (isset($document['uploaded_file']) && !empty($document['uploaded_file'])) {
                            $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                            $file_name = $document['uploaded_file'];
                            $temp_file_path = $temp_path . $file_name;

                            if (file_exists($temp_file_path)) {
                                unlink($temp_file_path);
                            }

                            $this->load->library('aws_lib');
                            $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);

                            if (file_exists($temp_file_path)) {
                                header('Content-Description: File Transfer');
                                header('Content-Type: application/octet-stream');
                                header('Content-Disposition: attachment; filename="' . $file_name . '"');
                                header('Expires: 0');
                                header('Cache-Control: must-revalidate');
                                header('Pragma: public');
                                header('Content-Length: ' . filesize($temp_file_path));
                                $handle = fopen($temp_file_path, 'rb');
                                $buffer = '';

                                while (!feof($handle)) {
                                    $buffer = fread($handle, 4096);
                                    echo $buffer;
                                    ob_flush();
                                    flush();
                                }

                                fclose($handle);
                                unlink($temp_file_path);
                            }
                        }
                    }

                    $this->load->view('hr_documents_management/download_generated_document', $data);
                } else {
                    $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;

                    if ($print_type == 'original') {
                        $file_name = $document['document_s3_name'];
                    } else {
                        $file_name = $document['uploaded_file'];
                    }

                    $temp_file_path = $temp_path . $file_name;

                    if (file_exists($temp_file_path)) {
                        unlink($temp_file_path);
                    }

                    $this->load->library('aws_lib');
                    $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $file_name, $temp_file_path);

                    if (file_exists($temp_file_path)) {
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/octet-stream');
                        header('Content-Disposition: attachment; filename="' . $file_name . '"');
                        header('Expires: 0');
                        header('Cache-Control: must-revalidate');
                        header('Pragma: public');
                        header('Content-Length: ' . filesize($temp_file_path));
                        $handle = fopen($temp_file_path, 'rb');
                        $buffer = '';

                        while (!feof($handle)) {
                            $buffer = fread($handle, 4096);
                            echo $buffer;
                            ob_flush();
                            flush();
                        }

                        fclose($handle);
                        unlink($temp_file_path);
                    }
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function print_assign_document($user_type, $user_sid, $document_sid, $print_type)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $data['session'] = $session;
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            $company_name = $session['company_detail']['CompanyName'];
            $employee_name = $session['employer_detail']['first_name'] . ' ' . $session['employer_detail']['last_name'];

            $data['company_name']   = $company_name;
            $data['employee_name']  = $employee_name;
            $data['action_date']    = 'Print Date';
            $data['action_by']      = 'Print By';
            $data['title']          = 'Document Center';

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                if ($document['document_type'] == 'generated') {
                    $data['document']           = $document;
                    $data['load_view']          = $load_view;
                    $data['company_sid']        = $company_sid;
                    $data['employer_sid']       = $employer_sid;
                    $data['security_details']   = $security_details;
                    $data['employee']           = $session['employer_detail'];
                    $data['document_title']     = $document['document_title'];

                    if ($print_type == 'original') {
                        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                        $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                        $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

                        //E_signature process
                        $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
                        $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
                        $signature_timestamp = '<a class="btn blue-button btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

                        $value = ' ';
                        $document_content = str_replace($signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
                        $document_content = str_replace($signature_timestamp, $value, $document_content);

                        $data['original_document_description'] = $document_content;
                        $data['print'] = $print_type;
                    } else {
                        if ($print_type == 'submitted') {
                            $document_filename = $document['uploaded_file'];
                            $document_file = AWS_S3_BUCKET_URL . $document_filename;
                            $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
                        }
                        $data['print'] = 'original';
                    }

                    $data['download'] = NULL;
                    $data['file_name'] = NULL;
                    $this->load->view('hr_documents_management/print_generated_document', $data);
                } else {
                    $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                    if ($print_type == 'submitted') {
                        $document_filename = $document['uploaded_file'];
                        $document_file = AWS_S3_BUCKET_URL . $document_filename;
                        $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
                    } else if ($print_type == 'original') {
                        $document_filename = $document['document_s3_name'];
                        $document_file = AWS_S3_BUCKET_URL . $document_filename;
                        $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
                    }

                    $data['download']       = NULL;
                    $data['file_name']      = NULL;
                    $data['print']          = 'original';
                    $data['document_title'] = $document['document_title'];

                    $this->load->view('hr_documents_management/print_generated_document', $data);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function vimeo_get_id($str)
    {
        if ($str != "") {
            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $response = @file_get_contents($api_url);

                if (!empty($response)) {
                    $response = json_decode($response, true);

                    if (isset($response['video_id'])) {
                        return $response['video_id'];
                    } else {
                        return 0;
                    }
                } else {
                    return 0;
                }
            } else {
                $api_url = 'https://vimeo.com/api/oembed.json?url=' . urlencode($str);
                $cSession = curl_init();
                curl_setopt($cSession, CURLOPT_URL, $api_url);
                curl_setopt($cSession, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($cSession, CURLOPT_HEADER, false);
                $response = curl_exec($cSession);
                curl_close($cSession);
                $response = json_decode($response, true); //$response = @file_get_contents($api_url);

                if (isset($response['video_id'])) {
                    return $response['video_id'];
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    public function print_upload_img($image_url)
    {
        $document_file = AWS_S3_BUCKET_URL . $image_url;
        $data['print'] = '';
        $data['download'] = NULL;
        $data['file_name'] = NULL;
        $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
        $this->load->view('hr_documents_management/print_generated_document', $data);
    }

    public function preview_generated_doc()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];

            $document_sid = $this->input->post('document_sid');
            $user_type = $this->input->post('user_type');
            $user_sid = $this->input->post('user_sid');
            $user_type = empty($user_type) ? null : $user_type;
            $user_sid = empty($user_sid) ? null : $user_sid;
            $document_info = $this->hr_documents_management_model->get_submitted_generated_document($document_sid);

            if (!empty($document_info)) {
                $document_content = $document_info['document_description'];
                $document = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_content, $document_info['document_sid']);
                $view_data = array();
                $view_data['document_title'] = $document_info['document_title'];
                $view_data['document_body'] = $document;
                echo $this->load->view('hr_documents_management/generated_document_preview_partial', $view_data, true);
            }
        }
    }

    public function print_generated_doc($type, $sid, $user_sid, $user_type, $download = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $document = $this->hr_documents_management_model->get_submitted_generated_document($sid);

            if ($type == 'original') {
                $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                $document_content = html_entity_decode(str_replace('[Target User Checkbox]', $value, $document_content));

                //E_signature process
                $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="draw_upload_img" />';
                $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src=""  id="target_signature_init" />';
                $signature_timestamp = '<a class="btn blue-button btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

                $value = '';
                $document_content = str_replace($signature_bas64_image, $value, $document_content);
                $document_content = str_replace($init_signature_bas64_image, $value, $document_content);
                $document_content = str_replace($signature_timestamp, $value, $document_content);

                $data['print'] = $type;
                $data['document_file'] = 'no_pdf';
                $data['download'] = $download;
                $data['file_name'] = $document['document_title'];
                $data['original_document_description'] = $document_content;
            } else if ($type == 'submitted') {
                $data['print'] = $type;
                $data['document_file'] = 'no_pdf';
                $data['download'] = $download;
                $data['file_name'] = $document['document_title'];
                $data['document'] = $document;
            }

            $this->load->view('hr_documents_management/print_generated_document', $data);
        }
    }

    public function print_generated_and_offer_later($type, $document_type, $document_sid, $download = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];

            if ($document_type == 'offer') {
                $document = $this->hr_documents_management_model->get_offer_latter($document_sid);
                $document['document_type'] = 'generated';
            } else if ($document_type == 'generated' && $type == 'original') {
                $document = $this->hr_documents_management_model->get_original_document($document_sid);
            } else {
                $document = $this->hr_documents_management_model->get_submitted_generated_document($document_sid);
                $document_sid = $document['document_sid'];
            }

            if ($document['document_type'] == 'generated') {
                if ($document_type == 'offer') {
                    $document_content = $document['letter_body'];
                    $data['file_name'] = $document['letter_name'];
                } else if ($document_type == 'generated') {
                    $document_content = $document['document_description'];
                    $data['file_name'] = $document['document_title'];
                }

                if ($type == 'original' || $type == 'assigned') {
                    $value = '------------------------------';
                    $document_content = str_replace('{{first_name}}', $value, $document_content);
                    $document_content = str_replace('{{firstname}}', $value, $document_content);
                    $document_content = str_replace('{{last_name}}', $value, $document_content);
                    $document_content = str_replace('{{lastname}}', $value, $document_content);
                    $document_content = str_replace('{{email}}', $value, $document_content);
                    $document_content = str_replace('{{job_title}}', $value, $document_content);
                    $document_content = str_replace('{{company_name}}', $value, $document_content);
                    $document_content = str_replace('{{company_address}}', $value, $document_content);
                    $document_content = str_replace('{{company_phone}}', $value, $document_content);
                    $document_content = str_replace('{{career_site_url}}', $value, $document_content);
                    $document_content = str_replace('{{signature}}', $value, $document_content);
                    $document_content = str_replace('{{inital}}', $value, $document_content);
                    $document_content = str_replace('{{sign_date}}', $value, $document_content);
                    $document_content = str_replace('{{signature_print_name}}', $value, $document_content);
                    $document_content = str_replace('{{short_text}}', $value, $document_content);
                    $value = '------/-------/----------------';
                    $document_content = str_replace('{{start_date}}', $value, $document_content);

                    $value = 'Date :------/-------/----------------';
                    $document_content = str_replace('{{date}}', $value, $document_content);

                    $value = 'Please contact with your manager';
                    $document_content = str_replace('{{username}}', $value, $document_content);
                    $document_content = str_replace('{{password}}', $value, $document_content);

                    $authorized_base64 = get_authorized_base64_signature($company_sid, $document_sid);

                    if (!empty($authorized_base64)) {
                        $authorized_signature = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . 'px;" src="' . $authorized_base64 . '">';
                        $authorized_signature_date = '';
                    } else {
                        $authorized_signature = '';
                        $authorized_signature_date = 'Authorize Sign Date :------/-------/----------------';
                    }

                    $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);
                    $document_content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_content);

                    $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                    $document_content = str_replace('{{checkbox}}', $value, $document_content);

                    $value = '<div style="border: 1px dotted #777; padding:5px;background-color:#eee;"  contenteditable="true"></div>';
                    $document_content = str_replace('{{text}}', $value, $document_content);

                    $value = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px;background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                    $document_content = str_replace('{{text_area}}', $value, $document_content);

                    $data['print'] = $type;
                    $data['document_file'] = 'no_pdf';
                    $data['download'] = $download;
                    $data['original_document_description'] = $document_content;
                } else if ($type == 'submitted') {
                    $data['print'] = $type;
                    $data['document_file'] = 'pdf';
                    $data['download'] = $download;
                    $data['file_name'] = $document['document_title'];
                    $data['document'] = $document;
                }

                if ($document["fillable_document_slug"]) {
                    $postfix = $type === "original" ? "print_assigned" : "print";
                    return $this->load->view("v1/documents/fillable/{$document["fillable_document_slug"]}_{$postfix}", $data);
                }

                $this->load->view('hr_documents_management/print_generated_document', $data);
            } else if ($document['document_type'] == 'uploaded') {
                if ($type == 'original') {
                    $document_file = AWS_S3_BUCKET_URL . $document['uploaded_document_s3_name'];
                } else if ($type == 'assigned') {
                    $document_file = AWS_S3_BUCKET_URL . $document['document_s3_name'];
                } else if ($type == 'submitted') {
                    $document_file = AWS_S3_BUCKET_URL . $document['uploaded_file'];
                }

                $data['print'] = 'generated';
                $data['document_file'] = 'no_pdf';
                $data['download'] = $download;
                $data['file_name'] = $document['document_title'];
                $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
                $this->load->view('hr_documents_management/print_generated_document', $data);
            } else if ($document['document_type'] == 'offer_letter') {

                $document_content = $document['document_description'];
                $data['file_name'] = $document['document_title'];

                if ($type == 'assigned') {
                    $value = '------------------------------';
                    $document_content = str_replace('{{first_name}}', $value, $document_content);
                    $document_content = str_replace('{{last_name}}', $value, $document_content);
                    $document_content = str_replace('{{email}}', $value, $document_content);
                    $document_content = str_replace('{{job_title}}', $value, $document_content);
                    $document_content = str_replace('{{company_name}}', $value, $document_content);
                    $document_content = str_replace('{{company_address}}', $value, $document_content);
                    $document_content = str_replace('{{company_phone}}', $value, $document_content);
                    $document_content = str_replace('{{career_site_url}}', $value, $document_content);
                    $document_content = str_replace('{{signature}}', $value, $document_content);
                    $document_content = str_replace('{{inital}}', $value, $document_content);
                    $document_content = str_replace('{{sign_date}}', $value, $document_content);
                    $document_content = str_replace('{{signature_print_name}}', $value, $document_content);
                    $document_content = str_replace('{{short_text}}', $value, $document_content);
                    $authorized_base64 = get_authorized_base64_signature($company_sid, $document_sid);

                    if (!empty($authorized_base64)) {
                        $authorized_signature = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $authorized_base64 . '">';
                        $authorized_signature_date = '';
                    } else {
                        $authorized_signature = '';
                        $authorized_signature_date = '';
                    }

                    $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);
                    $document_content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_content);

                    $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                    $document_content = str_replace('{{checkbox}}', $value, $document_content);

                    $value = '<div style="border: 1px dotted #777; padding:5px;background-color:#eee;"  contenteditable="true"></div>';
                    $document_content = str_replace('{{text}}', $value, $document_content);

                    $value = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px;background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                    $document_content = str_replace('{{text_area}}', $value, $document_content);

                    $data['print'] = $type;
                    $data['document_file'] = 'no_pdf';
                    $data['download'] = $download;
                    $data['original_document_description'] = $document_content;
                } else if ($type == 'submitted') {
                    $data['print'] = $type;
                    $data['document_file'] = 'pdf';
                    $data['download'] = $download;
                    $data['file_name'] = $document['document_title'];
                    $data['document'] = $document;
                }

                $this->load->view('hr_documents_management/print_generated_document', $data);
            }
        }
    }

    public function download_upload_document($document_path)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Documents Assignment';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            if ($this->form_validation->run() == false) {
                //
                $document_path = urldecode($document_path);
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document_path;
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document_path, $temp_file_path);

                if (file_exists($temp_file_path)) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="' . $file_name . '"');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($temp_file_path));
                    $handle = fopen($temp_file_path, 'rb');
                    $buffer = '';

                    while (!feof($handle)) {
                        $buffer = fread($handle, 4096);
                        echo $buffer;
                        ob_flush();
                        flush();
                    }

                    fclose($handle);
                    unlink($temp_file_path);
                }
            } else {
                //nothing
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function get_document_employees()
    {
        $doc_sid = $this->input->post('doc_sid');
        $doc_type = $this->input->post('doc_type');

        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employees = $this->hr_documents_management_model->fetch_documents_employees($doc_sid, $doc_type, $company_sid);
        if (!$this->input->post('departments')) {
            // Get all active Departments
            $departments = $this->hr_documents_management_model->getDepartments(
                $data['session']['company_detail']['sid']
            );
            // Get all active Teams
            $teams = $this->hr_documents_management_model->getTeams(
                $data['session']['company_detail']['sid'],
                $departments
            );
            //
            header('Content-Type: application/json');
            //
            echo json_encode(array(
                'Employees' => $employees,
                'Departments' =>  $departments,
                'Teams' =>  $teams
            ));
            exit(0);
        }

        echo json_encode($employees);
    }

    public function get_print_url()
    {
        if ($this->session->userdata('logged_in')) {
            $request_type = $this->input->post('request_type');
            $document_type = $this->input->post('document_type');
            $document_sid = $this->input->post('document_sid');
            //
            if ($document_type == 'uploaded') {
                $document_type = 'MS';
            }
            //
            $url = get_print_document_url($request_type, $document_type, $document_sid);
            //
            echo json_encode($url);
        }
    }

    public function get_print_and_download_urls()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        //
        $request_type = $this->input->post('request_type');
        $document_type = $this->input->post('document_type');
        $document_sid = $this->input->post('document_sid');
        //
        if ($document_type == 'offer') {
            $document = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $document_sid);
        } else {
            $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
        }
        //
        echo json_encode(getPDBTN($document));
    }

    public function check_active_auth_signature($document_sid, $company_sid)
    {
        $signature = $this->hr_documents_management_model->is_authorized_signature_exist($document_sid, $company_sid);
        $return_data = array();

        if (!empty($signature)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deactivate_auth_signature($document_sid)
    {
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $this->hr_documents_management_model->remove_authorized_signature_if_exist($document_sid, $data_to_update);
    }

    public function switch_admin_hr_to_new_documents()
    {
        if ($this->session->has_userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            $documents = $this->hr_documents_management_model->get_all_hr_documents();

            foreach ($documents as $key => $v) {
                $data = array();
                $data['company_sid'] = $v['company_sid'];
                $data['employer_sid'] = $v['employer_sid'];
                $data['document_title'] = $v['document_original_name'];
                $data['document_description'] = $v['document_description'];
                $data['document_type'] = 'uploaded';
                $data['uploaded_document_original_name'] = $v['document_original_name'];
                $data['uploaded_document_extension'] = $v['document_type'];
                $data['uploaded_document_s3_name'] = $v['document_name'];
                $data['unique_key'] = generateRandomString(32);
                $data['date_created'] = $v['date_uploaded'];
                $data['onboarding'] = $v['onboarding'];
                $data['action_required'] = $v['action_required'];
                $data['acknowledgment_required'] = $v['action_required'];
                $data['download_required'] = $v['action_required'];
                $data['signature_required'] = $v['action_required'];
                $data['to_all_employees'] = 0;

                if ($v['date_uploaded'] == '' || $v['date_uploaded'] == NULL) {
                    $data['date_created'] = NULL;
                } else {
                    $data['date_created'] = $v['date_uploaded'];
                }

                if ($v['archive'] == 1) {
                    $data['archive'] = 0;
                } else {
                    $data['archive'] = 1;
                }

                $data['video_required'] = 0;
                $data['video_source'] = NULL;
                $data['video_url'] = NULL;
                $data['copied_doc_sid'] = $v['sid'];
                $data['sort_order'] = $v['sid'];
                $documents_management_sid = $this->hr_documents_management_model->insert_document_record($data);

                $data_for_hr = array();
                $data_for_hr['documents_management_sid'] = $documents_management_sid;
                $this->hr_documents_management_model->update_hr_document($v['sid'], $data_for_hr);
            }
        }
    }

    public function documents_group_management()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid);

            if (!empty($groups)) {
                foreach ($groups as $key => $group) {
                    $document_status = $this->hr_documents_management_model->is_document_assign_2_group($group['sid']);
                    $groups[$key]['document_status'] = $document_status;
                }
            }

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $data['title'] = 'Group Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['groups'] = $groups;

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/documents_group_management');
                $this->load->view('main/footer');
            } else {
                $employees = $this->input->post('employees');
                $group_assign_sid = $this->input->post('group_sid');
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['group_sid'] = $group_assign_sid;
                $data_to_insert['assigned_by_sid'] = $employer_sid;
                $data_to_insert['applicant_sid'] = 0;
                if (in_array('-1', $employees)) {
                    $Allemployees = $this->hr_documents_management_model->fetch_all_company_employees($company_sid);
                    $employees = array_column($Allemployees, 'sid');
                }

                if (!empty($employees)) {
                    foreach ($employees as $key => $employee) {
                        $data_to_insert['employer_sid'] = $employee;
                        $is_group_assign = $this->hr_documents_management_model->check_group_already_assigned($company_sid, $employee, $group_assign_sid);

                        if ($is_group_assign == 0) {
                            $this->hr_documents_management_model->assign_document_group_2_empliyees($data_to_insert);
                        }
                    }
                }


                $this->session->set_flashdata('message', '<strong>Success:</strong> Documents Group Update Successfully!');
                redirect('hr_documents_management/documents_group_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add_edit_document_group_management($group_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Group Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            if ($group_sid != NULL) {
                $group = $this->hr_documents_management_model->get_document_group($group_sid);
                $data['group'] = $group;
                $data['submit_button_text'] = 'Update';
                $data['perform_action'] = 'edit_document_group';
            } else {
                $data['submit_button_text'] = 'Save';
                $data['perform_action'] = 'add_document_group';
            }

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/add_edit_document_group');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_document_group':
                        $group_name = $this->input->post('name');
                        $group_description = $this->input->post('description');
                        $group_status = $this->input->post('status');
                        $group_sort_order = $this->input->post('sort_order');
                        $ip_address = $this->input->post('ip_address');
                        $data_to_insert = array();
                        $new_history_data = array();
                        $group_description = htmlentities($group_description);

                        if (empty($group_sort_order)) {
                            $group_sort_order = 0;
                        }

                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['name'] = $group_name;
                        $data_to_insert['description'] = $group_description;
                        $data_to_insert['status'] = $group_status;
                        $data_to_insert['sort_order'] = $group_sort_order;
                        $data_to_insert['created_by_sid'] = $employer_sid;
                        $data_to_insert['ip_address'] = $ip_address;
                        $insert_id = $this->hr_documents_management_model->insert_group_record($data_to_insert);
                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data['group_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['name'] = $group_name;
                        $new_history_data['description'] = $group_description;
                        $new_history_data['status'] = $group_status;
                        $new_history_data['sort_order'] = $group_sort_order;
                        $new_history_data['updated_by_sid'] = $employer_sid;
                        $new_history_data['ip_address'] = $ip_address;
                        $this->hr_documents_management_model->insert_group_history($new_history_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Group Created Successfully!');
                        redirect('hr_documents_management/documents_group_management', 'refresh');
                        break;
                    case 'edit_document_group':
                        $group_name = $this->input->post('name');
                        $group_description = $this->input->post('description');
                        $group_status = $this->input->post('status');
                        $group_sort_order = $this->input->post('sort_order');
                        $ip_address = $this->input->post('ip_address');
                        $data_to_update = array();
                        $new_history_data = array();
                        $group_description = htmlentities($group_description);

                        if (empty($group_sort_order)) {
                            $group_sort_order = 0;
                        }

                        $data_to_update['name'] = $group_name;
                        $data_to_update['description'] = $group_description;
                        $data_to_update['status'] = $group_status;
                        $data_to_update['sort_order'] = $group_sort_order;
                        $data_to_update['ip_address'] = $ip_address;
                        $new_history_data = $group;
                        $new_history_data['group_sid'] = $group_sid;
                        $new_history_data['updated_by_sid'] = $group_status;
                        unset($new_history_data['sid']);
                        unset($new_history_data['created_by_sid']);
                        unset($new_history_data['created_date']);
                        $this->hr_documents_management_model->insert_group_history($new_history_data);
                        $this->hr_documents_management_model->update_document_group($group_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Group Updated Successfully!');
                        redirect('hr_documents_management/documents_group_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function document_2_group($group_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assign Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $documents = $this->hr_documents_management_model->get_all_documents($company_sid);
            $pre_assign_documents = $this->hr_documents_management_model->get_all_document_2_group($group_sid);
            $group = $this->hr_documents_management_model->get_document_group($group_sid);
            $assigned_documents = array();

            if (!empty($pre_assign_documents)) {
                foreach ($pre_assign_documents as $key => $pre_assign) {
                    array_push($assigned_documents, $pre_assign['document_sid']);
                }
            }

            $data["companyStateForms"] = $this->hr_documents_management_model
                ->getCompanyStateForm(
                    $company_sid
                );

            $data['group'] = $group;
            $data['selected_state_forms'] = $group["state_forms_json"] ? json_decode($group["state_forms_json"], true) : [];
            $data['group_name'] = $group['name'];
            $data['assigned_documents'] = $assigned_documents;
            $data['documents'] = $documents;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/document_2_group');
                $this->load->view('main/footer');
            } else {
                $assign_documents = $this->input->post('documents');
                $assign_system_documents = $this->input->post('system_documents');
                $general_documents = $this->input->post('general_documents');
                $stateForms = $this->input->post('state_forms');
                $this->hr_documents_management_model->unassign_system_document_from_group($group_sid);

                if (!empty($assign_system_documents)) {
                    foreach ($assign_system_documents as $document_name) {
                        $data_to_update = array();

                        if ($document_name == 'w4') {
                            $data_to_update['w4'] = 1;
                        } else if ($document_name == 'w9') {
                            $data_to_update['w9'] = 1;
                        } else if ($document_name == 'i9') {
                            $data_to_update['i9'] = 1;
                        } else if ($document_name == 'eeoc') {
                            $data_to_update['eeoc'] = 1;
                        }


                        $this->hr_documents_management_model->assign_system_document_2_group($group_sid, $data_to_update);
                    }
                }

                // For General Documents
                if (!empty($general_documents)) {
                    foreach ($general_documents as $d) {
                        //
                        $upd = [];
                        //
                        $upd[$d] = 1;
                        //
                        $this->hr_documents_management_model->assign_system_document_2_group($group_sid, $upd);
                    }
                }

                if ($stateForms) {
                    $this->hr_documents_management_model->assign_system_document_2_group($group_sid, [
                        "state_forms_json" => json_encode($stateForms)
                    ]);
                } else {
                    $this->hr_documents_management_model->assign_system_document_2_group($group_sid, [
                        "state_forms_json" => json_encode([])
                    ]);
                }

                $this->hr_documents_management_model->delete_document_2_group($group_sid);

                if (!empty($assign_documents)) {
                    foreach ($assign_documents as $key => $document_sid) {
                        $data_to_insert = array();
                        $data_to_insert['group_sid'] = $group_sid;
                        $data_to_insert['document_sid'] = $document_sid;
                        $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                    }
                }

                $this->session->set_flashdata('message', '<strong>Success:</strong> Documents Update Successfully!');
                redirect('hr_documents_management/documents_group_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function get_all_company_employees()
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employees = $this->hr_documents_management_model->fetch_all_company_employees($company_sid);
        //
        if (!empty($employees)) {
            foreach ($employees as $e_key => $employee) {
                $employees[$e_key]["full_name"] = getUserNameBySID($employee["sid"]);
            }
        }
        //
        echo json_encode($employees);
    }

    public function ajax_assign_group_2_applicant($group_sid, $user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;

            if ($user_type == 'employee') {
                $data_to_insert['employer_sid'] = $user_sid;
                $data_to_insert['applicant_sid'] = 0;
            } else if ($user_type == 'applicant') {
                $data_to_insert['employer_sid'] = 0;
                $data_to_insert['applicant_sid'] = $user_sid;
            }

            $data_to_insert['group_sid'] = $group_sid;
            $data_to_insert['assigned_by_sid'] = $employer_sid;
            $this->hr_documents_management_model->assign_document_group_2_empliyees($data_to_insert);
            echo 'success';
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_revoke_document_group($group_sid, $user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data_to_update = array();
            $data_to_update['assign_status'] = 0;
            $data_to_update['revoke_by'] = $employer_sid;
            $data_to_update['revoke_date'] = date('Y-m-d H:i:s');
            //
            $this->hr_documents_management_model->change_document_assign_group_status($group_sid, $user_type, $user_sid, $data_to_update);
            //
            $group_documents = $this->hr_documents_management_model->get_distinct_group_docs($group_sid);
            //
            if (!empty($group_documents)) {
                //
                foreach ($group_documents as $document) {
                    $this->hr_documents_management_model->change_group_document_status($document['document_sid'], $user_type, $user_sid, 0);
                }
                //
            }
            //
            $system_document = $this->hr_documents_management_model->get_document_group($group_sid);
            //
            if ($system_document['direct_deposit'] == 1) {
                $this->hr_documents_management_model->revoke_general_document(
                    'direct_deposit',
                    $user_sid,
                    $user_type
                );
            }
            //    
            if ($system_document['drivers_license'] == 1) {
                $this->hr_documents_management_model->revoke_general_document(
                    'drivers_license',
                    $user_sid,
                    $user_type
                );
            }
            //    
            if ($system_document['occupational_license'] == 1) {
                $this->hr_documents_management_model->revoke_general_document(
                    'occupational_license',
                    $user_sid,
                    $user_type
                );
            }
            //    
            if ($system_document['emergency_contacts'] == 1) {
                $this->hr_documents_management_model->revoke_general_document(
                    'emergency_contacts',
                    $user_sid,
                    $user_type
                );
            }
            //    
            if ($system_document['dependents'] == 1) {
                $this->hr_documents_management_model->revoke_general_document(
                    'dependents',
                    $user_sid,
                    $user_type
                );
            }
            //
            if ($system_document['w4'] == 1) {
                $this->hr_documents_management_model->deactivate_w4_forms($user_type, $user_sid);
            }
            //
            if ($system_document['w9'] == 1) {
                $this->hr_documents_management_model->deactivate_w9_forms($user_type, $user_sid);
            }
            //
            if ($system_document['i9'] == 1) {
                $this->hr_documents_management_model->deactivate_i9_forms($user_type, $user_sid);
            }
            //
            echo 'success';
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_reassign_document_group($group_sid, $user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data_to_update = array();
            $data_to_update['assign_status'] = 1;
            $data_to_update['assigned_by_sid'] = $employer_sid;
            $data_to_update['assigned_date'] = date('Y-m-d H:i:s');
            //
            $this->hr_documents_management_model->change_document_assign_group_status($group_sid, $user_type, $user_sid, $data_to_update);
            //
            $group_documents = $this->hr_documents_management_model->get_distinct_group_docs($group_sid);
            //

            if (!empty($group_documents)) {
                //
                foreach ($group_documents as $document) {
                    $assigned_document = $this->hr_documents_management_model->getAssignedGroupDocument(
                        $document['document_sid'],
                        $user_sid,
                        $user_type
                    );
                    //
                    if (!empty($assigned_document)) {
                        //
                        $assignInsertId = $assigned_document['sid'];
                        //
                        if ($assigned_document["user_consent"] == 1) {
                            //
                            unset($assigned_document['sid']);
                            unset($assigned_document['is_pending']);
                            //
                            $history_array = $assigned_document;
                            $history_array['doc_sid'] = $assignInsertId;
                            //
                            $this->hr_documents_management_model->insert_documents_assignment_record_history($history_array);
                        }
                        //
                        $document_to_update['status'] = 1;
                        $document_to_update['acknowledged'] = NULL;
                        $document_to_update['acknowledged_date'] = NULL;
                        $document_to_update['downloaded'] = NULL;
                        $document_to_update['downloaded_date'] = NULL;
                        $document_to_update['uploaded'] = NULL;
                        $document_to_update['uploaded_date'] = NULL;
                        $document_to_update['signature_timestamp'] = NULL;
                        $document_to_update['signature'] = NULL;
                        $document_to_update['signature_email'] = NULL;
                        $document_to_update['signature_ip'] = NULL;
                        $document_to_update['user_consent'] = 0;
                        $document_to_update['archive'] = 0;
                        $document_to_update['signature_base64'] = NULL;
                        $document_to_update['signature_initial'] = NULL;
                        $document_to_update['authorized_signature'] = NULL;
                        $document_to_update['authorized_signature_by'] = NULL;
                        $document_to_update['authorized_signature_date'] = NULL;
                        //
                        $this->hr_documents_management_model->reassign_group_document($document['document_sid'], $user_type, $user_sid, $document_to_update);
                        //
                        $original_document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document['document_sid']);
                        //
                        if ($original_document['document_type'] != "uploaded" && !empty($original_document['document_description'])) {
                            $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $original_document['document_description']);
                            //
                            if ($isAuthorized == 1) {
                                // Managers handling
                                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                    $original_document['managers_list'],
                                    $assignInsertId,
                                    $company_sid,
                                    $employer_sid
                                );
                            }
                        }
                    } else {
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document['document_sid']);
                        //
                        $document_to_insert = array();
                        $document_to_insert['company_sid'] = $company_sid;
                        $document_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                        $document_to_insert['assigned_by'] = $employer_sid;
                        $document_to_insert['user_type'] = $user_type;
                        $document_to_insert['user_sid'] = $user_sid;
                        $document_to_insert['document_type'] = $document['document_type'];
                        $document_to_insert['document_sid'] = $document['document_sid'];
                        $document_to_insert['status'] = 1;
                        $document_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                        $document_to_insert['document_extension'] = $document['uploaded_document_extension'];
                        $document_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                        $document_to_insert['document_title'] = $document['document_title'];
                        $document_to_insert['document_description'] = $document['document_description'];
                        $document_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                        $document_to_insert['signature_required'] = $document['signature_required'];
                        $document_to_insert['download_required'] = $document['download_required'];
                        $document_to_insert['is_required'] = $document['is_required'];
                        //
                        $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($document_to_insert);
                        //
                        if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                            $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $document['document_description']);
                            //
                            if ($isAuthorized == 1) {
                                // Managers handling
                                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                    $document['managers_list'],
                                    $assignment_sid,
                                    $company_sid,
                                    $employer_sid
                                );
                            }
                        }
                    }
                    //
                    if ($document['has_approval_flow'] == 1) {
                        $this->HandleApprovalFlow(
                            $assignment_sid,
                            $document['document_approval_note'],
                            $document["document_approval_employees"],
                            0,
                            $document['managers_list']
                        );
                    }
                }
                //
            }
            //
            $system_document = $this->hr_documents_management_model->get_document_group($group_sid);
            //
            if ($system_document['direct_deposit'] == 1) {
                $is_assign = $this->hr_documents_management_model->reassign_general_document(
                    'direct_deposit',
                    $user_sid,
                    $user_type
                );
                //
                if ($is_assign == "not_assign") {
                    $this->hr_documents_management_model->assignGeneralDocument(
                        $user_sid,
                        $user_type,
                        $company_sid,
                        'direct_deposit',
                        $employer_sid
                    );
                }
            }
            //    
            if ($system_document['drivers_license'] == 1) {
                $is_assign = $this->hr_documents_management_model->reassign_general_document(
                    'drivers_license',
                    $user_sid,
                    $user_type
                );
                //
                if ($is_assign == "not_assign") {
                    $this->hr_documents_management_model->assignGeneralDocument(
                        $user_sid,
                        $user_type,
                        $company_sid,
                        'drivers_license',
                        $employer_sid
                    );
                }
            }
            //    
            if ($system_document['occupational_license'] == 1) {
                $is_assign = $this->hr_documents_management_model->reassign_general_document(
                    'occupational_license',
                    $user_sid,
                    $user_type
                );
                //
                if ($is_assign == "not_assign") {
                    $this->hr_documents_management_model->assignGeneralDocument(
                        $user_sid,
                        $user_type,
                        $company_sid,
                        'occupational_license',
                        $employer_sid
                    );
                }
            }
            //    
            if ($system_document['emergency_contacts'] == 1) {
                $is_assign = $this->hr_documents_management_model->reassign_general_document(
                    'emergency_contacts',
                    $user_sid,
                    $user_type
                );
                //
                if ($is_assign == "not_assign") {
                    $this->hr_documents_management_model->assignGeneralDocument(
                        $user_sid,
                        $user_type,
                        $company_sid,
                        'emergency_contacts',
                        $employer_sid
                    );
                }
            }
            //    
            if ($system_document['dependents'] == 1) {
                $is_assign = $this->hr_documents_management_model->reassign_general_document(
                    'dependents',
                    $user_sid,
                    $user_type
                );
                //
                if ($is_assign == "not_assign") {
                    $this->hr_documents_management_model->assignGeneralDocument(
                        $user_sid,
                        $user_type,
                        $company_sid,
                        'dependents',
                        $employer_sid
                    );
                }
            }
            //    
            //
            if ($system_document['w4'] == 1) {
                //
                $already_assigned_w4 = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
                //
                if (!empty($already_assigned_w4)) {
                    if ($already_assigned_w4["user_consent"] == 1) {
                        //
                        $already_assigned_w4['form_w4_ref_sid'] = $already_assigned_w4['sid'];
                        unset($already_assigned_w4['sid']);
                        $this->hr_documents_management_model->w4_forms_history($already_assigned_w4);
                    }
                    //
                    $w4_data_to_update                                          = array();
                    $w4_data_to_update['sent_date']                             = date('Y-m-d H:i:s');
                    $w4_data_to_update['status']                                = 1;
                    $w4_data_to_update['signature_timestamp']                   = NULL;
                    $w4_data_to_update['signature_email_address']               = NULL;
                    $w4_data_to_update['signature_bas64_image']                 = NULL;
                    $w4_data_to_update['init_signature_bas64_image']            = NULL;
                    $w4_data_to_update['ip_address']                            = NULL;
                    $w4_data_to_update['user_agent']                            = NULL;
                    $w4_data_to_update['user_consent']                          = NULL;

                    $this->hr_documents_management_model->activate_w4_forms($user_type, $user_sid, $w4_data_to_update);
                } else {
                    $w4_data_to_insert = array();
                    $w4_data_to_insert['employer_sid'] = $user_sid;
                    $w4_data_to_insert['company_sid'] = $company_sid;
                    $w4_data_to_insert['user_type'] = $user_type;
                    $w4_data_to_insert['sent_status'] = 1;
                    $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                    $w4_data_to_insert['status'] = 1;
                    //
                    $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
                }
            }
            //
            if ($system_document['w9'] == 1) {
                //
                $already_assigned_w9 = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);
                //
                if (!empty($already_assigned_w9)) {
                    if ($already_assigned_w9["user_consent"] == 1) {
                        //
                        $already_assigned_w9['w9form_ref_sid'] = $already_assigned_w9['sid'];
                        unset($already_assigned_w9['sid']);
                        $this->hr_documents_management_model->w9_forms_history($already_assigned_w9);
                    }
                    //
                    $w9_data_to_update = array();
                    $w9_data_to_update['ip_address'] = NULL;
                    $w9_data_to_update['user_agent'] = NULL;
                    $w9_data_to_update['active_signature'] = NULL;
                    $w9_data_to_update['signature'] = NULL;
                    $w9_data_to_update['user_consent'] = NULL;
                    $w9_data_to_update['signature_timestamp'] = NULL;
                    $w9_data_to_update['signature_email_address'] = NULL;
                    $w9_data_to_update['signature_bas64_image'] = NULL;
                    $w9_data_to_update['init_signature_bas64_image'] = NULL;
                    $w9_data_to_update['signature_ip_address'] = NULL;
                    $w9_data_to_update['signature_user_agent'] = NULL;
                    $w9_data_to_update['sent_date'] = date('Y-m-d H:i:s');
                    $w9_data_to_update['status'] = 1;
                    $w9_data_to_update['user_consent'] = NULL;
                    //
                    $this->hr_documents_management_model->activate_w9_forms($user_type, $user_sid, $w9_data_to_update);
                } else {
                    $w9_data_to_insert = array();
                    $w9_data_to_insert['user_sid'] = $user_sid;
                    $w9_data_to_insert['company_sid'] = $company_sid;
                    $w9_data_to_insert['user_type'] = $user_type;
                    $w9_data_to_insert['sent_status'] = 1;
                    $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                    $w9_data_to_insert['status'] = 1;
                    $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
                }
            }
            //
            if ($system_document['i9'] == 1) {
                //
                $already_assigned_i9 = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);
                //
                if (!empty($already_assigned_i9)) {
                    if ($already_assigned_w9["user_consent"] == 1) {
                        //
                        $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                        unset($already_assigned_i9['sid']);
                        $this->hr_documents_management_model->i9_forms_history($already_assigned_i9);
                    }
                    //
                    $i9_data_to_update = array();
                    $i9_data_to_update['status'] = 1;
                    $i9_data_to_update["sent_status"] = 1;
                    $i9_data_to_update["sent_date"] = date('Y-m-d H:i:s');
                    $i9_data_to_update["section1_emp_signature"] = NULL;
                    $i9_data_to_update["section1_emp_signature_init"] = NULL;
                    $i9_data_to_update["section1_emp_signature_ip_address"] = NULL;
                    $i9_data_to_update["section1_emp_signature_user_agent"] = NULL;
                    $i9_data_to_update["section1_preparer_signature"] = NULL;
                    $i9_data_to_update["section1_preparer_signature_init"] = NULL;
                    $i9_data_to_update["section1_preparer_signature_ip_address"] = NULL;
                    $i9_data_to_update["section1_preparer_signature_user_agent"] = NULL;
                    $i9_data_to_update["section1_preparer_signature_user_agent"] = NULL;
                    $i9_data_to_update["section2_sig_emp_auth_rep"] = NULL;
                    $i9_data_to_update["section3_emp_sign"] = NULL;
                    $i9_data_to_update["employer_flag"] = 0;
                    $i9_data_to_update["user_consent"] = NULL;
                    $i9_data_to_update["version"] = getSystemDate('Y');
                    $i9_data_to_update["section1_preparer_json"] = NULL;
                    $i9_data_to_update["section3_authorized_json"] = NULL;
                    //
                    $this->hr_documents_management_model->reassign_i9_forms($user_type, $user_sid, $i9_data_to_update);
                } else {
                    $i9_data_to_insert = array();
                    $i9_data_to_insert['user_sid'] = $user_sid;
                    $i9_data_to_insert['user_type'] = $user_type;
                    $i9_data_to_insert['company_sid'] = $company_sid;
                    $i9_data_to_insert['sent_status'] = 1;
                    $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                    $i9_data_to_insert['status'] = 1;
                    $i9_data_to_insert["version"] = getSystemDate('Y');
                    //
                    $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                }
            }
            //
            if ($user_type == 'employee') {
                //
                $hf = message_header_footer(
                    $company_sid,
                    ucwords($data['session']['company_detail']['CompanyName'])
                );
                //
                $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
                $replacement_array = array();
                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                $replacement_array['baseurl'] = base_url();
                //
                $extra_user_info = array();
                $extra_user_info["user_sid"] = $user_sid;
                $extra_user_info["user_type"] = $user_type;
                //
                $this->load->model('Hr_documents_management_model', 'HRDMM');
                if ($this->HRDMM->isActiveUser($user_sid, $user_type)) {
                    //
                    if ($this->hr_documents_management_model->doSendEmail($user_sid, $user_type, "HREMS11")) {
                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $extra_user_info);
                    }
                }
            }
            //
            echo 'success';
        } else {
            redirect('login', 'refresh');
        }
    }

    public function print_eeoc_form($action, $sid, $type)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'EEOC Form';
            $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($sid, $type);
            $data['eeo_form_info'] = $eeo_form_info;
            $data['action'] = $action;
            $this->load->view('eeo/eeoc_print', $data);
        } else {
            redirect('login', "refresh");
        }
    }


    public function print_eeoc_form_history($action, $sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'EEOC Form';
            $eeo_form_info = $this->hr_documents_management_model->getUserVarificationHistoryDoc($sid, 'portal_eeo_form_history');
            $data['eeo_form_info'] = $eeo_form_info;
            $data['action'] = $action;
            $this->load->view('eeo/eeoc_print', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function required_documents($user_type = NULL, $user_sid = NULL, $eev_documents_sid = NULL, $form_type = 'uploaded')
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = $user_type;
            $data['title'] = 'Upload Required Documents';
            $user_info = array();
            $active_groups = array();
            $in_active_groups = array();
            $group_ids = array();
            $group_docs = array();
            $document_ids = array();

            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                        redirect('employee_management', 'refresh');
                    }
                    if (!$data['session']['employer_detail']['access_level_plus'] || $data['session']['employer_detail']['access_level'] != "Admin") {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                        redirect('employee_management', 'refresh');
                    }

                    $data = employee_right_nav($user_sid, $data);
                    $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                    $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                    $data['eeo_form_info'] = $eeo_form_info;
                    break;
                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                    }

                    $data = applicant_right_nav($user_sid, null);
                    $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                    $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);
                    $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                    $data['eeo_form_status'] = $eeo_form_status;
                    $data['eeo_form_info'] = $eeo_form_info;

                    $data_employer = array(
                        'sid' => $applicant_info['sid'],
                        'first_name' => $applicant_info['first_name'],
                        'last_name' => $applicant_info['last_name'],
                        'email' => $applicant_info['email'],
                        'Location_Address' => $applicant_info['address'],
                        'Location_City' => $applicant_info['city'],
                        'Location_Country' => $applicant_info['country'],
                        'Location_State' => $applicant_info['state'],
                        'Location_ZipCode' => $applicant_info['zipcode'],
                        'PhoneNumber' => $applicant_info['phone_number'],
                        'profile_picture' => $applicant_info['pictures'],
                        'user_type' => ucwords($user_type)
                    );

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $applicant_info['sid'];
                    break;
            }
            $data['user_sid'] = $user_sid;
            $data['left_navigation'] = $left_navigation;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $data['required_documents'] = $this->hr_documents_management_model->get_eev_required_document($user_sid, $eev_documents_sid, $form_type);
                if ($form_type == "uploaded") {
                    $data['form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded_by_id($eev_documents_sid);
                    $data['i9_form'] = null;
                    $data['w9_form'] = null;
                    $data['w4_form'] = null;
                } else {
                    $data['i9_form'] = $this->hr_documents_management_model->fetch_form('i9', $user_type, $user_sid);
                    $data['w9_form'] = $this->hr_documents_management_model->fetch_form('w9', $user_type, $user_sid);
                    $data['w4_form'] = $this->hr_documents_management_model->fetch_form('w4', $user_type, $user_sid);
                }

                $data['form_type'] = $form_type;
                if ($form_type == "w4_assigned") {
                    $data['FormName'] = "W4";
                } else if ($form_type == "w9_assigned") {
                    $data['FormName'] = "W9";
                } else if ($form_type == "i9_assigned") {
                    $data['FormName'] = "I9";
                }
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/required_documents_management');
                $this->load->view('main/footer');
            } else {
                $uploaded_document_original_name = $_FILES['document']['name'];
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                } else {
                    $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $uploaded_document_original_name), $employer_sid, AWS_S3_BUCKET_NAME);
                }

                if ($uploaded_document_s3_name != 'error') {
                    $data_to_insert['sid'] = $this->input->post('sid');
                    $data_to_insert['eev_documents_sid'] = $eev_documents_sid;
                    $data_to_insert['employee_sid'] = $user_sid;
                    $data_to_insert['document_name'] = $uploaded_document_original_name;
                    $data_to_insert['date_uploaded'] = date('Y-m-d H:i:s');
                    $data_to_insert['uploaded_by_sid'] = $employer_sid;
                    $data_to_insert['s3_filename'] = $uploaded_document_s3_name;
                    $data_to_insert['form_type'] = $form_type;
                    $this->hr_documents_management_model->insert_required_document($data_to_insert);
                    $this->session->set_flashdata('message', '<strong>Success:</strong> Document Uploaded Successful!');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                }

                redirect('hr_documents_management/required_documents' . '/' . $user_type . '/' . $user_sid . '/' . $eev_documents_sid . '/' . $form_type, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function view_eev_document($documents_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['employee'] = $data['session']['employer_detail'];

            $eev_document = $this->hr_documents_management_model->get_eev_uploaded_document($documents_sid);
            $page_title = '';

            if ($eev_document['document_type'] == 'w4') {
                $page_title = 'W4 Form';
            } else if ($eev_document['document_type'] == 'w9') {
                $page_title = 'W9 Form';
            } else if ($eev_document['document_type'] == 'i9') {
                $page_title = 'I9 Form';
            }

            $data['title'] = $page_title;
            $data['eev_document'] = $eev_document;

            $upload_document = $eev_document['s3_filename'];
            $file_name = explode(".", $upload_document);
            $document_name = $file_name[0];
            $document_extension = $file_name[1];

            $print_url = '';

            if ($document_extension == 'pdf') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
            } else if ($document_extension == 'doc') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
            } else if ($document_extension == 'docx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
            } else if ($document_extension == 'xls') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
            } else if ($document_extension == 'xlsx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
            } else if ($document_extension == 'csv') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';
            } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                $print_url = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
            }

            $data['print_url'] = $print_url;
            $data['download_url'] = base_url('hr_documents_management/download_upload_document/' . $eev_document['s3_filename']);
            $data['document_extension'] = $document_extension;
            $data['document_name'] = $eev_document['s3_filename'];

            $this->load->view('onboarding/on_boarding_header', $data);
            $this->load->view('hr_documents_management/eev_document');
            $this->load->view('onboarding/on_boarding_footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function documents_category_management()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $categories = $this->hr_documents_management_model->get_all_documents_category($company_sid, null, 'descending');
            if (!empty($categories)) {
                foreach ($categories as $key => $category) {
                    $document_status = $this->hr_documents_management_model->is_document_assign_2_category($category['sid']);
                    $categories[$key]['document_status'] = $document_status;
                }
            }

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $data['title'] = 'Category Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['categories'] = $categories;

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/documents_category_management');
                $this->load->view('main/footer');
            } else {
                $employees = $this->input->post('employees');
                $category_assign_sid = $this->input->post('category_sid');

                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['category_sid'] = $category_assign_sid;
                $data_to_insert['assigned_by_sid'] = $employer_sid;
                $data_to_insert['applicant_sid'] = 0;

                if (!empty($employees)) {
                    foreach ($employees as $key => $employee) {
                        $data_to_insert['employer_sid'] = $employee;
                        $is_category_assign = $this->hr_documents_management_model->check_category_already_assigned($company_sid, $employee, $category_assign_sid);

                        if ($is_category_assign == 0) {
                            $this->hr_documents_management_model->assign_document_category_2_empliyees($data_to_insert);
                        }
                    }
                }

                $this->session->set_flashdata('message', '<strong>Success:</strong> Documents Category Update Successfully!');
                redirect('hr_documents_management/documents_category_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add_edit_document_category_management($category_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Category Management';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['categories_count'] = $this->hr_documents_management_model->categories_count($company_sid);

            if ($category_sid != NULL) {
                if ($category_sid == PP_CATEGORY_SID) {
                    $this->session->set_flashdata('message', "<strong>Error:</strong> Access Denied!");
                    redirect('hr_documents_management/documents_category_management', 'refresh');
                }
                $category = $this->hr_documents_management_model->get_document_category($category_sid);
                $data['category'] = $category;
                $data['submit_button_text'] = 'Update';
                $data['perform_action'] = 'edit_document_category';
            } else {
                $data['submit_button_text'] = 'Save';
                $data['perform_action'] = 'add_document_category';
            }
            $this->form_validation->set_error_delimiters('<span style="color:red">', '</span>');
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            // $original_name = '';
            // $original_cat_obj = $this->hr_documents_management_model->get_document_category($category_sid);
            // if(isset($original_cat_obj['name']))
            //     $original_name = $original_cat_obj['name'];
            // if($this->input->post('name') != $original_name) {
            //     $is_unique =  '|is_unique[documents_category_management.name]';
            // } else {
            //     $is_unique =  '';
            // }
            $this->form_validation->set_rules(
                'name',
                'Category Name',
                'required|callback_checkCategoryName[' . ($category_sid) . ',' . ($company_sid) . ']',
                // 'required'.$is_unique,
                array(
                    'required'      => 'You have not provided %s.',
                    'checkCategoryName'     => 'This %s already exists.'
                )
            );

            if ($this->form_validation->run() == false) {
                if (validation_errors() != false) {
                    $category['name'] = $this->input->post('name');
                    $category['description'] = $this->input->post('description');
                    $category['status'] = $this->input->post('status');
                    $category['sort_order'] = $this->input->post('sort_order');
                    $data['category'] = $category;
                }
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/add_edit_document_category');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_document_category':
                        $category_name = $this->input->post('name');
                        $category_description = $this->input->post('description');
                        $category_status = $this->input->post('status');
                        $category_sort_order = $this->input->post('sort_order');
                        $ip_address = $this->input->post('ip_address');
                        $data_to_insert = array();
                        $new_history_data = array();
                        $category_description = htmlentities($category_description);

                        if (empty($category_sort_order)) {
                            $category_sort_order = 0;
                        }

                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['name'] = $category_name;
                        $data_to_insert['description'] = $category_description;
                        $data_to_insert['status'] = $category_status;
                        $data_to_insert['sort_order'] = $category_sort_order;
                        $data_to_insert['created_by_sid'] = $employer_sid;
                        $data_to_insert['ip_address'] = $ip_address;
                        $insert_id = $this->hr_documents_management_model->insert_category_record($data_to_insert);
                        // Tracking History For New Inserted Doc in new history table
                        $new_history_data['category_sid'] = $insert_id;
                        $new_history_data['company_sid'] = $company_sid;
                        $new_history_data['name'] = $category_name;
                        $new_history_data['description'] = $category_description;
                        $new_history_data['status'] = $category_status;
                        $new_history_data['sort_order'] = $category_sort_order;
                        $new_history_data['updated_by_sid'] = $employer_sid;
                        $new_history_data['ip_address'] = $ip_address;
                        $this->hr_documents_management_model->insert_category_history($new_history_data);
                        if ($this->input->is_ajax_request()) {
                            echo 'success';
                        } else {
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Category Created Successfully!');
                            redirect('hr_documents_management/documents_category_management', 'refresh');
                        }

                        break;
                    case 'edit_document_category':
                        $category_name = $this->input->post('name');
                        $category_description = $this->input->post('description');
                        $category_status = $this->input->post('status');
                        $category_sort_order = $this->input->post('sort_order');
                        $ip_address = $this->input->post('ip_address');
                        $data_to_update = array();
                        $new_history_data = array();
                        $category_description = htmlentities($category_description);

                        if (empty($category_sort_order)) {
                            $category_sort_order = 0;
                        }

                        $data_to_update['name'] = $category_name;
                        $data_to_update['description'] = $category_description;
                        $data_to_update['status'] = $category_status;
                        $data_to_update['sort_order'] = $category_sort_order;
                        $data_to_update['ip_address'] = $ip_address;
                        $data_to_update['updated_by_sid'] = $employer_sid;
                        $data_to_update['updated_date']  = date('Y-m-d');
                        //
                        $new_history_data = $category;
                        $new_history_data['category_sid'] = $category_sid;
                        $new_history_data['updated_by_sid'] = $category_status;
                        unset($new_history_data['sid']);
                        unset($new_history_data['created_by_sid']);
                        unset($new_history_data['created_date']);
                        $this->hr_documents_management_model->insert_category_history($new_history_data);
                        $this->hr_documents_management_model->update_document_category($category_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Category Updated Successfully!');
                        redirect('hr_documents_management/documents_category_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function checkCategoryName($list, $l)
    {
        $l = explode(',', $l);
        $categorySid = $l[0];
        $companySid = $l[1];
        return $this->hr_documents_management_model->checkCategoryName($categorySid, $companySid);
    }

    public function document_2_category($category_sid = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assign Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $documents = $this->hr_documents_management_model->get_all_documents($company_sid);
            $pre_assign_documents = $this->hr_documents_management_model->get_all_document_2_category($category_sid);
            $category = $this->hr_documents_management_model->get_document_category($category_sid);

            $assigned_documents = array();

            if (!empty($pre_assign_documents)) {
                foreach ($pre_assign_documents as $key => $pre_assign) {
                    array_push($assigned_documents, $pre_assign['document_sid']);
                }
            }

            $data['category'] = $category;
            $data['category_name'] = $category['name'];
            $data['assigned_documents'] = $assigned_documents;
            $data['documents'] = $documents;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management/document_2_category');
                $this->load->view('main/footer');
            } else {
                $assign_documents = $this->input->post('documents');

                $this->hr_documents_management_model->delete_document_2_category($category_sid);

                if (!empty($assign_documents)) {
                    foreach ($assign_documents as $key => $document_sid) {
                        $data_to_insert = array();
                        $data_to_insert['category_sid'] = $category_sid;
                        $data_to_insert['document_sid'] = $document_sid;
                        $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                    }
                }

                $this->session->set_flashdata('message', '<strong>Success:</strong> Documents Update Successfully!');
                redirect('hr_documents_management/documents_category_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_assign_category_2_applicant($category_sid, $user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;

            if ($user_type == 'employee') {
                $data_to_insert['employer_sid'] = $user_sid;
                $data_to_insert['applicant_sid'] = 0;
            } else if ($user_type == 'applicant') {
                $data_to_insert['employer_sid'] = 0;
                $data_to_insert['applicant_sid'] = $user_sid;
            }

            $data_to_insert['category_sid'] = $category_sid;
            $data_to_insert['assigned_by_sid'] = $employer_sid;
            $this->hr_documents_management_model->assign_document_category_2_empliyees($data_to_insert);
            echo 'success';
        } else {
            redirect('login', 'refresh');
        }
    }

    public function deactivate_document()
    {
        $document_sid = $_POST['document_sid'];
        //
        $is_manual = $this->hr_documents_management_model->checkDocumentIsManual($document_sid);
        //
        if ($is_manual) {
            $this->hr_documents_management_model->deleteManualDocument($document_sid);
        } else {
            $status_to_update = array();
            $status_to_update['status'] = 0;
            $status_to_update['archive'] = 1;
            $this->hr_documents_management_model->change_document_status($document_sid, $status_to_update);
        }
    }

    //
    function convert_document_to_payplan()
    {
        $r = array();
        $r['Status'] = FALSE;
        $r['Response'] = 'Invalid request made.';
        //
        if (!$this->session->userdata('logged_in')) $this->res($r);
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $document = array();
        // if($post['documentType'] == 'uploaded' || $post['documentType'] == 'generated'){
        // Fetch uploaded document
        $document = $this->hr_documents_management_model->getUploadedDocumentById($post['documentId']);
        // }
        //
        if (!sizeof($document)) {
            $r['Response'] = 'Unable to verify document.';
            $this->res($r);
        }
        //
        $j = $document;
        $j['company_sid'] = $company_sid;
        $j['employer_sid'] = $employer_sid;

        //
        $j['confidential_employees'] = $j['confidential_employees'] == null ? '' : $j['confidential_employees'];

        unset(
            $j['DocumentAssigmentId'],
            $j['DocumentAssignedId']
        );

        // Insert offer letter
        $insertId = $this->hr_documents_management_model->insertOfferLetter($j);
        // Convert document sids to offer letter sids
        if (!$insertId) {
            $r['Response'] = 'Something went wrong while converting Document to Pay Plan.';
            $this->res($r);
        }
        $this->hr_documents_management_model->updateAssignedDocumentId($insertId, $post['documentId']);
        // Create history
        $this->hr_documents_management_model->createConvertHistory(array(
            'document_sid' => $post['documentId'],
            'offer_letter_sid' => $insertId,
            'company_sid' => $company_sid,
            'employee_sid' => $employer_sid,
            'data' => json_encode($this->hr_documents_management_model->getDocumentById($post['documentId']))
        ));
        $this->hr_documents_management_model->removeDocument($post['documentId']);
        $r['Status'] = true;
        $r['Response'] = 'Document has been converted to Pay Plan.';
        $this->res($r);
    }

    //
    private function res($i)
    {
        header('Content-Type: application/json');
        echo json_encode($i);
        exit(0);
    }

    //
    private function convertDocumentToPayPlan()
    {
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        $post = $this->input->post(NULL, TRUE);
        //
        $r['Status'] = FALSE;
        $r['Response'] = 'Invalid request.';
        //
        $document = array();
        // Fetch uploaded document
        $document = $this->hr_documents_management_model->getUploadedDocumentById($post['document_sid']);
        //
        if (!sizeof($document)) {
            $r['Response'] = 'Unable to verify document.';
            $this->res($r);
        }
        //
        $j = $document;
        $j['company_sid'] = $company_sid;
        $j['employer_sid'] = $employer_sid;
        //
        unset(
            $j['DocumentAssigmentId'],
            $j['DocumentAssignedId']
        );
        // Insert offer letter
        $insertId = $this->hr_documents_management_model->insertOfferLetter($j);
        // Convert document sids to offer letter sids
        if (!$insertId) {
            $this->session->set_flashdata('message', 'Something went wrong while converting this document to Pay Plan.');
            redirect('hr_documents_management', 'refresh');
        }
        $this->hr_documents_management_model->updateAssignedDocumentId($insertId, $post['document_sid']);
        // Create history
        $this->hr_documents_management_model->createConvertHistory(array(
            'document_sid' => $post['document_sid'],
            'offer_letter_sid' => $insertId,
            'company_sid' => $company_sid,
            'employee_sid' => $employer_sid,
            'data' => json_encode($this->hr_documents_management_model->getDocumentById($post['document_sid']))
        ));
        $this->hr_documents_management_model->removeDocument($post['document_sid']);
        if (!$this->input->is_ajax_request()) {
            $this->session->set_flashdata('message', 'Document has been converted to Pay Plan.');
            redirect('hr_documents_management', 'refresh');
        } else {
            $r['Status'] = TRUE;
            $r['Response'] = 'Document converted to Offer letter.';
            $this->res($r);
        }
    }

    private function redirectHandler($uri, $type = 'auto')
    {
        if (headers_sent()) {
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                      <meta http-equiv = "refresh" content = "2; url = ' . (base_url($uri)) . '" />
            </head>
            </html>';
        } else {
            redirect($uri, $type);
        }
    }


    public function save_i9_section2()
    {
        if ($this->session->userdata('logged_in')) {

            $this->load->model('form_wi9_model');
            $data['session'] = $this->session->userdata('logged_in');
            $filler_sid = $data['session']['employer_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;

            $data['title'] = 'Form I-9';
            $data['employee'] = $data['session']['employer_detail'];

            $company_sid = $data['session']['company_detail']['sid'];

            $employer_access_level = $data['session']['employer_detail']['access_level'];
            $employer_details = $data['session']['employer_detail'];
            // Section 2,3 Data Array Starts
            $formpost = $this->input->post(NULL, TRUE);

            $insert_data = array();

            $user_sid = $employer_details['sid'];
            $user_type = 'employee';

            $signature = get_e_signature($company_sid, $user_sid, $user_type);

            if (!empty($signature)) {
                $reviewer_signature_base64 = $signature['signature_bas64_image'];
            }

            $insert_data['section2_last_name'] = $formpost['section2_last_name'];
            $insert_data['section2_first_name'] = $formpost['section2_first_name'];
            $insert_data['section2_middle_initial'] = $formpost['section2_middle_initial'];
            $insert_data['section2_citizenship'] = $formpost['section2_citizenship'];


            $insert_data['section2_lista_part1_document_title'] = $formpost['lista_part1_doc_select_input'] != 'input' ? $formpost['section2_lista_part1_document_title'] : $formpost['section2_lista_part1_document_title_text_val'];
            $insert_data['section2_lista_part1_issuing_authority'] = isset($formpost['section2_lista_part1_issuing_authority']) && $formpost['lista_part1_issuing_select_input'] != 'input' ? $formpost['section2_lista_part1_issuing_authority'] : $formpost['section2_lista_part1_issuing_authority_text_val'];
            $insert_data['section2_lista_part1_document_number'] = $formpost['section2_lista_part1_document_number'];
            $insert_data['section2_lista_part1_expiration_date'] = empty(checkDateFormate($formpost['section2_lista_part1_expiration_date'])) ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part1_expiration_date'])->format('Y-m-d H:i:s');
            $insert_data['section2_lista_part2_document_title'] = $formpost['lista_part2_doc_select_input'] != 'input' ? $formpost['section2_lista_part2_document_title'] : $formpost['section2_lista_part2_document_title_text_val'];
            $insert_data['section2_lista_part2_issuing_authority'] = isset($formpost['section2_lista_part2_issuing_authority']) && $formpost['lista_part2_issuing_select_input'] != 'input' ? $formpost['section2_lista_part2_issuing_authority'] : $formpost['section2_lista_part2_issuing_authority_text_val'];
            $insert_data['section2_lista_part2_document_number'] = $formpost['section2_lista_part2_document_number'];
            $insert_data['section2_lista_part2_expiration_date'] = empty(checkDateFormate($formpost['section2_lista_part2_expiration_date'])) ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part2_expiration_date'])->format('Y-m-d H:i:s');
            $insert_data['section2_lista_part3_document_title'] = $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_document_title'] : $formpost['section2_lista_part3_document_title_text_val'];
            $insert_data['section2_lista_part3_issuing_authority'] = isset($formpost['section2_lista_part3_issuing_authority']) && $formpost['lista_part3_doc_select_input'] != 'input' ? $formpost['section2_lista_part3_issuing_authority'] : $formpost['section2_lista_part3_issuing_authority_text_val'];
            $insert_data['section2_lista_part3_document_number'] = $formpost['section2_lista_part3_document_number'];
            $insert_data['section2_lista_part3_expiration_date'] = empty(checkDateFormate($formpost['section2_lista_part3_expiration_date'])) ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_lista_part3_expiration_date'])->format('Y-m-d H:i:s');
            $insert_data['section2_additional_information'] = $formpost['section2_additional_information'];

            $insert_data['section2_listb_document_title'] = $formpost['section2_listb_document_title'];
            $insert_data['listb_auth_select_input'] = isset($formpost['listb-auth-select-input']) ? $formpost['listb-auth-select-input'] : '';
            $insert_data['lista_part1_doc_select_input'] = isset($formpost['lista_part1_doc_select_input']) ? $formpost['lista_part1_doc_select_input'] : '';
            $insert_data['lista_part1_issuing_select_input'] = isset($formpost['lista_part1_issuing_select_input']) ? $formpost['lista_part1_issuing_select_input'] : '';
            $insert_data['lista_part2_doc_select_input'] = isset($formpost['lista_part2_doc_select_input']) ? $formpost['lista_part2_doc_select_input'] : '';
            $insert_data['lista_part2_issuing_select_input'] = isset($formpost['lista_part2_issuing_select_input']) ? $formpost['lista_part2_issuing_select_input'] : '';
            $insert_data['lista_part3_doc_select_input'] = isset($formpost['lista_part3_doc_select_input']) ? $formpost['lista_part3_doc_select_input'] : '';
            $insert_data['lista_part3_issuing_select_input'] = isset($formpost['lista_part3_issuing_select_input']) ? $formpost['lista_part3_issuing_select_input'] : '';

            $insert_data['section2_listb_issuing_authority'] = isset($formpost['section2_listb_issuing_authority']) && $formpost['listb-auth-select-input'] != 'input' ? $formpost['section2_listb_issuing_authority'] : $formpost['section2_listb_issuing_authority_text_val'];
            $insert_data['section2_listb_document_number'] = $formpost['section2_listb_document_number'];
            $insert_data['section2_listb_expiration_date'] = empty(checkDateFormate($formpost['section2_listb_expiration_date']))  ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listb_expiration_date'])->format('Y-m-d H:i:s');

            $insert_data['section2_listc_document_title'] = $formpost['section2_listc_document_title'];
            $insert_data['listc_auth_select_input'] = isset($formpost['listc-auth-select-input']) ? $formpost['listc-auth-select-input'] : '';
            $insert_data['section2_listc_dhs_extra_field'] = $formpost['section2_listc_dhs_extra_field'];
            $insert_data['section2_listc_issuing_authority'] = isset($formpost['section2_listc_issuing_authority']) && $formpost['listc-auth-select-input'] != 'input' ? $formpost['section2_listc_issuing_authority'] : $formpost['section2_listc_issuing_authority_text_val'];

            // $insert_data['section2_listc_issuing_authority'] = isset($formpost['section2_listc_issuing_authority']) ? $formpost['section2_listc_issuing_authority'] : '';

            $insert_data['section2_listc_document_number'] = $formpost['section2_listc_document_number'];
            $insert_data['section2_listc_expiration_date'] = empty(checkDateFormate($formpost['section2_listc_expiration_date']))  ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_listc_expiration_date'])->format('Y-m-d H:i:s');

            $insert_data['section2_firstday_of_emp_date'] = empty(checkDateFormate($formpost['section2_firstday_of_emp_date'])) ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_firstday_of_emp_date'])->format('Y-m-d H:i:s');
            $insert_data['section2_sig_emp_auth_rep'] = $reviewer_signature_base64;

            $insert_data['section2_today_date'] = empty(checkDateFormate($formpost['section2_today_date']))  ? null : DateTime::createFromFormat('m-d-Y', $formpost['section2_today_date'])->format('Y-m-d H:i:s');
            $insert_data['section2_title_of_emp'] = $formpost['section2_title_of_emp'];
            $insert_data['section2_last_name_of_emp'] = $formpost['section2_last_name_of_emp'];
            $insert_data['section2_first_name_of_emp'] = $formpost['section2_first_name_of_emp'];
            $insert_data['section2_emp_business_name'] = $formpost['section2_emp_business_name'];
            $insert_data['section2_emp_business_address'] = $formpost['section2_emp_business_address'];
            $insert_data['section2_city_town'] = $formpost['section2_city_town'];
            $insert_data['section2_state'] = $formpost['section2_state'];
            $insert_data['section2_zip_code'] = $formpost['section2_zip_code'];

            $insert_data['section3_pre_last_name'] = $formpost['section3_pre_last_name'];
            $insert_data['section3_pre_first_name'] = $formpost['section3_pre_first_name'];
            $insert_data['section3_pre_middle_initial'] = $formpost['section3_pre_middle_initial'];
            $insert_data['section3_last_name'] = $formpost['section3_last_name'];
            $insert_data['section3_first_name'] = $formpost['section3_first_name'];
            $insert_data['section3_middle_initial'] = $formpost['section3_middle_initial'];
            $insert_data['section3_rehire_date'] = empty(checkDateFormate($formpost['section3_rehire_date'])) || checkDateFormate($formpost['section3_rehire_date'])  ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_rehire_date'])->format('Y-m-d H:i:s');
            $insert_data['section3_document_title'] = $formpost['section3_document_title'];
            $insert_data['section3_document_number'] = $formpost['section3_document_number'];
            $insert_data['section3_expiration_date'] = empty(checkDateFormate($formpost['section3_expiration_date'])) || checkDateFormate($formpost['section3_expiration_date'])  ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_expiration_date'])->format('Y-m-d H:i:s');
            $insert_data['section3_emp_sign'] = $reviewer_signature_base64;
            $insert_data['section3_today_date'] = empty(checkDateFormate($formpost['section3_today_date'])) || checkDateFormate($formpost['section3_today_date']) ? null : DateTime::createFromFormat('m-d-Y', $formpost['section3_today_date'])->format('Y-m-d H:i:s');
            $insert_data['section3_name_of_emp'] = $formpost['section3_name_of_emp'];

            $insert_data['emp_app_sid'] = $employer_sid;
            $insert_data['employer_flag'] = 1;
            $insert_data['employer_filled_date'] = date('Y-m-d H:i:s');

            $this->form_wi9_model->update_form('i9', $formpost['user_type'], $formpost['user_sid'], $insert_data);
            $this->session->set_flashdata('message', '<strong>Success: </strong> I-9 Responded Successfully!');
            redirect($formpost['current-url'], 'refresh');
            // Section 2,3 Ends
        } else {
            redirect('login', "refresh");
        }
    }

    function get_document_content($document_sid, $request_type, $request_from)
    {
        $form_input_data = "";
        $is_iframe_preview = 1;
        // $document = $this->hr_documents_management_model->get_requested_authorized_content($document_sid, $request_from);
        // $requested_content = $this->hr_documents_management_model->get_requested_content($document_sid, $request_type, $request_from, 'preview');
        $document = $this->hr_documents_management_model->get_requested_generated_document_content($document_sid, $request_from);
        $requested_content = $this->hr_documents_management_model->get_requested_generated_document_content_body($document_sid, $request_type, $request_from, 'preview');
        $view = '<div class="panel panel-success"><div class="panel-heading"><strong>' . $document['document_title'] . '</strong></div><div class="panel-body" id="document_preview_div">' . html_entity_decode(html_entity_decode($requested_content)) . '</div></div>';

        if (!empty($document['form_input_data'])) {
            $form_input_data = unserialize($document['form_input_data']);
            $form_input_data = json_decode($form_input_data, true);
            $is_iframe_preview = 0;
        } else if (empty($document['submitted_description']) && empty($document['form_input_data'])) {
            $is_iframe_preview = 0;
        }


        $return_data = array();
        if (!empty($requested_content)) {
            $return_data['document_view']       = $view;
            $return_data['form_input_data']     = $form_input_data;
            $return_data['is_iframe_preview']   = $is_iframe_preview;
            $return_data['requested_content']   = $requested_content;
            $return_data['requested_content']   = preg_match('/(&.+;)/i', $requested_content) ? html_entity_decode($requested_content) : $requested_content;


            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    function perform_action_on_document_content($document_sid, $request_type, $request_from, $perform_action, $letter_request = NULL)
    {
        $form_input_data = "NULL";
        $is_iframe_preview = 1;

        // $document = $this->hr_documents_management_model->get_requested_authorized_content($document_sid, $request_from);
        // $requested_content = $this->hr_documents_management_model->get_requested_content($document_sid, $request_type, $request_from, 'P&D');
        $document = $this->hr_documents_management_model->get_requested_generated_document_content($document_sid, $request_from);
        $requested_content = $this->hr_documents_management_model->get_requested_generated_document_content_body($document_sid, $request_type, $request_from, 'P&D');
        $file_name = $this->hr_documents_management_model->get_document_title($document_sid, $request_type, $request_from);

        if ($letter_request == 1) {
            $requested_content = $document['submitted_description'];
        } else if (!empty($document['form_input_data']) && $request_type == 'submitted') {
            if (!empty(unserialize($document['form_input_data']))) {
                $is_iframe_preview = 0;
            }

            if (!empty($document['authorized_signature'])) {
                $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
            } else {
                $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            }
            if (!empty($document['authorized_signature_date'])) {
                $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
            } else {
                $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            }

            $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
            $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
            $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

            $document["signature"] = $signature_bas64_image;
            $document["sign_date"] = $sign_date;
            $document["authorized_signature"] = $authorized_signature_image;
            $document["authorized_signature_date"] = $authorized_signature_date;
            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);

            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = $document_content;

            $form_input_data = unserialize($document['form_input_data']);
            $form_input_data = json_encode(json_decode($form_input_data, true));
        } else {
            if ($request_type == 'assigned') {
                // if (empty($document['submitted_description']) && empty($document['form_input_data'])) {    
                $is_iframe_preview = 0;
            }

            $form_input_data = json_encode(json_decode('assigned'));
            //
            $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            $signature_bas64_image = '------------------------------(Signature Required)';
            $init_signature_bas64_image = '------------------------------(Signature Initial Required)';
            $sign_date = '------------------------------(Sign Date Required)';
            //
            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
            //
            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = html_entity_decode($document_content);
        }

        

        $data = array();
        $data['file_name'] = $file_name;
        $data['document'] = $document;
        $data['request_type'] = $request_type;
        $data['document_contant'] = $requested_content;
        $data['perform_action'] = $perform_action;
        $data['form_input_data'] = $form_input_data;
        $data['is_iframe_preview'] = $is_iframe_preview;
        $data['is_hybrid'] = "no";

        if ($document["document_type"] == "hybrid_document") {
            $document_path = "";
            if ($request_type == 'submitted') {
                $document_path = $document["uploaded_file"];
            } else {
                $document_path = $request_from == "company_document" ? $document["uploaded_document_s3_name"] : $document["document_s3_name"];
            }
            //

            $data['document_path'] = base_url("hr_documents_management/download_upload_document") . '/' . $document_path;
            $data['perform_action'] = "download";
            $data['is_hybrid'] = "yes";
        }

        if ($document["fillable_document_slug"]) {
            $postfix = $request_type == "assigned" ? "print_assigned" : "print";
            return $this->load->view("v1/documents/fillable/{$document["fillable_document_slug"]}_{$postfix}", $data);
        }

        $this->load->view('hr_documents_management/new_generated_document_action_page', $data);
    }

    function print_s3_image($image_path = NULL)
    {
        $data = array();
        $data['image_path'] = AWS_S3_BUCKET_URL . $image_path;
        $this->load->view('hr_documents_management/generate_s3_image_preview', $data);
    }

    function getbase64()
    {
        //get url from input
        $url = $this->input->get('url');
        //make a curl call to fetch content
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $data = curl_exec($ch);
        curl_close($ch);
        //get mime type
        $mime_type = getMimeType($url);
        $str64 = base64_encode($data);

        print_r(json_encode(array('type' => $mime_type, 'string' => $str64)));
    }

    public function automaticAssignDocumentsCronJob()
    {
        $allCompanies = $this->hr_documents_management_model->get_all_companies();
        foreach ($allCompanies as $company) {
            $assignable_documents = $this->hr_documents_management_model->get_company_all_documents($company['sid']);
            foreach ($assignable_documents as $doc) {
                $this->hr_documents_management_model->checkAndAssignDoc($doc, $company['sid'], $company['CompanyName']);
            }
        }
    }

    function get_authorized_document_content($document_sid, $company_sid, $user_sid, $user_type)
    {
        $document = $this->hr_documents_management_model->get_requested_authorized_content($document_sid);

        if (!empty($document['authorized_signature'])) {
            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
        }

        if (!empty($document['authorized_signature_date'])) {
            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
        }

        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);


        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid'], 1);
        $document['document_description'] = $document_content;

        $form_input_data = unserialize($document['form_input_data']);
        $form_input_data = json_decode($form_input_data, true);

        $view = '<div class="panel panel-success"><div class="panel-heading"><strong>' . $document['document_title'] . '</strong></div><div class="panel-body">' . html_entity_decode($document['document_description']) . '</div></div>';

        $return_data = array();
        if (!empty($document)) {
            $return_data['document_title']  = $document['document_title'];
            $return_data['form_input_data'] = $form_input_data;
            $return_data['document_view']   = $view;
            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    function perform_action_on_authorized_document($document_sid, $company_sid, $user_sid, $user_type, $action)
    {
        $document = $this->hr_documents_management_model->get_requested_authorized_content($document_sid);

        if (!empty($document['authorized_signature'])) {
            $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
        } else {
            $authorized_signature_image = '------------------------------';
        }

        if (!empty($document['authorized_signature_date'])) {
            $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
        } else {
            $authorized_signature_date = 'Authorize Sign Date :------/-------/----------------';
        }

        $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
        $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
        $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

        $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
        $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
        $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
        $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
        $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);

        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid'], 1);
        $document['document_description'] = $document_content;

        $form_input_data = unserialize($document['form_input_data']);
        $form_input_data = json_encode(json_decode($form_input_data, true));

        $data['document'] = $document;
        $data['perform_action'] = $action;
        $data['file_name'] = str_replace(' ', '_', $document['document_title']);
        $data['form_input_data'] = $form_input_data;

        $this->load->view('hr_documents_management/authorized_document_preview', $data);
    }


    public function hybrid_document(
        $type = 'add',
        $id = FALSE
    ) {
        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);

        //
        if (isset($_POST) && sizeof($_POST) && $type == 'add') {
            //
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            //
            $post = $this->input->post(NULL, TRUE);
            $document_title = $this->input->post('document_title');
            $document_description = $this->input->post('document_description', false);
            $document_description = htmlentities($document_description);

            //
            $video_required = $this->input->post('video_source');

            $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);

            $uploaded_document_original_name = $document_title;

            if (isset($_FILES['document']['name'])) {
                $uploaded_document_original_name = $_FILES['document']['name'];
            }


            $file_info = pathinfo($uploaded_document_original_name);
            $data_to_insert = array();

            $data_to_insert['isdoctolibrary'] = $this->input->post('isdoctolibrary') ? $this->input->post('isdoctolibrary') : 0;
            $data_to_insert['visible_to_document_center'] = 0;

            if (isset($file_info['extension'])) {
                $data_to_insert['uploaded_document_extension'] = $file_info['extension'];
                $new_history_data['uploaded_document_extension'] = $uploaded_document_s3_name;
            }

            if ($uploaded_document_s3_name != 'error') {
                $data_to_insert['uploaded_document_original_name'] = $uploaded_document_original_name;
                $data_to_insert['uploaded_document_s3_name'] = $uploaded_document_s3_name;

                $new_history_data['uploaded_document_original_name'] = $uploaded_document_original_name;
                $new_history_data['uploaded_document_s3_name'] = $uploaded_document_s3_name;
            }

            //
            $data_to_insert['isdoctohandbook'] = $this->input->post('isdoctohandbook') ? $this->input->post('isdoctohandbook') : 0;
            $data_to_insert['is_required'] = $this->input->post('isRequired') ? $this->input->post('isRequired') : 0;


            // $data_to_insert = array();
            $new_history_data = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['employer_sid'] = $employer_sid;
            $data_to_insert['document_title'] = $document_title;
            $data_to_insert['document_description'] = $document_description;
            $data_to_insert['document_type'] = 'hybrid_document';
            if (!empty($this->input->post('sort_order')))
                $data_to_insert['sort_order'] = $this->input->post('sort_order');
            else
                $data_to_insert['sort_order'] = 1;
            $data_to_insert['unique_key'] = generateRandomString(32);
            $data_to_insert['onboarding'] = $this->input->post('onboarding');
            $data_to_insert['download_required'] = $this->input->post('download_required');
            $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
            $data_to_insert['signature_required'] = $this->input->post('signature_required');
            $data_to_insert['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
            if ($data_to_insert == 'days') {
                $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
            } else {
                $data_to_insert['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
            }
            $video_required = $this->input->post('video_source');

            if ($video_required != 'not_required') {
                $video_source = $this->input->post('video_source');

                if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('hr_documents_management/generate_new_document', 'refresh');
                    }

                    $video_url = $file_name;
                } else {
                    $video_url = $this->input->post('yt_vm_video_url');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_url = $url_prams['v'];
                        } else {
                            $video_url = '';
                        }
                    } else {
                        $video_url = $this->vimeo_get_id($video_url);
                    }
                }

                $data_to_insert['video_source'] = $video_source;
                $data_to_insert['video_url'] = $video_url;
                $data_to_insert['video_required'] = 1;
                $new_history_data['video_required'] = 1;
                $new_history_data['video_source'] = $video_source;
                $new_history_data['video_url'] = $video_url;
            } else {
                $data_to_insert['video_required'] = 0;
                $new_history_data['video_required'] = 0;
            }

            if (!isset($_POST['categories']) && isset($_POST['visible_to_payroll'])) {
                $data_to_insert['visible_to_payroll'] = 1;
            } else {
                $data_to_insert['visible_to_payroll'] = 0;
            }

            $post = $this->input->post(NULL, true);
            //
            $data_to_insert['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
            $data_to_insert['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
            $data_to_insert['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
            $data_to_insert['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;

            // Assign & Send document
            // Set
            $aType = $this->input->post('assignAndSendDocument', true);
            $aDate = $this->input->post('assignAndSendCustomDate', true);
            $aDay = $this->input->post('assignAndSendCustomDay', true);
            $aTime = $this->input->post('assignAndSendCustomTime', true);
            $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
            //
            $data_to_insert['assign_type'] = $aType;
            $data_to_insert['assign_date'] = $aDate;
            $data_to_insert['assign_time'] = $aTime;
            //
            if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_insert['assign_type'] = 'none';
            //
            if (empty($aDate) && empty($aDay)) $data_to_insert['assign_date'] = null;
            if (empty($aTime)) $data_to_insert['assign_time'] = null;
            //
            if ($aType == 'weekly' && !empty($aDay)) $data_to_insert['assign_date'] = $aDay;
            //
            if ($aEmployees && count($aEmployees)) {
                //
                if (in_array('-1', $aEmployees)) $data_to_insert['assigned_employee_list'] = 'all';
                else $data_to_insert['assigned_employee_list'] = json_encode($aEmployees);
            }

            //
            $managersList = $this->input->post('managersList', true);
            if ($managersList && sizeof($managersList)) {
                $data_to_insert['managers_list'] = implode(',', $managersList);
            }

            if (!empty($this->input->post('document_url'))) {
                $data_to_insert['uploaded_document_original_name'] = $this->input->post('document_name');
                $data_to_insert['uploaded_document_s3_name'] = $this->input->post('document_url');
            }
            // Document Settings - Confidential
            $data_to_insert['is_confidential'] = $this->input->post('setting_is_confidential', true) && $this->input->post('setting_is_confidential', true) == 'on' ? 1 : 0;
            //
            $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
            //
            $data_to_insert['confidential_employees'] = NULL;
            //
            if ($post['confidentialSelectedEmployees']) {
                $data_to_insert['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
            }

            // Approval Flow
            $data_to_insert['has_approval_flow'] = 0;
            $data_to_insert['document_approval_note'] = $data_to_insert['document_approval_employees'] = '';
            // 
            if ($post['has_approval_flow'] == 'on') {
                $data_to_insert['has_approval_flow'] = 1;
                $data_to_insert['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                $data_to_insert['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
            }
            //
            $insert_id = $this->hr_documents_management_model->insert_document_record($data_to_insert);

            if (isset($_POST['document_group_assignment'])) {
                $document_group_assignment = $this->input->post('document_group_assignment');

                if (!empty($document_group_assignment)) {
                    foreach ($document_group_assignment as $key => $group_sid) {
                        $data_to_insert = array();
                        $data_to_insert['group_sid'] = $group_sid;
                        $data_to_insert['document_sid'] = $insert_id;
                        $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                    }
                }
            }
            if (isset($_POST['categories'])) {
                $document_category_assignment = $this->input->post('categories');

                if (!empty($document_category_assignment)) {
                    foreach ($document_category_assignment as $key => $category_sid) {
                        $data_to_insert = array();
                        $data_to_insert['category_sid'] = $category_sid;
                        $data_to_insert['document_sid'] = $insert_id;
                        $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                    }
                }
            }

            $authorized_signature_required = $this->input->post('auth_sign_sid');

            if ($authorized_signature_required > 0) {
                $update_authorized_signature = array();
                $update_authorized_signature['document_sid'] = $insert_id;
                $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
            }

            // Tracking History For New Inserted Doc in new history table
            $new_history_data['documents_management_sid'] = $insert_id;
            $new_history_data['company_sid'] = $company_sid;
            $new_history_data['employer_sid'] = $employer_sid;
            $new_history_data['document_title'] = $document_title;
            $new_history_data['document_description'] = $document_description;
            $new_history_data['document_type'] = 'hybrid_document';
            $new_history_data['date_created'] = date('Y-m-d H:i:s');
            $new_history_data['update_by_sid'] = $employer_sid;
            $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
            $new_history_data['download_required'] = $this->input->post('download_required');
            $new_history_data['signature_required'] = $this->input->post('signature_required');
            $new_history_data['automatic_assign_in'] = !empty($this->input->post('assign-in')) ? $this->input->post('assign-in') : 0;
            $this->hr_documents_management_model->insert_document_management_history($new_history_data);
            $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Generated Successfully!');
            redirect('hr_documents_management', 'refresh');
        }

        //
        if (isset($_POST) && sizeof($_POST) && $type == 'edit') {
            //
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            //
            $post = $this->input->post(NULL, TRUE);
            $document_name = $this->input->post('document_title');
            $document_description = $this->input->post('document_description');
            $video_required = $this->input->post('video_source');
            $document_description = htmlentities($document_description);

            $sid = $id;
            // $action_required = $this->input->post('action_required');
            $data_to_update = array();
            $data_to_update['isdoctolibrary'] = $this->input->post('isdoctolibrary') ? $this->input->post('isdoctolibrary') : 0;
            $data_to_update['visible_to_document_center'] = 0;

            if (isset($_FILES['document']['name']) && !empty($_FILES['document']['name'])) {
                $s3_file_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);
                $original_name = $_FILES['document']['name'];

                if ($s3_file_name != 'error') {
                    $data_to_update['uploaded_document_original_name'] = $original_name;
                    $data_to_update['uploaded_document_s3_name'] = $s3_file_name;
                }

                $file_info = pathinfo($original_name);

                if (isset($file_info['extension'])) {
                    $data_to_update['uploaded_document_extension'] = $file_info['extension'];
                }
            }

            //
            $data_to_update['isdoctohandbook'] = $this->input->post('isdoctohandbook') ? $this->input->post('isdoctohandbook') : 0;
            $data_to_update['is_required'] = $this->input->post('isRequired') ? $this->input->post('isRequired') : 0;

            if ($video_required != 'not_required') {
                $video_source = $this->input->post('video_source');

                if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {

                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {

                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect('hr_documents_management/edit_hr_document', 'refresh');
                    }

                    $video_url = $file_name;
                } else {
                    $video_url = $this->input->post('yt_vm_video_url');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_url = $url_prams['v'];
                        } else {
                            $video_url = '';
                        }
                    } else {
                        $video_url = $this->vimeo_get_id($video_url);
                    }
                }
                if (!empty($video_url)) {
                    $data_to_update['video_source'] = $video_source;
                    $data_to_update['video_url'] = $video_url;
                    $data_to_update['video_required'] = 1;
                }
            } else {
                $data_to_update['video_required'] = 0;
            }

            if (isset($_POST['visible_to_payroll'])) {
                $data_to_update['visible_to_payroll'] = 1;
            } else {
                $data_to_update['visible_to_payroll'] = 0;
            }

            $post = $this->input->post(NULL, true);
            //
            $data_to_update['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
            $data_to_update['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
            $data_to_update['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
            $data_to_update['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;

            $authorized_signature_required = $this->input->post('auth_sign_sid');

            if ($authorized_signature_required > 0) {
                $update_authorized_signature = array();
                $update_authorized_signature['document_sid'] = $sid;
                $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
            }

            // Tracking History For New Inserted Doc in new history table
            $new_history_data = array();
            $new_history_data = $this->hr_documents_management_model->get_hr_document_details($company_sid, $sid);
            $new_history_data['documents_management_sid'] = $sid;
            $new_history_data['update_by_sid'] = $employer_sid;
            unset($new_history_data['sid']);
            $this->hr_documents_management_model->insert_document_management_history($new_history_data);

            $data_to_update['document_title'] = $document_name;
            $data_to_update['document_description'] = $document_description;
            $data_to_update['onboarding'] = $this->input->post('onboarding');
            $data_to_update['download_required'] = $this->input->post('download_required');
            $data_to_update['acknowledgment_required'] = $this->input->post('acknowledgment_required');
            $data_to_update['signature_required'] = $this->input->post('signature_required');
            $data_to_update['sort_order'] = $this->input->post('sort_order');
            // $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in')) ? $this->input->post('assign-in') : 0;
            $data_to_update['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
            if ($data_to_update['automatic_assign_type'] == 'days') {
                $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in-days')) ? $this->input->post('assign-in-days') : 0;
            } else {
                $data_to_update['automatic_assign_in'] = !empty($this->input->post('assign-in-months')) ? $this->input->post('assign-in-months') : 0;
            }

            // Assign & Send document
            // Set
            $aType = $this->input->post('assignAndSendDocument', true);
            $aDate = $this->input->post('assignAndSendCustomDate', true);
            $aDay = $this->input->post('assignAndSendCustomDay', true);
            $aTime = $this->input->post('assignAndSendCustomTime', true);
            $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
            //
            $data_to_update['assign_type'] = $aType;
            $data_to_update['assign_date'] = $aDate;
            $data_to_update['assign_time'] = $aTime;
            //
            if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_update['assign_type'] = 'none';
            //
            if (empty($aDate) && empty($aDay)) $data_to_update['assign_date'] = null;
            if (empty($aTime)) $data_to_update['assign_time'] = null;
            //
            if ($aType == 'weekly' && !empty($aDay)) $data_to_update['assign_date'] = $aDay;
            if ($aType == 'weekly' && empty($aDay)) $data_to_update['assign_date'] = null;
            //
            if ($aEmployees && count($aEmployees)) {
                //
                if (in_array('-1', $aEmployees)) $data_to_update['assigned_employee_list'] = 'all';
                else $data_to_update['assigned_employee_list'] = json_encode($aEmployees);
            }

            //
            $managersList = $this->input->post('managersList', true);
            if ($managersList && sizeof($managersList)) {
                $data_to_update['managers_list'] = implode(',', $managersList);
            }

            if (!empty($this->input->post('document_url'))) {
                $data_to_update['uploaded_document_original_name'] = $this->input->post('document_name');
                $data_to_update['uploaded_document_s3_name'] = $this->input->post('document_url');
            }
            // Document Settings - Confidential
            $data_to_update['is_confidential'] = $this->input->post('setting_is_confidential', true) && $this->input->post('setting_is_confidential', true) == 'on' ? 1 : 0;
            //
            // Approval Flow
            $data_to_update['has_approval_flow'] = 0;
            $data_to_update['document_approval_note'] = $data_to_update['document_approval_employees'] = '';
            // 
            if ($post['has_approval_flow'] == 'on') {
                $data_to_update['has_approval_flow'] = 1;
                $data_to_update['document_approval_employees'] = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                $data_to_update['document_approval_note'] = isset($_POST['approvers_note']) && !empty($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
            }
            //
            $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
            //
            $data_to_update['confidential_employees'] = NULL;
            //
            if ($post['confidentialSelectedEmployees']) {
                $data_to_update['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
            }

            $this->hr_documents_management_model->update_documents($sid, $data_to_update, 'documents_management');
            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Info Successfully Updated!');
            $this->hr_documents_management_model->delete_group_2_document($sid);

            if (isset($_POST['document_group_assignment'])) {
                $document_group_assignment = $this->input->post('document_group_assignment');

                if (!empty($document_group_assignment)) {
                    foreach ($document_group_assignment as $key => $group_sid) {
                        $data_to_insert = array();
                        $data_to_insert['group_sid'] = $group_sid;
                        $data_to_insert['document_sid'] = $sid;
                        $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                    }
                }
            }

            $this->hr_documents_management_model->delete_category_2_document($sid);
            if (isset($_POST['categories'])) {
                $document_category_assignment = $this->input->post('categories');

                if (!empty($document_category_assignment)) {
                    foreach ($document_category_assignment as $key => $category_sid) {
                        $data_to_insert = array();
                        $data_to_insert['category_sid'] = $category_sid;
                        $data_to_insert['document_sid'] = $sid;
                        $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                    }
                }
            }

            // Check for pay plan
            if ($this->input->post('to_pay_plan') == 'yes') {
                $this->convertDocumentToPayPlan();
                exit(0);
            }

            redirect('hr_documents_management', 'refresh');
        }

        //
        check_access_permissions($data['security_details'], 'manage_ems', 'add_edit_upload_generate_document'); // no need to check in this Module as Dashboard will be available to all
        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        //
        $data['title'] = 'Hybrid Document';
        //
        $data['pre_assigned_groups'] = array();
        //
        $data['document_groups'] = $this->hr_documents_management_model->getAllGroups($data['company_sid'], 1);
        $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($data['company_sid'], 1);
        // Get departments & teams
        $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
        $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
        //
        $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(
            $data['company_sid'],
            $data['employer_sid']
        );
        //
        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/hybrid/' . ($type) . '');
        $this->load->view('main/footer');
    }


    private $res = array(
        'Status' => FALSE,
        'Response' => 'Invalid response'
    );

    //
    function handler()
    {
        //
        $post = $this->input->post();
        //
        $company_sid = isset($post['companySid']) ? $post['companySid'] : $this->session->userdata('logged_in')['company_detail']['sid'];
        $employer_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];
        $company_name =  isset($post['companyName']) ? $post['companyName'] : $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        //
        switch ($post['action']) {
            case 'bulk_assign':
                $document_type = 'hybrid_document';
                $document_sid = $this->input->post('documentSid');
                $select_employees = $this->input->post('employees');
                $user_type = 'employee';

                $authorized_signature_required = $this->input->post('auth_sign_sid');

                if ($authorized_signature_required > 0) {
                    $update_authorized_signature = array();
                    $update_authorized_signature['document_sid'] = $document_sid;
                    $this->hr_documents_management_model->update_authorized_signature($authorized_signature_required, $update_authorized_signature);
                }

                // 
                if ($post['assign_type'] == 'department') {
                    // Fetch all employees belong to selected department
                    $select_employees = $this->hr_documents_management_model->getEmployeesFromDepartment(
                        $post['departments'],
                        $company_sid
                    );
                } else if ($post['assign_type'] == 'team') {
                    // Fetch all employees belong to selected department
                    $select_employees = $this->hr_documents_management_model->getEmployeesFromTeams(
                        $post['teams'],
                        $company_sid
                    );
                } else {
                    $select_employees = $this->hr_documents_management_model->getEmployees(
                        $post['employees'],
                        $company_sid
                    );
                }
                //
                $doSendEmails = $this->input->post('sendEmails', true);
                //
                if ($doSendEmails != 'yes') {
                    //
                    $hf = message_header_footer(
                        $company_sid,
                        $company_name
                    );
                }
                //
                foreach ($select_employees as $emp) {
                    $check_exist = $this->hr_documents_management_model->check_assigned_document($document_sid, $emp, $user_type);
                    if (!empty($check_exist)) {
                        $assignment_sid = $check_exist[0]['sid'];
                        $assigned_document = $this->hr_documents_management_model->get_assigned_document_details($company_sid, $assignment_sid);
                        unset($assigned_document['sid']);
                        $assigned_document['doc_sid'] = $assignment_sid;
                        $this->hr_documents_management_model->insert_documents_assignment_record_history($assigned_document);

                        $data_to_update = array();
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                        $data_to_update['company_sid'] = $company_sid;
                        $data_to_update['assigned_date'] = date('Y-m-d H:i:s');
                        $data_to_update['assigned_by'] = $employer_sid;
                        $data_to_update['user_type'] = $user_type;
                        $data_to_update['user_sid'] = $emp;
                        $data_to_update['document_type'] = $document_type;
                        $data_to_update['document_sid'] = $document_sid;
                        $data_to_update['status'] = 1;
                        $data_to_update['document_original_name'] = $document['uploaded_document_original_name'];
                        $data_to_update['document_extension'] = $document['uploaded_document_extension'];
                        $data_to_update['document_s3_name'] = $document['uploaded_document_s3_name'];
                        $data_to_update['document_title'] = $document['document_title'];
                        $data_to_update['document_description'] = htmlentities($this->input->post('description'));
                        $data_to_update['acknowledged'] = NULL;
                        $data_to_update['acknowledged_date'] = NULL;
                        $data_to_update['downloaded'] = NULL;
                        $data_to_update['downloaded_date'] = NULL;
                        $data_to_update['uploaded'] = NULL;
                        $data_to_update['uploaded_date'] = NULL;
                        $data_to_update['uploaded_file'] = NULL;
                        $data_to_update['signature_timestamp'] = NULL;
                        $data_to_update['signature'] = NULL;
                        $data_to_update['signature_email'] = NULL;
                        $data_to_update['signature_ip'] = NULL;
                        $data_to_update['user_consent'] = 0;
                        $data_to_update['submitted_description'] = NULL;
                        $data_to_update['signature_base64'] = NULL;
                        $data_to_update['signature_initial'] = NULL;
                        $data_to_update['is_required'] = $document['is_required'];
                        $this->hr_documents_management_model->update_documents($assignment_sid, $data_to_update, 'documents_assigned');
                    } else {
                        $document = $this->hr_documents_management_model->get_hr_document_details($company_sid, $document_sid);
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                        $data_to_insert['assigned_by'] = $employer_sid;
                        $data_to_insert['user_type'] = $user_type;
                        $data_to_insert['user_sid'] = $emp;
                        $data_to_insert['document_type'] = $document_type;
                        $data_to_insert['document_sid'] = $document_sid;
                        $data_to_insert['status'] = 1;
                        $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                        $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                        $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                        $data_to_insert['document_title'] = $document['document_title'];
                        $data_to_insert['document_description'] = htmlentities($this->input->post('description'));
                        $data_to_insert['is_required'] = $document['is_required'];
                        $data_to_insert['fillable_document_slug'] = $document['fillable_document_slug'];
                        //
                        $data_to_insert['isdoctohandbook'] = $document['isdoctohandbook'];

                        $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                    }

                    //
                    if ($doSendEmails == 'no') continue;
                    //
                    $user_info = array();
                    switch ($user_type) {
                        case 'employee':
                            $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $emp);
                            //Send Email and SMS
                            $replacement_array = array();
                            $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                            $replacement_array['company_name'] = ucwords($company_name);
                            $replacement_array['username'] = $replacement_array['contact-name'];
                            $replacement_array['firstname'] = $user_info['first_name'];
                            $replacement_array['lastname'] = $user_info['last_name'];
                            $replacement_array['first_name'] = $user_info['first_name'];
                            $replacement_array['last_name'] = $user_info['last_name'];
                            $replacement_array['baseurl'] = base_url();
                            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                            //SMS Start
                            $company_sms_notification_status = get_company_sms_status($this, $company_sid);

                            if (empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($user_info['document_sent_on'])))) {

                                if ($company_sms_notification_status) {
                                    $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                    $sms_notify = 0;
                                    if (strpos($notify_by['notified_by'], 'sms') !== false) {
                                        $contact_no = $notify_by['PhoneNumber'];
                                        $sms_notify = 1;
                                    }
                                    if ($sms_notify) {
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this, $company_sid, 'hr_document_notification');
                                        $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                                        sendSMS(
                                            $contact_no,
                                            $sms_body,
                                            trim(ucwords(strtolower($replacement_array['company_name']))),
                                            $user_info['email'],
                                            $this,
                                            $sms_notify,
                                            $company_sid
                                        );
                                    }
                                }
                                //
                                $user_extra_info = array();
                                $user_extra_info['user_sid'] = $emp;
                                $user_extra_info['user_type'] = $user_type;
                                //
                                $this->load->model('Hr_documents_management_model', 'HRDMM');
                                if ($this->HRDMM->isActiveUser($emp, $user_type)) {
                                    //
                                    if ($this->hr_documents_management_model->doSendEmail($emp, $user_type, "HREMS12")) {
                                        //
                                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                    }
                                }
                            }
                            break;
                        case 'applicant':
                            $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $emp);
                            break;
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = '<strong>Success:</strong> Document Successfully Bulk Assigned!';
                break;

                // 
            case "get_documents":
                //
                $documents = $this->hr_documents_management_model->getGeneralAssignedDocuments(
                    $post['userSid'],
                    $post['userType'],
                    $company_sid,
                    isset($post['type']) ? $post['type'] : 'all'
                );

                //
                foreach ($documents as $key => $val) {
                    if ($val['assigned_by'] != 0 && $val['assigned_by'] != 0) {
                        $documents[$key]['assigned_by_name'] = "<br>Assigned By: " . getUserNameBySID($val['assigned_by']);
                    } else {
                        $documents[$key]['assigned_by_name'] = '';
                    }
                }

                //
                $this->res['Status'] = TRUE;
                $this->res['Data'] = $documents;
                $this->res['Response'] = 'Proceed';
                $this->resp();
                break;

                // 
            case "get_general_document_view":
                //
                switch ($post['documentType']) {
                    case "dependents":
                        //
                        $this->load->model('dependents_model');
                        //
                        $data = $this->dependents_model->get_dependant_info($post['userType'], $post['userSid']);
                        //
                        if (count($data)) {
                            $data_countries = db_get_active_countries();
                            //
                            $d = [];

                            foreach ($data_countries as $value) {
                                $states = db_get_active_states($value['sid']);
                                //
                                foreach ($states as $state) {
                                    //
                                    if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                        'Name' => $value['country_name'],
                                        'States' => []
                                    ];
                                    //
                                    $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                                }
                            }
                            //
                            $this->res['template'] = $this->load->view('hr_documents_management/templates/dependents', ['data' => $data, 'cs' => $d], true);
                            $this->res['Status'] = TRUE;
                            $this->res['Response'] = 'Proceed';
                            $this->resp();
                        }
                        break;
                        //
                    case "emergency_contacts":
                        //
                        $this->load->model('emergency_contacts_model');
                        //
                        $data = $this->emergency_contacts_model->get_emergency_contacts($post['userType'], $post['userSid']);
                        //
                        if (count($data)) {
                            $data_countries = db_get_active_countries();
                            //
                            $d = [];

                            foreach ($data_countries as $value) {
                                $states = db_get_active_states($value['sid']);
                                //
                                foreach ($states as $state) {
                                    //
                                    if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                        'Name' => $value['country_name'],
                                        'States' => []
                                    ];
                                    //
                                    $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                                }
                            }
                            //
                            $this->res['template'] = $this->load->view('hr_documents_management/templates/emergency_contacts', ['data' => $data, 'cs' => $d], true);
                            $this->res['Status'] = TRUE;
                            $this->res['Response'] = 'Proceed';
                            $this->resp();
                        }
                        break;
                        //
                    case "drivers_license":
                        //
                        $this->load->model('dashboard_model');
                        //
                        $data = $this->dashboard_model->get_license_info($post['userSid'], $post['userType'], 'drivers');
                        //
                        if (count($data)) {
                            //
                            $this->res['template'] = $this->load->view('hr_documents_management/templates/drivers_license', ['data' => $data], true);
                            $this->res['Status'] = TRUE;
                            $this->res['Response'] = 'Proceed';
                            $this->resp();
                        }
                        break;
                        //
                    case "occupational_license":
                        //
                        $this->load->model('dashboard_model');
                        //
                        $data = $this->dashboard_model->get_license_info($post['userSid'], $post['userType'], 'occupational');
                        //
                        if (count($data)) {
                            //
                            $this->res['template'] = $this->load->view('hr_documents_management/templates/occupational_license', ['data' => $data], true);
                            $this->res['Status'] = TRUE;
                            $this->res['Response'] = 'Proceed';
                            $this->resp();
                        }
                        break;
                        //
                    case "direct_deposit":
                        //
                        $this->load->model('direct_deposit_model');
                        $data['users_type'] = $userType = $post['userType'];
                        $data['users_sid'] = $userSid = $post['userSid'];
                        $data['type'] = 'prints';
                        $employee_number = $this->direct_deposit_model->get_user_extra_info($post['userType'], $post['userSid'], $company_sid);
                        $data['employee_number'] = $employee_number;
                        $data['data'] = $this->direct_deposit_model->getDDI($post['userType'], $post['userSid'], $company_sid);
                        //
                        $data['data'][0]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][0]['voided_cheque']));
                        if (isset($data['data'][1])) $data['data'][1]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][1]['voided_cheque']));

                        $data[$userType] = $data['cn'] = $this->direct_deposit_model->getUserData($userSid, $userType);
                        //
                        $this->res['template'] = $this->load->view('direct_deposit/pd', $data, true);
                        $this->res['Status'] = TRUE;
                        $this->res['Response'] = 'Proceed';
                        $this->resp();
                        break;
                }

                $this->resp();
                break;

                // 
            case "get_general_document_history":
                //
                $history = $this->hr_documents_management_model->getGeneralAssignedDocumentHistory(
                    $post['generalDocumentSid'],
                    $post['userType']
                );
                //
                $this->res['Status'] = TRUE;
                $this->res['Data'] = $history;
                $this->res['Response'] = 'Proceed';
                $this->resp();
                break;

                //
            case "assign_document":
                //
                $insertId = $this->hr_documents_management_model->assignGeneralDocument(
                    $post['userSid'],
                    $post['userType'],
                    $company_sid,
                    $post['documentType'],
                    $employer_sid,
                    $post['sid'],
                    $post['note'],
                    $post['isRequired']
                );
                //
                if (!$insertId) {
                    $this->res['Response'] = 'Oops! Looks like some thing went wrong. Please, try again in a few moments.';
                    $this->resp();
                }
                //
                if (isset($post['sendEmail']) && $post['sendEmail'] == 1) {
                    //
                    $hf = message_header_footer_domain($company_sid, $company_name);
                    // Send Email and SMS
                    $replacement_array = array();
                    //
                    $userInfoE = $this->hr_documents_management_model->getUserData(
                        $post['userSid'],
                        $post['userType'],
                        $company_sid
                    );
                    //
                    if ($post['userType'] == 'employee') {
                        //
                        $replacement_array['contact-name'] = ucwords($userInfoE['first_name'] . ' ' . $userInfoE['last_name']);
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['username'] = $replacement_array['contact-name'];
                        $replacement_array['firstname'] = $replacement_array['first_name'] = $userInfoE['first_name'];
                        $replacement_array['lastname'] = $replacement_array['last_name'] = $userInfoE['last_name'];
                        $replacement_array['baseurl'] = base_url();
                        $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                        //
                        $user_extra_info = array();
                        $user_extra_info['user_sid'] = $post['userSid'];
                        $user_extra_info['user_type'] = $post['userType'];
                        //
                        $this->load->model('Hr_documents_management_model', 'HRDMM');

                        if ($this->HRDMM->isActiveUser($post['userSid'], $post['userType'])) {
                            //
                            if ($this->hr_documents_management_model->doSendEmail($post['userSid'], $post['userType'], "HREMS13")) {
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $userInfoE['email'], $replacement_array, $hf, 1, $user_extra_info);
                            }
                        }
                    } else {
                        // Send single document emails to applicant
                        // Set email content
                        $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
                        //
                        $this->load->library('encryption', 'encrypt');
                        //
                        $time = strtotime('+10 days');
                        ///
                        $type = $post['documentType'];
                        //
                        $encryptedKey = $this->encrypt->encode($insertId . '/' . $post['userSid'] . '/' . $post['userType'] . '/' . $time . '/' . $type);
                        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                        //
                        $userInfoE["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . (ucwords(preg_replace('/_/', ' ', $post['documentType']))) . '</a>';
                        //
                        $subject = convert_email_template($template['subject'], $userInfoE);
                        $message = convert_email_template($template['text'], $userInfoE);
                        //
                        $body = $hf['header'];
                        $body .= $message;
                        $body .= $hf['footer'];
                        //
                        $this->hr_documents_management_model
                            ->updateAssignedGDocumentLinkTime(
                                $time,
                                $insertId
                            );
                        //
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $userInfoE['email'],
                            $subject,
                            $body,
                            $company_name
                        );
                    }
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Date'] = date('Y-m-d H:i:s', strtotime('now'));
                $this->res['Response'] = 'You have successfully assigned ' . (ucwords(str_replace('_', ' ', $post['documentType']))) . ' document.';
                $this->resp();
                break;

                //
            case "revoke_document":
                //
                $insertId = $this->hr_documents_management_model->revokeGeneralDocument(
                    $post['userSid'],
                    $post['userType'],
                    $company_sid,
                    $post['documentType'],
                    $employer_sid,
                    $post['sid']
                );
                //
                if (!$insertId) {
                    $this->res['Response'] = 'Oops! Looks like some thing went wrong. Please, try again in a few moments.';
                    $this->resp();
                }
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'You have successfully revoked ' . (ucwords(str_replace('_', ' ', $post['documentType']))) . ' document.';
                $this->resp();
                break;
                //
            case "modify_offer_letter_dates":
                //
                $data_to_update = array();
                //
                $document_sid = $post['document_sid'];
                //
                if (isset($_POST['assign_date']) && $_POST['assign_date'] != '') {
                    $data_to_update['assigned_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['assign_date'])));
                }
                //
                if (isset($_POST['signed_date']) && $_POST['signed_date'] != '') {
                    $data_to_update['signature_timestamp'] = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['signed_date'])));
                }
                //
                $this->hr_documents_management_model->modify_offer_letter_data($document_sid, $data_to_update);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Dates change successfully.';
                $this->resp();
                //
                break;

                //
            case "change_offer_letter_archive_status":
                //
                $data_to_update = array();
                //
                $document_sid = $post['document_sid'];
                //
                $data_to_update['archive'] = 1;
                //
                $this->hr_documents_management_model->modify_offer_letter_data($document_sid, $data_to_update);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Offer letter archived successfully.';
                $this->resp();
                //
                break;
                //
            case "modify_offer_letter_data":
                //
                $data_to_update = array();
                //
                $document_sid = $post['document_sid'];
                //
                $document_title = $post['title'];
                //
                $document_type = $post['document_type'];
                //
                $document_vtpr = $post['visible_to_payroll'];
                //
                $table_name = $post['table_name'];
                //
                $user_sid = $post['user_sid'];
                //
                if ($document_type == 'uploaded' && isset($_FILES) && !empty($_FILES)) {
                    $original_name = $_FILES['document']['name'];
                    //
                    $valid_extension = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv');
                    //
                    $file_info = pathinfo($original_name);
                    //
                    $extension = strtolower($file_info['extension']);
                    //
                    if (in_array($extension, $valid_extension)) {
                        $document_s3_path = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $user_sid, AWS_S3_BUCKET_NAME);

                        if (!empty($document_s3_path) && $document_s3_path != 'error') {
                            $data_to_update['document_s3_name'] = $document_s3_path;
                            $data_to_update['document_original_name'] = $original_name;
                            $data_to_update['document_extension'] = $extension;
                        } else {
                            $return_data['upload_status'] =  'error';
                            $return_data['reason'] =  'Something went wrong, Please try again!';
                            echo json_encode($return_data);
                        }
                    } else {
                        $return_data['upload_status'] =  'error';
                        $return_data['reason'] =  'Upload document type is not valid';
                        echo json_encode($return_data);
                    }
                } else if (isset($post['document_discription']) && !empty($post['document_discription'])) {
                    $data_to_update['document_description'] = $post['document_discription'];
                }
                //
                if (isset($post['assign_date']) && $post['assign_date'] != '') {
                    $data_to_update['assigned_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $post['assign_date'])));
                }
                //
                if (isset($post['signed_date']) && $post['signed_date'] != '') {
                    $data_to_update['signature_timestamp'] = date('Y-m-d', strtotime(str_replace('-', '/', $post['signed_date'])));
                    $data_to_update['uploaded_date'] = date('Y-m-d', strtotime(str_replace('-', '/', $post['signed_date']))) . ' 00:00:00';
                }
                //
                $data_to_update['document_title']       = $document_title;
                $data_to_update['visible_to_payroll']   = $document_vtpr;
                $data_to_update['archive']   = $_POST['archive'];
                //
                $this->hr_documents_management_model->modify_offer_letter_data($document_sid, $data_to_update, $table_name);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Offer letter archived successfully.';
                $this->resp();
                //
                break;
                //
            case "check_user_complete_general_document":
                //
                $company_sid = $post['company_sid'];
                //
                $user_sid = $post['user_sid'];
                //
                $user_type = $post['user_type'];
                //
                $generalDocuments = $this->hr_documents_management_model->getUncompletedGeneralAssignedDocuments(
                    $company_sid,
                    $user_sid,
                    $user_type
                );

                //
                $documents = $this->hr_documents_management_model->getUncompletedAssignedDocuments(
                    $company_sid,
                    $user_sid,
                    $user_type
                );

                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = array_merge($generalDocuments, $documents);
                $this->resp();
                //
                break;
                //
            case "modify_authorized_document":
                //
                $document_sid = $post['document_sid'];
                //
                $user_sid = $post['user_sid'];
                //
                $action_name = $post['action_name'];
                //
                $action_type = $post['action_type'];
                //
                $this->hr_documents_management_model->archive_authorized_document($document_sid, $user_sid, $action_name, $action_type);
                //
                $this->res['Status'] = TRUE;
                if ($action_name == 'archive') {
                    $this->res['Response'] = 'You have successfully archived the authorized document.';
                } else {
                    $this->res['Response'] = 'You have successfully activated the authorized document.';
                }
                //
                $this->resp();
                //
                break;

                //
            case "mark_general_document_mandatory":
                //
                $this->hr_documents_management_model->makeGeneralDocumentRequired(
                    $post['document_id'],
                    $post['document_type'],
                    $post['user_sid'],
                    $post['user_type'],
                    $post['required']
                );
                //
                $this->res['Status'] = true;
                //
                $this->resp();
                break;
            case "revoke_library_document":
                $this->hr_documents_management_model->requiredDocumentLibrary(
                    $post['document_sid']
                );
                //
                $this->res['Status'] = true;
                //
                $this->resp();
                break;
            case "modify_manual_document_data":
                //
                $data_to_update = array();
                //
                $document_sid = $post['document_sid'];
                //
                if (isset($_POST['title']) && $_POST['title'] != '') {
                    $data_to_update['document_title'] = $_POST['title'];
                }
                //
                if (isset($_POST['signed_date']) && $_POST['signed_date'] != '') {
                    $data_to_update['signature_timestamp'] = date('Y-m-d', strtotime(str_replace('-', '/', $_POST['signed_date'])));
                }
                //
                $this->hr_documents_management_model->updateManualDocumentData($document_sid, $data_to_update);
                //
                $this->res['Status'] = TRUE;
                $this->res['Response'] = 'Manual document update successfully.';
                $this->resp();
                break;
        }
        //
        $this->resp();
    }


    private function resp()
    {
        header('Content-Type: application/json');
        echo @json_encode($this->res);
        exit(0);
    }


    function print_download(
        $t, // Type 
        $a, // Action
        $s, // Section
        $i,  // ID
        $tt = 'document'
    ) {
        // Get document
        switch ($t) {
            case 'assigned_history':
                if ($tt == 'document')
                    $d = $this->hr_documents_management_model->getDocumentHistoryById($i);
                else
                    $d = $this->hr_documents_management_model->getOfferLetterById($i);
                break;
            case 'original':
                if ($tt == 'document')
                    $d = $this->hr_documents_management_model->getDocumentById($i);
                else
                    $d = $this->hr_documents_management_model->getOfferLetterById($i);
                break;
            case 'assigned':
            case 'submitted':
                $d = $this->hr_documents_management_model->getAssignedDocumentById($i);
                break;
        }

        if (!isset($d['user_type'])) $d['user_type'] = 'employee';

        $data['type'] = $t;
        $data['action'] = $a;
        $data['section'] = $s;
        $data['id'] = $i;
        $data['document'] = $d;

        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);

        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        //
        $data['title'] = 'Print / Download Document';
        //
        // $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/hybrid/pd', $data);
        // $this->load->view('main/footer');
    }


    // 
    function offer_letter_add()
    {
        //
        $resp = array(
            'Status' => FALSE,
            'Response' => 'Failed to add offer letter / pay plan'
        );

        //
        if (!isset($_POST) || !sizeof($_POST)) $this->res($resp);
        //
        $post = $_POST;
        //    
        if (isset($post['file'])) {
            $post['file'] =    json_decode($post['file'], true);
        }
        //
        $authManagers = isset($_POST['signers']) && $_POST['signers'] ?  $_POST['signers'] : '';
        //
        $msg = 'Offer Letter / Pay Plan is saved';
        //
        $ins = [];
        $ins['company_sid'] = $post['CompanySid'];
        $ins['employer_sid'] = $post['EmployerSid'];
        $ins['letter_type'] = $post['type'];
        $ins['letter_name'] = $post['name'];
        $ins['letter_body'] = $post['body'];
        $ins['guidence'] = $post['guidence'];
        $ins['signers'] = $authManagers;
        $ins['target_user_type'] = $post['Type'];
        $ins['acknowledgment_required'] = $post['acknowledgment'];
        $ins['download_required'] = $post['download'];
        $ins['signature_required'] = $post['signature'];
        $ins['sort_order'] = $post['sortOrder'] == '' ? 1 : $post['sortOrder'];
        $ins['is_specific'] = $post['EmployeeSid'];


        // Assigner handling
        $ins['has_approval_flow'] = 0;
        $ins['document_approval_note'] = $ins['document_approval_employees'] = '';
        // Assigner handling
        $post = $this->input->post(NULL, true);

        // Document Settings - Confidential
        $ins['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
        //
        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);

        //
        $ins['confidential_employees'] = NULL;
        //
        if ($post['confidentialSelectedEmployees']) {

            if (strpos($post['confidentialSelectedEmployees'], "-1") !== false) {
                $ins['confidential_employees'] = "-1";
            } else {
                $ins['confidential_employees'] = $post['confidentialSelectedEmployees'];
            }
        }
        //Automatically assign after Days
        $ins['automatic_assign_type'] = !empty($this->input->post('assign_type')) ? $this->input->post('assign_type') : 'days';
        if ($ins['automatic_assign_type'] == 'days') {
            $ins['automatic_assign_in'] = !empty($this->input->post('assign_in_days')) ? $this->input->post('assign_in_days') : 0;
        } else {
            $ins['automatic_assign_in'] = !empty($this->input->post('assign_in_months')) ? $this->input->post('assign_in_months') : 0;
        }

        $company_name = getCompanyNameBySid($post['CompanySid']);
        //
        if (($post['type'] == 'uploaded' || $post['type'] == 'hybrid_document') && sizeof($_FILES)) {
            //
            $s3Name = $_SERVER['HTTP_HOST'] == 'localhost'
                ? '0057-test_latest_uploaded_document-58-Yo2.pdf'
                : upload_file_to_aws('file', $ins['company_sid'], str_replace(' ', '_', $ins['letter_name']), $ins['employer_sid'], AWS_S3_BUCKET_NAME);

            $ins['uploaded_document_s3_name'] = $s3Name;
            $ins['uploaded_document_original_name'] = $_FILES['file']['name'];
        } else if (($post['type'] == 'uploaded'  || $post['type'] == 'hybrid_document') && isset($post['file']['s3Name'])) {
            //
            $s3Name = $_SERVER['HTTP_HOST'] == 'localhost'
                ? '0057-test_latest_uploaded_document-58-Yo2.pdf'
                : putFileOnAWSBase64($post['file']['s3Name']);

            $ins['uploaded_document_s3_name'] = $s3Name;
            $ins['uploaded_document_original_name'] = $post['file']['name'];
        }
        //
        $hasApprovalFlow = 0;
        $approvalList = "";
        $approvalNote = "";
        //
        if ($_POST['has_approval_flow'] == 'on') {
            $hasApprovalFlow = 1;
            $approvalList = isset($_POST['approvers_list']) && $_POST['approvers_list'] ?  $_POST['approvers_list'] : '';
            $approvalNote = isset($_POST['approvers_note']) ? $_POST['approvers_note'] : '';
        }
        //
        $ins['has_approval_flow'] = $hasApprovalFlow;
        $ins['document_approval_employees'] = $approvalList;
        $ins['document_approval_note'] = $approvalNote;

        //
        $insertId = $this->hr_documents_management_model->insertOfferLetterSpecific($ins);
        //
        $resp = array(
            'Status' => FALSE,
            'Response' => 'Failed to add offer letter'
        );
        //
        if (!$insertId) $this->res($resp);
        else {
            // Do we need to assign
            if ($post['assign'] == 'save_assign') {
                $msg .= ' and assigned';
                // Assignment
                // Get previous offer letter assignments
                $assignments = $this->hr_documents_management_model->getOfferLetterByEmployeeSid(
                    $post['EmployeeSid']
                );
                //
                if (sizeof($assignments)) {
                    foreach ($assignments as $assignment) {
                        $sid = $assignment['sid'];
                        unset($assignment['sid']);
                        $i = $this->hr_documents_management_model->insertOfferLetterIntoHistory($assignment);
                        if ($i) {
                            $this->hr_documents_management_model->removeAssignedOfferLetter($sid);
                        }
                    }
                }
                // Disable previous offer letters
                $this->hr_documents_management_model->DisableAssignedOfferLetter(
                    $post['EmployeeSid'],
                    $post['Type']
                );
                //
                $a = [];
                $a['company_sid'] = $post['CompanySid'];
                $a['status'] = 1;
                $a['assigned_by'] = $post['EmployerSid'];
                $a['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $a['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
                $a['user_type'] = $post['Type'];
                $a['user_sid'] = $post['EmployeeSid'];
                $a['document_type'] = 'offer_letter';
                $a['document_sid'] = $insertId;
                $a['document_title'] = $post['name'];
                $a['document_description'] = $post['body'];
                $a['offer_letter_type'] = $post['type'];
                if (isset($ins['uploaded_document_s3_name'])) {
                    $a['document_s3_name'] = $ins['uploaded_document_s3_name'];
                    $a['document_original_name'] = $ins['uploaded_document_original_name'];
                    $t = explode('.', $a['document_s3_name']);
                    $a['document_extension'] = $t[sizeof($t) - 1];
                }
                $a['acknowledgment_required'] = $post['acknowledgment'];
                $a['download_required'] = $post['download'];
                $a['signature_required'] = $post['signature'];
                $a['is_required'] = 1;
                // $a['is_required'] = $post['isRequired'];
                $a['is_signature_required'] = 0;
                // $a['is_signature_required'] = $post['isSignatureRequired'];
                $a['visible_to_payroll'] = $post['payroll'];
                $a['allowed_roles'] = $post['roles'];
                $a['allowed_departments'] = $post['departments'];
                $a['allowed_teams'] = $post['teams'];
                $a['allowed_employees'] = $post['employees'];
                //
                $a['visible_to_payroll'] = !isset($post['payroll']) ? $post['payroll'] : 0;
                //
                $a['allowed_roles'] = isset($post['roles']) ? $post['roles'] : NULL;
                $a['allowed_employees'] = isset($post['employees']) ? $post['employees'] : NULL;
                $a['allowed_departments'] = isset($post['departments']) ? $post['departments'] : NULL;
                $a['allowed_teams'] = isset($post['teams']) ? $post['teams'] : NULL;


                // Document Settings - Confidential
                $a['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
                //
                $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);

                //
                $a['confidential_employees'] = NULL;
                //
                if ($post['confidentialSelectedEmployees']) {

                    if (strpos($post['confidentialSelectedEmployees'], "-1") !== false) {
                        $a['confidential_employees'] = "-1";
                    } else {
                        $a['confidential_employees'] = $post['confidentialSelectedEmployees'];
                    }
                }
                //
                $assignInsertId = $this->hr_documents_management_model->assignOfferLetter($a);
                //
                $verification_key = random_key(80);
                $this->hr_documents_management_model->set_offer_letter_verification_key($a['user_sid'], $verification_key, $post['Type']);
                ///
                if ($hasApprovalFlow == 1) {
                    //
                    $managersList = '';
                    //
                    if (($ins['letter_type'] == 'generated' || $ins['letter_type'] == 'hybrid_document') && $authManagers != null) {
                        $managersList = $authManagers;
                    }
                    // When approval employees are selected
                    $this->HandleApprovalFlow(
                        $assignInsertId,
                        $approvalNote,
                        $approvalList,
                        $post['sendEmail'],
                        $managersList
                    );
                } else {
                    // For email
                    if ($post['sendEmail'] == 'yes') {
                        // 
                        $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
                        // Send Email and SMS
                        $replacement_array = array();
                        //
                        switch ($post['Type']) {
                            case 'employee':
                                $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($post['CompanyName']);
                                $replacement_array['username'] = $replacement_array['contact-name'];
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                //
                                $this->hr_documents_management_model->update_employee($post['EmployerSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                                break;

                            case 'applicant':
                                $user_info = $this->hr_documents_management_model->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                                //
                                // $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                // $replacement_array['company_name'] = ucwords($post['CompanyName']);
                                // $replacement_array['username'] = $replacement_array['contact-name'];
                                // $replacement_array['firstname'] = $user_info['first_name'];
                                // $replacement_array['lastname'] = $user_info['last_name'];
                                // $replacement_array['first_name'] = $user_info['first_name'];
                                // $replacement_array['last_name'] = $user_info['last_name'];
                                // $replacement_array['baseurl'] = base_url();
                                // $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                break;
                        }
                        //
                        $is_manual = get_document_type($assignInsertId);
                        //
                        if (sizeof($replacement_array) && $is_manual == 'no') {
                            //
                            $user_extra_info = array();
                            $user_extra_info['user_sid'] = $post['EmployeeSid'];
                            $user_extra_info['user_type'] = $post['Type'];
                            //
                            $this->load->model('Hr_documents_management_model', 'HRDMM');
                            if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $post['Type'])) {
                                //
                                if ($this->hr_documents_management_model->doSendEmail($post['EmployeeSid'], $post['Type'], "HREMS14")) {
                                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                }
                            }
                        }
                    }

                    // Check if it's Authorize document
                    if (($ins['letter_type'] == 'generated' || $ins['letter_type'] == 'hybrid_document') && $authManagers != null) {
                        // Managers handling
                        $this->hr_documents_management_model->addManagersToAssignedDocuments(
                            $authManagers,
                            $assignInsertId,
                            $post['CompanySid'],
                            $post['EmployerSid']
                        );
                        //
                        $this->hr_documents_management_model->change_document_approval_status(
                            $assignInsertId,
                            [
                                'managersList' => $ins['signers']
                            ]
                        );
                    }
                }
            }
            //
            $resp['Status'] = true;
            $resp['Response'] = $msg . ' successfully.';
            $resp['InsertId'] = $insertId;
            $this->res($resp);
        }
    }


    // 
    // Deprecated, need to remove it's functionlity
    // 
    function offer_letter_edit()
    {
        //
        if (!isset($_POST) || !sizeof($_POST))
            //
            $post = $_POST;
        //
        $ins = [];
        $ins['company_sid'] = $post['CompanySid'];
        $ins['employer_sid'] = $post['EmployerSid'];
        $ins['letter_type'] = $post['type'];
        $ins['letter_name'] = $post['name'];
        $ins['letter_body'] = $post['body'];
        $ins['guidence'] = $post['guidence'];
        $ins['target_user_type'] = $post['Type'];
        $ins['acknowledgment_required'] = $post['acknowledgment'];
        $ins['download_required'] = $post['download'];
        $ins['signature_required'] = $post['signature'];
        $ins['sort_order'] = $post['sortOrder'] == '' ? 1 : $post['sortOrder'];
        $ins['is_specific'] = $post['EmployeeSid'];
        //
        if ($post['type'] == 'uploaded' && sizeof($_FILES)) {
            //
            $s3Name = $_SERVER['HTTP_HOST'] == 'localhost'
                ? '0057-test_latest_uploaded_document-58-Yo2.pdf'
                : upload_file_to_aws('file', $ins['company_sid'], str_replace(' ', '_', $ins['letter_name']), $ins['employer_sid'], AWS_S3_BUCKET_NAME);

            $ins['uploaded_document_s3_name'] = $s3Name;
            $ins['uploaded_document_original_name'] = $_FILES['file']['name'];
        }
        //
        $this->hr_documents_management_model->updateOfferLetterSpecific($ins, $post['sid']);
        //
        $resp = array(
            'Status' => FALSE,
            'Response' => 'Failed to update offer letter'
        );
        //
        $resp['Status'] = true;
        $resp['Response'] = 'Offer letter is updated.';
        $resp['InsertId'] = $post['sid'];
        $this->res($resp);
    }


    public function add_document(
        $user_type,
        $user_sid
    ) {
        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        //
        check_access_permissions($data['security_details'], 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = $data['session']['company_detail']['CompanyName'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $employer_email = $data['session']['employer_detail']['email'];

        // Redirect URL
        $redirectURL = 'hr_documents_management/add_document/' . $user_type . '/' . $user_sid;

        //
        if (isset($_POST) && sizeof($_POST)) {
            //
            $data_to_insert = array();
            //
            $post = $this->input->post(NULL, TRUE);
            //

            $document_title = $this->input->post('document_title');
            $document_description = htmlentities($this->input->post('document_description', false));
            $document_guidence = htmlentities($this->input->post('document_guidence', false));
            //
            $do_upload = $post['perform_action'] == 'uploaded' || $post['perform_action'] == 'hybrid_document' ? true : false;
            $do_descpt = $post['perform_action'] == 'generated' || $post['perform_action'] == 'hybrid_document' ? true : false;

            $data_to_insert['isdoctolibrary'] = isset($post['isdoctolibrary']) ? $post['isdoctolibrary'] : 0;
            $data_to_insert['visible_to_document_center'] = 0;

            // Fo uploaded file
            if ($do_upload) {

                $data_to_insert['uploaded_document_original_name'] = $post['document_name'];
                $data_to_insert['uploaded_document_s3_name'] = $post['document_url'];
                $data_to_insert['uploaded_document_extension'] = $post['document_extension'];
            }

            if ($post['js-template-type'] == 'template' && isset($post['document_url']) && !empty($post['document_url'])) {
                $data_to_insert['uploaded_document_original_name'] = $post['document_name'];
                $data_to_insert['uploaded_document_s3_name'] = $post['document_url'];
                $data_to_insert['uploaded_document_extension'] = $post['document_extension'];
            } else if ($post['js-template-type'] == 'template' && isset($post['uploaded_file']) && !empty($post['uploaded_file'])) {
                $data_to_insert['uploaded_document_original_name'] = $post['uploaded_file_orig'];
                $data_to_insert['uploaded_document_s3_name'] = $post['uploaded_file'];
                $data_to_insert['uploaded_document_extension'] = $post['uploaded_file_ext'];
            }

            //
            $data_to_insert['isdoctohandbook'] = isset($post['isdoctohandbook']) ? $post['isdoctohandbook'] : 0;

            //
            if ($do_descpt) $data_to_insert['document_description'] = $document_description;
            //
            $data_to_insert['is_specific'] = $user_sid;
            $data_to_insert['is_specific_type'] = $user_type;
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['employer_sid'] = $employer_sid;
            $data_to_insert['document_title'] = $document_title;
            $data_to_insert['document_type'] = $post['perform_action'];
            $data_to_insert['sort_order'] = $post['sort_order'] == '' ? 1 : $post['sort_order'];

            $data_to_insert['unique_key'] = generateRandomString(32);
            $data_to_insert['onboarding'] = $post['onboarding'];
            $data_to_insert['download_required'] = $post['download_required'];
            $data_to_insert['acknowledgment_required'] = $post['acknowledgment_required'];
            $data_to_insert['signature_required'] = $post['signature_required'];
            $data_to_insert['automatic_assign_type'] = !empty($post['assign_type']) ? $post['assign_type'] : 'days';
            //
            if ($data_to_insert['automatic_assign_type'] == 'days')
                $data_to_insert['automatic_assign_in'] = !empty($post['assign-in-days']) ? $post['assign-in-days'] : 0;
            else
                $data_to_insert['automatic_assign_in'] = !empty($post['assign-in-months']) ? $post['assign-in-months'] : 0;
            //
            $data_to_insert['visible_to_payroll'] = !isset($post['categories']) && isset($post['visible_to_payroll']) ? 1 : 0;
            $post = $this->input->post(NULL, true);
            //
            $data_to_insert['is_available_for_na'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
            $data_to_insert['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
            $data_to_insert['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
            $data_to_insert['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;

            // Assign & Send document
            // Set
            $aType = $this->input->post('assignAndSendDocument', true);
            $aDate = $this->input->post('assignAndSendCustomDate', true);
            $aDay = $this->input->post('assignAndSendCustomDay', true);
            $aTime = $this->input->post('assignAndSendCustomTime', true);
            $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
            //
            $data_to_insert['assign_type'] = $aType;
            $data_to_insert['assign_date'] = $aDate;
            $data_to_insert['assign_time'] = $aTime;
            //
            if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_insert['assign_type'] = 'none';
            //
            if (empty($aDate)) $data_to_insert['assign_date'] = null;
            if (empty($aTime)) $data_to_insert['assign_time'] = null;
            //
            if ($aType == 'weekly' && !empty($aDay)) $data_to_insert['assign_date'] = $aDay;
            if ($aType == 'weekly' && empty($aDay)) $data_to_insert['assign_date'] = null;
            //
            if ($aEmployees && count($aEmployees)) {
                //
                if (in_array('-1', $aEmployees)) $data_to_insert['assigned_employee_list'] = 'all';
                else $data_to_insert['assigned_employee_list'] = json_encode($aEmployees);
            }

            //
            if (isset($post['managersList']) && $post['managersList'] && sizeof($post['managersList'])) {
                $data_to_insert['managers_list'] = implode(',', $post['managersList']);
            }
            //
            $data_to_insert['video_required'] = 0;
            //
            $video_required = $post['video_source'];

            if ($video_required != 'not_required') {
                $video_source = $this->input->post('video_source');

                if (isset($_FILES['video_upload']) && !empty($_FILES['video_upload']['name'])) {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = basename($_FILES["video_upload"]["name"]);
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    if (move_uploaded_file($_FILES["video_upload"]["tmp_name"], $target_file)) {
                        $this->session->set_flashdata('message', '<strong>The file ' . basename($_FILES["video_upload"]["name"]) . ' has been uploaded.');
                    } else {
                        $this->session->set_flashdata('message', '<strong>Sorry, there was an error uploading your file.');
                        redirect($redirectURL, 'refresh');
                    }

                    $video_url = $file_name;
                } else if ($post['yt_vm_video_url'] != '' && $video_required = 'upload') {
                    $random = generateRandomString(5);
                    $company_id = $data['session']['company_detail']['sid'];
                    $target_file_name = preg_replace('/\s+/', '_', strtolower($data_to_insert['document_title']));
                    $file_name = strtolower($company_id . '/' . $random . '_' . $target_file_name);
                    $target_dir = "assets/uploaded_videos/";
                    $target_file = $target_dir . $file_name;
                    $filename = $target_dir . $company_id;


                    // 
                    $t = explode('.', $post['yt_vm_video_url']);
                    $target_file .= '.' . $t[sizeof($t) - 1];

                    if (!file_exists($filename)) {
                        mkdir($filename);
                    }

                    file_put_contents($target_file, file_get_contents($target_dir . $post['yt_vm_video_url']));

                    $video_url = $file_name;
                } else {
                    $video_url = $this->input->post('yt_vm_video_url');

                    if ($video_source == 'youtube') {
                        $url_prams = array();
                        parse_str(parse_url($video_url, PHP_URL_QUERY), $url_prams);

                        if (isset($url_prams['v'])) {
                            $video_url = $url_prams['v'];
                        } else {
                            $video_url = '';
                        }
                    } else {
                        $video_url = $this->vimeo_get_id($video_url);
                    }
                }

                $data_to_insert['video_source'] = $video_source;
                $data_to_insert['video_url'] = $video_url;
                $data_to_insert['video_required'] = 1;
            }
            //
            $b = $data_to_insert;
            //
            // Document Settings - Confidential
            $data_to_insert['is_confidential'] = $this->input->post('setting_is_confidential', true) && $this->input->post('setting_is_confidential', true) == 'on' ? 1 : 0;
            //
            $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
            //
            $data_to_insert['confidential_employees'] = NULL;
            //
            if ($post['confidentialSelectedEmployees']) {
                $data_to_insert['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
            }
            //
            $insert_id = $this->hr_documents_management_model->insert_document_record($data_to_insert);

            if (isset($_POST['document_group_assignment'])) {
                $document_group_assignment = $this->input->post('document_group_assignment');

                if (!empty($document_group_assignment)) {
                    foreach ($document_group_assignment as $key => $group_sid) {
                        $data_to_insert = array();
                        $data_to_insert['group_sid'] = $group_sid;
                        $data_to_insert['document_sid'] = $insert_id;
                        $this->hr_documents_management_model->assign_document_2_group($data_to_insert);
                    }
                }
            }
            //
            if (isset($_POST['categories'])) {
                $document_category_assignment = $this->input->post('categories');

                if (!empty($document_category_assignment)) {
                    foreach ($document_category_assignment as $key => $category_sid) {
                        $data_to_insert = array();
                        $data_to_insert['category_sid'] = $category_sid;
                        $data_to_insert['document_sid'] = $insert_id;
                        $this->hr_documents_management_model->assign_document_2_category($data_to_insert);
                    }
                }
            }

            // Also assign it in case of 
            // assignandsave
            $todo = isset($post['saveAndAssign']) ? $post['saveAndAssign'] : $post['submit'];
            //
            if ($todo == 'saveandassign') {
                // 
                $documentId = $insert_id;
                // Set assign array
                $a = array();
                //
                $a['company_sid'] = $company_sid;
                $a['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
                $a['assigned_by'] = $employer_sid;
                $a['user_type'] = $user_type;
                $a['user_sid'] = $user_sid;
                $a['document_type'] = $b['document_type'];
                $a['document_title'] = $b['document_title'];
                if ($do_descpt) {
                    $a['document_description'] = $b['document_description'];
                }
                if ($do_upload) {
                    $a['document_description'] = !$do_descpt ? $document_guidence : $document_description;
                    $a['document_original_name'] = $b['uploaded_document_original_name'];
                    $a['document_extension'] = $b['uploaded_document_extension'];
                    $a['document_s3_name'] = $b['uploaded_document_s3_name'];
                }
                $a['document_sid'] = $documentId;
                $a['status'] = 1;
                $a['visible_to_payroll'] = $b['visible_to_payroll'];
                //
                $a['allowed_roles'] = isset($post['selected_roles']) ? implode(',', $post['selected_roles']) : NULL;
                $a['allowed_employees'] = isset($post['selected_employees']) ? implode(',', $post['selected_employees']) : NULL;
                $a['allowed_departments'] = isset($post['selected_departments']) ? implode(',', $post['selected_departments']) : NULL;
                $a['allowed_teams'] = isset($post['selected_teams']) ? implode(',', $post['selected_teams']) : NULL;
                //
                $a['download_required'] = $post['download_required'];
                $a['acknowledgment_required'] = $post['acknowledgment_required'];
                $a['signature_required'] = $post['signature_required'];
                $a['is_required'] = $post['isRequired'];
                $a['isdoctolibrary'] = isset($post['isdoctolibrary']) ? $post['isdoctolibrary'] : 0;

                $a['is_signature_required'] = $post['isSignatureRequired'];
                $a['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                // Document Settings - Confidential
                $a['is_confidential'] = $this->input->post('setting_is_confidential', true) && $this->input->post('setting_is_confidential', true) == 'on' ? 1 : 0;
                //
                $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
                //
                $a['confidential_employees'] = NULL;
                //
                if ($post['confidentialSelectedEmployees']) {
                    $a['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : implode(",", $post['confidentialSelectedEmployees']);
                }


                //
                $a['isdoctohandbook'] = isset($post['isdoctohandbook']) ? $post['isdoctohandbook'] : 0;

                // When approval employees are selected
                $assignInsertId = $this->hr_documents_management_model->insert_documents_assignment_record($a);

                // When approval employees are selected
                if ($post['has_approval_flow'] == 'on') {
                    //
                    $managersList = '';
                    //
                    if ($do_descpt && isset($post['managersList']) && $post['managersList'] != null && str_replace('{{authorized_signature}}', '', $document_description) != $document_description) {
                        $managersList = implode(',', $post['managersList']);
                    }
                    //
                    $approvers_list = isset($_POST['approvers_list']) && !empty($_POST['approvers_list']) ? implode(',', array_filter($_POST['approvers_list'])) : '';
                    $approvers_note = isset($post['approvers_note']) && !empty($_POST['approvers_note']) ? $post['approvers_note'] : "";
                    //
                    $this->HandleApprovalFlow(
                        $assignInsertId,
                        $approvers_note,
                        $approvers_list,
                        $post['sendEmail'],
                        $managersList
                    );
                } else {

                    //
                    // For email
                    if ($post['sendEmail'] == 'yes') {
                        $post['CompanySid'] = $company_sid;
                        $post['CompanyName'] = $company_name;
                        $post['EmployeeSid'] = $user_sid;
                        $post['EmployerSid'] = $employer_sid;
                        // 
                        $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
                        // Send Email and SMS
                        $replacement_array = array();
                        //
                        switch ($user_type) {
                            case 'employee':
                                $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                                $is_hour = $this->is_one_hour_complete($user_info['document_sent_on']);
                                //
                                if ($is_hour > 0) {
                                    $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                    $replacement_array['company_name'] = ucwords($post['CompanyName']);
                                    $replacement_array['username'] = $replacement_array['contact-name'];
                                    $replacement_array['firstname'] = $user_info['first_name'];
                                    $replacement_array['lastname'] = $user_info['last_name'];
                                    $replacement_array['first_name'] = $user_info['first_name'];
                                    $replacement_array['last_name'] = $user_info['last_name'];
                                    $replacement_array['baseurl'] = base_url();
                                    $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                                    //
                                    $this->hr_documents_management_model->update_employee($post['EmployeeSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                                    //
                                    $is_manual = get_document_type($assignInsertId);
                                    //
                                    if (sizeof($replacement_array) && $is_manual == 'no') {
                                        //
                                        $user_extra_info = array();
                                        $user_extra_info['user_sid'] = $post['EmployeeSid'];
                                        $user_extra_info['user_type'] = $user_type;
                                        //
                                        $this->load->model('Hr_documents_management_model', 'HRDMM');
                                        if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $user_type)) {
                                            //
                                            if ($this->hr_documents_management_model->doSendEmail($post['EmployeeSid'], $user_type, "HREMS15")) {
                                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                            }
                                        }
                                    }
                                }
                                //   
                                break;

                            case 'applicant':
                                $user_info = $this->hr_documents_management_model->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                                // Set email content
                                $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
                                //
                                $this->load->library('encryption', 'encrypt');
                                //
                                $time = strtotime('+10 days');
                                //
                                $encryptedKey = $this->encrypt->encode($assignInsertId . '/' . $user_info['sid'] . '/applicant/' . $time);
                                $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                                //
                                $user_info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($a['document_title']) . '</a>';
                                //
                                $message = convert_email_template($template['text'], $user_info);
                                $subject = convert_email_template($template['subject'], $user_info);
                                //
                                $body = $hf['header'];
                                $body .= $message;
                                $body .= $hf['footer'];
                                //
                                $this->hr_documents_management_model
                                    ->updateAssignedDocumentLinkTime(
                                        $time,
                                        $assignInsertId
                                    );
                                //
                                log_and_sendEmail(
                                    FROM_EMAIL_NOTIFICATIONS,
                                    $user_info['email'],
                                    $subject,
                                    $body,
                                    $data['session']['company_detail']['CompanyName']
                                );
                                break;
                        }
                        //
                        if ($user_type == 'employee') {
                            //
                            $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);
                            //
                            if ($user_info['document_sent_on'] < date('Y-m-d 23:59:59', strtotime('now'))) {
                                //
                                $this->hr_documents_management_model->update_employee($user_sid, array('document_sent_on' => date('Y-m-d H:i:s')));
                                // Send document completion alert
                                broadcastAlert(
                                    DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE,
                                    'documents_status',
                                    'document_assigned',
                                    $company_sid,
                                    $company_name,
                                    $data['session']['employer_detail']['first_name'],
                                    $data['session']['employer_detail']['last_name'],
                                    $employer_sid,
                                    [
                                        'document_title' => $b['document_title'],
                                        'employee_name' => $user_info['first_name'] . ' ' . $user_info['last_name']
                                    ]
                                );
                            }
                        }
                    }

                    //
                    // Check if it's Authorize document
                    if ($do_descpt && isset($post['managersList']) && $post['managersList'] != null && str_replace('{{authorized_signature}}', '', $document_description) != $document_description) {
                        // Managers handling
                        $this->hr_documents_management_model->addManagersToAssignedDocuments(
                            $post['managersList'],
                            $assignInsertId,
                            $company_sid,
                            $employer_sid
                        );
                        //
                        $this->hr_documents_management_model->change_document_approval_status(
                            $assignInsertId,
                            [
                                'managersList' => $post['managersList']
                            ]
                        );
                    }
                }
            }

            $this->session->set_flashdata('message', '<strong>Success:</strong>You have successfully added a new document.');
            redirect($redirectURL, 'refresh');
        }

        // 
        switch ($user_type) {
            case 'employee':
                $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                    redirect('employee_management', 'refresh');
                }
                if (!$data['session']['employer_detail']['access_level_plus'] && !$data['session']['employer_detail']['pay_plan_flag']) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Module Not Accessable!');
                    redirect('employee_management', 'refresh');
                }

                $data = employee_right_nav($user_sid, $data);
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                $data['eeo_form_info'] = $eeo_form_info;
                break;
            case 'applicant':
                $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $data = applicant_right_nav($user_sid, null);
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);
                $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid);
                $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                $data['eeo_form_status'] = $eeo_form_status;
                $data['eeo_form_info'] = $eeo_form_info;

                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => ucwords($user_type)
                );

                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $applicant_info['sid'];
                break;
        }

        //
        $data['user_type']  = $user_type;
        $data['user_sid']  = $user_sid;

        //
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $pp_flag = $data['session']['company_detail']['pay_plan_flag'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        //
        $data['title'] = 'Hybrid Document';
        //
        $data['left_navigation'] = $left_navigation;
        //
        $data['pre_assigned_groups'] = array();
        //
        $data['document_groups'] = $this->hr_documents_management_model->getAllGroups($data['company_sid'], 1);
        $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($data['company_sid'], 1);
        $data['all_documents'] = $this->hr_documents_management_model->get_total_documents($company_sid, $pp_flag, true);
        $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, '');
        // Get departments & teams
        $data['departments'] = $this->hr_documents_management_model->getDepartments($data['company_sid']);
        $data['teams'] = $this->hr_documents_management_model->getTeams($data['company_sid'], $data['departments']);
        //
        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/templates/document');
        $this->load->view('main/footer');
    }

    /**
     * Steps
     * 1 - Check if document already assigned to employee/applicant
     *  1.1 - Add to history
     *  1.2 - Update assigned document and set values to default
     * 2 - Add assigned document row
     * 3 - Send emails    (Only if  'yes' is selected)
     * 4 - Assign Signers (For authorized people)
     */
    function assign_document($document = array())
    {
        //
        //
        $r = [
            'Status' => FALSE,
            'Response' => 'Invalid request'
        ];
        //
        $post = array();
        //
        $assigner_firstname = '';
        $assigner_lastname = '';
        //
        if (!empty($document)) {
            $post['sendEmail'] = $document['sendEmail'];
            $post['Type'] = $document['user_type'];
            $post['CompanySid'] = $document['company_sid'];
            $post['CompanyName'] = getCompanyNameBySid($document['company_sid']);
            $post['EmployeeSid'] = $document['user_sid'];
            $post['EmployerSid'] = $document['assigned_by'];
            $post['managerList'] = isset($document['managersList']) ? $document['managersList'] : '';
            $post['desc'] = $document['document_description'];
            $post['documentTitle'] = $document['document_title'];
            $post['documentSid'] = $document['document_sid'];
            //
            unset($document['sid']);
            unset($document['sendEmail']);
            unset($document['managersList']);
            unset($document['assign_status']);
            unset($document['assigner_note']);
            //
            $assigner_info = get_employee_profile_info($document['assigned_by']);
            //
            $assigner_firstname = $assigner_info['first_name'];
            $assigner_lastname = $assigner_info['last_name'];
        } else {
            $post = $this->input->post(NULL, TRUE);
            $desc = $this->input->post('desc');

            //
            $oldDesc = $desc;

            $desc = $desc ? magicCodeCorrection($desc) : $desc;


            $assigner_firstname = $this->session->userData('logged_in')['employer_detail']['first_name'];
            $assigner_lastname = $this->session->userData('logged_in')['employer_detail']['last_name'];
        }
        //
        // Check if document is previously assigned
        $assigned = $this->hr_documents_management_model->getAssignedDocumentByIdAndEmployeeId(
            $post['documentSid'],
            $post['EmployeeSid']
        );
        //
        $assignInsertId = null;

        // Set assign array
        $a = array();
        //
        if (!empty($assigned)) {
            $assignInsertId = $assigned['sid'];
            //
            unset($assigned['sid']);
            unset($assigned['is_pending']);
            //
            $h = $assigned;
            $h['doc_sid'] = $assignInsertId;
            //
            $this->hr_documents_management_model->insert_documents_assignment_record_history($h);
            //
            $a['status'] = 1;
            $a['acknowledged'] = NULL;
            $a['acknowledged_date'] = NULL;
            $a['downloaded'] = NULL;
            $a['downloaded_date'] = NULL;
            $a['uploaded'] = NULL;
            $a['uploaded_date'] = NULL;
            $a['signature_timestamp'] = NULL;
            $a['signature'] = NULL;
            $a['signature_email'] = NULL;
            $a['signature_ip'] = NULL;
            $a['user_consent'] = 0;
            $a['archive'] = 0;
            $a['signature_base64'] = NULL;
            $a['signature_initial'] = NULL;
            $a['authorized_signature'] = NULL;
            $a['authorized_signature_by'] = NULL;
            $a['authorized_signature_date'] = NULL;
        }
        //
        if (!empty($document)) {
            $a = array_merge($a, $document);
        } else {
            //

            $a['fillable_document_slug'] = $this->hr_documents_management_model->getMainDocumentField(
                $post['documentSid'],
                "fillable_document_slug"
            );
            $a['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $a['is_required'] = $post['isRequired'];
            $a['is_signature_required'] = $post['isSignatureRequired'];
            $a['company_sid'] = $post['CompanySid'];
            $a['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
            $a['assigned_by'] = $post['EmployerSid'];
            $a['user_type'] = $post['Type'];
            $a['user_sid'] = $post['EmployeeSid'];
            $a['document_type'] = $post['documentType'];
            $a['document_title'] = $post['documentTitle'];
            if (isset($post['desc'])) $a['document_description'] = $desc;
            $a['document_sid'] = $post['documentSid'];
            $a['status'] = 1;
            $a['visible_to_payroll'] = $post['visibleToPayroll'];
            if (ASSIGNEDOCIMPL) {
                $a['signature_required'] = $post['isSignature'];
                $a['download_required'] = $post['isDownload'];
                $a['acknowledgment_required'] = $post['isAcknowledged'];
            }
            // Visibility
            if (isset($post['roles'])) {
                $a['allowed_roles'] = $post['roles'];
            }
            if (isset($post['departments'])) {
                $a['allowed_departments'] = $post['departments'];
            }
            if (isset($post['teams'])) {
                $a['allowed_teams'] = $post['teams'];
            }
            if (isset($post['employees'])) {
                $a['allowed_employees'] = $post['employees'];
            }

            //
            if (isset($post['file'])) {
                $a['document_s3_name'] = $_SERVER['HTTP_HOST'] != 'localhost' ? putFileOnAWSBase64($post['file']) : '0057-test_latest_uploaded_document-58-Yo2.pdf';
                $a['document_original_name'] = $post['fileOrigName'];
            }

            // Document Settings - Confidential
            $a['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
            //
            $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
            //
            $a['confidential_employees'] = NULL;
            //
            if ($post['confidentialSelectedEmployees']) {
                $a['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : $post['confidentialSelectedEmployees'];
            }

            if (sizeof($_FILES)) {
                //
                $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                $uploaded_document_original_name = $post['documentTitle'];
                if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_SERVER['HTTP_HOST'] != 'localhost') {
                    //
                    $uploaded_document_s3_name = upload_file_to_aws('file', $post['CompanySid'], str_replace(' ', '_', $post['documentTitle']), $post['EmployeeSid'], AWS_S3_BUCKET_NAME);
                    $uploaded_document_original_name = $_FILES['file']['name'];
                }
                //
                if ($uploaded_document_s3_name != 'error') {
                    $a['document_original_name'] = $uploaded_document_original_name;
                    $a['document_s3_name'] = $uploaded_document_s3_name;
                }
            }
        }
        //

        if ($assignInsertId == null)
            $assignInsertId = $this->hr_documents_management_model->insert_documents_assignment_record($a);
        else
            $assignInsertId = $this->hr_documents_management_model->updateAssignedDocument($assignInsertId, $a); // If already exists then update
        //
        $is_manual = get_document_type($assignInsertId);
        //
        if (isset($post["has_approval_flow"]) && $post["has_approval_flow"] == "on") {
            //
            $managersList = '';
            //
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                $managersList = $post['managerList'];
            }
            //
            $approvers_list = isset($post['approvers_list']) ? $post['approvers_list'] : "";
            $approvers_note = isset($post['approvers_note']) ? $post['approvers_note'] : "";
            //
            // When approval employees are selected
            $this->HandleApprovalFlow(
                $assignInsertId,
                $approvers_note,
                $approvers_list,
                $post['sendEmail'],
                $managersList
            );
        } else {
            // For email
            if ($post['sendEmail'] == 'yes' && $is_manual == 'no') {
                // 
                $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
                // Send Email and SMS
                $replacement_array = array();
                //
                switch ($post['Type']) {
                    case 'employee':
                        $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                        $is_hour = $this->is_one_hour_complete($user_info['document_sent_on']);
                        //
                        if ($is_hour > 0) {
                            //
                            $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                            $replacement_array['company_name'] = ucwords($post['CompanyName']);
                            $replacement_array['username'] = $replacement_array['contact-name'];
                            $replacement_array['firstname'] = $user_info['first_name'];
                            $replacement_array['lastname'] = $user_info['last_name'];
                            $replacement_array['first_name'] = $user_info['first_name'];
                            $replacement_array['last_name'] = $user_info['last_name'];
                            $replacement_array['baseurl'] = base_url();
                            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                            //
                            $this->hr_documents_management_model->update_employee($post['EmployeeSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                            //
                            if (sizeof($replacement_array)) {
                                //
                                $user_extra_info = array();
                                $user_extra_info['user_sid'] = $post['EmployeeSid'];
                                $user_extra_info['user_type'] = $post['Type'];
                                //
                                $this->load->model('Hr_documents_management_model', 'HRDMM');
                                if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $post['Type'])) {
                                    //
                                    if ($this->hr_documents_management_model->doSendEmail($post['EmployeeSid'], $post['Type'], "HREMS16")) {
                                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                    }
                                }
                            }
                        }
                        break;

                    case 'applicant':
                        $user_info = $this->hr_documents_management_model->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                        //
                        $this->load->library('encryption', 'encrypt');
                        //
                        $time = strtotime('+10 days');
                        //
                        $encryptedKey = $this->encrypt->encode($assignInsertId . '/' . $user_info['sid'] . '/applicant/' . $time);
                        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                        //
                        $replacement_array['applicant_name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                        $replacement_array['company_name'] = ucwords($post['CompanyName']);
                        $replacement_array['link'] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($a['document_title']) . '</a>';
                        //
                        $this->hr_documents_management_model
                            ->updateAssignedDocumentLinkTime(
                                $time,
                                $assignInsertId
                            );
                        if (sizeof($replacement_array)) {
                            //
                            $user_extra_info = array();
                            $user_extra_info['user_sid'] = $post['EmployeeSid'];
                            $user_extra_info['user_type'] = $post['Type'];
                            //
                            $this->load->model('Hr_documents_management_model', 'HRDMM');
                            if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $post['Type'])) {
                                //
                                log_and_send_templated_email(HR_DOCUMENTS_FOR_APPLICANT, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                            }
                        }
                        break;
                }
                //
                if ($post['Type'] == 'employee') {
                    //
                    $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                    // Send document completion alert
                    broadcastAlert(
                        DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE,
                        'documents_status',
                        'document_assigned',
                        $post['CompanySid'],
                        $post['CompanyName'],
                        $assigner_firstname,
                        $assigner_lastname,
                        $post['EmployeeSid'],
                        [
                            'document_title' => $post['documentTitle'],
                            'employee_name' => $user_info['first_name'] . ' ' . $user_info['last_name']
                        ]
                    );
                }
                //
            }
            //
            // Check if it's Authorize document
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                // Managers handling
                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                    $post['managerList'],
                    $assignInsertId,
                    $post['CompanySid'],
                    $post['EmployerSid']
                );
                //
                $this->hr_documents_management_model->change_document_approval_status(
                    $assignInsertId,
                    [
                        'managersList' => $post['managerList']
                    ]
                );
            }
        }

        echo 'success';
    }

    /**
     * Steps
     * 1 - Check if document already assigned to employee/applicant
     *  1.1 - Add to history
     *  1.2 - Update assigned document and set values to default
     * 2 - Add assigned document row
     * 3 - Send emails    (Only if  'yes' is selected)
     * 4 - Assign Signers (For authorized people)
     */
    function assign_offer_letter_new()
    {
        //
        $r = [
            'Status' => FALSE,
            'Response' => 'Invalid request'
        ];
        //
        $post = $this->input->post(NULL, TRUE);
        // echo '<pre>';
        // print_r($post);
        // die();
        $desc = $this->input->post('desc');
        $already_assigned = $this->hr_documents_management_model->check_applicant_offer_letter_exist($post['CompanySid'], $post['Type'], $post['EmployeeSid'], 'offer_letter');

        if (!empty($already_assigned)) {
            foreach ($already_assigned as $key => $previous_offer_letter) {
                $previous_assigned_sid = $previous_offer_letter['sid'];
                $already_moved = $this->hr_documents_management_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                if ($already_moved == 'no') {
                    $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                    unset($previous_offer_letter['sid']);
                    $this->hr_documents_management_model->insert_documents_assignment_record_history($previous_offer_letter);
                }
            }
        }

        $this->hr_documents_management_model->disable_all_previous_letter($post['CompanySid'], $post['Type'], $post['EmployeeSid'], 'offer_letter');
        $verification_key = random_key(80);
        $this->hr_documents_management_model->set_offer_letter_verification_key($post['EmployeeSid'], $verification_key, $post['Type']);

        // Managers handling
        // $this->hr_documents_management_model->addManagersToAssignedDocuments(
        //     $this->input->post('managerList'),
        //     $assignOfferLetterId,
        //     $post['CompanySid'],
        //     $employer_sid
        // );
        //
        $assignInsertId = null;

        // Set assign array
        $a = array();
        //
        $a['company_sid'] = $post['CompanySid'];
        $a['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $a['assigned_by'] = $post['EmployerSid'];
        $a['user_type'] = $post['Type'];
        $a['user_sid'] = $post['EmployeeSid'];
        $a['document_type'] = 'offer_letter';
        $a['offer_letter_type'] = $post['documentType'];
        $a['document_title'] = $post['documentTitle'];
        if (isset($post['desc'])) $a['document_description'] = $desc;
        $a['document_sid'] = $post['documentSid'];
        $a['status'] = 1;
        $a['visible_to_payroll'] = $post['visibleToPayroll'];
        $a['allowed_roles'] = $post['roles'];
        $a['allowed_departments'] = $post['departments'];
        $a['allowed_teams'] = $post['teams'];
        $a['allowed_employees'] = $post['employees'];

        if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
            $a['managersList'] = $post['managerList'];
        }


        // Document Settings - Confidential
        $a['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
        //
        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);
        //
        $a['confidential_employees'] = NULL;
        //
        if ($post['confidentialSelectedEmployees'] != 'null') {
            $a['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : $post['confidentialSelectedEmployees'];
        }
        //
        if (ASSIGNEDOCIMPL) {
            $a['signature_required'] = $post['isSignature'];
            $a['download_required'] = $post['isDownload'];
            $a['acknowledgment_required'] = $post['isAcknowledged'];
        }
        //
        if (isset($post['file'])) {
            $a['document_s3_name'] = $_SERVER['HTTP_HOST'] != 'localhost' ? putFileOnAWSBase64($post['file']) : '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $a['document_original_name'] = $post['fileOrigName'];
        }
        //
        if (sizeof($_FILES)) {
            //
            $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $uploaded_document_original_name = $post['documentTitle'];
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_SERVER['HTTP_HOST'] != 'localhost') {
                //
                $uploaded_document_s3_name = upload_file_to_aws('file', $post['CompanySid'], str_replace(' ', '_', $post['documentTitle']), $post['EmployeeSid'], AWS_S3_BUCKET_NAME);
                $uploaded_document_original_name = $_FILES['file']['name'];
            }
            //
            if ($uploaded_document_s3_name != 'error') {
                $a['document_original_name'] = $uploaded_document_original_name;
                $a['document_s3_name'] = $uploaded_document_s3_name;
            }
        }
        //
        if ($assignInsertId == null)
            $assignInsertId = $this->hr_documents_management_model->insert_documents_assignment_record($a);
        else
            $assignInsertId = $this->hr_documents_management_model->updateAssignedDocument($assignInsertId, $a); // If already exists then update
        //

        if (isset($_POST['has_approval_flow']) && $_POST['has_approval_flow'] == 'on') {

            //
            $managersList = '';
            //
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                $managersList = $post['managerList'];
            }
            //
            $approvers_list = isset($post['approvers_list']) ? $post['approvers_list'] : "";
            $approvers_note = isset($post['approvers_note']) ? $post['approvers_note'] : "";

            //     // When approval employees are selected
            $this->HandleApprovalFlow(
                $assignInsertId,
                $approvers_note,
                $approvers_list,
                $post['sendEmail'],
                $managersList
            );
        } else {

            //
            // Check if it's Authorize document
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                // Managers handling
                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                    $post['managerList'],
                    $assignInsertId,
                    $post['CompanySid'],
                    $post['EmployerSid']
                );
                //
                $this->hr_documents_management_model->change_document_approval_status(
                    $assignInsertId,
                    [
                        'managersList' => $post['managerList']
                    ]
                );
            }

            // For email
            if ($post['sendEmail'] == 'yes') {
                $company_name = getCompanyNameBySid($post['CompanySid']);
                // 
                $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
                // Send Email and SMS
                $replacement_array = array();
                //
                switch ($post['Type']) {
                    case 'employee':
                        $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                        $this->hr_documents_management_model->update_employee($post['EmployerSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                        break;

                    case 'applicant':
                        $user_info = $this->hr_documents_management_model->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                        break;
                }
                //
                $applicant_sid = $user_info['sid'];
                $applicant_email = $user_info['email'];
                $applicant_name = $user_info['first_name'] . ' ' . $user_info['last_name'];

                $url = base_url() . 'onboarding/my_offer_letter/' . $verification_key;

                $emailTemplateBody = 'Dear ' . $applicant_name . ', <br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Congratulations and Welcome to ' . $company_name . '</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . 'We have attached an offer letter with this email for you.' . '<br>';
                $emailTemplateBody = $emailTemplateBody . 'Please complete this offer letter by clicking on the link below.' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . $url . '">Offer Letter</a>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<em>If you have any questions at all, please feel free to send us a note at any time and we will get back to you as quickly as we can.</em>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>The HR Team at ' . $company_name . '</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '&nbsp;' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '<strong>Automated Email; Please Do Not reply!</strong>' . '<br>';
                $emailTemplateBody = $emailTemplateBody . '---------------------------------------------------------' . '<br>';

                $from = FROM_EMAIL_NOTIFICATIONS;
                $to = $applicant_email;
                $subject = 'Offer Letter / Pay Plan';
                $from_name = ucwords(STORE_DOMAIN);
                $email_hf = message_header_footer_domain($post['CompanySid'], $company_name);
                $body = $email_hf['header']
                    . $emailTemplateBody
                    . $email_hf['footer'];
                sendMail($from, $to, $subject, $body, $from_name);
            }
            //
        }
        //
        echo 'success';
    }


    /**
     * Steps

     * 1 - Check if document already assigned to employee/applicant
     *  1.1 - Add to history
     *  1.2 - Update assigned document and set values to default
     * 2 - Add assigned document row
     * 3 - Send emails    (Only if  'yes' is selected)
     * 4 - Assign Signers (For authorized people)
     */
    function update_assigned_document()
    {
        //
        $r = [
            'Status' => FALSE,
            'Response' => 'Invalid request'
        ];
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $desc = $this->input->post('desc');

        $desc = $desc ? magicCodeCorrection($desc) : $desc;

        //
        $assignInsertId = $post['documentSid'];
        //
        if (isset($post['desc'])) $a['document_description'] = $desc;
        //
        $a['signature_required'] = $post['isSignature'];
        $a['download_required'] = $post['isDownload'];
        $a['acknowledgment_required'] = $post['isAcknowledged'];
        $a['is_required'] = $post['isRequired'];
        $a['is_signature_required'] = $post['isSignatureRequired'];
        $a['visible_to_payroll'] = $post['visibleToPayroll'];
        $a['allowed_roles'] = $post['selected_roles'];
        $a['allowed_employees'] = $post['selected_employees'];
        $a['allowed_departments'] = $post['selected_departments'];
        $a['allowed_teams'] = $post['selected_teams'];
        $a['managersList '] = $post['managerList'];
        $a['is_confidential'] = $post['is_confidential'] == 'on' ? 1 : 0;
        $a['confidential_employees'] = null;

        //
        if ($post['confidentialSelectedEmployees']) {
            $a['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : $post['confidentialSelectedEmployees'];
        }
        //
        $session = $this->session->userdata('logged_in');
        $employer_sid = $session["employer_detail"]["sid"];
        //
        $a['assigned_by'] = $employer_sid;
        //
        if (isset($post['file'])) {
            $a['document_s3_name'] = $post['file'];
            $a['document_original_name'] = $post['fileOrigName'];
        }

        if (sizeof($_FILES)) {
            //
            $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $uploaded_document_original_name = $post['documentTitle'];
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_SERVER['HTTP_HOST'] != 'localhost') {
                //
                $uploaded_document_s3_name = upload_file_to_aws('file', $post['CompanySid'], str_replace(' ', '_', $post['documentTitle']), $post['EmployeeSid'], AWS_S3_BUCKET_NAME);
                $uploaded_document_original_name = $_FILES['file']['name'];
            }
            //
            if ($uploaded_document_s3_name != 'error') {
                $a['document_original_name'] = $uploaded_document_original_name;
                $a['document_s3_name'] = $uploaded_document_s3_name;
            }
        }

        $assignInsertId = $this->hr_documents_management_model->updateAssignedDocument($assignInsertId, $a); // If already exists then update
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($assignInsertId, false);
        if (isset($post["has_approval_flow"]) && $post["has_approval_flow"] == "on") {

            //
            $managersList = "";
            //
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                $managersList = $post['managerList'];
            }
            //
            $approvers_list = isset($post['approvers_list']) ? $post['approvers_list'] : "";
            $approvers_note = isset($post['approvers_note']) ? $post['approvers_note'] : "";
            //
            // When approval employees are selected
            $this->HandleApprovalFlow(
                $assignInsertId,
                $approvers_note,
                $approvers_list,
                $post['sendEmail'],
                $managersList
            );
            //

        } else {

            $this->hr_documents_management_model->change_document_approval_status(
                $assignInsertId,
                [
                    'approval_process' => 0,
                    'has_approval_flow' => 0
                ]
            );

            // For email
            if ($post['sendEmail'] == 'yes') {
                // 
                $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
                // Send Email and SMS
                $replacement_array = array();
                //
                switch ($post['Type']) {
                    case 'employee':
                        $user_info = $this->hr_documents_management_model->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                        $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                        $replacement_array['company_name'] = ucwords($post['CompanyName']);
                        $replacement_array['username'] = $replacement_array['contact-name'];
                        $replacement_array['firstname'] = $user_info['first_name'];
                        $replacement_array['lastname'] = $user_info['last_name'];
                        $replacement_array['first_name'] = $user_info['first_name'];
                        $replacement_array['last_name'] = $user_info['last_name'];
                        $replacement_array['baseurl'] = base_url();
                        $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                        //
                        $this->hr_documents_management_model->update_employee($post['EmployerSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                        //
                        $is_manual = get_document_type($assignInsertId);
                        //
                        if (sizeof($replacement_array) && $is_manual == 'no') {
                            //
                            $user_extra_info = array();
                            $user_extra_info['user_sid'] = $post['EmployeeSid'];
                            $user_extra_info['user_type'] = $post['Type'];
                            //
                            log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                        }
                        break;

                    case 'applicant':
                        $user_info = $this->hr_documents_management_model->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                        // Set email content
                        $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
                        //
                        $this->load->library('encryption', 'encrypt');
                        //
                        $time = strtotime('+10 days');
                        //
                        $encryptedKey = $this->encrypt->encode($assignInsertId . '/' . $user_info['sid'] . '/applicant/' . $time);
                        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                        //
                        $user_info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($post['documentTitle']) . '</a>';
                        //
                        $subject = convert_email_template($template['subject'], $user_info);
                        $message = convert_email_template($template['text'], $user_info);
                        //
                        $body = $hf['header'];
                        $body .= $message;
                        $body .= $hf['footer'];
                        //
                        $this->hr_documents_management_model
                            ->updateAssignedDocumentLinkTime(
                                $time,
                                $assignInsertId
                            );
                        //
                        log_and_sendEmail(
                            FROM_EMAIL_NOTIFICATIONS,
                            $user_info['email'],
                            $subject,
                            $body,
                            $post['CompanyName']
                        );
                        break;
                }
            }


            //
            // Check if it's Authorize document
            if (isset($post['desc'], $post['managerList']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                // Managers handling
                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                    $post['managerList'],
                    $assignInsertId,
                    $post['CompanySid'],
                    $post['EmployerSid']
                );
                //
                $this->hr_documents_management_model->change_document_approval_status(
                    $assignInsertId,
                    [
                        'managersList' => $post['managerList']
                    ]
                );
            }

            //
            if (isset($post['reset']) && $post['reset'] == 'yes') {
                $a = [];
                $a['status'] = 1;
                $a['acknowledged'] = NULL;
                $a['acknowledged_date'] = NULL;
                $a['downloaded'] = NULL;
                $a['downloaded_date'] = NULL;
                $a['uploaded'] = NULL;
                $a['uploaded_date'] = NULL;
                $a['uploaded_file'] = NULL;
                $a['signature_timestamp'] = NULL;
                $a['signature'] = NULL;
                $a['signature_email'] = NULL;
                $a['signature_ip'] = NULL;
                $a['user_consent'] = 0;
                $a['archive'] = 0;
                $a['submitted_description'] = NULL;
                $a['signature_base64'] = NULL;
                $a['signature_initial'] = NULL;
                $a['authorized_signature'] = NULL;
                $a['authorized_signature_by'] = NULL;
                $a['authorized_signature_date'] = NULL;

                //
                $this->hr_documents_management_model->updateAssignedDocument($assignInsertId, $a);
            }
        }

        echo 'success';
    }

    //
    function revoke_document()
    {
        $assigned = $this->hr_documents_management_model->getAssignedDocumentByIdAndEmployeeId(
            $this->input->post('sid', true),
            $this->input->post('employeeSid', true)
        );
        //
        $assignInsertId = $assigned['sid'];
        //
        unset($assigned['sid']);
        unset($assigned['is_pending']);
        //
        $h = $assigned;
        $h['doc_sid'] = $assignInsertId;
        //
        $this->hr_documents_management_model->insert_documents_assignment_record_history($h);
        //
        $this->hr_documents_management_model->revokeDocument(
            $this->input->post('sid', true),
            $this->input->post('employeeSid', true),
            [
                'status' => '0'
            ]
        );
        //
        echo 'success';
    }


    /**
     * Download all documents for applicant(s) and employee(s)
     *
     * $type           String employee|applicant
     * $id             Int
     * $documentType   String completed|not_completed
     * token           String token
     */
    function download($type, $id, $documentType, $token = null, $company_sid = 0)
    {
        //
        if ($company_sid == 0) {
            // When their is no session
            if (!$this->session->userdata('logged_in')) exit(0);
            //
            $data = $this->session->userdata('logged_in');
            //
            $session = $this->session->userdata('logged_in');
            //
            $company_sid = $session['company_detail']['sid'];
        }

        //
        $documents = [];
        // 
        if ($type == 'applicant') {
            // Get employee documents
            if ($documentType == 'completed' || $documentType == 'AllCompletedDocument') {
                $documents = $this->hr_documents_management_model->getApplicantCompletedDocuments(
                    $company_sid,
                    $id
                );
            } else if ($documentType == 'noActionRequired') {
                $documents = $this->hr_documents_management_model->getUserNoActionDocuments(
                    $company_sid,
                    $id,
                    "applicant"
                );
            }
            //
            $data['userInfo'] = $this->hr_documents_management_model->get_applicant_information(
                $company_sid,
                $id
            );
        } else if ($type == 'employee') { // For Employees
            // Get employee documents

            if ($documentType == 'completed' || $documentType == 'AllCompletedDocument') {
                $documents = $this->hr_documents_management_model->getEmployeeCompletedDocuments(
                    $company_sid,
                    $id
                );
            } else if ($documentType == 'noActionRequired') {
                $documents = $this->hr_documents_management_model->getUserNoActionDocuments(
                    $company_sid,
                    $id,
                    "employee"
                );
            }
            //
            $data['userInfo'] = $this->hr_documents_management_model->get_employee_information(
                $company_sid,
                $id
            );
        } else exit(0);
        //
        $data['documents'] = $documents;
        //
        $data['user_sid'] = $id;
        $data['user_type'] = $type;
        $data['company_sid'] = $company_sid;
        $data['token'] = $token == null || $token == 0 ? time() : $token;
        //

        $this->load->view('download_bulk_documents', $data);
    }

    /**
     * Upload document to a single folder 
     * and merge pdfs in case of hybrid document
     * 
     * TODO: Merge PDFs in case of hybrid document
     *       PDFTK is not working wth exec, shell_exec, passthru
     *       sytem. FPDPI is breaking on merging compress files as
     *       this fetaure is available on paid version.
     *       /usr/bin/pdftk inputfile output outputfile uncompress
     *
     * $token           String Name of base folder
     * $employeeSid     Int    Refers to employee and applicant
     * $data            Array  Contains the document
     * 
     * @return void
     */
    function upload()
    {
        //
        $post = $this->input->post(NULL, FALSE);
        //
        $dir = ROOTPATH . 'temp_files/employee_export/' . $post['token'] . '/' . $post['userFullNameSlug'] . '/';
        //
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        //
        //
        if (isset($post['typo']) && $post['typo'] == 'document') {
            //
            // Uploaded/Generated documents
            // Download Hybrid Document
            if (isset($post['data']['content'], $post['data']['file'])) {
                // Generated document
                $pathWithFile = $dir . time() . '_' . $post['data']['title'] . '1.pdf';
                $f = fopen($pathWithFile, 'w');
                fwrite($f, base64_decode(str_replace('data:application/pdf;base64,', '', $post['data']['content']), true));
                fclose($f);
                // Uploaded Document
                // For Generated documents
                downloadFileFromAWS(
                    getFileName(
                        $dir . time() . '_' . $post['data']['title'] . '2.pdf',
                        AWS_S3_BUCKET_URL . $post['data']['file']
                    ),
                    AWS_S3_BUCKET_URL . $post['data']['file']
                );
            } else if (isset($post['data']['content'])) {
                // Generated document
                $pathWithFile = $dir . time() . '_' . $post['data']['title'] . '.pdf';
                $f = fopen($pathWithFile, 'w');
                fwrite($f, base64_decode(str_replace('data:application/pdf;base64,', '', $post['data']['content']), true));
                fclose($f);
            } else if (isset($post['data']['s3_filename'])) {
                //
                $file_info = pathinfo($post['data']['s3_filename']);
                $extension = strtolower($file_info['extension']);
                //
                $this->load->library("aws_lib");
                $this
                    ->aws_lib
                    ->get_object(
                        AWS_S3_BUCKET_NAME,
                        $post['data']['s3_filename'],
                        $dir . time() . '_' . (preg_replace('/[^a-zA-Z0-9-_.]/', '_', $post['data']['orig_filename'])) . '.' . $extension
                    );
                // For Generated documents
                // downloadFileFromAWS(
                //     getFileName(
                //         $dir . time() . '_' . $post['data']['orig_filename'],
                //         AWS_S3_BUCKET_URL . $post['data']['s3_filename']
                //     ),
                //     AWS_S3_BUCKET_URL . $post['data']['s3_filename']
                // );
            }

            if ($post['type'] == 'I9' || $post['type'] == 'W9' || $post['type'] == 'W4') {
                $employee_sid = $post['employeeSid'];
                $supporting_DOC = $this->hr_documents_management_model->get_varification_supporting_document($post['employeeSid'], $post['type']);
                //
                if (!empty($supporting_DOC)) {
                    $dir = ROOTPATH . 'temp_files/employee_export/' . $post['token'] . '/' . $post['userFullNameSlug'] . '/suporting_documents/' . $post['type'] . '/';
                    //
                    if (!is_dir($dir)) mkdir($dir, 0777, true);
                    //
                    foreach ($supporting_DOC as $SD) {
                        downloadFileFromAWS(
                            getFileName(
                                $dir . time() . '_' . $SD['document_name'],
                                AWS_S3_BUCKET_URL . $SD['s3_filename']
                            ),
                            AWS_S3_BUCKET_URL . $SD['s3_filename']
                        );
                    }
                }
            }
        } else {
            // Verification documents
            $pathWithFile = $dir . time() . '_' . $post['type'] . '.pdf';
            $f = fopen($pathWithFile, 'w');
            fwrite($f, base64_decode(str_replace('data:application/pdf;base64,', '', $post['data']), true));
            fclose($f);
        }
    }

    /**
     * Generates a zip and download
     *
     * $token  String Name of base folder
     * $id     Int    Refers to employee and applicant
     */
    function generate_zip($token, $id = 0, $user_sid = 0, $user_type = 'employee', $company_sid = 0)
    {
        //
        $id = urldecode($id);
        //
        ini_set('memory_limit', '-1');
        //
        if (preg_match('/.zip/', $token)) {
            //
            $dt = APPPATH . '../temp_files/employee_export/' . $token;
            //
            $strFile = file_get_contents($dt);
            //
            header("Content-type: application/force-download");
            header('Content-Disposition: attachment; filename="' . $token . '"');

            header('Content-Length: ' . filesize($dt));
            echo $strFile;
            while (ob_get_level()) {
                ob_end_clean();
            }
            readfile($dt);
            exit;
        }
        //
        $this->load->library('zip');
        //
        if ($id == '0') {
            //

            $dir = ROOTPATH . 'temp_files/employee_export/' . $token . '/';
            //
            if (!is_dir($dir)) exit(0);
            //
            // $download_file = 'bulk_documents.zip';
            // //
            // $ndir =  ROOTPATH.'temp_files/employee_export/Bulk Documents/';
            //
            // if(is_dir($ndir)) deleteFolderWithFiles($ndir);
            $fnn = date('Y-m-d-H-i-s') . "_" . $company_sid . "_bulk_download.zip";
            //
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['user_sid'] = 0;
            $data_to_insert['user_type'] = $user_type;
            $data_to_insert['download_type'] = 'bulk_download';
            $data_to_insert['folder_name'] = $fnn;
            //
            $this->hr_documents_management_model->save_documents_download_history($data_to_insert);
            $dt = ROOTPATH . 'temp_files/employee_export/' . $fnn;
            //
            unlink($dt);
            //
            shell_exec("cd $dir; zip -r $dt *");
            //
            // rename( ROOTPATH.'temp_files/employee_export/'.$token.'/', $dir);
            // $dir = $ndir;
            $strFile = file_get_contents($dt);
            //
            header("Content-type: application/force-download");
            header('Content-Disposition: attachment; filename="' . $fnn . '"');

            header('Content-Length: ' . filesize($dt));
            echo $strFile;
            while (ob_get_level()) {
                ob_end_clean();
            }
            readfile($dt);
            exit;
            // $this->zip->read_dir($dir, false);
        } else {

            //
            $dir = ROOTPATH . 'temp_files/employee_export/' . $token . '/';
            //
            if (!is_dir($dir)) exit(0);
            //
            $download_file = $company_sid . "_" . (preg_replace('/[^a-zA-Z]/', '_', $id)) . '.zip';
            //
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['user_sid'] = $user_sid;
            $data_to_insert['user_type'] = $user_type;
            $data_to_insert['download_type'] = 'single_download';
            $data_to_insert['folder_name'] = $download_file;
            //
            $this->hr_documents_management_model->save_documents_download_history($data_to_insert);
            //
            $dt = ROOTPATH . 'temp_files/employee_export/' . $download_file;
            //
            if (is_file($dt)) {
                unlink($dt);
            }
            //
            shell_exec("cd $dir; zip -r $dt *");
            //
            redirect('download_document_zip/' . $download_file, 'auto');
        }

        $this->zip->download($download_file);
    }

    /**
     * Export bulk all documents for applicant(s) and employee(s)
     *
     * $type           String employee|applicant
     */
    function export_documents(
        $type
    ) {
        // When their is no session
        if (!$this->session->userdata('logged_in')) exit(0);
        ini_set('memory_limit', -1);
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

        $company_sid = $data['session']['company_detail']['sid'];
        $company_name = $data['session']['company_detail']['CompanyName'];
        $employer_sid = $data['session']['employer_detail']['sid'];
        $employer_email = $data['session']['employer_detail']['email'];
        $employer_first_name = $data['session']['employer_detail']['first_name'];
        $employer_last_name = $data['session']['employer_detail']['last_name'];
        $active_groups = array();
        $in_active_groups = array();
        $group_ids = array();
        $group_docs = array();
        $document_ids = array();

        $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, 0, $type, 'bulk_download');

        // Get all completed documents
        $data['documents'] = $this->hr_documents_management_model->GetCompletedDocumentsWithEmployees($company_sid);

        $data['title'] = 'Export Bulk Documents - ' . $type;
        $data['type'] = $type;
        $data['company_sid'] = $company_sid;
        $data['company_name'] = $company_name;
        $data['employer_sid'] = $employer_sid;
        $data['employer_email'] = $employer_email;
        $data['employer_last_name'] = $employer_last_name;
        $data['employer_first_name'] = $employer_first_name;
        //
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/bulk_export_documents');
        $this->load->view('main/footer');
    }

    /**
     * Get documents for applicant(s) and employee(s)
     *
     * $id             Int
     * $type           String employee|applicant
     */
    function getDocuments($id, $type = 'employee', $documentIds = '')
    {
        //
        $data = $this->session->userdata('logged_in');
        //
        $documents = [];
        // 
        if ($type == 'applicant') {
            $documents = $this->hr_documents_management_model->getApplicantCompletedDocuments(
                $data['company_detail']['sid'],
                $id
            );
        } else if ($type == 'employee') { // For Employees
            // Get employee documents
            $documents = $this->hr_documents_management_model->getEmployeeCompletedDocuments(
                $data['company_detail']['sid'],
                $id,
                $documentIds
            );
            //
            if (!empty($documentIds)) {
                //
                $documents['W4'] = '';
                $documents['TI9'] = '';
                $documents['TW9'] = '';
                $documents['TW4'] = '';
            } else {
                //
                $documents['W4']['sent_date'] = !isset($documents['W4']['sent_date']) ? date('Y-m-d') : $documents['W4']['sent_date'];
                //
                $assign_on = date("Y-m-d", strtotime($documents['W4']['sent_date']));
                $compare_date = date("Y-m-d", strtotime('2020-01-06'));
                //
                $documents['TI9'] = count($documents['I9']) ? $this->load->view('form_i9/form_i9_pdf_template', ['pre_form' => $documents['I9']], true) : '';
                $documents['TW9'] = count($documents['W9']) ? $this->load->view('form_w9/form_w9_pdf_template', ['pre_form' => $documents['W9']], true) : '';
                $documents['TW4'] = count($documents['W4']) ? $this->load->view('form_w4/' . ($assign_on >= $compare_date ? "form_w4_2020_pdf_template" : "form_w4_pdf_template") . '', ['pre_form' => $documents['W4']], true) : '';
            }
        }
        //
        echo json_encode($documents);
    }

    /**
     * 
     */
    function getSubmittedDocument($document_sid, $request_type, $request_from, $letter_request = NULL)
    {
        $form_input_data = "NULL";
        $is_iframe_preview = 1;

        $document = $this->hr_documents_management_model->get_requested_authorized_content($document_sid, $request_from);
        $requested_content = $this->hr_documents_management_model->get_requested_content($document_sid, $request_type, $request_from, 'P&D', 1);
        $file_name = $this->hr_documents_management_model->get_document_title($document_sid, $request_type, $request_from);

        if ($letter_request == 1) {
            $requested_content = $document['submitted_description'];
        } else if (!empty($document['form_input_data']) && $request_type == 'submitted') {
            $is_iframe_preview = 0;
            if (!empty($document['authorized_signature'])) {
                $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
            } else {
                $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            }

            if (!empty($document['authorized_signature_date'])) {
                $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
            } else {
                $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            }

            $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
            $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
            $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);

            // $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            // $requested_content = $document_content;

            $form_input_data = unserialize($document['form_input_data']);
            $form_input_data = json_encode(json_decode($form_input_data, true));
        } else if ($request_type == 'submitted') {
            if (preg_match('/data:application\/pdf;base64,/', $document['submitted_description'])) {
                echo $document['submitted_description'];
                exit(0);
            }
            if (empty($document['submitted_description'])) {
                $requested_content =  $document['submitted_description'] = $document['document_description'];
                $is_iframe_preview = 0;
                //
            }
        } else {
            if ($request_type == 'assigned') {
                // if (empty($document['submitted_description']) && empty($document['form_input_data'])) {    
                $is_iframe_preview = 0;
            }

            $form_input_data = json_encode(json_decode('assigned'));
        }


        $data = array();
        $data['file_name'] = $file_name;
        $data['document'] = $document;
        $data['request_type'] = $request_type;
        $data['document_contant'] = $requested_content;
        $data['form_input_data'] = $form_input_data;
        $data['is_iframe_preview'] = $is_iframe_preview;
        //
        //Create a new DOMDocument object.
        // $htmlDom = new DOMDocument;

        // //Load the HTML string into our DOMDocument object.
        // @$htmlDom->loadHTML($data['document_contant']);

        // //Extract all img elements / tags from the HTML.
        // $imageTags = $htmlDom->getElementsByTagName('img');

        // //Create an array to add extracted images to.
        // $extractedImages = array();

        // //Loop through the image tags that DOMDocument found.
        // foreach($imageTags as $imageTag){
        //     //Get the src attribute of the image.
        //     $imgSrc = $imageTag->getAttribute('src');

        //     //Get the alt text of the image.
        //     $altText = $imageTag->getAttribute('alt');

        //     //Get the title text of the image, if it exists.
        //     $titleText = $imageTag->getAttribute('title');

        //     //Add the image details to our $extractedImages array.
        //     $extractedImages[] = array(
        //         'src' => $imgSrc,
        //         'alt' => $altText,
        //         'title' => $titleText
        //     );
        // }


        //
        $form_input_data = unserialize($document['form_input_data']);
        $form_input_data = json_decode($form_input_data, true);
        //
        $html = $this->load->view('hr_documents_management/new_generated_document_action_page_template_new', $data, true);
        //
        $result['html'] = $html;
        $result['input_data'] = $form_input_data;
        //
        echo json_encode($result);
        //
    }

    //
    function send_document_to_sign()
    {
        // Set default error
        $resp = [
            'Status' => false,
            'Response' => 'Invalid request.'
        ];
        // Verfify the session
        $data = $this->session->userdata('logged_in');
        // Save sanatized post
        $post = $this->input->post(NULL, TRUE);
        // TOBE delete after testing
        // $post['assignedDocumentSid'] = $sid;
        // If not a post request
        if (!count($post)) $this->res($resp);
        // Verify document
        $document = $this->hr_documents_management_model->verifyAssignedDocument(
            $post['assignedDocumentSid'],
            $data['company_detail']['sid']
        );
        //
        if (!$document) {
            $resp['Response'] = 'Failed to verify the assigned document.';
            $this->res($resp);
        }
        //
        // Get Email header and footer
        $hf = message_header_footer(
            $data['company_detail']['sid'],
            $data['company_detail']['CompanyName']
        );
        // Set email content
        $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
        //
        $this->load->library('encryption', 'encrypt');
        //
        $time = strtotime('+10 days');
        //
        $encryptedKey = $this->encrypt->encode($post['assignedDocumentSid'] . '/' . $document['user_sid'] . '/' . $document['user_type'] . '/' . $time);
        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
        //
        $this->load->model('Hr_documents_management_model', 'HRDMM');
        if ($this->HRDMM->isActiveUser($document['user_sid'], $document['user_type'])) {
            //
            $user_info = $this->hr_documents_management_model->getUserData(
                $document['user_sid'],
                $document['user_type'],
                $data['company_detail']['sid']
            );
            //
            $user_info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($document['document_title']) . '</a>';
            //
            $subject = convert_email_template($template['subject'], $user_info);
            $message = convert_email_template($template['text'], $user_info);
            //
            $body = $hf['header'];
            $body .= $message;
            $body .= $hf['footer'];
            //
            $this->hr_documents_management_model
                ->updateAssignedDocumentLinkTime(
                    $time,
                    $post['assignedDocumentSid']
                );
            //
            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $document['user']['email'],
                $subject,
                $body,
                $data['company_detail']['CompanyName']
            );
        }
        //
        $resp['Status'] = TRUE;
        $resp['Response'] = 'The document has been sent successfully.';
        //
        $this->res($resp);
    }


    function fetchEmployees()
    {
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $employees = $this->hr_documents_management_model->fetchEmployeesByCompanyId($companyId);
        //
        if (!$employees) {
            $return_array['Response'] = 'No employees found.';
            $this->response($return_array);
        }
        //
        $return_array['Data'] = $employees;
        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Proceed..';
        $this->res($return_array);
    }

    public function check_complete_document_send_email($company_sid, $employee_sid)
    {
        if ($this->session->userdata('logged_in')) {
            $session = $this->session->userdata('logged_in');
            $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employee_sid, 0);
            // $assigned_offer_letter = $this->dashboard_model->get_assigned_offer_letter($company_id, 'employee', $employer_id);
            // $is_w4_assign = $this->dashboard_model->check_w4_form_exist('employee', $employer_id);
            // $is_w9_assign = $this->dashboard_model->check_w9_form_exist('employee', $employer_id);
            // $is_i9_assign = $this->dashboard_model->check_i9_exist('employee', $employer_id);
            $documents_count = sizeof($assigned_documents);

            $uncomplete_documents_count = 0;

            // if (!empty($is_w4_assign)) {
            //     $uncomplete_documents_count++;
            // }

            // if (!empty($is_w9_assign)) {
            //     $uncomplete_documents_count++;
            // }

            // if (!empty($is_i9_assign)) {
            //     $uncomplete_documents_count++;
            // }

            // if (!empty($assigned_offer_letter)) {
            //     $uncomplete_documents_count++;
            // }

            $completedTitles = [];

            foreach ($assigned_documents as $key => $assigned_document) {
                //
                $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                //
                if ($assigned_document['archive'] == 0) {
                    $is_magic_tag_exist = 0;
                    $is_document_completed = 0;

                    if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                        $document_body = $assigned_document['document_description'];
                        // $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                        $magic_codes = array('{{signature}}', '{{inital}}');

                        if (str_replace($magic_codes, '', $document_body) != $document_body) {
                            $is_magic_tag_exist = 1;
                        }
                    }

                    if ($assigned_document['document_type'] != 'offer_letter') {
                        if ($assigned_document['status'] == 1) {
                            if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) && $assigned_document['archive'] == 0) {

                                if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                    if ($is_magic_tag_exist == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1) {
                                    if ($assigned_document['acknowledged'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1) {
                                    if ($assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($is_magic_tag_exist == 1) {
                                    if ($assigned_document['user_consent'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                }

                                if ($is_document_completed > 0) {

                                    $completedTitles[] = $assigned_document['document_title'];

                                    if (!empty($assigned_document['uploaded_file']) || !empty($assigned_document['submitted_description'])) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        // $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else {
                                        $completed_document_sids[] = $assigned_document['document_sid'];
                                        // $completed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                    $completed_sids[] = $assigned_document['document_sid'];
                                    $signed_documents[] = $assigned_document;
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                }
                            } else { // nothing is required so it is "No Action Required Document"
                                if ($is_document_authorized == 1) {
                                    //
                                    if ($authorized_sign_status == 1) {
                                        if ($assigned_document['pay_roll_catgory'] == 0) {
                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $signed_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $completed_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    } else {
                                        $uncompleted_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                    //
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                    //
                                } else {
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                    $no_action_required_sids[] = $assigned_document['document_sid'];
                                    $no_action_required_documents[] = $assigned_document;
                                    unset($assigned_documents[$key]);
                                }
                            }
                        } else {
                            $revoked_sids[] = $assigned_document['document_sid'];
                        }
                    }
                } else if ($assigned_document['archive'] == 1) {
                    unset($assigned_documents[$key]);
                }
            }

            $uncomplete_documents_count = $uncomplete_documents_count + sizeof($assigned_documents);

            if ($uncomplete_documents_count == 0) {

                //Send document completion alert
                broadcastAlert(
                    DOCUMENT_NOTIFICATION_ACTION_TEMPLATE,
                    'general_information_status',
                    'document_completed',
                    $company_sid,
                    $session['company_detail']['CompanyName'],
                    $session['employer_detail']['first_name'],
                    $session['employer_detail']['last_name'],
                    $employee_sid,
                    [
                        'completedDocTitles' => $completedTitles
                    ]
                );
            }
        }
    }

    //
    function gpd(
        $action,
        $documentType,
        $userType,
        $userSid
    ) {
        //
        $template = '';
        $userData = $this->hr_documents_management_model->getUserDataWithCompany($userSid, $userType);
        //
        switch ($documentType) {
            case "dependents":
                //
                $this->load->model('dependents_model');
                //
                $data = $this->dependents_model->get_dependant_info($userType, $userSid);
                //
                if (count($data)) {
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];

                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $template = $this->load->view('hr_documents_management/templates/dependents', ['data' => $data, 'cs' => $d], true);
                }
                break;
                //
            case "emergency_contacts":
                //
                $this->load->model('emergency_contacts_model');
                //
                $data = $this->emergency_contacts_model->get_emergency_contacts($userType, $userSid);
                //
                if (count($data)) {
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];

                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $template = $this->load->view('hr_documents_management/templates/emergency_contacts', ['data' => $data, 'cs' => $d], true);
                }
                break;
                //
            case "drivers_license":
                //
                $this->load->model('dashboard_model');
                //
                $data = $this->dashboard_model->get_license_info($userSid, $userType, 'drivers');
                //
                if (count($data)) {
                    //
                    $template = $this->load->view('hr_documents_management/templates/drivers_license', ['data' => $data], true);
                }
                break;
                //
            case "occupational_license":
                //
                $this->load->model('dashboard_model');
                //
                $data = $this->dashboard_model->get_license_info($userSid, $userType, 'occupational');
                //
                if (count($data)) {
                    //
                    $template = $this->load->view('hr_documents_management/templates/occupational_license', ['data' => $data], true);
                }
                break;
                //
            case "direct_deposit":
                //
                $this->load->model('direct_deposit_model');
                $data['users_type'] = $userType;
                $data['users_sid'] = $userSid;
                $data['type'] = 'prints';
                $employee_number = $this->direct_deposit_model->get_user_extra_info($userType, $userSid, isset($userData['employer_sid']) ? $userData['employer_sid'] : $userData['parent_sid']);
                $data['employee_number'] = $employee_number;
                $data['data'] = $this->direct_deposit_model->getDDI($userType, $userSid, isset($userData['employer_sid']) ? $userData['employer_sid'] : $userData['parent_sid']);

                if (empty($data['employer_number']) && !empty($data['data'][0]['employee_number'])) {
                    $data['employee_number'] = $data['data'][0]['employee_number'];
                }
                //
                $data['data'][0]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][0]['voided_cheque']));
                if (isset($data['data'][1])) $data['data'][1]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][1]['voided_cheque']));

                $data[$userType] = $data['cn'] = $this->direct_deposit_model->getUserData($userSid, $userType);
                //
                $template = $this->load->view('direct_deposit/pd', $data, true);
                break;
        }
        //

        $data['template'] = $template;
        $data['action'] = $action;
        $data['userData'] = $userData;
        $data['userType'] = $userType;
        $data['documentType'] = $documentType;
        //
        $this->load->view('hr_documents_management/templates/pd', $data);
    }

    //
    function getGeneralDocument(
        $documentType,
        $userSid,
        $userType
    ) {
        echo
        $this->hr_documents_management_model->getGeneralDocument(
            $userSid,
            $userType,
            $documentType
        );
    }


    //
    function categoryManager(
        $type,
        $documentSid = 0,
        $assignedDocumentSid = 0
    ) {
        //
        $ses = $this->session->userdata(
            'logged_in'
        );
        //
        if ($type == 'get') {
            $this->res(
                $this->hr_documents_management_model->getAllCompanyCategories(
                    $ses['company_detail']['sid']
                )
            );
        }
        //
        if ($type == 'single') {
            $this->res(
                $this->hr_documents_management_model->getSingleDocumentCategories(
                    $ses['company_detail']['sid'],
                    $documentSid,
                    $assignedDocumentSid
                )
            );
        }
        //
        if ($type == 'update') {
            $this->res(
                $this->hr_documents_management_model->updateDocumentCategories(
                    $documentSid,
                    $assignedDocumentSid,
                    $this->input->post('cats')
                )
            );
        }
    }


    function add_new_category_from_cm()
    {
        //
        $session = $this->session->userdata('logged_in');
        //
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        //
        if (!$this->hr_documents_management_model->checkCategoryName(null, $company_sid)) {
            echo 'error';
            return;
        }
        //
        $category_name = $this->input->post('name');
        $category_description = $this->input->post('description');
        $category_status = $this->input->post('status');
        $category_sort_order = $this->input->post('sort_order');
        $ip_address = $this->input->post('ip_address');
        $data_to_insert = array();
        $new_history_data = array();
        $category_description = htmlentities($category_description);

        if (empty($category_sort_order)) {
            $category_sort_order = 0;
        }

        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['name'] = $category_name;
        $data_to_insert['description'] = $category_description;
        $data_to_insert['status'] = $category_status;
        $data_to_insert['sort_order'] = $category_sort_order;
        $data_to_insert['created_by_sid'] = $employer_sid;
        $data_to_insert['ip_address'] = $ip_address;
        $insert_id = $this->hr_documents_management_model->insert_category_record($data_to_insert);
        // Tracking History For New Inserted Doc in new history table
        $new_history_data['category_sid'] = $insert_id;
        $new_history_data['company_sid'] = $company_sid;
        $new_history_data['name'] = $category_name;
        $new_history_data['description'] = $category_description;
        $new_history_data['status'] = $category_status;
        $new_history_data['sort_order'] = $category_sort_order;
        $new_history_data['updated_by_sid'] = $employer_sid;
        $new_history_data['ip_address'] = $ip_address;
        $this->hr_documents_management_model->insert_category_history($new_history_data);
        //
        echo 'success';
    }

    //
    function set_schedule_document()
    {
        //
        $data_to_update = [];
        // Assign & Send document
        // Set
        $aType = $this->input->post('assignAndSendDocument', true);
        $aDate = $this->input->post('assignAndSendCustomDate', true);
        $aDay = $this->input->post('assignAndSendCustomDay', true);
        $aTime = $this->input->post('assignAndSendCustomTime', true);
        $aEmployees = $this->input->post('assignAdnSendSelectedEmployees', true);
        //
        $data_to_update['assign_type'] = $aType;
        $data_to_update['assign_date'] = $aDate;
        $data_to_update['assign_time'] = $aTime;
        //
        if ($aType == 'custom' && empty($aDate) && empty($aTime)) $data_to_update['assign_type'] = 'none';
        //
        if (empty($aDate) && empty($aDay)) $data_to_update['assign_date'] = null;
        if (empty($aTime)) $data_to_update['assign_time'] = null;
        //
        if ($aType == 'weekly' && !empty($aDay)) $data_to_update['assign_date'] = $aDay;
        if ($aType == 'weekly' && empty($aDay)) $data_to_update['assign_date'] = null;
        //
        if ($aEmployees && count($aEmployees)) {
            //
            if (in_array('-1', $aEmployees)) $data_to_update['assigned_employee_list'] = 'all';
            else $data_to_update['assigned_employee_list'] = json_encode($aEmployees);
        }

        $this->hr_documents_management_model->update_documents($this->input->post('documentSid', true), $data_to_update, 'documents_management');

        echo 'success';
        exit(0);
    }

    public function scheduled_documents()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $data['security_details'] = $security_details = db_get_access_level_details($data['session']['employer_detail']['sid']);
            //
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all
            //
            $data['company_sid'] = $company_sid = $data['session']['company_detail']['sid'];
            $data['company_name'] = $company_name = $data['session']['company_detail']['CompanyName'];
            $data['employer_sid'] = $employer_sid = $data['session']['employer_detail']['sid'];
            $data['employer_email'] = $employer_email = $data['session']['employer_detail']['email'];
            $data['employer_first_name'] = $employer_first_name = $data['session']['employer_detail']['first_name'];
            $data['employer_last_name'] = $employer_last_name = $data['session']['employer_detail']['last_name'];
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid);
            //
            $data['title'] = 'Scheduled Document(s)';
            //
            $this->load->view('main/header', $data);
            $this->load->view('hr_documents_management/scheduled_documents');
            $this->load->view('main/footer');
        } else {
            redirect('login', 'refresh');
        }
    }


    //
    function get_scheduled_documents(
        $employee
    ) {
        //
        $documents = $this->hr_documents_management_model->getScheduledDocuments(
            $employee,
            $this->session->userData('logged_in')['company_detail']['sid']
        );

        $this->res($documents);
    }


    //
    function get_scheduled_documents_employee(
        $documentSid
    ) {
        //
        $documents = $this->hr_documents_management_model->getScheduledDocumentsWithEmployees(
            $documentSid,
            $this->session->userData('logged_in')['company_detail']['sid']
        );

        $this->res($documents);
    }

    public function upload_file_ajax_handler()
    {

        $company_sid = $this->input->post('company_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        $document_title = $this->input->post('document_title');
        $original_name = $_FILES['document']['name'];

        $valid_extension = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'rtf', 'ppt', 'xls', 'xlsx', 'csv');
        // echo '<pre>';
        // print_r($original_name);
        $file_info = pathinfo($original_name);
        $extension = strtolower($file_info['extension']);
        // echo $file_info['extension'].'<br>';

        //                     // if (isset($file_info['extension'])) {
        //                     //     $data_to_update['uploaded_document_extension'] = $file_info['extension'];
        //                     // }


        if (in_array($extension, $valid_extension)) {
            $pictures = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $user_sid, AWS_S3_BUCKET_NAME);

            if (!empty($pictures) && $pictures != 'error') {
                $return_data['upload_status'] = 'success';
                $return_data['document_url'] = $pictures;
                $return_data['original_name'] = $original_name;
                $return_data['extension'] = $extension;

                echo json_encode($return_data);
            } else {
                $return_data['upload_status'] =  'error';
                $return_data['reason'] =  'Something went wrong, Please try again!';
                echo json_encode($return_data);
            }
        } else {
            $return_data['upload_status'] =  'error';
            $return_data['reason'] =  'Upload document type is not valid';
            echo json_encode($return_data);
        }
    }

    public function add_history_documents($user_type, $user_sid)
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $data['security_details'] = $security_details = db_get_access_level_details($security_sid);
        //
        check_access_permissions($security_details, 'hr_documents_management', 'add_history_documents'); // Param2: Redirect URL, Param3: Function Name
        //
        $company_sid  = $data['session']['company_detail']['sid'];
        $data['company_sid']  = $company_sid;

        $data['company_name'] = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];

        switch ($user_type) {
            case 'employee':
                $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                    redirect('employee_management', 'refresh');
                }

                $data = employee_right_nav($user_sid, $data);
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';

                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'employee'); // getting applicant ratings - getting average rating of applicant
                $data['employer'] = $this->hr_documents_management_model->get_company_detail($user_sid);
                $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                $data['eeo_form_info'] = $eeo_form_info;

                $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $user_sid, $user_type, 'single_download');

                $data['eeo_form_info'] = $eeo_form_info;
                $data['user_info'] = $user_info;
                $data['title'] = 'Assign Bulk History Documents';
                $data['left_navigation'] = $left_navigation;
                break;
            case 'applicant':
                $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $user_sid);

                if (empty($user_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                    redirect('application_tracking_system/active/all/all/all/all', 'refresh');
                }

                $data = applicant_right_nav($user_sid, NULL);
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $applicant_info = $this->hr_documents_management_model->get_applicants_details($user_sid);
                $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid);
                $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid, $user_type);
                $data['eeo_form_status'] = $eeo_form_status;
                $data['eeo_form_info'] = $eeo_form_info;

                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => ucwords($user_type)
                );

                $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;
                $data['company_sid'] = $company_sid;
                $data['employer_sid'] = $applicant_info['sid'];

                $data['downloadDocumentData'] = $this->hr_documents_management_model->get_last_download_document_name($company_sid, $user_sid, $user_type, 'single_download');

                $data['user_info'] = $user_info;
                $data['title'] = 'Assign Bulk History Documents';
                $data['left_navigation'] = $left_navigation;
                break;
        }


        $data['user_type'] = $user_type;
        $data['user_sid'] = $user_sid;
        $data['active_categories'] = $this->hr_documents_management_model->getAllCategories($data['company_sid'], 1);

        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/assign_bulk_document');
        $this->load->view('main/footer');
    }


    /**
     * 
     */
    function update_form_settings()
    {
        //
        $data = ['is_required' => $_POST['isRequired'], 'is_signature_required' => $_POST['isSignatureRequired']];
        //
        if ($_POST['formType'] == 'i9') {
            //
            $this->db
                ->where('sid', $_POST['id'])
                ->update('applicant_i9form', $data);
        } else if ($_POST['formType'] == 'w9') {
            //
            $this->db
                ->where('sid', $_POST['id'])
                ->update('applicant_w9form', $data);
        } else if ($_POST['formType'] == 'w4') {
            //
            $this->db
                ->where('sid', $_POST['id'])
                ->update('form_w4_original', $data);
        }

        //
        echo 'success';
    }

    function company_varification_document()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $security_sid = $session['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        getCompanyEmsStatusBySid($company_sid);
        // For verification documents
        $companyEmployeesForVerification = $this->varification_document_model->getAllCompanyInactiveEmployee($session['company_detail']['sid']);
        $companyApplicantsForVerification = $this->varification_document_model->getAllCompanyInactiveApplicant($session['company_detail']['sid']);
        //
        $employee_pending_w4 = $this->varification_document_model->get_all_users_pending_w4($company_sid, 'employee', false, $companyEmployeesForVerification);
        $employee_pending_i9 = $this->varification_document_model->get_all_users_pending_i9($company_sid, 'employee', false, $companyEmployeesForVerification);
        $applicant_pending_w4 = $this->varification_document_model->get_all_users_pending_w4($company_sid, 'applicant', false, $companyApplicantsForVerification);
        $applicant_pending_i9 = $this->varification_document_model->get_all_users_pending_i9($company_sid, 'applicant', false, $companyApplicantsForVerification);

        //
        if ($session['employer_detail']['access_level_plus'] || $session['employer_detail'] == 'Admin') {
            $employee_pending = array_merge($employee_pending_w4, $employee_pending_i9);
            $applicant_pending = array_merge($applicant_pending_w4, $applicant_pending_i9);
        } else {
            $employee_pending = $this->varification_document_model->getPendingAuthDocs($company_sid, 'employee', false, $session['employer_detail'], $companyEmployeesForVerification);
            $applicant_pending = $this->varification_document_model->getPendingAuthDocs($company_sid, 'applicant', false, $session['employer_detail'], $companyApplicantsForVerification);
        }

        //
        function tempr($a, $b)
        {
            return $a['filled_date'] < $b['filled_date'];
        }
        //
        usort($employee_pending, 'tempr');
        usort($applicant_pending, 'tempr');
        //
        $data['session'] = $session;
        $data['company_sid'] = $company_sid;
        $data['security_details'] = $security_details;
        $data['title'] = 'Pending Employer Section For Verification Documents';
        $data['employee_pending'] = $employee_pending;
        $data['applicant_pending'] = $applicant_pending;
        $data['load_view'] = check_blue_panel_status(false, 'self');
        //
        $data['employee'] = $session['employer_detail'];
        //
        $this->load->view('main/header', $data);
        if ($data['load_view']) {
            $this->load->view('hr_documents_management/pending_varification_documents_ems');
        } else {
            $this->load->view('hr_documents_management/pending_varification_documents');
        }
        $this->load->view('main/footer');
    }


    //
    function send_eeoc_form()
    {
        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        if (!strtolower($this->input->method()) == 'post' || empty($this->input->post(NULL, TRUE))) {
            exit(0);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // 
        $id = $this->hr_documents_management_model->getEEOCId($post['userId'], $post['userType'], $post['userJobId'], $post['userLocation']);
        //
        $this->load->library('encryption');
        //
        $token = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($id)
        );
        //
        if ($post['userType'] == 'employee') {
            $info = $this->hr_documents_management_model->getEmployeeInfo($post['userId']);
        } else {
            $info = $this->hr_documents_management_model->getApplicantInfo($post['userId']);
        }
        //
        if (empty($info)) {
            echo 'The ' . ($post['userType']) . ' is assigned the EEO form, but since the ' . ($post['userType']) . ' has been marked as deactivated or terminated, the system is unable to send an email notification.';
            exit(0);
        }
        $this->load->model('Hr_documents_management_model', 'HRDMM');
        if ($this->HRDMM->isActiveUser($post['userId'], $post['userType'])) {
            //
            $hf = message_header_footer(
                $this->session->userdata('logged_in')['company_detail']['sid'],
                $this->session->userdata('logged_in')['company_detail']['CompanyName']
            );
            //
            $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
            //
            $info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('eeoc_form/' . ($token) . '')) . '">EEOC Form</a>';
            //
            $subject = convert_email_template($template['subject'], $info);
            $message = convert_email_template($template['text'], $info);
            //
            $body = $hf['header'];
            $body .= $message;
            $body .= $hf['footer'];
            //
            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $info['email'],
                $subject,
                $body,
                $this->session->userdata('logged_in')['company_detail']['CompanyName']
            );
        }
        //
        echo 'success';
        exit(0);
    }

    public function people_with_pending_employer_documents(
        $employees = 'all',
        $documents = 'all',
        $type = FALSE
    ) {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'pending_document'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Employees With Pending Documents';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $data["session"]["company_detail"]["CompanyName"];
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();
            $start = microtime(true);
            //
            $data['selectedEmployees'] = 'all';
            //
            if ($employees != 'all') {
                $employees = explode(':', $employees);
                //
                $data['selectedEmployees'] = $employees;
                //
                $data['selectedEmployees'] = array_flip($data['selectedEmployees']);
            }
            // Get employees list
            $data['employeesList'] = $this->hr_documents_management_model->getAllActiveEmployees($company_sid, false);

            // Get managers with pending authorize documents
            $data['pendingAD'] = $this->hr_documents_management_model->GetCompanyPendingAuthorizedDocuments($data['company_sid'], $employees);
            // Get managers with pending employer sections
            $this->load->model('varification_document_model');
            // For verification documents
            $companyEmployeesForVerification = $this->varification_document_model->getAllCompanyInactiveEmployee($company_sid);
            //
            $employee_pending_w4 = $this->varification_document_model->get_all_users_pending_w4($company_sid, 'employee', false, $companyEmployeesForVerification);
            $employee_pending_i9 = $this->varification_document_model->get_all_users_pending_i9($company_sid, 'employee', false, $companyEmployeesForVerification);
            $employee_pending = $this->varification_document_model->getPendingAuthDocs($company_sid, 'employee', false, [], $companyEmployeesForVerification);
            $pendingStateForms = $this->varification_document_model->getPendingStateForms(
                $company_sid,
                'employee',
                false,
                $companyEmployeesForVerification
            );
            //
            $data['managers'] = array_merge(
                $employee_pending_w4,
                $employee_pending_i9,
                $employee_pending,
                $pendingStateForms
            );

            if ($type == 'export') {
                ob_start();
                $h = array('Empoloyee Name', 'Document');
                $companyHeader = '';
                if (!empty($data['session']['company_detail']['CompanyName'])) {
                    $companyHeader = 'Company Name: ' . $data['session']['company_detail']['CompanyName'];
                }
                //
                $filename = date('m_d_Y_H_i_s', strtotime('now')) . "_managers_with_pending_document.csv";
                $fp = fopen('php://output', 'w');
                fputcsv($fp, array($companyHeader, ''));
                fputcsv($fp, $h);
                //
                foreach ($data['managers'] as $k => $v) {
                    $iText = '';

                    $d = array(getUserNameBySID($v['user_sid']), $v['document_name'] . " \n(Verification)");
                    fputcsv($fp, $d);
                }
                header('Content-type: application/csv');
                header('Content-Disposition: attachment; filename=' . $filename);
                ob_flush();
                exit;
            } else if ($type == 'print') {
                $this->load->view('hr_documents_management/print_new_people_with_pending_employer_documents', $data);
                return;
            } else if ($type == 'return') {
                header('Content-Type: application/json');
                echo json_encode($data['employees']);
                exit(0);
            }

            //
            $this->load->view('main/header', $data);
            $this->load->view('hr_documents_management/new_people_with_pending_employer_documents');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function get_verification_history_document($document_sid, $document_type)
    {
        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $html = '';
        $data = array();
        $name = '';
        //
        if ($document_type == 'W4_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "form_w4_original_history");
            $html = $this->load->view('form_w4/preview_w4_2020', $data, true);
            $name = 'W4 Fillable History';
        }
        //
        if ($document_type == 'W9_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "applicant_w9form_history");
            $html = $this->load->view('form_w9/index-pdf', $data, true);
            $name = 'W9 Fillable History';
        }
        //
        if ($document_type == 'I9_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "applicant_i9form_history");
            $data['section_access'] = "complete_pdf";
            //
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $html = $this->load->view('2022/federal_fillable/form_i9_preview_new', $data, true);
            } else {
                $html = $this->load->view('2022/federal_fillable/form_i9_preview', $data, true);
            }
            //
            $name = 'I9 Fillable History';
        }
        //
        if ($document_type == 'EEOC_Form') {
            $data["eeo_form_info"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "portal_eeo_form_history");
            $data['user_sid'] = $data['eeo_form_info']['application_sid'];
            $data['user_type'] = $data['eeo_form_info']['users_type'];
            $html = $this->load->view('eeo/eeoc_view_history', $data, true);
            $name = 'EEOC Fillable History';
        }
        //
        if ($document_type == 'user_document') {
            $document = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "documents_assigned_history");
            //
            if ($document['document_type'] == 'uploaded' || $document['offer_letter_type'] == 'uploaded') {

                $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                $document_file = pathinfo($document_filename);
                $document_extension = strtolower($document['document_extension']);

                //
                $t = explode('.', $document_filename);
                $de = $t[sizeof($t) - 1];
                //
                if ($de != $document_extension) $document_extension = $de;

                if (in_array($document_extension, ['csv'])) {
                    $html = '<iframe src="https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $html = '<img class="img-responsive" src="' . AWS_S3_BUCKET_URL . $document_filename . '"/>';
                } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) {
                    $html = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename) . '" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
                } else {
                    $html = '<iframe src="https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $document_filename) . '&embedded=true" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
                }
            } else {
                $html = '<iframe src="' . $document['submitted_description'] . '" frameborder="0" style="width: 100%; height: 500px;"></iframe>';
            }
            //  
            $name = 'Assigned Document History';
        }
        //
        $response = array();
        $response['status'] = TRUE;
        $response['name'] = $name;
        $response['html'] = $html;

        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }

    public function get_all_completed_document($document_sid, $document_type, $document_section)
    {

        //
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $html = '';
        $data = array();
        $name = '';
        //
        $data['section_access'] = $document_section;
        //
        if ($document_type == 'W4_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "form_w4_original");
            // $html = $this->load->view('form_w4/preview_w4_2020', $data, true);
            $assign_on = date("Y-m-d", strtotime($data["pre_form"]['sent_date']));
            $compare_date_2024 = date("Y-m-d", strtotime('2024-01-01'));

            if ($assign_on >= $compare_date_2024) {
                $html = $this->load->view('form_w4/preview_w4_2024', $data, true);
            } else {
                $html = $this->load->view('form_w4/preview_w4_2023', $data, true);
            }


            $name = 'W4 Fillable';
        }
        //
        if ($document_type == 'W9_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "applicant_w9form");
            $html = $this->load->view('form_w9/index-pdf', $data, true);
            $name = 'W9 Fillable';
        }
        //
        if ($document_type == 'I9_Form') {
            $data["pre_form"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "applicant_i9form");
            //
            if (!empty($data["pre_form"]["section1_preparer_or_translator"]) && empty($data["pre_form"]["section1_preparer_json"])) {
                $data["pre_form"]["section1_preparer_json"] = copyPrepareI9Json($data["pre_form"]);
            }
            //
            if (!empty($data["pre_form"]["section3_emp_sign"]) && empty($data["pre_form"]["section3_authorized_json"])) {
                $data["pre_form"]["section3_authorized_json"] = copyAuthorizedI9Json($data["pre_form"]);
            }
            //
            if (!empty($data["pre_form"]["version"]) && $data["pre_form"]["version"] == "2023") {
                $html = $this->load->view('2022/federal_fillable/form_i9_preview_new', $data, true);
            } else {
                $html = $this->load->view('2022/federal_fillable/form_i9_preview', $data, true);
            }
            //
            $name = 'I9 Fillable';
        }
        //
        if ($document_type == 'EEOC_Form') {
            $data["eeo_form_info"] = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "portal_eeo_form");
            $data['user_sid'] = $data['eeo_form_info']['application_sid'];
            $data['user_type'] = $data['eeo_form_info']['users_type'];
            $html = $this->load->view('eeo/eeoc_view_history', $data, true);
            $name = 'EEOC Fillable';
        }
        //
        if ($document_type == 'user_document') {
            $document = $this->hr_documents_management_model->getUserVarificationHistoryDoc($document_sid, "documents_assigned_history");
            //
            if ($document['document_type'] == 'uploaded' || $document['offer_letter_type'] == 'uploaded') {

                $document_filename = !empty($document['document_s3_name']) ? $document['document_s3_name'] : '';
                $document_file = pathinfo($document_filename);
                $document_extension = strtolower($document['document_extension']);

                //
                $t = explode('.', $document_filename);
                $de = $t[sizeof($t) - 1];
                //
                if ($de != $document_extension) $document_extension = $de;

                if (in_array($document_extension, ['csv'])) {
                    $html = '<iframe src="https://docs.google.com/gview?url=' . AWS_S3_BUCKET_URL . $document_filename . '&embedded=true" class="uploaded-file-preview"  style="width:100%; height:80em;" frameborder="0"></iframe>';
                } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                    $html = '<img class="img-responsive" src="' . AWS_S3_BUCKET_URL . $document_filename . '"/>';
                } else if (in_array($document_extension, ['doc', 'docx', 'xlsx', 'xlx', 'pptx', 'ppt'])) {
                    $html = '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode(AWS_S3_BUCKET_URL . $document_filename) . '" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
                } else {
                    $html = '<iframe src="https://docs.google.com/gview?url=' . (AWS_S3_BUCKET_URL . $document_filename) . '&embedded=true" class="uploaded-file-preview" style="width:100%; height:80em;" frameborder="0"></iframe>';
                }
            } else {
                $html = '<iframe src="' . $document['submitted_description'] . '" frameborder="0" style="width: 100%; height: 500px;"></iframe>';
            }
            //  
            $name = 'Assigned Document';
        }
        //
        $response = array();
        $response['status'] = TRUE;
        $response['name'] = $name;
        $response['html'] = $html;

        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }


    public function library_document_listing()
    {
        getCompanyEmsStatusBySid($this->session->userdata('logged_in')['company_detail']['sid']);
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect(base_url('login'), "refresh");
        }
        if (!checkIfAppIsEnabled('documentlibrary')) {
            return redirect(base_url('login'), "refresh");
        }
        //
        $data['session']          = $this->session->userdata('logged_in');
        $company_sid              = $data['session']['company_detail']['sid'];
        $employers_details        = $data['session']['employer_detail'];
        $employer_sid             = $employers_details['sid'];
        $security_details         = db_get_access_level_details($employer_sid);
        $data['security_details'] = $security_details;
        //
        $data['verificationDocuments'] = $this->hr_documents_management_model->getVerificationDocumentsForLibrary(
            $company_sid,
            $employer_sid,
            'employee'
        );
        //                    
        $documents_list = $this->hr_documents_management_model->get_all_paginate_library_documents($company_sid);
        $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, null, $documents_list, $data['session']['employer_detail']['access_level_plus']);

        $data['categories_documents'] = $categorized_docs['categories_no_action_documents'];

        $data['title']          = 'AutomotoHR :: Documents Library';
        $data['employer_sid']   = $employer_sid;
        $data['company_sid']   = $company_sid;
        $data['employer']       = $employer_sid;
        $data['employer']       = $employers_details;
        $data['documents_list'] = $documents_list;
        //
        $data['employee'] = $data['session']['employer_detail'];
        //
        $data['load_view'] = 'old';
        //
        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/library_document_listing');
        $this->load->view('main/footer');
    }

    /**
     *
     */
    function complete_library_document()
    {
        //
        $post = $this->input->post();
        $document_sid = $post['document_sid'];
        $employee_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];



        $documents_list = $this->hr_documents_management_model->is_library_document_exist($document_sid, $employee_sid, 'employee');

        if (!empty($documents_list)) {

            $assignInsertId = $documents_list['sid'];

            $is_completed = check_document_completed($documents_list);
            if ($is_completed == "Completed") {

                unset($documents_list['sid']);
                unset($documents_list['is_pending']);
                //
                $h = $documents_list;
                $h['doc_sid'] = $assignInsertId;
                //
                $this->hr_documents_management_model->insert_documents_assignment_record_history($h);
            }

            //
            $document_to_update = array();

            $document_to_update['status'] = 1;
            $document_to_update['acknowledged'] = NULL;
            $document_to_update['acknowledged_date'] = NULL;
            $document_to_update['downloaded'] = NULL;
            $document_to_update['downloaded_date'] = NULL;
            $document_to_update['uploaded'] = NULL;
            $document_to_update['uploaded_date'] = NULL;
            $document_to_update['uploaded_file'] = NULL;
            $document_to_update['signature_timestamp'] = NULL;
            $document_to_update['signature'] = NULL;
            $document_to_update['signature_email'] = NULL;
            $document_to_update['signature_ip'] = NULL;
            $document_to_update['user_consent'] = 0;
            $document_to_update['archive'] = 0;
            $document_to_update['submitted_description'] = NULL;
            $document_to_update['signature_base64'] = NULL;
            $document_to_update['signature_initial'] = NULL;
            $document_to_update['authorized_signature'] = NULL;
            $document_to_update['authorized_signature_by'] = NULL;
            $document_to_update['authorized_signature_date'] = NULL;

            $assignInsertId = $this->hr_documents_management_model->updateAssignedDocument($assignInsertId, $document_to_update); // If already exists then update
            $document_id = json_encode((int)$assignInsertId);
            echo $document_id;
        } else {
            // Get the original document
            $documents_assigned_data = $this->hr_documents_management_model->get_documents_assigned($document_sid);
            // Create an insert array
            $new_documents_assigned_data['company_sid '] = $documents_assigned_data['company_sid'];
            $new_documents_assigned_data['user_type'] = 'employee';
            $new_documents_assigned_data['user_sid'] = $employee_sid;
            $new_documents_assigned_data['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
            $new_documents_assigned_data['assigned_by'] = $employee_sid;
            $new_documents_assigned_data['status'] = 1;
            $new_documents_assigned_data['document_type'] = $documents_assigned_data['document_type'];
            $new_documents_assigned_data['document_title '] = $documents_assigned_data['document_title'];
            $new_documents_assigned_data['document_description'] = $documents_assigned_data['document_description'];
            $new_documents_assigned_data['document_original_name'] = $documents_assigned_data['uploaded_document_original_name'];
            $new_documents_assigned_data['document_extension'] = $documents_assigned_data['uploaded_document_extension'];
            $new_documents_assigned_data['document_s3_name'] = $documents_assigned_data['uploaded_document_s3_name'];
            $new_documents_assigned_data['document_sid'] = $documents_assigned_data['sid'];
            $new_documents_assigned_data['acknowledgment_required'] = $documents_assigned_data['acknowledgment_required'];
            $new_documents_assigned_data['download_required'] = $documents_assigned_data['download_required'];
            $new_documents_assigned_data['signature_required'] = $documents_assigned_data['signature_required'];
            $new_documents_assigned_data['is_required'] = $documents_assigned_data['is_required'];
            $new_documents_assigned_data['is_signature_required'] = $documents_assigned_data['signature_required'];
            $new_documents_assigned_data['allowed_employees'] = $documents_assigned_data['allowed_employees'];
            $new_documents_assigned_data['allowed_departments'] = $documents_assigned_data['allowed_departments'];
            $new_documents_assigned_data['allowed_teams'] = $documents_assigned_data['allowed_teams'];
            $new_documents_assigned_data['isdoctolibrary'] = $documents_assigned_data['isdoctolibrary'];
            $new_documents_assigned_data['visible_to_document_center'] = 0;
            $new_documents_assigned_data['is_confidential'] = $documents_assigned_data['is_confidential'];
            $new_documents_assigned_data['fillable_document_slug'] = $documents_assigned_data['fillable_document_slug'];




            // TODO
            // Send emails to assigned employers
            // Send emails to authorize assigners
            // Send emails to approvers if any

            $sid = $this->hr_documents_management_model->insert_documents_assigned($new_documents_assigned_data);
            $document_id = json_encode($sid);
            echo $document_id;
        }
    }

    // change document visibility on document center 
    public function document_visible()
    {
        $document_sid = $_POST['document_sid'];
        $status_to_update = array();
        $status_to_update['visible_to_document_center'] = 0;
        $this->hr_documents_management_model->change_document_visible($document_sid, $status_to_update);
    }


    function delete_supporting_document($sid)
    {
        //
        $supporting_document = $this->hr_documents_management_model->getUserSupportingDocument($sid);
        $supporting_document_sid = $supporting_document["sid"];
        unset($supporting_document["sid"]);
        //
        $supporting_document_history = array();
        $supporting_document_history = $supporting_document;
        $supporting_document_history["supporting_documents_sid"] = $supporting_document_sid;
        //
        $this->hr_documents_management_model->addSupportingDocumentHistory($supporting_document_history);
        $this->hr_documents_management_model->deleteUserSupportingDocument($sid);
        //
        $response = array();
        $response['status'] = TRUE;
        $response['message'] = "The supporting document delete successfully.";
        //
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }

    public function approval_documents()
    {
        if (!$this->session->userdata('logged_in')) {
            return redirect(base_url('login'), "refresh");
        }
        //
        $data['session'] = $this->session->userdata('logged_in');
        $employer_detail = $data['session']['employer_detail'];
        $company_detail = $data['session']['company_detail'];
        $employer_sid = $data["session"]["employer_detail"]["sid"];
        $security_sid = $employer_detail['sid'];
        getCompanyEmsStatusBySid($company_detail['sid']);


        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'dashboard', 'private_messages');
        //
        $assign_approvals = $this->hr_documents_management_model->getMyAssignApprovalInfo($employer_sid);
        //
        $data["assign_approvals"] = $assign_approvals;
        //
        $data['employee'] = $employer_detail;
        $data['title'] = "Approval Documents";
        $data['load_view'] = "true";

        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/pending_documents_approval');
        $this->load->view('main/footer');
    }

    /**
     * 
     */
    public function save_approval_document_action()
    {
        //
        $post = $this->input->post(null, true);
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        $approver_reference = isset($post['approver_sid']) ? $post['approver_sid'] : $post['approver_reference'];
        $approver_action = $post['approver_action'];
        $approver_note = $_POST['approver_note'];
        $document_sid = $post['document_sid'];
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
        //
        if (empty($document_info)) {
            $resp['Msg'] = 'You are not allowed to perform that action!';
            res($resp);
        }
        $currentFlowId = $document_info['approval_flow_sid'];
        //
        $current_approver_reference = "";
        $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($currentFlowId);
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            $approver_detail = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
            $current_approver_reference = $approver_detail["email"];
        } else {
            $approver_detail = $this->hr_documents_management_model->get_approver_detail($current_approver_info["assigner_sid"]);
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }
        //
        if (!empty($document_info) && $current_approver_reference == $approver_reference) {
            //
            // Save Current Approver Action 
            $this->hr_documents_management_model->saveApproverAction(
                $approver_reference,
                $document_info['approval_flow_sid'],
                [
                    'approval_status' => $approver_action,
                    'note' => $approver_note,
                    'assigner_turn' => 0,
                    'action_date' => date('Y-m-d H:i:s', strtotime('now'))
                ]
            );
            //
            if ($approver_action == "Reject") {
                // 
                // Revoke all approvers against this document
                $this->hr_documents_management_model->updateApproversInfo(
                    $document_info['approval_flow_sid'],
                    [
                        'status' => 0,
                        'assigner_turn' => 0
                    ]
                );
                //
                // Update flow row because last approver reject it
                $this->hr_documents_management_model->updateApprovalDocument(
                    $document_info['approval_flow_sid'],
                    [
                        'assign_status' => 3 // 3 mean Reject this document
                    ]
                );
                //
                // Send Email to initiator of this document
                $this->SendEmailToDocumentInitiator(
                    "reject",
                    HR_DOCUMENTS_APPROVAL_FLOW_REJECTED,
                    $document_sid,
                    $approver_reference
                );
            } else {
                // Check any approver left against this document
                $new_approver = $this->hr_documents_management_model->getnextApproversInfo($document_info['approval_flow_sid']);
                // Sends email to next approver

                if ($new_approver) {
                    // Save Current Approver Action 
                    $this->hr_documents_management_model->saveApproverAction(
                        $new_approver['assigner_sid'],
                        $new_approver['portal_document_assign_sid'],
                        [
                            'assigner_turn' => 1,
                            'assign_on' => date('Y-m-d H:i:s', strtotime('now'))
                        ]
                    );
                    //
                    // Send Email to next approver of this document
                    $this->SendEmailToCurrentApprover($document_sid);
                } else {
                    $default_approver = $this->hr_documents_management_model->getDefaultApprovers(
                        $document_info['company_sid'],
                        $document_info['approval_flow_sid'],
                        $document_info['has_approval_flow']
                    );
                    //
                    if (empty($default_approver)) {
                        // 
                        // Update flow row because last approver approve it
                        $this->hr_documents_management_model->updateApprovalDocument(
                            $document_info['approval_flow_sid'],
                            [
                                'status' => 0,
                                'assign_status' => 2 // 2 mean Approve this document
                            ]
                        );
                        //
                        // Disabled all approver against this document
                        $this->hr_documents_management_model->disableApproverAssignDocs(
                            $document_info['approval_flow_sid'],
                            [
                                'status' => 0,
                                'assigner_turn' => 0
                            ]
                        );
                        //
                        // Send Email to initiator/assigner of this document
                        $this->SendEmailToDocumentInitiator(
                            "accept",
                            HR_DOCUMENTS_APPROVAL_FLOW_APPROVED,
                            $document_sid,
                            $approver_reference
                        );
                        //
                        // Update user assigned document row
                        $this->hr_documents_management_model->change_document_approval_status(
                            $document_sid,
                            [
                                'approval_process' => 0
                            ]
                        );
                        //
                        //
                        $this->after_approval_document_process($document_sid);
                    } else {
                        $approver_sid = 0;
                        $approver_email = "";
                        //
                        if (is_numeric($default_approver) && $default_approver > 0) {
                            $approver_sid = $default_approver;
                            //
                            $this->hr_documents_management_model->change_document_approval_status(
                                $document_sid,
                                [
                                    'document_approval_employees' => !empty($document_info["document_approval_employees"]) ? $document_info["document_approval_employees"] . ',' . $approver_sid : $approver_sid
                                ]
                            );
                        } else {
                            $approver_email = $default_approver;
                        }
                        //
                        $this->hr_documents_management_model->insert_assigner_employee(
                            [
                                'portal_document_assign_sid' =>  $document_info['approval_flow_sid'],
                                'assigner_sid' => $approver_sid,
                                'approver_email' => $approver_email,
                                'assign_on' =>  date('Y-m-d H:i:s', strtotime('now')),
                                'assigner_turn' => 1,
                            ]
                        );
                        //
                        // Send Email to first approver of this document
                        $this->SendEmailToCurrentApprover($document_sid);
                    }
                }
            }

            $resp['Status'] = true;
            $resp['Msg'] = 'Your action is save successfully!';
            res($resp);
        } else {
            $resp['Msg'] = 'You are not allowed to perform that action!';
            res($resp);
        }
    }

    function after_approval_document_process($document_sid)
    {
        //
        $document_info = $this->hr_documents_management_model->get_document_detail_for_end_process($document_sid);
        $companyName = getCompanyNameBySid($document_info["company_sid"]);
        // For email
        if ($document_info['sendEmail'] == 'yes') {
            // 
            $hf = message_header_footer_domain($document_info["company_sid"], $companyName);
            // Send Email and SMS
            $replacement_array = array();
            //
            switch ($document_info["user_type"]) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($document_info["company_sid"], $document_info["user_sid"]);
                    $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                    $replacement_array['company_name'] = ucwords($companyName);
                    $replacement_array['username'] = $replacement_array['contact-name'];
                    $replacement_array['firstname'] = $user_info['first_name'];
                    $replacement_array['lastname'] = $user_info['last_name'];
                    $replacement_array['first_name'] = $user_info['first_name'];
                    $replacement_array['last_name'] = $user_info['last_name'];
                    $replacement_array['baseurl'] = base_url();
                    $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                    //
                    $this->hr_documents_management_model->update_employee($post['EmployerSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                    //
                    $is_manual = get_document_type($document_sid);
                    //
                    if (sizeof($replacement_array) && $is_manual == 'no') {
                        //
                        $user_extra_info = array();
                        $user_extra_info['user_sid'] = $document_info["user_sid"];
                        $user_extra_info['user_type'] = $document_info["user_type"];
                        //
                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                    }
                    break;

                case 'applicant':
                    $user_info = $this->hr_documents_management_model->get_applicant_information($document_info["company_sid"], $document_info["user_sid"]);
                    // Set email content
                    $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
                    //
                    $this->load->library('encryption', 'encrypt');
                    //
                    $time = strtotime('+10 days');
                    //
                    $encryptedKey = $this->encrypt->encode($document_sid . '/' . $user_info['sid'] . '/applicant/' . $time);
                    $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                    //
                    $user_info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($post['documentTitle']) . '</a>';
                    //
                    $subject = convert_email_template($template['subject'], $user_info);
                    $message = convert_email_template($template['text'], $user_info);
                    //
                    $body = $hf['header'];
                    $body .= $message;
                    $body .= $hf['footer'];
                    //
                    $this->hr_documents_management_model
                        ->updateAssignedDocumentLinkTime(
                            $time,
                            $document_sid
                        );
                    //
                    log_and_sendEmail(
                        FROM_EMAIL_NOTIFICATIONS,
                        $user_info['email'],
                        $subject,
                        $body,
                        $companyName
                    );
                    break;
            }
        }
        //
        // Check if it's Authorize document
        if ($document_info['managersList'] != null && str_replace('{{authorized_signature}}', '', $document_info["document_description"]) != $document_info["document_description"]) {
            // Managers handling
            $this->hr_documents_management_model->addManagersToAssignedDocuments(
                $document_info['managersList'],
                $document_sid,
                $document_info["company_sid"],
                $document_info['assigned_by']
            );
        }
        //
        //
        if ($document_info["user_type"] == 'employee') {
            //
            $user_info = $this->hr_documents_management_model->get_employee_information($document_info["company_sid"], $document_info['user_sid']);
            $assigner_info = $this->hr_documents_management_model->get_employee_information($document_info["company_sid"], $document_info['assigned_by']);
            // Send document completion alert
            broadcastAlert(
                DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE,
                'documents_status',
                'document_assigned',
                $document_info["company_sid"],
                $companyName,
                $assigner_info['first_name'],
                $assigner_info['last_name'],
                $document_info["user_sid"],
                [
                    'document_title' => $document_info['document_title'],
                    'employee_name' => $user_info['first_name'] . ' ' . $user_info['last_name']
                ]
            );
        }
        //
    }

    function review_approval_document($document_sid)
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $employer_detail = $data['session']['employer_detail'];
            $company_detail = $data['session']['company_detail'];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $security_sid = $employer_detail['sid'];
            getCompanyEmsStatusBySid($company_detail['sid']);
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'dashboard', 'private_messages');
            //
            $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
            //
            if (empty($document_info)) {
                return redirect('hr_documents_management/approval_documents');
            }
            //
            $document_approvers = $this->hr_documents_management_model->getAllDocumentApprovers($document_info["approval_flow_sid"]);
            $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
            //
            $currentApprover = [];
            //
            foreach ($document_approvers as $assi) {
                //
                if ($assi['assigner_sid'] == $employer_detail['sid']) {
                    $currentApprover = $assi;
                }
            }
            //
            $data["page"] = "view";
            $data["document_sid"] = $document_sid;
            $data["document_info"] = $document_info;
            $data["currentApprover"] = $currentApprover;
            $data["currentApproverId"] = $employer_detail['sid'];
            $data["document_title"] = $document_info["document_title"];
            $data["document_type"] = $document_info["document_type"];
            $data["assigned_by"] = $approvers_flow_info['assigned_by'];
            $data["assigned_date"] = $approvers_flow_info['assigned_date'];
            $data["user_name"] = $document_info["user_sid"];
            $data["user_sid"] = $document_info["user_sid"];
            $data["user_type"] = $document_info["user_type"];
            $data["approvers"] = $document_approvers;
            $data['employee'] = $employer_detail;
            $data['title'] = "Approval Documents";
            $data['load_view'] = "true";

            //
            $this->load->view('main/header', $data);
            $this->load->view('hr_documents_management/view_approval_document');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function revoke_approval_document()
    {

        $document_sid = $this->input->post('document_sid');
        $user_type = $this->input->post('user_type');
        $user_sid = $this->input->post('user_sid');
        //
        $document_row_sid = $this->hr_documents_management_model->getApprovedDocumentRowSid($user_type, $user_sid, $document_sid);
        //
        $data_to_update = array();
        $data_to_update['assign_status'] = 0; // 2 mean Approve this document
        //
        $this->hr_documents_management_model->updateApprovalDocument($document_row_sid, $data_to_update);
        //
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $data_to_update['assigner_turn'] = 0;
        //
        $this->hr_documents_management_model->updateApproversInfo($document_row_sid, $data_to_update);
        //
        $response = array();
        $response['status'] = TRUE;
        $response['message'] = "The approval document revoke successfully.";
        //
        header('Content-Type: application/json');
        echo json_encode($response);
        exit(0);
    }

    /**
     * Handle document approval flow
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $document_sid
     * @param string $initiator_note
     * @param array  $approvers_list
     * @param string $send_email
     * @param array  $managers_list
     * 
     * @return
     */
    private function HandleApprovalFlow(
        $document_sid,
        $initiator_note,
        $approvers_list,
        $send_email,
        $managers_list
    ) {

        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        //
        // Set insert data array
        //
        $ins = [];
        $ins['company_sid'] = $company_sid;
        $ins['document_sid'] = $document_sid;
        $ins['assigned_by'] = $employer_sid;
        $ins['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['assigner_note'] = $initiator_note;
        $ins['status'] = 1;
        $ins['is_pending'] = 0; // 0 = Pending, 1 = Accepted, 2 = Rejected
        //
        // Lets revoke all previous document flows if exist
        $this->hr_documents_management_model->revoke_document_previous_flow($document_sid);

        // Lets insert the record
        $approvalInsertId = $this->hr_documents_management_model->insert_documents_assignment_flow($ins);
        //
        // Update user assigned document
        $this->hr_documents_management_model->change_document_approval_status(
            $document_sid,
            [
                'approval_process' => 1,
                'approval_flow_sid' => $approvalInsertId,
                'sendEmail' => $send_email,
                'managersList' => $managers_list,
                'has_approval_flow' => 1,
                'document_approval_employees' => $approvers_list,
                'document_approval_note' => $initiator_note,
            ]
        );
        //
        $this->AddAndSendNotificationsToApprovalEmployees(
            $approvalInsertId,
            $document_sid,
            $approvers_list,
            $initiator_note
        );
        //
        return true;
    }

    /**
     * Add and sends email notifications
     * to selected approval employees
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $approval_flow_sid
     * @param number $document_sid
     * @param array  $approvers_list
     * @param string $initiator_note
     */
    function AddAndSendNotificationsToApprovalEmployees(
        $approval_flow_sid,
        $document_sid,
        $approvers_list,
        $initiator_note
    ) {

        if (!empty($approvers_list)) {
            $approvalEmployees = explode(",", $approvers_list);
            //
            foreach ($approvalEmployees as $key => $approver_sid) {
                $is_default_approver = $this->hr_documents_management_model->is_default_approver($approver_sid);
                //
                if ($is_default_approver) {
                    $data_to_insert = array();
                    $data_to_insert['portal_document_assign_sid'] = $approval_flow_sid;
                    $data_to_insert['assigner_sid'] = $approver_sid;
                    //
                    if ($key == 0) {
                        $data_to_insert['assign_on'] = date('Y-m-d H:i:s', strtotime('now'));
                        $data_to_insert['assigner_turn'] = 1;
                    }
                    //
                    $this->hr_documents_management_model->insert_assigner_employee($data_to_insert);
                    //
                    if ($key == 0) {
                        //
                        // Send Email to first approver of this document
                        $this->SendEmailToCurrentApprover($document_sid);
                    }
                }
            }
        } else {

            $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
            //
            $default_approver = $this->hr_documents_management_model->getDefaultApprovers(
                $document_info['company_sid'],
                $document_info['approval_flow_sid'],
                $document_info['has_approval_flow']
            );

            if (!empty($default_approver)) {
                //
                $approver_sid = 0;
                $approver_email = "";
                //
                if (is_numeric($default_approver) && $default_approver > 0) {
                    $approver_sid = $default_approver;
                    //
                    $this->hr_documents_management_model->change_document_approval_status(
                        $document_sid,
                        [
                            'document_approval_employees' => $approver_sid
                        ]
                    );
                } else {
                    $approver_email = $default_approver;
                }
                //

                $this->hr_documents_management_model->insert_assigner_employee(
                    [
                        'portal_document_assign_sid' =>  $document_info['approval_flow_sid'],
                        'assigner_sid' => $approver_sid,
                        'approver_email' => $approver_email,
                        'assign_on' =>  date('Y-m-d H:i:s', strtotime('now')),
                        'assigner_turn' => 1,
                    ]
                );
                //
                // Send Email to first approver of this document
                $this->SendEmailToCurrentApprover($document_sid);
            }
        }
    }

    function SendEmailToCurrentApprover($document_sid)
    {

        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
        //
        $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
        //
        $approver_info = array();
        $current_approver_reference = '';
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            //
            $default_approver = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
            //
            $approver_name = explode(" ", $default_approver["contact_name"]);
            //
            $approver_info['first_name'] = isset($approver_name[0]) ? $approver_name[0] : "";
            $approver_info['last_name'] = isset($approver_name[1]) ? $approver_name[1] : "";
            $approver_info['email'] = $default_approver["email"];
            //
            $current_approver_reference = $default_approver["email"];
        } else {
            //
            $approver_info = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $current_approver_info["assigner_sid"]);
            //
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }

        //
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        //
        // Get the initiator name
        $document_initiator_name = getUserNameBySID($approvers_flow_info["assigned_by"]);
        //
        // Get the company name
        $company_name = getCompanyNameBySid($document_info['company_sid']);
        //
        // Get assigned document user name
        if ($document_info['user_type'] == 'employee') {
            //
            $t = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        }
        //
        $hf = message_header_footer_domain($document_info['company_sid'], $company_name);
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $accept_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'accept')
        );
        //
        $reject_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'reject')
        );
        //
        $view_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'view')
        );
        //
        $approval_public_link_accept = base_url("hr_documents_management/public_approval_document") . '/' . $accept_code;
        $approval_public_link_reject = base_url("hr_documents_management/public_approval_document") . '/' . $reject_code;
        $approval_public_link_view = base_url("hr_documents_management/public_approval_document") . '/' . $view_code;
        // 
        $replacement_array['initiator']             = $document_initiator_name;
        $replacement_array['contact-name']          = $document_assigned_user_name;
        $replacement_array['company_name']          = ucwords($company_name);
        $replacement_array['username']              = $replacement_array['contact-name'];
        $replacement_array['firstname']             = $approver_info['first_name'];
        $replacement_array['lastname']              = $approver_info['last_name'];
        $replacement_array['first_name']            = $approver_info['first_name'];
        $replacement_array['last_name']             = $approver_info['last_name'];
        $replacement_array['document_title']        = $document_info['document_title'];
        $replacement_array['user_type']             = $document_info['user_type'];
        $replacement_array['note']                  = $approvers_flow_info["assigner_note"];
        $replacement_array['baseurl']               = base_url();
        $replacement_array['accept_link']           = $approval_public_link_accept;
        $replacement_array['reject_link']           = $approval_public_link_reject;
        $replacement_array['view_link']             = $approval_public_link_view;
        //
        // Send email notification to approver with a private link
        log_and_send_templated_email(HR_DOCUMENTS_APPROVAL_FLOW, $approver_info['email'], $replacement_array, $hf, 1);
    }

    /**
     * Sends an email to approver
     * 
     * @version 2.0
     * @date    05/18/2022
     * 
     * @param string $assignerName
     * @param string $userName
     * @param array  $userInfo
     * @param string $documentTitle
     * @param string $userType
     * @param string $note
     * @param string $companyName
     * @param array  $hf
     * @param number $template
     * @param array  $approvers
     */
    function SendEmailToDocumentInitiator(
        $type,
        $template = HR_DOCUMENTS_APPROVAL_FLOW,
        $document_sid,
        $approver_reference
    ) {
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid, false);
        //
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        //
        // Get all the approvers of this document
        $document_approvers = $this->hr_documents_management_model->getAllDocumentApprovers($document_info['approval_flow_sid']);
        //
        // Get the document initiator info
        $initiator_info = $this->hr_documents_management_model->get_employee_information(
            $document_info['company_sid'],
            $approvers_flow_info["assigned_by"]
        );
        //
        // Get the company name
        $company_name = getCompanyNameBySid($document_info['company_sid']);
        //
        // Get assigned document user name
        if ($document_info['user_type'] == 'employee') {
            //
            $t = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        }
        //
        $hf = message_header_footer_domain($document_info['company_sid'], $company_name);
        //
        if ($type == "reject") {
            //
            $rejector_name = "";
            //
            if (is_numeric($approver_reference) && $approver_reference > 0) {
                $rejector_info = $this->hr_documents_management_model->get_employee_information(
                    $document_info['company_sid'],
                    $approver_reference
                );
                //
                $rejector_name = ucwords($rejector_info['first_name'] . ' ' . $rejector_info['last_name']);
            } else {
                $rejector_info = $this->hr_documents_management_model->get_default_outer_approver(
                    $document_info['company_sid'],
                    $approver_reference
                );
                //
                $rejector_name = ucwords($rejector_info['contact_name']);
            }
            //
            $rejector_note = $this->hr_documents_management_model->get_approver_note(
                $document_info['approval_flow_sid'],
                $approver_reference
            );
            //
            $replacement_array['rejector_name'] = $rejector_name;
            $replacement_array['rejector_note'] = $rejector_note;
        }
        //
        $replacement_array['contact-name'] = $document_assigned_user_name;
        $replacement_array['company_name'] = ucwords($company_name);
        $replacement_array['first_name'] = $initiator_info['first_name'];
        $replacement_array['last_name'] = $initiator_info['last_name'];
        $replacement_array['document_title'] = $document_info['document_title'];
        //
        //
        if (!empty($document_approvers)) {
            //
            $tb = '<table>';
            $tb .=   '<thead>';
            $tb .=       '<tr>';
            $tb .=           '<th>Employee</th>';
            $tb .=           '<th>Status</th>';
            $tb .=           '<th>Action Date</th>';
            $tb .=           '<th>Comment</th>';
            $tb .=       '</tr>';
            $tb .=   '</thead>';
            $tb .=   '<tbody>';
            foreach ($document_approvers as $approver) :
                $tb .=       '<tr>';
                if (is_numeric($approver['assigner_sid']) && $approver['assigner_sid'] > 0) {
                    $tb .=           '<th>' . (getUserNameBySID($approver['assigner_sid'])) . '</th>';
                } else {
                    //
                    $default_approver = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $approver["approver_email"]);
                    //
                    $tb .=           '<th>' . $default_approver["contact_name"] . '</th>';
                }

                $tb .=           '<th>' . ($approver['approval_status']) . '</th>';
                $tb .=           '<th>' . (formatDateToDB($approver['action_date'], DB_DATE_WITH_TIME, DATE_WITH_TIME)) . '</th>';
                $tb .=           '<th>' . ($approver['note']) . '</th>';
                $tb .=       '</tr>';
            endforeach;
            $tb .=   '</tbody>';
            $tb .= '</table>';
            //
            $replacement_array['approvers_list'] = $tb;
        }
        //
        // Send email to initiator of this document
        log_and_send_templated_email($template, $initiator_info['email'], $replacement_array, $hf, 1);

        if ($type == "accept") {
            //
            // Get the document assigner info
            $assigner_info = $this->hr_documents_management_model->get_employee_information(
                $document_info['company_sid'],
                $document_info["assigned_by"]
            );
            //
            if ($initiator_info['email'] != $assigner_info['email']) {
                //
                $replacement_array['first_name'] = $assigner_info['first_name'];
                $replacement_array['last_name'] = $assigner_info['last_name'];

                //
                // Send email to assigner of this document
                log_and_send_templated_email($template, $assigner_info['email'], $replacement_array, $hf, 1);
            }
        }
    }

    /**
     * public function for Accept and Reject Document
     * 
     * @version 1.0
     * @date    05/17/2022
     * 
     * 
     * @param string $token
     */
    function public_approval_document(
        $token
    ) {
        // 
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $decrypt_token = $this->encryption->decrypt(str_replace(['$$ab$$', '$$ba$$'], ['/', '+'], $token));
        //
        if (!empty($decrypt_token)) {
            $decrypt_keys = explode("/", $decrypt_token);
            //
            $document_sid = $decrypt_keys[0];
            $approver_reference = $decrypt_keys[1];
            $type = $decrypt_keys[2];
            $approvers_list = array();
            //
            if ($type == "view") {
                $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid, false);
                $approvers_list = $this->hr_documents_management_model->get_document_approvers($document_info["approval_flow_sid"]);
            } else {
                $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
            }

            //
            if (!empty($document_info)) {
                $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
                //
                if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
                    $approver_detail = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
                    $current_approver_reference = $approver_detail["email"];
                } else {
                    $approver_detail = $this->hr_documents_management_model->get_approver_detail($current_approver_info["assigner_sid"]);
                    $current_approver_reference = $current_approver_info["assigner_sid"];
                }
                //
                $company_detail = $this->hr_documents_management_model->get_company_detail($document_info['company_sid']);
                $company_domain = $this->hr_documents_management_model->get_company_domain_by_sid($document_info['company_sid']);
                $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
                //
                $data = array();
                $data['company_detail'] = $company_detail;
                $data["approvers_note"] = $approvers_flow_info['assigner_note'];
                $data["document_title"] = $document_info['document_title'];
                $data["document_type"] = $document_info['document_type'];
                $data["assigned_by"] = $approvers_flow_info['assigned_by'];
                $data["assigned_date"] = $approvers_flow_info['assigned_date'];
                $data["user_type"] = $document_info["user_type"];
                $data["user_sid"] = $document_info["user_sid"];
                $data["document_info"] = $document_info;
                $data["action"] = $type;
                $data["company_domain"] = $company_domain;
                $data["document_sid"] = $document_sid;
                $data["approver_reference"] = $approver_reference;
                $data["current_approver_reference"] = $current_approver_reference;
                $data["approvers_list"] = $approvers_list;
                $data["request_type"] = $type;
                //
                if ($document_info["user_type"] == "employee") {
                    $data["document_user_name"] = getUserNameBySID($document_info["user_sid"]);
                } else {
                    $data["document_user_name"] = getApplicantNameBySID($document_info["user_sid"]);
                }
                //
                $this->load->view('hr_documents_management/public_approval_document', $data);
                //
            } else {
                $this->load->view('onboarding/thank_you');
            }
        } else {
            $this->load->view('onboarding/thank_you');
        }
    }

    function get_document_history($user_sid, $user_type, $document_type, $document_sid)
    {
        //
        $document_history = $this->hr_documents_management_model->fetch_document_from_history($document_type, $document_sid, $user_type, $user_sid);
        //
        $history_array = array();
        $h_key = 0;
        //
        if (!empty($document_history)) {
            foreach ($document_history as $history) {
                $history_array[$h_key]['sid'] = $history['sid'];

                if ($document_type == "user_document") {
                    $history_array[$h_key]['type'] = 'Assigned' . $document_type;
                    $history_array[$h_key]['name'] = (strtoupper($history['document_title']));
                    $history_array[$h_key]['assign_on'] = reset_datetime(array('datetime' => $history['assigned_date'], '_this' => $this));
                    //
                    if ($history['document_type'] == "uploaded") {
                        $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['uploaded_date'], '_this' => $this));
                    } else {
                        $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['signature_timestamp'], '_this' => $this));
                    }
                    //
                } else {
                    $history_array[$h_key]['type'] = strtoupper($document_type) . '_Form';
                    $history_array[$h_key]['name'] = (strtoupper($document_type)) . ' Fillable Document';
                    $history_array[$h_key]['assign_on'] = reset_datetime(array('datetime' => $history['sent_date'], '_this' => $this));
                    //
                    if ($document_type == 'w4' || $document_type == 'w9') {
                        $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['signature_timestamp'], '_this' => $this));
                    } else if ($document_type == 'i9') {
                        $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['applicant_filled_date'], '_this' => $this));
                    } else if ($document_type == 'eeoc') {
                        $history_array[$h_key]['assign_on'] = reset_datetime(array('datetime' => $history['last_sent_at'], '_this' => $this));
                        $history_array[$h_key]['submitted_on'] = reset_datetime(array('datetime' => $history['last_completed_on'], '_this' => $this));
                    }
                    //
                }


                $history_array[$h_key]['status'] = !empty($history['user_consent']) && $history['user_consent'] == 1 ? "Completed" : "Not Completed";

                //
                $h_key++;
            }
        }
        //
        header('content-type: application/json');
        echo json_encode($history_array);
        exit(0);
    }

    function is_one_hour_complete($time)
    {
        if (empty($time)) {
            return 1;
        } else {
            $date1 = new DateTime($time);
            $date2 = new DateTime(date('Y-m-d H:i:s'));

            $diff = $date2->diff($date1);

            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);

            return $hours;
        }
    }

    function get_document_approvers($document_sid, $user_type, $user_sid)
    {
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        if (!$this->session->userdata('logged_in')) {
            //
            $resp['Msg'] = 'Your session has expired';
            res($resp);
        }
        //
        $company_sid = $this->session->userdata('logged_in')['company_detail']['sid'];
        $employer_sid = $this->session->userdata('logged_in')['employer_detail']['sid'];
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_information($document_sid, $user_type, $user_sid);
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        $approvers = $this->hr_documents_management_model->get_document_approvers($document_info['approval_flow_sid']);
        $employeesList = $this->hr_documents_management_model->fetch_all_company_managers($company_sid, $employer_sid);
        //
        $approvers_sids = array_column($approvers, "assigner_sid");
        //
        foreach ($employeesList as $e_key => $employee) {
            if (in_array($employee["sid"], $approvers_sids)) {
                unset($employeesList[$e_key]);
            }

            if ($employee["sid"] == $employer_sid) {
                unset($employeesList[$e_key]);
            }
        }
        //
        $data = array();
        $data["approvers"] = $approvers;
        $data["employeesList"] = $employeesList;
        $data["document_sid"] = $document_sid;
        $data["approval_document_sid"] = $approvers_flow_info['sid'];
        $data["approvers_note"] = $approvers_flow_info['assigner_note'];
        $data["document_title"] = $document_info['document_title'];
        $data["document_type"] = $document_info['document_type'];
        $data["company_sid"] = $document_info['company_sid'];
        $data["assigned_by"] = $approvers_flow_info['assigned_by'];
        $data["assigned_date"] = $approvers_flow_info['assigned_date'];
        $data["document_user_type"] = $user_type;
        $data["document_user_sid"] = $user_sid;
        //
        if ($user_type == "employee") {
            $data["document_user_name"] = getUserNameBySID($user_sid);
        } else {
            $data["document_user_name"] = getApplicantNameBySID($user_sid);
        }
        //
        if (empty($approvers)) {
            $resp['Msg'] = 'Approvers not found';
        } else {
            $resp['Status'] = true;
            $resp['Msg'] = 'Proceed.';
            $resp['Data'] = $this->load->view('hr_documents_management/partials/approvers_modal', $data, true);
        }
        //
        res($resp);
    }

    function approvers_handler()
    {
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        if (!$this->session->userdata('logged_in')) {
            //
            $resp['Msg'] = 'Your session has expired';
            res($resp);
        }
        //
        if (!$this->session->userdata('logged_in')['employer_detail']['access_level_plus']) {
            //
            $resp['Msg'] = 'You don\'t permission to the employee profile.';
            res($resp);
        }
        //
        $company_sid = $this->session->userdata('logged_in')['company_detail']['sid'];
        $employer_info = $this->session->userdata('logged_in')['employer_detail'];
        $company_name = $this->session->userdata('logged_in')['company_detail']['CompanyName'];
        //
        //
        $post = $this->input->post();
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_information($post["documentId"], $post["userType"], $post["userId"]);
        //
        switch ($post['action']) {
            case 'add_approver':
                //
                $data_to_insert = array();
                $data_to_insert['portal_document_assign_sid'] = $post["approvalDocumentId"];
                $data_to_insert['assigner_sid'] = $post["approverId"];
                //
                $current_approver_sid = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
                //
                if ($current_approver_sid == 0) {
                    $data_to_insert['assign_on'] = date('Y-m-d H:i:s', strtotime('now'));
                    $data_to_insert['assigner_turn'] = 1;
                }
                //
                $this->hr_documents_management_model->insert_assigner_employee($data_to_insert);
                //
                $this->hr_documents_management_model->change_document_approval_status(
                    $document_info["sid"],
                    [
                        'document_approval_employees' => $document_info["document_approval_employees"] . ',' . $post["approverId"]
                    ]
                );
                //
                if ($current_approver_sid == 0) {
                    //
                    // Send Email to new approver if previous all approver deactive
                    $this->SendEmailToCurrentApprover($document_info["sid"]);
                }
                //
                $resp['Status'] = true;
                $resp['Msg'] = getUserNameBySID($post["approverId"]) . ' add an approver successfully.';

                break;

                // 
            case "delete_approver":
                $this->hr_documents_management_model->delete_document_approver_from_list($post["rowId"]);
                //
                $resp['Status'] = true;
                $resp['Msg'] = getUserNameBySID($post["approverId"]) . ' is deleted from approver list successfully.';
                break;

                // 
            case "remind_approver":
                //
                // Send Email reminder to approver of this document
                $this->SendEmailToCurrentApprover($document_info["sid"]);
                //
                $resp['Status'] = true;
                $resp['Msg'] = "Send emai reminder to (" . getUserNameBySID($post["approverId"]) . ") successfully.";
                break;
        }
        //
        $resp['document_sid'] = $post["documentId"];
        $resp['user_type'] = $post["userType"];
        $resp['user_sid'] = $post["userId"];
        //  
        res($resp);
    }

    function get_document_approver_staus($document_sid, $approver_sid)
    {
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid, false);
        $approver_info = $this->hr_documents_management_model->get_document_approver_info($document_info['approval_flow_sid'], $approver_sid);
        //
        if (!empty($approver_info)) {
            $resp['Status'] = true;
            $resp['Msg'] = "Get approver information successfully.";
            $resp['approver_info'] = $approver_info;
        }

        //
        res($resp);
    }

    function get_document_external_default_approver($document_sid)
    {
        //
        $resp = [
            'Status' => false,
            'Msg' => 'Invalid Request'
        ];
        //
        $company_sid = $this->session->userdata('logged_in')['company_detail']['sid'];
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid, false);
        $extern_approvers = $this->hr_documents_management_model->get_document_external_approver_info($document_info['approval_flow_sid'], $company_sid);
        //
        if (!empty($extern_approvers)) {
            $resp['Status'] = true;
            $resp['Msg'] = "Get external approver information successfully.";
            $resp['external_approvers'] = $extern_approvers;
        }

        //
        res($resp);
    }

    /**
     * Update assigned document settings
     * 
     * @author  Mubashir Ahmed
     * @version 1.0
     * @method  POST
     */
    public function updateAssignedDocumentSettings()
    {
        //
        $post = $this->input->post(NULL, TRUE);
        //
        if (empty($post) || !$this->session->userdata('logged_in')) {
            return SendResponse(404, ['Response' => 'Invalid URL']);
        }
        //
        $confidential_employees = null;
        $confidential_employees = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : $post['confidentialSelectedEmployees'];
        //
        $this->db
            ->where([
                'sid' => $post['document_aid']
            ])->update('documents_assigned', [
                'is_confidential' => $post['is_confidential'] == 'on' ? 1 : 0,
                'confidential_employees' => $confidential_employees
            ]);
    }


    /**
     * Preview Document
     * 
     * @version 1.0
     * @date    04/27/2022
     * 
     * @param string $type
     * @param number $document_sid
     */
    public function preview_document($type, $document_sid)
    {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect(base_url('login'), "refresh");
        }
        //
        $session                    = $this->session->userdata('logged_in');
        $company_sid                = $session['company_detail']['sid'];
        $employers_details          = $session['employer_detail'];
        $employer_sid               = $employers_details['sid'];
        $security_details           = db_get_access_level_details($employer_sid);
        //
        $document = $this->hr_documents_management_model->get_preview_document($type, $document_sid);
        if ($document["document_type"] == "generated" || $document["document_type"] == "hybrid_document") {
            $document_content = $document['document_description'];
            $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $document_content);

            $first_name = 'First Name : ------------------------------';
            $document_content = str_replace('{{first_name}}', $first_name, $document_content);
            $document_content = str_replace('{{firstname}}', $first_name, $document_content);
            //
            $last_name = 'Last Name : ------------------------------';
            $document_content = str_replace('{{last_name}}', $last_name, $document_content);
            $document_content = str_replace('{{lastname}}', $last_name, $document_content);
            //
            $email = 'Email : ------------------------------';
            $document_content = str_replace('{{email}}', $email, $document_content);
            //
            $job_title = 'Job Title : ------------------------------';
            $document_content = str_replace('{{job_title}}', $job_title, $document_content);
            //
            $company_name = 'Company Name : ------------------------------';
            $document_content = str_replace('{{company_name}}', $company_name, $document_content);
            //
            $company_address = 'Company Address : ------------------------------';
            $document_content = str_replace('{{company_address}}', $company_address, $document_content);
            //
            $company_phone = 'Company Phone : ------------------------------';
            $document_content = str_replace('{{company_phone}}', $company_phone, $document_content);
            //
            $career_site_url = 'Career Site Url : ------------------------------';
            $document_content = str_replace('{{career_site_url}}', $career_site_url, $document_content);
            //
            $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            $document_content = str_replace('{{authorized_signature}}', $authorized_signature_image, $document_content);
            //
            $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            $document_content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_content);
            //
            $signature_bas64_image = '------------------------------(Signature Required)';
            $document_content = str_replace('{{signature}}', $signature_bas64_image, $document_content);
            //
            $init_signature_bas64_image = '------------------------------(Signature Initial Required)';
            $document_content = str_replace('{{inital}}', $init_signature_bas64_image, $document_content);
            //
            $sign_date = '------------------------------(Sign Date Required)';
            $document_content = str_replace('{{sign_date}}', $sign_date, $document_content);
            //
            //
            $signature_print_name = 'Signature Person Name : ------------------------------';
            $document_content = str_replace('{{signature_print_name}}', $signature_print_name, $document_content);
            //
            $start_date = '------/-------/----------------';
            $document_content = str_replace('{{start_date}}', $start_date, $document_content);
            //
            $date = 'Date :------/-------/----------------';
            $document_content = str_replace('{{date}}', $date, $document_content);
            //
            $valueUP = 'Please contact with your manager';
            $document_content = str_replace('{{username}}', $valueUP, $document_content);
            $document_content = str_replace('{{password}}', $valueUP, $document_content);
            //
            $checkbox = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
            $document_content = str_replace('{{checkbox}}', $checkbox, $document_content);

            $short_text = '<div style="border: 1px dotted #777; padding:5px;background-color:#eee;"  contenteditable="true"></div>';
            $document_content = str_replace('{{text}}', $short_text, $document_content);
            $document_content = str_replace('{{short_text}}', $short_text, $document_content);

            $text_area = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px;background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
            $document_content = str_replace('{{text_area}}', $text_area, $document_content);
            //
            $document['document_description'] = $document_content;
            $links = getGeneratedDocumentURL($document, "company", $isAuthorized);
        } else if ($document["document_type"] == "uploaded") {
            $links = getUploadedDocumentURL($document["document_s3_name"]);
        }
        //
        $data['load_view']          = 'old';
        $data['title']              = 'AutomotoHR :: Documents Preview';
        $data['employer_sid']       = $employer_sid;
        $data['document']           = $document;
        $data['session']            = $session;
        $data['employee']           = $employers_details;
        $data['security_details']   = $security_details;
        $data['print_url']          = $links["print_url"];
        $data['download_url']       = $links["download_url"];
        //
        $this->load->view('main/header', $data);
        $this->load->view('hr_documents_management/templates/preview_document');
        $this->load->view('main/footer');
    }

    /**
     * Handles assignment process of 
     * verification documents; I9, W9, W4
     * 
     * @author  Mubashir Ahmed
     * @version 1.0
     */
    public function assignVD()
    {
        // Let fetch the cleaned post
        $post = $this->input->post(null, true);
        //
        $func;
        //
        if ($post['documentType'] == 'I9') {
            $func = 'handleI9Assign';
        } else if ($post['documentType'] == 'W9') {
            $func = 'handleW9Assign';
        } else if ($post['documentType'] == 'W4') {
            $func = 'handleW4Assign';
        } else {
            exit(0);
        }
        //
        $id = $this->hr_documents_management_model->$func(
            $post['companyId'],
            $post['userId'],
            $post['userType']
        );
        //
        SendResponse(200, [
            'status' => true,
            'response' => 'Success',
            'id' => $id
        ]);
    }

    function test_approver_document()
    {
        $data = array();
        $data['employeesList'] = $this->hr_documents_management_model->fetch_all_company_managers(15708, '');
        $this->load->view('hr_documents_management/templates/test_approver_document', $data);
    }




    //
    function send_email_notification_pending_document()
    {
        //
        $resp = [
            'Status' => false,
            'Response' => 'Invalid request.'
        ];
        // Verfify the session
        $data = $this->session->userdata('logged_in');
        //
        // Save sanatized post
        $post = $this->input->post(NULL, TRUE);
        $user_sid = $post['user_sid'];
        $user_type = $post['user_type'];
        $fillable_type = $post['document_type'];
        //
        // If not a post request
        if (!count($post)) $this->res($resp);
        //
        // If user is not applicant then return back
        if ($user_type != "applicant") $this->res($resp);
        // 
        $session = $this->session->userdata('logged_in');
        $companyId = $session['company_detail']['sid'];
        $companyName = $session['company_detail']['CompanyName'];
        //
        // Get Email header and footer
        $hf = message_header_footer(
            $companyId,
            $companyName
        );
        // Set email content
        $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
        //
        $this->load->library('encryption', 'encrypt');
        //
        $time = strtotime('+10 days');
        //
        $encryptedKey = $this->encrypt->encode($fillable_type  . '/' . $user_sid . '/' . $time);
        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
        //
        $user_info = $this->hr_documents_management_model->getUserData(
            $user_sid,
            $user_type,
            $companyId
        );
        //
        $document_title = "W4 Fillable";
        //
        if ($fillable_type == "I9") {
            $document_title = "I9 Fillable";
        }
        //
        $user_info["link"] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($document_title) . '</a>';
        //

        $subject = convert_email_template($template['subject'], $user_info);
        $message = convert_email_template($template['text'], $user_info);
        //
        $body = $hf['header'];
        $body .= $message;
        $body .= $hf['footer'];
        //
        log_and_sendEmail(
            FROM_EMAIL_NOTIFICATIONS,
            $user_info['email'],
            $subject,
            $body,
            $companyName
        );
        //
        $this->hr_documents_management_model
            ->updateAssignedFederalFillableDocumentLinkTime(
                $time,
                $user_sid,
                $fillable_type
            );
        //
        $resp['Status'] = TRUE;
        $resp['Response'] = 'The document has been sent successfully.';
        //
        $this->res($resp);
    }


    /**
     * Dowload document zip file
     *
     * @param string $fileName
     */
    public function downloadDocumentZipFile($fileName)
    {
        //
        $fileWithPath = ROOTPATH . 'temp_files/employee_export/' . $fileName;
        // Download file
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
        header("Content-length: " . filesize($fileWithPath));
        header("Pragma: no-cache");
        header("Expires: 0");

        ob_clean();
        flush();
        readfile($fileWithPath);
        exit;
    }



    //

    function perform_action_on_document_content_new($document_sid, $request_type, $request_from, $perform_action, $letter_request = NULL)
    {
        $form_input_data = "NULL";
        $is_iframe_preview = 1;

        $document = $this->hr_documents_management_model->get_requested_generated_document_content($document_sid, $request_from);
        $requested_content = $this->hr_documents_management_model->get_requested_generated_document_content_body($document_sid, $request_type, $request_from, 'P&D');
        $file_name = $this->hr_documents_management_model->get_document_title($document_sid, $request_type, $request_from);

        if ($letter_request == 1) {
            $requested_content = $document['submitted_description'];
        } else if (!empty($document['form_input_data']) && $request_type == 'submitted') {
            if (!empty(unserialize($document['form_input_data']))) {
                $is_iframe_preview = 0;
            }

            if (!empty($document['authorized_signature'])) {
                $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
            } else {
                $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            }
            if (!empty($document['authorized_signature_date'])) {
                $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
            } else {
                $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            }

            $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
            $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
            $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);

            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = $document_content;

            $form_input_data = unserialize($document['form_input_data']);
            $form_input_data = json_encode(json_decode($form_input_data, true));
        } else {
            if ($request_type == 'assigned') {
                // if (empty($document['submitted_description']) && empty($document['form_input_data'])) {    
                $is_iframe_preview = 0;
            }

            $form_input_data = json_encode(json_decode('assigned'));
            //
            $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            $signature_bas64_image = '------------------------------(Signature Required)';
            $init_signature_bas64_image = '------------------------------(Signature Initial Required)';
            $sign_date = '------------------------------(Sign Date Required)';
            //
            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
            //
            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = $document_content;
        }

        $data = array();
        $data['file_name'] = $file_name;
        $data['document'] = $document;
        $data['request_type'] = $request_type;
        $data['document_contant'] = $requested_content;
        $data['perform_action'] = $perform_action;
        $data['form_input_data'] = $form_input_data;
        $data['is_iframe_preview'] = $is_iframe_preview;
        $data['is_hybrid'] = "yes";

        $data = [];
        $data['document_body'] = $document_content;
        $data['file_name'] = $file_name;
        $data['form_input_data'] = $form_input_data;
        $data['document'] = $document;
        $data['perform_action'] = $perform_action;

        if ($document["document_type"] == "hybrid_document") {
            $document_path = "";
            if ($request_type == 'submitted') {
                $document_path = $document["uploaded_file"];
            } else {
                $document_path = $request_from == "company_document" ? $document["uploaded_document_s3_name"] : $document["document_s3_name"];
            }
            //
            $data['hybridArray'] = [];
            $data['hybridArray']['s3_file'] = $document_path;
            $data['hybridArray']['file_name'] = $file_name;
            //
            $this->load->view('hr_documents_management/new_generated_document_action_page_hybrid', $data);
        } else {

            $this->load->view('hr_documents_management/new_generated_document_action_page', $data);
        }
    }

    public function generateHybridDocument()
    {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect('/login');
        }
        // get the post
        $post = $this->input->post(null, true);
        // set the s3 file
        $s3_file = urldecode($post['s3_file']);
        // set the path
        $path = ROOTPATH . '/temp_files/' . $post['file_name'] . '/';
        // check and create path
        if (!file_exists($path)) {
            //
            mkdir($path, 0777, true);
        }
        //
        $this->load->library('aws_lib');
        $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $s3_file, $path);
        // // 
        $handler = fopen($path . 'section_2.pdf', 'w');
        fwrite($handler, str_replace('data:application/pdf;base64,', '', $post['pdf']));
        fclose($handler);

        return sendResponse(200, ['success' => $post['file_name'] . '.zip']);
    }

    public function downloadHybridDocument($id)
    {
        //
        $fileWithPath = ROOTPATH . 'temp_files/hybird_document/' . $id;
        // Download file
        header('Content-type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($id) . '"');
        header("Content-length: " . filesize($fileWithPath));
        header("Pragma: no-cache");
        header("Expires: 0");

        ob_clean();
        flush();
        readfile($fileWithPath);
        exit;
    }

    //
    public function download_upload_document_new($document_path, $folderName = ROOTPATH . 'downloaded_documents')
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Documents Assignment';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            if ($this->form_validation->run() == false) {
                //
                $document_path = urldecode($document_path);
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document_path;

                //$empFolderName
                if (!file_exists("$temp_path/$folderName")) {
                    mkdir("$temp_path/$folderName", 0777, true);
                }

                $temp_file_path = $temp_path . '/' . $folderName . '/' . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document_path, $temp_file_path);


                //
                $this->load->library('zip');
                $path = $temp_path . '/' . $folderName;
                $this->zip->read_dir($path, FALSE);
                $this->zip->download($folderName . 'zip');
            } else {
                //nothing
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function print_download_hybird_document(
        $t, // Type 
        $a, // Action
        $s, // Section
        $i,  // ID
        $tt = 'document'
    ) {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');
        //
        $data['session'] = $this->session->userdata('logged_in');
        $data['security_details'] = db_get_access_level_details($data['session']['employer_detail']['sid']);
        //
        $data["s3_path"] = '';
        $data["document_body"] = '';
        //
        switch ($t) {
            case 'assigned_history':
                $d = $this->hr_documents_management_model->getDocumentHistoryById($i);
                $data["s3_path"] = $d['document_s3_name'];
                $document_body = $this->convertMagicCodeToHTML($d);
                $data["document_body"] = $document_body;
                //
                break;
            case 'original':
                if ($tt == 'document') {
                    $d = $this->hr_documents_management_model->getDocumentById($i);
                    $d['user_type'] = null;
                    $d['user_sid'] = null;
                    $d['document_sid'] = null;
                    $data["s3_path"] = $d['uploaded_document_s3_name'];
                    $document_body = $this->convertMagicCodeToHTML($d);
                    $data["document_body"] = $document_body;
                } else {
                    $d = $this->hr_documents_management_model->getOfferLetterById($i);
                    $d['user_type'] = null;
                    $d['user_sid'] = null;
                    $d['document_sid'] = null;
                    $data["s3_path"] = $d['uploaded_document_s3_name'];
                    $document_body = $this->convertMagicCodeToHTML($d);
                    $data["document_body"] = $document_body;
                }
                //     
                break;
            case 'assigned':
            case 'submitted':
                $d = $this->hr_documents_management_model->getAssignedDocumentById($i);
                $data["s3_path"] = $d['document_s3_name'];
                $document_body = $this->convertMagicCodeToHTML($d, 'submitted');
                $data["document_body"] = $document_body;
                break;
        }

        if (!isset($d['user_type'])) $d['user_type'] = 'employee';

        $data['type'] = $t;
        $data['action'] = $a;
        $data['section'] = $s;
        $data['id'] = $i;
        $data['document'] = $d;
        $urls = get_required_url($data['s3_path']);
        $data['print_url'] = $urls['print_url'];
        $data['download_url'] = $urls['download_url'];
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = $d['document_title'];
        //
        $this->load->view('hr_documents_management/hybrid/print_download_hybird_document', $data);
    }

    function convertMagicCodeToHTML($document, $request_type = 'original')
    {
        $requested_content = '';
        //
        if ($request_type == 'submitted') {
            if (!empty(unserialize($document['form_input_data']))) {
                $is_iframe_preview = 0;
            }

            if (!empty($document['authorized_signature'])) {
                $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['authorized_signature'] . '" id="show_authorized_signature">';
            } else {
                $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            }
            if (!empty($document['authorized_signature_date'])) {
                $authorized_signature_date = '<p><strong>' . date_with_time($document['authorized_signature_date']) . '</strong></p>';
            } else {
                $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            }

            $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_base64'] . '">';
            $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $document['signature_initial'] . '">';
            $sign_date = '<p><strong>' . date_with_time($document['signature_timestamp']) . '</strong></p>';

            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);

            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = $document_content;
        } else {
            if ($request_type == 'assigned') {
                // if (empty($document['submitted_description']) && empty($document['form_input_data'])) {    
                $is_iframe_preview = 0;
            }

            $form_input_data = json_encode(json_decode('assigned'));
            //
            $authorized_signature_date = '------------------------------(Authorized Sign Date Required)';
            $authorized_signature_image = '------------------------------(Authorized Signature Required)';
            $signature_bas64_image = '------------------------------(Signature Required)';
            $init_signature_bas64_image = '------------------------------(Signature Initial Required)';
            $sign_date = '------------------------------(Sign Date Required)';
            //
            $document['document_description'] = str_replace('{{signature}}', $signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{inital}}', $init_signature_bas64_image, $document['document_description']);
            $document['document_description'] = str_replace('{{sign_date}}', $sign_date, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature}}', $authorized_signature_image, $document['document_description']);
            $document['document_description'] = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document['document_description']);
            //
            $document_content = replace_tags_for_document($document['company_sid'], $document['user_sid'], $document['user_type'], $document['document_description'], $document['document_sid'], 1);
            $requested_content = $document_content;
        }
        //
        return $requested_content;
    }


    //
    public function get_print_url_secure()
    {
        if ($this->session->userdata('logged_in')) {
            $document_sid = $this->input->post('document_sid');
            $url = get_print_document_url_secure($document_sid);
            echo json_encode($url);
        }
    }

    //
    public function print_generated_and_offer_later_secure($document_sid, $download = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $document = $this->hr_documents_management_model->getSecureDocuemntById($document_sid);
            //
            $document_file = AWS_S3_BUCKET_URL . $document['document_s3_name'];
            $data['print'] = 'generated';
            $data['document_file'] = 'no_pdf';
            $data['download'] = $download;
            $data['file_name'] = $document['document_title'];
            $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
            $this->load->view('hr_documents_management/print_generated_document', $data);
        }
    }



    //
    function print_download_hybird_document_resource_center(
        $i
    ) {

        //die($i);
        //
        $data["s3_path"] = '';
        $data["document_body"] = '';
        //

        $d = $this->hr_documents_management_model->getDocumentByIdResourceDocuments($i);
        // _e( $d,true,true);

        $d['user_type'] = null;
        $d['user_sid'] = null;
        $d['document_sid'] = null;
        $data["s3_path"] = $d['file_code'];

        $d['document_description'] =  $d['word_content'];

        $document_body = $this->convertMagicCodeToHTML($d);
        $data["document_body"] = $document_body;
        //     

        if (!isset($d['user_type'])) $d['user_type'] = 'employee';

        $data['type'] = 'original';
        $data['action'] = 'download';
        $data['section'] = 'both';
        $data['id'] = $i;
        $data['document'] = $d;
        $urls = get_required_url($data['s3_path']);
        $data['print_url'] = $urls['print_url'];
        $data['download_url'] = $urls['download_url'];
        $data['company_sid'] = $data['session']['company_detail']['sid'];
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = $d['file_name'];
        //
        $this->load->view('hr_documents_management/hybrid/print_download_hybird_document', $data);
    }

    /**
     * handle state form process
     *
     * @param int $userId
     * @param int $userType
     * @return
     */
    public function handleStateForm(int $userId, string $userType)
    {
        // get the post
        $post = $this->input->post(null, true);
        //
        $this->hr_documents_management_model->handleStateForm(
            $userId,
            $userType,
            $post["formId"],
            $post["eventType"]
        );
    }

    /**
     * handle state form process
     *
     * @param int $formId
     */
    public function signMyStateForm(int $formId)
    {
        if (!$this->session->userdata('logged_in')) {
            return redirect("login");
        }
        //
        $data['title'] = 'State forms sign';
        $data['session'] = $this->session->userdata('logged_in');
        $data['employee'] = $data['session']['employer_detail'];
        $employeeId = $data['session']['employer_detail']['sid'];
        $data['security_details'] = db_get_access_level_details($employeeId);
        $companyId = $data['session']['company_detail']['sid'];
        $companyName = $data['session']['employer_detail']['CompanyName'];
        $data['company_sid'] = $companyId;
        $data['employer_sid'] = $employeeId;

        $data["signature"] = [
            "companyId" => $companyId,
            "companyName" => $companyName,
            "userId" => $employeeId,
            "userType" => "employee",
            "firstName" => $employee["first_name"],
            "lastName" => $employee["last_name"],
            "email" => $employee["email"],
        ];

        // get the form
        $form =  $this->hr_documents_management_model
            ->getStateForm(
                $companyId,
                $formId,
                $employeeId,
                "employee"
            );

        if (!$form) {
            return redirect("dashboard");
        }

        // get states
        $data["states"] = $this->hr_documents_management_model->getStates();

        $data["appJs"] = bundleJs([
            "js/app_helper",
            "v1/forms/" . $form["form_slug"],
        ], "public/v1/forms/", $form["form_slug"], true);

        $formData = [];

        if (!$form['is_completed']) {
            $employeeData = $this->hr_documents_management_model->getEmployeeData($employeeId);
            //
            $formData['first_name'] = $employeeData['first_name'];
            $formData['initial'] = $employeeData['middle_initial'];
            $formData['last_name'] = $employeeData['last_name'];
            $formData['ssn'] = $employeeData['ssn'];
            $formData['street_1'] = $employeeData['Location_Address'];
            $formData['street_2'] = $employeeData['Location_Address_2'];
            $formData['city'] = $employeeData['Location_City'];
            $formData['state'] = $employeeData['Location_State'];
            $formData['zip_code'] = $employeeData['Location_ZipCode'];
            $formData['country'] = "USA";
            $formData['day_time_phone_number'] = $employeeData['PhoneNumber'];
            //
            $marital_status = 1;
            //
            if ($employeeData['marital_status'] == 'Married') {
                $marital_status = 2;
            } else if ($employeeData['marital_status'] == 'Other') {
                $marital_status = 3;
            }
            //
            $formData['marital_status'] = $marital_status;
            $data['input'] = '';
            //
        } else {
            //
            $e_signature_data = get_e_signature($companyId, $employeeId, 'employee');
            $formData = $form['form_data'];
            $data['input'] = 'disabled';
            $data['signature'] = $e_signature_data['signature_bas64_image'];
        }
        //
        $data["formData"] = $formData;
        $data['helpSection'] = 'v1/forms/' . $form["form_slug"] . '_employee_help_section';
        $data['userId'] = $employeeId;
        $data['userType'] = "employee";
        $data['formId'] = $formId;
        //
        $this->load->view('onboarding/on_boarding_header', $data);
        $this->load->view('v1/forms/' . $form["form_slug"]);
        $this->load->view('onboarding/on_boarding_footer');
    }

    function saveMyStateForm(int $formId)
    {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect("login");
        }
        //
        $session = $this->session->userdata('logged_in');
        $employeeId = $session['employer_detail']['sid'];
        //
        $post = $this->input->post(null, true);
        $formData = json_encode($post);
        //
        $dataToUpdate = [
            'fields_json' => $formData,
            'user_consent' => 1,
            'user_consent_at' => getSystemDate(),
            "updated_at" => getSystemDate()
        ];
        //
        $this->hr_documents_management_model->updateStateForm(
            $formId,
            $employeeId,
            $dataToUpdate
        );
        if (checkIfAppIsEnabled(PAYROLL)) {
            //
            if ($stateFormId == 1) {
                // load the w4 model
                $this->load->model("v1/Payroll/W4_payroll_model", "w4_payroll_model");
                //
                $this->w4_payroll_model
                    ->pushMinnesotaStateFormOfEmployeeToGusto(
                        $employeeId,
                        $formId
                    );
            }
        }
        //
        $this->res['Status'] = TRUE;
        $this->res['Data'] = $documents;
        $this->res['Response'] = 'Proceed';
        $this->resp();
    }

    function getStateFormPreview(
        int $userId,
        string $userType,
        int $formId
    ) {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect("login");
        }
        //
        $session = $this->session->userdata('logged_in');
        $companyId = $session['company_detail']['sid'];
        //
        $form =  $this->hr_documents_management_model
            ->getStateForm(
                $companyId,
                $formId,
                $userId,
                $userType
            );
        //
        $formData = [];

        if (!$form['is_completed']) {
            $employeeData = $this->hr_documents_management_model->getEmployeeData($userId);
            //
            $formData['first_name'] = $employeeData['first_name'];
            $formData['initial'] = $employeeData['middle_initial'];
            $formData['last_name'] = $employeeData['last_name'];
            $formData['ssn'] = $employeeData['ssn'];
            $formData['street_1'] = $employeeData['Location_Address'];
            $formData['street_2'] = $employeeData['Location_Address_2'];
            $formData['city'] = $employeeData['Location_City'];
            $formData['state'] = !empty($employeeData['Location_State']) ? db_get_state_name_only($employeeData['Location_State']) : '';
            $formData['zip_code'] = $employeeData['Location_ZipCode'];
            $formData['country'] = !empty($employeeData['Location_Country']) ? db_get_country_name($employeeData['Location_Country'])['country_name'] : '';
            $formData['day_time_phone_number'] = $employeeData['PhoneNumber'];
            //
            $marital_status = 1;
            //
            if ($employeeData['marital_status'] == 'Married') {
                $marital_status = 2;
            } else if ($employeeData['marital_status'] == 'Other') {
                $marital_status = 3;
            }
            //
            $formData['marital_status'] = $marital_status;
            $data['input'] = '';
            //
        } else {
            //
            $e_signature_data = get_e_signature($companyId, $userId, $userType);
            $formData = $form['form_data'];
            $data['input'] = 'readonly';
            $data['signature'] = $e_signature_data['signature_bas64_image'];
        }
        //
        if ($form['employer_json']) {
            $data['employerData'] = $form['employer_json'];
            $data['employerData']['state'] = !empty($form['employer_json']['state']) ? db_get_state_name_only($form['employer_json']['state']) : '';
        }
        //
        $data["formData"] = $formData;
        // _e($form,true,true);
        // 
        $view = $this->load->view('v1/forms/' . $form["form_slug"] . '_preview', $data, true);
        //  
        return SendResponse(200, ['view' => $view, 'title' => $form['title']]);
    }

    function stateFormPrintAndDownload(
        int $userId,
        string $userType,
        int $formId,
        string $location,
        string $action
    ) {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect("login");
        }
        //
        $session = $this->session->userdata('logged_in');
        $companyId = $session['company_detail']['sid'];
        //
        $form =  $this->hr_documents_management_model
            ->getStateForm(
                $companyId,
                $formId,
                $userId,
                $userType
            );
        //
        $formData = [];

        if (!$form['is_completed']) {
            $employeeData = $this->hr_documents_management_model->getEmployeeData($userId);
            //
            $formData['first_name'] = $employeeData['first_name'];
            $formData['initial'] = $employeeData['middle_initial'];
            $formData['last_name'] = $employeeData['last_name'];
            $formData['ssn'] = $employeeData['ssn'];
            $formData['street_1'] = $employeeData['Location_Address'];
            $formData['street_2'] = $employeeData['Location_Address_2'];
            $formData['city'] = $employeeData['Location_City'];
            $formData['state'] = !empty($employeeData['Location_State']) ? db_get_state_name_only($employeeData['Location_State']) : '';
            $formData['zip_code'] = $employeeData['Location_ZipCode'];
            $formData['country'] = !empty($employeeData['Location_Country']) ? db_get_country_name($employeeData['Location_Country'])['country_name'] : '';
            $formData['day_time_phone_number'] = $employeeData['PhoneNumber'];
            //
            $marital_status = 1;
            //
            if ($employeeData['marital_status'] == 'Married') {
                $marital_status = 2;
            } else if ($employeeData['marital_status'] == 'Other') {
                $marital_status = 3;
            }
            //
            $formData['marital_status'] = $marital_status;
            $data['input'] = '';
            //
        } else {
            //
            $e_signature_data = get_e_signature($companyId, $userId, $userType);
            $formData = $form['form_data'];
            $data['input'] = 'readonly';
            $data['signature'] = $e_signature_data['signature_bas64_image'];
        }
        //
        if ($form['employer_json']) {
            $data['employerData'] = $form['employer_json'];
            $data['employerData']['state'] = !empty($form['employer_json']['state']) ? db_get_state_name_only($form['employer_json']['state']) : '';
        }
        //
        $data["formData"] = $formData;
        //
        $data["action"] = $action;
        $data["location"] = $location;
        $data['formName'] = $form["form_slug"];
        // 
        $this->load->view('v1/forms/' . $form["form_slug"] . '_print_download', $data);
    }

    function getEmployerSection(
        int $formId,
        int $userId,
        string $userType
    ) {
        $session = $this->session->userdata('logged_in');
        $companyId = $session['company_detail']['sid'];
        //
        $formInfo = $this->hr_documents_management_model->getStateForm(
            $companyId,
            $formId,
            $userId,
            $userType
        );
        //
        $data["states"] = $this->hr_documents_management_model->getStates();
        $data["formInfo"] = $formInfo;

        $data['helpSection'] = '';

        $view = $this->load->view('v1/forms/' . $formInfo["form_slug"] . '_employer_section', $data, true);
        //  
        return SendResponse(200, ['view' => $view, 'title' => $formInfo['title']]);
    }

    public function saveStateFormEmployerSection(
        int $formId,
        int $userId,
        string $userType
    ) {
        //
        if (!$this->session->userdata('logged_in')) {
            return redirect("login");
        }
        //
        $session = $this->session->userdata('logged_in');
        $employeeId = $session['employer_detail']['sid'];
        //
        $post = $this->input->post(null, true);
        $formData = json_encode($post);
        //
        $this->hr_documents_management_model->saveStateFormEmployerSection(
            $formId,
            $userId,
            $userType,
            $formData
        );
    }


    private function checkAndSetEmployerSection(
        array &$w4Form,
        string $userType,
        int $userId
    ) {
        //
        $companyDetail = checkAndGetSession("company");
        //
        $data = getDataForEmployerPrefill(
            $companyDetail["sid"],
            $userId,
            $userType
        );
        //
        $updateArray = [];
        $updateArray["emp_name"] = $w4Form["emp_name"] ?? $data["CompanyName"];
        $updateArray["emp_address"] = $w4Form["emp_address"] ?? $data["companyAddress"];
        if ((!$w4Form["first_date_of_employment"] || $w4Form["first_date_of_employment"] == "0000-00-00") && $data["first_day_of_employment"]) {
            $updateArray["first_date_of_employment"] = formatDateToDB($data["first_day_of_employment"], "m-d-Y", DB_DATE);
        }
        $updateArray["emp_identification_number"] = $w4Form["emp_identification_number"] ?? $data["ssn"];

        $w4Form = array_merge(
            $w4Form,
            $updateArray
        );
        if ($updateArray["first_date_of_employment"]) {
            $w4Form["first_date_of_employment"] = formatDateToDB(
                $updateArray["first_date_of_employment"],
                DB_DATE,
                "m-d-Y"
            );
        }
        //
        if ($w4Form['user_consent'] == 1) {
            $updateArray["signature_timestamp"] = $w4Form['signature_timestamp'];
        }
        //
        $this->db
            ->where("sid", $w4Form["sid"])
            ->update("form_w4_original", $updateArray);
    }




    //
    public function documents_group_management_ajax()
    {

        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;
        check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

        $company_sid = $data['session']['company_detail']['sid'];
        $employer_sid = $data['session']['employer_detail']['sid'];


        $employees = $this->input->post('employees');
        $group_assign_sid = $this->input->post('group_sid');

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['group_sid'] = $group_assign_sid;
        $data_to_insert['assigned_by_sid'] = $employer_sid;
        $data_to_insert['applicant_sid'] = 0;
        if (in_array('-1', $employees)) {
            $Allemployees = $this->hr_documents_management_model->fetch_all_company_employees($company_sid);
            $employees = array_column($Allemployees, 'sid');
        }

        if (!empty($employees)) {
            foreach ($employees as $key => $employee) {
                $data_to_insert['employer_sid'] = $employee;
                $is_group_assign = $this->hr_documents_management_model->check_group_already_assigned($company_sid, $employee, $group_assign_sid);

                if ($is_group_assign == 0) {
                    $this->hr_documents_management_model->assign_document_group_2_empliyees($data_to_insert);
                }
            }
        }


        //
        $employeesdata = $this->hr_documents_management_model->fetch_company_employees_by_id($company_sid, $employees);
        //
        if (!empty($employeesdata)) {
            foreach ($employeesdata as $e_key => $employee) {
                $employeesdata[$e_key]["full_name"] = getUserNameBySID($employee["sid"]);
            }
        }
        //

        echo json_encode($employeesdata);
    }



    //
    public function assigne_group_managements_ajax()
    {
        //
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];

        $employee_sid = $this->input->post('employee_sid');
        //
        $this->hr_documents_management_model
            ->assignGroupDocumentsToUser(
                $employee_sid,
                "employee",
                0,
                true,
                $company_sid,
                0
            );
        echo 'ok';
    }
}
