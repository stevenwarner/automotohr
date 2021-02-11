<?php class appearance_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_themes($user_sid) {
        $this->db->where('user_sid', $user_sid);
        $record_obj = $this->db->get('portal_themes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function run_query($qry) {
        return $this->db->query($qry);
    }

    function active_theme($theme_id, $employer_id) {
        //mark all theme deactive
        $this->db->set('theme_status', 0);
        $this->db->where('user_sid', $employer_id);
        $this->db->update('portal_themes');

        //now active only that theme you want to active
        $this->db->set('theme_status', 1);
        $this->db->where('sid', $theme_id);
        $this->db->update('portal_themes');
    }

    function SetThemeStatus($theme_id, $status = 0) {
        $this->db->where('sid', $theme_id);
        $data = array('theme_status' => $status);
        $this->db->update('portal_themes', $data);
    }

    function SetPurchasedStatus($theme_id, $purchased = 0) {
        $this->db->where('sid', $theme_id);
        $data = array('purchased' => $purchased);
        $this->db->update('portal_themes', $data);
    }

    function updateTheme($company_sid, $data) {
        $this->db->where('user_sid', $company_sid);
        $this->db->where('theme_name', 'theme-4');
        $this->db->update('portal_themes', $data);
    }

    function GetThemeId($theme_name, $user_sid) {
        $this->db->select('sid');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('theme_name', $theme_name);
        $this->db->limit(1);
        $record_obj = $this->db->get('portal_themes');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($result)) {
            return intval($result[0]['sid']);
        } else {
            return null;
        }
    }

    function get_enterprise_theme_product() {
        $this->db->where('product_type', 'account-package');
        $this->db->where('sid', 27);
        $this->db->where('active', 1);
        $record_obj = $this->db->get('products');
        $result = $record_obj->result_array();
        $record_obj->free_result();
        return $result;
    }

    function insert_career_page_logo_record($data) {
        $this->db->insert('career_page_logo', $data);
    }

    function update_career_page_logo_record($company_sid, $data) {
        $this->db->where('company_sid', $company_sid);
        $this->db->update('career_page_logo', $data);
    }

    function get_career_page_logo_record($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('career_page_logo');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    
    function get_all_job_fair_forms($company_sid) {
        $this->db->select('sid, title, page_url');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $default_form = $record_obj->result_array();
        $record_obj->free_result();
        
        if(!empty($default_form)) {
            $default_sid = $default_form[0]['sid'];
            $default_page_url = $default_form[0]['page_url'];
            
            if($default_page_url == '' || $default_page_url == NULL) {
                $default_page_url = md5('default_'.$default_sid);
                $this->db->set('page_url', $default_page_url); //update page url at database
                $this->db->where('sid', $default_sid);
                $this->db->update('job_fairs_recruitment');
                $default_form[0]['page_url'] = $default_page_url;
            }
            
            $default_form[0]['form_type'] = 'default';
            $this->db->select('sid, title, page_url, form_type'); // fetch all custom forms
            $this->db->where('company_sid', $company_sid);
            $this->db->where('form_type', 'custom');
            $record_obj = $this->db->get('job_fairs_forms');
            $custom_forms = $record_obj->result_array();
            $record_obj->free_result();

            if(!empty($custom_forms)) {
                foreach($custom_forms as $key => $custom_form) {
                    $custom_sid = $custom_form['sid'];
                    $custom_page_url = $custom_form['page_url'];

                    if($custom_page_url == '' || $custom_page_url == NULL) {
                        $custom_page_url = md5('custom_'.$custom_sid);
                        $this->db->set('page_url', $custom_page_url); //update page url at database
                        $this->db->where('sid', $custom_sid);
                        $this->db->update('job_fairs_forms');
                        $custom_forms[$key]['page_url'] = $custom_page_url;
                    }
                    
                    $default_form[] = $custom_forms[$key];
                }
            }
        }
         
        return $default_form;
    }
    
    function job_fair_configuration($company_sid) {
        $this->db->select('status');
        $this->db->where('company_sid', $company_sid); 
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if(empty($record_arr)) {
            return 0;
        } else {
            return $record_arr[0]['status'];
        }
    }

}
