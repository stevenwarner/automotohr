<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_status_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('reports_model');
    }

    public function index($company_sid, $keyword = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $page_number = 1) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicant Status Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            /*
            $data['flag'] = false;
            $this->form_validation->set_data($this->input->get(NULL, true));
            $start_date = '2015-01-01 00:00:00';
            $end_date = date('Y-m-d H:i:s');

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

                $data['flag'] = true;
                $data['search'] = $search_data;
            }

            $data['applicants'] = $this->Reports_model->get_applicants_status($company_sid, $start_date, $end_date);
            $data['have_status'] = $this->Reports_model->check_company_status($company_sid);
            */
            //**** working code ****//

            $keyword = urldecode($keyword);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            if(!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if(!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            //-----------------------------------Pagination Starts----------------------------//
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;
            if($page_number > 1){
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->reports_model->get_applicants($company_sid, $keyword, 0, 'all', $applicant_status, $start_date_applied, $end_date_applied, true);

            $this->load->library('pagination');

            $pagination_base = base_url('reports/applicant_status_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date);

            //echo $pagination_base;

            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 8;
            $config["num_links"] = 8;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
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

            //$config['page_query_string'] = true;
            //$config['reuse_query_string'] = true;
            //$config['query_string_segment'] = 'page_number';

            $this->pagination->initialize($config);
            $data["page_links"] = $this->pagination->create_links();

            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

            //-----------------------------------Pagination Ends-----------------------------//


            $applicants = $this->reports_model->get_applicants($company_sid, $keyword, 'all', 'all', $applicant_status, $start_date_applied, $end_date_applied, false, $per_page, $offset);

            //echo $this->db->last_query();
            $data['applicants'] = $applicants;
            $data['applicants_count'] = $total_records;

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['applicants']) && sizeof($data['applicants']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                    fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email', 'Status'));
                    
                    foreach ($data['applicants'] as $applicant) {
                        $input = array();
                        $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                        $input['Title'] = $applicant['Title'];
                        $input['email'] = $applicant['email'];
                        $input['status'] = ucwords($applicant['status']);
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }

            $applicant_statuses = $this->reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;

            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/applicant_status_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
