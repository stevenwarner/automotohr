<?php
class Timeoff_model extends CI_Model
{
    private $ids;
    function __construct($ids = array())
    {
        parent::__construct();
        $this->ids = $ids;
    }

    /*
    ***********************************************************************
    TIME OFF FUNCTIONS
    ***********************************************************************
    */


//     /**
//      * Fetch plans list by company sid
//      * @param Integer $companySid
//      * @return Array
//      */
//     function getPlanListByCompany($companySid)
//     {
//         $subQuery =
//             $this->db
//             ->select('timeoff_plan_list_sid')
//             ->from('timeoff_plans')
//             ->where('company_sid', $companySid)
//             ->order_by('sort_order', 'ASC')
//             ->get_compiled_select();
//         //
//         $result = $this->db
//             ->select('plan_year, plan_month, sid as timeoff_plan_list_sid')
//             ->from('timeoff_plan_list')
//             ->order_by('concat(plan_year, plan_month)', 'ASC', false)
//             // ->where_in('sid', $subQuery, false)
//             ->get();
//         //
//         $plans = $result->result_array();
//         $result = $result->free_result();
//         //
//         return $plans;
//     }

//     /**
//      * Fetch plan creators list by company sid
//      * @param Integer $companySid
//      * @return Array
//      */
//     function getPlanEmployeeListByCompany($companySid)
//     {
//         $subQuery =
//             $this->db
//             ->select('creator_sid')
//             ->from('timeoff_plans')
//             ->where('company_sid', $companySid)
//             ->order_by('sort_order', 'ASC')
//             ->get_compiled_select();
//         //
//         $result = $this->db
//             ->select('CONCAT(users.first_name," ",users.last_name) as full_name, sid as employee_id')
//             ->from('users')
//             ->where_in('sid', $subQuery, false)
//             ->get();
//         //
//         $creators = $result->result_array();
//         $result = $result->free_result();
//         //
//         return $creators;
//     }

//     /**
//      * Get all plans by company sid
//      * @param Integer $companySid
//      * @param Integer $page
//      * @param Integer $limit
//      * @param Array $formpost
//      * @return Array
//      */
//     function getPlansByCompany(
//         $companySid,
//         $page,
//         $limit,
//         $formpost
//     ) {
//         // Set start
//         $start = $page == 1 ? 0 : (($page * $limit) - $limit);
//         $this->db
//             ->select('
//             timeoff_plan_list.plan_year,
//             timeoff_plan_list.plan_month,
//             timeoff_plans.sid as plan_id,
//             timeoff_plans.timeoff_plan_list_sid as plan_list_id,
//             timeoff_plans.status,
//             timeoff_plans.is_archived,
//             timeoff_plans.is_default,
//             timeoff_plans.allowed_timeoff,
//             CONCAT(users.first_name," ",users.last_name) as full_name,
//             users.profile_picture as img,
//             users.employee_number,
//             users.sid as employee_id,
//             users.employee_number,
//             timeoff_settings.default_timeslot,
//             timeoff_formats.slug as format,
//             DATE_FORMAT(timeoff_plans.created_at, "%m-%d-%Y") as created_at
//         ')
//             ->from('timeoff_plans')
//             ->join('timeoff_plan_list', 'timeoff_plans.timeoff_plan_list_sid = timeoff_plan_list.sid', 'left')
//             ->join('timeoff_settings', 'timeoff_plans.company_sid = timeoff_settings.company_sid', 'left')
//             ->join('users', 'timeoff_plans.creator_sid = users.sid', 'left')
//             ->join('timeoff_formats', 'timeoff_settings.timeoff_format_sid = timeoff_formats.sid', 'left')
//             ->where('timeoff_plans.company_sid', $companySid)
//             ->where('timeoff_plans.is_archived', $formpost['fetchType'] == 'active' ? 0 : 1)
//             ->order_by('sort_order', 'ASC')
//             ->limit($limit, $start);

//         // Apply search filter
//         if ($formpost['planSid'] != '' && $formpost['planSid'] != 0) $this->db->where('timeoff_plans.timeoff_plan_list_sid', $formpost['planSid']);
//         if ($formpost['employeeSid'] != '' && $formpost['employeeSid'] != 0) $this->db->where('timeoff_plans.creator_sid', $formpost['employeeSid']);
//         if ($formpost['status'] != '' && $formpost['status'] != -1) $this->db->where('timeoff_plans.status', $formpost['status']);
//         if ($formpost['startDate'] != '' && $formpost['endDate']) $this->db->where('DATE_FORMAT(timeoff_plans.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['startDate']) . ' AND ' . ($formpost['endDate']) . '');

//         $result = $this->db->get();
//         //
//         $plans = $result->result_array();
//         $result = $result->free_result();
//         //
//         if (!sizeof($plans)) {
//             return array('Plans' => array(), 'Count' => 0);
//         }
//         //
//         if ($page == 1) {
//             $this->db
//                 ->from('timeoff_plans')
//                 ->where('timeoff_plans.company_sid', $companySid)
//                 ->where('timeoff_plans.is_archived', $formpost['fetchType'] == 'active' ? 0 : 1);
//             // Apply search filter
//             if ($formpost['planSid'] != '' && $formpost['planSid'] != 0) $this->db->where('timeoff_plans.timeoff_plan_list_sid', $formpost['planSid']);
//             if ($formpost['employeeSid'] != '' && $formpost['employeeSid'] != 0) $this->db->where('timeoff_plans.creator_sid', $formpost['employeeSid']);
//             if ($formpost['status'] != '' && $formpost['status'] != -1) $this->db->where('timeoff_plans.status', $formpost['status']);
//             if ($formpost['startDate'] != '' && $formpost['endDate']) $this->db->where('DATE_FORMAT(timeoff_plans.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['startDate']) . ' AND ' . ($formpost['endDate']) . '');
//             //
//             $result = $this->db->count_all_results();
//             return array('Plans' => $plans, 'Count' => $result);
//         }
//         return array('Plans' => $plans);
//     }





//     /**
//      * Fetch plans list by company sid
//      * @param Integer $formatSid
//      * @return Array
//      */
//     function checkAndInsertPlanList($planYear, $planMonth)
//     {
//         //
//         $result = $this->db
//             ->select('sid')
//             ->from('timeoff_plan_list')
//             ->where('plan_year', $planYear)
//             ->where('plan_month', $planMonth)
//             ->get();
//         //
//         $plan = $result->row_array();
//         $result = $result->free_result();
//         //
//         if (sizeof($plan)) return $plan['sid'];
//         //
//         $this->db->insert('timeoff_plan_list', array('plan_year' => $planYear, 'plan_month' => $planMonth, 'status' => 1, 'is_custom' => 1));
//         return $this->db->insert_id();
//     }

//     /**
//      * Check configuration by company_sid and pto_plan_sid
//      *
//      * @param $companySid              Integer
//      * @param $ptoConfigurationListId  String
//      * @param $ptoConfigurationId      String
//      * Default is 'FALSE'
//      *
//      * @return Array|Bool
//      */
//     function checkConfigurationForCompany($companySid, $ptoConfigurationListId, $ptoConfigurationId = FALSE)
//     {
//         $this->db
//             ->from('timeoff_plans')
//             ->where('company_sid', $companySid)
//             ->where('timeoff_plan_list_sid', $ptoConfigurationListId)
//             ->order_by('sort_order', 'ASC');
//         if ($ptoConfigurationId != FALSE) $this->db->where("sid <> $ptoConfigurationId", null);
//         return $this->db->count_all_results();
//     }

//     /**
//      * Insert configuration
//      *
//      * @param $post Array
//      *
//      * @return Array|Bool
//      */
//     function insertCompanyPlan($post)
//     {
//         // Set insert array
//         $insertArray = array();
//         $insertArray['timeoff_plan_list_sid'] = $post['planSid'];
//         $insertArray['status'] = $post['status'];
//         $insertArray['creator_sid'] = $post['employeeSid'];
//         $insertArray['company_sid'] = $post['companySid'];
//         $insertArray['is_archived'] = $post['isArchived'];
//         $insertArray['allowed_timeoff'] = $post['allowed_minutes'];
//         // Insert PTO Group
//         $this->db->insert('timeoff_plans', $insertArray);
//         $insertId = $this->db->insert_id();
//         if (!$insertId) return false;
//         // Add history
//         $this->db->insert('timeoff_plan_history', array(
//             'timeoff_plan_sid' => $insertId,
//             'allowed_timeoff' => $insertArray['allowed_timeoff'],
//             'timeoff_plan_list_sid' => $insertArray['timeoff_plan_list_sid'],
//             'user_sid' => $post['employeeSid']
//         ));
//         return $insertId;
//     }

//     /**
//      * Update configuration
//      *
//      * @param Array   $formpost
//      *
//      * @return VOID
//      */
//     function updateCompanyPlan($formpost)
//     {
//         // Set update array
//         $updateArray = array();
//         $updateArray['timeoff_plan_list_sid'] = $formpost['planListSid'];
//         $updateArray['status'] = $formpost['status'];
//         $updateArray['creator_sid'] = $formpost['employeeSid'];
//         $updateArray['company_sid'] = $formpost['companySid'];
//         $updateArray['is_archived'] = $formpost['isArchived'];
//         $updateArray['allowed_timeoff'] = $formpost['allowed_minutes'];
//         // Update PTO Group
//         $this->db
//             ->where('sid', $formpost['recordSid'])
//             ->update('timeoff_plans', $updateArray);
//         // Add history
//         $this->db->insert('timeoff_plan_history', array(
//             'timeoff_plan_sid' => $formpost['recordSid'],
//             'allowed_timeoff' => $updateArray['allowed_timeoff'],
//             'timeoff_plan_list_sid' => $updateArray['timeoff_plan_list_sid'],
//             'user_sid' => $formpost['employeeSid']
//         ));
//     }

//     /**
//      * Update configuration
//      *
//      * @param Array   $formpost
//      *
//      * @return VOID
//      */
//     function updateCompanyPlanWithData($sid, $d)
//     {
//         // Update PTO Group
//         $this->db
//             ->where('sid', $sid)
//             ->update('timeoff_plans', $d);
//     }

//     /**
//      * Fetch plans list by company sid
//      * @param Integer $companySid
//      * @param Bool    $doExplode Optional
//      * @return String|Bool
//      */
//     function getTimeOffSetting($companySid, $column = 'sid')
//     {
//         //
//         $result = $this->db
//             ->select($column)
//             ->from('timeoff_settings')
//             ->where('timeoff_settings.company_sid', $companySid)
//             ->get();
//         //
//         $format = $result->row_array();
//         $result = $result->free_result();
//         //
//         return !sizeof($format) ? false : $format[$column];
//     }


    


    

    

//     function get_email_to_supervisor_status($company_sid)
//     {
//         $this->db->where('company_sid', $company_sid);
//         $this->db->select('send_email_to_supervisor');
//         $query = $this->db->get('timeoff_settings');
//         return $query->result_array();
//     }

//     /**
//      * Get company plans for policies
//      * @param Integer $companySid
//      * @param Integer $status Optional
//      * @param Integer $archived Optional
//      *
//      * @return Array
//      */
//     function getCompanyPlans($companySid, $status = 1, $archived = 0)
//     {
//         $result = $this->db
//             ->select('
//             timeoff_plan_list.plan_year,
//             timeoff_plan_list.plan_month,
//             timeoff_plans.sid as plan_id,
//             timeoff_plans.allowed_timeoff,
//             timeoff_formats.slug as format,
//             timeoff_settings.default_timeslot
//         ')
//             ->from('timeoff_plans')
//             ->join('timeoff_plan_list', 'timeoff_plans.timeoff_plan_list_sid = timeoff_plan_list.sid', 'left')
//             ->join('timeoff_settings', 'timeoff_plans.company_sid = timeoff_settings.company_sid', 'left')
//             ->join('timeoff_formats', 'timeoff_settings.timeoff_format_sid = timeoff_formats.sid', 'left')
//             ->where('timeoff_plans.company_sid', $companySid)
//             ->where('timeoff_plans.is_archived', $archived)
//             ->where('timeoff_plans.status', $status)
//             ->order_by('(timeoff_plan_list.plan_year * 12) + timeoff_plan_list.plan_month', 'ASC', false)
//             ->get();
//         //
//         $plans = $result->result_array();
//         $result = $result->free_result();
//         //
//         return $plans;
//     }

//     /**
//      * Check policy
//      *
//      * @param Array $formpost
//      * @return Array
//      */
//     function companyPolicyExists($formpost)
//     {
//         $this->db
//             ->from('timeoff_policies')
//             ->where('company_sid', $formpost['companySid'])
//             ->where('title', $formpost['policy']);
//         if (isset($formpost['policySid'])) $this->db->where("sid <> " . ($formpost['policySid']) . "", null);
//         return $this->db->count_all_results();
//     }
    

//     /**
//      * Get a single policy by id and company sid
//      * @param Array $formpost
//      * @return Array
//      */
//     function getCompanyPolicyById($formpost)
//     {
//         $result = $this->db
//             ->select('
//             timeoff_policies.sid as policy_id,
//             timeoff_policies.title as policy_title,
//             timeoff_policies.assigned_employees,
//             timeoff_policies.is_archived,
//             timeoff_policies.status,
//             timeoff_policies.reset_policy,
//             timeoff_policies.fmla_range,
//             timeoff_policies.sort_order,
//             timeoff_policies.for_admin,
//             timeoff_policies.is_unlimited,
//             timeoff_policies.is_included,
//             timeoff_policies.policy_start_date,
//             timeoff_policy_accural.*,
//             timeoff_settings.default_timeslot,
//             timeoff_formats.slug as format
//         ')
//             ->from('timeoff_policies')
//             ->join('timeoff_policy_accural', 'timeoff_policies.sid = timeoff_policy_accural.timeoff_policy_sid', 'left')
//             ->join('timeoff_settings', 'timeoff_policies.company_sid = timeoff_settings.company_sid', 'left')
//             ->join('timeoff_formats', 'timeoff_settings.timeoff_format_sid = timeoff_formats.sid', 'left')
//             ->where('timeoff_policies.company_sid', $formpost['companySid'])
//             ->where('timeoff_policies.sid', $formpost['policySid'])
//             ->where('timeoff_policies.is_default <> 1', null)
//             ->limit(1)
//             ->get();
//         //
//         $policy = $result->row_array();
//         $result = $result->free_result();
//         //
//         if (!sizeof($policy)) return array();
//         $policy['types'] = [];
//         // Get Categories
//         $a = $this->db
//             ->select('timeoff_category_sid')
//             ->where('timeoff_policy_sid', $policy['policy_id'])
//             ->get('timeoff_policy_categories');
//         //
//         $c = $a->result_array();
//         $a = $a->free_result();
//         //
//         if (sizeof($c)) foreach ($c as $v0) $policy['types'][] = $v0['timeoff_category_sid'];
//         //
//         if ($policy['is_unlimited'] == 1) {
//             $policy['plans'] = array();
//             return $policy;
//         }
//         // Get plans
//         $result = $this->db
//             ->select('
//             timeoff_policy_plans.plan_title,
//             timeoff_policy_plans.accrual_rate
//         ')
//             ->from('timeoff_policy_plans')
//             ->where('timeoff_policy_plans.timeoff_policy_sid', $formpost['policySid'])
//             ->order_by('CONVERT(SUBSTRING_INDEX(timeoff_policy_plans.plan_title,' - ',-1),UNSIGNED INTEGER)', 'ASC')
//             ->get();
//         //
//         $plans = $result->result_array();
//         $result = $result->free_result();
//         //
//         $policy['plans'] = $plans;
//         return $policy;
//     }

    
//     /**
//      * Check for policy overwrite
//      *
//      * @param Array $formpost
//      * @return Array
//      */
//     function companyPolicyOverwriteExists($formpost)
//     {
//         $this->db
//             ->from('timeoff_policy_overwrite')
//             ->where('employee_sid', $formpost['assignedEmployees'])
//             ->where('timeoff_policy_sid', $formpost['policy']);
//         if (isset($formpost['policyOverwriteSid'])) $this->db->where('sid <> ' . ($formpost['policyOverwriteSid']) . '', null);
//         return $this->db->count_all_results();
//     }

//     /**
//      * Insert policy overwrite
//      *
//      * @param  Array $formpost
//      * @return Integer
//      */
//     function insertPolicyOverwrite($formpost)
//     {
//         $this->db->trans_begin();
//         //
//         $insertArray = array();
//         $insertArray['timeoff_policy_sid'] = $formpost['policy'];
//         // $insertArray['status'] = $formpost['status'];
//         $insertArray['for_admin'] = $formpost['approverCheck'];
//         $insertArray['creator_sid'] = $formpost['employeeSid'];
//         $insertArray['is_archived'] = $formpost['archiveCheck'];
//         $insertArray['is_unlimited'] = $formpost['accrualMethod'] == 'unlimited' ? 1 : 0;
//         $insertArray['employee_sid'] = $formpost['assignedEmployees'];


//         // Insert Policy Overwrite
//         $this->db->insert('timeoff_policy_overwrite', $insertArray);
//         $policyOverwriteSid = $this->db->insert_id();
//         //
//         if (!$policyOverwriteSid) {
//             $this->db->trans_rollback();
//             return 0;
//         }
//         //
//         if (isset($formpost['plans']) && sizeof($formpost['plans'])) {
//             // Insert Policy Plans
//             $insertArray = array();
//             $insertArray['timeoff_policy_overwrite_sid'] = $policyOverwriteSid;
//             foreach ($formpost['plans'] as $plan) {
//                 $insertArray['accrual_rate'] = $plan['accrualRate'];
//                 $insertArray['plan_title'] = $plan['accrualType'];
//                 //
//                 $this->db->insert('timeoff_policy_overwrite_plans', $insertArray);
//             }
//         }
//         // Insert Accural settings
//         $insertArray = array();
//         $insertArray['timeoff_policy_overwrite_sid'] = $policyOverwriteSid;
//         // $insertArray['accural_type'] = $formpost['accuralType'];
//         if ($formpost['accuralStartDate'] != '')
//             $insertArray['accural_start_date'] = DateTime::createFromFormat('m-d-Y', $formpost['accuralStartDate'])->format('Y-m-d');
//         // $insertArray['is_lose_active'] = $formpost['isUse'];
//         $insertArray['new_hire_days'] = $formpost['newHireDays'];
//         // Phase 3
//         $insertArray['accrual_method'] = $formpost['accrualMethod'];
//         $insertArray['accrual_rate'] = $formpost['accrualRate'];
//         $insertArray['accrual_time'] = $formpost['accrualTime'];
//         $insertArray['accrual_frequency'] = $formpost['accrualFrequency'];
//         $insertArray['minimum_applicable_hours'] = $formpost['minimumApplicableHours'];
//         $insertArray['minimum_applicable_type'] = $formpost['minimumApplicableHoursType'];
//         $insertArray['accrual_frequency_custom'] = $formpost['accrualFrequencyCustom'];
//         $insertArray['allow_negative_balance'] = $formpost['negativeBalance'] == 'yes' ? 1 : 0;
//         $insertArray['negative_balance'] = $formpost['negativeBalanceAllowed'];
//         $insertArray['reset_date'] = $formpost['resetDate'] != 0 ? DateTime::createFromFormat('m-d-Y', $formpost['resetDate'])->format('Y-m-d') : NULL;
//         $insertArray['carryover_cap'] = $formpost['carryoverCap'];
//         $insertArray['carryover_cap_check'] = $formpost['carryoverCapCheck'] == 'yes' ? 1 : 0;
//         $insertArray['newhire_prorate'] = $formpost['newhireProrate'];
//         //
//         $this->db->insert('timeoff_policy_overwrite_accural', $insertArray);
//         $policyAccuralSid = $this->db->insert_id();
//         //
//         if (!$policyAccuralSid) {
//             $this->db->trans_rollback();
//             return 0;
//         }
//         $this->db->trans_commit();
//         return $policyOverwriteSid;
//     }

//     /**
//      * Get policies overwrites created by a company
//      *
//      * @param Array $formpost
//      * @return Array
//      */
//     function getPolicyOverwritesByCompany(
//         $formpost,
//         $page,
//         $limit
//     ) {
//         // Set start
//         $start = $page == 1 ? 0 : (($page * $limit) - $limit);
//         //
//         $this->db
//             ->select('
//            timeoff_policy_overwrite.sid as policy_overwrite_id,
//            timeoff_policies.title as policy_title,
//            timeoff_policy_overwrite.status as status,
//            timeoff_policy_overwrite.created_at,
//            timeoff_policy_overwrite_accural.*,
//            users.profile_picture as img,
//            users.employee_number,
//            users.sid as employee_id,
//            users.employee_number,
//            users.first_name,
//             users.last_name,
//             users.access_level_plus,
//             users.access_level,
//             users.pay_plan_flag,
//             users.job_title,
//             users.is_executive_admin,
//            CONCAT(users.first_name," ", users.last_name) as full_name
//        ')
//             ->from('timeoff_policy_overwrite')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_policy_overwrite.timeoff_policy_sid', 'left')
//             ->join('timeoff_policy_overwrite_accural', 'timeoff_policy_overwrite.sid = timeoff_policy_overwrite_accural.timeoff_policy_overwrite_sid', 'left')
//             ->join('users', 'timeoff_policy_overwrite.employee_sid = users.sid', 'left')
//             ->where('timeoff_policy_overwrite.is_archived', $formpost['fetchType'] == 'active' ? 0 : 1)
//             ->where('timeoff_policies.company_sid', $formpost['companySid'])
//             ->order_by('timeoff_policy_overwrite.sort_order', 'ASC')
//             ->limit($limit, $start);
//         // Search Filter
//         if ($formpost['status'] != '' && $formpost['status'] == -1) $this->db->where('timeoff_policy_overwrite.status', $formpost['status']);
//         if ($formpost['policySid'] != '' && $formpost['policySid'] == 0) $this->db->where('timeoff_policies.sid', $formpost['policySid']);
//         if ($formpost['employeeSid'] != '' && $formpost['employeeSid'] == 0) $this->db->where('timeoff_policy_overwrite.sid', $formpost['employeeSid']);
//         if ($formpost['startDate'] != '' && $formpost['endDate']) $this->db->where('DATE_FORMAT(timeoff_policy_overwrite.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['startDate']) . ' AND ' . ($formpost['endDate']) . '');
//         //
//         $result = $this->db->get();
//         $policies = $result->result_array();
//         $result  = $result->free_result();
//         //
//         if (!sizeof($policies)) return array();
//         //
//         $returnArray = array('PolicyOverwrites' => $policies, 'Count' => 0);
//         //
//         if ($page != 1) return $returnArray;
//         //
//         $this->db
//             ->from('timeoff_policy_overwrite')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_policy_overwrite.timeoff_policy_sid', 'left')
//             ->join('timeoff_policy_overwrite_accural', 'timeoff_policy_overwrite.sid = timeoff_policy_overwrite_accural.timeoff_policy_overwrite_sid', 'left')
//             ->where('timeoff_policy_overwrite.is_archived', $formpost['fetchType'] == 'active' ? 0 : 1)
//             ->where('timeoff_policies.company_sid', $formpost['companySid']);
//         // Search Filter
//         if ($formpost['status'] != '' && $formpost['status'] == -1) $this->db->where('timeoff_policy_overwrite.status', $formpost['status']);
//         if ($formpost['policySid'] != '' && $formpost['policySid'] == 0) $this->db->where('timeoff_policies.sid', $formpost['policySid']);
//         if ($formpost['employeeSid'] != '' && $formpost['employeeSid'] == 0) $this->db->where('timeoff_policy_overwrite.sid', $formpost['employeeSid']);
//         if ($formpost['startDate'] != '' && $formpost['endDate']) $this->db->where('DATE_FORMAT(timeoff_policy_overwrite.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['startDate']) . ' AND ' . ($formpost['endDate']) . '');
//         //
//         $returnArray['Count'] = $this->db->count_all_results();

//         return $returnArray;
//         // Loop through policies
//         // Get Plans
//     }

//     /**
//      * Update Policy Overwrite
//      *
//      * @param Integer $policyOverwriteSid
//      * @param Array   $updateArray
//      *
//      * @return VOID
//      */
//     function updateCompanyPolicyOverwrite($policyOverwriteSid, $updateArray)
//     {
//         // Update PTO Group
//         $this->db
//             ->where('sid', $policyOverwriteSid)
//             ->update('timeoff_policy_overwrite', $updateArray);
//     }

//     /**
//      * Get a single policy overwrite by id and company sid
//      * @param  Array $formpost
//      * @return Array
//      */
//     function getCompanyPolicyOverwriteById($formpost)
//     {
//         $result = $this->db
//             ->select('
//             timeoff_policy_overwrite.sid as policy_overwrite_id,
//             timeoff_policy_overwrite.timeoff_policy_sid as policy_id,
//             timeoff_policies.title as policy_title,
//             timeoff_policy_overwrite.employee_sid,
//             timeoff_policy_overwrite.is_archived,
//             timeoff_policy_overwrite.status,
//             timeoff_policy_overwrite.for_admin,
//             timeoff_policy_overwrite.is_unlimited,
//             timeoff_policy_overwrite_accural.*,
//             timeoff_settings.default_timeslot,
//             timeoff_formats.slug as format
//         ')
//             ->from('timeoff_policy_overwrite')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_policy_overwrite.timeoff_policy_sid', 'left')
//             ->join('timeoff_policy_overwrite_accural', 'timeoff_policy_overwrite.sid = timeoff_policy_overwrite_accural.timeoff_policy_overwrite_sid', 'left')
//             ->join('timeoff_settings', 'timeoff_policies.company_sid = timeoff_settings.company_sid', 'left')
//             ->join('timeoff_formats', 'timeoff_settings.timeoff_format_sid = timeoff_formats.sid', 'left')
//             ->where('timeoff_policies.company_sid', $formpost['companySid'])
//             ->where('timeoff_policy_overwrite.sid', $formpost['policyOverwriteSid'])
//             ->limit(1)
//             ->get();
//         //
//         $policyOverwrite = $result->row_array();
//         $result = $result->free_result();
//         //
//         if (!sizeof($policyOverwrite)) return array();
//         if ($policyOverwrite['is_unlimited'] == 1) {
//             $policyOverwrite['plans'] = array();
//             return $policyOverwrite;
//         }
//         // Get plans
//         $result = $this->db
//             ->select('
//             timeoff_policy_overwrite_plans.plan_title,
//             timeoff_policy_overwrite_plans.accrual_rate
//         ')
//             ->from('timeoff_policy_overwrite_plans')
//             ->where('timeoff_policy_overwrite_plans.timeoff_policy_overwrite_sid', $formpost['policyOverwriteSid'])
//             ->order_by('CONVERT(SUBSTRING_INDEX(timeoff_policy_plans.plan_title,' - ',-1),UNSIGNED INTEGER)', 'ASC')
//             ->get();
//         //
//         $plans = $result->result_array();
//         $result = $result->free_result();

//         $policyOverwrite['plans'] = $plans;
//         return $policyOverwrite;
//     }

//     /**
//      * Update policy
//      *
//      * @param  Array $formpost
//      * @return Integer
//      */
//     function updatePolicyOverwrite($formpost)
//     {
//         //
//         $updateArray = array();
//         $updateArray['timeoff_policy_sid'] = $formpost['policy'];
//         // $updateArray['status'] = $formpost['status'];
//         $updateArray['for_admin'] = $formpost['approverCheck'];
//         $updateArray['creator_sid'] = $formpost['employeeSid'];
//         $updateArray['is_archived'] = $formpost['archiveCheck'];
//         $updateArray['is_unlimited'] = $formpost['accrualMethod'] == 'unlimited' ? 1 : 0;
//         $updateArray['employee_sid'] = $formpost['assignedEmployees'];

//         // Update Policy Overwrite
//         $this->db->where('sid', $formpost['policyOverwriteSid'])->update('timeoff_policy_overwrite', $updateArray);
//         $policyOverwriteSid = $formpost['policyOverwriteSid'];
//         // Drop previous
//         $this->db
//             ->where('timeoff_policy_overwrite_sid', $policyOverwriteSid)
//             ->delete('timeoff_policy_overwrite_plans');
//         // //
//         if (isset($formpost['plans']) && sizeof($formpost['plans'])) {
//             // Insert Policy Plans
//             $insertArray = array();
//             $insertArray['timeoff_policy_overwrite_sid'] = $policyOverwriteSid;
//             foreach ($formpost['plans'] as $plan) {
//                 $insertArray['accrual_rate'] = $plan['accrualRate'];
//                 $insertArray['plan_title'] = $plan['accrualType'];
//                 //
//                 $this->db->insert('timeoff_policy_overwrite_plans', $insertArray);
//             }
//         }
//         // Update Accural settings
//         $updateArray = array();
//         // $updateArray['accural_type'] = $formpost['accuralType'];
//         if ($formpost['accuralStartDate'] != '')
//             $updateArray['accural_start_date'] = DateTime::createFromFormat('m-d-Y', $formpost['accuralStartDate'])->format('Y-m-d');
//         // $updateArray['is_lose_active'] = $formpost['isUse'];
//         // $updateArray['is_lose_active'] = $formpost['isUse'];
//         $updateArray['new_hire_days'] = $formpost['newHireDays'];
//         // Phase 3
//         $updateArray['accrual_method'] = $formpost['accrualMethod'];
//         $updateArray['accrual_rate'] = $formpost['accrualRate'];
//         $updateArray['accrual_time'] = $formpost['accrualTime'];
//         $updateArray['accrual_frequency'] = $formpost['accrualFrequency'];
//         $updateArray['minimum_applicable_hours'] = $formpost['minimumApplicableHours'];
//         $updateArray['minimum_applicable_type'] = $formpost['minimumApplicableHoursType'];
//         $updateArray['accrual_frequency_custom'] = $formpost['accrualFrequencyCustom'];
//         $updateArray['allow_negative_balance'] = $formpost['negativeBalance'] == 'yes' ? 1 : 0;
//         $updateArray['negative_balance'] = $formpost['negativeBalanceAllowed'];
//         $updateArray['reset_date'] = $formpost['resetDate'] != 0 ? DateTime::createFromFormat('m-d-Y', $formpost['resetDate'])->format('Y-m-d') : NULL;
//         $updateArray['carryover_cap'] = $formpost['carryoverCap'];
//         $updateArray['carryover_cap_check'] = $formpost['carryoverCapCheck'] == 'yes' ? 1 : 0;
//         $updateArray['newhire_prorate'] = $formpost['newhireProrate'];
//         //
//         $this->db->where('timeoff_policy_overwrite_sid', $policyOverwriteSid)->update('timeoff_policy_overwrite_accural', $updateArray);
//         return $policyOverwriteSid;
//     }

//     /**
//      * Fetch company active departments
//      * @param Integer $companySid
//      * @param Array
//      */
//     function getCompanyDepartments($companySid)
//     {
//         $result =  $this->db
//             ->select('sid as department_id, name as title')
//             ->where('company_sid', $companySid)
//             ->where('status', 1)
//             ->where('is_deleted', 0)
//             ->order_by('name', 'ASC')
//             ->get('departments_management');
//         //
//         $departments = $result->result_array();
//         $result = $result->free_result();
//         //
//         return $departments;
//     }
    
    
    


    

    
    
//     /**
//      * Update approver
//      * @param  Array $formpost
//      * @return VOID
//      */
//     function updateApprover($formpost)
//     {
//         //
//         $updateArray = array();
//         $updateArray['employee_sid'] = $formpost['approverSid'];
//         $updateArray['department_sid'] = in_array('all', $formpost['departmentSid']) ? 'all' : implode(',', $formpost['departmentSid']);
//         $updateArray['status'] = $formpost['status'];
//         $updateArray['is_department'] = $formpost['isDepartment'];
//         $updateArray['approver_percentage'] = $formpost['percentageCheck'];
//         $updateArray['is_archived'] = $formpost['archiveCheck'];
//         //
//         $this->db
//             ->where('sid', $formpost['sid'])
//             ->update('timeoff_approvers', $updateArray);
//     }

    

//     /**
//      * Update Approver
//      *
//      * @param Integer $approverSid
//      * @param Array   $updateArray
//      *
//      * @return VOID
//      */
//     function updateCompanyApprover($approverSid, $updateArray)
//     {
//         // Update PTO Group
//         $this->db
//             ->where('sid', $approverSid)
//             ->update('timeoff_approvers', $updateArray);
//     }

    


//     /**
//      * Get employee policies
//      * @param Array $post
//      */
//     function getEmployeePolicies($post)
//     {
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         // Get approvers
//         //
//         $result = $this->db
//             ->select('sid, title, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('for_admin', 0)
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             // ->where('policy_start_date >=', date('Y-m-d'))
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);

//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result(); 
//             //
//             if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                 unset($policies[$k0]);
//                 continue;
//             }

//             // Set phase 3 Accural
//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;

//             $policies[$k0]['title'] = ucwords($v0['title']);
//             $policies[$k0]['user_shift_hours'] = $employeeShiftHours;
//             $policies[$k0]['user_shift_minutes'] = $employeeShiftMinutes;
//             $policies[$k0]['employee_timeslot'] = $defaultTimeFrame;
//             $policies[$k0]['employee_type'] = $employmentType;
//             $policies[$k0]['format'] = $timeoffFormat;
//             // Get policy category
//             $a =
//                 $this->db
//                 ->select('category_name')
//                 ->from('timeoff_policy_categories')
//                 ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policy_categories.timeoff_category_sid')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->limit(1)
//                 ->get();
//             //
//             $b = $a->row_array();
//             $a = $a->free_result();
//             $policies[$k0]['Category'] = '';
//             if (sizeof($b)) {
//                 $policies[$k0]['Category'] = $b['category_name'];
//             }

//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();
//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();
//                 $policies[$k0]['company_sid'] = $companyId;
//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];

//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }
               
//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     true,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//             } else {
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }
//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     false,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//             }
//             // Manipulate policy
//             if ($policies[$k0]['allowed_timeoff'] != -1)
//                 $policies[$k0]['timeoff_breakdown'] = get_array_from_minutes(
//                     $policies[$k0]['allowed_timeoff'],
//                     $defaultTimeFrame,
//                     $slug,
//                     $policies[$k0]['accrual']
//                 );
//         }
//         return array_values($policies);
//     }

//     function getTimeByAccrualRules(
//         &$policies,
//         $k0,
//         $years,
//         $employeeId,
//         $joinedAt,
//         $defaultTimeFrame,
//         $slug,
//         $isOverwrite = FALSE,
//         $balances = [],
//         $bypass = false,
//         $employementStatus
//     ) {
//         //
//         $mb = 0;
//         $v0 = $policies[$k0];
//         //
//         $rateInMinutes = 0;
       
//         // Check if we need to reset time off
//         if ($v0['accrual']['reset_date'] !== null) $resetDate = DateTime::createFromFormat('Y-m-d', $v0['accrual']['reset_date'])->format('m-d');
//         //
//         $format = '-Y';
//         // For hours per month
//         if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//             if ($v0['accrual']['reset_date'] !== null) $resetDate = DateTime::createFromFormat('Y-m-d', $v0['accrual']['reset_date'])->format('d');
//             $format = '';
//         }
//         //
//         $consumedTime = 0;
//         $getConsumedTime = 1;
//         // Check if reset date is not empty
//         if ($v0['accrual']['reset_date'] != null && $v0['accrual']['reset_date'] != '0000-00-00') {
//             //
//             $v0['accrual']['reset_date'] = trim($v0['accrual']['reset_date']);
//             // For hours
//             if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//                 // 04-15 06-15
//                 $resetDay = date('d', strtotime($v0['accrual']['reset_date']));
//                 //
//                 $now = date_create(date('m-d-Y', strtotime('now')));
//                 $then = date_create(date('m-' . ($resetDay) . '-Y', strtotime('-1 month')));
//                 $diff = date_diff($now, $then);
//                 //
//                 if ($diff->format('%a') >= '30') {
//                     $consumedTime = 0;
//                     $getConsumedTime = 0;
//                 }
//             } else { // For Days
//                 //
//                 $now = date_create(date('Y-m-d', strtotime('now')));
//                 $then = date_create($v0['accrual']['reset_date']);
//                 $diff = date_diff($now, $then);
//                 //
//                 if ($diff->format('%a') >= '365') {
//                     $consumedTime = 0;
//                     $getConsumedTime = 0;
//                 }
//             }
//         }
//         //
//         if ($getConsumedTime == 1) {
//             // Get total consumed time
//             $consumedTime = $this->getConsumedTimeOff(
//                 $v0['sid'],
//                 $employeeId,
//                 $years,
//                 $joinedAt,
//                 $policies[$k0]['accrual']
//             );
//         }


//         // Get Pending time
//         $pendingTime = $this->getPendingTimeOff(
//             $v0['sid'],
//             $years,
//             $employeeId,
//             $joinedAt,
//             $policies[$k0]['accrual']
//         );
//         //
//         if($employementStatus == 'permanent'){
//             //
//             $m = $isOverwrite ? 'getPolicyOverwriteAccrualPlans' : 'getPolicyAccrualPlans';
//             // Get accrual plans
//             $newAccuralRate = $v0['accrual']['accrual_method'] == 'unlimited' ? 0 : $this->$m(
//                 isset($v0['overwrite_sid']) ? $v0['overwrite_sid'] : $v0['sid'],
//                 $years,
//                 $v0['accrual']['accrual_method'],
//                 $defaultTimeFrame
//             );
//             //
//             $oar = $policies[$k0]['accrual']['accrual_rate'];
//             $policies[$k0]['accrual']['accrual_rate'] += $newAccuralRate;
//         } else{
//             $newAccuralRate = 0;
//             $oar = $policies[$k0]['accrual']['newhire_prorate'];
//             $v0['accrual']['accrual_rate'] = $policies[$k0]['accrual']['accrual_rate'] = $policies[$k0]['accrual']['newhire_prorate'];
//         }

