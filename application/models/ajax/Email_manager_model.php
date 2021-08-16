<?php

class Email_manager_model extends CI_Model{

    //
    private $U = 'users';
  
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
        ->get($this->U);
        //
        $r = $query->row_array();
        $query = $query->row_array();
        //
        return $r;
    }
    
}