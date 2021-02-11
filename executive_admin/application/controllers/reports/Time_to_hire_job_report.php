<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Time_to_hire_job_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $keyword = 'all') {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicants Report - Time To Hire';
            $data['company_sid'] = $company_sid;

            $keyword = urldecode($keyword);

            //**** working code ****//
            $jobs = $this->Reports_model->GetAllJobsCompanySpecific($company_sid, $keyword);
            $average_difference = 0;

            foreach ($jobs as $jobKey => $job) {
                $applicants = $this->Reports_model->GetAllApplicantsCompanyEmployerAndJobSpecific($company_sid, $job['sid']);
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

            $data['jobs'] = $jobs;
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['jobs']) && sizeof($data['jobs']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                    fputcsv($output, array('Job Title', 'Job Date', 'Applicants', 'Average Days To Hire'));

                    foreach ($data['jobs'] as $job) {
                        $input = array();
                        $input['Title'] = $job['Title'];
                        $input['job_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $job['activation_date'])));
                        $input['applicant_count'] = $job['applicant_count'];
                        $input['average_days_to_hire'] = $job['average_days_to_hire'];
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/time_to_hire_job_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
