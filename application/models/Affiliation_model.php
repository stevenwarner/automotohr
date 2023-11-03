<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Affiliation_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function insert_affiliation_form($form) {
        $this->db->insert('affiliations', $form);
        return $this->db->insert_id();
    }

    public function check_register_affiliater($email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }

    public function get_all_countries() {
        $this->db->select('country_name');
        $this->db->where('country_code','CA');
        $this->db->or_where('country_code','US');
        $this->db->order_by('order', 'ASC');
        $result = $this->db->get('countries')->result_array();
        return $result;
    }

    public function validate_affiliate_video_status($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $result = $this->db->get('demo_affiliate_configurations')->result_array();
        return $result;
    }
   
}