<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Turnover_cost_calculator extends CI_Controller {
    public function __construct() {
        parent::__construct();

        $this->load->model('home_model');
        $this->load->model('turnover_cost_calculator_model');
    }

    public function index() {
        redirect(base_url(), 'refresh');
        $data = array();
        $data['session'] = $this->session->userdata('logged_in');

        if ($data['session']) {
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
        }

        $this->form_validation->set_rules('dealership_name', 'Dealership Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|xss_clean|trim');
        $this->form_validation->set_rules('number_of_employees', 'Number of Employees', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('number_of_sales_rep', 'Number of Sales Reps', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('emp_annual_turnover', 'Employee Annual Turnover', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('sales_rep_annual_turnover', 'Sales Reps. Annual Turnover', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('emp_average_annual_salary', 'Employee average annual Salary', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('sales_rep_average_annual_salary', 'Sales Rep. average annual Salary', 'required|xss_clean|trim|numeric');
        $this->form_validation->set_rules('additional_costs_percentage', 'Additional Cost Percentage', 'required|xss_clean|trim|numeric');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Turnover Cost Calculator';
            //Getting customize home page Data Starts
            $data['home_page'] = $this->home_model->get_home_page_data();
            //Getting customize home page Data Ends
            $user_ip = getUserIP();
            $job_session = 'tcc_' . $user_ip . '_' . date('y_m_d_H');
            
            if (!$this->session->userdata($job_session)) {
                $this->turnover_cost_calculator_model->update_page_visit_count();
                $this->session->set_userdata($job_session, true);
            }

            $this->load->view('main/header', $data);
            $this->load->view('turnover_cost_calculator/index');
            $this->load->view('main/footer');
        } else {
            $dealership_name = $this->input->post('dealership_name');
            $first_name = $this->input->post('first_name');
            $last_name = $this->input->post('last_name');
            $email = $this->input->post('email');
            $number_of_employees = $this->input->post('number_of_employees');
            $number_of_sales_rep = $this->input->post('number_of_sales_rep');
            $emp_annual_turnover = $this->input->post('emp_annual_turnover');
            $sales_rep_annual_turnover = $this->input->post('sales_rep_annual_turnover');
            $emp_average_annual_salary = $this->input->post('emp_average_annual_salary');
            $sales_rep_average_annual_salary = $this->input->post('sales_rep_average_annual_salary');
            $additional_costs_percentage = $this->input->post('additional_costs_percentage');
            $employees_left = round(( $number_of_employees * $emp_annual_turnover ) / 100, 0);
            $employees_left_salary = $employees_left * $emp_average_annual_salary;
            $employees_turnover_cost = $employees_left_salary - round($employees_left_salary * ( $additional_costs_percentage / 100 ), 0);
            $sales_rep_left = round(( $number_of_sales_rep * $sales_rep_annual_turnover ) / 100, 0);
            $sales_rep_left_salary = $sales_rep_left * $sales_rep_average_annual_salary;
            $sales_rep_turnover_cost = $sales_rep_left_salary - round($sales_rep_left_salary * ( $additional_costs_percentage / 100 ), 0);
            $reduced_percentage = 10;
            $employees_left_reduced = 0;
            $employees_left_salary_reduced = 0;
            $employees_turnover_cost_reduced = 0;
            $emp_annual_turnover_reduced = $emp_annual_turnover - $reduced_percentage;

            if ($emp_annual_turnover > 10) {
                $employees_left_reduced = round(($number_of_employees * $emp_annual_turnover_reduced) / 100, 0);
                $employees_left_salary_reduced = $employees_left_reduced * $emp_average_annual_salary;
                $employees_turnover_cost_reduced = $employees_left_salary_reduced - round($employees_left_salary_reduced * ( $additional_costs_percentage / 100 ), 0);
            }

            $sales_rep_left_reduced = 0;
            $sales_rep_left_salary_reduced = 0;
            $sales_rep_turnover_cost_reduced = 0;
            $sales_rep_annual_turnover_reduced = $sales_rep_annual_turnover - $reduced_percentage;

            if ($sales_rep_annual_turnover > 10) {
                $sales_rep_left_reduced = round(( $number_of_sales_rep * $sales_rep_annual_turnover_reduced) / 100, 0);
                $sales_rep_left_salary_reduced = $sales_rep_left_reduced * $sales_rep_average_annual_salary;
                $sales_rep_turnover_cost_reduced = $sales_rep_left_salary_reduced - round($sales_rep_left_salary_reduced * ( $additional_costs_percentage / 100 ), 0);
            }

            $data_to_insert = array();
            $data_to_insert['dealership_name'] = $dealership_name;
            $data_to_insert['first_name'] = $first_name;
            $data_to_insert['last_name'] = $last_name;
            $data_to_insert['email'] = $email;
            $data_to_insert['number_of_employees'] = $number_of_employees;
            $data_to_insert['number_of_sales_reps'] = $number_of_sales_rep;
            $data_to_insert['employee_annual_turnover_percentage'] = $emp_annual_turnover;
            $data_to_insert['sales_reps_annual_turnover_percentage'] = $sales_rep_annual_turnover;
            $data_to_insert['employee_average_annual_salary'] = $emp_average_annual_salary;
            $data_to_insert['sales_rep_average_annual_salary'] = $sales_rep_average_annual_salary;
            $data_to_insert['additional_cost_percentage'] = $additional_costs_percentage;
            $data_to_insert['calculated_employee_turnover_cost'] = $employees_turnover_cost;
            $data_to_insert['calculated_sales_rep_turnover_cost'] = $sales_rep_turnover_cost;
            $data_to_insert['date_created'] = date('Y-m-d H:i:s');
            $data_to_insert['random_secure_key'] = random_key(40);
            $data_to_insert['number_of_employees_left'] = $employees_left;
            $data_to_insert['number_of_sales_reps_left'] = $sales_rep_left;
            $data_to_insert['employee_annual_turnover_percentage_reduced'] = $emp_annual_turnover_reduced > 0 ? $emp_annual_turnover_reduced : 0;
            $data_to_insert['sales_reps_annual_turnover_percentage_reduced'] = $sales_rep_annual_turnover_reduced > 0 ? $sales_rep_annual_turnover_reduced : 0;
            $data_to_insert['calculated_employee_turnover_cost_reduced'] = $employees_turnover_cost_reduced;
            $data_to_insert['calculated_sales_rep_turnover_cost_reduced'] = $sales_rep_turnover_cost_reduced;
            $data_to_insert['number_of_employees_left_reduced'] = $employees_left_reduced;
            $data_to_insert['number_of_sales_reps_left_reduced'] = $sales_rep_left_reduced;
            $record_id = $this->turnover_cost_calculator_model->insert_turnover_cost_calculator_record($data_to_insert);
            $secure_key = $this->turnover_cost_calculator_model->get_turnover_cost_calculation_key($record_id);
            $report_link = anchor(base_url('turnover_cost_calculator/report/' . $secure_key), 'Click Here', 'target="_blank"');
            $replacement_array = array();
            $replacement_array['store_name'] = STORE_NAME;
            $replacement_array['dealership_name'] = $dealership_name;
            $replacement_array['turnover_cost_calculation_report_link'] = $report_link;
            $replacement_array['client_name'] = trim(ucwords($first_name . ' ' . $last_name));
            log_and_send_templated_email(TURNOVER_COST_CALCULATION_REPORT_EMAIL, $email, $replacement_array);
            redirect('turnover_cost_calculator/report/' . $secure_key, 'refresh');
        }
    }

    public function report($secure_key = null) {
        redirect(base_url(), 'refresh');
        
        if ($secure_key != null) {
            $data = array();
            $data['session'] = $this->session->userdata('logged_in');

            if ($data['session']) {
                $security_sid = $data['session']['employer_detail']['sid'];
                $security_details = db_get_access_level_details($security_sid);
                $data['security_details'] = $security_details;
            }

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Turnover Cost Calculator';
                //Getting customize home page Data Starts
                $data['home_page'] = $this->home_model->get_home_page_data();
                //Getting customize home page Data Ends
                $cost_record = $this->turnover_cost_calculator_model->get_turnover_cost_calculation_record($secure_key);
                $data['cost_record'] = $cost_record;
                $this->load->view('main/header', $data);
                $this->load->view('turnover_cost_calculator/report');
                $this->load->view('main/footer');
            } else {
                
            }
        } else {
            redirect('turnover_cost_calculator', 'refresh');
        }
    }
}