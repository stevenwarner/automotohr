<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_origination_report extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        //$this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $source = 'all', $startdate = 'all', $enddate = 'all', $keyword = 'all', $page_number = 1)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_origination_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $source = urldecode($source);
        $company_sid = urldecode($company_sid);
        $start_date = urldecode($startdate);
        $end_date = urldecode($enddate);
        $keyword = urldecode($keyword);
        $this->data['flag'] = true;
        $this->form_validation->set_data($this->input->get(NULL, true));


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

        $this->data['page_title'] = 'Applicant Origination Report';
        $main_companies = $this->advanced_report_model->get_all_companies();
        $this->data['companies'] = $main_companies;

        $result_array = array();
        $this->data['source'] = '';


        if (!empty($source)) {

            switch ($source) {
                case 'automotosocial':
                    $this->data['source'] = 'from Automoto Social';
                    break;
                case 'glassdoor':
                    $this->data['source'] = 'from Glassdoor';
                    break;
                case 'indeed':
                    $this->data['source'] = 'from Indeed';
                    break;
                case 'juju':
                    $this->data['source'] = 'from JuJu';
                    break;
                case 'jobs2careers':
                    $this->data['source'] = 'from Jobs2Careers';
                    break;
                case 'ziprecruiter':
                    $this->data['source'] = 'from ZipRecruiter';
                    break;
                case 'all':
                    $this->data['source'] = 'from all sources';
                    break;
                case 'career_website':
                    $this->data['source'] = 'from Career Website';
                    break;
                default;
                    break;
            }
        }


        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }
//        $total_records = $this->advanced_report_model->get_applicant_origins($company_sid, 'all', $start_date_applied, $end_date_applied, $keyword, 1);
        $applicants = $this->advanced_report_model->get_applicant_origins($company_sid, 'all', $start_date_applied, $end_date_applied, $keyword, 0, $per_page, $offset);
        $total_records = 0;
        $final_applicants = array();
        if($source != 'all'){
            for($i=0; $i < sizeof($applicants); $i++){
                $a = domainParser($applicants[$i]['applicant_source'], $applicants[$i]['main_referral'], true);
                if($a != 'N/A' && strtolower(preg_replace('/[^a-zA-Z]/', '', $a['ReferrerSource'])) == strtolower($source)){
                    $final_applicants[] = $applicants[$i];
                    $total_records++;
                }
            }
        }else{
            $total_records = sizeof($applicants);
            $final_applicants = $applicants;
        }
        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/applicant_origination_report') . '/' . $company_sid . '/' . urlencode($source) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($keyword);

        //echo $pagination_base;

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 9;
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

        $this->data['applicants_count'] = $total_records;
        $this->data['applicants'] = $final_applicants;
        $this->render('manage_admin/reports/applicant_origination_report');
    }
}