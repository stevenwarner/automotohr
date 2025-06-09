<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_ai_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $keyword = 'all', $startdate = 'all', $enddate = 'all', $status = 'all', $page_number = 1)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_origination_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $company_sid = urldecode($company_sid);
        $start_date = urldecode($startdate);
        $end_date = urldecode($enddate);
        $keyword = urldecode($keyword);
        $status = urldecode($status);
        $this->data['flag'] = true;
        $this->form_validation->set_data($this->input->get(NULL, true));


        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_applied = date('Y-m-d 00:00:00');
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_applied = date('Y-m-d 23:59:59');
        }

        $this->data['page_title'] = 'Applicants AI Report';
        $main_companies = $this->advanced_report_model->get_all_companies();
        $this->data['companies'] = $main_companies;

        $result_array = array();


        $per_page = PAGINATION_RECORDS_PER_PAGE;
        // $per_page = 2;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }
        //
        $total_records = $this->advanced_report_model->get_applicant_ai_report($company_sid, $keyword, $start_date_applied, $end_date_applied, $status, 1);
        $applicants = $this->advanced_report_model->get_applicant_ai_report($company_sid, $keyword, $start_date_applied, $end_date_applied, $status, 0, $per_page, $offset);

        $final_applicants = array();
        $final_applicants = $applicants;
       
        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/applicants_ai_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($status);

        //echo $pagination_base;

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 8;
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
        $config['next_link'] = '<i class="fa fa-angle-right" style="line-height: 32px;"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left" style="line-height: 32px;"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';


        $this->pagination->initialize($config);
        $this->data["page_links"] = $this->pagination->create_links();

        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

        //-----------------------------------Pagination Ends-----------------------------//

        $this->data['applicants_count'] = $total_records;
        $this->data['applicants'] = $final_applicants;
        $this->render('manage_admin/reports/applicants_ai_report');
    }

    public function view_detail ($sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_resume_analysis_detail';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $applicantResume = $this->advanced_report_model->get_applicant_resume_analysis($sid);
        //
        $this->data["applicant"] = $applicantResume;
        $this->render('manage_admin/reports/applicant_resume_analysis');
    }

}