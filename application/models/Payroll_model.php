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
        $this->tables['U'] = 'users'; 
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

    //
    /**
     * 
     */
    function UpdateCompanyEIN($companyId, $array){
        //
        $this->db
        ->where('sid', $companyId)
        ->update($this->tables['U'], $array);
    }

    //
    function GetCompaniesWithGusto(){
        //
        $query = 
        $this->db
        ->select("
            {$this->tables['U']}.sid,
            {$this->tables['U']}.CompanyName,
            {$this->tables['U']}.ssn,
            {$this->tables['PC']}.gusto_company_uid,
            {$this->tables['PC']}.access_token,
            {$this->tables['PC']}.refresh_token,
            {$this->tables['PC']}.old_access_token,
            {$this->tables['PC']}.old_refresh_token,
            {$this->tables['PC']}.is_active,
            {$this->tables['PC']}.updated_at,
            {$this->tables['PC']}.created_at
        ")
        ->from($this->tables['U'])
        ->join("{$this->tables['PC']}", "{$this->tables['PC']}.company_sid = {$this->tables['U']}.sid", 'left')
        ->where("{$this->tables['U']}.parent_sid", 0)
        ->where("{$this->tables['U']}.active", 1)
        ->order_by("{$this->tables['U']}.CompanyName")
        ->get();
        //
        $companies = $query->result_array();
        $query = $query->free_result();
        //
        return $companies;
    }


    /**
     * 
     */
    function UpdatePC($array, $where){
        //
        $this->db
        ->where($where)
        ->update($this->tables['PC'], $array);
    }
}