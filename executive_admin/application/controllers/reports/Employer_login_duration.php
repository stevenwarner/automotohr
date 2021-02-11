<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employer_login_duration extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Employer Login Duration Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'generate_csv_file':
                    $this->form_validation->set_rules('company_sid', 'company_sid', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('employer_sid', 'employer_sid', 'required|xss_clean|trim');
                    break;
            }

            if ($this->form_validation->run() == false) {
                
            } else {
                $perform_action = $this->input->post('perform_action');

                switch ($perform_action) {
                    case 'generate_csv_file':
                        $employer_sid = $this->input->post('employer_sid');
                        $daily_activities = array();

                        for ($dCount = 1; $dCount <= date('t'); $dCount++) {
                            for ($hCount = 0; $hCount < 24; $hCount++) {
                                $hour_activity = $this->Reports_model->get_activity_log($employer_sid, date('Y'), date('m'), $dCount, $hCount);
                                if (!empty($hour_activity)) {
                                    $hour_activity = $hour_activity[0];
                                } else {
                                    $hour_activity = array();
                                }

                                $daily_activities[$dCount][] = $hour_activity;
                            }
                        }


                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=data.csv');
                        $output = fopen('php://output', 'w');
                        fputcsv($output, array('Hours', '', 'Days'));
                        $header_days = array('', '');

                        for ($headHCount = 0; $headHCount < 24; $headHCount++) {
                            $header_days[] = str_pad($headHCount, 2, '0', STR_PAD_LEFT);
                        }

                        fputcsv($output, $header_days);

                        foreach ($daily_activities as $key => $daily_activity) {
                            $row_data = array('', $key);

                            foreach ($daily_activity as $key => $hourly_activity) {
                                if (!empty($hourly_activity)) {
                                    $row_data[] = 'A';
                                } else {
                                    $row_data[] = '';
                                }
                            }
                            fputcsv($output, $row_data);
                        }

                        fclose($output);
                        exit;

                        break;
                }
            }
            //**** working code ****//

            $this->load->view('main/header', $data);
            $this->load->view('reports/employer_login_duration');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function ajax_responder() {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_company_users':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                break;
            case 'get_login_duration_log':
                $this->form_validation->set_rules('employer_sid', 'employer_sid', 'required|trim|xss_clean');
                break;

            default:
                break;
        }

        $data['employers'] = array();

        if ($this->form_validation->run() == false) {            
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_company_users':
                    $company_sid = $this->input->post('company_sid');
                    $employers = $this->Reports_model->get_all_employers($company_sid);

                    if (!empty($employers)) {
                        echo '<option value="">Please Select</option>' . PHP_EOL;
                        foreach ($employers as $employer) {
                            echo '<option value="' . $employer['sid'] . '">' . ucwords($employer['first_name'] . ' ' . $employer['last_name']) . '</option>' . PHP_EOL;
                        }
                    } else {
                        echo '<option value="">Please Select Company</option>' . PHP_EOL;
                    }

                    break;
                case 'get_login_duration_log':
                    $employer_sid = $this->input->post('employer_sid');

                    $daily_activities = array();

                    for ($dCount = 1; $dCount <= date('t'); $dCount++) {
                        for ($hCount = 0; $hCount < 24; $hCount++) {
                            $hour_activity = $this->Reports_model->get_activity_log($employer_sid, date('Y'), date('m'), $dCount, $hCount);
                            if (!empty($hour_activity)) {
                                $hour_activity = $hour_activity[0];
                            } else {
                                $hour_activity = array();
                            }

                            $daily_activities[$dCount][] = $hour_activity;
                        }
                    }
                    echo '<table class="table table-bordered table-hover table-striped">';
                    echo '<thead>';
                    echo '<th colspan="2" rowspan="2"></th>';
                    echo '<th colspan="24" class="text-center text-success">Hours</th>';
                    echo '<tr>';

                    for ($headHCount = 0; $headHCount < 24; $headHCount++) {
                        echo '<th class="text-center">' . str_pad($headHCount, 2, 0, STR_PAD_LEFT) . '</th>';
                    }
                    echo '</tr>';
                    echo '</thead>';

                    echo '<tbody>';
                    echo '<th rowspan="' . (intval(date('t')) + 1) . '" class="text-center" style="vertical-align:middle;"><span class="duration-days-strip text-success">Days</span></th>';
                    foreach ($daily_activities as $key => $daily_activity) {
                        echo '<tr>';
                        echo '<th class="text-center">' . str_pad($key, 2, 0, STR_PAD_LEFT) . '</th>';
                        foreach ($daily_activity as $hourly_activity) {
                            if (!empty($hourly_activity)) {
                                echo '<td class="text-center">' . '<div class="btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Active">A</div>' . '</td>';
                            } else {
                                echo '<td class="text-center"></td>';
                            }
                        }
                        echo '</tr>';
                    }
                    echo '</tbody>';
                    echo '</table>';

                    break;
                default:
                    break;
            }
        }
    }

}
