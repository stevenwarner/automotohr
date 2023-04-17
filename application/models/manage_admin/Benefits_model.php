<?php

class benefits_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    public function InsertBenifit($insertData)
    {

        $this->db->insert('default_benifits', $insertData);
    }

    public function UpdateBenifit($sid, $insertData)
    {
        $this->db->where('sid', $sid);
        $this->db->update('default_benifits', $insertData);
    }


    public function SaveBenifit($sid, $insertData)
    {
        if ($sid == null) {
            $this->InsertBenifit($insertData);
        } else {
            $this->UpdateBenifit($sid, $insertData);
        }
    }
  
    //
    public function GetAllBenifits()
    {
        $this->db->order_by('sid', 'Desc');
        return $this->db->get('default_benifits')->result_array();
    }

  
    public function GetBenifitById($sid)
    {
        $this->db->where('sid', $sid);
        return $this->db->get('default_benifits')->row_array();
    }
   
   
}