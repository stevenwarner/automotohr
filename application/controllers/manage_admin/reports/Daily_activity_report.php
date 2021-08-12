<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Daily_activity_report extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/employer_login_duration_model');

        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index()
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'daily_activity_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Daily Activity Report';

        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'export_csv_file':
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
                case 'export_csv_file':

                    $report_date = $this->input->post('report_date');

                    $my_date = new DateTime($report_date);

                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';

                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';

                    $companies = $this->employer_login_duration_model->get_all_companies();

                    if(!empty($companies)){
                        header('Content-Type: text/csv; charset=utf-8');
                        header('Content-Disposition: attachment; filename=data.csv');
                        $output = fopen('php://output', 'w');

                        foreach($companies as $key => $company){
                            $company_sid = $company['sid'];

                            $employers = $this->employer_login_duration_model->generate_activity_log_data_for_view($company_sid, $start_date, $end_date);


                            if(!empty($employers)) {
                                fputcsv($output, array('Company Name', $company['CompanyName']));
                                foreach ($employers as $employer) {
                                    fputcsv($output, array($employer['employer_name'], '', $employer['total_time_spent'] . ' Minutes'));
                                    fputcsv($output, array('IP Address', 'Login Duration', 'User Agent'));
                                    foreach ($employer['activity_logs'] as $key => $activity_log) {
                                        fputcsv($output, array(str_replace('_', '.', $key), $activity_log['time_spent'] . ' Minutes', $activity_log['act_details']['user_agent']));
                                    }
                                    fputcsv($output, array('', '', ''));
                                }
                                fputcsv($output, array(''));
                            }


                        }

                        fclose($output);
                    }


                    exit;

                    break;
                default:
                    //do nothing
                    break;
            }
        }


        $this->render('manage_admin/reports/daily_activity_report', 'admin_master');
    }

    public function ajax_responder()
    {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_companies'); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        $perform_action = $this->input->post('perform_action');

        switch ($perform_action) {
            case 'get_daily_activity':
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
                case 'get_daily_activity':

                    // _e('here 123', true, true);

                    $report_date = $this->input->post('report_date');

                    $my_date = new DateTime($report_date);

                    $start_date = $my_date->format('Y-m-d');
                    $start_date = $start_date . ' 00:00:00';

                    $end_date = $my_date->format('Y-m-d');
                    $end_date = $end_date . ' 23:59:59';

                    // Get company and employee Ids
                    $CE = $this->employer_login_duration_model->GetTrackerCE($start_date, $end_date);

                    $my_data['companies'] = $CE;
                    $my_data['report_date'] = $this->input->post('report_date');
                    $my_data['report_type'] = 'daily';
                    $my_data['start_date'] = $start_date;
                    $my_data['end_date'] = $end_date;
                    
                    
                    echo $this->load->view('manage_admin/reports/activity_report_main_partial', $my_data, true);
                    // echo $this->load->view('manage_admin/reports/activity_report_partial', $my_data, true);

                    break;
                default:
                    //do nothing
                    break;
            }
        }

    }

    //
    function GetEmployeeReport($companyId){
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $records = $this->employer_login_duration_model->GetTrackerReport(
            $post['start_date'],
            $post['end_date'],
            $companyId
        );
        //
        res(['data'=>$records]);
    }


}
