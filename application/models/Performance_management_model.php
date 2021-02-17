<?php

class Performance_management_model extends CI_Model{
    /**
     * 
     */
    private $tables;

    /**
     * 
     */
    function __construct($tables){
        $this->tables = $tables;
        parent::__construct();
    }

    /**
     * Insert data into the database
     * 
     * @employee Mubashir Ahmed
     * @date     02/17/2021
     * 
     * @param  String $tableName
     * @param  Array  $data
     * 
     * @return Integer
     */
    function _insert($tableName, $data){
        $this->db->insert($tableName, $data);
        return $this->db->insert_id();
    }
    
    /**
     * Update data into the database
     * 
     * @employee Mubashir Ahmed
     * @date     02/17/2021
     * 
     * @param  String $tableName
     * @param  Array  $data
     * @param  Array  $where
     * 
     * @return Void
     */
    function _update($tableName, $data, $where){
        $this->db
        ->where($where)
        ->update($tableName, $data);
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
    function getCompanyTemplates(
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC');
        //
        $a = $this->db->get($this->tables['PMT']);
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
    function getPersonalTemplates(
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
        $a = $this->db->get($this->tables['PMCT']);
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        if($page == 1){
            return [
                'Records' => $b,
                'Count' => $this->db->where('is_archived', $archived)->count_all_results($this->tables['PMCT'])
            ];
        }
        //
        return $b;        
    }


    /**
     * Get personal template questions
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer $templateId
     * @return Array
     */
    function getPersonalQuestionsById($templateId){
        return 
        $this->db
        ->select('questions')
        ->where('sid', $templateId)
        ->get($this->tables['PMCT'])
        ->row_array()['questions'];
    }

    /**
     * Get default template questions
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer $templateId
     * @return Array
     */
    function getCompanyQuestionsById($templateId){
        return 
        $this->db
        ->select('questions')
        ->where('sid', $templateId)
        ->get($this->tables['PMCT'])
        ->row_array()['questions'];
    }
    
    /**
     * Get personal template by id
     * 
     * @employee
     * @date      02/10/2021
     * 
     * @param  Integer       $templateId
     * @param  String|Array  $columns
     * @return Array
     */
    function getPersonalTemplateById($templateId, $columns = '*'){
        return 
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $templateId)
        ->get($this->tables['PMCT'])
        ->row_array();
    }
    
    /**
     * Get default template by id
     * 
     * @employee  Mubashir Ahmed
     * @date      02/10/2021
     * 
     * @param  Integer       $templateId
     * @param  String|Array  $columns
     * @return Array
     */
    function getCompanyTemplateById($templateId, $columns = '*'){
        return 
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $templateId)
        ->get($this->tables['PMT'])
        ->row_array();
    }

