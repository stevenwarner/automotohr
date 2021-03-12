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
        $this->data['jobs'] = $this->db
        ->select('
            facebook_jobs_status.job_id,
            facebook_jobs_status.external_id,
            facebook_jobs_status.status,
            facebook_jobs_status.reason,
            facebook_jobs_status.created_at,
            facebook_jobs_status.updated_at,
            portal_job_listings.Title
        ')
        ->join('portal_job_listings', 'portal_job_listings.sid = facebook_jobs_status.job_id')
        ->order_by('facebook_jobs_status.sid', 'DESC')
        ->get('facebook_jobs_status')
        ->result_array();

        //
        $this->render('manage_admin/reports/facebook_jobs');
    }
    
    
    //
    function blacklist_email(){
        //
        $this->data['jobs'] = $this->db
        ->order_by('blacklist_emails.sid', 'DESC')
        ->get('blacklist_emails')
        ->result_array();

        //
        $this->render('manage_admin/reports/blacklist_emails');
    }


}