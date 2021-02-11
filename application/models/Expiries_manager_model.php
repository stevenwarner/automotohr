<?php

class Expiries_manager_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function GetAllUsers($company_sid) {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->order_by("sid", "desc");
        return $this->db->get('users')->result_array();
    }

    function GetAllLicenses($users_sid) {
        $this->db->where('users_sid', $users_sid);
        $this->db->order_by("sid", "desc");
        return $this->db->get('license_information')->result_array();
    }

    function GetUserDetails($users_sid) {
        $this->db->where('sid', $users_sid);
        return $this->db->get('users')->result_array();
    }

    function GetAllLicensesCron() {
        $this->db->order_by("sid", "desc");
        return $this->db->get('license_information')->result_array();
    }

    function GetCompanyExpiration($cid){
        $this->db->select('expiration_manager');
        $this->db->where('company_sid',$cid);
        return $this->db->get('notifications_emails_configuration')->result_array();
    }

    function GetExpirationsManagerEmails($cid){
        $this->db->select('status, email, contact_name, contact_no, employer_sid, short_description');
        $this->db->where('company_sid',$cid);
        $this->db->where('notifications_type','expiration_manager');

        return $this->db->get('notifications_emails_management')->result_array();

    }

}
