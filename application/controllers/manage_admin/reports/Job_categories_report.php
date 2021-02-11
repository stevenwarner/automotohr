<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_categories_report extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = 'all', $brand_sid = 'all', $all = 1, $page_number = 1) {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url       = 'manage_admin';
        $function_name      = 'job_categories_report';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $brand_sid = urldecode($brand_sid);
        $company_sid = urldecode($company_sid);

        $pagination_base = base_url('manage_admin/reports/job_categories_report') . '/' . $company_sid . '/' . $brand_sid . '/' . urlencode($all);

        if($brand_sid!='all'){
            $this->data['company_or_brand'] = 'brands';
            $company_sid = NULL;
        }
        elseif($company_sid!='all'){
            $this->data['company_or_brand'] = 'company';
            $brand_sid = NULL;
        }
        else {
            $this->data['company_or_brand'] = 'all';
            $company_sid = NULL;
            $brand_sid = NULL;
        }

        //-----------------------------------Pagination Starts----------------------------//
        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if($page_number > 1){
            $offset = ($page_number - 1) * $per_page;
        }
        $total_records = $this->advanced_report_model->GetAllJobCategoriesWhereApplicantsAreHired($company_sid, $brand_sid, $all, 1);

        $this->load->library('pagination');

        //echo $pagination_base;

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 7;
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

        $this->data['page_title'] = 'Advanced Hr Reports - Active New Hire Categories';
        $this->data['companies'] = $this->advanced_report_model->get_all_companies();
        $this->data['brands'] = $this->advanced_report_model->get_all_oem_brands();
        $this->data['flag'] = false;
        $this->form_validation->set_data($this->input->get(NULL, true));

        if($company_sid != 'all' || $brand_sid != 'all' && $brand_sid != NULL){
            $this->data['flag'] = true;
        }
        //** get job categories where applicants are hired **//
        $this->data['categories'] = $this->advanced_report_model->GetAllJobCategoriesWhereApplicantsAreHired($company_sid, $brand_sid, $all, 0, $per_page, $offset);
        //** get job categories **//
//        echo '<pre>' ;
//        print_r($this->data['categories']);
//        die();
        $this->data['applicants_count'] = $total_records;

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
//            //** get job categories where applicants are hired **//
//            $this->data['categories'] = $this->advanced_report_model->GetAllJobCategoriesWhereApplicantsAreHired($company_sid, $brand_sid);
//            //** get job categories **//
//
//            $this->data['flag'] = true;
//            $this->data['search'] = $search_data;
//        }

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
            if (isset($this->data['categories']) && sizeof($this->data['categories']) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array('Sr. No', 'Category', 'Hire Count'));

                foreach ($this->data['categories'] as $key => $category) {
                    $input = array();
                    $input['key'] = $key + 1;
                    $input['category'] = $category['category'];
                    $input['count'] = $category['count'];

                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/job_categories_report');
    }

}
