<?php
class Complynet_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    //
    function get_all_companies($active = 1) {
        $this->db->select('sid, CompanyName,complynet_status');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', $active);
        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('CompanyName', 'ASC');
        // $this->db->order_by('sid', 'desc');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
//
function mapcompany($data){
    $this->db->insert('complynet_companies', $data);

}


}
