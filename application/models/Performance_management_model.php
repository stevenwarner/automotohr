<?php

class Performance_management_model extends CI_Model{
    // 
    private $U = 'users';
    private $DM = 'departments_management';
    private $DTM = 'departments_team_management';
    private $DE2T = 'departments_employee_2_team';
    private $PMT = 'performance_management_templates';
    private $PMCT = 'performance_management_company_templates';

    private $DbDateFormat = 'Y-m-d H:i:s';

    private $DbDateFormatWithoutTime = 'Y-m-d';

    private $SiteDateFormat = 'M d Y, D H:i:s';
    /**
     * 
     */
    function __construct(){
        parent::__construct();
    }

    /**
     * 
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
                    'Role' => trim(remakeEmployeeName($v, false)),
                    'Image' => AWS_S3_BUCKET_URL. (empty($v['profile_picture']) ? 'test.png' : $v['profile_picture']),
                    'Email' => strtolower($v['email']),
                    'Phone' => $v['PhoneNumber'],
                    'DOB' => empty($v['dob']) || $v['dob'] == '0000-00-00' ? '' : DateTime::createfromformat($this->DbDateFormatWithoutTime, $v['dob'])->format($this->DbDateFormatWithoutTime),
                    'JoinedDate' => empty($v['joined_at']) ? '' : DateTime::createfromformat($this->DbDateFormat, $v['joined_at'])->format($this->DbDateFormat)
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
            {$this->DM}.sid as department_id,
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
            $d = $t = $s = $l = [];
            //
            foreach($b as $v){
                //
                $d[] = $v['department_id'];
                $t[] = $v['team_id'];
                //
                $s = array_merge($s, explode(',', $v['supervisor']));
                $l = array_merge($l, explode(',', $v['team_id']));
            }
            //
            $r['TeamLeads'] = $l;
            $r['Supervisors'] = $s;
            $r['Departments'] = $d;
            $r['Teams'] = $t;
        } else{
            $r['TeamLeads'] = 
            $r['Supervisors'] =
            $r['Departments'] =
            $r['Teams'] = [];
        }
        return $r;
    }

    
}