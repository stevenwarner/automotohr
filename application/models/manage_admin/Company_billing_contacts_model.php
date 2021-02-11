<?php

class Company_billing_contacts_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_billing_contact_record($company_sid, $admin_sid, $field_data = array()){
        $dataToInsert = array();
        $dataToInsert['created_date'] = date('Y-m-d h:i:s');
        $dataToInsert['created_by'] = $admin_sid;
        $dataToInsert['status'] = 'active';
        $dataToInsert['company_sid'] = $company_sid;

        if(!empty($field_data)){
            $dataToInsert = array_merge($dataToInsert, $field_data);
        }

        $this->db->insert('company_billing_contacts', $dataToInsert);
    }

    function update_billing_contact_record($company_billing_contact_sid, $field_data = array()){
        $this->db->where('sid', $company_billing_contact_sid);

        $this->db->update('company_billing_contacts', $field_data);
    }

    function save_billing_contact_record($company_billing_contact_sid, $company_sid, $admin_sid, $field_data = array()){
        if($company_billing_contact_sid > 0){
            $this->update_billing_contact_record($company_billing_contact_sid, $field_data);
        }else{
            $this->insert_billing_contact_record($company_sid, $admin_sid, $field_data);
        }
    }

    function get_company_name($company_sid){
        $this->db->select('CompanyName');
        $this->db->where('sid', $company_sid);
        $company_details = $this->db->get('users')->result_array();

        if(!empty($company_details)){
            return $company_details[0]['CompanyName'];
        }else{
            return '';
        }

    }

    function get_all_billing_contacts($company_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'active');
        return $this->db->get('company_billing_contacts')->result_array();
    }

    function get_single_billing_contact_record($company_sid, $company_billing_contact_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $company_billing_contact_sid);

        $company_billing_contact_record = $this->db->get('company_billing_contacts')->result_array();

        if (!empty($company_billing_contact_record)) {
            return $company_billing_contact_record[0];
        } else {
            return array();
        }
    }

    function set_billing_contact_record_status($company_sid, $company_billing_contact_sid, $status = 'active'){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $company_billing_contact_sid);

        $dataToUpdate = array();
        $dataToUpdate['status'] = $status;
        $this->db->update('company_billing_contacts', $dataToUpdate);
    }
}
