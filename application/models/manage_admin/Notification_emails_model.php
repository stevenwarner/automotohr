<?php

class Notification_emails_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    
    function get_notification_emails($company_id, $notifications_type) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_id);
        $this->db->where('notifications_type', $notifications_type);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('notifications_emails_management');
    }
    
    function delete_contact($contact_sid){
        $this->db->where('sid', $contact_sid);
        return $this->db->delete('notifications_emails_management');
    }
    
    function check_unique_email($company_id, $email, $notifications_type) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_id);
        $this->db->where('email', $email);
        $this->db->where('notifications_type', $notifications_type);
        $result = $this->db->get('notifications_emails_management')->result_array();
        $result = $this->db->affected_rows();
        if ($result == 1){
            return 'exists';
        } else {
            return 'not_exists';
        }
    }
    
    function get_contact_details($contact_sid) {
        $this->db->where('sid', $contact_sid);
        $data = $this->db->get('notifications_emails_management')->result_array();
        
        if(isset($data[0])){
            return $data[0];
        } else {
            return array();
        }
    }
    
    function update_contact($contact_sid, $update_data) {
        $this->db->where('sid', $contact_sid);
        return $this->db->update('notifications_emails_management', $update_data);
    }
    
    function save_notification_email($data_to_save) {
        $this->db->insert('notifications_emails_management', $data_to_save);
        $result = $this->db->affected_rows();
        if ($result == 1){
            return 'success';
        } else {
            return 'fail';
        }
    }



    function get_notifications_status($company_sid, $notifications_type)
    {
        $result_row = array();
        switch ($notifications_type) {
            case 'billing_invoice':
                $this->db->select('billing_invoice_notifications');
                break;
            case 'new_applicant':
                $this->db->select('new_applicant_notifications');
                break;
            default:
                $this->db->select('*');
                break;
        }

        $this->db->where('company_sid', $company_sid);
        $result_row = $this->db->get('notifications_emails_configuration')->result_array();

        if (!empty($result_row)) {
            return $result_row[0];
        } else {
            return array();
        }
    }

    function get_notifications_configuration_record($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $row_data = $this->db->get('notifications_emails_configuration')->result_array();

        if (!empty($row_data)) {
            return $row_data[0];
        } else {
            return array();
        }
    }

    function insert_notifications_configuration_record($company_sid)
    {
        $data = array();
        $data['company_sid'] = $company_sid;
        $this->db->insert('notifications_emails_configuration', $data);
    }

    function update_notifications_configuration_record($company_sid, $data)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->update('notifications_emails_configuration', $data);
    }
}
