<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_offers_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicant Offers Report';
            $data['company_sid'] = $company_sid;
            $data['companyName'] = getCompanyNameBySid($company_sid);

            //**** working code ****//
            $data['flag'] = false;
            $this->form_validation->set_data($this->input->get(NULL, true));
            $start_date = date('Y-m-d 00:00:00');
            $end_date = date('Y-m-d 23:59:59');
            $keyword = 'all';

            if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
                $search_data = $this->input->get(NULL, true);

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

            $data['candidates'] = $this->Reports_model->get_candidate_offers($company_sid, $start_date, $end_date, $keyword);

            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['candidates']) && sizeof($data['candidates']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, ['Company Name' , getCompanyNameBySid($company_sid)]);

                    fputcsv($output, array('Offer Date', 'Job Title', 'Applicant Name', 'Email', 'Employee Type'));

                    foreach ($data['candidates'] as $candidate) {
                        $input = array();

                        $input['offer_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $candidate['registration_date'])));
                        $input['job_title'] = $candidate['job_title'];
                        $input['applicant_name'] = ucwords($candidate['first_name'] . ' ' . $candidate['last_name']);
                        $input['email'] = $candidate['email'];
                        $input['employee_type'] = ucwords($candidate['access_level']);

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
            $this->load->view('reports/applicant_offers_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
