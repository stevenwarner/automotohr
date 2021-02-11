<?php

class Company_addresses_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_all_company_addresses($company_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->from('company_addresses_locations');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function get_company_address_by_id($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('company_addresses_locations');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    public function add_update_address($data,$flag,$address_id = NULL){
        if($flag=='update' && $address_id != NULL){
            $this->db->where('sid',$address_id);
            $this->db->update('company_addresses_locations',$data);
        } else{
            $this->db->insert('company_addresses_locations',$data);
        }
    }

    public function delete_address($sid){
        $this->db->where('sid',$sid);
        $this->db->delete('company_addresses_locations');
    }

}
