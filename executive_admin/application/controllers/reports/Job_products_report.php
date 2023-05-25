<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_products_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid, $product_sid = null, $job_sid = 'all', $start_date = null, $end_date = null, $page = 1) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Job Products Report';
            $data['company_sid'] = $company_sid;

            $data['companyName'] = getCompanyNameBySid($company_sid);

            /** search criteria * */
            /*
            if (isset($_GET['submit']) && $_GET['submit'] == 'Search') {
                $search = array();
                $search_data = $this->input->get(NULL, true);
                $data["search"] = $search_data;
                $data["flag"] = false;

                foreach ($search_data as $key => $value) {
                    if ($key != 'start' && $key != 'end' && $key != 'submit') {
                        if ($value != '') { // exclude these values from array
                            $search[$key] = $value;
                            $data["flag"] = true;
                        }
                    }
                }

                if (isset($search_data['start']) || isset($search_data['end'])) {
                    if (isset($search_data['start']) && $search_data['start'] != "") {
                        $start_date = explode('-', $search_data['start']);
                        $start_date = $start_date[2] . '-' . $start_date[0] . '-' . $start_date[1] . ' 00:00:00';
                    } else {
                        $start_date = '2015-01-01 00:00:00';
                    }

                    if (isset($search_data['end']) && $search_data['end'] != "") {
                        $end_date = explode('-', $search_data['end']);
                        $end_date = $end_date[2] . '-' . $end_date[0] . '-' . $end_date[1] . ' 23:59:59';
                    } else {
                        $end_date = date('Y-m-d H:i:s');
                    }

                    $between = "purchased_date between '" . $start_date . "' and '" . $end_date . "'";
                }

                $data['search'] = $search_data;
                $data["flag"] = true;
            } else {
                $search = '';
                $between = '';
                $data["flag"] = false;
            }
            */
            /** search criteria * */

            $job_sid = urldecode($job_sid);
            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            if(!empty($start_date) && $start_date != 'all') {
                $start_date_db = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
            } else {
                $start_date_db = date('Y-m-d 00:00:00');
            }

            if(!empty($end_date) && $end_date != 'all') {
                $end_date_db = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
            } else {
                $end_date_db = date('Y-m-d 23:59:59');
            }

            if ($job_sid != null || $job_sid != 'all') {
                $data['job_sid_array']                                          = explode(',', $job_sid);
            }

            /** pagination * */
            $records_per_page = PAGINATION_RECORDS_PER_PAGE;
            $page = $this->input->get('page');

            $page = empty($page) || is_null($page) ? 1 : $page;
            $my_offset = 0;

            if ($page > 1) {
                $my_offset = ($page - 1) * $records_per_page;
            }

            $baseUrl = base_url('reports/job_products_report/' . urlencode($company_sid) . '/' . urlencode($product_sid) . '/' . urlencode($job_sid) . '/' . urlencode( $start_date) . '/' . urlencode( $end_date));
            $total_records = $this->Reports_model->get_job_products($company_sid, $product_sid, $job_sid, $start_date_db, $end_date_db, true, $records_per_page, $my_offset);

            $uri_segment = 8;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $total_records;
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;

            $config["num_links"] = 5;
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo;';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = '&raquo;';
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
            //$config['query_string_segment'] = 'page';

            $this->pagination->initialize($config);
            $data["page_links"] = $this->pagination->create_links();
            /** pagination end * */

            //$export_data_products = $this->Reports_model->get_job_products($company_sid, null, null, $search, $between);

            //$data['products'] = $this->Reports_model->get_job_products($company_sid, $records_per_page, $my_offset, $search, $between);
            $export_data_products = $this->Reports_model->get_job_products($company_sid, $product_sid, $job_sid, $start_date_db, $end_date_db, false, $records_per_page, $my_offset);
            $data['products'] = $export_data_products;


            // $data['active_products'] = $this->Reports_model->get_active_products();
            $data['active_products'] = $this->Reports_model->get_purchased_products_by_company($company_sid);
            $data['active_jobs'] = $this->Reports_model->get_active_jobs($company_sid);

            //$pro = $this->Reports_model->get_purchased_products_by_company($company_sid);

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($export_data_products) && sizeof($export_data_products) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, ['Company Name' , getCompanyNameBySid($company_sid)]);

                    fputcsv($output, array('Job ID', 'Job Title', 'Product Name', 'Advertised Date'));

                    foreach ($export_data_products as $product) {
                        $input = array();
                        $input['job_sid'] = $product['job_sid'];
                        $input['job_title'] = $product['job_title'];
                        $input['product_name'] = $product['product_name'];
                        $input['purchased_date'] = date_with_time($product['purchased_date']);
                        fputcsv($output, $input);
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export sheet file * */

            $data['current_page'] = $page;
            $data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
            $data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;
            $data['record_count'] = $total_records;

            $this->load->view('main/header', $data);
            $this->load->view('reports/job_products_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
