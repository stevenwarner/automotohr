<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class View_my_referral_list_model extends CI_Model {
    
    function __construct() {
        parent::__construct();
    }
    
    public function get_all_referel($referel_id) {
        $this->db->select('sid, first_name, last_name, email, phone_number');
        $this->db->where('refferred_by_sid', $referel_id);
        $records_obj = $this->db->get('free_demo_requests');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
   
}