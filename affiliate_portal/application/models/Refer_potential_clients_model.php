<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Refer_potential_clients_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function insert_refer_form($form) {
        $this->db->insert('free_demo_requests', $form);
        return $this->db->insert_id();
    }
    public function check_refer_affiliater($email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $result = $this->db->get('free_demo_requests')->result_array();
        return $result;
    }
    public function check_reffer_potential_before_update_record($sid, $email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $this->db->where('sid !=', $sid);
        $result = $this->db->get('free_demo_requests')->result_array();
        return $result;
    }
    public function get_all_countries() {
        $this->db->select('country_name');
        $this->db->order_by('order', 'ASC');
        $result = $this->db->get('countries')->result_array();
        return $result;
    }
    public function get_reffer_potential_user($refer_users_sid) {
        $this->db->select('*');
        $this->db->where('sid', $refer_users_sid);
        $record_obj = $this->db->get('free_demo_requests');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    public function update_reffer_potential_user_record($sid, $data_array) {
        $this->db->where('sid', $sid);
        $this->db->update('free_demo_requests', $data_array);
    }
   
}