<?php

class Automotive_groups_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function insert_automotive_group_record($data = array()) {
        if (!empty($data)) {
            $this->db->insert('automotive_groups', $data);
        }
    }

    function update_automotive_group_record($sid, $data = array()) {
        if (!empty($data)) {
            $this->db->where('sid', $sid);
            $this->db->update('automotive_groups', $data);
        }
    }

    function save_automotive_group_record($sid = 0, $data = array()) {
        if ($sid > 0) {
            $this->update_automotive_group_record($sid, $data);
        } else {
            $this->insert_automotive_group_record($data);
        }
    }

    function get_automotive_group_records($limit = 0, $offset = 0, $where_filters = array(), $like_filters = array(), $get_count = 0) {
        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
        }

        if (!empty($where_filters)) {
            foreach ($where_filters as $key => $filter) {
                $this->db->where($key, $filter);
            }
        }

        if (!empty($like_filters)) {
            foreach ($like_filters as $key => $filter) {
                $this->db->like($key, $filter);
            }
        }

        $this->db->order_by('LOWER(group_name)', 'ASC');
        $data_rows = $this->db->get('automotive_groups')->result_array();
        if($get_count)
            return $this->db->get('automotive_groups')->num_rows();

        if (!empty($data_rows)) {
            foreach ($data_rows as $key => $group) {
                $group_sid = $group['sid'];
                $corporate_company_sid = $group['corporate_company_sid'];
                $filters = array();
                $filters['automotive_group_sid'] = $group_sid;
                $limit = 0;
                $offset = 0;
                $member_companies = $this->get_automotive_group_member_companies($limit, $offset, $filters);
                $country_sid = $group['group_country_sid'];
                $country_name = $this->get_country_name($country_sid);

                if (isset($country_name[0]['country_name'])) {
                    $data_rows[$key]['country_name'] = $country_name[0]['country_name'];
                }

                if (!empty($member_companies)) {
                    $data_rows[$key]['member_companies'] = $member_companies;
                } else {
                    $data_rows[$key]['member_companies'] = array();
                }

                if ($corporate_company_sid > 0) {
                    $this->db->select('*');
                    $this->db->where('sid', $corporate_company_sid);
                    $corporate_company_detail = $this->db->get('users')->result_array();

                    if (!empty($corporate_company_detail)) {
                        $data_rows[$key]['corporate_company'] = $corporate_company_detail[0];
                    } else {
                        $data_rows[$key]['corporate_company'] = array();
                    }
                }
            }
        }
        return $data_rows;
    }

    function delete_automotive_group($automotive_group_sid) {
        $this->db->where('sid', $automotive_group_sid);
        $this->db->delete('automotive_groups');
    }

    function get_all_companies() {
        $this->db->select('sid, CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    function get_all_oem_brands() {
        $this->db->select('sid, oem_brand_name');
        $this->db->where('brand_status', 'active');
        return $this->db->get('oem_brands');
    }

    function insert_member_company_record($data) {
        $this->db->insert('automotive_group_companies', $data);
    }

    function update_member_company_record($sid, $data) {
        $this->db->where('sid', $sid);
        $this->db->update('automotive_group_companies', $data);
    }

    function get_automotive_group_member_companies($limit = 0, $offset = 0, $where_filters = array(), $like_filters = array()) {
        $this->db->select('*');

        if (!empty($where_filters)) {
            foreach ($where_filters as $key => $filter) {
                $this->db->where($key, $filter);
            }
        }

        if (!empty($like_filters)) {
            foreach ($like_filters as $key => $filter) {
                $this->db->like($key, $filter);
            }
        }

        if ($limit > 0 && $offset >= 0) {
            $this->db->limit($limit, $offset);
        }
        $this->db->order_by("sid", "DESC");
        return $this->db->get('automotive_group_companies')->result_array();
    }

    function delete_member_company_record($member_company_sid) {
        $this->db->where('sid', $member_company_sid);
        $this->db->delete('automotive_group_companies');
    }

    public function get_active_countries() {
        $this->db->select('*');
        $this->db->where('active', '1');
        $this->db->order_by("order", "asc");
        $this->db->from('countries');
        return $this->db->get()->result_array();
    }

    public function get_country_name($sid) {
        $this->db->select('country_name');
        $this->db->where('sid', $sid);
        return $this->db->get('countries')->result_array();
    }

    public function get_all_corporate_companies($selected_corporate_company_sid = null) {
        $this->db->select('corporate_company_sid');
        $automotive_corporate_companies = $this->db->get('automotive_groups')->result_array();
        $corporate_company_sids = array();
        
        foreach ($automotive_corporate_companies as $company) {
            $corporate_company_sids[] = $company['corporate_company_sid'];
        }

        if ($selected_corporate_company_sid != null) {
            unset($corporate_company_sids[array_search($selected_corporate_company_sid, $corporate_company_sids)]);
        }

        $this->db->select('*');
        $this->db->where('parent_sid', 0);

        if (!empty($corporate_company_sids)) {
            $this->db->where('sid NOT IN ( ' . implode(',', $corporate_company_sids) . ' ) ');
        }

        $this->db->where('career_page_type', 'corporate_career_site');
        $this->db->where('active', 1);
        return $this->db->get('users')->result_array();
    }

    public function get_group_executive_users($automotive_group_sid) {
        $this->db->select('sid');
        $this->db->where('automotive_group_sid', $automotive_group_sid);
        return $this->db->get('executive_users')->result_array();
    }
    
    function get_company_details($sid){
        $this->db->select('Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, Location_ZipCode, accounts_contact_person, accounts_contact_number');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }
    
    function check_company_exists_in_group($company_sid, $automotive_group_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('automotive_group_sid', $automotive_group_sid);
        $record_obj = $this->db->get('automotive_group_companies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }
}