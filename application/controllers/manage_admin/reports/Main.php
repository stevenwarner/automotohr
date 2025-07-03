<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main extends Admin_Controller
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

    //
    function facebook_job_report()
    {
        //
        $passArray = [];
        //
        $jobs = json_decode(getFileData(base_url("Facebook_feed/index/1")), true);
        //
        foreach ($jobs as $job) {
            //
            $passArray[$job['jid']] = [
                'job_id' => $job['jid'],
                'job_status' => 'MISSING',
                'external_id' => '',
                'status' => '',
                'is_deleted' => 0,
                'reason' => '',
                'updated_at' => $job['publish_date_orginal'],
                'Title' => $job['title']
            ];
        }
        //
        $facebookJobs = $this->db
            ->select('
            facebook_jobs_status.job_id,
            facebook_jobs_status.job_status,
            facebook_jobs_status.external_id,
            facebook_jobs_status.status,
            facebook_jobs_status.is_deleted,
            facebook_jobs_status.reason,
            facebook_jobs_status.updated_at
        ')
            ->get('facebook_jobs_status')
            ->result_array();
        //
        foreach ($facebookJobs as $job) {
            //
            if (isset($passArray[$job['job_id']])) {
                $passArray[$job['job_id']]['job_status'] = $job['job_status'];
                $passArray[$job['job_id']]['external_id'] = $job['external_id'];
                $passArray[$job['job_id']]['status'] = $job['status'];
                $passArray[$job['job_id']]['is_deleted'] = $job['is_deleted'];
                $passArray[$job['job_id']]['reason'] = $job['reason'];
                $passArray[$job['job_id']]['updated_at'] = $job['updated_at'];
            }
        }
        //
        $this->data['Jobs'] = array_values($passArray);
        //
        $this->render('manage_admin/reports/facebook_jobs');
    }


    //
    function blacklist_email($page_number = 1)
    {
        //
        $this->load->model('2022/User_model', 'user_model');
        $this->data['flag'] = true;

        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }

        $total_records = $this->user_model->getBlacklistEmailReport(1);
        $reportData = $this->user_model->getBlacklistEmailReport(0, $per_page, $offset);

        $this->load->library('pagination');

        $pagination_base = base_url('manage_admin/reports/main/blacklist_email');

        //echo $pagination_base;
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 0;
        $config["num_links"] = 1;
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
        $this->data['data_count'] = $total_records;
        $this->data['reportData'] = $reportData;
        //
        $this->render('manage_admin/reports/blacklist_emails');
    }


    //
    public function employeeProfileDataReport(
        $startdate = 'all',
        $enddate = 'all',
        $page_number = 1
    ) {

        //
        $this->data['page_title'] = 'Employee Profile Update - Report';
        // load user model
        $this->load->model('2022/User_model', 'user_model');
        // get filter records

        $start_date = urldecode($startdate);
        $end_date = urldecode($enddate);
        $this->data['flag'] = true;

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

        $per_page = PAGINATION_RECORDS_PER_PAGE;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }
        //
        $total_records = $this->user_model->getEmployeeHistoryReport($start_date_applied, $end_date_applied, 1);

        $reportData = $this->user_model->getEmployeeHistoryReport($start_date_applied, $end_date_applied, 0, $per_page, $offset);

        $this->load->library('pagination');

        $pagination_base = base_url('employee_profile_data_report') . '/' . urlencode($start_date) . '/' . urlencode($end_date);

        //echo $pagination_base;
        $config = array();
        $config["base_url"] = $pagination_base;
        $config["total_rows"] = $total_records;
        $config["per_page"] = $per_page;
        $config["uri_segment"] = 4;
        $config["num_links"] = 4;
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
        $this->data['reportData'] = $reportData;
        //
        $this->render('manage_admin/company/employee_profile_change_report');
    }

    public function employeeProfileDataReport_OLD()
    {

        //
        $this->data['page_title'] = 'Employee Profile Update - Report';
        // load user model
        $this->load->model('2022/User_model', 'user_model');
        // get filter records
        $this->data['records'] = $this->user_model->getEmployeeHistory($this->input->get(null, false));
        //
        $this->render('manage_admin/company/employee_profile_change_report');
    }

    //
    public function aiWhishlistDataReport()
    {
        //
        $this->data['page_title'] = 'AI Recruiter Wait-list Report';
        // load user model
        $this->load->model('2022/User_model', 'user_model');
        // get filter records
        $this->data['records'] = $this->user_model->getAiWhishlistData($this->input->get(null, false));
        //
        $this->render('manage_admin/company/ai_whishlist_report');
    }

    //

    public function cookiesDataReport()
    {
        //
        $this->data['page_title'] = 'Cookies Report';
        // load user model
        $this->load->model('2022/User_model', 'user_model');
        // get filter records
        $this->data['records'] = $this->user_model->getCookiesData($this->input->get(null, false));

        if ($this->input->get('export') == 1) {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: CookiesData; filename=CookiesData.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, array(''));
            fputcsv($output, array('Cookies Data Report'));
            fputcsv($output, array(''));
            fputcsv($output, array('IP', 'Agent', 'Preferences', 'Page URl', 'Date'));

            foreach ($this->data['records'] as $dataRow) {

                $input = array();
                $input['IP'] = $dataRow['client_ip'];
                $input['Agent'] = $dataRow['client_agent'];
                if ($dataRow['preferences'] != '') {
                    $prefArray = json_decode($dataRow['client_agent'], true);
                    $doNotSell = $dataRow['doNotSell'] == 'true' ? "Yes" : "NO";
                    $performance = $dataRow['performance'] == 'true' ? "Yes" : "NO";
                    $analytics = $dataRow['analytics'] == 'true' ? "Yes" : "NO";
                    $marketing = $dataRow['marketing'] == 'true' ? "Yes" : "NO";
                    $social = $dataRow['social'] == 'true' ? "Yes" : "NO";
                    $unclassified = $dataRow['unclassified'] == 'true' ? "Yes" : "NO";
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
                $input['Page_URl'] = $dataRow['page_url'];
                $input['Date'] = $dataRow['created_at'];
                fputcsv($output, $input);
            }
            fclose($output);
            exit;
        }

        //
        $this->render('manage_admin/company/cookies_report');
    }

    //
    public function indeedApplicantDispositionReport(int $pageNumber = 0)
    {

        $this->load->model('2022/User_model', 'user_model');
        // query params
        $this->data["filter"]["companies"] = $this->input->get("companies", true) ?? ["All"];
        $this->data["filter"]["status"] = $this->input->get("status", true) ?? ["All"];
        $this->data["filter"]["startDate"] = $this->input->get("start", true) ?? "";
        $this->data["filter"]["endDate"] = $this->input->get("end", true) ?? "";
        $this->data["filter"]["applicantName"] = $this->input->get("applicantname", true) ?? "";
        $this->data["filter"]["atsIndeedId"] = $this->input->get("atsindeedid", true) ?? "";


        $this->data['page_title'] = 'Indeed Applicant Disposition Status Report';
        $this->data['companies'] = $this->user_model->get_all_companies();
        $this->data['flag'] = true;

        // get the records
        $this->data["counts"] = $this
            ->user_model
            ->getIndeedApplicantDispositionDataCount(
                $this->data["filter"]
            );

        // set pagination
        $per_page =  PAGINATION_RECORDS_PER_PAGE;
        // $page_number = $pageNumber;
        $page_number = isset($_GET['page']) ? $_GET['page'] : 0;
        $offset = 0;
        if ($page_number > 1) {
            $offset = ($page_number - 1) * $per_page;
        }

        $config = array();
        $config["base_url"] = base_url("indeed_applicant_disposition_report?" . (str_replace("page=", "pg=", $_SERVER['QUERY_STRING'])));
        $config["total_rows"] = $this->data["counts"]["records"];
        $config["per_page"] = $per_page;
        $config['page_query_string'] = TRUE; // Enable query string for pagination
        $config['query_string_segment'] = 'page'; // Query string parameter for pagination (default: 'per_page')

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
        $this->data['to_records'] = $this->data["counts"]["records"] < $per_page ? $this->data["counts"]["records"] : $offset + $per_page;

        //ExportData
        if ($this->input->get("perform_action", true)) {

            // query params
            $data["filter"]["companies"] = $this->input->get("companies", true) ?? ["All"];
            $data["filter"]["status"] = $this->input->get("status", true) ?? ["All"];
            $data["filter"]["startDate"] = $this->input->get("start", true) ?? "";
            $data["filter"]["endDate"] = $this->input->get("end", true) ?? "";
            $data["filter"]["applicantName"] = $this->input->get("applicantname", true) ?? "";
            $data["filter"]["atsIndeedId"] = $this->input->get("atsindeedid", true) ?? "";

            $records = $this
                ->user_model
                ->getIndeedApplicantDispositionCSV(
                    $data["filter"]
                );

            header('Content-Type: text/csv; charset=utf-8');
            header("Content-Disposition: attachment; filename= indeed_applicant_disposition_status_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
            $output = fopen('php://output', 'w');

            if (!empty($data["filter"]["startDate"]) && !empty($data["filter"]["endDate"])) {
                fputcsv($output, array(
                    "Period: ",
                    $data["filter"]["startDate"] . " - " . $data["filter"]["endDate"],
                ));
            }

            fputcsv($output, array(
                "Export Date",
                date('m/d/Y H:i:s ', strtotime('now'))
            ));

            fputcsv($output, array('', ''));

            fputcsv(
                $output,
                array(
                    'Applicant Name',
                    'Company Name',
                    'ATS Status',
                    'ATS Indeed Id',
                    'Indeed Status',
                    'Changed By',
                    'Action Date'
                )
            );

            if (!empty($records)) {
                $companyCache = [];

                foreach ($records as $row) {
                    $a = [];
                    $a[] = $row['first_name'] . ' ' . $row['last_name'];
                    $a[] = $row['CompanyName'];
                    $a[] = $row['ats_status'];
                    $a[] = $row['indeed_ats_sid'];
                    $a[] = $row['indeed_status'];
                    $a[] =  $row['created_by'] != 0 ? getEmployeeOnlyNameBySID($row['created_by']) : '';
                    $a[] = $row['created_at'];
                    //
                    fputcsv($output, $a);
                }
            }

            fclose($output);
            exit;
        }

        // get the records
        $this->data["records"] = $this
            ->user_model
            ->getIndeedApplicantDispositionRecords(
                $this->data["filter"],
                $per_page,
                $offset
            );
        //
        $this->render('manage_admin/company/indeed_applicant_disposition_report');
    }

    //
    public function indeedApplicantDispositionReportLog($indeedATSId, $atsStatus)
    {
        // 
        $this->load->model('2022/User_model', 'user_model');


        $record = $this
            ->user_model
            ->getIndeedApplicantDispositionReportLog(
                $indeedATSId,
                $atsStatus
            );

        return SendResponse(
            200,
            [
                "view" => $this->load->view("manage_admin/company/indeed_applicant_disposition_report_log", ["record" => $record], true)
            ]
        );
    }


    //

    public function indeedApplicantDispositionReportResend($companySId, $jobSid)
    {
        //
        $this->load->model("Indeed_model", "indeed_model");
        $msg = $this->indeed_model->updateJobToQueue(
            $jobSid,
            $companySId,
            true
        );

        $this->resp($msg);
    }



    private function resp($array)
    {
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }
}
