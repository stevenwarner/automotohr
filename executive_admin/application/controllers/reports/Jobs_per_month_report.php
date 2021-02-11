<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Jobs_per_month_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Jobs Per Month Report';
            $data['company_sid'] = $company_sid;
            $status = 0;
            //**** working code ****//
            if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
                $search_data = $this->input->get(NULL, true);
                $status = $this->input->get('status');
                $year = $search_data['year'];
                $data['flag'] = true;
                $data['search'] = $search_data;
            } else {
                $year = date("Y");
                $data['search'] = false;
            }
            $data['status'] = $status;
            $jan_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-01-' . $year, '31-01-' . $year, $status);

            if (is_leap_year($year)) {
                $feb_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-02-' . $year, '29-02-' . $year,$status);
            } else {
                $feb_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-02-' . $year, '28-02-' . $year,$status);
            }

            $mar_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-03-' . $year, '31-03-' . $year,$status);
            $apr_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-04-' . $year, '30-04-' . $year,$status);
            $may_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-05-' . $year, '31-05-' . $year,$status);
            $jun_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-06-' . $year, '30-06-' . $year,$status);
            $jul_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-07-' . $year, '31-07-' . $year,$status);
            $aug_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-08-' . $year, '31-08-' . $year,$status);
            $sep_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-09-' . $year, '30-09-' . $year,$status);
            $oct_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-10-' . $year, '31-10-' . $year,$status);
            $nov_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-11-' . $year, '30-11-' . $year,$status);
            $dec_jobs = $this->Reports_model->get_all_hired_jobs($company_sid, '01-12-' . $year, '31-12-' . $year,$status);

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

            $data['jobs'] = $jobs;
            $data['chart_data'] = json_encode($chart_data);
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['jobs']) && sizeof($data['jobs'] > 0)) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    foreach ($data['jobs'] as $month => $jobList) {
                        fputcsv($output, array($month));
                        fputcsv($output, array('Job Title', 'Filled Date'));

                        if (sizeof($jobList) > 0) {
                            foreach ($jobList as $job) {
                                $input = array();
                                $input['Title'] = $job['Title'];
                                $input['hired_date'] = $job['hired_date'];
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
            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/jobs_per_month_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
