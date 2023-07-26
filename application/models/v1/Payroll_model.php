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
     * onboard an employee
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    public function onboardEmployee(int $employeeId, int $companyId): array
    {
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
        return [];
        // set employee job
        // $this->createEmployeeJobOnGusto($employeeId, $companyId);
        // set home address
        // $this->checkAndSetEmployeeHomeAddressOnGusto($employeeId, $companyId);
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
        // $this->syncCompanyFederalTaxWithGusto($companyDetails);
        // let's sync the company bank accounts
        $this->syncCompanyBankAccountsWithGusto($companyDetails);

        return [];
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
     * bank accounts sync
     */
    private function syncCompanyBankAccountsWithGusto(array $companyDetails): array
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
        $dataArray['gusto_uuid'] = $gustoResponse['uuid'];
        $dataArray['account_type'] = $gustoResponse['account_type'];
        $dataArray['routing_number'] = $gustoResponse['routing_number'];
        $dataArray['hidden_account_number'] = $gustoResponse['hidden_account_number'];
        $dataArray['verification_status'] = $gustoResponse['verification_status'];
        $dataArray['verification_type'] = $gustoResponse['verification_type'];
        $dataArray['plaid_status'] = $gustoResponse['plaid_status'];
        $dataArray['last_cached_balance'] = $gustoResponse['last_cached_balance'];
        $dataArray['balance_fetched_date'] = $gustoResponse['balance_fetched_date'];
        // check
        // TODO
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
     * create an employee job on Gusto
     *
     * @param int $employeeId
     * @param int $companyId
     * @return array
     */
    private function createEmployeeJobOnGusto(int $employeeId, int $companyId): array
    {
        // check the company location
        $location = $this->db
            ->select('gusto_uuid')
            ->where('company_sid', $companyId)
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
                hourly_rate
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
        $hourlyRate = $employeeDetails['hourly_rate'] ? $employeeDetails['hourly_rate'] : 0;
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
        $request['location_id'] = $location['gusto_uuid'];
        $request['hire_date'] = $joiningDate;
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // make call
        $gustoResponse = gustoCall(
            "createEmployeeJobOnGusto",
            $companyDetails,
            $request,
            "POST"
        );
        _e($gustoResponse);
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
                'is_primary' => $gustoResponse['primary'],
                'created_at' => getSystemDate(),
                'updated_at' => getSystemDate(),
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
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate()
                ]);
        }
        //
        return [
            'gusto_uuid' => $gustoResponse['uuid'],
            'gusto_version' => $gustoResponse['version']
        ];
    }
}
