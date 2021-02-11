<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Time_to_hire_job_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $brand_sid = 'all', $all = 1, $page_number = 1) {

        // Time to fill and Time to hire reports are same so redirect it
        redirect(base_url('manage_admin/reports/time_to_fill_job_report/'), "refresh");
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'time_to_hire_job_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $brand_sid = urldecode($brand_sid);
        $company_sid = urldecode($company_sid);

        $pagination_base = base_url('manage_admin/reports/time_to_hire_job_report') . '/' . $company_sid . '/' . $brand_sid . '/' . urlencode($all);

        if($brand_sid!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        }
        elseif($company_sid!='all'){
            $this->data['company_or_brand'] = 'company';
            $brand_sid = NULL;
        }
        else {
            $this->data['company_or_brand'] = 'all';
            $company_sid = NULL;
            $brand_sid = NULL;
        }

        //-----------------------------------Pagination Starts----------------------------//
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }
        $total_records = $this->advanced_report_model->GetAllJobsCompanySpecific($company_sid, $brand_sid, '', 1, $all);

        $this->load->library('pagination');

        //echo $pagination_base;

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

        $this->data['page_title'] = 'Advanced Hr Reports - Time To Hire';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));

        if($company_sid != 'all' || $brand_sid != 'all' && $brand_sid != NULL){
            $this->data['flag'] = true;
        }
        //** get jobs **//
        $jobs = $this->advanced_report_model->GetAllJobsCompanySpecific($company_sid, $brand_sid, '', 0, $all, $per_page, $offset);
        $average_difference = 0;
        foreach ($jobs as $jobKey => $job) {
            $applicants = $this->advanced_report_model->GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $brand_sid, $job['sid']);
            foreach ($applicants as $appkey => $applicant) {
                $applicationDate = strtotime(str_replace('-', '/', $applicant['date_applied']));
                $hiredDate = strtotime(str_replace('-', '/', $applicant['hired_date']));
                $differenceUnix = $hiredDate - $applicationDate;

                if ($differenceUnix < 0) {
                    $differenceUnix = 0;
                }

                $applicants[$appkey]['unix_diff'] = $differenceUnix;
                $difference = ceil(intval($differenceUnix) / (60 * 60 * 24));
                $applicants[$appkey]['days_to_hire'] = $difference;
                $average_difference += intval($difference);
            }

            if (count($applicants) > 0) {
                $average_difference = ceil($average_difference / count($applicants));
            } else {
                $average_difference = 0;
            }

            $jobs[$jobKey]['average_days_to_hire'] = $average_difference;
            $jobs[$jobKey]['applicant_count'] = count($applicants);
            $jobs[$jobKey]['applicants'] = $applicants;
        }
        $this->data['applicants_count'] = $total_records;

        $this->data['jobs'] = $jobs;

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
//            //** get jobs **//
//            $jobs = $this->advanced_report_model->GetAllJobsCompanySpecific($company_sid, $brand_sid);
//            $average_difference = 0;
//
//            foreach ($jobs as $jobKey => $job) {
//                $applicants = $this->advanced_report_model->GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $brand_sid, $job['sid'], 1);
//                foreach ($applicants as $appkey => $applicant) {
//                    $applicationDate = strtotime(str_replace('-', '/', $applicant['date_applied']));
//                    $hiredDate = strtotime(str_replace('-', '/', $applicant['hired_date']));
//                    $differenceUnix = $hiredDate - $applicationDate;
//
//                    if ($differenceUnix < 0) {
//                        $differenceUnix = 0;
//                    }
//
//                    $applicants[$appkey]['unix_diff'] = $differenceUnix;
//                    $difference = ceil(intval($differenceUnix) / (60 * 60 * 24));
//                    $applicants[$appkey]['days_to_hire'] = $difference;
//                    $average_difference += intval($difference);
//                }
//
//                if (count($applicants) > 0) {
//                    $average_difference = ceil($average_difference / count($applicants));
//                } else {
//                    $average_difference = 0;
//                }
//
//                $jobs[$jobKey]['average_days_to_hire'] = $average_difference;
//                $jobs[$jobKey]['applicant_count'] = count($applicants);
//                $jobs[$jobKey]['applicants'] = $applicants;
//            }
//
//            $this->data['jobs'] = $jobs;
//            //** get jobs **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['jobs']) && sizeof($this->data['jobs']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                    fputcsv($output, array('Job Title', 'Job Date', 'Applicants', 'Average Days To Hire', 'Company Name'));
                } else {
                    fputcsv($output, array('Job Title', 'Job Date', 'Applicants', 'Average Days To Hire'));
                }

                foreach ($this->data['jobs'] as $job) {
                    $input = array();
                    $input['Title'] = $job['Title'];
                    $input['job_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $job['activation_date'])));
                    $input['applicant_count'] = $job['applicant_count'];
                    $input['average_days_to_hire'] = $job['average_days_to_hire'];
                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        $input['CompanyName'] = $job['CompanyName'];
                    }
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/time_to_hire_job_report');
    }

}
