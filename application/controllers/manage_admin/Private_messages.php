<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Private_messages extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/message_model');
        $this->load->model('manage_admin/company_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'private_messages';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Private Messages Inbox';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //$admin_id = $this->ion_auth->user()->row()->id;

        $db_data = $this->message_model->get_admin_messages(1);
        $this->data['messages'] = $db_data->result_array();
        //unread
        $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
        //total
        $this->data['total'] = $this->message_model->get_messages_total_inbox(1);

        $this->data['page'] = 'inbox';
        $this->render('manage_admin/private_messages/listing_view');
    }

    public function inbox_message_detail($edit_id = NULL)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'private_messages';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($edit_id == NULL) {
            redirect('manage_admin/private_messages', 'refresh');
        } else {
            //$admin_id = $this->ion_auth->user()->row()->id;
            $this->data['page_title'] = 'Private Message Details';
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->data['page'] = 'inbox';
            $message_data = $this->message_model->get_inbox_message_detail($edit_id);
            $this->data['message'] = $message_data[0];
            //mark messsage as read
            $this->message_model->mark_read($edit_id);
            $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
            $this->render('manage_admin/private_messages/message_detail');
        }
    }

    public function outbox()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'private_messages_outbox';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Private Messages Outbox';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //$admin_id = $this->ion_auth->user()->row()->id;

        $manual_outbox = $this->message_model->fetch_manual_email_outbox();
        $db_data = $this->message_model->get_admin_outbox_messages(1);
        $this->data['messages'] = $db_data->result_array();
        $this->data['manual_outbox'] = $manual_outbox;
        $this->data['total'] = $this->message_model->get_messages_total_outbox(1);
        $this->data['page'] = 'outbox';
        $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
        $this->render('manage_admin/private_messages/listing_view');
    }

    public function outbox_message_detail($edit_id = NULL, $anonym = 0)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'private_messages';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($edit_id == NULL) {
            redirect('manage_admin/private_messages', 'refresh');
        } else {
            //$admin_id = $this->ion_auth->user()->row()->id;
            $this->data['page_title'] = 'Outbox Message Details';
            $this->data['groups'] = $this->ion_auth->groups()->result();
            $this->data['page'] = 'outbox';
            $message_data = $this->message_model->get_outbox_message_detail($edit_id,$anonym);
            $this->data['message'] = $message_data[0];
            $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
            $this->render('manage_admin/private_messages/message_detail');
        }
    }

    public function compose_message()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'compose_private_message';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Compose Private Messages';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //$admin_id                                                               = $this->ion_auth->user()->row()->id;

        $this->load->library('form_validation');
