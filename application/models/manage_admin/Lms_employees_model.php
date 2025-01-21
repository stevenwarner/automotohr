<?php
class Lms_employees_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_corporate_groups($active = 1)
    {
        $this->db->select('sid, group_name');
        $this->db->where('corporate_company_sid <>', 0);
        $this->db->order_by('group_name', 'ASC');
        $this->db->from('automotive_groups');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_companies()
    {

        $this->db->select('sid, CompanyName');
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('parent_sid', 0);
        $this->db->order_by('CompanyName', 'ASC');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $result = array();
        if (!empty($records_arr)) {
            $result = $records_arr;
        }
        return $result;
    }

    function get_corporate_companies_by_id($sid)
    {
        $this->db->select('company_sid');
        $this->db->where('automotive_group_sid', $sid);
        $this->db->where('is_registered_in_ahr', 1);
        $this->db->from('automotive_group_companies');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $result = array();
        if (!empty($records_arr)) {
            $result = $records_arr;
        }
        return $result;
    }

    function get_company_name_by_id($sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr[0]['CompanyName'];
        }

        return $return_data;
    }

    function get_company_employee($sid)
    {
        $this->db->select('sid, email, first_name, last_name, active, job_title, access_level, access_level_plus, pay_plan_flag, terminated_status');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_executive_admin', 0);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $records_obj = $this->db->get('users');

        // _e($this->db->last_query(), true);
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_employee_count($sid, $type, $employee_keyword)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_executive_admin', 0);

        if ($type == 'active') {
            $this->db->where('active', 1);
            $this->db->where('terminated_status', 0);
        }

        if ($type == 'terminated') {
            $this->db->where('terminated_status', 1);
        }

        if ($type != 'all' && $type != 'active' && $type != 'terminated'  && $type != null) {
            $this->db->where('LCASE(general_status) ', $type);
        }


        if (trim($employee_keyword)) {
            //
            $keywords = explode(',', trim($employee_keyword));
            $this->db->group_start();
            //
            foreach ($keywords as $keyword) {
                $this->db->or_group_start();
                //
                $keyword = trim(urldecode($keyword));
                //
                if (strpos($keyword, '@') !== false) {
                    $this->db->or_where('email', $keyword);
                } else {
                    $this->db->where("first_name regexp '$keyword'", null, null);
                    $this->db->or_where("last_name regexp '$keyword'", null, null);
                    $this->db->or_where("nick_name regexp '$keyword'", null, null);
                    $this->db->or_where("extra_info regexp '$keyword'", null, null);
                    $this->db->or_where('lower(concat(first_name, last_name)) =', strtolower(preg_replace('/[^a-z0-9]/i', '', $keyword)));
                }
                $this->db->group_end();
            }
            $this->db->group_end();
        }


        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_count = 0;

        if (!empty($records_arr)) {
            $return_count = count($records_arr);
        }

        return $return_count;
    }

    function fetch_employee_by_sid($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr[0];
        }

        return $return_data;
    }

    function check_employee_exist($email, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->where('is_executive_admin', 0);
        $this->db->from('users');
        $ids = $this->db->count_all_results();

        return $ids != 0 ? true : false;
    }

    function check_employee_username_exist($username)
    {
        $this->db->select('sid');
        $this->db->where('username', $username);
        $this->db->from('users');
        $ids = $this->db->count_all_results();

        return $ids != 0 ? true : false;
    }

    
}