//         //
//         if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//             $rateInMinutes = $policies[$k0]['accrual']['accrual_rate'] * 60;
//         } else {
//             if($v0['accrual']['accrual_frequency'] == 'monthly'){
//                 $rateInMinutes = $policies[$k0]['accrual']['accrual_rate'] * 12 * 60;
//             } else{
//                 $rateInMinutes = $policies[$k0]['accrual']['accrual_rate'] * ($defaultTimeFrame * 60);
//             }
//         }
//         //
//         $cdd = date('d', strtotime('now'));
//         $cdm = date('m', strtotime('now'));
//         //
//         // $cdd = '15';
//         // $cdm = '03';
//         // Period
//         if ($v0['accrual']['accrual_time'] == 'end_of_period') {
//             if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//                 if($cdd < '15') $rateInMinutes = 0;
//             } else{
//                 if($cdm < '6') $rateInMinutes = 0;
//             }
//         }
//         else if($v0['accrual']['accrual_time'] == 'start_of_period') {
//             if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//                 if($cdd > '15') $rateInMinutes = 0;
//             } else{
//                 if($cdm > '6') $rateInMinutes = 0;
//             }
//         }
//         //
//         if(isset($balances[$v0['sid']])) { 
//             $rateInMinutes += $balances[$v0['sid']];
//             $mb = $balances[$v0['sid']];
//             $policies[$k0]['balance'] = $balances[$v0['sid']];
//         }
//         //
//         $policies[$k0]['consumed_timeoff'] = $consumedTime;
//         $policies[$k0]['actual_allowed_timeoff'] = ($rateInMinutes + $pendingTime);
//         $policies[$k0]['allowed_timeoff'] = ($rateInMinutes + $pendingTime) - $consumedTime;
//         //
//         if ($v0['accrual']['accrual_frequency'] == 'custom') {
//             $monthSlots = 12 / $v0['accrual']['accrual_frequency_custom'];
//             $allowedTime = $policies[$k0]['allowed_timeoff'] / $monthSlots;
//             $actualAllowedTime = $policies[$k0]['actual_allowed_timeoff'] / $monthSlots;
//             //
//             $iko = 0;
//             //
//             for($i = $monthSlots; $i >= 1; $i--){
//                 if(
//                     $years <= ( $i * $v0['accrual']['accrual_frequency_custom'] * 30) &&
//                     $years >=  (( $i - 1) * $v0['accrual']['accrual_frequency_custom'] * 30)
//                 ){
//                     $iko = $i;
//                 }
//             }
//         }
//         // // Frequency
//         if ($v0['accrual']['accrual_method'] == 'days_per_year') {
//             if ($v0['accrual']['accrual_frequency'] == 'yearly') {
//                 $policies[$k0]['actual_allowed_timeoff'] = $policies[$k0]['actual_allowed_timeoff'] / 12;
//                 $policies[$k0]['allowed_timeoff'] = $policies[$k0]['allowed_timeoff'] / 12;
//             } else if ($v0['accrual']['accrual_frequency'] == 'monthly') {
//                 $policies[$k0]['actual_allowed_timeoff'] = ( $policies[$k0]['actual_allowed_timeoff'] / 12 );
//                 $policies[$k0]['allowed_timeoff'] = ( $policies[$k0]['allowed_timeoff'] / 12);
//             } else if ($v0['accrual']['accrual_frequency'] == 'custom') {
//                 $policies[$k0]['actual_allowed_timeoff'] = $actualAllowedTime * $iko;
//                 $policies[$k0]['allowed_timeoff'] = $allowedTime * $iko;
//             }
//         } else{
//             if ($v0['accrual']['accrual_frequency'] == 'custom') {
//                 $policies[$k0]['actual_allowed_timeoff'] = $actualAllowedTime * $iko;
//                 $policies[$k0]['allowed_timeoff'] = $allowedTime * $iko;
//             }
//         }

//         // Check if an employee has already availed the alowed time
//         if ($policies[$k0]['allowed_timeoff'] <= $consumedTime) {
//             // $policies[$k0]['allowed_timeoff'] = 0;
//         }

//         //
//         if($slug){
//             //
//             $oar += $newAccuralRate;
//             //
//             if ($v0['accrual']['accrual_method'] == 'hours_per_month') {
//                 $oar = ( $v0['accrual']['accrual_rate']) * 60;
//             } else {
//                 if($v0['accrual']['accrual_frequency'] == 'monthly'){
//                     $oar = $v0['accrual']['accrual_rate'] * 12 * 60;
//                 } else{
//                     $oar = $v0['accrual']['accrual_rate'] * ($defaultTimeFrame * 60);
//                 }
//             }
//             //
//             $policies[$k0]['PendingBreakDown'] = get_array_from_minutes(
//                 $oar + $pendingTime - $consumedTime + $mb,
//                 $defaultTimeFrame,
//                 $slug,
//                 $policies[$k0]['accrual']
//             );

//             $policies[$k0]['ConsumedBreakDown'] = get_array_from_minutes(
//                 $consumedTime,
//                 $defaultTimeFrame,
//                 $slug,
//                 $policies[$k0]['accrual']
//             );

//             $policies[$k0]['ManualBalanceBreakDown'] = get_array_from_minutes(
//                 $mb,
//                 $defaultTimeFrame,
//                 $slug,
//                 $policies[$k0]['accrual']
//             );

//             $policies[$k0]['TotalBreakDown'] = get_array_from_minutes(
//                 $oar + $mb,
//                 $defaultTimeFrame,
//                 $slug,
//                 $policies[$k0]['accrual']
//             );
            
//             $policies[$k0]['AllowedBreakDown'] = get_array_from_minutes(
//                 $oar,
//                 $defaultTimeFrame,
//                 $slug,
//                 $policies[$k0]['accrual']
//             );
//             $policies[$k0]['PolicyResetDate'] = 
//             !empty($v0['accrual']['reset_date']) && $v0['accrual']['reset_date'] != null ? $v0['accrual']['reset_date'] : ( 
//                 !empty($v0['accrual']['accural_start_date']) && $v0['accrual']['accural_start_date'] != null ? $v0['accrual']['accural_start_date'] : $joinedAt
//             );
//         }


//         return array(
//             'allowed' => $rateInMinutes,
//             'consumed' => $consumedTime,
//             'pending' => $pendingTime
//         );
//     }

//     private function getPolicyAccrualPlans(
//         $policySid,
//         $days,
//         $accrualMethod,
//         $defaultTimeFrame
//     ) {
//         $a = $this->db
//             ->select('plan_title, accrual_rate')
//             ->where('timeoff_policy_sid', $policySid)
//             ->get('timeoff_policy_plans');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if (!sizeof($b)) return 0;
//         //
//         $work = 0;
//         //
//         if (sizeof($b) > 1) {
//             //
//             foreach ($b as $k => $v) {
//                 //
//                 $index = ++$k;
//                 //
//                 $v1 = $this->getPlanInDays($v['plan_title'], $accrualMethod, $defaultTimeFrame);
//                 //
//                 if (!isset($b[$index])) {
//                     if ($days >= $v1) {
//                         $work = $v['accrual_rate'];
//                         break;
//                     }
//                 }
//                 //
//                 if (isset($b[$index])) {
//                     $v2 = $this->getPlanInDays($b[$index]['plan_title'], $accrualMethod, $defaultTimeFrame);
//                     //
//                     if ($days >= $v1 && $days <= $v2) {
//                         $work = $v['accrual_rate'];
//                         break;
//                     }
//                 }
//             }
//         } else {
//             $v1 = $this->getPlanInDays($b[0]['plan_title'], $accrualMethod, $defaultTimeFrame);
//             if ($days >= $v1) return $b[0]['accrual_rate'];
//         }
//         //
//         return $work;
//     }

//     private function getPolicyOverwriteAccrualPlans(
//         $policySid,
//         $days,
//         $accrualMethod,
//         $defaultTimeFrame
//     ) {
//         $a = $this->db
//             ->select('plan_title, accrual_rate')
//             ->where('timeoff_policy_overwrite_sid', $policySid)
//             ->get('timeoff_policy_overwrite_plans');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if (!sizeof($b)) return 0;
//         //
//         $work = 0;
//         //
//         if (sizeof($b) > 1) {
//             //
//             foreach ($b as $k => $v) {
//                 //
//                 $index = ++$k;
//                 //
//                 $v1 = $this->getPlanInDays($v['plan_title'], $accrualMethod, $defaultTimeFrame);
//                 $v2 = $this->getPlanInDays($b[$index]['plan_title'], $accrualMethod, $defaultTimeFrame);
//                 //
//                 if (!isset($b[$index])) {
//                     if ($days >= $v1) {
//                         $work = $v['accrual_rate'];
//                         break;
//                     }
//                 }
//                 //
//                 if ($days >= $v1 && $days <= $v2) {
//                     $work = $v['accrual_rate'];
//                     break;
//                 }
//             }
//         } else {
//             $v1 = $this->getPlanInDays($b[0]['plan_title'], $accrualMethod, $defaultTimeFrame);
//             if ($days >= $v1) return $b[0]['accrual_rate'];
//         }
//         //
//         return $work;
//     }

//     //
//     private function getPlanInDays(
//         $plan,
//         $type,
//         $defaultTimeFrame
//     ) {
//         //
//         $t = explode('.', $plan);
//         //
//         if ($type == 'days_per_year')
//             return yearsToMonths($t[0], isset($t[1]) ? $t[1] : 0) * 30;
//         else
//             return floor(($t[0] * 30) + (isset($t[1]) ? $t[1] : 0) / 1440);
//     }


//     /**
//      * Get allowed hour for policy
//      *
//      * @param Integer $policyId
//      * @param Integer $years
//      * @param Integer $employeeId
//      *
//      * @return Array
//      */
//     private function getAllowedTimeOffWithPlan(
//         $policyId,
//         $years,
//         $employeeId,
//         $joinedAt
//     ) {
//         $result = $this->db
//             ->select('
//             timeoff_policy_plans.allowed_timeoff,
//             timeoff_plan_list.plan_year,
//             timeoff_plan_list.plan_month,
//             timeoff_policy_accural.is_lose_active,
//             timeoff_policy_accural.accural_type,
//             timeoff_policy_accural.accural_start_date
//         ')
//             ->join('timeoff_plans', 'timeoff_plans.sid = timeoff_policy_plans.timeoff_plan_sid', 'inner')
//             ->join('timeoff_plan_list', 'timeoff_plan_list.sid = timeoff_plans.timeoff_plan_list_sid', 'inner')
//             ->join('timeoff_policy_accural', 'timeoff_policy_accural.timeoff_policy_sid = ' . ($policyId) . '', 'inner')
//             ->where('timeoff_policy_plans.timeoff_policy_sid', $policyId)
//             ->where('timeoff_plans.is_archived', 0)
//             ->order_by('(timeoff_plan_list.plan_year * 12) + timeoff_plan_list.plan_month', 'ASC', false)
//             ->get('timeoff_policy_plans');
//         //
//         $records = $result->result_array();
//         $result  = $result->free_result();
//         //
//         if (!sizeof($records)) return array();
//         // Reset years and joining date
//         if ($records[0]['accural_start_date'] != null) {
//             $joinedAt = $records[0]['accural_start_date'];
//             $years = $this->getYearsFromDate($records[0]['accural_start_date']);
//         }
//         // $months = $years;
//         // Get consumed timeoff
//         $consumedTimeOff = $this->getTimeOffUsed(
//             $policyId,
//             $employeeId,
//             $records[0]['accural_type'],
//             $joinedAt
//         );
//         //
//         // $matchedYear = $records[0];
//         // foreach ($records as $k0 => $v0) {
//         //     if($months <= yearsToMonths($v0['plan_year'], $v0['plan_month']) ) {
//         //         $matchedYear = $v0;
//         //         break;
//         //     }
//         // }
//         $matchedYear['pendingTimeOffOld'] = 0;
//         $matchedYear['consumed'] = $consumedTimeOff;
//         if ($matchedYear['is_lose_active'] == 1) {
//             // Get consumed timeoff
//             $oldPendingTimeOff = $this->getPendingTimeOff(
//                 $policyId,
//                 $employeeId,
//                 $records[0]['accural_type'],
//                 $joinedAt,
//                 $matchedYear['allowed_timeoff']
//             );
//             $matchedYear['pendingTimeOffOld'] = $oldPendingTimeOff;
//         }
//         return $matchedYear;
//     }


//     /**
//      * Get Overwrite allowed hour for policy
//      *
//      * @param Integer $policyOverwriteId
//      * @param Integer $years
//      * @param Integer $employeeId
//      * @param Integer $policyId
//      *
//      * @return Array
//      */
//     private function getOverwriteAllowedTimeOffWithPlan(
//         $policyOverwriteId,
//         $years,
//         $employeeId,
//         $policyId,
//         $joinedAt
//     ) {
//         $result = $this->db
//             ->select('
//             timeoff_policy_overwrite_plans.allowed_timeoff,
//             timeoff_plan_list.plan_year,
//             timeoff_plan_list.plan_month,
//             timeoff_policy_overwrite_accural.is_lose_active,
//             timeoff_policy_overwrite_accural.accural_type,
//             timeoff_policy_overwrite_accural.accural_start_date
//         ')
//             ->join('timeoff_plans', 'timeoff_plans.sid = timeoff_policy_overwrite_plans.plan_sid', 'inner')
//             ->join('timeoff_plan_list', 'timeoff_plan_list.sid = timeoff_plans.timeoff_plan_list_sid', 'inner')
//             ->where('timeoff_plans.is_archived', 0)
//             ->join('timeoff_policy_overwrite_accural', 'timeoff_policy_overwrite_accural.timeoff_policy_overwrite_sid = ' . ($policyOverwriteId) . '', 'inner')
//             ->where('timeoff_policy_overwrite_plans.timeoff_policy_overwrite_sid', $policyOverwriteId)
//             ->order_by('(timeoff_plan_list.plan_year * 12) + timeoff_plan_list.plan_month', 'ASC', false)
//             ->get('timeoff_policy_overwrite_plans');
//         //
//         $records = $result->result_array();
//         $result  = $result->free_result();
//         //
//         if (!sizeof($records)) return array();
//         // Reset years and joining date
//         if ($records[0]['accural_start_date'] != null) {
//             $joinedAt = $records[0]['accural_start_date'];
//             $years = $this->getYearsFromDate($records[0]['accural_start_date']);
//         }
//         $months = $years;
//         // Get consumed timeoff
//         $consumedTimeOff = $this->getTimeOffUsed(
//             $policyId,
//             $employeeId,
//             $records[0]['accural_type'],
//             $joinedAt
//         );

//         //
//         $matchedYear = $records[0];
//         foreach ($records as $k0 => $v0) {
//             if ($months <= yearsToMonths($v0['plan_year'], $v0['plan_month'])) {
//                 $matchedYear = $v0;
//                 break;
//             }
//         }
//         $matchedYear['pendingTimeOffOld'] = 0;
//         $matchedYear['consumed'] = $consumedTimeOff;
//         if ($matchedYear['is_lose_active'] == 1) {
//             // Get consumed timeoff
//             $oldPendingTimeOff = $this->getPendingTimeOff(
//                 $policyId,
//                 $employeeId,
//                 $records[0]['accural_type'],
//                 $joinedAt,
//                 $matchedYear['allowed_timeoff']
//             );
//             $matchedYear['pendingTimeOffOld'] = $oldPendingTimeOff;
//         }
//         return $matchedYear;
//     }


//     private function getTimeOffUsed(
//         $policyId,
//         $employeeId,
//         $accuralType,
//         $accuralStartDate
//     ) {
//         $dateFormat = 'Y';
//         $dateFormatDB = '%Y';
//         // Fetch current used timeoff
//         if ($accuralType == 'hours_per_month') {
//             // if($accuralType == 'pay_per_period' || $accuralType == 'pay_per_month'){
//             $dateFormat = 'Y-m';
//             $dateFormatDB = '%Y-%m';
//         }
//         $date = date($dateFormat, strtotime('now'));
//         //
//         $result = $this->db
//             ->select('
//             SUM(requested_time) as requested_time
//         ')
//             ->from('timeoff_requests')
//             ->where('timeoff_policy_sid', $policyId)
//             ->where('employee_sid', $employeeId)
//             ->where('status', 'approved')
//             ->where('is_draft', 0)
//             ->where("date_format(request_from_date, \"$dateFormatDB\") = ", "$date")
//             ->get();
//         //
//         $record = $result->row_array();
//         $result->free_result();
//         //
//         return $record['requested_time'] == null ? 0 : $record['requested_time'];
//     }


//     // private function getPendingTimeOff(
//     //     $policyId,
//     //     $employeeId,
//     //     $accuralType,
//     //     $accuralStartDate,
//     //     $allowedTimeOff
//     // ){
//     //     $dateFormat = 'Y';
//     //     $dateFormatDB = '%Y';
//     //     // Fetch current used timeoff
//     //     if($accuralType == 'hours_per_month'){
//     //         $dateFormat = 'Y-m';
//     //         $dateFormatDB = '%Y-%m';
//     //     }
//     //     $date = date($dateFormat, strtotime('now'));
//     //     //
//     //     $result = $this->db
//     //     ->select('
//     //         SUM(requested_time) as consumed,
//     //         COUNT(DISTINCT(DATE_FORMAT(request_from_date, "'.( $dateFormatDB ).'"))) as occurrence
//     //     ')
//     //     ->from('timeoff_requests')
//     //     ->where('timeoff_policy_sid', $policyId)
//     //     ->where('employee_sid', $employeeId)
//     //     ->where('status', 'approved')
//     //     ->where("date_format(request_from_date, \"$dateFormatDB\") < ", "$date")
//     //     ->get();
//     //     //
//     //     $record = $result->row_array();
//     //     $result->free_result();
//     //     //
//     //     if(!sizeof($record) || $record['consumed'] == '') return 0;
//     //     //
//     //     $pendingTimeOff = ($allowedTimeOff * $record['occurrence']) - $record['consumed'];
//     //     return $pendingTimeOff < 0 ? 0 : $pendingTimeOff;
//     // }


//     //
//     function isTimeOffExists($formpost)
//     {
//         return $this->db
//             ->where('employee_sid', $formpost['employeeSid'])
//             ->where('request_from_date', $formpost['requestDate'])
//             ->count_all_results('timeoff_requests');
//     }

//     //
//     function fetchTimeOffEmployers($employeeSid, $companySid)
//     {
//         // Set Default holders
//         $departmentIds = [];
//         $teamIds = [];
//         $all = [];
//         // Get teamleads
//         $a = $this->db
//             ->select('team_lead as id, departments_team_management.sid as team_sid, "teamlead" as type')
//             ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
//             ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
//             ->where('departments_team_management.status', 1)
//             ->where('departments_management.is_deleted', 0)
//             ->where('departments_management.status', 1)
//             ->where('departments_management.company_sid', $companySid)
//             ->where('employee_sid', $employeeSid)
//             ->group_by('team_lead')
//             ->get('departments_employee_2_team');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         // When team leads are found
//         if(count($b)) {
//             //
//             foreach($b as $k => $v){
//                 //
//                 $a = $this->db
//                 ->select('users.email, CONCAT(users.first_name," ", users.last_name) as full_name, sid')
//                 ->where_in('sid', explode(',', $v['id']))
//                 ->get('users');
//                 //
//                 $d = $a->result_array();
//                 $a = $a->free_result();
//                 //
//                 $e = [];
//                 //
//                 foreach($d as $c) $e[] = array_merge($v, array('email' => $c['email'], 'full_name' => $c['full_name'], 'id' => $c['sid']));
//                 //
//             }
//             $b = $e;
//             $all = array_merge($all, $b);
//             $teamIds = array_column($b, 'team_sid');
//         }

//         // Get Supervisors
//         $a = $this->db
//             ->select('
//             supervisor as id, 
//             departments_management.sid as department_sid, 
//             "supervisor" as type
//         ')
//         ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
//         ->where('departments_employee_2_team.employee_sid', $employeeSid)
//         ->where('departments_management.status', 1)
//         ->where('departments_management.is_deleted', 0)
//         ->where('departments_management.company_sid', $companySid)
//         ->group_by('supervisor')
//         ->get('departments_employee_2_team');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         // When supervisors are found
//         if(count($b)) {
//              //
//              foreach($b as $k => $v){
//                 //
//                 $a = $this->db
//                 ->select('users.email, CONCAT(users.first_name," ", users.last_name) as full_name, sid')
//                 ->where_in('sid', explode(',', $v['id']))
//                 ->get('users');
//                 //
//                 $d = $a->result_array();
//                 $a = $a->free_result();
//                 //
//                 $e = [];
//                 //
//                 foreach($d as $c) $e[] = array_merge($v, array('email' => $c['email'], 'full_name' => $c['full_name'], 'id' => $c['sid']));
//                 //
//             }
//             //
//             $b = $e;
//             //
//             $all = array_merge($all, $b);
//             $departmentIds = array_column($b, 'department_sid');
//         }
//         // Get Approvers for specific departments
//         if(count($departmentIds)){
//             $this->db
//             ->select('
//                 timeoff_approvers.employee_sid as id, 
//                 users.email, 
//                 CONCAT(users.first_name," ", users.last_name) as full_name, 
//                 "approver" as type
//             ')
//             ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner');
//             //
//             $this->db->group_start();
//             foreach($departmentIds as $k => $dt)  {
//                 if($k == 0) $this->db->where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//                 else $this->db->or_where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//             }
//             $this->db->group_end();
//             $a = $this->db
//             ->where('timeoff_approvers.company_sid', $companySid)
//             ->where('timeoff_approvers.is_archived', 0)
//             ->where('timeoff_approvers.is_department', '1')
//             ->get('timeoff_approvers');
//             //
//             $b = $a->result_array();
//             $a = $a->free_result();
//             //
//             if(count($b)) $all = array_merge($all, $b);
//         }

//         // Get Approvers for specific teams
//         if(count($teamIds)){
//             $this->db
//             ->select('
//                 timeoff_approvers.employee_sid as id, 
//                 users.email, 
//                 CONCAT(users.first_name," ", users.last_name) as full_name, 
//                 "approver" as type
//             ')
//             ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner');
//             //
//             $this->db->group_start();
//             foreach($teamIds as $k => $dt)  {
//                 if($k == 0) $this->db->where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//                 else $this->db->or_where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//             }
//             $a = $this->db
//             ->group_end()
//             ->where('timeoff_approvers.company_sid', $companySid)
//             ->where('timeoff_approvers.is_archived', 0)
//             ->where('timeoff_approvers.is_department', '0')
//             ->get('timeoff_approvers');
//             //
//             $b = $a->result_array();
//             $a = $a->free_result();
//             //
//             if(count($b)) $all = array_merge($all, $b);
//         }

//         // Get Approvers for all departments
//         $a = $this->db
//         ->select('
//             timeoff_approvers.employee_sid as id, 
//             users.email, 
//             CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "approver" as type
//         ')
//         ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         ->where('timeoff_approvers.company_sid', $companySid)
//         ->where('timeoff_approvers.department_sid', 'all')
//         ->where('timeoff_approvers.is_archived', 0)
//         ->where('timeoff_approvers.is_department', '1')
//         ->get('timeoff_approvers');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(count($b)) $all = array_merge($all, $b);

//         // Get Approvers for all teams
//         $a = $this->db
//         ->select('
//             timeoff_approvers.employee_sid as id, 
//             users.email, 
//             CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "approver" as type
//         ')
//         ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         ->where('timeoff_approvers.company_sid', $companySid)
//         ->where('timeoff_approvers.department_sid', 'all')
//         ->where('timeoff_approvers.is_archived', 0)
//         ->where('timeoff_approvers.is_department', '0')
//         ->get('timeoff_approvers');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(count($b)) $all = array_merge($all, $b);


//         return $all;




//     // ----------------------------------------------------------------------


//         // Set
//         // $departmentIds = [];
//         // $teamIds = [];
//         // // Get teamleads
//         // $a = $this->db
//         //     ->select('team_lead as id, users.email, CONCAT(users.first_name," ", users.last_name) as full_name, "teamlead" as type')
//         //     ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'left')
//         //     ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
//         //     ->join('users', 'users.sid = departments_team_management.team_lead', 'inner')
//         //     ->where('departments_management.is_deleted', 0)
//         //     ->where('departments_management.status', 1)
//         //     ->where('departments_team_management.status', 1)
//         //     ->where('employee_sid', $employeeSid)
//         //     ->group_by('team_lead')
//         //     ->get('departments_employee_2_team');
//         // //
//         // $b = $a->result_array();
//         // $a = $a->free_result();

//         // // Get all approvers belong to 'all departments'
//         // // only if teams are not found
//         // if (!sizeof($b)) {
//         //     $a = $this->db
//         //         ->select('
//         //         timeoff_approvers.employee_sid as id, 
//         //         users.email, CONCAT(users.first_name," ", users.last_name) as full_name, 
//         //         "approver" as type
//         //     ')
//         //         ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         //         ->where('timeoff_approvers.is_archived', 0)
//         //         ->where('timeoff_approvers.company_sid', $companySid)
//         //         ->where('timeoff_approvers.department_sid', 'all')
//         //         ->get('timeoff_approvers');
//         //     //
//         //     $b = $a->result();
//         //     $a = $a->free_result();
//         //     //
//         //     return $b;
//         // }
//         // // Save teamleads to return array
//         // $r = $b;
//         // // Fetch Supervisors
//         // $a = $this->db
//         //     ->select('
//         //     supervisor as id, 
//         //     users.email, CONCAT(users.first_name," ", users.last_name) as full_name, 
//         //     "supervisor" as type
//         // ')
//         //     ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
//         //     ->join('users', 'users.sid = departments_management.supervisor', 'inner')
//         //     ->where('departments_employee_2_team.employee_sid', $employeeSid)
//         //     ->where('departments_management.status', 1)
//         //     ->where('departments_management.is_deleted', 0)
//         //     ->group_by('supervisor')
//         //     ->get('departments_employee_2_team');
//         // //
//         // $b = $a->result_array();
//         // $a = $a->free_result();
//         // //
//         // if (sizeof($b)) $r = array_merge($r, $b);

//         // // Fetch department Approvers
//         // $a = $this->db
//         //     ->select('
//         //     timeoff_approvers.employee_sid as id, 
//         //     users.email, 
//         //     CONCAT(users.first_name," ", users.last_name) as full_name, 
//         //     "approver" as type
//         // ')
//         // ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         // ->where('timeoff_approvers.is_archived', 0)
//         // ->where('timeoff_approvers.is_department', '1')
//         // ->where_in('timeoff_approvers.department_sid', 1)
//         // ->get('timeoff_approvers');
//         // //
//         // $b = $a->result_array();
//         // $a = $a->free_result();
//         // //
//         // if (sizeof($b)) $r = array_merge($r, $b);
//         // //
//         // // Get approvers by all departments
//         // $a = $this->db
//         //     ->select('
//         //     timeoff_approvers.employee_sid as id, 
//         //     users.email, CONCAT(users.first_name," ", users.last_name) as full_name, 
//         //     "approver" as type
//         // ')
//         //     ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         //     ->where('timeoff_approvers.is_archived', 0)
//         //     ->where('timeoff_approvers.company_sid', $companySid)
//         //     ->where('timeoff_approvers.department_sid', 'all')
//         //     ->where('timeoff_approvers.is_department', '1')
//         //     ->get('timeoff_approvers');
//         // //
//         // $c = $a->result();
//         // $a = $a->free_result();
//         // //
//         // if (sizeof($c)) $r = array_merge($r, $c);
//         // $a = $this->db
//         //     ->select('
//         //     timeoff_approvers.employee_sid as id, 
//         //     users.email, CONCAT(users.first_name," ", users.last_name) as full_name, 
//         //     "approver" as type
//         // ')
//         //     ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         //     ->where('timeoff_approvers.is_archived', 0)
//         //     ->where('timeoff_approvers.company_sid', $companySid)
//         //     ->where('timeoff_approvers.department_sid', 'all')
//         //     ->where('timeoff_approvers.is_department', '0')
//         //     ->get('timeoff_approvers');
//         // //
//         // $c = $a->result();
//         // $a = $a->free_result();
//         // //
//         // if (sizeof($c)) $r = array_merge($r, $c);
//         // return $r;
//     }

//     //
    

//     //
//     function assignEmployeeToRequest($ins)
//     {
//         $this->db->insert('timeoff_request_assignment', $ins);
//         return $this->db->insert_id();
//     }

//     //
//     function checkAndAssignEmployeeToRequest($ins)
//     {
//         $a = $this->db
//             ->select('sid')
//             ->where('timeoff_request_sid', $ins['timeoff_request_sid'])
//             ->where('employee_sid', $ins['employee_sid'])
//             ->where('role', $ins['role'])
//             ->get('timeoff_request_assignment');
//         //
//         $b = $a->row_array();
//         if (sizeof($b)) return $b['sid'];
//         else return $this->assignEmployeeToRequest($ins);
//     }

//     // 
//     function fetchEmployeeRequestsLMS(
//         $post
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.requested_time,
//             timeoff_requests.company_sid,
//             timeoff_requests.employee_sid,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.request_from_date as requested_date,
//             timeoff_requests.is_partial_leave,
//             timeoff_requests.status,
//             timeoff_requests.is_draft,
//             timeoff_requests.partial_leave_note,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_policies.title as policy_title,
//         ")
//             ->from('timeoff_requests')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
//             ->where('timeoff_requests.employee_sid', $post['employeeSid'])
//             ->where('timeoff_requests.archive', 0)
//             ->where('timeoff_policies.is_archived', 0)
//             ->order_by('requested_date', 'ASC')
//             ->order_by('status', 'ASC');
//         //
//         if (isset($post['noDraft'])) $this->db->where('timeoff_requests.is_draft', 0);
//         //
//         if ($post['fromDate'] != 'all' && $post['toDate'] != 'all') $this->db->where('timeoff_requests.request_from_date between "' . ($post['fromDate']) . '" and "' . ($post['toDate']) . '"', null);
//         if ($post['status'] != 'all') $this->db->where('timeoff_requests.status', $post['status']);
//         $a = $this->db->get();
//         //
//         $b = $a->result_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         //
//         // Fetch employee joining date
//         $result = $this->db
//             ->select('joined_at, user_shift_hours, user_shift_minutes')
//             ->from('users')
//             ->where('sid', $post['employeeSid'])
//             ->limit(1)
//             ->get();
//         //
//         $joinedAt = isset($result->row_array()['joined_at']) ? $result->row_array()['joined_at'] : null;
//         $employeeShiftHours = isset($result->row_array()['user_shift_hours']) ? $result->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//         $employeeShiftMinutes = isset($result->row_array()['user_shift_minutes']) ? $result->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//         $result   = $result->free_result();
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $post['companySid'])
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result->free_result();

//         //
//         foreach ($b as $k => $v) {
//             // Fetch non responded employees
//             $b[$k]['Progress']['UnResponded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 0)
//                 ->count_all_results('timeoff_request_assignment');
//             // Fetch responded employees
//             $b[$k]['Progress']['Responded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 1)
//                 ->count_all_results('timeoff_request_assignment');

//             //
//             $b[$k]['expired'] = false;
//             if ($v['requested_date'] <= date('Y-m-d')) {
//                 $b[$k]['expired'] = true;
//             }
//             if ($v['status'] != 'pending') {
//                 $b[$k]['expired'] = true;
//             }

//             //
//             $b[$k]['Progress']['Total'] = $b[$k]['Progress']['UnResponded'] + $b[$k]['Progress']['Responded'];
//             if ($v['status'] != 'pending' || ($b[$k]['Progress']['UnResponded'] == 0 && $b[$k]['Progress']['Responded'] == 0)) {
//                 $b[$k]['Progress']['CompletedPercentage'] = 100;
//             } else {
//                 $b[$k]['Progress']['CompletedPercentage'] = ceil(($b[$k]['Progress']['Responded'] / $b[$k]['Progress']['Total']) * 100);
//             }
//             $b[$k]['policy_title'] = ucwords($v['policy_title']);
//             //
//             $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
//                 $v['requested_time'],
//                 $defaultTimeFrame,
//                 $slug
//             );

//             // Fetch history
//             $a = $this->db
//                 ->select('
//                 reason
//             ')
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->order_by('created_at', 'DESC')
//                 ->get('timeoff_request_history');
//             //
//             $b[$k]['History'] = $a->row_array();
//             $a->free_result();

//             // Time off Category
//             $a = $this->db
//                 ->select('
//                 category_name
//             ')
//                 ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                 ->where('timeoff_policy_categories.timeoff_policy_sid', $v['policyId'])
//                 ->order_by('timeoff_categories.sort_order', 'ASC')
//                 ->get('timeoff_policy_categories');
//             //
//             $b[$k]['Category'] = $a->row_array();
//             $a->free_result();

//             // Fetch assigned employees
//             $a = $this->db
//                 ->select('
//                 timeoff_request_assignment.sid,
//                 timeoff_request_assignment.employee_sid,
//                 timeoff_request_assignment.role,
//                 users.email
//             ')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_assignment.timeoff_request_sid', $v['requestId'])
//             ->order_by('sid', 'ASC')
//             ->get('timeoff_request_assignment');
//             //
//             $Assigned = $a->result_array();
//             $this->cleanAssignedArray(
//                 $v['requestId'],
//                 $v['employee_sid'],
//                 $v['company_sid'],
//                 $Assigned
//             );
//             $a->free_result();

//             // Sort by policy
//             if (!isset($r[($b[$k]['Category']['category_name'])])) $r[($b[$k]['Category']['category_name'])] = ($b[$k]['Category']['category_name']);
//             // if(!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
//         }
//         //
//         asort($r);
//         $r = array_values($r);
//         return ['Requests' => $b, 'Titles' => $r];
//     }

//     //
//     function cancelEmployeeRequest($post)
//     {
//         $this->db
//             ->where('sid', $post['requestId'])
//             ->update('timeoff_requests', [
//                 'status' => 'cancelled'
//             ]);
//     }

//     //
//     /**
//      * Get employee policies status
//      * @param Array $post
//      */
//     function getEmployeePoliciesStatus($post)
//     {
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         //
//         $result = $this->db
//             ->select('sid, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('is_included', 1)
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         //
//         $r = array(
//             'Total' => 0,
//             'Consumed' => 0,
//             'Pending' => 0
//         );

//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);
//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result();


//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;
//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();

//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();


//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];
//                 //
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }
//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }
//                 //
//                 $det = $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     true,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//                 //
//                 $r['Total'] += $det['allowed'];
//                 $r['Consumed'] += $det['consumed'];
//                 $r['Pending'] += ($det['allowed'] + $det['pending']) - $det['consumed'];
//             } else {
//                 //
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }
//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }
//                 //
//                 $det = $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     false,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//                 //
//                 $r['Total'] += $det['allowed'];
//                 $r['Consumed'] += $det['consumed'];
//                 $r['Pending'] += ($det['allowed'] + $det['pending']) - $det['consumed'];
//             }
//         }

//         $r['Total'] = get_array_from_minutes($r['Total'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Consumed'] = get_array_from_minutes($r['Consumed'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Pending'] = get_array_from_minutes($r['Pending'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         return $r;
//     }


//     //
//     /**
//      * Get employee policies status
//      * @param Array $post
//      */
//     function getEmployeePoliciesForBalance($post)
//     {
//         //
//         if(!isset($post['bypass'])) $post['bypass'] = false;
//         //
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         //
//         $result = $this->db
//             ->select('sid, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('is_included', 1)
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         //
//         $r = array(
//             'Total' => 0,
//             'Consumed' => 0,
//             'Pending' => 0
//         );

//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);
//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result();


//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;
//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();

//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();


//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];
//                 //
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                      unset($policies[$k0]);
//                      continue;
//                  }
//                 // if (!$post['bypass']) {
//                 // }
//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }
//                 // Get total consumed time
//                 $consumedTime = $this->getConsumedTimeOff(
//                     $v0['sid'],
//                     $employeeId,
//                     $years,
//                     $joinedAt,
//                     $aa
//                 );

//                 // Get Pending time
//                 $pendingTime = $this->getPendingTimeOff(
//                     $v0['sid'],
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $aa
//                 );
//                 if($employmentStatus == 'permanent'){
//                     // Get accrual plans
//                     $newAccuralRate = $aa['accrual_method'] == 'unlimited' ? 0 : $this->getPolicyOverwriteAccrualPlans(
//                         isset($v0['overwrite_sid']) ? $v0['overwrite_sid'] : $v0['sid'],
//                         $years,
//                         $aa['accrual_method'],
//                         $defaultTimeFrame
//                     );
//                 } else{
//                     $newAccuralRate = 0;
//                     $aa['accrual_rate'] = $aa['newhire_prorate'];
//                 }

//                 //
//                 $aa['accrual_rate'] += $newAccuralRate;
//                 //
//                 $rateInMinutes = $aa['accrual_rate'];
//                 if(isset($balances[$v0['sid']])) $rateInMinutes += $balances[$v0['sid']];
//                 //
//                 if ($aa['accrual_method'] == 'hours_per_month') {
//                     $rateInMinutes = $aa['accrual_rate'] * 60;
//                 } else {
//                     $rateInMinutes = $aa['accrual_rate'] * ($defaultTimeFrame * 60);
//                 }

//                  //
//                 $r['Total'] += ceil($rateInMinutes);
//                 $r['Consumed'] += $consumedTime;
//                 $r['Pending'] += ceil(($rateInMinutes + $pendingTime) - $consumedTime);
//             } else {
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                      unset($policies[$k0]);
//                      continue;
//                  }
//                 //
//                 // if (!$post['bypass']) {
//                 //  }
//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }

//                 // Get total consumed time
//                 $consumedTime = $this->getConsumedTimeOff(
//                     $v0['sid'],
//                     $employeeId,
//                     $years,
//                     $joinedAt,
//                     $aa
//                 );

//                 // Get Pending time
//                 $pendingTime = $this->getPendingTimeOff(
//                     $v0['sid'],
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $aa
//                 );
//                 if($employmentStatus == 'permanent'){
//                     // Get accrual plans
//                     $newAccuralRate = $aa['accrual_method'] == 'unlimited' ? 0 : $this->getPolicyOverwriteAccrualPlans(
//                         isset($v0['overwrite_sid']) ? $v0['overwrite_sid'] : $v0['sid'],
//                         $years,
//                         $aa['accrual_method'],
//                         $defaultTimeFrame
//                     );
//                 } else{
//                     $newAccuralRate = 0;
//                     $aa['accrual_rate'] = $aa['newhire_prorate'];
//                 }

        

//                 //
//                 $aa['accrual_rate'] += $newAccuralRate;
//                 //
//                 $rateInMinutes = $aa['accrual_rate'];
//                 //
//                 if ($aa['accrual_method'] == 'hours_per_month') {
//                     $rateInMinutes = ( $aa['accrual_rate']  ) * 60;
//                 } else {
//                     $rateInMinutes = $aa['accrual_rate'] * ($defaultTimeFrame * 60);
//                 }
//                 if(isset($balances[$v0['sid']])) $rateInMinutes += $balances[$v0['sid']];
//                 // if(isset($balances[$v0['sid']])) _e($v0['sid'].' - '.$rateInMinutes, true);
//                 //
//                 $r['Total'] += ceil($rateInMinutes);
//                 $r['Consumed'] += $consumedTime;
//                 $r['Pending'] += ceil(($rateInMinutes + $pendingTime) - $consumedTime);
//             }
//         }

//         $r['Total'] = get_array_from_minutes($r['Total'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Consumed'] = get_array_from_minutes($r['Consumed'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Pending'] = get_array_from_minutes($r['Pending'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         return $r;
//     }
    
    

    

