<?php

class Courses_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // FUNCTIONS related to bulk email
    
    function get_all_companies() {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    public function GetAllActiveTemplates() {
        $this->db->select('sid, title');
        $this->db->where('archive_status', 'active');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('portal_job_listing_templates')->result_array();
    }

    /**
     * Add company data to table
     * 
     * @param string $table
     * @param array $insertArray
     * 
     * @return int
     */
    public function addData(
        string $table, 
        array $insertArray
    ){
        //
        $this->db->insert($table, $insertArray);
        //
        return $this->db->insert_id();
    }
}
