<?php
class Application_status_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function check_company_status($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $records = $this->db->get('application_status')->result_array();
        
        if(sizeof($records) <= 0) {
            // empty; do insertion here 
            $this->db->where('company_sid', 0);
            $this->db->order_by("status_order", "ASC");
            $default_records = $this->db->get('application_status')->result_array();
            
            foreach ($default_records as $record){
                $insert_array = array();
                $insert_array['name'] = $record['name'];
                $insert_array['css_class'] = $record['css_class'];
                $insert_array['text_css_class'] = $record['text_css_class'];
                $insert_array['status_order'] = $record['status_order'];
                $insert_array['company_sid'] = $company_sid;
                $this->db->insert("application_status", $insert_array);
            }
            return true;
        } else {
            return false;
        }
    }
    
    function get_status_by_company($company_sid) {
        $this->db->select('sid, name, css_class, status_order, status_type, bar_bgcolor');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('application_status')->result_array();
    }
    
    function update_company_status($statuses, $company_sid) {
        foreach($statuses as $key => $status){
            $this->db->where('company_sid', $company_sid);
            $is_order = explode('order_', $key);
            $is_color = explode('color_', $key);

            if(sizeof($is_order) > 1){
                $key = $is_order[1];
                $column = 'status_order';
            } elseif(sizeof($is_color) > 1){
                $key = $is_color[1];
                $column = 'bar_bgcolor';
            } else {
                $column = 'name';
            }
            
            $this->db->where('css_class', $key);
            $this->db->update('application_status', array($column => $status));
        }
    }

    function check_company_status_right($company_sid) {
        $this->db->select('enable_applicant_status_bar');
        $this->db->where('sid',$company_sid);
        $status = $this->db->get('users')->result_array();
        return $status[0]['enable_applicant_status_bar'];
    }
    
    function check_status_for_additional_status_bar($sid) {
        $this->db->select('enable_applicant_status_bar');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        
        if($record_arr[0]['enable_applicant_status_bar'] == 0) {
            $status = 'disabled';
        } else {
            $status = 'enabled';
        }
        
        return $status;
    }

    function insert_applicant_status($insert_array){
        $this->db->insert("application_status", $insert_array);
        return $this->db->insert_id();
    }

    function delete_status($id){
        $this->db->where('sid',$id);
        $this->db->delete('application_status');
    }
}