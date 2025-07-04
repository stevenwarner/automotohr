<?php

class Employee_model extends CI_Model{
    //
    private $U;
    private $EBA;

    /**
     * 
     */
    function __construct(){
        $this->U = 'users'; 
        $this->PE = 'payroll_employees'; 
        $this->EBA = 'bank_account_details'; 
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

    /**
     * 
     */
    function GetCompanyEmployees(
        $companyId, 
        $columns = '*',
        $whereArray = []
    ){
        //
        $whereArray = !empty($whereArray) ? $whereArray : ["{$this->U}.active" => 1, "{$this->U}.terminated_status" => 0];
        //
        $redo = false;
        //
        if($columns === true){
            //
            $redo = true;
            //
            $columns = [];
            $columns[] = "{$this->U}.sid";
            $columns[] = "{$this->U}.first_name";
            $columns[] = "{$this->U}.last_name";
            $columns[] = "{$this->U}.email";
            $columns[] = "{$this->U}.joined_at";
            $columns[] = "{$this->U}.registration_date";
            $columns[] = "{$this->U}.ssn";
            $columns[] = "{$this->U}.timezone";
            $columns[] = "company.timezone as company_timezone";
            $columns[] = "{$this->U}.dob";
            $columns[] = "{$this->U}.profile_picture";
            $columns[] = "{$this->U}.access_level";
            $columns[] = "{$this->U}.access_level_plus";
            $columns[] = "{$this->U}.user_shift_hours";
            $columns[] = "{$this->U}.user_shift_minutes";
            $columns[] = "{$this->U}.is_executive_admin";
            $columns[] = "{$this->U}.job_title";
            $columns[] = "{$this->U}.pay_plan_flag";
            $columns[] = "{$this->U}.full_employment_application";
            $columns[] = "{$this->U}.on_payroll";
            $columns[] = "{$this->PE}.onboard_completed";
        }
        //
        $query = 
        $this->db
        ->select($columns)
        ->join("{$this->U} as company", "{$this->U}.parent_sid = company.sid", 'inner')
        ->join("{$this->PE}", "{$this->PE}.employee_sid = users.sid", 'left')
        ->where("{$this->U}.parent_sid", $companyId)
        ->where($whereArray)
        ->order_by("{$this->U}.first_name", 'asc')
        ->get($this->U);
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        if($redo && !empty($records)){
            //
            $newRecords = [];
            //
            $updateArray = [];
            //
            foreach($records as $record){
                //
                $newRecords[$record['sid']] = [
                    'sid' => $record['sid'],
                    'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'first_name' => ucwords($record['first_name']),
                    'last_name' => ucwords($record['last_name']),
                    'name' => ucwords($record['first_name'].' '.$record['last_name']),
                    'role' => remakeEmployeeName($record, false),
                    'plus' => $record['access_level_plus'],
                    'email' => $record['email'],
                    'image' => getImageURL($record['profile_picture']),
                    'joined_on' => $record['joined_at'],
                    'ssn' => $record['ssn'],
                    'dob' => $record['dob'],
                    'shift_hours' => $record['user_shift_hours'],
                    'shift_minutes' => $record['user_shift_minutes'],
                    'on_payroll' => $record['on_payroll'],
                    'onboard_completed' => $record['onboard_completed'],
                ];
                //
                if(!empty($record['timezone'])){
                    $newRecords[$record['sid']]['timezone'] = $record['timezone'];
                } else if(!empty($record['company_timezone'])){
                    $newRecords[$record['sid']]['timezone'] = $record['company_timezone'];
                }
                //
                if(!empty($record['full_employment_application'])){
                    //
                    $fef = unserialize($record['full_employment_application']);
                    //
                    if(empty($newRecords[$record['sid']]['ssn']) && isset($fef['TextBoxSSN'])){
                        $newRecords[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                        //
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                    }
                    //
                    if(empty($newRecords[$record['sid']]['dob']) && isset($fef['TextBoxDOB'])){
                        $newRecords[$record['sid']]['dob'] = DateTime::createfromformat('m-d-Y', $fef['TextBoxDOB'])->format('Y-m-d');
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['dob'] = $newRecords[$record['sid']]['dob'];
                    }
                }
            }
            //
            if(!empty($updateArray)){
                $this->db->update_batch($this->U, $updateArray, 'sid');
            }
            //
            $records = $newRecords;
            //
            unset($newRecords);
        }
        //
        return $records;
    }

    /**
     * 
     */
    function Update(
        $arr,
        $where
    ){
        //
        $this->db
        ->where($where)
        ->update($this->U, $arr);
    }


    /**
     * 
     */
    function GetBankAccounts(
        $employeeId,
        $columns = '*'
    ){
        //
        $query = 
        $this->db
        ->select($columns)
        ->where('users_type', 'employee')
        ->where('users_sid', $employeeId)
        ->get($this->EBA);
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
    function GetEmployeeDetailWithPayroll(
        $employeeId, 
        $columns = '*',
        $table = 'payroll_employees'
    ){
        $query = 
        $this->db
        ->select($columns)
        ->join($table, "users.sid = {$table}.employee_sid", 'left')
        ->where("{$this->U}.sid", $employeeId)
        ->get($this->U);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $record;
    }


    /**
     * Get all the payroll employees
     * 
     * @version 1.0
     * @param number       $companyId
     * @param string|array $columns
     * @return
     */
    function GetPayrollEmployees(
        $companyId, 
        $columns = '*'
    ){
        $query = 
        $this->db
        ->select($columns, false)
        ->join('payroll_employees', "users.sid = payroll_employees.employee_sid", 'left')
        ->where("{$this->U}.parent_sid", $companyId)
        ->where("{$this->U}.active", 1)
        ->where("{$this->U}.terminated_status", 0)
        ->order_by("{$this->U}.first_name", "ASC")
        ->get($this->U);
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        return $records;
    }

    /**
     * Deletes payroll employee
     * 
     * @param number $employeeId
     */
    public function DeletePayrollEmployee($employeeId){
        //
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employees');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_address');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_federal_tax');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_jobs');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_state_tax');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_payment_method');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_bank_accounts');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employees_forms');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employees_pay_stubs');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_benefits');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_benefits_history');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_garnishments');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_garnishments_history');
        $this->db->where('employee_sid', $employeeId)->delete('payroll_employee_jobs_history');
    }

    /**
     * 
     */
    function GetSingleEmployee(
        $employeeId, 
        $columns = '*',
        $redo = true
    ){
        //
        $whereArray = !empty($whereArray) ? $whereArray : ["{$this->U}.active" => 1, "{$this->U}.terminated_status" => 0];
        //
        if($columns === true){
            //
            $columns = [];
            $columns[] = "{$this->U}.sid";
            $columns[] = "{$this->U}.first_name";
            $columns[] = "{$this->U}.last_name";
            $columns[] = "{$this->U}.email";
            $columns[] = "{$this->U}.joined_at";
            $columns[] = "{$this->U}.registration_date";
            $columns[] = "{$this->U}.ssn";
            $columns[] = "{$this->U}.timezone";
            $columns[] = "company.timezone as company_timezone";
            $columns[] = "{$this->U}.dob";
            $columns[] = "{$this->U}.user_shift_hours";
            $columns[] = "{$this->U}.user_shift_minutes";
            $columns[] = "{$this->U}.profile_picture";
            $columns[] = "{$this->U}.access_level";
            $columns[] = "{$this->U}.access_level_plus";
            $columns[] = "{$this->U}.is_executive_admin";
            $columns[] = "{$this->U}.job_title";
            $columns[] = "{$this->U}.pay_plan_flag";
            $columns[] = "{$this->U}.full_employment_application";
            $columns[] = "{$this->U}.on_payroll";
            $columns[] = "{$this->PE}.onboard_completed";
        }
        //
        $query = 
        $this->db
        ->select($columns)
        ->join("{$this->U} as company", "{$this->U}.parent_sid = company.sid", 'inner')
        ->join("{$this->PE}", "{$this->PE}.employee_sid = users.sid", 'left')
        ->where("{$this->U}.sid", $employeeId)
        ->where($whereArray)
        ->order_by("{$this->U}.first_name", 'asc')
        ->get($this->U);
        //
        $record = $query->row_array();
        //
        $query = $query->free_result();
        //
        if($redo && !empty($record)){
            //
            $newRecords = [];
            //
            $updateArray = [];
            //
            $newRecords[$record['sid']] = [
                'sid' => $record['sid'],
                'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                'first_name' => ucwords($record['first_name']),
                'last_name' => ucwords($record['last_name']),
                'name' => ucwords($record['first_name'].' '.$record['last_name']),
                'role' => remakeEmployeeName($record, false),
                'plus' => $record['access_level_plus'],
                'email' => $record['email'],
                'image' => getImageURL($record['profile_picture']),
                'joined_on' => $record['joined_at'],
                'ssn' => $record['ssn'],
                'dob' => $record['dob'],
                'shift_hours' => $record['user_shift_hours'],
                'shift_minutes' => $record['user_shift_minutes'],
                'on_payroll' => $record['on_payroll'],
                'onboard_completed' => $record['onboard_completed'],
            ];
            //
            if(!empty($record['timezone'])){
                $newRecords[$record['sid']]['timezone'] = $record['timezone'];
            } else if(!empty($record['company_timezone'])){
                $newRecords[$record['sid']]['timezone'] = $record['company_timezone'];
            }
            //
            if(!empty($record['full_employment_application'])){
                //
                $fef = unserialize($record['full_employment_application']);
                //
                if(empty($newRecords[$record['sid']]['ssn']) && isset($fef['TextBoxSSN'])){
                    $newRecords[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                    //
                    $updateArray[$record['sid']]['sid'] = $record['sid'];
                    $updateArray[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                }
                //
                if(empty($newRecords[$record['sid']]['dob']) && isset($fef['TextBoxDOB'])){
                    $newRecords[$record['sid']]['dob'] = DateTime::createfromformat('m-d-Y', $fef['TextBoxDOB'])->format('Y-m-d');
                    $updateArray[$record['sid']]['sid'] = $record['sid'];
                    $updateArray[$record['sid']]['dob'] = $newRecords[$record['sid']]['dob'];
                }
            }
            //
            if(!empty($updateArray)){
                $this->db->update_batch($this->U, $updateArray, 'sid');
            }
        }
        //
        return $newRecords;
    }
}