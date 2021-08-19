<?php

class Employee_model extends CI_Model{
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
    function GetEmployeeDetails(
        $employeeId, 
        $columns = '*'
    ){
        //
        $query = 
        $this->db
        ->select($columns)
        ->where('sid', $employeeId)
        ->get($this->U);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }
}