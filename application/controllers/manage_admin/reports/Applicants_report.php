<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $oem = 'all', $search_base = 'all', $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        if($oem!='all')
            $this->data['company_or_brand'] = 'brands';
        elseif($company_sid!='all')
            $this->data['company_or_brand'] = 'company';
        else
            $this->data['company_or_brand'] = 'all';
        $this->data['page_title'] = 'Applicants Report';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;

        $keyword = urldecode($keyword);
        $job_sid = urldecode($job_sid);
        $applicant_type = urldecode($applicant_type);
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
        $this->data['flag'] = true;
        if ($job_sid != null || $job_sid != 'all') {
            $this->data['job_sid_array'] = $job_sid;
        }

//        $applicant_types = array();
//        $applicant_types[] = 'Applicant';
//        $applicant_types[] = 'Talent Network';
//        $applicant_types[] = 'Manual Candidate';
//        $applicant_types[] = 'Re-Assigned Candidates';
//        $applicant_types[] = 'Job Fair';
        $applicant_types = explode(',', APPLICANT_TYPE_ATS);
        $this->data['applicant_types'] = $applicant_types;

        $applicant_statuses = $this->advanced_report_model->get_company_statuses($company_sid);
        $this->data['applicant_statuses'] = $applicant_statuses;

        //-----------------------------------Pagination Starts----------------------------//
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }
        $total_records = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, true);

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/applicants_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . $job_sid . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($oem) . '/' . urlencode($search_base);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 13;
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
        $config['next_link'] = '<i class="fa fa-angle-right" style="line-height: 32px;"></i>';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '<i class="fa fa-angle-left" style="line-height: 32px;"></i>';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $this->pagination->initialize($config);
        $this->data["page_links"] = $this->pagination->create_links();
        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

        //-----------------------------------Pagination Ends-----------------------------//

        $applicants = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, false, $per_page, $offset);
        $this->data['applicants'] = $applicants;

        $this->data['applicants_count'] = $total_records;
        
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            $applicants = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, false);
            if(isset($applicants) && sizeof($applicants) > 0){
             
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') { 
                    fputcsv($output, array('Job Title', 'First Name', 'Last Name', 'Email', 'Phone Number', 'Date Applied', 'Applicant Type', 'Questionnaire Score', 'Reviews Score', 'Company Name'));
                } else {
                    fputcsv($output, array('Job Title', 'First Name', 'Last Name', 'Email', 'Phone Number', 'Date Applied', 'Questionnaire Score', 'Reviews Score', 'Applicant Type'));
                }
                
                foreach($applicants as $applicant){
                    $input = array();
                    $city = '';
                    $state='';
                    if (isset($applicant['Location_City']) && $applicant['Location_City'] != NULL) {
                        $city = ' - '.ucfirst($applicant['Location_City']);
                    }
                    if (isset($applicant['Location_State']) && $applicant['Location_State'] != NULL) {
                        $state = ', '.db_get_state_name($applicant['Location_State'])['state_name'];
                    }
                    $input['Title'] = $applicant['Title'].$city.$state;
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

                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        $input['CompanyName'] = $applicant['CompanyName'];
                    }
                    
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

        $this->render('manage_admin/reports/applicants_report');
    }

    public function ajax_responder() {
        if (array_key_exists('perform_action', $_POST)) {
            $perform_action = $_POST['perform_action'];
            switch ($perform_action) {
                case 'load_jobs':
                    $company_sid = $this->input->post('company_sid');
                    $company_jobs = $this->advanced_report_model->get_company_jobs($company_sid);
                    echo json_encode($company_jobs);
                    break;
                case 'load_status':
                    $company_sid = $this->input->post('company_sid');
                    $company_status = $this->advanced_report_model->get_company_statuses($company_sid);
                    echo json_encode($company_status);
                    break;
                default:
                    break;
            }
        }
    }

}