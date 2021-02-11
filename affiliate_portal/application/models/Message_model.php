<?php

if (!defined('BASEPATH')) 
    exit('No direct script access allowed');

class message_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_affiliates($sid) {
        $this->db->select('sid, full_name, email');
        $this->db->where('parent_sid', $sid);

        $record_obj = $this->db->get('marketing_agencies');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }

    public function get_all_outbox_messages($between, $affiliate_sid, $outbox, $type) {
        $this->db->select('*, from_id as username, sid as msg_id');
        $this->db->where('outbox', $outbox);

        if ($type == 'inbox') {
            $this->db->where('to_id', $affiliate_sid);
        } else if ($type == 'outbox') {
            $this->db->where('from_id', $affiliate_sid);
        }

        if ($between != '') {
            $this->db->where($between);
        }
        
        $this->db->order_by('message_date', 'DESC');
        $record_obj = $this->db->get('affiliate_private_messages');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }


    function get_name_by_id($user_id) {
        $name = 'Record Not Found';
        $email = 'Email Not Found';

        $this->db->select('full_name, email');
        $this->db->where('sid', $user_id);
        $records_obj = $this->db->get('marketing_agencies');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr)) {
            $name = $records_arr[0]['full_name'];
            $email = $records_arr[0]['email'];
        }

        return array('name'=>$name,'email'=>$email);
    }

    function fetch_name($email) {
        $this->db->select('full_name');
        $this->db->where('email', $email);

        $records_obj = $this->db->get('marketing_agencies');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $to_name = $email;

        $to_name = $records_arr[0]['full_name'];

        return $to_name;
    }

    public function get_all_inbox_messages($between, $affiliate_sid, $email) {
        $this->db->select('*, from_id as username, sid as msg_id');
        $this->db->where('to_id', $affiliate_sid);
        $this->db->or_where('to_id', $email);
        $this->db->where('outbox', 0);

        if ($between != '') {
            $this->db->where($between);
        }
        
        $this->db->order_by('message_date', 'DESC');
        $record_obj = $this->db->get('affiliate_private_messages');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data;
        } else {
            return array();
        }
    }

    public function get_outbox_message($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        
        $record_obj = $this->db->get('affiliate_private_messages');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    public function get_inbox_message($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
         $this->db->where('outbox', 0);
        
        $record_obj = $this->db->get('affiliate_private_messages');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    public function user_data_by_id($sid) {
        $this->db->select('username, email, full_name');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $record_obj = $this->db->get('marketing_agencies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_array = array();

        if (!empty($record_arr)) {
            $return_array = $record_arr[0];
        }

        return $return_array;
    }

    public function get_email_for_record($email, $parent_sid = NULL, $user_sid = NULL) {
        echo $email;
        $this->db->select('sid, full_name, parent_sid');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $record_obj = $this->db->get('marketing_agencies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        $return_array = array();

        if (!empty($record_arr)) { //do not confuse because its confusing
            if ($parent_sid == 0) {
                if ($record_arr[0]['parent_sid'] == $user_sid) {
                    $return_array = $record_arr[0];
                }
            } else {
                if ($record_arr[0]['parent_sid'] == $parent_sid) {
                    $return_array = $record_arr[0];
                } else if ($record_arr[0]['sid'] == $parent_sid) {
                    $return_array = $record_arr[0];
                }
            }
        }

        return $return_array;
    }

    public function save_message($message_data) {
        $message_data['outbox'] = 0;
        $this->db->insert('affiliate_private_messages', $message_data);
        $outbox = $this->db->insert_id();
        $message_data['outbox'] = 1;
        $this->db->insert('affiliate_private_messages', $message_data);
        $inbox = $this->db->insert_id();
        return array($inbox,$outbox);
    }

    public function get_admin_email($id) {
        $this->db->select('email');
        $this->db->where('id', $id);
        $this->db->where('active', 1);

        $record_obj = $this->db->get('administrator_users');
        $result = $record_obj->result_array();
        $record_obj->free_result();
        $email = 'record not found!';
        
        if(!empty($result)) {
            $email = $result[0]['email'];
        }
        
        return $email;
    }

    //Delete Message
    public function delete_message($message_id) {
        $this->db->where('sid', $message_id);
        $this->db->delete('affiliate_private_messages');
    }

    public function get_employer_messages_attachments($emp_id) {
        $this->db->select('attachment');
        $this->db->where('private_message_sid',$emp_id);
        $result = $this->db->get('private_message_attachments')->result_array();
        return $result;
    }

    function insert_private_messages_files($data){
        $this->db->insert('private_message_attachments',$data);
    }
}
