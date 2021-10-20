<?php
//
class Sitemap_model extends CI_Model {
    //
    function __construct() {
        parent::__construct();
    }


    //
    function GetCompaniesWithJobs() {
        $this->db->select('
            portal_job_listings.sid, 
            portal_job_listings.user_sid, 
            portal_job_listings.Title, 
            portal_job_listings.approval_status, 
            users.CompanyName, 
            users.has_job_approval_rights,
            portal_employer.sub_domain
        ');
        $this->db->from('portal_job_listings');
        $this->db->join('users', 'users.sid = portal_job_listings.user_sid', 'left');
        $this->db->join('portal_employer', 'portal_employer.user_sid = portal_job_listings.user_sid', 'left');
        $this->db->where('portal_job_listings.active', 1);
        $this->db->where('portal_job_listings.organic_feed', 1);
        $this->db->where('users.active', 1);
        $this->db->where('users.is_paid', 1);
        $this->db->where('users.career_site_listings_only', 0);
        $this->db->order_by('activation_date', 'DESC');
        $this->db->group_by('portal_job_listings.sid');
        //
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_statename_by_id($sid) {
        $this->db->select('state_name, state_code');
        $this->db->where('sid', $sid);
        $this->db->from('states');
        return $this->db->get()->result_array();
    }
}
