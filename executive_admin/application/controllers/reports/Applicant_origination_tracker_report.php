<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_origination_tracker_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $source = 'all', $startdate = 'all', $enddate = 'all', $keyword = 'all', $page_number = 1) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicant Origination Tracking Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            $source = urldecode($source);
            $start_date = urldecode($startdate);
            $end_date = urldecode($enddate);
            $keyword = urldecode($keyword);
            $data['flag'] = false;
            $this->form_validation->set_data($this->input->get(NULL, true));

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

            $per_page = PAGINATION_RECORDS_PER_PAGE;

            $offset = 0;
            if($page_number > 1){
                $offset = ($page_number - 1) * $per_page;
            }

            $data['flag'] = true;
            $applicant_sources = array();
            $applicant_sources[] = 'automotosocial';
            $applicant_sources[] = 'career_website';
            $applicant_sources[] = 'glassdoor';
            $applicant_sources[] = 'indeed';
            $applicant_sources[] = 'juju';
            $applicant_sources[] = 'jobs2careers';
            $applicant_sources[] = 'ziprecruiter';
            $data['applicant_sources'] = $applicant_sources;

            $applicants = $this->Reports_model->get_applicants_by_source($company_sid, $source, $start_date_applied, $end_date_applied, $keyword, false, $per_page, $offset);
            $data['applicants'] = $applicants;
            //**** working code ****//

            $total_records = $this->Reports_model->get_applicants_by_source($company_sid, $source, $start_date_applied, $end_date_applied, $keyword, true);

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['companies_applicants_by_source']) && sizeof($data['companies_applicants_by_source']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    $company_applicants_by_source = $data['companies_applicants_by_source'];
                    $company_info = $company_applicants_by_source['company_info'];
                    $applicants_by_source = $company_applicants_by_source['applicants_by_source'];
                    fputcsv($output, array('Company Name : ' . ucwords($company_info['CompanyName'])));
                    fputcsv($output, array());
                    if (!empty($applicants_by_source)) {
                        foreach ($applicants_by_source as $key => $source_applicants) {
                            fputcsv($output, array(ucwords(str_replace('_', ' ', $key))));
                            fputcsv($output, array('Total : ' . count($source_applicants) . ' Applicant(s)'));
                            fputcsv($output, array('Application Date', 'Applicant Name', 'Job Title', 'Email'));
                            if (!empty($source_applicants)) {
                                foreach ($source_applicants as $applicant) {
                                    $input = array();
                                    $input['date_applied'] = convert_date_to_frontend_format($applicant['date_applied']);
                                    $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                                    $input['job_title'] = ucwords($applicant['job_title']);
                                    $input['email'] = ucwords($applicant['email']);
                                    fputcsv($output, $input);
                                }
                            } else {
                                fputcsv($output, array('No Applicants'));
                            }
                            fputcsv($output, array());
                        }
                    } else {
                        fputcsv($output, array('No Applicants'));
                    }
                    fputcsv($output, array());
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export sheet file * */



            $this->load->library('pagination');

            $pagination_base = base_url('reports/applicant_origination_tracker_report') . '/' . $company_sid . '/' . urlencode($source) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($keyword);

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

            $this->pagination->initialize($config);
            $data["page_links"] = $this->pagination->create_links();

            $data['applicants_count'] = $total_records;
            $data['current_page'] = $page_number;
            $data['from_records'] = $offset == 0 ? 1 : $offset;
            $data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;



            $this->load->view('main/header', $data);
            $this->load->view('reports/applicant_origination_tracker_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }



}
