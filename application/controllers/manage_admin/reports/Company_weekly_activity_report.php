<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company_weekly_activity_report extends Admin_Controller
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
        $function_name      = 'company_weekly_activity_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $companies = $this->employer_login_duration_model->get_all_companies();
        $this->data['companies'] = $companies;

        $week_dropdown_data = array();

        $my_date = new DateTime();
        for($iCount = 1; $iCount <= 53; $iCount++){

            $my_date->setISODate(date('Y'), $iCount);

            $week_start = $my_date->format('l m/d/Y');


            $my_date->modify('+6 days');

            $week_end = $my_date->format('l m/d/Y');

            $week_dropdown_data[] = array('week' => $iCount, 'dates' => str_pad($iCount, 2, '0', STR_PAD_LEFT) . ' ( ' . $week_start . ' - ' . $week_end . ' )');
        }

        $this->data['weeks'] = $week_dropdown_data;
        $this->data['page_title'] = 'Company Weekly Activity Report';

        $perform_action = $this->input->post('perform_action');

        switch($perform_action){
            case 'export_csv_file':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                $this->form_validation->set_rules('start_date', 'report_date', 'required|trim|xss_clean');
                $this->form_validation->set_rules('end_date', 'report_date', 'required|trim|xss_clean');
                break;
        }

        if($this->form_validation->run() == false){
            //Do Nothing
        } else {
            $perform_action = $this->input->post('perform_action');

            switch($perform_action){
                case 'export_csv_file':
                    $company_sid = $this->input->post('company_sid');

                    $week_span = $this->input->post('week_span');

                    $start_date = $this->input->post('start_date');
                    $end_date = $this->input->post('end_date');


                    $start_date = new DateTime($start_date);

                    $end_date = new DateTime($end_date);

                    $week_start = $start_date->format('Y-m-d');
                    $week_start = $week_start . ' 00:00:00';

                    $week_end = $end_date->format('Y-m-d');
                    $week_end = $week_end . ' 23:59:59';


                    //$this->employer_login_duration_model->generate_activity_log_data_for_view($company_sid, '2016/11/09 00:00:00', '2016/11/09 23:59:59');
                    $employers = $this->employer_login_duration_model->generate_activity_log_data_for_view($company_sid, $week_start, $week_end);

                    /*echo '<pre>';
                    print_r($employers);
                    exit;*/


                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    foreach($employers as $employer){
                        fputcsv($output, array($employer['employer_name'] ,'' , $employer['total_time_spent'] . ' Minutes'));
                        fputcsv($output, array('IP Address', 'Login Duration', 'User Agent'));
                        foreach($employer['activity_logs'] as $key => $activity_log){
                            fputcsv($output, array(str_replace('_', '.', $key), $activity_log['time_spent'] . ' Minutes', $activity_log['act_details']['user_agent']));
                        }
                        fputcsv($output, array('', '', ''));
                    }

                    fclose($output);

                    exit;
                    break;
            }
        }

        $this->render('manage_admin/reports/company_weekly_activity_report', 'admin_master');
    }

    public function ajax_responder()
    {
        $admin_id = $this->session->userdata('user_id');
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        //check_access_permissions($security_details, 'manage_admin', 'list_companies'); // Param2: Redirect URL, Param3: Function Name

        $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

        $perform_action = $this->input->post('perform_action');
        //print_r($this->input->post);
        
        
        switch ($perform_action) {
            case 'get_employers_weekly_activity':
                $this->form_validation->set_rules('company_sid', 'company_sid', 'required|trim|xss_clean');
                $this->form_validation->set_rules('week_span', 'week_span', 'required|trim|xss_clean');
                break;
            default:
                //do nothing
                break;
        }

        if ($this->form_validation->run() == false) {

        } else {

            $perform_action = $this->input->post('perform_action');
            switch ($perform_action) {
                case 'get_employers_weekly_activity':
                    $company_sid = $this->input->post('company_sid');

                    $week_span = $this->input->post('week_span');

                    $start_date = $this->input->post('start_date');
                    $end_date = $this->input->post('end_date');


                    $start_date = new DateTime($start_date);

                    $end_date = new DateTime($end_date);

                    $week_start = $start_date->format('Y-m-d');
                    $week_start = $week_start . ' 00:00:00';

                    $week_end = $end_date->format('Y-m-d');
                    $week_end = $week_end . ' 23:59:59';


                    //$this->employer_login_duration_model->generate_activity_log_data_for_view($company_sid, '2016/11/09 00:00:00', '2016/11/09 23:59:59');
                    $report_data = $this->employer_login_duration_model->generate_activity_log_data_for_view($company_sid, $week_start, $week_end);

                    $my_data['employers_logs'] = $report_data;
                    $my_data['report_type'] = 'weekly';
                    $my_data['company_sid'] = $company_sid;
                    $my_data['start_date'] = $this->input->post('start_date');
                    $my_data['end_date'] = $this->input->post('end_date');

                    $this->load->view('manage_admin/reports/company_activity_report_partial', $my_data);

                    //print_r($return_data);

                    break;
                default:
                    //do nothing
                    break;
            }
        }

    }


}
