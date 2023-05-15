<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Interviews_report extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library('pagination');
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Interviews Report';
            $data['company_sid'] = $company_sid;
            $company_users = $this->Reports_model->GetAllUsers($company_sid);

            $data['companyName'] = getCompanyNameBySid($company_sid);

            foreach ($company_users as $key => $user) {
                $employer_events = $this->Reports_model->GetAllEventsByCompanyAndEmployer($user['parent_sid'], $user['sid']);
                $company_users[$key]['events'] = $employer_events;
            }

            $data['users_events'] = $company_users;

            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['users_events']) && sizeof($data['users_events'] > 0)) {
                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                    fputcsv($output, ['Company Name' , getCompanyNameBySid($company_sid)]);

                    foreach ($data['users_events'] as $user_event) {
                        fputcsv($output, array(ucwords($user_event['first_name'] . ' ' . $user_event['last_name'])));
                        fputcsv($output, array('Interview Scheduled For', 'Interview Date'));

                        if (sizeof($user_event['events']) > 0) {
                            foreach ($user_event['events'] as $event) {
                                $input = array();
                                $input['name'] = ucwords($event['applicant_first_name'] . ' ' . $event['applicant_last_name']);
                                $input['date'] = date('m/d/Y', strtotime($event['date']));
                                fputcsv($output, $input);
                            }
                        } else {
                            fputcsv($output, array('No Interviews Found.'));
                        }
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export sheet file * */
//            $applicant_statuses = $this->Reports_model->get_company_statuses($company_sid);
            $have_status = $this->Reports_model->have_status_records($company_sid);
            $data['have_status'] = $have_status;
//            $data['applicant_statuses'] = $applicant_statuses;
            $this->load->view('main/header', $data);
            $this->load->view('reports/interviews_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
