<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Settings_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_settings() {
        $this->db->select('*');
        $this->db->from('settings');
        return $this->db->get()->result_array();
    }

    public function save_setting($sid, $data) {
        $this->db->where('sid', $sid);
        $result = $this->db->update('settings', $data);
        (!$result) ? $this->session->set_flashdata('message', 'Update Failed, Please try Again!') : $this->session->set_flashdata('message', 'General settings updated successfully');
    }

    function get_admin_status() {
        $this->db->select('sid, name, css_class, active, status_order, status_type, bar_bgcolor');
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('admin_status_bars')->result_array();
    }

    function update_admin_status_bar($statuses) {
        foreach ($statuses as $key => $status) {
            $is_name = explode('name_', $key);
            $is_order = explode('order_', $key);
            $is_status = explode('status_', $key);

            if (sizeof($is_name) > 1) {
                $key = $is_name[1];
                $column = 'name';
            } elseif (sizeof($is_order) > 1) {
                $key = $is_order[1];
                $column = 'status_order';
            } else {
                $key = $is_status[1];
                $column = 'active';
            }

            $this->db->where('css_class', $key);
            $this->db->update('admin_status_bars', array($column => $status));
        }
    }
    
    function get_page_configurations($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('demo_affiliate_configurations')->result_array();
    }

    function update_demo_affiliate_videos($video_sid, $data) {
        
        $this->db->where('sid', $video_sid);
        $this->db->update('demo_affiliate_configurations', $data);
    }

    function get_affiliate_demo_page_configurations(){
        $this->db->select('sid, video_source, page_name, status');
        $records_obj = $this->db->get('demo_affiliate_configurations');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }
}