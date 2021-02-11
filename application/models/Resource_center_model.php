<?php
class resource_center_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_parent_menu_tree() {
        $this->db->select('documents_library_types.sid as main_sid, documents_library_types.name, documents_library_types.url_code, documents_library_types.parent_sid, documents_library_types.status as parent_status, documents_library_types.sort_order as parent_sort_order, documents_library_types.description as parent_description, documents_library_types.activation_date, documents_library_types.deactivation_date, documents_library_types.fa_icon');
        $this->db->select('document_library_sub_menu.*');
        $this->db->where('documents_library_types.status', 1);
//        $this->db->where('document_library_sub_menu.status', 1);
        $this->db->order_by('documents_library_types.sort_order', 'asc');
        $this->db->join('document_library_sub_menu', 'document_library_sub_menu.category_id = documents_library_types.sid', 'left');
        $this->db->from('documents_library_types');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function get_parent_menu() {
        $this->db->select('*');
        $this->db->where('status', 1);
        $this->db->order_by('sort_order', 'asc');
        $this->db->from('documents_library_types');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    
    function update_url_code($url_code, $sid, $table_name) {
        $data_to_update = array();
        
        if($table_name == 'document_library_sub_menu') {
            $data_to_update = array('sub_url_code' => $url_code);
        } 
        
        if($table_name == 'documents_library_types') {
            $data_to_update = array('url_code' => $url_code);
        } 
        
        $this->db->where('sid', $sid);
        $this->db->update($table_name, $data_to_update);
    }
    
    function get_recource_id($code, $type, $category_id = 0, $generated_doc_segment = NULL) {
        $this->db->select('sid');
        
        if($type == 'main_menu') { // documents_library_types
            $this->db->where('url_code', $code);
            $this->db->from('documents_library_types');
        }
        
        if($type == 'sub_menu') { // document_library_sub_menu
            $this->db->where('sub_url_code', $code);
            if($category_id > 0 ) {
                $this->db->where('category_id', $category_id);
            }
            $this->db->from('document_library_sub_menu');
        }
        
        if($type == 'gen_menu') {
            echo '<br>code: '.$code;
            echo '<br>category_id: '.$category_id;
            echo '<br>generated_doc_segment: '.$generated_doc_segment;
            exit;
        }
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(empty($records_arr)) {
            return 0;
        } else {
            return $records_arr[0]['sid'];
        }
    }
    
    function get_page_content($category_id, $sid) {
//            echo '<br>category_id: '.$category_id;
//            echo '<br>sid: '.$sid;
        $this->db->select('*');
        $this->db->where('category_id', $category_id);
        $this->db->where('sid', $sid);
        $this->db->where('status', 1);
        $this->db->from('document_library_sub_menu');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
//        echo '<br><br>'.$this->db->last_query();            exit;

        if(empty($records_arr)) {
            return array();
        } else {
            $return_data = $records_arr[0];
            // check for any attachments with the sub menu
            
            $this->db->select('*');
            $this->db->where('menu_id', $sid);
            $this->db->where('status', 1);
            $this->db->from('document_library_files');

            $r_obj = $this->db->get();
            $r_arr = $r_obj->result_array();
            $r_obj->free_result();
            $return_data['attachments'] = $r_arr;
            return $return_data;
        }
    }
    
    function get_page_submenu($sid) {
        $this->db->select('*');
        $this->db->where('menu_id', $sid);
        $this->db->where('status', 1);
        $this->db->from('document_library_sub_menu');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(empty($records_arr)) {
            return 0;
        } else {
            foreach($records_arr as $key => $value) {
                $submenu_sid = $value['sid'];
//                echo $submenu_sid; exit;
                $this->db->select('*');
                $this->db->where('menu_id', $submenu_sid);
                $this->db->order_by('sort_order', 'asc');
                $this->db->from('document_library_files');

                $r_obj = $this->db->get();
                $files_data = $r_obj->result_array();
                $r_obj->free_result();
                $records_arr[$key]['attachments'] = $files_data;
//            echo '<pre>'; print_r($files_data); echo '</pre>';
            }
            return $records_arr;
        }
    }
    
    function update_document_library_files() {
        $this->db->select('sid, file_name, doc_type, file_url_code');
//        $this->db->where('doc_type', 'Generated');
        $this->db->where('file_url_code', NULL);
        $this->db->from('document_library_files');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        echo $this->db->last_query();
        
        if(!empty($records_arr)) {
            foreach ($records_arr as $update) {
                $sid = $update['sid'];
                $name = $update['file_name'];
                $file_url_code = strtolower(clean($name)).'-v'.$sid;
                $data_to_update = array('file_url_code' => $file_url_code);
                $this->db->where('sid', $sid);
                $this->db->update('document_library_files', $data_to_update);
                echo '<br>'.$this->db->last_query();
            }
        }
    }
    
    function get_generated_doc_content($file_url_code) {
        $this->db->select('sid, file_name as title, file_code, type, menu_id, country, states, federal_check, word_content as description, status, doc_type, file_url_code, parent_id, sub_menu_id, sub_heading_id');
        $this->db->where('status', 1);
        $this->db->where('file_url_code', $file_url_code);
        $this->db->from('document_library_files');
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(empty($records_arr)) {
            return array();
        } else {
            return $records_arr[0];
        }
    }
    
    // Updated on: 03-06-2019
    function search_result($key) {
        $key = preg_replace('!\s+!', ' ', $key);
        $okey = $key;
        $key = str_replace(' ', '', strtolower($key));
        //
        $search_keys = explode(' ', trim(strtolower($okey)));
        $search_key_count = count($search_keys) - 1;
        // step1: search for search keyword in subheading, submenu
        $this->db->select('sid, title, sub_url_code, description, category_id, parent_type, menu_id');
        $this->db->where('status', 1);
        $this->db->where('type', 'content');
        // $this->db->like('title', $key);
        $this->db->order_by('sid', 'ASC');
        $this->db->from('document_library_sub_menu');

        $this->db->group_start();
        $this->db->like('REPLACE(TRIM(LOWER(title)), " ", "") ', $key);
        if($search_key_count > 0){
            $this->db->or_where('LOWER(title) REGEXP "'.strtolower($okey).'"', NULL);
            for($i = $search_key_count; $i > 0 ; $i--){
                $query = strtolower($okey);
                $q2 = trim(
                substr(
                    $query, 
                    0, 
                    strpos(
                        $query, 
                        $search_keys[$i],
                        strlen($search_keys[$i])
                    )
                ));

                if($q2 != ''){
                    $this->db->or_where('LOWER(title) REGEXP "'.$q2.'"', NULL);
                    $this->db->or_where('LOWER(title) REGEXP "'.str_replace(' ', '', $q2).'"', NULL);
                }
            }
        }
        $this->db->group_end();

        // _e($this->db->get_compiled_select(), true, true);
        
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        $types = array( 1 => 'topics',
                        2 => 'laws',
                        3 => 'learning',
                        4 => 'tools',
                        5 => 'documents',
                        6 => 'hr-on-demand');
        
        $return_data = array();
//        echo $this->db->last_query().'<br>';
        if(!empty($records_arr)) {
            foreach($records_arr as $ra) {
                $sid = $ra['sid'];
                $title = $ra['title'];
                $sub_url_code = $ra['sub_url_code'];
                $description = $ra['description'];
                $category_id = $ra['category_id'];
                $parent_type = $ra['parent_type'];
                $menu_id = $ra['menu_id'];
//                echo '<br>Parent Type: '.$parent_type;
                
                if($parent_type == 'sub_menu') {
                    $return_data[] = array( 'sid' => $sid,
                                            'title' => $title,
                                            'sub_url_code' => $sub_url_code,
                                            'description' => $description,
                                            'category_id' => $category_id,
                                            'parent' => $types[$category_id]);
//                    echo '<br>sid: '.$sid.': '.$sub_url_code;
                } else {
                    $this->db->select('title, sub_url_code, description, category_id');
                    $this->db->where('sid', $menu_id);
                    $this->db->from('document_library_sub_menu');
                    $ra_obj = $this->db->get();
                    $ra_arr = $ra_obj->result_array();
                    $ra_obj->free_result();
//                    echo '<br>sid: '.$menu_id.': '.$ra_arr[0]['sub_url_code'];
                    $category_id = $ra_arr[0]['category_id'];
                    
                    $return_data[] = array( 'sid' => $menu_id,
                                            'title' => $ra_arr[0]['title'],
                                            'sub_url_code' => $ra_arr[0]['sub_url_code'],
                                            'description' => $ra_arr[0]['description'],
                                            'category_id' => $category_id,
                                            'parent' => $types[$category_id]);
                }
            }
        }
        
        // step2: search in the attachments
        $this->db->select('sid, file_name, file_url_code, type, word_content, menu_id, country, states, federal_check, status, parent_id, sub_menu_id, sub_heading_id');
        $this->db->where('status', 1);
        $this->db->like('REPLACE(TRIM(LOWER(file_name)), " ", "") ', $key);
        if($search_key_count > 0){
            $this->db->or_where('LOWER(file_name) REGEXP "'.strtolower($okey).'"', NULL);
            for($i = $search_key_count; $i > 0 ; $i--){
                $query = strtolower($okey);
                $q2 = trim(
                substr(
                    $query, 
                    0, 
                    strpos(
                        $query, 
                        $search_keys[$i],
                        strlen($search_keys[$i])
                    )
                ));
                if($q2 != ''){
                    $this->db->or_where('LOWER(file_name) REGEXP "'.$q2.'"', NULL);
                    $this->db->or_where('LOWER(file_name) REGEXP "'.str_replace(' ', '', $q2).'"', NULL);
                }
            }
        }
        // $this->db->like('file_name', $key);
        $this->db->from('document_library_files');
        
        $files_obj = $this->db->get();
        $files_arr = $files_obj->result_array();
        $files_obj->free_result();
        
        $search_result = array('content' => $return_data,
                               'files' => $files_arr);
        return $search_result;
    }
    
    function document_library_files_parent_id() {
        $this->db->select('sid, name');
        $this->db->from('documents_library_types');
        $a_obj = $this->db->get();
        $a_arr = $a_obj->result_array();
        $a_obj->free_result();
//        echo '<br> Parent Query: '.$this->db->last_query();
        foreach($a_arr as $a_value) {
            $a_sid = $a_value['sid'];
            
            $this->db->select('sid'); // sub heading
            $this->db->where('category_id', $a_sid);
            $this->db->from('document_library_sub_menu');
            $b_obj = $this->db->get();
            $b_arr = $b_obj->result_array();
            $b_obj->free_result();
            
//            echo '<br> 2nd Query: '.$this->db->last_query();
            foreach($b_arr as $b_value) {
                $b_sid = $b_value['sid'];
                
                $this->db->select('sid'); // sub heading
                $this->db->where('menu_id', $b_sid);
                $this->db->from('document_library_sub_menu');
                $menu_obj = $this->db->get();
                $menu_arr = $menu_obj->result_array();
                $menu_obj->free_result();
//                echo '<br> 3rd Query: '.$this->db->last_query();
                
                foreach($menu_arr as $menu_value) { // Step 1: check all files for this sub heading
                    $menu_id            = $menu_value['sid'];
                    $data_menu_update   = array(    'parent_id'=> $a_sid,
                                                    'sub_menu_id'=> $b_sid);

                    $this->db->where('sid', $menu_id);
                    $this->db->update('document_library_files', $data_menu_update);
                    echo '<br> Sub Menu Update: '.$this->db->last_query();
                    // Step 2: check all files for sub menu for current sub heading
                    $this->db->select('sid'); // sub menu
                    $this->db->where('menu_id', $menu_id);
//                    $this->db->where('parent_id', 0);
                    $this->db->from('document_library_files');
                    $heading_obj = $this->db->get();
                    $heading_arr = $heading_obj->result_array();
                    $heading_obj->free_result();
                    echo '<br> 4th Query: '.$this->db->last_query();
                    if(!empty($heading_arr)){
                        foreach($heading_arr as $heading_value) {
                            $heading_id = $heading_value['sid'];
                            $data_menu_update   = array(    'parent_id' => $a_sid,
                                                            'sub_menu_id' => $b_sid,
                                                            'sub_heading_id' => $menu_id);

                            $this->db->where('sid', $heading_id);
                            $this->db->update('document_library_files', $data_menu_update);
                            echo '<br> Sub Heading Update: '.$this->db->last_query();
                        }
                    }
                }
            }
        }
    }
    
    function get_submenu_urlcodes() {
        $this->db->select('sid, sub_url_code');
        $this->db->where('status', 1);
        $this->db->where('sub_url_code !=', NULL);
        $this->db->from('document_library_sub_menu');
        
        $data_obj = $this->db->get();
        $data_arr = $data_obj->result_array();
        $data_obj->free_result();
        $return_data = array();
        
        foreach($data_arr as $dr) {
            $sid = $dr['sid'];
            $sub_url_code = $dr['sub_url_code'];
            
            $return_data[$sid] = $sub_url_code;
        }
        
       return $return_data;
    }

    function get_library_file_by_sid($file_id){
        return $this->db->where('sid',$file_id)->get('document_library_files')->result_array();
    }

    function insert_document_record($data_to_insert) {
        $this->db->insert('documents_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function save_doc_history($data) {
        $this->db->insert('documents_management_history', $data);
        return $this->db->insert_id();
    }

    function record_copied_document($data){
        $this->db->insert('document_library_copy_history', $data);
        return $this->db->insert_id();
    }

    function get_copied_record($page_sid,$company_sid){
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_library_sid', $page_sid);
        $records_arr = $this->db->get('document_library_copy_history')->result_array();
        return $records_arr;
    }
}
