<?php

class General_info_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_license_details($user_type, $user_sid, $license_type) {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->limit(1);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_dependant_information($user_type, $user_sid) {
        $this->db->select('*');
        $this->db->where('users_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('have_dependents', '1');

        $record_obj = $this->db->get('dependant_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            foreach ($record_arr as $key => $record) {
                if (isset($record['dependant_details'])) {
                    $dependant = unserialize($record['dependant_details']);
                    unset($record['dependant_details']);
                    $new_data = array_merge($record, $dependant);
                    unset($record_arr[$key]['dependant_details']);
                    $record_arr[$key] = $new_data;
                }
            }
            
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_employee_emergency_contacts($user_type, $user_sid) {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $records_obj = $this->db->get('emergency_contacts');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_bank_details($user_type, $user_sid) {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);

        $record_obj = $this->db->get('bank_account_details');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_equipment_info($user_type, $users_sid) {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('delete_flag', 0);;

        $records_obj = $this->db->get('equipment_information');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function check_user_license($user_sid, $user_type, $license_type) {
        $this->db->select('*');
        $this->db->where('users_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->where('license_type', $license_type);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_license_info($license_id, $licenseData,$dateOfBirth = array(), $employeeId = null) {
        $this->db->where('sid', $license_id);
        $this->db->update('license_information', $licenseData);
        if(!empty($dateOfBirth) && $employeeId != null && ($licenseData['users_type'] == 'employee' || $licenseData['users_type'] == 'Employee')){
            $this->db->where('sid', $employeeId)
                ->update('users', $dateOfBirth);
        }
    }

    function save_license_info($data,$dateOfBirth = array(), $employeeId = null) {
        $this->db->insert('license_information', $data);
        if(!empty($dateOfBirth) && $employeeId != null && ($data['users_type'] == 'employee' || $data['users_type'] == 'Employee') ){
            $this->db->where('sid', $employeeId)
                ->update('users', $dateOfBirth);
        }
    }

    function dependant_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('dependant_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_dependant_info($dependant_id, $dependantData) {
        $this->db->where('sid', $dependant_id);
        $this->db->update('dependant_information', $dependantData);
    }

    function insert_dependent_information($data_to_save) {
        $this->db->insert('dependant_information', $data_to_save);
    }

    function delete_dependent_information($dependent_sid) {
        $this->db->where('sid', $dependent_sid);
        $this->db->delete('dependant_information');
    }

    function emergency_contacts_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('emergency_contacts');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function edit_emergency_contacts($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('emergency_contacts', $data_to_update);
        $result = $this->db->affected_rows();

        if ($result != 1) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not update emergency contact, Please try Again!');
            $result = 'success';
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Emergency contact updated successfully.');
            $result = 'fail';
        }

        return $result;
    }

    function insert_employee_emergency_contact($data_to_insert) {
        $this->db->insert('emergency_contacts', $data_to_insert);
    }

    function delete_emergency_contact($contact_sid) {
        $this->db->where('sid', $contact_sid);
        $this->db->delete('emergency_contacts');
    }

    function save_bank_details($user_type, $user_sid, $bank_details) {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->from('bank_account_details');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('users_sid', $user_sid);

            if (isset($bank_details['users_type'])) {
                unset($bank_details['users_type']);
            }

            if (isset($bank_details['users_sid'])) {
                unset($bank_details['users_sid']);
            }

            $this->db->update('bank_account_details', $bank_details);
        } else {
            $this->db->insert('bank_account_details', $bank_details);
        }
    }

    function equipment_info_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('equipment_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_equipment_acknomledgement($data_to_update, $equipment_sid, $user_sid, $user_type) {
        $this->db->where('sid', $equipment_sid);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->update('equipment_information', $data_to_update);
    }
    function get_dob($employeeId){
        return $this->db->where('sid',$employeeId)->get('users')->row()->dob;
    }

    function get_user_info($sid){
        $this->db->select('full_employment_application,extra_info');
        $this->db->where('sid',$sid);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function update_user($sid, $data){
        $this->db->where('sid',$sid);
        $this->db->update('users',$data);

    }

}
