<?php defined('BASEPATH') || exit('No direct script access allowed');

class Payroll_model extends CI_Model
{
    // set AutomotoHR admin
    private $adminArray;

    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
        // load the payroll helper
        $this->load->helper('v1/payroll_helper');
        // set the admin
        $this->adminArray = [
            'first_name' => 'Steven',
            'last_name' => 'Warner',
            'email_address' => 'steven@automotohr.com',
            'phone_number' => '3331234569',
        ];
    }


    /**
     * check company requirements for onboard
     *
     * @param int $companyId
     * @return array
     */
    public function checkCompanyRequirements(int $companyId): array
    {
        //
        $r = [];
        //
        $record = $this->db
            ->select('ssn, Location_Address, Location_City, Location_State, Location_ZipCode, PhoneNumber')
            ->where('sid', $companyId)
            ->get('users')
            ->row_array();
        //
        if (!$record['ssn']) {
            $r[] = 'Social Security Number (SSN) is missing.';
        }
        //
        if (!$record['Location_Address']) {
            $r[] = 'Company address is missing.';
        }
        //
        if (!$record['Location_City']) {
            $r[] = 'Company city is missing.';
        }
        //
        if (!$record['Location_State']) {
            $r[] = 'Company state is missing.';
        }
        //
        if (!$record['Location_ZipCode']) {
            $r[] = 'Company zip code is missing.';
        } //
        if (!$record['PhoneNumber']) {
            $r[] = 'Company phone number code is missing.';
        }
        //
        return $r;
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
            $columns[] = "payroll_employees.onboard_completed";
        }
        //
        $query =
            $this->db
            ->select($columns)
            ->join("users as company", "users.parent_sid = company.sid", 'inner')
            ->join("payroll_employees", "payroll_employees.employee_sid = users.sid", 'left')
            ->where("users.parent_sid", $companyId)
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

    /**
     * get company employees for payroll
     *
     * @param int $companyId
     * @return array
     */
    public function getEmployeesForPayroll(int $companyId): array
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
            'payroll_employees.payroll_employee_uuid',
        ], [
            'users.active' => 1,
            'users.is_executive_admin' => 0,
            'users.terminated_status' => 0
        ]);
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
                $employee['ssn'] = preg_replace('/[^0-9]/', '', $employee['ssn']);
                //
                if (!preg_match('/[0-9]{9}/', $employee['ssn'])) {
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
     * get payroll employees
     */
    public function getPayrollEmployees(int $companyId): array
    {
        //
        $records = $this->db
            ->select(
                getUserFields() . 'is_onboarded'
            )
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where('gusto_companies_employees.company_sid', $companyId)
            ->get('gusto_companies_employees')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $employee) {
            $tmp[] = [
                'name' => remakeEmployeeName($employee),
                'is_onboard' => $employee['is_onboarded'],
                'id' => $employee['userId'],
            ];
        }
        //
        return $tmp;
    }

    /**
     * Get gusto company details for gusto
     *
     * @param int   $companyId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    public function getCompanyDetailsForGusto(int $companyId, array $extra = [], bool $include = true): array
    {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'refresh_token',
            'access_token'
        ], $extra) : $extra;
        //
        return $this->db
            ->select($columns)
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
    }

    /**
     * Get gusto employees details for gusto
     *
     * @param int   $employeeId
     * @param array $extra Optional
     * @param bool  $include Optional
     * @return array
     */
    public function getEmployeeDetailsForGusto(int $employeeId, array $extra = [], bool $include = true): array
    {
        //
        $columns = $include ? array_merge([
            'gusto_uuid',
            'gusto_version',
        ], $extra) : $extra;
        //
        return $this->db
            ->select($columns)
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
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
                'email_address' => $post['email'],
                'is_store_admin' => 0,
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]
        );
        // retrieve the last id
        return $this->db->insert_id();
    }

    // Create partner company process

    /**
     * create partner company on Gusto
     *
     * @param int $companyId
     * @param array $employees
     * @return array
     */
    public function startCreatePartnerCompany(int $companyId, array $employees): array
    {
        // set default return array
        $returnArray = ['success' => true];
        // check if company is already onboard
        if (!$this->checkIfCompanyAlreadyPartnered($companyId)) {
            // company needs to be created first
            $response = $this->createPartnerCompany($companyId);
            //
            if ($response['errors']) {
                return $response;
            }
        }
        // saves the employees list
        $this->db
            ->where('company_sid', $companyId)
            ->update('gusto_companies', [
                'employee_ids' => implode(',', $employees)
            ]);
        // push the admins to Gusto
        $this->checkAndSetPayrollStoreAdmin($companyId);
        // sync the admins
        $this->syncPayrollAdmins($companyId);
        // return
        return $returnArray;
    }

    /**
     * set the store admin against company
     *
     * @param int $companyId
     * @return int
     */
    private function checkAndSetPayrollStoreAdmin(int $companyId): int
    {
        // set where array
        $whereArray = [
            'company_sid' => $companyId,
            'is_store_admin' => 1,
            'email_address' => $this->adminArray['email_address']
        ];
        // check if already pushed
        if ($this->db->where($whereArray)->count_all_results('gusto_companies_admin')) {
            return 0;
        }
        // insert the admin
        $this->db->insert(
            'gusto_companies_admin',
            [
                'company_sid' => $companyId,
                'is_store_admin' => 1,
                'first_name' => $this->adminArray['first_name'],
                'last_name' => $this->adminArray['last_name'],
                'email_address' => $this->adminArray['email_address'],
                'gusto_uuid' => null,
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]
        );
        // inserted id
        return $this->db->insert_id();
    }

    /**
     * sync payroll admins
     *
     * @param int $companyId
     * @return bool
     */
    public function syncPayrollAdmins(int $companyId): bool
    {
        // get the company
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // sync Gusto admins with store (only UUID)
        $this->syncGustoToStore($companyId, $companyDetails);
        // sync store admins with GUSTO
        $this->syncStoreToGusto($companyId, $companyDetails);
        //
        return true;
    }

    /**
     * sync Gusto admins to store
     *
     * @param int $companyId
     * @param array $companyDetails
     * @return bool
     */
    private function syncGustoToStore(int $companyId, array $companyDetails): bool
    {
        // get the admins
        $admins = $this->db
            ->select('
                sid,
                first_name,
                last_name,
                email_address,
                gusto_uuid
            ')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_admin')
            ->result_array();
        // check if admins are found
        if (!$admins) {
            return false;
        }
        // fetch all admins from Gusto
        $gustoAdmins = getAdminsFromGusto($companyDetails);
        // check for errors
        $errors = hasGustoErrors($gustoAdmins);
        // error occurred
        if ($errors) {
            return $errors;
        }
        // remake
        $gustoAdmins = covertArrayToObject($gustoAdmins, 'email');
        // loop through
        foreach ($admins as $admin) {
            // check and set gusto uuids
            if ($gustoAdmins[$admin['email_address']]) {
                $this->db->where('sid', $admin['sid'])
                    ->update('gusto_companies_admin', [
                        'gusto_uuid' => $gustoAdmins[$admin['email_address']]['uuid']
                    ]);
            }
        }
        //
        return true;
    }

    /**
     * sync store admins to Gusto
     *
     * @param int $companyId
     * @param array $companyDetails
     * @return bool
     */
    private function syncStoreToGusto(int $companyId, array $companyDetails): bool
    {
        // get the admins
        $admins = $this->db
            ->select('
                sid,
                first_name,
                last_name,
                email_address,
                gusto_uuid
            ')
            ->where('company_sid', $companyId)
            ->group_start()
            ->where('gusto_uuid is null', null)
            ->or_where('gusto_uuid', '')
            ->group_end()
            ->get('gusto_companies_admin')
            ->result_array();
        // check if admins are found
        if (!$admins) {
            return false;
        }
        // loop through
        foreach ($admins as $admin) {
            // set request
            $request = [];
            $request['first_name'] = $admin['first_name'];
            $request['last_name'] = $admin['last_name'];
            $request['email'] = $admin['email_address'];
            // make call
            $gustoResponse = createAdminOnGusto($request, $companyDetails);
            // check for errors
            $errors = hasGustoErrors($gustoResponse);
            //
            if ($errors) {
                // block the iteration
                continue;
            }
            // update the UUID
            $this->db
                ->where('sid', $admin['sid'])
                ->update('gusto_companies_admin', [
                    'gusto_uuid' => $gustoResponse['uuid'],
                    'updated_at' => getSystemDate()
                ]);
        }
        //
        return true;
    }

    /**
     * check if company already partnered with Gusto
     *
     * @param int $companyId
     * @return bool
     */
    private function checkIfCompanyAlreadyPartnered(int $companyId): bool
    {
        return $this->db
            ->where('company_sid', $companyId)
            ->count_all_results('gusto_companies');
    }

    /**
     * create partner company on Gusto
     *
     * @param int $companyId
     * @return
     */
    private function createPartnerCompany(int $companyId): array
    {
        // set default return array
        $returnArray = [];
        $returnArray['errors'] = [];
        // check and get company
        $companyDetails = $this->db
            ->select('
            users.CompanyName,
            users.ssn,
            users.Location_Address,
            users.Location_City,
            users.Location_State,
            users.Location_ZipCode
        ')
            ->where(
                'users.sid',
                $companyId
            )
            ->get('users')
            ->row_array();
        // check for SSN
        if (!$companyDetails['ssn']) {
            // set error
            $returnArray['errors'][] = '"EIN" is missing.';
        }
        // Check if EIN is already used
        if ($this->db->where('ein', $companyDetails['ssn'])->count_all_results('gusto_companies')) {
            // set error
            $returnArray['errors'][] = '"EIN" already in used.';
        }
        // check for company location
        if (!$companyDetails['Location_Address']) {
            // set error
            $returnArray['errors'][] = '"Location Address" is missing.';
        }
        if (!$companyDetails['Location_State']) {
            // set error
            $returnArray['errors'][] = '"Location State" is missing.';
        }
        if (!$companyDetails['Location_City']) {
            // set error
            $returnArray['errors'][] = '"Location City" is missing.';
        }
        if (!$companyDetails['Location_ZipCode']) {
            // set error
            $returnArray['errors'][] = '"Location Zip" is missing.';
        }
        // check and return errors
        if ($returnArray['errors']) {
            //
            return $returnArray;
        }
        // set request array
        $request = [
            'user' => [],
            'company' => []
        ];
        // add primary admin
        $request['user']['first_name'] = $this->adminArray['first_name'];
        $request['user']['last_name'] = $this->adminArray['last_name'];
        $request['user']['email'] = $this->adminArray['email_address'];
        $request['user']['phone'] = $this->adminArray['phone_number'];
        // add company details
        $request['company']['name'] = $companyDetails['CompanyName'];
        $request['company']['ein'] = $companyDetails['ssn'];
        //
        $response = createPartnerCompany($request);
        // // set errors
        $errors = hasGustoErrors($response);
        //
        if ($errors) {
            // Error took place
            return $errors;
        }
        // set the insert array
        $ia = [];
        $ia['company_sid'] = $companyId;
        $ia['ein'] = $request['company']['ein'];
        $ia['parent_company_sid'] = 0;
        $ia['gusto_uuid'] = $response['company_uuid'];
        $ia['refresh_token'] = $response['refresh_token'];
        $ia['access_token'] = $response['access_token'];
        $ia['is_ts_accepted'] = 0;
        $ia['ts_email'] = null;
        $ia['ts_ip'] = null;
        $ia['ts_user_sid'] = null;
        $ia['created_at'] = $ia['updated_at'] = getSystemDate();
        // insert the array
        $this->db->insert('gusto_companies', $ia);
        // set the success array
        return [
            'success' => true
        ];
    }

    // create partner company ends

    /**
     * check and set the company location
     *
     * @param int $companyId
     * @return array
     */
    public function checkAndPushCompanyLocationToGusto(int $companyId): array
    {
        //
        if ($this->db->where('company_sid', $companyId)->count_all_results('gusto_companies_locations')) {
            return ['errors' => ['"Company Address" is already created.']];
        }
        // get the company location
        $location = $this->db
            ->select('
            users.Location_Address,
            users.Location_City,
            states.state_code,
            users.Location_ZipCode,
            users.PhoneNumber,
            users.Location_Address_2,
        ')
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->where('users.sid', $companyId)
            ->get('users')
            ->row_array();
        //
        $errorArray = [];
        //
        if (!$location['Location_Address']) {
            $errorArray[] = '"Street 1" is required.';
        }
        //
        if (!$location['Location_City']) {
            $errorArray[] = '"City" is required.';
        }
        //
        if (!$location['state_code']) {
            $errorArray[] = '"State" is required.';
        }
        //
        if (!$location['Location_ZipCode']) {
            $errorArray[] = '"Zip" is required.';
        }
        //
        if (!$location['PhoneNumber']) {
            $errorArray[] = '"Phone Number" is required.';
        }
        //
        if ($errorArray) {
            return ['errors' => $errorArray];
        }
        // make request
        $request = [];
        $request['street_1'] = $location['Location_Address'];
        $request['street_2'] = $location['Location_Address_2'];
        $request['city'] = $location['Location_City'];
        $request['state'] = $location['state_code'];
        $request['zip'] = $location['Location_ZipCode'];
        $request['country'] = "USA";
        $request['mailing_address'] = true;
        $request['filing_address'] = true;
        $request['phone_number'] = phonenumber_format($location['PhoneNumber'], true);
        //
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $gustoResponse = gustoCall(
            'createCompanyLocationOnGusto',
            $companyDetails,
            $request,
            "POST"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        // check for errors
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->insert('gusto_companies_locations', [
                'company_sid' => $companyId,
                'gusto_uuid' => $gustoResponse['uuid'],
                'gusto_version' => $gustoResponse['version'],
                'is_active' => (int) $gustoResponse['active'],
                'mailing_address' => (int) $gustoResponse['mailing_address'],
                'filing_address' => (int) $gustoResponse['filing_address'],
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]);
        //
        return $gustoResponse;
    }

    /**
     *
     */
    public function handleInitialEmployeeOnboard(int $companyId): bool
    {
        // get all employees
        $companyDetails = $this->getCompanyDetailsForGusto(
            $companyId,
            [
                'employee_ids'
            ]
        );
        //
        if (!$companyDetails['employee_ids']) {
            return false;
        }
        //
        $employeeIds = explode(',', $companyDetails['employee_ids']);
        //
        foreach ($employeeIds as $employeeId) {
            $this->onboardEmployee(
                $employeeId,
                $companyId
            );
        }
        //
        return true;
    }

    /**
     * onboard an employee
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    public function onboardEmployee(int $employeeId, int $companyId): array
    {
        //
        $megaResponse = [];
        // let's check the employee
        $gustoEmployee = $this->db
            ->select('gusto_uuid, gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        // check and create
        if (!$gustoEmployee) {
            $gustoEmployee = $this->createEmployeeOnGusto($employeeId, $companyId);
            //
            if ($gustoEmployee['errors']) {
                return $gustoEmployee;
            }
        }
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', ['personal_details' => 1]);
        //
        $megaResponse['employee'] = $gustoEmployee;
        $megaResponse['errors'] = [];
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // add employee gusto uuid
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $companyDetails['company_sid'] = $companyId;
        // sync employee jobs
        $response = $this->syncEmployeeJobs($employeeId, $companyDetails);
        // if has errors
        if ($response['errors']) {
            $megaResponse['errors'] = array_merge(
                $megaResponse['errors'],
                ['Jobs & Compensations' => $response['errors']]
            );
        } else {
            // check if there were jobs
            if ($response['count'] == 0) {
                // we need to add the employee job and compensations
                $response = $this->createEmployeeJobOnGusto(
                    $employeeId,
                    $companyDetails
                );
                // if errors occurs
                if ($response['errors']) {
                    $megaResponse['errors'] = array_merge(
                        $megaResponse['errors'],
                        ['Jobs & Compensations' => $response['errors']]
                    );
                }
            }
        }
        // sync employee bank accounts
        $this->syncEmployeePaymentMethod($employeeId);
        // sync employee work locations
        $this->syncEmployeeWorkAddresses($employeeId);
        //
        return $megaResponse;
    }

    /**
     * sync company with Gusto
     *
     * @param int $companyId
     * @return array
     */
    public function syncCompanyWithGusto(int $companyId): array
    {
        // get the company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        $companyDetails['company_sid'] = $companyId;
        // let's sync the company federal tax
        $this->syncCompanyFederalTaxWithGusto($companyDetails);
        // let's sync the company industry
        $this->syncCompanyIndustryWithGusto($companyDetails);
        // let's sync the company bank accounts
        $this->syncCompanyBankAccountsWithGusto($companyDetails);
        // let's sync the company pay schedule
        $this->syncCompanyPayScheduleWithGusto($companyDetails);
        // let's sync the company industry
        $this->syncCompanyPaymentConfigWithGusto($companyDetails);

        return SendResponse(
            200,
            [
                'success' => true
            ]
        );
    }

    /**
     * federal tax sync
     */
    private function syncCompanyFederalTaxWithGusto(array $companyDetails): array
    {
        // get the federal tax
        $gustoResponse = gustoCall(
            'getFederalTax',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $dataArray = [];
        $dataArray['gusto_version'] = $gustoResponse['version'];
        $dataArray['tax_payer_type'] = $gustoResponse['tax_payer_type'];
        $dataArray['taxable_as_scorp'] = $gustoResponse['taxable_as_scorp'];
        $dataArray['filing_form'] = $gustoResponse['filing_form'];
        $dataArray['has_ein'] = $gustoResponse['has_ein'];
        $dataArray['ein_verified'] = $gustoResponse['ein_verified'];
        $dataArray['legal_name'] = $gustoResponse['legal_name'];
        // check
        if (
            !$this->db
                ->where(['company_sid' => $companyDetails['company_sid']])
                ->count_all_results('companies_federal_tax')
        ) {
            $dataArray['company_sid'] = $companyDetails['company_sid'];
            $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
            $this->db->insert('companies_federal_tax', $dataArray);
        } else {
            $dataArray['updated_at'] = getSystemDate();
            $this->db
                ->where(['company_sid' => $companyDetails['company_sid']])
                ->update('companies_federal_tax', $dataArray);
        }
        //
        return ['success' => true];
    }

    /**
     * federal tax sync
     */
    private function syncCompanyIndustryWithGusto(array $companyDetails): array
    {
        // get the federal tax
        $gustoResponse = gustoCall(
            'getIndustry',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $dataArray = $gustoResponse;
        $dataArray['sic_codes'] = json_encode($dataArray['sic_codes']);
        //
        unset(
            $dataArray['company_uuid']
        );
        // check
        if (!$this->db
            ->where(['company_sid' => $companyDetails['company_sid']])
            ->count_all_results('companies_industry')) {
            $dataArray['company_sid'] = $companyDetails['company_sid'];
            $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
            $this->db->insert('companies_industry', $dataArray);
        } else {
            $dataArray['updated_at'] = getSystemDate();
            $this->db
                ->where(['company_sid' => $companyDetails['company_sid']])
                ->update('companies_industry', $dataArray);
        }
        //
        return ['success' => true];
    }

    /**
     * bank accounts sync
     */
    private function syncCompanyBankAccountsWithGusto(array $companyDetails): array
    {
        // get the federal tax
        $gustoResponse = gustoCall(
            'getBankAccounts',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        foreach ($gustoResponse as $account) {
            //
            $dataArray = [];
            $dataArray = $account;
            $dataArray['gusto_uuid'] = $account['uuid'];
            //
            unset($dataArray['uuid'], $dataArray['company_uuid']);
            // check
            if (
                !$this->db
                    ->where([
                        'company_sid' => $companyDetails['company_sid'],
                        'gusto_uuid' => $dataArray['gusto_uuid'],
                    ])
                    ->count_all_results('companies_bank_accounts')
            ) {
                $dataArray['company_sid'] = $companyDetails['company_sid'];
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                $this->db->insert('companies_bank_accounts', $dataArray);
            } else {
                $dataArray['updated_at'] = getSystemDate();
                $this->db
                    ->where([
                        'company_sid' => $companyDetails['company_sid'],
                        'gusto_uuid' => $dataArray['gusto_uuid'],
                    ])
                    ->update('companies_bank_accounts', $dataArray);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * pay schedules sync
     */
    private function syncCompanyPayScheduleWithGusto(array $companyDetails): array
    {
        // get the federal tax
        $gustoResponse = gustoCall(
            'getPaySchedules',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        foreach ($gustoResponse as $schedule) {
            //
            $dataArray = [];
            $dataArray = $schedule;
            $dataArray['gusto_uuid'] = $schedule['uuid'];
            $dataArray['gusto_version'] = $schedule['version'];
            //
            unset($dataArray['uuid'], $dataArray['version']);
            // check
            if (
                !$this->db
                    ->where([
                        'company_sid' => $companyDetails['company_sid'],
                        'gusto_uuid' => $dataArray['gusto_uuid'],
                    ])
                    ->count_all_results('companies_pay_schedules')
            ) {
                $dataArray['company_sid'] = $companyDetails['company_sid'];
                $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
                $this->db->insert('companies_pay_schedules', $dataArray);
            } else {
                $dataArray['updated_at'] = getSystemDate();
                $this->db
                    ->where([
                        'company_sid' => $companyDetails['company_sid'],
                        'gusto_uuid' => $dataArray['gusto_uuid'],
                    ])
                    ->update('companies_pay_schedules', $dataArray);
            }
        }
        //
        return ['success' => true];
    }
    /**
     * federal tax sync
     */
    private function syncCompanyPaymentConfigWithGusto(array $companyDetails): array
    {
        // get the federal tax
        $gustoResponse = gustoCall(
            'getPaymentConfig',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $dataArray = $gustoResponse;
        //
        unset(
            $dataArray['company_uuid']
        );
        // check
        if (!$this->db
            ->where(['company_sid' => $companyDetails['company_sid']])
            ->count_all_results('companies_payment_configs')) {
            $dataArray['company_sid'] = $companyDetails['company_sid'];
            $dataArray['created_at'] = $dataArray['updated_at'] = getSystemDate();
            $this->db->insert('companies_payment_configs', $dataArray);
        } else {
            $dataArray['updated_at'] = getSystemDate();
            $this->db
                ->where(['company_sid' => $companyDetails['company_sid']])
                ->update('companies_payment_configs', $dataArray);
        }
        //
        return ['success' => true];
    }

    /**
     * create an employee on Gusto
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    private function createEmployeeOnGusto(int $employeeId, int $companyId): array
    {
        // get employee profile data
        $employeeDetails = $this->db
            ->select('
                first_name,
                last_name,
                middle_name,
                dob,
                email,
                ssn
            ')
            ->where('sid', $employeeId)
            ->where('parent_sid', $companyId)
            ->get('users')
            ->row_array();
        //
        $errorArray = [];
        //
        if (!$employeeDetails['first_name']) {
            $errorArray[] = '"First Name" is required.';
        }
        if (!$employeeDetails['last_name']) {
            $errorArray[] = '"Last Name" is required.';
        }
        if (!$employeeDetails['dob'] || $employeeDetails['dob'] == '0000-00-00') {
            $errorArray[] = '"Date Of Birth" is required.';
        }
        if (!$employeeDetails['email']) {
            $errorArray[] = '"Email" is required.';
        }
        if (!$employeeDetails['ssn']) {
            $errorArray[] = '"Social Security Number (SSN)" is required.';
        }
        //
        if ($errorArray) {
            return ['errors' => $errorArray];
        }
        // make request
        $request = [];
        $request['first_name'] = $employeeDetails['first_name'];
        $request['middle_name'] = substr($employeeDetails['middle_name'], 0, 1);
        $request['last_name'] = $employeeDetails['last_name'];
        $request['date_of_birth'] = $employeeDetails['dob'];
        $request['email'] = $employeeDetails['email'];
        $request['ssn'] = $employeeDetails['ssn'];
        $request['self_onboarding'] = false;
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // make call
        $gustoResponse = gustoCall(
            "createEmployeeOnGusto",
            $companyDetails,
            $request,
            "POST"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->insert('gusto_companies_employees', [
                'company_sid' => $companyId,
                'employee_sid' => $employeeId,
                'gusto_uuid' => $gustoResponse['uuid'],
                'gusto_version' => $gustoResponse['version'],
                'is_onboarded' => 0,
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate(),
            ]);
        //
        return [
            'gusto_uuid' => $gustoResponse['uuid'],
            'gusto_version' => $gustoResponse['version']
        ];
    }

    /**
     * sync employee job with Gusto
     *
     * @param int   $employeeId
     * @param array $companyDetails
     * @return array
     */
    private function syncEmployeeJobs(int $employeeId, array $companyDetails): array
    {
        // make call
        $gustoResponse = gustoCall(
            "getEmployeeJobs",
            $companyDetails,
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        if (!$gustoResponse) {
            return ['count' => 0];
        }
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', ['compensation_details' => 1]);
        //
        foreach ($gustoResponse as $gustoJobs) {
            // set array
            $ins = [];

            $ins['gusto_version'] = $gustoJobs['version'];
            $ins['is_primary'] = $gustoJobs['primary'];
            $ins['gusto_location_uuid'] = $gustoJobs['location_uuid'];
            $ins['hire_date'] = $gustoJobs['hire_date'];
            $ins['title'] = $gustoJobs['title'];
            $ins['rate'] = $gustoJobs['rate'];
            $ins['current_compensation_uuid'] = $gustoJobs['current_compensation_uuid'];

            // let's quickly check
            if (
                $gustoEmployeeJobId =
                $this->db
                    ->select('sid')
                    ->where(['gusto_uuid' => $gustoJobs['uuid']])
                    ->get('gusto_employees_jobs')
                    ->row_array()['sid']
            ) {
                //
                $ins['updated_at'] = getSystemDate();
                // insert
                $this->db
                    ->where(['gusto_uuid' => $gustoJobs['uuid']])
                    ->update('gusto_employees_jobs', $ins);
            } else {
                //
                $ins['created_at'] =
                    $ins['updated_at'] =
                    getSystemDate();
                $ins['employee_sid'] = $employeeId;
                $ins['gusto_uuid'] = $gustoJobs['uuid'];
                // insert
                $this->db
                    ->insert('gusto_employees_jobs', $ins);
                $gustoEmployeeJobId = $this->db->insert_id();
            }
            //
            // add compensations
            foreach ($gustoJobs['compensations'] as $compensation) {
                //
                $ins = [];
                $ins['gusto_version'] = $compensation['version'];
                $ins['rate'] = $compensation['rate'];
                $ins['payment_unit'] = $compensation['payment_unit'];
                $ins['flsa_status'] = $compensation['flsa_status'];
                $ins['effective_date'] = $compensation['effective_date'];
                $ins['adjust_for_minimum_wage'] = $compensation['adjust_for_minimum_wage'];
                //
                if ($this->db->where(['gusto_uuid' => $compensation['uuid']])->count_all_results('gusto_employees_jobs_compensations')) {
                    $ins['updated_at'] = getSystemDate();
                    $this->db
                        ->where(['gusto_uuid' => $compensation['uuid']])
                        ->update('gusto_employees_jobs_compensations', $ins);
                } else {
                    $ins['gusto_employees_jobs_sid'] = $gustoEmployeeJobId;
                    $ins['gusto_uuid'] = $compensation['uuid'];
                    $ins['created_at'] = getSystemDate();
                    $ins['updated_at'] = getSystemDate();
                    $this->db->insert('gusto_employees_jobs_compensations', $ins);
                }
            }
        }
        //
        return ['count' => count($gustoResponse)];
    }

    /**
     * sync employee payment method
     *
     * @param int $employeeId
     * @return array
     */
    public function syncEmployeePaymentMethod(int $employeeId): array
    {
        // get employee payment method
        $paymentMethod = $this->db
            ->select('payment_method')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array()['payment_method'];
        //
        $paymentMethod = 'check';
        if ($paymentMethod === 'direct_deposit') {
            // handle bank accounts
            $this->syncEmployeeBankAccountsToGusto(
                $employeeId
            );
        } else {
            $this->syncEmployeeBankAccountsToGusto($employeeId, true);
        }
        // sync it with Gusto
        $this->syncEmployeePaymentMethodFromGusto(
            $employeeId
        );
        //
        return ['success' => true];
    }

    /**
     * sync employee bank accounts
     *
     * @param int $employeeId
     * @param bool $deleteIt Optional
     * @return
     */
    private function syncEmployeeBankAccountsToGusto(int $employeeId, bool $deleteIt = false): array
    {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
            sid,
            account_title,
            routing_transaction_number,
            account_number,
            account_type,
            gusto_uuid
        ')
            ->where([
                'users_sid' => $employeeId,
                'users_type' => 'employee'
            ])
            ->order_by('sid', 'desc')
            ->get('bank_account_details')
            ->result_array();
        //
        if (!$bankAccounts) {
            return ['errors' => ['No bank accounts found.']];
        }
        //
        foreach ($bankAccounts as $account) {
            //
            if ($deleteIt) {
                //
                $this->deleteEmployeeBankAccountToGusto($employeeId, $account);
                //
                continue;
            }
            //
            if ($account['gusto_uuid'] != '') {
                continue;
            }
            //
            $this->addEmployeeBankAccountToGusto($employeeId, $account);
        }
        //
        return ['success' => true];
    }

    /**
     * add employees bank account
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function addEmployeeBankAccountToGusto(int $employeeId, array $data): array
    {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // make request
        $request = [];
        $request['name'] = $data['account_title'];
        $request['routing_number'] = $data['routing_transaction_number'];
        $request['account_number'] = $data['account_number'];
        $request['account_type'] = ucwords($data['account_type']);
        // response
        $gustoResponse = gustoCall(
            'addBankAccount',
            $companyDetails,
            $request,
            'POST'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('sid', $data['sid'])
            ->update(
                'bank_account_details',
                [
                    'gusto_uuid' => $gustoResponse['uuid']
                ]
            );

        //
        return ['success' => true];
    }

    /**
     * delete employees bank account
     *
     * @param int   $employeeId
     * @param array $data
     */
    private function deleteEmployeeBankAccountToGusto(int $employeeId, array $data): array
    {
        //
        if (!$data['gusto_uuid']) {
            return ['success' => true];
        }
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        $companyDetails['other_uuid_2'] = $data['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'deleteBankAccount',
            $companyDetails,
            [],
            "DELETE"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('sid', $data['sid'])
            ->update(
                'bank_account_details',
                [
                    'gusto_uuid' => null
                ]
            );

        //
        return ['success' => true];
    }

    /**
     * sync employee work addresses
     *
     * @param int   $employeeId
     */
    public function syncEmployeeWorkAddresses(int $employeeId): array
    {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getEmployeeWorkAddress',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $location) {
            //
            $ins = [];

            $ins['gusto_location_uuid'] = $location['location_uuid'];
            $ins['gusto_version'] = $location['version'];
            $ins['effective_date'] = $location['effective_date'];
            $ins['active'] = $location['active'];
            $ins['street_1'] = $location['street_1'];
            $ins['street_2'] = $location['street_2'];
            $ins['city'] = $location['city'];
            $ins['state'] = $location['state'];
            $ins['zip'] = $location['zip'];
            $ins['country'] = $location['country'];
            $ins['updated_at'] = getSystemDate();
            //
            $whereArray = [
                'gusto_uuid' => $location['uuid']
            ];
            //
            if (!$this->db->where($whereArray)->count_all_results('gusto_companies_employees_work_addresses')) {

                $ins['created_at'] = getSystemDate();
                $ins['gusto_uuid'] = $location['uuid'];
                $ins['employee_sid'] = $employeeId;
                // insert
                $this->db->insert('gusto_companies_employees_work_addresses', $ins);
            } else {
                // update
                $this->db
                    ->where($whereArray)
                    ->update('gusto_companies_employees_work_addresses', $ins);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * create and sync employee job on Gusto
     *
     * @param int   $employeeId
     * @param array $companyDetails
     * @return array
     */
    private function createEmployeeJobOnGusto(int $employeeId, array $companyDetails): array
    {
        // check the company location
        $location = $this->db
            ->select('gusto_uuid')
            ->where('company_sid', $companyDetails['company_sid'])
            ->where('is_active', 1)
            ->get('gusto_companies_locations')
            ->row_array();
        //
        if (!$location) {
            return ['errors' => ['"Location" is missing.']];
        }
        // get employee profile data
        $employeeDetails = $this->db
            ->select('
                job_title,
                complynet_job_title,
                registration_date,
                joined_at,
            ')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        // get job title
        $jobTitle = 'Automotive';
        $joiningDate = get_employee_latest_joined_date(
            $employeeDetails['registration_date'],
            $employeeDetails['joined_at'],
            ''
        );
        //
        if ($employeeDetails['job_title']) {
            $jobTitle = $employeeDetails['job_title'];
        } elseif ($employeeDetails['complynet_job_title']) {
            $jobTitle = $employeeDetails['complynet_job_title'];
        }
        //
        $errorArray = [];
        // validation
        if (!$jobTitle) {
            $errorArray[] = '"Job Title" is required.';
        }
        if (!$joiningDate) {
            $errorArray[] = '"Joining Date" is required.';
        }
        //
        if ($errorArray) {
            return ['errors' => $errorArray];
        }
        // create request
        $request = [];
        $request['title'] = $jobTitle;
        $request['location_uuid'] = $location['gusto_uuid'];
        $request['hire_date'] = $joiningDate;
        // make call
        $gustoResponse = gustoCall(
            "createEmployeeJobOnGusto",
            $companyDetails,
            $request,
            "POST"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->insert('gusto_employees_jobs', [
                'employee_sid' => $employeeId,
                'gusto_uuid' => $gustoResponse['uuid'],
                'gusto_version' => $gustoResponse['version'],
                'gusto_location_uuid' => $gustoResponse['location_uuid'],
                'is_primary' => $gustoResponse['primary'],
                'hire_date' => $gustoResponse['hire_date'],
                'title' => $gustoResponse['title'],
                'rate' => $gustoResponse['rate'],
                'current_compensation_uuid' => $gustoResponse['current_compensation_uuid'],
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate()
            ]);
        //
        $gustoEmployeeJobId = $this->db->insert_id();
        // add compensations
        foreach ($gustoResponse['compensations'] as $compensation) {
            $this->db
                ->insert('gusto_employees_jobs_compensations', [
                    'gusto_employees_jobs_sid' => $gustoEmployeeJobId,
                    'gusto_uuid' => $compensation['uuid'],
                    'gusto_version' => $compensation['version'],
                    'rate' => $compensation['rate'],
                    'payment_unit' => $compensation['payment_unit'],
                    'flsa_status' => $compensation['flsa_status'],
                    'effective_date' => $compensation['effective_date'],
                    'adjust_for_minimum_wage' => $compensation['adjust_for_minimum_wage'],
                    'minimum_wages' => json_encode($compensation['minimum_wages']),
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]);
        }
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', [
                'work_address' => 1,
                'compensation_details' => 1
            ]);
        //
        return [
            'gusto_uuid' => $gustoResponse['uuid'],
            'gusto_version' => $gustoResponse['version']
        ];
    }

    /**
     * get employee personal details for Gusto
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeePersonalDetailsForGusto(int $employeeId): array
    {
        // get user profile data
        $record = $this->db
            ->select('
            first_name,
            last_name,
            middle_name,
            registration_date,
            joined_at,
            email,
            ssn,
            dob,
        ')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        // let reset the array
        $record['start_date'] = get_employee_latest_joined_date(
            $record['registration_date'],
            $record['joined_at'],
            '',
        );
        $record['middle_initial'] = substr($record['middle_name'], 0, 1);
        $record['start_date'] = formatDateToDB(
            $record['start_date'],
            DB_DATE,
            SITE_DATE
        );
        $record['dob'] = $record['dob'] ? formatDateToDB(
            $record['dob'],
            DB_DATE,
            SITE_DATE
        ) : '';
        //
        return $record;
    }

    /**
     * get company locations
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyLocations(int $companyId): array
    {
        // get user profile data
        return $this->db
            ->select('
                users.Location_Address,
                users.Location_City,
                states.state_code,
                users.Location_ZipCode,
                users.Location_Address_2,
                gusto_companies_locations.gusto_uuid
        ')
            ->join('users', 'users.sid = gusto_companies_locations.company_sid', 'inner')
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->where('gusto_companies_locations.company_sid', $companyId)
            ->get('gusto_companies_locations')
            ->result_array();
    }

    /**
     * get employee primary job
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeePrimaryJob(int $employeeId): array
    {
        // get the job
        $job = $this->db
            ->select('title, current_compensation_uuid')
            ->where([
                'employee_sid' => $employeeId,
                'is_primary' => 1
            ])
            ->get('gusto_employees_jobs')
            ->row_array();
        //
        if ($job) {
            // get the compensation
            $job['compensation'] = $this->db
                ->select('rate, payment_unit, flsa_status, adjust_for_minimum_wage, minimum_wages')
                ->where([
                    'gusto_uuid' => $job['current_compensation_uuid']
                ])
                ->get('gusto_employees_jobs_compensations')
                ->row_array();
        }
        return $job;
    }

    /**
     * get employee home address
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeHomeAddress(int $employeeId): array
    {
        // get the company location
        return $this->db
            ->select('
                users.Location_Address,
                users.Location_City,
                states.state_code,
                users.Location_ZipCode,
                users.Location_Address_2,
            ')
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->where('users.sid', $employeeId)
            ->get('users')
            ->row_array();
    }

    /**
     * get employee federal tax
     *
     * TODO: extract employee data from w4
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeFederalTax(int $employeeId): array
    {
        // get
        return $this->db
            ->select('
                filing_status,
                extra_withholding,
                two_jobs,
                dependents_amount,
                other_income,
                deductions
            ')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_federal_tax')
            ->row_array();
    }

    /**
     * get employee state tax
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeStateTax(int $employeeId): array
    {
        // get
        $record = $this->db
            ->select('state_code, questions_json, file_new_hire_report, is_work_state')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_state_tax')
            ->row_array();
        //record not found
        if (!$record) {
            $response = $this->syncEmployeeStateTaxFromGusto(
                $employeeId
            );
            //
            if (!$response['errors']) {
                // get
                $record = $this->db
                    ->select('state_code, questions_json, file_new_hire_report, is_work_state')
                    ->where('employee_sid', $employeeId)
                    ->get('gusto_employees_state_tax')
                    ->row_array();
            }
        }
        //
        if ($record) {
            $record['state_name'] = getStateColumn(
                ['state_code' => $record['state_code']],
                'state_name'
            );
        }
        return $record;
    }

    /**
     * get employee payment method
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeePaymentMethod(int $employeeId): array
    {
        // get
        $record = $this->db
            ->select('type, split_by, splits')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_payment_method')
            ->row_array();
        //record not found
        if (!$record) {
            $response = $this->syncEmployeePaymentMethodFromGusto(
                $employeeId
            );
            //
            if (!$response['errors']) {
                // get
                $record = $this->db
                    ->select('type, split_by, splits')
                    ->where('employee_sid', $employeeId)
                    ->get('gusto_employees_payment_method')
                    ->row_array();
            }
        }
        return $record;
    }

    /**
     * get employee bank accounts
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeBankAccounts(int $employeeId): array
    {
        // get
        $record = $this->db
            ->select('type, split_by, splits')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_payment_method')
            ->row_array();
        //record not found
        if (!$record) {
            $response = $this->syncEmployeePaymentMethodFromGusto(
                $employeeId
            );
            //
            if (!$response['errors']) {
                // get
                $record = $this->db
                    ->select('type, split_by, splits')
                    ->where('employee_sid', $employeeId)
                    ->get('gusto_employees_payment_method')
                    ->row_array();
            }
        }
        return $record;
    }

    /**
     * update employee's profile details on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeePersonalDetails(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                    'gusto_version',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $gustoEmployee['gusto_version'];
        $request['first_name'] = $data['first_name'];
        $request['last_name'] = $data['last_name'];
        $request['middle_initial'] = substr($data['middle_initial'], 0, 1);
        $request['date_of_birth'] = $data['date_of_birth'];
        $request['email'] = $data['email'];
        $request['ssn'] = $data['ssn'];
        $request['two_percent_shareholder'] = false;
        // response
        $gustoResponse = gustoCall(
            'updateEmployeePersonalDetails',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $updateArray = [];
        $updateArray['gusto_version'] = $gustoResponse['version'];
        $updateArray['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->update('gusto_companies_employees', $updateArray);
        //
        $updateArray = [];
        $updateArray['first_name'] = $request['first_name'];
        $updateArray['last_name'] = $request['last_name'];
        $updateArray['email'] = $request['email'];
        $updateArray['middle_name'] = $request['middle_initial'];
        $updateArray['ssn'] = $request['ssn'];
        $updateArray['dob'] = $request['date_of_birth'];
        $updateArray['joined_at'] = $data['start_date'];
        //
        $this->db
            ->where('sid', $employeeId)
            ->update('users', $updateArray);
        // TODO
        // record change history
        return ['success' => true];
    }

    /**
     * update employee's work address on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeWorkAddress(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                ]
            );
        // get gusto work address
        $gustoWorkLocation = $this->db
            ->select('sid, gusto_version, gusto_uuid')
            ->where([
                'employee_sid' => $employeeId,
                'active' => 1
            ])
            ->get('gusto_companies_employees_work_addresses')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoWorkLocation['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $gustoWorkLocation['gusto_version'];
        $request['location_uuid'] = $data['location_uuid'];
        $request['effective_date'] = $data['start_date'];
        // response
        $gustoResponse = gustoCall(
            'updateEmployeeWorkAddress',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $updateArray = [];
        $updateArray['gusto_version'] = $gustoResponse['version'];
        $updateArray['location_uuid'] = $gustoResponse['location_uuid'];
        $updateArray['effective_date'] = $gustoResponse['effective_date'];
        $updateArray['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $gustoWorkLocation['sid'])
            ->update('gusto_companies_employees_work_addresses', $updateArray);
        //
        return ['success' => true];
    }

    /**
     * update employee's job on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeJob(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                ]
            );
        // get the job
        $gustoJob = $this->db
            ->select('sid, title, gusto_uuid, gusto_location_uuid, hire_date, gusto_version')
            ->where([
                'employee_sid' => $employeeId,
                'is_primary' => 1
            ])
            ->get('gusto_employees_jobs')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoJob['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $gustoJob['gusto_version'];
        $request['title'] = $data['title'] ?? $gustoJob['title'];
        $request['location_uuid'] = $data['location_uuid'] ?? $gustoJob['gusto_location_uuid'];
        $request['hire_date'] = $data['start_date'] ?? $gustoJob['hire_date'];
        //
        if (!$request['title']) {
            $employeeDetails = $this->db
                ->select('
                job_title,
                complynet_job_title
            ')
                ->where('sid', $employeeId)
                ->get('users')
                ->row_array();
            // get job title
            $jobTitle = 'Automotive';
            //
            if ($employeeDetails['job_title']) {
                $jobTitle = $employeeDetails['job_title'];
            } elseif ($employeeDetails['complynet_job_title']) {
                $jobTitle = $employeeDetails['complynet_job_title'];
            }
            //
            $request['title'] = $jobTitle;
        }
        // response
        $gustoResponse = gustoCall(
            'updateEmployeeJob',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', ['compensation_details' => 1]);
        // set array
        $ins = [];
        $ins['gusto_version'] = $gustoResponse['version'];
        $ins['is_primary'] = $gustoResponse['primary'];
        $ins['gusto_location_uuid'] = $gustoResponse['location_uuid'];
        $ins['hire_date'] = $gustoResponse['hire_date'];
        $ins['title'] = $gustoResponse['title'];
        $ins['rate'] = $gustoResponse['rate'];
        $ins['current_compensation_uuid'] = $gustoResponse['current_compensation_uuid'];
        $ins['updated_at'] = getSystemDate();
        // update
        $this->db
            ->where(['gusto_uuid' => $gustoResponse['uuid']])
            ->update('gusto_employees_jobs', $ins);

        //
        // add compensations
        foreach ($gustoResponse['compensations'] as $compensation) {
            //
            $ins = [];
            $ins['gusto_version'] = $compensation['version'];
            $ins['rate'] = $compensation['rate'];
            $ins['payment_unit'] = $compensation['payment_unit'];
            $ins['flsa_status'] = $compensation['flsa_status'];
            $ins['effective_date'] = $compensation['effective_date'];
            $ins['adjust_for_minimum_wage'] = $compensation['adjust_for_minimum_wage'];
            //
            if ($this->db->where(['gusto_uuid' => $compensation['uuid']])->count_all_results('gusto_employees_jobs_compensations')) {
                $ins['updated_at'] = getSystemDate();
                $this->db
                    ->where(['gusto_uuid' => $compensation['uuid']])
                    ->update('gusto_employees_jobs_compensations', $ins);
            } else {
                $ins['gusto_employees_jobs_sid'] = $gustoJob['sid'];
                $ins['gusto_uuid'] = $compensation['uuid'];
                $ins['created_at'] = getSystemDate();
                $ins['updated_at'] = getSystemDate();
                $this->db->insert('gusto_employees_jobs_compensations', $ins);
            }
        }

        //
        return ['success' => true];
    }

    /**
     * update employee's job on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeCompensation(
        int $employeeId,
        array $data
    ): array {
        //
        $response = $this->updateEmployeeJob($employeeId, ['title' => $data['title']]);
        //
        if ($response['errors']) {
            return $response;
        }
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                ]
            );
        // get the job
        $gustoJob = $this->db
            ->select('
                gusto_employees_jobs_compensations.gusto_uuid,
                gusto_employees_jobs_compensations.gusto_version,
                gusto_employees_jobs_compensations.adjust_for_minimum_wage,
                gusto_employees_jobs_compensations.minimum_wages
            ')
            ->where([
                'gusto_employees_jobs.employee_sid' => $employeeId,
                'gusto_employees_jobs.is_primary' => 1
            ])
            ->join(
                'gusto_employees_jobs_compensations',
                'gusto_employees_jobs_compensations.gusto_uuid = gusto_employees_jobs.current_compensation_uuid',
                'inner'
            )
            ->get('gusto_employees_jobs')
            ->row_array();
        //
        if (!$gustoJob) {
            return [
                'errors' => [
                    "Compensation doesn't exists."
                ]
            ];
        }
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoJob['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $gustoJob['gusto_version'];
        $request['rate'] = $data['amount'];
        $request['flsa_status'] = $data['classification'];
        $request['payment_unit'] = $data['per'];
        $request['adjust_for_minimum_wage'] = $gustoJob['adjust_for_minimum_wage'];
        $request['minimum_wages'] = json_decode($gustoJob['minimum_wages'], true);
        // response
        $gustoResponse = gustoCall(
            'updateEmployeeJobCompensation',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['gusto_version'] = $gustoResponse['version'];
        $ins['rate'] = $gustoResponse['rate'];
        $ins['payment_unit'] = $gustoResponse['payment_unit'];
        $ins['flsa_status'] = $gustoResponse['flsa_status'];
        $ins['effective_date'] = $gustoResponse['effective_date'];
        $ins['adjust_for_minimum_wage'] = $gustoResponse['adjust_for_minimum_wage'];
        $ins['updated_at'] = getSystemDate();
        //
        $this->db
            ->where(['gusto_uuid' => $gustoResponse['uuid']])
            ->update('gusto_employees_jobs_compensations', $ins);
        // set job id
        $companyDetails['other_uuid'] = $gustoResponse['job_uuid'];

        // let's sync single job
        $gustoResponse = gustoCall(
            'getSingleJob',
            $companyDetails
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('gusto_uuid', $gustoResponse['uuid'])
            ->update(
                'gusto_employees_jobs',
                [
                    'title' => $gustoResponse['title'],
                    'hire_date' => $gustoResponse['hire_date'],
                    'gusto_version' => $gustoResponse['version'],
                    'rate' => $gustoResponse['rate']
                ]
            );

        //
        return ['success' => true];
    }

    /**
     * update employee's home address on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeHomeAddress(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_home_address_uuid',
                    'gusto_home_address_version',
                    'gusto_home_address_effective_date',
                    'gusto_home_address_courtesy_withholding'
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_home_address_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $gustoEmployee['gusto_home_address_version'];
        $request['street_1'] = $data['street_1'];
        $request['street_2'] = $data['street_2'];
        $request['city'] = $data['city'];
        $request['state'] = strtoupper($data['state']);
        $request['zip'] = $data['zip'];
        // response
        $gustoResponse = gustoCall(
            'updateHomeAddress',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['gusto_home_address_version'] = $gustoResponse['version'];
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', $upd);
        //
        $upd = [];
        $upd['Location_Address'] = $request['street_1'];
        $upd['Location_Address_2'] = $request['street_2'];
        $upd['Location_City'] = $request['city'];
        $upd['Location_State'] = getStateColumn(['state_code' => $request['state']], 'sid');
        $upd['Location_ZipCode'] = $request['zip'];

        $this->db
            ->where(['sid' => $employeeId])
            ->update('users', $upd);
        //
        return ['success' => true];
    }

    /**
     * update employee's home address on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function createEmployeeHomeAddress(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // let's make request
        $request = [];
        $request['street_1'] = $data['street_1'];
        $request['street_2'] = $data['street_2'];
        $request['city'] = $data['city'];
        $request['state'] = strtoupper($data['state']);
        $request['zip'] = $data['zip'];
        // response
        $gustoResponse = gustoCall(
            'createHomeAddress',
            $companyDetails,
            $request,
            'POST'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_home_address_uuid'] = $gustoResponse['uuid'];
        $upd['gusto_home_address_version'] = $gustoResponse['version'];
        $upd['gusto_home_address_effective_date'] = $gustoResponse['effective_date'];
        $upd['gusto_home_address_courtesy_withholding'] = $gustoResponse['courtesy_withholding'];
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', $upd);
        //
        $upd = [];
        $upd['Location_Address'] = $request['street_1'];
        $upd['Location_Address_2'] = $request['street_2'];
        $upd['Location_City'] = $request['city'];
        $upd['Location_State'] = getStateColumn(['state_code' => $request['state']], 'sid');
        $upd['Location_ZipCode'] = $request['zip'];

        $this->db
            ->where(['sid' => $employeeId])
            ->update('users', $upd);
        //
        return ['success' => true];
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function createEmployeeFederalTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['employee_sid'] = $employeeId;
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $data['w4_data_type'];
        $upd['created_at'] = $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->insert('gusto_employees_federal_tax', $upd);
        //
        return $this->updateEmployeeFederalTax($employeeId, $data);
    }

    /**
     * create employee's federal tax on Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeFederalTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // let's make request
        $request = [];
        $request['version'] = $this->db
            ->select('gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_federal_tax')
            ->row_array()['gusto_version'];
        $request['filing_status'] = $data['filing_status'];
        $request['extra_withholding'] = $data['extra_withholding'];
        $request['two_jobs'] = $data['two_jobs'];
        $request['dependents_amount'] = $data['dependents_amount'];
        $request['other_income'] = $data['other_income'];
        $request['deductions'] = $data['deductions'];
        $request['w4_data_type'] = 'rev_2020_w4';
        // response
        $gustoResponse = gustoCall(
            'createFederalTax',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        // set update array
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['filing_status'] = $gustoResponse['filing_status'];
        $upd['extra_withholding'] = $gustoResponse['extra_withholding'];
        $upd['two_jobs'] = $gustoResponse['two_jobs'];
        $upd['dependents_amount'] = $gustoResponse['dependents_amount'];
        $upd['other_income'] = $gustoResponse['other_income'];
        $upd['deductions'] = $gustoResponse['deductions'];
        $upd['w4_data_type'] = $request['w4_data_type'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('employee_sid', $employeeId)
            ->update('gusto_employees_federal_tax', $upd);
        //
        return ['success' => true];
    }

    /**
     * get employee's state tax from Gusto
     *
     * @param int   $employeeId
     */
    public function syncEmployeeStateTaxFromGusto(
        int $employeeId
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getStateTax',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $tax) {
            //
            $whereArray = [
                'employee_sid' => $employeeId,
                'state_code' => $tax['state']
            ];
            //
            $dataArray = [];
            $dataArray['state_code'] = $tax['state'];
            $dataArray['file_new_hire_report'] = $tax['file_new_hire_report'];
            $dataArray['is_work_state'] = $tax['is_work_state'];
            $dataArray['questions_json'] = json_encode($tax['questions']);
            $dataArray['updated_at'] = getSystemDate();
            //
            if (!$this->db->where($whereArray)->count_all_results('gusto_employees_state_tax')) {
                // insert
                $dataArray['created_at'] = $dataArray['updated_at'];
                $dataArray['employee_sid'] = $employeeId;
                //
                $this->db
                    ->insert('gusto_employees_state_tax', $dataArray);
            } else {
                // update
                $this->db
                    ->where($whereArray)
                    ->update('gusto_employees_state_tax', $dataArray);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * get employee's payment method from Gusto
     *
     * @param int   $employeeId
     */
    public function syncEmployeePaymentMethodFromGusto(
        int $employeeId
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getPaymentMethod',
            $companyDetails,
            [],
            'GET'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        $whereArray = [
            'employee_sid' => $employeeId,
        ];
        //
        $dataArray = [];
        $dataArray['gusto_version'] = $gustoResponse['version'];
        $dataArray['type'] = $gustoResponse['type'];
        $dataArray['split_by'] = $gustoResponse['split_by'];
        $dataArray['splits'] = json_encode($gustoResponse['splits']);
        $dataArray['updated_at'] = getSystemDate();
        //
        if (!$this->db->where($whereArray)->count_all_results('gusto_employees_payment_method')) {
            // insert
            $dataArray['created_at'] = $dataArray['updated_at'];
            $dataArray['employee_sid'] = $employeeId;
            //
            $this->db
                ->insert('gusto_employees_payment_method', $dataArray);
        } else {
            // update
            $this->db
                ->where($whereArray)
                ->update('gusto_employees_payment_method', $dataArray);
        }
        //
        return ['success' => true, 'version' => $gustoResponse['version']];
    }

    /**
     * update employee's state tax from Gusto
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeeStateTax(
        int $employeeId,
        array $data
    ): array {
        // get gusto employee details
        $gustoEmployee = $this
            ->getEmployeeDetailsForGusto(
                $employeeId,
                [
                    'company_sid',
                    'gusto_uuid',
                ]
            );
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $request = [
            'states' => [$data]
        ];

        // response
        $gustoResponse = gustoCall(
            'updateStateTax',
            $companyDetails,
            $request,
            'PUT'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$gustoResponse) {
            return ['success' => true];
        }
        //
        foreach ($gustoResponse as $tax) {
            //
            $whereArray = [
                'employee_sid' => $employeeId,
                'state_code' => $tax['state']
            ];
            //
            $dataArray = [];
            $dataArray['state_code'] = $tax['state'];
            $dataArray['file_new_hire_report'] = $tax['file_new_hire_report'];
            $dataArray['is_work_state'] = $tax['is_work_state'];
            $dataArray['questions_json'] = json_encode($tax['questions']);
            $dataArray['updated_at'] = getSystemDate();
            // update
            $this->db
                ->where($whereArray)
                ->update('gusto_employees_state_tax', $dataArray);
        }
        //
        return ['success' => true];
    }
}
