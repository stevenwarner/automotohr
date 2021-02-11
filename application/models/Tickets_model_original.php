<?php
class tickets_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_all_tickets($company_sid, $limit = null, $start = null){
        $this->db->where("company_sid", $company_sid);
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        $this->db->order_by("sid", "desc");
        return $this->db->get("tickets")->result_array();
    }
    
    function get_all_tickets_count($company_sid){
        $this->db->where("company_sid", $company_sid);
        return $this->db->get("tickets")->num_rows();
    }
    
    function delete_ticket($ticket_id){
        $this->db->where('sid', $ticket_id);
        $this->db->delete('tickets');
    }
    
    function change_status($ticket_id, $status){
        $this->db->where('sid', $ticket_id);
        $this->db->update('tickets', array('status' => $status));
    }
    
    function add_ticket($insert_array, $insert_array2, $file_insert_array=array()){
        $this->db->insert('tickets', $insert_array);
        $ticket_sid = $this->db->insert_id();
        
        $insert_array2['ticket_sid'] = $ticket_sid;
        $ticket_messages_sid = $this->db->insert('ticket_messages', $insert_array2);
        
        if(!empty($file_insert_array)){
            $file_insert_array['ticket_sid']                                    = $ticket_sid;
            $file_insert_array['ticket_message_sid']                            = $ticket_messages_sid;
            $this->db->insert('ticket_files', $file_insert_array);
        }
    }
    
    function get_messages_by_id($ticket_sid, $order = 'ASC'){
        $this->db->where("ticket_sid", $ticket_sid);
        $this->db->order_by('sid', $order);
        return $this->db->get("ticket_messages");
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
    
    function add_ticket_message($insert_array){
        $this->db->where('sid', $insert_array['ticket_sid']);
        $this->db->update('tickets', array('status' => 'Awaiting Response'));
        $this->db->insert('ticket_messages', $insert_array);
        
        return $this->db->insert_id();
    }
    
    function update_ticket_status($ticket_sid, $update_array){
        $this->db->where('sid', $ticket_sid);
        return $this->db->update('tickets', $update_array);
    }
    
    function get_unread_tickets_count($company_sid){
        $this->db->where("company_sid", $company_sid);
        $this->db->where("employee_read", 0);
        return $this->db->get("tickets")->num_rows();
    }
    
    function save_ticket_file($file_insert_array){
        return $this->db->insert('ticket_files', $file_insert_array);
    }
    
    function get_ticket_files($ticket_sid) {
        $this->db->where('ticket_sid', $ticket_sid);
        return $this->db->get("ticket_files")->result_array();
    }
    
    function get_ticket_participants($ticket_sid, $employer_sid) {
        $this->db->select('distinct(employer_sid)');
        $this->db->where('ticket_sid', $ticket_sid);
        $this->db->where('employee_type', 'employee');
        $this->db->where('employer_sid <>', $employer_sid);
        
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
?>