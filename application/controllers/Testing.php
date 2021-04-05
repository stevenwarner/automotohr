<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller{
    //
    public function __construct(){
        parent::__construct();
        $this->load->model('hr_documents_management_model');
        $this->load->model('test_model');
    }
	//
    function jobs(){
        //
        $jobs = $this->db->select('
            portal_job_listings.sid,
            portal_job_listings.status,
            portal_job_listings.active,
            portal_job_listings.approval_status,
            portal_job_listings.approval_status_change_datetime,
            portal_job_listings.organic_feed,
            portal_job_listings.activation_date,
            portal_job_listings.deactivation_date,
            portal_job_listings.published_on_career_page,
            portal_job_listings.expiration_date,
            users.sid as company_id,
            users.active as company_active,
            users.has_job_approval_rights,
            users.is_paid
        ')
        ->join('users', 'users.sid = portal_job_listings.user_sid', 'inner')
        ->get('portal_job_listings')
        ->result_array();
        //
        header('Content-Type: application/json');
        echo json_encode($jobs);
    }
}
