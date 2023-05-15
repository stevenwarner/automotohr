<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Job_categories_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Active New Hire Categories';
            $data['company_sid'] = $company_sid;

            $data['companyName'] = getCompanyNameBySid($company_sid);

            //**** working code ****//
            $data['categories'] = $this->Reports_model->GetAllJobCategoriesWhereApplicantsAreHired($company_sid);
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($data['categories']) && sizeof($data['categories']) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    fputcsv($output, ['Company Name' , getCompanyNameBySid($company_sid)]);
                    fputcsv($output, array('Sr. No', 'Category', 'Hire Count'));

                    foreach ($data['categories'] as $key => $category) {
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

            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/job_categories_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
