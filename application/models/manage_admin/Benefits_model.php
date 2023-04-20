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



    public function GetAllCompanies()
    {
        $this->db->select('sid,CompanyName');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $return = $this->db->get('users')->result_array();
        return $return;
    }


    //
    public function GetAllCompanyBenifits($comapnySid)
    {
        $this->db->where('company_sid', $comapnySid);
        $this->db->order_by('sid', 'Desc');
        return $this->db->get('company_benefits')->result_array();
    }


    //
    public function SaveCompanyBenefit($sid, $insertData)
    {
        if ($sid == null) {
            $this->InsertCompanyBenefit($insertData);
        } else {
            $this->UpdateCompanyBenefit($sid, $insertData);
        }
    }


    //
    public function InsertCompanyBenefit($insertData)
    {

        $this->db->insert('company_benefits', $insertData);
    }
    //
    public function UpdateCompanyBenefit($sid, $insertData)
    {
        $this->db->where('sid', $sid);
        $this->db->update('company_benefits', $insertData);
    }


    //
    public function get_default_benefits_category()
    {

        $this->db->select('distinct(category)');
        $this->db->order_by('category', 'ASC');
        return $this->db->get('default_benifits')->result_array();
    }
}
