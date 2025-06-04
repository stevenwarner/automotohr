<?php defined('BASEPATH') or exit('No direct script access allowed');

class Portal_email_templates extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('portal_email_templates_model');
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $data['title'] = 'Email Templates Module';
            // Push new default emails 
            // for employees
            $this->portal_email_templates_model->check_default_tables($company_id, $data['session']['employer_detail']['email'], $company_name);
            $data['all_templates'] = $this->portal_email_templates_model->getallemailtemplates($company_id);
            $data['employer_id'] = $employer_id;
            $data['company_id'] = $company_id;
            $data['company_name'] = $company_name;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'trim|xss_clean|required');

            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('email_templates/portal_email_templates');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'save_template':
                        $template_name = $this->input->post('template_name');
                        $template_subject = $this->input->post('template_subject');
                        $template_body = $this->input->post('template_body', false);
                        $company_sid = $this->input->post('company_sid');
                        $company_name = $this->input->post('company_name');
                        $data_to_insert = array();
                        $data_to_insert['template_code'] = strtolower(str_replace(' ', '_', trim($template_name)));
                        $data_to_insert['template_name'] = $template_name;
                        $data_to_insert['company_sid'] = $company_sid;
                        $data_to_insert['created'] = date('Y-m-d H:i:s');
                        $data_to_insert['status'] = 1;
                        $data_to_insert['from_name'] = $company_name;
                        $data_to_insert['from_email'] = FROM_EMAIL_INFO;
                        $data_to_insert['subject'] = $template_subject;
                        $data_to_insert['message_body'] = $template_body;
                        $data_to_insert['is_custom'] = 1;
                        $data_to_insert['enable_auto_responder'] = 0;

                        if (isset($_FILES) && !empty($_FILES)) {
                            $data_to_insert['has_attachment'] = 1;
                        }

                        $template_sid = $this->portal_email_templates_model->insert_portal_email_template($data_to_insert);
                        $file_names = $this->upload_multiple_attachments($company_id, $employer_id, 'portal_email_templates');

                        foreach ($file_names as $key => $file_name) {
                            $attachment_data = array();
                            $attachment_data['company_sid'] = $company_id;
                            $attachment_data['employer_sid'] = $employer_id;
                            $attachment_data['portal_email_template_sid'] = $template_sid;
                            $attachment_data['attachment_aws_file'] = $file_name;
                            $attachment_data['created_date'] = date('Y-m-d H:i:s');
                            $attachment_data['original_file_name'] = $key;
                            $this->portal_email_templates_model->insert_email_template_attachment_record($attachment_data);
                        }

                        $this->session->set_flashdata('message', '<strong>Success:</strong> Email Template Saved');
                        redirect('portal_email_templates', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function add_email_template()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
            $employer_id = $data['session']['employer_detail']['sid'];
            $company_id = $data['session']['company_detail']['sid'];
            $data['title'] = 'Email Templates Module';
            $data['company_sid'] = $company_id;
            $data['company_name'] = $data['session']['company_detail']['CompanyName'];
            $names = array();
            $temp_names = $this->portal_email_templates_model->get_company_custom_template_names($company_id);
            foreach ($temp_names as $name) {
                $names[] = strtolower($name['template_name']);
            }
            $data['names'] = json_encode($names);
            if (isset($_POST['action']) && $_POST['action'] == 'edit_email_template') {
                $template_name = $this->input->post('template_name');
                $template_subject = $this->input->post('subject');
                $template_body = $this->input->post('message_body', false);
                $company_sid = $this->input->post('company_sid');
                $company_name = $this->input->post('company_name');
                $from_name = $this->input->post('from_name');
                $data_to_insert = array();
                $data_to_insert['template_code'] = strtolower(str_replace(' ', '_', trim($template_name)));
                $data_to_insert['template_name'] = $template_name;
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['created'] = date('Y-m-d H:i:s');
                $data_to_insert['status'] = 1;
                $data_to_insert['from_name'] = ucwords($company_name);
                $data_to_insert['from_email'] = FROM_EMAIL_INFO;
                $data_to_insert['subject'] = $template_subject;
                $data_to_insert['message_body'] = $template_body;
                $data_to_insert['is_custom'] = 1;
                $data_to_insert['enable_auto_responder'] = 0;

                if (!empty($_FILES)) {
                    $data_to_insert['has_attachment'] = 1;
                } else {
                    $data_to_insert['has_attachment'] = 0;
                }

                $template_sid = $this->portal_email_templates_model->insert_portal_email_template($data_to_insert);
                $file_names = $this->upload_multiple_attachments($company_id, $employer_id, 'portal_email_templates');

                foreach ($file_names as $key => $file_name) {
                    $attachment_data = array();
                    $attachment_data['company_sid'] = $company_id;
                    $attachment_data['employer_sid'] = $employer_id;
                    $attachment_data['portal_email_template_sid'] = $template_sid;
                    $attachment_data['attachment_aws_file'] = $file_name;
                    $attachment_data['created_date'] = date('Y-m-d H:i:s');
                    $attachment_data['original_file_name'] = $key;
                    $this->portal_email_templates_model->insert_email_template_attachment_record($attachment_data);
                }

                $this->session->set_flashdata('message', '<b>Success:</b> Email template <b><i>' . $template_name . ' </b></i>Added successfully');
                redirect('portal_email_templates', 'refresh');
            }

            $this->load->view('main/header', $data);
            $this->load->view('email_templates/portal_add_email_templates');
            $this->load->view('main/footer');
        } else { // customer is not logged in
            redirect(base_url('login'), "refresh");
        }
    }

    public function edit_email_template($sid = null)
    {
        if ($this->session->userdata('logged_in')) {
            if (!empty($sid) || $sid != null) {
                $data['session'] = $this->session->userdata('logged_in');
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
                check_access_permissions($security_details, 'my_settings', 'portal_email_templates');
                $employer_id = $data['session']['employer_detail']['sid'];
                $company_id = $data['session']['company_detail']['sid'];
                $data['title'] = 'Email Templates Module';
                $template_data = $this->portal_email_templates_model->gettemplatedetails($sid, $company_id);

                if (!empty($template_data)) {
                    $data['employer_id'] = $employer_id;
                    $data['company_id'] = $company_id;
                    $data['template_data'] = $template_data[0];

                    $names = array();
                    $temp_names = $this->portal_email_templates_model->get_company_custom_template_names($company_id, $sid);
                    foreach ($temp_names as $name) {
                        $names[] = strtolower($name['template_name']);
                    }
                    $data['names'] = json_encode($names);
                    if (isset($_POST['action']) && $_POST['action'] == 'edit_email_template') {
                        $formpost = $this->input->post(NULL, TRUE);
                        $template_name = $formpost['template_name'];
                        $from_name = $formpost['from_name'];
                        //$from_email                                         = $formpost['from_email'];
                        $subject = $formpost['subject'];
                        $message_body = $this->input->post('message_body', false);
                        $enable_auto_responder = 0;

                        if (isset($formpost['enable_auto_responder'])) {
                            $enable_auto_responder = 1;
                        }

                        $data_to_save = array(
                            'template_name' => $template_name,
                            'from_name' => $from_name,
                            'subject' => $subject,
                            'message_body' => $message_body,
                            'enable_auto_responder' => $enable_auto_responder
                        );

                        $file_names = $this->upload_multiple_attachments($company_id, $employer_id, 'portal_email_templates');


                        if (!empty($file_names)) {
                            $data_to_save['has_attachment'] = 1;
                        } else {
                            $data_to_save['has_attachment'] = 0;
                        }

                        $this->portal_email_templates_model->update_email_template($data_to_save, $sid);
                        foreach ($file_names as $key => $file_name) {
                            $attachment_data = array();
                            $attachment_data['company_sid'] = $company_id;
                            $attachment_data['employer_sid'] = $employer_id;
                            $attachment_data['portal_email_template_sid'] = $sid;
                            $attachment_data['attachment_aws_file'] = $file_name;
                            $attachment_data['created_date'] = date('Y-m-d H:i:s');
                            $attachment_data['original_file_name'] = $key;
                            $this->portal_email_templates_model->insert_email_template_attachment_record($attachment_data);
                        }

                        $this->session->set_flashdata('message', '<b>Success:</b> Email template <b><i>' . $template_name . ' </b></i>updated successfully');
                        redirect('portal_email_templates', 'refresh');
                    }

                    $attachments = $this->portal_email_templates_model->get_all_email_template_attachments($sid);

                    if (!empty($attachments)) {
                        foreach ($attachments as $key => $attachment) {
                            $attached_document = $attachment['attachment_aws_file'];
                            $file_name = explode(".", $attached_document);
                            $document_name = $file_name[0];
                            $document_extension = $file_name[1];

                            if ($document_extension == 'pdf') {
                                $attachments[$key]['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.pdf';
                            } else if ($document_extension == 'doc') {
                                $attachments[$key]['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edoc&wdAccPdf=0';
                            } else if ($document_extension == 'docx') {
                                $attachments[$key]['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Edocx&wdAccPdf=0';
                            } else if ($document_extension == 'xls') {
                                $attachments[$key]['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exls';
                            } else if ($document_extension == 'xlsx') {
                                $attachments[$key]['print_url'] = 'https://view.officeapps.live.com/op/view.aspx?src=https%3A%2F%2Fautomotohrattachments%2Es3%2Eamazonaws%2Ecom%3A443%2F' . $document_name . '%2Exlsx';
                            } else if ($document_extension == 'csv') {
                                $attachments[$key]['print_url'] = 'https://docs.google.com/viewerng/viewer?url=https://automotohrattachments.s3.amazonaws.com/' . $document_name . '.csv';

                            } else if (in_array($document_extension, ['jpe', 'jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'])) {
                                $attachments[$key]['print_url'] = base_url('hr_documents_management/print_generated_and_offer_later/original/generated/' . $document_sid);
                            }

                            $attachments[$key]['download_url'] = base_url('hr_documents_management/download_upload_document/' . $attached_document);
                        }
                    }

                    $data['attachments'] = $attachments;

                    $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

                    if ($this->form_validation->run() == false) {
                        $this->load->view('main/header', $data);
                        $this->load->view('email_templates/portal_edit_email_templates');
                        $this->load->view('main/footer');
                    } else {
                        $perform_action = $this->input->post('perform_action');

                        switch ($perform_action) {
                            case 'delete_attachment':
                                $attachment_sid = $this->input->post('attachment_sid');
                                $portal_email_template_sid = $this->input->post('portal_email_template_sid');
                                $this->portal_email_templates_model->delete_attachment($attachment_sid);
                                $this->session->set_flashdata('message', '<b>Success:</b> Attachment Deleted!');
                                redirect('portal_email_templates/edit_email_template/' . $portal_email_template_sid, 'refresh');
                                break;
                        }
                    }
                } else { // template not found
                    $this->session->set_flashdata('message', '<b>Error:</b> Email template not found!');
                    redirect('portal_email_templates', 'refresh');
                }
            } else { // template id not found
                $this->session->set_flashdata('message', '<b>Error:</b> Email template not found!');
                redirect('portal_email_templates', 'refresh');
            }
        } else { // customer is not logged in
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_handler()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->portal_email_templates_model->delete_custom_email_template($id);
            echo 1;
        } else {
            echo 0;
        }
    }

    private function upload_multiple_attachments($company_sid, $employer_sid, $document_name)
    {
        $aws_file_names = array();

        foreach ($_FILES as $key => $file) {
            $original_name = 'empty';
            if (isset($_FILES[$key]) && $_FILES[$key]['name'] != '') {
                $original_name = $_FILES[$key]['name'];
                if ($_FILES[$key]['size'] == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                    $result = 'error';
                } else {
                    $new_file_name = upload_file_to_aws($key, $company_sid, str_replace(' ', '_', 'email template attachment'), $employer_sid, AWS_S3_BUCKET_NAME);
                    $result = $new_file_name;
                }
            } else {
                $result = 'error';
            }
            if ($result != 'error') {
                $aws_file_names[$original_name] = $result;
            }
        }

        return $aws_file_names;
    }


}