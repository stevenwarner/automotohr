<?php defined('BASEPATH') or exit('No direct script access allowed');

class Complynet extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/2022/complynet_model');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = null, $from = null, $to = null)
    {

        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['title'] = 'ComplyNet';

        if (!empty($company_sid) && $company_sid != 'all') {
            $companySid = $company_sid;
        } else {
            $companySid = 'all';
        }

        if (!empty($from) && $from != 'all') {
            $start_date = empty($from) ? null : DateTime::createFromFormat('m-d-Y', $from)->format('Y-m-d 00:00:00');
        } else {
            $start_date = date('Y-m-d 00:00:00');
            $from = 'all';
        }

        if (!empty($to) && $to != 'all') {
            $end_date = empty($to) ? null : DateTime::createFromFormat('m-d-Y', $to)->format('Y-m-d 23:59:59');
        } else {
            $end_date = date('Y-m-d 23:59:59');
            $to = 'all';
        }

        //
        $total_records = $this->complynet_model->get_complynet_companies($company_sid, null, null, true);
        $base_url = base_url('manage_admin/2022/reports/complynet') . '/' . urlencode($companySid) . '/' . urlencode($from) . '/' . urlencode($to);

        $offset = 0;
        $page_number  = ($this->uri->segment(8)) ? $this->uri->segment(8) : 1;


        $records_per_page = 10; //PAGINATION_RECORDS_PER_PAGE;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $records_per_page;
        }

        $config = array();
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_records;
        $config['per_page'] = $records_per_page;
        $config['uri_segment'] = 8;
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
        $this->data['company_records'] = $this->complynet_model->get_complynet_companies($company_sid, $records_per_page, $offset);

        $companies = $this->complynet_model->get_all_companies();
        $this->data['companies'] = $companies;
        $this->data['companySid'] = $companySid;

        $this->render('manage_admin/2022/reports/complynet');
    }


    //

    function login($sid)
    {

        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->data['title'] = 'ComplyNet';
        $this->data['employee'] = getUserNameBySID($sid, false);
        $this->render('2022/complynet/index');
    }


    //
    public function getcompanyemployees($company_id)
    {

        $employees = $this->complynet_model->get_active_employees_detail($company_id);
        $result_head = '<table class="table table-bordered table-hover table-striped table-condensed">
        <thead>
            <tr>
                <th class="col-xs-4">Employee Name</th>
                <th class="col-xs-2">Email</th>
                <th class="col-xs-2">User Name</th>
                <th class="col-xs-2">Password</th>
                <th class="col-xs-2">Action</th>
            </tr>
        </thead>
        <thead>';

        //
        $result_row = '';
        if(!empty($employees)){
        foreach ($employees as $emp_row) {

            $result_row .=  '<tr>                  
            <td>' . getUserNameBySID($emp_row['sid'], $remake = true) . '</td>
            <td>' . $emp_row['email'] . '</td>
            <td>' . $emp_row['username'] . '</td>
            <td>' . $emp_row['password'] . '</td>
            <td><a href="' . base_url('manage_admin/2022/reports/complynet/login/' . $emp_row['sid']) . '" class="btn btn-success btn-block" target="_blank">View</a></td>
        </tr>';
        }
    }else{

        $result_row .=  '<tr><td colspan="5" class="text-center"><div class="no-data">No employee found.</div></td> </tr>';
    }

        //
        $result_footer = '</thead>
        </table>';
        echo $result_head . $result_row . $result_footer;
    }
}