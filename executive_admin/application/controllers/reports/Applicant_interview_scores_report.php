<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_interview_scores_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $keyword = 'all') {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Advanced Hr Reports - Applicant Interview Scores';
            $data['company_sid'] = $company_sid;

            $keyword = urldecode($keyword);
            
            $data['companies_applicant_scores'] = $this->Reports_model->get_applicant_interview_scores($company_sid,$keyword);
            
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['companies_applicant_scores']) && sizeof($data['companies_applicant_scores']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                    
                    fputcsv($output, array('Total : '.count($data['companies_applicant_scores']).' Applicant Interview(s)'));
                    fputcsv($output, array('Interview Date', 'Applicant', 'Conducted By', 'For Position', 'Applicant Evaluation Score', 'Job Relevancy Evaluation Score', 'Applicant Overall Score', 'Job Relevancy Overall Score', 'Star Rating'));
                    
                    if (!empty($data['companies_applicant_scores'])) {
                        foreach ($data['companies_applicant_scores'] as $applicant_score) {
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
                        fputcsv($output, array('No Applicants Found'));
                    }
                    
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('reports/applicant_interview_scores_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
