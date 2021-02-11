<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Job_listing_templates extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/company_model');
        $this->load->model('manage_admin/Job_listing_templates_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        $redirect_url = 'manage_admin';
        $function_name = 'job_listing_templates';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Job Listing Templates';
        $templates = $this->Job_listing_templates_model->GetAllActiveTemplates(); //Get All Templates
        $this->data['templates'] = $templates;
        $groups = $this->Job_listing_templates_model->GetAllActiveGroups(); //Get All Groups
        $this->data['groups'] = $groups;
        $this->render('manage_admin/job_listing_templates/index', 'admin_master');
    }

    public function add_edit($sid = NULL) {
        $redirect_url = 'manage_admin';
        $function_name = 'add_edit_job_listing_templates';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $action = $this->input->post('action');
        
        if ($action == 'save_job_listing_template') {
            $sid = $this->input->post('sid');
            $title = $this->input->post('title');
            $description = htmlentities($this->input->post('description'));
            $requirements = htmlentities($this->input->post('requirements'));
            $sortOrder = $this->input->post('sort_order');

            if ($sid == '') {
                $sid = null;
            }

            $this->form_validation->set_rules('title', 'Template Title', 'trim|required');

            if ($this->form_validation->run() === FALSE) {
                // show error - don't save
            } else {
                $this->Job_listing_templates_model->SaveTemplate($sid, $title, $description, $requirements, $sortOrder);
                redirect('manage_admin/job_templates');
            }
        } else if ($action == 'delete_job_listing_template') {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);

            if (in_array('full_access', $security_details) || in_array('fdeletetemplate', $security_details)) {
                $sid = $this->input->post('sid');
                $this->Job_listing_templates_model->SetArchiveStatusTemplate($sid, 'deleted');
                redirect('manage_admin/job_templates');
            }
        } else if ($action == 'switch_template_status') {
            $sid = $this->input->post('sid');
            $status = $this->input->post('status');

            if ($status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }

            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            
            if (in_array('full_access', $security_details) || in_array('fswitchstatus', $security_details)) {
                $this->Job_listing_templates_model->SetStatusTemplate($sid, $status);
                redirect('manage_admin/job_templates');
            }
        }

        if ($sid == NULL) {
            $this->data['page_title'] = 'Add New Job Listing Template';

            $template = array('sid' => '',
                'title' => '',
                'description' => '',
                'requirements' => '',
                'status' => 0,
                'archive_status' => '',
                'sort_order' => 0);

            $this->data['template'] = $template;
            $this->render('manage_admin/job_listing_templates/add_edit', 'admin_master');
        } else {
            $template = $this->Job_listing_templates_model->GetTemplate($sid);
            $this->data['template'] = $template[0];
            $this->data['page_title'] = 'Edit Job Listing Template';
            $this->render('manage_admin/job_listing_templates/add_edit', 'admin_master');
        }
    }

    public function add_edit_group($sid = NULL) {
        $redirect_url = 'manage_admin';
        $function_name = 'add_edit_group';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $action = $this->input->post('action');
        
        if ($action == 'save_job_listing_template_group') {
            $sid = $this->input->post('sid');
            
            if ($sid == '') {
                $this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[portal_job_listing_template_groups.name]');
                $this->form_validation->set_message('unique', 'Duplicate Group Name');
                $this->form_validation->set_rules('description', 'Description', 'trim|required');

                if ($this->form_validation->run() === FALSE) {
                    // show error - don't save
                } else {
                    $name = $this->input->post('name');
                    $description = $this->input->post('description');
                    $templates = $this->input->post('templates');
                    $this->Job_listing_templates_model->SaveGroup($sid, $name, $description, 1, $templates);
                    redirect('manage_admin/job_templates');
                }
            } else {
                $name = $this->input->post('name');
                $old_name = $this->input->post('old_name');
                $description = $this->input->post('description');
                $templates = $this->input->post('templates');

                if ($old_name != $name) {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required|is_unique[portal_job_listing_template_groups.name]');
                } else {
                    $this->form_validation->set_rules('name', 'Name', 'trim|required');
                }

                $this->form_validation->set_rules('description', 'Description', 'trim|required');
                if ($this->form_validation->run() === FALSE) {
                    // show error - don't save
                } else {
                    $this->Job_listing_templates_model->SaveGroup($sid, $name, $description, 1, $templates);
                    redirect('manage_admin/job_templates');
                }
            }
        } else if ($action == 'delete_job_listing_template_group') {
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);

            if (in_array('full_access', $security_details) || in_array('fdeletetemplategroup', $security_details)) {
                $sid = $this->input->post('sid');
                $this->Job_listing_templates_model->SetArchiveStatusGroup($sid, 'deleted');
                redirect('manage_admin/job_templates');
            }
        } else if ($action == 'switch_template_group_status') {
            $sid = $this->input->post('sid');
            $status = $this->input->post('status');

            if ($status == 1) {
                $status = 0;
            } else {
                $status = 1;
            }
            
            $admin_id = $this->session->userdata('user_id');
            $security_details = db_get_admin_access_level_details($admin_id);
            
            if (in_array('full_access', $security_details) || in_array('fswitchstatus', $security_details)) {
                $this->Job_listing_templates_model->SetStatusGroup($sid, $status);
                redirect('manage_admin/job_templates');
            }
        }

        if ($sid == NULL) {
            $this->data['page_title'] = 'Add New Job Listing Template Group';
            $group = array('sid' => '',
                'name' => '',
                'description' => '',
                'templates' => array(),
                'status' => 0,
                'archive_status' => 'active');

            $this->data['group'] = $group;
            $this->data['templatesArray'] = array();
        } else {
            $this->data['page_title'] = 'Edit Job Listing Template Group';
            $group = $this->Job_listing_templates_model->GetGroup($sid);
            $this->data['group'] = $group[0];
            $templatesArray = array();
            
            if ($group[0]['templates'] != '') {
                $templatesArray = unserialize($group[0]['templates']);
            }

            if ($templatesArray == null) {
                $templatesArray = array();
            }
            
            $this->data['templatesArray'] = $templatesArray;
        }

        $templates = $this->Job_listing_templates_model->GetAllActiveTemplates();
        $this->data['templates'] = $templates;
        $this->render('manage_admin/job_listing_templates/add_edit_group', 'admin_master');
    }
}