//     //
//     function getIncomingRequestByPerm(
//         $post
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.employee_sid,
//             timeoff_requests.company_sid,
//             timeoff_requests.requested_time,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.request_from_date as requested_date,
//             timeoff_requests.request_to_date,
//             timeoff_requests.is_partial_leave,
//             timeoff_requests.status,
//             timeoff_requests.partial_leave_note,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_requests.timeoff_days,
//             timeoff_policies.title as policy_title,
//         ")
//             ->from('timeoff_requests')
//             ->where('timeoff_requests.status', 'pending')
//             ->where('timeoff_requests.is_draft', '0')
//             ->where('timeoff_requests.archive', '0')
//             ->where('timeoff_policies.is_archived', '0')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
//             ->order_by('requested_date', 'ASC')
//             ->order_by('status', 'DESC');
//         //
//         if ($post['startDate'] != '' && $post['endDate'] != '' && $post['startDate'] != 'all' && $post['endDate'] != 'all') $this->db->where('timeoff_requests.request_from_date between "' . ($post['startDate']) . '" and "' . ($post['endDate']) . '"', null);
//         if ($post['status'] != 'all') $this->db->where('timeoff_requests.status', $post['status']);
//         if ($post['policySid'] != 'all') $this->db->where('timeoff_requests.timeoff_policy_sid', $post['policySid']);
//         if ($post['requesterSid'] != 'all') $this->db->where('timeoff_requests.employee_sid', $post['requesterSid']);
//         else $this->db->where_in('timeoff_requests.employee_sid', $post['employeeList']);
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->result_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         // Get company default time
//         $a = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $post['companySid'])
//             ->limit(1)
//             ->get();
//         //
//         $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
//         $a->free_result();
//         //
//         foreach ($b as $k => $v) {
//             // Fetch assigned employees
//             $a = $this->db
//                 ->select('
//                 timeoff_request_assignment.sid,
//                 timeoff_request_assignment.employee_sid,
//                 timeoff_request_assignment.role,
//                 users.email
//             ')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_assignment.timeoff_request_sid', $v['requestId'])
//             ->order_by('sid', 'ASC')
//             ->get('timeoff_request_assignment');
//             //
//             $Assigned = $a->result_array();
//             $this->cleanAssignedArray(
//                 $v['requestId'],
//                 $v['employee_sid'],
//                 $v['company_sid'],
//                 $Assigned
//             );
//             // Check if teamlead is  assigned
//             $a = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('role', 'teamlead')
//                 ->count_all_results('timeoff_request_assignment');
//             if (!$a) {
//                 $a = $this->db
//                     ->where('timeoff_request_sid', $v['requestId'])
//                     ->where('role', 'supervisor')
//                     ->count_all_results('timeoff_request_assignment');
//                 //
//                 $this->db
//                     ->where('sid', $v['requestId'])
//                     ->update(
//                         'timeoff_requests',
//                         array(
//                             'level_at' => !$a ? 3 : 2
//                         )
//                     );
//                 $v['level_at'] = $b[$k]['level_at'] = !$a ? 3 : 2;
//             }

//             $a = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('employee_sid', $post['employeeSid'])
//                 ->where(
//                     'role',
//                     $v['level_at'] == 1 ? 'teamlead' : ($v['level_at'] == 2 ? 'supervisor' : 'approver')
//                 )
//                 ->count_all_results('timeoff_request_assignment');
//             if (!$a) {
//                 unset($b[$k]);
//                 continue;
//             }
//             // Fetch employee joining date
//             $a = $this->db
//                 ->select('
//                 joined_at,
//                 first_name,
//                 last_name,
//                 access_level_plus,
//                 access_level,
//                 pay_plan_flag,
//                 job_title,
//                 is_executive_admin,
//                 user_shift_hours, 
//                 user_shift_minutes,
//                 concat(first_name," ",last_name) as full_name,
//                 profile_picture as img,
//                 employee_number
//             ')
//                 ->from('users')
//                 ->where('sid', $v['employee_sid'])
//                 ->limit(1)
//                 ->get();
//             //
//             $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
//             $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//             $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//             //
//             $b[$k]['first_name'] = $a->row_array()['full_name'];
//             $b[$k]['last_name'] = $a->row_array()['full_name'];
//             $b[$k]['access_level'] = $a->row_array()['access_level'];
//             $b[$k]['access_level_plus'] = $a->row_array()['access_level_plus'];
//             $b[$k]['job_title'] = $a->row_array()['job_title'];
//             $b[$k]['is_executive_admin'] = $a->row_array()['is_executive_admin'];
//             $b[$k]['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
//             $b[$k]['full_name'] = $a->row_array()['full_name'];
//             $b[$k]['img'] = $a->row_array()['img'];
//             $b[$k]['employee_number'] = $a->row_array()['employee_number'];
//             //
//             $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//             $a->free_result();
//             // Fetch non responded employees
//             $b[$k]['Progress']['UnResponded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 0)
//                 ->count_all_results('timeoff_request_assignment');
//             // Fetch responded employees
//             $b[$k]['Progress']['Responded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 1)
//                 ->count_all_results('timeoff_request_assignment');
//             //
//             $b[$k]['Progress']['Total'] = $b[$k]['Progress']['UnResponded'] + $b[$k]['Progress']['Responded'];
//             $b[$k]['Progress']['CompletedPercentage'] = ceil(($b[$k]['Progress']['Responded'] / $b[$k]['Progress']['Total']) * 100);

//             if ($v['status'] != 'pending') {
//                 $b[$k]['Progress']['CompletedPercentage'] = 100;
//             }

//             $b[$k]['policy_title'] = ucwords($v['policy_title']);
//             //
//             $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
//                 $v['requested_time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//             $b[$k]['slug'] = $slug;
//             $b[$k]['defaultTimeFrame'] = $defaultTimeFrame;

//             // Time off Category
//             $a = $this->db
//                 ->select('
//                 category_name
//             ')
//                 ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                 ->where('timeoff_policy_categories.timeoff_policy_sid', $v['policyId'])
//                 ->order_by('timeoff_categories.sort_order', 'ASC')
//                 ->get('timeoff_policy_categories');
//             //
//             $b[$k]['Category'] = '';
//             $aa = $a->row_array();
//             $a->free_result();
//             if (sizeof($aa)) $b[$k]['Category'] = $aa['category_name'];

//             // Sort by policy
//             if (!isset($r[($b[$k]['Category'])])) $r[($b[$k]['Category'])] = ($b[$k]['Category']);

//             // Sort by policy
//             // if(!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
//         }
//         //
//         asort($r);
//         $r = array_values($r);
//         return ['Requests' => array_values($b), 'Titles' => $r];
//     }

//     function update_request_archive_status($sid, $status)
//     {
//         $this->db->where('sid', $sid);
//         $this->db->set('archive', $status);
//         $this->db->update('timeoff_requests');
//     }

//     //
//     function getSingleRequest(
//         $post
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.employee_sid,
//             timeoff_requests.requested_time,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.fmla,
//             timeoff_requests.request_from_date,
//             timeoff_requests.request_to_date,
//             timeoff_requests.status,
//             timeoff_requests.is_draft,
//             timeoff_requests.level_status,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_requests.timeoff_days,
//             timeoff_requests.return_date,
//             timeoff_requests.compensation_start_time,
//             timeoff_requests.compensation_end_time,
//             timeoff_requests.relationship,
//             timeoff_requests.temporary_address,
//             timeoff_requests.emergency_contact_number,
//             timeoff_requests.archive,
//             timeoff_policies.title as policy_title
//         ")
//             ->from('timeoff_requests')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
//             ->where('timeoff_requests.sid', $post['requestSid']);
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         $r['Info'] = $b;
//         $r['Info']['timeoff_days'] = @json_decode($r['Info']['timeoff_days'], TRUE);

//         if (!sizeof($r['Info']['timeoff_days'])) {
//             $r['Info']['timeoff_days'][] = array(
//                 'date' => DateTime::createFromFormat('Y-m-d', $r['Info']['request_from_date'])->format('m-d-Y'),
//                 'time' => $r['Info']['requested_time'],
//                 'partial' => isset($r['Info']['is_partial_leave']) && $r['Info']['is_partial_leave'] == 1 ? 'partialday' : 'fullday'
//             );
//         }

//         // Get company default time
//         $a = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $post['companySid'])
//             ->limit(1)
//             ->get();
//         //
//         $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
//         $a->free_result();
//         // Time off Category
//         $a = $this->db
//             ->select('
//             category_name
//         ')
//             ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//             ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//             ->where('timeoff_policy_categories.timeoff_policy_sid', $r['Info']['policyId'])
//             ->order_by('timeoff_categories.sort_order', 'ASC')
//             ->get('timeoff_policy_categories');
//         //
//         $r['Info']['Category'] = $a->row_array()['category_name'];
//         $a->free_result();

//         //
//         // Fetch employee joining date
//         $a = $this->db
//             ->select('
//             joined_at, 
//             user_shift_hours, 
//             user_shift_minutes,
//             email,
//             concat(first_name," ",last_name) as full_name,
//             first_name,
//             last_name,
//             access_level_plus,
//             access_level,
//             pay_plan_flag,
//             job_title,
//             is_executive_admin,
//             profile_picture as img,
//             employee_number
//         ')
//             ->from('users')
//             ->where('sid', $b['employee_sid'])
//             ->limit(1)
//             ->get();
//         //
//         $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
//         $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//         $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//         //
//         $r['Info']['full_name'] = $a->row_array()['full_name'];
//         $r['Info']['first_name'] = $a->row_array()['first_name'];
//         $r['Info']['last_name'] = $a->row_array()['last_name'];
//         $r['Info']['access_level'] = $a->row_array()['access_level'];
//         $r['Info']['access_level_plus'] = $a->row_array()['access_level_plus'];
//         $r['Info']['job_title'] = $a->row_array()['job_title'];
//         $r['Info']['is_executive_admin'] = $a->row_array()['is_executive_admin'];
//         $r['Info']['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
//         $r['Info']['email'] = $a->row_array()['email'];
//         $r['Info']['img'] = $a->row_array()['img'];
//         $r['Info']['employee_number'] = $a->row_array()['employee_number'];
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         $a->free_result();
//         // Fetch non responded employees
//         $r['Info']['Progress']['UnResponded'] = $this->db
//             ->where('timeoff_request_sid', $b['requestId'])
//             ->where('is_reassigned', 0)
//             ->where('is_responded', 0)
//             ->count_all_results('timeoff_request_assignment');
//         // Fetch responded employees
//         $r['Info']['Progress']['Responded'] = $this->db
//             ->where('timeoff_request_sid', $b['requestId'])
//             ->where('is_reassigned', 0)
//             ->where('is_responded', 1)
//             ->count_all_results('timeoff_request_assignment');
//         //
//         $r['Info']['Progress']['Total'] = $r['Info']['Progress']['UnResponded'] + $r['Info']['Progress']['Responded'];
//         if ($r['Info']['status'] != 'pending') {
//             $r['Info']['Progress']['CompletedPercentage'] = 100;
//         } else if ($r['Info']['Progress']['Total'] == 0) {
//             $r['Info']['Progress']['CompletedPercentage'] = 0;
//         } else {
//             $r['Info']['Progress']['CompletedPercentage'] = ceil(($r['Info']['Progress']['Responded'] / $r['Info']['Progress']['Total']) * 100);
//         }
//         $r['Info']['policy_title'] = ucwords($b['policy_title']);
//         //
//         $r['Info']['timeoff_breakdown'] = get_array_from_minutes(
//             $b['requested_time'],
//             $defaultTimeFrame,
//             $slug
//         );
//         // Get breakdown for all
//         foreach ($r['Info']['timeoff_days'] as $k1 => $v1) {
//             $r['Info']['timeoff_days'][$k1]['breakdown'] = get_array_from_minutes(
//                 $v1['time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//         }
//         // Fetch assigned employees
//         $a = $this->db
//             ->select('
//             timeoff_request_assignment.sid,
//             timeoff_request_assignment.employee_sid,
//             timeoff_request_assignment.is_responded,
//             timeoff_request_assignment.status,
//             timeoff_request_assignment.is_reassigned,
//             timeoff_request_assignment.role,
//             timeoff_request_assignment.created_at,
//             concat(users.first_name," ",users.last_name) as full_name,
//             users.email
//         ')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_assignment.timeoff_request_sid', $post['requestSid'])
//             ->order_by('sid', 'ASC')
//             ->get('timeoff_request_assignment');
//         //
//         $r['Assigned'] = $a->result_array();
//         $r['Assigned'] = $this->cleanAssignedArray(
//             $r['Info']['requestId'],
//             $r['Info']['employee_sid'],
//             $post['companySid'],
//             $r['Assigned']
//         );
//         $a->free_result();

//         // Fetch history
//         $a = $this->db
//             ->select('
//             request_from_date as requested_date,
//             request_time,
//             timeoff_request_assignment_sid,
//             reason,
//             is_partial_leave,
//             note,
//             status,
//             created_at
//         ')
//             ->where('timeoff_request_sid', $post['requestSid'])
//             ->order_by('created_at', 'DESC')
//             ->get('timeoff_request_history');
//         //
//         $r['History'] = $a->result_array();
//         $a->free_result();
//         //
//         if (sizeof($r['History'])) {
//             foreach ($r['History'] as $k => $v) {
//                 $r['History'][$k]['timeoff_breakdown'] = get_array_from_minutes(
//                     $v['request_time'],
//                     $defaultTimeFrame,
//                     $slug
//                 );
//             }
//         }
//         // Fetch Conversation
//         $a = $this->db
//             ->select('
//             request_from_date as requested_date,
//             sid,
//             timeoff_request_assignment_sid,
//             request_time,
//             sent,
//             request_status as status,
//             created_at,
//             is_active
//         ')
//             ->where('timeoff_request_sid', $post['requestSid'])
//             ->get('timeoff_request_conversation');
//         //
//         $r['Conversation'] = $a->result_array();
//         $a->free_result();
//         // Fetch Attachments
//         $a = $this->db
//             ->select('
//             sid,
//             document_title,
//             document_type,
//             s3_filename,
//             serialized_data,
//             is_archived,
//             is_completed,
//             created_at
//         ')
//             ->where('timeoff_request_sid', $post['requestSid'])
//             ->order_by('created_at', 'DESC')
//             ->get('timeoff_attachments');
//         //
//         $r['Attachments'] = $a->result_array();
//         $a->free_result();
//         //
//         return $r;
//     }

//     //
//     function getSingleRequestBySid(
//         $sid
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.employee_sid,
//             timeoff_requests.requested_time,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.fmla,
//             timeoff_requests.request_from_date,
//             timeoff_requests.request_to_date,
//             timeoff_requests.status,
//             timeoff_requests.is_draft,
//             timeoff_requests.level_status,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_requests.timeoff_days,
//             timeoff_requests.return_date,
//             timeoff_requests.company_sid,
//             timeoff_requests.compensation_start_time,
//             timeoff_requests.compensation_end_time,
//             timeoff_requests.relationship,
//             timeoff_requests.temporary_address,
//             timeoff_requests.emergency_contact_number,
//             timeoff_requests.archive,
//             timeoff_policies.title as policy_title
//         ")
//             ->from('timeoff_requests')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
//             ->where('timeoff_requests.sid', $sid);
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         $r['Info'] = $b;
//         $r['Info']['timeoff_days'] = @json_decode($r['Info']['timeoff_days'], TRUE);

//         if (!sizeof($r['Info']['timeoff_days'])) {
//             $r['Info']['timeoff_days'][] = array(
//                 'date' => DateTime::createFromFormat('Y-m-d', $r['Info']['request_from_date'])->format('m-d-Y'),
//                 'time' => $r['Info']['requested_time'],
//                 'partial' => $r['Info']['is_partial_leave'] == 1 ? 'partialday' : 'fullday'
//             );
//         }

//         // Get company default time
//         $a = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $b['company_sid'])
//             ->limit(1)
//             ->get();
//         //
//         $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
//         $a->free_result();
//         // Time off Category
//         $a = $this->db
//             ->select('
//             category_name
//         ')
//             ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//             ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//             ->where('timeoff_policy_categories.timeoff_policy_sid', $r['Info']['policyId'])
//             ->order_by('timeoff_categories.sort_order', 'ASC')
//             ->get('timeoff_policy_categories');
//         //
//         $r['Info']['Category'] = $a->row_array()['category_name'];
//         $a->free_result();

//         //
//         // Fetch employee joining date
//         $a = $this->db
//             ->select('
//             joined_at, 
//             user_shift_hours, 
//             user_shift_minutes,
//             email,
//             concat(first_name," ",last_name) as full_name,
//             first_name,
//             last_name,
//             access_level_plus,
//             access_level,
//             pay_plan_flag,
//             job_title,
//             is_executive_admin,
//             profile_picture as img,
//             employee_number
//         ')
//             ->from('users')
//             ->where('sid', $b['employee_sid'])
//             ->limit(1)
//             ->get();
//         //
//         $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
//         $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//         $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//         //
//         $r['Info']['full_name'] = $a->row_array()['full_name'];
//         $r['Info']['first_name'] = $a->row_array()['first_name'];
//         $r['Info']['last_name'] = $a->row_array()['last_name'];
//         $r['Info']['access_level'] = $a->row_array()['access_level'];
//         $r['Info']['access_level_plus'] = $a->row_array()['access_level_plus'];
//         $r['Info']['job_title'] = $a->row_array()['job_title'];
//         $r['Info']['is_executive_admin'] = $a->row_array()['is_executive_admin'];
//         $r['Info']['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
//         $r['Info']['email'] = $a->row_array()['email'];
//         $r['Info']['img'] = $a->row_array()['img'];
//         $r['Info']['employee_number'] = $a->row_array()['employee_number'];
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         $a->free_result();
//         // Fetch non responded employees
//         $r['Info']['Progress']['UnResponded'] = $this->db
//             ->where('timeoff_request_sid', $b['requestId'])
//             ->where('is_reassigned', 0)
//             ->where('is_responded', 0)
//             ->count_all_results('timeoff_request_assignment');
//         // Fetch responded employees
//         $r['Info']['Progress']['Responded'] = $this->db
//             ->where('timeoff_request_sid', $b['requestId'])
//             ->where('is_reassigned', 0)
//             ->where('is_responded', 1)
//             ->count_all_results('timeoff_request_assignment');
//         //
//         $r['Info']['Progress']['Total'] = $r['Info']['Progress']['UnResponded'] + $r['Info']['Progress']['Responded'];
//         if ($r['Info']['status'] != 'pending') {
//             $r['Info']['Progress']['CompletedPercentage'] = 100;
//         } else if ($r['Info']['Progress']['Total'] == 0) {
//             $r['Info']['Progress']['CompletedPercentage'] = 0;
//         } else {
//             $r['Info']['Progress']['CompletedPercentage'] = ceil(($r['Info']['Progress']['Responded'] / $r['Info']['Progress']['Total']) * 100);
//         }
//         $r['Info']['policy_title'] = ucwords($b['policy_title']);
//         //
//         $r['Info']['timeoff_breakdown'] = get_array_from_minutes(
//             $b['requested_time'],
//             $defaultTimeFrame,
//             $slug
//         );
//         // Get breakdown for all
//         foreach ($r['Info']['timeoff_days'] as $k1 => $v1) {
//             $r['Info']['timeoff_days'][$k1]['breakdown'] = get_array_from_minutes(
//                 $v1['time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//         }
//         // Fetch assigned employees
//         $a = $this->db
//             ->select('
//             timeoff_request_assignment.sid,
//             timeoff_request_assignment.employee_sid,
//             timeoff_request_assignment.is_responded,
//             timeoff_request_assignment.status,
//             timeoff_request_assignment.is_reassigned,
//             timeoff_request_assignment.role,
//             timeoff_request_assignment.created_at,
//             users.first_name,
//             users.last_name,
//             users.access_level_plus,
//             users.is_executive_admin,
//             users.pay_plan_flag,
//             users.job_title,
//             users.access_level,
//             users.email
//         ')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_assignment.timeoff_request_sid', $sid)
//             // ->order_by('concat(users.first_name,users.last_name)', 'ASC', false)
//             ->order_by('timeoff_request_assignment.role', 'ASC')
//             ->get('timeoff_request_assignment');
//         //
//         $r['Assigned'] = $a->result_array();
//         $r['Assigned'] = $this->cleanAssignedArray(
//             $r['Info']['requestId'],
//             $r['Info']['employee_sid'],
//             $r['Info']['company_sid'],
//             $r['Assigned']
//         );
//         $a->free_result();

//         // Fetch history
//         $a = $this->db
//             ->select('
//             timeoff_request_history.request_from_date as requested_date,
//             timeoff_request_history.request_time,
//             timeoff_request_history.timeoff_request_assignment_sid,
//             timeoff_request_history.reason,
//             users.first_name,
//             users.last_name,
//             users.access_level_plus,
//             users.is_executive_admin,
//             users.pay_plan_flag,
//             users.job_title,
//             users.access_level,
//             timeoff_request_history.is_partial_leave,
//             timeoff_request_history.note,
//             timeoff_request_history.status,
//             timeoff_request_history.created_at
//         ')
//             ->join('timeoff_request_assignment', 'timeoff_request_assignment.sid = timeoff_request_history.timeoff_request_assignment_sid')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_history.timeoff_request_sid', $sid)
//             ->order_by('timeoff_request_history.created_at', 'DESC')
//             ->get('timeoff_request_history');
//         //
//         $r['History'] = $a->result_array();
//         $a->free_result();
//         //
//         if (sizeof($r['History'])) {
//             foreach ($r['History'] as $k => $v) {
//                 $r['History'][$k]['timeoff_breakdown'] = get_array_from_minutes(
//                     $v['request_time'],
//                     $defaultTimeFrame,
//                     $slug
//                 );
//             }
//         }
//         // Fetch Conversation
//         $a = $this->db
//             ->select('
//             request_from_date as requested_date,
//             sid,
//             timeoff_request_assignment_sid,
//             request_time,
//             sent,
//             request_status as status,
//             created_at,
//             is_active
//         ')
//             ->where('timeoff_request_sid', $sid)
//             ->get('timeoff_request_conversation');
//         //
//         $r['Conversation'] = $a->result_array();
//         $a->free_result();
//         // Fetch Attachments
//         $a = $this->db
//             ->select('
//             sid,
//             document_title,
//             document_type,
//             s3_filename,
//             serialized_data,
//             is_archived,
//             is_completed,
//             created_at
//         ')
//             ->where('timeoff_request_sid', $sid)
//             ->order_by('created_at', 'DESC')
//             ->get('timeoff_attachments');
//         //
//         $r['Attachments'] = $a->result_array();
//         $a->free_result();
//         //
//         return $r;
//     }

//     /**
//      * Get employee all policies
//      * @param Array $post
//      */
//     function getEmployeeAllPolicies($post)
//     {
//         // error_reporting(E_ALL);
//         // ini_set('display_errors', 1);
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         //
//         $result = $this->db
//             ->select('sid, title, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         // Check for empty data
//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);
//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result();

//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;
//             $policies[$k0]['title'] = ucwords($v0['title']);
//             $policies[$k0]['user_shift_hours'] = $employeeShiftHours;
//             $policies[$k0]['user_shift_minutes'] = $employeeShiftMinutes;
//             $policies[$k0]['employee_timeslot'] = $defaultTimeFrame;
//             $policies[$k0]['format'] = $timeoffFormat;
//             // Time off Category
//             $a = $this->db
//                 ->select('
//                 category_name
//             ')
//                 ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                 ->where('timeoff_policy_categories.timeoff_policy_sid', $v0['sid'])
//                 ->order_by('timeoff_categories.sort_order', 'ASC')
//                 ->get('timeoff_policy_categories');
//             //
//             $policies[$k0]['Category'] = '';
//             $b = $a->row_array();
//             $a->free_result();
//             //
//             if (sizeof($b)) $policies[$k0]['Category'] = $b['category_name'];

//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();

//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();

//                 $policies[$k0]['company_sid'] = $companyId;
//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }

//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     true,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//             } else {
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     unset($policies[$k0]);
//                     continue;
//                 }
//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = '1';
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     false,
//                     $balances,
//                     false,
//                     $employmentStatus
//                 );
//             }
//             // Manipulate policy
//             if ($policies[$k0]['allowed_timeoff'] != -1)
//                 $policies[$k0]['timeoff_breakdown'] = get_array_from_minutes(
//                     $policies[$k0]['allowed_timeoff'],
//                     $defaultTimeFrame,
//                     $slug,
//                     $policies[$k0]['accrual']
//                 );
//             // Check if policy is umlimited or not
//             // if($v0['is_unlimited'] == 1){
//             //     $policies[$k0]['allowed_timeoff'] = -1;
//             //     continue;
//             // }
//             // // Fetch plans
//             // $details = $this->getAllowedTimeOffWithPlan(
//             //     $v0['sid'],
//             //     $years,
//             //     $employeeId,
//             //     $joinedAt
//             // );
//             // if(!sizeof($details)) {
//             //     $policies[$k0]['is_unlimited'] = 1;
//             //     continue;
//             // }

//             // $policies[$k0]['allowed_timeoff'] = ($details['allowed_timeoff'] + $details['pendingTimeOffOld']) - $details['consumed'];

//             // }          // Manipulate policy
//             // if($policies[$k0]['allowed_timeoff'] != -1)
//             // $policies[$k0]['timeoff_breakdown'] = get_array_from_minutes($policies[$k0]['allowed_timeoff'], $defaultTimeFrame, $slug);
//         }
//         return array_values($policies);
//     }
//     //
//     function updateEmployeeTimeOff($post)
//     {
//         // Check if changed
//         // Add History
//         // Change Status
//         // Check for multiple TLS
//         // Send Email
//         $send_email = false;

//         $post['employeeSid'] = isset($post['employerSid']) ? $post['employerSid'] : $post['employeeSid'];

//         //
//         $h = array();
//         $u = array();
//         $u['timeoff_days'] = @json_encode($post['requestedDays']['days']);
//         $h['timeoff_days'] = @json_encode($post['requestedDays']['days']);

//         // Check if something changed
//         $u['request_from_date'] = DateTime::createFromFormat('m/d/Y', $post['startDate'])->format('Y-m-d');
//         //
//         $u['request_to_date'] = DateTime::createFromFormat('m/d/Y', $post['endDate'])->format('Y-m-d');
//         //
//         $u['requested_time'] = $post['requestedDays']['totalTime'];
//         //
//         $u['timeoff_policy_sid'] = $post['policyId'];
//         //
//         $u['compensation_start_time'] = $post['compensationStartTime'];
//         //
//         $u['compensation_end_time'] = $post['compensationEndTime'];
//         //
//         $u['return_date'] = $post['returnDate'] == '' ? NULL : DateTime::createFromFormat('m/d/Y', $post['returnDate'])->format('Y-m-d');
//         //
//         $u['relationship'] = $post['relationship'];
//         //
//         $u['temporary_address'] = $post['temporaryAddress'];
//         //
//         $u['emergency_contact_number'] = $post['emergencyContactNumber'];
//         //
//         $u['reason'] = $post['reason'];
//         //
//         $h = $u;
//         $h['request_time'] = $h['requested_time'];
//         unset($h['requested_time']);
//         $h['reason'] = $post['comment'];

//         $timeoffRequestAssignmentSid = $this->getAssignmentByEmployeeId(
//             $post['requestId'],
//             $post['employeeSid'],
//             $post['request']['level_at'],
//             'sid'
//         );
//         //
//         if (!$timeoffRequestAssignmentSid) {
//             // Insert current user as approver
//             $this->db
//                 ->insert('timeoff_request_assignment', array(
//                     'timeoff_request_sid' => $post['requestId'],
//                     'employee_sid' => $post['employeeSid'],
//                     'role' => 'approver',
//                     'status' => 1
//                 ));
//             //
//             $timeoffRequestAssignmentSid = $this->db->insert_id();
//         }

//         // Add history
//         if (sizeof($h)) {
//             $h['timeoff_request_sid'] = $post['request']['requestId'];
//             $h['timeoff_request_assignment_sid'] = $timeoffRequestAssignmentSid;
//             //
//             $this->db->insert('timeoff_request_history', $h);
//         }
//         // Set last level
//         $newLevel = $lastLevel = $post['request']['level_at'];
//         // Check if handler exists as an approver
//         $ac = $this->db
//             ->where('employee_sid', $post['employeeSid'])
//             ->where('is_archived', 0)
//             ->count_all_results('timeoff_approvers');
//         //
//         if ($ac) $post['approver'] = 1;
//         // Set Reposnded check to 1
//         $this->db
//             ->where('sid', $timeoffRequestAssignmentSid)
//             ->update(
//                 'timeoff_request_assignment',
//                 array(
//                     'is_responded' => 1
//                 )
//             );
//         // Get company setting
//         $approvalCheck = $this->getTimeOffSettingsForCompany(
//             $post['companySid'],
//             'pto_approval_check'
//         );
//         //
//         if ($post['request']['level_at'] == 3) {
//             $send_email = true;
//             $u['status'] = $post['status'];
//         } else {
//             //
//             if ($approvalCheck == 1) {
//                 // Check for response
//                 if ($this->getRespondedCheck($post['requestId'], $post['request']['level_at'])) {
//                     $u['level_at'] = ++$post['request']['level_at'];
//                 }
//             } else {
//                 $u['level_at'] = ++$post['request']['level_at'];
//             }
//             $newLevel = $u['level_at'];
//         }
//         // Update
//         $u['level_status'] = $post['status'];
//         if (isset($post['approver'])) {
//             $u['level_at'] = 3;
//             $u['status'] = $post['status'];
//         }
//         //
//         $u['fmla'] = $post['isFMLA'];
//         //
//         if ($u['fmla'] == null) {
//             // Delete assigned FMLA 
//             $this->db
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->where('document_type', 'generated')
//                 ->delete('timeoff_attachments');
//         } else {
//             // Get last fmla name
//             $a = $this->db
//                 ->select('serialized_data')
//                 ->where('document_type', 'generated')
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->get('timeoff_attachments');
//             //
//             $b = $a->row_array();
//             $a = $a->free_result();
//             if (sizeof($b)) {
//                 $s = @json_decode($b['serialized_data'], true);
//                 if (isset($s['type']) && $s['type'] != $u['fmla']) {
//                     // Delete assigned FMLA 
//                     $this->db
//                         ->where('timeoff_request_sid', $post['requestId'])
//                         ->where('document_type', 'generated')
//                         ->delete('timeoff_attachments');
//                     //
//                     $this->db
//                         ->insert('timeoff_attachments', array(
//                             'timeoff_request_sid' => $post['requestId'],
//                             'document_type' => 'generated',
//                             'document_title' => $u['fmla'],
//                             'serialized_data' => json_encode($post['fmla'])
//                         ));
//                 }
//             } else {
//                 //
//                 $this->db
//                     ->insert('timeoff_attachments', array(
//                         'timeoff_request_sid' => $post['requestId'],
//                         'document_type' => 'generated',
//                         'document_title' => $u['fmla'],
//                         'serialized_data' => json_encode($post['fmla'])
//                     ));
//             }
//         }
//         //
//         $this->db
//             ->where('sid', $post['requestId'])
//             ->update('timeoff_requests', $u);

//         return ['SendEmailToAll' => $send_email, 'SendEmailToNextLevel' => ($lastLevel != $newLevel ? $newLevel : 0)];
//     }
//     //
//     function updateEmployeeTimeOffFromEmployee($post)
//     {
//         $post['employeeSid'] = isset($post['employerSid']) ? $post['employerSid'] : $post['employeeSid'];

//         // Set update array
//         $upd = array();
//         $upd['company_sid'] = $post['companySid'];
//         $upd['employee_sid'] = $post['employeeSid'];
//         $upd['timeoff_policy_sid'] = $post['policyId'];
//         $upd['requested_time'] = $post['requestedDays']['totalTime'];
//         $upd['allowed_timeoff'] = $post['allowedTimeOff'];
//         $upd['request_from_date'] = DateTime::createFromFormat('m/d/Y', $post['startDate'])->format('Y-m-d');
//         $upd['request_to_date'] = DateTime::createFromFormat('m/d/Y', $post['endDate'])->format('Y-m-d');
//         $upd['reason'] = $post['reason'];
//         $upd['timeoff_days'] = @json_encode($post['requestedDays']['days']);
//         $upd['creator_sid'] = $post['employeeSid'];
//         $upd['level_at'] = 1;
//         $upd['level_status'] = 'pending';
//         $upd['fmla'] = isset($post['isFMLA']) && !empty($post['isFMLA'])  && $post['isFMLA'] != 'no' ? strtolower($post['isFMLA']) : NULL;
//         //
//         // Phase 3
//         if (isset($post['compensationStartTime'])) $upd['compensation_start_time'] = $post['compensationStartTime'];
//         if (isset($post['compensationEndTime'])) $upd['compensation_end_time'] = $post['compensationEndTime'];
//         if (isset($post['returnDate'])) {
//             $upd['return_date'] = $post['returnDate'] == '' ? NULL : DateTime::createFromFormat('m/d/Y', $post['returnDate'])->format('Y-m-d');
//         }
//         if (isset($post['relationship'])) $upd['relationship'] = $post['relationship'];
//         if (isset($post['temporaryAddress'])) $upd['temporary_address'] = $post['temporaryAddress'];
//         if (isset($post['emergencyContactNumber'])) $upd['emergency_contact_number'] = $post['emergencyContactNumber'];
//         // Reset uponm every change
//         $this->db
//             ->where('sid', $post['requestId'])
//             ->update('timeoff_requests', $upd);
//         //
//         $this->db
//             ->where('timeoff_request_sid', $post['requestId'])
//             ->update('timeoff_request_assignment', array(
//                 'is_responded' => 0
//             ));

//         //
//         if ($upd['fmla'] == null) {
//             // Delete assigned FMLA 
//             $this->db
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->where('document_type', 'generated')
//                 ->delete('timeoff_attachments');
//         } else {
//             // Get last fmla name
//             $a = $this->db
//                 ->select('serialized_data')
//                 ->where('document_type', 'generated')
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->get('timeoff_attachments');
//             //
//             $b = $a->row_array();
//             $a = $a->free_result();
//             if (sizeof($b)) {
//                 $s = @json_decode($b['serialized_data'], true);
//                 if (isset($s['type']) && $s['type'] != $upd['fmla']) {
//                     // Delete assigned FMLA 
//                     $this->db
//                         ->where('timeoff_request_sid', $post['requestId'])
//                         ->where('document_type', 'generated')
//                         ->delete('timeoff_attachments');
//                     //
//                     $this->db
//                         ->insert('timeoff_attachments', array(
//                             'timeoff_request_sid' => $post['requestId'],
//                             'document_type' => 'generated',
//                             'document_title' => $upd['fmla'],
//                             'serialized_data' => json_encode($post['fmla'])
//                         ));
//                 }
//             } else {
//                 //
//                 $this->db
//                     ->insert('timeoff_attachments', array(
//                         'timeoff_request_sid' => $post['requestId'],
//                         'document_type' => 'generated',
//                         'document_title' => $upd['fmla'],
//                         'serialized_data' => json_encode($post['fmla'])
//                     ));
//             }
//         }

//         return $post['requestId'];
//     }

//     //
//     function updateEmployeeTimeOffDraft($post)
//     {

//         $post['employeeSid'] = isset($post['employerSid']) ? $post['employerSid'] : $post['employeeSid'];

//         // Set update array
//         $upd = array();
//         $upd['company_sid'] = $post['companySid'];
//         $upd['employee_sid'] = $post['employeeSid'];
//         $upd['timeoff_policy_sid'] = $post['policyId'];
//         $upd['allowed_timeoff'] = $post['allowedTimeOff'];
//         $upd['requested_time'] = $post['requestedDays']['totalTime'];
//         $upd['request_from_date'] = DateTime::createFromFormat('m/d/Y', $post['startDate'])->format('Y-m-d');
//         $upd['request_to_date'] = DateTime::createFromFormat('m/d/Y', $post['endDate'])->format('Y-m-d');
//         $upd['timeoff_days'] = @json_encode($post['requestedDays']['days']);
//         $upd['reason'] = $post['reason'];
//         $upd['creator_sid'] = $post['employeeSid'];
//         $upd['is_draft'] = 1;
//         $upd['fmla'] = isset($post['isFMLA']) && $post['isFMLA'] != 'no' ? strtolower($post['isFMLA']) : NULL;
//         if (isset($post['startTime'])) $upd['start_time'] = $post['startTime'];
//         if (isset($post['endTime'])) $upd['end_time'] = $post['endTime'];

//         // Phase 3
//         if (isset($post['compensationStartTime'])) $upd['compensation_start_time'] = $post['compensationStartTime'];
//         if (isset($post['compensationEndTime'])) $upd['compensation_end_time'] = $post['compensationEndTime'];
//         if (isset($post['returnDate'])) {
//             $upd['return_date'] = $post['returnDate'] == '' ? NULL : DateTime::createFromFormat('m/d/Y', $post['returnDate'])->format('Y-m-d');
//         }
//         if (isset($post['relationship'])) $upd['relationship'] = $post['relationship'];
//         if (isset($post['temporaryAddress'])) $upd['temporary_address'] = $post['temporaryAddress'];
//         if (isset($post['emergencyContactNumber'])) $upd['emergency_contact_number'] = $post['emergencyContactNumber'];
//         // Phase 3 ends
//         //
//         $this->db
//             ->where('sid', $post['requestId'])
//             ->update('timeoff_requests', $upd);

//         //
//         if ($upd['fmla'] == null) {
//             // Delete assigned FMLA 
//             $this->db
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->where('document_type', 'generated')
//                 ->delete('timeoff_attachments');
//         } else {
//             // Get last fmla name
//             $a = $this->db
//                 ->select('serialized_data')
//                 ->where('document_type', 'generated')
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->get('timeoff_attachments');
//             //
//             $b = $a->row_array();
//             $a = $a->free_result();
//             if (sizeof($b)) {
//                 $s = @json_decode($b['serialized_data'], true);
//                 if (isset($s['type']) && $s['type'] != $upd['fmla']) {
//                     // Delete assigned FMLA 
//                     $this->db
//                         ->where('timeoff_request_sid', $post['requestId'])
//                         ->where('document_type', 'generated')
//                         ->delete('timeoff_attachments');
//                     //
//                     $this->db
//                         ->insert('timeoff_attachments', array(
//                             'timeoff_request_sid' => $post['requestId'],
//                             'document_type' => 'generated',
//                             'document_title' => $upd['fmla'],
//                             'serialized_data' => json_encode($post['fmla'])
//                         ));
//                 }
//             } else {
//                 //
//                 $this->db
//                     ->insert('timeoff_attachments', array(
//                         'timeoff_request_sid' => $post['requestId'],
//                         'document_type' => 'generated',
//                         'document_title' => $upd['fmla'],
//                         'serialized_data' => json_encode($post['fmla'])
//                     ));
//             }
//         }
//         //
//         return $post['requestId'];
//     }

