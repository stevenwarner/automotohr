<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_between_period_report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $start_date = 'all', $end_date = 'all', $keyword = 'all', $page_number = 1)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicants Between Period Report';
            $data['company_sid'] = $company_sid;

            $data['companyName'] = getCompanyNameBySid($company_sid);


            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);
            $keyword = urldecode($keyword);

            $data['title'] = 'Applicants Between ( ' . ($start_date != 'all' ? $start_date : 'Beginning') . ' - ' . ($end_date != 'all' ? $end_date : 'Today') . ' )';

            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            //-----------------------------------Pagination Starts----------------------------//
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            $offset = 0;
            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            $total_records = $this->Reports_model->get_applicants($company_sid, $keyword, 'all', 'all', 'all', $start_date_applied, $end_date_applied, true);

            $this->load->library('pagination');

            $pagination_base = base_url('reports/applicants_between_period_report') . '/' . $company_sid . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($keyword);

            //echo $pagination_base;

            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 7;
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

            $applicants = $this->Reports_model->get_applicants($company_sid, $keyword, 'all', 'all', 'all', $start_date_applied, $end_date_applied, false, $per_page, $offset);

            $data['applicants'] = $applicants;

            $data['applicants_count'] = $total_records;

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['applicants']) && sizeof($data['applicants']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');
                   
                    fputcsv($output, ['Company Name' , getCompanyNameBySid($company_sid)]);

                    fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date'));

                    foreach ($data['applicants'] as $applicant) {
                        $input = array();
                        $input['Title'] = $applicant['Title'];
                        $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                        $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
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
            $this->load->view('reports/applicants_between_period_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
