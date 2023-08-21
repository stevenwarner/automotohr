<?php

class Complynet_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getFilterRecords($keyword = null, $status = null, $method = null, $start_date = null, $end_date = null,  $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('sid, uuid_field, request_url, request_method, response_code, created_at');

        $this->db->where("request_url <>", 'https://api.complynet.com/Token');

        if ($keyword != "all") {
            $this->db->group_start();
            $this->db->where("request_body REGEXP '$keyword'", null, null);
            $this->db->or_where("response_body REGEXP '$keyword'", null, null);
            $this->db->or_where("request_url REGEXP '$keyword'", null, null);
            $this->db->group_end();
        }

        //
        if ($status != 'all') {
            if ($status == 'success') {
                $this->db->where('response_code', 200);
            } else if ($status == 'error') {
                $this->db->where('response_code <>', 200);
            }
        }
        //
        if ($method && $method != 'all') {
            $this->db->where('LOWER(request_method)', $method);
        }
        //
        if ($start_date != 'all' && $end_date != 'all') {
            $this->db->where('created_at BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        }

        $this->db->order_by('created_at', 'DESC');
        $this->db->from('complynet_calls');


        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                $this->db->limit($limit, $offset);
            }

            $result = array();
            $records_obj = $this->db->get();
            if ($records_obj) {
                $result = $records_obj->result_array();
                $records_obj->free_result();
            }

            return $result;
        }
    }

    public function getCall($sid)
    {
        $this->db->select('response_code, request_url, response_headers, response_body, request_body');
        $this->db->where('sid', $sid);

        $result = $this->db->get('complynet_calls')->row_array();
        return $result;
    }


    //
    public function getComplynetCompanies()
    {
        $this->db->select('company_sid,company_name');
        $this->db->where('status', 1);
        return $this->db->get('complynet_companies')->result_array();
    }


    public function getoverviewData($keyword, $status, $companies)
    {

        //
        $overviewData['companiesTotalEmployees'] = 0;
        $overviewData['companiesTotalEmployeesOnComplynet'] = 0;


        if ($companies['0'] == '0' || $companies == '') {
            $complynetCompanies = $this->getComplynetCompanies();
            $companies = array_column($complynetCompanies, 'company_sid');
        }

        // Company employees
        $this->db->from('users');

        if ($status == 'Active') {
            $this->db->where('general_status', 'active');
            $this->db->where('terminated_status', 0);
        }
        if ($status == 'Inactive') {
            $this->db->where('general_status', 'inactive');
        }

        if ($keyword != '') {
            if (strpos($keyword, '@') !== false) {
                $this->db->or_where('email', $keyword);
            } else {
                $this->db->where("first_name regexp '$keyword'", null, null);
                $this->db->or_where("last_name regexp '$keyword'", null, null);
            }
        }


        $this->db->where_in('parent_sid', $companies);
        //
        $companiesTotalEmployees = $this->db->count_all_results();


        // ComplyNet Employees
        $this->db->from('complynet_employees');
        if ($status != 'all' && $status != '') {
            $str = 'complynet_json REGEXP \'"Status":' . ($status == 'Active' ? "true" : "false") . '\'';
            $this->db->where($str, null, null);
        }

        if ($keyword != '') {
            $this->db->where("complynet_json regexp '$keyword'", null, null);
        }

        $this->db->where_in('company_sid', $companies);
        //
        $companiesTotalEmployeesOnComplynet = $this->db->count_all_results();

        // echo $this->db->last_query();

        $overviewData['companiesTotalEmployees'] = $companiesTotalEmployees;
        $overviewData['companiesTotalEmployeesOnComplynet'] = $companiesTotalEmployeesOnComplynet;

        //
        return $overviewData;
    }
}