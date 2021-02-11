<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Daily_activity_detailed_overview_report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        // $this->load->library("pagination");
        $this->load->model('Reports_model');
    }
    public function index($company_sid) {
        
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Daily Activity Detailed Overview Report Including Executive Admins';
            $data['company_sid'] = $company_sid;
            
            //**** working code ****//
            
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|xss_clean|trim');
            
            if ($this->form_validation->run() == false) {
             
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'export_csv_file':
                        
                        $report_date = $this->input->post('report_date');
                        $my_date = new DateTime($report_date);
                        $start_date = $my_date->format('Y-m-d');
                        $start_date = $start_date . ' 00:00:00';
                        $end_date = $my_date->format('Y-m-d');
                        $end_date = $end_date . ' 23:59:59';
                        
                        $companies = $this->Reports_model->get_company_activity_overview($company_sid, $start_date, $end_date, true);
                        
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=data.csv');
                        $output = fopen('php://output', 'w');
                        
                        foreach ($companies as $company) {
                            
                            fputcsv($output, array('', $company['CompanyName']));
                            fputcsv($output, array('', 'Job Title', 'Access Level', 'Employer Name', 'Email', 'Phone', 'Total Time', 'IP Address', 'Time Spent', 'User Agent'));
                            $inactive_employers = $company['inactive_employers'];
                            $active_employers = $company['active_employers'];
                            
                            if (!empty($active_employers)) {
                                foreach ($active_employers as $active_employer) {
                                    $job_title = ($active_employer['job_title'] != '' ? ucwords($active_employer['job_title']) : 'Not Available');
                                    if ($active_employer['is_executive_admin'] == 1) {
                                        $access_level = 'Executive Admin';
                                    } else {
                                        $access_level = ucwords($active_employer['access_level']);
                                    }
                                    $employer_name = ucwords($active_employer['first_name'] . ' ' . $active_employer['last_name']);
                                    $employer_email = $active_employer['email'];
                                    $employer_phone = ($active_employer['PhoneNumber'] == '' ? 'Not Available' : $active_employer['PhoneNumber']);
                                    if (!empty($active_employer['details'])) {
                                        $details = $active_employer['details'];
                                        $total_time_spent = $details['total_time_spent'] . 'Mins';
                                        $logs = $details['activity_logs'];
                                        fputcsv($output, array('Active', $job_title, $access_level, $employer_name, $employer_email, $employer_phone, $total_time_spent, '', '', ''));
                                        foreach ($logs as $log_key => $log) {
                                            $IP_address = str_replace('_', '.', $log_key);
                                            $time_spent = $log['time_spent'];
                                            $user_agent = $log['act_details']['user_agent'];
                                            fputcsv($output, array('', '=>', '=>', '=>', '=>', '=>', '=>', $IP_address, $time_spent, $user_agent));
                                        }
                                        fputcsv($output, array(''));
                                    } else {
                                        $activity_details = 'No Activity';
                                        fputcsv($output, array(''));
                                    }
                                }
                            } else {
                                fputcsv($output, array('Active', 'No Active Employers'));
                            }
                            fputcsv($output, array(''));
                            fputcsv($output, array(''));
                            if (!empty($inactive_employers)) {
                                fputcsv($output, array('', 'Job Title', 'Access Level', 'Employer Name', 'Email', 'Phone', 'Total Time', 'IP Address', 'Time Spent', 'User Agent'));
                                foreach ($inactive_employers as $inactive_employer) {
                                    $job_title = ($inactive_employer['job_title'] != '' ? ucwords($inactive_employer['job_title']) : 'Not Available');
                                    if ($inactive_employer['is_executive_admin'] == 1) {
                                        $access_level = 'Executive Admin';
                                    } else {
                                        $access_level = ucwords($inactive_employer['access_level']);
                                    }
                                    $employer_name = ucwords($inactive_employer['first_name'] . ' ' . $inactive_employer['last_name']);
                                    $employer_email = $inactive_employer['email'];
                                    $employer_phone = ($inactive_employer['PhoneNumber'] == '' ? 'Not Available' : $inactive_employer['PhoneNumber']);
                                    fputcsv($output, array('Inactive', $job_title, $access_level, $employer_name, $employer_email, $employer_phone, 'No Activity', 'No Activity', 'No Activity', 'No Activity'));
                                }
                            } else {
                                $activity_details = 'No Activity';
                            }
                            fputcsv($output, array(''));
                            fputcsv($output, array(''));
                            fputcsv($output, array(''));
                            fputcsv($output, array(''));
                        }
                        fclose($output);
                        exit;
                        break;
                    default:
                        break;
                }
            }
            //**** working code ****//
            $this->load->view('main/header', $data);
            $this->load->view('reports/daily_activity_detailed_overview_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
    
    public function ajax_responder() {
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');
        switch ($perform_action) {
            case 'get_daily_activity_detailed_overview':
                $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                break;
            default:
                break;
        }
        if ($this->form_validation->run() == false) {
            
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_daily_activity_detailed_overview':
                    
                    $company_sid = $this->input->post('company_sid');
                    $report_date = $this->input->post('report_date');
                    $my_date = new DateTime($report_date);
                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';
                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';
                    
                    $companies = $this->Reports_model->get_company_activity_overview($company_sid, $start_date, $end_date, true);
                    $my_data['companies'] = $companies;
                    
                    $this->load->view('reports/activity_detailed_overview_report_partial', $my_data);
                    break;
                default:
                    break;
            }
        }
    }
}
