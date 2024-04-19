<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Employees_termination_report extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/employees_termination_report_model');
        $this->load->library("pagination");
        $this->load->library('ion_auth');
    }

    public function index($company_sid ='all', $start_date = 'all', $end_date = 'all', $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'employees_termination_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        //
        $this->data['flag'] = false;
        //
        $company_sid = urldecode($company_sid);
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        $this->data['page_title'] = 'Employees Termination Report';
        //
        $baseUrl = base_url('manage_admin/reports/employees_termination_report') . '/' . $company_sid . '/'. urlencode($start_date) . '/' . urlencode($end_date);
        //
        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? NULL : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
        } else {
            $start_date_applied = date('Y-m-d');
        }
        //
        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? NULL : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
        } else {
            $end_date_applied = date('Y-m-d');
        }
        //
        if($company_sid == 'all'){
            $company_sid = NULL;
        }
        //
        $between = '';
        //
        if ($start_date_applied != NULL && $end_date_applied != NULL) {
            $between = "terminated_employees.termination_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
        }
        //
        $this->data["flag"] = true;
        
        //
        $this->data['terminatedEmployeesCount'] = sizeof($this->employees_termination_report_model->getTerminatedEmployees($company_sid, $between, null, null));
        /** pagination * */
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $my_offset = 0;
        //
        if($page_number > 1){
            $my_offset = ($page_number - 1) * $records_per_page;
        }
        //
        $uri_segment = 7;
        $config = array();
        $config["base_url"] = $baseUrl;
        $config["total_rows"] = $this->data['terminatedEmployeesCount'];
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = ceil($choice);
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '<i class="fa fa-angle-right"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["links"] = $this->pagination->create_links();
        $total_records = $this->data['products_count'];

        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;
       
        $this->data['active_companies'] = $this->employees_termination_report_model->getAllActiveCompanies();
        $this->data['terminatedEmployees'] = $this->employees_termination_report_model->getTerminatedEmployees($company_sid, $between, $records_per_page, $my_offset);
        $allTerminatedEmployees = $this->employees_termination_report_model->getTerminatedEmployees($company_sid, $between, null, null);

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if(isset($allTerminatedEmployees) && sizeof($allTerminatedEmployees) > 0){

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=employee_terminated_report.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array('Name', 'Employee ID', 'Job Title', 'Department', 'Hire Date', 'Last Day Worked', 'Termination Reason'));

                foreach($allTerminatedEmployees as $terminatedEmployee){
                    //
                    $employeeName = remakeEmployeeName([
                        'first_name' => $terminatedEmployee['first_name'],
                        'last_name' => $terminatedEmployee['last_name'],
                        'access_level' => $terminatedEmployee['access_level'],
                        'timezone' => isset($terminatedEmployee['timezone']) ? $terminatedEmployee['timezone'] : '',
                        'access_level_plus' => $terminatedEmployee['access_level_plus'],
                        'is_executive_admin' => $terminatedEmployee['is_executive_admin'],
                        'pay_plan_flag' => $terminatedEmployee['pay_plan_flag'],
                        'job_title' => $terminatedEmployee['job_title'],
                    ]); 
                    //
                    $hireDate = get_employee_latest_joined_date(
                        $terminatedEmployee['registration_date'],
                        $terminatedEmployee['joined_at'],
                        $terminatedEmployee['rehire_date'],
                        false
                    );
                    //
                    $reason = $terminatedEmployee['termination_reason'];
                    //
                    if ($reason == 1) {
                        $terminatedReason = 'Resignation';
                    } else if ($reason == 2) {
                        $terminatedReason = 'Fired';
                    } else if ($reason == 3) {
                        $terminatedReason = 'Tenure Completed';
                    } else if ($reason == 4) {
                        $terminatedReason = 'Personal';
                    } else if ($reason == 5) {
                        $terminatedReason = 'Personal';
                    } else if ($reason == 6) {
                        $terminatedReason = 'Problem with Supervisor';
                    } else if ($reason == 7) {
                        $terminatedReason = 'Relocation';
                    } else if ($reason == 8) {
                        $terminatedReason = 'Work Schedule';
                    } else if ($reason == 9) {
                        $terminatedReason = 'Retirement';
                    } else if ($reason == 10) {
                        $terminatedReason = 'Return to School';
                    } else if ($reason == 11) {
                        $terminatedReason = 'Pay';
                    } else if ($reason == 12) {
                        $terminatedReason = 'Without Notice/Reason';
                    } else if ($reason == 13) {
                        $terminatedReason = 'Involuntary';
                    } else if ($reason == 14) {
                        $terminatedReason = 'Violating Company Policy';
                    } else if ($reason == 15) {
                        $terminatedReason = 'Attendance Issues';
                    } else if ($reason == 16) {
                        $terminatedReason = 'Performance';
                    } else if ($reason == 17) {
                        $terminatedReason = 'Workforce Reduction';
                    } elseif ($reason == 18) {
                        $terminatedReason = 'Store Closure';
                    } else {
                        $terminatedReason = 'N/A';
                    }
                    //
                    $input = array();
                    $input['employee_name'] = $employeeName;
                    $input['employee_id'] = "AHR-".$terminatedEmployee['sid'];
                    $input['job_title'] = $terminatedEmployee['job_title'];
                    $input['department'] = getDepartmentNameBySID($terminatedEmployee['department_sid']);
                    $input['hire_date'] = $hireDate;
                    $input['last_day_worked'] = $terminatedEmployee['termination_date'];
                    $input['termination_reason'] = $terminatedReason;
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/employees_termination_report/employees_termination_report');
    }

}
