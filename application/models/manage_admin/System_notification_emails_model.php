<?php

class System_notification_emails_model extends CI_Model {

    protected $messages;

    function __construct() {
        parent::__construct();
    }

    function get_system_notification_email($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('system_notification_emails');
        $system_notification_record = $this->db->get()->result_array();

        if(!empty($system_notification_record)){
            return $system_notification_record[0];
        } else {
            return array();
        }
    }

    function insert_system_notification_email_config($system_notification_email_sid, $has_access_to = array()){
        $this->delete_system_notification_email_config($system_notification_email_sid);

        foreach($has_access_to as $module){
            $data_to_insert = array();
            $data_to_insert['system_notification_email_sid'] = $system_notification_email_sid;
            $data_to_insert['has_access_to'] = $module;

            $this->db->insert('system_notification_emails_config', $data_to_insert);
        }
    }

    function delete_system_notification_email_config($system_notification_email_sid){
        $this->db->where('system_notification_email_sid', $system_notification_email_sid);
        $this->db->delete('system_notification_emails_config');
    }

    function get_system_notification_email_config($system_notification_email_sid){
        $this->db->select('*');
        $this->db->where('system_notification_email_sid', $system_notification_email_sid);
        return $this->db->get('system_notification_emails_config')->result_array();
    }

    function get_all_system_notification_emails() {
        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $this->db->from('system_notification_emails');
        return $this->db->get()->result_array();
    }

    function total_system_notification_emails() {
        $this->db->select('*');
        $this->db->from('system_notification_emails');
        return $this->db->get()->num_rows();
    }


    function insert_system_notification_emails_record($data_to_insert){
        $this->db->insert('system_notification_emails', $data_to_insert);
    }

    function update_status($sid, $status){
        $data_to_update = array();
        $data_to_update['status'] = $status;
        $this->db->where('sid', $sid);
        $this->db->update('system_notification_emails', $data_to_update);
    }







    function delete_email_record($id) {
        $this->db->where('sid', $id);
        $this->db->delete('system_notification_emails');
    }





}
