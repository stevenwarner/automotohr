<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Weekly_inactivity_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/employer_login_duration_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'weekly_inactivity_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Weekly Inactivity Report';

        if (isset($_POST['export']) && $_POST['export'] == 'export_data') {
            $week_span = $_POST['excel_week_span'];
            
            $dates = explode('-', $week_span);
            $start_date = trim($dates[0]);
            $end_date = trim($dates[1]);
            
            $start_date = new DateTime($start_date);
            $end_date = new DateTime($end_date);
            $week_start = $start_date->format('Y-m-d');
            $week_start = $week_start . ' 00:00:00';
            $week_end = $end_date->format('Y-m-d');
            $week_end = $week_end . ' 23:59:59';

            $companies = $this->employer_login_duration_model->get_all_companies();

            if (!empty($companies)) {
                foreach ($companies as $key => $company) {
                    $company_sid = $company['sid'];
                    $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $week_start, $week_end);
                    $companies[$key]['inactive_employers'] = $report_data;
                }
            }
            
            /** download file * */
            if (sizeof($companies) > 0) {
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
            
                foreach ($companies as $company) {
                    if (isset($company['inactive_employers']) && sizeof($company['inactive_employers']) > 0) {
                        fputcsv($output, array(ucwords($company['CompanyName'])));
                        fputcsv($output, array('Job Title', 'Access Level', 'Name', 'Email', 'Phone'));
                        foreach ($company['inactive_employers'] as $employer) {
                            $job_title = (isset($employer['job_title']) && $employer['job_title'] != '') ? $employer['job_title'] : 'Not Available';
                            $phone_number = (isset($employer['PhoneNumber']) && $employer['PhoneNumber'] != '') ? $employer['PhoneNumber'] : 'Not Available';
                            fputcsv($output, array($job_title, $employer['access_level'], $employer['first_name'] . ' ' . $employer['last_name'], $employer['email'], $phone_number));
                        }
                        fputcsv($output, array());
                        fputcsv($output, array());
                    }
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No data found');
            }
            /** download file * */
        }

        $this->render('manage_admin/reports/weekly_inactivity_report', 'admin_master');
    }

    public function ajax_responder() {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_companies'); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        $perform_action = $this->input->post('perform_action');
        //print_r($this->input->post);


        switch ($perform_action) {
            case 'get_weekly_activity':
                $this->form_validation->set_rules('week_span', 'week_span', 'required|trim|xss_clean');
                $this->form_validation->set_rules('start_date', 'start_date', 'required|trim|xss_clean');
                $this->form_validation->set_rules('end_date', 'end_date', 'required|trim|xss_clean');
                break;
            default:
                //do nothing
                break;
        }

        if ($this->form_validation->run() == false) {
            
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_weekly_inactivity':
                    $week_span = $this->input->post('week_span');

                    $start_date = $this->input->post('start_date');
                    $end_date = $this->input->post('end_date');


                    $start_date = new DateTime($start_date);

                    $end_date = new DateTime($end_date);

                    $week_start = $start_date->format('Y-m-d');
                    $week_start = $week_start . ' 00:00:00';

                    $week_end = $end_date->format('Y-m-d');
                    $week_end = $week_end . ' 23:59:59';

                    $companies = $this->employer_login_duration_model->get_all_companies();

                    if (!empty($companies)) {
                        foreach ($companies as $key => $company) {
                            $company_sid = $company['sid'];

                            $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $week_start, $week_end);

                            $companies[$key]['inactive_employers'] = $report_data;
                        }
                    }

                    $my_data['companies'] = $companies;

                    $this->load->view('manage_admin/reports/inactivity_report_partial', $my_data);

                    //print_r($return_data);

                    break;

                case 'get_all_active_companies':
                    //
                    $start_date = $this->input->post('start_date');
                    $end_date = $this->input->post('end_date');
                    //
                    $companies = $this->employer_login_duration_model->get_all_companies("sid, CompanyName");
                    $data['companies'] = $companies;
                    $data['start_date'] = $start_date;
                    $data['end_date'] = $end_date;
                    //
                    $this->load->view('manage_admin/reports/weekly_inactivity_report_partial', $data);
                    break;

                case 'get_company_employee_report':
                    //
                    $company_sid = $this->input->post('company_sid');
                    //
                    $start_date = $this->input->post('start_date');
                    $end_date = $this->input->post('end_date');
                    //
                    $start_date = new DateTime($start_date);
                    $end_date = new DateTime($end_date);
                    //
                    $week_start = $start_date->format('Y-m-d');
                    $week_start = $week_start . ' 00:00:00';

                    $week_end = $end_date->format('Y-m-d');
                    $week_end = $week_end . ' 23:59:59';
                    //
                    $column = ["job_title", "access_level", "first_name", "last_name", "email", "PhoneNumber"];
                    $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $week_start, $week_end, $column);
                    res(['data'=>array_values($report_data)]);
                    //  
                    break;    
                        
                default:
                    //do nothing
                    break;
            }
        }
    }

}
