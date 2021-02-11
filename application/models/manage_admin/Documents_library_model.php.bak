<?php

class Documents_library_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_library_types() {
        $this->db->select('*');
        $this->db->where('parent_sid', 0);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->from('documents_library_types');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_library_types_tree() {
        $this->db->select('*');
        //$this->db->where('status', 1);
        $this->db->where('parent_sid', 0);
        $this->db->order_by('sort_order', 'ASC');
        $this->db->from('documents_library_types');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)) {
            foreach($records_arr as $key => $value) {
                $result = array();
                $sid = $value['sid'];
                $this->db->select('*');
                //$this->db->where('status', 1);
                $this->db->where('parent_sid', $sid);
                $this->db->order_by('sort_order', 'ASC');
                $this->db->from('documents_library_types');
                $records_obj = $this->db->get();
                $result = $records_obj->result_array();
                $records_obj->free_result();
                $records_arr[$key]['sub_library_types'] = $result;
            }
        }
        return $records_arr;
    }

    function get_type_details($sid) {
        $this->db->select('*');
        $this->db->where('category_id', $sid);
        $this->db->where('type <>', NULL);
        $this->db->order_by('sid', 'ASC');
        $this->db->from('document_library_sub_menu');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_sub_heading_details($sid) {
        $this->db->select('*');
        $this->db->where('menu_id', $sid);
        $this->db->where('type <>', NULL);
        $this->db->order_by('sid', 'ASC');
        $this->db->from('document_library_sub_menu');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function update_incident_type($id,$data){
        $this->db->where('sid',$id);
        $type = $this->db->update('documents_library_types',$data);
        return $type;
    }

    function update_sub_menu($id,$data){
        $this->db->where('sid',$id);
        $type = $this->db->update('document_library_sub_menu',$data);
        return $type;
    }

    function add_new_sub_menu($data){
        $this->db->insert('document_library_sub_menu',$data);
        $sid = $this->db->insert_id();
        
        if(isset($data['title']) && !empty($data['title'])) {
            $sub_url_code = strtolower(clean($data['title']));
            $data_to_update = array('sub_url_code' => $sub_url_code);
            $this->db->where('sid', $sid);
            $this->db->update('document_library_sub_menu', $data_to_update);
        }
        
        return $sid;
    }

    function edit_sub_menu($sid,$data){
        $this->db->where('sid', $sid);
        $this->db->update('document_library_sub_menu',$data);
    }

    function fetch_sub_menu($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from('document_library_sub_menu');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_library_docs($docs){
        $this->db->insert('document_library_files',$docs);
        $sid = $this->db->insert_id();
        $file_url_code = $docs['file_url_code'].'-v'.$sid;
        $data_to_update = array('file_url_code' => $file_url_code);
        $this->db->where('sid', $sid);
        $this->db->update('document_library_files', $data_to_update);
        return $sid;
    }

    function update_library_docs($docs,$id){
        $this->db->where('sid',$id);
        $this->db->update('document_library_files',$docs);
    }

    function fetch_sub_menu_files($sid,$type){
        $this->db->select('sid,file_code,file_name,type,upload_date,federal_check,states,country,sort_order,word_content,status');
        $this->db->where('menu_id', $sid);
        $this->db->where('doc_type', $type);
        $this->db->from('document_library_files');
        $this->db->order_by('sort_order','ASC');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function edit_file($fid,$data){
        $this->db->where('sid',$fid);
        $this->db->update('document_library_files',$data);
    }

    function get_parent_name($sid){
        $this->db->select('name');
        $this->db->where('sid',$sid);
        $result = $this->db->get('documents_library_types')->result_array();
        return $result[0]['name'];
    }

    function get_tree($par,$child,$type){
        if($type=='menu'){
            $this->db->select('title,name');
            $this->db->where('document_library_sub_menu.sid',$child);
            $this->db->join('documents_library_types','documents_library_types.sid = document_library_sub_menu.category_id','left');
            $result = $this->db->get('document_library_sub_menu')->result_array();
        } else{
            $this->db->select('t2.title as menu,name,t1.title as heading');
            $this->db->where('t1.sid',$child);
            $this->db->join('document_library_sub_menu as t2','t2.sid = t1.menu_id','left');
            $this->db->join('documents_library_types','documents_library_types.sid = t2.category_id','left');
            $result = $this->db->get('document_library_sub_menu as t1')->result_array();
        }
        return $result;
    }

    function delete_file($fid){
        $this->db->where('sid',$fid);
        $this->db->delete('document_library_files');
    }

}