<?php defined('BASEPATH') OR exit('No direct script access allowed');

class System_notification_emails extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/system_notification_emails_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'system_notification_emails';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

        if($this->form_validation->run() == false){
            $this->data['page_title'] = 'Manage System Notification Emails';
            $this->data['data'] = $this->system_notification_emails_model->get_all_system_notification_emails();
            $this->render('manage_admin/system_notification_emails/listings_view', 'admin_master');
        } else {
            $perform_action = $this->input->post('perform_action');
            $sid = $this->input->post('sid');
             switch($perform_action){
                 case 'activate_email':
                     $this->system_notification_emails_model->update_status($sid, 1);
                     $this->session->set_flashdata('message', '<strong>Success :</strong> Notifications Activated!');
                     redirect('manage_admin/system_notification_emails', 'refresh');
                     break;
                 case 'deactivate_email':
                     $this->system_notification_emails_model->update_status($sid, 0);
                     $this->session->set_flashdata('message', '<strong>Success :</strong> Notifications Dectivated!');
                     redirect('manage_admin/system_notification_emails', 'refresh');
                     break;
                 case 'delete_email':
                     $this->system_notification_emails_model->delete_email_record($sid);
                     $this->session->set_flashdata('message', '<strong>Success :</strong> Notification Email Record Deleted!');
                     redirect('manage_admin/system_notification_emails', 'refresh');
                     break;
             }
        }       
    }

    public function add_new_system_notification_email() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin/system_notification_emails';
        $function_name      = 'add_new_system_notification_email';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        if ($this->form_validation->run() === FALSE) {
            $this->data['page_title'] = 'Add New System Notification Email';
            $this->render('manage_admin/system_notification_emails/add_new_system_notification_email');
        } else {
            $admin = $this->ion_auth->user()->row();
            $full_name = $this->input->post('full_name');
            $email = $this->input->post('email');
            $data_to_insert = array();
            $data_to_insert['full_name'] = $full_name;
            $data_to_insert['email'] = $email;
            $data_to_insert['added_by_sid'] = $admin_id;
            $data_to_insert['added_by_name'] = $admin->first_name . ' ' . $admin->last_name;
            $data_to_insert['date_added'] = date('Y-m-d H:i:s');
            $data_to_insert['status'] = 1;
            $this->system_notification_emails_model->insert_system_notification_emails_record($data_to_insert);
            $this->session->set_flashdata('message', '<strong>Success :</strong> Notification Email Added!');
            redirect('manage_admin/system_notification_emails', 'refresh');
        }
    }

    public function set_system_notification_email_configuration($sid = 0){
        $this->form_validation->set_rules('sid', 'sid', 'required|xss_clean|trim');
        $this->form_validation->set_rules('has_access_to[]', 'system_notification_emails[]', 'required|xss_clean|trim');

        if($this->form_validation->run() == false){

            if($sid > 0) {
                $system_notification_email = $this->system_notification_emails_model->get_system_notification_email($sid);
                $this->data['system_notification_email'] = $system_notification_email;

                $system_notification_email_config = $this->system_notification_emails_model->get_system_notification_email_config($sid);
                $this->data['system_notification_email_config'] = $system_notification_email_config;


                $this->data['page_title'] = 'Set System Notifications Email Configuration';
                $this->render('manage_admin/system_notification_emails/set_system_notification_email_configuration');

            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> Email Record Not Found!');
            }
        } else {
            $system_notification_email_sid = $this->input->post('sid');
            $has_access_to = $this->input->post('has_access_to');

            $this->system_notification_emails_model->insert_system_notification_email_config($system_notification_email_sid, $has_access_to);

            $this->session->set_flashdata('message', '<strong>Success :</strong> Configuration Updated!');

            redirect('manage_admin/system_notification_emails', 'refresh');
        }
    }
}