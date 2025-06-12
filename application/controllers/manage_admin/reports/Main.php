<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Main extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
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
    function blacklist_email()
    {
        //
        $this->data['jobs'] = $this->db
            ->order_by('blacklist_emails.note', 'DESC')
            ->get('blacklist_emails')
            ->result_array();

        //
        $this->render('manage_admin/reports/blacklist_emails');
    }


    public function employeeProfileDataReport()
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
            fputcsv($output, array('IP', 'Agent', 'Page URl', 'Date'));

            foreach ($this->data['records'] as $dataRow) {

                $input = array();
                $input['IP'] = $dataRow['client_ip'];
                $input['Agent'] = $dataRow['client_agent'];
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
    public function indeedApplicantDispositionReport()
    {
        //
        $this->data['page_title'] = 'Indeed Applicant Disposition Status Report';
        // load user model
        $this->load->model('2022/User_model', 'user_model');
        // get filter records
        $this->data['records'] = $this->user_model->getIndeedApplicantDispositionData($this->input->get(null, false));

        if ($this->input->get('export') == 1) {

            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: CookiesData; filename=indeed_applicant_disposition_status_report.csv');
            $output = fopen('php://output', 'w');
            fputcsv($output, array(''));
            fputcsv($output, array('Cookies Data Indeed Applicant Disposition Status Report'));
            fputcsv($output, array(''));
            fputcsv($output, array('Applicant Name', 'Company Name', 'ATS Status', 'Indeed Status', 'Changed By', 'Action Date'));

            foreach ($this->data['records'] as $dataRow) {
                $input = array();
                $input['Applicant_Name'] = $dataRow['first_name'] . ' ' . $dataRow['last_name'];
                $input['Company_Name'] = $dataRow['CompanyName'];
                $input['ATS_Status'] = $dataRow['ats_status'];
                $input['Indeed_Status'] = $dataRow['indeed_status'];
                $input['Changed_By'] =  $dataRow['created_by'] != 0 ? getEmployeeOnlyNameBySID($dataRow['created_by']) : '';;
                $input['Action_Date'] = $dataRow['created_at'];

                fputcsv($output, $input);
            }
            fclose($output);
            exit;
        }

        //
        $this->render('manage_admin/company/indeed_applicant_disposition_report');
    }
}
