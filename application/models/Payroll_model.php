<?php

class Payroll_model extends CI_Model{
    //
    private $tables;

    /**
     * 
     */
    function __construct(){
        $this->tables = []; 
        $this->tables['PC'] = 'payroll_companies'; 
        $this->tables['PCE'] = 'payroll_employees'; 
    }
    
    /**
     * 
     */
    function AddCompany($ia){
        //
        $this->db->insert(
            $this->tables['PC'],
            $ia
        );
        //
        return $this->db->insert_id();
    }
    
    /**
     * 
     */
    function AddEmployeeCompany($ia){
        //
        $this->db->insert(
            $this->tables['PCE'],
            $ia
        );
        //
        return $this->db->insert_id();
    }
   
    /**
     * 
     */
    function GetCompany($companyId, $columns){
        //
        $query = 
        $this->db
        ->where('company_sid', $companyId)
        ->select($columns)
        ->get($this->tables['PC']);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }
    
    /**
     * 
     */
    function EmployeeAlreadyAddedToGusto($employeeId, $columns){
        //
        $query = 
        $this->db
        ->select($columns)
        ->where('employee_sid', $employeeId)
        ->get($this->tables['PCE']);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }
    
    /**
     * 
     */
    function UpdateToken($array, $where){
        //
        $this->db
        ->where($where)
        ->update($this->tables['PC'], $array);
    }
}