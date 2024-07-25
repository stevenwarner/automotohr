<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("payroll/Base_payroll_model", "base_payroll_model");
/**
 * Employee payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Create_partner_company_model extends Base_payroll_model
{
    /**
     * main function
     */
    public function __construct()
    {
    }

    /**
     * set company details
     *
     * @param int $companyId
     * @param string $column Optional
     */
    public function setCompanyDetails(
        int $companyId,
        string $column = "company_sid"
    ) {
        //
        $this
            ->getGustoLinkedCompanyDetails(
                $companyId,
                [
                    "company_sid",
                    "employee_ids"
                ],
                true,
                $column
            );
        //
        $this->initialize($companyId);
        return $this;
    }

    /**
     * check company onboard
     *
     * @param int $companyId
     * @return string
     */
    public function getCompanyOnboardLastStep(int $companyId): string
    {
        $record = $this->db
            ->select('is_ts_accepted')
            ->where([
                'company_sid' => $companyId
            ])
            ->get('gusto_companies')
            ->row_array();
        //
        if (!$record) {
            return 'onboard';
        } elseif (!$record['is_ts_accepted']) {
            return 'terms';
        }
        return 'done';
    }

    /**
     * get company employees for payroll
     *
     * @param int $companyId
     * @return array
     */
    public function getEmployeesForPayroll(int $companyId, array $where = [
        'users.active' => 1,
        'users.is_executive_admin' => 0,
        'users.employee_type != ' => 'contractual',
        'users.terminated_status' => 0
    ]): array
    {
        // Fetch employees
        $employees = $this->getCompanyEmployees($companyId, [
            "users.sid",
            "users.first_name",
            "users.last_name",
            "users.access_level",
            "users.access_level_plus",
            "users.is_executive_admin",
            "users.job_title",
            "users.ssn",
            "users.dob",
            "users.email",
            "users.middle_name",
            "users.pay_plan_flag",
            "users.on_payroll",
            'users.PhoneNumber',
            'gusto_companies_employees.gusto_uuid',
        ], $where);
        //
        $tmp = [];
        //
        if ($employees) {
            //
            foreach ($employees as $employee) {
                //
                if ($employee['payroll_employee_uuid']) {
                    continue;
                }
                //
                $missingFields = [];
                //
                if (!$employee['first_name']) {
                    $missingFields[] = 'First Name';
                }
                //
                if (!$employee['last_name']) {
                    $missingFields[] = 'Last Name';
                }
                //
                $employee['ssn'] = preg_replace('/\D/', '', $employee['ssn']);
                //
                if (strlen($employee['ssn']) != 9) {
                    $missingFields[] = 'Social Security Number';
                }
                //
                if (!$employee['dob']) {
                    $missingFields[] = 'Date Of Birth';
                }
                //
                if (!$employee['email']) {
                    $missingFields[] = 'Email';
                }
                //
                $tmp[] = [
                    'sid' => $employee['sid'],
                    'full_name_with_role' => remakeEmployeeName($employee),
                    'first_name' => $employee['first_name'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'phone_number' => $employee['PhoneNumber'],
                    'missing_fields' => $missingFields
                ];
            }
        }
        return $tmp;
    }

    /**
     * check payroll admin of a company
     *
     * @param int $companyId
     * @return bool
     */
    public function checkAdminForPayroll(int $companyId): bool
    {
        //
        return (bool) $this->db
            ->where([
                'company_sid' => $companyId,
                'is_store_admin' => 0
            ])
            ->count_all_results('gusto_companies_admin');
    }

    /**
     * get active employees
     *
     * @param int $companyId
     * @return
     */
    public function getActiveEmployees(int $companyId): array
    {
        return $this->db
            ->select(getUserFields())
            ->where('parent_sid', $companyId)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->where('access_level <>', 'employee')
            ->get('users')
            ->result_array();
    }

    /**
     * check and add admin
     *
     * @param array $post
     * @param int   $companyId
     * @return int
     */
    public function checkAndSaveAdmin(array $post, int $companyId): int
    {
        // check if already exists
        if ($this->db->where([
            'company_sid' => $companyId,
            'is_store_admin' => 0,
            'email_address' => $post['email']
        ])->count_all_results('gusto_companies_admin')) {
            return 0;
        }
        // add the admin
        $this->db->insert(
            'gusto_companies_admin',
            [
                'company_sid' => $companyId,
                'gusto_uuid' => null,
                'first_name' => $post['firstName'],
                'last_name' => $post['lastName'],
                'automotohr_reference' => $post['id'],
                'email_address' => $post['email'],
                'is_store_admin' => 0,
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]
        );
        // retrieve the last id
        return $this->db->insert_id();
    }

    /**
     * get payroll admin of a company
     *
     * @param int $companyId
     * @return bool
     */
    public function getAdminForPayroll(int $companyId): array
    {
        //
        return $this->db
            ->select('first_name, email_address, last_name')
            ->where([
                'company_sid' => $companyId,
                'is_store_admin' => 0
            ])
            ->get('gusto_companies_admin')
            ->row_array();
    }

    /**
     * get company employees
     *
     * @param int $companyId
     * @param array $columns
     * @param array $whereArray
     * @return array
     */
    public function getCompanyEmployees(int $companyId, array $columns = ['*'], array $whereArray = []): array
    {
        //
        $whereArray = !empty($whereArray) ? $whereArray : ["users.active" => 1, "users.terminated_status" => 0];
        //
        $redo = false;
        //
        if ($columns === true) {
            //
            $redo = true;
            //
            $columns = [];
            $columns[] = "users.sid";
            $columns[] = "users.first_name";
            $columns[] = "users.last_name";
            $columns[] = "users.email";
            $columns[] = "users.joined_at";
            $columns[] = "users.registration_date";
            $columns[] = "users.ssn";
            $columns[] = "users.timezone";
            $columns[] = "company.timezone as company_timezone";
            $columns[] = "users.dob";
            $columns[] = "users.profile_picture";
            $columns[] = "users.access_level";
            $columns[] = "users.access_level_plus";
            $columns[] = "users.user_shift_hours";
            $columns[] = "users.user_shift_minutes";
            $columns[] = "users.is_executive_admin";
            $columns[] = "users.job_title";
            $columns[] = "users.pay_plan_flag";
            $columns[] = "users.full_employment_application";
            $columns[] = "users.on_payroll";
            $columns[] = "gusto_companies_employees.gusto_uuid";
            $columns[] = "gusto_companies_employees.is_onboarded";
        }
        //
        $query =
            $this->db
            ->select($columns)
            ->join("users as company", "users.parent_sid = company.sid", 'inner')
            ->join("gusto_companies_employees", "gusto_companies_employees.employee_sid = users.sid", 'left')
            ->where("users.parent_sid", $companyId)
            ->where("gusto_companies_employees.gusto_uuid is null", null, null)
            ->where($whereArray)
            ->order_by("users.first_name", 'asc')
            ->get('users');
        //
        $records = $query->result_array();
        //
        $query = $query->free_result();
        //
        if ($redo && !empty($records)) {
            //
            $newRecords = [];
            //
            $updateArray = [];
            //
            foreach ($records as $record) {
                //
                $newRecords[$record['sid']] = [
                    'sid' => $record['sid'],
                    'timezone' => STORE_DEFAULT_TIMEZONE_ABBR,
                    'first_name' => ucwords($record['first_name']),
                    'last_name' => ucwords($record['last_name']),
                    'name' => ucwords($record['first_name'] . ' ' . $record['last_name']),
                    'role' => remakeEmployeeName($record, false),
                    'plus' => $record['access_level_plus'],
                    'email' => $record['email'],
                    'image' => getImageURL($record['profile_picture']),
                    'joined_on' => $record['joined_at'],
                    'ssn' => $record['ssn'],
                    'dob' => $record['dob'],
                    'shift_hours' => $record['user_shift_hours'],
                    'shift_minutes' => $record['user_shift_minutes'],
                    'on_payroll' => $record['on_payroll'],
                    'gusto_uuid' => $record['gusto_uuid'],
                    'onboard_completed' => $record['onboard_completed'],
                ];
                //
                if (!empty($record['timezone'])) {
                    $newRecords[$record['sid']]['timezone'] = $record['timezone'];
                } elseif (!empty($record['company_timezone'])) {
                    $newRecords[$record['sid']]['timezone'] = $record['company_timezone'];
                }
                //
                if (!empty($record['full_employment_application'])) {
                    //
                    $fef = unserialize($record['full_employment_application']);
                    //
                    if (empty($newRecords[$record['sid']]['ssn']) && isset($fef['TextBoxSSN'])) {
                        $newRecords[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                        //
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['ssn'] = $fef['TextBoxSSN'];
                    }
                    //
                    if (empty($newRecords[$record['sid']]['dob']) && isset($fef['TextBoxDOB'])) {
                        $newRecords[$record['sid']]['dob'] = DateTime::createfromformat('m-d-Y', $fef['TextBoxDOB'])->format('Y-m-d');
                        $updateArray[$record['sid']]['sid'] = $record['sid'];
                        $updateArray[$record['sid']]['dob'] = $newRecords[$record['sid']]['dob'];
                    }
                }
            }
            //
            if (!empty($updateArray)) {
                $this->db->update_batch($this->U, $updateArray, 'sid');
            }
            //
            $records = $newRecords;
            //
            unset($newRecords);
        }
        //
        return $records;
    }


    
}    