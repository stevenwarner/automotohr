<?php

class Company_model extends CI_Model{
    //
    private $U;
    private $CBA;

    /**
     * 
     */
    function __construct(){
        $this->U = 'users'; 
        $this->CBA = 'company_bank_accounts'; 
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

    /**
     * 
     */
    function GetBankAccounts(
        $companyId, 
        $columns = '*'
    ){
        //
        $query = 
        $this->db
        ->select($columns)
        ->from($this->CBA)
        ->join("{$this->U}", "{$this->U}.sid = {$this->CBA}.created_by", "inner")
        ->join("{$this->U} as u", "u.sid = {$this->CBA}.created_by", "inner")
        ->where("{$this->CBA}.company_sid", $companyId)
        ->where("{$this->CBA}.is_deleted", 0)
        ->order_by("{$this->CBA}.sid", 'DESC')
        ->get();
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        return $records;
    }
    
    /**
     * 
     */
    function GetSingleBankAccounts(
        $companyId, 
        $bankAccountId, 
        $columns = '*'
    ){
        //
        $query = 
        $this->db
        ->select($columns)
        ->from($this->CBA)
        ->join("{$this->U}", "{$this->U}.sid = {$this->CBA}.created_by", "inner")
        ->join("{$this->U} as u", "u.sid = {$this->CBA}.created_by", "inner")
        ->where("{$this->CBA}.company_sid", $companyId)
        ->where("{$this->CBA}.sid", $bankAccountId)
        ->get();
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
    function GetPayrollBankAccount(
        $companyId,
        $columns = '*',
        $where = false
    ){
        //
         
        $this->db
        ->select($columns)
        ->from($this->CBA)
        ->where("{$this->CBA}.company_sid", $companyId)
        ->where("{$this->CBA}.use_for_payroll", 1)
        ->order_by("{$this->CBA}.sid", 'DESC');
        //
        if($where){
            $this->db->where($where);
        }
        $query = $this->db->get();
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
    function AddBankAccount($array){
        $this->db->insert($this->CBA, $array);
        return $this->db->insert_id();
    }
    
    /**
     * 
     */
    function UpdateBankAccount($array, $where){
        $this->db
        ->where($where)
        ->update($this->CBA, $array);
    }

    /***
     * 
     */
    function Update($array, $where){
        $this->db->where($where)->update($this->CBA, $array);
    }

    /**
     * 
     */
    function DeleteBankAccounts($ids, $employerId){
        $this->db
        ->where_in('sid', $ids)
        ->update($this->CBA, [
            'is_deleted' => 1,
            'last_updated_by' => $employerId
        ]);
    }
}