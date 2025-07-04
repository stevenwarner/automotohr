<?php

class Safety_sheets_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function fetch_all_categories() {
        $this->db->where('status', 1);
        $types = $this->db->get('safety_sheet_category')->result_array();
        return $types;
    }

    function fetch_sheets_to_category($cat_id) {
        $this->db->select('safety_data_sheet.sid,safety_data_sheet.company_sid,safety_data_sheet.title,safety_data_sheet.created_date,safety_data_sheet.notes');
        $this->db->where('sheet_2_category.category_sid', $cat_id);
        $this->db->where('safety_data_sheet.status', 1);
        $this->db->join('safety_data_sheet','safety_data_sheet.sid = sheet_2_category.sheet_sid','left');
        $sheet_2_category = $this->db->get('sheet_2_category')->result_array();
        return $sheet_2_category;
    }

    function fetch_sheet_details($sheet_id) {
        $this->db->select('safety_data_sheet.company_sid,file_code,file_name,title,notes,created_date,type');
        $this->db->where('safety_data_sheet.sid', $sheet_id);
//        $this->db->where('safety_data_sheet_files.sheet_sid', $sheet_id);
        $this->db->join('safety_data_sheet_files','safety_data_sheet_files.sheet_sid = safety_data_sheet.sid','left');
        $sheet_2_category = $this->db->get('safety_data_sheet')->result_array();
        return $sheet_2_category;
    }

    //Starting Of Company Level Safety Sheet Management

    function check_unique_with_name($company_sid,$name,$table = 'cat'){
        $this->db->where('company_sid',$company_sid);
        if($table == 'cat'){
            $this->db->where('name',$name);
            $result = $this->db->get('safety_sheet_category')->num_rows();
        } else{
            $this->db->where('title',$name);
            $result = $this->db->get('safety_data_sheet')->num_rows();
        }
        return $result;
    }

    function get_all_categories_for_sheets($company_sid = 0){
        $this->db->select('*');
        $this->db->where('company_sid',$company_sid);
        $this->db->where('status',1);
        $this->db->order_by('company_sid','DESC');
        $types = $this->db->get('safety_sheet_category')->result_array();
        return $types;
    }

    function get_all_categories_company_specific($company_sid = 0){
        $this->db->select('*');
        $this->db->where('company_sid',$company_sid);
        $this->db->order_by('company_sid','DESC');
        $types = $this->db->get('safety_sheet_category')->result_array();
        return $types;
    }
    function get_all_category($company_sid = 0, $for_sheet = 0){
        $this->db->select('*');
        $this->db->where('company_sid',$company_sid);
        if($for_sheet){
            $this->db->where('status',$for_sheet);
        }
        $this->db->order_by('company_sid','DESC');
        $types = $this->db->get('safety_sheet_category')->result_array();
        return $types;
    }

    function update_category($id,$data){
        $this->db->where('sid',$id);
        $type = $this->db->update('safety_sheet_category',$data);
        return $type;
    }

    function add_category($data){
        $this->db->insert('safety_sheet_category',$data);
        return $this->db->insert_id();
    }

    function get_category($id){
        $this->db->select('*');
        $this->db->where('sid',$id);
        $type = $this->db->get('safety_sheet_category')->result_array();
        return $type;
    }

    function get_all_safety_data($company_sid){
        $this->db->select('safety_data_sheet.*');
        $this->db->where('company_sid', $company_sid);
//        $this->db->or_where('company_sid', 0);
        $this->db->where('status <>', 2);
        $data = $this->db->get('safety_data_sheet')->result_array();
//        if($data){
//            foreach($data as $key => $sheet_id){
//                $this->db->select('sheet_2_category.category_sid');
//                $this->db->where('sheet_2_category.sheet_sid',$sheet_id['sid']);
//                $selected_categories = $this->db->get('sheet_2_category')->result_array();
//                if(sizeof($selected_categories)>0){
//                    foreach($selected_categories as $cat){
//                        $data[$key]['categories'][] = $cat['category_sid'];
//                    }
//                }else{
//                    $data[$key]['categories'] = array();
//                }
//            }
//        }
//        echo '<pre>';
//        print_r($data);
//        die();
        return $data;
    }

    function get_all_safety_data_company($company_sid,$cat_sid){
        $this->db->select('sheet_2_category.category_sid,safety_data_sheet.*');
        $this->db->where('sheet_2_category.category_sid', $cat_sid);
        $this->db->where('safety_data_sheet.status', 1);
        $this->db->where('safety_data_sheet.company_sid', $company_sid);
        $this->db->join('safety_data_sheet','safety_data_sheet.sid = sheet_2_category.sheet_sid','left');
        $selected_categories = $this->db->get('sheet_2_category')->result_array();


//        $this->db->select('safety_data_sheet.*');
//        $this->db->where('company_sid', $company_sid);
//        $this->db->where('status <>', 2);
//        $data = $this->db->get('safety_data_sheet')->result_array();
//        if($data){
//            foreach($data as $key => $sheet_id){
//                $this->db->select('sheet_2_category.category_sid');
//                $this->db->where('sheet_2_category.sheet_sid',$sheet_id['sid']);
//                $selected_categories = $this->db->get('sheet_2_category')->result_array();
//                if(sizeof($selected_categories)>0){
//                    foreach($selected_categories as $cat){
//                        $data[$key]['categories'][] = $cat['category_sid'];
//                    }
//                }else{
//                    $data[$key]['categories'] = array();
//                }
//            }
//        }
//        echo '<pre>';
//        print_r($selected_categories);
//        die();
        return $selected_categories;
    }

    function add_safety_data_sheet($data){
        $this->db->insert('safety_data_sheet',$data);
        return $this->db->insert_id();
    }

    function safety_data_sheet_files($data){
        $this->db->insert('safety_data_sheet_files',$data);
        return $this->db->insert_id();
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

    function edit_safety_data_sheet($sid,$data){
        $this->db->where('sid',$sid);
        $this->db->update('safety_data_sheet',$data);
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

    function get_data_sheet_for_clone($sid){
        $this->db->select('safety_data_sheet.*');
        $this->db->where('safety_data_sheet.sid',$sid);
        $result = $this->db->get('safety_data_sheet')->result_array();
        $result[0]['categories'] = array();
        return $result;
    }

    function delete_sheet_to_category($cat_id,$sheet_id){
        $this->db->where('category_sid',$cat_id);
        $this->db->where('sheet_sid',$sheet_id);
        $this->db->delete('sheet_2_category');
    }

    public function GetAllUsers($company_sid) {
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        //$this->db->where('password !=', '');
        $this->db->where('active', 1);
        //$this->db->where('is_executive_admin', 0);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function check_unique_other_name($company_sid, $name_title,$table = 'cat',$sid){
        $this->db->where('company_sid',$company_sid);
        $this->db->where('sid <> ',$sid);
        $this->db->where('status <>', 2);
        if($table == 'cat'){
            $this->db->where('name',$name_title);
            $result = $this->db->get('safety_sheet_category')->num_rows();
        } else{
            $this->db->where('title',$name_title);
            $result = $this->db->get('safety_data_sheet')->num_rows();
        }
        return $result;
    }

    public function delete_sheet($cat_id,$sheet_sid){
        $this->db->where('sheet_sid',$sheet_sid);
        $this->db->from('sheet_2_category');
        $result =  $this->db->count_all_results();
        if($result > 1){
            $this->db->where('sheet_sid',$sheet_sid);
            $this->db->where('category_sid',$cat_id);
            $this->db->delete('sheet_2_category');
        } else{
            $this->db->where('sheet_sid',$sheet_sid);
            $this->db->where('category_sid',$cat_id);
            $this->db->delete('sheet_2_category');

            $this->db->where('sid',$sheet_sid);
            $this->db->delete('safety_data_sheet');
        }
    }
}
