<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Interviews_report extends Admin_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library('pagination');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $brand_sid = 'all'){
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'interviews_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $brand_sid = urldecode($brand_sid);
        $company_sid = urldecode($company_sid);
        
        if($brand_sid!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        } else {
            $this->data['company_or_brand'] = 'company';
            $brand_sid = NULL;
        }

        $this->data['page_title'] = 'Advanced Hr Reports - Interviews Scheduled by Recruiters';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));

        if($company_sid != 'all' || $brand_sid != 'all' && $brand_sid != NULL) {
            $company_users = $this->advanced_report_model->GetAllUsers($company_sid, $brand_sid);
            //echo $this->db->last_query();
//            echo "<pre>";
//            print_r($company_users);
//            exit;
            foreach ($company_users as $key => $user) {
                $employer_events = $this->advanced_report_model->GetAllEventsByCompanyAndEmployer($user['parent_sid'], $user['sid']); // $company_sid
                $company_users[$key]['events'] = $employer_events;
            }

            $this->data['users_events'] = $company_users;
            //** get all user events **//

            $this->data['flag'] = true;
        }
//        if(isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters'){
//            $search_data = $this->input->get(NULL, true);
//
//            // ** select company or brand sid **//
//            if($search_data['company_or_brand'] == 'company'){
//                $company_sid = $search_data['company_sid'];
//                $brand_sid = NULL;
//            } else {
//                $brand_sid = $search_data['brand_sid'];
//                $company_sid = NULL;
//            }
//            // ** select company or brand sid **//
//
//            //** get all user events **//
//            $company_users = $this->advanced_report_model->GetAllUsers($company_sid, $brand_sid);
//
//            foreach($company_users as $key => $user){
//                $employer_events = $this->advanced_report_model->GetAllEventsByCompanyAndEmployer($user['parent_sid'], $user['sid']); // $company_sid
//                $company_users[$key]['events'] = $employer_events;
//            }
//
//            $this->data['users_events'] = $company_users;
//            //** get all user events **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }
        
        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['users_events']) && sizeof($this->data['users_events'] > 0)) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                foreach ($this->data['users_events'] as $user_event) {
                    fputcsv($output, array(ucwords($user_event['first_name'] . ' ' . $user_event['last_name'])));

                    if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                        fputcsv($output, array('Interview Scheduled For', 'Interview Date', 'Company Name'));
                    } else {
                        fputcsv($output, array('Interview Scheduled For', 'Interview Date'));
                    }

                    if (sizeof($user_event['events']) > 0) {
                        foreach ($user_event['events'] as $event) {
                            $input = array();
                            $input['name'] = ucwords($event['applicant_first_name'] . ' ' . $event['applicant_last_name']);
                            $input['date'] = date('m/d/Y', strtotime($event['date']));
                            if (isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') {
                                $input['CompanyName'] = $event['CompanyName'];
                            }
                            fputcsv($output, $input);
                        }
                    } else {
                        fputcsv($output, array('No Interviews Found.'));
                    }
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }
        
        $this->render('manage_admin/reports/interviews_report');
    }
}
