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
        //
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        // for super admin as the library would already be loaded
        if (str_replace('sa/payrolls', '', $_SERVER['REQUEST_URI']) == $_SERVER['REQUEST_URI']) {
            $companyId = checkAndGetSession("company", true)["sid"];
            if ($companyId) {
                // load the payroll helper
                $this->load->helper('v1/payroll' . ($this->db->where([
                    "company_sid" => $companyId,
                    "stage" => "production"
                ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
            }
        }

        // set the admin
        $this->adminArray = [
            'first_name' => 'Steven',
            'last_name' => 'Warner',
            'email_address' => 'Steven@AutomotoHR.com',
            'phone_number' => '951-385-8204',
        ];
    }


    public function loadPayrollHelper(int $companyId)
    {
        // load the payroll helper
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $companyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');
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
            $r[] = 'Employer Identification Number (EIN) is missing.';
        }
        if (strlen(preg_replace('/\D/', '', $record['ssn'])) != 9) {
            $r[] = 'Employer Identification Number (EIN) must be 9 digits long.';
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
        }
        //
        if (!$record['PhoneNumber']) {
            $r[] = 'Company phone number code is missing.';
        }
        if (!phonenumber_validate($record['PhoneNumber'])) {
            $r[] = 'Company phone number is invalid.';
        }

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
     * get payroll employees
     */
    public function getPayrollEmployees(int $companyId, bool $useIndex = false, int $limit = 0): array
    {
        //
        $this->db
            ->select(
                getUserFields() . 'is_onboarded'
            )
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where('gusto_companies_employees.company_sid', $companyId);
        //
        if ($limit !== 0) {
            $this->db->limit($limit);
        }
        //
        $this->db->order_by('gusto_companies_employees.is_onboarded', 'ASC');
        //
        $records = $this->db->get('gusto_companies_employees')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $employee) {
            //
            $tmp[$employee['userId']] = [
                'name' => remakeEmployeeName($employee),
                'is_onboard' => $employee['is_onboarded'],
                'id' => $employee['userId'],
                "paymentMethodIsDirectDeposit" => $this->checkIfEmployeePMIsDD(
                    $employee["userId"]
                )
            ];
        }
        //
        if (!$useIndex) {
            $tmp = array_values($tmp);
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
     * Get gusto employees details for gusto
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyPaymentConfiguration(int $companyId): array
    {
        //
        return $this->db
            ->select('fast_payment_limit, payment_speed')
            ->where('company_sid', $companyId)
            ->get('companies_payment_configs')
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
        //
        $firstName = $this->adminArray['first_name'];
        $lastName = $this->adminArray['last_name'];
        $email = $this->adminArray['email_address'];
        //
        if (
            $this->checkDefaultAdminExist($companyId)
        ) {
            $defaultAdmin = $this->db
                ->select('first_name, last_name, email_address')
                ->where('company_sid', $companyId)
                ->get('gusto_companies_default_admin')
                ->row_array();
            //
            $firstName = $defaultAdmin['first_name'];
            $lastName = $defaultAdmin['last_name'];
            $email = $defaultAdmin['email_address'];
        }
        //
        // insert the admin
        $this->db->insert(
            'gusto_companies_admin',
            [
                'company_sid' => $companyId,
                'is_store_admin' => 1,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email_address' => $email,
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
     * check and set the company location
     *
     * @param int $companyId
     * @return array
     */
    public function updateCompanyLocationToGusto(int $companyId): array
    {
        $gustoLocation = $this->db
            ->where('company_sid', $companyId)
            ->get('gusto_companies_locations')
            ->row_array();
        //
        if (!$gustoLocation || !$gustoLocation["gusto_uuid"]) {
            return ["errors" => ["Gusto UUID is missing."]];
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
            ->where(
                'users.sid',
                $companyId
            )
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
        $request['version'] = $gustoLocation["gusto_version"];
        $request['phone_number'] = phonenumber_format($location['PhoneNumber'], true);
        //
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        $companyDetails["gusto_uuid"] = $gustoLocation["gusto_uuid"];
        //
        $gustoResponse = gustoCall(
            'updateCompanyLocationOnGusto',
            $companyDetails,
            $request,
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        // check for errors
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->where("company_sid", $companyId)
            ->update('gusto_companies_locations', [
                'gusto_uuid' => $gustoResponse['uuid'],
                'gusto_version' => $gustoResponse['version'],
                'is_active' => (int) $gustoResponse['active'],
                'mailing_address' => (int) $gustoResponse['mailing_address'],
                'filing_address' => (int) $gustoResponse['filing_address'],
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
        $newIds = [];
        //
        foreach ($employeeIds as $employeeId) {
            $response = $this->onboardEmployee(
                $employeeId,
                $companyId
            );
            //
            if ($response['errors']) {
                $newIds[] = $employeeId;
            }
        }
        //
        $newIds = $newIds ? implode(',', $newIds) : null;
        //
        $this->db
            ->where('company_sid', $companyId)
            ->update('gusto_companies', [
                'employee_ids' => $newIds
            ]);
        // create earning types
        $this->createCompanyEarningTypes($companyId);
        //
        $this->syncCompanyEarningTypes($companyId);
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
        // get employee compensation
        $employee = $this->db
            ->select("
                    payment_method,
                    hourly_rate,
                    hourly_technician,
                    flat_rate_technician,
                    semi_monthly_salary,
                    semi_monthly_draw,
                ")
            ->where('sid', $employeeId)
            ->get("users")
            ->row_array();
        //
        $megaResponse['employee'] = $gustoEmployee;
        $megaResponse['errors'] = [];
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // add employee gusto uuid
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $companyDetails['company_sid'] = $companyId;
        // payment method
        if ($employee['payment_method'] != 'check') {
            $this->getEmployeeBankAccountsById($employeeId, true);
        }
        // sync federal tax
        $this->syncEmployeeFederalTax($employeeId);
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
            //
            if (!$response['errors']) {
                //
                $data = [];
                //
                if ((int) $employee['hourly_rate'] != 0) {
                    $data['amount'] = $employee['hourly_rate'];
                    $data['classification'] = "Nonexempt";
                    $data['per'] = "Hour";
                } elseif ((int) $employee['hourly_technician'] != 0) {
                    $data['amount'] = $employee['hourly_technician'];
                    $data['classification'] = "Nonexempt";
                    $data['per'] = "Hour";
                } elseif ((int) $employee['flat_rate_technician'] != 0) {
                    $data['amount'] = $employee['flat_rate_technician'];
                    $data['classification'] = "Nonexempt";
                    $data['per'] = "Hour";
                } elseif ((int) $employee['semi_monthly_salary'] != 0) {
                    $data['amount'] = $employee['semi_monthly_salary'];
                    $data['classification'] = "Exempt";
                    $data['per'] = "Month";
                } elseif ((int) $employee['semi_monthly_draw'] != 0) {
                    $data['amount'] = $employee['semi_monthly_draw'];
                    $data['classification'] = "Exempt";
                    $data['per'] = "Month";
                }
                $this->updateEmployeeCompensation($employeeId, $data);
            }
        }
        // sync employee bank accounts
        $this->syncEmployeePaymentMethod($employeeId);
        // sync employee work locations
        $this->syncEmployeeWorkAddresses($employeeId, []);
        // get employee address
        $employee = $this->getEmployeeHomeAddress($employeeId);
        //
        if (
            $employee['Location_Address'] &&
            $employee['Location_City'] &&
            $employee['state_code'] &&
            $employee['Location_ZipCode']
        ) {
            // sync employee address
            $this->createEmployeeHomeAddress($employeeId, [
                'street_1' => $employee['Location_Address'],
                'street_2' => $employee['Location_Address_2'],
                'city' => $employee['Location_City'],
                'state' => strtoupper($employee['state_code']),
                'zip' => $employee['Location_ZipCode'],
            ]);
        }
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
        // create earning types
        $this->createCompanyEarningTypes($companyId);
        // sync the earning types
        $this->syncCompanyEarningTypes($companyId);
        // sync web hooks
        // $this->syncCompanyWebHook();
        // create company webhook
        // $this->createCompanyWebHook();

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
                    //
                    $previousStatus = $this->db
                        ->select('flsa_status')
                        ->where([
                            'gusto_uuid' => $compensation['uuid']
                        ])
                        ->get('gusto_employees_jobs_compensations')
                        ->row_array()['flsa_status'];
                    //
                    if ($previousStatus == "Salaried Commission" && $compensation['flsa_status'] == "Salaried Nonexempt") {
                        unset($ins['flsa_status']);
                    }
                    //
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
     * sync employee federal tax with Gusto
     *
     * @param int   $employeeId
     * @return array
     */
    private function syncEmployeeFederalTax(int $employeeId): array
    {
        // get the federal tax
        $record = $this->db
            ->select("
                marriage_status,
                mjsw_status,
                dependents_children,
                other_dependents,
                other_income,
                other_deductions,
                additional_tax
            ")
            ->where([
                "user_type" => "employee",
                "employer_sid" => $employeeId
            ])
            ->get("form_w4_original")
            ->row_array();
        //
        if (!$record) {
            return ["success" => false, "msg" => "No federal tax found."];
        }
        //
        $record['dependents_children'] = (int)$record['dependents_children'];
        $record['other_dependents'] = (int)$record['other_dependents'];
        //
        $data = [];
        //
        $data['filing_status'] = $record['marriage_status'] == 'jointly' ? "Married" : "Single";
        if ($record['marriage_status'] == 'head') {
            $data['filing_status'] = "Head of Household";
        } elseif ($record['marriage_status'] == 'separately') {
            $data['filing_status'] = "Single";
        }
        $data['two_jobs'] = $record['mjsw_status'] === "similar_pay" ? "yes" : "no";
        //
        $data['dependents_amount'] = $record['dependents_children'] + $record['other_dependents'];
        $data['extra_withholding'] = (int) $record['additional_tax'];
        $data['other_income'] = (int) $record['other_income'];
        $data['deductions'] = (int) $record['other_deductions'];
        $data['w4_data_type'] = "rev_2020_w4";
        //
        $gustoFederalTax = $this->db
            ->where('employee_sid', $employeeId)
            ->count_all_results('gusto_employees_federal_tax');
        //
        $method = !$gustoFederalTax ? 'createEmployeeFederalTax' : 'updateEmployeeFederalTax';
        // let's update employee's home address
        return $this->payroll_model
            ->$method(
                $employeeId,
                $data
            );
    }

    /**
     * sync employee payment method
     *
     * @param int $employeeId
     * @return array
     */
    public function syncEmployeePaymentMethod(int $employeeId): array
    {
        // let's check the employee
        $gustoEmployee = $this->db
            ->select('gusto_uuid, gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        // check and create
        if (!$gustoEmployee) {
            return [];
        }
        //
        // get employee payment method
        $paymentMethod = $this->db
            ->select('payment_method')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array()['payment_method'];
        //
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
    public function addEmployeeBankAccountToGusto(int $employeeId, array $data): array
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
            ->reset_query()
            ->where('sid', $data['sid'])
            ->update(
                'bank_account_details',
                [
                    'gusto_uuid' => $gustoResponse['uuid'],
                ]
            );
        //
        return ['success' => true, 'gusto_uuid' => $gustoResponse['uuid']];
    }

    /**
     * delete employees bank account
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function deleteEmployeeBankAccountToGusto(int $employeeId, array $data): array
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
     * @param array   $info
     */
    public function syncEmployeeWorkAddresses(int $employeeId, array $info): array
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
            //
            if ($info) {
                $locationUUID = $info['location_uuid'];
                $effectiveDate = $info['start_date'];
            } else {
                $locations = $this->payroll_model->getCompanyLocations(
                    $gustoEmployee['company_sid']
                );
                //
                $personalDetails = $this->payroll_model->getEmployeePersonalDetailsForGusto(
                    $employeeId
                );
                //
                $locationUUID = $locations[0]['gusto_uuid'];
                $effectiveDate = formatDateToDB(
                    $personalDetails['start_date'],
                    SITE_DATE,
                    DB_DATE
                );
            }
            //
            if ($locationUUID && $effectiveDate) {
                // make request
                $request = [];
                $request['location_uuid'] = $locationUUID;
                $request['effective_date'] = $effectiveDate;
                //                           
                // response
                $gustoResponse = gustoCall(
                    'createEmployeeWorkAddress',
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
                $ins = [];

                $ins['employee_sid'] = $employeeId;
                $ins['gusto_location_uuid'] = $gustoResponse['location_uuid'];
                $ins['gusto_version'] = $gustoResponse['version'];
                $ins['effective_date'] = $gustoResponse['effective_date'];
                $ins['active'] = $gustoResponse['active'];
                $ins['street_1'] = $gustoResponse['street_1'];
                $ins['street_2'] = $gustoResponse['street_2'];
                $ins['city'] = $gustoResponse['city'];
                $ins['state'] = $gustoResponse['state'];
                $ins['zip'] = $gustoResponse['zip'];
                $ins['country'] = $gustoResponse['country'];
                $ins['gusto_uuid'] = $gustoResponse['uuid'];
                $ins['updated_at'] = getSystemDate();
                $ins['created_at'] = getSystemDate();
                // insert
                $this->db->insert('gusto_companies_employees_work_addresses', $ins);
                //
            }
            //
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
        // $request['location_uuid'] = $location['gusto_uuid']; // The location_uuid parameter is deprecated
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
                    'minimum_wages' => serialize($compensation['minimum_wages']),
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
     * get company minimum wages
     *
     * @param int $employeeId
     * @return array
     */
    public function getCompanyMinimumWages($employeeId)
    {
        $companyId = getEmployeeUserParent_sid($employeeId);
        // get the company location
        return $this->db
            ->select('
                gusto_uuid,
                wage,
                wage_type,
                effective_date,
                authority,
                sid,
            ')
            ->where('company_sid', $companyId)
            ->get('company_minimum_wages')
            ->result_array();
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
                    'gusto_uuid',
                    'company_sid',
                ]
            );
        //
        // sync employee work locations
        $this->syncEmployeeWorkAddresses($employeeId, $data);
        // get the job
        $gustoJob = $this->db
            ->select('sid, title, gusto_uuid, gusto_location_uuid, hire_date, gusto_version')
            ->where([
                'employee_sid' => $employeeId,
                'is_primary' => 1
            ])
            ->get('gusto_employees_jobs')
            ->row_array();
        //
        if (!$gustoJob) {
            $gustoJob = $this->createEmployeeJob($employeeId, $gustoEmployee['gusto_uuid'], $gustoEmployee['company_sid']);
            //
            if ($gustoJob['errors']) {
                return $gustoJob;
            }
        }
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
                //
                $previousStatus = $this->db
                    ->select('flsa_status')
                    ->where([
                        'gusto_uuid' => $compensation['uuid']
                    ])
                    ->get('gusto_employees_jobs_compensations')
                    ->row_array()['flsa_status'];
                //
                if ($previousStatus == "Salaried Commission" && $compensation['flsa_status'] == "Salaried Nonexempt") {
                    unset($ins['flsa_status']);
                }
                //
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
        array $data,
        bool $updateJob = true
    ): array {
        //

        if ($updateJob) {
            $response = $this->updateEmployeeJob($employeeId, ['title' => $data['title']]);
            //
            if ($response['errors']) {
                return $response;
            }
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
        $request['flsa_status'] = $data['classification'] == 'Salaried Commission' ? 'Salaried Nonexempt' : $data['classification'];
        $request['payment_unit'] = $data['per'];
        //
        $wagesInfo = $this->getMinimumWagesData($data['minimumWage'], $data['wagesId']);
        $request['adjust_for_minimum_wage'] = $wagesInfo['minimumWage'] == 1 ? true : false;
        //
        if ($wagesInfo['minimumWage'] == 1) {
            $request['minimum_wages'] = $wagesInfo['minimum_wages'];
        }
        //
        // response
        //
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
        $ins['flsa_status'] = $data['classification'];
        $ins['effective_date'] = $gustoResponse['effective_date'];
        $ins['adjust_for_minimum_wage'] = $gustoResponse['adjust_for_minimum_wage'];
        $ins['minimum_wages'] = serialize($wagesInfo['minimum_wages']);
        //
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

    public function getMinimumWagesData($minimumWage, $wagesId)
    {
        //
        $response = [
            'minimumWage' => 0,
            'minimum_wages' => []
        ];
        //
        if ($minimumWage == 1 && !empty($wagesId)) {
            //
            $response['minimumWage'] = 1;
            //
            foreach ($wagesId as $id) {
                //
                $uuid = $this->db
                    ->select('gusto_uuid')
                    ->where('sid', $id)
                    ->get('company_minimum_wages')
                    ->row_array()['gusto_uuid'];
                //
                $wageInfo = array('uuid' => $uuid);
                //
                $response['minimum_wages'][] = $wageInfo;
            }
        }
        return $response;
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
        $upd["home_address"] = 1;
        //
        $this->db
            ->where(['employee_sid' => $employeeId])
            ->update('gusto_companies_employees', $upd);
        //
        updateUserById(
            [
                "Location_Address" => $request['street_1'],
                "Location_Address_2" => $request['street_2'],
                "Location_City" => $request['city'],
                "Location_State" => getStateColumn(["state_code" => $request["state"]], "sid"),
                "Location_ZipCode" => $request["zip"],
            ],
            $employeeId
        );
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
        if ($gustoResponse['type'] == 'Check') {
            //
            $this->db
                ->where('users_sid', $employeeId)
                ->where('users_type', 'employee')
                ->update('bank_account_details', [
                    'gusto_uuid' => null
                ]);
        }
        //
        updateUserById([
            'payment_method' => stringToSlug($gustoResponse['type'], '_')
        ], $employeeId);
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

    /**
     * get employee bank account
     *
     * @param int   $employeeId
     * @param int   $companyId
     * @param array $data
     */
    public function getEmployeeBankAccountId(
        int $employeeId,
        int $companyId,
        array $data
    ): int {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
            sid,
            gusto_uuid
        ')
            ->where([
                'users_sid' => $employeeId,
                'users_type' => 'employee'
            ])
            ->order_by('sid', 'desc')
            ->get('bank_account_details')
            ->result_array();
        // if none found than create a new one
        if (!$bankAccounts) {
            // we need to add a new one
            $this->db
                ->insert(
                    'bank_account_details',
                    [
                        'company_sid' => $companyId,
                        'users_type' => 'employee',
                        'users_sid' => $employeeId,
                        'account_title' => strtolower($data['accountTitle']),
                        'routing_transaction_number' => $data['routingNumber'],
                        'account_number' => $data['accountNumber'],
                        'account_type' => $data['accountType'],
                        'account_status' => 'primary',
                        'deposit_type' => 'percentage',
                        'account_percentage' => 100,
                        'employee_number' => $employeeId,
                    ]
                );
            //
            return $this->db->insert_id();
        }
        // already account exists needs to link it
        if (count($bankAccounts) == 2) {
            return 0;
        }
        // we need to add a new one
        $this->db
            ->insert(
                'bank_account_details',
                [
                    'company_sid' => $companyId,
                    'users_type' => 'employee',
                    'users_sid' => $employeeId,
                    'account_title' => strtolower($data['accountTitle']),
                    'routing_transaction_number' => $data['routingNumber'],
                    'account_number' => $data['accountNumber'],
                    'account_type' => $data['accountType'],
                    'account_status' => 'primary',
                    'deposit_type' => 'secondary',
                    'account_percentage' => 100,
                    'employee_number' => $employeeId,
                ]
            );
        //
        return $this->db->insert_id();
    }


    /**
     * get employee bank accounts
     *
     * @param int   $employeeId
     */
    public function getEmployeeBankAccountsById(
        int $employeeId,
        bool $doMoveToGusto = false
    ): array {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
                sid,
                account_title,
                account_number,
                routing_transaction_number,
                account_type,
                deposit_type,
                account_percentage,
                gusto_uuid
            ')
            ->where([
                'users_sid' => $employeeId,
                'users_type' => 'employee'
            ])
            // ->where('gusto_uuid <> ', '')
            // ->where('gusto_uuid is not null', null, null)
            ->order_by('sid', 'asc')
            ->limit(2)
            ->get('bank_account_details')
            ->result_array();
        //
        if (!$bankAccounts) {
            return [];
        }
        if (!$doMoveToGusto) {
            return $bankAccounts;
        }
        //
        $didIt = false;
        //
        foreach ($bankAccounts as $index => $bankAccount) {
            //
            if (!$bankAccount['gusto_uuid']) {
                //
                $response = $this->addEmployeeBankAccountToGusto($employeeId, [
                    'account_title' => $bankAccount['account_title'],
                    'account_number' => $bankAccount['account_number'],
                    'routing_transaction_number' => $bankAccount['routing_transaction_number'],
                    'account_type' => $bankAccount['account_type'],
                    'sid' => $bankAccount['sid'],
                ]);
                //
                if ($response['errors']) {
                    unset($bankAccounts[$index]);
                } else {
                    $didIt = true;
                    $bankAccounts[$index]['gusto_uuid'] = $response['gusto_uuid'];
                }
            }
        }
        //
        if ($didIt) {
            $this->syncEmployeePaymentMethodFromGusto($employeeId);
        }
        //
        return $bankAccounts;
    }

    /**
     * get employee bank accounts
     *
     * @param int   $employeeId
     */
    public function getEmployeeBankAccountsByIdWithGusto(
        int $employeeId,
        bool $doMoveToGusto = false
    ): array {
        // get employee bank accounts
        $bankAccounts = $this->db
            ->select('
                sid,
                account_title,
                account_number,
                routing_transaction_number,
                account_type,
                deposit_type,
                account_percentage,
                gusto_uuid
            ')
            ->where([
                'users_sid' => $employeeId,
                'users_type' => 'employee'
            ])
            ->where('gusto_uuid <> ', '')
            ->where('gusto_uuid is not null', null, null)
            ->order_by('sid', 'asc')
            ->limit(2)
            ->get('bank_account_details')
            ->result_array();
        //
        if (!$bankAccounts) {
            return [];
        }
        if (!$doMoveToGusto) {
            return $bankAccounts;
        }
        //
        $didIt = false;
        //
        foreach ($bankAccounts as $index => $bankAccount) {
            //
            if (!$bankAccount['gusto_uuid']) {
                //
                $response = $this->addEmployeeBankAccountToGusto($employeeId, [
                    'account_title' => $bankAccount['account_title'],
                    'account_number' => $bankAccount['account_number'],
                    'routing_transaction_number' => $bankAccount['routing_transaction_number'],
                    'account_type' => $bankAccount['account_type'],
                    'sid' => $bankAccount['sid'],
                ]);
                //
                if ($response['errors']) {
                    unset($bankAccounts[$index]);
                } else {
                    $didIt = true;
                    $bankAccounts[$index]['gusto_uuid'] = $response['gusto_uuid'];
                }
            }
        }
        //
        if ($didIt) {
            $this->syncEmployeePaymentMethodFromGusto($employeeId);
        }
        //
        return $bankAccounts;
    }

    /**
     * get employee bank accounts
     *
     * @param int   $employeeId
     */
    public function useEmployeeSingleBankAccount(
        int $employeeId,
        int $bankId
    ): array {
        // get employee bank accounts
        $bankAccount = $this->db
            ->select('
                sid,
                account_title,
                account_number,
                routing_transaction_number,
                account_type
            ')
            ->where('sid', $bankId)
            ->get('bank_account_details')
            ->row_array();
        //
        if (!$bankAccount) {
            return [
                'error' => 'Failed to verify bank account.'
            ];
        }
        //
        $gustoResponse = $this->addEmployeeBankAccountToGusto($employeeId, [
            'account_title' => $bankAccount['account_title'],
            'account_number' => $bankAccount['account_number'],
            'routing_transaction_number' => $bankAccount['routing_transaction_number'],
            'account_type' => $bankAccount['account_type'],
            'sid' => $bankAccount['sid'],
        ]);
        //
        if ($gustoResponse['errors']) {
            return $gustoResponse;
        }
        //
        $this->syncEmployeePaymentMethodFromGusto($employeeId);
        //
        return [
            'success' => true
        ];
    }

    /**
     * add contractor
     *
     * @param int   $companyId
     * @param array $data
     */
    public function addContractor(
        int $companyId,
        array $data
    ): array {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // ste request
        $request = [];
        $request['type'] = $data['type'];
        $request['wage_type'] = $data['wageType'];
        $request['email'] = $data['email'];
        $request['is_active'] = $data['isActive'] ? "true" : "false";
        $request['self_onboarding'] = "false";
        $request['start_date'] = formatDateToDB(
            $data['startDate'],
            SITE_DATE,
            DB_DATE
        );
        //
        if ($request['wage_type'] === 'Hourly') {
            $request['hourly_rate'] = $data['hourlyRate'];
        }
        // for individual
        if ($request['type'] === 'Individual') {
            $request['first_name'] = $data['firstName'];
            $request['last_name'] = $data['lastName'];
            $request['middle_initial'] = substr($data['middleInitial'], 0, 1);
            $request['file_new_hire_report'] = $data['fileNewHireReport'] ? "true" : "false";
            if ($request['file_new_hire_report'] === 'true') {
                $request['work_state'] = strtoupper($data['workState']);
            }
            $request['ssn'] =
                preg_replace('/\D/', '', $data['ssn']);
        } elseif ($request['type'] === 'Business') { // for business
            $request['business_name'] = $data['businessName'];
            $request['ein'] = preg_replace('/\D/', '', $data['ein']);
        }

        // response
        $gustoResponse = gustoCall(
            'createContractor',
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
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['gusto_version'] = $gustoResponse['version'];
        $ins['contractor_type'] = $gustoResponse['type'];
        $ins['wage_type'] = $gustoResponse['wage_type'];
        $ins['is_active'] = $gustoResponse['is_active'];
        $ins['first_name'] = $gustoResponse['first_name'];
        $ins['last_name'] = $gustoResponse['last_name'];
        $ins['middle_initial'] = $gustoResponse['middle_initial'];
        $ins['business_name'] = $gustoResponse['business_name'];
        $ins['ein'] = $request['ein'] ?? $request['ssn'];
        $ins['email'] = $gustoResponse['email'];
        $ins['start_date'] = $gustoResponse['start_date'];
        //
        if ($gustoResponse['address']) {
            $ins['street_1'] = $gustoResponse['address']['street_1'];
            $ins['street_2'] = $gustoResponse['address']['street_2'];
            $ins['city'] = $gustoResponse['address']['city'];
            $ins['state'] = $gustoResponse['address']['state'];
            $ins['zip'] = $gustoResponse['address']['zip'];
            $ins['country'] = $gustoResponse['address']['country'];
        }
        $ins['hourly_rate'] = $gustoResponse['hourly_rate'];
        $ins['file_new_hire_report'] = $gustoResponse['file_new_hire_report'];
        $ins['work_state'] = $gustoResponse['work_state'];
        $ins['onboarded'] = $gustoResponse['onboarded'];
        $ins['onboarding_status'] = $gustoResponse['onboarding_status'];
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        $ins['personal_details'] = 1;
        //
        $this->db->insert(
            'gusto_contractors',
            $ins
        );
        //
        return ['success' => true];
    }

    /**
     * modify contractor
     *
     * @param int   $contractorId
     * @param array $data
     */
    public function updateContractor(
        int $contractorId,
        array $data
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            gusto_version,
            company_sid,
            ein,
            contractor_type
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        // ste request
        $request = [];
        $request['version'] = $contractor['gusto_version'];
        $request['type'] = $contractor['contractor_type'];
        $request['wage_type'] = $data['wageType'];
        $request['email'] = $data['email'];
        $request['is_active'] = $data['isActive'] ? "true" : "false";
        $request['self_onboarding'] = "false";
        $request['start_date'] = formatDateToDB(
            $data['startDate'],
            SITE_DATE,
            DB_DATE
        );
        //
        if ($request['wage_type'] === 'Hourly') {
            $request['hourly_rate'] = $data['hourlyRate'];
        }
        // for individual
        if ($request['type'] === 'Individual') {
            $request['first_name'] = $data['firstName'];
            $request['last_name'] = $data['lastName'];
            $request['middle_initial'] = substr($data['middleInitial'], 0, 1);
            $request['file_new_hire_report'] = $data['fileNewHireReport'] ? "true" : "false";
            if ($request['file_new_hire_report'] === 'true') {
                $request['work_state'] = strtoupper($data['workState']);
            }
            $request['ssn'] =
                preg_match('/x/i', $data['ssn']) ? $contractor['ein'] :
                preg_replace('/\D/', '', $data['ssn']);
        } elseif ($request['type'] === 'Business') { // for business
            $request['business_name'] = $data['businessName'];
            $request['ein'] =
                preg_match('/x/i', $data['ein']) ? $contractor['ein'] :
                preg_replace('/\D/', '', $data['ein']);
        }
        //
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'updateContractor',
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
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['contractor_type'] = $gustoResponse['type'];
        $upd['wage_type'] = $gustoResponse['wage_type'];
        $upd['is_active'] = $gustoResponse['is_active'];
        $upd['first_name'] = $gustoResponse['first_name'];
        $upd['last_name'] = $gustoResponse['last_name'];
        $upd['middle_initial'] = $gustoResponse['middle_initial'];
        $upd['business_name'] = $gustoResponse['business_name'];
        $upd['ein'] = $request['ein'] ?? $request['ssn'];
        $upd['email'] = $gustoResponse['email'];
        $upd['start_date'] = $gustoResponse['start_date'];
        //
        if ($gustoResponse['address']) {
            $upd['street_1'] = $gustoResponse['address']['street_1'];
            $upd['street_2'] = $gustoResponse['address']['street_2'];
            $upd['city'] = $gustoResponse['address']['city'];
            $upd['state'] = $gustoResponse['address']['state'];
            $upd['zip'] = $gustoResponse['address']['zip'];
            $upd['country'] = $gustoResponse['address']['country'];
        }
        $upd['hourly_rate'] = $gustoResponse['hourly_rate'];
        $upd['file_new_hire_report'] = $gustoResponse['file_new_hire_report'];
        $upd['work_state'] = $gustoResponse['work_state'];
        $upd['onboarded'] = $gustoResponse['onboarded'];
        $upd['onboarding_status'] = $gustoResponse['onboarding_status'];
        $upd['updated_at'] = getSystemDate();
        $upd['personal_details'] = 1;
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }

    /**
     * sync contractor home address
     *
     * @param int   $contractorId
     */
    public function syncContractorHomeAddress(
        int $contractorId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractorHomeAddress',
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
        $upd = [];
        $upd['gusto_address_version'] = $gustoResponse['version'];
        $upd['street_1'] = $gustoResponse['street_1'];
        $upd['street_2'] = $gustoResponse['street_2'];
        $upd['city'] = $gustoResponse['city'];
        $upd['state'] = $gustoResponse['state'];
        $upd['zip'] = $gustoResponse['zip'];
        $upd['country'] = $gustoResponse['country'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }

    /**
     * sync contractor home address
     *
     * @param int   $contractorId
     * @param array $data
     * @return array
     */
    public function updateContractorHomeAddress(
        int $contractorId,
        array $data
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            gusto_address_version,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        //
        $request = [];
        $request['version'] = $contractor['gusto_address_version'];
        $request['street_1'] = $data['street_1'];
        $request['street_2'] = $data['street_2'];
        $request['city'] = $data['city'];
        $request['state'] = $data['state'];
        $request['zip'] = $data['zip'];
        $request['country'] = 'USA';
        // response
        $gustoResponse = gustoCall(
            'updateContractorHomeAddress',
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
        $upd['gusto_address_version'] = $gustoResponse['version'];
        $upd['street_1'] = $gustoResponse['street_1'];
        $upd['street_2'] = $gustoResponse['street_2'];
        $upd['city'] = $gustoResponse['city'];
        $upd['state'] = $gustoResponse['state'];
        $upd['zip'] = $gustoResponse['zip'];
        $upd['country'] = $gustoResponse['country'];
        $upd['updated_at'] = getSystemDate();
        $upd['address'] = 1;
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }

    /**
     * sync contractor home address
     *
     * @param int $contractorId
     * @return array
     */
    public function syncContractorPaymentMethod(
        int $contractorId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractorPaymentMethod',
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
        $upd = [];
        $upd['gusto_payment_method_version'] = $gustoResponse['version'];
        $upd['payment_method_type'] = $gustoResponse['type'];
        $upd['splits_by'] = $gustoResponse['split_by'];
        $upd['splits'] = json_encode($gustoResponse['splits']);
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }


    /**
     * sync contractor home address
     *
     * @param int $contractorId
     * @return array
     */
    public function checkContractorOnboard(
        int $contractorId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractorStatus',
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
        $upd = [];
        $upd['onboarding_status'] = $gustoResponse['onboarding_status'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true, 'response' => $gustoResponse];
    }

    /**
     * sync contractor home address
     *
     * @param int $contractorId
     * @return array
     */
    public function syncContractor(
        int $contractorId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractor',
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
        $upd = [];
        $upd['gusto_version'] = $gustoResponse['version'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }

    /**
     * sync contractor home address
     *
     * @param int $contractorId
     * @return array
     */
    public function syncContractorDocuments(
        int $contractorId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractorDocuments',
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
        if ($gustoResponse) {
            foreach ($gustoResponse as $form) {
                //
                $ins = [];
                $ins['name'] = $form['name'];
                $ins['title'] = $form['title'];
                $ins['description'] = $form['description'];
                $ins['draft'] = $form['draft'];
                $ins['requires_signing'] = $form['requires_signing'];
                $ins['updated_at'] = getSystemDate();

                if ($this->db->where('gusto_uuid', $form['uuid'])->count_all_results('gusto_contractors_documents')) {
                    // update
                    $this->db->where('gusto_uuid', $form['uuid'])->update('gusto_contractors_documents', $ins);
                } else {
                    // insert
                    $ins['gusto_uuid'] = $form['uuid'];
                    $ins['created_at'] = getSystemDate();
                    $ins['contractor_sid'] = $contractorId;
                    $this->db->insert('gusto_contractors_documents', $ins);
                }
            }
        }
        //
        return ['success' => true];
    }

    /**
     * sync contractor home address
     *
     * @param int $contractorId
     * @param array $data
     * @return array
     */
    public function updateContractorPaymentMethod(
        int $contractorId,
        array $data
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid,
            gusto_payment_method_version,
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        //
        if ($data['type'] === 'Direct Deposit') {
            //
            $request = [];
            $request['name'] = $data['accountName'];
            $request['routing_number'] = $data['routingNumber'];
            $request['account_number'] = $data['accountNumber'];
            $request['account_type'] = ucwords($data['accountType']);
            // lets create the bank first
            $gustoBankResponse = gustoCall(
                'createContractorBankAccount',
                $companyDetails,
                $request,
                "POST"
            );
            //
            $errors = hasGustoErrors($gustoBankResponse);
            //
            if ($errors) {
                return $errors;
            }
            //
            $this->db
                ->insert('gusto_contractors_bank_accounts', [
                    'contractor_sid' => $contractorId,
                    'name' => $gustoBankResponse['name'],
                    'account_type' => $gustoBankResponse['account_type'],
                    'routing_number' => $gustoBankResponse['routing_number'],
                    'account_number' => $data['accountNumber'],
                    'created_at' => getSystemDate(),
                    'updated_at' => getSystemDate(),
                ]);
            //
            $this->syncContractorPaymentMethod($contractorId);
            // get details
            $contractor = $this->db
                ->select('
                    gusto_uuid,
                    company_sid,
                    gusto_payment_method_version,
                ')
                ->where('sid', $contractorId)
                ->get('gusto_contractors')
                ->row_array();
        }
        //
        $request = [];
        $request['version'] = $contractor['gusto_payment_method_version'];
        $request['type'] = $data['type'];
        // response
        $gustoResponse = gustoCall(
            'updateContractorPaymentMethod',
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
        $upd['gusto_payment_method_version'] = $gustoResponse['version'];
        $upd['payment_method_type'] = $gustoResponse['type'];
        $upd['splits_by'] = $gustoResponse['split_by'];
        $upd['splits'] = json_encode($gustoResponse['splits']);
        $upd['updated_at'] = getSystemDate();
        $upd['payment_method'] = 1;
        //
        $this->db
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                $upd
            );
        //
        return ['success' => true];
    }

    /**
     * get all contractors
     */
    public function getPayrollContractors(int $companyId): array
    {
        //
        return $this->db
            ->where('company_sid', $companyId)
            ->get('gusto_contractors')
            ->result_array();
    }

    /**
     * get specific contractors
     *
     * @param int $contractorId
     * @param array $columns Optional
     * @return array
     */
    public function getContractorById(
        int $contractorId,
        array $columns = ['*']
    ): array {
        //
        return $this->db
            ->select($columns)
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
    }

    /**
     * get contractors bank accounts
     *
     * @param int $contractorId
     * @return array
     */
    public function getContractorBankAccount(
        int $contractorId
    ): array {
        //
        return $this->db
            ->where('contractor_sid', $contractorId)
            ->order_by('sid', 'desc')
            ->limit(1)
            ->get('gusto_contractors_bank_accounts')
            ->row_array();
    }

    /**
     * get contractors bank accounts
     *
     * @param int $contractorId
     * @return array
     */
    public function getContractorDocuments(
        int $contractorId
    ): array {
        //
        return $this->db
            ->order_by('sid', 'desc')
            ->where('contractor_sid', $contractorId)
            ->get('gusto_contractors_documents')
            ->result_array();
    }

    /**
     * check and update contractor onboard status
     *
     * @param int $contractorId
     * @return array
     */
    public function checkAndUpdateContractorOnboard(int $contractorId): array
    {
        //
        $whereArray = [
            'sid' => $contractorId,
            'personal_details' => 1,
            'payment_method' => 1,
            'address' => 1,
            'onboarded' => 0
        ];
        //
        if (!$this->db->where($whereArray)->count_all_results('gusto_contractors')) {
            return ['errors' => ['Please see summary to see mandatory steps.']];
        }
        // need to onboard the contractor
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'updateContractorOnboardingStatus',
            $companyDetails,
            [
                'onboarding_status' => 'onboarding_completed'
            ],
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
            ->where('sid', $contractorId)
            ->update(
                'gusto_contractors',
                [
                    'onboarded' => 1,
                    'onboarding_status' => 'onboarding_completed'
                ]
            );
        //
        return ['success' => true];
    }

    /**
     * get the contractor document in PDF
     *
     * @param int $contractorId
     * @param int $formId
     * @return array
     */
    public function getContractorDocument(
        int $contractorId,
        int $formId
    ): array {
        // get details
        $contractor = $this->db
            ->select('
            gusto_uuid,
            company_sid
        ')
            ->where('sid', $contractorId)
            ->get('gusto_contractors')
            ->row_array();
        // get the form
        $form =
            $this->db
            ->select('
            gusto_uuid,
            title
        ')
            ->where('sid', $formId)
            ->get('gusto_contractors_documents')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($contractor['company_sid']);
        $companyDetails['other_uuid'] = $contractor['gusto_uuid'];
        $companyDetails['other_uuid_2'] = $form['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'getContractorFormPdf',
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
        $gustoResponse['title'] = $form['title'];
        //
        return ['success' => true, 'response' => $gustoResponse];
    }


    /**
     * get company earning types
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyEarningTypes(
        int $companyId
    ): array {
        // get earnings
        $earnings = $this->db
            ->select('
                sid,
                name,
                is_default,
                fields_json,
                created_at
            ')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_earning_types')
            ->result_array();
        //
        if (!$earnings) {
            // fetch them from Gusto
            $earnings = $this->syncCompanyEarningTypes($companyId, true);
        }
        //
        return $earnings;
    }

    /**
     * get company earning types
     *
     * @param int $companyId
     * @param bool $return Optional
     * @return array
     */
    private function syncCompanyEarningTypes(
        int $companyId,
        bool $return = false
    ): array {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // response
        $gustoResponse = gustoCall(
            'getCompanyEarningTypes',
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
        // for default
        if ($gustoResponse && $gustoResponse['default']) {
            foreach ($gustoResponse['default'] as $type) {
                //
                $ins = [];
                $ins['name'] = $type['name'];
                $ins['gusto_uuid'] = $type['uuid'];
                $ins['is_default'] = 1;
                $ins['updated_at'] = getSystemDate();

                if ($this->db->where([
                    'gusto_uuid' => $type['uuid'],
                    "company_sid" => $companyId
                ])->count_all_results('gusto_companies_earning_types')) {
                    // update
                    $this->db->where([
                        'gusto_uuid' => $type['uuid'],
                        "company_sid" => $companyId
                    ])->update('gusto_companies_earning_types', $ins);
                } else {
                    // insert
                    $ins['created_at'] = getSystemDate();
                    $ins['company_sid'] = $companyId;
                    $this->db->insert('gusto_companies_earning_types', $ins);
                }
            }
        }
        // for custom
        if ($gustoResponse && $gustoResponse['custom']) {
            foreach ($gustoResponse['custom'] as $type) {
                //
                $ins = [];
                $ins['name'] = $type['name'];
                $ins['gusto_uuid'] = $type['uuid'];
                $ins['is_default'] = 0;
                $ins['updated_at'] = getSystemDate();

                if ($this->db->where([
                    'gusto_uuid' => $type['uuid'],
                    "company_sid" => $companyId
                ])->count_all_results('gusto_companies_earning_types')) {
                    // update
                    $this->db->where([
                        'gusto_uuid' => $type['uuid'],
                        "company_sid" => $companyId
                    ])->update('gusto_companies_earning_types', $ins);
                } else {
                    // insert
                    $ins['created_at'] = getSystemDate();
                    $ins['company_sid'] = $companyId;
                    $this->db->insert('gusto_companies_earning_types', $ins);
                }
            }
        }
        //
        if ($return) {
            //
            return $this->db
                ->select('
                    sid,
                    name,
                    is_default,
                    created_at
                ')
                ->where('company_sid', $companyId)
                ->get('gusto_companies_earning_types')
                ->result_array();
        }
        //
        return ['success' => true];
    }

    /**
     * deactivate company earning types
     *
     * @param int $earningId
     * @return array
     */
    public function checkCompanyEarningType(
        int $earningId,
        int $companyId
    ): int {
        // get earning
        return $this->db
            ->where('sid', $earningId)
            ->where('company_sid', $companyId)
            ->where('is_default', 0)
            ->count_all_results('gusto_companies_earning_types');
    }

    /**
     * get company earning type
     *
     * @param int $earningId
     * @param int $companyId
     * @return array
     */
    public function getSingleEarning(
        int $earningId,
        int $companyId
    ): array {
        // get earning
        return $this->db
            ->select('sid, name, fields_json, is_default')
            ->where('sid', $earningId)
            ->where('company_sid', $companyId)
            ->get('gusto_companies_earning_types')
            ->row_array();
    }

    /**
     * deactivate company earning types
     *
     * @param int $earningId
     * @return array
     */
    public function deactivateCompanyEarningType(
        int $earningId
    ): array {
        // get earning
        $earning = $this->db
            ->select('
                gusto_uuid,
                company_sid
            ')
            ->where('sid', $earningId)
            ->get('gusto_companies_earning_types')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($earning['company_sid']);
        $companyDetails['other_uuid'] = $earning['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'deactivateCompanyEarningTypes',
            $companyDetails,
            [],
            'DELETE'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('sid', $earningId)
            ->delete('gusto_companies_earning_types');
        //
        return ['success' => true];
    }

    /**
     * add company earning types
     *
     * @param int   $companyId
     * @param array $data
     * @return array
     */
    public function addCompanyEarningType(
        int $companyId,
        array $data
    ): array {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        // response
        $gustoResponse = gustoCall(
            'addCompanyEarningTypes',
            $companyDetails,
            [
                'name' => $data['name']
            ],
            'POST'
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['name'] = $gustoResponse['name'];
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['fields_json'] = json_encode($data);
        $ins['is_default'] = 0;
        $ins['updated_at'] = $ins['created_at'] = getSystemDate();
        $ins['company_sid'] = $companyId;
        $this->db->insert('gusto_companies_earning_types', $ins);
        //
        return ['success' => true];
    }

    /**
     * edit company earning types
     *
     * @param int   $earningId
     * @param array $data
     * @return array
     */
    public function editCompanyEarningType(
        int $earningId,
        array $data
    ): array {
        //
        $earning = $this->db
            ->select('gusto_uuid, company_sid, is_default')
            ->where('sid', $earningId)
            ->get('gusto_companies_earning_types')
            ->row_array();
        if ($earning["is_default"] == 0) {

            // get company details
            $companyDetails = $this->getCompanyDetailsForGusto($earning['company_sid']);
            $companyDetails['other_uuid'] = $earning['gusto_uuid'];
            // response
            $gustoResponse = gustoCall(
                'editCompanyEarningTypes',
                $companyDetails,
                [
                    'name' => $data['name']
                ],
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
            $upd['name'] = $gustoResponse['name'];
            $upd['fields_json'] = json_encode($data);
            $upd['updated_at'] = getSystemDate();
        } else {
            //
            $upd = [];
            $upd['fields_json'] = json_encode($data);
            $upd['updated_at'] = getSystemDate();
        }
        //
        $this->db
            ->where('sid', $earningId)
            ->update('gusto_companies_earning_types', $upd);
        //
        return ['success' => true];
    }

    /**
     * create company webhook
     *
     * @return array
     */
    public function createCompanyWebHook(): array
    {
        //
        if ($this->db->where('webhook_type', 'company')->count_all_results('gusto_companies_webhooks')) {
            return ['success' => true];
        }
        // response
        $gustoResponse = createCompanyWebHook(
            [
                'url' => base_url('gusto/subscriber'),
                'subscription_types' => [
                    'Company'
                ]
            ]
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['webhook_type'] = 'company';
        $ins['gusto_uuid'] = $gustoResponse['uuid'];
        $ins['status'] = $gustoResponse['status'];
        $ins['url'] = $gustoResponse['url'];
        $ins['subscription_types'] = json_encode($gustoResponse['subscription_types']);
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->db
            ->insert('gusto_companies_webhooks', $ins);
        //
        return ['success' => true];
    }

    /**
     * create company webhook
     *
     * @return array
     */
    public function syncCompanyWebHook(): array
    {
        // response
        $gustoResponse = getWebHooks();
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }

        if ($gustoResponse) {
            //
            foreach ($gustoResponse as $hook) {
                //
                $ins = [];
                $ins['webhook_type'] = 'company';
                $ins['url'] = $hook['url'];
                $ins['status'] = $hook['status'];
                $ins['subscription_types'] = json_encode($hook['subscription_types']);
                $ins['updated_at'] = getSystemDate();
                //
                if ($this->db->where('gusto_uuid', $hook['uuid'])->count_all_results('gusto_companies_webhooks')) {
                    $this->db
                        ->where('gusto_uuid', $hook['uuid'])
                        ->update('gusto_companies_webhooks', $ins);
                } else {
                    //
                    $ins['created_at'] = $ins['updated_at'];
                    $ins['gusto_uuid'] = $hook['uuid'];
                    $this->db->insert('gusto_companies_webhooks', $ins);
                }
            }
        }
        //
        return ['success' => true];
    }

    /**
     * update company webhook
     *
     * @param array $data
     * @return array
     */
    public function callWebHook(
        array $data
    ): array {
        // get webhook
        $webhook = $this->db
            ->select('gusto_uuid, sid')
            ->where([
                'webhook_type' => 'company',
            ])
            ->get('gusto_companies_webhooks')
            ->row_array();
        //
        $gustoResponse = callWebHook(
            $webhook['gusto_uuid'],
            [
                'verification_token' => $data['verification_token']
            ]
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $upd = [];
        $upd['status'] = $gustoResponse['status'];
        $upd['updated_at'] = getSystemDate();
        //
        $this->db
            ->where('sid', $webhook['sid'])
            ->insert('gusto_companies_webhooks', $upd);
        //
        return $gustoResponse;
    }

    /*
     * push the paid time off policies
     *
     * @param int $companyId
     * @return array
     */
    public function createCompanyEarningTypes(
        int $companyId
    ): array {
        // check if already exists
        if ($this->db->where([
            "name" => "Paid Time Off",
            "company_sid" => $companyId
        ])->count_all_results("gusto_companies_earning_types")) {
            return ['success' => true];
        }
        //
        return $this->addCompanyEarningType(
            $companyId,
            [
                'name' => "Paid Time Off"
            ]
        );
        // get the paid time offs
        $paidPolicies = $this->db
            ->select('sid, title')
            ->where([
                'company_sid' => $companyId,
                'policy_category_type' => 1
            ])
            ->get('timeoff_policies')
            ->result_array();

        if (!$paidPolicies) {
            //
            return $this->addCompanyEarningType(
                $companyId,
                [
                    'name' => "Paid Time Off"
                ]
            );
        }
        // loop through data
        foreach ($paidPolicies as $policy) {
            //
            if ($this->db->where('name', $policy['title'])->count_all_results('gusto_companies_earning_types')) {
                continue;
            }
            //
            $this->addCompanyEarningType(
                $companyId,
                [
                    'name' => $policy['title']
                ]
            );
        }
        //
        return ['success' => true];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     * @param array $data
     */
    public function updateEmployeePaymentMethodToGusto(
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
        // get gusto employee details
        $gustoPaymentMethod = $this
            ->db
            ->select('sid, gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_employees_payment_method')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        //
        $request = [];
        $request['version'] = $gustoPaymentMethod['gusto_version'];
        $request['type'] = $data['paymentType'];
        //
        if ($data['paymentType'] === 'Direct Deposit') {
            // set bank account array for Gusto
            $split = getBankAccountForGusto($data['accounts']);
            $request['split_by'] = $split['split_by'];
            $request['splits'] = $split['splits'];
        }
        // response
        $gustoResponse = gustoCall(
            'updatePaymentMethod',
            $companyDetails,
            $request,
            "PUT"
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
        $dataArray['type'] = $gustoResponse['type'];
        $dataArray['split_by'] = $gustoResponse['split_by'];
        $dataArray['splits'] = json_encode($gustoResponse['splits']);
        $dataArray['updated_at'] = getSystemDate();

        // update
        $this->db
            ->where('sid', $gustoPaymentMethod['sid'])
            ->update('gusto_employees_payment_method', $dataArray);
        // bank accounts
        $this->db
            ->where('users_sid', $employeeId)
            ->where('users_type', 'employee')
            ->update('bank_account_details', [
                'gusto_uuid' => null
            ]);
        //
        updateUserById([
            'payment_method' => stringToSlug($data['paymentType'], '_')
        ], $employeeId);

        //
        return ['success' => true, 'version' => $gustoResponse['version']];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function getEmployeeSummary(
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
            'getEmployeeSummary',
            $companyDetails,
            [],
            "GET"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        return ['success' => true, 'response' => $gustoResponse];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function syncEmployeeDocuments(
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
            'getEmployeeForms',
            $companyDetails,
            [],
            "GET"
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
        foreach ($gustoResponse as $form) {
            //
            $ins = [];
            $ins['form_name'] = $form['name'];
            $ins['form_title'] = $form['title'];
            $ins['description'] = $form['description'];
            $ins['requires_signing'] = $form['requires_signing'];
            $ins['draft'] = $form['draft'];
            $ins['updated_at'] = getSystemDate();
            // we need to check the current status of w4
            if ($form['name'] == 'US_W-4') {
                $document = $this->getEmployeeW4AssignStatus($employeeId);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            } elseif ($form['name'] == 'employee_direct_deposit') {
                $document = $this->getEmployeeDirectDepositAssignStatus($employeeId);
                $ins['status'] = $document['status'];
                $ins['document_sid'] = $document['documentId'];
            }
            //
            if ($this->db->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])->count_all_results('gusto_employees_forms')) {
                $this->db
                    ->where(['employee_sid' => $employeeId, 'gusto_uuid' => $form['uuid']])
                    ->update('gusto_employees_forms', $ins);
            } else {
                //
                $ins['company_sid'] = $gustoEmployee['company_sid'];
                $ins['employee_sid'] = $employeeId;
                $ins['gusto_uuid'] = $form['uuid'];
                $ins['created_at'] = getSystemDate();
                //
                $this->db
                    ->insert('gusto_employees_forms', $ins);
            }
        }
        //
        return ['success' => true];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function getEmployeeDocuments(
        int $employeeId
    ): array {
        return $this->db
            ->where(['employee_sid' => $employeeId])
            ->get('gusto_employees_forms')
            ->result_array();
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function getEmployeeW4AssignStatus(
        int $employeeId
    ): array {
        //
        $record = $this->db
            ->select('status, sid')
            ->where(['employer_sid' => $employeeId, 'user_type' => 'employee'])
            ->get('form_w4_original')
            ->row_array();
        //
        if (!$record) {
            return [
                'status' => 'pending',
                'documentId' => 0,
                'documentType' => 'w4_form'
            ];
        }
        //
        if ($record['status'] == 1) {
            return [
                'status' => 'assign',
                'documentId' => $record['sid'],
                'documentType' => 'w4_form'
            ];
        }
        //
        return [
            'status' => 'revoke',
            'documentId' => $record['sid'],
            'documentType' => 'w4_form'
        ];
    }

    /**
     * update employee's payment method
     *
     * @param int   $employeeId
     */
    public function getEmployeeDirectDepositAssignStatus(
        int $employeeId
    ): array {
        //
        $record = $this->db
            ->select('status, sid')
            ->where([
                'user_sid' => $employeeId,
                'user_type' => 'employee',
                'document_type' => 'direct_deposit'
            ])
            ->get('documents_assigned_general')
            ->row_array();
        //
        if (!$record) {
            return [
                'status' => 'pending',
                'documentId' => 0,
                'documentType' => 'direct_deposit'
            ];
        }
        //
        if ($record['status'] == 1) {
            return [
                'status' => 'assign',
                'documentId' => $record['sid'],
                'documentType' => 'direct_deposit'
            ];
        }
        return [
            'status' => 'revoke',
            'documentId' => $record['sid'],
            'documentType' => 'direct_deposit'
        ];
    }

    /**
     * update employee's payment method
     *
     * @param int    $employeeId
     * @param string $gustoUUID
     */
    public function signEmployeeForm(
        int $employeeId,
        string $gustoUUID
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
        // get employee name
        $userInfo = $this->db
            ->select('first_name, last_name')
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($gustoEmployee['company_sid']);
        //
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        $companyDetails['other_uuid_2'] = $gustoUUID;
        // response
        $gustoResponse = gustoCall(
            'signEmployeeForm',
            $companyDetails,
            [
                'signature_text' => (ucwords(trim($userInfo['first_name'] . ' ' . $userInfo['last_name']))),
                'agree' => "true",
                'signed_by_ip_address' => getUserIP(),
            ],
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('gusto_uuid', $gustoUUID)
            ->update(
                'gusto_employees_forms',
                [
                    'requires_signing' => $gustoResponse['requires_signing'],
                    'draft' => $gustoResponse['draft'],
                    'updated_at' => getSystemDate()
                ]
            );
        //
        return ['success' => true, 'response' => $gustoResponse];
    }

    /**
     * update employee's payment method
     *
     * @param int    $employeeId
     */
    public function finishEmployeeOnboard(
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
            'finishEmployeeOnboard',
            $companyDetails,
            [
                'onboarding_status' => "onboarding_completed"
            ],
            "PUT"
        );

        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        if ($gustoResponse['onboarding_status'] !== 'onboarding_completed') {
            return [
                'view' =>
                $this->load->view('v1/payroll/employees/flow', $gustoResponse, true)
            ];
        } else {
            //
            $this->db
                ->where('employee_sid', $employeeId)
                ->update(
                    'gusto_companies_employees',
                    [
                        'is_onboarded' => 1,
                        'personal_details' => 1,
                        'compensation_details' => 1,
                        'work_address' => 1,
                        'home_address' => 1,
                        'federal_tax' => 1,
                        'state_tax' => 1,
                        'updated_at' => getSystemDate()
                    ]
                );
        }
        //
        return ['msg' => 'You have successfully onboard an employee for payroll.'];
    }

    /**
     * update employee's payment method
     *
     * @param int    $employeeId
     */
    public function removeEmployee(
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
            'removeOnboardEmployee',
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
        $this->db->where('employee_sid', $employeeId)->delete('gusto_companies_employees');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_companies_employees_work_addresses');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_employees_federal_tax');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_employees_forms');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_employees_jobs');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_employees_payment_method');
        $this->db->where('employee_sid', $employeeId)->delete('gusto_employees_state_tax');
        $this->db
            ->where('users_sid', $employeeId)
            ->where('users_type', 'employee')
            ->update('bank_account_details', ['gusto_uuid' => null]);

        //
        return ['msg' => 'You have successfully removed selected employee from payroll.'];
    }

    /**
     * update employee's payment method
     *
     * @param int   $companyId
     * @param array $data
     */
    public function updatePaymentConfig(
        int $companyId,
        array $data
    ): array {

        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        //
        $companyDetails['other_uuid'] = $companyDetails['gusto_uuid'];
        // response
        $gustoResponse = gustoCall(
            'updatePaymentConfig',
            $companyDetails,
            $data,
            "PUT"
        );
        //
        $errors = hasGustoErrors($gustoResponse);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db
            ->where('company_sid', $companyId)
            ->update('companies_payment_configs', [
                'payment_speed' => $gustoResponse['payment_speed'],
                'fast_payment_limit' => $gustoResponse['fast_payment_limit'],
                'updated_at' => getSystemDate()
            ]);
        //
        return ['msg' => 'You have successfully updated settings.'];
    }

    /**
     * update employee's payment method
     *
     * @param int   $companyId
     * @param array $data
     */
    public function updateMode(
        int $companyId,
        array $data
    ): array {
        //
        $ins = [];
        $ins["stage"] = $data["mode"];
        $ins["updated_at"] = getSystemDate();
        //
        if ($this->db->where([
            "company_sid" => $companyId,
        ])->count_all_results("gusto_companies_mode")) {
            // UPDATE
            $this->db
                ->where('company_sid', $companyId)
                ->update('gusto_companies_mode', $ins);
        } else {
            // INSERT
            $ins["company_sid"] = $companyId;
            $ins["created_at"] = $ins["updated_at"];
            $this->db
                ->insert('gusto_companies_mode', $ins);
        }

        //
        return SendResponse(200, ['msg' => 'You have successfully updated company mode.']);
    }


    /**
     * get onboard payroll employees
     *
     * @param int $companyId
     * @return array
     */
    public function getPayrollOnboardEmployees(int $companyId): array
    {
        // fetch
        $records = $this->db
            ->select(
                getUserFields()
                    . '
                    joined_at,
                    registration_date,
                    rehire_date
                '
            )
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where('gusto_companies_employees.company_sid', $companyId)
            ->where('gusto_companies_employees.is_onboarded', 1)
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
                'hired_date' => get_employee_latest_joined_date(
                    $employee['registration_date'],
                    $employee['joined_at'],
                    ''
                ),
                'id' => $employee['userId'],
            ];
        }
        //
        return $tmp;
    }

    /**
     * get single onboard payroll employees
     *
     * @param int $companyId
     * @param int $employeeId
     * @return array
     */
    public function getPayrollEmployeeById(int $companyId, int $employeeId): array
    {
        // fetch
        $employee = $this->db
            ->select(
                getUserFields()
                    . '
                    joined_at,
                    registration_date,
                    rehire_date
                '
            )
            ->join('users', 'users.sid = gusto_companies_employees.employee_sid', 'inner')
            ->where('gusto_companies_employees.company_sid', $companyId)
            ->where('gusto_companies_employees.employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        //
        if (!$employee) {
            return [];
        }
        return  [
            'name' => remakeEmployeeName($employee),
            'hired_date' => get_employee_latest_joined_date(
                $employee['registration_date'],
                $employee['joined_at'],
                ''
            ),
            'id' => $employee['userId'],
        ];
    }

    public function createEmployeeJob(int $employeeId, string $gustoUUID, int $companyId): array
    {
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($companyId);
        $companyDetails['company_sid'] = $companyId;
        $companyDetails['other_uuid'] = $gustoUUID;
        //
        $gustoResponse = $this->createEmployeeJobOnGusto($employeeId, $companyDetails);
        //
        if ($gustoResponse['errors']) {
            return $gustoResponse;
        }
        // get the job
        return $this->db
            ->select('sid, title, gusto_uuid, gusto_location_uuid, hire_date, gusto_version')
            ->where([
                'employee_sid' => $employeeId,
                'is_primary' => 1
            ])
            ->get('gusto_employees_jobs')
            ->row_array();
    }

    /**
     * get all company active employees
     *
     * @param int $companyId
     * @return array
     */
    public function getSystemEmployees(int $companyId): array
    {
        $employees = $this->db->select(
            getUserFields()
        )
            ->where('parent_sid', $companyId)
            ->order_by('first_name', 'ASC')
            ->get('users')
            ->result_array();
        //
        if (!$employees) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($employees as $value) {
            //
            $tmp[] = [
                'value' => remakeEmployeeName($value),
                'id' => $value['userId']
            ];
        }
        //
        return $tmp;
    }

    /**
     * get all company active employees
     *
     * @param int $employeeId
     * @return array
     */
    public function getEmployeeById(int $employeeId): array
    {
        $employee = $this->db->select(
            getUserFields() . '
                ssn,
                dob,
                PhoneNumber,
                middle_name
            '
        )
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
        //
        $returnArray = [];
        $returnArray['id'] = $employee['userId'];
        $returnArray['first_name'] = $employee['first_name'];
        $returnArray['last_name'] = $employee['last_name'];
        $returnArray['middle_name'] = $employee['middle_name'];
        $returnArray['ssn'] = $employee['ssn'];
        $returnArray['date_of_birth'] = $employee['dob'] ? formatDateToDB($employee['dob'], DB_DATE, SITE_DATE) : '';
        $returnArray['email'] = $employee['email'];
        $returnArray['phone'] = $employee['PhoneNumber'];
        // get address
        $address = $this->getEmployeeHomeAddress($employeeId);
        $returnArray['street_1'] = $address['Location_Address'];
        $returnArray['street_2'] = $address['Location_Address_2'];
        $returnArray['city'] = $address['Location_City'];
        $returnArray['state'] = $address['state_code'];
        $returnArray['zip_code'] = $address['Location_ZipCode'];
        //
        return $returnArray;
    }

    /**
     * get the company bank account
     *
     * @param int $companyId
     * @return array
     */
    public function getCompanyBankAccount(int $companyId): array
    {
        return $this->db
            ->select('routing_number, hidden_account_number, verification_status, name, account_type')
            ->where('company_sid', $companyId)
            ->get('companies_bank_accounts')
            ->row_array();
    }

    /**
     * get filtered payroll employees
     *
     * @param int $companyId
     * @return array
     */
    public function getFilteredPayrollEmployees(int $companyId): array
    {
        $employees = $this->getPayrollEmployees($companyId);
        //
        if ($employees) {
            $employees = array_filter($employees, function ($employee) {
                return $employee['is_onboard'];
            });
        }
        //
        return $employees;
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
     * Get company payroll details
     * @param integer $companyId
     * @return
     */
    function GetPayrollCompany($companyId)
    {
        //
        return $this->db
            ->select('refresh_token, access_token, gusto_company_uid, onbording_level, onboarding_status')
            ->where('company_sid', $companyId)
            ->get('payroll_companies')
            ->row_array();
    }

    //
    public function updatePaymentConfiguration($table, $dataArray, $whereArray)
    {
        //
        $this->db
            ->where($whereArray)
            ->update($table, $dataArray);
    }

    /**
     * Get company primary Admin
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyPrimaryAdmin(int $companyId): array
    {
        $primaryAdmin = [];
        //
        $gustoPrimaryAdmin = $this->db
            ->select('first_name, last_name, email_address')
            ->where('company_sid', $companyId)
            ->where('is_store_admin', 1)
            ->get('gusto_companies_admin')
            ->row_array();
        //
        if (empty($gustoPrimaryAdmin)) {
            $defaultAdmin = $this->db
                ->select('first_name, last_name, email_address')
                ->where('company_sid', $companyId)
                ->get('gusto_companies_default_admin')
                ->row_array();
            //
            if (!empty($defaultAdmin)) {
                $defaultAdmin['is_sync'] = 0;
                $primaryAdmin = $defaultAdmin;
            } else {
                $primaryAdmin['first_name'] = 'Steven';
                $primaryAdmin['last_name'] = 'Warner';
                $primaryAdmin['email_address'] = 'steven@automotohr.com';
                $primaryAdmin['is_sync'] = 0;
            }
        } else {
            $gustoPrimaryAdmin['is_sync'] = 1;
            $primaryAdmin = $gustoPrimaryAdmin;
        }
        //
        return $primaryAdmin;
    }

    /**
     * add or update primary Admin
     *
     * @param int   $companyId
     * @param array   $post
     * @return array
     */
    public function addOrUpdatePrimaryAdmin(int $companyId, array $post): array
    {
        //
        if (
            $this->checkDefaultAdminExist($companyId)
        ) {
            $this->db
                ->where('company_sid', $companyId)
                ->update('gusto_companies_default_admin', $post);
            //
            return ["success" => true, "msg" => "Primary Admin update successfully"];
        } else {
            $this->db->insert(
                'gusto_companies_default_admin',
                [
                    'company_sid' => $companyId,
                    'email_address' => $post['email_address'],
                    'first_name' => $post['first_name'],
                    'last_name' => $post['last_name'],
                    'created_at' => getSystemDate(),
                ]
            );
            //
            return ["success" => true, "msg" => "Primary Admin save successfully"];
        }
    }

    /**
     * add or update primary Admin
     *
     * @param int   $companyId
     * @return int
     */
    public function checkDefaultAdminExist(int $companyId): int
    {
        return $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->from('gusto_companies_default_admin')
            ->count_all_results();
    }

    public function syncEmployeeStatus($employeeId, $employeeData)
    {
        //
        $companyId = getEmployeeUserParent_sid($employeeId);
        //
        $companyPayrollStatus = $this->GetCompanyPayrollStatus($companyId);
        $employeePayrollStatus = $this->checkEmployeePayrollStatus($employeeId, $companyId);
        //
        if ($companyPayrollStatus && $employeePayrollStatus) {
            // Call helper
            $this->load->helper("payroll_helper");
            //
            $companyDetails = $this->getGustoCompanyDetail($companyId);
            //
            $gustoEmployeeUUID = $this->getEmployeeGustoId($employeeId, $companyId);
            //
            foreach ($employeeData as $statusInfo) {
                if ($statusInfo['employee_status'] == 1) {
                    //
                    $employeeTerminateData = [];
                    $employeeTerminateData["effective_date"] = $statusInfo['effective_date'];
                    $employeeTerminateData["run_termination_payroll"] = '';
                    //
                    $response = createAnEmployeeTerminationOnGusto($employeeTerminateData, $companyDetails, $gustoEmployeeUUID, [
                        'X-Gusto-API-Version: 2024-03-01'
                    ]);
                    //
                    $this->updateEmployeeStatus($statusInfo['sid'], $response);
                    //
                } else if ($statusInfo['employee_status'] == 8) {
                    //
                    $gustoEmployeeWorkLocationId = $this->getEmployeeGustoWorkId($employeeId);
                    //
                    $employeeRehireData = [];
                    $employeeRehireData["effective_date"] = $statusInfo['effective_date'];
                    $employeeRehireData["file_new_hire_report"] = true;
                    $employeeRehireData["work_location_uuid"] = $gustoEmployeeWorkLocationId;
                    //
                    $response = createAnEmployeeRehireOnGusto($employeeRehireData, $companyDetails, $gustoEmployeeUUID, [
                        'X-Gusto-API-Version: 2024-03-01'
                    ]);
                    //
                    $this->updateEmployeeStatus($statusInfo['sid'], $response);
                    //
                }
            }
        }
        //
        return $response;
    }

    public function updateEmployeeStatusOnGusto($employeeId, $companyId, $employeeData)
    {
        //
        $companyPayrollStatus = $this->GetCompanyPayrollStatus($companyId);
        $employeePayrollStatus = $this->checkEmployeePayrollStatus($employeeId, $companyId);
        //
        if ($companyPayrollStatus && $employeePayrollStatus) {
            // Call helper
            $this->load->helper("payroll_helper");
            //
            $companyDetails = $this->getGustoCompanyDetail($companyId);
            //
            $gustoEmployeeUUID = $this->getEmployeeGustoId($employeeId, $companyId);
            //
            if ($employeeData['employee_status'] == 1) {
                //
                $employeeTerminateData = [];
                $employeeTerminateData["version"] = $employeeData['version'];
                $employeeTerminateData["effective_date"] = $employeeData['effective_date'];
                $employeeTerminateData["run_termination_payroll"] = '';
                //
                $response = updateAnEmployeeTerminationOnGusto($employeeTerminateData, $companyDetails, $gustoEmployeeUUID, [
                    'X-Gusto-API-Version: 2024-03-01'
                ]);
                //
                $this->updateEmployeeStatus($employeeData['sid'], $response);
                //
            } else if ($employeeData['employee_status'] == 8) {
                //
                $gustoEmployeeWorkLocationId = $this->getEmployeeGustoWorkId($employeeId);
                //
                $employeeRehireData = [];
                $employeeRehireData["version"] = $employeeData['version'];
                $employeeRehireData["effective_date"] = $employeeData['effective_date'];
                $employeeRehireData["file_new_hire_report"] = true;
                $employeeRehireData["work_location_uuid"] = $gustoEmployeeWorkLocationId;
                //
                $response = updateAnEmployeeRehireOnGusto($employeeRehireData, $companyDetails, $gustoEmployeeUUID, [
                    'X-Gusto-API-Version: 2024-03-01'
                ]);
                //
                $this->updateEmployeeStatus($employeeData['sid'], $response);
                //
            }
        }
        //
        return $response;
    }

    public function updateEmployeeStatus($rowId, $data)
    {
        $updateArray = [];
        $updateArray['payroll_version'] = $data['version'];
        $updateArray['payroll_object'] = serialize($data);
        //
        $this->db
            ->where('sid', $rowId)
            ->update('terminated_employees', $updateArray);
    }

    function GetCompanyPayrollStatus($companyId)
    {
        $this->db->select('is_active');
        $this->db->where('company_sid', $companyId);
        $this->db->where('module_sid', 7);

        $record_obj = $this->db->get('company_modules');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            if ($record_arr['is_active'] == 1) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function checkEmployeePayrollStatus($employeeId, $companyId)
    {
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('company_sid', $companyId);
        $this->db->from('gusto_companies_employees');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getGustoCompanyDetail($companyId)
    {
        //
        $this->db->select('refresh_token, access_token, gusto_uuid');
        $this->db->where('company_sid', $companyId);
        $record_obj = $this->db->get('gusto_companies');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    public function getEmployeeGustoId($employeeId, $companyId)
    {
        //
        $this->db->select('gusto_uuid');
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('company_sid', $companyId);
        $record_obj = $this->db->get('gusto_companies_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr['gusto_uuid'];
        } else {
            return '';
        }
    }

    public function getEmployeeGustoWorkId(int $employeeId)
    {
        //
        $this->db->select('gusto_location_uuid');
        $this->db->where('employee_sid', $employeeId);
        //
        $record_obj = $this->db->get('gusto_companies_employees_work_addresses');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr['gusto_location_uuid'];
        } else {
            return '';
        }
    }

    /**
     * check company onboarding status
     *
     * @param int $companyId
     * @return string
     */
    public function getCompanyOnboardingStatus(int $companyId): string
    {
        $status = 'Not Connected';
        //
        $record = $this->db
            ->select('status')
            ->where([
                'company_sid' => $companyId
            ])
            ->get('gusto_companies')
            ->row_array();
        //
        if (!empty($record)) {
            $status = $record['status'];
        }
        //
        return $status;
    }

    /**
     * Get company signatories info
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanySignatoriesInfo(int $companyId): array
    {
        //
        return $this->db
            ->select('first_name, last_name, email, title, ssn, phone, birthday, street_1, city, state, zip')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_signatories')
            ->row_array();
    }

    /**
     * Get company bank info
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyBankInfo(int $companyId): array
    {
        //
        return $this->db
            ->select('name, account_type, routing_number, hidden_account_number')
            ->where('company_sid', $companyId)
            ->get('companies_bank_accounts')
            ->row_array();
    }

    /**
     * Get company federal tax info
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyFederalTaxInfo(int $companyId): array
    {
        //
        return $this->db
            ->select('tax_payer_type, filing_form, legal_name, ein_verified')
            ->where('company_sid', $companyId)
            ->get('companies_federal_tax')
            ->row_array();
    }

    /**
     * Get company earning types
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyEarningTypesForDashboard(int $companyId): array
    {
        //
        return $this->db
            ->select('name')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_earning_types')
            ->result_array();
    }

    /**
     * Get company selected Industry
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanySelectedIndustry(int $companyId): array
    {
        //
        return $this->db
            ->select('title')
            ->where('company_sid', $companyId)
            ->get('companies_industry')
            ->row_array();
    }

    /**
     * Get company onboard employees
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyOnboardEmployees(int $companyId): array
    {
        //
        $employees = $this->db
            ->select('employee_sid, personal_details, compensation_details, work_address, home_address, federal_tax, state_tax, is_onboarded')
            ->where('company_sid', $companyId)
            ->get('gusto_companies_employees')
            ->result_array();
        //
        if (!empty($employees)) {
            foreach ($employees as $eKey => $employee) {
                $employees[$eKey]['name'] = getUserNameBySID($employee['employee_sid']);
            }
        }
        //
        return $employees;
    }

    /**
     * Get company onboard employees
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyTermConditionInfo(int $companyId): array
    {
        //
        return $this->db
            ->select('is_ts_accepted, ts_user_sid, ts_email, ts_ip')
            ->where('company_sid', $companyId)
            ->get('gusto_companies')
            ->row_array();
    }

    /**
     * Get company pay schedules
     *
     * @param int   $companyId
     * @return array
     */
    public function getCompanyPaySchedules(int $companyId): array
    {
        //
        return $this->db
            ->select('frequency, anchor_pay_date, anchor_end_of_pay_period, custom_name, active')
            ->where('company_sid', $companyId)
            ->get('companies_pay_schedules')
            ->result_array();
    }


    public function handleRateUpdateFromProfile($employeeId)
    {
        // let's check the employee
        $gustoEmployee = $this->db
            ->select('gusto_uuid, gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        // check and create
        if (!$gustoEmployee) {
            return [];
        }
        // get employee compensation
        $employee = $this->db
            ->select("
                parent_sid,
                payment_method,
                hourly_rate,
                hourly_technician,
                flat_rate_technician,
                semi_monthly_salary,
                semi_monthly_draw,
            ")
            ->where('sid', $employeeId)
            ->get("users")
            ->row_array();
        //
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($employee["parent_sid"]);
        // add employee gusto uuid
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        $companyDetails['company_sid'] = $employee["parent_sid"];
        //
        $data = [];
        //
        if ((int) $employee['hourly_rate'] != 0) {
            $data['amount'] = $employee['hourly_rate'];
            $data['classification'] = "Nonexempt";
            $data['per'] = "Hour";
        } elseif ((int) $employee['hourly_technician'] != 0) {
            $data['amount'] = $employee['hourly_technician'];
            $data['classification'] = "Nonexempt";
            $data['per'] = "Hour";
        } elseif ((int) $employee['flat_rate_technician'] != 0) {
            $data['amount'] = $employee['flat_rate_technician'];
            $data['classification'] = "Nonexempt";
            $data['per'] = "Hour";
        } elseif ((int) $employee['semi_monthly_salary'] != 0) {
            $data['amount'] = $employee['semi_monthly_salary'];
            $data['classification'] = "Exempt";
            $data['per'] = "Month";
        } elseif ((int) $employee['semi_monthly_draw'] != 0) {
            $data['amount'] = $employee['semi_monthly_draw'];
            $data['classification'] = "Exempt";
            $data['per'] = "Month";
        }
        $this->updateEmployeeCompensation($employeeId, $data);
    }

    /**
     * add employees to pay schedules
     *
     * @param array $post
     * @return array
     */
    public function updateEmployeePaySchedules(array $post): array
    {
        // get Gusto company details
        $gustoCompanyDetails = $this->getGustoCompanyDetail($post["companyId"]);
        // load schedule model
        $this->load->model("v1/Schedule_model", "schedule_model");
        //
        if (!$gustoCompanyDetails) {
            // add employees to AutomotoHR
            $this->schedule_model->linkEmployeesToPaySchedule($post);
            return [];
        }
        //
        $errors = [];
        //
        foreach ($post["data"] as $payScheduleId => $employeeIds) {
            // get the pay schedule UUID
            $gustoPaySchedule = $this->db
                ->select("gusto_uuid")
                ->where("sid", $payScheduleId)
                ->get("companies_pay_schedules")
                ->row_array();
            // when pay schedule not found
            if (!$gustoPaySchedule) {
                // add employees to AutomotoHR
                $this->schedule_model->linkEmployeesToPayScheduleById($payScheduleId, $employeeIds, $post["companyId"]);
                continue;
            }
            // prepare pass array
            $request = [];
            $request["type"] = "by_employee";
            $request["employees"] = [];
            // loop through the employees
            foreach ($employeeIds as $employeeId) {
                // get Gusto employee details
                $gustoEmployee = $this->getEmployeeDetailsForGusto(
                    $employeeId
                );
                // check if employee is on gusto
                if ($gustoEmployee) {
                    $request["employees"][] = [
                        "employee_uuid" => $gustoEmployee["gusto_uuid"],
                        "pay_schedule_uuid" => $employeeId
                    ];
                } else {
                    $this->schedule_model->linkEmployeeToPayScheduleById(
                        $payScheduleId,
                        $employeeId,
                        $post["companyId"]
                    );
                }
                // todo
                // when api start working fix the code below
                // remove it
                $this->schedule_model->linkEmployeeToPayScheduleById(
                    $payScheduleId,
                    $employeeId,
                    $post["companyId"]
                );
            }
            // todo
            // when api start working fix the code below
            // when employee list is not empty
            // if ($request["employees"]) {
            //     //
            //     $response = gustoCall(
            //         "assignEmployeesToPaySchedules",
            //         $gustoCompanyDetails,
            //         $request,
            //         "POST"
            //     );

            //     $hasErrors = hasGustoErrors($response);
            //     //
            //     if ($hasErrors) {
            //         $errors = array_merge($errors, $hasErrors["errors"]);
            //     }
            // }
        }


        return $errors ? ["errors" => $errors] : [];
    }

    /**
     * add employee to pay schedule
     *
     * @param array $post
     * @param int   $employeeId
     * @return array
     */
    public function updateEmployeePaySchedule(array $post, int $employeeId): array
    {
        // get Gusto company details
        $gustoCompanyDetails = $this->getGustoCompanyDetail($post["companyId"]);
        //
        if (!$gustoCompanyDetails) {
            return ["Company is not on Gusto."];
        }
        // get the pay schedule UUID
        $gustoPaySchedule = $this->db
            ->select("gusto_uuid")
            ->where("sid", $post["pay_schedule"])
            ->get("companies_pay_schedules")
            ->row_array();
        // when pay schedule not found
        if (!$gustoPaySchedule) {
            return ["Pay schedule is not on Gusto."];
        }
        // get Gusto employee details
        $gustoEmployee = $this->getEmployeeDetailsForGusto(
            $employeeId
        );
        if (!$gustoEmployee) {
            return ["Employee is not on Gusto."];
        }
        // prepare pass array
        $request = [];
        $request["type"] = "by_employee";
        $request["employees"] = $this->getGustoEmployeesOnPaySchedule($post["pay_schedule"], $gustoPaySchedule["gusto_uuid"]);
        $request["employees"][] =
            [
                "employee_uuid" => $gustoEmployee["gusto_uuid"],
                "pay_schedule_uuid" => $gustoPaySchedule["gusto_uuid"]
            ];
        // when employee list is not empty
        $response = gustoCall(
            "assignEmployeesToPaySchedules",
            $gustoCompanyDetails,
            $request,
            "POST"
        );

        $hasErrors = hasGustoErrors($response);
        //
        if ($hasErrors) {
            return $hasErrors;
        }

        return ["Employee is synced with Gusto."];
    }

    /**
     * get Gusto pay schedule employees
     *
     * @param int $payScheduleId
     * @param string $gustoUUID
     * @return array
     */
    public function getGustoEmployeesOnPaySchedule(int $payScheduleId, string $gustoUUID): array
    {
        // get the pay schedule UUID
        $records = $this->db
            ->select("gusto_companies_employees.gusto_uuid")
            ->join(
                "gusto_companies_employees",
                "gusto_companies_employees.employee_sid = employees_pay_schedule.employee_sid",
                "inner"
            )
            ->where("employees_pay_schedule.pay_schedule_sid", $payScheduleId)
            ->get("employees_pay_schedule")
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $returnArray = [];
        //
        foreach ($records as $v0) {
            $returnArray[] = [
                "employee_uuid" => $v0["gusto_uuid"],
                "pay_schedule_uuid" => $gustoUUID
            ];
        }
        return $returnArray;
    }

    public function syncEmployeeJobBeforeUpdate($employeeId, $companyDetails)
    {
        // sync employee jobs
        $this->syncEmployeeJobs($employeeId, $companyDetails);
    }

    public function regenerateAuthToken($isDemo = 0)
    {
        //
        $gustoDetails = getCreds("AHR")->GUSTO->DEMO;
        if (!$isDemo) {
            $gustoDetails = getCreds("AHR")->GUSTO->PRODUCTION;
        }
        //
        $dataToInsert = [];
        $dataToInsert['state'] = generateRandomString(5);
        $dataToInsert['is_production'] = "0";
        $dataToInsert['created_at'] = getSystemDate();
        //
        $this->db->insert('gusto_authorization', $dataToInsert);
        //
        $URL = "https://api.gusto" . ($isDemo ? "-demo" : "") . ".com/oauth/authorize?client_id=" . $gustoDetails->CLIENT_ID . "&redirect_uri=" . $gustoDetails->CALLBACK_URL . "&response_type=code&state=" . $dataToInsert['state'];
        //
        return $URL;
    }

    /**
     * handles employee profile update
     *
     * @param int $employeeId
     */
    public function handleUserUpdate(int $employeeId)
    {
        // let's check the employee
        $gustoEmployee = $this->db
            ->select('gusto_uuid, gusto_version')
            ->where('employee_sid', $employeeId)
            ->get('gusto_companies_employees')
            ->row_array();
        // check and create
        if (!$gustoEmployee) {
            return [];
        }
        // get employee compensation
        $employee = $this->db
            ->select("
                users.parent_sid,
                users.Location_Address,
                users.Location_Address_2,
                users.Location_City,
                states.state_code,
                users.Location_ZipCode
            ")
            ->join(
                "states",
                "states.sid = users.Location_State",
                "inner"
            )
            ->where('users.sid', $employeeId)
            ->get("users")
            ->row_array();
        //
        if (!$employee) {
            return false;
        }
        //
        // get company details
        $companyDetails = $this->getCompanyDetailsForGusto($employee["parent_sid"]);
        // add employee gusto uuid
        $companyDetails['other_uuid'] = $gustoEmployee['gusto_uuid'];
        $companyDetails['company_sid'] = $employee["parent_sid"];
        // handles address
        // check if address is complete
        if ($employee["Location_Address"] && $employee["Location_City"] && $employee["Location_ZipCode"]) {
            // prepare data array
            $data = [];
            $data["street_1"] = $employee["Location_Address"];
            $data["street_2"] = $employee["Location_Address_2"];
            $data["city"] = $employee["Location_City"];
            $data["zip"] = $employee["Location_ZipCode"];
            $data["state"] = $employee["state_code"];

            $this->updateEmployeeHomeAddress(
                $employeeId,
                $data
            );
        }

        return true;
    }

    private function checkIfEmployeePMIsDD(int $employeeId): int
    {
        // check if the employees payment method is Direct deposit
        return $this->db
            ->where([
                "employee_sid" => $employeeId,
                "type" => "Direct Deposit"
            ])
            ->count_all_results("gusto_employees_payment_method");
    }
}
