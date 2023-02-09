<?php
class Users_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_online_users($minutes = 10, $company_sid = 0){
        // $current_date_obj = new DateTime(date('Y-m-d H:i:s'));
        // $current_date_obj->sub(date_interval_create_from_date_string( $minutes . ' min'));
        // $current_date_str = $current_date_obj->format('Y-m-d H:i:s');
        $current_date_str = date('Y-m-d H:i:s', strtotime("-$minutes minutes"));
        $rows_to_return = array();
        $this->db->select('*');
        $this->db->where('action_timestamp >',  $current_date_str);
        $this->db->order_by('sid', 'DESC');
        
        $return_obj = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $current_date_str));
        $data_row = $return_obj->result_array();
        $return_obj->free_result();
//        $data_row = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array(); //Table name is correct
        
        if(!empty($data_row)){
            foreach($data_row as $value) {
                $logged_in_id = $value['employer_sid'];
                $rows_to_return[$logged_in_id] = $value;
            } 
        }
      
        return $rows_to_return;
    }

    function get_total_users_count() {
        $this->db->from('users');
        $this->db->where('parent_sid > ', 0);
        return $this->db->get()->num_rows();
    }
    
    function get_total_users() {
        $this->db->select('sid, active');
        $this->db->from('users');
        $this->db->where('parent_sid > ', 0);
        return $this->db->get()->result_array();
    }
    
    function total_active_users() {
        $this->db->select('sid');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('active', 1);
        return $this->db->count_all_results('users');
    }
    
    function total_inactive_uers() {
        $this->db->select('sid');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('active', 0);
        return $this->db->count_all_results('users');
    }
    
    function total_users() {
        $this->db->select('sid');
        $this->db->where('parent_sid > ', 0);
        return $this->db->count_all_results('users');
    }

    function check_key($key){
        $this->db->select('*');
        $this->db->from('administrator_users');
        $this->db->where('salt', $key);
        $this->db->limit(1);
        $query_result = $this->db->get()->result_array();
        if(sizeof($query_result)>0){
            return 1;
        }
        else{
            return 0;
        }
    }

    function updatePass($password,$key){
        $data = array('password' => $password);
        // $data = array('password' => MD5($password),'salt' => NULL);
        $this->db->where('salt', $key);
        $this->db->update('administrator_users', $data);
    }

    function get_employee_details($sid) {
        $this->db->select('first_name, last_name, username, company, salt, email');
        $this->db->where('id', $sid);
        $this->db->from('administrator_users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_user($sid, $data, $type = NULL) {
        $this->db->where('id', $sid);
        $result = $this->db->update('administrator_users', $data);

        (!$result) ? $this->session->set_flashdata('message', 'Update Failed, Please try Again!') : $this->session->set_flashdata('message', $type . ' updated successfully');
    }

    function get_online_users_count($minutes){
        $current_date_obj = new DateTime(date('Y-m-d H:i:s'));
        $current_date_obj->sub(date_interval_create_from_date_string( $minutes . ' min'));
        $current_date_str = $current_date_obj->format('Y-m-d H:i:s');
        //
        $this->db->where('action_timestamp >',  $current_date_str);
        $this->db->group_by('employer_sid');
        $this->db->from(checkAndGetArchiveTable('logged_in_activitiy_tracker', $current_date_str));
        //
        $count = $this->db->count_all_results();
        //
        return $count;
    }

}
