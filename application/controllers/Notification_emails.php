<?php defined('BASEPATH') or exit('No direct script access allowed');

class Notification_emails extends Public_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notification_emails_model');
        $this->load->model('hr_documents_management_model');
        $this->load->library("pagination");
    }

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data["session"]["company_detail"]["sid"];
            $data['title']                                                      = "Notification Emails Management";
            $notifications_configuration                                        = $this->notification_emails_model->get_notifications_configuration_record($company_sid);

            $company_notifications_emails = $this->notification_emails_model->get_to_update_notification_emails($company_sid);
            $company_notifications_emails_unique = unique_multi_dimension_array($company_notifications_emails, 'email');

            foreach ($company_notifications_emails_unique as $email) {
                $this->db->select('email');
                $this->db->where('sid', $email['employer_sid']);
                $user_email = $this->db->get('users')->result_array();

                if (!empty($user_email)) {
                    $user_email = $user_email[0]['email'];

                    if ($user_email != $email['email']) {
                        $this->db->where('employer_sid', $email['employer_sid']);
                        $this->db->where('email', $email['email']);
                        $this->db->update('notifications_emails_management', array('email' => $user_email));
                    }
                }
            }

            if (empty($notifications_configuration)) {
                $this->notification_emails_model->insert_notifications_configuration_record($company_sid);
            }

            $this->load->view('main/header', $data);
            $this->load->view('notification_emails/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //    public function remove_contact_ajax() {
    //        if ($this->session->userdata('logged_in')) {
    //            $action = $this->input->post('action');
    //            $contact_sid = $this->input->post('sid');
    //
    //            if (isset($action) && $action == 'delete') {
    //                $this->notification_emails_model->delete_contact($contact_sid);
    //            }
    //        }
    //    }

    public function employment_application()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['company_sid'] = $company_sid;
            $data['title'] = "Full Employment Application Notifications";
            $notifications_type = 'employment_application';
            $data['notification_type'] = $notifications_type;
            $data['sub_title'] = "Employment Notification";
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action = $this->input->post('perform_action');
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_employment_application');
                    break;
                default:
                    break;
            }

            if ($this->form_validation->run() == FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'employment_application');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['employment_application'];
                $data['notifications_type'] = 'employment_application';
                $data['title_for_js_dialog'] = 'Employment Application Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/employment_application');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost                                               = $this->input->post(NULL, true);
                        $employee_data                                          = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data                                      = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];

                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New full employment application is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/employment_application', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status                                   = $this->input->post('notifications_status');
                        $company_sid                                            = $this->input->post('company_sid');
                        $data_to_update                                         = array();
                        $data_to_update['employment_application']               = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/employment_application', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid']                            = $company_sid;
                        $data_to_save['date_added']                             = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Full Employment Application is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/employment_application', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function expiration_manager()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['company_sid'] = $company_sid;
            $data['title'] = "Expiration Manager Notifications";
            $notifications_type = 'expiration_manager';
            $data['notification_type'] = $notifications_type;
            $data['sub_title'] = "Employment Notification";
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action = $this->input->post('perform_action');
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_expiration_manager');
                    break;
                default:
                    break;
            }

            if ($this->form_validation->run() == FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'expiration_manager');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['expiration_manager'];
                $data['notifications_type'] = 'expiration_manager';
                $data['title_for_js_dialog'] = 'Expiration Manager Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/expiration_manager');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost                                               = $this->input->post(NULL, true);
                        $employee_data                                          = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data                                      = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];

                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New expiration manager is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/expiration_manager', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status                                   = $this->input->post('notifications_status');
                        $company_sid                                            = $this->input->post('company_sid');
                        $data_to_update                                         = array();
                        $data_to_update['expiration_manager']               = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/expiration_manager', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid']                            = $company_sid;
                        $data_to_save['date_added']                             = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Expiration Manager is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/expiration_manager', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function billing_invoice_notifications()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_sid                                                       = $data['session']['employer_detail']['sid'];
            $company_sid                                                        = $data["session"]["company_detail"]["sid"];
            $data['company_sid']                                                = $company_sid;
            $data['title']                                                      = "Billing and Invoice Email Notifications";
            $data['helping_info']                                               = BILLING_AND_INVOICE;
            $notifications_type                                                 = 'billing_invoice';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = "Add New Billing & Invoice Notification";
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_billing_employee');
                    break;
                default:
                    break;
            }

            if ($this->form_validation->run() == FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'billing_invoice_notifications');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['billing_invoice_notifications'];
                $data['notifications_type'] = 'billing_invoice';
                $data['title_for_js_dialog'] = 'Billing and Invoice Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/notifications_email_view');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost                                               = $this->input->post(NULL, true);
                        $employee_data                                          = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data                                      = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];

                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Billing and Invoice email contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/billing_invoice_notifications', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status                                   = $this->input->post('notifications_status');
                        $company_sid                                            = $this->input->post('company_sid');
                        $data_to_update                                         = array();
                        $data_to_update['billing_invoice_notifications']        = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/billing_invoice_notifications', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid']                            = $company_sid;
                        $data_to_save['date_added']                             = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Billing and Invoice email contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/billing_invoice_notifications', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function new_applicant_notifications()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_id = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['company_sid'] = $company_sid;
            $notifications_type = 'new_applicant';
            $data['title'] = "New Applicant Email Notifications";
            $data['helping_info'] = NEW_APPLICANT;
            $data['notification_type'] = $notifications_type;
            $data['sub_title'] = "Add New Applicant Email Notification";
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);

            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_applicant_employee');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'new_applicant_notifications');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['new_applicant_notifications'];
                $data['notifications_type'] = 'new_applicant';
                $data['title_for_js_dialog'] = 'New Applicant Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/notifications_email_view');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array = array();
                        $insert_array['email'] = $employee_data['email'];
                        $insert_array['contact_name'] = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no'] = $employee_data['PhoneNumber'];
                        $insert_array['status'] = 'active';
                        $insert_array['date_added'] = date('Y-m-d H:i:s');
                        $insert_array['short_description'] = 'Company Employee';
                        $insert_array['notifications_type'] = $formpost['notifications_type'];
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['employer_sid'] = $employee_data['sid'];
                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/new_applicant_notifications', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status = $this->input->post('notifications_status');
                        $company_sid = $this->input->post('company_sid');
                        $data_to_update = array();
                        $data_to_update['new_applicant_notifications'] = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/new_applicant_notifications', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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

                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/new_applicant_notifications', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    // video interview notifications 
    public function video_interview_notifications()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_id = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['company_sid'] = $company_sid;
            $notifications_type = 'video_interview';
            $data['title'] = "New Video Interview System Notifications";
            $data['helping_info'] = VIDEO_INTERVIEW;
            $data['notification_type'] = $notifications_type;
            $data['sub_title'] = "Add Video Interview System Notification";
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_video_interview_employee');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'video_interview_notifications');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['video_interview_notifications'];
                $data['notifications_type'] = 'video_interview';
                $data['title_for_js_dialog'] = 'Video Interview Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/notifications_email_view');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array = array();
                        $insert_array['email'] = $employee_data['email'];
                        $insert_array['contact_name'] = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no'] = $employee_data['PhoneNumber'];
                        $insert_array['status'] = 'active';
                        $insert_array['date_added'] = date('Y-m-d H:i:s');
                        $insert_array['short_description'] = 'Company Employee';
                        $insert_array['notifications_type'] = $formpost['notifications_type'];
                        $insert_array['company_sid'] = $company_sid;
                        $insert_array['employer_sid'] = $employee_data['sid'];
                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/video_interview_notifications', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status = $this->input->post('notifications_status');
                        $company_sid = $this->input->post('company_sid');
                        $data_to_update = array();
                        $data_to_update['video_interview_notifications'] = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/video_interview_notifications', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');


                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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

                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/video_interview_notifications', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            $my_session = $this->session->userdata('logged_in');
            $company_id = $my_session['company_detail']['sid'];
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

    public function edit_contact($contact_sid, $type = '')
    {
        if ($this->session->userdata('logged_in')) {
            if (!isset($contact_sid) || $contact_sid == '' || $contact_sid == NULL || $contact_sid == 0) {
                $this->session->set_flashdata('message', 'Contact not found.');
                redirect("notification_emails");
            }

            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $company_sid  = $data['session']['company_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $data['title'] = "Edit Contact";
            $data['contact'] = $this->notification_emails_model->get_contact_details($contact_sid);
            $data['notification_type'] = $type;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
            $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
            $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');

            if ($data['contact']['employer_sid'] == 0) {
                $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
            }

            if ($this->form_validation->run() === FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/edit_contact');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                $update_data = array();

                foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
                    if ($key != 'status') { // remove status from save data as it is an DB Enum
                        $update_data[$key] = $value;
                    }
                }

                $status = $this->input->post('status');

                if (!empty($status) && intval($status) == 1) {
                    $status = 'active';
                } else {
                    $status = 'deactive';
                }

                $update_data['status'] = $status;
                //Check Form Post and handle status - end
                $this->notification_emails_model->update_contact($contact_sid, $update_data);
                //
                if ($type == "default_approvers" && $status == 'active') {
                    $this->add_default_approver_to_document($company_sid);
                }
                //
                $this->session->set_flashdata('message', 'Success: Contact Updated');

                if ($type == 'billing_invoice') {
                    redirect("notification_emails/billing_invoice_notifications");
                } else if ($type == 'video_interview') {
                    redirect("notification_emails/video_interview_notifications");
                } else if ($type == 'approval_management') {
                    redirect("notification_emails/approval_rights_notifications");
                } else if ($type == 'employment_application') {
                    redirect("notification_emails/employment_application");
                } else if ($type == 'expiration_manager') {
                    redirect("notification_emails/expiration_manager");
                } else if ($type == 'onboarding_request') {
                    redirect("notification_emails/onboarding_request");
                } else if ($type == 'offer_letter') {
                    redirect("notification_emails/offer_letter");
                } else if ($type == 'documents_status') {
                    redirect("notification_emails/documents");
                } else if ($type == 'general_information_status') {
                    redirect("notification_emails/general_information");
                } else if ($type == 'default_approvers') {
                    redirect("notification_emails/default_approvers");
                } else if ($type == 'course_status') {
                    redirect("notification_emails/courses");
                } else if ($type == 'document_report') {
                    redirect("notification_emails/document_report");
                } else if ($type == 'scheduled_documents_status') {
                    redirect("notification_emails/scheduleddocuments");
                } else {
                    redirect("notification_emails/new_applicant_notifications");
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function approval_rights_notifications()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'approval_management';
            $data['title']                                                      = 'Approval Management Email Notifications';
            $data['helping_info']                                               = APPROVAL_RIGHTS;
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add New Approval Email Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_approval_management');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'approval_rights_notifications');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['approval_rights_notifications'];
                $data['notifications_type']                                 = 'approval_management';
                $data['title_for_js_dialog']                                = 'New Approval Management Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/notifications_email_view');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/approval_rights_notifications', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['approval_rights_notifications']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/approval_rights_notifications', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/approval_rights_notifications', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function check_billing_employee($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'billing_invoice');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_billing_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/billing_invoice_notifications', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_video_interview_employee($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'video_interview');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            redirect('notification_emails/video_interview_notifications', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_applicant_employee($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'new_applicant');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/new_applicant_notifications', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_document_report($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'document_report');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/document_report', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_approval_management($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'approval_management');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/approval_rights_notifications', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_offer_letter($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'offer_letter');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/offer_letter', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_document_general($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'general_information_status');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/general_information', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_employee_profile_employee($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'employee_Profile');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/employee_profile', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_employee_default_document_approver($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'default_approvers');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/default_approvers', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_document_assignment($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'documents_status');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/documents', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_onboarding($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'onboarding_request');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/onboarding_request', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function check_employment_application($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'employment_application');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_billing_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/employment_application', "refresh");
            return false;
        } else {
            return true;
        }
    }

    //expiration_manager
    public function check_expiration_manager($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'expiration_manager');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_billing_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/expiration_manager', "refresh");
            return false;
        } else {
            return true;
        }
    }

    public function onboarding_request()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'onboarding_request';
            $data['title']                                                      = 'Onboarding Request Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Onboarding Request Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_onboarding');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'onboarding_request_notification');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['onboarding_request_notification'];
                $data['notifications_type']                                 = 'onboarding_request';
                $data['title_for_js_dialog']                                = 'New Onboarding Request Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/onboarding_request_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/onboarding_request', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['onboarding_request_notification']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/onboarding_request', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/onboarding_request', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function offer_letter()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'offer_letter';
            $data['title']                                                      = 'Offer Letter Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Offer Letter Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_offer_letter');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'offer_letter_notification');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['offer_letter_notification'];
                $data['notifications_type']                                 = 'offer_letter';
                $data['title_for_js_dialog']                                = 'New Offer Letter Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/offer_letter_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/offer_letter', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['offer_letter_notification']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/offer_letter', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/offer_letter', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function documents()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'documents_status';
            $data['title']                                                      = 'Document Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Document Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_document_assignment');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'documents_status');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['documents_status'];
                $data['notifications_type']                                 = 'documents_status';
                $data['title_for_js_dialog']                                = 'Document Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/document_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/documents', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['documents_status']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/documents', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/documents', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function general_information()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'general_information_status';
            $data['title']                                                      = 'General Information Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add General Informationt Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_document_general');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'general_information_status');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['general_information_status'];
                $data['notifications_type']                                 = 'general_information_status';
                $data['title_for_js_dialog']                                = 'General Information Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/general_information_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/general_information', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['general_information_status']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/general_information', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/general_information', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function employee_profile()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'employee_Profile';
            $data['title']                                                      = 'Employee Profile Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Employee profile Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_employee_profile_employee');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, $notifications_type);
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['employee_Profile'];

                // $data['notifications_type']                                 = 'general_information_status';
                $data['title_for_js_dialog']                                = 'Employee Profile Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/employee_profile_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/employee_profile', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['employee_profile']                 = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/employee_profile', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/employee_profile', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function default_approvers()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            //
            $notifications_type                                                 = 'default_approvers';
            $data['title']                                                      = 'Default Document Approvers Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Employee as Defallt Approver';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_employee_default_document_approver');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, $notifications_type);
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['default_approvers'];

                // $data['notifications_type']                                 = 'general_information_status';
                $data['title_for_js_dialog']                                = 'Default Document Approver Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/default_approver');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            //
                            $this->add_default_approver_to_document($company_sid);
                            //
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/default_approvers', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['default_approvers']                = $notifications_status;
                        //
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/default_approvers', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            //
                            $this->add_default_approver_to_document($company_sid);
                            //
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/default_approvers', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function add_default_approver_to_document($company_sid)
    {
        //
        $default_approver = $this->notification_emails_model->get_active_default_approver($company_sid);
        $approval_documents = $this->notification_emails_model->get_all_documents_without_approvers($company_sid);
        //
        if (!empty($approval_documents) && !empty($default_approver)) {
            //
            $approver_sid = 0;
            $approver_email = "";
            //
            if (is_numeric($default_approver["employer_sid"]) && $default_approver["employer_sid"] > 0) {
                $approver_sid = $default_approver["employer_sid"];
                //
                $this->hr_documents_management_model->change_document_approval_status(
                    $document_sid,
                    [
                        'document_approval_employees' => $approver_sid
                    ]
                );
            } else {
                $approver_email = $default_approver["email"];
            }
            //
            foreach ($approval_documents as $document) {
                if ($default_approver != 0) {
                    $this->hr_documents_management_model->insert_assigner_employee(
                        [
                            'portal_document_assign_sid' =>  $document['approval_flow_sid'],
                            'assigner_sid' => $approver_sid,
                            'approver_email' => $approver_email,
                            'assign_on' =>  date('Y-m-d H:i:s', strtotime('now')),
                            'assigner_turn' => 1,
                        ]
                    );
                    //
                    // Send Email to first approver of this document
                    $this->SendEmailToCurrentApprover($document['sid']);
                }
            }
        }
        //
    }

    function SendEmailToCurrentApprover($document_sid)
    {
        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
        //
        $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
        //
        $approver_info = array();
        $current_approver_reference = '';
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            //
            $default_approver = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
            //
            $approver_name = explode(" ", $default_approver["contact_name"]);
            //
            $approver_info['first_name'] = isset($approver_name[0]) ? $approver_name[0] : "";
            $approver_info['last_name'] = isset($approver_name[1]) ? $approver_name[1] : "";
            $approver_info['email'] = $default_approver["email"];
            //
            $current_approver_reference = $default_approver["email"];
        } else {
            //
            $approver_info = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $current_approver_info["assigner_sid"]);
            //
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }

        //
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        //
        // Get the initiator name
        $document_initiator_name = getUserNameBySID($approvers_flow_info["assigned_by"]);
        //
        // Get the company name
        $company_name = getCompanyNameBySid($document_info['company_sid']);
        //
        // Get assigned document user name
        if ($document_info['user_type'] == 'employee') {
            //
            $t = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        }
        //
        $hf = message_header_footer_domain($document_info['company_sid'], $company_name);
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $accept_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'accept')
        );
        //
        $reject_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'reject')
        );
        //
        $view_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'view')
        );
        //
        $approval_public_link_accept = base_url("hr_documents_management/public_approval_document") . '/' . $accept_code;
        $approval_public_link_reject = base_url("hr_documents_management/public_approval_document") . '/' . $reject_code;
        $approval_public_link_view = base_url("hr_documents_management/public_approval_document") . '/' . $view_code;
        // 
        $replacement_array['initiator']             = $document_initiator_name;
        $replacement_array['contact-name']          = $document_assigned_user_name;
        $replacement_array['company_name']          = ucwords($company_name);
        $replacement_array['username']              = $replacement_array['contact-name'];
        $replacement_array['firstname']             = $approver_info['first_name'];
        $replacement_array['lastname']              = $approver_info['last_name'];
        $replacement_array['first_name']            = $approver_info['first_name'];
        $replacement_array['last_name']             = $approver_info['last_name'];
        $replacement_array['document_title']        = $document_info['document_title'];
        $replacement_array['user_type']             = $document_info['user_type'];
        $replacement_array['note']                  = $approvers_flow_info["assigner_note"];
        $replacement_array['baseurl']               = base_url();
        $replacement_array['accept_link']           = $approval_public_link_accept;
        $replacement_array['reject_link']           = $approval_public_link_reject;
        $replacement_array['view_link']             = $approval_public_link_view;
        //
        // Send email notification to approver with a private link
        log_and_send_templated_email(HR_DOCUMENTS_APPROVAL_FLOW, $approver_info['email'], $replacement_array, $hf, 1);
    }


    public function private_message_notification()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            //
            $notifications_type                                                 = 'private_message';
            $data['title']                                                      = 'Private Messages Notifications';
            $data['notification_type']                                          = $notifications_type;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, $notifications_type);
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['private_message'];
                $data['title_for_js_dialog']                                = 'Private Messages Notifications';

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/private_message_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {

                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['private_message']                  = $notifications_status;
                        //
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/private_message_notification', 'refresh');
                    default:
                        rredirect('notification_emails/private_message_notification', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function courses()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'course_status';
            $data['title']                                                      = 'Course Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Course Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'course_status':
                    $this->form_validation->set_rules('course_status', 'Course Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_course');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'course_status');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['course_status'];
                $data['notifications_type']                                 = 'course_status';
                $data['title_for_js_dialog']                                = 'Course Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/course_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/courses', 'refresh');
                        break;
                    case 'course_status':
                        $notifications_status                               = $this->input->post('course_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['course_status']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/courses', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/courses', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    public function document_report()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');

            $employer_sid = $data['session']['employer_detail']['sid'];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $data['company_sid'] = $company_sid;
            $data['title'] = "Document Report Notifications";
            $notifications_type = 'document_report';
            $data['notification_type'] = $notifications_type;
            $data['sub_title'] = "Document Report Notification";
            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action = $this->input->post('perform_action');
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_document_report');
                    break;
                default:
                    break;
            }

            if ($this->form_validation->run() == FALSE) {
                $notifications_emails = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails'] = $notifications_emails;
                $notifications_status = $this->notification_emails_model->get_notifications_status($company_sid, 'document_report');
                $data['notifications_status'] = $notifications_status;
                $data['current_notification_status'] = $notifications_status['document_report'];
                $data['notifications_type'] = 'document_report';
                $data['title_for_js_dialog'] = 'Document Report Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/document_report');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost                                               = $this->input->post(NULL, true);
                        $employee_data                                          = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data                                      = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];

                        $result = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New document report is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/document_report', "refresh");
                        break;
                    case 'set_notifications_status':
                        $notifications_status                                   = $this->input->post('notifications_status');
                        $company_sid                                            = $this->input->post('company_sid');
                        $data_to_update                                         = array();
                        $data_to_update['document_report']               = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/document_report', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid']                            = $company_sid;
                        $data_to_save['date_added']                             = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Document Report is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }
                        redirect('notification_emails/document_report', "refresh");
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    public function check_course($employee_sid)
    {
        $data['session'] = $this->session->userdata('logged_in');
        $company_sid = $data["session"]["company_detail"]["sid"];
        $result = $this->notification_emails_model->check_employee_exists($employee_sid, $company_sid, 'course_status');

        if ($result == true) {
            $this->session->set_flashdata('message', 'Error: Employee already exists');
            //$this->form_validation->set_message('check_applicant_employee', 'Please enter a unique Employee email');
            redirect('notification_emails/courses', "refresh");
            return false;
        } else {
            return true;
        }
    }


    public function scheduleddocuments()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            $company_sid                                                        = $data['session']['company_detail']['sid'];
            $data['company_sid']                                                = $company_sid;
            $notifications_type                                                 = 'scheduled_documents_status';
            $data['title']                                                      = 'Scheduled Document Notifications';
            $data['notification_type']                                          = $notifications_type;
            $data['sub_title']                                                  = 'Add Document Notification';
            $employees = $this->notification_emails_model->get_all_employeesNew($company_sid);
            //
            foreach ($employees as $e_key => $employee) {
                $employee_name = ucwords($employee['first_name'] . ' ' . $employee['last_name']) . ($employee['job_title'] != '' && $employee['job_title'] != null ? ' (' . $employee['job_title'] . ')' : '') . ' [' . (remakeAccessLevel($employee)) . ']';
                $employees[$e_key]['employee_name'] = $employee_name . ' [' . $employee['email'] . ']';
            }
            //
            $data['employees'] = $employees;

            $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
            $perform_action                                                     = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'set_notifications_status':
                    $this->form_validation->set_rules('notifications_status', 'Notifications Status', 'required|trim|xss_clean');
                    break;
                case 'add_notification_email':
                    $this->form_validation->set_rules('contact_name', 'Contact name', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('contact_no', 'Contact Number', 'trim|xss_clean');
                    $this->form_validation->set_rules('short_description', 'Short Description', 'trim|xss_clean');
                    $this->form_validation->set_rules('email', 'Email Address', 'trim|xss_clean|required');
                    $this->form_validation->set_rules('notifications_type', 'notifications type', 'trim|xss_clean');
                    break;
                case 'add_notification_employee':
                    $this->form_validation->set_rules('employee', 'Employee Email', 'trim|xss_clean|required|callback_check_document_assignment');
                default:
                    break;
            }

            if ($this->form_validation->run() === FALSE) {
                $notifications_emails                                       = $this->notification_emails_model->get_notification_emails($company_sid, $notifications_type);
                $data['notifications_emails']                               = $notifications_emails;
                $notifications_status                                       = $this->notification_emails_model->get_notifications_status($company_sid, 'scheduled_documents_status');
                $data['notifications_status']                               = $notifications_status;
                $data['current_notification_status']                        = $notifications_status['scheduled_documents_status'];
                $data['notifications_type']                                 = 'scheduled_documents_status';
                $data['title_for_js_dialog']                                = 'Document Notifications';

                if ($perform_action == 'add_notification_employee') {
                    $data['emp_id'] = $this->input->post('employee');
                    $data['duplicate_employee'] = true;
                }

                $data['document_schedule'] = $this->notification_emails_model->getscheduleddocuments($company_sid);

                $this->load->view('main/header', $data);
                $this->load->view('notification_emails/scheduled_document_notification');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'add_notification_employee':
                        $formpost = $this->input->post(NULL, true);
                        $employee_data = $this->notification_emails_model->get_employee_data($formpost['employee']);

                        if (isset($employee_data[0])) {
                            $employee_data = $employee_data[0];
                        }

                        $insert_array                                           = array();
                        $insert_array['email']                                  = $employee_data['email'];
                        $insert_array['contact_name']                           = $employee_data['first_name'] . ' ' . $employee_data['last_name'];
                        $insert_array['contact_no']                             = $employee_data['PhoneNumber'];
                        $insert_array['status']                                 = 'active';
                        $insert_array['date_added']                             = date('Y-m-d H:i:s');
                        $insert_array['short_description']                      = 'Company Employee';
                        $insert_array['notifications_type']                     = $formpost['notifications_type'];
                        $insert_array['company_sid']                            = $company_sid;
                        $insert_array['employer_sid']                           = $employee_data['sid'];
                        $result                                                 = $this->notification_emails_model->save_notification_email($insert_array);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/scheduleddocuments', 'refresh');
                        break;
                    case 'set_notifications_status':
                        $notifications_status                               = $this->input->post('notifications_status');
                        $company_sid                                        = $this->input->post('company_sid');
                        $data_to_update                                     = array();
                        $data_to_update['scheduled_documents_status']    = $notifications_status;
                        $this->notification_emails_model->update_notifications_configuration_record($company_sid, $data_to_update);
                        $this->session->set_flashdata('message', '<strong>Success: </strong>Notifications Status successfully updated!');
                        redirect('notification_emails/scheduleddocuments', 'refresh');
                        break;
                    default:
                        $formpost = $this->input->post(NULL, TRUE);
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['date_added'] = date('Y-m-d H:i:s');

                        foreach ($formpost as $key => $value) { //Check Form Post and handle status - start
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
                        $result = $this->notification_emails_model->save_notification_email($data_to_save);

                        if ($result == 'success') {
                            $this->session->set_flashdata('message', 'Success: New Contact is added!');
                        } else {
                            $this->session->set_flashdata('error', 'Error: There was some error! Please try again.');
                        }

                        redirect('notification_emails/scheduleddocuments', 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }





    public function setscheduleddocuments()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            check_access_permissions($security_details, 'my_settings', 'notification_emails');
            //

            $company_sid = $data["session"]["company_detail"]["sid"];

            $formpost = $this->input->post(NULL, TRUE);

            //_e($formpost, true, true);
            $insertData = [];
            $insertData['schedule_type'] = $formpost['assignAndSendDocument'];
            $insertData['schedule_date'] = $formpost['assignAndSendCustomDate'];
            $insertData['schedule_day'] = $formpost['assignAndSendCustomDay'];
            $insertData['schedule_time'] = $formpost['assignAndSendCustomTime'];
            $insertData['company_sid'] = $company_sid;

            $this->notification_emails_model->setscheduleddocuments($company_sid, $insertData);
            $this->session->set_flashdata('message', 'Success: Schedule Updated!');

            redirect('notification_emails/scheduleddocuments', 'refresh');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
