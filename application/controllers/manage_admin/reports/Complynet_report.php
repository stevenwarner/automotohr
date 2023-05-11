<?php defined('BASEPATH') or exit('No direct script access allowed');

class Complynet_report extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/complynet_model');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($keyword = null, $status = null, $method = null, $from = null, $to = null, $page_active = 1, $page_number = 1)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        // reset values
        $keyword = $keyword ?? 'all';
        $status = $status ?? 'all';
        $method = $method ?? 'all';
        $from = $from ?? 'all';
        $to = $to ?? 'all';

        $this->data['title'] = 'ComplyNet Requests Report';

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

        // 
        $this->data['boxArray'] = [
            'success' => $this->complynet_model->getFilterRecords($keyword, 'success', $method, $start_date, $end_date, null, null, true),
            'error' => $this->complynet_model->getFilterRecords($keyword, 'error', $method, $start_date, $end_date, null, null, true),
            'get' => $this->complynet_model->getFilterRecords($keyword, $status, 'get', $start_date, $end_date, null, null, true),
            'post' => $this->complynet_model->getFilterRecords($keyword, $status, 'post', $start_date, $end_date, null, null, true),
            'put' => $this->complynet_model->getFilterRecords($keyword, $status, 'put', $start_date, $end_date, null, null, true),
            'delete' => $this->complynet_model->getFilterRecords($keyword, $status, 'delete', $start_date, $end_date, null, null, true)
        ];

        $total_records = $this->complynet_model->getFilterRecords($keyword, $status, $method, $start_date, $end_date, null, null, true);
        $keyword = urldecode($keyword);

        $base_url = base_url('manage_admin/reports/complynet_report') . '/' . urlencode($keyword) . '/' . urlencode($status) . '/' . urldecode($method) . '/' . urlencode($from) . '/' . urlencode($to) . '/' . $page_number;

        $offset = 0;
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
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

        $calls = $this->complynet_model->getFilterRecords($keyword, $status, $method, $start_date, $end_date, $records_per_page, $offset);
        $this->data['calls'] = $calls;

        $this->render('manage_admin/reports/complynet_report');
    }

    public function getDetail($sid)
    {
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');
        // Check foir ajax request
        if (!$this->input->is_ajax_request() || $this->input->method(true) !== 'GET') exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        //
        $call = $this->complynet_model->getCall($sid);
        //
        if (!$call) {
            $resp['Response'] = 'No call found.';
            $this->resp($resp);
        }
        // 
        $resp['Status'] = TRUE;
        $resp['Response'] = $call;
        // $resp['request_body'] = json_decode($call['request_body'],true);
        // $resp['response_code'] = $call['response_code'];
        // $resp['request_url'] = $call['request_url'];
        // $resp['response_body'] = $call['response_body'];
        // $resp['response_headers'] = $call['response_headers'];

        $this->resp($resp);
    }

    /**
     * Send response
     *
     * @param $array Array
     *
     * @return JSON
     */
    private function resp($array)
    {
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }
}
