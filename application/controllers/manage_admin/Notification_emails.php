<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notification_emails extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/notification_emails_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($company_sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'edit_company';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($company_sid == '' || $company_sid == NULL || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found.');
            redirect("manage_admin/companies");
        }

        $notifications_configuration = $this->notification_emails_model->get_notifications_configuration_record($company_sid);

        if (empty($notifications_configuration)) {
            $this->notification_emails_model->insert_notifications_configuration_record($company_sid);
        }

        $this->data['page_title'] = 'Notification Emails Management';
        $this->data['company_sid'] = $company_sid;
        $this->render('manage_admin/notification_emails/index');
    }

    public function billing_invoice_notifications($company_sid)
    {
        if ($company_sid == '' || $company_sid == NULL || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found.');
            redirect("manage_admin/companies");
        }

        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');

        if($this->form_validation->run() == false){
            //do nothing
        } else {
            $perform_action = $this->input->post('perform_action');

            switch($perform_action){
                case 'set_notifications_status':
                    $company_sid = $this->input->post('company_sid');
                    $notifications_status = $this->input->post('notifications_status');

                    $data_to_update = array();
                    $data_to_update['billing_invoice_notifications'] = $notifications_status;
                    $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);


                    $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                    redirect('manage_admin/notification_emails/billing_invoice_notifications/' . $company_sid , 'refresh');
                    break;
            }
        }


        $notifications_status = $this->notification_emails_model->get_notifications_configuration_record($company_sid);

        if(!empty($notifications_status)){
            $current_notification_status = $notifications_status['billing_invoice_notifications'];
            $this->data['current_notification_status'] = $current_notification_status;
        } else {
            $this->data['current_notification_status'] = 0;
        }

        $this->data['title_for_js_dialog'] = 'Billing and Invoice Notifications';


        $billing_emails = $this->notification_emails_model->get_notification_emails($company_sid, 'billing_invoice');
        $this->data['emails'] = $billing_emails->result_array();
        $this->data['emails_count'] = $billing_emails->num_rows();
        $company_details = get_company_details($company_sid);

        if (isset($company_details['CompanyName'])) {
            $this->data['company_name'] = $company_details['CompanyName'];
        }

        $this->data['page_title'] = 'Billing and Invoice Emails Management';
        $this->data['company_sid'] = $company_sid;
        $this->data['notification_type'] = 'billing_invoice';
        $this->render('manage_admin/notification_emails/emails_list_view');
    }

    public function new_applicant_notifications($company_sid)
    {
        if ($company_sid == '' || $company_sid == NULL || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found.');
            redirect("manage_admin/companies");
        }


        $this->form_validation->set_rules('perform_action', 'Perform Action', 'required|trim|xss_clean');

        if($this->form_validation->run() == false){
            //do nothing
        } else {
            $perform_action = $this->input->post('perform_action');

            switch($perform_action){
                case 'set_notifications_status':
                    $company_sid = $this->input->post('company_sid');
                    $notifications_status = $this->input->post('notifications_status');

                    $data_to_update = array();
                    $data_to_update['new_applicant_notifications'] = $notifications_status;
                    $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);


                    $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                    redirect('manage_admin/notification_emails/new_applicant_notifications/' . $company_sid , 'refresh');
                    break;
            }
        }


        $applicant_emails = $this->notification_emails_model->get_notification_emails($company_sid, 'new_applicant');
        $this->data['emails'] = $applicant_emails->result_array();
        $this->data['emails_count'] = $applicant_emails->num_rows();
        $company_details = get_company_details($company_sid);

        if (isset($company_details['CompanyName'])) {
            $this->data['company_name'] = $company_details['CompanyName'];
        }

        $notifications_status = $this->notification_emails_model->get_notifications_configuration_record($company_sid);

        if(!empty($notifications_status)){
            $current_notification_status = $notifications_status['new_applicant_notifications'];
            $this->data['current_notification_status'] = $current_notification_status;
        } else {
            $this->data['current_notification_status'] = 0;
        }

        $this->data['title_for_js_dialog'] = 'New Applicant Notifications';

        $this->data['page_title'] = 'New Applicant Emails Management';
        $this->data['company_sid'] = $company_sid;
        $this->data['notification_type'] = 'new_applicant';
        $this->render('manage_admin/notification_emails/emails_list_view');
    }

    public function add_contact($type, $company_sid)
    {
        if ($company_sid == '' || $company_sid == NULL || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found.');
            redirect("manage_admin/companies");
        }

        $this->data['page_title'] = 'Add New Email Contact';
        $this->data['notification_type'] = $type;
        $this->data['company_sid'] = $company_sid;
        $company_details = get_company_details($company_sid);

        if (isset($company_details['CompanyName'])) {
            $this->data['company_name'] = $company_details['CompanyName'];
        }

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
        $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/notification_emails/add_contact');
        } else {
            $formpost = $this->input->post(NULL, TRUE);

            //Check Form Post and handle status - start
            foreach ($formpost as $key => $value) {
                if ($key != 'status') { // remove status from save data as it is an DB Enum
                    $data_to_save[$key] = $value;
                }
            }

            $status = $this->input->post('status');

            if (!empty($status) && intval($status) == 1) {
                $status = 'active';
            } else {
                $status = 'deactive';
            }

            $data_to_save['status'] = $status;
            //Check Form Post and handle status - end

            $data_to_save['company_sid'] = $company_sid;
            $data_to_save['date_added'] = date('Y-m-d H:i:s');

            $data_to_save['notifications_type'] = $type;

            $result = $this->notification_emails_model->save_notification_email($data_to_save);

            if ($result == 'success') {
                $this->session->set_flashdata('message', 'Success: New Contact is added!');
            } else {
                $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
            }

            if ($type == 'billing_invoice') {
                redirect("manage_admin/notification_emails/billing_invoice_notifications" . '/' . $company_sid);
            } else {
                redirect("manage_admin/notification_emails/new_applicant_notifications" . '/' . $company_sid);
            }
        }
    }

    public function edit_contact($contact_sid, $type, $company_sid)
    {
        if ($company_sid == '' || $company_sid == NULL || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found.');
            redirect("manage_admin/companies");
        }

        if ($contact_sid == '' || $contact_sid == NULL || $contact_sid == 0) {
            $this->session->set_flashdata('message', 'Contact not found.');
            redirect("manage_admin/notification_emails");
        }

        $this->data['page_title'] = 'Edit Contact';
        $this->data['contact'] = $this->notification_emails_model->get_contact_details($contact_sid);
        $this->data['notification_type'] = $type;
        $this->data['company_sid'] = $company_sid;
        $company_details = get_company_details($company_sid);

        if (isset($company_details['CompanyName'])) {
            $this->data['company_name'] = $company_details['CompanyName'];
        }

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
        $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');

        if ($this->form_validation->run() === FALSE) {
            $this->render('manage_admin/notification_emails/edit_contact');
        } else {
            $update_data = $this->input->post(NULL, TRUE);

            //Check Form Post and handle status - start
            foreach ($update_data as $key => $value) {
                if ($key != 'status') { // remove status from save data as it is an DB Enum
                    $data_to_update[$key] = $value;
                }
            }

            $status = $this->input->post('status');

            if (!empty($status) && intval($status) == 1) {
                $status = 'active';
            } else {
                $status = 'deactive';
            }

            $data_to_update['status'] = $status;
            //Check Form Post and handle status - end


            $this->notification_emails_model->update_contact($contact_sid, $data_to_update);
            $this->session->set_flashdata('message', 'Success: Contact Updated');

            if ($type == 'billing_invoice') {
                redirect("manage_admin/notification_emails/billing_invoice_notifications" . '/' . $company_sid);
            } else {
                redirect("manage_admin/notification_emails/new_applicant_notifications" . '/' . $company_sid);
            }
        }
    }

//    public function remove_contact_ajax() {
//        $admin_id = $this->session->userdata('user_id');
//        
//        if (isset($admin_id) && $admin_id != 0 && $admin_id != NULL) {
//            $action = $this->input->post('action');
//            $contact_sid = $this->input->post('sid');
//            
//            if (isset($action) && $action == 'delete') {
//                $this->notification_emails_model->delete_contact($contact_sid);
//            }
//        }
//    }

    public function ajax_responder()
    {
        $company_id = $_POST['company_sid'];
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = strtolower($_POST['perform_action']);
            switch ($perform_action) {
                case 'checkuniqueemail':
                    $email = $_POST['email'];
                    $notifications_type = $_POST['notifications_type'];
                    $result = $this->notification_emails_model->check_unique_email($company_id, $email, $notifications_type);
                    echo $result;
                    break;
                case 'delete_contact':
                    $contact_sid = $_POST['sid'];
                    $this->notification_emails_model->delete_contact($contact_sid);
                    break;
                default:
                    echo 'do_nothing';
                    break;
            }
        }
    }

}
