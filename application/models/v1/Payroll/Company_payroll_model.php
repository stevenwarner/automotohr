<?php defined('BASEPATH') || exit('No direct script access allowed');
loadUpModel("v1/Payroll/Base_payroll_model", "base_payroll_model");
/**
 * Company payroll model
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Company_payroll_model extends Base_payroll_model
{
    /**
     * holds the admin data
     * @var array
     */
    private $adminArray;

    /**
     * main function
     */
    public function __construct()
    {
        // set the admin
        $this->adminArray = [
            'first_name' => 'Steven',
            'last_name' => 'Warner',
            'email' => 'Steven@AutomotoHR.com',
            'phone' => '951-385-8204',
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
            ->select([
                "ssn",
                "Location_Address",
                "Location_City",
                "Location_State",
                "Location_ZipCode",
                "PhoneNumber"
            ])
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
            $r[] = 'Company phone number is missing.';
        }
        if (!phonenumber_validate($record['PhoneNumber'])) {
            $r[] = 'Company phone number is invalid.';
        }
        //
        return $r;
    }

    /**
     * create partner company on Gusto
     *
     * @param int $companyId
     * @param array $employees
     * @return array
     */
    public function startCreatePartnerCompany(int $companyId, array $employees): array
    {
        // load the library
        $this->initialize($companyId);
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
        // get the gusto company details
        $this->getGustoLinkedCompanyDetails(
            $companyId,
            [
                "company_sid"
            ]
        );
        // check and add company checklist
        $this->checkAndSetChecklist();
        //
        return $returnArray;
    }

    /**
     * get the company primary admin
     *
     * @param int $companyId
     * @return array
     */
    public function getPrimaryAdmin(int $companyId): array
    {
        // check if primary admin exists
        if (
            !$this
                ->db
                ->where("company_sid", $companyId)
                ->count_all_results("gusto_companies_default_admin")
        ) {
            return [
                "first_name" => $this->adminArray["first_name"],
                "last_name" => $this->adminArray["last_name"],
                "email" => $this->adminArray["email"],
                "phone" => $this->adminArray["phone"],
            ];
        }
        // get the primary admin
        $record = $this
            ->db
            ->select([
                "first_name",
                "last_name",
                "email_address",
            ])
            ->where("company_sid", $companyId)
            ->limit(1)
            ->get("gusto_companies_default_admin")
            ->row_array();
        //
        return [
            "first_name" => $record["first_name"],
            "last_name" => $record["last_name"],
            "email" => $record["email_address"],
            "phone" => "",
        ];
    }

    /**
     * get the company agreement
     *
     * @param int $companyId
     */
    public function signCompanyAgreement(
        int $companyId,
        array $data
    ): array {
        //
        $this->initialize($companyId);
        // get the gusto company details
        $this->getGustoLinkedCompanyDetails(
            $companyId,
            [
                "company_sid"
            ]
        );
        // set the request
        $request = [];
        $request['ip_address'] = getUserIP();
        $request['external_user_id'] = $data['userReference'];
        $request['email'] = $data['email'];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "agreement",
                $this->gustoCompany,
                $request,
                "POST"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        $this->db->where('company_sid', $companyId)
            ->update('gusto_companies', [
                'is_ts_accepted' => 1,
                'ts_email' => $request['email'],
                'ts_ip' => $request['ip_address'],
                'ts_user_sid' => $request['external_user_id'],
                "latest_terms_accepted" => $response["latest_terms_accepted"]
            ]);

        //
        $this->updateCompanyChecklist("agreement_count");

        return ["success" => true];
    }

    /**
     * add the sync job to queue
     *
     * @param int $companyId
     */
    public function addSyncJobToQueue(int $companyId)
    {
        $this
            ->db
            ->insert(
                "payrolls.gusto_sync_queue",
                [
                    "company_sid" => $companyId,
                    "created_at" => getSystemDate(),
                    "updated_at" => getSystemDate(),
                ]
            );
    }

    /**
     * Sync company
     * triggers from the webhook
     * Gusto To Store
     * 
     * @version 1.0
     */
    public function syncGustoToStore()
    {
        // sync the payroll blockers
        // $this->gustoToStorePayrollBlockers();
        // // // add the missing gusto uuid
        // $this->gustoToCompanyAdmins();
        // // // // sync the address
        // $this->gustoToStoreAddress();
        // // // // sync the earning types
        // $this->gustoToStoreEarningTypes();
        // // // // sync payment config
        // $this->gustoToStorePaymentConfig();
        // // // sync federal tax
        // $this->gustoToStoreFederalTax();
        // // // sync benefits
        // $this->gustoToStoreBenefits();
        // // // sync industry
        // $this->gustoToStoreIndustry();
        // // sync bank accounts
        // $this->gustoToStoreBankAccounts();
        // // sync signatories
        // $this->gustoToStoreSignatories();
        // // sync company forms
        // $this->gustoToStoreForms();

        $this->gustoToStoreMinimumWages();
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
        $companyDetails = $this
            ->db
            ->select([
                "users.CompanyName",
                "users.ssn",
                "users.Location_Address",
                "users.Location_City",
                "users.Location_State",
                "users.Location_ZipCode"
            ])
            ->where(
                'users.sid',
                $companyId
            )
            ->get('users')
            ->row_array();
        // check for SSN
        if (!$companyDetails['ssn']) {
            // set error
            $returnArray['errors'][] = '"Employer Identification Number (EIN)" is missing.';
        }
        // check for SSN
        if (strlen(preg_replace('/\D/', '', $companyDetails['ssn'])) != 9) {
            // set error
            $returnArray['errors'][] = '"Employer Identification Number (EIN)" must be 9 digits long.';
        }
        // Check if EIN is already used
        if (
            $this
            ->db
            ->where('ein', $companyDetails['ssn'])
            ->count_all_results('gusto_companies')
        ) {
            // set error
            $returnArray['errors'][] = '"Employer Identification Number (EIN)" is already in use.';
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
        //
        $request["user"] = $this->getPrimaryAdmin($companyId);
        // add company details
        $request['company']['name'] = $companyDetails['CompanyName'];
        $request['company']['trade_name'] = $companyDetails['CompanyName'];
        $request['company']['ein'] = $companyDetails['ssn'];
        //
        $response = $this
            ->lb_gusto
            ->createPartnerCompanyOnGusto(
                $request
            );
        // // set errors
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
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
            'success' => true,
            "response" => $response
        ];
    }

    /**
     * check and set company onboard checklist
     *
     * @return void
     */
    private function checkAndSetChecklist()
    {
        // check if record already added
        if (
            !$this
                ->db
                ->where("company_sid", $this->gustoCompany["company_sid"])
                ->count_all_results("payrolls.gusto_company_checklist")
        ) {
            // set the system date time
            $systemDateTime = getSystemDate();
            // insert the row
            $this
                ->db
                ->insert(
                    "payrolls.gusto_company_checklist",
                    [
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "created_at" => $systemDateTime,
                        "updated_at" => $systemDateTime
                    ]
                );
        }
    }


    // ------------------------------------------------------
    // Company Sync
    // ------------------------------------------------------
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
                    "company_sid"
                ],
                true,
                $column
            );
        //
        $this->initialize($companyId);
    }

    /**
     * get the payroll blockers from Gusto
     */
    public function gustoToStorePayrollBlockers()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payroll_blockers",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        // set insert array
        $ins = [
            "blocker_json" => json_encode(
                $response
            ),
            "updated_at" => getSystemDate()
        ];

        //
        if (
            !$this
                ->db
                ->where(
                    "company_sid",
                    $this->gustoCompany["company_sid"]
                )
                ->count_all_results(
                    "payrolls.payroll_blockers"
                )
        ) {
            //
            $ins["company_sid"] = $this->gustoCompany["company_sid"];
            $ins["created_at"] = $ins["updated_at"];
            //
            $this
                ->db
                ->insert(
                    "payrolls.payroll_blockers",
                    $ins
                );
        } else {
            //
            $this
                ->db
                ->where(
                    "company_sid",
                    $this->gustoCompany["company_sid"]
                )
                ->update(
                    "payrolls.payroll_blockers",
                    $ins
                );
        }

        return true;
    }


    // ------------------------------------------------------
    // Sync Admins
    // ------------------------------------------------------

    /**
     * sync Admins
     *
     * @method checkAndSetPrimaryAdmin
     * @method storeToGustoAdmins
     * @method gustoToCompanyAdmins
     * @return array
     */
    public function syncAdmins()
    {
        // check and add primary admin to company
        $this->checkAndSetPrimaryAdmin();
        // push the admins to Gusto
        $this->storeToGustoAdmins();
        // add the missing gusto uuid
        $this->gustoToCompanyAdmins();
    }

    /**
     * check and set the primary admin
     *
     * @return void
     */
    private function checkAndSetPrimaryAdmin()
    {
        // get the admin
        $admin = $this->getPrimaryAdmin(
            $this->gustoCompany["company_sid"]
        );
        //
        if (
            !$this
                ->db
                ->where([
                    "company_sid" => $this->gustoCompany["company_sid"],
                    "email_address" => $admin["email"],
                    "is_store_admin" => 1
                ])
                ->count_all_results("gusto_companies_admin")
        ) {
            // ste the system date and time
            $systemDateTime = getSystemDate();
            // get
            $this
                ->db
                ->insert(
                    "gusto_companies_admin",
                    [
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "first_name" => $admin["first_name"],
                        "last_name" => $admin["last_name"],
                        "email_address" => $admin["email"],
                        "is_store_admin" => 1,
                        "created_at" => $systemDateTime,
                        "updated_at" => $systemDateTime
                    ]
                );
        }
    }

    /**
     * push store admins to Gusto
     *
     * @return void
     */
    private function storeToGustoAdmins()
    {
        $records = $this
            ->db
            ->select([
                "sid",
                "first_name",
                "last_name",
                "email_address",
            ])
            ->where([
                "company_sid" => $this->gustoCompany["company_sid"],
                "is_store_admin" => 0,
                "gusto_uuid is null" => null,
            ])
            ->get("gusto_companies_admin")
            ->result_array();
        //
        if ($records) {
            //
            foreach ($records as $v) {
                // set the request
                $request = [
                    "first_name" => $v["first_name"],
                    "last_name" => $v["last_name"],
                    "email" => $v["email_address"],
                ];
                // make the call
                $response = $this
                    ->lb_gusto
                    ->gustoCall(
                        "admins",
                        $this->gustoCompany,
                        $request,
                        "POST"
                    );
                //
                if ($response["uuid"]) {
                    // update the main table
                    $this
                        ->db
                        ->where("sid", $v["sid"])
                        ->update(
                            "gusto_companies_admin",
                            [
                                "gusto_uuid" => $response["uuid"],
                                "updated_at" => getSystemDate()
                            ]
                        );
                    //
                    $this->updateCompanyChecklist("admin_count");
                }
            }
        }
    }

    /**
     * sync Gusto to store
     *
     * @return void
     */
    private function gustoToCompanyAdmins()
    {
        // make the call
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "admins",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if (!$errors && $response) {
            foreach ($response as $v) {
                // update the main table
                $this
                    ->db
                    ->where([
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "email_address" => $v["email"]
                    ])
                    ->update(
                        "gusto_companies_admin",
                        [
                            "gusto_uuid" => $v["uuid"],
                            "updated_at" => getSystemDate()
                        ]
                    );
            }
        }
    }


    // ------------------------------------------------------
    // Sync Company Address
    // ------------------------------------------------------

    /**
     * sync company address
     *
     * @method storeToGustoCompanyAddress
     * @method storeToGustoAdmins
     * @return array
     */
    public function syncCompanyAddress()
    {
        // check if company address is added or not
        if (
            !$this
                ->db
                ->where("company_sid", $this->gustoCompany["company_sid"])
                ->count_all_results("gusto_companies_locations")
        ) {
            // add the address
            $this->storeToGustoCompanyAddress();
        }
        // sync the address
        $this->gustoToStoreAddress();
    }

    /**
     * sync company address
     * store to Gusto
     *
     * @return array
     */
    private function storeToGustoCompanyAddress()
    {
        // get the company location
        $location = $this->db
            ->select([
                "users.Location_Address",
                "users.Location_City",
                "states.state_code",
                "users.Location_ZipCode",
                "users.PhoneNumber",
                "users.Location_Address_2",
            ])
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->where('users.sid', $this->gustoCompany["company_sid"])
            ->limit(1)
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
        $gustoResponse = $this
            ->lb_gusto
            ->gustoCall(
                'company_locations',
                $this->gustoCompany,
                $request,
                "POST"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($gustoResponse);
        // check for errors
        if ($errors) {
            return $errors;
        }
        // insert
        $this->db
            ->insert('gusto_companies_locations', [
                'company_sid' => $this->gustoCompany["company_sid"],
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
     * sync company address
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreAddress()
    {
        //
        $gustoResponse = $this
            ->lb_gusto
            ->gustoCall(
                'company_locations',
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($gustoResponse);
        // check for errors
        if ($errors) {
            return $errors;
        }
        //
        if ($gustoResponse) {
            foreach ($gustoResponse as $v0) {
                if (
                    $this
                    ->db
                    ->where([
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "gusto_uuid" => $v0['uuid']
                    ])
                    ->count_all_results("gusto_companies_locations")
                ) {
                    $this
                        ->db
                        ->update(
                            "gusto_companies_locations",
                            [
                                'gusto_version' => $v0['version'],
                                'is_active' => (int) $v0['active'],
                                'mailing_address' => (int) $v0['mailing_address'],
                                'filing_address' => (int) $v0['filing_address'],
                                'updated_at' => getSystemDate()
                            ]
                        );
                } else {
                    // insert
                    $this->db
                        ->insert('gusto_companies_locations', [
                            'company_sid' => $this->gustoCompany["company_sid"],
                            'gusto_uuid' => $v0['uuid'],
                            'gusto_version' => $v0['version'],
                            'is_active' => (int) $v0['active'],
                            'mailing_address' => (int) $v0['mailing_address'],
                            'filing_address' => (int) $v0['filing_address'],
                            'created_at' => getSystemDate(),
                            'updated_at' => getSystemDate()
                        ]);
                }
            }
            //
            $this->updateCompanyChecklist("location_count");
        }
        //
        return $gustoResponse;
    }

    // ------------------------------------------------------
    // Sync Company Earning Types
    // ------------------------------------------------------

    /**
     * sync company earning types
     *
     * @method storeToGustoEarningTypes
     * @method gustoToStoreEarningTypes
     * @return array
     */
    public function syncEarningTypes()
    {
        $this->storeToGustoEarningTypes();
        $this->gustoToStoreEarningTypes();
    }

    /**
     * sync company earning types
     * store to Gusto
     *
     * @return array
     */
    private function storeToGustoEarningTypes()
    {
        // get the earning types
        $earningTypes = $this
            ->lb_gusto
            ->getEarningTypes();
        //
        foreach ($earningTypes as $v0) {
            // check if earning type is already created
            if (
                !$this
                    ->db
                    ->where([
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "name" => $v0["name"]
                    ])
                    ->count_all_results("gusto_companies_earning_types")
            ) {
                //
                $this->createCompanyEarningTypeToGusto([
                    "name" => $v0["name"],
                    "fields_json" => $v0["fields_json"],
                ]);
            }
        }
        //
        $this->updateCompanyChecklist("earning_types_count");
    }

    /**
     * sync company earning types
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreEarningTypes()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "earning_types",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return ["errors" => ['No earning types found.']];
        }
        // Gusto provided
        if ($response["default"]) {
            //
            foreach ($response["default"] as $v0) {
                // check if earning type is already created
                if (
                    $this
                    ->db
                    ->where([
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "name" => $v0["name"]
                    ])
                    ->count_all_results("gusto_companies_earning_types")
                ) {
                    //
                    $upd = [];
                    $upd['gusto_uuid'] = $v0['uuid'];
                    $upd['is_active'] = $v0['active'];
                    $upd['updated_at'] = getSystemDate();
                    //
                    $this
                        ->db
                        ->where([
                            "company_sid" => $this->gustoCompany["company_sid"],
                            "name" => $v0["name"]
                        ])
                        ->update(
                            'gusto_companies_earning_types',
                            $upd
                        );
                } else {
                    //
                    $ins = [];
                    $ins['is_default'] = 0;
                    $ins['name'] = $v0['name'];
                    $ins['gusto_uuid'] = $v0['uuid'];
                    $ins['is_active'] = $v0['active'];
                    $ins['fields_json'] = json_encode([]);
                    $ins['updated_at'] = $ins['created_at'] = getSystemDate();
                    $ins['company_sid'] = $this->gustoCompany["company_sid"];
                    //
                    $this->db->insert('gusto_companies_earning_types', $ins);
                }
            }
        }

        // Store provided
        if ($response["custom"]) {
            //
            foreach ($response["custom"] as $v0) {
                // check if earning type is already created
                if (
                    $this
                    ->db
                    ->where([
                        "company_sid" => $this->gustoCompany["company_sid"],
                        "name" => $v0["name"]
                    ])
                    ->count_all_results("gusto_companies_earning_types")
                ) {
                    //
                    $upd = [];
                    $upd['gusto_uuid'] = $v0['uuid'];
                    $upd['is_active'] = $v0['active'];
                    $upd['updated_at'] = getSystemDate();
                    //
                    $this
                        ->db
                        ->where([
                            "company_sid" => $this->gustoCompany["company_sid"],
                            "name" => $v0["name"]
                        ])
                        ->update(
                            'gusto_companies_earning_types',
                            $upd
                        );
                } else {
                    //
                    $ins = [];
                    $ins['is_default'] = 0;
                    $ins['name'] = $v0['name'];
                    $ins['gusto_uuid'] = $v0['uuid'];
                    $ins['is_active'] = $v0['active'];
                    $ins['fields_json'] = json_encode([]);
                    $ins['updated_at'] = $ins['created_at'] = getSystemDate();
                    $ins['company_sid'] = $this->gustoCompany["company_sid"];
                    //
                    $this->db->insert('gusto_companies_earning_types', $ins);
                }
            }
        }
    }

    /**
     * add company earning types
     *
     * @param array $data
     * @return bool
     */
    private function createCompanyEarningTypeToGusto(
        array $data
    ): array {
        // response
        $response = $this
            ->lb_gusto
            ->gustoCall(
                'earning_types',
                $this->gustoCompany,
                [
                    'name' => $data['name']
                ],
                'POST'
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        $ins = [];
        $ins['is_default'] = 1;
        $ins['name'] = $response['name'];
        $ins['gusto_uuid'] = $response['uuid'];
        $ins['is_active'] = $response['active'];
        $ins['fields_json'] = $data['fields_json'];
        $ins['updated_at'] = $ins['created_at'] = getSystemDate();
        $ins['company_sid'] = $this->gustoCompany["company_sid"];
        //
        $this->db->insert('gusto_companies_earning_types', $ins);
        //
        return $response;
    }


    // ------------------------------------------------------
    // Sync Company Payment Config
    // ------------------------------------------------------
    /**
     * sync company payment config
     *
     * @method gustoToStorePaymentConfig
     * @method storeToGustoPaymentConfig
     * @return array
     */
    public function syncPaymentConfig()
    {
        $this->storeToGustoPaymentConfig();
        $this->gustoToStorePaymentConfig();
    }

    /**
     * sync company earning types
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStorePaymentConfig()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payment_configs",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return ["errors" => ['No payment config found.']];
        }
        //
        $data = [
            "fast_payment_limit" => $response["fast_payment_limit"],
            "payment_speed" => $response["payment_speed"],
            "updated_at" => getSystemDate()
        ];
        // check if already added
        if (
            !$this
                ->db
                ->where([
                    "company_sid" => $this->gustoCompany["company_sid"],
                ])
                ->count_all_results(
                    "companies_payment_configs"
                )
        ) {
            //
            $data["company_sid"] = $this->gustoCompany["company_sid"];
            $data["partner_uuid"] = $response["partner_uuid"];
            $data["created_at"] = $data["updated_at"];
            // insert
            $this
                ->db
                ->insert(
                    "companies_payment_configs",
                    $data
                );
        } else {
            // update
            $this
                ->db
                ->where([
                    "company_sid" => $this->gustoCompany["company_sid"],
                ])
                ->update(
                    "companies_payment_configs",
                    $data
                );
        }
    }

    /**
     * sync company earning types
     * Gusto to store
     *
     * @return array
     */
    private function storeToGustoPaymentConfig()
    {
        // set request
        $request = [
            "payment_speed" => "1-day"
        ];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "payment_configs",
                $this->gustoCompany,
                $request,
                "PUT"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return ["errors" => ['No payment config found.']];
        }
        //
        $data = [
            "fast_payment_limit" => $response["fast_payment_limit"],
            "payment_speed" => $response["payment_speed"],
            "updated_at" => getSystemDate()
        ];
        //
        $data["company_sid"] = $this->gustoCompany["company_sid"];
        $data["partner_uuid"] = $response["partner_uuid"];
        $data["created_at"] = $data["updated_at"];
        // insert
        $this
            ->db
            ->insert(
                "companies_payment_configs",
                $data
            );
    }

    // ------------------------------------------------------
    // Sync Company Federal Tax Details
    // ------------------------------------------------------
    /**
     * sync company federal tax
     *
     * @method storeToGustoCompanyAddress
     * @method storeToGustoAdmins
     * @return array
     */
    public function syncFederalTax()
    {
        $this->gustoToStoreFederalTax();
    }

    /**
     * sync company earning types
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreFederalTax()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "federal_tax_details",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return ["errors" => ['No payment config found.']];
        }
        //
        $data = [
            "gusto_version" => $response["version"],
            "tax_payer_type" => $response["tax_payer_type"],
            "taxable_as_scorp" => $response["taxable_as_scorp"],
            "filing_form" => $response["filing_form"],
            "has_ein" => $response["has_ein"],
            "ein_verified" => $response["ein_verified"],
            "effective_date" => $response["effective_date"],
            "deposit_schedule" => $response["deposit_schedule"],
            "legal_name" => $response["legal_name"],
            "updated_at" => getSystemDate()
        ];
        // check if already exists
        if (
            $this
            ->db
            ->where("company_sid", $this->gustoCompany["company_sid"])
            ->count_all_results("companies_federal_tax")
        ) {
            // update
            // insert
            $this
                ->db
                ->where(
                    "company_sid",
                    $this->gustoCompany["company_sid"]
                )
                ->update(
                    "companies_federal_tax",
                    $data
                );
        } else {
            // insert
            $data["company_sid"] = $this->gustoCompany["company_sid"];
            $data["created_at"] = $data["updated_at"];
            // insert
            $this
                ->db
                ->insert(
                    "companies_federal_tax",
                    $data
                );
        }
    }


    // ------------------------------------------------------
    // Sync benefits
    // ------------------------------------------------------
    /**
     * sync benefits
     *
     * @method storeToGustoCompanyAddress
     * @method storeToGustoAdmins
     * @return array
     */
    public function syncBenefits()
    {
        $this->gustoToStoreBenefits();
        $this->storeToGustoBenefits();
    }

    /**
     * sync benefits
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreBenefits()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "company_benefits",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        foreach ($response as $v0) {
            $data = [
                "gusto_uuid" => $v0["uuid"],
                "gusto_version" => $v0["version"],
                "benefit_type" => $v0["benefit_type"],
                "description" => $v0["description"],
                "active" => $v0["active"],
                "responsible_for_employer_taxes" =>
                $v0["responsible_for_employer_taxes"],
                "responsible_for_employee_w2" =>
                $v0["responsible_for_employee_w2"],
                "deletable" => $v0["deletable"],
                "supports_percentage_amounts" =>
                $v0["supports_percentage_amounts"],
                "updated_at" => getSystemDate()
            ];
            // check if already exists
            if (
                $this
                ->db
                ->where("company_sid", $this->gustoCompany["company_sid"])
                ->where("gusto_uuid", $v0["uuid"])
                ->count_all_results("payrolls.company_benefits")
            ) {
                // update
                $this
                    ->db
                    ->where(
                        "company_sid",
                        $this->gustoCompany["company_sid"]
                    )
                    ->where("gusto_uuid", $v0["uuid"])
                    ->update(
                        "payrolls.company_benefits",
                        $data
                    );
            } else {
                // insert
                $data["company_sid"] = $this->gustoCompany["company_sid"];
                $data["created_at"] = $data["updated_at"];
                // insert
                $this
                    ->db
                    ->insert(
                        "payrolls.company_benefits",
                        $data
                    );
            }
        }
        return true;
    }

    /**
     * sync benefits
     * store to Gusto
     *
     * @return array
     */
    private function storeToGustoBenefits()
    {
        // get the benefits
        $benefits = $this
            ->lb_gusto
            ->getBenefits();

        if (!$benefits) {
            return false;
        }
        //
        foreach ($benefits as $v0) {
            // check if not exists
            if (
                $this
                ->db
                ->where([
                    "company_sid" => $this->gustoCompany["company_sid"],
                    "benefit_type" => $v0["benefit_type"],
                    "description" => $v0["description"]
                ])
                ->limit(1)
                ->count_all_results("payrolls.company_benefits")
            ) {
                continue;
            }
            // prepare request array
            $request = [];
            $request['benefit_type'] = $v0["benefit_type"];
            $request['active'] = true;
            $request['description'] = $v0["description"];
            $request['responsible_for_employer_taxes'] =
                $v0["responsible_for_employer_taxes"];
            $request['responsible_for_employee_w2'] =
                $v0["responsible_for_employee_w2"];
            //
            $response = $this
                ->lb_gusto
                ->gustoCall(
                    "company_benefits",
                    $this->gustoCompany,
                    $request,
                    "POST"
                );
            //
            $errors = $this
                ->lb_gusto
                ->hasGustoErrors($response);
            //
            if ($errors) {
                return $errors;
            }
            //
            if (!$response) {
                return false;
            }
            //
            $ins = [];
            $ins['gusto_uuid'] = $response['uuid'];
            $ins['gusto_version'] = $response['version'];
            $ins['benefit_type'] = $response['benefit_type'];
            $ins['description'] = $response['description'];
            $ins['active'] = $response['active'];
            $ins['responsible_for_employer_taxes'] =
                $response['responsible_for_employer_taxes'];
            $ins['responsible_for_employee_w2'] =
                $response['responsible_for_employee_w2'];
            $ins['deletable'] = $response['deletable'];
            $ins['supports_percentage_amounts'] =
                $response['supports_percentage_amounts'];
            $ins['company_sid'] = $this->gustoCompany["company_sid"];
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->db->insert(
                'payrolls.company_benefits',
                $ins
            );
            usleep(200);
        }

        return true;
    }

    // ------------------------------------------------------
    // Sync industry
    // ------------------------------------------------------
    /**
     * sync industry
     *
     * @method storeToGustoCompanyAddress
     * @method storeToGustoAdmins
     * @return array
     */
    public function syncIndustry()
    {
        $this->gustoToStoreIndustry();
        $this->storeToGustoIndustry();
    }

    /**
     * sync benefits
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreIndustry()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "industry",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $dataArray = [];
        $dataArray['naics_code'] = $response['naics_code'];
        $dataArray['sic_codes'] = json_encode($response['sic_codes']);
        $dataArray['title'] = $response['title'];
        $dataArray['updated_at'] = getSystemDate();
        //
        $whereArray = [
            'company_sid' => $this->gustoCompany["company_sid"]
        ];
        //
        if (
            !$this
                ->db
                ->where($whereArray)
                ->count_all_results("gusto_company_industry")
        ) {
            //
            $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
            $dataArray['created_at'] =
                $dataArray['updated_at'];
            //
            $this
                ->db
                ->insert(
                    "gusto_company_industry",
                    $dataArray
                );
        } else {
            //
            $this
                ->db
                ->where($whereArray)
                ->update(
                    "gusto_company_industry",
                    $dataArray
                );
        }

        return true;
    }

    /**
     * sync benefits
     * store to Gusto
     *
     * @return array
     */
    private function storeToGustoIndustry()
    {
        // prepare request array
        $request = [];
        $request['title'] = "New Car Dealers";
        $request['naics_code'] = "441110";
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "industry",
                $this->gustoCompany,
                $request,
                "PUT"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $dataArray = [];
        $dataArray['naics_code'] = $response['naics_code'];
        $dataArray['sic_codes'] = json_encode($response['sic_codes']);
        $dataArray['title'] = $response['title'];
        $dataArray['updated_at'] = getSystemDate();
        //
        $whereArray = [
            'company_sid' => $this->gustoCompany["company_sid"]
        ];
        //
        if (
            !$this
                ->db
                ->where($whereArray)
                ->count_all_results("gusto_company_industry")
        ) {
            //
            $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
            $dataArray['created_at'] =
                $dataArray['updated_at'];
            //
            $this
                ->db
                ->insert(
                    "gusto_company_industry",
                    $dataArray
                );
        } else {
            //
            $this
                ->db
                ->where($whereArray)
                ->update(
                    "gusto_company_industry",
                    $dataArray
                );
        }

        return true;
    }

    // ------------------------------------------------------
    // Sync bank accounts
    // ------------------------------------------------------
    /**
     * sync bank accounts
     *
     * @method gustoToStoreBankAccounts
     * @return array
     */
    public function syncBankAccounts()
    {
        $this->storeToGustoBankAccounts();
        $this->gustoToStoreBankAccounts();

        // check if the company is a demo one
        if (
            !$this
                ->db
                ->where([
                    "company_sid" => $this->gustoCompany["company_sid"],
                    "stage" => "production",
                ])
                ->count_all_results("gusto_companies_mode")
        ) {
            // start the bank account verification process
            $this->verifyBankAccounts();
            $this->gustoToStoreBankAccounts();
        }
    }

    /**
     * sync bank accounts
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreBankAccounts()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "bank_accounts",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $systemDateTime = getSystemDate();
        //
        foreach ($response as $v0) {
            //
            $dataArray = [];
            $dataArray['gusto_uuid'] = $v0['uuid'];
            $dataArray['account_type'] = $v0['account_type'];
            $dataArray['routing_number'] = $v0['routing_number'];
            $dataArray['hidden_account_number'] = $v0['hidden_account_number'];
            $dataArray['verification_status'] = $v0['verification_status'];
            $dataArray['verification_type'] = $v0['verification_type'];
            $dataArray['plaid_status'] = $v0['plaid_status'] ?? null;
            $dataArray['last_cached_balance'] = $v0['last_cached_balance'] ?? null;
            $dataArray['balance_fetched_date'] = $v0['balance_fetched_date'] ?? null;
            $dataArray['name'] = $v0['name'];
            $dataArray['updated_at'] = $systemDateTime;
            //
            $whereArray = [
                'company_sid' => $this->gustoCompany["company_sid"],
                'gusto_uuid' => $v0["uuid"]
            ];
            //
            if (
                !$this
                    ->db
                    ->where($whereArray)
                    ->count_all_results("gusto_company_bank_accounts")
            ) {
                //
                $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
                $dataArray['created_at'] = $systemDateTime;
                //
                $this
                    ->db
                    ->insert(
                        "gusto_company_bank_accounts",
                        $dataArray
                    );
            } else {
                //
                $this
                    ->db
                    ->where($whereArray)
                    ->update(
                        "gusto_company_bank_accounts",
                        $dataArray
                    );
            }
        }

        return true;
    }

    /**
     * sync bank accounts
     * Store to Gusto
     *
     * @return array
     */
    private function storeToGustoBankAccounts()
    {
        // check and get the bank accounts
        $records = $this
            ->db
            ->select([
                "sid",
                "account_type",
                "routing_number",
                "account_number",
            ])
            ->where([
                "gusto_uuid is null" => null,
                "company_sid" =>
                $this->gustoCompany["company_sid"]
            ])
            ->get("gusto_company_bank_accounts")
            ->result_array();
        //
        if (!$records) {
            return false;
        }
        //
        foreach ($records as $record) {
            //
            $request = [
                "routing_number" => $record["routing_number"],
                "account_number" => $record["account_number"],
                "account_type" => $record["account_type"],
            ];
            //
            $response = $this
                ->lb_gusto
                ->gustoCall(
                    "bank_accounts",
                    $this->gustoCompany,
                    $request,
                    "POST"
                );
            //
            $errors = $this
                ->lb_gusto
                ->hasGustoErrors($response);
            //
            if ($errors) {
                return $errors;
            }
            //
            if (!$response) {
                return false;
            }
        }
        //
        $dataArray = [];
        $dataArray['gusto_uuid'] = $response['uuid'];
        $dataArray['account_type'] = $response['account_type'];
        $dataArray['routing_number'] = $response['routing_number'];
        $dataArray['hidden_account_number'] = $response['hidden_account_number'];
        $dataArray['verification_status'] = $response['verification_status'];
        $dataArray['verification_type'] = $response['verification_type'];
        $dataArray['plaid_status'] = $response['plaid_status'] ?? null;
        $dataArray['last_cached_balance'] = $response['last_cached_balance'] ?? null;
        $dataArray['balance_fetched_date'] = $response['balance_fetched_date'] ?? null;
        $dataArray['name'] = $response['name'];
        $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
        $dataArray['updated_at'] = getSystemDate();
        //
        $this
            ->db
            ->where("sid", $record["sid"])
            ->update(
                "gusto_company_bank_accounts",
                $dataArray
            );
        //
        return true;
    }

    /**
     * verify bank accounts
     *
     * @return array
     */
    private function verifyBankAccounts()
    {
        // check and get the bank accounts
        $records = $this
            ->db
            ->select([
                "gusto_uuid",
            ])
            ->where([
                "gusto_uuid is not null" => null,
                "company_sid" =>
                $this->gustoCompany["company_sid"]
            ])
            ->get("gusto_company_bank_accounts")
            ->result_array();
        //
        if (!$records) {
            return false;
        }
        //
        foreach ($records as $record) {

            //
            $this->gustoCompany["other_uuid"]
                = $record["gusto_uuid"];
            //
            $response = $this
                ->lb_gusto
                ->gustoCall(
                    "send_test_deposits",
                    $this->gustoCompany,
                    [],
                    "POST"
                );
            //
            $errors = $this
                ->lb_gusto
                ->hasGustoErrors($response);
            //
            if ($errors) {
                continue;
            }
            //
            if (!$response) {
                continue;
            }
            //
            $response2 = $this
                ->lb_gusto
                ->gustoCall(
                    "verify_bank_Account",
                    $this->gustoCompany,
                    [
                        "deposit_1" => $response["deposit_1"],
                        "deposit_2" => $response["deposit_2"]
                    ],
                    "PUT"
                );
            //
            $errors = $this
                ->lb_gusto
                ->hasGustoErrors($response2);
            //
            if ($errors) {
                continue;
            }
            //
            if (!$response2) {
                continue;
            }
        }
    }

    // ------------------------------------------------------
    // Sync signatories
    // ------------------------------------------------------
    /**
     * sync signatories
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreSignatories()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "signatories",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $systemDateTime = getSystemDate();
        //
        foreach ($response as $v0) {
            //
            $dataArray = [];
            $dataArray['gusto_uuid'] = $v0['uuid'];
            $dataArray['gusto_version'] = $v0['version'];
            $dataArray['title'] = $v0['title'];
            $dataArray['first_name'] = $v0['first_name'];
            $dataArray['last_name'] = $v0['last_name'];
            $dataArray['email'] = $v0['email'];
            $dataArray['phone'] = $v0['phone'];
            $dataArray['birthday'] = $v0['birthday'];
            $dataArray['identity_verification_status'] = $v0['identity_verification_status'];
            $dataArray['street_1'] = $v0["home_address"]['street_1'];
            $dataArray['street_2'] = $v0["home_address"]['street_2'];
            $dataArray['city'] = $v0["home_address"]['city'];
            $dataArray['state'] = $v0["home_address"]['state'];
            $dataArray['zip'] = $v0["home_address"]['zip'];
            $dataArray['updated_at'] = $systemDateTime;
            //
            $whereArray = [
                'company_sid' => $this->gustoCompany["company_sid"],
                'gusto_uuid' => $v0["uuid"]
            ];
            //
            if (
                !$this
                    ->db
                    ->where($whereArray)
                    ->count_all_results("gusto_companies_signatories")
            ) {
                //
                $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
                $dataArray['created_at'] = $systemDateTime;
                //
                $this
                    ->db
                    ->insert(
                        "gusto_companies_signatories",
                        $dataArray
                    );
            } else {
                //
                $this
                    ->db
                    ->where($whereArray)
                    ->update(
                        "gusto_companies_signatories",
                        $dataArray
                    );
            }
        }

        return true;
    }

    // ------------------------------------------------------
    // Sync forms
    // ------------------------------------------------------
    /**
     * sync forms
     * Gusto to store
     *
     * @return array
     */
    private function gustoToStoreForms()
    {
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "forms",
                $this->gustoCompany,
                [],
                "GET"
            );
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
        //
        $systemDateTime = getSystemDate();
        //
        foreach ($response as $v0) {
            //
            $this->gustoCompany["other_uuid"]
                = $v0["uuid"];
            //
            $gustoFormPDF =
                $this
                ->lb_gusto
                ->gustoCall(
                    "forms_pdf",
                    $this->gustoCompany,
                    [],
                    "GET"
                );
            //
            $errors = $this
                ->lb_gusto
                ->hasGustoErrors($response);
            //
            if ($errors) {
                return $errors;
            }
            //
            if (!$response) {
                return false;
            }
            //
            $dataArray = [];
            $dataArray['gusto_uuid'] = $v0['uuid'];
            $dataArray['form_name'] = $v0['name'];
            $dataArray['form_title'] = $v0['title'];
            $dataArray['form_content'] = $v0['description'];
            $dataArray['draft'] = $v0['draft'];
            $dataArray['year'] = $v0['year'] ? $v0["year"] : null;
            $dataArray['quarter'] = $v0['quarter'] ? $v0["quarter"] : null;
            $dataArray['requires_signing'] = $v0['requires_signing'];
            $dataArray['updated_at'] = $systemDateTime;
            //
            $whereArray = [
                'company_sid' => $this->gustoCompany["company_sid"],
                'gusto_uuid' => $v0["uuid"]
            ];
            // copy the company form from Gusto
            // to store and make it private
            $fileObject = copyFileFromUrlToS3(
                $gustoFormPDF["document_url"],
                $v0["name"],
                "",
                "private"
            );
            // set the unsigned file
            $dataArray['s3_form'] = $fileObject["s3_file_name"];
            //
            if (
                !$this
                    ->db
                    ->where($whereArray)
                    ->count_all_results("gusto_company_forms")
            ) {
                //
                $dataArray['company_sid'] = $this->gustoCompany["company_sid"];
                $dataArray['created_at'] = $systemDateTime;
                //
                $this
                    ->db
                    ->insert(
                        "gusto_company_forms",
                        $dataArray
                    );
            } else {
                //
                $this
                    ->db
                    ->where($whereArray)
                    ->update(
                        "gusto_company_forms",
                        $dataArray
                    );
            }
        }

        return true;
    }

    // ------------------------------------------------------
    // Sync Minimum Wages
    // ------------------------------------------------------

    /**
     * sync Minimum Wages
     *
     * @return array
     */
    public function gustoToStoreMinimumWages()
    {
        // get the locations
        $record
            = $this->db
            ->select([
                "states.state_code",
            ])
            ->join('states', 'states.sid = users.Location_State', 'left')
            ->join('gusto_companies_locations', 'states.sid = users.Location_State', 'left')
            ->where('users.sid', $this->gustoCompany["company_sid"])
            ->limit(1)
            ->get('users')
            ->row_array();
        //
        if (!$record) {
            return false;
        }
        //
        $this->gustoCompany["other_uuid"]
            = $record["state_code"];
        //
        $response = $this
            ->lb_gusto
            ->gustoCall(
                "minimum_wages",
                $this->gustoCompany,
                [],
                "GET"
            );
        _e($response);
        //
        $errors = $this
            ->lb_gusto
            ->hasGustoErrors($response);
        //
        if ($errors) {
            return $errors;
        }
        //
        if (!$response) {
            return false;
        }
    }
}
