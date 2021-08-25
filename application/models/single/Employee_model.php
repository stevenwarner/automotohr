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
        $columns = '*'
    ){
        //
        $redo = false;
        //
        if($columns === true){
            //
            $redo = true;
            //
            $columns = [];
            $columns[] = 'sid';
            $columns[] = 'first_name';
            $columns[] = 'last_name';
            $columns[] = 'email';
            $columns[] = 'joined_at';
            $columns[] = 'registration_date';
            $columns[] = 'ssn';
            $columns[] = 'dob';
            $columns[] = 'profile_picture';
            $columns[] = 'access_level';
            $columns[] = 'access_level_plus';
            $columns[] = 'is_executive_admin';
            $columns[] = 'job_title';
            $columns[] = 'pay_plan_flag';
            $columns[] = 'full_employment_application';
            $columns[] = 'on_payroll';
        }
        //
        $query = 
        $this->db
        ->select($columns)
        ->where('parent_sid', $companyId)
        ->where('active', 1)
        ->where('terminated_status', 0)
        ->order_by('first_name', 'asc')
        ->get($this->U);
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        if($redo){
            //
            $newRecords = [];
            //
            $updateArray = [];
            //
            foreach($records as $record){
                //
                $newRecords[$record['sid']] = [
                    'Id' => $record['sid'],
                    'FirstName' => ucwords($record['first_name']),
                    'LastName' => ucwords($record['last_name']),
                    'Name' => ucwords($record['first_name'].' '.$record['last_name']),
                    'Role' => remakeEmployeeName($record, false),
                    'Plus' => $record['access_level_plus'],
                    'Email' => $record['email'],
                    'Image' => getImageURL($record['profile_picture']),
                    'JoinedOn' => $record['joined_at'],
                    'SSN' => $record['ssn'],
                    'DOB' => $record['dob'],
                    'OnPayroll' => $record['on_payroll']
                ];
                //
                if(!empty($record['full_employment_application'])){
                    //
                    $fef = unserialize($record['full_employment_application']);
                    //
                    if(empty($newRecords[$record['sid']]['SSN']) && isset($fef['TextBoxSSN'])){
                        $newRecords[$record['sid']]['SSN'] = $fef['TextBoxSSN'];
                        //
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                    }
                    //
                    if(empty($newRecords[$record['sid']]['DOB']) && isset($fef['TextBoxDOB'])){
                        $newRecords[$record['sid']]['DOB'] = DateTime::createfromformat('m-d-Y', $fef['TextBoxDOB'])->format('Y-m-d');
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['dob'] = $newRecords[$record['sid']]['DOB'];
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
}