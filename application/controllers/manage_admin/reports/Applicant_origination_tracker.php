<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_origination_tracker extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/advanced_report_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($brand_sid, $company_sid, $start_date, $end_date) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicant_origination_tracker';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //


        $this->data['page_title'] = 'Advanced Hr Reports - Applicant Origination Tracker';
        $companies = $this->advanced_report_model->get_all_companies();
        $this->data['companies'] = $companies;
        $brands = $this->advanced_report_model->get_all_oem_brands();
        $this->data['brands'] = $brands;

        $applicant_sources = array();
        $applicant_sources[] = 'automoto_social';
        $applicant_sources[] = 'career_website';
        $applicant_sources[] = 'glassdoor';
        $applicant_sources[] = 'indeed';
        $applicant_sources[] = 'juju';

        if ($brand_sid == 'all') {
            $brand_sid = 0;
        }
        if ($company_sid == 'all') {
            $company_sid = 0;
        }
        if ($start_date == 'all') {
            $start_date = '1970-01-01 00:00:00';
        } else {
            $my_date = explode('-', $start_date);
            $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, $my_date[0], $my_date[1], $my_date[2]));
        }
        if ($end_date == 'all') {
            $end_date = date('Y-m-d H:i:s');
        } else {
            $my_date = explode('-', $end_date);
            $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, $my_date[0], $my_date[1], $my_date[2]));
        }

        $applicants_by_source = array();
        $companies_applicants_by_source = array();
        $companies_for_report = array();

        if ($brand_sid == 0) {
            $companies_for_report = $companies;
        } else {
            $companies_for_report = $this->advanced_report_model->get_brand_companies_with_full_details($brand_sid);
        }

        if ($company_sid == 0) {
            if (!empty($companies_for_report)) {
                foreach ($companies_for_report as $company) {
                    $companies_applicants_by_source[] = $this->get_company_applicants_by_source($company['sid'], $applicant_sources, $start_date, $end_date);
                }
            }
        } else {
            $companies_applicants_by_source[] = $this->get_company_applicants_by_source($company_sid, $applicant_sources, $start_date, $end_date);
        }

        $this->data['companies_applicants_by_source'] = $companies_applicants_by_source;

        //******* data exports ********//
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['companies_applicants_by_source']) && sizeof($this->data['companies_applicants_by_source']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                foreach ($this->data['companies_applicants_by_source'] as $company_applicants_by_source) {
                    $company_info = $company_applicants_by_source['company_info'];
                    $applicants_by_source = $company_applicants_by_source['applicants_by_source'];
                    fputcsv($output, array('Company Name : '.ucwords($company_info['CompanyName'])));
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
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }
        //******* data exports ********//

        $this->render('manage_admin/reports/applicant_origination_tracker');
    }

    private function get_company_applicants_by_source($company_sid, $applicant_sources = array(), $start_date, $end_date) {

        $applicants_by_source = array();

        foreach ($applicant_sources as $applicant_source) {
            $applicants = $this->advanced_report_model->get_applicants_by_source($company_sid, $applicant_source, $start_date, $end_date);
            $applicants_by_source[$applicant_source] = $applicants;
        }

        $company_info = get_company_details($company_sid);
        //$applicants = $this->advanced_report_model->get_applicants_by_source($company_sid, null, $start_date, $end_date);
        //$applicants_by_source['career_website'] = array_merge($applicants, $applicants_by_source['career_website']);

        $company_data = array();
        $company_data['company_info'] = $company_info;
        $company_data['applicants_by_source'] = $applicants_by_source;

        return $company_data;
    }

}
