<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class hr_documents_management_test extends Public_Controller {

    public function __construct() {
        parent::__construct();

        if ($this->session->userdata('logged_in')) {
            $this->load->model('hr_documents_management_model');
            $this->load->model('onboarding_model');
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function index() {
        $starttime = microtime(true);
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
                    foreach ($groups as $key => $group) {
                        $group_status = $group['status'];
                        $group_sid = $group['sid'];
                        $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 0);

                        if ($group_status) {
                            $active_groups[] = array('sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents);
                        } else {
                            $in_active_groups[] = array('sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents);
                        }
                    }
                }
                $all_documents =$this->hr_documents_management_model->get_total_documents($company_sid);


                $uncategorized_documents = $this->hr_documents_management_model->get_uncategorized_docs($company_sid, $document_ids, 0);

                $data['uncategorized_documents'] = $uncategorized_documents;
                $data['active_groups'] = $active_groups;
                $data['all_documents'] = $all_documents;
                $data['in_active_groups'] = $in_active_groups;
                $offer_letters = $this->hr_documents_management_model->get_all_offer_letters($company_sid, 0);
                $data['offer_letters'] = $offer_letters;
                $sections = $this->hr_documents_management_model->get_hr_documents_section_records(1); //Get Editors Data
                $data['sections'] = $sections;
                $endtime = microtime(true); // Bottom of page

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/index');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

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
                        redirect('hr_documents_management_test', 'refresh');
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
                        redirect('hr_documents_management_test', 'refresh');
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
                                $data_to_update['submitted_description'] = NULL;
                                $data_to_update['signature_base64'] = NULL;
                                $data_to_update['signature_initial'] = NULL;
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
                                $data_to_insert['document_description'] = htmlentities($this->input->post('document_description'));

                                $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            }
                            $user_info = array();
                            switch ($user_type) {
                                case 'employee':
                                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $emp);
                                    //Send Email and SMS
                                    $replacement_array = array();
                                    $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                    $replacement_array['company_name'] = ucwords($company_name);
                                    $replacement_array['firstname'] = $user_info['first_name'];
                                    $replacement_array['lastname'] = $user_info['last_name'];
                                    $replacement_array['first_name'] = $user_info['first_name'];
                                    $replacement_array['last_name'] = $user_info['last_name'];
                                    $replacement_array['baseurl'] = base_url();
                                    $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                                    //SMS Start
                                    $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                    if($company_sms_notification_status){
                                        $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                        $sms_notify = 0 ;
                                        if(strpos($notify_by['notified_by'],'sms') !== false){
                                            $contact_no = $notify_by['PhoneNumber'];
                                            $sms_notify = 1;
                                        }
                                        if($sms_notify){
                                            $this->load->library('Twilioapp');
                                            // Send SMS
                                            $sms_template = get_company_sms_template($this,$company_sid,'hr_document_notification');
                                            $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_array);
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
                                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);
                                    break;
                                case 'applicant':
                                    $user_info = $this->hr_documents_management_model->get_applicant_information($company_sid, $emp);
                                    break;
                            }
                        }

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Bulk Assigned!');
                        redirect('hr_documents_management_test/', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function archived_documents() {
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

            if ($this->form_validation->run() == false) {
                if (!empty($groups)) {
                    foreach ($groups as $key => $group) {
                        $group_status = $group['status'];
                        $group_sid = $group['sid'];
                        $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 1);

                        if ($group_status) {
                            $active_groups[] = array('sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents);
                        } else {
                            $in_active_groups[] = array('sid' => $group_sid,
                                'name' => $group['name'],
                                'sort_order' => $group['sort_order'],
                                'description' => $group['description'],
                                'created_date' => $group['created_date'],
                                'w4' => $group['w4'],
                                'w9' => $group['w9'],
                                'i9' => $group['i9'],
                                'documents_count' => count($group_documents),
                                'documents' => $group_documents);
                        }
                    }
                }

                $uncategorized_documents = $this->hr_documents_management_model->get_uncategorized_docs($company_sid, $document_ids, 1);
                $data['uncategorized_documents'] = $uncategorized_documents;
                $data['active_groups'] = $active_groups;
                $data['in_active_groups'] = $in_active_groups;
                $offer_letters = $this->hr_documents_management_model->get_all_offer_letters($company_sid, 1);
                $data['offer_letters'] = $offer_letters;

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/archived_document');
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
                        redirect('hr_documents_management_test', 'refresh');
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
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function upload_new_document() {
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
            $data['active_categories'] = $this->hr_documents_management_model->get_all_documents_category($company_sid,1);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/upload_new_document');
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
                            $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                        } else {
                            $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);
                        }
                        $uploaded_document_original_name = $document_title;

                        if (isset($_FILES['document']['name'])) {
                            $uploaded_document_original_name = $_FILES['document']['name'];
                        }

                        $file_info = pathinfo($uploaded_document_original_name);
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
                        if(!empty($this->input->post('sort_order')))
                            $data_to_insert['sort_order'] = $this->input->post('sort_order');
                        else
                            $data_to_insert['sort_order'] = 1;

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
                                    redirect('hr_documents_management_test/upload_new_document', 'refresh');
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
                        $this->hr_documents_management_model->insert_document_management_history($new_history_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Upload Successful!');
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function generate_new_document() {
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
            $data['active_categories'] = $this->hr_documents_management_model->get_all_documents_category($company_sid,1);

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/generate_new_document');
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
                        $data_to_insert['document_type'] = 'generated';
                        if(!empty($this->input->post('sort_order')))
                            $data_to_insert['sort_order'] = $this->input->post('sort_order');
                        else
                            $data_to_insert['sort_order'] = 1;
                        $data_to_insert['unique_key'] = generateRandomString(32);
                        $data_to_insert['onboarding'] = $this->input->post('onboarding');
                        $data_to_insert['download_required'] = $this->input->post('download_required');
                        $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $data_to_insert['signature_required'] = $this->input->post('signature_required');
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
                                    redirect('hr_documents_management_test/generate_new_document', 'refresh');
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
                        $new_history_data['document_type'] = 'generated';
                        $new_history_data['date_created'] = date('Y-m-d H:i:s');
                        $new_history_data['update_by_sid'] = $employer_sid;
                        $new_history_data['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                        $new_history_data['download_required'] = $this->input->post('download_required');
                        $new_history_data['signature_required'] = $this->input->post('signature_required');
                        $this->hr_documents_management_model->insert_document_management_history($new_history_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Generated Successfully!');
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    // The below function is not working properly
    // working not completed
    public function upload_new_offer_letter() {
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
            $data['active_categories'] = $this->hr_documents_management_model->get_all_documents_category($company_sid,1);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/upload_new_offer_letter');
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
                        if(!empty($this->input->post('sort_order')))
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
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function generate_new_offer_letter() {
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
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/generate_new_offer_letter');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                $new_history_data = array();

                switch ($perform_action) {
                    case 'generate_new_offer_letter':
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
                        if(!empty($this->input->post('sort_order')))
                            $offer_letter_data['sort_order'] = $this->input->post('sort_order');
                        else
                            $offer_letter_data['sort_order'] = 1;
                        $offer_letter_data['created_date'] = date('Y-m-d H:i:s');
                        $insert_id = $this->hr_documents_management_model->add_new_offer_letter($offer_letter_data);

                        // Tracking History For New Inserted Doc in new history table
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
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_hr_document($sid = NULL, $redirect = 'index') {
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
            $data['active_categories'] = $this->hr_documents_management_model->get_all_documents_category($company_sid,1);
            $pre_assigned_category_data = $this->hr_documents_management_model->get_all_category_2_document($sid);

            if (!empty($pre_assigned_category_data)) {
                foreach ($pre_assigned_category_data as $pagd) {
                    $pre_assigned_categories[] = $pagd['category_sid'];
                }
            }
            $data['assigned_categories'] = $pre_assigned_categories;

            $data['pre_assigned_groups'] = $pre_assigned_groups;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $document_info = $this->hr_documents_management_model->get_hr_document_details($company_sid, $sid);

                if (!empty($document_info)) {
                    $data['document_info'] = $document_info;
                    $document_type = $document_info['document_type'];
                    $data['title'] = $document_type == 'uploaded' ? 'Modify Uploaded HR Document' : 'Modify Generated HR Document';

                    if ($document_type == 'generated') {
                        $authorized_signature_exist = $this->hr_documents_management_model->is_authorized_signature_exist($sid, $company_sid);
                        $data['authorized_signature'] = $authorized_signature_exist;
                    }

                    $this->load->view('main/header', $data);

                    if ($document_type == 'uploaded') {
                        $this->load->view('hr_documents_management_test/upload_new_document');
                    } else {
                        $this->load->view('hr_documents_management_test/generate_new_document');
                    }

                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> HR Document not found!');
                    redirect('hr_documents_management_test', 'refresh');
                }
            } else {
                $perform_action = $this->input->post('perform_action');
                $type = $this->input->post('type');

                switch ($perform_action) {
                    case 'update_document':
                        $document_name = $this->input->post('document_title');
                        $document_description = $this->input->post('document_description');
                        $video_required = $this->input->post('video_source');
                        $document_description = htmlentities($document_description);
                        // $action_required = $this->input->post('action_required');
                        $data_to_update = array();

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
                                    redirect('hr_documents_management_test/edit_hr_document', 'refresh');
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
                        if($this->input->post('to_pay_plan') == 'yes'){
                            $this->convertDocumentToPayPlan();
                            exit(0);
                        }

                        if ($redirect == 'index') {
                            redirect('hr_documents_management_test', 'refresh');
                        } else {
                            redirect('hr_documents_management_test/archived_documents', 'refresh');
                        }

                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_offer_letter($sid = NULL) {
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
                    $this->load->view('hr_documents_management_test/generate_new_offer_letter');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Offer letter not found!');
                    redirect('hr_documents_management_test', 'refresh');
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
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_uploaded_offer_letter($sid = NULL) {
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
                    $this->load->view('hr_documents_management_test/upload_new_offer_letter');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Offer letter not found!');
                    redirect('hr_documents_management_test', 'refresh');
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
                        redirect('hr_documents_management_test', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_responder() {
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

                        if ($source == 'offer') {
                            $document_info = $this->hr_documents_management_model->get_offer_letter_details($company_sid, $document_sid);
                        }
                        if (!empty($document_info)) {
                            $document_content = $source == 'offer' ? $document_info['letter_body'] : $document_info['document_description'];
                            $document = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_content, $document_sid);
                            $view_data = array();
                            $view_data['document_title'] = $source == 'offer' ? $document_info['letter_name'] : $document_info['document_title'];
                            $view_data['document_body'] = $document;
                            echo $this->load->view('hr_documents_management_test/generated_document_preview_partial', $view_data, true);
                        }

                        break;
                }
            }
        }
    }

    public function documents_assignment($user_type = NULL, $user_sid = NULL, $jobs_listing = NULL) {
        $starttime = microtime(true);
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $pp_flag = $data['session']['employer_detail']['pay_plan_flag'];
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
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
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid);
                    $data['eeo_form_info'] = $eeo_form_info;
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
                    $eeo_form_status = $this->hr_documents_management_model->get_eeo_form_status($user_sid);
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid);
                    $data['eeo_form_status'] = $eeo_form_status;
                    $data['eeo_form_info'] = $eeo_form_info;

                    $data_employer = array('sid' => $applicant_info['sid'],
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
                        'user_type' => ucwords($user_type));

                    $data['applicant_average_rating'] = $this->hr_documents_management_model->getApplicantAverageRating($user_sid, 'applicant'); //getting average rating of applicant
                    $data['employer'] = $data_employer;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $applicant_info['sid'];
                    break;
            }

            // Check for post
            if(isset($_POST) && sizeof($_POST)){
                $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
                if($this->form_validation->run() != false){
                    $perform_action = $this->input->post('perform_action');
                    switch ($perform_action) {
                        case 'activate_uploaded_document':
                            $document_sid = $this->input->post('document_sid');
                            $document_type = $this->input->post('document_type');
                            $this->hr_documents_management_model->update_documents($document_sid, array('archive' => 0), 'documents_assigned');
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Activated!');
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'archive_uploaded_document':
                            $document_sid = $this->input->post('document_sid');
                            $document_type = $this->input->post('document_type');
                            $this->hr_documents_management_model->update_documents($document_sid, array('archive' => 1), 'documents_assigned');
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Hr Document Archived!');
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
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
                                // $data_to_update['is_pending'] = 1;
                                $this->hr_documents_management_model->update_documents($assignment_sid, $data_to_update, 'documents_assigned');
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

                                $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            }

                            if($user_type == 'employee'){
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                                //SMS Start
                                $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                if($company_sms_notification_status){
                                    $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                    $sms_notify = 0 ;
                                    if(strpos($notify_by['notified_by'],'sms') !== false){
                                        $contact_no = $notify_by['PhoneNumber'];
                                        $sms_notify = 1;
                                    }
                                    if($sms_notify){
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this,$company_sid,'hr_document_notification');
                                        $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_array);
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
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);

                            }
                            //
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');
                            redirect('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'remove_document':
                            $document_type = $this->input->post('document_type');
                            $document_sid = $this->input->post('document_sid');
                            $data = array();
                            $data['status'] = 0;
                            // $data['is_pending'] = 1;
                            $this->hr_documents_management_model->assign_revoke_assigned_documents($document_sid, $document_type, $user_sid, $user_type, $data);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'assign_w4': //W4 Form Active
                            $w4_form_history = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);

                            if (empty($w4_form_history)) {
                                $w4_data_to_insert = array();
                                $w4_data_to_insert['employer_sid'] = $user_sid;
                                $w4_data_to_insert['company_sid'] = $company_sid;
                                $w4_data_to_insert['user_type'] = $user_type;
                                $w4_data_to_insert['sent_status'] = 1;
                                $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $w4_data_to_insert['status'] = 1;
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
                                $w4_data_to_insert['user_consent']                          = 0;
                                $w4_data_to_insert['uploaded_file']                         = NULL;
                                $w4_data_to_insert['uploaded_by_sid']                       = 0;

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

                            if($user_type == 'employee') {
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                                //SMS Start
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
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);
                            }

                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_w4': //W4 Form Deactive
                            $this->hr_documents_management_model->deactivate_w4_forms($user_type, $user_sid);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

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
                                $already_assigned_w9['user_consent'] = NULL;
                                $already_assigned_w9['uploaded_file'] = NULL;
                                $already_assigned_w9['uploaded_by_sid'] = 0;
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
                            $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                            if($user_type == 'employee') {
                                //SMS Start
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
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);
                            }
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_w9': //W9 Form Deactive
                            $this->hr_documents_management_model->deactivate_w9_forms($user_type, $user_sid);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
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
                                $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                            } else {
                                $already_assigned_i9['i9form_ref_sid'] = $already_assigned_i9['sid'];
                                unset($already_assigned_i9['sid']);
                                $this->hr_documents_management_model->i9_forms_history($already_assigned_i9);
                                $this->hr_documents_management_model->delete_i9_form($already_assigned_i9['i9form_ref_sid']);
                                $i9_data_to_insert = array();
                                $i9_data_to_insert['sid'] = $already_assigned_i9['i9form_ref_sid'];
                                $i9_data_to_insert['user_sid'] = $user_sid;
                                $i9_data_to_insert['user_type'] = $user_type;
                                $i9_data_to_insert['company_sid'] = $company_sid;
                                $i9_data_to_insert['sent_status'] = 1;
                                $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                                $i9_data_to_insert['status'] = 1;
                                $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                                //  $this->hr_documents_management_model->activate_i9_forms($user_type, $user_sid);
                            }


                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');

                            if ($user_type == 'employee') {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            }

                            break;
                        case 'remove_i9': //I9 Form Deactive
                            $this->hr_documents_management_model->deactivate_i9_forms($user_type, $user_sid);
                            $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Revoked!');

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
                                $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                                //SMS Start
                                $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                if($company_sms_notification_status){
                                    $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                    $sms_notify = 0 ;
                                    if(strpos($notify_by['notified_by'],'sms') !== false){
                                        $contact_no = $notify_by['PhoneNumber'];
                                        $sms_notify = 1;
                                    }
                                    if($sms_notify){
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this,$company_sid,'hr_document_notification');
                                        $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_array);
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
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);


                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                            } else {
                                $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
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
                                $data_to_update['manual_document_type'] = 'offer_letter';
                            } else {
                                $data_to_update['manual_document_type'] = NULL;
                            }

                            if (isset($_POST['doc_assign_date']) && !empty($_POST['doc_assign_date'])) { //check if document has assign date
                                $data_to_update['assigned_date'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_assign_date'])->format('Y-m-d');
                            }

                            if (isset($_POST['doc_sign_date']) && !empty($_POST['doc_sign_date'])) { //check if document has sign date
                                $data_to_update['signature_timestamp'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_sign_date'])->format('Y-m-d');
                            }

                            if(!empty($_FILES['document']['name'])){
                                $uploaded_document_original_name = '';

                                if (isset($_FILES['document']['name'])) {
                                    $uploaded_document_original_name = $_FILES['document']['name'];
                                }
                                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                    $uploaded_document_s3_name = '0003-d_6-1542874444-39O.jpg';
                                    // $uploaded_document_s3_name = '0057-testing_uploaded_doc-58-AAH.docx';
                                    // $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                                } else {
                                    $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $uploaded_document_original_name), $employer_sid, AWS_S3_BUCKET_NAME);
                                }

                                $file_info = pathinfo($uploaded_document_original_name);

                                $data_to_update['company_sid'] = $company_sid;
                                if (isset($file_info['extension'])) {
                                    $data_to_update['document_extension'] = $file_info['extension'];
                                }
                                if ($uploaded_document_s3_name != 'error') {
                                    $data_to_update['document_original_name'] = $uploaded_document_original_name;
                                    $data_to_update['document_s3_name'] = $uploaded_document_s3_name;
                                    $data_to_update['uploaded_file'] = $uploaded_document_s3_name;
                                    $data_to_update['uploaded'] = 1;
                                    $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                                } else {
                                    $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                                    $this->redirectHandler('hr_documents_management_test', 'refresh');
                                }
                            }

                            $this->hr_documents_management_model->update_documents_assignment_record($this->input->post('documents_assigned_sid'),$data_to_update);
                            $this->hr_documents_management_model->add_update_categories_2_documents($this->input->post('documents_assigned_sid'),$this->input->post('categories'),"documents_assigned");
                            $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Reupload Successful!');
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'assign_specific':
                            $document_title = $this->input->post('document_title');
                            $document_description = $this->input->post('document_description');
                            $document_description = htmlentities($document_description);
                            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                // $uploaded_document_s3_name = '0003-d_6-1542874444-39O.jpg';
                                // $uploaded_document_s3_name = '0057-testing_uploaded_doc-58-AAH.docx';
                                $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            } else {
                                $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_title), $employer_sid, AWS_S3_BUCKET_NAME);
                            }
                            $uploaded_document_original_name = $document_title;

                            if (isset($_FILES['document']['name'])) {
                                $uploaded_document_original_name = $_FILES['document']['name'];
                            }

                            $file_info = pathinfo($uploaded_document_original_name);
                            $data_to_insert = array();
                            if (isset($_POST['accessable'])) { //check if document is uploaded for access level plus
                                $data_to_insert['document_type'] = 'confidential';
                            } else {

                                $data_to_insert['document_type'] = 'uploaded';
                            }

                            if (isset($_POST['is_offer_letter'])) { //check if document is offer letter
                                $data_to_insert['manual_document_type'] = 'offer_letter';
                            }

                            if (isset($_POST['doc_assign_date']) && $_POST['doc_assign_date'] != '') { //check if document has assign date
                                $data_to_insert['assigned_date'] = DateTime::createFromFormat('m-d-Y', $_POST['doc_assign_date'])->format('Y-m-d');
                            } else {
                                $data_to_insert['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
                            }

                            if (isset($_POST['doc_sign_date']) && $_POST['doc_assign_date'] != '') { //check if document has sign date
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
                            // $data_to_insert['acknowledgment_required'] = $this->input->post('acknowledgment_required');
                            // $data_to_insert['download_required'] = $this->input->post('download_required');
                            // $data_to_insert['signature_required'] = $this->input->post('signature_required');

                            if (isset($file_info['extension'])) {
                                $data_to_insert['document_extension'] = $file_info['extension'];
                            }

                            if ($uploaded_document_s3_name != 'error') {
                                $data_to_insert['document_original_name'] = $uploaded_document_original_name;
                                $data_to_insert['document_s3_name'] = $uploaded_document_s3_name;
                                $data_to_insert['uploaded_file'] = $uploaded_document_s3_name;
                                $data_to_insert['uploaded'] = 1;
                                $data_to_insert['uploaded_date'] = date('Y-m-d H:i:s');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                                $this->redirectHandler('hr_documents_management_test', 'refresh');
                            }

                            $insert_id = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                            $this->hr_documents_management_model->add_update_categories_2_documents($insert_id,$this->input->post('categories'),"documents_assigned");

                            if(!isset($_POST['accessable']) && $user_type == 'employee'){
                                //Send Email and SMS
                                $replacement_array = array();
                                $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                                $replacement_array['company_name'] = ucwords($company_name);
                                $replacement_array['firstname'] = $user_info['first_name'];
                                $replacement_array['lastname'] = $user_info['last_name'];
                                $replacement_array['first_name'] = $user_info['first_name'];
                                $replacement_array['last_name'] = $user_info['last_name'];
                                $replacement_array['baseurl'] = base_url();
                                $replacement_array['url'] = base_url('hr_documents_management_test/my_documents');
                                //SMS Start
                                $company_sms_notification_status = get_company_sms_status($this, $company_sid);
                                if($company_sms_notification_status){
                                    $notify_by = get_employee_sms_status($this, $user_info['sid']);
                                    $sms_notify = 0 ;
                                    if(strpos($notify_by['notified_by'],'sms') !== false){
                                        $contact_no = $notify_by['PhoneNumber'];
                                        $sms_notify = 1;
                                    }
                                    if($sms_notify){
                                        $this->load->library('Twilioapp');
                                        // Send SMS
                                        $sms_template = get_company_sms_template($this,$company_sid,'hr_document_notification');
                                        $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_array);
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
                                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);

                            }
                            $this->session->set_flashdata('message', '<strong>Success:</strong> HR Document Upload Successful!');
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                        case 'revoke_eeoc':
                            $eeoc_form_history = $this->hr_documents_management_model->get_eeo_form_info($user_sid);
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
                            $document_name = 'eev-'.$document_type.'-document';

                            if ($_SERVER['HTTP_HOST'] == 'localhost') {
                                $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
                            } else {
                                $uploaded_document_s3_name = upload_file_to_aws('document', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);
                            }

                            $file_info = pathinfo($uploaded_document_original_name);
                            $data_to_insert = array();

                            if ($uploaded_document_s3_name != 'error') {
                                $data_to_insert['company_sid'] = $company_sid;
                                $data_to_insert['employee_sid'] = $user_sid;
                                $data_to_insert['document_name'] = $uploaded_document_original_name;
                                $data_to_insert['date_uploaded'] = date('Y-m-d H:i:s');
                                $data_to_insert['uploaded_by_sid'] = $employer_sid;
                                $data_to_insert['document_type'] = $this->input->post('document_type');
                                ;
                                $data_to_insert['sid'] = $this->input->post('sid');
                                ;

                                $data_to_insert['s3_filename'] = $uploaded_document_s3_name;

                                $insert_id = $this->hr_documents_management_model->insert_eev_document($data_to_insert);
                                $this->session->set_flashdata('message', '<strong>Success:</strong> Signed Employment Eligibility Verification Document Uploaded Successful!');
                            } else {
                                $this->session->set_flashdata('message', '<strong>Error:</strong> Something went wrong!');
                            }
                            if(!empty($this->input->post('redirect_link') ))
                                $this->redirectHandler ($this->input->post('redirect_link'));
                            $this->redirectHandler('hr_documents_management_test/documents_assignment' . '/' . $user_type . '/' . $user_sid . '/' . $jobs_listing, 'refresh');
                            break;
                    }
                }
            }

            $groups = $this->hr_documents_management_model->get_all_documents_group($company_sid);

            if (!empty($groups)) {
                foreach ($groups as $key => $group) {
                    $document_status = $this->hr_documents_management_model->is_document_assign_2_group($group['sid']);
                    $groups[$key]['document_status'] = $document_status;
                    $group_status = $group['status'];
                    $group_sid = $group['sid'];
                    $group_ids[] = $group_sid;
                    $group_documents = $this->hr_documents_management_model->get_all_documents_in_group($group_sid, 0, $pp_flag);

                    if ($group_status) {
                        $active_groups[] = array('sid' => $group_sid,
                            'name' => $group['name'],
                            'sort_order' => $group['sort_order'],
                            'description' => $group['description'],
                            'created_date' => $group['created_date'],
                            'w4' => $group['w4'],
                            'w9' => $group['w9'],
                            'i9' => $group['i9'],
                            'documents_count' => count($group_documents),
                            'documents' => $group_documents);
                    } else {
                        $in_active_groups[] = array('sid' => $group_sid,
                            'name' => $group['name'],
                            'sort_order' => $group['sort_order'],
                            'description' => $group['description'],
                            'created_date' => $group['created_date'],
                            'w4' => $group['w4'],
                            'w9' => $group['w9'],
                            'i9' => $group['i9'],
                            'documents_count' => count($group_documents),
                            'documents' => $group_documents);
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
                        $active_categories[] = array('sid' => $category_sid,
                            'name' => $category['name'],
                            'sort_order' => $category['sort_order'],
                            'description' => $category['description'],
                            'created_date' => $category['created_date'],
                            'documents_count' => count($category_documents),
                            'documents' => $category_documents);
                    } else {
                        $in_active_categories[] = array('sid' => $category_sid,
                            'name' => $category['name'],
                            'sort_order' => $category['sort_order'],
                            'description' => $category['description'],
                            'created_date' => $category['created_date'],
                            'documents_count' => count($category_documents),
                            'documents' => $category_documents);
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
            $groups_assign = $this->hr_documents_management_model->get_all_documents_group_assigned($company_sid, $user_type, $user_sid);
            $assigned_groups = array();

            if (!empty($groups_assign)) {
                foreach ($groups_assign as $value) {
                    array_push($assigned_groups, $value['group_sid']);
                    $system_document = $this->hr_documents_management_model->get_document_group($value['group_sid']);

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
                            $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
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
                            $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
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
                            $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
                        }
                    }
                }
            }

            $data['assigned_groups'] = $assigned_groups;
            // $categories_assign = $this->hr_documents_management_model->get_all_documents_category_assigned($company_sid, $user_type, $user_sid);
            // $assigned_categories = array();
            // if (!empty($categories_assign)) {
            //     foreach ($categories_assign as $value) {
            //         array_push($assigned_categories, $value['category_sid']);
            //         $system_document = $this->hr_documents_management_model->get_document_category($value['category_sid']);
            //         if ($system_document['w4'] == 1) {
            //             $is_w4_assign = $this->hr_documents_management_model->check_w4_form_exist($user_type, $user_sid);
            //             if (empty($is_w4_assign)) {
            //                 $w4_data_to_insert = array();
            //                 $w4_data_to_insert['employer_sid'] = $user_sid;
            //                 $w4_data_to_insert['company_sid'] = $company_sid;
            //                 $w4_data_to_insert['user_type'] = $user_type;
            //                 $w4_data_to_insert['sent_status'] = 1;
            //                 $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
            //                 $w4_data_to_insert['status'] = 1;
            //                 $this->hr_documents_management_model->insert_w4_form_record($w4_data_to_insert);
            //             }
            //         }
            //         if ($system_document['w9'] == 1) {
            //             $is_w9_assign = $this->hr_documents_management_model->check_w9_form_exist($user_type, $user_sid);
            //             if (empty($is_w9_assign)) {
            //                 $w9_data_to_insert = array();
            //                 $w9_data_to_insert['user_sid'] = $user_sid;
            //                 $w9_data_to_insert['company_sid'] = $company_sid;
            //                 $w9_data_to_insert['user_type'] = $user_type;
            //                 $w9_data_to_insert['sent_status'] = 1;
            //                 $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
            //                 $w9_data_to_insert['status'] = 1;
            //                 $this->hr_documents_management_model->insert_w9_form_record($w9_data_to_insert);
            //             }
            //         }
            //         if ($system_document['i9'] == 1) {
            //             $is_i9_assign = $this->hr_documents_management_model->check_i9_exist($user_type, $user_sid);
            //             if (empty($is_i9_assign)) {
            //                 $i9_data_to_insert = array();
            //                 $i9_data_to_insert['user_sid'] = $user_sid;
            //                 $i9_data_to_insert['user_type'] = $user_type;
            //                 $i9_data_to_insert['company_sid'] = $company_sid;
            //                 $i9_data_to_insert['sent_status'] = 1;
            //                 $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
            //                 $i9_data_to_insert['status'] = 1;
            //                 $this->hr_documents_management_model->insert_i9_form_record($i9_data_to_insert);
            //             }
            //         }
            //     }
            // }

            // $data['assigned_categories'] = $assigned_categories;
            $data['left_navigation'] = $left_navigation;
            $i9_form = $this->hr_documents_management_model->fetch_form('i9', $user_type, $user_sid);
            $w9_form = $this->hr_documents_management_model->fetch_form('w9', $user_type, $user_sid);
            $w4_form = $this->hr_documents_management_model->fetch_form('w4', $user_type, $user_sid);
            $data['i9_form'] = $i9_form;
            $data['w9_form'] = $w9_form;
            $data['w4_form'] = $w4_form;
            $assigned_sids = array();
            $no_action_required_sids = array();
            $completed_sids = array();
            $revoked_sids = array();
            $completed_documents = array();
            $signed_documents = array();
            $signed_document_sids = array();
            $completed_document_sids = array();
            $no_action_required_documents = array();
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
                            $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                        }
                    }
                }
            }

            $active_documents = $this->hr_documents_management_model->get_all_documents($company_sid, 0);
            $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, $user_type, $user_sid, 0, 1, 0, $pp_flag);
            $assigned_offer_letters = $this->hr_documents_management_model->get_assigned_offers($company_sid, $user_type, $user_sid, 0);
            $manual_assigned_offer_letters = $this->hr_documents_management_model->get_manual_assigned_offers($company_sid, $user_type, $user_sid, 0);
            $assigned_offer_letter_history = $this->hr_documents_management_model->get_assigned_offer_letter_history($company_sid, $user_type, $user_sid);
           //_e(count($assigned_documents),true,true);
            foreach ($assigned_documents as $key => $assigned_document) {
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

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
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        }
                    } else {
                        $revoked_sids[] = $assigned_document['document_sid'];
                    }
                }
            }

            $data['w4_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w4');
            $data['w9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'w9');
            $data['i9_form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded($user_sid, 'i9');

            // single flag will not resolve the issue
            // echo 'Incomplete ID<pre>'; print_r($assigned_sids); echo '<hr>';
            // echo 'Completed ID<pre>'; print_r($completed_document_sids); echo '<hr>';
            // echo 'Signed ID<pre>'; print_r($signed_document_sids); echo '<hr>';
            // echo 'No Action Required<pre>'; print_r($no_action_required_sids);
            // echo 'Revoked Documents<pre>'; print_r($revoked_sids);   exit;
            // echo 'Assigned Documents<pre>'; print_r($assigned_documents);
            // exit;

            $assigned_documents_history = $this->hr_documents_management_model->get_assigned_documents_history(0, $user_type, $user_sid,$pp_flag);

            foreach ($active_documents as $key => $doc) {
                if ($doc['document_type'] == 'generated') {
                    $document = $this->hr_documents_management_model->get_assigned_document_record($user_type, $user_sid, $doc['sid']);
                    $active_documents[$key]['document_title'] = $doc['document_title'];
                    $active_documents[$key]['document_description'] = sizeof($document) > 0 && $document['document_description'] != NULL && !empty($document['document_description']) ? $document['document_description'] : $doc['document_description'];
                }
            }
            //print_r($no_action_document_categories);
            $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, $signed_documents, $no_action_required_documents, $data['session']['employer_detail']['access_level_plus']);
            $data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
            $data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];
            $data['no_action_document_categories'] =  $categorized_docs['no_action_document_categories'];

            $archived_manual_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, $user_type, $user_sid, 1,1,1,$pp_flag);
            $archived_categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, null, $archived_manual_documents, $data['session']['employer_detail']['access_level_plus']);

            $data['archived_manual_documents'] = $archived_manual_documents;
            $data['archived_no_action_document_categories'] =  $archived_categorized_docs['no_action_document_categories'];

            $data['title'] = 'Document(s) Management';
            $data['active_documents'] = $active_documents;
            $data['assigned_documents'] = $assigned_documents; // not completed Documemts
            $data['completed_sids'] = $completed_sids; // completed Documemts Ids
            $data['signed_document_sids'] = $signed_document_sids; // signed Documemts Ids
            $data['signed_documents'] = $signed_documents; // signed Documemts
            $data['completed_document_sids'] = $completed_document_sids; // completed Documemts Ids
            $data['completed_documents'] = $completed_documents; // completed Documemts
            $data['no_action_required_documents'] = $no_action_required_documents; // no action required documents
            $data['assigned_sids'] = $assigned_sids;
            $data['revoked_sids'] = $revoked_sids;
            $data['assigned_documents_history'] = $assigned_documents_history;
            $data['assigned_offer_letter_history'] = $assigned_offer_letter_history;
            $data['user_info'] = $user_info;
            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['job_list_sid'] = $jobs_listing;

            $data['all_documents'] =$this->hr_documents_management_model->get_total_documents($company_sid);


            $data['company_name'] = $company_name;
            $data['employer_email'] = $employer_email;
            $data['employer_first_name'] = $employer_first_name;
            $data['employer_last_name'] = $employer_last_name;

            if (!empty($manual_assigned_offer_letters) && !empty($assigned_offer_letters)) {
                $marge_offer_letters = array_merge($manual_assigned_offer_letters,$assigned_offer_letters);

                $sort_offer_letters = array();
                foreach ($marge_offer_letters as $key => $value) {
                    $index_key = $value['sid'];
                    $sort_offer_letters[$index_key] = $value;
                }

                ksort($sort_offer_letters);
                $data['assigned_offer_letters'] = $sort_offer_letters;  // assign offer letters
            } else if (!empty($assigned_offer_letters)) {
                $data['assigned_offer_letters'] = $assigned_offer_letters;  // assign offer letters
            } else if (!empty($manual_assigned_offer_letters)) {
               $data['assigned_offer_letters'] = $manual_assigned_offer_letters; // assign offer letters
            }

            //
            $data['uncompleted_payrolls'] = array();
            $data['completed_payrolls'] = array();
            $data['current_payroll'] = array();
            if(isset($data['assigned_offer_letters']))
                $data['current_payroll'] = array_values($data['assigned_offer_letters'])[0]['sid'];
            if($pp_flag){
                if(sizeof($data['assigned_offer_letters'])){
                    foreach($data['assigned_offer_letters'] as $k => $v){
                        if($v['user_consent'] == 1 || $v['manual_document_type'] == 'offer_letter'){
                            if(!isset($data['categories_documents_completed'][PP_CATEGORY_SID])){
                                $data['categories_documents_completed'][PP_CATEGORY_SID]['category_sid'] = PP_CATEGORY_SID;
                                $data['categories_documents_completed'][PP_CATEGORY_SID]['name'] = 'Payroll Documents';
                            }
                            $data['categories_documents_completed'][PP_CATEGORY_SID]['documents'][] = $v;
                        } else{
                            if(!isset($data['uncompleted_payrolls'][PP_CATEGORY_SID])){
                                $data['uncompleted_payrolls'][PP_CATEGORY_SID]['category_sid'] = PP_CATEGORY_SID;
                                $data['uncompleted_payrolls'][PP_CATEGORY_SID]['name'] = 'Payroll Documents';
                            }
                            $data['uncompleted_payrolls'][PP_CATEGORY_SID]['documents'][] = $v;
                        }
                    }
                }
            }

            // _e($data['completed_payrolls'], true);
            // _e($data['uncompleted_payrolls'], true);
            $data['starttime'] = $starttime;
            $data['pp_flag'] = $pp_flag;
            $this->load->view('main/header', $data);
            $this->load->view('hr_documents_management_test/documents_assignment');
            $this->load->view('main/footer');
           
            
        } else {
            redirect('login', 'refresh');
        }
    }

    public function my_documents() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assignment Documents';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $assigned_sids = array();
            $no_action_required_sids = array();
            $completed_sids = array();
            $revoked_sids = array();
            $completed_documents = array();
            $no_action_required_documents = array();
            $groups_assign = $this->hr_documents_management_model->get_all_documents_group_assigned($company_sid, 'employee', $employer_sid);

            if (!empty($groups_assign)) {
                foreach ($groups_assign as $value) {
                    $system_document = $this->hr_documents_management_model->get_document_group($value['group_sid']);

                    if ($system_document['w4'] == 1) {
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
                        }
                    }

                    if ($system_document['w9'] == 1) {
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
                        }
                    }

                    if ($system_document['i9'] == 1) {
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

                            $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                        }
                    }
                }
            }



            $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employer_sid, 0);
            $assigned_offer_letters = $this->hr_documents_management_model->get_assigned_offers($company_sid, 'employee', $employer_sid, 0);
            $manual_assigned_offer_letters = $this->hr_documents_management_model->get_manual_assigned_offers($company_sid, 'employee', $employer_sid, 0, 0);

            foreach ($assigned_documents as $key => $assigned_document) {
                $is_document_completed = 0;
                $is_magic_tag_exist = 0;

                if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                    $document_body = $assigned_document['document_description'];
                    $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                if ($assigned_document['document_type'] != 'offer_letter' && $assigned_document['document_type'] != 'confidential') {
                    if ($assigned_document['status'] == 1) {
                        if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                            if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                    $is_document_completed = 1;
                                } else if ($assigned_document['uploaded'] == 1) {
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
                                $completed_sids[] = $assigned_document['document_sid'];
                                $completed_documents[] = $assigned_document;
                                unset($assigned_documents[$key]);
                            } else {
                                $assigned_sids[] = $assigned_document['document_sid'];
                            }
                        } else { // nothing is required so it is "No Action Required Document"
                            $no_action_required_sids[] = $assigned_document['document_sid'];
                            $no_action_required_documents[] = $assigned_document;
                            unset($assigned_documents[$key]);
                        }
                    } else {
                        $revoked_sids[] = $assigned_document['document_sid'];
                    }
                }
            }
            $categorized_docs = $this->hr_documents_management_model->categrize_documents($company_sid, $completed_documents, $no_action_required_documents,$data['session']['employer_detail']['access_level_plus']);
            $data['categories_no_action_documents'] = $categorized_docs['categories_no_action_documents'];
            $data['categories_documents_completed'] =  $categorized_docs['categories_documents_completed'];

            $documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employer_sid);
            $data['documents'] = $documents;
            // $offer_letter = $this->hr_documents_management_model->get_assigned_offer_letter($company_sid, 'employee', $employer_sid);
            // $data['offer_letter'] = $offer_letter;

            $data['load_view'] = check_blue_panel_status(false, 'self');
            $data['employee'] = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            $eev_w4 = $this->hr_documents_management_model->is_exist_in_eev_document('w4', $company_sid, $employer_sid);
            if (!empty($eev_w4)) {
                $data['w9_form'] = $data['eev_w4'] = $eev_w4;
            } else {
               $w4_form = $this->hr_documents_management_model->fetch_form_for_front_end('w4', 'employee', $employer_sid);
               $data['w4_form'] = $w4_form;
            }

            $eev_w9 = $this->hr_documents_management_model->is_exist_in_eev_document('w9', $company_sid, $employer_sid);
            if (!empty($eev_w9)) {
                $data['w9_form'] = $data['eev_w9'] = $eev_w9;
            } else {
               $w9_form = $this->hr_documents_management_model->fetch_form_for_front_end('w9', 'employee', $employer_sid);
               $data['w9_form'] = $w9_form;
            }

            $eev_i9 = $this->hr_documents_management_model->is_exist_in_eev_document('i9', $company_sid, $employer_sid);
            if (!empty($eev_i9)) {
                $data['i9_form'] = $data['eev_i9'] = $eev_i9;
            } else {
                $i9_form = $this->hr_documents_management_model->fetch_form_for_front_end('i9', 'employee', $employer_sid);
                $data['i9_form'] = $i9_form;
            }

            if (!empty($manual_assigned_offer_letters) && !empty($assigned_offer_letters)) {
                $marge_offer_letters = array_merge($manual_assigned_offer_letters,$assigned_offer_letters);

                $sort_offer_letters = array();
                foreach ($marge_offer_letters as $key => $value) {
                    $index_key = $value['sid'];
                    $sort_offer_letters[$index_key] = $value;
                }

                ksort($sort_offer_letters);
                $data['assigned_offer_letters'] = $sort_offer_letters;  // assign offer letters
            } else if (!empty($assigned_offer_letters)) {
                $data['assigned_offer_letters'] = $assigned_offer_letters;  // assign offer letters
            } else if (!empty($manual_assigned_offer_letters)) {
               $data['assigned_offer_letters'] = $manual_assigned_offer_letters; // assign offer letters
            }

            //
            $data['uncompleted_payrolls'] = array();
            $data['completed_payrolls'] = array();
            $data['current_payroll'] = array_values($data['assigned_offer_letters'])[0]['sid'];
            if(sizeof($data['assigned_offer_letters'])){
                foreach($data['assigned_offer_letters'] as $k => $v){
                    if($v['user_consent'] == 1 || $v['manual_document_type'] == 'offer_letter'){
                        if(!isset($data['categories_documents_completed'][PP_CATEGORY_SID])){
                            $data['categories_documents_completed'][PP_CATEGORY_SID]['category_sid'] = PP_CATEGORY_SID;
                            $data['categories_documents_completed'][PP_CATEGORY_SID]['name'] = 'Payroll Documents';
                        }
                        $data['categories_documents_completed'][PP_CATEGORY_SID]['documents'][] = $v;
                    } else{
                        if(!isset($data['uncompleted_payrolls'][PP_CATEGORY_SID])){
                            $data['uncompleted_payrolls'][PP_CATEGORY_SID]['category_sid'] = PP_CATEGORY_SID;
                            $data['uncompleted_payrolls'][PP_CATEGORY_SID]['name'] = 'Payroll Documents';
                        }
                        $data['uncompleted_payrolls'][PP_CATEGORY_SID]['documents'][] = $v;
                    }
                }
            }

            $data['assigned_documents'] = $assigned_documents;
            $data['completed_sids'] = $completed_sids; // completed Documemts Ids
            $data['completed_documents'] = $completed_documents;
            $data['no_action_required_documents'] = $no_action_required_documents;
            $data['assigned_sids'] = $assigned_sids;
            $data['revoked_sids'] = $revoked_sids;
            $data['user_type'] = 'employee';
            $data['user_sid'] = $employer_sid;
            if ($this->form_validation->run() == false) {
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

    public function sign_hr_document($doc = NULL, $document_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Assigned Documents';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['doc'] = $doc;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, $doc);

                if ($document['document_type'] == 'offer_letter') {
                    $data['attached_video'] = array();
                } else {
                    $attached_video = $this->hr_documents_management_model->get_document_attached_video($document['document_sid']);
                    $data['attached_video'] = $attached_video;
                }

                $save_offer_letter_type = '';

                if (!empty($document['document_description'])) {
                    $document_body = $document['document_description'];
                    $magic_codes = array('{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}');
                    $magic_signature_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_signature_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'consent_only';
                    } else if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $save_offer_letter_type = 'save_only';
                    }
                }

                $data['save_offer_letter_type'] = $save_offer_letter_type;

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_sid, $employer_sid, 'employee', $document['document_description'], $document['document_sid']);
                    $document['document_description'] = $document_content;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                    redirect('hr_documents_management_test/my_documents', 'refresh');
                }

                $data['document'] = $document;
                $document_type = $document['document_type'];
                $data['document_type'] = $document_type;
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
                $data['back_url'] = base_url('hr_documents_management_test/my_documents');
                $data['download_url'] = base_url('hr_documents_management_test/download_hr_document/' . $document['sid']);
                $data['unique_sid'] = ''; //No Need for Unique Sid for Employee

                if ($document['acknowledged'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Document Status:</strong> You have successsfully Acknowledged this document';
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
                    $download_button_action = base_url('hr_documents_management_test/download_hr_document/' . $document['sid']);

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successsfully downloaded this document';
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
                    $download_button_action = 'javascript:;';

                    if ($document['downloaded'] == 1) {
                        $download_status = '<strong class="text-success">Document Status:</strong> You have successsfully printed this document';
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
                            $print_button_action = base_url('hr_documents_management_test/print_upload_img/' . $document['uploaded_file']);
                        }
                    } else if (empty($document['submitted_description'])) {
                        // $print_button_action = base_url('hr_documents_management_test/print_generated_doc/original/' .$document['sid'].'/'.$employer_sid.'/employee');
                        if ($document['document_type'] == 'generated') {
                            $print_button_action = base_url('hr_documents_management_test/print_generated_and_offer_later/assigned/generated/' . $document['sid']);
                        } else if ($document['document_type'] == 'offer_letter') {
                            $print_button_action = base_url('hr_documents_management_test/print_generated_and_offer_later/assigned/offer_letter/' . $document['sid']);
                        }
                    } else {
                        // $print_button_action = base_url('hr_documents_management_test/print_generated_doc/submitted/' .$document['sid'].'/'.$employer_sid.'/employee');
                        if ($document['document_type'] == 'generated') {
                            $print_button_action = base_url('hr_documents_management_test/print_generated_and_offer_later/submitted/generated/' . $document['sid']);
                        } else if ($document['document_type'] == 'offer_letter') {
                            $print_button_action = base_url('hr_documents_management_test/print_generated_and_offer_later/submitted/offer_letter/' . $document['sid']);
                        }
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
                $this->load->view('hr_documents_management_test/sign_hr_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'acknowledge_document':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        // $document_sid = $this->input->post('document_sid');
                        $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_sid);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                        redirect('hr_documents_management_test/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
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
                            $this->hr_documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                            $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                        }

                        redirect('hr_documents_management_test/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
                        break;
                    case 'sign_document':
                        $user_type = 'employee';
                        $user_sid = $employer_sid;
                        $save_signature = $this->input->post('save_signature');
                        $save_initial = $this->input->post('save_signature_initial');
                        $save_date = $this->input->post('save_signature_date');
                        $user_consent = $this->input->post('user_consent');
                        $base64_pdf = $this->input->post('save_PDF');
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
                        $data_to_update['signature_ip'] = $_SERVER['REMOTE_ADDR'];
                        $data_to_update['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                        $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                        $data_to_update['submitted_description'] = $base64_pdf;
                        $data_to_update['uploaded'] = 1;
                        $data_to_update['uploaded_date'] = date('Y-m-d H:i:s');
                        $this->hr_documents_management_model->update_generated_documents($document_sid, $user_sid, $user_type, $data_to_update);
                        $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');

                        if ($user_type == 'employee') {
                            redirect('hr_documents_management_test/sign_hr_document/' . $doc . '/' . $document_sid, 'refresh');
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

    public function download_hr_document($document_sid) {
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

    public function copy_old_hr_documents_to_new_documents() {
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

    public function people_with_pending_documents() {
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
            $data['employer_sid'] = $employer_sid;
            $data['user_type'] = 'employee';
            $emp_ids = array();

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {

                $result = $this->hr_documents_management_model->getEmployeesWithPendingDoc($company_sid);
                foreach ($result as $id) {
                    $emp_ids[] = $id['user_sid'];
                }

                if (!empty($emp_ids)) {
                    $data['employees'] = $this->hr_documents_management_model->getEmployeesDetails($emp_ids);
                } else {
                    $data['employees'] = array();
                }

                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/new_people_with_pending_documents');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function employee_document($employee_id = NULL) {
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
                $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $employee_id, 0);

                foreach ($assigned_documents as $key => $assigned_document) {
                    $is_magic_tag_exist = 0;
                    $is_document_completed = 0;

                    if (!empty($assigned_document['document_description']) && $assigned_document['document_type'] == 'generated') {
                        $document_body = $assigned_document['document_description'];
                        $magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}');

                        if (str_replace($magic_codes, '', $document_body) != $document_body) {
                            $is_magic_tag_exist = 1;
                        }
                    }

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
                    }
                }

                $w4_form = $this->hr_documents_management_model->is_w4_form_assign('employee', $employee_id);
                $w9_form = $this->hr_documents_management_model->is_w9_form_assign('employee', $employee_id);
                $i9_form = $this->hr_documents_management_model->is_i9_form_assign('employee', $employee_id);

                $data['w4_form'] = $w4_form;
                $data['w9_form'] = $w9_form;
                $data['i9_form'] = $i9_form;
                $data['documents'] = $assigned_documents;
                $data['userDetail'] = $this->hr_documents_management_model->getEmployerDetail($employee_id);
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/pending-hr-document');
                $this->load->view('main/footer');
            } else {
                redirect(base_url('login'), "refresh");
            }//else end for session check fail
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please select an Employee to review documents');
            redirect(base_url('hr_documents'));
        }//else end for session check fail
    }

    public function send_document_reminder() {
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
            //$emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION);
            $emailTemplateData = $this->hr_documents_management_model->getEmailTemplate(HR_DOCUMENTS_NOTIFICATION_EMS, $company_sid);
            if(!sizeof($emailTemplateData)){
                echo 'Email template not found!';
                exit(0);
            }
            // $emailTemplateData = $this->hr_documents_management_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION_EMS);
            $emailTemplateBody = $emailTemplateData['body'];

            // $emailTemplateBody = preg_replace('<a href="{{baseurl}}received_documents/{{verification_key}}">', 'a href="{{baseurl}}hr_documents_management_test/my_documents" style="background-color: #0000ff; color: #ffffff; text-decoration: none; padding:5px 10px; border-radius: 5px;"', $emailTemplateBody);
            // $emailTemplateBody = preg_replace('<a href="{{baseurl}}received_documents/{{verification_key}}" >', 'a href="{{baseurl}}hr_documents_management_test/my_documents" style="background-color: #0000ff; color: #ffffff; text-decoration: none; padding:5px 10px; border-radius: 5px;"', $emailTemplateBody);
            // $emailTemplateBody = preg_replace('<a href="{{baseurl}}login">', 'a href="{{baseurl}}login" style="background-color: #0000ff; color: #ffffff; text-decoration: none; padding:5px 10px; border-radius: 5px;"', $emailTemplateBody);

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

            $emailTemplateBody = $message_hf['header'].$emailTemplateBody.$message_hf['footer'];


            $from = $emailTemplateData['from_email'];
            $to = $userData['email'];
            $subject = $emailTemplateData['subject'];
            $from_name = ucwords(str_replace('{{company_name}}', $company_name, $emailTemplateData['from_name']));

            /*

              $from                                                               = FROM_EMAIL_DEV;
              $to                                                                 = $userData['email'];
              $subject                                                            = "HR Documents Reminder From " . ucfirst($companyname);
              $body           = $message_hf['header']
              . '<h2 style="width:100%; margin:10px 0;">Dear ' . $userData['first_name'] . ' ' . $userData['last_name'] . ',</h2>'
              . 'Your Company HR Administrator has sent you some important HR Documents. Please see the attachments.'
              . '<br>To manage all of your documents Please go to <a href="' . base_url('received_documents') . '/' . $userData['ver_key'] . '">This Link</a>.'
              . '<br>Or <a href="' . base_url('login') . '">Login</a> to your '.STORE_NAME.' account.'
              . '<br>Go to your <b>'.STORE_NAME.' dashboard</b>-><b>My HR Documents</b>.<br><br>'
              . $message_hf['footer'];

             */

            if (base_url() == 'http://localhost/ahr') {
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $emailTemplateBody,
                );
                save_email_log_common($emailData);
            } else {
                $emailData = array(
                    'date' => date('Y-m-d H:i:s'),
                    'subject' => $subject,
                    'email' => $to,
                    'message' => $emailTemplateBody,
                );
                save_email_log_common($emailData);
                sendMail($from, $to, $subject, $emailTemplateBody, $from_name);
            }

            //saving email logs
            // $emailLog['subject'] = $subject;
            // $emailLog['email'] = $to;
            // $emailLog['message'] = $emailTemplateBody;
            // $emailLog['date'] = date('Y-m-d H:i:s');
            // $emailLog['admin'] = 'admin';
            // $emailLog['status'] = 'Delivered';
            // save_email_log_common($emailLog);
            // $this->session->set_flashdata('message', '<b>Success:</b> Document reminder sent successfully!');
            echo 'success';
        } else {
            echo 'error';
            // $this->session->set_flashdata('message', '<b>Error:</b> Please try again!');
        }
    }

    public function downloaded_generated_doc($user_sid, $company_sid, $document_sid, $user_type) {
        $this->hr_documents_management_model->downloaded_generated_doc_on($company_sid, $user_sid, $document_sid, $user_type);
    }

    public function manage_document($user_type, $document_sid, $user_sid, $job_list_sid = NULL) {
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
                    redirect('hr_documents_management_test/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                }
            } else { // document not found!
                $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                redirect('hr_documents_management_test/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
            }

            switch ($user_type) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error</strong> Document Not found!');
                        redirect('hr_documents_management_test/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
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

                    $data_employer = array('sid' => $applicant_info['sid'],
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
                        'user_type' => ucwords($user_type));

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
                $data['back_url'] = base_url('hr_documents_management_test/documents_assignment/' . $user_type . '/' . $user_sid . '/' . $job_list_sid);
                $data['unique_sid'] = '';

                if ($document['acknowledged'] == 1) {
                    $acknowledgement_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successsfully Acknowledged this document';
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
                        $download_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successsfully downloaded this document';
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
                        $download_status = '<strong class="text-success">Document Status:</strong> ' . ucwords($user_type) . ' has successsfully printed this document';
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

                $data['original_download_url'] = base_url('hr_documents_management_test/download_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original');

                $data['original_print_url'] = base_url('hr_documents_management_test/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/original');

                $data['submitted_download_url'] = base_url('hr_documents_management_test/download_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/submitted');
                $data['submitted_print_url'] = base_url('hr_documents_management_test/print_assign_document/' . $user_type . '/' . $user_sid . '/' . $document_sid . '/submitted');
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


                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/manage_hr_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'acknowledge_document':
                        $this->hr_documents_management_model->update_acknowledge_status($user_type, $user_sid, $document['sid']);

                        $action_track = array('company_sid' => $company_sid,
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
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']);

                        $this->hr_documents_management_model->manager_document_activity_track($action_track);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                        redirect('hr_documents_management_test/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
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
                                'ip' => $_SERVER['REMOTE_ADDR'],
                                'user_agent' => $_SERVER['HTTP_USER_AGENT']);

                            $this->hr_documents_management_model->manager_document_activity_track($action_track);
                            $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                        }

                        redirect('hr_documents_management_test/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                        break;
                    case 'acknowledge_document_download':
                        $this->hr_documents_management_model->update_download_status($user_type, $user_sid, $document['sid']);
                        $action_track = array('company_sid' => $company_sid,
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
                            'ip' => $_SERVER['REMOTE_ADDR'],
                            'user_agent' => $_SERVER['HTTP_USER_AGENT']);

                        $this->hr_documents_management_model->manager_document_activity_track($action_track);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Download Acknowledged!');
                        redirect('hr_documents_management_test/manage_document/' . $user_type . '/' . $document_sid . '/' . $user_sid . '/' . $job_list_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function download_assign_document($user_type, $user_sid, $document_sid, $print_type) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                if ($document['document_type'] == 'generated') {
                    $data['document'] = $document;
                    $data['load_view'] = $load_view;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['security_details'] = $security_details;
                    $data['title'] = 'Learning Center - Supported Document';
                    $data['employee'] = $data['session']['employer_detail'];
                    $data['print'] = $print_type;
                    if ($print_type == 'original') {
                        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                        $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                        $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

                        //E_signature process
                        $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: 145px;" src=""  id="draw_upload_img" />';
                        $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: 145px;" src=""  id="target_signature_init" />';
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
                    // $this->load->view('main/header', $data);
                    $this->load->view('hr_documents_management_test/download_generated_document', $data);
                    // $this->load->view('main/footer');
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

    public function print_assign_document($user_type, $user_sid, $document_sid, $print_type) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $load_view = check_blue_panel_status(false, 'self');

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), 'refresh');
            }

            if ($this->form_validation->run() == false) {
                $document = $this->hr_documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                if ($document['document_type'] == 'generated') {
                    $data['document'] = $document;
                    $data['load_view'] = $load_view;
                    $data['company_sid'] = $company_sid;
                    $data['employer_sid'] = $employer_sid;
                    $data['security_details'] = $security_details;
                    $data['title'] = 'Learning Center - Supported Document';
                    $data['employee'] = $data['session']['employer_detail'];

                    if ($print_type == 'original') {
                        $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                        $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                        $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                        $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

                        //E_signature process
                        $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: 145px;" src=""  id="draw_upload_img" />';
                        $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: 145px;" src=""  id="target_signature_init" />';
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
                    $this->load->view('hr_documents_management_test/print_generated_document', $data);
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

                    $data['print'] = 'original';
                    $data['download'] = NULL;
                    $data['file_name'] = NULL;
                    $this->load->view('hr_documents_management_test/print_generated_document', $data);
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function vimeo_get_id($str) {
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

    public function print_upload_img($image_url) {
        $document_file = AWS_S3_BUCKET_URL . $image_url;
        $data['print'] = '';
        $data['download'] = NULL;
        $data['file_name'] = NULL;
        $data['original_document_description'] = '<img src="' . $document_file . '" style="width:100%; height:500px;" />';
        $this->load->view('hr_documents_management_test/print_generated_document', $data);
    }

    public function preview_generated_doc() {
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
                echo $this->load->view('hr_documents_management_test/generated_document_preview_partial', $view_data, true);
            }
        }
    }

    public function print_generated_doc($type, $sid, $user_sid, $user_type, $download = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $document = $this->hr_documents_management_model->get_submitted_generated_document($sid);

            if ($type == 'original') {
                $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_description'], $document['document_sid']);

                $value = '<div class="div-editable fillable_input_field input-grey" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
                $document_content = str_replace('[Target User Input Field]', $value, $document_content);

                $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
                $document_content = str_replace('[Target User Checkbox]', $value, $document_content);

                //E_signature process
                $signature_bas64_image = '<a class="btn blue-button btn-sm get_signature" href="javascript:;">Create E-Signature</a><img style="max-height: 145px;" src=""  id="draw_upload_img" />';
                $init_signature_bas64_image = '<a class="btn blue-button btn-sm get_signature_initial" href="javascript:;">Signature Initial</a><img style="max-height: 145px;" src=""  id="target_signature_init" />';
                $signature_timestamp = '<a class="btn blue-button btn-sm get_signature_date" href="javascript:;">Sign Date</a><p id="target_signature_timestamp"></p>';

                $value = ' ';
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

            $this->load->view('hr_documents_management_test/print_generated_document', $data);
        }
    }

    public function print_generated_and_offer_later($type, $document_type, $document_sid, $download = NULL) {
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
                        $authorized_signature = '<img style="max-height: 145px;" src="' . $authorized_base64 . '">';
                    } else {
                        $authorized_signature = '';
                    }

                    $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);

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

                $this->load->view('hr_documents_management_test/print_generated_document', $data);
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
                $this->load->view('hr_documents_management_test/print_generated_document', $data);
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
                        $authorized_signature = '<img style="max-height: 145px;" src="' . $authorized_base64 . '">';
                    } else {
                        $authorized_signature = '';
                    }

                    $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);

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

                $this->load->view('hr_documents_management_test/print_generated_document', $data);
            }
        }
    }

    public function download_upload_document($document_path) {
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

    public function get_document_employees() {
        $doc_sid = $this->input->post('doc_sid');
        $doc_type = $this->input->post('doc_type');

        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employees = $this->hr_documents_management_model->fetch_documents_employees($doc_sid, $doc_type, $company_sid);
        echo json_encode($employees);
    }

    public function get_print_url() {
        if ($this->session->userdata('logged_in')) {
            $request_type = $this->input->post('request_type');
            $document_type = $this->input->post('document_type');
            $document_sid = $this->input->post('document_sid');
            $url = get_print_document_url($request_type, $document_type, $document_sid);
            echo json_encode($url);
        }
    }

    public function authorized_e_signature() {
        $form_post = $this->input->post();
        $company_sid = $form_post['company_sid'];
        $first_name = $form_post['first_name'];
        $last_name = $form_post['last_name'];
        $email_address = $form_post['email_address'];
        $user_sid = $form_post['user_sid'];
        $active_signature = $form_post['active_signature'];
        $signature_timestamp = date('Y-m-d H:i:s');
        $drawn_signature = $form_post['drawn_signature'];
        $drawn_init_signature = $form_post['drawn_init_signature'];
        $ip_address = $form_post['ip_address'];
        $user_agent = $form_post['user_agent'];
        $user_consent = $form_post['user_consent'];

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['first_name'] = $first_name;
        $data_to_insert['last_name'] = $last_name;
        $data_to_insert['email_address'] = $email_address;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['document_sid'] = '0';
        $data_to_insert['active_signature'] = $active_signature;
        $data_to_insert['signature_timestamp'] = $signature_timestamp;
        $data_to_insert['signature_base64'] = $drawn_signature;
        $data_to_insert['initial_base64'] = $drawn_init_signature;
        $data_to_insert['ip_address'] = $ip_address;
        $data_to_insert['user_agent'] = $user_agent;
        $data_to_insert['user_consent'] = $user_consent == 1 ? 1 : 0;
        $data_to_insert['status'] = 1;
        $authorized_signature_sid = $this->hr_documents_management_model->save_document_authorized_signature($data_to_insert);
        echo $authorized_signature_sid;
    }

    public function check_active_auth_signature($document_sid, $company_sid) {
        $signature = $this->hr_documents_management_model->is_authorized_signature_exist($document_sid, $company_sid);
        $return_data = array();

        if (!empty($signature)) {
            echo 1;
        } else {
            echo 0;
        }
    }

    public function deactivate_auth_signature($document_sid) {
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $this->hr_documents_management_model->remove_authorized_signature_if_exist($document_sid, $data_to_update);
    }

    public function switch_admin_hr_to_new_documents() {
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

    public function documents_group_management() {
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
                $this->load->view('hr_documents_management_test/documents_group_management');
                $this->load->view('main/footer');
            } else {
                $employees = $this->input->post('employees');
                $group_assign_sid = $this->input->post('group_sid');

                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['group_sid'] = $group_assign_sid;
                $data_to_insert['assigned_by_sid'] = $employer_sid;
                $data_to_insert['applicant_sid'] = 0;

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
                redirect('hr_documents_management_test/documents_group_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add_edit_document_group_management($group_sid = NULL) {
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
                $this->load->view('hr_documents_management_test/add_edit_document_group');
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
                        redirect('hr_documents_management_test/documents_group_management', 'refresh');
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
                        redirect('hr_documents_management_test/documents_group_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function document_2_group($group_sid = NULL) {
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

            $data['group'] = $group;
            $data['group_name'] = $group['name'];
            $data['assigned_documents'] = $assigned_documents;
            $data['documents'] = $documents;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/document_2_group');
                $this->load->view('main/footer');
            } else {
                $assign_documents = $this->input->post('documents');
                $assign_system_documents = $this->input->post('system_documents');
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
                        }

                        $this->hr_documents_management_model->assign_system_document_2_group($group_sid, $data_to_update);
                    }
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
                redirect('hr_documents_management_test/documents_group_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function get_all_company_employees() {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data['session']['company_detail']['sid'];
        $employees = $this->hr_documents_management_model->fetch_all_company_employees($company_sid);
        echo json_encode($employees);
    }

    public function ajax_assign_group_2_applicant($group_sid, $user_type, $user_sid) {
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

    public function print_eeoc_form($action, $sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data['session']['company_detail']['sid'];
            $security_sid = $data['session']['employer_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = 'EEOC Form';
            $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($sid);
            $data['eeo_form_info'] = $eeo_form_info;
            $data['action'] = $action;
            $this->load->view('eeo/eeoc_print', $data);
        } else {
            redirect('login', "refresh");
        }
    }

    public function required_documents($user_type = NULL, $user_sid = NULL, $eev_documents_sid = NULL, $form_type = 'uploaded') {
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
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid);
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
                    $eeo_form_info = $this->hr_documents_management_model->get_eeo_form_info($user_sid);
                    $data['eeo_form_status'] = $eeo_form_status;
                    $data['eeo_form_info'] = $eeo_form_info;

                    $data_employer = array('sid' => $applicant_info['sid'],
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
                        'user_type' => ucwords($user_type));

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
                $data['required_documents'] = $this->hr_documents_management_model->get_eev_required_document($user_sid,$eev_documents_sid,$form_type);
                if($form_type == "uploaded"){
                    $data['form_uploaded'] = $this->hr_documents_management_model->get_form_uploaded_by_id($eev_documents_sid);
                    $data['i9_form'] = null;
                    $data['w9_form'] = null;
                    $data['w4_form'] = null;

                }else{
                    $data['i9_form'] = $this->hr_documents_management_model->fetch_form('i9', $user_type, $user_sid);
                    $data['w9_form'] = $this->hr_documents_management_model->fetch_form('w9', $user_type, $user_sid);
                    $data['w4_form'] = $this->hr_documents_management_model->fetch_form('w4', $user_type, $user_sid);
                }

                $data['form_type'] = $form_type;
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/required_documents_management');
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

                redirect('hr_documents_management_test/required_documents' . '/' . $user_type . '/' . $user_sid . '/' .$eev_documents_sid. '/' . $form_type, 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function view_eev_document ($documents_sid = NULL) {
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
            $file_name = explode(".",$upload_document);
            $document_name = $file_name[0];
            $document_extension = $file_name[1];

            $print_url = '';

            if ($document_extension == 'pdf') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$document_name.'.pdf';
            } else if ($document_extension == 'doc') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edoc&wdAccPdf=0';
            } else if ($document_extension == 'docx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Edocx&wdAccPdf=0';
            } else if ($document_extension =='xls') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Exls';
            } else if ($document_extension == 'xlsx') {
                $print_url = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F'.$document_name.'%2Exlsx';
            } else if ($document_extension == 'csv') {
                $print_url = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/'.$document_name.'.csv';
            } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                $print_url = base_url('hr_documents_management_test/print_generated_and_offer_later/original/generated/'.$document_sid);
            }

            $data['print_url'] = $print_url;
            $data['download_url'] = base_url('hr_documents_management_test/download_upload_document/'.$eev_document['s3_filename']);
            $data['document_extension'] = $document_extension;
            $data['document_name'] = $eev_document['s3_filename'];

            $this->load->view('onboarding/on_boarding_header', $data);
            $this->load->view('hr_documents_management_test/eev_document');
            $this->load->view('onboarding/on_boarding_footer');
        } else {
            redirect('login', "refresh");
        }
    }

    public function documents_category_management() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $categories = $this->hr_documents_management_model->get_all_documents_category($company_sid,null,'descending');
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
                $this->load->view('hr_documents_management_test/documents_category_management');
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
                redirect('hr_documents_management_test/documents_category_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function add_edit_document_category_management($category_sid = NULL) {
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
                if($category_sid == PP_CATEGORY_SID){
                    $this->session->set_flashdata('message', "<strong>Error:</strong> Access Denied!");
                    redirect('hr_documents_management_test/documents_category_management', 'refresh');
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
                'name', 'Category Name',
                'required|callback_checkCategoryName['.($category_sid).','.($company_sid).']',
                // 'required'.$is_unique,
                array(
                        'required'      => 'You have not provided %s.',
                        'checkCategoryName'     => 'This %s already exists.'
                )
            );

            if ($this->form_validation->run() == false) {
                if(validation_errors() != false){
                    $category['name'] = $this->input->post('name');
                    $category['description'] = $this->input->post('description');
                    $category['status'] = $this->input->post('status');
                    $category['sort_order'] = $this->input->post('sort_order');
                    $data['category'] = $category;
                }
                $this->load->view('main/header', $data);
                $this->load->view('hr_documents_management_test/add_edit_document_category');
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
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Category Created Successfully!');
                        redirect('hr_documents_management_test/documents_category_management', 'refresh');
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
                        $new_history_data = $category;
                        $new_history_data['category_sid'] = $category_sid;
                        $new_history_data['updated_by_sid'] = $category_status;
                        unset($new_history_data['sid']);
                        unset($new_history_data['created_by_sid']);
                        unset($new_history_data['created_date']);
                        $this->hr_documents_management_model->insert_category_history($new_history_data);
                        $this->hr_documents_management_model->update_document_category($category_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Category Updated Successfully!');
                        redirect('hr_documents_management_test/documents_category_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    function checkCategoryName($list, $l){
        $l = explode(',', $l);
        $categorySid = $l[0];
        $companySid = $l[1];
        return $this->hr_documents_management_model->checkCategoryName($categorySid, $companySid);
    }

    public function document_2_category($category_sid = NULL) {
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
                $this->load->view('hr_documents_management_test/document_2_category');
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
                redirect('hr_documents_management_test/documents_category_management', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function ajax_assign_category_2_applicant($category_sid, $user_type, $user_sid) {
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

    public function deactivate_document () {
        echo "dasdfasdf";exit;
        $document_sid = $_POST['document_sid'];
        $status_to_update = array();
        $status_to_update['status'] = 0;
        $status_to_update['archive'] = 1;
        $this->hr_documents_management_model->change_document_status($document_sid, $status_to_update);
    }


    //
    function convert_document_to_payplan(){
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
        if($post['documentType'] == 'uploaded' || $post['documentType'] == 'generated'){
            // Fetch uploaded document
            $document = $this->hr_documents_management_model->getUploadedDocumentById($post['documentId']);
        }
        //
        if(!sizeof($document)){
            $r['Response'] = 'Unable to verify document.';
            $this->res();
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
        if(!$insertId){
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
    private function res($i){
        header('Content-Type: application/json');
        echo json_encode($i);
        exit(0);
    }

    //
    private function convertDocumentToPayPlan(){
        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        $post = $this->input->post(NULL, TRUE);
        //
        $document = array();
        // Fetch uploaded document
        $document = $this->hr_documents_management_model->getUploadedDocumentById($post['document_sid']);
        //
        if(!sizeof($document)){
            $r['Response'] = 'Unable to verify document.';
            $this->res();
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
        if(!$insertId){
            $this->session->set_flashdata('message', 'Something went wrong while converting this document to Pay Plan.');
            redirect('hr_documents_management_test', 'refresh');
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
        $this->session->set_flashdata('message', 'Document has been converted to Pay Plan.');
        redirect('hr_documents_management_test', 'refresh');
    }

    private function redirectHandler($uri, $type = 'auto'){
        if(headers_sent()){
            echo '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                      <meta http-equiv = "refresh" content = "2; url = '.(base_url($uri)).'" />
            </head>
            </html>';
        }else{
            redirect($uri, $type);
        }
    }

}
