<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class New_hires_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'New Hires Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            $data['flag'] = false;
            $this->form_validation->set_data($this->input->get(NULL, true));
            $start_date = date('Y-m-d 00:00:00');
            $end_date   = date('Y-m-d 23:59:59');
            $keyword = 'all';

            if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
                $search_data = $this->input->get(NULL, true);
                $company_detail = get_company_details($company_sid);

                if (isset($company_detail['registration_date']) && $company_detail['registration_date'] != '') {
                    $start_date = $company_detail['registration_date'];
                }

                if (isset($search_data['start']) && $search_data['start'] != "") {
                    $start_date = explode('-', $search_data['start']);
                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                }

                if (isset($search_data['end']) && $search_data['end'] != "") {
                    $end_date = explode('-', $search_data['end']);
                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                }

                if (isset($search_data['keyword']) && $search_data['keyword'] != "") {
                    $keyword = $search_data['keyword'];
                }

                $data['flag'] = true;
                $data['search'] = $search_data;
            }

            $data['applicants'] = $this->Reports_model->GetAllApplicantsBetween($company_sid, $keyword, $start_date, $end_date, 1, true);
            $data['title'] = 'Advanced Hr Reports - Applicants Hired Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
            $data['is_hired_report'] = true;
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['applicants']) && sizeof($data['applicants']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date'));
                    }

                    foreach ($data['applicants'] as $applicant) {
                        $input = array();
                        $input['Title'] = $applicant['Title'];
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);

                        if (isset($data['is_hired_report']) && $data['is_hired_report'] == true) {
                            $input['hired_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date'])));
                        } else {
                            $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                        }

                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/new_hires_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
