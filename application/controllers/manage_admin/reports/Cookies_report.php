<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cookies_report extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
        $this->load->library('form_validation');
        $this->load->library("pagination");
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }

    public function index($preferences = 'all', $website = 'all', $startdate = 'all', $enddate = 'all', $ip = 'all', $page_number = 1)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_origination_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $preferences = urldecode($preferences);
        $start_date = urldecode($startdate);
        $end_date = urldecode($enddate);
        $website = urldecode($website);
        $ip = urldecode($ip);
        $this->data['flag'] = true;
        $this->form_validation->set_data($this->input->get(NULL, true));


        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_applied = "";
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_applied = "";
        }

        $this->data['page_title'] = 'Cookies Report';
        // $main_companies = $this->advanced_report_model->get_all_companies();
        // $this->data['companies'] = $main_companies;

        $result_array = array();


        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }
        //
        $total_records = $this->advanced_report_model->get_cookies_report($preferences, $website, $start_date_applied, $end_date_applied,  $ip, 1);
        $logData = $this->advanced_report_model->get_cookies_report($preferences, $website, $start_date_applied, $end_date_applied, $ip, 0, $per_page, $offset);

        // $final_applicants = array();
        // $final_applicants = $logData;

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/cookies_report') . '/' . $preferences . '/' . urlencode($website) . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/'  . urlencode($ip);

        //echo $pagination_base;

        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 9;
        $config["num_links"] = 9;
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

        $this->data['applicants_count'] = $total_records;
        $this->data['logData'] = $logData;
        $this->render('manage_admin/reports/cookies_report');
    }

    public function view_detail($sid)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_resume_analysis_detail';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $applicantResume = $this->advanced_report_model->get_applicant_resume_analysis($sid);
        //
        $this->data["applicant"] = $applicantResume;
        $this->render('manage_admin/reports/applicant_resume_analysis');
    }

    //
    public function export($preferences = 'all', $website = 'all', $startdate = 'all', $enddate = 'all', $ip = 'all', $page_number = 1)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin';
        $function_name = 'applicant_origination_report';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        $preferences = urldecode($preferences);
        $start_date = urldecode($startdate);
        $end_date = urldecode($enddate);
        $website = urldecode($website);
        $ip = urldecode($ip);
        $this->data['flag'] = true;
        $this->form_validation->set_data($this->input->get(NULL, true));


        if (!empty($start_date) && $start_date != 'all') {
            $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d 00:00:00');
        } else {
            $start_date_applied = "";
        }

        if (!empty($end_date) && $end_date != 'all') {
            $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d 23:59:59');
        } else {
            $end_date_applied = "";
        }

        //
        $logData = $this->advanced_report_model->get_cookies_report($preferences, $website, $start_date_applied, $end_date_applied, $ip, 0);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: CookiesData; filename=CookiesData.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, array(''));
        fputcsv($output, array('Cookies Data Report'));
        fputcsv($output, array(''));
        fputcsv($output, array('IP', 'User', 'Page', 'Preferences', 'Agent', 'Date'));
        foreach ($logData as $dataRow) {

            $input = array();
            $input['IP'] = $dataRow['client_ip'];
            $input['User'] =  $dataRow['user_sid'] == 0 ? "Applicant" : "Employee";
            $input['Page_URl'] = $dataRow['page_url'];

            if ($dataRow['preferences'] != '') {
                $prefArray = json_decode($dataRow['preferences'], true);
                $doNotSell = $prefArray['doNotSell'] == 'true' ? "Yes" : "NO";
                $performance = $prefArray['performance'] == 'true' ? "Yes" : "NO";
                $analytics = $prefArray['analytics'] == 'true' ? "Yes" : "NO";
                $marketing = $prefArray['marketing'] == 'true' ? "Yes" : "NO";
                $social = $prefArray['social'] == 'true' ? "Yes" : "NO";
                $unclassified = $prefArray['unclassified'] == 'true' ? "Yes" : "NO";
                $pref = '';
                $pref .=  "Do not sell: " . $doNotSell . "\n";
                $pref .= "Performance: " . $performance . "\n";
                $pref .= "Analytics: " . $analytics . "\n";
                $pref .= "Marketing: " . $marketing . "\n";
                $pref .= "Social: " . $social . "\n";
                $pref .= "Unclassified: " . $unclassified;

                $input['Preferences'] = $pref;
            } else {
                $input['Preferences'] = '';
            }
            $input['Agent'] = $dataRow['client_agent'];
            $input['Date'] = $dataRow['created_at'];
            fputcsv($output, $input);
        }
        fclose($output);
        exit;
    }
}
