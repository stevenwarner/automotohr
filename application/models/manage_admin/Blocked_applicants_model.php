<?php

class Blocked_applicants_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function insert_record($data) {
        $this->db->insert('blocked_applicants', $data);
    }

    public function delete_blocked_applicant($applicant_email) {
        $this->db->where('applicant_email', $applicant_email);
        $this->db->delete('blocked_applicants');
    }

    public function check_if_already_blocked($applicant_email) {
        $this->db->where('applicant_email', $applicant_email);
        $this->db->from('blocked_applicants');
        $count = $this->db->count_all_results();

        if ($count > 0) {
            return 'exists';
        } else {
            return 'not-exists';
        }
    }

    public function insert_blocked_applicant($applicant_email) {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = 0;
        $data_to_insert['company_name'] = 'Entire Server';
        $data_to_insert['applicant_email'] = $applicant_email;
        $data_to_insert['date_blocked'] = date('Y-m-d H:i:s');
        $this->insert_record($data_to_insert);
    }

    public function get_companies() {
        $this->db->select('sid');
        $this->db->select('CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->order_by('sid', 'DESC');
        $this->db->where('career_page_type', 'standard_career_site');

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_all_records() {
        $this->db->select('*');
        $this->db->order_by('date_blocked', 'DESC');

        $records_obj = $this->db->get('blocked_applicants');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function delete_record($email_address) {
        $this->db->where('applicant_email', $email_address);
        $this->db->delete('blocked_applicants');
    }

    public function delete_records_by_sids($sids) {
        $this->db->where_in('sid', $sids);
        $this->db->delete('blocked_applicants');
    }

}
