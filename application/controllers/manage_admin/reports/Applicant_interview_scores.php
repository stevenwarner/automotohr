<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Applicant_interview_scores extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/advanced_report_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }
    public function index($brand_sid, $company_sid) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicant_interview_scores';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Advanced Hr Reports - Applicant Interview Scores';
        $companies = $this->advanced_report_model->get_all_companies();
        $this->data['companies'] = $companies;
        $brands = $this->advanced_report_model->get_all_oem_brands();
        $this->data['brands'] = $brands;
        if($brand_sid == 'all'){
            $brand_sid = 0;
        }
        if($company_sid == 'all'){
            $company_sid = 0;
        }
        if($brand_sid == 0){
            $companies_for_report = $companies;
        } else {
            $companies_for_report = $this->advanced_report_model->get_brand_companies_with_full_details($brand_sid);
        }
        $companies_applicant_scores = array();
        if($company_sid == 0){
            foreach($companies_for_report as $company){
                $applicant_scores = $this->advanced_report_model->get_applicant_interview_scores($company['sid']);
                $company_data = array();
                $company_data['company_info'] = $company;
                $company_data['applicant_scores'] = $applicant_scores;
                $companies_applicant_scores[] = $company_data;
            }
        } else {
            $company_info = get_company_details($company_sid);
            $applicant_scores = $this->advanced_report_model->get_applicant_interview_scores($company_sid);
            $company_data = array();
            $company_data['company_info'] = $company_info;
            $company_data['applicant_scores'] = $applicant_scores;
            $companies_applicant_scores[] = $company_data;
        }
        $this->data['companies_applicant_scores'] = $companies_applicant_scores;
        
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['companies_applicant_scores']) && sizeof($this->data['companies_applicant_scores']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                
                foreach($this->data['companies_applicant_scores'] as $company_applicant_scores) {
                    $company_info = $company_applicant_scores['company_info']; 
                    $applicant_scores = $company_applicant_scores['applicant_scores']; 
                    
                    fputcsv($output, array('Company Name : '.ucwords($company_info['CompanyName'])));
                    fputcsv($output, array('Total : '.count($applicant_scores).' Applicant Interview(s)'));
                    fputcsv($output, array());
                    fputcsv($output, array('Interview Date', 'Applicant', 'Conducted By', 'For Position', 'Applicant Evaluation Score', 'Job Relevancy Evaluation Score', 'Applicant Overall Score', 'Job Relevancy Overall Score', 'Star Rating'));
                    
                    if(!empty($applicant_scores)) {
                        foreach ($applicant_scores as $key => $applicant_score) {
                            $input = array();
                            $input['created_date'] = convert_date_to_frontend_format($applicant_score['created_date']);
                            $input['applicant'] = ucwords($applicant_score['first_name'] . ' ' . $applicant_score['last_name']);
                            $input['conducted_by'] = ucwords($applicant_score['conducted_by_first_name'] . ' ' . $applicant_score['conducted_by_last_name']) . ' ( ' . ucwords($applicant_score['conducted_by_job_title']) . ' ) ';
                            $input['for_position'] = ucwords($applicant_score['job_title']);
                            $input['applicant_evaluation_score'] = $applicant_score['candidate_score'] . ' Point(s) ( Out of 100 )';
                            $input['job_relevancy_evaluation_score'] = $applicant_score['job_relevancy_score'] . ' Point(s) ( Out of 100 )';
                            $input['applicant_overall_score'] = ($applicant_score['candidate_overall_score'] * 10) . ' Point(s) ( Out of 100 )';
                            $input['job_relevancy_overall_score'] = ($applicant_score['job_relevancy_overall_score'] * 10) . ' Point(s) ( Out of 100 )';
                            $input['star_rating'] = $applicant_score['star_rating'] . '/5';
                            fputcsv($output, $input);
                        }
                    } else {
                        fputcsv($output, array('No Applicants'));
                    }
                    fputcsv($output, array());
                }
                
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }
        
        $this->render('manage_admin/reports/applicant_interview_scores');
    }
}