//     //
//     function updateAndConvertEmployeeTimeOffDraft($post)
//     {

//         $post['employeeSid'] = isset($post['employerSid']) ? $post['employerSid'] : $post['employeeSid'];

//         // Set update array
//         $upd = array();
//         $upd['company_sid'] = $post['companySid'];
//         $upd['employee_sid'] = $post['employeeSid'];
//         $upd['timeoff_policy_sid'] = $post['policyId'];
//         $upd['allowed_timeoff'] = $post['allowedTimeOff'];
//         $upd['requested_time'] = $post['requestedDays']['totalTime'];
//         $upd['request_from_date'] = DateTime::createFromFormat('m/d/Y', $post['startDate'])->format('Y-m-d');
//         $upd['request_to_date'] = DateTime::createFromFormat('m/d/Y', $post['endDate'])->format('Y-m-d');
//         $upd['timeoff_days'] = @json_encode($post['requestedDays']['days']);
//         $upd['reason'] = $post['reason'];
//         $upd['creator_sid'] = $post['employeeSid'];
//         $upd['is_draft'] = 0;
//         $upd['fmla'] = isset($post['isFMLA']) && $post['isFMLA'] != 'no' ? strtolower($post['isFMLA']) : NULL;
//         if (isset($post['startTime'])) $upd['start_time'] = $post['startTime'];
//         if (isset($post['endTime'])) $upd['end_time'] = $post['endTime'];
//         // Phase 3
//         if (isset($post['compensationStartTime'])) $upd['compensation_start_time'] = $post['compensationStartTime'];
//         if (isset($post['compensationEndTime'])) $upd['compensation_end_time'] = $post['compensationEndTime'];
//         if (isset($post['returnDate'])) {
//             $upd['return_date'] = $post['returnDate'] == '' ? NULL : DateTime::createFromFormat('m/d/Y', $post['returnDate'])->format('Y-m-d');
//         }
//         if (isset($post['relationship'])) $upd['relationship'] = $post['relationship'];
//         if (isset($post['temporaryAddress'])) $upd['temporary_address'] = $post['temporaryAddress'];
//         if (isset($post['emergencyContactNumber'])) $upd['emergency_contact_number'] = $post['emergencyContactNumber'];
//         //
//         $this->db
//             ->where('sid', $post['requestId'])
//             ->update('timeoff_requests', $upd);

//         //
//         if ($upd['fmla'] == null) {
//             // Delete assigned FMLA 
//             $this->db
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->where('document_type', 'generated')
//                 ->delete('timeoff_attachments');
//         } else {
//             // Get last fmla name
//             $a = $this->db
//                 ->select('serialized_data')
//                 ->where('document_type', 'generated')
//                 ->where('timeoff_request_sid', $post['requestId'])
//                 ->get('timeoff_attachments');
//             //
//             $b = $a->row_array();
//             $a = $a->free_result();
//             if (sizeof($b)) {
//                 $s = @json_decode($b['serialized_data'], true);
//                 if (isset($s['type']) && $s['type'] != $upd['fmla']) {
//                     // Delete assigned FMLA 
//                     $this->db
//                         ->where('timeoff_request_sid', $post['requestId'])
//                         ->where('document_type', 'generated')
//                         ->delete('timeoff_attachments');
//                     //
//                     $this->db
//                         ->insert('timeoff_attachments', array(
//                             'timeoff_request_sid' => $post['requestId'],
//                             'document_type' => 'generated',
//                             'document_title' => $upd['fmla'],
//                             'serialized_data' => json_encode($post['fmla'])
//                         ));
//                 }
//             } else {
//                 //
//                 $this->db
//                     ->insert('timeoff_attachments', array(
//                         'timeoff_request_sid' => $post['requestId'],
//                         'document_type' => 'generated',
//                         'document_title' => $upd['fmla'],
//                         'serialized_data' => json_encode($post['fmla'])
//                     ));
//             }
//         }
//         //
//         return $post['requestId'];
//     }
//     //
//     function getAssignmentByEmployeeId($requestId, $employeeId, $level = 1, $column = '*')
//     {
//         $a = $this->db
//             ->select($column)
//             ->where('timeoff_request_sid', $requestId)
//             ->where('employee_sid', $employeeId)
//             ->where('role', $level == 1 ? 'teamlead' : ($level == 2 ? 'supervisor' : 'approver'))
//             ->get('timeoff_request_assignment');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         return sizeof($b) && $column != '*' ? $b[$column] : $b;
//     }
//     //
//     function getTimeOffSettingsForCompany($companySid, $column = '*')
//     {
//         $a = $this->db
//             ->select($column)
//             ->where('company_sid', $companySid)
//             ->get('timeoff_settings');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         return sizeof($b) && $column != '*' ? $b[$column] : $b;
//     }
//     //
//     function getRespondedCheck($requestId, $level)
//     {
//         $level = $level == 1 ? 'teamlead' : ($level == 2 ? 'supervisor' : 'approver');
//         $a = $this->db
//             ->where('timeoff_request_sid', $requestId)
//             ->where('role', $level)
//             ->count_all_results('timeoff_request_assignment');
//         $b = $this->db
//             ->where('timeoff_request_sid', $requestId)
//             ->where('role', $level)
//             ->where('is_responded', 1)
//             ->count_all_results('timeoff_request_assignment');
//         //
//         return $a == $b;
//     }
//     //
//     function getCompanyName($companyId)
//     {
//         $a = $this->db
//             ->select('CompanyName')
//             ->where('sid', $companyId)
//             ->get('users');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         return isset($b['CompanyName']) ? $b['CompanyName'] : '';
//     }


//     //
//     function insertTimeOffSettings($companySid, $employeeSid)
//     {
//         // return ;
//         // Check for settings
//         $a = $this->db
//             ->where('company_sid', $companySid)
//             ->count_all_results('timeoff_settings');
//         //
//         if (!$a) {
//             $ins = array();
//             $ins['company_sid'] = $companySid;
//             $ins['default_timeslot'] = 8;
//             $ins['pto_approval_check'] = 0;
//             $ins['pto_email_receiver'] = 0;
//             $ins['accural_type'] = 'per year';
//             $ins['accrue_start_day'] = 0;
//             $ins['timeoff_type'] = 'per year';
//             $ins['is_lose_active'] = 0;
//             $ins['timeoff_format_sid'] = 2;
//             $ins['off_days'] = 'saturday,sunday';
//             //
//             $this->db->insert('timeoff_settings', $ins);
//         }

//         //Set Categories
//         $a = $this->db
//             ->select('category_name,timeoff_categories.sid')
//             ->where('company_sid', $companySid)
//             ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'left')
//             ->get('timeoff_categories')->result_array();

//         if (!sizeof($a)) {
//             //Medical
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 4,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 4,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $medCatId = $this->db->insert_id();
//             //Vacation
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 3,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 3,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $vacCatId = $this->db->insert_id();
//             //FMLA
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 2,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 2,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $fmlaCatId = $this->db->insert_id();
//             //Beverment
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 5,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 5,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $bavCatId = $this->db->insert_id();
//             //Other
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 6,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 6,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $othCatId = $this->db->insert_id();
//             //Paid
//             $this->db->insert('timeoff_categories', array(
//                 'timeoff_category_list_sid' => 8,
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'sort_order' => 7,
//                 'is_archived' => 0,
//                 'is_default' => 1,
//                 'status' => 1
//             ));
//             $paidCatId = $this->db->insert_id();
//         }
//          else {
//             $default_cat = array(4 => 'Medical', 3 => 'Vacation', 2 => 'FMLA', 5 => 'Bereavement', 6 => 'Other', 8 => 'Paid');
//             $existed_names = array();
//             foreach ($a as $e) {
//                 $existed_names[$e['sid']] = strtolower($e['category_name']);
//             }
//             foreach ($default_cat as $key => $cat) {
//                 if (!in_array(strtolower($cat), $existed_names)) {
//                     $this->db->insert('timeoff_categories', array(
//                         'timeoff_category_list_sid' => $key,
//                         'company_sid' => $companySid,
//                         'creator_sid' => $employeeSid,
//                         'created_at' => date('Y-m-d H:i:s'),
//                         'sort_order' => $key,
//                         'is_archived' => 0,
//                         'is_default' => 1,
//                         'status' => 1
//                     ));
//                     $Id = $this->db->insert_id();
//                     if ($cat == 'Medical') {
//                         $medCatId = $Id;
//                     } elseif ($cat == 'Vacation') {
//                         $vacCatId = $Id;
//                     } elseif ($cat == 'FMLA') {
//                         $fmlaCatId = $Id;
//                     } elseif ($cat == 'Bereavement') {
//                         $bavCatId = $Id;
//                     } elseif ($cat == 'Other') {
//                         $othCatId = $Id;
//                     } elseif ($cat == 'Paid') {
//                         $paidCatId = $Id;
//                     }
//                 } else {
//                     $Id = array_search(strtolower($cat), $existed_names);
//                     if ($cat == 'Medical') {
//                         $medCatId = $Id;
//                     } elseif ($cat == 'Vacation') {
//                         $vacCatId = $Id;
//                     } elseif ($cat == 'FMLA') {
//                         $fmlaCatId = $Id;
//                     } elseif ($cat == 'Bereavement') {
//                         $bavCatId = $Id;
//                     } elseif ($cat == 'Other') {
//                         $othCatId = $Id;
//                     } elseif ($cat == 'Paid') {
//                         $paidCatId = $Id;
//                     }
//                 }
//             }
//         }


//         // // Set plans
//         // $a = $this->db
//         //     ->select('sid,timeoff_plan_list_sid')
//         //     ->where('company_sid', $companySid)
//         //     ->get('timeoff_plans')->result_array();
//         // //
//         // if (!sizeof($a)) {
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 1, //1
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '2400',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p1Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 7,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '4800',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p2Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 3,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '5280',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p3Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 4,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '5760',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p4Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 9,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '6240',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p5Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 10,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '6720',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p6Id = $this->db->insert_id();
//         //     $this->db->insert('timeoff_plans', array(
//         //         'timeoff_plan_list_sid' => 11,
//         //         'company_sid' => $companySid,
//         //         'allowed_timeoff' => '7200',
//         //         'creator_sid' => $employeeSid,
//         //         'is_default' => 0,
//         //         'sort_order' => 1,
//         //         'created_at' => date('Y-m-d H:i:s'),
//         //         'status' => '1'
//         //     ));
//         //     $p7Id = $this->db->insert_id();
//         // } else {
//         //     $default_cat = array(1 => '2400', 7 => '4800', 3 => '5280', 4 => '5760', 9 => '6240', 10 => '6720', 11 => '7200');
//         //     $existed_names = array();
//         //     foreach ($a as $e) {
//         //         $existed_names[$e['sid']] = $e['timeoff_plan_list_sid'];
//         //     }
//         //     foreach ($default_cat as $key => $hour) {
//         //         if (!in_array($key, $existed_names)) {

//         //             $this->db->insert('timeoff_plans', array(
//         //                 'timeoff_plan_list_sid' => $key,
//         //                 'company_sid' => $companySid,
//         //                 'allowed_timeoff' => $hour,
//         //                 'creator_sid' => $employeeSid,
//         //                 'is_default' => 0,
//         //                 'sort_order' => 1,
//         //                 'created_at' => date('Y-m-d H:i:s'),
//         //                 'status' => '1'
//         //             ));
//         //             $Id = $this->db->insert_id();
//         //             if ($key == 1) {
//         //                 $p1Id = $Id;
//         //             } elseif ($key == 7) {
//         //                 $p2Id = $Id;
//         //             } elseif ($key == 3) {
//         //                 $p3Id = $Id;
//         //             } elseif ($key == 4) {
//         //                 $p4Id = $Id;
//         //             } elseif ($key == 9) {
//         //                 $p5Id = $Id;
//         //             } elseif ($key == 10) {
//         //                 $p6Id = $Id;
//         //             } elseif ($key == 11) {
//         //                 $p7Id = $Id;
//         //             }
//         //         } else {
//         //             $Id = array_search($key, $existed_names);
//         //             if ($key == 1) {
//         //                 $p1Id = $Id;
//         //             } elseif ($key == 7) {
//         //                 $p2Id = $Id;
//         //             } elseif ($key == 3) {
//         //                 $p3Id = $Id;
//         //             } elseif ($key == 4) {
//         //                 $p4Id = $Id;
//         //             } elseif ($key == 9) {
//         //                 $p5Id = $Id;
//         //             } elseif ($key == 10) {
//         //                 $p6Id = $Id;
//         //             } elseif ($key == 11) {
//         //                 $p7Id = $Id;
//         //             }
//         //         }
//         //     }
//         // }


//         // Set policies
//         $a = $this->db
//             ->select("sid,LOWER(title) as title")
//             ->where('company_sid', $companySid)
//             ->get('timeoff_policies')->result_array();
//         //
//         if (!sizeof($a)) {
//             //Medical
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Medical',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 1,
//                 'is_unlimited' => 1,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'company_sid' => $companySid,
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accural_type' => 'pay_per_year',
//                 'accrual_method' => 'unlimited'
//             )); //No plans because unlimited
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $medCatId
//             )); //Link it to categories

//             //Personal
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Personal',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 2,
//                 'is_unlimited' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accural_type' => 'pay_per_year',
//                 'accrual_method' => 'unlimited'
//             )); //No plans because unlimited
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $vacCatId
//             )); //Link it to categories

//             //Leisure
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Leisure',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 3,
//                 'is_unlimited' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accural_type' => 'pay_per_year',
//                 'accrual_method' => 'unlimited'
//             )); //No plans because unlimited
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $vacCatId
//             )); //Link it to categories

//             //PTO
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'PTO',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 4,
//                 'is_unlimited' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $paidCatId
//             )); //Link it to categories

//             //Medical - Doctor Excused
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Medical - Doctor Excused',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 6,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $medCatId
//             )); //Link it to categories

//             //Illness
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Illness',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 7,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $medCatId
//             )); //Link it to categories

//             //Military
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Military',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 8,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //Jury Duty
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Jury Duty',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 9,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //Bereavement - Paid
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Bereavement - Paid',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 10,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $bavCatId
//             )); //Link it to categories

//             //Bereavement - Unpaid
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Bereavement - Unpaid',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 11,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $bavCatId
//             )); //Link it to categories

//             //Suspension - Unpaid
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Suspension - Unpaid',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 12,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //Suspension - Paid
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Suspension - Paid',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 13,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //Unexcused
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Unexcused',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 14,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //Workers Comp
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Workers Comp',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 15,
//                 'is_unlimited' => 1,
//                 'for_admin' => 1,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories

//             //FMLA
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'FMLA',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 16,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $fmlaCatId
//             )); //Link it to categories

//             //FMLA PTO
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'FMLA PTO',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 17,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $fmlaCatId
//             )); //Link it to categories

