<?php

class Job_products_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_active_products(){
        $this->db->select('sid, name');
        $this->db->where('active', 1);
        $this->db->where('product_type', 'job-board');
        return $this->db->get('products')->result_array();
    }
    
    public function get_active_jobs(){
        $this->db->select('sid, Title');
        $this->db->where('active', 1);
        return $this->db->get('portal_job_listings')->result_array();
    }
    
    public function get_job_products($company_sid = NULL, $brand_sid = NULL, $limit = null, $start = null, $search = '', $between = ''){

        if($brand_sid == NULL && $company_sid == NULL){
            // do nothing
        } else if ($brand_sid == NULL) {
            $this->db->where('company_sid', $company_sid);
        } else if ($company_sid == NULL) {
            $companies = $this->get_brand_companies($brand_sid);
            $company_sids = array();

            foreach ($companies as $company) {
                $company_sids[] = $company['company_sid'];
            }
            
            if (!empty($company_sids)) {
                $this->db->where_in('company_sid', $company_sids);
            } else {
                return array();
            }
        }
        $this->db->select('jobs_to_feed.*');
        $this->db->join('portal_job_listings','portal_job_listings.sid=jobs_to_feed.job_sid','left');
        $this->db->select('portal_job_listings.Location_State');
        $this->db->select('portal_job_listings.Location_City');
        $this->db->where('company_sid >', 0);
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        if($search != '' && $search != NULL){
            if(isset($search['job_sid'])){
                $check_jobs_exists = explode(',', $search['job_sid']);
                if (!in_array('all', $check_jobs_exists)) {
                    if (is_array($check_jobs_exists)) {
                        $this->db->where_in('job_sid', $check_jobs_exists);
                    } else {
                        $this->db->where('job_sid', $search['job_sid']);
                    }
                }
            }
            elseif(isset($search['product_sid'])){
                $this->db->where($search);
            }
        }
        
        if($between != '' && $between != NULL){
            $this->db->where($between);
        }
        $this->db->order_by("jobs_to_feed.sid", "desc");
        $products = $this->db->get('jobs_to_feed')->result_array();
        $i = 0;
        foreach($products as $product){
            $job_title = get_job_title($product['job_sid']);
           
            $product_name = db_get_products_details($product['product_sid']);
            $company_name = get_company_details($product['company_sid']);
            
            $products[$i]['job_title'] = $job_title;
            
            if(isset($product_name['name'])){
                $products[$i]['product_name'] = $product_name['name'];
            } else {
                $products[$i]['product_name'] = '';
            }
            
            if(isset($company_name['CompanyName'])){
                $products[$i]['company_name'] = $company_name['CompanyName'];
            } else {
                $products[$i]['company_name'] = '';
            }
            
            $i++;
        } 
        
        return $products;
    }
    
    public function get_job_products_count(){
        $this->db->where('company_sid > ', 0);
        return $this->db->get('jobs_to_feed')->num_rows();
    }
    
    function get_all_companies(){
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }
    
    function get_all_oem_brands() {
        $this->db->select('sid, oem_brand_name');
        $this->db->where('brand_status', 'active');
        return $this->db->get('oem_brands')->result_array();
    }

    function get_brand_companies($brand_sid) {
        $this->db->select('distinct(company_sid)');
        $this->db->where('oem_brand_sid', $brand_sid);
        return $this->db->get('oem_brands_companies')->result_array();
    }
}
