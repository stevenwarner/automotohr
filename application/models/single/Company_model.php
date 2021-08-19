<?php

class Company_model extends CI_Model{
    //
    private $U;

    /**
     * 
     */
    function __construct(){
        $this->U = 'users'; 
    }
    
    /**
     * 
     */
    function GetCompanyDetails(
        $companyId, 
        $columns = '*'
    ){
        //
        $query = 
        $this->db
        ->select($columns)
        ->where('sid', $companyId)
        ->get($this->U);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }
}