//        $this->form_validation->set_rules('to_id', 'Employer', 'trim|required');
        $this->form_validation->set_rules('from_email', 'From Email', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            $this->data['page'] = 'compose';
            $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
            // Added on: 29-04-2019
            $this->data['admin_templates'] = $this->fetch_admin_templates();
            //getting all employer
            $this->data['employers'] = $this->company_model->get_employers();
            $this->render('manage_admin/private_messages/compose_message');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            foreach ($formpost as $key => $value) {
                if ($key != 'availability_to' && $key != 'availability_from' && $key != 'active' && $key != 'from_email' && $key != 'receiver' && $key != 'custom_email') { // exclude these values from array
                    if (is_array($value)) {
                        $value = implode(',', $value);
                    }
                    $message_data[$key] = $value;
                }
            }
            if($formpost['receiver'] == 'to_email'){
                $message_data['to_id'] = $formpost['custom_email'];
            }
            $message_data['from_id'] = '1';
            $message_data['from_type'] = 'admin';
            $message_data['to_type'] = 'employer';
            $message_data['date'] = date('Y-m-d H:i:s');
//            echo '<pre>';
//            print_r($formpost);
//            print_r($message_data);
//            die();

            if($formpost['receiver'] == 'to_employees'){
                // $this->message_model->save_message($message_data);
                // $this->session->set_flashdata('message', 'Message sent successfully!');
                //send mail notification to recevier start
                $employer_raw_data = $this->company_model->get_details($formpost["to_id"], 'employer');
                $employerData = $employer_raw_data[0];
                $formpost['message'] = replace_tags_for_document($employerData['parent_sid'], $employerData['sid'], 'employee', $formpost['message']);
                $message_body = $formpost['message'];
                $create_password_link = '';
                if(strpos($message_body, '{{create_password_link}}')){
                    $salt = $employerData['salt'];

                    if($employerData['is_executive_admin']) {
                        if ($salt == NULL || $salt == '') {
                            $salt = generateRandomString(48);
                            $data = array('salt' => $salt);
                            $this->company_model->update_excetive_admin($formpost["to_id"], $data);
                        }
                        $create_password_link = '<a style="'.DEF_EMAIL_BTN_STYLE_DANGER.'" target="_blank" href="'. base_url() . "executive_admin/generate-password/" . $salt.'">Create Your Password</a>';
                    } else{
                        if($salt == NULL || $salt == '') {
                            $salt = generateRandomString(48);
                            $data = array('salt' => $salt);
                            $this->company_model->update_user($formpost["to_id"], $data);
                        }
                        $create_password_link  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';

                    }

                }
                $to_email = $employerData["email"];
                // Added on 30-04-2019
                replace_magic_quotes(
                    $formpost['subject'],
                    array(
                        '{{first_name}}' => $employerData['first_name'],
                        '{{firstname}}' => $employerData['first_name'],
                        '{{last_name}}' => $employerData['last_name'],
                        '{{lastname}}' => $employerData['last_name'],
                        '{{username}}' => $employerData["username"],
                        '{{email}}' => $employerData['email'],
                        '{{phone}}'  => $employerData['PhoneNumber']
                    )
                );
                replace_magic_quotes(
                    $formpost['message'],
                    array(
//                    '{{first_name}}' => $employerData['first_name'],
//                    '{{firstname}}' => $employerData['first_name'],
//                    '{{last_name}}' => $employerData['last_name'],
//                    '{{lastname}}' => $employerData['last_name'],
                        '{{username}}' => $employerData["username"],
                        '{{email}}' => $employerData['email'],
                        '{{create_password_link}}' => $create_password_link,
                        '{{phone}}'  => $employerData['PhoneNumber'],
                        '{{site_url}}'  => 'https://www.automotohr.com/'
                    )
                );
            }else{

                $formpost['message'] = str_replace(
                    array(
                        '{{company_name}}',
                        '{{company_address}}',
                        '{{company_phone}}',
                        '{{career_site_url}}',
                        '{{site_url}}',
                        '{{first_name}}',
                        '{{last_name}}',
                        '{{firstname}}',
                        '{{lastname}}',
                        '{{email}}',
                        '{{phone}}',
                        '{{username}}',
                        '{{create_password_link}}',
                    ),
                    array(
                        '',
                        '',
                        '',
                        '',
                        '',
                        $formpost['custom_email'],
                        '',
                        $formpost['custom_email'],
                        '',
                        $formpost['custom_email'],
                        '',
                        '',
                        '',
                    ),
                    $formpost['message']
                );
                $formpost['subject'] = str_replace(
                    array(
                        '{{company_name}}',
                        '{{company_address}}',
                        '{{company_phone}}',
                        '{{career_site_url}}',
                        '{{site_url}}',
                        '{{first_name}}',
                        '{{last_name}}',
                        '{{firstname}}',
                        '{{lastname}}',
                        '{{email}}',
                        '{{phone}}',
                        '{{username}}',
                        '{{create_password_link}}',
                    ),
                    array(
                        '',
                        '',
                        '',
                        '',
                        '',
                        $formpost['custom_email'],
                        '',
                        $formpost['custom_email'],
                        '',
                        $formpost['custom_email'],
                        '',
                        '',
                        '',
                    ),
                    $formpost['subject']
                );
                $to_email = $formpost['custom_email'];
                $message_data['anonym'] = 1;
            }

            $message_data['subject'] = $formpost['subject'];

            $from = $formpost['from_email'];
            $to = $to_email;
            $body = EMAIL_HEADER
//                . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $employerData["username"] . ',</h2>'
//                . '<br><br>'
//                . $this->ion_auth->user()->row()->username . '</b> has sent you a private message.'
//                . '<br><br><b>'
//                . 'Date:</b> '
//                . date('Y-m-d H:i:s')
//                . '<br><br><b>'
//                . 'Subject:</b> '
//                . $formpost["subject"]
//                . '<br><hr>'
                . $formpost["message"] . '<br><br>'
                //. '<a href="' . STORE_FULL_URL . '/login">Login to your account to reply this Message.</a><br>'
                . EMAIL_FOOTER;

            sendMail($from, $to, $formpost["subject"], $body);

            $message_data['message'] = $body;
            //saving email log
            $emailLog['subject'] = $formpost["subject"];
            $emailLog['email'] = $to;
            $emailLog['message'] = $body;
            $emailLog['date'] = date('Y-m-d H:i:s');
            $emailLog['admin'] = 'admin';
            $emailLog['status'] = 'Delivered';

            save_email_log_common($emailLog);

            $this->message_model->save_message($message_data);
            $this->session->set_flashdata('message', 'Message sent successfully!');
            //send mail notification to recevier end
            redirect('manage_admin/private_messages', 'refresh');
        }
    }

    public function reply_message($edit_id = NULL)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'reply_message';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($edit_id == NULL) {
            redirect('manage_admin/private_messages', 'refresh');
        } else {

            $this->data['page_title'] = 'Compose Messages';
            $this->data['groups'] = $this->ion_auth->groups()->result();
            //$admin_id = $this->ion_auth->user()->row()->id;

            $this->load->library('form_validation');
            $this->form_validation->set_rules('to_id', 'Employer', 'trim|required');
            $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
            $this->form_validation->set_rules('message', 'Message', 'trim');

            if ($this->form_validation->run() === FALSE) {

                $this->data['total_messages'] = $this->message_model->get_admin_messages_total(1);
                //getting all employer
                $this->data['page'] = 'reply';
                $employer_raw_data = $this->company_model->get_details($edit_id, 'employer');
                $this->data['employer_info'] = $employer_raw_data[0];
                $this->data['admin_templates'] = $this->fetch_admin_templates();

                $this->render('manage_admin/private_messages/compose_message');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                foreach ($formpost as $key => $value) {
                    if ($key != 'availability_to' && $key != 'availability_from' && $key != 'active' && $key != 'from_email') { // exclude these values from array
                        if (is_array($value)) {
                            $value = implode(',', $value);
                        }
                        $message_data[$key] = $value;
                    }
                }
                $message_data['from_id'] = '1';
                $message_data['from_type'] = 'admin';
                $message_data['to_type'] = 'employer';
                $message_data['date'] = date('Y-m-d H:i:s');

                //send mail notification to recevier start
                $employer_raw_data = $this->company_model->get_details($edit_id, 'employer');
                $employerData = $employer_raw_data[0];
                $formpost['message'] = replace_tags_for_document($employerData['parent_sid'], $employerData['sid'], 'employee', $formpost['message']);
                $message_body = $formpost['message'];
                $create_password_link = '';
                if(strpos($message_body, '{{create_password_link}}')){
                    $salt = $employerData['salt'];

                    if($employerData['is_executive_admin']) {
                        if ($salt == NULL || $salt == '') {
                            $salt = generateRandomString(48);
                            $data = array('salt' => $salt);
                            $this->company_model->update_excetive_admin($formpost["to_id"], $data);
                        }
                        $create_password_link = '<a style="'.DEF_EMAIL_BTN_STYLE_DANGER.'" target="_blank" href="'. base_url() . "executive_admin/generate-password/" . $salt.'">Create Your Password</a>';
                    } else{
                        if($salt == NULL || $salt == '') {
                            $salt = generateRandomString(48);
                            $data = array('salt' => $salt);
                            $this->company_model->update_user($formpost["to_id"], $data);
                        }
                        $create_password_link  = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . base_url() . "employee_management/generate_password/" . $salt . '">Create Your Password</a>';

                    }

                }
                // Added on: 30-04-2019
                replace_magic_quotes(
                    $formpost['subject'],
                    array(
                        '{{first_name}}' => $employerData['first_name'],
                        '{{firstname}}' => $employerData['first_name'],
                        '{{last_name}}' => $employerData['last_name'],
                        '{{lastname}}' => $employerData['last_name'],
                        '{{username}}' => $employerData["username"],
                        '{{email}}' => $employerData['email'],
                        '{{phone}}'  => $employerData['PhoneNumber']
                    )
                );
                replace_magic_quotes(
                    $formpost['message'],
                    array(
//                        '{{first_name}}' => $employerData['first_name'],
//                        '{{firstname}}' => $employerData['first_name'],
//                        '{{last_name}}' => $employerData['last_name'],
//                        '{{lastname}}' => $employerData['last_name'],
                        '{{username}}' => $employerData["username"],
                        '{{email}}' => $employerData['email'],
                        '{{create_password_link}}' => $create_password_link,
                        '{{phone}}'  => $employerData['PhoneNumber'],
                        '{{site_url}}'  => 'https://www.automotohr.com/'
                    )
                );

                $message_data['subject'] = $formpost['subject'];

                $from = $formpost['from_email'];
                $to = $employerData["email"];
                $body = EMAIL_HEADER
//                    . '<h2 style="width:100%; margin:0 0 20px 0;">Dear ' . $employerData["username"] . ',</h2>'
//                    . '<br><br><b>'
//                    . $this->ion_auth->user()->row()->username . '</b> has sent you a private message.'
//                    . '<br><br><b>'
//                    . 'Date:</b> '
//                    . date('Y-m-d H:i:s')
//                    . '<br><br><b>'
//                    . 'Subject:</b> '
//                    . $formpost["subject"]
//                    . '<br><hr>'
                    . $formpost["message"] . '<br>'
                    . '<a href="' . STORE_FULL_URL . '/login">Login to you account to reply this Message.</a><br>'
                    . EMAIL_FOOTER;

                sendMail($from, $to, $formpost["subject"], $body);
                $message_data['message'] = $body;
                //send mail notification to recevier end
                $this->message_model->save_message($message_data);
                $this->session->set_flashdata('message', 'Message sent successfully!');
                redirect('manage_admin/private_messages', 'refresh');
            }
        }
    }

    function message_task()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'delete_private_message';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $action = $this->input->post("action");
        $message_id = $this->input->post("sid");

        if ($action == 'delete') {
            $this->message_model->delete_message($message_id);
        }
    }

    /**
     * Fetch Admin templates
     *
     * @return Array|Bool
     */

    function fetch_admin_templates(){
        $admin_id = $this->ion_auth->user()->row()->id;
        if(!$admin_id) redirect('manage_admin/user/login');
        //
        return $this->message_model->fetch_admin_templates();
    }

}
