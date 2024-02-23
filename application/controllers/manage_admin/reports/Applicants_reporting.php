<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Applicants_reporting extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_reporting_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($company_sid = '', $keyword = 'all',  $page_number = 1)
    {
        $redirect_url       = 'manage_admin';
        $function_name      = 'applicants_reporting';

        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name

        $this->data['page_title'] = 'Applicants Reporting';
        $this->data['companies'] = $this->advanced_reporting_model->get_all_companies();

        $keyword = urldecode($keyword);
        $company_sid = urldecode($company_sid);

        //---Pagination-//
        $per_page = 10;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }
        $total_records = $this->advanced_reporting_model->get_applicants($company_sid, $keyword, true);

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/applicantsReporting') . '/' . $company_sid . '/' . urlencode($keyword);

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 6;
        $config["num_links"] = 6;
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

        //--Pagination Ends---//

        $applicants = $this->advanced_reporting_model->get_applicants($company_sid, $keyword, false, $per_page, $offset);

        $this->data['applicants'] = $applicants;

        $this->data['applicants_count'] = $total_records;

        if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {

            $applicants = $this->advanced_reporting_model->get_applicants($company_sid, $keyword, false);
            if (isset($applicants) && sizeof($applicants) > 0) {

                header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=data.csv');
                $output = fopen('php://output', 'w');

                fputcsv($output, array('employee', 'Company', 'Applied jobs', 'last Applied'));

                foreach ($applicants as $applicant) {
                    $input = array();
                    $input['employee'] = "Name: " . $applicant['first_name'] . '' . $applicant['last_name'] . "\n" . 'Email:' . $applicant['email'] . "\n" . 'Phone Number:' . $applicant['phone_number'] . "\n" . 'Address:' . $applicant['address'];
                    $input['Company'] = $applicant['CompanyName'];
                    $input['Applied jobs'] = $applicant['total_jobs_applied'];
                    $input['last Applied'] = date_with_time($applicant['date_applied']);
                    fputcsv($output, $input);
                }
                fclose($output);
                exit;
            } else {
                $this->session->set_flashdata('message', 'No data found.');
            }
        }

        $this->render('manage_admin/reports/applicants_reporting');
    }


    //
    public function viewJobs($applicantSid)
    {
        $applicants = $this->advanced_reporting_model->get_applicants_jobs($applicantSid);
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view(
                    'manage_admin/reports/applicants_reporting_jobs',
                    ['applicants' => $applicants],
                    true
                )
            ]
        );
    }
}
