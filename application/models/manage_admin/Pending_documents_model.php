<?php
class Pending_documents_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function getAllCompanies($active = 1) {
        $result = $this->db
        ->select('sid, CompanyName')
        ->where('parent_sid', 0)
        ->where('active', $active)
        ->where('is_paid', 1)
        ->where('career_page_type', 'standard_career_site')
        ->order_by('CompanyName', 'ASC')
        ->get('users');
        //
        $companies = $result->result_array();
        $result = $result->free_result();
        //
        return $companies;
    }
    
    
    function GetCompanyDetail($id, $columns= '*') {
        $result = $this->db
        ->select('sid, CompanyName')
        ->where('sid', $id)
        ->get('users');
        //
        $companies = $result->row_array();
        $result = $result->free_result();
        //
        return $companies;
    }


}
