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
    private $PRQ = 'pm_review_questions';
    private $PRA = 'pm_review_answers';
    private $PRR = 'pm_review_reviewees';
    private $PRRS = 'pm_review_reviewers';
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
    function GetReviewRowById($reviewId, $company_sid, $columns = null){
        //
        if($reviewId == 0){
            return [];
        }
        //
        $query = $this->db
        ->select($columns === null ? "
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
            is_draft,
            review_runs as custom_runs,
            visibility_roles as roles,
            visibility_departments as departments,
            visibility_teams as teams,
            included_employees as included,
            excluded_employees as excluded,
            reviewers,
            questions,
        ": implode(',', $columns))
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
    
    /**
     * 
     */
    function UpdateReviewee($data, $where){
        $this->db
        ->where($where)
        ->update($this->PRR, $data);
    }

    /**
     * 
     */
    function insertReviewQuestions($data){
        $this->db->insert_batch($this->PRQ, $data);
    }
   
    /**
     * 
     */
    function insertReviewReviewees($data){
        $this->db->insert_batch($this->PRR, $data);
    }
    
    /**
     * 
     */
    function insertReviewReviewers($data){
        $this->db->insert_batch($this->PRRS, $data);
    }

    /**
     * 
     */
    function isManager($reviewee, $reviewer, $companyId){
        //
        $revieweeDT = [];
        //
        $revieweeDT = $this->employeeDT($reviewee, $revieweeDT);
        //
        if(in_array($reviewer, $revieweeDT['ReportingManagers'])){
            return 1;
        } else{
            return 0;
        }
    } 

    /**
     * 
     */
    function GetAllReviews($employeeId, $employeeRole, $plus, $companyId, $columns = null, $type = 'active'){
        //
        if(!$plus){
            $dt = $this->employeeDT($employeeId, []);
        }

        $this->db
        ->select(
            $columns ? $columns : "
            sid,
            review_title,
            description,
            share_feedback,
            is_archived,
            is_draft,
            review_start_date,
            review_end_date,
            review_end_date,
            created_at,
            updated_at,
            status
        ")
        ->where('company_sid', $companyId);
        //
        if($type == 'active'){
            $this->db
            ->where('is_archived', 0)
            ->where('is_draft', 0);
        }
        //
        if($type == 'archived'){
            $this->db
            ->where('is_archived', 1)
            ->where('is_draft', 0);
        }
        //
        if($type == 'draft'){
            $this->db
            ->where('is_archived', 0)
            ->where('is_draft', 1);
        }
        //
        if(!$plus){
            //
            $employeeRole = stringToSlug($employeeRole);
            //
            $this->db->group_start();
            $this->db->where("FIND_IN_SET('{$employeeRole}', visibility_roles) > 0", null, null);
            $this->db->or_where("FIND_IN_SET({$employeeId}, visibility_employees) > 0", null, null);
            //
            if(!empty($dt['Departments'])){
                foreach($dt['Departments'] as $department){
                    $this->db->or_where("FIND_IN_SET({$department}, visibility_departments) > 0", null, null);
                }
            }
            //
            if(!empty($dt['Teams'])){
                foreach($dt['Teams'] as $team){
                    $this->db->or_where("FIND_IN_SET({$team}, visibility_teams) > 0", null, null);
                }
            }
            //
            $this->db->group_end();
        }

        $query = $this->db->get($this->R);
        //
        $reviews = $query->result_array();
        //
        $query->free_result();
        //
        if(!empty($reviews)){
            foreach($reviews as $index => $review){
                $reviews[$index]['created_at'] = formatDateToDB($review['created_at'], 'Y-m-d H:i:s', 'M d Y, D H:i:s');
                $reviews[$index]['updated_at'] = formatDateToDB($review['updated_at'], 'Y-m-d H:i:s', 'M d Y, D H:i:s');
                $reviews[$index]['Reviewees'] = $this->GetReviewRevieews($review['sid']);
            }
        }
        //
        return $reviews;
    }

    /**
     * 
     */
    function GetEmployeeColumns($employeeIds, $columns = '*'){
        //
        $query = $this->db
        ->select(is_array($columns) ? implode(',', $columns) : $columns)
        ->where_in('sid', $employeeIds)
        ->get($this->U);
        //
        $employees = $query->result_array();
        //
        $query->free_result();
        //
        return $employees;
        
    }

    /**
     * 
     */
    function CheckAndStartEndReviewees($now, $reviewId){
        //
        $reviewees = $this->GetReviewRevieews($reviewId);
        //
        foreach($reviewees as $reviewee){
            //
            if($reviewee['start_date'] == $now && $reviewee['end_date'] > $now){
                $this->UpdateReviewee(
                    ['is_started' => "1"], 
                    [
                        'review_sid' => $reviewId,
                        'reviewee_sid' => $reviewee['reviewee_sid']
                    ]
                );
            }
            
            // Review will end
            if($reviewee['end_date'] <= $now){
                $this->UpdateReviewee(
                    ['is_started' => "0"], 
                    [
                        'review_sid' => $reviewId,
                        'reviewee_sid' => $reviewee['reviewee_sid']
                    ]
                );
            }
        }   
    }

    /**
     * 
     */
    function GetReviewsForCron(){
        $reviews =  $this->db
        ->order_by('sid', 'desc')
        ->where('is_draft', 0)
        ->where('is_archived', 0)
        ->where('frequency != ', 'onetime')
        ->get('pm_reviews')
        ->result_array();

        //
        if(!empty($reviews)){
            //
            $nr = [];
            //
            foreach($reviews as $index => $review){
                //
                if(empty($review['parent_review_sid'])){
                   //
                   $nr[$review['sid']]  = $review;
                   $nr[$review['sid']]['Cycles']  = [];
                }
            }

            //
            foreach($reviews as $index => $review){
                //
                if(!empty($review['parent_review_sid'])){
                   //
                   $nr[$review['parent_review_sid']]['Cycles'][]  = $review;
                }
            }
            //
            $reviews = $nr;
        }


        //
        return $reviews;
    }

    /**
     * 
     */
    function GetEmployeesForNotificationEmailByDays($days){
        //
        $date = date('Y-m-d H:i:s', strtotime("+{$days} days"));
        //
        $query = 
        $this->db
        ->select("
            {$this->R}.sid,
            {$this->R}.review_title,
            {$this->PRR}.reviewee_sid,
            {$this->PRR}.start_date,
            {$this->PRR}.end_date,
            {$this->PRRS}.is_manager,
            {$this->PRRS}.reviewer_sid,
            {$this->U}.first_name,
            {$this->U}.last_name,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
            reviewer.email as reviewer_email,
            {$this->R}.company_sid,
            company.CompanyName as company_name,
        ")
        ->from("{$this->PRRS}")
        ->join("{$this->PRR}", "{$this->PRR}.review_sid = {$this->PRRS}.review_sid AND {$this->PRR}.reviewee_sid = {$this->PRRS}.reviewee_sid", "inner")
        ->join("{$this->R}", "{$this->R}.sid = {$this->PRR}.review_sid", "inner")
        ->join("{$this->U}", "{$this->U}.sid = {$this->PRR}.reviewee_sid", "inner")
        ->join("{$this->U} as reviewer", "reviewer.sid = {$this->PRRS}.reviewer_sid", "inner")
        ->join("{$this->U} as company", "company.sid = {$this->R}.company_sid", "inner")
        ->where("{$this->PRR}.is_started", 1)
        ->where("{$this->PRRS}.is_completed", 0)
        ->where("{$this->PRR}.end_date", $date)
        ->get();
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        return $result;
    }

    /**
     * 
     */
    function getMyReviewCounts($companyId, $employeeId){
        //
        $query = 
        $this->db
        ->select("
            {$this->PRRS}.is_manager,
            {$this->PRRS}.is_completed
        ")
        ->from("{$this->PRRS}")
        ->join("{$this->PRR}", "{$this->PRR}.review_sid = {$this->PRRS}.review_sid AND {$this->PRR}.reviewee_sid = {$this->PRRS}.reviewee_sid", "inner")
        ->join("{$this->R}", "{$this->R}.sid = {$this->PRR}.review_sid", "inner")
        ->where("{$this->PRR}.is_started", 1)
        ->where("{$this->PRRS}.reviewer_sid", $employeeId)
        ->where("{$this->R}.company_sid", $companyId)
        ->get();
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        $returnArray = [
            'Reviews' => 0,
            'Feedbacks' => 0,
            'Total' => 0
        ];
        //
        if(!empty($result)){
            foreach($result as $record){
                //
                $returnArray[$record['is_manager'] ? 'Feedbacks' : 'Reviews']++;
                $returnArray['Total']++;
            }
        }
        //
        return $returnArray;
    }
    
    /**
     * 
     */
    function GetReviewsByTypeForDashboard($employeeId, $type){
        //
        $query = 
        $this->db
        ->select("
            {$this->R}.sid,
            {$this->PRR}.reviewee_sid,
            {$this->PRRS}.reviewer_sid,
            {$this->PRR}.start_date,
            {$this->PRR}.end_date,
            ".(getUserFields())."
        ")
        ->from("{$this->PRRS}")
        ->join("{$this->PRR}", "{$this->PRR}.review_sid = {$this->PRRS}.review_sid AND {$this->PRR}.reviewee_sid = {$this->PRRS}.reviewee_sid", "inner")
        ->join("{$this->R}", "{$this->R}.sid = {$this->PRR}.review_sid", "inner")
        ->join("{$this->U}", "{$this->U}.sid = {$this->PRR}.reviewee_sid", "inner")
        ->where("{$this->PRR}.is_started", 1)
        ->where("{$this->PRRS}.reviewer_sid", $employeeId)
        ->where("{$this->PRRS}.is_manager", $type)
        ->get();
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        return $result;
    }

    /**
     * 
     */
    function GetReviewByReviewer($reviewId, $revieweeId, $reviewerId){
        //
        $query =
        $this->db
        ->select("
            {$this->R}.review_title,
            {$this->R}.share_feedback,
            {$this->PRR}.start_date,
            {$this->PRR}.end_date,
            {$this->PRR}.is_started,
            {$this->PRRS}.is_manager,
            {$this->PRRS}.is_completed,
            {$this->U}.first_name,
            {$this->U}.last_name,
            reviewer.first_name as reviewer_first_name,
            reviewer.last_name as reviewer_last_name,
        ")
        ->join($this->PRR, "{$this->PRR}.reviewee_sid = {$this->PRRS}.reviewee_sid and {$this->PRR}.review_sid = {$this->PRRS}.review_sid", "inner")
        ->join($this->R, "{$this->R}.sid = {$this->PRR}.review_sid", "inner")
        ->join($this->U, "{$this->U}.sid = {$this->PRRS}.reviewee_sid", "inner")
        ->join("{$this->U} as reviewer", "reviewer.sid = {$this->PRRS}.reviewer_sid", "inner")
        ->where("{$this->PRR}.review_sid", $reviewId)
        ->where("{$this->PRRS}.reviewee_sid", $revieweeId)
        ->where("{$this->PRRS}.reviewer_sid", $reviewerId)
        ->get($this->PRRS);
        //
        $result = $query->row_array();
        //
        $query->free_result();
        //
        if(!empty($result)){
            //
            $result = array_merge($result, $this->GetReviewerAnswers($reviewId, $revieweeId, $reviewerId));
        }
        //
        return $result;
    }

    /**
     * 
     */
    function GetReviewById($reviewId){
        //
        $this->db
        ->select(
            "
            sid,
            review_title,
            description,
            share_feedback,
            is_archived,
            is_draft,
            review_start_date,
            review_end_date,
            review_end_date,
            created_at,
            updated_at,
            status
        ")
        ->where('sid', $reviewId);
        //
        $query = $this->db->get($this->R);
        //
        $reviews = $query->result_array();
        //
        $query->free_result();
        //
        if(!empty($reviews)){
            foreach($reviews as $index => $review){
                $reviews[$index]['created_at'] = formatDateToDB($review['created_at'], 'Y-m-d H:i:s', 'M d Y, D H:i:s');
                $reviews[$index]['updated_at'] = formatDateToDB($review['updated_at'], 'Y-m-d H:i:s', 'M d Y, D H:i:s');
                $reviews[$index]['Reviewees'] = $this->GetReviewRevieews($review['sid']);
            }
        }
        //
        return $reviews;
    }
    
    /**
     * 
     */
    function CheckAndSaveAnswer($reviewId, $reviweeId, $reviewerId, $questionId, $answer){
        //
        if(isset($answer['completed'])){
            $this->db
            ->where('review_sid', $reviewId)
            ->where('reviewee_sid', $reviweeId)
            ->where('reviewer_sid', $reviewerId)
            ->update($this->PRRS, ['is_completed' => 1]);
        }
        // 
        $array = [];
        $array['multiple_choice'] = isset($answer['multiple_choice']) ? $answer['multiple_choice'] : null;
        $array['rating'] = isset($answer['rating']) ? $answer['rating'] : null;
        $array['text_answer'] = isset($answer['text']) ? $answer['text'] : null;
        $array['updated_at'] = date("Y-m-d H:i:s", strtotime("now"));
        if(isset($answer['attachments'])){

            $array['attachments'] = json_encode($answer['attachments']);
        }
        //
        if(
            $this->db
            ->where('review_sid', $reviewId)
            ->where('reviewee_sid', $reviweeId)
            ->where('reviewer_sid', $reviewerId)
            ->where('question_sid', $questionId)
            ->count_all_results($this->PRA)
        ){  
            $array['is_modified'] = 1;
            //
            $this->db
            ->where('review_sid', $reviewId)
            ->where('reviewee_sid', $reviweeId)
            ->where('reviewer_sid', $reviewerId)
            ->where('question_sid', $questionId)
            ->update($this->PRA, $array);
            return $questionId;
        }
        //
        $array['review_sid'] = $reviewId;
        $array['reviewee_sid'] = $reviweeId;
        $array['reviewer_sid'] = $reviewerId;
        $array['question_sid'] = $questionId;
        $array['created_at'] = date("Y-m-d H:i:s", strtotime("now"));
        //
        $this->db->insert($this->PRA, $array);
        return $questionId;
    }


    /**
     * 
     */
    function GetReviewReviewers($reviewId, $revieweeId){
        //
        $query = $this->db
        ->select('reviewer_sid')
        ->where('review_sid', $reviewId)
        ->where('reviewee_sid', $revieweeId)
        ->get($this->PRRS);
        //
        $reviewers = $query->result_array();
        //
        $query->free_result();
        //
        return array_column($reviewers, 'reviewer_sid');
    }
    

    /**
     * 
     */
    function CheckAndInsertReviewee($reviewId, $revieweeId){
        //
        if(
            $this->db->where('review_sid', $reviewId)
            ->where('reviewee_sid', $revieweeId)
            ->count_all_results($this->PRR)
        ){
            return true;
        }
        //
        $this->db->insert($this->PRR, [
            'review_sid' => $reviewId,
            'reviewee_sid' => $revieweeId,
            'created_at' => date('Y-m-d H:i:s', strtotime('now')),
            'updated_at' => date('Y-m-d H:i:s', strtotime('now')),
            'is_started' => 0,
            'start_date' => '',
            'end_date' => ''
        ]);
        return $this->db->insert_id();
    }
    
    /**
     * 
     */
    function UpdateRevieweeReviewers($insertArray){
        //
        $this->db->insert_batch($this->PRRS, $insertArray);
        return $this->db->insert_id();
    }
    
    
    /**
     * 
     */
    function MarkReviewAsArchived($reviewId){
        //
        $this->db->where('sid',$reviewId)
        ->update($this->R, ['is_archived' => 1]);
    }
    
    /**
     * 
     */
    function MarkReviewAsActive($reviewId){
        //
        $this->db->where('sid',$reviewId)
        ->update($this->R, ['is_archived' => 0]);
    }
    
    /**
     * 
     */
    function StopReview($reviewId){
        //
        $this->db->where('sid',$reviewId)
        ->update($this->R, ['status' => 'ended']);
        
        //
        $this->db->where('review_sid',$reviewId)
        ->update($this->PRR, ['is_started' =>0]);
    }
    
    /**
     * 
     */
    function StartReview($reviewId){
        //
        $this->db->where('sid',$reviewId)
        ->update($this->R, ['status' => 'started']);
        
        //
        $this->db->where('review_sid',$reviewId)
        ->update($this->PRR, ['is_started' =>1]);
    }
    
    
    /**
     * 
     */
    function StartReviweeReview($reviewId, $revieweeId){
        //
        $this->db->where('sid',$reviewId)
        ->update($this->R, ['status' => 'started']);
        //
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->update($this->PRR, ['is_started' =>1]);
    }
    
    /**
     * 
     */
    function StopReviweeReview($reviewId, $revieweeId){
        //
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->update($this->PRR, ['is_started' =>0]);
    }
    
    /**
     * 
     */
    function DeleteRevieweeReviewers($reviewId, $revieweeId, $reviewerIds){
        //
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->where_in('reviewer_sid',$reviewerIds)
        ->delete($this->PRRS);
    }
    
    /**
     * 
     */
    function AddRevieweeReviewers($reviewId, $revieweeId, $reviewerIds){
        //
        foreach($reviewerIds as $reviewerId){
            //
            $this->db
            ->insert($this->PRRS, [
                'review_sid' => $reviewId,
                'reviewee_sid' => $revieweeId,
                'reviewer_sid' => $reviewerId,
                'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                'is_manager' => 0,
                'is_completed' => 1
            ]);
        }
    }
    
    /**
     * 
     */
    function UpdateRevieweeDates($reviewId, $revieweeId, $post){
        //
        $this->db
        ->where('review_sid',$reviewId)
        ->where('reviewee_sid',$revieweeId)
        ->update(
            $this->PRR, [
                'start_date' => formatDateToDB($post['start_date']),
                'end_date' => formatDateToDB($post['start_date'])
            ]
        );
    }

    /**
     * 
     */
    function GetCompletedReviews($companyId){
        //
        $query = 
        $this->db
        ->select("
            {$this->R}.review_title,
            {$this->U}.first_name,
            {$this->U}.last_name,
            reviewee.first_name as reviewee_first_name,
            reviewee.last_name as reviewee_last_name,
            {$this->PRRS}.review_sid,
            {$this->PRRS}.reviewee_sid,
            {$this->PRRS}.reviewer_sid,
            {$this->PRRS}.is_completed,
            {$this->PRRS}.is_manager
        ")
        ->from($this->PRRS)
        ->join($this->R, "{$this->R}.sid = {$this->PRRS}.review_sid", "inner")
        ->join($this->PRR, "{$this->PRR}.review_sid = {$this->R}.sid", "inner")
        ->join($this->U, "{$this->U}.sid = {$this->PRRS}.reviewer_sid", "inner")
        ->join("{$this->U} as reviewee", "reviewee.sid = {$this->PRRS}.reviewee_sid", "inner")
        ->where("{$this->R}.status <>", 'pending')
        ->where("{$this->R}.company_sid", $companyId)
        ->where("{$this->PRR}.is_started", 1)
        ->get();
        //
        $records = $query->result_array();
        $query->free_result();
        //
        $rt = [
            'Total' => 0,
            'Completed' => 0,
            'Pending' => 0,
            'Records' => []
        ];
        //
        if(!empty($records)){
            //
            $t = [];
            $completed = 0;
            $total = 0;
            $pending = 0;
            //
            foreach($records as $record){
                //
                $key = $record['review_sid'].'_'.$record['reviewee_sid'].'_'.$record['reviewer_sid'];
                //
                if(!isset($t[$key])){
                    $t[$key] = $record;
                    //
                    $total++;
                    //
                    if($record['is_completed']){
                        $completed++;
                    } else{
                        $pending++;
                    }
                }
            }
            //
            $rt['Total'] = $total;
            $rt['Pending'] = $pending;
            $rt['Completed'] = $completed;
            $rt['Records'] = array_values($t);
        }
        //
        return $rt;
    }

    /**
     * 
     */
    function GetReviewCountByStatus($companyId){
        //
        $rt = [
            'Started' => 0,
            'Pending' => 0,
            'Ended' => 0,
            'Draft' => 0,
            'Archived' => 0
        ];
        //
        $rt['Started'] = 
        $this->db
        ->where("{$this->R}.status", 'started')
        
        ->where("{$this->R}.is_draft <>", 1)
        ->where("{$this->R}.is_archived <>", 1)
        ->where("{$this->R}.company_sid", $companyId)
        ->count_all_results($this->R);

        //
        $rt['Pending'] = 
        $this->db
        ->where("{$this->R}.status", 'pending')
        
        ->where("{$this->R}.is_draft <>", 1)
        ->where("{$this->R}.is_archived <>", 1)
        ->where("{$this->R}.company_sid", $companyId)
        ->count_all_results($this->R);

        //
        $rt['Ended'] = 
        $this->db
        ->where("{$this->R}.status", 'ended')
        ->where("{$this->R}.is_draft <>", 1)
        ->where("{$this->R}.is_archived <>", 1)
        ->where("{$this->R}.company_sid", $companyId)
        ->count_all_results($this->R);

        //
        $rt['Draft'] = 
        $this->db
        ->where("{$this->R}.is_draft", 1)
        ->where("{$this->R}.is_archived <>", 1)
        ->where("{$this->R}.company_sid", $companyId)
        ->count_all_results($this->R);
        
        //
        $rt['Archived'] = 
        $this->db
        ->where("{$this->R}.is_draft <>", 1)
        ->where("{$this->R}.is_archived", 1)
        ->where("{$this->R}.company_sid", $companyId)
        ->count_all_results($this->R);
        //
        return $rt;
    }
    
    

    
    
    











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


    /**
     * 
     */
    private function GetReviewRevieews($reviewId){
        //
        $query =
        $this->db
        ->select("
            {$this->PRR}.reviewee_sid,
            {$this->PRR}.start_date,
            {$this->PRR}.end_date,
            {$this->PRR}.is_started,
            {$this->PRRS}.reviewer_sid,
            {$this->PRRS}.is_manager,
            {$this->PRRS}.is_completed
        ")
        ->join($this->PRRS, "{$this->PRRS}.reviewee_sid = {$this->PRR}.reviewee_sid", "inner")
        ->where("{$this->PRR}.review_sid", $reviewId)
        ->where("{$this->PRRS}.review_sid", $reviewId)
        ->get($this->PRR);
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        if(!empty($result)){
            //
            $t = [];
            //
            foreach($result as $row){
                //
                if(!isset($t[$row['reviewee_sid']])){
                    $t[$row['reviewee_sid']] = [
                        "reviewee_sid" => $row['reviewee_sid'],
                        "start_date" => $row['start_date'],
                        "end_date" => $row['end_date'],
                        "is_started" => $row['is_started'],
                        "reviewers" => []
                    ];
                }
                //
                $t[$row['reviewee_sid']]['reviewers'][$row['reviewer_sid']] = [
                    "reviewer_sid" => $row['reviewer_sid'],
                    "is_manager" => $row['is_manager'],
                    "is_completed" => $row['is_completed']
                ];
            }
            //
            $result = $t;
            //
            unset($t);
            //
            foreach($result as $index => $row){
               //
               foreach($row['reviewers'] as $index2 => $reviewer){
                    //
                    $result[$index]['reviewers'][$index2] = array_merge($result[$index]['reviewers'][$index2], $this->GetReviewerAnswers($reviewId, $row['reviewee_sid'], $reviewer['reviewer_sid']));
               }
            }
        }
        //
        return $result;
    }

    /**
     * 
     */
    private function GetReviewerAnswers($reviewId, $revieweeId, $reviewerId){
        //
        $ra = ['QA' => [], 'Feedback' => []];
        //
        $query = 
        $this->db
        ->select("
            {$this->PRQ}.question,
            {$this->PRQ}.sid,
            {$this->PRA}.multiple_choice,
            {$this->PRA}.text_answer,
            {$this->PRA}.rating,
            {$this->PRA}.attachments,
            {$this->PRA}.is_modified,
            {$this->PRA}.updated_at
        ")
        ->from($this->PRQ)
        ->join($this->PRA, "
            {$this->PRQ}.sid = {$this->PRA}.question_sid AND 
            {$this->PRA}.reviewer_sid = {$reviewerId} AND 
            {$this->PRA}.reviewee_sid = {$revieweeId} AND 
            {$this->PRA}.review_sid = {$reviewId}
        ", "left")
        ->where("{$this->PRQ}.review_sid", $reviewId)
        ->order_by("{$this->PRQ}.sid", "ASC")
        ->get();
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        if(!empty($result)){
            //
            $t = [];
            //
            foreach($result as $row){
                //
                $question = json_decode($row['question'], true);
                //
                unset(
                    $question['id'],
                    $question['not_applicable'],
                    $question['video_help']
                );
                //
                $t[] = [
                    'question_id' => $row['sid'],
                    'question' => $question,
                    'attachments' => $row['attachments'],
                    'answer' => [
                        'multiple_choice' => $row['multiple_choice'],
                        'rating' => $row['rating'],
                        'text' => $row['text_answer'],
                        'is_modified' => $row['is_modified'],
                        'updated_at' => $row['updated_at']
                    ]
                ];
            }
            //
            $ra['QA'] = $t;
        } else{
            $ra['QA'] = [];
        }
        //
        $query = 
        $this->db
        ->select("
            {$this->PRA}.multiple_choice,
            {$this->PRA}.text_answer,
            {$this->PRA}.rating,
            {$this->PRA}.attachments,
            {$this->PRA}.is_modified,
            {$this->PRA}.updated_at
        ")
        ->where("{$this->PRA}.review_sid", $reviewId)
        ->where("{$this->PRA}.reviewee_sid", $revieweeId)
        ->where("{$this->PRA}.reviewer_sid", $reviewerId)
        ->where("{$this->PRA}.question_sid", 0)
        ->get($this->PRA);
        //
        $feedback = $query->row_array();
        //
        if(!empty($feedback)){
            $ra['Feedback'] = [
                'attachments' => $feedback['attachments'],
                'multiple_choice' => $feedback['multiple_choice'],
                'rating' => $feedback['rating'],
                'text' => $feedback['text_answer'],
                'is_modified' => $feedback['is_modified'],
                'updated_at' => $feedback['updated_at']
            ];
        } else{
            $ra['Feedback'] = [
                'attachments' => '',
                'multiple_choice' => '',
                'rating' => '',
                'text' => '',
                'is_modified' => '',
                'updated_at' => ''
            ];
        }
        //
        return $ra;
    }

    
   


    
}