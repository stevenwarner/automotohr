<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_views_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Job Views Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            $all_jobs = $this->Reports_model->get_all_jobs_views_applicants_count($company_sid);
            $data['all_jobs'] = $all_jobs;
            $total_views = 0;
            $total_applicants = 0;

            foreach ($all_jobs as $job) {
                $total_views += intval($job['views']);
                $total_applicants += intval($job['applicant_count']);
            }

            $data['total_views'] = $total_views;
            $data['total_applicants'] = $total_applicants;
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['all_jobs']) && sizeof($data['all_jobs']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, array('Date', 'Job Title', 'Views', 'Applicants'));

                    foreach ($data['all_jobs'] as $job) {
                        $input = array();
                        $input['date'] = convert_date_to_frontend_format($job['activation_date']);
                        $input['job_title'] = $job['Title'];
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

            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/job_views_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
