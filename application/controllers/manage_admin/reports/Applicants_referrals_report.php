<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_referrals_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index() {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_referrals_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $this->data['page_title'] = 'Advanced Hr Reports - Company Referrals';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));

        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
            $search_data = $this->input->get(NULL, true);

            // ** select company or brand sid **//
            if ($search_data['company_or_brand'] == 'company') {
                $company_sid = $search_data['company_sid'];
                $brand_sid = NULL;
            } else {
                $brand_sid = $search_data['brand_sid'];
                $company_sid = NULL;
            }
            // ** select company or brand sid **//

            $references = $this->advanced_report_model->get_references($company_sid, $brand_sid);

            //********************//
            $users = array();
            foreach ($references as $reference) {
                $ref = array();
                $user_sid = $reference['user_sid'];
                foreach ($references as $reference2) {
                    if ($reference2['user_sid'] == $user_sid) {
                        $ref[] = $reference2;
                    }
                }
                $users[$reference['user_name']] = $ref;
            }
            //********************//

            $this->data['users'] = $users;
            $this->data['flag'] = true;
            $this->data['search'] = $search_data;
        }

        /** export excel sheet * */
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($users) && sizeof($users) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                foreach ($users as $user => $references) {
                    fputcsv($output, array($user, ucwords($references[0]['users_type'])));

                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        fputcsv($output, array('Reference Title', 'Name', 'Type', 'Relation', 'Email', 'Department', 'Branch', 'Reference Organization', 'Company Name'));
                    } else {
                        fputcsv($output, array('Reference Title', 'Name', 'Type', 'Relation', 'Email', 'Department', 'Branch', 'Reference Organization'));
                    }
                    
                    foreach ($references as $reference) {
                        $input = array();

                        $input['reference_title'] = $reference['reference_title'];
                        $input['reference_name'] = $reference['reference_name'];
                        $input['reference_type'] = $reference['reference_type'];
                        $input['reference_relation'] = $reference['reference_relation'];
                        $input['reference_email'] = $reference['reference_email'];
                        $input['department_name'] = $reference['department_name'];
                        $input['branch_name'] = $reference['branch_name'];
                        $input['organization_name'] = $reference['organization_name'];

                        if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                            $input['CompanyName'] = $reference['CompanyName'];
                        }

                        fputcsv($output, $input);
                    }
                    
                    fputcsv($output, array());
                    fputcsv($output, array());
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }
        /** export excel sheet * */
        $this->render('manage_admin/reports/applicants_referrals_report');
    }

}
