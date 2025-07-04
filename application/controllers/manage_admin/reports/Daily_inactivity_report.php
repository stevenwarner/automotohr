<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_inactivity_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/employer_login_duration_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'daily_inactivity_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Daily Inactivity Report';

        if (isset($_POST['export']) && $_POST['export'] == 'export_data') {
            $report_date = $_POST['excel_date'];
            $my_date = new DateTime($report_date);

            $start_date = $my_date->format('Y-m-d');
            $start_date = $start_date . ' 00:00:00';

            $end_date = $my_date->format('Y-m-d');
            $end_date = $end_date . ' 23:59:59';

            $companies = $this->employer_login_duration_model->get_all_companies();

            if (!empty($companies)) {
                foreach ($companies as $key => $company) {
                    $company_sid = $company['sid'];
                    $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $start_date, $end_date);
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

        $this->render('manage_admin/reports/daily_inactivity_report', 'admin_master');
    }

    public function ajax_responder() {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_companies'); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');
        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_daily_inactivity':
                $this->form_validation->set_rules('report_date', 'report_date', 'required|trim|xss_clean');
                break;
            default:
                //do nothing
                break;
        }

        if ($this->form_validation->run() == false) {
            
        } else {
            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_daily_inactivity':

                    $report_date = $this->input->post('report_date');

                    $my_date = new DateTime($report_date);

                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';

                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';

                    $companies = $this->employer_login_duration_model->get_all_companies();

                    if (!empty($companies)) {
                        foreach ($companies as $key => $company) {
                            $company_sid = $company['sid'];
                            $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $start_date, $end_date);
                            $companies[$key]['inactive_employers'] = $report_data;
                        }
                    }
                    $my_data['companies'] = $companies;
                    $this->load->view('manage_admin/reports/inactivity_report_partial', $my_data);
                    break;

                case 'get_all_active_companies':
                    //
                    $report_date = $this->input->post('report_date');
                    //
                    $companies = $this->employer_login_duration_model->get_all_companies("sid, CompanyName");
                    $data['companies'] = $companies;
                    $data['report_date'] = $report_date;
                    //
                    $this->load->view('manage_admin/reports/inactivity_report_partial_new', $data);
                    break;

                case 'get_company_employee_report':
                    //
                    $report_date = $this->input->post('report_date');
                    $company_sid = $this->input->post('company_sid');
                    //
                    $my_date = new DateTime($report_date);
                    //
                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';
                    //
                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';
                    //
                    $column = ["job_title", "access_level", "first_name", "last_name", "email", "PhoneNumber"];
                    $report_data = $this->employer_login_duration_model->get_all_inactive_employees($company_sid, $start_date, $end_date, $column);
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
