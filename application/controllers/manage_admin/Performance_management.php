<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Performance_management extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('performance_management_model', 'pmm');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'index';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //
        //View data working starts
        $this->data['page_title'] = 'Performance Management Templates';
        //
        $this->data['data'] = $this->pmm->GetCompanyTemplates();
        //
        $this->render('manage_admin/performance_management/templates_listing', 'admin_master');
    }

    public function edit_performance_template ($template_id) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'index';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //
        //View data working starts
        $this->data['page_title'] = 'Edit Performance Templates';
        //
        $template = $this->pmm->GetCompanyTemplatesById($template_id);
        $this->data['template'] = $template;
        //
        $questions = json_decode($template[0]['questions'], true);
        //
        $this->data['questions'] = $questions;
        //
        if ($_POST) {
            $action = $_POST['action'];
            //
            if ($action == 'Save') {
                foreach ($questions as $qkey => $question) {
                    $questions[$qkey]['title'] = $_POST['Question'.($qkey+1)];
                    $questions[$qkey]['description'] = $_POST['Description'.($qkey+1)];
                }

                $question_string = json_encode($questions);

                $data_to_update = array();
                $data_to_update['questions'] = $question_string;

                $this->pmm->updateCompanyTemplatesById($template_id, $data_to_update);

                $this->session->set_flashdata('message', '<strong>Success: </strong>The performance template update successfully!');

                redirect('manage_admin/performance_management', 'refresh');
            } 
        }
        //
        $template = $this->pmm->GetCompanyTemplatesById($template_id);
        //
        $this->data['questions'] = json_decode($template[0]['questions'], true);
        //
        $this->render('manage_admin/performance_management/edit_templates_question', 'admin_master');
    }

}
