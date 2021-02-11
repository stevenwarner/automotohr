<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class New_hires_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $start_date = 'all', $end_date = 'all', $oem = 'all', $all = 1, $keyword = 'all', $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'new_hires_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if($oem!='all'){
            $this->data['company_or_brand'] = 'brands';
        }
        elseif($company_sid!='all'){
            $this->data['company_or_brand'] = 'company';
        }
        else{
            $this->data['company_or_brand'] = 'all';
        }

        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        $keyword = urldecode($keyword);

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

        $this->data['page_title'] = 'Advanced Hr Reports - New Hires';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;


        //-----------------------------------Pagination Starts----------------------------//
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/new_hires_report') . '/' . $company_sid . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($oem) . '/' . urlencode($all) . '/' . urlencode($keyword);
        if($company_sid != 'all' || $oem != 'all'){
            if($company_sid!='all'){
                $oem = NULL;
            }
            if($oem!='all' && $oem!=NULL){
                $company_sid = NULL;
            }

            if($start_date_applied!=NULL && $end_date_applied!=NULL)
                $this->data['page_title'] = 'Advanced Hr Reports - Applicants New Hires Between ( ' . date('m-d-Y', strtotime($start_date_applied)) . ' - ' . date('m-d-Y', strtotime($end_date_applied)) . ' )';
            else
                $this->data['page_title'] = 'Advanced Hr Reports - New Hires';
        }
        else{
            $oem = NULL;
            $company_sid = NULL;
        }

        $total_records = $this->advanced_report_model->GetAllApplicantsBetween($company_sid, $oem, $start_date_applied, $end_date_applied, $keyword, 0, true, 1, $all);
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

        $this->data['applicants'] = $this->advanced_report_model->GetAllApplicantsBetween($company_sid, $oem, $start_date_applied, $end_date_applied, $keyword, 0, true, 0, $all, $per_page, $offset);

        $this->data['applicants_count'] = $total_records;
        $this->data['flag'] = true;
        $this->data['is_hired_report'] = true;
//        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
//            $search_data = $this->input->get(NULL, true);
//
//            // ** select company or brand sid **//
//            if ($search_data['company_or_brand'] == 'company') {
//                $company_sid = $search_data['company_sid'];
//                $brand_sid = NULL;
//            } else {
//                $brand_sid = $search_data['brand_sid'];
//                $company_sid = NULL;
//            }
//            // ** select company or brand sid **//
//            //** get new hires **//
//            $company_detail = get_company_details($company_sid);
//
//            if (isset($company_detail['registration_date']) && $company_detail['registration_date'] != '') {
//                $start_date = $company_detail['registration_date'];
//            }
//
//            if (isset($search_data['start']) && $search_data['start'] != "") {
//                $start_date = explode('-', $search_data['start']);
//                $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
//            }
//
//            if (isset($search_data['end']) && $search_data['end'] != "") {
//                $end_date = explode('-', $search_data['end']);
//                $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
//            }
//
//            $this->data['applicants'] = $this->advanced_report_model->GetAllApplicantsBetween($company_sid, $brand_sid, $start_date, $end_date, 1, true);
//            $this->data['page_title'] = 'Advanced Hr Reports - Applicants Hired Between ( ' . date('m-d-Y', strtotime($start_date)) . ' - ' . date('m-d-Y', strtotime($end_date)) . ' )';
//            $this->data['is_hired_report'] = true;
//            //** get new hires **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['applicants']) && sizeof($this->data['applicants']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                
                if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                    if (isset($this->data['is_hired_report']) && $this->data['is_hired_report'] == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On', 'Company Name'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date', 'Company Name'));
                    }
                } else {
                    if (isset($this->data['is_hired_report']) && $this->data['is_hired_report'] == true) {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Hired On'));
                    } else {
                        fputcsv($output, array('Job Title', 'Applicant Name', 'Application Date'));
                    }
                }

                foreach ($this->data['applicants'] as $applicant) {
                    $input = array();
                    $input['Title'] = $applicant['Title'];
                    $input['name'] = ucwords($applicant['first_name'] . ' ' . $applicant['last_name']);
                    
                    if (isset($this->data['is_hired_report']) && $this->data['is_hired_report'] == true) {
                        $input['hired_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['hired_date'])));
                    } else {
                        $input['date_applied'] = date('m-d-Y', strtotime(str_replace('-', '/', $applicant['date_applied'])));
                    }
                    
                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        $input['CompanyName'] = $applicant['CompanyName'];
                    }
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/new_hires_report');
    }

}
