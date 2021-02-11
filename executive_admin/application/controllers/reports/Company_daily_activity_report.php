<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company_daily_activity_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Company Daily Activity Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            // $data['companies'] = $this->Reports_model->get_all_companies();
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'export_csv_file':
                    $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                    $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                    break;
            }

            if ($this->form_validation->run() == false) {
                
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'export_csv_file':

                        $company_sid = $this->input->post('company_sid');
                        $report_date = $this->input->post('report_date');

                        $my_date = new DateTime($report_date);

                        $start_date = $my_date->format('Y-m-d');
                        $start_date = $start_date . ' 00:00:00';

                        $end_date = $my_date->format('Y-m-d');
                        $end_date = $end_date . ' 23:59:59';

                        $employers = $this->Reports_model->generate_activity_log_data_for_view($company_sid, $start_date, $end_date);

                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=data.csv');
                        $output = fopen('php://output', 'w');

                        foreach ($employers as $employer) {
                            fputcsv($output, array($employer['employer_name'], '', $employer['total_time_spent'] . ' Minutes'));
                            fputcsv($output, array('IP Address', 'Login Duration', 'User Agent'));
                            foreach ($employer['activity_logs'] as $key => $activity_log) {
                                fputcsv($output, array(str_replace('_', '.', $key), $activity_log['time_spent'] . ' Minutes', $activity_log['act_details']['user_agent']));
                            }
                            fputcsv($output, array('', '', ''));
                        }

                        fclose($output);

                        exit;
                        break;
                }
            }

            //**** working code ****//

            $this->load->view('main/header', $data);
            $this->load->view('reports/company_daily_activity_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder() {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_employers_daily_activity':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                break;
            default:
                break;
        }

        if ($this->form_validation->run() == false) {
            
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_employers_daily_activity':
                    $company_sid = $this->input->post('company_sid');
                    $report_date = $this->input->post('report_date');

                    $my_date = new DateTime($report_date);
                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';
                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';
                    $report_data = $this->Reports_model->generate_activity_log_data_for_view($company_sid, $start_date, $end_date);

                    $my_data['employers_logs'] = $report_data;
                    $my_data['report_type'] = 'daily';
                    $my_data['company_sid'] = $company_sid;
                    $my_data['report_date'] = $report_date;
                    $this->load->view('reports/company_activity_report_partial', $my_data);

                    break;
                default:
                    break;
            }
        }
    }

}
