<?php defined('BASEPATH') OR exit('No direct script access allowed');

class jobs_for_facebook extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('Facebook_configuration_model');
    }

    public function index($fb_unique_identifier = null) {
        $data = array();
        $data['title'] = 'Job Listings';
        $is_ams = false;
        
        if ($fb_unique_identifier != null) {
            $fbConfiguration = $this->Facebook_configuration_model->GetFacebookConfigurationByUniqueString($fb_unique_identifier);

            if (!empty($fbConfiguration)) {
                $company_sid = $fbConfiguration['company_sid'];
                $domain_name = db_get_sub_domain($company_sid);
                
                if($company_sid == 57 || $company_sid == 5089) {
                    $is_ams = true;
                    $domain_name = 'www.automotosocial.com';
                    $career_site_only_companies = $this->Facebook_configuration_model->get_career_site_only_companies(); // get the career site status for companies
                    $career_site_company_sid = array();
                    
                    if(!empty($career_site_only_companies)) {
                            foreach($career_site_only_companies as $csoc) {
                                $career_site_company_sid[] = $csoc['sid'];
                            }
                        }
                }

                $Jobs = $this->Facebook_configuration_model->fetch_active_jobs($company_sid, $career_site_company_sid);
//echo '<pre>'; print_r($Jobs); exit;
                if (!empty($Jobs)) {
                    $companyDetails = $this->Facebook_configuration_model->get_company_logo($company_sid);
                    $data['company_details'] = $companyDetails;

                    foreach ($Jobs as $Key => $Job) {
                        $country_id = $Job['Location_Country'];
                        
                        if($is_ams) {
                            $company_sid = $Job['user_sid'];
                            $companyDetails = $this->Facebook_configuration_model->get_company_logo($company_sid);
                        }
                        
                        $Jobs[$Key]['company_logo'] = $companyDetails['Logo'];
                        
                        if (!empty($country_id)) {
                            if($country_id == 227){
                                $Jobs[$Key]['Location_Country'] =  'United States';
                            } else if ($country_id == 38){
                                $Jobs[$Key]['Location_Country'] =  'Canada';
                            } else { // for all other countries
                                $country_name = $this->Facebook_configuration_model->GetCountryNameById($country_id);

                                if (!empty($country_name[0]['country_name'])) {
                                    $Jobs[$Key]['Location_Country'] = $country_name[0]['country_name'];
                                }
                            }
                        } else { // country not found in database
                            $Jobs[$Key]['Location_Country'] = $country_name[0]['country_name'];
                        }

                        $state_id = $Job['Location_State'];
                        
                        if (!empty($state_id)) {
                            $state_name = $this->Facebook_configuration_model->GetStateNameById($state_id);
                            $Jobs[$Key]['Location_State'] = $state_name['state_name'];
                        }

                        $JobCategorys = $Job['JobCategory'];
                        
                        if ($JobCategorys != null) {
                            $cat_id = explode(',', $JobCategorys);
                            $job_category_array = array();
                            
                            foreach ($cat_id as $id) {
                                $job_cat_name = $this->Facebook_configuration_model->GetJobsCategoryNameById($id);
                                $job_category_array[] = $job_cat_name['value'];
                            }
                            
                            $job_category = implode(', ', $job_category_array);
                            $Jobs[$Key]['JobCategory'] = $job_category;
                        }

                        $Jobs[$Key]['Title'] = db_get_job_title($company_sid, $Jobs[$Key]['Title'], $Jobs[$Key]['Location_City'], $Jobs[$Key]['Location_State'], $Jobs[$Key]['Location_Country']);
                        $Jobs[$Key]['Job_Url'] = $domain_name . '/job_details/' . $Job['sid'];
                    }

                    $data['jobs'] = $Jobs;
                    $this->load->view('jobs_for_facebook/jobs_list_view', $data);
                } else {
                    echo 'No Jobs Found!';
                }
            } else {
                echo 'Wrong Unique Identifier!';
            }
        } else {
            echo 'Unique Identifier Not Provided.';
        }
    }
    
    public function testit($fb_unique_identifier = null) {
        $data = array();
        $data['title'] = 'Job Listings Test';
        $is_ams = false;
        
        if ($fb_unique_identifier != null) {
            $fbConfiguration = $this->Facebook_configuration_model->GetFacebookConfigurationByUniqueString($fb_unique_identifier);

            if (!empty($fbConfiguration)) {
                $company_sid = $fbConfiguration['company_sid'];
                $domain_name = db_get_sub_domain($company_sid);
                
                if($company_sid == 57 || $company_sid == 5089) {
                    echo 'I am IN '; exit;
                    $is_ams = true;
                    $domain_name = 'www.automotosocial.com';
                    $career_site_only_companies = $this->Facebook_configuration_model->get_career_site_only_companies(); // get the career site status for companies
                    $career_site_company_sid = array();
                    
                    if(!empty($career_site_only_companies)) {
                            foreach($career_site_only_companies as $csoc) {
                                $career_site_company_sid[] = $csoc['sid'];
                            }
                        }
                }

                $Jobs = $this->Facebook_configuration_model->fetch_active_jobs($company_sid, $career_site_company_sid);

                if (!empty($Jobs)) {
                    $companyDetails = $this->Facebook_configuration_model->get_company_logo($company_sid);
                    $data['company_details'] = $companyDetails;

                    foreach ($Jobs as $Key => $Job) {
                        $country_id = $Job['Location_Country'];
                        
                        if($is_ams) {
                            $company_sid = $Job['user_sid'];
                            $companyDetails = $this->Facebook_configuration_model->get_company_logo($company_sid);
                        }
                        
                        $Jobs[$Key]['company_logo'] = $companyDetails['Logo'];
                        
                        if (!empty($country_id)) {
                            if($country_id == 227){
                                $Jobs[$Key]['Location_Country'] =  'United States';
                            } else if ($country_id == 38){
                                $Jobs[$Key]['Location_Country'] =  'Canada';
                            } else { // for all other countries
                                $country_name = $this->Facebook_configuration_model->GetCountryNameById($country_id);

                                if (!empty($country_name[0]['country_name'])) {
                                    $Jobs[$Key]['Location_Country'] = $country_name[0]['country_name'];
                                }
                            }
                        } else { // country not found in database
                            $Jobs[$Key]['Location_Country'] = $country_name[0]['country_name'];
                        }

                        $state_id = $Job['Location_State'];
                        
                        if (!empty($state_id)) {
                            $state_name = $this->Facebook_configuration_model->GetStateNameById($state_id);
                            $Jobs[$Key]['Location_State'] = $state_name['state_name'];
                        }

                        $JobCategorys = $Job['JobCategory'];
                        
                        if ($JobCategorys != null) {
                            $cat_id = explode(',', $JobCategorys);
                            $job_category_array = array();
                            
                            foreach ($cat_id as $id) {
                                $job_cat_name = $this->Facebook_configuration_model->GetJobsCategoryNameById($id);
                                $job_category_array[] = $job_cat_name['value'];
                            }
                            
                            $job_category = implode(', ', $job_category_array);
                            $Jobs[$Key]['JobCategory'] = $job_category;
                        }

                        $Jobs[$Key]['Title'] = db_get_job_title($company_sid, $Jobs[$Key]['Title'], $Jobs[$Key]['Location_City'], $Jobs[$Key]['Location_State'], $Jobs[$Key]['Location_Country']);
                        $Jobs[$Key]['Job_Url'] = $domain_name . '/job_details/' . $Job['sid'];
                    }

                    $data['jobs'] = $Jobs;
                    echo '<pre>'; print_r($Jobs); exit;
                    $this->load->view('jobs_for_facebook/jobs_list_view', $data);
                } else {
                    echo 'No Jobs Found!';
                }
            } else {
                echo 'Wrong Unique Identifier!';
            }
        } else {
            echo 'Unique Identifier Not Provided.';
        }
    }
}
