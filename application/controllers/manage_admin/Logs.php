<?php

defined('BASEPATH') or exit('No direct script access allowed');

class logs extends Admin_Controller
{
    //
    private $resp = [];

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/logs_model');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        //
        $this->resp = [
            'Status' => false,
            'Response' => 'Reqest Not Authorized'
        ];
    }

    //contact us logs
    public function contactus_enquiries()
    {
        $this->data['page_title'] = 'Contact Us Enquiries';
        $this->data['groups'] = $this->ion_auth->groups()->result();

        //--------------------Search section Start---------------//
        $search = array();
        $search_data = $this->input->get(NULL, True);
        $this->data["search"] = $search_data;
        $this->data["flag"] = false;
        foreach ($search_data as $key => $value) {
            if ($key != 'start' && $key != 'end') {
                if ($value != '') { // exclude these values from array
                    $search[$key] = $value;
                    $this->data["flag"] = true;
                }
            }
        }
        if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "") {
            $start_date = explode('-', $search_data['start']);
            $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
            $end_date = explode('-', $search_data['end']);
            $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 00:00:00';
            $between = "date between '" . $start_date . "' and '" . $end_date . "'";
        }


        //--------------------Search section End---------------//
        if (isset($search_data['start']) && $search_data['start'] != "" && isset($search_data['end']) && $search_data['end'] != "")
            $db_products = $this->logs_model->get_contact_logs_date($search, $between);
        else
            $db_products = $this->logs_model->get_contact_logs($search);
        $this->data['logs'] = $db_products;

        $this->render('manage_admin/logs/contactus_log_view');
    }

    public function log_detail($edit_id = NULL)
    {
        //If parameter not exist 
        if ($edit_id == NULL) {
            redirect('manage_admin/contactus_enquiries', 'refresh');
        } else {
            $this->data['page_title'] = 'Contact Us Enquiries';
            $this->data['groups'] = $this->ion_auth->groups()->result();

            $log_data = $this->logs_model->get_contactus_log_detail($edit_id);
            $this->data['log'] = $log_data[0];

            $this->render('manage_admin/logs/contactus_log_detail');
        }
    }

    //email logs 
    public function email_enquiries($user_name = 'all', $email = 'all', $start_date = 'all', $end_date = 'all', $name_search = 'all')
    {
        $redirect_url = 'manage_admin';
        $function_name = 'email_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;


        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Email Enquiries';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $user_name = urldecode($user_name);
        $email = urldecode($email);
        $name_search = urldecode($name_search);

        if (!empty($start_date) && $start_date != 'all') {
            $start_date_db = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_db = null;
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_db = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_db = null;
        }

        $total_records = $this->logs_model->get_email_enquiries_logs($user_name, $email, $start_date_db, $end_date_db, null, null, true, $name_search);
        $this->load->library('pagination');
        $pagination_base = base_url('manage_admin/email_enquiries') . '/' . urlencode($user_name) . '/' . urlencode($email) . '/' .  urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($name_search);

        $records_per_page                                                       = PAGINATION_RECORDS_PER_PAGE;
        $uri_segment                                                            = 8;
        $keywords                                                               = '';
        $offset                                                                 = 0;
        $page_number                                                            = ($this->uri->segment(8)) ? $this->uri->segment(8) : 1;

        if ($page_number > 1) {
            $offset                                                             = ($page_number - 1) * $records_per_page;
        }

        //echo $pagination_base;
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        //$config['page_query_string'] = true;
        //$config['reuse_query_string'] = true;
        //$config['query_string_segment'] = 'page_number';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $email_logs = $this->logs_model->get_email_enquiries_logs($user_name, $email, $start_date_db, $end_date_db, $records_per_page, $offset, false, $name_search);
        //echo strip_tags($email_logs[0]['message'], '<h2>').'<hr><br><hr>'.$email_logs[0]['message'];

        //echo '<br>';
        //        $length_start = strpos($email_logs[0]['message'],'Dear');
        //        $message_start = substr($email_logs[0]['message'], $length_start);
        //        $length_end = strpos($message_start,'</h2>');
        //        echo $message_name = substr($message_start, 0, $length_end-1);
        //        exit;
        $this->data['Flag'] = true;
        $this->data['logs'] = $email_logs;
        $this->render('manage_admin/logs/email_log_view');
    }

    //SMS Log
    public function sms_enquiries($name_search = 'all', $email = 'all', $sender = 'all', $status = 'all', $start_date = 'all', $end_date = 'all')
    {

        $redirect_url = 'manage_admin';
        $function_name = 'sms_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'SMS Enquiries';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $sender = urldecode($sender);
        $email = urldecode($email);
        $name_search = urldecode($name_search);
        $status = urldecode($status);

        if (!empty($start_date) && $start_date != 'all') {
            $start_date_db = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_db = null;
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_db = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_db = null;
        }


        $total_records = $this->logs_model->get_sms_enquiries_logs($sender, $status, $email, $start_date_db, $end_date_db, null, null, true, $name_search);
        $this->load->library('pagination');
        //  $first_link                                                              =1;
        // $first_link_url                                                         =10;
        $records_per_page                                                       = 5;
        $uri_segment                                                            = 9;
        $keywords                                                               = '';
        $offset                                                                 = 0;
        $page_number                                                            = ($this->uri->segment(9)) ? $this->uri->segment(9) : 1;

        if ($page_number > 1) {
            $offset                                                             = ($page_number - 1) * $records_per_page;
        }
        $pagination_base = base_url('manage_admin/sms_enquiries') . '/' . urlencode($name_search) . '/' . urlencode($email) . '/' . urlencode($sender) . '/' . urlencode($status) . '/' . urlencode($start_date) . '/' . urlencode($end_date);
        //echo $pagination_base;
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        // $config["first_url"]=9;
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        //$config['page_query_string'] = true;
        //$config['reuse_query_string'] = true;
        //$config['query_string_segment'] = 'page_number';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        // $email_logs = $this->logs_model->get_email_enquiries_logs($user_name, $email, $start_date_db, $end_date_db, $records_per_page, $offset, false, $name_search);
        $sms_logs = $this->logs_model->get_sms_enquiries_logs($sender, $status, $email, $start_date_db, $end_date_db, $records_per_page, $offset, false, $name_search);
        $sms_company_name = $this->logs_model->get_company_sms_info();



        //echo strip_tags($email_logs[0]['message'], '<h2>').'<hr><br><hr>'.$email_logs[0]['message'];

        //echo '<br>';
        //        $length_start = strpos($email_logs[0]['message'],'Dear');
        //        $message_start = substr($email_logs[0]['message'], $length_start);
        //        $length_end = strpos($message_start,'</h2>');
        //        echo $message_name = substr($message_start, 0, $length_end-1);
        //        exit;
        $this->data['Flag'] = true;
        // $this->data['logs'] = $email_logs;
        $this->data['sms_company_name'] = $sms_company_name;
        $this->data['sms_logs'] = $sms_logs;
        $this->render('manage_admin/logs/sms_log_view');
    }
    public function modules($module_name = 'all', $is_disabled = 'all', $is_ems_module = 'all', $stage = 'all')
    {
        $this->load->library('pagination');
        $module_name = urldecode($module_name);
        $is_disabled = urldecode($is_disabled);
        $is_ems_module = urldecode($is_ems_module);
        $stage = urldecode($stage);
        $redirect_url = 'manage_admin';
        $function_name = 'email_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Modules';
        $page_number = ($this->uri->segment(7)) ? $this->uri->segment(7) : 1;
        $offset           = 0;
        $records_per_page = 50;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }
        $pagination_base = base_url('manage_admin/modules') . '/' . urlencode($module_name) . '/' . urlencode($is_disabled) . '/' . urlencode($is_ems_module) . '/' . urlencode($stage);
        $Module_data = $this->logs_model->get_module_data($module_name, $is_disabled, $is_ems_module, $stage);
        $total_records = $this->logs_model->get_module_data($module_name, $is_disabled, $is_ems_module, $stage, null, null, true);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $this->uri->total_segments();
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $Module_data = $this->logs_model->get_module_data($module_name, $is_disabled, $is_ems_module, $stage, $records_per_page, $offset, false);
        $this->data['groups'] = $this->ion_auth->groups()->result();

        $this->data['module_data'] = $Module_data;
        $this->render('manage_admin/modules/index');
    }
    public function edit_module($sid)
    {

        $this->load->helper('url');
        $redirect_url = 'manage_admin';
        $function_name = 'email_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);

        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Modules';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $module_data = $this->logs_model->get_specific_module_data($sid);
        $this->data['module_data'] = $module_data[0];
        $this->form_validation->set_rules('module_name', 'module_name', 'required');
        if ($this->form_validation->run() == false) {
            $this->render('manage_admin/modules/edit_module');
        } else {
            $update_array = array();
            $name = $this->input->post('module_name');
            $stage = $this->input->post('stage');
            $disabled = $this->input->post('is_disabled');
            $ems = $this->input->post('is_ems_module');
            $submit = $this->input->post('submit_button');
            $update_array['module_name'] = $name;
            $update_array['stage'] = $stage;
            $update_array['is_disabled'] = $disabled;
            $update_array['is_ems_module'] = $ems;
            $this->logs_model->update_module_data($sid, $update_array);
            if ($submit != "" && $submit == 'Save') {
                $this->session->set_flashdata('message', '<strong>Success</strong> Your data has been saved successfully!');
                redirect(base_url("manage_admin/modules"));
            }
        }
    }
    function change_company_status()
    {
        $comany_sid = $this->input->post("company_sid");
        $status = $this->input->post("status");
        $module_id = $this->input->post("module_id");
        if ($status) {
            $data = array('complynet_status' => 0);
            $return_data = array(
                'btnValue' => 'Disable',
                'label'     => 'Enabled',
                'value'     =>  1
            );
        } else {
            $data = array('complynet_status' => 1);
            $return_data = array(
                'btnValue' => 'Enable',
                'label'     => 'Disabled',
                'value'     =>  0
            );
        }
        $this->company_model->update_user_status($sid, $data);
        print_r(json_encode($return_data));
    }

    public function company_module($sid)
    {
        $this->load->helper('url');
        //
        $this->load->helper('common_helper');
        //
        $redirect_url = 'manage_admin';
        $function_name = 'email_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['page_title'] = 'Company Module';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        $this->data['module_data'] = $this->logs_model->getModuleInfo($sid);
        //
        $active_companies = $this->logs_model->get_all_active_companies();
        //
        foreach ($active_companies as $ckey => $company) {
            $module_status = $this->logs_model->get_company_module_status($company["sid"], $sid);
            $active_companies[$ckey]["status"] = $module_status;
        }
        //
        $this->data['company_data'] = $active_companies;
        //
        if ($sid == 7) {
            $this->render('payroll/company_module');
        } else {
            $this->render('manage_admin/modules/company_module');
        }
    }

    public function notification_email_log($from = 'all', $to_email = 'all', $start_date = 'all', $end_date = 'all')
    {
        $redirect_url = 'manage_admin';
        $function_name = 'notification_email_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Notification Email Enquiries';
        $this->data['groups'] = $this->ion_auth->groups()->result();
        //
        $from = urldecode($from);
        $to_email = urldecode($to_email);
        //
        if (!empty($start_date) && $start_date != 'all') {
            $start_date_db = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_db = null;
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_db = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_db = null;
        }

        $total_records = $this->logs_model->get_notification_email_logs($from, $to_email, $start_date_db, $end_date_db, null, null, true);
        $this->load->library('pagination');
        $pagination_base = base_url('manage_admin/notification_email_log') . '/' . urlencode($from) . '/' . urlencode($to_email) . '/' .  urlencode($start_date) . '/' . urlencode($end_date);

        $records_per_page                                                       = PAGINATION_RECORDS_PER_PAGE;
        $uri_segment                                                            = 7;
        $keywords                                                               = '';
        $offset                                                                 = 0;
        $page_number                                                            = ($this->uri->segment(7)) ? $this->uri->segment(7) : 1;

        if ($page_number > 1) {
            $offset                                                             = ($page_number - 1) * $records_per_page;
        }

        //echo $pagination_base;
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $config["num_links"] = 8;
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $email_logs = $this->logs_model->get_notification_email_logs($from, $to_email, $start_date_db, $end_date_db, $records_per_page, $offset, false);

        $this->data['Flag'] = true;
        $this->data['logs'] = $email_logs;
        $this->data['logs'] = $email_logs;
        $this->render('manage_admin/logs/notification_log_view');
    }

    public function email_log($edit_id = NULL)
    {
        //If parameter not exist 
        if ($edit_id == NULL) {
            redirect('manage_admin/email_enquiries', 'refresh');
        } else {
            $this->data['page_title'] = 'Email Enquiries';
            $this->data['groups'] = $this->ion_auth->groups()->result();

            $log_data = $this->logs_model->get_email_log_detail($edit_id);
            if (!empty($log_data)) {
                $result = $log_data[0];
                $username = $result['username'];
                if ($username == '') {
                    $result['username'] = 'admin';
                } else {
                    $sid = $username;
                    $user_data = $this->logs_model->get_username_by_sid($sid);
                    if (!empty($user_data)) {
                        $result_username = $user_data[0]['username'];
                        $result['username'] = $result_username;
                    }
                }
            }
            $this->data['log'] = $result;
            $this->render('manage_admin/logs/email_log_detail');
        }
    }
    public function sms_log($edit_id = NULL)
    {
        //If parameter not exist 
        if ($edit_id == NULL) {
            redirect('manage_admin/sms_enquiries', 'refresh');
        } else {
            $this->data['page_title'] = 'SMS Enquiries';
            $this->data['groups'] = $this->ion_auth->groups()->result();

            // $log_data = $this->logs_model->get_email_log_detail($edit_id);
            $sms_log = $this->logs_model->get_sms_log_detail($edit_id);
            // if (!empty($log_data)) {
            //     $result = $log_data[0];
            //     $username = $result['username'];
            //     if ($username == '') {
            //         $result['username'] = 'admin';
            //     } else {
            //         $sid = $username;
            //         $user_data = $this->logs_model->get_username_by_sid($sid);
            //         if (!empty($user_data)) {
            //             $result_username = $user_data[0]['username'];
            //             $result['username'] = $result_username;
            //         }
            //     }
            // }
            // $this->data['sms_logs'] = $sms_logs;
            // $this->data['log'] = $result;
            $this->data['sms_log'] = $sms_log[0];
            $this->render('manage_admin/logs/sms_log_detail');
        }
    }

    public function notification_log($edit_id = NULL)
    {
        //If parameter not exist 
        if ($edit_id == NULL) {
            redirect('manage_admin/notification_email_log', 'refresh');
        } else {
            $this->data['page_title'] = 'Notification Email Log';
            $this->data['groups'] = $this->ion_auth->groups()->result();

            $log_data = $this->logs_model->get_notification_email_log_detail($edit_id);
            if (!empty($log_data)) {
                $result = $log_data[0];
            }
            $this->data['log'] = $result;

            $this->render('manage_admin/logs/notification_email_log_view');
        }
    }

    public function resend_email($edit_id = NULL)
    {
        if ($edit_id == NULL) {
            redirect('manage_admin/email_enquiries', 'refresh');
        } else {
            $log_data = $this->logs_model->get_email_log_detail($edit_id);
            $log_data = $log_data[0];
            $this->logs_model->update_resend_status($edit_id);
            //Re-sending email to user
            $from = FROM_EMAIL_NOTIFICATIONS;
            $to = $log_data["email"];
            $subject = $log_data["subject"];
            $body = $log_data["message"];
            sendMail($from, $to, $subject, $body, STORE_NAME . " Re-send");
            $this->session->set_flashdata('message', 'Mail sent Successfully.');
            redirect('manage_admin/email_enquiries', 'refresh');
        }
    }


    //
    function UpdatePayroll()
    {
        //
        if (
            !$this->input->is_ajax_request() ||
            $this->input->method() != 'post' ||
            empty($this->input->post(NULL))
        ) {
            res($this->resp);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        // 
        $this->load->model('Payroll_model', 'pm');
        //
        switch ($post['action']):
            case "update_ein":
                // Check if EIN number already exists
                $exists = $this->pm->CheckEINNumber($post['ein'], $post['companyId']);
                //
                if ($exists) {
                    $this->resp['Response'] = 'EIN number already exists for another company.';
                    res($this->resp);
                }
                //
                $this->pm->UpdateCompanyEIN($post['companyId'], ['ssn' => $post['ein']]);
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'You have successfully updated the EIN number.';
                break;
            case "update_status":
                //
                $this->logs_model->UpdateCompanyData($post['companyId'], $post['status']);
                //
                $this->pm->UpdatePC(
                    [
                        'is_active' => $post['status'] ? 0 : 1
                    ],
                    [
                        'company_sid' => $post['companyId']
                    ]
                );
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'You have successfully ' . ($post['status'] ? 'disabled' : 'enabled') . ' the company for payroll.';
                break;
            case "refresh_token":
                // Load Curl Helper
                $this->load->model('Payroll_model', 'payroll_model');
                $this->load->helper('Payroll_helper');
                //
                $companyId = $this->input->post('companyId', TRUE);
                //
                $company = $this->payroll_model->GetCompany(
                    $companyId,
                    [
                        'gusto_company_uid',
                        'access_token',
                        'refresh_token'
                    ]
                );
                //
                $response = RefreshToken([
                    'access_token' => $company['access_token'],
                    'refresh_token' => $company['refresh_token']
                ]);

                if (isset($response['access_token'])) {
                    $this->payroll_model->UpdatePC([
                        'old_access_token' => $company['access_token'],
                        'old_refresh_token' => $company['refresh_token'],
                        'access_token' => $response['access_token'],
                        'refresh_token' => $response['refresh_token']
                    ], [
                        'company_sid' => $companyId
                    ]);
                    //
                }
                //
                $this->resp['Status'] = true;
                $this->resp['Response'] = 'You have successfully generated new tokens.';
                break;
        endswitch;
        //
        res($this->resp);
    }

    function company_onboarding($company_sid)
    {
        //
        $this->load->helper("payroll_helper");
        //
        $company = $this->logs_model->getCompanyPayrollInfo($company_sid);
        //
        $url = PayrollURL('GetCompanyFlows', $company['gusto_company_uid']);
        //
        $request = [
            "flow_type" => "company_onboarding",
            "entity_type" => "Company",
            "entity_uuid" => $company['gusto_company_uid']
        ];
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            $this->data["iframe_url"] = "https://gws-flows.gusto-demo.com/flows/lO2BHHAMCScPVV9G5WEURW0Im_nP9mGYloQgjUWbenQ";
        } else {
            $this->data["iframe_url"] = $response["url"];
        }
        $this->render('payroll/payroll_company_flow');
    }
}
