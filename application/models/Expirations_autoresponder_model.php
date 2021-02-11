<?php
class Expirations_autoresponder_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function GetAllCompanies(){
        $this->db->select('*');
        $this->db->where('parent_sid', '');
        $this->db->where('career_page_type', 'standard_career_site');
        $result = $this->db->get('users')->result_array();

        if(!empty($result)){
            return $result;
        }else{
            return array();
        }
    }
}