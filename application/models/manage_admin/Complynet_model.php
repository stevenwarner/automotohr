<?php

class Complynet_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    public function getFilterRecords($keyword = null, $status = null, $start_date = null, $end_date = null,  $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('sid, uuid_field, request_url, request_method, response_code, created_at');

        if(!empty($status) && !is_null($status))
        {
            if ($status == 'success') {
                $this->db->where('response_code', 200);
            } else if ( $status == 'error') {
                $this->db->where('response_code <>', 200);
            }
        }
        if ($keyword &&$keyword != "all") {
            $this->db->group_start();
            $this->db->where("request_body REGEXP '$keyword'", null, null);
            $this->db->or_where("response_body REGEXP '$keyword'", null, null);
            $this->db->or_where("request_url REGEXP '$keyword'", null, null);
            $this->db->group_end();
        }
       

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('created_at BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('created_at >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('created_at <=', $end_date);
        }

        $this->db->from('complynet_calls');
        

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {

            if ($limit !== null && $offset !== null) {
                // $this->db->limit($limit, $offset);
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

    public function getCall ($sid) {
        $this->db->select('response_headers, response_body, request_body');
        $this->db->where('sid',1);

        $result = $this->db->get('complynet_calls')->row_array();
        return $result;
    }

}
