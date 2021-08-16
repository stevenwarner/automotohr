<?php

class Email_manager_model extends CI_Model{
  
    /**
     * 
     */
    function __construct(){ 
        parent::__construct(); 
    }

    //
    function GetEmployeeDetails(
        $employeeId,
        $columns = '*'
    ){
        //
        $query = $this->db
        ->select($columns)
        ->where('sid', $employeeId)
        ->get();
        //
        $r = $query->row_array();
        $query = $query->row_array();
        //
        return $r;
    }
    
}