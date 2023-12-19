<?php

class Terminate_employee_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function terminate_user($sid, $data){
        //Insert Terminated Data
        $this->db->insert('terminated_employees', $data);
        $record_sid = $this->db->insert_id();

        //Enable Files if uploaded
        $data_to_update = array();
        $data_to_update['status'] = 1;
        $data_to_update['terminated_record_sid'] = $record_sid;
        $this->db->where('status', 0);
        $this->db->where('terminated_user_id', $sid);
        $this->db->where('terminated_record_sid', 0);
        $this->db->update('terminated_employees_documents',$data_to_update);
        //
        return $record_sid; 
    }

    public function update_terminate_user($sid, $data){
        $this->db->where('sid',$sid);
        $this->db->update('terminated_employees', $data);
    }

    public function change_terminate_user_status ($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('users',$data_to_update);
    }

    public function change_employee_terminated_status ($sid){
        $this->db->where('sid',$sid);
        $term = array('terminated_status' => 1);
        $this->db->update('users',$term);
    }

    public function get_terminated_employees_documents($sid, $record_sid){
        $this->db->select('file_name, file_code, file_type');
        $this->db->where('terminated_user_id',$sid);
        $this->db->where('terminated_record_sid',$record_sid);
        $this->db->where('status', 1);
        $result = $this->db->get('terminated_employees_documents')->result_array();
        return $result;
    }

    public function get_termination_record($sid){
        $this->db->where('employee_sid',$sid);
        $result = $this->db->get('terminated_employees')->result_array();
        if(sizeof($result) > 0){
            $result = $result[0];
        }
        return $result;
    }

    public function get_employee_status_detail($sid){
        $this->db->select('*');
        $this->db->where('employee_sid', $sid);
        $this->db->order_by('sid', 'DESC');
        $records_obj = $this->db->get('terminated_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function insert_termination_docs($doc){
        $this->db->insert('terminated_employees_documents', $doc);
    }

    public function update_file($sid, $data){
        $this->db->where('sid',$sid);
        $this->db->update('terminated_employees_documents',$data);
    }

    public function delete_file($sid){
        $this->db->where('sid',$sid);
        $this->db->delete('terminated_employees_documents');
    }

    public function deleteEmployeeStatus ($sid) {
        $this->db->where('sid',$sid);
        $this->db->delete('terminated_employees');
    }

    function employee_exists($user_id, $company_sid) {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_id);
        return $this->db->count_all_results('users');
    }

    function get_status_by_id($status_id) {
        $this->db->where('sid', $status_id);
        return $this->db->get('terminated_employees')->result_array();
    }

    function get_status_documents($status_id) {
        $this->db->where('terminated_record_sid', $status_id);
        $this->db->where('status', 1);
        return $this->db->get('terminated_employees_documents')->result_array();
    }

    function check_for_main_status_update($emp_sid, $status_id){
        $this->db->select('sid');
        $this->db->where('employee_sid', $emp_sid);
        $this->db->order_by('sid','DESC');
        $status_result = $this->db->get('terminated_employees')->row_array();
        if(sizeof($status_result)){
            if($status_result['sid'] == $status_id){
                return true;
            }
        }
        return false;
    }


   //
    public function employees_transfer_log_update ($sid,$data_transfer_log_update) {
     
        $this->db->select('new_employee_sid');
        $this->db->where('new_employee_sid', $sid);
        $result=$this->db->get('employees_transfer_log')->row_array();

        if(!empty($result)){
            $data_update['employee_copy_date'] = $data_transfer_log_update['employee_copy_date'];
            $this->db->where('new_employee_sid',$sid);
            $this->db->update('employees_transfer_log',$data_update);

        }else{
            $data_transfer_log_update['from_company_sid'] = 0;
            $data_transfer_log_update['previous_employee_sid'] = 0;
            $data_transfer_log_update['employee_copy_date']=$data_transfer_log_update['employee_copy_date'];
            $data_transfer_log_update['last_update']=date('Y-m-d H:i:s');
            $data_transfer_log_update['new_employee_sid']=$sid;

            $this->db->insert('employees_transfer_log', $data_transfer_log_update);
        }

    }


}