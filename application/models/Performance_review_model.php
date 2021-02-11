<?php

class Performance_review_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Fetch templates by company Id 
     * Created on: 10/11/2019
     * 
     * @param Integer $companySid
     * @param String  $title
     * 
     * @return Array
     */
    function templatesByCompanyId(
        $companySid,
        $limit,
        $post = []
    ) {
        //
        $r = [
            'Records' => [],
            'Count' => 0
        ];
        //
        $start = $post['Page'] == 1 ? 0 : (($post['Page'] - 1) * $limit);

        $this->db
            ->select('
            portal_review_questionnaire_types.sid,
            portal_review_questionnaire_types.creator_sid,
            portal_review_questionnaire_types.title,
            portal_review_questionnaire_types.description,
            portal_review_questionnaire_types.status,
            portal_review_questionnaire_types.created_at,
            users.first_name,
            users.last_name,
            users.access_level,
            users.pay_plan_flag,
            users.pay_plan_flag,
            users.access_level_plus, pay_plan_flag,
            users.is_executive_admin,
            users.job_title
        ')
            ->from('portal_review_questionnaire_types')
            ->join('users', 'users.sid = portal_review_questionnaire_types.creator_sid', 'inner')
            ->order_by('portal_review_questionnaire_types.sid', 'DESC')
            ->limit($limit, $start)
            ->where('portal_review_questionnaire_types.company_sid', $companySid);
        // If searched by title
        if ($post['Query'] != '') $this->db->like('portal_review_questionnaire_types.title', $post['Query']);
        if ($post['Status'] != '' && $post['Status'] != '-1') $this->db->like('portal_review_questionnaire_types.status', $post['Status']);
        //
        $result =
            $this->db
            ->order_by('portal_review_questionnaire_types.is_default', 'ASC')
            ->order_by('portal_review_questionnaire_types.title', 'ASC')
            ->get();
        //
        $r['Records'] = $result->result_array();
        $result    = $result->free_result();
        //
        if ($post['Page'] == 1) {
            $this->db
                ->from('portal_review_questionnaire_types')
                ->join('users', 'users.sid = portal_review_questionnaire_types.creator_sid', 'inner')
                ->where('portal_review_questionnaire_types.company_sid', $companySid);
            // If searched by title
            if ($post['Query'] != '') $this->db->like('portal_review_questionnaire_types.title', $post['Query']);
            if ($post['Status'] != '' && $post['Status'] != '-1') $this->db->like('portal_review_questionnaire_types.status', $post['Status']);
            //
            $r['Count'] = $this->db->count_all_results();
        }
        //
        return $r;
    }


    //
    function doTitleExists($main, $companySid)
    {
        $this->db->where('title', $main['title'])
            ->where('company_sid', $companySid);
        if (isset($main['sid'])) {
            $this->db->where('sid <> ', $main['sid']);
        }
        $this->db->limit(1);
        return $this->db->count_all_results('portal_review_questionnaire_types');
    }

    //
    function insertTemplate($ins)
    {
        $this->db->insert("portal_review_questionnaire_types", $ins);
        return $this->db->insert_id();
    }


    //
    function insertTemplateQuestion($ins)
    {
        $this->db->insert("portal_review_questionnaire", $ins);
        return $this->db->insert_id();
    }

    //
    function getTemplateById($id)
    {
        $r = [
            'main' => [],
            'questions' => []
        ];
        //
        $a = $this->db
            ->select('sid, title, description, status')
            ->where('sid', $id)
            ->get('portal_review_questionnaire_types');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) {
            return false;
        }
        //
        $r['main'] = $b;
        // Get questions
        $a = $this->db
            ->select('
            title,
            description,
            question_type,
            sort_order,
            label_question,
            not_applicable, 
            scale,
            sid
        ')
            ->where('portal_review_questionnaire_type_sid', $b['sid'])
            ->order_by('sort_order', 'ASC')
            ->get('portal_review_questionnaire');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (count($b)) {
            foreach ($b as $question) {
                $t = [];
                $t['sid'] = $question['sid'];
                $t['question'] = $question['title'];
                $t['includeNA'] = $question['not_applicable'] == 0 ? false : true;
                $t['ratingLabels'] = json_decode($question['label_question'], true);
                $t['useLabels'] = count($t['ratingLabels']) > 0 ? true : false;
                $t['description'] = $question['description'];
                $t['ratingScale'] = $question['scale'];
                $t['type'] = $question['question_type'];
                //
                $r['questions'][] = $t;
            }
        }
        //
        return $r;
    }


    //
    function updateTemplate($updt, $sid)
    {
        $this->db
            ->where('sid', $sid)
            ->update("portal_review_questionnaire_types", $updt);
        return true;
    }

    //
    function updateTemplateQuestion($updt, $sid)
    {
        $this->db
            ->where('sid', $sid)
            ->update("portal_review_questionnaire", $updt);
        return true;
    }

    //
    function dropTemplateQuestions(
        $parentId
    ) {
        $this->db->where('portal_review_questionnaire_type_sid', $parentId)
            ->delete('portal_review_questionnaire');
    }


    /**
     * Fetch reviews by company Id 
     * Created on: 10/11/2019
     * 
     * @param Integer $companySid
     * @param String  $title
     * 
     * @return Array
     */
    function reviewsByCompanyId(
        $companySid,
        $limit,
        $post = [],
        $employeeId,
        $isSuperAdmin
    ) {
        //
        $rp = [];
        //
        if(!$isSuperAdmin){
            $a = $this->db
            ->select('portal_review_sid')
            ->where('employee_sid', $employeeId)
            ->get('portal_review_reporting_managers');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            if(count($b)){
                $rp = array_column($b, 'portal_review_sid');
            }
        }
        //
        $r = [
            'Records' => [],
            'Count' => 0
        ];
        //
        if(!$isSuperAdmin && !count($rp)) return $r;
        //
        $start = $post['Page'] == 1 ? 0 : (($post['Page'] - 1) * $limit);

        $this->db
            ->select('
            portal_reviews.sid,
            portal_reviews.creator_sid,
            portal_reviews.title,
            portal_reviews.description,
            portal_reviews.status,
            portal_reviews.start_date,
            portal_reviews.end_date,
            portal_reviews.status,
            portal_reviews.is_archive,
            portal_reviews.created_at,
            users.first_name,
            users.last_name,
            users.access_level,
            users.pay_plan_flag,
            users.access_level_plus, pay_plan_flag,
            users.is_executive_admin,
            users.job_title
        ')
            ->from('portal_reviews')
            ->join('users', 'users.sid = portal_reviews.creator_sid', 'inner')
            ->order_by('portal_reviews.sid', 'DESC')
            ->limit($limit, $start)
            ->where('portal_reviews.company_sid', $companySid);
        //
        if (!$isSuperAdmin) {
            $this->db->where_in('portal_reviews.sid', $rp);
        }
        // If searched by title
        if ($post['Query'] != '') $this->db->like('portal_reviews.title', $post['Query']);
        if ($post['Status'] != '' && $post['Status'] != '-1') $this->db->like('portal_reviews.status', $post['Status']);
        //
        $result =
        $this->db
        ->order_by('portal_reviews.sid', 'DESC')
        ->order_by('portal_reviews.title', 'ASC')
        ->get();
        //
        $r['Records'] = $result->result_array();
        $result    = $result->free_result();
        
        //
        if ($post['Page'] == 1) {
            $this->db
                ->from('portal_reviews')
                ->join('users', 'users.sid = portal_reviews.creator_sid', 'inner')
                ->where('portal_reviews.company_sid', $companySid);
            if (!$isSuperAdmin) {
                $this->db->where_in('portal_reviews.sid', $rp);
            }
            // If searched by title
            if ($post['Query'] != '') $this->db->like('portal_reviews.title', $post['Query']);
            if ($post['Status'] != '' && $post['Status'] != '-1') $this->db->like('portal_reviews.status', $post['Status']);
            //
            $r['Count'] = $this->db->count_all_results();
        }
        //
        return $r;
    }


    //
    function getTemplates($companySid)
    {
        $a = $this->db
            ->select('sid, title')
            ->where('status', 1)
            ->where('company_sid', $companySid)
            ->order_by('title', 'ASC')
            ->get('portal_review_questionnaire_types');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function getReviewees(
        $companySid,
        $employeeSid
    ) {
        $r = [
            'All' => [],
            'SB' => [],
            'Departments' => []
        ];
        // All
        $a = $this->db
            ->select('sid, first_name, last_name, access_level, access_level_plus, pay_plan_flag, is_executive_admin, job_title')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->order_by('LOWER(CONCAT(first_name, last_name))', 'ASC', false)
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!count($b)) return false;
        //
        $r['All'] = $b;
        //
        $all = [];
        //
        foreach ($r['All'] as $employee) $all[$employee['sid']] = $employee;
        //
        $a = $this->db
            ->select('
                departments_employee_2_team.employee_sid,
                departments_management.sid,
                departments_management.name as department_name,
                departments_management.supervisor,
                departments_team_management.name as team_name,
                departments_team_management.team_lead
            ')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
            ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
            ->where('departments_management.status', 1)
            ->where('departments_management.is_deleted', 0)
            ->where('departments_team_management.status', 1)
            ->where('departments_team_management.is_deleted', 0)
            ->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!count($b)) return $r;
        //
        $c = [];
        //
        foreach ($b as $d) {
            if (!isset($c[$d['department_name']])) $c[$d['department_name']] = [];
            if (!isset($c[$d['department_name']][$d['team_name']])) $c[$d['department_name']][$d['team_name']] = [
                'TeamLeadId' => $d['supervisor'],
                'SupervisorId' => $d['team_lead'],
                'TeamId' => $d['sid'],
                'TeamName' => $d['team_name'],
                'Employees' => []
            ];
            //
            if (isset($all[$d['employee_sid']]))
                $c[$d['department_name']][$d['team_name']]['Employees'][] = $all[$d['employee_sid']];
            //
        }
        //
        $r['Departments'] = $c;
        //
        foreach ($c as $d) {
            foreach ($d as $e) {
                if ($e['TeamLeadId'] == $employeeSid || $e['SupervisorId'] == $employeeSid) $r['SB'] = array_merge($r['SB'], $e['Employees']);
            }
        }
        //
        $r['SB'] = array_unique($r['SB'], SORT_REGULAR);
        //
        return $r;
    }


    //
    function insertReview($ins)
    {
        //
        $this->db->insert('portal_reviews', $ins);
        return $this->db->insert_id();
    }

    //
    function updateReview($ins, $sid)
    {
        //
        $this->db
        ->where('sid', $sid)
        ->update('portal_reviews', $ins);
    }


    //
    function insertReviewQuestion($ins)
    {
        //
        $this->db->insert('portal_review_questions', $ins);
        return $this->db->insert_id();
    }
    
    //
    function updateReviewQuestion($ins, $sid)
    {
        //
        $this->db
        ->where('sid', $sid)
        ->update('portal_review_questions', $ins);
    }


    //
    function insertReviewReviewees($ins)
    {
        //
        $this->db->insert('portal_review_employees', $ins);
        return $this->db->insert_id();
    }

    //
    function insertReviewDepartment($ins)
    {
        //
        $this->db->insert('portal_review_departments', $ins);
        return $this->db->insert_id();
    }
    
    
    //
    function checkAndInsertReviewDepartment($ins)
    {
        if(
            !$this->db
            ->where('portal_review_sid', $ins['portal_review_sid'])
            ->where('department_sid', $ins['department_sid'])
            ->count_all_results('portal_review_departments')
        ){
            //
            $this->db->insert('portal_review_departments', $ins);
            return $this->db->insert_id();
        }
    }

    //
    function insertReviewConductor($ins)
    {
        //
        $this->db->insert('portal_review_conductors', $ins);
        return $this->db->insert_id();
    }


    //
    function startReview($Id)
    {
        //
        $this->db
            ->where('sid', $Id)
            ->update('portal_reviews', [
                'status' => 'started'
            ]);

        $this->db
            ->where('portal_review_sid', $Id)
            ->update('portal_review_employees', [
                'is_started' => 1
            ]);
        //
        return true;
    }

    //
    function endReview($Id)
    {
        //
        $this->db
            ->where('sid', $Id)
            ->update('portal_reviews', [
                'status' => 'ended'
            ]);
        $this->db
            ->where('portal_review_sid', $Id)
            ->update('portal_review_employees', [
                'is_started' => 0
            ]);
        //
        return true;
    }

    //
    function endEmployeeReview($Id)
    {
        $this->db
            ->where('sid', $Id)
            ->update('portal_review_employees', [
                'is_started' => 0
            ]);
        //
        return true;
    }

    //
    function startEmployeeReview($Id)
    {
        $this->db
            ->where('sid', $Id)
            ->update('portal_review_employees', [
                'is_started' => 1
            ]);
        //
        return true;
    }

    //
    function getReviewById($id, $indexes = [])
    {
        //
        if (!count($indexes)) $indexes = [
            'detail' => true, 
            'questions' => true, 
            'reviewees' => true, 
            'departments' => true,
            'reporting_managers' => true
        ];
        $r = [
            'main' => [],
            'questions' => [],
            'reviewees' => [],
            'reviewed' => [
                'total' => 0,
                'completed' => 0
            ],
            'feedback' => [
                'total' => 0,
                'completed' => 0
            ]
        ];
        //
        $a = $this->db
            ->select('sid, title, description, status, start_date, end_date, review_type')
            ->where('sid', $id)
            ->get('portal_reviews');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) {
            return false;
        }
        //
        $r['main'] = $b;
        //
        if (isset($indexes['questions'])) {
            // Get questions
            $a = $this->db
                ->select('
                title,
                description,
                question_type,
                sort_order,
                label_question,
                not_applicable, 
                scale,
                sid
            ')
                ->where('portal_review_sid', $b['sid'])
                ->order_by('sort_order', 'ASC')
                ->get('portal_review_questions');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            if (count($b)) {
                foreach ($b as $question) {
                    $t = [];
                    $t['sid'] = $question['sid'];
                    $t['question'] = $question['title'];
                    $t['includeNA'] = $question['not_applicable'] == 0 ? false : true;
                    $t['ratingLabels'] = json_decode($question['label_question'], true);
                    $t['useLabels'] = count($t['ratingLabels']) > 0 ? true : false;
                    $t['description'] = $question['description'];
                    $t['ratingScale'] = $question['scale'];
                    $t['type'] = $question['question_type'];
                    //
                    $r['questions'][] = $t;
                }
            }
        }

        // Get Employees
        if (isset($indexes['reviewees'])) {
            $a = $this->db
                ->select('
                    portal_review_employees.sid,
                    portal_review_employees.is_started,
                    portal_review_conductors.is_completed,
                    portal_review_conductors.conductor_sid,
                    portal_review_employees.employee_sid,
                    eusers.first_name as efirst_name,
                    eusers.last_name as elast_name,
                    eusers.access_level as eaccess_level,
                    eusers.access_level_plus as eaccess_level_plus,
                    eusers.is_executive_admin as eis_executive_admin,
                    eusers.job_title as ejob_title,
                    eusers.pay_plan_flag as epay_plan_flag,
                    users.first_name,
                    users.email,
                    users.last_name,
                    users.access_level,
                    users.access_level_plus,
                    users.pay_plan_flag,
                    users.is_executive_admin,
                    users.job_title
                ')
                ->join('users as eusers', 'eusers.sid = portal_review_employees.employee_sid', 'inner')
                ->join('portal_review_conductors', 'portal_review_conductors.portal_review_employee_sid = portal_review_employees.sid', 'inner')
                ->join('users', 'users.sid = portal_review_conductors.conductor_sid', 'inner')
                ->where('portal_review_employees.portal_review_sid', $id)
                ->get('portal_review_employees');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            $r['reviewees'] = $b;
            //
            if(count($b)) {
                $r['reviewed']['total'] = count($b);
                foreach($b as $rew) if($rew['is_completed'] == 1) $r['reviewed']['completed']++;
            }
        }
        // Get Departments
        if(isset($indexes['departments'])){
            $a = $this->db
            ->select('department_sid')
            ->where('portal_review_sid', $r['main']['sid'])
            ->get('portal_review_departments')
            ->row_array();
            //
            $r['department'] = count($a) ? $a['department_sid'] : false;
        }
        // Get Reporting Manegers
        if(isset($indexes['reporting_managers'])){
            //
            $r['reporting_managers'] = [];
            $r['reporting_manager_feedback'] = [];
            //
            $a = $this->db
            ->select('employee_sid, sid')
            ->where('portal_review_sid', $r['main']['sid'])
            ->get('portal_review_reporting_managers')
            ->result_array();
            //
            $d = [];
            //
            if(count($a)){
                //
                foreach($a as $c) $d[$c['sid']] = $c['employee_sid'];
                //
                $r['reporting_managers'] = array_column($a, 'employee_sid');
                //
                $a = $this->db
                ->select('employee_sid, feedback_content, rate, portal_review_reporting_manager_sid')
                ->where_in('portal_review_reporting_manager_sid', array_column($a, 'sid'))
                ->get('portal_review_manager_feedback');
                //
                $b = $a->result_array();
                $a = $a->free_result();
                //
                if(count($b)){
                    //
                    $r['feedback']['total'] = count($b);
                    foreach($b as $rew) {
                        $key = $rew['employee_sid'].'-'.$d[$rew['portal_review_reporting_manager_sid']];
                        $r['reporting_manager_feedback'][$key] = $rew;
                        if(!empty($rew['feedback_content'])) $r['feedback']['completed']++;
                    }
                }
            }
        }
        //
        if (!isset($indexes['detail'])) $r['main'] = [];
        //
        if($r['reviewed']['total'] == $r['reviewed']['completed']) $r['main']['status'] = 'Completed';

        // Handle review and feedback completion
        //
        return $r;
    }

    //
    function getAssignedReviews(
        $mySid,
        $limit,
        $post = []
    ) {
        //
        $r = [
            'Records' => [],
            'Count' => 0
        ];
        //
        $start = $post['Page'] == 1 ? 0 : (($post['Page'] - 1) * $limit);
        //
        $a = $this->db
            ->select('
            portal_review_employees.sid,
            portal_review_employees.is_started,
            portal_review_conductors.is_completed,
            eusers.first_name as efirst_name,
            eusers.last_name as elast_name,
            eusers.access_level as eaccess_level,
            eusers.access_level_plus as eaccess_level_plus,
            eusers.is_executive_admin as eis_executive_admin,
            eusers.job_title as ejob_title,
            eusers.pay_plan_flag as epay_plan_flag,
            users.first_name,
            users.last_name,
            users.access_level,
            users.access_level_plus,
            users.pay_plan_flag,
            users.is_executive_admin,
            users.job_title
        ')
            ->join('users as eusers', 'eusers.sid = portal_review_employees.employee_sid', 'inner')
            ->join('portal_review_conductors', 'portal_review_conductors.portal_review_employee_sid = portal_review_employees.sid', 'inner')
            ->join('users', 'users.sid = portal_review_conductors.conductor_sid', 'inner')
            ->where('portal_review_conductors.conductor_sid', $mySid)
            ->where('portal_review_employees.is_started', 1)
            ->order_by('portal_review_conductors.is_completed', 'ASC')
            ->limit($limit, $start)
            ->get('portal_review_employees');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        $r['Records'] = $b;
        //
        if ($start == 0) {
            //
            $r['Count'] = $this->db
                ->join('users as eusers', 'eusers.sid = portal_review_employees.employee_sid', 'inner')
                ->join('portal_review_conductors', 'portal_review_conductors.portal_review_employee_sid = portal_review_employees.sid', 'inner')
                ->join('users', 'users.sid = portal_review_conductors.conductor_sid', 'inner')
                ->where('portal_review_conductors.conductor_sid', $mySid)
                ->where('portal_review_employees.is_started', 1)
                ->order_by('portal_review_conductors.is_completed', 'ASC')
                ->count_all_results('portal_review_employees');
        }
        //
        return $r;
    }

    //
    function getEmployeeReviewQuestions($conductorEmployeeId, $mySid)
    {
        $a = $this->db
            ->select('portal_review_sid, employee_sid')
            ->where('sid', $conductorEmployeeId)
            ->get('portal_review_employees');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) return false;
        //
        $review = $this->getReviewById($b['portal_review_sid']);
        //
        $review['reviewee_sid'] = $b['employee_sid'];
        //
        if (!count($review['questions'])) return $review;
        //
        $employeeSid = $b['employee_sid'];
        //
        foreach ($review['questions'] as $k => $question) {
            $a = $this->db
                ->select()
                ->where('portal_review_question_sid', $question['sid'])
                ->where('conductor_tbl_sid', $mySid)
                ->where('employee_tbl_sid', $employeeSid)
                ->get('portal_review_answers');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            $review['questions'][$k]['answer'] = $b;
        }
        //
        return $review;
    }

    //
    function insertReportingManager($ins){
        $this->db->insert('portal_review_reporting_managers', $ins);
        return $this->db->insert_id();
    }

    //
    function addEmployeeManagerForFeedback($ins){
        $this->db->insert('portal_review_manager_feedback', $ins);
        return $this->db->insert_id();
    }
    
    //
    function checkAndInsertReportingManager($ins){
        $a = $this->db
            ->select('sid')
            ->where('portal_review_sid', $ins['portal_review_sid'])
            ->where('employee_sid', $ins['employee_sid'])
            ->get('portal_review_reporting_managers');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if(isset($b['sid'])) return $b['sid'];
        //
        return $this->insertReportingManager($ins);
    }
    
    
    //
    function checkAndAddEmployeeManagerForFeedback($ins){
        //
        if(
           ! $this->db
            ->where('portal_review_reporting_manager_sid', $ins['portal_review_reporting_manager_sid'])
            ->where('employee_sid', $ins['employee_sid'])
            ->count_all_results('portal_review_manager_feedback')
        ){
            return $this->addEmployeeManagerForFeedback($ins);
        }
    }
   
    //
    function insertAnswer($ins){
        $this->db->insert('portal_review_answers', $ins);
        return $this->db->insert_id();
    }

    //
    function  updateAnswer($upd, $sid){
        $this->db
        ->where('sid', $sid)
        ->update('portal_review_answers', $upd);
    }

    //
    function getPendingReviewCount($mySid, $total = false){
        //
        $r =  $this->db
            ->join('users as eusers', 'eusers.sid = portal_review_employees.employee_sid', 'inner')
            ->join('portal_review_conductors', 'portal_review_conductors.portal_review_employee_sid = portal_review_employees.sid', 'inner')
            ->join('users', 'users.sid = portal_review_conductors.conductor_sid', 'inner')
            ->where('portal_review_conductors.conductor_sid', $mySid)
            ->where('portal_review_employees.is_started', 1)
            ->where('portal_review_conductors.is_completed', 0)
            ->order_by('portal_review_conductors.is_completed', 'ASC')
            ->count_all_results('portal_review_employees');
        //
        if(!$total) return $r;
        return ['Pending' => $r, 'Total' => $this->db
            ->join('users as eusers', 'eusers.sid = portal_review_employees.employee_sid', 'inner')
            ->join('portal_review_conductors', 'portal_review_conductors.portal_review_employee_sid = portal_review_employees.sid', 'inner')
            ->join('users', 'users.sid = portal_review_conductors.conductor_sid', 'inner')
            ->where('portal_review_conductors.conductor_sid', $mySid)
            ->order_by('portal_review_conductors.is_completed', 'ASC')
            ->count_all_results('portal_review_employees')];
    }

    //
    function markAsCompleted($e){
        $a = $this->db
        ->select('sid')
        ->from('portal_review_employees')
        ->where('portal_review_sid', $e['review_sid'])
        ->where('employee_sid', $e['employee_sid'])
        ->get();
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if(!count($b)) return false;
        //
        $this->db
        ->where('portal_review_employee_sid', $b['sid'])
        ->where('conductor_sid', $e['condictor_sid'])
        ->update('portal_review_conductors', [
            'is_completed' => 1,
            'completed_at' => date('Y-m-d H:i:s')
        ]);
        //
        return 1;
    }


    //
    function getAnswersId($reviewId, $conductorId, $employeeId){
        $a = $this->db
        ->select('sid,portal_review_question_sid, text_answer, rating_answer, edit_flag')
        ->where('review_sid', $reviewId)
        ->where('conductor_tbl_sid', $conductorId)
        ->where('employee_tbl_sid', $employeeId)
        ->get('portal_review_answers');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;        
    }


    // Check and add templates
    function checkAndAddTemplates($companySid, $employeeSid){
        //
        $questions = $this->getPreTemplateQuestions('Peer Review');
        //
        $hasIt = $this->db
        ->where('company_sid', $companySid)
        ->where('creator_sid', $employeeSid)
        ->count_all_results('portal_review_questionnaire_types');
        //
        if(!$hasIt || !count($hasIt)){
            $templates = $this->getPreTemplates();
            foreach($templates as $template){
                //
                $template['company_sid'] = $companySid;
                $template['creator_sid'] = $employeeSid;
                //
                $this->db->insert('portal_review_questionnaire_types', $template);
                $templateId = $this->db->insert_id();
                //
                $questions = $this->getPreTemplateQuestions($template['title']);
                //
                foreach($questions as $question){
                    $question['portal_review_questionnaire_type_sid'] = $templateId;
                    unset($question['name']);
                    $this->db->insert('portal_review_questionnaire', $question);
                }
            }
        }
        //
        $_SESSION['PRD'] = true;
    }


    //
    function getPreTemplates(){
        return array(
            array('parent_sid' => NULL, 'title' => 'Check-In or Coaching Session', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 03:55:06', 'updated_at' => '2020-08-21 03:55:06'),
            array('parent_sid' => NULL, 'title' => 'Quartly Review', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 03:59:44', 'updated_at' => '2020-08-21 03:59:44'),
            array('parent_sid' => NULL, 'title' => 'Performance Review-Short Form', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 04:07:55', 'updated_at' => '2020-08-21 04:07:55'),
            array('parent_sid' => NULL, 'title' => 'Performance Conversation', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 04:18:38', 'updated_at' => '2020-08-21 04:18:38'),
            array('parent_sid' => NULL, 'title' => 'Peer Review', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 04:33:37', 'updated_at' => '2020-08-21 04:33:37'),
            array('parent_sid' => NULL, 'title' => 'Manager Review', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 04:47:35', 'updated_at' => '2020-08-21 04:47:35'),
            array('parent_sid' => NULL, 'title' => 'General Review', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 05:08:12', 'updated_at' => '2020-08-21 05:08:12'),
            array('parent_sid' => NULL, 'title' => 'Check-In or Coaching Session', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 10:55:06', 'updated_at' => '2020-08-21 10:55:06'),
            array('parent_sid' => NULL, 'title' => 'Quartly Review', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 10:59:44', 'updated_at' => '2020-08-21 10:59:44'),
            array('parent_sid' => NULL, 'title' => 'Performance Review-Short Form', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 11:07:55', 'updated_at' => '2020-08-21 11:07:55'),
            array('parent_sid' => NULL, 'title' => 'Performance Conversation', 'description' => '', 'is_default' => '0', 'is_custom' => '1', 'status' => '1', 'created_at' => '2020-08-21 11:18:38', 'updated_at' => '2020-08-21 11:18:38')
            
        );
    }

    //
    function getPreTemplateQuestions($templateName){
        $questions = array(
            array('title' => 'What challenges are you facing right now?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What progress have you made on your action plans or goals since we last met?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What went well?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What didn\'t go well?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What would you do differently next time?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'Where do you need help?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What did this person do well this quarter?', 'description' => '<p>Please elaborate on specific examples of where this person executed well and highlights on how they got things done.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:59:44', 'name' => 'Quartly Review'),
            array('title' => 'What could this person have done differently and why?', 'description' => '<p>Please elaborate on areas of improvement for this person along with specific examples.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:59:44', 'name' => 'Quartly Review'),
            array('title' => 'What should this person focus on next quarter?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:59:44', 'name' => 'Quartly Review'),
            array('title' => 'Please rate this person\'s output for the quarter.', 'description' => '<p>Please leave comments to explain your rating choice.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:59:44', 'name' => 'Quartly Review'),
            array('title' => 'Please add any additional feedback you\'d like to note for this person.', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 03:59:44', 'name' => 'Quartly Review'),
            array('title' => 'I go to this person when I need excellent results.', 'description' => '<p>Please add comments with details if available.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'Given what I know of this person\'s performance, I would always want him or her on my team.', 'description' => '<p>Please add comments with examples of this person&#39;s teamwork if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'Given I know of this person\'s performance, and if it were my money, I would award this person the highest possible compensation increase and bonus.', 'description' => '<p>Please elaborate on your answer with comments.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'This person is ready for promotion today.', 'description' => '<p>Please elaborate on your answer with comments.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'This person does not have issues which are blocking performance and require immediate attention.', 'description' => '<p>If issues exist, please provide specific examples and ways in which this person can improve.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'What is going well?', 'description' => '<p>Please describe accomplishments or area in which you&#39;ve had success during this review period.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can you continue that success?', 'description' => '<p>What can you do, what tools or skills do you need, or what environment do you need to continue to experience success in the areas mentioned above.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What would you like to have done better?', 'description' => '<p>Please describe any areas or situations in which you would like to do something differently next time.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What would you do differently?', 'description' => '<p>Please describe what you learned from the experiences that could have been better and how would you apply those lessons in future situations?</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can we (your manager. the company) help do those things differently or improve your ability to contribute,', 'description' => '<p>Please describe any tools, trainings, skills or changes to the work environment in general that would help improve performance tor you or the team.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What are your professional goals,', 'description' => '<p>Please describe what you would like to achieve or where you would like to be in your career progression in the coming years.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can the company help you achieve your professional goals?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'I can rely on this person to follow through and meet timelines.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:33:37', 'name' => 'Peer Review'),
            array('title' => 'This person has knowledge and expertise which is helpful to me and others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:33:37', 'name' => 'Peer Review'),
            array('title' => 'My interactions with this person are positive and productive.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:33:37', 'name' => 'Peer Review'),
            array('title' => 'This person demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:33:37', 'name' => 'Peer Review'),
            array('title' => 'Please add any additional feedback you\'d like to note for this person', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:33:37', 'name' => 'Peer Review'),
            array('title' => 'Understands the role of the department or team relates to the work of the company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Work of the department or team advances the objectives of the business and/or customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Has the knowledge, skills, and abilities needed to perform the functions of this job.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Reliably and consistently meets deadlines and completes work in a timely manner.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Personal work demonstrates the appropriate attention to detail.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Organizes and leads members of the team to accomplish goals.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Takes ownership of tasks and issues and sees them through completion.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Effectively solves problems.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Demonstrates good judgement and engages others when appropriate for making decisions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Adapts and embraces changes in situations or directions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Written communication is clear, concise, and compelling.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Presentations are engaging and informational.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Interpersonal communication is productive and in keeping with the organization\'s culture.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Listens well and incorporates the input of others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Contributes positively in meetings and team interactions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Receives feedback and coaching and adjusts appropriately.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Delivers feedback and coaching in a manner that achieves positive results.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Consistently meets with their direct reports for check-ins.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Delivers actionable and honest feedback in performance reviews in time with company schedule.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Partners well with fellow leaders and other departments to advance the objectives of the customer and company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Acts in the interest of the company and its objectives.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Areas for improvement', 'description' => '<p>Please describe any areas in which this person can show improvement and how that improvement may be achieved</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Summary', 'description' => '<p>Please provide a summary of this person&#39;s performance in this period.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 04:49:13', 'name' => 'Manager Review'),
            array('title' => 'Understands the position and how it relates to the work of the department and company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Has the knowledge, skills, and abilities needed to perform the functions of this job.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Reliably and consistently meets deadlines and completes work in a timely manner.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Work demonstrates the appropriate attention to detail.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Quality of work achieves  Me objectives of the business and/or customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Partners well with teammates and coworkers to accomplish goals.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Takes ownership of tasks and issues and sees them through completion.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Effectively solves problems.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Demonstrates good judgement and engages others when appropriate for making decisions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Adapts and embraces changes in situations or directions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Written communication is clear, concise, and compelling.', 'description' => '<p>Please select how drongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Presentations are engaging and informational.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Interpersonal communication is productive and in keeping with the organization\'s culture.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Listens well and incorporates the input of others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Contributes positively in meetings and team interactions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Receives feedback and coaching and adjusts appropriately.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Acts in the interest of the company and its objectives.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Areas for improvement', 'description' => '<p>Please describe any areas in which this person can show improvement an how that improvement may be achieved. </p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'Summary', 'description' => '<p>Please provide a summary of this person&#39;s performance in this period.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 05:08:12', 'name' => 'General Review'),
            array('title' => 'What challenges are you facing right now?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What progress have you made on your action plans or goals since we last met?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What went well?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What didn\'t go well?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What would you do differently next time?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'Where do you need help?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:55:06', 'name' => 'Check-In or Coaching Session'),
            array('title' => 'What did this person do well this quarter?', 'description' => '<p>Please elaborate on specific examples of where this person executed well and highlights on how they got things done.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:59:44', 'name' => 'Quartly Review'),
            array('title' => 'What could this person have done differently and why?', 'description' => '<p>Please elaborate on areas of improvement for this person along with specific examples.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:59:44', 'name' => 'Quartly Review'),
            array('title' => 'What should this person focus on next quarter?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:59:44', 'name' => 'Quartly Review'),
            array('title' => 'Please rate this person\'s output for the quarter.', 'description' => '<p>Please leave comments to explain your rating choice.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:59:44', 'name' => 'Quartly Review'),
            array('title' => 'Please add any additional feedback you\'d like to note for this person.', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 10:59:44', 'name' => 'Quartly Review'),
            array('title' => 'I go to this person when I need excellent results.', 'description' => '<p>Please add comments with details if available.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'Given what I know of this person\'s performance, I would always want him or her on my team.', 'description' => '<p>Please add comments with examples of this person&#39;s teamwork if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'Given I know of this person\'s performance, and if it were my money, I would award this person the highest possible compensation increase and bonus.', 'description' => '<p>Please elaborate on your answer with comments.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'This person is ready for promotion today.', 'description' => '<p>Please elaborate on your answer with comments.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'This person does not have issues which are blocking performance and require immediate attention.', 'description' => '<p>If issues exist, please provide specific examples and ways in which this person can improve.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:07:55', 'name' => 'Performance Review-Short Form'),
            array('title' => 'What is going well?', 'description' => '<p>Please describe accomplishments or area in which you&#39;ve had success during this review period.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can you continue that success?', 'description' => '<p>What can you do, what tools or skills do you need, or what environment do you need to continue to experience success in the areas mentioned above.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What would you like to have done better?', 'description' => '<p>Please describe any areas or situations in which you would like to do something differently next time.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What would you do differently?', 'description' => '<p>Please describe what you learned from the experiences that could have been better and how would you apply those lessons in future situations?</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can we (your manager. the company) help do those things differently or improve your ability to contribute,', 'description' => '<p>Please describe any tools, trainings, skills or changes to the work environment in general that would help improve performance tor you or the team.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'What are your professional goals,', 'description' => '<p>Please describe what you would like to achieve or where you would like to be in your career progression in the coming years.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'How can the company help you achieve your professional goals?', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:18:38', 'name' => 'Performance Conversation'),
            array('title' => 'I can rely on this person to follow through and meet timelines.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:33:37', 'name' => 'Peer Review'),
            array('title' => 'This person has knowledge and expertise which is helpful to me and others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:33:37', 'name' => 'Peer Review'),
            array('title' => 'My interactions with this person are positive and productive.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:33:37', 'name' => 'Peer Review'),
            array('title' => 'This person demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments with examples if applicable.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:33:37', 'name' => 'Peer Review'),
            array('title' => 'Please add any additional feedback you\'d like to note for this person', 'description' => '', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:33:37', 'name' => 'Peer Review'),
            array('title' => 'Understands the role of the department or team relates to the work of the company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Work of the department or team advances the objectives of the business and/or customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Has the knowledge, skills, and abilities needed to perform the functions of this job.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Reliably and consistently meets deadlines and completes work in a timely manner.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Personal work demonstrates the appropriate attention to detail.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Organizes and leads members of the team to accomplish goals.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Takes ownership of tasks and issues and sees them through completion.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Effectively solves problems.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Demonstrates good judgement and engages others when appropriate for making decisions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Adapts and embraces changes in situations or directions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Written communication is clear, concise, and compelling.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Presentations are engaging and informational.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Interpersonal communication is productive and in keeping with the organization\'s culture.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Listens well and incorporates the input of others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Contributes positively in meetings and team interactions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Receives feedback and coaching and adjusts appropriately.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Delivers feedback and coaching in a manner that achieves positive results.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Consistently meets with their direct reports for check-ins.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Delivers actionable and honest feedback in performance reviews in time with company schedule.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Partners well with fellow leaders and other departments to advance the objectives of the customer and company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Acts in the interest of the company and its objectives.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Areas for improvement', 'description' => '<p>Please describe any areas in which this person can show improvement and how that improvement may be achieved</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Summary', 'description' => '<p>Please provide a summary of this person&#39;s performance in this period.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 11:49:13', 'name' => 'Manager Review'),
            array('title' => 'Understands the position and how it relates to the work of the department and company.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Has the knowledge, skills, and abilities needed to perform the functions of this job.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Reliably and consistently meets deadlines and completes work in a timely manner.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Work demonstrates the appropriate attention to detail.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Quality of work achieves  Me objectives of the business and/or customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement antl add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Partners well with teammates and coworkers to accomplish goals.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Takes ownership of tasks and issues and sees them through completion.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Effectively solves problems.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Demonstrates good judgement and engages others when appropriate for making decisions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Adapts and embraces changes in situations or directions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Written communication is clear, concise, and compelling.', 'description' => '<p>Please select how drongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Presentations are engaging and informational.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Interpersonal communication is productive and in keeping with the organization\'s culture.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed.</p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Listens well and incorporates the input of others.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Contributes positively in meetings and team interactions.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Receives feedback and coaching and adjusts appropriately.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Acts in the interest of the company and its objectives.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Demonstrates commitment and care to the customer.', 'description' => '<p>Please select how strongly you agree/disagree with this statement and add comments as needed. </p>
', 'question_type' => 'text-rating', 'sort_order' => '1', 'label_question' => '[{"id":"0","text":"Strongly Disagree"},{"id":"1","text":"Disagree"},{"id":"2","text":"Neutral"},{"id":"3","text":"Agree"},{"id":"4","text":"Strongly Agree"}]', 'text' => '', 'not_applicable' => '0', 'scale' => '5', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Areas for improvement', 'description' => '<p>Please describe any areas in which this person can show improvement an how that improvement may be achieved. </p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review'),
            array('title' => 'Summary', 'description' => '<p>Please provide a summary of this person&#39;s performance in this period.</p>
', 'question_type' => 'text', 'sort_order' => '1', 'label_question' => '[]', 'text' => '', 'not_applicable' => '0', 'scale' => '0', 'labels_flag' => '0', 'updated_at' => '2020-08-21 12:08:12', 'name' => 'General Review')
        );
        //
        $r = [];
        //
        foreach($questions as $question){
            if($question['name'] == $templateName) $r[] = $question;
        }
        //
        return $r;
    }


    //
    function getReviewForCron($date){
        //
        $a = $this->db
        ->select('sid, start_date, end_date')
        ->where('start_date <= ', $date)
        ->where('is_archive', 0)
        ->get('portal_reviews');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function endReviews($sid){
        //
        $this->db
        ->where('sid', $sid)
        ->update('portal_reviews', ['status' => 0]);

        //
        $this->db
        ->where('portal_review_sid', $sid)
        ->update('portal_review_employees', ['is_started' => 0]);
    }

    //
    function startReviews($sid)
    {
        //
        $this->db
            ->where('sid', $sid)
            ->update('portal_reviews', ['status' => 1]);

        //
        $this->db
            ->where('portal_review_sid', $sid)
            ->update('portal_review_employees', ['is_started' => 1]);
    }


    //
    function getReviewForEmail($reviewSid){

    }

    function get_employee_detail($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_employee_all_reviews ($employee_sid) {
        $this->db->select('sid, portal_review_sid');
        $this->db->where('employee_sid', $employee_sid);
        $this->db->where('is_started', 1);
        $record_obj = $this->db->get('portal_review_employees');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_reviewers_count ($sid) {
        $this->db->select('sid, portal_review_sid');
        $this->db->where('portal_review_employee_sid', $sid);
        $this->db->from('portal_review_conductors');
        return $this->db->count_all_results();
    }

    function get_all_reviewers_names ($sid) {
        $this->db->select('conductor_sid');
        $this->db->where('portal_review_employee_sid', $sid);
        $record_obj = $this->db->get('portal_review_conductors');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $employee_name_string = '';
        if (!empty($record_arr)) {
            foreach ($record_arr as $key => $value) {
                $employee_detail = $this->get_employee_detail($value['conductor_sid']);
                $employee_name = remakeEmployeeName([
                    'first_name' => $employee_detail['first_name'],
                    'last_name' => $employee_detail['last_name'],
                    'pay_plan_flag' => $employee_detail['pay_plan_flag'],
                    'access_level' => $employee_detail['access_level'],
                    'access_level_plus' => $employee_detail['access_level_plus'],
                    'job_title' => $employee_detail['job_title'],
                ], true);

                $employee_name_string .= $employee_name.',<br>';
            }
        } 

        return $employee_name_string;
    }

    function get_review_info ($sid) {
        $this->db->select('title, status, start_date');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('portal_reviews');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_all_performance_reviewers ($sid) {
        $this->db->select('sid, conductor_sid, is_completed');
        $this->db->where('portal_review_employee_sid', $sid);
        $record_obj = $this->db->get('portal_review_conductors');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_active_employees_detail($parent_sid, $sid, $archive = 0, $order_by = 'first_name', $order = 'ASC') {
        
        $this->db->select('sid, first_name, last_name, pay_plan_flag, access_level, access_level_plus, job_title');
        $this->db->where('parent_sid', $parent_sid);
        $this->db->where('active', '1');
        $this->db->where('terminated_status', 0);
        $this->db->where('archived', $archive);
        $this->db->where('sid != ' . $sid);
        $this->db->order_by($order_by, $order);
        $this->db->order_by('last_name', $order);
        $all_employees = $this->db->get('users')->result_array();
        return $all_employees;
    }

    function get_all_filter_reviews ($title, $conductor_sid, $employee_sid) {

        $this->db->select('portal_review_employees.sid, portal_review_employees.employee_sid, portal_review_employees.is_started, portal_reviews.sid as reviews_sid, portal_reviews.title, portal_reviews.start_date, portal_reviews.status');

        if ($employee_sid != 0) {
            $this->db->where('portal_review_employees.employee_sid', $employee_sid);
        }

        if ($conductor_sid != 0) {
            $this->db->where('portal_review_conductors.conductor_sid', $conductor_sid);
            $this->db->join('portal_review_conductors', 'portal_review_employees.sid = portal_review_conductors.portal_review_employee_sid');
        }

        if (!empty($title)) {
            $this->db->where('portal_reviews.title', $title);
        }
        
        // $this->db->group_start();
        // $this->db->where('portal_reviews.status', 1);
        // $this->db->where('portal_review_employees.is_started', 1);
        // $this->db->group_end();
        $this->db->join('portal_reviews', 'portal_review_employees.portal_review_sid = portal_reviews.sid');
        $record_obj = $this->db->get('portal_review_employees');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function insert_conductor ($data_to_insert) {
       $this->db->insert('portal_review_conductors', $data_to_insert);
    }

    function delete_conductor ($portal_review_employee_sid, $conductor_sid) {
        $this->db->where('portal_review_employee_sid', $portal_review_employee_sid);
        $this->db->where('conductor_sid', $conductor_sid);
        $this->db->delete('portal_review_conductors');
    }

    function get_all_reviews_titles ($company_sid) {
        $this->db->select('sid, title');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('portal_reviews');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_reviewees ($review_sid) {
        $this->db->select('employee_sid');
        $this->db->where('portal_review_sid', $review_sid);
        $record_obj = $this->db->get('portal_review_employees');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function insert_reviewee($review_sid, $reviewee_sid) {
        $data_to_insert = array();
        $data_to_insert['portal_review_sid'] = $review_sid;
        $data_to_insert['employee_sid'] = $reviewee_sid;
        $data_to_insert['is_deleted'] = 0;
        $data_to_insert['is_started'] = 0;

        $this->db->insert('portal_review_employees', $data_to_insert);
        return $this->db->insert_id();
    }

    function get_all_related_reviewees ($sid) {
        $this->db->select('sid');
        $this->db->where('portal_review_sid', $sid);
        $this->db->from('portal_review_employees');
        return $this->db->count_all_results();
    }

    function update_employee_review_status ($update_row, $data_to_update) {
        $this->db->where('sid', $update_row);
        $this->db->update('portal_review_employees', $data_to_update);
    }

    function get_review_title_by_sid ($sid) {
        $this->db->select('sid, title');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('portal_reviews');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    //
    function checkAndInsertReviewReviewees($ins){
        //
        $a = $this->db
        ->select('sid')
        ->where('portal_review_sid', $ins['portal_review_sid'])
        ->where('employee_sid', $ins['employee_sid'])
        ->get('portal_review_employees')
        ->row_array();
        //
        if($a && count($a)) return $a['sid'];
        //
        $this->db->insert('portal_review_employees', $ins);
        //
        return $this->db->insert_id();
    }
    
    
    //
    function checkAndInsertReviewConductor($ins){
        //
        $a = $this->db
        ->select('sid')
        ->where('portal_review_employee_sid', $ins['portal_review_employee_sid'])
        ->where('conductor_sid', $ins['conductor_sid'])
        ->get('portal_review_conductors')
        ->row_array();
        //
        if($a && count($a)) return $a['sid'];
        //
        $this->db->insert('portal_review_conductors', $ins);
        //
        return $this->db->insert_id();
    }



    /**
     * Get reviews for feedback for managers
     * 
     * @param $companySid  Integer
     * @param $employeeSid Integer
     * @param $post        Array
     * 
     * @return Array
     */
    function getReviewsForManagersForFeedback(
        $companySid,
        $employeeSid,
        $post
    ){
        // Get reviews id for managers
        $this->db
        ->select('
            portal_reviews.title,
            portal_review_reporting_managers.portal_review_sid,
            portal_review_manager_feedback.feedback_content
        ')
        ->where('portal_review_reporting_managers.employee_sid', $employeeSid)
        ->join('portal_review_reporting_managers', 'portal_review_reporting_managers.sid = portal_review_manager_feedback.portal_review_reporting_manager_sid', 'inner')
        ->join('portal_reviews', 'portal_reviews.sid = portal_review_reporting_managers.portal_review_sid', 'inner')
        ->join('portal_review_employees', 'portal_review_employees.employee_sid = portal_review_manager_feedback.employee_sid', 'inner');
        //
        $this->db
        ->group_start()
        ->where('portal_reviews.status', 'started')
        ->or_where('portal_review_employees.is_started', '1')
        ->group_end();
        //
        // If searched by title
        if ($post['Query'] != '') $this->db->like('portal_reviews.title', $post['Query']);
        //
        $a = $this->db->get('portal_review_manager_feedback');
        // Get records
        $reviewManagersR = $a->result_array();
        $a = $a->free_result();
        //
        $r = [];
        //
        if(count($reviewManagersR)){
            foreach($reviewManagersR as $k => $v){
                //
                if(!isset($r[$v['portal_review_sid']]))  $r[$v['portal_review_sid']] = [
                    'sid' => $v['portal_review_sid'],
                    'title' => $v['title'],
                    'total' => 0,
                    'completed' => 0
                ];
                //
                $r[$v['portal_review_sid']]['total']++;
                //
                if(!empty($v['feedback_content'])) $r[$v['portal_review_sid']]['completed']++;
            }
        }
        //
        $r = array_values($r);
        //
        return $r;
    }

    //
    function saveFeedback($post){
        //
        $a = $this->db
        ->select('sid')
        ->where('portal_review_sid', $post['data']['reviewSid'])
        ->where('employee_sid', $post['data']['managerSid'])
        ->get('portal_review_reporting_managers');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        $this->db
        ->where('portal_review_reporting_manager_sid', $b['sid'])
        ->where('employee_sid', $post['data']['employeeSid'])
        ->update('portal_review_manager_feedback', [
            'feedback_content' => $post['data']['feedback'],
            'rate' => $post['data']['rate']
        ]);
    }

    //
    function getAnswerCount(
        $reviewSid, 
        $employeeSid,
        $conductorSids
    ){
        //
        return $this->db
        ->where_in('conductor_tbl_sid', $conductorSids)
        ->where('employee_tbl_sid', $employeeSid)
        ->where('review_sid', $reviewSid)
        ->count_all_results('portal_review_answers');
    }

    //
    function getFeedbackCount(
        $reviewSid,
        $employeeSid
    ){
        $this->db
        ->select('
            portal_review_manager_feedback.feedback_content
            
        ')
        ->where('portal_review_manager_feedback.employee_sid', $employeeSid)
        ->where_in('portal_reviews.sid', $reviewSid)
        ->join('portal_review_reporting_managers', 'portal_review_reporting_managers.sid = portal_review_manager_feedback.portal_review_reporting_manager_sid', 'inner')
        ->join('portal_reviews', 'portal_reviews.sid = portal_review_reporting_managers.portal_review_sid', 'inner');
        //
        $a = $this->db->get('portal_review_manager_feedback');
        // Get records
        $reviewManagersR = $a->result_array();
        $a = $a->free_result();
        //
        $r = [
            'total' => 0,
            'completed' => 0
        ];
        //
        if(!count($reviewManagersR)) return $r;
        //
        $r['total'] = count($reviewManagersR);
        //
        foreach($reviewManagersR as $v) if(!empty($v['feedback_content'])) $r['completed']++;
        //
        return $r;
    }

    //
    function getReportingManagersByReview(
        $reviewSid
    ){
        $a = $this->db
        ->select('sid')
        ->where('portal_review_sid', $reviewSid)
        ->get('portal_review_reporting_managers');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if(count($b)) $b = array_column($b, 'sid');
        //
        return $b;
    }

    //
    function getReport(
        $reviewIds, 
        $employeeSid
    ){
        $r = [
            'Title' => [['Reviews', 'Score']],
            'Rating' => [
                ['Rating', 'Score'],
                1 => ["Strongly Disagree", 0],
                2 => ["Disagree", 0],
                3 => ["Neutral", 0],
                4 => ["Agree", 0],
                5 => ["Strongly Agree", 0]
            ],
            'total' => 0
        ];
        //
        if(empty($reviewIds)) return $r;
        //
        $a =
        $this->db
        ->select('
            portal_review_answers.rating_answer,
            portal_review_answers.review_sid,
            portal_reviews.title
            ')
            ->join('portal_reviews', 'portal_reviews.sid = portal_review_answers.review_sid', 'inner')
            ->where_in('portal_review_answers.review_sid', $reviewIds)
            ->where('portal_review_answers.employee_tbl_sid', $employeeSid)
            ->where('portal_review_answers.rating_answer IS NOT NULL', null)
            ->where('portal_review_answers.rating_answer <> ', '')
            ->where('portal_review_answers.created_at >= ', date('Y-01-01 00:00:00'))
            ->get('portal_review_answers');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            // _e($b, true, true);
        if(count($b)){
            //
            foreach($b as $k => $v){
                //
                if(!isset($r['Title'][$v['title']])) $r['Title'][$v['title']] = [$v['title'], 1];
                //
                $r['total']++;
                $r['Rating'][$v['rating_answer']][1]++;
            }
            //
            foreach($r['Rating'] as $k => $v){
                if($k == 0) continue;
                $r['Rating'][$k][1] = ($v[1] * $r['total'] / 100);
            }
            //
            $r['Title'] = array_values($r['Title']);
        }
        //
        return $r;
    }
   
   
    //
    function getReportAll(
        $companySid
    ){
        $r = [
            'Title' => [['Reviews', 'Score']],
            'Review' => [
                ['Review', 'Score'],
                1 => ['Completed', 0],
                2 => ['Pending', 0]
            ],
            'Rating' => [
                ['Rating', 'Score'],
                1 => ["Strongly Disagree", 0],
                2 => ["Disagree", 0],
                3 => ["Neutral", 0],
                4 => ["Agree", 0],
                5 => ["Strongly Agree", 0]
            ],
            'total' => 0
        ];
        //
        $a =
        $this->db
        ->select('
            portal_review_answers.rating_answer,
            portal_review_answers.review_sid,
            portal_reviews.title
        ')
        ->join('portal_reviews', 'portal_reviews.sid = portal_review_answers.review_sid', 'inner')
        ->where_in('portal_reviews.company_sid', $companySid)
        ->where('portal_review_answers.rating_answer IS NOT NULL', null)
        ->where('portal_review_answers.rating_answer <> ', '')
        ->where('portal_review_answers.created_at >= ', date('Y-01-01 00:00:00'))
        ->get('portal_review_answers');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        // _e($b, true, true);
        if(count($b)){
            //
            foreach($b as $k => $v){
                //
                if(!isset($r['Title'][$v['title']])) $r['Title'][$v['title']] = [$v['title'], 1];
                //
                $r['total']++;
                $r['Rating'][$v['rating_answer']][1]++;
            }
            //
            foreach($r['Rating'] as $k => $v){
                if($k == 0) continue;
                $r['Rating'][$k][1] = ($v[1] * $r['total'] / 100);
            }
            //
            $r['Title'] = array_values($r['Title']);

            //
            $a = $this->db
            ->select('
                portal_reviews.title,
                portal_reviews.sid,
                portal_review_conductors.is_completed
            ')
            ->join('portal_review_employees', 'portal_review_employees.sid = portal_review_conductors.portal_review_employee_sid', 'inner')
            ->join('portal_reviews', 'portal_reviews.sid = portal_review_employees.portal_review_sid', 'inner')
            ->where('portal_reviews.company_sid', $companySid)
            ->where('portal_review_employees.is_started', 1)
            ->get('portal_review_conductors');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            if(count($b)){
                $d = [];
                foreach($b as $k => $v){
                    //
                    if(!isset($d[$v['sid']])) $d[$v['sid']] = [
                        'completed' => 0,
                        'pending' => 0,
                        'total' => 0
                    ];

                    //
                    if($v['is_completed'] == 1) $d[$v['sid']]['completed']++;
                    else  $d[$v['sid']]['pending']++;
                    $d[$v['sid']]['total']++;

                }
                //
                foreach($d as $v){
                    if($v['completed'] != $v['total']) $r['Review'][2][1]++;
                    else $r['Review'][1][1]++;
                    // $r['Review']['total']++;
                }
            }
        }
        return $r;
    }


    //
    function deleteReviewee(
        $reviewSid,
        $employeeSid
    ){  
        //
        $a = $this->db
        ->select('sid')
        ->where('portal_review_sid', $reviewSid)
        ->where('employee_sid', $employeeSid)
        ->get('portal_review_employees');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if(!count($b)) return false;
        //
        $sids = array_column($b, 'sid');
        // Delete from main employees table
        $this->db
        ->where_in('sid', $sids)
        ->delete('portal_review_employees');
        // Delete from conductors
        $this->db
        ->where_in('portal_review_employee_sid', $sids)
        ->delete('portal_review_conductors');
    }
}
