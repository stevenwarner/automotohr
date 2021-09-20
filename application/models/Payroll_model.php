<?php

class Payroll_model extends CI_Model{
    //
    private $tables;

    /**
     * 
     */
    function __construct(){
        $this->tables = []; 
        $this->tables['P'] = 'payrolls'; 
        $this->tables['PH'] = 'payroll_history'; 
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

    /**
     * 
     */
    function UpdatePCE($array, $where){
        //
        $this->db
        ->where($where)
        ->update($this->tables['PCE'], $array);
    }

    /**
     * 
     */
    function CheckEINNumber($ein, $companyId){
        //
        return $this->db
        ->where('ssn', $ein)
        ->where('sid <>', $companyId)
        ->count_all_results($this->tables['U']);
    }


    /**
     * 
     */
    function CheckAndInsertPayroll(
        $companyId,
        $employerId,
        $payrollUid,
        $payroll
    ){
        //
        if(empty($payroll['version'])){
            return true;
        }
        //
        $isNew = false;
        $doAdd = true;
        //
        $date = date('Y-m-d H:i:s', strtotime('now'));
        // Check if the payroll already
        // been added
        if(
            !$this->db
            ->where('payroll_uid', $payrollUid)
            ->count_all_results($this->tables['P'])
        ){
            // Let's insert the payroll
            $this->db
            ->insert(
                $this->tables['P'], [
                    'company_sid' => $companyId,
                    'payroll_uid' => $payrollUid,
                    'payroll_id' => $payroll['payroll_id'],
                    'start_date' => $payroll['pay_period']['start_date'],
                    'end_date' => $payroll['pay_period']['end_date'],
                    'check_date' => $payroll['check_date'],
                    'deadline_date' => $payroll['payroll_deadline'],
                    'is_processed' => 0,
                    'created_by' => $employerId,
                    'created_at' => $date,
                    'updated_at' => $date
                ]
            );
            //
            $isNew = true;
        }
        //
        if(!$isNew){
            // Get the last history
            $historyVersion = $this->GetPayrollHistory($payroll['payroll_id'], ['version'])['version'];
            //
            if($historyVersion == $payroll['version']){
                $doAdd = false;
            }
        }
        //
        if(!$doAdd){
            return false;
        }
        // Let's add a history
        $this->db
        ->insert(
            $this->tables['PH'], [
                'payroll_id' => $payroll['payroll_id'],
                'version' => $payroll['version'],
                'created_by' => $employerId,
                'content' => json_encode($payroll),
                'created_at' => $date
            ]
        );
    }


    /**
     * 
     */
    function GetPayrollHistory(
        $payrollId, 
        $columns = '*'
    ){
        //
        $query =
        $this->db
        ->select($columns)
        ->where('payroll_id', $payrollId)
        ->order_by('sid', 'desc')
        ->get($this->tables['PH']);
        //
        $record = $query->row_array();
        $query = $query->free_result();
        //
        return $record;
    }

    /**
     * 
     */
    function isEmployeeOnPayroll($employeeId){
        //
        return $this->db
        ->where('on_payroll', 1)
        ->where('sid', $employeeId)
        ->count_all_results('users');
    }
}