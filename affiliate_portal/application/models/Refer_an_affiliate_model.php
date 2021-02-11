<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Refer_an_affiliate_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    public function insert_refer_form($form) {
        $this->db->insert('affiliations', $form);
        return $this->db->insert_id();
    }
    public function check_reffer_affiliater($email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }
    public function check_reffer_affiliater_before_update_record($sid, $email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $this->db->where('sid !=', $sid);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }
    public function get_all_countries() {
        $this->db->select('country_name');
        $this->db->order_by('order', 'ASC');
        $result = $this->db->get('countries')->result_array();
        return $result;
    }
    public function get_all_referel_affiliate($referel_id) {
        $this->db->select('sid, first_name, last_name, email, contact_number');
        $this->db->where('refferred_by_sid', $referel_id);
        $records_obj = $this->db->get('affiliations');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }
    public function get_reffer_affiliate_user($affiliate_users_sid) {
        $this->db->select('*');
        $this->db->where('sid', $affiliate_users_sid);
        $record_obj = $this->db->get('affiliations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }
    public function update_affiliate_user_record($sid, $data_array) {
        $this->db->where('sid', $sid);
        $this->db->update('affiliations', $data_array);
    }
   
}