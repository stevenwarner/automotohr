<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicant_source_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        //$this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $keyword = 'all', $job_sid = 'all', $applicant_type = 'all', $applicant_status = 'all', $start_date = 'all', $end_date = 'all', $oem = 'all', $source = 'all', $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicant_source_report';
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

        $this->data['page_title'] = 'Applicant Source Report';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->form_validation->set_data($this->input->get(NULL, true));

        $this->data['flag'] = false;
//        $data['jobs'] = $this->advanced_report_model->get_company_jobs($company_sid);
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

//        $applicant_types = array();
//        $applicant_types[] = 'Applicant';
//        $applicant_types[] = 'Talent Network';
//        $applicant_types[] = 'Manual Candidate';
//        $applicant_types[] = 'Re-Assigned Candidates';
//        $applicant_types[] = 'Job Fair';
        $applicant_types = explode(',', APPLICANT_TYPE_ATS);
        $this->data['applicant_types'] = $applicant_types;
        if ($job_sid != null || $job_sid != 'all') {
            $this->data['job_sid_array'] = $job_sid;
        }

        $applicant_statuses = $this->advanced_report_model->get_company_statuses($company_sid);
        $this->data['applicant_statuses'] = $applicant_statuses;

        $per_page = PAGINATION_RECORDS_PER_PAGE;
        //$per_page = 2;
        //$page_number = $this->input->get('page_number');
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }
//        $total_records = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, true, null, null, $source);
        $applicants = $this->advanced_report_model->get_applicants($company_sid, $keyword, $job_sid, $applicant_type, $applicant_status, $start_date_applied, $end_date_applied, $oem, false, $per_page, $offset);
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

        $pagination_base = base_url('manage_admin/reports/applicant_source_report') . '/' . $company_sid . '/' . urlencode($keyword) . '/' . $job_sid . '/' . urlencode($applicant_type) . '/' . urlencode($applicant_status) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($oem) . '/' . urlencode($source);

        //echo $pagination_base;

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
        $this->data["page_links"] = $this->pagination->create_links();

        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $offset == 0 ? 1 : $offset;
        $this->data['to_records'] = $total_records < $per_page ? $total_records : $offset + $per_page;

        //-----------------------------------Pagination Ends-----------------------------//

        //$applicants = $this->Reports_model->get_applicants($company_sid, $search_string, $search_string2, $start_date_applied, $end_date_applied, false, $per_page, $offset);

        $this->data['applicants'] = $final_applicants;

        $this->data['applicants_count'] = $total_records;
//        if (isset($_GET['submit']) && $_GET['submit'] == 'Apply Filters') {
//            $search_data = $this->input->get(NULL, true);
//            $values = array();
//            $methods = array();
//            $i = 0;
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
//            foreach ($search_data as $key => $value) {
//                if ($key != 'submit' && $key != 'company_sid' && $key != 'sort_Title') {
//                    $key_exp = explode('value_', $key);
//
//                    if (isset($key_exp[1]) && $key_exp[1] != '') {
//                        $values[$key] = $value;
//                    } else {
//                        $methods[$key] = $value;
//                    }
//                }
//            }
//
//            foreach ($values as $key => $value) {
//                if ($value != '' && $value != NULL && $value != "0") {
//                    $key_exp = explode('filter_value_', $key);
//                    $key = $key_exp[1];
//                    $get_method = 'filter_method_' . $key;
//                    $method = $methods[$get_method];
//
//                    if ($i != 0 && $key != 'applicant_type' && $key != 'date_applied') {
//                        $search_string .= ' AND ';
//                    }
//
//                    if ($key != 'date_applied' && $key != 'applicant_type') {
//
//                        if ($key == 'Title') {
//                            // $key = 'job_sid';
//                            $key = 'portal_applicant_jobs_list.job_sid';
//                        }
//
//                        if ($method == 'like') {
//                            $search_string .= $key . " like '%" . $value . "%'";
//                        } else if ($method == 'equals') {
//                            $search_string .= $key . " = '" . $value . "'";
//                        }
//                    } else if ($key == 'date_applied') {
//                        $date_search ++;
//                        $date = explode('-', $value);
//                        $start_date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' 00:00:00';
//                        $end_date = $date[2] . '-' . $date[0] . '-' . $date[1] . ' 23:59:59';
//
//                        if ($method == 'equals') {
//                            $search_string2 .= "portal_applicant_jobs_list." . $key . " between '" . $start_date . "' and '" . $end_date . "'";
//                        } else if ($method == 'greater_than') {
//                            $search_string2 .= "portal_applicant_jobs_list." . $key . " > '" . $end_date . "'";
//                        } else if ($method == 'lesser_than') {
//                            $search_string2 .= "portal_applicant_jobs_list." . $key . " < '" . $start_date . "'";
//                        }
//                    } else if ($key == 'applicant_type') {
//                        if ($value != '' && $value != NULL && $date_search == 1) {
//                            $search_string2 .= ' AND ';
//                        }
//
//                        if ($method == 'like') {
//                            $search_string2 .= "portal_applicant_jobs_list." . $key . " like '%" . $value . "%'";
//                        } else if ($method == 'equals') {
//                            $search_string2 .= "portal_applicant_jobs_list." . $key . " = '" . $value . "'";
//                        }
//                    }
//
//                    $i++;
//                }
//            }
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//            $this->data['applicants'] = $this->advanced_report_model->get_applicant_source($company_sid, $brand_sid, $search_string, $search_string2);
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if(sizeof($final_applicants) > 0){
             
                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');
                if ((isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') || (isset($_GET['company_sid']) && $_GET['company_sid'] == 'all')) { 
                    fputcsv($output, array('Job Title', 'First Name', 'Last Name', 'Date Applied', 'Applicant Source', 'IP Address', 'Applicant Type', 'Company Name'));
                } else {
                    fputcsv($output, array('Job Title', 'First Name', 'Last Name', 'Date Applied', 'Applicant Source', 'IP Address', 'Applicant Type'));
                }
                
                foreach($final_applicants as $applicant){
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
                    $input['date_applied'] = date_with_time($applicant['date_applied']);
                    $input['applicant_source'] = $applicant['applicant_source'];
                    $input['ip_address'] = $applicant['ip_address'];
                    $input['applicant_type'] = $applicant['applicant_type'];

                    if ((isset($_GET['company_or_brand']) && $_GET['company_or_brand'] == 'brand') || (isset($_GET['company_sid']) && $_GET['company_sid'] == 'all')) {
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

        $this->render('manage_admin/reports/applicant_source_report');
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
                default:
                    break;
            }
        }
    }

}
