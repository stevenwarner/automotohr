<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_per_month_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $brand_sid = 'all', $status = 0, $year = 'all') {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'jobs_per_month_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $brand_sid = urldecode($brand_sid);
        $company_sid = urldecode($company_sid);
        $status = urldecode($status);
        $year = urldecode($year);

        if($brand_sid!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        }
        else{
            $this->data['company_or_brand'] = 'company';
            $brand_sid = NULL;
        }

        $this->data['page_title'] = 'Advanced Hr Reports - Closed / Filled Jobs Per Month';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));
        if($company_sid != 'all' || $brand_sid != 'all' && $brand_sid != NULL){
            $jan_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-01-' . $year, '31-01-' . $year, $status);
            if (is_leap_year($year)) {
                $feb_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-02-' . $year, '29-02-' . $year, $status);
            } else {
                $feb_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-02-' . $year, '28-02-' . $year, $status);
            }

            $mar_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-03-' . $year, '31-03-' . $year, $status);
            $apr_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-04-' . $year, '30-04-' . $year, $status);
            $may_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-05-' . $year, '31-05-' . $year, $status);
            $jun_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-06-' . $year, '30-06-' . $year, $status);
            $jul_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-07-' . $year, '31-07-' . $year, $status);
            $aug_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-08-' . $year, '31-08-' . $year, $status);
            $sep_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-09-' . $year, '30-09-' . $year, $status);
            $oct_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-10-' . $year, '31-10-' . $year, $status);
            $nov_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-11-' . $year, '30-11-' . $year, $status);
            $dec_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-12-' . $year, '31-12-' . $year, $status);

            $chart_data = array(
                array('January', count($jan_jobs)),
                array('February', count($feb_jobs)),
                array('March', count($mar_jobs)),
                array('April', count($apr_jobs)),
                array('May', count($may_jobs)),
                array('June', count($jun_jobs)),
                array('July', count($jul_jobs)),
                array('August', count($aug_jobs)),
                array('September', count($sep_jobs)),
                array('October', count($oct_jobs)),
                array('November', count($nov_jobs)),
                array('December', count($dec_jobs))
            );

            $jobs = array(
                'january' => $jan_jobs,
                'february' => $feb_jobs,
                'march' => $mar_jobs,
                'april' => $apr_jobs,
                'may' => $may_jobs,
                'june' => $jun_jobs,
                'july' => $jul_jobs,
                'august' => $aug_jobs,
                'september' => $sep_jobs,
                'october' => $oct_jobs,
                'november' => $nov_jobs,
                'december' => $dec_jobs
            );

            $this->data['jobs'] = $jobs;
            $this->data['chart_data'] = json_encode($chart_data);
            $this->data['flag'] = true;
        }

//        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
//            $search_data = $this->input->get(NULL, true);
//            $year = $search_data['year'];
//            // ** select company or brand sid ** //
//            if ($search_data['company_or_brand'] == 'company') {
//                $company_sid = $search_data['company_sid'];
//                $brand_sid = NULL;
//            } else {
//                $brand_sid = $search_data['brand_sid'];
//                $company_sid = NULL;
//            }
//            // ** select company or brand sid **//
//            //** loading jobs by month **//
//            $jan_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-01-' . $year, '31-01-' . $year);
//
//            if (is_leap_year($year)) {
//                $feb_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-02-' . $year, '29-02-' . $year);
//            } else {
//                $feb_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, '01-02-' . $year, '28-02-' . $year);
//            }
//
//            $mar_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-03-' . $year, '31-03-' . $year);
//            $apr_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-04-' . $year, '30-04-' . $year);
//            $may_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-05-' . $year, '31-05-' . $year);
//            $jun_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-06-' . $year, '30-06-' . $year);
//            $jul_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-07-' . $year, '31-07-' . $year);
//            $aug_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-08-' . $year, '31-08-' . $year);
//            $sep_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-09-' . $year, '30-09-' . $year);
//            $oct_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-10-' . $year, '31-10-' . $year);
//            $nov_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-11-' . $year, '30-11-' . $year);
//            $dec_jobs = $this->advanced_report_model->get_all_hired_jobs($company_sid, $brand_sid, '01-12-' . $year, '31-12-' . $year);
//
//            $chart_data = array(
//                array('January', count($jan_jobs)),
//                array('February', count($feb_jobs)),
//                array('March', count($mar_jobs)),
//                array('April', count($apr_jobs)),
//                array('May', count($may_jobs)),
//                array('June', count($jun_jobs)),
//                array('July', count($jul_jobs)),
//                array('August', count($aug_jobs)),
//                array('September', count($sep_jobs)),
//                array('October', count($oct_jobs)),
//                array('November', count($nov_jobs)),
//                array('December', count($dec_jobs))
//            );
//
//            $jobs = array(
//                'january' => $jan_jobs,
//                'february' => $feb_jobs,
//                'march' => $mar_jobs,
//                'april' => $apr_jobs,
//                'may' => $may_jobs,
//                'june' => $jun_jobs,
//                'july' => $jul_jobs,
//                'august' => $aug_jobs,
//                'september' => $sep_jobs,
//                'october' => $oct_jobs,
//                'november' => $nov_jobs,
//                'december' => $dec_jobs
//            );
//
//            $this->data['jobs'] = $jobs;
//            $this->data['chart_data'] = json_encode($chart_data);
//            //** loading jobs by month **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['jobs']) && sizeof($this->data['jobs'] > 0)) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                foreach ($this->data['jobs'] as $month => $jobList) {
                    fputcsv($output, array($month));

                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        fputcsv($output, array('Job Title', 'Filled Date', 'Company Name'));
                    } else {
                        fputcsv($output, array('Job Title', 'Filled Date'));
                    }

                    if (sizeof($jobList) > 0) {
                        foreach ($jobList as $job) {
                            $input = array();
                            $city='';
                            $state='';
                            if(isset($job['Location_City']) && $job['Location_City']!=''){
                               $city=' - '.$job['Location_City'];
                            }
                            if(isset($job['Location_State']) && $job['Location_State']!=''){
                               $state=', '.db_get_state_name($job['Location_State'])['state_name'];
                            }
                            $input['Title'] = $job['Title'].$city.$state;
                            $input['hired_date'] = $job['hired_date'];
                            if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                                $input['CompanyName'] = $job['CompanyName'];
                            }
                            fputcsv($output, $input);
                        }
                    } else {
                        fputcsv($output, array('No Jobs Found.'));
                    }
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/jobs_per_month_report');
    }

}