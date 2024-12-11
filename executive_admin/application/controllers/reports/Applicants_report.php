<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Applicants_report extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $page_number = 1)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicants Report';
            $data['company_sid'] = $company_sid;


            $companyinfo = getCompanyInfo($company_sid);
            $data['companyName'] = $companyinfo['company_name'];


            //**** working code ****//
            $data['flag'] = false;

            $data['jobs'] = $this->Reports_model->get_company_jobs($company_sid);

            $keyword = urldecode($keyword);
            $job_sid = urldecode($job_sid);
            $applicant_type = urldecode($applicant_type);
            $applicant_status = urldecode($applicant_status);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

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

            $data['flag'] = true;

            //            $applicant_types = array();
            //            $applicant_types[] = 'Applicant';
            //            $applicant_types[] = 'Talent Network';
            //            $applicant_types[] = 'Manual Candidate';
            //            $applicant_types[] = 'Re-Assigned Candidates';
            //            $applicant_types[] = 'Job Fair';
            $applicant_types = explode(',', APPLICANT_TYPE_ATS);
            $data['applicant_types'] = $applicant_types;

            $applicant_statuses = $this->Reports_model->get_company_statuses($company_sid);
            $data['applicant_statuses'] = $applicant_statuses;

            //-----------------------------------Pagination Starts----------------------------//
            $per_page = PAGINATION_RECORDS_PER_PAGE;
            //$per_page = 2;
            //$page_number = $this->input->get('page_number');
            $offset = 0;
            if ($page_number > 1) {
                $offset = ($page_number - 1) * $per_page;
            }

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            $total_records = $this->Reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, true);

            $this->load->library('pagination');

            $pagination_base = base_url('reports/applicants_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . $job_sid . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date);

            //echo $pagination_base;

            $config = array();
            $config["base_url"] = $pagination_base;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $per_page;
            $config["uri_segment"] = 10;
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

            //$applicants = $this->Reports_model->get_applicants($company_sid, $search_string, $search_string2, $start_date_applied, $end_date_applied, false, $per_page, $offset);
            $applicants = $this->Reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, false, $per_page, $offset);

            $data['applicants'] = $applicants;

            $data['applicants_count'] = $total_records;
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {

                $applicants = $this->Reports_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied);

                if (!empty($applicants)) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, ['Company Name', getCompanyNameBySid($company_sid)]);


                    fputcsv($output, array('Job Title', 'First Name', 'Last Name', 'Email', 'Phone Number', 'Date Applied', 'Applicant Type', 'Questionnaire Score', 'Reviews Score'));

                    foreach ($applicants as $applicant) {
                        $input = array();
                        $input['Title'] = $applicant['Title'];
                        $input['first_name'] = $applicant['first_name'];
                        $input['last_name'] = $applicant['last_name'];
                        $input['email'] = $applicant['email'];
                        $input['phone_number'] = $applicant['phone_number'];
                        $input['date_applied'] = date_with_time($applicant['date_applied']);
                        $input['applicant_type'] = $applicant['applicant_type'];

                        if ($applicant['questionnaire'] == '' || $applicant['questionnaire'] == NULL) {
                            $input['questionnaire_score'] = 'N/A';
                        } else {
                            $result = $applicant['score'];
                            if ($applicant['score'] >= $applicant['passing_score']) {
                                $result .= ' (Pass)';
                            } else {
                                $result .= ' (Fail)';
                            }
                            $input['questionnaire_score'] = $result;
                        }
                        $input['reviews_score'] = $applicant['review_score'] . ' with ' . $applicant['review_count'] . ' Review(s)';
                        if (sizeof($applicant['scores']) > 0) {
                            $score_text = '';
                            foreach ($applicant['scores'] as $score) {
                                $score_text .= 'Employer : ' . ucwords($score['first_name'] . ' ' . $score['last_name']) . ' ';
                                $score_text .= 'Candidate Score : ' . $score['candidate_score'] . ' out of 100 ';
                                $score_text .= 'Job Relevancy Score : ' . $score['job_relevancy_score'] . ' out of 100; ';
                            }
                        } else {
                            $score_text = 'No interview scores';
                        }
                        $input['scores'] = $score_text;
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
            $this->load->view('reports/applicants_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    //
    public function documentsReport($company_sid = 'all', $keyword = 'all', $start_date = 'all', $end_date = 'all')
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicants Report';

            $data['flag'] = false;
            $data['assigneddocuments'] = [];


            $this->load->model('Users_model');
            $executive_user_companies  = $this->Users_model->get_executive_users_companies($data['executive_user']['sid'], null);
            $data['executive_user_companies'] = $executive_user_companies;


            if (!empty($start_date) && $start_date != 'all') {
                $start_date_assigned = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else{
                $start_date_assigned ='all';
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_assigned = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else{
                $end_date_assigned='all';
            }

           // if ($this->uri->segment(3) != '') {
                $userSids = array_column($executive_user_companies, 'logged_in_sid');
                $data['assigneddocuments'] = $this->Reports_model->getAssignedDocumentForReport($userSids, $company_sid, $keyword,$start_date_assigned,$end_date_assigned);
                $data['flag'] = true;
          //  }

            $this->load->view('main/header', $data);
            $this->load->view('reports/documents_activity_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}