<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_products_report extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/job_products_report_model');
        $this->load->library("pagination");
        $this->load->library('ion_auth');
    }

    public function index($product_sid = 'all', $job_sid = 'all', $company_sid ='all', $brand_sid = 'all', $start_date = 'all', $end_date = 'all', $all = 1, $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'job_products_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //
        if($brand_sid!='all')
            $this->data['company_or_brand'] = 'brands';
        elseif($company_sid!='all')
            $this->data['company_or_brand'] = 'company';
        else
            $this->data['company_or_brand'] = 'all';

        $this->data['flag'] = false;
        $product_sid = urldecode($product_sid);
        $job_sid = urldecode($job_sid);
        $company_sid = urldecode($company_sid);
        $brand_sid = urldecode($brand_sid);
        $start_date = urldecode($start_date);
        $end_date = urldecode($end_date);
        $this->data['page_title'] = 'Job Products Report';
//        $this->data['products_count'] = $this->job_products_report_model->get_job_products_count();
        $this->data['brands'] = $this->job_products_report_model->get_all_oem_brands();
        $baseUrl = base_url('manage_admin/reports/job_products_report') . '/' . $product_sid . '/' . $job_sid . '/' . $company_sid . '/' . $brand_sid . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($all);

        if($company_sid != 'all' || $brand_sid != 'all' || $all) {
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? NULL : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? NULL : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_applied = date('Y-m-d 23:59:59');
            }

            if($company_sid == 'all'){
                $company_sid = NULL;
            }
            if($brand_sid == 'all'){
                $brand_sid = NULL;
            }
            $search = '';
            $between = '';
            if ($start_date_applied != NULL && $end_date_applied != NULL)
                $between = "purchased_date between '" . $start_date_applied . "' and '" . $end_date_applied . "'";
            $product_sid != 'all' ? $search['product_sid']=$product_sid : '';
            $job_sid != 'all' ? $search['job_sid']=$job_sid : '';

//            $data['job_sid_array'] = array();
            if ($job_sid != null || $job_sid != 'all') {
                $this->data['job_sid_array'] = explode(',', $job_sid);
            }
//            echo '<pre>';
//            print_r($search);
//            die();

            $this->data["flag"] = true;
        }
        else{
            $search = '';
            $between = '';
            $this->data["flag"] = false;
            $brand_sid = NULL;
            $company_sid = NULL;
        }
        $this->data['products_count'] = sizeof($this->job_products_report_model->get_job_products($company_sid, $brand_sid, null, null, $search, $between));
        /** pagination * */
        $records_per_page = PAGINATION_RECORDS_PER_PAGE;
        $my_offset = 0;
        if($page_number > 1){
            $my_offset = ($page_number - 1) * $records_per_page;
        }
        $uri_segment = 11;
        $config = array();
        $config["base_url"] = $baseUrl;
        $config["total_rows"] = $this->data['products_count'];
        $config["per_page"] = $records_per_page;
        $config["uri_segment"] = $uri_segment;
        $choice = $config["total_rows"] / $config["per_page"];
        $config["num_links"] = ceil($choice);
        $config["use_page_numbers"] = true;
        $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
        $config['full_tag_close'] = '</ul></nav><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
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
        $this->data["links"] = $this->pagination->create_links();
        $total_records = $this->data['products_count'];

        $this->data['current_page'] = $page_number;
        $this->data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
        $this->data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;
        /** pagination end * */


        /** search code * */
//        if (isset($_GET['submit'])) {
//            $search = array();
//            $search_data = $this->input->get(NULL, true);
//            $this->data["search"] = $search_data;
//            $this->data["flag"] = false;
//
//
//            foreach ($search_data as $key => $value) {
//                if ($key != 'start' && $key != 'end' && $key != 'sid' && $key != 'submit' && $key != 'company_or_brand' && $key != 'company_sid' && $key != 'brand_sid') {
//                    if ($value != '') { // exclude these values from array
//                        $search[$key] = $value;
//                        $this->data["flag"] = true;
//                    }
//                }
//            }
//
//            if (isset($search_data['start']) || isset($search_data['end'])) {
//                if (isset($search_data['start']) && $search_data['start'] != "") {
//                    $start_date = explode('-', $search_data['start']);
//                    $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
//                } else {
//                    $start_date = '01-01-1970 00:00:00';
//                }
//
//                if (isset($search_data['end']) && $search_data['end'] != "") {
//                    $end_date = explode('-', $search_data['end']);
//                    $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
//                } else {
//                    $end_date = date('Y-m-d H:i:s');
//                }
//
//                $between = "purchased_date between '" . $start_date . "' and '" . $end_date . "'";
//            }
//
//            $this->data['search'] = $search_data;
//            $this->data["flag"] = true;
//        } else {
//            $search = '';
//            $between = '';
//            $this->data["flag"] = false;
//        }

        /** search code end * */
        $this->data['active_companies'] = $this->job_products_report_model->get_all_companies();
        $this->data['active_products'] = $this->job_products_report_model->get_active_products();
        //$this->data['active_products'] = $this->job_products_report_model->get_active_products();
        $this->data['active_jobs'] = $this->job_products_report_model->get_active_jobs();
        $this->data['products'] = $this->job_products_report_model->get_job_products($company_sid, $brand_sid, $records_per_page, $my_offset, $search, $between);
        $products_full = $this->job_products_report_model->get_job_products($company_sid, $brand_sid, null, null, $search, $between);

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if(isset($products_full) && sizeof($products_full) > 0){

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array('Job ID', 'Job Title', 'Product Name', 'Company Name', 'Advertised Date'));

                foreach($products_full as $product){
                    $input = array();
                     $city = '';
                     $state='';
                    if (isset($product['Location_City']) && $product['Location_City'] != NULL) {
                        $city = ' - '.ucfirst($product['Location_City']);
                     }
                    if (isset($product['Location_State']) && $product['Location_State'] != NULL) {
                        $state = ', '.db_get_state_name($product['Location_State'])['state_name'];
                    }
                    $input['job_sid'] = $product['job_sid'];
                    $input['job_title'] = $product['job_title'].$city.$state;
                    $input['product_name'] = $product['product_name'];
                    $input['company_name'] = $product['company_name'];
                    $input['purchased_date'] = date_with_time($product['purchased_date']);
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/job_products_report/job_products_report');
    }

}
