<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_status_report extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/job_status_report_model');
        $this->load->library("pagination");
        $this->load->library('ion_auth');
    }
    
    public function index($status = 'active_organic_jobs',$company_sid = 'all') {

        $this->data['page_title'] = 'All Job Status';

        $active_jobs = array();
        $inactive_jobs = array();
        $active_organic_jobs = array();

        $card_type = 'Active Organic Jobs';
        $active_type = 'active_organic_jobs';

        $this->data['companies'] = $this->job_status_report_model->get_all_companies();

        if ($status == 'active_organic_jobs' || $status == NULL) {
            $count = $this->job_status_report_model->get_active_organic_jobs(1,$company_sid);
        } elseif ($status == 'active_jobs') {
            $count = $this->job_status_report_model->get_active_jobs(1,$company_sid);
        } elseif ($status == 'inactive_jobs') {
            $count = $this->job_status_report_model->get_inactive_jobs(1,$company_sid);
        }

        /** pagination * */
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 1;
        $my_offset = 0;

        if ($page > 1) {
            $my_offset = ($page - 1) * $records_per_page;
        }

        $baseUrl = base_url('manage_admin/reports/job_status_report/' . $status . '/' . $company_sid);
        $uri_segment = 6;
        $config = array();
        $config["base_url"] = $baseUrl;
        $config["total_rows"] = $count;
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $config["num_links"] = 8;
//        $choice = $config["total_rows"] / $config["per_page"];
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '&raquo;';
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
        $this->data['references_count'] = $count;
        /** pagination end * */

        if ($status == 'active_organic_jobs' || $status == NULL) {
            $active_organic_jobs = $this->job_status_report_model->get_active_organic_jobs(0, $company_sid, $records_per_page, $my_offset);
            $card_type = 'Active Organic';
            $active_type = 'active_organic_jobs';
        } elseif ($status == 'active_jobs') {
            $active_jobs = $this->job_status_report_model->get_active_jobs(0, $company_sid, $records_per_page, $my_offset);
            $card_type = 'Active';
            $active_type = 'active_jobs';
        } elseif ($status == 'inactive_jobs') {
            $inactive_jobs = $this->job_status_report_model->get_inactive_jobs(0, $company_sid, $records_per_page, $my_offset);
            $card_type = 'In-Active';
            $active_type = 'inactive_jobs';
        }

        $this->data['references_count'] = $count;
        $this->data['active_jobs'] = $active_jobs;
        $this->data['inactive_jobs'] = $inactive_jobs;
        $this->data['active_organic_jobs'] = $active_organic_jobs;

        $this->data['card_type'] = $card_type;
        $this->data['active'] = $active_type;

        $this->render('manage_admin/reports/job_status_report');
    }

    /*public function index($flag = NULL,$start_date = NULL,$end_date = NULL) {

        $this->data['page_title'] = 'All Job Status';

        if($start_date == NULL){
            $start_date = date('Y-m-d 00:00:00');
        }
        if($end_date == NULL){
            $end_date = date('Y-m-d 23:59:59');
        }

        $active_jobs = array();
        $inactive_jobs = array();
        $active_organic_jobs = array();

        $card_type = 'Active Organic Jobs';
        $active_type = 'active_organic_jobs';

        if ($flag == 'active_organic_jobs' || $flag == NULL) {
            $active_organic_jobs = $this->job_status_report_model->get_active_organic_jobs($start_date,$end_date);
            $this->data['references_count'] = sizeof($active_organic_jobs);
            $card_type = 'Active Organic Jobs';
            $active_type = 'active_organic_jobs';
        } elseif ($flag == 'active_jobs') {
            $active_jobs = $this->job_status_report_model->get_active_jobs($start_date,$end_date);
            $this->data['references_count'] = sizeof($active_jobs);
            $card_type = 'Active';
            $active_type = 'active_jobs';
        } elseif ($flag == 'inactive_jobs') {
            $inactive_jobs = $this->job_status_report_model->get_inactive_jobs($start_date,$end_date);
            $this->data['references_count'] = sizeof($inactive_jobs);
            $card_type = 'In-Active';
            $active_type = 'inactive_jobs';
        }

        $this->data['active_jobs'] = $active_jobs;
        $this->data['inactive_jobs'] = $inactive_jobs;
        $this->data['active_organic_jobs'] = $active_organic_jobs;

        $this->data['card_type'] = $card_type;
        $this->data['active'] = $active_type;

        $this->render('manage_admin/reports/job_status_report');
    } */

}
