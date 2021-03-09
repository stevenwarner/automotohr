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
        if(isset($data[0]) && count($data) == 1) $data = $data[0];
        //
        if(isset($data[1])) 
        $this->db->insert_batch($tableName, $data);
        else
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
    function checkAndInsert($tableName, $data, $where){
        //
        if(
            !$this->db->where($where)->count_all_results($tableName)
        ){
            return $this->_insert($tableName, $data);
        }
        //
        return false;
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
        ->get($this->tables['PMT'])
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
                {$this->tables['DTM']}.name,
                {$this->tables['DTM']}.team_lead
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
            ->select('sid, name, supervisor')
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
        $id,
        $columns = '*', 
        $archived = 0,
        $where = []
    ){
        $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where('sid', $id)
        ->where('is_archived', $archived)
        ->limit(1);
        //
        if(!empty($where)) $this->db->where($where);
        //
        $a = $this->db->get($this->tables['PM']);
        $b = $a->row_array();
        // Free result
        $a->free_result();
        //
        return $b;
    }

    /**
     * 
     */
    function getAllCompanyEmployees($companyId){
        $a = $this->db
        ->select('
            first_name,
            last_name,
            profile_picture,
            sid as userId,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin,
            job_title,
            IF(joined_at is null, registration_date, joined_at) as joined_at
        ')
        ->where('parent_sid', $companyId)
        ->where('active', 1)
        ->where('terminated_status', 0)
        ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }

    /**
     * 
     */
    function getReviews($companyId, $employeeId, $employeeRole, $level, $filter = []){
        // When the employe is not super admin
        if($level != 1){
            $dt = $this->getEmployeeDTR($employeeId, $employeeRole);
            //
            $Role = $dt['Role'];
            $Teams = $dt['Teams'];
            $Departments = $dt['Departments'];
        }
        //
        $this->db
        ->select('
            sid,
            review_title,
            status,
            is_template,
            is_archived,
            review_start_date,
            review_end_date
        ')
        ->from($this->tables['PM'])
        ->where('company_sid', $companyId)
        ->order_by('sid', 'desc');
        //
        if($level != 1){
            $this->db->group_start();
            $this->db->where('visibility_roles', $Role);
            $this->db->or_where_in('visibility_departments', $Teams);
            $this->db->or_where_in('visibility_teams', $Departments);
            $this->db->or_where_in('visibility_employees', $employeeId);
            $this->db->group_end();
        }
        //
        if(count($filter)){
            $this->db->where('is_archived', $filter['reviewStatus'] != 'archived' ? 0 : 1);
            $this->db->where('is_draft', $filter['reviewStatus'] == 'draft' ? 1 : 0);
        }
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(!empty($b)){
            //
            $reviewIds = array_column($b, 'sid');
            //
            $a = 
            $this->db
            ->select('review_sid, reviewer_sid, is_completed, is_manager')
            ->get($this->tables['PMRV']);
            //
            $c = $a->result_array();
            $a->free_result();
            //
            if(empty($c)) return $b;
            //
            $newReviewers = [];
            //
            foreach($c as $k => $v){
                $newReviewers[$v['review_sid']][] = ['reviewer_Id' => $v['reviewer_sid'], 'reviewer_type' => $v['is_manager'] == 1 ? 'Feeback' : 'Review', 'completion_status' => $v['is_completed'] ];
            }
            //
            $reviewIds = array_column($c, 'review_sid');
            //
            $filterCount = count($filter);
            //
            foreach($b as $k => $v){
                if($level != 1){
                    //
                    if($filterCount && $filter['reviewType'] != -1){
                        //
                        $filteredArray = 
                        //
                        array_filter($newReviewers[$v['sid']], function($row){
                            if($filter['reviewType'] == 'review'){
                                if($row['is_manager'] == 0 && $row['reviewer_Id'] == $employeeId) return true;
                            } else if($filter['reviewType'] == 'feedback'){
                                if($row['is_manager'] == 1 && $row['reviewer_Id'] == $employeeId) return true;
                            }
                        });
                        //
                        if(empty($filteredArray)) {
                            unset($b[$k]);
                            continue;
                        }
                    }
                }
                //
                $b[$k]['reviewers'] = $newReviewers[$v['sid']];
            }
            //
            $b = array_values($b);
        }
        //
        return $b;
    }

    /**
     * 
     */
    private function getEmployeeDTR($employeeId, $employeeRole){
        //
        $r = [
            'Role' => strtolower($employeeRole),
            'Teams' => [],
            'Departments' => []
        ];
        //
        $b = 
        $this->db
        ->select('
            departments_team_management.sid as team_ids,
            departments_team_management.sid as department_ids
        ')
        ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        ->join('documents_management', 'documents_management.sid = departments_employee_2_team.department_si', 'inner')
        ->from('departments_employee_2_team')
        ->where('departments_team_management.status', 1)
        ->where('departments_team_management.is_deleted', 0)
        ->where('documents_management.status', 1)
        ->where('documents_management.is_deleted', 0)
        ->where('departments_employee_2_team.employee_sid', $employeeId)
        ->get()
        ->result_array();
        //
        if(!empty($b)){
            $r['Teams'] = array_column($b, 'team_ids');
            $r['Departments'] = array_column($b, 'department_ids');
        }
        //
        return $b;
    }

    /**
     * 
     * @date 02/25/2021
     */
    function saveReviewAsTemplate(
        $companyId,
        $employeeId,
        $reviewId
    ){
        $a =    
        $this->db
        ->select('pm.review_title, pmrq.question')
        ->from('performance_management pm')
        ->join('performance_management_review_questions pmrq', 'pmrq.review_sid = pm.sid')
        ->where('pm.sid', $reviewId)
        ->where('pm.company_sid', $companyId)
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)) return false;
        //
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['employee_sid'] = $employeeId;
        $ins['review_sid'] = $reviewId;
        $ins['name'] = $b[0]['review_title'];
        $ins['questions'] = '['.implode(',',array_column($b, 'question')).']';
        //
        $this->db
        ->insert('performance_management_company_templates', $ins);
        //
        $this->db
        ->where('sid', $reviewId)
        ->update('performance_management', ['is_template' => 1]);
        //
        return true;
    }

    /**
     * 
     */
    function getReviwerListByRevieweeId($reviewId, $revieweeId){
        $a = 
        $this->db
        ->select('reviewer_sid')
        ->from($this->tables['PMRV'])
        ->where('review_sid', $reviewId)
        ->where('reviewee_sid', $revieweeId)
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)) return [];
        return array_column($b, 'reviewer_sid');
    }

    /**
     * 
     */
    function getManagerOffEmployee($employeeId){
        $a =
        $this->db
        ->select('dtm.team_lead')
        ->from('departments_employee_2_team dm2t')
        ->join('departments_team_management dtm', 'dtm.sid = dm2t.team_sid')
        ->where('dm2t.employee_sid', $employerId)
        ->where('dtm.status', 1)
        ->where('dtm.is_deleted', 0)
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(empty($b)) return [];
        //
        return array_unique(explode(',', implode(',', array_column($b, 'team_lead'))), SORT_STRING);
    }

    /**
     * 
     */
    function getCompanyGoals($companyId){
        $a = $this->db
        ->select('sid, title, measure_type, target')
        ->where('company_sid', $companyId)
        ->where('goal_type', 4)
        ->where('status', 1)
        ->order_by('title', 'ASC')
        ->get('goals');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
    
    /**
     * 
     */
    function getGoalsByFilter($companyId, $employeeId, $filter){
        $this->db
        ->select('sid, title, description, measure_type, target, completed_target, start_date, end_date, employee_sid, on_track, status, roles, teams, departments, employees')
        ->where('company_sid', $companyId)
        ->order_by('sid', 'DESC');
        //
        if($filter['status'] != -1) $this->db->where('status', $filter['status']);
        if($filter['type'] != -1) $this->db->where('goal_type', $filter['type']);
        // if($filter['type'] == 1) $this->db->where('employee_sid', $employeeId);
        if($filter['employeeId'] != -1 && $filter['employeeId'] != 0) $this->db->where('employee_sid', $filter['employeeId']);
        //
        $a = $this->db->get('goals');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
      
    /**
     * 
     */
    function getGoalComments($companyId, $goalId){
        $this->db
        ->select('sender_sid, message, created_at')
        ->where('goal_sid', $goalId)
        ->order_by('sid', 'ASC');
        //
        $a = $this->db->get('goal_comments');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
    
    /**
     * 
     */
    function getGoalHistory($companyId, $goalId){
        $this->db
        ->select('action, note, created_at, employee_sid')
        ->where('goal_sid', $goalId)
        ->order_by('sid', 'DESC');
        //
        $a = $this->db->get('goal_history');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }


    /**
     * 
     */
    function getReview($reviewId){
        //
        $review = $this->getReviewById($reviewId, ['review_title', 'share_feedback']);
        // Get Review Reviewees
        $review['Reviewees'] = $this->getReviewReviewees($reviewId);
        $review['Reviewers'] = $this->getReviewReviewers($reviewId);
        //
        return $review;
    }
    
    /**
     * 
     */
    function getReviewWithQuestions($reviewId, $revieweeId, $reviewerId){
        //
        $review = $this->getReviewById($reviewId, ['review_title', 'share_feedback']);
        // Get Review Reviewees
        $review['Reviewee'] = $this->getReviewReviewees($reviewId, $revieweeId);
        $review['Reviewer'] = $this->getReviewReviewers($reviewId, $revieweeId, $reviewerId);
        $review['Questions'] = $this->getReviewQuestions($reviewId, $revieweeId, $reviewerId);
        //
        return $review;
    }

    /**
     * 
     */
    function getReviewReviewees($reviewId, $revieweeId = 0){
        //
        $this->db
        ->select('reviewee_sid, start_date, end_date, is_started')
        ->where('review_sid', $reviewId);
        //
        if($revieweeId !== 0) $this->db->where('reviewee_sid', $revieweeId);
        //
        $a = $this->db->get('performance_management_reviewees');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
    
    /**
     * 
     */
    function getReviewReviewers($reviewId, $revieweeId = 0, $reviewerId = 0){
        //
        $this->db
        ->select('reviewee_sid, reviewer_sid, is_manager, is_completed')
        ->where('review_sid', $reviewId);
        //
        if($revieweeId !== 0) $this->db->where('reviewee_sid', $revieweeId);
        if($reviewerId !== 0) $this->db->where('reviewer_sid', $reviewerId);
        $a = $this->db->get('performance_management_reviewers');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
    
    /**
     * 
     */
    function getReviewQuestions($reviewId, $revieweeId, $reviewerId){
        //
        $this->db
        ->select('
            pmrq.sid,
            pmrq.question,
            pmra.answer
        ')
        ->from('performance_management_review_questions pmrq')
        ->join('performance_management_review_answers pmra', "
            pmra.review_question_sid = pmrq.sid AND
            pmra.review_reviewer_sid = {$reviewerId} AND
            pmra.reviewee_sid = {$revieweeId}
        ", 'left')
        ->where('pmrq.review_sid', $reviewId);
        $a = $this->db
        ->order_by('pmrq.sid', 'ASC')
        ->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }


    /**
     * 
     */
    function getReviewWithQuestionsForManager($reviewId, $employerId){
        //
        $review = $this->getReviewWithQuestions($reviewId, $employerId);
        //
        foreach($review['Questions'] as $k => $v){
            $reviewersAnswer = $this->getReviewQuestions($reviewId, $employerId);
            // _e($v, true, true);
        }
        //
        return $review;
    }

     /**
     * Get timeoff email template
     * 
     * @employee Mubashir Ahmed
     * @date     02/07/2021
     * 
     * @param Integer $templateId
     * 
     * @return Array
     */
    function getEmailTemplate($templateId){
        $a = $this->db
        ->select()
        ->where('sid', $templateId)
        ->limit(1)
        ->get('email_templates');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if(!count($b)) return [];
        //
        return [
            'Subject' => $b['subject'],
            'Body' => $b['text'],
            'FromEmail' => !empty($b['from_email']) ? $b['from_email'] : 'no-reply@automotohr.com',
            'FromName' => !empty($b['from_name']) ? $b['from_name'] : '{{company_name}}'
        ];
    }

    /**
     * 
     */
    function getGoals($employeeId){
        $this->db
        ->select('sid, title, description, measure_type, target, completed_target, start_date, end_date, employee_sid, on_track')
        ->where('employee_sid', $employeeId)
        ->where('status', 1)
        ->order_by('sid', 'DESC');
        //
        $a = $this->db->get('goals');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }
    
    /**
     * 
     */
    function getReviewsByType(
        $employeeId,
        $type = 'assigned'
    ){
        $this->db
        ->select('
            pmrs.reviewee_sid,
            pmr.end_date,
            pmr.review_sid
        ')
        ->from('performance_management_reviewers pmrs')
        ->join('performance_management_reviewees pmr', 'pmr.reviewee_sid = pmrs.reviewee_sid')
        ->where('pmrs.reviewer_sid', $employeeId)
        ->where('pmrs.is_manager', $type == 'assigned' ? 0 : 1)
        ->where('pmrs.is_completed', 0)
        ->where('pmr.is_started', 1)
        ->order_by('pmrs.sid', 'DESC');
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }


    /**
     * 
     */
    function isManager($employeeId, $employerId){
        //
        return in_array($employeeId, $this->getMyEmployees($employerId));
    }

    /**
     * 
     */
    function getEmployeePermission(
        $employerId,
        $isPlus
    ){
        //
        if($isPlus == 1) return ['isSuperAdmin' => 1];
        //
        return $this->getTDEUnderEmployee($employerId);
    }

    /**
     * 
     */
    function getTDEUnderEmployee($employerId){
        //
        $d = $this->db
        ->select('de2t.employee_sid, dm.sid as department_sid')
        ->from('departments_employee_2_team de2t')
        ->join('departments_team_management dtm', 'dtm.sid = de2t.team_sid')
        ->where('dtm.status', 1)
        ->where('dtm.is_deleted', 0)
        ->join('departments_management dm', 'dm.sid = de2t.department_sid')
        ->where('dm.status', 1)
        ->where('dm.is_deleted', 0)
        ->where("FIND_IN_SET({$employerId}, dm.supervisor) > 0", false, false)
        ->get()
        ->result_array();

        //
        $t = $this->db
        ->select('de2t.employee_sid, dtm.sid as team_sid')
        ->from('departments_employee_2_team de2t')
        ->join('departments_team_management dtm', 'dtm.sid = de2t.team_sid')
        ->where('dtm.status', 1)
        ->where('dtm.is_deleted', 0)
        ->join('departments_management dm', 'dm.sid = de2t.department_sid')
        ->where('dm.status', 1)
        ->where('dm.is_deleted', 0)
        ->where("FIND_IN_SET({$employerId}, dtm.team_lead) > 0", false, false)
        ->get()
        ->result_array();

        return [
            'teamIds' => array_column($t, 'team_sid'),
            'departmentIds' => array_column($d, 'department_sid'),
            'employeeIds' => array_unique(array_merge(array_column($d, 'employee_sid'), array_column($t, 'employee_sid')), SORT_STRING)
        ];
    }

    /**
     * 
     */
    function getMyGoals($employeeId){
        return $this->db
        ->where('status', 1)
        ->where('employee_sid', $employeeId)
        ->order_by('sid', 'desc')
        ->get('goals')
        ->result_array();
    }

    /**
     * 
     */
    function removeReviewee(
        $reviewId,
        $revieweeId
    ){
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->delete('performance_management_reviewees');
        //
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->delete('performance_management_reviewers');
    }
}