//             //Training
//             $this->db->insert('timeoff_policies', array(
//                 'title' => 'Training',
//                 'assigned_employees' => 'all',
//                 'sort_order' => 18,
//                 'is_unlimited' => 1,
//                 'for_admin' => 0,
//                 'company_sid' => $companySid,
//                 'created_at' => date('Y-m-d H:i:s'),
//                 'creator_sid' => $employeeSid
//             ));
//             $insertId = $this->db->insert_id();
//             $this->db->insert('timeoff_policy_accural', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'accrual_method' => 'unlimited'
//             ));
//             $this->db->insert('timeoff_policy_categories', array(
//                 'timeoff_policy_sid' => $insertId,
//                 'timeoff_category_sid' => $othCatId
//             )); //Link it to categories
//         } else {
//             $polictTitleCol = array_column($a, 'title');
//             if (!in_array('medical', $polictTitleCol)) {
//                 //Medical
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Medical',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 1,
//                     'is_unlimited' => 1,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'company_sid' => $companySid,
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accural_type' => 'pay_per_year',
//                     'accrual_method' => 'unlimited'
//                 )); //No plans because unlimited
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $medCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('personal', $polictTitleCol)) {
//                 //Personal
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Personal',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 2,
//                     'is_unlimited' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accural_type' => 'pay_per_year',
//                     'accrual_method' => 'unlimited'
//                 )); //No plans because unlimited
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $vacCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('leisure', $polictTitleCol)) {
//                 //Leisure
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Leisure',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 3,
//                     'is_unlimited' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accural_type' => 'pay_per_year',
//                     'accrual_method' => 'unlimited'
//                 )); //No plans because unlimited
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $vacCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('pto', $polictTitleCol)) {
//                 //PTO
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'PTO',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 4,
//                     'is_unlimited' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $paidCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('medical - doctor excused', $polictTitleCol)) {
//                 //Medical - Doctor Excused
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Medical - Doctor Excused',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 6,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $medCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('illness', $polictTitleCol)) {
//                 //Illness
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Illness',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 7,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $medCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('military', $polictTitleCol)) {
//                 //Military
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Military',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 8,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('jury duty', $polictTitleCol)) {
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Jury Duty',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 9,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('bereavement - paid', $polictTitleCol)) {
//                 //Bereavement - Paid
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Bereavement - Paid',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 10,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $bavCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('bereavement - unpaid', $polictTitleCol)) {
//                 //Bereavement - Unpaid
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Bereavement - Unpaid',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 11,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $bavCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('suspension - unpaid', $polictTitleCol)) {
//                 //Suspension - Unpaid
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Suspension - Unpaid',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 12,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('suspension - paid', $polictTitleCol)) {
//                 //Suspension - Paid
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Suspension - Paid',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 13,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('unexcused', $polictTitleCol)) {
//                 //Unexcused
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Unexcused',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 14,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('workers comp', $polictTitleCol)) {
//                 //Workers Comp
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Workers Comp',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 15,
//                     'is_unlimited' => 1,
//                     'for_admin' => 1,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('fmla', $polictTitleCol)) {
//                 //FMLA
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'FMLA',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 16,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $fmlaCatId
//                 )); //Link it to categories
//             }
//             if (!in_array('fmla pto', $polictTitleCol)) {
//                 //FMLA PTO
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'FMLA PTO',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 17,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $fmlaCatId
//                 )); //Link it to categories

//             }
//             if (!in_array('training', $polictTitleCol)) {

//                 //Training
//                 $this->db->insert('timeoff_policies', array(
//                     'title' => 'Training',
//                     'assigned_employees' => 'all',
//                     'sort_order' => 18,
//                     'is_unlimited' => 1,
//                     'for_admin' => 0,
//                     'company_sid' => $companySid,
//                     'created_at' => date('Y-m-d H:i:s'),
//                     'creator_sid' => $employeeSid
//                 ));
//                 $insertId = $this->db->insert_id();
//                 $this->db->insert('timeoff_policy_accural', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'accrual_method' => 'unlimited'
//                 ));
//                 $this->db->insert('timeoff_policy_categories', array(
//                     'timeoff_policy_sid' => $insertId,
//                     'timeoff_category_sid' => $othCatId
//                 )); //Link it to categories
//             }
//         }

//         // Set approvers
//         $a = $this->db
//             ->where('company_sid', $companySid)
//             ->count_all_results('timeoff_approvers');
//         //
//         if (!$a) {
//             // Fetch all access level plus admins
//             $a = $this->db
//                 ->select('sid')
//                 ->where('access_level_plus', 1)
//                 ->where('active', 1)
//                 ->where('parent_sid', $companySid)
//                 ->limit(2)
//                 ->get('users');
//             //
//             $b = $a->result_array();
//             $a->free_result();
//             //
//             if (sizeof($b)) {
//                 foreach ($b as $k => $v) {
//                     $this->db->insert('timeoff_approvers', array(
//                         'company_sid' => $companySid,
//                         'employee_sid' => $v['sid'],
//                         'creator_sid' => $employeeSid,
//                         'status' => 1,
//                         'department_sid' => 'all'
//                     ));
//                 }
//             }
//         }

//         // Add public holidays
//         $previousYear = date('Y', strtotime('-1 year'));
//         $currentYear = date('Y');
//         $nextYear = date('Y', strtotime('+1 year'));
//         //
//         $a = $this->db
//             ->where('company_sid', $companySid)
//             ->where('holiday_year', $previousYear)
//             ->count_all_results('timeoff_holidays');
//         //
//         if (!$a) {
//             $s = $this->db
//                 ->select("
//                 $companySid as company_sid, 
//                 $employeeSid as creator_sid, 
//                 holiday_year, 
//                 holiday_title, 
//                 icon, 
//                 from_date, 
//                 to_date, 
//                 event_link,
//                 status
//             ")
//                 ->where('holiday_year', $previousYear)
//                 ->order_by('from_date', 'ASC')
//                 ->get_compiled_select('timeoff_holiday_list');
//             //
//             $this->db->query("INSERT INTO 
//                 timeoff_holidays (company_sid, creator_sid, holiday_year, holiday_title, icon, from_date, to_date, event_link, status)
//                 $s
//             ");
//         }

//         //
//         $a = $this->db
//             ->where('company_sid', $companySid)
//             ->where('holiday_year', $currentYear)
//             ->count_all_results('timeoff_holidays');
//         //
//         if (!$a) {
//             $s = $this->db
//                 ->select("
//                 $companySid as company_sid, 
//                 $employeeSid as creator_sid, 
//                 holiday_year, 
//                 holiday_title, 
//                 icon, 
//                 from_date, 
//                 to_date, 
//                 event_link,
//                 status
//             ")
//                 ->where('holiday_year', $currentYear)
//                 ->order_by('from_date', 'ASC')
//                 ->get_compiled_select('timeoff_holiday_list');
//             //
//             $this->db->query("INSERT INTO 
//                 timeoff_holidays (company_sid, creator_sid, holiday_year, holiday_title, icon, from_date, to_date, event_link, status)
//                 $s
//             ");
//         }

//         //
//         $a = $this->db
//             ->where('company_sid', $companySid)
//             ->where('holiday_year', $nextYear)
//             ->count_all_results('timeoff_holidays');
//         //
//         if (!$a) {
//             $s = $this->db
//                 ->select("
//                 $companySid as company_sid, 
//                 $employeeSid as creator_sid, 
//                 holiday_year, 
//                 holiday_title, 
//                 icon, 
//                 from_date, 
//                 to_date, 
//                 event_link,
//                 status
//             ")
//                 ->where('holiday_year', $nextYear)
//                 ->order_by('from_date', 'ASC')
//                 ->get_compiled_select('timeoff_holiday_list');
//             //
//             $this->db->query("INSERT INTO 
//                 timeoff_holidays (company_sid, creator_sid, holiday_year, holiday_title, icon, from_date, to_date, event_link, status)
//                 $s
//             ");
//         }
//         // // Check for settings
//         // if(!$this->db->where('company_sid', $companySid)->count_all_results('timeoff_settings')){
//         //   $this->db->insert('timeoff_settings', array(
//         //     'company_sid' => $companySid,
//         //     'default_timeslot' => 8
//         //   ));
//         // }
//     }


//     //
//     function getIncomingRequestReportByPerm(
//         $post
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.employee_sid,
//             timeoff_requests.company_sid,
//             timeoff_requests.requested_time,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.request_from_date as requested_date,
//             timeoff_requests.request_to_date,
//             timeoff_requests.is_partial_leave,
//             timeoff_requests.status,
//             timeoff_requests.level_status,
//             timeoff_requests.partial_leave_note,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_requests.archive,
//             timeoff_requests.timeoff_days,
//             timeoff_policies.title as policy_title,
//         ")
//             ->from('timeoff_requests')
//             ->where('timeoff_requests.company_sid', $post['companySid'])
//             ->where('timeoff_requests.is_draft', '0')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner');

//         if (isset($post['sortType']) && $post['sortType'] == 'created_asc') {
//             $this->db
//                 ->order_by('timeoff_requests.created_at, timeoff_requests.status', 'ASC', false);
//         } else if (isset($post['sortType']) && $post['sortType'] == 'created_desc') {
//             $this->db
//                 ->order_by('timeoff_requests.created_at', 'DESC', false);
//         } else if (isset($post['sortType']) && $post['sortType'] == 'created_start_desc') {
//             $this->db
//                 ->order_by('requested_date', 'DESC')
//                 ->order_by('timeoff_requests.status', 'DESC');
//         } else if (isset($post['sortType']) && $post['sortType'] == 'created_start_asc') {
//             $this->db
//                 ->order_by('requested_date', 'ASC')
//                 ->order_by('timeoff_requests.status', 'DESC');
//         }  else if (isset($post['sortType']) && $post['sortType'] == 'upcoming') {
//             $this->db->order_by('requested_date', 'ASC');
//         } else {
//             $this->db
//                 ->order_by('requested_date <= CURDATE()', 'ASC', false)
//                 ->order_by('requested_date >= CURDATE()', 'ASC', false);
//         }
//         //
//         if ($post['startDate'] != '' && $post['endDate'] != '' && $post['startDate'] != 'all' && $post['endDate'] != 'all') $this->db->where('timeoff_requests.request_from_date between "' . ($post['startDate']) . '" and "' . ($post['endDate']) . '"', null);
//         //
//         if ($post['status'] == 'archive') {
//             $this->db->where('timeoff_requests.archive', 1);
//             $this->db->where('timeoff_policies.is_archived', 1);
//         } else {
//             $this->db->where('timeoff_requests.archive', 0);
//             $this->db->where('timeoff_policies.is_archived', 0);
//             if ($post['status'] != 'all') $this->db->where('timeoff_requests.status', $post['status']);
//         }
//         //
//         if ($post['policySid'] != 'all') $this->db->where('timeoff_requests.timeoff_policy_sid', $post['policySid']);
//         if ($post['requesterSid'] != 'all') $this->db->where('timeoff_requests.employee_sid', $post['requesterSid']);
//         if(isset($post['TeamMembers']) && count($post['TeamMembers'])) $this->db->where_in('timeoff_requests.employee_sid', array_column($post['TeamMembers'], 'sid'));

//         if (isset($post['sortType']) && $post['sortType'] == 'upcoming') {
//             $this->db->where('timeoff_requests.request_from_date >= ', 'CURDATE()', false);
//         }
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->result_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         // Get company default time
//         $a = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $post['companySid'])
//             ->limit(1)
//             ->get();
//         //
//         $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
//         $a->free_result();
//         //
//         $isAllowed = $this->db
//             ->where('sid', $post['employeeSid'])
//             ->group_start()
//             ->where('pay_plan_flag', 1)
//             ->or_where('access_level_plus', 1)
//             ->group_end()
//             ->count_all_results('users');
//         //
//         if(isset($post['TeamMembers']) && count($post['TeamMembers'])) $isAllowed = 1;
//         //
//         foreach ($b as $k => $v) {
//             // Fetch assigned employees
//             $a = $this->db
//                 ->select('
//                 timeoff_request_assignment.sid,
//                 timeoff_request_assignment.employee_sid,
//                 timeoff_request_assignment.role,
//                 users.email
//             ')
//             ->join('users', 'users.sid = timeoff_request_assignment.employee_sid')
//             ->where('timeoff_request_assignment.timeoff_request_sid', $v['requestId'])
//             ->order_by('sid', 'ASC')
//             ->get('timeoff_request_assignment');
//             //
//             $Assigned = $a->result_array();
//             $this->cleanAssignedArray(
//                 $v['requestId'],
//                 $v['employee_sid'],
//                 $v['company_sid'],
//                 $Assigned
//             );

//             if (!$isAllowed) {
//                 // Check if time off was assigned to logged in employee
//                 if (!$this->db
//                     ->where('employee_sid', $post['employeeSid'])
//                     ->where('timeoff_request_sid', $v['requestId'])
//                     ->count_all_results('timeoff_request_assignment')) {
//                     unset($b[$k]);
//                     continue;
//                 }
//             }

//             // Fetch employee joining date
//             $a = $this->db
//                 ->select('
//                 joined_at,
//                 first_name,
//                 last_name,
//                 access_level_plus,
//                 access_level,
//                 pay_plan_flag,
//                 job_title,
//                 is_executive_admin,
//                 user_shift_hours, 
//                 user_shift_minutes,
//                 concat(first_name," ",last_name) as full_name,
//                 profile_picture as img,
//                 employee_number
//             ')
//                 ->from('users')
//                 ->where('sid', $v['employee_sid'])
//                 ->limit(1)
//                 ->get();
//             //
//             $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
//             $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//             $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//             //
//             $b[$k]['full_name'] = $a->row_array()['full_name'];
//             $b[$k]['first_name'] = $a->row_array()['full_name'];
//             $b[$k]['last_name'] = $a->row_array()['full_name'];
//             $b[$k]['access_level'] = $a->row_array()['access_level'];
//             $b[$k]['access_level_plus'] = $a->row_array()['access_level_plus'];
//             $b[$k]['job_title'] = $a->row_array()['job_title'];
//             $b[$k]['is_executive_admin'] = $a->row_array()['is_executive_admin'];
//             $b[$k]['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
//             $b[$k]['img'] = $a->row_array()['img'];
//             $b[$k]['employee_number'] = $a->row_array()['employee_number'];
//             //
//             $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//             $a->free_result();
//             //
//             $this->setTimeOffDays(
//                 $b[$k],
//                 $defaultTimeFrame,
//                 $slug
//             );
//             // Fetch non responded employees
//             $b[$k]['Progress']['UnResponded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 0)
//                 ->count_all_results('timeoff_request_assignment');
//             // Fetch responded employees
//             $b[$k]['Progress']['Responded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 1)
//                 ->count_all_results('timeoff_request_assignment');

//             //
//             $b[$k]['Progress']['Total'] = $b[$k]['Progress']['UnResponded'] + $b[$k]['Progress']['Responded'];
//             if ($b[$k]['Progress']['Total'] != 0) {
//                 $b[$k]['Progress']['CompletedPercentage'] = ceil(($b[$k]['Progress']['Responded'] / $b[$k]['Progress']['Total']) * 100);
//             } else {
//                 $b[$k]['Progress']['CompletedPercentage'] = 0;
//             }

//             if ($v['status'] != 'pending') {
//                 $b[$k]['Progress']['CompletedPercentage'] = 100;
//             }

//             $b[$k]['policy_title'] = ucwords($v['policy_title']);
//             //
//             $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
//                 $v['requested_time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//             $b[$k]['slug'] = $slug;
//             $b[$k]['defaultTimeFrame'] = $defaultTimeFrame;

//             // Date: 19/10/2020
//             // note: The below line comment because we allow archive PTO request to list
//             // if ($v['archive'] == 0) {
//                 // Time off Category
//                 $a = $this->db
//                     ->select('
//                     category_name
//                 ')
//                     ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                     ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                     ->where('timeoff_policy_categories.timeoff_policy_sid', $v['policyId'])
//                     ->order_by('timeoff_categories.sort_order', 'ASC')
//                     ->get('timeoff_policy_categories');
//                 //
//                 $b[$k]['Category'] = '';
//                 $c = $a->row_array();
//                 $a->free_result();
//                 if (sizeof($c)) $b[$k]['Category'] = $c['category_name'];

//                 // Sort by policy
//                 if (!isset($r[($b[$k]['Category'])])) $r[($b[$k]['Category'])] = ($b[$k]['Category']);

//                 // Sort by policy
//                 // if(!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
//             // }
//         }
//         //
//         // echo '<pre>';
//         // print_r($b);
//         // die();
//         asort($r);
//         $r = array_values($r);
//         return ['Requests' => array_values($b), 'Titles' => $r];
//     }


//     //
//     private function getEmployeeTimeOffShifts($employeeId)
//     {
//         $r = array();
//         $r['joinedAt'] = null;
//         $r['years'] = 1;
//         $r['employmentStatus'] = 'permanent';
//         $r['employmentType'] = 'full-time';
//         $r['employeeShiftHours'] = PTO_DEFAULT_SLOT;
//         $r['employeeShiftMinutes'] = PTO_DEFAULT_SLOT_MINUTES;
//         // Fetch employee joining date
//         $a = $this->db
//             ->select('
//             joined_at, 
//             registration_date, 
//             user_shift_hours, 
//             user_shift_minutes,
//             employee_status,
//             employee_type
//         ')
//             ->from('users')
//             ->where('sid', $employeeId)
//             ->limit(1)
//             ->get();
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         //
//         $r['joinedAt'] = $b['joined_at'] != null && $b['joined_at'] != '' ? $b['joined_at'] : ($b['registration_date'] != null && $b['registration_date'] != '' ? $b['registration_date'] : null);
//         //
//         $r['employeeShiftHours'] = $b['user_shift_hours'];
//         $r['employeeShiftMinutes'] = $b['user_shift_minutes'];
//         $r['employmentType'] = $b['employee_type'];
//         $r['employmentStatus'] = $b['employee_status'];
//         $r['years'] = $this->getYearsFromDate($r['joinedAt']);
//         //
//         return $r;
//     }

//     //
//     private function getYearsFromDate($date)
//     {
//         if ($date == null) return 1;
//         // Reset joining date
//         // Calculate the difference from joining date and current date
//         $joinDate = new DateTime($date);
//         $currentDate = new DateTime();
//         // Difference
//         $difference = $joinDate->diff($currentDate);
//         //
//         return $difference->days;
//         // Get difference in years
//         $years = ceil($difference->days / (365 + getLeapYears($joinDate->format('Y'), $currentDate->format('Y'))));
//         if ($years <= 0) {
//             return ceil($difference->days / 30);
//         }
//         //
//         return yearsToMonths($years);
//     }

//     //
//     function updateRequestColumn($requestId, $data)
//     {
//         $this->db
//             ->where('sid', $requestId)
//             ->update('timeoff_requests', $data);
//     }

//     //
//     function fetchTimeOffHandlers($employeeSid, $companySid, $role)
//     {
//         //
//         if($role == 1){
//             // Get teamleads
//             $all = $this->getTeams($companySid, $employeeSid);
//         } else if($role == 2){
//             // Get Supervisors
//             $all = $this->getDepartments($companySid, $employeeSid);
//         } else{
//             return $this->getApprovers($companySid, $employeeSid);
//         }
//         //
//         if(!count($all)){
//             return $this->getApprovers($companySid, $employeeSid);
//         }

//         return $all;
//     }


//     function getApprovers(
//         $companySid,
//         $employeeSid
//     ){
//         // Set default holders
//         $departmentIds = $this->getDepartments($companySid, $employeeSid, true);
//         $teamIds = $this->getTeams($companySid, $employeeSid, true);
//         $all = [];
//         // Get Approvers for all departments
//         $a = $this->db
//         ->select('
//             timeoff_approvers.employee_sid as id, 
//             users.email, 
//             CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "approver" as type
//         ')
//         ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         ->where('timeoff_approvers.company_sid', $companySid)
//         ->where('timeoff_approvers.department_sid', 'all')
//         ->where('timeoff_approvers.is_archived', 0)
//         ->where('timeoff_approvers.is_department', '1')
//         ->get('timeoff_approvers');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(count($b)) $all = array_merge($all, $b);

//         // Get Approvers for all teams
//         $a = $this->db
//         ->select('
//             timeoff_approvers.employee_sid as id, 
//             users.email, 
//             CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "approver" as type
//         ')
//         ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
//         ->where('timeoff_approvers.company_sid', $companySid)
//         ->where('timeoff_approvers.department_sid', 'all')
//         ->where('timeoff_approvers.is_archived', 0)
//         ->where('timeoff_approvers.is_department', '0')
//         ->get('timeoff_approvers');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(count($b)) $all = array_merge($all, $b);

//         // Get Approvers for specific departments
//         if(count($departmentIds)){
//             $a = $this->db
//             ->select('
//                 timeoff_approvers.employee_sid as id, 
//                 users.email, 
//                 CONCAT(users.first_name," ", users.last_name) as full_name, 
//                 "approver" as type
//             ')
//             ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner');
//             //
//             $this->db->group_start();
//             foreach($departmentIds as $k => $dt)  {
//                 if($k == 0) $this->db->where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//                 else $this->db->or_where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//             }
//             $a = $this->db
//             ->group_end()
//             ->where('timeoff_approvers.company_sid', $companySid)
//             ->where('timeoff_approvers.is_archived', 0)
//             ->where('timeoff_approvers.is_department', '1')
//             ->get('timeoff_approvers');
//             //
//             $b = $a->result_array();
//             $a = $a->free_result();
//             //
//             if(count($b)) $all = array_merge($all, $b);
//         }

//         // Get Approvers for specific teams
//         if(count($teamIds)){
//             $a = $this->db
//             ->select('
//             timeoff_approvers.employee_sid as id, 
//             users.email, 
//             CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "approver" as type
//             ')
//             ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner');
//             //
//             $this->db->group_start();
//             foreach($teamIds as $k => $dt)  {
//                 if($k == 0) $this->db->where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//                 else $this->db->or_where('FIND_IN_SET("'.( $dt ).'", timeoff_approvers.department_sid) > 0', false, false); 
//             }
//             $a = $this->db
//             ->group_end()
//             ->where('timeoff_approvers.company_sid', $companySid)
//             ->where('timeoff_approvers.is_archived', 0)
//             ->where('timeoff_approvers.is_department', '0')
//             ->get('timeoff_approvers');
//             //
//             $b = $a->result_array();
//             $a = $a->free_result();
//             //
//             if(count($b)) $all = array_merge($all, $b);
//         }

//         //
//         return $b;
//     }


//     function getDepartments(
//         $companySid,
//         $employeeSid,
//         $rt = false
//     ){
//         $a = $this->db
//             ->select('
//             supervisor as id, 
//             departments_management.sid as department_sid, 
//             users.email, CONCAT(users.first_name," ", users.last_name) as full_name, 
//             "supervisor" as type
//         ')
//         ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
//         ->join('users', 'users.sid = departments_management.supervisor', 'inner')
//         ->where('departments_employee_2_team.employee_sid', $employeeSid)
//         ->where('departments_management.status', 1)
//         ->where('departments_management.is_deleted', 0)
//         ->where('departments_management.company_sid', $companySid)
//         ->group_by('supervisor')
//         ->get('departments_employee_2_team');
//         //
//         $all = $a->result_array();
//         $a = $a->free_result();
//         //
//         if($rt && count($all)) return array_column($all, 'department_sid');
//         //
//         return $all;
//     }

//     function getTeams(
//         $companySid,
//         $employeeSid,
//         $rt = false
//     ){
//         // Get teamleads
//         $a = $this->db
//         ->select('team_lead as id, departments_team_management.sid as team_sid,  users.email, CONCAT(users.first_name," ", users.last_name) as full_name, "teamlead" as type')
//         ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
//         ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
//         ->join('users', 'users.sid = departments_team_management.team_lead', 'inner')
//         ->where('departments_team_management.status', 1)
//         ->where('departments_team_management.is_deleted', 0)
//         ->where('departments_management.is_deleted', 0)
//         ->where('departments_management.status', 1)
//         ->where('departments_management.company_sid', $companySid)
//         ->where('employee_sid', $employeeSid)
//         ->group_by('team_lead')
//         ->get('departments_employee_2_team');
//         //
//         $all = $a->result_array();
//         $a = $a->free_result();
//         //
//         if($rt && count($all)) return array_column($all, 'team_sid');
//         //
//         return $all;
//     }


//     //
//     function getPolicyIdByTitle($policyTitle, $companySid)
//     {
//         $a = $this->db
//             ->select('sid')
//             ->where("LOWER(title) = '" . (strtolower($policyTitle)) . "'", null)
//             ->where('company_sid', $companySid)
//             ->get('timeoff_policies');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         return sizeof($b) ? $b['sid'] : false;
//     }

//     //
//     function getEmployeeIdByEmail($emailAddress, $companySid)
//     {
//         $a = $this->db
//             ->select('sid, user_shift_minutes, user_shift_hours')
//             ->where("LOWER(email) = '" . (strtolower($emailAddress)) . "'", null)
//             ->where('parent_sid', $companySid)
//             ->get('users');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         //
//         return $b;
//     }

//     //
//     function hasTimeOff($date, $employeeId, $policyId)
//     {
//         return
//             $this->db
//             ->where('employee_sid', $employeeId)
//             ->where('timeoff_policy_sid', $policyId)
//             ->where('request_from_date', $date)
//             ->where('status != "cancelled"', null)
//             ->count_all_results('timeoff_requests');
//     }

//     //
//     function insertTimeOffRequestFromImport($d)
//     {
//         $this->db->insert('timeoff_requests', $d);
//         return $this->db->insert_id();
//     }

//     //
//     function insertTimeOffRequestAssignmentFromImport($d)
//     {
//         $this->db->insert('timeoff_request_assignment', $d);
//         return $this->db->insert_id();
//     }

//     //
//     function insertTimeOffRequestHistoryFromImport($d)
//     {
//         $this->db->insert('timeoff_request_history', $d);
//         return $this->db->insert_id();
//     }



//     function checkAndInsertAssignedRequest($requestId, $employerId)
//     {
//         $a = $this->db
//             ->select('sid')
//             ->where('timeoff_request_sid', $requestId)
//             ->where('employee_sid', $employerId)
//             ->where('role', 'approver')
//             ->get('timeoff_request_assignment');
//         //
//         $b = $a->row_array();
//         $a->free_result();
//         if (sizeof($b)) {
//             return $b['sid'];
//         } else {
//             $this->db->insert(
//                 'timeoff_request_assignment',
//                 array(
//                     'timeoff_request_sid' => $requestId,
//                     'employee_sid' => $employerId,
//                     'role' => 'approver',
//                     'status' => 1
//                 )
//             );
//             return $this->db->insert_id();
//         }
//     }

//     //
//     function addHistoryRecord($ins)
//     {
//         $this->db->insert('timeoff_request_history', $ins);
//         return $this->db->insert_id();
//     }

//     //
//     function login($employeeSid)
//     {
//         $this->db->select('*');
//         $this->db->from('users');
//         $this->db->where('sid', $employeeSid);
//         $this->db->where('active', 1);
//         $this->db->where('terminated_status', 0);
//         $this->db->limit(1);
//         $emp_query                                                              = $this->db->get();

//         if ($emp_query->num_rows() == 1) {
//             $employer                                                       = $emp_query->result_array();
//             //$this->db->select('sid, Logo, username, career_page_type, email, ip_address, registration_date, expiry_date, active, activation_key, verification_key, parent_sid, Location_Country, Location_State, Location_City, Location_Address, PhoneNumber, CompanyName, ContactName, WebSite, Location_ZipCode, profile_picture, first_name, last_name, access_level, job_title, full_employee_application, background_check, job_listing_template_group, linkedin_profile_url, discount_type, discount_amount, has_job_approval_rights, has_applicant_approval_rights, is_primary_admin, is_executive_admin, marketing_agency_sid, is_paid, job_category_industries_sid');
//             $this->db->select('*');
//             $this->db->from('users');
//             $this->db->where('sid', $employer[0]['parent_sid']);
//             $this->db->limit(1);
//             $company                                                        = $this->db->get()->result_array();

//             $data['employer']                                               = $employer[0];
//             $data['company']                                                = $company[0];
//             return $data;
//         } else {
//             return false;
//         }
//     }


//     function updatePlansSort($a)
//     {
//         $this->db
//             ->where('sid', $a['id'])
//             ->update('timeoff_plans', array('sort_order' => $a['sort']));
//     }

//     function updatePolicyOverwriteSort($a)
//     {
//         $this->db
//             ->where('sid', $a['id'])
//             ->update('timeoff_policy_overwrite', array('sort_order' => $a['sort']));
//     }

//     function updateApproversSort($a)
//     {
//         $this->db
//             ->where('sid', $a['id'])
//             ->update('timeoff_approvers', array('sort_order' => $a['sort']));
//     }



//     function updateHolidaySort($a)
//     {
//         $this->db
//             ->where('sid', $a['id'])
//             ->update('timeoff_holidays', array('sort_order' => $a['sort']));
//     }

//     //
//     function checkEmployeeBelongsToLoggedInCompany($employeeId, $companyId)
//     {
//         return $this->db
//             ->where('sid', $employeeId)
//             ->where('parent_sid', $companyId)
//             ->count_all_results('users');
//     }

//     //
//     /**
//      * Get company policies
//      * @param Array $post
//      */
//     function getCompanyPolicies($post)
//     {
//         $companyId = $post['companySid'];
//         //
//         $result = $this->db
//             ->select('sid, title, is_archived')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         return $policies;
//     }


//     /**
//      * Check company category
//      * @param Array $post
//      */
//     function checkIfTypeAlreadyExists($post)
//     {
//         //
//         $this->db
//             ->from('timeoff_categories')
//             ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//             ->where('timeoff_categories.company_sid', $post['companySid'])
//             ->where('timeoff_category_list.category_name', $post['category']);
//         //
//         if (isset($post['typeSid'])) $this->db->where('timeoff_categories.sid <>', $post['typeSid']);
//         //
//         return $this->db->count_all_results();
//     }


//     /**
//      * insert company category
//      * @param Array $post
//      */
//     function insertCompanyType($post)
//     {
//         // Get the category id
//         $a = $this->db
//             ->select('sid')
//             ->where('category_name', $post['category'])
//             ->get('timeoff_category_list');
//         //
//         $b = $a->row_array();
//         $a = $a->free_result();
//         //
//         if (!isset($b['sid']) || $b['sid'] == '') {
//             // Insert type
//             $this->db->insert(
//                 'timeoff_category_list',
//                 array(
//                     'category_name' => $post['category']
//                 )
//             );
//             $categoryId = $this->db->insert_id();
//         } else $categoryId = $b['sid'];
//         // Check if catgeory failed to add
//         if (!$categoryId) return 0;
//         // Add Company category
//         $this->db->insert(
//             'timeoff_categories',
//             array(
//                 'timeoff_category_list_sid' => $categoryId,
//                 'company_sid' => $post['companySid'],
//                 'creator_sid' => $post['employerSid'],
//                 'status' => $post['status'],
//                 'is_archived' => $post['isArchived']
//             )
//         );
//         //
//         return $this->db->insert_id();
//     }


    

//     /**
//      * Drop company policies categories
//      * @param Array $post
//      */
//     function dropPolicyCategories($categoryId)
//     {
//         $this->db
//             ->where('timeoff_category_sid', $categoryId)
//             ->delete('timeoff_policy_categories');
//     }

//     /**
//      * Add company policies categories
//      * @param Array $post
//      */
//     function linkCategoryToPolicy($in)
//     {
//         $this->db
//             ->insert('timeoff_policy_categories', $in);
//         return $this->db->insert_id();
//     }

   
//     /**
//      * Types
//      * @param Array $post
//      */
//     function getCompanyTypes($post)
//     {
//         $this->db
//             ->select('
//             CONCAT(users.first_name," ",users.last_name) as full_name,
//             users.profile_picture as img,
//             users.employee_number,
//             users.sid as employee_id,
//             users.employee_number,
//             users.first_name,
//             users.last_name,
//             users.access_level_plus,
//             users.access_level,
//             users.pay_plan_flag,
//             users.job_title,
//             users.is_executive_admin,
//             timeoff_categories.sid as type_id,
//             timeoff_categories.is_archived,
//             timeoff_categories.status,
//             timeoff_categories.created_at,
//             timeoff_category_list.category_name as type_name
//         ')
//             ->from('timeoff_categories')
//             ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//             ->join('users', 'users.sid = timeoff_categories.creator_sid', 'inner')
//             ->where('timeoff_categories.company_sid', $post['companySid'])
//             ->where('timeoff_categories.is_archived', $post['fetchType'] == 'active' ? 0 : 1)
//             ->order_by('timeoff_categories.sort_order', 'ASC');
//         //
//         if ($post['status'] != '' && $post['status'] != 'all') $this->db->where('timeoff_categories.status', $post['status']);
//         if ($post['employeeSid'] != '' && $post['employeeSid'] != 'all') $this->db->where('timeoff_categories.creator_sid', $post['employeeSid']);
//         if ($post['typeSid'] != '' && $post['typeSid'] != 'all') $this->db->where('timeoff_category_list.sid', $post['typeSid']);
//         if ($post['startDate'] != '' && $post['endDate'] != '') $this->db->where('timeoff_categories.created_at BETWEEN ' . ($post['startDate']) . ' AND ' . ($post['endDate']) . ' ', false);
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if (!sizeof($b)) return $b;
//         // Get policies assigned
//         foreach ($b as $k => $v) {
//             $b[$k]['Policies'] = array();
//             // Fetch policies sids
//             $a = $this->db
//                 ->select('timeoff_policy_sid')
//                 ->where('timeoff_category_sid', $v['type_id'])
//                 ->get('timeoff_policy_categories');
//             //
//             $c = $a->result_array();
//             $a = $a->free_result();
//             //
//             if (sizeof($c)) foreach ($c as $v0) $b[$k]['Policies'][] = $v0['timeoff_policy_sid'];
//         }

//         return $b;
//     }


//     /**
//      * Update types
//      *
//      * @param Array   $formpost
//      *
//      * @return VOID
//      */
//     function updateCompanyTypeWithData($sid, $d)
//     {
//         // Update PTO Group
//         $this->db
//             ->where('sid', $sid)
//             ->update('timeoff_categories', $d);
//     }

//     /**
//      * Drop types
//      *
//      * @param Array   $formpost
//      *
//      * @return VOID
//      */
//     function dropPolicyCategoriesByPolicyId($policyId)
//     {
//         // Update PTO Group
//         $this->db
//             ->where('timeoff_policy_sid', $policyId)
//             ->delete('timeoff_policy_categories');
//     }

//     //
//     function insertAttachment($ins)
//     {
//         $this->db->insert('timeoff_attachments', $ins);
//         return $this->db->insert_id();
//     }

//     //
//     function updateAttachment($ins, $attachmentId)
//     {
//         $this->db
//             ->where('sid', $attachmentId)
//             ->update('timeoff_attachments', $ins);
//         return $attachmentId;
//     }

//     //
//     function removeAttachment($attachmentId)
//     {
//         $this->db
//             ->where('sid', $attachmentId)
//             ->delete('timeoff_attachments');
//     }

//     //
//     function addFMLAToRequest($ins)
//     {
//         $this->db->insert('timeoff_attachments', $ins);
//         return $this->db->insert_id();
//     }

//     //
//     function getLatestFMLAData($requestId)
//     {
//         $a = $this->db
//             ->select('serialized_data')
//             ->where('timeoff_request_sid', $requestId)
//             ->where('document_type', 'generated')
//             ->get('timeoff_attachments');
//         //
//         $b = $a->row_array();
//         $a = $a->free_result();
//         //
//         return $b;
//     }

//     //
//     function udateFMLAEmployerSection(
//         $requestId,
//         $ins
//     ) {
//         $this->db
//             ->where('timeoff_request_sid', $requestId)
//             ->update('timeoff_attachments', $ins);
//     }

//     //
//     function getFMLADetailsBySid($sid)
//     {
//         $a = $this->db
//             ->select('
//             timeoff_attachments.*,
//             DATE_FORMAT(timeoff_requests.created_at, "%m-%d-%Y") as created_at,
//             DATE_FORMAT(timeoff_requests.request_from_date, "%m-%d-%Y") as request_from_date,
//             CONCAT(users.first_name," ",users.last_name) as full_name
//         ')
//             ->join('timeoff_requests', 'timeoff_requests.sid = timeoff_attachments.timeoff_request_sid', 'inner')
//             ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
//             ->where('timeoff_attachments.sid', $sid)
//             ->get('timeoff_attachments');
//         $b = $a->row_array();
//         $a->free_result();
//         return $b;
//     }


//     function getEmployerData($employerId)
//     {
//         $a = $this->db
//             ->where('sid', $employerId)
//             ->get('users');
//         //

//         $b = $a->row_array();
//         $a = $a->free_result();
//         //
//         return $b;
//     }

//     function getCompanyData($companySid)
//     {
//         $a = $this->db
//             ->where('sid', $companySid)
//             ->get('users');
//         //

//         $b = $a->row_array();
//         $a = $a->free_result();
//         //
//         return $b;
//     }

    


    

//     //
    




//     /**
//      * Check company category
//      * @param Array $post
//      */
    

//     //
   

//     //
    


    


//     private function getConsumedTimeOff(
//         $policyId,
//         $employeeId,
//         $years,
//         $joinedAt,
//         $accrual
//     ) {
//         $dateFormat = 'Y';
//         $dateFormatDB = '%Y';
//         // Fetch current used timeoff
//         if ($accrual['accrual_method'] == 'hours_per_month') {
//             $dateFormat = 'Y-m';
//             $dateFormatDB = '%Y-%m';
//         }
//         $date = date($dateFormat, strtotime('now'));
//         //
//         $result = $this->db
//             ->select('
//             SUM(requested_time) as requested_time
//         ')
//             ->from('timeoff_requests')
//             ->where('timeoff_policy_sid', $policyId)
//             ->where('employee_sid', $employeeId)
//             ->where('status', 'approved')
//             ->where('is_draft', 0)
//             ->where('archive', 0)
//             ->where("date_format(request_from_date, \"$dateFormatDB\") = ", "$date")
//             ->get();
//         //
//         $record = $result->row_array();
//         $result->free_result();

//         // _e( $this->db->last_query(), true );
//         //
//         return $record['requested_time'] == null ? 0 : $record['requested_time'];
//     }


//     private function getPendingTimeOff(
//         $policyId,
//         $employeeId,
//         $years,
//         $joinedAt,
//         $accrual
//     ) {
//         $dateFormat = 'Y';
//         $dateFormatDB = '%Y';
//         // Fetch current used timeoff
//         if ($accrual['accrual_method'] == 'hours_per_month') {
//             $dateFormat = 'Y-m';
//             $dateFormatDB = '%Y-%m';
//         }
//         $date = date($dateFormat, strtotime('now'));
//         //
//         $result = $this->db
//             ->select('
//             SUM(requested_time) as consumed,
//             COUNT(DISTINCT(DATE_FORMAT(request_from_date, "' . ($dateFormatDB) . '"))) as occurrence
//         ')
//             ->from('timeoff_requests')
//             ->where('timeoff_policy_sid', $policyId)
//             ->where('employee_sid', $employeeId)
//             ->where('status', 'approved')
//             ->where('is_draft', 0)
//             ->where("date_format(request_from_date, \"$dateFormatDB\") < ", "$date")
//             ->get();
//         //
//         $record = $result->row_array();
//         $result->free_result();
//         //
//         if (!sizeof($record) || $record['consumed'] == '') return 0;
//         //
//         $pendingTimeOff = ($accrual['accrual_rate'] * $record['occurrence']) - $record['consumed'];
//         //
//         if ($pendingTimeOff > $accrual['carryover_cap']) {
//             $pendingTimeOff = $accrual['carryover_cap'];
//         }
//         //
//         if ($accrual['carryover_cap_check'] == 0) {
//             return 0;
//         }
//         return $pendingTimeOff < 0 ? 0 : $pendingTimeOff;
//     }


//     //
//     private function setTimeOffDays(
//         &$b,
//         $defaultTimeFrame,
//         $slug
//     ) {
//         $b['timeoff_days'] = @json_decode($b['timeoff_days'], TRUE);

//         if (!sizeof($b['timeoff_days'])) {

//             // Get dates
//             $s = isset($b['request_from_date']) ? $b['request_from_date'] : $b['requested_date'];
//             $e = $b['request_to_date'];
//             //
//             $ss = new DateTime($s);
//             $ee = new DateTime($e);
//             //
//             $d = $ss->diff($ee)->d;
//             // Split time
//             $t = $b['requested_time']; // 720
//             $dt = $defaultTimeFrame * 60; // 720
//             $dt = $dt == 0 ? 540 : $dt;
//             $tt = $t;
//             //
//             for ($i = 0; $i <= $d; $i++) {
//                 $sss = new DateTime($s);
//                 //
//                 $tt = $t - $dt > 0 ? $dt : ($t <= $dt ? $t : 0);
//                 //
//                 $t = $t - $dt >= 0 ? $t - $dt : 0;
//                 //
//                 $nd = $sss->add(new DateInterval('P' . ($i) . 'D'))->format('Y-m-d');
//                 //
//                 $b['timeoff_days'][] = array(
//                     'date' => DateTime::createFromFormat('Y-m-d', $nd)->format('m-d-Y'),
//                     'time' => $tt,
//                     'partial' => $b['is_partial_leave'] == 1 ? 'partialday' : 'fullday'
//                 );
//             }
//         }

//         // Get breakdown for all
//         foreach ($b['timeoff_days'] as $k1 => $v1) {
//             $b['timeoff_days'][$k1]['breakdown'] = get_array_from_minutes(
//                 $v1['time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//         }
//     }

//     //
//     function getAllowedTimeOffByPolicyIdAndMonths(
//         $startMonth,
//         $endMonth,
//         $policyId,
//         $employeeId
//     ) {
//         // Get policy from request
//         $a =
//             $this->db
//             ->select('requested_time, request_from_date, request_to_date')
//             ->where('date_format(request_from_date, "%m") >= ', $startMonth)
//             ->where('date_format(request_to_date, "%m") <= ', $endMonth)
//             ->where('timeoff_policy_sid', $policyId)
//             ->where('employee_sid', $employeeId)
//             ->where('status', 'approved')
//             ->get('timeoff_requests');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         return $b;
//     }

//     //
//     function getDataForExport($post)
//     {
//         //
//         $this->db->distinct();
//         // Select

//         $this->db->select('timeoff_requests.sid');
//         $this->db->select('timeoff_requests.employee_sid');
//         $this->db->select('users.first_name');
//         $this->db->select('users.last_name');
//         // Where
//         $this->db->where('timeoff_requests.company_sid', $post['companySid']);
//         $this->db->where('timeoff_requests.is_draft', 0);
//         $this->db->where('timeoff_requests.archive', $post['archive']);

//         if (!in_array('all', $post['employees'])) $this->db->where_in('timeoff_requests.employee_sid', $post['employees']);
//         if (!in_array('all', $post['status'])) $this->db->where_in('timeoff_requests.status', $post['status']);

//         if ((!empty($post['fromDate']) || !is_null($post['fromDate'])) && (!empty($post['toDate']) || !is_null($post['toDate']))) {
//             $this->db->where('timeoff_requests.request_from_date BETWEEN \'' . $post['fromDate'] . '\' AND \'' . $post['toDate'] . '\'');
//         } else if ((!empty($post['fromDate']) || !is_null($post['fromDate'])) && (empty($post['toDate']) || is_null($post['toDate']))) {
//             $this->db->where('timeoff_requests.request_from_date >=', $post['fromDate']);
//         } else if ((empty($post['fromDate']) || is_null($post['fromDate'])) && (!empty($post['toDate']) || !is_null($post['toDate']))) {
//             $this->db->where('timeoff_requests.request_from_date <=', $post['toDate']);
//         }
//         //
//         $this->db->join("users", "users.sid = timeoff_requests.employee_sid", "inner");
//         //
//         $a = $this->db->get('timeoff_requests');
//         $b = $a->result_array();
//         $a->free_result();
//         //
//         return $b;
//     }


//     //
//     function getTimeOffByIds(
//         $companySid,
//         $requestIds
//     ) {
//         $r = array();
//         //
//         $this->db->select("
//             timeoff_requests.sid as requestId,
//             timeoff_requests.timeoff_policy_sid as policyId,
//             timeoff_requests.employee_sid,
//             timeoff_requests.requested_time,
//             timeoff_requests.allowed_timeoff,
//             timeoff_requests.request_from_date as requested_date,
//             timeoff_requests.request_to_date,
//             timeoff_requests.is_partial_leave,
//             timeoff_requests.status,
//             timeoff_requests.partial_leave_note,
//             timeoff_requests.reason,
//             timeoff_requests.level_at,
//             timeoff_requests.timeoff_days,
//             timeoff_policies.title as policy_title,
//         ")
//             ->from('timeoff_requests')
//             ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
//             ->order_by('requested_date', 'ASC')
//             ->order_by('status', 'DESC');
//         //
//         $this->db->where_in('timeoff_requests.sid', $requestIds);
//         //
//         $a = $this->db->get();
//         //
//         $b = $a->result_array();
//         $a->free_result();
//         //
//         if (!sizeof($b)) return $r;
//         // Get company default time
//         $a = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companySid)
//             ->limit(1)
//             ->get();
//         //
//         $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
//         $a->free_result();
//         //
//         foreach ($b as $k => $v) {
//             // Check if teamlead is  assigned
//             $a = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('role', 'teamlead')
//                 ->count_all_results('timeoff_request_assignment');
//             if (!$a) {
//                 $a = $this->db
//                     ->where('timeoff_request_sid', $v['requestId'])
//                     ->where('role', 'supervisor')
//                     ->count_all_results('timeoff_request_assignment');
//                 //
//                 $this->db
//                     ->where('sid', $v['requestId'])
//                     ->update(
//                         'timeoff_requests',
//                         array(
//                             'level_at' => !$a ? 3 : 2
//                         )
//                     );
//                 $v['level_at'] = $b[$k]['level_at'] = !$a ? 3 : 2;
//             }

//             // $a = $this->db
//             //     ->where('timeoff_request_sid', $v['requestId'])
//             //     ->count_all_results('timeoff_request_assignment');
//             // if (!$a) {
//             //     unset($b[$k]);
//             //     continue;
//             // }
//             // Fetch employee joining date
//             $a = $this->db
//                 ->select('
//                 joined_at,
//                 first_name,
//                 last_name,
//                 access_level_plus,
//                 access_level,
//                 pay_plan_flag,
//                 job_title,
//                 is_executive_admin,
//                 user_shift_hours, 
//                 user_shift_minutes,
//                 concat(first_name," ",last_name) as full_name,
//                 profile_picture as img,
//                 employee_number
//             ')
//                 ->from('users')
//                 ->where('sid', $v['employee_sid'])
//                 ->limit(1)
//                 ->get();
//             //
//             $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
//             $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
//             $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
//             //
//             $b[$k]['first_name'] = $a->row_array()['first_name'];
//             $b[$k]['last_name'] = $a->row_array()['last_name'];
//             $b[$k]['access_level'] = $a->row_array()['access_level'];
//             $b[$k]['access_level_plus'] = $a->row_array()['access_level_plus'];
//             $b[$k]['job_title'] = $a->row_array()['job_title'];
//             $b[$k]['is_executive_admin'] = $a->row_array()['is_executive_admin'];
//             $b[$k]['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
//             $b[$k]['full_name'] = $a->row_array()['full_name'];
//             $b[$k]['img'] = $a->row_array()['img'];
//             $b[$k]['employee_number'] = $a->row_array()['employee_number'];
//             //
//             $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//             $a->free_result();
//             // Fetch non responded employees
//             $b[$k]['Progress']['UnResponded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 0)
//                 ->count_all_results('timeoff_request_assignment');
//             // Fetch responded employees
//             $b[$k]['Progress']['Responded'] = $this->db
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->where('is_reassigned', 0)
//                 ->where('is_responded', 1)
//                 ->count_all_results('timeoff_request_assignment');
//             //
//             $b[$k]['Progress']['Total'] = $b[$k]['Progress']['UnResponded'] + $b[$k]['Progress']['Responded'];
//             $b[$k]['Progress']['CompletedPercentage'] = ceil(($b[$k]['Progress']['Responded'] / $b[$k]['Progress']['Total']) * 100);

//             if ($v['status'] != 'pending') {
//                 $b[$k]['Progress']['CompletedPercentage'] = 100;
//             }

//             $b[$k]['policy_title'] = ucwords($v['policy_title']);
//             //
//             $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
//                 $v['requested_time'],
//                 $defaultTimeFrame,
//                 $slug
//             );
//             $b[$k]['slug'] = $slug;
//             $b[$k]['defaultTimeFrame'] = $defaultTimeFrame;

//             // Time off Category
//             $a = $this->db
//                 ->select('
//                 category_name
//             ')
//                 ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                 ->where('timeoff_policy_categories.timeoff_policy_sid', $v['policyId'])
//                 ->order_by('timeoff_categories.sort_order', 'ASC')
//                 ->get('timeoff_policy_categories');
//             //
//             $b[$k]['Category'] = '';
//             $aa = $a->row_array();
//             $a->free_result();
//             if (sizeof($aa)) $b[$k]['Category'] = $aa['category_name'];

//             // Sort by policy
//             // Let's fetch the history
//             $b[$k]['History'] = $this->db
//                 ->select('
//                 users.first_name,
//                 users.last_name,
//                 users.is_executive_admin,
//                 users.job_title,
//                 users.access_level,
//                 users.access_level_plus,
//                 users.pay_plan_flag,
//                 timeoff_request_history.reason as comment,
//                 timeoff_request_history.status
//             ')
//                 ->join('users', 'users.sid = timeoff_request_assignment.employee_sid', 'inner')
//                 ->join('timeoff_request_history', 'timeoff_request_history.timeoff_request_assignment_sid = timeoff_request_assignment.sid', 'inner')
//                 ->where('timeoff_request_assignment.timeoff_request_sid', $v['requestId'])
//                 ->order_by('timeoff_request_assignment.sid', 'DESC')
//                 ->get('timeoff_request_assignment')
//                 ->result_array();
//             // Attachments
//             $b[$k]['Attachments'] = $this->db
//                 ->select('
//                 document_title,
//                 s3_filename,
//                 is_archived
//             ')
//                 ->where('timeoff_request_sid', $v['requestId'])
//                 ->order_by('sid', 'DESC')
//                 ->get('timeoff_attachments')
//                 ->result_array();

//             // Sort by policy
//             // if(!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
//         }
//         return array_values($b);
//     }

    

//     //
//     private function getEmployeeBalancesSum($employeeSid){
//         $a = $this->db
//             ->select('
//                 timeoff_balances.added_time, 
//                 timeoff_balances.is_added,
//                 timeoff_balances.policy_sid
//             ')
//             ->where('user_sid', $employeeSid)
//             ->where('effective_at <= ', date('Y-m-d', strtotime('now')))
//             ->get('timeoff_balances');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(!count($b)) return [];
//         //
//         $r = [];
//         //
//         foreach($b as $v){
//             //
//             if(!isset($r[$v['policy_sid']])) $r[$v['policy_sid']] = 0;
//             //
//             if($v['is_added'] == 1) $r[$v['policy_sid']] += $v['added_time'];
//             else $r[$v['policy_sid']] -= $v['added_time'];
//         }
//         //
//         return $r;
//     }

//     //
    


    


//     // 
//     function getRequestFieldById(
//         $requestId,
//         $columns = '*'
//     ){
//         $a = $this->db
//         ->select($columns)
//         ->from('timeoff_requests')
//         ->where('sid', $requestId)
//         ->get();
//         //
//         $b = $a->row_array();
//         $a = $a->free_result();
//         //
//         return $columns != '*' && array_key_exists($columns, $b) ? $b[$columns] : $b;
//     }

    
//     //
//     function removeAssignedEmployer($where){
//         $this->db
//         ->where($where)
//         ->delete('timeoff_request_assignment');
//     }
    
//     //
//     function addAssignedEmployer($ins){
//         $this->db
//         ->insert(
//             'timeoff_request_assignment',
//             $ins
//         );
//     }
    
    
//     //
//     private function cleanAssignedArray(
//         $requestId,
//         $employeeSid,
//         $companySid,
//         $array
//     ){
//         // Fetch latest tls
//         $newTLS = $this->timeoff_model->fetchTimeOffEmployers(
//             $employeeSid,
//             $companySid
//         );
//         //
//         if(!count($newTLS)){
//             $this->db
//             ->where('timeoff_request_sid', $requestId)
//             ->where('employee_sid != ', $this->session->userdata('logged_in')['employer_detail']['sid'])
//             ->delete('timeoff_request_assignment');
//             return [];
//         }
//         //
//         if(count($array)){
//             // Check and delete
//             foreach($array as $k0 => $tl){
//                 //
//                 $has = false;
//                 //
//                 foreach($newTLS as $k => $v) {
//                     if( $tl['role'] == $v['type'] && $tl['email'] == $v['email'] ) $has = true;
//                     if($tl['employee_sid'] == $this->session->userdata('logged_in')['employer_detail']['sid']) $has = true;
//                 }
//                 //
//                 if(!$has){
//                     //
//                     unset($array[$k0]);
//                     // Lets delete the employer
//                     $this->timeoff_model->removeAssignedEmployer([ 'sid' => $tl['sid'] ]);
//                 }
//             }
//         }
//         // Check and add
//         foreach($newTLS as $k0 => $tl){
//             //
//             $has = false;
//             //
//             foreach($array as $k => $v) if( $tl['type'] == $v['role'] && $tl['email'] == $v['email'] ) $has = true;
//             //
//             if(!$has){
//                 $this->timeoff_model->addAssignedEmployer([
//                     'timeoff_request_sid' => $requestId,
//                     'employee_sid' => $tl['id'],
//                     'role' => $tl['type']
//                 ]);
//             }
//         }
//         //
//         return array_values($array);

//     }



//     /**
//      * Get employee all policies
//      * @param Array $post
//      */
//     function getEmployeeAllPoliciesWR($post)
//     {
//         // error_reporting(E_ALL);
//         // ini_set('display_errors', 1);
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         //
//         $result = $this->db
//             ->select('sid, title, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         // Check for empty data
//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);
//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             $policies[$k0]['PolicyStatus'] = [
//                 'Implements' => true,
//                 'Reason' => ''
//             ];
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result();

//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;
//             $policies[$k0]['title'] = ucwords($v0['title']);
//             $policies[$k0]['user_shift_hours'] = $employeeShiftHours;
//             $policies[$k0]['user_shift_minutes'] = $employeeShiftMinutes;
//             $policies[$k0]['employee_timeslot'] = $defaultTimeFrame;
//             $policies[$k0]['format'] = $timeoffFormat;
//             // Time off Category
//             $a = $this->db
//                 ->select('
//                 category_name
//             ')
//                 ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
//                 ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
//                 ->where('timeoff_policy_categories.timeoff_policy_sid', $v0['sid'])
//                 ->order_by('timeoff_categories.sort_order', 'ASC')
//                 ->get('timeoff_policy_categories');
//             //
//             $policies[$k0]['Category'] = '';
//             $b = $a->row_array();
//             $a->free_result();
//             //
//             if (sizeof($b)) $policies[$k0]['Category'] = $b['category_name'];

//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();

//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();

//                 $policies[$k0]['company_sid'] = $companyId;
//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0] ) ) {
//                     // unset($policies[$k0]);
//                     // continue;
//                 }
//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     true,
//                     $balances,
//                     true,
//                     $employmentStatus
//                 );
//             } else {
//                 //
//                 if( !policyAccrualCheck($aa, $joinedAt, $employmentStatus, $policies[$k0]) ){
//                     // unset($policies[$k0]);
//                     // continue;
//                 }
//                 // Check if policy is umlimited or not
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = '1';
//                     continue;
//                 }

//                 //
//                 $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     false,
//                     $balances,
//                     true,
//                     $employmentStatus
//                 );
//             }
//             // Manipulate policy
//             if ($policies[$k0]['allowed_timeoff'] != -1)
//                 $policies[$k0]['timeoff_breakdown'] = get_array_from_minutes(
//                     $policies[$k0]['allowed_timeoff'],
//                     $defaultTimeFrame,
//                     $slug,
//                     $policies[$k0]['accrual']
//                 );
//             // Check if policy is umlimited or not
//             // if($v0['is_unlimited'] == 1){
//             //     $policies[$k0]['allowed_timeoff'] = -1;
//             //     continue;
//             // }
//             // // Fetch plans
//             // $details = $this->getAllowedTimeOffWithPlan(
//             //     $v0['sid'],
//             //     $years,
//             //     $employeeId,
//             //     $joinedAt
//             // );
//             // if(!sizeof($details)) {
//             //     $policies[$k0]['is_unlimited'] = 1;
//             //     continue;
//             // }

//             // $policies[$k0]['allowed_timeoff'] = ($details['allowed_timeoff'] + $details['pendingTimeOffOld']) - $details['consumed'];

//             // }          // Manipulate policy
//             // if($policies[$k0]['allowed_timeoff'] != -1)
//             // $policies[$k0]['timeoff_breakdown'] = get_array_from_minutes($policies[$k0]['allowed_timeoff'], $defaultTimeFrame, $slug);
//         }
//         return array_values($policies);
//     }

//     //
//     /**
//      * Get employee policies status
//      * @param Array $post
//      */
//     function getEmployeePoliciesStatusWR($post)
//     {
//         $companyId = $post['companySid'];
//         $employeeId = $post['employeeSid'];
//         //
//         $result = $this->db
//             ->select('sid, is_unlimited')
//             ->from('timeoff_policies')
//             ->where_in('company_sid', array($companyId, 0))
//             ->group_start()
//             ->where("FIND_IN_SET('$employeeId', assigned_employees) !=", 0)
//             ->or_where('assigned_employees', 'all')
//             ->group_end()
//             ->where('is_included', 1)
//             ->where('status', 1)
//             ->where('is_archived', 0)
//             ->order_by('sort_order', 'ASC')
//             ->get();
//         //
//         $policies = $result->result_array();
//         $result   = $result->free_result();
//         //
//         if (!sizeof($policies)) return $policies;
//         //
//         // Fetch employee shift and joinging date
//         extract($this->getEmployeeTimeOffShifts($employeeId));
//         // Get company default time
//         $result = $this->db
//             ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
//             ->from('timeoff_settings')
//             ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
//             ->where('company_sid', $companyId)
//             ->limit(1)
//             ->get();
//         //
//         $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
//         // $defaultCompanyTimeFrame = isset($result->row_array()['default_timeslot']) ? $result->row_array()['default_timeslot'] : 9;
//         $timeoffFormat = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H';
//         $slug = isset($result->row_array()['slug']) ? $result->row_array()['slug'] : 'H:M';
//         $result   = $result->free_result();
//         //
//         $r = array(
//             'Total' => 0,
//             'Consumed' => 0,
//             'Pending' => 0
//         );

//         //
//         $balances = $this->getEmployeeBalancesSum($employeeId);
//         // Loop through policies
//         foreach ($policies as $k0 => $v0) {
//             // Get accrue
//             $a = $this->db
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->get('timeoff_policy_accural');
//             //
//             $aa = $a->row_array();
//             $a = $a->free_result();


//             $policies[$k0]['company_sid'] = $companyId;
//             $policies[$k0]['accrual'] = $aa;
//             // Check for overwrite policy
//             $result =
//                 $this->db
//                 ->select('sid, is_unlimited')
//                 ->from('timeoff_policy_overwrite')
//                 ->where('timeoff_policy_sid', $v0['sid'])
//                 ->where('is_archived', 0)
//                 ->where('employee_sid', $employeeId)
//                 ->limit(1)
//                 ->get();

//             //
//             $policyOverwrite = $result->row_array();
//             $result          = $result->free_result();
//             //
//             if (sizeof($policyOverwrite)) {
//                 // Get accrue
//                 $a = $this->db
//                     ->where('timeoff_policy_overwrite_sid', $policyOverwrite['sid'])
//                     ->get('timeoff_policy_overwrite_accural');
//                 //
//                 $aa = $a->row_array();
//                 $a = $a->free_result();


//                 $policies[$k0]['accrual'] = $aa;
//                 $policies[$k0]['overwrite_sid'] = $policyOverwrite['sid'];
//                 //

//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }
//                 //
//                 $det = $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     true,
//                     $balances,
//                     true,
//                     $employmentStatus
//                 );
//                 //
//                 $r['Total'] += $det['allowed'];
//                 $r['Consumed'] += $det['consumed'];
//                 $r['Pending'] += ($det['allowed'] + $det['pending']) - $det['consumed'];
//             } else {
//                 //
//                 // Check if policy is umlimited or note
//                 if ($aa['accrual_method'] == 'unlimited' || $aa['accrual_rate'] == 0) {
//                     $policies[$k0]['allowed_timeoff'] = -1;
//                     $policies[$k0]['is_unlimited'] = 1;
//                     continue;
//                 }
//                 //
//                 $det = $this->getTimeByAccrualRules(
//                     $policies,
//                     $k0,
//                     $years,
//                     $employeeId,
//                     $joinedAt,
//                     $defaultTimeFrame,
//                     $slug,
//                     false,
//                     $balances,
//                     true,
//                     $employmentStatus
//                 );
//                 //
//                 $r['Total'] += $det['allowed'];
//                 $r['Consumed'] += $det['consumed'];
//                 $r['Pending'] += ($det['allowed'] + $det['pending']) - $det['consumed'];
//             }
//         }

//         $r['Total'] = get_array_from_minutes($r['Total'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Consumed'] = get_array_from_minutes($r['Consumed'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         $r['Pending'] = get_array_from_minutes($r['Pending'], $defaultTimeFrame, isset($post['slug']) ? $slug : 'H');
//         return $r;
//     }

//     //
//     function handleResetPolicy(
//         $companyId
//     ){
//         //
//         $a = $this->db
//         ->where('timeoff_policies.default_policy', 0)
//         ->where('timeoff_policies.is_archived', 0)
//         ->where('timeoff_policies.company_sid', $companyId)
//         ->order_by('timeoff_policies.sid', 'DESC')
//         ->get('timeoff_policies');
//         //
//         $b = $a->result_array();
//         $a = $a->free_result();
//         //
//         if(empty($b)) return;
//         //
//         foreach($b as $policy){
//             //
//             $yes = false;
//             //
//             $cp = json_decode($policy['accruals'], true);
//             $rp = json_decode($policy['reset_policy'], true);
//             //
//             if( $rp['resetDate'] != 0 ){
//                 if(strtotime('now') >= strtotime(DateTime::createfromformat('m-d-Y', $rp['resetDate'])->format('Y-m-d').' 00:00:00')){
//                     $this->db
//                     ->where('sid', $policy['sid'])
//                     ->update('timeoff_policies', [
//                         'accruals' => $policy['reset_policy']
//                     ]);
//                 }
//             }
//         }
//     }


    // -------------------------------------------------------- 2020 ---------------------------------------------------------------------

    /**
     * Types
     * 
     * @employee Mubashir Ahmed
     * @date     12/14/2020
     * 
     * @param Array $post
     * 
     * @return Array
     */
    function getCompanyTypesList($post){
        $a = $this->db
            ->select('
            timeoff_categories.sid as type_id,
            timeoff_category_list.category_name as type_name
        ')
            ->from('timeoff_categories')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_categories.company_sid', $post['companyId'])
            ->order_by('timeoff_categories.sort_order', 'ASC')
            ->get();
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    /**
     * Fetch policy list by company sid
     * 
     * @employee Mubashir Ahmed
     * @date     12/14/2020
     * 
     * @param  Integer $companySid
     * @param  String  $type Optional
     * 
     * @return Array
     */
    function getPoliciesListByCompany($companySid, $type = 'specific'){
        //
        $this->db
            ->select('
                timeoff_policies.sid as policy_id, 
                timeoff_policies.title as policy_title,
                timeoff_category_list.category_name as category
            ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->from('timeoff_policies')
            ->where('timeoff_policies.company_sid', $companySid)
            ->where('timeoff_policies.is_archived', 0)
            ->where('timeoff_policies.status', 1)
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        if ($type == 'specific') $this->db->where('timeoff_policies.is_admin', 0);
        //
        $result = $this->db->get();
        //
        $policies = $result->result_array();
        $result = $result->free_result();
        //
        //
        return $policies;
    }

    /**
     * Get company employees
     * 
     * @employee Mubashir Ahmed
     * @date     12/14/2020
     *
     * @param Integer $companySid
     * @param Integer $employerId
     * 
     * @return Array
     */
    function getCompanyEmployees($companySid, $employerId){
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        $ids = [];
        //
        //if($ses['access_level_plus'] == 0 && $ses['pay_plan_flag'] == 0){
          //  $ids = $this->getEmployeeTeamMemberIds($employerId);
            //if(empty($ids)) return [];
        //}
        //
        $this->db
            ->select('sid as user_id, 
            employee_number, 
            email, 
            first_name, 
            last_name, 
          job_title, 
          is_executive_admin, 
          concat(first_name," ",last_name) as full_name, 
          profile_picture as image, 
          access_level,
          access_level_plus,
          pay_plan_flag
        ')
            ->from('users')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->order_by('first_name', 'ASC');
        if(!empty($ids)) $this->db->where_in('sid', $ids);
            
        $result = $this->db->get();
        //
        $employees = $result->result_array();
        $result = $result->free_result();
        return $employees;
    }
    
    /**
     * Check if policy exists
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  String   $policyTitle
     * @param  Integer  $companyId
     * @param  Integer  $policyId    (Optional)
     * 
     * @return Integer
     */
    function policyExists(
        $policyTitle,
        $companyId,
        $policyId = 0
    ){
        $this->db
        ->from('timeoff_policies')
        ->where('title', $policyTitle)
        ->where('company_sid', $companyId);
        //
        if($policyId !=  0) $this->db->where("sid <> $policyId", null);
        //
        return $this->db->count_all_results();
    }
    
    
    /**
     * Insert policy
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  Array    $insertArray
     * 
     * @return Integer
     */
    function insertPolicy(
        $insertArray
    ){
        $this->db
        ->insert(
            'timeoff_policies',
            $insertArray
        );
        //
        return $this->db->insert_id();
    }
    
    /**
     * Insert policy history
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  Array    $insertArray
     * 
     * @return Integer
     */
    function insertPolicyHistory(
        $insertArray
    ){
        $this->db
        ->insert(
            'timeoff_policy_timeline',
            $insertArray
        );
        //
        return $this->db->insert_id();
    }

    /**
     * Update policy
     * 
     * @employee Mubashir Ahmed
     * @date     12/17/2020
     * 
     * @param Array      $updateArray
     * @param Integer    $policyId
     * 
     * @return VOID
     */
    function updatePolicy(
        $updateArray,
        $policyId
    ){
        $this->db
        ->where('sid', $policyId)
        ->update(
            'timeoff_policies',
            $updateArray
        );
    }

    /**
     * Get policies created by a company
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     *
     * @param   Array    $formpost
     * @param   Integer  $formpost
     * @param   Integer  $limit
     * 
     * @return  Array
     */
    function getPoliciesByCompany(
        $formpost,
        $page,
        $limit
    ) {
        // Set start
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $this->db
            ->select('
            timeoff_policies.sid as policy_id,
            timeoff_policies.title as policy_title,
            timeoff_policies.default_policy,
            timeoff_policies.accruals,
            timeoff_policies.type_sid,
            timeoff_policies.created_at
        ')
            ->from('timeoff_policies')
            ->where('timeoff_policies.is_archived', !empty($formpost['filter']) ? $formpost['filter']['archived'] : 0)
            ->where('timeoff_policies.company_sid', $formpost['companyId'])
            ->order_by('timeoff_policies.sort_order', 'ASC')
            ->limit($limit, $start);
        //
        if(!empty($formpost['filter'])){
            // Search Filter
            if ($formpost['filter']['status'] != '' && $formpost['filter']['status'] != -1) $this->db->where('timeoff_policies.status', $formpost['filter']['status']);
            if ($formpost['filter']['policy'] != '' && $formpost['filter']['policy'] != 0) $this->db->where('timeoff_policies.sid', $formpost['filter']['policy']);
            if ($formpost['filter']['startDate'] != '' && $formpost['filter']['endDate'] != '') $this->db->where('timeoff_policies.created_at BETWEEN "' . (formatDate($formpost['filter']['startDate'], 'm-d-Y', 'Y-m-d')) . '" AND "' . (formatDate($formpost['filter']['endDate'], 'm-d-Y', 'Y-m-d')) . '"', null, false);
        }

        //
        $result = $this->db->get();
        $policies = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($policies)) return array();
        //
        $returnArray = array('Policies' => $policies, 'Count' => 0);
        //
        if ($page != 1) return $returnArray;
        //
        $this->db
            ->from('timeoff_policies')
            ->where('timeoff_policies.is_archived', !empty($formpost['filter']) ? $formpost['filter']['archived'] : 0)
            ->where('timeoff_policies.company_sid', $formpost['companyId']);
        //
        if(!empty($formpost['filter'])){
            // Search Filter
            if ($formpost['filter']['status'] != '' && $formpost['filter']['status'] != -1) $this->db->where('timeoff_policies.status', $formpost['filter']['status']);
            if ($formpost['filter']['policy'] != '' && $formpost['filter']['policy'] != 0) $this->db->where('timeoff_policies.sid', $formpost['filter']['policy']);
            if ($formpost['filter']['startDate'] != '' && $formpost['filter']['endDate'] != '') $this->db->where('timeoff_policies.created_at BETWEEN "' . (formatDate($formpost['filter']['startDate'], 'm-d-Y', 'Y-m-d')) . '" AND "' . (formatDate($formpost['filter']['endDate'], 'm-d-Y', 'Y-m-d')) . '"', null, false);
        }
        //
        $returnArray['Count'] = $this->db->count_all_results();
        //
        return $returnArray;
    }


    /**
     * Get single policy by company id
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param Integer $policyId
     * 
     * @return Array
     */
    function getSinglePolicyById($policyId){
        return $this->db
        ->where('sid', $policyId)
        ->get('timeoff_policies')
        ->row_array();
    }

    /**
     * Get policy history by id
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Integer $policyId
     * 
     * @return Array
     */
    function getPolicyHistory($policyId){
        return $this->db
        ->select('
            timeoff_policy_timeline.action, 
            timeoff_policy_timeline.action_type,
            timeoff_policy_timeline.created_at,
            '.( getUserFields() ).'
        ')
        ->join('users', 'users.sid = timeoff_policy_timeline.employee_sid', 'inner')
        ->where('timeoff_policy_timeline.policy_sid', $policyId)
        ->order_by('timeoff_policy_timeline.sid', 'DESC')
        ->get('timeoff_policy_timeline')
        ->result_array();
    }

    /**
     * Get company types
     * 
     * @employee Mubashir Ahmed
     * @date     12/17/2020
     * 
     * @param Array   $post
     * @param Integer $page
     * @param Integer $limit
     * 
     * @return Array
     */
    function getTypesListByCompany($post, $page, $limit){
        // Fetch all polcies
        $policies = $this->db
        ->select('type_sid, title')
        ->from('timeoff_policies')
        ->where('company_sid', $post['companyId'])
        ->order_by('sort_order', 'ASC')
        ->get()
        ->result_array();
        //
        if(!empty($policies)){
            //
            $tmp = [];
            //
            foreach($policies as $policy) {
                //
                if(!isset($tmp[$policy['type_sid']])) $tmp[$policy['type_sid']] = [];
                //
                $tmp[$policy['type_sid']][] = $policy['title'];
            }
            //
            $policies = $tmp;
            //
            unset($tmp);
        }
        //
        // Set start
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $this->db
        ->select('
            timeoff_category_list.category_name as type_title,
            timeoff_categories.sid as type_sid,
            timeoff_categories.default_type,
            timeoff_categories.created_at,
            '.( getUserFields() ).'
        ')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->join('users', 'users.sid = timeoff_categories.creator_sid', 'inner')
        ->where('timeoff_categories.is_archived', !empty($post['filter']) ? $post['filter']['archived'] : 0)
        ->where('timeoff_categories.company_sid', $post['companyId'])
        ->limit($limit, $start)
        ->order_by('timeoff_categories.sort_order', 'ASC');
        //
        if(!empty($post['filter'])){
            // Search Filter
            if ($post['filter']['type'] != '' && $post['filter']['type'] != -1) $this->db->where('timeoff_categories.sid', $post['filter']['type']);
            if ($post['filter']['status'] != '' && $post['filter']['status'] != -1) $this->db->where('timeoff_categories.status', $post['filter']['status']);
            if ($post['filter']['startDate'] != '' && $post['filter']['endDate'] != '') $this->db->where('timeoff_categories.created_at BETWEEN "' . (formatDate($post['filter']['startDate'], 'm-d-Y', 'Y-m-d')) . '" AND "' . (formatDate($post['filter']['endDate'], 'm-d-Y', 'Y-m-d')) . '"', null, false);
        }
        //
        $result = $this->db->get('timeoff_categories');
        $types = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($types)) return array();
        //
        foreach($types as $k => $v) $types[$k]['policies'] = isset($policies[$v['type_sid']]) ? $policies[$v['type_sid']] : null;
        //
        $returnArray = array('Types' => $types, 'Count' => 0);
        //
        if ($page != 1) return $returnArray;
        //
        $this->db
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->join('users', 'users.sid = timeoff_categories.creator_sid', 'inner')
        ->where('timeoff_categories.is_archived', !empty($post['filter']) ? $post['filter']['archived'] : 0)
        ->where('timeoff_categories.company_sid', $post['companyId']);
        //
        if(!empty($post['filter'])){
            // Search Filter
            if ($post['filter']['type'] != '' && $post['filter']['type'] != -1) $this->db->where('timeoff_categories.sid', $post['filter']['type']);
            if ($post['filter']['status'] != '' && $post['filter']['status'] != -1) $this->db->where('timeoff_categories.status', $post['filter']['status']);
            if ($post['filter']['startDate'] != '' && $post['filter']['endDate'] != '') $this->db->where('timeoff_categories.created_at BETWEEN "' . (formatDate($post['filter']['startDate'], 'm-d-Y', 'Y-m-d')) . '" AND "' . (formatDate($post['filter']['endDate'], 'm-d-Y', 'Y-m-d')) . '"', null, false);
        }
        //
        $returnArray['Count'] = $this->db->count_all_results('timeoff_categories');
        //
        return $returnArray;
    }

    /**
     * Check if type exists
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  String   $typeTitle
     * @param  Integer  $companyId
     * @param  Integer  $typeId    (Optional)
     * 
     * @return Array
     */
    function typeExists(
        $typeTitle,
        $companyId,
        $typeId = 0 
    ){
        //
        $categoryArray = 
        $this->db
        ->select('sid')
        ->from('timeoff_category_list')
        ->where('category_name', $typeTitle)
        ->get()
        ->row_array();
        //
        if(empty($categoryArray)) return [0, 0];
        //
        $this->db
        ->from('timeoff_categories')
        ->where('timeoff_category_list_sid', $categoryArray['sid'])
        ->where('company_sid', $companyId);
        //
        if($typeId !=  0) $this->db->where("sid <> $typeId", null);
        //
        return [$categoryArray['sid'], $this->db->count_all_results()];
    }

    /**
     * Insert type into main table
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  String   $typeTitle
     * 
     * @return Integer
     */
    function insertMainCategory(
        $typeTitle
    ){
       //
       $this->db
       ->insert(
           'timeoff_category_list', [
               'category_name' => $typeTitle,
               'status' => 1
           ]
       );
       //
       return $this->db->insert_id();
    }
    
    /**
     * Insert type
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  Array   $insertArray
     * 
     * @return Integer
     */
    function insertCategory(
        $insertArray
    ){
       //
       $this->db
       ->insert(
           'timeoff_categories', 
           $insertArray
       );
       //
       return $this->db->insert_id();
    }
    
    /**
     * Insert type history
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  Array   $insertArray
     * 
     * @return Integer
     */
    function insertTypeHistory(
        $insertArray
    ){
       //
       $this->db
       ->insert(
           'timeoff_type_timeline', 
           $insertArray
       );
       //
       return $this->db->insert_id();
    }
    
    /**
     * Insert type history
     * 
     * @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param  Integer $typeId
     * @param  Array   $policyIds
     * @param  Array   $companyId
     * 
     * @return VOID
     */
    function updatePolicyTypes(
        $typeId,
        $policyIds,
        $companyId
    ){
       //
       $this->db
       ->where_in('company_sid', $companyId);
       //
       if($policyIds != 0) $this->db->where_in('sid', $policyIds);
       //
       $this->db
       ->update(
           'timeoff_policies', [
               'type_sid' => $typeId
           ]
       );
    }

    /**
     * Update Policy
     *
     *  @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param Integer $policySid
     * @param Array   $updateArray
     *
     * @return VOID
     */
    function updateCompanyPolicy($policySid, $updateArray){
        // Update PTO Group
        $this->db
            ->where('sid', $policySid)
            ->update('timeoff_policies', $updateArray);
    }
    
    /**
     * Update type
     *
     *  @employee Mubashir Ahmed
     * @date     12/16/2020
     * 
     * @param Integer $typeId
     * @param Array   $updateArray
     *
     * @return VOID
     */
    function updateCompanyType($typeId, $updateArray){
        // Update PTO Group
        $this->db
            ->where('sid', $typeId)
            ->update('timeoff_categories', $updateArray);
    }

    /**
     * Get type history by id
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Integer $typeId
     * 
     * @return Array
     */
    function getTypeHistory($typeId){
        return $this->db
        ->select('
            timeoff_type_timeline.action, 
            timeoff_type_timeline.created_at,
            '.( getUserFields() ).'
        ')
        ->join('users', 'users.sid = timeoff_type_timeline.employee_sid', 'inner')
        ->where('timeoff_type_timeline.type_sid', $typeId)
        ->order_by('timeoff_type_timeline.sid', 'DESC')
        ->get('timeoff_type_timeline')
        ->result_array();
    }
    
    /**
     * Get type history by id
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Integer $typeId
     * @param Integer $companyId
     * 
     * @return Array
     */
    function getSingleType($typeId, $companyId){
        $types =
        $this->db
        ->select('
            timeoff_categories.sid as type_sid, 
            timeoff_categories.is_archived,
            timeoff_categories.default_type,
            timeoff_category_list.category_name as type
        ')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->where('timeoff_categories.sid', $typeId)
        ->where('timeoff_categories.company_sid', $companyId)
        ->get('timeoff_categories')
        ->row_array();
        //
        if(!empty($types)){
            $types['policies'] = $this->db
            ->select('sid')
            ->where('type_sid', $typeId)
            ->get('timeoff_policies')
            ->result_array();
            //
            if(!empty($types['policies'])){
                $types['policies'] = array_column(
                    $types['policies'],
                    'sid'
                );
            }
        }
        //
        return $types;
    }

    /**
     * Get approvers by a company
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     *
     * @param  Array    $post
     * @param  Integer  $page
     * @param  Integer  $limit
     * 
     * @return Array
     */
    function getCompanyHolidays(
        $post,
        $page,
        $limit
    ) {
        // Set start
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $this->db
        ->select('
            timeoff_holidays.sid,
            timeoff_holidays.holiday_year,
            timeoff_holidays.holiday_title,
            timeoff_holidays.created_at,
            timeoff_holidays.work_on_holiday,
            timeoff_holidays.from_date,
            timeoff_holidays.icon,
            timeoff_holidays.to_date
       ')
        ->from('timeoff_holidays')
        ->where('timeoff_holidays.is_archived', $post['filter']['archived'] == 0 ? 0 : 1)
        ->where('timeoff_holidays.company_sid', $post['companyId'])
        ->order_by('timeoff_holidays.from_date', 'ASC')
        ->limit($limit, $start);
        // Search Filter
        if ($post['filter']['years'] != '' && $post['filter']['years'] != -1) $this->db->where('timeoff_holidays.holiday_year', $post['filter']['years']);
        else $this->db->where('timeoff_holidays.holiday_year', date('Y'));
        //
        $result = $this->db->get();
        $holidays = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($holidays)) return array();
        //
        $returnArray = array('Holidays' => $holidays, 'Count' => 0);
        //
        if ($page != 1) return $returnArray;
        //
        $this->db
            ->from('timeoff_holidays')
            ->where('timeoff_holidays.is_archived', $post['filter']['archived'] == 0 ? 0 : 1)
            ->where('timeoff_holidays.company_sid', $post['companyId']);
        // Search Filter
        if ($post['filter']['years'] != '' && $post['filter']['years'] != -1) $this->db->where('timeoff_holidays.holiday_year', $post['filter']['years']); //
        else $this->db->where('timeoff_holidays.holiday_year', date('Y'));
        //
        $returnArray['Count'] = $this->db->count_all_results();

        return $returnArray;
    }

    /**
     * Update company holidays
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * 
     * @param  Array    $upd
     * @param  Integer  $holidayId
     * 
     * @return VOID
     */
    function updateCompanyHoliday($upd, $holidayId){
        $this->db
        ->where('sid', $holidayId)
        ->update('timeoff_holidays', $upd);
    }

    /**
     * Get holiday history by id
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Integer $holidayId
     * 
     * @return Array
     */
    function getHolidayHistory($holidayId){
        return $this->db
        ->select('
            timeoff_holiday_timeline.action, 
            timeoff_holiday_timeline.created_at,
            '.( getUserFields() ).'
        ')
        ->join('users', 'users.sid = timeoff_holiday_timeline.employee_sid', 'inner')
        ->where('timeoff_holiday_timeline.holiday_sid', $holidayId)
        ->order_by('timeoff_holiday_timeline.sid', 'DESC')
        ->get('timeoff_holiday_timeline')
        ->result_array();
    }
    
    
    /**
     * Insert holiday history
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Array $insertArray
     * 
     * @return Integer
     */
    function inserttHolidayHistory($insertArray){
        //
        $this->db->insert('timeoff_holiday_timeline', $insertArray);
        //
        return $this->db->insert_id();
    }
    
    
    /**
     * Insert holiday history
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Array $insertArray
     * 
     * @return Integer
     */
    function getCompanyDistinctHolidays($post){
        // 
        $a = $this->db
            ->select('DISTINCT(holiday_year)')
            ->where('company_sid', $post['companyId'])
            ->get('timeoff_holidays');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!sizeof($b)) return array();
        //
        $c = array();
        //
        foreach ($b as $v) $c[] = $v['holiday_year'];
        //
        return $c;
    }


    /**
     * Check if company holiday already exists
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Array $post
     * 
     * @return Integer
     */
    function companyHolidayExists($post){
        //
        $this->db
            ->from('timeoff_holidays')
            ->where('timeoff_holidays.company_sid', $post['companyId'])
            ->where('timeoff_holidays.holiday_title', $post['holiday'])
            ->where('timeoff_holidays.holiday_year', $post['year']);
        //
        if (isset($post['holidayId'])) $this->db->where('timeoff_holidays.sid <>', $post['holidayId']);
        //
        return $this->db->count_all_results();
    }

    /**
     * Insert company holiday
     * 
     * @employee  Mubashir Ahmed
     * @date      12/17/2020
     * 
     * @param Array $post
     * 
     * @return Integer
     */
    function insertCompanyHoliday($insertArray){
        //
        $this->db->insert('timeoff_holidays', $insertArray);
        //
        return $this->db->insert_id();
    }

    /**
     * Get single holiday
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param Integer  $companyId
     * @param Integer  $holidayId
     * 
     * @return Integer
     */
    function getHolidayById(
        $companyId,
        $holidayId
    ) {
        $a = $this->db
        ->select('
            holiday_title,     
            holiday_year,     
            icon,     
            from_date,     
            to_date,     
            is_archived,     
            work_on_holiday     
        ')
        ->where('sid', $holidayId)
        ->where('company_sid', $companyId)
        ->get('timeoff_holidays');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }
    
    
    /**
     * Get single holiday full
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param Integer  $holidayId
     * @param Integer  $companyId
     * 
     * @return Integer
     */
    function getCurrentHolidayById(
        $companyId,
        $holidayId
    ) {
        $a = $this->db
        ->where('sid', $holidayId)
        ->where('company_sid', $companyId)
        ->get('timeoff_holidays');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }


    /**
     * Fetch settings and formats list by company sid
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param Integer $companyId
     * @param Bool    $formats Optional
     * 
     * @return Array
     */
    function getSettingsAndFormats($companyId, $formats = FALSE){
        //
        $result = $this->db
        ->select('
            sid as setting_id,
            default_timeslot,
            pto_approval_check as approval_check,
            pto_email_receiver as email_check,
            accural_type,
            accrue_start_day,
            timeoff_type,
            is_lose_active,
            off_days,
            send_email_to_supervisor,
            accrue_start_date,
            timeoff_format_sid,
            theme
        ')
        ->from('timeoff_settings')
        ->where('company_sid', $companyId)
        ->get();
        //
        $settings = $result->row_array();
        $result = $result->free_result();
        //
        $result = $this->db
            ->select('sid as format_id, format as title')
            ->where('status', 1)
            ->from('timeoff_formats')
            ->get();
        //
        $formats = $result->result_array();
        $result = $result->free_result();
        //
        return array('Settings' => $settings, 'Formats' => $formats);
    }


    /**
     * Update Timeoff settings
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array $post
     * 
     * @return Integer
     */
    function updateSettings($post){
        //
        $rt = [
            'setting' => [],
            'settingId' => 0
        ];
        //
        $dataArray = array();
        $dataArray['default_timeslot'] = $post['defaultTimeslot'];
        $dataArray['pto_approval_check'] = $post['approvalCheck'];
        $dataArray['send_email_to_supervisor'] = $post['emailSendCheck'];
        $dataArray['pto_email_receiver'] = $post['emailCheck'];
        $dataArray['timeoff_format_sid'] = $post['format'];
        $dataArray['off_days'] = !isset($post['offDays']) || $post['offDays'] == null ? null : implode(',', $post['offDays']);
        $dataArray['theme'] = $post['theme'];

        // Check if default time option is set
        if($post['forAllEmployees'] == 1){
            $this->db
            ->where('parent_sid', $post['companyId'])
            ->update(
                'users', [
                    'user_shift_hours' => $post['defaultTimeslot']
                ]
            );
        }
        // Check if settiuing exists
        if ($this->db->where('company_sid', $post['companyId'])->count_all_results('timeoff_settings') == 0) {
            $dataArray['company_sid'] = $post['companyId'];
            // Insert
            $this->db->insert('timeoff_settings', $dataArray);
            //
            $rt['settingId'] = $this->db->insert_id();
        } else {
            //
            $rt['setting'] = $this->db
            ->where('company_sid', $post['companyId'])
            ->get('timeoff_settings')
            ->row_array();
            //
            $rt['settingId'] = $rt['setting']['sid'];
            // Update
            $this->db
            ->where('company_sid', $post['companyId'])
            ->update('timeoff_settings', $dataArray);
        }
        //
        $this->session->set_userdata('time_off_theme', $post['theme']);
        return $rt;
    }


    /**
     * Insert timeline history
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array  $insertArray
     * @param  String $table
     * 
     * @return Integer
     */
    function insertHistory(
        $insertArray,
        $table
    ){
        $this->db->insert($table, $insertArray);
        return $this->db->insert_id();
    }

    /**
     * Fetch company active departments & teams
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param Integer $companyId
     * 
     * @param Array
     */
    function getCompanyDepartmentsAndTeams($companyId){
        //
        $r = [
            'Departments' => [],
            'Teams' => []
        ];
        // Get departments
        $a =  $this->db
            ->select('sid as department_id, name as title')
            ->where('company_sid', $companyId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('name', 'ASC')
            ->get('departments_management');
        //
        $r['Departments'] = $a->result_array();
        $a = $a->free_result();
        // Get teams
        $this->db
            ->select('sid as team_id, name as title, department_sid')
            ->where('company_sid', $companyId)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->group_start();
        //
        if(count($r['Departments'])) $this->db->where_in('department_sid', array_column($r['Departments'], 'department_id'))->or_where('department_sid', 0);
        else $this->db->where('department_sid', 0);
        $this->db
            ->group_end()
            ->order_by('name', 'ASC');
        //
        $a = $this->db->get('departments_team_management');
        //
        $r['Teams'] = $a->result_array();
        $a = $a->free_result();
        //
        return $r;
    }

    /**
     * Get approvers by a company name
     *
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array $formpost
     * @return Array
     */
    function getApproversByCompany(
        $formpost,
        $page,
        $limit
    ) {
        // Set start
        $start = $page == 1 ? 0 : (($page * $limit) - $limit);
        //
        $this->db
            ->select('
                timeoff_approvers.sid as approver_id,
                timeoff_approvers.department_sid as department_id,
                timeoff_approvers.status,
                timeoff_approvers.is_department,
                timeoff_approvers.approver_percentage,
                timeoff_approvers.is_archived,
                timeoff_approvers.created_at,
                users.profile_picture as img,
                users.employee_number,
                users.sid as employee_id,
                users.first_name,
                users.last_name,
                users.access_level_plus,
                users.access_level,
                users.pay_plan_flag,
                users.job_title,
                users.is_executive_admin
            ')
            ->from('timeoff_approvers')
            ->join('users', 'timeoff_approvers.employee_sid = users.sid', 'inner')
            ->where('timeoff_approvers.is_archived', $formpost['filter']['archived'])
            ->where('timeoff_approvers.company_sid', $formpost['companyId'])
            ->order_by('users.first_name', 'ASC')
            ->limit($limit, $start);
        // Search Filter
        if ($formpost['filter']['status'] != '' && $formpost['filter']['status'] != -1) $this->db->where('timeoff_approvers.status', $formpost['filter']['status']);
        if (!empty($formpost['filter']['departments']) && !in_array('all', $formpost['filter']['departments'])){
            foreach($formpost['filter']['departments'] as $k => $dt){
                if($k == 0) $this->db->where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
                else $this->db->or_where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
            }
        } 
        if (!empty($formpost['filter']['teams']) && !in_array('all', $formpost['filter']['teams'])){
            foreach($formpost['filter']['teams'] as $k => $dt){
                if($k == 0) $this->db->where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
                else $this->db->or_where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
            }
        } 
        if ( isset($formpost['filter']['employees']) && $formpost['filter']['employees'] != '' && $formpost['filter']['employees'] != 'all') $this->db->where('timeoff_approvers.employee_sid', $formpost['filter']['employees']);
        if ($formpost['filter']['startDate'] != '' && $formpost['filter']['endDate']) $this->db->where('DATE_FORMAT(timeoff_approvers.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['filter']['startDate']) . ' AND ' . ($formpost['filter']['endDate']) . '');
        //
        $result = $this->db->get();
        $approvers = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($approvers)) return array();
        //
        foreach($approvers as $k => $approver){
            //
            $approvers[$k]['team_name'] = null;
            $approvers[$k]['department_name'] = null;
            //
            if($approver['is_department'] == 0 && $approver['department_id'] != 'all'){
                $a = $this->db
                ->select('name')
                ->where_in('sid', explode(',', $approver['department_id']))
                ->order_by('name', 'ASC')
                ->get('departments_team_management');
                //
                $b = $a->result_array();
                $a = $a->free_result();
                //
                $approvers[$k]['team_name'] = implode(', ', array_column($b, 'name'));
            }
            else if($approver['is_department'] == 1 && $approver['department_id'] != 'all'){
                $a = $this->db
                ->select('name')
                ->where_in('sid', explode(',', $approver['department_id']))
                ->order_by('name', 'ASC')
                ->get('departments_management');
                //
                $b = $a->result_array();
                $a = $a->free_result();
                //
                $approvers[$k]['department_name'] = implode(', ', array_column($b, 'name'));
            }
        }
        //
        $returnArray = array('Approvers' => $approvers, 'Count' => 0);
        //
        if ($page != 1) return $returnArray;
        //
        $this->db
            ->from('timeoff_approvers')
            ->join('users', 'timeoff_approvers.employee_sid = users.sid', 'inner')
            ->join('departments_management', 'departments_management.sid = timeoff_approvers.department_sid', 'left')
            ->where('timeoff_approvers.is_archived', $formpost['filter']['archived'])
            ->where('timeoff_approvers.company_sid', $formpost['companyId'])
            ->order_by('timeoff_approvers.sid', 'DESC');
        // Search Filter
        if ($formpost['filter']['status'] != '' && $formpost['filter']['status'] != -1) $this->db->where('timeoff_approvers.status', $formpost['filter']['status']);
        if (!empty($formpost['filter']['departments']) && !in_array('all', $formpost['filter']['departments'])){
            foreach($formpost['filter']['departments'] as $k => $dt){
                if($k == 0) $this->db->where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
                else $this->db->or_where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
            }
        } 
        if (!empty($formpost['filter']['teams']) && !in_array('all', $formpost['filter']['teams'])){
            foreach($formpost['filter']['teams'] as $k => $dt){
                if($k == 0) $this->db->where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
                else $this->db->or_where('find_in_set("'.( $dt).'", timeoff_approvers.department_sid)',false, false);
            }
        } 
        if (isset($formpost['filter']['employees']) && $formpost['filter']['employees'] != '' && $formpost['filter']['employees'] != 'all') $this->db->where('timeoff_approvers.employee_sid', $formpost['filter']['employees']);
        if ($formpost['filter']['startDate'] != '' && $formpost['filter']['endDate']) $this->db->where('DATE_FORMAT(timeoff_approvers.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['filter']['startDate']) . ' AND ' . ($formpost['filter']['endDate']) . '');
        //
        $returnArray['Count'] = $this->db->count_all_results();

        return $returnArray;
    }


    /**
     * Get timeline
     *
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  String  $cId
     * @param  Integer $id
     * @param  String  $id
     * 
     * @return Array
     */
    function getHistory(
        $cId,
        $id,
        $tbl
    ){
       //
        return $this->db
        ->select("
            $tbl.action, 
            $tbl.note, 
            $tbl.created_at,
            ".( getUserFields() )."
        ")
        ->join('users', "users.sid = $tbl.employee_sid", 'inner')
        ->where("$tbl.$cId", $id)
        ->order_by("$tbl.sid", 'DESC')
        ->get($tbl)
        ->result_array();
    }
    
    /**
     * Update table
     *
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array   $cId
     * @param  Integer $id
     * @param  String  $id
     * 
     * @return VOID
     */
    function updateTable(
        $upd,
        $id,
        $tbl
    ){
        //
        $this->db
        ->where('sid', $id)
        ->update(
            $tbl,
            $upd
        );
    }

    /**
     * Check if approver exists
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array $post
     * 
     * @return Integer
     */
    function companyApproverCheck($post){
        $this->db
            ->from('timeoff_approvers')
            ->where('employee_sid', $post['employee'][0]);
            //
        $this->db->group_start();
            //
            foreach($post['selectedEmployees'] as $k => $dt){
                if($k == 0) $this->db->where('find_in_set("'.($dt).'", department_sid) > 0', false, false);
                else $this->db->or_where('find_in_set("'.($dt).'", department_sid) > 0', false, false);
            }
        $this->db->group_end();
        //
        if(isset($post['type']) && $post['type'] == 1) $this->db->where('is_department', '1');
        else if(isset($post['type']) && $post['type'] == 0) $this->db->where('is_department', '0');
        if (isset($post['approverId'])) $this->db->where('sid <> ' . ($post['approverId']) . '', null);
        //
        return $this->db->count_all_results();
    }

    /**
     * Insert
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array $formpost
     * 
     * @return Integer
     */
    function insert(
        $insertArray,
        $tbl
    ){
        $this->db->insert('timeoff_approvers', $insertArray);
        return $this->db->insert_id();
    }

    /**
     * Get a single approver by id and company sid
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Array $formpost
     * 
     * @return Array
     */
    function getCompanyApprover($formpost){
        $result = $this->db
            ->select('
            timeoff_approvers.sid,
            timeoff_approvers.employee_sid,
            timeoff_approvers.department_sid,
            timeoff_approvers.status,
            timeoff_approvers.is_department,
            timeoff_approvers.approver_percentage,
            timeoff_approvers.is_archived
        ')
            ->from('timeoff_approvers')
            ->where('timeoff_approvers.company_sid', $formpost['companyId'])
            ->where('timeoff_approvers.sid', $formpost['approverId'])
            ->limit(1)
            ->get();
        //
        $approver = $result->row_array();
        $result = $result->free_result();
        //
        return $approver;
    }

    /**
     * Get a single approver by id
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Integer $approverId
     * 
     * @return Array
     */
    function getAprroverById($approverId){
        $result = $this->db
        ->from('timeoff_approvers')
        ->where('timeoff_approvers.sid', $approverId)
        ->limit(1)
        ->get();
        //
        $approver = $result->row_array();
        $result = $result->free_result();
        //
        return $approver;
    }

    
    /**
     * Get approver leads
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeeTeamMemberIds(
        $employeeId
    ){
        // Get departments & teams
        $approvers =
        $this->db
        ->select('
            is_department,
            department_sid
        ')
        ->where('employee_sid', $employeeId)
        ->where('is_archived', 0)
        ->get('timeoff_approvers')
        ->result_array();
        //
        if(empty($approvers)) return [];
        //
        $teams = [];
        $departments = [];
        //
        foreach($approvers as $approver){
            if($approver['is_department'] == 1) $departments[] = explode(',', $approver['department_sid']);
            else $teams[] = explode(',', $approver['department_sid']);
        }
        //
        if(in_array('all', $departments)) $departments = 'all';
        else{
            $nt = [];
            foreach($departments as $department){
                foreach($department as $t) $nt[] = $t;
            }
            $departments = $nt;
        }
        if(in_array('all', $teams)) $teams = 'all';
        else{
            $nt = [];
            foreach($teams as $team){
                foreach($team as $t) $nt[] = $t;
            }
            $teams = $nt;
        }
        //
        $de = [];
        $te = [];
        //
        if($departments != 'all' && !empty($departments)){
            // Get department employees
            $this->db
            ->select('departments_employee_2_team.employee_sid')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
            ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
            ->where('departments_management.status ', 1)
            ->where('departments_management.is_deleted ', 0)
            ->where('departments_team_management.status ', 1)
            ->where('departments_team_management.is_deleted ', 0);
            //
            if($departments != 'all' && !empty($departments)) $this->db->where_in('departments_employee_2_team.department_sid', $departments);
            //
            $de = $this->db
            ->get('departments_employee_2_team')
            ->result_array();
        }
        //
        if($teams != 'all' && !empty($teams)){
            // Get team employees
            $this->db
            ->select('departments_employee_2_team.employee_sid')
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
            ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
            ->where('departments_management.status ', 1)
            ->where('departments_management.is_deleted ', 0)
            ->where('departments_team_management.status ', 1)
            ->where('departments_team_management.is_deleted ', 0);
            //
            if($teams != 'all' && !empty($teams)) $this->db->where_in('departments_employee_2_team.team_sid', $teams);
            //
            $te = $this->db
            ->get('departments_employee_2_team')
            ->result_array();
        }
        //
        return array_filter(
                array_unique(
                array_column(
                    array_merge(
                        $de,
                        $te
                    ),
                    'employee_sid'
                ),
                SORT_STRING
            ), function ($emp){
                if($emp != 0) return $emp;
            }
        );
    }


    /**
     * Get policies with accruals
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Integer $companyId
     * @param  Booleon $withInclude
     * @param  Array   $policies
     * 
     * @return Array
     */
    function getCompanyPoliciesWithAccruals(
        $companyId,
        $withInclude = TRUE,
        $policies = []
    ){
        
        $this->db
        ->select('
            timeoff_policies.sid,
            timeoff_policies.title,
            timeoff_policies.accruals,
            timeoff_policies.assigned_employees,
            timeoff_policies.is_included,
            timeoff_policies.for_admin,
            timeoff_policies.default_policy,
            timeoff_category_list.category_name
        ')
        ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->where('timeoff_policies.company_sid', $companyId)
        ->where('timeoff_policies.is_archived', 0)
        ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        if($withInclude) $this->db->where('timeoff_policies.is_included', 1);
        if(!empty($policies)) $this->db->where_in('timeoff_policies.sid', $policies);
        //
        $policies = $this->db->get('timeoff_policies')
        ->result_array();
        //
        return $policies;
    }
    
    
    /**
     * Get balances
     * 
     * @employee  Mubashir Ahmed
     * @date      12/18/2020
     * 
     * @param  Integer $companyId
     * 
     * @return Array
     */
    function getBalances(
        $companyId
    ){
        $balances = 
        $this->db
        ->select('
            timeoff_balances.policy_sid,
            timeoff_balances.user_sid,
            timeoff_balances.is_added,
            timeoff_balances.added_time
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_balances.policy_sid', 'inner')
        ->where('timeoff_policies.company_sid', $companyId)
        ->where('timeoff_policies.is_archived', 0)
        ->where('timeoff_balances.effective_at <=', 'CURDATE()', FALSE)
        ->where('date_format(timeoff_balances.effective_at, "%Y") =', date('Y', strtotime('now')))
        ->get('timeoff_balances')
        ->result_array();
        //
        if(empty($balances)) return [];
        //
        $t = [];
        //
        foreach($balances as $balance){
            //
            $bb = $balance['user_sid'].'-'.$balance['policy_sid'];
            //
            if(!isset($t[$bb])) $t[$bb] = 0;
            //
            if($balance['is_added'] == '1') $t[$bb] += $balance['added_time'];
            else $t[$bb] -= $balance['added_time'];
        }
        //
        return $t;
    }

    /**
     * Get balances by employee
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeeBalanceBreakdown(
        $companyId,
        $employeeId
    ){
        $balances = 
        $this->db
        ->select('
            timeoff_balances.policy_sid,
            timeoff_balances.user_sid,
            timeoff_balances.is_added,
            timeoff_balances.added_time
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_balances.policy_sid', 'inner')
        ->where('timeoff_policies.company_sid', $companyId)
        ->where('timeoff_balances.user_sid', $employeeId)
        ->where('timeoff_policies.is_archived', 0)
        ->get('timeoff_balances')
        ->result_array();
        //
        if(empty($balances)) return [];
        //
        $t = [];
        //
        foreach($balances as $balance){
            //
            $t[$balance['policy_sid']] = $balance;
        }
        //
        return $t;
    }


    /**
     * Get balances of employees
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Array $post
     * 
     * @return Array
     */
    function getBalanceSheet(
        $post
    ){
        //
        $r = ['Balances' => [], 'Employees' => []];
        // If the employee is not a plus
        $inIds = [];
        // Check either the employee is a plus or not
        // If not then he/she can only see teams/departments
        if($post['level'] != 1) {
            $inIds = $this->getEmployeeTeamMemberIds(
                $post['employerId']
            );
            //
            if(empty($inIds)) return $r;
        }
        // _e($post, true, true);
        // Fetch all active employees 
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $post['companyId'])
        ->where('active', 1)
        ->limit($post['offset'], $post['inset'])
        ->where('terminated_status', 0);
        //
        if (!empty($inIds)) $this->db->where_in('sid', $inIds);
        if ($post['filter']['employees'] != '' && $post['filter']['employees'] != 'all') $this->db->where('sid', $post['filter']['employees']);
        //
        $a = $this->db->get('users');
        $employees = $a->result_array();
        $a->free_result();
        //
        if (empty($employees)) return $r;
        //
        $filterPolicies = [];
        //
        if(!is_array($post['filter']['policies'])) $post['filter']['policies'] = explode(',', $post['filter']['policies']);
        if(!empty($post['filter']['policies']) && !in_array('all', $post['filter']['policies'])) $filterPolicies = $post['filter']['policies'];
        //
        $settings = $this->getSettings($post['companyId']);
        $policies = $this->getCompanyPoliciesWithAccruals($post['companyId'], true, $filterPolicies);
        $balances = $this->getBalances($post['companyId']);
        // Loop through employees
        foreach ($employees as $k => $v) {
            //
            $r['Employees'][$v['userId']] = $v;
            //
            if(empty($policies)){
                $r['Balances'][] = [
                    'UserId' => $v['userId'],
                    'AllowedTime' => 0,
                    'ConsumedTime' => 0,
                    'RemainingTime' => 0
                ];
            } else {
                // Fetch employee policies
                $r['Balances'][] = 
                $this->getBalanceOfEmployee(
                    $v['userId'],
                    $v['employee_status'],
                    empty($v['joined_at']) ? $v['registration_date'] : $v['joined_at'],
                    (($v['user_shift_hours'] * 60) + $v['user_shift_minutes']),
                    $settings['slug'],
                    $policies,
                    $balances,
                    $v['employee_type']
                );
            }
        }
        //
        return $r;
    }


    /**
     * Get employee policies
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeePoliciesById(
        $companyId,
        $employeeId
    ){
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return [];
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = !empty($employee['joined_at']) ? $employee['joined_at'] : $employee['registration_date'];
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach($policies as $policy){
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'Title' => $policy['title'],
                'AllowedTime' => 0,
                'ConsumedTime' => 0,
                'CarryOverTime' => 0,
                'RemainingTime' => 0,
                'RemainingTimeWithNegative' => 0,
                'Balance' => 0,
                'MaxNegativeTime' => 0,
                'Settings' => [
                    'Slug' => $slug,
                    'ShiftHours' => $employee['user_shift_hours'],
                    'ShiftMinutes' => $employee['user_shift_minutes'],
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes']/60, 2))
                ],
                'Category' => $policy['category_name'],
                'Reason' => ''
            ];
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if(!isset($accruals['employeeTypes'])) continue;
            if(!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            $balance['AllowedTime'] = get_array_from_minutes($t['AllowedTime'], $durationInHours, $slug);
            $balance['ConsumedTime'] = get_array_from_minutes($t['ConsumedTime'], $durationInHours, $slug);
            $balance['CarryOverTime'] = get_array_from_minutes($t['CarryOverTime'], $durationInHours, $slug);
            $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug);
            $balance['MaxNegativeTime'] = get_array_from_minutes($t['MaxNegativeTime'], $durationInHours, $slug);
            $balance['Balance'] = get_array_from_minutes($t['Balance'], $durationInHours, $slug);
            $balance['EmployementStatus'] = $t['EmployementStatus'];
            $balance['IsUnlimited'] = $t['IsUnlimited'];
            $balance['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $balance['RemainingTimeWithNegative'] = get_array_from_minutes($t['RemainingTimeWithNegative'], $durationInHours, $slug);
            $balance['Reason'] = $t['Reason'];
            //
            $r[] = $balance;
            
        }
        //
        return $r;
    }
    
    
    /**
     * Get employee policies with approvers
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeePoliciesWithApproversById(
        $companyId,
        $employeeId
    ){
        $e['Policies'] = [];
        $e['Approvers'] = [];
        // Get approvers
        $e['Approvers'] = $this->getEmployeeApprovers($companyId, $employeeId);
        //
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return $e;
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = !empty($employee['joined_at']) ? $employee['joined_at'] : $employee['registration_date'];
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach($policies as $policy){
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'Title' => $policy['title'],
                'AllowedTime' => 0,
                'ConsumedTime' => 0,
                'RemainingTime' => 0,
                'RemainingTimeWithNegative' => 0,
                'Balance' => 0,
                'MaxNegativeTime' => 0,
                'Settings' => [
                    'Slug' => $slug,
                    'ShiftHours' => $employee['user_shift_hours'],
                    'ShiftMinutes' => $employee['user_shift_minutes'],
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes']/60, 2))
                ],
                'Category' => $policy['category_name'],
                'Reason' => ''
            ];
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if(!isset($accruals['employeeTypes'])) continue;
            if(!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            $balance['AllowedTime'] = get_array_from_minutes($t['AllowedTime'], $durationInHours, $slug);
            $balance['ConsumedTime'] = get_array_from_minutes($t['ConsumedTime'], $durationInHours, $slug);
            $balance['CarryOverTime'] = get_array_from_minutes($t['CarryOverTime'], $durationInHours, $slug);
            $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug);
            $balance['MaxNegativeTime'] = get_array_from_minutes($t['MaxNegativeTime'], $durationInHours, $slug);
            $balance['Balance'] = get_array_from_minutes($t['Balance'], $durationInHours, $slug);
            $balance['EmployementStatus'] = $t['EmployementStatus'];
            $balance['IsUnlimited'] = $t['IsUnlimited'];
            $balance['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $balance['RemainingTimeWithNegative'] = get_array_from_minutes($t['RemainingTimeWithNegative'], $durationInHours, $slug);
            $balance['Reason'] = $t['Reason'];
            //
            $r[] = $balance;
            
        }
        //
        $e['Policies'] = $r;
        return $e;
    }

    /**
     * Get balances by employee
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getBalanceOfEmployee(
        $employeeId,
        $employementStatus,
        $employeeJoiningDate,
        $durationInMinutes,
        $slug,
        $policies,
        $balances,
        $employementType
    ){
        //
        $balance = [
            'UserId' => $employeeId,
            'AllowedTime' => 0,
            'PolicyIds' => [],
            'ConsumedTime' => 0,
            'CarryOverTime' => 0,
            'UnpaidConsumedTime' => 0,
            'RemainingTime' => 0,
            'EmployementStatus' => 'probation',
            'RemainingTimeWithNegative' => 0,
            'Balance' => 0,
            'MaxNegativeTime' => 0,
            'Reason' => ''
        ];
        //
        $returnBalance = [];
        $returnBalance['total'] = $balance;
        //
        foreach($policies as $policy){
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if(!isset($accruals['employeeTypes'])) continue;
            if(!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug
            );
            //
            if(empty($t['Reason'])){
                $returnBalance['total']['PolicyIds'][] = $policy['sid'];
            }
            //
            $returnBalance[$policy['title']] = $balance;
            $durationInHours = $durationInMinutes / 60;
            //
            $returnBalance[$policy['title']]['AllowedTime'] = get_array_from_minutes($t['AllowedTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['ConsumedTime'] = get_array_from_minutes($t['ConsumedTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['CarryOverTime'] = get_array_from_minutes($t['CarryOverTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['UnpaidConsumedTime'] = get_array_from_minutes($t['UnpaidConsumedTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['MaxNegativeTime'] = get_array_from_minutes($t['MaxNegativeTime'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['Balance'] = get_array_from_minutes($t['Balance'], $durationInHours, $slug);
            $returnBalance[$policy['title']]['EmployementStatus'] = $returnBalance[$policy['title']]['EmployementStatus'];
            $returnBalance[$policy['title']]['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $returnBalance[$policy['title']]['IsUnlimited'] = $t['IsUnlimited'];
            $returnBalance[$policy['title']]['RemainingTimeWithNegative'] = get_array_from_minutes($t['RemainingTimeWithNegative'], $durationInHours, $slug);
            //
            $returnBalance['total']['AllowedTime'] += $t['AllowedTime'];
            $returnBalance['total']['Balance'] += $t['Balance'];
            $returnBalance['total']['IsUnlimited'] = $t['IsUnlimited'];
            $returnBalance['total']['CarryOverTime'] += $t['CarryOverTime'];
            $returnBalance['total']['EmployementStatus'] = $t['EmployementStatus'];
            $returnBalance['total']['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            if($t['AllowedTime'] != 0){
                $returnBalance['total']['ConsumedTime'] += $t['ConsumedTime'];
                $returnBalance['total']['RemainingTime'] += $t['RemainingTime'];
                $returnBalance['total']['MaxNegativeTime'] += $t['MaxNegativeTime'];
                $returnBalance['total']['RemainingTimeWithNegative'] += $t['RemainingTimeWithNegative'];
            } else{
                $returnBalance['total']['UnpaidConsumedTime'] += $t['ConsumedTime'];
            }
        }
        $durationInHours = $durationInMinutes / 60;
        // //
        $returnBalance['total']['AllowedTime'] = get_array_from_minutes($returnBalance['total']['AllowedTime'], $durationInHours, $slug);
        $returnBalance['total']['ConsumedTime'] = get_array_from_minutes($returnBalance['total']['ConsumedTime'], $durationInHours, $slug);
        $returnBalance['total']['CarryOverTime'] = get_array_from_minutes($returnBalance['total']['CarryOverTime'], $durationInHours, $slug);
        $returnBalance['total']['UnpaidConsumedTime'] = get_array_from_minutes($returnBalance['total']['UnpaidConsumedTime'], $durationInHours, $slug);
        $returnBalance['total']['RemainingTime'] = get_array_from_minutes($returnBalance['total']['RemainingTime'], $durationInHours, $slug);
        $returnBalance['total']['MaxNegativeTime'] = get_array_from_minutes($returnBalance['total']['MaxNegativeTime'], $durationInHours, $slug);
        $returnBalance['total']['Balance'] = get_array_from_minutes($returnBalance['total']['Balance'], $durationInHours, $slug);
        $returnBalance['total']['EmployementStatus'] = $returnBalance['total']['EmployementStatus'];
        $returnBalance['total']['IsUnlimited'] = $returnBalance['total']['IsUnlimited'];
        $returnBalance['total']['RemainingTimeWithNegative'] = get_array_from_minutes($returnBalance['total']['RemainingTimeWithNegative'], $durationInHours, $slug);
        //
        return $returnBalance;
    }

    /** 
     * Get employee consumed time
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $policyId
     * @param  Integer $employeeId
     * @param  String  $method
     * @param  String  $asOfToday
     * 
     * @return Array
     */
    function getEmployeeConsumedTime(
        $policyId,
        $employeeId,
        $method,
        $frequency,
        $todayDate
    ) {
        $dateFormat = 'Y';
        $dateFormatDB = '%Y';
        // Fetch current used timeoff
        if($frequency == 'monthly'){
            $dateFormat = 'Y-m';
            $dateFormatDB = '%Y-%m';
        }
        // $date = date($dateFormat, strtotime('now'));
        //
        $this->db
            ->select('
            SUM(requested_time) as requested_time
        ')
            ->from('timeoff_requests')
            ->where('timeoff_policy_sid', $policyId)
            ->where('employee_sid', $employeeId)
            ->where('status', 'approved')
            ->where('is_draft', 0)
            ->where('archive', 0);
        //
        $newDate = DateTime::createfromformat('Y-m-d', $todayDate)->format($dateFormat);
        $this->db->where("date_format(request_from_date, \"$dateFormatDB\") = ", "$newDate");
        //
        $result = $this->db->get();
        //
        $record = $result->row_array();
        $result->free_result();
        //
        return $record['requested_time'] == null ? 0 : $record['requested_time'];
    }

    /** 
     * Get employee carryover time
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $policyId
     * @param  Integer $employeeId
     * @param  String  $method
     * @param  Integer $accrualRateInMinutes
     * @param  Integer $carryOverInMinutes
     * @param  String  $todayDate
     * @param  Integer $monthsWorked
     * @param  Integer $accrualRateInMinutesWithoutAward
     * 
     * @return Array
     */
    function getEmployeeCarryOverTime(
        $policyId,
        $employeeId,
        $method,
        $frequency,
        $rateType,
        $accrualRateInMinutes,
        $carryOverInMinutes,
        $todayDate,
        $monthsWorked,
        $accrualRateInMinutesWithoutAward
    ) {
        //
        $dateFormat = 'Y-m';
        $dateFormatDB = '%Y-%m';
        //
        if($frequency == 'yearly'){
            $dateFormat = 'Y';
            $dateFormatDB = '%Y';
        }
        //
        $date = date($dateFormat, strtotime($todayDate.' 00:00:00'));
        //
        $result = $this->db
        ->select('
        SUM(requested_time) as consumed,
        COUNT(DISTINCT(DATE_FORMAT(request_from_date, "' . ($dateFormatDB) . '"))) as occurrence
        ')
        ->from('timeoff_requests')
        ->where('timeoff_policy_sid', $policyId)
        ->where('employee_sid', $employeeId)
        ->where('status', 'approved')
        ->where('is_draft', 0)
        ->where("date_format(request_from_date, \"$dateFormatDB\") <= ", "$date")
        ->get();
        //
        $record = $result->row_array();
        $result->free_result();

        //
        $consumedTimeInMinutes = 0;
        //
        if (!empty($record) && $record['consumed'] != ''){
            //
            $consumedTimeInMinutes = $record['occurrence'] * $record['consumed'];
        }
        //
        $monthsWorked -= 1;
        $returnTime = 0;
        $years = ($monthsWorked >= 12 ? ($monthsWorked) / 12 : 0);
        //
        if($frequency == 'none'){
            $newTime = $accrualRateInMinutes * $monthsWorked;
            $cNewTime = $carryOverInMinutes * $monthsWorked;
            //
            if($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if($frequency == 'monthly'){
            $newTime = $accrualRateInMinutes * $monthsWorked;
            $cNewTime = $carryOverInMinutes * $monthsWorked;
            //
            if($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if($frequency == 'yearly'){
            $newTime = $accrualRateInMinutes * $years;
            $cNewTime = $carryOverInMinutes * $years;
            //
            if($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if($frequency == 'custom'){
            $newTime = $accrualRateInMinutes * $years;
            $cNewTime = $carryOverInMinutes * $years;
            //
            if($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        //
        return $returnTime < 0 ? 0 : $returnTime;
    }

    /** 
     * Get company settings
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * 
     * @return Array
     */
    function getSettings(
        $companyId
    ){
        return
        $this->db
        ->select('
            timeoff_settings.send_email_to_supervisor,
            timeoff_settings.off_days,
            timeoff_formats.slug
        ')
        ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid', 'inner')
        ->where('timeoff_settings.company_sid', $companyId)
        ->where('timeoff_formats.status', 1)
        ->get('timeoff_settings')
        ->row_array();
    }

    /** 
     * Get employee manual balance history
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeeBalanceHistory(
        $companyId,
        $employeeId
    ){
        $this->db->select('
            user_shift_hours,
            user_shift_minutes
        ')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return $r;
        //
        $settings = $this->getSettings($companyId);
        //
        $r = [];
        //
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];

        
         //
         $a = $this->db
         ->select('
             timeoff_balances.is_added,
             timeoff_balances.added_time,
             timeoff_balances.note,
             timeoff_balances.effective_at,
             timeoff_balances.created_at,
             users.first_name,
             users.last_name,
             users.access_level,
             users.access_level_plus,
             users.is_executive_admin,
             users.pay_plan_flag,
             users.job_title,
             timeoff_policies.title
         ')
         ->join('users', 'users.sid = timeoff_balances.added_by', 'inner')
         ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_balances.policy_sid', 'inner')
         ->where('timeoff_balances.user_sid', $employeeId)
         ->where('timeoff_policies.is_archived', 0)
         ->order_by('timeoff_balances.sid', 'DESC')
         ->get('timeoff_balances');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        
        // Get employees taken time off
        $a = $this->db
        ->select('
            timeoff_requests.requested_time as added_time,
            timeoff_requests.created_at,
            timeoff_requests.updated_at as effective_at ,
            "0" as is_added,
            timeoff_requests.reason as note,
            users.first_name,
            users.last_name,
            users.access_level,
            users.access_level_plus,
            users.is_executive_admin,
            users.pay_plan_flag,
            users.job_title,
            timeoff_policies.title
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
        ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
        ->where('timeoff_requests.archive', 0)
        ->where('timeoff_requests.status', 'approved')
        ->where('timeoff_policies.is_archived', 0)
        ->where('timeoff_requests.employee_sid', $employeeId)
        ->order_by('timeoff_requests.created_at', 'desc')
        ->get('timeoff_requests');
        //
        $c = $a->result_array();
        $a = $a->free_result();
        //
        if(count($c)) $b = array_merge($b, $c);

        //
        if(count($b)){
            foreach($b as $k => $v){
                $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
                    $v['added_time'],
                    $durationInMinutes,
                    $slug
                );
            }
        }
        //
        return $b;
    }

    /** 
     * Add employee manaual balance
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Array $post
     * 
     * @return Array
     */
    function addEmployeeBalance($post){
        //
        $this->db->insert(
            'timeoff_balances',[
                'user_sid' => $post['employeeId'],
                'added_by' => $post['employerId'],
                'is_added' => $post['btype'] == 'add' ? 1 : 0,
                'added_time' => $post['time'],
                'note' => $post['note'],
                'effective_at' => DateTime::createFromFormat('m/d/Y', $post['effectedDate'])->format('Y-m-d'),
                'policy_sid' => $post['policy']
            ]
        );
        return $this->db->insert_id();
    }

    /**
     * Get employee policies
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * @param  String  $startDate
     * 
     * @return Array
     */
    function getEmployeePoliciesByDate(
        $companyId,
        $employeeId,
        $startDate
    ){
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type
        ')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return $r;
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = !empty($employee['joined_at']) ? $employee['joined_at'] : $employee['registration_date'];
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach($policies as $policy){
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'Title' => $policy['title'],
                'AllowedTime' => 0,
                'ConsumedTime' => 0,
                'RemainingTime' => 0,
                'RemainingTimeWithNegative' => 0,
                'Balance' => 0,
                'MaxNegativeTime' => 0,
                'Category' => $policy['category_name'],
                'Settings' => [
                    'Slug' => $slug,
                    'ShiftHours' => $employee['user_shift_hours'],
                    'ShiftMinutes' => $employee['user_shift_minutes'],
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes']/60, 2))
                ],
                'Reason' => ''
            ];
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if(!isset($accruals['employeeTypes'])) continue;
            if(!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                $startDate,
                $slug
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            $balance['AllowedTime'] = get_array_from_minutes($t['AllowedTime'], $durationInHours, $slug);
            $balance['ConsumedTime'] = get_array_from_minutes($t['ConsumedTime'], $durationInHours, $slug);
            $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug);
            $balance['CarryOverTime'] = get_array_from_minutes($t['CarryOverTime'], $durationInHours, $slug);
            $balance['MaxNegativeTime'] = get_array_from_minutes($t['MaxNegativeTime'], $durationInHours, $slug);
            $balance['Balance'] = get_array_from_minutes($t['Balance'], $durationInHours, $slug);
            $balance['IsUnlimited'] = $t['IsUnlimited'];
            $balance['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $balance['EmployementStatus'] = $t['EmployementStatus'];
            $balance['RemainingTimeWithNegative'] = get_array_from_minutes($t['RemainingTimeWithNegative'], $durationInHours, $slug);
            $balance['Reason'] = $t['Reason'];
            //
            $r[] = $balance;
        }
        //
        return $r;
        
    }

    //
    function insertTimeOff($ins){
        $this->db->insert('timeoff_requests', $ins);
        return $this->db->insert_id();
    }

    //
    function getRequestById($requestId, $addApprovers = TRUE){
        //
        $request = 
        $this->db
        ->select('
            timeoff_requests.*,
            timeoff_policies.title,
            company.CompanyName,
            users.user_shift_hours,
            users.user_shift_minutes,
            '.( getUserFields()).'
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
        ->join('users as company', 'company.sid = timeoff_requests.company_sid', 'inner')
        ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
        ->where('timeoff_requests.sid', $requestId)
        ->get('timeoff_requests')
        ->row_array();
        $request['history'] = $this->timeoff_model->getHistory(
            'request_sid',
            $request['sid'],
            'timeoff_request_timeline'
        );
        if($addApprovers){
            // Get approvers
            $request['approvers'] = $this->getEmployeeApprovers($request['company_sid'], $request['employee_sid']);
        }
        //
        if(empty($request['timeoff_days']) || $request['timeoff_days'] = '[]'){
            //
            $user =
            $this->db
            ->select('user_shift_hours, user_shift_minutes')
            ->where('sid', $request['userId'])
            ->get('users')
            ->row_array();
            //
            $json = [
                'totalTime' => $request['requested_time'],
                'days' => []
            ];
            //
            $s = date_create($request['request_from_date']);
            $e = date_create($request['request_to_date']);
            $d = date_diff($s, $e)->d + 1;
            //
            $totalTimeInHours = ceil($request['requested_time'] / 60);
            //
            for($i = 0; $i < $d; $i++){
                //
                $newDate = date('m-d-Y', strtotime($request['request_from_date'].' 00:00:00 +'.($i).' days'));
                //
                $time = 0;
                //
                if($totalTimeInHours > $user['user_shift_hours']){
                    //
                    $time = $user['user_shift_hours'];
                    //
                } else $time = $totalTimeInHours;
                // 
                $totalTimeInHours -= $time;
                //
                $json['days'][$i] = [
                    'time' => $time * 60,
                    'partial' => 'fullday',
                    'date' => $newDate
                ];
            }
            //
            $request['timeoff_days'] = json_encode($json);
        }

        $settings = $this->getSettings($request['company_sid']);
        //
        $request['breakdown'] = get_array_from_minutes(
            $request['requested_time'],
            (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
            $settings['slug']
        );
        //
        return $request;
    }

    //
    function getRequests(
        $post
    ){
        //
        $notIds = [];
        //
        if($post['level'] != 1){
            $notIds = $this->getEmployeeTeamMemberIds($post['employerId']);
        }
        //
        $this->db
        ->select('
            timeoff_requests.*,
            users.user_shift_hours,
            users.user_shift_minutes,
            timeoff_policies.title,
            '.( getUserFields()).'
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
        ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
        ->where('timeoff_policies.is_archived', 0)
        ->where('timeoff_requests.company_sid', $post['companyId']);
        //
        if($post['type'] != 'archive') {
            $this->db->where('timeoff_requests.status', $post['type']);
            $this->db->where('timeoff_requests.archive', 0);
        } else{
            $this->db->where('timeoff_requests.archive', 1);
        }
        //
        if(isset($post['isMine']) && $post['isMine'] == 1){
            $notIds = [];
            $this->db->where('timeoff_requests.employee_sid', $post['employeeId']);
        }
        if($post['level'] == 0 && empty($notIds)) return [];
        //
        if(!empty($notIds)) $this->db->where_in('timeoff_requests.employee_sid', $notIds);
        else if($post['filter']['employees'] != 'all'){
            $this->db->where('timeoff_requests.employee_sid', $post['filter']['employees']);
        }
        //
        if($post['filter']['order'] == 'created_start_desc'){
            $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        } else if($post['filter']['order'] == 'created_start_asc'){
            $this->db->order_by('timeoff_requests.request_from_date', 'ASC', false);
        } else{
            // $this->db->where('timeoff_requests.request_from_date >= ', 'CURDATE()', false);
            $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        }
        //
        $this->db->where('timeoff_requests.request_from_date >= "'.(date('Y')).'-01-01"', null);
        $this->db->where('timeoff_requests.request_from_date <= "'.(date('Y')).'-12-31"', null);
        //
        $requests = $this->db->get('timeoff_requests')
        ->result_array();
        //
        if(!empty($requests)){
            //
            if($post['filter']['order'] == 'upcoming'){
                // Sort requests by date
                $newRequests = [];
                $oldRequests = [];
                //
                foreach($requests as $request){
                    if($request['request_from_date'] > date('Y-m-d', strtotime('now'))){
                        $newRequests[] = $request;
                    } else $oldRequests[] = $request;
                }
                //
                $requests = array_merge(
                    array_reverse($newRequests),
                    $oldRequests
                );
            }

            //
            $settings = $this->getSettings($post['companyId']);
            //
            foreach($requests as $k => $request){
                $requests[$k]['breakdown'] = get_array_from_minutes(
                    $request['requested_time'],
                    (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
                    $settings['slug']
                );
                $requests[$k]['history'] = $this->timeoff_model->getHistory(
                    'request_sid',
                    $request['sid'],
                    'timeoff_request_timeline'
                );
            }
        }
        //
        return $requests;
    }

    //
    function getMyEmployees($post)
    {
        // fetch all active employees 
        $this->db->select('
            sid as employee_id,
            is_executive_admin,
            job_title,
            first_name,
            last_name,
            access_level_plus,
            access_level,
            pay_plan_flag,
            concat(first_name," ", last_name) as full_name
        ')
            ->order_by('full_name', 'ASC')
            ->where('parent_sid', $post['companyId'])
            ->where('active', 1);
        //
        $a = $this->db->get('users');
        $e = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($e)) return $e;
        // Check for access level plus check
        if ($post['isSuper'] == 0) {
            // Start filtering
            // Check if a department is assigned to current login employee 
            $a = $this->db
                ->select('sid')
                ->where('supervisor', $post['employeeSid'])
                ->get('departments_management');
            //
            $d = $a->result_array();
            $a->free_result();
            $ts = array();
            //
            if (sizeof($d)) {
                // Get teams
                foreach ($d as $v) {
                    $a = $this->db
                        ->select('sid')
                        ->where('department_sid', $v['sid'])
                        ->get('departments_team_management');
                    //
                    $t = $a->result_array();
                    $a->free_result();
                    //
                    if (!sizeof($t)) continue;
                    //
                    foreach ($t as $v1) $ts[$v1['sid']] = $v1['sid'];
                }
            }
            // Check in teams
            $a = $this->db
                ->select('sid')
                ->where('team_lead', $post['employeeId'])
                ->get('departments_team_management');
            //
            $t = $a->result_array();
            $a->free_result();
            //
            if (sizeof($t)) foreach ($t as $v) $ts[$v['sid']] = $v['sid'];
            //
            $ts = array_values($ts);
        }
        //
        $r = array();
        // Loop through employees
        foreach ($e as $k => $v) {
            // If not super admin then skip
            if ($post['isSuper'] == 0) {
                // Check if exists in team
                $a = $this->db
                    ->where('employee_sid', $v['employeeId'])
                    ->count_all_results('departments_employee_2_team');
                //
                if (!$a) continue;
            }

            $r[] = $v;
        }

        return $r;
    }


    /**
     * Get policies created by a company
     *
     * @param Array $formpost
     * @return Array
     */
    function getAllCompanyPolicies(
        $formpost
    ) {
        //
        $this->db
            ->select('
            timeoff_policies.sid as policyId,
            timeoff_policies.title as policy_title
        ')
            ->from('timeoff_policies')
            ->where('timeoff_policies.is_archived', 0)
            ->where('timeoff_policies.company_sid', $formpost['companyId'])
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        $result = $this->db->get();
        $policies = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($policies)) return array();
        //
        return $policies;
    }

    //
    function getGraphBalanceOfEmployee(
        $companyId,
        $employeeId
    ){
        //
        $r = [
            'Balance' => [
                'Remaining' => 0,
                'Consumed' => 0
            ], 
            'Timeoffs' => [
                1 => [0, 0],
                2 => [0, 0],
                3 => [0, 0],
                4 => [0, 0],
                5 => [0, 0],
                6 => [0, 0],
                7 => [0, 0],
                8 => [0, 0],
                9 => [0, 0],
                10 => [0, 0],
                11 => [0, 0],
                12 => [0, 0]
            ]
        ];
        // Fetch all active employees 
        $this->db->select('
            joined_at,
            registration_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return $r;
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        // Fetch employee policies
        $balance = 
        $this->getBalanceOfEmployee(
            $employeeId,
            $employee['employee_status'],
            empty($employee['joined_at']) ? $employee['registration_date'] : $employee['joined_at'],
            (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']),
            $settings['slug'],
            $policies,
            $balances,
            $employee['employee_type']
        );

        //
        $r['Balance'] = [
            'Remaining' => $balance['total']['RemainingTime'],
            'Consumed' => get_array_from_minutes(
                $balance['total']['ConsumedTime']['M']['minutes']+ $balance['total']['UnpaidConsumedTime']['M']['minutes'],
                (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']),
                $settings['slug']
            )
        ];
        //
        if(!empty($balance['total']['PolicyIds'])){
            // Get time off for current years
            $timeoffs = $this->db
            ->select('
                timeoff_requests.request_from_date, 
                timeoff_requests.request_to_date, 
                timeoff_requests.requested_time,
                timeoff_requests.timeoff_days
            ')
            ->where('timeoff_requests.employee_sid', $employeeId)
            ->where('timeoff_requests.company_sid', $companyId)
            ->where('timeoff_requests.status', 'approved')
            ->where('timeoff_requests.archive', 0)
            ->where_in('timeoff_requests.timeoff_policy_sid', $balance['total']['PolicyIds'])
            ->where('timeoff_requests.request_from_date >= ', ''.(date('Y')).'-01-01')
            ->where('timeoff_requests.request_from_date <= ', ''.(date('Y')).'-12-31')
            ->get('timeoff_requests')
            ->result_array();
        } else{
            $timeoffs = [];
        }
        //
        $durationInHours = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']) / 60;
        //
        if(!empty($timeoffs)){
            //
            $tif = [];
            foreach($timeoffs as $timeoff){
                //
                if(empty($timeoff['timeoff_days']) || $timeoff['timeoff_days'] = '[]'){
                    //
                    $json = [
                        'totalTime' => $timeoff['requested_time'],
                        'days' => []
                    ];
                    //
                    $s = date_create($timeoff['request_from_date']);
                    $e = date_create($timeoff['request_to_date']);
                    $d = date_diff($s, $e)->d + 1;
                    //
                    $totalTimeInHours = ceil($timeoff['requested_time'] / 60);
                    //
                    for($i = 0; $i < $d; $i++){
                        //
                        $newDate = date('m-d-Y', strtotime($timeoff['request_from_date'].' 00:00:00 +'.($i).' days'));
                        //
                        $time = 0;
                        //
                        if($totalTimeInHours > $employee['user_shift_hours']){
                            //
                            $time = $employee['user_shift_hours'];
                            //
                        } else $time = $totalTimeInHours;
                        // 
                        $totalTimeInHours -= $time;
                        //
                        $json['days'][$i] = [
                            'time' => $time * 60,
                            'partial' => 'fullday',
                            'date' => $newDate
                        ];
                    }
                    //
                    $timeoff['timeoff_days'] = json_encode($json);
                }
                //
                $month = formatDate($timeoff['request_from_date'], 'Y-m-d', 'm');
                //
                $month = strpos($month, 0) == 0 ? ltrim($month, 0) : $month;
                //
                $r['Timeoffs'][$month][0]++;
                //
                $jta = json_decode($timeoff['timeoff_days'], true);
                //
                if(!empty($jta)) $r['Timeoffs'][$month][1] += $jta['totalTime'];
            }
            //
            foreach($r['Timeoffs'] as $k => $ti){
                if($ti != 0){
                    $r['Timeoffs'][$k][1] = get_array_from_minutes($ti[1], $durationInHours, $settings['slug']);
                }
            }
        }
        //
        return $r;
    }


    //
    function getEmployeeApprovers(
        $companyId,
        $employeeId
    ){
        // Get team leads and supervisors
        $n =
        $this->db
        ->select('
            departments_team_management.sid as team_sid,
            departments_management.sid
        ')
        ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
        ->where('departments_team_management.status', 1)
        ->where('departments_team_management.is_deleted', 0)
        ->where('departments_team_management.company_sid', $companyId)
        ->where('departments_management.status', 1)
        ->where('departments_management.is_deleted', 0)
        ->where('departments_management.company_sid', $companyId)
        ->where('departments_employee_2_team.employee_sid', $employeeId)
        ->get('departments_employee_2_team')
        ->result_array();
        //
        $teamIds = array_column($n, 'team_sid');
        $departmentIds = array_column($n, 'sid');
        //
        $tWhere = '';
        $dWhere = '';
        // Get approvers
        $this->db
        ->select('
        timeoff_approvers.approver_percentage,
        '.(getUserFields()).'
        ')
        ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
        ->where('timeoff_approvers.company_sid', $companyId)
        ->where('timeoff_approvers.status', 1)
        ->where('timeoff_approvers.is_archived', 0);
        //
        if(!empty($teamIds)) {
            foreach($teamIds as $teamId) $tWhere .= "FIND_IN_SET($teamId, timeoff_approvers.department_sid) > 0 OR ";
            $tWhere = rtrim($tWhere, " OR ");
        }
        if(!empty($departmentIds)) {
            foreach($departmentIds as $departmentId) $dWhere .= "FIND_IN_SET($departmentId, timeoff_approvers.department_sid) > 0 OR ";
            $dWhere = rtrim($dWhere, " OR ");
        }
        $this->db->group_start();

        
        //
        if(!empty($tWhere) && !empty($dWhere)){
            $this->db->group_start();
            $this->db->group_start();
            $this->db->where(rtrim($tWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 0);
            $this->db->group_end();
            $this->db->or_group_start();
            $this->db->where(rtrim($dWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 1);
            $this->db->group_end();
            $this->db->group_end();
        } else if(!empty($tWhere)){
            $this->db->group_start();
            $this->db->where(rtrim($tWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 0);
            $this->db->group_end();
        }  else if(!empty($dWhere)){
            $this->db->group_start();
            $this->db->where(rtrim($dWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 1);
            $this->db->group_end();
        }
        $this->db->or_where('timeoff_approvers.department_sid', 'all');
        $this->db->group_end();
        //

        $approvers = $this->db->get('timeoff_approvers')->result_array();

        // $users = 
        // $this->db
        // ->select('
        // "1" as plus,
        // "1" as approver_percentage,
        // '.(getUserFields()).'
        // ')
        // ->where('users.parent_sid', $companyId)
        // ->where('users.active', 1)
        // ->where('users.terminated_status', 0)
        // ->group_start()
        // ->where('users.access_level_plus', 1)
        // ->or_where('users.pay_plan_flag', 1)
        // ->group_end()
        // ->get('users')
        // ->result_array();
        
        // //
        // $approvers = array_merge($approvers, $users);
        //
        $t = [];
        //
        foreach($approvers as $k => $approver){
            if(in_array($approver['userId'], $t)) {
                unset($approvers[$k]);
                continue;
            }
            $t[] =$approver['userId'];
        }

        //
        return array_values($approvers);
    }

    //
    function getEmployerApprovalStatus(
        $employeeId,
        $ems = false
    ){
        //
        $b = 
        $this->db
        ->where('sid', $employeeId)
        ->group_start()
        ->where('access_level_plus', 1)
        ->or_where('pay_plan_flag', 1)
        ->group_end()
        ->count_all_results('users');
        //
        if($b != 0) return 1;
        // Check approver
        $this->db
        ->where('employee_sid', $employeeId);
        //
        if(!$ems){
            $this->db->where('approver_percentage', 1);
        }
        
        $b = $this->db->where('is_archived', 0)
        ->count_all_results('timeoff_approvers');
        //
        return $b;
    }

    //
    function push_default_timeoff_policy () {
        //
        $category_sid = '';
        //
        // Get all those companies which have timeoff module  
        $companies_sid = $this->db
                            ->select('company_sid')
                            ->where('module_sid', 1)
                            ->get('company_modules')
                            ->result_array();
        //
        // Check Unpaid category exist in "timeoff_category_list" table
        $is_category_exist = $this->db
                            ->select('sid')
                            ->where('LOWER(category_name)', 'unpaid')
                            ->get('timeoff_category_list')
                            ->row_array();
        //
        if (empty($is_category_exist)) {
            $category_sid = $this->insertMainCategory('Unpaid');  
        } else {
            $category_sid = $is_category_exist['sid'];
        } 

        if (!empty($category_sid) && $category_sid != 0) {
            //            
            $accruals = [];
            $accruals['method']                 = 'days_per_year';
            $accruals['time']                   = 'none';
            $accruals['frequency']              = 'none';
            $accruals['frequencyVal']           = 0;
            $accruals['rate']                   = 0;
            $accruals['rateType']               = 'days';
            $accruals['applicableTime']         = 0;
            $accruals['applicableTimeType']     = 'hours';
            $accruals['carryOverCheck']         = 'no';
            $accruals['carryOverType']          = 'days';
            $accruals['carryOverVal']           = 0;
            $accruals['negativeBalanceCheck']   = 'no';
            $accruals['negativeBalanceType']    = 'days';
            $accruals['negativeBalanceVal']     = 0;
            $accruals['applicableDate']         = 0;
            $accruals['applicableDateType']     = 'hireDate';
            $accruals['resetDate']              = 0;
            $accruals['resetDateType']          = 'policyDate';
            $accruals['newHireTime']            = 0;
            $accruals['newHireTimeType']        = 'hours';
            $accruals['newHireRate']            = 0;
            $accruals['plans']                  = array();
            //
            //
            foreach ($companies_sid as $key => $company_sid) {
                //
                $time_off_category_sid = '';
                //
                $creator_sid = getCompanyAdminSid($company_sid['company_sid']);
                //
                $is_time_off_category_exist = $this->db
                                ->select('sid')
                                ->where('company_sid', $company_sid['company_sid'])
                                ->where('timeoff_category_list_sid', $category_sid)
                                ->get('timeoff_categories')
                                ->row_array();

                //
                if (empty($is_time_off_category_exist)) {
                    $insert_time_off_category = array();
                    //
                    $insert_time_off_category['company_sid'] = $company_sid['company_sid'];
                    $insert_time_off_category['timeoff_category_list_sid'] = $category_sid;
                    $insert_time_off_category['creator_sid'] = $creator_sid;
                    $insert_time_off_category['status'] = 1;
                    $insert_time_off_category['is_archived'] = 0;
                    $insert_time_off_category['sort_order'] = 1;
                    $insert_time_off_category['default_type'] = 1;

                    $time_off_category_sid = $this->insertCategory($insert_time_off_category);
                    
                } else { 
                    $time_off_category_sid = $is_time_off_category_exist['sid'];
                    //
                    $update_time_off_category = array();
                    $update_time_off_category['is_archived'] = 0;
                    $update_time_off_category['default_type'] = 1;
                    //
                    $this->db
                        ->where('sid', $time_off_category_sid)
                        ->update('timeoff_categories', $update_time_off_category);
                }

                $is_polict_exist = $this->db
                                        ->select('*')
                                        ->where('company_sid', $company_sid['company_sid'])
                                        ->where('type_sid', $time_off_category_sid)
                                        ->get('timeoff_policies')
                                        ->row_array();   
                //
                //
                if (empty($is_polict_exist)) {
                    //
                    $insert_time_off_policy = array();
                    //
                    $insert_time_off_policy['company_sid']          = $company_sid['company_sid'];
                    $insert_time_off_policy['type_sid']             = $time_off_category_sid;
                    $insert_time_off_policy['creator_sid']          = $creator_sid;
                    $insert_time_off_policy['title']                = 'Unpaid';
                    $insert_time_off_policy['assigned_employees']   = 'all';
                    $insert_time_off_policy['note']                 = NULL;
                    $insert_time_off_policy['is_default']           = 0;
                    $insert_time_off_policy['for_admin']            = 0;
                    $insert_time_off_policy['is_archived']          = 0;
                    $insert_time_off_policy['is_included']          = 1;
                    $insert_time_off_policy['is_unlimited']         = 1;
                    $insert_time_off_policy['creator_type']         = 'employee';
                    $insert_time_off_policy['status']               = 1;
                    $insert_time_off_policy['sort_order']           = 1;
                    $insert_time_off_policy['fmla_range']           = NULL;
                    $insert_time_off_policy['accruals']             = json_encode($accruals);
                    $insert_time_off_policy['policy_start_date']    = NULL;
                    $insert_time_off_policy['reset_policy']         = $insert_time_off_policy['accruals'];
                    $insert_time_off_policy['default_policy']       = 1;
                    //
                    $this->db->insert('timeoff_policies', $insert_time_off_policy);
                    //
                } else { 
                    //
                    $time_off_policy_sid = $is_polict_exist['sid'];
                    //
                    $time_off_default_policy = $is_polict_exist['default_policy'];
                    //
                    if ($time_off_default_policy == 0) {
                        //
                        $update_time_off_policy = array();
                        //
                        $update_time_off_policy['company_sid']          = $company_sid['company_sid'];
                        $update_time_off_policy['type_sid']             = $time_off_category_sid;
                        $update_time_off_policy['creator_sid']          = $creator_sid;
                        $update_time_off_policy['title']                = 'Unpaid';
                        $update_time_off_policy['assigned_employees']   = 'all';
                        $update_time_off_policy['note']                 = NULL;
                        $update_time_off_policy['is_default']           = 0;
                        $update_time_off_policy['for_admin']            = 0;
                        $update_time_off_policy['is_archived']          = 0;
                        $update_time_off_policy['is_unlimited']         = 1;
                        $update_time_off_policy['creator_type']         = 'employee';
                        $update_time_off_policy['status']               = 1;
                        $update_time_off_policy['sort_order']           = 1;
                        $update_time_off_policy['fmla_range']           = NULL;
                        $update_time_off_policy['policy_start_date']    = NULL;
                        $update_time_off_policy['is_included']          = 1;
                        $update_time_off_policy['accruals']             = json_encode($accruals);
                        $update_time_off_policy['reset_policy']         = $update_time_off_policy['accruals'];
                        $update_time_off_policy['default_policy']       = 1;
                        //
                        $this->db
                            ->where('sid', $time_off_policy_sid)
                            ->update('timeoff_policies', $update_time_off_policy);
                        // 
                        unset($is_polict_exist['sid']);
                        $is_polict_exist['policy_sid'] = $time_off_policy_sid; 
                        //
                        $this->db->insert('timeoff_policy_history', $is_polict_exist);
                        //  
                    }
                }                          
            }                       
            //
            die('finish script');
            //
        } else {
            die('category not exist');
        }

    }

    //
    function getTimeOffRequests(
        $companySid,
        $employeeId,
        $accessOBJ = []
    ) {
        //
        $r = array();
        //
        $ids = [];
        //
        if($accessOBJ['access_level_plus'] != 1 && $accessOBJ['pay_plan_flag'] != 1){
            $ids = $this->getEmployeeTeamMemberIds($employeeId);
            //
            if(empty($ids)) return [
                'TodaysCount' => 0,
                'TotalCount' => 0
            ];
        }

        $this->db
        ->select('sid')
        ->where('company_sid', $companySid)
        ->where('status', 'pending')
        ->where('created_at >= ', date('Y-m-d 00:00:00', strtotime('now')))
        ->where('created_at <= ', date('Y-m-d 23:59:59', strtotime('now')));
        //
        if(!empty($ids)) $this->db->where_in('employee_sid', $ids);
        //
        $r['TodaysCount'] = $this->db->count_all_results('timeoff_requests');
        
        $this->db
        ->select('sid')
        ->where('company_sid', $companySid)
        ->where('status', 'pending');
        //
        if(!empty($ids)) $this->db->where_in('employee_sid', $ids);
        //
        $r['TotalCount'] = $this->db->count_all_results('timeoff_requests');
        //
        return $r;
    }

    /**
     * Get employee policies
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeePoliciesByEmployeeId(
        $companyId,
        $employeeId
    ){
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return [];
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = !empty($employee['joined_at']) ? $employee['joined_at'] : $employee['registration_date'];
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach($policies as $policy){
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'Default' => $policy['default_policy'],
                'Title' => $policy['title'],
                'RemainingTime' => 0,
                'Implements' => true,
                'Reason' => ''
            ];
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) $balance['Implements'] = false;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug)['text'];
            $balance['EmployementStatus'] = $t['EmployementStatus'];
            $balance['IsUnlimited'] = $t['IsUnlimited'];
            $balance['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $balance['Reason'] = $t['Reason'];
            //
            $r[] = $balance;
        }
        //
        return $r;
        
    }


    //
    function updateEmployeePolicies(
        $companyId,
        $employerId,
        $policyIds
    ){
        //
        $policies = $this->getCompanyPoliciesWithAccruals($companyId, true);
        //
        if(empty($policies)) return false;
        //
        foreach(
            $policies as $policy
        ){
            //
            if($policy['default_policy'] == 1) continue;
            //
            $assignedemployees = explode(',', $policy['assigned_employees']);
            //
            if(!in_array($policy['sid'], $policyIds)){
                //
                if(!in_array($employerId, $assignedemployees)){
                    //
                    $assignedemployees[] = $employerId;
                    //
                    $this->db
                    ->where('sid', $policy['sid'])
                    ->update(
                        'timeoff_policies', [
                            'assigned_employees' => implode(
                                ',',
                                $assignedemployees
                            )
                        ]
                    );
                }
            } else{
                //
                if(in_array($employerId, $assignedemployees)){
                    //
                    $index = array_search($employerId, $assignedemployees);
                    //
                    unset($assignedemployees[$index]);
                    //
                    $this->db
                    ->where('sid', $policy['sid'])
                    ->update(
                        'timeoff_policies', [
                            'assigned_employees' => implode(
                                ',',
                                $assignedemployees
                            )
                        ]
                    );
                }
            }
        }
    }

    //
    function movePolicies(
        $companyId
    ){
        //
        $a = $this->db
        ->select('
            timeoff_policies.*,
            timeoff_policy_accural.*,
            timeoff_policies.sid as policyId
        ')
        ->join('timeoff_policy_accural', 'timeoff_policy_accural.timeoff_policy_sid = timeoff_policies.sid', 'inner')
        // ->where('timeoff_policies.company_sid', $companyId)
        ->order_by('timeoff_policies.sid', 'DESC')
        ->get('timeoff_policies');
        //
        $policies = $a->result_array();
        $a = $a->free_result();
        //
        if(!empty($policies)){
            foreach($policies as $policy){
                //
                $newPolicy = [];
                //
                $accruals = [];
                $accruals['method'] = 'days_per_year';
                $accruals['time'] = 'none';
                $accruals['frequency'] = 'none';
                $accruals['frequencyVal'] = 0;
                $accruals['rate'] = 0;
                $accruals['rateType'] = 'days';
                $accruals['applicableTime'] = 0;
                $accruals['applicableTimeType'] = 'hours';
                $accruals['carryOverCheck'] = 'no';
                $accruals['carryOverType'] = 'days';
                $accruals['carryOverVal'] = 0;
                $accruals['negativeBalanceCheck'] = 'no';
                $accruals['negativeBalanceType'] = 'days';
                $accruals['negativeBalanceVal'] = 0;
                $accruals['applicableDate'] = 0;
                $accruals['applicableDateType'] = 'hireDate';
                $accruals['resetDate'] = '01-01-2020';
                $accruals['resetDateType'] = 'applicableDate';
                $accruals['newHireTime'] = 0;
                $accruals['newHireTimeType'] = 'hours';
                $accruals['newHireRate'] = 0;
                $accruals['plans'] = [];
                // Fetch type sids
                $policyPlans = $this->db
                ->select('timeoff_category_sid')
                ->where('timeoff_policy_sid', $policy['policyId'])
                ->get('timeoff_policy_categories')
                ->result_array();
                //
                if(!empty($policyPlans)) $newPolicy['type_sid'] = array_column($policyPlans, 'timeoff_category_sid')[0];
                // Fetch awards
                $policyAwards = $this->db
                ->where('timeoff_policy_sid', $policy['policyId'])
                ->get('timeoff_policy_plans')
                ->result_array();
                //
                if(!empty($policyAwards)) {
                    foreach($policyAwards as $award){
                        $accruals['plans'] = [
                            'accrualType' => $award['plan_title'],
                            'accrualRate' => $award['accrual_rate']
                        ];
                    }
                }
                //
                $accruals['method'] = $policy['accrual_method'];
                $accruals['time'] = empty($policy['accrual_time']) ? 'none' : $policy['accrual_time'];
                // New Arruals
                $accruals['rate'] = $policy['accrual_rate'];
                $accruals['applicableTime'] = $policy['minimum_applicable_hours'];
                //
                if(empty($policy['accrual_frequency'])) $accruals['accrual_frequency'] = 'none';
                else if($policy['accrual_frequency'] == 'monthly') $accruals['accrual_frequency'] = 'yearly';
                //
                $accruals['carryOverCheck'] = $policy['carryover_cap_check'] == 1 ? 'on' : 'off';
                $accruals['carryOverVal'] = $policy['carryover_cap'];
                //
                $accruals['negativeBalanceCheck'] = $policy['allow_negative_balance'] == 1 ? 'on' : 'off';
                $accruals['negativeBalanceVal'] = $policy['negative_balance'];
                //
                $accruals['negativeBalanceCheck'] = $policy['allow_negative_balance'] == 1 ? 'on' : 'off';
                $accruals['negativeBalanceVal'] = $policy['negative_balance'];
                // Lets handle unlimited
                if($policy['accrual_method'] == 'unlimited' || $policy['accrual_rate'] == 0){
                    $accruals['rate'] = 0;
                } else if($policy['accrual_method'] == 'hours_per_month'){
                    $accruals['rateType'] = 'total_hours';
                }
                //
                $accruals['newHireTime'] = $policy['new_hire_days'];
                $accruals['newHireTimeType'] = 'hours';
                $accruals['newHireRate'] = empty($policy['newhire_prorate']) ? 0 : $policy['newhire_prorate'];
                //
                $newPolicy['accruals'] = json_encode($accruals);
                $newPolicy['reset_policy'] = json_encode($accruals);
                //
                $this->db->where('sid', $policy['sid'])
                ->update('timeoff_policies', $newPolicy);
            }
        }
    }

    //
    function adjustRequests(){
        $requests = $this->db
        ->select('timeoff_days, sid')
        ->get('timeoff_requests')
        ->result_array();
        //
        if(empty($requests)) return false;
        //
        foreach($requests as $request){
            //
            if(empty($request['timeoff_days'])) continue;
            //
            $days = json_decode($request['timeoff_days'], true);
            //
            if(empty($days)) continue;
            if(isset($days['totalTime'])) continue;
            //
            $totalTime = 0;
            $newDays = [
                'totalTime' => 0,
                'days' => []
            ];
            //
            foreach($days as $day){
                //
                $totalTime += $day['time'];
                //
                $newDays['days'][] = $day;
            }
            //
            $newDays['totalTime'] = $totalTime;
            //
            $this->db
            ->where('sid', $request['sid'])
            ->update('timeoff_requests', [
                'timeoff_days' => json_encode($newDays)
            ]);
            _e($newDays, true);
        }

    }

    function getActionData ($table, $company_sid, $id) {
        
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        if ($id != 'all') {
            $this->db->where('sid', $id);
        }
        $records_obj = $this->db->get($table);
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function getCompanyDomainName ($company_sid) {

        $this->db->select('sub_domain');
        $this->db->where('user_sid', $company_sid);
        $records_obj = $this->db->get('portal_employer');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        $return_data = '';

        if (!empty($records_arr)) {
            $return_data = $records_arr['sub_domain'];
        }

        return $return_data;
    }

    //
    private function isTeamMember($employeeSid, $employerSid){
        return $this->db
        ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
        ->where('departments_team_management.status', 1)
        ->where('departments_team_management.is_deleted', 0)
        ->where('departments_management.status', 1)
        ->where('departments_management.is_deleted', 0)
        ->group_start()
        ->where("FIND_IN_SET($employerSid, departments_management.supervisor)> 0", NULL, FALSE)
        ->or_where("FIND_IN_SET($employerSid, departments_team_management.team_lead)> 0", NULL, FALSE)
        ->group_end()
        ->where('departments_employee_2_team.employee_sid', $employeeSid)
        ->count_all_results('departments_employee_2_team');
    }
    
    //
    private function isColleague($employeeSid, $employerSid){
        $employerTeams = 
        $this->db
        ->select('departments_team_management.sid')
        ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        ->where('departments_team_management.status', 1)
        ->where('departments_team_management.is_deleted', 0)
        ->where('departments_employee_2_team.employee_sid', $employerSid)
        ->get('departments_employee_2_team')
        ->result_array();
        //
        if(empty($employerTeams)) return 0;
        //
        return $this->db
        ->where('departments_employee_2_team.employee_sid', $employeeSid)
        ->where_in('departments_employee_2_team.team_sid', array_column($employerTeams, 'sid'))
        ->count_all_results('departments_employee_2_team');

    }

    /**
     * Fetch plans list by company sid
     * @param Integer $companySid
     * @param Bool    $doExplode Optional
     * @return Array
     */
    function getTimeOffDays($companySid, $doExplode = TRUE)
    {
        //
        $result = $this->db
            ->select('timeoff_settings.off_days')
            ->from('timeoff_settings')
            ->where('timeoff_settings.company_sid', $companySid)
            ->get();
        //
        $b = $result->row_array();
        $result = $result->free_result();
        //
        return sizeof($b) ? explode(',', $b['off_days']) : $b;
    }

    /**
     * Fetch plans list by company sid
     * @param Integer $companySid
     * @param Bool    $doExplode Optional
     * @return Array
     */
    function getTimeOffFormat($companySid, $doExplode = TRUE)
    {
        //
        $result = $this->db
            ->select('timeoff_formats.slug')
            ->from('timeoff_settings')
            ->join('timeoff_formats', 'timeoff_settings.timeoff_format_sid = timeoff_formats.sid', 'left')
            ->where('timeoff_settings.company_sid', $companySid)
            ->get();
        //
        $format = $result->row_array();
        $result = $result->free_result();
        //
        if ($doExplode === false) return $format;
        return !sizeof($format) ? array('H', 'M') : explode(':', $format['slug']);
    }

    //
    function getDistinctHolidayDates($post)
    {
        // Update PTO Group
        $a = $this->db
            ->select('
          date_format(from_date, "%m-%d-%Y") as from_date,
          holiday_title,
          date_format(to_date, "%m-%d-%Y") as to_date,
          DATEDIFF(to_date, from_date) as diff,
          work_on_holiday
          ')
            ->where('company_sid', $post['companySid'])
            ->where('is_archived', 0)
            ->get('timeoff_holidays');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    
    function getIncomingRequestByPermForCalendar(
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
        $shift_start_time = "09:00";
        if ($week_start > $week_end) {
            // $year = date('Y', strtotime($year . '-01-01 -1 year'));
        }
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
        $r = array();
        //
        $this->db->select("
            timeoff_requests.sid as requestId,
            timeoff_requests.timeoff_policy_sid as policyId,
            timeoff_requests.employee_sid,
            timeoff_requests.allowed_timeoff,
            timeoff_requests.requested_time,
            timeoff_requests.request_to_date,
            timeoff_requests.request_from_date as requested_date,
            timeoff_requests.status,
            timeoff_requests.reason,
            timeoff_policies.title as policy_title
        ")
            ->from('timeoff_requests')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->order_by('requested_date', 'ASC')
            ->order_by('status', 'DESC');
        //
        if ($startDate != '' && $startDate != 'all' ) $this->db->where('timeoff_requests.request_from_date >= "' . ($startDate) . '"', null);
        if ($endDate != '' && $endDate != 'all' ) $this->db->where('timeoff_requests.request_to_date <= "' . ($endDate) . '"', null);
        $this->db->group_start();
        $this->db->where('timeoff_requests.status', 'approved');
        $this->db->or_where('timeoff_requests.status', 'pending');
        $this->db->group_end();
        $this->db->where('timeoff_requests.company_sid', $company_id);
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) return $r;

        // Get company default time
        $a = $this->db
            ->select('timeoff_settings.default_timeslot, timeoff_formats.slug')
            ->from('timeoff_settings')
            ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
            ->where('company_sid', $company_id)
            ->limit(1)
            ->get();
        //
        $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
        $a->free_result();
        //
        $timeoff_requests = array();
        $asApprover = $employer_detail['access_level_plus'] == 1 || $employer_detail['pay_plan_flag'] == 1 ? 1 : 0;
        //
        foreach ($b as $k => $v) {
            //
            if( $employer_detail['access_level_plus'] == 0 && $employer_detail['pay_plan_flag'] == 0 ){
                // Check if the employee is part of team
                $isTeamMember = $this->isTeamMember($v['employee_sid'], $employer_id);
                $isColleague = $this->isColleague($v['employee_sid'], $employer_id);
                $isSame = $v['employee_sid'] != $employer_id ? 0 : 1;
                //
                if($isColleague) $asApprover = 2;
                if($isSame) $asApprover = 1;
                //
                if($isTeamMember == 0 && $isColleague == 0 && $isSame == 0){
                    unset($b[$k]);
                    continue;
                }
            }

            // Fetch employee joining date
            $a = $this->db
                ->select('
                joined_at, 
                user_shift_hours, 
                user_shift_minutes,
                concat(first_name," ",last_name) as full_name,
                profile_picture as img,
                employee_number
            ')
                ->from('users')
                ->where('sid', $v['employee_sid'])
                ->limit(1)
                ->get();
            //
            $joinedAt = isset($a->row_array()['joined_at']) ? $a->row_array()['joined_at'] : null;
            $employeeShiftHours = isset($a->row_array()['user_shift_hours']) ? $a->row_array()['user_shift_hours'] : PTO_DEFAULT_SLOT;
            $employeeShiftMinutes = isset($a->row_array()['user_shift_minutes']) ? $a->row_array()['user_shift_minutes'] : PTO_DEFAULT_SLOT_MINUTES;
            //
            $full_name = $a->row_array()['full_name'];
            $img = $a->row_array()['img'];
            $employee_number = $a->row_array()['employee_number'];
            // $b[$k]['img'] = $a->row_array()['img'];
            // $b[$k]['employee_number'] = $a->row_array()['employee_number'];

            //
            $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
            $a->free_result();

            //
            $timeoff_breakdown = get_array_from_minutes(
                $v['requested_time'],
                $defaultTimeFrame,
                $slug
            );

            // Sort by policy
            if (!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
            $start_datetime = $v['requested_date'] . "T" . $shift_start_time;
            $end_datetime = $v['request_to_date'] . "T" . $shift_start_time;
            // $time = new \DateTime($start_datetime);
            // $time->add(new \DateInterval('PT' . $v['requested_time'] . 'M'));
            // $end_datetime = $time->format('Y-m-dTH:i');
            $timeoff_requests[] = [
                'title' =>  $full_name . " - " . ucwords($v['policy_title']) . " - " . $timeoff_breakdown['text'],
                'start' =>  $start_datetime,
                'end' =>  $end_datetime,
                'color' => "#5cb85c",
                'date' => $v['requested_date'],
                'status' => "confirmed",
                'timeoff_status' => ucwords($v['status']),
                'type' => 'timeoff',
                'requests' => 0,
                'timeoff_breakdown' => $timeoff_breakdown,
                'img' => $img,
                'approver' => $asApprover,
                'employee_name' => $full_name,
                'employee_number' => $employee_number,
                'reason' => $v['reason'],
                'policy' => $v['policy_title'],
                'from_date' => $v['requested_date'],
                'to_date' => $v['request_to_date'],
                'request_id' => $v['requestId'],
            ];
        }
        // _e('sdas', true, true);
        //
        return $timeoff_requests;
    }

    
    function getCompanyPublicHolidays(
        $type,
        $year,
        $month,
        $day,
        $week_start,
        $week_end,
        $company_id
    ) {
        if ($week_start > $week_end) {
            // $year = date('Y', strtotime($year . '-01-01 -1 year'));
        }
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
        $a = $this->db
            ->select('
            sid as event_id,
            from_date as date,
            icon,
            "1" as allDay,
            concat(from_date," 23:59:59") as end,
            concat(from_date," 00:00:00") as start,
            "#111111" as color,
            "holidays" as status,
            "0" as requests,
            holiday_title as title,
            "holidays" as type,
            "holidays" as category
        ')
            ->where("is_archived", 0)
            ->where("from_date BETWEEN '$startDate' AND '$endDate'", null)
            ->where("company_sid", $company_id)
            ->get('timeoff_holidays');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        return $b;
    }

    //
    function shiftApprovers(){
        //
        $list = $this->db
        ->select('
            timeoff_request_assignment.timeoff_request_sid,
            timeoff_request_assignment.employee_sid,
            timeoff_request_history.status,
            timeoff_request_history.reason,
            timeoff_request_history.created_at
        ')
        ->join('timeoff_request_assignment', 'timeoff_request_assignment.sid = timeoff_request_history.timeoff_request_assignment_sid', 'inner')
        ->get('timeoff_request_history')
        ->result_array();
        //
        foreach($list as $his){
            //
            $ins = [];
            $ins['request_sid'] = $his['timeoff_request_sid'];
            $ins['employee_sid'] = $his['employee_sid'];
            $ins['action'] = 'update';
            $ins['note'] = json_encode(['status' => $his['status'], 'canApprove' => $this->getEmployerApprovalStatus($his['employee_sid']), 'comment' => $his['reason']]);
            $ins['created_at'] = $his['created_at'];
            $ins['is_moved'] = 1;
            $ins['comment'] = empty($his['reason']) ? '' : $his['reason'];
            //
            $this->insertHistory( $ins, 'timeoff_request_timeline' );
        }
    }

    
    function getCompanyAllHolidays(
        $post
    ) {
        //
        $this->db
            ->select('
           timeoff_holidays.holiday_title,
           timeoff_holidays.icon,
           timeoff_holidays.from_date,
           timeoff_holidays.to_date
       ')
            ->from('timeoff_holidays')
            ->where('timeoff_holidays.is_archived', 0)
            ->where('timeoff_holidays.holiday_year', date('Y'))
            ->where('timeoff_holidays.company_sid', $post['companyId'])
            ->order_by('timeoff_holidays.from_date', 'ASC');
        //
        $result = $this->db->get();
        $holidays = $result->result_array();
        $result  = $result->free_result();

        return $holidays;
    }


    //
    function shiftHolidays(){
        //
        $b = $this->db
        ->where('holiday_year', '2020')
        ->get('timeoff_holidays')
        ->result_array();
        //
        //
        if(empty($b)) return;
        $this->db
        ->where('holiday_year', '2021')
        ->delete('timeoff_holidays');
        //
        foreach($b as $holiday){
            //
            $newHoliday = $holiday;
            //
            unset(
                $newHoliday['sid'],
                $newHoliday['created_at'],
                $newHoliday['updated_at']
            );
            //
            $newHoliday['from_date'] = '2021'.(substr($newHoliday['from_date'], 4));
            $newHoliday['to_date'] = '2021'.(substr($newHoliday['to_date'], 4));
            $newHoliday['holiday_year'] = '2021';
            //
            $this->db->insert('timeoff_holidays', $newHoliday);
        }
    }

    //
    function getPolicyColumn(
        $column = '*',
        $policyId
    ){
        return $this->db->select($column)
        ->where('sid', $policyId)
        ->get('timeoff_policies')
        ->row_array();
    }

    //
    function getEmployeeIdByemail(
        $companyId,
        $email
    ){
        $b = $this->db
        ->select('sid')
        ->where('parent_sid', $companyId)
        ->where('email', $email)
        ->get('users')
        ->row_array();
        //
        return isset($b['sid']) ? $b['sid'] : 0; 
    }

    //
    function getPolicyIdByName(
        $companyId,
        $title
    ){
        $b = $this->db
        ->select('sid')
        ->where('company_sid', $companyId)
        ->where('title', $title)
        ->get('timeoff_policies')
        ->row_array();
        //
        return isset($b['sid']) ? $b['sid'] : 0; 
    }

    /**
     * Get employee policies
     * 
     * @employee  Mubashir Ahmed
     * @date      01/05/2021
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeePoliciesByIdWithTimeoffs(
        $companyId,
        $employeeId
    ){
        $this->db->select('
            '.( getUserFields() ).'
            joined_at,
            registration_date,
            employee_status,
            employee_type
        ')
        ->order_by('first_name', 'ASC')
        ->where('parent_sid', $companyId)
        ->where('sid', $employeeId)
        ->where('active', 1)
        ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return [];
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = !empty($employee['joined_at']) ? $employee['joined_at'] : $employee['registration_date'];
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach($policies as $policy){
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'Title' => $policy['title'],
                'UserId' => $employeeId,
                'AllowedTime' => 0,
                'ConsumedTime' => 0,
                'RemainingTime' => 0,
                'Approved' => 0,
                'IsUnlimited' => 0,
                'Pending' => 0,
                'Plans' => [],
                'Category' => $policy['category_name'],
                'Reason' => ''
            ];
            //
            if($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if(!isset($accruals['employeeTypes'])) continue;
            if(!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId.'-'.$policy['sid']]) ? $balances[$employeeId.'-'.$policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            $balance['AllowedTime'] = get_array_from_minutes($t['AllowedTime'], $durationInHours, $slug);
            $balance['ConsumedTime'] = get_array_from_minutes($t['ConsumedTime'], $durationInHours, $slug);
            $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug);
            $balance['EmployementStatus'] = $t['EmployementStatus'];
            $balance['IsUnlimited'] = $t['IsUnlimited'];
            $balance['EmployeeJoinedAt'] = $t['EmployeeJoinedAt'];
            $balance['Plans'] = $t['Plans'];
            $balance['Reason'] = $t['Reason'];
            //
            $balance['Approved'] = $this->db
            ->select('status, timeoff_policy_sid')
            ->from('timeoff_requests')
            ->where('timeoff_policy_sid', $balance['PolicyId'])
            ->where('employee_sid', $employeeId)
            ->where('status', 'approved')
            ->where('is_draft', 0)
            ->where('archive', 0)
            ->where("request_from_date >= ", date('Y-01-01'))
            ->where("request_from_date <=", date('Y-12-31'))
            ->count_all_results();
            $balance['Pending'] = $this->db
            ->select('status, timeoff_policy_sid')
            ->from('timeoff_requests')
            ->where('timeoff_policy_sid', $balance['PolicyId'])
            ->where('employee_sid', $employeeId)
            ->where('status', 'pending')
            ->where('archive', 0)
            ->where('is_draft', 0)
            ->where("request_from_date >= ", date('Y-01-01'))
            ->where("request_from_date <= ", date('Y-12-31'))
            ->count_all_results();
            //
            $r[] = $balance;
            
        }
        //
        return $r;
    }

    //
    function getRequestsByStatus(
        $post
    ){
        //
        $this->db
        ->select('
            timeoff_requests.sid,
            timeoff_requests.requested_time,
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.status,
            timeoff_requests.level_status,
            timeoff_requests.reason,
            timeoff_requests.created_at,
            users.user_shift_hours,
            users.user_shift_minutes,
            timeoff_policies.title
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
        ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
        ->where('timeoff_policies.is_archived', 0)
        ->where('timeoff_requests.company_sid', $post['companyId']);
        $this->db->where('timeoff_requests.archive', 0);
        $this->db->where('timeoff_requests.status', $post['status']);

        //
        $this->db->where('timeoff_requests.employee_sid', $post['employeeId']);
        $this->db->where('timeoff_requests.timeoff_policy_sid', $post['policyId']);
        $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        $this->db->where('timeoff_requests.request_from_date >= "'.(date('Y')).'-01-01"', null);
        $this->db->where('timeoff_requests.request_from_date <= "'.(date('Y')).'-12-31"', null);
        //
        $requests = $this->db
        ->get('timeoff_requests')
        ->result_array();
        //
        if(!empty($requests)){
            //
            $settings = $this->getSettings($post['companyId']);
            //
            foreach($requests as $k => $request){
                $requests[$k]['breakdown'] = get_array_from_minutes(
                    $request['requested_time'],
                    (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
                    $settings['slug']
                );
                $requests[$k]['history'] = $this->timeoff_model->getHistory(
                    'request_sid',
                    $request['sid'],
                    'timeoff_request_timeline'
                );
            }
        }
        //
        return $requests;
    }

    //
    function getTodayOffEmployees($post){
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        //
        $notIds = [];
        //
        if($ses['access_level_plus'] == 0 && $ses['pay_plan_flag'] == 0){
            $notIds = $this->getEmployeeTeamMemberIds($post['employerId']);
            //
            if(empty($notIds)) return [];
        }
        //
        $this->db
        ->distinct()
        ->select('
            '.( getUserFields()).'
        ')
        ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
        ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
        ->where('timeoff_policies.is_archived', 0)
        ->where('timeoff_requests.archive', 0)
        ->where('timeoff_requests.status', 'approved')
        ->where('timeoff_requests.company_sid', $post['companyId']);
        //
        $this->db->where('timeoff_requests.archive', 0);
        $this->db->where('timeoff_requests.request_from_date', 'CURDATE()', false);
        //
        if(!empty($notIds)) $this->db->where_in('timeoff_requests.employee_sid', $notIds);
        //
        return $this->db->get('timeoff_requests')->result_array();
    }


    /**
     * Get company types
     * 
     * @employee Mubashir Ahmed
     * @date     01/13/2021
     * 
     * 
     * @return Array
     */
    function getTypesListByCompanyForAdmin(){
        // Fetch all polcies
        $policies = $this->db
        ->select('type_sid, title')
        ->from('timeoff_policies')
        ->where('company_sid', 0)
        ->order_by('sort_order', 'ASC')
        ->get()
        ->result_array();
        //
        if(!empty($policies)){
            //
            $tmp = [];
            //
            foreach($policies as $policy) {
                //
                if(!isset($tmp[$policy['type_sid']])) $tmp[$policy['type_sid']] = [];
                //
                $tmp[$policy['type_sid']][] = $policy['title'];
            }
            //
            $policies = $tmp;
            //
            unset($tmp);
        }
        //
        //
        $this->db
        ->select('
            timeoff_category_list.category_name as type_title,
            timeoff_categories.*
        ')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->where('timeoff_categories.company_sid', 0)
        ->order_by('timeoff_categories.sort_order', 'ASC');
        //
        $result = $this->db->get('timeoff_categories');
        $types = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($types)) return array();
        //
        foreach($types as $k => $v) $types[$k]['policies'] = isset($policies[$v['sid']]) ? $policies[$v['sid']] : null;
        //
        return $types;
    }

    public function get_all_timeoff_icons () {
    	$this->db->select('*');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('timeoff_policy_icons_info')->result_array();
    }

    public function change_time_off_icon_info_content ($icon_id, $data_to_update) {
        $this->db->where('sid', $icon_id);
        $this->db->update('timeoff_policy_icons_info', $data_to_update);
    }

    /**
     * Fetch policy list for admin 
     * 
     * @employee Mubashir Ahmed
     * @date     01/13/2021
     * 
     * @return Array
     */
    function getPoliciesForAdmin(){
        //
        $this->db
            ->select('
                timeoff_policies.*, 
                timeoff_category_list.category_name as category
            ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'left')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->from('timeoff_policies')
            ->where('timeoff_policies.company_sid', 0)
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        $result = $this->db->get();
        //
        $policies = $result->result_array();
        $result = $result->free_result();
        //
        return $policies;
    }
    
    
    /**
     * Fetch policy with companies
     * 
     * @employee Mubashir Ahmed
     * @date     01/13/2021
     * 
     * @return Array
     */
    function getPoliciesWithCompanies(){
        //
        $cPolicies = [];
        // Fetch admin policies
        $policies =
        $this->db
        ->select('title, company_sid')
        ->from('timeoff_policies')
        ->where('timeoff_policies.company_sid <>', 0)
        ->get()
        ->result_array();
        //
        if(!empty($policies)){
            foreach($policies as $policy){
                //
                $slug = $policy['company_sid'];
                //
                if(!isset($cPolicies[$slug])) $cPolicies[$slug] = [];
                //
                $cPolicies[$slug][] = $policy;
            }
        }
        //
        $returnArray = [
            'Companies' => [],
            'Policies' => []
        ];
        // Fetch companies
        $cp =
        $this->db
        ->select('sid, CompanyName')
        ->from('users')
        ->where('is_paid', 1)
        ->where('active', 1)
        ->where('parent_sid', 0)
        ->order_by('CompanyName', 'ASC')
        ->get()
        ->result_array();
        //
        if(!empty($cp)){
            foreach($cp as $k => $company){
                $returnArray['Companies'][$company['sid']] = $company;
                $returnArray['Companies'][$company['sid']]['Policies'] = isset($cPolicies[$company['sid']]) ? $cPolicies[$company['sid']] : [];
            }
        } else $returnArray['Companies'] = [];
        // Fetch admin policies
        $returnArray['Policies'] =
        $this->db
        ->select('sid, title')
        ->from('timeoff_policies')
        ->where('timeoff_policies.company_sid', 0)
        ->order_by('timeoff_policies.sort_order', 'ASC')
        ->get()
        ->result_array();
        //
        return $returnArray;
    }


    //
    function getAdminPoliciesByIds($ids){
        return 
        $this->db
        ->select('
            timeoff_policies.*,
            timeoff_category_list.category_name as category,
            timeoff_category_list.sid as category_sid
        ')
        ->from('timeoff_policies')
        ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'left')
        ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
        ->where_in('timeoff_policies.sid', $ids)
        ->get()
        ->result_array();
    }

    function assignAdminPoliciesToCompany ($insert_time_off_policy) {
        $title = $insert_time_off_policy['title'];
        $company_sid = $insert_time_off_policy['company_sid'];

        $policy_exist = $this->db
        ->select('sid')
        ->from('timeoff_policies')
        ->where('company_sid', $company_sid)
        ->where('title', $title)
        ->get()
        ->result_array();

        if(empty($policy_exist)){
            $this->db->insert('timeoff_policies', $insert_time_off_policy);
        }    
    }

    function checkCategoryExistAgainstCompany ($category_sid, $company_sid) {
        $cp = $this->db
        ->select('sid')
        ->from('timeoff_categories')
        ->where('company_sid', $company_sid)
        ->where('timeoff_category_list_sid', $category_sid)
        ->get()
        ->row_array();
        //
        if(empty($cp)){
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['timeoff_category_list_sid'] = $category_sid;
            $data_to_insert['creator_sid'] = 0;
            $data_to_insert['is_default'] = 1;
            $data_to_insert['default_type'] = 1;

            $this->db->insert('timeoff_categories', $data_to_insert);
            return $this->db->insert_id();
        } else{
            return $cp['sid'];
        }
    }


    //
    function setAdminPolicyStatus(
        $status,
        $id
    ){
        $this->db
        ->where('sid', $id)
        ->update(
            'timeoff_policies', [
                'is_archived' => $status
            ]
        );
    }

    function updateTypeSort($a){
        $this->db
            ->where('sid', $a['id'])
            ->update('timeoff_categories', array('sort_order' => $a['sort']));
    }

    
    function updatePoliciesSort($a)
    {
        $this->db
            ->where('sid', $a['id'])
            ->update('timeoff_policies', array('sort_order' => $a['sort']));
    }

    // Created By: Aleem Shaukat
    // Created On: 03-02-2021
    function getCompanyTheme ($company_sid) {
        $theme_id = $this->db
        ->select('theme')
        ->from('timeoff_settings')
        ->where('company_sid', $company_sid)
        ->get()
        ->row_array();
        //
        if(!empty($theme_id)){
            return $theme_id['theme'];
        } else{
            return 1;
        }
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

}