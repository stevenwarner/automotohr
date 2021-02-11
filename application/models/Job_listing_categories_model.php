<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Job_listing_categories_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function Insert($sid, $company_sid, $employer_sid, $field_sid, $value, $sort_order){
        $data = array();
        $data['company_sid'] = $company_sid;
        $data['employer_sid'] = $employer_sid;
        $data['field_sid'] = $field_sid;
        $data['value'] = $value;
        $data['order'] = $sort_order;
        $data['field_type'] = 'custom';

        $this->db->insert('listing_field_list', $data);
    }

    function Update($sid, $company_sid, $employer_sid, $field_sid, $value, $sort_order){
        $data = array();
        $data['company_sid'] = $company_sid;
        $data['employer_sid'] = $employer_sid;
        $data['field_sid'] = $field_sid;
        $data['value'] = $value;
        $data['order'] = $sort_order;
        $data['field_type'] = 'custom';

        $this->db->where('sid', $sid);
        $this->db->update('listing_field_list', $data);
    }

    function Save($sid, $company_sid, $employer_sid, $field_sid, $value, $sort_order){
        if($sid == null){
            $this->Insert($sid, $company_sid, $employer_sid, $field_sid, $value, $sort_order);
        }else{
            $this->Update($sid, $company_sid, $employer_sid, $field_sid, $value, $sort_order);
        }
    }

    function GetAll($company_sid, $limit = null, $start = null){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('field_type', 'custom');
        
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        
        $this->db->order_by("sid", "desc");
        //$this->db->order_by('value');

        return $this->db->get('listing_field_list')->result_array();
    }

    function GetAllCount($company_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('field_type', 'custom');
        
        return $this->db->get('listing_field_list')->num_rows();
    }
    
    function GetSingle($sid){
        $this->db->where('sid', $sid);
        return $this->db->get('listing_field_list')->result_array();
    }

    function DeleteSingle($sid){
        $this->db->where('sid', $sid);
        $this->db->delete('listing_field_list');
    }

}