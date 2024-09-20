<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class message_model extends CI_Model {

//ADMIN inbox messages 
    public function get_admin_messages($admin_id) {
        $this->db->select('status,username,subject,date,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.from_id');
        $this->db->where("to_id = '{$admin_id}'");
        $this->db->where('to_type', 'admin');
        $this->db->where('outbox', 0);
        $this->db->order_by("date", "desc");
        return $this->db->get('private_message');
    }

    public function get_admin_messages_total($admin_id) {
        $this->db->select('username,subject,date,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.from_id');
        $this->db->where("to_id = '{$admin_id}'");
        $this->db->where('to_type', 'admin');
        $this->db->where('status', 0);
        $this->db->where('outbox', 0);
        return $this->db->count_all_results("private_message");
        // return $this->db->get('private_message')->num_rows();
    }

    public function get_messages_total_inbox($admin_id) {
        $this->db->select('username,subject,date,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.from_id');
        $this->db->where("to_id = '{$admin_id}'");
        $this->db->where('to_type', 'admin');
        $this->db->where('outbox', 0);
        return $this->db->count_all_results("private_message");
        // return $this->db->get('private_message')->num_rows();
    }

    public function get_messages_total_outbox($admin_id) {
        $this->db->select('username,subject,date,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.to_id');
        $this->db->where('from_id', $admin_id);
        $this->db->where('outbox', 1);
        $this->db->where('from_type', 'admin');
        return $this->db->count_all_results("private_message");
        // return $this->db->get('private_message')->num_rows();
    }

    public function get_inbox_message_detail($message_id) {
        $this->db->select('from_type,from_id,username,subject,date,message,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.from_id');
        $this->db->where('id', $message_id);
        return $this->db->get('private_message')->result_array();
    }

//ADMIN Outbox messages 
    public function get_outbox_message_detail($message_id, $anonym) {
        if($anonym){
            $this->db->select('from_type,from_id,to_id as username,subject,date,message,private_message.id as msg_id');
        }else{
            $this->db->select('from_type,from_id,username,subject,date,message,private_message.id as msg_id');
            $this->db->join('users', 'users.sid = private_message.to_id');
        }
        $this->db->where('id', $message_id);

        return $this->db->get('private_message')->result_array();
    }

    public function get_admin_outbox_messages($admin_id) {
        $this->db->select('username,subject,date,anonym,private_message.id as msg_id');
        $this->db->join('users', 'users.sid = private_message.to_id');
        $this->db->where('from_id', $admin_id);
        $this->db->where('outbox', 1);
        $this->db->where('from_type', 'admin');
         $this->db->order_by("date", "desc");
        return $this->db->get('private_message');
    }

//Delete Message
    public function delete_message($message_id) {
        $this->db->where('id', $message_id);
        $this->db->delete('private_message');
    }

//Compose Message 
    public function save_message($product_data) {

        $product_data['outbox'] = 0;
        $this->db->insert('private_message', $product_data);

        $product_data['outbox'] = 1;
        $this->db->insert('private_message', $product_data);

        return "Product Saved Successfully";
    }

    public function mark_read($message_id) {
        $data = array('status' => 1);
        $this->db->where('id', $message_id);
        $this->db->update('private_message', $data);
    }


    /**
     * Fetch Admin templates from db
     * Created on: 29-04-2019
     *
     * @return Array|Bool
     */
    function fetch_admin_templates(){
        $result = $this->db
        ->select('
            sid AS id, name as templateName, subject, text as body
        ')
        ->from('email_templates')
        ->where('status', 1)
        ->where('group', 'super_admin_templates')
        ->order_by('name', 'ASC')
        ->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }

    function fetch_manual_email_outbox(){
        $this->db->select('to_id as username,subject,anonym,date,private_message.id as msg_id');
        $this->db->where('from_id', 1);
        $this->db->where('anonym', 1);
        $this->db->where('outbox', 1);
        $this->db->where('from_type', 'admin');
        $this->db->order_by("date", "desc");
        return $this->db->get('private_message')->result_array();
    }

}
