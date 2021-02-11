<?php

class Safety_data_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function add_category($data){
        $this->db->insert('safety_sheet_category',$data);
        return $this->db->insert_id();
    }

    function get_all_category($for_sheet = 0){
        $this->db->select('*');
        if($for_sheet){
            $this->db->where('status',$for_sheet);
        }
        $types = $this->db->get('safety_sheet_category')->result_array();
        return $types;
    }

    function get_all_safety_data(){
        $this->db->select('*');
        $this->db->where('status <>', 2);
        $data = $this->db->get('safety_data_sheet')->result_array();
        return $data;
    }

    function get_category($id){
        $this->db->select('*');
        $this->db->where('sid',$id);
        $type = $this->db->get('safety_sheet_category')->result_array();
        return $type;
    }

    function update_category($id,$data){
        $this->db->where('sid',$id);
        $type = $this->db->update('safety_sheet_category',$data);
        return $type;
    }

    function update_sheet($id,$data){
        $this->db->where('sid',$id);
        $type = $this->db->update('safety_data_sheet',$data);
        return $type;
    }

    function add_safety_data_sheet($data){
        $this->db->insert('safety_data_sheet',$data);
        return $this->db->insert_id();
    }

    function edit_safety_data_sheet($sid,$data){
        $this->db->where('sid',$sid);
        $this->db->update('safety_data_sheet',$data);
    }

    function safety_data_sheet_files($data){
        $this->db->insert('safety_data_sheet_files',$data);
        return $this->db->insert_id();
    }

    function sheet_to_category($insert_array){
        $this->db->insert('sheet_2_category',$insert_array);
        return $this->db->insert_id();
    }

    function get_data_sheet_by_id($sid){
        $this->db->select('safety_data_sheet.*');
        $this->db->where('safety_data_sheet.sid',$sid);
//        $this->db->join('safety_data_sheet_files','safety_data_sheet_files.sheet_sid = safety_data_sheet.sid','left');
        $result = $this->db->get('safety_data_sheet')->result_array();
        if($result){
            $this->db->select('sheet_2_category.category_sid');
            $this->db->where('sheet_2_category.sheet_sid',$sid);
            $selected_categories = $this->db->get('sheet_2_category')->result_array();
            if(sizeof($selected_categories)>0){
                foreach($selected_categories as $cat){
                    $result[0]['categories'][] = $cat['category_sid'];
                }
            }else{
                $result[0]['categories'] = array();
            }
        }
        return $result;
    }

    function delete_sheet_to_category($cat_id,$sheet_id){
        $this->db->where('category_sid',$cat_id);
        $this->db->where('sheet_sid',$sheet_id);
        $this->db->delete('sheet_2_category');
    }

    function get_sheet_files($sid){
        $this->db->select('file_code,file_name,sid');
        $this->db->where('sheet_sid',$sid);
        $files = $this->db->get('safety_data_sheet_files')->result_array();
        return $files;
    }

    function delete_file($fid){
        $this->db->where('sid',$fid);
        $this->db->delete('safety_data_sheet_files');
    }

//    function get_data_sheet_by_id($sid){
//        $this->db->select('safety_data_sheet.*,safety_sheet_category.sid as cat_sid,safety_sheet_category.name');
//        $this->db->where('safety_data_sheet.sid',$sid);
//        $this->db->join('sheet_2_category','sheet_2_category.sheet_sid = safety_data_sheet.sid','left');
//        $this->db->join('safety_sheet_category','safety_sheet_category.sid = sheet_2_category.category_sid','left');
//        $result = $this->db->get('safety_data_sheet')->result_array();
//
//        return $result;
//    }

}