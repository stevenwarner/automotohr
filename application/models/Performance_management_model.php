<?php

class Performance_management_model extends CI_Model{
    // 
    private $U = 'users';
    private $DM = 'departments_management';
    private $DTM = 'departments_team_management';
    private $DE2T = 'departments_employee_2_team';
    private $PMT = 'performance_management_templates';
    private $PMCT = 'performance_management_company_templates';
    private $R = 'pm_reviews';
    //
    private $DbDateFormat = 'Y-m-d H:i:s';
    //
    private $DbDateFormatWithoutTime = 'Y-m-d';
    //
    private $SiteDateFormat = 'M d Y, D H:i:s';
    /**
     * 
     */
    function __construct(){ parent::__construct(); }

    /**
     * Get company all active employees
     * 
     * @param  Integer $CompanyId
     * @return Array
     */
    function GetAllEmployees($CompanyId){
        //
        $a = $this->db
        ->select("
            {$this->U}.sid,
            {$this->U}.first_name,
            {$this->U}.last_name,
            {$this->U}.email,
            {$this->U}.PhoneNumber,
            {$this->U}.job_title,
            {$this->U}.dob,
            IF({$this->U}.joined_at = null, {$this->U}.registration_date, {$this->U}.joined_at) as joined_at,
            {$this->U}.profile_picture,
            {$this->U}.employee_type,
            {$this->U}.access_level,
            {$this->U}.access_level_plus,
            {$this->U}.pay_plan_flag,
            {$this->U}.is_executive_admin
        ")
        ->where("{$this->U}.parent_sid", $CompanyId)
        ->where("{$this->U}.active", 1)
        ->where("{$this->U}.terminated_status", 0)
        ->order_by("{$this->U}.first_name", "ASC")
        ->get($this->U);
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        if(!empty($b)){
            //
            $r = [];
            //
            foreach($b as $v){
                $t = [
                    'Id' => $v['sid'],
                    'Name' => ucwords($v['first_name'].' '.$v['last_name']),
                    'BasicRole' => $v['access_level'],
                    'Role' => trim(remakeEmployeeName($v, false)),
                    'Image' => AWS_S3_BUCKET_URL. (empty($v['profile_picture']) ? 'test.png' : $v['profile_picture']),
                    'Email' => strtolower($v['email']),
                    'EmploymentType' => strtolower($v['employee_type']),
                    'JobTitle' => ucwords(strtolower($v['job_title'])),
                    'Phone' => $v['PhoneNumber'],
                    'DOB' => empty($v['dob']) || $v['dob'] == '0000-00-00' ? '' : DateTime::createfromformat($this->DbDateFormatWithoutTime, $v['dob'])->format($this->DbDateFormatWithoutTime),
                    'JoinedDate' => empty($v['joined_at']) ? '' : DateTime::createfromformat($this->DbDateFormat, $v['joined_at'])->format($this->DbDateFormatWithoutTime)
                ];

                //
                $r[] = $this->employeeDT($v['sid'], $t);
            }
            //
            $b = $r;
        }
        //
        return $b;
    }

    /**
     * 
     */
    function GetMyDepartmentAndTeams($CompanyId, $EmployeeId){
        $a =
        $this->db
        ->select("
            {$this->DTM}.name as team_name,
            {$this->DM}.name as department_name
        ")
        ->join("{$this->DTM}", "{$this->DTM}.sid = {$this->DE2T}.team_sid")
        ->join("{$this->DM}", "{$this->DM}.sid = {$this->DTM}.department_sid")
        ->where("{$this->DM}.status", 1)
        ->where("{$this->DM}.is_deleted", 0)
        ->where("{$this->DTM}.status", 1)
        ->where("{$this->DTM}.is_deleted", 0)
        ->where("{$this->DE2T}.employee_sid", $EmployeeId)
        ->where("{$this->DTM}.company_sid", $CompanyId)
        ->where("{$this->DM}.company_sid", $CompanyId)
        ->get("{$this->DE2T}");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        return $b;
    }
    
    /**
     * 
     */
    function GetCompanyDepartmentAndTeams($CompanyId){
        $a =
        $this->db
        ->select("
            {$this->DTM}.sid as team_id,
            {$this->DTM}.name as team_name,
            {$this->DM}.sid as department_id,
            {$this->DM}.name as department_name,
            {$this->DE2T}.employee_sid
        ")
        ->join("{$this->DTM}", "{$this->DTM}.sid = {$this->DE2T}.team_sid")
        ->join("{$this->DM}", "{$this->DM}.sid = {$this->DTM}.department_sid")
        ->where("{$this->DM}.status", 1)
        ->where("{$this->DM}.is_deleted", 0)
        ->where("{$this->DTM}.status", 1)
        ->where("{$this->DTM}.is_deleted", 0)
        ->where("{$this->DTM}.company_sid", $CompanyId)
        ->where("{$this->DM}.company_sid", $CompanyId)
        ->order_by("{$this->DM}.name", "ASC")
        ->order_by("{$this->DTM}.name", "ASC")
        ->get("{$this->DE2T}");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        if(!empty($b)){
            //
            $r = [];
            $r['Teams'] = [];
            $r['Departments'] = [];
            //
            foreach($b as $dt){
                //
                if(!isset($r['Teams'][$dt['team_id']])){
                    //
                    $r['Teams'][$dt['team_id']] = [
                        "Id" => $dt['team_id'],
                        "Name" => $dt['team_name'],
                        "DepartmentId" => $dt['department_id'],
                        "DepartmentName" => $dt['department_name'],
                        "EmployeeIds" => []
                    ];
                }
                //
                $r['Teams'][$dt['team_id']]['EmployeeIds'][] = $dt['employee_sid'];
                //
                if(!isset($r['Departments'][$dt['department_id']])){
                    //
                    $r['Departments'][$dt['department_id']] = [
                        "Id" => $dt['department_id'],
                        "Name" => $dt['department_name'],
                        "EmployeeIds" => []
                    ];
                }
                //
                $r['Departments'][$dt['department_id']]['EmployeeIds'][] = $dt['employee_sid'];
            }
            //
            $b = $r;
        }
        //
        return $b;
    }

    /**
     * Get company templates
     * 
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * 
     * @return Array
     */
    function GetCompanyTemplates(
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        //
        $a = $this->db->get($this->PMT);
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        return $b;        
    }

    /**
     * Get personal templates
     * 
     * @employee Mubashir Ahmed
     * @date     02/09/2021
     * 
     * @param Integer      $companyId
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * @param Integer      $page 
     *                     Default is '0'
     * @param Integer      $limit 
     *                     Default is '100'
     * 
     * @return Array
     */
    function GetPersonalTemplates(
        $companyId, 
        $columns = '*', 
        $archived = 0,
        $page = 0,
        $limit = 100
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        // For pagination
        if($page != 0){
            $inset = 0;
            $offset = 0;
            $this->db->limit($inset, $offset);
        }
        //
        $a = $this->db->get($this->PMCT);
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        if($page == 1){
            return [
                'Records' => $b,
                'Count' => $this->db->where('is_archived', $archived)->count_all_results($this->PMCT)
            ];
        }
        //
        return $b;        
    }
    
    /**
     * Get single company templates
     * 
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * 
     * @return Array
     */
    function GetSingleCompanyTemplates(
        $id,
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $id)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        //
        $a = $this->db->get($this->PMT);
        $b = $a->row_array();
        // Free result
        $a->free_result();
        //
        return $b;        
    }

    /**
     * Get single personal templates
     * 
     * @employee Mubashir Ahmed
     * @date     02/09/2021
     * 
     * @param Integer      $id
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * @return Array
     */
    function GetSinglePersonalTemplates(
        $id, 
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->where('sid', $id)
        ->order_by('name', 'ASC');
        //
        $a = $this->db->get($this->PMCT);
        $b = $a->row_array();
        // Free result
        $a->free_result();
        //
        return $b;        
    }

    /**
     * 
     */
    function GetReviewRowById($reviewId, $company_sid){
        //
        if($reviewId == 0){
            return [];
        }
        //
        $query = $this->db
        ->select("
            sid as reviewId,
            review_title as title,
            description,
            frequency as frequency_type,
            review_start_date as start_date,
            review_end_date as end_date,
            visibility_employees as employees,
            repeat_after as recur_value,
            repeat_type as recur_type,
            repeat_type as recur_type,
            review_due as review_due_value,
            review_due_type,
            repeat_review,
            review_runs as custom_runs,
            visibility_roles as roles,
            visibility_departments as departments,
            visibility_teams as teams,
            included_employees as included,
            excluded_employees as excluded,
            reviewers,
        ")
        ->where('sid', $reviewId)
        ->where('company_sid', $company_sid)
        ->get($this->R);
        //
        $review = $query->row_array();
        //
        $query->free_result();
        //
        return $review;
    }


    /**
     * 
     */
    function InsertReview($data){
        $this->db->insert($this->R, $data);
        return $this->db->insert_id();
    }
    
    /**
     * 
     */
    function UpdateReview($data, $id){
        $this->db
        ->where('sid', $id)
        ->update($this->R, $data);
        //
        return $id;
    }


    /*------------------------------------------------- Private -------------------------------------------------/*

    /**
     * 
     */
    private function employeeDT($EmployeeId, $r){
        //
        $a =
        $this->db
        ->select("
            {$this->DTM}.sid as team_id,
            {$this->DTM}.team_lead,
            {$this->DTM}.reporting_managers,
            {$this->DM}.sid as department_id,
            {$this->DM}.reporting_managers as reporting_managers_2,
            {$this->DM}.supervisor
        ")
        ->join("{$this->DTM}", "{$this->DTM}.sid = {$this->DE2T}.team_sid")
        ->join("{$this->DM}", "{$this->DM}.sid = {$this->DTM}.department_sid")
        ->where("{$this->DM}.status", 1)
        ->where("{$this->DM}.is_deleted", 0)
        ->where("{$this->DTM}.status", 1)
        ->where("{$this->DTM}.is_deleted", 0)
        ->where("{$this->DE2T}.employee_sid", $EmployeeId)
        ->get("{$this->DE2T}");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        if(!empty($b)){
            //
            $d = $t = $s = $l = $rm = [];
            //
            foreach($b as $v){
                //
                $d[] = $v['department_id'];
                $t[] = $v['team_id'];
                //
                $s = array_merge($s, explode(',', $v['supervisor']));
                $l = array_merge($l, explode(',', $v['team_id']));
                //
                $rm = array_merge($rm, !empty( $v['reporting_managers']) ? explode(',', $v['reporting_managers']) : []);
                $rm = array_merge($rm, !empty( $v['reporting_managers_2']) ? explode(',', $v['reporting_managers_2']) : []);
            }
            //
            $r['TeamLeads'] = $l;
            $r['Supervisors'] = $s;
            $r['Departments'] = $d;
            $r['Teams'] = $t;
            $r['ReportingManagers'] = $rm;
        } else{
            $r['TeamLeads'] = 
            $r['Supervisors'] =
            $r['Departments'] =
            $r['ReportingManagers'] =
            $r['Teams'] = [];
        }
        return $r;
    }

    //
    function getMyGoals(){
        return [];
    }
    
}