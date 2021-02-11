<?php

/**
 * Created by PhpStorm.
 * User: Hamid Ashraf
 * Date: 2/25/2016
 * Time: 9:55 AM
 */
class testimonials_model extends CI_Model
{
    public $tableName = 'portal_testimonials';

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }


    public function GetTestimonial($sid)
    {
        $this->db->where('sid', $sid);
        $myReturn = $this->db->get($this->tableName)->result_array();

        if (!empty($myReturn)) {
            return $myReturn[0];
        } else {
            return array();
        }

    }

    public function GetAll(){
        return $this->db->get($this->tableName)->result_array();
    }

    public function GetAllActive($company_id){
        $this->db->where('status', '1');
        $this->db->where('company_id', $company_id);
        return $this->db->get($this->tableName)->result_array();
    }


    public function GetAllCompanySpecific($companyId){
        $this->db->where('company_id', $companyId);
        $myReturn = $this->db->get($this->tableName)->result_array();
        return $myReturn;
    }

    public function GetAllLimitOffset($limit, $offset){
        return $this->db->get($this->tableName, $limit, $offset)->result_array();
    }

    public function GetSingle($sid){
        $this->db->where('sid', $sid);
        return $this->db->get($this->tableName)->result_array();
    }


    public function GetAllTestimonialIds($company_id){

    }


    public function GetNextTestimonialId($company_id, $sid){
        $data = $this->db->query("SELECT `sid` FROM `portal_testimonials` WHERE sid > $sid and `company_id` = $company_id ORDER BY `sid` ASC LIMIT 1");
        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0]['sid'];
        }else{
            return '0';
        }
    }

    public function GetPreviousTestimonialId($company_id, $sid){
        $data = $this->db->query("SELECT `sid` FROM `portal_testimonials` WHERE sid < $sid and `company_id` = $company_id ORDER BY `sid` DESC LIMIT 1");
        if ($data->num_rows() > 0) {
            $data = $data->result_array();
            return $data[0]['sid'];
        }else{
            return '0';
        }
    }

}