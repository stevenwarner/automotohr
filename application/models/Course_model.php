<?php
class Course_model extends CI_Model {
     private $U = 'users';
     private $DbDateFormatWithoutTime = 'Y-m-d';
     private $DbDateFormat = 'Y-m-d H:i:s';
     //
    function __construct() {
        parent::__construct();
    }

    function get_all_employees($company_sid) {
        $this->db->select('sid, first_name, last_name, email, access_level, access_level_plus, pay_plan_flag, is_executive_admin, job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->from('users');
        $this->db->order_by('concat(first_name,last_name)', 'ASC', false);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getActiveDepartments($company_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('is_deleted', 0);
        $this->db->from('departments_management');
        $this->db->order_by('name', 'asc');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getTeams( $companySid, $departments ){
        //
        if(!$departments || !count($departments)) return [];
        //
        $a = $this->db
        ->select('sid, name')
        ->where('company_sid', $companySid)
        ->where('status', 1)
        ->where('is_deleted', 0)
        ->where_in('department_sid', array_column($departments, 'sid'))
        ->order_by('sort_order', 'ASC')
        ->get('departments_team_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function insert_attached_document($data_to_insert) {
        $this->db->insert('learning_center_attachment', $data_to_insert);
        return $this->db->insert_id();
    }

    function GetCompanyDepartmentAndTeams($CompanyId){
        $a =
        $this->db
        ->select("
            departments_team_management.sid as team_id,
            departments_team_management.name as team_name,
            departments_management.sid as department_id,
            departments_management.name as department_name,
            departments_employee_2_team.employee_sid
        ")
        ->join("departments_team_management", "departments_team_management.sid = departments_employee_2_team.team_sid")
        ->join("departments_management", "departments_management.sid = departments_team_management.department_sid")
        ->where("departments_management.status", 1)
        ->where("departments_management.is_deleted", 0)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->where("departments_team_management.company_sid", $CompanyId)
        ->where("departments_management.company_sid", $CompanyId)
        ->order_by("departments_management.name", "ASC")
        ->order_by("departments_team_management.name", "ASC")
        ->get("departments_employee_2_team");
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

    private function employeeDT($EmployeeId, $r){
        //
        $a =
        $this->db
        ->select("
            departments_team_management.sid as team_id,
            departments_team_management.team_lead,
            departments_team_management.reporting_managers,
            departments_management.sid as department_id,
            departments_management.reporting_managers as reporting_managers_2,
            departments_management.supervisor
        ")
        ->join("departments_team_management", "departments_team_management.sid = departments_employee_2_team.team_sid")
        ->join("departments_management", "departments_management.sid = departments_team_management.department_sid")
        ->where("departments_management.status", 1)
        ->where("departments_management.is_deleted", 0)
        ->where("departments_team_management.status", 1)
        ->where("departments_team_management.is_deleted", 0)
        ->where("departments_employee_2_team.employee_sid", $EmployeeId)
        ->get("departments_employee_2_team");
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

}
