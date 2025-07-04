<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Security_settings extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/security_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'security_settings';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Security Settings [Employee Access Level]';
        //$security_types                                                       = $this->security_model->get_enum_values();
        $security_types = $this->security_model->get_all_security_levels();
        $this->data['security_types'] = $security_types;
        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/security/listing_view');
        } else {
            $this->render('manage_admin/security/listing_view');
        }
    }

    public function manage_permissions($sid = NULL)
    {
        if (empty($sid)) {
            $this->session->set_flashdata('message', 'Security access level not found!');
            redirect('manage_admin/security_settings', 'refresh');
        } else {
            $access_level_details = $this->security_model->security_levels_details($sid);
            $available_modules = $this->security_model->security_levels_available_modules()['available_modules'];
            $available_modules = unserialize($available_modules);
//echo '<pre>'; print_r($available_modules); exit;
            if (empty($access_level_details)) {
                $this->session->set_flashdata('message', 'Security access level not found!');
                redirect('manage_admin/security_settings', 'refresh');
            } else {
                if (isset($_POST['action']) && $_POST['action'] == 'true') {
                    $formdata = $this->input->post('action');
                    $function_name = '';
                    $description = $_POST['description'];

                    if (isset($_POST['function_name'])) {
                        $function_name = $_POST['function_name'];
                    }

                    $update_permission = array();

                    if (!empty($function_name)) { // update permissions for the user
                        foreach ($function_name as $mykey => $fn_name) {
                            $key = array_search($fn_name, array_column($available_modules, 'function_name'));
                            $mykey++;
                            $update_permission[] = $available_modules[$key];
                        }
                    }

                    $update_permission = serialize($update_permission);
                    $this->security_model->update_permission($sid, $update_permission, $description);
                    $access_level_details = $this->security_model->security_levels_details($sid);
                    redirect('manage_admin/security_settings', 'refresh');
                }

                $access_level_name = $access_level_details['access_level'];
                $permissions = array();
                $access_level_permissions = $access_level_details['permissions'];
                if (!empty($access_level_permissions)) {
                    $permissions = unserialize($access_level_permissions);
                }
                $this->data['page_title'] = 'Security Settings For ' . $access_level_name;
                $this->data['permissions'] = $permissions;
                $this->data['modules'] = $available_modules;
                $this->data['description'] = $access_level_details['description'];
                $this->data['access_level'] = $access_level_details['access_level'];
                $this->render('manage_admin/security/manage_permissions');
            }
        }
    }

    public function manage_members($access_level_sid = null){

        if($access_level_sid != null  &&  $access_level_sid > 1) {
            // ** Check Security Permissions Checks - Start ** //
            $redirect_url = 'manage_admin';
            $function_name = 'security_settings';

            $admin_id = $this->ion_auth->user()->row()->id;
            $security_details = db_get_admin_access_level_details($admin_id);
            $this->data['security_details'] = $security_details;
            //check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
            // ** Check Security Permissions Checks - End ** //

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');


            if ($this->form_validation->run() == false) {
                $access_level_details = $this->security_model->security_levels_details($access_level_sid);

                if(!empty($access_level_details)) {
                    $this->data['access_level_details'] = $access_level_details;

                    $access_level = $access_level_details['access_level'];

                    $members = $this->security_model->get_access_level_specific_users($access_level);
                    $this->data['members'] = $members;


                    $this->data['access_level'] = $access_level;

                    $access_levels = $this->security_model->get_all_security_levels();
                    $this->data['access_levels'] = $access_levels;

                    $this->data['page_title'] = 'Security Settings - Manage Members';
                    $this->render('manage_admin/security/manage_members');
                } else {

                }
            } else {
                $perform_action = $this->input->post('perform_action');

                switch($perform_action){
                    case 'update_access_levels':

                        $current_access_level = $this->input->post('current_access_level');

                        $form_posts = $this->input->post();

                        unset($form_posts['perform_action']);
                        unset($form_posts['current_access_level']);
                        unset($form_posts['current_access_level_sid']);

                        foreach($form_posts as $key => $access_level){
                            $key_parts = explode('_', $key);

                            $company_sid = $key_parts[2];
                            $user_sid = $key_parts[3];

                            $this->security_model->set_access_level($company_sid, $user_sid, $access_level);
                        }

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Updated!');
                        redirect('manage_admin/security_settings/manage_members/' . $current_access_level, 'refresh');

                        break;

                    case 'activate_security_access_level':

                        $access_level_sid = $this->input->post('access_level_sid');

                        $this->security_model->set_access_level_status($access_level_sid, 1);

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Status Updated!');

                        redirect('manage_admin/security_settings/manage_members/' . $access_level_sid, 'refresh');

                        break;

                    case 'deactivate_security_access_level':

                        $access_level_sid = $this->input->post('access_level_sid');

                        $this->security_model->set_access_level_status($access_level_sid, 0);

                        $this->session->set_flashdata('message', '<strong>Success: </strong> Security Access Levels Status Updated!');

                        redirect('manage_admin/security_settings/manage_members/' . $access_level_sid, 'refresh');

                        break;
                }


            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> No Such Access Level Defined in system!');
            redirect('manage_admin/security_settings', 'refresh');
        }
    }
}

