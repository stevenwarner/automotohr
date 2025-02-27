<?php

use function PHPSTORM_META\type;

class Timeoff_model extends CI_Model
{
    private $ids;
    function __construct($ids = array())
    {
        parent::__construct();
        $this->ids = $ids;
        //
        $this->load->helper('timeoff');
    }
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
    function getCompanyTypesList($post)
    {
        $a = $this->db
            ->select('
            timeoff_categories.sid as type_id,
            timeoff_categories.category_type,
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
    function getPoliciesListByCompany($companySid, $type = 'specific')
    {
        //
        $this->db
            ->select('
                timeoff_policies.sid as policy_id, 
                timeoff_policies.title as policy_title,
                timeoff_category_list.category_name as category,
                timeoff_policies.policy_category_type as category_type
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
    function getCompanyEmployees($companySid, $employerId = false, int $employeeStatus = 0)
    {
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        $ids = [];
        //
        $this->db
            ->select('sid as user_id, 
            employee_number, 
            email, 
            first_name, 
            timezone, 
            last_name, 
            job_title, 
            is_executive_admin, 
            concat(first_name," ",last_name) as full_name, 
            profile_picture as image, 
            access_level,
            access_level_plus,
            pay_plan_flag,
            joined_at,
            registration_date,
            rehire_date,
            terminated_status,
            active,
        ')
            ->from('users')
            ->where('parent_sid', $companySid)
            ->where('is_executive_admin', 0)
            ->order_by('first_name', 'ASC');
        // include terminated and inactive people 
        if ($employeeStatus === 0) {
            $this->db
                ->where('active', 1)
                ->where('terminated_status', 0);
        }
        if (!empty($ids)) $this->db->where_in('sid', $ids);

        $result = $this->db->get();
        //
        $employees = $result->result_array();
        $result = $result->free_result();
        //
        if (!empty($employees)) {
            foreach ($employees as $index => $employee) {
                $employees[$index]['anniversary_text'] = get_user_anniversary_date(
                    $employee['joined_at'],
                    $employee['registration_date'],
                    $employee['rehire_date']
                );
            }
        }
        return $employees;
    }

    /**
     * Get company employees and executive admin
     * 
     * @employee Aleem Shaukat
     * @date     01/11/2022
     *
     * @param Integer $companySid
     * @param Integer $employerId
     * 
     * @return Array
     */
    function getCompanyAllEmployees($companySid, $employerId = false)
    {
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        $ids = [];
        //
        $this->db
            ->select('sid as user_id, 
            employee_number, 
            email, 
            first_name, 
            timezone, 
            last_name, 
            job_title, 
            is_executive_admin, 
            concat(first_name," ",last_name) as full_name, 
            profile_picture as image, 
            access_level,
            access_level_plus,
            pay_plan_flag,
            joined_at,
            registration_date,
            rehire_date
        ')
            ->from('users')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->order_by('first_name', 'ASC');
        if (!empty($ids)) $this->db->where_in('sid', $ids);

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
    ) {
        $this->db
            ->from('timeoff_policies')
            ->where('title', $policyTitle)
            ->where('company_sid', $companyId);
        //
        if ($policyId !=  0) $this->db->where("sid <> $policyId", null);
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
    ) {
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
    ) {
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
    ) {
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
            timeoff_policies.created_at,
            timeoff_policies.policy_category_type as category_type
        ')
            ->from('timeoff_policies')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->where('timeoff_policies.is_archived', !empty($formpost['filter']) ? $formpost['filter']['archived'] : 0)
            ->where('timeoff_policies.company_sid', $formpost['companyId'])
            ->order_by('timeoff_policies.sort_order', 'ASC')
            ->limit($limit, $start);
        //
        if (!empty($formpost['filter'])) {
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
        if (!empty($formpost['filter'])) {
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
    function getSinglePolicyById($policyId)
    {
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
    function getPolicyHistory($policyId)
    {
        return $this->db
            ->select('
            timeoff_policy_timeline.action, 
            timeoff_policy_timeline.action_type,
            timeoff_policy_timeline.created_at,
            ' . (getUserFields()) . '
        ')
            ->join('users', 'users.sid = timeoff_policy_timeline.employee_sid', 'inner')
            ->where('timeoff_policy_timeline.policy_sid', $policyId)
            ->order_by('timeoff_policy_timeline.sid', 'DESC')
            ->get('timeoff_policy_timeline')
            ->result_array();
    }

    /**
     * Get policy requests by id
     * 
     * @employee  Aleem Shaukat
     * @date      12/05/2023
     * 
     * @param Integer $policyId
     * 
     * @return Array
     */
    function getPolicyRequests($policyId)
    {
        return $this->db
            ->select('
            request_from_date , 
            request_to_date,
            requested_time,
            employee_sid
        ')
            ->where('timeoff_policy_sid', $policyId)
            ->get('timeoff_requests')
            ->result_array();
    }

    /**
     * Get all active policy by company id
     * 
     * @employee  Aleem Shaukat
     * @date      12/05/2023
     * 
     * @param Integer $companyId
     * 
     * @return Array
     */
    function getAllActivePolicies($companyId)
    {
        $this->db
            ->select('
            sid,
            title
        ')
            ->where('company_sid', $companyId)
            ->where('is_archived', 0)
            ->order_by('sort_order', 'ASC');
        //
        $policies = $this->db->get('timeoff_policies')
            ->result_array();
        //
        return $policies;
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
    function getTypesListByCompany($post, $page, $limit)
    {
        // Fetch all polcies
        $policies = $this->db
            ->select('type_sid, title')
            ->from('timeoff_policies')
            ->where('company_sid', $post['companyId'])
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();
        //
        if (!empty($policies)) {
            //
            $tmp = [];
            //
            foreach ($policies as $policy) {
                //
                if (!isset($tmp[$policy['type_sid']])) $tmp[$policy['type_sid']] = [];
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
            timeoff_categories.category_type,
            timeoff_categories.default_type,
            timeoff_categories.created_at,
            ' . (getUserFields()) . '
        ')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->join('users', 'users.sid = timeoff_categories.creator_sid', 'inner')
            ->where('timeoff_categories.is_archived', !empty($post['filter']) ? $post['filter']['archived'] : 0)
            ->where('timeoff_categories.company_sid', $post['companyId'])
            ->limit($limit, $start)
            ->order_by('timeoff_categories.sort_order', 'ASC');
        //
        if (!empty($post['filter'])) {
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
        foreach ($types as $k => $v) $types[$k]['policies'] = isset($policies[$v['type_sid']]) ? $policies[$v['type_sid']] : null;
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
        if (!empty($post['filter'])) {
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
    ) {
        //
        $categoryArray =
            $this->db
            ->select('sid')
            ->from('timeoff_category_list')
            ->where('category_name', $typeTitle)
            ->get()
            ->row_array();
        //
        if (empty($categoryArray)) return [0, 0];
        //
        $this->db
            ->from('timeoff_categories')
            ->where('timeoff_category_list_sid', $categoryArray['sid'])
            ->where('company_sid', $companyId);
        //
        if ($typeId !=  0) $this->db->where("sid <> $typeId", null);
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
    ) {
        //
        $this->db
            ->insert(
                'timeoff_category_list',
                [
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
    ) {
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
    ) {
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
    ) {
        //
        $this->db
            ->where_in('company_sid', $companyId);
        //
        if ($policyIds != 0) $this->db->where_in('sid', $policyIds);
        //
        $this->db
            ->update(
                'timeoff_policies',
                [
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
    function updateCompanyPolicy($policySid, $updateArray)
    {
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
    function updateCompanyType($typeId, $updateArray)
    {
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
    function getTypeHistory($typeId)
    {
        return $this->db
            ->select('
            timeoff_type_timeline.action, 
            timeoff_type_timeline.created_at,
            ' . (getUserFields()) . '
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
    function getSingleType($typeId, $companyId)
    {
        $types =
            $this->db
            ->select('
            timeoff_categories.sid as type_sid, 
            timeoff_categories.is_archived,
            timeoff_categories.default_type,
            timeoff_categories.category_type,
            timeoff_category_list.category_name as type
            
        ')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_categories.sid', $typeId)
            ->where('timeoff_categories.company_sid', $companyId)
            ->get('timeoff_categories')
            ->row_array();
        //
        if (!empty($types)) {
            $types['policies'] = $this->db
                ->select('sid')
                ->where('type_sid', $typeId)
                ->get('timeoff_policies')
                ->result_array();
            //
            if (!empty($types['policies'])) {
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
    function updateCompanyHoliday($upd, $holidayId)
    {
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
    function getHolidayHistory($holidayId)
    {
        return $this->db
            ->select('
            timeoff_holiday_timeline.action, 
            timeoff_holiday_timeline.created_at,
            ' . (getUserFields()) . '
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
    function inserttHolidayHistory($insertArray)
    {
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
    function getCompanyDistinctHolidays($post)
    {
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
    function companyHolidayExists($post)
    {
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
    function insertCompanyHoliday($insertArray)
    {
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
    function getSettingsAndFormats($companyId, $formats = FALSE)
    {
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
            team_visibility_check,
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
    function updateSettings($post)
    {
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
        $dataArray['team_visibility_check'] = $post['teamVisibility'];
        $dataArray['timeoff_format_sid'] = $post['format'];
        $dataArray['off_days'] = !isset($post['offDays']) || $post['offDays'] == null ? '' : implode(',', $post['offDays']);
        $dataArray['theme'] = $post['theme'];

        // Check if default time option is set
        if ($post['forAllEmployees'] == 1) {
            $this->db
                ->where('parent_sid', $post['companyId'])
                ->update(
                    'users',
                    [
                        'user_shift_hours' => $post['defaultTimeslot']
                    ]
                );
        }
        // _e($post['offDays'],true);
        // _e($dataArray,true,true);
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
    ) {
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
    function getCompanyDepartmentsAndTeams($companyId)
    {
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
        if (count($r['Departments'])) $this->db->where_in('department_sid', array_column($r['Departments'], 'department_id'))->or_where('department_sid', 0);
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
                users.timezone,
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
            ->where('users.terminated_status', 0)
            ->where('users.active', 1)
            ->order_by('users.first_name', 'ASC')
            ->limit($limit, $start);
        // Search Filter
        if ($formpost['filter']['status'] != '' && $formpost['filter']['status'] != -1) $this->db->where('timeoff_approvers.status', $formpost['filter']['status']);
        if (!empty($formpost['filter']['departments']) && !in_array('all', $formpost['filter']['departments'])) {
            foreach ($formpost['filter']['departments'] as $k => $dt) {
                if ($k == 0) $this->db->where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
                else $this->db->or_where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
            }
        }
        if (!empty($formpost['filter']['teams']) && !in_array('all', $formpost['filter']['teams'])) {
            foreach ($formpost['filter']['teams'] as $k => $dt) {
                if ($k == 0) $this->db->where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
                else $this->db->or_where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
            }
        }
        if (isset($formpost['filter']['employees']) && $formpost['filter']['employees'] != '' && $formpost['filter']['employees'] != 'all') $this->db->where('timeoff_approvers.employee_sid', $formpost['filter']['employees']);
        if ($formpost['filter']['startDate'] != '' && $formpost['filter']['endDate']) $this->db->where('DATE_FORMAT(timeoff_approvers.created_at, "%m-%d-%Y") BETWEEN ' . ($formpost['filter']['startDate']) . ' AND ' . ($formpost['filter']['endDate']) . '');
        //
        $result = $this->db->get();
        $approvers = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($approvers)) return array();
        //
        foreach ($approvers as $k => $approver) {
            //
            $approvers[$k]['team_name'] = null;
            $approvers[$k]['department_name'] = null;
            //
            if ($approver['is_department'] == 0 && $approver['department_id'] != 'all') {
                $a = $this->db
                    ->select('departments_team_management.name')
                    ->join('departments_management', 'departments_team_management.department_sid = departments_management.sid', 'inner')
                    ->where_in('departments_team_management.sid', explode(',', $approver['department_id']))
                    ->where('departments_team_management.is_deleted', 0)
                    ->where('departments_management.is_deleted', 0)
                    ->where('departments_management.company_sid', $formpost['companyId'])
                    ->order_by('departments_team_management.name', 'ASC')
                    ->get('departments_team_management');
                //
                $b = $a->result_array();
                $a = $a->free_result();
                //
                if (!$b) {
                    unset($approvers[$k]);
                    continue;
                }
                //
                $approvers[$k]['team_name'] = implode(', ', array_column($b, 'name'));
            } else if ($approver['is_department'] == 1 && $approver['department_id'] != 'all') {
                $a = $this->db
                    ->select('name')
                    ->where_in('sid', explode(',', $approver['department_id']))
                    ->where('is_deleted', 0)
                    ->where('departments_management.company_sid', $formpost['companyId'])
                    ->order_by('name', 'ASC')
                    ->get('departments_management');
                //
                $b = $a->result_array();
                $a = $a->free_result();
                //
                if (!$b) {
                    unset($approvers[$k]);
                    continue;
                }
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
        if (!empty($formpost['filter']['departments']) && !in_array('all', $formpost['filter']['departments'])) {
            foreach ($formpost['filter']['departments'] as $k => $dt) {
                if ($k == 0) $this->db->where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
                else $this->db->or_where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
            }
        }
        if (!empty($formpost['filter']['teams']) && !in_array('all', $formpost['filter']['teams'])) {
            foreach ($formpost['filter']['teams'] as $k => $dt) {
                if ($k == 0) $this->db->where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
                else $this->db->or_where('find_in_set("' . ($dt) . '", timeoff_approvers.department_sid)', false, false);
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
    ) {
        //
        $historyResults = $this->db
            ->select("
            $tbl.action, 
            $tbl.note, 
            $tbl.created_at,
            " . (getUserFields()) . ",
            users.joined_at,
            users.registration_date,
            users.rehire_date,
        ")
            ->join('users', "users.sid = $tbl.employee_sid", 'inner')
            ->where("$tbl.$cId", $id)
            ->order_by("$tbl.sid", 'DESC')
            ->get($tbl)
            ->result_array();

        if ($tbl == 'timeoff_request_timeline') {

            if (!empty($historyResults)) {
                foreach ($historyResults as $index => $historyResult) {
                    $historyResults[$index]['anniversary_text'] = get_user_anniversary_date(
                        $historyResult['joined_at'],
                        $historyResult['registration_date'],
                        $historyResult['rehire_date']
                    );
                }
            }
        }

        return $historyResults;
    }

    function fetchRequestHistoryInfo($request_sid)
    {
        $this->db->select('employee_sid ,note, action, created_at');
        $this->db->where("request_sid", $request_sid);
        $this->db->order_by('sid', "desc");
        $this->db->limit(1);
        $records_obj = $this->db->get('timeoff_request_timeline');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }
    // return $this->db->select('*')->order_by('sid',"desc")->where("request_sid", $request_sid)->limit(1)->get('timeoff_request_timeline')->row_array();

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
    ) {
        //
        $this->db
            ->where('sid', $id)
            ->update(
                $tbl,
                $upd
            );
    }

    /**
     * Update Employee Note 
     *
     * @employee  Aleem Shaukat
     * @date      07/15/2022
     * 
     * @param  Array   $data_to_update
     * @param  Integer $request_sid
     * @param  Integer  $employee_sid
     * 
     * @return VOID
     */
    function updateEmployeeNote(
        $data_to_update,
        $request_sid,
        $employee_sid
    ) {
        //
        $this->db
            ->where('request_sid', $request_sid)
            ->where('employee_sid', $employee_sid)
            ->where('action', "update")
            ->update(
                "timeoff_request_timeline",
                $data_to_update
            );
    }

    function getEmployeePreviousNote($request_sid, $employee_sid)
    {
        $this->db->select('note');
        $this->db->where("request_sid", $request_sid);
        $this->db->where('employee_sid', $employee_sid);
        $this->db->where('action', "update");
        $records_obj = $this->db->get('timeoff_request_timeline');

        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();


        if (!empty($records_arr)) {
            $return_data = json_decode($records_arr["note"], true);
        }

        return $return_data;
    }

    function getPreviousStatus($request_sid)
    {
        $this->db->select('status ,level_status');
        $this->db->where("sid", $request_sid);
        $records_obj = $this->db->get('timeoff_requests');

        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
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
    function companyApproverCheck($post)
    {
        $this->db
            ->from('timeoff_approvers')
            ->where('employee_sid', $post['employee'][0])
            ->where('is_archived', 0);
        //
        $this->db->group_start();
        //
        foreach ($post['selectedEmployees'] as $k => $dt) {
            if ($k == 0) $this->db->where('find_in_set("' . ($dt) . '", department_sid) > 0', false, false);
            else $this->db->or_where('find_in_set("' . ($dt) . '", department_sid) > 0', false, false);
        }
        $this->db->group_end();
        //
        if (isset($post['type']) && $post['type'] == 1) $this->db->where('is_department', '1');
        else if (isset($post['type']) && $post['type'] == 0) $this->db->where('is_department', '0');
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
    ) {
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
    function getCompanyApprover($formpost)
    {
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
    function getAprroverById($approverId)
    {
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
    ) {
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
        if (empty($approvers)) return [];
        //
        $teams = [];
        $departments = [];
        //
        foreach ($approvers as $approver) {
            if ($approver['is_department'] == 1) $departments[] = explode(',', $approver['department_sid']);
            else $teams[] = explode(',', $approver['department_sid']);
        }
        //
        if (in_array('all', $departments)) $departments = 'all';
        else {
            $nt = [];
            foreach ($departments as $department) {
                foreach ($department as $t) $nt[] = $t;
            }
            $departments = $nt;
        }
        if (in_array('all', $teams)) $teams = 'all';
        else {
            $nt = [];
            foreach ($teams as $team) {
                foreach ($team as $t) $nt[] = $t;
            }
            $teams = $nt;
        }
        //
        $de = [];
        $te = [];
        //
        if ($departments != 'all' && !empty($departments)) {
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
            if ($departments != 'all' && !empty($departments)) $this->db->where_in('departments_employee_2_team.department_sid', $departments);
            //
            $de = $this->db
                ->get('departments_employee_2_team')
                ->result_array();
        }
        //
        if ($teams != 'all' && !empty($teams)) {
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
            if ($teams != 'all' && !empty($teams)) $this->db->where_in('departments_employee_2_team.team_sid', $teams);
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
            ),
            function ($emp) {
                if ($emp != 0) return $emp;
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
        $policies = [],
        $includeArchived = false
    ) {

        $this->db
            ->select('
            timeoff_policies.sid,
            timeoff_policies.title,
            timeoff_policies.accruals,
            timeoff_policies.assigned_employees,
            timeoff_policies.is_entitled_employee,
            timeoff_policies.off_days,
            timeoff_policies.is_included,
            timeoff_policies.for_admin,
            timeoff_policies.default_policy,
            timeoff_category_list.category_name,
            timeoff_policies.policy_category_type as category_type,
            timeoff_policies.is_archived,
            timeoff_policies.allowed_approvers
        ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_policies.company_sid', $companyId)
            ->order_by('timeoff_policies.sort_order', 'ASC');

        if (!$includeArchived) {
            $this->db->where('timeoff_policies.is_archived', 0);
        }
        //
        if ($withInclude) $this->db->where('timeoff_policies.is_included', 1);
        if (!empty($policies)) $this->db->where_in('timeoff_policies.sid', $policies);
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
    ) {
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
        if (empty($balances)) return [];
        //
        $t = [];
        //
        foreach ($balances as $balance) {
            //
            $bb = $balance['user_sid'] . '-' . $balance['policy_sid'];
            //
            if (!isset($t[$bb])) $t[$bb] = 0;
            //
            if ($balance['is_added'] == '1') $t[$bb] += $balance['added_time'];
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
    ) {
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
        if (empty($balances)) return [];
        //
        $t = [];
        //
        foreach ($balances as $balance) {
            //
            $t[$balance['policy_sid']] = $balance;
        }
        //
        return $t;
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
        $employeeId,
        $includeArchived = false
    ) {
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type,
            terminated_status,
            active,
            employment_date

        ')
            ->order_by('first_name', 'ASC')
            ->where('parent_sid', $companyId)
            ->where('sid', $employeeId);
        //
        if (!$includeArchived) {
            // $this->db->where('active', 1)
            // ->where('terminated_status', 0);
        }
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return [];
        //
        if ($employee['is_executive_admin'] == 1) {
            return [];
        }
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId, true, [], $includeArchived);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        // fulltime
        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }
        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = $JoinedDate;
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach ($policies as $policy) {
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'IsArchived' => $policy['is_archived'],
                'Title' => $policy['title'],
                'CategoryType' => $policy['category_type'],
                'IsEntitledEmployee' => $policy['is_entitled_employee'],
                'AssignedEmployees' => $policy['assigned_employees'],
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
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes'] / 60, 2))
                ],
                'Category' => $policy['category_name'],
                'Reason' => ''
            ];
            //
            if (checkPolicyESTA($policy['sid']) != 1) {

                if ($policy['is_entitled_employee'] == 1) {
                    //
                    if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                        continue;
                    }

                    if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                } else {
                    //
                    if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                        continue;
                    }
                    // Not-Entitled
                    if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                }
            }
            //
            $accruals = json_decode($policy['accruals'], true);
            //

            //
            if (checkPolicyESTA($policy['sid']) != 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }

            //
            if (checkPolicyESTA($policy['sid']) == 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }


            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug,
                $policy['category_type']
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
    ) {
        $e['Policies'] = [];
        $e['Approvers'] = [];
        // Get approvers
        $e['Approvers'] = $this->getEmployeeApprovers($companyId, $employeeId);
        //
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type,
            employment_date
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
        if ($employee['is_executive_admin'] == 1) {
            return $e;
        }
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //

        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }

        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = $JoinedDate;
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach ($policies as $policy) {
            //
            $allowedApprovers = explode(',', $policy['allowed_approvers']);
            //
            if (
                $policy['for_admin'] == 1
                && (
                    (!in_array('all', $allowedApprovers) &&
                        !in_array(getCurrentLoginEmployeeDetails('sid'), $allowedApprovers)
                    )
                    && getCurrentLoginEmployeeDetails('access_level_plus') != 1
                )
            ) {
                continue;
            }
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'Title' => $policy['title'],
                'categoryType' => $policy['category_type'],
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
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes'] / 60, 2))
                ],
                'Category' => $policy['category_name'],
                'Reason' => ''
            ];


            if ($policy['is_entitled_employee'] == 1) {
                //

                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    continue;
                }

                if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            } else {
                //

                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    continue;
                }
                // Not-Entitled
                if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            }
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if (!isset($accruals['employeeTypes'])) continue;
            if (!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug,
                $policy['category_type']
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
    ) {
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
        foreach ($policies as $policy) {
            // Entitled


            if ($policy['is_entitled_employee'] == 1) {
                //

                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    continue;
                }
                //
                if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            } else {

                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    continue;
                }
                // Not-Entitled
                if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
            }

            //
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if (!isset($accruals['employeeTypes'])) continue;
            if (!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug,
                $policy['category_type']
            );
            //
            if (empty($t['Reason'])) {
                $returnBalance['total']['PolicyIds'][] = $policy['sid'];
            }
            //
            $returnBalance[$policy['title']] = $balance;
            $durationInHours = $durationInMinutes / 60;
            $returnBalance[$policy['title']]['title'] = $policy['title'];
            $returnBalance[$policy['title']]['policy_type'] = $policy['category_type'];
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
            if ($t['AllowedTime'] != 0) {
                $returnBalance['total']['ConsumedTime'] += $t['ConsumedTime'];
                $returnBalance['total']['UnpaidConsumedTime'] += $t['UnpaidConsumedTime'];
                $returnBalance['total']['RemainingTime'] += $t['RemainingTime'];
                $returnBalance['total']['MaxNegativeTime'] += $t['MaxNegativeTime'];
                $returnBalance['total']['RemainingTimeWithNegative'] += $t['RemainingTimeWithNegative'];
            } else {
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
     * @param  string  $consumeDate
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
        if ($frequency == 'monthly') {
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
        //
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
     * Get employee consumed time
     * 
     * @employee  Mubashir Ahmed
     * @date      12/21/2020
     * 
     * @param  Integer $policyId
     * @param  Integer $employeeId
     * @param  String  $method
     * @param  String  $asOfToday
     * @param  string  $consumeDate
     * 
     * @return Array
     */
    function getEmployeeConsumedTimeByResetDate(
        $policyId,
        $employeeId,
        $lastAnniversaryDate,
        $upcomingAnniversaryDate

    ) {
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
        $this->db->group_start();
        $this->db->where("request_from_date >=", $lastAnniversaryDate);
        $this->db->where("request_from_date <", $upcomingAnniversaryDate);
        $this->db->group_end();
        //
        $result = $this->db->get();
        //
        $record = $result->row_array();
        $result->free_result();
        //
        return $record['requested_time'] == null ? 0 : $record['requested_time'];
    }

    /** 
     * Get employee consumed time
     * 
     * @employee  Aleem Shaukat
     * @date      12/19/2023
     * 
     * @param  Integer $policyId
     * @param  Integer $employeeId
     * @param  String  $method
     * @param  String  $asOfToday
     * @param  string  $consumeDate
     * 
     * @return Array
     */
    function getEmployeeConsumedTimeByResetDateNew(
        $policyId,
        $employeeId,
        $lastAnniversaryDate,
        $upcomingAnniversaryDate

    ) {
        $this->db
            ->select('sid')
            ->from('timeoff_requests')
            ->where('timeoff_policy_sid', $policyId)
            ->where('employee_sid', $employeeId)
            ->where('status', 'approved')
            ->where('is_draft', 0)
            ->where('archive', 0);
        //
        $this->db->group_start();
        $this->db->where("request_to_date >=", $lastAnniversaryDate);
        $this->db->where("request_to_date <", $upcomingAnniversaryDate);
        $this->db->group_end();
        //
        $result = $this->db->get();
        $upperLimitRecords = $result->result_array();
        $recordIds = array_column($upperLimitRecords, 'sid');
        //
        $this->db
            ->select('timeoff_days')
            ->from('timeoff_requests')
            ->where('timeoff_policy_sid', $policyId)
            ->where('employee_sid', $employeeId)
            ->where('status', 'approved')
            ->where('is_draft', 0)
            ->where('archive', 0);
        //
        if (!empty($recordIds)) {
            $this->db->or_where_in('sid', $recordIds);
        }
        //
        $this->db->group_start();
        $this->db->where("request_from_date >=", $lastAnniversaryDate);
        $this->db->where("request_from_date <", $upcomingAnniversaryDate);
        $this->db->group_end();
        //
        $result = $this->db->get();
        //
        $records = $result->result_array();
        $result->free_result();
        //
        $consumedTime = 0;

        //
        foreach ($records as $record) {
            $timeoffDays = json_decode($record['timeoff_days'], true)['days'];
            //
            foreach ($timeoffDays as $dayInfo) {
                if (preg_match('/[0-9]{4}-[0-9]{2}-[0-9]{2}/', $dayInfo['date'])) {
                    $dayInfo["date"] = formatDateToDB(
                        $dayInfo["date"],
                        DB_DATE,
                        "m-d-Y"
                    );
                }
                //
                $timeoffDate = DateTime::createFromFormat('m-d-Y', $dayInfo['date'])->format('Y-m-d');
                //    
                if (($timeoffDate >= $lastAnniversaryDate) && ($timeoffDate <= $upcomingAnniversaryDate)) {
                    $consumedTime = $consumedTime + $dayInfo['time'];
                }
            }
        }
        //
        return $consumedTime;
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
        if ($frequency == 'yearly') {
            $dateFormat = 'Y';
            $dateFormatDB = '%Y';
        }
        //
        $date = date($dateFormat, strtotime($todayDate . ' 00:00:00'));
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
        if (!empty($record) && $record['consumed'] != '') {
            //
            $consumedTimeInMinutes = $record['occurrence'] * $record['consumed'];
        }
        //
        $monthsWorked -= 1;
        $returnTime = 0;
        $years = ($monthsWorked >= 12 ? ($monthsWorked) / 12 : 0);
        //
        if ($frequency == 'none') {
            $newTime = $accrualRateInMinutes * $monthsWorked;
            $cNewTime = $carryOverInMinutes * $monthsWorked;
            //
            if ($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if ($frequency == 'monthly') {
            $newTime = $accrualRateInMinutes * $monthsWorked;
            $cNewTime = $carryOverInMinutes * $monthsWorked;
            //
            if ($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if ($frequency == 'yearly') {
            $newTime = $accrualRateInMinutes * $years;
            $cNewTime = $carryOverInMinutes * $years;
            //
            if ($newTime > $cNewTime) return $cNewTime;
            return $newTime;
        }
        if ($frequency == 'custom') {
            $newTime = $accrualRateInMinutes * $years;
            $cNewTime = $carryOverInMinutes * $years;
            //
            if ($newTime > $cNewTime) return $cNewTime;
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
    ) {
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
    ) {
        $this->db->select('
            user_shift_hours,
            user_shift_minutes
        ')
            ->where('parent_sid', $companyId)
            ->where('sid', $employeeId);
        // ->where('active', 1)
        // ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employee = $a->row_array();
        $a->free_result();
        //
        if (empty($employee)) return [];
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
             users.timezone,
             users.access_level,
             users.access_level_plus,
             users.is_executive_admin,
             users.pay_plan_flag,
             users.job_title,
             timeoff_policies.title,
             "1" as is_manual ,
             "0" as is_allowed
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
            timeoff_requests.sid,
            timeoff_requests.requested_time as added_time,
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.created_at,
            timeoff_requests.updated_at as effective_at ,
            "0" as is_added,
            timeoff_requests.reason as note,
            users.first_name,
            users.last_name,
            users.timezone,
            users.access_level,
            users.access_level_plus,
            users.is_executive_admin,
            users.pay_plan_flag,
            users.job_title,
            timeoff_policies.title,
            "0" as is_manual, 
            "0" as is_allowed 
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
        if (sizeof($c)) {
            //
            foreach ($c as $key => $balance) {
                //
                $c[$key]['approverName'] = '';
                $c[$key]['approverRole'] = '';
                //
                $status = '"status":"approved"';
                $canApprove = '"canApprove":1';
                //
                $e =
                    $this->db->select('
                    users.first_name,
                    users.last_name,
                    users.timezone,
                    users.access_level,
                    users.access_level_plus,
                    users.is_executive_admin,
                    users.pay_plan_flag,
                    users.job_title
                ')
                    ->join('users', 'users.sid = timeoff_request_timeline.employee_sid')
                    ->where('timeoff_request_timeline.request_sid', $balance['sid'])
                    ->where("timeoff_request_timeline.note regexp '$status'", NULL, NULL)
                    ->where("timeoff_request_timeline.note regexp '$canApprove'", NULL, NULL)
                    ->order_by('timeoff_request_timeline.sid', 'DESC')
                    ->get('timeoff_request_timeline')
                    ->row_array();
                //
                if (!empty($e)) {
                    $c[$key]['approverName'] = ucwords($e['first_name'] . ' ' . $e['last_name']);
                    $c[$key]['approverRole'] = remakeEmployeeName($e, false);
                }
            }
            $b = array_merge($b, $c);
        }
        //
        $allowedBalance = $this->getEmployeeAllowedBalanceHistory($employeeId);
        //
        if (sizeof($allowedBalance)) {
            $b = array_merge($b, $allowedBalance);
            //
            usort($b, function ($item1, $item2) {
                $t1 = strtotime($item1['created_at']);
                $t2 = strtotime($item2['created_at']);
                return $t2 - $t1;
            });
        }
        //
        if (count($b)) {
            foreach ($b as $k => $v) {
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
     * Get employee allowed balance history
     * 
     * @employee  Aleem Shaukat
     * @date      03/04/2023
     * 
     * @param  Integer $companyId
     * @param  Integer $employeeId
     * 
     * @return Array
     */
    function getEmployeeAllowedBalanceHistory($employeeId)
    {
        //
        $a = $this->db
            ->select('
             timeoff_allowed_balances.is_added,
             timeoff_allowed_balances.added_time,
             timeoff_allowed_balances.note,
             timeoff_allowed_balances.effective_at,
             timeoff_allowed_balances.effective_at as created_at,
             users.first_name,
             users.last_name,
             users.timezone,
             users.access_level,
             users.access_level_plus,
             users.is_executive_admin,
             users.pay_plan_flag,
             users.job_title,
             timeoff_policies.title,
             "0" as is_manual, 
             "1" as is_allowed 
         ')
            ->join('users', 'users.sid = timeoff_allowed_balances.added_by', 'inner')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_allowed_balances.policy_sid', 'inner')
            ->where('timeoff_allowed_balances.user_sid', $employeeId)
            ->where('timeoff_policies.is_archived', 0)
            ->order_by('timeoff_allowed_balances.created_at', 'DESC')
            ->get('timeoff_allowed_balances');
        //
        $b = $a->result_array();
        $a = $a->free_result();
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
    function addEmployeeBalance($post)
    {
        //
        $this->db->insert(
            'timeoff_balances',
            [
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
        $startDate,
        $policyIds = []
    ) {
        //
        $r = [];
        //
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type,
            employment_date
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
        if ($employee['is_executive_admin'] == 1) {
            return $r;
        }
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId, true, $policyIds);
        $balances = $this->getBalances($companyId);


        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }


        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = $JoinedDate;
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach ($policies as $policy) {
            //
            $allowedApprovers = explode(',', $policy['allowed_approvers']);
            //
            if (
                $policy['for_admin'] == 1
                && (
                    (!in_array('all', $allowedApprovers) &&
                        !in_array(getCurrentLoginEmployeeDetails('sid'), $allowedApprovers)
                    )
                    && getCurrentLoginEmployeeDetails('access_level_plus') != 1
                )
            ) {
                continue;
            }
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'UserId' => $employeeId,
                'Title' => $policy['title'],
                'categoryType' => $policy['category_type'],
                'AllowedTime' => 0,
                'ConsumedTime' => 0,
                'RemainingTime' => 0,
                'RemainingTimeWithNegative' => 0,
                'Balance' => 0,
                'MaxNegativeTime' => 0,
                'Category' => $policy['category_name'],
                'OffDays' => $policy['off_days'],
                'Settings' => [
                    'Slug' => $slug,
                    'ShiftHours' => $employee['user_shift_hours'],
                    'ShiftMinutes' => $employee['user_shift_minutes'],
                    'Shift' => $employee['user_shift_hours'] + (round($employee['user_shift_minutes'] / 60, 2))
                ],
                'Reason' => ''
            ];
            //

            if (checkPolicyESTA($policy['sid']) != 1) {

                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    continue;
                }
                // Entitled
                if ($policy['is_entitled_employee'] == 1) {

                    if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                } else {
                    // Not-Entitled
                    if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                }
            }
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if (checkPolicyESTA($policy['sid']) != 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }

            if (checkPolicyESTA($policy['sid']) == 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                $startDate,
                $slug,
                $policy['category_type']
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

            $balance['lastAnniversaryDate'] = $t['lastAnniversaryDate'];
            $balance['upcomingAnniversaryDate'] = $t['upcomingAnniversaryDate'];
            //
            $r[] = $balance;
        }
        //
        return $r;
    }

    //
    function insertTimeOff($ins)
    {
        $this->db->insert('timeoff_requests', $ins);
        return $this->db->insert_id();
    }

    //
    function getRequestById($requestId, $addApprovers = TRUE)
    {
        //
        $request =
            $this->db
            ->select('
            timeoff_requests.*,
            timeoff_policies.title,
            company.CompanyName,
            users.user_shift_hours,
            users.user_shift_minutes,
            ' . (getUserFields()) . '
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
        if ($addApprovers) {
            // Get approvers
            $request['approvers'] = $this->getEmployeeApprovers($request['company_sid'], $request['employee_sid']);
        }
        //
        if (empty($request['timeoff_days']) || $request['timeoff_days'] == '[]') {
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
            $off_days = $this->getTimeOffDays($request['company_sid']);
            //
            for ($i = 0; $i < $d; $i++) {
                $day_name = strtolower(date('l', strtotime($request['request_from_date'] . ' 00:00:00 +' . ($i) . ' days')));
                //
                $newDate = date('m-d-Y', strtotime($request['request_from_date'] . ' 00:00:00 +' . ($i) . ' days'));
                //
                $time = 0;
                //
                if (!in_array($day_name, $off_days)) {

                    if ($totalTimeInHours > $user['user_shift_hours']) {
                        //
                        $time = $user['user_shift_hours'];
                        //
                    } else $time = $totalTimeInHours;
                    // 
                    $totalTimeInHours -= $time;
                }
                //
                $json['days'][$i] = [
                    'time' => $time * 60,
                    'partial' => 'fullday',
                    'date' => $newDate
                ];
                // 
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
    ) {
        //
        $notIds = [];
        $approvers = [];
        //
        if ($post['level'] != 1) {
            $notIds = $this->getEmployeeTeamMemberIds($post['employerId']);
            $subordinateIds = $this->getEmployeeSubordinateIds($post['employerId']);

            if (!empty($notIds)) {
                $approvers = $notIds;
            }

            $combine_employees = array_merge($notIds, $subordinateIds);
            $notIds = array_unique($combine_employees);
        }
        //
        $this->db
            ->select('
            timeoff_requests.*,
            users.user_shift_hours,
            users.user_shift_minutes,
            users.joined_at,
            users.registration_date,
            users.rehire_date,
            users.terminated_status,
            users.active,
            timeoff_policies.title,
            timeoff_policies.policy_category_type as categoryType,
            ' . (getUserFields()) . '
            ')

            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
            ->where('timeoff_policies.is_archived', 0)
            ->where('timeoff_requests.company_sid', $post['companyId']);
        //
        if ($post['type'] != 'archive') {
            $this->db->where('timeoff_requests.status', $post['type']);
            $this->db->where('timeoff_requests.archive', 0);
        } else {
            $this->db->where('timeoff_requests.archive', 1);
        }
        //
        if (isset($post['isMine']) && $post['isMine'] == 1) {
            $notIds = [];
            $this->db->where('timeoff_requests.employee_sid', $post['employeeId']);
        } else {
            if ($post['level'] == 0 && empty($notIds)) return [];
        }
        //
        if (!empty($notIds)) {
            //
            if (isset($post['filter']['employees']) && !empty($post['filter']['employees']) && $post['filter']['employees'] != 'all') {

                $notIds = array_intersect([$post['filter']['employees']], $notIds);
                //
                if (empty($notIds)) {
                    return [];
                }
            }
            $this->db->where_in('timeoff_requests.employee_sid', $notIds);
        } else if ($post['filter']['employees'] != 'all') {
            $this->db->where('timeoff_requests.employee_sid', $post['filter']['employees']);
        }
        //
        if ($post['filter']['order'] == 'created_start_desc') {
            $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        } else if ($post['filter']['order'] == 'created_start_asc') {
            $this->db->order_by('timeoff_requests.request_from_date', 'ASC', false);
        } else {
            // $this->db->where('timeoff_requests.request_from_date >= ', 'CURDATE()', false);
            $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        }
        //
        if (
            isset($post['filter']['startDate'], $post['filter']['endDate']) &&
            !empty($post['filter']['startDate']) &&
            !empty($post['filter']['endDate'])
        ) {
            $newDate = formatDateToDB($post['filter']['startDate'], 'm-d-Y', 'Y-m-d');
            $endDate = formatDateToDB($post['filter']['endDate'], 'm-d-Y', 'Y-m-d');
            //
            $this->db->where('timeoff_requests.request_from_date >=', $newDate);
            $this->db->where('timeoff_requests.request_from_date <=', $endDate);
        } else {
            //
            if ($post['type'] != 'pending') {
                $this->db->group_start();
                $this->db->where('timeoff_requests.request_from_date >= "' . (date('Y')) . '-01-01"', null);
                $this->db->where('timeoff_requests.request_from_date <= "' . (date('Y')) . '-12-31"', null);
                $this->db->or_where('timeoff_requests.request_to_date >= "' . (date('Y')) . '-01-01"', null);
                $this->db->group_end();
            }
        }

        // check and add the employee status
        if ($post["filter"]["employeeStatus"]) {
            $this->db->where(getTheWhereFromEmployeeStatus($post["filter"]["employeeStatus"]));
        } else {
            $this->db->where('users.active', 1)
                ->where('users.terminated_status', 0);
        }

        //
        $requests = $this->db->get('timeoff_requests')
            ->result_array();
        //
        if (!empty($requests)) {
            //
            if ($post['filter']['order'] == 'upcoming') {
                // Sort requests by date
                $newRequests = [];
                $oldRequests = [];
                //
                foreach ($requests as $request) {
                    if ($request['request_from_date'] > date('Y-m-d', strtotime('now'))) {
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
            $is_access_level_plus = 'no';
            $employee_info = db_get_employee_profile($post['employerId']);
            //
            if ($employee_info[0]['access_level_plus'] == 1) {
                $is_access_level_plus = 'yes';
            }
            //
            foreach ($requests as $k => $request) {
                if (isset($post['isMine']) && $post['isMine'] == 1) {
                    $requests[$k]['allow_update'] = 'yes';
                } else {

                    if ($is_access_level_plus == 'yes') {
                        $requests[$k]['allow_update'] = 'yes';
                    } else {

                        if (in_array($request['employee_sid'], $approvers)) {
                            $requests[$k]['allow_update'] = 'yes';
                        } else {
                            $requests[$k]['allow_update'] = 'no';
                        }
                    }

                    $requests[$k]['anniversary_text'] = get_user_anniversary_date(
                        $request['joined_at'],
                        $requests['registration_date'],
                        $requests['rehire_date']
                    );
                }

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

    function getEmployeeSubordinateIds($employerId)
    {
        $myTeams = $this->getMyTeams($employerId, 'teams');
        //
        $result = array();
        //
        if (!empty($myTeams['teams'])) {
            $all_employees = $this->get_all_employees_to_team($myTeams['teams']);

            if (!empty($all_employees)) {
                $subordinateIds = array_column($all_employees, 'employee_sid');
                $uniqueSubordinateIds = array_unique($subordinateIds);
                $result = $uniqueSubordinateIds;
            }
        }

        return $result;
    }

    function get_all_employees_to_team($team_sids)
    {
        if (empty($team_sids)) {
            return array();
        }

        $this->db->select('employee_sid');
        $this->db->where_in('team_sid', $team_sids);
        $record_obj = $this->db->get('departments_employee_2_team');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function getMyTeams(
        $sid,
        $type = 'all'
    ) {
        //
        $departments = $this->db
            ->select('sid')
            ->where('departments_management.is_deleted', 0)
            ->where('departments_management.status', 1)
            ->where('FIND_IN_SET(' . ($sid) . ', supervisor)', NULL, FALSE)
            ->get('departments_management')
            ->result_array();
        //
        if (!empty($departments)) {
            $departments = array_column($departments, 'sid');
            //
            $newDept = [];
            //
            foreach ($departments as $dept) {
                //
                $t = explode(',', $dept);
                //
                $newDept = array_merge($t, $newDept);
            }
            //
            $departments = $newDept;
        }
        //

        if (!empty($departments)) {
            $teams = $this->db
                ->select('
                    departments_team_management.sid
                ')
                ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
                ->where('departments_management.is_deleted', 0)
                ->where('departments_management.status', 1)
                ->where('departments_team_management.is_deleted', 0)
                ->where('departments_team_management.status', 1)
                ->group_start()
                ->where_in('departments_team_management.department_sid', $departments)
                ->or_where('FIND_IN_SET(' . ($sid) . ', team_lead)', NULL, FALSE)
                ->group_end()
                ->get('departments_team_management')
                ->result_array();
        } else {
            $teams = $this->db
                ->select('
                    departments_team_management.sid
                ')
                ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
                ->where('departments_management.is_deleted', 0)
                ->where('departments_management.status', 1)
                ->where('departments_team_management.is_deleted', 0)
                ->where('departments_team_management.status', 1)
                ->where('FIND_IN_SET(' . ($sid) . ', team_lead)', NULL, FALSE)
                ->get('departments_team_management')
                ->result_array();
        }

        //
        if (!empty($teams)) {
            //
            $teams = array_column($teams, 'sid');
            //
            $newDept = [];
            //
            foreach ($teams as $dept) {
                //
                $t = explode(',', $dept);
                //
                $newDept = array_merge($t, $newDept);
            }
            //
            $teams = $newDept;
        }
        //
        if ($type == 'all') {
            return ['departments' => $departments, 'teams' => $teams];
        } else {
            return ['teams' => $teams];
        }
        // 

    }

    function get_all_departments($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_deleted', 0);
        $this->db->where('status', 1);
        $this->db->order_by('sort_order', 'asc');
        $record_obj = $this->db->get('departments_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return array_column($record_arr, 'sid');
        } else {
            return array();
        }
    }

    function get_all_teams($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_deleted', 0);
        $this->db->where('status', 1);
        $this->db->order_by('sort_order', 'asc');
        $record_obj = $this->db->get('departments_team_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return array_column($record_arr, 'sid');
        } else {
            return array();
        }
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
            timezone,
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
    ) {
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
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            is_executive_admin,
            employee_type,
            terminated_status,
            active,
            employment_date
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
        $settings = $this->getSettings($companyId);
        //
        if (empty($employee) || $employee['is_executive_admin']) {
            $r['Balance']['Remaining'] = get_array_from_minutes(
                0,
                (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']),
                $settings['slug']
            );
            $r['Balance']['Consumed'] = get_array_from_minutes(
                0,
                (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']),
                $settings['slug']
            );
            return $r;
        }
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        // Fetch employee policies
        //

        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }

        //
        $balance =
            $this->getBalanceOfEmployee(
                $employeeId,
                $employee['employee_status'],
                $JoinedDate,
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
                $balance['total']['ConsumedTime']['M']['minutes'] + $balance['total']['UnpaidConsumedTime']['M']['minutes'],
                (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']),
                $settings['slug']
            )
        ];
        //
        if (!empty($balance['total']['PolicyIds'])) {
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
                ->where('timeoff_requests.request_from_date >= ', '' . (date('Y')) . '-01-01')
                ->where('timeoff_requests.request_from_date <= ', '' . (date('Y')) . '-12-31')
                ->get('timeoff_requests')
                ->result_array();
        } else {
            $timeoffs = [];
        }
        //
        $durationInHours = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']) / 60;
        //
        if (!empty($timeoffs)) {
            //
            $tif = [];
            foreach ($timeoffs as $timeoff) {
                //
                if (empty($timeoff['timeoff_days']) || $timeoff['timeoff_days'] = '[]') {
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
                    for ($i = 0; $i < $d; $i++) {
                        //
                        $newDate = date('m-d-Y', strtotime($timeoff['request_from_date'] . ' 00:00:00 +' . ($i) . ' days'));
                        //
                        $time = 0;
                        //
                        if ($totalTimeInHours > $employee['user_shift_hours']) {
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
                if (!empty($jta)) $r['Timeoffs'][$month][1] += $jta['totalTime'];
            }
            //
            foreach ($r['Timeoffs'] as $k => $ti) {
                if ($ti != 0) {
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
    ) {
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
        ' . (getUserFields()) . '
        ')
            ->join('users', 'users.sid = timeoff_approvers.employee_sid', 'inner')
            ->where('timeoff_approvers.company_sid', $companyId)
            ->where('timeoff_approvers.status', 1)
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->where('timeoff_approvers.is_archived', 0);
        //
        if (!empty($teamIds)) {
            foreach ($teamIds as $teamId) $tWhere .= "FIND_IN_SET($teamId, timeoff_approvers.department_sid) > 0 OR ";
            $tWhere = rtrim($tWhere, " OR ");
        }
        if (!empty($departmentIds)) {
            foreach ($departmentIds as $departmentId) $dWhere .= "FIND_IN_SET($departmentId, timeoff_approvers.department_sid) > 0 OR ";
            $dWhere = rtrim($dWhere, " OR ");
        }
        $this->db->group_start();


        //
        if (!empty($tWhere) && !empty($dWhere)) {
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
        } else if (!empty($tWhere)) {
            $this->db->group_start();
            $this->db->where(rtrim($tWhere, 'OR '));
            $this->db->where('timeoff_approvers.is_department', 0);
            $this->db->group_end();
        } else if (!empty($dWhere)) {
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
        foreach ($approvers as $k => $approver) {
            if (in_array($approver['userId'], $t)) {
                unset($approvers[$k]);
                continue;
            }
            $t[] = $approver['userId'];
        }

        //
        return array_values($approvers);
    }

    //
    function getEmployerApprovalStatus(
        $employeeId,
        $ems = false
    ) {
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
        if ($b != 0) return 1;
        // Check approver
        $this->db
            ->where('employee_sid', $employeeId);
        //
        if (!$ems) {
            $this->db->where('approver_percentage', 1);
        }

        $b = $this->db->where('is_archived', 0)
            ->count_all_results('timeoff_approvers');
        //
        return $b > 1 ? 1 : $b;
    }

    //
    function push_default_timeoff_policy()
    {
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
        if ($accessOBJ['access_level_plus'] != 1 && $accessOBJ['pay_plan_flag'] != 1) {
            $ids = $this->getEmployeeTeamMemberIds($employeeId);
            //
            if (empty($ids)) return [
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
        if (!empty($ids)) $this->db->where_in('employee_sid', $ids);
        //
        $r['TodaysCount'] = $this->db->count_all_results('timeoff_requests');

        $this->db
            ->select('sid')
            ->where('company_sid', $companySid)
            ->where('status', 'pending');
        //
        if (!empty($ids)) $this->db->where_in('employee_sid', $ids);
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
    ) {
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            user_shift_hours,
            user_shift_minutes,
            employee_status,
            employee_type,
            employment_date
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
        if ($employee['is_executive_admin'] == 1) {
            return [];
        }
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //

        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }

        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = $JoinedDate;
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach ($policies as $policy) {
            //
            $balance = [
                'PolicyId' => $policy['sid'],
                'Default' => $policy['default_policy'],
                'Title' => $policy['title'],
                'RemainingTime' => 0,
                'Implements' => true,
                'Reason' => ''
            ];
            // Entitled
            if ($policy['is_entitled_employee'] == 1) {
                //
                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    $balance['Implements'] = false;
                } else {
                    if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) $balance['Implements'] = false;;
                }
            } else {
                //
                if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                    $balance['Implements'] = false;
                } else {
                    // Not-Entitled
                    if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) $balance['Implements'] = false;;
                }
            }
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
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug,
                $policy['category_type']
            );
            //
            $durationInHours = $durationInMinutes / 60;
            //
            if (!empty($slug)) {
                $balance['RemainingTime'] = get_array_from_minutes($t['RemainingTime'], $durationInHours, $slug)['text'];
            } else {
                $balance['RemainingTime'] = '';
            }

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
    ) {
        //
        $policies = $this->getCompanyPoliciesWithAccruals($companyId, true);
        //
        if (empty($policies)) return false;
        //
        foreach ($policies as $policy) {
            //
            if ($policy['default_policy'] == 1) continue;
            //
            $assignedemployees = explode(',', $policy['assigned_employees']);
            //
            if (!in_array($policy['sid'], $policyIds)) {
                //
                if (!in_array($employerId, $assignedemployees)) {
                    //
                    $assignedemployees[] = $employerId;
                    //
                    $this->db
                        ->where('sid', $policy['sid'])
                        ->update(
                            'timeoff_policies',
                            [
                                'assigned_employees' => implode(
                                    ',',
                                    $assignedemployees
                                )
                            ]
                        );
                }
            } else {
                //
                if (in_array($employerId, $assignedemployees)) {
                    //
                    $index = array_search($employerId, $assignedemployees);
                    //
                    unset($assignedemployees[$index]);
                    //
                    $this->db
                        ->where('sid', $policy['sid'])
                        ->update(
                            'timeoff_policies',
                            [
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
    ) {
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
        if (!empty($policies)) {
            foreach ($policies as $policy) {
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
                if (!empty($policyPlans)) $newPolicy['type_sid'] = array_column($policyPlans, 'timeoff_category_sid')[0];
                // Fetch awards
                $policyAwards = $this->db
                    ->where('timeoff_policy_sid', $policy['policyId'])
                    ->get('timeoff_policy_plans')
                    ->result_array();
                //
                if (!empty($policyAwards)) {
                    foreach ($policyAwards as $award) {
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
                if (empty($policy['accrual_frequency'])) $accruals['accrual_frequency'] = 'none';
                else if ($policy['accrual_frequency'] == 'monthly') $accruals['accrual_frequency'] = 'yearly';
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
                if ($policy['accrual_method'] == 'unlimited' || $policy['accrual_rate'] == 0) {
                    $accruals['rate'] = 0;
                } else if ($policy['accrual_method'] == 'hours_per_month') {
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
    function adjustRequests()
    {
        $requests = $this->db
            ->select('timeoff_days, sid')
            ->get('timeoff_requests')
            ->result_array();
        //
        if (empty($requests)) return false;
        //
        foreach ($requests as $request) {
            //
            if (empty($request['timeoff_days'])) continue;
            //
            $days = json_decode($request['timeoff_days'], true);
            //
            if (empty($days)) continue;
            if (isset($days['totalTime'])) continue;
            //
            $totalTime = 0;
            $newDays = [
                'totalTime' => 0,
                'days' => []
            ];
            //
            foreach ($days as $day) {
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

    function getActionData($table, $company_sid, $id)
    {

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

    function getCompanyDomainName($company_sid)
    {

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
    private function isTeamMember($employeeSid, $employerSid)
    {
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
    private function isColleague($employeeSid, $employerSid)
    {
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
        if (empty($employerTeams)) return 0;
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
        $employer_detail,
        $calendarStartDate,
        $calendarEndDate
    ) {
        //
        if ($type == 'day') {
            $startDate = $endDate = $year . '-' . $month . '-' . $day;
        } else {
            $startDate = $calendarStartDate;
            $endDate = $calendarEndDate;
        }
        $shift_start_time = "09:00";
        //
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
        // find the date within
        $this->db->group_start();
        $this->db->group_start();
        $this->db->where('timeoff_requests.request_from_date <=', $calendarStartDate);
        $this->db->where('timeoff_requests.request_to_date >=', $calendarStartDate);
        $this->db->where('timeoff_requests.request_from_date <=', $calendarEndDate);
        $this->db->where('timeoff_requests.request_to_date >=', $calendarEndDate);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('timeoff_requests.request_from_date >=', $calendarStartDate);
        $this->db->where('timeoff_requests.request_to_date <=', $calendarEndDate);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('timeoff_requests.request_from_date <=', $calendarEndDate);
        $this->db->where('timeoff_requests.request_to_date >=', $calendarStartDate);
        $this->db->group_end();
        $this->db->group_end();


        $this->db->group_start();
        $this->db->where('timeoff_requests.status', 'approved');
        $this->db->or_where('timeoff_requests.status', 'pending');
        $this->db->group_end();
        $this->db->where('timeoff_requests.company_sid', $company_id);
        $this->db->where('timeoff_policies.is_archived', 0);
        $this->db->where('timeoff_requests.archive', 0);
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) return $r;

        // Get company default time
        $a = $this->db
            ->select('timeoff_settings.default_timeslot, timeoff_formats.slug, timeoff_settings.team_visibility_check')
            ->from('timeoff_settings')
            ->join('timeoff_formats', 'timeoff_formats.sid = timeoff_settings.timeoff_format_sid')
            ->where('company_sid', $company_id)
            ->limit(1)
            ->get();
        //
        $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
        $timeoffSettings = $a->row_array();
        $a->free_result();
        //
        $timeoff_requests = array();
        $asApprover = $employer_detail['access_level_plus'] == 1 || $employer_detail['pay_plan_flag'] == 1 ? 1 : 0;
        //
        $teamMembers = $this->getEmployeeTeamMemberIds($employer_id);
        foreach ($b as $k => $v) {
            //
            if ($employer_detail['access_level_plus'] == 0 && $employer_detail['pay_plan_flag'] == 0) {
                // Check if the employee is part of team
                $isTeamMember = in_array($v['employee_sid'], $teamMembers);
                // $this->isTeamMember($v['employee_sid'], $employer_id);
                $isColleague = 0;
                //
                if (!empty($timeoffSettings) && $timeoffSettings['team_visibility_check'] == '1') {
                    $isColleague = $this->isColleague($v['employee_sid'], $employer_id);
                }
                $isSame = $v['employee_sid'] != $employer_id ? 0 : 1;
                //
                if ($isColleague) $asApprover = 2;
                if ($isSame) $asApprover = 1;
                //
                if ($isTeamMember == 0 && $isColleague == 0 && $isSame == 0) {
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
                ->where('active', 1)
                ->where('terminated_status', 0)
                ->where('is_executive_admin', 0)
                ->limit(1)
                ->get();
            if (!$a->row_array()) {
                unset($b[$k]);
                continue;
            }
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
            if (substr($week_start, 0, 2) == '11' && substr($week_start, 3, 4) == '29') {
                $startDate = $year . '-' . $week_start;
                $endDate = date('Y', strtotime('' . ($year) . '+1 year')) . '-' . $week_end;
            }
            if (substr($week_start, 0, 2) == '12' && substr($week_start, 3, 4) == '27') {
                $startDate = date('Y', strtotime("$year -1 year")) . '-' . $week_start;
                $endDate   = $year . '-' . $week_end;
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
    function shiftApprovers()
    {
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
        foreach ($list as $his) {
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
            $this->insertHistory($ins, 'timeoff_request_timeline');
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
    function shiftHolidays()
    {
        //
        $b = $this->db
            ->where('holiday_year', '2020')
            ->get('timeoff_holidays')
            ->result_array();
        //
        //
        if (empty($b)) return;
        $this->db
            ->where('holiday_year', '2021')
            ->delete('timeoff_holidays');
        //
        foreach ($b as $holiday) {
            //
            $newHoliday = $holiday;
            //
            unset(
                $newHoliday['sid'],
                $newHoliday['created_at'],
                $newHoliday['updated_at']
            );
            //
            $newHoliday['from_date'] = '2021' . (substr($newHoliday['from_date'], 4));
            $newHoliday['to_date'] = '2021' . (substr($newHoliday['to_date'], 4));
            $newHoliday['holiday_year'] = '2021';
            //
            $this->db->insert('timeoff_holidays', $newHoliday);
        }
    }

    //
    function getPolicyColumn(
        $column = '*',
        $policyId
    ) {
        return $this->db->select($column)
            ->where('sid', $policyId)
            ->get('timeoff_policies')
            ->row_array();
    }

    //
    function getEmployeeIdByemail(
        $companyId,
        $email
    ) {
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
    ) {
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
    ) {
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            employee_status,
            employee_type,
            employment_date
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
        if ($employee['is_executive_admin'] == 1) {
            return [];
        }
        //
        $settings = $this->getSettings($companyId);
        $policies = $this->getCompanyPoliciesWithAccruals($companyId);
        $balances = $this->getBalances($companyId);
        //
        $r = [];
        //

        if ($employee['employment_date'] && ($employee['employee_type'] == 'fulltime' || $employee['employee_type'] == 'full-time')) {
            $JoinedDate = $employee['employment_date'];
        } else {
            $JoinedDate = get_employee_latest_joined_date($employee['registration_date'], $employee['joined_at'], $employee['rehire_date']);
        }

        //
        $employeeId = $employee['userId'];
        $employementStatus = $employee['employee_status'];
        $employementType = $employee['employee_type'];
        $employeeJoiningDate = $JoinedDate;
        $durationInMinutes = (($employee['user_shift_hours'] * 60) + $employee['user_shift_minutes']);
        $slug = $settings['slug'];
        //
        foreach ($policies as $policy) {
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


            if (checkPolicyESTA($policy['sid']) != 1) {

                if ($policy['is_entitled_employee'] == 1) {
                    //
                    if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                        continue;
                    }

                    if ($policy['assigned_employees'] != 'all' && !in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                } else {
                    //
                    if ($policy['assigned_employees'] == '0' || $policy['assigned_employees'] == '') {
                        continue;
                    }
                    // Not-Entitled
                    if ($policy['assigned_employees'] == 'all' || in_array($employeeId, explode(',', $policy['assigned_employees']))) continue;
                }
            }
            //
            $accruals = json_decode($policy['accruals'], true);
            //
            if (checkPolicyESTA($policy['sid']) != 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (!in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }

            if (checkPolicyESTA($policy['sid']) == 1) {
                if (!isset($accruals['employeeTypes'])) continue;
                if (in_array('all', $accruals['employeeTypes']) && !in_array($employementType, $accruals['employeeTypes'])) continue;
            }
            //
            $t = getEmployeeAccrual(
                $policy['sid'], // Policy id
                $employeeId, // Employee id
                $employementStatus, // Employee employement status
                $employeeJoiningDate, // Employee joining date
                $durationInMinutes,   // Employee worked duration
                $accruals, // Accruals
                isset($balances[$employeeId . '-' . $policy['sid']]) ? $balances[$employeeId . '-' . $policy['sid']] : 0, // Employee Balance against this policy
                '',
                $slug,
                $policy['category_type']
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
    ) {
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
        $this->db->where('timeoff_requests.request_from_date >= "' . (date('Y')) . '-01-01"', null);
        $this->db->where('timeoff_requests.request_from_date <= "' . (date('Y')) . '-12-31"', null);
        //
        $requests = $this->db
            ->get('timeoff_requests')
            ->result_array();
        //
        if (!empty($requests)) {
            //
            $settings = $this->getSettings($post['companyId']);
            //
            foreach ($requests as $k => $request) {
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
    function getTodayOffEmployees($post, $count = false)
    {
        //
        $ses = $this->session->userdata('logged_in')['employer_detail'];
        //
        $notIds = [];
        //
        if ($ses['access_level_plus'] == 0 && $ses['pay_plan_flag'] == 0) {
            $notIds = $this->getEmployeeTeamMemberIds($post['employerId']);
            //
            if (empty($notIds)) return [];
        }
        //
        $nowDate = getSystemDate(DB_DATE);
        //
        $this->db
            ->distinct()
            ->select('
            ' . (getUserFields()) . '
        ')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->where('users.is_executive_admin', 0)
            ->where('timeoff_policies.is_archived', 0)
            ->where('timeoff_requests.archive', 0)
            ->where('timeoff_requests.status', 'approved')
            ->where('timeoff_requests.company_sid', $post['companyId']);
        //
        $this->db->where('timeoff_requests.archive', 0);
        $this->db->where('timeoff_requests.request_from_date <=', $nowDate);
        $this->db->where('timeoff_requests.request_to_date >=', $nowDate);
        //
        if (!empty($notIds)) $this->db->where_in('timeoff_requests.employee_sid', $notIds);
        //
        if ($count) {
            return $this->db->count_all_results('timeoff_requests');
        }
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
    function getTypesListByCompanyForAdmin()
    {
        // Fetch all polcies
        $policies = $this->db
            ->select('type_sid, title')
            ->from('timeoff_policies')
            ->where('company_sid', 0)
            ->order_by('sort_order', 'ASC')
            ->get()
            ->result_array();
        //
        if (!empty($policies)) {
            //
            $tmp = [];
            //
            foreach ($policies as $policy) {
                //
                if (!isset($tmp[$policy['type_sid']])) $tmp[$policy['type_sid']] = [];
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
        foreach ($types as $k => $v) $types[$k]['policies'] = isset($policies[$v['sid']]) ? $policies[$v['sid']] : null;
        //
        return $types;
    }

    public function get_all_timeoff_icons()
    {
        $this->db->select('*');
        $this->db->order_by('sort_order', 'ASC');
        return $this->db->get('timeoff_policy_icons_info')->result_array();
    }

    public function change_time_off_icon_info_content($icon_id, $data_to_update)
    {
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
    function getPoliciesForAdmin()
    {
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
    function getPoliciesWithCompanies()
    {
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
        if (!empty($policies)) {
            foreach ($policies as $policy) {
                //
                $slug = $policy['company_sid'];
                //
                if (!isset($cPolicies[$slug])) $cPolicies[$slug] = [];
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
        if (!empty($cp)) {
            foreach ($cp as $k => $company) {
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
    function getAdminPoliciesByIds($ids)
    {
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

    function assignAdminPoliciesToCompany($insert_time_off_policy)
    {
        $title = $insert_time_off_policy['title'];
        $company_sid = $insert_time_off_policy['company_sid'];

        $policy_exist = $this->db
            ->select('sid')
            ->from('timeoff_policies')
            ->where('company_sid', $company_sid)
            ->where('title', $title)
            ->get()
            ->result_array();

        if (empty($policy_exist)) {
            $this->db->insert('timeoff_policies', $insert_time_off_policy);
        }
    }

    function checkCategoryExistAgainstCompany($category_sid, $company_sid)
    {
        $cp = $this->db
            ->select('sid')
            ->from('timeoff_categories')
            ->where('company_sid', $company_sid)
            ->where('timeoff_category_list_sid', $category_sid)
            ->get()
            ->row_array();
        //
        if (empty($cp)) {
            $data_to_insert = array();
            $data_to_insert['company_sid'] = $company_sid;
            $data_to_insert['timeoff_category_list_sid'] = $category_sid;
            $data_to_insert['creator_sid'] = 0;
            $data_to_insert['is_default'] = 1;
            $data_to_insert['default_type'] = 1;

            $this->db->insert('timeoff_categories', $data_to_insert);
            return $this->db->insert_id();
        } else {
            return $cp['sid'];
        }
    }


    //
    function setAdminPolicyStatus(
        $status,
        $id
    ) {
        $this->db
            ->where('sid', $id)
            ->update(
                'timeoff_policies',
                [
                    'is_archived' => $status
                ]
            );
    }

    function updateTypeSort($a)
    {
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
    function getCompanyTheme($company_sid)
    {
        $theme_id = $this->db
            ->select('theme')
            ->from('timeoff_settings')
            ->where('company_sid', $company_sid)
            ->get()
            ->row_array();
        //
        if (!empty($theme_id)) {
            return $theme_id['theme'];
        } else {
            return TIMEOFF_THEME_TWO;
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
    function getEmailTemplate($templateId)
    {
        $a = $this->db
            ->select()
            ->where('sid', $templateId)
            ->limit(1)
            ->get('email_templates');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) return [];
        //
        return [
            'Subject' => $b['subject'],
            'Body' => $b['text'],
            'FromEmail' => !empty($b['from_email']) ? $b['from_email'] : 'no-reply@automotohr.com',
            'FromName' => !empty($b['from_name']) ? $b['from_name'] : '{{company_name}}'
        ];
    }

    function getAssignDepartmentAndTeams($employee_sid)
    {
        $myTeams = $this->getMyTeams($employee_sid);
        $assign_employees = array_column($this->get_all_employees_to_team($myTeams['teams']), 'employee_sid');
        //
        $data_to_return = array();
        $data_to_return['departments'] = $myTeams['departments'];
        $data_to_return['teams'] = $myTeams['teams'];
        $data_to_return['employees'] = !empty($assign_employees) ? array_unique($assign_employees) : array();

        return $data_to_return;
    }


    //
    function getEmployeesWithDepartmentAndTeams($companyId, $employees = 'all', $departments = 'all', $teams = 'all', $employeeStatus = 0)
    {
        //
        $this->db->select('
            sid,
            first_name,
            last_name,
            timezone,
            email,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin,
            job_title,
            employee_type,
            employee_number,
            joined_at,
            registration_date,
            rehire_date,
            active,
            terminated_status,
            employment_date
        ');

        // echo '<pre>';
        // print_r($employees);
        // print_r($departments);
        // print_r($teams);
        // die();

        if ($employees != 'all') {
            $this->db->or_where_in('sid', $employees);
        }

        if ($departments != 'all') {
            $this->db->or_where_in('department_sid', $departments);
        }

        if ($teams != 'all') {
            $this->db->or_where_in('team_sid', $teams);
        }

        $this->db->where('parent_sid', $companyId);

        if ($employeeStatus == 0) {

            $this->db->where('active', 1);
            $this->db->where('terminated_status', 0);
        } else {
            $this->db
                ->where(
                    getTheWhereFromEmployeeStatus($employeeStatus)
                );
        }
        $this->db->order_by('first_name', 'ASC');
        // Get employees
        $a = $this->db->get('users');
        $employees = $a->result_array();
        $a = $a->free_result();
        //
        if (empty($employees)) {
            return [];
        }
        // 
        $sids = array_column($employees, 'sid');
        //Get department
        $this->db->select('
            de2t.employee_sid,
            dtm.sid as team_sid,
            dt.sid as department_sid
        ')
            ->from('departments_employee_2_team de2t')
            ->join('departments_team_management dtm', 'dtm.sid = de2t.team_sid')
            ->join('departments_management dt', 'dt.sid = dtm.department_sid')
            ->where_in('de2t.employee_sid', $sids)
            ->where('dtm.status', 1)
            ->where('dtm.is_deleted', 0)
            ->where('dt.status', 1)
            ->where('dt.is_deleted', 0);
        //
        $a = $this->db->get();
        $dt = $a->result_array();
        $a = $a->free_result();
        //
        $newIds = [];
        //
        if (!empty($dt)) {
            //
            foreach ($dt as $record) {
                //
                if (!isset($newIds[$record['employee_sid']])) {
                    $newIds[$record['employee_sid']] = ['DepartmentIds' => [], 'TeamIds' => []];
                }
                //
                $newIds[$record['employee_sid']]['DepartmentIds'][$record['department_sid']] = $record['department_sid'];
                $newIds[$record['employee_sid']]['TeamIds'][$record['team_sid']] = $record['team_sid'];
            }
        }
        //
        foreach ($employees as $index => $employee) {
            //
            if (!isset($newIds[$employee['sid']])) {
                $employees[$index] = array_merge($employee, ['DepartmentIds' => [], 'TeamIds' => []]);
            } else {
                $employees[$index] = array_merge($employee, $newIds[$employee['sid']]);
            }
        }
        //
        return $employees;
    }


    //
    function getEmployeesTimeOff(
        $companyId,
        $employeeIds,
        $startDate = FALSE,
        $endDate = FALSE
    ) {

        $this->db->select('
            tp.title,
            tr.request_from_date,
            tr.request_to_date,
            tr.requested_time,
            tr.reason,
            tr.created_at,
            tr.timeoff_days,
            tr.status,
            u.first_name,
            u.sid as employeeId,
            u.job_title,
            u.last_name,
            u.timezone,
            u.access_level,
            u.employee_number,
            u.access_level_plus,
            u.is_executive_admin,
            u.pay_plan_flag,
            u.user_shift_minutes,
            u.user_shift_hours,
        ')
            ->from('timeoff_requests tr')
            ->join('timeoff_policies tp', 'tp.sid = tr.timeoff_policy_sid')
            ->join('users u', 'u.sid = tr.employee_sid')
            ->where('tr.company_sid', $companyId)
            ->where('tr.archive', 0)
            ->where('tr.is_draft', 0)
            ->order_by('tr.request_from_date', 'ASC');
        //
        if ($employeeIds != 'all' && $employeeIds && $employeeIds[0] != '') {
            $this->db->where_in('tr.employee_sid', $employeeIds);
        }
        //
        if ($startDate && $startDate != 'all') {
            $this->db->where('tr.request_from_date >= ', DateTime::createfromformat('m/d/Y', $startDate)->format('Y-m-d'));
        }
        //
        if ($endDate && $endDate != 'all') {
            $this->db->where('tr.request_from_date <= ', DateTime::createfromformat('m/d/Y', $endDate)->format('Y-m-d'));
        }
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        //
        $a = $a->free_result();
        //
        if (!empty($b)) {
            //
            $settings = $this->getSettings($companyId);
            //
            foreach ($b as $k => $request) {
                $tmp = get_array_from_minutes(
                    $request['requested_time'],
                    (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
                    $settings['slug']
                );
                //
                $b[$k]['consumed_time'] = $tmp['text'];
            }
        }
        return $b;
    }

    //
    function getMyTimeOffs(
        $companyId,
        $employeeId
    ) {
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
            ->where('timeoff_requests.company_sid', $companyId);
        $this->db->where('timeoff_requests.archive', 0);
        $this->db->where('timeoff_requests.status', 'approved');
        $this->db->where('timeoff_requests.employee_sid', $employeeId);
        $this->db->order_by('timeoff_requests.request_from_date', 'DESC', false);
        //
        $requests = $this->db
            ->get('timeoff_requests')
            ->result_array();
        //
        if (!empty($requests)) {
            //
            $settings = $this->getSettings($companyId);
            //
            foreach ($requests as $k => $request) {
                $b = get_array_from_minutes(
                    $request['requested_time'],
                    (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
                    $settings['slug']
                );
                //
                $requests[$k]['consumed_time'] = $b['text'];
            }
        }
        //
        return $requests;
    }

    // Get employee upcoming timeoffs
    function getEmployeeUpcomingTimeoffs($companyId, $employeeId)
    {
        //
        return $this->db
            ->select('
            timeoff_requests.sid,
            timeoff_requests.status,
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_policies.title
        ')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid')
            ->where('timeoff_requests.employee_sid', $employeeId)
            ->where('timeoff_requests.company_sid', $companyId)
            ->where('timeoff_requests.is_draft', 0)
            ->where_in('timeoff_requests.status', ['approved', 'pending'])
            ->where('timeoff_requests.request_from_date >=', date('Y-m-d', strtotime('now')))
            ->order_by('timeoff_requests.request_from_date', 'ASC')
            ->get('timeoff_requests')
            ->result_array();
    }

    function getEmployeesWithTimeoffRequest($company_sid, $type, $start_date, $end_date, $get_employees = false)
    {
        //

        if ($type == 'employees_only') {
            $this->db->select('employee_sid');
        } else if ($type == 'records_only') {
            $this->db->select('employee_sid, timeoff_policy_sid, requested_time, allowed_timeoff, request_from_date, request_to_date, status');
        }
        //
        $this->db->where('company_sid', $company_sid);
        $this->db->where('request_from_date >=', date('Y-m-d', strtotime($start_date)));
        $this->db->where('request_from_date <=', date('Y-m-d', strtotime($end_date)));
        $this->db->where('archive', 0);
        $this->db->where('is_draft', 0);
        if ($get_employees) {
            $this->db->select(getUserFields());
            $this->db->join('users', 'users.sid = timeoff_requests.employee_sid');
        }
        $records_obj = $this->db->get('timeoff_requests');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $return_data = array();

        if (!empty($records_arr)) {
            if ($type == 'employees_only') {
                $return_data = array_unique(array_column($records_arr, 'employee_sid'));
            } else if ($type == 'records_only') {
                $return_data = $records_arr;
            }
        }

        return $return_data;
    }

    function getEPolicyName($policy_sid)
    {
        //
        $this->db->select('title');
        //
        $this->db->where('sid', $policy_sid);
        $records_obj = $this->db->get('timeoff_policies');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        $return_data = 'Unknown';

        if (!empty($records_arr)) {
            $return_data = $records_arr['title'];
        }

        return $return_data;
    }

    public function getEmployeesByName($names, $companyId)
    {
        $records =
            $this->db
            ->select('sid, first_name, last_name')
            ->where('parent_sid', $companyId)
            ->where('is_executive_admin', 0)
            ->where_in('LOWER(REGEXP_REPLACE(CONCAT(first_name,"",last_name), "[^a-zA-Z]", ""))', $names)
            ->get('users')
            ->result_array();
        //
        if (empty($records)) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $record) {
            //
            $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($record['first_name'] . $record['last_name'])));
            //
            $tmp[$slug] = $record['sid'];
        }
        //
        return $tmp;
    }


    public function getCompanyPolicies($policies, $companyId)
    {
        //
        $tmp = [];
        //
        foreach ($policies as $policy) {
            $tmp[preg_replace('/[^a-zA-Z]/', '', strtolower(trim($policy)))] = 1;
        }
        $policies = $tmp;
        //
        $records =
            $this->db
            ->select('sid, title')
            ->from('timeoff_policies')
            ->where('timeoff_policies.company_sid', $companyId)
            ->get()
            ->result_array();
        //
        if (empty($records)) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $record) {
            //
            //
            $slug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($record['title'])));
            if (!isset($policies[$slug])) {
                continue;
            }
            //
            $tmp[$slug] = $record['sid'];
        }
        //
        return $tmp;
    }

    /**
     * Check the time off of and employee
     *
     * @param int    $companyId
     * @param int    $employeeId
     * @param int    $policyId
     * @param string $startDate
     * @param string $endDate
     * 
     * @return int
     */
    public function checkTimeOffForSpecificEmployee(
        int $companyId,
        int $employeeId,
        int $policyId,
        string $startDate,
        string $endDate
    ) {
        //
        return
            $this->db
            ->where([
                'company_sid' => $companyId,
                'employee_sid' => $employeeId,
                'timeoff_policy_sid' => $policyId
            ])
            ->where('request_from_date >= ', $startDate)
            ->where('request_to_date <= ', $endDate)
            ->count_all_results('timeoff_requests');
    }

    /**
     * Get policy title
     *
     * @param int $policyId
     * @return string
     */
    public function getPolicyNameById($policyId)
    {
        return $this->db
            ->select('title')
            ->where('sid', $policyId)
            ->get('timeoff_policies')
            ->row_array()['title'];
    }

    /**
     * Get employee company sid
     *
     * @param int $employeeId
     * @return int
     */
    public function getEmployeeCompanySid($employeeId)
    {
        return $this->db
            ->select('parent_sid')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array()['parent_sid'];
    }

    /**
     * Check the timeoff allowed balance of employee
     *
     * @param int    $employeeId
     * @param int    $policyId
     * @param int    $is_added
     * @param string $effactedDate
     * @param int $allowedTime
     * 
     * @return int
     */
    public function checkAllowedBalanceAdded(
        $employeeId,
        $policyId,
        $is_added,
        $effactedDate,
        $allowedTime
    ) {
        //
        return
            $this->db
            ->where('user_sid', $employeeId)
            ->where('policy_sid', $policyId)
            ->where('is_added', $is_added)
            ->where('effective_at', $effactedDate)
            ->where('added_time', $allowedTime)
            ->count_all_results('timeoff_allowed_balances');
    }

    //
    function addEmployeeAllowedBalance($balanceInfo)
    {
        $this->db->insert('timeoff_allowed_balances', $balanceInfo);
    }


    //
    function get_all_policies($company_sid)
    {
        $this->db->select('sid,title');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('title', 'asc');
        $record_obj = $this->db->get('timeoff_policies');
        return $record_obj->result_array();
        //  $record_obj->free_result();

    }


    function getEmployeesWithTimeoffRequestNew($company_sid, $type, $start_date, $end_date, $filter_policy, $get_employees = false)
    {
        //

        if ($type == 'employees_only') {
            $this->db->select('employee_sid');
        } else if ($type == 'records_only') {
            $this->db->select('employee_sid, timeoff_policy_sid, requested_time, allowed_timeoff, request_from_date, request_to_date, status');
        }
        //
        $this->db->where('company_sid', $company_sid);

        if ($start_date != '' && $end_date != '') {
            $this->db->where('request_from_date >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('request_from_date <=', date('Y-m-d', strtotime($end_date)));
        }

        $this->db->where('archive', 0);
        $this->db->where('is_draft', 0);
        if ($get_employees) {
            $this->db->select(getUserFields());
            $this->db->join('users', 'users.sid = timeoff_requests.employee_sid');
        }

        //

        if (!empty($filter_policy) && $filter_policy != 'all') {
            $this->db->where_in('timeoff_policy_sid', $filter_policy);
        }

        $records_obj = $this->db->get('timeoff_requests');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            if ($type == 'employees_only') {
                $return_data = array_unique(array_column($records_arr, 'employee_sid'));
            } else if ($type == 'records_only') {
                $return_data = $records_arr;
            }
        }

        return $return_data;
    }


    //
    function getEmployeesTimeOffNew(
        $companyId,
        $employeeIds,
        $startDate = FALSE,
        $endDate = FALSE,
        $filter_policy
    ) {


        $this->db->select('
        tp.title,
        tr.timeoff_policy_sid,
        tr.request_from_date,
        tr.request_to_date,
        tr.requested_time,
        tr.reason,
        tr.created_at,
        tr.timeoff_days,
        tr.status,
        u.first_name,
        u.sid as employeeId,
        u.job_title,
        u.last_name,
        u.timezone,
        u.access_level,
        u.employee_number,
        u.access_level_plus,
        u.is_executive_admin,
        u.pay_plan_flag,
        u.user_shift_minutes,
        u.user_shift_hours,
        u.registration_date,
        u.joined_at,
        u.rehire_date,
        u.active,
        u.terminated_status,
        u.employment_date,
        u.employee_type

    ')
            ->from('timeoff_requests tr')
            ->join('timeoff_policies tp', 'tp.sid = tr.timeoff_policy_sid')
            ->join('users u', 'u.sid = tr.employee_sid')
            ->where('tr.company_sid', $companyId)
            ->where('tr.archive', 0)
            ->where('tr.is_draft', 0)
            ->order_by('tr.request_from_date', 'ASC');
        //
        if ($employeeIds != 'all' && $employeeIds && $employeeIds[0] != '') {
            $this->db->where_in('tr.employee_sid', $employeeIds);
        }

        if (!empty($filter_policy) && $filter_policy != 'all') {
            $this->db->where_in('tr.timeoff_policy_sid', $filter_policy);
        }

        //
        if ($startDate && $startDate != 'all' && $startDate != '') {
            $this->db->where('tr.request_from_date >= ', DateTime::createfromformat('m/d/Y', $startDate)->format('Y-m-d'));
        }
        //
        if ($endDate && $endDate != 'all' && $endDate != '') {
            $this->db->where('tr.request_from_date <= ', DateTime::createfromformat('m/d/Y', $endDate)->format('Y-m-d'));
        }

        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        //
        $a = $a->free_result();
        //
        if (!empty($b)) {
            //
            $settings = $this->getSettings($companyId);
            //
            foreach ($b as $k => $request) {
                $tmp = get_array_from_minutes(
                    $request['requested_time'],
                    (($request['user_shift_hours'] * 60) + $request['user_shift_minutes']) / 60,
                    $settings['slug']
                );
                //
                $b[$k]['consumed_time'] = $tmp['text'];
                $b[$k]['timeoff_category'] = $this->getTimeOffPolicyType($request['timeoff_policy_sid'], $companyId);
                //
                $policiesDetail  = $this->getEmployeePoliciesByDate(
                    $companyId,
                    $request['employeeId'],
                    $request['request_from_date'],
                    [$request['timeoff_policy_sid']]
                );
                //
                if ($request['employment_date'] && ($request['employee_type'] == 'fulltime' || $request['employee_type'] == 'full-time')) {
                    $effectedDate = $request['employment_date'];
                } else {
                    $effectedDate = get_employee_latest_joined_date($request['registration_date'], $request['joined_at'], $request['rehire_date']);
                }
                //
                $employeeAnniversaryDate = getEmployeeAnniversary($effectedDate, $request['request_from_date']);
                //
                $b[$k]['allowed_time'] = $policiesDetail[0]['AllowedTime']['text'];
                $b[$k]['remaining_time'] = $policiesDetail[0]['RemainingTime']['text'];
                $b[$k]['anniversary_date'] = DateTime::createfromformat('Y-m-d', $employeeAnniversaryDate['upcomingAnniversaryDate'])->format('m/d/Y');;
                //
            }
        }
        return $b;
    }

    //
    function getDataForExport($post)
    {
        //
        $this->db->distinct();

        $this->db->select('timeoff_requests.sid');
        $this->db->select('timeoff_requests.employee_sid');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        // Where
        $this->db->where('timeoff_requests.company_sid', $post['companySid']);
        $this->db->where('timeoff_requests.is_draft', 0);
        $this->db->where('timeoff_requests.archive', $post['archive']);

        if (!in_array('all', $post['employees'])) $this->db->where_in('timeoff_requests.employee_sid', $post['employees']);
        if (!in_array('all', $post['status'])) $this->db->where_in('timeoff_requests.status', $post['status']);

        if ((!empty($post['fromDate']) || !is_null($post['fromDate'])) && (!empty($post['toDate']) || !is_null($post['toDate']))) {
            $this->db->where('timeoff_requests.request_from_date BETWEEN \'' . $post['fromDate'] . '\' AND \'' . $post['toDate'] . '\'');
        } else if ((!empty($post['fromDate']) || !is_null($post['fromDate'])) && (empty($post['toDate']) || is_null($post['toDate']))) {
            $this->db->where('timeoff_requests.request_from_date >=', $post['fromDate']);
        } else if ((empty($post['fromDate']) || is_null($post['fromDate'])) && (!empty($post['toDate']) || !is_null($post['toDate']))) {
            $this->db->where('timeoff_requests.request_from_date <=', $post['toDate']);
        }
        //
        $this->db->join("users", "users.sid = timeoff_requests.employee_sid", "inner");
        //
        $a = $this->db->get('timeoff_requests');
        $b = $a->result_array();
        $a->free_result();
        //
        return $b;
    }



    //


    //     //
    function getTimeOffByIds(
        $companySid,
        $requestIds
    ) {
        $r = array();
        //         //
        $this->db->select("
             timeoff_requests.sid as requestId,
             timeoff_requests.timeoff_policy_sid as policyId,
             timeoff_requests.employee_sid,
             timeoff_requests.requested_time,
             timeoff_requests.allowed_timeoff,
             timeoff_requests.request_from_date as requested_date,
             timeoff_requests.request_to_date,
             timeoff_requests.is_partial_leave,
             timeoff_requests.status,
             timeoff_requests.partial_leave_note,
             timeoff_requests.reason,
             timeoff_requests.level_at,
             timeoff_requests.timeoff_days,
             timeoff_policies.title as policy_title,
         ")
            ->from('timeoff_requests')
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->order_by('requested_date', 'ASC')
            ->order_by('status', 'DESC');
        //
        $this->db->where_in('timeoff_requests.sid', $requestIds);
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
            ->where('company_sid', $companySid)
            ->limit(1)
            ->get();
        //
        $slug = isset($a->row_array()['slug']) ? $a->row_array()['slug'] : 'H:M';
        $a->free_result();
        //


        foreach ($b as $k => $v) {
            // Check if teamlead is  assigned
            $a = $this->db
                ->where('timeoff_request_sid', $v['requestId'])
                ->where('role', 'teamlead')
                ->count_all_results('timeoff_request_assignment');
            if (!$a) {
                $a = $this->db
                    ->where('timeoff_request_sid', $v['requestId'])
                    ->where('role', 'supervisor')
                    ->count_all_results('timeoff_request_assignment');
                //
                $this->db
                    ->where('sid', $v['requestId'])
                    ->update(
                        'timeoff_requests',
                        array(
                            'level_at' => !$a ? 3 : 2
                        )
                    );
                $v['level_at'] = $b[$k]['level_at'] = !$a ? 3 : 2;
            }

            /*
            $a = $this->db
                ->where('timeoff_request_sid', $v['requestId'])
                ->count_all_results('timeoff_request_assignment');
            if (!$a) {
                unset($b[$k]);
                continue;
            }
            */


            // Fetch employee joining date
            $a = $this->db
                ->select('
                 joined_at,
                 first_name,
                 last_name,
                 access_level_plus,
                 access_level,
                 pay_plan_flag,
                 job_title,
                 is_executive_admin,
                 user_shift_hours, 
                 user_shift_minutes,
                 concat(first_name," ",last_name) as full_name,
                 profile_picture as img,
                 employee_number,
                 registration_date,
                 rehire_date,
                 employment_date,
                 employee_type
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
            $b[$k]['first_name'] = $a->row_array()['first_name'];
            $b[$k]['last_name'] = $a->row_array()['last_name'];
            $b[$k]['access_level'] = $a->row_array()['access_level'];
            $b[$k]['access_level_plus'] = $a->row_array()['access_level_plus'];
            $b[$k]['job_title'] = $a->row_array()['job_title'];
            $b[$k]['is_executive_admin'] = $a->row_array()['is_executive_admin'];
            $b[$k]['pay_plan_flag'] = $a->row_array()['pay_plan_flag'];
            $b[$k]['full_name'] = $a->row_array()['full_name'];
            $b[$k]['img'] = $a->row_array()['img'];
            $b[$k]['employee_number'] = $a->row_array()['employee_number'];

            //

            if ($a->row_array()['employment_date'] && ($a->row_array()['employee_type'] == 'fulltime' || $a->row_array()['employee_type'] == 'full-time')) {

                $joiningDate = $a->row_array()["employment_date"];
            } else {
                $joiningDate = get_employee_latest_joined_date($a->row_array()["registration_date"], $a->row_array()["joined_at"], "", false);
            }


            //
            if (!empty($joiningDate)) {
                $b[$k]['joining_date'] = $joiningDate;
            } else {
                $b[$k]['joining_date'] = "N/A";
            }

            $rehireDate = get_employee_latest_joined_date("", "", $a->row_array()["rehire_date"], false);
            //
            if (!empty($rehireDate)) {
                $b[$k]['rehire_date'] = $rehireDate;
            } else {
                $b[$k]['rehire_date'] = "N/A";
            }

            //
            $defaultTimeFrame = $employeeShiftHours + (round($employeeShiftMinutes / 60, 2));
            $a->free_result();
            // Fetch non responded employees
            $b[$k]['Progress']['UnResponded'] = $this->db
                ->where('timeoff_request_sid', $v['requestId'])
                ->where('is_reassigned', 0)
                ->where('is_responded', 0)
                ->count_all_results('timeoff_request_assignment');
            // Fetch responded employees
            $b[$k]['Progress']['Responded'] = $this->db
                ->where('timeoff_request_sid', $v['requestId'])
                ->where('is_reassigned', 0)
                ->where('is_responded', 1)
                ->count_all_results('timeoff_request_assignment');
            //
            $b[$k]['Progress']['Total'] = $b[$k]['Progress']['UnResponded'] + $b[$k]['Progress']['Responded'];
            $b[$k]['Progress']['CompletedPercentage'] = ceil(($b[$k]['Progress']['Responded'] / $b[$k]['Progress']['Total']) * 100);

            if ($v['status'] != 'pending') {
                $b[$k]['Progress']['CompletedPercentage'] = 100;
            }

            $b[$k]['policy_title'] = ucwords($v['policy_title']);
            //
            $b[$k]['timeoff_breakdown'] = get_array_from_minutes(
                $v['requested_time'],
                $defaultTimeFrame,
                $slug
            );
            $b[$k]['slug'] = $slug;
            $b[$k]['defaultTimeFrame'] = $defaultTimeFrame;

            // Time off Category
            $a = $this->db
                ->select('
                 category_name
             ')
                ->join('timeoff_categories', 'timeoff_policy_categories.timeoff_category_sid = timeoff_categories.sid', 'inner')
                ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
                ->where('timeoff_policy_categories.timeoff_policy_sid', $v['policyId'])
                ->order_by('timeoff_categories.sort_order', 'ASC')
                ->get('timeoff_policy_categories');
            //
            $b[$k]['Category'] = '';
            $aa = $a->row_array();
            $a->free_result();
            if (sizeof($aa)) $b[$k]['Category'] = $aa['category_name'];

            // Sort by policy
            // Let's fetch the history
            $b[$k]['History'] = $this->db
                ->select('
                 users.first_name,
                 users.last_name,
                 users.is_executive_admin,
                 users.job_title,
                 users.access_level,
                 users.access_level_plus,
                 users.pay_plan_flag,
                 timeoff_request_history.reason as comment,
                 timeoff_request_history.status
             ')
                ->join('users', 'users.sid = timeoff_request_assignment.employee_sid', 'inner')
                ->join('timeoff_request_history', 'timeoff_request_history.timeoff_request_assignment_sid = timeoff_request_assignment.sid', 'inner')
                ->where('timeoff_request_assignment.timeoff_request_sid', $v['requestId'])
                ->order_by('timeoff_request_assignment.sid', 'DESC')
                ->get('timeoff_request_assignment')
                ->result_array();
            // Attachments
            $b[$k]['Attachments'] = $this->db
                ->select('
                 document_title,
                 s3_filename,
                 is_archived
             ')
                ->where('timeoff_request_sid', $v['requestId'])
                ->order_by('sid', 'DESC')
                ->get('timeoff_attachments')
                ->result_array();

            // Sort by policy
            if (!isset($r[ucwords($v['policy_title'])])) $r[ucwords($v['policy_title'])] = ucwords($v['policy_title']);
        }

        return array_values($b);
    }

    /**
     * update employee tomeoffs requests
     * 
     * @Author  Aleem Shaukat
     * @date      15/05/2023
     * 
     * @param Integer $employeeID
     * @param Integer $oldPolicyId
     * @param Integer $newPolicyId
     * 
     * @return Array
     */
    function updateEmployeeRequestPolicy($employeeID, $oldPolicyId, $newPolicyId)
    {
        $this->db->where('employee_sid', $employeeID);
        $this->db->where('timeoff_policy_sid', $oldPolicyId);
        $this->db->update('timeoff_requests', [
            'timeoff_policy_sid' => $newPolicyId
        ]);

        // Move timeoff balances to new policy
        $this->updateEmployeeTimeoffBalance($employeeID, $oldPolicyId, $newPolicyId);
    }

    /**
     * get policy title
     * 
     * @Author  Aleem Shaukat
     * @date      15/05/2023
     * 
     * @param Integer $policyId
     * 
     * @return String
     */
    function getPolicyTitleById($policyId)
    {
        $this->db->select('title');
        $this->db->where('sid', $policyId);
        $record_obj = $this->db->get('timeoff_policies');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['title'];
        } else {
            return array();
        }
    }

    /**
     * Get policy employees
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
    ) {
        //
        $r = ['Balances' => [], 'Employees' => []];
        // If the employee is not a plus
        $inIds = [];
        // Check either the employee is a plus or not
        // If not then he/she can only see teams/departments
        if ($post['level'] != 1) {
            $inIds = $this->getEmployeeTeamMemberIds(
                $post['employerId']
            );
            //
            if (empty($inIds)) return $r;
        }
        // _e($post, true, true);
        // Fetch all active employees 
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            employee_status,
            employee_type, terminated_status,
            active,
            employment_date
        ')
            ->order_by('first_name', 'ASC')
            ->where('parent_sid', $post['companyId'])
            ->where('is_executive_admin', 0)
            ->limit($post['offset'], $post['inset']);
        //
        if (!empty($inIds)) $this->db->where_in('sid', $inIds);
        if ($post['filter']['employees'] != '' && $post['filter']['employees'] != 'all') $this->db->where('sid', $post['filter']['employees']);
        //
        if ($post["filter"]["all"]) {
            $this->db->where(getTheWhereFromEmployeeStatus($post["filter"]["all"]));
        } else {
            $this->db->where([
                "users.active" => 1,
                "users.terminated_status" => 0,
            ]);
        }
        //
        $a = $this->db->get('users');
        $employees = $a->result_array();
        $a->free_result();
        //
        if (empty($employees)) return $r;
        //
        $filterPolicies = [];
        //
        if (!is_array($post['filter']['policies'])) $post['filter']['policies'] = explode(',', $post['filter']['policies']);
        if (!empty($post['filter']['policies']) && !in_array('all', $post['filter']['policies'])) $filterPolicies = $post['filter']['policies'];
        //

        $settings = $this->getSettings($post['companyId']);
        $policies = $this->getCompanyPoliciesWithAccruals($post['companyId'], true, $filterPolicies);
        $balances = $this->getBalances($post['companyId']);
        // Loop through employees
        foreach ($employees as $k => $v) {
            //
            $v['anniversary_text'] = get_user_anniversary_date(
                $v['joined_at'],
                $v['registration_date'],
                $v['rehire_date']
            );
            //
            $r['Employees'][$v['userId']] = $v;
            //
            if (empty($policies)) {
                $r['Balances'][] = [
                    'UserId' => $v['userId'],
                    'AllowedTime' => 0,
                    'ConsumedTime' => 0,
                    'RemainingTime' => 0
                ];
            } else {
                //

                if ($v['employment_date'] && ($v['employee_type'] == 'fulltime' || $v['employee_type'] == 'full-time')) {

                    $JoinedDate = $v['employment_date'];
                } else {
                    $JoinedDate = get_employee_latest_joined_date($v['registration_date'], $v['joined_at'], $v['rehire_date']);
                }



                //
                // Fetch employee policies
                $r['Balances'][] =
                    $this->getBalanceOfEmployee(
                        $v['userId'],
                        $v['employee_status'],
                        $JoinedDate,
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

    /** Get the policy history
     * 
     * Get the time off policy change log with the 
     * difference of what was changed with who changed it
     * 
     * @param int $policyId
     * @param int $companyId
     * @return array
     */
    public function getPolicyHistoryWithDifference(int $policyId, int $companyId)
    {
        // set policy where
        $whereArray = [
            'sid' => $policyId,
            'company_sid' => $companyId
        ];
        // check if policy belong to current company
        if (!$this->db->where($whereArray)->count_all_results('timeoff_policies')) {
            return [];
        }
        // get the policy history
        $history = $this->db
            ->select('
            timeoff_policy_timeline.action, 
            timeoff_policy_timeline.action_type,
            timeoff_policy_timeline.created_at,
            timeoff_policy_timeline.note,
            ' . (getUserFields()) . '
        ')
            ->join('users', 'users.sid = timeoff_policy_timeline.employee_sid', 'inner')
            ->where('timeoff_policy_timeline.policy_sid', $policyId)
            ->where('timeoff_policy_timeline.action_type', 'current')
            ->order_by('timeoff_policy_timeline.sid', 'ASC')
            ->get('timeoff_policy_timeline')
            ->result_array();
        // check if there is no history
        if (!$history) {
            return [];
        }
        // get the current time off policy
        $currentPolicy = $this->db->select('
            title,
            assigned_employees,
            off_days,
            for_admin,
            is_included,
            accruals,
            is_entitled_employee,
            is_archived,
            allowed_approvers,
            policy_category_type
        ')
            ->where($whereArray)
            ->get('timeoff_policies')
            ->row_array();
        //
        $currentPolicyCompare = ['note' => json_encode($currentPolicy)];
        // set records array
        $records = [];
        // let's loop through the history
        foreach ($history as $index => $value) {
            // check if it was a first entry
            if ($value['action'] == 'create') {
                // set the record array
                $records[] = [
                    'action' => $value['action'],
                    'user' => remakeEmployeeName($value),
                    'difference' => [],
                    'created_at' => formatDateToDB($value['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME)
                ];
                // then skip the iteration
                continue;
            }
            //
            $compareWithArray = $currentPolicyCompare;
            // check if there are next index
            if (isset($history[$index + 1])) {
                $compareWithArray = $history[$index + 1];
            }

            if (preg_match('/trasnsferred/i', $value['note'])) {
                // lets compare the array
                $differenceArray = getPolicyDifference($value, $currentPolicyCompare);
            } else {

                // lets compare the array
                $differenceArray = getPolicyDifference($value, $compareWithArray);
            }
            //
            if ($differenceArray) {
                // set the record array
                $records[] = [
                    'action' => $value['action'],
                    'user' => remakeEmployeeName($value),
                    'difference' => $differenceArray,
                    'created_at' => formatDateToDB($value['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME)
                ];
            }
        }
        // return the results
        return array_reverse($records);
    }


    /**
     * Get policy employees
     * 
     * @author  Aleem Shaukat
     * @date      15/0521/2023
     * 
     * @param  Integer $policyId 
     * 
     * @return Array
     */
    function getPolicyEmployees(
        $policyId,
        $companyId
    ) {
        $policy = $this->getCompanyPolicyWithAccruals($companyId, true, $policyId);
        //
        if (empty($policy)) {
            return [];
        }
        //
        // Fetch all active employees 
        $this->db->select('
            ' . (getUserFields()) . '
            joined_at,
            registration_date,
            rehire_date,
            employee_status,
            employee_type,
            employment_date
        ')
            ->order_by('first_name', 'ASC')
            ->where('parent_sid', $companyId)
            ->where('active', 1)
            ->where('is_executive_admin', 0)
            ->where('terminated_status', 0);
        //
        $a = $this->db->get('users');
        $employees = $a->result_array();
        $a->free_result();
        //
        if (empty($employees)) return [];
        //
        $balances = $this->getBalances($companyId);
        $settings = $this->getSettings($companyId);
        //
        foreach ($employees as $ekey => $v) {
            //
            $employees[$ekey]['anniversary_text'] = get_user_anniversary_date(
                $v['joined_at'],
                $v['registration_date'],
                $v['rehire_date']
            );
            //

            if ($v['employment_date'] && ($v['employee_type'] == 'fulltime' || $v['employee_type'] == 'full-time')) {

                $JoinedDate = $v['employment_date'];
            } else {
                $JoinedDate = get_employee_latest_joined_date($v['registration_date'], $v['joined_at'], $v['rehire_date']);
            }


            //
            $employeeTimeoffInfo =
                $this->getBalanceOfEmployee(
                    $v['userId'],
                    $v['employee_status'],
                    $JoinedDate,
                    (($v['user_shift_hours'] * 60) + $v['user_shift_minutes']),
                    $settings['slug'],
                    $policy,
                    $balances,
                    $v['employee_type']
                )['total'];
            //
            if (empty($employeeTimeoffInfo['PolicyIds'])) {
                unset($employees[$ekey]);
            } else {
                //
                $employees[$ekey]['allowed_time'] = $employeeTimeoffInfo['AllowedTime']['text'];
                $employees[$ekey]['remaining_time'] = $employeeTimeoffInfo['RemainingTime']['text'];
                $employees[$ekey]['consumed_time'] = $employeeTimeoffInfo['ConsumedTime']['text'];
                $employees[$ekey]['remaining_minutes'] = $employeeTimeoffInfo['RemainingTime']['M']['minutes'];
            }
            //
        }
        //
        return $employees;
    }

    /**
     * Get policies with accruals
     * 
     * @employee  Aleem Shaukat
     * @date      15/05/2023
     * 
     * @param  Integer $companyId
     * @param  Integer $policyId
     * 
     * @return Array
     */
    function getCompanyPolicyWithAccruals(
        $companyId,
        $withInclude = TRUE,
        $policyId
    ) {
        //
        $this->db
            ->select('
            timeoff_policies.sid,
            timeoff_policies.title,
            timeoff_policies.accruals,
            timeoff_policies.assigned_employees,
            timeoff_policies.is_entitled_employee,
            timeoff_policies.off_days,
            timeoff_policies.is_included,
            timeoff_policies.for_admin,
            timeoff_policies.default_policy,
            timeoff_category_list.category_name,
            timeoff_policies.policy_category_type as category_type,
            timeoff_policies.allowed_approvers
        ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_policies.company_sid', $companyId)
            ->where('timeoff_policies.is_archived', 0)
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        if ($withInclude) $this->db->where('timeoff_policies.is_included', 1);
        $this->db->where('timeoff_policies.sid', $policyId);
        //
        $policies = $this->db->get('timeoff_policies')
            ->result_array();
        //
        return $policies;
    }


    /**
     * Get policy requests by id
     * 
     * @employee  Aleem Shaukat
     * @date      12/05/2023
     * 
     * @param int $policyId
     * @param bool $isActive
     * 
     * @return array
     */
    function getPolicyRequestsWithEmployees($policyId, bool $isActive = true)
    {
        $this->db
            ->select('
            ' . (getUserFields()) . '
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.requested_time
        ')
            ->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner')
            ->where('timeoff_requests.timeoff_policy_sid', $policyId);
        //
        if ($isActive) {
            $this->db->where('users.active', 1);
            $this->db->where('users.terminated_status', 0);
        }
        return $this->db
            ->get('timeoff_requests')
            ->result_array();
    }

    /**
     * Get all active policy by company id
     * 
     * @employee  Aleem Shaukat
     * @date      12/05/2023
     * 
     * @param int  $companyId
     * @param bool $isActive
     * 
     * @return array
     */
    function getAllPolicies(int $companyId, bool $isActive = true)
    {
        $this->db
            ->select('
            sid,
            title,
            is_archived,
            policy_category_type
        ')
            ->where('company_sid', $companyId)
            ->order_by('policy_category_type', 'DESC');
        //
        if ($isActive) {
            $this->db->where('is_archived', 0);
        }
        //
        return $this->db->get('timeoff_policies')
            ->result_array();
    }




    /**
     * update employee tomeoffs balances
     * 
     * @Author  Nisar Ahmad
     * @date      20/06/2023
     * 
     * @param Integer $employeeID
     * @param Integer $oldPolicyId
     * @param Integer $newPolicyId
     * 
     * @return Array
     */
    function updateEmployeeTimeoffBalance($employeeID, $oldPolicyId, $newPolicyId)
    {
        $this->db->where('user_sid', $employeeID);
        $this->db->where('policy_sid', $oldPolicyId);
        $this->db->update('timeoff_balances', [
            'policy_sid' => $newPolicyId
        ]);
    }

    public function getEmployeeRequestCountAgainstPolicy(int $policyId, int $employeeId): int
    {
        return $this->db
            ->where('timeoff_policy_sid', $policyId)
            ->where('employee_sid', $employeeId)
            ->count_all_results('timeoff_requests');
    }

    public function getPolicyDetailsById(int $policyId, array $column = ['*']): array
    {
        return $this->db
            ->select($column)
            ->where('sid', $policyId)
            ->get('timeoff_policies')
            ->row_array();
    }

    public function checkAndAddType(int $typeId, int $companyId): int
    {
        // get the catgeory
        $category = $this->db
            ->where('sid', $typeId)
            ->get('timeoff_categories')
            ->row_array();
        // get the type main id
        $record = $this->db
            ->select('sid')
            ->where('timeoff_category_list_sid', $category['timeoff_category_list_sid'])
            ->where('company_sid', $companyId)
            ->get('timeoff_categories')
            ->row_array();
        // check if found
        if ($record) {
            return $record['sid'];
        }
        unset($category['sid']);
        // change the id
        $category['company_sid'] = $companyId;
        //
        $this->db->insert('timeoff_categories', $category);
        //
        return $this->db->insert_id();
    }

    public function getEmployeeRequests(int $employeeId, int $policyId): array
    {
        //
        return $this->db
            ->where('employee_sid', $employeeId)
            ->where('timeoff_policy_sid', $policyId)
            ->get('timeoff_requests')
            ->result_array();
    }

    public function getEmployeeBalances(int $employeeId, int $policyId): array
    {
        //
        return $this->db
            ->where('user_sid', $employeeId)
            ->where('policy_sid', $policyId)
            ->get('timeoff_balances')
            ->result_array();
    }

    public function getRequestApproverComment(int $requestId, int $employeeId): string
    {
        //
        $record = $this->db
            ->where('comment')
            ->where('request_sid', $requestId)
            ->where('employee_sid', $employeeId)
            ->get('timeoff_request_timeline')
            ->row_array();
        //
        return $record ? $record['comment'] : '';
    }


    //
    function updateEmployeePoliciesNew(
        $companyId,
        $employerId,
        $policyIds
    ) {
        //
        $this->db
            ->select('
        timeoff_policies.sid,
        timeoff_policies.title,
        timeoff_policies.assigned_employees,
        timeoff_policies.is_entitled_employee
        ')
            ->where('timeoff_policies.company_sid', $companyId)
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        $policies = $this->db->get('timeoff_policies')
            ->result_array();

        //
        if (empty($policies)) return false;
        //

        //
        $data['session'] = $this->session->userdata('logged_in');
        $employer_id = $data["session"]["employer_detail"]["sid"];

        foreach ($policies as $policy) {
            //
            $oldPolicy = $this->timeoff_model->getSinglePolicyById($policy['sid']);
            $isAddedHistory = "no";
            //
            if (in_array($policy['sid'], $policyIds)) {
                //
                if ($policy['is_entitled_employee'] == 1) {
                    //
                    if ($policy['assigned_employees'] != 'all') {
                        //
                        $assignedEmployeesArry = [];
                        //
                        if ($policy['assigned_employees'] != 0) {
                            $assignedEmployeesArry = explode(',', $policy['assigned_employees']);
                        }
                        //check if employee already assigned
                        array_push($assignedEmployeesArry, $employerId);
                        $assignedEmployeesArry = array_unique($assignedEmployeesArry);
                        $assignedEmployeesNew = implode(',', $assignedEmployeesArry);
                        // Update Recorde
                        $this->updateTimeoffAssignedEmployees($policy['sid'], $companyId, [
                            'assigned_employees' => $assignedEmployeesNew
                        ]);
                        //
                        $isAddedHistory = "yes";
                    }
                } else {
                    //is_entitled_employee==0
                    if ($policy['assigned_employees'] == 'all' || $policy['assigned_employees'] == '0') {
                        //
                        $this->updateTimeoffAssignedEmployees($policy['sid'], $companyId, [
                            'assigned_employees' => $employerId,
                            'is_entitled_employee' => 1
                        ]);
                        //
                        $isAddedHistory = "yes";
                    }
                }
            } else {
                if ($policy['is_entitled_employee'] == 1) {
                    if ($policy['assigned_employees'] == 'all') {
                        //
                        $this->updateTimeoffAssignedEmployees($policy['sid'], $companyId, [
                            'assigned_employees' => $employerId,
                            'is_entitled_employee' => 0
                        ]);
                        //
                        $isAddedHistory = "yes";
                    } else {
                        $assignedEmployeesArry = [];
                        //
                        if ($policy['assigned_employees'] != 0) {
                            $assignedEmployeesArry = explode(',', $policy['assigned_employees']);
                        }
                        //
                        if (in_array($employerId, $assignedEmployeesArry)) {
                            //
                            if (($key = array_search($employerId, $assignedEmployeesArry)) !== false) {
                                unset($assignedEmployeesArry[$key]);
                                $assignedEmployeesNew = implode(',', $assignedEmployeesArry);
                                //Update 
                                $this->updateTimeoffAssignedEmployees($policy['sid'], $companyId, [
                                    'assigned_employees' => $assignedEmployeesNew
                                ]);
                                //
                                $isAddedHistory = "yes";
                            }
                        }
                    }
                } else {
                    if ($policy['assigned_employees'] != 'all') {
                        $assignedEmployeesArry = [];
                        //
                        if ($policy['assigned_employees'] != 0 && $policy['assigned_employees']) {
                            $assignedEmployeesArry = explode(',', $policy['assigned_employees']);
                        }
                        //
                        $assignedEmployeesArry[] = $employerId;
                        $assignedEmployeesArry = array_unique($assignedEmployeesArry);
                        $assignedEmployeesArry = implode(',', $assignedEmployeesArry);
                        //
                        $this->updateTimeoffAssignedEmployees($policy['sid'], $companyId, [
                            'assigned_employees' => $assignedEmployeesArry
                        ]);
                        //
                        $isAddedHistory = "yes";
                    }
                }
            }
            //
            if ($isAddedHistory == "yes") {
                // Lets save who history of the policy
                $in = [];
                $in['policy_sid'] = $policy['sid'];
                $in['employee_sid'] = $employer_id;
                $in['action'] = 'update';
                $in['note'] = json_encode($oldPolicy);
                //
                $this->timeoff_model->insertPolicyHistory($in);
            }
        }
    }


    // 
    function updateTimeoffAssignedEmployees($ploicyID, $companyId, $data)
    {
        $this->db->where('sid', $ploicyID);
        $this->db->where('company_sid', $companyId);
        $this->db->update('timeoff_policies', $data);
    }

    /**
     * Get the employees time off within dates
     * 
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     */
    public function getEmployeeTimeOffsInRange(
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        // get the timeoffs ids within range
        $records = $this->db
            ->select("
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.reason,
            timeoff_policies.title
        ")
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->where("timeoff_requests.employee_sid", $employeeId)
            ->group_start()
            ->group_start()
            ->where('timeoff_requests.request_from_date <=', $startDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date >=', $startDate)
            ->where('timeoff_requests.request_to_date <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->group_end()
            ->group_end()
            ->where('timeoff_requests.status', 'approved')
            ->get("timeoff_requests")
            ->result_array();

        //
        if (!$records) {
            return [];
        }

        $returnArray = [];

        foreach ($records as $v0) {
            // get the dates in range
            $datesPool = getDatesBetweenDates(
                $v0["request_from_date"],
                $v0["request_to_date"]
            );
            //
            foreach ($datesPool as $v1) {
                //
                if ($v1["date"] >= $startDate && $v1["date"] <= $endDate) {
                    $returnArray[$v1["date"]] = [
                        "reason" => $v0["reason"],
                        "title" => $v0["title"]
                    ];
                }
            }
        }
        return $returnArray;
    }

    /**
     * Get the employees time off within dates
     * 
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     */
    public function getEmployeePaidTimeOffsInRange(
        int $employeeId,
        string $startDate,
        string $endDate
    ): array {
        // get the timeoffs ids within range
        $records = $this->db
            ->select("
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.reason,
            timeoff_policies.title,
            timeoff_requests.timeoff_days
        ")
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->where("timeoff_policies.policy_category_type", 1)
            ->where("timeoff_requests.employee_sid", $employeeId)
            ->group_start()
            ->group_start()
            ->where('timeoff_requests.request_from_date <=', $startDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date >=', $startDate)
            ->where('timeoff_requests.request_to_date <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->group_end()
            ->group_end()
            ->where('timeoff_requests.status', 'approved')
            ->get("timeoff_requests")
            ->result_array();

        //
        if (!$records) {
            return [];
        }

        $returnArray = [
            "polices" => [],
            "total_hours" => 0
        ];

        foreach ($records as $record) {
            $timeoffDays = json_decode($record['timeoff_days'], true)['days'];
            //
            foreach ($timeoffDays as $dayInfo) {
                $timeoffDate = DateTime::createFromFormat('m-d-Y', $dayInfo['date'])->format('Y-m-d');
                //
                if ($timeoffDate >= $startDate && $timeoffDate <= $endDate) {
                    if (!$returnArray['polices'][$record['title']]) {
                        $returnArray['polices'][$record['title']] = [
                            'title' => $record['title'],
                            'hours' => ($dayInfo['time'] / 60),
                            'day_count' => 1,
                            'days' => [
                                [
                                    'date' => $timeoffDate,
                                    'hours' => ($dayInfo['time'] / 60)
                                ]
                            ]
                        ];
                    } else {
                        $returnArray['polices'][$record['title']]['hours'] += ($dayInfo['time'] / 60);
                        $returnArray['polices'][$record['title']]['day_count'] += 1;
                        $returnArray['polices'][$record['title']]['days'][] = [
                            'date' => $timeoffDate,
                            'hours' => ($dayInfo['time'] / 60)
                        ];
                    }
                    //
                    $returnArray['total_hours'] += ($dayInfo['time'] / 60);
                    $returnArray['total_days'] += 1;
                    //
                }
            }
        }
        //
        return $returnArray;
    }

    /**
     * Get and set Google holidays
     */
    public function holidayShifter($companyId, $publicHolidays)
    {
        //
        $year = date('Y', strtotime('now'));
        //
        foreach ($publicHolidays as $holiday) {
            //
            $ins = [];
            $holidaySlug = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($holiday['holiday_title'])));
            //
            $this->db->select('*');
            $this->db->where(
                "LOWER(REGEXP_REPLACE(holiday_title, '[^a-zA-Z]', '')) = ",
                $holidaySlug
            );
            $this->db->where('company_sid', $companyId);
            $this->db->order_by('holiday_year', 'DESC');
            $record_obj = $this->db->get('timeoff_holidays');
            $previousHoliday = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!$previousHoliday) {
                // fresh insert
                $ins['company_sid'] = $companyId;
                $ins['creator_sid'] = 0;
                $ins['holiday_year'] = $holiday['holiday_year'];
                $ins['holiday_title'] = $holiday['holiday_title'];
                $ins['frequency'] = 'yearly';
                $ins['icon'] = "";
                $ins['from_date'] = $holiday['from_date'];
                $ins['to_date'] = $holiday['to_date'];
                $ins['event_link'] = $holiday['event_link'];
                $ins['status'] = $holiday['status'];
                $ins['is_public'] = 1;
                $ins['is_archived'] = 0;
                $ins['sort_order'] = 1;
                $ins['work_on_holiday'] = 1;
                $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                //
                $this->db->insert('timeoff_holidays', $ins);
                //
            } elseif ($previousHoliday['holiday_year'] != $year) {
                $ins = $previousHoliday;
                //
                unset($ins['sid'], $ins['created_at'], $ins['updated_at']);
                //
                $ins['holiday_year'] = $year;
                $ins['event_link'] = $holiday['event_link'];
                $ins['from_date'] = $holiday['from_date'];
                $ins['to_date'] = $holiday['to_date'];
                $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                //
                $this->db->insert('timeoff_holidays', $ins);
            }
        }
    }

    public function getPublicHolidays()
    {

        //company_modules
        $this->db->select('company_sid');
        $this->db->where('module_sid', 1);
        $this->db->where('company_sid', 8578);
        // $this->db->where('is_active', 1);
        $record_obj = $this->db->get('company_modules');
        $timeoffCompanies = $record_obj->result_array();
        $record_obj->free_result();
        //
        if ($timeoffCompanies) {
            // Get public holidays
            $publicHolidays = getCurrentYearHolidaysFromGoogle();

            if (!$publicHolidays) {
                exit("No public holidays found!");
            }
            //
            foreach ($timeoffCompanies as $value) {
                $this->holidayShifter($value['company_sid'], $publicHolidays);
            }
        }
        //
        exit("Done");
    }

    /**
     * Get the employees time off within dates
     * 
     * @param int $employeeId
     * @param string $startDate
     * @param string $endDate
     */
    public function getEmployeesTimeOffsInRange(
        array $employeeId,
        string $startDate,
        string $endDate
    ): array {
        // get the timeoffs ids within range
        $records = $this->db
            ->select("
            timeoff_requests.request_from_date,
            timeoff_requests.request_to_date,
            timeoff_requests.reason,
            timeoff_requests.employee_sid,
            timeoff_policies.title
        ")
            ->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner')
            ->where_in("timeoff_requests.employee_sid", $employeeId)
            ->group_start()
            ->group_start()
            ->where('timeoff_requests.request_from_date <=', $startDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date >=', $startDate)
            ->where('timeoff_requests.request_to_date <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('timeoff_requests.request_from_date <=', $endDate)
            ->where('timeoff_requests.request_to_date >=', $startDate)
            ->group_end()
            ->group_end()
            ->where('timeoff_requests.status', 'approved')
            ->get("timeoff_requests")
            ->result_array();

        //
        if (!$records) {
            return [];
        }

        $returnArray = [];

        foreach ($records as $v0) {
            // get the dates in range
            $datesPool = getDatesBetweenDates(
                $v0["request_from_date"],
                $v0["request_to_date"]
            );
            //
            foreach ($datesPool as $v1) {
                //
                if ($v1["date"] >= $startDate && $v1["date"] <= $endDate) {
                    $returnArray[$v0["employee_sid"]][$v1["date"]] = [
                        "reason" => $v0["reason"],
                        "title" => $v0["title"],
                    ];
                }
            }
        }
        return $returnArray;
    }




    //
    function getEmployeeTimeoffRequest($company_sid, $employeeSid, $start_date, $end_date, $filter_policy)
    {
        //
        $this->db->select('timeoff_requests.sid');
        //
        $this->db->where('company_sid', $company_sid);

        if ($start_date != '' && $end_date != '') {
            $this->db->where('request_from_date >=', date('Y-m-d', strtotime($start_date)));
            $this->db->where('request_from_date <=', date('Y-m-d', strtotime($end_date)));
        }

        if (is_array($employeeSid)) {
            $this->db->where_in('employee_sid', $employeeSid);
        } else {
            $this->db->where('employee_sid', $employeeSid);
        }

        $this->db->where('archive', 0);
        $this->db->where('is_draft', 0);
        $records_obj = $this->db->get('timeoff_requests');
        $records_arr = $records_obj->result_array();

        return $records_arr;
    }

    //
    public function getEmployeeRequestsPrevious(int $employeeId)
    {
        //
        return $this->db
            ->where('employee_sid', $employeeId)
            ->get('timeoff_requests')
            ->result_array();
    }


    //
    public function getPreviousPlicyTitle(int $companyId, int $policyId)
    {
        //
        return $this->db
            ->select('title')
            ->where('company_sid', $companyId)
            ->where('sid', $policyId)
            ->get('timeoff_policies')
            ->row_array();
    }

    //
    public function getPreviousPlicySid(int $companyId, string $title)
    {
        //
        return $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->where('title', $title)
            ->get('timeoff_policies')
            ->row_array();
    }

    //
    function checkEmployeeTimeoffRequestExist($employeeSid, $startDate, $endDate, $time)
    {
        //
        $response = [
            'message' => 'No timeoff already exist on same date.',
            'code' => 1
        ];
        //
        $records = $this->db
            ->select("
            request_from_date,
            request_to_date,
            status,
            timeoff_days
        ")
            ->where("employee_sid", $employeeSid)
            ->group_start()
            ->group_start()
            ->where('request_from_date <=', $startDate)
            ->where('request_to_date >=', $startDate)
            ->where('request_from_date <=', $endDate)
            ->where('request_to_date >=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('request_from_date >=', $startDate)
            ->where('request_to_date <=', $endDate)
            ->group_end()
            ->or_group_start()
            ->where('request_from_date <=', $endDate)
            ->where('request_to_date >=', $startDate)
            ->group_end()
            ->group_end()
            ->where('status <>', "rejected")
            ->get("timeoff_requests")
            ->result_array();

        //
        if (!$records) {
            return $response;
        }
        //
        $conflictCount = 0;
        $conflictStatus = 'pending';
        $newMessage = '<strong>You have {{conflict_count}} conflicts with time off requests:</strong><br><br>';
        $alreadyAppliedTime = [];
        $shortLeaveStatus = false;
        $shortLeaveText = '';
        //
        foreach ($records as $v0) {
            //
            $timeoffDays = json_decode($v0['timeoff_days'], true)['days'];
            //
            foreach ($timeoffDays as $v1) {
                //
                if (!isset($alreadyAppliedTime[$v1["date"]])) {
                    $alreadyAppliedTime[$v1["date"]] = $v1["time"];
                } else {
                    $alreadyAppliedTime[$v1["date"]] = $alreadyAppliedTime[$v1["date"]] + $v1["time"];
                }
                //
                // $requestDate = DateTime::createfromformat('m-d-Y', $v1["date"])->format('Y-m-d');
                $requestDate = checkAndFixDateFormat($v1["date"], 'Y-m-d');
                //
                if ($requestDate >= $startDate && $requestDate <= $endDate) {
                    //
                    foreach ($time['days'] as $day) {
                        //
                        if ($day['date'] == $v1["date"]) {
                            //
                            if ($v1["time"] >= 480 || ($v1["time"] + $day['time']) > 480) {
                                //
                                $conflictCount++;
                                //
                                if ($v0['status'] == 'approved') {
                                    $conflictStatus = 'approved';
                                    $newMessage .= formatDateToDB($requestDate, DB_DATE, DATE) . ' ' . ($v1["time"] / 60) . ' hours (<span class="text-success"><b>' . strtoupper($v0['status']) . '</b></span>)<br>';
                                } else {
                                    $newMessage .= formatDateToDB($requestDate, DB_DATE, DATE) . ' ' . ($v1["time"] / 60) . ' hours (<span class="text-warning"><b>' . strtoupper($v0['status']) . '</b></span>)<br>';
                                }
                                //
                            } else if ($v1['time'] < 480) {
                                if ($v0['status'] == 'approved') {
                                    $conflictStatus = 'approved';
                                    $shortLeaveText .= formatDateToDB($requestDate, DB_DATE, DATE) . ' ' . ($v1["time"] / 60) . ' hours (<span class="text-success"><b>' . strtoupper($v0['status']) . '</b></span>)<br>';
                                } else {
                                    $shortLeaveText .= formatDateToDB($requestDate, DB_DATE, DATE) . ' ' . ($v1["time"] / 60) . ' hours (<span class="text-warning"><b>' . strtoupper($v0['status']) . '</b></span>)<br>';
                                }
                                //
                                if ($alreadyAppliedTime[$v1["date"]] >= 480) {
                                    $conflictCount++;
                                    $shortLeaveStatus = true;
                                }
                            }
                        }
                    }
                }
            }
        }
        //
        if ($shortLeaveStatus) {
            $newMessage .= $shortLeaveText;
        }
        //
        if ($conflictCount > 0) {
            //
            if ($conflictStatus == 'approved') {
                $newMessage = '<strong class="text-danger">You can not create this time off request due to following conflict(s).</strong><br><br>' . $newMessage;
            } else {
                $newMessage = $newMessage . '<br><br><b>Do you still want to continue?</b>';
            }
            //
            $response = [
                'message' => str_replace('{{conflict_count}}', $conflictCount, $newMessage),
                'code' => 2,
                'conflictStatus' => $conflictStatus
            ];
        }

        //
        return $response;
    }

    //
    function checkRequestAlreadyApproved($requestId)
    {
        //
        $timeOff = $this->db
            ->select("
            employee_sid,
            request_from_date,
            request_to_date,
            requested_time
        ")
            ->where("sid", $requestId)
            ->get("timeoff_requests")
            ->row_array();
        //
        $response = [
            'message' => 'No timeoff approved on same date.',
            'code' => 1
        ];
        //
        $records = $this->db
            ->select("
            sid,
            requested_time
        ")
            ->where("employee_sid", $timeOff['employee_sid'])
            ->where('request_from_date', $timeOff['request_from_date'])
            ->where('request_to_date', $timeOff['request_to_date'])
            ->where('status', 'approved')
            ->where('sid <>', $requestId)
            ->get("timeoff_requests")
            ->result_array();

        //
        if (!$records) {
            return $response;
        }
        //
        $previousTime = 0;
        //
        if (count($records) > 1) {
            foreach ($records as $request) {
                $previousTime = $previousTime + $request['requested_time'];
            }
        } else {
            $previousTime = $records[0]['requested_time'];
        }
        //
        if ($previousTime < 480 && ($timeOff['requested_time'] + $previousTime) <= 480) {
            return $response;
        } else {
            $info = $this->fetchRequestHistoryInfo($records[0]['sid']);
            //
            $response = [
                'message' => 'Another time off request by <b>' . getUserNameBySID($timeOff['employee_sid']) . '</b> for same date(s) already has been approved by <b>' . getUserNameBySID($info['employee_sid']) . '</b>.',
                'code' => 2
            ];
            //
            return $response;
        }
    }


    //
    function setHolidays($companyId)
    {

        $this->db
            ->from('timeoff_holidays')
            ->where('company_sid', $companyId)
            ->where('holiday_year', date('Y'));

        //
        $holidaysCount = $this->db->count_all_results();


        if ($holidaysCount == 0) {

            // Get public holidays
            $publicHolidays = getCurrentYearHolidaysFromGoogle();
            //
            $ph = [];
            //
            if ($publicHolidays) {
                foreach ($publicHolidays as $holiday) {
                    $ph[preg_replace('/[^a-zA-Z]/', '', strtolower(trim($holiday['holiday_title'])))] = $holiday;
                }
            }
            //
            $holidays = $this->findAndGetCompanyHolidays($companyId);
            //
            $nf = [
                'found' => 0,
                'notFound' => 0
            ];
            //
            if (empty($holidays)) {
                // No holiday of companies are found
                foreach ($publicHolidays as $pb) {
                    $ins = [];
                    //
                    $ins['company_sid'] = $companyId;
                    $ins['creator_sid'] = getCompanyAdminSid($companyId);
                    $ins['holiday_year'] = $pb["holiday_year"];
                    $ins['holiday_title'] = $pb["holiday_title"];
                    $ins['from_date'] = $pb['from_date'];
                    $ins['to_date'] = $pb['from_date'];
                    $ins['event_link'] = $pb['event_link'];
                    $ins['status'] = $pb["status"];
                    $ins['is_public'] = 1;
                    $ins['created_at'] = $ins['updated_at'] = getSystemDate();
                    //
                    $this->db->insert('timeoff_holidays', $ins);
                }
            } else {
                //
                $year = date('Y', strtotime('now'));
                $lastYear = date('Y', strtotime('-1 year'));
                //
                foreach ($holidays as $holiday) {
                    //
                    $title = preg_replace('/[^a-zA-Z]/', '', strtolower(trim($holiday['holiday_title'])));
                    //
                    $doExists = $this->db
                        ->where([
                            'holiday_title' => $holiday['holiday_title'],
                            'company_sid' => $holiday['company_sid'],
                            'holiday_year' => $year
                        ])
                        ->count_all_results('timeoff_holidays');
                    //
                    if (
                        $doExists
                    ) {
                        $nf['found']++;
                        continue;
                    }
                    //
                    $ins = $holiday;
                    //
                    unset($ins['sid'], $ins['created_at'], $ins['updated_at']);
                    //
                    $ins['holiday_year'] = $year;
                    $ins['created_at'] = $ins['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
                    //
                    if (isset($ph[$title])) {
                        $ins['from_date'] = $ph[$title]['from_date'];
                        $ins['to_date'] = $ph[$title]['to_date'];
                    } else {
                        $ins['from_date'] = preg_replace("/$lastYear/", $year, $ins['from_date']);
                        $ins['to_date'] = preg_replace("/$lastYear/", $year, $ins['to_date']);
                    }
                    //
                    $this->db->insert('timeoff_holidays', $ins);
                    //
                    $nf['notFound']++;
                }
            }
            //
        }
    }

    /**
     * get the company holidays
     *
     * @param int $companyId
     * @return array
     */
    private function findAndGetCompanyHolidays(int $companyId): array
    {
        // get last year of company
        $record = $this->db
            ->where("company_sid", $companyId)
            ->order_by("holiday_year", "DESC")
            ->limit(1)
            ->get("timeoff_holidays")
            ->row_array();
        //
        if (!$record) {
            return [];
        }
        // Get all companies
        return $this->db
            ->where([
                "company_sid" => $companyId,
                "holiday_year" => $record["holiday_year"]
            ])
            ->get('timeoff_holidays')
            ->result_array();
    }

    public function getTimeOffPolicyType($policyId, $companyId)
    {

        $this->db
            ->select('
            timeoff_category_list.category_name
        ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_policies.company_sid', $companyId)
            ->where('timeoff_policies.is_archived', 0)
            ->order_by('timeoff_policies.sort_order', 'ASC');
        //
        $this->db->where('timeoff_policies.sid', $policyId);
        //
        $policyInfo = $this->db->get('timeoff_policies')
            ->row_array();
        //
        return $policyInfo['category_name'];
    }
}
