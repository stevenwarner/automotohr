<?php

class copy_policies_model extends CI_Model
{

    private $companyId;
    private $employeeId;
    private $dateTime;
    private $catArray;

    function __construct()
    {
        parent::__construct();
        $this->companyId = 0;
        $this->employeeId = 0;
        $this->dateTime = date('Y-m-d H:i:s', strtotime('now'));
        $this->catArray = [];
    }

    function getAllCompanies($active = 1)
    {
        $result = $this->db
            ->select('sid as company_id, CompanyName as title')
            ->where('parent_sid', 0)
            ->where('active', $active)
            ->where('is_paid', 1)
            ->where('career_page_type', 'standard_career_site')
            ->order_by('CompanyName', 'ASC')
            ->get('users');
        //
        $companies = $result->result_array();
        $result = $result->free_result();
        //
        return $companies;
    }


    function getCompanyPolicies($formpost, $limit)
    {
        $start = $formpost['page'] == 1 ? 0 : ($formpost['page'] * $limit) - $limit;
        $this->db
            ->select('
            timeoff_policies.sid as policie_id, 
            timeoff_policies.title, 
            timeoff_policies.is_archived,
            timeoff_category_list.category_name as category,
            "policy" as type
        ')
            ->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policies.type_sid', 'inner')
            ->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner')
            ->where('timeoff_policies.company_sid', $formpost['fromCompanyId'])
            ->order_by('timeoff_policies.title')
            ->limit($limit, $start);

        if ($formpost['status'] != 'all') $this->db->where('timeoff_policies.is_archived', $formpost['status']);

        $result = $this->db->get('timeoff_policies');
        //
        $policies = $result->result_array();
        $result = $result->free_result();
        //
        $returnArray = array('policies' => $policies, 'PoliciesCount' => 0);
        if ($formpost['page'] == 1 && !sizeof($policies)) return $returnArray;
        if ($formpost['page'] != 1) return $returnArray;
        //
        $this->db
            ->where('company_sid', $formpost['fromCompanyId'])
            ->order_by('title');
        if ($formpost['status'] != 'all') $this->db->where('is_archived', $formpost['status']);
        $returnArray['PoliciesCount'] = $this->db->count_all_results('timeoff_policies');
        //
        return $returnArray;
    }

    function checkPolicyCopied($formpost)
    {
        return $this->db
            ->where('from_company_sid', $formpost['fromCompanyId'])
            ->where('to_company_sid', $formpost['toCompanyId'])
            ->where('policy_sid', $formpost['policy']['policy_id'])
            ->where('policy_category', $formpost['policy']['policy_category'])
            ->count_all_results('copy_timeoff_policy_track');
    }

    function movePolicy($formpost, $id)
    {
        // Fetch document details
        $result = $this->db
            ->select('*')
            ->where('sid', $formpost['policy']['policy_id'])
            ->get('timeoff_policies');
        //
        $policy = $result->row_array();
        $result = $result->free_result();
        //
        if (!sizeof($policy)) {
            return false;
        }
        //
        $creatorId = $this->getCompanyCreator($formpost['toCompanyId']);
        $typeSid = $this->checkPolicyType($policy['type_sid'], $formpost['toCompanyId'], $creatorId);
        // Check
        if (!$this->db->where([
            'lower(title)' => strtolower($policy['title']),
            'company_sid' => $formpost['toCompanyId']
        ])->count_all_results('timeoff_policies')) {
            //
            unset(
                $policy['sid'],
                $policy['created_at'],
                $policy['updated_at']
            );
            //
            $this->db->trans_begin();
            //
            $policy['creator_sid'] = $creatorId;
            $policy['assigned_employees'] = 0;
            $policy['company_sid'] = $formpost['toCompanyId'];
            $policy['type_sid'] = $typeSid;
            //
            $this->db->insert('timeoff_policies', $policy);
            $policyId = $this->db->insert_id();
            //
            $insertArray = array();
            $insertArray['admin_sid'] = $id;
            $insertArray['policy_sid'] = $formpost['policy']['policy_id'];
            $insertArray['to_company_sid'] = $formpost['toCompanyId'];
            $insertArray['new_policy_sid'] = $policyId;
            $insertArray['from_company_sid'] = $formpost['fromCompanyId'];
            $insertArray['policy_category'] = $formpost['policy']['policy_category'];
            //
            // $this->db->insert('copy_timeoff_policy_track', $insertArray);

            if (!$policyId) {
                return $this->db->trans_rollback();
            } else $this->db->trans_commit();
        }
        //
        return true;
    }

    private function checkPolicyType($typeId, $companyId, $creatorId)
    {
        //
        $result = $this->db
            ->select('timeoff_category_list_sid')
            ->where('sid', $typeId)
            ->get('timeoff_categories');
        //
        $category = $result->row_array();
        $result = $result->free_result();

        if (empty($category)) {
            return 0;
        }
        //
        $categoryArray =  $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->where('timeoff_category_list_sid', $category['timeoff_category_list_sid'])
            ->get('timeoff_categories')
            ->row_array();

        //
        if (empty($categoryArray)) {
            //
            $cat = [];
            $cat['company_sid'] = $companyId;
            $cat['timeoff_category_list_sid'] = $category['timeoff_category_list_sid'];
            $cat['creator_sid'] = $creatorId;
            $cat['status'] = 1;
            $cat['is_archived'] = 0;
            $cat['is_default'] = 0;
            $cat['default_type'] = 0;
            $cat['created_at'] =
                $cat['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $this->db->insert('timeoff_categories', $cat);
            //
            return $this->db->insert_id();
        }
        //
        return $categoryArray['sid'];
    }

    public function addDefaultPolicies($companyId)
    {
        //
        $result = $this->db
            ->select('*')
            ->where('company_sid', 11276)
            ->where('is_archived', 0)
            ->where('status', 1)
            ->get('timeoff_policies');
        //
        $policies = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($policies)) {
            return false;
        }
        //
        $creatorId = $this->getCompanyCreator($companyId);
        //
        foreach ($policies as $policy) {
            //
            if (!$this->db->where([
                'lower(title)' => strtolower($policy['title']),
                'company_sid' => $companyId
            ])->count_all_results('timeoff_policies')) {
                //
                $typeSid = $this->checkPolicyType($policy['type_sid'], $companyId, $creatorId);
                //
                unset(
                    $policy['sid'],
                    $policy['created_at'],
                    $policy['updated_at']
                );
                //
                $this->db->trans_begin();
                //
                $policy['creator_sid'] = $creatorId;
                $policy['assigned_employees'] = 0;
                $policy['company_sid'] = $companyId;
                $policy['type_sid'] = $typeSid;
                //
                $this->db->insert('timeoff_policies', $policy);
                $policyId = $this->db->insert_id();
                //
                if (!$policyId) {
                    return $this->db->trans_rollback();
                } else $this->db->trans_commit();
            }
        }
    }

    public function getCompanyCreator($companyId)
    {
        $result = $this->db
            ->select('sid')
            ->where('parent_sid', $companyId)
            ->group_start()
            ->where('access_level_plus', 1)
            ->or_where('access_level', 'Admin')
            ->group_end()
            ->order_by('access_level_plus', 'desc')
            ->get('users');
        //
        $employeesList = $result->result_array();
        $result = $result->free_result();
        //
        if (!empty($employeesList[0])) {
            return $employeesList[0]["sid"];
        }
        //
        $result = $this->db
            ->select('sid')
            ->where('parent_sid', $companyId)
            ->limit(1)
            ->get('users');
        //
        $employeeInfo = $result->row_array();
        $result = $result->free_result();
        //
        if (!empty($employeeInfo)) {
            return $employeeInfo["sid"];
        }
        //
        return 0;
    }

    public function copyTimeOff($fromCompanyId, $toCompanyId, $adminId)
    {
        // Set company id
        $this->companyId = $toCompanyId;
        // Get creator id
        $this->employeeId = $this->getCompanyCreator($toCompanyId);
        // Copy categories
        $this->checkAndAddPolicyTypes($fromCompanyId);
        // Copy policies
        $this->checkAndAddPolicies($fromCompanyId);
        // Copy holidays
        $this->addCompanyHolidays($fromCompanyId);
        // Copy settings
        $this->updateCompanyTimeOffSettings($fromCompanyId);
    }


    /**
     * Check and add time off stuff
     *
     * @author Mubashir Ahmed
     * @param int $companyId
     */
    public function checkAndAddDefaultPolicies($companyId)
    {
        // Set company id
        $this->companyId = $companyId;
        // Set type
        $this->employeeId = $this->getCompanyCreator($this->companyId);
        // Check and add types
        $this->checkAndAddPolicyTypes();
        // Check and add policies
        $this->checkAndAddPolicies();
        // Check and add setting
        $this->checkAndAddSettings();
        // Check and add holidays
        $this->checkAndAddHolidays();
    }

    /**
     * Check and add time off types
     *
     * @author Mubashir Ahmed
     */
    private function checkAndAddPolicyTypes($companyId = 0)
    {
        //
        if ($companyId != 0) {
            $categories = $this->getCompanyTypes($companyId);
        } else {
            // Load all the types
            $categories = json_decode(
                loadFileData(ROOTPATH . '../protected_files/timeoff/categories.json'),
                true
            );
        }
        //
        foreach ($categories as $category) {
            //
            $id = $category['sid'];
            //
            if (!$this->db->where([
                'company_sid' => $this->companyId,
                'timeoff_category_list_sid' => $category['timeoff_category_list_sid']
            ])->count_all_results('timeoff_categories')) {
                //
                unset($category['sid']);
                $category['company_sid'] = $this->companyId;
                $category['creator_sid'] = $this->employeeId;
                $category['created_at'] = $category['updated_at'] = $this->dateTime;
                //
                $this->db->insert(
                    'timeoff_categories',
                    $category
                );
                $this->catArray[$id] = $this->db->insert_id();
            } else {
                //
                $this->db
                    ->where([
                        'company_sid' => $this->companyId,
                        'timeoff_category_list_sid' => $category['timeoff_category_list_sid']
                    ])->update('timeoff_categories', [
                        'status' => $category['status'],
                        'is_archived' => $category['is_archived'],
                        'sort_order' => $category['sort_order']
                    ]);
                //
                $row = $this->db->select('sid')->where([
                    'company_sid' => $this->companyId,
                    'timeoff_category_list_sid' => $category['timeoff_category_list_sid']
                ])
                    ->get('timeoff_categories')
                    ->row_array();
                //
                $this->catArray[$id] = $row['sid'];
            }
        }
    }

    /**
     * Check and add time off policies
     *
     * @author Mubashir Ahmed
     */
    private function checkAndAddPolicies($companyId = 0)
    {
        if ($companyId != 0) {
            $policies = $this->getCompanyPoliciesById($companyId);
        } else {
            // Load all the types
            $policies = json_decode(
                loadFileData(ROOTPATH . '../protected_files/timeoff/policies.json'),
                true
            );
        }
        //
        foreach ($policies as $policy) {
            //
            $policyRecord =
                $this->db
                ->select('sid')
                ->where([
                    'company_sid' => $this->companyId,
                    'LOWER(title)' => strtolower($policy['title'])
                ])
                ->get('timeoff_policies')
                ->row_array();
            //
            if (empty($policyRecord)) {
                //
                unset(
                    $policy['sid']
                );
                $policy['company_sid'] = $this->companyId;
                $policy['creator_sid'] = $this->employeeId;
                $policy['assigned_employees'] = 0;
                $policy['type_sid'] = $this->catArray[$policy['type_sid']];
                $policy['created_at'] = $policy['updated_at'] = $this->dateTime;
                //
                $this->db->insert(
                    'timeoff_policies',
                    $policy
                );
            } else {
                //
                $newPolicy = [];
                $newPolicy['type_sid'] = $this->catArray[$policy['type_sid']];
                $newPolicy['title'] = $policy['title'];
                $newPolicy['off_days'] = $policy['off_days'];
                $newPolicy['is_default'] = $policy['is_default'];
                $newPolicy['for_admin'] = $policy['for_admin'];
                $newPolicy['is_archived'] = $policy['is_archived'];
                $newPolicy['is_unlimited'] = $policy['is_unlimited'];
                $newPolicy['status'] = $policy['status'];
                $newPolicy['sort_order'] = $policy['sort_order'];
                $newPolicy['reset_policy'] = $policy['reset_policy'];
                $newPolicy['accruals'] = $policy['accruals'];

                $this->db
                    ->where([
                        'sid' => $policyRecord['sid']
                    ])
                    ->update('timeoff_policies', $newPolicy);
            }
        }
    }

    /**
     * Check and add time off settings
     *
     * @author Mubashir Ahmed
     */
    private function checkAndAddSettings()
    {
        //
        if (!$this->db->where([
            'company_sid' => $this->companyId
        ])->count_all_results('timeoff_settings')) {
            // set
            $ia = [];
            $ia['company_sid'] = $this->companyId;
            $ia['default_timeslot'] = 8;
            $ia['pto_approval_check'] = 0;
            $ia['pto_email_receiver'] = 0;
            $ia['created_at'] = $this->dateTime;
            $ia['accural_type'] = 'per year';
            $ia['accrue_start_day'] = 0;
            $ia['timeoff_type'] = 'per year';
            $ia['is_lose_active'] = 0;
            $ia['accrue_start_date'] = NULL;
            $ia['timeoff_format_sid'] = 2;
            $ia['send_email_to_supervisor'] = 0;
            $ia['off_days'] = '';
            $ia['theme'] = 2;
            $ia['team_visibility_check'] = 1;
            // insert
            $this->db->insert('timeoff_settings', $ia);
            $this->db->where('parent_sid', $this->companyId)
                ->update('timeoff_settings', [
                    'user_shift_hours' => 8,
                    'user_shift_minutes' => 0
                ]);
        }
    }

    /**
     * Check and add time off holidays
     *
     * @author Mubashir Ahmed
     */
    private function checkAndAddHolidays()
    {
        //
        $year = date('Y', strtotime('now'));
        // Check if current year holidays
        // exists
        if (!$this->db->where([
            'holiday_year' => $year
        ])->count_all_results('timeoff_holiday_list')) {
            // Lets get the holidays
            $this->getCurrentYearHolidaysFromGoogle();
        }
        //
        $publicHolidays = $this->db
            ->select(['holiday_title', 'holiday_year', 'from_date', 'to_date', 'event_link', 'status'])
            ->where([
                'holiday_year' => $year
            ])
            ->get('timeoff_holiday_list')
            ->result_array();
        //
        foreach ($publicHolidays as $holiday) {
            //
            if ($this->db->where([
                'company_sid' => $this->companyId,
                'holiday_year' => $year,
                'LOWER(holiday_title)' => strtolower($holiday['holiday_title'])
            ])->count_all_results('timeoff_holidays')) {
                continue;
            }
            //
            $holiday['company_sid'] = $this->companyId;
            $holiday['creator_sid'] = $this->employeeId;
            $holiday['frequency'] = 'year';
            $holiday['icon'] = '';
            $holiday['is_public'] = 1;
            $holiday['work_on_holiday'] = 0;
            $holiday['created_at'] = $holiday['updated_at'] = $this->dateTime;
            //
            $this->db->insert('timeoff_holidays', $holiday);
        }
    }


    /**
     * Get current year holidays
     *
     * @author Mubashir Ahmed
     */
    private function getCurrentYearHolidaysFromGoogle()
    {
        //
        $holidays = json_decode(
            getFileData("https://www.googleapis.com/calendar/v3/calendars/en.usa%23holiday%40group.v.calendar.google.com/events?key=" . (getCreds('AHR')->GoogleAPIKey) . ""),
            true
        )['items'];
        // Extract current year holidays
        $holidays = array_filter(
            $holidays,
            function ($holiday) {
                $year = date('Y');
                return preg_match("/$year/", $holiday['start']['date']);
            }
        );
        //
        $year = date('Y');
        // Let's insert to database
        foreach ($holidays as $holiday) {
            //
            $ia = [];
            $ia['holiday_year'] = $year;
            $ia['holiday_title'] = trim($holiday['summary']);
            $ia['from_date'] = trim($holiday['start']['date']);
            $ia['to_date'] = trim($holiday['end']['date']);
            $ia['event_link'] = trim($holiday['htmlLink']);
            $ia['status'] = trim($holiday['status']);
            $ia['icon'] = NULL;
            $ia['created_at'] = $ia['updated_at'] = $this->dateTime;
            //
            if (!$this->db->where([
                'holiday_title' => $ia['holiday_title'],
                'holiday_year' => $year
            ])->count_all_results('timeoff_holiday_list')) {
                //
                $this->db->insert(
                    'timeoff_holiday_list',
                    $ia
                );
            }
        }
    }

    /**
     *
     */
    private function getCompanyTypes($companyId)
    {
        //
        return $this->db
            ->where('company_sid', $companyId)
            ->get('timeoff_categories')
            ->result_array();
    }

    /**
     *
     */
    private function getCompanyPoliciesById($companyId)
    {
        //
        return $this->db
            ->where('company_sid', $companyId)
            ->get('timeoff_policies')
            ->result_array();
    }

    /**
     *
     */
    private function updateCompanyTimeOffSettings($companyId)
    {
        //
        $ia =
            $this->db
            ->where('company_sid', $companyId)
            ->get('timeoff_settings')
            ->row_array();
        //
        if (!$ia) {
            // set
            $ia = [];
            $ia['default_timeslot'] = 8;
            $ia['pto_approval_check'] = 0;
            $ia['pto_email_receiver'] = 0;
            $ia['accural_type'] = 'per year';
            $ia['accrue_start_day'] = 0;
            $ia['timeoff_type'] = 'per year';
            $ia['is_lose_active'] = 0;
            $ia['accrue_start_date'] = null;
            $ia['timeoff_format_sid'] = 2;
            $ia['send_email_to_supervisor'] = 0;
            $ia['off_days'] = '';
            $ia['theme'] = 2;
            $ia['team_visibility_check'] = 1;
        } else {
            unset($ia['sid'], $ia['created_at'], $ia['company_sid']);
        }
        //
        $ia['company_sid'] = $this->companyId;
        $ia['created_at'] = $this->dateTime;
        // Check if already exists
        if (!$this->db->where([
            'company_sid' => $this->companyId
        ])->count_all_results('timeoff_settings')) {
            // Insert
            return $this->db
                ->insert('timeoff_settings', $ia);
        }
        // Update
        $this->db
            ->where('company_sid', $this->companyId)
            ->update('timeoff_settings', $ia);

        //
        $this->db
            ->where('parent_sid', $this->companyId)
            ->update('users', [
                'user_shift_hours' => $ia['default_timeslot'],
                'user_shift_minutes' => 0
            ]);
    }

    private function addCompanyHolidays($companyId)
    {
        //
        $year = date('Y', strtotime('now'));
        //
        $holidays = $this->db
            ->where([
                'company_sid' => $companyId,
                'holiday_year' => $year
            ])
            ->get('timeoff_holidays')
            ->result_array();
        //
        if (empty($holidays)) {
            //
            $holidays = $this->db
                ->where([
                    'company_sid' => $companyId,
                    'holiday_year' => $year - 1
                ])
                ->get('timeoff_holidays')
                ->result_array();
        }
        // Check for current year holidays
        if (!$this->db->where([
            'holiday_year' => $year
        ])->count_all_results('timeoff_holiday_list')) {
            // Lets get the holidays
            $this->getCurrentYearHolidaysFromGoogle();
        }
        //
        $publicHolidays = $this->db
            ->where('holiday_year', $year)
            ->get('timeoff_holiday_list')
            ->result_array();
        //
        $ph = [];
        //
        if ($publicHolidays) {
            foreach ($publicHolidays as $holiday) {
                $ph[$holiday['holiday_title']] = $holiday;
            }
        }
        if (!empty($holidays)) {
            // Flush the old records
            $this->db
                ->where('company_sid', $this->companyId)
                ->delete('timeoff_holidays');
            //
            foreach ($holidays as $holiday) {
                //
                $ia = $holiday;
                unset(
                    $ia['sid']
                );
                //
                $ia['company_sid'] = $this->companyId;
                $ia['holiday_year'] = $year;
                $ia['created_at'] =
                    $ia['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
                // Check for public holiday
                if (isset($ph[$holiday['holiday_title']])) {
                    $ii = $ph[$holiday['holiday_title']];
                    // public holiday
                    $ia['from_date'] = $ii['from_date'];
                    $ia['to_date'] = $ii['to_date'];
                } else {
                    // company holiday
                    $ia['from_date'] = preg_replace('/[0-9]{4}/', $year, $ia['from_date']);
                    $ia['to_date'] = preg_replace('/[0-9]{4}/', $year, $ia['to_date']);
                }

                // check if already exits
                if (!$this->db->where([
                    'company_sid' => $this->companyId,
                    'holiday_title' => $holiday['holiday_title'],
                    'holiday_year' => $year
                ])->count_all_results('timeoff_holidays')) {
                    // insert
                    $this->db->insert('timeoff_holidays', $ia);
                }
            }
        } else {
            //
            foreach ($publicHolidays as $holiday) {
                //
                $ia = [];
                //
                $ia['company_sid'] = $this->companyId;
                $ia['holiday_title'] = $holiday['holiday_title'];
                $ia['holiday_year'] = $year;
                $ia['from_date'] = $holiday['from_date'];
                $ia['to_date'] = $holiday['to_date'];
                $ia['event_link'] = $holiday['event_link'];
                $ia['status'] = $holiday['status'];
                $ia['created_at'] = $ia['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));

                // check if already exits
                if (!$this->db->where([
                    'company_sid' => $this->companyId,
                    'holiday_title' => $holiday['holiday_title'],
                    'holiday_year' => $year
                ])->count_all_results('timeoff_holidays')) {
                    // insert
                    $this->db->insert('timeoff_holidays', $ia);
                } else {
                    //
                    unset($ia['company_sid'], $ia['created_at']);
                    //
                    $this->db->where([
                        'company_sid' => $this->companyId,
                        'holiday_title' => $holiday['holiday_title'],
                        'holiday_year' => $year
                    ])->update('timeoff_holidays', $ia);
                }
            }
        }
    }
}
