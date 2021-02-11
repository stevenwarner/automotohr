<?php

class Photo_gallery_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function save_attachment( $data ){
        return $this->db->insert('photo_gallery_attachments', $data);
    }
    
    function get_pictures($company_sid){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('photo_gallery_attachments')->result_array();
    }
    
    function delete_attachment($sid){
        $this->db->where('sid', $sid);
        return $this->db->delete('photo_gallery_attachments');
    }
}
?>