<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Documents_management extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('documents_management_model');
        $this->load->model('onboarding_model');
    }

    public function index() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Document Management';
// hassan working area - move documents to new module
//            $this->documents_management_model->copy_old_documents_to_new_documents_management($company_sid, $employer_sid);
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            
            if ($this->form_validation->run() == false) {
                // die('up');
                $uploaded_documents = $this->documents_management_model->get_all_documents($company_sid, 0);
                $data['documents'] = $uploaded_documents;
                $archived_documents = $this->documents_management_model->get_all_documents($company_sid, 1);
                $data['archived_documents'] = $archived_documents;
                $generated_documents = $this->documents_management_model->get_all_generated_documents($company_sid, 0);
                $data['generated_documents'] = $generated_documents;
                $archived_generated_documents = $this->documents_management_model->get_all_generated_documents($company_sid, 1);
                $data['archived_generated_documents'] = $archived_generated_documents;
                $offer_letters = $this->documents_management_model->get_all_offer_letters($company_sid, 0);
                $data['offer_letters'] = $offer_letters;
                $archived_offer_letters = $this->documents_management_model->get_all_offer_letters($company_sid, 1);
                $data['archived_offer_letters'] = $archived_offer_letters;
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/new_index');
//                $this->load->view('documents_management/index');
                $this->load->view('main/footer');
            } else {
                 // die('down');
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'delete_uploaded_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->delete_uploaded_document($document_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Uploaded Document Deleted!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'archive_uploaded_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->set_archive_status_document_record($document_sid, 1);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Uploaded Document Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'unarchive_uploaded_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->set_archive_status_document_record($document_sid, 0);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Uploaded Document Un-Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'archive_generated_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->set_archive_status_generated_document_record($document_sid, 1);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Generated Document Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'unarchive_generated_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->set_archive_status_generated_document_record($document_sid, 0);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Generated Document Un-Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'delete_generated_document':
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->delete_generated_document($document_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Generated Document Deleted!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'archive_offer_letter':
                        $offer_letter_sid = $this->input->post('offer_letter_sid');
                        $this->documents_management_model->set_offer_letter_archive_status($offer_letter_sid, 1);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'delete_offer_letter':
                        $offer_letter_sid = $this->input->post('offer_letter_sid');
                        $this->documents_management_model->delete_offer_letter($offer_letter_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Deleted!');
                        redirect('documents_management', 'refresh');
                        break;
                    case 'un_archive_offer_letter':
                        $offer_letter_sid = $this->input->post('offer_letter_sid');
                        $this->documents_management_model->set_offer_letter_archive_status($offer_letter_sid, 0);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Un-Archived!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function upload_new_document() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Upload Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/upload_new_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'upload_document':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $document_name = $this->input->post('document_name');
                        $document_description = $this->input->post('document_description');
                        $action_required = $this->input->post('action_required');
                        $s3_file_name = upload_file_to_aws('document_file', $company_sid, str_replace(' ', '_', $document_name), $employer_sid, AWS_S3_BUCKET_NAME);
                        $original_name = $document_name;
                        
                        if (isset($_FILES['document_file']["name"])) {
                            $original_name = $_FILES['document_file']["name"];
                        }

                        $file_info = pathinfo($original_name);
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['employer_sid'] = $employer_sid;
                        $data_to_insert['document_name'] = $document_name;
                        $data_to_insert['document_description'] = $document_description;
                        $data_to_insert['unique_key'] = generateRandomString(32);
                        $data_to_insert['date_uploaded'] = date('Y-m-d H:i:s');
                        $data_to_insert['action_required'] = $action_required;

                        if (isset($file_info['extension'])) {
                            $data_to_insert['document_type'] = $file_info['extension'];
                        }

                        if ($s3_file_name != 'error') {
                            $data_to_insert['document_original_name'] = $original_name;
                            $data_to_insert['s3_file_name'] = $s3_file_name;
                        }

                        $this->documents_management_model->insert_documents_uploads_record($data_to_insert);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Upload Successful!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function edit_document_info($document_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Edit Uploaded Document Details';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            
            if ($this->form_validation->run() == false) {
                $document_info = $this->documents_management_model->get_uploaded_document_record($company_sid, $document_sid);

                if (!empty($document_info)) {
                    $data['document_info'] = $document_info;
                    $this->load->view('main/header', $data);
                    $this->load->view('documents_management/upload_new_document');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Document Not Found!');
                    redirect('documents_management', 'refresh');
                }
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'update_document':
                        $document_sid = $this->input->post('document_sid');
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $document_name = $this->input->post('document_name');
                        $document_description = $this->input->post('document_description');
                        $action_required = $this->input->post('action_required');
                        $s3_file_name = upload_file_to_aws('document_file', $company_sid, str_replace(' ', '_', $document_name), $employer_sid);
                        $original_name = $document_name;
                        
                        if (isset($_FILES['document_file']["name"])) {
                            $original_name = $_FILES['document_file']["name"];
                        }

                        $file_info = pathinfo($original_name);
                        $data_to_update = array();
                        $data_to_update['document_name'] = $document_name;
                        $data_to_update['document_description'] = $document_description;
                        $data_to_update['date_uploaded'] = date('Y-m-d H:i:s');
                        $data_to_update['action_required'] = $action_required;


                        if (isset($file_info['extension'])) {
                            $data_to_update['document_type'] = $file_info['extension'];
                        }

                        if ($s3_file_name != 'error') {
                            $data_to_update['document_original_name'] = $original_name;
                            $data_to_update['s3_file_name'] = $s3_file_name;
                        }

                        $this->documents_management_model->update_documents_uploads_record($document_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Info Successfully Updated!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function generate_new_document() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Generate Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/generate_new_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                
                switch ($perform_action) {
                    case 'generate_new_document':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $target_user_type = $this->input->post('target_user_type');
                        $document_title = $this->input->post('document_title');
                        $document_content = $this->input->post('document_content');
                        $document_content = htmlentities($document_content);
                        $document_detail = array();
                        $document_detail['company_sid'] = $company_sid;
                        $document_detail['employer_sid'] = $employer_sid;
                        $document_detail['target_user_type'] = $target_user_type;
                        $document_detail['document_title'] = $document_title;
                        $document_detail['document_content'] = $document_content;
                        $document_detail['created_date'] = date('Y-m-d H:i:s');
                        $this->documents_management_model->insert_generated_document_record($document_detail);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Generated!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit_generated_document($document_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Edit Generated Document';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            if ($this->form_validation->run() == false) {
                $document_info = $this->documents_management_model->get_generated_document($company_sid, $document_sid);

                if (empty($document_info)) {
                    $this->session->set_flashdata('message', '<strong>Error:</strong> Document Not Found!');
                    redirect('documents_management', 'refresh');
                }

                $data['document_info'] = $document_info;
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/generate_new_document');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'generate_new_document':
                        $document_sid = $this->input->post('document_sid');
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $target_user_type = $this->input->post('target_user_type');
                        $document_title = $this->input->post('document_title');
                        $document_content = $this->input->post('document_content');
                        $document_content = htmlentities($document_content);
                        $document_detail = array();
                        $document_detail['company_sid'] = $company_sid;
                        $document_detail['target_user_type'] = $target_user_type;
                        $document_detail['document_title'] = $document_title;
                        $document_detail['document_content'] = $document_content;
                        $document_detail['modified_by'] = $employer_sid;
                        $document_detail['modified_date'] = date('Y-m-d H:i:s');
                        $this->documents_management_model->update_generated_document_record($document_sid, $document_detail);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Updated!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function documents_assignment($user_type = NULL, $user_sid = NULL, $jobs_listing = NULL) {
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

            if (!check_company_ems_status($company_sid)) {
                $this->session->set_flashdata('message', '<b>Warning:</b> Not Accessable');
                redirect(base_url('dashboard'), "refresh");
            }
            
            $user_info = array();
            
            switch ($user_type) {
                case 'employee':
                    $user_info = $this->documents_management_model->get_employee_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Employee Not Found!');
                        redirect('employee_management', 'refresh');
                    }
                    
                    $old_pending_documents = $this->documents_management_model->get_old_sent_documents($company_sid, $user_sid);
                    $data['old_pending_documents'] = $old_pending_documents;
                    break;
                case 'applicant':
                    $user_info = $this->documents_management_model->get_applicant_information($company_sid, $user_sid);

                    if (empty($user_info)) {
                        $this->session->set_flashdata('message', '<strong>Error:</strong> Applicant Not Found!');
                        redirect('employee_management', 'refresh');
                    }
                    $old_pending_documents = $this->documents_management_model->get_old_sent_documents($company_sid, $user_sid);
                    $data['old_pending_documents'] = $old_pending_documents;
                    break;
            }
            
            if (empty($user_info)) {
                $this->session->set_flashdata('message', '<strong>Error:</strong> User Not Found!');
                redirect('documents_management', 'refresh');
            }

            $i9_form = $this->documents_management_model->fetch_form('i9', $user_type, $user_sid);
            $w4_form = $this->documents_management_model->fetch_form('w4', $user_type, $user_sid);
            $data['i9_form'] = $i9_form;
            $data['w4_form'] = $w4_form;
            $uploaded_documents = $this->documents_management_model->get_all_documents($company_sid, 0);
            $generated_documents = $this->documents_management_model->get_all_generated_documents($company_sid, 0, $user_type);
            $assigned_documents = $this->documents_management_model->get_assigned_documents($company_sid, $user_type, $user_sid);
            $assign_u_doc_sids = array();
            $assign_g_doc_sids = array();
            $assign_u_doc_urls = array();

            foreach ($assigned_documents as $assigned_document) {
                if ($assigned_document['document_type'] == 'uploaded') {
                    $assign_u_doc_sids[] = $assigned_document['document_sid'];
                    $assign_u_doc_urls[$assigned_document['document_sid']] = AWS_S3_BUCKET_URL . $assigned_document['document_s3_file_name'];
                }

                if ($assigned_document['document_type'] == 'generated') {
                    $assign_g_doc_sids[] = $assigned_document['document_sid'];
                }
            }

            $data['uploaded_documents'] = $uploaded_documents;
            $data['generated_documents'] = $generated_documents;
            $data['assigned_documents'] = $assigned_documents;
            $data['assign_u_doc_sids'] = $assign_u_doc_sids;
            $data['assign_g_doc_sids'] = $assign_g_doc_sids;
            $data['assign_u_doc_urls'] = $assign_u_doc_urls;
            $data['user_info'] = $user_info;
            $data['user_type'] = $user_type;
            $data['user_sid'] = $user_sid;
            $data['job_list_sid'] = $jobs_listing;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/documents_assignment');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                
                switch ($perform_action) {
                    case 'assign_document':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $file_exists = false;
                        $document_info = array();
                        
                        switch ($document_type) {
                            case 'uploaded':
                                $document_info = $this->documents_management_model->get_uploaded_document_record($company_sid, $document_sid);
                                break;
                            case 'generated':
                                $document_info = $this->documents_management_model->get_generated_document($company_sid, $document_sid);
                                break;
                        }

                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                        $data_to_insert['assigned_by'] = $employer_sid;
                        $data_to_insert['user_type'] = $user_type;
                        $data_to_insert['user_sid'] = $user_sid;
                        $data_to_insert['document_type'] = $document_type;
                        $data_to_insert['document_sid'] = $document_sid;

                        if (isset($document_info['document_title'])) {
                            $data_to_insert['document_title'] = $document_info['document_title'];
                        }

                        if (isset($document_info['document_name'])) {
                            $data_to_insert['document_name'] = $document_info['document_name'];
                        }

                        if (isset($document_info['document_content'])) {
                            $data_to_insert['document_content'] = $document_info['document_content'];
                        }

                        if (isset($document_info['document_description'])) {
                            $data_to_insert['document_description'] = $document_info['document_description'];
                        }

                        if (isset($document_info['document_original_name'])) {
                            $data_to_insert['document_original_name'] = $document_info['document_original_name'];
                        }

                        if (isset($document_info['s3_file_name'])) {
                            $this->load->library('aws_lib');
                            $file_exists = $this->aws_lib->check_if_file_exists(AWS_S3_BUCKET_NAME, $document_info['s3_file_name']);
                            $file_info = pathinfo($document_info['s3_file_name']);

                            if (!empty($file_info)) {
                                $filename = $file_info['filename'];
                                $extension = $file_info['extension'];
                                $new_file_name = $filename . '_hr_' . $user_type . '_' . $user_sid . '_' . date('m-d-Y') . '_' . time() . '.' . $extension;
                                $this->aws_lib->copy_object(AWS_S3_BUCKET_NAME, $document_info['s3_file_name'], AWS_S3_BUCKET_NAME, $new_file_name);
                                $data_to_insert['document_s3_file_name'] = $new_file_name;
                            }
                        }

                        if ($file_exists) {
                            $this->documents_management_model->insert_documents_assignment_record($data_to_insert);
                        }

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Assigned!');
                        redirect('documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                        break;
                    case 'remove_document':
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $this->documents_management_model->delete_documents_assignment_record($document_type, $document_sid, $user_type, $user_sid);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Document Successfully Removed!');
                        redirect('documents_management/documents_assignment' . '/' . $user_type . '/' . $user_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function my_documents() {
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
            $documents = $this->documents_management_model->get_assigned_documents($company_sid, 'employee', $employer_sid);
            $data['documents'] = $documents;
            $data['load_view'] = check_blue_panel_status(false, 'self');
            $data['employee'] = $data['session']['employer_detail'];
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
            $data['w4_form'] = $this->onboarding_model->get_w4_form('employee', $employer_sid);
            $data['i9_form'] = $this->onboarding_model->get_i9_form('employee', $employer_sid);
            $old_documents = $this->onboarding_model->get_old_system_documents('employee', $employer_sid);

            $data['old_system_documents'] = $old_documents;     
           
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/my_documents');
                $this->load->view('main/footer');
//                $this->load->view('onboarding/on_boarding_header', $data);
//                $this->load->view('onboarding/documents');
//                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'sign_document':
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function sign_u_document($document_sid) {
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

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required');
            if ($this->form_validation->run() == false) {
                $data['title'] = 'Automoto HR Onboarding';
                $document = $this->documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, 'uploaded');
                $data['document'] = $document;
                $data['employee'] = $data['session']['employer_detail'];
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/sign_u_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                
                switch ($perform_action) {
                    case 'acknowledge_document':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $this->documents_management_model->update_acknowledge_status($user_type, $user_sid, $document_type, $document_sid);
                        $this->session->set_flashdata('message', '<strong>Success</strong> Document Acknowledged!');
                        redirect('documents_management/sign_u_document/' . $document_sid, 'refresh');
                        break;
                    case 'upload_document':
                        $user_type = $this->input->post('user_type');
                        $user_sid = $this->input->post('user_sid');
                        $document_type = $this->input->post('document_type');
                        $document_sid = $this->input->post('document_sid');
                        $company_sid = $this->input->post('company_sid');
                        $aws_file_name = upload_file_to_aws('upload_file', $company_sid, $document_type . '_' . $document_sid, time());

                        $uploaded_file = '';
                        
                        if ($aws_file_name != 'error') {
                            $uploaded_file = $aws_file_name;
                        }

                        if (!empty($uploaded_file)) {
                            $this->documents_management_model->update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file);
                            $this->session->set_flashdata('message', '<strong>Success</strong> Document Uploaded!');
                        } else {
                            $this->session->set_flashdata('message', '<strong>Error</strong> Document Uploaded was not successful!');
                        }

                        redirect('documents_management/sign_u_document/' . $document_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', 'refresh');
        }
    }

    public function download_u_document($document_sid) {
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
                $document = $this->documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid, 'uploaded');
                $data['document'] = $document;
                $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                $file_name = $document['document_original_name'];
                $temp_file_path = $temp_path . $file_name;

                if (file_exists($temp_file_path)) {
                    unlink($temp_file_path);
                }

                $this->load->library('aws_lib');
                $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['document_s3_file_name'], $temp_file_path);

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

                $this->load->model('documents_management_model');
                $this->documents_management_model->update_download_status('employee', $employer_sid, 'uploaded', $document_sid);
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function sign_g_document($document_sid) {
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
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            
            if ($this->form_validation->run() == false) {
                $data['title'] = 'Automoto HR Onboarding';
                $this->load->model('documents_management_model');
                $document = $this->documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid);

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_sid, $employer_sid, 'employee', $document['document_content']);
                    $document['document_content'] = $document_content;
                }

                $data['document'] = $document;
                $this->load->model('e_signature_model');
                $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
                $signed_flag = false;

                if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                    $e_signature_data = array();
                    $e_signature_data['company_sid'] = $document['company_sid'];
                    $e_signature_data['first_name'] = $document['first_name'];
                    $e_signature_data['last_name'] = $document['last_name'];
                    $e_signature_data['email_address'] = $document['email_address'];
                    $e_signature_data['user_type'] = $document['user_type'];
                    $e_signature_data['user_sid'] = $document['user_sid'];
                    $e_signature_data['signature'] = $document['signature'];
                    $e_signature_data['signature_hash'] = $document['signature_hash'];
                    $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                    $e_signature_data['signature_bas64_image'] = $document['signature_bas64_image'];
                    $e_signature_data['active_signature'] = $document['active_signature'];
                    $e_signature_data['ip_address'] = $document['ip_address'];
                    $e_signature_data['latitude'] = $document['latitude'];
                    $e_signature_data['longitude'] = $document['longitude'];
                    $e_signature_data['user_agent'] = $document['user_agent'];
                    $e_signature_data['user_consent'] = $document['user_consent'];
                    $signed_flag = true;
                }

                $data['e_signature_data'] = $e_signature_data;
                $data['signed_flag'] = $signed_flag;
                $data['first_name'] = $data['session']['employer_detail']['first_name'];
                $data['last_name'] = $data['session']['employer_detail']['last_name'];
                $data['email'] = $data['session']['employer_detail']['email'];
                $data['employee'] = $data['session']['employer_detail'];
                //$this->load->view('main/header', $data);
                //$this->load->view('documents_management/sign_g_document');
                //$this->load->view('main/footer');
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/sign_g_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'sign_document':
                        $company_sid = $this->input->post('company_sid');
                        $users_type = $this->input->post('user_type');
                        $users_sid = $this->input->post('user_sid');
                        $ip_address = $this->input->post('ip_address');
                        $users_agent = $this->input->post('user_agent');
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $email_address = $this->input->post('email_address');
                        $signature_timestamp = $this->input->post('signature_timestamp');
                        $documents_assignment_sid = $this->input->post('documents_assignment_sid');
                        $active_signature = $this->input->post('active_signature');
                        $signature = $this->input->post('signature');
                        $signature_bas64_image = $this->input->post('signature_bas64_image');
                        $drawn_signature = $this->input->post('drawn_signature');
                        $user_consent = $this->input->post('user_consent');
                        $data_to_update = array();
                        $data_to_update['first_name'] = $first_name;
                        $data_to_update['last_name'] = $last_name;
                        $data_to_update['email_address'] = $email_address;
                        $data_to_update['signature'] = $signature;
                        $data_to_update['signature_hash'] = md5($signature);
                        $data_to_update['signature_timestamp'] = $signature_timestamp;
                        $data_to_update['signature_bas64_image'] = $signature_bas64_image;
                        $data_to_update['ip_address'] = $ip_address;
                        $data_to_update['user_agent'] = $users_agent;
                        $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                        $data_to_update['active_signature'] = $active_signature;
                        $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                        $this->onboarding_model->sign_document($company_sid, $users_type, $users_sid, $documents_assignment_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');

                        if ($users_type == 'employee') {
                            redirect('documents_management/sign_g_document/' . $document_sid);
                        } else {
                            redirect('onboarding/sign_g_document/' . $document_sid);
                        }
                        
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function sign_offer_letter($document_sid) {
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
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            
            if ($this->form_validation->run() == false) {
                $data['title'] = 'Automoto HR Onboarding';
                $this->load->model('documents_management_model');
                $document = $this->documents_management_model->get_assigned_document('employee', $employer_sid, $document_sid);

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_sid, $employer_sid, 'employee', $document['document_content']);
                    $document['document_content'] = $document_content;
                }

                $data['document'] = $document;
                $this->load->model('e_signature_model');
                $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
                $signed_flag = false;

                if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                    $e_signature_data = array();
                    $e_signature_data['company_sid'] = $document['company_sid'];
                    $e_signature_data['first_name'] = $document['first_name'];
                    $e_signature_data['last_name'] = $document['last_name'];
                    $e_signature_data['email_address'] = $document['email_address'];
                    $e_signature_data['user_type'] = $document['user_type'];
                    $e_signature_data['user_sid'] = $document['user_sid'];
                    $e_signature_data['signature'] = $document['signature'];
                    $e_signature_data['signature_hash'] = $document['signature_hash'];
                    $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                    $e_signature_data['signature_bas64_image'] = $document['signature_bas64_image'];
                    $e_signature_data['active_signature'] = $document['active_signature'];
                    $e_signature_data['ip_address'] = $document['ip_address'];
                    $e_signature_data['latitude'] = $document['latitude'];
                    $e_signature_data['longitude'] = $document['longitude'];
                    $e_signature_data['user_agent'] = $document['user_agent'];
                    $e_signature_data['user_consent'] = $document['user_consent'];
                    $signed_flag = true;
                }

                $data['e_signature_data'] = $e_signature_data;
                $data['signed_flag'] = $signed_flag;
                $data['first_name'] = $data['session']['employer_detail']['first_name'];
                $data['last_name'] = $data['session']['employer_detail']['last_name'];
                $data['email'] = $data['session']['employer_detail']['email'];
                $data['employee'] = $data['session']['employer_detail'];
                //$this->load->view('main/header', $data);
                //$this->load->view('documents_management/sign_g_document');
                //$this->load->view('main/footer');
                $this->load->view('onboarding/on_boarding_header', $data);
                $this->load->view('onboarding/sign_g_document');
                $this->load->view('onboarding/on_boarding_footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'sign_document':
                        $company_sid = $this->input->post('company_sid');
                        $users_type = $this->input->post('user_type');
                        $users_sid = $this->input->post('user_sid');
                        $ip_address = $this->input->post('ip_address');
                        $users_agent = $this->input->post('user_agent');
                        $first_name = $this->input->post('first_name');
                        $last_name = $this->input->post('last_name');
                        $email_address = $this->input->post('email_address');
                        $signature_timestamp = $this->input->post('signature_timestamp');
                        $documents_assignment_sid = $this->input->post('documents_assignment_sid');
                        $active_signature = $this->input->post('active_signature');
                        $signature = $this->input->post('signature');
                        $signature_bas64_image = $this->input->post('signature_bas64_image');
                        $drawn_signature = $this->input->post('drawn_signature');
                        $user_consent = $this->input->post('user_consent');
                        $data_to_update = array();
                        $data_to_update['first_name'] = $first_name;
                        $data_to_update['last_name'] = $last_name;
                        $data_to_update['email_address'] = $email_address;
                        $data_to_update['signature'] = $signature;
                        $data_to_update['signature_hash'] = md5($signature);
                        $data_to_update['signature_timestamp'] = $signature_timestamp;
                        $data_to_update['signature_bas64_image'] = $signature_bas64_image;
                        $data_to_update['ip_address'] = $ip_address;
                        $data_to_update['user_agent'] = $users_agent;
                        $data_to_update['user_consent'] = $user_consent == 1 ? 1 : 0;
                        $data_to_update['active_signature'] = $active_signature;
                        $data_to_update['signature_timestamp'] = date('Y-m-d H:i:s');
                        $this->onboarding_model->sign_document($company_sid, $users_type, $users_sid, $documents_assignment_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<b>Success: </b> You Have Successfully Signed This Document!');

                        if ($users_type == 'employee') {
                            redirect('documents_management/sign_offer_letter/' . $document_sid);
                        } else {
                            redirect('onboarding/sign_offer_letter/' . $document_sid);
                        }
                        
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function view_g_document($user_type, $user_sid, $document_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Documents Assignment';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            
            if ($this->form_validation->run() == false) {
                $data['title'] = 'Automoto HR Onboarding';
                $this->load->model('documents_management_model');
                $document = $this->documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);

                if (!empty($document)) {
                    $document_content = replace_tags_for_document($company_sid, $user_sid, $user_type, $document['document_content']);
                    $document['document_content'] = $document_content;
                }

                $data['document'] = $document;
                $signed_flag = false;

                if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                    $signed_flag = true;
                } else {
                    $this->session->set_flashdata('message', '<strong>Error: </strong> Document Not Yet Signed!');
                    redirect('documents_management/documents_assignment/' . $user_type . '/' . $user_sid, 'refresh');
                }

                $data['signed_flag'] = $signed_flag;
                $data['first_name'] = $document['first_name'];
                $data['last_name'] = $document['last_name'];
                $data['email'] = $document['email_address'];
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/view_g_document');
                $this->load->view('main/footer');
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function people_with_pending_documents() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'People With Pending Documents';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $old_pending_documents = $this->documents_management_model->get_old_sent_documents($company_sid);
                $data['old_pending_documents'] = $old_pending_documents;
                $uploaded_employee_documents = $this->documents_management_model->get_pending_assigned_documents($company_sid, 'employee', 'uploaded');
                $data['uploaded_employee_documents'] = $uploaded_employee_documents;
                $generated_employee_documents = $this->documents_management_model->get_pending_assigned_documents($company_sid, 'employee', 'generated');
                $data['generated_employee_documents'] = $generated_employee_documents;

                $employeesInArray = array('');
                $i = 0;
                $data['docs'] = $this->documents_management_model->getAllEmployeesWithPendingAction($company_sid, 'document');

                foreach ($data['docs'] as $key => $value) {
                    $result = $this->documents_management_model->removeVerificationKey($value['sid']);

                    if ($result == "false") {
                        if (!in_array($value['receiver_sid'], $employeesInArray)) {
                            $employeesInArray[$i] = $value['receiver_sid'];
                            $i++;
                        }
                    }
                }
                $data['employees'] = $this->documents_management_model->getEmployeesDetails($employeesInArray);

                /*
                  $uploaded_applicant_documents  = $this->documents_management_model->get_pending_assigned_documents($company_sid, 'applicant', 'uploaded');
                  $data['uploaded_applicant_documents'] = $uploaded_applicant_documents;
                  $generated_applicant_documents  = $this->documents_management_model->get_pending_assigned_documents($company_sid, 'applicant', 'generated');
                  $data['generated_applicant_documents'] = $generated_applicant_documents;
                 */

                $this->load->view('main/header', $data);
                $this->load->view('documents_management/new_people_with_pending_documents');
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
                check_access_permissions($security_details, 'dashboard', 'hr_documents'); // Param2: Redirect URL, Param3: Function Name
                $data['title'] = 'HR Documents';
                $company_sid = $data['session']['company_detail']['sid'];
                $employer_id = $data['session']['employer_detail']['sid'];
                $data['docs'] = $this->documents_management_model->getEmployeePendingActionDocument($company_sid, 'document', $employee_id); //getting all users which still have pending documents to Review Starts.
                $this->load->model('my_hr_document_model');
                $data['documents'] = array();
                $i = 0;

                foreach ($data['docs'] as $key => $value) {
                    $result = $this->documents_management_model->removeVerificationKey($value['userDocumentSid']);

                    if ($result == "false") {
                        $data['documents'][$i] = $value;
                        $i++;
                    }
                }
                $data['userDetail'] = $this->documents_management_model->getEmployerDetail($employee_id);
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/pending-hr-document');
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
            $userData = $this->documents_management_model->getUserDocument($userDocumentSid);
            $userData = $userData[0];
            $this->load->model('dashboard_model');
            $data['session'] = $this->session->userdata('logged_in');
            $company_sid = $data["session"]["company_detail"]["sid"];
            $company_name = $data["session"]["company_detail"]["CompanyName"];
            $company_data = $this->dashboard_model->get_company_detail($company_sid);
            $companyname = $company_data['CompanyName'];
            $message_hf = (message_header_footer($company_sid, $companyname));
            //$emailTemplateData = get_email_template(HR_DOCUMENTS_NOTIFICATION);
            $emailTemplateData = $this->documents_management_model->get_admin_or_company_email_template(HR_DOCUMENTS_NOTIFICATION, $company_sid);
            $emailTemplateBody = $emailTemplateData['text'];
            $emailTemplateBody = str_replace('{{username}}', ucwords($userData['first_name'] . ' ' . $userData['last_name']), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{baseurl}}', base_url(), $emailTemplateBody);
            $emailTemplateBody = str_replace('{{verification_key}}', $userData['ver_key'], $emailTemplateBody);
            $emailTemplateBody = str_replace('{{company_name}}', $company_name, $emailTemplateBody);

            replace_magic_quotes($emailTemplateBody);
            
            $from = $emailTemplateData['from_email'];
            $to = $userData['email'];
            $subject = $emailTemplateData['subject'];
            $from_name = $emailTemplateData['from_name'];


            /*

            $from                                                               = FROM_EMAIL_NOTIFICATIONS;
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

            if (base_url() == 'http://localhost/automotoCI') {
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
            $emailLog['subject'] = $subject;
            $emailLog['email'] = $to;
            $emailLog['message'] = $emailTemplateBody;
            $emailLog['date'] = date('Y-m-d H:i:s');
            $emailLog['admin'] = 'admin';
            $emailLog['status'] = 'Delivered';
            save_email_log_common($emailLog);
            $this->session->set_flashdata('message', '<b>Success:</b> Document reminder sent successfully!');
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Please try again!');
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
                        $user_type = empty($user_type) ? null : $user_type;
                        $user_sid = empty($user_sid) ? null : $user_sid;
                        
                        if ($source == 'generated' || $source == 'original' || ($user_type == null && $user_sid == null)) {
                            if($source == 'offer'){
                                $document_info = $this->documents_management_model->get_offer_letter($company_sid, $document_sid);
                            } else{
                                $document_info = $this->documents_management_model->get_generated_document($company_sid, $document_sid);
                            }
                        } else {
                            $document_info = $this->documents_management_model->get_assigned_document($user_type, $user_sid, $document_sid);
                        }

                        if (!empty($document_info)) {
                            $document_content = $source == 'offer' ? $document_info['letter_body'] : $document_info['document_content'];
                            $document = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_content);
                            $view_data = array();
                            $view_data['document_title'] = $source == 'offer' ? $document_info['letter_name'] : $document_info['document_title'];
                            $view_data['document_body'] = $document;
                            echo $this->load->view('documents_management/generated_document_preview_partial', $view_data, true);
                        }

                        break;
                }
            }
        }
    }

    public function generate_new_offer_letter() {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Generate New Offer Letter';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/generate_new_offer_letter');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'generate_new_offer_letter':
                        $target_user_type = $this->input->post('target_user_type');
                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $company_sid = $this->input->post('company_sid');
                        $offer_letter_data = array();
                        $offer_letter_data['target_user_type'] = $target_user_type;
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['archive'] = 0;
                        $offer_letter_data['created_date'] = date('Y-m-d H:i:s');

                        $this->documents_management_model->add_new_offer_letter($offer_letter_data);

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully Created!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function edit_offer_letter($offer_letter_sid) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Edit Offer Letter';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                $offer_letter_info = $this->documents_management_model->get_offer_letter($company_sid, $offer_letter_sid);
                $data['offer_letter_info'] = $offer_letter_info;
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/generate_new_offer_letter');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'generate_new_offer_letter':
                        $target_user_type = $this->input->post('target_user_type');
                        $letter_name = $this->input->post('letter_name');
                        $letter_body = $this->input->post('letter_body');
                        $company_sid = $this->input->post('company_sid');
                        $offer_letter_sid = $this->input->post('offer_letter_sid');
                        $offer_letter_data = array();
                        $offer_letter_data['target_user_type'] = $target_user_type;
                        $offer_letter_data['letter_name'] = $letter_name;
                        $offer_letter_data['letter_body'] = htmlentities($letter_body);
                        $offer_letter_data['company_sid'] = $company_sid;
                        $offer_letter_data['archive'] = 0;
                        $this->documents_management_model->update_offer_letter($offer_letter_sid, $offer_letter_data);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Offer Letter Successfully updated!');
                        redirect('documents_management', 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function old_system_document($record_sid) {
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
            $data['load_view'] = check_blue_panel_status(false, 'self');
            $data['employer'] = $data['session']['employer_detail'];
            $data['employee'] = $data['session']['employer_detail'];
            $document = $this->documents_management_model->get_old_document($company_sid, $employer_sid, $record_sid);
            $data['document'] = $document;
//            echo '<pre>';
//            print_r($document);
//            die();
            $this->load->model('e_signature_model');
            $e_signature_data = $this->e_signature_model->get_signature_record('employee', $employer_sid);
            $signed_flag = false;

            if (!empty($document['first_name']) && (!empty($document['signature']) || !empty($document['signature_bas64_image']))) {
                $e_signature_data = array();
                $e_signature_data['company_sid'] = $document['company_sid'];
                $e_signature_data['first_name'] = $document['first_name'];
                $e_signature_data['last_name'] = $document['last_name'];
                $e_signature_data['email_address'] = $document['email_address'];
                $e_signature_data['user_type'] = $document['user_type'];
                $e_signature_data['user_sid'] = $document['user_sid'];
                $e_signature_data['signature'] = $document['signature'];
                $e_signature_data['signature_hash'] = $document['signature_hash'];
                $e_signature_data['signature_timestamp'] = $document['signature_timestamp'];
                $e_signature_data['signature_bas64_image'] = $document['signature_bas64_image'];
                $e_signature_data['active_signature'] = $document['active_signature'];
                $e_signature_data['ip_address'] = $document['ip_address'];
                $e_signature_data['latitude'] = $document['latitude'];
                $e_signature_data['longitude'] = $document['longitude'];
                $e_signature_data['user_agent'] = $document['user_agent'];
                $e_signature_data['user_consent'] = $document['user_consent'];
                $signed_flag = true;
            }

            $data['e_signature_data'] = $e_signature_data;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
            
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('documents_management/view_sign_old_document_green');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'upload_document':
                        //$user_type = $this->input->post('user_type');
                        //$user_sid = $this->input->post('user_sid');
                        $record_sid = $this->input->post('record_sid');
                        $company_sid = $this->input->post('company_sid');
                        //$document_type = $this->input->post('document_type');
                        $s3_file_name = upload_file_to_aws('upload_file', $company_sid, 'employee_response_document_' . time(), $employer_sid);

                        if ($s3_file_name != 'error') {
                            $this->documents_management_model->update_uploaded_file_name($company_sid, $employer_sid, $record_sid, $s3_file_name);
                            $this->documents_management_model->update_old_system_document_status($company_sid, $employer_sid, $record_sid, 'uploaded', 1);
                        }

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Document Successfully Uploaded!');
                        redirect('documents_management/old_system_document/' . $record_sid, 'refresh');
                        break;
                    case 'acknowledge_document':
                        //$user_type = $this->input->post('user_type');
                        //$user_sid = $this->input->post('user_sid');
                        $record_sid = $this->input->post('record_sid');
                        $company_sid = $this->input->post('company_sid');
                        //$document_type = $this->input->post('document_type');
                        $this->documents_management_model->update_old_system_document_status($company_sid, $employer_sid, $record_sid, 'acknowledged', 1);
                        $this->session->set_flashdata('message', '<strong>Success: </strong> Document Acknowledged!');
                        redirect('documents_management/old_system_document/' . $record_sid, 'refresh');
                        break;
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function download_old_system_document($record_sid) {
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
                $document = $this->documents_management_model->get_old_document($company_sid, $employer_sid, $record_sid);
                $data['document'] = $document;
                $document_type = $document['document_type'];
                $this->documents_management_model->update_old_system_document_status($company_sid, $employer_sid, $record_sid, 'downloaded', 1);

                if ($document_type == 'document') {
                    $temp_path = FCPATH . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'temp_files' . DIRECTORY_SEPARATOR;
                    $file_name = $document['document_name'];
                    $temp_file_path = $temp_path . $file_name;

                    if (file_exists($temp_file_path)) {
                        unlink($temp_file_path);
                    }

                    $this->load->library('aws_lib');
                    $this->aws_lib->get_object(AWS_S3_BUCKET_NAME, $document['document_name'], $temp_file_path);

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
                } else if ($document_type == 'offerletter') {
                    $fileName = str_replace(' ', '-', $document['offer_letter_name'] . '.doc');
                    header('Content-Type: application/octet-stream');
                    header("Content-Disposition: attachment;Filename=$fileName");

                    echo "<html>";
                    echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
                    echo "<body>";
                    echo $document['offer_letter_body'];
                    echo "</body>";
                    echo "</html>";
                }
            } else {
                //nothing
            }
        } else {
            redirect('login', "refresh");
        }
    }
}