<?php

class Resource_page_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get_page_data($page_name){
        $this->db->select('*');
        $this->db->where('page_name', $page_name);
        $record_obj = $this->db->get('dynamic_pages');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            $record_arr = $record_arr[0];
            $page_sid = $record_arr['sid'];

            $this->db->where('dynamic_pages_sid', $page_sid);

            $sections_obj = $this->db->get('dynamic_pages_sections');
            $sections_arr = $sections_obj->result_array();
            $sections_obj->free_result();

            $record_arr['sections'] = $sections_arr;

            return $record_arr;
        } else {
            return array();
        }
    }

    public function insert_page_record($data_to_insert){
        $this->db->insert('dynamic_pages', $data_to_insert);
    }

    public function update_page_record($sid, $data_to_update){
        $this->db->where('sid', $sid);
        $this->db->update('dynamic_pages', $data_to_update);
    }

    public function save_page_record($sid, $data){
        if(empty($sid) || $sid == 0){
            $this->insert_page_record($data);
        } else {
            $this->update_page_record($sid, $data);
        }
    }



    function insert_dynamic_pages_section_record($data_to_insert){
        $this->db->insert('dynamic_pages_sections', $data_to_insert);
    }

    function update_dynamic_pages_section_record($sid, $data_to_insert){
        $this->db->where('sid', $sid);
        $this->db->update('dynamic_pages_sections', $data_to_insert);
    }

    function get_dynamic_pages_section_records($status = null){
        $this->db->select('*');

        if($status !== null){
            $this->db->where('status', $status);
        }

        $records_obj = $this->db->get('dynamic_pages_sections');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_single_dynamic_pages_section_record($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('dynamic_pages_sections');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if(!empty($record_arr)){
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function delete_dynamic_pages_section($section_sid){
        $this->db->where('sid', $section_sid);
        $this->db->delete('dynamic_pages_sections');
    }


}
