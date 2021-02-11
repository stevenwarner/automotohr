<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Portal_email_templates extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('portal_email_templates_model');
    }

    public function index($company_sid = null) {
        $redirect_url = 'manage_admin';
        $function_name = 'portal_email_templates';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        if ($company_sid != null) {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            $company_name = '{{company_name}}';//$this->portal_email_templates_model->getCompanyName($company_sid);
            $primary_admin_email = FROM_EMAIL_INFO; //$this->portal_email_templates_model->getCompanyPrimaryAdminEmail($company_sid);
            $this->portal_email_templates_model->check_default_tables($company_sid, $primary_admin_email, $company_name);
            $company_portal_email_templates = $this->portal_email_templates_model->getallemailtemplates($company_sid);
            $this->data['portal_email_templates'] = $company_portal_email_templates;
            $this->data['company_sid'] = $company_sid;
            $this->render('manage_admin/portal_email_templates/index');
        } else {
            $this->session->set_flashdata('message', '<strong>Error:</strong> Company Not Found!');
            redirect('manage_admin/companies/', 'refresh');
        }
    }

    public function edit($sid = null) {
        $redirect_url = 'manage_admin';
        $function_name = 'add_email_templates_group';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required');
        $this->form_validation->set_rules('from_name', 'From Name', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message_body', 'Message Body', 'trim|required');
        $this->form_validation->set_rules('enable_auto_responder', 'Enable Auto responder', 'trim');

        if ($sid != null) {
            $email_template = $this->portal_email_templates_model->getSingleTemplateDetails($sid);

            if (!empty($email_template)) {
                $this->data['template'] = $email_template;

                if ($this->form_validation->run() == false) {
                    $this->render('manage_admin/portal_email_templates/edit');
                } else {
                    $dataToUpdate = array();
                    $dataToUpdate['template_name'] = $this->input->post('template_name');
                    $dataToUpdate['from_name'] = $this->input->post('from_name');
                    $dataToUpdate['subject'] = $this->input->post('subject');
                    $dataToUpdate['message_body'] = $this->input->post('message_body');
                    $enable_auto_responder = $this->input->post('enable_auto_responder');

                    if (empty($enable_auto_responder)) {
                        $enable_auto_responder = 0;
                    } else {
                        $enable_auto_responder = 1;
                    }
                    
                    $dataToUpdate['enable_auto_responder'] = $enable_auto_responder;
                    $this->portal_email_templates_model->update_email_template($dataToUpdate, $sid);
                    $this->session->set_flashdata('message', '<strong>Success: </strong> Template Updated!');
                    redirect('manage_admin/portal_email_templates/' . $email_template['company_sid']);
                }
            } else {
                redirect('manage_admin/companies/', 'refresh');
            }
        } else {
            redirect('manage_admin/companies/', 'refresh');
        }
    }
}