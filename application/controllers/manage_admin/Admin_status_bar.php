<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_status_bar extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->load->model('manage_admin/settings_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'admin_status_bar';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);
        // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Admin Status Bar';
         $data['application_status'] = $this->settings_model->get_admin_status();

        foreach ($data['application_status'] as $status) {
                $field = array(
                    'field' => $status['css_class'],
                    'label' => 'Status',
                    //'rules' => 'xss_clean|trim|required|alpha_numeric_spaces'
                );
                $order_field = array(
                    'field' => 'order_' . $status['css_class'],
                    'label' => 'Order',
                    'rules' => 'xss_clean|trim|required|numeric|min_length[1]|max_length[50]'
                );
                
                $config[] = $field;
                $config[] = $order_field;
            }

        $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
        $this->form_validation->set_rules($config);

        if ($this->form_validation->run() == FALSE) {
            
                $this->data['page_title'] = 'Admin Status Bar';

                $this->data['application_status'] = $this->settings_model->get_admin_status();
                $this->render('manage_admin/settings/admin_status_bar');
            } else {
                
                $custom_status_array = array();
                $formpost = $this->input->post(NULL, TRUE);
                
                $this->settings_model->update_admin_status_bar($formpost);
                $data['application_status'] = $this->settings_model->get_admin_status();
                $this->session->set_flashdata('message', '<strong>Success: </strong> Status updated!');
                redirect('manage_admin/admin_status_bar', 'refresh');
            }
    }

}
