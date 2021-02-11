<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_item_usage extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/invoice_item_usage_model');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = null, $from = null, $to = null, $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['title'] = 'Invoice Items Usage';

        if (!empty($from) && $from != 'all') {
            $start_date = empty($from) ? null : DateTime::createFromFormat('m-d-Y', $from)->format('Y-m-d 00:00:00');
        } else {
            $start_date = date('Y-m-d 00:00:00');
        }

        if (!empty($to) && $to != 'all') {
            $end_date = empty($to) ? null : DateTime::createFromFormat('m-d-Y', $to)->format('Y-m-d 23:59:59');
        } else {
            $end_date = date('Y-m-d 23:59:59');
        }

        $total_records = $this->invoice_item_usage_model->invoice_item_usage($company_sid, $start_date, $end_date, null, null, true);

        $base_url = base_url('manage_admin/reports/invoice_item_usage') . '/' . urlencode($company_sid) . '/' . urlencode($from) . '/' . urlencode($to) . '/' . $page_number;

        $offset = 0;
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        if($page_number > 1)
        {
            $offset = ($page_number - 1) * $records_per_page;
        }

        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 7;
        $config['num_links'] = 5;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
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

        $this->data['links'] = $this->pagination->create_links();

        $item_track_records = $this->invoice_item_usage_model->invoice_item_usage($company_sid, $start_date, $end_date,$records_per_page, $offset);
        $this->data['item_track_records'] = $item_track_records;

        $companies = $this->invoice_item_usage_model->get_all_companies();
        $this->data['companies'] = $companies;

        $this->render('manage_admin/reports/invoice_item_usage');
    }



}
