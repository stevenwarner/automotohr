<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_offers_report extends Admin_Controller {

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
        $function_name      = 'applicant_offers_report';
        
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        if($oem!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        }
        elseif($company_sid!='all'){
            $this->data['company_or_brand'] = 'company';
            $oem = NULL;
        }
        else {
            $this->data['company_or_brand'] = 'all';
            $company_sid = NULL;
            $oem = NULL;
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
        $this->data['flag'] = true;

        $this->data['page_title'] = 'Advanced Hr Reports - Applicant Offers';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->form_validation->set_data($this->input->get(NULL, true));


        $this->data['applicants'] = $this->advanced_report_model->get_candidate_offers($company_sid, $oem, $start_date_applied, $end_date_applied, $all, $keyword);

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
//
//            //** get applicant statuses **//
////            if (isset($search_data['start']) && $search_data['start'] != "") {
////                $start_date = explode('-', $search_data['start']);
////                $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
////            }
////
////            if (isset($search_data['end']) && $search_data['end'] != "") {
////                $end_date = explode('-', $search_data['end']);
////                $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
////            }
//
//            $this->data['applicants'] = $this->advanced_report_model->get_candidate_offers($company_sid, $brand_sid, $start_date, $end_date);
//
//            //** get applicant statuses **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['applicants']) && sizeof($this->data['applicants']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array('Offer Date', 'Job Title', 'Applicant Name', 'Email', 'Employee Type'));

                foreach ($this->data['applicants'] as $candidate) {
                    $input = array();
                    $city = '';
                    $state='';
                    if (isset($candidate['Location_City']) && $candidate['Location_City'] != NULL) {
                        $city = ' - '.ucfirst($candidate['Location_City']);
                    }
                    if (isset($candidate['Location_State']) && $candidate['Location_State'] != NULL) {
                        $state = ', '.db_get_state_name($candidate['Location_State'])['state_name'];
                    }
                    $input['offer_date'] = date('m-d-Y', strtotime(str_replace('-', '/', $candidate['registration_date'])));
                    $input['job_title'] = $candidate['job_title'].$city.$state;
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

        $this->render('manage_admin/reports/applicant_offers_report');
    }

}
