<?php

class Dashboard_old extends CI_Model {

    function __construct() {
        parent::__construct();
    }

  function get_cc_auth() {
        $this->db->select('*');
        $this->db->where('processed', 1);
        $this->db->where('status', 'signed');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('form_document_credit_card_authorization')->result_array();
    }
    
    function get_company_name($id) {
        $this->db->select('CompanyName');
        $this->db->where('sid', $id);
        return $this->db->get('users')->result_array();
    }
    
    function get_all_active_company_name() {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        //$this->db->where('is_paid', 1);
        //$this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'desc');
        return $this->db->get('users')->result_array();
    }

    function get_details($id) {
        $this->db->select('*');
        $this->db->where('verification_key', $id);
        return $this->db->get('form_document_credit_card_authorization')->result_array();
    }
}