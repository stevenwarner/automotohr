<?php

class Tickets_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_all_tickets($limit = null, $start = null)
    {
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('tickets')->result_array();
    }
    
    function get_all_tickets_count(){
        return $this->db->get('tickets')->num_rows();
    }
    
    function get_ticket_by_id($ticket_sid){
        $this->db->where("sid", $ticket_sid);
        $ticket = $this->db->get("tickets")->result_array();
        
        if(!empty($ticket)){
            return $ticket[0];
        } else {
            return array();
        }
    }
    
    function get_messages_by_id($ticket_sid, $order = 'ASC'){
        $this->db->where("ticket_sid", $ticket_sid);
        $this->db->order_by('sid', $order);
        return $this->db->get("ticket_messages");
    }
    
    function add_ticket_message($insert_array, $ticket_status){
        $this->db->where('sid', $insert_array['ticket_sid']);
        $this->db->update('tickets', array('status' => $ticket_status));
        $this->db->insert('ticket_messages', $insert_array);
        
        return $this->db->insert_id();
    }
    
    function update_ticket_status($ticket_sid, $update_array){
        $this->db->where('sid', $ticket_sid);
        return $this->db->update('tickets', $update_array);
    }
    
    function save_ticket_file($file_insert_array){
        return $this->db->insert('ticket_files', $file_insert_array);
    }
    
    function get_ticket_files($ticket_sid) {
        $this->db->where('ticket_sid', $ticket_sid);
        return $this->db->get("ticket_files")->result_array();
    }
    
    function get_ticket_participants($ticket_sid) {
        $this->db->select('distinct(employer_sid)');
        $this->db->where('ticket_sid', $ticket_sid);
        $this->db->where('employee_type', 'employee');
        
        return $this->db->get("ticket_messages");
    }
    
    function get_employee_data($employer_sid){
        $this->db->select('email, first_name, last_name');
        $this->db->where('sid', $employer_sid);
        $employee_data = $this->db->get("users")->result_array();
        
        if(!empty($employee_data)){
            return $employee_data[0];
        } else {
            return array();
        }
    }
}
