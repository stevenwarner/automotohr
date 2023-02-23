<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Security_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_enum_values() {
        $type = $this->db->query("SHOW COLUMNS FROM `users` WHERE Field = 'access_level'")->row(0)->Type;
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        $enum = explode("','", $matches[1]);
        return $enum;
    }

    function get_all_security_levels() {
        $this->db->select('*');
        $this->db->from('security_access_level');
        return $this->db->get()->result_array();
    }

    function security_levels_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('security_access_level');
        $result = $this->db->get()->result_array();
        
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function security_levels_available_modules() {
        $this->db->select('available_modules');
        $this->db->where('sid', 1);
        $this->db->from('security_access_level');
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }
    }

    function update_permission($sid, $update_permission, $description) {
        $this->db->set('permissions', $update_permission);
        $this->db->set('description', $description);
        $this->db->where('sid', $sid);
        $result = $this->db->update('security_access_level');
        (!$result) ? $this->session->set_flashdata('message', 'Update Failed, Please try Again!') : $this->session->set_flashdata('message', 'Security Settings updated successfully');
    }

    function get_access_level_specific_users($access_level) {
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('access_level');
        $this->db->select('CompanyName');
        $this->db->select('parent_sid');
        $this->db->select('sid as user_sid');
        $this->db->where('parent_sid > 0');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('access_level', $access_level);
        $this->db->order_by('parent_sid', 'ASC');
        $users = $this->db->get('users')->result_array();

        foreach ($users as $key => $user) {
            $this->db->select('CompanyName');
            $this->db->where('sid', $user['parent_sid']);
            $company_detail = $this->db->get('users')->result_array();

            if (!empty($company_detail)) {
                $users[$key]['CompanyName'] = $company_detail[0]['CompanyName'];
            }
        }

        return $users;
    }

    function set_access_level($company_sid, $user_sid, $access_level) {
        $data_to_update = array();
        $data_to_update['access_level'] = $access_level;
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_sid);
        $this->db->update('users', $data_to_update);
    }

    function set_access_level_status($access_level_sid, $status = 0) {
        $data_to_update = array();
        $data_to_update['status'] = $status;
        $this->db->where('sid', $access_level_sid);
        $this->db->update('security_access_level', $data_to_update);
    }

    function get_company_users($sid) {
        $this->db->select('sid, first_name, last_name, username, access_level,email, is_primary_admin');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        return $this->db->get('users')->result_array();
    }
    
    function get_security_access_levels(){
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $access_levels = $this->db->get('security_access_level')->result_array();
        $my_return = array();

        foreach ($access_levels as $access_level) {
            $my_return[] = $access_level['access_level'];
        }

        return $my_return;
    }


        // 
        public function save_security_level($data) {
            $this->db->insert('security_access_level', $data);
            return $this->db->insert_id();
               }

}