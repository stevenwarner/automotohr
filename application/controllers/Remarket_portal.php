<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Remarket_portal extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('remarket_portal_model');
    }

    /**
    * @ Get all jobs from system and company check 
	* @date 12/14/2020
	* @employer Aleem 
	* @return  json
    **/
    public function index () {
    	//
    	header('content-type:application/json');
    	//
    	$companies = $this->remarket_portal_model->get_all_ahr_companies();
    	//
       	$jobs = $this->remarket_portal_model->get_all_ahr_jobs();
       	//
       	if (empty($companies) || empty($jobs)) {
    		echo '[]';
    		exit;
    	} 
    	//
       	foreach ($jobs as $key => $job) {
       		if (isset($companies[$job['user_sid']])) {
       			$company_data = $companies[$job['user_sid']];
	       		$jobs[$key]['company_active'] = $company_data['active'];
	       		$jobs[$key]['is_paid'] = $company_data['is_paid'];
	       		$jobs[$key]['has_job_approval_rights'] = $company_data['has_job_approval_rights'];
       		} else {
       			unset($jobs[$key]);
       		}
       	}
       	//
       	echo json_encode($jobs);
    }
}