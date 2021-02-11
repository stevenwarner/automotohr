<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_source_report_daily extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($filter=null) {
        $redirect_url                                                           = 'manage_admin';
        $function_name                                                          = 'daily_applicant_source_report';
        $admin_id                                                               = $this->ion_auth->user()->row()->id;
        $security_details                                                       = db_get_admin_access_level_details($admin_id);
        $this->data['security_details']                                         = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title']                                               = 'Daily Based Applicants Source Report';  
        $filter_button_url                                                      = site_url('manage_admin/reports/applicant_source_report_daily/yesterday'); 
        $filter_button_text                                                     = "Fetch Yesterday's List"; 
        $filter_date                                                            = date('Y-m-d');
        $today_start                                                            = date('Y-m-d').' 00:00:01';
        $today_end                                                              = date('Y-m-d').' 23:59:59';
        $yesterday_date                                                         = date('Y-m-d',strtotime("-1 days"));
        $yesterday_start                                                        = $yesterday_date.' 00:00:01';
        $yesterday_end                                                          = $yesterday_date.' 23:59:59';
        $filter_start_date                                                      = $today_start;
        $filter_end_date                                                        = $today_end;
//        $filter_start_date                                                      = '2016-01-01 00:00:00';
//        $filter_end_date                                                        = '2019-12-10 00:00:00';
        
        if($filter=='yesterday'){
            $filter_start_date                                                  = $yesterday_start;
            $filter_end_date                                                    = $yesterday_end;
            $filter_date                                                        = $yesterday_date;
            $filter_button_url                                                  = site_url('manage_admin/reports/applicant_source_report_daily'); 
            $filter_button_text                                                 = "Fetch Today's List"; 
        }
        
        $all_applicants                                                         = $this->advanced_report_model->get_total_job_applications($filter_start_date,$filter_end_date);

        /*
        $companies                                                              = $this->advanced_report_model->get_all_companies();
        $all_jobs                                                               = $this->advanced_report_model->get_active_jobs();
        $active_companies                                                       = array();
        $active_jobs                                                            = array();
        
        foreach($companies as $active_company){
                $sid                                                            = $active_company['sid'];
                $name                                                           = $active_company['CompanyName'];
                $active_companies[$sid]                                         =  $name;          
        }
        
        foreach($all_jobs as $active_job){
                $sid                                                            = $active_job['sid'];
                $title                                                          = $active_job['Title'];
                $active_jobs[$sid]                                              =  $title;          
        }

        $this->data['active_jobs']                                              = $active_jobs;
        $this->data['active_companies']                                         = $active_companies;
        */
        $cityChart = array();
        $tempArr = array();
        $cityChart[0] = array("City", "Count");
        foreach($all_applicants as $applicant){
            if(!in_array($applicant['city'], $tempArr) && !empty(trim($applicant['city'])) && $applicant['city'] != NULL){
                $cityChart[] = array($applicant['city'], intval($this->advanced_report_model->get_total_job_applications($filter_start_date,$filter_end_date,$applicant['city'])));
                $tempArr[] = $applicant['city'];
            }
        }
        $stateChart = array();
        $tempArr = array();
        $stateChart[0] = array("State", "Count");
        foreach($all_applicants as $applicant){
            if(!in_array($applicant['state'], $tempArr) && !empty(trim($applicant['state'])) && $applicant['state'] != NULL){
                $stateChart[] = array($applicant['state_name'], intval($this->advanced_report_model->get_total_job_applications($filter_start_date,$filter_end_date,NULL,$applicant['state'])));
                $tempArr[] = $applicant['state'];
            }
        }


        $this->data['all_applicants']                                           = $all_applicants;
        $this->data['applicant_count']                                          = sizeof($all_applicants);
        $this->data['citychart']                                                = json_encode($cityChart);
        $this->data['statechart']                                               = json_encode($stateChart);
        $this->data['filter_date']                                              = $filter_date;
        $this->data['filter_button_url']                                        = $filter_button_url;
        $this->data['filter_button_text']                                       = $filter_button_text;
        
        $this->form_validation->set_data($this->input->get(NULL, true));
        $this->render('manage_admin/reports/applicant_source_report_daily');
    }

    public function ajax_responder() {
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = $_POST['perform_action'];
            switch ($perform_action) {
                case 'load_jobs':
                    $company_sid = $this->input->post('company_sid');
                    $company_jobs = $this->advanced_report_model->get_company_jobs($company_sid);
                    echo json_encode($company_jobs);
                    break;
                default:
                    break;
            }
        }
    }

}