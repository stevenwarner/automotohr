<?php
class Facebook_configuration_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function fetch_all_active_jobs($company_sid) {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        //$this->db->limit($limit, $start);
        
        $records_obj = $this->db->get('portal_job_listings');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        return $result;
    }
    
    function fetch_active_jobs($company_sid, $ams=array()) {
        $this->db->select('sid, user_sid, Title, Location_City, Location_Country, Location_State, JobCategory, pictures');
        
        if(empty($ams)){
            $this->db->where('user_sid', $company_sid);
        } else {
            $this->db->where_not_in('user_sid', $ams);
        }
        
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'desc');
        //$this->db->limit($limit, $start);
        
        $records_obj = $this->db->get('portal_job_listings');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        return $result;
    }

    //Facebook Configuration - Start
    public function Insert($sid, $company_sid, $fb_app_id, $fb_app_secret, $fb_page_url, $fb_unique_identifier) {
        $data = array(  'company_sid' => $company_sid,
                        'fb_app_id' => $fb_app_id,
                        'fb_app_secret' => $fb_app_secret,
                        'fb_page_url' => $fb_page_url,
                        'fb_unique_identifier' => $fb_unique_identifier);

        $this->db->insert('facebook_configuration', $data);
    }

    public function Update($sid, $company_sid, $fb_app_id, $fb_app_secret, $fb_page_url, $fb_unique_identifier) {
        $data = array(  'company_sid' => $company_sid,
                        'fb_app_id' => $fb_app_id,
                        'fb_app_secret' => $fb_app_secret,
                        'fb_page_url' => $fb_page_url,
                        'fb_unique_identifier' => $fb_unique_identifier);

        $this->db->where('sid', $sid);
        $this->db->update('facebook_configuration', $data);
    }

    public function Save($sid, $company_sid, $fb_app_id, $fb_app_secret, $fb_page_url, $fb_unique_identifier) {
        if ($sid == null) {
            $this->Insert($sid, $company_sid, $fb_app_id, $fb_app_secret, $fb_page_url, $fb_unique_identifier);
        } else {
            $this->Update($sid, $company_sid, $fb_app_id, $fb_app_secret, $fb_page_url, $fb_unique_identifier);
        }
    }

    public function GetFacebookConfiguration($company_sid) {
        $this->db->where('company_sid', $company_sid);
        
        $records_obj = $this->db->get('facebook_configuration');
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function GetFacebookConfigurationByUniqueString($unique_string) {
        $this->db->where('fb_unique_identifier', $unique_string);
        $records_obj = $this->db->get('facebook_configuration');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function GetCompanyDetails($sid) {
        $this->db->where('sid', $sid);
        
        $records_obj = $this->db->get('users');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function get_company_logo($sid) {
        $this->db->select('Logo');
        $this->db->where('sid', $sid);
        
        $records_obj = $this->db->get('users');
        $result = $records_obj->result_array();
        $records_obj->free_result();
                
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    public function GetCountryNameById($sid) {
        $this->db->select('country_name');
        $this->db->where('sid', $sid);
        
        $records_obj = $this->db->get('countries');
        $result = $records_obj->result_array();
        $records_obj->free_result();
        
        return $result;
    }

    public function GetStateNameById($sid) {
        $this->db->select('state_name');
        $this->db->where('sid', $sid);
        $this->db->where('active', '1');
        $this->db->limit(1);
        
        $records_obj = $this->db->get('states');
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array('state_name'=>'');
        }
    }

    public function GetJobsCategoryNameById($sid) {
        $this->db->select('value');
        $this->db->where('sid', $sid);
        $this->db->where('field_sid', '198');
        
        $records_obj = $this->db->get('listing_field_list');
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array('value'=>'');
        }
    }

    public function UpdateUniqueIdentifier($sid, $UniqueIdentifier) {
        $this->db->where('sid', $sid);
        $data = array('fb_unique_identifier' => $UniqueIdentifier);
        $this->db->update('facebook_configuration', $data);
    }
    //Facebook Configuration - End

    public function get_current_package_details($company_sid) {
        $this->db->select('account_package_sid');
        $this->db->where('sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $package_sid = $records_arr[0]['account_package_sid'];
            $this->db->select('*');
            $this->db->where('sid', $package_sid);
            $records_obj = $this->db->get('products');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                return $records_arr[0];
            } else {
                return array();
            }
        }
    }
    
    function get_career_site_only_companies() {
        $this->db->select('sid');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_site_listings_only', 1);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

}
