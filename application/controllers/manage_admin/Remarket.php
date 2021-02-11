<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Remarket extends Admin_Controller {
    private $limit = 50;
    function __construct() {
        parent::__construct();
        $this->load->model('manage_admin/remarket_model');
        $this->load->model('manage_admin/company_model');
    }
    /**
     * Send response
     *
     * @param $array Array
     *
     * @return JSON
     */
    private function resp($array){
        header('content-type: application/json');
        echo json_encode($array);
        exit(0);
    }
    function remarket_company_settings ($company_sid = null) {
        if ($company_sid == NULL || $company_sid == '' || $company_sid == 0) {
            $this->session->set_flashdata('message', 'Company not found!');
            redirect('manage_admin/companies/', 'refresh');
        }
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
        
        $this->data['company_sid'] = $company_sid;
        $company_name = $this->company_model->get_company_name($company_sid);
        $this->data['company_name'] = $company_name;
        $this->data['remarket_company_settings'] = $this->remarket_model->get_remarket_company_settings($company_sid);
        
        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Remarket company settings';
            $this->render('manage_admin/remarket/remarket_company_settings');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            $company_sid = $formpost['company_sid'];
            $this->remarket_model->update_remarket_company_settings($company_sid, $formpost);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Remarket company settings updated successfully.');
            redirect('manage_admin/remarket/remarket_company_settings/' . $company_sid, 'location');
        }
    }
    function remarket_settings () {
        
        $redirect_url = 'manage_admin/remarket/remarket_settings';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        $this->form_validation->set_rules('jobs', 'Jobs', 'required|trim|xss_clean');
        $this->form_validation->set_rules('duration', 'Duration', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email_template_sid', 'Email Template', 'required|trim|xss_clean');
        
        $this->data['remarket_settings'] = $this->remarket_model->get_remarket_settings();
        $this->data['email_templates'] = $this->remarket_model->get_remarket_templates();
        
        if ($this->form_validation->run() == false) {
            $this->data['page_title'] = 'Remarket settings';
            $this->render('manage_admin/remarket/remarket_settings');
        } else {
            $formpost = $this->input->post(NULL, TRUE);
            if($this->data['remarket_settings']['jobs'] != $formpost['jobs'] || $this->data['remarket_settings']['duration'] != $formpost['duration'] || $this->data['remarket_settings']['email_template_sid'] != $formpost['email_template_sid']){
                $url = REMARKET_PORTAL_BASE_URL."/remarket_settings/".REMARKET_PORTAL_KEY;
                send_settings_to_remarket($url,$formpost);
            }
            
            $this->remarket_model->update_remarket_settings($formpost);
            $this->session->set_flashdata('message', '<strong>Success:</strong> Remarket settings updated successfully.');
            redirect($redirect_url, 'location');
        }
    }
    function campaign ($campaign_sid) {
        
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'remarket/campaign');
        
        $this->form_validation->set_rules('name', 'Jobs', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email_template_sid', 'Email Template', 'required|trim|xss_clean');
        
        $this->data['remarket_settings'] = $this->remarket_model->get_remarket_settings();
        $this->data['email_templates'] = $this->remarket_model->get_remarket_templates();
        $all_active_jobs = $this->remarket_model->filters_of_active_jobs();
        $countries_array[38] = array(   'sid' => 38, 'country_code' => 'CA', 'country_name' => 'Canada');
        $countries_array[227] = array(  'sid' => 227, 'country_code' => 'US', 'country_name' => 'United States');
        $this->data['active_countries'] = $countries_array;
        $state_ids = array();        
        $cat_ids = array();
        $company_ids = array();
        $categories_in_active_jobs = array();
        $country_states_array = array();
        $companies_list = array();
        $states = array();
        
        if (!empty($all_active_jobs)) { // we need it for search filters as we only need to show filters as per active jobs only
            for($i=0; $i<count($all_active_jobs); $i++) {
                
                $state_id = $all_active_jobs[$i]['Location_State'];
                if (!empty($state_id) && $state_id != 'undefined') {
                    $state_ids[$state_id] = $state_id;
                }
                $company_sid = $all_active_jobs[$i]['user_sid'];
                if (!empty($company_sid)) {
                    $company_ids[$company_sid] = $company_sid;
                }
                
                $JobCategorys = $all_active_jobs[$i]['JobCategory'];
                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    foreach ($cat_id as $id) {
                        $cat_ids[$id] = $id;
                    }
                }
            }
            $state_ids = array_values($state_ids);
            $state_names = $this->remarket_model->get_statenames($state_ids,38);
            foreach($state_names as $state_name){
                $country_states_array[38][] = array('sid' => $state_name['sid'], 'state_name' => $state_name['state_name']);
                $states[$state_name['sid']] = $state_name['state_name'];
            }
            $state_names = $this->remarket_model->get_statenames($state_ids,227);
            foreach($state_names as $state_name){
                $country_states_array[227][] = array('sid' => $state_name['sid'], 'state_name' => $state_name['state_name']);
                $states[$state_name['sid']] = $state_name['state_name'];
            }
            $cat_ids = array_values($cat_ids);
            $categories = $this->remarket_model->get_job_categories($cat_ids);
            foreach($categories as $category){
                $categories_in_active_jobs[$category['sid']] = $category['value'];
            }
            $company_sids = array_values($company_ids);
            
            $companies = $this->remarket_model->get_all_companies($company_sids);
            foreach($companies as $company){
                $companies_list[$company['sid']] = $company['CompanyName'];
            }
        }
        $this->data['categories_in_active_jobs'] = $categories_in_active_jobs;
        $this->data['country_states_array'] = htmlentities(json_encode($country_states_array));
        $this->data['companies_list'] = $companies_list;
        $this->data['campaign_sid'] = $campaign_sid;
        
        if($campaign_sid == 0)
            $this->data['page_title'] = 'Create New Campaign';
        else
            $this->data['page_title'] = 'Update Campaign';
        $this->render('manage_admin/remarket/remarket_campaign');
    }
    function remarket_campaigns () {
        
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'remarket/remarket_campaigns');
        
        $this->data['page_title'] = 'Remarket Campaigns';
        $this->render('manage_admin/remarket/remarket_campaigns');
    }
    function search_jobs(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'remarket/search_jobs');
        // Check for ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        
        //
        $jobs = $this->remarket_model->fetchJobsByFilters($formpost, $this->limit);
        if(!$jobs){
            $resp['Response'] = 'No jobs found.';
            $this->resp($resp);
        }
        // FWhen page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        if($formpost['page'] == 1){
            $resp['Limit'] = $this->limit;
            $resp['Records'] = $jobs['Jobs'];
            $resp['TotalPages'] = ceil( $jobs['JobCount'] / $this->limit );
            $resp['TotalRecords'] = $jobs['JobCount'];
        } else $resp['Records'] = $jobs;
        $this->resp($resp);
    }
    function search_applicants(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'remarket/search_applicants');
        // Check for ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        
        //
        $applicants = $this->remarket_model->fetchApplicantsByFilters($formpost, $this->limit);
        if(!$applicants){
            $resp['Response'] = 'No applicants found.';
            $this->resp($resp);
        }
        // FWhen page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        if($formpost['page'] == 1){
            $resp['Limit'] = $this->limit;
            $resp['Records'] = $applicants['Applicants'];
            $resp['TotalPages'] = ceil( $applicants['ApplicantCount'] / $this->limit );
            $resp['TotalRecords'] = $applicants['ApplicantCount'];
        } else $resp['Records'] = $applicants;
        $this->resp($resp);
    }
    function campaign_jobs_details(){
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'remarket/campaign_jobs_details');
        // Check for ajax request
        if(!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST'|| !sizeof($this->input->post())) exit(0);
        // Set return array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request';
        // Save post
        $formpost = $this->input->post(NULL, TRUE);
        
        //
        $jobs = $this->remarket_model->getJobDetails($formpost['jobIds']);
        if(!$jobs){
            $resp['Response'] = 'No jobs found.';
            $this->resp($resp);
        }
        // FWhen page is 1 send back total pages records
        $resp['Status'] = TRUE;
        $resp['Response'] = 'Proceed';
        $resp['Records'] = $jobs;
        $this->resp($resp);
    }    
} 