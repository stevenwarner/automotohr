<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Call_logs extends Admin_Controller
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

    //
    public function index($user_name = 'all', $email = 'all', $start_date = 'all', $end_date = 'all', $name_search = 'all')
    {
        $redirect_url = 'manage_admin';
        $function_name = 'email_enquiries_log';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;


        check_access_permissions($security_details, $redirect_url, $function_name); 

        $this->data['page_title'] = 'Call Logs';
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

        $table_name='adp_queue';

        $total_records = $this->logs_model->get_call_logs($table_name, $email, $start_date_db, $end_date_db, null, null, true, $name_search);
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


        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $this->data['total_records'] = $total_records;
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $offset + $records_per_page;
        $email_logs = $this->logs_model->get_call_logs($table_name, $email, $start_date_db, $end_date_db, $records_per_page, $offset, false, $name_search);

        $this->data['Flag'] = true;
        $this->data['logs'] = $email_logs;
        $this->render('manage_admin/logs/call_log_view');
    }
}
