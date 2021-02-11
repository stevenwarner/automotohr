<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Turnover_cost_calculator_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function insert_turnover_cost_calculator_record($data){
        $this->db->insert('turnover_cost_calculator_data', $data);
        return $this->db->insert_id();
    }

    function get_turnover_cost_calculation_key($sid){
        $this->db->select('random_secure_key');
        $this->db->where('sid', $sid);
        $data = $this->db->get('turnover_cost_calculator_data')->result_array();

        if(!empty($data)){
            return $data[0]['random_secure_key'];
        } else {
            return null;
        }
    }

    function get_turnover_cost_calculation_record($secure_key){
        $this->db->select('*');
        $this->db->where('random_secure_key', $secure_key);

        $data = $this->db->get('turnover_cost_calculator_data')->result_array();

        if(!empty($data)){
            return $data[0];
        } else {
            return array();
        }
    }

    function get_all_turnover_cost_calculation_records($limit = null, $offset = null){

        $this->db->select('*');

        if($limit != null && $offset != null){
            $this->db->limit($limit, $offset);
        }

        $this->db->order_by('sid', 'DESC');

        return $this->db->get('turnover_cost_calculator_data')->result_array();
    }

    function update_page_visit_count(){
        $record_sid = 1;

        $this->db->select('sid, tcc_page_visit_count');
        $this->db->where('sid', $record_sid);
        $visit_count_data = $this->db->get('settings')->result_array();

        if(!empty($visit_count_data)){
            $visit_count = intval($visit_count_data[0]['tcc_page_visit_count']);

            $visit_count = $visit_count + 1;

            $data_to_update = array();
            $data_to_update['tcc_page_visit_count'] = $visit_count;


            $this->db->where('sid', $record_sid);
            $this->db->update('settings', $data_to_update);
        }
    }

    function get_page_visit_count(){
        $record_sid = 1;

        $this->db->select('sid, tcc_page_visit_count');
        $this->db->where('sid', $record_sid);
        $visit_count_data = $this->db->get('settings')->result_array();

        if(!empty($visit_count_data)){
            return $visit_count_data[0]['tcc_page_visit_count'];
        } else {
            return 0;
        }
    }
}
