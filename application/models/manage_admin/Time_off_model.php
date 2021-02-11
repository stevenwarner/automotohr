<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Time_off_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_company_timeoff_policies($company_sid) {

        $this->db->select('timeoff_category_list.sid as cat_sid,timeoff_policies.title,,timeoff_policies.is_archived, timeoff_policies.type_sid,timeoff_policies.created_at,timeoff_policies.sid, timeoff_policies.default_policy, timeoff_policies.status');
        $this->db->where('timeoff_policies.company_sid',$company_sid);
        $this->db->order_by('timeoff_policies.title', 'ASC');
        $this->db->join('timeoff_categories','timeoff_categories.sid=timeoff_policies.type_sid','inner');
        $this->db->join('timeoff_category_list','timeoff_category_list.sid=timeoff_categories.timeoff_category_list_sid','inner');
        $policies = $this->db->get('timeoff_policies')->result_array();

        if (!empty($policies)) {
        	return $policies;
        } else {
        	return array();
        }
    }

    public function get_all_timeoff_category () {
    	$this->db->select('sid, category_name');
    	$this->db->order_by('category_name', 'ASC');
        $categories = $this->db->get('timeoff_category_list')->result_array();

        if (!empty($categories)) {
        	return $categories;
        	// $category_list = array();
        	// foreach ($categories as $category) {
        	// 	$category_list[$category['sid']] = $category['category_name'];
        	// }
        	// return $category_list;
        } else {
        	return array();
        }
    }

    public function get_security_access_levels () {
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $access_levels = $this->db->get('security_access_level')->result_array();
        $my_return = array();

        foreach ($access_levels as $access_level) {
            $my_return[] = $access_level['access_level'];
        }

        return $my_return;
    }

    public function change_company_policy_status ($policy_sid, $company_sid, $data_to_update) {
    	$this->db->where('sid', $policy_sid);
    	$this->db->where('company_sid', $company_sid);
        $this->db->update('timeoff_policies', $data_to_update);
    }

    public function get_all_timeoff_icons () {
    	$this->db->select('*');
        $this->db->order_by('sort_order', 'ASC');
        $icons = $this->db->get('timeoff_policy_icons_info')->result_array();

        if (!empty($icons)) {
        	return $icons;
        } else {
        	return array();
        }
    }

    public function change_time_off_icon_info_content ($icon_id, $data_to_update) {
        $this->db->where('sid', $icon_id);
        $this->db->update('timeoff_policy_icons_info', $data_to_update);
    }

    public function get_company_timeoff_approvers ($company_sid) {
        $this->db->select('
            timeoff_approvers.sid, 
            timeoff_approvers.employee_sid, 
            timeoff_approvers.department_sid, 
            timeoff_approvers.status, 
            timeoff_approvers.approver_percentage, 
            timeoff_approvers.is_archived, 
            timeoff_approvers.is_department, 
            timeoff_approvers.created_at,
            users.first_name,
            users.last_name,
            users.profile_picture');
        $this->db->where('timeoff_approvers.company_sid', $company_sid);
        $this->db->where('users.parent_sid >', 0);
        $this->db->where('users.terminated_status', 0);
        $this->db->where('users.active', 1);
        $this->db->join('users', 'timeoff_approvers.employee_sid = users.sid', 'inner');
        $approvers = $this->db->get('timeoff_approvers')->result_array();

        if (!empty($approvers)) {
            return $approvers;
        } else {
            return array();
        }
    }

    public function get_department_team_name ($sid, $is_department) {
        $this->db->select('name');
        $this->db->where('sid', $sid);
        if ($is_department === 1) {
            $records_obj = $this->db->get('departments_management');
        } else {
            $records_obj = $this->db->get('departments_team_management');
        }
        
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr[0]['name'];
        }

        return $return_data;
    }

    public function change_company_approvers_status ($approver_sid, $company_sid, $data_to_update) {//echo $approver_sid; die('kk');
        $this->db->where('sid', $approver_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->update('timeoff_approvers', $data_to_update);
    }

}