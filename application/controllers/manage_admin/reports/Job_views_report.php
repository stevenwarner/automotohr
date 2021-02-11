<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_views_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $oem = 'all', $all = 1, $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'job_views_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $oem = urldecode($oem);
        $company_sid = urldecode($company_sid);

        $this->data['page_title'] = 'Job Views and Hires';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;

        //-----------------------------------Pagination Starts----------------------------//
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/job_views_report') . '/' . $company_sid . '/' . urlencode($oem) . '/' . urlencode($all);

        if($oem!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        }
        elseif($company_sid!='all' && $company_sid!=NULL){
            $this->data['company_or_brand'] = 'company';
            $oem = NULL;
        }
        else{
            $this->data['company_or_brand'] = 'all';
            $oem = NULL;
            $company_sid = NULL;
        }
        $total_records = $this->advanced_report_model->get_all_jobs_views_applicants_count($company_sid, $oem, $all, 1);
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 7;
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

        $all_jobs = $this->advanced_report_model->get_all_jobs_views_applicants_count($company_sid, $oem, $all, 0, $per_page, $offset);
        
        $this->data['all_jobs'] = $all_jobs;
        $total_views = 0;
        $total_applicants = 0;

        if(sizeof($all_jobs)>0){
            foreach ($all_jobs as $job) {
                $total_views += intval($job['views']);
                $total_applicants += intval($job['applicant_count']);
            }
        }

        $this->data['total_views'] = $total_views;
        $this->data['total_applicants'] = $total_applicants;
        $this->data['applicants_count'] = $total_records;
        //** get job views and hires **//

        $this->data['flag'] = true;
        
//        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
//            $search_data = $this->input->get(NULL, true);
//
//            // ** select company or brand sid **//
//            if($search_data['company_or_brand'] == 'company'){
//                $company_sid = $search_data['company_sid'];
//                $brand_sid = NULL;
//            } else {
//                $brand_sid = $search_data['brand_sid'];
//                $company_sid = NULL;
//            }
//            // ** select company or brand sid **//
//
//            //** get job views and hires **//
//            $all_jobs = $this->advanced_report_model->get_all_jobs_views_applicants_count($company_sid, $brand_sid);
//            $this->data['all_jobs'] = $all_jobs;
//            $total_views = 0;
//            $total_applicants = 0;
//
//            foreach ($all_jobs as $job) {
//                $total_views += intval($job['views']);
//                $total_applicants += intval($job['applicant_count']);
//            }
//
//            $this->data['total_views'] = $total_views;
//            $this->data['total_applicants'] = $total_applicants;
//            //** get job views and hires **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }
        
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if(isset($this->data['all_jobs']) && sizeof($this->data['all_jobs']) > 0){
             
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') { 
                    fputcsv($output, array('Date', 'Job Title', 'Company Name', 'Views', 'Applicants'));
                } else {
                    fputcsv($output, array('Date', 'Job Title', 'Views', 'Applicants'));
                }
                
                foreach($this->data['all_jobs'] as $job){
                    $input = array();
                    $city = '';
                    $state='';
                    if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                        $city = ' - '.ucfirst($job['Location_City']);
                    }
                    if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                         $state = ', '.db_get_state_name($job['Location_State'])['state_name'];
                     }
                    $input['date'] = convert_date_to_frontend_format($job['activation_date']);
                    $input['job_title'] = $job['Title'].$city.$state ;
                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        $input['company_name'] = $job['company_title'];
                    }
                    $input['views'] = $job['views'];
                    $input['applicants'] = $job['applicant_count'];
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/job_views_report');
    }

}
