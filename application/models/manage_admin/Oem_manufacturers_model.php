<?php

class Oem_manufacturers_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    // FUNCTIONS related to oem manufacturers
    
    // adding a new oem manufacturer
    function add_oem_manufacturer($oem_data) {
        $result = $this->db->insert('oem_brands', $oem_data);
        return $result;
    }

    // getting all active oem manufacturers from db
    function get_all_oem_manufacturers()
    {
        $this->db->select('*');
        $this->db->where('brand_status', 'active');
        $this->db->order_by('LOWER(oem_brand_name)', 'asc');
        $brands = $this->db->get('oem_brands')->result_array();
        
        return $brands;
    }
    
    // delete an oem manufacturer by sid
    function oem_manufacturer_delete($oem_sid)
    {
        $this->db->delete('oem_brands_companies', array('oem_brand_sid' => $oem_sid));
        
        //$this->db->where('sid', $oem_sid);
        //$result = $this->db->update('oem_brands', array('brand_status' => 'deleted'));
           
        $result = $this->db->delete('oem_brands', array('sid' => $oem_sid));
        return $result;
    }
    
    // get an oem manufacturer by id
    function get_oem_manufacturer($oem_sid)
    {
        $this->db->select('*');
        $this->db->where('brand_status', 'active');
        $this->db->where('sid', $oem_sid);
        $brand = $this->db->get('oem_brands')->result_array();
        
        return $brand;
    }
    
    // get an oem manufacturer by name
    function get_oem_manufacturer_by_name($name)
    {
        $this->db->select('*');
        $this->db->where('brand_status', 'active');
        $this->db->where('oem_brand_name', $name);
        $brand = $this->db->get('oem_brands')->result_array();
        
        return $brand;
    }
    
    // get all companies assigned to one brand manufacturer
    function get_brand_companies($oem_sid)
    {
        $this->db->select('*');
        $this->db->where('oem_brand_sid', $oem_sid);
        $brand = $this->db->get('oem_brands_companies')->result_array();
        
        return $brand;
    }
    
    // update an OEM manufacturer
    function update_oem_manufacturer($update_data, $oem_sid)
    {
        $this->db->where('sid', $oem_sid);
        $result = $this->db->update('oem_brands', $update_data);
        
        return $result;
    }
    
    // add a company against a manufacturer
    function add_brand_company($data, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('oem_brand_sid', $data['oem_brand_sid']);
        $this->db->where('company_sid', $company_sid);
        $company_info = $this->db->get('oem_brands_companies')->result_array();
       
        if (!empty($company_info)) {
            $sid = $company_info[0]['sid'];
            $this->db->where('sid', $sid);
            $this->db->update('oem_brands_companies', $data);
        } else {
            $this->db->insert('oem_brands_companies', $data);
        }
    }
    
    // remove a company that was assigned to an oem manufacturer
    function remove_oem_company($sid) {
        $result = $this->db->delete('oem_brands_companies', array('sid' => $sid));
        return $result;
    }
}