    /**
     * Get company active employee by 
     * permission
     * 
     * @employee  Mubashir Ahmed
     * @date      02/11/2021
     * 
     * @method  getUserFields
     * @method  getMyEmployees
     * @method  getTeamsAndDepartmentsWithEmployees
     * 
     * @param  Integer       $employerId 
     * @param  Integer       $companyId 
     * @param  Integer       $hasAccess 
     *                       (Default 0)
     * @return Array
     */
    function getEmployeeListWithDepartments($employerId, $companyId, $hasAccess = 0){
        // If not a super admin
        $employeeIds = -1;
        //
        if(!$hasAccess){
            $employeeIds = $this->getMyEmployees($employerId);
            
            if(empty($employeeIds)) return [];
        }
        // Get employees
        $this->db
        ->select("
            ".( getUserFields() )."
            IF(users.joined_at = '' OR users.joined_at = NULL, DATE_FORMAT(users.registration_date, '%Y-%m-%d'), users.joined_at) as joined_at,
            users.employee_type
        ")
        ->from('users')
        ->where('users.active', 1)
        ->where('users.terminated_status', 0)
        ->where('users.parent_sid', $companyId)
        ->order_by('users.first_name', 'ASC');
        //
        if(is_array($employees)) $this->db->where_in('users.sid', $employeeIds);
        //
        $employees = $this->db->get()->result_array();
        //
        if(empty($employees)) return [];
        // Get teams and departments
        $dta = $this->getTeamsAndDepartmentsWithEmployees($companyId);
        //
        foreach($employees as $k => $employee){
            //
            if(!isset($dta[$employee['userId']])) $employees[$k]['departmentIds'] = $employees[$k]['teamIds'] = [];
            else{
                $dta[$employee['userId']]['departmentIds'] = array_unique($dta[$employee['userId']]['departmentIds'], SORT_STRING);
                $dta[$employee['userId']]['teamIds'] = array_unique($dta[$employee['userId']]['teamIds'], SORT_STRING);
                $employees[$k] = array_merge($employee, $dta[$employee['userId']]);
            }
        }
        //
        return $employees;
    }
    
    /**
     * Get my employees
     * 
     * @employee  Mubashir Ahmed
     * @date      02/11/2021
     * 
     * @param  Integer       $employerId 
     * @return Array
     */
    function getMyEmployees($employerId){
        //
        $teamIds = $departmentIds = [];
        // Get team Ids
        $teams = $this->db
            ->select("{$this->tables['DTM']}.sid, {$this->tables['DM']}.sid as department_sid")
            ->from("{$this->tables['DTM']}")
            ->join("{$this->tables['DM']}", "{$this->tables['DM']}.sid = {$this->tables['DTM']}.department_sid", 'inner')
            ->where("{$this->tables['DTM']}.status", 1)
            ->where("{$this->tables['DTM']}.is_deleted", 0)
            ->where("{$this->tables['DM']}.status", 1)
            ->where("{$this->tables['DM']}.is_deleted", 0)
            ->where("FIND_IN_SET({$employerId}, {$this->tables['DTM']}.team_lead) > ", 0, false)
            ->get()
            ->result_array();
        //
        if(!empty($teams)){
            $teamIds = array_column($teams, 'sid');
            $departmentIds = array_column($teams, 'department_sid');
        }
        // Get departments
        $this->db
        ->select('sid')
        ->from("{$this->tables['DM']}")
        ->where('status', 1)
        ->where('is_deleted', 0)
        ->where("FIND_IN_SET({$employerId}, supervisor) > ", 0, false);
        //
        if(!empty($departmentIds)){
            $this->db->where_not_in('sid', $departmentIds);
        }
        //
        $departments =  $this->db
        ->get()
        ->result_array();
        //
        if(!empty($departments)){
            $departmentIds = array_merge($departmentIds, array_column($departments, 'sid'));
        }
        //
        if(empty($departmentIds) && empty($teamIds)) return [];
        // Get employee Ids
        $this->db->select('employee_sid')
        ->from("{$this->tables['DME']}");
        //
        if(!empty($departmentIds) && !empty($teamIds)){
            $this->db->where_in('department_sid', $departmentIds);
            $this->db->or_where_in('team_sid', $teamIds);
        } else if(!empty($departmentIds)){
            $this->db->where_in('department_sid', $departmentIds);
        } else if(!empty($teamIds)){
            $this->db->where_in('team_sid', $teamIds);
        }
        //
        $employees = $this->db->get()->result_array();
        //
        if(!empty($employees)) return array_column($employees, 'employee_sid');
        return [];
    }


    /**
     * Get company teams & departments with employees
     * 
     * @employee  Mubashir Ahmed
     * @date      02/11/2021
     * 
     * @param  Integer       $companyId 
     * @return Array
     */
    function getTeamsAndDepartmentsWithEmployees($companyId){
        //
        $ra = [];
        //
        $teamIds = $departmentIds = [];
        // Get team Ids
        $teams = $this->db
            ->select("{$this->tables['DTM']}.sid, {$this->tables['DM']}.sid as department_sid")
            ->from("{$this->tables['DTM']}")
            ->join("{$this->tables['DM']}", "{$this->tables['DM']}.sid = {$this->tables['DTM']}.department_sid", 'inner')
            ->where("{$this->tables['DTM']}.status", 1)
            ->where("{$this->tables['DTM']}.is_deleted", 0)
            ->where("{$this->tables['DM']}.status", 1)
            ->where("{$this->tables['DM']}.is_deleted", 0)
            ->where("{$this->tables['DM']}.company_sid", $companyId)
            ->get()
            ->result_array();
        //
        if(!empty($teams)){
            $teamIds = array_column($teams, 'sid');
            $departmentIds = array_column($teams, 'department_sid');
        }
        // Get departments
        $this->db
        ->select('sid')
        ->from("{$this->tables['DM']}")
        ->where('status', 1)
        ->where('is_deleted', 0)
        ->where("company_sid", $companyId);
        //
        if(!empty($departmentIds)){
            $this->db->where_not_in('sid', $departmentIds);
        }
        //
        $departments =  $this->db
        ->get()
        ->result_array();
        //
        if(!empty($departments)){
            $departmentIds = array_merge($departmentIds, array_column($departments, 'sid'));
        }
        //
        if(empty($departmentIds) && empty($teamIds)) return $ra;
        
        // Get department employee Ids
        if(!empty($departmentIds)){
            //
            $departmentEmployees =
            $this->db->select('employee_sid, department_sid')
            ->from("{$this->tables['DME']}")
            ->where_in('department_sid', $departmentIds)
            ->get()
            ->result_array();
            //
            if(!empty($departmentEmployees)){
                foreach($departmentEmployees as $employee){
                    //
                    if(!isset($ra[$employee['employee_sid']]))
                        $ra[$employee['employee_sid']] = ['departmentIds' => [], 'teamIds' => []];
                    //    
                    $ra[$employee['employee_sid']]['departmentIds'][] = $employee['department_sid'];
                }
            }
        }

        // Get team employee Ids
        if(!empty($teamIds)){
            //
            $teamEmployees =
            $this->db->select('employee_sid, team_sid')
            ->from("{$this->tables['DME']}")
            ->where_in('team_sid', $teamIds)
            ->get()
            ->result_array();
            //
            if(!empty($teamEmployees)){
                foreach($teamEmployees as $employee){
                    //
                    if(!isset($ra[$employee['employee_sid']]))
                        $ra[$employee['employee_sid']] = ['departmentIds' => [], 'teamIds' => []];
                    //    
                    $ra[$employee['employee_sid']]['teamIds'][] = $employee['team_sid'];
                }
            }
        }

        return $ra;
    }

    /**
     * Get company teams & departments
     * 
     * @employee  Mubashir Ahmed
     * @date      02/11/2021
     * 
     * @param  Integer  $companyId 
     * @return Array
     */
    function getTeamsAndDepartments($companyId){
        //
        $ra = [];
        // Get team Ids
        $ra['teams'] = $this->db
            ->select("
                {$this->tables['DTM']}.sid, 
                {$this->tables['DTM']}.name
            ")
            ->from("{$this->tables['DTM']}")
            ->join("{$this->tables['DM']}", "{$this->tables['DM']}.sid = {$this->tables['DTM']}.department_sid", "inner")
            ->where("{$this->tables['DTM']}.status", 1)
            ->where("{$this->tables['DTM']}.is_deleted", 0)
            ->where("{$this->tables['DM']}.status", 1)
            ->where("{$this->tables['DM']}.is_deleted", 0)
            ->where("{$this->tables['DM']}.company_sid", $companyId)
            ->order_by("{$this->tables['DTM']}.name", 'ASC')
            ->get()
            ->result_array();
            // Get departments
            $ra['departments'] = $this->db
            ->select('sid, name')
            ->from("{$this->tables['DM']}")
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->where("company_sid", $companyId)
            ->order_by('name', 'ASC')
            ->get()
            ->result_array();
       

        return $ra;
    }
    
    /**
     * Get company job titles
     * 
     * @employee  Mubashir Ahmed
     * @date      02/11/2021
     * 
     * @param  Integer  $companyId 
     * @return Array
     */
    function getCompanyJobTitles($companyId){
        //
        return
        $this->db
        ->select('job_title')
        ->distinct()
        ->from("{$this->tables['USER']}")
        ->where('parent_sid', $companyId)
        ->where('active', 1)
        ->where('terminated_status', 0)
        ->where('job_title <> ', NULL)
        ->where('job_title != ', '')
        ->order_by('job_title', 'ASC')
        ->get()
        ->result_array();
    }

    /**
     * Get review by id
     * 
     * @param Array|String $columns
     *                     Default is '*'
     * @param Booleon      $archived 
     *                     Default is '0'
     * 
     * @return Array
     */
    function getReviewById(
        $columns = '*', 
        $archived = 0
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('is_archived', $archived)
        ->order_by('name', 'ASC')
        ->limit(1);
        //
        $a = $this->db->get($this->tables['PM']);
        $b = $a->result_array();
        // Free result
        $a->free_result();
        //
        return $b;
    }
    
}
