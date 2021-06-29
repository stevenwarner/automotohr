<?php

class Performance_management_model extends CI_Model{
    // 
    private $U = 'users';
    private $DM = 'departments_management';
    private $DTM = 'departments_team_management';
    private $DE2T = 'departments_employee_2_team';
    private $PMT = 'performance_management_templates';
    private $PMCT = 'performance_management_company_templates';
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
<<<<<<< HEAD


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
        ->distinct()
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


    // function checkAndGetRole($id){
    //     //
    //     $dm = $this->db->table('departments_management');
    //     // $dtm = $this->db->table('departments_team_management');
    //     // $d2e = $this->db->table('departments_employee_2_team');

    //     // Fetch all department SP
    //     $a = $dm
    //     ->select('supervisors')
    //     ->where('is_deleted', 0)
    //     ->where('status', 1)
    //     ->get();
    //     //
    //     $b = $a->result_array();
    //     $a->free_result();

    //     _e($b, true);
    // }

    /**
     * 
     */
    function getLMSReviews($employeeId, $companyId){
        
        // Get assigned reviews
        //
        $assignedReviews = 
        $this->db
        ->select('
        pm.review_title,
        pm.share_feedback,
            pmr.review_sid, 
            pmr.reviewee_sid, 
            pmrv.reviewer_sid, 
            pmr.is_started,
            pmrv.is_manager,
            pmr.start_date,
            pmr.end_date
        ')
        ->from('performance_management_reviewers pmrv')
        ->join('performance_management_reviewees pmr', 'pmrv.review_sid = pmr.review_sid and pmrv.reviewee_sid = pmr.reviewee_sid')
        ->join('performance_management pm', 'pmrv.review_sid = pm.sid')
        ->where('pmrv.reviewer_sid', $employeeId)
        ->where('pmr.is_started', 1)
        ->where('pm.company_sid', $companyId)
        ->get()
        ->result_array();
        // Get my reviews
        $myReviews = 
        $this->db
        ->select('
        pm.review_title,
        pm.share_feedback,
        pmr.review_sid, 
        pmr.reviewee_sid, 
        pmrv.reviewer_sid, 
            pmr.is_started,
            pmrv.is_manager,
            pmr.start_date,
            pmr.end_date
            ')
            ->from('performance_management_reviewers pmrv')
            ->join('performance_management_reviewees pmr', 'pmrv.review_sid = pmr.review_sid and pmrv.reviewee_sid = pmr.reviewee_sid')
            ->join('performance_management pm', 'pmrv.review_sid = pm.sid')
            ->where('pmr.reviewee_sid', $employeeId)
            ->where('pm.share_feedback', 1)
            ->where('pmr.is_started', 1)
        ->where('pm.company_sid', $companyId)
        ->get()
        ->result_array();
        //
        return ['MyReviews' => $myReviews, 'AssignedReviews' => $assignedReviews];
    }

    function getGoalsByPerm(
        $type,
        $year,
        $month,
        $day,
        $week_start,
        $week_end,
        $company_id,
        $employer_id,
        $event_type,
        $access_level,
        $employer_detail
    ) {
        // check for type
        if ($type == 'day') {
            $startDate = $year . '-' . $month . '-' . $day;
            $endDate = $year . '-' . $month . '-' . $day;
        } else { // month, week
            $startDate = $year . '-' . $week_start;
            $endDate   = $year . '-' . $week_end;
            if(substr($week_start, 0, 2) == '11' && substr($week_start, 3, 4) == '29'){
                $startDate = $year.'-'.$week_start;
                $endDate = date('Y', strtotime(''.($year).'+1 year')).'-'.$week_end;
            }
            if (substr($week_start, 0, 2) == '12' && substr($week_start, 3, 4) == '27') {
                $startDate = date('Y', strtotime("$year -1 year")).'-'.$week_start;
                $endDate   = $year. '-' . $week_end;
            }
        }
        //
        $this->db
        ->select('goals.*, users.first_name, users.last_name, users.profile_picture')
            ->from('goals')
            ->join('users', 'users.sid = goals.employee_sid');
        //
        $this->db->group_start();
        if ($startDate != '' && $startDate != 'all' ){ $this->db->where('goals.start_date >= "' . ($startDate) . '"', null);}
        if ($endDate != '' && $endDate != 'all' ) {$this->db->or_where('goals.end_date  >= "' . ($endDate) . '"', null);}
        $this->db->group_end();
        $this->db->where('goals.company_sid', $company_id);
        $this->db->where('goals.employee_sid', $employer_id);
        //
        $a = $this->db->get();
        //
        $b =  $a->result_array();
        //
        $goals = [];

        //
        if(empty($b)) {
            return [];
        }
        foreach($b as $v){
            $start_datetime = $v['start_date'] . "T08:00" ;
            $end_datetime = $v['end_date'] . "T08:00";
            $goals[] = [
                'title' =>  $v['title'],
                'target' =>  $v['target'],
                'completed_target' =>  $v['completed_target'],
                'measure_type' =>  $v['measure_type'],
                'start' =>  $start_datetime,
                'end' =>  $end_datetime,
                'color' => "#5cb85c",
                'date' => $v['start_date'],
                'status' => "confirmed",
                'type' => 'goals',
                'requests' => 0,
                'from_date' => $v['start_date'],
                'to_date' => $v['end_date'],
                'request_id' => $v['sid'],
                'first_name' => $v['first_name'],
                'last_name' => $v['last_name'],
                'profile_picture' => $v['profile_picture']
            ];
        }
        //
        return $goals;
    }
    
    //
    function getEmployeeDetails($employeeId){
        return $this->db->where('sid', $employeeId)
        ->get('users')
        ->row_array();
    }
    
    //
    function getTemplate($templateId){
        return $this->db->where('sid', $templateId)
        ->get('email_templates')
        ->row_array();
    }

    //
    function getDepartmentEmployees($departmentId, $companyId){
        return array_column(
        $this->db
        ->select('users.sid')
        ->from('departments_employee_2_team')
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid')
        ->where('users.active', 1)
        ->where('users.parent_sid', $companyId)
        ->where('users.terminated_status', 0)
        ->where('departments_employee_2_team.department_sid', $departmentId)
        ->get()
        ->result_array(),'sid');
    }
    
    //
    function getTeamEmployees($teamId, $companyId){
        return array_column(
        $this->db
        ->select('users.sid')
        ->from('departments_employee_2_team')
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid')
        ->where('users.active', 1)
        ->where('users.parent_sid', $companyId)
        ->where('users.terminated_status', 0)
        ->where('departments_employee_2_team.team_sid', $teamId)
        ->get()
        ->result_array(), 'sid');
    }

    //
    function getGoalsExpire($time = 7){
        //
        return $this->db
        ->select('goals.title, goals.goal_type, employee.sid, company.sid as companyId, company.CompanyName')
        ->where('goals.end_date', date('Y-m-d', strtotime("+ {$time} days")))
        ->join('users employee', 'employee.sid = goals.employee_sid', 'left')
        ->join('users company', 'company.sid = goals.company_sid')
        ->get('goals')
        ->result_array();
    }

    //
    function getReviewReviewers2($reviewId){
        return array_column(
        $this->db
        ->select('reviewer_sid')
        ->distinct()
        ->where('pmr.review_sid', $reviewId)
        ->get('performance_management_reviewers pmr')
        ->result_array(),'reviewer_sid');
    }


    //
    function getAllReviews(
        $companyId,
        $reviewIds,
        $employeeIds,
        $startDate,
        $endDate
    ){
        // Get all reviews for report
        $this->db
        ->select('sid, review_title')
        ->from('performance_management')
        ->where('company_sid', $companyId)
        ->where('review_start_date >= ', $startDate)
        ->where('review_end_date <= ', $endDate);
        //
        if($reviewIds != 'all'){
            $this->db->where_in('sid', $reviewIds);
        }
        
        //
        $reviews = $this->db->get()->result_array();
        //
        if(empty($reviews)){
            return [];
        }
        //
        $answers = [
            'employees' => [],
            'reviews' => []
        ];
        //
        foreach($reviews as $review){
            // Get anwers
            $reviewAnswer = $this->db
            ->select('performance_management_review_answers.answer, performance_management_review_answers.reviewee_sid, performance_management_review_questions.question_type')
            ->from('performance_management_review_answers')
            ->join('performance_management_review_questions', 'performance_management_review_questions.sid = performance_management_review_answers.review_question_sid')
            ->where('performance_management_review_questions.review_sid', $review['sid'])
            ->where_in('performance_management_review_questions.question_type', ['text-n-rating', 'text-rating', 'multiple-choice-with-text', 'multiple-choice', 'rating'])
            ->where('performance_management_review_answers.review_question_sid <> ', 0)
            ->get()
            ->result_array();
            //
            if(!empty($reviewAnswer)){
                //
                foreach($reviewAnswer as $answ){
                    //
                    if($employeeIds != 'all'){
                        if(!in_array($answ['reviewee_sid'], [$employeeIds])) {
                            continue;
                        };
                    }
                    //
                    $ans = json_decode($answ['answer'], true);
                    //
                    $neutral = 0;
                    $agree = 0;
                    $disagree = 0;
                    //
                    if($answ['question_type'] == 'rating' || $answ['question_type'] == 'text-n-rating' || $answ['question_type'] == 'text-rating'){
                        if($ans['rating'] <= 2){
                            $agree++;
                        } else if($ans['rating'] >= 4){
                            $disagree++;
                        } else{
                            $neutral++;
                        }
                    } else if($answ['question_type'] == 'multiple-choice' || $answ['question_type'] == 'multiple-choice-with-text'){
                        if($ans['radio'] == 'yes'){
                            $agree++;
                        } else if($ans['radio'] == 'no'){
                            $disagree++;
                        } else{
                            $neutral++;
                        }
                    }
                    //
                    if(!isset($answers['reviews'][$review['sid']])){
                        $answers['reviews'][$review['sid']] = [
                            'agree' => 0,
                            'neutral' => 0,
                            'disagree' => 0,
                            'title' => $review['review_title']
                        ];
                    }
                    //
                    if(!isset($answers['employees'][$answ['reviewee_sid']])){
                        $answers['employees'][$answ['reviewee_sid']] = [
                            'agree' => 0,
                            'neutral' => 0,
                            'disagree' => 0
                        ];
                    }
                    //
                    $answers['reviews'][$review['sid']]['agree'] += $agree;
                    $answers['reviews'][$review['sid']]['neutral'] += $neutral;
                    $answers['reviews'][$review['sid']]['disagree'] += $disagree;
                    //
                    $answers['employees'][$answ['reviewee_sid']]['agree'] += $agree;
                    $answers['employees'][$answ['reviewee_sid']]['neutral'] += $neutral;
                    $answers['employees'][$answ['reviewee_sid']]['disagree'] += $disagree;
                }
            }
        }
        //
        if(!empty($answers['reviews'])){
            foreach($answers['reviews'] as $key => $review){
                //
                $total = $review['agree'] + $review['disagree'] + $review['neutral'];
                //
                $answers['reviews'][$key]['agree'] = ceil(($review['agree'] * 100) / $total);
                $answers['reviews'][$key]['neutral'] = ceil(($review['neutral'] * 100) / $total);
                $answers['reviews'][$key]['disagree'] = ceil(($review['disagree'] * 100) / $total);
            }
        }
        //
        if(!empty($answers['employees'])){
            foreach($answers['employees'] as $key => $review){
                //
                $total = $review['agree'] + $review['disagree'] + $review['neutral'];
                //
                $answers['employees'][$key]['agree'] = ceil(($review['agree'] * 100) / $total);
                $answers['employees'][$key]['neutral'] = ceil(($review['neutral'] * 100) / $total);
                $answers['employees'][$key]['disagree'] = ceil(($review['disagree'] * 100) / $total);
            }
        }
        //
        return $answers;
    }
    
    
    //
    function getMyReviews(
        $companyId,
        $employeeId
    ){
        // Get all reviews for report
        $this->db
        ->select('
            performance_management.sid, 
            performance_management.review_title,
            performance_management_reviewers.reviewer_sid
        ')
        ->from('performance_management')
        ->join('performance_management_reviewers', 'performance_management_reviewers.review_sid = performance_management.sid')
        ->where('performance_management_reviewers.reviewee_sid', $employeeId)
        ->where('performance_management.company_sid', $companyId);
        //
        return $this->db->get()->result_array();
    }

    //
    function getReviewTitles($companyId){
        // Get all reviews for report
        return $this->db
        ->select('sid, review_title')
        ->from('performance_management')
        ->where('company_sid', $companyId)
        ->where('is_draft', 0)
        ->where('is_archived', 0)
        ->get()
        ->result_array();
    }


    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


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
=======
>>>>>>> 2798fc44... Added review part of Perfoemance management


    //
    function getMyGoals(){
        return [];
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