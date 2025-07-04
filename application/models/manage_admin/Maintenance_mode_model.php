<?php

class Maintenance_mode_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_maintenance_mode_record($data_to_insert) {
        $this->db->insert('maintenance_mode_page_content', $data_to_insert);
    }

    function update_maintenance_mode_record($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('maintenance_mode_page_content', $data_to_update);
    }

    function save_maintenance_mode_record($sid, $data_row) {
        if ($sid > 0) {
            $this->update_maintenance_mode_record($sid, $data_row);
        } else {
            $this->insert_maintenance_mode_record($data_row);
        }
    }

    function insert_default_maintenance_mode_record($company_sid, $portal_sid) {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['portal_employer_sid'] = $portal_sid;
        $data_to_insert['page_title'] = 'Maintenance Mode';
        $data_to_insert['page_content'] .= '<p>Our Website is Under Maintenance</p>';
        $this->insert_maintenance_mode_record($data_to_insert);
    }

    function get_maintenance_mode_record($company_sid, $portal_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_employer_sid', $portal_sid);
        $data_row = $this->db->get('maintenance_mode_page_content')->result_array();

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
        }
    }

    function get_employer_portal_record($company_sid) {
        $this->db->select('*');
        $this->db->where('user_sid', $company_sid);
        $data_row = $this->db->get('portal_employer')->result_array();

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return array();
        }
    }

    function update_maintenance_mode_page_content($company_sid, $employer_portal_sid, $data_to_update) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('portal_employer_sid', $employer_portal_sid);
        $this->db->update('maintenance_mode_page_content', $data_to_update);
    }

    function update_maintenance_mode_status($company_sid, $employer_portal_sid, $status) {
        $data_to_update = array();
        $data_to_update['maintenance_mode'] = $status;
        $this->db->where('user_sid', $company_sid);
        $this->db->where('sid', $employer_portal_sid);
        $this->db->update('portal_employer', $data_to_update);
    }

    function get_company_info($company_sid) {
        $this->db->select('*');
        $this->db->where('sid', $company_sid);
        $data_row = $this->db->get('users')->result_array();

        if (!empty($data_row)) {
            return $data_row[0];
        } else {
            return $data_row;
        }
    }

}
