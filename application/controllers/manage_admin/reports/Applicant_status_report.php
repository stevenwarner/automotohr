<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_status_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $oem = 'all', $all = 1, $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicant_status_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        if($oem!='all')
            $this->data['company_or_brand'] = 'brands';
        elseif($company_sid!='all')
            $this->data['company_or_brand'] = 'company';
        else
            $this->data['company_or_brand'] = 'all';

        $this->data['page_title'] = 'Advanced Hr Reports - Applicant Status';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));

        $keyword = urldecode($keyword);
        $job_sid = urldecode($job_sid);
        $applicant_type = urldecode($applicant_type);
        $applicant_status = urldecode($applicant_status);
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);

        if(!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_applied = date('Y-m-d 00:00:00');
        }

        if(!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_applied = date('Y-m-d 23:59:59');
        }
        $this->data['flag'] = true;

//        $applicant_types = array();
//        $applicant_types[] = 'Applicant';
//        $applicant_types[] = 'Talent Network';
//        $applicant_types[] = 'Manual Candidate';
        $applicant_types = explode(',', APPLICANT_TYPE_ATS);
        $this->data['applicant_types'] = $applicant_types;

        $applicant_statuses = $this->advanced_report_model->get_company_statuses($company_sid);
        $this->data['applicant_statuses'] = $applicant_statuses;
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        //$per_page = 2;
        //$page_number = $this->input->get('page_number');
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }
        $total_records = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, true);

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/applicant_status_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . $job_sid . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($oem) . '/' . urlencode($all);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 13;
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
        $this->data["page_links"] = $this->pagination->create_links();


        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

        //-----------------------------------Pagination Ends-----------------------------//

        //$applicants = $this->Reports_model->get_applicants($company_sid, $search_string, $search_string2, $start_date_applied, $end_date_applied, false, $per_page, $offset);
        $applicants = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, false, $per_page, $offset);
        $this->data['applicants'] = $applicants;

        $this->data['applicants_count'] = $total_records;

//        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
//            $search_data = $this->input->get(NULL, true);
//
//            // ** select company or brand sid **//
//            if ($search_data['company_or_brand'] == 'company') {
//                $company_sid = $search_data['company_sid'];
//                $brand_sid = NULL;
//            } else {
//                $brand_sid = $search_data['brand_sid'];
//                $company_sid = NULL;
//            }
//            // ** select company or brand sid **//
//
//            //** get applicant statuses **//
//
//            $this->data['applicants'] = $this->advanced_report_model->get_applicants_status($company_sid, $brand_sid, $start_date, $end_date);
//            //** get applicant statuses **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }
        $this->data['have_status'] = $this->advanced_report_model->check_company_status($company_sid);
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['applicants']) && sizeof($this->data['applicants']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                
                if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {                   
                        fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email', 'Company Name', 'Status'));              
                } else {                   
                        fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email', 'Status'));             
                }

                foreach ($this->data['applicants'] as $applicant) {
                    $input = array();
                    $city = '';
                    $state='';
                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                        $city = ' - '.ucfirst($applicant['Location_City']);
                    }
                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                        $state = ', '.db_get_state_name($applicant['Location_State'])['state_name'];
                    }
                    $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                    $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                    $input['Title'] = $applicant['Title'].$city.$state;                    
                    $input['email'] = $applicant['email'];
                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        $input['CompanyName'] = $applicant['CompanyName'];
                    }
                    $input['status'] = ucwords($applicant['status']);
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/applicant_status_report');
    }

}
