<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/advanced_report_model');
    }

    //
    function facebook_job_report(){
        //
        $passArray = [];
        //
        $jobs = json_decode(getFileData(base_url("Facebook_feed/index/1")), true);
        //
        foreach($jobs as $job){
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
        foreach($facebookJobs as $job){
            //
            if(isset($passArray[$job['job_id']])){
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
    function blacklist_email(){
        //
        $this->data['jobs'] = $this->db
        ->order_by('blacklist_emails.note', 'DESC')
        ->get('blacklist_emails')
        ->result_array();

        //
        $this->render('manage_admin/reports/blacklist_emails');
    }


}